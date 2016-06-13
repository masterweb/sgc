<?php

class GestionComentariosController extends Controller {

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
                'actions' => array('create', 'update'),
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
    public function actionCreate($id_informacion = NULL) {
        $model = new GestionComentarios;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionComentarios'])) {
            $model->attributes = $_POST['GestionComentarios'];
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            $model->id_vehiculo = $_POST['GestionComentarios']['id_vehiculo'];

            if ($model->save()) {
                $cargo_id = (int) Yii::app()->user->getState('cargo_id');
                $grupo_id = (int) Yii::app()->user->getState('grupo_id');
                $id_asesor = Yii::app()->user->getId();
                $dealer_id = $this->getDealerId($id_asesor);
                $concesionarioid = $this->getConcesionarioDealerId($_POST['GestionComentarios']['id_responsable_enviado']);
                $not = new GestionNotificaciones;
                require_once 'email/mail_func.php';
                if ($cargo_id == 70) { // jefe de almacen
                    // enviar notificacion al jefe de almacen
                    $not->tipo = 5; // tipo seguimiento
                    $not->paso = 12;
                    $not->id_informacion = $_POST['GestionComentarios']['id_informacion'];
                    $not->id_asesor = $_POST['GestionComentarios']['id_responsable_recibido'];
                    $not->id_dealer = $this->getDealerId(Yii::app()->user->getId());
                    $not->descripcion = 'Se ha creado un nuevo comentario';
                    $not->fecha = date("Y-m-d H:i:s");
                    $not->id_asesor_envia = $_POST['GestionComentarios']['id_responsable_enviado'];
                    $not->id_agendamiento = $model->id;
                    $not->save();

                    $asunto = 'Kia Motors Ecuador - Comentario enviado por Jefe de Sucursal; ' . $this->getResponsable($_POST['GestionComentarios']['id_responsable_enviado']) . '.';
                    $email = $this->getAsesorEmail($_POST['GestionComentarios']['id_responsable_recibido']);
                    //die('email asesor: '.$email);
                    $general = '<body style="margin: 10px;">
                                <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;">
                                    <div align="">
                                    <img src="images/header_mail.jpg"><br>
                                        <p style="margin: 2px 0;">Señor(a): Asesor de Ventas: ' . $this->getResponsable($_POST['GestionComentarios']['id_responsable_recibido']) . '</p>
                                        <p></p> <br />   

                                        <p style="margin: 2px 0;">Se ha generado un comentario desde la plataforma de comentarios.</p><br /> 
                                           
                                        <p style="margin: 2px 0;">A continuación le presentamos el detalle:</p><br /><br />
                                        
                                        <table width="600">
                                        <tr><td><strong>Jefe Comercial:</strong></td><td>' . $this->getResponsable($id_asesor) . '</td></tr>
                                        <tr><td><strong>Concesionario:</strong></td><td>' . $this->getNombreConcesionario($concesionarioid) . '</td></tr> 
                                        <tr><td><strong>Modelo:</strong></td><td>' . $this->getModeloTestDrive($_POST['GestionComentarios']['id_vehiculo']) . '</td></tr>
                                        <tr><td><strong>Fecha:</strong></td><td>' . date("d") . "/" . date("m") . "/" . date("Y") . '</td></tr>
                                        <tr><td><strong>Hora:</strong></td><td>' . date("H:i:s") . '</td></tr>
                                        </table>
                                        <br/><br />
                                        <p style="margin: 2px 0;">Por favor ingresar a la plataforma,<a href="' . Yii::app()->createAbsoluteUrl('gestionComentarios/create', array('id_informacion' => $_POST['GestionComentarios']['id_informacion'], 'id' => $model->id, 'validate' => 'true')) . '">Aquí</a></p><br />
                                        <p>Kia Motors Ecuador</p><br /><br />

                                        
                                    </div>
                                </div>
                            </body>';
                }
                if ($cargo_id == 71) { // asesor de ventas
                    
                    // enviar notificacion al asesor de ventas
                    $not->tipo = 5; // tipo seguimiento
                    $not->paso = 12;
                    $not->id_informacion = $_POST['GestionComentarios']['id_informacion'];
                    $not->id_asesor = $_POST['GestionComentarios']['id_responsable_recibido'];
                    $not->id_dealer = $this->getDealerId(Yii::app()->user->getId());
                    $not->descripcion = 'Se ha creado un nuevo comentario';
                    $not->fecha = date("Y-m-d H:i:s");
                    $not->id_asesor_envia = $_POST['GestionComentarios']['id_responsable_enviado'];
                    $not->id_agendamiento = $model->id;
                    $not->save();
                    $nombre_jefe_sucursal = $this->getNombresJefeConcesion(70, $grupo_id, $dealer_id);
                    $email = $this->getEmailJefeConcesion(70, $grupo_id, $dealer_id); //email jefe de sucursal
                    //die('nombre jefe de sucursal; '.$nombre_jefe_sucursal);
                    $asunto = 'Kia Motors Ecuador - Comentario enviado por Asesor de Ventas: ' . $this->getResponsable($_POST['GestionComentarios']['id_responsable_recibido']) . '.';
                    $general = '<body style="margin: 10px;">
                                <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;">
                                    <div align="">
                                    <img src="images/header_mail.jpg"><br>
                                        <p style="margin: 2px 0;">Señor(a): Jefe de Sucursal: ' . $nombre_jefe_sucursal . '</p>
                                        <p></p> <br />   

                                        <p style="margin: 2px 0;">Se ha generado un comentario desde la plataforma de comentarios.</p><br /> 
                                           
                                        <p style="margin: 2px 0;">A continuación le presentamos el detalle:</p><br /><br />
                                        
                                        <table width="600">
                                        <tr><td><strong>Asesor de Ventas:</strong></td><td>' . $this->getResponsable($id_asesor) . '</td></tr>
                                        <tr><td><strong>Concesionario:</strong></td><td>' . $this->getNombreConcesionario($concesionarioid) . '</td></tr> 
                                        <tr><td><strong>Modelo:</strong></td><td>' . $this->getModeloTestDrive($_POST['GestionComentarios']['id_vehiculo']) . '</td></tr>
                                        <tr><td><strong>Fecha:</strong></td><td>' . date("d") . "/" . date("m") . "/" . date("Y") . '</td></tr>
                                        <tr><td><strong>Hora:</strong></td><td>' . date("H:i:s") . '</td></tr>
                                        </table>
                                        <br/><br />
                                        <p style="margin: 2px 0;">Por favor ingresar a la plataforma,<a href="' . Yii::app()->createAbsoluteUrl('gestionComentarios/create', array('id_informacion' => $_POST['GestionComentarios']['id_informacion'], 'id' => $model->id, 'validate' => 'true')) . '">Aquí</a></p><br />
                                        <p>Kia Motors Ecuador</p><br /><br />

                                        
                                    </div>
                                </div>
                            </body>';
                }
                $codigohtml = $general;
                $headers = 'From: servicioalcliente@kiamail.com.ec' . "\r\n";
                $headers .= 'Content-type: text/html' . "\r\n";
                //$email = 'alkanware@gmail.com'; //email administrador

                $send = sendEmailInfo('servicioalcliente@kiamail.com.ec', "Kia Motors Ecuador", $email, html_entity_decode($asunto), $codigohtml);

                $this->render('create', array(
                    'model' => $model,
                ));
                exit();
            }
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

        if (isset($_POST['GestionComentarios'])) {
            $model->attributes = $_POST['GestionComentarios'];
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
        $dataProvider = new CActiveDataProvider('GestionComentarios');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new GestionComentarios('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['GestionComentarios']))
            $model->attributes = $_GET['GestionComentarios'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return GestionComentarios the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = GestionComentarios::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param GestionComentarios $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'gestion-comentarios-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
