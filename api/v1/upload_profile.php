<?php
require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . "/../../auth.php";

use App\DB as DB;

$auth = is_login();
if ($auth && isset($_FILES['file']['name'])) {
   // file name
   $filename = $_FILES['file']['name'];

   // file extension
   $file_extension = pathinfo($filename, PATHINFO_EXTENSION);
   $file_extension = strtolower($file_extension);

   // Location
   $location = __DIR__ . '/../../assets/uploads/users/profile_' . str_pad($auth['id_user'], 9, '0', STR_PAD_LEFT) . "." . $file_extension;

   // Valid extensions
   //$valid_ext = array("pdf","doc","docx","jpg","png","jpeg");
   $valid_ext = array("bmp","gif","jpg", "png", "jpeg");

   $response = 0;
   if (in_array($file_extension, $valid_ext)) {
      // Upload file
      if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
         $response = 1;
      }
   }

   try {
      $db = new DB();
      $conn = $db->Connection();

      $fetch_post = "UPDATE `user` SET avatar=:avatar WHERE id_user=:id_user";
      $fetch_stmt = $conn->prepare($fetch_post);
      $fetch_stmt->bindValue(':avatar', '/assets/uploads/users/profile_' . str_pad($auth['id_user'], 9, '0', STR_PAD_LEFT) . "." . $file_extension, PDO::PARAM_STR);
      $fetch_stmt->bindValue(':id_user', $auth['id_user'], PDO::PARAM_INT);
      $fetch_stmt->execute();

   } catch (PDOException $e) {
      http_response_code(500);
      echo json_encode([
         'success' => 0,
         'message' => $e->getMessage()
      ]);
      exit;
   }
   echo $response;
   exit;
}
