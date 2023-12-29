<?php

namespace App;

class AESIO
{
    private $password = 'DC1O2jJEJiS8Y5wAArRw6j1AGOVE3WYPz9hxVEEu02p';
    private $method = 'aes-256-cbc';
    private $iv;
    private $passwordEncode;

    public function __construct()
    {
        $this->passwordEncode = substr(hash('sha256', $this->password, true), 0, 32);
    }


    public function Encrypted($plaintext)
    {
        // Must be exact 32 chars (256 bit)
        
        //echo "Password:" . $password . "\n";
        // IV must be exact 16 chars (128 bit)
        $this->iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

        // av3DYGLkwBsErphcyYp+imUW4QKs19hUnFyyYcXwURU=
        $encode = openssl_encrypt(
            $plaintext,
            $this->method,
            $this->passwordEncode,
            OPENSSL_RAW_DATA,
            $this->iv
        );
        return base64_encode($encode);
    }


    public function Decrypted($encrypted)
    {
        // IV must be exact 16 chars (128 bit)
        $this->iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

        // My secret message 1234
        return openssl_decrypt(base64_decode($encrypted), $this->method, $this->passwordEncode, OPENSSL_RAW_DATA, $this->iv);
    }
    /*
echo 'plaintext=' . $plaintext . "\n";
echo 'cipher=' . $method . "\n";
echo 'encrypted to: ' . $encrypted . "\n";
echo 'decrypted to: ' . $decrypted . "\n\n";*/
}
