<?php

class GestionTestDriveController extends Controller {

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
    public function actionCreate($id_informacion = NULL, $id = NULL) {
        $model = new GestionTestDrive;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);


        if (isset($_POST['GestionTestDrive'])) {
//            echo '<pre>';
//            print_r($_POST);
//            echo '</pre>';
//            die();
            $id_informacion = $_POST['GestionTestDrive']['id_informacion'];
            $id_vehiculo = $_POST['GestionTestDrive']['id_vehiculo'];
            $criteria = new CDbCriteria(array(
                'condition' => "id_informacion={$id_informacion} AND id_vehiculo = {$id_vehiculo}"
            ));
            $td = GestionTestDrive::model()->count($criteria);
            //die('count td:'.$td);
            if($td > 0){
                $model->order = 2;
            }
            $model->attributes = $_POST['GestionTestDrive'];
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");


            if ($_POST['GestionTestDrive']['preg1'] == 'Si') { // If make a Test Drive uploads a image
                $model->test_drive = 1;
                $model->setscenario('img');
                $model->observacion = $_POST['GestionTestDrive']['observaciones_form'];
                // subir imagen principal del test drive al servidor
                $archivoThumb = CUploadedFile::getInstance($model, 'img');
                $fileName = "{$archivoThumb}";  // file name
                if ($archivoThumb != "") {
                    //die('enter file');
                    $model->img = $fileName;
                    if ($model->save()) {
                        $archivoThumb->saveAs(Yii::getPathOfAlias("webroot") . "/images/uploads/" . $fileName);
                        $link = Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName;
                        $image = new EasyImage($link);
                        $image->resize(600, 480); // resize images for thumbs
                        $image->save(Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName);
                    }
                }
                $archivoFirma = CUploadedFile::getInstance($model, 'firma');
                $fileNameFirma = "{$archivoFirma}";  // file name
                //$fecha = (string) date("Y-m-d");
                //$paramFirma = explode('.', $fileNameFirma);
                ///$nameFirma = $paramFirma[0] . '-6-' . $fecha;
                //$nameFinal = $nameFirma . "." . $paramFirma[1];
                //die('archivo firma: '.$nameFinal);
                if ($fileNameFirma != "") {
                    //die('enter file');
                    $model->firma = $nameFinal;
                    if ($model->save()) {
                        $archivoFirma->saveAs(Yii::getPathOfAlias("webroot") . "/images/uploads/" . $nameFinal);
                        $link = Yii::getPathOfAlias('webroot') . '/images/uploads/' . $nameFinal;
                        $image = new EasyImage($link);
                        $image->resize(600, 480); // resize images for thumbs
                        $image->save(Yii::getPathOfAlias('webroot') . '/images/uploads/' . $nameFinal);
                    }
                }
                $con = Yii::app()->db;
                $sql = "UPDATE gestion_diaria SET primera_visita = 1, paso = '6', status = 1 WHERE id_informacion = {$id_informacion}";
                $request = $con->createCommand($sql)->query();

                $demostracion = new GestionDemostracion;
                $demostracion->preg1 = $_POST['GestionTestDrive']['preg1'];
                $demostracion->preg1_licencia = $fileName;
                $demostracion->fecha = date("Y-m-d H:i:s");
                $demostracion->id_informacion = $_POST['GestionTestDrive']['id_informacion'];
                $demostracion->id_vehiculo = $_POST['GestionTestDrive']['id_vehiculo'];
                $demostracion->save();
                $emailCliente = $this->getEmailCliente($_POST['GestionTestDrive']['id_informacion']);
                $id_asesor = Yii::app()->user->getId();
                $cargo_id = (int) Yii::app()->user->getState('cargo_id');
                $grupo_id = (int) Yii::app()->user->getState('grupo_id');
                $nombre_cliente = $this->getNombresInfo($_POST['GestionTestDrive']['id_informacion']) . ' ' . $this->getApellidosInfo($_POST['GestionTestDrive']['id_informacion']);
                //---- SEND THANK YOU EMAIL TO CLIENT
                require_once 'email/mail_func.php';
                $asunto = 'Kia Motors Ecuador - Gracias por realizar una Prueba de Manejo con Nosotros.';
                $general = '<body style="margin: 10px;">
                                <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;">
                                    <div align="">
                                    <img src="images/header_mail.jpg"><br>
                                        <p style="margin: 2px 0;">Señor(a): ' . $nombre_cliente . '</p>
                                        <p></p> <br />   

                                        <p style="margin: 2px 0;">Reciba un cordial saludo de Kia Motors Ecuador.</p><br /> 
                                        <p style="margin: 2px 0;">Este email es una notificación electrónica de que su prueba de manejo se ha efectuado con éxito.</p>
                                        <p></p> <br />   
                                        <p style="margin: 2px 0;">A continuación le presentamos el detalle:</p><br /><br />
                                        
                                        <table width="600">
                                        <tr><td><strong>Asesor Comercial:</strong></td><td>' . $this->getResponsable($id_asesor) . '</td></tr>
                                        <tr><td><strong>Concesionario:</strong></td><td>' . $this->getNameConcesionario($id_asesor) . '</td></tr> 
                                        <tr><td><strong>Modelo:</strong></td><td>' . $this->getModeloTestDrive($_POST['GestionTestDrive']['id_vehiculo']) . '</td></tr>
                                        <tr><td><strong>Fecha:</strong></td><td>' . date("d") . "/" . date("m") . "/" . date("Y") . '</td></tr>
                                        <tr><td><strong>Hora:</strong></td><td>' . date("H:i:s") . '</td></tr>
                                        </table>
                                        <br/><br />
                                        <p style="margin: 2px 0;">En las próximas horas nuestro Call Center se contactará con Ud. para conocer su experiencia.</p>
                                        <p></p>
                                        <p style="margin: 2px 0;">De antemano agradecemos por su tiempo,</p><br />
                                        <p>Kia Motors Ecuador</p><br /><br />

                                        <table width="600"  cellpadding="0" cellspacing="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                                            <tr>
                                              <td width="228">&nbsp;</td>
                                              <td width="228" style="color:#1f497d">' . $this->getResponsable($id_asesor) . ' - Asesor Ventas Kia</td>
                                            </tr>
                                            <tr>
                                              <td width="178" rowspan="5"><img src="images/logo_pdf2.png" /></td>
                                              <td colspan="2"><strong style="color: #AB1F2C; font-size: 16px;">' . strtoupper($this->getNameConcesionario($id_asesor)) . '</strong></td>
                                            </tr>
                                            
                                            <tr>
                                              <td colspan="2">' . $this->getConcesionarioDireccion($id_asesor) . '</td>
                                            </tr>
                                            <tr>
                                              <td><strong style="color:#AB1F2C;">T</strong> (593) ' . $this->getAsesorTelefono($id_asesor) . ' ext. ' . $this->getAsesorExtension($id_asesor) . '</td>
                                              <td><strong style="color:#AB1F2C;">M</strong> (593 9) ' . $this->getAsesorCelular($id_asesor) . '</td>
                                            </tr>
                                            <tr>
                                              <td><strong style="color:#AB1F2C;">E</strong> ' . $this->getAsesorEmail($id_asesor) . ' </td>
                                              <td><strong style="color:#AB1F2C;">W</strong> www.kia.com.ec </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <br /><br />
                                    <p>Nota de descargo: La información contenida en este e-mail es confidencial y sólo puede ser utilizada por el individuo o la compañía a la cual está dirigido. Esta información no debe ser distribuida ni copiada total o parcialmente por ningún medio sin la autorización de AEKIA S.A.
La organización no asume responsabilidad sobre información, opiniones o criterios contenidos en este mail que no esté relacionada con negocios oficiales de nuestra compañía.
</p>
                                </div>
                            </body>';
                //die('table: '.$general);
                $codigohtml = $general;
                $headers = 'From: info@kia.com.ec' . "\r\n";
                $headers .= 'Content-type: text/html' . "\r\n";
                //$email = $emailCliente; //email cliente registrado
                $email = 'alkanware@gmail.com'; //email administrador

                $send = sendEmailInfo('info@kia.com.ec', "Kia Motors Ecuador", $email, html_entity_decode($asunto), $codigohtml);
                //die('send: '.$send);
                //---- SEND EMAIL JEFE DE CONCESIONARIO Y ASESOR COMERCIAL
                $asunto = 'Kia Motors Ecuador SGC - Prueba de Manejo ID Cliente # ' . $_POST['GestionTestDrive']['id_informacion'];
                $cri = new CDbCriteria(array(
                    'condition' => "id={$_POST['GestionTestDrive']['id_informacion']}"
                ));
                $art = GestionInformacion::model()->findAll($cri);

                $general = '<body style="margin: 10px;">
                                <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;">
                                    <div align="">
                                    <img src="images/header_mail.jpg">
                                    <p></p>
                                    <p style="margin: 2px 0;">Estimado/a Jefe de Sucursal</p><br />
                                    <p style="margin: 2px 0;">Se ha realizado una prueba de manejo con el siguiente detalle:</p>

                                    <br>
                                        <table width="600" cellpadding="">';
                foreach ($art as $vl) {
                    $general .= '<tr><td><strong>Cliente:</strong></td><td> ' . $nombre_cliente . '</td></tr> '
                            . '<tr><td><strong>Asesor Comercial:</strong></td><td> ' . $this->getResponsable($id_asesor) . '</td></tr>
                                <tr><td><strong>Concesionario:</strong></td><td>' . $this->getNameConcesionario($id_asesor) . '</td></tr> 
                                <tr><td><strong>Modelo:</strong></td><td> ' . $this->getModeloTestDrive($_POST['GestionTestDrive']['id_vehiculo']) . '</td></tr>
                                <tr><td><strong>Fecha:</strong></td><td> ' . date("d") . "/" . date("m") . "/" . date("Y") . '</td></tr>
                               <tr><td><strong>Hora:</strong></td><td>' . date("H:i:s") . '</td></tr>'; 
                }
                $general .= ' </table>
                                <br/>
                                <p style="margin: 2px 0;">Por favor realizar llamada de presentaci&oacute;n seguimiento.</p>
                                <p></p>
                                <p style="margin: 2px 0;">Saludos cordiales,</p>
                                <p style="margin: 2px 0;">SGC</p>
                                <p style="margin: 2px 0;">Kia Motors Ecuador</p>
					<p>Saludos Cordales.<br>
					SCG<br>
					Kia Motors Ecuador</p>			
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
                //die('after send email admin');
                // ENVIAR TEST DRIVE AL CALL CENTER PARA ENCUESTA
                $cr = new CDbCriteria(array(
                    "condition" => "id = {$id_informacion}",
                ));
                $info = GestionInformacion::model()->find($cr);

                $casos = new Casos;
                $casos->tema = 6; // sugerencias
                $casos->subtema = 20; // quejas
                $casos->nombres = $this->getNombresInfo($id_informacion);
                $casos->apellidos = $this->getApellidosInfo($id_informacion);
                $casos->identificacion = 'ci';
                $casos->cedula = $info->cedula;
                $casos->ciudad = $info->ciudad_domicilio;
                $casos->concesionario = $info->dealer_id;
                $casos->direccion = $info->direccion;
                $casos->provincia_domicilio = $info->provincia_domicilio;
                $casos->ciudad_domicilio = $info->ciudad_domicilio;
                $casos->sector = $info->direccion;
                $casos->telefono = $info->telefono_casa;
                $casos->celular = $info->celular;
                $casos->email = $info->email;
                $casos->comentario = $_POST['GestionTestDrive']['observaciones_form'];
                $casos->estado = 'Abierto';
                $casos->tipo_form = 'caso';
                $casos->responsable = 32;
                $casos->origen = 2;
                $casos->fecha = date("Y-m-d H:i:s");
                $casos->provincia = $info->provincia_domicilio;
                $casos->save();
                //die('casos save');
            } else if ($_POST['GestionTestDrive']['preg1'] == 'No') { // If not make Test Drive
                //$model->setscenario('observacion');
                $model->test_drive = 0;
                $model->observacion = $_POST['GestionTestDrive']['observacion'];
                $demostracion = new GestionDemostracion;
                $demostracion->preg1 = $_POST['GestionTestDrive']['preg1'];
                $demostracion->preg1_observaciones = $_POST['GestionTestDrive']['preg1_observaciones'];
                $demostracion->preg1_agendamiento = $_POST['GestionTestDrive']['preg1_agendamiento'];
                $demostracion->id_informacion = $_POST['GestionTestDrive']['id_informacion'];
                $demostracion->save();
            }

            if ($model->save())
                $this->redirect(array('site/demostracion/' . $id_informacion));
        }

        $this->render('create', array(
            'model' => $model, 'id_informacion' => $id_informacion, 'id' => $id
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

        if (isset($_POST['GestionTestDrive'])) {
            $model->attributes = $_POST['GestionTestDrive'];
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
        $dataProvider = new CActiveDataProvider('GestionTestDrive');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new GestionTestDrive('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['GestionTestDrive']))
            $model->attributes = $_GET['GestionTestDrive'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return GestionTestDrive the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = GestionTestDrive::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param GestionTestDrive $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'gestion-test-drive-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
