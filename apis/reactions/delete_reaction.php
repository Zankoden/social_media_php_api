<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE");
header("Content-Type: application/json");

require_once('../../database/db.php');

if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    $reactionId = isset($_GET['id']) ? intval($_GET['id']) : null;

    if (!$reactionId) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Reaction ID is required"]);
        exit;
    }

    try {
        $conn->begin_transaction();

        $sql = "DELETE FROM POST_REACTIONS WHERE ID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $reactionId);

        $stmt->execute();

        $affectedRows = $stmt->affected_rows;

        if ($affectedRows > 0) {
            http_response_code(200); // OK
            echo json_encode(["status" => "success", "message" => "Reaction deleted successfully"]);
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
