<?php

class GestionSeguimientoController extends Controller {

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
    public function actionCreate($id_informacion = null, $id_vehiculo = null) {
        $model = new GestionSeguimiento;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionSeguimiento'])) {
            $model->attributes = $_POST['GestionSeguimiento'];
            if ($model->save()) {
                $con = Yii::app()->db;
                $sql = "UPDATE gestion_diaria SET seguimiento = 1, paso = '10', status = 1 WHERE id_informacion = {$id_informacion}";
                $request = $con->createCommand($sql)->query();
                $this->redirect(array('gestionInformacion/seguimiento'));
            }
        }
        
        // actualizar base de datos con el paso 10 en gestion_diaria
        Yii::app()->db
                    ->createCommand("UPDATE gestion_diaria SET entrega = 1, paso = 10 WHERE id_informacion=:ID")
                    ->bindValues(array(':ID' => $id_informacion))
                    ->execute();
        
        // mandar email de carta de bienvenida al cliente y email al asesor para llamada de seguimiento
        
        $emailCliente = $this->getEmailCliente($id_informacion);
        $id_asesor = Yii::app()->user->getId();
        $nombre_asesor = $this->getResponsableNombres($id_asesor);
        $cargo_id = (int) Yii::app()->user->getState('cargo_id');
        $grupo_id = (int) Yii::app()->user->getState('grupo_id');
        $nombre_cliente = $this->getNombresInfo($id_informacion) . ' ' . $this->getApellidosInfo($id_informacion);
        $modelo = $this->getModeloInfo($id_vehiculo);
        $ciudadCliente = $this->getCiudadConcesionario($id_informacion);
        $ciudad = $this->getCiudad($id_asesor);
        date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
        $fecha_m = date("d m Y");
        $fecha_m = $this->getFormatFecha($fecha_m);
        //die('ciudad cliente: '.$ciudadCliente);
        // ENVIAR EMAIL CON CARTA DE BIENVENIDA AL CLIENTE
        require_once 'email/mail_func.php';
        $asunto = '[Kia Motors Ecuador] Bienvenido a la Familia Kia Motors Ecuador';
        $general = '<body style="margin: 10px;">
                                <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;">
                                    <div align="">
                                    <img src="images/header_mail.jpg">
                                    <br>
                                        <p>' . $ciudadCliente . ', ' . $fecha_m . '</p><br /><br />
                                        <p>Señor(a)</p>
                                        <p>' . $nombre_cliente . '</p>
                                        <p>' . $ciudadCliente . '.-</p>
                                        <br />
                                        <p>
                                        KIA MOTORS ECUADOR le da la bienvenida, agradecemos la confianza al haber escogido uno de nuestros vehículos KIA, con la mejor tecnología Coreana. 
                                        </p><br />
                                        <p>
                                        Le recordamos que su vehículo cuenta con una';
        if ($modelo == 86) {
            $general .= ' garantía de 7 años o 120.000 Km, ';
        } else {
            $general .=' garantía de 5 años o 100.000 Km, ';
        }
        $general .= 'para mantener dicha garantía, usted debe realizar los mantenimientos en nuestro <a href="https://www.kia.com.ec/concesionarios.html"> concesionario KIA a nivel nacional</a>. 
                                        </p><br />
                                        <p>
                                        Nuestra prioridad es servirle de la mejor manera, por lo que usted tiene a su disposición la nueva línea gratuita de Servicio al Cliente 1800 KIA KIA (1800 542 542), donde Usted podrá obtener información de Vehículos Nuevos, Seminuevos, Talleres de Servicio Autorizado Kia, Costos de Mantenimiento Preventivos, Repuestos y Accesorios, Políticas de Garantías de su Vehículo, etc. Nuestro personal de la línea 1800 KIAKIA podrá ayudarle también a realizar su próxima cita de mantenimiento para que Ud. pueda continuar disfrutando de su vehículo Kia en todo momento, basta con llamar y uno de nuestros asesores podrá brindarle el mejor servicio para su próxima cita.
                                        </p><br />
                                        <p>
                                        Para complementar nuestro servicio hemos incorporado para usted, totalmente gratis por un año, un producto denominado “Asistencia KIA” el cual le asiste ante cualquier desperfecto mecánico, las 24 horas del día, los 365 días del año. La cobertura del producto “Asistencia KIA” comprende de:
                                        </p><br />
                                        <ul>
                                            <li><strong>Remolque o traslado de su vehículo en caso de avería o accidente hasta el concesionario Kia más cercano</strong></ol>
                                            <li><strong>Auxilio Mecánico: </strong>asistencia en caso llanta baja, falta de combustible, carga de batería.</ol>
                                            <li><strong>Traslado en ambulancia en caso accidente de tránsito</strong></ol>
                                            <li><strong>Cobertura en caso de viaje: </strong>en caso de que su vehículo quede inmovilizado, sufra hurto simple o calificado se cubrirá la estancia y desplazamiento de los ocupantes.</ol>
                                            <li><strong>Transporte ó custodia del vehículo reparado o recuperado en caso de encontrarse en una ciudad que no es de su residencia. </strong></ol>
                                            <li><strong>Servicio de conductor profesional: </strong>en caso de imposibilidad del conductor para conducir el vehículo por muerte, accidente o cualquier enfermedad grave.</ol>
                                        </ul>
                                        <p>
                                        Si desea obtener mayor información le invitamos a visitar y registrarse en nuestra página Web en www.kia.com.ec o comunicándose a 1800KIAKIA.
                                        </p>
                                        <p>Para KIA MOTORS ECUADOR es importante poner a su disposición productos de calidad para poder brindarle el mejor servicio.</p>
										
