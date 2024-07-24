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

$userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;

if ($userId= null) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "User ID is required"]);
    exit;
}

try {
    $conn->begin_transaction();

    $sql = "SELECT * FROM POSTS WHERE USER_ID =?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $posts = $result->fetch_all(MYSQLI_ASSOC);

    if (!empty($posts)) {
        http_response_code(200);
        echo json_encode(["status" => "success", "data" => $posts]);
    } else {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "No posts found for this user"]);
    }
} catch (\Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database operation failed: " . $e->getMessage()]);
} finally {
    $stmt->close();
    $conn->close();
}
