<?php

class PvboletinpostventaController extends Controller
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
				'actions'=>array(),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','search','eliminar','admin','adjuntar','adminAdjunto','eliminarAdjunto','updateAdjunto','verAdjunto','searchAdjunto'),
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
	public function actionAdminAdjunto($id){
		//$model=new Boletinpostventa('search');
		$concesionario = Yii::app()->user->getState('dealer_id');
        $rol = Yii::app()->user->getState('roles');
        if ($rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
            $criteria = new CDbCriteria;
            $criteria->condition = "boletinpostventa_id=".(int)$id;
            $criteria->order = 'id desc';
        else:
            $criteria = new CDbCriteria;
            //$criteria->condition = "concesionario={$concesionario}";
			$criteria->condition = "boletinpostventa_id=".(int)$id;
            $criteria->order = 'id desc';
        endif;


        // Count total records
        $pages = new CPagination(AdjuntoBoletin::model()->count($criteria));

        // Set Page Limit
        $pages->pageSize = 10;

        // Apply page criteria to CDbCriteria
        $pages->applyLimit($criteria);

        // Grab the records
        $posts = AdjuntoBoletin::model()->findAll($criteria);

        // Render the view
        $this->render('adminAdjunto', array(
            'model' => $posts,
            'pages' => $pages,
            'busqueda' => '',
			'boletin'=>$this->loadModel($id),
           )
        );
	}
	public function actionSearchAdjunto($id)
	{
		$p = new CHtmlPurifier();
		if(!empty($_GET['Modelos']['Descripcion'])){
			$patronBusqueda =  $p->purify($_GET['Modelos']['Descripcion']);
			
			$posts = AdjuntoBoletin::model()->findAll(array('order' => 'id DESC', 'condition' => "boletinpostventa_id=:id AND detalle LIKE :match ", 'params' => array(':id'=>(int)$id,':match' =>"%$patronBusqueda%")));
		   
		   if(!empty($posts)){
				$pages = new CPagination(count($posts));
				$pages->pageSize = 10;
	
				$this->render('adminAdjunto', array(
					'model' => $posts,
					'pages' => $pages,
					'busqueda' => $patronBusqueda,
					'boletin'=>$this->loadModel($id),
					)
				);
		   }else{
				Yii::app()->user->setFlash('error', "No se encontraron datos con la busqueda realizada.");
				 $this->redirect(array('pvcodigoCausal/admin/'));
		   }
		}else{
				Yii::app()->user->setFlash('error', "Ingrese un valor para realizar la busqueda.");
				 $this->redirect(array('pvcodigoCausal/admin/'));
		   }
	}
	function sanear_string($string)
{

    $string = trim($string);

    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );

    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );

    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );

    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );

    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );

    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
    );

    //Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(
        array("\\", "¨", "º", "-", "~",
             "#", "@", "|", "!", "\"",
             "·", "$", "%", "&", "/",
             "(", ")", "?", "'", "¡",
             "¿", "[", "^", "`", "]",
             "+", "}", "{", "¨", "´",
             ">", "< ", ";", ",", ":",
             ".", " "),
        '',
        $string
    );


    return $string;
}
	public function actionAdjuntar($id){
		//llamadas de clases EXCEL
		date_default_timezone_set("America/Bogota");
		$p = new CHtmlPurifier();
		$boletin = Boletinpostventa::model()->find(array('order' => 'id DESC', 'condition' => "id=:id", 'params' => array(':id' =>(int)$id)));
		  
		$model=new AdjuntoBoletin;
		$error = 0;
		if(isset($_POST['AdjuntoBoletin']))
		{
			$model->attributes=$_POST['AdjuntoBoletin'];
			$uploadedFile = CUploadedFile::getInstance($model, 'nombre');
			if(!empty($uploadedFile)){
				$rnd = rand(0, 9999);
				
				$date = date("Ymdhis");
				$extension = explode('.', $uploadedFile);
				$nuevo_nombre = str_replace(' ', '-', $_POST['AdjuntoBoletin']['detalle']);
				//echo utf8_decode($_POST['AdjuntoBoletin']['detalle']);
				$nuevo_nombre = $this->sanear_string(utf8_decode($_POST['AdjuntoBoletin']['detalle']));
				
				//$fileName = md5($rnd.$date).'.'.$extension[1];
				//$fileName = $extension[0].'.'.$extension[1];
				$fileName = $nuevo_nombre.'.'.$extension[1];
				$model->nombre = $fileName;
				if($uploadedFile->size > 2048576){
					Yii::app()->user->setFlash('error', "El archivo que usted ha enviado tiene un peso mayor a 2MB.");
					$this->redirect(array('pvboletinpostventa/adjuntar/'.$id));
					die();
				}else{
					if($uploadedFile->saveAs(Yii::app()->basePath . '/../upload/boletines/' . $fileName))
						$error = 0;
					else{
						$error = 1;
						Yii::app()->user->setFlash('error', "Verifique que el archivo no se encuentre da&ntilde;ado o sea superior a 2MB");
						$this->redirect(array('pvboletinpostventa/updateAdjunto/idA/'.$idA.'/id/'.$id));
						die();
					}
				}
			}
			if($model->save()&& $error == 0){
				Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
				$this->redirect(array('pvboletinpostventa/adjuntar/'.$model->boletinpostventa_id));
				die();
			}
		}

		$this->render('adjuntar',array(
			'model'=>$model,
			'id'=>$id,
			'boletin'=>$boletin,
		));
	}
	public function actionUpdateAdjunto($idA,$id){
		//llamadas de clases EXCEL
		date_default_timezone_set("America/Bogota");
		$p = new CHtmlPurifier();
		$boletin = Boletinpostventa::model()->find(array('order' => 'id DESC', 'condition' => "id=:id", 'params' => array(':id' =>(int)$id)));
		  $error = 0;
		$model= AdjuntoBoletin::model()->find(array('order' => 'id DESC', 'condition' => "id=:id", 'params' => array(':id' =>(int)$idA)));
		$fileAnterior = $model->nombre;
		if(isset($_POST['AdjuntoBoletin']))
		{
			$model->attributes=$_POST['AdjuntoBoletin'];
			$uploadedFile = CUploadedFile::getInstance($model, 'nombre');
			if(!empty($uploadedFile)){
				
				$rnd = rand(0, 9999);
				$date = date("Ymdhis");
				$extension = explode('.', $uploadedFile);
				$fileName = md5($rnd.$date).'.'.$extension[1];
				$model->nombre = $fileName;
				
				if($uploadedFile->size > 2048576){
					Yii::app()->user->setFlash('error', "El archivo que usted ha enviado tiene un peso mayor a 2MB.");
					$this->redirect(array('pvboletinpostventa/updateAdjunto/idA/'.$idA.'/id/'.$id));
					die();
				}else{
					if($uploadedFile->saveAs(Yii::app()->basePath . '/../upload/boletines/' . $fileName))
						$error = 0;
					else{
						$error = 1;
						Yii::app()->user->setFlash('error', "Verifique que el archivo no se encuentre da&ntilde;ado o sea superior a 2MB");
						$this->redirect(array('pvboletinpostventa/updateAdjunto/idA/'.$idA.'/id/'.$id));
						die();
					}
				}
			}else{
				$model->nombre = $fileAnterior;
			}
			if($model->save() && $error == 0){
				Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
				$this->redirect(array('pvboletinpostventa/adjuntar/'.$model->boletinpostventa_id));
				die();
			}
		}

		$this->render('adjuntar',array(
			'model'=>$model,
			'id'=>$id,
			'boletin'=>$boletin,
		));
	}
	public function actionEliminarAdjunto($idA,$id)
	{

		if(AdjuntoBoletin::model()->deleteAll(array('condition' => "id=:id", 'params' => array(':id' =>(int)$idA))))
			$this->redirect(array('pvboletinpostventa/adminAdjunto/id/'.$id));
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		/*if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));*/
	}
	
	
	public function actionView($id)
	{
		$modelos = ControlBoletin::model()->findAll(array('order' => 'id DESC', 'condition' => "boletinpostventa_id=:id", 'params' => array(':id' =>(int)$id)));
		$adjuntos = AdjuntoBoletin::model()->findAll(array('order' => 'id DESC', 'condition' => "boletinpostventa_id=:id", 'params' => array(':id' =>(int)$id)));
		$this->render('view',array(
			'model'=>$this->loadModel($id),
			'modelos'=>$modelos,
			'adjuntos'=>$adjuntos,
		));
	}
	public function actionVerAdjunto($id)
	{
		$model= AdjuntoBoletin::model()->find(array('order' => 'id DESC', 'condition' => "id=:id", 'params' => array(':id' =>(int)$id)));
		$boletin = Boletinpostventa::model()->find(array('order' => 'id DESC', 'condition' => "id=:id", 'params' => array(':id' =>(int)$model->boletinpostventa_id)));
		
		$this->render('verAdjunto',array(
			'model'=>$model,
			'boletin'=>$boletin,
			'id'=>$model->boletinpostventa_id,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
	
		$model=new Boletinpostventa;
		$control= 0;
		$models = Modelosposventa::model()->findAll(array("order"=>"ordenar ASC"));
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Boletinpostventa']))
		{
				
			if(!isset($_POST['modelos'])){
				Yii::app()->user->setFlash('error', '<div class="errorMessage">Debe seleccionar por lo menos un modelo.</div>');
				$this->redirect(array('pvboletinpostventa/create'));
				die();
			}
			/**/
			$model->attributes=$_POST['Boletinpostventa'];
			if($model->save()){
				$error = 0;
				for($i = 0 ; $i < count($_POST['modelos']) ; $i++){
					$control = new ControlBoletin;
					$control->modelosposventa_id = $_POST['modelos'][$i];
					$control->boletinpostventa_id = $model->id;
					if(!$control->save())
						$error++;
				}
				if($error == 0){
					Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
					$this->redirect(array('pvboletinpostventa/create'));
				}
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'control' =>$control,
			'models'=>$models,
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
		$control = ControlBoletin::Model()->findAll(array('condition' => "boletinpostventa_id=:x", 'params' => array(':x' =>(int)$id)));
		$models = Modelosposventa::model()->findAll(array("order"=>"ordenar ASC"));
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Boletinpostventa']))
		{
			if(!isset($_POST['modelos'])){
				Yii::app()->user->setFlash('error', '<div class="errorMessage">Debe seleccionar por lo menos un modelo.</div>');
				$this->redirect(array('pvboletinpostventa/update'.$model->id));
				die();
			}
			
			$model->attributes=$_POST['Boletinpostventa'];
			if($model->save()){
				if(ControlBoletin::Model()->deleteAll(array('condition' => "boletinpostventa_id=:x", 'params' => array(':x' =>(int)$id)))){
					$error = 0;
					for($i = 0 ; $i < count($_POST['modelos']) ; $i++){
						$control = new ControlBoletin;
						$control->modelosposventa_id = $_POST['modelos'][$i];
						$control->boletinpostventa_id = $model->id;
						if(!$control->save())
							$error++;
					}
					if($error == 0){
						Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
						$this->redirect(array('pvboletinpostventa/update/'.$model->id));
					}
				}
				
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'control' =>$control,
			'models'=>$models,
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
		$items = AdjuntoBoletin::model()->count(array('condition' => "boletinpostventa_id=:id", 'params' => array(':id' =>(int)$id)));
		if($items >0)
			AdjuntoBoletin::model()->deleteAll(array('condition' => "boletinpostventa_id=:id", 'params' => array(':id' =>(int)$id)));
		
		$items = ControlBoletin::model()->deleteAll(array('condition' => "boletinpostventa_id=:id", 'params' => array(':id' =>(int)$id)));
		if($items >0)
			ControlBoletin::model()->deleteAll(array('condition' => "boletinpostventa_id=:id", 'params' => array(':id' =>(int)$id)));
			
			$this->loadModel($id)->delete();
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		
		
		
	}
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Boletinpostventa');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		//$model=new Boletinpostventa('search');
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
        $pages = new CPagination(Boletinpostventa::model()->count($criteria));

        // Set Page Limit
        $pages->pageSize = 10;

        // Apply page criteria to CDbCriteria
        $pages->applyLimit($criteria);

        // Grab the records
        $posts = Boletinpostventa::model()->findAll($criteria);
		
        // Render the view
        $this->render('admin', array(
            'model' => $posts,
            'pages' => $pages,
            'busqueda' => '',
			'fechaBusqueda'=> '',
			'fechaBusquedaFin'=> '',
           )
        );
	}
	public function actionSearch()
	{
		$p = new CHtmlPurifier();
		$fechaBusqueda = "";
		$fechaBusquedaFin = "";
		$patronBusqueda = "";
		
		if(!empty($_GET['Modelos']['Descripcion']) && empty($_GET['fechaInicio']) && empty($_GET['fechaFin'])){
			$patronBusqueda =  $p->purify($_GET['Modelos']['Descripcion']);
			$posts = Boletinpostventa::model()->findAll(array('order' => 'id DESC', 'condition' => "titulo LIKE :match OR codigo LIKE :match OR contenido LIKE :match", 'params' => array(':match' =>"%$patronBusqueda%")));
		}else if(empty($_GET['Modelos']['Descripcion']) && !empty($_GET['fechaInicio']) && !empty($_GET['fechaFin'])){
			$fechaBusqueda =  $p->purify($_GET['fechaInicio']);
			$fechaBusquedaFin =  $p->purify($_GET['fechaFin']);
			$posts = Boletinpostventa::model()->findAll(array('order' => 'id DESC', 'condition' => "fecha >=:f AND fecha <=:fn", 'params' => array(':f' =>$fechaBusqueda,':fn' =>$fechaBusquedaFin)));
			
		}else if(!empty($_GET['Modelos']['Descripcion']) && !empty($_GET['fechaInicio']) && !empty($_GET['fechaFin'])){		
			$fechaBusqueda =  $p->purify($_GET['fechaInicio']);
			$fechaBusquedaFin =  $p->purify($_GET['fechaFin']);
			$patronBusqueda =  $p->purify($_GET['Modelos']['Descripcion']);
			$posts = Boletinpostventa::model()->findAll(array('order' => 'id DESC', 'condition' => "(fecha >=:f AND fecha <=:fn) AND (titulo LIKE :match OR codigo LIKE :match OR contenido LIKE :match)", 'params' => array(':f' =>$fechaBusqueda,':fn' =>$fechaBusquedaFin,':match' =>"%$patronBusqueda%")));
		}else{
				Yii::app()->user->setFlash('error', "Ingrese un valor o seleccione una FECHA INICIAL y FINAL para realizar la busqueda.");
				 $this->redirect(array('pvboletinpostventa/admin/'));
		}	
			
		   
		   if(!empty($posts)){
				$pages = new CPagination(count($posts));
				$pages->pageSize = 10;
	
				$this->render('admin', array(
					'model' => $posts,
					'pages' => $pages,
					'busqueda' => $patronBusqueda,
					'fechaBusqueda' => $fechaBusqueda,
					'fechaBusquedaFin' => $fechaBusquedaFin,
					)
				);
		   }else{
				Yii::app()->user->setFlash('error', "No se encontraron datos con la busqueda realizada.");
				 $this->redirect(array('pvboletinpostventa/admin/'));
		   }
		
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Boletinpostventa the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Boletinpostventa::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Boletinpostventa $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='boletinpostventa-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
