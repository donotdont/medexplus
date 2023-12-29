<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

// Require composer autoload
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . "/../../auth.php";

use App\DB as DB;

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

$auth = is_login();
if ($auth && checkAuth($auth, "scanner_student", 0) && !empty($_GET["id_subject_activity"])) {

    $db = new DB();
    $conn = $db->Connection();

    $query = "SELECT *,ca.name_th AS category_name_th,sa.name_th AS subject_name_th FROM scanner_student ss LEFT JOIN subject_activity sa ON sa.id_subject_activity = ss.id_subject_activity LEFT JOIN category_activity ca ON ca.id_category_activity = sa.id_category_activity LEFT JOIN user u ON u.id_user = ss.id_user LEFT JOIN student s ON s.id_student = ss.id_student WHERE ss.id_subject_activity=:id_subject_activity ORDER BY CAST(s.id_student_card AS UNSIGNED INTEGER) ASC";
    $fetch_stmt = $conn->prepare($query);
    $fetch_stmt->bindValue(':id_subject_activity', $_GET["id_subject_activity"], PDO::PARAM_INT);
    $fetch_stmt->execute();
    $subject_activities = $fetch_stmt->fetchAll();
    //echo "<pre>";
    //var_dump($subject_activities);

    function ThaiDate($strDate)
    {
        //วันภาษาไทย
        $ThDay = array("อาทิตย์", "จันทร์", "อังคาร", "พุธ", "พฤหัสบดี", "ศุกร์", "เสาร์");
        //เดือนภาษาไทย
        $ThMonth = array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");

        //กำหนดคุณสมบัติ
        $week = date("w", strtotime($strDate)); // ค่าวันในสัปดาห์ (0-6)
        $months = date("m", strtotime($strDate)) - 1; // ค่าเดือน (1-12)
        $day = date("d", strtotime($strDate)); // ค่าวันที่(1-31)
        $years = date("Y", strtotime($strDate)) + 543; // ค่า ค.ศ.บวก 543 ทำให้เป็น ค.ศ.

        return "วัน$ThDay[$week] ที่ $day $ThMonth[$months] พ.ศ. $years";
    }

    $date_start = ThaiDate($subject_activities[0]["date_start"]);
    $header = <<<EOL
    <div>
    <div class="row">
    <div class="column-33">วัน/เดือน/ปี: {$date_start}</div>
    <div class="column-33 text-center">หน่วยกิต: {$subject_activities[0]["credit"]} หน่วย</div>
    <div class="column-33 text-end">จำนวน: {$subject_activities[0]["hours"]} ชั่วโมง</div>
    </div>
    </div>
    EOL;

    $table = <<<EOL
    <table>
    <tr>
        <th class="text-center" width="50">ลำดับ</th>
        <th class="text-center" width="80">รหัสนักศึกษา</th>
        <th class="text-center" width="80">คำนำหน้า</th>
        <th class="text-center">ชื่อ</th>
        <th class="text-center">นามสกุล</th>
        <th class="text-center" width="50">รหัสสาขา</th>
        <th class="text-center" width="80">สาขาวิชา</th>
    </tr>
    EOL;

    foreach ($subject_activities as $key => $value) {
        $numberIndex = $key + 1;
        $table .= <<<EOL
        <tr>
        <td class="text-center">{$numberIndex}</td>
        <td class="text-center">{$value['id_student_card']}</td>
        <td class="text-center">{$value['title_th']}</td>
        <td>{$value['firstname_th']}</td>
        <td>{$value['lastname_th']}</td>
        <td class="text-center">{$value['major_code']}</td>
        <td class="text-center">{$value['major']}</td>
        </tr>
        EOL;
    }
    $table .= "</table>";

    $footer = <<<EOL
    <div class="text-end">
        <p>ลงชื่อ................................................................</p>
        <p>(...................................................................)</p>
    </div>
    EOL;


    $html = <<<EOL
    <!DOCTYPE html>
    <html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>PDF</title>
    <style>
    * {
        box-sizing: border-box;
      }
      
      /* Create two equal columns that floats next to each other */
      .column {
        float: left;
        width: 50%;
        /*padding: 10px;
        height: 300px;*/ /* Should be removed. Only for demonstration */
      }
      
      .column-33 {
        float: left;
        width: 33%;
        /*padding: 10px;
        height: 300px;*/ /* Should be removed. Only for demonstration */
      }
      
      /* Clear floats after the columns */
      .row:after {
        content: "";
        display: table;
        clear: both;
      }

    body {
        font-family: "sarabun";
    }
    table {
    border-collapse: collapse;
    width: 100%;
    }

    td, th {
    /*border: 1px solid #dddddd;*/
    border: 1px solid #000000;
    text-align: left;
    padding: 8px;
    }

    /*tr:nth-child(even) {
    background-color: #dddddd;
    }*/

    .text-center{
        text-align: center;
    }
    
    .text-end{
        text-align: right;
    }
    </style>
    </head>
    <body>

    <h2 class="text-center">[[subject]]<br />[[category]]</h2>
    [[header]]

    [[table]]
    <br /><br />
    [[footer]]

    </body>
    </html>
    EOL;

    $html = str_replace("[[subject]]", $subject_activities[0]["subject_name_th"], $html);
    $html = str_replace("[[category]]", $subject_activities[0]["category_name_th"], $html);
    $html = str_replace("[[header]]", $header, $html);
    $html = str_replace("[[table]]", $table, $html);
    $html = str_replace("[[footer]]", $footer, $html);

    $mpdf = new \Mpdf\Mpdf();
    $mpdf->allow_charset_conversion = true;
    $mpdf->charset_in = 'UTF-8';
    //$html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
    $mpdf->WriteHTML($html); // ทำการสร้าง PDF ไฟล์
    //$mpdf->Output("MyPDF.pdf", "S"); // ให้ทำการบันทึกโค้ด HTML เป็น PDF โดยบันทึกเป็นไฟล์ชื่อ MyPDF.pdf
    $mpdf->Output();
}
