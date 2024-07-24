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

        $filename = $userId . '_' . time() . '.' . $extension;
        $upload_path = '../../uploads/' . $filename;

        if (file_put_contents($upload_path, $decodedData)) {
            return $filename;
        }
    }
    return null;
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate input
    if (!isset($_POST['category_id'], $_POST['title'], $_POST['description'], $_POST['content'], $_POST['post_id'])) {
        http_response_code(400); // Bad Request
        echo json_encode(["status" => "error", "message" => "All fields are required"]);
        exit;
    }

    // Extract data from the POST request
    // $postId = isset($_POST['id']) ? intval($_POST['id']) : null;
    $categoryId = isset($_POST['category_id']) ? intval($_POST['category_id']) : null;
    $title = isset($_POST['title']) ? trim($_POST['title']) : null;
    $description = isset($_POST['description']) ? trim($_POST['description']) : null;
    $content = isset($_POST['content']) ? $_POST['content'] : null;
    $postId = isset($_POST['post_id']) ? intval($_POST['post_id']) : null;

    try {
        // Start a transaction
        $conn->begin_transaction();

        // Fetch the existing post to get the user_id
        $fetch_sql = "SELECT USER_ID, CONTENT FROM POSTS WHERE ID = ?";
        $fetch_stmt = $conn->prepare($fetch_sql);
        $fetch_stmt->bind_param("i", $postId);
        $fetch_stmt->execute();
        $result = $fetch_stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $userId = $row['USER_ID'];

            // Handle file upload
            if (isset($_POST['content']) && !empty($_POST['content'])) {
                $content = $_POST['content'];

                // Check if the content is a base64 file
                if (strpos($content, ';base64,') !== false) {
                    $filename = saveBase64File($content, $userId);
                    if ($filename) {
                        $content = $filename;
                    } else {
                        throw new Exception("Failed to save the file.");
                    }
                }
            }

            // Prepare the SQL statement
            $sql = "UPDATE POSTS SET CATEGORY_ID = ?, TITLE = ?, DESCRIPTION = ?, CONTENT = ? WHERE ID = ?";

            // Prepare and bind the statement
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssi", $categoryId, $title, $description, $content, $postId);

            // Execute the statement
            $stmt->execute();

            // Commit the transaction
            $conn->commit();

            if ($stmt->affected_rows > 0) {
                http_response_code(200); // OK
                echo json_encode(["status" => "success", "message" => "Post updated successfully"]);
            } else {
                http_response_code(404); // Not Found
                echo json_encode(["status" => "error", "message" => "Post not found or no changes made"]);
            }
        } else {
            http_response_code(404); // Not Found
            echo json_encode(["status" => "error", "message" => "Post not found"]);
        }
    } catch (\Exception $e) {
        // Rollback in case of error
        $conn->rollback();
        http_response_code(500); // Internal Server Error
        echo json_encode(["status" => "error", "message" => "Database operation failed: " . $e->getMessage()]);
    } finally {
        $fetch_stmt->close();
        $stmt->close();
        $conn->close(); // Close the database connection
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
