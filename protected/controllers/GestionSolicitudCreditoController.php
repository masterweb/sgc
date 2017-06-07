<?php

class GestionSolicitudCreditoController extends Controller {

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
            'postOnly + delete', // we only allow deletion via POST request
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
                'actions' => array('create', 'update', 'createAjax', 'cotizacion', 'admin', 'aprobar', 'aprobarhj', 'status'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
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

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($id_informacion = NULL, $id_vehiculo = NULL) {
        //die('id informacion: '.$id_informacion.', id vehiculo: '.$id_vehiculo);
        $model = new GestionSolicitudCredito;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionSolicitudCredito'])) {

            $model->attributes = $_POST['GestionSolicitudCredito'];
            $hoja_entrega = new GestionHojaEntregaSolicitud;
            $hoja_entrega->id_informacion = $_POST['GestionSolicitudCredito']['id_informacion'];
            $hoja_entrega->id_vehiculo = $_POST['GestionSolicitudCredito']['id_vehiculo'];
            $hoja_entrega->status = 'pendiente';
            $hoja_entrega->save();
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }
        $this->render('create', array(
            'model' => $model, 'id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo
        ));
    }

    public function actionCreateAjax($id_informacion = NULL, $id_vehiculo = NULL) {
        $model = new GestionSolicitudCredito;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);


