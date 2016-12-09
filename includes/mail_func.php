<?php

include("class.phpmailer1.php");

function sendEmailFunction($from, $fromname, $to, $names, $subject, $body, $cc, $cco, $cu) {
    $mail = new PHPMailer();
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host = "email-smtp.us-east-1.amazonaws.com"; //"webmail.etb.net.co";//"66.132.131.192"; // SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = "AKIAIZYNPI5NVVUM6XYA"; // SMTP account username
    $mail->Password = "AnH9ahUyZyPacdEgDIU+tlxPRPgtrm2pYMGlBtv3XaUZ";        // SMTP account password
    $mail->CharSet = 'UTF-8';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->From = $from;
    $mail->FromName = $fromname;

    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->IsHTML(true);

    for ($i = 0; $i < count($to); $i++) {
        $mail->AddAddress($to[$i]);
    }


    if ((int) $cu == 1) {
        if ($cc) {
            for ($j = 0; $j < count($cc); $j++) {
                $mail->AddCC($cc[$j]);
            }
        }
        if ($cco) {
            for ($k = 0; $k < count($cco); $k++) {
                $mail->AddBCC($cco[$k]);
            }
        }
    }
    if ((int) $cu == 1) {
        $mail->AddBCC('comunidad@kiamail.com.ec');
    }
    //$mail->AddBCC('alkanware@gmail.com');
    //$mail->AddBCC('carli-c@hotmail.com');


    if (!$mail->Send()) {
        return false;
    } else {
        return true;
    }
}

function sendEmailFunctionKmotorTaxi($from, $fromname, $subject, $body) {
    $mail = new PHPMailer();
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host = "email-smtp.us-east-1.amazonaws.com"; //"webmail.etb.net.co";//"66.132.131.192"; // SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = "AKIAIZYNPI5NVVUM6XYA"; //"cristian.cogollo@etb.net.co";//"movistar@ariadna.co";//"envios@ariadna.com.co";
    $mail->Password = "AnH9ahUyZyPacdEgDIU+tlxPRPgtrm2pYMGlBtv3XaUZ"; //"1sysadmas0";//"1qa2ws3e";//envio9685";
    $mail->CharSet = 'UTF-8';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->From = $from;
    $mail->FromName = $fromname;

    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->IsHTML(true);

    $mail->AddAddress("info@kiamail.com.ec");
    $mail->AddAddress("msandoval@kmotor.com.ec");
    //$mail->AddBCC('alkanware@gmail.com');

    if (!$mail->Send()) {
        return false;
    } else {
        return true;
    }
}

function sendEmailFunctionAtencion($from, $fromname, $to, $subject, $body) {
    $mail = new PHPMailer();
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host = "email-smtp.us-east-1.amazonaws.com"; //"webmail.etb.net.co";//"66.132.131.192"; // SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = "AKIAIZYNPI5NVVUM6XYA"; // SMTP account username
    $mail->Password = "AnH9ahUyZyPacdEgDIU+tlxPRPgtrm2pYMGlBtv3XaUZ";        // SMTP account password
    $mail->CharSet = 'UTF-8';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 25;

    $mail->From = $from;
    $mail->FromName = $fromname;

    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->IsHTML(true);

    $mail->AddAddress($to);

    //$mail->AddBCC('alkanware@gmail.com');
    //$mail->AddBCC('marcelo.rodriguez@ariadna.com.ec');


    if (!$mail->Send()) {
        return false;
    } else {
        return true;
    }
}

function sendEmailFunctionCallCenter($from, $fromname, $to, $subject, $body) {
    $mail = new PHPMailer();
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host = "email-smtp.us-east-1.amazonaws.com"; //"webmail.etb.net.co";//"66.132.131.192"; // SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = "AKIAIZYNPI5NVVUM6XYA"; //"cristian.cogollo@etb.net.co";//"movistar@ariadna.co";//"envios@ariadna.com.co";
    $mail->Password = "AnH9ahUyZyPacdEgDIU+tlxPRPgtrm2pYMGlBtv3XaUZ";        // SMTP account password
    $mail->CharSet = 'UTF-8';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->From = $from;
    $mail->FromName = $fromname;

    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->IsHTML(true);
    $mail->AddAddress($to);
    //$mail->AddBCC('marcelo.rodriguez@ariadna.com.ec');

    if (!$mail->Send()) {
        return false;
    } else {
        return true;
    }
}

function sendEmailFunctionAsistencia($from, $fromname, $to, $subject, $body) {
    $mail = new PHPMailer();
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host = "email-smtp.us-east-1.amazonaws.com"; //"webmail.etb.net.co";//"66.132.131.192"; // SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = "AKIAIZYNPI5NVVUM6XYA"; // SMTP account username
    $mail->Password = "AnH9ahUyZyPacdEgDIU+tlxPRPgtrm2pYMGlBtv3XaUZ";        // SMTP account password
    $mail->CharSet = 'UTF-8';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 25;

    $mail->From = $from;
    $mail->FromName = $fromname;

    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->IsHTML(true);
    $mail->AddAddress('supervisoroperaciones2@centerphone.com.ec');
    $mail->AddBCC('fjacome@kia.com.ec');
    $mail->AddBCC('vlondono@kia.com.ec');
    $mail->AddBCC('svillota@kia.com.ec');
    $mail->AddBCC('ssalvador@kia.com.ec');

    if (!$mail->Send()) {
        return false;
    } else {
        return true;
    }
}

