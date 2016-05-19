<?php

class GestionCierreController extends Controller {

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
                'actions' => array('create', 'update', 'create','pdf'),
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
    public function actionCreate($id_informacion = NULL, $id_vehiculo = NULL) {
        $model = new GestionCierre;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionCierre'])) {
            $model->attributes = $_POST['GestionCierre'];
            $model->id_informacion = $_POST['id_informacion'];
            $model->id_vehiculo = $_POST['id_vehiculo'];

            if ($model->save()) {
                $con = Yii::app()->db;
                $sql = "UPDATE gestion_vehiculo SET cierre = 'ACTIVO' WHERE id = {$_POST['id_vehiculo']}";

                // ACTUALIZAR EN GESTION DIARIA EL STATUS DE CIERRE A 1
                $sql2 = "UPDATE gestion_diaria SET cierre = 1, paso = 9 WHERE id_informacion = {$_POST['id_informacion']}";
                $request = $con->createCommand($sql)->query();
                $request2 = $con->createCommand($sql2)->query();

                $factura = new GestionFactura;
                $factura->id_informacion = $_POST['id_informacion'];
                $factura->id_vehiculo = $_POST['id_vehiculo'];
                //$factura->datos_vehiculo = $_POST['datos_vehiculo'];
                $factura->observaciones = $_POST['GestionCierre']['observacion'];
                date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
                $factura->fecha = date("Y-m-d H:i:s");
                $factura->save();
                $this->redirect(array('site/factura', 'id_informacion' => $_POST['id_informacion'], 'id_vehiculo' => $_POST['id_vehiculo']));
            }
            //$this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model, 'id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id_informacion = NULL, $id_vehiculo = NULL) {
        $model = $this->loadModel($id_informacion, $id_vehiculo);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionCierre'])) {
            $model->attributes = $_POST['GestionCierre'];
            if ($model->save())
                $this->redirect(array('site/factura', 'id_informacion' => $_POST['id_informacion'], 'id_vehiculo' => $_POST['id_vehiculo']));
        }

        $this->render('update', array(
            'model' => $model, 'id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo,
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
        $dataProvider = new CActiveDataProvider('GestionCierre');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new GestionCierre('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['GestionCierre']))
            $model->attributes = $_GET['GestionCierre'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return GestionCierre the loaded model
     * @throws CHttpException
     */
    public function loadModel($id_informacion, $id_vehiculo) {
        $model = GestionCierre::model()->find(array('condition' => "id_informacion = {$id_informacion} AND id_vehiculo = {$id_vehiculo}"));
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param GestionCierre $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'gestion-cierre-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
    
    public function actionPdf($id_informacion, $id_vehiculo) {
        $this->layout = '//layouts/call-print';
        $id_asesor = Yii::app()->user->getId();
        $responsable_id = $this->getResponsableId($id_informacion);
        $nombre_responsable = $this->getResponsableNombres($responsable_id);
        $nombre_responsable = mb_convert_case($nombre_responsable, MB_CASE_UPPER, "UTF-8");
        $nombre_cliente = $this->getNombreCliente($id_informacion);
        $concesionarioid = $this->getConcesionarioDealerId($id_asesor);
        
        $mPDF1 = Yii::app()->ePdf->mpdf('', 'A4');
        $stylesheet = file_get_contents(Yii::getPathOfAlias('webroot.bootstrap.css') . '/bootstrap.css');
        $mPDF1->WriteHTML($stylesheet, 1);
        $mPDF1->WriteHTML($this->render('factura', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo), true));
        $mPDF1->Output('factura-cierre.pdf', 'I');
        
    }

}
