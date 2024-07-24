<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->id) && !empty($data->name) && !empty($data->description)) {
        $id = mysqli_real_escape_string($conn, $data->id);
        $name = mysqli_real_escape_string($conn, $data->name);
        $description = mysqli_real_escape_string($conn, $data->description);

        $query = "UPDATE CATEGORY SET NAME = ?, DESCRIPTION = ? WHERE ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $name, $description, $id);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(array("message" => "Category updated successfully."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to update category."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to update category. Data is incomplete."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Method not allowed."));
}
