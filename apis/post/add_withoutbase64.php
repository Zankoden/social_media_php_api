<?php
// Enable CORS headers for cross-origin requests
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Set the response content type to JSON
header('Content-Type: application/json');

// Include your database connection file
require_once('../../database/db.php');

// Function to save uploaded file
function saveUploadedFile($file, $userId) {
    $uploadDir = '../../uploads/';
    $filename = $userId . '_' . time() . '_' . basename($file['name']);
    $uploadFile = $uploadDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
        return $filename;
    }
    return null;
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate input
    if (!isset($_POST['user_id'], $_POST['category_id'], $_POST['title'], $_POST['description'])) {
        http_response_code(400); // Bad Request
        echo json_encode(["status" => "error", "message" => "All fields are required"]);
        exit;
    }

    // Extract data from the POST request
    $userId = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;
    $categoryId = isset($_POST['category_id']) ? intval($_POST['category_id']) : null;
    $title = isset($_POST['title']) ? trim($_POST['title']) : null;
    $description = isset($_POST['description']) ? trim($_POST['description']) : null;
    $content = null;

    if (isset($_FILES['content']) && $_FILES['content']['error'] == UPLOAD_ERR_OK) {
        $content = saveUploadedFile($_FILES['content'], $userId);
        if (!$content) {
            http_response_code(500); // Internal Server Error
            echo json_encode(["status" => "error", "message" => "Failed to save the file."]);
            exit;
        }
    }

    try {
        // Start a transaction
        $conn->begin_transaction();

        // Prepare the SQL statement
        $sql = "INSERT INTO POSTS (USER_ID, CATEGORY_ID, TITLE, DESCRIPTION, CONTENT) VALUES (?, ?, ?, ?, ?)";

        // Prepare and bind the statement
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisss", $userId, $categoryId, $title, $description, $content);

        // Execute the statement
        $stmt->execute();

        // Commit the transaction
        $conn->commit();

        // Get the ID of the newly inserted post
        $lastId = $stmt->insert_id;

        if ($lastId > 0) {
            http_response_code(201); // Created
            echo json_encode(["status" => "success", "message" => "Post created successfully", "postId" => $lastId]);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(["status" => "error", "message" => "Failed to create post"]);
        }
    } catch (\Exception $e) {
        // Rollback in case of error
        $conn->rollback();
        http_response_code(500); // Internal Server Error
        echo json_encode(["status" => "error", "message" => "Database operation failed: " . $e->getMessage()]);
    } finally {
        $stmt->close();
        $conn->close(); // Close the database connection
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

