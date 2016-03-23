<?php

class SiteController extends Controller {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /*     * actionConsultarUsuarioEncuesta
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */

    public function actionIndex() {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        $this->render('index');
    }

    public function actionMenu($opcion = null, $tipo = null) {
        if (empty($opcion)) {
            $this->redirect(array('dashboard'));
        }
        if (Yii::app()->user->id > 0) {
            $this->render('menu', array('opcion' => $opcion, 'tipo' => $tipo));
            $user = Usuarios::model()->findByPk(Yii::app()->user->id);
            if ($user->cargo->codigo == Constantes::CDG) {
                $this->traercotizaciones();
                $this->traernocompradores();
            }
        } else {
            //throw new CHttpException(404,'Lo sentimos no se puede desplegar esta '.utf8_decode(utf8_encode("información")).', usted debe estar registrado en el sistema.');
            $this->redirect(array('site/login'));
        }
    }

    public function traercotizaciones() {
        date_default_timezone_set("America/Bogota");
        $sql = 'SELECT * FROM atencion_detalle WHERE fecha_form >="2016-02-09" and encuestado = 0 and id_modelos is not null order by id_atencion_detalle desc';
        $datosC = Yii::app()->db2->createCommand($sql)->queryAll();
        $cargo_id = Cargo::model()->find(array('condition' => 'codigo = "' . Constantes::CDG . '"'));
        $usuarios = Usuarios::model()->findAll(array('condition' => 'estado = "ACTIVO" and cargo_id =' . $cargo_id->id));

        if (!empty($datosC)) {
            $maximo = number_format(count($datosC) / count($usuarios), 0);
            $actual = 0;
            $contactual = 0;
            $posicion = 0;
            $usuario_list = array();
            foreach ($usuarios as $u) {
                $usuario_list[$actual++] = $u->id;
            }


            foreach ($datosC as $d) {

                if ($contactual == $maximo) {
                    $contactual = 0;
                    $posicion++;
                }
                if ($posicion <= count($usuarios) && !empty($usuario_list[$posicion])) {

                    $cotizacion = new Cotizacionesnodeseadas();

                    $cotizacion->atencion_detalle_id = (int) $d['id_atencion_detalle'];

                    $cotizacion->usuario_id = $usuario_list[$posicion];
                    $cotizacion->fecha = $d['fecha_form'];
                    $cotizacion->realizado = '0';
                    $cotizacion->nombre = $d['nombre'];
                    $cotizacion->apellido = $d['apellido'];
                    $cotizacion->cedula = $d['cedula'];
                    $cotizacion->direccion = $d['direccion'];
                    $cotizacion->telefono = $d['telefono'];
                    $cotizacion->celular = $d['celular'];
                    $cotizacion->email = $d['email'];
                    $cotizacion->modelo_id = $d['id_modelos'];
                    $cotizacion->version_id = $d['id_version'];
                    $cotizacion->ciudadconcesionario_id = $d['cityid'];
                    $cotizacion->concesionario_id = $d['dealerid'];
                    if ($cotizacion->save()) {
                        //echo $usuario_list[$posicion];
                        Yii::app()->db2
                                ->createCommand("UPDATE atencion_detalle SET encuestado=1,fechaencuesta='" . date("Y-m-d h:i:s") . "' WHERE id_atencion_detalle=:RListID")
                                ->bindValues(array(':RListID' => $d['id_atencion_detalle']))
                                ->execute();
                    }
                }
                $contactual++;
            }
        }
    }

    public function actionDashboard() {
        if (Yii::app()->user->id > 0) {
            $this->render('dashboard');
        } else {
            //throw new CHttpException(404,'Lo sentimos no se puede desplegar esta '.utf8_decode(utf8_encode("información")).', usted debe estar registrado en el sistema.');
            $this->redirect(array('site/login'));
        }
    }

    public function actionTable() {
        $this->render('table');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {

        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                        "Reply-To: {$model->email}\r\n" .
                        "MIME-Version: 1.0\r\n" .
                        "Content-type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        if (Yii::app()->user->id > 0 && !empty(Yii::app()->user->id)) {
            $this->redirect(array('site/dashboard'));
            die();
        }
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            $model->password = md5($_POST['LoginForm']['password']);
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                $usuarios = Usuarios::model()->findAll(array('condition' => "estado=:match AND ultimavisita is not null AND id!=:id", 'params' => array(':match' => "ACTIVO", ':id' => (int) Yii::app()->user->id)));
                if (!empty($usuarios)) {
                    foreach ($usuarios as $item) {
                        $datetime1 = date_create($item->ultimavisita);
                        $datetime2 = date_create("now");
                        $interval = date_diff($datetime1, $datetime2);
                        if ($interval->format('%a') > 21) {
                            $item->estado = "INACTIVO";
                            $item->update();
                        } else if ($interval->format('%a') > 62) {
                            $item->estado = "BAJA";
                            $item->update();
                        }

                        //if($interval->format('%a')>0 && $reto->fechaFin >= date('Y-m-d')){
                    }
                    //die();
                }
                //$this->redirect(Yii::app()->user->returnUrl);
                //FUNCION PARA CALL CENTER SI TIENE COTIZACIONES AUTOMATICAS.

                $user = Usuarios::model()->findByPk(Yii::app()->user->id);
                if ($user->cargo->codigo == Constantes::CDG) {
                    $this->traercotizaciones();
                }

                $user = Usuarios::model()->findByPk(Yii::app()->user->id);
                if ($user->cargo->codigo == Constantes::CDG) {
                    $this->traercotizaciones();
                    $this->traernocompradores();
                }



                //Actualizar entrada del usuario
                $user = Usuarios::model()->find(array('condition' => "id=:match", 'params' => array(':match' => (int) Yii::app()->user->id)));
                if (empty($user->ultimavisita)) {
                    $this->redirect($this->createUrl('uusuarios/cambiar'));
                } else {
                    $user->ultimavisita = date("Y-m-d");
                    if ($user->update())
                        $this->redirect($this->createUrl('site/menu'));
                }
            }
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionConsulta($id_informacion = NULL, $tipo = NULL, $fuente = NULL) {
        $this->render('consulta', array('id_informacion' => $id_informacion, 'tipo' => $tipo, 'fuente' => $fuente));
    }

    public function actionBusqueda() {
        $txt = "";
        if (isset($_GET['buscar'])) {
            $txt = $_GET['buscar'];

            //----BUSQUEDA POR ID
            $sql = "SELECT * FROM casos WHERE id = '{$txt}'";
            $ids = Casos::model()->findAllBySQL($sql);
            //echo '<pre>'.print_r($nombres).'</pre>';
            if (!is_null($ids) && !empty($ids)) {
                //echo 'valor enconstrado en nombres: ' . $txt . '<br>';
            } else {
                $ids = '';
            }

            //----BUSQUEDA POR NOMBRES O APELLIDOS
            $sql = "SELECT * FROM casos WHERE nombres LIKE '%{$txt}%' OR apellidos LIKE '%{$txt}%'";
            //die('sql: '.$sql);
            $nombres = Casos::model()->findAllBySQL($sql);
            //echo '<pre>'.print_r($nombres).'</pre>';
            if (!is_null($nombres) && !empty($nombres)) {
                //echo 'valor enconstrado en nombres: ' . $txt . '<br>';
            } else {
                $nombres = '';
            }

            //----BUSQUEDA POR TEMA
            $sql = "SELECT id FROM menu WHERE name LIKE '%{$txt}%'";
            $idtema = Casos::model()->findBySQL($sql);
            if (!is_null($idtema) && !empty($idtema)) {
                //echo 'valor encontrado en tema: ' . $txt . '<br>';
                $id = $idtema->id;
                $sql = "SELECT * FROM casos WHERE tema = $id";
                $temas = Casos::model()->findAllBySQL($sql);
            } else {
                $temas = '';
            }

            //----BUSQUEDA POR SUBTEMA
            $sql = "SELECT id FROM submenu WHERE name LIKE '%{$txt}%'";
            $idsubtema = Casos::model()->findBySQL($sql);
            if (!is_null($idsubtema) && !empty($idsubtema)) {
                //echo 'valor encontrado en subtema: ' . $txt . '<br>';
                $idsb = $idsubtema->id;
                //echo $idsb;
                $sql = "SELECT * FROM casos WHERE subtema = $idsb";
                $subtemas = Casos::model()->findAllBySQL($sql);
            } else {
                $subtemas = '';
            }

            //----BUSQUEDA POR PROVINCIA
            $sql = "SELECT id_provincia FROM provincias WHERE nombre LIKE '%{$txt}%'";
            //die('sql: '.$sql);
            $idprovincia = Provincias::model()->findBySQL($sql);
            //echo '<pre>'.print_r($idprovincia).'</pre>';
            //die();
            if (!is_null($idprovincia) && !empty($idprovincia)) {
                //echo 'valor encontrado en provincia: ' . $txt . '<br>';
                $idpr = $idprovincia->id_provincia;
                //echo $idpr;
                $sql = "SELECT * FROM casos WHERE provincia = $idpr";
                $provincias = Casos::model()->findAllBySQL($sql);
            } else {
                $provincias = '';
            }

            //----BUSQUEDA POR CIUDAD
            $sql = "SELECT id FROM dealercities WHERE name LIKE '%{$txt}%'";
            //die('sql: '.$sql);
            $idcities = Dealercities::model()->findBySQL($sql);
            //echo '<pre>'.print_r($idprovincia).'</pre>';
            //die();
            if (!is_null($idcities) && !empty($idcities)) {
                //echo 'valor encontrado en provincia: ' . $txt . '<br>';
                $idct = $idcities->id;
                //echo $idct;
                $sql = "SELECT * FROM casos WHERE ciudad = $idct";
                $ciudades = Casos::model()->findAllBySQL($sql);
            } else {
                $ciudades = '';
            }

            //----BUSQUEDA POR CEDULA
            $sql = "SELECT * FROM casos WHERE cedula LIKE '%{$txt}%'";
            $cedula = Casos::model()->findAllBySQL($sql);
            if (!is_null($cedula) && !empty($cedula)) {
                //echo 'valor enconstrado en cedula: ' . $txt . '<br>';
            } else {
                $cedula = '';
            }

            $this->render('busqueda', array('nombres' => $nombres, 'temas' => $temas, 'subtemas' => $subtemas, 'provincias' => $provincias, 'ciudades' => $ciudades, 'cedula' => $cedula, 'txt' => $txt, 'ids' => $ids));
        }
    }

    public function actionRegistro() {
        date_default_timezone_set("America/Bogota");
        $model = new Usuarios;
        $model->body = 'resetPasswordWithCaptcha';
        if (isset($_POST['Usuarios'])) {
            /* echo '<pre>';
              print_r($_POST);
              echo '</pre>';
              die(); */
            if (!isset($_POST["recaptcha_response_field"]) && empty($_POST["recaptcha_response_field"]))
                Yii::app()->clientScript->registerScript('Alerta', 'alert("Debes ingresar el codigo de verificación")');

            $url = "https://" . $_SERVER['HTTP_HOST'];
            $model->attributes = $_POST['Usuarios'];
            $model->fecharegistro = date("Y-m-d h:i:s");
            $password = "Kia_" . date('s_i');
            $model->password = md5($password);
            $model->estado = 'PENDIENTE';
            $model->area_id = $_POST['Cargo']['area_id'];
            $model->codigo_asesor = $_POST['Usuarios']['codigo_asesor'];
            $grupo_concesionarios = $_POST['Usuarios']['concesionario_id'];
            $model->concesionario_id = 0;
            $model->firma = $_POST['Usuarios']['firma'];
            if ($model->save()) {
                /*
                  REGISTRAR TODOS LOS CONCESIONARIOS SELECCIONADOS EN LA TABLA DE GRUPOCONCESIONARIOUSUARIO
                 */
                if (!empty($grupo_concesionarios)) {
                    for ($i = 0; $i <= count($grupo_concesionarios); $i++) {
                        if (!empty($grupo_concesionarios[$i])) {
                            $gconcesionario = new Grupoconcesionariousuario();
                            $gconcesionario->usuario_id = $model->id;
                            $gconcesionario->concesionario_id = $grupo_concesionarios[$i];
                            $gconcesionario->save();
                        }
                    }
                }


                /*
                  FIN DEL REGISTRO DE CONCESIONARIOS
                 */


                require_once 'email/mail_func.php';
                $asunto = 'Kia Motors Ecuador SGC - Confirmaci&oacute;n de Registro de Usuario';
                $token = md5($model->correo . '--' . $model->id . '--' . $model->usuario);
                $general = '<body style="margin: 10px;">
                            <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;margin:auto">
                                <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;"><img src="images/header_mail.jpg"/></div>';
                $general .= '<div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;">Señor(a): <b>' . utf8_decode(utf8_encode($model->nombres)) . ' ' . utf8_decode(utf8_encode($model->apellido)) . '</b> su registro en el SGC de Kia ha sido realizado exitosamente. <br /><br />Para continuar con el proceso debe confirmar su correo electrónico realizando clic <a href="' . $url . Yii::app()->request->baseUrl . '/index.php/site/confirmar/t/' . $token . '-' . md5($model->id) . '">aqu&iacute;.</a>.<br></div>';
                $general .= '<div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;">'
                        . '<br /><br />Saludos cordiales, <br />SGC <br />Kia Motors Ecuador <br /><br /></div>';
                $general .= '<div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;">'
                        . '<p>Nota de descargo: La información contenida en este e-mail es confidencial y sólo puede ser utilizada por el individuo o la compañía a la cual está dirigido. Esta información no debe ser distribuida ni copiada total o parcialmente por ningún medio sin la autorización de AEKIA S.A.
La organización no asume responsabilidad sobre información, opiniones o criterios contenidos en este mail que no esté relacionada con negocios oficiales de nuestra compañía.

</p></div>';
                $general.=' <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;"><img src="images/footer_mail.jpg"/></div>
                            
                            </div>
                        </body>';
                $codigohtml = $general;
                $tipo = 'informativo';
                $headers = 'From: info@kia.com.ec' . "\r\n";
                $headers .= 'Content-type: text/html' . "\r\n";
                $email = $model->correo; //email administrador

                if ($send = sendEmailInfoD('info@kia.com.ec', html_entity_decode("Kia -  Sistema de Prospección"), $email, html_entity_decode($asunto), $codigohtml, $tipo)) {
                    //die('send email: '.$send);
                    Yii::app()->user->setFlash('success', '<div class="exitoRegistro"><img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/><br>Tu usuario ha sido creado exitosamente, por favor revisa la bandeja de entrada de tu correo electr&oacute;nico para activar la cuenta.</div>');
                    $this->redirect(array('site/registro'));
                }
            }
        }
        $ciudades = Dealercities::model()->findAll();
        $area = Area::model()->findAll();
        $this->render('registro', array("model" => $model, 'ciudades' => $ciudades, 'area' => $area));
    }

    public function actionTraerconsesionario() {
        date_default_timezone_set("America/Bogota");
        $p = new CHtmlPurifier();
        $valor = $p->purify($_POST["rs"]);
        //die('valor:'.$valor);
        $concesionarios = GrConcesionarios::model()->findAll(array('order' => 'nombre ASC', 'condition' => "id_grupo=:match", 'params' => array(':match' => (int) $valor)));
        $html = "";
        if (!empty($concesionarios)) {
            $html .='<select required title="Para seleccionar m&aacute;s de un elemento presione la tecla CTRL y realice clic en los items que desea agregar." multiple name="Usuarios[concesionario_id][]" onchange="verciudadcon(this.value)" id="Usuarios_concesionario_id" class="form-control cccc">';
            //$html .="<option>Seleccione >></option>";
            foreach ($concesionarios as $c) {
                $html .="<option value='" . $c->dealer_id . "'>" . $c->nombre . "</option>";
            }
            $html .="</select>";
            echo $html;
        } else {
            echo 0;
            die();
        }
    }

    public function actionTraerconsesionarioE() {
        date_default_timezone_set("America/Bogota");
        $p = new CHtmlPurifier();
        $valor = $p->purify($_POST["rs"]);
        $concesionarios = Dealers::model()->findAll(array('order' => 'name ASC', 'condition' => "cityid=:match", 'params' => array(':match' => (int) $valor)));
        $html = "";
        if (!empty($concesionarios)) {
            $html .='<select required name="Usuarios[dealers_id]" id="Usuarios_dealers_id" class="form-control cccc" onchange="validarPersonas()">';
            $html .="<option value='0'>Seleccione >></option>";
            foreach ($concesionarios as $c) {
                $html .="<option value='" . $c->id . "'>" . $c->name . "</option>";
            }
            $html .="</select>";
            echo $html;
        } else {
            echo 0;
            die();
        }
    }

    public function actionTraercargos() {
        date_default_timezone_set("America/Bogota");
        $p = new CHtmlPurifier();
        $valor = $p->purify($_POST["rs"]);
        $concesionarios = Cargo::model()->findAll(array('order' => 'descripcion ASC', 'condition' => "area_id=:match", 'params' => array(':match' => (int) $valor)));
        $html = "";
        if (!empty($concesionarios)) {
            $html .='<select required name="Usuarios[cargo_id]" id="Usuarios_cargo_id" class="form-control">';
            $html .="<option>Seleccione >></option>";
            foreach ($concesionarios as $c) {
                $html .="<option value='" . $c->id . "'>" . $c->descripcion . "</option>";
            }
            $html .="</select>";
            echo $html;
        } else {
            echo 0;
            die();
        }
    }

    public function actionVerificaNick() {
        date_default_timezone_set("America/Bogota");
        $p = new CHtmlPurifier();
        $valor = $p->purify($_POST["rs"]);
        $usuarios = Usuarios::model()->count(array('condition' => "usuario=:match", 'params' => array(':match' => $valor)));
        if ($usuarios > 0) {
            echo 1;
        } else {
            echo 0;
        }
        die();
    }

    public function actionVerificaEmail() {
        date_default_timezone_set("America/Bogota");
        $p = new CHtmlPurifier();
        $valor = $p->purify($_POST["rs"]);
        $usuarios = Usuarios::model()->count(array('condition' => "correo=:match", 'params' => array(':match' => $valor)));
        if ($usuarios > 0) {
            echo 1;
        } else {
            echo 0;
        }
        die();
    }

