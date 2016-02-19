<?php

class CencuestadoscquestionarioController extends Controller {

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
                'actions' => array('nocompradoresadminencuestados', 'atenciondetalleencuestado', 'nocompradoresadmin', 'search', 'atenciondetalle', 'nocotizacionok', 'nocompradores', 'cotizaciones', 'create', 'cotizacionok', 'update', 'admin', ''
                    . '', 'encuestas', 'encuestar', 'encuestarusuario', 'finalizarencuesta', 'seleccionar'),
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

    public function actionGenerar($id) {
        $encuestados = Cencuestados::model()->findAll(array('condition' => 'estado ="ACTIVO" and cquestionario_id =' . $id));
        $questionario = Cquestionario::model()->findByPk($id);
        $cargo_id = Cargo::model()->find(array('condition' => 'codigo = "' . Constantes::CDG . '"'));

        $asesores = Usuarios::model()->findAll(array("condition" => 'estado = "ACTIVO" and cargo_id =' . $cargo_id->id));


        $asesoresYaAsignados = Cencuestadoscquestionario::model()->findAll(array("condition" => "cquestionario_id = $id"));

        if (!empty($_POST)) {
            $encuestadosEnvio = $_POST['check'];
            /* echo '<pre>';
              print_r($encuestadosEnvio);
              echo count($encuestadosEnvio);
              echo '</pre>';
              die(); */
            $totalEncuestados = count($encuestados);
            $totalParaAsesores = number_format($totalEncuestados / count($encuestadosEnvio), 0);
            //Verificar si ya hay registros de esta encuesta en la tabla de cencuestadosquestionario y si existe eliminanos
            if (!empty($asesoresYaAsignados)) {
                Cencuestadoscquestionario::model()->deleteAll(array('condition' => 'cquestionario_id=' . $id));
            }

            //Almacenar en la tabla de encuestadosQuestionario para que se habiliten las encuestas
            $error = 0;
            $contTPA = 0;
            $ua = 0;
            $min = 0;
            foreach ($asesores as $as) {

                if (!empty($encuestadosEnvio[$as->id])) {
                    $ua ++;
                    $contTPA = 0;
                    $min = ($ua * $totalParaAsesores) - $totalParaAsesores;
                    $usuarioDato = Usuarios::model()->findByPk($encuestadosEnvio[$as->id]);

                    foreach ($encuestados as $row) {

                        if ($contTPA < ($ua * $totalParaAsesores) && $contTPA >= $min) {
                            //	echo 'entra->'.$contTPA.' con-->'. $row->id.'<br>';
                            if (!empty($row->id)) {
                                $model = new Cencuestadoscquestionario();
                                $model->cencuestados_id = $row->id;
                                $model->cquestionario_id = $id;
                                $model->usuarios_id = $encuestadosEnvio[$as->id];
                                $model->estado = 'PENDIENTE';
                                if (!$model->save())
                                    $error++;
                            }
                        }
                        $contTPA++;
                    }

                    //ENVIAMOS UN CORREO DE NOTIFICACION CUANDO EL USUARIO TIENE ENCUESTADOS POR REALIZAR
                    if (!empty($usuarioDato)) {
                        $url = "https://" . $_SERVER['HTTP_HOST'];
                        require_once 'email/mail_func.php';
                        $asunto = 'Notificación de encuestas';
                        $general = '<body style="margin: 10px;">
									<div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;margin:auto">
										<div width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;margin:auto align="center"><img src="images/header_mail.jpg"/><br><div>';

                        $general .= '<div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;margin:auto;text-align:left">Estimad@: <b>' . utf8_decode(utf8_encode($usuarioDato->nombres)) . ' ' . utf8_decode(utf8_encode($usuarioDato->apellido)) . '</b> se le ha asignado nuevas encuestas que deben ser completadas totalmente desde el sistema.</div>';
                        $general .= '<div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;margin:auto;text-align:left">Recuerde que la fecha de inicio de la encuesta es <b>' . $questionario->fechainicio . '</b> y la de caducidad es el <b>' . $questionario->fechafin . '</b>.</div>';
                        $general .= '<div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;margin:auto;text-align:left">Para acceder al sistema realice un clic <a href="' . $url . Yii::app()->request->baseUrl . '/index.php/site/login">aqu&iacute;.</a></div>';

                        $general.=' <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;margin:auto;text-align:left"><img src="images/footer_mail.jpg"/></dvi>
										</div>
									</div>
								</body>';
                        $codigohtml = $general;
                        $tipo = 'informativo';
                        $headers = 'From: info@kia.com.ec' . "\r\n";
                        $headers .= 'Content-type: text/html' . "\r\n";
                        $email = $usuarioDato->correo; //email administrador 



                        if (!sendEmailInfoD('info@kia.com.ec', utf8_decode(utf8_encode("Kia -  Sistema de Prospección")), $email, utf8_decode(utf8_encode($asunto)), utf8_decode(utf8_encode($codigohtml)), $tipo)) {
                            Yii::app()->user->setFlash('success', '<div class="exitoRegistro" style="text-align:justify"><h1>Se produjo un error al notificar al usuario: </h1><p>Estimad@ <b>' . utf8_decode(utf8_encode($usuarioDato->nombres . ' ' . $usuarioDato->apellido)) . '</b> intente recargando la p&acute;gina nuevamente.</div>');
                            $this->redirect(array('cencuestadoscquestionario/generar/id/' . $id));
                        }
                    }
                }
            }
            if ($error == 0) {
                Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
                $this->redirect(array('cencuestadoscquestionario/generar/id/' . $id));
            }
        }

        $this->render('generar', array(
            'asesores' => $asesores,
            'encuestados' => $encuestados,
            'questionario' => $questionario,
            'asesoresYaAsignados' => $asesoresYaAsignados,
            'id' => $id,
                )
        );
    }

