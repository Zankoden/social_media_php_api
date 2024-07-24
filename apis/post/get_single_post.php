<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

require_once('../../database/db.php');

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed"]);
    exit;
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing required parameter: id"]);
    exit;
}

$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
if ($id === false || $id <= 0) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid id parameter"]);
    exit;
}

try {
    $sql = "SELECT p.*, u.username AS user_name, c.category_name 
            FROM posts p 
            JOIN users u ON p.user_id = u.id 
            JOIN categories c ON p.category_id = c.id 
            WHERE p.id = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Failed to prepare statement");
    }

    $stmt->bind_param("i", $id);

    if (!$stmt->execute()) {
        throw new Exception("Failed to execute statement");
    }

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
        echo json_encode(["status" => "success", "post" => $post]);
    } else {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Post not found"]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Server error: " . $e->getMessage()]);
} finally {
    $stmt->close();
    $conn->close();
}
