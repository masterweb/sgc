<?php

class PvQiradicionalController extends Controller {

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
                'actions' => array('create', 'admin','update', 'Eliminar', 'Search'),
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
        $model = new Qiradicional;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Qiradicional'])) {
            $model->attributes = $_POST['Qiradicional'];
            $model->qirId = $id;

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

        if (isset($_POST['Qiradicional'])) {
            $model->attributes = $_POST['Qiradicional'];
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
        $dataProvider = new CActiveDataProvider('Qiradicional');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin($id) {
        $modelV = new Qiradicional;
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
        $pages = new CPagination(Qiradicional::model()->count($criteria));

        // Set Page Limit
        $pages->pageSize = 10;

        // Apply page criteria to CDbCriteria
        $pages->applyLimit($criteria);

        // Grab the records
        $posts = Qiradicional::model()->findAll($criteria);

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
     * @return Qiradicional the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Qiradicional::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Qiradicional $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'qiradicional-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionEliminar($id) {
        $model = $this->loadModel($id);
        $qir = $model->qirId;
        if ($model) {
            if (!$model->delete()) {
                $model->getErrors();
                Yii::app()->user->setState('errorDelete', 'No se puede eliminar porque existe datos relacionados con el mismo');
            }
        }

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin', 'id' => $qir));
    }

    public function actionSearch($id) {
        //$p = new CHtmlPurifier();        
        if (isset($_GET['Qiradicional']) && !empty($_GET['Qiradicional'])) {
            $modelQir = Qir::model()->findByPk($id);

            $model = new Qiradicional;
            $modelV = new Qiradicional;
            $model->attributes = $_GET['Qiradicional'];
            $modelV->attributes = $_GET['Qiradicional'];


            $criteria = new CDbCriteria;
            $params = array();

            if ($model->vin) {
                $criteria->addCondition('vin like "%' . $model->vin . '%"', 'OR');
                $criteria->addCondition('num_motor like "%' . $model->vin . '%" ', 'OR');
                $criteria->addCondition('kilometraje like "%' . $model->vin . '%" ', 'OR');
                $criteria->addCondition('num_reporte like "%' . $model->vin . '%" ', 'OR');
            }

            $criteria->addCondition('qirId =:qirId');
            $params[':qirId'] = $id;


            $criteria->params = $params;

            // Count total records
            $pages = new CPagination(Qiradicional::model()->count($criteria));

            // Set Page Limit
            $pages->pageSize = 10;

            // Apply page criteria to CDbCriteria
            $pages->applyLimit($criteria);

            // Grab the records
            $posts = Qiradicional::model()->findAll($criteria);

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
