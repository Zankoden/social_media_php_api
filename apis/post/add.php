<?php
// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Methods: POST");
// header("Access-Control-Allow-Headers: Content-Type");

// header('Content-Type: application/json');

// require_once('../../database/db.php');

// if ($_SERVER["REQUEST_METHOD"] !== "POST") {
//     http_response_code(405);
//     echo json_encode(["status" => "error", "message" => "Method not allowed"]);
//     exit;
// }


// if (isset($_POST['post_id'], $_POST['user_id'], $_POST['content'])) {
//     http_response_code(400);
//     echo json_encode(["status" => "error", "message" => "All fields are required"]);
//     exit;
// }
// $postId = isset($_POST['post_id']) ? intval($_POST['post_id']) : null;
// $userId = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;
// $content = isset($_POST['content']) ? trim($_POST['content']) : null;

// try {
//     $conn->begin_transaction();

//     $sql = "SELECT * FROM posts WHERE id =?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("i", $postId);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $post = $result->fetch_assoc();

//     if (!$post || $post['user_id'] !== $userId) {
//         throw new Exception("Post not found or you are not the owner.");
//     }

//     $sql = "INSERT INTO COMMENTS (POST_ID, USER_ID, CONTENT) VALUES (?,?,?)";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("iis", $postId, $userId, $content);

//     $stmt->execute();

//     $lastId = $stmt->insert_id;

//     if ($lastId > 0) {
//         http_response_code(201);
//         echo json_encode(["status" => "success", "message" => "Comment added successfully", "commentId" => $lastId]);
//     } else {
//         http_response_code(500);
//         echo json_encode(["status" => "error", "message" => "Failed to add comment"]);
//     }
// } catch (\Exception $e) {
//     $conn->rollback();
//     http_response_code(500);
//     echo json_encode(["status" => "error", "message" => "Database operation failed: " . $e->getMessage()]);
// } finally {
//     $stmt->close();
//     $conn->close();
// }

// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Methods: POST");
// header("Content-Type: application/json");

// require_once('../../database/db.php');

// if ($_SERVER["REQUEST_METHOD"] !== "POST") {
//     http_response_code(405);
//     echo json_encode(["status" => "error", "message" => "Method not allowed"]);
//     exit;
// }
// echo $_POST['$postId'];
// echo $_POST['$categoryId'];
// echo $_POST['$title'];
// echo $_POST['$description'];
// echo $_POST['$content'];

// if (!isset($_POST['post_id'], $_POST['category_id'], $_POST['title'], $_POST['description'], $_POST['content'])) {
//     http_response_code(400);
//     echo json_encode([
//         "status" => "error",
//         "message" => "Missing required parameters: post_id, category_id, title, description, and content."
//     ]);
//     exit;
// }

// $postId = intval($_POST['post_id']);
// $categoryId = intval($_POST['category_id']);
// $title = trim($_POST['title']);
// $description = trim($_POST['description']);
// $content = trim($_POST['content']);

// echo $postId;
// echo $categoryId;
// echo $title;
// echo $description;
// echo $content;



// if ($postId <= 0 || $categoryId <= 0 || empty($title) || empty($description) || empty($content)) {
//     http_response_code(400);
//     echo json_encode(["status" => "error", "message" => "Invalid input data"]);
//     exit;
// }

// try {
//     $conn->begin_transaction();

//     // First, get the existing post data
//     $sqlSelect = "SELECT USER_ID, CONTENT FROM POSTS WHERE ID = ?";
//     $stmtSelect = $conn->prepare($sqlSelect);
//     $stmtSelect->bind_param("i", $postId);
//     $stmtSelect->execute();
//     $result = $stmtSelect->get_result();
//     $existingPost = $result->fetch_assoc();
//     $stmtSelect->close();

//     if (!$existingPost) {
//         throw new Exception("Post not found");
//     }

//     $userId = $existingPost['USER_ID'];
//     $oldImageName = $existingPost['CONTENT'];

//     // Handle image upload if a new image is provided
//     if (isset($_FILES['image'])) {
//         $uploadDir = '../../uploads/';
//         $imageFile = $_FILES['image'];
//         $fileExtension = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
//         $newFileName = $userId . '_' . date('YmdHis') . '.' . $fileExtension;
//         $uploadPath = $uploadDir . $newFileName;

//         if (!move_uploaded_file($imageFile['tmp_name'], $uploadPath)) {
//             throw new Exception("Failed to upload new image");
//         }

//         // Delete the old image file
//         if (file_exists($uploadDir . $oldImageName)) {
//             unlink($uploadDir . $oldImageName);
//         }
//     } else {
//         $newFileName = $oldImageName;
//     }

//     $sql = "UPDATE POSTS SET CATEGORY_ID=?, TITLE=?, DESCRIPTION=?, CONTENT=? WHERE ID=?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("isssi", $categoryId, $title, $description, $newFileName, $postId);

//     if ($stmt->execute()) {
//         $conn->commit();
//         http_response_code(200);
//         echo json_encode(["status" => "success", "message" => "Post updated successfully"]);
//     } else {
//         throw new Exception("Failed to update post");
//     }
// } catch (Exception $e) {
//     $conn->rollback();
//     // If a new image was uploaded but the update failed, delete it
//     if (isset($uploadPath) && file_exists($uploadPath)) {
//         unlink($uploadPath);
//     }
//     http_response_code(500);
//     echo json_encode(["status" => "error", "message" => "Operation failed: " . $e->getMessage()]);
// } finally {
//     if (isset($stmt)) $stmt->close();
//     $conn->close();
// }

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
    // Validate input
    if (!isset($_POST['user_id'], $_POST['category_id'], $_POST['title'], $_POST['description'], $_POST['content'])) {
        http_response_code(400); // Bad Request
        echo json_encode(["status" => "error", "message" => "All fields are required"]);
        exit;
    }

    // Extract data from the POST request
    $userId = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;
    $categoryId = isset($_POST['category_id']) ? intval($_POST['category_id']) : null;
    $title = isset($_POST['title']) ? trim($_POST['title']) : null;
    $description = isset($_POST['description']) ? trim($_POST['description']) : null;
    $content = isset($_POST['content']) ? $_POST['content'] : null;

    try {
        // Start a transaction
        $conn->begin_transaction();

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
