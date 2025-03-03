<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

require_once('../../database/db.php');

$postId = isset($_GET['post_id']) ? intval($_GET['post_id']) : null;

if (!$postId) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Post ID is required"]);
    exit;
}

try {
    $conn->begin_transaction();

    // Joining POSTS, POST_REACTIONS, and USERS tables to get all columns
    $sql = "SELECT P.*, PR.*, U.*
            FROM POSTS P
            JOIN POST_REACTIONS PR ON P.ID = PR.POST_ID
            LEFT JOIN USERS U ON PR.USER_ID = U.ID
            WHERE P.ID =?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postId);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $reactions = [];
        while ($row = $result->fetch_assoc()) {
            $reactions[] = $row;
        }

        http_response_code(200); // OK
        echo json_encode(['status' => 'success', 'data' => $reactions]);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(['status' => 'error', 'message' => 'No reactions found for this post']);
    }
} catch (\Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database operation failed: ' . $e->getMessage()]);
} finally {
    $stmt->close();
    $conn->close();
}
