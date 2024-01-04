<?php
header('Content-Type: text/html; charset=UTF-8');
require_once __DIR__ . '/vendor/autoload.php';

$version = "0.0.1";
$request = urldecode($_SERVER['REQUEST_URI']);
$urls = explode("/", $request);

if (!empty($request)) {
    $urls = explode("/", $request);

    if (!empty($urls[1]) && ($urls[1] == 'product')) {
        require __DIR__ . '/views/header.php';
        require __DIR__ . '/views/product.php';
        require __DIR__ . '/views/footer.php';
    } else if (!empty($urls[1]) && ($urls[1] == 'pdf-quatation')) {
        require __DIR__ . '/views/header.php';
        require __DIR__ . '/views/pdf-quatation.php';
        require __DIR__ . '/views/footer.php';
    } else if (!empty($urls[1]) && ($urls[1] == 'quatation')) {
        require __DIR__ . '/views/header.php';
        require __DIR__ . '/views/quatation.php';
        require __DIR__ . '/views/footer.php';
    } else if (!empty($urls[1]) && ($urls[1] == 'contact')) {
        require __DIR__ . '/views/header.php';
        require __DIR__ . '/views/contact.php';
        require __DIR__ . '/views/footer.php';
    } else if (!empty($urls[1]) && ($urls[1] == 'aboutus')) {
        require __DIR__ . '/views/header.php';
        require __DIR__ . '/views/aboutus.php';
        require __DIR__ . '/views/footer.php';
    } else if (!empty($urls[1]) && ($urls[1] == 'mpdf')) {
        //custom font
        $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'fontDir' => array_merge($fontDirs, [
                __DIR__ . '/fonts',
            ]),
            'fontdata' => $fontData + [
                'sarabun' => [
                    'R' => 'THSarabunNew.ttf',
                    'I' => 'THSarabunNew Italic.ttf',
                    'B' =>  'THSarabunNew Bold.ttf',
                ]
            ],
        ]);
        $stylesheet = file_get_contents(__DIR__ . '/assets/css/bootstrap.min.css');
        $mpdf->WriteHTML($stylesheet, 1); // CSS Script goes here.
        $mpdf->WriteHTML(file_get_contents(__DIR__ . '/views/pdf-quatation.php'));
        $mpdf->Output();
    } else {
        require __DIR__ . '/views/header.php';
        require __DIR__ . '/views/home.php';
        require __DIR__ . '/views/footer.php';
    }
}
