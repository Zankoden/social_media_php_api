<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

require_once('../../database/db.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Extract subscription details from the query string
    $userId = isset($_GET['userId']) ? intval($_GET['userId']) : '';
    $planName = isset($_GET['planName']) ? htmlspecialchars($_GET['planName']) : '';
    $startDate = isset($_GET['startDate']) ? htmlspecialchars($_GET['startDate']) : '';
    $endDate = isset($_GET['endDate']) ? htmlspecialchars($_GET['endDate']) : '';
    $price = isset($_GET['price']) ? floatval($_GET['price']) : '';

    // Validate input (simple validation, consider adding more comprehensive checks)
    if (!$userId || !$planName || !$startDate || !$endDate || !$price) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Missing required subscription details"]);
        exit;
    }

    // Prepare the SQL statement to insert the new subscription
    $sql = "INSERT INTO PREMIUM_SUBSCRIPTIONS (USER_ID, PLAN_NAME, START_DATE, END_DATE, PRICE, STATUS) VALUES (?,?,?,?,?, 'active')";

    // Prepare and bind the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssdd", $userId, $planName, $startDate, $endDate, $price);

    // Execute the statement
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(["status" => "success", "message" => "Subscription inserted successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to insert subscription"]);
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
