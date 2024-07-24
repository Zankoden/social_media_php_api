<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

require_once('../../database/db.php'); // Assuming this is where your database connection logic is located

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postId = $_POST['post_id'] ?? null;
    $userId = $_POST['user_id'] ?? null;
    $reactionType = $_POST['reaction_type'] ?? null;

    if (!$postId || !$userId || !$reactionType) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "All fields are required"]);
        exit;
    }

    // Prepare the SQL statement
    $sql = "INSERT INTO POST_REACTIONS (POST_ID, USER_ID, REACTION_TYPE) VALUES (?,?,?)";

    // Prepare and bind the statement
    $stmt = $conn->prepare($sql);
    // Corrected the binding parameter type for reactionType to 's' for string
    $stmt->bind_param("iis", $postId, $userId, $reactionType);

    // Execute the statement
    $stmt->execute();

    $lastId = $stmt->insert_id;

    if ($lastId > 0) {
        http_response_code(201);
        echo json_encode(["status" => "success", "message" => "Reaction added successfully", "reactionId" => $lastId]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to add reaction"]);
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
