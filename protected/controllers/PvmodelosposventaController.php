<?php

class PvmodelosposventaController extends Controller
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
				'actions'=>array('admin','create','update','eliminar','search'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array(),
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
		$model=new Modelosposventa;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Modelosposventa']))
		{
			print_r($_POST);
			//die();
			$model->attributes=$_POST['Modelosposventa'];
			if($model->save()){
				Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
                $this->redirect(array('pvmodelosposventa/create'));
			}
				//$this->redirect(array('admin','id'=>$model->id));
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

		if(isset($_POST['Modelosposventa']))
		{
			$model->attributes=$_POST['Modelosposventa'];
			if($model->save()){
				Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
                $this->redirect(array('pvmodelosposventa/update/'.$model->id));
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
		echo $id;
		die();
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
		$dataProvider=new CActiveDataProvider('Modelosposventa');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 
	public function actionAdmin()
	{
		$model=new Modelosposventa('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Modelosposventa']))
			$model->attributes=$_GET['Modelosposventa'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}*/
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
        $pages = new CPagination(Modelosposventa::model()->count($criteria));

        // Set Page Limit
        $pages->pageSize = 10;

        // Apply page criteria to CDbCriteria
        $pages->applyLimit($criteria);

        // Grab the records
        $posts = Modelosposventa::model()->findAll($criteria);

        // Render the view
        $this->render('admin', array(
            'model' => $posts,
            'pages' => $pages,
            'busqueda' => '',
           )
        );
	}
	public function actionSearch()
	{
		$p = new CHtmlPurifier();
		if(!empty($_GET['Modelos']['Descripcion'])){
			$patronBusqueda =  $p->purify($_GET['Modelos']['Descripcion']);
			
			$posts = Modelosposventa::model()->findAll(array('order' => 'id DESC, ordenar ASC', 'condition' => "descripcion LIKE :match OR codigo LIKE :match", 'params' => array(':match' =>"%$patronBusqueda%")));
		   
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
				 $this->redirect(array('pvmodelosposventa/admin/'));
		   }
		}else{
				Yii::app()->user->setFlash('error', "Ingrese un valor para realizar la busqueda.");
				 $this->redirect(array('pvmodelosposventa/admin/'));
		   }
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Modelosposventa the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Modelosposventa::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Modelosposventa $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='modelosposventa-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
