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
        $dealer_id = $this->getDealerId($id_asesor);
        $nombre_asesor = $this->getResponsableNombres($id_asesor);
        $cargo_id = (int) Yii::app()->user->getState('cargo_id');
        $grupo_id = (int) Yii::app()->user->getState('grupo_id');
        $nombre_cliente = $this->getNombresInfo($id_informacion) . ' ' . $this->getApellidosInfo($id_informacion);
        $modelo = $this->getModeloInfo($id_vehiculo);
        $ciudadCliente = $this->getCiudadConcesionario($id_informacion);
        $ciudad = $this->getCiudad($id_asesor);
        $foto_entrega = $this->getFotoEntregaDetail($id_informacion);
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
        if ($modelo == 86 || $modelo == 93) {
            $general .= ' garantía de 7 años o 120.000 Km, ';
        } else {
            $general .=' garantía de 7 años o 150.000 Km, ';
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
										
                                        <a href="https://www.kia.com.ec/'. Yii::app()->request->baseUrl.'/images/uploads/'.$foto_entrega.'">Foto de Entrega</a>
                                        
                                        <p><strong>Atentamente</strong></p>
                                        <p><strong>KIA MOTORS ECUADOR</strong></p>
                                        
                                            <br /><br /><p>Nota de descargo: La información contenida en este e-mail es confidencial y sólo puede ser utilizada por el individuo o la compañía a la cual está dirigido. Esta información no debe ser distribuida ni copiada total o parcialmente por ningún medio sin la autorización de AEKIA S.A.
La organización no asume responsabilidad sobre información, opiniones o criterios contenidos en este mail que no esté relacionada con negocios oficiales de nuestra compañía.
</p>
                                        <img src="images/footer.png">
                                    </div>
                                </div>
                            </body>';        //die('table: '.$general);
        $html = '<html><head><!--[if mso]>
 
<style>
    span, td, table, div {
      font-family: Arial, serif !important;font-size: 12px; color: #5e5e5e;
    }
</style>
 
<![endif]--></head>';
        $html .= '<body style="width: 650px; margin: auto; font-family: Arial, "sans-serif !important"; font-size: 12px; color: #5e5e5e; padding-left: 30px;" width="650px;">
	<div class="cont-mail" style="width: 650px; margin: auto;">
		<div class="col-xs-12"><img src="https://www.kia.com.ec'.Yii::app()->request->baseUrl.'/images/mail/mail_01.jpg" width="173" height="69" alt=""/></div>
		<div class="col-xs-12 txt-mail" style="font-family: Arial, "sans-serif"; font-size: 12px; color: #5e5e5e; padding-left: 30px">'.$ciudadCliente.', '.$fecha_m.'<br>
Señor(a)<br>
'.$nombre_cliente.'</div><br />
		<table cellpadding="0" cellspacing="0" style="display: block; border: none;" width="650">
                    <tr>
                        <td>KIA MOTORS ECUADOR le da la bienvenida, agradecemos la confianza al haber escogido uno de nuestros vehículos KIA, con la mejor tecnología Coreana.</tr>
                    </td>
                </table>
<br />		
<div class="row">
		
                <table cellpadding="0" cellspacing="0" style="display: block; border: none;" width="650">
                        <tr>
                            <td style="padding-right:8px;"><p>Nuestra prioridad es servirle de la mejor manera, por lo que usted tiene a su disposición la nueva línea gratuita de Servicio al Cliente 1800 KIA KIA (1800 542 542), donde Usted podrá obtener información de Vehículos Nuevos, Seminuevos, Talleres de Servicio Autorizado Kia, Costos de Mantenimiento Preventivos, Repuestos y Accesorios, Políticas de Garantías de su Vehículo, etc. 
                                </p>
                            <p>Nuestro personal de la línea 1800 KIAKIA podrá ayudarle también a realizar su próxima cita de mantenimiento para que Ud. pueda continuar disfrutando de su vehículo Kia en todo momento, basta con llamar y uno de nuestros asesores podrá brindarle el mejor servicio para su próxima cita.</p>
                            </td>
                          <td><a href="https://www.kia.com.ec/concesionarios.html"><img src="https://www.kia.com.ec'.Yii::app()->request->baseUrl.'/images/mail/mail_05.jpg" width="265" height="208" alt=""/></a></td>
                        </tr>
                </table>
                <br />
		<table cellpadding="0" cellspacing="0" class="col-xs-12 txt-mail" style="display: block; border: none; font-family: Arial, "sans-serif"; font-size: 12px; color: #5e5e5e; padding-left: 30px" width="650">
                    <tr>
                    <td>Para complementar nuestro servicio, le recordamos que tenemos a su disposición un producto denominado “Asistencia KIA” el cual le asiste ante ante cualquier desperfecto mecánico o emergencia, las 24 horas del día, los 365 días del año, por un costo de $25 + IVA.
                    </td>
                    </tr>
                </table>
                <br>
                <br />
                La cobertura del producto “Asistencia KIA” comprende de:</div><br />
		<table cellpadding="0" cellspacing="0" style="display: block; border: none;" width="650">
                    <tr>
                        <td width="62"><img src="https://www.kia.com.ec'.Yii::app()->request->baseUrl.'/images/mail/mail_09.jpg" width="41" height="28" alt="" style="margin:5px 10px 5px 10px;"/></td>
			<td width="586" style="font-family: Arial, "sans-serif"; font-size: 12px; color: #5e5e5e; padding-left: 30px"><strong>Remolque o traslado</strong> de su vehículo en caso de avería o accidente hasta el concesionario Kia más cercano.</td>
                    </tr>
		</table>
		<table cellpadding="0" cellspacing="0" style="display: block; border: none;" width="650">
                    <tr>
			<td width="62"><img src="https://www.kia.com.ec'.Yii::app()->request->baseUrl.'/images/mail/mail_12.jpg" width="41" height="28" alt="" style="margin:5px 10px 5px 10px;"/></td>
			<td width="586" style="font-family: Arial, "sans-serif"; font-size: 12px; color: #5e5e5e; padding-left: 30px"><strong>Auxilio Mecánico:</strong> asistencia en caso llanta baja, falta de combustible, carga de batería.</td>
                    </tr>
		</table>
		<table cellpadding="0" cellspacing="0" style="display: block; border: none;" width="650">
                    <tr>
			<td width="62"><img src="https://www.kia.com.ec'.Yii::app()->request->baseUrl.'/images/mail/mail_14.jpg" width="41" height="28" alt="" style="margin:5px 10px 5px 10px;"/></td>
			<td width="586" style="font-family: Arial, "sans-serif"; font-size: 12px; color: #5e5e5e; padding-left: 30px"><strong>Traslado en ambulancia:</strong> en caso accidente de tránsito accidente de tránsito.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    </tr>
		</table>
		<table cellpadding="0" cellspacing="0" style="display: block; border: none;" width="650">
			<tr><td width="62"><img src="https://www.kia.com.ec'.Yii::app()->request->baseUrl.'/images/mail/mail_16.jpg" width="41" height="28" alt="" style="margin:5px 10px 5px 10px;"/></td>
			<td width="586" style="font-family: Arial, "sans-serif"; font-size: 12px; color: #5e5e5e; padding-left: 30px"><strong>Cobertura en caso de viaje:</strong> en caso de que su vehículo quede inmovilizado, sufra hurto simple o calificado se cubrirá la estancia y desplazamiento de los ocupantes.</td></tr>
		</table>
		<table cellpadding="0" cellspacing="0" style="display: block; border: none;" width="650">
			<tr><td width="62"><img src="https://www.kia.com.ec'.Yii::app()->request->baseUrl.'/images/mail/mail_18.jpg" width="41" height="28" alt="" style="margin:5px 10px 5px 10px;"/></td>
			<td width="586" class="col-xs-11 txt-mail" style="font-family: Arial, "sans-serif"; font-size: 12px; color: #5e5e5e; padding-left: 30px"><strong>Transporte o custodia</strong> del vehículo reparado o recuperado en caso de encontrarse en una ciudad que no es de su residencia.</td></tr>
		</table>
		<table cellpadding="0" cellspacing="0" style="display: block; border: none;" width="650">
			<tr><td width="62"><img src="https://www.kia.com.ec'.Yii::app()->request->baseUrl.'/images/mail/mail_20.jpg" width="41" height="28" alt="" style="margin:5px 10px 5px 10px;"/></td>
			<td width="586" style="font-family: Arial, "sans-serif"; font-size: 12px; color: #5e5e5e; padding-left: 30px"><strong>Servicio de conductor profesional:</strong> en caso de imposibilidad del conductor para conducir el vehículo por muerte, accidente o cualquier enfermedad grave.</td></tr>
		</table>
		<table cellpadding="0" cellspacing="0" style="display: block; border: none;" width="650">
			<tr><td width="62"><img src="https://www.kia.com.ec'.Yii::app()->request->baseUrl.'/images/mail/mail_22.jpg" width="41" height="28" alt="" style="margin:5px 10px 5px 10px;"/></td>
			<td width="586" style="font-family: Arial, "sans-serif"; font-size: 12px; color: #5e5e5e; padding-left: 30px"><strong>Asistencia Legal telefónica:</strong> Asesoría gratuita vía conferencia con nuestros abogados, quienes están en la capacidad legal de guiarlo en caso de tener un accidente de tránsito o infracciones en la vía.</td></tr>
		</table>
                <br />
		<div class="col-xs-12">
			<table cellpadding="0" cellspacing="0" style="display: block; border: none;">
				<tr>
					<td><img src="https://www.kia.com.ec'.Yii::app()->request->baseUrl.'/images/mail/mail_24.jpg" width="29" height="170" alt="" style="display: block; border: none;"/></td>
					<td><img src="https://www.kia.com.ec'.Yii::app()->request->baseUrl.'/images/mail/mail_25.jpg" width="190" height="170" alt="" style="display: block; border: none;"/></td>
					<td><img src="https://www.kia.com.ec'.Yii::app()->request->baseUrl.'/images/mail/mail_26.jpg" width="15" height="170" alt="" style="display: block; border: none;"/></td>
					<td><a href="https://www.kia.com.ec/usuarios/registro.html"><img src="https://www.kia.com.ec'.Yii::app()->request->baseUrl.'/images/mail/mail_27.jpg" width="189" height="170" alt="" style="display: block; border: none;"/></a></td>
					<td><img src="https://www.kia.com.ec'.Yii::app()->request->baseUrl.'/images/mail/mail_28.jpg" width="14" height="170" alt="" style="display: block; border: none;"/></td>
					<td><a href="https://www.facebook.com/KiaMotorsEcuador/"><img src="https://www.kia.com.ec'.Yii::app()->request->baseUrl.'/images/mail/mail_29.jpg" width="189" height="170" alt="" style="display: block; border: none;"/></a></td>
					<td><img src="https://www.kia.com.ec'.Yii::app()->request->baseUrl.'/images/mail/mail_30.jpg" width="24" height="170" alt="" style="display: block; border: none;"/></td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0" style="display: block; border: none;">
				<tr>
					<td><img src="https://www.kia.com.ec'.Yii::app()->request->baseUrl.'/images/mail/mail_31.jpg" width="650" height="63" alt="" style="display: block; border: none;"/></td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0" style="display: block; border: none;">
                            <tr>
                                    <td><img src="https://www.kia.com.ec'.Yii::app()->request->baseUrl.'/images/mail/mail_32.jpg" width="30" height="30" alt="" style="display: block; border: none;"/></td>
                                    <td><a href="https://www.kia.com.ec/'. Yii::app()->request->baseUrl.'/images/uploads/'.$foto_entrega.'"><img src="https://www.kia.com.ec'.Yii::app()->request->baseUrl.'/images/mail/mail_33.jpg" width="150" height="30" alt="" style="display: block; border: none;"/></a></td>
                                    <td><img src="https://www.kia.com.ec'.Yii::app()->request->baseUrl.'/images/mail/mail_34.jpg" width="470" height="30" alt="" style="display: block; border: none;"/></td>
                            </tr>
			</table>
			<table cellpadding="0" cellspacing="0" class="col-xs-12 txt-mail" style="background-color: #eeedec; padding-top: 15px; padding-left: 30px;" width="650"><tr><td>Atentamente<br>
                        KIA MOTORS ECUADOR</tr></td></table>
			<table cellpadding="0" cellspacing="0" style="display: block; border: none;">
				<tr>
					<td><img src="https://www.kia.com.ec'.Yii::app()->request->baseUrl.'/images/mail/mail_36a.jpg" width="650" height="87" alt="" style="display: block; border: none;"/></td>
				</tr>
			</table>
		</div>
	</div>
</body></html>';
        $codigohtml = $html;
        $headers = 'From: servicioalcliente@kiamail.com.ec' . "\r\n";
        $headers .= 'Content-type: text/html' . "\r\n";
        $email = $emailCliente; //email cliente
        $email = 'alkanware@gmail.com'; //email cliente

        $ccarray = array('gansaldo72@hotmail.com');
        //die('img: https://www.kia.com.ec'.Yii::app()->request->baseUrl.'/images/mail/mail_36.jpg');

        if (sendEmailInfoClienteConcesionario('servicioalcliente@kiamail.com.ec', "Kia Motors Ecuador", $email, $ccarray, html_entity_decode($asunto), $codigohtml)) {
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
        $headers = 'From: servicioalcliente@kiamail.com.ec' . "\r\n";
        $headers .= 'Content-type: text/html' . "\r\n";
        $email = $this->getEmailJefeConcesion(70, $grupo_id, $dealer_id); //email administrador
        $emailAsesor = $this->getAsesorEmail($id_asesor);
        sendEmailInfoTestDrive('servicioalcliente@kiamail.com.ec', "Kia Motors Ecuador", $email, $emailAsesor, html_entity_decode($asunto), $codigohtml);
        
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
