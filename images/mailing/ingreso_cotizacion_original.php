<?php

ini_set('display_errors', 1);
date_default_timezone_set('America/Guayaquil');
require_once 'includes/config_emails.php';
require_once 'includes/Formularios.php';
require_once 'includes/Clicks.php';
require_once 'includes/Ips.php';
require_once 'includes/Herramientas.php';
require_once 'includes/form_cotizacion.php';

$objHerramientas = new Herramientas();
$dataBrowser = $objHerramientas->getBrowser();
$objIps = new Ips();
$objFormularios = new Formularios();
$form_cotizacion = new FormCotizacion();

$id_formulario = $_POST['id_formulario'];
//========================= INGRESO PARA FORMULARIO DE COTIZACION Y VEHICULOS EXONERADOS ==============================================
if (isset($_POST['sendCotizacion'])) {
    //die('enter cotizacion');
    $correcto = 0;
    $error = 0;
    foreach ($_POST as $nombre_campo => $valor) {
        $valor = trim(@$valor);
        if (empty($valor) || !isset($valor)) {
            $error++;
        } else {
            $correcto++;
        }

        if (is_numeric($_POST['cedula']) == false || is_numeric($_POST['celular']) == false || is_numeric($_POST['telefono']) == false) {
            $error++;
        }
        if (strlen($_POST['cedula']) != 10) {
            $error++;
        }
        if (strlen($_POST['celular']) != 10) {
            $error++;
        }
        if (strlen($_POST['telefono']) != 9) {
            $error++;
        }
    }
    //die('error:'.$error);
    if ($error == 0) {
        $fechaactual = date('Y-m-d');
        $form_cotizacion->nombre = $_POST['nombres'];
        $form_cotizacion->apellido = $_POST['apellidos'];
        $form_cotizacion->cedula = $_POST['cedula'];
        $form_cotizacion->direccion = $_POST['direccion'];
        $form_cotizacion->telefono = $_POST['telefono'];
        $form_cotizacion->celular = $_POST['celular'];
        $form_cotizacion->email = $_POST['email'];
        $form_cotizacion->id_modelos = $_POST['modelo'];
        $form_cotizacion->id_version = $_POST['version'];
        $form_cotizacion->id_provincia = 2;
        $form_cotizacion->cityid = $_POST['ciudadCotizacion'];
        $form_cotizacion->dealerid = $_POST['concesionario'];
        $form_cotizacion->id_atencion = $id_formulario;
        $form_cotizacion->fecha_form = $fechaactual;
        $form_cotizacion->fecha_email = $fechaactual;
        $form_cotizacion->ips = $objIps->__getIp();
        $form_cotizacion->id_origen = 1;
        $form_cotizacion->motivo = $_POST['motivo'];
        $form_cotizacion->codigo = '';
        $form_cotizacion->navegador = $dataBrowser['name'] . ' ' . $dataBrowser['version'];
        $form_cotizacion->plataforma = $dataBrowser['platform'];
        $form_cotizacion->fuente = $_POST['fuente'];
        //$form_cotizacion->ruc = $_POST['ruc'];
        $return = $form_cotizacion->create();
        //die('after second');

        if ($return) {// if table form_cotizacion not empty
            $nombre = "'" . $form_cotizacion->nombre . "'";
            $apellidos = "'" . $form_cotizacion->apellido . "'";
            $direccion = "'" . $form_cotizacion->direccion . "'";
            $datos['nombre'] = $nombre;
            $datos['apellido'] = $apellidos;
            $datos['cedula'] = "'$form_cotizacion->cedula'";
            //$datos['cedula'] = "'$form_cotizacion->ruc'";
            $datos['direccion'] = $direccion;
            $datos['telefono'] = "'$form_cotizacion->telefono'";
            $datos['celular'] = "'$form_cotizacion->celular'";
            $datos['email'] = "'$form_cotizacion->email'";
            $datos['id_modelos'] = $form_cotizacion->id_modelos;
            $datos['id_version'] = $form_cotizacion->id_version;
            $datos['cityid'] = $form_cotizacion->cityid;
            $datos['dealerid'] = $form_cotizacion->dealerid;
            $fechaactual = date('Y-m-d');
            $datos['fecha_form'] = "'$fechaactual'";
            $datos['id_atencion'] = $form_cotizacion->id_atencion;
            $datos['ips'] = $objIps->__getIp();
            $datos['navegador'] = "'" . $form_cotizacion->navegador . "'";
            $datos['plataforma'] = "'" . $form_cotizacion->plataforma . "'";
            $datos['fuente'] = "'" . $form_cotizacion->fuente . "'";
            $archivoXmlFormularios = $objFormularios->ingresosGeneral('atencion_detalle', $datos);
            $getLastQuery = $objFormularios->getLastQuery();

            if ($archivoXmlFormularios) {
                //die('arhivoxml');
                //ENVIAR DATOS A EL SISTEMA SGC DE VENTAS
                // INGRESAR DATOS A CASOS EN EL CALL CENTER
                // CONECCION A LA BASE DE DATOS DE KIA ADMINKIA

                $arrayProvincias = array(
                    7 => 23,
                    20 => 13,
                    6 => 1,
                    19 => 8,
                    5 => 10,
                    8 => 11,
                    14 => 6,
                    18 => 12,
                    32 => 7,
                    22 => 14,
                    11 => 14,
                    12 => 18,
                    4 => 19,
                    15 => 5,
                    9 => 21,
                    21 => 16,
                    34 => 10,
                );
                // COLOCAR LA PROVINCIA Y CIUDAD PRINCIPAL CON LA ID CORRECTA
                $arrayCiudadesProv = array(
                    7 => array(23, 246), //ambato
                    20 => array(13, 10), // babahoyo - quevedo
                    6 => array(1, 7), //cuenca
                    19 => array(8, 62), //esmeraldas
                    5 => array(10, 4), //guayaquil
                    8 => array(11, 1010), //ibarra
                    14 => array(6, 48), //latacunga
                    18 => array(12, 110), //loja
                    32 => array(7, 56), //machala
                    22 => array(14, 128), //manta
                    11 => array(14, 59), //portoviejo
                    12 => array(18, 149), //puyo
                    4 => array(19, 159), //quito
                    15 => array(5, 44), //riobamba
                    9 => array(21, 195), //sto domingo
                    21 => array(16, 241),// tena
                    34 => array(10, 810) //milagro
                );
                $grupo_id = 0;
                switch ($form_cotizacion->dealerid) {
                    case 60:
                    case 7:
                    case 6:
                    case 2:
                    case 76:
                    case 5:
                    case 62:
                    case 63:
                    case 20:
                    case 65:
                    case 38: 
                        $grupo_id = 2;
                        break;
                    case 72:
                    case 77:
                    case 81:
                    case 10:
                    case 80:
                        $grupo_id = 3;
                        break;
                    default:
                        break;
                }
                $her = new Herramientas();
                //$usuario_id = $her->getResponsableAsesorExterno($form_cotizacion->dealerid);
                $usuario_id = $her->getRandomKey(86,$form_cotizacion->dealerid);
                
                //die('usuario id: '.$usuario_id);
                // SI ASESOR WEB EXISTE EN EL CONCESIONARIO SELECCIONADO
                // GRABA EN BASE DE DATOS DE gestion informacion y se va al RGD del asesor
                if($usuario_id != 0){
                    date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
                    mysql_set_charset('utf8');
                    $fecha = date("Y-m-d H:i:s");
                    $connection = @mysql_connect('localhost', 'root', 'k143c89?4Fg&2')
                            or die('Could not connect to database');
                    mysql_select_db('callcenter')
                            or die('Could not select database');
                    $sq = "INSERT INTO gestion_nueva_cotizacion (fuente,identificacion,cedula,fecha) "
                            . " VALUES('web','ci','{$form_cotizacion->cedula}','{$fecha}')";
                    $res = mysql_query($sq) or die("Could not execute query 0"); 
                    $lastIdCotizacion = mysql_insert_id($connection);

                    $sqlAsesor="SELECT nombres, apellido,correo,telefono,direccion 
                                FROM callcenter.usuarios
                                inner join dealers
                                on dealers.id=usuarios.dealers_id
                                where usuarios.id=".$usuario_id;
                    $query=mysql_query($sqlAsesor,$connection);
                    $rowAsesor=mysql_fetch_array($query);

                    //die("cliente: ".$nombre." ".$apellidos."<br> Asesor: ".$rowAsesor["nombres"]." ".$rowAsesor["apellido"]." correo: ".$rowAsesor["correo"]." telefono: ".$rowAsesor["telefono"]." direccion: ".$rowAsesor["direccion"]);


                    //$responsable = 

                    $query = "INSERT INTO gestion_informacion "
                            . "(nombres, apellidos, cedula,email,celular,telefono_oficina, telefono_casa, "
                            . "provincia_conc,ciudad_conc,concesionario,responsable,dealer_id, "
                            . "fecha, direccion, provincia_domicilio, ciudad_domicilio, id_cotizacion, bdc) "
                            . "VALUES('{$form_cotizacion->nombre}', '{$form_cotizacion->apellido}', '{$form_cotizacion->cedula}','{$form_cotizacion->email}','{$form_cotizacion->celular}','{$form_cotizacion->telefono}','{$form_cotizacion->telefono}',"
                            . "{$arrayProvincias[$form_cotizacion->cityid]},{$arrayCiudadesProv[$form_cotizacion->cityid][1]},{$form_cotizacion->dealerid},{$usuario_id},{$form_cotizacion->dealerid},"
                            . "'{$fecha}','{$form_cotizacion->direccion}',{$arrayProvincias[$form_cotizacion->cityid]},{$arrayCiudadesProv[$form_cotizacion->cityid][1]},{$lastIdCotizacion},1)";

                            //die($query);        
                    $result = mysql_query($query) or die("Could not execute query 1");
                    $lastIdCasos = mysql_insert_id($connection);

                    $queryConsulta = "INSERT INTO gestion_consulta ("
                            . "preg7, id_informacion) "
                            . "VALUES('Hot C (hasta 30 dias)',{$lastIdCasos})";
                    $result2 = mysql_query($queryConsulta) or die("Could not execute query 2");

                    $fecha_actual = strftime("%Y/%m/%d %X ", time());
                    $fecha_posterior = strtotime('+2 day', strtotime($fecha_actual));
                    $fecha_posterior = date('Y-m-d H:i:s', $fecha_posterior); // suma dos dias a la fecha de proximo seguimiento

                    $queryGestion = "INSERT INTO gestion_diaria (id_informacion, id_vehiculo, observaciones,medio_contacto,"
                            . "fuente_contacto, codigo_vehiculo, prospeccion, status, paso,"
                            . "proximo_seguimiento, fecha) "
                            . "VALUES ({$lastIdCasos},{$form_cotizacion->id_modelos},'Prospeccion','web',"
                            //. "'web',{$form_cotizacion->id_version},1,1,'1-2',"
                            . "'web',{$form_cotizacion->id_version},1,1,'4',"
                            . "'{$fecha_posterior}','{$fecha}')";
                    //die('sql: '.$queryGestion);
                    $resultGestion =   mysql_query($queryGestion) or die("Could not execute query 3"); 
                    
                    // INSERTAR VEHICULO EN TABLA gestion_vehiculo del SGC
                    $queryVehiculo = "INSERT INTO gestion_vehiculo (id_informacion, modelo, version, fecha) "
                            . " VALUES({$lastIdCasos},{$form_cotizacion->id_modelos},{$form_cotizacion->id_version},'{$fecha}')";
                    $resultVec =   mysql_query($queryVehiculo) or die("Could not execute query vec");         
                            
                }
                

                /* Buscar concesionario por ciudad */
                if ($form_cotizacion->cityid > 0) {
                    $ANomCC = $objFormularios->dameNombresCC($form_cotizacion->cityid, $form_cotizacion->dealerid);
                    $row = $ANomCC->fetch_assoc();

                    $nombreCiudad = $row['NombreCiudad'];
                    //die('nombre ciudad: '.$nombreCiudad);
                    $nombreConcesionario = $row['NombreConce'];
                }

                /* Seleccionar Modelo de un Auto */
                if ($form_cotizacion->id_modelos > 0) {
                    $ANomCC1 = $objFormularios->dameNombresCC1($form_cotizacion->id_modelos, $form_cotizacion->id_version);
                    $row1 = $ANomCC1->fetch_assoc();

                    if ($form_cotizacion->id_modelos == 93 && $form_cotizacion->id_version == 217) {
                        $nombreAuto = 'Soul EV';
                        $nombreModel = 'SOUL EV ELECTRICO';
                    } else {
                        $nombreAuto = $row1['nombre_modelo'];
                        $nombreModel = $row1['nombre_version'];
                    }


                    //die('nombre modelo: '.$nombreAuto);
                }

                /* Seleccionar Nombre y Email */
                if ($form_cotizacion->dealerid > 0) {
                    $ANomCC2 = $objFormularios->dameNombresCC2($form_cotizacion->dealerid);
                    $row2 = $ANomCC2->fetch_assoc();
                    $conName = $row2['name'];
                }


                $colEmailsTipoAtencion = $objFormularios->dameEmailsConcesionariosTipoAtencion($form_cotizacion->dealerid, $form_cotizacion->id_atencion);

                $paraEmail = $colEmailsTipoAtencion[0];
                $ccEmail = $colEmailsTipoAtencion[1];
                $ccoEmail = $colEmailsTipoAtencion[2];


                $mensajeEnvio = "";
                $general = '<table border=0>
                    <tr><td><b>Concesionario:</b></td><td>' . utf8_encode($row2['name']) . '</td></tr>
                    <tr><td><b>Dir. Concesionario:</b></td><td>' . utf8_encode($row2['direccion']) . '</td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td><b>Fuente:</b></td><td>' . utf8_encode($form_cotizacion->fuente) . '</td></tr>
                    <tr><td><b>Nombres:</b></td><td>' . utf8_encode($form_cotizacion->nombre) . '</td></tr>
                    <tr><td><b>Apellidos:</b></td><td>' . $form_cotizacion->apellido . '</td></tr>
                    <tr><td><b>C&eacute;dula:</b></td><td>' . $form_cotizacion->cedula . '</td></tr>
                    <tr><td><b>Direcci&oacute;n:</b></td><td>' . $form_cotizacion->direccion . '</td></tr>
                    <tr><td><b>Tel&eacute;fono:</b></td><td>' . $form_cotizacion->telefono . '</td></tr>
                    <tr><td><b>Celular:</b></td><td>' . $form_cotizacion->celular . '</td></tr>
                    <tr><td><b>Veh&iacute;culo de Inter&eacute;s</b>:</td><td>' . utf8_encode($nombreAuto) . '</td></tr>
                    <tr><td><b>Modelo:</b></td><td>' . utf8_encode($nombreModel) . '</td></tr>
                    <tr><td><b>Ciudad:</b></td><td>' . utf8_decode($nombreCiudad) . '</td></tr>
                    <tr><td><b>E-mail:</b></td><td>' . $form_cotizacion->email . '</td></tr>
                    </table>';

                $codigohtml = $general;
                $headers = 'From: servicioalcliente@kiamail.com.ec' . "\r\n";
                $headers .= 'Content-type: text/html' . "\r\n";

                $asunto = 'Formulario enviado desde Kia.com.ec: Solicitud de Cotización - Concesionario: ' . utf8_encode($row2['name']) . ', Id form: ' . $getLastQuery;


                //$send = mail($email, html_entity_decode($asunto), $codigohtml, $headers);

                include_once("includes/mail_func.php");
                //llenar arrays
                //$ccEmail = 
                //$ccoEmail = 
                //$email = $paraEmail . 'info@kia.com.ec';
                //  $nombre = $paraEmail . "Kia Motors";
                //$mails = 'info@kia.com.ec,' . $paraEmail;

                $array_mails = explode(",", $paraEmail);

                $noms = 'servicioalcliente@kiamail.com.ec,' . $paraEmail;
                $array_noms = explode(",", $noms);

                $ccEmail = 'servicioalcliente@kiamail.com.ec,' . $ccEmail;
                $ccEmail_array = explode(",", $ccEmail);
                $ccoEmail = 'servicioalcliente@kiamail.com.ec,' . $ccoEmail;
                $ccoEmail_array = explode(",", $ccoEmail);
                //die('before send');
                if (($form_cotizacion->dealerid == 10 || $form_cotizacion->dealerid == 72 || $form_cotizacion->dealerid == 77 || $form_cotizacion->dealerid == 80) && ($form_cotizacion->id_version == 216)) {
                    sendEmailFunctionKmotorTaxi('servicioalcliente@kiamail.com.ec', "Kia Motors Ecuador", html_entity_decode($asunto), $codigohtml);
                } else {
                    $res = sendEmailFunction('servicioalcliente@kiamail.com.ec', "Kia Motors Ecuador", $array_mails, $array_noms, html_entity_decode($asunto), $codigohtml, $ccEmail_array, $ccoEmail_array, 1);
                }



                if ($_POST['fuente'] == 'Call Center') {
                    //die('enter call');
                    sendEmailFunctionCallCenter('servicioalcliente@kiamail.com.ec', "Kia Motors Ecuador", 'vlondono@kia.com.ec', html_entity_decode($asunto), $codigohtml);
                }

                $send = mail($email,html_entity_decode($asunto),$codigohtml,$headers);
                // para usuarios

                $mails_us = $form_cotizacion->email . ", servicioalcliente@kiamail.com.ec";
                $array_mails_us = explode(",", $mails_us);

                $noms_us = $datos['nombre'] . ", Kia Motors";
                $array_noms_us = explode(",", $noms_us);
                $body = '<style>
                            body {margin: 0; padding: 0; min-width: 100%!important;}
                        </style>
                    </head>

                    <body>
                        <table cellpadding="0" cellspacing="0" width="650" align="center" border="0">
                            <tr>
                                <td align="center"><img src="images/mailing/mail_factura_03.jpg" width="569" height="60" alt="" style="display:block; border:none;"/></td>
                            </tr>
                            <tr>
                                <td style="font-family:Arial, sans-serif; font-size:16px; color:#5e5e5e; text-align:center; padding-top:15px; padding-bottom:15px;">Hola <strong>'.$nombre." ".$apellidos.'</strong>,<br/>
                                    Gracias por contactarnos. Ahora estás más cerca de tu nuevo Cerato Forte</td>
                                </tr>
                                <tr>
                                    <td align="center"><a href="https://www.kia.com.ec/images/Fichas_Tecnicas/'.$her->getFichaTecnica($form_cotizacion->id_version).'" target="_blank"><img src="images/mailing/'.$her->getFichaTecnica($form_cotizacion->id_version).'.jpg" width="570" height="240" alt="" style="display:block; border:none;"/></a></td>
                                </tr>
                                <tr>
                                    <td style="font-family:Arial, sans-serif; font-size:16px; color:#5e5e5e; text-align:center; padding-top:15px; padding-bottom:15px;">Soy <strong>'.$rowAsesor["nombres"]." ".$rowAsesor["apellido"].'</strong>, me encargaré de preparar tu cotización,<br/>
                                        pronto me contactaré contigo.</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table cellpadding="0" cellspacing="0" align="center">
                                                <tr>
                                                    <td>
                                                        <table cellpadding="0" cellspacing="0" width="300">
                                                            <tr>
                                                                <td style="font-family:Arial, sans-serif; font-size:16px; color:#5e5e5e; text-align:center; padding-top:15px; padding-bottom:15px;">Si tienes alguna duda comunícate conmigo</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-bottom:10px;">
                                                                    <table>
                                                                        <tr>
                                                                            <td rowspan="2"><img src="images/mailing/mail_factura_11.jpg" width="31" height="29" alt="" style="display:block; border:none;"/></td>
                                                                            <td style="font-family:Arial, sans-serif; font-size:14px; color:#5e5e5e; text-align:left;"><strong>Teléfono:</strong></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="font-family:Arial, sans-serif; font-size:14px; color:#5e5e5e; text-align:left;">'.$rowAsesor["telefono"].'</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-bottom:10px;">
                                                                    <table>
                                                                        <tr>
                                                                            <td rowspan="2"><img src="images/mailing/mail_factura_14.jpg" width="31" height="29" alt="" style="display:block; border:none;"/></td>
                                                                            <td style="font-family:Arial, sans-serif; font-size:14px; color:#5e5e5e; text-align:left;"><strong>Email:</strong></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="font-family:Arial, sans-serif; font-size:14px; color:#5e5e5e; text-align:left;"><a href="mailto:amsanchez@asiauto.com.ec" style="color:#5e5e5e;">'.$rowAsesor["correo"].'</a></td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-bottom:10px;">
                                                                    <table>
                                                                        <tr>
                                                                            <td rowspan="2"><img src="images/mailing/mail_factura_16.jpg" width="31" height="29" alt="" style="display:block; border:none;"/></td>
                                                                            <td style="font-family:Arial, sans-serif; font-size:14px; color:#5e5e5e; text-align:left;"><strong>Dirección:</strong></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="font-family:Arial, sans-serif; font-size:14px; color:#5e5e5e; text-align:left;">'.$rowAsesor["direccion"].'</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td><a href="https://www.kia.com.ec/concesionarios.html" target="_blank"><img src="images/mailing/mail_factura_09.jpg" width="256" height="226" alt=""/></a></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top:15px;">
                                            <table cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td><img src="images/mailing/mail_factura_19.jpg" width="56" height="160" alt="" style="display:block; border:none;"/></td>
                                                    <td><img src="images/mailing/mail_factura_20.jpg" width="178" height="160" alt="" style="display:block; border:none;"/></td>
                                                    <td><img src="images/mailing/mail_factura_21.jpg" width="14" height="160" alt="" style="display:block; border:none;"/></td>
                                                    <td><a href="https://www.kia.com.ec/usuarios/registro.html" target="_blank"><img src="images/mailing/mail_factura_22.jpg" width="178" height="160" alt="" style="display:block; border:none;"/></a></td>
                                                    <td><img src="images/mailing/mail_factura_23.jpg" width="14" height="160" alt="" style="display:block; border:none;"/></td>
                                                    <td><a href="https://www.kia.com.ec/Atencion-al-Cliente/prueba-de-manejo.html" target="_blank"><img src="images/mailing/mail_factura_24.jpg" width="178" height="160" alt="" style="display:block; border:none;"/></a></td>
                                                    <td><img src="images/mailing/mail_factura_25.jpg" width="67" height="160" alt="" style="display:block; border:none;"/></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="https://www.kia.com.ec/" target="_blank"><img src="images/mailing/mail_factura_26.jpg" width="685" height="130" alt="" style="display:block; border:none;"/></a></td>
                                    </tr>
                                </table>
                            </body>';
                if (sendEmailFunction('servicioalcliente@kiamail.com.ec', "Kia Motors Ecuador", $array_mails_us, $array_noms_us, html_entity_decode("Formulario enviado desde Kia.com.ec: Solicitud de Cotizacion"), $body, $ccEmail_array, $ccEmail_array, 0)) {
                    // GRABAR DATOS DE EMAIL ENVIADO A BASE DE DATOS
                    // CONCESIONARIOS DE GRUPO ASIAUTO Y KMOTOR
                    if($usuario_id != 0){
                        $sqlExt = "INSERT INTO gestion_emails_enviados (user_id, id_concesionario, id_informacion, modelo, version, fecha) "
                                . "VALUES({$usuario_id},{$form_cotizacion->dealerid},{$lastIdCasos},{$form_cotizacion->id_modelos},{$form_cotizacion->id_version},'{$fecha}')";
                        $resultExterior =   mysql_query($sqlExt) or die("Could not execute query");  
                    }
                } else {
                    
                }
                $modelos = array(
                    "Picanto R" => "picanto",
                    "Rio R" => "rio",
                    "Cerato Forte" => "cerato_forte",
                    "Cerato R" => "cerato_r",
                    "Óptima Híbrido" => "optima",
                    "Quoris" => "quoria",
                    "Carens R" => "new_carens",
                    "Grand Carnival" => "grand_carnival",
                    "Sportage Active" => "sportage",
                    "Sportage R" => "new_sportage",
                    "K 2700 Cabina Doble" => "k2700",
                    "K 3000" => "k3000"
                );
                
                header('Location: https://www.kia.com.ec/Ingreso-de-mensaje/confirmacion.html?model=' . $modelos[$nombreAuto]);
            }
        }
    } else {
        header('Location: https://www.kia.com.ec/Ingreso-de-mensaje/error.html');
    }
}
?>
