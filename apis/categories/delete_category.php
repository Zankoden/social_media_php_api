<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->id)) {
        $id = mysqli_real_escape_string($conn, $data->id);

        $query = "DELETE FROM CATEGORY WHERE ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(array("message" => "Category deleted successfully."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete category."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to delete category. No ID provided."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Method not allowed."));
}