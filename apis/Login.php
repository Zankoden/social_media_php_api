<?php

// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Methods: POST");
// header("Access-Control-Allow-Headers: Content-Type");

// // Assuming db.php correctly initializes $conn
// require_once('../database/db.php');

// // this is fo debugging
// // error_reporting(E_ALL);
// // ini_set('display_errors', 1);
// // var_dump($_POST);
// // exit;

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     // Check if required parameters are provided
//     if (!isset($_POST['email'], $_POST['password'])) {
//         // Missing required parameters, return an error
//         header('Content-Type: application/json');
//         echo json_encode([
//             'status' => 'error',
//             'message' => 'Missing required parameters: email and password.'
//         ]);
//         exit; // Exit the script to prevent further execution
//     }

//     $email = $_POST['email'];
//     $password = $_POST['password'];

//     // Prepared statement to avoid SQL injection
//     $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
//     $stmt->bind_param("s", $email); // "s" indicates the variable type is string
//     $stmt->execute();
//     $result = $stmt->get_result();

//     if ($result->num_rows > 0) {
//         $row = $result->fetch_assoc();
//         $userPasswordHash = $row['password'];

//         if (password_verify($password, $userPasswordHash)) {
//             $user_id = $row['id'];
//             $token = bin2hex(random_bytes(16));

//             // Insert the token into the api_tokens table
//             $stmt = $conn->prepare("INSERT INTO api_tokens (user_id, token) VALUES (?, ?)");
//             $stmt->bind_param("is", $user_id, $token);
//             $stmt->execute();

//             if ($row['type'] == 'Customer') {
//                 // No additional action needed here since we're already fetching the user details
//             }

//             $response = [
//                 'status' => 'success',
//                 'message' => 'Login successful',
//                 'token' => $token,
//                 'user' => $row,
//             ];
//         } else {
//             $response = [
//                 'status' => 'error',
//                 'message' => 'Invalid credentials'
//             ];
//         }
//     } else {
//         $response = [
//             'status' => 'error',
//             'message' => 'Invalid credentials'
//         ];
//     }

//     // Send the response as JSON
//     header('Content-Type: application/json');
//     echo json_encode($response);
// } else{
//     $response = array(
//         'status' => 'error',
//         'message' => 'Invalid request method'
//     );

//     // Return the response as JSON
//     header('Content-Type: application/json');
//     echo json_encode($response);
// }

//new

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once('../database/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debugging: Output the entire $_POST array
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    echo "<pre>";
    var_dump($_POST);
    echo "</pre>";

    // Check if required parameters are provided
    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing required parameters: email and password.'
        ]);
        exit;
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Debugging: Output the values of email and password
    echo "<pre>";
    echo "Email: " . $email . "\n";
    echo "Password: " . $password . "\n";
    echo "</pre>";

    // Prepared statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE EMAIL = ?");
    if ($stmt === false) {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => 'Database query preparation failed.'
        ]);
        exit;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Debugging: Output the fetched row
        echo "<pre>";
        echo "Fetched row: \n";
        var_dump($row);
        echo "</pre>";

        if (isset($row['PASSWORD'])) {
            $userPasswordHash = $row['PASSWORD'];
        } else {
            // Debugging: Output an error message if password is not set
            echo "<pre>";
            echo "Error: Password key not found in the fetched row.\n";
            echo "</pre>";
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Password not found in the fetched row.'
            ]);
            exit;
        }

        if (password_verify($password, $userPasswordHash)) {
            $user_id = $row['ID'];
            $token = bin2hex(random_bytes(16));

            // Insert the token into the api_tokens table
            $stmt = $conn->prepare("INSERT INTO api_tokens (user_id, token) VALUES (?, ?)");
            if ($stmt === false) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to insert token into database.'
                ]);
                exit;
            }

            $stmt->bind_param("is", $user_id, $token);
            $stmt->execute();

            $response = [
                'status' => 'success',
                'message' => 'Login successful',
                'token' => $token,
                'user' => [
                    'id' => $row['ID'],
                    'email' => $row['EMAIL'],
                    'type' => $row['TYPE']
                ],
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Invalid credentials'
            ];
        }
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Invalid credentials'
        ];
    }

    // Send the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    $response = array(
        'status' => 'error',
        'message' => 'Invalid request method'
    );

    header('Content-Type: application/json');
    echo json_encode($response);
}
