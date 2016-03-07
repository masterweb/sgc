<?php

class GestionFinanciamientoController extends Controller {

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
                'actions' => array('create', 'update', 'createAjax', 'updatefn','admin','delete'),
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
        $model = new GestionFinanciamiento;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionFinanciamiento'])) {
            $model->attributes = $_POST['GestionFinanciamiento'];
            if ($model->save())
                $this->redirect(array('site/presentacion', 'id' => $id_informacion));
            //$this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model, 'id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo
        ));
    }

    /**
     * Creates a new model in AJAX FORM.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreateAjax($id_informacion = NULL, $id_vehiculo = NULL) {
        $model = new GestionFinanciamiento;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionFinanciamiento'])) {
//            echo '<pre>';
//            print_r($_POST);
//            echo '</pre>';
//            die('enter post');
            $model->attributes = $_POST['GestionFinanciamiento'];
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            if ($model->validate()) {
                die('enter validate');
                $res = $model->save();
                //die('res: '.$res);
                if ($res == TRUE)
                    echo TRUE;
                else
                    echo FALSE;
            }
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
    public function actionUpdate() {
        //die('id update: '.$_POST['GestionFinanciamiento1']['id_financiamiento']);
        $model = $this->loadModel($_POST['GestionFinanciamiento1']['id_financiamiento']);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['GestionFinanciamiento'])) {
//              echo '<pre>';
//              print_r($_POST);
//              echo '</pre>';
//              die(); 
            //$model = $this->loadModelFinanciamiento($_POST['GestionFinanciamiento1']['id_financiamiento']);
            $currencys = array("$");
            $currencys2 = array(".");
            $result = FALSE;
            $numCotizaciones = $_POST['options-cont'];
            $tipoFinanciamiento = $_POST['GestionFinanciamiento1']['tipo_financiamiento'];

            if ($tipoFinanciamiento == 0) {// financiamiento al contado
                $model->attributes = $_POST['GestionFinanciamiento1'];

                $precio_vehiculo = str_replace(',', "", $_POST['GestionFinanciamiento1']['precio_contado']);
                $precio_vehiculo = str_replace('.', ",", $precio_vehiculo);
                $precio_vehiculo = (int) str_replace('$', "", $precio_vehiculo);

                $seguro = str_replace(',', "", $_POST['GestionFinanciamiento1']['seguro_contado']);
                $seguro = str_replace('.', ",", $seguro);
                $seguro = (int) str_replace('$', "", $seguro);

                $model->precio_vehiculo = $precio_vehiculo;
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
//                //die('enter accesorios');
//                $counter = $_POST['accesorios'];
//                $accesorios = '';
//                foreach ($counter as $key => $entry) {
//                    $accesorios .= $entry . '@';
//                }
                $accesorios = $_POST['GestionFinanciamiento1']['acc1'];
                $accesorios = substr($accesorios, 0, -1);
                $con = Yii::app()->db;
                $sql = "UPDATE gestion_vehiculo SET accesorios = '{$accesorios}' WHERE id = {$_POST['GestionFinanciamiento1']['id_vehiculo']}";
                $request = $con->createCommand($sql)->query();
            }
            //die('before save:');
            if ($model->update()) {
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

                // COMPROBAR NUMERO DE COTIZACIONES PARA BORRAR LAS YA EXISTENTES 
                $existentes = $this->getNumFinOp($_POST['GestionFinanciamiento1']['id_financiamiento']);
                //die('num exisytentes: '.$existentes);
                $nrAdicionales = $numCotizaciones - 2;
                //die('adicionales: '.$nrAdicionales);
                if ($existentes > $nrAdicionales) {
                    //die('enyer');
                    switch ($numCotizaciones) {
                        case 3:
                            //die('enter 3');                            
                            $con = Yii::app()->db;
                            $sql = "UPDATE gestion_financiamiento_op SET status = 'INACTIVO' WHERE id_financiamiento = {$_POST['GestionFinanciamiento1']['id_financiamiento']} AND num_cotizacion = 4";
                            //die($sql);
                            $request = $con->createCommand($sql)->query();

                            break;
                        case 4:
                            $con = Yii::app()->db;
                            $sql = "UPDATE gestion_financiamiento_op SET status = 'INACTIVO' WHERE id_financiamiento = {$_POST['GestionFinanciamiento1']['id_financiamiento']} AND num_cotizacion = 3";
                            $request = $con->createCommand($sql)->query();
                            $sql2 = "UPDATE gestion_financiamiento_op SET status = 'INACTIVO' WHERE id_financiamiento = {$_POST['GestionFinanciamiento1']['id_financiamiento']} AND num_cotizacion = 4";
                            $request2 = $con->createCommand($sql2)->query();
                            break;
                        default:
                            break;
                    }
                }


                if (isset($_POST['GestionFinanciamiento2']) && $numCotizaciones == 3) {// segundacotizacion
                    // si el numero de cotizaciones es mayor que las ya creadas
                    // crear nuevo objeto de financiamiento op
                    // buscar la cotizacion 3 en financiamiento op
                    $sr = $this->getFinanOp($_POST['GestionFinanciamiento1']['id_financiamiento'], 3);
                    if ($sr) { // si existe actualiza
                        $id_fin_op = $this->getIdFinOp($_POST['GestionFinanciamiento1']['id_financiamiento'], 3);
                        $model2 = $this->loadModelFinOp($id_fin_op);
                    } else { // se crea un nuevo modelo
                        $model2 = new GestionFinanciamientoOp;
                    }

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
                    
                    if(isset($_POST['GestionFinanciamiento2']['tiempo_seguro_contado']) && !empty($_POST['GestionFinanciamiento2']['tiempo_seguro_contado'])){
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
                    $sr = $this->getFinanOp($_POST['GestionFinanciamiento1']['id_financiamiento'], 3);
                    if ($sr) { // si existe actualiza
                        $id_fin_op = $this->getIdFinOp($_POST['GestionFinanciamiento1']['id_financiamiento'], 3);
                        $model2 = $this->loadModelFinOp($id_fin_op);
                    } else { // se crea un nuevo modelo
                        $model2 = new GestionFinanciamientoOp;
                    }

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
                    if(isset($_POST['GestionFinanciamiento2']['tiempo_seguro_contado']) && !empty($_POST['GestionFinanciamiento2']['tiempo_seguro_contado'])){
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

                    $sr = $this->getFinanOp($_POST['GestionFinanciamiento1']['id_financiamiento'], 4);
                    if ($sr) { // si existe actualiza
                        $id_fin_op2 = $this->getIdFinOp($_POST['GestionFinanciamiento1']['id_financiamiento'], 4);
                        $model3 = $this->loadModelFinOp($id_fin_op2);
                    } else { // se crea un nuevo modelo
                        $model3 = new GestionFinanciamientoOp;
                    }

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
                    if(isset($_POST['GestionFinanciamiento3']['tiempo_seguro_contado']) && !empty($_POST['GestionFinanciamiento3']['tiempo_seguro_contado'])){
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

//        if (isset($_POST['GestionFinanciamiento'])) {
//            $model->attributes = $_POST['GestionFinanciamiento'];
//            if ($model->save())
//                $this->redirect(array('view', 'id' => $model->id));
//        }
//
//        $this->render('update', array(
//            'model' => $model,
//        ));
    }
    public function actionUpdatefn() {
        //die('id update: '.$_POST['GestionFinanciamiento1']['id_financiamiento']);
        $model = $this->loadModel($_POST['GestionFinanciamiento1']['id_financiamiento']);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['GestionFinanciamiento'])) {
//              echo '<pre>';
//              print_r($_POST);
//              echo '</pre>';
//              die(); 
            //$model = $this->loadModelFinanciamiento($_POST['GestionFinanciamiento1']['id_financiamiento']);
            $currencys = array("$");
            $currencys2 = array(".");
            $result = FALSE;
            $numCotizaciones = $_POST['options-cont'];
            $tipoFinanciamiento = $_POST['GestionFinanciamiento1']['tipo_financiamiento'];

            if ($tipoFinanciamiento == 0) {// financiamiento al contado
                $model->attributes = $_POST['GestionFinanciamiento1'];

                $precio_vehiculo = str_replace(',', "", $_POST['GestionFinanciamiento1']['precio_contado']);
                $precio_vehiculo = str_replace('.', ",", $precio_vehiculo);
                $precio_vehiculo = (int) str_replace('$', "", $precio_vehiculo);

                $seguro = str_replace(',', "", $_POST['GestionFinanciamiento1']['seguro_contado']);
                $seguro = str_replace('.', ",", $seguro);
                $seguro = (int) str_replace('$', "", $seguro);

                $model->precio_vehiculo = $precio_vehiculo;
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
//                //die('enter accesorios');
//                $counter = $_POST['accesorios'];
//                $accesorios = '';
//                foreach ($counter as $key => $entry) {
//                    $accesorios .= $entry . '@';
//                }
                $accesorios = $_POST['GestionFinanciamiento1']['acc1'];
                $accesorios = substr($accesorios, 0, -1);
                $con = Yii::app()->db;
                $sql = "UPDATE gestion_vehiculo SET accesorios = '{$accesorios}' WHERE id = {$_POST['GestionFinanciamiento1']['id_vehiculo']}";
                $request = $con->createCommand($sql)->query();
            }
            //die('before save:');
            if ($model->update()) {
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

                // COMPROBAR NUMERO DE COTIZACIONES PARA BORRAR LAS YA EXISTENTES 
                $existentes = $this->getNumFinOp($_POST['GestionFinanciamiento1']['id_financiamiento']);
                //die('num exisytentes: '.$existentes);
                $nrAdicionales = $numCotizaciones - 2;
                //die('adicionales: '.$nrAdicionales);
                if ($existentes > $nrAdicionales) {
                    //die('enyer');
                    switch ($numCotizaciones) {
                        case 3:
                            //die('enter 3');                            
                            $con = Yii::app()->db;
                            $sql = "UPDATE gestion_financiamiento_op SET status = 'INACTIVO' WHERE id_financiamiento = {$_POST['GestionFinanciamiento1']['id_financiamiento']} AND num_cotizacion = 4";
                            //die($sql);
                            $request = $con->createCommand($sql)->query();

                            break;
                        case 4:
                            $con = Yii::app()->db;
                            $sql = "UPDATE gestion_financiamiento_op SET status = 'INACTIVO' WHERE id_financiamiento = {$_POST['GestionFinanciamiento1']['id_financiamiento']} AND num_cotizacion = 3";
                            $request = $con->createCommand($sql)->query();
                            $sql2 = "UPDATE gestion_financiamiento_op SET status = 'INACTIVO' WHERE id_financiamiento = {$_POST['GestionFinanciamiento1']['id_financiamiento']} AND num_cotizacion = 4";
                            $request2 = $con->createCommand($sql2)->query();
                            break;
                        default:
                            break;
                    }
                }


                if (isset($_POST['GestionFinanciamiento2']) && $numCotizaciones == 3) {// segundacotizacion
                    // si el numero de cotizaciones es mayor que las ya creadas
                    // crear nuevo objeto de financiamiento op
                    // buscar la cotizacion 3 en financiamiento op
                    $sr = $this->getFinanOp($_POST['GestionFinanciamiento1']['id_financiamiento'], 3);
                    if ($sr) { // si existe actualiza
                        $id_fin_op = $this->getIdFinOp($_POST['GestionFinanciamiento1']['id_financiamiento'], 3);
                        $model2 = $this->loadModelFinOp($id_fin_op);
                    } else { // se crea un nuevo modelo
                        $model2 = new GestionFinanciamientoOp;
                    }

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
                    
                    if(isset($_POST['GestionFinanciamiento2']['tiempo_seguro_contado']) && !empty($_POST['GestionFinanciamiento2']['tiempo_seguro_contado'])){
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
                    $sr = $this->getFinanOp($_POST['GestionFinanciamiento1']['id_financiamiento'], 3);
                    if ($sr) { // si existe actualiza
                        $id_fin_op = $this->getIdFinOp($_POST['GestionFinanciamiento1']['id_financiamiento'], 3);
                        $model2 = $this->loadModelFinOp($id_fin_op);
                    } else { // se crea un nuevo modelo
                        $model2 = new GestionFinanciamientoOp;
                    }

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
                    if(isset($_POST['GestionFinanciamiento2']['tiempo_seguro_contado']) && !empty($_POST['GestionFinanciamiento2']['tiempo_seguro_contado'])){
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

                    $sr = $this->getFinanOp($_POST['GestionFinanciamiento1']['id_financiamiento'], 4);
                    if ($sr) { // si existe actualiza
                        $id_fin_op2 = $this->getIdFinOp($_POST['GestionFinanciamiento1']['id_financiamiento'], 4);
                        $model3 = $this->loadModelFinOp($id_fin_op2);
                    } else { // se crea un nuevo modelo
                        $model3 = new GestionFinanciamientoOp;
                    }

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
                    if(isset($_POST['GestionFinanciamiento3']['tiempo_seguro_contado']) && !empty($_POST['GestionFinanciamiento3']['tiempo_seguro_contado'])){
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

//        if (isset($_POST['GestionFinanciamiento'])) {
//            $model->attributes = $_POST['GestionFinanciamiento'];
//            if ($model->save())
//                $this->redirect(array('view', 'id' => $model->id));
//        }
//
//        $this->render('update', array(
//            'model' => $model,
//        ));
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
        $dataProvider = new CActiveDataProvider('GestionFinanciamiento');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new GestionFinanciamiento('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['GestionFinanciamiento']))
            $model->attributes = $_GET['GestionFinanciamiento'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return GestionFinanciamiento the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        //die('load model: '.$id);
        $model = GestionFinanciamiento::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function loadModelFinOp($id) {
        $model = GestionFinanciamientoOp::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param GestionFinanciamiento $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'gestion-financiamiento-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
