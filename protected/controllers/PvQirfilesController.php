<?php

class PvQirfilesController extends Controller {

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
                'actions' => array('create','admin', 'update', 'Eliminar', 'Search'),
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
    public function actionCreate($id) {
        $model = new Qirfiles;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Qirfiles'])) {
            $model->attributes = $_POST['Qirfiles'];
            $model->qirId = $id;

            $uploadedFile = CUploadedFile::getInstance($model, 'nombre');
            if (!empty($uploadedFile)) {
                $rnd = rand(0, 9999);
                $extension = explode('.', $uploadedFile);
                //$fileName = $model->num_reporte.$rnd . '.' . $extension[1];
                $fileName = $model->num_reporte."_".date("Y-m-d-h-i-s") . '.' . $extension[1];
                $model->nombre = $fileName;
                if ($uploadedFile->size > 2048576) {
                    Yii::app()->user->setFlash('error', "El archivo que usted ha enviado tiene un peso mayor a 2MB.");
                    $this->redirect(array('admin', 'id' => $model->qirId));
                    die();
                } else {
                    if ($uploadedFile->saveAs(Yii::app()->basePath . '/../upload/qirfiles/' . $fileName))
                        $error = 0;
                    else {
                        $error = 1;
                        Yii::app()->user->setFlash('error', "Verifique que el archivo no se encuentre da&ntilde;ado o sea superior a 2MB");
                        $this->redirect(array('admin', 'id' => $model->qirId));
                        die();
                    }
                }
            }

            if ($model->save())
                $this->redirect(array('admin', 'id' => $model->qirId));
            
        }

        $model->qirId = $id;
        $model->num_reporte = $model->qir->num_reporte;

        $this->render('create', array(
            'model' => $model,
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

        if (isset($_POST['Qirfiles'])) {
            $fileAnterior = $model->nombre;
            $model->attributes = $_POST['Qirfiles'];
            
            $uploadedFile = CUploadedFile::getInstance($model, 'nombre');
            if (!empty($uploadedFile)) {
                $rnd = rand(0, 9999);
                $extension = explode('.', $uploadedFile);
                $fileName = $model->num_reporte.$rnd . '.' . $extension[1];
                $fileName = $model->num_reporte."_".date("Y-m-d-h-i-s") . '.' . $extension[1];
                //$model->nombre = $fileName;
                
                if ($uploadedFile->size > 2048576) {
                    Yii::app()->user->setFlash('error', "El archivo que usted ha enviado tiene un peso mayor a 2MB.");
                    $this->redirect(array('admin', 'id' => $model->qirId));
                    die();
                } else {
                    if ($uploadedFile->saveAs(Yii::app()->basePath . '/../upload/qirfiles/' . $fileName))
                        $error = 0;
                    else {
                        $error = 1;
                        Yii::app()->user->setFlash('error', "Verifique que el archivo no se encuentre da&ntilde;ado o sea superior a 2MB");
                        $this->redirect(array('admin', 'id' => $model->qirId));
                        die();
                    }
                }
            } else {
                $model->nombre = $fileAnterior;
            }
            
            if ($model->save())
                $this->redirect(array('admin', 'id' => $model->qirId));
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
        $dataProvider = new CActiveDataProvider('Qirfiles');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin($id) {
        $modelV = new Qirfiles;
        $concesionario = Yii::app()->user->getState('dealer_id');
        $rol = Yii::app()->user->getState('roles');

        $modelQir = Qir::model()->findByPk($id);


        $criteria = new CDbCriteria;


        if ($rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
            $criteria->condition = "qirId = :id";
            $criteria->order = 'id asc';
            $criteria->params = array(':id' => $id);
        else:
            $criteria->condition = "qirId = :id";
            $criteria->params = array(':id' => $id);
            $criteria->order = 'id asc';
        endif;


        if (Yii::app()->user->hasState('errorDelete')) {
            Yii::app()->user->setFlash('message', Yii::app()->user->getState('errorDelete'));
            Yii::app()->user->setState('errorDelete', NULL);
        }


        // Count total records
        $pages = new CPagination(Qirfiles::model()->count($criteria));

        // Set Page Limit
        $pages->pageSize = 10;

        // Apply page criteria to CDbCriteria
        $pages->applyLimit($criteria);

        // Grab the records
        $posts = Qirfiles::model()->findAll($criteria);

        // Render the view
        $this->render('admin', array(
            'model' => $posts,
            'pages' => $pages,
            'busqueda' => '',
            'modelPost' => $modelV,
            'modelQir' => $modelQir,
            'id' => $id,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Qirfiles the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Qirfiles::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Qirfiles $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'qirfiles-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionEliminar($id) {
        $model = $this->loadModel($id);
        $qirId = $model->qirId;
        $model->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin', 'id' => $qirId));
    }

    public function actionSearch($id) {
        //$p = new CHtmlPurifier();        
        if (isset($_GET['Qirfiles']) && !empty($_GET['Qirfiles'])) {
            $modelQir = Qir::model()->findByPk($id);

            $model = new Qirfiles;
            $modelV = new Qirfiles;
            $model->attributes = $_GET['Qirfiles'];
            $modelV->attributes = $_GET['Qirfiles'];


            $criteria = new CDbCriteria;
            $params = array();

            if ($model->nombre) {
                $criteria->addCondition('id like "%' . $model->nombre . '%"', 'OR');
                $criteria->addCondition('nombre like "%' . $model->nombre . '%"', 'OR');
                $criteria->addCondition('num_reporte like "%' . $model->nombre . '%" ', 'OR');
            }

            $criteria->addCondition('qirId =:qirId');
            $params[':qirId'] = $id;


            $criteria->params = $params;

            // Count total records
            $pages = new CPagination(Qirfiles::model()->count($criteria));

            // Set Page Limit
            $pages->pageSize = 10;

            // Apply page criteria to CDbCriteria
            $pages->applyLimit($criteria);

            // Grab the records
            $posts = Qirfiles::model()->findAll($criteria);

            /* Verificamos que tenga parametros de busqueda caso contrario redirect al admin */

            if (!$posts) {
                Yii::app()->user->setFlash('error', "No se encontraron datos con la busqueda realizada.");
                $this->redirect(array('admin', 'id' => $id));
            }

            if (!empty($params)) {
                $this->render('admin', array(
                    'model' => $posts,
                    'pages' => $pages,
                    'busqueda' => '',
                    'modelPost' => $modelV,
                    'id' => $id,
                    'modelQir' => $modelQir
                ));
            } else
                $this->redirect(array('admin', 'id' => $id));
        } else {
            Yii::app()->user->setFlash('error', "Ingrese un valor para realizar la busqueda.");
            $this->redirect(array('admin', 'id' => $id));
        }
    }

}