    public function actionSeleccionar($id) {
        $questionario = Cquestionario::model()->findByPk($id);
        $models = new UploadForm;
        //llamadas de clases EXCEL
        date_default_timezone_set("America/Bogota");
        $p = new CHtmlPurifier();

        $phpExcelPath = Yii::getPathOfAlias('ext.reader');
        include($phpExcelPath . "/" . 'php-excel-reader/excel_reader2.php');
        include($phpExcelPath . "/" . 'SpreadsheetReader.php');

        //Archivo a leer
        //$Filepath=Yii::app()->basePath . '/../upload/example.xls';

        $StartMem = memory_get_usage();
        $uploadedFile = CUploadedFile::getInstance($models, 'upload_file');

        if (!empty($uploadedFile) && $uploadedFile != '') {

            $error = 0;
            $rnd = rand(0, 9999);
            $date = date("Ymdhis");
            $extension = explode('.', $uploadedFile);

            switch (strtolower($extension[1])) {
                case 'xls':
                    $error = 0;
                    break;
                case 'xlsx':
                    $error = 0;
                    break;
                default:
                    Yii::app()->user->setFlash('error', "El archivo que usted ha enviado no es un EXCEL.");
                    $this->redirect(array('cencuestadoscquestionario/seleccionar/' . $id));
                    $error = 1;
                    break;
            }



            if ($error == 0) {

                $fileName = md5($rnd . $date) . '.' . $extension[1];

                /*                 * *****SUBIR IMAGEN************ */

                //print_r($_FILES['userfile']['tmp_name']);
                //print_r(CUploadedFile::getInstance($_FILES));
                //die();
                if ($uploadedFile->saveAs(Yii::app()->basePath . '/../upload/encuestados/' . $fileName)) {
                    try {
                        $errorSave = "";
                        $uploadfile = Yii::app()->basePath . '/../upload/encuestados/' . $fileName;
                        //VERIFICAMOS PARA ELIMINAR LOS DATOS ANTERIORES
                        if ($_POST["eliminar"] == 'SI') {
                            if (Cencuestados::model()->findAll(array('condition' => 'cquestionario_id=' . $id))) {
                                Cencuestados::model()->deleteAll(array('condition' => 'cquestionario_id=' . $id));
                            }
                        }


                        $Spreadsheet = new SpreadsheetReader($uploadfile);
                        $BaseMem = memory_get_usage();
                        $Sheets = $Spreadsheet->Sheets();
                        foreach ($Sheets as $Index => $Name) {
                            $Time = microtime(true);
                            $Spreadsheet->ChangeSheet($Index);

                            foreach ($Spreadsheet as $Key => $Row) {

                                print_r($Row);

                                if (!empty($Row)) {

                                    $model = new Cencuestados;
                                    if ($Row[0] != "" && $Row[1] != "" && $Row[2] != "" && $Row[3] != "" && $Row[4] != "" && $Row[5] != "") {
                                        $model->nombre = $p->purify($Row[0]);
                                        $model->telefono = $p->purify($Row[1]);
                                        $model->celular = $p->purify($Row[2]);
                                        $model->email = $p->purify($Row[3]);
                                        $model->ciudad = $p->purify($Row[4]);
                                        $model->estado = $p->purify($Row[5]);
                                        $model->cquestionario_id = $id;
                                        if (!$model->save()) {
                                            $errorSave++;
                                        } else
                                            $errorSave = 0;
                                    }

                                    //echo $Row[0].' '.$Row[1].' '.$Row[2].' '.$Row[3].' '.$Row[4].' '.$Row[5].'<br>';
                                }
                                else {
                                    var_dump($Row);
                                }
                                $CurrentMem = memory_get_usage();
                            }
                        }
                        //die();
                        if ($errorSave == 0) {
                            Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
                            $this->redirect(array('cencuestadoscquestionario/seleccionar/' . $id));
                            die();
                        } else {
                            Yii::app()->user->setFlash('error', "Se han prducido " . $errorSave . " error/es al cargar tu archivo");
                            $this->redirect(array('cencuestadoscquestionario/seleccionar/' . $id));
                        }
                    } catch (Exception $E) {
                        echo $E->getMessage();
                    }
                } else {
                    Yii::app()->user->setFlash('error', "El archivo que usted ha enviado no se ha podido cargar.");
                    $this->redirect(array('cencuestadoscquestionario/seleccionar/' . $id));
                }
            }
        }


        /* OBTENER LOS USUARIOS SEGUN LA BASE QUE SE SELECCIONO EN LA ENCUESTA */
        $totalUsuariosExistentes = 0;
        switch ($questionario->cbasedatos_id) {
            case '1':
                /* OBTENER LOS USUARIOS DE LA BASE DE DATOS TEST DRIVE, LAS TABLAS INVOLUCRADAS SON gestión_test_drive Y gestion_informacion */
                $sql = 'SELECT DISTINCT gi.fecha, gi.nombres, gi.apellidos, gi.email, gi.celular, gi.telefono_oficina, gi.ciudad_conc
	                    FROM gestion_informacion gi 
	                        inner join gestion_test_drive gt 
	                            on gi.id = gt.id_informacion
                        WHERE gt.test_drive = 1 and gt.order=1
                        ORDER BY gi.fecha DESC';
                $totalUsuariosExistentes = Yii::app()->db->createCommand($sql)->queryAll();
                break;
            case '2':
                $sql = 'SELECT DISTINCT gi.nombres, gi.apellidos, gi.email, gi.celular, gi.telefono_oficina, gi.ciudad_conc
                    FROM gestion_informacion gi 
                        inner join gestion_nueva_cotizacion gt 
                            on gi.id_cotizacion = gt.id';
                $totalUsuariosExistentes = Yii::app()->db->createCommand($sql)->queryAll();
                ;
                break;
            case '4':
                /* OBTENER LOS USUARIOS DE LA BASE DE DATOS TEST DRIVE, LAS TABLAS INVOLUCRADAS SON gestión_test_drive Y gestion_informacion */
                $sql = 'SELECT DISTINCT gi.fecha, gi.nombres, gi.apellidos, gi.email, gi.celular, gi.telefono_oficina, gi.ciudad_conc
                        FROM gestion_informacion gi 
                            inner join gestion_test_drive gt 
                                on gi.id = gt.id_informacion
                        WHERE gt.test_drive = 0 and gt.order=1
                        ORDER BY  gi.fecha DESC';
                $totalUsuariosExistentes = Yii::app()->db->createCommand($sql)->queryAll();
                break;
            default:
                $sql = 'SELECT DISTINCT count(*) as total
					FROM encuestas e 
					inner join atencion_detalle a 
					on e.id_atencion_detalle = a.id_atencion_detalle 

					LIMIT 100;
                    ';
                //echo $sql;
                $totalUsuariosExistentes = Yii::app()->db2->createCommand($sql)->queryAll();
                ;
                break;
        }

        /* REGISTRAMOS POR LOS FILTROS */
        if (!empty($_POST['Usuarios'])) {
            $p = new CHtmlPurifier();

            //ELIMINAR SI HAY PERSONAS PARA ELIMINAR
            if (Cencuestados::model()->findAll(array('condition' => 'cquestionario_id=' . $id))) {
                Cencuestados::model()->deleteAll(array('condition' => 'cquestionario_id=' . $id));
            }
            //FIN DE ELIMINAR

            if ($questionario->cbasedatos_id == 1) {

                $ciudad = $p->purify($_POST['Usuarios']['ciudad']);
                $concesionario = $p->purify($_POST['Usuarios']['dealers_id']);
                $desde = $p->purify($_POST['Usuarios']['desde']);
                $hasta = $p->purify($_POST['Usuarios']['hasta']);
                $where = '';
                if (!empty($ciudad) && $ciudad > 0) {
                    if (empty($where)) {
                        $where .= "where gt.test_drive = 1 and gt.order=1 and gi.ciudad_conc =" . $ciudad;
                    } else {
                        $where .= " and gt.test_drive = 1 and gt.order=1 and gi.ciudad_conc =" . $ciudad;
                    }
                }

                if (!empty($concesionario) && $concesionario > 0) {
                    if (empty($where)) {
                        $where .= "where gt.test_drive = 1 and gt.order=1 and gi.dealer_id =" . $concesionario;
                    } else {
                        $where .= " and gt.test_drive = 1 and gt.order=1 and gi.dealer_id =" . $concesionario;
                    }
                }

                if (!empty($desde) && !empty($hasta)) {
                    if (empty($where)) {
                        $where .= "where gt.test_drive = 1 and gt.order=1 and gt.fecha >='" . $desde . "' and gt.fecha <='" . $hasta . "' ";
                    } else {
                        $where .= " and gt.test_drive = 1 and gt.order=1  and gt.fecha >='" . $desde . "' and gt.fecha <='" . $hasta . "'";
                    }
                }
                if(empty($where)){
					$where = ' WHERE gt.test_drive = 1 and gt.order=1';
				}
                $sql = 'SELECT DISTiNCT gi.fecha,gi.nombres, gi.apellidos, gi.email, gi.celular, gi.telefono_oficina, gi.ciudad_conc 
	                    FROM gestion_informacion gi 
	                        inner join gestion_test_drive gt 
	                            on gi.id = gt.id_informacion
	                    ' . $where . ' ORDER BY gi.fecha DESC';
                echo $sql;
                //die();
                $persona = Yii::app()->db->createCommand($sql)->queryAll();
                $errorSave = 0;
                echo $sql;
                //die();
                if (!empty($persona)) {
                    foreach ($persona as $key) {
                        echo $key['nombres'];

                        if (!empty($key['nombres']) && !empty($key['apellidos']) && !empty($key['telefono_oficina']) && !empty($key['celular']) && !empty($key['ciudad_conc']) && !empty($key['email'])) {
                            $model = new Cencuestados;
                            $model->nombre = $p->purify($key['nombres'] . ' ' . $key['apellidos']);
                            $model->telefono = $p->purify($key['telefono_oficina']);
                            $model->celular = $p->purify($key['celular']);
                            $model->email = $p->purify($key['email']);
                            $city = Dealercities::model()->findByPk($key['ciudad_conc']);
                            $model->ciudad = $p->purify($city->name);
                            $model->estado = 'ACTIVO';
                            $model->cquestionario_id = $id;
                            if (!$model->save()) {
                                $errorSave++;
                            } else
                                $errorSave = 0;
                        }
                    }
                }
                if ($errorSave == 0) {
                    Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
                    $this->redirect(array('cencuestadoscquestionario/seleccionar/' . $id));
                    die();
                } else {
                    Yii::app()->user->setFlash('error', "Se han prducido " . $errorSave . " error/es al registrar tus datos.");
                    $this->redirect(array('cencuestadoscquestionario/seleccionar/' . $id));
                }
            }
            if ($questionario->cbasedatos_id == 2) {

                $ciudad = $p->purify($_POST['Usuarios']['ciudad']);
                $concesionario = $p->purify($_POST['Usuarios']['dealers_id']);
                $desde = $p->purify($_POST['Usuarios']['desde']);
                $hasta = $p->purify($_POST['Usuarios']['hasta']);
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
                $errorSave = 0;
                echo $sql;
                //die();
                if (!empty($persona)) {
                    foreach ($persona as $key) {
                        echo $key['nombres'];

                        if (!empty($key['nombres']) && !empty($key['apellidos']) && !empty($key['telefono_oficina']) && !empty($key['celular']) && !empty($key['ciudad_conc']) && !empty($key['email'])) {
                            $model = new Cencuestados;
                            $model->nombre = $p->purify($key['nombres'] . ' ' . $key['apellidos']);
                            $model->telefono = $p->purify($key['telefono_oficina']);
                            $model->celular = $p->purify($key['celular']);
                            $model->email = $p->purify($key['email']);
                            $city = Dealercities::model()->findByPk($key['ciudad_conc']);
                            $model->ciudad = $p->purify($city->name);
                            $model->estado = 'ACTIVO';
                            $model->cquestionario_id = $id;
                            if (!$model->save()) {
                                $errorSave++;
                            } else
                                $errorSave = 0;
                        }
                    }
                }
                if ($errorSave == 0) {
                    Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
                    $this->redirect(array('cencuestadoscquestionario/seleccionar/' . $id));
                    die();
                } else {
                    Yii::app()->user->setFlash('error', "Se han prducido " . $errorSave . " error/es al registrar tus datos.");
                    $this->redirect(array('cencuestadoscquestionario/seleccionar/' . $id));
                }
            }
            if ($questionario->cbasedatos_id == 3) {

                $ciudad = $p->purify($_POST['Usuarios']['ciudad']);
                $concesionario = $p->purify($_POST['Usuarios']['dealers_id']);
                $desde = $p->purify($_POST['Usuarios']['desde']);
                $hasta = $p->purify($_POST['Usuarios']['hasta']);
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

                $sql = 'SELECT DISTINCT a.nombre,a.telefono,a.celular,a.email,a.cityid
                    FROM encuestas e 
                    inner join atencion_detalle a 
                    on e.id_atencion_detalle = e.id_atencion_detalle 
                    ' . $where . ' LIMIT 10';
                //echo $sql;
                $persona = Yii::app()->db2->createCommand($sql)->queryAll();
                $errorSave = 0;
                echo $sql;
                //die();
                if (!empty($persona)) {
                    foreach ($persona as $key) {
                        echo $key['nombre'];

                        if (!empty($key['nombre']) && !empty($key['telefono']) && !empty($key['celular']) && !empty($key['cityid']) && !empty($key['email'])) {
                            $model = new Cencuestados;
                            $model->nombre = $p->purify($key['nombre']);
                            $model->telefono = $p->purify($key['telefono']);
                            $model->celular = $p->purify($key['celular']);
                            $model->email = $p->purify($key['email']);
                            $city = Dealercities::model()->findByPk($key['cityid']);
                            $model->ciudad = $p->purify($city->name);
                            $model->estado = 'ACTIVO';
                            $model->cquestionario_id = $id;
                            if (!$model->save()) {
                                $errorSave++;
                            } else
                                $errorSave = 0;
                        }
                    }
                }
                if ($errorSave == 0) {
                    Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
                    $this->redirect(array('cencuestadoscquestionario/seleccionar/' . $id));
                    die();
                } else {
                    Yii::app()->user->setFlash('error', "Se han prducido " . $errorSave . " error/es al registrar tus datos.");
                    $this->redirect(array('cencuestadoscquestionario/seleccionar/' . $id));
                }
            }
            if ($questionario->cbasedatos_id == 4) {

                $ciudad = $p->purify($_POST['Usuarios']['ciudad']);
                $concesionario = $p->purify($_POST['Usuarios']['dealers_id']);
                $desde = $p->purify($_POST['Usuarios']['desde']);
                $hasta = $p->purify($_POST['Usuarios']['hasta']);
                $where = '';
                if (!empty($ciudad) && $ciudad > 0) {
                    if (empty($where)) {
                        $where .= "where gt.test_drive = 0 and gt.order=1 and gi.ciudad_conc =" . $ciudad;
                    } else {
                        $where .= " and gt.test_drive = 0 and gt.order=1 and gi.ciudad_conc =" . $ciudad;
                    }
                }

                if (!empty($concesionario) && $concesionario > 0) {
                    if (empty($where)) {
                        $where .= "where gt.test_drive = 0 and gt.order=1 and gi.dealer_id =" . $concesionario;
                    } else {
                        $where .= " and gt.test_drive = 0 and gt.order=1 and gi.dealer_id =" . $concesionario;
                    }
                }

                if (!empty($desde) && !empty($hasta)) {
                    if (empty($where)) {
                        $where .= "where gt.test_drive = 0 and gt.order=1 and gt.fecha >='" . $desde . "' and gt.fecha <='" . $hasta . "' ";
                    } else {
                        $where .= " and gt.test_drive = 0 and gt.order=1  and gt.fecha >='" . $desde . "' and gt.fecha <='" . $hasta . "'";
                    }
                }
                	if(empty($where)){
					$where = ' WHERE gt.test_drive = 0 and gt.order=1';
				}
                $sql = 'SELECT DISTiNCT gi.fecha,gi.nombres, gi.apellidos, gi.email, gi.celular, gi.telefono_oficina, gi.ciudad_conc 
                        FROM gestion_informacion gi 
                            inner join gestion_test_drive gt 
                                on gi.id = gt.id_informacion
                        ' . $where . ' ORDER BY gi.fecha DESC';
                echo $sql;
                //die();
                $persona = Yii::app()->db->createCommand($sql)->queryAll();
                $errorSave = 0;
                echo $sql;
                //die();
                if (!empty($persona)) {
                    foreach ($persona as $key) {
                        echo $key['nombres'];

                        if (!empty($key['nombres']) && !empty($key['apellidos']) && !empty($key['telefono_oficina']) && !empty($key['celular']) && !empty($key['ciudad_conc']) && !empty($key['email'])) {
                            $model = new Cencuestados;
                            $model->nombre = $p->purify($key['nombres'] . ' ' . $key['apellidos']);
                            $model->telefono = $p->purify($key['telefono_oficina']);
                            $model->celular = $p->purify($key['celular']);
                            $model->email = $p->purify($key['email']);
                            $city = Dealercities::model()->findByPk($key['ciudad_conc']);
                            $model->ciudad = $p->purify($city->name);
                            $model->estado = 'ACTIVO';
                            $model->cquestionario_id = $id;
                            if (!$model->save()) {
                                $errorSave++;
                            } else
                                $errorSave = 0;
                        }
                    }
                }
                if ($errorSave == 0) {
                    Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
                    $this->redirect(array('cencuestadoscquestionario/seleccionar/' . $id));
                    die();
                } else {
                    Yii::app()->user->setFlash('error', "Se han prducido " . $errorSave . " error/es al registrar tus datos.");
                    $this->redirect(array('cencuestadoscquestionario/seleccionar/' . $id));
                }
            }
        }

        $this->render('seleccionar', array(
            'id' => $id,
            'questionario' => $questionario,
            'model' => $models,
            'totalUBD' => $totalUsuariosExistentes,
        ));
    }

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
        $model = new Cencuestadoscquestionario;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Cencuestadoscquestionario'])) {
            $model->attributes = $_POST['Cencuestadoscquestionario'];
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