function sendEmailFunctionPrueba($from, $fromname, $to, $names, $subject, $body, $charset = 'utf-8') {
    $mail = new PHPMailer();
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host = "hairfree.com.ec"; //"webmail.etb.net.co";//"66.132.131.192"; // SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = "cotizacionesguayaquil"; //"cristian.cogollo@etb.net.co";//"movistar@ariadna.co";//"envios@ariadna.com.co";
    $mail->Password = "hairfree2014"; //"1sysadmas0";//"1qa2ws3e";//envio9685";
    $mail->CharSet = 'UTF-8';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->From = $from;
    $mail->FromName = $fromname;

    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->IsHTML(true);
    $mail->AddAddress($to);
    //$mail->AddAddress('marcelo.rodriguez@ariadna.com.ec');
    //$mail->AddBCC('jorge.rodriguez@ariadna.com.ec');
    //$mail->AddBCC('javier.alban@ariadna.com.ec');


    if (!$mail->Send()) {
        return false;
    } else {
        return true;
    }
}

function sendEmailFunctionContactos($fromname, $to, $names, $subject, $body, $cc, $cco, $cu) {
    $mail = new PHPMailer();
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host = "email-smtp.us-east-1.amazonaws.com"; //"webmail.etb.net.co";//"66.132.131.192"; // SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = "AKIAIZYNPI5NVVUM6XYA"; //"cristian.cogollo@etb.net.co";//"movistar@ariadna.co";//"envios@ariadna.com.co";
    $mail->Password = "AnH9ahUyZyPacdEgDIU+tlxPRPgtrm2pYMGlBtv3XaUZ"; //"1sysadmas0";//"1qa2ws3e";//envio9685";
    $mail->CharSet = 'UTF-8';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 25;

    $mail->From = 'servicioalcliente@kia.com.ec';
    $mail->FromName = $fromname;

    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->IsHTML(true);

    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->IsHTML(true);
//    echo '<pre>';
//    print_r($to);
//    echo '</pre>';
//    die('num of array: '.count($to));

    for ($i = 0; $i < count($to); $i++) {
        $mail->AddAddress($to[$i]);
        //echo 'add address: '.$to[$i].'<br>';
    }
//die();

    if ((int) $cu == 1) {
        //die('enter cu 1');
        if ($cc) {
            //die('enter cu');
            for ($j = 0; $j < count($cc); $j++) {
                $mail->AddCC($cc[$j]);
            }
        }
        if ($cco) {
            for ($k = 0; $k < count($cco); $k++) {
                $mail->AddBCC($cco[$k]);
            }
        }
    }
    if ((int) $cu == 1) {
        $mail->AddBCC('contactos@kia.com.ec');
    }
    //$mail->AddBCC('alkanware@gmail.com');
    //$mail->AddBCC('carli-c@hotmail.com');
    //$mail->AddBCC('vlondono@kia.com.ec');

    if (!$mail->Send()) {
        return false;
    } else {
        return true;
    }
}

function sendEmailFunctionExonerados($from, $fromname, $to, $names, $subject, $body, $cc, $cco, $cu, $concesionario, $taxi) {
    //die('antra a sendEmailExonerados');
    $mail = new PHPMailer();
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host = "email-smtp.us-east-1.amazonaws.com"; //"webmail.etb.net.co";//"66.132.131.192"; // SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = "AKIAIZYNPI5NVVUM6XYA"; // SMTP account username
    $mail->Password = "AnH9ahUyZyPacdEgDIU+tlxPRPgtrm2pYMGlBtv3XaUZ";
    $mail->CharSet = 'UTF-8';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 25;

    $mail->From = $from;
    $mail->FromName = $fromname;

    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->IsHTML(true);
    //$mail->AddAddress("carla.calderon@ariadna.com.ec");

    for ($i = 0; $i < count($to); $i++) {
        $mail->AddAddress($to[$i], $names[$i]);
    }
    

    if ((($concesionario == 10) || ($concesionario == 72) || ($concesionario == 77)) && ($taxi == 216)) {

        $mail->ClearAddresses();
        $mail->AddAddress("msandoval@kmotor.com.ec");
        //$mail->AddAddress("alkanware@gmail.com");
        //$mail->AddAddress("carla.calderon@ariadna.com.ec");
    }


    if ((int) $cu == 1) {
        if ($cc) {
            for ($j = 0; $j < count($cc); $j++) {
                $mail->AddCC($cc[$j]);
            }
        }
        if ($cco) {
            for ($k = 0; $k < count($cco); $k++) {
                $mail->AddBCC($cco[$k]);
            }
        }
    }
    if ((int) $cu == 1) {
        $mail->AddBCC('contactos@kia.com.ec');
    }

    //$mail->AddBCC('alkanware@gmail.com');
    //$mail->AddBCC('carli-c@hotmail.com');
    if (!$mail->Send()) {
        return false;
    } else {
        return true;
    }
}

