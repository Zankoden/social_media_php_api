<?php
// Enable CORS headers for cross-origin requests
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Set the response content type to JSON
header('Content-Type: application/json');

// Include your database connection file
require_once('../../database/db.php');

function saveBase64File($base64Content, $userId)
{
    // Extract the file type and base64 data
    if (preg_match('/^data:([a-zA-Z0-9]+\/[a-zA-Z0-9-.+]+);base64,/', $base64Content, $matches)) {
        $mimeType = $matches[1];
        $base64Data = substr($base64Content, strpos($base64Content, ',') + 1);
        $decodedData = base64_decode($base64Data);

        // Get file extension from mime type
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'application/pdf' => 'pdf',
            // Add more mime types and their corresponding extensions as needed
        ];
        $extension = isset($extensions[$mimeType]) ? $extensions[$mimeType] : 'bin';

        $filename = $userId  . '_' . time() . '.' . $extension;
        $upload_path = '../../uploads/' . $filename;

        if (file_put_contents($upload_path, $decodedData)) {
            return $filename;
        }
    }
    return null;
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get raw POST data
    $rawData = file_get_contents("php://input");

    // Decode the JSON data
    $data = json_decode($rawData, true);

    // Debug: Log the incoming data
    file_put_contents("php://stderr", print_r($data, true));

    // Validate input
    if (!isset($data['user_id'], $data['category_id'], $data['title'], $data['description'], $data['content'])) {
        http_response_code(400); // Bad Request
        echo json_encode(["status" => "error", "message" => "All fields are required"]);
        exit;
    }

    // Extract data from the decoded JSON
    $userId = isset($data['user_id']) ? intval($data['user_id']) : null;
    $categoryId = isset($data['category_id']) ? intval($data['category_id']) : null;
    $title = isset($data['title']) ? trim($data['title']) : null;
    $description = isset($data['description']) ? trim($data['description']) : null;
    $content = isset($data['content']) ? $data['content'] : null;

    try {
        // Start a transaction
        $conn->begin_transaction();

        // Handle file upload
        if (!empty($content) && strpos($content, ';base64,') !== false) {
            $filename = saveBase64File($content, $userId);
            if ($filename) {
                $content = $filename;
            } else {
                throw new Exception("Failed to save the file.");
            }
        }

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