        if (isset($_POST['Cencuestadoscquestionario'])) {
            $model->attributes = $_POST['Cencuestadoscquestionario'];
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
        $dataProvider = new CActiveDataProvider('Cencuestadoscquestionario');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $values = array();
        $results = Cquestionario::model()->findAll(array("condition" => 'fechafin >="' . date("Y-m-d") . '"'));
        if (!empty($results)) {

            foreach ($results as $r) {
                $values[] = $r->id;
            }
        }

        $criteria = new CDbCriteria;
        $criteria->distinct = true;
        $criteria->condition = "usuarios_id=" . (int) Yii::app()->user->id;
        $criteria->addInCondition('cquestionario_id', $values);
        //$criteria->select = ' usuarios_id, cquestionario_id';
        $criteria->group = 'cquestionario_id';
        $criteria->order = 'id desc';




        // Count total records
        $pages = new CPagination(Cencuestadoscquestionario::model()->count($criteria));

        // Set Page Limit
        $pages->pageSize = 10;

        // Apply page criteria to CDbCriteria
        $pages->applyLimit($criteria);

        // Grab the records
        $posts = Cencuestadoscquestionario::model()->findAll($criteria);

        // Render the view
        $this->render('admin', array(
            'model' => $posts,
            'pages' => $pages,
            'busqueda' => '',
                )
        );
    }

