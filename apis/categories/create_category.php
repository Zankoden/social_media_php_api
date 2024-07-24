<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->name) && !empty($data->description)) {
        $name = mysqli_real_escape_string($conn, $data->name);
        $description = mysqli_real_escape_string($conn, $data->description);

        $query = "INSERT INTO CATEGORY (NAME, DESCRIPTION) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $name, $description);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(array("message" => "Category created successfully.", "id" => $stmt->insert_id));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create category."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to create category. Data is incomplete."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Method not allowed."));
}
