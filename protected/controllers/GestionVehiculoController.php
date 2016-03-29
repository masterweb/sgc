<?php

class GestionVehiculoController extends Controller {

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
                'actions' => array('admin', 'create', 'update', 'createAjax', 'createAjax2', 'negociacion', 'negociacionup','proformaexo',
                    'proforma', 'negociacionAjax', 'hojaEntrega', 'solicitud', 'pago', 'sendProforma', 'modProforma', 'negociacionex'),
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
    public function actionCreate($id = NULL) {
        $model = new GestionVehiculo;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionVehiculo'])) {
            $model->attributes = $_POST['GestionVehiculo'];
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            if ($model->save())
                $this->redirect(array('gestionFinanciamiento/create', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model, 'id' => $id
        ));
    }

    public function actionSolicitud($id_informacion = NULL, $id_vehiculo = NULL) {
        $this->render('solicitud', array(
            'id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo
        ));
    }

    /**
     * Creates a new model via Ajax.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreateAjax($id = NULL) {
        //die('enter create ajax');
        $model = new GestionVehiculo;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionVehiculo'])) {
//            echo '<pre>';
//            print_r($_POST);
//            echo '</pre>';
//            die('enter post');
            $model->attributes = $_POST['GestionVehiculo'];
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            $gestion = new GestionDiaria;
            $gestion->id_informacion = $_POST['GestionVehiculo']['id_informacion'];
            $gestion->id_vehiculo = 0;
            if (isset($_POST['opciones_seguimiento'])):
                $gestion->observaciones = $_POST['opciones_seguimiento'];
            endif;
            $gestion->medio_contacto = 'visita';
            $gestion->fuente_contacto = 'na';
            $gestion->codigo_vehiculo = 0;
            $gestion->seguimiento = 1;
            if (isset($_POST['agendamiento'])):
                $gestion->proximo_seguimiento = $_POST['agendamiento'];
            endif;
            $gestion->fecha = date("Y-m-d H:i:s");
            $gestion->save();
            if ($model->validate()) {
                $res = $model->save();
                //die('res: '.$res);
                if ($res == TRUE)
                    echo TRUE;
                else
                    echo FALSE;
            }
        }
        //die('if not post');
    }

    public function actionCreateAjax2($id = NULL) {
        //die('enter create ajax');
        $model = new GestionVehiculo;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionVehiculo2'])) {
//            echo '<pre>';
//            print_r($_POST);
//            echo '</pre>';
//            die('enter post');
            $model->attributes = $_POST['GestionVehiculo2'];
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            if ($model->validate()) {
                $res = $model->save();
                //die('res: '.$res);
                if ($res == TRUE)
                    echo TRUE;
                else
                    echo FALSE;
            }
        }
        //die('if not post');

        $this->render('create', array(
            'model' => $model, 'id' => $id
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id_vehiculo = NULL, $id_informacion = NULL) {
        $model = $this->loadModel($id_vehiculo);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionVehiculo'])) {
            $model->attributes = $_POST['GestionVehiculo'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model, 'id_vehiculo' => $id_vehiculo, 'id_informacion' => $id_informacion
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
        $dataProvider = new CActiveDataProvider('GestionVehiculo');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new GestionVehiculo('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['GestionVehiculo']))
            $model->attributes = $_GET['GestionVehiculo'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return GestionVehiculo the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = GestionVehiculo::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return GestionFinanciamiento the loaded model
     * @throws CHttpException
     */
    public function loadModelFinanciamiento($id) {
        $model = GestionFinanciamiento::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param GestionVehiculo $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'gestion-vehiculo-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * 
     * @param type $id_informacion
     * @param type $id_vehiculo
     */
    public function actionNegociacion($id_informacion = NULL, $id_vehiculo = NULL) {
        
        if (isset($_POST['GestionFinanciamiento'])) {
//            echo '<pre>';
//            print_r($_POST);
//            echo '</pre>';
//            die();
            $model = new GestionFinanciamiento;
            $model->attributes = $_POST['GestionFinanciamiento'];
            $model->precio_vehiculo = $_POST['GestionFinanciamiento']['precio'];
            $model->tasa = $_POST['GestionFinanciamiento']['tasa'];
            $model->plazos = $_POST['GestionFinanciamiento']['plazo'];
            $model->seguro = $_POST['GestionFinanciamiento']['seguro'];
            $model->valor_financiamiento = $_POST['GestionFinanciamiento']['valor_financiamiento'];
            $model->forma_pago = $_POST['GestionFinanciamiento']['tipo']; // forma de pago
            $model->entidad_financiera = $_POST['GestionFinanciamiento']['entidad_financiera'];
            $model->observaciones = $_POST['GestionFinanciamiento']['observaciones'];
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            if ($model->save()) {
                //$this->render('proforma',array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo));
                $this->redirect(array('proforma', 'id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo));
            }
        }
        $this->render('negociacion', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo));
    }

    public function actionNegociacionup($id_informacion = NULL, $id_vehiculo = NULL) {
        
        if (isset($_POST['GestionFinanciamiento'])) {
//            echo '<pre>';
//            print_r($_POST);
//            echo '</pre>';
//            die();
            $model = new GestionFinanciamiento;
            $model->attributes = $_POST['GestionFinanciamiento'];
            $model->precio_vehiculo = $_POST['GestionFinanciamiento']['precio'];
            $model->tasa = $_POST['GestionFinanciamiento']['tasa'];
            $model->plazos = $_POST['GestionFinanciamiento']['plazo'];
            $model->seguro = $_POST['GestionFinanciamiento']['seguro'];
            $model->valor_financiamiento = $_POST['GestionFinanciamiento']['valor_financiamiento'];
            $model->forma_pago = $_POST['GestionFinanciamiento']['tipo']; // forma de pago
            $model->entidad_financiera = $_POST['GestionFinanciamiento']['entidad_financiera'];
            $model->observaciones = $_POST['GestionFinanciamiento']['observaciones'];
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            if ($model->save()) {
                //$this->render('proforma',array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo));
                $this->redirect(array('proforma', 'id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo));
            }
        }
        $this->render('negociacionup', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo));
    }
    
    public function actionNegociacionex($id_informacion = NULL, $id_vehiculo = NULL) {
        
        if (isset($_POST['GestionFinanciamiento'])) {
//            echo '<pre>';
//            print_r($_POST);
//            echo '</pre>';
//            die();
            $model = new GestionFinanciamiento;
            $model->attributes = $_POST['GestionFinanciamiento'];
            $model->precio_vehiculo = $_POST['GestionFinanciamiento']['precio'];
            $model->tasa = $_POST['GestionFinanciamiento']['tasa'];
            $model->plazos = $_POST['GestionFinanciamiento']['plazo'];
            $model->seguro = $_POST['GestionFinanciamiento']['seguro'];
            $model->valor_financiamiento = $_POST['GestionFinanciamiento']['valor_financiamiento'];
            $model->forma_pago = $_POST['GestionFinanciamiento']['tipo']; // forma de pago
            $model->entidad_financiera = $_POST['GestionFinanciamiento']['entidad_financiera'];
            $model->observaciones = $_POST['GestionFinanciamiento']['observaciones'];
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            if ($model->save()) {
                //$this->render('proforma',array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo));
                $this->redirect(array('proforma', 'id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo));
            }
        }
        $this->render('negociacionex', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo));
    }

    public function actionModProforma($id_financiamiento = NULL) {
        //die('enter modificar');
        //$model = $this->loadModelFinanciamiento($id_financiamiento);

        if (isset($_POST['GestionFinanciamiento'])) {
//              echo '<pre>';
//              print_r($_POST);
//              echo '</pre>';
//              die(); 
            $model = $this->loadModelFinanciamiento($_POST['GestionFinanciamiento1']['id_financiamiento']);
            $currencys = array("$");
            $currencys2 = array(".");
            $result = FALSE;
            $numCotizaciones = $_POST['options-cont'];
            $tipoFinanciamiento = $_POST['GestionFinanciamiento1']['tipo_financiamiento'];

            if ($tipoFinanciamiento == 0) {// financiamiento al contado
                $model = new GestionFinanciamiento;
                $model->attributes = $_POST['GestionFinanciamiento1'];

                $precio_vehiculo = str_replace(',', "", $_POST['GestionFinanciamiento1']['precio_contado']);
                $precio_vehiculo = str_replace('.', ",", $precio_vehiculo);
                $precio_vehiculo = (int) str_replace('$', "", $precio_vehiculo);

                $seguro = str_replace(',', "", $_POST['GestionFinanciamiento1']['seguro_contado']);
                $seguro = str_replace('.', ",", $seguro);
                $seguro = (int) str_replace('$', "", $seguro);

                $model->precio_vehiculo = $precio_vehiculo;
                $model->ts = $_POST['GestionFinanciamiento1']['tiempo_seguro_contado'];
                $model->seguro = $seguro;
                $model->forma_pago = $_POST['GestionFinanciamiento']['tipo']; // forma de pago
                $model->observaciones = $_POST['GestionFinanciamiento1']['observaciones_contado'];
                date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
                $model->fecha = date("Y-m-d H:i:s");
            } else {
                $model = new GestionFinanciamiento;
                $model->attributes = $_POST['GestionFinanciamiento1'];
                $precio_vehiculo = str_replace(',', "", $_POST['GestionFinanciamiento1']['precio']);
                $precio_vehiculo = str_replace('.', ",", $precio_vehiculo);
                $precio_vehiculo = (int) str_replace('$', "", $precio_vehiculo);

                $precio_entrada = str_replace(',', "", $_POST['GestionFinanciamiento1']['entrada']);
                $precio_entrada = str_replace('.', ",", $precio_entrada);
                $precio_entrada = (int) str_replace('$', "", $precio_entrada);

                $precio_financiamiento = str_replace(',', "", $_POST['GestionFinanciamiento1']['valor_financiamiento']);
                $precio_financiamiento = str_replace('.', ",", $precio_financiamiento);
                $precio_financiamiento = (int) str_replace('$', "", $precio_financiamiento);

                $precio_cuota_mensual = str_replace(',', "", $_POST['GestionFinanciamiento1']['cuota_mensual']);
                $precio_cuota_mensual = str_replace('.', ",", $precio_cuota_mensual);
                $precio_cuota_mensual = (int) str_replace('$', "", $precio_cuota_mensual);

                $seguro = str_replace(',', "", $_POST['GestionFinanciamiento1']['seguro']);
                $seguro = str_replace('.', ",", $seguro);
                $seguro = (int) str_replace('$', "", $seguro);

                $model->precio_vehiculo = $precio_vehiculo;
                $model->cuota_inicial = $precio_entrada;
                $model->tasa = (int) $_POST['GestionFinanciamiento1']['tasa'];
                $model->plazos = $_POST['GestionFinanciamiento1']['plazo'];
                $model->ts = $_POST['GestionFinanciamiento1']['tiempo_seguro'];
                $model->valor_financiamiento = $precio_financiamiento;
                $model->cuota_mensual = $precio_cuota_mensual;
                $model->seguro = $seguro;
                $model->forma_pago = $_POST['GestionFinanciamiento']['tipo']; // forma de pago
                $model->entidad_financiera = $_POST['GestionFinanciamiento1']['entidad_financiera'];
                $model->observaciones = $_POST['GestionFinanciamiento1']['observaciones'];
                date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
                $model->fecha = date("Y-m-d H:i:s");
            }




            if (isset($_POST['accesorios']) && !empty($_POST['accesorios'])) {
                //die('enter accesorios');
                $counter = $_POST['accesorios'];
                $accesorios = '';
                foreach ($counter as $key => $entry) {
                    $accesorios .= $entry . '@';
                }
                $accesoHorios = substr($accesorios, 0, -1);
                $con = Yii::app()->db;
                $sql = "UPDATE gestion_vehiculo SET accesorios = '{$accesorios}' WHERE id = {$_POST['GestionFinanciamiento1']['id_vehiculo']}";
                $request = $con->createCommand($sql)->query();
            }
            //die('before save:');
            if ($model->save()) {
                $con = Yii::app()->db;
                $sql = "UPDATE gestion_diaria SET paso = '7' WHERE id_informacion = {$_POST['GestionFinanciamiento1']['id_informacion']}";
                $request = $con->createCommand($sql)->query();


                if (isset($_POST['GestionFinanciamiento2']) && $numCotizaciones == 3) {// segundacotizacion
                    $model2 = new GestionFinanciamientoOp;
                    $model2->attributes = $_POST['GestionFinanciamiento2'];

                    $precio_vehiculo2 = str_replace(',', "", $_POST['GestionFinanciamiento2']['precio']);
                    $precio_vehiculo2 = str_replace('.', ",", $precio_vehiculo2);
                    $precio_vehiculo2 = (int) str_replace('$', "", $precio_vehiculo2);

                    $precio_entrada2 = str_replace(',', "", $_POST['GestionFinanciamiento2']['entrada']);
                    $precio_entrada2 = str_replace('.', ",", $precio_entrada2);
                    $precio_entrada2 = (int) str_replace('$', "", $precio_entrada2);

                    $precio_financiamiento2 = str_replace(',', "", $_POST['GestionFinanciamiento2']['valor_financiamiento']);
                    $precio_financiamiento2 = str_replace('.', ",", $precio_financiamiento2);
                    $precio_financiamiento2 = (int) str_replace('$', "", $precio_financiamiento2);

                    $precio_cuota_mensual2 = str_replace(',', "", $_POST['GestionFinanciamiento2']['cuota_mensual']);
                    $precio_cuota_mensual2 = str_replace('.', ",", $precio_cuota_mensual2);
                    $precio_cuota_mensual2 = (int) str_replace('$', "", $precio_cuota_mensual2);

                    $seguro2 = str_replace(',', "", $_POST['GestionFinanciamiento2']['seguro']);
                    $seguro2 = str_replace('.', ",", $seguro2);
                    $seguro2 = (int) str_replace('$', "", $seguro2);

                    $model2->precio_vehiculo = $precio_vehiculo2;
                    $model2->cuota_inicial = $precio_entrada2;
                    $model2->tasa = (int) $_POST['GestionFinanciamiento1']['tasa'];
                    $model2->plazos = $_POST['GestionFinanciamiento2']['plazo'];
                    $model2->ts = $_POST['GestionFinanciamiento2']['tiempo_seguro'];
                    $model2->valor_financiamiento = $precio_financiamiento2;
                    $model2->cuota_mensual = $precio_cuota_mensual2;
                    $model2->seguro = $seguro2;

                    //$model2->precio_vehiculo = $_POST['GestionFinanciamiento2']['precio'];
                    //$model2->cuota_inicial = $_POST['GestionFinanciamiento2']['entrada'];
                    //$model2->tasa = $_POST['GestionFinanciamiento2']['tasa'];
                    //$model2->plazos = $_POST['GestionFinanciamiento2']['plazo'];
                    //$model2->seguro = $_POST['GestionFinanciamiento2']['seguro'];
                    //$model2->valor_financiamiento = $_POST['GestionFinanciamiento2']['valor_financiamiento'];
                    $model2->forma_pago = $_POST['GestionFinanciamiento']['tipo']; // forma de pago
                    $model2->entidad_financiera = $_POST['GestionFinanciamiento2']['entidad_financiera'];
                    $model2->observaciones = $_POST['GestionFinanciamiento2']['observaciones'];
                    date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
                    $model2->fecha = date("Y-m-d H:i:s");
                    $model2->id_financiamiento = $model->id;
                    $model2->save();
                }
                if (isset($_POST['GestionFinanciamiento3']) && $numCotizaciones == 4) {// tercera cotizacion
                    //die('enter finan3');
                    $model3 = new GestionFinanciamientoOp;
                    $model2->attributes = $_POST['GestionFinanciamiento3'];

                    $precio_vehiculo3 = str_replace(',', "", $_POST['GestionFinanciamiento3']['precio']);
                    $precio_vehiculo3 = str_replace('.', ",", $precio_vehiculo3);
                    $precio_vehiculo3 = (int) str_replace('$', "", $precio_vehiculo3);

                    $precio_entrada3 = str_replace(',', "", $_POST['GestionFinanciamiento3']['entrada']);
                    $precio_entrada3 = str_replace('.', ",", $precio_entrada3);
                    $precio_entrada3 = (int) str_replace('$', "", $precio_entrada3);

                    $precio_financiamiento3 = str_replace(',', "", $_POST['GestionFinanciamiento3']['valor_financiamiento']);
                    $precio_financiamiento3 = str_replace('.', ",", $precio_financiamiento3);
                    $precio_financiamiento3 = (int) str_replace('$', "", $precio_financiamiento3);

                    $precio_cuota_mensual3 = str_replace(',', "", $_POST['GestionFinanciamiento3']['cuota_mensual']);
                    $precio_cuota_mensual3 = str_replace('.', ",", $precio_cuota_mensual3);
                    $precio_cuota_mensual3 = (int) str_replace('$', "", $precio_cuota_mensual3);

                    $seguro3 = str_replace(',', "", $_POST['GestionFinanciamiento3']['seguro']);
                    $seguro3 = str_replace('.', ",", $seguro3);
                    $seguro3 = (int) str_replace('$', "", $seguro3);

                    $model3->precio_vehiculo = $precio_vehiculo3;
                    $model3->cuota_inicial = $precio_entrada3;
                    $model3->tasa = (int) $_POST['GestionFinanciamiento1']['tasa'];
                    $model3->plazos = $_POST['GestionFinanciamiento3']['plazo'];
                    $model3->ts = $_POST['GestionFinanciamiento3']['tiempo_seguro'];
                    $model3->valor_financiamiento = $precio_financiamiento3;
                    $model3->cuota_mensual = $precio_cuota_mensual3;
                    $model3->seguro = $seguro3;
                    $model3->forma_pago = $_POST['GestionFinanciamiento']['tipo']; // forma de pago
                    $model3->entidad_financiera = $_POST['GestionFinanciamiento3']['entidad_financiera'];
                    $model3->observaciones = $_POST['GestionFinanciamiento3']['observaciones'];
                    date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
                    $model3->fecha = date("Y-m-d H:i:s");
                    $model3->id_financiamiento = $model->id;
                    $model3->save();
                }
                $result = TRUE;
                $arr = array('result' => $result, 'id' => $model->id);
                echo json_encode($arr);
            }
            //die('no save');
        }
    }

    /**
     * function save information Ajax mode for a new vehicle quote
     * @param type $id_informacion
     * @param type $id_vehiculo
     * return boolean TRUE if save success or FALSE in fail
     */
    public function actionNegociacionAjax($id_informacion = NULL, $id_vehiculo = NULL) {
        if (isset($_POST['GestionFinanciamiento'])) {
//              echo '<pre>';
//              print_r($_POST);
//              echo '</pre>';
//              die(); 

            $currencys = array("$");
            $currencys2 = array(".");
            $result = FALSE;
            $numCotizaciones = $_POST['options-cont'];
            $tipoFinanciamiento = $_POST['GestionFinanciamiento1']['tipo_financiamiento'];

            $model = new GestionFinanciamiento;

            $id_informacion = $_POST['GestionFinanciamiento1']['id_informacion'];
            $criteria = new CDbCriteria(array(
                'condition' => "id_informacion={$id_informacion}"
            ));
            $td = GestionFinanciamiento::model()->count($criteria);
            //die('count td:'.$td);
            if ($td > 0) {
                $model->order = 2;
            }

            if ($tipoFinanciamiento == 0) {// financiamiento al contado
                $model->attributes = $_POST['GestionFinanciamiento1'];
                $model->id_pdf = $this->getLastProforma();

                $precio_vehiculo = str_replace(',', "", $_POST['GestionFinanciamiento1']['precio_contado']);
                $precio_vehiculo = str_replace('.', ",", $precio_vehiculo);
                $precio_vehiculo = (int) str_replace('$', "", $precio_vehiculo);
                
                $precio_normal = str_replace(',', "", $_POST['precio_normal']);
                $precio_normal = str_replace('.', ",", $precio_normal);
                $precio_normal = (int) str_replace('$', "", $precio_normal);

                $seguro = str_replace(',', "", $_POST['GestionFinanciamiento1']['seguro_contado']);
                $seguro = str_replace('.', ",", $seguro);
                $seguro = (int) str_replace('$', "", $seguro);

                $model->precio_vehiculo = $precio_vehiculo;
                $model->precio_normal = $precio_normal;
                $model->ts = $_POST['GestionFinanciamiento1']['tiempo_seguro_contado'];
                if (!empty($_POST['GestionFinanciamiento1']['tiempo_seguro_contado']) && $_POST['GestionFinanciamiento1']['tiempo_seguro_contado'] != 0) {
                    $precio_vehiculo = str_replace(',', "", $_POST['GestionFinanciamiento1']['precio_contado_total']);
                    $precio_vehiculo = str_replace('.', ",", $precio_vehiculo);
                    $precio_vehiculo = (int) str_replace('$', "", $precio_vehiculo);
                    $model->precio_vehiculo = $precio_vehiculo;
                }

                $model->seguro = $seguro;
                $model->forma_pago = $_POST['GestionFinanciamiento']['tipo']; // forma de pago
                $model->observaciones = $_POST['GestionFinanciamiento1']['observaciones_contado'];
                date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
                $model->fecha = date("Y-m-d H:i:s");
            } else {
                $model = new GestionFinanciamiento;
                $model->attributes = $_POST['GestionFinanciamiento1'];
                $model->id_pdf = $this->getLastProforma();
                $precio_vehiculo = str_replace(',', "", $_POST['GestionFinanciamiento1']['precio']);
                $precio_vehiculo = str_replace('.', ",", $precio_vehiculo);
                $precio_vehiculo = (int) str_replace('$', "", $precio_vehiculo);

                $precio_entrada = str_replace(',', "", $_POST['GestionFinanciamiento1']['entrada']);
                $precio_entrada = str_replace('.', ",", $precio_entrada);
                $precio_entrada = (int) str_replace('$', "", $precio_entrada);

                $precio_financiamiento = str_replace(',', "", $_POST['GestionFinanciamiento1']['valor_financiamiento']);
                $precio_financiamiento = str_replace('.', ",", $precio_financiamiento);
                $precio_financiamiento = (int) str_replace('$', "", $precio_financiamiento);

                $precio_cuota_mensual = str_replace(',', "", $_POST['GestionFinanciamiento1']['cuota_mensual']);
                $precio_cuota_mensual = str_replace('.', ",", $precio_cuota_mensual);
                $precio_cuota_mensual = (int) str_replace('$', "", $precio_cuota_mensual);

                $seguro = str_replace(',', "", $_POST['GestionFinanciamiento1']['seguro']);
                $seguro = str_replace('.', ",", $seguro);
                $seguro = (int) str_replace('$', "", $seguro);

                $model->precio_vehiculo = $precio_vehiculo;
                $model->cuota_inicial = $precio_entrada;
                $model->tasa = (int) $_POST['GestionFinanciamiento1']['tasa'];
                $model->plazos = $_POST['GestionFinanciamiento1']['plazo'];
                $model->ts = $_POST['GestionFinanciamiento1']['tiempo_seguro'];
                $model->valor_financiamiento = $precio_financiamiento;
                $model->cuota_mensual = $precio_cuota_mensual;
                $model->seguro = $seguro;
                $model->forma_pago = $_POST['GestionFinanciamiento']['tipo']; // forma de pago
                $model->entidad_financiera = $_POST['GestionFinanciamiento1']['entidad_financiera'];
                $model->observaciones = $_POST['GestionFinanciamiento1']['observaciones'];
                date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
                $model->fecha = date("Y-m-d H:i:s");
            }
            if (isset($_POST['accesorios']) && !empty($_POST['accesorios'])) {
                //die('enter accesorios');
//                $counter = $_POST['accesorios'];
//                $accesorios = '';
//                foreach ($counter as $key => $entry) {
//                    $accesorios .= $entry . '@';
//                }
                $accesorios = $_POST['GestionFinanciamiento1']['acc1'];
                $accesorios = substr($accesorios, 0, -1);
                $con = Yii::app()->db;
                $sql = "UPDATE gestion_vehiculo SET accesorios = '{$accesorios}' WHERE id = {$_POST['GestionFinanciamiento1']['id_vehiculo']}";
                //die('sql: '.$sql);
                $request = $con->createCommand($sql)->query();
            }
            //die('before save:');
            if ($model->save()) {
                $con = Yii::app()->db;
                $sql = "UPDATE gestion_diaria SET paso = '7' WHERE id_informacion = {$_POST['GestionFinanciamiento1']['id_informacion']}";
                $request = $con->createCommand($sql)->query();

                $precio_financiamiento = '';
                $precio_financiamiento2 = '';
                $precio_financiamiento3 = '';
                $precio_vehiculo = '';
                $precio_entrada = '';
                $precio_vehiculo2 = '';
                $precio_entrada2 = '';
                $precio_vehiculo3 = '';
                $precio_entrada3 = '';
                $precio_cuota_mensual = '';
                $precio_cuota_mensual2 = '';
                $precio_cuota_mensual3 = '';
                if (isset($_POST['GestionFinanciamiento2']) && $numCotizaciones == 3) {// segundacotizacion
                    //die('enter cotizacion 3');
                    $model2 = new GestionFinanciamientoOp;
                    $model2->attributes = $_POST['GestionFinanciamiento2'];

                    if (isset($_POST['GestionFinanciamiento2']['precio'])) {
                        $precio_vehiculo2 = str_replace(',', "", $_POST['GestionFinanciamiento2']['precio']);
                        $precio_vehiculo2 = str_replace('.', ",", $precio_vehiculo2);
                        $precio_vehiculo2 = (int) str_replace('$', "", $precio_vehiculo2);
                    }


                    if (isset($_POST['GestionFinanciamiento2']['precio_contado'])) {
                        $precio_vehiculo2 = str_replace(',', "", $_POST['GestionFinanciamiento2']['precio_contado']);
                        $precio_vehiculo2 = str_replace('.', ",", $precio_vehiculo2);
                        $precio_vehiculo2 = (int) str_replace('$', "", $precio_vehiculo2);
                    }
                    if (isset($_POST['GestionFinanciamiento2']['tiempo_seguro_contado']) && !empty($_POST['GestionFinanciamiento2']['tiempo_seguro_contado'])) {
                        $precio_vehiculo2 = str_replace(',', "", $_POST['GestionFinanciamiento2']['precio_contado_total']);
                        $precio_vehiculo2 = str_replace('.', ",", $precio_vehiculo2);
                        $precio_vehiculo2 = (int) str_replace('$', "", $precio_vehiculo2);
                    }
                    if (isset($_POST['GestionFinanciamiento2']['tiempo_seguro_contado']) && !empty($_POST['GestionFinanciamiento2']['tiempo_seguro_contado'])) {
                        $model2->ts = $_POST['GestionFinanciamiento2']['tiempo_seguro_contado'];
                    }


                    if (isset($_POST['GestionFinanciamiento2']['entrada'])) {
                        $precio_entrada2 = str_replace(',', "", $_POST['GestionFinanciamiento2']['entrada']);
                        $precio_entrada2 = str_replace('.', ",", $precio_entrada2);
                        $precio_entrada2 = (int) str_replace('$', "", $precio_entrada2);
                    }

                    if (isset($_POST['GestionFinanciamiento2']['valor_financiamiento'])) {
                        $precio_financiamiento2 = str_replace(',', "", $_POST['GestionFinanciamiento2']['valor_financiamiento']);
                        $precio_financiamiento2 = str_replace('.', ",", $precio_financiamiento2);
                        $precio_financiamiento2 = (int) str_replace('$', "", $precio_financiamiento2);
                    }

                    if (isset($_POST['GestionFinanciamiento2']['cuota_mensual'])) {
                        $precio_cuota_mensual2 = str_replace(',', "", $_POST['GestionFinanciamiento2']['cuota_mensual']);
                        $precio_cuota_mensual2 = str_replace('.', ",", $precio_cuota_mensual2);
                        $precio_cuota_mensual2 = (int) str_replace('$', "", $precio_cuota_mensual2);
                    }

                    if (isset($_POST['GestionFinanciamiento2']['seguro'])) {
                        $seguro2 = str_replace(',', "", $_POST['GestionFinanciamiento2']['seguro']);
                        $seguro2 = str_replace('.', ",", $seguro2);
                        $seguro2 = (int) str_replace('$', "", $seguro2);
                    }

                    if (isset($_POST['GestionFinanciamiento2']['seguro_contado'])) {
                        $seguro2 = str_replace(',', "", $_POST['GestionFinanciamiento2']['seguro_contado']);
                        $seguro2 = str_replace('.', ",", $seguro2);
                        $seguro2 = (int) str_replace('$', "", $seguro2);
                    }

                    $model2->precio_vehiculo = $precio_vehiculo2;
                    $model2->cuota_inicial = $precio_entrada2;
                    if (isset($_POST['GestionFinanciamiento1']['tasa']))
                        $model2->tasa = (int) $_POST['GestionFinanciamiento1']['tasa'];
                    if (isset($_POST['GestionFinanciamiento2']['plazo']))
                        $model2->plazos = $_POST['GestionFinanciamiento2']['plazo'];
                    if (isset($_POST['GestionFinanciamiento2']['tiempo_seguro']))
                        $model2->ts = $_POST['GestionFinanciamiento2']['tiempo_seguro'];

                    $model2->valor_financiamiento = $precio_financiamiento2;
                    $model2->cuota_mensual = $precio_cuota_mensual2;
                    $model2->seguro = $seguro2;

                    //$model2->precio_vehiculo = $_POST['GestionFinanciamiento2']['precio'];
                    //$model2->cuota_inicial = $_POST['GestionFinanciamiento2']['entrada'];
                    //$model2->tasa = $_POST['GestionFinanciamiento2']['tasa'];
                    //$model2->plazos = $_POST['GestionFinanciamiento2']['plazo'];
                    //$model2->seguro = $_POST['GestionFinanciamiento2']['seguro'];
                    //$model2->valor_financiamiento = $_POST['GestionFinanciamiento2']['valor_financiamiento'];
                    $model2->forma_pago = $_POST['GestionFinanciamiento']['tipo']; // forma de pago
                    if (isset($_POST['GestionFinanciamiento2']['entidad_financiera']))
                        $model2->entidad_financiera = $_POST['GestionFinanciamiento2']['entidad_financiera'];
                    if (isset($_POST['GestionFinanciamiento2']['observaciones']))
                        $model2->observaciones = $_POST['GestionFinanciamiento2']['observaciones'];
                    if (isset($_POST['GestionFinanciamiento2']['observaciones_contado']))
                        $model2->observaciones = $_POST['GestionFinanciamiento2']['observaciones_contado'];

                    $model2->saldo_financiar = $precio_financiamiento2;
                    date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
                    $model2->fecha = date("Y-m-d H:i:s");
                    $model2->id_financiamiento = $model->id;
                    $model2->num_cotizacion = 3;

                    if (isset($_POST['GestionFinanciamiento1']['acc2']) && !empty($_POST['GestionFinanciamiento1']['acc2'])) {
                        $model2->accesorios = substr($_POST['GestionFinanciamiento1']['acc2'], 0, -1);
                    }
                    $model2->save();
                }
                if (isset($_POST['GestionFinanciamiento3']) && $numCotizaciones == 4) {// tercera cotizacion
                    //die('enter finan3');
                    $model2 = new GestionFinanciamientoOp;
                    $model2->attributes = $_POST['GestionFinanciamiento2'];

                    if (isset($_POST['GestionFinanciamiento2']['precio'])) {
                        $precio_vehiculo2 = str_replace(',', "", $_POST['GestionFinanciamiento2']['precio']);
                        $precio_vehiculo2 = str_replace('.', ",", $precio_vehiculo2);
                        $precio_vehiculo2 = (int) str_replace('$', "", $precio_vehiculo2);
                    }
                    if (isset($_POST['GestionFinanciamiento2']['precio_contado'])) {
                        $precio_vehiculo2 = str_replace(',', "", $_POST['GestionFinanciamiento2']['precio_contado']);
                        $precio_vehiculo2 = str_replace('.', ",", $precio_vehiculo2);
                        $precio_vehiculo2 = (int) str_replace('$', "", $precio_vehiculo2);
                    }
                    if (isset($_POST['GestionFinanciamiento2']['tiempo_seguro_contado']) && !empty($_POST['GestionFinanciamiento2']['tiempo_seguro_contado'])) {
                        $precio_vehiculo2 = str_replace(',', "", $_POST['GestionFinanciamiento2']['precio_contado_total']);
                        $precio_vehiculo2 = str_replace('.', ",", $precio_vehiculo2);
                        $precio_vehiculo2 = (int) str_replace('$', "", $precio_vehiculo2);
                    }

                    if (isset($_POST['GestionFinanciamiento2']['tiempo_seguro_contado']) && !empty($_POST['GestionFinanciamiento2']['tiempo_seguro_contado'])) {
                        $model2->ts = $_POST['GestionFinanciamiento2']['tiempo_seguro_contado'];
                    }

                    if (isset($_POST['GestionFinanciamiento2']['entrada'])) {
                        $precio_entrada2 = str_replace(',', "", $_POST['GestionFinanciamiento2']['entrada']);
                        $precio_entrada2 = str_replace('.', ",", $precio_entrada2);
                        $precio_entrada2 = (int) str_replace('$', "", $precio_entrada2);
                    }

                    if (isset($_POST['GestionFinanciamiento2']['valor_financiamiento'])) {
                        $precio_financiamiento2 = str_replace(',', "", $_POST['GestionFinanciamiento2']['valor_financiamiento']);
                        $precio_financiamiento2 = str_replace('.', ",", $precio_financiamiento2);
                        $precio_financiamiento2 = (int) str_replace('$', "", $precio_financiamiento2);
                    }

                    if (isset($_POST['GestionFinanciamiento2']['cuota_mensual'])) {
                        $precio_cuota_mensual2 = str_replace(',', "", $_POST['GestionFinanciamiento2']['cuota_mensual']);
                        $precio_cuota_mensual2 = str_replace('.', ",", $precio_cuota_mensual2);
                        $precio_cuota_mensual2 = (int) str_replace('$', "", $precio_cuota_mensual2);
                    }

                    if (isset($_POST['GestionFinanciamiento2']['seguro'])) {
                        $seguro2 = str_replace(',', "", $_POST['GestionFinanciamiento2']['seguro']);
                        $seguro2 = str_replace('.', ",", $seguro2);
                        $seguro2 = (int) str_replace('$', "", $seguro2);
                    }

                    if (isset($_POST['GestionFinanciamiento2']['seguro_contado'])) {
                        $seguro2 = str_replace(',', "", $_POST['GestionFinanciamiento2']['seguro_contado']);
                        $seguro2 = str_replace('.', ",", $seguro2);
                        $seguro2 = (int) str_replace('$', "", $seguro2);
                    }

                    $model2->precio_vehiculo = $precio_vehiculo2;
                    $model2->cuota_inicial = $precio_entrada2;
                    if (isset($_POST['GestionFinanciamiento1']['tasa']))
                        $model2->tasa = (int) $_POST['GestionFinanciamiento1']['tasa'];
                    if (isset($_POST['GestionFinanciamiento2']['plazo']))
                        $model2->plazos = $_POST['GestionFinanciamiento2']['plazo'];
                    if (isset($_POST['GestionFinanciamiento2']['tiempo_seguro']))
                        $model2->ts = $_POST['GestionFinanciamiento2']['tiempo_seguro'];

                    $model2->valor_financiamiento = $precio_financiamiento2;
                    $model2->cuota_mensual = $precio_cuota_mensual2;
                    $model2->seguro = $seguro2;

                    //$model2->precio_vehiculo = $_POST['GestionFinanciamiento2']['precio'];
                    //$model2->cuota_inicial = $_POST['GestionFinanciamiento2']['entrada'];
                    //$model2->tasa = $_POST['GestionFinanciamiento2']['tasa'];
                    //$model2->plazos = $_POST['GestionFinanciamiento2']['plazo'];
                    //$model2->seguro = $_POST['GestionFinanciamiento2']['seguro'];
                    //$model2->valor_financiamiento = $_POST['GestionFinanciamiento2']['valor_financiamiento'];
                    $model2->forma_pago = $_POST['GestionFinanciamiento']['tipo']; // forma de pago
                    if (isset($_POST['GestionFinanciamiento2']['entidad_financiera']))
                        $model2->entidad_financiera = $_POST['GestionFinanciamiento2']['entidad_financiera'];
                    if (isset($_POST['GestionFinanciamiento2']['observaciones']))
                        $model2->observaciones = $_POST['GestionFinanciamiento2']['observaciones'];
                    if (isset($_POST['GestionFinanciamiento2']['observaciones_contado']))
                        $model2->observaciones = $_POST['GestionFinanciamiento2']['observaciones_contado'];

                    $model2->saldo_financiar = $precio_financiamiento2;
                    date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
                    $model2->fecha = date("Y-m-d H:i:s");
                    $model2->id_financiamiento = $model->id;
                    $model2->num_cotizacion = 3;

                    if (isset($_POST['GestionFinanciamiento1']['acc2']) && !empty($_POST['GestionFinanciamiento1']['acc2'])) {
                        $model2->accesorios = substr($_POST['GestionFinanciamiento1']['acc2'], 0, -1);
                    }
                    $model2->save();


                    $model3 = new GestionFinanciamientoOp;
                    $model3->attributes = $_POST['GestionFinanciamiento3'];

                    if (isset($_POST['GestionFinanciamiento3']['precio'])) {
                        $precio_vehiculo3 = str_replace(',', "", $_POST['GestionFinanciamiento3']['precio']);
                        $precio_vehiculo3 = str_replace('.', ",", $precio_vehiculo3);
                        $precio_vehiculo3 = (int) str_replace('$', "", $precio_vehiculo3);
                    }

                    if (isset($_POST['GestionFinanciamiento3']['precio_contado'])) {
                        $precio_vehiculo3 = str_replace(',', "", $_POST['GestionFinanciamiento3']['precio_contado']);
                        $precio_vehiculo3 = str_replace('.', ",", $precio_vehiculo3);
                        $precio_vehiculo3 = (int) str_replace('$', "", $precio_vehiculo3);
                    }

                    if (isset($_POST['GestionFinanciamiento3']['tiempo_seguro_contado']) && !empty($_POST['GestionFinanciamiento3']['tiempo_seguro_contado'])) {
                        $precio_vehiculo3 = str_replace(',', "", $_POST['GestionFinanciamiento3']['precio_contado_total']);
                        $precio_vehiculo3 = str_replace('.', ",", $precio_vehiculo3);
                        $precio_vehiculo3 = (int) str_replace('$', "", $precio_vehiculo3);
                    }

                    if (isset($_POST['GestionFinanciamiento3']['tiempo_seguro_contado']) && !empty($_POST['GestionFinanciamiento3']['tiempo_seguro_contado'])) {
                        $model3->ts = $_POST['GestionFinanciamiento3']['tiempo_seguro_contado'];
                    }

                    if (isset($_POST['GestionFinanciamiento3']['entrada'])) {
                        $precio_entrada3 = str_replace(',', "", $_POST['GestionFinanciamiento3']['entrada']);
                        $precio_entrada3 = str_replace('.', ",", $precio_entrada3);
                        $precio_entrada3 = (int) str_replace('$', "", $precio_entrada3);
                    }

                    if (isset($_POST['GestionFinanciamiento3']['valor_financiamiento'])) {
                        $precio_financiamiento3 = str_replace(',', "", $_POST['GestionFinanciamiento3']['valor_financiamiento']);
                        $precio_financiamiento3 = str_replace('.', ",", $precio_financiamiento3);
                        $precio_financiamiento3 = (int) str_replace('$', "", $precio_financiamiento3);
                    }

                    if (isset($_POST['GestionFinanciamiento3']['cuota_mensual'])) {
                        $precio_cuota_mensual3 = str_replace(',', "", $_POST['GestionFinanciamiento3']['cuota_mensual']);
                        $precio_cuota_mensual3 = str_replace('.', ",", $precio_cuota_mensual3);
                        $precio_cuota_mensual3 = (int) str_replace('$', "", $precio_cuota_mensual3);
                    }

                    if (isset($_POST['GestionFinanciamiento3']['seguro'])) {
                        $seguro3 = str_replace(',', "", $_POST['GestionFinanciamiento3']['seguro']);
                        $seguro3 = str_replace('.', ",", $seguro3);
                        $seguro3 = (int) str_replace('$', "", $seguro3);
                    }

                    if (isset($_POST['GestionFinanciamiento3']['seguro_contado'])) {
                        $seguro3 = str_replace(',', "", $_POST['GestionFinanciamiento3']['seguro_contado']);
                        $seguro3 = str_replace('.', ",", $seguro3);
                        $seguro3 = (int) str_replace('$', "", $seguro3);
                    }

                    $model3->precio_vehiculo = $precio_vehiculo3;
                    $model3->cuota_inicial = $precio_entrada3;

                    if (isset($_POST['GestionFinanciamiento1']['tasa']))
                        $model3->tasa = (int) $_POST['GestionFinanciamiento1']['tasa'];
                    if (isset($_POST['GestionFinanciamiento3']['plazo']))
                        $model3->plazos = $_POST['GestionFinanciamiento3']['plazo'];
                    if (isset($_POST['GestionFinanciamiento3']['tiempo_seguro']))
                        $model3->ts = $_POST['GestionFinanciamiento3']['tiempo_seguro'];

                    $model3->valor_financiamiento = $precio_financiamiento3;
                    $model3->cuota_mensual = $precio_cuota_mensual3;
                    $model3->seguro = $seguro3;
                    $model3->forma_pago = $_POST['GestionFinanciamiento']['tipo']; // forma de pago

                    if (isset($_POST['GestionFinanciamiento3']['entidad_financiera']))
                        $model3->entidad_financiera = $_POST['GestionFinanciamiento3']['entidad_financiera'];
                    if (isset($_POST['GestionFinanciamiento3']['observaciones']))
                        $model3->observaciones = $_POST['GestionFinanciamiento3']['observaciones'];
                    if (isset($_POST['GestionFinanciamiento3']['observaciones_contado']))
                        $model3->observaciones = $_POST['GestionFinanciamiento3']['observaciones_contado'];

                    $model3->saldo_financiar = $precio_financiamiento3;
                    date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
                    $model3->fecha = date("Y-m-d H:i:s");
                    $model3->id_financiamiento = $model->id;
                    $model3->num_cotizacion = 4;

                    if (isset($_POST['GestionFinanciamiento1']['acc3']) && !empty($_POST['GestionFinanciamiento1']['acc3'])) {
                        $model3->accesorios = substr($_POST['GestionFinanciamiento1']['acc3'], 0, -1);
                    }
                    $model3->save();
                }
                $result = TRUE;
                $arr = array('result' => $result, 'id' => $model->id);
                echo json_encode($arr);
            }
            //die('no save');
        }
    }

    /**
     * Generate a pdf document with vehicle quote and client's information
     * @param type $id_informacion
     * @param type $id_vehiculo
     */
    public function actionHojaentrega($id_informacion, $id_vehiculo) {
        $this->layout = '//layouts/call-print';
        //die('enter prof');
        $con = Yii::app()->db;
        $sql = "SELECT gi.*, ge.* FROM gestion_entrega ge 
INNER JOIN gestion_informacion gi on gi.id = ge.id_informacion
WHERE ge.id_informacion = {$id_informacion} ORDER BY ge.id DESC limit 1";
//die('sql:'.$sql);
        $request = $con->createCommand($sql)->queryAll();
        $num_entrega = $this->getLastEntrega();
        $hoja_entrega = new GestionHojaEntrega;
        $hoja_entrega->id_vehiculo = $id_vehiculo;
        date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
        $hoja_entrega->fecha = date("Y-m-d H:i:s");
        $hoja_entrega->save();
        # mPDF        

        # You can easily override default constructor's params
        $mPDF1 = Yii::app()->ePdf->mpdf('', 'A4');
        $mPDF1->SetTitle('Hoja de Entrega');

        //$mPDF1->WriteHTML($this->render('pdf2', array('data' => $request), true));
        # Load a stylesheet
        $stylesheet = file_get_contents(Yii::getPathOfAlias('webroot.bootstrap.css') . '/bootstrap.css');
        $mPDF1->WriteHTML($stylesheet, 1);
        # renderPartial (only 'view' of current controller)
        $mPDF1->WriteHTML($this->renderPartial('hoja_entrega', array('data' => $request, 'id_hoja' => $num_entrega, 'id_informacion' => $id_informacion), true));

        # Renders image
        //$mPDF1->WriteHTML(CHtml::image(Yii::getPathOfAlias('webroot.css') . '/bg.gif' ));
        # Outputs ready PDF
        $mPDF1->Output('hoja-de-entrega.pdf', 'I');
    }

    /**
     * Generate a pdf document with vehicle accesories and charts
     * @param type $id_informacion
     * @param type $id_vehiculo
     */
    public function actionProforma($id_informacion, $id_vehiculo) {
        $this->layout = '//layouts/call-print';
        $id_asesor = Yii::app()->user->getId();
        $responsable_id = $this->getResponsableId($id_informacion);
        $nombre_responsable = $this->getResponsableNombres($responsable_id);
        $nombre_responsable = mb_convert_case($nombre_responsable, MB_CASE_UPPER, "UTF-8");

        $concesionarioid = $this->getConcesionarioDealerId($id_asesor);
        $nombreproforma = $this->getNombreProforma($concesionarioid);
        $ruc = $this->getConcesionarioGrupoRuc($responsable_id);
        //die('conc id: '.$concesionarioid);
        $telefono = $this->getAsesorTelefono($id_asesor);
        $celular = $this->getAsesorCelular($id_asesor);
        $codigo_asesor = $this->getAsesorCodigo($id_asesor);
//echo $this->getResponsable($id_asesor);
        //die('enter prof');
        $con = Yii::app()->db;
        $sql = "SELECT gf.forma_pago, gf.precio_vehiculo,gf.seguro, gf.valor_financiamiento, gf.cuota_inicial, gf.saldo_financiar, gf.plazos, 
gf.entidad_financiera, gf.id as id_financiamiento, gf.ts, gf.observaciones, gf.cuota_mensual, gi.nombres, gi.apellidos, gi.direccion, gi.celular, gi.telefono_casa, 
gi.responsable, gv.modelo, gv.version, gv.accesorios
FROM gestion_financiamiento gf 
INNER JOIN gestion_informacion gi ON gi.id =  gf.id_informacion 
INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo 
WHERE gf.id_informacion = {$id_informacion} AND gf.id_vehiculo = {$id_vehiculo} ORDER BY gf.id DESC LIMIT 1";
//die('sql:'.$sql);
        $request = $con->createCommand($sql)->queryAll();
        $num_proforma = $this->getLastProforma();
        // grabar numero de proforma en la base de datos segun idvehiculo
        $con = Yii::app()->db;
        $sql = "UPDATE gestion_vehiculo SET num_pdf = {$num_proforma} WHERE id_informacion = {$id_informacion} AND id = {$id_vehiculo}";
        $req = $con->createCommand($sql)->query();

        //die('num proforma: '.$num_proforma);
        //die('id vehiculo: ' . $id_vehiculo);
        $proforma = new GestionProforma;
        $proforma->id_vehiculo = $id_vehiculo;
        $proforma->id_informacion = $id_informacion;
        date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
        $proforma->fecha = date("Y-m-d H:i:s");
        /* if ($proforma->validate()) {
          die('save success');
          } else {
          print_r($proforma->getErrors);
          } */
        $proforma->save();
        # mPDF        

        # You can easily override default constructor's params
        $mPDF1 = Yii::app()->ePdf->mpdf('', 'A4');
        $mPDF1->SetTitle('Proforma de Cotización');

        //$mPDF1->WriteHTML($this->render('pdf2', array('data' => $request), true));
        # Load a stylesheet
        $stylesheet = file_get_contents(Yii::getPathOfAlias('webroot.bootstrap.css') . '/bootstrap.css');
        $mPDF1->WriteHTML($stylesheet, 1);
        # renderPartial (only 'view' of current controller)
        $mPDF1->WriteHTML($this->renderPartial('proforma', array('data' => $request, 'id_hoja' => $num_proforma, 'id_informacion' => $id_informacion, 'nombre_responsable' => $nombre_responsable, 'responsable_id' => $responsable_id, 'ruc' => $ruc), true));

        # Renders image
        //$mPDF1->WriteHTML(CHtml::image(Yii::getPathOfAlias('webroot.css') . '/bg.gif' ));
        # Outputs ready PDF
        $mPDF1->Output($nombreproforma.'.pdf', 'I');
    }
    
    /**
     * Generate a pdf document with vehicle accesories and charts
     * @param type $id_informacion
     * @param type $id_vehiculo
     */
    public function actionProformaexo($id_informacion, $id_vehiculo) {
        $this->layout = '//layouts/call-print';
        $id_asesor = Yii::app()->user->getId();
        $responsable_id = $this->getResponsableId($id_informacion);
        $nombre_responsable = $this->getResponsableNombres($responsable_id);
        $nombre_responsable = mb_convert_case($nombre_responsable, MB_CASE_UPPER, "UTF-8");
        $concesionarioid = $this->getConcesionarioDealerId($id_asesor);
        $nombreproforma = $this->getNombreProforma($concesionarioid);
        $ruc = $this->getConcesionarioGrupoRuc($responsable_id);
        $tipo_exonerado = $this->getTipoExoInfo($id_informacion);
        //die('conc id: '.$concesionarioid);
        $telefono = $this->getAsesorTelefono($id_asesor);
        $celular = $this->getAsesorCelular($id_asesor);
        $codigo_asesor = $this->getAsesorCodigo($id_asesor);
//echo $this->getResponsable($id_asesor);
        $mpdf = Yii::app()->ePdf->mpdf();

        //die('enter prof');
        $con = Yii::app()->db;
        $sql = "SELECT gi.nombres, gi.apellidos, gi.direccion, gi.celular, gi.telefono_casa,gi.responsable, gv.modelo, gv.version, gf.forma_pago, 
gf.precio_vehiculo, gf.precio_normal, gf.seguro, gf.valor_financiamiento, gf.cuota_inicial, gf.saldo_financiar, gf.plazos, gf.entidad_financiera, gf.id as id_financiamiento,gf.ts,  
gf.observaciones, gf.cuota_mensual, gv.accesorios
FROM gestion_informacion gi 
INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id 
INNER JOIN gestion_financiamiento gf ON gf.id_informacion = gi.id 
WHERE gi.id = {$id_informacion} AND gv.id = {$id_vehiculo} ORDER BY gf.id DESC LIMIT 1";
//die('sql:'.$sql);
        $request = $con->createCommand($sql)->queryAll();
        $num_proforma = $this->getLastProforma();
        // grabar numero de proforma en la base de datos segun idvehiculo
        $con = Yii::app()->db;
        $sql = "UPDATE gestion_vehiculo SET num_pdf = {$num_proforma} WHERE id_informacion = {$id_informacion} AND id = {$id_vehiculo}";
        $req = $con->createCommand($sql)->query();

        //die('num proforma: '.$num_proforma);
        //die('id vehiculo: ' . $id_vehiculo);
        $proforma = new GestionProforma;
        $proforma->id_vehiculo = $id_vehiculo;
        $proforma->id_informacion = $id_informacion;
        date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
        $proforma->fecha = date("Y-m-d H:i:s");
        /* if ($proforma->validate()) {
          die('save success');
          } else {
          print_r($proforma->getErrors);
          } */
        $proforma->save();
        # mPDF
        $mPDF1 = Yii::app()->ePdf->mpdf();
        $mPDF1->SetTitle('Formulario de Prueba de Manejo');

        # You can easily override default constructor's params
        $mPDF1 = Yii::app()->ePdf->mpdf('', 'A4');

        //$mPDF1->WriteHTML($this->render('pdf2', array('data' => $request), true));
        # Load a stylesheet
        $stylesheet = file_get_contents(Yii::getPathOfAlias('webroot.bootstrap.css') . '/bootstrap.css');
        $mPDF1->WriteHTML($stylesheet, 1);
        # renderPartial (only 'view' of current controller)
        $mPDF1->WriteHTML($this->renderPartial('proformaexo', array('data' => $request, 'id_hoja' => $num_proforma, 'id_informacion' => $id_informacion,
            'nombre_responsable' => $nombre_responsable, 'responsable_id' => $responsable_id, 'ruc' => $ruc,'tipo_exonerado' => $tipo_exonerado), true));

        # Renders image
        //$mPDF1->WriteHTML(CHtml::image(Yii::getPathOfAlias('webroot.css') . '/bg.gif' ));
        # Outputs ready PDF
        $mPDF1->Output($nombreproforma.'.pdf', 'I');
    }

    public function actionSendProforma() {
        $id_informacion = isset($_POST["id_informacion"]) ? $_POST["id_informacion"] : "";
        $id_vehiculo = isset($_POST["id_vehiculo"]) ? $_POST["id_vehiculo"] : "";
        require_once 'email/mail_func.php';
        $emailCliente = $this->getEmailCliente($id_informacion);
        $id_modelo = $this->getIdModelo($id_vehiculo);
        $ficha_tecnica = $this->getPdf($id_modelo);
        $nombre_cliente = $this->getNombresInfo($id_informacion) . ' ' . $this->getApellidosInfo($id_informacion);
        $id_asesor = Yii::app()->user->getId();
        $dealer_id = $this->getDealerId($id_asesor);
        $concesionarioid = $this->getConcesionarioDealerId($id_asesor);

        $estadoCredito = $this->getTipoVenta($id_informacion);
        // ENVIAR EMAIL AL CLIENTE CON AGRADECIMIENTO DE VISITA Y ADJUNTO EL CATALOGO Y PROFORMA
        if ($estadoCredito == 1) // FINANCIADO
            $asunto = 'Kia Motors Ecuador – Cotización con Financiamiento Vehículo Kia ' . $this->getModeloTestDrive($id_vehiculo);
        else
            $asunto = 'Kia Motors Ecuador – Cotización de Vehículo Kia ' . $this->getModeloTestDrive($id_vehiculo);

        $general = '<body style="margin: 10px;">
                        <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;">
                            <div align="">
                            <img src="images/header_mail.jpg">
                            <br><br>
                            <p style="margin: 2px 0;">Señor(a): ' . $nombre_cliente . '</p>
                            <p></p><br />
                            <p style="margin: 2px 0;">Reciba un cordial saludo de <strong>Kia Motors Ecuador.</strong></p>
                            <p></p><br />

                            <p style="margin: 2px 0;">Le agradecemos mucho su visita y conforme a lo solicitado adjuntamos el catálogo y
                            proforma de su nuevo vehículo Kia. </p>
                            <p></p><br /><br />
                            <p style="margin: 2px 0;">Modelo: <strong>' . $this->getModeloTestDrive($id_vehiculo) . '</strong></p> 
                            <br />
                            <p style="margin: 2px 0;">Para descargar la proforma haga click <a href="https://www.kia.com.ec/intranet/usuario/index.php/site/proformacliente?id_informacion=' . $id_informacion . '&amp;id_vehiculo=' . $id_vehiculo . '">Aquí</a></p>
                            <p style="margin: 2px 0;">Para descargar el catálogo haga click <a href="https://www.kia.com.ec/images/Fichas_Tecnicas/' . $ficha_tecnica . '">Aquí</a></p>
                                <br />
                            <p style="margin: 2px 0;">Estaremos gustosos de servirle.</p><br />
                            <p style="margin: 2px 0;">Kia Motors Ecuador</p>
                            <p></p><br /><br />';

        $general .= '</div>
                            <br />
                           <table width="600"  cellpadding="0" cellspacing="0" style="font-family:Arial, Helvetica, sans-serif; font-size:11px;">
                                <tr>
                                  <td width="228">&nbsp;</td>
                                  <td width="228" style="color:#1f497d">' . $this->getResponsable($id_asesor) . ' - Asesor Ventas Kia</td>
                                </tr>
                                <tr>
                                  <td width="178" rowspan="5"><img src="images/logo_pdf2.png" /></td>
                                  <td colspan="2"><strong style="color: #AB1F2C; font-size: 16px;">' . strtoupper($this->getNombreConcesionario($concesionarioid)) . '</strong></td>
                                </tr>

                                <tr>
                                  <td colspan="2">' . $this->getConcesionarioDireccion($id_asesor) . '</td>
                                </tr>
                                <tr>
                                  <td><strong style="color:#AB1F2C;">T</strong> <a href="tel:' . $this->getAsesorTelefono($id_asesor) . '">' . $this->getAsesorTelefono($id_asesor) . '</a> ext. ' . $this->getAsesorExtension($id_asesor) . '</td>
                                  <td><strong style="color:#AB1F2C;">M</strong> <a href="tel:' . $this->getAsesorCelular($id_asesor) . '">' . $this->getAsesorCelular($id_asesor) . '</a></td>
                                </tr>
                                <tr>
                                  <td><strong style="color:#AB1F2C;">E</strong> ' . $this->getAsesorEmail($id_asesor) . ' </td>
                                  <td><strong style="color:#AB1F2C;">W</strong> www.kia.com.ec </td>
                                </tr>
                            </table>
                            <br />';
        if ($estadoCredito == 1) {
            $general .= '       <p style="margin: 2px 0;"><strong>Importante</strong>: La proforma es referencial y sujeta a revisión y análisis '
                    . 'por parte de nuestro Jefe de Sucursal al momento de concretar la compra de su vehículo. Tiene una validez de 48 horas.'
                    . ' Las especificaciones y precios pueden variar sin previo aviso. <br />'
                    . '* Valor de la cuota sujeta a revisión o confirmación por parte del asesor de crédito - '
                    . 'Cotización Referencial</p>
                            <br/>';
        } else {
            $general .= '<p style="margin: 2px 0;">La proforma es referencial y sujeta a revisión y análisis por parte de nuestro Jefe de Sucursal '
                    . 'al momento de concretar la compra de su vehículo. Tiene una validez de 48 horas. '
                    . 'Las especificaciones y precios pueden variar sin previo aviso.'
                    . '* Precios y/o observaciones sujetos a cambio sin previo aviso </p>';
        }
        $general .= '<br /><br /><p style="margin: 2px 0;">Nota de descargo: La información contenida en este e-mail es confidencial y sólo puede ser utilizada por el individuo o la compañía a la cual está dirigido. Esta información no debe ser distribuida ni copiada total o parcialmente por ningún medio sin la autorización de AEKIA S.A.
La organización no asume responsabilidad sobre información, opiniones o criterios contenidos en este mail que no esté relacionada con negocios oficiales de nuestra compañía.
</p></div>
                    </body>';
        //die('table: '.$general);
        $codigohtml = $general;
        $headers = 'From: info@kia.com.ec' . "\r\n";
        $headers .= 'Content-type: text/html' . "\r\n";
        $email = $emailCliente; //email cliente registrado
        //$email = 'alkanware@gmail.com'; //email administrador

        $send = sendEmailInfo('info@kia.com.ec', "Kia Motors Ecuador", $email, html_entity_decode($asunto), $codigohtml);


        // SEND EMAIL TO CLIENT WITH PROFORM NUMBER
        $con = Yii::app()->db;
        $sql = "SELECT gi.*, gc.preg6 as formapago FROM gestion_informacion gi "
                . "INNER JOIN gestion_consulta gc ON gc.id_informacion = gi.id "
                . "WHERE gi.id = {$id_informacion}";
        $request = $con->createCommand($sql)->query();




        //die('before send proforma');
        $id_asesor = Yii::app()->user->getId();
        $cargo_id = (int) Yii::app()->user->getState('cargo_id');
        $grupo_id = (int) Yii::app()->user->getState('grupo_id');
        $asunto = 'Kia Motors Ecuador SGC - Proforma ID Cliente # ' . $this->getNumProforma($id_vehiculo);
        $general = '<body style="margin: 10px;">
                        <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;">
                            <div align="">
                            <img src="images/header_mail.jpg">
                            <br><br>
                            <p style="margin: 2px 0;">Estimado/a (Jefe de Sucursal)</p>
                            <p></p>';
        foreach ($request as $value) {
            if ($value['formapago'] == 1)
                $forma = 'Crédito';
            else
                $forma = 'Contado';
            $general .= '<p style="margin: 2px 0;">Se ha realizado una proforma con el siguiente detalle:</p>
                            <p></p>
                            <table width="600">
                            <tr><td><strong>Cliente:</strong></td><td>' . $nombre_cliente . '</td></tr>';
            $general .= '<tr><td><strong>Teléfono Domicilio:</strong></td><td> ' . $value['telefono_casa'] . '</td></tr> 
                            <tr><td><strong>Teléfono Celular:</strong></td><td> ' . $value['celular'] . '</td></tr> 
                            <tr><td><strong>Modelo:</strong></td><td> ' . $this->getModeloTestDrive($id_vehiculo) . '</td></tr> 
                            <tr><td><strong>Forma de pago:</strong></td><td>' . $forma . ' </td></tr>';

            $general .= '<tr><td><strong>Asesor Comercial:</strong></td><td> ' . $this->getResponsable($id_asesor) . '</td></tr> 
                            </table>';
        }
		

        $general .= '<p style="margin: 2px 0;">Para descargar la proforma haga click <a href="https://www.kia.com.ec/intranet/usuario/index.php/site/proformacliente?id_informacion=' . $id_informacion . '&amp;id_vehiculo=' . $id_vehiculo . '">Aquí</a></p>
		<p style="margin: 2px 0;">Para descargar el catálogo haga click <a href="https://www.kia.com.ec/images/Fichas_Tecnicas/' . $ficha_tecnica . '">Aquí</a></p>
                            </div>
		<p>Por favor realizar llamada de Presentación al cliente.</p><br />
                            <p></p><br />
                            <p style="margin: 2px 0;">Saludos cordiales,</p>
                            <p style="margin: 2px 0;">SGC</p>
                            <p style="margin: 2px 0;">Kia Motors Ecuador</p><br /><br />
							<p>Nota de descargo: La información contenida en este e-mail es confidencial y sólo puede ser utilizada por el individuo o la compañía a la cual está dirigido. Esta información no debe ser distribuida ni copiada total o parcialmente por ningún medio sin la autorización de AEKIA S.A.<br>
La organización no asume responsabilidad sobre información, opiniones o criterios contenidos en este mail que no esté relacionada con negocios oficiales de nuestra compañía.
</p>
                                
                            </div>
                            <img src="images/footer.png">
                        </div>
                    </body>';
        //die('table: '.$general);
        $codigohtml = $general;
        $headers = 'From: info@kia.com.ec' . "\r\n";
        $headers .= 'Content-type: text/html' . "\r\n";
        //$email = $emailCliente; //email administrador
        $email = $this->getEmailJefeConcesion(70, $grupo_id, $dealer_id); //email administrador
        //$email = 'gansaldo72@hotmail.com';
        $emailAsesor = $this->getAsesorEmail($id_asesor);

        $send = sendEmailInfoTestDrive('info@kia.com.ec', "Kia Motors Ecuador", $email, $emailAsesor, html_entity_decode($asunto), $codigohtml);
        
    }

    /**
     * PHP Version of PMT in Excel.
     *
     * @param float $apr
     *   Interest rate.
     * @param integer $term
     *   Loan length in years.
     * @param float $loan
     *   The loan amount.
     *
     * @return float
     *   The monthly mortgage amount.
     */
    public function actionPago() {

        $taza = isset($_POST["taza"]) ? $_POST["taza"] : "";
        $taza = 16.06;
        $numpagos = isset($_POST["numpagos"]) ? $_POST["numpagos"] : "";
        $valorPrest = isset($_POST["valorPrest"]) ? $_POST["valorPrest"] : "";
        $plazo = isset($_POST["plazo"]) ? $_POST["plazo"] : "";
        //die('plazo: '.$plazo);
        switch ($plazo) {
            case '12':
                $tr = 1;
                break;
            case '24':
                $tr = 2;
                break;
            case '36':
                $tr = 3;
                break;
            case '48':
                $tr = 4;
                break;
            case '60':
                $tr = 5;
                break;
            default:
                break;
        }

        $term = $tr * 12;
        //$taza = $taza / 1200;
        $amount = $taza * -$valorPrest * pow((1 + $taza), $term) / (1 - pow((1 + $taza), $term));

        //$options = array('coutamensual' => $amount);
        //echo number_format($amount, 2);
        $months = $plazo; // meses de plazo
        $intRate = ($taza / 100) / 12; // tasa de interes
        $intRate = floatval($intRate);
        //die('intRate: '.$intRate);
        $cuota = floor(($valorPrest * $intRate) / (1 - pow(1 + $intRate, (-1 * $months))) * 100) / 100; // cuota
        //$cuota = floor(($valorPrest * $intRate) / (1 - pow(1 + $intRate, (-1 * 60))) * 100) / 100;
        //die('cuota: '.$cuota);

        $txtcuadro = "<table>"
                . "<tr>"
                . " <th>Año</th>"
                . " <th>Mes</th>"
                . " <th>Cuota</th>"
                . " <th>Interes</th>"
                . " <th>Amortizacion </th>"
                . " <th>Capital</th>"
                . "</tr>";
        //echo $txtcuadro;
        $am = new GestionAmortizacion;
        $capitalpendiente = $valorPrest;
        $ano = 1;
        $m = 0;
        $desgravamen = array();

        // calculo del seguro de desgravamen
        for ($i = 1; $i <= $months; $i++) {
            $m++;
            if ($m > 12) {
                $ano++;
                $m = 1;
            }
            $txtlinea = "<tr>";
            $txtlinea .= "<td>" . $ano . "</td>";
            $txtlinea .= "<td>" . $m . "</td>";
            $txtlinea .= "<td>" . $cuota . "</td>";
            //echo $cuota.'<br>';
            //echo $txtlinea.'<br />';
            $intereses = round($capitalpendiente * $intRate * 100) / 100;
            $txtlinea .= "<td>" . $intereses . "</td>";
            $amortizacion = round(($cuota - $intereses) * 100) / 100;
            $txtlinea .= "<td>" . $amortizacion . "</td>";
            $capitalpendiente = round(($capitalpendiente - $amortizacion) * 100) / 100;
            $txtlinea .= "<td>" . $capitalpendiente . "</td>";


            if ($i == $months) { //ultima cuota redondeo
                $txtlinea = "";
                $txtlinea .= $ano . "<td>" + $m . "</td>";
                $nuevacuota = round(($cuota + $capitalpendiente) * 100) / 100;
                $txtlinea.="<td>" . $nuevacuota . "</td>";
                $txtlinea.="<td>" . $intereses . "</td>";
                $txtlinea.="<td>" . $nuevacuota . "</td>";
                //$txtlinea+="<td>" . 0;
                //$txtlinea+="</td>";
            }
            $am->interes = $intereses;
            $am->capital_reducido = $capitalpendiente;
            $seguro_desgravamen = ($capitalpendiente * 0.00054 * 12) / 12;
            //echo $seguro_desgravamen.'<br />';
            $am->seguro_desgravamen = round($seguro_desgravamen, 2);
            //$am->save();
            $desgravamen[] = round($seguro_desgravamen, 2);

            $txtlinea .= "</tr>";
            $txtcuadro .= $txtlinea;
        }
        $txtcuadro .= "</table>";
        //echo $txtcuadro;
        //echo '<pre>';
        //print_r($desgravamen);
        //echo '</pre>';
        $seguro_desgravamen_sum = array_sum($desgravamen);
        //echo 'suma desgravamen: '.$seguro_desgravamen_sum;
        // volver a calcular la couta de entrada con el desgravamen
        $valorPrest = $valorPrest - 463.75;
        $valorPrest = $valorPrest + $seguro_desgravamen_sum + 4;
        //echo 'Nuevo valor a financiar: '.$valorPrest;
        $capitalpendiente = $valorPrest;
        $ano = 1;
        $m = 0;

        $cuota = floor(($valorPrest * $intRate) / (1 - pow(1 + $intRate, (-1 * $months))) * 100) / 100; // cuota
        //echo number_format($cuota, 2);
        $cuota = $cuota + 3;

        $options = array('cuota' => $cuota, 'valorFinanciamiento' => $valorPrest);
        echo json_encode($options);
    }

}