    public function VerificarEncuestasAutomaticas($id) {
        $encuesta = Cquestionario::model()->findByPk($id);
        if ($encuesta->automatico == 'SI') {
            //OBTENER LAS PERSONAS A ENCUESTAR
            $encuestados = Cencuestados::model()->findAll(array('condition' => 'cquestionario_id=' . $encuesta->id));
            if ($encuesta->cbasedatos_id == 1) {
                //obtener todas las personas existentes en la base de datos TEST DRIVE
                $sql = 'SELECT DISTiNCT gi.fecha, gi.nombres, gi.apellidos, gi.email, gi.celular, gi.telefono_oficina, gi.ciudad_conc 
	                    FROM gestion_informacion gi 
	                        inner join gestion_test_drive gt 
	                            on gi.id = gt.id_informacion
                                 WHERE gt.test_drive = 1 and gt.order=1
                        ORDER BY gi.fecha DESC
	                    ';
                $persona = Yii::app()->db->createCommand($sql)->queryAll();
                if (!empty($persona)) {
                    foreach ($persona as $key) {
                        $verificasihay = Cencuestados::model()->find(array("condition" => 'email ="' . $key['email'] . '"'));

                        if (empty($verificasihay)) {

                            $sqlU = 'select count(*) asignados, usuarios_id from cencuestadoscquestionario where cquestionario_id=' . $id . ' group by usuarios_id order by asignados ASC limit 1';
                            $user = Yii::app()->db->createCommand($sqlU)->queryAll();
                            $uu = '';
                            foreach ($user as $u) {
                                $uu = $u['usuarios_id'];
                            }
                            if (!empty($key['nombres']) && !empty($key['apellidos']) && !empty($key['telefono_oficina']) && !empty($key['celular']) && !empty($key['ciudad_conc']) && !empty($key['email'])) {
                                $p = new CHtmlPurifier();
                                $model = new Cencuestados;
                                $model->nombre = $p->purify($key['nombres'] . ' ' . $key['apellidos']);
                                $model->telefono = $p->purify($key['telefono_oficina']);
                                $model->celular = $p->purify($key['celular']);
                                $model->email = $p->purify($key['email']);
                                $city = Dealercities::model()->findByPk($key['ciudad_conc']);
                                $model->ciudad = $p->purify($city->name);
                                $model->estado = 'ACTIVO';
                                $model->cquestionario_id = $id;
                                if ($model->save()) {
                                    $models = new Cencuestadoscquestionario();
                                    $models->cencuestados_id = $model->id;
                                    $models->cquestionario_id = $id;
                                    $models->usuarios_id = $uu;
                                    $models->estado = 'PENDIENTE';
                                    $models->save();
                                }
                            }
                        }
                    }
                }
            }
            if ($encuesta->cbasedatos_id == 4) {
                //obtener todas las personas existentes en la base de datos TEST DRIVE
                $sql = 'SELECT DISTiNCT gi.fecha, gi.nombres, gi.apellidos, gi.email, gi.celular, gi.telefono_oficina, gi.ciudad_conc 
                        FROM gestion_informacion gi 
                            inner join gestion_test_drive gt 
                                on gi.id = gt.id_informacion
                                 WHERE gt.test_drive = 0 and gt.order=1
                        ORDER BY gi.fecha DESC
                        ';
                $persona = Yii::app()->db->createCommand($sql)->queryAll();
                if (!empty($persona)) {
                    foreach ($persona as $key) {
                        $verificasihay = Cencuestados::model()->find(array("condition" => 'email ="' . $key['email'] . '"'));

                        if (empty($verificasihay)) {

                            $sqlU = 'select count(*) asignados, usuarios_id from cencuestadoscquestionario where cquestionario_id=' . $id . ' group by usuarios_id order by asignados ASC limit 1';
                            $user = Yii::app()->db->createCommand($sqlU)->queryAll();
                            $uu = '';
                            foreach ($user as $u) {
                                $uu = $u['usuarios_id'];
                            }
                            if (!empty($key['nombres']) && !empty($key['apellidos']) && !empty($key['telefono_oficina']) && !empty($key['celular']) && !empty($key['ciudad_conc']) && !empty($key['email'])) {
                                $p = new CHtmlPurifier();
                                $model = new Cencuestados;
                                $model->nombre = $p->purify($key['nombres'] . ' ' . $key['apellidos']);
                                $model->telefono = $p->purify($key['telefono_oficina']);
                                $model->celular = $p->purify($key['celular']);
                                $model->email = $p->purify($key['email']);
                                $city = Dealercities::model()->findByPk($key['ciudad_conc']);
                                $model->ciudad = $p->purify($city->name);
                                $model->estado = 'ACTIVO';
                                $model->cquestionario_id = $id;
                                if ($model->save()) {
                                    $models = new Cencuestadoscquestionario();
                                    $models->cencuestados_id = $model->id;
                                    $models->cquestionario_id = $id;
                                    $models->usuarios_id = $uu;
                                    $models->estado = 'PENDIENTE';
                                    $models->save();
                                }
                            }
                        }
                    }
                }
            }
            if ($encuesta->cbasedatos_id == 2) {
                //obtener todas las personas existentes en la base de datos TEST DRIVE
                $sql = 'SELECT DISTiNCT gi.nombres, gi.apellidos, gi.email, gi.celular, gi.telefono_oficina, gi.ciudad_conc
                    FROM gestion_informacion gi 
                        inner join gestion_nueva_cotizacion gt 
                            on gi.id_cotizacion = gt.id
	                    ';
                $persona = Yii::app()->db->createCommand($sql)->queryAll();
                if (!empty($persona)) {
                    foreach ($persona as $key) {
                        $verificasihay = Cencuestados::model()->find(array("condition" => 'email ="' . $key['email'] . '"'));

                        if (empty($verificasihay)) {

                            $sqlU = 'select count(*) asignados, usuarios_id from cencuestadoscquestionario where cquestionario_id=' . $id . ' group by usuarios_id order by asignados ASC limit 1';
                            $user = Yii::app()->db->createCommand($sqlU)->queryAll();
                            $uu = '';
                            foreach ($user as $u) {
                                $uu = $u['usuarios_id'];
                            }
                            if (!empty($key['nombres']) && !empty($key['apellidos']) && !empty($key['telefono_oficina']) && !empty($key['celular']) && !empty($key['ciudad_conc']) && !empty($key['email'])) {
                                $p = new CHtmlPurifier();
                                $model = new Cencuestados;
                                $model->nombre = $p->purify($key['nombres'] . ' ' . $key['apellidos']);
                                $model->telefono = $p->purify($key['telefono_oficina']);
                                $model->celular = $p->purify($key['celular']);
                                $model->email = $p->purify($key['email']);
                                $city = Dealercities::model()->findByPk($key['ciudad_conc']);
                                $model->ciudad = $p->purify($city->name);
                                $model->estado = 'ACTIVO';
                                $model->cquestionario_id = $id;
                                if ($model->save()) {
                                    $models = new Cencuestadoscquestionario();
                                    $models->cencuestados_id = $model->id;
                                    $models->cquestionario_id = $id;
                                    $models->usuarios_id = $uu;
                                    $models->estado = 'PENDIENTE';
                                    $models->save();
                                }
                            }
                        }
                    }
                }
            }
        }
        return true;
    }

