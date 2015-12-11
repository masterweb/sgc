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
                'actions' => array('create', 'update', 'seguimiento', 'calendar', 'createAjax'),
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
    public function actionCreate($tipo = NULL, $id = NULL, $fuente = NULL) {
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
            $model->dealer_id = $this->getDealerId(Yii::app()->user->getId());
            $model->id_cotizacion = $_POST['GestionInformacion']['id_cotizacion'];
            if ($_POST['tipo'] == 'gestion'):
                $model->provincia_conc = $_POST['GestionInformacion']['provincia_conc'];
                $model->ciudad_conc = $_POST['GestionInformacion']['ciudad_conc'];
                $model->concesionario = $_POST['GestionInformacion']['concesionario'];
                $model->provincia_domicilio = $_POST['GestionInformacion']['provincia_domicilio'];
                $model->ciudad_domicilio = $_POST['GestionInformacion']['ciudad_domicilio'];
            endif;

            if (isset($_POST['GestionInformacion']['fecha_cita']))
                $model->fecha_cita = $_POST['GestionInformacion']['fecha_cita'];

            if ($_POST['tipo'] == 'gestion') {
                $model->setscenario('gestion');
            } else if ($_POST['tipo'] == 'prospeccion') {
                $model->setscenario('prospeccion');
            }

            if ($model->save()) {
                //die('enter save');
                if ($_POST['tipo'] == 'prospeccion' && $_POST['yt0'] == 'Continuar') {
                    //die('enter continuar');
                    // enviar a la pantalla de seguimiento con el nuevo caso ingresado
                    // ingresar datos en gestion diaria con status 1: prospección
                    $gestion = new GestionDiaria;
                    /* $gestion->id_informacion = $model->id;
                      $gestion->id_vehiculo = 0;
                      $gestion->observaciones = 'Prospección';
                      $gestion->medio_contacto = 'telefono';
                      $gestion->fuente_contacto = 'prospeccion';
                      $gestion->codigo_vehiculo = 0;
                      $gestion->prospeccion = 1;
                      $gestion->proximo_seguimiento = $_POST['GestionInformacion']['fecha_cita'];
                      $gestion->fecha = date("Y-m-d H:i:s");
                      $gestion->save(); */
                    $gestion->paso = $_POST['GestionInformacion']['paso'];
                    $prospeccion = new GestionProspeccionRp;
                    $prospeccion->id_informacion = $model->id;
                    $observaciones = $_POST['GestionProspeccionPr']['pregunta'];
                    switch ($observaciones) {
                        case 1:
                            $prospeccion->preg1 = $_POST['GestionProspeccionPr']['pregunta'];
                            break;
                        case 2:
                            $prospeccion->preg2 = $_POST['GestionProspeccionPr']['pregunta'];
                            break;
                        case 3: // compro otro vehiculo
                            $prospeccion->preg3 = $_POST['GestionProspeccionPr']['pregunta'];
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
                            $gestion->proximo_seguimiento = '';
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        case 4:// si estoy interesado
                            //die('enter case 4');
                            $prospeccion->preg4 = $_POST['GestionProspeccionPr']['pregunta'];
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
                            $gestion->proximo_seguimiento = $_POST['GestionDiaria']['agendamiento'];
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            //die('after save');
                            break;
                        case 5:// no contesta
                            $prospeccion->preg5 = $_POST['GestionProspeccionPr']['pregunta'];
                            $prospeccion->preg5_sec1 = $_POST['GestionDiaria']['agendamiento2'];

                            $gestion->id_informacion = $model->id;
                            $gestion->id_vehiculo = 0;
                            $gestion->observaciones = 'Prospección';
                            $gestion->medio_contacto = 'telefono';
                            $gestion->fuente_contacto = 'prospeccion';
                            $gestion->codigo_vehiculo = 0;
                            $gestion->prospeccion = 1;
                            $gestion->proximo_seguimiento = $_POST['GestionDiaria']['agendamiento2'];
                            $gestion->fecha = date("Y-m-d H:i:s");
                            $gestion->save();
                            break;
                        case 6: // telefono equivocado
                            $prospeccion->preg6 = $_POST['GestionProspeccionPr']['pregunta'];
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
                    //die('enter abandonar');
                    $this->redirect(array('gestionInformacion/seguimiento'));
                }
                if ($_POST['tipo'] == 'gestion') {
                    //die('enter gestion');
                    $gestion = new GestionDiaria;
                    /* $gestion->id_informacion = $model->id;
                      $gestion->id_vehiculo = 0;
                      $gestion->observaciones = 'Primera visita';
                      $gestion->medio_contacto = 'visita';
                      $gestion->fuente_contacto = $fuente;
                      $gestion->codigo_vehiculo = 0;
                      $gestion->primera_visita = 1;
                      $gestion->fecha = date("Y-m-d H:i:s");
                      $gestion->save(); */
                    $gestion->paso = $_POST['GestionInformacion']['paso'];
                    if (isset($_POST['GestionConsulta']['preg1_sec5']) && ($_POST['GestionConsulta']['preg1_sec5']) == 1) {// primer vehiculo
                        $consulta = new GestionConsulta;
                        $consulta->id_informacion = $model->id;
                        $consulta->preg1_sec5 = 1; // 1 value of primer vehiculo
                        $consulta->preg3 = $_POST['GestionConsulta']['preg3'];
                        if ($_POST['GestionConsulta']['preg3'] == 0) {
                            $consulta->preg3_sec1 = $_POST['GestionConsulta']['preg3_sec1'];
                        }
                        if ($_POST['GestionConsulta']['preg3'] == 1) {
                            $consulta->preg3_sec2 = $_POST['GestionConsulta']['preg3_sec1'];
                        }
                        $consulta->preg4 = $_POST['GestionConsulta']['preg4'];
                        $consulta->preg5 = $_POST['GestionConsulta']['preg5'];
                        $consulta->preg6 = $_POST['GestionConsulta']['preg6'];
                        $consulta->fecha = date("Y-m-d H:i:s");
                        $consulta->save();

                        $gestion->id_informacion = $model->id;
                        $gestion->id_vehiculo = 0;
                        $gestion->observaciones = 'Prospección';
                        $gestion->medio_contacto = 'telefono';
                        $gestion->fuente_contacto = 'prospeccion';
                        $gestion->codigo_vehiculo = 0;
                        $gestion->primera_visita = 1;
                        $gestion->proximo_seguimiento = '';
                        $gestion->fecha = date("Y-m-d H:i:s");
                        $gestion->save();
                    } else if (!isset($_POST['GestionConsulta']['preg1_sec5'])) {// tiene vehiculo
                        $consulta = new GestionConsulta;
                        $consulta->id_informacion = $model->id;
                        $consulta->preg1_sec5 = 0; // 0 value for no vehicle
                        $consulta->preg1_sec1 = $_POST['GestionConsulta']['preg1_sec1'];// marca
                        $consulta->preg1_sec2 = $_POST['Cotizador']['modelo'];
                        $consulta->preg1_sec3 = $_POST['Cotizador']['year'];
                        $consulta->preg1_sec4 = $_POST['GestionConsulta']['preg1_sec4'];// kilometraje
                        $consulta->preg2 = $_POST['GestionConsulta']['preg2'];

                        if ($_POST['GestionConsulta']['preg2'] == 1) {
                            // subir foto del auto para Seminuevos
                            // get array of images for nivo slider galery
                            $photos = CUploadedFile::getInstancesByName('photos');

                            if (isset($photos) && count($photos) > 0) {
                                // go through each uploaded image
                                $spic = '';
                                $galeria = '';
                                foreach ($photos as $image => $pic) {
                                    if ($pic->saveAs(Yii::getPathOfAlias('webroot') . '/images/uploads/' . $pic->name)) {
                                        // add string to field preg2_sec1 in table gestion_consulta
                                        $link = Yii::getPathOfAlias('webroot') . '/images/uploads/' . $pic->name;
                                        $link2 = Yii::getPathOfAlias('webroot') . '/images/uploads/' . $pic->name;
                                        $image2 = new EasyImage($link2);
                                        $image2->resize(600, 450); // resize images for gallery
                                        $image2->save(Yii::getPathOfAlias('webroot') . '/images/uploads/' . $pic->name);
                                        $galeria .= $pic->name . '@'; // image gallery for seminuevos
                                    } else {
                                        //echo 'Cannot upload!';
                                    }
                                }
                                $consulta->preg2_sec1 = $galeria; // save string of image names
                                // ENVIAR EMAIL A SEMINUEVOS
                                require_once 'email/mail_func.php';
                                $stringModelo = $_POST['Cotizador']['modelo'];
                                $stringModelo = trim($stringModelo);
                                $paramModelo = explode('@', $stringModelo);
                                $modelo_auto = $paramModelo[1].' '.$paramModelo[2];
                                
                                $stringImage = $galeria;
                                $stringImage = trim($stringImage);
                                $stringImage = substr($stringImage, 0, strlen($stringImage) - 1);
                                $paramString = explode('@', $stringImage);
                                $asunto = 'Formulario enviado desde Gestión de Ventas:';
                                $general =
                                        '<body style="margin: 10px;">
                                        <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;">
                                            <div align="center"><br>
                                                <table width="600" cellpadding="12">
                                                    <tr><td><strong>Responsable:</strong>'.$this->getResponsable(Yii::app()->user->getId()).'</td></tr>
                                                    <tr>
                                                    <tr><td><strong>Concesionario:</strong>'.$this->getConcesionario($this->getDealerId(Yii::app()->user->getId())).'</td></tr>
                                                    <tr>
                                                    <tr><td><strong>Marca:</strong>'.$_POST['GestionConsulta']['preg1_sec1'].'</td></tr>
                                                    <tr>
                                                    <tr><td><strong>Modelo:</strong>'.$modelo_auto.'</td></tr>
                                                    <tr>
                                                    <tr><td><strong>Año:</strong>'.$_POST['Cotizador']['year'].'</p></td></tr>
                                                    <tr>
                                                    <tr><td><strong>Kilometraje:</strong>'.number_format($_POST['GestionConsulta']['preg1_sec4']).'</td></tr>
                                                    <tr>
                                                        <td colspan="2"><strong>Imágenes de Auto:</strong></td>
                                                    </tr>';
                                foreach ($paramString as $value) {
                                    $general .= '<tr><td><img src="https://www.kia.com.ec/intranet/ventas/images/uploads/' . $value . '" /></td></tr>';
                                }
                                $general .= '</table>
                                            </div>
                                        </div>
                                    </body>';
                                //die('table: '.$general);
                                $codigohtml = $general;
                                $headers = 'From: info@kia.com.ec' . "\r\n";
                                $headers .= 'Content-type: text/html' . "\r\n";
                                $email = 'alkanware@gmail.com'; //email administrador

                                sendEmailInfo('info@kia.com.ec', "Gestion Ventas", $email, html_entity_decode($asunto), $codigohtml);
                                //die('after send email');
                            }
                            if(isset($_POST['GestionConsulta']['link']) && !empty($_POST['GestionConsulta']['preg2'])){
                                $consulta->link = $_POST['GestionConsulta']['link']; // Graba web link
                            }

//                            $archivoThumb = CUploadedFile::getInstance($consulta, 'preg2_sec1');
//                            $fileName = "{$archivoThumb}";  // file name
//                            if ($archivoThumb != "") {
//                                $consulta->preg2_sec1 = $fileName;
//                                $archivoThumb->saveAs(Yii::getPathOfAlias("webroot") . "/images/uploads/" . $fileName);
//                                $link = Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName;
//                                $image = new EasyImage($link);
//                                $image->resize(600, 480); // resize images for thumbs
//                                $image->save(Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName);
//                            }
                        }
                        $consulta->preg3 = $_POST['GestionConsulta']['preg3'];
                        if ($_POST['GestionConsulta']['preg3'] == 0) {
                            $consulta->preg3_sec1 = $_POST['GestionConsulta']['preg3_sec1'];
                        }
                        if ($_POST['GestionConsulta']['preg3'] == 1) {
                            $consulta->preg3_sec2 = $_POST['GestionConsulta']['preg3_sec1'];
                        }
                        $consulta->preg4 = $_POST['GestionConsulta']['preg4'];
                        $consulta->preg5 = $_POST['GestionConsulta']['preg5'];
                        $consulta->preg6 = $_POST['GestionConsulta']['preg6'];
                        $consulta->fecha = date("Y-m-d H:i:s");
                        $consulta->save();

                        $gestion->id_informacion = $model->id;
                        $gestion->id_vehiculo = 0;
                        $gestion->observaciones = 'Prospección';
                        $gestion->medio_contacto = 'telefono';
                        $gestion->fuente_contacto = 'prospeccion';
                        $gestion->codigo_vehiculo = 0;
                        $gestion->primera_visita = 1;
                        $gestion->proximo_seguimiento = '';
                        $gestion->fecha = date("Y-m-d H:i:s");
                        $gestion->save();
                    }

                    $vehiculo = new GestionVehiculo;
                    $vehiculo->attributes = $_POST['GestionVehiculo'];
                    $vehiculo->fecha = date("Y-m-d H:i:s");
                    $vehiculo->id_informacion = $model->id;
                    $vehiculo->save();

                    $historial->id_responsable = Yii::app()->user->getId();
                    $historial->id_informacion = $model->id;
                    $historial->observacion = 'Nuevo registro de usuario';
                    $historial->paso = '3-4';
                    $historial->fecha = date("Y-m-d H:i:s");
                    $historial->save();
                }
                $this->redirect(array('gestionInformacion/seguimiento'));
                //$this->redirect(array('gestionInformacion/create', 'id' => $model->id, 'tipo' => $tipo));
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
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionInformacion'])) {
            $model->attributes = $_POST['GestionInformacion'];
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
        $model = new GestionNuevaCotizacion;
        $con = Yii::app()->db;
        // SELECT ANTIGUO QUE SE ENLAZABA GON GESTION DIARIA
        $sql = "SELECT gi.id as id_info, gi. nombres, gi.apellidos, gi.cedula, gi.email, gi.responsable, gd.* FROM gestion_diaria gd 
                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion ORDER BY gd.id_informacion DESC";

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
            $model->attributes = $_POST['GestionNuevaCotizacion'];
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            if ($_POST['GestionNuevaCotizacion']['fuente'] == 'otro') {
                $model->fuente = $_POST['GestionNuevaCotizacion']['fuente'];
            }
            if ($_POST['GestionNuevaCotizacion']['fuente'] == 'prospeccion') {
                $model->setscenario('prospeccion');
                $tipo = 'prospeccion';
                if ($model->save()) {
                    $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id,));
                }
            } else {
                //die('enter consulta');
                $model->setscenario('consulta');
                $tipo = 'gestion';
                if ($_POST['GestionNuevaCotizacion']['cedula'] != '') {
                    //die('enter empty');
                    $model->cedula = $_POST['GestionNuevaCotizacion']['cedula'];
                    $criteria = new CDbCriteria(array(
                                'condition' => "cedula='{$_POST['GestionNuevaCotizacion']['cedula']}'"
                            ));
                    $ced = GestionInformacion::model()->find($criteria);
                    if ((count($ced)) > 0) {
                        $this->redirect(array('gestionVehiculo/create', 'id' => $ced->id));
                    } else {
                        if ($model->save()) {
                            $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo, 'id' => $model->id, 'fuente' => $_POST['GestionNuevaCotizacion']['fuente']));
                        }
                    }
                }
            }

            if ($model->save()) {
                $this->redirect(array('gestionInformacion/create', 'tipo' => $tipo));
            }
        }

        $this->render('seguimiento', array('users' => $users, 'model' => $model));
    }

    public function actionCalendar() {
        $this->render('calendar');
    }

}
