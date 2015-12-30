<?php

class GestionConsultaController extends Controller {

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
                'actions' => array('create', 'update', 'setFinanciamiento'),
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
    public function actionCreate($id_informacion = NULL, $tipo = NULL, $fuente = NULL) {
        //die('fuente: '.$fuente);
        $model = new GestionConsulta;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionConsulta'])) {
//             echo '<pre>';
//              print_r($_POST);
//              echo '</pre>';
//              die(); 
            $currencys = array("$", ".");
            $id_info = $_POST['GestionInformacion']['id_informacion'];
            //die('id info: '.$id_info);
            $model->attributes = $_POST['GestionConsulta'];
            $model->preg7 = $_POST['GestionConsulta']['preg7'];
            $model->status = 'ACTIVO';
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador

            /* $gestion->id_informacion = $model->id;
              $gestion->id_vehiculo = 0;
              $gestion->observaciones = 'Primera visita';
              $gestion->medio_contacto = 'visita';
              $gestion->fuente_contacto = $fuente;
              $gestion->codigo_vehiculo = 0;
              $gestion->primera_visita = 1;
              $gestion->fecha = date("Y-m-d H:i:s");
              $gestion->save(); */

            $vec = $_POST['GestionConsulta']['vec'];

            if ($vec == 1) {// tiene vehiculo
                $model->id_informacion = $_POST['GestionInformacion']['id_informacion'];
                $model->preg1_sec5 = 0; // 0 value for yes vehicle
                $model->preg1_sec1 = $_POST['GestionConsulta']['preg1_sec1']; // marca
                $model->preg1_sec2 = $_POST['Cotizador']['modelo'];
                $model->preg1_sec3 = $_POST['Cotizador']['year'];
                $model->preg1_sec4 = $_POST['GestionConsulta']['preg1_sec4']; // kilometraje
                $model->preg2 = $_POST['GestionConsulta']['preg2'];

                if ($_POST['GestionConsulta']['preg2'] == 1) {
                    // subir foto del auto para Seminuevos
                    //die('count file: '.count($_FILES));
                    $grupo_id = (int) Yii::app()->user->getState('grupo_id');
                    
                    $archivoThumb1 = CUploadedFile::getInstance($model, 'img1');
                    $fileName1 = "{$archivoThumb1}";  // file name
                    $archivoThumb2 = CUploadedFile::getInstance($model, 'img2');
                    $fileName2 = "{$archivoThumb2}";  // file name
                    $archivoThumb3 = CUploadedFile::getInstance($model, 'img3');
                    $fileName3 = "{$archivoThumb3}";  // file name
                    $archivoThumb4 = CUploadedFile::getInstance($model, 'img4');
                    $fileName4 = "{$archivoThumb4}";  // file name
                    $archivoThumb5 = CUploadedFile::getInstance($model, 'img5');
                    $fileName5 = "{$archivoThumb5}";  // file name

                    $galeria = '';
                    if ($archivoThumb1 != "") {
                        $archivoThumb1->saveAs(Yii::getPathOfAlias("webroot") . "/images/uploads/" . $fileName1);
                        $link = Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName1;
                        $image = new EasyImage($link);
                        $image->resize(600, 480); // resize images for thumbs
                        $image->save(Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName1);
                        $galeria .= $fileName1 . '@'; // image gallery for seminuevos
                    }
                    if ($archivoThumb2 != "") {
                        $archivoThumb2->saveAs(Yii::getPathOfAlias("webroot") . "/images/uploads/" . $fileName2);
                        $link = Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName2;
                        $image = new EasyImage($link);
                        $image->resize(600, 480); // resize images for thumbs
                        $image->save(Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName2);
                        $galeria .= $fileName2 . '@'; // image gallery for seminuevos
                    }
                    if ($archivoThumb3 != "") {
                        $archivoThumb3->saveAs(Yii::getPathOfAlias("webroot") . "/images/uploads/" . $fileName3);
                        $link = Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName3;
                        $image = new EasyImage($link);
                        $image->resize(600, 480); // resize images for thumbs
                        $image->save(Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName3);
                        $galeria .= $fileName3 . '@'; // image gallery for seminuevos
                    }
                    if ($archivoThumb4 != "") {
                        $archivoThumb4->saveAs(Yii::getPathOfAlias("webroot") . "/images/uploads/" . $fileName4);
                        $link = Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName4;
                        $image = new EasyImage($link);
                        $image->resize(600, 480); // resize images for thumbs
                        $image->save(Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName4);
                        $galeria .= $fileName4 . '@'; // image gallery for seminuevos
                    }
                    if ($archivoThumb5 != "") {
                        $archivoThumb5->saveAs(Yii::getPathOfAlias("webroot") . "/images/uploads/" . $fileName5);
                        $link = Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName5;
                        $image = new EasyImage($link);
                        $image->resize(600, 480); // resize images for thumbs
                        $image->save(Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName5);
                        $galeria .= $fileName5 . '@'; // image gallery for seminuevos
                    }
                    $model->preg2_sec1 = $galeria; // save string of image names

                    if ($_POST['GestionConsulta']['preg2_sec2'] != '') {
                        $model->preg2_sec2 = $_POST['GestionConsulta']['preg2_sec2'];
                    } else {
                        // ----REGISTRAR EL AUTO USADO PARA EL RGD DE USADOS
                        // SACAR INFORMACION DEL CLIENTE REGISTRADO
                        $marca_usado = $_POST['GestionConsulta']['preg1_sec1']; // marca
                        $par = explode('@', $_POST['Cotizador']['modelo']);
                        $modelo_usado = $par[1].' '.$par[2];
                        
                        $con = Yii::app()->db;
                        $sql = "UPDATE gestion_informacion SET tipo_form_web = 'usadopago', marca_usado = '{$marca_usado}', modelo_usado = '{$modelo_usado}'  WHERE id = {$id_info}";
                        $request = $con->createCommand($sql)->query();

                        // ENVIAR EMAIL A SEMINUEVOS
                        //die('before send email seminuevos');
                        require_once 'email/mail_func.php';
                        $stringModelo = $_POST['Cotizador']['modelo'];
                        $stringModelo = trim($stringModelo);
                        $paramModelo = explode('@', $stringModelo);
                        $modelo_auto = $paramModelo[1] . ' ' . $paramModelo[2];

                        $stringImage = $galeria;
                        $stringImage = trim($stringImage);
                        $stringImage = substr($stringImage, 0, strlen($stringImage) - 1);
                        $paramString = explode('@', $stringImage);
                        /* echo '<pre>';
                          print_r($paramString);
                          echo '<pre>';
                          die(); */
						$usr = new CDbCriteria;
						$usr->select = (['telefono_casa', 'celular']);
						$usr->condition = "id = '".$id_info."'";
						$usertelf = Gestioninformacion::model()->findAll($usr);
						$return = array();
						foreach($usertelf as $row)
						{
							$return[] = $row->attributes;
						}
                        $asunto = 'Kia Motors Ecuador SGC -  Solicitud de Pre Avalúo Vehículo Usado ID Cliente # ' . $id_info;
                        $general = '<body style="margin: 10px;">
                                        <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;">
                                            <div align="">
                                            <img src="images/header_mail.jpg">
                                            <br><br>
                                            <p style="margin: 2px 0;">Señor(a): Kia Seminuevos</p><br />
                                            <p style="margin: 2px 0;">Por favor su ayuda con el pre avalúo del siguiente vehículo:</p><br />
                                            <p></p>
                                                <table width="400">
                                                    <tr><td><strong>Marca:</strong></td><td>' . $_POST['GestionConsulta']['preg1_sec1'] . '</td></tr>
                                                    <tr>
                                                    <tr><td><strong>Modelo:</strong></td><td>' . $modelo_auto . '</td></tr>
                                                    <tr>
                                                    <tr><td><strong>Año:</strong></td><td>' . $_POST['Cotizador']['year'] . '</td></tr>
                                                    <tr>
                                                    <tr><td><strong>Kilometraje:</strong></td><td>' . $_POST['GestionConsulta']['preg1_sec4'] . '</td></tr>
                                                    <tr>
                                                        <td colspan="2"><strong>Imágenes de Auto:</strong></td>
                                                    </tr>
                                                    <tr><td>Adjunto podrán encontrar fotografías para su análisis.</td></tr>
                                                    <tr><td>Saludos cordiales.</td></tr>';
                        foreach ($paramString as $vl) {
                            $general .= '<tr><td><img src="https://www.kia.com.ec/intranet/usuario/images/uploads/' . $vl . '" width="450"/></td></tr>';
                        }
                        $general .= '</table>
                            
                            <p style="margin: 2px 0;"><strong>Asesor Comercial: </strong>' . $this->getResponsable(Yii::app()->user->getId()) . '</p>
                            <p style="margin: 2px 0;"><strong>Concesionario: </strong>' . $this->getConcesionario($this->getDealerId(Yii::app()->user->getId())) . '</p>
                            <p style="margin: 2px 0;"><strong>Tlf. Concesionario: </strong>'.$this->getConcesionarioTlf($this->getDealerId(Yii::app()->user->getId())).'</p>
                            <p style="margin: 2px 0;"><strong>Celular: '.$return[0]['celular'].'</strong></p><p style="margin: 2px 0;"><strong>Tlf. Cliente: </strong> '.$return[0]['telefono_casa'].'</p>
                            <p style="margin: 2px 0;"><strong>Celular: </strong></p>
							<p>Saludos cordiales,<br> SGC<br> Kia Motors Ecuador </p>
							<p>Nota de descargo: La información contenida en este e-mail es confidencial y sólo puede ser utilizada por el individuo o la compañía a la cual está dirigido. Esta información no debe ser distribuida ni copiada total o parcialmente por ningún medio sin la autorización de AEKIA S.A.<br>
La organización no asume responsabilidad sobre información, opiniones o criterios contenidos en este mail que no esté relacionada con negocios oficiales de nuestra compañía.</p>
                            <img src="images/footer.png">
                                            </div>
                                        </div>
                                    </body>';
                        //die('table: '.$general);
                        $codigohtml = $general;
                        $headers = 'From: info@kia.com.ec' . "\r\n";
                        $headers .= 'Content-type: text/html' . "\r\n";
                        $email = $this->getEmailAsesorUsados(77, $grupo_id); //email asesor usados

                        sendEmailInfo('info@kia.com.ec', "Kia Motors Ecuador", $email, html_entity_decode($asunto), $codigohtml);
                        //die('after send email');
                    }
                }
                $model->preg3 = $_POST['GestionConsulta']['preg3'];
                if ($_POST['GestionConsulta']['preg3'] == 0) {
                    $model->preg3_sec1 = $_POST['GestionConsulta']['preg3_sec1'];
                }
                if ($_POST['GestionConsulta']['preg3'] == 1) {
                    $model->preg3_sec2 = $_POST['GestionConsulta']['preg3_sec1'];
                }
                $model->preg4 = $_POST['GestionConsulta']['preg4'];
                $estructura = (int) str_replace($currencys, "", $_POST['GestionConsulta']['preg5']);
                $model->preg5 = $estructura;
                $model->preg6 = $_POST['GestionConsulta']['preg6'];
                if (isset($_POST['necesidad']) && !empty($_POST['necesidad'])) {
                    //die('enter necesidad si vec');
                    $counter = $_POST['necesidad'];
                    $necesidades = '';
                    foreach ($counter as $key => $entry) {
                        $necesidades .= $entry . '@';
                    }
                    $necesidades = substr($necesidades, 0, -1);
                    $model->preg8 = $necesidades;
                }
                $model->fecha = date("Y-m-d H:i:s");
                $model->save();
                $ges = $this->getGestion($id_informacion);
                if ($ges == TRUE) {
                    $con = Yii::app()->db;
                    $sql = "UPDATE gestion_diaria SET primera_visita = 1, paso = '4', status = 1 WHERE id_informacion = {$id_informacion}";
                    $request = $con->createCommand($sql)->query();
                } else {
                    $gestion = new GestionDiaria;
                    $gestion->id_informacion = $id_info;
                    $gestion->id_vehiculo = 0;
                    $gestion->observaciones = 'Prospección';
                    $gestion->medio_contacto = 'telefono';
                    $gestion->fuente_contacto = $_POST['GestionInformacion']['fuente'];;
                    $gestion->codigo_vehiculo = 0;
                    $gestion->primera_visita = 1;
                    $gestion->status = 1;
                    $gestion->paso = '4';
                    $gestion->proximo_seguimiento = '';
                    $gestion->fecha = date("Y-m-d H:i:s");
                    $gestion->save();
                }

                //die('request: '.$request);
            } else if ($vec == 0) {// primer vehiculo
                $model->id_informacion = $id_informacion;
                $model->preg1_sec5 = 1; // 1 value of primer vehiculo
                $model->preg3 = $_POST['GestionConsulta']['preg3'];
                if ($_POST['GestionConsulta']['preg3'] == 0) {
                    $model->preg3_sec1 = $_POST['GestionConsulta']['preg3_sec1'];
                }
                if ($_POST['GestionConsulta']['preg3'] == 1) {
                    $model->preg3_sec2 = $_POST['GestionConsulta']['preg3_sec1'];
                }
                $model->preg4 = $_POST['GestionConsulta']['preg4'];
                $estructura = (int) str_replace($currencys, "", $_POST['GestionConsulta']['preg5']);
                $model->preg5 = $estructura;
                $model->preg6 = $_POST['GestionConsulta']['preg6'];
                if (isset($_POST['necesidad']) && !empty($_POST['necesidad'])) {
                    //die('enter accesorios no vec');
                    $counter = $_POST['necesidad'];
                    $necesidades = '';
                    foreach ($counter as $key => $entry) {
                        $necesidades .= $entry . '@';
                    }
                    $necesidades = substr($necesidades, 0, -1);
                    //die("necesidades: ".$necesidades);
                    $model->preg8 = $necesidades;
                }
                $model->fecha = date("Y-m-d H:i:s");
                /* if($model->validate()){
                  die('no error');
                  print_r($model->getErrors());
                  }else{
                  die('errors');
                  } */
                $con = $this->getConsulta($id_informacion);
                $model->save();
                //die('after save');
                $ges = $this->getGestion($id_informacion);
                if ($ges == TRUE) {
                    $con = Yii::app()->db;
                    $sql = "UPDATE gestion_diaria SET primera_visita = 1, paso = '4', status = 1, proximo_seguimiento = '' WHERE id_informacion = {$id_informacion}";
                    $request = $con->createCommand($sql)->query();
                } else {
                    $gestion = new GestionDiaria;
                    $gestion->id_informacion = $id_info;
                    $gestion->id_vehiculo = 0;
                    $gestion->observaciones = 'Prospección';
                    $gestion->medio_contacto = 'telefono';
                    $gestion->fuente_contacto = $_POST['GestionInformacion']['fuente'];;
                    $gestion->codigo_vehiculo = 0;
                    $gestion->primera_visita = 1;
                    $gestion->status = 1;
                    $gestion->paso = '4';
                    $gestion->proximo_seguimiento = '';
                    $gestion->fecha = date("Y-m-d H:i:s");
                    $gestion->save();
                }
            }
            $vehiculo = new GestionVehiculo;
            $vehiculo->attributes = $_POST['GestionVehiculo'];
            $vehiculo->fecha = date("Y-m-d H:i:s");
            $vehiculo->id_informacion = $_POST['GestionInformacion']['id_informacion'];
            $vehiculo->precio = $_POST['GestionVehiculo']['precio'];
            $vehiculo->save();

            $historial = new GestionHistorial;
            $historial->id_responsable = Yii::app()->user->getId();
            $historial->id_informacion = $model->id;
            $historial->observacion = 'Nuevo registro de usuario';
            $historial->paso = '3-4';
            $historial->fecha = date("Y-m-d H:i:s");
            $historial->save();

            $this->redirect(array('gestionVehiculo/create/' . $id_info));

            //if ($model->save())
            //    $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model, 'id_informacion' => $id_informacion, 'tipo' => $tipo, 'fuente' => $fuente
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     */
    public function actionUpdate($id_informacion = NULL, $tipo = NULL, $fuente = NULL) {
        $id = $this->getIdConsulta($id_informacion);

        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionConsulta'])) {
            $currencys = array("$", ".");
            $id_info = $_POST['GestionInformacion']['id_informacion'];
            //die('id info: '.$id_info);
            $model->attributes = $_POST['GestionConsulta'];
            $model->preg7 = $_POST['GestionConsulta']['preg7'];
            $model->status = 'ACTIVO';
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador

            /* $gestion->id_informacion = $model->id;
              $gestion->id_vehiculo = 0;
              $gestion->observaciones = 'Primera visita';
              $gestion->medio_contacto = 'visita';
              $gestion->fuente_contacto = $fuente;
              $gestion->codigo_vehiculo = 0;
              $gestion->primera_visita = 1;
              $gestion->fecha = date("Y-m-d H:i:s");
              $gestion->save(); */

            $vec = $_POST['GestionConsulta']['vec'];

            if ($vec == 1) {// tiene vehiculo
                $model->id_informacion = $_POST['GestionInformacion']['id_informacion'];
                $model->preg1_sec5 = 0; // 0 value for yes vehicle
                $model->preg1_sec1 = $_POST['GestionConsulta']['preg1_sec1']; // marca
                $model->preg1_sec2 = $_POST['Cotizador']['modelo'];
                $model->preg1_sec3 = $_POST['Cotizador']['year'];
                $model->preg1_sec4 = $_POST['GestionConsulta']['preg1_sec4']; // kilometraje
                $model->preg2 = $_POST['GestionConsulta']['preg2'];

                if ($_POST['GestionConsulta']['preg2'] == 1) {
                    // subir foto del auto para Seminuevos
                    //die('count file: '.count($_FILES));
                    $archivoThumb1 = CUploadedFile::getInstance($model, 'img1');
                    $fileName1 = "{$archivoThumb1}";  // file name
                    $archivoThumb2 = CUploadedFile::getInstance($model, 'img2');
                    $fileName2 = "{$archivoThumb2}";  // file name
                    $archivoThumb3 = CUploadedFile::getInstance($model, 'img3');
                    $fileName3 = "{$archivoThumb3}";  // file name
                    $archivoThumb4 = CUploadedFile::getInstance($model, 'img4');
                    $fileName4 = "{$archivoThumb4}";  // file name
                    $archivoThumb5 = CUploadedFile::getInstance($model, 'img5');
                    $fileName5 = "{$archivoThumb5}";  // file name

                    $galeria = '';
                    if ($archivoThumb1 != "") {
                        $archivoThumb1->saveAs(Yii::getPathOfAlias("webroot") . "/images/uploads/" . $fileName1);
                        $link = Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName1;
                        $image = new EasyImage($link);
                        $image->resize(600, 480); // resize images for thumbs
                        $image->save(Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName1);
                        $galeria .= $fileName1 . '@'; // image gallery for seminuevos
                    }
                    if ($archivoThumb2 != "") {
                        $archivoThumb2->saveAs(Yii::getPathOfAlias("webroot") . "/images/uploads/" . $fileName2);
                        $link = Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName2;
                        $image = new EasyImage($link);
                        $image->resize(600, 480); // resize images for thumbs
                        $image->save(Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName2);
                        $galeria .= $fileName2 . '@'; // image gallery for seminuevos
                    }
                    if ($archivoThumb3 != "") {
                        $archivoThumb3->saveAs(Yii::getPathOfAlias("webroot") . "/images/uploads/" . $fileName3);
                        $link = Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName3;
                        $image = new EasyImage($link);
                        $image->resize(600, 480); // resize images for thumbs
                        $image->save(Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName3);
                        $galeria .= $fileName3 . '@'; // image gallery for seminuevos
                    }
                    if ($archivoThumb4 != "") {
                        $archivoThumb4->saveAs(Yii::getPathOfAlias("webroot") . "/images/uploads/" . $fileName4);
                        $link = Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName4;
                        $image = new EasyImage($link);
                        $image->resize(600, 480); // resize images for thumbs
                        $image->save(Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName4);
                        $galeria .= $fileName4 . '@'; // image gallery for seminuevos
                    }
                    if ($archivoThumb5 != "") {
                        $archivoThumb5->saveAs(Yii::getPathOfAlias("webroot") . "/images/uploads/" . $fileName5);
                        $link = Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName5;
                        $image = new EasyImage($link);
                        $image->resize(600, 480); // resize images for thumbs
                        $image->save(Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName5);
                        $galeria .= $fileName5 . '@'; // image gallery for seminuevos
                    }
                    $model->preg2_sec1 = $galeria; // save string of image names

                    if ($_POST['GestionConsulta']['preg2_sec2'] != '') {
                        $model->preg2_sec2 = $_POST['GestionConsulta']['preg2_sec2'];
                    } else {
                        // ENVIAR EMAIL A SEMINUEVOS
                        require_once 'email/mail_func.php';
                        $stringModelo = $_POST['Cotizador']['modelo'];
                        $stringModelo = trim($stringModelo);
                        $paramModelo = explode('@', $stringModelo);
                        $modelo_auto = $paramModelo[1] . ' ' . $paramModelo[2];

                        $stringImage = $galeria;
                        $stringImage = trim($stringImage);
                        $stringImage = substr($stringImage, 0, strlen($stringImage) - 1);
                        $paramString = explode('@', $stringImage);
                        /* echo '<pre>';
                          print_r($paramString);
                          echo '<pre>';
                          die(); */
						$usr = new CDbCriteria;
						$usr->select = (['telefono_casa', 'celular']);
						$usr->condition = "id = '".$id_info."'";
						$usertelf = Gestioninformacion::model()->findAll($usr);
						$return = array();
						foreach($usertelf as $row)
						{
							$return[] = $row->attributes;
						}
                        $asunto = 'Kia Motors Ecuador SGC -  Solicitud de Pre Avalúo Vehículo Usado ID Cliente # ' . $id_info ;
                        $general = '<body style="margin: 10px;">
                                        <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;">
                                            <div align="">
                                            <img src="images/header_mail.jpg">
                                            <br><br>
                                            <p style="margin: 2px 0;">Señor(a): Kia Seminuevos</p><br /><br />
                                            <p style="margin: 2px 0;">Por favor su ayuda con el pre avalúo del siguiente vehículo:</p><br /><br />
                                            <p></p>
                                                <table width="400">
                                                    <tr><td><strong>Marca:</strong></td><td>' . $_POST['GestionConsulta']['preg1_sec1'] . '</td></tr>
                                                    <tr>
                                                    <tr><td><strong>Modelo:</strong></td><td>' . $modelo_auto . '</td></tr>
                                                    <tr>
                                                    <tr><td><strong>Año:</strong></td><td>' . $_POST['Cotizador']['year'] . '</td></tr>
                                                    <tr>
                                                    <tr><td><strong>Kilometraje:</strong></td><td>' . $_POST['GestionConsulta']['preg1_sec4'] . '</td></tr>
                                                    <tr>
                                                        <td colspan="2"><strong>Imágenes de Auto:</strong></td>
                                                    </tr>
                                                    <tr><td>Adjunto podrán encontrar fotografías para su análisis.</td></tr>';
                        foreach ($paramString as $vl) {
                            $general .= '<tr><td><img src="https://www.kia.com.ec/intranet/usuario/images/uploads/' . $vl . '" width="450"/></td></tr>';
                        }
                        $general .= '</table>
                            
                            <p style="margin: 2px 0;"><strong>Asesor Comercial: </strong>' . $this->getResponsable(Yii::app()->user->getId()) . '</p>
                            <p style="margin: 2px 0;"><strong>Concesionario: </strong>' . $this->getConcesionario($this->getDealerId(Yii::app()->user->getId())) . '</p>
                            <p style="margin: 2px 0;"><strong>Tlf. Cliente: </strong> '.$return[0]['telefono_casa'].'</p>
                            <p style="margin: 2px 0;"><strong>Celular: </strong>'.$return[0]['celular'].'</p>
							<p>Saludos cordiales,<br> SGC<br> Kia Motors Ecuador </p>
							<p>Nota de descargo: La información contenida en este e-mail es confidencial y sólo puede ser utilizada por el individuo o la compañía a la cual está dirigido. Esta información no debe ser distribuida ni copiada total o parcialmente por ningún medio sin la autorización de AEKIA S.A.<br>
La organización no asume responsabilidad sobre información, opiniones o criterios contenidos en este mail que no esté relacionada con negocios oficiales de nuestra compañía.</p>
                            <img src="images/footer.png">
                                            </div>
                                        </div>
                                    </body>';
                        //die('table: '.$general);
                        $codigohtml = $general;
                        $headers = 'From: info@kia.com.ec' . "\r\n";
                        $headers .= 'Content-type: text/html' . "\r\n";
                        $email = 'alkanware@gmail.com'; //email administrador

                        sendEmailInfo('info@kia.com.ec', "Kia Motors Ecuador", $email, html_entity_decode($asunto), $codigohtml);
                        //die('after send email');
                    }
                }
                $model->preg3 = $_POST['GestionConsulta']['preg3'];
                if ($_POST['GestionConsulta']['preg3'] == 0) {
                    $model->preg3_sec1 = $_POST['GestionConsulta']['preg3_sec1'];
                }
                if ($_POST['GestionConsulta']['preg3'] == 1) {
                    $model->preg3_sec2 = $_POST['GestionConsulta']['preg3_sec1'];
                }
                $model->preg4 = $_POST['GestionConsulta']['preg4'];
                $estructura = (int) str_replace($currencys, "", $_POST['GestionConsulta']['preg5']);
                $model->preg5 = $estructura;
                $model->preg6 = $_POST['GestionConsulta']['preg6'];
                if (isset($_POST['necesidad']) && !empty($_POST['necesidad'])) {
                    //die('enter necesidad si vec');
                    $counter = $_POST['necesidad'];
                    $necesidades = '';
                    foreach ($counter as $key => $entry) {
                        $necesidades .= $entry . '@';
                    }
                    $necesidades = substr($necesidades, 0, -1);
                    $model->preg8 = $necesidades;
                }
                $model->fecha = date("Y-m-d H:i:s");
                $model->save();
                $ges = $this->getGestion($id_informacion);
                if ($ges == TRUE) {
                    $con = Yii::app()->db;
                    $sql = "UPDATE gestion_diaria SET primera_visita = 1, paso = '4', status = 1 WHERE id_informacion = {$id_informacion}";
                    $request = $con->createCommand($sql)->query();
                } else {
                    $gestion = new GestionDiaria;
                    $gestion->id_informacion = $id_info;
                    $gestion->id_vehiculo = 0;
                    $gestion->observaciones = 'Prospección';
                    $gestion->medio_contacto = 'telefono';
                    $gestion->fuente_contacto = 'prospeccion';
                    $gestion->codigo_vehiculo = 0;
                    $gestion->primera_visita = 1;
                    $gestion->status = 1;
                    $gestion->paso = '4';
                    $gestion->proximo_seguimiento = '';
                    $gestion->fecha = date("Y-m-d H:i:s");
                    $gestion->save();
                }

                //die('request: '.$request);
            } else if ($vec == 0) {// primer vehiculo
                $model->id_informacion = $id_informacion;
                $model->preg1_sec5 = 1; // 1 value of primer vehiculo
                $model->preg3 = $_POST['GestionConsulta']['preg3'];
                if ($_POST['GestionConsulta']['preg3'] == 0) {
                    $model->preg3_sec1 = $_POST['GestionConsulta']['preg3_sec1'];
                }
                if ($_POST['GestionConsulta']['preg3'] == 1) {
                    $model->preg3_sec2 = $_POST['GestionConsulta']['preg3_sec1'];
                }
                $model->preg4 = $_POST['GestionConsulta']['preg4'];
                $estructura = (int) str_replace($currencys, "", $_POST['GestionConsulta']['preg5']);
                $model->preg5 = $estructura;
                $model->preg6 = $_POST['GestionConsulta']['preg6'];
                if (isset($_POST['necesidad']) && !empty($_POST['necesidad'])) {
                    //die('enter accesorios no vec');
                    $counter = $_POST['necesidad'];
                    $necesidades = '';
                    foreach ($counter as $key => $entry) {
                        $necesidades .= $entry . '@';
                    }
                    $necesidades = substr($necesidades, 0, -1);
                    //die("necesidades: ".$necesidades);
                    $model->preg8 = $necesidades;
                }
                $model->fecha = date("Y-m-d H:i:s");
                /* if($model->validate()){
                  die('no error');
                  print_r($model->getErrors());
                  }else{
                  die('errors');
                  } */
                $con = $this->getConsulta($id_informacion);
                $model->save();
                //die('after save');
                $ges = $this->getGestion($id_informacion);
                if ($ges == TRUE) {
                    $con = Yii::app()->db;
                    $sql = "UPDATE gestion_diaria SET primera_visita = 1, paso = '4', status = 1, proximo_seguimiento = '' WHERE id_informacion = {$id_informacion}";
                    $request = $con->createCommand($sql)->query();
                } else {
                    $gestion = new GestionDiaria;
                    $gestion->id_informacion = $id_info;
                    $gestion->id_vehiculo = 0;
                    $gestion->observaciones = 'Prospección';
                    $gestion->medio_contacto = 'telefono';
                    $gestion->fuente_contacto = 'prospeccion';
                    $gestion->codigo_vehiculo = 0;
                    $gestion->primera_visita = 1;
                    $gestion->status = 1;
                    $gestion->paso = '4';
                    $gestion->proximo_seguimiento = '';
                    $gestion->fecha = date("Y-m-d H:i:s");
                    $gestion->save();
                }
            }
            $vehiculo = new GestionVehiculo;
            $vehiculo->attributes = $_POST['GestionVehiculo'];
            $vehiculo->fecha = date("Y-m-d H:i:s");
            $vehiculo->id_informacion = $_POST['GestionInformacion']['id_informacion'];
            $vehiculo->precio = $_POST['GestionVehiculo']['precio'];
            $vehiculo->save();

            $historial = new GestionHistorial;
            $historial->id_responsable = Yii::app()->user->getId();
            $historial->id_informacion = $model->id;
            $historial->observacion = 'Nuevo registro de usuario';
            $historial->paso = '3-4';
            $historial->fecha = date("Y-m-d H:i:s");
            $historial->save();

            $this->redirect(array('gestionVehiculo/create/' . $id_info));

            //if ($model->save())
            //    $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model, 'id_informacion' => $id_informacion, 'tipo' => $tipo, 'fuente' => $fuente
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
        $dataProvider = new CActiveDataProvider('GestionConsulta');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new GestionConsulta('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['GestionConsulta']))
            $model->attributes = $_GET['GestionConsulta'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return GestionConsulta the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = GestionConsulta::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param GestionConsulta $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'gestion-consulta-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionSetFinanciamiento() {
        $idInformacion = isset($_POST["idInformacion"]) ? $_POST["idInformacion"] : "";
        $tipo = isset($_POST["tipo"]) ? $_POST["tipo"] : "";
        $sql = "UPDATE gestion_consulta SET preg6 = {$tipo} WHERE id_informacion = {$idInformacion}";
        $con = Yii::app()->db;
        $request = $con->createCommand($sql)->query();
    }

}