    public function actionEncuestas($id) {
        $rol = Yii::app()->user->getState('roles');
        $this->VerificarEncuestasAutomaticas($id);
        $criteria = new CDbCriteria;
        $criteria->condition = "cquestionario_id = $id and usuarios_id=" . (int) Yii::app()->user->id;
        //$criteria->select = ' usuarios_id, cquestionario_id';
        $criteria->order = 'estado, id DESC';




        // Count total records
        $pages = new CPagination(Cencuestadoscquestionario::model()->count($criteria));

        // Set Page Limit
        $pages->pageSize = 10;

        // Apply page criteria to CDbCriteria
        $pages->applyLimit($criteria);

        // Grab the records
        $posts = Cencuestadoscquestionario::model()->findAll($criteria);

        // Render the view
        //VERIFICA SI AUN TIENE ENCUESTADOS PENDIENTES CASO CONTRARIO NOTIFICA A LOS ADMINISTRADORES
        $conts = 0;
        if (!empty($posts)) {
            foreach ($posts as $key) {
                if ($key->estado == 'PENDIENTE' || $key->estado == 'EN PROCESO') {
                    $conts++;
                }
            }
        }
        if ($conts == 0 && !empty($posts)) {
            require_once 'email/mail_func.php';
            $url = "https://" . $_SERVER['HTTP_HOST'];
            $asunto = 'Finalizaci&oacute;n de encuestas';
            $user = Usuarios::model()->findByPk(Yii::app()->user->id);
            $q = Cquestionario::model()->findByPk($id);
            $general = '<body style="margin: 10px;">
						<div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;margin:auto">
							<div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;margin:auto" align="center"><img src="images/header_mail.jpg"/><br></div>';
            $general .= '<div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;margin:auto">El usuario: <b>' . utf8_decode(utf8_encode($user->nombres)) . ' ' . utf8_decode(utf8_encode($user->apellido)) . '</b>  ha ha completado todas las encuestas pendientes referentes a la campa&ntilde;a <b>"' . $q->ccampana->nombre . '"</b> y su questionario <b>"' . $q->nombre . '"</b>.</div>';
            $general.=' <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;margin:auto;text-align:left"><img src="images/footer_mail.jpg"/></div>
							</div>
						</div>
					</body>';
            $codigohtml = $general;
            $tipo = 'informativo';
            $headers = 'From: info@kia.com.ec' . "\r\n";
            $headers .= 'Content-type: text/html' . "\r\n";
            $email = "";
            $cc = array();
            $estado = 0;
            $cargoAdm = Cargo::model()->find(array('condition' => "codigo=:match  AND estado='ACTIVO'", 'params' => array(':match' => Constantes::ADM_CALL_CODIGO_USUARIO)));
            $administradores = Usuarios::model()->findAll(array('condition' => "cargo_id=:match  AND estado='ACTIVO'", 'params' => array(':match' => (int) $cargoAdm->id)));

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

            sendEmailFunction('info@kia.com.ec', html_entity_decode("Kia -  Sistema de Prospecci&oacute;n"), $email, html_entity_decode($asunto), $codigohtml, $tipo, $cc, '', '');
        }
        //FIN DE VERIFICA
        $this->render('encuestas', array(
            'model' => $posts,
            'pages' => $pages,
            'busqueda' => '',
            'id' => $id,
                )
        );
    }

    public function actionEncuestar($id) {
        $p = new CHtmlPurifier();
        date_default_timezone_set("America/Bogota");
        $model = Cencuestadoscquestionario::model()->findByPk($id);

        if (isset($_POST['Cencuestadoscquestionario'])) {
            $uploadedFile = CUploadedFile::getInstance($model, 'audio');
            if (!empty($uploadedFile) && $uploadedFile != '') {
                $rnd = rand(0, 9999);
                $date = date("Ymdhis");
                $extension = explode('.', $uploadedFile);

                if ($uploadedFile->size > 2048576) {
                    Yii::app()->user->setFlash('error', '<div class="alert alert-danger"><strong>Ups!</strong>  El audio  que ha ingresado tiene un peso mayor a 2MB, lo cual no esta permitido.</div>');
                    $this->redirect(array('cencuestadoscquestionario/encuestar/id/' . $id));
                    die();
                }
                $paso = 0;
                $ext = "";
                $paso = 0;
                switch ($extension[1]) {
                    case "wav":
                        $paso = 1;
                        break;
                    case "wave":
                        $paso = 1;
                        break;
                }
                if ($paso == 0) {

                    Yii::app()->user->setFlash('error', '<div class="alert alert-danger"><strong>Ups!</strong>  Solamente puedes subir archivos en formato WAV.</div>');
                    $this->redirect(array('cencuestadoscquestionario/encuestar/id/' . $id));
                    die();
                }
                $fileName = md5($rnd . $date) . '.' . $extension[1];
                $model->audio = $fileName;

                /*                 * *****SUBIR IMAGEN************ */
                $uploadedFile->saveAs(Yii::app()->basePath . '/../upload/audio/' . $fileName);
            } else {
                Yii::app()->user->setFlash('error', '<div class="alert alert-danger"><strong>Ups!</strong>  Debes subir el audio de la llamada y recuerda que debe tener un formato WAV.</div>');
                $this->redirect(array('cencuestadoscquestionario/encuestar/id/' . $id));
                die();
            }
            $model->observaciones = 'La encuesta ha sido cancelada por el cliente.';
            $model->estado = 'CANCELADA';
            $model->tiempoinicio = date('Y-m-d h:i:s');
            $model->tiempofinal = date('Y-m-d h:i:s');
            if (empty($_POST['Cencuestadoscquestionario']['observaciones'])) {
                Yii::app()->user->setFlash('error', '<div class="alert alert-danger"><strong>Error!</strong>  Debes ingresar un comentario del motivo de cancelaci&oacute;n de la encuesta.</div>');
                $this->redirect(array('cencuestadoscquestionario/encuestar/id/' . $id));
                die();
            }
            if ($model->save()) {
                $obs = new Cobservacionesencuesta();
                $obs->descripcion = $p->purify($_POST['Cencuestadoscquestionario']['observaciones']);
                $obs->fecha = date('Y-m-d h:i:s');
                $obs->cencuestadoscquestionario_id = $id;
                if ($obs->save()) {
                    Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
                    $this->redirect(array('cencuestadoscquestionario/encuestar/id/' . $id));
                }
            }
        } else if (!empty($_POST['txtobservacion'])) {

            $obs = new Cobservacionesencuesta();
            $obs->descripcion = $p->purify($_POST['txtobservacion']);
            $obs->fecha = date('Y-m-d h:i:s');
            $obs->cencuestadoscquestionario_id = $id;
            //$model->sugerido = $_POST['GestionAgendamiento']['agendamiento'];
            $model->estado = 'EN PROCESO';
            if ($model->update()) {
                if ($obs->save()) {
                    Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
                    $this->redirect(array('cencuestadoscquestionario/encuestar/id/' . $id));
                }
            }
        }

        $this->render('encuestar', array('model' => $model));
    }

