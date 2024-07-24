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

$sql = "SELECT p.*, u.username AS user_name, c.name FROM posts p JOIN users u ON p.user_id = u.id JOIN category c ON p.category_id = c.id ORDER BY p.date DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
    echo json_encode(["status" => "success", "posts" => $posts]);
} else {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "No posts found"]);
}

$conn->close();
