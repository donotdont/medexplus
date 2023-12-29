<?php
require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . "/../../auth.php";

use App\DB as DB;
use App\AESIO as AESIO;

$auth = is_login();

//var_dump($auth);
function checkAuth($root, $model, $index = 0)
{
    $curent_role = array_filter($root['roles'], function ($var) use ($model, $index) {
        if (!empty($var["model"]) && !empty($var["enable_code"])) {
            return $var["model"] == $model && substr(str_pad(decbin($var["enable_code"]), 3, '0', STR_PAD_LEFT), $index, 1) == 1;
        } else {
            return;
        }
    });
    return COUNT($curent_role);
}

if ($auth && checkAuth($auth, "student", 1) && isset($_FILES['file1']['name'])) {

    $fileName = $_FILES["file1"]["name"]; // The file name
    $fileTmpLoc = $_FILES["file1"]["tmp_name"]; // File in the PHP tmp folder
    $fileType = $_FILES["file1"]["type"]; // The type of file it is
    $fileSize = $_FILES["file1"]["size"]; // File size in bytes
    $fileErrorMsg = $_FILES["file1"]["error"]; // 0 for false... and 1 for true
    if (!$fileTmpLoc) { // if file not chosen
        echo "ERROR: Please browse for a file before clicking the upload button.";
        exit();
    }

    $date = new \DateTime();
    $newFileName = $date->format('Y-m-d_H-i-s') . "_$fileName";
    if (move_uploaded_file($fileTmpLoc, "../../assets/uploads/students/xlsx/" . $newFileName)) {
        echo "$fileName upload is complete";
    } else {
        echo "move_uploaded_file function failed";
    }

    //use PhpOffice\PhpSpreadsheet\Spreadsheet;
    //use PhpOffice\PhpSpreadsheet\IOFactory;

    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
    $reader->setReadDataOnly(TRUE);
    $spreadsheet = $reader->load("../../assets/uploads/students/xlsx/" . $newFileName);

    $worksheet = $spreadsheet->getActiveSheet();
    // Get the highest row and column numbers referenced in the worksheet
    $highestRow = $worksheet->getHighestRow(); // e.g. 10
    $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5

    $db = new DB();
    $conn = $db->Connection();

    echo '<pre><table>' . "\n";
    for ($row = 3; $row <= $highestRow; ++$row) { // START ROW 3 NOT USE A B => [C]
        echo '<tr>' . PHP_EOL;
        $dataArray = array();
        for ($col = 1; $col <= $highestColumnIndex; ++$col) { // START COLUME 1 => [3]
            $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
            $dataArray[$col] = $value;
            echo '<td>' . $value . '</td>' . PHP_EOL;
        }
        var_dump($dataArray);

        if (!empty($dataArray[3])) {
            $currentYear = ((int)$date->format('Y')) + 543;

            $query = "INSERT INTO `student` (id_student_card, id_user, student_year, title_th, firstname_th, lastname_th, title_en, firstname_en, lastname_en, mejor_code, major, student_status, email, password, confirmed, active, block) VALUES(:id_student_card, :id_user, :student_year, :title_th, :firstname_th, :lastname_th, :title_en, :firstname_en, :lastname_en, :mejor_code, :major, :student_status, :email, :password, :confirmed, :active, :block)";

            $create_stmt = $conn->prepare($query);
            echo '$dataArray[3] => ' . $dataArray[3] . '<br />';
            $create_stmt->bindValue(':id_student_card', $dataArray[3], PDO::PARAM_STR);
            $create_stmt->bindValue(':id_user', $auth['id_user'], PDO::PARAM_INT);
            $create_stmt->bindValue(':student_year', (int)(substr($currentYear, 0, 2) . substr($dataArray[3], 0, 2)), PDO::PARAM_INT);
            $create_stmt->bindValue(':title_th', $dataArray[4], PDO::PARAM_STR);
            $create_stmt->bindValue(':firstname_th', $dataArray[5], PDO::PARAM_STR);
            $create_stmt->bindValue(':lastname_th', $dataArray[6], PDO::PARAM_STR);
            $create_stmt->bindValue(':title_en', $dataArray[7], PDO::PARAM_STR);
            $create_stmt->bindValue(':firstname_en', $dataArray[8], PDO::PARAM_STR);
            $create_stmt->bindValue(':lastname_en', $dataArray[9], PDO::PARAM_STR);
            $create_stmt->bindValue(':mejor_code', $dataArray[10], PDO::PARAM_STR);
            $create_stmt->bindValue(':major', $dataArray[11], PDO::PARAM_STR);
            $create_stmt->bindValue(':student_status', $dataArray[12], PDO::PARAM_STR);
            $create_stmt->bindValue(':email', $dataArray[13], PDO::PARAM_STR);
            $create_stmt->bindValue(':confirmed', 1, PDO::PARAM_INT);
            $create_stmt->bindValue(':active', 1, PDO::PARAM_INT);
            $create_stmt->bindValue(':block', 0, PDO::PARAM_INT);
            $aesio = new AESIO();
            $create_stmt->bindValue(':password', $aesio->Encrypted($dataArray[10]), PDO::PARAM_STR);

            if ($create_stmt->execute()) {
                echo 'User created successfully';
            } else {
                echo 'User Not created. Something is going wrong.';
            }
        }

        /*echo json_encode([
        'success' => 0,
        'message' => 'User Not created. Something is going wrong.'
    ]);*/

        echo '</tr>' . PHP_EOL;
    }
    echo '</table>' . PHP_EOL;
}