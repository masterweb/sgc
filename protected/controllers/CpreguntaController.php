<?php

class CpreguntaController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/call';

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
				'actions'=>array('create','update','subadmin','admin','matriz','matrizactualizar','eliminar','search','seleccion','opciones','opcionesactualizar'),
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
	public function actionCreate($c,$opcion=null)
	{
		$model=new Cpregunta;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Cpregunta']))
		{
			$model->attributes=$_POST['Cpregunta'];
			$model->fecha = date("Y-m-d");
			if(!empty($opcion))
				$model->copcionpregunta_id = (int)$opcion;
			
			if($model->save()){
				if($model->ctipopregunta_id == 1){
					//Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
					if(!empty($opcion)){
						$getOpcion = Copcionpregunta::model()->findByPk($opcion);
						Yii::app()->user->setFlash('error', "<div class='alert alert-info'>Subpregunta creada exitosamente</div>");
						$this->redirect(array('cpregunta/opcionesactualizar/id/'.$getOpcion->cpregunta->id.'/c/'.$getOpcion->cpregunta->cquestionario_id.'/op/'.$getOpcion->cpregunta->ctipopregunta_id));
					}
					$this->redirect(array('cpregunta/admin/'.$model->cquestionario_id));
				}else{
					$this->redirect(array('copcionpregunta/admin/'.$model->id));
				}
			}
		}
		$nump =  Cpregunta::model()->count(array('condition'=>'cquestionario_id='.$c))+1;
		$this->render('create',array(
			'model'=>$model,
			'idc'=>$c,
			'nump'=>$nump,
		));
	}
	public function actionOpciones($c,$op,$opcion=null){
		$model=new Cpregunta;
		if(isset($_POST['Cpregunta']))
		{	
			
			$p = new CHtmlPurifier();
			
			$model->attributes=$_POST['Cpregunta'];
			$model->estado ='ACTIVO';
			$model->fecha = date("Y-m-d");
			if(!empty($opcion))
				$model->copcionpregunta_id = (int)$opcion;
			if($model->save()){
				$opciones = $p->purify($_POST['opciontxt']);
				$status = 0;
				if(count($opciones)>=3){
					
					for($i = 0 ; $i <= count($opciones) ; $i++){

						if(!empty($opciones[$i])){
							//echo $opciones[$i];
							$opcionesB = new Copcionpregunta();		
							if(!empty($opciones[$i]['opcion'])){
								$opcionesB->detalle = $opciones[$i]['opcion'];
							}
							if(!empty($opciones[$i]['justifica'])){
								$opcionesB->valor = $opciones[$i]['justifica'];
							}
							$opcionesB->cpregunta_id = $model->id;
							if(!$opcionesB->save()){
								$status++;
								/*echo '<pre>';
								print_r($opciones[$i]);
								die();*/
							}
						}
					}
					//echo $status;
					//if($status==0){
						//Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
						if(!empty($opcion)){
							$getOpcion = Copcionpregunta::model()->findByPk($opcion);
							Yii::app()->user->setFlash('error', "<div class='alert alert-info'>Subpregunta creada exitosamente</div>");
							$this->redirect(array('cpregunta/opcionesactualizar/id/'.$getOpcion->cpregunta->id.'/c/'.$getOpcion->cpregunta->cquestionario_id.'/op/'.$getOpcion->cpregunta->ctipopregunta_id));
						}else{
							Yii::app()->user->setFlash('error', "<div class='alert alert-info'>La pregunta ha sido creada exitosamente.</div>");
							$this->redirect(array('cpregunta/Opcionesactualizar/id/'.$model->id.'/c/'.$c.'/op/'.$model->ctipopregunta_id));	
						}
						//$this->redirect(array('cpregunta/admin/'.$model->cquestionario_id));	
				//	}
					
				}
			}
		}


		$this->render('opciones',array('idc'=>$c,'op'=>$op,'model'=>$model,));
	}
	public function actionMatriz($c,$op){
		$model=new Cpregunta;
		if(isset($_POST['Cpregunta']))
		{
			$p = new CHtmlPurifier();
			echo '<pre>';
				print_r($_POST);
				echo '-->'.($_POST['columnatxt']['valor']['1']);
				echo '</pre>';
				//die();
			$model->attributes=$_POST['Cpregunta'];
			$model->estado ='ACTIVO';
			$model->fecha = date("Y-m-d");
			if($model->save()){
				
				$opciones = $p->purify($_POST['opciontxt']);
				$status = 0;
				if(count($opciones)>=3){
					
					for($i = 0 ; $i <= count($opciones) ; $i++){

						if(!empty($opciones[$i])){
							echo $opciones[$i];
							$opcionesB = new Copcionpregunta();		
							$opcionesB->detalle = $opciones[$i];
							$opcionesB->cpregunta_id = $model->id;
							if(!$opcionesB->save()){
								$status++;
							}
						}
					}
				}
				$columnas = $p->purify($_POST['columnatxt']['pregunta']);
				$valores =$p->purify($_POST['columnatxt']['valor']);
				if(count($columnas)>=3){
					
					for($i = 0 ; $i <= count($columnas) ; $i++){

						if(!empty($columnas[$i])){
							echo $columnas[$i];
							$columna = new Cmatrizpregunta();		
							$columna->detalle = $columnas[$i];
							$columna->valor = $valores[$i];
							$columna->fecha = date('Y-m-d h:i:s');
							$columna->cpregunta_id = $model->id;
							if(!$columna->save()){
								$status++;
							}
						}
					}
				}
				if($status==0){
					//Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
					$this->redirect(array('cpregunta/admin/'.$model->cquestionario_id));	
				}
			}
		}
		$this->render('matriz',array('idc'=>$c,'op'=>$op,'model'=>$model,));
	}

	public function actionMatrizactualizar($id,$c,$op){
		$model=$this->loadModel($id);
		if(isset($_POST['Cpregunta']))
		{
			$p = new CHtmlPurifier();
			echo '<pre>';
				print_r($_POST);
				echo '-->'.($_POST['columnatxt']['valor']['1']);
				echo '</pre>';
				//die();
			$model->attributes=$_POST['Cpregunta'];
			$model->estado ='ACTIVO';
			$model->fecha = date("Y-m-d");
			if($model->save()){
				
				$opciones = $p->purify($_POST['opciontxt']);
				$revisar = Copcionpregunta::model()->findAll(array('condition'=>'cpregunta_id='.$model->id));
				if(!empty($revisar)){
					Copcionpregunta::model()->deleteAll(array('condition'=>'cpregunta_id='.$model->id));
				}
				$status = 0;
				if(count($opciones)>=3){
					
					for($i = 0 ; $i <= count($opciones) ; $i++){

						if(!empty($opciones[$i])){
							echo $opciones[$i];
							$opcionesB = new Copcionpregunta();		
							$opcionesB->detalle = $opciones[$i];
							$opcionesB->cpregunta_id = $model->id;
							if(!$opcionesB->save()){
								$status++;
							}
						}
					}
				}
				$revisar = Cmatrizpregunta::model()->findAll(array('condition'=>'cpregunta_id='.$model->id));
				if(!empty($revisar)){
					Cmatrizpregunta::model()->deleteAll(array('condition'=>'cpregunta_id='.$model->id));
				}
				$columnas = $p->purify($_POST['columnatxt']['pregunta']);
				$valores =$p->purify($_POST['columnatxt']['valor']);
				if(count($columnas)>=3){
					
					for($i = 0 ; $i <= count($columnas) ; $i++){

						if(!empty($columnas[$i])){
							echo $columnas[$i];
							$columna = new Cmatrizpregunta();		
							$columna->detalle = $columnas[$i];
							$columna->valor = $valores[$i];
							$columna->fecha = date('Y-m-d h:i:s');
							$columna->cpregunta_id = $model->id;
							if(!$columna->save()){
								$status++;
							}
						}
					}
				}
				if($status==0){
					//Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
					$this->redirect(array('cpregunta/admin/'.$model->cquestionario_id));	
				}
			}
		}
		$this->render('matrizactualizar',array('idc'=>$c,'op'=>$op,'model'=>$model,));
	}
	public function actionOpcionesactualizar($id,$c,$op){
		$model=$this->loadModel($id);
		if(isset($_POST['Cpregunta']))
		{
			$p = new CHtmlPurifier();
			//echo '<pre>';
								//print_r($_POST);
								//die();
			$model->attributes=$_POST['Cpregunta'];
			$model->estado ='ACTIVO';
			$model->fecha = date("Y-m-d");
			if($model->save()){
				$opciones = $p->purify($_POST['opciontxt']);
				$status = 0;
				$revisar = Copcionpregunta::model()->findAll(array('condition'=>'cpregunta_id='.$model->id));
				if(!empty($revisar)){
					Copcionpregunta::model()->deleteAll(array('condition'=>'cpregunta_id='.$model->id));
				}
				if(count($opciones)>=3){
					
					for($i =0 ; $i <= count($opciones) ; $i++){

						if(!empty($opciones[$i])){
							//echo $opciones[$i];
							$opcionesB = new Copcionpregunta();		
							if(!empty($opciones[$i]['opcion'])){
								$opcionesB->detalle = $opciones[$i]['opcion'];
							}
							if(!empty($opciones[$i]['justifica'])){
								$opcionesB->valor = $opciones[$i]['justifica'];
							}
							$opcionesB->cpregunta_id = $model->id;
							if(!$opcionesB->save()){
								$status++;
								/*echo '<pre>';
								print_r($opciones[$i]);
								die();*/
							}
						}
					}
					
					//if($status==0){
						//Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
						$this->redirect(array('cpregunta/admin/'.$model->cquestionario_id));	
					//}
					
				}
			}
		}
		

		$this->render('opcionesactualizar',array('idc'=>$c,'op'=>$op,'model'=>$model,));
	}
	public function actionSeleccion($c,$opcion=null){
		$this->render('seleccionar',array('idc'=>$c,'opcion'=>$opcion));
	}
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Cpregunta']))
		{
			$model->attributes=$_POST['Cpregunta'];
			if($model->save()){
				
				Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
				$this->redirect(array('cpregunta/update/'.$model->id));
			
			}
				//$this->redirect(array('view','id'=>$model->id));
		}
		$nump = $model->orden;
		$this->render('update',array(
			'model'=>$model, 
			'nump'=>$nump,
		));
	}

	public function actionEliminar($id)
	{
	
		$models = Cpregunta::model()->findByPk($id);

		$this->loadModel($id)->delete();
		
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('cpregunta/admin/'.$models->cquestionario_id));
	}
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	public function actionSearch($id)
	{
		$p = new CHtmlPurifier();
		if(!empty($_GET['Modelos']['Descripcion'])){
			$patronBusqueda =  $p->purify($_GET['Modelos']['Descripcion']);
			
			$posts = Cpregunta::model()->findAll(array('order' => 'id DESC', 'condition' => "descripcion LIKE :match", 'params' => array(':match' =>"%$patronBusqueda%")));
		   
		   if(!empty($posts)){
				$pages = new CPagination(count($posts));
				$pages->pageSize = 10;
	
				$this->render('admin', array(
					'model' => $posts,
					'pages' => $pages,
					'busqueda' => $patronBusqueda,
					 'idc'=>$id,
					)
				);
		   }else{
				Yii::app()->user->setFlash('error', "No se encontraron datos con la busqueda realizada.");
				 $this->redirect(array('cpregunta/admin/'.$id));
		   }
		}else{
				Yii::app()->user->setFlash('error', "Ingrese un valor para realizar la busqueda.");
				 $this->redirect(array('cpregunta/admin/'.$id));
		   }
	}
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Cpregunta');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin($id)
	{
		$rol = Yii::app()->user->getState('roles');
        $criteria = new CDbCriteria;
        $criteria->condition = "cquestionario_id=$id and copcionpregunta_id is null";
        $criteria->order = 'orden ASC';
		$pages = null;


        // Count total records
        //$pages = new CPagination(Cpregunta::model()->count($criteria));

        // Set Page Limit
        //$pages->pageSize = 10;

        // Apply page criteria to CDbCriteria
       // $pages->applyLimit($criteria);

        // Grab the records
        $posts = Cpregunta::model()->findAll($criteria);

        // Render the view
        $this->render('admin', array(
            'model' => $posts,
            'pages' => $pages,
            'busqueda' => '',
            'idc'=>$id,
           )
        );
	}
	public function actionSubadmin($id,$opcion)
	{
		$rol = Yii::app()->user->getState('roles');
        $criteria = new CDbCriteria;
        $criteria->condition = "cquestionario_id=$id and copcionpregunta_id=$opcion";
        $criteria->order = 'orden ASC';
		$pages = null;


        // Count total records
        //$pages = new CPagination(Cpregunta::model()->count($criteria));

        // Set Page Limit
        //$pages->pageSize = 10;

        // Apply page criteria to CDbCriteria
       // $pages->applyLimit($criteria);

        // Grab the records
        $posts = Cpregunta::model()->findAll($criteria);

        // Render the view
        $this->render('subadmin', array( 
            'model' => $posts,
            'pages' => $pages,
            'busqueda' => '',
            'idc'=>$id,
            'opcion'=>$opcion,
           )
        );
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Cpregunta the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Cpregunta::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Cpregunta $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cpregunta-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
