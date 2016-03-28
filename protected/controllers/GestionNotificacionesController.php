<?php

class GestionNotificacionesController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

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
                'actions' => array('create', 'update', 'vernotificacion'),
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
    public function actionCreate() {
        $model = new GestionNotificaciones;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionNotificaciones'])) {
            $model->attributes = $_POST['GestionNotificaciones'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

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

        if (isset($_POST['GestionNotificaciones'])) {
            $model->attributes = $_POST['GestionNotificaciones'];
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
        $dataProvider = new CActiveDataProvider('GestionNotificaciones');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new GestionNotificaciones('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['GestionNotificaciones']))
            $model->attributes = $_GET['GestionNotificaciones'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return GestionNotificaciones the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = GestionNotificaciones::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param GestionNotificaciones $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'gestion-notificaciones-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionVernotificacion($id = NULL, $id_informacion = NULL, $cargo_id = NULL, $tipo = NULL) {
        //echo 'id: '.$id.' ,caso id: '.$caso_id;
        //die();
        if ($tipo == 4) {
            $sql = "UPDATE gestion_consulta SET leido = 'READ' WHERE id_informacion={$id_informacion}";
            //die('sql: '.$sql);
            $con = Yii::app()->db;
            $request = $con->createCommand($sql)->query();
            $paso = $this->getPasoNotificacionDiaria($id_informacion);
        } 
        if ($tipo == 3) {
            $sql = "UPDATE gestion_notificaciones SET leido = 'READ' WHERE id_informacion={$id_informacion}";
            //die('sql: '.$sql);
            $con = Yii::app()->db;
            $request = $con->createCommand($sql)->query();
            $paso = $this->getPasoNotificacionDiaria($id_informacion);
        }
        if ($tipo == 1) {
            $sql = "UPDATE gestion_notificaciones SET leido = 'READ' WHERE id={$id}";
            //die('sql: '.$sql);
            $con = Yii::app()->db;
            $request = $con->createCommand($sql)->query();
            $paso = $this->getPasoNotificacion($id);
        }

        switch ($paso) {
            case '1-2':
                $url = Yii::app()->createUrl('gestionInformacion/update/', array('id' => $id_informacion, 'tipo' => 'gestion'));
                $this->redirect(array('gestionInformacion/update/' . $id_informacion));
                break;
            case '3':
                //$url = Yii::app()->createUrl('gestionVehiculo/create', array('id' => $value['id']));
                $url = Yii::app()->createUrl('site/consulta', array('id_informacion' => $c['id_info'], 'tipo' => 'gestion', 'fuente' => 'web'));
                $this->redirect(array('site/consulta/', 'id_informacion' => $id_informacion, 'tipo' => 'gestion', 'fuente' => 'web'));
                break;
            case '4':
                //$url = Yii::app()->createUrl('gestionVehiculo/create', array('id' => $id_informacion));
                //$this->redirect(array('gestionVehiculo/create/' . $id_informacion));
                $this->redirect(array('site/consulta/', 'id_informacion' => $id_informacion, 'tipo' => 'gestion', 'fuente' => 'web'));
                break;
            case '5':
                //$url = Yii::app()->createUrl('site/presentacion', array('id' => $id_informacion));
                $this->redirect(array('site/presentacion/' . $id_informacion));
                break;
            case '6':
                //$url = Yii::app()->createUrl('site/demostracion', array('id' => $value['id_informacion']));
                $this->redirect(array('site/demostracion/' . $id_informacion));
                break;
            case '7':
                //$url = Yii::app()->createUrl('site/negociacion', array('id' => $value['id_informacion']));
                $this->redirect(array('site/negociacion/' . $id_informacion));
                break;
            case '8':
                //$url = Yii::app()->createUrl('site/negociacion', array('id' => $value['id_informacion']));
                $this->redirect(array('site/negociacion/' . $id_informacion));
                break;
            case '9':
                //$url = Yii::app()->createUrl('site/cierre', array('id' => $value['id_informacion']));
                $this->redirect(array('site/cierre/' . $id_informacion));
                break;
            case '11':
                //$url = Yii::app()->createUrl('site/cierre', array('id' => $value['id_informacion']));
                $this->redirect(array('site/negociacion/' . $id_informacion));
                break;
            default:
                break;
        }
    }

}
