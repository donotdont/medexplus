<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') :
    http_response_code(405);
    echo json_encode([
        'success' => 0,
        'message' => 'Invalid Request Method. HTTP method should be POST',
    ]);
    exit;
endif;

require __DIR__ . '/../../vendor/autoload.php';

use App\DB as DB;
use App\AESIO as AESIO;
use App\JWTIO as JWTIO;

$db = new DB();
$conn = $db->Connection();

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->username)) {
    echo json_encode(['success' => 0, 'message' => 'Please provide Student ID Card and password.']);
    exit;
}

try {
    if (!empty($data->username)) {
        $fetch_post = "SELECT id_student FROM `student` WHERE id_student_card=:username AND password=:password AND (block IS NULL OR block = 0)"; //( OR phone=:username OR email=:username)
        $fetch_stmt = $conn->prepare($fetch_post);
        $fetch_stmt->bindValue(':username', $data->username, PDO::PARAM_STR);
    }

    $aesio = new AESIO();
    $fetch_stmt->bindValue(':password', $aesio->Encrypted($data->password), PDO::PARAM_STR);
    $fetch_stmt->execute();

    if ($fetch_stmt->rowCount() > 0) :
        $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
        //print_r($row);

        $payload = array(
            "id" => $row['id_student'],
            "iat" => time(),
            "ext" => time() + 86400 //Add a day to it (eg: by adding 86400 seconds (24 * 60 * 60))
        );

        $jwtio = new JWTIO();
        $jwt = $jwtio->Encode($payload);
        $aesio = new AESIO();
        $token = "EasternLanguageStudent_" . base64_encode($aesio->Encrypted($jwt));
        echo json_encode(['success' => 1, 'message' => 'Login to system successful.', 'token' => $token]);
        exit;

    else :
        echo json_encode(['success' => 0, 'message' => 'Please check Student ID Card and Password.']);
        exit;
    endif;
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
    exit;
}