    public function actionEncuestarusuario($id) {
        $p = new CHtmlPurifier();
        date_default_timezone_set("America/Bogota");
        $q = Cencuestadoscquestionario::model()->findByPk($id);
        $q->tiempoinicio = date('Y-m-d h:i:s');
        if (!empty($_POST['tt']))
            $q->tiempo = $_POST['tt'];

        $q->update();
        $preguntas = Cpregunta::model()->findAll(array('condition' => 'copcionpregunta_id is null and cquestionario_id=' . $q->cquestionario_id, 'order' => 'orden ASC'));
        if (!empty($_POST)) {
            $preguntas = Cpregunta::model()->findAll(array('condition' => 'cquestionario_id=' . $q->cquestionario_id, 'order' => 'orden ASC'));
            /*
              echo '<pre>';
              print_r($_POST);

              echo '</pre>';
              die(); */
            $eq = Cencuestadospreguntas::model()->findAll(array('condition' => 'cencuestadoscquestionario_id=' . (int) $id));
            if (!empty($eq)) {
                Cencuestadospreguntas::model()->deleteAll(array('condition' => 'cencuestadoscquestionario_id=' . (int) $id));
            }

            $error = 0;
            if (!empty($preguntas)) {
                foreach ($preguntas as $item) {
                    if ($item->ctipopregunta_id == 3) {

                        if (isset($_POST['respuesta'][$item->id])) {
                            $respuestasPreguntas = $_POST['respuesta'][$item->id];
                            echo '<Pre>';
                            print_r($respuestasPreguntas);
                            echo '</pre>';


                            $model = new Cencuestadospreguntas();
                            $model->cencuestadoscquestionario_id = (int) $id;
                            $model->fecha = date('Y-m-d h:i:s');
                            $exp = explode('|', $p->purify($respuestasPreguntas['respuesta']));
                            $model->copcionpregunta_id = $exp[1];
                            $model->pregunta_id = $item->id;
                            if (!empty($respuestasPreguntas['justifica'])) {
                                $model->respuesta = $p->purify($exp[0] . ' por: ' . $respuestasPreguntas['justifica']);
                            } else {
                                $model->respuesta = $p->purify($exp[0]);
                            }


                            //echo 'error-> '.$item->id.' - '.$respuestasPreguntas[$i].'<br>';
                            if (!$model->save()) {
                                $error++;
                                echo 'error-> ' . $item->id . ' - ' . $respuestasPreguntas[$i] . '<br>';
                            }
                        }/* else{
                          Yii::app()->user->setFlash('error', '<div class="alert alert-danger"><strong>Error!</strong> Debe ingresar todas las respuestas de las preguntas del questionario.</div>');
                          $this->redirect(array('cencuestadoscquestionario/encuestarusuario/id/'.$id));
                          die();
                          } */
                        //die();
                    } else if ($item->ctipopregunta_id == 2) {

                        if (!empty($_POST['respuesta'][$item->id])) {
                            $respuestasPreguntas = $_POST['respuesta'][$item->id];
                            for ($i = 0; $i <= count($respuestasPreguntas); $i++) {
                                if (!empty($respuestasPreguntas[$i]) && $respuestasPreguntas[$i] != '') {
                                    $model = new Cencuestadospreguntas();
                                    $model->cencuestadoscquestionario_id = (int) $id;
                                    $model->fecha = date('Y-m-d h:i:s');
                                    $opcionesR = Copcionpregunta::model()->findAll(array('condition' => 'cpregunta_id=' . $item->id));
                                    $exp = explode('|', $p->purify($respuestasPreguntas[$i]));
                                    $model->copcionpregunta_id = $exp[1];
                                    $model->pregunta_id = $item->id;
                                    $model->respuesta = $p->purify($exp[0]);
                                    //echo 'error-> '.$item->id.' - '.$respuestasPreguntas[$i].'<br>';
                                    if (!$model->save()) {
                                        $error++;
                                        echo 'error-> ' . $item->id . ' - ' . $respuestasPreguntas[$i] . '<br>';
                                    }
                                }
                            }
                        }/* else{
                          Yii::app()->user->setFlash('error', '<div class="alert alert-danger"><strong>Error!</strong> Debe ingresar todas las respuestas de las preguntas del questionario.</div>');
                          $this->redirect(array('cencuestadoscquestionario/encuestarusuario/id/'.$id));
                          die();
                          } */
                    } else if ($item->ctipopregunta_id == 4) {
                        $opcionesR = Copcionpregunta::model()->findAll(array('condition' => 'cpregunta_id=' . $item->id));
                        $respuestasPreguntas = $_POST['respuesta'][$item->id];

                        print_r($respuestasPreguntas);
                        if (!empty($opcionesR)) {
                            foreach ($opcionesR as $op) {
                                $model = new Cencuestadospreguntas();
                                $model->cencuestadoscquestionario_id = (int) $id;
                                $model->fecha = date('Y-m-d h:i:s');
                                $model->pregunta_id = $item->id;
                                $model->copcionpregunta_id = $_POST['respuesta'][$op->id]['idop'];
                                $res = explode('|', $p->purify($respuestasPreguntas[$op->id]));
                                $model->cmatrizpregunta_id = $res[1];
                                $model->respuesta = $res[0];
                                if (!$model->save()) {
                                    $error++;
                                    echo 'error-> ' . $item->id . '<br>';
                                }
                            }
                        }
                    } else {
                        if (!empty($_POST['respuesta'][$item->id])) {
                            $model = new Cencuestadospreguntas();
                            $model->cencuestadoscquestionario_id = (int) $id;
                            $model->fecha = date('Y-m-d h:i:s');
                            $model->pregunta_id = $item->id;
                            $model->respuesta = $p->purify($_POST['respuesta'][$item->id]);
                            if (!$model->save()) {
                                $error++;
                                echo 'error-> ' . $item->id . '<br>';
                            }
                        }
                    }
                }
            }
            if ($error == 0)
                $this->redirect(array('finalizarencuesta', 'id' => $id));
            else {
                Yii::app()->user->setFlash('error', '<div class="alert alert-danger"><strong>Error!</strong> Debe ingresar todas las respuestas de las preguntas del questionario.</div>');
                $this->redirect(array('cencuestadoscquestionario/encuestarusuario/id/' . $id));
                die();
            }
        }
        $this->render('encuestarusuario', array('questionario' => $q, 'preguntas' => $preguntas, 'id' => $id, 'idq' => $q->cquestionario_id, 'inicio' => date('Y-m-d h:i:s')));
    }

    public function actionFinalizarencuesta($id) {

        date_default_timezone_set("America/Bogota");
        $model = Cencuestadoscquestionario::model()->findByPk($id);
        if (isset($_POST['Cencuestadoscquestionario'])) {
            $model->attributes = $_POST['Cencuestadoscquestionario'];
            $model->estado = 'COMPLETADO';
            $model->tiempofinal = date('Y-m-d h:i:s');
            $uploadedFile = CUploadedFile::getInstance($model, 'audio');

            if (!empty($uploadedFile) && $uploadedFile != '') {

                $rnd = rand(0, 9999);
                $date = date("Ymdhis");
                $extension = explode('.', $uploadedFile);

                if ($uploadedFile->size > 2048576) {

                    Yii::app()->user->setFlash('error', '<div class="alert alert-danger"><strong>Ups!</strong>  El audio ingresado tiene un peso mayor a 2MB, lo cual no esta permitido.</div>');
                    $this->redirect(array('cencuestadoscquestionario/finalizarencuesta/id/' . $id));
                    die();
                }
                $paso = 0;
                switch ($extension[1]) {
                    case "wav":
                        $paso = 1;
                        break;
                    case "wave":
                        $paso = 1;
                        break;
                }
                if ($paso == 0) {

                    Yii::app()->user->setFlash('error', '<div class="alert alert-danger"><strong>Ups!</strong>  Solamente puedes subir archivos en formato WAV.</div>');
                    $this->redirect(array('cencuestadoscquestionario/finalizarencuesta/id/' . $id));
                    die();
                }
                $fileName = md5($rnd . $date) . '.' . $extension[1];
                $model->audio = $fileName;

                /*                 * *****SUBIR IMAGEN************ */
                $uploadedFile->saveAs(Yii::app()->basePath . '/../upload/audio/' . $fileName);
            } else {
                Yii::app()->user->setFlash('error', '<div class="alert alert-danger"><strong>Ups!</strong>  Debes subir el audio de la llamada y recuerda que debe tener un formato WAV.</div>');
                $this->redirect(array('cencuestadoscquestionario/finalizarencuesta/id/' . $id));
                die();
            }
            if ($model->save()) {
                Yii::app()->user->setFlash('success', '<img src="' . Yii::app()->request->baseUrl . '/images/agradecimiento.png"/>');
                $this->redirect(array('cencuestadoscquestionario/encuestarusuario/id/' . $id));
            }
        }
        $this->render('finalizarencuesta', array('model' => $model));
    }

