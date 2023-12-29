<?php
class Gmail {

	private $mail;
	private $email;
	private $pass;

	public function __construct($email, $pass){
		$this->email = $email;
		$this->pass = $pass; 
	}

	private function mailGen(){
		$from = yield;
		$to = yield;
		$subject = yield;
		$body = yield;
		yield "FROM: <" . $from . ">\n";
		yield "To: <" . $to . ">\n";
		yield "Date: " . date("r") . "\n";
		yield "Subject: " . $subject . "\n";
		yield "\n";
		yield $body;
		yield "";
	}
	
	public function getLine(){
		$resp = $this->mail->current();
		$this->mail->next();
		return $resp;
	}
	
	public function send($to, $subject, $body){
		$this->mail = $this->mailGen();
		$this->mail->send($this->email);
		$this->mail->send($to);
		$this->mail->send($subject);
		$this->mail->send($body);
		$ch = curl_init("smtps://smtp.gmail.com:465");
 
		curl_setopt($ch, CURLOPT_MAIL_FROM, "<" . $this->email . ">");
		curl_setopt($ch, CURLOPT_MAIL_RCPT, array("<" . $to . ">"));
		curl_setopt($ch, CURLOPT_USERNAME, $this->email);
		curl_setopt($ch, CURLOPT_PASSWORD, $this->pass);
		curl_setopt($ch, CURLOPT_USE_SSL, CURLUSESSL_ALL);
		// curl_setopt($ch, CURLOPT_VERBOSE, true); // Uncomment to see the transaction
		curl_setopt($ch, CURLOPT_READFUNCTION, array($this, "getLine")); 
 
		return curl_exec($ch);
	}

}