<?php
header('Content-Type: text/html; charset=UTF-8');
$version = "0.0.7";
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
    }else{
		require __DIR__ . '/views/header.php';
        require __DIR__ . '/views/home.php';
        require __DIR__ . '/views/footer.php';
	}
	
}