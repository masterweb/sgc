<?php

class Encryption {

    var $skey = "SuPerEncKey2010"; // you can change it

    public function safe_b64encode($string) {

        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }

    public function safe_b64decode($string) {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    public function encode($value) {

        if (!$value) {
            return false;
        }
        $text = $value;
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->skey, $text, MCRYPT_MODE_ECB, $iv);
        return trim($this->safe_b64encode($crypttext));
    }

    public function decode($value) {

        if (!$value) {
            return false;
        }
        $crypttext = $this->safe_b64decode($value);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->skey, $crypttext, MCRYPT_MODE_ECB, $iv);
        return trim($decrypttext);
    }

    function encode_this($string) {
        $string = utf8_encode($string);
        $control = "SuPerEncKey2010"; //defino la llave para encriptar la cadena, cambiarla por la que deseamos usar
        $string = $control . $string . $control; //concateno la llave para encriptar la cadena
        $string = base64_encode($string); //codifico la cadena
        return($string);
    }

    function decode_get2($string) {
        $cad = split("[?]", $string); //separo la url desde el ?
        $string = $cad[1]; //capturo la url desde el separador ? en adelante
        $string = base64_decode($string); //decodifico la cadena
        $control = "SuPerEncKey2010"; //defino la llave con la que fue encriptada la cadena,, cambiarla por la que deseamos usar
        $string = str_replace($control, "", "$string"); //quito la llave de la cadena
//procedo a dejar cada variable en el $_GET
        $cad_get = split("[&]", $string); //separo la url por &
//        echo '<pre>';
//        print_r($cad_get);
//        echo '</pre>';
        foreach ($cad_get as $value) {
            $val_get = split("[=]", $value); //asigno los valosres al GET
            $_GET[$val_get[0]] = utf8_decode($val_get[1]);
        }
    }

}

?>
