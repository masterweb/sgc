<?php

class GestionNuevaCotizacionController extends Controller {

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
    public function actionCreate() {
        $model = new GestionNuevaCotizacion;

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
            //die('fuente: '.$_POST['GestionNuevaCotizacion']['fuente']);
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
                            if (($_POST['GestionNuevaCotizacion']['tipo'] == 'Exonerado Taxi') ||
                                    ($_POST['GestionNuevaCotizacion']['tipo'] == 'Exonerado Conadis') ||
                                    ($_POST['GestionNuevaCotizacion']['tipo'] == 'Exonerado Diplomatico')
                            ) {
                                $model->setscenario('prospeccion');
                                $tipo = 'exonerado';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/exonerados', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'exonerado', 'iden' => $ident));
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
                            if (($_POST['GestionNuevaCotizacion']['tipo'] == 'Exonerado Taxi') ||
                                    ($_POST['GestionNuevaCotizacion']['tipo'] == 'Exonerado Conadis') ||
                                    ($_POST['GestionNuevaCotizacion']['tipo'] == 'Exonerado Diplomatico')
                            ) {
                                $model->setscenario('prospeccion');
                                $tipo = 'exonerado';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/exonerados', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'exonerado', 'iden' => $ident));
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
                            if (($_POST['GestionNuevaCotizacion']['tipo'] == 'Exonerado Taxi') ||
                                    ($_POST['GestionNuevaCotizacion']['tipo'] == 'Exonerado Conadis') ||
                                    ($_POST['GestionNuevaCotizacion']['tipo'] == 'Exonerado Diplomatico')
                            ) {
                                $model->setscenario('prospeccion');
                                $tipo = 'exonerado';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/exonerados', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'exonerado', 'iden' => $ident));
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
                    $model->setscenario('prospeccion');
                    $tipo = 'prospeccion';
                    $documento = $_POST['GestionNuevaCotizacion']['identificacion'];
                    //die('ente pros ' . $documento);
                    switch ($documento) {
                        case 'ci':
                            $ident = 'ci';
                            $model->cedula = $_POST['GestionNuevaCotizacion']['cedula'];
                            break;
                        case 'ruc':
                            $ident = 'ruc';
                            $model->ruc = $_POST['GestionNuevaCotizacion']['ruc'];
                            break;
                        case 'pasaporte':
                            $ident = 'pasaporte';
                            $model->pasaporte = $_POST['GestionNuevaCotizacion']['pasaporte'];
                            break;

                        default:
                            break;
                    }
                    if ($model->save()) {
                        //die('enter save');
                        $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'fuente' => $_POST['GestionNuevaCotizacion']['fuente'], 'iden' => $ident));
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
                        if (($_POST['GestionNuevaCotizacion']['tipo'] == 'Exonerado Taxi') || ($_POST['GestionNuevaCotizacion']['tipo'] == 'Exonerado Conadis') || ($_POST['GestionNuevaCotizacion']['tipo'] == 'Exonerado Diplomatico')) {
                            $this->redirect(array('gestionInformacion/create2', 'tipo' => $tipo, 'id' => $model->id, 'tipo_fuente' => 'exonerados'));
                        }
                        $this->redirect(array('gestionInformacion/create2', 'tipo' => $tipo, 'id' => $model->id,));
                    }


                    break;
                case 'showroom':
                    //die('enter showroom');

                    $model->setscenario('consulta');
                    $tipo = 'gestion';
                    $documento = $_POST['GestionNuevaCotizacion']['identificacion'];
                    //die('documente: '.$documento);
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
                        //if ((count($ced)) > 0) {
                        //die('enter ced');
                        //$this->redirect(array('gestionVehiculo/create', 'id' => $ced->id));
                        //} else {
                        //die('no find cedula');
                        if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Usado') {
                            $model->setscenario('prospeccion');
                            $tipo = 'trafico';
                            if ($model->save()) {
                                //die('enter save');
                                $this->redirect(array('gestionInformacion/usados', 'tipo' => $tipo, 'id' => $model->id, 'tipo_fuente' => 'usado', 'iden' => $ident));
                            }
                        }
                        switch ($_POST['GestionNuevaCotizacion']['tipo']) {
                            case 'Exonerado Taxi':
                                $model->setscenario('prospeccion');
                                $tipo = 'exonerado';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/exonerados', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'exonerado', 'iden' => $ident));
                                }
                                break;
                            case 'Exonerado Conadis':
                                $model->setscenario('prospeccion');
                                $tipo = 'exonerado';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/conadis', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'exonerado', 'iden' => $ident));
                                }
                                break;
                            case 'Exonerado Diplomatico':
                                $model->setscenario('prospeccion');
                                $tipo = 'exonerado';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/diplomaticos', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'exonerado', 'iden' => $ident));
                                }
                                break;
                            default:
                                break;
                        }
                        if ($model->save()) {
                            $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'fuente' => $_POST['GestionNuevaCotizacion']['fuente'], 'iden' => $ident));
                        }
                        //}
                    }

                    if ($_POST['GestionNuevaCotizacion']['ruc'] != '') {
                        $ident = 'ruc';
                        //die('enter empty');
                        $model->ruc = $_POST['GestionNuevaCotizacion']['ruc'];
                        $criteria = new CDbCriteria(array(
                            'condition' => "ruc='{$_POST['GestionNuevaCotizacion']['ruc']}'"
                        ));
                        $ced = GestionInformacion::model()->find($criteria);
                        //if ((count($ced)) > 0) {
                        //die('enter ced');
                        //$this->redirect(array('gestionVehiculo/create', 'id' => $ced->id));
                        //} else {
                        if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Usado') {
                            $model->setscenario('prospeccion');
                            $tipo = 'trafico';
                            if ($model->save()) {
                                //die('enter save');
                                $this->redirect(array('gestionInformacion/usados', 'tipo' => $tipo, 'id' => $model->id, 'tipo_fuente' => 'usado', 'iden' => $ident));
                            }
                        }
                        switch ($_POST['GestionNuevaCotizacion']['tipo']) {
                            case 'Exonerado Taxi':
                                $model->setscenario('prospeccion');
                                $tipo = 'exonerado';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/exonerados', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'exonerado', 'iden' => $ident));
                                }
                                break;
                            case 'Exonerado Conadis':
                                $model->setscenario('prospeccion');
                                $tipo = 'exonerado';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/conadis', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'exonerado', 'iden' => $ident));
                                }
                                break;
                            case 'Exonerado Diplomatico':
                                $model->setscenario('prospeccion');
                                $tipo = 'exonerado';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/diplomaticos', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'exonerado', 'iden' => $ident));
                                }
                                break;
                            default:
                                break;
                        }
                        if ($model->save()) {
                            $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'fuente' => $_POST['GestionNuevaCotizacion']['fuente'], 'iden' => $ident));
                            //      }
                        }
                    }

                    if ($_POST['GestionNuevaCotizacion']['pasaporte'] != '') {
                        //die('enter pasaporte');       
                        $ident = 'pasaporte';
                        //die('enter pasaporte');
                        $model->pasaporte = $_POST['GestionNuevaCotizacion']['pasaporte'];
                        $criteria = new CDbCriteria(array(
                            'condition' => "pasaporte='{$_POST['GestionNuevaCotizacion']['pasaporte']}'"
                        ));
                        $ced = GestionInformacion::model()->find($criteria);
                        //if ((count($ced)) > 0) {
                        //die('enter ced');
                        //$this->redirect(array('gestionVehiculo/create', 'id' => $ced->id));
                        //} else {
                        if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Usado') {
                            $model->setscenario('prospeccion');
                            $tipo = 'trafico';
                            if ($model->save()) {
                                //die('enter save');
                                $this->redirect(array('gestionInformacion/usados', 'tipo' => $tipo, 'id' => $model->id, 'tipo_fuente' => 'usado', 'iden' => $ident));
                            }
                        }
                        switch ($_POST['GestionNuevaCotizacion']['tipo']) {
                            case 'Exonerado Taxi':
                                $model->setscenario('prospeccion');
                                $tipo = 'exonerado';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/exonerados', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'exonerado', 'iden' => $ident));
                                }
                                break;
                            case 'Exonerado Conadis':
                                $model->setscenario('prospeccion');
                                $tipo = 'exonerado';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/conadis', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'exonerado', 'iden' => $ident));
                                }
                                break;
                            case 'Exonerado Diplomatico':
                                $model->setscenario('prospeccion');
                                $tipo = 'exonerado';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/diplomaticos', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'exonerado', 'iden' => $ident));
                                }
                                break;
                            default:
                                break;
                                }
                                if ($model->save()) {
                                    //die('enter save trafico showroom');
                                    $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'fuente' => $_POST['GestionNuevaCotizacion']['fuente'], 'iden' => $ident));
                            //    }
                        }
                    }
                    break;

                case 'exhibicion quierounkia':
                case 'exhibicion quierounkiatd':
                case 'exhibicion_automundo_uio':   
                case 'exhibicion_automundo_gye': 
                    //die('enter showroom');

                    $model->setscenario('consulta');
                    $tipo = 'gestion';
                    $documento = $_POST['GestionNuevaCotizacion']['identificacion'];
                    //die('documente: '.$documento);
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
                        //if ((count($ced)) > 0) {
                        //die('enter ced');
                        //$this->redirect(array('gestionVehiculo/create', 'id' => $ced->id));
                        //} else {
                        //die('no find cedula');
                        if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Usado') {
                            $model->setscenario('prospeccion');
                            $tipo = 'trafico';
                            if ($model->save()) {
                                //die('enter save');
                                $this->redirect(array('gestionInformacion/usados', 'tipo' => $tipo, 'id' => $model->id, 'tipo_fuente' => 'usado', 'iden' => $ident));
                            }
                        }
                        switch ($_POST['GestionNuevaCotizacion']['tipo']) {
                            case 'Exonerado Taxi':
                                $model->setscenario('prospeccion');
                                $tipo = 'exonerado';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/exonerados', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'exonerado', 'iden' => $ident));
                                }
                                break;
                            case 'Exonerado Conadis':
                                $model->setscenario('prospeccion');
                                $tipo = 'exonerado';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/conadis', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'exonerado', 'iden' => $ident));
                                }
                                break;
                            case 'Exonerado Diplomatico':
                                $model->setscenario('prospeccion');
                                $tipo = 'exonerado';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/diplomaticos', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'exonerado', 'iden' => $ident));
                                }
                                break;
                            default:
                                break;
                        }
                        if ($model->save()) {
                            $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'fuente' => $_POST['GestionNuevaCotizacion']['fuente'], 'iden' => $ident));
                        }
                        //}
                    }

                    if ($_POST['GestionNuevaCotizacion']['ruc'] != '') {
                        $ident = 'ruc';
                        //die('enter empty');
                        $model->ruc = $_POST['GestionNuevaCotizacion']['ruc'];
                        $criteria = new CDbCriteria(array(
                            'condition' => "ruc='{$_POST['GestionNuevaCotizacion']['ruc']}'"
                        ));
                        $ced = GestionInformacion::model()->find($criteria);
                        //if ((count($ced)) > 0) {
                        //die('enter ced');
                        //$this->redirect(array('gestionVehiculo/create', 'id' => $ced->id));
                        //} else {
                        if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Usado') {
                            $model->setscenario('prospeccion');
                            $tipo = 'trafico';
                            if ($model->save()) {
                                //die('enter save');
                                $this->redirect(array('gestionInformacion/usados', 'tipo' => $tipo, 'id' => $model->id, 'tipo_fuente' => 'usado', 'iden' => $ident));
                            }
                        }
                        switch ($_POST['GestionNuevaCotizacion']['tipo']) {
                            case 'Exonerado Taxi':
                                $model->setscenario('prospeccion');
                                $tipo = 'exonerado';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/exonerados', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'exonerado', 'iden' => $ident));
                                }
                                break;
                            case 'Exonerado Conadis':
                                $model->setscenario('prospeccion');
                                $tipo = 'exonerado';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/conadis', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'exonerado', 'iden' => $ident));
                                }
                                break;
                            case 'Exonerado Diplomatico':
                                $model->setscenario('prospeccion');
                                $tipo = 'exonerado';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/diplomaticos', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'exonerado', 'iden' => $ident));
                                }
                                break;
                            default:
                                break;
                        }
                        if ($model->save()) {
                            $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'fuente' => $_POST['GestionNuevaCotizacion']['fuente'], 'iden' => $ident));
                            //      }
                        }
                    }

                    if ($_POST['GestionNuevaCotizacion']['pasaporte'] != '') {
                        //die('enter pasaporte');       
                        $ident = 'pasaporte';
                        //die('enter pasaporte');
                        $model->pasaporte = $_POST['GestionNuevaCotizacion']['pasaporte'];
                        $criteria = new CDbCriteria(array(
                            'condition' => "pasaporte='{$_POST['GestionNuevaCotizacion']['pasaporte']}'"
                        ));
                        $ced = GestionInformacion::model()->find($criteria);
                        //if ((count($ced)) > 0) {
                        //die('enter ced');
                        //$this->redirect(array('gestionVehiculo/create', 'id' => $ced->id));
                        //} else {
                        if ($_POST['GestionNuevaCotizacion']['tipo'] == 'Usado') {
                            $model->setscenario('prospeccion');
                            $tipo = 'trafico';
                            if ($model->save()) {
                                //die('enter save');
                                $this->redirect(array('gestionInformacion/usados', 'tipo' => $tipo, 'id' => $model->id, 'tipo_fuente' => 'usado', 'iden' => $ident));
                            }
                        }
                        switch ($_POST['GestionNuevaCotizacion']['tipo']) {
                            case 'Exonerado Taxi':
                                $model->setscenario('prospeccion');
                                $tipo = 'exonerado';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/exonerados', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'exonerado', 'iden' => $ident));
                                }
                                break;
                            case 'Exonerado Conadis':
                                $model->setscenario('prospeccion');
                                $tipo = 'exonerado';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/conadis', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'exonerado', 'iden' => $ident));
                                }
                                break;
                            case 'Exonerado Diplomatico':
                                $model->setscenario('prospeccion');
                                $tipo = 'exonerado';
                                if ($model->save()) {
                                    //die('enter save');
                                    $this->redirect(array('gestionInformacion/diplomaticos', 'tipo' => 'gestion', 'id' => $model->id, 'tipo_fuente' => 'exonerado', 'iden' => $ident));
                                }
                                break;
                            default:
                                break;
                                }
                                if ($model->save()) {
                                    //die('enter save trafico showroom');
                                    $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'fuente' => $_POST['GestionNuevaCotizacion']['fuente'], 'iden' => $ident));
                            //    }
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

        $this->redirect(array('gestionInformacion/seguimiento'));
//        $this->render('create', array(
//            'model' => $model
//        ));
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

        if (isset($_POST['GestionNuevaCotizacion'])) {
            $model->attributes = $_POST['GestionNuevaCotizacion'];
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
        $dataProvider = new CActiveDataProvider('GestionNuevaCotizacion');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new GestionNuevaCotizacion('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['GestionNuevaCotizacion']))
            $model->attributes = $_GET['GestionNuevaCotizacion'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return GestionNuevaCotizacion the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = GestionNuevaCotizacion::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param GestionNuevaCotizacion $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'gestion-nueva-cotizacion-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
