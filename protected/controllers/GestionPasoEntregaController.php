<?php

class GestionPasoEntregaController extends Controller {

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
                'actions' => array('create', 'update', 'admin', 'delete', 'alter'),
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

    public function actionAlter() {
        $con = Yii::app()->db;
        //$sql = "ALTER table gestion_paso_entrega_detail add id_gestion int";
        $sql = "TRUNCATE gestion_paso_entrega_detail";
        $request = $con->createCommand($sql)->query();
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($id_informacion = NULL, $id_vehiculo = NULL) {
        $model = new GestionPasoEntrega;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionPasoEntrega'])) {
//            echo '<pre>';
//            print_r($_POST);
//            echo '</pre>';
//            die();
            $tipo = $this->getFinanciamiento($_POST['GestionPasoEntrega']['id_informacion']);
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador

            $paso_entrega = $_POST['GestionPasoEntrega']['paso'];
            $id_gestion = $_POST['GestionPasoEntrega']['id_gestion_paso_entrega'];
            //die('id gestion: '.$id_gestion);
            if ($id_gestion == 0) {
                //die('enter gestion 0');
                $model->attributes = $_POST['GestionPasoEntrega'];
                $model->paso = ($tipo == 1) ? 1 : 4;
                if ($tipo == 0) {
                    $model->emision_contrato = 1;
                    $model->agendar_firma = 1;
                    $model->alistamiento_unidad = 1;
                }
                if ($model->save()) {
                    $gestion = new GestionPasoEntregaDetail;
                    $gestion->id_paso = ($tipo == 1) ? 1 : 4;
                    $gestion->id_gestion = $model->id;
                    if ($tipo == 0) {
                        $gestion->fecha_paso = $_POST['GestionPasoEntregaDetail']['envio_factura_fecha4'];
                        $gestion->observaciones = $_POST['GestionPasoEntregaDetail']['observaciones4'];
                    } else {
                        $gestion->fecha_paso = $_POST['GestionPasoEntregaDetail']['envio_factura_fecha1'];
                        $gestion->observaciones = $_POST['GestionPasoEntregaDetail']['observaciones1'];
                    }

                    $gestion->fecha = date("Y-m-d H:i:s");
                    $gestion->save();
                    //$this->redirect(array('view', 'id' => $model->id));
                }
            } else {
                $model = $this->loadModel($id_gestion);
                //die('paso entrega: '.$paso_entrega);
                switch ($paso_entrega) {
                    case 1:
                        $model->paso = 2;
                        $model->emision_contrato = 1;
                        $model->save();
                        $gestion = new GestionPasoEntregaDetail;
                        $gestion->id_paso = 2;
                        $gestion->id_gestion = $id_gestion;
                        $gestion->fecha_paso = $_POST['GestionPasoEntregaDetail']['envio_factura_fecha2'];
                        $gestion->observaciones = $_POST['GestionPasoEntregaDetail']['observaciones2'];
                        $gestion->fecha = date("Y-m-d H:i:s");
                        $gestion->save();
                        break;
                    case 2:
                        $model->paso = 3;
                        $model->agendar_firma = 1;
                        $model->save();
                        $gestion = new GestionPasoEntregaDetail;
                        $gestion->id_paso = 3;
                        $gestion->id_gestion = $id_gestion;
                        $gestion->fecha_paso = $_POST['GestionPasoEntregaDetail']['envio_factura_fecha3'];
                        $gestion->observaciones = $_POST['GestionPasoEntregaDetail']['observaciones3'];
                        $gestion->fecha = date("Y-m-d H:i:s");
                        $gestion->save();
                        break;
                    case 3:
                        $model->paso = 4;
                        $model->alistamiento_unidad = 1;
                        $model->save();
                        $gestion = new GestionPasoEntregaDetail;
                        $gestion->id_paso = 4;
                        $gestion->id_gestion = $id_gestion;
                        $gestion->fecha_paso = $_POST['GestionPasoEntregaDetail']['envio_factura_fecha4'];
                        $gestion->observaciones = $_POST['GestionPasoEntregaDetail']['observaciones4'];
                        $gestion->fecha = date("Y-m-d H:i:s");
                        $gestion->save();
                        break;
                    case 4:
                        $model->paso = 5;
                        $model->pago_matricula = 1;
                        $model->save();
                        $gestion = new GestionPasoEntregaDetail;
                        $gestion->id_paso = 5;
                        $gestion->id_gestion = $id_gestion;
                        $gestion->fecha_paso = $_POST['GestionPasoEntregaDetail']['envio_factura_fecha5'];
                        $gestion->observaciones = $_POST['GestionPasoEntregaDetail']['observaciones5'];
                        $gestion->fecha = date("Y-m-d H:i:s");
                        $gestion->save();
                        break;
                    case 5:
                        $model->paso = 6;
                        $model->recepcion_contratos = 1;
                        $model->save();
                        $gestion = new GestionPasoEntregaDetail;
                        $gestion->id_paso = 6;
                        $gestion->id_gestion = $id_gestion;
                        $gestion->fecha_paso = $_POST['GestionPasoEntregaDetail']['envio_factura_fecha6'];
                        $gestion->observaciones = $_POST['GestionPasoEntregaDetail']['observaciones6'];
                        $gestion->fecha = date("Y-m-d H:i:s");
                        $gestion->save();
                        break;
                    case 6:
                        $model->paso = 7;
                        $model->recepcion_matricula = 1;
                        $model->save();
                        $gestion = new GestionPasoEntregaDetail;
                        $gestion->id_paso = 7;
                        $gestion->id_gestion = $id_gestion;
                        $gestion->fecha_paso = $_POST['GestionPasoEntregaDetail']['envio_factura_fecha7'];
                        $gestion->placa = $_POST['GestionPasoEntregaDetail']['placa'];
                        $gestion->fecha = date("Y-m-d H:i:s");
                        $gestion->save();
                        break;
                    case 7:
                        $model->paso = 8;
                        $model->vehiculo_revisado = 1;
                        $model->save();
                        $gestion = new GestionPasoEntregaDetail;
                        $gestion->id_paso = 8;
                        $gestion->id_gestion = $id_gestion;
                        $gestion->fecha_paso = $_POST['GestionPasoEntregaDetail']['envio_factura_fecha8'];
                        $gestion->responsable = $_POST['GestionPasoEntregaDetail']['responsable'];
                        $gestion->fecha = date("Y-m-d H:i:s");
                        $gestion->save();
                        break;
                    case 8:
                        $model->paso = 9;
                        $model->entrega_vehiculo = 1;
                        $model->save();
                        $gestion = new GestionPasoEntregaDetail;
                        $gestion->id_paso = 9;
                        $gestion->id_gestion = $id_gestion;
                        $gestion->fecha_paso = $_POST['GestionPasoEntregaDetail']['envio_factura_fecha9'];
                        $gestion->observaciones = $_POST['GestionPasoEntregaDetail']['observaciones9'];
                        $gestion->fecha = date("Y-m-d H:i:s");
                        $gestion->save();
                        break;
                    case 9:
                        //die('enter paso 9');
                        $model->paso = 10;
                        $model->foto_entrega = 1;
                        $model->save();
                        $gestion = new GestionPasoEntregaDetail;
                        $gestion->id_paso = 10;
                        $gestion->id_gestion = $id_gestion;
//                        echo '<pre>';
//                        print_r($_FILES);
//                        echo '</pre>';
//                        die();
                        $archivoThumb = CUploadedFile::getInstance($model, 'foto_entrega');
                        $fileName = "{$archivoThumb}";  // file name
                        //die('filename: '.$fileName);
                        if ($archivoThumb != "") {
                            //die('enter file');
                            $gestion->foto_entrega = $fileName;

                            $archivoThumb->saveAs(Yii::getPathOfAlias("webroot") . "/images/uploads/" . $fileName);
                            $link = Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName;
                        }
                        $archivoThumb2 = CUploadedFile::getInstance($model, 'foto_hoja_entrega');
                        $fileName2 = "{$archivoThumb2}";  // file name
                        if ($archivoThumb2 != "") {
                            //die('enter file 2');
                            $gestion->foto_hoja_entrega = $fileName2;
                            $archivoThumb2->saveAs(Yii::getPathOfAlias("webroot") . "/images/uploads/" . $fileName2);
                            $link2 = Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName2;
                        }
                        $gestion->fecha = date("Y-m-d H:i:s");
                        $gestion->save();
                        break;

                    default:
                        break;
                }
            }

            //if ($model->save())
            //    $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model, 'id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo
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

        if (isset($_POST['GestionPasoEntrega'])) {
            $model->attributes = $_POST['GestionPasoEntrega'];
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
        $dataProvider = new CActiveDataProvider('GestionPasoEntrega');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new GestionPasoEntrega('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['GestionPasoEntrega']))
            $model->attributes = $_GET['GestionPasoEntrega'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return GestionPasoEntrega the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = GestionPasoEntrega::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param GestionPasoEntrega $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'gestion-paso-entrega-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
