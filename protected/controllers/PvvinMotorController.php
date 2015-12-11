<?php

class PvvinMotorController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	 public $layout = '//layouts/call';

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
				'actions'=>array('create','update','eliminar','search','adjunto','admin'),
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

	public function actionAdjunto(){
		//llamadas de clases EXCEL
		$models = new UploadForm;
		date_default_timezone_set("America/Bogota");
		$p = new CHtmlPurifier();
		
		$phpExcelPath = Yii::getPathOfAlias('ext.reader');
       	include($phpExcelPath . "/" . 'php-excel-reader/excel_reader2.php');
       	include($phpExcelPath . "/" . 'SpreadsheetReader.php');
		
		//Archivo a leer
		$Filepath=Yii::app()->basePath . '/../upload/example.xls';
		
		$StartMem = memory_get_usage();
		$uploadedFile = CUploadedFile::getInstance($models, 'upload_file');
				
		if (!empty($uploadedFile) && $uploadedFile != '') {
			
			$error = 0;
			$rnd = rand(0, 9999);
			$date = date("Ymdhis");
			$extension = explode('.', $uploadedFile);
			
			switch (strtolower($extension[1])){
					   case 'xls': 
							$error = 0;
					   break;
					   case 'xlsx': 
							$error = 0;
							break;
					   default:
							Yii::app()->user->setFlash('error', "El archivo que usted ha enviado no es un EXCEL.");
							$this->redirect(array('pvvinMotor/adjunto/'));
							$error = 1;
							break;
			 }
			
			/*switch ($_FILES['userfile']['type']){
					   case 'application/vnd.ms-excel': 
							$error = 0;
					   break;
					   case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': 
							$error = 0;
							break;
					   default:
							Yii::app()->user->setFlash('error', "El archivo que usted ha enviado no es un EXCEL o no se ha guardado exitosamente.");
							$this->redirect(array('pvvinMotor/adjunto/'));
							$error = 1;
						break;
			 }*/
			 
			
			if($error == 0) {
				 
								$fileName = md5($rnd.$date).'.'.$extension[1];
						
				/*******SUBIR IMAGEN*************/
				
				//print_r($_FILES['userfile']['tmp_name']);
				//print_r(CUploadedFile::getInstance($_FILES));
				//die();
				if ($uploadedFile->saveAs(Yii::app()->basePath . '/../upload/vin/' . $fileName)){
					try
					{
						$errorSave = "";
						$uploadfile = Yii::app()->basePath . '/../upload/vin/' . $fileName;
						$Spreadsheet = new SpreadsheetReader($uploadfile);
						$BaseMem = memory_get_usage();
						$Sheets = $Spreadsheet -> Sheets();
						foreach ($Sheets as $Index => $Name)
						{
							$Time = microtime(true);
							$Spreadsheet -> ChangeSheet($Index);
							
							foreach ($Spreadsheet as $Key => $Row)
							{
								if ($Row)
								{
									$model = new VinMotor;
									$model->vin = $p->purify($Row[0]);
									$model->motor = $p->purify($Row[1]);
									if(!$model->save()){
										$errorSave++;
									}else
										$errorSave = 0;
									//echo $Row[0].' '.$Row[1].' '.$Row[2].'<br>';
								}
								else
								{
									var_dump($Row);
								}
								$CurrentMem = memory_get_usage();
							}
						}
						if($errorSave == 0){
							Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
							$this->redirect(array('pvvinMotor/adjunto'));
							die();
						}else{
							Yii::app()->user->setFlash('error', "Se han prducido ".$errorSave." error/es al cargar tu archivo");
							$this->redirect(array('pvvinMotor/adjunto/'));
						}
						
					}
					catch (Exception $E)
					{
						echo $E -> getMessage();
					}
				}else{
					Yii::app()->user->setFlash('error', "El archivo que usted ha enviado no se ha podido cargar.");
					$this->redirect(array('pvvinMotor/adjunto/'));
				}
			}
		}
		$this->render('adjunto',array('model'=>$models));
	}
	public function actionCreate()
	{
		$model=new VinMotor;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['VinMotor']))
		{
			$model->attributes=$_POST['VinMotor'];
			if($model->save()){
				Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
                $this->redirect(array('pvvinMotor/create'));
			}
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

		if(isset($_POST['VinMotor']))
		{
			$model->attributes=$_POST['VinMotor'];
			if($model->save()){
				Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
                $this->redirect(array('pvvinMotor/update/'.$model->id));
				//$this->redirect(array('admin','id'=>$model->id));
			}
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
	
	public function actionEliminar($id)
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
		$dataProvider=new CActiveDataProvider('VinMotor');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$concesionario = Yii::app()->user->getState('dealer_id');
        $rol = Yii::app()->user->getState('roles');
        if ($rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
            $criteria = new CDbCriteria;
            //$criteria->condition = "tipo_form='caso'";
            $criteria->order = 'id desc';
        else:
            $criteria = new CDbCriteria;
            //$criteria->condition = "concesionario={$concesionario}";
            $criteria->order = 'id desc';
        endif;


        // Count total records
        $pages = new CPagination(VinMotor::model()->count($criteria));

        // Set Page Limit
        $pages->pageSize = 10;

        // Apply page criteria to CDbCriteria
        $pages->applyLimit($criteria);

        // Grab the records
        $posts = VinMotor::model()->findAll($criteria);

        // Render the view
        $this->render('admin', array(
            'model' => $posts,
            'pages' => $pages,
            'busqueda' => '',
           )
        );
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return VinMotor the loaded model
	 * @throws CHttpException
	 */
	 public function actionSearch()
	{
		$p = new CHtmlPurifier();
		if(!empty($_GET['Modelos']['Descripcion'])){
			$patronBusqueda =  $p->purify($_GET['Modelos']['Descripcion']);
			
			$posts = VinMotor::model()->findAll(array('order' => 'id DESC', 'condition' => "vin LIKE :match OR motor LIKE :match", 'params' => array(':match' =>"%$patronBusqueda%")));
		   
		   if(!empty($posts)){
				$pages = new CPagination(count($posts));
				$pages->pageSize = 10;
	
				$this->render('admin', array(
					'model' => $posts,
					'pages' => $pages,
					'busqueda' => $patronBusqueda,
					)
				);
		   }else{
				Yii::app()->user->setFlash('error', "No se encontraron datos con la busqueda realizada.");
				 $this->redirect(array('pvvinMotor/admin/'));
		   }
		}else{
				Yii::app()->user->setFlash('error', "Ingrese un valor para realizar la busqueda.");
				 $this->redirect(array('pvvinMotor/admin/'));
		   }
	}
	public function loadModel($id)
	{
		$model=VinMotor::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param VinMotor $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='vin-motor-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
