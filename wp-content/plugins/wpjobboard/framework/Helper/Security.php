<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Security
 *
 * @author Grzegorz
 */
class Daq_Helper_Security
{
    const CIPHER = MCRYPT_RIJNDAEL_128; // Rijndael-128 is AES
    const MODE   = MCRYPT_MODE_CBC;

    /* Cryptographic key of length 16, 24 or 32. NOT a password! */
    private $key;
    private $type;
    
    public static function enabled()
    {
        if(function_exists("mcrypt_encrypt")) {
            return true;
        } else {
            return false;
        }
    }
    
    public function __construct($key, $type = "AES") {
        $this->key = $key;
        $this->type = $type;
    }

    public function encrypt($plaintext) {
        
        if($this->type != "AES") {
            return base64_encode($plaintext);
        }
        
        $ivSize = mcrypt_get_iv_size(self::CIPHER, self::MODE);
        $iv = mcrypt_create_iv($ivSize, MCRYPT_DEV_RANDOM);
        $ciphertext = mcrypt_encrypt(self::CIPHER, $this->key, $plaintext, self::MODE, $iv);
        return base64_encode($iv.$ciphertext);
    }

    public function decrypt($ciphertext) {
        $ciphertext = base64_decode($ciphertext);
        
        if($this->type != "AES") {
            return $ciphertext;
        }
        
        $ivSize = mcrypt_get_iv_size(self::CIPHER, self::MODE);
        if (strlen($ciphertext) < $ivSize) {
            throw new Exception('Missing initialization vector');
        }

        $iv = substr($ciphertext, 0, $ivSize);
        $ciphertext = substr($ciphertext, $ivSize);
        $plaintext = mcrypt_decrypt(self::CIPHER, $this->key, $ciphertext, self::MODE, $iv);
        return rtrim($plaintext, "\0");
    }
}

?>
