<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE");
header("Content-Type: application/json");

require_once('../../database/db.php');

if ($_SERVER["REQUEST_METHOD"] !== "DELETE") {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed"]);
    exit;
}


if (!isset($_POST['post_id'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Post ID is required"]);
    exit;
}
$postId = isset($_POST['post_id']) ? intval($_POST['post_id']) : null;

try {
    $conn->begin_transaction();

    $sql = "DELETE FROM POSTS WHERE ID =?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postId);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Post deleted successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to delete post"]);
    }
} catch (\Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database operation failed: " . $e->getMessage()]);
} finally {
    $stmt->close();
    $conn->close();
}
