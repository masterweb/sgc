<?php

require_once 'Conexion.php';
require_once 'ConsultasBase.php';

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Herramientas
 *
 * @author DESARROLLO1
 */
class Herramientas {

    public function validarCedula($campo) {
        $numero = $campo;
        $suma = 0;
        $residuo = 0;
        $pri = 0;
        $pub = 0;
        $nat = 0;
        $numeroProvincias = 22;
        $modulo = 11;
        /* Aqui almacenamos los digitos de la cedula en variables. */
        $d1 = substr($numero, 0, 1);
        $d2 = substr($numero, 1, 1);
        $d3 = substr($numero, 2, 1);
        $d4 = substr($numero, 3, 1);
        $d5 = substr($numero, 4, 1);
        $d6 = substr($numero, 5, 1);
        $d7 = substr($numero, 6, 1);
        $d8 = substr($numero, 7, 1);
        $d9 = substr($numero, 8, 1);
        $d10 = substr($numero, 9, 1);
        /* El tercer digito es: */
        /* 9 para sociedades privadas y extranjeros */
        /* 6 para sociedades publicas */
        /* menor que 6 (0,1,2,3,4,5) para personas naturales */
        if ($d3 == 7 || $d3 == 8) {
            //echo"El tercer d&iacute;gito ingresado es inv&aacute;lido";
            return 0;
        }
        /* Solo para personas naturales (modulo 10) */
        if ($d3 < 6) {
            $nat = 1;
            $p1 = $d1 * 2;
            if ($p1 >= 10)
                $p1 -= 9;
            $p2 = $d2 * 1;
            if ($p2 >= 10)
                $p2 -= 9;
            $p3 = $d3 * 2;
            if ($p3 >= 10)
                $p3 -= 9;
            $p4 = $d4 * 1;
            if ($p4 >= 10)
                $p4 -= 9;
            $p5 = $d5 * 2;
            if ($p5 >= 10)
                $p5 -= 9;
            $p6 = $d6 * 1;
            if ($p6 >= 10)
                $p6 -= 9;
            $p7 = $d7 * 2;
            if ($p7 >= 10)
                $p7 -= 9;
            $p8 = $d8 * 1;
            if ($p8 >= 10)
                $p8 -= 9;
            $p9 = $d9 * 2;
            if ($p9 >= 10)
                $p9 -= 9;
            $modulo = 10;
        }
        /* Solo para sociedades publicas (modulo 11) */
        /* Aqui el digito verficador esta en la posicion 9, en las otras 2 en la pos. 10 */
        else if ($d3 == 6) {
            $pub = 1;
            $p1 = $d1 * 3;
            $p2 = $d2 * 2;
            $p3 = $d3 * 7;
            $p4 = $d4 * 6;
            $p5 = $d5 * 5;
            $p6 = $d6 * 4;
            $p7 = $d7 * 3;
            $p8 = $d8 * 2;
            $p9 = 0;
        }
        /* Solo para entidades privadas (modulo 11) */ else if ($d3 == 9) {
            $pri = 1;
            $p1 = $d1 * 4;
            $p2 = $d2 * 3;
            $p3 = $d3 * 2;
            $p4 = $d4 * 7;
            $p5 = $d5 * 6;
            $p6 = $d6 * 5;
            $p7 = $d7 * 4;
            $p8 = $d8 * 3;
            $p9 = $d9 * 2;
        }
        $suma = $p1 + $p2 + $p3 + $p4 + $p5 + $p6 + $p7 + $p8 + $p9;
        $residuo = $suma % $modulo;
        /* Si residuo=0, dig.ver.=0, caso contrario 10 - residuo */
        $digitoVerificador = $residuo == 0 ? 0 : $modulo - $residuo;
        /* ahora comparamos el elemento de la posicion 10 con el dig. ver. */
        if ($pub == 1) {
            if ($digitoVerificador != $d9) {
                //echo"El ruc de la empresa del sector p&uacute;blico es incorrecto.";
                return 0;
            }
            /* El ruc de las empresas del sector publico terminan con 0001 */
            if (substr($numero, 9, 4) != '0001') {
                //echo "El ruc de la empresa del sector p&uacute;blico debe terminar con 0001";
                return 0;
            }
        } elseif ($pri == 1) {
            if ($digitoVerificador != $d10) {
                //echo"El ruc de la empresa del sector privado es incorrecto.";
                return 0;
            }
            if (substr($numero, 10, 3) != '001') {
                //echo"El ruc de la empresa del sector privado debe terminar con 001";
                return 0;
            }
        } elseif ($nat == 1) {
            if ($digitoVerificador != $d10) {
                //echo"El n&uacute;mero de c&eacute;dula de la persona natural es incorrecto.";
                return 0;
            }
            if (strlen($numero) > 10 && substr($numero, 10, 3) != '001') {
                //echo"El ruc de la persona natural debe terminar con 001";
                return 0;
            }
        }
        return 1;
    }

    # Return browser information