        if (isset($_POST['GestionSolicitudCredito'])) {
//            echo '<pre>';
//            print_r($_POST);
//            echo '</pre>';
//            die();
            //die('enter post');
            // CREAR ID SOLICITUD DE CREDITO
            $hoja_solicitud = new GestionSolicitud;
            $hoja_solicitud->id_informacion = $_POST['GestionSolicitudCredito']['id_informacion'];
            $hoja_solicitud->id_vehiculo = $_POST['GestionSolicitudCredito']['id_vehiculo'];
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $hoja_solicitud->fecha = date("Y-m-d H:i:s");
            $hoja_solicitud->save();
            $num_solicitud = $this->getLastSolicitudCotizacion($_POST['GestionSolicitudCredito']['id_informacion'], $_POST['GestionSolicitudCredito']['id_vehiculo']);

            $nombre_cliente = $this->getNombresInfo($_POST['GestionSolicitudCredito']['id_informacion']) . ' ' . $this->getApellidosInfo($_POST['GestionSolicitudCredito']['id_informacion']);
            $id_asesor = Yii::app()->user->getId();
            $dealer_id = $this->getConcesionarioDealerId($id_asesor);
            $result = FALSE;
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->attributes = $_POST['GestionSolicitudCredito'];
            $model->id_solicitud = $num_solicitud;
            $model->id_informacion = $_POST['GestionSolicitudCredito']['id_informacion'];
            $model->id_vehiculo = $_POST['GestionSolicitudCredito']['id_vehiculo'];
            $model->estado_civil = $_POST['GestionSolicitudCredito']['estado_civil'];
            $model->year = $_POST['GestionSolicitudCredito']['year'];
            $model->status = 0;
            $model->fecha = date("Y-m-d H:i:s");
            $model->trabaja_conyugue = $_POST['GestionSolicitudCredito']['trabaja_conyugue'];
            if (isset($_POST['GestionSolicitudCredito']['ruc']) && !empty($_POST['GestionSolicitudCredito']['ruc']))
                $model->ruc = $_POST['GestionSolicitudCredito']['ruc'];
            if (isset($_POST['GestionSolicitudCredito']['pasaporte']) && !empty($_POST['GestionSolicitudCredito']['pasaporte']))
                $model->pasaporte = $_POST['GestionSolicitudCredito']['pasaporte'];

            $valor = str_replace(',', "", $_POST['GestionSolicitudCredito']['valor']);
            $valor = str_replace('.', ",", $valor);
            $valor = (int) str_replace('$', "", $valor);

            $monto_financiar = str_replace(',', "", $_POST['GestionSolicitudCredito']['monto_financiar']);
            $monto_financiar = str_replace('.', ",", $monto_financiar);
            $monto_financiar = (int) str_replace('$', "", $monto_financiar);

            $entrada = str_replace(',', "", $_POST['GestionSolicitudCredito']['entrada']);
            $entrada = str_replace('.', ",", $entrada);
            $entrada = (int) str_replace('$', "", $entrada);

            $cuotamensual = str_replace(',', "", $_POST['GestionSolicitudCredito']['cuota_mensual']);
            $cuotamensual = str_replace('.', ",", $cuotamensual);
            $cuotamensual = (int) str_replace('$', "", $cuotamensual);

            $sueldo = str_replace(',', "", $_POST['GestionSolicitudCredito']['sueldo_mensual']);
            $sueldo = str_replace('.', ",", $sueldo);
            $sueldo = (int) str_replace('$', "", $sueldo);

            $sueldo = str_replace(',', "", $_POST['GestionSolicitudCredito']['sueldo_mensual']);
            $sueldo = str_replace('.', ",", $sueldo);
            $sueldo = (int) str_replace('$', "", $sueldo);

            $model->valor = $valor;
            $model->monto_financiar = $monto_financiar;
            $model->entrada = $entrada;
            $model->sueldo_mensual = $sueldo;
            $model->cuota_mensual = $cuotamensual;
            $model->meses_trabajo = $_POST['GestionSolicitudCredito']['meses_trabajo'];
            $model->meses_trabajo_conyugue = $_POST['GestionSolicitudCredito']['meses_trabajo_conyugue'];
            $model->numero = $_POST['GestionSolicitudCredito']['numero'];

            # INGRESAR NUEVOS VALORES POSIBLES EN TABLA GESTION_FINANCIAMIENTO - UPDATE
            $fin = GestionFinanciamiento::model()->find(array('condition' => "id_informacion = {$_POST['GestionSolicitudCredito']['id_informacion']} AND id_vehiculo = {$_POST['GestionSolicitudCredito']['id_vehiculo']}"));
            $fin->precio_vehiculo = $valor;
            $fin->valor_financiamiento = $monto_financiar;
            $fin->cuota_inicial = $entrada;
            $fin->cuota_mensual = $cuotamensual;
            $fin->plazos = $_POST['GestionSolicitudCredito']['plazo'];
            $fin->tasa = $_POST['GestionSolicitudCredito']['taza'];
            $fin->update();

            if (isset($_POST['GestionSolicitudCredito']['numero_cargas']) && !empty($_POST['GestionSolicitudCredito']['numero_cargas'])) {
                $model->numero_cargas = $_POST['GestionSolicitudCredito']['numero_cargas'];
            }    

            if (isset($_POST['GestionSolicitudCredito']['sueldo_mensual_conyugue']) && !empty($_POST['GestionSolicitudCredito']['sueldo_mensual_conyugue'])) {
                $sueldo_conyugue = str_replace(',', "", $_POST['GestionSolicitudCredito']['sueldo_mensual_conyugue']);
                $sueldo_conyugue = str_replace('.', ",", $sueldo_conyugue);
                $sueldo_conyugue = (int) str_replace('$', "", $sueldo_conyugue);
                $model->sueldo_mensual_conyugue = $sueldo_conyugue;
            }

            if (isset($_POST['GestionSolicitudCredito']['otros_ingresos']) && !empty($_POST['GestionSolicitudCredito']['otros_ingresos'])) {
                $otros_ingresos = str_replace(',', "", $_POST['GestionSolicitudCredito']['otros_ingresos']);
                $otros_ingresos = str_replace('.', ",", $otros_ingresos);
                $otros_ingresos = (int) str_replace('$', "", $otros_ingresos);
                $model->otros_ingresos = $otros_ingresos;
            }

            if (isset($_POST['GestionSolicitudCredito']['total_ingresos']) && !empty($_POST['GestionSolicitudCredito']['total_ingresos'])) {
                $total_ingresos = str_replace(',', "", $_POST['GestionSolicitudCredito']['total_ingresos']);
                $total_ingresos = str_replace('.', ",", $total_ingresos);
                $total_ingresos = (int) str_replace('$', "", $total_ingresos);
                $model->total_ingresos = $total_ingresos;
            }
            if (isset($_POST['GestionSolicitudCredito']['gastos_arriendo']) && !empty($_POST['GestionSolicitudCredito']['gastos_arriendo'])) {
                $model->gastos_arriendo = $this->setFormat($_POST['GestionSolicitudCredito']['gastos_arriendo']);
            }
            if (isset($_POST['GestionSolicitudCredito']['gastos_alimentacion_otros']) && !empty($_POST['GestionSolicitudCredito']['gastos_alimentacion_otros'])) {
                $model->gastos_alimentacion_otros = $this->setFormat($_POST['GestionSolicitudCredito']['gastos_alimentacion_otros']);
            }
            if (isset($_POST['GestionSolicitudCredito']['gastos_educacion']) && !empty($_POST['GestionSolicitudCredito']['gastos_educacion'])) {
                $model->gastos_educacion = $this->setFormat($_POST['GestionSolicitudCredito']['gastos_educacion']);
            }
            if (isset($_POST['GestionSolicitudCredito']['gastos_prestamos']) && !empty($_POST['GestionSolicitudCredito']['gastos_prestamos'])) {
                $model->gastos_prestamos = $this->setFormat($_POST['GestionSolicitudCredito']['gastos_prestamos']);
            }
            if (isset($_POST['GestionSolicitudCredito']['gastos_tarjetas_credito']) && !empty($_POST['GestionSolicitudCredito']['gastos_tarjetas_credito'])) {
                $model->gastos_tarjetas_credito = $this->setFormat($_POST['GestionSolicitudCredito']['gastos_tarjetas_credito']);
            }
            if (isset($_POST['GestionSolicitudCredito']['total_egresos']) && !empty($_POST['GestionSolicitudCredito']['total_egresos'])) {
                $model->total_egresos = $this->setFormat($_POST['GestionSolicitudCredito']['total_egresos']);
            }
            //die('post save');
            $model->tipo_producto = $_POST['GestionSolicitudCredito']['tipo_producto'];

            $tipo_vivienda = $_POST['GestionSolicitudCredito']['habita'];
            switch ($tipo_vivienda) {
                case 'Propia':
                    $vav = str_replace(',', "", $_POST['GestionSolicitudCredito']['avaluo_propiedad']);
                    $vav = str_replace('.', ",", $vav);
                    $vav = (int) str_replace('$', "", $vav);
                    $model->avaluo_propiedad = $vav;
                    break;
                case 'Rentada':
                    $vav = str_replace(',', "", $_POST['GestionSolicitudCredito']['valor_arriendo']);
                    $vav = str_replace('.', ",", $vav);
                    $vav = (int) str_replace('$', "", $vav);
                    $model->valor_arriendo = $vav;

                    break;
                case 'Vive con Familiares':


                    break;

                default:
                    break;
            }

            if (isset($_POST['GestionSolicitudCredito']['otros_activos']) && !empty($_POST['GestionSolicitudCredito']['otros_activos'])) {
                $model->otros_activos = $_POST['GestionSolicitudCredito']['otros_activos'];
            }
            if (isset($_POST['GestionSolicitudCredito']['otros_activos2']) && !empty($_POST['GestionSolicitudCredito']['otros_activos2'])) {
                $model->otros_activos2 = $_POST['GestionSolicitudCredito']['otros_activos2'];
            }
            for($i = 0; $i <= count($_POST['activos']); $i++){
                if(!empty($_POST['activos'][$i])){
                    $model->tipo_activo1 = $_POST['activos'][$i];
                }
            }
            for($i = 0; $i <= count($_POST['activos2']); $i++){
                if(!empty($_POST['activos2'][$i])){
                    $model->tipo_activo2 = $_POST['activos2'][$i];
                }
            }
            $hoja_entrega = new GestionHojaEntregaSolicitud;
            $hoja_entrega->id_informacion = $_POST['GestionSolicitudCredito']['id_informacion'];
            $hoja_entrega->id_vehiculo = $_POST['GestionSolicitudCredito']['id_vehiculo'];
            $hoja_entrega->status = 'pendiente';
            $hoja_entrega->save();
            //die('before save');
            if ($model->validate()) {
                
            } else {
                echo '<pre>';
                print_r($model->getErrors());
                echo '</pre>';
                //die('no validate');
            }

            if ($model->save()) {
                require_once 'email/mail_func.php';
                //---- SEND EMAIL ASESOR DE CREDITO
                $asunto = 'Kia Motors Ecuador SGC - Solicitud de Crédito ';


                $general = '<body style="margin: 10px;">
                                <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;">
                                    <div align="">
                                    <img src="images/header_mail.jpg">
                                    <p></p>
                                    <p style="margin: 2px 0;">Estimado/a Asesor de Crédito</p><br />
                                    <p style="margin: 2px 0;">Se ha realizado una solicitud de crédito:</p>

                                    <br>
                                        <table width="600" cellpadding="">';

                $general .= '<tr><td><strong>Cliente:</strong></td><td> ' . $nombre_cliente . '</td></tr> '
                        . '<tr><td><strong>Asesor Comercial:</strong></td><td> ' . $this->getResponsable($id_asesor) . '</td></tr>
                                <tr><td><strong>Concesionario:</strong></td><td>' . $this->getNameConcesionario($id_asesor) . '</td></tr> 
                                <tr><td><strong>Modelo:</strong></td><td> ' . $this->getModeloTestDrive($_POST['GestionSolicitudCredito']['id_vehiculo']) . '</td></tr>
                                <tr><td><strong>Fecha:</strong></td><td> ' . date("d") . "/" . date("m") . "/" . date("Y") . '</td></tr>
                               <tr><td><strong>Hora:</strong></td><td>' . date("H:i:s") . '</td></tr>';

                $general .= ' </table>
                                <br/>
                                <p style="margin: 2px 0;"><a href="https://www.kia.com.ec/intranet/usuario/index.php/site/cotizacion?id_informacion=' . $_POST['GestionSolicitudCredito']['id_informacion'] . '&amp;id_vehiculo=' . $_POST['GestionSolicitudCredito']['id_vehiculo'] . '">Ver Solicitud de Crédito</a></p>
                                <p></p>
                                <p style="margin: 2px 0;">Saludos cordiales,</p>
                                <p style="margin: 2px 0;">SGC</p>
                                <p style="margin: 2px 0;">Kia Motors Ecuador</p>
                                <img src="images/footer.png">
                              </div>
                              </div>
                            </body>';
                //die('table: '.$general);
                $codigohtml = $general;
                $headers = 'From: servicioalcliente@kiamail.com.ec' . "\r\n";
                $headers .= 'Content-type: text/html' . "\r\n";
                $emailAsesorCredito = $this->getEmailAsesorCredito($dealer_id);
                //$emailAsesorCredito = 'alkanware@gmail.com';
                //die('email asesor: '.$emailAsesorCredito);
                sendEmailInfo('servicioalcliente@kiamail.com.ec', "Kia Motors Ecuador", $emailAsesorCredito, html_entity_decode($asunto), $codigohtml);
                //die('enter save');
                $result = TRUE;
                $arr = array('result' => $result, 'id' => $model->id);
                echo json_encode($arr);
            }
        }
    }

    public function actionAprobar() {
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        $user = Yii::app()->db->createCommand()
                ->update('gestion_solicitud_credito', array(
            'status' => 1,
                ), 'id=:id', array(':id' => $id));
        $result = TRUE;
        $options = array('result' => $result);
        echo json_encode($options);
    }

    public function actionAprobarhj() {
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        $user = Yii::app()->db->createCommand()
                ->update('gestion_hoja_entrega_solicitud', array(
            'status' => 'aprobado',
                ), 'id=:id', array(':id' => $id));
        $result = TRUE;
        $options = array('result' => $result);
        echo json_encode($options);
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id_informacion = NULL, $id_vehiculo = NULL) {
        if (isset($_POST['GestionSolicitudCredito'])) {
            $id = $this->getIdSolicitudCredito($_POST['GestionSolicitudCredito']['id_informacion'], $_POST['GestionSolicitudCredito']['id_vehiculo']);
        } else {
            $id = $this->getIdSolicitudCredito($id_informacion, $id_vehiculo);
        }

        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionSolicitudCredito'])) {

//            echo '<pre>';
//            print_r($_POST);
//            echo '</pre>';
//            die();
            //die('enter post');
            $nombre_cliente = $this->getNombresInfo($_POST['GestionSolicitudCredito']['id_informacion']) . ' ' . $this->getApellidosInfo($_POST['GestionSolicitudCredito']['id_informacion']);
            $id_asesor = Yii::app()->user->getId();
            $dealer_id = $this->getConcesionarioDealerId($id_asesor);
            $result = FALSE;
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->attributes = $_POST['GestionSolicitudCredito'];
            $model->id_informacion = $_POST['GestionSolicitudCredito']['id_informacion'];
            $model->id_vehiculo = $_POST['GestionSolicitudCredito']['id_vehiculo'];
            $model->estado_civil = $_POST['GestionSolicitudCredito']['estado_civil'];
            $model->year = $_POST['GestionSolicitudCredito']['year'];
            $model->status = 0;
            $model->fecha = date("Y-m-d H:i:s");
            $model->trabaja_conyugue = $_POST['GestionSolicitudCredito']['trabaja_conyugue'];
            if (isset($_POST['GestionSolicitudCredito']['ruc']) && !empty($_POST['GestionSolicitudCredito']['ruc']))
                $model->ruc = $_POST['GestionSolicitudCredito']['ruc'];
            if (isset($_POST['GestionSolicitudCredito']['pasaporte']) && !empty($_POST['GestionSolicitudCredito']['pasaporte']))
                $model->pasaporte = $_POST['GestionSolicitudCredito']['pasaporte'];

            $valor = str_replace(',', "", $_POST['GestionSolicitudCredito']['valor']);
            $valor = str_replace('.', ",", $valor);
            $valor = (int) str_replace('$', "", $valor);

            $monto_financiar = str_replace(',', "", $_POST['GestionSolicitudCredito']['monto_financiar']);
            $monto_financiar = str_replace('.', ",", $monto_financiar);
            $monto_financiar = (int) str_replace('$', "", $monto_financiar);

            $entrada = str_replace(',', "", $_POST['GestionSolicitudCredito']['entrada']);
            $entrada = str_replace('.', ",", $entrada);
            $entrada = (int) str_replace('$', "", $entrada);

            $cuotamensual = str_replace(',', "", $_POST['GestionSolicitudCredito']['cuota_mensual']);
            $cuotamensual = str_replace('.', ",", $cuotamensual);
            $cuotamensual = (int) str_replace('$', "", $cuotamensual);

            $sueldo = str_replace(',', "", $_POST['GestionSolicitudCredito']['sueldo_mensual']);
            $sueldo = str_replace('.', ",", $sueldo);
            $sueldo = (int) str_replace('$', "", $sueldo);

            $sueldo = str_replace(',', "", $_POST['GestionSolicitudCredito']['sueldo_mensual']);
            $sueldo = str_replace('.', ",", $sueldo);
            $sueldo = (int) str_replace('$', "", $sueldo);

            $model->valor = $valor;
            $model->monto_financiar = $monto_financiar;
            $model->entrada = $entrada;
            $model->sueldo_mensual = $sueldo;
            $model->cuota_mensual = $cuotamensual;
            $model->meses_trabajo = $_POST['GestionSolicitudCredito']['meses_trabajo'];
            $model->meses_trabajo_conyugue = $_POST['GestionSolicitudCredito']['meses_trabajo_conyugue'];
            $model->numero = $_POST['GestionSolicitudCredito']['numero'];

            # INGRESAR NUEVOS VALORES POSIBLES EN TABLA GESTION_FINANCIAMIENTO - UPDATE
            $fin = GestionFinanciamiento::model()->find(array('condition' => "id_informacion = {$_POST['GestionSolicitudCredito']['id_informacion']} AND id_vehiculo = {$_POST['GestionSolicitudCredito']['id_vehiculo']}"));
            $fin->precio_vehiculo = $valor;
            $fin->valor_financiamiento = $monto_financiar;
            $fin->cuota_inicial = $entrada;
            $fin->cuota_mensual = $cuotamensual;
            $fin->plazos = $_POST['GestionSolicitudCredito']['plazo'];
            $fin->tasa = $_POST['GestionSolicitudCredito']['taza'];
            $fin->update();

            if (isset($_POST['GestionSolicitudCredito']['sueldo_mensual_conyugue']) && !empty($_POST['GestionSolicitudCredito']['sueldo_mensual_conyugue'])) {
                $sueldo_conyugue = str_replace(',', "", $_POST['GestionSolicitudCredito']['sueldo_mensual_conyugue']);
                $sueldo_conyugue = str_replace('.', ",", $sueldo_conyugue);
                $sueldo_conyugue = (int) str_replace('$', "", $sueldo_conyugue);
                $model->sueldo_mensual_conyugue = $sueldo_conyugue;
            }

            if (isset($_POST['GestionSolicitudCredito']['otros_ingresos']) && !empty($_POST['GestionSolicitudCredito']['otros_ingresos'])) {
                $otros_ingresos = str_replace(',', "", $_POST['GestionSolicitudCredito']['otros_ingresos']);
                $otros_ingresos = str_replace('.', ",", $otros_ingresos);
                $otros_ingresos = (int) str_replace('$', "", $otros_ingresos);
                $model->otros_ingresos = $otros_ingresos;
            }
            if (isset($_POST['GestionSolicitudCredito']['numero_cargas']) && !empty($_POST['GestionSolicitudCredito']['numero_cargas'])) {
                $model->numero_cargas = $_POST['GestionSolicitudCredito']['numero_cargas'];
            } 

            if (isset($_POST['GestionSolicitudCredito']['total_ingresos']) && !empty($_POST['GestionSolicitudCredito']['total_ingresos'])) {
                $total_ingresos = str_replace(',', "", $_POST['GestionSolicitudCredito']['total_ingresos']);
                $total_ingresos = str_replace('.', ",", $total_ingresos);
                $total_ingresos = (int) str_replace('$', "", $total_ingresos);
                $model->total_ingresos = $total_ingresos;
            }
            if (isset($_POST['GestionSolicitudCredito']['gastos_arriendo']) && !empty($_POST['GestionSolicitudCredito']['gastos_arriendo'])) {
                $model->gastos_arriendo = $this->setFormat($_POST['GestionSolicitudCredito']['gastos_arriendo']);
            }
            if (isset($_POST['GestionSolicitudCredito']['gastos_alimentacion_otros']) && !empty($_POST['GestionSolicitudCredito']['gastos_alimentacion_otros'])) {
                $model->gastos_alimentacion_otros = $this->setFormat($_POST['GestionSolicitudCredito']['gastos_alimentacion_otros']);
            }
            if (isset($_POST['GestionSolicitudCredito']['gastos_educacion']) && !empty($_POST['GestionSolicitudCredito']['gastos_educacion'])) {
                $model->gastos_educacion = $this->setFormat($_POST['GestionSolicitudCredito']['gastos_educacion']);
            }
            if (isset($_POST['GestionSolicitudCredito']['gastos_prestamos']) && !empty($_POST['GestionSolicitudCredito']['gastos_prestamos'])) {
                $model->gastos_prestamos = $this->setFormat($_POST['GestionSolicitudCredito']['gastos_prestamos']);
            }
            if (isset($_POST['GestionSolicitudCredito']['gastos_tarjetas_credito']) && !empty($_POST['GestionSolicitudCredito']['gastos_tarjetas_credito'])) {
                $model->gastos_tarjetas_credito = $this->setFormat($_POST['GestionSolicitudCredito']['gastos_tarjetas_credito']);
            }
            if (isset($_POST['GestionSolicitudCredito']['total_egresos']) && !empty($_POST['GestionSolicitudCredito']['total_egresos'])) {
                $model->total_egresos = $this->setFormat($_POST['GestionSolicitudCredito']['total_egresos']);
            }
            $model->tipo_producto = $_POST['GestionSolicitudCredito']['tipo_producto'];

            $tipo_vivienda = $_POST['GestionSolicitudCredito']['habita'];
            switch ($tipo_vivienda) {
                case 'Propia':
                    $vav = str_replace(',', "", $_POST['GestionSolicitudCredito']['avaluo_propiedad']);
                    $vav = str_replace('.', ",", $vav);
                    $vav = (int) str_replace('$', "", $vav);
                    $model->avaluo_propiedad = $vav;
                    break;
                case 'Rentada':
                    $vav = str_replace(',', "", $_POST['GestionSolicitudCredito']['valor_arriendo']);
                    $vav = str_replace('.', ",", $vav);
                    $vav = (int) str_replace('$', "", $vav);
                    $model->valor_arriendo = $vav;

                    break;
                case 'Vive con Familiares':
                    break;

                default:
                    break;
            }

            if (isset($_POST['GestionSolicitudCredito']['otros_activos']) && !empty($_POST['GestionSolicitudCredito']['otros_activos'])) {
                $model->otros_activos = $_POST['GestionSolicitudCredito']['otros_activos'];
            }
            if (isset($_POST['GestionSolicitudCredito']['otros_activos2']) && !empty($_POST['GestionSolicitudCredito']['otros_activos2'])) {
                $model->otros_activos2 = $_POST['GestionSolicitudCredito']['otros_activos2'];
            }
            for($i = 0; $i <= count($_POST['activos']); $i++){
                if(!empty($_POST['activos'][$i])){
                    $model->tipo_activo1 = $_POST['activos'][$i];
                }
            }
            for($i = 0; $i <= count($_POST['activos2']); $i++){
                if(!empty($_POST['activos2'][$i])){
                    $model->tipo_activo2 = $_POST['activos2'][$i];
                }
            }
            $hoja_entrega = new GestionHojaEntregaSolicitud;
            $hoja_entrega->id_informacion = $_POST['GestionSolicitudCredito']['id_informacion'];
            $hoja_entrega->id_vehiculo = $_POST['GestionSolicitudCredito']['id_vehiculo'];
            $hoja_entrega->status = 'pendiente';
            $hoja_entrega->save();
            //die('before save');
            if ($model->validate()) {
                
            } else {
                echo '<pre>';
                print_r($model->getErrors());
                echo '</pre>';
            }
            if ($model->save()) {
                require_once 'email/mail_func.php';
                //---- SEND EMAIL ASESOR DE CREDITO
                $asunto = 'Kia Motors Ecuador SGC - Solicitud de Crédito ';


                $general = '<body style="margin: 10px;">
                                <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;">
                                    <div align="">
                                    <img src="images/header_mail.jpg">
                                    <p></p>
                                    <p style="margin: 2px 0;">Estimado/a Asesor de Crédito</p><br />
                                    <p style="margin: 2px 0;">Se ha realizado una solicitud de crédito:</p>

                                    <br>
                                        <table width="600" cellpadding="">';

                $general .= '<tr><td><strong>Cliente:</strong></td><td> ' . $nombre_cliente . '</td></tr> '
                        . '<tr><td><strong>Asesor Comercial:</strong></td><td> ' . $this->getResponsable($id_asesor) . '</td></tr>
                                <tr><td><strong>Concesionario:</strong></td><td>' . $this->getNameConcesionario($id_asesor) . '</td></tr> 
                                <tr><td><strong>Modelo:</strong></td><td> ' . $this->getModeloTestDrive($_POST['GestionSolicitudCredito']['id_vehiculo']) . '</td></tr>
                                <tr><td><strong>Fecha:</strong></td><td> ' . date("d") . "/" . date("m") . "/" . date("Y") . '</td></tr>
                               <tr><td><strong>Hora:</strong></td><td>' . date("H:i:s") . '</td></tr>';

                $general .= ' </table>
                                <br/>
                                <p style="margin: 2px 0;"><a href="https://www.kia.com.ec/intranet/usuario/index.php/site/cotizacion?id_informacion=' . $_POST['GestionSolicitudCredito']['id_informacion'] . '&amp;id_vehiculo=' . $_POST['GestionSolicitudCredito']['id_vehiculo'] . '">Ver Solicitud de Crédito</a></p>
                                <p></p>
                                <p style="margin: 2px 0;">Saludos cordiales,</p>
                                <p style="margin: 2px 0;">SGC</p>
                                <p style="margin: 2px 0;">Kia Motors Ecuador</p>
                                <img src="images/footer.png">
                              </div>
                              </div>
                            </body>';
                //die('table: '.$general);
                $codigohtml = $general;
                $headers = 'From: servicioalcliente@kiamail.com.ec' . "\r\n";
                $headers .= 'Content-type: text/html' . "\r\n";
                $emailAsesorCredito = $this->getEmailAsesorCredito($dealer_id);
                //die('email asesor: '.$emailAsesorCredito);
                sendEmailInfo('servicioalcliente@kiamail.com.ec', "Kia Motors Ecuador", $emailAsesorCredito, html_entity_decode($asunto), $codigohtml);
                //die('enter save');
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model, 'id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo
        ));
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
        $dataProvider = new CActiveDataProvider('GestionSolicitudCredito');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new GestionSolicitudCredito('search');
        $model->unsetAttributes();  // clear any default values
        $con = Yii::app()->db;

        if (isset($_GET['GestionSolicitudCredito'])) {
//            echo '<pre>';
//            print_r($_GET);
//            echo '<pre>';
//            die();
            $model->attributes = $_GET['GestionSolicitudCredito'];

            //---- BUSQUEDA GENERAL----
            if (!empty($_GET['GestionSolicitudCredito']['general']) &&
                    empty($_GET['GestionSolicitudCredito']['status']) &&
                    empty($_GET['GestionSolicitudCredito']['responsable'])) {
                //echo 'enter general';

                /* BUSQUEDA POR NOMBRES O APELLIDOS */
                $sql = "SELECT gs.* FROM gestion_solicitud_credito gs "
                        . " WHERE gs.nombres LIKE '%{$_GET['GestionSolicitudCredito']['general']}%' OR"
                        . " gs.apellido_paterno LIKE '%{$_GET['GestionSolicitudCredito']['general']}%'";
                $request = $con->createCommand($sql);
                $posts = $request->queryAll();
                if (count($posts) > 0) {
                    $this->render('admin', array(
                        'model' => $model, 'users' => $posts, 'search' => true
                    ));
                    exit();
                }

                /* BUSQUEDA POR CEDULA,RUC O PASAPORTE */
                $sql = "SELECT gs.* FROM gestion_solicitud_credito gs "
                        . " WHERE gs.cedula LIKE '%{$_GET['GestionSolicitudCredito']['general']}%' OR"
                        . " gs.ruc LIKE '%{$_GET['GestionSolicitudCredito']['general']}%' OR"
                        . " gs.pasaporte LIKE '%{$_GET['GestionSolicitudCredito']['general']}%'";
                $request = $con->createCommand($sql);
                $posts = $request->queryAll();
                if (count($posts) > 0) {
                    $this->render('admin', array(
                        'model' => $model, 'users' => $posts, 'search' => true
                    ));
                    exit();
                }
            }

            //---- BUSQUEDA POR STATUS----
            if (empty($_GET['GestionSolicitudCredito']['general']) &&
                    !empty($_GET['GestionSolicitudCredito']['status']) &&
                    empty($_GET['GestionSolicitudCredito']['responsable'])) {
                //echo 'status';

                $sql = "SELECT gs.*, gt.`status` FROM gestion_solicitud_credito gs 
                        INNER JOIN gestion_status_solicitud gt ON gs.id_informacion = gt.id_informacion 
                        WHERE gt.`status` = '{$_GET['GestionSolicitudCredito']['status']}'";
                //die('sql: ' . $sql);
                $request = $con->createCommand($sql);
                $posts = $request->queryAll();
                //die('num posts: '.count($posts));
                if (count($posts) > 0) {
                    $this->render('admin', array(
                        'model' => $model, 'users' => $posts, 'search' => true
                    ));
                    exit();
                } else {
                    $this->render('admin', array(
                        'model' => $model,
                    ));
                    exit();
                }
            }
            // SEARCH BY RESPONSABLE

            if (empty($_GET['GestionSolicitudCredito']['general']) &&
                    empty($_GET['GestionSolicitudCredito']['status']) &&
                    !empty($_GET['GestionSolicitudCredito']['responsable'])) {
                //echo 'enter responsable'  ;
                $sql = "SELECT gs.* FROM gestion_solicitud_credito gs 
                        INNER JOIN usuarios u ON u.id = gs.vendedor 
                        INNER JOIN gestion_informacion gi ON gi.id = gs.id_informacion
                        WHERE u.id = {$_GET['GestionSolicitudCredito']['responsable']}";
                //die($sql);
                $request = $con->createCommand($sql);
                $posts = $request->queryAll();
                //die('num posts: '.count($posts));
                if (count($posts) > 0) {
                    $this->render('admin', array(
                        'model' => $model, 'users' => $posts, 'search' => true
                    ));
                    exit();
                } else {
                    $this->render('admin', array(
                        'model' => $model, 'title' => 'No existen resultados'
                    ));
                    exit();
                }
            }
        }


        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return GestionSolicitudCredito the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = GestionSolicitudCredito::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exists.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param GestionSolicitudCredito $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'gestion-solicitud-credito-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionCotizacion($id_informacion, $id_vehiculo) {
        $this->layout = '//layouts/call-print';
        //die('enter prof');
        $con = Yii::app()->db;
        //die('num solicitud:'.$num_solicitud);

        $num_solicitud = $this->getLastSolicitudCotizacion($id_informacion, $id_vehiculo);

        # mPDF
        $mPDF1 = Yii::app()->ePdf->mpdf();
        $mPDF1->setFooter('{PAGENO}');
        $mPDF1->SetTitle('Solicitud de Crédito');

        # You can easily override default constructor's params
        $mPDF1 = Yii::app()->ePdf->mpdf('', 'A4');

        //$mPDF1->WriteHTML($this->render('pdf2', array('data' => $request), true));
        # Load a stylesheet
        $stylesheet = file_get_contents(Yii::getPathOfAlias('webroot.bootstrap.css') . '/bootstrap.css');
        $mPDF1->WriteHTML($stylesheet, 1);
        # renderPartial (only 'view' of current controller)
        //$this->render('cotizacion', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo, 'id_hoja' => $num_solicitud));
        $mPDF1->WriteHTML($this->renderPartial('cotizacion', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo, 'id_hoja' => $num_solicitud), true));

        # Renders image
        //$mPDF1->WriteHTML(CHtml::image(Yii::getPathOfAlias('webroot.css') . '/bg.gif' ));
        # Outputs ready PDF
        $mPDF1->Output('solicitud-de-credito.pdf', 'I');
    }

    public function actionStatus($id = null, $id_informacion = null, $id_vehiculo = null, $id_status = null) {
        $modelst = $this->loadModel($id);

        $model = new GestionStatusSolicitud;
//        echo '<pre>';
//        print_r($_POST);
//        echo '</pre>';
//        die();
        if (isset($_POST['GestionStatus'])) {
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            //die('enter gestion status');
            $cr = new CDbCriteria(array(
                'condition' => "id_informacion='{$_POST['GestionStatus']['id_informacion']}' AND id_vehiculo='{$_POST['GestionStatus']['id_vehiculo']}'"
            ));
            $pr = GestionStatusSolicitud::model()->count($cr);
            
            $model->attributes = $_POST['GestionStatus'];
            $model->id_informacion = $_POST['GestionStatus']['id_informacion'];
            $model->id_vehiculo = $_POST['GestionStatus']['id_vehiculo'];
            $model->observaciones = $_POST['GestionStatus']['observaciones'];
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            $model->save();

            // UPDATE table gestion_solicitud_credito to new status
            $model_solicitud = GestionSolicitudCredito::model()->find(array('condition' => "id_informacion=:id_informacion AND id_vehiculo=:id_vehiculo", 'params' => array(':id_informacion' => $_POST['GestionStatus']['id_informacion'], ':id_vehiculo' => $_POST['GestionStatus']['id_vehiculo'])));
            if (!empty($model_solicitud)) {
                $model_solicitud->status = $_POST['GestionStatus']['status'];
                $model_solicitud->update();
            }
//            }
//            if ($_POST['GestionStatus']['status'] == 2) {
//                $con = Yii::app()->db;
//                $sql = "UPDATE gestion_solicitud_credito SET status = 2 WHERE id = {$_POST['GestionStatus']['id_status']}";
//                $request = $con->createCommand($sql)->query();
//            }

            $agen = new GestionAgendamiento;
            $agen->id_informacion = $_POST['GestionStatus']['id_informacion'];
            $agen->fecha = date("Y-m-d H:i:s");
            $agen->save();

            $not = new GestionNotificaciones;
            //$id_asesor = Yii::app()->user->getId();
            $id_asesor = $this->getResponsableId($_POST['GestionStatus']['id_informacion']);
            $not->tipo = 2; // tipo solicitud credito
            $not->id_informacion = $_POST['GestionStatus']['id_informacion'];
            $not->id_asesor = $id_asesor;
            $not->paso = 11;
            $not->id_dealer = $this->getDealerId(Yii::app()->user->getId());
            switch ($_POST['GestionStatus']['status']) {
                case 1:
                    $not->descripcion = 'Solicitud de Crédito en Análisis';

                    break;
                case 2:
                    $not->descripcion = 'Solicitud de Crédito Aprobada';

                    break;
                case 3:
                    $not->descripcion = 'Solicitud de Crédito Negada';

                    break;
                case 4:
                    $not->descripcion = 'Solicitud de Crédito Condicionada';

                    break;

                default:
                    break;
            }

            $not->fecha = date("Y-m-d H:i:s");
            $not->id_agendamiento = $agen->id;
            $not->save();

            $this->redirect(array('gestionSolicitudCredito/admin'));
        }
        $this->render('status', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo, 'id_status' => $id_status, 'modelst' => $modelst,));
    }

    public function setFormat($input){
        $res = str_replace(',', "", $input);
        $res = str_replace('.', ",", $input);
        $res = (int) str_replace('$', "", $input);
        return $res;
    }

}
