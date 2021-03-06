<?php

class GestionInformacionController extends Controller {

    public $layout = '//layouts/call';

    public function filters() {
        return array(
            'accessControl',
            'postOnly + delete',
        );
    }

    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'seguimiento', 'seguimientobdc', 'seguimientoexonerados',
                    'calendar', 'createAjax', 'create2', 'seguimientoUsados', 'usados', 'exonerados', 'reportes'),
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

    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function actionCreate($tipo = NULL, $id = NULL, $fuente = NULL, $tipo_fuente = NULL) {
        $model = new GestionInformacion;

        if (isset($_POST['GestionInformacion'])) {

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

            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            //die('id responsable: '.Yii::app()->user->getId());
            $model->responsable = Yii::app()->user->getId();
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
            endif;

            if ($_POST['tipo'] == 'prospeccion'):
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
                if ($_POST['tipo'] == 'prospeccion' && $_POST['yt0'] == 'Continuar') {
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

                    $this->redirect(array('gestionInformacion/seguimiento'));
                } else if ($_POST['tipo'] == 'prospeccion' && $_POST['yt0'] == 'Abandonar') {
                    $this->redirect(array('gestionInformacion/seguimiento'));
                }
                if ($_POST['tipo'] == 'gestion') {
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
                $this->redirect(array('gestionConsulta/create', 'id_informacion' => $model->id, 'tipo' => $_POST['tipo'], 'fuente' => $fuente));
            }
        }

        $this->render('create', array(
            'model' => $model, 'tipo' => $tipo, 'id' => $id, 'fuente' => $fuente
        ));
    }


    public function actionCreate2($tipo = NULL, $id = NULL, $fuente = NULL, $tipo_fuente = NULL) {
        $model = new GestionInformacion;

        if (isset($_POST['GestionInformacion'])) {
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
                    }
                } else if ($_POST['GestionInformacion']['tipo'] == 'gestion' || $_POST['GestionInformacion']['tipo'] == 'trafico') {
                    //die('enter gestion update');
                    $this->redirect(array('gestionConsulta/create', 'id_informacion' => $id, 'tipo' => 'gestion', 'fuente' => $_POST['GestionInformacion']['tipo']));
                    //$this->redirect(array('gestionConsulta/update', 'id' => $id));
                }
                //die('gestion: '.$_POST['GestionProspeccionPr']['pregunta']);

                $con = Yii::app()->db;
                $sql = "UPDATE gestion_diaria SET primera_visita = 1, paso = '3' WHERE id_informacion = {$id}";
                $request = $con->createCommand($sql)->query();

                //$this->redirect(array('gestionConsulta/create', 'id_informacion' => $model->id, 'tipo' => 'gestion'));
                $this->redirect(array('gestionInformacion/seguimiento'));
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

    public function actionSeguimiento() {
        //$this->layout = '//layouts/callventas';
        $cargo = Yii::app()->user->getState('usuario');
        $cargo_id = (int) Yii::app()->user->getState('cargo_id');
        //die('cargo id:'.$cargo_id);
        $id_responsable = Yii::app()->user->getId();
        $dealer_id = $this->getDealerId($id_responsable);
        //die($dealer_id);
        $model = new GestionNuevaCotizacion;
        $con = Yii::app()->db;

        if ($cargo_id == 70) {
            //die('enter jefe');
            // SELECT ANTIGUO QUE SE ENLAZABA GON GESTION DIARIA
            $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, 
            gi.ruc,gi.pasaporte,gi.email, gi.responsable as resp,gi.tipo_form_web,gi.fecha, gi.bdc, 
            gd.*, gc.preg7 as categorizacion, gn.fuente 
            FROM gestion_diaria gd 
                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
                INNER JOIN gestion_consulta gc ON gi.id = gc.id_informacion
                INNER JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion 
                WHERE gi.dealer_id = {$this->getConcesionarioDealerId($id_responsable)} AND gd.desiste = 0
                ORDER BY gd.id DESC";
            //die('sql sucursal'. $sql);
        }
        if ($cargo_id == 71) {
            //die('enter code 71');
            // SELECT ANTIGUO QUE SE ENLAZABA GON GESTION DIARIA
            $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, 
            gi.ruc,gi.pasaporte,gi.email, gi.responsable as resp,gi.tipo_form_web,gi.fecha, gi.bdc, 
            gd.*, gc.preg7 as categorizacion, gn.fuente 
            FROM gestion_diaria gd 
                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
                INNER JOIN gestion_consulta gc ON gi.id = gc.id_informacion
                LEFT JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion 
                WHERE gi.responsable = {$id_responsable} AND gd.desiste = 0
                ORDER BY gd.id DESC";
            //die('sql: '. $sql);
        }

        // SELECT DE USUARIOS MAS VERHICULOS SUBIDOS       
        //$sql = "SELECT gi.*, gv.modelo, gv.version FROM gestion_informacion gi 
        //        INNER JOIN gestion_vehiculo gv ON gi.id = gv.id_informacion 
        //        GROUP BY gi.id";
        //$sql = "SELECT * FROM gestion_informacion GROUP BY id";
        $request = $con->createCommand($sql);
        $users = $request->queryAll();
        $count = count($users);
        //die('count: '.$count);
        $tipo = '';

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_GET['GestionDiaria'])) {
//            echo '<pre>';
//            print_r($_GET);
//            echo '</pre>';
//            die();
            $cargo_id = (int) Yii::app()->user->getState('cargo_id');
            $id_responsable = Yii::app()->user->getId();

            $con = Yii::app()->db;
            $getParams = array();

            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $fechaActual = date("Y/m/d");
            $params = explode('-', $_GET['GestionDiaria']['fecha']);
            $fechaPk = 0;
            if (($fechaActual == trim($params[0])) && ($fechaActual == trim($params[1]))) {
                $fechaPk = 1;
            }
            //die('fechaPk: '.$fechaPk);
            /* BUSQUEDA EN CAMPOS VACIOS GERENTE COMERCIAL */
            if (empty($_GET['GestionDiaria']['categorizacion']) &&
                    $fechaPk == 1 &&
                    empty($_GET['GestionDiaria']['responsable']) &&
                    empty($_GET['GestionDiaria']['tipo_fecha']) &&
                    empty($_GET['GestionDiaria']['general']) &&
                    empty($_GET['GestionDiaria']['status']) &&
                    empty($_GET['GestionDiaria']['fuente']) && $cargo_id == 69) {
                //die('enter busqueda general');
                $title_busqueda = 'Búsqueda General: ';
                if ($cargo_id == 70) {
                    //die('enter jefe');
                    // SELECT ANTIGUO QUE SE ENLAZABA GON GESTION DIARIA
                    $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, 
            gi.ruc,gi.pasaporte,gi.email, gi.responsable as resp,gi.tipo_form_web,gi.fecha, gi.bdc, 
            gd.*, gc.preg7 as categorizacion, gn.fuente 
            FROM gestion_diaria gd 
                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
                INNER JOIN gestion_consulta gc ON gi.id = gc.id_informacion
                INNER JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion 
                WHERE gi.bdc = 0 AND gi.dealer_id = {$dealer_id} AND gd.desiste = 0
                ORDER BY gd.id DESC";
                    //die('sql sucursal'. $sql);
                }
                if ($cargo_id = 71) {
                    // SELECT ANTIGUO QUE SE ENLAZABA GON GESTION DIARIA
                    $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, 
            gi.ruc,gi.pasaporte,gi.email, gi.responsable as resp,gi.tipo_form_web,gi.fecha, gi.bdc, 
            gd.*, gc.preg7 as categorizacion, gn.fuente 
            FROM gestion_diaria gd 
                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
                INNER JOIN gestion_consulta gc ON gi.id = gc.id_informacion
                LEFT JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion 
                WHERE gi.responsable = {$id_responsable} AND gi.bdc = 0 AND gd.desiste = 0
                ORDER BY gd.id DESC";
                    //die('sql: '. $sql);
                }

                // SELECT DE USUARIOS MAS VERHICULOS SUBIDOS       
                //$sql = "SELECT gi.*, gv.modelo, gv.version FROM gestion_informacion gi 
                //        INNER JOIN gestion_vehiculo gv ON gi.id = gv.id_informacion 
                //        GROUP BY gi.id";
                //$sql = "SELECT * FROM gestion_informacion GROUP BY id";
                $request = $con->createCommand($sql);
                $users = $request->queryAll();
                $getParams['general'] = $_GET['GestionDiaria']['general'];
                $this->render('seguimiento', array('users' => $users, 'getParams' => $getParams, 'title_busqueda' => $title_busqueda, 'model' => $model));
                exit();
            }

            /* BUSQUEDA EN CAMPOS VACIOS */
            if (empty($_GET['GestionDiaria']['categorizacion']) &&
                    $fechaPk == 1 &&
                    empty($_GET['GestionDiaria']['tipo_fecha']) &&
                    empty($_GET['GestionDiaria']['general']) &&
                    empty($_GET['GestionDiaria']['status']) &&
                    empty($_GET['GestionDiaria']['fuente'])) {
                //die('enter busqueda geberal');
                $title_busqueda = 'Búsqueda General: ';
                if ($cargo_id == 70) {
                    //die('enter jefe');
                    // SELECT ANTIGUO QUE SE ENLAZABA GON GESTION DIARIA
                    $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, 
            gi.ruc,gi.pasaporte,gi.email, gi.responsable as resp,gi.tipo_form_web,gi.fecha, gi.bdc, 
            gd.*, gc.preg7 as categorizacion, gn.fuente 
            FROM gestion_diaria gd 
                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
                INNER JOIN gestion_consulta gc ON gi.id = gc.id_informacion
                INNER JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion 
                WHERE gi.bdc = 0 AND gi.dealer_id = {$dealer_id} AND gd.desiste = 0
                ORDER BY gd.id DESC";
                    //die('sql sucursal'. $sql);
                }
                if ($cargo_id = 71) {
                    // SELECT ANTIGUO QUE SE ENLAZABA GON GESTION DIARIA
                    $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, 
            gi.ruc,gi.pasaporte,gi.email, gi.responsable as resp,gi.tipo_form_web,gi.fecha, gi.bdc, 
            gd.*, gc.preg7 as categorizacion, gn.fuente 
            FROM gestion_diaria gd 
                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
                INNER JOIN gestion_consulta gc ON gi.id = gc.id_informacion
                LEFT JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion 
                WHERE gi.responsable = {$id_responsable} AND gi.bdc = 0 AND gd.desiste = 0
                ORDER BY gd.id DESC";
                    //die('sql: '. $sql);
                }

                // SELECT DE USUARIOS MAS VERHICULOS SUBIDOS       
                //$sql = "SELECT gi.*, gv.modelo, gv.version FROM gestion_informacion gi 
                //        INNER JOIN gestion_vehiculo gv ON gi.id = gv.id_informacion 
                //        GROUP BY gi.id";
                //$sql = "SELECT * FROM gestion_informacion GROUP BY id";
                $request = $con->createCommand($sql);
                $users = $request->queryAll();
                $getParams['general'] = $_GET['GestionDiaria']['general'];
                $this->render('seguimiento', array('users' => $users, 'getParams' => $getParams, 'title_busqueda' => $title_busqueda, 'model' => $model));
                exit();
            }

            /* -----------------BUSQUEDA GENERAL------------------ */
            if (empty($_GET['GestionDiaria']['categorizacion']) &&
                    $fechaPk == 1 &&
                    empty($_GET['GestionDiaria']['responsable']) &&
                    empty($_GET['GestionDiaria']['tipo_fecha']) &&
                    !empty($_GET['GestionDiaria']['general'])) {
                //DIE('enter general');

                /* BUSQUEDA POR NOMBRES O APELLIDOS */
                $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, gi.tipo_form_web,gi.fecha, gi.bdc,
                    gi.ruc,gi.pasaporte,gi.email, gi.responsable as resp, gd.*, gc.preg7 as categorizacion, gn.fuente 
                    FROM gestion_diaria gd 
                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
                INNER JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion 
                INNER JOIN gestion_consulta gc ON gc.id_informacion = gd.id_informacion 
                WHERE gi.responsable = {$id_responsable} AND
                gi.nombres LIKE '%{$_GET['GestionDiaria']['general']}%' "
                        . "OR gi.apellidos LIKE '%{$_GET['GestionDiaria']['general']}%' "
                        . "OR gi.cedula LIKE '%{$_GET['GestionDiaria']['general']}%'";
                //die($sql);
                $request = $con->createCommand($sql);
                $posts = $request->queryAll();

                $title_busqueda = 'Búsqueda General: ';
                $getParams['general'] = $_GET['GestionDiaria']['general'];
                //die('before render seg');
                if (count($posts) > 0) {
                    $this->render('seguimiento', array('users' => $posts, 'getParams' => $getParams, 'title_busqueda' => $title_busqueda, 'model' => $model));
                    exit();
                }

                /* BUSQUEDA POR CEDULA */
                $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, gi.tipo_form_web,gi.fecha, gi.bdc,
                    gi.ruc,gi.pasaporte,gi.email, gi.responsable as resp, gd.*, gc.preg7 as categorizacion, gn.fuente 
                    FROM gestion_diaria gd 
                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
                INNER JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion 
                INNER JOIN gestion_consulta gc ON gc.id_informacion = gd.id_informacion 
                WHERE gi.responsable = {$id_responsable} AND
                gi.cedula LIKE '%{$_GET['GestionDiaria']['general']}%'";
                $request = $con->createCommand($sql);
                $posts = $request->queryAll();
                $title_busqueda = 'Búsqueda General: ';
                $getParams['general'] = $_GET['GestionDiaria']['general'];
                //die('before render seg');
                if (count($posts) > 0) {
                    $this->render('seguimiento', array('users' => $posts, 'getParams' => $getParams, 'title_busqueda' => $title_busqueda, 'model' => $model));
                    exit();
                }

                /* BUSQUEDA POR ID */
                $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, gi.tipo_form_web,gi.fecha, gi.bdc,
                    gi.ruc,gi.pasaporte,gi.email, gi.responsable as resp, gd.*, gc.preg7 as categorizacion, gn.fuente 
                    FROM gestion_diaria gd 
                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
                INNER JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion 
                INNER JOIN gestion_consulta gc ON gc.id_informacion = gd.id_informacion 
                WHERE gi.responsable = {$id_responsable} AND
                gi.id = {$_GET['GestionDiaria']['general']}";
                $request = $con->createCommand($sql);
                $posts = $request->queryAll();
                $title_busqueda = 'Búsqueda General: ';
                $getParams['general'] = $_GET['GestionDiaria']['general'];
                //die('before render seg');
                if (count($posts) > 0) {
                    $this->render('seguimiento', array('users' => $posts, 'getParams' => $getParams, 'title_busqueda' => $title_busqueda, 'model' => $model));
                    exit();
                }


                $this->render('seguimiento', array('users' => 0, 'getParams' => $getParams, 'title_busqueda' => $title_busqueda, 'model' => $model));
            }
            /* -----------------FIN BUSQUEDA GENERAL------------------ */

            /* -----------------BUSQUEDA POR FUENTE PARA GERENTE COMERCIAL------------------ */
            if (!empty($_GET['GestionDiaria']['fuente']) &&
                    empty($_GET['GestionDiaria']['general']) &&
                    empty($_GET['GestionDiaria']['categorizacion']) &&
                    empty($_GET['GestionDiaria']['status']) &&
                    $fechaPk == 1 &&
                    empty($_GET['GestionDiaria']['responsable']) &&
                    empty($_GET['GestionDiaria']['tipo_fecha']) && $cargo_id == 69) {
                //die('enter fuente');    
                $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, gi.tipo_form_web,gi.fecha, gi.bdc,
gi.ruc,gi.pasaporte,gi.email, gi.responsable as resp, gd.*, gc.preg7 as categorizacion, gn.fuente 
FROM gestion_diaria gd 
INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
INNER JOIN gestion_consulta gc ON gi.id = gc.id_informacion 
LEFT JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion 
                WHERE gi.responsable = {$id_responsable} AND
                gn.fuente = '{$_GET['GestionDiaria']['fuente']}' AND gi.dealer_id = {$dealer_id}";
                //die('sql: '.$sql);
                $request = $con->createCommand($sql);
                $posts = $request->queryAll();
                $title_busqueda = 'Búsqueda por Fuente: ';
                $getParams['fuente'] = $_GET['GestionDiaria']['fuente'];
                //die('before render seg');
                $this->render('seguimiento', array('users' => $posts, 'getParams' => $getParams, 'title_busqueda' => $title_busqueda, 'model' => $model));
            }

            /* -----------------BUSQUEDA POR FUENTE------------------ */
            if (!empty($_GET['GestionDiaria']['fuente']) &&
                    empty($_GET['GestionDiaria']['general']) &&
                    empty($_GET['GestionDiaria']['categorizacion']) &&
                    empty($_GET['GestionDiaria']['status']) &&
                    $fechaPk == 1 &&
                    empty($_GET['GestionDiaria']['tipo_fecha'])) {
                //die('enter fuente asesor');    
                $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, gi.tipo_form_web,gi.fecha, gi.bdc,
gi.ruc,gi.pasaporte,gi.email, gi.responsable as resp, gd.*, gc.preg7 as categorizacion, gn.fuente 
FROM gestion_diaria gd 
INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
INNER JOIN gestion_consulta gc ON gi.id = gc.id_informacion 
LEFT JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion 
                WHERE gi.responsable = {$id_responsable} AND
                gn.fuente = '{$_GET['GestionDiaria']['fuente']}' AND gi.dealer_id = {$dealer_id}";
                //die('sql: '.$sql);
                $request = $con->createCommand($sql);
                $posts = $request->queryAll();
                $title_busqueda = 'Búsqueda por Fuente: ';
                $getParams['fuente'] = $_GET['GestionDiaria']['fuente'];
                //die('before render seg');
                $this->render('seguimiento', array('users' => $posts, 'getParams' => $getParams, 'title_busqueda' => $title_busqueda, 'model' => $model));
                exit();
            }
            /* -----------------FIN BUSQUEDA POR FUENTE------------------ */
            /* -----------------BUSQUEDA POR CATEGORIZACION------------------ */


            if (!empty($_GET['GestionDiaria']['categorizacion']) && $fechaPk == 1 && empty($_GET['GestionDiaria']['tipo_fecha']) && empty($_GET['GestionDiaria']['general'])) {
                //die('enter categorizacion');
                $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, gi.tipo_form_web,gi.fecha, gi.bdc,
                    gi.ruc,gi.pasaporte,gi.email, gi.responsable as resp, gd.*, gc.preg7 as categorizacion, gn.fuente 
                    FROM gestion_diaria gd 
                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion
                INNER JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion
                INNER JOIN gestion_consulta gc ON gc.id_informacion = gd.id_informacion 
                WHERE gi.responsable = {$id_responsable} AND
                gc.preg7 = '{$_GET['GestionDiaria']['categorizacion']}'";
                $request = $con->createCommand($sql);
                $posts = $request->queryAll();
                $title_busqueda = 'Búsqueda por Categorización: ';
                $getParams['categorizacion'] = $_GET['GestionDiaria']['categorizacion'];
                //die('before render seg');
                $this->render('seguimiento', array('users' => $posts, 'getParams' => $getParams, 'title_busqueda' => $title_busqueda, 'model' => $model));
                exit();
            }
            /* -----------------FIN BUSQUEDA POR CATEGORIZACION DE GERENTE COMERCIAL------------------ */

            if (!empty($_GET['GestionDiaria']['categorizacion']) && $fechaPk == 1 && $cargo_id == 69 && empty($_GET['GestionDiaria']['responsable']) && empty($_GET['GestionDiaria']['tipo_fecha']) && empty($_GET['GestionDiaria']['general'])) {
                //die('enter cat');
                $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, gi.tipo_form_web,gi.fecha, gi.bdc,
                    gi.ruc,gi.pasaporte,gi.email, gi.responsable as resp, gd.*, gc.preg7 as categorizacion, gn.fuente 
                    FROM gestion_diaria gd 
                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion
                INNER JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion
                INNER JOIN gestion_consulta gc ON gc.id_informacion = gd.id_informacion 
                WHERE gi.responsable = {$id_responsable} AND
                gc.preg7 = '{$_GET['GestionDiaria']['categorizacion']}'";
                $request = $con->createCommand($sql);
                $posts = $request->queryAll();
                $title_busqueda = 'Búsqueda por Categorización: ';
                $getParams['categorizacion'] = $_GET['GestionDiaria']['categorizacion'];
                //die('before render seg');
                $this->render('seguimiento', array('users' => $posts, 'getParams' => $getParams, 'title_busqueda' => $title_busqueda, 'model' => $model));
            }
            /* -----------------FIN BUSQUEDA POR CATEGORIZACION------------------ */

            /* -----------------BUSQUEDA POR STATUS------------------ */
            if (!empty($_GET['GestionDiaria']['status']) && $fechaPk == 1 &&
                    empty($_GET['GestionDiaria']['responsable']) &&
                    empty($_GET['GestionDiaria']['tipo_fecha'])) {
                //die('enter to status');
                $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, gi.tipo_form_web,gi.fecha, gi.bdc,
                    gi.ruc,gi.pasaporte,gi.email, gi.responsable as resp, gd.*, gc.preg7 as categorizacion, gn.fuente 
                    FROM gestion_diaria gd 
                        INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
                        INNER JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion
                        INNER JOIN gestion_consulta gc ON gc.id_informacion = gd.id_informacion 
                        WHERE gi.responsable = {$id_responsable} AND";
                switch ($_GET['GestionDiaria']['status']) {
                    case 'Cierre':
                        $sql .= " gd.cierre = 1 ORDER BY gd.id DESC";
                        break;
                    case 'Desiste':
                        $sql .= " gd.desiste = 1 ORDER BY gd.id DESC";
                        break;
                    case 'Entrega':
                        $sql .= " gd.entrega = 1 ORDER BY gd.id DESC";
                        break;
                    case 'PrimeraVisita':
                        $sql .= " gd.primera_visita = 1 ORDER BY gd.id DESC";
                        break;
                    case 'Seguimiento':
                        $sql .= " gd.seguimiento = 1 ORDER BY gd.id DESC";
                        break;
                    case 'SeguimientoEntrega':
                        $sql .= " gd.seguimiento_entrega = 1 ORDER BY gd.id DESC";
                        break;

                    default:
                        break;
                }
                //die('sql: '.$sql);
                $request = $con->createCommand($sql);
                $posts = $request->queryAll();
                $title_busqueda = 'Búsqueda por Status: ';
                $getParams['status'] = $_GET['GestionDiaria']['status'];
                //die('before render seg');
                $this->render('seguimiento', array('users' => $posts, 'getParams' => $getParams, 'title_busqueda' => $title_busqueda, 'model' => $model));
            }
            /* -----------------END SEARCH BY STATUS------------------ */

            /* -----------------BUSQUEDA POR FECHA------------------ */
            if (empty($_GET['GestionDiaria']['status']) && $fechaPk == 0 &&
                    empty($_GET['GestionDiaria']['responsable']) &&
                    !empty($_GET['GestionDiaria']['tipo_fecha'])) {
                //die('enter fecha');
                $tipoFecha = $_GET['GestionDiaria']['tipo_fecha'];
                $params1 = trim($params[0]);
                $params2 = trim($params[1]);
                //die('after params');
                $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, gi.tipo_form_web,gi.fecha, gi.bdc,
                    gi.ruc,gi.pasaporte,gi.email, gi.responsable as resp, gd.*, gc.preg7 as categorizacion, gn.fuente 
                    FROM gestion_diaria gd 
                        INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
                        WHERE gi.responsable = {$id_responsable} AND";
                if ($tipoFecha == 'proximoseguimiento') {
                    $sql .= " gd.proximo_seguimiento BETWEEN '{$params1}' AND '{$params2}'";
                } else if ($tipoFecha == 'fecharegistro') {
                    $sql .= " gd.fecha BETWEEN '{$params1}' AND '{$params2}'";
                }

                $request = $con->createCommand($sql);
                $posts = $request->queryAll();
                $title_busqueda = 'Búsqueda por Fecha: ';
                $getParams['status'] = $_GET['GestionDiaria']['fecha'];
                $this->render('seguimiento', array('users' => $posts, 'getParams' => $getParams, 'title_busqueda' => $title_busqueda, 'model' => $model));
            }

            /* -----------------BUSQUEDA POR RESPONSABLE------------------ */
            if (!empty($_GET['GestionDiaria']['responsable']) && $fechaPk == 1 &&
                    empty($_GET['GestionDiaria']['general']) &&
                    empty($_GET['GestionDiaria']['categorizacion']) &&
                    empty($_GET['GestionDiaria']['tipo_fecha']) &&
                    empty($_GET['GestionDiaria']['fuente']) &&
                    empty($_GET['GestionDiaria']['grupo']) &&
                    empty($_GET['GestionDiaria']['concesionario']) &&
                    empty($_GET['GestionDiaria']['provincia'])) {
                //die('enter responsable');
                $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, 
            gi.ruc,gi.pasaporte,gi.email, gi.responsable as resp,gi.tipo_form_web,gi.fecha, gi.bdc, 
            gd.*, gc.preg7 as categorizacion, gn.fuente 
            FROM gestion_diaria gd 
                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
                INNER JOIN gestion_consulta gc ON gi.id = gc.id_informacion
                LEFT JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion 
                WHERE gi.responsable = {$_GET['GestionDiaria']['responsable']} AND gi.bdc = 0 AND gd.desiste = 0
                ORDER BY gd.id DESC";

                $request = $con->createCommand($sql);
                $posts = $request->queryAll();
                $title_busqueda = 'Búsqueda por Responsable: ';
                $getParams['responsable'] = $_GET['GestionDiaria']['responsable'];
                //die('before render seg');
                $this->render('seguimiento', array('users' => $posts, 'getParams' => $getParams, 'title_busqueda' => $title_busqueda, 'model' => $model));
                exit();
            }

            //$this->render('seguimiento');
        }

        $this->render('seguimiento', array('users' => $users, 'model' => $model));
    }

    public function actionSeguimientoBdc() {
        //$this->layout = '//layouts/callventas';
        $cargo = Yii::app()->user->getState('usuario');
        $id_responsable = Yii::app()->user->getId();
        $dealer_id = $this->getDealerId($id_responsable);
        $model = new GestionNuevaCotizacion;
        $con = Yii::app()->db;

        if ($cargo == 'gerente') {
            // SELECT ANTIGUO QUE SE ENLAZABA GON GESTION DIARIA
            $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, 
            gi.ruc,gi.pasaporte,gi.email, gi.responsable,gi.tipo_form_web,gi.fecha, gi.bdc, 
            gd.*, gc.preg7 as categorizacion, gn.fuente 
            FROM gestion_diaria gd 
                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
                INNER JOIN gestion_consulta gc ON gi.id = gc.id_informacion
                INNER JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion 
                WHERE gi.bdc = 1
                ORDER BY gd.id DESC";
        } else {
            // SELECT ANTIGUO QUE SE ENLAZABA GON GESTION DIARIA
            $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, 
            gi.ruc,gi.pasaporte,gi.email, gi.responsable,gi.tipo_form_web,gi.fecha, gi.bdc, 
            gd.*, gc.preg7 as categorizacion, gn.fuente 
            FROM gestion_diaria gd 
                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
                INNER JOIN gestion_consulta gc ON gi.id = gc.id_informacion
                INNER JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion 
                WHERE gi.bdc = 1
                ORDER BY gd.id DESC";
        }

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

        $this->render('seguimientobdc', array('users' => $users, 'model' => $model));
    }

    public function actionSeguimientoexonerados() {
        //$this->layout = '//layouts/callventas';
        $cargo = Yii::app()->user->getState('usuario');
        $id_responsable = Yii::app()->user->getId();
        $dealer_id = $this->getDealerId($id_responsable);
        $model = new GestionNuevaCotizacion;
        $con = Yii::app()->db;

        if ($cargo == 'gerente') {
            // SELECT ANTIGUO QUE SE ENLAZABA GON GESTION DIARIA
            $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, 
            gi.ruc,gi.pasaporte,gi.email, gi.responsable as resp,gi.tipo_form_web,gi.fecha, gi.bdc, 
            gd.*, gn.fuente 
            FROM gestion_diaria gd 
                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
                INNER JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion 
                WHERE gi.tipo_form_web = 'exonerados'
                ORDER BY gd.id DESC";
        } else {
            // SELECT ANTIGUO QUE SE ENLAZABA GON GESTION DIARIA
            $sql = "SELECT gi.id as id_info, gi.nombres, gi.apellidos, gi.cedula, 
            gi.ruc,gi.pasaporte,gi.email, gi.responsable as resp,gi.tipo_form_web,gi.fecha, gi.bdc, 
            gd.*, gn.fuente 
            FROM gestion_diaria gd 
                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
                INNER JOIN gestion_nueva_cotizacion gn ON gn.id = gi.id_cotizacion 
                WHERE gi.tipo_form_web = 'exonerados' AND gi.dealer_id = {$dealer_id}
                ORDER BY gd.id DESC";
        }

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

        $this->render('seguimientoexonerado', array('users' => $users, 'model' => $model));
    }

    public function actionExonerados($tipo = NULL, $id = NULL, $fuente = NULL, $tipo_fuente = NULL) {
        $model = new GestionInformacion;
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

            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            $model->responsable = Yii::app()->user->getId();
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
                if ($_POST['tipo'] == 'gestion' && $_POST['yt0'] == 'Continuar') {
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

            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            $model->responsable = Yii::app()->user->getId();
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
                $params = explode('@', $_POST['GestionInformacion']['modelo_usado']);
                $model->modelo_usado = $params[1] . ' ' . $params[2];
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
                if ($_POST['tipo'] == 'gestion' && $_POST['yt0'] == 'Continuar') {
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
                    $this->redirect(array('gestionInformacion/seguimiento'));
                }
                $this->redirect(array('gestionConsulta/create', 'id_informacion' => $model->id, 'tipo' => $_POST['tipo'], 'fuente' => $fuente));
            }
        }
        $this->render('usadoscr', array(
            'model' => $model, 'tipo' => $tipo, 'id' => $id, 'fuente' => $fuente
        ));
    }

    public function actionSeguimientoUsados() {
        $con = Yii::app()->db;

        if (isset($_GET['GestionSolicitudCredito'])) {

            //---- BUSQUEDA GENERAL----
            if (!empty($_GET['GestionSolicitudCredito']['general']) &&
                    empty($_GET['GestionSolicitudCredito']['status']) &&
                    empty($_GET['GestionSolicitudCredito']['responsable'])) {

                /* BUSQUEDA POR NOMBRES O APELLIDOS */
                $sql = "SELECT gi.*, gd.proximo_seguimiento FROM gestion_informacion gi 
                        INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id
                        WHERE gi.tipo_form_web = 'usado' OR tipo_form-web = 'usadopago' 
                        AND (gi.nombres LIKE '%{$_GET['GestionSolicitudCredito']['general']}%' 
                        OR gi.apellidos LIKE '%{$_GET['GestionSolicitudCredito']['general']}%')";
                $request = $con->createCommand($sql);
                $posts = $request->queryAll();
                if (count($posts) > 0) {
                    $this->render('usados', array(
                        'users' => $posts, 'search' => true
                    ));
                    exit();
                }

                /* BUSQUEDA POR CEDULA,RUC O PASAPORTE */
                $sql = "SELECT gi.*, gd.proximo_seguimiento FROM gestion_informacion gi 
                        INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id
                        WHERE gi.tipo_form_web = 'usado' 
                        AND (gi.cedula LIKE '%{$_GET['GestionSolicitudCredito']['general']}%' 
                        OR gi.ruc LIKE '%{$_GET['GestionSolicitudCredito']['general']}%' "
                        . "OR gi.pasaporte LIKE '%{$_GET['GestionSolicitudCredito']['general']}%')";
                $request = $con->createCommand($sql);
                $posts = $request->queryAll();
                if (count($posts) > 0) {
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

        $sql = "SELECT gi.*, gd.proximo_seguimiento FROM gestion_informacion gi 
            INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id
WHERE gi.tipo_form_web = 'usado' OR  gi.tipo_form_web = 'usadopago' ORDER BY gi.id DESC";
        $request = $con->createCommand($sql);
        $users = $request->queryAll();
        $this->render('usados', array('users' => $users));
    }

    public function actionCalendar() {
        $this->render('calendar');
    }


    // Empieza optimización de código por Nicolás Vela 12-2015

    public function actionReportes($id_informacion = null, $id_vehiculo = null) {
        $grupo_id = (int) Yii::app()->user->getState('grupo_id');
        $cargo_id = (int) Yii::app()->user->getState('cargo_id');
        $id_responsable = Yii::app()->user->getId();
        $dealer_id = $this->getDealerId($id_responsable);
        $titulo = '';
        $concesionario = 2000;
        $tipos = null;

        date_default_timezone_set('America/Guayaquil'); 
        $dt = time();
        setlocale(LC_TIME, 'es_ES.UTF-8');
        $fecha_actual = strftime("%Y-%m-%d", $dt);
        $fecha_actual2 = strtotime('+1 day', strtotime($fecha_actual));  
        $fecha_actual2 = date('Y-m-d', $fecha_actual2);        
        $fecha_inicial_actual = (new DateTime('first day of this month'))->format('Y-m-d');
        $fecha_anterior = strftime( "%Y-%m-%d", strtotime( '-1 month', $dt ) );
        $fecha_inicial_anterior = strftime( "%Y-%m", strtotime( '-1 month', $dt ) ). '-01';
        $nombre_mes_actual = strftime("%B - %Y", $dt);
        $nombre_mes_anterior = strftime( "%B - %Y", strtotime( '-1 month', $dt ) );

        $con = Yii::app()->db;

     	//GET MODELOS Y VERSIONES PARA BUSCADOR
        $sqlModelos_nv = "SELECT nombre_modelo, id_modelos from modelos";
        $requestModelos_nv = $con->createCommand($sqlModelos_nv);
        $modelos_car = $requestModelos_nv->queryAll();

        //SI BUSCAN POR VERSION O MODELO Y RECIBE VARIABLES PARA LA CONSULTA
        $lista_datos = array();
        if (isset($_GET['modelo'])) {array_push($lista_datos, array('modelos' => $_GET['modelo']));}
        if (isset($_GET['version'])) {array_push($lista_datos, array('versiones' =>$_GET['version']));}
        $SQLmodelos = '';
        foreach ($lista_datos as $key => $value) {
            foreach ($value as $key => $carros) {
                if($key == 'modelos'){$campo_car= 'modelo';}
                else{$campo_car= 'version';}              
                $id_carros_nv[$key] = implode(', ', $carros);
                $SQLmodelos[$key] = " AND gi.".$campo_car." IN (".$id_carros_nv[$key].") ";
            }
        }

        //GET Asesores
        $mod = new GestionDiaria;
        $cre = new CDbCriteria();
        $dealer_resp = $this->getConcesionarioDealerId($id_responsable);
        if(!empty($dealer_resp)){
            $dealer_id = $dealer_resp;
        }
        $cre->condition = " cargo_id = 71 AND dealers_id = {$dealer_id}";
        $cre->order = " nombres ASC";
        $usu = CHtml::listData(Usuarios::model()->findAll($cre), "id", "fullname");

        //variables busqueda por defecto
        $tit_ext = '';
        $join_ext = null;
        $group_ext = null;
        $select_ext = null;
        $tit_init = 'Búsqueda entre ';
        switch ($cargo_id) { 
            case 71: // asesor de ventas 
            	$id_persona = "gi.responsable = ".$id_responsable;
            	$tit_init = 'Búsqueda entre ';
            	break;
            case 70: // jefe de sucursal 
            	$id_persona = "gi.dealer_id = ".$dealer_id;
            	$tit_init = 'Búsqueda entre ';          	
            	break;
            case 69: // GERENTE COMERCIAL
            	$id_persona = 'u.grupo_id = '.$grupo_id;
            	$tit_init = 'Búsqueda por defecto entre ';                
            	$tit_ext = ', Grupo: ' . $this->getNombreGrupo($grupo_id);
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
                $id_persona = 'gi.responsable = '.$responsable;
                $id_persona .= " AND gi.dealer_id = ".$dealer_id.' ';
            }else if($tip_us != 1 && $tip_us != 3 && empty($_GET['GestionDiaria']['responsable'])){       
                $responsable = key($usu);
                $tit_ext .= '. Asesor: ' . reset($usu);
                $id_persona = 'gi.responsable = '.$responsable;
                $id_persona .= " AND gi.dealer_id = ".$dealer_id.' ';
            }else if($tip_us == 3 && !empty($_GET['GestionInformacion']['concesionario'])){
                $responsable = $_GET['GestionDiaria']['responsable'];
                if($responsable){
                    $id_persona .= " AND gi.responsable = ".$responsable.' ';
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
             }else if (($tip_us == 2) && !empty($_GET['GestionDiaria']['responsable'])) {
                $tipo_busqueda_trafico2 = 5;
                $tipo_busqueda_proforma2 = 6;
                $tipo_busqueda_testdrive2 = 7;
                $tipo_busqueda_ventas2 = 8;

            }else if ($tip_us == 3 && !empty($_GET['GestionInformacion']['concesionario'])) {
                $tipo_busqueda_trafico2 = 9;
                $tipo_busqueda_proforma2 = 10;
                $tipo_busqueda_testdrive2 = 11;
                $tipo_busqueda_ventas2 = 12;
            }else if ($tip_us == 3 && (($_GET['GestionInformacion']['concesionario'] == 0) || empty($_GET['GestionInformacion']['concesionario'])) && empty($_GET['GestionDiaria']['responsable'])) {
                $tipo_busqueda_trafico2 = 13;
                $tipo_busqueda_proforma2 = 14;
                $tipo_busqueda_testdrive2 = 15;
                $tipo_busqueda_ventas2 = 16;
                $tit_ext .= '. Grupo: ' . $this->getNombreGrupo($grupo_id);
            }

            $fecha1 = explode(' - ', $_GET['GestionInformacion']['fecha1']);
            $fecha2 = explode(' - ', $_GET['GestionInformacion']['fecha2']);
            $responsable = 0;

            $fecha_inicial_anterior = trim($fecha1[0]);
            $fecha_anterior = trim($fecha1[1]);

            $fecha_inicial_actual = trim($fecha2[0]);                
            $fecha_actual = trim($fecha2[1]);            

            $nombre_mes_actual = strftime( "%B - %Y", strtotime($fecha_inicial_actual));
            $nombre_mes_anterior = strftime( "%B - %Y", strtotime($fecha_inicial_anterior));

            $tipos = array();
            array_push($tipos, $tipo_busqueda_trafico2, $tipo_busqueda_proforma2, $tipo_busqueda_testdrive2, $tipo_busqueda_ventas2);  
        }      
  
        $retorno = $this->buscar(
            $cargo_id, 
            $id_responsable, 
            $select_ext, 
            $join_ext, 
            $id_persona, 
            $group_ext, 
            $fecha_inicial_anterior, 
            $fecha_anterior, 
            $fecha_inicial_actual, 
            $fecha_actual, 
            $concesionario, 
            $tipos, 
            $SQLmodelos
        );

        $titulo = $tit_init. $fecha_inicial_actual . ' / ' . $fecha_actual . ', y ' . $fecha_inicial_anterior . ' / ' . $fecha_anterior.$tit_ext;

        $this->render('reportes', array(
            'titulo' => $titulo,
            'nombre_mes_anterior' => $nombre_mes_anterior, //segundo
            'nombre_mes_actual' => $nombre_mes_actual, // primero
            'trafico_mes_anterior' => $retorno[0],
            'trafico_mes_actual' => $retorno[1],
            'vhcbu2' => $retorno[2],
            'vhckd2' => $retorno[3],
            'vhcbu1' => $retorno[4],
            'vhckd1' => $retorno[5],
            'vh_mes_actual' => $retorno[6],
            'vh_mes_anterior' => $retorno[7],
            'tdcbu2' => $retorno[8],
            'tdckd2' => $retorno[9],
            'tdcbu1' => $retorno[10],
            'tdckd1' => $retorno[11],
            'td_mes_actual' => $retorno[12],
            'td_mes_anterior' => $retorno[13],
            'proformacbu2' => $retorno[14],
            'proformackd2' => $retorno[15],
            'proformacbu1' => $retorno[16],
            'proformackd1' => $retorno[17],
            'proforma_mes_actual' => $retorno[18],
            'proforma_mes_anterior' => $retorno[19],
            'traficocbu2' => $retorno[20],
            'traficockd2' => $retorno[21],
            'traficocbu1' => $retorno[22],            
            'traficockd1' => $retorno[23],
            'modelos_car' => $modelos_car,
            'lista_datos' => $lista_datos,
            'usu' => $usu,
            'mod' => $mod
        ));
    }
    function SQLconstructor($selection, $table, $join, $where, $group = null){
        $con = Yii::app()->db;
        $sql_cons = "SELECT {$selection} from {$table} {$join}
        WHERE {$where} {$group}";
        //echo $sql_cons.'<br><br>';

        $request_cons = $con->createCommand($sql_cons);
        return  $request_cons->queryAll();
    }

    function buscar($cargo_id, $id_responsable, $select_ext, $join_ext, $id_persona, $group_ext, $fecha_inicial_anterior, $fecha_anterior, $fecha_inicial_actual, $fecha_actual, $concesionario = 0, $tipos = null, $carros){
        $modelos = null;
        $versiones = null;
        if(!empty($carros['modelos'])){ $modelos = $carros['modelos'];  }
        if(!empty($carros['versiones'])){ $versiones = $carros['versiones'];}

        if(empty($tipos)){
            $tipos = array();
            array_push($tipos,1,2,3,4);       
        }

        $retorno = array();
        
        //BUSQUEDA POR TRAFICO      
        $trafico_mes_anterior = $this->SQLconstructor(
            'gi.nombres '.$select_ext, 
            'gestion_informacion gi', 
            $join_ext, 
            $id_persona." AND DATE(gi.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."'".$modelos.$versiones, 
            $group_ext
        );

        $trafico_mes_anterior = count($trafico_mes_anterior);
        $retorno[] = $trafico_mes_anterior;                               
        
        $trafico_mes_actual = $this->SQLconstructor(
            'gi.nombres '.$select_ext, 
            'gestion_informacion gi', 
            $join_ext, 
            $id_persona." AND DATE(gi.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."'".$modelos.$versiones, 
            $group_ext
        );
        $trafico_mes_actual = count($trafico_mes_actual);
        $retorno[] = $trafico_mes_actual;

        
        $traficockd1 = $this->SQLconstructor(
            'gi.id, gi.nombres, gi.apellidos, gi.responsable, gi.fecha, gi.modelo '.$select_ext.' as modinfo, gv.modelo', 
            'gestion_informacion gi', 
            $join_ext.' LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id ', 
            "DATE(gi.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."' AND ".$id_persona.$modelos.$versiones." AND ((gv.modelo IN (24,21)) OR gi.modelo IN (24,21))", 
            $group_ext
        );
        $traficockd1 = count($traficockd1);
        $retorno[] = $traficockd1;
        $traficocbu1 = $trafico_mes_anterior - $traficockd1; // resto de modelos
        $retorno[] = $traficocbu1;

        $traficockd2 = $this->SQLconstructor(
            'gi.id, gi.nombres, gi.apellidos, gi.responsable, gi.fecha, gi.modelo '.$select_ext.' as modinfo, gv.modelo', 
            'gestion_informacion gi', 
            $join_ext.' LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id ', 
            "DATE(gi.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."' AND ".$id_persona.$modelos.$versiones." AND ((gv.modelo IN (24,21)) OR gi.modelo IN (24,21))", 
            $group_ext
        );
        $traficockd2 = count($traficockd2);
        $retorno[] = $traficockd2;

        $traficocbu2 = $trafico_mes_actual - $traficockd2; // resto de modelos
        $retorno[] = $traficocbu2;


        // BUSQUEDA POR PROFORMA
        $proforma_mes_anterior = $this->SQLconstructor(
            'gf.id_informacion, gf.id_vehiculo, gf.fecha, gi.responsable, gi.dealer_id '.$select_ext, 
            'gestion_financiamiento gf', 
            'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion '.$join_ext, 
            "DATE(gf.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."' AND ".$id_persona.$modelos.$versiones." AND gf.order = 1", 
            "GROUP BY gf.id_vehiculo"
        );
        $proforma_mes_anterior = count($proforma_mes_anterior);
        $retorno[] = $proforma_mes_anterior;        
       
        $proforma_mes_actual = $this->SQLconstructor(
            'gf.id_informacion, gf.id_vehiculo, gf.fecha, gi.responsable, gi.dealer_id '.$select_ext, 
            'gestion_financiamiento gf', 
            'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion '.$join_ext, 
            "DATE(gf.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."' AND ".$id_persona.$modelos.$versiones."  AND gf.order = 1", 
            "GROUP BY gf.id_vehiculo"
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
            'gt.id_informacion, gt.id_vehiculo, gt.test_drive, gt.fecha, gi.responsable, gi.dealer_id', 
            'gestion_test_drive  gt', 
            'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion '.$join_ext, 
            "gt.test_drive = 1 AND DATE(gt.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."' ".$modelos.$versiones." AND gt.order = 1 AND ".$id_persona, 
            "GROUP BY gt.id_vehiculo"
        );
        $td_mes_anterior = count($td_mes_anterior);
        $retorno[] = $td_mes_anterior;

        $td_mes_actual = $this->SQLconstructor(
            'gt.id_informacion, gt.id_vehiculo, gt.test_drive, gt.fecha, gi.responsable, gi.dealer_id', 
            'gestion_test_drive  gt', 
            'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion '.$join_ext, 
            "gt.test_drive = 1 AND DATE(gt.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."' ".$modelos.$versiones." AND gt.order = 1 AND ".$id_persona, 
            "GROUP BY gt.id_vehiculo"
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
            'gv.id_informacion, gv.modelo, gv.version, gv.fecha, gv.cierre, gi.dealer_id', 
            'gestion_vehiculo gv', 
            'INNER JOIN gestion_informacion gi ON gi.id = gv.id_informacion '.$join_ext, 
            "gv.cierre = 'ACTIVO' AND (DATE(gv.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."') AND ".$id_persona.$modelos.$versiones, 
            "GROUP BY gv.id_informacion"
        );
        $vh_mes_anterior = count($vh_mes_anterior);
        $retorno[] = $vh_mes_anterior;
        
        $vh_mes_actual = $this->SQLconstructor(
            'gv.id_informacion, gv.modelo, gv.version, gv.fecha, gv.cierre, gi.dealer_id', 
            'gestion_vehiculo gv', 
            'INNER JOIN gestion_informacion gi ON gi.id = gv.id_informacion '.$join_ext, 
            "gv.cierre = 'ACTIVO' AND (DATE(gv.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."') AND ".$id_persona.$modelos.$versiones, 
            "GROUP BY gv.id_informacion"
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

    private function getSelectCKDCBU($cargo_id, $fecha1, $fecha2, $id_responsable, $ckdcku, $tipo_busqueda, $concesionario) {
        $responsable = Yii::app()->user->getId();
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
                echo $sql;
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
                        //die('sql jefe almacen: '.$sql);
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
                        $sql = "SELECT gi.nombres, gi.apellidos, gi.responsable, gi.fecha, u.grupo_id from gestion_informacion gi 
                    INNER JOIN usuarios u ON u.id = gi.responsable 
                    WHERE u.grupo_id = {$grupo_id} AND DATE(gi.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}'";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 14: // proforma gerente comercial
                        $sql = "SELECT gf.id_informacion, gf.id_vehiculo, gf.fecha, gi.responsable, gi.dealer_id, u.grupo_id FROM gestion_financiamiento gf 
                        INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion 
                        INNER JOIN usuarios u ON u.id = gi.responsable
                    WHERE DATE(gf.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}' AND u.grupo_id = {$grupo_id} GROUP BY gf.id_vehiculo ";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 15: // test drive gerente comercial
                        $sql = "SELECT gt.id_informacion, gt.id_vehiculo, gt.test_drive, gt.fecha, gi.responsable, gi.dealer_id FROM gestion_test_drive  gt 
                    INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion 
                    INNER JOIN usuarios u ON u.id = gi.responsable
                    WHERE gt.test_drive = 1 AND DATE(gt.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}' AND gt.order = 1 AND u.grupo_id = {$grupo_id}
                    GROUP BY gt.id_vehiculo";
                        $requestr1 = $con->createCommand($sql);
                        $result = $requestr1->queryAll();
                        $result = count($result);
                        return $result;
                        break;
                    case 16: // ventas gerente comercial
                        $sql = "SELECT gv.id_informacion, gv.modelo, gv.version, gv.fecha, gv.cierre, gi.dealer_id FROM gestion_vehiculo gv 
                    INNER JOIN gestion_informacion gi ON gi.id = gv.id_informacion 
                    INNER JOIN usuarios u ON u.id = gi.responsable
                    WHERE gv.cierre = 'ACTIVO' AND (DATE(gv.fecha) BETWEEN '{$fecha1}' AND '{$fecha2}') AND u.grupo_id = {$grupo_id}
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
        }
    }
}
