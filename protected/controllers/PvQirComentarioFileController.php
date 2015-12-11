<?php

class PvQirComentarioFileController extends Controller {

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
                'actions' => array('create', 'admin','update','Eliminar','Search'),
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
        $model = new QirComentarioFile;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['QirComentarioFile'])) {
            $model->attributes = $_POST['QirComentarioFile'];
            $model->qirComentarioId = $id;
            $uploadedFile = CUploadedFile::getInstance($model, 'nombre_file');
            if (!empty($uploadedFile)) {
                $rnd = rand(0, 9999);
                $fecha = date("Ymd");
                $extension = explode('.', $uploadedFile);
                $fileName = date("Y-m-d-h-i-s") . '.' . $extension[1];
                //$fileName = md5($rnd.$fecha) . '.' . $extension[1];
                $model->nombre_file = $fileName;
                if ($uploadedFile->size > 2048576) {
                    Yii::app()->user->setFlash('error', "El archivo que usted ha enviado tiene un peso mayor a 2MB.");
                    $this->redirect(array('admin', 'id' => $model->qirId));
                    die();
                } else {
                    if ($uploadedFile->saveAs(Yii::app()->basePath . '/../upload/qircomentariofile/' . $fileName))
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
                $this->redirect(array('admin', 'id' => $model->qirComentarioId));
        }
        
        $model->qirComentarioId = $id;

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

        if (isset($_POST['QirComentarioFile'])) {
            $fileAnterior = $model->nombre_file;
            $model->attributes = $_POST['QirComentarioFile'];
            
            $uploadedFile = CUploadedFile::getInstance($model, 'nombre_file');
            if (!empty($uploadedFile)) {
                $rnd = rand(0, 9999);
                $fecha = date("Ymd");
                $extension = explode('.', $uploadedFile);
                $fileName = md5($rnd.$fecha) . '.' . $extension[1];
                $fileName = date("Y-m-d-h-i-s") . '.' . $extension[1];
                //$model->nombre_file = $fileName;
                if ($uploadedFile->size > 2048576) {
                    Yii::app()->user->setFlash('error', "El archivo que usted ha enviado tiene un peso mayor a 2MB.");
                    $this->redirect(array('admin', 'id' => $model->qirComentarioId));
                    die();
                } else {
                    if ($uploadedFile->saveAs(Yii::app()->basePath . '/../upload/qircomentariofile/' . $fileName))
                        $error = 0;
                    else {
                        $error = 1;
                        Yii::app()->user->setFlash('error', "Verifique que el archivo no se encuentre da&ntilde;ado o sea superior a 2MB");
                        $this->redirect(array('admin', 'id' => $model->qirComentarioId));
                        die();
                    }
                }
            } else {
                $model->nombre_file = $fileAnterior;
            }
            
            if ($model->save())
                $this->redirect(array('admin', 'id' => $model->qirComentarioId));
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
        $dataProvider = new CActiveDataProvider('QirComentarioFile');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin($id) {
        $modelV = new QirComentarioFile;
        $concesionario = Yii::app()->user->getState('dealer_id');
        $rol = Yii::app()->user->getState('roles');

        $modelQir = Qircomentario::model()->findByPk($id);


        $criteria = new CDbCriteria;


        if ($rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
            $criteria->condition = "qirComentarioId = :id";
            $criteria->order = 'id asc';
            $criteria->params = array(':id' => $id);
        else:
            $criteria->condition = "qirComentarioId = :id";
            $criteria->params = array(':id' => $id);
            $criteria->order = 'id asc';
        endif;


        if (Yii::app()->user->hasState('errorDelete')) {
            Yii::app()->user->setFlash('message', Yii::app()->user->getState('errorDelete'));
            Yii::app()->user->setState('errorDelete', NULL);
        }


        // Count total records
        $pages = new CPagination(QirComentarioFile::model()->count($criteria));

        // Set Page Limit
        $pages->pageSize = 10;

        // Apply page criteria to CDbCriteria
        $pages->applyLimit($criteria);

        // Grab the records
        $posts = QirComentarioFile::model()->findAll($criteria);

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
     * @return QirComentarioFile the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = QirComentarioFile::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param QirComentarioFile $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'qir-comentario-file-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionEliminar($id) {
        $model = $this->loadModel($id);
        $qirId = $model->qirComentarioId;
        $model->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin', 'id' => $qirId));
    }
    
    public function actionSearch($id) {
        //$p = new CHtmlPurifier();
        if (isset($_GET['QirComentarioFile']) && !empty($_GET['QirComentarioFile'])) {
            $modelQir = Qircomentario::model()->findByPk($id);

            $model = new QirComentarioFile;
            $modelV = new QirComentarioFile;
            $model->attributes = $_GET['QirComentarioFile'];
            $modelV->attributes = $_GET['QirComentarioFile'];


            $criteria = new CDbCriteria;
            $params = array();

            if ($model->nombre_file) {
                $criteria->addCondition('id like "%' . $model->nombre_file . '%"', 'OR');
                $criteria->addCondition('nombre_file like "%' . $model->nombre_file . '%"', 'OR');
            }

            $criteria->addCondition('qirComentarioId =:qirId');
            $params[':qirId'] = $id;


            $criteria->params = $params;

            // Count total records
            $pages = new CPagination(QirComentarioFile::model()->count($criteria));

            // Set Page Limit
            $pages->pageSize = 10;

            // Apply page criteria to CDbCriteria
            $pages->applyLimit($criteria);

            // Grab the records
            $posts = QirComentarioFile::model()->findAll($criteria);

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
