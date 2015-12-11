<?php

class GestionStockController extends Controller {

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
                'actions' => array('create', 'update', 'adjunto', 'admin'),
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

    public function actionAdjunto() {
        //llamadas de clases EXCEL
        date_default_timezone_set("America/Bogota");
        $p = new CHtmlPurifier();

        $phpExcelPath = Yii::getPathOfAlias('ext.reader');
        include($phpExcelPath . "/" . 'php-excel-reader/excel_reader2.php');
        include($phpExcelPath . "/" . 'SpreadsheetReader.php');

        //Archivo a leer
        $Filepath = Yii::app()->basePath . '/../upload/example.xls';

        $StartMem = memory_get_usage();
        if (!empty($_FILES)) {
            /*echo '<pre>';
            print_r($_FILES);
            echo '</pre>';
            die();*/
            $error = 0;
            $extension = substr($_FILES['userfile']['name'], -6);
            $extension = explode('.', $extension);

            switch (strtolower($extension[1])) {
                case 'xls':
                    $error = 0;
                    break;
                case 'xlsx':
                    $error = 0;
                    break;
                default:
                    Yii::app()->user->setFlash('error', "El archivo que usted ha enviado no es un EXCEL.");
                    $this->redirect(array('gestionStock/adjunto/'));
                    $error = 1;
                    break;
            }
            switch ($_FILES['userfile']['type']) {
                case 'application/vnd.ms-excel':
                    $error = 0;
                    break;
                case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                    $error = 0;
                    break;
                default:
                    Yii::app()->user->setFlash('error', "El archivo que usted ha enviado no es un EXCEL o no se ha guardado exitosamente.");
                    $this->redirect(array('gestionStock/adjunto/'));
                    $error = 1;
                    break;
            }
            if ($error == 0) {
                $ruta_destino = '/var/www/archivos/';
                $Filepath = Yii::app()->basePath . '/../upload/stock/';
                $uploadfile = $Filepath . basename('file_' . date("Y-m-d_h_i_s") . '_' . $_FILES['userfile']['name']);

                if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                    try {
                        $errorSave = "";
                        $Spreadsheet = new SpreadsheetReader($uploadfile);
                        $BaseMem = memory_get_usage();
                        $Sheets = $Spreadsheet->Sheets();
                        foreach ($Sheets as $Index => $Name) {
                            $Time = microtime(true);
                            $Spreadsheet->ChangeSheet($Index);

                            foreach ($Spreadsheet as $Key => $Row) {
                                if ($Row) {
                                    $model = new GestionStock;
                                    $model->fecha_w = $p->purify($Row[0]);
                                    $model->embarque = $p->purify($Row[1]);
                                    $model->bloque = $p->purify($Row[2]);
                                    $model->familia = $p->purify($Row[3]);
                                    $model->code = $p->purify($Row[4]);
                                    $model->version = $p->purify($Row[5]);
                                    $model->equip = $p->purify($Row[6]);
                                    $model->fsc = $p->purify($Row[7]);
                                    $model->referencia = $p->purify($Row[8]);
                                    $model->aeade = $p->purify($Row[9]);
                                    $model->segmento = $p->purify($Row[10]);
                                    $model->grupo = $p->purify($Row[11]);
                                    $model->concesionario = $p->purify($Row[12]);
                                    $model->color_origen = $p->purify($Row[13]);
                                    $model->color_plano = $p->purify($Row[14]);
                                    $model->my = $p->purify($Row[15]);
                                    $model->chasis = $p->purify($Row[16]);
                                    $model->edad = $p->purify($Row[17]);
                                    $model->rango = $p->purify($Row[18]);
                                    $model->fact = $p->purify($Row[19]);
                                    $model->cod_aeade = $p->purify($Row[20]);
                                    $model->pvc = $p->purify($Row[21]);
                                    $model->stock = $p->purify($Row[22]);
                                    
                                    
                                    if (!$model->save()) {
                                        $errorSave++;
                                    } else
                                        $errorSave = 0;
                                    //echo $Row[0].' '.$Row[1].' '.$Row[2].'<br>';
                                }
                                else {
                                    var_dump($Row);
                                }
                                $CurrentMem = memory_get_usage();
                            }
                        }
                        if ($errorSave == 0) {
                            Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
                            $this->redirect(array('gestionStock/adjunto'));
                            die();
                        } else {
                            Yii::app()->user->setFlash('error', "Se han prducido " . $errorSave . " error/es al cargar tu archivo");
                            $this->redirect(array('gestionStock/adjunto/'));
                        }
                    } catch (Exception $E) {
                        echo $E->getMessage();
                    }
                } else {
                    Yii::app()->user->setFlash('error', "El archivo que usted ha enviado no se ha podido cargar.");
                    $this->redirect(array('gestionStock/adjunto/'));
                }
            }
        }

        $this->render('adjunto');
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new GestionStock;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionStock'])) {
            $model->attributes = $_POST['GestionStock'];
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

        if (isset($_POST['GestionStock'])) {
            $model->attributes = $_POST['GestionStock'];
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
        $dataProvider = new CActiveDataProvider('GestionStock');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new GestionStock('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['GestionStock']))
            $model->attributes = $_GET['GestionStock'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return GestionStock the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = GestionStock::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param GestionStock $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'gestion-stock-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
