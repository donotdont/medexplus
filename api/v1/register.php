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

function get_client_ip()
{
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

require __DIR__ . '/vendor/autoload.php';

use App\DB as DB;
use App\AESIO as AESIO;

$db = new DB();
$conn = $db->Connection();

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->username) || !isset($data->email) || !isset($data->phone) || !isset($data->firstname) || !isset($data->lastname)) {
    echo json_encode(['success' => 0, 'message' => 'Please provide username, email, phone, firstname and lastname.']);
    exit;
}

if (!isset($data->password) || !isset($data->confirmpassword)) {
    echo json_encode(['success' => 0, 'message' => 'Not found the password.']);
    exit;
}

if (isset($data->password) && isset($data->confirmpassword) && $data->password != $data->confirmpassword) {
    echo json_encode(['success' => 0, 'message' => 'The password not math.']);
    exit;
}

if (isset($data->email) && !filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => 0, 'message' => 'Please check the email format.']);
    exit;
}

try {

    $fetch_post = "SELECT * FROM `user` WHERE username=:username OR email=:email OR phone=:phone";
    $fetch_stmt = $conn->prepare($fetch_post);
    $fetch_stmt->bindValue(':username', $data->username, PDO::PARAM_STR);
    $fetch_stmt->bindValue(':email', $data->email, PDO::PARAM_STR);
    $fetch_stmt->bindValue(':phone', $data->phone, PDO::PARAM_STR);
    $fetch_stmt->execute();

    if ($fetch_stmt->rowCount() == 0) :

        /*$setCondition = [];
        foreach ($data as $column => $value) {
            if (!empty($value) && $column != "id" && $column != "confirmpassword") {
                $setCondition[] = "{$column} = '{$value}'";
                //$bindValues[] = $value;
            }
        }

        echo implode(', ', $setCondition);

        exit;*/

        $query = "INSERT INTO `user` (username, email, phone, password, firstname, lastname, ip_address) VALUES(:username, :email, :phone, :password, :firstname, :lastname, :ip_address)";

        $update_stmt = $conn->prepare($query);

        $update_stmt->bindValue(':username', $data->username, PDO::PARAM_STR);
        $update_stmt->bindValue(':email', $data->email, PDO::PARAM_STR);
        $update_stmt->bindValue(':phone', $data->phone, PDO::PARAM_STR);
        $update_stmt->bindValue(':firstname', $data->firstname, PDO::PARAM_STR);
        $update_stmt->bindValue(':lastname', $data->lastname, PDO::PARAM_STR);
        $update_stmt->bindValue(':ip_address', get_client_ip(), PDO::PARAM_STR);
        $aesio = new AESIO();
        $update_stmt->bindValue(':password', $aesio->Encrypted($data->password), PDO::PARAM_STR);

        if ($update_stmt->execute()) {

            echo json_encode([
                'success' => 1,
                'message' => 'User created successfully'
            ]);
            exit;
        }

        echo json_encode([
            'success' => 0,
            'message' => 'User Not created. Something is going wrong.'
        ]);
        exit;

    else :
        echo json_encode(['success' => 0, 'message' => 'User, Email or Phone have already.']);
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