function sendEmailFunctionEncuesta($from, $fromname, $to, $subject, $body) {
    $mail = new PHPMailer();
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host = "hairfree.com.ec"; //"webmail.etb.net.co";//"66.132.131.192"; // SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = "cotizacionesguayaquil"; //"cristian.cogollo@etb.net.co";//"movistar@ariadna.co";//"envios@ariadna.com.co";
    $mail->Password = "hairfree2014"; //"1sysadmas0";//"1qa2ws3e";//envio9685";
    $mail->CharSet = 'UTF-8';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->From = $from;
    $mail->FromName = $fromname;

    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->IsHTML(true);

    $mail->AddAddress($to);
    //$mail->AddBCC('jorge.rodriguez@ariadna.com.ec');


    if (!$mail->Send()) {
        return false;
    } else {
        return true;
    }
}

function sendEmailFunctionEncuestaNoCompradores($from, $fromname, $to, $subject, $body) {
    //echo 'enter to send email</br>';
    $mail = new PHPMailer();
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host = "hairfree.com.ec"; //"webmail.etb.net.co";//"66.132.131.192"; // SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = "cotizacionesguayaquil"; //"cristian.cogollo@etb.net.co";//"movistar@ariadna.co";//"envios@ariadna.com.co";
    $mail->Password = "hairfree2014"; //"1sysadmas0";//"1qa2ws3e";//envio9685";
    $mail->CharSet = 'UTF-8';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->From = $from;
    $mail->FromName = $fromname;
    $mail->SetFrom('comunidad@kiamail.com.ec', 'Kia Ecuador');
    $mail->AddReplyTo('info@kia.com.ec');

    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->IsHTML(true);
    echo 'to: ' . $to;
    $mail->AddAddress($to);
    //$mail->AddBCC('jorge.rodriguez@ariadna.com.ec');

    if (!$mail->Send()) {
        return false;
    } else {
        return true;
    }
}

function sendEmailFunctionReportes($fromname, $to, $subject, $body, $cco_email, $bcco_email) {
    $mail = new PHPMailer();
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host = "hairfree.com.ec"; //"webmail.etb.net.co";//"66.132.131.192"; // SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = "cotizacionesguayaquil"; //"cristian.cogollo@etb.net.co";//"movistar@ariadna.co";//"envios@ariadna.com.co";
    $mail->Password = "hairfree2014"; //"1sysadmas0";//"1qa2ws3e";//envio9685";
    $mail->CharSet = 'UTF-8';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->From = $from;
    $mail->FromName = $fromname;
    $mail->SetFrom('comunidad@kiamail.com.ec', 'Kia Ecuador');
    $mail->AddReplyTo('info@kia.com.ec');

    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->IsHTML(true);

    $mail->AddAddress($to);
    //$mail->AddAddress('marcelo.rodriguez@ariadna.com.ec');
    //$mail->AddAddress('jorge.rodriguez@ariadna.com.ec');

    if ($cco_email != ''):
        $mail->AddCC($cco_email);
    endif;

    if ($bcco_email != ''):
        $bcco = explode(',', $bcco_email);
        for ($i = 0; $i < count($bcco); $i++) {
            $mail->AddBCC($bcco[$i]);
        }
    endif;
    //$mail->AddBCC('jorge.rodriguez@ariadna.com.ec');
    //echo '<h3>'.$to.'------'.$cco_email.'------------ '.$bcco_email.'</h3>';

    if (!$mail->Send()) {
        return false;
    } else {
        return true;
    }
}

function sendEmailFunctionReportesPrueba($fromname, $to, $subject, $body) {
    $mail = new PHPMailer();
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host = "hairfree.com.ec"; //"webmail.etb.net.co";//"66.132.131.192"; // SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = "cotizacionesguayaquil"; //"cristian.cogollo@etb.net.co";//"movistar@ariadna.co";//"envios@ariadna.com.co";
    $mail->Password = "hairfree2014"; //"1sysadmas0";//"1qa2ws3e";//envio9685";
    $mail->CharSet = 'UTF-8';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->From = $from;
    $mail->FromName = $fromname;
    $mail->MsgHTML($body);
    $mail->IsHTML(true);

    //$mail->AddAddress($to);
    //$mail->AddAddress('marcelo.rodriguez@ariadna.com.ec');
    //$mail->AddAddress('jorge.rodriguez@ariadna.com.ec');

    if (!$mail->Send()) {
        return false;
    } else {
        return true;
    }
}

?>