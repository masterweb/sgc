<?php

class GestionTestDriveController extends Controller {

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
                'actions' => array('create', 'update'),
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
    public function actionCreate($id_informacion = NULL, $id = NULL) {
        $model = new GestionTestDrive;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        

        if (isset($_POST['GestionTestDrive'])) {
            $model->attributes = $_POST['GestionTestDrive'];
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            $model->test_drive = 1;

            if ($_POST['GestionTestDrive']['preg1'] == 1) { // If make a Test Drive uploads a image
                $model->setscenario('img');
                // subir imagen principal del test drive al servidor
                $archivoThumb = CUploadedFile::getInstance($model, 'img');
                $fileName = "{$archivoThumb}";  // file name
                if ($archivoThumb != "") {
                    //die('enter file');
                    $model->img = $fileName;
                    if ($model->save()) {
                        $archivoThumb->saveAs(Yii::getPathOfAlias("webroot") . "/images/uploads/" . $fileName);
                        $link = Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName;
                        $image = new EasyImage($link);
                        $image->resize(600, 480); // resize images for thumbs
                        $image->save(Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName);
                    }
                }
                $con = Yii::app()->db;
                $sql = "UPDATE gestion_diaria SET primera_visita = 1, paso = '6', status = 1 WHERE id_informacion = {$id_informacion}";
                $request = $con->createCommand($sql)->query();
                
                $demostracion = new GestionDemostracion;
                $demostracion->preg1 = $_POST['GestionTestDrive']['preg1'];
                $demostracion->preg1_licencia = $fileName;
                $demostracion->fecha = date("Y-m-d H:i:s");
                $demostracion->save();
                
            } else if($_POST['GestionTestDrive']['test_drive'] == 2){ // Vuelve hacer Test Drive
                $model->observacion = $_POST['GestionTestDrive']['observacion'];
            }else { // If not make Test Drive
                $model->setscenario('observacion');
                $model->observacion = $_POST['GestionTestDrive']['observacion'];
            }

            if ($model->save())
                $this->redirect(array('site/presentacion/'.$id_informacion));
        }

        $this->render('create', array(
            'model' => $model, 'id_informacion' => $id_informacion, 'id' => $id
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

        if (isset($_POST['GestionTestDrive'])) {
            $model->attributes = $_POST['GestionTestDrive'];
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
        $dataProvider = new CActiveDataProvider('GestionTestDrive');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new GestionTestDrive('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['GestionTestDrive']))
            $model->attributes = $_GET['GestionTestDrive'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return GestionTestDrive the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = GestionTestDrive::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param GestionTestDrive $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'gestion-test-drive-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
