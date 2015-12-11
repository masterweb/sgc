<?php

class UcargoController extends Controller
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
				'actions'=>array('create','update','admin','search','eliminar','asignar'),
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
	public function actionCreate()
	{
		$model=new Cargo;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Cargo']))
		{

			$model->attributes=$_POST['Cargo'];
			if(empty($_POST['modulo'])){ 
					Yii::app()->user->setFlash('error', "Seleccione los modulos que tendr&aacute; acceso el cargo antes de continuar.");
					$this->redirect(array('ucargo/create/'));
					die();
			}
			if($model->save()){
				//REGISTRAMOS FUENTE
				/*$fuente = $_POST['fuente'];
				if(!empty($fuente)){
					
					for($i = 0 ; $i <count($fuente) ; $i++){
						$modelFuente = new Cargofuente();
						$modelFuente->cargo_id = $model->id;
						$modelFuente->fuente_id = $fuente[$i];
						$modelFuente->save();
					}
				}else{
					Yii::app()->user->setFlash('error', "Seleccione la fuente antes de continuar.");
					$this->redirect(array('ucargo/create/'));
				}*/
				//REGISTRAMOS MODULOS
				if(!empty($_POST['modulo'])){ 
					$modulo = $_POST['modulo'];
					
					for($i = 0 ; $i < count($modulo) ; $i++){
						$modelModulo = new Cargomodulos();
						$modelModulo->cargo_id = $model->id;
						$modelModulo->modulo_id = $modulo[$i];
						$modelModulo->save();
					}
				}else{
					Yii::app()->user->setFlash('error', "Seleccione los modulos que tendr&aacute; acceso el cargo antes de continuar.");
					$this->redirect(array('ucargo/create/'));
					die();
				}
				$countAuth = Authitem::model()->count(array("condition" => "name = '$model->descripcion' "));
                if ($countAuth == 0)
                    Yii::app()->authManager->createRole($model->descripcion);
				
				Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
                $this->redirect(array('ucargo/create'));
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

		if(isset($_POST['Cargo']))
		{
			$model->attributes=$_POST['Cargo'];
			if($model->save()){
				//REGISTRAMOS FUENTE
				/*$fuente = $_POST['fuente'];
				if(!empty($fuente)){
					$cf = Cargofuente::model()->count(array("condition"=>'cargo_id='.$model->id));
					if($cf >0){
						Cargofuente::model()->deleteAll(array("condition"=>'cargo_id='.$model->id));
					}
					for($i = 0 ; $i <count($fuente) ; $i++){
						$modelFuente = new Cargofuente();
						$modelFuente->cargo_id = $model->id;
						$modelFuente->fuente_id = $fuente[$i];
						$modelFuente->save();
					}
				}else{
					Yii::app()->user->setFlash('error', "Seleccione la fuente antes de continuar.");
					$this->redirect(array('ucargo/update/'.$model->id));
				}*/
				//REGISTRAMOS MODULOS
				
				if(!empty($_POST['modulo'])){ 
					$modulo = $_POST['modulo'];
					$cf = Cargomodulos::model()->count(array("condition"=>'cargo_id='.$model->id));
					if($cf >0){
						Cargomodulos::model()->deleteAll(array("condition"=>'cargo_id='.$model->id));
					}
					for($i = 0 ; $i < count($modulo) ; $i++){
						$modelModulo = new Cargomodulos();
						$modelModulo->cargo_id = $model->id;
						$modelModulo->modulo_id = $modulo[$i];
						$modelModulo->save();
					}
				}else{
					Yii::app()->user->setFlash('error', "Seleccione los modulos que tendr&aacute; acceso el cargo antes de continuar.");
					$this->redirect(array('ucargo/create/'));
				}
				
				$countAuth = Authitem::model()->count(array("condition" => "name = '$model->descripcion' "));
                if ($countAuth == 0)
                    Yii::app()->authManager->createRole($model->descripcion);
				
				Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
                $this->redirect(array('ucargo/update/'.$model->id));
			}
		}
		$modulos = Cargomodulos::model()->findAll(array('condition'=>'cargo_id='.$model->id));
		$fuentes = Cargofuente::model()->findAll(array('condition'=>'cargo_id='.$model->id));
		$this->render('update',array(
			'model'=>$model,
			'umodulos'=>$modulos,
			'ufuentes'=>$fuentes,
		));
	}

	public function actionSearch()
	{
		$p = new CHtmlPurifier();
		if(!empty($_GET['Search']['Descripcion'])){
			$patronBusqueda =  $p->purify($_GET['Search']['Descripcion']);
			
			$posts = Cargo::model()->findAll(array('order' => 'id DESC', 'condition' => "descripcion LIKE :match OR codigo LIKE :match", 'params' => array(':match' =>"%$patronBusqueda%")));
		   
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
				 $this->redirect(array('ucargo/admin/'));
		   }
		}else{
				Yii::app()->user->setFlash('error', "Ingrese un valor para realizar la busqueda.");
				 $this->redirect(array('ucargo/admin/'));
		   }
	}
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
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Cargo');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$criteria = new CDbCriteria;
		$criteria->order = 'id desc';
	    // Count total records
        $pages = new CPagination(Cargo::model()->count($criteria));

        // Set Page Limit
        $pages->pageSize = 10;

        // Apply page criteria to CDbCriteria
        $pages->applyLimit($criteria);

        // Grab the records
        $posts = Cargo::model()->findAll($criteria);

        // Render the view
        $this->render('admin', array(
            'model' => $posts,
            'pages' => $pages,
            'busqueda' => '',
           )
        );
	}

	public function loadModel($id)
	{
		$model=Cargo::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Cargo $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cargo-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	public function actionAsignar($rol){
		
		$rol = Cargo::model()->find(array('order' => 'id DESC', 'condition' => "id=:match", 'params' => array(':match' =>"$rol")));
		if(isset($_POST['accesos']))
		{
			$acesoso_id = $_POST['accesos'];
			$acesoso_id = explode("|",$acesoso_id);
			print_r($acesoso_id);
			//die();
			if(count($acesoso_id)>0){
				$verificarAccesos = Permiso::model()->count(array('condition' => "cargoId=:match", 'params' => array(':match' =>"$rol->id")));
				if($verificarAccesos>0)
					$verificarAccesos = Permiso::model()->deleteAll(array('condition' => "cargoId=:match", 'params' => array(':match' =>"$rol->id")));
				$error = 0;
				for($i = 0; $i < count($acesoso_id)-1; $i++){
					
					$model = new Permiso();
					$model->cargoId = $rol->id;
					$model->accesoSistemaId = (int)$acesoso_id[$i];
					$model->fecha = date("Y-m-d h:i:s");
					$model->estado = "ACTIVO";
					if(!$model->save()){
						$error++;
					}
				}
				$accesos = Accesosistema::model()->findAll(array('order' => 'controlador ASC ,modulo_id DESC', 'condition' => "estado=:match", 'params' => array(':match' =>"TODOS")));
				if(!empty($accesos)){
					foreach($accesos as $i){
						/*acciones ajax que deben tener todos los usuarios*/
						$model = new Permiso();
						$model->cargoId = $rol->id;
						$model->accesoSistemaId = $i->id;
						$model->fecha = date("Y-m-d h:i:s");
						$model->estado = "ACTIVO";
						$model->save();
					}
				}
				
				if($error > 1){
					
					Yii::app()->user->setFlash('success', 'Se produjeron ' . $error. 'al grabar verifique que los cambios solicitados se han completado.');
					$this->redirect(array('ucargo/asignar/rol/'.$rol->id));
					die();
				}else{
					Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
					$this->redirect(array('ucargo/asignar/rol/'.$rol->id));
					die();
				}
			}
			
		}
		$cargados = Permiso::model()->findAll(array( 'condition' => "cargoId=:match", 'params' => array(':match' =>$rol->id)));
		/*if($rol->area->modulo->descripcion == "TOTAL"){
			$modulos = Modulo::model()->findAll(array('order' => 'descripcion DESC'));
			$accesos = Accesosistema::model()->findAll(array('order' => 'controlador ASC, modulo_id DESC', 'condition' => "estado=:match", 'params' => array(':match' =>"ACTIVO")));
		}else{*/
			
			$values = array();
			$modulosA = array();
			$results = Cargomodulos::model()->findAll(array("condition"=>'cargo_id ='.$rol->id));
			if(!empty($results)){
				
				foreach($results as $r){ 
					$values[] = $r->cargo_id;
					$modulosA[] = $r->modulo_id;
				}
			}
			
			$criteria = new CDbCriteria;
			$criteria->addInCondition('id', $modulosA);
			$criteria->order = 'descripcion DESC';
			$modulos = Modulo::model()->findAll($criteria);
			
			$criteria = new CDbCriteria;
			$criteria->condition = "estado='ACTIVO'";
			$criteria->addInCondition('modulo_id', $modulosA);
			$criteria->order = 'controlador ASC, modulo_id DESC';
			$accesos = Accesosistema::model()->findAll($criteria);
		//}
		$this->render('asignar',array('rols'=>$rol,'modulos'=>$modulos,"accesos"=>$accesos,"cargados"=>$cargados));
	}
}
