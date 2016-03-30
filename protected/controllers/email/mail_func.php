<?php

include("class.phpmailer1.php");

function sendEmailFunctionCotizador($from, $fromname, $to, $names, $subject, $body, $cc, $cco, $cu) {
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

    for ($i = 0; $i < count($to); $i++) {
        $mail->AddAddress($to[$i], $names[$i]);
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
    //$mail->AddBCC('encaladayadira@gmail.com');


    if (!$mail->Send()) {
        return false;
    } else {
        return true;
    }
}

function sendEmailInfoClienteConcesionario($from, $fromname, $to, $ccarray, $subject, $body) {
    $mail = new PHPMailer();
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host = "email-smtp.us-east-1.amazonaws.com"; // SMTP server
    $mail->SMTPAuth = true;                  // enable SMTP authentication
    $mail->SMTPSecure = 'tls'; //
    $mail->Port = 587;                    // set the SMTP port for the GMAIL server
    $mail->Username = "AKIAIZYNPI5NVVUM6XYA"; // SMTP account username
    $mail->Password = "AnH9ahUyZyPacdEgDIU+tlxPRPgtrm2pYMGlBtv3XaUZ";        // SMTP account password
    $mail->CharSet = 'UTF-8';
    //$mail->From = $from;
    //$mail->FromName = $fromname;

    $mail->SetFrom('servicioalcliente@kiamail.com.ec', 'Kia Motors Ecuador SGC');
    $mail->AddReplyTo("servicioalcliente@kiamail.com.ec", "Kia Motors Ecuador SGC");

    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->IsHTML(true);

    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->IsHTML(true);

    $mail->AddAddress($to);
    //$mail->AddBCC('alkanware@gmail.com');

    if ($ccarray != '') {
        for ($j = 0; $j < count($ccarray); $j++) {
            $mail->AddCC($ccarray[$j]);
        }
    }

    if (!$mail->Send()) {
        return false;
    } else {
        return true;
    }
}

function sendEmailFunction($from, $fromname, $to, $subject, $body, $charset = 'utf-8', $mails, $cc, $cco) {
    $mail = new PHPMailer();
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host = "email-smtp.us-east-1.amazonaws.com"; // SMTP server
    $mail->SMTPAuth = true;                  // enable SMTP authentication
    $mail->SMTPSecure = 'tls'; //
    $mail->Port = 587;                    // set the SMTP port for the GMAIL server
    $mail->Username = "AKIAIZYNPI5NVVUM6XYA"; // SMTP account username
    $mail->Password = "AnH9ahUyZyPacdEgDIU+tlxPRPgtrm2pYMGlBtv3XaUZ";        // SMTP account password
    $mail->CharSet = 'UTF-8';

    $mail->From = $from;
    $mail->FromName = $fromname;

    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->IsHTML(true);

    for ($i = 0; $i < count($mails); $i++) {
        $mail->AddAddress($mails[$i]);
    }

    $mail->AddAddress($to);
    //$mail->AddAddress('gansaldo72@hotmail.com');
    //$mail->AddBCC('alkanware@gmail.com');
    //$mail->AddBCC('jorge.rodriguez@ariadna.com.ec');
    //$mail->AddBCC('javier.alban@ariadna.com.ec');
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


    if (!$mail->Send()) {
        return false;
    } else {
        return true;
    }
}

// email a concesionarios
function sendEmailFunctionConc($from, $fromname, $to, $subject, $body, $charset = 'utf-8', $mails, $cc, $cco, $emailVP, $emailCallCenter) {
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

    if ($mails != '') {
        for ($i = 0; $i < count($mails); $i++) {
            $mail->AddAddress($mails[$i]);
        }
    }

    $mail->AddAddress($to);
    //$mail->AddAddress('leticiaserranopro@gmail.com');// concesionario
    //$mail->AddBCC('alkanware@gmail.com');
    $mail->AddAddress($emailVP);
    if ($emailCallCenter != '') {
        $mail->AddAddress($emailCallCenter);
    }

    //$mail->AddBCC('jorge.rodriguez@ariadna.com.ec');
    //$mail->AddBCC('javier.alban@ariadna.com.ec');
    if ($cc != '') {
        for ($j = 0; $j < count($cc); $j++) {
            $mail->AddCC($cc[$j]);
        }
    }
    if ($cco != '') {
        for ($k = 0; $k < count($cco); $k++) {
            $mail->AddBCC($cco[$k]);
        }
    }


    if (!$mail->Send()) {
        return false;
    } else {
        return true;
    }
}

function sendEmailFunctionExonerados($from, $fromname, $to, $names, $subject, $body, $charset = 'utf-8', $attachment, $cc, $cco, $cu, $concesionario) {
    //die('antra a sendEmailExonerados');
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

    $mail->From = $from;
    $mail->FromName = $fromname;

    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->IsHTML(true);
    if ($attachment != "") {
        $mail->AddAttachment($attachment);
    }

    for ($i = 0; $i < count($to); $i++) {
        $mail->AddAddress($to[$i], $names[$i]);
    }
    if (($concesionario == 62) || ($concesionario == 60) || ($concesionario == 7) || ($concesionario == 76) || ($concesionario == 5) || ($concesionario == 2) || ($concesionario == 6)) {
        $mail->AddAddress("rmoya@asiauto.com.ec");
    }

    $mail->AddAddress("jaguirre@aekia.com.ec");
    //$mail->AddBCC('alkanware@gmail.com');
    //$mail->AddAddress("jorge.rodriguez@ariadna.com.ec");

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
    //$mail->AddBCC('pablo.leyton@ariadna.com.ec');
    //$mail->AddBCC('javier.alban@ariadna.com.ec');


    if (!$mail->Send()) {
        return false;
    } else {
        return true;
    }
}

function sendEmailInfo($from, $fromname, $to, $subject, $body) {
    $mail = new PHPMailer();
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host = "email-smtp.us-east-1.amazonaws.com"; // SMTP server
    $mail->SMTPDebug = 2;                     // enables SMTP debug information (for testing)
    // 1 = errors and messages
    // 2 = messages only
    $mail->SMTPAuth = true;                  // enable SMTP authentication
    $mail->SMTPSecure = 'tls'; //
    $mail->Port = 587;                    // set the SMTP port for the GMAIL server
    $mail->Username = "AKIAIZYNPI5NVVUM6XYA"; // SMTP account username
    $mail->Password = "AnH9ahUyZyPacdEgDIU+tlxPRPgtrm2pYMGlBtv3XaUZ";        // SMTP account password
    $mail->CharSet = 'UTF-8';
    //$mail->From = $from;
    //$mail->FromName = $fromname;

    $mail->SetFrom('servicioalcliente@kiamail.com.ec', 'Kia Motors Ecuador');
    $mail->AddReplyTo("servicioalcliente@kiamail.com.ec", "Kia Motors Ecuador");

    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->IsHTML(true);

    $mail->AddAddress($to);
    //$mail->AddAddress('vlondono@kia.com.ec'); // call center
    //$mail->AddAddress('carlacalderonsalazar@gmail.com'); // call center
    $mail->AddBCC('alkanware@gmail.com');

    if (!$mail->Send()) {
        return false;
    } else {
        return true;
    }
}

function sendEmailInfoTestDrive($from, $fromname, $to, $toAsesor, $subject, $body) {
    $mail = new PHPMailer();
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host = "email-smtp.us-east-1.amazonaws.com"; // SMTP server
    $mail->SMTPAuth = true;                  // enable SMTP authentication
    $mail->SMTPSecure = 'tls'; //
    $mail->Port = 587;                    // set the SMTP port for the GMAIL server
    $mail->Username = "AKIAIZYNPI5NVVUM6XYA"; // SMTP account username
    $mail->Password = "AnH9ahUyZyPacdEgDIU+tlxPRPgtrm2pYMGlBtv3XaUZ";        // SMTP account password
    $mail->CharSet = 'UTF-8';
    //$mail->From = $from;
    //$mail->FromName = $fromname;

    $mail->SetFrom('servicioalcliente@kiamail.com.ec', 'Kia Motors Ecuador SGC');
    $mail->AddReplyTo("servicioalcliente@kiamail.com.ec", "Kia Motors Ecuador SGC");

    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->IsHTML(true);

    $mail->AddAddress($to);
    $mail->AddAddress($toAsesor);
    //$mail->AddAddress('vlondono@kia.com.ec'); // call center
    //$mail->AddAddress('gansaldo72@hotmail.com');
    //$mail->AddBCC('alkanware@gmail.com');

    if (!$mail->Send()) {
        return false;
    } else {
        return true;
    }
}

function sendEmailInfoD($from, $fromname, $to, $subject, $body, $tipo) {
    $mail = new PHPMailer();
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host = "email-smtp.us-east-1.amazonaws.com"; // SMTP server
    $mail->SMTPAuth = true;                  // enable SMTP authentication
    $mail->SMTPSecure = 'tls'; //
    $mail->Port = 587;                    // set the SMTP port for the GMAIL server
    $mail->Username = "AKIAIZYNPI5NVVUM6XYA"; // SMTP account username
    $mail->Password = "AnH9ahUyZyPacdEgDIU+tlxPRPgtrm2pYMGlBtv3XaUZ";        // SMTP account password
    $mail->CharSet = 'UTF-8';

    $mail->From = $from;
    $mail->FromName = $fromname;

    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->IsHTML(true);

    $mail->AddAddress($to);
    //$mail->AddBCC('alkanware@gmail.com');



    if (!$mail->Send()) {
        return false;
    } else {
        return true;
    }
}

// enviar email al cliente
function sendEmailCliente($from, $fromname, $to, $subject, $body, $tipo) {
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
    //$mail->AddAddress('jorge.rodriguez@ariadna.com.ec'); // email al administrador
    //$mail->AddBCC('alkanware@gmail.com');


    if (!$mail->Send()) {
        return false;
    } else {
        return true;
    }
}

?>