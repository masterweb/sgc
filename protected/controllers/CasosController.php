<?php

class CasosController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/call';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
                //'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'seguimiento', 'admin', 'edit', 'search',
                    'exportExcel', 'getcedula', 'reportes', 'observacion', 'informativo',
                    'vernotificacion', 'agradecimiento'),
//                'actions' => array('create', 'update'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete', 'create'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionAgradecimiento() {
        $this->render('agradecimiento');
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function actionVernotificacion($id, $caso_id) {
        //echo 'id: '.$id.' ,caso id: '.$caso_id;
        //die();
        $model = $this->loadModel($caso_id);
        $not = new Notificaciones;
        $sql = "UPDATE notificaciones SET leido = 'READ' WHERE id={$id}";
        //die('sql: '.$sql);
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $rowCount = $command->execute(); // execute the non-query SQL
        $dataReader = $command->query(); // execute a query SQL
        //echo $rowCount;
        if ($rowCount > 0):
            $this->redirect(array('casos/update/' . $caso_id));
        endif;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Casos;
        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['Casos'])) {
//            echo '<pre>';
//            print_r($_POST);
//            echo '</pre>';
//            die();
            if ($_POST['Casos']['identificacion'] == 'ci') {
                $model->identificacion = $_POST['Casos']['identificacion'];
                $model->setscenario('ci');
            } else if ($_POST['Casos']['identificacion'] == 'ruc') {
                $model->identificacion = $_POST['Casos']['identificacion'];
                $model->setscenario('ruc');
            } else if ($_POST['Casos']['identificacion'] == 'pasaporte') {
                $model->provincia_domicilio = $_POST['Casos']['provincia_domicilio'];
                $model->ciudad_domicilio = $_POST['Casos']['ciudad_domicilio'];
                $model->identificacion = $_POST['Casos']['identificacion'];
                $model->pasaporte = $_POST['Casos']['pasaporte'];
            }

            if (isset($_POST['Casos']['estado'])) {
                $model->estado = $_POST['Casos']['estado'];
            }
            $model->attributes = $_POST['Casos'];
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            $model->responsable = Yii::app()->user->getId();
            $model->tipo_venta = $_POST['Casos']['tipo_venta'];
            if (isset($_POST['Casos']['concesionario']) && !empty($_POST['Casos']['concesionario'])) {
                $model->concesionario = $_POST['Casos']['concesionario'];
            }
            if (isset($_POST['Casos']['modelo']) && !empty($_POST['Casos']['modelo'])) {
                $model->modelo = $_POST['Casos']['modelo'];
            }
            if (isset($_POST['Casos']['version']) && !empty($_POST['Casos']['version'])) {
                $model->version = $_POST['Casos']['version'];
            }

            // SI EL CASO ES ASISTENCIA KIA EMAIL, DIRECCION DOMICILIO, SECTOR DOMICILIO, CELULAR 
            // Y COMENTARIOS NO SON OBLIGATORIOS
            if ($_POST['Casos']['tema'] == 7) {
                if (empty($_POST['Casos']['email']))
                    $model->email = 'supervisoroperaciones2@centerphone.com.ec';
                if (empty($_POST['Casos']['celular']))
                    $model->celular = '0999999999';
                if (empty($_POST['Casos']['direccion']))//direccion domicilio
                    $model->direccion = 'NA';
                if (empty($_POST['Casos']['sector']))//sector domicilio
                    $model->sector = 'NA';
                if (empty($_POST['Casos']['comentario']))
                    $model->comentario = 'NA';
            }

            // SI EL CASO ES SUGERENCIAS E INQUIETUDES EMAIL, DIRECCION DOMICILIO, SECTOR DOMICILIO, TELEFONO 
            // Y COMENTARIOS NO SON OBLIGATORIOS
            if ($_POST['Casos']['tema'] == 6) {
                //die('enter sugerencias');
                if (empty($_POST['Casos']['email']))
                    $model->email = 'supervisoroperaciones2@centerphone.com.ec';
                if (empty($_POST['Casos']['telefono']))
                    $model->telefono = '022415222';
                if (empty($_POST['Casos']['direccion']))//direccion domicilio
                    $model->direccion = 'NA';
                if (empty($_POST['Casos']['sector']))//sector domicilio
                    $model->sector = 'NA';
                if (empty($_POST['Casos']['comentario']))
                    $model->comentario = 'NA';
            }
            $tipoFormularioPV = 'caso';

            $model->tipo_form = $tipoFormularioPV;
            if ($model->save()) {

                // CREAR NOTIFICACION 
                /*$modelN = new Notificaciones;
                $modelN->user_id = Yii::app()->user->getId();
                $modelN->caso_id = $model->id;
                if ($tipoFormularioPV == 'informativo') {
                    $modelN->descripcion = "El usuario " . $this->getResponsable(Yii::app()->user->getId()) . "  ha ingresado un nuevo caso informativo con la ID: " . $model->id;
                    $modelN->tipo_form = 'informativo';
                } else {
                    $modelN->descripcion = "El usuario " . $this->getResponsable(Yii::app()->user->getId()) . "  ha ingresado un nuevo caso con la ID: " . $model->id;
                    $modelN->tipo_form = 'caso';
                }

                $modelN->fecha = date("Y-m-d H:i:s");
                $modelN->save();*/

                $fecha_atencion = date("Y-m-d");

                // CREAR HISTORIAL 
                $model2 = new Historial;
                $model2->fecha = date("Y-m-d H:i:s");
                $model2->id_caso = $model->id;
                $model2->tema = $_POST['Casos']['tema'];
                $model2->subtema = $_POST['Casos']['subtema'];
                $model2->id_responsable = Yii::app()->user->getId();
                $model2->estado = $_POST['Casos']['estado'];
                $model2->observaciones = $_POST['Casos']['comentario'];
                $model2->save();

                require_once 'email/mail_func.php';


                // SI EL TEMA ES INFORMACION DE VEHICULOS NUEVOS---------------------------------------------
                // Y SUBTEMA ES COTIZACION-------------------------------------------------------------------
                if ($_POST['Casos']['subtema'] == 9) {
                    
                }


                //-----------EMAIL PARA CASO INFORMATIVO------------------
                if ($tipoFormularioPV == 'informativo'):
                    $asunto = 'Formulario enviado desde Call Center Informativo:';
                    $general =
                            '<body style="margin: 10px;">
                            <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;">
                                <div align="center"><img src="images/header_mail.jpg"/><br>
                                    <table width="600" cellpadding="12">
                                        <tr>
                                            <td colspan="2">
                                                <p>Estimado administrador</p>
                                                <p>
                                                    El cliente ' . $_POST['Casos']['nombres'] . ' ' . $_POST['Casos']['apellidos'] . ' ha sido registrado en el <strong>Tema: ' . $this->getTema($_POST['Casos']['tema']) . ', Subtema: ' . $this->getSubtema($_POST['Casos']['subtema']) . ' </strong>con los siguientes datos
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p style="color:#A81F2F;"><strong>Datos Usuario</strong></p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><strong>ID:</strong> ' . $model->id . '</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nombre:</strong> ' . $_POST['Casos']['nombres'] . ' ' . $_POST['Casos']['apellidos'] . '</td>
                                            <td><strong>Cédula:</strong> ' . $_POST['Casos']['cedula'] . '</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Provincia:</strong> ' . $this->getProvincia($_POST['Casos']['provincia']) . '</td>
                                            <td><strong>Ciudad:</strong> ' . $this->getCiudad($_POST['Casos']['ciudad']) . '</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Teléfono</strong>: ' . $_POST['Casos']['telefono'] . '</td>
                                            <td><strong>Celular</strong>: ' . $_POST['Casos']['celular'] . '</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong> ' . $_POST['Casos']['email'] . '</td>
                                            <td><strong>Estado</strong>: Abierto </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Concesionario:</strong> ' . $this->getConcesionario($_POST['Casos']['concesionario']) . '</td>
                                            <td><strong>Responsable:</strong> ' . $this->getResponsable(Yii::app()->user->getId()) . '</td>    
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><strong>Comentario:</strong></p>
                                                <p>' . $_POST['Casos']['comentario'] . '</p></td>
                                        </tr>
                                    </table>
                                    <br>
                                    <a href="https://www.kia.com.ec/intranet/callcenter/index.php/casos/update/' . $model->id . '"><img src="images/seguimiento.jpg"/></a>
                                    <br>
                                    <img src="images/footer_mail.jpg"/>
                                </div>
                            </div>
                        </body>';
                    $codigohtml = $general;
                    $tipo = 'informativo';
                    $headers = 'From: servicioalcliente@kiamail.com.ec' . "\r\n";
                    $headers .= 'Content-type: text/html' . "\r\n";
                    $email = $_POST['Casos']['email']; //email administrador

                    sendEmailInfo('servicioalcliente@kiamail.com.ec', "Call Center Informativo", $email, html_entity_decode($asunto), $codigohtml, $tipo);

                //-----------EMAIL PARA CASO CASO------------------    
                elseif ($tipoFormularioPV == 'caso'):
                    $emailSP = 'vlondono@kia.com.ec'; // email super administrador
                    $emailCallCenter = 'supervisoroperaciones2@centerphone.com.ec';
                    $emailVP = 'vlondono@kia.com.ec';

                    if ($_POST['Casos']['tipo_venta'] == 'venta') {
                        $emailVP = 'vlondono@kia.com.ec';
                        $tipo = 'venta';
                    } else if ($_POST['Casos']['tipo_venta'] == 'postventa') {
                        $emailVP = 'vlondono@kia.com.ec';
                        $tipo = 'postventa';
                    }
                    $general = '<body style="margin: 10px;">
                        <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;">
                            <div align="center"><img src="images/header_mail.jpg"/><br>
                                <table width="600" cellpadding="12">
                                    <tr>
                                        <td colspan="2">
                                            <p>Estimado administrador</p>
                                            <p>
                                                El cliente ' . $_POST['Casos']['nombres'] . ' ' . $_POST['Casos']['apellidos'] . ' ha sido registrado en el <strong>Tema: ' . $this->getTema($_POST['Casos']['tema']) . ', Subtema: ' . $this->getSubtema($_POST['Casos']['subtema']) . ' </strong>con los siguientes datos
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><p style="color:#A81F2F;"><strong>Datos Usuario</strong></p></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong>ID:</strong> ' . $model->id . '</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nombre:</strong> ' . $_POST['Casos']['nombres'] . ' ' . $_POST['Casos']['apellidos'] . '</td>';
                    if (!empty($_POST['Casos']['cedula']))
                        $general .= '<td><strong>Cédula:</strong> ' . $_POST['Casos']['cedula'] . '</td>';
                    if (!empty($_POST['Casos']['ruc']))
                        $general .= '<td><strong>RUC:</strong> ' . $_POST['Casos']['ruc'] . '</td>';
                    if (!empty($_POST['Casos']['pasaporte']))
                        $general .= '<td><strong>Pasaporte:</strong> ' . $_POST['Casos']['pasaporte'] . '</td>';


                    $general .= '</tr>
                                    <tr>
                                        <td><strong>Provincia:</strong> ' . $this->getProvincia($_POST['Casos']['provincia']) . '</td>
                                        <td><strong>Ciudad:</strong> ' . $this->getCiudad($_POST['Casos']['ciudad']) . '</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Teléfono</strong>: ' . $_POST['Casos']['telefono'] . '</td>
                                        <td><strong>Celular</strong>: ' . $_POST['Casos']['celular'] . '</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong> ' . $_POST['Casos']['email'] . '</td>
                                        <td><strong>Estado</strong>: Abierto </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Concesionario:</strong> ' . $this->getConcesionario($_POST['Casos']['concesionario']) . '</td>
                                        <td><strong>Responsable:</strong> ' . $this->getResponsable(Yii::app()->user->getId()) . '</td>    
                                    </tr>
                                    <tr>
                                        <td colspan="2"><p><strong>Comentario:</strong></p>
                                            <p>' . $_POST['Casos']['comentario'] . '</p></td>
                                    </tr>
                                </table>
                                <br>
                                <a href="https://www.kia.com.ec/intranet/callcenter/index.php/casos/update/' . $model->id . '"><img src="images/seguimiento.jpg"/></a>
                                <br>
                                <img src="images/footer_mail.jpg"/>
                            </div>
                        </div>
                    </body>';
                    $codigohtml = $general;

                    $headers = 'From: servicioalcliente@kiamail.com.ec' . "\r\n";
                    $headers .= 'Content-type: text/html' . "\r\n";

                    $asunto = 'Formulario enviado desde Call Center:';
                    sendEmailFunctionConc('servicioalcliente@kiamail.com.ec', "Call Center", $emailSP, html_entity_decode($asunto), $codigohtml, 'utf-8', '', '', '', $emailVP, $emailCallCenter);

                    // SI DATOS VIENEN DE COTIZACION ENVIAR LOS DATOS A BASE DE DATOS DE COTIZACIONES adminkia_b4s3k1
                    // ENVIO DE EMAIL AL CONCESIONARIO RESPECTIVO
                    //die('subtema: '.$_POST['Casos']['subtema']);
                    if ($_POST['Casos']['subtema'] == 9) {
                        // CONECCION A LA BASE DE DATOS DE KIA ADMINKIA
                        DEFINE('DB_USER', 'root');
                        DEFINE('DB_PASSWORD', 'lcz3QmXen4dc');
                        DEFINE('DB_HOST', 'localhost');
                        DEFINE('DB_NAME', 'adminkia_b4s3k1');
                        $connection = @mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)
                                or die('Could not connect to database');
                        mysql_select_db(DB_NAME)
                                or die('Could not select database');
                        // EMAILS DE LOS CONCESIONARIOS DE ACUERDO A SU ID
                        $sqlEmail = "SELECT para_email, cc_email, cco_email FROM dealers_email 
                        WHERE dealersid = {$_POST['Casos']['concesionario']} 
                        AND id_tipo_atencion = 1";
                        $resultEmail = mysql_query($sqlEmail) or die("Could not execute query");
                        $para_email = "";
                        $cc_email = "";
                        $cco_email = "";
                        $row = mysql_fetch_assoc($resultEmail);
                        $paraEmail = $row['para_email'];
                        $ccEmail = $row['cc_email'];
                        $ccoEmail = $row['cco_email'];

                        //die('enter subtema 9');

                        $arrayModelos = array(
                            84 => 'Picanto R',
                            85 => 'Rio R',
                            24 => 'Cerato Forte',
                            90 => 'Cerato R',
                            89 => 'Óptima Híbrido',
                            88 => 'Quoris',
                            20 => 'Carens R',
                            11 => 'Grand Carnival',
                            21 => 'Sportage Active',
                            83 => 'Sportage R',
                            10 => 'Sorento',
                            25 => 'K 2700 Cabina Simple',
                            87 => 'K 2700 Cabina Doble',
                            86 => 'K 3000'
                        );

                        $query = "INSERT into atencion_detalle 
                    (nombre, apellido, cedula, direccion, telefono, 
                    celular, email, id_modelos, id_version, cityid, dealerid, 
                    id_atencion, fecha_form, ips, id_origen )
                    VALUES ('{$_POST['Casos']['nombres']}','{$_POST['Casos']['apellidos']}', '{$_POST['Casos']['cedula']}', 
                        '{$_POST['Casos']['direccion']}','{$_POST['Casos']['telefono']}','{$_POST['Casos']['celular']}', 
                         '{$_POST['Casos']['email']}',{$_POST['Casos']['modelo']},{$_POST['Casos']['version']}, 
                         {$_POST['Casos']['ciudad']}, {$_POST['Casos']['concesionario']},1, '{$fecha_atencion}', 
                             '65.99.241.180',4)";
                        //die('query: '.$query);         
                        $result = mysql_query($query) or die("Could not execute query");

                        // ENVIO DE EMAIL AL CONCESIONARIO
                        $mensajeEnvio = "";
                        $general = '<table border=0>
                    <tr><td><b>Concesionario:</b></td><td>' . $this->getConcesionario($_POST['Casos']['concesionario']) . '</td></tr>
                    <tr><td><b>Dir. Concesionario:</b></td><td>' . $this->getDireccion($_POST['Casos']['concesionario']) . '</td></tr>
                    <tr><td>&nbsp;</td></tr>				
                    <tr><td><b>Nombres:</b></td><td>' . $_POST['Casos']['nombres'] . '</td></tr>
                    <tr><td><b>Apellidos:</b></td><td>' . $_POST['Casos']['apellidos'] . '</td></tr>';
                        if (!empty($_POST['Casos']['cedula']))
                            $general .= '<tr><td><b>C&eacute;dula:</b></td><td>' . $_POST['Casos']['cedula'] . '</td></tr>';
                        if (!empty($_POST['Casos']['ruc']))
                            $general .= '<tr><td><b>RUC:</b></td><td>' . $_POST['Casos']['ruc'] . '</td></tr>';
                        if (!empty($_POST['Casos']['pasaporte']))
                            $general .= '<tr><td><b>Pasaporte:</b></td><td>' . $_POST['Casos']['pasaporte'] . '</td></tr>';

                        $general .= '<tr><td><b>Direcci&oacute;n:</b></td><td>' . $_POST['Casos']['direccion'] . '</td></tr>
                    <tr><td><b>Tel&eacute;fono:</b></td><td>' . $_POST['Casos']['telefono'] . '</td></tr>
                    <tr><td><b>Celular:</b></td><td>' . $_POST['Casos']['celular'] . '</td></tr>
                    <tr><td><b>Veh&iacute;culo de Inter&eacute;s</b>:</td><td>' . $arrayModelos[$_POST['Casos']['modelo']] . '</td></tr>
                    <tr><td><b>Modelo:</b></td><td>' . $this->getVersion($_POST['Casos']['version']) . '</td></tr>
                    <tr><td><b>Ciudad:</b></td><td>' . $this->getCiudad($_POST['Casos']['ciudad']) . '</td></tr>
                    <tr><td><b>E-mail:</b></td><td>' . $_POST['Casos']['email'] . '</td></tr>
                    </table>';

                        $codigohtml = $general;
                        $headers = 'From: cotizacion@kia.com.ec' . "\r\n";
                        $headers .= 'Content-type: text/html' . "\r\n";

                        $asunto = 'Formulario enviado desde Call Center Kia: Solicitud de Cotización - Concesionario: ' . $this->getConcesionario($_POST['Casos']['concesionario']) . ', Id form: ' . mysql_insert_id($connection);
                        //$asunto = 'Formulario enviado desde Kia.com.ec: Solicitud de Cotización - Concesionario: ' . $this->getConcesionario($_POST['Casos']['concesionario']) . ', Id form: Pruebas' ;
                        $array_mails = explode(",", $paraEmail);

                        $noms = 'info@kiamail.com.ec,' . $paraEmail;
                        $array_noms = explode(",", $noms);

                        $ccEmail = 'info@kiamail.com.ec,' . $ccEmail;
                        $ccEmail_array = explode(",", $ccEmail);
                        $ccoEmail = 'info@kiamail.com.ec,' . $ccoEmail;
                        $ccoEmail_array = explode(",", $ccoEmail);
                        //die('before send');

                        if (sendEmailFunctionCotizador('comunidad@kiamail.com.ec', "Kia Motors Ecuador", $array_mails, $array_noms, html_entity_decode($asunto), $codigohtml, $ccEmail_array, $ccoEmail_array, 1)) {
                            
                        } else {
                            
                        }
                    }// FIN DE ENVIO DE EMAIL AL CONCESIONARIO
                    // enviar email al cliente cuando se crea un caso si el campo email no esta vacio
                    $general = '<body>
                            <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;">
                                <div align="center"><img src="images/header_mail.jpg"/><br>
                                    <table width="600" cellpadding="12">
                                        <tr>
                                            <td colspan="2">
                                                <p>Estimado ' . $_POST['Casos']['nombres'] . ' ' . $_POST['Casos']['apellidos'] . '</p>
                                                <p>Su caso ha sido abierto con el número  <strong>' . $model->id . '</strong>,<br>
                                                    próximamente nos comunicaremos con usted para informarle lo sucedido con su caso.
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p>
                                                    Atentamente<br>
                                                    1800 Kia Kia<br>
                                                    Kia Motors Ecuador
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <br>
                                <img src="images/footer_mail.jpg"/>
                            </div>
                        </body>';
                    $codigohtml = $general;
                    $headers = 'From: servicioalcliente@kiamail.com.ec' . "\r\n";
                    $headers .= 'Content-type: text/html' . "\r\n";
                    $asunto = 'Formulario enviado desde Call Center';
                    $tipo = 'caso';
                    //if (!empty($_POST['Casos']['email'])):
                        //sendEmailCliente('servicioalcliente@kiamail.com.ec', "Call Center", $_POST['Casos']['email'], html_entity_decode($asunto), $codigohtml, $tipo);
                    //else:
                        sendEmailCliente('servicioalcliente@kiamail.com.ec', "Call Center", 'supervisoroperaciones2@centerphone.com.ec', html_entity_decode($asunto), $codigohtml, $tipo);
                    //endif;

                endif; // FIN DE ENVIO PARA CASOS
                //die('Antes de setflash');
                Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
                $this->redirect(array('casos/create'));
            } // END model->save
            //$this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Casos'])) {
