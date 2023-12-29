<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

// Require composer autoload
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . "/../../auth.php";

use App\DB as DB;

$authStudent = is_login_student();
if ($authStudent && !empty($authStudent["id_student"])) { //&& !empty($_GET["id_subject_activity"])

    $db = new DB();
    $conn = $db->Connection();

    $query = "SELECT *,ca.name_th AS category_name_th,sa.name_th AS subject_name_th,ca.name_en AS category_name_en,sa.name_en AS subject_name_en FROM scanner_student ss LEFT JOIN subject_activity sa ON sa.id_subject_activity = ss.id_subject_activity LEFT JOIN category_activity ca ON ca.id_category_activity = sa.id_category_activity LEFT JOIN user u ON u.id_user = ss.id_user LEFT JOIN student s ON s.id_student = ss.id_student WHERE ss.id_student=:id_student ORDER BY ss.`created_at` ASC";
    $fetch_stmt = $conn->prepare($query);
    $fetch_stmt->bindValue(':id_student', $authStudent["id_student"], PDO::PARAM_INT);
    $fetch_stmt->execute();
    $subject_activities = $fetch_stmt->fetchAll();
    //echo "<pre>";
    //var_dump($subject_activities);

    if (empty($subject_activities)) {
        echo 'Data not found!';
        return;
    }

    function lng()
    {
        return (!empty($_GET["lang"]) && $_GET["lang"] == "en") ? "en" : "th";
    }

    function EnglishDate($strDate)
    {
        return date('d F, Y (l)', strtotime($strDate));
    }

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
    if (lng() == "th")
        $header = <<<EOL
    <div>
    <div class="row">
    <div class="column">ชื่อ-นามสกุล: {$subject_activities[0]['title_th']}{$subject_activities[0]['firstname_th']}  {$subject_activities[0]['lastname_th']}</div>
    <div class="column">รหัสบัตรนักศึกษา: {$subject_activities[0]['id_student_card']}</div>
    </div>
    <div class="row">
    <div class="column">รหัสสาขา: {$subject_activities[0]['major_code']}</div>
    <div class="column">สาขา: {$subject_activities[0]['major']}</div>
    </div>
    </div>
    EOL;
    if (lng() == "en")
        $header = <<<EOL
    <div>
    <div class="row">
    <div class="column">Name: {$subject_activities[0]['title_en']}{$subject_activities[0]['firstname_en']}  {$subject_activities[0]['lastname_en']}</div>
    <div class="column">Student No.: {$subject_activities[0]['id_student_card']}</div>
    </div>
    <div class="row">
    <div class="column">Major Code: {$subject_activities[0]['major_code']}</div>
    <div class="column">Major: {$subject_activities[0]['major']}</div>
    </div>
    </div>
    EOL;

    $table = "<table>";
    if (lng() == "th")
        $table .= <<<EOL
        <tr>
        <th class="text-center" width="25">ลำดับ</th>
        <th class="text-center" width="200">วัน/เดือน/ปี</th>
        <th class="text-center">ชื่อโครงการ</th>
        <th class="text-center" width="25">จำนวนชั่วโมง</th>
    </tr>
    EOL;
    if (lng() == "en")
        $table .= <<<EOL
        <tr>
        <th class="text-center">Index</th>
        <th class="text-center">Date</th>
        <th class="text-center">Subject</th>
        <th class="text-center">Hours</th>
    </tr>
    EOL;
    $totalHours = 0;
    foreach ($subject_activities as $key => $value) {
        $numberIndex = $key + 1;
        $date_start = (lng() == "en") ? EnglishDate($value["date_start"]) : ThaiDate($value["date_start"]);
        $totalHours += $value["hours"];
        $table .= <<<EOL
        <tr><td class="text-center">{$numberIndex}</td><td>{$date_start}</td><td>{$value["subject_name_" . lng()]}</td><td class="text-center">{$value["hours"]}</td></tr>";
        EOL;
    }
    if (lng() == "th")
        $table .= "<tr><td colspan=\"2\"></td><td class=\"text-center\">รวมจำนวนชั่วโมง</td><td class=\"text-center\"><b>{$totalHours}</b></td></tr>";
    if (lng() == "en")
        $table .= "<tr><td colspan=\"2\"></td><td class=\"text-center\">Total hours</td><td class=\"text-center\"><b>{$totalHours}</b></td></tr>";
    $table .= "</table>";

    if (lng() == "th")
        $footer = <<<EOL
    <div class="text-end">
        <p>ลงนาม................................................................</p>
        <p>({$subject_activities[0]['title_th']}{$subject_activities[0]['firstname_th']}  {$subject_activities[0]['lastname_th']})</p>
    </div>
    EOL;
    if (lng() == "en")
        $footer = <<<EOL
    <div class="text-end">
        <p>Sign................................................................</p>
        <p>({$subject_activities[0]['title_en']}{$subject_activities[0]['firstname_en']}  {$subject_activities[0]['lastname_en']})</p>
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

    $html = str_replace("[[subject]]", (lng() == "en") ? "Activity Record" : "บันทึกชั่วโมงกิจกรรม", $html); //$subject_activities[0]["subject_name_th"]
    $html = str_replace("[[category]]", $subject_activities[0]["category_name_" . lng()], $html);
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
