<?php

error_reporting(E_ALL);
ini_set('display_errors', 'on');

require_once __DIR__ . '/../vendor/autoload.php';

use App\DB;
use App\Types;
//use App\Type\MutationType;

use GraphQL\Server\StandardServer;
//use GraphQL\GraphQL;
//use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;

//use App\Type\QueryType;


/** 
 * Get header Authorization
 * */
function getAuthorizationHeader()
{
	$headers = null;
	if (isset($_SERVER['Authorization'])) {
		$headers = trim($_SERVER["Authorization"]);
	} else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
		$headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
	} elseif (function_exists('apache_request_headers')) {
		$requestHeaders = apache_request_headers();
		// Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
		$requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
		//print_r($requestHeaders);
		if (isset($requestHeaders['Authorization'])) {
			$headers = trim($requestHeaders['Authorization']);
		}
	}
	return $headers;
}

/**
 * get access token from header
 * */
function getBearerToken()
{
	$headers = getAuthorizationHeader();
	// HEADER: Get the access token from the header
	if (!empty($headers)) {
		if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
			return $matches[1];
		}
	}
	return null;
}

/*$rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);
    $auth = json_decode($input["variables"], true)["Authorization"];
    $token = str_replace('Bearer ','',$auth);*/
//print_r($token);

use App\AESIO as AESIO;
use App\JWTIO as JWTIO;

function getAuthorizationDB($stoken)
{
	try {

		if (preg_match("/EasternLanguageUser_/i", $stoken)) {
			if (!empty($stoken)) {
				$cookieToken = str_replace("EasternLanguageUser_", "", $stoken);
			} else {
				return false;
			}
			$baseDecode = base64_decode($cookieToken);

			$aesio = new AESIO();
			$decryptCode = $aesio->Decrypted($baseDecode);

			$jwtio = new JWTIO();
			$jwtDecode = (array)$jwtio->Decode($decryptCode);

			//print_r($jwtDecode);
			//echo date('Y-m-d', $jwtDecode['iat']);
			//echo $jwtDecode['iat'] . " >> " . strtotime('now') . " >> " . $jwtDecode['ext'];
			//var_dump($jwtDecode['iat'] <= strtotime('now') && strtotime('now') <= $jwtDecode['ext']);
			if ($jwtDecode['iat'] <= strtotime('now') && strtotime('now') <= $jwtDecode['ext']) {
				$db = new DB();
				$conn = $db->Connection();
				$fetch_user = "SELECT u.id_user,u.id_role,u.username,u.firstname,u.lastname FROM `user` u WHERE id_user=:id_user LIMIT 1";
				$fetch_stmt_user = $conn->prepare($fetch_user);
				$fetch_stmt_user->bindValue(':id_user', $jwtDecode['id'], PDO::PARAM_INT);
				$fetch_stmt_user->execute();

				$fetch_role = "SELECT u.id_user,u.id_role,p.model,p.enable_code FROM `user` u LEFT JOIN `permission` p ON p.id_role = u.id_role WHERE id_user=:id_user";
				$fetch_stmt_role = $conn->prepare($fetch_role);
				$fetch_stmt_role->bindValue(':id_user', $jwtDecode['id'], PDO::PARAM_INT);
				$fetch_stmt_role->execute();

				if ($fetch_stmt_user->rowCount() > 0 && $fetch_stmt_role->rowCount()) {
					$row_user = $fetch_stmt_user->fetch(PDO::FETCH_ASSOC);
					$row_role = $fetch_stmt_role->fetchAll(PDO::FETCH_ASSOC);
					//print_r($row);
					return array_merge($row_user, ["roles" => $row_role]);
				}
			}
		} elseif (preg_match("/EasternLanguageStudent_/i", $stoken)) {
			if (!empty($stoken)) {
				$cookieToken = str_replace("EasternLanguageStudent_", "", $stoken);
			} else {
				return false;
			}
			$baseDecode = base64_decode($cookieToken);

			$aesio = new AESIO();
			$decryptCode = $aesio->Decrypted($baseDecode);

			$jwtio = new JWTIO();
			$jwtDecode = (array)$jwtio->Decode($decryptCode);

			//print_r($jwtDecode);
			//echo date('Y-m-d', $jwtDecode['iat']);
			//echo $jwtDecode['iat'] . " >> " . strtotime('now') . " >> " . $jwtDecode['ext'];
			//var_dump($jwtDecode['iat'] <= strtotime('now') && strtotime('now') <= $jwtDecode['ext']);
			if ($jwtDecode['iat'] <= strtotime('now') && strtotime('now') <= $jwtDecode['ext']) {
				$db = new DB();
				$conn = $db->Connection();
				$fetch_student = "SELECT u.id_student,u.id_student_card,u.title_th,u.firstname_th,u.lastname_th,u.avatar FROM `student` u WHERE id_student=:id_student LIMIT 1";
				$fetch_stmt_student = $conn->prepare($fetch_student);
				$fetch_stmt_student->bindValue(':id_student', $jwtDecode['id'], PDO::PARAM_INT);
				$fetch_stmt_student->execute();

				if ($fetch_stmt_student->rowCount() > 0) {
					$row_student = $fetch_stmt_student->fetch(PDO::FETCH_ASSOC);
					//print_r($row);
					return $row_student;
				}
			}
		}


		return false;
	} catch (Exception $e) {
		return false;
	}
}

global $auth;
try {
	//print_r(getBearerToken());
	
	$stoken = getBearerToken();
	//echo $stoken;
	if (!empty($stoken))
	$auth = getAuthorizationDB($stoken);
	//var_dump($auth);
	/*$config = [
		'host' => 'localhost',
		'database' => 'eastern',
		'username' => 'root',
		'password' => '501309'
	];*/

	//DB::init($config);
	$db = new DB();
	$db->init();

	$schema = new Schema([
		'query' => Types::query(),
		'mutation' => Types::mutation(),
	]);

	$server = new StandardServer([
		'schema' => $schema,
		'rootValue' => $auth
	]);

	$server->handleRequest();
} catch (Throwable $e) {
	//StandardServer::send500Error($e);
	throw new \GraphQL\Error\Error($e);
}