    public function loadModel($id) {
        $model = Cencuestadoscquestionario::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Cencuestadoscquestionario $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'cencuestadoscquestionario-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionCotizaciones($id) {
        $p = new CHtmlPurifier();
        date_default_timezone_set("America/Bogota");
        $informacion = new GestionInformacion();

        if (!empty($_POST)) {
            //grbar los datos en gestion informacion y gestion diaria
            //print_r($_POST);
            //die();

            /* OBTNER DATOS DE PROVINCIA */
            $city = Dealercities::model()->findByPk($_POST["data"]['ciudad']);

            $informacion->nombres = $p->purify($_POST["data"]['nombre']);
            $informacion->apellidos = $p->purify($_POST["data"]['apellido']);
            $informacion->cedula = $p->purify($_POST["data"]['cedula']);
            $informacion->identificacion = $p->purify($_POST["data"]['cedula']);
            $informacion->direccion = $p->purify($_POST["data"]['direccion']);
            $informacion->fecha = date("Y-m-d H:i:s");

            $informacion->email = $p->purify($_POST["data"]['email']);
            $informacion->celular = $p->purify($_POST["data"]['celular']);
            $informacion->telefono_oficina = $p->purify($_POST["data"]['telefono']);

            $informacion->ciudad = 0;
            $informacion->ciudad_conc = $p->purify($_POST["data"]['ciudad']);
            $informacion->concesionario = $p->purify($_POST["data"]['concesionario']);
            $informacion->dealer_id = $p->purify($_POST["data"]['concesionario']);
            $informacion->modelo = $p->purify($_POST["data"]['modelo']);
            $informacion->version = $p->purify($_POST["data"]['version']);
            $informacion->bdc = 1;
            $informacion->provincia_domicilio = $city->id_provincia;
            $informacion->ciudad_domicilio = $city->id;
            $informacion->responsable = $this->getRandomKey(73, $_POST["data"]['concesionario']);
            $informacion->provincia_conc = $city->id_provincia;
            $informacion->ciudad_conc = $city->id;


            if ($informacion->validate()) {
                if ($informacion->save()) {
                    $fecha_actual = date("Y-m-d H:i:s");
                    $fecha_posterior = strtotime('+1 day', strtotime($fecha_actual));
                    $fecha_posterior = date('Y-m-d H:i:s', $fecha_posterior);

                    $gestion = new GestionDiaria();
                    $gestion->id_informacion = $informacion->id; // la id del modelo anterior grabado
                    $gestion->id_vehiculo = 0;
                    $gestion->observaciones = 'Primera visita';
                    $gestion->medio_contacto = 'visita';
                    $gestion->fuente_contacto = 'web';
                    $gestion->codigo_vehiculo = 0;
                    $gestion->prospeccion = 1;
                    $gestion->status = 1;
                    $gestion->paso = '1-2';
                    $gestion->fecha = date("Y-m-d H:i:s");
                    $gestion->proximo_seguimiento = $fecha_posterior; // agendamiento automatico en 24 horas
                    if ($gestion->save()) {
                        $consulta = new GestionConsulta;
                        $consulta->id_informacion = $informacion->id;
                        $consulta->fecha = date("Y-m-d H:i:s");
                        $consulta->status = 'ACTIVO';
                    }

                    if ($consulta->save()) {

                        $cotizacion = Cotizacionesnodeseadas::model()->find(array('condition' => 'id=' . (int) $_POST['data']['id_atencion']));
                        $cotizacion->fecharealizado = date('Y-m-d H:i:s');
                        $cotizacion->realizado = 'SI';
                        $cotizacion->nombre = $p->purify($_POST["data"]['nombre']);
                        $cotizacion->apellido = $p->purify($_POST["data"]['apellido']);
                        $cotizacion->cedula = $p->purify($_POST["data"]['cedula']);
                        $cotizacion->direccion = $p->purify($_POST["data"]['direccion']);
                        $cotizacion->provincia = '0';
                        $cotizacion->ciudad = '0';
                        $cotizacion->email = $p->purify($_POST["data"]['email']);
                        $cotizacion->celular = $p->purify($_POST["data"]['celular']);
                        $cotizacion->trabajo = $p->purify($_POST["data"]['telefono']);
                        $cotizacion->telefono = $p->purify($_POST["data"]['telefono']);
                        $cotizacion->ciudadconcesionario_id = $p->purify($_POST["data"]['ciudad']);
                        $cotizacion->concesionario_id = $p->purify($_POST["data"]['concesionario']);

                        $cotizacion->modelo_id = $p->purify($_POST["data"]['modelo']);
                        $cotizacion->version_id = $p->purify($_POST["data"]['version']);

                        if ($cotizacion->save()) {
                            $this->redirect(array('cotizacionok'));
                        }
                    }
                }
            }
        }

        $datos = Cotizacionesnodeseadas::model()->find(array('condition' => 'id=' . $id));
        $this->render('cotizaciones', array('datos' => $datos, 'informacion' => $informacion));
    }

    public function actionCotizacionok() {
        $this->render('cotizacionok');
    }

    public function actionNocompradores($id) {
        date_default_timezone_set("America/Bogota");
        if (!empty($_POST)) {
            //die('enter post');

            /* echo '<pre>';
              print_r($_POST);
              echo '</pre>';
              die(); */

            //Registramos no compradores en su tabla
            $noc = Nocompradores::model()->findByPk($id);
            $noc->preguntauno = $_POST['datos']['motivo'];
            $noc->experienciaasesor = $_POST['datos']['respuesta_experiencia'];
            $noc->caracteristicas = $_POST['datos']['respuesta_caracteristicas'];
            $noc->otros = $_POST['datos']['otro'];

            if (isset($_POST['datos']['compro']))
                $noc->compro = $_POST['datos']['compro'];

            if (isset($_POST['datos']['nuevo']))
                $noc->nuevo = $_POST['datos']['nuevo'];

            if (isset($_POST['datos']['txtmarca']) || isset($_POST['datos']['txtmarcausado']))
                $noc->marca = (!empty($_POST['datos']['txtmarca'])) ? $_POST['datos']['txtmarca'] : $_POST['datos']['txtmarcausado'];

            if (isset($_POST['datos']['txtmodelo']) || isset($_POST['datos']['txtmodelousado']))
                $noc->modelo = (!empty($_POST['datos']['txtmodelo'])) ? $_POST['datos']['txtmodelo'] : $_POST['datos']['txtmodelousado'];

            if (isset($_POST['datos']['porquenuevo']))
                $noc->porque = $_POST['datos']['porquenuevo'];

            if (isset($_POST['datos']['lugarcompra']))
                $noc->donde = $_POST['datos']['lugarcompra'];

            $noc->fecha = date('Y-m-d H:i:s');
            //$noc->usuario_id = (int) Yii::app()->user->id;
            if ($noc->update()) {

                $gd = GestionDiaria::model()->find(array('condition' => 'id=' . (int) $noc->gestiondiaria_id));
                //validaciones de registro
                if (!empty($gd)) {
                    $gi = GestionInformacion::model()->find(array('condition' => 'id=' . (int) $gd->id_informacion));

                    if ((trim($_POST['datos']['motivo']) == 'Falta de Dinero' || trim($_POST['datos']['motivo']) == 'Precio' || trim($_POST['datos']['motivo']) == 'Credito Rechazado' || trim($_POST['datos']['motivo']) == 'Modelo no disponible') && trim($_POST['datos']['compro']) == 'NO') {
                        //actualizamos el gestion de informacion
                        if (!empty($gi)) {

                            $conce = $gi->dealer_id;
                            $resp = $gi->responsable;
                            $gi->tipo_form_web = 'usadopago';
                            $gi->responsable_origen = $resp;
                            $gi->responsable = $this->getRandomKey(77, $conce);

                            if ($gi->update()) {
                                /* $gestion = new GestionDiaria();
                                  $gestion->id_informacion = $gi->id; // id del modelo anterior
                                  $gestion->id_vehiculo = 0;
                                  $gestion->observaciones = 'Prospección';
                                  $gestion->medio_contacto = 'telefono';
                                  $gestion->fuente_contacto = 'prospeccion';
                                  $gestion->codigo_vehiculo = 0;
                                  $gestion->prospeccion = 1;
                                  $gestion->status = 0;
                                  $gestion->proximo_seguimiento = '';
                                  $gestion->paso = '1-2';
                                  $gestion->fecha = date("Y-m-d H:i:s");
                                  if($gestion->save()){
                                  $this->redirect(array('nocotizacionok'));
                                  } */
                            }
                        }
                    }
                    if (trim($_POST['datos']['motivo']) == 'Aun no toma la decision') {
                        //die('enter no toma des');
                        if (!empty($gi)) {
                            /* $gd->desiste = 2;
                              $gd->update(); */
                            $gi->tipo_form_web = '';
                            $gi->bdc = 1;
                            $gi->responsable_origen = $gi->responsable;
                            $gi->responsable = $this->getRandomKey(73, $gi->dealer_id);
                            $gi->update();
                        }
                    }
                }
                $this->redirect(array('nocotizacionok'));
            }
        } else {


            $model = Nocompradores::model()->findByPk($id);
        }
        $this->render('nocompradores', array('model' => $model));
    }

