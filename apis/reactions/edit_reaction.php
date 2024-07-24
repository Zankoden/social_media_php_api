<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

require_once('../../database/db.php');

if ($_SERVER["REQUEST_METHOD"] === "PUT") {
    $reactionId = isset($_POST['reaction_id']) ? intval($_POST['reaction_id']) : null;
    $userId = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;
    $newReactionType = isset($_POST['reaction_type']) ? trim($_POST['reaction_type']) : null;

    if (!$reactionId || !$userId || !$newReactionType) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "All fields are required"]);
        exit;
    }

    try {
        $conn->begin_transaction();

        $sql = "UPDATE POST_REACTIONS SET USER_ID=?, REACTION_TYPE=? WHERE ID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isi", $userId, $newReactionType, $reactionId);

        $stmt->execute();

        $affectedRows = $stmt->affected_rows;

        if ($affectedRows > 0) {
            http_response_code(200); // OK
            echo json_encode(["status" => "success", "message" => "Reaction updated successfully"]);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(["status" => "error", "message" => "Reaction not found or already deleted"]);
        }
    } catch (\Exception $e) {
        $conn->rollback();
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Database operation failed: " . $e->getMessage()]);
    } finally {
        $stmt->close();
        $conn->close();
    }
} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
