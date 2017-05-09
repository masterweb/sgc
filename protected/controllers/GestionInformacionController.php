<?php

class GestionInformacionController extends Controller {

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
                'actions' => array('create', 'update', 'seguimiento', 'seguimientobdc', 'seguimientoexonerados', 'seguimientoexcel', 'getresponsable',
                    'calendar', 'createAjax', 'create2', 'seguimientoUsados', 'usados', 'exonerados', 'reportes', 'conadis', 'diplomaticos', 'setAsignamiento','admin'),
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
    public function actionCreate($tipo = NULL, $id = NULL, $fuente = NULL, $tipo_fuente = NULL) {
        $model = new GestionInformacion;
        $cargo_id = (int) Yii::app()->user->getState('cargo_id');
        $cargo_adicional = (int) Yii::app()->user->getState('cargo_adicional');
        $grupo_id = (int) Yii::app()->user->getState('grupo_id');
        $tipo_grupo = 1; // GRUPOS ASIAUTO, KMOTOR POR DEFECTO
        if($grupo_id == 4 || $grupo_id == 5 || $grupo_id == 6 || $grupo_id == 7 || $grupo_id == 8 || $grupo_id == 9 ){
            $tipo_grupo = 0; // GRUPOS MOTRICENTRO, MERQUIAUTO, AUTHESA, AUTOSCOREA, IOKARS
        }

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        //die('fuente: '.$fuente);

        if (isset($_POST['GestionInformacion'])) {
//            echo '<pre>';
//            print_r($_POST);
//            echo '</pre>';
//            die();
            $historial = new GestionHistorial;
            $model->attributes = $_POST['GestionInformacion'];
            if (isset($_POST['GestionInformacion']['tipo_form_web']))
                $model->tipo_form_web = $_POST['GestionInformacion']['tipo_form_web'];

            if (isset($_POST['GestionInformacion']['presupuesto']))
                $model->presupuesto = $_POST['GestionInformacion']['presupuesto'];

            if (isset($_POST['GestionInformacion']['iden'])) {
                if (($_POST['GestionInformacion']['iden'] == 'ruc') && !isset($_POST['tipo_fuente'])) {
                    $model->setscenario('ruc');
                }
                if (($_POST['GestionInformacion']['iden'] == 'pasaporte') && !isset($_POST['tipo_fuente'])) {
                    $model->setscenario('pasaporte');
                    $model->pasaporte = $_POST['GestionInformacion']['pasaporte'];
                }
                if (($_POST['GestionInformacion']['iden'] == 'ruc') && isset($_POST['tipo_fuente'])) {
                    $model->setscenario('rucusado');
                    $model->provincia_domicilio = $_POST['GestionInformacion']['provincia_domicilio'];
                    $model->ciudad_domicilio = $_POST['GestionInformacion']['ciudad_domicilio'];
                }
                if (($_POST['GestionInformacion']['iden'] == 'pasaporte') && isset($_POST['tipo_fuente'])) {
                    $model->setscenario('pasaporteusado');
                    $model->pasaporte = $_POST['GestionInformacion']['pasaporte'];
                    $model->provincia_domicilio = $_POST['GestionInformacion']['provincia_domicilio'];
                    $model->ciudad_domicilio = $_POST['GestionInformacion']['ciudad_domicilio'];
                }
            }

            if (isset($_POST['GestionInformacion']['pasaporte']) && !empty($_POST['GestionInformacion']['pasaporte'])) {
                $model->pasaporte = $_POST['GestionInformacion']['pasaporte'];
            }

            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            //die('id responsable: '.Yii::app()->user->getId());
            $model->responsable = Yii::app()->user->getId();
            $model->dealer_id = $this->getDealerId(Yii::app()->user->getId());
            $model->id_cotizacion = $_POST['GestionInformacion']['id_cotizacion'];
            $model->nombres = ucfirst($_POST['GestionInformacion']['nombres']);
            $model->apellidos = ucfirst($_POST['GestionInformacion']['apellidos']);
            $model->medio = $_POST['GestionInformacion']['medio'];
            $model->considero = $_POST['GestionInformacion']['considero'];
            switch ($_POST['GestionInformacion']['medio']) {
                case 'recomendacion':
                    $model->recomendaron = $_POST['GestionInformacion']['recomendaron'];
                    break;
                case 'television':
                    $model->medio_television = $_POST['GestionInformacion']['medio_television'];
                    break;
                case 'prensa_escrita':
                    $model->medio_prensa = $_POST['GestionInformacion']['medio_prensa'];
                    break;

                default:
                    break;
            }
            switch ($_POST['GestionInformacion']['considero']) {
                case 'recomendacion':
                    $model->considero_recomendaron = $_POST['GestionInformacion']['considero_recomendaron'];
                    break;
                
                default:
                    break;
            }
            if ($_POST['tipo'] == 'gestion'):
                $model->provincia_conc = $_POST['GestionInformacion']['provincia_conc'];
                $model->ciudad_conc = $_POST['GestionInformacion']['ciudad_conc'];
                $model->concesionario = $_POST['GestionInformacion']['concesionario'];
                $model->provincia_domicilio = $_POST['GestionInformacion']['provincia_domicilio'];
                $model->ciudad_domicilio = $_POST['GestionInformacion']['ciudad_domicilio'];
            endif;

            if ($_POST['tipo'] == 'prospeccion'):
                $model->provincia_conc = $_POST['Casos']['provincia'];
                $model->ciudad_conc = $_POST['Casos']['ciudad'];
                $model->concesionario = $_POST['Casos']['concesionario'];
                $model->provincia_domicilio = $_POST['GestionInformacion']['provincia_domicilio'];
                $model->ciudad_domicilio = $_POST['GestionInformacion']['ciudad_domicilio'];
//                echo '<pre>';
//                print_r($_POST);
//                echo '</pre>';die();
            endif;
            
            // ASIGNACION DE BDC SEGUN CARGO NORMAL O DOBLE CARGO=========================================================================
            switch ($cargo_adicional) {
                case 0:// SI EL USUARIO TIENE UN SOLO CARGO
                    if ($cargo_id == 73 || $cargo_id == 86) // asesor de ventas y asesor de ventas externas
                        $model->bdc = 1;
                    break;
                case 86:// USUARIO ASESOR VENTAS Y ASESOR DE VENTAS EXTERNAS
                    if($cargo_id == 71 && $_POST['tipo'] == 'prospeccion')
                        $model->bdc = 1;
                    break;
                case 85:// USUARIO JEFE DE SUCURSAL Y JEFE DE VENTAS EXTERNAS
                    if($cargo_id == 70 && $_POST['tipo'] == 'prospeccion')
                        $model->bdc = 1;
                    break;    
                default:
                    break;
            }
            
            

            if (isset($_POST['GestionInformacion']['fecha_cita']))
                $model->fecha_cita = $_POST['GestionInformacion']['fecha_cita'];

            if (($_POST['tipo'] == 'gestion') && ($_POST['GestionInformacion']['iden'] == 'ci')) {
                $model->setscenario('gestion');
            } else if (($_POST['tipo'] == 'prospeccion') && !isset($_POST['tipo_fuente'])) {
                $model->setscenario('prospeccion');
            }

            if ($model->save()) {
                //die('enter save');
                if ($_POST['tipo'] == 'prospeccion') {
                    //die('enter continuar');
                    // enviar a la pantalla de seguimiento con el nuevo caso ingresado
                    // ingresar datos en gestion diaria con status 1: prospección
                    $gestion = new GestionDiaria;
                    $gestion->paso = $_POST['GestionInformacion']['paso'];
                    $prospeccion = new GestionProspeccionRp;
                    $prospeccion->id_informacion = $model->id;
                    $observaciones = $_POST['GestionProspeccionPr']['pregunta'];
                    //die('observaciones: '.$observaciones);
                    switch ($observaciones) {
                        case 1:// no estoy interesado
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 0;
                            $gestion->proximo_seguimiento = '';
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        case 2:// falta de dinero
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 0;
                            $gestion->proximo_seguimiento = '';
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        case 3: // compro otro vehiculo
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $prospeccion->preg3_sec1 = $_POST['GestionProspeccionRp']['nuevousado'];
                            $prospeccion->preg3_sec2 = $_POST['GestionProspeccionRp']['preg3_sec2'];
                            $prospeccion->preg3_sec3 = $_POST['Cotizador']['modelo'];
                            $prospeccion->preg3_sec4 = $_POST['Cotizador']['year'];

                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 0;
                            $gestion->proximo_seguimiento = '';
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        case 4:// si estoy interesado
                            //die('enter case 4');
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $prospeccion->preg4_sec1 = $_POST['GestionDiaria']['agendamiento'];
                            $prospeccion->preg4_sec2 = $_POST['GestionProspeccionRp']['lugar'];
                            //$prospeccion->preg4_sec3 = $_POST['GestionProspeccionRp']['agregar'];
                            if ($_POST['GestionProspeccionRp']['lugar'] == 0) {
                                $prospeccion->preg4_sec4 = $_POST['Casos']['concesionario'];
                            }
                            if (isset($_POST['GestionProspeccionRp']['ingresolugar'])):
                                $prospeccion->preg4_sec5 = $_POST['GestionProspeccionRp']['ingresolugar'];
                            endif;

                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 1;
                            $gestion->proximo_seguimiento = $_POST['GestionDiaria']['agendamiento'];
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();

                            $consulta = new GestionConsulta;
                            $consulta->id_informacion = $model->id;
                            $consulta->fecha = date("Y-m-d H:i:s");
                            $consulta->status = 'ACTIVO';
                            $consulta->save();

                            $historial->id_responsable = Yii::app()->user->getId();
                            $historial->id_informacion = $model->id;
                            $historial->observacion = 'Nuevo registro de usuario';
                            $historial->paso = '3-4';
                            $historial->fecha = date("Y-m-d H:i:s");
                            $historial->save();
                            //die('after save');
                            if($cargo_id == 71 && $cargo_adicional == 86 && $tipo_grupo == 0)
                                $this->redirect(array('gestionInformacion/seguimiento')); 
                            if($cargo_id == 71 && $cargo_adicional == 86)
                                $this->redirect(array('gestionInformacion/seguimientobdc')); 
                            if($cargo_id == 70 && $cargo_adicional == 85)
                                $this->redirect(array('gestionInformacion/seguimiento')); 
                            if ($cargo_id == 73 || $cargo_id == 85 || $cargo_id == 86)
                                $this->redirect(array('gestionInformacion/seguimientobdc'));
                            else
                                $this->redirect(array('gestionInformacion/seguimiento'));
                            break;
                        case 5:// no contesta
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $prospeccion->preg5_sec1 = $_POST['GestionDiaria']['agendamiento2'];

                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 3;
                            $gestion->proximo_seguimiento = $_POST['GestionDiaria']['agendamiento2'];
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            //die('gestion save');

                            $consulta = new GestionConsulta;
                            $consulta->id_informacion = $model->id;
                            $consulta->fecha = date("Y-m-d H:i:s");
                            $consulta->status = 'ACTIVO';
                            $consulta->save();
                            if($cargo_id == 71 && $cargo_adicional == 86)
                                $this->redirect(array('gestionInformacion/seguimientobdc'));
                            if ($cargo_id == 73)
                                $this->redirect(array('gestionInformacion/seguimientobdc'));
                            else
                                $this->redirect(array('gestionInformacion/seguimiento'));
                            //$this->redirect(array('gestionConsulta/create', 'id_informacion' => $model->id, 'tipo' => $_POST['tipo'], 'fuente' => $fuente));
                            break;
                        case 6: // telefono equivocado
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 0;
                            $gestion->proximo_seguimiento = '';
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        case 15:// tipo usados
                            //die('enter usados');
                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 0;
                            $gestion->proximo_seguimiento = '';
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        default:
                            break;
                    }
                    $prospeccion->fecha = date("Y-m-d H:i:s");
                    $prospeccion->save();

                    $historial->id_responsable = Yii::app()->user->getId();
                    $historial->id_informacion = $model->id;
                    $historial->observacion = 'Nuevo registro de usuario';
                    $historial->paso = '1-2';
                    $historial->fecha = date("Y-m-d H:i:s");
                    $historial->save();

                    if ($cargo_id == 73 || $cargo_id == 72)
                        $this->redirect(array('gestionInformacion/seguimientobdc'));
                    else
                        $this->redirect(array('gestionInformacion/seguimiento'));
                }
                if ($_POST['tipo'] == 'prospeccion' && $_POST['yt0'] == 'Abandonar') {
                    $this->redirect(array('gestionInformacion/seguimiento'));
                }
                if ($_POST['tipo'] == 'prospeccion' && $cargo_id == 73) {
                    $this->redirect(array('gestionInformacion/seguimientobdc'));
                }
                if ($_POST['tipo'] == 'gestion') {
                    //die('enter gestion');
                    $fecha_actual = date("Y-m-d H:i:s");
                    $fecha_posterior = strtotime('+2 day', strtotime($fecha_actual));
                    $fecha_posterior = date('Y-m-d H:i:s', $fecha_posterior);
                    //die('enter gestion');
                    $gestion = new GestionDiaria;
                    $gestion->id_informacion = $model->id;
                    $gestion->id_vehiculo = 0;
                    $gestion->observaciones = 'Primera visita';
                    $gestion->medio_contacto = 'visita';
                    if($_POST['GestionInformacion']['fuente'] == 'exhibicion quierounkiatd'){
                        $gestion->medio_contacto = 'exhquktd';
                    }
                    $gestion->fuente_contacto = $fuente;
                    $gestion->codigo_vehiculo = 0;
                    $gestion->primera_visita = 1;
                    $gestion->status = 1;
                    $gestion->paso = 3;
                    $gestion->fecha = date("Y-m-d H:i:s");
                    $gestion->proximo_seguimiento = $fecha_posterior; // agendamiento automatico en 48 horas
                    $gestion->save();
                    $consulta = new GestionConsulta;
                    $consulta->id_informacion = $model->id;
                    $consulta->fecha = date("Y-m-d H:i:s");
                    $consulta->status = 'ACTIVO';
                    $consulta->save();

                    $historial->id_responsable = Yii::app()->user->getId();
                    $historial->id_informacion = $model->id;
                    $historial->observacion = 'Nuevo registro de usuario';
                    $historial->paso = '3-4';
                    $historial->fecha = date("Y-m-d H:i:s");
                    $historial->save();
                }
                if ($_POST['tipo'] == 'trafico') {
                    $this->redirect(array('gestionInformacion/seguimiento'));
                }
                // SI EL CLIENTE ES DE EXHIBICION SALTAMOS EL PASO DE CONSULTA Y SE VA A GESTIONVEHICULO CREATE
                if($_POST['GestionInformacion']['fuente'] == 'exhibicion' || $_POST['GestionInformacion']['fuente'] == 'exhibicion quierounkiatd'){
                    $this->redirect(array('gestionVehiculo/create', 'id' => $model->id, 'tipo' => $_POST['tipo'], 'fuente' => $fuente));
                }
                //$this->redirect(array('gestionConsulta/create', 'id_informacion' => $model->id, 'tipo' => $_POST['tipo'], 'fuente' => $fuente));
                $this->redirect(array('site/consulta', 'id_informacion' => $model->id, 'tipo' => $_POST['tipo'], 'fuente' => $fuente));
            }
        }

        $this->render('create', array(
            'model' => $model, 'tipo' => $tipo, 'id' => $id, 'fuente' => $fuente
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate2($tipo = NULL, $id = NULL, $fuente = NULL, $tipo_fuente = NULL) {
        $model = new GestionInformacion;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        //die('fuente: '.$fuente);

        if (isset($_POST['GestionInformacion'])) {
            /* echo '<pre>';
              print_r($_POST);
              echo '</pre>';
              die(); */
            $historial = new GestionHistorial;
            $model->attributes = $_POST['GestionInformacion'];
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            $model->responsable = Yii::app()->user->getId();
            $model->dealer_id = $this->getDealerId(Yii::app()->user->getId());
            $model->id_cotizacion = $_POST['GestionInformacion']['id_cotizacion'];
            $model->modelo = $_POST['GestionVehiculo']['modelo'];
            $model->version = $_POST['GestionVehiculo']['version'];

            if (isset($_POST['GestionInformacion']['fecha_cita']))
                $model->fecha_cita = $_POST['GestionInformacion']['fecha_cita'];

            else if ($_POST['tipo'] == 'trafico') {
                $model->setscenario('trafico');
            }

            if ($model->save()) {
                //die('enter save');

                if (isset($_POST['yt0']) && ($_POST['yt0'] == 'Guardar')) {
                    $this->redirect(array('gestionInformacion/seguimiento'));
                }
                if (isset($_POST['yt1']) && ($_POST['yt1'] == 'Cotizar')) {
                    //$this->redirect(array('gestionConsulta/create', 'id_informacion' => $model->id, 'tipo' => $_POST['tipo'], 'fuente' => 'trafico'));
                    $this->redirect(array('gestionInformacion/update/' . $model->id . '?tipo=trafico'));
                }
            }
        }

        $this->render('create2', array(
            'model' => $model, 'tipo' => $tipo, 'id' => $id, 'fuente' => $fuente
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreateAjax($tipo = NULL, $id = NULL, $fuente = NULL) {
        $model = new GestionInformacion;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        //die('fuente: '.$fuente);

        if (isset($_POST['GestionInformacion'])) {

//            echo '<pre>';
//            print_r($_POST);
//            echo '</pre>';
//            die();
            $historial = new GestionHistorial;
            $model->attributes = $_POST['GestionInformacion'];
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            $model->responsable = Yii::app()->user->getId();
            if ($_POST['tipo'] == 'gestion'):
                $model->provincia_conc = $_POST['GestionInformacion']['provincia_conc'];
                $model->ciudad_conc = $_POST['GestionInformacion']['ciudad_conc'];
                $model->concesionario = $_POST['GestionInformacion']['concesionario'];
            endif;

            if (isset($_POST['GestionInformacion']['fecha_cita']))
                $model->fecha_cita = $_POST['GestionInformacion']['fecha_cita'];

            if ($_POST['tipo'] == 'gestion') {
                $model->setscenario('gestion');
            } else if ($_POST['tipo'] == 'prospeccion') {
                $model->setscenario('prospeccion');
            }

            if ($model->save()) {
                echo TRUE;
            }
        }

        $this->render('create', array(
            'model' => $model, 'tipo' => $tipo, 'id' => $id, 'fuente' => $fuente
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id, $tipo = NULL) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionInformacion'])) {
//            echo '<pre>';
//            print_r($_POST);
//            echo '</pre>';
//            die();
            $model->attributes = $_POST['GestionInformacion'];
            $model->provincia_domicilio = $_POST['GestionInformacion']['provincia_domicilio'];
            $model->ciudad_domicilio = $_POST['GestionInformacion']['ciudad_domicilio'];
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            if ($model->save()) {
                $gestionId = $this->getIdGestion($id);
                $gestionrp = new GestionProspeccionRp;
                if ($_POST['GestionInformacion']['tipo'] == 'prospeccion') {
                    $pregunta = $_POST['GestionProspeccionPr']['pregunta'];
                    //die('pregunta: '.$pregunta);
                    switch ($pregunta) {
                        case 1:// no estoy interesado
                            $gestionrp->preg1 = $pregunta;
                            $gestionrp->id_informacion = $id;
                            $gestionrp->fecha = date("Y-m-d H:i:s");
                            $gestionrp->save();
                            $con = Yii::app()->db;
                            $sql = "UPDATE gestion_diaria SET status = 0, proximo_seguimiento = '' WHERE id_informacion = {$id}";
                            $request = $con->createCommand($sql)->query();
                            $this->redirect(array('gestionInformacion/seguimiento'));
                            break;
                        case 2:// falta de dinero
                            $gestionrp->preg1 = $pregunta;
                            $gestionrp->id_informacion = $id;
                            $gestionrp->fecha = date("Y-m-d H:i:s");
                            $gestionrp->save();
                            $con = Yii::app()->db;
                            $sql = "UPDATE gestion_diaria SET status = 0, proximo_seguimiento = '' WHERE id_informacion = {$id}";
                            $request = $con->createCommand($sql)->query();
                            $this->redirect(array('gestionInformacion/seguimiento'));
                            break;
                        case 3:// compro otro vehiculo
                            $gestionrp->preg1 = $pregunta;
                            $gestionrp->preg3_sec1 = $_POST['GestionProspeccionRp']['nuevousado'];
                            $gestionrp->preg3_sec2 = $_POST['GestionProspeccionRp']['preg3_sec2'];
                            $gestionrp->preg3_sec3 = $_POST['Cotizador']['modelo'];
                            $gestionrp->preg3_sec4 = $_POST['Cotizador']['year'];
                            $gestionrp->save();
                            $con = Yii::app()->db;
                            $sql = "UPDATE gestion_diaria SET status = 0, proximo_seguimiento = '' WHERE id_informacion = {$id}";
                            $request = $con->createCommand($sql)->query();
                            $this->redirect(array('gestionInformacion/seguimiento'));

                            break;
                        case 4:// si estoy interesado
                            //die('enter interesado');
                            $gestionrp->preg1 = $pregunta;
                            $gestionrp->id_informacion = $id;
                            $gestionrp->fecha = date("Y-m-d H:i:s");
                            switch ($_POST['GestionProspeccionRp']['lugar']) {
                                case 0: // concesionario
                                    $gestionrp->preg4_sec4 = $_POST['Casos']['concesionario'];
                                    break;
                                case 1:// lugar de trabajo
                                    $gestionrp->preg4_sec5 = $_POST['GestionProspeccionRp']['ingresolugar'];
                                    break;
                                case 2://  domicilio
                                    $gestionrp->preg4_sec5 = $_POST['GestionProspeccionRp']['ingresolugar'];
                                    break;

                                default:
                                    break;
                            }
                            $gestionrp->preg4_sec4 = '';
                            $gestionrp->save();
                            $con = Yii::app()->db;
                            if ($_POST['yt0'] == 'Grabar') {
                                $sql = "UPDATE gestion_diaria SET status = 1, proximo_seguimiento = '{$_POST['GestionDiaria']['agendamiento']}' WHERE id_informacion = {$id}";
                                $request = $con->createCommand($sql)->query();
                                $this->redirect(array('gestionInformacion/seguimiento'));
                            } else if ($_POST['yt0'] == 'Continuar') {
                                $sql = "UPDATE gestion_diaria SET status = 1, proximo_seguimiento = '', paso = '3' WHERE id_informacion = {$id}";
                                $request = $con->createCommand($sql)->query();
                                $this->redirect(array('gestionConsulta/create', 'id_informacion' => $id, 'tipo' => 'gestion'));
                            }
                            break;
                        case 5:// no contesta
                            $fecha = $this->getFecha($id);
                            $fecha .= '@' . $_POST['GestionDiaria']['agendamiento2'];
                            $con = Yii::app()->db;
                            $sql = "UPDATE gestion_prospeccion_rp SET preg5_sec1 = '{$fecha}' WHERE id_informacion = {$id}";
                            $request = $con->createCommand($sql)->query();

                            $con = Yii::app()->db;
                            $sql = "UPDATE gestion_diaria SET primera_visita = 1, paso = '1-2',proximo_seguimiento='" . $_POST['GestionDiaria']['agendamiento2'] . "' WHERE id_informacion = {$id}";
                            $request = $con->createCommand($sql)->query();
                            $this->redirect(array('gestionInformacion/seguimiento'));
                            break;
                        case 6:// telefono equivocado
                            $gestionrp->preg1 = $pregunta;
                            $gestionrp->id_informacion = $id;
                            $gestionrp->fecha = date("Y-m-d H:i:s");
                            $gestionrp->save();
                            $con = Yii::app()->db;
                            $sql = "UPDATE gestion_diaria SET status = 0, proximo_seguimiento = '' WHERE id_informacion = {$id}";
                            $request = $con->createCommand($sql)->query();
                            $this->redirect(array('gestionInformacion/seguimiento'));
                            break;

                        default:
                            //die('enter def');
                            $con = Yii::app()->db;
                            if ($_POST['yt0'] == 'Grabar') {
                                $sql = "UPDATE gestion_diaria SET status = 1, proximo_seguimiento = '{$_POST['GestionDiaria']['agendamiento']}' WHERE id_informacion = {$id}";
                                $request = $con->createCommand($sql)->query();
                                $this->redirect(array('gestionInformacion/seguimiento'));
                            } else if ($_POST['yt0'] == 'Continuar') {
                                $sql = "UPDATE gestion_diaria SET status = 1, proximo_seguimiento = '', paso = '3' WHERE id_informacion = {$id}";
                                $request = $con->createCommand($sql)->query();
                                $this->redirect(array('site/consulta', 'id_informacion' => $id, 'tipo' => 'gestion', 'fuente' => 'prospeccion'));
                            }
                            break;
                    }
                } else if ($_POST['GestionInformacion']['tipo'] == 'gestion' || $_POST['GestionInformacion']['tipo'] == 'trafico') {
                    //die('enter gestion update');
                    //$this->redirect(array('site/consulta', 'id_informacion' => $id, 'tipo' => 'gestion', 'fuente' => $_POST['GestionInformacion']['tipo']));
                    $this->redirect(array('gestionVehiculo/create', 'id' => $id));
                }
                //die('gestion: '.$_POST['GestionProspeccionPr']['pregunta']);

                $con = Yii::app()->db;
                $sql = "UPDATE gestion_diaria SET primera_visita = 1, paso = '3' WHERE id_informacion = {$id}";
                $request = $con->createCommand($sql)->query();

                //$this->redirect(array('gestionConsulta/create', 'id_informacion' => $model->id, 'tipo' => 'gestion'));
                if ($_POST['tipo'] == 'trafico') {
                    $this->redirect(array('gestionInformacion/seguimiento'));
                }
                //$this->redirect(array('gestionConsulta/create', 'id_informacion' => $model->id, 'tipo' => $_POST['tipo'], 'fuente' => $fuente));
                $this->redirect(array('site/consulta', 'id_informacion' => $model->id, 'tipo' => $_POST['tipo'], 'fuente' => $fuente));
            }
            //$this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model, 'tipo' => $tipo
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
        $dataProvider = new CActiveDataProvider('GestionInformacion');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new GestionInformacion('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['GestionInformacion']))
            $model->attributes = $_GET['GestionInformacion'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return GestionInformacion the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = GestionInformacion::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param GestionInformacion $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'gestion-informacion-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionSeguimientoexcel() {
        $cargo = Yii::app()->user->getState('usuario');
        $cargo_id = (int) Yii::app()->user->getState('cargo_id');
        $grupo_id = (int) Yii::app()->user->getState('grupo_id');
        $id_responsable = Yii::app()->user->getId();
        $array_dealers = $this->getDealerGrupoConc($grupo_id);
        $dealerList = implode(', ', $array_dealers);
        if ($cargo_id != 46)
            $dealer_id = $this->getDealerId($id_responsable);

        $con = Yii::app()->db;

//          echo '<pre>';
//          print_r($_GET);
//          echo '</pre>';
//          die();

        if (count($_GET) == 1) {
            //die('enter default');
            $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, gi.celular, gi.direccion, 
            gi.ruc,gi.pasaporte,gi.email, gi.responsable as resp,gi.tipo_form_web,gi.fecha, gi.bdc, 
            gd.*, gc.preg7 as categorizacion, gd.fuente_contacto as fuente, gd.fuente_contacto_historial, gd.desiste
            FROM gestion_diaria gd 
                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
                INNER JOIN gestion_consulta gc ON gi.id = gc.id_informacion
                INNER JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion 
                WHERE gi.dealer_id = {$this->getConcesionarioDealerId($id_responsable)} 
                ORDER BY gd.id DESC";
            $request = $con->createCommand($sql);
            $users = $request->queryAll();
            $tituloReporte = "Reporte General RGD Concesionario: " . $this->getNameConcesionario($id_responsable);
            $name_file = "Reporte General RGD.xls";
        }

        if (isset($_GET['GestionDiaria2'])) {
            //die('enter gestion diaria2');
//            echo '<pre>';
//            print_r($_GET);
//            echo '</pre>';
//            die();
            $criteria = new CDbCriteria;
            $criteria->select = "gi.id, gi.nombres, gi.apellidos, gi.cedula, gi.celular, gi.direccion, 
            gi.ruc,gi.pasaporte,gi.email, gi.responsable,gi.tipo_form_web,gi.fecha,gi.bdc, gi.dealer_id, gi.id_cotizacion,
            gi.reasignado,gi.responsable_cesado,gi.id_comentario";
            $criteria->alias = 'gi';
            $criteria->join = 'INNER JOIN gestion_diaria gd ON gi.id = gd.id_informacion';
            $criteria->join .= ' LEFT JOIN gestion_consulta gc ON gi.id = gc.id_informacion';
            $criteria->join .= ' INNER JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion';
            $criteria->join .= ' INNER JOIN usuarios u ON u.id = gi.responsable';
            $con = Yii::app()->db;
            $getParams = array();
            
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $fechaActual = date("Y/m/d");
            $params = explode('-', $_GET['GestionDiaria2']['fecha']);
            $fechaPk = 0;
            if (($fechaActual == trim($params[0])) && ($fechaActual == trim($params[1]))) {
                $fechaPk = 1;
            }
            $search = 0;
            //die('fechaPk: '.$fechaPk);
            /* BUSQUEDA EN CAMPOS VACIOS GERENTE COMERCIAL */
            if (empty($_GET['GestionDiaria2']['general']) &&
                    empty($_GET['GestionDiaria2']['categorizacion']) &&
                    $fechaPk == 1 &&
                    empty($_GET['GestionDiaria2']['status']) &&
                    empty($_GET['GestionDiaria2']['responsable']) &&
                    empty($_GET['GestionDiaria2']['concesionario']) &&
                    empty($_GET['GestionDiaria2']['fuente']) && $cargo_id == 69) {
                //echo('enter busqueda general');
                $search = 1;
                $title_busqueda = 'Búsqueda General: ';
                if ($cargo_id == 70) {
                    //die('enter jefe');
                    // SELECT ANTIGUO QUE SE ENLAZABA GON GESTION DIARIA
                    
                $criteria->condition = "gi.bdc = 0 AND gi.dealer_id = {$dealer_id} AND gd.desiste = 0 AND u.cargo_id IN (70,71)";
                $criteria->order = "gi.id DESC";
                    //die('sql sucursal'. $sql);
                }
                if ($cargo_id == 71) {
                    // SELECT ANTIGUO QUE SE ENLAZABA GON GESTION DIARIA
                    
                $criteria->condition = "gi.responsable = {$id_responsable} AND gi.bdc = 0 AND gd.desiste = 0";
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                    //die('sql: '. $sql);
                }

                
                $posts = GestionInformacion::model()->findAll($criteria);
                $tituloReporte = "Reporte General RGD Concesionario: " . $this->getNameConcesionario($id_responsable);
                $name_file = "Reporte General RGD.xls";
            }

            /* BUSQUEDA EN CAMPOS VACIOS */
            if (empty($_GET['GestionDiaria2']['general']) &&
                    $fechaPk == 0 &&
                    empty($_GET['GestionDiaria2']['categorizacion']) &&
                    empty($_GET['GestionDiaria2']['status']) &&
                    empty($_GET['GestionDiaria2']['responsable']) &&
                    empty($_GET['GestionDiaria2']['fecha']) &&
                    empty($_GET['GestionDiaria2']['fuente']) &&
                    empty($_GET['GestionDiaria2']['grupo']) &&
                    empty($_GET['GestionDiaria2']['concesionario'])) {
                //echo('enter busqueda general jefe almacen');
                $search = 2;
                $title_busqueda = 'Búsqueda General: ';
                if ($cargo_id == 70) { // jefe de almacen
                    
                $criteria->condition = "gi.bdc = 0 AND gi.dealer_id = {$dealer_id} AND gd.desiste = 0 AND u.cargo_id IN (70,71)";
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                }
                if ($cargo_id == 71) { // asesor de ventas
                    
                $criteria->condition = "gi.responsable = {$id_responsable} AND gi.bdc = 0 AND gd.desiste = 0";
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                }

                $posts = GestionInformacion::model()->findAll($criteria);
                $tituloReporte = "Reporte General RGD Concesionario: " . $this->getNameConcesionario($id_responsable);
                $name_file = "Reporte General RGD.xls";
            }

            /* -----------------BUSQUEDA GENERAL------------------ */
            if (empty($_GET['GestionDiaria2']['categorizacion']) &&
                    $fechaPk == 1 &&
                    empty($_GET['GestionDiaria2']['responsable']) &&
                    empty($_GET['GestionDiaria2']['tipo_fecha']) &&
                    !empty($_GET['GestionDiaria2']['general'])) {
                $search = 3;
                /* BUSQUEDA POR NOMBRES, APELLIDOS, CEDULA, ID */
                
                if ($cargo_id == 70) { // jefe de almacen
                    
                    if(empty($dealer_id)){
                        $criteria->addCondition("gi.dealer_id IN ({$dealerList})");
                    }else{
                       $criteria->addCondition("gi.dealer_id = {$dealer_id}"); 
                    }
                } else {
                    $criteria->addCondition("gi.responsable = {$id_responsable}");
                }
                $criteria->addCondition("gi.nombres LIKE '%{$_GET['GestionDiaria2']['general']}%' "
                        . "OR gi.apellidos LIKE '%{$_GET['GestionDiaria2']['general']}%' "
                        . "OR gi.cedula LIKE '%{$_GET['GestionDiaria2']['general']}%' OR gi.id = '{$_GET['GestionDiaria2']['general']}'");
                $criteria->group = "gi.id";        
                
                $posts = GestionInformacion::model()->findAll($criteria);

                $tituloReporte = "Reporte General RGD Concesionario: " . $this->getNameConcesionario($id_responsable);
                $name_file = "Reporte General RGD.xls";
                //die('before render seg');
                if (count($posts) > 0) {
                    //$this->render('seguimiento', array('users' => $posts, 'getParams' => $getParams, 'title_busqueda' => $title_busqueda, 'model' => $model));
                    //exit();
                }
            }
            /* -----------------FIN BUSQUEDA GENERAL------------------ */

            /* -----------------BUSQUEDA POR FUENTE PARA GERENTE COMERCIAL------------------ */
            if (!empty($_GET['GestionDiaria2']['fuente']) &&
                    empty($_GET['GestionDiaria2']['general']) &&
                    empty($_GET['GestionDiaria2']['categorizacion']) &&
                    empty($_GET['GestionDiaria2']['status']) &&
                    $fechaPk == 1 &&
                    empty($_GET['GestionDiaria2']['responsable']) &&
                    empty($_GET['GestionDiaria2']['tipo_fecha']) && $cargo_id == 69) {
                $search = 4;
                
                //die('sql: '.$sql);
                $criteria->condition = "gi.responsable = {$id_responsable} AND gn.fuente = '{$_GET['GestionDiaria']['fuente']}' AND gi.dealer_id = {$dealer_id}";
                $criteria->group = "gi.id";
                $posts = GestionInformacion::model()->findAll($criteria);
                $tituloReporte = "Reporte General RGD Por Fuente: " . $_GET['GestionDiaria2']['fuente'];
                $name_file = "Reporte General RGD por Fuente.xls";
                //die('before render seg');
            }

            /* -----------------BUSQUEDA POR FUENTE------------------ */
            if (!empty($_GET['GestionDiaria2']['fuente']) &&
                    empty($_GET['GestionDiaria2']['general']) &&
                    empty($_GET['GestionDiaria2']['categorizacion']) &&
                    empty($_GET['GestionDiaria2']['status']) &&
                    $fechaPk == 1 &&
                    empty($_GET['GestionDiaria2']['tipo_fecha'])) {
                //echo('enter fuente asesor'); 
                $search = 5;
                
                if ($cargo_id == 70) { // jefe de almacen
                    
                    if(empty($dealer_id)){
                        $criteria->addCondition("gi.dealer_id IN ({$dealerList}");
                    }else{
                       $criteria->addCondition("gi.dealer_id = {$dealer_id}"); 
                    }
                } else {
                    $criteria->addCondition("gi.responsable = {$id_responsable}");
                }
                $criteria->addCondition("gn.fuente = '{$_GET['GestionDiaria2']['fuente']}' AND gi.dealer_id = {$dealer_id}");
                $criteria->group = "gi.id";
                $posts = GestionInformacion::model()->findAll($criteria);
                $title_busqueda = 'Búsqueda por Fuente: ';
                $tituloReporte = "Reporte General RGD Por Fuente: " . $_GET['GestionDiaria2']['fuente'];
                $name_file = "Reporte General RGD por Fuente.xls";
            }
            /* -----------------FIN BUSQUEDA POR FUENTE------------------ */

            /* -----------------BUSQUEDA POR CATEGORIZACION------------------ */
            if (!empty($_GET['GestionDiaria2']['categorizacion']) && $fechaPk == 1 && empty($_GET['GestionDiaria2']['tipo_fecha']) && empty($_GET['GestionDiaria2']['general'])) {
                //echo('enter categorizacion');
                
                $search = 6;
                if ($cargo_id == 70) { // jefe de almacen
                    
                    if(empty($dealer_id)){
                        $criteria->addCondition("gi.dealer_id IN ({$dealerList})");
                    }else{
                        $criteria->addCondition("gi.dealer_id = {$dealer_id}");
                    }
                } else {
                    $criteria->addCondition("gi.responsable = {$id_responsable}");
                }

                $criteria->addCondition("gc.preg7 = '{$_GET['GestionDiaria2']['categorizacion']}'");
                $criteria->group = "gi.id";
                $posts = GestionInformacion::model()->findAll($criteria);

                $tituloReporte = "Reporte por Categorización : " . $_GET['GestionDiaria2']['categorizacion'];
                $name_file = "Reporte General RGD por Categorizacion.xls";
                //die('before render seg');
            }
            /* -----------------FIN BUSQUEDA POR CATEGORIZACION DE GERENTE COMERCIAL------------------ */

            if (!empty($_GET['GestionDiaria2']['categorizacion']) && $fechaPk == 1 && $cargo_id == 69 && empty($_GET['GestionDiaria2']['responsable']) && empty($_GET['GestionDiaria2']['tipo_fecha']) && empty($_GET['GestionDiaria2']['general'])) {
                //echo('enter cat');
                
                $search = 7;
                if ($cargo_id == 70) { // jefe de almacen
                    $criteria->addCondition("gi.dealer_id = {$dealer_id}");
                } else {
                    $criteria->addCondition("gi.responsable = {$id_responsable}");
                }

                $criteria->addCondition("gc.preg7 = '{$_GET['GestionDiaria2']['categorizacion']}'");
                $criteria->group = "gi.id";
                //$request = $con->createCommand($sql);
                //$posts = $request->queryAll();
                $posts = GestionInformacion::model()->findAll($criteria);
                $tituloReporte = "Reporte por Categorización : " . $_GET['GestionDiaria2']['categorizacion'];
                $name_file = "Reporte General RGD por Categorizacion.xls";

                //die('before render seg');
            }
            /* -----------------FIN BUSQUEDA POR CATEGORIZACION------------------ */

            /* -----------------BUSQUEDA POR STATUS------------------ */
            if (!empty($_GET['GestionDiaria2']['status']) && $fechaPk == 1 &&
                    empty($_GET['GestionDiaria2']['responsable']) &&
                    empty($_GET['GestionDiaria2']['tipo_fecha'])) {
                //echo('enter to status');
                
                $search = 8;
                if ($cargo_id == 70) { // jefe de almacen
                    
                    if(empty($dealer_id)){
                        $criteria->addCondition("gi.dealer_id IN ({$dealerList})");
                    }else{
                        $criteria->addCondition("gi.dealer_id = {$dealer_id}");
                    }
                } else {
                    $criteria->addCondition("gi.responsable = {$id_responsable}");
                }
                switch ($_GET['GestionDiaria2']['status']) {
                    case 'Cierre':
                        $criteria->addCondition("gd.cierre = 1");
                        break;
                    case 'Desiste':
                        $criteria->addCondition("gd.desiste = 1");
                        break;
                    case 'Entrega':
                        $criteria->addCondition("gd.entrega = 1");
                        break;
                    case 'PrimeraVisita':
                        $criteria->addCondition("gd.paso = '1-2'");
                        break;
                    case 'Seguimiento':
                        $criteria->addCondition("gd.seguimiento = 1");
                        break;
                    case 'SeguimientoEntrega':
                        $criteria->addCondition("gd.seguimiento_entrega = 1");
                        break;
                    case 'qk':
                        $criteria->addCondition("gd.medio_contacto = 'exhquk' AND gd.status = 1");
                        break;

                    default:
                        break;
                }
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                //die('sql: '.$sql);
                //$request = $con->createCommand($sql);
                //$posts = $request->queryAll();
                $posts = GestionInformacion::model()->findAll($criteria);
                $tituloReporte = "Reporte por Status : " . $_GET['GestionDiaria2']['status'];
                $name_file = "Reporte General RGD por Status.xls";
                //die('before render seg');
            }
            /* -----------------END SEARCH BY STATUS------------------ */

            /* -----------------BUSQUEDA POR FECHA------------------ */
            if (empty($_GET['GestionDiaria2']['general']) &&
                    empty($_GET['GestionDiaria2']['categorizacion']) &&
                    empty($_GET['GestionDiaria2']['status']) && $fechaPk == 0 &&
                    empty($_GET['GestionDiaria2']['responsable']) &&
                    !empty($_GET['GestionDiaria2']['fecha']) &&
                    empty($_GET['GestionDiaria2']['concesionario'])) {
                //die('enter fecha');
                $search = 9;
                $tipoFecha = $_GET['GestionDiaria2']['tipo_fecha'];
                $params1 = trim($params[0]);
                $params2 = trim($params[1]);
                //die('after params');
                
                if ($cargo_id == 70) { // jefe de almacen
                    
                    if(empty($dealer_id)){
                        $criteria->addCondition("gi.dealer_id IN ({$dealerList})");
                    }else{
                        $criteria->addCondition("gi.dealer_id = {$dealer_id}");
                    }
                    $criteria->addCondition("u.cargo_id IN(70,71)");
                } else {
                    $criteria->addCondition("gi.responsable = {$id_responsable}");
                }
                $criteria->addCondition(" (gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico')");
                $criteria->addCondition("DATE(gd.fecha) BETWEEN '{$params1}' AND '{$params2}'");
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                //die($sql);

                //$request = $con->createCommand($sql);
                //$posts = $request->queryAll();
                $posts = GestionInformacion::model()->findAll($criteria);
                $tituloReporte = "Reporte por Fecha : " . $_GET['GestionDiaria2']['fecha'];
                $name_file = "Reporte General RGD por Fecha.xls";
            }

            /* -----------------BUSQUEDA POR RESPONSABLE PARA GERENTE COMERCIAL------------------ */
            if (!empty($_GET['GestionDiaria2']['responsable']) && $fechaPk == 1 &&
                    empty($_GET['GestionDiaria2']['general']) &&
                    empty($_GET['GestionDiaria2']['categorizacion']) &&
                    empty($_GET['GestionDiaria2']['tipo_fecha']) &&
                    empty($_GET['GestionDiaria2']['fuente']) &&
                    empty($_GET['GestionDiaria2']['grupo']) &&
                    empty($_GET['GestionDiaria2']['concesionario']) &&
                    empty($_GET['GestionDiaria2']['provincia'])) {
                
                if ($cargo_id == 70) { // jefe de almacen
                    if(empty($dealer_id)){
                        $criteria->addCondition("gi.dealer_id IN ({$dealerList})");
                    }else{
                        $criteria->addCondition("gi.dealer_id = {$dealer_id}");
                    }
                } else {
                    $criteria->addCondition("gi.responsable = {$_GET['GestionDiaria2']['responsable']}");
                }
                
                $criteria->addCondition("gd.desiste = 0");
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                //die($sql);

                //$request = $con->createCommand($sql);
                //$posts = $request->queryAll();
                $posts = GestionInformacion::model()->findAll($criteria);
                $tituloReporte = "Reporte por Responsable : " . $this->getResponsableNombres($_GET['GestionDiaria2']['responsable']);
                $name_file = "Reporte RGD por Responsable.xls";
            }

            /* -----------------BUSQUEDA POR RESPONSABLE PARA JEFE DE ALMACEN------------------ */
            if ($fechaPk == 1 &&
                    empty($_GET['GestionDiaria2']['general']) &&
                    empty($_GET['GestionDiaria2']['categorizacion']) &&
                    empty($_GET['GestionDiaria2']['status']) &&
                    !empty($_GET['GestionDiaria2']['responsable']) &&
                    !empty($_GET['GestionDiaria2']['fecha']) &&
                    empty($_GET['GestionDiaria2']['tipo_fecha']) &&
                    empty($_GET['GestionDiaria2']['fuente'])) {
                //echo ('enter responsable jefe almacen');
                $search = 11;
                
                if ($cargo_id == 70) { // jefe de almacen
                    $criteria->addCondition("gi.responsable = {$_GET['GestionDiaria2']['responsable']}");
                } else {
                    $criteria->addCondition("gi.responsable = {$_GET['GestionDiaria2']['responsable']}");
                }
                $criteria->addCondition("gd.desiste = 0");
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                //die($sql);
                //$request = $con->createCommand($sql);
                //$posts = $request->queryAll();
                $posts = GestionInformacion::model()->findAll($criteria);
                $tituloReporte = "Reporte por Responsable : " . $this->getResponsableNombres($_GET['GestionDiaria2']['responsable']);
                $name_file = "Reporte RGD por Responsable.xls";
                //die('before render seg');
            }

            // BUSQUEDAS COMBINADAS--------------------------------------------------------------------------------------
            
            if ($cargo_id != 46)
                $dealer_id = $this->getDealerId($id_responsable);
            if ($cargo_id == 46) { // super administrador
                $criteria->join .= ' INNER JOIN gr_concesionarios gr ON gr.dealer_id = gi.dealer_id';
            }
            if ($cargo_id == 69) { // gerente comercial
                //die('enter ge');
                if ($get_array == 'bdc') {
                    $criteria->join .= " INNER JOIN gr_concesionarios gr ON gr.dealer_id = gi.dealer_id";
                    $criteria->addCondition("gr.id_grupo = {$grupo_id} AND u.cargo_id IN(72,73)");
                }
                if ($get_array == 'exo') {
                    $criteria->join .= " INNER JOIN gr_concesionarios gr ON gr.dealer_id = gi.dealer_id";
                    $criteria->addCondition("gr.id_grupo = {$grupo_id} AND u.cargo_id IN(75)");
                }
                if ($get_array == 'seg') {
                    $criteria->join .= ' INNER JOIN gr_concesionarios gr ON gr.dealer_id = gi.dealer_id';
                    $criteria->addCondition("gr.id_grupo = {$grupo_id}");
                }
            }
            if ($cargo_id == 70) { // jefe de almacen
                $criteria->addCondition("gi.dealer_id = {$dealer_id}");
                if(empty($dealer_id)){
                    $criteria->addCondition("gi.dealer_id IN ({$dealerList})");
                }
                $criteria->addCondition("(u.cargo_id IN(71,70))");
            }
            if ($cargo_id == 71) { // asesor de ventas
                $$criteria->addCondition("gi.responsable = {$id_responsable}");
            }
            if ($cargo_id == 73) { // asesor bdc
                $criteria->addCondition("gi.responsable = {$id_responsable} AND gi.bdc = 1");
            }
            if ($cargo_id == 75) { // asesor exonerados
                $criteria->addCondition("gi.responsable = {$id_responsable} AND gi.tipo_form_web = 'exonerados'");
            }
            if ($cargo_id == 72) { // JEFE BDC EXONERADOS
                $array_dealers = $this->getDealerGrupoConc($grupo_id);
                $dealerList = implode(', ', $array_dealers);
                if ($get_array == 'exo') {
                    $criteria->addCondition("gi.tipo_form_web = 'exonerados'");
                    $criteria->addCondition("gi.dealer_id IN ({$dealerList})");
                    $criteria->addCondition("u.cargo_id = 75");
                }
                if ($get_array == 'bdc') {
                    $criteria->addCondition("gi.bdc = 1 AND gi.dealer_id IN ({$dealerList})");
                }
            }

            if ($area_id == 4 || $area_id == 12 || $area_id == 13 || $area_id == 14) { // AEKIA USERS
                switch ($get_array) {
                    case 'exo':
                        $criteria->join .= ' INNER JOIN gr_concesionarios gr ON gr.dealer_id = gi.dealer_id';
                        $criteria->addCondition("u.cargo_id IN (75)");
                        break;
                    case 'bdc':
                        $criteria->join .= ' INNER JOIN gr_concesionarios gr ON gr.dealer_id = gi.dealer_id';
                        $criteria->addCondition("u.cargo_id IN (72,73)");
                        break;
                    case 'seg':
                        $criteria->join .= ' INNER JOIN gr_concesionarios gr ON gr.dealer_id = gi.dealer_id';
                        $criteria->addCondition("u.cargo_id IN (70,71)");
                        break;

                    default:
                        $criteria->join .= ' INNER JOIN gr_concesionarios gr ON gr.dealer_id = gi.dealer_id';
                        break;
                }
            }

            $params = explode('-', $_GET['GestionDiaria2']['rango_fecha']);
            $fechaActual = date("Y/m/d");
            $fechaPk2 = 0;
            if (($fechaActual == trim($params[0])) && ($fechaActual == trim($params[1]))) {
                $fechaPk2 = 1;
            }
            $search_type = '';
            // STATUS - CATEGORIZACION
            if ($_GET['busqueda_general2'] == 0 && $_GET['categorizacion2'] == 1 && $_GET['status2'] == 1 && $_GET['responsable2'] == 0 && $fechaPk == 1 && $_GET['seguimiento_rgd2'] == 0 && $fechaPk2 == 1) {
                $search_type = 20;
                $search = 20;
                switch ($_GET['GestionDiaria2']['status']) {
                    case 'Cierre':
                        $criteria->addCondition("gd.cierre = 1");
                        $criteria->addCondition("gd.paso = 9");
                        break;
                    case 'Desiste':
                        $criteria->addCondition("gd.desiste = 1");
                        break;
                    case 'Entrega':
                        $criteria->addCondition("gd.entrega = 1");
                        $sql .= " gd.paso = 9 ";
                        break;
                    case 'PrimeraVisita':
                        $criteria->addCondition("gd.paso = '1-2'");
                        break;
                    case 'Seguimiento':
                        $criteria->addCondition("gd.seguimiento = 1 ");
                        break;
                    case 'SeguimientoEntrega':
                        $criteria->addCondition("gd.seguimiento_entrega = 1 ");
                        break;
                    case 'Vendido':
                        $criteria->addCondition("gd.seguimiento = 1 ");
                        $criteria->addCondition("gd.paso = 10");
                        $criteria->addCondition("gd.status = 1");
                        break;
                    case 'qk':
                        $criteria->addCondition("gd.medio_contacto = 'exhquk' AND gd.status = 1");
                        break; 
                    default:
                        break;
                }
                $criteria->addCondition(" gc.preg7 = '{$_GET['GestionDiaria2']['categorizacion']}'");
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                //$posts = GestionInformacion::model()->findAll($criteria);
                //$request = $con->createCommand($sql);
                //$posts = $request->queryAll();
                $posts = GestionInformacion::model()->findAll($criteria);

            }
            // STATUS - CATEGORIZACION - RESPONSABLE
            if ($_GET['categorizacion2'] == 1 && $_GET['status2'] == 1 && $_GET['responsable2'] == 1 && $fechaPk == 1 && $_GET['seguimiento_rgd2'] == 0 && $fechaPk2 == 1) {
                $search = 21;
                $criteria->condition = "gi.responsable = {$_GET['GestionDiaria2']['responsable']}";
                switch ($_GET['GestionDiaria2']['status']) {
                    case 'Cierre':
                        $criteria->addCondition("gd.cierre = 1");
                        $criteria->addCondition("gd.paso = 9");
                        break;
                    case 'Desiste':
                        $criteria->addCondition("gd.desiste = 1");
                        break;
                    case 'Entrega':
                        $criteria->addCondition("gd.entrega = 1");
                        $criteria->addCondition("gd.paso = 9 ");
                        break;
                    case 'PrimeraVisita':
                        $criteria->addCondition("gd.paso = '1-2'");
                        break;
                    case 'Seguimiento':
                        $criteria->addCondition("gd.seguimiento = 1 ");
                        break;
                    case 'SeguimientoEntrega':
                        $criteria->addCondition("gd.seguimiento_entrega = 1 ");
                        break;
                    case 'Vendido':
                        $criteria->addCondition("gd.seguimiento = 1 ");
                        $criteria->addCondition("gd.paso = 10");
                        $criteria->addCondition("gd.status = 1");
                        break;
                    case 'qk':
                        $criteria->addCondition("gd.medio_contacto = 'exhquk' AND gd.status = 1");
                        break; 
                    default:
                        break;
                }

                $criteria->addCondition(" gc.preg7 = '{$_GET['GestionDiaria2']['categorizacion']}'");
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                //die($sql);
                $responsable = $this->getResponsableNombres($_GET['GestionDiaria2']['responsable']);
                //$posts = GestionInformacion::model()->findAll($criteria);
                //$request = $con->createCommand($sql);
                //$posts = $request->queryAll();
                $posts = GestionInformacion::model()->findAll($criteria);
                $tituloReporte = "Búsqueda por Categorización: {$_GET['GestionDiaria2']['categorizacion']}, Status: {$_GET['GestionDiaria2']['status']}, Responsable: {$responsable}";
                
            }
            
            // CATEGORIZACION - RESPONSABLE
            if ($_GET['categorizacion2'] == 1 && $_GET['status2'] == 0 && $_GET['responsable2'] == 1 && $fechaPk == 1 && $_GET['seguimiento_rgd2'] == 0 && $fechaPk2 == 1) {
                $search = 22;
                $criteria->addCondition("gi.responsable = {$_GET['GestionDiaria2']['responsable']}");
                $criteria->addCondition("gc.preg7 = '{$_GET['GestionDiaria2']['categorizacion']}'");
                $criteria->addCondition("gd.desiste = 0");
                $criteria->group = "gi.id";
                $responsable = $this->getResponsableNombres($_GET['GestionDiaria2']['responsable']);
                
                
                $posts = GestionInformacion::model()->findAll($criteria);
                $tituloReporte = "Búsqueda por Categorización: {$_GET['GestionDiaria2']['categorizacion']} y Responsable: {$responsable}";
            }
            // CATEGORIZACION - RESPONSABLE - SEGUIMIENTO
            if ($_GET['categorizacion2'] == 1 && $_GET['status2'] == 0 && $_GET['responsable2'] == 1 && $fechaPk == 1 && $_GET['seguimiento_rgd2'] == 1 && $fechaPk2 == 1) {
                $search = 23;
                $criteria->addCondition("gi.responsable = {$_GET['GestionDiaria2']['responsable']}");
                $criteria->addCondition("gc.preg7 = '{$_GET['GestionDiaria2']['categorizacion']}'");
                $criteria->addCondition("gd.desiste = 0");
                $fecha_actual = (string) date("Y/m/d");
                switch ($_GET['GestionDiaria2']['seguimiento']) {
                    case 1: // TODAY
                        $criteria->addCondition("DATE(gd.proximo_seguimiento) = '{$fecha_actual}' ");
                        $title_ag = "Agendamiento para hoy: {$fecha_actual}";
                        break;
                    case 2: // EMPTY DATE
                        $criteria->addCondition("gd.proximo_seguimiento = '' ");
                        $title_ag = "Agendamiento: Vacío";
                        break;
                    case 3: // RANGE DATE SEARCH
                        $params = explode('-', $_GET['GestionDiaria2']['rango_fecha']);
                        $fecha1 = trim($params[0]);
                        $fecha2 = trim($params[1]);
                        $criteria->addCondition(" (DATE(gd.proximo_seguimiento) BETWEEN '{$fecha1}' AND '{$fecha2}') ");
                        $title_ag = "Agendamiento para entre: {$fecha1} - {$fecha2} ";
                        break;

                    default:
                        break;
                }
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                $responsable = $this->getResponsableNombres($_GET['GestionDiaria2']['responsable']);
                $posts = GestionInformacion::model()->findAll($criteria);
                $tituloReporte = "Búsqueda por Categorización: {$_GET['GestionDiaria2']['categorizacion']}, Responsable: {$responsable}, {$title_ag} ";
                
            }
            // CATEGORIZACION - RESPONSABLE - SEGUIMIENTO - SEGUIMIENTO FECHA
            if ($_GET['categorizacion2'] == 1 && $_GET['status2'] == 0 && $_GET['responsable2'] == 1 && $fechaPk == 1 && $_GET['seguimiento_rgd2'] == 1 && $fechaPk2 == 0) {
                $search = 24;
                $criteria->addCondition("gi.responsable = {$_GET['GestionDiaria2']['responsable']}");
                $criteria->addCondition("gc.preg7 = '{$_GET['GestionDiaria2']['categorizacion']}'");
                $criteria->addCondition("gd.desiste = 0");
                
                $fecha_actual = (string) date("Y/m/d");
                
                switch ($_GET['GestionDiaria2']['seguimiento']) {
                    case 1: // TODAY
                        $criteria->addCondition("DATE(gd.proximo_seguimiento) = '{$fecha_actual}' ");
                        $title_ag = "Agendamiento para hoy: {$fecha_actual}";
                        break;
                    case 2: // EMPTY DATE
                        $criteria->addCondition("gd.proximo_seguimiento = '' ");
                        $title_ag = "Agendamiento: Vacío";
                        break;
                    case 3: // RANGE DATE SEARCH
                        $params = explode('-', $_GET['GestionDiaria2']['rango_fecha']);
                        $fecha1 = trim($params[0]);
                        $fecha2 = trim($params[1]);
                        $criteria->addCondition(" (DATE(gd.proximo_seguimiento) BETWEEN '{$fecha1}' AND '{$fecha2}') ");
                        $title_ag = "Agendamiento para entre: {$fecha1} - {$fecha2} ";
                        break;

                    default:
                        break;
                }
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                $responsable = $this->getResponsableNombres($_GET['GestionDiaria2']['responsable']);
                $posts = GestionInformacion::model()->findAll($criteria);
                $tituloReporte = "Búsqueda por Categorización: {$_GET['GestionDiaria2']['categorizacion']}, Responsable: {$responsable}, {$title_ag} ";
                
            }
            // CATEGORIZACION - FECHA DE REGISTRO
            if ($_GET['categorizacion2'] == 1 && $_GET['status2'] == 0 && $_GET['responsable2'] == 0 && $_GET['fecha2'] == 1 && $_GET['seguimiento_rgd2'] == 0 && $_GET['fecha_segumiento2'] == 0) {
                $search = 25;
                $params = explode('-', $_GET['GestionDiaria2']['fecha']);
                $params1 = trim($params[0]);
                $params2 = trim($params[1]);
                $criteria->addCondition(" (DATE(gd.proximo_seguimiento) BETWEEN '{$fecha1}' AND '{$fecha2}') ");
                $criteria->addCondition("gc.preg7 = '{$_GET['GestionDiaria2']['categorizacion']}' ");
                $criteria->addCondition("gd.desiste = 0");
                
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                //$request = $con->createCommand($sql);
                //$posts = $request->queryAll();
                $posts = GestionInformacion::model()->findAll($criteria);
                $tituloReporte = "Búsqueda por Categorización: {$_GET['GestionDiaria2']['categorizacion']}, Fecha: {$_GET['GestionDiaria2']['fecha']}";
                
            }
            // STATUS - FECHA DE REGISTRO
            if ($_GET['categorizacion2'] == 0 && $_GET['status2'] == 1 && $_GET['responsable2'] == 0 && $_GET['fecha2'] == 1 && $_GET['seguimiento_rgd2'] == 0 && $_GET['fecha_segumiento2'] == 0) {
                $search = 26;
                $params = explode('-', $_GET['GestionDiaria2']['fecha']);
                $params1 = trim($params[0]);
                $params2 = trim($params[1]);
                
                switch ($_GET['GestionDiaria2']['status']) {
                    case 'Cierre':
                        $criteria->addCondition("gd.cierre = 1");
                        $criteria->addCondition("gd.paso = 9");
                        break;
                    case 'Desiste':
                        $criteria->addCondition("gd.desiste = 1");
                        break;
                    case 'Entrega':
                        $criteria->addCondition("gd.entrega = 1");
                        $criteria->addCondition("gd.paso = 9 ");
                        break;
                    case 'PrimeraVisita':
                        $criteria->addCondition("gd.paso = '1-2'");
                        break;
                    case 'Seguimiento':
                        $criteria->addCondition("gd.seguimiento = 1 ");
                        break;
                    case 'SeguimientoEntrega':
                        $criteria->addCondition("gd.seguimiento_entrega = 1 ");
                        break;
                    case 'Vendido':
                        $criteria->addCondition("gd.seguimiento = 1 ");
                        $criteria->addCondition("gd.paso = 10");
                        $criteria->addCondition("gd.status = 1");
                        break;
                    case 'qk':
                        $criteria->addCondition("gd.medio_contacto = 'exhquk' AND gd.status = 1");
                        break; 
                    default:
                        break;
                }
                $criteria->addCondition(" (DATE(gi.fecha) BETWEEN '{$params1}' AND '{$params2}')");
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                //$request = $con->createCommand($sql);
                //$posts = $request->queryAll();
                $posts = GestionInformacion::model()->findAll($criteria);
                $tituloReporte = "Búsqueda por Status: {$_GET['GestionDiaria2']['status']}, Fecha: {$_GET['GestionDiaria2']['fecha']}";
                
            }
            // CATEGORIZACION - RESPONSABLE - FECHA DE REGISTRO
            if ($_GET['categorizacion2'] == 1 && $_GET['status2'] == 0 && $_GET['responsable2'] == 1 && $_GET['fecha2'] == 1 && $_GET['seguimiento_rgd2'] == 0 && $_GET['fecha_segumiento2'] == 0) {
                $search = 27;
                $params = explode('-', $_GET['GestionDiaria2']['fecha']);
                $params1 = trim($params[0]);
                $params2 = trim($params[1]);
                $criteria->addCondition("gi.responsable = {$_GET['GestionDiaria2']['responsable']}");
                $criteria->addCondition("(DATE(gi.fecha) BETWEEN '{$params1}' AND '{$params2}')");
                $criteria->addCondition("gc.preg7 = '{$_GET['GestionDiaria2']['categorizacion']}'");
                $criteria->addCondition("gd.desiste = 0");
                $fecha_actual = (string) date("Y/m/d");

                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                $responsable = $this->getResponsableNombres($_GET['GestionDiaria2']['responsable']);
                $posts = GestionInformacion::model()->findAll($criteria);
                $tituloReporte = "Búsqueda por Categorización: {$_GET['GestionDiaria']['categorizacion']}, Responsable: {$responsable} Fecha de Registro: {$_GET['GestionDiaria']['fecha']}";
                
            }
            
            
            // RESPONSABLE - SEGUIMIENTO
            if ($_GET['categorizacion2'] == 0 && $_GET['status2'] == 0 && $_GET['responsable2'] == 1 && $_GET['fecha2'] == 0 && $_GET['seguimiento_rgd2'] == 1 && $_GET['fecha_segumiento2'] == 0) {
                $search = 28;
                $sql .= " and gi.responsable = {$_GET['GestionDiaria2']['responsable']}";
                $sql .= ' and gd.desiste = 0 and ';
                $criteria->addCondition("gi.responsable = {$_GET['GestionDiaria2']['responsable']}");
                $criteria->addCondition("gd.desiste = 0");
                $fecha_actual = (string) date("Y/m/d");
                switch ($_GET['GestionDiaria2']['seguimiento']) {
                    case 1: // TODAY
                        $criteria->addCondition("DATE(gd.proximo_seguimiento) = '{$fecha_actual}' ");
                        $title_ag = "Agendamiento para hoy: {$fecha_actual}";
                        break;
                    case 2: // EMPTY DATE
                        $criteria->addCondition("gd.proximo_seguimiento = '' ");
                        $title_ag = "Agendamiento: Vacío";
                        break;
                    case 3: // RANGE DATE SEARCH
                        $params = explode('-', $_GET['GestionDiaria2']['rango_fecha']);
                        $fecha1 = trim($params[0]);
                        $fecha2 = trim($params[1]);
                        $criteria->addCondition(" (DATE(gd.proximo_seguimiento) BETWEEN '{$fecha1}' AND '{$fecha2}') ");
                        $title_ag = "Agendamiento para entre: {$fecha1} - {$fecha2} ";
                        break;

                    default:
                        break;
                }
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                $responsable = $this->getResponsableNombres($_GET['GestionDiaria2']['responsable']);
                //$request = $con->createCommand($sql);
                //$posts = $request->queryAll();
                $posts = GestionInformacion::model()->findAll($criteria);
                //$request = $con->createCommand($sql);
                //$users = $request->queryAll();
                $tituloReporte = "Búsqueda por Responsable: {$responsable}, {$title_ag} ";
                
            }
            // RESPONSABLE - SEGUIMIENTO - SEGUIMIENTO FECHA
            if ($_GET['categorizacion2'] == 0 && $_GET['status2'] == 0 && $_GET['responsable2'] == 1 && $_GET['fecha2'] == 0 && $_GET['seguimiento_rgd2'] == 1 && $_GET['fecha_segumiento2'] == 1) {
                $search = 29;
                
                $criteria->addCondition("gi.responsable = {$_GET['GestionDiaria2']['responsable']}");
                $criteria->addCondition("gd.desiste = 0");
                $fecha_actual = (string) date("Y/m/d");
                switch ($_GET['GestionDiaria2']['seguimiento']) {
                    case 1: // TODAY
                        $criteria->addCondition("DATE(gd.proximo_seguimiento) = '{$fecha_actual}' ");
                        $title_ag = "Agendamiento para hoy: {$fecha_actual}";
                        break;
                    case 2: // EMPTY DATE
                        $criteria->addCondition("gd.proximo_seguimiento = '' ");
                        $title_ag = "Agendamiento: Vacío";
                        break;
                    case 3: // RANGE DATE SEARCH
                        $params = explode('-', $_GET['GestionDiaria2']['rango_fecha']);
                        $fecha1 = trim($params[0]);
                        $fecha2 = trim($params[1]);
                        $criteria->addCondition(" (DATE(gd.proximo_seguimiento) BETWEEN '{$fecha1}' AND '{$fecha2}') ");
                        $title_ag = "Agendamiento para entre: {$fecha1} - {$fecha2} ";
                        break;

                    default:
                        break;
                }
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                $responsable = $this->getResponsableNombres($_GET['GestionDiaria2']['responsable']);
                //$request = $con->createCommand($sql);
                $posts = GestionInformacion::model()->findAll($criteria);

                //$request = $con->createCommand($sql);
                //$users = $request->queryAll();
                $tituloReporte = "Búsqueda por Responsable: {$responsable}, {$title_ag} ";
                
            }
            // SEGUIMIENTO
            if ($_GET['categorizacion2'] == 0 && $_GET['status2'] == 0 && $_GET['responsable2'] == 0 && $_GET['fecha2'] == 0 && $_GET['seguimiento_rgd2'] == 1 && $_GET['fecha_segumiento2'] == 0) {
                $search = 30;
                
                $criteria->addCondition("gd.desiste = 0");
                $fecha_actual = (string) date("Y/m/d");
                switch ($_GET['GestionDiaria2']['seguimiento']) {
                    case 1: // TODAY
                        $criteria->addCondition("DATE(gd.proximo_seguimiento) = '{$fecha_actual}' ");
                        $title_ag = "Agendamiento para hoy: {$fecha_actual}";
                        break;
                    case 2: // EMPTY DATE
                        $criteria->addCondition("gd.proximo_seguimiento = '' ");
                        $title_ag = "Agendamiento: Vacío";
                        break;
                    case 3: // RANGE DATE SEARCH
                        $params = explode('-', $_GET['GestionDiaria2']['rango_fecha']);
                        $fecha1 = trim($params[0]);
                        $fecha2 = trim($params[1]);
                        $criteria->addCondition(" (DATE(gd.proximo_seguimiento) BETWEEN '{$fecha1}' AND '{$fecha2}') ");
                        $title_ag = "Agendamiento para entre: {$fecha1} - {$fecha2} ";
                        break;

                    default:
                        break;
                }
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                $responsable = $this->getResponsableNombres($_GET['GestionDiaria2']['responsable']);
                //$request = $con->createCommand($sql);
                //$posts = $request->queryAll();
                $posts = GestionInformacion::model()->findAll($criteria);
                //$request = $con->createCommand($sql);
                //$users = $request->queryAll();
                $tituloReporte = "Búsqueda por {$title_ag} ";
                
                
            }
            // RESPONSABLE - FECHA DE REGISTRO
            if ($_GET['categorizacion2'] == 0 && $_GET['status2'] == 0 && $_GET['responsable2'] == 1 && $_GET['fecha2'] == 1 && $_GET['seguimiento_rgd2'] == 0 && $_GET['fecha_segumiento2'] == 0) {
                $search = 31;
                $params = explode('-', $_GET['GestionDiaria2']['fecha']);
                $params1 = trim($params[0]);
                $params2 = trim($params[1]);
                $criteria->addCondition("gi.responsable = {$_GET['GestionDiaria2']['responsable']}");
                $criteria->addCondition("(DATE(gi.fecha) BETWEEN '{$params1}' AND '{$params2}')");
                $criteria->addCondition("gd.desiste = 0");
                $fecha_actual = (string) date("Y/m/d");

                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                $responsable = $this->getResponsableNombres($_GET['GestionDiaria2']['responsable']);
                $posts = GestionInformacion::model()->findAll($criteria);
//                echo '<pre>';
//                print_r($criteria);
//                echo '</pre>';
//                die();
                $tituloReporte = "Búsqueda por Responsable: {$responsable}, Fecha de Registro: {$_GET['GestionDiaria2']['fecha']}";
                
            }
            // STATUS - RESPONSABLE
            if ($_GET['categorizacion2'] == 0 && $_GET['status2'] == 1 && $_GET['responsable2'] == 1 && $fechaPk == 1 && $_GET['seguimiento_rgd2'] == 0 && $fechaPk2 == 1) {
                $search = 32;
                $criteria->condition = "gi.responsable = {$_GET['GestionDiaria2']['responsable']}";
                switch ($_GET['GestionDiaria2']['status']) {
                    case 'Cierre':
                        $criteria->addCondition("gd.cierre = 1");
                        $criteria->addCondition("gd.paso = 9");
                        break;
                    case 'Desiste':
                        $criteria->addCondition("gd.desiste = 1");
                        break;
                    case 'Entrega':
                        $criteria->addCondition("gd.entrega = 1");
                        $criteria->addCondition("gd.paso = 9 ");
                        break;
                    case 'PrimeraVisita':
                        $criteria->addCondition("gd.paso = '1-2'");
                        break;
                    case 'Seguimiento':
                        $criteria->addCondition("gd.seguimiento = 1 ");
                        break;
                    case 'SeguimientoEntrega':
                        $criteria->addCondition("gd.seguimiento_entrega = 1 ");
                        break;
                    case 'Vendido':
                        $criteria->addCondition("gd.seguimiento = 1 ");
                        $criteria->addCondition("gd.paso = 10");
                        $criteria->addCondition("gd.status = 1");
                        break;
                    case 'qk':
                        $criteria->addCondition("gd.medio_contacto = 'exhquk' AND gd.status = 1");
                        break; 
                    default:
                        break;
                }
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                $responsable = $this->getResponsableNombres($_GET['GestionDiaria2']['responsable']);
//                echo '<pre>';
//               print_r($criteria);
//               echo '</pre>';
//               die();
                $posts = GestionInformacion::model()->findAll($criteria);
                $tituloReporte = "Búsqueda por Status: {$_GET['GestionDiaria2']['status']}, Responsable: {$responsable}";
                
            }
            //   echo '<pre>';
            //   print_r($criteria);
            //   echo '</pre>';
            //   die();
            
            //die('search type: '.$search);

            //$this->render('seguimiento');
        }
        //die('search: '.$search);
        Yii::import('ext.phpexcel.XPHPExcel');
        $objPHPExcel = XPHPExcel::createPHPExcel();
        $objPHPExcel->getProperties()->setCreator("SGC Kia Ecuador")
                ->setLastModifiedBy("SGC")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");
        $estiloTituloReporte = array(
            'font' => array(
                'name' => 'Tahoma',
                'bold' => true,
                'italic' => false,
                'strike' => false,
                'size' => 11,
                'color' => array(
                    'rgb' => 'B6121A'
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'rotation' => 0,
                'wrap' => TRUE
            )
        );

        $estiloTituloColumnas = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'size' => 9,
                'color' => array(
                    'rgb' => '333333'
                )
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => 'F1F1F1')
            ),
            'borders' => array(
                'top' => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                    'color' => array(
                        'rgb' => '143860'
                    )
                ),
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                    'color' => array(
                        'rgb' => '143860'
                    )
                ),
                'left' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => '143860'
                    )
                ),
                'right' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => '143860'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => TRUE
            )
        );
        // Se combinan las celdas A1 hasta F1, para colocar ahí el titulo del reporte
        $objPHPExcel->setActiveSheetIndex(0)
                ->mergeCells('A1:N1');
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', $tituloReporte) // Titulo del reporte
                ->setCellValue('A2', 'Status')
                ->setCellValue('B2', 'ID')
                ->setCellValue('C2', 'Fecha de Registro')
                ->setCellValue('D2', 'Nombres')
                ->setCellValue('E2', 'Apellidos')
                ->setCellValue('F2', 'Identificación')
                ->setCellValue('G2', 'Email')
                ->setCellValue('H2', 'Celular')
                ->setCellValue('I2', 'Direccion')
                ->setCellValue('J2', 'Responsable')
                ->setCellValue('K2', 'Concesionario')
                ->setCellValue('L2', 'Modelo')
                ->setCellValue('M2', 'Versiones')
                ->setCellValue('N2', 'Test Drive')
                ->setCellValue('O2', 'Proximo Seguimiento 1')
                ->setCellValue('P2', 'Proximo Seguimiento 2')
                ->setCellValue('Q2', 'Categorización')
                ->setCellValue('R2', 'Fuente')
                ->setCellValue('S2', 'Fuente Historial')
                ->setCellValue('T2', 'Desiste');
        $i = 3;
        /* echo '<pre>';
          print_r($casos);
          echo '</pre>';
          die(); */
        //die('count: '.count($posts));
        foreach ($posts as $row) {
            $identificacion = '';
            if ($row['cedula'] != '') {
                $identificacion = $row['cedula'];
            }
            if ($row['pasaporte'] != '') {
                $identificacion = $row['pasaporte'];
            }
            if ($row['ruc'] != '') {
                $identificacion = $row['ruc'];
            }
            $modeloVehiculo = ' ';
            $versionVehiculo = '';
            $td_si = '';
            $td_no = '';
            $countvec = GestionVehiculo::model()->count(array('condition' => "id_informacion = {$row['id']}"));
            if ($countvec > 0) {
                $vec = GestionVehiculo::model()->findAll(array('condition' => "id_informacion = {$row['id']}", 'order' => 'id desc'));
                foreach ($vec as $val) {
                    $modeloVehiculo .= $this->getModel($val['modelo']) . '-';
                    $versionVehiculo .= $this->getVersion($val['version']). '/';
                }
                $tdv = GestionTestDrive::model()->findAll(array('condition' => "id_informacion = {$row['id']}", 'order' => 'id_vehiculo desc'));
                foreach ($tdv as $vc) {
                    if($vc['test_drive'] == 1){$td_si .= 'Si-';}else{$td_no = 'No';}
                }
            }
            $paso = $this->getPasoGestionDiaria($row['id']);
            $proximo_seguimiento = $this->getSeguimiento($row['id']);
            $proxseg = explode(' ',$proximo_seguimiento);
            $modeloVehiculo = substr($modeloVehiculo, 0, -1);
            $versionVehiculo = substr($versionVehiculo, 0, -1);
            $categorizacion = $this->getCategorizacionSGC($row['id']);
            $fuente = $this->getFuenteSGC($row['id']);
            $status = $this->getStatusSGC($row['id']);
            $fuente_contacto = $this->getFuenteContacto($row['id']);
            $fuente_contacto_historial = $this->getFuenteContactoHistorial($row['id']);
            $desiste = $this->getDesiste($row['id']);
            //$td_si = substr($td_si, 0, -1);
            $td_si = $this->getListaTDExcel($row['id']);
            $test_drive = '';
            //die ('id info: '.$row['id_info'].', id vec: '.$row['id']);
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $this->getPasoSeguimiento($paso))
                    ->setCellValue('B' . $i, $row['id'])
                    ->setCellValue('C' . $i, ($row['fecha']))
                    ->setCellValue('D' . $i, ($row['nombres']))
                    ->setCellValue('E' . $i, ($row['apellidos']))
                    ->setCellValue('F' . $i, $identificacion)
                    ->setCellValue('G' . $i, $row['email'])
                    ->setCellValue('H' . $i, $row['celular'])
                    ->setCellValue('I' . $i, $row['direccion'])
                    ->setCellValue('J' . $i, $this->getResponsableNombres($row['responsable']))
                    ->setCellValue('K' . $i, $this->getNameConcesionarioById($row['dealer_id']))
                    ->setCellValue('L' . $i, $modeloVehiculo)
                    ->setCellValue('M' . $i, $versionVehiculo)
                    ->setCellValue('N' . $i, $td_si)
                    ->setCellValue('O' . $i, $proximo_seguimiento)
                    ->setCellValue('P' . $i, $proxseg[0])
                    ->setCellValue('Q' . $i, $categorizacion)
                    ->setCellValue('R' . $i, $fuente)
                    ->setCellValue('S' . $i, $fuente_contacto_historial)
                    ->setCellValue('T' . $i, $desiste);

            $objPHPExcel->getActiveSheet()->setCellValueExplicit('F' . $i, $identificacion, PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('H' . $i, $row['celular'], PHPExcel_Cell_DataType::TYPE_STRING);
            //$objPHPExcel->getActiveSheet()->setCellValueExplicit('P' . $i, $row['celular'], PHPExcel_Cell_DataType::TYPE_STRING);
            //$objPHPExcel->getActiveSheet()->setCellValueExplicit('N' . $i, $row['comentario'], PHPExcel_Cell_DataType::TYPE_STRING);
            //$objPHPExcel->getActiveSheet()->getStyle('M' . $i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $i++;
        }
        $style1 = array(
            'font' => array(
                'name' => 'Calibri',
                'bold' => true,
                'size' => 11,
            ),
        );

        $objPHPExcel->getActiveSheet()->getStyle('A2:M2')->applyFromArray($style1);
        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("G")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("H")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("I")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("J")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("K")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("L")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("M")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("N")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("O")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("P")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("Q")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("R")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("S")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("T")->setAutoSize(true);
        // rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Reporte de casos');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->applyFromArray($estiloTituloReporte);
        $objPHPExcel->getActiveSheet()->getStyle('A2:T2')->applyFromArray($estiloTituloColumnas);

        // Inmovilizar paneles
        $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0, 3);

        // Redirect output to a client's web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $name_file . '');
        header('Cache-Control: max-age=0');
        //      If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        //      If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        Yii::app()->end();
    }

    /**
     * Sql constructor by combined inputs from RGD Intranet
     * @param type $fechaPk
     */
    public function getSqlCombined($fechaPk) {
        $params = explode('-', $_GET['GestionDiaria']['rango_fecha']);
        $fechaActual = date("Y/m/d");
        $fechaPk2 = 0;
        if (($fechaActual == trim($params[0])) && ($fechaActual == trim($params[1]))) {
            $fechaPk2 = 1;
        }
        //echo '<br />fechaPk: '.$fechaPk.', fechaPk2: '.$fechaPk2;
        // STATUS - CATEGORIZACION
        if ($_GET['busqueda_general'] == 0 && $_GET['categorizacion'] == 1 && $_GET['status'] == 1 && $_GET['responsable'] == 0 && $fechaPk == 1 && $_GET['seguimiento_rgd'] == 0 && $fechaPk2 == 1) {
            $search_type = 20;
        }
        // STATUS - CATEGORIZACION - RESPONSABLE
        if ($_GET['categorizacion'] == 1 && $_GET['status'] == 1 && $_GET['responsable'] == 1 && $fechaPk == 1 && $_GET['seguimiento_rgd'] == 0 && $fechaPk2 == 1) {
            $search_type = 21;
        }
        // CATEGORIZACION - RESPONSABLE
        if ($_GET['categorizacion'] == 1 && $_GET['status'] == 0 && $_GET['responsable'] == 1 && $fechaPk == 1 && $_GET['seguimiento_rgd'] == 0 && $fechaPk2 == 1) {
            $search_type = 22;
        }
        // CATEGORIZACION - RESPONSABLE - SEGUIMIENTO
        if ($_GET['categorizacion'] == 1 && $_GET['status'] == 0 && $_GET['responsable'] == 1 && $fechaPk == 1 && $_GET['seguimiento_rgd'] == 1 && $fechaPk2 == 1) {
            $search_type = 23;
        }
        // CATEGORIZACION - RESPONSABLE - SEGUIMIENTO - SEGUIMIENTO FECHA
        if ($_GET['categorizacion'] == 1 && $_GET['status'] == 0 && $_GET['responsable'] == 1 && $fechaPk == 1 && $_GET['seguimiento_rgd'] == 1 && $fechaPk2 == 0) {
            $search_type = 24;
        }
        // CATEGORIZACION - FECHA DE REGISTRO
        if ($_GET['categorizacion'] == 1 && $_GET['status'] == 0 && $_GET['responsable'] == 0 && $fechaPk == 0 && $_GET['seguimiento_rgd'] == 0 && $fechaPk2 == 1) {
            $search_type = 25;
        }
        // STATUS - FECHA DE REGISTRO
        if ($_GET['categorizacion'] == 0 && $_GET['status'] == 1 && $_GET['responsable'] == 0 && $fechaPk == 0 && $_GET['seguimiento_rgd'] == 0 && $fechaPk2 == 1) {
            $search_type = 26;
        }
        // CATEGORIZACION - RESPONSABLE - FECHA DE REGISTRO
        if ($_GET['categorizacion'] == 1 && $_GET['status'] == 0 && $_GET['responsable'] == 1 && $fechaPk == 0 && $_GET['seguimiento_rgd'] == 0 && $fechaPk2 == 1) {
            $search_type = 27;
        }
        // RESPONSABLE - SEGUIMIENTO
        if ($_GET['categorizacion'] == 0 && $_GET['status'] == 0 && $_GET['responsable'] == 1 && $fechaPk == 1 && $_GET['seguimiento_rgd'] == 1 && $fechaPk2 == 1) {
            $search_type = 28;
        }
        // RESPONSABLE - SEGUIMIENTO - SEGUIMIENTO FECHA
        if ($_GET['categorizacion'] == 0 && $_GET['status'] == 0 && $_GET['responsable'] == 1 && $fechaPk == 1 && $_GET['seguimiento_rgd'] == 1 && $fechaPk2 == 0) {
            $search_type = 29;
        }
        return $search_type;
    }

    public function actionSeguimiento($tipo_seg = NULL) {
        //$this->layout = '//layouts/callventas';
        //echo 'TIPO SEG: '.$tipo_seg;
        $cargo = Yii::app()->user->getState('usuario');
        $cargo_id = (int) Yii::app()->user->getState('cargo_id');
        $cargo_adicional = (int) Yii::app()->user->getState('cargo_adicional');
        $grupo_id = (int) Yii::app()->user->getState('grupo_id');
        $tipo_grupo = 1; // GRUPOS ASIAUTO, KMOTOR POR DEFECTO
        if($grupo_id == 4 || $grupo_id == 5 || $grupo_id == 6 || $grupo_id == 2 || $grupo_id == 8 || $grupo_id == 9 ){
            $tipo_grupo = 0; // GRUPOS MOTRICENTRO, MERQUIAUTO, AUTHESA, ASIAUTO, IOKARS
        }
        $area_id = (int) Yii::app()->user->getState('area_id');
        //die('cargo id:'.$cargo_id);
        $id_responsable = Yii::app()->user->getId();
        $array_dealers = $this->getDealerGrupoConc($grupo_id);
        $dealerList = implode(', ', $array_dealers);

        date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
        $dt_hoy = date('Y-m-d'); // Fecha actual
        $dt_unasemana_antes = date('Y-m-d', strtotime('-1 day')); // Fecha resta 1 semanas
        $dt_unasemana_antes_nuevo = date('Y-m-d', strtotime('-1 week')); // Fecha resta 1 semanas
        $dt_unmes_antes = date('Y-m-d', strtotime('-4 week')); // Fecha resta 1 mes
        //die('responsable id: '.$id_responsable);
        if ($cargo_id != 46)
            $dealer_id = $this->getDealerId($id_responsable);
        //die($dealer_id);
        $model = new GestionNuevaCotizacion;
        $con = Yii::app()->db;
        $criteria = new CDbCriteria;
        $criteria->select = "gi.id , gi.nombres, gi.apellidos, gi.cedula, 
            gi.ruc,gi.pasaporte,gi.email, gi.responsable,gi.tipo_form_web,gi.fecha, gi.bdc, gi.dealer_id, gi.id_cotizacion,
            gi.reasignado,gi.responsable_cesado,gi.id_comentario";
        $criteria->alias = 'gi';
        $criteria->join = 'INNER JOIN gestion_diaria gd ON gi.id = gd.id_informacion';
        $criteria->join .= ' LEFT JOIN gestion_consulta gc ON gi.id = gc.id_informacion';
        $criteria->join .= ' INNER JOIN usuarios u ON u.id = gi.responsable';
        
        switch ($cargo_id) {
            case 46: // SUPER ADMINISTRADOR AEKIA
                $criteria->join .= ' INNER JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion';
                $criteria->condition = "gd.desiste = 0 AND gd.paso <> '10' AND gd.status = 1 ";
                $criteria->group = "gi.id";
                $criteria->order = "gi.id DESC";
                break;
            case 70: // JEFE DE SUCURSAL
                if($cargo_adicional == 0){ // CON UN SOLO CARGO
                    $array_dealers = $this->getResponsablesVariosConc();
                    if(count($array_dealers) == 0){
                        $array_dealers = $this->getDealerGrupoConcUsuario($id_responsable);
                    }
                    $dealerList = implode(', ', $array_dealers);
                    $criteria->join .= ' INNER JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion';
                    $criteria->condition = "gd.desiste = 0 AND gd.paso <> '10' AND gd.status = 1 ";
                    $criteria->addCondition("gi.dealer_id IN ({$dealerList})");
                    $criteria->addCondition("u.cargo_id IN (70,71)");
                    $criteria->addCondition("DATE(gd.fecha) BETWEEN '{$dt_unasemana_antes}' and '{$dt_hoy}'");
                    $criteria->group = 'gi.id';
                    $criteria->order = "gi.id DESC";
                }
                if ($cargo_adicional == 85 && $tipo_grupo == 0) { // JEFE DE SUCURSAL CON SEGUNDO CARGO JEFE DE VENTAS EXTERNAS - CONCESIONARIOS INDEPENDIENTES
                    //die('enter jefe');
                    $array_dealers = $this->getResponsablesVariosConc();
                    if(count($array_dealers) == 0){
                        $array_dealers = $this->getDealerGrupoConcUsuario($id_responsable);
                    }
                    $dealerList = implode(', ', $array_dealers);
                    $criteria->join .= ' INNER JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion';
                    $criteria->condition = "gd.desiste = 0 AND gd.paso <> '10' AND gd.status = 1 ";
                    $criteria->addCondition("gi.dealer_id IN ({$dealerList})");
                    $criteria->addCondition("gi.bdc = 0 OR gi.bdc = 1");
                    $criteria->addCondition("u.cargo_id IN (70,71)");
                    $criteria->addCondition("DATE(gd.fecha) BETWEEN '{$dt_unasemana_antes}' and '{$dt_hoy}'");
                    $criteria->group = 'gi.id';
                    $criteria->order = "gi.id DESC";
                }
                break;
            case 71: // ASESOR DE VENTAS
                // ASESOR DE VENTAS Y ASESOR DE VENTAS EXTERNAS DOBLE CARGO
                if ($cargo_adicional == 86 && $tipo_grupo == 0) { 
                    //die('enter jefe');
                    $array_dealers = $this->getResponsablesVariosConc();
                    if(count($array_dealers) == 0){
                        $array_dealers = $this->getDealerGrupoConcUsuario($id_responsable);
                    }
                    $dealerList = implode(', ', $array_dealers);
                    $criteria->condition = "gd.desiste = 0 AND gd.paso <> '10' ";
                    $criteria->addCondition("gi.dealer_id IN ({$dealerList})");
                    $criteria->addCondition("(gi.responsable = {$id_responsable} OR gi.responsable_origen = {$id_responsable})");
                    //$criteria->addCondition("gi.bdc = 0");
                    $criteria->addCondition("u.cargo_id IN (71)");
                    //$criteria->addCondition("DATE(gd.fecha) BETWEEN '{$dt_unasemana_antes_nuevo}' and '{$dt_hoy}'");
                    $criteria->group = 'gi.id';
                    $criteria->order = "gi.id DESC";
                }
                // ASESOR DE VENTAS UN SOLO CARGO
                if ($cargo_adicional == 0) { 
                    $criteria->join .= ' LEFT JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion';
                    $criteria->condition = "gd.desiste = 0 AND gd.paso <> '10' AND gd.status = 1 ";
                    $criteria->addCondition("(gi.responsable = {$id_responsable} OR gi.responsable_origen = {$id_responsable})");
                    $criteria->addCondition("gi.bdc = 0");
                    $criteria->addCondition("u.cargo_id = 71");
                    $criteria->addCondition("gd.desiste = 0", 'AND');
                    //$criteria->addCondition("DATE(gd.fecha) BETWEEN '{$dt_unasemana_antes_nuevo}' and '{$dt_hoy}'");
                    $criteria->group = "gi.id";
                    $criteria->order = "gi.id DESC";
                }
                break;
            case 69: // GERENTE COMERCIAL DE CONCESIONARIO
                $criteria->join .= ' INNER JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion';
                $criteria->condition = "gd.desiste = 0 AND gd.paso <> '10' AND gd.status = 1 ";
                $criteria->addCondition("u.grupo_id = {$grupo_id}");
                $criteria->addCondition("u.cargo_id IN (70,71)");
                $criteria->addCondition("DATE(gi.fecha) BETWEEN '{$dt_unasemana_antes}' and '{$dt_hoy}'");
                if($tipo_seg == 'exhibicion' || $tipo_seg == 'exh'){
                    $criteria->addCondition("gd.fuente_contacto = 'exhibicion' OR gd.fuente_contacto = 'exhibicion quierounkia'");
                }
                if($tipo_seg == ''){
                    $criteria->addCondition("gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico'");
                }
                $criteria->group = 'gi.id';
                $criteria->order = "gi.id DESC";
                break;

            default:
                break;
        }

        if ($area_id == 4 || $area_id == 12 || $area_id == 13 || $area_id == 14) {
            //die('tipo: '.$tipo);
            $criteria->join .= ' LEFT JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion';
            $criteria->condition = "gi.bdc = 0 AND gd.desiste = 0 AND gd.paso <> '10' AND gd.status = 1 ";
            switch ($tipo_seg) {
                case 'exhibicion':
                case 'exh':    
                    $criteria->addCondition(" gd.fuente_contacto = 'exhibicion'"); 
                    $criteria->addCondition("DATE(gi.fecha) = '{$dt_unasemana_antes}'");
                    break;
                case 'exhqk':    
                    $criteria->addCondition(" gd.fuente_contacto = 'exhibicion quierounkia' OR gd.fuente_contacto = 'exhibicion quierounkiatd'"); 
                    $criteria->addCondition("DATE(gi.fecha) = '{$dt_unasemana_antes}'");
                    break;    
                case 'web':
                    break;
                default:
                    $criteria->addCondition(" gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico'"); 
                    $criteria->addCondition("u.cargo_id IN(70,71)");
                    break;
            }
            $criteria->group = "gi.id";
            $criteria->order = "gi.id DESC";
            
        }
//        echo '<pre>';
//        print_r($criteria);
//        echo '</pre>';
    
        if (isset($_GET['GestionDiaria'])) {
//            echo '<pre>';
//            print_r($_GET);
//            echo '</pre>';
//            die();
            $getParams = array();
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $fechaActual = date("Y/m/d");
            $params = explode('-', $_GET['GestionDiaria']['fecha']);
            $fechaPk = 0;
            if (($fechaActual == trim($params[0])) && ($fechaActual == trim($params[1]))) {
                $fechaPk = 1;
            }
            $tipo_search = '';
            // REVISAR VARIABLE $tipo_seg PARA SUMAR UNA CONDICION AL CRITERIA
            //die('tipo search: '.$_GET['tipo_search']);
            if(isset($_GET['tipo_search']) && !empty($_GET['tipo_search'])){
                switch ($_GET['tipo_search']) {
                    case 'web':
                        $tipo_search = 'web';
                        $get_array = 'web';
                        break;
                    case 'exhibicion':
                    case 'exh':    
                        $tipo_search = 'exhibicion';
                        $get_array = 'exhibicion';
                        break;
                    case 'exhqk':    
                        $tipo_search = 'exhqk';
                        $get_array = 'exhqk';
                        break;    
                    case 'prospeccion':
                        $tipo_search = 'pro';
                        $get_array = 'pro';
                        break;
                    default:
                        break;
                }
            }

            $posts = $this->searchSql($cargo_id, $grupo_id, $id_responsable, $fechaPk, $get_array, $tipo_search);
            $this->render('seguimiento', array('users' => $posts['users'], 'getParams' => '', 'title' => $posts['title'], 'model' => $model, 'pages' => $posts['pages'], 'tipo_seg' => $tipo_search));
            exit();
        }

        $tipo = '';

        // Count total records
        $pages = new CPagination(GestionInformacion::model()->count($criteria));
        // Set Page Limit
        $pages->pageSize = 10;
        // Apply page criteria to CDbCriteria
        $pages->applyLimit($criteria);
        $users = GestionInformacion::model()->findAll($criteria);

        $this->render('seguimiento', array('users' => $users, 'model' => $model, 'pages' => $pages,'tipo_seg' => $tipo_seg));
        //$this->render('seguimiento', array('users' => $users, 'model' => $model));
    }

    public function actionSeguimientoBdc($tipo_seg = NULL) {
        //$this->layout = '//layouts/callventas';
        //echo 'TIPO SEG: '.$tipo_seg;
        $grupo_id = (int) Yii::app()->user->getState('grupo_id');
        $cargo = Yii::app()->user->getState('usuario');
        $cargo_id = (int) Yii::app()->user->getState('cargo_id');
        $cargo_adicional = (int) Yii::app()->user->getState('cargo_adicional');
        $area_id = (int) Yii::app()->user->getState('area_id');
        $id_responsable = Yii::app()->user->getId();
        $array_dealers = $this->getDealerGrupoConcUsuario($id_responsable);
        $dealerList = implode(', ', $array_dealers);
        $dealer_id = $this->getDealerId($id_responsable);
        
        date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
        $dt_hoy = date('Y-m-d'); // Fecha actual
        $dt_unasemana_antes = date('Y-m-d', strtotime('-1 day')); // Fecha resta 1 semanas
        $dt_unmes_antes = date('Y-m-d', strtotime('-4 week')); // Fecha resta 1 mes
        $dt_fecha_web = '2016-10-12';
        $model = new GestionNuevaCotizacion;
        $con = Yii::app()->db;
        $criteria = new CDbCriteria;
        $criteria->select = "gi.id , gi.nombres, gi.apellidos, gi.cedula, 
            gi.ruc,gi.pasaporte,gi.email, gi.responsable,gi.tipo_form_web,gi.fecha, gi.bdc, gi.dealer_id, gi.id_cotizacion,
            gi.reasignado,gi.responsable_cesado,gi.id_comentario";
        $criteria->alias = 'gi';
        $criteria->join = 'INNER JOIN gestion_diaria gd ON gi.id = gd.id_informacion';
        $criteria->join .= ' LEFT JOIN gestion_consulta gc ON gi.id = gc.id_informacion';
        $criteria->join .= ' INNER JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion';
        $criteria->join .= ' INNER JOIN usuarios u ON u.id = gi.responsable';
        //die('cargo: '.$cargo_id);
        // GERENTE COMERCIAL
        if ($cargo_id == 69) {
            // SELECT ANTIGUO QUE SE ENLAZABA GON GESTION DIARIA 
            $criteria->condition = " u.grupo_id = {$grupo_id}";
            $criteria->group = 'gi.cedula, gi.ruc, gi.pasaporte';   
            $criteria->order = "gi.id DESC";
        }
        // ASESOR BDC
        if ($cargo_id == 73 ) { 
            // SELECT ANTIGUO QUE SE ENLAZABA GON GESTION DIARIA
            $criteria->condition = " gi.bdc = 1 AND gi.dealer_id IN ({$dealerList}) AND gi.responsable = {$id_responsable}";
            $criteria->group = 'gi.cedula, gi.ruc, gi.pasaporte';   
            $criteria->order = "gi.id DESC";    
            //die($sql);
        }
        // ASESOR VENTAS EXTERNAS NORMAL O CON DOBLE CARGO
        if ($cargo_id == 86 || $cargo_adicional == 86) { 
            $criteria->condition = " gi.bdc = 1 AND gi.dealer_id IN ({$dealerList}) AND gi.responsable = {$id_responsable}";
            $criteria->addCondition("gd.desiste = 0");
            //$criteria->group = 'gi.cedula, gi.ruc, gi.pasaporte';   
            $criteria->order = "gi.id DESC";
            //die($sql);
        }
        if ($cargo_id == 72) { // JEFE DE  BDC
            // SELECT ANTIGUO QUE SE ENLAZABA GON GESTION DIARIA
            $array_dealers = $this->getDealerGrupoConc($grupo_id);
            $dealerList = implode(', ', $array_dealers); 
            $criteria->condition = " gi.bdc = 1 AND gi.dealer_id IN ({$dealerList}) AND u.cargo_id = 73";
            //$criteria->group = 'gi.cedula, gi.ruc, gi.pasaporte';   
            $criteria->order = "gi.id DESC";    
            //die($sql);
        }
        // JEFE DE VENTAS EXTERNAS CON CARGO UNICO O DOBLE CARGO COMO JEFE DE SUCURSAL
        if ($cargo_id == 85 || $cargo_adicional == 85) { 
            $array_dealers = $this->getDealerGrupoConc($grupo_id);
            $dealerList = implode(', ', $array_dealers);
            $criteria->condition = " gi.bdc = 1 AND gi.dealer_id IN ({$dealerList}) AND (u.cargo_id = 86 OR u.cargo_id = 70 OR u.cargo_adicional = 86 OR u.cargo_adicional = 85)";
            $criteria->addCondition("gd.desiste = 0");
//$criteria->group = 'gi.cedula, gi.ruc, gi.pasaporte';   
            $criteria->order = "gi.id DESC";     
            //die($sql);
        }
        // USUARIOS AEKIA Y GERENTE COMERCIAL DE GRUPO
        if ($area_id == 4 || $area_id == 12 || $area_id == 13 || $area_id == 14 || $cargo_id == 69) {
            if($tipo_seg == 'web'){
                $criteria->condition = " gi.bdc = 1 AND (u.cargo_id IN (85,86) OR u.cargo_adicional IN (85,86))";
                $criteria->addCondition("gd.fuente_contacto = 'web'"); 
            }
            if($tipo_seg == 'prospeccion'){
                $criteria->addCondition(" u.cargo_id IN (70,71)");
                $criteria->addCondition("gd.fuente_contacto = 'prospeccion'"); 
            }
            if($tipo_seg == NULL){
                $criteria->condition = " gi.bdc = 1 AND u.cargo_id IN (72,73)";
                $criteria->group = 'gi.cedula, gi.ruc, gi.pasaporte'; 
            }
            $criteria->addCondition("DATE(gi.fecha) BETWEEN '{$dt_unmes_antes}' and '{$dt_hoy}'");
            $criteria->order = "gi.id DESC";
            //die('else: '.$sql);
        }
        //die($sql);
    //    echo '<pre>';
    //    print_r($criteria);
    //    echo '</pre>';
        $users = GestionInformacion::model()->findAll($criteria);
        $tipo = '';

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionNuevaCotizacion'])) {
            //die('enter nueva coti');
//            echo '<pre>';
//            print_r($_POST);
//            echo '</pre>';
//            die();
            $model->attributes = $_POST['GestionNuevaCotizacion'];
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            $model->identificacion = $_POST['GestionNuevaCotizacion']['identificacion'];
            $model->tipo = $_POST['GestionNuevaCotizacion']['tipo'];
            if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Flota') {
                $model->empresa_flotas = $_POST['GestionNuevaCotizacion']['empresa'];
            }
            //die('save identf');
            switch ($_POST['GestionNuevaCotizacion']['fuente']) {
                case 'otro':
                    $model->fuente = $_POST['GestionNuevaCotizacion']['fuente'];
                    break;
                case 'exhibicion':
                    //die('enter exch');
                    $model->lugar_exhibicion = $_POST['GestionNuevaCotizacion']['lugar'];

                    $model->setscenario('consulta');
                    $tipo = 'gestion';
                    $documento = $_POST['GestionNuevaCotizacion']['identificacion'];
                    switch ($documento) {
                        case 'ci':
                            //$model->setscenario('prospeccion');
                            break;
                        case 'ruc':
                            $model->setscenario('ruc');
                            break;
                        case 'pasaporte':
                            //die('apps');
                            $model->setscenario('pasaporte');
                            break;

                        default:
                            break;
                    }
                    if ($_POST['GestionNuevaCotizacion']['cedula'] != '') {
                        $ident = 'ci';
                        //die('enter empty');
                        $model->cedula = $_POST['GestionNuevaCotizacion']['cedula'];
                        $criteria = new CDbCriteria(array(
                            'condition' => "cedula='{$_POST['GestionNuevaCotizacion']['cedula']}'"
                        ));
                        $ced = GestionInformacion::model()->find($criteria);
                        if ((count($ced)) > 0) {
                            //die('enter ced');
                            $this->redirect(array('gestionVehiculo/create', 'id' => $ced->id));
                        } else {
                            if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Usado') {
                                $model->setscenario('prospeccion');
                                $tipo = 'prospeccion';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/usados', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'usado', 'iden' => $ident));
                                }
                            }
                            if ($model->save()) {
                                $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'fuente' => $_POST['GestionNuevaCotizacion']['fuente'], 'iden' => $ident));
                            }
                        }
                    }

                    if ($_POST['GestionNuevaCotizacion']['ruc'] != '') {
                        $ident = 'ruc';
                        //die('enter empty');
                        $model->ruc = $_POST['GestionNuevaCotizacion']['ruc'];
                        $criteria = new CDbCriteria(array(
                            'condition' => "ruc='{$_POST['GestionNuevaCotizacion']['ruc']}'"
                        ));
                        $ced = GestionInformacion::model()->find($criteria);
                        if ((count($ced)) > 0) {
                            //die('enter ced');
                            $this->redirect(array('gestionVehiculo/create', 'id' => $ced->id));
                        } else {
                            if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Usado') {
                                $model->setscenario('prospeccion');
                                $tipo = 'prospeccion';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/usados', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'usado', 'iden' => $ident));
                                }
                            }
                            if ($model->save()) {
                                $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'fuente' => $_POST['GestionNuevaCotizacion']['fuente'], 'iden' => $ident));
                            }
                        }
                    }

                    if ($_POST['GestionNuevaCotizacion']['pasaporte'] != '') {
                        //die('enter pass');
                        $ident = 'pasaporte';
                        //die('enter pasaporte');
                        $model->pasaporte = $_POST['GestionNuevaCotizacion']['pasaporte'];
                        $criteria = new CDbCriteria(array(
                            'condition' => "pasaporte='{$_POST['GestionNuevaCotizacion']['pasaporte']}'"
                        ));
                        $ced = GestionInformacion::model()->find($criteria);
                        if ((count($ced)) > 0) {
                            //die('enter ced');
                            $this->redirect(array('gestionVehiculo/create', 'id' => $ced->id));
                        } else {
                            if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Usado') {
                                $model->setscenario('prospeccion');
                                $tipo = 'prospeccion';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/usados', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'usado', 'iden' => $ident));
                                }
                            }
                            if ($model->save()) {
                                //die('enter save');
                                $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'fuente' => $_POST['GestionNuevaCotizacion']['fuente'], 'iden' => $ident));
                            }
                        }
                    }
                    break;
                case 'exonerados':
                    //die('ente exo');
                    $model->motivo_exonerados = $_POST['GestionNuevaCotizacion']['motivo_exonerados'];

                    $model->setscenario('consulta');
                    $tipo = 'gestion';
                    $documento = $_POST['GestionNuevaCotizacion']['identificacion'];
                    switch ($documento) {
                        case 'ci':
                            //$model->setscenario('prospeccion');
                            break;
                        case 'ruc':
                            $model->setscenario('ruc');
                            break;
                        case 'pasaporte':
                            //die('apps');
                            $model->setscenario('pasaporte');
                            break;

                        default:
                            break;
                    }
                    if ($_POST['GestionNuevaCotizacion']['cedula'] != '') {
                        $ident = 'ci';
                        //die('enter empty');
                        $model->cedula = $_POST['GestionNuevaCotizacion']['cedula'];
                        $criteria = new CDbCriteria(array(
                            'condition' => "cedula='{$_POST['GestionNuevaCotizacion']['cedula']}'"
                        ));
                        $ced = GestionInformacion::model()->find($criteria);
                        if ((count($ced)) > 0) {
                            //die('enter ced');
                            $this->redirect(array('gestionVehiculo/create', 'id' => $ced->id));
                        } else {
                            if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Usado') {
                                $model->setscenario('prospeccion');
                                $tipo = 'prospeccion';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/usados', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'usado', 'iden' => $ident));
                                }
                            }
                            if ($model->save()) {
                                $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'fuente' => $_POST['GestionNuevaCotizacion']['fuente'], 'iden' => $ident));
                            }
                        }
                    }

                    if ($_POST['GestionNuevaCotizacion']['ruc'] != '') {
                        $ident = 'ruc';
                        //die('enter empty');
                        $model->ruc = $_POST['GestionNuevaCotizacion']['ruc'];
                        $criteria = new CDbCriteria(array(
                            'condition' => "ruc='{$_POST['GestionNuevaCotizacion']['ruc']}'"
                        ));
                        $ced = GestionInformacion::model()->find($criteria);
                        if ((count($ced)) > 0) {
                            //die('enter ced');
                            $this->redirect(array('gestionVehiculo/create', 'id' => $ced->id));
                        } else {
                            if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Usado') {
                                $model->setscenario('prospeccion');
                                $tipo = 'prospeccion';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/usados', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'usado', 'iden' => $ident));
                                }
                            }
                            if ($model->save()) {
                                $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'fuente' => $_POST['GestionNuevaCotizacion']['fuente'], 'iden' => $ident));
                            }
                        }
                    }

                    if ($_POST['GestionNuevaCotizacion']['pasaporte'] != '') {
                        //die('enter pass');
                        $ident = 'pasaporte';
                        //die('enter pasaporte');
                        $model->pasaporte = $_POST['GestionNuevaCotizacion']['pasaporte'];
                        $criteria = new CDbCriteria(array(
                            'condition' => "pasaporte='{$_POST['GestionNuevaCotizacion']['pasaporte']}'"
                        ));
                        $ced = GestionInformacion::model()->find($criteria);
                        if ((count($ced)) > 0) {
                            //die('enter ced');
                            $this->redirect(array('gestionVehiculo/create', 'id' => $ced->id));
                        } else {
                            if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Usado') {
                                $model->setscenario('prospeccion');
                                $tipo = 'prospeccion';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/usados', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'usado', 'iden' => $ident));
                                }
                            }
                            if ($model->save()) {
                                //die('enter save');
                                $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'fuente' => $_POST['GestionNuevaCotizacion']['fuente'], 'iden' => $ident));
                            }
                        }
                    }
                    break;
                case 'prospeccion':
                    //die('ente pros');
                    $model->setscenario('prospeccion');
                    $tipo = 'prospeccion';
                    if ($model->save()) {
                        //die('enter save');
                        $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id,));
                    }
                    break;
                case 'trafico':
                    //die('ente traf');
                    $model->setscenario('trafico');
                    $tipo = 'trafico';
                    if ($model->save()) {
                        if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Usado') {
                            $this->redirect(array('gestionInformacion/usados', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'usado'));
                        }
                        $this->redirect(array('gestionInformacion/create2', 'tipo' => $tipo, 'id' => $model->id,));
                    }


                    break;
                case 'showroom':
                case 'web':
                    //die('enter showroom');

                    $model->setscenario('consulta');
                    $tipo = 'gestion';
                    $documento = $_POST['GestionNuevaCotizacion']['identificacion'];
                    switch ($documento) {
                        case 'ci':
                            //$model->setscenario('prospeccion');
                            break;
                        case 'ruc':
                            $model->setscenario('ruc');
                            break;
                        case 'pasaporte':
                            //die('apps');
                            $model->setscenario('pasaporte');
                            break;

                        default:
                            break;
                    }
                    if ($_POST['GestionNuevaCotizacion']['cedula'] != '') {
                        $ident = 'ci';
                        //die('enter empty');
                        $model->cedula = $_POST['GestionNuevaCotizacion']['cedula'];
                        $criteria = new CDbCriteria(array(
                            'condition' => "cedula='{$_POST['GestionNuevaCotizacion']['cedula']}'"
                        ));
                        $ced = GestionInformacion::model()->find($criteria);
                        if ((count($ced)) > 0) {
                            //die('enter ced');
                            $this->redirect(array('gestionVehiculo/create', 'id' => $ced->id));
                        } else {
                            if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Usado') {
                                $model->setscenario('prospeccion');
                                $tipo = 'prospeccion';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'tipo_fuente' => 'usado', 'iden' => $ident));
                                }
                            }
                            if ($model->save()) {
                                $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'fuente' => $_POST['GestionNuevaCotizacion']['fuente'], 'iden' => $ident));
                            }
                        }
                    }

                    if ($_POST['GestionNuevaCotizacion']['ruc'] != '') {
                        $ident = 'ruc';
                        //die('enter empty');
                        $model->ruc = $_POST['GestionNuevaCotizacion']['ruc'];
                        $criteria = new CDbCriteria(array(
                            'condition' => "ruc='{$_POST['GestionNuevaCotizacion']['ruc']}'"
                        ));
                        $ced = GestionInformacion::model()->find($criteria);
                        if ((count($ced)) > 0) {
                            //die('enter ced');
                            $this->redirect(array('gestionVehiculo/create', 'id' => $ced->id));
                        } else {
                            if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Usado') {
                                $model->setscenario('prospeccion');
                                $tipo = 'prospeccion';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'tipo_fuente' => 'usado', 'iden' => $ident));
                                }
                            }
                            if ($model->save()) {
                                $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'fuente' => $_POST['GestionNuevaCotizacion']['fuente'], 'iden' => $ident));
                            }
                        }
                    }

                    if ($_POST['GestionNuevaCotizacion']['pasaporte'] != '') {
                        //die('enter pass');
                        $ident = 'pasaporte';
                        //die('enter pasaporte');
                        $model->pasaporte = $_POST['GestionNuevaCotizacion']['pasaporte'];
                        $criteria = new CDbCriteria(array(
                            'condition' => "pasaporte='{$_POST['GestionNuevaCotizacion']['pasaporte']}'"
                        ));
                        $ced = GestionInformacion::model()->find($criteria);
                        if ((count($ced)) > 0) {
                            //die('enter ced');
                            $this->redirect(array('gestionVehiculo/create', 'id' => $ced->id));
                        } else {
                            if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Usado') {
                                $model->setscenario('prospeccion');
                                $tipo = 'prospeccion';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'tipo_fuente' => 'usado', 'iden' => $ident));
                                }
                            }
                            if ($model->save()) {
                                //die('enter save');
                                $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'fuente' => $_POST['GestionNuevaCotizacion']['fuente'], 'iden' => $ident));
                            }
                        }
                    }
                    break;

                default:
                    break;
            }




            if ($model->save()) {
                $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo));
            }
        }

        if (isset($_GET['GestionDiaria'])) {
//            echo '<pre>';
//            print_r($_GET);
//            echo '</pre>';
//            die();
            $getParams = array();
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $fechaActual = date("Y/m/d");
            $params = explode('-', $_GET['GestionDiaria']['fecha']);
            $fechaPk = 0;
            if (($fechaActual == trim($params[0])) && ($fechaActual == trim($params[1]))) {
                $fechaPk = 1;
            }
            //die('55d: '.$_GET['GestionDiaria']['tipo']);
            $tipo_search = '';
            // REVISAR VARIABLE $tipo_seg PARA SUMAR UNA CONDICION AL CRITERIA
            if(isset($_GET['tipo_search']) && !empty($_GET['tipo_search'])){
                switch ($_GET['tipo_search']) {
                    case 'web':
                        $tipo_search = 'web';
                        $get_array = 'web';
                        break;
                    case 'bdc':
                        $tipo_search = 'bdc';
                        $get_array = 'bdc';
                        break;
                    case 'exhibicion':
                        $tipo_search = 'exh';
                        $get_array = 'exh';
                        break;
                    case 'prospeccion':
                        $tipo_search = 'pro';
                        $get_array = 'pro';
                        break;
                    default:
                        break;
                }
            }


            $posts = $this->searchSql($cargo_id, $grupo_id, $id_responsable, $fechaPk, $get_array,$tipo_search);
            $this->render('seguimientobdc', array('users' => $posts['users'], 'getParams' => '', 'title' => $posts['title'], 'model' => $model, 'pages' => $posts['pages'], 'tipo_seg' => $tipo_search));
            exit();
        }
        // Count total records
        $pages = new CPagination(GestionInformacion::model()->count($criteria));
        // Set Page Limit
        $pages->pageSize = 10;
        // Apply page criteria to CDbCriteria
        $pages->applyLimit($criteria);
        $users = GestionInformacion::model()->findAll($criteria);

        $this->render('seguimientobdc', array('users' => $users, 'model' => $model, 'pages' => $pages,'tipo_seg' => $tipo_seg));
    }

    public function actionSeguimientoexonerados() {
        //$this->layout = '//layouts/callventas';
        $cargo = Yii::app()->user->getState('usuario');
        $cargo_id = (int) Yii::app()->user->getState('cargo_id');
        $grupo_id = (int) Yii::app()->user->getState('grupo_id');
        $id_responsable = Yii::app()->user->getId();
        $array_dealers = $this->getDealerGrupoConcUsuario($id_responsable);
        $dealerList = implode(', ', $array_dealers);

        $area_id = (int) Yii::app()->user->getState('area_id');
        if ($cargo_id != 46)
            $dealer_id = $this->getDealerId($id_responsable);
        $model = new GestionNuevaCotizacion;
        $con = Yii::app()->db;

        $array_dealers = $this->getDealerGrupo($id_responsable);

        $dealerList = implode(', ', $array_dealers);
        //die('array: '.$dealerList);

        if ($cargo_id == 71) { // ASESOR DE VENTAS         
            // SELECT ANTIGUO QUE SE ENLAZABA GON GESTION DIARIA
            $dealer_id = $this->getDealerId($id_responsable);
            //die($dealer_id);
            $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, gi.celular, gi.direccion, 
            gi.ruc,gi.pasaporte,gi.email, gi.responsable as id_resp,gi.tipo_form_web,gi.fecha, gi.bdc,gi.tipo_ex, 
            gd.*, gd.fuente_contacto as fuente, gd.fuente_contacto_historial, gd.desiste 
            FROM gestion_diaria gd 
                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
                LEFT JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion 
                WHERE gi.tipo_form_web = 'exonerados' AND gi.dealer_id IN ($dealerList) AND gi.responsable = $id_responsable 
                    GROUP BY gi.cedula, gi.ruc, gi.pasaporte
                ORDER BY gd.id DESC";
            //die($sql);
        }

        if ($cargo_id == 75) { // ASESOR DE EXONERADOS           
            // SELECT ANTIGUO QUE SE ENLAZABA GON GESTION DIARIA
            $dealer_id = $this->getDealerId($id_responsable);
            $array_dealers = $this->getDealerGrupo($id_responsable);
            $dealerList = implode(', ', $array_dealers);
            //die('count: '.count($array_dealers));
            //die($dealer_id);
            $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, gi.celular, gi.direccion, 
            gi.ruc,gi.pasaporte,gi.email, gi.responsable as id_resp,gi.tipo_form_web,gi.fecha, gi.bdc,gi.tipo_ex, 
            gd.*, gd.fuente_contacto as fuente, gd.fuente_contacto_historial, gd.desiste 
            FROM gestion_diaria gd 
                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
                LEFT JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion ";
            if (count($array_dealers) > 0) {
                $sql .= "WHERE gi.tipo_form_web = 'exonerados' AND gi.responsable = $id_responsable GROUP BY gi.cedula, gi.ruc, gi.pasaporte";
            } else {
                $array_dealers = $this->getDealerGrupoConc($grupo_id);
                $dealerList = implode(', ', $array_dealers);
                $sql .= " WHERE gi.tipo_form_web = 'exonerados' AND gi.dealer_id IN ($dealerList) GROUP BY gi.cedula, gi.ruc, gi.pasaporte
                ORDER BY gd.id DESC";
            }

            //die($sql);
        }

        if ($cargo_id == 70) { // JEFE DE SUCURSAL
            $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, gi.celular, gi.direccion, 
            gi.ruc,gi.pasaporte,gi.email, gi.responsable as id_resp,gi.tipo_form_web,gi.fecha, gi.bdc,gi.tipo_ex, 
            gd.*, gd.fuente_contacto as fuente, gd.fuente_contacto_historial, gd.desiste 
            FROM gestion_diaria gd 
                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
                LEFT JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion 
                WHERE gi.tipo_form_web = 'exonerados' AND gi.dealer_id = {$dealer_id} GROUP BY gi.cedula, gi.ruc, gi.pasaporte
                ORDER BY gd.id DESC";
        }
        if ($cargo_id == 72) { // JEFE DE BDC
            $array_dealers = $this->getDealerGrupoConc($grupo_id);
            $dealerList = implode(', ', $array_dealers);
            $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, gi.celular, gi.direccion, 
            gi.ruc,gi.pasaporte,gi.email, gi.responsable as id_resp,gi.tipo_form_web,gi.fecha, gi.bdc,gi.tipo_ex, 
            gd.*, gd.fuente_contacto as fuente, gd.fuente_contacto_historial, gd.desiste 
            FROM gestion_diaria gd 
                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
                LEFT JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion 
                INNER JOIN usuarios u ON u.id = gi.responsable 
                WHERE gi.tipo_form_web = 'exonerados' AND gi.dealer_id IN ({$dealerList}) AND u.cargo_id = 75 GROUP BY gi.cedula, gi.ruc, gi.pasaporte
                ORDER BY gd.id DESC";
        }
        if ($cargo_id == 46) { // SUPER ADMINISTRADOR
            $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, gi.celular, gi.direccion, 
            gi.ruc,gi.pasaporte,gi.email, gi.responsable as id_resp,gi.tipo_form_web,gi.fecha, gi.bdc,gi.tipo_ex, 
            gd.*, gd.fuente_contacto as fuente, gd.fuente_contacto_historial, gd.desiste 
            FROM gestion_diaria gd 
                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
                LEFT JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion 
                WHERE gi.tipo_form_web = 'exonerados' GROUP BY gi.cedula, gi.ruc, gi.pasaporte
                ORDER BY gd.id DESC";
        }

        if ($cargo_id == 69) { //GERENTE COMERCIAL
            // SELECT ANTIGUO QUE SE ENLAZABA GON GESTION DIARIA
            $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, gi.celular, gi.direccion, 
            gi.ruc,gi.pasaporte,gi.email, gi.responsable as id_resp,gi.tipo_form_web,gi.fecha, gi.bdc, gi.tipo_ex,
            gd.*, gd.fuente_contacto as fuente, gd.fuente_contacto_historial, gd.desiste 
            FROM gestion_diaria gd 
                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
                LEFT JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion 
                INNER JOIN usuarios u ON u.id = gi.responsable 
                WHERE gi.tipo_form_web = 'exonerados' AND u.grupo_id = {$grupo_id} GROUP BY gi.cedula, gi.ruc, gi.pasaporte
                ORDER BY gd.id DESC";
        } if ($area_id == 4 || $area_id == 12 || $area_id == 13 || $area_id == 14) {
            $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, gi.celular, gi.direccion, 
            gi.ruc,gi.pasaporte,gi.email, gi.responsable as id_resp,gi.tipo_form_web,gi.fecha, gi.bdc, gi.tipo_ex,
            gd.*, gd.fuente_contacto as fuente, gd.fuente_contacto_historial, gd.desiste 
            FROM gestion_diaria gd 
                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
                LEFT JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion 
                INNER JOIN usuarios u ON u.id = gi.responsable 
                WHERE gi.tipo_form_web = 'exonerados' AND u.cargo_id IN (75) GROUP BY gi.cedula, gi.ruc, gi.pasaporte
                ORDER BY gd.id DESC";
        }
        //die($sql);
        // SELECT DE USUARIOS MAS VERHICULOS SUBIDOS       
        //$sql = "SELECT gi.*, gv.modelo, gv.version FROM gestion_informacion gi 
        //        INNER JOIN gestion_vehiculo gv ON gi.id = gv.id_informacion 
        //        GROUP BY gi.id";
        //$sql = "SELECT * FROM gestion_informacion GROUP BY id";
        $request = $con->createCommand($sql);
        $users = $request->queryAll();
        $tipo = '';

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionNuevaCotizacion'])) {
            //die('enter nueva coti');
//            echo '<pre>';
//            print_r($_POST);
//            echo '</pre>';
//            die();
            $model->attributes = $_POST['GestionNuevaCotizacion'];
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            $model->identificacion = $_POST['GestionNuevaCotizacion']['identificacion'];
            $model->tipo = $_POST['GestionNuevaCotizacion']['tipo'];
            if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Flota') {
                $model->empresa_flotas = $_POST['GestionNuevaCotizacion']['empresa'];
            }
            //die('save identf');
            switch ($_POST['GestionNuevaCotizacion']['fuente']) {
                case 'otro':
                    $model->fuente = $_POST['GestionNuevaCotizacion']['fuente'];
                    break;
                case 'exhibicion':
                    //die('enter exch');
                    $model->lugar_exhibicion = $_POST['GestionNuevaCotizacion']['lugar'];

                    $model->setscenario('consulta');
                    $tipo = 'gestion';
                    $documento = $_POST['GestionNuevaCotizacion']['identificacion'];
                    switch ($documento) {
                        case 'ci':
                            //$model->setscenario('prospeccion');
                            break;
                        case 'ruc':
                            $model->setscenario('ruc');
                            break;
                        case 'pasaporte':
                            //die('apps');
                            $model->setscenario('pasaporte');
                            break;

                        default:
                            break;
                    }
                    if ($_POST['GestionNuevaCotizacion']['cedula'] != '') {
                        $ident = 'ci';
                        //die('enter empty');
                        $model->cedula = $_POST['GestionNuevaCotizacion']['cedula'];
                        $criteria = new CDbCriteria(array(
                            'condition' => "cedula='{$_POST['GestionNuevaCotizacion']['cedula']}'"
                        ));
                        $ced = GestionInformacion::model()->find($criteria);
                        if ((count($ced)) > 0) {
                            //die('enter ced');
                            $this->redirect(array('gestionVehiculo/create', 'id' => $ced->id));
                        } else {
                            if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Usado') {
                                $model->setscenario('prospeccion');
                                $tipo = 'prospeccion';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/usados', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'usado', 'iden' => $ident));
                                }
                            }
                            if ($model->save()) {
                                $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'fuente' => $_POST['GestionNuevaCotizacion']['fuente'], 'iden' => $ident));
                            }
                        }
                    }

                    if ($_POST['GestionNuevaCotizacion']['ruc'] != '') {
                        $ident = 'ruc';
                        //die('enter empty');
                        $model->ruc = $_POST['GestionNuevaCotizacion']['ruc'];
                        $criteria = new CDbCriteria(array(
                            'condition' => "ruc='{$_POST['GestionNuevaCotizacion']['ruc']}'"
                        ));
                        $ced = GestionInformacion::model()->find($criteria);
                        if ((count($ced)) > 0) {
                            //die('enter ced');
                            $this->redirect(array('gestionVehiculo/create', 'id' => $ced->id));
                        } else {
                            if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Usado') {
                                $model->setscenario('prospeccion');
                                $tipo = 'prospeccion';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/usados', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'usado', 'iden' => $ident));
                                }
                            }
                            if ($model->save()) {
                                $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'fuente' => $_POST['GestionNuevaCotizacion']['fuente'], 'iden' => $ident));
                            }
                        }
                    }

                    if ($_POST['GestionNuevaCotizacion']['pasaporte'] != '') {
                        //die('enter pass');
                        $ident = 'pasaporte';
                        //die('enter pasaporte');
                        $model->pasaporte = $_POST['GestionNuevaCotizacion']['pasaporte'];
                        $criteria = new CDbCriteria(array(
                            'condition' => "pasaporte='{$_POST['GestionNuevaCotizacion']['pasaporte']}'"
                        ));
                        $ced = GestionInformacion::model()->find($criteria);
                        if ((count($ced)) > 0) {
                            //die('enter ced');
                            $this->redirect(array('gestionVehiculo/create', 'id' => $ced->id));
                        } else {
                            if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Usado') {
                                $model->setscenario('prospeccion');
                                $tipo = 'prospeccion';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/usados', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'usado', 'iden' => $ident));
                                }
                            }
                            if ($model->save()) {
                                //die('enter save');
                                $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'fuente' => $_POST['GestionNuevaCotizacion']['fuente'], 'iden' => $ident));
                            }
                        }
                    }
                    break;
                case 'exonerados':
                    //die('ente exo');
                    $model->motivo_exonerados = $_POST['GestionNuevaCotizacion']['motivo_exonerados'];

                    $model->setscenario('consulta');
                    $tipo = 'gestion';
                    $documento = $_POST['GestionNuevaCotizacion']['identificacion'];
                    switch ($documento) {
                        case 'ci':
                            //$model->setscenario('prospeccion');
                            break;
                        case 'ruc':
                            $model->setscenario('ruc');
                            break;
                        case 'pasaporte':
                            //die('apps');
                            $model->setscenario('pasaporte');
                            break;

                        default:
                            break;
                    }
                    if ($_POST['GestionNuevaCotizacion']['cedula'] != '') {
                        $ident = 'ci';
                        //die('enter empty');
                        $model->cedula = $_POST['GestionNuevaCotizacion']['cedula'];
                        $criteria = new CDbCriteria(array(
                            'condition' => "cedula='{$_POST['GestionNuevaCotizacion']['cedula']}'"
                        ));
                        $ced = GestionInformacion::model()->find($criteria);
                        if ((count($ced)) > 0) {
                            //die('enter ced');
                            $this->redirect(array('gestionVehiculo/create', 'id' => $ced->id));
                        } else {
                            if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Usado') {
                                $model->setscenario('prospeccion');
                                $tipo = 'prospeccion';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/usados', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'usado', 'iden' => $ident));
                                }
                            }
                            if ($model->save()) {
                                $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'fuente' => $_POST['GestionNuevaCotizacion']['fuente'], 'iden' => $ident));
                            }
                        }
                    }

                    if ($_POST['GestionNuevaCotizacion']['ruc'] != '') {
                        $ident = 'ruc';
                        //die('enter empty');
                        $model->ruc = $_POST['GestionNuevaCotizacion']['ruc'];
                        $criteria = new CDbCriteria(array(
                            'condition' => "ruc='{$_POST['GestionNuevaCotizacion']['ruc']}'"
                        ));
                        $ced = GestionInformacion::model()->find($criteria);
                        if ((count($ced)) > 0) {
                            //die('enter ced');
                            $this->redirect(array('gestionVehiculo/create', 'id' => $ced->id));
                        } else {
                            if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Usado') {
                                $model->setscenario('prospeccion');
                                $tipo = 'prospeccion';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/usados', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'usado', 'iden' => $ident));
                                }
                            }
                            if ($model->save()) {
                                $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'fuente' => $_POST['GestionNuevaCotizacion']['fuente'], 'iden' => $ident));
                            }
                        }
                    }

                    if ($_POST['GestionNuevaCotizacion']['pasaporte'] != '') {
                        //die('enter pass');
                        $ident = 'pasaporte';
                        //die('enter pasaporte');
                        $model->pasaporte = $_POST['GestionNuevaCotizacion']['pasaporte'];
                        $criteria = new CDbCriteria(array(
                            'condition' => "pasaporte='{$_POST['GestionNuevaCotizacion']['pasaporte']}'"
                        ));
                        $ced = GestionInformacion::model()->find($criteria);
                        if ((count($ced)) > 0) {
                            //die('enter ced');
                            $this->redirect(array('gestionVehiculo/create', 'id' => $ced->id));
                        } else {
                            if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Usado') {
                                $model->setscenario('prospeccion');
                                $tipo = 'prospeccion';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/usados', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'usado', 'iden' => $ident));
                                }
                            }
                            if ($model->save()) {
                                //die('enter save');
                                $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'fuente' => $_POST['GestionNuevaCotizacion']['fuente'], 'iden' => $ident));
                            }
                        }
                    }
                    break;
                case 'prospeccion':
                    //die('ente pros');
                    $model->setscenario('prospeccion');
                    $tipo = 'prospeccion';
                    if ($model->save()) {
                        //die('enter save');
                        $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id,));
                    }
                    break;
                case 'trafico':
                    //die('ente traf');
                    $model->setscenario('trafico');
                    $tipo = 'trafico';
                    if ($model->save()) {
                        if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Usado') {
                            $this->redirect(array('gestionInformacion/usados', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'usado'));
                        }
                        $this->redirect(array('gestionInformacion/create2', 'tipo' => $tipo, 'id' => $model->id,));
                    }


                    break;
                case 'showroom':
                case 'web':
                    //die('enter showroom');

                    $model->setscenario('consulta');
                    $tipo = 'gestion';
                    $documento = $_POST['GestionNuevaCotizacion']['identificacion'];
                    switch ($documento) {
                        case 'ci':
                            //$model->setscenario('prospeccion');
                            break;
                        case 'ruc':
                            $model->setscenario('ruc');
                            break;
                        case 'pasaporte':
                            //die('apps');
                            $model->setscenario('pasaporte');
                            break;

                        default:
                            break;
                    }
                    if ($_POST['GestionNuevaCotizacion']['cedula'] != '') {
                        $ident = 'ci';
                        //die('enter empty');
                        $model->cedula = $_POST['GestionNuevaCotizacion']['cedula'];
                        $criteria = new CDbCriteria(array(
                            'condition' => "cedula='{$_POST['GestionNuevaCotizacion']['cedula']}'"
                        ));
                        $ced = GestionInformacion::model()->find($criteria);
                        if ((count($ced)) > 0) {
                            //die('enter ced');
                            $this->redirect(array('gestionVehiculo/create', 'id' => $ced->id));
                        } else {
                            if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Usado') {
                                $model->setscenario('prospeccion');
                                $tipo = 'prospeccion';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'tipo_fuente' => 'usado', 'iden' => $ident));
                                }
                            }
                            if ($model->save()) {
                                $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'fuente' => $_POST['GestionNuevaCotizacion']['fuente'], 'iden' => $ident));
                            }
                        }
                    }

                    if ($_POST['GestionNuevaCotizacion']['ruc'] != '') {
                        $ident = 'ruc';
                        //die('enter empty');
                        $model->ruc = $_POST['GestionNuevaCotizacion']['ruc'];
                        $criteria = new CDbCriteria(array(
                            'condition' => "ruc='{$_POST['GestionNuevaCotizacion']['ruc']}'"
                        ));
                        $ced = GestionInformacion::model()->find($criteria);
                        if ((count($ced)) > 0) {
                            //die('enter ced');
                            $this->redirect(array('gestionVehiculo/create', 'id' => $ced->id));
                        } else {
                            if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Usado') {
                                $model->setscenario('prospeccion');
                                $tipo = 'prospeccion';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'tipo_fuente' => 'usado', 'iden' => $ident));
                                }
                            }
                            if ($model->save()) {
                                $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'fuente' => $_POST['GestionNuevaCotizacion']['fuente'], 'iden' => $ident));
                            }
                        }
                    }

                    if ($_POST['GestionNuevaCotizacion']['pasaporte'] != '') {
                        //die('enter pass');
                        $ident = 'pasaporte';
                        //die('enter pasaporte');
                        $model->pasaporte = $_POST['GestionNuevaCotizacion']['pasaporte'];
                        $criteria = new CDbCriteria(array(
                            'condition' => "pasaporte='{$_POST['GestionNuevaCotizacion']['pasaporte']}'"
                        ));
                        $ced = GestionInformacion::model()->find($criteria);
                        if ((count($ced)) > 0) {
                            //die('enter ced');
                            $this->redirect(array('gestionVehiculo/create', 'id' => $ced->id));
                        } else {
                            if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Usado') {
                                $model->setscenario('prospeccion');
                                $tipo = 'prospeccion';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'tipo_fuente' => 'usado', 'iden' => $ident));
                                }
                            }
                            if ($model->save()) {
                                //die('enter save');
                                $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'fuente' => $_POST['GestionNuevaCotizacion']['fuente'], 'iden' => $ident));
                            }
                        }
                    }
                    break;

                default:
                    break;
            }




            if ($model->save()) {
                $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo));
            }
        }
        if (isset($_GET['GestionDiaria'])) {
//            echo '<pre>';
//            print_r($_GET);
//            echo '</pre>';
//            die();

            $getParams = array();
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $fechaActual = date("Y/m/d");
            $params = explode('-', $_GET['GestionDiaria']['fecha']);
            $fechaPk = 0;
            if (($fechaActual == trim($params[0])) && ($fechaActual == trim($params[1]))) {
                $fechaPk = 1;
            }

            $posts = $this->searchSql($cargo_id, $grupo_id, $id_responsable, $fechaPk, 'exo');
            $this->render('seguimientoexonerado', array('users' => $posts['users'], 'getParams' => '', 'title' => $posts['title'], 'model' => $model));
            exit();
        }

        $this->render('seguimientoexonerado', array('users' => $users, 'model' => $model));
    }

    public function actionExonerados($tipo = NULL, $id = NULL, $fuente = NULL, $tipo_fuente = NULL) {
        $model = new GestionInformacion;
        $cargo_id = (int) Yii::app()->user->getState('cargo_id');
        $id_responsable = Yii::app()->user->getId();
        $dealer_id = $this->getConcesionarioDealerId($id_responsable);
        if (isset($_POST['GestionInformacion'])) {
//            echo '<pre>';
//            print_r($_POST);
//            echo '</pre>';
//            die();
            $historial = new GestionHistorial;
            $model->attributes = $_POST['GestionInformacion'];
            if (isset($_POST['GestionInformacion']['tipo_form_web']))
                $model->tipo_form_web = $_POST['GestionInformacion']['tipo_form_web'];

            if (isset($_POST['GestionInformacion']['presupuesto']))
                $model->presupuesto = $_POST['GestionInformacion']['presupuesto'];

            if (isset($_POST['GestionInformacion']['tipo_ex']))
                $model->tipo_ex = $_POST['GestionInformacion']['tipo_ex'];

            if (isset($_POST['GestionInformacion']['iden'])) {
                if (($_POST['GestionInformacion']['iden'] == 'ruc') && !isset($_POST['tipo_fuente'])) {
                    $model->setscenario('ruc');
                }
                if (($_POST['GestionInformacion']['iden'] == 'pasaporte') && !isset($_POST['tipo_fuente'])) {
                    $model->setscenario('pasaporte');
                    $model->pasaporte = $_POST['GestionInformacion']['pasaporte'];
                }
                if (($_POST['GestionInformacion']['iden'] == 'ruc') && isset($_POST['tipo_fuente'])) {
                    $model->setscenario('rucusado');
                    $model->provincia_domicilio = $_POST['GestionInformacion']['provincia_domicilio'];
                    $model->ciudad_domicilio = $_POST['GestionInformacion']['ciudad_domicilio'];
                }
                if (($_POST['GestionInformacion']['iden'] == 'pasaporte') && isset($_POST['tipo_fuente'])) {
                    $model->setscenario('pasaporteusado');
                    $model->pasaporte = $_POST['GestionInformacion']['pasaporte'];
                    $model->provincia_domicilio = $_POST['GestionInformacion']['provincia_domicilio'];
                    $model->ciudad_domicilio = $_POST['GestionInformacion']['ciudad_domicilio'];
                }
            }


            //die('random: '.$random_key);
            // SACAMOS EL ARRAY DE IDS DE EXONERADOS DESDE LA BASE

            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            if ($cargo_id == 75) {
                $model->responsable = Yii::app()->user->getId();
                $email_responsable = $this->getEmailAsesor($id_responsable);
            }
            if ($cargo_id == 71 || $cargo_id == 70) { // asesor de ventas y jefe de sucursal
                $random_key = $this->getRandomKey(75, $dealer_id); //exonerados
                $model->responsable = $random_key;
                $model->responsable_origen = Yii::app()->user->getId();
                $email_responsable = $this->getEmailAsesor($random_key);
            }

            $model->dealer_id = $this->getDealerId(Yii::app()->user->getId());
            $model->id_cotizacion = $_POST['GestionInformacion']['id_cotizacion'];
            $model->nombres = ucfirst($_POST['GestionInformacion']['nombres']);
            $model->apellidos = ucfirst($_POST['GestionInformacion']['apellidos']);
            $model->modelo = $_POST['GestionVehiculo']['modelo'];
            $model->marca_usado = $_POST['GestionVehiculo']['version'];
            if ($_POST['tipo'] == 'gestion'):
                $model->provincia_conc = $_POST['GestionInformacion']['provincia_conc'];
                $model->ciudad_conc = $_POST['GestionInformacion']['ciudad_conc'];
                $model->concesionario = $_POST['GestionInformacion']['concesionario'];
                $model->provincia_domicilio = $_POST['GestionInformacion']['provincia_domicilio'];
                $model->ciudad_domicilio = $_POST['GestionInformacion']['ciudad_domicilio'];
            endif;

            if (isset($_POST['GestionInformacion']['fecha_cita']))
                $model->fecha_cita = $_POST['GestionInformacion']['fecha_cita'];

            if (($_POST['tipo'] == 'gestion') && ($_POST['GestionInformacion']['iden'] == 'ci')) {
                $model->setscenario('gestion');
            } else if (($_POST['tipo'] == 'prospeccion') && !isset($_POST['tipo_fuente'])) {
                $model->setscenario('prospeccion');
            }


            if ($model->save()) {
                //die('enter save');
                if ($_POST['tipo'] == 'gestion') {
                    // die('enter continuar');
                    // enviar a la pantalla de seguimiento con el nuevo caso ingresado
                    // ingresar datos en gestion diaria con status 1: prospección
                    $gestion = new GestionDiaria;
                    $gestion->paso = $_POST['GestionInformacion']['paso'];
                    $prospeccion = new GestionProspeccionRp;
                    $prospeccion->id_informacion = $model->id;
                    $observaciones = $_POST['GestionProspeccionPr']['pregunta'];
                    switch ($observaciones) {
                        case 1:// no estoy interesado
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 0;
                            $gestion->proximo_seguimiento = '';
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        case 2:// falta de dinero
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 0;
                            $gestion->proximo_seguimiento = '';
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        case 3: // compro otro vehiculo
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $prospeccion->preg3_sec1 = $_POST['GestionProspeccionRp']['nuevousado'];
                            $prospeccion->preg3_sec2 = $_POST['GestionProspeccionRp']['preg3_sec2'];
                            $prospeccion->preg3_sec3 = $_POST['Cotizador']['modelo'];
                            $prospeccion->preg3_sec4 = $_POST['Cotizador']['year'];

                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 0;
                            $gestion->proximo_seguimiento = '';
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        case 4:// si estoy interesado
                            //die('enter case 4');
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $prospeccion->preg4_sec1 = $_POST['GestionDiaria']['agendamiento'];
                            $prospeccion->preg4_sec2 = $_POST['GestionProspeccionRp']['lugar'];
                            $prospeccion->preg4_sec3 = $_POST['GestionProspeccionRp']['agregar'];
                            if ($_POST['GestionProspeccionRp']['lugar'] == 0) {
                                $prospeccion->preg4_sec4 = $_POST['Casos']['concesionario'];
                            }
                            if (isset($_POST['GestionProspeccionRp']['ingresolugar'])):
                                $prospeccion->preg4_sec5 = $_POST['GestionProspeccionRp']['ingresolugar'];
                            endif;

                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 1;
                            $gestion->proximo_seguimiento = $_POST['GestionDiaria']['agendamiento'];
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            //die('after save');
                            break;
                        case 5:// no contesta
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $prospeccion->preg5_sec1 = $_POST['GestionDiaria']['agendamiento2'];

                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 3;
                            $gestion->proximo_seguimiento = $_POST['GestionDiaria']['agendamiento2'];
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        case 6: // telefono equivocado
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 0;
                            $gestion->proximo_seguimiento = '';
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        case 15:// tipo usados o exonerados
                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->primera_visita = 1;
                            $gestion->status = 0;
                            $gestion->proximo_seguimiento = '';
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->paso = '3';
                            $gestion->save();

                            $consulta = new GestionConsulta;
                            $consulta->id_informacion = $model->id;
                            $consulta->fecha = date("Y-m-d H:i:s");
                            $consulta->status = 'ACTIVO';
                            $consulta->save();
                            require_once 'email/mail_func.php';
                            $asunto = 'Kia Motors Ecuador - Nuevo Exonerado Taxi.';
                            $general = '<body style="margin: 10px;">
                                <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;">
                                    <div align="">
                                    <img src="images/header_mail.jpg"><br>

                                        <p style="margin: 2px 0;">Reciba un cordial saludo de Kia Motors Ecuador.</p><br /> 
                                        <p style="margin: 2px 0;">Este email es una notificación electrónica de que su prueba de manejo se ha efectuado con éxito.</p>
                                        <p></p> <br />   
                                        <p style="margin: 2px 0;">A continuación le presentamos el detalle:</p><br /><br />
                                        
                                        <table width="600">
                                        <tr><td><strong>Asesor Comercial:</strong></td><td>' . $this->getResponsable($id_asesor) . '</td></tr>
                                        <tr><td><strong>Concesionario:</strong></td><td>' . $this->getNameConcesionario($id_asesor) . '</td></tr> 
                                        <tr><td><strong>Modelo:</strong></td><td>' . $this->getModeloTestDrive($_POST['GestionTestDrive']['id_vehiculo']) . '</td></tr>
                                        <tr><td><strong>Fecha:</strong></td><td>' . date("d") . "/" . date("m") . "/" . date("Y") . '</td></tr>
                                        <tr><td><strong>Hora:</strong></td><td>' . date("H:i:s") . '</td></tr>
                                        </table>
                                        <br/><br />
                                    </div>
                                    <br /><br />
                                </div>
                            </body>';
                            //die('table: '.$general);
                            $codigohtml = $general;
                            $headers = 'From: servicioalcliente@kiamail.com.ec' . "\r\n";
                            $headers .= 'Content-type: text/html' . "\r\n";
                            $email = $email_responsable; //email cliente registrado
                            //$email = 'alkanware@gmail.com'; //email administrador
                            //$send = sendEmailInfo('servicioalcliente@kiamail.com.ec', "Kia Motors Ecuador", $email, html_entity_decode($asunto), $codigohtml);

                            break;
                        default:
                            break;
                    }
                    $prospeccion->fecha = date("Y-m-d H:i:s");
                    $prospeccion->save();

                    $historial->id_responsable = Yii::app()->user->getId();
                    $historial->id_informacion = $model->id;
                    $historial->observacion = 'Nuevo registro de usuario';
                    $historial->paso = '1-2';
                    $historial->fecha = date("Y-m-d H:i:s");
                    $historial->save();
                    if ($cargo_id == 75)
                        $this->redirect(array('gestionInformacion/seguimientoexonerados'));
                    else
                        $this->redirect(array('gestionInformacion/seguimiento'));
                } else if ($_POST['tipo'] == 'prospeccion' && $_POST['yt0'] == 'Abandonar') {
                    $this->redirect(array('gestionInformacion/seguimiento'));
                }

                if ($_POST['tipo'] == 'trafico') {
                    $this->redirect(array('gestionInformacion/seguimiento'));
                }
                $this->redirect(array('gestionConsulta/create', 'id_informacion' => $model->id, 'tipo' => $_POST['tipo'], 'fuente' => $fuente));
            }
        }
        $this->render('exoneradoscr', array(
            'model' => $model, 'tipo' => $tipo, 'id' => $id, 'fuente' => $fuente
        ));
    }

    public function actionUsados($tipo = NULL, $id = NULL, $fuente = NULL, $tipo_fuente = NULL) {
        $model = new GestionInformacion;
        $id_responsable = Yii::app()->user->getId();
        $dealer_id = $this->getConcesionarioDealerId($id_responsable);

        if (isset($_POST['GestionInformacion'])) {
//            echo '<pre>';
//            print_r($_POST);
//            echo '</pre>';
//            die();
            $historial = new GestionHistorial;
            $model->attributes = $_POST['GestionInformacion'];
            $model->marca_usado = $_POST['GestionInformacion']['marca_usado'];
            $model->modelo_usado = $_POST['GestionInformacion']['modelo_usado'];
            if (isset($_POST['GestionInformacion']['tipo_form_web']))
                $model->tipo_form_web = $_POST['GestionInformacion']['tipo_form_web'];

            if (isset($_POST['GestionInformacion']['presupuesto']))
                $model->presupuesto = $_POST['GestionInformacion']['presupuesto'];

            if (isset($_POST['GestionInformacion']['iden'])) {
                if (($_POST['GestionInformacion']['iden'] == 'ruc') && !isset($_POST['tipo_fuente'])) {
                    $model->setscenario('ruc');
                }
                if (($_POST['GestionInformacion']['iden'] == 'pasaporte') && !isset($_POST['tipo_fuente'])) {
                    $model->setscenario('pasaporte');
                    $model->pasaporte = $_POST['GestionInformacion']['pasaporte'];
                }
                if (($_POST['GestionInformacion']['iden'] == 'ruc') && isset($_POST['tipo_fuente'])) {
                    $model->setscenario('rucusado');
                    $model->provincia_domicilio = $_POST['GestionInformacion']['provincia_domicilio'];
                    $model->ciudad_domicilio = $_POST['GestionInformacion']['ciudad_domicilio'];
                }
                if (($_POST['GestionInformacion']['iden'] == 'pasaporte') && isset($_POST['tipo_fuente'])) {
                    $model->setscenario('pasaporteusado');
                    $model->pasaporte = $_POST['GestionInformacion']['pasaporte'];
                    $model->provincia_domicilio = $_POST['GestionInformacion']['provincia_domicilio'];
                    $model->ciudad_domicilio = $_POST['GestionInformacion']['ciudad_domicilio'];
                }
            }

            $random_key = $this->getRandomKey(77, $dealer_id); //usados

            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            $model->responsable = $random_key;
            $model->responsable_origen = Yii::app()->user->getId();
            $model->dealer_id = $this->getDealerId(Yii::app()->user->getId());
            $model->id_cotizacion = $_POST['GestionInformacion']['id_cotizacion'];
            $model->nombres = ucfirst($_POST['GestionInformacion']['nombres']);
            $model->apellidos = ucfirst($_POST['GestionInformacion']['apellidos']);
            if ($_POST['tipo'] == 'gestion'):
                $model->provincia_conc = $_POST['GestionInformacion']['provincia_conc'];
                $model->ciudad_conc = $_POST['GestionInformacion']['ciudad_conc'];
                $model->concesionario = $_POST['GestionInformacion']['concesionario'];
                $model->provincia_domicilio = $_POST['GestionInformacion']['provincia_domicilio'];
                $model->ciudad_domicilio = $_POST['GestionInformacion']['ciudad_domicilio'];
                $model->marca_usado = $_POST['GestionInformacion']['marca_usado'];
                //$params = explode('@', $_POST['GestionInformacion']['modelo_usado']);
                $model->modelo_usado = $_POST['GestionInformacion']['modelo_usado'];
            endif;

            if (isset($_POST['GestionInformacion']['fecha_cita']))
                $model->fecha_cita = $_POST['GestionInformacion']['fecha_cita'];

            if (($_POST['tipo'] == 'gestion') && ($_POST['GestionInformacion']['iden'] == 'ci')) {
                $model->setscenario('gestion');
            } else if (($_POST['tipo'] == 'prospeccion') && !isset($_POST['tipo_fuente'])) {
                $model->setscenario('prospeccion');
            }

            $gestion = new GestionDiaria;
            if ($model->save()) {
                //die('enter save');
                if ($_POST['tipo'] == 'gestion' && $_POST['yt0'] == 'Continuar') {
                    //die('enter continuar');
                    // enviar a la pantalla de seguimiento con el nuevo caso ingresado
                    // ingresar datos en gestion diaria con status 1: prospección

                    $gestion->paso = $_POST['GestionInformacion']['paso'];
                    $prospeccion = new GestionProspeccionRp;
                    $prospeccion->id_informacion = $model->id;
                    $observaciones = $_POST['GestionProspeccionPr']['pregunta'];
                    switch ($observaciones) {
                        case 1:// no estoy interesado
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 0;
                            $gestion->proximo_seguimiento = '';
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        case 2:// falta de dinero
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 0;
                            $gestion->proximo_seguimiento = '';
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        case 3: // compro otro vehiculo
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $prospeccion->preg3_sec1 = $_POST['GestionProspeccionRp']['nuevousado'];
                            $prospeccion->preg3_sec2 = $_POST['GestionProspeccionRp']['preg3_sec2'];
                            $prospeccion->preg3_sec3 = $_POST['Cotizador']['modelo'];
                            $prospeccion->preg3_sec4 = $_POST['Cotizador']['year'];

                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 0;
                            $gestion->proximo_seguimiento = '';
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        case 4:// si estoy interesado
                            //die('enter case 4');
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $prospeccion->preg4_sec1 = $_POST['GestionDiaria']['agendamiento'];
                            $prospeccion->preg4_sec2 = $_POST['GestionProspeccionRp']['lugar'];
                            $prospeccion->preg4_sec3 = $_POST['GestionProspeccionRp']['agregar'];
                            if ($_POST['GestionProspeccionRp']['lugar'] == 0) {
                                $prospeccion->preg4_sec4 = $_POST['Casos']['concesionario'];
                            }
                            if (isset($_POST['GestionProspeccionRp']['ingresolugar'])):
                                $prospeccion->preg4_sec5 = $_POST['GestionProspeccionRp']['ingresolugar'];
                            endif;

                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 1;
                            $gestion->proximo_seguimiento = $_POST['GestionDiaria']['agendamiento'];
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            //die('after save');
                            break;
                        case 5:// no contesta
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $prospeccion->preg5_sec1 = $_POST['GestionDiaria']['agendamiento2'];

                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 3;
                            $gestion->proximo_seguimiento = $_POST['GestionDiaria']['agendamiento2'];
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        case 6: // telefono equivocado
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 0;
                            $gestion->proximo_seguimiento = '';
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        case 15:// tipo usados
                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 0;
                            $gestion->proximo_seguimiento = '';
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        default:
                            break;
                    }
                    $prospeccion->fecha = date("Y-m-d H:i:s");
                    $prospeccion->save();

                    $historial->id_responsable = Yii::app()->user->getId();
                    $historial->id_informacion = $model->id;
                    $historial->observacion = 'Nuevo registro de usuario';
                    $historial->paso = '1-2';
                    $historial->fecha = date("Y-m-d H:i:s");
                    $historial->save();

                    $this->redirect(array('gestionInformacion/seguimiento'));
                } else if ($_POST['tipo'] == 'prospeccion' && $_POST['yt0'] == 'Abandonar') {
                    $this->redirect(array('gestionInformacion/seguimiento'));
                }

                if ($_POST['tipo'] == 'trafico') {
                    $gestion->id_informacion = $model->id;
                    $gestion->id_vehiculo = 0;
                    $gestion->observaciones = 'Prospección';
                    $gestion->medio_contacto = 'telefono';
                    $gestion->fuente_contacto = 'prospeccion';
                    $gestion->codigo_vehiculo = 0;
                    $gestion->prospeccion = 1;
                    $gestion->status = 0;
                    $gestion->proximo_seguimiento = '';
                    $gestion->fecha = date("Y-m-d H:i:s");
                    $gestion->save();
                    //die('enter trafico usado');
                    $this->redirect(array('gestionInformacion/seguimiento'));
                }
                $this->redirect(array('gestionConsulta/create', 'id_informacion' => $model->id, 'tipo' => $_POST['tipo'], 'fuente' => $fuente));
            }
        }
        $this->render('usadoscr', array(
            'model' => $model, 'tipo' => $tipo, 'id' => $id, 'fuente' => $fuente
        ));
    }

    public function actionConadis($tipo = NULL, $id = NULL, $fuente = NULL, $tipo_fuente = NULL) {
        $model = new GestionInformacion;
        $cargo_id = (int) Yii::app()->user->getState('cargo_id');
        $id_responsable = Yii::app()->user->getId();
        $dealer_id = $this->getConcesionarioDealerId($id_responsable);
        if (isset($_POST['GestionInformacion'])) {
//            echo '<pre>';
//            print_r($_POST);
//            echo '</pre>';
//            die();
            $historial = new GestionHistorial;
            $model->attributes = $_POST['GestionInformacion'];
            if (isset($_POST['GestionInformacion']['tipo_form_web']))
                $model->tipo_form_web = $_POST['GestionInformacion']['tipo_form_web'];

            if (isset($_POST['GestionInformacion']['tipo_ex']))
                $model->tipo_ex = $_POST['GestionInformacion']['tipo_ex'];

            if (isset($_POST['GestionInformacion']['presupuesto']))
                $model->presupuesto = $_POST['GestionInformacion']['presupuesto'];

            if (isset($_POST['GestionInformacion']['iden'])) {
                if (($_POST['GestionInformacion']['iden'] == 'ruc') && !isset($_POST['tipo_fuente'])) {
                    $model->setscenario('ruc');
                }
                if (($_POST['GestionInformacion']['iden'] == 'pasaporte') && !isset($_POST['tipo_fuente'])) {
                    $model->setscenario('pasaporte');
                    $model->pasaporte = $_POST['GestionInformacion']['pasaporte'];
                }
                if (($_POST['GestionInformacion']['iden'] == 'ruc') && isset($_POST['tipo_fuente'])) {
                    $model->setscenario('rucusado');
                    $model->provincia_domicilio = $_POST['GestionInformacion']['provincia_domicilio'];
                    $model->ciudad_domicilio = $_POST['GestionInformacion']['ciudad_domicilio'];
                }
                if (($_POST['GestionInformacion']['iden'] == 'pasaporte') && isset($_POST['tipo_fuente'])) {
                    $model->setscenario('pasaporteusado');
                    $model->pasaporte = $_POST['GestionInformacion']['pasaporte'];
                    $model->provincia_domicilio = $_POST['GestionInformacion']['provincia_domicilio'];
                    $model->ciudad_domicilio = $_POST['GestionInformacion']['ciudad_domicilio'];
                }
            }


            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            if ($cargo_id == 75) {
                $model->responsable = Yii::app()->user->getId();
            }
            if ($cargo_id == 71 || $cargo_id == 70) { // asesor de ventas y jefe de sucursal
                $random_key = $this->getRandomKey(75, $dealer_id); //exonerados
                $model->responsable = $random_key;
                $model->responsable_origen = Yii::app()->user->getId();
            }
            $model->dealer_id = $this->getDealerId(Yii::app()->user->getId());
            $model->id_cotizacion = $_POST['GestionInformacion']['id_cotizacion'];
            $model->nombres = ucfirst($_POST['GestionInformacion']['nombres']);
            $model->apellidos = ucfirst($_POST['GestionInformacion']['apellidos']);
            $model->modelo = $_POST['GestionVehiculo']['modelo'];
            $model->marca_usado = $_POST['GestionVehiculo']['version'];
            if ($_POST['tipo'] == 'gestion'):
                $model->provincia_conc = $_POST['GestionInformacion']['provincia_conc'];
                $model->ciudad_conc = $_POST['GestionInformacion']['ciudad_conc'];
                $model->concesionario = $_POST['GestionInformacion']['concesionario'];
                $model->provincia_domicilio = $_POST['GestionInformacion']['provincia_domicilio'];
                $model->ciudad_domicilio = $_POST['GestionInformacion']['ciudad_domicilio'];
            endif;

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
            if (isset($_POST['GestionInformacion']['porcentaje_discapacidad']))
                $model->porcentaje_discapacidad = $_POST['GestionInformacion']['porcentaje_discapacidad'];
            if (isset($_POST['GestionInformacion']['senae']))
                $model->senae = $_POST['GestionInformacion']['senae'];

            if (isset($_POST['GestionInformacion']['fecha_cita']))
                $model->fecha_cita = $_POST['GestionInformacion']['fecha_cita'];

            if (($_POST['tipo'] == 'gestion') && ($_POST['GestionInformacion']['iden'] == 'ci')) {
                $model->setscenario('gestion');
            } else if (($_POST['tipo'] == 'prospeccion') && !isset($_POST['tipo_fuente'])) {
                $model->setscenario('prospeccion');
            }


            if ($model->save()) {
                //die('enter save');
                if ($_POST['tipo'] == 'gestion') {
                    //die('enter continuar');
                    // enviar a la pantalla de seguimiento con el nuevo caso ingresado
                    // ingresar datos en gestion diaria con status 1: prospección
                    $gestion = new GestionDiaria;
                    $gestion->paso = $_POST['GestionInformacion']['paso'];
                    $prospeccion = new GestionProspeccionRp;
                    $prospeccion->id_informacion = $model->id;
                    $observaciones = $_POST['GestionProspeccionPr']['pregunta'];
                    //die('obs '.$observaciones);
                    switch ($observaciones) {
                        case 1:// no estoy interesado
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 0;
                            $gestion->proximo_seguimiento = '';
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        case 2:// falta de dinero
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 0;
                            $gestion->proximo_seguimiento = '';
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        case 3: // compro otro vehiculo
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $prospeccion->preg3_sec1 = $_POST['GestionProspeccionRp']['nuevousado'];
                            $prospeccion->preg3_sec2 = $_POST['GestionProspeccionRp']['preg3_sec2'];
                            $prospeccion->preg3_sec3 = $_POST['Cotizador']['modelo'];
                            $prospeccion->preg3_sec4 = $_POST['Cotizador']['year'];

                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 0;
                            $gestion->proximo_seguimiento = '';
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        case 4:// si estoy interesado
                            //die('enter case 4');
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $prospeccion->preg4_sec1 = $_POST['GestionDiaria']['agendamiento'];
                            $prospeccion->preg4_sec2 = $_POST['GestionProspeccionRp']['lugar'];
                            $prospeccion->preg4_sec3 = $_POST['GestionProspeccionRp']['agregar'];
                            if ($_POST['GestionProspeccionRp']['lugar'] == 0) {
                                $prospeccion->preg4_sec4 = $_POST['Casos']['concesionario'];
                            }
                            if (isset($_POST['GestionProspeccionRp']['ingresolugar'])):
                                $prospeccion->preg4_sec5 = $_POST['GestionProspeccionRp']['ingresolugar'];
                            endif;

                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 1;
                            $gestion->proximo_seguimiento = $_POST['GestionDiaria']['agendamiento'];
                            $gestion->fecha = date("Y-m-d H:i:s");
                            if ($gestion->validate()) {
                                die('no errores');
                            } else {
                                die('error');
                            }
                            $gestion->save();
                            //die('after save');
                            break;
                        case 5:// no contesta
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $prospeccion->preg5_sec1 = $_POST['GestionDiaria']['agendamiento2'];

                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 3;
                            $gestion->proximo_seguimiento = $_POST['GestionDiaria']['agendamiento2'];
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        case 6: // telefono equivocado
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 0;
                            $gestion->proximo_seguimiento = '';
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        case 15:// tipo usados
                            //die('enter exo cnadis');

                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->primera_visita = 1;
                            $gestion->status = 0;
                            $gestion->proximo_seguimiento = '';
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->paso = '3';
                            $gestion->save();
                            $consulta = new GestionConsulta;
                            $consulta->id_informacion = $model->id;
                            $consulta->fecha = date("Y-m-d H:i:s");
                            $consulta->status = 'ACTIVO';
                            $consulta->save();
                            break;
                        default:
                            break;
                    }
                    $prospeccion->fecha = date("Y-m-d H:i:s");
                    $prospeccion->save();

                    $historial->id_responsable = Yii::app()->user->getId();
                    $historial->id_informacion = $model->id;
                    $historial->observacion = 'Nuevo registro de usuario';
                    $historial->paso = '1-2';
                    $historial->fecha = date("Y-m-d H:i:s");
                    $historial->save();

                    if ($cargo_id == 75)
                        $this->redirect(array('gestionInformacion/seguimientoexonerados'));
                    else
                        $this->redirect(array('gestionInformacion/seguimiento'));
                } else if ($_POST['tipo'] == 'prospeccion' && $_POST['yt0'] == 'Abandonar') {
                    $this->redirect(array('gestionInformacion/seguimiento'));
                }

                if ($_POST['tipo'] == 'trafico') {
                    $this->redirect(array('gestionInformacion/seguimiento'));
                }
                //$this->redirect(array('gestionConsulta/create', 'id_informacion' => $model->id, 'tipo' => $_POST['tipo'], 'fuente' => $fuente));
                $this->redirect(array('gestionInformacion/seguimiento'));
            }
        }
        $this->render('conadis', array(
            'model' => $model, 'tipo' => $tipo, 'id' => $id, 'fuente' => $fuente
        ));
    }

    public function actionDiplomaticos($tipo = NULL, $id = NULL, $fuente = NULL, $tipo_fuente = NULL) {
        $model = new GestionInformacion;
        $cargo_id = (int) Yii::app()->user->getState('cargo_id');
        $id_responsable = Yii::app()->user->getId();
        $dealer_id = $this->getConcesionarioDealerId($id_responsable);
        if (isset($_POST['GestionInformacion'])) {
//            echo '<pre>';
//            print_r($_POST);
//            echo '</pre>';
//            die();
            $historial = new GestionHistorial;
            $model->attributes = $_POST['GestionInformacion'];
            if (isset($_POST['GestionInformacion']['tipo_form_web']))
                $model->tipo_form_web = $_POST['GestionInformacion']['tipo_form_web'];

            if (isset($_POST['GestionInformacion']['tipo_ex']))
                $model->tipo_ex = $_POST['GestionInformacion']['tipo_ex'];

            if (isset($_POST['GestionInformacion']['presupuesto']))
                $model->presupuesto = $_POST['GestionInformacion']['presupuesto'];

            if (isset($_POST['GestionInformacion']['iden'])) {
                if (($_POST['GestionInformacion']['iden'] == 'ruc') && !isset($_POST['tipo_fuente'])) {
                    $model->setscenario('ruc');
                }
                if (($_POST['GestionInformacion']['iden'] == 'pasaporte') && !isset($_POST['tipo_fuente'])) {
                    $model->setscenario('pasaporte');
                    $model->pasaporte = $_POST['GestionInformacion']['pasaporte'];
                }
                if (($_POST['GestionInformacion']['iden'] == 'ruc') && isset($_POST['tipo_fuente'])) {
                    $model->setscenario('rucusado');
                    $model->provincia_domicilio = $_POST['GestionInformacion']['provincia_domicilio'];
                    $model->ciudad_domicilio = $_POST['GestionInformacion']['ciudad_domicilio'];
                }
                if (($_POST['GestionInformacion']['iden'] == 'pasaporte') && isset($_POST['tipo_fuente'])) {
                    $model->setscenario('pasaporteusado');
                    $model->pasaporte = $_POST['GestionInformacion']['pasaporte'];
                    $model->provincia_domicilio = $_POST['GestionInformacion']['provincia_domicilio'];
                    $model->ciudad_domicilio = $_POST['GestionInformacion']['ciudad_domicilio'];
                }
            }

            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            if ($cargo_id == 75) {
                $model->responsable = Yii::app()->user->getId();
            }
            if ($cargo_id == 71 || $cargo_id == 70) { // asesor de ventas y jefe de sucursal
                $random_key = $this->getRandomKey(75, $dealer_id); //exonerados
                $model->responsable = $random_key;
                $model->responsable_origen = Yii::app()->user->getId();
            }
            $model->dealer_id = $this->getDealerId(Yii::app()->user->getId());
            $model->id_cotizacion = $_POST['GestionInformacion']['id_cotizacion'];
            $model->nombres = ucfirst($_POST['GestionInformacion']['nombres']);
            $model->apellidos = ucfirst($_POST['GestionInformacion']['apellidos']);
            $model->modelo = $_POST['GestionVehiculo']['modelo'];
            $model->marca_usado = $_POST['GestionVehiculo']['version'];
            if ($_POST['tipo'] == 'gestion'):
                $model->provincia_conc = $_POST['GestionInformacion']['provincia_conc'];
                $model->ciudad_conc = $_POST['GestionInformacion']['ciudad_conc'];
                $model->concesionario = $_POST['GestionInformacion']['concesionario'];
                $model->provincia_domicilio = $_POST['GestionInformacion']['provincia_domicilio'];
                $model->ciudad_domicilio = $_POST['GestionInformacion']['ciudad_domicilio'];
            endif;

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

            if (isset($_POST['GestionInformacion']['fecha_cita']))
                $model->fecha_cita = $_POST['GestionInformacion']['fecha_cita'];

            if (($_POST['tipo'] == 'gestion') && ($_POST['GestionInformacion']['iden'] == 'ci')) {
                $model->setscenario('gestion');
            } else if (($_POST['tipo'] == 'prospeccion') && !isset($_POST['tipo_fuente'])) {
                $model->setscenario('prospeccion');
            }


            if ($model->save()) {
                //die('enter save');
                //die($_POST['tipo']);
                /* echo '<pre>';
                  print_r($_POST);
                  echo '</pre>';
                  die(); */

                if ($_POST['tipo'] == 'gestion') {
                    //die('enter continuar');
                    // enviar a la pantalla de seguimiento con el nuevo caso ingresado
                    // ingresar datos en gestion diaria con status 1: prospección
                    $gestion = new GestionDiaria;
                    $gestion->paso = $_POST['GestionInformacion']['paso'];
                    $prospeccion = new GestionProspeccionRp;
                    $prospeccion->id_informacion = $model->id;
                    $observaciones = $_POST['GestionProspeccionPr']['pregunta'];
                    switch ($observaciones) {
                        case 1:// no estoy interesado
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 0;
                            $gestion->proximo_seguimiento = '';
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        case 2:// falta de dinero
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 0;
                            $gestion->proximo_seguimiento = '';
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        case 3: // compro otro vehiculo
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $prospeccion->preg3_sec1 = $_POST['GestionProspeccionRp']['nuevousado'];
                            $prospeccion->preg3_sec2 = $_POST['GestionProspeccionRp']['preg3_sec2'];
                            $prospeccion->preg3_sec3 = $_POST['Cotizador']['modelo'];
                            $prospeccion->preg3_sec4 = $_POST['Cotizador']['year'];

                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 0;
                            $gestion->proximo_seguimiento = '';
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        case 4:// si estoy interesado
                            //die('enter case 4');
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $prospeccion->preg4_sec1 = $_POST['GestionDiaria']['agendamiento'];
                            $prospeccion->preg4_sec2 = $_POST['GestionProspeccionRp']['lugar'];
                            $prospeccion->preg4_sec3 = $_POST['GestionProspeccionRp']['agregar'];
                            if ($_POST['GestionProspeccionRp']['lugar'] == 0) {
                                $prospeccion->preg4_sec4 = $_POST['Casos']['concesionario'];
                            }
                            if (isset($_POST['GestionProspeccionRp']['ingresolugar'])):
                                $prospeccion->preg4_sec5 = $_POST['GestionProspeccionRp']['ingresolugar'];
                            endif;

                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 1;
                            $gestion->proximo_seguimiento = $_POST['GestionDiaria']['agendamiento'];
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            //die('after save');
                            break;
                        case 5:// no contesta
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $prospeccion->preg5_sec1 = $_POST['GestionDiaria']['agendamiento2'];

                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 3;
                            $gestion->proximo_seguimiento = $_POST['GestionDiaria']['agendamiento2'];
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        case 6: // telefono equivocado
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->status = 0;
                            $gestion->proximo_seguimiento = '';
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        case 15:// tipo usados
                            //die('enter dipl');
                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->primera_visita = 1;
                            $gestion->status = 0;
                            $gestion->proximo_seguimiento = '';
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->paso = '3';
                            $gestion->save();

                            $consulta = new GestionConsulta;
                            $consulta->id_informacion = $model->id;
                            $consulta->fecha = date("Y-m-d H:i:s");
                            $consulta->status = 'ACTIVO';
                            $consulta->save();
                            break;
                        default:
                            break;
                    }
                    $prospeccion->fecha = date("Y-m-d H:i:s");
                    $prospeccion->save();

                    $historial->id_responsable = Yii::app()->user->getId();
                    $historial->id_informacion = $model->id;
                    $historial->observacion = 'Nuevo registro de usuario';
                    $historial->paso = '1-2';
                    $historial->fecha = date("Y-m-d H:i:s");
                    $historial->save();

                    if ($cargo_id == 75)
                        $this->redirect(array('gestionInformacion/seguimientoexonerados'));
                    else
                        $this->redirect(array('gestionInformacion/seguimiento'));
                } else if ($_POST['tipo'] == 'prospeccion' && $_POST['yt0'] == 'Abandonar') {
                    $this->redirect(array('gestionInformacion/seguimiento'));
                }

                if ($_POST['tipo'] == 'trafico') {
                    $this->redirect(array('gestionInformacion/seguimiento'));
                }
                $this->redirect(array('gestionConsulta/create', 'id_informacion' => $model->id, 'tipo' => $_POST['tipo'], 'fuente' => $fuente));
            }
        }
        $this->render('diplomaticos', array(
            'model' => $model, 'tipo' => $tipo, 'id' => $id, 'fuente' => $fuente
        ));
    }

    public function actionSeguimientoUsados() {
        $cargo_id = (int) Yii::app()->user->getState('cargo_id');
        $grupo_id = (int) Yii::app()->user->getState('grupo_id');
        //die('grupo id: '.$grupo_id);
        $area_id = (int) Yii::app()->user->getState('area_id');
        $id_responsable = Yii::app()->user->getId();
        //die('id responsable: '.$id_responsable);
        $array_dealers = $this->getDealerGrupoConc($grupo_id);
        $dealerList = implode(', ', $array_dealers);
        if ($cargo_id != 46)
            $dealer_id = $this->getDealerId($id_responsable);
        $con = Yii::app()->db;
        date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
//        $fechaActual = date("Y/m/d");
//        $params = explode('-', $_GET['GestionSolicitudCredito']['fecha']);
//        $fechaPk = 0;
//        if (($fechaActual == trim($params[0])) && ($fechaActual == trim($params[1]))) {
//            $fechaPk = 1;
//        }

        if (isset($_GET['GestionSolicitudCredito'])) {
//            echo '<pre>';
//            print_r($_GET);
//            echo '</pre>';
//            die();
            //---- BUSQUEDA GENERAL----
            if (!empty($_GET['GestionSolicitudCredito']['general'])) {
                //die('enter nombres apellidos');

                /* BUSQUEDA POR NOMBRES O APELLIDOS */
                $sql = "SELECT gi.*, gd.proximo_seguimiento FROM gestion_informacion gi 
                        LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id
                        WHERE (gi.tipo_form_web = 'usado' OR gi.tipo_form_web = 'usadopago') 
                        AND (gi.nombres LIKE '%{$_GET['GestionSolicitudCredito']['general']}%' OR gi.apellidos LIKE '%{$_GET['GestionSolicitudCredito']['general']}%')";
                if ($cargo_id != 69) { // gerente comercial           
                    $sql .= " AND gi.dealer_id = {$dealer_id} AND gi.responsable = {$id_responsable}";
                }
                //die($sql);        
                $request = $con->createCommand($sql);
                $posts = $request->queryAll();
                if (count($posts) > 0) {
                    //die('enter count nombre');
                    $this->render('usados', array(
                        'users' => $posts, 'search' => true
                    ));
                    exit();
                }

                /* BUSQUEDA POR CEDULA,RUC O PASAPORTE */
                $sql = "SELECT gi.*, gd.proximo_seguimiento FROM gestion_informacion gi 
                        LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id
                        WHERE gi.tipo_form_web = 'usado' 
                        AND (gi.cedula LIKE '%{$_GET['GestionSolicitudCredito']['general']}%' 
                        OR gi.ruc LIKE '%{$_GET['GestionSolicitudCredito']['general']}%' "
                        . "OR gi.pasaporte LIKE '%{$_GET['GestionSolicitudCredito']['general']}%')";
                //die('sql cedula: '.$sql);
                $request = $con->createCommand($sql);
                $posts = $request->queryAll();
                if (count($posts) > 0) {
                    //die('enter count ruc cedula');
                    $this->render('usados', array(
                        'users' => $posts, 'search' => true
                    ));
                    exit();
                }

                /* BUSQUEDA POR ID */
                $sql = "SELECT gi.*, gd.proximo_seguimiento FROM gestion_informacion gi 
                        LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id
                        WHERE gi.tipo_form_web = 'usado' 
                        AND gi.id = '{$_GET['GestionSolicitudCredito']['general']}'";
                //die('sql id: '.$sql);
                $request = $con->createCommand($sql);
                $posts = $request->queryAll();
                if (count($posts) > 0) {
                    //die('enter count ruc cedula');
                    $this->render('usados', array(
                        'users' => $posts, 'search' => true
                    ));
                    exit();
                }
            }


            //---- BUSQUEDA GENERAL EN JEFE USADOS----
            if (!empty($_GET['GestionSolicitudCredito']['general']) &&
                    empty($_GET['GestionSolicitudCredito']['responsable'])) {
                //die('enter general jefe');
                /* BUSQUEDA POR NOMBRES O APELLIDOS */
                $sql = "SELECT gi.*, gd.proximo_seguimiento FROM gestion_informacion gi 
                        LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id
                        WHERE (gi.tipo_form_web = 'usado' OR gi.tipo_form_web = 'usadopago') 
                        AND (gi.nombres LIKE '%{$_GET['GestionSolicitudCredito']['general']}%' OR gi.apellidos LIKE '%{$_GET['GestionSolicitudCredito']['general']}%')";
                if ($cargo_id != 69) { // gerente comercial           
                    $sql .= " AND gi.dealer_id IN ({$dealerList})";
                }
                //die($sql);        
                $request = $con->createCommand($sql);
                $posts = $request->queryAll();
                if (count($posts) > 0) {
                    //die('enter count nombre');
                    $this->render('usados', array(
                        'users' => $posts, 'search' => true
                    ));
                    exit();
                }

                /* BUSQUEDA POR CEDULA,RUC O PASAPORTE */
                $sql = "SELECT gi.*, gd.proximo_seguimiento FROM gestion_informacion gi 
                        LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id
                        WHERE gi.tipo_form_web = 'usado' 
                        AND (gi.cedula LIKE '%{$_GET['GestionSolicitudCredito']['general']}%' 
                        OR gi.ruc LIKE '%{$_GET['GestionSolicitudCredito']['general']}%' "
                        . "OR gi.pasaporte LIKE '%{$_GET['GestionSolicitudCredito']['general']}%')";
                $sql .= " AND gi.dealer_id IN ({$dealerList})";
                //die('sql cedula: '.$sql);
                $request = $con->createCommand($sql);
                $posts = $request->queryAll();
                if (count($posts) > 0) {
                    //die('enter count ruc cedula');
                    $this->render('usados', array(
                        'users' => $posts, 'search' => true
                    ));
                    exit();
                }

                /* BUSQUEDA POR ID */
                $sql = "SELECT gi.*, gd.proximo_seguimiento FROM gestion_informacion gi 
                        LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id
                        WHERE gi.tipo_form_web = 'usado' 
                        AND gi.id = '{$_GET['GestionSolicitudCredito']['general']}'";
                $sql .= " AND gi.dealer_id IN ({$dealerList})";
                //die('sql id: '.$sql);
                $request = $con->createCommand($sql);
                $posts = $request->queryAll();
                if (count($posts) > 0) {
                    //die('enter count ruc cedula');
                    $this->render('usados', array(
                        'users' => $posts, 'search' => true
                    ));
                    exit();
                }
            }

            //---- BUSQUEDA POR RESPONSABLE----
            if (empty($_GET['GestionSolicitudCredito']['general']) &&
                    !empty($_GET['GestionSolicitudCredito']['responsable'])) {
                $sql = "SELECT gi.*, gd.proximo_seguimiento FROM gestion_informacion gi 
                        LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id 
                        INNER JOIN usuarios u ON u.id = gi.responsable 
                        WHERE (gi.tipo_form_web = 'usado' OR gi.tipo_form_web = 'usadopago')";
                if ($_GET['GestionSolicitudCredito']['responsable'] == 'all') {
                    $sql .= " AND u.grupo_id = {$grupo_id} AND cargo_id = 77
                            ORDER BY gi.id DESC";
                } else {
                    $sql .= " AND gi.dealer_id = {$dealer_id} AND gi.responsable = {$_GET['GestionSolicitudCredito']['responsable']}";
                }

                //die('sql responsable: '.$sql);
                $request = $con->createCommand($sql);
                $posts = $request->queryAll();
                if (count($posts) > 0) {
                    //die('enter count ruc cedula');
                    $this->render('usados', array(
                        'users' => $posts, 'search' => true
                    ));
                    exit();
                }
            }

            //---- BUSQUEDA POR STATUS----
            if (empty($_GET['GestionSolicitudCredito']['general']) &&
                    !empty($_GET['GestionSolicitudCredito']['status']) &&
                    empty($_GET['GestionSolicitudCredito']['responsable'])) {
                //die('busqueda status');

                $sql = "SELECT gs.*, gt.`status` FROM gestion_solicitud_credito gs 
                        INNER JOIN gestion_status_solicitud gt ON gs.id_informacion = gt.id_informacion 
                        WHERE gt.`status` = '{$_GET['GestionSolicitudCredito']['status']}'";
                //die('sql: ' . $sql);
                $request = $con->createCommand($sql);
                $posts = $request->queryAll();
                //die('num posts: '.count($posts));
                if (count($posts) > 0) {
                    $this->render('admin', array(
                        'model' => $model, 'users' => $posts, 'search' => true
                    ));
                    exit();
                } else {
                    $this->render('admin', array(
                        'model' => $model,
                    ));
                    exit();
                }
            }
        }
        if ($cargo_id == 46) { // SUPER ADMINISTRADOR
            $sql = "SELECT gi.*, gd.proximo_seguimiento FROM gestion_informacion gi 
            left JOIN gestion_diaria gd ON gd.id_informacion = gi.id
            WHERE gi.tipo_form_web = 'usado' OR  gi.tipo_form_web = 'usadopago' 
            ORDER BY gi.id DESC";
        }
        if ($cargo_id == 71) { // ASESOR DE VENTAS
            $sql = "SELECT gi.*, gd.proximo_seguimiento FROM gestion_informacion gi 
            left JOIN gestion_diaria gd ON gd.id_informacion = gi.id
            WHERE (gi.tipo_form_web = 'usado' OR  gi.tipo_form_web = 'usadopago') 
            AND gi.dealer_id = {$dealer_id}
            ORDER BY gi.id DESC";
            //die($sql);
        }if ($cargo_id == 77) { // ASESOR USADOS
            $sql = "SELECT gi.*, gd.proximo_seguimiento FROM gestion_informacion gi 
            left JOIN gestion_diaria gd ON gd.id_informacion = gi.id
            WHERE (gi.tipo_form_web = 'usado' OR  gi.tipo_form_web = 'usadopago') 
            AND gi.dealer_id = {$dealer_id} AND gi.responsable = {$id_responsable}
            ORDER BY gi.id DESC";
            //die($sql);
        }if ($cargo_id == 69) { // JEFE COMERCIAL
            $sql = "SELECT gi.*, gd.proximo_seguimiento FROM gestion_informacion gi 
            left JOIN gestion_diaria gd ON gd.id_informacion = gi.id 
            INNER JOIN usuarios u ON u.id = gi.responsable 
            WHERE (gi.tipo_form_web = 'usado' OR  gi.tipo_form_web = 'usadopago') 
            AND u.grupo_id = {$grupo_id} AND cargo_id = 77 
            ORDER BY gi.id DESC";
            //die($sql);
        }
        if ($cargo_id == 76) { // JEFE USADOS
            $sql = "SELECT gi.*, gd.proximo_seguimiento FROM gestion_informacion gi 
            left JOIN gestion_diaria gd ON gd.id_informacion = gi.id 
            INNER JOIN usuarios u ON u.id = gi.responsable 
            WHERE (gi.tipo_form_web = 'usado' OR  gi.tipo_form_web = 'usadopago') 
            AND u.grupo_id = {$grupo_id} AND cargo_id = 77
            ORDER BY gi.id DESC";
            //die($sql);
        } if ($area_id == 4 || $area_id == 12 || $area_id == 13 || $area_id == 14) {
            $sql = "SELECT gi.*, gd.proximo_seguimiento FROM gestion_informacion gi 
            left JOIN gestion_diaria gd ON gd.id_informacion = gi.id 
            INNER JOIN usuarios u ON u.id = gi.responsable 
            WHERE (gi.tipo_form_web = 'usado' OR  gi.tipo_form_web = 'usadopago') 
            AND u.cargo_id = 77
            ORDER BY gi.id DESC";
        }
        //die($sql);
        $request = $con->createCommand($sql);
        $users = $request->queryAll();
        $this->render('usados', array('users' => $users));
    }

    public function actionCalendar() {
        $this->render('calendar');
    }

    // Empieza optimización de código por Nicolás Vela 12-2015

    public function actionReportes($id_informacion = null, $id_vehiculo = null) {
        $varView = array();

        $varView['grupo_id'] = (int) Yii::app()->user->getState('grupo_id');
        $varView['cargo_id'] = (int) Yii::app()->user->getState('cargo_id');
        $varView['id_responsable'] = Yii::app()->user->getId();
        $varView['dealer_id'] = $this->getDealerId($varView['id_responsable']);
        $varView['titulo'] = '';
        $varView['concesionario'] = 2000;
        $varView['tipos'] = null;

        date_default_timezone_set('America/Guayaquil');
        $dt = time();
        setlocale(LC_TIME, 'es_ES.UTF-8');

        $varView['fecha_actual'] = strftime("%Y-%m-%d", $dt);
        $varView['fecha_actual2'] = strtotime('+1 day', strtotime($varView['fecha_actual']));
        $varView['fecha_actual2'] = date('Y-m-d', $varView['fecha_actual2']);
        $varView['fecha_inicial_actual'] = (new DateTime('first day of this month'))->format('Y-m-d');
        $varView['fecha_anterior'] = strftime("%Y-%m-%d", strtotime('-1 month', $dt));
        $varView['fecha_inicial_anterior'] = strftime("%Y-%m", strtotime('-1 month', $dt)) . '-01';
        $varView['nombre_mes_actual'] = strftime("%B - %Y", $dt);
        $varView['nombre_mes_anterior'] = strftime("%B - %Y", strtotime('-1 month', $dt));

        $con = Yii::app()->db;

        //GET MODELOS Y VERSIONES PARA BUSCADOR
        $sqlModelos_nv = "SELECT nombre_modelo, id_modelos from modelos";
        $requestModelos_nv = $con->createCommand($sqlModelos_nv);
        $varView['modelos_car'] = $requestModelos_nv->queryAll();

        //SI BUSCAN POR VERSION O MODELO Y RECIBE VARIABLES PARA LA CONSULTA
        $lista_datos = array();
        if (isset($_GET['modelo'])) {
            array_push($lista_datos, array('modelos' => $_GET['modelo']));
        }
        if (isset($_GET['version'])) {
            array_push($lista_datos, array('versiones' => $_GET['version']));
        }

        $SQLmodelos = '';
        foreach ($lista_datos as $key => $value) {
            foreach ($value as $key => $carros) {
                if ($key == 'modelos') {
                    $campo_car = 'modelo';
                } else {
                    $campo_car = 'version';
                }
                $id_carros_nv[$key] = implode(', ', $carros);
                $SQLmodelos[$key] = " AND gi." . $campo_car . " IN (" . $id_carros_nv[$key] . ") ";
            }
        }

        //GET Asesores
        $mod = new GestionDiaria;
        $cre = new CDbCriteria();
        $varView['dealer_resp'] = $this->getConcesionarioDealerId($varView['id_responsable']);
        if (!empty($dealer_resp)) {
            $varView['dealer_id'] = $varView['dealer_resp'];
        }
        $cre->condition = " cargo_id = 71 AND dealers_id = " . $varView['dealer_id'];
        $cre->order = " nombres ASC";
        $usu = CHtml::listData(Usuarios::model()->findAll($cre), "id", "fullname");

        //variables busqueda por defecto
        $tit_ext = '';
        $join_ext = null;
        $group_ext = null;
        $select_ext = null;
        $tit_init = 'Búsqueda entre ';
        switch ($varView['cargo_id']) {
            case 71: // asesor de ventas 
                $id_persona = "gi.responsable = " . $varView['id_responsable'];
                $tit_init = 'Búsqueda entre ';
                break;
            case 70: // jefe de sucursal 
                $id_persona = "gi.dealer_id = " . $varView['dealer_id'];
                $tit_init = 'Búsqueda entre ';
                break;
            case 69: // GERENTE COMERCIAL
            case 46: // SUPER ADMINISTRADOR
                $id_persona = 'u.grupo_id = ' . $varView['grupo_id'];
                $tit_init = 'Búsqueda por defecto entre ';
                $tit_ext = ', Grupo: ' . $this->getNombreGrupo($varView['grupo_id']);
                $join_ext = 'INNER JOIN usuarios u ON u.id = gi.responsable ';
                $group_ext = null;
                $select_ext = ', u.grupo_id ';
                break;
        }


        if (isset($_GET['GestionInformacion'])) {
            $tit_ext = '';
            $tipo_busqueda_trafico2 = '';
            $tipo_busqueda_proforma2 = '';
            $tipo_busqueda_testdrive2 = '';
            $tipo_busqueda_ventas2 = '';

            //SET GET VARS
            $tip_us = $_GET['GestionInformacion']['tipousuario'];

            if (!empty($_GET['GestionDiaria']['responsable']) && $tip_us != 3) {
                $responsable = $_GET['GestionDiaria']['responsable'];
                $tit_ext = '. Asesor: ' . $this->getResponsableNombres($responsable);
                $id_persona = 'gi.responsable = ' . $responsable;
                $id_persona .= " AND gi.dealer_id = " . $varView['dealer_id'] . ' ';
            } else if ($tip_us != 1 && $tip_us != 3 && empty($_GET['GestionDiaria']['responsable'])) {
                $responsable = key($usu);
                $tit_ext .= '. Asesor: ' . reset($usu);
                $id_persona = 'gi.responsable = ' . $responsable;
                $id_persona .= " AND gi.dealer_id = " . $varView['dealer_id'] . ' ';
            } else if ($tip_us == 3 && !empty($_GET['GestionInformacion']['concesionario'])) {
                $responsable = $_GET['GestionDiaria']['responsable'];
                if ($responsable) {
                    $id_persona .= " AND gi.responsable = " . $responsable . ' ';
                    $tit_ext .= '. Asesor: ' . $this->getResponsableNombres($responsable);
                }

                $tit_ext .= '. Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionInformacion']['concesionario']);
            }


            //===========================VARIABLES para getSelectCKDCBU()================================
            if ($tip_us == 1) {
                $tipo_busqueda_trafico2 = 1;
                $tipo_busqueda_proforma2 = 2;
                $tipo_busqueda_testdrive2 = 3;
                $tipo_busqueda_ventas2 = 4;
            } else if (($tip_us == 2) && !empty($_GET['GestionDiaria']['responsable'])) {
                $tipo_busqueda_trafico2 = 5;
                $tipo_busqueda_proforma2 = 6;
                $tipo_busqueda_testdrive2 = 7;
                $tipo_busqueda_ventas2 = 8;
            } else if ($tip_us == 3 && !empty($_GET['GestionInformacion']['concesionario'])) {
                $tipo_busqueda_trafico2 = 9;
                $tipo_busqueda_proforma2 = 10;
                $tipo_busqueda_testdrive2 = 11;
                $tipo_busqueda_ventas2 = 12;
            } else if ($tip_us == 3 && (($_GET['GestionInformacion']['concesionario'] == 0) || empty($_GET['GestionInformacion']['concesionario'])) && empty($_GET['GestionDiaria']['responsable'])) {
                $tipo_busqueda_trafico2 = 13;
                $tipo_busqueda_proforma2 = 14;
                $tipo_busqueda_testdrive2 = 15;
                $tipo_busqueda_ventas2 = 16;
                $tit_ext .= '. Grupo: ' . $this->getNombreGrupo($varView['grupo_id']);
            }

            $varView['fecha1'] = explode(' - ', $_GET['GestionInformacion']['fecha1']);
            $varView['fecha2'] = explode(' - ', $_GET['GestionInformacion']['fecha2']);
            $responsable = 0;

            $varView['fecha_inicial_anterior'] = trim($varView['fecha1'][0]);
            $varView['fecha_anterior'] = trim($varView['fecha1'][1]);

            $varView['fecha_inicial_actual'] = trim($varView['fecha2'][0]);
            $varView['fecha_actual'] = trim($varView['fecha2'][1]);

            $varView['nombre_mes_actual'] = strftime("%B - %Y", strtotime($varView['fecha_inicial_actual']));
            $varView['nombre_mes_anterior'] = strftime("%B - %Y", strtotime($varView['fecha_inicial_anterior']));

            $tipos = array();
            array_push($tipos, $tipo_busqueda_trafico2, $tipo_busqueda_proforma2, $tipo_busqueda_testdrive2, $tipo_busqueda_ventas2);
        }

        $retorno = $this->buscar(
                $varView['cargo_id'], $varView['id_responsable'], $select_ext, $join_ext, $id_persona, $group_ext, $varView['fecha_inicial_anterior'], $varView['fecha_anterior'], $varView['fecha_inicial_actual'], $varView['fecha_actual'], $varView['$concesionario'], $tipos, $SQLmodelos
        );
        $varView['trafico_mes_anterior'] = $retorno[0];
        $varView['trafico_mes_actual'] = $retorno[1];
        $varView['vhcbu2'] = $retorno[2];
        $varView['vhckd2'] = $retorno[3];
        $varView['vhcbu1'] = $retorno[4];
        $varView['vhckd1'] = $retorno[5];
        $varView['vh_mes_actual'] = $retorno[6];
        $varView['vh_mes_anterior'] = $retorno[7];
        $varView['tdcbu2'] = $retorno[8];
        $varView['tdckd2'] = $retorno[9];
        $varView['tdcbu1'] = $retorno[10];
        $varView['tdckd1'] = $retorno[11];
        $varView['td_mes_actual'] = $retorno[12];
        $varView['td_mes_anterior'] = $retorno[13];
        $varView['proformacbu2'] = $retorno[14];
        $varView['proformackd2'] = $retorno[15];
        $varView['proformacbu1'] = $retorno[16];
        $varView['proformackd1'] = $retorno[17];
        $varView['proforma_mes_actual'] = $retorno[18];
        $varView['proforma_mes_anterior'] = $retorno[19];
        $varView['traficocbu2'] = $retorno[20];
        $varView['traficockd2'] = $retorno[21];
        $varView['traficocbu1'] = $retorno[22];
        $varView['traficockd1'] = $retorno[23];
        $varView['lista_datos'] = $lista_datos;
        $varView['usu'] = $usu;
        $varView['mod'] = $mod;

        //set diferencias
        $varView['var_tr'] = $this->DIFconstructor($varView['trafico_mes_actual'], $varView['trafico_mes_anterior'], 'var');
        $varView['dif_tr'] = $this->DIFconstructor($varView['trafico_mes_actual'], $varView['trafico_mes_anterior'], 'dif');

        $varView['var_pr'] = $this->DIFconstructor($varView['proforma_mes_actual'], $varView['proforma_mes_anterior'], 'var');
        $varView['dif_pr'] = $this->DIFconstructor($varView['proforma_mes_actual'], $varView['proforma_mes_anterior'], 'dif');

        $varView['var_td'] = $this->DIFconstructor($varView['td_mes_actual'], $varView['td_mes_anterior'], 'var');
        $varView['dif_td'] = $this->DIFconstructor($varView['td_mes_actual'], $varView['td_mes_anterior'], 'dif');

        $varView['var_ve'] = $this->DIFconstructor($varView['vh_mes_actual'], $varView['vh_mes_anterior'], 'var');
        $varView['dif_ve'] = $this->DIFconstructor($varView['vh_mes_actual'], $varView['vh_mes_anterior'], 'dif');

        $varView['titulo'] = $tit_init . $fecha_inicial_actual . ' / ' . $fecha_actual . ', y ' . $fecha_inicial_anterior . ' / ' . $fecha_anterior . $tit_ext;

        $this->render('reportes', array('varView' => $varView));
    }

    function DIFconstructor($var1, $var2, $tipo) {
        $dif = $var1 - $var2;
        $unidad = '';
        if ($tipo == 'var') {
            $unidad = '%';
            if ($var2 == 0) {
                $var2 = $var1;
            }
            $dif = ($dif * 100) / $var2;
            $dif = round($dif, 2);
        }

        $resp = '<span';
        if ($dif >= 0) {
            $resp .= '>' . $dif . $unidad;
        } else {
            $resp .= ' class="dif">(' . abs($dif) . $unidad . ')';
        }
        $resp .= '</span>';

        return $resp;
    }

    function SQLconstructor($selection, $table, $join, $where, $group = null) {
        $con = Yii::app()->db;
        $sql_cons = "SELECT {$selection} from {$table} {$join}
        WHERE {$where} {$group}";
        //echo $sql_cons.'<br><br>';

        $request_cons = $con->createCommand($sql_cons);
        return $request_cons->queryAll();
    }

    function buscar($cargo_id, $id_responsable, $select_ext, $join_ext, $id_persona, $group_ext, $fecha_inicial_anterior, $fecha_anterior, $fecha_inicial_actual, $fecha_actual, $concesionario = 0, $tipos = null, $carros) {
        $modelos = null;
        $versiones = null;
        if (!empty($carros['modelos'])) {
            $modelos = $carros['modelos'];
        }
        if (!empty($carros['versiones'])) {
            $versiones = $carros['versiones'];
        }

        if (empty($tipos)) {
            $tipos = array();
            array_push($tipos, 1, 2, 3, 4);
        }

        $retorno = array();

        //BUSQUEDA POR TRAFICO      
        $trafico_mes_anterior = $this->SQLconstructor(
                'gi.nombres ' . $select_ext, 'gestion_informacion gi', $join_ext, $id_persona . " AND DATE(gi.fecha) BETWEEN '" . $fecha_inicial_anterior . "' AND '" . $fecha_anterior . "'" . $modelos . $versiones, $group_ext
        );

        $trafico_mes_anterior = count($trafico_mes_anterior);
        $retorno[] = $trafico_mes_anterior;

        $trafico_mes_actual = $this->SQLconstructor(
                'gi.nombres ' . $select_ext, 'gestion_informacion gi', $join_ext, $id_persona . " AND DATE(gi.fecha) BETWEEN '" . $fecha_inicial_actual . "' AND '" . $fecha_actual . "'" . $modelos . $versiones, $group_ext
        );
        $trafico_mes_actual = count($trafico_mes_actual);
        $retorno[] = $trafico_mes_actual;


        $traficockd1 = $this->SQLconstructor(
                'gi.id, gi.nombres, gi.apellidos, gi.responsable, gi.fecha, gi.modelo ' . $select_ext . ' as modinfo, gv.modelo', 'gestion_informacion gi', $join_ext . ' LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id ', "DATE(gi.fecha) BETWEEN '" . $fecha_inicial_anterior . "' AND '" . $fecha_anterior . "' AND " . $id_persona . $modelos . $versiones . " AND ((gv.modelo IN (24,21)) OR gi.modelo IN (24,21))", $group_ext
        );
        $traficockd1 = count($traficockd1);
        $retorno[] = $traficockd1;
        $traficocbu1 = $trafico_mes_anterior - $traficockd1; // resto de modelos
        $retorno[] = $traficocbu1;

        $traficockd2 = $this->SQLconstructor(
                'gi.id, gi.nombres, gi.apellidos, gi.responsable, gi.fecha, gi.modelo ' . $select_ext . ' as modinfo, gv.modelo', 'gestion_informacion gi', $join_ext . ' LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id ', "DATE(gi.fecha) BETWEEN '" . $fecha_inicial_actual . "' AND '" . $fecha_actual . "' AND " . $id_persona . $modelos . $versiones . " AND ((gv.modelo IN (24,21)) OR gi.modelo IN (24,21))", $group_ext
        );
        $traficockd2 = count($traficockd2);
        $retorno[] = $traficockd2;

        $traficocbu2 = $trafico_mes_actual - $traficockd2; // resto de modelos
        $retorno[] = $traficocbu2;


        // BUSQUEDA POR PROFORMA
        $proforma_mes_anterior = $this->SQLconstructor(
                'gf.id_informacion, gf.id_vehiculo, gf.fecha, gi.responsable, gi.dealer_id ' . $select_ext, 'gestion_financiamiento gf', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion ' . $join_ext, "DATE(gf.fecha) BETWEEN '" . $fecha_inicial_anterior . "' AND '" . $fecha_anterior . "' AND " . $id_persona . $modelos . $versiones . " AND gf.order = 1", "GROUP BY gf.id_vehiculo"
        );
        $proforma_mes_anterior = count($proforma_mes_anterior);
        $retorno[] = $proforma_mes_anterior;

        $proforma_mes_actual = $this->SQLconstructor(
                'gf.id_informacion, gf.id_vehiculo, gf.fecha, gi.responsable, gi.dealer_id ' . $select_ext, 'gestion_financiamiento gf', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion ' . $join_ext, "DATE(gf.fecha) BETWEEN '" . $fecha_inicial_actual . "' AND '" . $fecha_actual . "' AND " . $id_persona . $modelos . $versiones . "  AND gf.order = 1", "GROUP BY gf.id_vehiculo"
        );
        $proforma_mes_actual = count($proforma_mes_actual);
        $retorno[] = $proforma_mes_actual;

        $proformackd1 = $this->getSelectCKDCBU($cargo_id, $fecha_inicial_anterior, $fecha_anterior, $id_responsable, 1, $tipos[1], $concesionario); // cerato forte, sportage active
        $retorno[] = $proformackd1;

        $proformacbu1 = $this->getSelectCKDCBU($cargo_id, $fecha_inicial_anterior, $fecha_anterior, $id_responsable, 2, $tipos[1], $concesionario); // resto de modelos
        $retorno[] = $proformacbu1;

        $proformackd2 = $this->getSelectCKDCBU($cargo_id, $fecha_inicial_actual, $fecha_actual, $id_responsable, 1, $tipos[1], $concesionario); // cerato forte, sportage active
        $retorno[] = $proformackd2;

        $proformacbu2 = $this->getSelectCKDCBU($cargo_id, $fecha_inicial_actual, $fecha_actual, $id_responsable, 2, $tipos[1], $concesionario); // resto de modelos
        $retorno[] = $proformacbu2;

        // BUSQUEDA POR TEST DRIVE
        $td_mes_anterior = $this->SQLconstructor(
                'gt.id_informacion, gt.id_vehiculo, gt.test_drive, gt.fecha, gi.responsable, gi.dealer_id', 'gestion_test_drive  gt', 'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion ' . $join_ext, "gt.test_drive = 1 AND DATE(gt.fecha) BETWEEN '" . $fecha_inicial_anterior . "' AND '" . $fecha_anterior . "' " . $modelos . $versiones . " AND gt.order = 1 AND " . $id_persona, "GROUP BY gt.id_vehiculo"
        );
        $td_mes_anterior = count($td_mes_anterior);
        $retorno[] = $td_mes_anterior;

        $td_mes_actual = $this->SQLconstructor(
                'gt.id_informacion, gt.id_vehiculo, gt.test_drive, gt.fecha, gi.responsable, gi.dealer_id', 'gestion_test_drive  gt', 'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion ' . $join_ext, "gt.test_drive = 1 AND DATE(gt.fecha) BETWEEN '" . $fecha_inicial_actual . "' AND '" . $fecha_actual . "' " . $modelos . $versiones . " AND gt.order = 1 AND " . $id_persona, "GROUP BY gt.id_vehiculo"
        );
        $td_mes_actual = count($td_mes_actual);
        $retorno[] = $td_mes_actual;

        $tdckd1 = $this->getSelectCKDCBU($cargo_id, $fecha_inicial_anterior, $fecha_anterior, $id_responsable, 1, $tipos[2], $concesionario); // cerato forte, sportage active
        $retorno[] = $tdckd1;

        $tdcbu1 = $this->getSelectCKDCBU($cargo_id, $fecha_inicial_anterior, $fecha_anterior, $id_responsable, 2, $tipos[2], $concesionario); // resto de modelos
        $retorno[] = $tdcbu1;

        $tdckd2 = $this->getSelectCKDCBU($cargo_id, $fecha_inicial_actual, $fecha_actual, $id_responsable, 1, $tipos[2], $concesionario); // cerato forte, sportage active
        $retorno[] = $tdckd2;

        $tdcbu2 = $this->getSelectCKDCBU($cargo_id, $fecha_inicial_actual, $fecha_actual, $id_responsable, 2, $tipos[2], $concesionario); // resto de modelos
        $retorno[] = $tdcbu2;

        // BUSQUEDA POR VENTAS 
        $vh_mes_anterior = $this->SQLconstructor(
                'gv.id_informacion, gv.modelo, gv.version, gv.fecha, gv.cierre, gi.dealer_id', 'gestion_vehiculo gv', 'INNER JOIN gestion_informacion gi ON gi.id = gv.id_informacion ' . $join_ext, "gv.cierre = 'ACTIVO' AND (DATE(gv.fecha) BETWEEN '" . $fecha_inicial_anterior . "' AND '" . $fecha_anterior . "') AND " . $id_persona . $modelos . $versiones, "GROUP BY gv.id_informacion"
        );
        $vh_mes_anterior = count($vh_mes_anterior);
        $retorno[] = $vh_mes_anterior;

        $vh_mes_actual = $this->SQLconstructor(
                'gv.id_informacion, gv.modelo, gv.version, gv.fecha, gv.cierre, gi.dealer_id', 'gestion_vehiculo gv', 'INNER JOIN gestion_informacion gi ON gi.id = gv.id_informacion ' . $join_ext, "gv.cierre = 'ACTIVO' AND (DATE(gv.fecha) BETWEEN '" . $fecha_inicial_actual . "' AND '" . $fecha_actual . "') AND " . $id_persona . $modelos . $versiones, "GROUP BY gv.id_informacion"
        );
        $vh_mes_actual = count($vh_mes_actual);
        $retorno[] = $vh_mes_actual;

        $vhckd1 = $this->getSelectCKDCBU($cargo_id, $fecha_inicial_anterior, $fecha_anterior, $id_responsable, 1, $tipos[3], $concesionario); // cerato forte, sportage active
        $retorno[] = $vhckd1;

        $vhcbu1 = $this->getSelectCKDCBU($cargo_id, $fecha_inicial_anterior, $fecha_anterior, $id_responsable, 2, $tipos[3], $concesionario); // resto de modelos
        $retorno[] = $vhcbu1;

        $vhckd2 = $this->getSelectCKDCBU($cargo_id, $fecha_inicial_actual, $fecha_actual, $id_responsable, 1, $tipos[3], $concesionario); // cerato forte, sportage active
        $retorno[] = $vhckd2;

        $vhcbu2 = $this->getSelectCKDCBU($cargo_id, $fecha_inicial_actual, $fecha_actual, $id_responsable, 2, $tipos[3], $concesionario); // resto de modelos
        $retorno[] = $vhcbu2;

        return $retorno;
    }

    private function getNombreMes($mes) {
        switch ($mes) {
            case '01':
                $nombre_mes_anterior = 'ENERO';
                break;
            case '02':
                $nombre_mes_anterior = 'FEBRERO';
                break;
            case '03':
                $nombre_mes_anterior = 'MARZO';
                break;
            case '04':
                $nombre_mes_anterior = 'ABRIL';
                break;
            case '05':
                $nombre_mes_anterior = 'MAYO';
                break;
            case '06':
                $nombre_mes_anterior = 'JUNIO';
                break;
            case '07':
                $nombre_mes_anterior = 'JULIO';
                break;
            case '08':
                $nombre_mes_anterior = 'AGOSTO';
                break;
            case '09':
                $nombre_mes_anterior = 'SEPTIEMBRE';
                break;
            case '10':
                $nombre_mes_anterior = 'OCTUBRE';
                break;
            case '11':
                $nombre_mes_anterior = 'NOVIEMBRE';
                break;
            case '12':
                $nombre_mes_anterior = 'DICIEMBRE';
                break;

            default:
                break;
        }
        return $nombre_mes_anterior;
    }

    private function getSelect($cargo_id, $tipo_busqueda, $fecha1, $fecha2, $responsable, $concesionario) {
        $id_responsable = Yii::app()->user->getId();
        $dealer_id = $this->getDealerId($id_responsable);
        $con = Yii::app()->db;
        $grupo_id = (int) Yii::app()->user->getState('grupo_id');

        switch ($cargo_id) {
            case 71: // asesor de ventas
                switch ($tipo_busqueda) {
                    case 1: // trafico
                        $sql = "SELECT nombres, apellidos, responsable, fecha from gestion_informacion 
WHERE responsable = {$id_responsable} AND DATE(fecha) BETWEEN '{$fecha1}' AND '{$fecha2}'";
//die($sql);
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 2: // proformas
                        $sql = "SELECT gf.id_informacion, gf.id_vehiculo, gf.fecha, gi.responsable, gi.dealer_id 
                    FROM gestion_financiamiento gf 
                    INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion 
                    WHERE DATE(gf.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}' AND gi.responsable = {$id_responsable} AND gf.order = 1 GROUP BY gf.id_vehiculo ";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 3: // test drive
                        $sql = "SELECT gt.id_informacion, gt.id_vehiculo, gt.test_drive, gt.fecha FROM gestion_test_drive  gt 
                        INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion
                        WHERE gt.test_drive = 1 AND DATE(gt.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}' AND gt.order = 1 AND gi.responsable = {$id_responsable}
                        GROUP BY gt.id_vehiculo";
                        //die('slq: '.$sql);
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 4: // ventas
                        $sql = "SELECT gv.id_informacion, gv.modelo, gv.version, gv.fecha, gv.cierre FROM gestion_vehiculo gv 
INNER JOIN gestion_informacion gi ON gi.id = gv.id_informacion
WHERE gv.cierre = 'ACTIVO' AND (DATE(gv.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') AND gi.responsable = {$id_responsable}
GROUP BY gv.id_informacion";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;

                    default:
                        break;
                }
                break;
            case 70: // jefe de sucursal
                switch ($tipo_busqueda) {
                    case 1: // trafico
                        $sql = "SELECT nombres, apellidos, responsable, fecha from gestion_informacion 
WHERE dealer_id = {$dealer_id} AND DATE(fecha) BETWEEN '{$fecha1}' AND '{$fecha2}'";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 2: // proformas
                        $sql = "SELECT gf.id_informacion, gf.id_vehiculo, gf.fecha, gi.responsable, gi.dealer_id FROM gestion_financiamiento gf 
INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion
WHERE DATE(gf.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}' AND gi.dealer_id = {$dealer_id}  AND gf.order = 1 
GROUP BY gf.id_vehiculo ";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;

                        break;
                    case 3: // test drive
                        $sql = "SELECT gt.id_informacion, gt.id_vehiculo, gt.test_drive, gt.fecha, gi.responsable, gi.dealer_id FROM gestion_test_drive  gt 
INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion
WHERE gt.test_drive = 1 AND (DATE(gt.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}')  AND gt.order = 1 AND gi.dealer_id = {$dealer_id}
GROUP BY gt.id_vehiculo";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;

                        break;
                    case 4: // ventas
                        $sql = "SELECT gv.id_informacion, gv.modelo, gv.version, gv.fecha, gv.cierre, gi.dealer_id FROM gestion_vehiculo gv 
INNER JOIN gestion_informacion gi ON gi.id = gv.id_informacion
WHERE gv.cierre = 'ACTIVO' AND (DATE(gv.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') AND gi.dealer_id = {$dealer_id}
GROUP BY gv.id_informacion";
//die('sql: '.$sql);
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 5: // trafico con fecha y responsable
                        $sql = "SELECT nombres, apellidos, responsable, fecha from gestion_informacion 
WHERE dealer_id = {$dealer_id} AND DATE(fecha) BETWEEN '{$fecha1}' AND '{$fecha2}' AND responsable = {$responsable}";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 6: // proforma con fecha y responsable
                        $sql = "SELECT gf.id_informacion, gf.id_vehiculo, gf.fecha, gi.responsable, gi.dealer_id FROM gestion_financiamiento gf 
INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion
WHERE DATE(gf.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}' AND gi.responsable = {$responsable} AND gf.order = 1 
GROUP BY gf.id_vehiculo ";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 7: // test drive con fecha y responsable
                        $sql = "SELECT gt.id_informacion, gt.id_vehiculo, gt.test_drive, gt.fecha, gi.responsable, gi.dealer_id FROM gestion_test_drive  gt 
INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion
WHERE gt.test_drive = 1 AND (DATE(gt.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}')  AND gt.order = 1 AND gi.responsable = {$responsable}
GROUP BY gt.id_vehiculo";
//die('sql: '.$sql);
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 8: // ventas con fecha y responsable
                        $sql = "SELECT gv.id_informacion, gv.modelo, gv.version, gv.fecha, gv.cierre, gi.dealer_id FROM gestion_vehiculo gv 
INNER JOIN gestion_informacion gi ON gi.id = gv.id_informacion
WHERE gv.cierre = 'ACTIVO' AND (DATE(gv.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') AND gi.responsable = {$responsable}
GROUP BY gv.id_informacion";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                }

                break;
            case 69: // gerente comercial
                switch ($tipo_busqueda) {
                    case 1: // trafico
                        $sql = "SELECT nombres, apellidos, responsable, fecha from gestion_informacion 
WHERE dealer_id = {$dealer_id} AND DATE(fecha) BETWEEN '{$fecha1}' AND '{$fecha2}'";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 2: // proformas
                        $sql = "SELECT gf.id_informacion, gf.id_vehiculo, gf.fecha, gi.responsable, gi.dealer_id FROM gestion_financiamiento gf 
INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion
WHERE DATE(gf.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}' AND gi.dealer_id = {$dealer_id} AND gf.order = 1 
GROUP BY gf.id_vehiculo ";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;

                        break;
                    case 3: // test drive
                        $sql = "SELECT gt.id_informacion, gt.id_vehiculo, gt.test_drive, gt.fecha, gi.responsable, gi.dealer_id FROM gestion_test_drive  gt 
INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion
WHERE gt.test_drive = 1 AND (DATE(gt.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}')  AND gt.order = 1 AND gi.dealer_id = {$dealer_id}
GROUP BY gt.id_vehiculo";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;

                        break;
                    case 4: // ventas
                        $sql = "SELECT gv.id_informacion, gv.modelo, gv.version, gv.fecha, gv.cierre, gi.dealer_id FROM gestion_vehiculo gv 
INNER JOIN gestion_informacion gi ON gi.id = gv.id_informacion
WHERE gv.cierre = 'ACTIVO' AND (DATE(gv.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') AND gi.dealer_id = {$dealer_id}
GROUP BY gv.id_informacion";
//die('sql: '.$sql);
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 5: // trafico con fecha y responsable
                        //die($concesionario);
                        $sql = "SELECT nombres, apellidos, responsable, fecha from gestion_informacion 
WHERE dealer_id = {$concesionario} AND DATE(fecha) BETWEEN '{$fecha1}' AND '{$fecha2}' AND responsable = {$responsable}";
//die($sql);
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 6: // proforma con fecha y responsable
                        $sql = "SELECT gf.id_informacion, gf.id_vehiculo, gf.fecha, gi.responsable, gi.dealer_id FROM gestion_financiamiento gf 
INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion
WHERE DATE(gf.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}' AND gi.responsable = {$responsable} AND gf.order = 1 
GROUP BY gf.id_vehiculo ";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 7: // test drive con fecha y responsable
                        $sql = "SELECT gt.id_informacion, gt.id_vehiculo, gt.test_drive, gt.fecha, gi.responsable, gi.dealer_id FROM gestion_test_drive  gt 
INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion
WHERE gt.test_drive = 1 AND (DATE(gt.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}')  AND gt.order = 1 AND gi.responsable = {$responsable}
GROUP BY gt.id_vehiculo";
//die('sql: '.$sql);
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 8: // ventas con fecha y responsable
                        $sql = "SELECT gv.id_informacion, gv.modelo, gv.version, gv.fecha, gv.cierre, gi.dealer_id FROM gestion_vehiculo gv 
INNER JOIN gestion_informacion gi ON gi.id = gv.id_informacion
WHERE gv.cierre = 'ACTIVO' AND (DATE(gv.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') AND gi.responsable = {$responsable}
GROUP BY gv.id_informacion";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 9: // trafico con fecha y concesionario
                        if ($concesionario == 0) {
                            $grupo_id = (int) Yii::app()->user->getState('grupo_id');
                            $sql = "SELECT gi.nombres, gi.apellidos, gi.responsable, gi.fecha, u.grupo_id from gestion_informacion gi 
                    INNER JOIN usuarios u ON u.id = gi.responsable 
                    WHERE u.grupo_id = {$grupo_id} AND DATE(gi.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}'";
                        } else {
                            $sql = "SELECT gi.nombres, gi.apellidos, gi.responsable, gi.fecha from gestion_informacion gi
                                INNER JOIN usuarios u ON u.id = gi.responsable 
WHERE gi.dealer_id = {$grupo_id} AND DATE(gi.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}'";
                        }
                        //die($sql);
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 10: // proforma con fecha y concesionario
                        if ($concesionario == 0) {
                            $grupo_id = (int) Yii::app()->user->getState('grupo_id');
                            $sql = "SELECT gf.id_informacion, gf.id_vehiculo, gf.fecha, gi.responsable, gi.dealer_id, u.grupo_id FROM gestion_financiamiento gf 
                        INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion 
                        INNER JOIN usuarios u ON u.id = gi.responsable
                    WHERE DATE(gf.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}' AND u.grupo_id = {$grupo_id} AND gf.order = 1 GROUP BY gf.id_vehiculo ";
                        } else {
                            $sql = "SELECT gf.id_informacion, gf.id_vehiculo, gf.fecha, gi.responsable, gi.dealer_id FROM gestion_financiamiento gf 
INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion
WHERE DATE(gf.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}' AND gi.dealer_id = {$concesionario} AND gf.order = 1 
GROUP BY gf.id_vehiculo ";
                        }
                        //die($sql);
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 11: // test drive con fecha y concesionario
                        if ($concesionario == 0) {
                            $grupo_id = (int) Yii::app()->user->getState('grupo_id');
                            $sql = "SELECT gt.id_informacion, gt.id_vehiculo, gt.test_drive, gt.fecha, gi.responsable, gi.dealer_id FROM gestion_test_drive  gt 
                    INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion 
                    INNER JOIN usuarios u ON u.id = gi.responsable
                    WHERE gt.test_drive = 1 AND DATE(gt.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}'  AND gt.order = 1 AND u.grupo_id = {$grupo_id}
                    GROUP BY gt.id_vehiculo";
                        } else {
                            $sql = "SELECT gt.id_informacion, gt.id_vehiculo, gt.test_drive, gt.fecha, gi.responsable, gi.dealer_id FROM gestion_test_drive  gt 
INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion
WHERE gt.test_drive = 1 AND (DATE(gt.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}')   AND gt.order = 1 AND gi.dealer_id = {$concesionario}
GROUP BY gt.id_vehiculo";
                        }

//die('sql: '.$sql);
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 12: // ventas con fecha y concesionario
                        if ($concesionario == 0) {
                            $grupo_id = (int) Yii::app()->user->getState('grupo_id');
                            $sql = "SELECT gv.id_informacion, gv.modelo, gv.version, gv.fecha, gv.cierre, gi.dealer_id FROM gestion_vehiculo gv 
                    INNER JOIN gestion_informacion gi ON gi.id = gv.id_informacion 
                    INNER JOIN usuarios u ON u.id = gi.responsable
                    WHERE gv.cierre = 'ACTIVO' AND (DATE(gv.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') AND u.grupo_id = {$grupo_id}
                    GROUP BY gv.id_informacion";
                        } else {
                            $sql = "SELECT gv.id_informacion, gv.modelo, gv.version, gv.fecha, gv.cierre, gi.dealer_id FROM gestion_vehiculo gv 
INNER JOIN gestion_informacion gi ON gi.id = gv.id_informacion
WHERE gv.cierre = 'ACTIVO' AND (DATE(gv.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}')  AND gi.dealer_id = {$concesionario}
GROUP BY gv.id_informacion";
                        }

                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                }

                break;

            default:
                break;
        }
    }

    private function getSelectCKDCBU($cargo_id, $fecha1, $fecha2, $id_responsable, $ckdcku, $tipo_busqueda, $concesionario) {
        $responsable = Yii::app()->user->getId();
        if ($cargo_id != 46)
            $dealer_id = $this->getDealerId($responsable);
        $grupo_id = (int) Yii::app()->user->getState('grupo_id');
        $con = Yii::app()->db;
        switch ($cargo_id) {
            case 71: // asesor de ventas
                switch ($tipo_busqueda) {

                    case 1: // TRAFICO
                        $sql = "SELECT gi.id, gi.nombres, gi.apellidos, gi.responsable, gi.fecha, gi.modelo as modinfo, gv.modelo
                from gestion_informacion gi 
                LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id 
                WHERE gi.responsable = {$responsable} AND (DATE(gi.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') 
                AND ((gv.modelo IN (24,21)) OR gi.modelo IN (24,21)) ";
                        //die($sql);
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 2: // PROFORMA
                        $sql = "
                SELECT gf.id_informacion, gf.id_vehiculo, gf.fecha, gi.responsable, gi.dealer_id, gv.modelo
                FROM gestion_financiamiento gf 
                INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion 
                INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo ";
                        if ($ckdcku == 1) {// cerato forte, sportage active
                            $sql .= "WHERE gv.modelo IN (24, 21) ";
                        } elseif ($ckdcku == 2) {// resto de modelos
                            $sql .= "WHERE gv.modelo NOT IN (24, 21) ";
                        }
                        $sql .= "AND  (DATE(gf.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') 
                AND gi.responsable = {$responsable} AND gf.order = 1 
                GROUP BY gf.id_vehiculo";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 3: // TEST DRIVE
                        $sql = "SELECT gt.id_informacion, gt.id_vehiculo, gt.test_drive, gt.fecha, gv.modelo
                FROM gestion_test_drive gt 
                INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion 
                INNER JOIN gestion_vehiculo gv on gv.id = gt.id_vehiculo ";
                        if ($ckdcku == 1) {// cerato forte, sportage active
                            $sql .= "WHERE gv.modelo IN (24, 21) ";
                        } elseif ($ckdcku == 2) {// resto de modelos
                            $sql .= "WHERE gv.modelo NOT IN (24, 21) ";
                        }
                        $sql .= "AND gt.test_drive = 1 AND (DATE(gt.fecha) BETWEEN '{$fecha1}' AND '$fecha2') 
                AND gt.order = 1 AND gi.responsable = {$responsable} 
                GROUP BY gt.id_vehiculo";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 4: // VENTAS
                        $sql = "SELECT gv.id_informacion, gv.modelo, gv.version, gv.fecha, gv.cierre 
                FROM gestion_vehiculo gv 
                INNER JOIN gestion_informacion gi ON gi.id = gv.id_informacion 
                WHERE gv.cierre = 'ACTIVO' ";
                        if ($ckdcku == 1) {// cerato forte, sportage active
                            $sql .= "AND gv.modelo IN (24, 21) ";
                        } elseif ($ckdcku == 2) {// resto de modelos
                            $sql .= "AND gv.modelo NOT IN (24, 21) ";
                        }
                        $sql .= "AND (DATE(gv.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') 
                AND gi.responsable = {$responsable}
                GROUP BY gv.id_informacion";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;

                    default:
                        break;
                }
                break;
            case 70: //jefe del almacen
                $responsable_dealer_id = $this->getDealerId($responsable);
                switch ($tipo_busqueda) {
                    case 1: // TRAFICO
                        $sql = "SELECT gi.id, gi.nombres, gi.apellidos, gi.responsable, gi.fecha, gi.modelo as modinfo, gv.modelo
                from gestion_informacion gi 
                LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id 
                WHERE gi.dealer_id = {$responsable_dealer_id} AND (DATE(gi.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') 
                AND ((gv.modelo IN (24,21)) OR gi.modelo IN (24,21)) ";
                        //die('sql jefe almacen: '.$sql);
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 2: // PROFORMAS
                        $sql = "
                SELECT gf.id_informacion, gf.id_vehiculo, gf.fecha, gi.responsable, gi.dealer_id, gv.modelo
                FROM gestion_financiamiento gf 
                INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion 
                INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo ";
                        if ($ckdcku == 1) {// cerato forte, sportage active
                            $sql .= "WHERE gv.modelo IN (24, 21) ";
                        } elseif ($ckdcku == 2) {// resto de modelos
                            $sql .= "WHERE gv.modelo NOT IN (24, 21) ";
                        }
                        $sql .= "AND  (DATE(gf.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') 
                AND gi.dealer_id = {$responsable_dealer_id} AND gf.order = 1 
                GROUP BY gf.id_vehiculo";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 3: // TEST DRIVE
                        $sql = "SELECT gt.id_informacion, gt.id_vehiculo, gt.test_drive, gt.fecha, gv.modelo
                FROM gestion_test_drive gt 
                INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion 
                INNER JOIN gestion_vehiculo gv on gv.id = gt.id_vehiculo ";
                        if ($ckdcku == 1) {// cerato forte, sportage active
                            $sql .= "WHERE gv.modelo IN (24, 21) ";
                        } elseif ($ckdcku == 2) {// resto de modelos
                            $sql .= "WHERE gv.modelo NOT IN (24, 21) ";
                        }
                        $sql .= "AND gt.test_drive = 1 AND (DATE(gt.fecha) BETWEEN '{$fecha1}' AND '$fecha2') 
                AND gt.order = 1 AND gi.dealer_id = {$responsable_dealer_id} 
                GROUP BY gt.id_vehiculo";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 4: // VENTAS
                        $sql = "SELECT gv.id_informacion, gv.modelo, gv.version, gv.fecha, gv.cierre 
                FROM gestion_vehiculo gv 
                INNER JOIN gestion_informacion gi ON gi.id = gv.id_informacion 
                WHERE gv.cierre = 'ACTIVO' ";
                        if ($ckdcku == 1) {// cerato forte, sportage active
                            $sql .= "AND gv.modelo IN (24, 21) ";
                        } elseif ($ckdcku == 2) {// resto de modelos
                            $sql .= "AND gv.modelo NOT IN (24, 21) ";
                        }
                        $sql .= "AND (DATE(gv.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') 
                AND gi.dealer_id = {$responsable_dealer_id}
                GROUP BY gv.id_informacion";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 5: // trafico con fecha y responsable
                        $sql = "SELECT gi.id, gi.nombres, gi.apellidos, gi.responsable, gi.fecha, gi.modelo as modinfo, gv.modelo
                from gestion_informacion gi 
                LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id 
                WHERE gi.responsable = {$id_responsable} AND (DATE(gi.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') 
                AND ((gv.modelo IN (24,21)) OR gi.modelo IN (24,21)) ";
                        //die('sql trafico jefe allamcen: '.$sql);
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 6: // proforma con fecha y responsable
                        $sql = "
                SELECT gf.id_informacion, gf.id_vehiculo, gf.fecha, gi.responsable, gi.dealer_id, gv.modelo
                FROM gestion_financiamiento gf 
                INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion 
                INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo ";
                        if ($ckdcku == 1) {// cerato forte, sportage active
                            $sql .= "WHERE gv.modelo IN (24, 21) ";
                        } elseif ($ckdcku == 2) {// resto de modelos
                            $sql .= "WHERE gv.modelo NOT IN (24, 21) ";
                        }
                        $sql .= "AND  (DATE(gf.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') 
                AND gi.responsable = {$id_responsable} AND gf.order = 1 
                GROUP BY gf.id_vehiculo";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 7: // test drive con fecha y responsable
                        $sql = "SELECT gt.id_informacion, gt.id_vehiculo, gt.test_drive, gt.fecha, gv.modelo
                FROM gestion_test_drive gt 
                INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion 
                INNER JOIN gestion_vehiculo gv on gv.id = gt.id_vehiculo ";
                        if ($ckdcku == 1) {// cerato forte, sportage active
                            $sql .= "WHERE gv.modelo IN (24, 21) ";
                        } elseif ($ckdcku == 2) {// resto de modelos
                            $sql .= "WHERE gv.modelo NOT IN (24, 21) ";
                        }
                        $sql .= "AND gt.test_drive = 1 AND (DATE(gt.fecha) BETWEEN '{$fecha1}' AND '$fecha2') 
                AND gt.order = 1 AND gi.responsable = {$id_responsable} 
                GROUP BY gt.id_vehiculo";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 8: // ventas con fecha y responsable
                        $sql = "SELECT gv.id_informacion, gv.modelo, gv.version, gv.fecha, gv.cierre 
                FROM gestion_vehiculo gv 
                INNER JOIN gestion_informacion gi ON gi.id = gv.id_informacion 
                WHERE gv.cierre = 'ACTIVO' ";
                        if ($ckdcku == 1) {// cerato forte, sportage active
                            $sql .= "AND gv.modelo IN (24, 21) ";
                        } elseif ($ckdcku == 2) {// resto de modelos
                            $sql .= "AND gv.modelo NOT IN (24, 21) ";
                        }
                        $sql .= "AND (DATE(gv.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') 
                AND gi.responsable = {$id_responsable}
                GROUP BY gv.id_informacion";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    default:
                        break;
                }
                break;
            case 69: // gerente comercial
                $grupo_id = (int) Yii::app()->user->getState('grupo_id');
                switch ($tipo_busqueda) {
                    case 1: // TRAFICO
                        $sql = "SELECT gi.id, gi.nombres, gi.apellidos, gi.responsable, gi.fecha, gi.modelo as modinfo, gv.modelo
                from gestion_informacion gi 
                INNER JOIN usuarios u ON u.id = gi.responsable 
                LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id 
                WHERE u.grupo_id = {$grupo_id} AND (DATE(gi.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') 
                AND ((gv.modelo IN (24,21)) OR gi.modelo IN (24,21)) ";
                        //die('sql jefe almacen: '.$sql);
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 2: // PROFORMAS
                        $sql = "
                SELECT gf.id_informacion, gf.id_vehiculo, gf.fecha, gi.responsable, gi.dealer_id, gv.modelo
                FROM gestion_financiamiento gf 
                INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion 
                INNER JOIN usuarios u ON u.id = gi.responsable 
                INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo ";
                        if ($ckdcku == 1) {// cerato forte, sportage active
                            $sql .= "WHERE gv.modelo IN (24, 21) ";
                        } elseif ($ckdcku == 2) {// resto de modelos
                            $sql .= "WHERE gv.modelo NOT IN (24, 21) ";
                        }
                        $sql .= "AND  (DATE(gf.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') 
                AND u.grupo_id = {$grupo_id} AND gf.order = 1 
                GROUP BY gf.id_vehiculo";
                        //die($sql);
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 3: // TEST DRIVE
                        $sql = "SELECT gt.id_informacion, gt.id_vehiculo, gt.test_drive, gt.fecha, gv.modelo
                FROM gestion_test_drive gt 
                INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion 
                INNER JOIN usuarios u ON u.id = gi.responsable 
                INNER JOIN gestion_vehiculo gv on gv.id = gt.id_vehiculo ";
                        if ($ckdcku == 1) {// cerato forte, sportage active
                            $sql .= "WHERE gv.modelo IN (24, 21) ";
                        } elseif ($ckdcku == 2) {// resto de modelos
                            $sql .= "WHERE gv.modelo NOT IN (24, 21) ";
                        }
                        $sql .= "AND gt.test_drive = 1 AND (DATE(gt.fecha) BETWEEN '{$fecha1}' AND '$fecha2') 
                AND gt.order = 1 AND u.grupo_id = {$grupo_id} 
                GROUP BY gt.id_vehiculo";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 4: // VENTAS
                        $sql = "SELECT gv.id_informacion, gv.modelo, gv.version, gv.fecha, gv.cierre 
                FROM gestion_vehiculo gv 
                INNER JOIN gestion_informacion gi ON gi.id = gv.id_informacion 
                INNER JOIN usuarios u ON u.id = gi.responsable 
                WHERE gv.cierre = 'ACTIVO' ";
                        if ($ckdcku == 1) {// cerato forte, sportage active
                            $sql .= "AND gv.modelo IN (24, 21) ";
                        } elseif ($ckdcku == 2) {// resto de modelos
                            $sql .= "AND gv.modelo NOT IN (24, 21) ";
                        }
                        $sql .= "AND (DATE(gv.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') 
                AND u.grupo_id = {$grupo_id} 
                GROUP BY gv.id_informacion";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 5: // trafico por responsable
                        $sql = "SELECT gi.id, gi.nombres, gi.apellidos, gi.responsable, gi.fecha, gi.modelo as modinfo, gv.modelo
                from gestion_informacion gi 
                LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id 
                WHERE gi.responsable = {$id_responsable} AND (DATE(gi.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') 
                AND ((gv.modelo IN (24,21)) OR gi.modelo IN (24,21)) ";
                        //die('sql jefe almacen: '.$sql);
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 6: // proforma por responsable
                        $sql = "
                SELECT gf.id_informacion, gf.id_vehiculo, gf.fecha, gi.responsable, gi.dealer_id, gv.modelo
                FROM gestion_financiamiento gf 
                INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion 
                INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo ";
                        if ($ckdcku == 1) {// cerato forte, sportage active
                            $sql .= "WHERE gv.modelo IN (24, 21) ";
                        } elseif ($ckdcku == 2) {// resto de modelos
                            $sql .= "WHERE gv.modelo NOT IN (24, 21) ";
                        }
                        $sql .= "AND  (DATE(gf.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') 
                AND gi.responsable = {$id_responsable} AND gf.order = 1 
                GROUP BY gf.id_vehiculo";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 7: // test drive por responsable
                        $sql = "SELECT gt.id_informacion, gt.id_vehiculo, gt.test_drive, gt.fecha, gv.modelo
                FROM gestion_test_drive gt 
                INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion 
                INNER JOIN gestion_vehiculo gv on gv.id = gt.id_vehiculo ";
                        if ($ckdcku == 1) {// cerato forte, sportage active
                            $sql .= "WHERE gv.modelo IN (24, 21) ";
                        } elseif ($ckdcku == 2) {// resto de modelos
                            $sql .= "WHERE gv.modelo NOT IN (24, 21) ";
                        }
                        $sql .= "AND gt.test_drive = 1 AND (DATE(gt.fecha) BETWEEN '{$fecha1}' AND '$fecha2') 
                AND gt.order = 1 AND gi.responsable = {$id_responsable} 
                GROUP BY gt.id_vehiculo";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 8:
                        $sql = "SELECT gv.id_informacion, gv.modelo, gv.version, gv.fecha, gv.cierre 
                FROM gestion_vehiculo gv 
                INNER JOIN gestion_informacion gi ON gi.id = gv.id_informacion 
                WHERE gv.cierre = 'ACTIVO' ";
                        if ($ckdcku == 1) {// cerato forte, sportage active
                            $sql .= "AND gv.modelo IN (24, 21) ";
                        } elseif ($ckdcku == 2) {// resto de modelos
                            $sql .= "AND gv.modelo NOT IN (24, 21) ";
                        }
                        $sql .= "AND (DATE(gv.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') 
                AND gi.responsable = {$id_responsable}
                GROUP BY gv.id_informacion";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 9: // trafico para concesionario
                        $sql = "SELECT gi.id, gi.nombres, gi.apellidos, gi.responsable, gi.fecha, gi.modelo as modinfo, gv.modelo
                from gestion_informacion gi 
                LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id 
                WHERE gi.dealer_id = {$concesionario} AND (DATE(gi.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') 
                AND ((gv.modelo IN (24,21)) OR gi.modelo IN (24,21)) ";
                        //die('sql gerente comercial: '.$sql);
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                        break;
                    case 10: // busqueda proforma por concesionario
                        $sql = "
                SELECT gf.id_informacion, gf.id_vehiculo, gf.fecha, gi.responsable, gi.dealer_id, gv.modelo
                FROM gestion_financiamiento gf 
                INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion 
                INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo ";
                        if ($ckdcku == 1) {// cerato forte, sportage active
                            $sql .= "WHERE gv.modelo IN (24, 21) ";
                        } elseif ($ckdcku == 2) {// resto de modelos
                            $sql .= "WHERE gv.modelo NOT IN (24, 21) ";
                        }
                        $sql .= "AND  (DATE(gf.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') 
                AND gi.dealer_id = {$concesionario} AND gf.order = 1 
                GROUP BY gf.id_vehiculo";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 11: // busqueda test drive por concesionario
                        $sql = "SELECT gt.id_informacion, gt.id_vehiculo, gt.test_drive, gt.fecha, gv.modelo
                FROM gestion_test_drive gt 
                INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion
                INNER JOIN gestion_vehiculo gv on gv.id = gt.id_vehiculo ";
                        if ($ckdcku == 1) {// cerato forte, sportage active
                            $sql .= "WHERE gv.modelo IN (24, 21) ";
                        } elseif ($ckdcku == 2) {// resto de modelos
                            $sql .= "WHERE gv.modelo NOT IN (24, 21) ";
                        }
                        $sql .= "AND gt.test_drive = 1 AND (DATE(gt.fecha) BETWEEN '{$fecha1}' AND '$fecha2') 
                AND gt.order = 1 AND gi.dealer_id = {$concesionario} 
                GROUP BY gt.id_vehiculo";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 12: // busqueda ventas por concesionario
                        $sql = "SELECT gv.id_informacion, gv.modelo, gv.version, gv.fecha, gv.cierre 
                FROM gestion_vehiculo gv 
                INNER JOIN gestion_informacion gi ON gi.id = gv.id_informacion 
                WHERE gv.cierre = 'ACTIVO' ";
                        if ($ckdcku == 1) {// cerato forte, sportage active
                            $sql .= "AND gv.modelo IN (24, 21) ";
                        } elseif ($ckdcku == 2) {// resto de modelos
                            $sql .= "AND gv.modelo NOT IN (24, 21) ";
                        }
                        $sql .= "AND (DATE(gv.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') 
                AND gi.dealer_id = {$concesionario} 
                GROUP BY gv.id_informacion";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 13: // trafico busqueda gerente comercial
                        $sql = "SELECT gi.id, gi.nombres, gi.apellidos, gi.responsable, gi.fecha, gi.modelo as modinfo, gv.modelo
                from gestion_informacion gi 
                INNER JOIN usuarios u ON u.id = gi.responsable 
                LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id 
                WHERE u.grupo_id = {$grupo_id} AND (DATE(gi.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') 
                AND ((gv.modelo IN (24,21)) OR gi.modelo IN (24,21)) ";
                        //die($sql);
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 14: // proforma gerente comercial
                        $sql = "
                SELECT gf.id_informacion, gf.id_vehiculo, gf.fecha, gi.responsable, gi.dealer_id, gv.modelo
                FROM gestion_financiamiento gf 
                INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion 
                INNER JOIN usuarios u ON u.id = gi.responsable 
                INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo ";
                        if ($ckdcku == 1) {// cerato forte, sportage active
                            $sql .= "WHERE gv.modelo IN (24, 21) ";
                        } elseif ($ckdcku == 2) {// resto de modelos
                            $sql .= "WHERE gv.modelo NOT IN (24, 21) ";
                        }
                        $sql .= "AND  (DATE(gf.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') 
                AND u.grupo_id = {$grupo_id} AND gf.order = 1 
                GROUP BY gf.id_vehiculo";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        //die($sql);
                        return $result;
                        break;
                    case 15: // test drive gerente comercial
                        $sql = "SELECT gt.id_informacion, gt.id_vehiculo, gt.test_drive, gt.fecha, gv.modelo
                FROM gestion_test_drive gt 
                INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion 
                INNER JOIN usuarios u ON u.id = gi.responsable 
                INNER JOIN gestion_vehiculo gv on gv.id = gt.id_vehiculo ";
                        if ($ckdcku == 1) {// cerato forte, sportage active
                            $sql .= "WHERE gv.modelo IN (24, 21) ";
                        } elseif ($ckdcku == 2) {// resto de modelos
                            $sql .= "WHERE gv.modelo NOT IN (24, 21) ";
                        }
                        $sql .= "AND gt.test_drive = 1 AND (DATE(gt.fecha) BETWEEN '{$fecha1}' AND '$fecha2') 
                AND gt.order = 1 AND u.grupo_id = {$grupo_id} 
                GROUP BY gt.id_vehiculo";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 16: // ventas gerente comercial
                        $sql = "SELECT gv.id_informacion, gv.modelo, gv.version, gv.fecha, gv.cierre 
                FROM gestion_vehiculo gv 
                INNER JOIN gestion_informacion gi ON gi.id = gv.id_informacion 
                INNER JOIN usuarios u ON u.id = gi.responsable 
                WHERE gv.cierre = 'ACTIVO' ";
                        if ($ckdcku == 1) {// cerato forte, sportage active
                            $sql .= "AND gv.modelo IN (24, 21) ";
                        } elseif ($ckdcku == 2) {// resto de modelos
                            $sql .= "AND gv.modelo NOT IN (24, 21) ";
                        }
                        $sql .= "AND (DATE(gv.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') 
                AND u.grupo_id = {$grupo_id} 
                GROUP BY gv.id_informacion";
                        //die($sql);
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    default:
                        break;
                }
                break;
        }
    }

    /**
     * Function that change to new id responsable for selected data
     * return true or false
     */
    public function actionSetAsignamiento() {
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        $comentario = isset($_POST["comentario"]) ? $_POST["comentario"] : "";
        $checkboxvalues = isset($_POST["checkboxvalues"]) ? $_POST["checkboxvalues"] : "";
        $con = Yii::app()->db;
        $result = TRUE;
        date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador

        $model = new GestionReasignamiento;
        $model->comentario = $comentario;
        $model->fecha = date("Y-m-d H:i:s");
        $model->save();

        foreach ($checkboxvalues as $value) {
            $param = explode(',', $value);
            $sql = "UPDATE gestion_informacion SET responsable = {$id}, reasignado = 1, responsable_cesado = {$param[1]}, id_comentario = {$model->id} WHERE id = {$param[0]}";
            if (!$request = $con->createCommand($sql)->execute()) {
                $result = FALSE;
            }
            $sqlNot = "UPDATE gestion_notificaciones SET id_asesor = {$id} WHERE id_informacion = {$param[0]}";
            $request2 = $con->createCommand($sqlNot)->execute();

            $sqlSC = "UPDATE gestion_solicitud_credito SET vendedor = {$id} where id_informacion = {$param[0]}";
            $request3 = $con->createCommand($sqlSC)->execute();
        }
        $options = array('result' => $result);
        echo json_encode($options);
    }

    public function actionGetresponsable() {
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        $responsableid = Usuarios::model()->findByPk($id);
        if (!is_null($responsableid) && !empty($responsableid)) {
            echo 'Asignamiento a: ' . $responsableid->nombres . ' ' . $responsableid->apellido;
        } else {
            echo '';
        }
    }

    

}