                                        <a href="https://www.kia.com.ec/intranet/usuario/index.php/site/hojaentregacliente?id_informacion=' . $$id_informacion . '&id_vehiculo=' . $id_vehiculo . '">Foto de Entrega</a>
                                        
                                        <p><strong>Atentamente</strong></p>
                                        <p><strong>KIA MOTORS ECUADOR</strong></p>
                                        
                                            <br /><br /><p>Nota de descargo: La información contenida en este e-mail es confidencial y sólo puede ser utilizada por el individuo o la compañía a la cual está dirigido. Esta información no debe ser distribuida ni copiada total o parcialmente por ningún medio sin la autorización de AEKIA S.A.
La organización no asume responsabilidad sobre información, opiniones o criterios contenidos en este mail que no esté relacionada con negocios oficiales de nuestra compañía.
</p>
                                        <img src="images/footer.png">
                                    </div>
                                </div>
                            </body>';        //die('table: '.$general);
        $codigohtml = $general;
        $headers = 'From: info@kia.com.ec' . "\r\n";
        $headers .= 'Content-type: text/html' . "\r\n";
        $email = $emailCliente; //email cliente
        //$email = 'alkanware@gmail.com'; //email cliente

        $ccarray = array('gansaldo72@hotmail.com');

        if (sendEmailInfoClienteConcesionario('info@kia.com.ec', "Kia Motors Ecuador", $email, $ccarray, html_entity_decode($asunto), $codigohtml)) {
            //die('send emaail carta');
        }
        //---- SEND EMAIL JEFE DE CONCESIONARIO Y ASESOR COMERCIAL
        $asunto = 'Kia Motors Ecuador SGC - Prueba de Manejo ID Cliente # ' . $id_informacion;
        $cri = new CDbCriteria(array(
            'condition' => "id={$id_informacion}"
        ));
        $art = GestionInformacion::model()->findAll($cri);

        $general = '<body style="margin: 10px;">
                                <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;">
                                    <div align="">
                                    <img src="images/header_mail.jpg">
                                    <p></p>
                                    <p style="margin: 2px 0;">Estimado/a Asesor/a ' . $nombre_asesor . '</p><br />
                                    <br>
                                    <p>' . $ciudadCliente . ', ' . $fecha_m . '</p><br /><br />   
                                <br/>
                                <p style="margin: 2px 0;">Se ha realizado la entrega del vehículo '.$this->getModeloTestDrive($id_vehiculo).'  a su cliente '.$nombre_cliente.', 
                                por favor es necesario que después de tres días laborables de la entrega del vehículo, usted debe realizar 
                                la llamada de seguimiento al cliente para conocer el nivel de satisfacción en las primeras horas de conducir su Kia.</p>
                                <p></p>
                                <p style="margin: 2px 0;">Saludos cordiales,</p>
                                <p style="margin: 2px 0;">SGC</p>
                                <p style="margin: 2px 0;">Kia Motors Ecuador</p>			
								<p>Nota de descargo: La información contenida en este e-mail es confidencial y sólo puede ser utilizada por el individuo o la compañía a la cual está dirigido. Esta información no debe ser distribuida ni copiada total o parcialmente por ningún medio sin la autorización de AEKIA S.A.<br>
La organización no asume responsabilidad sobre información, opiniones o criterios contenidos en este mail que no esté relacionada con negocios oficiales de nuestra compañía.</p>
                                <img src="images/footer.png">
                              </div>
                              </div>
                            </body>';
        //die('table: '.$general);
        $codigohtml = $general;
        $headers = 'From: info@kia.com.ec' . "\r\n";
        $headers .= 'Content-type: text/html' . "\r\n";
        $email = $this->getEmailJefeConcesion(70, $grupo_id); //email administrador
        $emailAsesor = $this->getAsesorEmail($id_asesor);
        sendEmailInfoTestDrive('info@kia.com.ec', "Kia Motors Ecuador", $email, $emailAsesor, html_entity_decode($asunto), $codigohtml);

        $this->render('create', array(
            'model' => $model, 'id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo
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

        if (isset($_POST['GestionSeguimiento'])) {
            $model->attributes = $_POST['GestionSeguimiento'];
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
        $dataProvider = new CActiveDataProvider('GestionSeguimiento');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new GestionSeguimiento('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['GestionSeguimiento']))
            $model->attributes = $_GET['GestionSeguimiento'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return GestionSeguimiento the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = GestionSeguimiento::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param GestionSeguimiento $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'gestion-seguimiento-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