    public function actionRecuperar() {
        date_default_timezone_set("America/Bogota");
        $model = new Usuarios;
        $model->body = 'resetPasswordWithCaptcha';
        if (isset($_POST["Usuarios_correo"])) {

            if (isset($_POST['g-recaptcha-response'])) {
                $captcha = $_POST['g-recaptcha-response'];
            }
            if (!$captcha) {
                Yii::app()->clientScript->registerScript('Alerta', 'alert("Debes realizar la verificación de robot.")');
                $this->render("recuperar");
                die();
            }
            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LdpfAYTAAAAACR8BH2nzw-zy5uPb00HXY1TFQWZ&response=" . $captcha);
            $response = json_decode($response, true);


            if ($response["success"] != 1) {
                Yii::app()->clientScript->registerScript('Alerta', 'alert("Debes realizar la verificación de robot.")');
                $this->render("recuperar");
                die();
            }

            $p = new CHtmlPurifier();
            $valor = $_POST["Usuarios_correo"];
            $user = Usuarios::model()->find(array(
                'order' => 'nombres ASC',
                'condition' => "correo=:match OR usuario=:match AND estado='ACTIVO'",
                'params' => array(':match' => $p->purify($valor)))
            );
            if (!empty($user)) {
                $recuperar = new Recuperar();
                $recuperar->usuarios_id = (int) $user->id;
                $token = md5($user->id . $user->usuario);
                $recuperar->token = $token;
                $recuperar->fecha = date("Y-m-d");
                $recuperar->estado = "SOLICITADO";
                if ($recuperar->save()) {
                    $url = "https://" . $_SERVER['HTTP_HOST'];
                    require_once 'email/mail_func.php';
                    $asunto = 'Confirmaci&oacute;n de Cambio de password';
                    $general = '<body style="margin: 10px;">
                            <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;margin:auto"></div>
                                <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;">
                                <img src="images/header_mail.jpg"/></div>';
                    $general .= '<div style="width:600px;margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;">'
                            . 'Estimad@: <b>' . utf8_decode(utf8_encode($user->nombres)) . ' ' . utf8_decode(utf8_encode($user->apellido)) . '</b> su solicitud de cambio de contrase&ntilde;a ha sido realizado exitosamente, '
                            . 'por favor para continuar con el proceso usted debe confirmar su correo electrónico realizando clic <a href="' . $url . Yii::app()->request->baseUrl . '/index.php/site/Restablecerpassword/t/' . $token . '/pz/' . md5($user->id) . '">aqu&iacute;.</a>.<br></div>';
                    $general.=' <div style="width:600px;margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;"><img src="images/footer_mail.jpg"/></div>
	                            
	                            </div>
	                        </body>';
                    $codigohtml = $general;
                    $tipo = 'informativo';
                    $headers = 'From: info@kia.com.ec' . "\r\n";
                    $headers .= 'Content-type: text/html' . "\r\n";
                    $email = $user->correo; //email administrador

                    if (sendEmailInfoD('info@kia.com.ec', html_entity_decode("Kia -  Sistema de Prospección"), $email, html_entity_decode($asunto), $codigohtml, $tipo)) {

                        Yii::app()->user->setFlash('success', '<div class="exitoRegistro" style="text-align:justify"><h1>Solicitud de cambio de contrase&ntilde;a</h1><p>Estimad@ <b>' . $user->nombres . ' ' . $user->apellido . '</b> su solicitud de cambio de contrase&ntilde;a ha sido realizado exitosamente, por favor revise su correo electr&oacute;nico "<b>' . $user->correo . '</b>" para continuar con el proceso, recuerde revisar en su bandeja de span en caso de no encontrar el email de solicitud de cambio de contrase&ntilde;a.</div>');
                        $this->redirect(array('site/recuperar'));
                    }
                }
            } else {
                Yii::app()->user->setFlash('error', '<div class="alert alert-danger">
                    <strong>Error!</strong> El correo electr&oacute;nico ingresado es incorrecto, no se han encontrado usuarios relacionados con el mismo, por favor revise la informaci&oacute;n e intente nuevamente.
                </div>');
                $this->redirect(array('site/recuperar/'));
            }
        }
        $this->render('recuperar', array("model" => $model));
    }

    public function actionRestablecerpassword($t, $pz) {
        date_default_timezone_set("America/Bogota");
        $p = new CHtmlPurifier();
        $token = $p->purify($t);
        $usuario_id = $p->purify($pz);

        $recuperar = Recuperar::model()->find(array('condition' => "token=:match AND md5(usuarios_id)=:uid AND estado='SOLICITADO'", 'params' => array(':match' => $token, 'uid' => $usuario_id)));
        if (!empty($recuperar)) {
            $datetime1 = date_create($recuperar->fecha);
            $datetime2 = date_create("now");

            $interval = date_diff($datetime2, $datetime1);

            //if ($interval->i <= 100) {
            $user = Usuarios::model()->find(array('condition' => "id=:match  AND estado='ACTIVO'", 'params' => array(':match' => $recuperar->usuarios_id)));

            if (!empty($_POST["password"])) {
                if ($_POST["password"] == $_POST["repetir_password"]) {
                    $recuperar->estado = "EJECUTADO";
                    $recuperar->fecha = date('Y-m-d h:i:s');
                    if ($recuperar->update()) {
                        $user->password = md5($_POST["password"]);
                        if ($user->update()) {
                            Yii::app()->user->setFlash('success', '<div class="exitoRegistro" style="text-align:justify"><h1>Cambio de contrase&ntilde;a</h1><p>Estimad@ <b>' . $user->nombres . ' ' . $user->apellido . '</b> su solicitud de cambio de contrase&ntilde;a ha sido realizado exitosamente,ahora puede ingresar al sistema con su nueva contrase&ntilde;a desde <a href="' . Yii::app()->createUrl('site/login') . '">aqu&iacute;.</a></div>');
                            $this->redirect(array("site/listocambio"));
                        }
                    }
                }
            }

            $this->render('restablecerpassword', array("t" => $t, "pz" => $pz));
            //}
        } else
            throw new CHttpException(404, 'Lo sentimos no se puede desplegar esta s' . utf8_encode("información") . '.');
    }

    public function actionListocambio() {
        $this->render('listocambio');
    }

    public function actionConfirmar($t) {
        date_default_timezone_set("America/Bogota");
        $p = new CHtmlPurifier();
        $token = $p->purify($t);
        $token = explode("-", $token);
        $user = Usuarios::model()->find(array('condition' => "md5(id)=:match  AND estado='PENDIENTE'", 'params' => array(':match' => $token[1])));
        if (!empty($user)) {
            $verificar = md5($user->correo . '--' . $user->id . '--' . $user->usuario);
            if ($verificar == $token[0]) {
                $cargoAdm = Cargo::model()->find(array('condition' => "codigo=:match  AND estado='ACTIVO'", 'params' => array(':match' => Constantes::ADM_SITIO_CODIGO_USUARIO)));
                $administradores = Usuarios::model()->findAll(array('condition' => "cargo_id=:match  AND estado='ACTIVO'", 'params' => array(':match' => (int) $cargoAdm->id)));
                if (!empty($administradores)) {
                    require_once 'email/mail_func.php';
                    $url = "https://" . $_SERVER['HTTP_HOST'];
                    $asunto = 'Verificaci&oacute;n de Usuario';

                    $general = '<body style="margin: 10px;">
                            <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;margin:auto">
                                <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;" ><img src="images/header_mail.jpg"/></div>';
                    $general .= '<div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;margin:auto">El usuario: <b>' . utf8_decode(utf8_encode($user->nombres)) . ' ' . utf8_decode(utf8_encode($user->apellido)) . '</b> se ha registrado  en la intranet de Kia con el cargo de :<b>' . strtoupper($user->cargo->descripcion) . '</b> y con el correo : <b>' . $user->correo . '</b>, usted ahora debe confirmar o rechazar el registro de este usuario realizando un clic <a href="' . $url . Yii::app()->request->baseUrl . '/index.php/uusuarios/confirmar/' . $user->id . '">aqu&iacute;</a>.</div>';
                    $general.=' <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;margin:auto;text-align:left"><img src="images/footer_mail.jpg"/></div>
									
								</div>
							</body>';
                    $codigohtml = $general;
                    $tipo = 'informativo';
                    $headers = 'From: info@kia.com.ec' . "\r\n";
                    $headers .= 'Content-type: text/html' . "\r\n";
                    $email = "";
                    $cc = array();
                    $estado = 0;
                    foreach ($administradores as $m) {
                        if ($estado == 0) {
                            $email = $m->correo;
                            $estado = 1;
                        } else {
                            array_push($cc, $m->correo);
                        }
                    }
                    /* echo $email;
                      print_r($cc);
                      die(); */

                    if (sendEmailFunction('info@kia.com.ec', html_entity_decode("Kia -  Sistema de Prospecci&oacute;n"), $email, html_entity_decode($asunto), $codigohtml, $tipo, $cc, '', '')) {
                        $user->estado = "CONFIRMADO";
                        if ($user->update()) {
                            Yii::app()->user->setFlash('success', '<div class="exitoRegistro" style="text-align:justify"><h1>Correo Electr&oacute;nico Confirmado</h1><p>Estimad@ <b>' . $user->nombres . ' ' . $user->apellido . '</b> su correo ha sido confirmado, ahora su registro deber&aacute; ser aprobado por el administrador para poder acceder a la intranet, cuando este proceso se realice recibirá un correo electr&oacute;nico con su contrase&ntilde;a de acceso.</div>');
                            $this->redirect(array("site/listocambio"));
                        } else {
                            throw new CHttpException(404, 'Lo sentimos no se puede desplegar esta ' . utf8_encode("información") . '.');
                        }
                    } else {
                        throw new CHttpException(404, 'Lo sentimos no se puede desplegar esta ' . utf8_encode("información") . '.');
                    }
                }
            }
        } else {
            throw new CHttpException(404, 'Lo sentimos no se puede desplegar esta ' . utf8_encode("información") . '.');
        }
        die();
    }

    public function actionComentario() {
        $model = Qircomentario::model()->findAll();
        if (!empty($model)) {
            foreach ($model as $m) {
                echo $m->num_reporte . '<br>';
                $qir = Qir::model()->find(array('condition' => "num_reporte='" . $m->num_reporte . "'"));
                if (!empty($qir)) {

                    $m->qirId = $qir->id;
                    $m->update();
                }
            }
        }
    }

    public function actionvinad() {
        $model = Qiradicional::model()->findAll();
        if (!empty($model)) {
            foreach ($model as $m) {
                echo $m->num_reporte . '<br>';
                $qir = Qir::model()->find(array('condition' => "num_reporte='" . $m->num_reporte . "'"));
                if (!empty($qir)) {

                    $m->qirId = $qir->id;
                    $m->update();
                }
            }
        }
    }

    public function actionfiles() {
        $model = Qirfiles::model()->findAll();
        if (!empty($model)) {
            foreach ($model as $m) {
                echo $m->num_reporte . '<br>';
                $qir = Qir::model()->find(array('condition' => "num_reporte='" . $m->num_reporte . "'"));
                if (!empty($qir)) {

                    $m->qirId = $qir->id;
                    $m->update();
                }
            }
        }
    }

    public function actionConsultarUsuarioEncuesta() {
        date_default_timezone_set("America/Bogota");
        $p = new CHtmlPurifier();
        $opcion = $p->purify($_POST['rs']);
        if ($opcion == 1) {

            $ciudad = $p->purify($_POST['ciudad']);
            $concesionario = $p->purify($_POST['concesionario']);
            $desde = $p->purify($_POST['desde']);
            $hasta = $p->purify($_POST['hasta']);
            $where = '';
            if (!empty($ciudad) && $ciudad > 0) {
                if (empty($where)) {
                    $where .= "where gt.test_drive = 1 and gt.order=1 and gi.ciudad_conc =" . $ciudad;
                } else {
                    $where .= " and gt.test_drive = 1 and gt.order=1  and gi.ciudad_conc =" . $ciudad;
                }
            }

            if (!empty($concesionario) && $concesionario > 0) {
                if (empty($where)) {
                    $where .= "where gt.test_drive = 1 and gt.order=1 and gi.dealer_id =" . $concesionario;
                } else {
                    $where .= " and gt.test_drive = 1 and gt.order=1  and gi.dealer_id =" . $concesionario;
                }
            }

            if (!empty($desde) && !empty($hasta)) {
                if (empty($where)) {
                    $where .= "where gt.test_drive = 1 and gt.order=1 and gt.fecha >='" . $desde . "' and gt.fecha <='" . $hasta . "'";
                } else {
                    $where .= " and gt.test_drive = 1 and gt.order=1  and gt.fecha >='" . $desde . "' and gt.fecha <='" . $hasta . "'";
                }
            }
            if (empty($where)) {
                $where = ' WHERE gt.test_drive = 1 and gt.order=1';
            }
            $sql = 'SELECT DISTiNCT gi.nombres, gi.apellidos, gi.email, gi.celular, gi.telefono_oficina, gi.ciudad_conc
                    FROM gestion_informacion gi 
                        inner join gestion_test_drive gt 
                            on gi.id = gt.id_informacion
                    ' . $where . ' ORDER BY gi.fecha DESC';
            //echo $sql;
            $persona = Yii::app()->db->createCommand($sql)->queryAll();
            echo count($persona);
        }
        if ($opcion == 4) {

            $ciudad = $p->purify($_POST['ciudad']);
            $concesionario = $p->purify($_POST['concesionario']);
            $desde = $p->purify($_POST['desde']);
            $hasta = $p->purify($_POST['hasta']);
            $where = '';
            if (!empty($ciudad) && $ciudad > 0) {
                if (empty($where)) {
                    $where .= "where gt.test_drive = 0 and gt.order=1 and gi.ciudad_conc =" . $ciudad;
                } else {
                    $where .= " and gt.test_drive = 0 and gt.order=1  and gi.ciudad_conc =" . $ciudad;
                }
            }

            if (!empty($concesionario) && $concesionario > 0) {
                if (empty($where)) {
                    $where .= "where gt.test_drive = 0 and gt.order=1 and gi.dealer_id =" . $concesionario;
                } else {
                    $where .= " and gt.test_drive = 0 and gt.order=1  and gi.dealer_id =" . $concesionario;
                }
            }

            if (!empty($desde) && !empty($hasta)) {
                if (empty($where)) {
                    $where .= "where gt.test_drive = 0 and gt.order=1 and gt.fecha >='" . $desde . "' and gt.fecha <='" . $hasta . "'";
                } else {
                    $where .= " and gt.test_drive = 0 and gt.order=1  and gt.fecha >='" . $desde . "' and gt.fecha <='" . $hasta . "'";
                }
            }
            if (empty($where)) {
                $where = ' WHERE gt.test_drive = 0 and gt.order=1';
            }
            $sql = 'SELECT DISTiNCT gi.nombres, gi.apellidos, gi.email, gi.celular, gi.telefono_oficina, gi.ciudad_conc
                    FROM gestion_informacion gi 
                        inner join gestion_test_drive gt 
                            on gi.id = gt.id_informacion
                    ' . $where . ' ORDER BY gi.fecha DESC';
            //echo $sql;
            $persona = Yii::app()->db->createCommand($sql)->queryAll();
            echo count($persona);
        }
        if ($opcion == 2) {

            $ciudad = $p->purify($_POST['ciudad']);
            $concesionario = $p->purify($_POST['concesionario']);
            $desde = $p->purify($_POST['desde']);
            $hasta = $p->purify($_POST['hasta']);
            $where = '';
            if (!empty($ciudad) && $ciudad > 0) {
                if (empty($where)) {
                    $where .= "where gi.ciudad_conc =" . $ciudad;
                } else {
                    $where .= " and gi.ciudad_conc =" . $ciudad;
                }
            }

            if (!empty($concesionario) && $concesionario > 0) {
                if (empty($where)) {
                    $where .= "where gi.dealer_id =" . $concesionario;
                } else {
                    $where .= " and gi.dealer_id =" . $concesionario;
                }
            }

            if (!empty($desde) && !empty($hasta)) {
                if (empty($where)) {
                    $where .= "where gt.fecha >='" . $desde . "' and gt.fecha <='" . $hasta . "'";
                } else {
                    $where .= " and gt.fecha >='" . $desde . "' and gt.fecha <='" . $hasta . "'";
                }
            }

            $sql = 'SELECT DISTiNCT gi.nombres, gi.apellidos, gi.email, gi.celular, gi.telefono_oficina, gi.ciudad_conc
                    FROM gestion_informacion gi 
                        inner join gestion_nueva_cotizacion gt 
                            on gi.id_cotizacion = gt.id
                    ' . $where;
            //echo $sql;
            $persona = Yii::app()->db->createCommand($sql)->queryAll();
            echo count($persona);
        }
        if ($opcion == 3) {

            $ciudad = $p->purify($_POST['ciudad']);
            $concesionario = $p->purify($_POST['concesionario']);
            $desde = $p->purify($_POST['desde']);
            $hasta = $p->purify($_POST['hasta']);
            $where = '';
            if (!empty($ciudad) && $ciudad > 0) {
                if (empty($where)) {
                    $where .= "where a.cityid =" . $ciudad;
                } else {
                    $where .= " and a.cityid =" . $ciudad;
                }
            }

            if (!empty($concesionario) && $concesionario > 0) {
                if (empty($where)) {
                    $where .= "where a.dealerid =" . $concesionario;
                } else {
                    $where .= " and a.dealerid =" . $concesionario;
                }
            }

            if (!empty($desde) && !empty($hasta)) {
                if (empty($where)) {
                    $where .= "where e.fecha >='" . $desde . "' and e.fecha <='" . $hasta . "'";
                } else {
                    $where .= " and e.fecha >='" . $desde . "' and e.fecha <='" . $hasta . "'";
                }
            }

            $sql = 'SELECT DISTINCT count(*) as total
                    FROM encuestas e 
                    inner join atencion_detalle a 
                    on e.id_atencion_detalle = e.id_atencion_detalle 
                    ' . $where . ' LIMIT 100';
            // echo $sql;
            $persona = Yii::app()->db2->createCommand($sql)->queryAll();
            echo ($persona[0]['total']);
        }
    }

    public function actionTraerArea() {
        $p = new CHtmlPurifier();
        $valor = $p->purify($_POST["rs"]);
        $model = Area::model()->findAll(array('condition' => 'tipo=' . $valor));
        if (!empty($model)) {
            echo '<select class="form-control" name="Cargo[area_id]" id="Cargo_area_id" onchange="buscarCargo(this.value)">';
            foreach ($model as $m) {
                echo '<option value="' . $m->id . '">' . $m->descripcion . '</option>';
            }
            echo '</select>';
        } else {
            echo '0';
        }
    }

    public function actionTraerciudadcon() {
        $p = new CHtmlPurifier();
        $valor = $p->purify($_POST["rs"]);
        //die($valor);
        $model = GrConcesionarios::model()->find(array('condition' => 'dealer_id=' . $valor));
        if ($model->provincia > 0 && $model->dealer_id > 0) {
            $provincia = TblProvincias::model()->find(array('condition' => 'id_provincia=' . $model->provincia));
            $dealer = Dealers::model()->findByPk($model->dealer_id);
            if (!empty($provincia) && !empty($dealer)) {
                echo '
					<label class = "col-sm-2 control-label">Provincia</label>
					<div class="col-sm-4">
						<p style="font-size:13px; font-weight:bold;padding-top:-5px;" id="ppp">' . strtoupper($provincia->nombre) . '</p>
						<input type="hidden" name="Usuarios[provincia_id]" id="Usuarios_provincia_id" value="' . $provincia->id_provincia . '">
					</div>
					<label class = "col-sm-2 control-label">Direcci&oacute;n</label>
					<div class="col-sm-4">
						<p style="font-size:13px; font-weight:bold;padding-top:-5px;" id="pppc"> ' . strtoupper($dealer->name) . '</p>
						<input type="hidden" name="Usuarios[dealers_id]" id="Usuarios_dealers_id" value="' . $dealer->id . '">
					</div>';
            } else {
                echo 0;
                die();
            }
        } else {
            echo '
			<label class = "col-sm-2 control-label">Provincia</label>
			<div class="col-sm-4">
				<p style="font-size:13px; font-weight:bold;padding-top:-5px;">TODAS</p>
				<input type="hidden" name="Usuarios[provincia_id]" id="Usuarios_provincia_id" value="0">
			</div>
			<label class = "col-sm-2 control-label">Direcci&oacute;n</label>
			<div class="col-sm-4">
				<p style="font-size:13px; font-weight:bold;padding-top:-5px;"> --- </p>
			</div>';
        }
    }

    public function actionSubpregunta() {
        $p = new CHtmlPurifier();
        $valor = $p->purify($_POST["rs"]);

        $valors = $p->purify($_POST["cl"]);
        $preguntas = Cpregunta::model()->findAll(array('condition' => 'copcionpregunta_id=' . $valor));

        if (!empty($preguntas)) {
            echo '<div id="dd' . $valors . '" class="ddopcion">';
            foreach ($preguntas as $value) {
                if ($value->ctipopregunta_id == 1) {
                    echo '<div class="row pad-all">';
                    echo $value->descripcion . '</div>';
                    echo '<div class="row"><textarea class="required" onkeypress="return ' . $value->tipocontenido . '(event);" id="txtpregunta' . $value->id . '" name="respuesta[' . $value->id . ']" placeholder="Ingrese la respuesta aquí"></textarea>';
                    echo '</div>';
                } else if ($value->ctipopregunta_id == 2) {
                    echo '<div class="row pad-all">';
                    echo $value->descripcion . '</div>';
                    $opciones = Copcionpregunta::model()->findAll(array('condition' => 'cpregunta_id=' . $value->id));
                    if (!empty($opciones)) {
                        echo '<div class="row"><div class="highlight">';
                        $chk = 'checked="true"';
                        $chk = '';
                        foreach ($opciones as $op) {
                            echo '<div class="radio">
								  <label class="lblrespuesta">
									<input  id="ckk' . $op->id . '"  class="required" type="checkbox" onclick="verificarSubPregunta(1,this.id,' . $op->id . ',' . $value->id . ')" ' . $chk . ' name="respuesta[' . $value->id . '][]" id="respuestaCheck" value="' . $op->detalle . '|' . $op->id . '">
									' . $op->detalle . '
								  </label>';
                            echo '<div id="divopcion-' . $op->id . '" class=" cl-' . $value->id . '"></div>';
                            echo '</div>';
                            $chk = '';
                        }
                        echo '</div></div>';
                    }
                    //
                } else if ($value->ctipopregunta_id == 3) {
                    echo '<div class="row pad-all">';
                    echo $value->descripcion . '</div>';
                    $opciones = Copcionpregunta::model()->findAll(array('condition' => 'cpregunta_id=' . $value->id));
                    if (!empty($opciones)) {
                        echo '<div class="row"><div class="highlight">';
                        foreach ($opciones as $op) {
                            $vv = (!empty($op->valor)) ? $op->valor : 0;
                            echo '<div class="radio">
								  <label class="lblrespuesta">
									<input class="required" type="radio" onclick="oculta(' . $value->id . ',' . $vv . '); verificarSubPregunta(0,this.id,' . $op->id . ',' . $value->id . ')"  name="respuesta[' . $value->id . '][respuesta]" id="respuestaOption" value="' . $op->detalle . '|' . $op->id . '">
									' . $op->detalle . '
										</label>';
                            if (!empty($op->valor)) {
                                echo '<input class="required form-control" style="margin-top:10px;" type="text" class="form-control" name="respuesta[' . $value->id . '][justifica]" id="j' . $value->id . '" placeholder="&iquest;Por qu&eacute;?">';
                            }
                            echo '<div id="divopcion-' . $op->id . '"  class=" cl-' . $value->id . '"></div>';
                            echo '</div>';
                        }
                        echo '</div></div>';
                    }
                    //
                } else {
                    echo '<div class="row pad-all">';
                    echo $value->descripcion . '</div>';
                    $opciones = Copcionpregunta::model()->findAll(array('condition' => 'cpregunta_id=' . $value->id));
                    $matriz = Cmatrizpregunta::model()->findAll(array('condition' => 'cpregunta_id=' . $value->id));
                    if (!empty($opciones) && !empty($matriz)) {
                        echo '<div class="row"><div class="highlight">';
                        echo '<table>';
                        echo '<tr>';
                        echo '<td><b>Opci&oacute;n</b></td>';
                        foreach ($matriz as $key) {
                            echo '<td>' . $key->detalle . '</td>';
                        }
                        echo '</tr>';
                        foreach ($opciones as $op) {
                            echo '<tr>';
                            echo '<td><input type="hidden" name="respuesta[' . $op->id . '][idop]" value="' . $op->id . '">' . $op->detalle . '</td>';
                            foreach ($matriz as $key) {
                                echo '<td>';
                                echo '<div class="radio">
                                              <label class="lblrespuesta">
                                              
                                                <input type="radio" class="required" checked="true" name="respuesta[' . $value->id . '][' . $op->id . ']" id="respuestaOptionMatriz" value="' . $op->detalle . ' - ' . $key->detalle . ' ( ' . $key->valor . ' )|' . $key->id . '">
                                                
                                              </label>
                                            </div>';
                                echo '</td>';
                            }

                            echo '</tr>';
                        }
                        echo '</table>';
                        echo '</div></div>';
                    }
                }
            }
            echo '</div>';
        } else {
            echo 0;
            die();
        }
    }

    public function actionUpdateuser() {
        $p = new CHtmlPurifier();
        $id = $p->purify($_POST["id"]);
        $nombre = $p->purify($_POST["nombre"]);
        $telefono = $p->purify($_POST["telefono"]);
        $celular = $p->purify($_POST["celular"]);
        $ciudad = $p->purify($_POST["ciudad"]);
        $email = $p->purify($_POST["email"]);
        $fecha = $p->purify($_POST["fecha"]);
        $user = Cencuestados::model()->findByPk((int) $id);
        if (!empty($user)) {
            $user->nombre = $nombre;
            $user->telefono = $telefono;
            $user->celular = $celular;
            $user->email = $email;
            $user->ciudad = $ciudad;
            $user->fechanacimiento = $fecha;
            if ($user->update()) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    public function actionIcal($startTime, $endTime, $subject, $desc, $location, $to_name, $conc) {

        if (isset($_SERVER['HTTP_USER_AGENT'])) {

            $mobile_agents = '!(android)!i';

            if (preg_match($mobile_agents, $_SERVER['HTTP_USER_AGENT'])) {

                $android = true;
            } else {

                $android = false;
            }
        }

        header('Content-type: text/calendar; charset=utf-8');

        if ($android) {

            header('Content-Disposition: inline; filename=calendar.ics');
        } else {

            header('Content-Disposition: inline; filename=calendar.vcs');
        }



        //$start = date("Ymd\THis", strtotime($startTime));
        //$end = date("Ymd\THis", strtotime($endTime));
        //echo 'start time: '.$start.', end Time: '.$end.'<br>';

        $from_name = 'Asiauto Mariana de Jesús';

        $from_address = 'alkanware@gmail.com';

        //$to_name = '';

        $to_address = 'carli-c@hotmail.com';

        $domain = 'www.kia.com.ec';

        if ($conc == 'si') { // si se recibe una id del concesionario
            $location = $this->getConcesionario($location);
        }

        $ical = 'BEGIN:VCALENDAR' . "\r\n" .
                'PRODID:-//Microsoft Corporation//Outlook 10.0 MIMEDIR//EN' . "\r\n" .
                'VERSION:2.0' . "\r\n" .
                'METHOD:REQUEST' . "\r\n" .
                'BEGIN:VTIMEZONE' . "\r\n" .
                'TZID:America/Guayaquil' . "\r\n" .
                'BEGIN:STANDARD' . "\r\n" .
                'DTSTART:20091101T020000' . "\r\n" .
                'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=1SU;BYMONTH=11' . "\r\n" .
                'TZOFFSETFROM:-0400' . "\r\n" .
                'TZOFFSETTO:-0500' . "\r\n" .
                'TZNAME:EST' . "\r\n" .
                'END:STANDARD' . "\r\n" .
                'BEGIN:DAYLIGHT' . "\r\n" .
                'DTSTART:20090301T020000' . "\r\n" .
                'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=2SU;BYMONTH=3' . "\r\n" .
                'TZOFFSETFROM:-0500' . "\r\n" .
                'TZOFFSETTO:-0400' . "\r\n" .
                'TZNAME:EDST' . "\r\n" .
                'END:DAYLIGHT' . "\r\n" .
                'END:VTIMEZONE' . "\r\n" .
                'BEGIN:VEVENT' . "\r\n" .
                'ORGANIZER;CN="' . $from_name . '":MAILTO:' . $from_address . "\r\n" .
                'ATTENDEE;CN="' . $to_name . '";ROLE=REQ-PARTICIPANT;RSVP=TRUE:MAILTO:' . $to_address . "\r\n" .
                'LAST-MODIFIED:' . date("Ymd\TGis") . "\r\n" .
                'UID:' . date("Ymd\TGis", strtotime($startTime)) . rand() . "@" . $domain . "\r\n" .
                'DTSTAMP:' . date("Ymd\TGis") . "\r\n" .
                'DTSTART;TZID="America/Guayaquil":' . date("Ymd\THis", strtotime($startTime)) . "\r\n" .
                'DTEND;TZID="America/Guayaquil":' . date("Ymd\THis", strtotime($endTime)) . "\r\n" .
                'TRANSP:OPAQUE' . "\r\n" .
                'SEQUENCE:1' . "\r\n" .
                'SUMMARY:' . $subject . "\r\n" .
                'LOCATION:' . $location . "\r\n" .
                'CLASS:PUBLIC' . "\r\n" .
                'PRIORITY:5' . "\r\n" .
                'BEGIN:VALARM' . "\r\n" .
                'TRIGGER:-PT15M' . "\r\n" .
                'ACTION:DISPLAY' . "\r\n" .
                'DESCRIPTION:Reminder' . "\r\n" .
                'END:VALARM' . "\r\n" .
                'END:VEVENT' . "\r\n" .
                'END:VCALENDAR' . "\r\n";

        echo $ical;
    }

    /* INICIO DE VENTAS*------------------------------ */

    public function actionGetTemas() {
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        $criteria = new CDbCriteria(array(
            'order' => 'name'
        ));
        $menu = Menu::model()->findAll($criteria);
        $data = '<option value="">Selecciona un tema</option>';
        foreach ($menu as $m) {
            $data .= '<option value="' . $m['id'] . '"';
            if ($m['id'] == $id) {
                $data .= ' selected = "selected">';
            } else {
                $data .= '>';
            }
            $data .= $m['name'] . '</option>';
        }
        $options = array('options' => $data);
        echo json_encode($options);
    }

    public function actionGetSubtemas() {
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        $sid = isset($_POST["sid"]) ? $_POST["sid"] : "";
        $criteria = new CDbCriteria(array(
            'order' => 'name'
        ));
        $menu = Submenu::model()->findAll($criteria);
        $data = '<option value="">Selecciona un subtema</option>';
        foreach ($menu as $m) {
            $data .= '<option value="' . $m['id'] . '"';
            if ($m['id'] == $sid) {
                $data .= ' selected = "selected">';
            } else {
                $data .= '>';
            }
            $data .= $m['name'] . '</option>';
        }
        $options = array('options' => $data);
        echo json_encode($options);
    }

    public function actionGetProvinciasDom() {
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        $criteria = new CDbCriteria(array(
            'order' => 'nombre'
        ));
        $menu = TblProvincias::model()->findAll($criteria);
        $data = '<option value="">Selecciona una provincia</option>';
        foreach ($menu as $m) {
            $data .= '<option value="' . $m['id_provincia'] . '"';
            if ($m['id_provincia'] == $id) {
                $data .= ' selected = "selected">';
            } else {
                $data .= '>';
            }
            $data .= $m['nombre'] . '</option>';
        }
        $options = array('options' => $data);
        echo json_encode($options);
    }

    public function actionGetCiudadDom() {
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        $ciudad = isset($_POST["ciudad"]) ? $_POST["ciudad"] : "";
        $criteria = new CDbCriteria(array(
            'condition' => "id_provincia={$id}",
            'order' => 'nombre asc'
        ));
        $menu = TblCiudades::model()->findAll($criteria);
        $data = '';
        foreach ($menu as $m) {
            $data .= '<option value="' . $m['id_ciudad'] . '"';
            if ($m['id_ciudad'] == $ciudad) {
                $data .= ' selected = "selected">';
            } else {
                $data .= '>';
            }
            $data .= $m['nombre'] . '</option>';
        }
        $options = array('options' => $data);
        echo json_encode($options);
    }

    public function actionGetProvinciaConc() {
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        $criteria = new CDbCriteria(array(
            'condition' => "estado='s'",
            'order' => 'nombre'
        ));
        $menu = Provincias::model()->findAll($criteria);
        $data = '<option value="">Selecciona una provincia</option>';
        foreach ($menu as $m) {
            $data .= '<option value="' . $m['id_provincia'] . '"';
            if ($m['id_provincia'] == $id) {
                $data .= ' selected = "selected">';
            } else {
                $data .= '>';
            }
            $data .= $m['nombre'] . '</option>';
        }
        $options = array('options' => $data);
        echo json_encode($options);
    }

    public function actionGetCiudadConc() {
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        $ciudad = isset($_POST["ciudad"]) ? $_POST["ciudad"] : "";
        $criteria = new CDbCriteria(array(
            'condition' => "id_provincia='{$id}'",
            'order' => 'name'
        ));
        $data = '';
        $menu = Dealercities::model()->findAll($criteria);
        foreach ($menu as $m) {
            $data .= '<option value="' . $m['id'] . '"';
            if ($m['id'] == $ciudad) {
                $data .= ' selected = "selected">';
            } else {
                $data .= '>';
            }
            $data .= $m['name'] . '</option>';
        }
        $options = array('options' => $data);
        echo json_encode($options);
    }

    public function actionGetConc() {
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        $ciudad = isset($_POST["ciudad"]) ? $_POST["ciudad"] : "";
        $conc = isset($_POST["conc"]) ? $_POST["conc"] : "";
        $criteria = new CDbCriteria(array(
            'condition' => "cityid='{$ciudad}'",
            'order' => 'name'
        ));
        $menu = Dealers::model()->findAll($criteria);
        $data = '';
        foreach ($menu as $m) {
            $data .= '<option value="' . $m['id'] . '"';
            if ($m['id'] == $conc) {
                $data .= ' selected = "selected">';
            } else {
                $data .= '>';
            }
            $data .= $m['name'] . '</option>';
        }
        $options = array('options' => $data);
        echo json_encode($options);
    }

    public function actionGetVec() {
        //die('enter getvec');
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion='{$id}'", 'order' => 'fecha DESC'
        ));
        $menu = GestionVehiculo::model()->findAll($criteria);
        //die('num id: '.$id);
        $accesorios = '';
        $data = '<div class="table-responsive">
            <table class="tables tablesorter" id="keywords">
                <thead>
                    <tr>
                        <th><span>Modelo</span></th>
                        <th><span>Versión</span></th>
                        <th><span>Necesidad</span></th>
                        <th><span>Consulta</span></th>
                        <th><span>Lista de Precios</span></th>
                    </tr>
                </thead>
                <tbody>';
        foreach ($menu as $m):
            if ($m['precio_accesorios'] == '') {
                $accesorios = 'No';
            } else {
                $accesorios = 'Si';
            }
            $data .= '<tr>
                <td>' . $this->getModel($m['modelo']) . '</td>
                <td>' . $this->getVersion($m['version']) . '</td>
                <td>' . $this->getNecesidad($m['id_informacion']) . '</td>    
                <!--<td><a href="' . Yii::app()->createUrl('gestionVehiculo/update', array('id' => $m['id'], 'id_informacion' => $m['id_informacion'])) . '" class="btn btn-primary btn-xs btn-danger">Editar</a>                              
                </td>-->
                <td><button class="btn btn-xs btn-success">Consulta</button></td>
                <td><a href="' . Yii::app()->request->baseUrl . '/images/Lista-de-Precios-Nov2015.pdf" target="_blank" class="btn btn-xs btn-default">Ver Precios</a></td>
                </tr>';
        endforeach;
        $data .= '</tbody></table></div>';
        $options = array('options' => $data, 'count' => count($menu));
        echo json_encode($options);
    }

    public function actionCreateVec() {
        //die('enter createVec');
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        $data = '';
        $data .= '<form onsubmit="return false;" onkeypress=" if(event.keyCode == 13){ send(); } " id="gestion-vehiculo-form" action="/intranet/callcenter/index.php/gestionVehiculo/create/' . $id . '" method="post">                     <div class="col-md-6">
                          
                            <div class="row">
                                <input type="hidden" name="GestionVehiculo[id_informacion]" id="GestionVehiculo_id_informacion" value="12">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="GestionVehiculo_modelo" class="required">Modelo <span class="required">*</span></label>                                    <select class="form-control" name="GestionVehiculo[modelo]" id="GestionVehiculo_modelo">
                                    <option value="" selected="selected">--Escoja un Modelo--</option>
                                    <option value="84">Picanto R</option>
                                    <option value="85">Rio R</option>
                                    <option value="24">Cerato Forte</option>
                                    <option value="90">Cerato R</option>
                                    <option value="89">Óptima Híbrido</option>
                                    <option value="88">Quoris</option>
                                    <option value="20">Carens R</option>
                                    <option value="11">Grand Carnival</option>
                                    <option value="21">Sportage Active</option>
                                    <option value="83">Sportage R</option>
                                    <option value="10">Sorento</option>
                                    <option value="25">K 2700 Cabina Simple</option>
                                    <option value="87">K 2700 Cabina Doble</option>
                                    <option value="86">K 3000</option>
                                    </select>
                                    </div>
                                <div class="col-md-6">
                                    <label for="GestionVehiculo_version">Version</label>
                                    <select class="form-control" name="GestionVehiculo[version]" id="GestionVehiculo_version">
                                        <option value="" selected="selected">Escoja una versión</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="GestionVehiculo_precio" class="required">Precio <span class="required">*</span></label>                                    <input size="45" maxlength="45" class="form-control" name="GestionVehiculo[precio]" id="GestionVehiculo_precio" type="text">                                                                    </div>
                                <div class="col-md-6">
                                    <label for="GestionVehiculo_dispositivo">Dispositivo</label>                                    <input size="45" maxlength="45" class="form-control" name="GestionVehiculo[dispositivo]" id="GestionVehiculo_dispositivo" type="text">                                                                    </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="GestionVehiculo_accesorios">Accesorios</label>                                    <input size="45" maxlength="45" class="form-control" name="GestionVehiculo[accesorios]" id="GestionVehiculo_accesorios" type="text">                                                                    </div>
                                <div class="col-md-6">
                                    <label for="GestionVehiculo_seguro">Seguro</label>
                                    <input size="45" maxlength="45" class="form-control" name="GestionVehiculo[seguro]" id="GestionVehiculo_seguro" type="text">                                                                    </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="GestionVehiculo_total">Total</label>
                                    <input size="45" maxlength="45" class="form-control" name="GestionVehiculo[total]" id="GestionVehiculo_total" type="text">                                                                    </div>
                                <div class="col-md-6">
                                    <label for="GestionVehiculo_plazo">Plazo</label>
                                    <input size="45" maxlength="45" class="form-control" name="GestionVehiculo[plazo]" id="GestionVehiculo_plazo" type="text">                                                                    </div>
                            </div>
                            <div class="row">                                
                                <div class="col-md-6">
                                    <label for="GestionVehiculo_forma_pago">Forma Pago</label>                                    <input size="45" maxlength="45" class="form-control" name="GestionVehiculo[forma_pago]" id="GestionVehiculo_forma_pago" type="text">                                                                    </div>
                            </div>
                            <div class="row buttons">
                            <div class="col-md-8">
                                <input class="btn btn-danger" onclick="send();" type="submit" name="yt0" value="Crear">
                                <input class="btn btn-primary" onclick="cancelVec();" type="submit" name="yt0" value="Cancelar">
                            </div>
                        </div>
                        </div>
                        
                        
                        </form>';
        $options = array('options' => $data);
        echo json_encode($options);
    }

    /**
     * List all vehicles for a client id
     * @param integer $id the ID of the vehicle's client to be displayed
     */
    public function actionPresentacion($id = NULL) {
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion='{$id}'"
        ));
        $vec = GestionVehiculo::model()->findAll($criteria);
        $this->render('presentacion', array('id' => $id, 'vec' => $vec));
    }

    /**
     * List all vehicles for a client id
     * @param integer $id the ID of the vehicle's client to be displayed
     */
    public function actionNegociacion($id = NULL) {
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion='{$id}'"
        ));
        $vec = GestionVehiculo::model()->findAll($criteria);
        $this->render('negociacion', array('id' => $id, 'vec' => $vec));
    }

    public function actionCierre($id = NULL) {
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion='{$id}'"
        ));
        $vec = GestionVehiculo::model()->findAll($criteria);
        $this->render('cierre', array('id' => $id, 'vec' => $vec));
    }

    /**
     * Lista todos los vehiculo para financiamiento. Si tiene el Test Drive
     * @param type $id
     */
    public function actionFinanciamiento($id = NULL) {
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion='{$id}' AND test_drive = 1"
        ));
        $vec = GestionTestDrive::model()->findAll($criteria);
        $this->render('financiamiento', array('id' => $id, 'vec' => $vec));
    }

    public function actionGetPrice() {
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        $criteria = new CDbCriteria(array(
            'condition' => "id_versiones='{$id}'"
        ));
        $vec = Versiones::model()->find($criteria);
        $options = array('options' => $vec->precio);
        echo json_encode($options);
    }

    public function actionGetCedula() {
        $model = new GestionNuevaCotizacion;
        $id_responsable = Yii::app()->user->getId();
        $dealer_id = $this->getDealerId($id_responsable);
        $id_nueva_cotizacion;
        // id cedula a ser buscada
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        // fuente showroom, exhibicion, prospeccion
        $fuente = isset($_POST["fuente"]) ? $_POST["fuente"] : "";

        //die('id: '.$id);
        $criteria = new CDbCriteria(array(
            'condition' => "cedula='{$id}'"
        ));
        $data = '';
        $result = FALSE;
        $ced = GestionInformacion::model()->count($criteria);
        $cd = GestionInformacion::model()->findAll($criteria);
        //die('ced: '.$ced);
        if ($ced > 0) {
            $result = TRUE;
            $data = '<div class="row"><h1 class="tl_seccion">Cliente existente</h1></div>'
                    . '<div class="row"><div class="col-md-12">'
                    . '<div class="table-responsive">'
                    . '<table class="tables tablesorter" id="keywords">
                <thead>
                    <tr>
                        <th><span>Status</span></th>
                        <th><span>Nombres</span></th>
                        <th><span>Apellidos</span></th>
                        <th><span>Cédula</span></th>
                        <th><span>Concesionario</span></th>
                        <th><span>Fecha Registro</span></th>
                        <th><span>Modelo</span></th>
                        <th><span>Edición</span></th>
                    </tr>
                </thead>
                <tbody>';


            foreach ($cd as $value) {
                $criteria = new CDbCriteria(array(
                    'condition' => "id_informacion = {$value['id']}"
                ));
                $countvec = GestionVehiculo::model()->count($criteria);


                // sacar id informacion para obtener el paso donde esta el cliente
                $criteria2 = new CDbCriteria();
                //die('value: '.$value['id']);
                $criteria2->condition = "id_informacion = {$value['id']}";
                $gd = GestionDiaria::model()->find($criteria2);
                /* echo '<pre>';
                  print_r($gd);
                  echo '</pre>';
                  die(); */
                if (empty($gd)) {
                    $paso = 1;
                } else {
                    $paso = $gd->paso;
                }

                $showboton = $this->setBotonCotizacion($paso, $model->id, $fuente, $value['id']);
                //die('paso: '.$paso);

                $data .= '<tr><td>Cliente</td>'
                        . '<td>' . $value['nombres'] . '</td>'
                        . '<td>' . $value['apellidos'] . '</td>'
                        . '<td>' . $value['cedula'] . '</td>'
                        . '<td>' . $this->getNombreConcesionario($value['dealer_id']) . '</td>'
                        . '<td>' . $value['fecha'] . '</td>'
                        . '<td>';
                if ($countvec > 0) {
                    $vec = GestionVehiculo::model()->findAll($criteria);
                    foreach ($vec as $val) {
                        $data .= '<em>' . $this->getModel($val['modelo']) . '</em>, <em>' . $this->getVersion($val['version']) . '. </em><br />';
                    }
                } else {
                    $data .= '<em>' . $this->getModel($value['modelo']) . '</em>, <em>' . $this->getVersion($value['version']) . '. </em><br />';
                }

                $data .= '</td>'
                        . '<td>' . $showboton . '</td></tr>';
            }
            $data .= '</table></div></div></div>';

            // LLAMADA A FUNCION DE CREATEC 
            $data_createc = $this->Createc($id, 0,'cedula');
        }
        if ($ced == 0) {
            $model->cedula = $id;
            $model->fuente = $fuente;
            $model->identificacion = 'ci';
            $model->save();
            $id_nueva_cotizacion = $model->id;
            
            // LLAMADA A FUNCION DE CREATEC 
            $data_createc = $this->Createc($id, $id_nueva_cotizacion,'cedula');
            $gn = GestionNuevaCotizacion::model()->findByPk($id_nueva_cotizacion);
            $gn->datos_cliente =  implode(',', $data_createc['data_save']);
            $gn->update();
            $result = $data_createc['result'];
        }// ----FIN DE NI CEDULA-----------
        $options = array('data' => $data,
            'result' => $result,
            'datattga35' => $data_createc['datattga35'],
            'datattga36' => $data_createc['datattga36'],
            'datavh01' => $data_createc['datavh01'],
            'flagttga35' => $data_createc['flagttga35'],
            'flagttga36' => $data_createc['flagttga36'],
            'flagvh01' => $data_createc['flagvh01'],
            'id_informacion' => $data_createc['id_informacion'],
            'id_nueva_cotizacion' => $id_nueva_cotizacion
        );
        echo json_encode($options);
    }

    public function actionGetRuc() {
        //die('enter action get ruc');
        $model = new GestionNuevaCotizacion;
        $id_responsable = Yii::app()->user->getId();
        $dealer_id = $this->getDealerId($id_responsable);
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        $fuente = isset($_POST["fuente"]) ? $_POST["fuente"] : "";

        //die('id: '.$id);
        $criteria = new CDbCriteria(array(
            'condition' => "ruc='{$id}'"
        ));
        $data = '';
        $result = FALSE;
        $ced = GestionInformacion::model()->count($criteria);
        $cd = GestionInformacion::model()->findAll($criteria);
        //die('ced: '.$ced);
        if ($ced > 0) {
            //die('enter ruc');
            $result = TRUE;
            $data = '<div class="row"><h1 class="tl_seccion">Cliente existente</h1></div>'
                    . '<div class="row"><div class="col-md-12">'
                    . '<div class="table-responsive">'
                    . '<table class="tables tablesorter" id="keywords">
                <thead>
                    <tr>
                        <th><span>Status</span></th>
                        <th><span>Nombres</span></th>
                        <th><span>Apellidos</span></th>
                        <th><span>Ruc</span></th>
                        <th><span>Concesionario</span></th>
                        <th><span>Fecha Registro</span></th>
                        <th><span>Modelo</span></th>
                        <th><span>Edición</span></th>
                    </tr>
                </thead>
                <tbody>';
            foreach ($cd as $value) {
                $criteria = new CDbCriteria(array(
                    'condition' => "id_informacion = {$value['id']}"
                ));
                $countvec = GestionVehiculo::model()->count($criteria);


                // sacar id informacion para obtener el paso donde esta el cliente
                $criteria2 = new CDbCriteria();
                //die('value: '.$value['id']);
                $criteria2->condition = "id_informacion = {$value['id']}";
                $gd = GestionDiaria::model()->find($criteria2);
                /* echo '<pre>';
                  print_r($gd);
                  echo '</pre>';
                  die(); */
                if (empty($gd)) {
                    $paso = 1;
                } else {
                    $paso = $gd->paso;
                }

                $showboton = $this->setBotonCotizacion($paso, $model->id, $fuente, $value['id']);
                //die('paso: '.$paso);

                $data .= '<tr><td>Cliente</td>'
                        . '<td>' . $value['nombres'] . '</td>'
                        . '<td>' . $value['apellidos'] . '</td>'
                        . '<td>' . $value['ruc'] . '</td>'
                        . '<td>' . $this->getNombreConcesionario($value['dealer_id']) . '</td>'
                        . '<td>' . $value['fecha'] . '</td>'
                        . '<td>';
                if ($countvec > 0) {
                    $vec = GestionVehiculo::model()->findAll($criteria);
                    foreach ($vec as $val) {
                        $data .= '<em>' . $this->getModel($val['modelo']) . '</em>, <em>' . $this->getVersion($val['version']) . '. </em><br />';
                    }
                } else {
                    $data .= '<em>' . $this->getModel($value['modelo']) . '</em>, <em>' . $this->getVersion($value['version']) . '. </em><br />';
                }

                $data .= '</td>'
                        . '<td>' . $showboton . '</td></tr>';
            }
            $data .= '</table></div></div></div>';
            // LLAMADA A FUNCION DE CREATEC 
            $data_createc = $this->Createc($id, 0,'ruc');
        }
        if ($ced == 0) {
            $model->ruc = $id;
            $model->fuente = $fuente;
            $model->identificacion = 'ruc';
            $model->save();
            $id_nueva_cotizacion = $model->id;

            // LLAMADA A FUNCION DE CREATEC 
            $data_createc = $this->Createc($id, $id_nueva_cotizacion,'ruc');
            $gn = GestionNuevaCotizacion::model()->findByPk($id_nueva_cotizacion);
            $gn->datos_cliente =  implode(',', $data_createc['data_save']);
            $gn->update();
            $result = $data_createc['result'];
        }// ----FIN DE NO RUC-----------

        $options = array('data' => $data,
            'result' => $result,
            'datattga35' => $data_createc['datattga35'],
            'datattga36' => $data_createc['datattga36'],
            'datavh01' => $data_createc['datavh01'],
            'flagttga35' => $data_createc['flagttga35'],
            'flagttga36' => $data_createc['flagttga36'],
            'flagvh01' => $data_createc['flagvh01'],
            'id_informacion' => $data_createc['id_informacion'],
            'id_nueva_cotizacion' => $id_nueva_cotizacion
        );
        echo json_encode($options);
    }

    public function actionGetPasaporte() {
        //die('enter pasaporte');
        $model = new GestionNuevaCotizacion;
        $id_responsable = Yii::app()->user->getId();
        $dealer_id = $this->getDealerId($id_responsable);
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        $fuente = isset($_POST["fuente"]) ? $_POST["fuente"] : "";
        $model->cedula = $id;
        $model->fuente = $fuente;
        $model->save();
        //die('id: '.$id);
        $criteria = new CDbCriteria(array(
            'condition' => "pasaporte='{$id}'"
        ));
        $data = '';
        $result = FALSE;
        $ced = GestionInformacion::model()->count($criteria);
        $cd = GestionInformacion::model()->findAll($criteria);
        //die('ced: '.$ced);
        if ($ced > 0) {
            $result = TRUE;
            $data = '<div class="row"><h1 class="tl_seccion">Cliente existente</h1></div>'
                    . '<div class="row"><div class="col-md-12">'
                    . '<div class="table-responsive">'
                    . '<table class="tables tablesorter" id="keywords">
                <thead>
                    <tr>
                        <th><span>Status</span></th>
                        <th><span>Nombres</span></th>
                        <th><span>Apellidos</span></th>
                        <th><span>Pasaporte</span></th>
                        <th><span>Concesionario</span></th>
                        <th><span>Fecha Registro</span></th>
                        <th><span>Modelo</span></th>
                        <th><span>Edición</span></th>
                    </tr>
                </thead>
                <tbody>';
            foreach ($cd as $value) {
                $criteria = new CDbCriteria(array(
                    'condition' => "id_informacion = {$value['id']}"
                ));
                $countvec = GestionVehiculo::model()->count($criteria);


                // sacar id informacion para obtener el paso donde esta el cliente
                $criteria2 = new CDbCriteria();
                //die('value: '.$value['id']);
                $criteria2->condition = "id_informacion = {$value['id']}";
                $gd = GestionDiaria::model()->find($criteria2);
                /* echo '<pre>';
                  print_r($gd);
                  echo '</pre>';
                  die(); */
                if (empty($gd)) {
                    $paso = 1;
                } else {
                    $paso = $gd->paso;
                }

                $showboton = $this->setBotonCotizacion($paso, $model->id, $fuente, $value['id']);
                //die('url: '.$showboton);

                $data .= '<tr><td>Cliente</td>'
                        . '<td>' . $value['nombres'] . '</td>'
                        . '<td>' . $value['apellidos'] . '</td>'
                        . '<td>' . $value['pasaporte'] . '</td>'
                        . '<td>' . $this->getConcesionario($dealer_id) . '</td>'
                        . '<td>' . $value['fecha'] . '</td>'
                        . '<td>';
                if ($countvec > 0) {
                    $vec = GestionVehiculo::model()->findAll($criteria);
                    foreach ($vec as $val) {
                        $data .= '<em>' . $this->getModel($val['modelo']) . '</em>, <em>' . $this->getVersion($val['version']) . '. </em><br />';
                    }
                } else {
                    $data .= '<em>' . $this->getModel($value['modelo']) . '</em>, <em>' . $this->getVersion($value['version']) . '. </em><br />';
                }

                $data .= '</td>'
                        . '<td>' . $showboton . '</td></tr>';
            }
            $data .= '</table></div></div></div>';
        }
        $options = array('data' => $data, 'result' => $result);
        echo json_encode($options);
    }

    private function Createc($id, $id_nueva_cotizacion, $tipo_identificacion) {
        $id_responsable = Yii::app()->user->getId();
        $dealer_id = $this->getDealerId($id_responsable);
        // BUSQUEDA EN CREATEC==================================================================================
        //======================================================================================================
        $id_modeloInformacion = 0;
        $uriservicio = "http://200.31.10.92/wsa/wsa1/wsdl?targetURI=urn:aekia";
        //die('after uri');
        $client = new SoapClient(@$uriservicio, array('trace' => 1));
        $response = $client->pws01_01_cl("{$id}", '');
        
        $result = FALSE;

        $coin1 = FALSE;
        $coin2 = FALSE;
        $coincidencias;
        $coincidencias2;
        $count;
        $count2;
        $coin1_36 = FALSE;
        $coin2_36 = FALSE;
        $coincidencias_36;
        $coincidencias2_36;
        $count_36;
        $count2_36;

        $coin1_vh = FALSE;
        $coin2_vh = FALSE;
        $coincidencias_vh;
        $coincidencias2_vh;
        $count_vh;
        $count2_vh;

        $dataCrTga35 = FALSE;
        $dataCrTga36 = FALSE;
        $dataCrTgavh = FALSE;
        $dataCreatec = '';
        $dataCreatec_36 = '';
        $dataCreatec_vh = '';

        //------BUSQUEDA PARA TA TABLA TTGA35 POSTVENTA---------------------------------------------
        if (preg_match_all('/<ttga35>/', $response['lcxml'], $coincidencias, PREG_OFFSET_CAPTURE)) {

            $count = count($coincidencias[0]);
            //echo 'numero de coincidencias: '.$count;
            $coin1 = TRUE;
        } else {
            //echo "NO HAY COINCIDENCIA";
        }

        if (preg_match_all('/<\/ttga35>/', $response['lcxml'], $coincidencias2, PREG_OFFSET_CAPTURE)) {

            $count2 = count($coincidencias2[0]);
            $coin2 = TRUE;
        } else {
            //echo "NO HAY COINCIDENCIA";
        }

        // datos de la tabla ttga35 a ser mostrados
        $datos_tga35 = array('chasis' => 'Chasis', 'tipo_id_cliente' => 'Tipo Identificación', 'identifica_cliente' => 'Identificación',
            'nombre_cliente' => 'Nombre del Cliente', 'direccion_cliente' => 'Dirección del Cliente', 'telefono_cliente' => 'Teléfono',
            'telefono_celular' => 'Celular', 'mail_1' => 'Email', 'fecha_reparacion' => 'Fecha de reparación', 'reparaciones_solicitadas' => 'Reparaciones Solicitadas',
            'tipo_ingreso' => 'Tipo de Ingreso'
        );
        if ($coin1 && $coin2) {// si las dos busquedas son true en etiqueta de apertura y cierre
            if ($count == $count2) {// si las dos busquedas devuelven el mismo numero de coincidencias
                $dataCreatec = '<div class="row"><h1 class="tl_seccion">Info Postventa</h1></div>'
                        . '<div class="row"><div class="col-md-12">'
                        . '<div class="table-responsive">
                        <table class="tables tablesorter" id="keywords">
                <thead>
                    <tr>
                        <th><span>Chasis</span></th>
                        <th><span>Tipo Identificación</span></th>
                        <th><span>Identificación</span></th>
                        <th><span>Nombre</span></th>
                        <th><span>Dirección</span></th>
                        <th><span>Teléfono</span></th>
                        <th><span>Celular</span></th>
                        <th><span>Email</span></th>
                        <th><span>Fecha Reparación</span></th>
                        <th><span>Reparaciones Solicitadas</span></th>
                        <th><span>Tipo de ingreso</span></th>
                    </tr>
                </thead>
                <tbody>';
                for ($i = 0; $i < $count; $i++) {
                    $ini = $coincidencias[0][$i][1];
                    $fin = $coincidencias2[0][$i][1];
                    $ini += 8; // sumamos el numero de caracteres (8) de <ttga35>
                    $longitudchr = $fin - $ini; // longitud de la cadena a imprimir
                    $str = substr($response['lcxml'], $ini, $longitudchr);
                    $dataCreatec .= '<tr>';
                    foreach ($datos_tga35 as $key => $value) {
                        // primero encontramos la posicion de la cadena a encontrar
                        $pos = strpos($str, '<' . $key . '>');
                        // encontramos la posicion final de la cadena final
                        $posfinal = strpos($str, '</' . $key . '>');
                        // longitud de la cadena a impriimir
                        $longitud_cadena = $posfinal - $pos;
                        // cadena a imprimir, con posicion inicial y longitud
                        $strtga35 = substr($str, $pos, $longitud_cadena);
                        $dataCreatec .= '<td>' . $strtga35 . '</td>';
                    }
                    $dataCreatec .= '</tr>';
                }
                $dataCreatec .= '</table></div></div></div>';
                $dataCrTga35 = TRUE;
                $result = TRUE;
            }
        }
        //------FIN DE BUSQUEDA TABLA TTGA35-------------------------------
        // -------------BUSQUEDA TABLA TTGA36 REPUESTOS---------------------
        if (preg_match_all('/<ttga36>/', $response['lcxml'], $coincidencias_36, PREG_OFFSET_CAPTURE)) {
            ////echo 'hay coincidencia 1 <br />';
            $count_36 = count($coincidencias_36[0]);
            //echo 'numero de coincidencias: '.$count;
            $coin1_36 = TRUE;
        } else {
            //echo "NO HAY COINCIDENCIA";
        }

        if (preg_match_all('/<\/ttga36>/', $response['lcxml'], $coincidencias2_36, PREG_OFFSET_CAPTURE)) {
            //echo 'hay coincidencia 2 <br />';
            $count2_36 = count($coincidencias2_36[0]);
            $coin2_36 = TRUE;
        } else {
            //echo "NO HAY COINCIDENCIA";
        }
        //die('coin1 36: '.$coin1_36.', coin2 36: '.$coin2_36.', count 36: '.$count_36.', count2 36: '.$count2_36);
        // datos de la tabla ttga36 a ser mostrados
        $datos_tga36 = array('anio_ga35' => 'Año', 'descripcion' => 'Descripción', 'clase_registro' => 'Clase de Registro');
        if ($coin1_36 && $coin2_36) {// si las dos busquedas son true en etiqueta de apertura y cierre
            if ($count_36 == $count2_36) {// si las dos busquedas devuelven el mismo numero de coincidencias
                //die('enter data createc 36');
                $dataCreatec_36 = '<div class="row"><h1 class="tl_seccion">Info Repuestos</h1></div>'
                        . '<div class="row"><div class="col-md-12">'
                        . '<div class="table-responsive">
                        <table class="tables tablesorter" id="keywords">
                <thead>
                    <tr>
                        <th><span>Año</span></th>
                        <th><span>Descripción</span></th>
                        <th><span>Clase de Registro</span></th>
                    </tr>
                </thead>
                <tbody>';
                for ($i = 0; $i < $count_36; $i++) {
                    $ini = $coincidencias_36[0][$i][1];
                    $fin = $coincidencias2_36[0][$i][1];
                    $ini +=8; // sumamos el numero de caracteres (8) de <ttga35>
                    $longitudchr = $fin - $ini; // longitud de la cadena a imprimir
                    $str_36 = substr($response['lcxml'], $ini, $longitudchr);
                    $dataCreatec_36 .= '<tr>';
                    foreach ($datos_tga36 as $key => $value) {
                        // primero encontramos la posicion de la cadena a encontrar
                        $pos = strpos($str_36, '<' . $key . '>');
                        // encontramos la posicion final de la cadena final
                        $posfinal = strpos($str_36, '</' . $key . '>');
                        // longitud de la cadena a impriimir
                        $longitud_cadena = $posfinal - $pos;
                        // cadena a imprimir, con posicion inicial y longitud
                        $strtga36 = substr($str_36, $pos, $longitud_cadena);
                        $dataCreatec_36 .= '<td>' . $strtga36 . '</td>';
                    }
                    $dataCreatec_36 .= '</tr>';
                }
                $dataCreatec_36 .= '</table></div></div></div>';
                //die('data cr 36: '.$dataCreatec_36);
                $dataCrTga36 = TRUE;
                $result = TRUE;
            }
        }
        // -------------FIN DE BUSQUEDA TABLA TTGH36-----------------------
        // -------------BUSQUEDA TABLA VH01 VEHICULOS ---------------------
        $data_save = array();
        if (preg_match_all('/<ttvh01>/', $response['lcxml'], $coincidencias_vh, PREG_OFFSET_CAPTURE)) {

            $count_vh = count($coincidencias_vh[0]);
            $coin1_vh = TRUE;
        } else {
            //echo "NO HAY COINCIDENCIA";
        }

        if (preg_match_all('/<\/ttvh01>/', $response['lcxml'], $coincidencias2_vh, PREG_OFFSET_CAPTURE)) {

            $count2_vh = count($coincidencias2_vh[0]);
            $coin2_vh = TRUE;
        } else {
            //echo "NO HAY COINCIDENCIA";
        }

        // datos de la tabla vh01 a ser mostrados
        $datos_vh01 = array(
            'chasis' => 'No. Chasis', 'codigo_modelo' => 'Código Modelo', 'nombre_propietario' => 'Nombre Propietario', 'color_vehiculo' => 'Color Vehículo',
            'fecha_retail' => 'Fecha de Venta', 'anio_modelo' => 'Año', 'color_origen' => 'Color de Orígen',
            'tipo_id_propietario' => 'Tipo Id', 'numero_id_propietario' => 'Id del Propietario', 'precio_venta' => 'Precio de Venta',
            'calle_principal_propietario' => 'Calle Principal', 'numero_calle_propietario' => 'Número de Calle',
            'telefono_propietario' => 'Teléfono del Propietario', 'grupo_concesionario' => 'Grupo Concesionario',
            'forma_pago_retail' => 'Forma de Pago'
        );
        if ($coin1_vh && $coin2_vh) {// si las dos busquedas son true en etiqueta de apertura y cierre
            if ($count_vh == $count2_vh) {// si las dos busquedas devuelven el mismo numero de coincidencias
                //die('enter vec');
                $dataCreatec_vh = '<div class="row"><h1 class="tl_seccion">Info Venta Vehículos</h1></div>'
                        . '<div class="row"><div class="col-md-12">'
                        . '<div class="table-responsive">
                        <table class="tables tablesorter" id="keywords">
                <thead>
                    <tr>
                        <th><span>Chasis</span></th>
                        <th><span>Código Modelo</span></th>
                        <th><span>Nombre</span></th>
                        <th><span>Color</span></th>
                        <th><span>Fecha de Venta</span></th>
                        <th><span>Año</span></th>
                        <th><span>Color orígen</span></th>
                        <th><span>Tipo ID</span></th>
                        <th><span>Id Propietario</span></th>
                        <th><span>Precio de Venta</span></th>
                        <th><span>Direccion</span></th>
                        <th><span>Número</span></th>
                        <th><span>Teléfono</span></th>
                        <th><span>Grupo Conc.</span></th>
                        <th><span>Forma de pago</span></th>
                    </tr>
                </thead>
                <tbody>';

                // GRABAR DATOS EN MODELO GESTION INFORMACION
                $modelInformacion = new GestionInformacion;
                $modelInformacion->setscenario('createc');


                for ($i = 0; $i < $count_vh; $i++) {
                    $ini = $coincidencias_vh[0][$i][1];
                    $fin = $coincidencias2_vh[0][$i][1];
                    $ini += 8; // sumamos el numero de caracteres (8) de <ttga35>
                    $longitudchr = $fin - $ini; // longitud de la cadena a imprimir
                    $str_vh = substr($response['lcxml'], $ini, $longitudchr);
                    $dataCreatec_vh .= '<tr>';
                    foreach ($datos_vh01 as $key => $value) {
                        // primero encontramos la posicion de la cadena a encontrar
                        $pos = strpos($str_vh, '<' . $key . '>');
                        // encontramos la posicion final de la cadena final
                        $posfinal = strpos($str_vh, '</' . $key . '>');
                        // longitud de la cadena a impriimir
                        $longitud_cadena = $posfinal - $pos;
                        // cadena a imprimir, con posicion inicial y longitud
                        $strtgavh = substr($str_vh, $pos, $longitud_cadena);
                        $params = explode(">", $strtgavh);
                        $dataCreatec_vh .= '<td>' . $strtgavh . '</td>';
                        $data_save[$value] = "{$params[1]}";
                    }
                    $dataCreatec_vh .= '</tr>';
                }
                $dataCreatec_vh .= '</table></div></div></div>';
//                echo '<pre>';
//                print_r($data_save);
//                echo '</pre>';

                $dataCrTgavh = TRUE;
                $result = TRUE;

                if ($id_nueva_cotizacion != 0) { // SI NUEVA COTIZACION NO ES CERO PUEDE REGISTRAR UN NUEVO CLIENTE EN GESTION INFORMACION, GESTION CONSULA Y GESTION DIARIA
                    //GRABAR DATOS A GESTION INFORMACION
//                    date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
//                    $modelInformacion->fecha = date("Y-m-d H:i:s");
//                    $modelInformacion->responsable = $id_responsable;
//                    $modelInformacion->dealer_id = $dealer_id;
//                    if (strlen($data_save['Teléfono del Propietario']) == 10) {
//                        $modelInformacion->celular = $data_save['Teléfono del Propietario'];
//                    } else {
//                        $modelInformacion->celular = '0999999999';
//                    }    
//                    if($tipo_identificacion == 'cedula'){
//                        $modelInformacion->cedula = $data_save['Id del Propietario'];
//                    }
//                    if($tipo_identificacion == 'ruc'){
//                        $modelInformacion->ruc = $data_save['Id del Propietario'];
//                    }
//                    $modelInformacion->nombres = $data_save['Nombre Propietario'];
//                    $modelInformacion->direccion = $data_save['Calle Principal'];
//                    $modelInformacion->id_cotizacion = $id_nueva_cotizacion;
//                    //die('before validare');
////                if ($modelInformacion->validate()) {
////                    die( 'validate');
////                } else {
////                    echo '<pre>';
////                    print_r($modelInformacion->getErrors());
////                    echo '</pre>';
////                    die( 'validate errors');
////                }
//                    if ($modelInformacion->save()) {
//                        //die('enter model save');
//                        $id_modeloInformacion = $modelInformacion->id;
//                        
//                        // GRABAR PASO INICIAL GESTION DIARIA
//                        $gestion = new GestionDiaria;
//                        $gestion->id_informacion = $modelInformacion->id;
//                        $gestion->id_vehiculo = 0;
//                        $gestion->observaciones = 'Prospección';
//                        $gestion->medio_contacto = 'telefono';
//                        $gestion->fuente_contacto = 'prospeccion';
//                        $gestion->codigo_vehiculo = 0;
//                        $gestion->primera_visita = 1;
//                        $gestion->status = 1;
//                        $gestion->paso = 3;
//                        $gestion->proximo_seguimiento = '';
//                        $gestion->fecha = date("Y-m-d H:i:s");
//                        $gestion->save();
//
//                        // GRABAR PASO INICIAL EN GESTION CONSULTA
//                        $consulta = new GestionConsulta;
//                        $consulta->id_informacion = $modelInformacion->id;
//                        $consulta->fecha = date("Y-m-d H:i:s");
//                        $consulta->status = 'ACTIVO';
//                        $consulta->save();
//                    }
                }
            }
        }
        //die('id informacion: '.$id_modeloInformacion);
        //----------FIN DE BUSQUEDA TABLA VH01---------------------------
        $options = array(
            'datattga35' => $dataCreatec,
            'datattga36' => $dataCreatec_36,
            'datavh01' => $dataCreatec_vh,
            'flagttga35' => $dataCrTga35,
            'flagttga36' => $dataCrTga36,
            'flagvh01' => $dataCrTgavh,
            'id_informacion' => 0,
            'result' => $result,
            'data_save' => $data_save,
        );
        return $options;
    }

    /**
     * Make a Test Drive for a vehicle id
     * @param integer $id_informacion the ID of the info's client
     * @param integer $id the ID of the vehicle's client
     */
    public function actionDemostracion($id_informacion = NULL, $id = NULL) {
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion='{$id}'"
        ));
        $vec = GestionVehiculo::model()->findAll($criteria);
        //$this->render('presentacion', array('id' => $id, 'vec' => $vec));

        $this->render('demostracion', array('id_informacion' => $id, 'vec' => $vec));
    }

    public function actionGetObsTestDrive() {
        $id_informacion = isset($_POST["id_informacion"]) ? $_POST["id_informacion"] : "";
        $id_vehiculo = isset($_POST["id_vehiculo"]) ? $_POST["id_vehiculo"] : "";
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion={$id_informacion} AND id_vehiculo = {$id_vehiculo} AND test_drive = 0"
        ));
        $obs = GestionTestDrive::model()->find($criteria);
        $options = array('options' => $obs->observacion);
        echo json_encode($options);
    }

    public function actionMatricula($param) {
        $model = new GestionMatricula;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionMatricula'])) {
            die('enter gesiton');
            $model->attributes = $_POST['GestionMatricula'];
            if ($model->save()) {
                //die('enter save');
            }
        }
    }

    public function actionProforma($id_informacion, $id_vehiculo) {
        $this->layout = '//layouts/call-print';
        //die('enter prof');
        $con = Yii::app()->db;
        $sql = "SELECT gi.nombres, gi.apellidos, gi.direccion, gi.telefono_casa, gv.modelo, gv.version, gv.forma_pago, 
gv.precio, gv.seguro, gv.total, gf.cuota_inicial, gf.saldo_financiar, gf.plazos, gf.cuota_mensual, gv.accesorios
FROM gestion_informacion gi 
INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id 
INNER JOIN gestion_financiamiento gf ON gf.id_informacion = gi.id 
WHERE gi.id = {$id_informacion} AND gv.id = {$id_vehiculo}";
//die('sql:'.$sql);
        $request = $con->createCommand($sql)->queryAll();
        $this->render('proforma', array('data' => $request));
    }

    /**
     * Generate a pdf document with vehicle accesories and charts
     * @param type $id_informacion
     * @param type $id_vehiculo
     */
    public function actionProformaClienteExo($id_informacion, $id_vehiculo) {
        $this->layout = '//layouts/call-print';
        $responsable_id = $this->getResponsableId($id_informacion);
        $nombre_responsable = $this->getResponsableNombres($responsable_id);
        $nombre_responsable = mb_convert_case($nombre_responsable, MB_CASE_UPPER, "UTF-8");
        $concesionarioid = $this->getConcesionarioDealerId($responsable_id);
        $nombreproforma = $this->getNombreProforma($concesionarioid);
        $ruc = $this->getConcesionarioGrupoRuc($responsable_id);
        //die('concesionario id: '.$concesionarioid);
        //die('enter prof');

        $con = Yii::app()->db;
        $sql = "SELECT gi.id,gi.nombres, gi.apellidos, gi.direccion, gi.celular, gi.telefono_casa,gi.responsable, gv.modelo, gv.version, gf.forma_pago, 
gf.precio_vehiculo,  gf.precio_normal,gf.seguro, gf.valor_financiamiento, gf.cuota_inicial, gf.saldo_financiar, gf.plazos, gf.entidad_financiera, gf.id as id_financiamiento,gf.ts,  
gf.observaciones, gf.cuota_mensual, gv.accesorios
FROM gestion_informacion gi 
INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id 
INNER JOIN gestion_financiamiento gf ON gf.id_informacion = gi.id 
WHERE gi.id = {$id_informacion} AND gv.id = {$id_vehiculo} ORDER BY gf.id DESC LIMIT 1";
//die('sql:'.$sql);
        $request = $con->createCommand($sql)->queryAll();
        $num_proforma = $this->getProformaCliente($id_informacion, $id_vehiculo);
        //die('num proforma: '.$num_proforma);
        //$proforma = new GestionProforma;
        //$proforma->id_vehiculo = $id_vehiculo;
        //date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
        //$proforma->fecha = date("Y-m-d H:i:s");
        //$proforma->save();
        # mPDF        
        # You can easily override default constructor's params
        $mPDF1 = Yii::app()->ePdf->mpdf('', 'A4');
        $mPDF1->SetTitle('Proforma Cliente');

        //$mPDF1->WriteHTML($this->render('pdf2', array('data' => $request), true));
        # Load a stylesheet
        $stylesheet = file_get_contents(Yii::getPathOfAlias('webroot.bootstrap.css') . '/bootstrap.css');
        $mPDF1->WriteHTML($stylesheet, 1);
        # renderPartial (only 'view' of current controller)
        $mPDF1->WriteHTML($this->renderPartial('proformaclientexo', array('data' => $request, 'id_hoja' => $num_proforma, 'id_informacion' => $id_informacion, 'nombre_responsable' => $nombre_responsable, 'responsable_id' => $responsable_id, 'ruc' => $ruc), true));

        # Renders image
        //$mPDF1->WriteHTML(CHtml::image(Yii::getPathOfAlias('webroot.css') . '/bg.gif' ));
        # Outputs ready PDF
        $mPDF1->Output($nombreproforma . '.pdf', 'I');
    }

    /**
     * Generate a pdf document with vehicle accesories and charts
     * @param type $id_informacion
     * @param type $id_vehiculo
     */
    public function actionProformaCliente($id_informacion, $id_vehiculo) {
        $this->layout = '//layouts/call-print';
        $responsable_id = $this->getResponsableId($id_informacion);
        $nombre_responsable = $this->getResponsableNombres($responsable_id);
        $nombre_responsable = mb_convert_case($nombre_responsable, MB_CASE_UPPER, "UTF-8");

        $concesionarioid = $this->getConcesionarioDealerId($responsable_id);
        $nombreproforma = $this->getNombreProforma($concesionarioid);
        $ruc = $this->getConcesionarioGrupoRuc($responsable_id);

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
        $num_proforma = $this->getProformaCliente($id_informacion, $id_vehiculo);

        $mPDF1 = Yii::app()->ePdf->mpdf('', 'A4');
        $stylesheet = file_get_contents(Yii::getPathOfAlias('webroot.bootstrap.css') . '/bootstrap.css');
        $mPDF1->WriteHTML($stylesheet, 1);
        $mPDF1->WriteHTML($this->render('proformacliente', array('data' => $request, 'id_hoja' => $num_proforma, 'id_informacion' => $id_informacion, 'nombre_responsable' => $nombre_responsable, 'responsable_id' => $responsable_id, 'ruc' => $ruc), true));
        $mPDF1->Output($nombreproforma . '.pdf', 'I');
    }

    /**
     * Generate a pdf document with vehicle quote and client's information
     * @param type $id_informacion
     * @param type $id_vehiculo
     */
    public function actionHojaentregacliente($id_informacion, $id_vehiculo) {
        $this->layout = '//layouts/call-print';
        //die('enter prof');
        $con = Yii::app()->db;
        $sql = "SELECT gi.*, ge.* FROM gestion_entrega ge 
INNER JOIN gestion_informacion gi on gi.id = ge.id_informacion
WHERE ge.id_informacion = {$id_informacion} ORDER BY ge.id DESC limit 1";
//die('sql:'.$sql);
        $request = $con->createCommand($sql)->queryAll();
        $num_entrega = $this->getHojaEntregaCliente($id_informacion, $id_vehiculo);

        # You can easily override default constructor's params
        $mPDF1 = Yii::app()->ePdf->mpdf('', 'A4');
        $mPDF1->SetTitle('Hoja de Entrega');

        //$mPDF1->WriteHTML($this->render('pdf2', array('data' => $request), true));
        # Load a stylesheet
        $stylesheet = file_get_contents(Yii::getPathOfAlias('webroot.bootstrap.css') . '/bootstrap.css');
        $mPDF1->WriteHTML($stylesheet, 1);
        # renderPartial (only 'view' of current controller)
        $mPDF1->WriteHTML($this->renderPartial('hoja_entrega_cliente', array('data' => $request, 'id_hoja' => $num_entrega, 'id_informacion' => $id_informacion), true));

        # Renders image
        //$mPDF1->WriteHTML(CHtml::image(Yii::getPathOfAlias('webroot.css') . '/bg.gif' ));
        # Outputs ready PDF
        $mPDF1->Output('hoja-de-entrega.pdf', 'I');
    }

    public function actionPrint($id_informacion, $id_vehiculo) {
        //die('enter print');
        $con = Yii::app()->db;
        $sql = "SELECT gi.nombres, gi.apellidos, gi.direccion, gi.telefono_casa, gv.modelo, gv.version,
gv.forma_pago, gv.precio, gv.seguro, gv.total, gf.cuota_inicial, gv.saldo_financiar, 
gv.plazo, gf.cuota_mensual, gv.accesorios
FROM gestion_informacion gi 
INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id 
INNER JOIN gestion_financiamiento gf ON gf.id_informacion = gi.id
WHERE gi.id = {$id_informacion} AND gv.id = {$id_vehiculo}";

        $request = $con->createCommand($sql)->queryAll();
        $this->render('proforma', array('data' => $request));
    }

    public function actionPdf($id_informacion, $id_vehiculo) {
        $this->layout = '//layouts/call-print';
        $responsable_id = $this->getResponsableId($id_informacion);
        $nombre_responsable = $this->getResponsableNombres($responsable_id);
        $nombre_responsable = mb_convert_case($nombre_responsable, MB_CASE_UPPER, "UTF-8");
        $con = Yii::app()->db;
        $sql = "SELECT gi.id,gi.nombres, gi.apellidos, gi.direccion, gi.telefono_casa, gv.modelo, gv.version, gv.forma_pago, 
gv.precio, gv.seguro, gv.total, gv.accesorios, gt.firma
FROM gestion_informacion gi 
INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id
INNER JOIN gestion_test_drive gt ON gt.id_informacion = gi.id 
WHERE gi.id = {$id_informacion} AND gv.id = {$id_vehiculo}";

        //$request = $con->createCommand($sql)->queryAll();
        $criteria = new CDbCriteria(array(
            'condition' => "id='{$id_informacion}'"
        ));
        $request = GestionInformacion::model()->findAll($criteria);

        # You can easily override default constructor's params
        $mPDF1 = Yii::app()->ePdf->mpdf('', 'A4');
        $mPDF1->SetTitle('Formulario de Prueba de Manejo');

        //$mPDF1->WriteHTML($this->render('pdf2', array('data' => $request), true));
        # Load a stylesheet
        $stylesheet = file_get_contents(Yii::getPathOfAlias('webroot.bootstrap.css') . '/bootstrap.css');
        $mPDF1->WriteHTML($stylesheet, 1);
        # renderPartial (only 'view' of current controller)
        $mPDF1->WriteHTML($this->renderPartial('pdf2', array('data' => $request, 'id_vehiculo' => $id_vehiculo, 'nombre_responsable' => $nombre_responsable, 'responsable_id' => $responsable_id), true));

        # Renders image
        //$mPDF1->WriteHTML(CHtml::image(Yii::getPathOfAlias('webroot.css') . '/bg.gif' ));
        # Outputs ready PDF
        $mPDF1->Output('formulario-test-drive.pdf', 'I');


        ////////////////////////////////////////////////////////////////////////////////////
        # HTML2PDF has very similar syntax
        //$html2pdf = Yii::app()->ePdf->HTML2PDF();
        //$html2pdf->WriteHTML($this->renderPartial('pdf2', array('data' => $request), true));
        //$html2pdf->Output();
        ////////////////////////////////////////////////////////////////////////////////////
        # Example from HTML2PDF wiki: Send PDF by email
//        $content_PDF = $html2pdf->Output('', EYiiPdf::OUTPUT_TO_STRING);
//        require_once(dirname(__FILE__).'/pjmail/pjmail.class.php');
//        $mail = new PJmail();
//        $mail->setAllFrom('webmaster@my_site.net', "My personal site");
//        $mail->addrecipient('mail_user@my_site.net');
//        $mail->addsubject("Example sending PDF");
//        $mail->text = "This is an example of sending a PDF file";
//        $mail->addbinattachement("my_document.pdf", $content_PDF);
//        $res = $mail->sendmail();
    }

    public function actionPdf2($id_informacion, $id_vehiculo) {
        $this->layout = '//layouts/call-print';
        $con = Yii::app()->db;
        $sql = "SELECT gi.nombres, gi.apellidos, gi.direccion, gi.telefono_casa, gv.modelo, gv.version, gv.forma_pago, 
gv.precio, gv.seguro, gv.total, gf.cuota_inicial, gf.saldo_financiar, gf.plazos, gf.cuota_mensual, gv.accesorios
FROM gestion_informacion gi 
INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id 
INNER JOIN gestion_financiamiento gf ON gf.id_informacion = gi.id 
WHERE gi.id = {$id_informacion} AND gv.id = {$id_vehiculo}";

        $request = $con->createCommand($sql)->queryAll();
//        # mPDF
//        $mPDF1 = Yii::app()->ePdf->mpdf();
// 
//        # You can easily override default constructor's params
//        $mPDF1 = Yii::app()->ePdf->mpdf('', 'A5');
// 
//        # render (full page)
//        $mPDF1->WriteHTML($this->render('pdf', array('data' => $request), true));
// 
//        # Load a stylesheet
//        //$stylesheet = file_get_contents(Yii::getPathOfAlias('webroot.css') . '/main.css');
//        //$mPDF1->WriteHTML($stylesheet, 1);
// 
//        # renderPartial (only 'view' of current controller)
//        //$mPDF1->WriteHTML($this->renderPartial('pdf', array('data' => $request), true));
// 
//        # Renders image
//        //$mPDF1->WriteHTML(CHtml::image(Yii::getPathOfAlias('webroot.css') . '/bg.gif' ));
// 
//        # Outputs ready PDF
//        $mPDF1->Output();
        # HTML2PDF has very similar syntax
        $html2pdf = Yii::app()->ePdf->HTML2PDF();
        $html2pdf->WriteHTML($this->renderPartial('pdf', array('data' => $request), true));
        $html2pdf->Output();
    }

    public function actionDownloadForm($id_informacion, $id_vehiculo) {
        //die('enter download');
        $this->layout = '//layouts/call-print';
        $criteria = new CDbCriteria(array(
            'condition' => "id='{$id_informacion}'"
        ));
        $info = GestionInformacion::model()->findAll($criteria);

        # HTML2PDF has very similar syntax
        //$html2pdf = Yii::app()->ePdf->HTML2PDF();
        //$html2pdf->WriteHTML($this->renderPartial('downloadform', array('data' => $info, 'id_vehiculo' => $id_vehiculo), true));
        //$html2pdf->Output();
        # mPDF
        $mPDF1 = Yii::app()->ePdf->mpdf();

        # You can easily override default constructor's params
        $mPDF1 = Yii::app()->ePdf->mpdf('', 'A5');

        # render (full page)
        $mPDF1->WriteHTML($this->render('downloadform', array('data' => $info, 'id_vehiculo' => $id_vehiculo), true));

        # Load a stylesheet
        $stylesheet = file_get_contents(Yii::getPathOfAlias('webroot.bootstrap.css') . '/bootstrap.css');
        $mPDF1->WriteHTML($stylesheet, 1);
        # renderPartial (only 'view' of current controller)
        $mPDF1->WriteHTML($this->renderPartial('downloadform', array('data' => $info, 'id_vehiculo' => $id_vehiculo), true));

        # Renders image
        //$mPDF1->WriteHTML(CHtml::image(Yii::getPathOfAlias('webroot.css') . '/bg.gif' ));
        # Outputs ready PDF
        $mPDF1->Output();
    }

    public function actionAccesorios($id_vehiculo = NULL) {
        $this->render('accesorios', array('id_vehiculo' => $id_vehiculo));
    }

    public function actionUpdatePrice() {
        $id_vehiculo = isset($_POST["id_vehiculo"]) ? $_POST["id_vehiculo"] : "";
        $precio_accesorios = isset($_POST["precio_accesorios"]) ? $_POST["precio_accesorios"] : "";
        $vec = GestionVehiculo::model()->findByPk($id_vehiculo);
        $vec->precio_accesorios = $precio_accesorios;
        $vec->update();
    }

    public function actionFacturaCorrecta($id_informacion = NULL, $id_vehiculo = NULL) {
        $con = Yii::app()->db;
        $sql = "UPDATE gestion_vehiculo SET cierre = 'ACTIVO' WHERE id = {$id_vehiculo}";

        // ACTUALIZAR EN GESTION DIARIA EL STATUS DE CIERRE A 1
        $sql2 = "UPDATE gestion_diaria SET cierre = 1, paso = 9 WHERE id_informacion = {$id_informacion}";
        //die('sql: '.$sql);
        $request = $con->createCommand($sql)->query();
        $request2 = $con->createCommand($sql2)->query();
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion='{$id_informacion}'"
        ));
        //    die('vec');
        $vec = GestionVehiculo::model()->findAll($criteria);
        $factura = new GestionFactura;
        $factura->id_informacion = $_POST['id_informacion'];
        $factura->id_vehiculo = $_POST['id_vehiculo'];
        $factura->datos_vehiculo = $_POST['datos_vehiculo'];
        $factura->observaciones = 'Venta realizada';
        date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
        $factura->fecha = date("Y-m-d H:i:s");
        $factura->save();

        $this->redirect(array('site/cierre/' . $id_informacion));
        //$this->render('cierre',  array('id' => $id_informacion, 'vec' => $vec));
    }

    public function actionFacturaIncorrecta($id_informacion = NULL, $id_vehiculo = NULL) {
        /* echo '<pre>';
          print_r($_POST);
          echo '</pre>';
          die(); */
        $con = Yii::app()->db;
        $sql = "UPDATE gestion_vehiculo SET cierre = 'ACTIVO' WHERE id = {$id_vehiculo}";

        // ACTUALIZAR EN GESTION DIARIA EL STATUS DE CIERRE A 1
        $sql2 = "UPDATE gestion_diaria SET cierre = 1, paso = 9 WHERE id_informacion = {$id_informacion}";
        //die('sql: '.$sql);
        $request = $con->createCommand($sql)->query();
        $request2 = $con->createCommand($sql2)->query();
        $factura = new GestionFactura;
        $factura->id_informacion = $_POST['id_informacion'];
        $factura->id_vehiculo = $_POST['id_vehiculo'];
        $factura->datos_vehiculo = $_POST['datos_vehiculo'];
        $factura->observaciones = $_POST['Factura']['observaciones'];
        date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
        $factura->fecha = date("Y-m-d H:i:s");
        $factura->save();

        $this->redirect(array('site/cierre/' . $id_informacion));
    }

    public function actionFacturaNoRegistered($id_informacion = NULL, $id_vehiculo = NULL) {
        $this->redirect(array('site/cierre/' . $id_informacion));
    }

    public function actionFacturacierre() {
        $identificacion = '';
        $result = 'nofind'; // identificacion no encontrada
        $count_vh = 0;
        if (isset($_POST['Factura'])) {
            $identificacionsgc = $_POST['Factura']['identificacion'];
            switch ($_POST['Factura']['tipo']) {
                case 'cedula':
                    $identificacion = $_POST['Factura']['cedula'];
                    break;
                case 'ruc':
                    $identificacion = $_POST['Factura']['ruc'];
                    break;
                case 'chasis':
                    $identificacion = $_POST['Factura']['chasis'];
                    break;

                default:
                    break;
            }
            $data = '';
            $data_save = array();
            $uriservicio = "http://200.31.10.92/wsa/wsa1/wsdl?targetURI=urn:aekia";
            $params = array(
                "cidentifica_cliente" => '1791282264001',
                "cchasis" => '8LCDC22323E000003',
                "xmlres" => '',
                "xmlerror" => '',
            );
            //die('after uri');
            $client = new SoapClient(@$uriservicio, array('trace' => 1));

            try {
                $response = $client->pws01_01_cl("{$identificacion}", '');
            } catch (Exception $exc) {
                Yii::app()->user->setFlash('error', "Error al conectarse a la pirámide");
            }


            $params = explode('<ttvh01>', $response['lcxml']);
            $count = count($params);
            // -------------BUSQUEDA TABLA VH01 VEHICULOS ---------------------
            if (preg_match_all('/<ttvh01>/', $response['lcxml'], $coincidencias_vh, PREG_OFFSET_CAPTURE)) {
                $count_vh = count($coincidencias_vh[0]);
                $coin1_vh = TRUE;
            } else {
                //echo "NO HAY COINCIDENCIA";
            }

            if (preg_match_all('/<\/ttvh01>/', $response['lcxml'], $coincidencias2_vh, PREG_OFFSET_CAPTURE)) {
                $count2_vh = count($coincidencias2_vh[0]);
                $coin2_vh = TRUE;
            } else {
                //echo "NO HAY COINCIDENCIA";
            }
            $datos_search = array(
                'chasis' => 'No. Chasis', 'codigo_modelo' => 'Código Modelo', 'numero_motor' => 'Número Modelo',
                'nombre_propietario' => 'Nombre del Propietario', 'color_vehiculo' => 'Color Vehículo',
                'fsc' => 'Factura', 'codigo_concesionario' => 'Concesionario', 'fecha_retail' => 'Fecha de Venta', 'anio_modelo' => 'Año', 'color_origen' => 'Color de Orígen',
                'numero_id_propietario' => 'Identificación', 'precio_venta' => 'Precio de Venta',
                'calle_principal_propietario' => 'Calle Principal', 'numero_calle_propietario' => 'Número de Calle',
                'telefono_propietario' => 'Teléfono del Propietario', 'grupo_concesionario' => 'Grupo Concesionario',
                'forma_pago_retail' => 'Forma de Pago'
            );

            //die('numero coincidencias: '.$count_vh);
            if ($count_vh > 1) { // si el numero de coincidecias es mayor a 1
                $ght = $count_vh; // tomamos la longitud del array de $coincidencias_vh
                $ght--; // restamos un valor para el array de $coincidencias_vh
                //die('ght: '.$ght);
                $ini = $coincidencias_vh[0][$ght][1]; // valor mas actual del array $coincidencias_vh
                $fin = $coincidencias2_vh[0][$ght][1]; // valor mas actual del array $coincidencias2_vh
                $ini += 8; // sumamos el numero de caracteres (8) de <ttga35>
                $longitudchr = $fin - $ini; // longitud de la cadena a imprimir
                $str_vh = substr($response['lcxml'], $ini, $longitudchr);

                foreach ($datos_search as $key => $value) {
                    // primero encontramos la posicion de la cadena a encontrar
                    $pos = strpos($str_vh, '<' . $key . '>');
                    // encontramos la posicion final de la cadena final
                    $posfinal = strpos($str_vh, '</' . $key . '>');
                    // longitud de la cadena a impriimir
                    $longitud_cadena = $posfinal - $pos;
                    // cadena a imprimir, con posicion inicial y longitud
                    $strtgavh = substr($str_vh, $pos, $longitud_cadena);
                    if ($key == 'numero_id_propietario') {
                        //die('enter numero id');
                        if ($identificacion == $identificacionsgc) {
                            //die('iguales');
                            $result = 'equal'; //identificacion igual
                        } else {
                            //die('no iguales');
                            $result = 'noequal'; //identificacion no igual
                        }
                    }

                    $data .= '<tr class="odd"><th>' . $value . '</th><td>' . $strtgavh . '</td></tr>';
                    $data_save[$value] = "{$strtgavh}";
                }
                //echo '<pre>';
                //print_r($data_save);
                //echo '</pre>';
                //die();

                $dt = time();
                $fecha_actual = strftime("%Y-%m-%d", $dt);
                $year_actual = explode('-', $fecha_actual);
                $year_createc = explode('-', $data_save['Fecha de Venta']);
                $fact = (string) trim($year_actual[0]);
                $fcreatec = substr($year_createc[0], 14, 4);
                //echo('fecha actual:' . $fact . ', fecha createc:' . $fcreatec).'<br />';
                //echo '<h2>' . $fcreatec . '</h2>';
                if ($fcreatec !== $fact) {
                    $result = 'nofind'; // Year doesn't match 
                }
                //die('result 1: ' . $result);
                //die('SALIDA NUMERO DE COINCIDENCIAS: '.print_r($data_save));
                $this->render('facturacierre', array('id_informacion' => $_POST['Factura']['id_informacion'], 'id_vehiculo' => $_POST['Factura']['id_vehiculo'], 'data' => $data, 'data_save' => $data_save, 'search' => TRUE, 'result' => $result));
            } else { // si el valor es menor o igual a 0
                if ($count >= 2) {// ENCUENTRA IDENTIFICACION
                    foreach ($datos_search as $key => $value) {
                        // primero encontramos la posicion de la cadena a encontrar
                        $pos = strpos($params[1], '<' . $key . '>');
                        // encontramos la posicion final de la cadena final
                        $posfinal = strpos($params[1], '</' . $key . '>');
                        // longitud de la cadena a impriimir
                        $longitud_cadena = $posfinal - $pos;
                        // cadena a imprimir, con posicion inicial y longitud
                        $str = substr($params[1], $pos, $longitud_cadena);
                        if ($key == 'numero_id_propietario') {
                            //die('enter numero id');
                            if ($identificacion == $identificacionsgc) {
                                //die('iguales');
                                $result = 'equal'; //identificacion igual
                            } else {
                                //die('no iguales');
                                $result = 'noequal'; //identificacion no igual
                            }
                        }

                        $data .= '<tr class="odd"><th>' . $value . '</th><td>' . $str . '</td></tr>';
                        $data_save[$value] = "{$str}";
                    }

                    //echo '<pre>';
                    //print_r($data_save);
                    //echo '</pre>';
                    //die();

                    $dt = time();
                    $fecha_actual = strftime("%Y-%m-%d", $dt);
                    $year_actual = explode('-', $fecha_actual);
                    $year_createc = explode('-', $data_save['Fecha de Venta']);
                    $fact = (string) trim($year_actual[0]);
                    $fcreatec = substr($year_createc[0], 14, 4);
                    //echo('fecha actual:' . $fact . ', fecha createc:' . $fcreatec).'<br />';
                    //echo '<h2>' . $fcreatec . '</h2>';
                    if ($fcreatec !== $fact) {
                        $result = 'nofind'; // Year doesn't match 
                    }
                    //die('result 2: ' . $result);
                    //die('SALIDA ENCUENTRA INDENTIFICACION: '.print_r($data_save));
                    //die('result: '.$result);
                    $this->render('facturacierre', array('id_informacion' => $_POST['Factura']['id_informacion'], 'id_vehiculo' => $_POST['Factura']['id_vehiculo'], 'data' => $data, 'data_save' => $data_save, 'search' => TRUE, 'result' => $result));
                } else {
                    $this->render('facturacierre', array('id_informacion' => $_POST['Factura']['id_informacion'], 'id_vehiculo' => $_POST['Factura']['id_vehiculo'], 'data' => $data, 'data_save' => $data_save, 'search' => FALSE, 'result' => $result));
                }
            }
        }
    }

    function checkCreatec($response) {
        if (!$response) {
            throw new Exception("Error al conectarse con la pirámide");
        }
        return true;
    }

    public function actionFactura($id_vehiculo = NULL, $id_informacion = NULL) {

        $this->render('factura', array('id_vehiculo' => $id_vehiculo, 'id_informacion' => $id_informacion));
    }

    public function actionEntrega($id_informacion = NULL) {
        $model = new GestionEntrega;


        if (isset($_POST['GestionEntrega'])) {
            //die('enter entrega');
            $model->attributes = $_POST['GestionEntrega'];
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            if (isset($_POST['GestionEntrega']['accesorios']) && !empty($_POST['GestionEntrega']['accesorios'])) {
                $array_equip = $_POST['GestionEntrega']['accesorios'];
                $string_equip = '';
                foreach ($array_equip as $value) {
                    $string_equip .= $value . '@';
                }
                $model->accesorios = $string_equip;
            }
            if ($model->save()) {
                $con = Yii::app()->db;
                $sql = "UPDATE gestion_diaria SET seguimiento = 1, paso = '9', status = 1, entrega = 1, cierre = 1, proximo_seguimiento = '{$_POST['GestionEntrega']['agendamiento1']}' WHERE id_informacion = {$_POST['GestionEntrega']['id_informacion']}";
                $request = $con->createCommand($sql)->query();
                $result = TRUE;
                $arr = array('result' => $result, 'id' => $model->id);
                echo json_encode($arr);
                exit();
                //$this->redirect(array('gestionInformacion/seguimiento'));
            }
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion='{$id_informacion}'"
        ));
        $vec = GestionVehiculo::model()->findAll($criteria);
        $this->render('entrega', array('vec' => $vec, 'id_informacion' => $id_informacion));
        //$this->render('entrega', array('id_vehiculo' => $id_vehiculo, 'id_informacion' => $id_informacion));
    }

    public function actionEntregaAjax($id_vehiculo = NULL, $id_informacion = NULL) {
        //die('enter ajax');
        $model = new GestionEntrega;
        /* echo '<pre>';
          print_r($_FILES);
          echo '</pre>';
          die(); */

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionEntrega'])) {
            //die('enter post');
            $model->attributes = $_POST['GestionEntrega'];
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            $model->observaciones = $_POST['GestionEntrega']['observaciones'];
            if (isset($_POST['GestionEntrega']['accesorios']) && !empty($_POST['GestionEntrega']['accesorios'])) {
                $array_equip = $_POST['GestionEntrega']['accesorios'];
                $string_equip = '';
                foreach ($array_equip as $value) {
                    $string_equip .= $value . '@';
                }
                $model->accesorios = $string_equip;
            }
            $archivoThumb = CUploadedFile::getInstance($model, 'firma');
            //die('thumb: '.$archivoThumb);
            $fileName = "{$archivoThumb}";  // file name
            if ($archivoThumb != "") {
                //die('enter file');
                $model->firma = $fileName;
                $archivoThumb->saveAs(Yii::getPathOfAlias("webroot") . "/images/uploads/" . $fileName);
                $link = Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName;
                $image = new EasyImage($link);
                $image->resize(600, 480); // resize images for thumbs
                $image->save(Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName);
            }

            $archivoThumb2 = CUploadedFile::getInstance($model, 'foto_cliente');
            $fileName2 = "{$archivoThumb2}";  // file name
            if ($archivoThumb2 != "") {
                //die('enter file2');
                $model->foto_cliente = $fileName2;
                $archivoThumb2->saveAs(Yii::getPathOfAlias("webroot") . "/images/uploads/" . $fileName2);
                $link2 = Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName2;
                $image = new EasyImage($link2);
                $image->resize(600, 480); // resize images for thumbs
                $image->save(Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName2);
            }
            //die('before save');
            /* if ($model->validate()) {
              //echo 'todo bien';
              die('good');
              } else {

              print_r($model->getErrors());
              die('bad');
              } */
            if ($model->save()) {
                //die('enter save');
                // ENVIO DE EMAIL AL CLIENTE SOBRE ENTREGA DEL VEHICULO------------------------------------

                require_once 'email/mail_func.php';
                //$id_modelo = $this->getIdModelo($id_vehiculo);
                //$ficha_tecnica = $this->getPdf($id_modelo);
                //echo $this->getVersionTestDrive($_POST['GestionEntrega']['id_vehiculo']);
                //die();
                $id_asesor = Yii::app()->user->getId();
                $cargo_id = (int) Yii::app()->user->getState('cargo_id');
                $grupo_id = (int) Yii::app()->user->getState('grupo_id');
                $emailCliente = $this->getEmailCliente($_POST['GestionEntrega']['id_informacion']);
                $ciudadCliente = $this->getCiudad($_POST['GestionEntrega']['id_informacion']);
                $nombre_cliente = $this->getNombresInfo($_POST['GestionEntrega']['id_informacion']) . ' ' . $this->getApellidosInfo($_POST['GestionEntrega']['id_informacion']);
                $asunto = 'Kia Motors Ecuador - Confirmación de Entrega de vehículo Kia ' . $this->getModeloTestDrive($_POST['GestionEntrega']['id_vehiculo']);
                $general = '<body style="margin: 10px;">
                                <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;">
                                    <div align="">
                                    <img src="images/header_mail.jpg"><br><br>
                                    <p style="margin: 2px 0;">Señor(a): ' . $nombre_cliente . '</p>
                                    <p></p>
                                    <p style="margin: 2px 0;">Reciba un cordial saludo de Kia Motors Ecuador.</p>

                                    <p style="margin: 2px 0;">La fecha de entrega de su vehículo está fijada para el ' . date("d") . "/" . date("m") . "/" . date("Y") . ' 
                                        en el concesionario ' . strtoupper($this->getNameConcesionario($id_asesor)) . ' por:</p>
                                        <br />
                                    <table width="600" cellpadding="">
                                    <tr><td><strong>Asesor Comercial:</strong></td><td> ' . $this->getResponsable($id_asesor) . '</td></tr>
                                    <tr><td><strong>Modelo:</strong></td><td> ' . $this->getModeloTestDrive($_POST['GestionEntrega']['id_vehiculo']) . '</td></tr>
                                    <tr><td><strong>Fecha:</strong></td><td>' . date("d") . "/" . date("m") . "/" . date("Y") . '</td></tr>
                                    <tr><td><strong>Hora:</strong></td><td>' . date("H:i:s") . '</p></td></tr>
                                    </table> 
                                    <p style="margin: 2px 0;"><strong>Importante:</strong> La fecha de entrega de su vehículo puede variar por motivos fuera de control de Kia Motors Ecuador, por favor confirmar con su Asesor Comercial antes de acercarse al Concesionario. </p>

                                    <p style="margin: 2px 0;">Saludos cordiales,<br>
                                    SGC<br>
                                    Kia Motors Ecuador
                                    </p>
                                    <br /><br />
                                    </div>
                                    <br />
                                    <table width="600"  cellpadding="0" cellspacing="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                                            <tr>
                                              <td width="228">&nbsp;</td>
                                              <td width="228" style="color:#1f497d">' . $this->getResponsable($id_asesor) . ' - Asesor Ventas Kia</td>
                                            </tr>
                                            <tr>
                                              <td width="178" rowspan="5"><img src="images/logo_pdf2.png" /></td>
                                              <td colspan="2"><strong style="color: #AB1F2C; font-size: 16px;">' . strtoupper($this->getNameConcesionario($id_asesor)) . '</strong></td>
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
                                        <br /><br /><p style="margin: 2px 0;">Nota de descargo: La información contenida en este e-mail es confidencial y sólo puede ser utilizada por el individuo o la compañía a la cual está dirigido. Esta información no debe ser distribuida ni copiada total o parcialmente por ningún medio sin la autorización de AEKIA S.A.
La organización no asume responsabilidad sobre información, opiniones o criterios contenidos en este mail que no esté relacionada con negocios oficiales de nuestra compañía.
</p>
                                </div>
                            </body>';
                //die('table: '.$general);
                $codigohtml = $general;
                $headers = 'From: info@kia.com.ec' . "\r\n";
                $headers .= 'Content-type: text/html' . "\r\n";
                $email = $emailCliente; //email cliente
                $ciudad = $this->getCiudad($id_asesor);
                date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
                $fecha_m = date("d m Y");
                $fecha_m = $this->getFormatFecha($fecha_m);

                $send = sendEmailInfo('info@kia.com.ec', "Kia Motors Ecuador", $email, html_entity_decode($asunto), $codigohtml);

                $nombre_cliente = $this->getNombresInfo($_POST['GestionEntrega']['id_informacion']) . ' ' . $this->getApellidosInfo($_POST['GestionEntrega']['id_informacion']);
                $modelo = $this->getModeloInfo($_POST['GestionEntrega']['id_vehiculo']);
                $ciudadCliente = $this->getCiudadConcesionario($_POST['GestionEntrega']['id_informacion']);
                //die('ciudad cliente: '.$ciudadCliente);
                // ENVIAR EMAIL CON CARTA DE BIENVENIDA AL CLIENTE
                $asunto = '[Kia Motors Ecuador] Bienvenido a la Familia Kia Motors Ecuador';
                $general = '<body style="margin: 10px;">
                                <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;">
                                    <div align="">
                                    <img src="images/header_mail.jpg">
                                    <br>
                                        <p>' . $ciudadCliente . ', ' . $fecha_m . '</p><br /><br />
                                        <p>Señor(a)</p>
                                        <p>' . $nombre_cliente . '</p>
                                        <p>' . $ciudadCliente . '.-</p>
                                        <br />
                                        <p>
                                        KIA MOTORS ECUADOR le da la bienvenida, agradecemos la confianza al haber escogido uno de nuestros vehículos KIA, con la mejor tecnología Coreana. 
                                        </p><br />
                                        <p>
                                        Le recordamos que su vehículo cuenta con una';
                if ($modelo == 86) {
                    $general .= ' garantía de 7 años o 120.000 Km, ';
                } else {
                    $general .=' garantía de 5 años o 100.000 Km, ';
                }
                $general .= 'para mantener dicha garantía, usted debe realizar los mantenimientos en nuestro <a href="https://www.kia.com.ec/concesionarios.html"> concesionario KIA a nivel nacional</a>. 
                                        </p><br />
                                        <p>
                                        Nuestra prioridad es servirle de la mejor manera, por lo que usted tiene a su disposición la nueva línea gratuita de Servicio al Cliente 1800 KIA KIA (1800 542 542), donde Usted podrá obtener información de Vehículos Nuevos, Seminuevos, Talleres de Servicio Autorizado Kia, Costos de Mantenimiento Preventivos, Repuestos y Accesorios, Políticas de Garantías de su Vehículo, etc. Nuestro personal de la línea 1800 KIAKIA podrá ayudarle también a realizar su próxima cita de mantenimiento para que Ud. pueda continuar disfrutando de su vehículo Kia en todo momento, basta con llamar y uno de nuestros asesores podrá brindarle el mejor servicio para su próxima cita.
                                        </p><br />
                                        <p>
                                        Para complementar nuestro servicio hemos incorporado para usted, totalmente gratis por un año, un producto denominado “Asistencia KIA” el cual le asiste ante cualquier desperfecto mecánico, las 24 horas del día, los 365 días del año. La cobertura del producto “Asistencia KIA” comprende de:
                                        </p><br />
                                        <ul>
                                            <li><strong>Remolque o traslado de su vehículo en caso de avería o accidente hasta el concesionario Kia más cercano</strong></ol>
                                            <li><strong>Auxilio Mecánico: </strong>asistencia en caso llanta baja, falta de combustible, carga de batería.</ol>
                                            <li><strong>Traslado en ambulancia en caso accidente de tránsito</strong></ol>
                                            <li><strong>Cobertura en caso de viaje: </strong>en caso de que su vehículo quede inmovilizado, sufra hurto simple o calificado se cubrirá la estancia y desplazamiento de los ocupantes.</ol>
                                            <li><strong>Transporte ó custodia del vehículo reparado o recuperado en caso de encontrarse en una ciudad que no es de su residencia. </strong></ol>
                                            <li><strong>Servicio de conductor profesional: </strong>en caso de imposibilidad del conductor para conducir el vehículo por muerte, accidente o cualquier enfermedad grave.</ol>
                                        </ul>
                                        <p>
                                        Si desea obtener mayor información le invitamos a visitar y registrarse en nuestra página Web en www.kia.com.ec o comunicándose a 1800KIAKIA.
                                        </p>
                                        <p>Para KIA MOTORS ECUADOR es importante poner a su disposición productos de calidad para poder brindarle el mejor servicio.</p>
										
										<a href="https://www.kia.com.ec/intranet/usuario/index.php/site/hojaentregacliente?id_informacion=' . $_POST['GestionEntrega']['id_informacion'] . '&id_vehiculo=' . $_POST['GestionEntrega']['id_vehiculo'] . '">Hoja de Entrega</a>
                                        
                                        <p><strong>Atentamente</strong></p>
                                        <p><strong>KIA MOTORS ECUADOR</strong></p>
                                        
                                            <br /><br /><p>Nota de descargo: La información contenida en este e-mail es confidencial y sólo puede ser utilizada por el individuo o la compañía a la cual está dirigido. Esta información no debe ser distribuida ni copiada total o parcialmente por ningún medio sin la autorización de AEKIA S.A.
La organización no asume responsabilidad sobre información, opiniones o criterios contenidos en este mail que no esté relacionada con negocios oficiales de nuestra compañía.
</p>
                                        <img src="images/footer.png">
                                    </div>
                                </div>
                            </body>';        //die('table: '.$general);
                $codigohtml = $general;
                $headers = 'From: info@kia.com.ec' . "\r\n";
                $headers .= 'Content-type: text/html' . "\r\n";
                $email = $emailCliente; //email cliente
                //$email = 'alkanware@gmail.com'; //email cliente

                $ccarray = array('jorge.rodriguez@ariadna.com.ec', 'gansaldo72@hotmail.com');

                if (sendEmailInfoClienteConcesionario('info@kia.com.ec', "Kia Motors Ecuador", $email, $ccarray, html_entity_decode($asunto), $codigohtml)) {
                    //die('send emaail carta');
                }
                //die('before request');
                $con = Yii::app()->db;
                $sql = "UPDATE gestion_diaria SET seguimiento = 1, paso = '9', status = 1, entrega = 1, cierre = 1 WHERE id_informacion = {$_POST['GestionEntrega']['id_informacion']}";
                $request = $con->createCommand($sql)->query();
                $result = TRUE;
                $arr = array('result' => $result, 'id' => $model->id);
                echo json_encode($arr);
                exit();
                //$this->redirect(array('gestionInformacion/seguimiento'));
            }
        }
    }

    public function actionBienvenida() {
        //die('enter bienvenida');
        $id_vehiculo = isset($_POST["id_vehiculo"]) ? $_POST["id_vehiculo"] : "";
        $id_informacion = isset($_POST["id_informacion"]) ? $_POST["id_informacion"] : "";
        //die('id_informacion: '.$id_informacion);
        $nombre_cliente = $this->getNombresInfo($id_informacion) . ' ' . $this->getApellidosInfo($id_informacion);
        // ENVIAR EMAIL CON CARTA DE BIENVENIDA AL CLIENTE
        require_once 'email/mail_func.php';
        $emailCliente = $this->getEmailCliente($id_informacion);
        $asunto = '[Kia Motors Ecuador] Bienvenido a la Familia Kia  Motors Ecuador';
        $general = '<body style="margin: 10px;">
                    <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;">
                        <div align=""><br>
                            <p>Quito, 5 de agosto del 2015</p><br /><br />
                            <p>Apreciado Cliente</p>
                            <p>' . $nombre_cliente . '</p>
                            <p>Ciudad.-</p>
                            <br />
                            <p>
                            KIA MOTORS le da la bienvenida, agradecemos la confianza al haber escogido uno de nuestros vehículos KIA, con la mejor tecnología Coreana. 
                            </p><br />
                            <p>
                            Le recordamos que para mantener su vehículo cuenta con una garantía de 5 años o 100.000 Km. (EN CASO DE K3000 se debe poner garantía de 7 años o 120.000 Km) para mantener dicha garantía, usted debe realizar los mantenimientos en nuestro concesionario KIA a nivel nacional. 
                            </p><br />
                            <p>
                            Nuestra prioridad es servirle de la mejor manera, por lo que usted tiene a su disposición la nueva línea gratuita de Servicio al Cliente 1800 KIA KIA, donde Usted podrá obtener información de Vehículos Nuevos, Seminuevos, Talleres de Servicio Autorizado Kia, Costos de Mantenimiento Preventivos, Repuestos y Accesorios, Políticas de Garantías de su Vehículo, etc. Nuestro personal de la línea 1800 KIAKIA podrá ayudarle también a realizar su próxima cita de mantenimiento para que Ud. pueda continuar disfrutando de su vehículo Kia en todo momento, basta con llamar y uno de nuestros asesores podrá brindarle el mejor servicio para su próxima cita.
                            </p><br />
                            <p>
                            Para complementar nuestro servicio hemos incorporado para usted, totalmente gratis por un año, un producto denominado “Asistencia KIA” el cual le asiste ante cualquier desperfecto mecánico, las 24 horas del día, los 365 días del año. La cobertura del producto “Asistencia KIA” comprende de:
                            </p><br />
                            <ul>
                                <li><strong>Remolque o traslado de su vehículo en caso de avería o accidente hasta el concesionario Kia más cercano</strong></ol>
                                <li><strong>Auxilio Mecánico: </strong>asistencia en caso llanta baja, falta de combustible, carga de batería.</ol>
                                <li><strong>Traslado en ambulancia en caso accidente de tránsito</strong></ol>
                                <li><strong>Cobertura en caso de viaje: </strong>en caso de que su vehículo quede inmovilizado, sufra hurto simple o calificado se cubrirá la estancia y desplazamiento de los ocupantes.</ol>
                                <li><strong>Transporte ó custodia del vehículo reparado o recuperado en caso de encontrarse en una ciudad que no es de su residencia. </strong></ol>
                                <li><strong>Servicio de conductor profesional: </strong>en caso de imposibilidad del conductor para conducir el vehículo por muerte, accidente o cualquier enfermedad grave.</ol>
                            </ul>
                            <p>
                            Si desea obtener mayor información le invitamos a visitar y registrarse en nuestra página Web en www.kia.com.ec o comunicándose a 1800KIAKIA.
                            </p>
                            <p>Para KIA MOTORS es importante poner a su disposición productos de calidad para poder brindarle el mejor servicio.</p>

                            <p><strong>Atentamente</strong></p>
                            <p><strong>KIA MOTORS</strong></p>
                            <p>Para descargar la hoja de entrega hacer clic <a href="https://www.kia.com.ec/intranet/usuario/index.php/site/hojaentregacliente?id_informacion=' . $id_informacion . '&id_vehiculo=' . $id_vehiculo . '">Aquí</a></p>
                            
                        </div>
                    </div>
                </body>';        //die('table: '.$general);
        $codigohtml = $general;
        $headers = 'From: info@kia.com.ec' . "\r\n";
        $headers .= 'Content-type: text/html' . "\r\n";
        $email = $emailCliente; //email cliente
        //$email = 'carli-c@hotmail.com'; //email cliente

        $ccarray = array('jorge.rodriguez@ariadna.com.ec', 'gansaldo72@hotmail.com');

        if (sendEmailInfoClienteConcesionario('info@kia.com.ec', "Kia Motors Ecuador", $email, $ccarray, html_entity_decode($asunto), $codigohtml)) {
            
        }
    }

    public function actionSeguimiento($id_vehiculo = NULL, $id_informacion = NULL) {
        $this->render('seguimiento', array('id_vehiculo' => $id_vehiculo, 'id_informacion' => $id_informacion));
    }

    public function actionGetciudades() {
        $idProvincia = isset($_POST["id"]) ? $_POST["id"] : "";
        //die('id provincia: '.$idProvincia);
        $data = '<option value="">--Seleccione una ciudad--</option>';
        $data .= $this->getNombres($idProvincia);

        $options = array('options' => $data);
        //echo json_encode($options);
        echo $data;
    }

    private function getNombres($idProvincia) {
        $criteria = new CDbCriteria(array(
            'condition' => "id_provincia={$idProvincia}",
            'order' => 'nombre asc'
        ));
        $ciudades = TblCiudades::model()->findAll($criteria);
        $data = '';
        foreach ($ciudades as $ciudad) {
            $data .= '<option value="' . $ciudad['id_ciudad'] . '">' . $ciudad['nombre'] . '</option>';
        }
        return $data;
    }

    public function actionGetConcesionarios() {
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        $criteria = new CDbCriteria(array(
            'condition' => "id_grupo={$id}",
            'order' => 'nombre asc'
        ));
        $conc = Concesionarios::model()->findAll($criteria);
        $data = '<option value="">--Seleccione concesionario--</option>';
        foreach ($conc as $ciudad) {
            $data .= '<option value="' . $ciudad['dealer_id'] . '">' . $ciudad['nombre'] . '</option>';
        }
        echo $data;
    }

    public function actionGetmodelos() {
        $modeloAuto = isset($_POST["marca"]) ? $_POST["marca"] : "";
        $data = '<option value="">--Seleccione un modelo--</option>';
        $data .= $this->getModelosAutos($modeloAuto);

//        $options = array('options' => $data);
//        echo json_encode($options);

        echo $data;
    }

    public function actionGetyears() {
        $modelo = isset($_POST["modelo"]) ? $_POST["modelo"] : "";
        $year = isset($_POST["year"]) ? $_POST["year"] : "";
        $params = explode('@', $modelo);
        //$data = '<option value="">--Seleccione el año--</option>';
        //$data .= $this->getYears($params[1], $params[2], $year);
        $value = $this->getYears($params[1], $params[2], $year);
        //$options = array('options' => $data);
        //echo json_encode($options);
        echo $value;
    }

    public function actionGetAsesores() {
        $dealer_id = isset($_POST["dealer_id"]) ? $_POST["dealer_id"] : "";
        $tipo = isset($_POST["tipo"]) ? $_POST["tipo"] : "";
        $cargo_id = (int) Yii::app()->user->getState('cargo_id');
        $area_id = (int) Yii::app()->user->getState('area_id');
        $con = Yii::app()->db;
        if ($cargo_id == 69 && $tipo == 'seg') { // GERENTE COMERCIAL
            //die('enter gerente');
            $sql = "SELECT * FROM usuarios WHERE dealers_id = {$dealer_id} AND cargo_id IN (71,70) ORDER BY nombres ASC";
        }
        if ($cargo_id == 69 && $tipo == 'exo') { // GERENTE COMERCIAL
            //die('enter gerente');
            $sql = "SELECT * FROM usuarios WHERE dealers_id = {$dealer_id} AND cargo_id IN (75) ORDER BY nombres ASC";
        }
        if ($cargo_id == 69 && $tipo == 'bdc') { // GERENTE COMERCIAL
            //die('enter gerente');
            $sql = "SELECT * FROM usuarios WHERE dealers_id = {$dealer_id} AND cargo_id IN (72,73) ORDER BY nombres ASC";
        }
        if ($cargo_id == 70) { // JEFE SUCURSAL
            $sql = "SELECT * FROM usuarios WHERE dealers_id = {$dealer_id} AND cargo_id IN (71,70) ORDER BY nombres ASC";
        }
        if ($area_id == 4 || $area_id == 12 || $area_id == 13 || $area_id == 14) {
            switch ($tipo) {
                case 'exo':
                    $sql = "SELECT * FROM usuarios WHERE dealers_id = {$dealer_id} AND cargo_id IN (75) ORDER BY nombres ASC";
                    break;
                case 'seg':
                    $sql = "SELECT * FROM usuarios WHERE dealers_id = {$dealer_id} AND cargo_id IN (71,70) ORDER BY nombres ASC";
                    break;
                case 'bdc':
                    $sql = "SELECT * FROM usuarios WHERE dealers_id = {$dealer_id} AND cargo_id IN (73,72) ORDER BY nombres ASC";
                    break;

                default:
                    $sql = "SELECT * FROM usuarios WHERE dealers_id = {$dealer_id} AND cargo_id IN (71,70) ORDER BY nombres ASC";
                    break;
            }
        }
        /* else{
          $sql = "SELECT * FROM usuarios WHERE dealers_id = {$dealer_id} AND cargo_id = 71 ORDER BY nombres ASC";
          } */

        //die($sql);
        $requestr1 = $con->createCommand($sql);
        $requestr1 = $requestr1->queryAll();
        $data = '<option value="">--Seleccione Asesor--</option>';
        $data .= '<option value="all">Todos</option>';
        foreach ($requestr1 as $value) {
            $data .= '<option value="' . $value['id'] . '">';
            $data .= $this->getResponsableNombres($value['id']);
            $data .= '</option>';
        }

        echo $data;
    }

    public function actionGetmodelsyears() {
        //die('enter get years');
        $modelo = isset($_POST["modelo"]) ? $_POST["modelo"] : "";
        $params = explode('@', $modelo);


        $data = '<option value="">--Seleccione el año--</option>';
        $criteria = new CDbCriteria(array(
            'condition' => "modelo='{$params[1]}' AND submodelo='{$params[2]}' AND year > '2002'",
            'group' => 'year',
            'order' => 'year desc'
        ));
        $modelos = Marcas::model()->findAll($criteria);
        foreach ($modelos as $model) {
            $data .= '<option value="' . $model['year'] . '">' . $model['year'] . '</option>';
        }
        echo $data;
    }

    public function actionSignature($id_informacion = NULL) {
        //die('id informacion: '.$id_informacion);
        $this->render('signature', array('id_informacion' => $id_informacion));
    }

    private function getModelosAutos($modeloAuto) {
        //$sql = "SELECT id, modelo, submodelo FROM tbl_marcas WHERE marca='{$modeloAuto}' GROUP BY submodelo ORDER BY modelo";

        $criteria = new CDbCriteria(array(
            'condition' => "marca='{$modeloAuto}' AND year > 2002",
            'group' => 'modelo,submodelo',
            'order' => 'modelo asc'
        ));
        $modelos = Marcas::model()->findAll($criteria);
        $data = '';
        foreach ($modelos as $model) {
            $data .= '<option value="' . $model['id'] . '@' . $model['modelo'] . '@' . $model['submodelo'] . '">' . $model['modelo'] . ' ' . $model['submodelo'] . '</option>';
        }
        return $data;
    }

    private function getYears($modelo, $submodelo, $year) {
        //$sql = "SELECT id, modelo, submodelo FROM tbl_marcas WHERE marca='{$modeloAuto}' GROUP BY submodelo ORDER BY modelo";

        $criteria = new CDbCriteria(array(
            'condition' => "modelo='{$modelo}' AND submodelo='{$submodelo}' AND year='{$year}'"
        ));
        $nums = Marcas::model()->count($criteria);
        if ($nums > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function actionGrabarFirma() {
        $rnd = rand(0, 9999);
        $date = date("Ymdhis");
        $data = substr($_POST['imageData'], strpos($_POST['imageData'], ",") + 1);
        $decodedData = base64_decode($data);
        $img = $_POST['imageData'];
        $ruta = Yii::app()->basePath . '/../upload/firma/';
        //die('ruta: '.$ruta);
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $fileName = $_POST['id_informacion'] . md5($rnd . $date) . "_firma.png";
        $file = $ruta . $_POST['id_informacion'] . md5($rnd . $date) . "_firma.png";

        $model = new GestionFirma;
        $model->firma = $fileName;
        $model->id_informacion = $_POST['id_informacion'];
        date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
        $model->fecha = date("Y-m-d H:i:s");
        $model->tipo = $_POST['tipo'];
        $model->save();
        if (file_put_contents($file, $data)) {
            echo '1';
        }
        // header('Location: '.$_POST['return_url']);
    }

    public function actionGrabarFirmaAjax() {
        $rnd = rand(0, 9999);
        $date = date("Ymdhis");
        $data = substr($_POST['imageData'], strpos($_POST['imageData'], ",") + 1);
        $decodedData = base64_decode($data);
        $img = $_POST['imageData'];
        $ruta = Yii::app()->basePath . '/../upload/firma/';
        //die('ruta: '.$ruta);
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $fileName = $_POST['id_informacion'] . md5($rnd . $date) . "_firma.png";
        $file = $ruta . $_POST['id_informacion'] . md5($rnd . $date) . "_firma.png";


        $model = new GestionFirma;
        $model->firma = $fileName;
        $model->id_informacion = $_POST['id_informacion'];
        date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
        $model->fecha = date("Y-m-d H:i:s");
        $model->tipo = $_POST['tipo'];
        $model->save();
        if (file_put_contents($file, $data)) {
            $result = true;
            $options = array('id' => $model->id, 'result' => $result);
            echo json_encode($options);
        }
        // header('Location: '.$_POST['return_url']);
    }

    public function actionGetfirma() {
        $criteria = new CDbCriteria(array(
            'condition' => "id_informacion={$_POST['id_informacion']}"
        ));
        $firma = GestionFirma::model()->find($criteria);
        $options = array('firma' => $firma->firma);
        echo json_encode($options);
        //return $firma->firma;
    }

    public function actionGetfirmaAjax() {
        $criteria = new CDbCriteria(array(
            'condition' => "id={$_POST['id']}"
        ));
        $firma = GestionFirma::model()->find($criteria);
        $options = array('firma' => $firma->firma);
        echo json_encode($options);
        //return $firma->firma;
    }

    public function loadModelFirma($id) {
        $model = GestionFirma::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function actionSetcl() {
        $con = Yii::app()->db;
        $sql = "delete from gestion_informacion";
        $request = $con->createCommand($sql)->query();
    }

    public function actionCancelarcotizacion() {
        date_default_timezone_set("America/Bogota");
        $p = new CHtmlPurifier();
        $id = $p->purify($_POST["rs"]);
        if ($id > 0) {
            $cotizacion = Cotizacionesnodeseadas::model()->find(array('condition' => 'id=' . $id));
            $cotizacion->fecharealizado = date('Y-m-d H:i:s');
            $cotizacion->realizado = 'NO';
            if ($cotizacion->save()) {
                //if (Yii::app()->db2->createCommand("UPDATE atencion_detalle SET encuestado=1 WHERE id_atencion_detalle=:RListID")->bindValues(array(':RListID' => $id))->execute()) {
                echo 1;
                //}
            } else {
                echo 0;
            }
        }
        die();
    }

    public function actionTraerversioncotizacion() {
        date_default_timezone_set("America/Bogota");
        $p = new CHtmlPurifier();
        $id = $p->purify($_POST["vl"]);
        $idv = $p->purify($_POST["rs"]);
        $sql = 'SELECT * FROM versiones where id_modelos=' . (int) $id;
        $versiones = Yii::app()->db2->createCommand($sql)->queryAll();
        if (!empty($versiones)) {
            echo '<select name="data[version]" required id="data_version" class = "form-control" >';
            $s = '';
            foreach ($versiones as $m) {
                if ($m['id_versiones'] == $idv)
                    $s = 'selected';
                else
                    $s = '';

                echo '<option value="' . $m["id_versiones"] . '" ' . $s . '>' . $m["nombre_version"] . ' - ' . $m["precio"] . '</option>';
            }
            echo '</select>';
        }else {
            echo 0;
        }
    }

    public function actionTraerciudadcotizacion() {
        date_default_timezone_set("America/Bogota");
        $p = new CHtmlPurifier();
        $id = $p->purify($_POST["vl"]);
        $idv = $p->purify($_POST["rs"]);
        $sql = 'SELECT * FROM dealers where cityid=' . (int) $id;
        $versiones = Yii::app()->db2->createCommand($sql)->queryAll();
        if (!empty($versiones)) {
            echo '<select name="data[concesionario]" required id="data_concesionario" class = "form-control" >';
            $s = '';
            foreach ($versiones as $m) {
                if ($m['dealerid'] == $idv)
                    $s = 'selected';
                else
                    $s = '';

                echo '<option value="' . $m["dealerid"] . '" ' . $s . '>' . $m["name"] . ' - ' . $m["direccion"] . '</option>';
            }
            echo '</select>';
        }else {
            echo 0;
        }
    }

    public function actionGetfinanciamiento() {
        $type = isset($_POST["type"]) ? $_POST["type"] : "";
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        switch ($type) {
            case 'first':
                $cri5 = new CDbCriteria;
                $cri5->condition = "id={$id}";
                $gf = GestionFinanciamiento::model()->find($cri5);
                $options = array('valor' => $gf->precio_vehiculo, 'monto_financiar' => $gf->valor_financiamiento, 'entrada' => $gf->cuota_inicial, 'plazo' => $gf->plazos, 'tasa' => $gf->tasa, 'cuota_mensual' => $gf->cuota_mensual);
                echo json_encode($options);
                break;
            case 'second':
                $cri5 = new CDbCriteria;
                $cri5->condition = "id={$id}";
                $gf = GestionFinanciamientoOp::model()->find($cri5);
                $options = array('valor' => $gf->precio_vehiculo, 'monto_financiar' => $gf->valor_financiamiento, 'entrada' => $gf->cuota_inicial, 'plazo' => $gf->plazos, 'tasa' => $gf->tasa, 'cuota_mensual' => $gf->cuota_mensual);
                echo json_encode($options);
                break;
            default:
                break;
        }
    }

    public function actionGetversiones() {
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        $criteria = new CDbCriteria(array(
            "condition" => "id_modelos = '{$id}'",
        ));
        $modelo = Versiones::model()->findAll($criteria);
        $data = '';
        if ($modelo) {
            foreach ($modelo as $value) {
                $data .= '<div class="col-md-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="' . $value['id_versiones'] . '" name="version[]" id="">
                                ' . $value['nombre_version'] . '
                            </label>
                        </div>
                    </div>';
            }
        } else {
            $data .= 'NA';
        }
        $options = array('options' => $data);
        echo json_encode($options);
    }

    public function actionCotizacion($id_informacion, $id_vehiculo) {
        $this->layout = '//layouts/call-print';
        //die('enter prof');
        $con = Yii::app()->db;
        $num_solicitud = $this->getLastSolicitudCotizacion();
        //die('num solicitud:'.$num_solicitud);
        $hoja_solicitud = new GestionSolicitud;
        $hoja_solicitud->id_vehiculo = $id_vehiculo;
        date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
        $hoja_solicitud->fecha = date("Y-m-d H:i:s");
        $hoja_solicitud->save();



        # mPDF
        $mPDF1 = Yii::app()->ePdf->mpdf();
        $mPDF1->setFooter('{PAGENO}');
        $mPDF1->SetTitle('Solicitud de Crédito');

        # You can easily override default constructor's params
        $mPDF1 = Yii::app()->ePdf->mpdf('', 'A4');

        //$mPDF1->WriteHTML($this->render('pdf2', array('data' => $request), true));
        # Load a stylesheet
        $stylesheet = file_get_contents(Yii::getPathOfAlias('webroot.bootstrap.css') . '/bootstrap.css');
        $mPDF1->WriteHTML($stylesheet, 1);
        # renderPartial (only 'view' of current controller)
        //$this->render('cotizacion', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo, 'id_hoja' => $num_solicitud));
        $mPDF1->WriteHTML($this->renderPartial('cotizacion', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo, 'id_hoja' => $num_solicitud), true));

        # Renders image
        //$mPDF1->WriteHTML(CHtml::image(Yii::getPathOfAlias('webroot.css') . '/bg.gif' ));
        # Outputs ready PDF
        $mPDF1->Output('solicitud-de-credito.pdf', 'I');
    }

    /*public function actionAlterTable() {
        $sql = "DELETE from gestion_informacion where id = 2673";
        $con = Yii::app()->db;
        $request = $con->createCommand($sql)->execute();
        echo 'result: ' . $request;
    }*/

    //
}