    public function getBrowser() {
        $u_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version = $ub = "";

        //First get the platform?
        $mobile_agents = '!(tablet|pad|mobile|phone|symbian|android|ipod|ios|blackberry|webos)!i';
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        } elseif (preg_match($mobile_agents, $u_agent)) {
            $platform = 'mobile';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } elseif (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif (preg_match('/Chrome/i', $u_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } elseif (preg_match('/Safari/i', $u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif (preg_match('/Opera/i', $u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
                ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version = $matches['version'][0];
            } else {
                $version = isset($matches['version'][1]) ? $matches['version'][1] : '';
            }
        } else {
            $version = $matches['version'][0];
        }

        // check if we have a number
        if ($version == null || $version == "") {
            $version = "?";
        }

        return array(
            'userAgent' => $u_agent,
            'name' => $bname,
            'version' => $version,
            'platform' => $platform,
            'pattern' => $pattern
        );
    }

    public function getResponsableAsesorExterno($dealer_id) {
        require_once 'adodb/adodb.inc.php';
        $host = 'localhost';
        $user = 'root';
        $pass = 'k143c89?4Fg&2';
        $dbname = 'callcenter';

        $conn1 = &ADONewConnection('mysql');
        $conn1->PConnect($host, $user, $pass, $dbname);
        $conn1->SetFetchMode(ADODB_FETCH_ASSOC);

        $sql = "SELECT gu.* FROM grupoconcesionariousuario gu "
                . "INNER JOIN usuarios u ON u.id = gu.usuario_id WHERE gu.concesionario_id = {$dealer_id} "
                . " AND (u.cargo_id IN (86,85) OR u.cargo_adicional IN(86,85))";
        $result = $conn1->Execute($sql);
        $dealer = $result->fields['usuario_id'];
        return $dealer;
    }

    /**
     * Funcion para asignar vendedores web para solicitudes web
     * @param int $cargo_id
     * @param int $dealer_id
     * @return int id del asesor
     */

    public function getRandomKey($cargo_id, $dealer_id) {
        //echo 'dealer id: '.$dealer_id;
        // GENERACION Y ASIGNACION DE USUARIOS EXONERADOS DE LOS CLIENTES GENERADOS
        $connection = @mysql_connect('localhost', 'root', 'k143c89?4Fg&2')
                or die('Could not connect to database');
        mysql_select_db('callcenter')
                or die('Could not select database');

        $array_ids = array();

        //$dealer_id = $this->getConcesionarioDealerId($id_responsable);
        $sql = "SELECT gr.*,u.status_asesor FROM grupoconcesionariousuario gr 
                    INNER JOIN usuarios u ON u.id = gr.usuario_id 
                    WHERE (u.cargo_id = {$cargo_id} OR u.cargo_adicional = {$cargo_id})  AND u.status_asesor = 'ACTIVO' AND gr.concesionario_id = {$dealer_id}";
        $res = mysql_query($sql) or die("Could not execute query1");
        //die($sql); 
        //die('count: ' . mysql_num_rows($res));
        if (mysql_num_rows($res) > 0) {
            //die('enter not null');
            while($row = mysql_fetch_array($res,MYSQL_ASSOC)) {
                $array_ids[] = $row['usuario_id'];
                //echo 'usuario id: '.$row['usuario_id'];
            }
            //print_r($array_ids);die();
            // ID DEL ASESOR A SER ASIGNADO CLIENTE
            $random_key = $array_ids[array_rand($array_ids)];
            // ID DEL ASESOR EN TABLA usuarios PONEMOS ESTADO INACTIVO
            $sqlupd = "UPDATE usuarios SET status_asesor='INACTIVO' WHERE id={$random_key}";
            //die('sql: '.$sqlupd);
            $res2 = mysql_query($sqlupd) or die("Could not execute query update");
        } else {
            //die('enter else');
            $sql2 = "SELECT gr.*,u.status_asesor FROM grupoconcesionariousuario gr 
                    INNER JOIN usuarios u ON u.id = gr.usuario_id 
                    WHERE (u.cargo_id = {$cargo_id} OR u.cargo_adicional = {$cargo_id})  AND u.status_asesor = 'INACTIVO' AND gr.concesionario_id = {$dealer_id}";
            //die('sql empty: '.$sql2);
            $res2 =  mysql_query($sql2) or die("Could not execute query2");
            //die('count: ' . mysql_num_rows($res2));
            if(mysql_num_rows($res2) == 0){
                return 0;
            } 
            while($row2 = mysql_fetch_array($res2)) {
                $usuario_id = $row2['usuario_id'];
                $sqlupd2 = "UPDATE usuarios SET status_asesor='ACTIVO' WHERE id={$usuario_id}";
                $res3 = mysql_query($sqlupd2) or die("Could not execute query update");
            }
            $sql3 = "SELECT gr.*,u.status_asesor FROM grupoconcesionariousuario gr 
                    INNER JOIN usuarios u ON u.id = gr.usuario_id 
                    WHERE (u.cargo_id = {$cargo_id} OR u.cargo_adicional = {$cargo_id})  AND u.status_asesor = 'ACTIVO' AND gr.concesionario_id = {$dealer_id}";
            $res4 = mysql_query($sql3) or die("Could not execute query3");
            while($row3 = mysql_fetch_array($res4)) {
                $array_ids[] = $row3['usuario_id'];
            }
            // ID DEL ASESOR A SER ASIGNADO CLIENTE
            $random_key = $array_ids[array_rand($array_ids)];
            // ID DEL ASESOR EN TABLA usuarios PONEMOS ESTADO INACTIVO
            $sqlupd3 = "UPDATE usuarios SET status_asesor='ACTIVO' WHERE id={$random_key}";
            //die('$sqlupd3: '.$sqlupd3);
            $res5 = mysql_query($sqlupd3) or die("Could not execute query4");
        }
        return $random_key;
    }

    public function getFichaTecnica($id_vehiculo) {
        require_once 'adodb/adodb.inc.php';
        $host = 'localhost';
        $user = 'root';
        $pass = 'k143c89?4Fg&2';
        $dbname = 'callcenter';

        $conn1 = &ADONewConnection('mysql');
        $conn1->PConnect($host, $user, $pass, $dbname);
        $conn1->SetFetchMode(ADODB_FETCH_ASSOC);

        $sql = "SELECT * FROM versiones WHERE id_versiones = {$id_vehiculo}";
        $result = $conn1->Execute($sql);
        $dealer = $result->fields['pdf'];
        return $dealer;
    }

}

?>
