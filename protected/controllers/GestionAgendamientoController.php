<?php

class GestionAgendamientoController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

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
                'actions' => array('create', 'update', 'createCat'),
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
    public function actionCreate() {
        $model = new GestionAgendamiento;
        $cargo_id = (int) Yii::app()->user->getState('cargo_id');

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionAgendamiento'])) {
            //die('enter agendamiento');
    //     echo '<pre>';
    //     print_r($_POST);
    //     echo '</pre>';
    //       die();
            $model->attributes = $_POST['GestionAgendamiento'];
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            if (isset($_POST['GestionAgendamiento']['observacion_categorizacion']) && !empty($_POST['GestionAgendamiento']['observacion_categorizacion']))
                $model->observacion_categorizacion = $_POST['GestionAgendamiento']['observacion_categorizacion'];
            if (isset($_POST['GestionAgendamiento']['observacion_seguimiento']) && !empty($_POST['GestionAgendamiento']['observacion_seguimiento']))
                $model->observacion_seguimiento = $_POST['GestionAgendamiento']['observacion_seguimiento'];

            //if (isset($_POST['GestionAgendamiento']['tipo_form_web']) && !empty($_POST['GestionAgendamiento']['tipo_form_web']))
            //if ($_POST['GestionAgendamiento']['observaciones'] == 'Otro')
            $model->otro_observacion = $_POST['GestionAgendamiento']['otro'];

//DIE('CASE: '.$_POST['GestionAgendamiento']['observaciones']);
            switch ($_POST['GestionAgendamiento']['observaciones']) {
                case 'Falta de tiempo':
                case 'Llamada de emergencia':
                    // poner status Seguimiento en Gestion Diaria
                    $con = Yii::app()->db;
                    $sql = "UPDATE gestion_diaria SET seguimiento = 1 ,proximo_seguimiento = '{$_POST['GestionAgendamiento']['agendamiento']}' WHERE id_informacion = {$_POST['GestionAgendamiento']['id_informacion']}";
                    $request = $con->createCommand($sql)->query();

                    break;
                //SI EL CLIENTE DESISTE VA AL CALLCENTER PARA ENCUESTA
                case 'Desiste':
                    $con = Yii::app()->db;
                    $sql = "UPDATE gestion_diaria SET desiste = 1 WHERE id_informacion = {$_POST['GestionAgendamiento']['id_informacion']}";
                    $request = $con->createCommand($sql)->query();
                    $sql2 = "UPDATE gestion_consulta SET preg7 = 'Very Cold(mas de 6 meses)' WHERE id_informacion = {$_POST['GestionAgendamiento']['id_informacion']}";
                    $request2 = $con->createCommand($sql2)->query();
                    break;
                case 'Cita':
                // INGRESAR LA PRIMERA CITA COMO TRAFICO EN SOLICITUDES WEB
                    $cita = new GestionCita;
                    $cita->id_informacion = $_POST['GestionAgendamiento']['id_informacion'];
                    $cita->fecha = $_POST['GestionAgendamiento']['agendamiento'];//date("Y-m-d H:i:s");
                    # SI ES ASESOR TELEMARKETING WEB, COLOCAR CITA EN 1 Y tw EN 1
                    if((int) Yii::app()->user->getState('cargo_id') == 89){
                        //$gc = GestionCita::model()->count(array('condition' => "id_informacion={$_POST['GestionAgendamiento']['id_informacion']} AND `order` = 2 AND tw = 0"));
                        //if($gc > 0){
                            $cita->order = 1;
                            $cita->tw = 1;
                        //}

                    }else{// EL RESTO DE ASESORES
                        $gc = GestionCita::model()->count(array('condition' => "id_informacion={$_POST['GestionAgendamiento']['id_informacion']}"));
                        if($gc > 0){
                            $cita->order = 2;
                        }
                    }
                    $cita->save();
                    // TIPO DE COTIZACION
                    
                    $fuente = $this->getFuenteSGC($_POST['GestionAgendamiento']['id_informacion']);
                    // SI ES WEB PARA ASESORES TELEMERCADEO WEB
                    // REASIGNAR AL JEFE DE AGENCIA CORRESPONDIENTE
                    if( ($fuente == 'web' || $fuente == 'web_espectaculo') && (Yii::app()->user->getState('cargo_id') == 89 && (GestionFinanciamiento::model()->count(array('condition' => "id_informacion = {$_POST['GestionAgendamiento']['id_informacion']}")) > 0))){
                        $jefe_agencia_id = $this->getJefeAgenciaTW($_POST['GestionAgendamiento']['id_informacion']);
                        $fin = GestionInformacion::model()->find(array('condition' => "id = {$_POST['GestionAgendamiento']['id_informacion']}"));
                        $fin->responsable = $jefe_agencia_id;
                        $fin->responsable_origen_tm = (int) Yii::app()->user->getId();
                        $fin->reasignado_tm = 1;
                        $fin->update();
                    }


                    $this -> sendMeetingNotification($fin->responsable, $fin->responsable_origen_tm,
                        $_POST['GestionAgendamiento']['id_informacion'],$_POST['GestionAgendamiento']['agendamiento'],
                        $_POST['GestionAgendamiento']['otro']);
                    break;

                default:
                    break;
            }

            $not = new GestionNotificaciones;
            if (isset($_POST['GestionAgendamiento']['pasoconsulta'])) {
                switch ($_POST['GestionAgendamiento']['categorizacion']) {
                    case '1':
                        $model->categorizacion = 'Hot A(hasta 7 dias)';
                        $not->categorizacion = 'Hot A(hasta 7 dias)';
                        break;
                    case '2':
                        $model->categorizacion = 'Hot B(hasta 15 dias)';
                        $not->categorizacion = 'Hot B(hasta 15 dias)';
                        break;
                    case '3':
                        $model->categorizacion = 'Hot C(hasta 30 dias)';
                        $not->categorizacion = 'Hot B(hasta 15 dias)';
                        break;
                    case '4':
                        $model->categorizacion = 'Warm(hasta 3 meses)';
                        $not->categorizacion = 'Hot B(hasta 15 dias)';
                        break;
                    case '5':
                        $model->categorizacion = 'Warm(hasta 6 meses)';
                        $not->categorizacion = 'Hot B(hasta 15 dias)';
                        break;
                    case '6':
                        $model->categorizacion = 'Very Cold(mas de 6 meses)';
                        $not->categorizacion = 'Hot B(hasta 15 dias)';
                        break;

                    default:
                        break;
                }
            } elseif (isset($_POST['GestionAgendamiento']['categorizacion'])) {
                //die('enter normal');
                switch ($_POST['GestionAgendamiento']['categorizacion']) {
                    case 'Hot A (hasta 7 dias)':
                        $model->categorizacion = 'Hot A(hasta 7 dias)';
                        $not->categorizacion = 'Hot A(hasta 7 dias)';
                        break;
                    case 'Hot B (hasta 15 dias)':
                        $model->categorizacion = 'Hot B(hasta 15 dias)';
                        $not->categorizacion = 'Hot B(hasta 15 dias)';
                        break;
                    case 'Hot C (hasta 30 dias)':
                        $model->categorizacion = 'Hot C(hasta 30 dias)';
                        $not->categorizacion = 'Hot C(hasta 30 dias)';
                        break;
                    case 'Warm (hasta 3 meses)':
                        $model->categorizacion = 'Warm(hasta 3 meses)';
                        $not->categorizacion = 'Warm(hasta 3 meses)';
                        break;
                    case 'Cold (hasta 6 meses)':
                        $model->categorizacion = 'Cold (hasta 6 meses)';
                        $not->categorizacion = 'Cold (hasta 6 meses)';
                        break;
                    case 'Very Cold(mas de 6 meses)':
                        $model->categorizacion = 'Very Cold(mas de 6 meses)';
                        $not->categorizacion = 'Very Cold(mas de 6 meses)';
                        break;

                    default:
                        break;
                }
            }

            if (isset($_POST['GestionAgendamiento']['categorizacion'])) {
                $con = Yii::app()->db;
                $sql = "UPDATE gestion_diaria SET seguimiento = 1 ,proximo_seguimiento = '{$_POST['GestionAgendamiento']['agendamiento']}' WHERE id_informacion = {$_POST['GestionAgendamiento']['id_informacion']}";
                $request = $con->createCommand($sql)->query();
            }

            if (isset($_POST['GestionInformacion']['tipo_form_web'])) {
                $con = Yii::app()->db;
                $sql = "UPDATE gestion_diaria SET seguimiento = 1 ,proximo_seguimiento = '{$_POST['GestionAgendamiento']['agendamiento']}' WHERE id_informacion = {$_POST['GestionAgendamiento']['id_informacion']}";
                $request = $con->createCommand($sql)->query();
                if ($model->save())
                    $this->redirect(array('gestionInformacion/seguimientoUsados'));
            }

            // CONOCER SI EL TIPO DE FUENTE EN EL FORMULARIO ES EXONERADO PARA REDIRECCIONAR AL RGD DE EXONERADOS
            $fuente = $this->getFuenteExonerados($_POST['GestionAgendamiento']['id_informacion']);
            if ($fuente == 'exonerados') {
                $con = Yii::app()->db;
                $sql = "UPDATE gestion_diaria SET seguimiento = 1 ,proximo_seguimiento = '{$_POST['GestionAgendamiento']['agendamiento']}' WHERE id_informacion = {$_POST['GestionAgendamiento']['id_informacion']}";
                $request = $con->createCommand($sql)->query();
                if ($model->save())
                    $this->redirect(array('gestionInformacion/seguimientoexonerados'));
            }


            if ($model->save()) {
                $id_asesor = Yii::app()->user->getId();
                $not->tipo = 1; // tipo seguimiento
                $not->paso = $_POST['GestionAgendamiento']['paso'];
                $not->id_informacion = $_POST['GestionAgendamiento']['id_informacion'];
                $not->id_asesor = $id_asesor;
                $not->id_dealer = $this->getDealerId(Yii::app()->user->getId());
                $not->descripcion = 'Se ha creado un nuevo seguimiento';
                $not->fecha = date("Y-m-d H:i:s");
                $not->id_agendamiento = $model->id;
                $not->save();
                switch ($cargo_id) {
                    case 71: // asesor de ventas
                    case 70: // jefe sucursal
                    case 89: // jefe sucursal
                        $this->redirect(array('gestionInformacion/seguimiento'));
                        break;
                    case 75: // asesor de exonerados
                        $this->redirect(array('gestionInformacion/seguimientoexonerados'));
                        break;
                    case 73: // asesor bdc
                    case 72: // jefe bdc 
                    case 85: // asesor externas
                    case 86: // jefe ventas externas    
                        $this->redirect(array('gestionInformacion/seguimientobdc'));
                        break;
                    default:
                        break;
                }
                
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionCreateCat() {
        if (isset($_POST['GestionAgendamiento'])) {

//            echo '<pre>';
//            print_r($_POST);
//            echo '</pre>';
//            die();
            $categorizacion = new GestionCategorizacion;
            $categorizacion->id_informacion = $_POST['GestionAgendamiento']['id_informacion'];
            $categorizacion->categorizacion = $_POST['GestionAgendamiento']['categorizacion'];
            $categorizacion->paso = $_POST['GestionAgendamiento']['paso'];
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $categorizacion->fecha = date("Y-m-d H:i:s");
            $categorizacion->save();


            $connection = Yii::app()->db;
            $sql = "UPDATE gestion_consulta SET preg7 = '{$_POST['GestionAgendamiento']['categorizacion']}' "
                    . "WHERE id_informacion = {$_POST['GestionAgendamiento']['id_informacion']} ";

            $command = $connection->createCommand($sql);
            $rowCount = $command->execute();

            if ($rowCount > 0)
                echo TRUE;
        }
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

        if (isset($_POST['GestionAgendamiento'])) {
            $model->attributes = $_POST['GestionAgendamiento'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
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
        $dataProvider = new CActiveDataProvider('GestionAgendamiento');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new GestionAgendamiento('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['GestionAgendamiento']))
            $model->attributes = $_GET['GestionAgendamiento'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return GestionAgendamiento the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = GestionAgendamiento::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param GestionAgendamiento $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'gestion-agendamiento-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function sendMeetingNotification($jefeAgencia, $telemercadista,$id_informacion,$fechaCita,$observacion){
       
        $emailJefeAgencia = $this->getAsesorEmail($jefeAgencia);
        $emailTelemercadista = $this->getAsesorEmail($telemercadista);

        $nameJefeAgencia = $this->getAsesorName($jefeAgencia);
        $nameTelemercadista = $this->getAsesorName($telemercadista);

        $nombreCliente = $this->getNombreCliente($id_informacion);
        $nombreConcecionario = $this->getConcesionario($this->getAsesorDealersId($jefeAgencia));
        
        $version=$this->getVersionByIdInformacion($id_informacion);

        $modelo=$this->getModeloByIdInformacion($id_informacion);

            require_once 'email/mail_func.php';

              $body=   '<style>
                            body {margin: 0; padding: 0; min-width: 100%!important;}
                        </style>
                    </head>

                    <body>
                        <table cellpadding="0" cellspacing="0" width="650" align="center" border="0">
                            <tr>
                                <td align="center"><a href="https://www.kia.com.ec" target="_blank"><img src="images/mailing/headerMessage.png" width="569" height="60" alt="" style="display:block; border:none;"/></a></td>
                            </tr>


                            <tr>
                                <td style="font-family:Arial, sans-serif; font-size:16px; color:#5e5e5e; text-align:center; padding-top:15px; padding-bottom:15px;">Estimado/a <strong>'.$nameJefeAgencia.'</strong>,<br/>
                                    </td>
                             </tr>

                              <tr>
                                <td style="font-family:Arial, sans-serif; font-size:16px; color:#5e5e5e; text-align:center; padding-top:15px; padding-bottom:15px;">El Asesor TeleMercaderista <strong>'.$nameTelemercadista.'</strong>, acabó de generar una nueva cita del cliente <strong>'.$nombreCliente.'</strong> asigando para su concesionario <strong>'.$nombreConcecionario.'.</strong><br/>
                                 
                                </tr> 
                                </table>
                                <table cellpadding="0" cellspacing="0" width="650" align="center" border="0">

                                    <tr>
                                        <td style="font-family:Arial, sans-serif; font-size:16px; color:#5e5e5e; text-align:center; padding-top:15px; padding-bottom:15px;" width="25%">Fecha de Cita:
                                         </td>
                                         <td style="font-family:Arial, sans-serif; font-size:16px; color:#5e5e5e; text-align:left; padding-top:15px; padding-bottom:15px;" width="75%"><strong>'.$fechaCita.'</strong><br/>
                                         </td>
                                     </tr>


                                      <tr>
                                        <td style="font-family:Arial, sans-serif; font-size:16px; color:#5e5e5e; text-align:center; padding-top:15px; padding-bottom:15px;" width="25%">Observación:
                                         </td>
                                         <td style="font-family:Arial, sans-serif; font-size:16px; color:#5e5e5e; text-align:left; padding-top:15px; padding-bottom:15px;" width="75%"><strong>'.$observacion.'</strong><br/>
                                         </td>
                                     </tr>

                                     <tr>
                                        <td style="font-family:Arial, sans-serif; font-size:16px; color:#5e5e5e; text-align:center; padding-top:15px; padding-bottom:15px;" width="25%">Modelo:
                                         </td>
                                         <td style="font-family:Arial, sans-serif; font-size:16px; color:#5e5e5e; text-align:left; padding-top:15px; padding-bottom:15px;" width="75%"><strong>'.$modelo.'</strong><br/>
                                         </td>
                                     </tr>

                                    <tr>
                                        <td style="font-family:Arial, sans-serif; font-size:16px; color:#5e5e5e; text-align:center; padding-top:15px; padding-bottom:15px;" width="25%">Versión:
                                         </td>
                                         <td style="font-family:Arial, sans-serif; font-size:16px; color:#5e5e5e; text-align:left; padding-top:15px; padding-bottom:15px;" width="75%"><strong>'.$version.'</strong><br/>
                                         </td>
                                     </tr>
                                </table>    
                                <table cellpadding="0" cellspacing="0" width="650" align="center" border="0">

                                        <tr>
                                            <td style="font-family:Arial, sans-serif; font-size:16px; color:#5e5e5e; text-align:center; padding-top:15px; padding-bottom:15px;">
                                            Por favor realizar la gestión del cliente re asignando a uno de sus asesores
                                             comerciales en los próximos 60 minutos y verificando que se realice el correcto seguimiento del 
                                             cliente. </td>
                                             
                                        </tr> 

                                        <tr>
                                            <td style="font-family:Arial, sans-serif; font-size:16px; color:#5e5e5e; text-align:center; padding-top:15px; padding-bottom:15px;">
                                            Es importante que el Asesor Comercial, confirme en el Paso 7 (Negociación) 
                                            si se realizó o no la cita generada por la Telemercaderista.</td>
                                             
                                        </tr> 
            
                                        <tr>
                                            <td style="font-family:Arial, sans-serif; font-size:16px; color:#5e5e5e; text-align:center; padding-top:15px; padding-bottom:15px;">
                                            <strong>Kia Motors Ecuador.</strong></td>
                                             
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
                   
                $emailCliente = $this->getAsesorEmail($jefeAgencia);
                $id_asesor = Yii::app()->user->getId();
               $emailAsesor = $this->getAsesorEmail($id_asesor);
               $asunto = 'SGC NUEVA CITA GENERADA POR TELEMERCADERISTA';           
                sendEmailInfoTestDrive('servicioalcliente@kiamail.com.ec', "Kia Motors Ecuador", "", $emailAsesor, html_entity_decode($asunto), $body);
    }

}
