<?php

class GestionFilesController extends Controller
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
				'actions'=>array('create','update','admin','producto','libros','mercado'),
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
		$model=new GestionFiles;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['GestionFiles']))
		{
			/*echo '<pre>';
			print_r($_FILES);
			echo '</pre>';
			die();*/
			$model->attributes=$_POST['GestionFiles'];

			$archivoThumb = CUploadedFile::getInstance($model, 'nombre');
            $fileName = "{$archivoThumb}";  // file name
            if ($archivoThumb != "") {
                    //die('enter file');
                $model->nombre = $fileName;
                date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
                $model->fecha_actualizacion = date("Y-m-d H:i:s");
                if ($model->save()) {
                    $archivoThumb->saveAs(Yii::getPathOfAlias("webroot") . "/images/uploads/libreria/" . $fileName);
                    $this->redirect(array('view'));
                }
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

		if(isset($_POST['GestionFiles']))
		{
			$model->attributes=$_POST['GestionFiles'];
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
		$dataProvider=new CActiveDataProvider('GestionFiles');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin($tipo = null, $mes = null)
	{
		$model=new GestionFiles('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['GestionFiles']))
			$model->attributes=$_GET['GestionFiles'];

		$this->render('admin',array(
			'model'=>$model, 'tipo' => $tipo, 'mes' => $mes
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return GestionFiles the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=GestionFiles::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param GestionFiles $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='gestion-files-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionProducto(){
		$this->render('producto');
	}

	public function actionLibros(){
		$this->render('libros');
	}

	public function actionMercado(){
		$this->render('mercado');
	}
}
