<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT * FROM CATEGORY";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $categories = array();
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        http_response_code(200);
        echo json_encode($categories);
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "No categories found."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Method not allowed."));
}
