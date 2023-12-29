<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
/*require 'gmail.php';
$gmail = new Gmail('easternlangku@gmail.com', 'East18042555');
$gmail->send('dontdory@gmail.com', 'Test Subject', 'Body');*/

	$user = "easternlangku@gmail.com";
	$pass = "East18042555";
	$to = "dontdory@gmail.com";
	$server = 'smtps://smtp.gmail.com:465';
	$name = "Chutirat Chaisan";
	$from = $user; //You could set this to a different email, but it is likely to get flagged as SPAM 
	$subject = "TEST YOUR_SUBJECT";
	$message = <<<EOD
	A MIME FORMATTED EMAIL
EOD;
	$emailFile = fopen("php://temp", 'w+');
	fwrite($emailFile, "$message");
	rewind($emailFile);
	$fstat = fstat($emailFile);
	$size = $fstat['size'];
	$ch = curl_init($server);
	//curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
	curl_setopt($ch, CURLOPT_MAIL_FROM, "<" . $from . ">");
	curl_setopt($ch, CURLOPT_MAIL_RCPT, array("<" . $to . ">"));
	curl_setopt($ch, CURLOPT_USERNAME, $user);
	curl_setopt($ch, CURLOPT_PASSWORD, $pass);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($ch, CURLOPT_USE_SSL, CURLUSESSL_ALL);
	curl_setopt($ch, CURLOPT_PUT, 1);
	curl_setopt($ch, CURLOPT_INFILE, $emailFile);
	curl_setopt($ch, CURLOPT_INFILESIZE,$size);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //If you want to analyze the output.
	$data = curl_exec($ch);
	fclose($emailFile);
	curl_close($ch);
