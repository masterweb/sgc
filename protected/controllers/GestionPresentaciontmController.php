<?php

class GestionPresentaciontmController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
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
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'create'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($id_informacion = null)
	{
		$model=new GestionPresentaciontm;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['GestionPresentaciontm']))
		{
	//		echo '<pre>';
	//		print_r($_FILES);
	//		echo '</pre>';
	//		die();
			$model->attributes=$_POST['GestionPresentaciontm'];
			date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
			$archivoThumb = CUploadedFile::getInstance($model, 'img');
            $fileName = "{$archivoThumb}";  // file name
            if ($archivoThumb != "") {
                    //die('enter file');
                $model->img = $fileName;
                $archivoThumb->saveAs(Yii::getPathOfAlias("webroot") . "/images/uploads/" . $fileName);
                $link = Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName;
                $image = new EasyImage($link);
                $image->resize(600, 480); // resize images for thumbs
                $image->save(Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName);                    
            }
			if($model->save()){
				switch ($_POST['GestionPresentaciontm']['presentacion']) {
					case '0': // cliente no se presenta al concesionario y cliente regresa a Asesor TM Web
						$gi = GestionInformacion::model()->find(array('condition' => "id = {$_POST['GestionPresentaciontm']['id_informacion']}"));
                        $gi->responsable = $gi->responsable_origen_tm;
                        $gi->responsable_origen_tm = 0;
                        $gi->reasignado_tm = 2; // ESTADO DEVUELTO
                        $gi->reasignado = 0;
                        $gi->update();
                        // QUITAR CITA DE ASESOR TW, PONER TODAS LAS CITAS EN 2 Y CAMPO tw EN 0 - SE QUITA LA CITA PROGRAMADA
                        $gc = GestionCita::model()->findAll(array('condition' => "id_informacion={$_POST['GestionPresentaciontm']['id_informacion']}"));
                        $con = Yii::app()->db;
                        if($con){
                        	foreach ($gc as $key){
	                        	$sql = "UPDATE gestion_cita SET `order` = 2, tw = 0 WHERE id = {$key['id']}";
	                        	$request = $con->createCommand($sql)->execute();
	                        }
                        }

                         $this -> sendReturnNotification($gi->responsable, Yii::app()->user->getId(),
                        $_POST['GestionPresentaciontm']['id_informacion']);
                        die();
						break;
					case '1':// cliente se presenta y sigue el proceso normal
						# code...
						break;	
					
					default:
						# code...
						break;
				}
			}
				$this->redirect(array('gestionInformacion/seguimiento'));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['GestionPresentaciontm']))
		{
			$model->attributes=$_POST['GestionPresentaciontm'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('GestionPresentaciontm');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new GestionPresentaciontm('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['GestionPresentaciontm']))
			$model->attributes=$_GET['GestionPresentaciontm'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return GestionPresentaciontm the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=GestionPresentaciontm::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param GestionPresentaciontm $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='gestion-presentaciontm-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function sendReturnNotification($jefeAgencia, $telemercadista,$id_informacion){
       
        


        $emailJefeAgencia = $this->getAsesorEmail($jefeAgencia);
        $emailTelemercadista = $this->getAsesorEmail($telemercadista);

        $nameJefeAgencia = $this->getAsesorName($jefeAgencia);
        $nameTelemercadista = $this->getAsesorName($telemercadista);

        $nombreCliente = $this->getNombreCliente($id_informacion);
        $nombreConcecionario = $this->getConcesionario($this->getAsesorDealersId($jefeAgencia));
        
       

            require_once 'email/mail_func.php';

              $body=   '<style>
                            body {margin: 0; padding: 0; min-width: 100%!important;}
                        </style>
                    </head>

                    <body>
                        <table cellpadding="0" cellspacing="0" width="650" align="center" border="0">
                            <tr>
                                <td align="center"><a href="https://www.kia.com.ec" target="_blank"><img src="images/mailing/mail_factura_03.jpg" width="569" height="60" alt="" style="display:block; border:none;"/></a></td>
                            </tr>


                            <tr>
                                <td style="font-family:Arial, sans-serif; font-size:16px; color:#5e5e5e; text-align:center; padding-top:15px; padding-bottom:15px;">Estimado/a <strong>'.$nameJefeAgencia.'</strong>,<br/>
                                    </td>
                             </tr>

                             <tr>
                                <td style="font-family:Arial, sans-serif; font-size:16px; color:#5e5e5e; text-align:center; padding-top:15px; padding-bottom:15px;">Del concesionario <strong>'.$nombreConcecionario.'.</strong> se acaba de notificar que no se concretó o canceló la cita del cliente <strong>'.$nombreCliente.'</strong>.<br/>
                                    </td>
                             </tr>
							
							<tr>
                                            <td style="font-family:Arial, sans-serif; font-size:16px; color:#5e5e5e; text-align:center; padding-top:15px; padding-bottom:15px;">
                                            Por favor realizar nuevamente la gestión del cliente para verificar la razón por la cuál no se acercó al concesionario.</td>
                                             
                            </tr> 

                             <tr>
                                            <td style="font-family:Arial, sans-serif; font-size:16px; color:#5e5e5e; text-align:center; padding-top:15px; padding-bottom:15px;">
                                            Es importante generar nuevos seguimientos al cliente hasta generar una nueva visita.</td>
                                             
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
                   
                  
                $emailCliente = 'dandee_ds@hotmail.com';//$this->getAsesorEmail($jefeAgencia);
                $id_asesor = Yii::app()->user->getId();
               $emailAsesor = $this->getAsesorEmail($id_asesor);
               $asunto = 'CITA N O CONFIRMADA';           
                sendEmailInfoTestDrive('servicioalcliente@kiamail.com.ec', "Kia Motors Ecuador", $emailCliente, $emailAsesor, html_entity_decode($asunto), $body);
    }
}