    public function actionNocotizacionok() {
        $this->render('nocotizacionok');
    }

    /////////////ALGORITMO PARA LAS COTIZACIONES NO DESEADAS DESDE ATENCION_DETALLE DE LA BASE ADMINKIA
    public function actionAtenciondetalle() {
        date_default_timezone_set("America/Bogota");

        $name = '';
        $this->traercotizaciones();
        $criteria = new CDbCriteria;
        $criteria->distinct = true;
        $criteria->condition = "realizado='0' and usuario_id=" . (int) Yii::app()->user->id;

        if (!empty($_POST['nombre'])) {
            $p = new CHtmlPurifier();
            $name = $p->purify($_POST['nombre']);
            $criteria = new CDbCriteria;
            $criteria->distinct = true;
            $criteria->condition = "(nombre LIKE '%$name%' or apellido LIKE '%$name%') and realizado='0' and usuario_id=" . (int) Yii::app()->user->id;
        }
        //$criteria->order = 'id DESC';



        // Count total records
        $pages = new CPagination(Cotizacionesnodeseadas::model()->count($criteria));

        // Set Page Limit
        $pages->pageSize = 10;

        // Apply page criteria to CDbCriteria
        $pages->applyLimit($criteria);
        // $pages ='';
        // Grab the records
        $posts = Cotizacionesnodeseadas::model()->findAll($criteria);



        // Render the view
        $this->render('atenciondetalle', array(
            'model' => $posts,
            'pages' => $pages,
            'busqueda' => $name,
                )
        );
    }

    public function actionAtenciondetalleencuestado() {
        date_default_timezone_set("America/Bogota");
        $name = '';
        $this->traercotizaciones();
        $criteria = new CDbCriteria;
        $criteria->distinct = true;
        $criteria->condition = "realizado!='0' and usuario_id=" . (int) Yii::app()->user->id;
        $criteria->order = 'fecharealizado DESC';

        if (!empty($_POST['nombre'])) {

            $p = new CHtmlPurifier();
            $name = $p->purify($_POST['nombre']);
            $criteria = new CDbCriteria;
            $criteria->condition = "(nombre LIKE '%$name%' or apellido LIKE '%$name%') and fecharealizado is not null and usuario_id=" . (int) Yii::app()->user->id;
            $criteria->order = 'fecharealizado DESC';
        }

        //$criteria->order = 'id DESC';
        // Count total records
        $pages = new CPagination(Cotizacionesnodeseadas::model()->count($criteria));

        // Set Page Limit
        $pages->pageSize = 10;

        // Apply page criteria to CDbCriteria
        $pages->applyLimit($criteria);
        // $pages ='';
        // Grab the records
        $posts = Cotizacionesnodeseadas::model()->findAll($criteria);

        // Render the view
        $this->render('atenciondetalleencuestado', array(
            'model' => $posts,
            'pages' => $pages,
            'busqueda' => $name,
                )
        );
    }

    //No compradores Administrador
    public function actionNocompradoresadmin() {
        date_default_timezone_set("America/Bogota");

        /*  $datosC = GestionDiaria::model()->findAll(array('condition'=>'desiste = 1 and encuestado=0'));




          $cargo_id = Cargo::model()->find(array('condition'=>'codigo = "'.Constantes::CDG.'"'));
          $usuarios = Usuarios::model()->findAll(array('condition'=>'cargo_id ='.$cargo_id->id));

          if(!empty($datosC)){
          $maximo =number_format(count($datosC)/count($usuarios),0);
          $actual = 0;
          $contactual = 0;
          $posicion = 0;
          $usuario_list = array();
          foreach($usuarios as $u){
          $usuario_list[$actual++] = $u->id;
          }


          foreach($datosC as $d){

          if($contactual == $maximo){

          $contactual = 0;
          $posicion++;

          }

          if($posicion <= count($usuarios) && !empty($usuario_list[$posicion])){
          //echo $usuario_list[$posicion].'<br>';
          $cotizacion = new Nocompradores();
          $cotizacion->gestiondiaria_id = (int) $d->id;
          $cotizacion->usuario_id =  $usuario_list[$posicion];
          $cotizacion->nombre = $d->gestioninformacion->nombres;
          $cotizacion->apellido = $d->gestioninformacion->apellidos;
          $cotizacion->cedula = $d->gestioninformacion->cedula;
          $cotizacion->email = $d->gestioninformacion->email;
          $cotizacion->ceular = $d->gestioninformacion->celular;

          if($cotizacion->save()){
          $d->encuestado = 1;
          $d->realizado = date('Y-m-d h:i:s');
          $d->update();
          }
          }
          $contactual++;
          }



          } */
        $name = '';
        $this->traernocompradores();
        $criteria = new CDbCriteria;
        $criteria->distinct = true;
        $criteria->condition = "fecha is null and usuario_id=" . (int) Yii::app()->user->id;
        if (!empty($_POST['nombre'])) {
            $p = new CHtmlPurifier();
            $name = $p->purify($_POST['nombre']);
            $criteria = new CDbCriteria;
            $criteria->distinct = true;
            $criteria->condition = "(nombre LIKE '%$name%' or apellido LIKE '%$name%') and fecha is null and usuario_id=" . (int) Yii::app()->user->id;
        }
        //$criteria->condition ='';
        $criteria->order = 'id desc';




        // Count total records
        $pages = new CPagination(Nocompradores::model()->count($criteria));

        // Set Page Limit
        $pages->pageSize = 10;

        // Apply page criteria to CDbCriteria
        $pages->applyLimit($criteria);

        // Grab the records
        $posts = Nocompradores::model()->findAll($criteria);

        // Render the view

        $this->render('nocompradoresadmin', array(
            'model' => $posts,
            'pages' => $pages,
            'busqueda' => $name,
                )
        );
    }

    public function actionNocompradoresadminencuestados() {
        date_default_timezone_set("America/Bogota");
        $name = '';
        $this->traernocompradores();
        $criteria = new CDbCriteria;
        $criteria->distinct = true;
        $criteria->condition = "fecha is not null and usuario_id=" . (int) Yii::app()->user->id;
        if (!empty($_POST['nombre'])) {
            $p = new CHtmlPurifier();
            $name = $p->purify($_POST['nombre']);
            $criteria = new CDbCriteria;
            $criteria->distinct = true;
            $criteria->condition = "(nombre LIKE '%$name%' or apellido LIKE '%$name%') and fecha is not null and usuario_id=" . (int) Yii::app()->user->id;
            $criteria->order = 'fecha desc';
        }
        //$criteria->condition ='';
        // Count total records
        $pages = new CPagination(Nocompradores::model()->count($criteria));

        // Set Page Limit
        $pages->pageSize = 10;

        // Apply page criteria to CDbCriteria
        $pages->applyLimit($criteria);

        // Grab the records
        $posts = Nocompradores::model()->findAll($criteria);

        // Render the view

        $this->render('nocompradoresadminencuestados', array(
            'model' => $posts,
            'pages' => $pages,
            'busqueda' => $name,
                )
        );
    }

}