//            echo '<pre>';
//            print_r($_POST);
//            echo '</pre>';
//            die();
            if ($_POST['identificacion'] == 'ruc')
                $model->setscenario('ruc');
            if ($_POST['identificacion'] == 'pasaporte')
                $model->setscenario('pasaporte');
            $model->attributes = $_POST['Casos'];
            $model->estado = $_POST['Casos']['estado'];
            if (($_POST['Casos']['estado'] === 'Cerrado') && !empty($_POST['Casos']['observaciones']) && isset($_POST['closed_case'])) {
                //die('enter closed case');
                $model->estado = 'Proceso';
            }
            if(isset($_POST['Casos']['concesionario']))
                $model->concesionario = $_POST['Casos']['concesionario'];
            //die('model concesionario: '.$model->concesionario);
            if ($_POST['Casos']['tipo_form'] == 'caso') {
                $model->tipo_venta = $_POST['Casos']['tipo_venta'];
            }
            // SI EL CASO ES ASISTENCIA KIA EMAIL, DIRECCION DOMICILIO, SECTOR DOMICILIO, CELULAR 
            // Y COMENTARIOS NO SON OBLIGATORIOS
            if ($_POST['Casos']['tema'] == 7) {
                if (empty($_POST['Casos']['email']))
                    $model->email = 'supervisoroperaciones2@centerphone.com.ec';
                if (empty($_POST['Casos']['celular']))
                    $model->celular = '0999999999';
                if (empty($_POST['Casos']['direccion']))
                    $model->direccion = 'NA';
                if (empty($_POST['Casos']['sector']))
                    $model->sector = 'NA';
                if (empty($_POST['Casos']['comentario']))
                    $model->comentario = 'NA';
            }

            // SI EL CASO ES SUGERENCIAS E INQUIETUDES EMAIL, DIRECCION DOMICILIO, SECTOR DOMICILIO, TELEFONO 
            // Y COMENTARIOS NO SON OBLIGATORIOS
            if ($_POST['Casos']['tema'] == 6) {
                //die('enter sugerencias');
                if (empty($_POST['Casos']['email']))
                    $model->email = 'supervisoroperaciones2@centerphone.com.ec';
                if (empty($_POST['Casos']['telefono']))
                    $model->telefono = '022415222';
                if (empty($_POST['Casos']['direccion']))
                    $model->direccion = 'NA';
                if (empty($_POST['Casos']['sector']))
                    $model->sector = 'NA';
                if (empty($_POST['Casos']['comentario']))
                    $model->comentario = 'NA';
            }
            $tipoFormularioPV = '';
            $subtema = $_POST['Casos']['subtema'];
            $model->tipo_form = 'caso';

            //$model->observaciones = $_POST['Casos']['observaciones'];
            if ($model->save()) {
                require_once 'email/mail_func.php';

                // si se cierra un caso enviar un email de agardecimiento con la id del cliente
                if ($_POST['Casos']['estado'] === 'Cerrado') {

                    // ENVIA NOTIFICACION DE CASO CERRADO
                    $modelN = new Notificaciones;
                    $modelN->user_id = Yii::app()->user->getId();
                    $modelN->caso_id = $model->id;
                    $modelN->tipo_form = $_POST['Casos']['tipo_form'];
                    $modelN->descripcion = "El usuario " . $this->getResponsable(Yii::app()->user->getId()) . "  ha cerrado caso con la ID: " . $model->id;
                    $modelN->fecha = date("Y-m-d H:i:s");
                    $modelN->save();

                    // ENVIO DE EMAIL CASO CERRADO
                    $general = '<body>
                        <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;">
                            <div align="center"><img src="images/header_mail.jpg"/><br>
                                <table width="600" cellpadding="12">
                                    <tr>
                                        <td colspan="2">
                                            <p>Estimado ' . $_POST['Casos']['nombres'] . ' ' . $_POST['Casos']['apellidos'] . '</p>
                                            <p>
                                                Su caso número <strong>' . $model->id . '</strong>  ha sido cerrado.<br>
                                            </p>
                                            <p>
                                                Gracias por comunicarse con nosotros.<br>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>
                                                Atentamente<br>
                                                1800 Kia Kia<br>
                                                Kia Motors Ecuador
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <br>
                            <img src="images/footer_mail.jpg"/>
                        </div>
                    </body>';
                    $codigohtml = $general;
                    $headers = 'From: servicioalcliente@kiamail.com.ec' . "\r\n";
                    $headers .= 'Content-type: text/html' . "\r\n";

                    $asunto = 'Formulario enviado desde Call Center';
                    $tipo = 'cerrado';
                    sendEmailInfo('servicioalcliente@kiamail.com.ec', "Call Center", 'vlondono@kia.com.ec', html_entity_decode($asunto), $codigohtml, $tipo);

                    // email al concesionario, postventa-venta, super administrador
                    $emailSP = 'vlondono@kia.com.ec'; // email super administrador
                    $emailCallCenter = 'supervisoroperaciones2@centerphone.com.ec';

                    if ($model->tipo_venta == 'venta') {
                        $emailVP = 'vlondono@kia.com.ec';
                        $tipo = 'venta';
                    } else if ($model->tipo_venta == 'postventa') {
                        $emailVP = 'vlondono@kia.com.ec';
                        $tipo = 'postventa';
                    }
                    $general = '<body style="margin: 10px;">
                        <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;">
                            <div align="center"><img src="images/header_mail.jpg"/><br>
                                <table width="600" cellpadding="12">
                                    <tr>
                                        <td colspan="2">
                                            <p>Estimado administrador</p>
                                            <p>
                                                El caso del cliente ' . $_POST['Casos']['nombres'] . ' ' . $_POST['Casos']['apellidos'] . ' del <strong>Tema: ' . $this->getTema($_POST['Casos']['tema']) . ', Subtema: ' . $this->getSubtema($_POST['Casos']['subtema']) . ' </strong>se ha cerrado con los siguientes datos
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><p style="color:#A81F2F;"><strong>Datos Usuario</strong></p></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong>ID:</strong> ' . $model->id . '</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong>Tipo Formulario</strong> ' . $model->tipo_venta . '</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nombre:</strong> ' . $_POST['Casos']['nombres'] . ' ' . $_POST['Casos']['apellidos'] . '</td>';
                    if (!empty($_POST['Casos']['cedula']))
                        $general .= '<td><strong>Cédula:</strong> ' . $_POST['Casos']['cedula'] . '</td>';
                    if (!empty($_POST['Casos']['ruc']))
                        $general .= '<td><strong>RUC:</strong> ' . $_POST['Casos']['ruc'] . '</td>';
                    if (!empty($_POST['Casos']['pasaporte']))
                        $general .= '<td><strong>Pasaporte:</strong> ' . $_POST['Casos']['pasaporte'] . '</td>';

                    $general .= '</tr>
                                    <tr>
                                        <td><strong>Provincia:</strong> ' . $this->getProvincia($_POST['Casos']['provincia']) . '</td>';
                    if (isset($_POST['Casos']['ciudad'])) {
                        $general .= '<td><strong>Ciudad:</strong> ' . $this->getCiudad($_POST['Casos']['ciudad']) . '</td>';
                    }
                    $general .= '</tr>
                                    <tr>
                                        <td><strong>Teléfono</strong>: ' . $_POST['Casos']['telefono'] . '</td>
                                        <td><strong>Celular</strong>: ' . $_POST['Casos']['celular'] . '</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong> ' . $_POST['Casos']['email'] . '</td>
                                        <td><strong>Estado</strong>:' . $_POST['Casos']['estado'] . '</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Concesionario:</strong> ' . $this->getConcesionario($_POST['Casos']['concesionario']) . '</td>
                                        <td><strong>Responsable:</strong> ' . $this->getResponsable(Yii::app()->user->getId()) . '</td>    
                                    </tr>
                                    <tr>
                                        <td colspan="2"><p><strong>Observaciones:</strong></p>
                                            <p>' . $_POST['Casos']['observaciones'] . '</p></td>
                                    </tr>
                                </table>
                                <br>
                                <a href="https://www.kia.com.ec/intranet/callcenter/index.php/casos/update/' . $model->id . '"><img src="images/seguimiento.jpg"/></a>
                                <br>
                                <img src="images/footer_mail.jpg"/>
                            </div>
                        </div>
                    </body>';
                    $codigohtml = $general;

                    $headers = 'From: servicioalcliente@kiamail.com.ec' . "\r\n";
                    $headers .= 'Content-type: text/html' . "\r\n";

                    $asunto = 'Formulario enviado desde Call Center:';
                    sendEmailFunctionConc('servicioalcliente@kiamail.com.ec', "Call Center", $emailSP, html_entity_decode($asunto), $codigohtml, 'utf-8', '', '', '', $emailVP, $emailCallCenter);
                } elseif ($_POST['Casos']['estado'] === 'Proceso') {
                    // CONECCION A LA BASE DE DATOS DE KIA ADMINKIA
                    DEFINE('DB_USER', 'root');
                    DEFINE('DB_PASSWORD', 'lcz3QmXen4dc');
                    DEFINE('DB_HOST', 'localhost');
                    DEFINE('DB_NAME', 'adminkia_b4s3k1');
                    $connection = @mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)
                            or die('Could not connect to database');
                    mysql_select_db(DB_NAME)
                            or die('Could not select database');

                    if (($_POST['Casos']['subtema'] == 9) && isset($_POST['Casos']['concesionario'])) {
                        // EMAILS DE LOS CONCESIONARIOS DE ACUERDO A SU ID
                        $sqlEmail = "SELECT para_email, cc_email, cco_email FROM dealers_email 
                        WHERE dealersid = {$_POST['Casos']['concesionario']} 
                        AND id_tipo_atencion = 1";
                        $resultEmail = mysql_query($sqlEmail) or die("Could not execute query");
                        $para_email = "";
                        $cc_email = "";
                        $cco_email = "";
                        $row = mysql_fetch_assoc($resultEmail);
                        $paraEmail = $row['para_email'];
                        $ccEmail = $row['cc_email'];
                        $ccoEmail = $row['cco_email'];
                        $array_mails = explode(",", $paraEmail);
                        $ccEmail_array = explode(",", $ccEmail);
                        $ccoEmail_array = explode(",", $ccoEmail);

                        $general = '<body style="margin: 10px;">
                        <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;">
                            <div align="center"><img src="images/header_mail.jpg"/><br>
                                <table width="600" cellpadding="12">
                                    <tr>
                                        <td colspan="2">
                                            <p>Estimado administrador</p>
                                              <p>
                                                    El cliente ' . $_POST['Casos']['nombres'] . ' ' . $_POST['Casos']['apellidos'] . ' ha actualizado el <strong>Tema de ' . $this->getTema($_POST['Casos']['tema']) . ' </strong>con los siguientes datos
                                              </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><p style="color:#A81F2F;"><strong>Datos Usuario</strong></p></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong>ID:</strong> ' . $model->id . '</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong>Tipo Formulario</strong> ' . $model->tipo_venta . '</td>
                                    </tr>  
                                    <tr>
                                        <td><strong>Nombre:</strong> ' . $_POST['Casos']['nombres'] . ' ' . $_POST['Casos']['apellidos'] . '</td>';
                                    if (!empty($_POST['Casos']['cedula']))
                                        $general .= '<td><strong>Cédula:</strong> ' . $_POST['Casos']['cedula'] . '</td>';
                                    if (!empty($_POST['Casos']['ruc']))
                                        $general .= '<td><strong>RUC:</strong> ' . $_POST['Casos']['ruc'] . '</td>';
                                    if (!empty($_POST['Casos']['pasaporte']))
                                        $general .= '<td><strong>Pasaporte:</strong> ' . $_POST['Casos']['pasaporte'] . '</td>';

                                    $general .= '</tr>
                                    <tr>
                                        <td><strong>Provincia:</strong> ' . $this->getProvincia($_POST['Casos']['provincia']) . '</td>
                                        <td><strong>Ciudad:</strong> ' . $this->getCiudad($_POST['Casos']['ciudad']) . '</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Teléfono</strong>: ' . $_POST['Casos']['telefono'] . '</td>
                                        <td><strong>Celular</strong>: ' . $_POST['Casos']['celular'] . '</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong> ' . $_POST['Casos']['email'] . '</td>
                                        <td><strong>Estado</strong>:' . $_POST['Casos']['estado'] . '</td>
                                    </tr>
                                    <tr>
                                    <tr>
                                        <td><strong>Concesionario:</strong> ' . $this->getConcesionario($_POST['Casos']['concesionario']) . '</td>
                                        <td><strong>Responsable:</strong> ' . $this->getResponsable(Yii::app()->user->getId()) . '</td>    
                                    </tr>
                                    <tr>
                                        <td colspan="2"><p><strong>Observaciones:</strong></p>
                                            <p>' . $_POST['Casos']['observaciones'] . '</p></td>
                                    </tr>
                                </table>
                                <br>
                                <a href="https://www.kia.com.ec/intranet/callcenter/index.php/casos/update/' . $model->id . '"><img src="images/seguimiento.jpg"/></a>
                                <br>
                                <img src="images/footer_mail.jpg"/>
                            </div>
                        </div>
                    </body>';
                        $codigohtml = $general;

                        $headers = 'From: servicioalcliente@kiamail.com.ec' . "\r\n";
                        $headers .= 'Content-type: text/html' . "\r\n";

                        $asunto = 'Formulario enviado desde Call Center:';

                        $tipo_venta = $model->tipo_venta;
                        if ($tipo_venta === 'venta') {
                            $emailVP = 'vlondono@kia.com.ec';
                            $tipo = 'venta';
                        } else if ($tipo_venta === 'postventa') {
                            $emailVP = 'vlondono@kia.com.ec';
                            $tipo = 'postventa';
                        }
                        $emailSP = 'vlondono@kia.com.ec'; // email super administrador
                        $emailCallCenter = 'vlondono@kia.com.ec';
                        sendEmailFunctionConc('servicioalcliente@kiamail.com.ec', "Call Center", $emailSP, html_entity_decode($asunto), $codigohtml, 'utf-8', '', '', '', $emailVP, $emailCallCenter);
                    }


                    // ENVIAR NOTIFICACION DE CASO ACTUALIZADO
                    /*$modelN = new Notificaciones;
                    $modelN->user_id = Yii::app()->user->getId();
                    $modelN->caso_id = $model->id;
                    $modelN->tipo_form = $_POST['Casos']['tipo_form'];
                    $modelN->descripcion = "El usuario " . $this->getResponsable(Yii::app()->user->getId()) . "  ha comentado el caso con la ID: " . $model->id;
                    $modelN->fecha = date("Y-m-d H:i:s");
                    $modelN->save();*/
                }

                // GUARDAR EN HISTORIAL
                date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
                $model2 = new Historial;
                $model2->fecha = date("Y-m-d H:i:s");
                $model2->id_caso = $_POST['Casos']['id'];
                $model2->tema = $_POST['Casos']['tema'];
                $model2->subtema = $_POST['Casos']['subtema'];
                $model2->id_responsable = Yii::app()->user->getId();
                $model2->estado = $model->estado;
                $model2->observaciones = $_POST['Casos']['observaciones'];
                $model2->save();
                //die('enter save');
                //Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
                //$this->redirect(array('casos/update'));
                $this->redirect(array('casos/agradecimiento'));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function actionObservacion($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Casos'])) {
//             echo '<pre>';
//              print_r($_POST);
//              echo '</pre>';
//            die();
            $model->attributes = $_POST['Casos'];
            $model->estado = $_POST['Casos']['estado'];
            $model->concesionario = $_POST['Casos']['concesionario'];
            //$model->observaciones = $_POST['Casos']['observaciones'];
            if ($model->save()) {
                $model2 = new Historial;
                $model2->fecha = date("Y-m-d H:i:s");
                $model2->id_caso = $_POST['Casos']['id'];
                $model2->tema = $_POST['Casos']['tema'];
                $model2->subtema = $_POST['Casos']['subtema'];
                $model2->id_responsable = Yii::app()->user->getId();
                $model2->estado = $_POST['Casos']['estado'];
                $model2->observaciones = $_POST['Casos']['observaciones'];
                $model2->save();
                //die('enter save');
                $this->redirect(array('casos/seguimiento'));
            }
        }

        $this->render('seguimiento');
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Casos');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Casos('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Casos']))
            $model->attributes = $_GET['Casos'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Casos the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Casos::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Casos $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'casos-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Get data table of casos
     */
    public function actionSeguimiento() {
        //$this->layout = '//layouts/call_1';
        // Note: Posts is a Posts model just pulling data from a posts database table.
        // Setup Criteria
        $cargo_id = (int) Yii::app()->user->getState('cargo_id');
        $id_responsable = Yii::app()->user->getId();
        $concesionario = $this->getDealerId($id_responsable);
        //die('concesionario: '.$concesionario);
        //$concesionario = Yii::app()->user->getState('dealer_id');
        if ($cargo_id === 83):
            $criteria = new CDbCriteria;
            //$criteria->condition = "tipo_form='caso'";
            $criteria->order = 'id desc';
        else:
            $criteria = new CDbCriteria;
            $criteria->condition = "responsable={$id_responsable}";
            $criteria->order = 'id desc';
        endif;


        // Count total records
        $pages = new CPagination(Casos::model()->count($criteria));

        // Set Page Limit
        $pages->pageSize = 10;

        // Apply page criteria to CDbCriteria
        $pages->applyLimit($criteria);

        // Grab the records
        $posts = Casos::model()->findAll($criteria);

        // Render the view
        $this->render('seguimiento', array(
            'model' => $posts,
            'pages' => $pages,
            'case' => 'default',
            'tipo_form' => 'caso'
                )
        );
    }

    /**
     * Get data table of informativos
     */
    public function actionInformativo() {
        //$this->layout = '//layouts/call_1';
        // Note: Posts is a Posts model just pulling data from a posts database table.
        // Setup Criteria

        $concesionario = Yii::app()->user->getState('dealer_id');
        $rol = Yii::app()->user->getState('roles');

        $criteria = new CDbCriteria;
        $criteria->condition = "tipo_form='informativo'";
        $criteria->order = 'id desc';

        // Count total records
        $pages = new CPagination(Casos::model()->count($criteria));

        // Set Page Limit
        $pages->pageSize = 10;

        // Apply page criteria to CDbCriteria
        $pages->applyLimit($criteria);

        // Grab the records
        $posts = Casos::model()->findAll($criteria);

        // Render the view
        $this->render('informativo', array(
            'model' => $posts,
            'pages' => $pages,
            'case' => 'default'
                )
        );
    }

    public function actionEdit($id) {
        $model = $this->loadModel($id);
        $this->render('edit', array(
            'model' => $model,
        ));
    }

    public function actionSearch() {
        $model = new Casos;
        $getParams = array();
//        echo '<pre>';
//        print_r($_GET);
//        echo '</pre>';
//        die();
        $cargo_id = (int) Yii::app()->user->getState('cargo_id');
        $id_responsable = Yii::app()->user->getId();
        $concesionario = Yii::app()->user->getState('dealer_id');
        $rol = Yii::app()->user->getState('roles');

        if (isset($_GET['Casos'])) {
            $tipo_form = $_GET['Casos']['tipo_form'];
            if ($_GET['Casos']['tipo_form'] == 'caso') {
                $render = 'seguimiento';
            } else {
                $render = 'informativo';
            }
//-------------- busqueda por defecto 
            if (empty($_GET['Casos']['tema']) && empty($_GET['Casos']['subtema']) && empty($_GET['Casos']['fecha']) && empty($_GET['Casos']['estado'])) {

                if ($cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                    $criteria = new CDbCriteria;
                    //$criteria->condition = "tipo_form='{$tipo_form}'";
                    $criteria->order = 'id desc';
                else:
                    $criteria = new CDbCriteria;
                    $criteria->condition = "responsable={$id_responsable}";
                    $criteria->order = 'id desc';
                endif;

                $pages = new CPagination(Casos::model()->count($criteria));
                // Set Page Limit
                $pages->pageSize = 10;

                // Apply page criteria to CDbCriteria
                $pages->applyLimit($criteria);

                // Grab the records
                $posts = Casos::model()->findAll($criteria);
                $title_busqueda = 'Búsqueda general';
                $this->render($render, array('model' => $posts, 'pages' => $pages, 'getParams' => $getParams, 'title_busqueda' => $title_busqueda));
            }
//-------------- busqueda por temas
            if (!empty($_GET['Casos']['tema']) && empty($_GET['Casos']['subtema']) && empty($_GET['Casos']['fecha']) && empty($_GET['Casos']['estado'])) {

                if ($cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                    $criteria = new CDbCriteria;
                    $criteria->condition = "tema = {$_GET['Casos']['tema']}";
                    $criteria->order = 'id desc';

                else:
                    $criteria = new CDbCriteria;
                    $criteria->condition = "responsable={$id_responsable} and tema = {$_GET['Casos']['tema']}";
                    $criteria->order = 'id desc';
                endif;


                $pages = new CPagination(Casos::model()->count($criteria));
                // Set Page Limit
                $pages->pageSize = 10;

                // Apply page criteria to CDbCriteria
                $pages->applyLimit($criteria);

                // Grab the records
                $posts = Casos::model()->findAll($criteria);
                $getParams['tema'] = $_GET['Casos']['tema'];
                $title_busqueda = 'Búsqueda por Tema: ' . $this->getTema($_GET['Casos']['tema']);
                $this->render($render, array('model' => $posts, 'pages' => $pages, 'getParams' => $getParams, 'title_busqueda' => $title_busqueda));
            }
//-------------- busqueda por tema y subtema
            if (!empty($_GET['Casos']['tema']) && !empty($_GET['Casos']['subtema']) && empty($_GET['Casos']['fecha']) && empty($_GET['Casos']['estado'])) {

                if ($cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                    $criteria = new CDbCriteria(array(
                                "condition" => "tema = {$_GET['Casos']['tema']} AND subtema = {$_GET['Casos']['subtema']} ",
                                "order" => 'id desc'
                            ));
                else:
                    $criteria = new CDbCriteria;
                    $criteria->condition = "responsable={$id_responsable} and tema = {$_GET['Casos']['tema']} AND subtema = {$_GET['Casos']['subtema']} ";
                    $criteria->order = 'id desc';
                endif;
                //$searchCasos = Casos::model()->findAll($criteria);
                $pages = new CPagination(Casos::model()->count($criteria));
                // Set Page Limit
                $pages->pageSize = 10;

                // Apply page criteria to CDbCriteria
                $pages->applyLimit($criteria);

                // Grab the records
                $posts = Casos::model()->findAll($criteria);
                $getParams['tema'] = $_GET['Casos']['tema'];
                $getParams['subtema'] = $_GET['Casos']['subtema'];
                $title_busqueda = 'Búsqueda por Tema: ' . $this->getTema($_GET['Casos']['tema']) . ' , Subtema: ' . $this->getSubtema($_GET['Casos']['subtema']);
                $this->render($render, array('model' => $posts, 'pages' => $pages, 'getParams' => $getParams, 'title_busqueda' => $title_busqueda));
            }
//-------------- busqueda por fecha
            if (!empty($_GET['Casos']['fecha']) && empty($_GET['Casos']['tema']) && empty($_GET['Casos']['subtema']) && empty($_GET['Casos']['estado'])) {
                //die('enter fecha');
                if ($_GET['Casos']['tipo_fecha'] === 'igual') {
                    if ($cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                        $criteria = new CDbCriteria(array(
                                    "condition" => "fecha LIKE '{$_GET['Casos']['fecha']}%' ",
                                    "order" => 'id desc'
                                ));
                    else:
                        $criteria = new CDbCriteria;
                        $criteria->condition = "responsable={$id_responsable} and fecha = '{$_GET['Casos']['fecha']}' ";
                        $criteria->order = 'id desc';
                    endif;
                }else {
                    if ($cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                        $criteria = new CDbCriteria(array(
                                    "condition" => "date(fecha) BETWEEN '{$_GET['Casos']['fecha']}' AND '{$_GET['Casos']['fecha2']}' ",
                                    "order" => 'id desc'
                                ));
                    else:
                        $criteria = new CDbCriteria;
                        $criteria->condition = "responsable={$id_responsable} and date(fecha) BETWEEN '{$_GET['Casos']['fecha']}' AND '{$_GET['Casos']['fecha2']}' ";
                        $criteria->order = 'id desc';
                    endif;
                }

                $pages = new CPagination(Casos::model()->count($criteria));
                // Set Page Limit
                $pages->pageSize = 10;

                // Apply page criteria to CDbCriteria
                $pages->applyLimit($criteria);

                // Grab the records
                $posts = Casos::model()->findAll($criteria);
                $getParams['fecha'] = $_GET['Casos']['fecha'];
                if ($_GET['Casos']['tipo_fecha'] === 'igual') {
                    $title_busqueda = 'Búsqueda por Fecha: ' . $_GET['Casos']['fecha'];
                } else {
                    $title_busqueda = 'Búsqueda por Fechas - Desde : ' . $_GET['Casos']['fecha'] . ', Hasta: ' . $_GET['Casos']['fecha2'];
                }
                $this->render($render, array('model' => $posts, 'pages' => $pages, 'getParams' => $getParams, 'title_busqueda' => $title_busqueda));
            }

//-------------- busqueda por estado
            if (!empty($_GET['Casos']['estado']) && empty($_GET['Casos']['tema']) && empty($_GET['Casos']['subtema']) && empty($_GET['Casos']['fecha'])) {
//                echo '<pre>';
//                print_r($_GET);
//                echo '</pre>';
//                die('enter estado');
                $condition = "estado = ";
                $title_busqueda = "Búsqueda por Estado: ";
                for ($i = 0; $i < count($_GET['Casos']['estado']); $i++) {
                    if ($i == 0):
                        $condition .= " '{$_GET['Casos']['estado'][$i]}'";
                        $title_busqueda .= "{$_GET['Casos']['estado'][$i]} ";
                    else:
                        $condition .= " OR estado = '{$_GET['Casos']['estado'][$i]}'";
                        $title_busqueda .= "{$_GET['Casos']['estado'][$i]} ";
                    endif;
                }

                if ($cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                    $criteria = new CDbCriteria(array(
                                "condition" => $condition,
                                "order" => 'id desc'
                            ));
                else:
                    $criteria = new CDbCriteria;
                    $criteria->condition = "responsable={$id_responsable} and estado = '{$_GET['Casos']['estado']}' ";
                    $criteria->order = 'id desc';
                endif;
                $pages = new CPagination(Casos::model()->count($criteria));
                // Set Page Limit
                $pages->pageSize = 10;

                // Apply page criteria to CDbCriteria
                $pages->applyLimit($criteria);

                // Grab the records
                $posts = Casos::model()->findAll($criteria);
                $getParams['estado'] = $_GET['Casos']['estado'];
                //$title_busqueda = 'Búsqueda por Estado: ' . $_GET['Casos']['estado'];
                $this->render($render, array('model' => $posts, 'pages' => $pages, 'getParams' => $getParams, 'title_busqueda' => $title_busqueda));
            }

//-------------- busqueda por tema subtema y fecha
            if (!empty($_GET['Casos']['tema']) && !empty($_GET['Casos']['subtema']) && !empty($_GET['Casos']['fecha']) && empty($_GET['Casos']['estado'])) {
                //die('enter tema subtema fecha');
//                $criteria = new CDbCriteria(array(
//                            "condition" => "tema = {$_GET['Casos']['tema']} AND subtema = {$_GET['Casos']['subtema']} AND fecha = '{$_GET['Casos']['fecha']}'"
//                        ));


                if ($cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                    $criteria = new CDbCriteria(array(
                                "condition" => "tema = {$_GET['Casos']['tema']} AND subtema = {$_GET['Casos']['subtema']} AND fecha LIKE '{$_GET['Casos']['fecha']}%' ",
                                "order" => 'id desc'
                            ));
                else:
                    $criteria = new CDbCriteria;
                    $criteria->condition = "responsable={$id_responsable} and tema = {$_GET['Casos']['tema']} AND subtema = {$_GET['Casos']['subtema']} AND fecha LIKE '{$_GET['Casos']['fecha']}%' ";
                    $criteria->order = 'id desc';
                endif;
                $pages = new CPagination(Casos::model()->count($criteria));
                // Set Page Limit
                $pages->pageSize = 10;

                // Apply page criteria to CDbCriteria
                $pages->applyLimit($criteria);

                // Grab the records
                $posts = Casos::model()->findAll($criteria);
                $getParams['tema'] = $_GET['Casos']['tema'];
                $getParams['subtema'] = $_GET['Casos']['subtema'];
                $getParams['fecha'] = $_GET['Casos']['fecha'];
                $title_busqueda = 'Búsqueda por Tema: ' . $this->getTema($_GET['Casos']['tema']) . ' , Subtema: ' . $this->getSubtema($_GET['Casos']['subtema']) . ', Fecha: ' . $_GET['Casos']['fecha'];
                $this->render($render, array('model' => $posts, 'pages' => $pages, 'getParams' => $getParams, 'title_busqueda' => $title_busqueda));
            }

//-------------- busqueda por tema subtema fecha y estado
            if (!empty($_GET['Casos']['tema']) && !empty($_GET['Casos']['subtema']) && !empty($_GET['Casos']['fecha']) && !empty($_GET['Casos']['estado'])) {
                //die('enter tema subtema fecha y estado');
                $condition = "estado = ";
                $title_busqueda_estado = ", y Estado: ";
                $num_estados = count($_GET['Casos']['estado']);
                if ($num_estados > 1) {
                    for ($i = 0; $i < count($_GET['Casos']['estado']); $i++) {
                        if ($i == 0):
                            $condition .= " '{$_GET['Casos']['estado'][$i]}'";
                            $title_busqueda_estado .= "{$_GET['Casos']['estado'][$i]} ";
                        else:
                            $condition .= " OR estado = '{$_GET['Casos']['estado'][$i]}'";
                            $title_busqueda_estado .= "{$_GET['Casos']['estado'][$i]} ";
                        endif;
                    }
                }else {
                    $condition .= "'{$_GET['Casos']['estado'][0]}'";
                    $title_busqueda_estado .= "{$_GET['Casos']['estado'][0]} ";
                }

                //die('condition: '.$condition);

                if ($_GET['Casos']['tipo_fecha'] === 'igual') {
                    if ($cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                        $criteria = new CDbCriteria(array(
                                    "condition" => "tema = {$_GET['Casos']['tema']} AND subtema = {$_GET['Casos']['subtema']} AND fecha LIKE '{$_GET['Casos']['fecha']}%'  AND (" . $condition . ")",
                                    "order" => 'id desc'
                                ));
                    else:
                        $criteria = new CDbCriteria;
                        $criteria->condition = "responsable={$id_responsable} and tema = {$_GET['Casos']['tema']} AND subtema = {$_GET['Casos']['subtema']} AND fecha LIKE '{$_GET['Casos']['fecha']}%'  AND (" . $condition . ")";
                        $criteria->order = 'id desc';
                    endif;
                }else {
                    if ($cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                        $criteria = new CDbCriteria(array(
                                    "condition" => "tema = {$_GET['Casos']['tema']} AND subtema = {$_GET['Casos']['subtema']} AND (date(fecha) BETWEEN '{$_GET['Casos']['fecha']}' AND '{$_GET['Casos']['fecha2']}') AND (" . $condition . ")",
                                    "order" => 'id desc'
                                ));
                    else:
                        $criteria = new CDbCriteria;
                        $criteria->condition = "responsable={$id_responsable} AND tema = {$_GET['Casos']['tema']} AND subtema = {$_GET['Casos']['subtema']} AND (date(fecha) BETWEEN '{$_GET['Casos']['fecha']}' AND '{$_GET['Casos']['fecha2']}') AND (" . $condition . ")";
                        $criteria->order = 'id desc';
                    endif;
                }


                $pages = new CPagination(Casos::model()->count($criteria));
                // Set Page Limit
                $pages->pageSize = 10;

                // Apply page criteria to CDbCriteria
                $pages->applyLimit($criteria);

                // Grab the records
                $posts = Casos::model()->findAll($criteria);
                $getParams['tema'] = $_GET['Casos']['tema'];
                $getParams['subtema'] = $_GET['Casos']['subtema'];
                $getParams['fecha'] = $_GET['Casos']['fecha'];
                $getParams['estado'] = $_GET['Casos']['estado'];
                $title_busqueda = 'Búsqueda por Tema: ' . $this->getTema($_GET['Casos']['tema']) . ' , Subtema: ' . $this->getSubtema($_GET['Casos']['subtema']) . ', Fecha: ' . $_GET['Casos']['fecha'] . $title_busqueda_estado;
                $this->render($render, array('model' => $posts, 'pages' => $pages, 'getParams' => $getParams, 'title_busqueda' => $title_busqueda));
            }

//-------------- busqueda por fecha y estado
            if (!empty($_GET['Casos']['fecha']) && !empty($_GET['Casos']['estado']) && empty($_GET['Casos']['tema']) && empty($_GET['Casos']['subtema'])) {
//                $criteria = new CDbCriteria(array(
//                            "condition" => "fecha = '{$_GET['Casos']['fecha']}' AND estado = '{$_GET['Casos']['estado']}'"
//                        ));
                $condition = "estado = ";
                $title_busqueda_estado = ", y Estado: ";
                $num_estados = count($_GET['Casos']['estado']);
                if ($num_estados > 1) {
                    for ($i = 0; $i < count($_GET['Casos']['estado']); $i++) {
                        if ($i == 0):
                            $condition .= " '{$_GET['Casos']['estado'][$i]}'";
                            $title_busqueda_estado .= "{$_GET['Casos']['estado'][$i]} ";
                        else:
                            $condition .= " OR estado = '{$_GET['Casos']['estado'][$i]}'";
                            $title_busqueda_estado .= "{$_GET['Casos']['estado'][$i]} ";
                        endif;
                    }
                }else {
                    $condition .= "'{$_GET['Casos']['estado'][0]}'";
                    $title_busqueda_estado .= "{$_GET['Casos']['estado'][0]} ";
                }


                if ($_GET['Casos']['tipo_fecha'] === 'igual') {
                    if ($cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                        $criteria = new CDbCriteria(array(
                                    //"condition" => "fecha LIKE '{$_GET['Casos']['fecha']}%' AND estado = '{$_GET['Casos']['estado']}' ",
                                    "condition" => "fecha LIKE '{$_GET['Casos']['fecha']}%' AND " . $condition,
                                    "order" => 'id desc'
                                ));
                    else:
                        $criteria = new CDbCriteria;
                        $criteria->condition = "responsable={$id_responsable} and fecha LIKE '{$_GET['Casos']['fecha']}%' AND " . $condition;
                        $criteria->order = 'id desc';
                    endif;
                } else {
                    if ($cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                        $criteria = new CDbCriteria(array(
                                    "condition" => "(date(fecha) BETWEEN '{$_GET['Casos']['fecha']}' AND '{$_GET['Casos']['fecha2']}') AND (" . $condition . ")",
                                    "order" => 'id desc'
                                ));
                    else:
                        $criteria = new CDbCriteria;
                        $criteria->condition = "responsable={$id_responsable} and (date(fecha) BETWEEN '{$_GET['Casos']['fecha']}' AND '{$_GET['Casos']['fecha2']}')  AND (" . $condition . ")";
                        $criteria->order = 'id desc';
                    endif;
                }

                $pages = new CPagination(Casos::model()->count($criteria));
                // Set Page Limit
                $pages->pageSize = 10;

                // Apply page criteria to CDbCriteria
                $pages->applyLimit($criteria);

                // Grab the records
                $posts = Casos::model()->findAll($criteria);
                $getParams['fecha'] = $_GET['Casos']['fecha'];
                $getParams['estado'] = $_GET['Casos']['estado'];
                if ($_GET['Casos']['tipo_fecha'] === 'igual') {
                    $title_busqueda = 'Búsqueda por Fecha: ' . $_GET['Casos']['fecha'] . $title_busqueda_estado;
                } else {
                    $title_busqueda = 'Búsqueda por Fechas - Desde : ' . $_GET['Casos']['fecha'] . ', Hasta: ' . $_GET['Casos']['fecha2'] . $title_busqueda_estado;
                }

                $this->render($render, array('model' => $posts, 'pages' => $pages, 'getParams' => $getParams, 'title_busqueda' => $title_busqueda));
            }

//-------------- busqueda por tema subtema y estado
            if (!empty($_GET['Casos']['tema']) && !empty($_GET['Casos']['subtema']) && empty($_GET['Casos']['fecha']) && !empty($_GET['Casos']['estado'])) {
                //die('enter tema subtema y estado');
                $condition = "estado = ";
                $title_busqueda = ", Búsqueda por Estado: ";
                for ($i = 0; $i < count($_GET['Casos']['estado']); $i++) {
                    if ($i == 0):
                        $condition .= " '{$_GET['Casos']['estado'][$i]}'";
                        $title_busqueda .= "{$_GET['Casos']['estado'][$i]} ";
                    else:
                        $condition .= " OR estado = '{$_GET['Casos']['estado'][$i]}'";
                        $title_busqueda .= " / {$_GET['Casos']['estado'][$i]} ";
                    endif;
                }

                if ($cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                    $criteria = new CDbCriteria(array(
                                "condition" => "tema = '{$_GET['Casos']['tema']}' AND subtema = '{$_GET['Casos']['subtema']}' AND ({$condition})",
                                "order" => 'id desc'
                            ));
                else:
                    $criteria = new CDbCriteria;
                    $criteria->condition = "responsable={$id_responsable} and tema = '{$_GET['Casos']['tema']}' AND subtema = '{$_GET['Casos']['subtema']}' AND ({$condition})";
                    $criteria->order = 'id desc';
                endif;
                //die('sql: '.$criteria->condition);
                $pages = new CPagination(Casos::model()->count($criteria));
                // Set Page Limit
                $pages->pageSize = 10;

                // Apply page criteria to CDbCriteria
                $pages->applyLimit($criteria);

                // Grab the records
                $posts = Casos::model()->findAll($criteria);
                $title_busqueda = 'Búsqueda por Tema: ' . $this->getTema($_GET['Casos']['tema']) . ' , Subtema: ' . $this->getSubtema($_GET['Casos']['subtema']) . $title_busqueda;
                $this->render($render, array('model' => $posts, 'pages' => $pages, 'getParams' => $getParams, 'title_busqueda' => $title_busqueda));
            }

//-------------- busqueda por tema y fecha
            if (!empty($_GET['Casos']['tema']) && empty($_GET['Casos']['subtema']) && !empty($_GET['Casos']['fecha']) && empty($_GET['Casos']['estado'])) {
                //die('enter tema y fecha');
                if ($_GET['Casos']['tipo_fecha'] === 'igual') {
                    //die('enter igual');
                    if ($cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                        $criteria = new CDbCriteria(array(
                                    "condition" => "tema={$_GET['Casos']['tema']} AND fecha LIKE '{$_GET['Casos']['fecha']}%' ",
                                    "order" => 'id desc'
                                ));
                    /* echo '<pre>';
                      print_r($criteria);
                      echo '</pre>'; */
                    else:
                        $criteria = new CDbCriteria;
                        $criteria->condition = "responsable={$id_responsable} AND tema={$_GET['Casos']['tema']} AND fecha LIKE '{$_GET['Casos']['fecha']}%' ";
                        $criteria->order = 'id desc';
                    endif;
                }else {
                    if ($cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                        $criteria = new CDbCriteria(array(
                                    "condition" => "tema={$_GET['Casos']['tema']} AND date(fecha) BETWEEN '{$_GET['Casos']['fecha']}' AND '{$_GET['Casos']['fecha2']}' "
                                ));
                    else:
                        $criteria = new CDbCriteria;
                        $criteria->condition = "responsable={$id_responsable} and tema={$_GET['Casos']['tema']} AND date(fecha) BETWEEN '{$_GET['Casos']['fecha']}' AND '{$_GET['Casos']['fecha2']}' ";
                        $criteria->order = 'id';
                    endif;
                }

                $pages = new CPagination(Casos::model()->count($criteria));
                // Set Page Limit
                $pages->pageSize = 10;

                // Apply page criteria to CDbCriteria
                $pages->applyLimit($criteria);

                // Grab the records
                $posts = Casos::model()->findAll($criteria);
                /* echo '<pre>';
                  print_r($posts);
                  echo '</pre>';
                  die(); */
                $getParams['fecha'] = $_GET['Casos']['fecha'];
                if ($_GET['Casos']['tipo_fecha'] === 'igual') {
                    $title_busqueda = 'Búsqueda por Tema: ' . $this->getTema($_GET['Casos']['tema']) . ', Fecha: ' . $_GET['Casos']['fecha'];
                } else {
                    $title_busqueda = 'Búsqueda por Tema: ' . $this->getTema($_GET['Casos']['tema']) . ', Fechas - Desde : ' . $_GET['Casos']['fecha'] . ', Hasta: ' . $_GET['Casos']['fecha2'];
                }
                $this->render($render, array('model' => $posts, 'pages' => $pages, 'getParams' => $getParams, 'title_busqueda' => $title_busqueda));
            }
        }
    }

    public function actionExportExcel() {
//        echo '<pre>';
//        print_r($_POST);
//        echo '</pre>';
//        die();
        $concesionario = Yii::app()->user->getState('dealer_id');
        $rol = Yii::app()->user->getState('roles');
        $cargo_id = (int) Yii::app()->user->getState('cargo_id');
        $id_responsable = Yii::app()->user->getId();
        if (isset($_POST['Casos'])) {

            // busqueda por defecto---------------------------------------------------
            if (empty($_POST['Casos']['tema']) && empty($_POST['Casos']['subtema']) && empty($_POST['Casos']['fecha']) && empty($_POST['Casos']['estado'])) {
                if ($cargo_id === 83 || $cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                    $criteria = new CDbCriteria;
                    $criteria->order = 'id DESC';
                else:
                    $criteria = new CDbCriteria;
                    $criteria->condition = "responsable={$id_responsable}";
                    $criteria->order = 'id';
                endif;
                $casos = Casos::model()->findAll($criteria);
                $tituloReporte = "Reporte de Casos Totales " . date("Y-m-d");
                $name_file = 'Reporte de Casos Total ' . date("Y-m-d") . '.xls';
            }
            // busqueda por temas---------------------------------------------------
            if (!empty($_POST['Casos']['tema']) && empty($_POST['Casos']['subtema']) && empty($_POST['Casos']['fecha']) && empty($_POST['Casos']['estado'])) {
                if ($cargo_id === 83 || $cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                    $criteria = new CDbCriteria(array(
                                "condition" => "tema = {$_POST['Casos']['tema']}"
                            ));
                else:
                    $criteria = new CDbCriteria;
                    $criteria->condition = "responsable={$id_responsable} and tema = {$_POST['Casos']['tema']}";
                    $criteria->order = 'id';
                endif;

                // Grab the records
                $casos = Casos::model()->findAll($criteria);
                $tituloReporte = "Reporte de Casos por Tema: " . $this->getTema($_POST['Casos']['tema']);
                $name_file = "Reporte de Casos por Tema {$this->getTema($_POST['Casos']['tema'])} " . date("Y-m-d") . ".xls";
            }
            //busqueda por tema y subtema---------------------------------------------------
            if (!empty($_POST['Casos']['tema']) && !empty($_POST['Casos']['subtema']) && empty($_POST['Casos']['fecha']) && empty($_POST['Casos']['estado0'])) {
                if ($cargo_id === 83 || $cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                    $criteria = new CDbCriteria(array(
                                "condition" => "tema = {$_POST['Casos']['tema']} AND subtema = {$_POST['Casos']['subtema']}"
                            ));
                else:
                    $criteria = new CDbCriteria;
                    $criteria->condition = "responsable={$id_responsable} and tema = {$_POST['Casos']['tema']} AND subtema = {$_POST['Casos']['subtema']}";
                    $criteria->order = 'id';
                endif;

                // Grab the records
                $casos = Casos::model()->findAll($criteria);
                $tituloReporte = "Reporte de Casos por Tema: " . $this->getTema($_POST['Casos']['tema']) . ", Subtema:  " . $this->getSubtema($_POST['Casos']['subtema']) . " " . date("Y-m-d");
                $name_file = "Reporte de Casos por Tema {$this->getTema($_POST['Casos']['tema'])} Subtema  {$this->getSubtema($_POST['Casos']['subtema'])}" . date("Y-m-d") . ".xls";
            }
            //busqueda por fecha---------------------------------------------------
            if (!empty($_POST['Casos']['fecha']) && empty($_POST['Casos']['tema']) && empty($_POST['Casos']['subtema']) && empty($_POST['Casos']['estado'])) {
                //die('enter fevha');
                if (!empty($_POST['Casos']['fecha']) && !empty($_POST['Casos']['fecha2'])) {
                    if ($cargo_id === 83 || $cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                        $criteria = new CDbCriteria(array(
                                    "condition" => "date(fecha) BETWEEN '{$_POST['Casos']['fecha']}' AND '{$_POST['Casos']['fecha2']}'"
                                ));
                    else:
                        $criteria = new CDbCriteria;
                        $criteria->condition = "responsable={$id_responsable} and date(fecha) BETWEEN '{$_POST['Casos']['fecha']}' AND '{$_POST['Casos']['fecha2']}'";
                        $criteria->order = 'id';
                    endif;
                    //$tituloReporte = "Reporte de Casos por Fecha: Desde " . $_POST['Casos']['fecha']. ", Hasta ".$_POST['Casos']['fecha2'];
                    //$name_file = "Reporte de Casos por Fecha Desde " . $_POST['Casos']['fecha']. ", Hasta ".$_POST['Casos']['fecha2'] . ".xls";
                }else {
                    if ($cargo_id === 83 || $cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                        $criteria = new CDbCriteria(array(
                                    "condition" => "fecha LIKE '{$_POST['Casos']['fecha']}%'"
                                ));
                    else:
                        $criteria = new CDbCriteria;
                        $criteria->condition = "responsable={$id_responsable} and fecha LIKE '{$_POST['Casos']['fecha']}%'";
                        $criteria->order = 'id';
                    endif;
                    //$tituloReporte = "Reporte de Casos por Fecha: " . $_POST['Casos']['fecha'];
                    //$name_file = "Reporte de Casos por Fecha " . $_POST['Casos']['fecha'] . ".xls";
                }
                // Grab the records
                if (!empty($_POST['Casos']['fecha']) && !empty($_POST['Casos']['fecha2'])) {
                    $tituloReporte = "Reporte de Casos por Fecha Desde: " . $_POST['Casos']['fecha'] . ', Hasta: ' . $_POST['Casos']['fecha2'];
                } else {
                    $tituloReporte = "Reporte de Casos por Fecha: " . $_POST['Casos']['fecha'];
                }
                $casos = Casos::model()->findAll($criteria);

                $name_file = "Reporte de Casos por Fecha " . $_POST['Casos']['fecha'] . ".xls";
            }

            //busqueda por estado---------------------------------------------------
            if (!empty($_POST['Casos']['estado0']) && empty($_POST['Casos']['tema']) && empty($_POST['Casos']['subtema']) && empty($_POST['Casos']['fecha'])) {
                //die('enter estado');
                $condition = "estado = '{$_POST['Casos']['estado0']}'";
                $title_busqueda = "Reporte de Casos por Estado: " . $_POST['Casos']['estado0'];
                if (isset($_POST['Casos']['estado1'])) {
                    $condition .= " OR estado = '{$_POST['Casos']['estado1']}'";
                    $title_busqueda .= " {$_POST['Casos']['estado1']}";
                }
                if (isset($_POST['Casos']['estado1']) && isset($_POST['Casos']['estado2'])) {
                    $condition .= " OR estado = '{$_POST['Casos']['estado1']}' OR estado = '{$_POST['Casos']['estado2']}'";
                    $title_busqueda .= " {$_POST['Casos']['estado1']} , {$_POST['Casos']['estado2']}";
                }

                if ($cargo_id === 83 || $cargo_id === 83 || $cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                    $criteria = new CDbCriteria(array(
                                "condition" => "{$condition}"
                            ));
                else:
                    $criteria = new CDbCriteria;
                    $criteria->condition = "responsable={$id_responsable} and {$condition}";
                    $criteria->order = 'id';
                endif;

                // Grab the records
                $casos = Casos::model()->findAll($criteria);
                //$tituloReporte = "Reporte de Casos por Estado: " . $_POST['Casos']['estado'];
                $tituloReporte = $title_busqueda;
                $name_file = "Reporte de Casos por Estado " . $_POST['Casos']['estado0'] . ".xls";
            }

            // busqueda por tema subtema y fecha---------------------------------------------------
            if (!empty($_POST['Casos']['tema']) && !empty($_POST['Casos']['subtema']) && !empty($_POST['Casos']['fecha'])) {
                //die('enter tema subtema fecha');
                if ($cargo_id === 83 || $cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                    $criteria = new CDbCriteria(array(
                                "condition" => "tema = {$_POST['Casos']['tema']} AND subtema = {$_POST['Casos']['subtema']} AND fecha LIKE '{$_POST['Casos']['fecha']}%'"
                            ));
                else:
                    $criteria = new CDbCriteria;
                    $criteria->condition = "responsable={$id_responsable} and tema = {$_POST['Casos']['tema']} AND subtema = {$_POST['Casos']['subtema']} AND fecha LIKE '{$_POST['Casos']['fecha']}%'";
                    $criteria->order = 'id';
                endif;
                // Grab the records
                $casos = Casos::model()->findAll($criteria);
                $tituloReporte = "Reporte de Casos por Subtema: " . $this->getSubtema($_POST['Casos']['subtema']) . ", Fecha: " . $_POST['Casos']['fecha'];
                $name_file = "Reporte de Casos por Subtema-{$this->getSubtema($_POST['Casos']['subtema'])}Fecha-" . $_POST['Casos']['fecha'] . ".xls";
            }

            // busqueda por tema subtema fecha y estado---------------------------------------------------
            if (!empty($_POST['Casos']['tema']) && !empty($_POST['Casos']['subtema']) && !empty($_POST['Casos']['fecha']) && !empty($_POST['Casos']['estado0'])) {
                //die('enter tema subtema fecha estado');
                $condition = "estado = '{$_POST['Casos']['estado0']}'";
                $title_busqueda = ", Estado: " . $_POST['Casos']['estado0'];
                if (isset($_POST['Casos']['estado1'])) {
                    $condition .= " OR estado = '{$_POST['Casos']['estado1']}'";
                    $title_busqueda .= " / {$_POST['Casos']['estado1']}";
                }
                if (isset($_POST['Casos']['estado1']) && isset($_POST['Casos']['estado2'])) {
                    $condition .= " OR estado = '{$_POST['Casos']['estado1']}' OR estado = '{$_POST['Casos']['estado2']}'";
                    $title_busqueda .= " / {$_POST['Casos']['estado1']} , {$_POST['Casos']['estado2']}";
                }
                if (!empty($_POST['Casos']['fecha']) && !empty($_POST['Casos']['fecha2'])) {
                    if ($cargo_id === 83 || $cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                        $criteria = new CDbCriteria(array(
                                    "condition" => "tema = {$_POST['Casos']['tema']} AND subtema = {$_POST['Casos']['subtema']} AND (date(fecha) BETWEEN '{$_POST['Casos']['fecha']}' AND '{$_POST['Casos']['fecha2']}') AND " . "(" . $condition . ")"
                                ));
                    else:
                        $criteria = new CDbCriteria;
                        $criteria->condition = "responsable={$id_responsable} and tema = {$_POST['Casos']['tema']} AND subtema = {$_POST['Casos']['subtema']} AND (date(fecha) BETWEEN '{$_POST['Casos']['fecha']}' AND '{$_POST['Casos']['fecha2']}') AND " . "(" . $condition . ")";
                        $criteria->order = 'id';
                    endif;
                } else {
                    if ($cargo_id === 83 || $cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                        $criteria = new CDbCriteria(array(
                                    "condition" => "tema = {$_POST['Casos']['tema']} AND subtema = {$_POST['Casos']['subtema']} AND fecha LIKE '{$_POST['Casos']['fecha']}%' AND " . "(" . $condition . ")"
                                ));
                    else:
                        $criteria = new CDbCriteria;
                        $criteria->condition = "responsable={$id_responsable} and tema = {$_POST['Casos']['tema']} AND subtema = {$_POST['Casos']['subtema']} AND fecha LIKE '{$_POST['Casos']['fecha']}%' AND " . "(" . $condition . ")";
                        $criteria->order = 'id';
                    endif;
                }

                // Grab the records
                $casos = Casos::model()->findAll($criteria);

                $tituloReporte = "Reporte de Casos por Subtema: " . $this->getSubtema($_POST['Casos']['subtema']) . ", Fecha: " . $_POST['Casos']['fecha'] . $title_busqueda;
                $name_file = "Reporte Subtema-{$this->getSubtema($_POST['Casos']['subtema'])} Fecha-" . $_POST['Casos']['fecha'] . " Estado-" . $_POST['Casos']['estado0'] . ".xls";
            }

            // busqueda por fecha y estado---------------------------------------------------
            if (!empty($_POST['Casos']['fecha']) && !empty($_POST['Casos']['estado0']) && empty($_POST['Casos']['tema']) && empty($_POST['Casos']['subtema'])) {
                //die('enter fecha estado');
                $condition = "estado = '{$_POST['Casos']['estado0']}'";
                $title_busqueda = ", Estado: " . $_POST['Casos']['estado0'];
                if (isset($_POST['Casos']['estado1'])) {
                    $condition .= " OR estado = '{$_POST['Casos']['estado1']}'";
                    $title_busqueda .= " {$_POST['Casos']['estado1']}";
                }
                if (isset($_POST['Casos']['estado1']) && isset($_POST['Casos']['estado2'])) {
                    $condition .= " OR estado = '{$_POST['Casos']['estado1']}' OR estado = '{$_POST['Casos']['estado2']}'";
                    $title_busqueda .= " {$_POST['Casos']['estado1']} , {$_POST['Casos']['estado2']}";
                }
                //die('condition: '.$condition);
                if (!empty($_POST['Casos']['fecha']) && !empty($_POST['Casos']['fecha2'])) {
                    if ($cargo_id === 83 || $cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                        $criteria = new CDbCriteria(array(
                                    "condition" => "(date(fecha) BETWEEN '{$_POST['Casos']['fecha']}' AND '{$_POST['Casos']['fecha2']}') AND " . "(" . $condition . ")"
                                ));
                    else:
                        $criteria = new CDbCriteria;
                        $criteria->condition = "responsable={$id_responsable} and date(fecha) BETWEEN '{$_POST['Casos']['fecha']}' AND '{$_POST['Casos']['fecha2']}' AND " . "(" . $condition . ")";
                        $criteria->order = 'id';
                    endif;
                    //$tituloReporte = "Reporte de Casos por Fecha: Desde " . $_POST['Casos']['fecha']. ", Hasta ".$_POST['Casos']['fecha2'];
                    //$name_file = "Reporte de Casos por Fecha Desde " . $_POST['Casos']['fecha']. ", Hasta ".$_POST['Casos']['fecha2'] . ".xls";
                }else {
                    if ($cargo_id === 83 || $cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                        $criteria = new CDbCriteria(array(
                                    "condition" => "fecha LIKE '{$_POST['Casos']['fecha']}%' AND " . $condition
                                ));
                    else:
                        $criteria = new CDbCriteria;
                        $criteria->condition = "responsable={$id_responsable} and fecha LIKE '{$_POST['Casos']['fecha']}%' AND " . "(" . $condition . ")";
                        $criteria->order = 'id';
                    endif;
                    //$tituloReporte = "Reporte de Casos por Fecha: " . $_POST['Casos']['fecha'];
                    //$name_file = "Reporte de Casos por Fecha " . $_POST['Casos']['fecha'] . ".xls";
                }


                // Grab the records
                $casos = Casos::model()->findAll($criteria);
                if (!empty($_POST['Casos']['fecha']) && !empty($_POST['Casos']['fecha2'])) {
                    $tituloReporte = "Reporte de Casos por Fecha Desde: " . $_POST['Casos']['fecha'] . ', Hasta: ' . $_POST['Casos']['fecha2'] . ", Estado: " . $_POST['Casos']['estado0'];
                } else {
                    $tituloReporte = "Reporte de Casos por Fecha: " . $_POST['Casos']['fecha'] . ", Estado: " . $_POST['Casos']['estado0'];
                }

                $name_file = "Reporte por Fecha-" . $_POST['Casos']['fecha'] . " Estado-" . $_POST['Casos']['estado0'] . ".xls";
            }

            // busqueda por tema subtema y estado---------------------------------------------------
            if (!empty($_POST['Casos']['tema']) && !empty($_POST['Casos']['subtema']) && empty($_POST['Casos']['fecha']) && !empty($_POST['Casos']['estado0'])) {
                //die('enter tema subtema y estado');
                $condition = "estado = '{$_POST['Casos']['estado0']}'";
                $title_busqueda = ", Estado: " . $_POST['Casos']['estado0'];
                if (isset($_POST['Casos']['estado1'])) {
                    $condition .= " OR estado = '{$_POST['Casos']['estado1']}'";
                    $title_busqueda .= " / {$_POST['Casos']['estado1']}";
                }
                if (isset($_POST['Casos']['estado1']) && isset($_POST['Casos']['estado2'])) {
                    $condition .= " OR estado = '{$_POST['Casos']['estado1']}' OR estado = '{$_POST['Casos']['estado2']}'";
                    $title_busqueda .= " {$_POST['Casos']['estado1']} , {$_POST['Casos']['estado2']}";
                }

                if ($cargo_id === 83 || $cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                    $criteria = new CDbCriteria(array(
                                "condition" => "tema = '{$_POST['Casos']['tema']}' AND subtema = '{$_POST['Casos']['subtema']}' AND ({$condition})"
                            ));
                else:
                    $criteria = new CDbCriteria;
                    $criteria->condition = "responsable={$id_responsable} and tema = '{$_POST['Casos']['tema']}' AND subtema = '{$_POST['Casos']['subtema']}' AND ({$condition})";
                    $criteria->order = 'id';
                endif;
                // Grab the records
                $casos = Casos::model()->findAll($criteria);
                $tituloReporte = "Reporte de Casos por Tema: " . $this->getTema($_POST['Casos']['tema']) . ", Subtema: " . $this->getSubtema($_POST['Casos']['subtema']) . $title_busqueda;
                $name_file = "Reporte por Tema-" . $this->getTema($_POST['Casos']['tema']) . " Subtema-" . $this->getSubtema($_POST['Casos']['subtema']) . " Estado-" . $_POST['Casos']['estado0'] . ".xls";
            }

            // busqueda por tema y fecha--------------------------------------------------------------------
            if (!empty($_POST['Casos']['tema']) && empty($_POST['Casos']['subtema']) && !empty($_POST['Casos']['fecha']) && empty($_POST['Casos']['estado'])) {
                if (!empty($_POST['Casos']['fecha']) && !empty($_POST['Casos']['fecha2'])) {
                    if ($cargo_id === 83 || $cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                        $criteria = new CDbCriteria(array(
                                    "condition" => "tema= {$_POST['Casos']['tema']} AND date(fecha) BETWEEN '{$_POST['Casos']['fecha']}' AND '{$_POST['Casos']['fecha2']}'"
                                ));

                    else:
                        $criteria = new CDbCriteria;
                        $criteria->condition = "responsable={$id_responsable} and tema= {$_POST['Casos']['tema']} AND fecha BETWEEN '{$_POST['Casos']['fecha']}' AND '{$_POST['Casos']['fecha2']}'";
                        $criteria->order = 'id';
                    endif;
                    $tituloReporte = "Reporte de Casos por Tema: " . $this->getTema($_POST['Casos']['tema']) . " ,Fecha entre: " . $_POST['Casos']['fecha'] . " y " . $_POST['Casos']['fecha2'];
                }else {
                    if ($cargo_id === 83 || $cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                        $criteria = new CDbCriteria(array(
                                    "condition" => "tema= {$_POST['Casos']['tema']} AND fecha like '{$_POST['Casos']['fecha']}%'"
                                ));
                    else:
                        $criteria = new CDbCriteria;
                        $criteria->condition = "responsable={$id_responsable} and tema= {$_POST['Casos']['tema']} AND fecha = '{$_POST['Casos']['fecha']}'";
                        $criteria->order = 'id';
                    endif;
                    $tituloReporte = "Reporte de Casos por Tema: " . $this->getTema($_POST['Casos']['tema']) . ", Fecha: " . $_POST['Casos']['fecha'];
                }
                // Grab the records
                $casos = Casos::model()->findAll($criteria);
                $name_file = "Reporte de Casos por Fecha " . $_POST['Casos']['fecha'] . ".xls";
            }
        }

        //$name_file = 'Reporte de Casos_2014' . date("Y-m-d") . '.xls';
        Yii::import('ext.phpexcel.XPHPExcel');
        $objPHPExcel = XPHPExcel::createPHPExcel();
        $objPHPExcel->getProperties()->setCreator("Call Center Kia Ecuador")
                ->setLastModifiedBy("Call Center")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");

        //$criteria = new CDbCriteria;
        //$criteria->order = 'id';
        //$casos = Casos::model()->findAll($criteria);

        $estiloTituloReporte = array(
            'font' => array(
                'name' => 'Tahoma',
                'bold' => true,
                'italic' => false,
                'strike' => false,
                'size' => 11,
                'color' => array(
                    'rgb' => 'B6121A'
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'rotation' => 0,
                'wrap' => TRUE
            )
        );

        $estiloTituloColumnas = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'size' => 9,
                'color' => array(
                    'rgb' => '333333'
                )
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => 'F1F1F1')
            ),
            'borders' => array(
                'top' => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                    'color' => array(
                        'rgb' => '143860'
                    )
                ),
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                    'color' => array(
                        'rgb' => '143860'
                    )
                ),
                'left' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => '143860'
                    )
                ),
                'right' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => '143860'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => TRUE
            )
        );
        // Se combinan las celdas A1 hasta F1, para colocar ahí el titulo del reporte
        $objPHPExcel->setActiveSheetIndex(0)
                ->mergeCells('A1:J1');


        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', $tituloReporte) // Titulo del reporte
                ->setCellValue('A2', 'Id')
                ->setCellValue('B2', 'Tema')
                ->setCellValue('C2', 'Subtema')
                ->setCellValue('D2', 'Nombres')
                ->setCellValue('E2', 'Apellidos')
                ->setCellValue('F2', 'Tipo de Identificación')
                ->setCellValue('G2', 'No. de Identificación')
                ->setCellValue('H2', 'Provincia')
                ->setCellValue('I2', 'Ciudad')
                ->setCellValue('J2', 'Concesionario')
                ->setCellValue('K2', 'Dirección')
                ->setCellValue('L2', 'Provincia Domicilio')
                ->setCellValue('M2', 'Ciudad Domicilio')
                ->setCellValue('N2', 'Sector')
                ->setCellValue('O2', 'Teléfono')
                ->setCellValue('P2', 'Celular')
                ->setCellValue('Q2', 'Email')
                ->setCellValue('R2', 'Comentarios')
                ->setCellValue('S2', 'Estado')
                ->setCellValue('T2', 'Tipo Formulario')
                ->setCellValue('U2', 'Tipo Venta')
                ->setCellValue('V2', 'Responsable')
                ->setCellValue('W2', 'Fecha')
                ->setCellValue('X2', 'Tipo');

        $i = 3;
        /* echo '<pre>';
          print_r($casos);
          echo '</pre>';
          die(); */
        foreach ($casos as $row) {
            $ident;$tipoIden;
            switch ($row['identificacion']) {
                case 'ci':
                    $ident = $row['cedula'];$tipoIden = 'Cédula';
                    break;
                case 'ruc':
                    $ident = $row['ruc'];$tipoIden = 'Ruc';
                    break;
                case 'pasaporte':
                    break;
                    $ident = $row['pasaporte'];$tipoIden = 'Pasaporte';
                default:
                    break;
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $row['id'])
                    ->setCellValue('B' . $i, $this->getTema($row['tema']))
                    ->setCellValue('C' . $i, $this->getSubtema($row['subtema']))
                    ->setCellValue('D' . $i, $row['nombres'])
                    ->setCellValue('E' . $i, $row['apellidos'])
                    ->setCellValue('F' . $i, $tipoIden)
                    ->setCellValue('G' . $i, $ident)
                    ->setCellValue('H' . $i, $this->getProvincia($row['provincia']))
                    ->setCellValue('I' . $i, $this->getCiudad($row['ciudad']))
                    ->setCellValue('J' . $i, $this->getConcesionario($row['concesionario']))
                    ->setCellValue('K' . $i, $row['direccion'])
                    ->setCellValue('L' . $i, $row['origen'] == 0 ? $this->getProvinciaDom($row['provincia_domicilio']) : $this->getProvincia($row['provincia_domicilio']))
                    ->setCellValue('M' . $i, $row['origen'] == 0 ? $this->getCiudadDom($row['ciudad_domicilio']) : $this->getCiudad($row['ciudad_domicilio']))
                    ->setCellValue('N' . $i, $row['sector'])
                    ->setCellValue('O' . $i, $row['telefono'])
                    ->setCellValue('P' . $i, $row['celular'])
                    ->setCellValue('Q' . $i, $row['email'])
                    ->setCellValue('R' . $i, $row['comentario'])
                    ->setCellValue('S' . $i, $row['estado'])
                    ->setCellValue('T' . $i, $row['tipo_form'])
                    ->setCellValue('U' . $i, $row['tipo_venta'])
                    ->setCellValue('V' . $i, $this->getResponsable($row['responsable']))
                    ->setCellValue('W' . $i, $row['fecha'])
                    ->setCellValue('X' . $i, ($row['origen'] == 0) ? '1800' : 'web');

            $objPHPExcel->getActiveSheet()->setCellValueExplicit('G' . $i, $ident, PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('O' . $i, $row['telefono'], PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('P' . $i, $row['celular'], PHPExcel_Cell_DataType::TYPE_STRING);
            //$objPHPExcel->getActiveSheet()->setCellValueExplicit('N' . $i, $row['comentario'], PHPExcel_Cell_DataType::TYPE_STRING);
            //$objPHPExcel->getActiveSheet()->getStyle('M' . $i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $i++;
        }
        $style1 = array(
            'font' => array(
                'name' => 'Calibri',
                'bold' => true,
                'size' => 11,
            ),
        );

        $objPHPExcel->getActiveSheet()->getStyle('A2:M2')->applyFromArray($style1);
        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("G")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("H")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("I")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("J")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("K")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("L")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("M")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("N")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("M")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("N")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("O")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("P")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("R")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("S")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("T")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("U")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("V")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("W")->setAutoSize(true);
        // rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Reporte de casos');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->applyFromArray($estiloTituloReporte);
        $objPHPExcel->getActiveSheet()->getStyle('A2:X2')->applyFromArray($estiloTituloColumnas);

        // Inmovilizar paneles
        $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0, 3);

        // Redirect output to a client's web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $name_file . '');
        header('Cache-Control: max-age=0');
        //      If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        //      If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        Yii::app()->end();
    }

    public function actionGetcedula() {
        /* echo '<pre>';
          print_r($_GET);
          echo '</pre>'; */

        if (isset($_GET['term'])) {
            $sql = 'SELECT id, nombres, apellidos, email, cedula, direccion, telefono, celular, direccion, sector FROM casos WHERE cedula LIKE :qterm ';
            $sql .= ' GROUP BY cedula ORDER BY cedula ASC';
            $command = Yii::app()->db->createCommand($sql);
            $qterm = $_GET['term'] . '%';
            $command->bindParam(":qterm", $qterm, PDO::PARAM_STR);
            $models = $command->queryAll();
            foreach ($models as $model) {
                $arr[] = array(
                    'id' => $model['id'],
                    'value' => $model['cedula'],
                    'nombres' => $model['nombres'],
                    'apellidos' => $model['apellidos'],
                    'email' => $model['email'],
                    'telefono' => $model['telefono'],
                    'celular' => $model['celular'],
                    'direccion' => $model['direccion'],
                    'sector' => $model['sector']
                );
            }
            echo CJSON::encode($arr);
            exit;
        } else {
            return false;
        }
    }

    /*
     * Funcion para notificar que un usuario ha sido registrado
     * @param1 $id int id del registro ingresado
     */

    public function actionMail($id) {
        $model = $this->loadModel($id);

        $this->render('mail', array(
            'model' => $model,
        ));
    }

    public function actionReportes() {
//        echo '<pre>';
//        print_r($_GET);
//        echo '</pre>';
        $concesionario = Yii::app()->user->getState('dealer_id');
        $rol = Yii::app()->user->getState('roles');
        $model = new Casos;


        if (isset($_GET['Casos'])) {

            /* ---------GRAFICA POR DEFECTO---------- */
            if (empty($_GET['Casos']['tema']) && empty($_GET['Casos']['subtema']) && empty($_GET['Casos']['fecha']) && empty($_GET['Casos']['estado'])) {
                $this->render('reportes', array('modeldf' => $model));
            }

            /* ---------GRAFICA POR TEMAS---------- */
            if (!empty($_GET['Casos']['tema']) && empty($_GET['Casos']['subtema']) && empty($_GET['Casos']['fecha']) && empty($_GET['Casos']['estado'])) {
                //die('enter temas');
                if ($cargo_id === 83 || $cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                    $sql = "SELECT tema, subtema, count(subtema) AS sub FROM casos WHERE tema = {$_GET['Casos']['tema']} GROUP BY subtema HAVING COUNT(*) >= 1";
                //die('sql: '.$sql);
                else:
                    $criteria = new CDbCriteria;
                    $criteria->condition = "responsable={$id_responsable} and tema = {$_GET['Casos']['tema']}";
                    $criteria->order = 'id';
                endif;
                $arrayDatos = array();
                $model->setAttribute("sub", "");

                $casos = Casos::model()->findAllBySQL($sql);
//                echo '<pre>';
//                print_r($casos);
//                echo '</pre>';
//                die();                
                $this->render('reportes', array('model' => $casos, 'id_tema' => $_GET['Casos']['tema'], 'tipo' => 'temas'));
            }

            /* ---------GRAFICA POR ESTADO---------- */
            if (!empty($_GET['Casos']['estado']) && empty($_GET['Casos']['tema']) && empty($_GET['Casos']['subtema']) && empty($_GET['Casos']['fecha'])) {
                //die('enter estado');
                if ($cargo_id === 83 || $cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                    $sql = "SELECT tema, count(tema) AS tem FROM casos WHERE estado = '{$_GET['Casos']['estado']}' GROUP BY tema HAVING COUNT(*) >= 1";
                else:
                    $sql = "SELECT tema, count(tema) AS tem FROM casos WHERE estado = '{$_GET['Casos']['estado']}'  
                        AND concesionario = {$concesionario} GROUP BY tema HAVING COUNT(*) >= 1";
                endif;

                $arrayDatos = array();
                $model->setAttribute("tem", "");

                $casos = Casos::model()->findAllBySQL($sql);
                $this->render('reportes', array('model_estado' => $casos, 'estado' => $_GET['Casos']['estado'], 'tipo' => 'estado'));
            }

            /* ---------GRAFICA POR FECHAS---------- */
            if (!empty($_GET['Casos']['fecha']) && empty($_GET['Casos']['tema']) && empty($_GET['Casos']['subtema']) && empty($_GET['Casos']['estado'])) {
                //die('enter fechas');
                if ($_GET['Casos']['tipo_fecha'] === 'between') {
                    if ($cargo_id === 83 || $cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                        $sql = "SELECT tema, count(tema) AS tem FROM casos WHERE fecha BETWEEN '{$_GET['Casos']['fecha']}' 
                           AND '{$_GET['Casos']['fecha2']}' GROUP BY tema HAVING COUNT(*) >= 1";
                    else:
                        $sql = "SELECT tema, count(tema) AS tem FROM casos WHERE fecha BETWEEN '{$_GET['Casos']['fecha']}' 
                           AND '{$_GET['Casos']['fecha2']}' AND concesionario = {$concesionario} GROUP BY tema HAVING COUNT(*) >= 1";
                    endif;
                }else {
                    if ($cargo_id === 83 || $cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                        $sql = "SELECT tema, count(tema) AS tem FROM casos WHERE fecha = '{$_GET['Casos']['fecha']}' GROUP BY tema HAVING COUNT(*) >= 1";
                    else:
                        $sql = "SELECT tema, count(tema) AS tem FROM casos WHERE fecha = '{$_GET['Casos']['fecha']}' AND concesionario = {$concesionario} GROUP BY tema HAVING COUNT(*) >= 1";
                    endif;
                }
                //die('sql: '.$sql);
                $arrayDatos = array();

                $casos = Casos::model()->findAllBySQL($sql);
                if ($_GET['Casos']['tipo_fecha'] === 'between') {
                    $this->render('reportes', array('model_estado' => $casos, 'fecha' => $_GET['Casos']['fecha'], 'fecha2' => $_GET['Casos']['fecha2'], 'tipo' => 'fechas_between'));
                } else {
                    $this->render('reportes', array('model_estado' => $casos, 'fecha' => $_GET['Casos']['fecha'], 'tipo' => 'fechas'));
                }
            }

            /* ---------GRAFICA POR TEMAS Y FECHA ---------- */
            if (!empty($_GET['Casos']['fecha']) && !empty($_GET['Casos']['tema']) && empty($_GET['Casos']['subtema']) && empty($_GET['Casos']['estado'])) {
                //die('enter temas y fecha');
                if ($_GET['Casos']['tipo_fecha'] === 'between') {
                    if ($cargo_id === 83 || $cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                        //$sql = "SELECT tema, count(tema) AS tem FROM casos WHERE fecha BETWEEN '{$_GET['Casos']['fecha']}' 
                        //   AND '{$_GET['Casos']['fecha2']}' AND tema = '{$_GET['Casos']['tema']}' GROUP BY tema HAVING COUNT(*) >= 1 ";
                        $sql = "SELECT tema, subtema, count(subtema) AS sub FROM casos 
                            WHERE tema = {$_GET['Casos']['tema']} 
                            AND fecha BETWEEN '{$_GET['Casos']['fecha']}' AND '{$_GET['Casos']['fecha2']}'     
                            GROUP BY subtema HAVING COUNT(*) >= 1";
                    //die('sql: '.$sql);
                    else:
                        //$sql = "SELECT tema, count(tema) AS tem FROM casos WHERE fecha BETWEEN '{$_GET['Casos']['fecha']}' 
                        //   AND '{$_GET['Casos']['fecha2']}' AND concesionario = {$concesionario} GROUP BY tema HAVING COUNT(*) >= 1";
                        $sql = "SELECT tema, subtema, count(subtema) AS sub FROM casos 
                            WHERE tema = {$_GET['Casos']['tema']} 
                            AND fecha = '{$_GET['Casos']['fecha']}'     
                            GROUP BY subtema HAVING COUNT(*) >= 1";
                    endif;
                }else {
                    if ($cargo_id === 83 || $cargo_id === 83 || $rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
                        $sql = "SELECT tema, count(tema) AS tem FROM casos WHERE fecha = '{$_GET['Casos']['fecha']}' GROUP BY tema HAVING COUNT(*) >= 1 AND tema = '{$_GET['Casos']['tema']}'";
                    else:
                        $sql = "SELECT tema, count(tema) AS tem FROM casos WHERE fecha = '{$_GET['Casos']['fecha']}' AND concesionario = {$concesionario} GROUP BY tema HAVING COUNT(*) >= 1";
                    endif;
                }
                $arrayDatos = array();

                $casos = Casos::model()->findAllBySQL($sql);
                if ($_GET['Casos']['tipo_fecha'] === 'between') {
                    $this->render('reportes', array('model' => $casos,
                        'fecha' => $_GET['Casos']['fecha'],
                        'fecha2' => $_GET['Casos']['fecha2'],
                        'id_tema' => $_GET['Casos']['tema'],
                        'tipo' => 'tema_fecha_between'));
                    //$this->render('reportes', array('model' => $casos, 'id_tema' => $_GET['Casos']['tema'], 'tipo' => 'temas'));
                } else {
                    $this->render('reportes', array('model' => $casos, 'fecha' => $_GET['Casos']['fecha'], 'tema' => $_GET['Casos']['tema'], 'tipo' => 'tema_fecha'));
                }
            }
        } else {
            $this->render('reportes', array('modeldf' => $model));
        }
    }

}
