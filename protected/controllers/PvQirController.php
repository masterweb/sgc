<?php

class PvQirController extends Controller {

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
                'actions' => array('create','admin', 'update', 'Eliminar', 'Search', 'CargarVin', 'QirPDF', 'QirExcel', 'QirvExcel', 'QirViewPDF'),
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
        $model = $this->loadModel($id);

        $modelFiles = Qirfiles::model()->findAll(array('condition' => 'qirId=:id', 'params' => array(':id' => $model->id)));
        $modelAdicionales = Qiradicional::model()->findAll(array('condition' => 'qirId=:id', 'params' => array(':id' => $model->id)));
        $modelComentario = Qircomentario::model()->findAll(array('condition' => 'qirId=:id', 'params' => array(':id' => $model->id)));


        $this->render('view', array(
            'model' => $model,
            'modelFiles' => $modelFiles,
            'modelAdicionales' => $modelAdicionales,
            'modelComentario' => $modelComentario,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Qir;
        $modelA = new Qirfiles;
		require_once 'email/mail_func.php';
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Qir'])) {
            $transaction = Yii::app()->db->beginTransaction();

            $model->attributes = $_POST['Qir'];
            $modelA->attributes = $_POST['Qirfiles'];

            $model->fecha_fabricacion = ($model->fecha_fabricacion) ? date("Y-m-d", strtotime($model->fecha_fabricacion)) : "";
            $model->fecha_garantia = ($model->fecha_garantia) ? date("Y-m-d", strtotime($model->fecha_garantia)) : "";
            $model->fecha_registro = ($model->fecha_registro) ? date("Y-m-d", strtotime($model->fecha_registro)) : "";
            $model->fecha_reparacion = ($model->fecha_reparacion) ? date("Y-m-d", strtotime($model->fecha_reparacion)) : "";
            $uploadedFile = CUploadedFile::getInstance($modelA, 'nombre');

            try {
               // if (!empty($uploadedFile)) {
					$model->estado = 'Pendiente';
                    if ($model->save()) {
                        if (!empty($uploadedFile)) {
                            $rnd = rand(0, 9999);
                            $extension = explode('.', $uploadedFile);
                            $modelA->num_reporte = $model->num_reporte;
                            $fileName = $modelA->num_reporte . "_" . date("Y-m-d-h-i-s") . '.' . $extension[1];
                            $modelA->nombre = $fileName;
                            if ($uploadedFile->size > 2048576) {
                                Yii::app()->user->setFlash('error', "El archivo que usted ha enviado tiene un peso mayor a 2MB.");
                            } else {
                                if ($uploadedFile->saveAs(Yii::app()->basePath . '/../upload/qirfiles/' . $fileName)) {
                                    $modelA->qirId = $model->id;
                                    $modelA->save();
                                }
                            }
                        }

                        $asunto = "Nuevo QIR, ingrese al portal de Servicios KIA para darle seguimiento";
						 $HTML =
                            '<body style="margin: 10px;">
                            <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;margin:auto">
                                <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;" align="center"><img src="images/header_mail.jpg"/><br>';
                        $HTML .= "<table style='width:100%'>";
                        $HTML.= "<tr><td style='width:150px'>Titular</td><td>" . utf8_decode(($model->titular)) . "</td></tr>";
                        $HTML.= "<tr><td style='width:150px'># Reporte</td><td>" . $model->num_reporte . "</td></tr>";
                        $HTML.= "<tr><td style='width:150px'>Modelo</td><td>" . utf8_encode($model->modeloPostVenta->descripcion) . "</td></tr>";
                        $HTML.= "<tr><td style='width:150px'>Fecha Registro</td><td>" . $model->fecha_registro . "</td></tr>";
                        $HTML.= "<tr><td style='width:150px'>Descripci&oacute;n</td><td>" . (($model->descripcion)) . "</td></tr>";
                        $HTML.= "<tr><td style='width:150px'>Dealer</td><td>" . (($model->dealer->name)) . "</td></tr>";
                        $HTML.= "</table>";
                        $HTML.=' <img src="images/footer_mail.jpg"/>';
                        $codigohtml = $HTML;
                        
                        $cc = array('jorge.rodriguez@ariadna.com.ec', 'fjacome@kia.com.ec', 'creyes@kia.com.ec', 'fcastillo@kia.com.ec');
                        //$ccToFrom = array('ssalvador@aekia.com.ec','golivo@aekia.com.ec','rlopez@aekia.com.ec');
                        //$this->sendMail($HTML, $subject, 'marcelo.rodriguez@ariadna.com.ec', $ccToFrom, 'comunidad@kiamail.com.ec', 'KIA Ecuador');
                        //$this->sendMail($HTML, $subject, 'fjacome@aekia.com.ec', $ccToFrom, 'comunidad@kiamail.com.ec', 'KIA Ecuador');
                        $tipo = 'informativo';
                        $headers = 'From: info@kia.com.ec' . "\r\n"; 
                        $headers .= 'Content-type: text/html' . "\r\n";
                        $email = "";
                        if(sendEmailFunction('info@kia.com.ec', (utf8_decode(utf8_encode("Kia -  Sistema de Prospección"))), $email, ($asunto), utf8_decode(utf8_encode($codigohtml)), $tipo,$cc,'','')){
                                $transaction->commit();
                        }
                        $this->redirect(array('admin'));
                    }
                /*} else {
                    $model->validate();
                    $model->addError("id", "No a seleccionado ningun adjunto");
                }*/
            } catch (Exception $exc) {
                $transaction->rollback();
                echo $exc->getErrors();
            }
        }

        //Yii::app()->clientScript->registerScript('Mask', '$("#Qir_descripcion").attr("value","1. Descripción.\n\n2. Análisis Síntoma\n\n3. Investigación\n\n4. Acciones correctivas\n\n5. Comentarios / Recomendaciones\n\n")');

        $this->render('create', array(
            'model' => $model,
            'modelA' => $modelA,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        $modelA = new Qirfiles;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Qir'])) {
            $model->attributes = $_POST['Qir'];
            $model->fecha_fabricacion = ($model->fecha_fabricacion) ? date("Y-m-d", strtotime($model->fecha_fabricacion)) : "";
            $model->fecha_garantia = ($model->fecha_garantia) ? date("Y-m-d", strtotime($model->fecha_garantia)) : "";
            $model->fecha_registro = ($model->fecha_registro) ? date("Y-m-d", strtotime($model->fecha_registro)) : "";
            $model->fecha_reparacion = ($model->fecha_reparacion) ? date("Y-m-d", strtotime($model->fecha_reparacion)) : "";
            if ($model->save())
                $this->redirect(array('admin'));
        }


        $f1 = explode("/", date("m/d/Y", strtotime($model->fecha_fabricacion)));
        $model->fecha_fabricacion = ($model->fecha_fabricacion && checkdate($f1[0], $f1[1], $f1[2])) ? date("m/d/Y", strtotime($model->fecha_fabricacion)) : $model->fecha_fabricacion;
        $f2 = explode("/", date("m/d/Y", strtotime($model->fecha_garantia)));
        $model->fecha_garantia = ($model->fecha_garantia && checkdate($f2[0], $f2[1], $f2[2])) ? date("m/d/Y", strtotime($model->fecha_garantia)) : $model->fecha_garantia;
        $f3 = explode("/", date("m/d/Y", strtotime($model->fecha_registro)));
        $model->fecha_registro = ($model->fecha_registro && checkdate($f3[0], $f3[1], $f3[2])) ? date("m/d/Y", strtotime($model->fecha_registro)) : $model->fecha_registro;
        $f4 = explode("/", date("m/d/Y", strtotime($model->fecha_reparacion)));
        $model->fecha_reparacion = ($model->fecha_reparacion && checkdate($f4[0], $f4[1], $f4[2])) ? date("m/d/Y", strtotime($model->fecha_reparacion)) : $model->fecha_reparacion;

        $this->render('update', array(
            'model' => $model,
            'modelA' => $modelA,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        die("hola");
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    public function actionEliminar($id) {
        $model = $this->loadModel($id);
        if ($model) {
            if (!$model->delete()) {
                $model->getErrors();
                Yii::app()->user->setState('errorDelete', 'No se puede eliminar porque existe datos relacionados con el mismo');
            }
        }

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Qir');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $modelV = new Qir;
        $concesionario = Yii::app()->user->getState('dealer_id');
        $rol = Yii::app()->user->getState('roles');


        $criteria = new CDbCriteria;


        if ($rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
            //$criteria->condition = "tipo_form='caso'";
            $criteria->order = 'id desc';
        else:
            //$criteria->condition = "concesionario={$concesionario}";
            $criteria->order = 'id desc';
        endif;

        if (Yii::app()->user->hasState('errorDelete')) {
            Yii::app()->user->setFlash('message', Yii::app()->user->getState('errorDelete'));
            Yii::app()->user->setState('errorDelete', NULL);
        }


        // Count total records
        $pages = new CPagination(Qir::model()->count($criteria));

        // Set Page Limit
        $pages->pageSize = 10;

        // Apply page criteria to CDbCriteria
        $pages->applyLimit($criteria);

        // Grab the records
        $posts = Qir::model()->findAll($criteria);

        // Render the view
        $this->render('admin', array(
            'model' => $posts,
            'pages' => $pages,
            'busqueda' => '',
            'modelPost' => $modelV,
                )
        );
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Qir the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Qir::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Qir $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'qir-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionSearch() {
        //$p = new CHtmlPurifier();
        if (isset($_GET['Qir']))
            $post = array_filter($_GET['Qir']);
        else
            $port = null;

        if (isset($_GET['Qir']) && !empty($post)) {
            $model = new Qir;
            $modelV = new Qir;
            $model->attributes = $_GET['Qir'];
            $modelV->attributes = $_GET['Qir'];


            $criteria = new CDbCriteria;
            $params = array();

            if ($model->dealerId) {
                $criteria->addCondition('dealerId =:dealerId');
                $params[':dealerId'] = $model->dealerId;
                $criteria->params = array(':dealerId' => $model->dealerId);
            }

            if ($model->modeloPostVentaId) {
                $criteria->addCondition('modeloPostVentaId = :modeloPostVentaId');
                $params[':modeloPostVentaId'] = $model->modeloPostVentaId;
            }

            if ($model->estado) {
                $criteria->addCondition('estado = :estado ');
                $params[':estado'] = $model->estado;
            }

            if ($model->descripcion) {
                $model->descripcion = trim($model->descripcion);
                $criteria->addCondition('modeloPostVenta.descripcion LIKE "%' . $model->descripcion . '%"', 'OR');
                $criteria->addCondition('dealer.name LIKE "%' . $model->descripcion . '%"', 'OR');
                $criteria->addCondition('titular LIKE "%' . $model->descripcion . '%"', 'OR');
                $criteria->addCondition('num_reporte LIKE "%' . $model->descripcion . '%"', 'OR');
                $criteria->addCondition('fecha_registro LIKE "%' . $model->descripcion . '%"', 'OR');
            }



            if (isset($_GET['Qir']['fechaInicio']) && isset($_GET['Qir']['fechaFin'])) {
                if (!empty($_GET['Qir']['fechaInicio']) && !empty($_GET['Qir']['fechaFin'])) {
                    $fecha1 = date('Y-m-d', strtotime($_GET['Qir']['fechaInicio']));
                    $fecha2 = date('Y-m-d', strtotime($_GET['Qir']['fechaFin']));
                    Yii::app()->clientScript->registerScript('setF1', '$("#qirFechaInicio").val("' . date("m/d/Y", strtotime($fecha1)) . '")');
                    Yii::app()->clientScript->registerScript('setF2', '$("#qirFechaFin").val("' . date("m/d/Y", strtotime($fecha2)) . '")');
                    $criteria->addCondition('date(fecha_registro) BETWEEN :fecha1 and :fecha2');
                    $params[':fecha1'] = $fecha1;
                    $params[':fecha2'] = $fecha2;
                }
            }

            /* Cuando son consultas por fechas necesitamos las dos fechas, envio de error!!! */
            if (isset($_GET['Qir']['fechaInicio']) && isset($_GET['Qir']['fechaFin'])) {
                $sw = TRUE;
                if (empty($_GET['Qir']['fechaInicio']) && !empty($_GET['Qir']['fechaFin']))
                    $sw = FALSE;

                if (!empty($_GET['Qir']['fechaInicio']) && empty($_GET['Qir']['fechaFin']))
                    $sw = FALSE;


                if (!$sw)
                    Yii::app()->user->setFlash('fechas', 'Para hacer consultas por fechas, necesita la fecha inicio y fin del registro');
            }

            $criteria->params = $params;

            // Count total records
            $pages = new CPagination(Qir::model()->with('modeloPostVenta', 'dealer')->count($criteria));

            // Set Page Limit
            $pages->pageSize = 10;

            // Apply page criteria to CDbCriteria
            $pages->applyLimit($criteria);

            // Grab the records
            $posts = Qir::model()->with('modeloPostVenta', 'dealer')->findAll($criteria);


            /* Verificamos que tenga parametros de busqueda caso contrario redirect al admin */

            if (!$posts) {
                Yii::app()->user->setFlash('error', "No se encontraron datos con la busqueda realizada.");
                $this->redirect(array('pvQir/admin/'));
            }


            $this->render('admin', array(
                'model' => $posts,
                'pages' => $pages,
                'busqueda' => '',
                'modelPost' => $modelV,
            ));
        } else {
            Yii::app()->user->setFlash('error', "Ingrese un valor para realizar la busqueda.");
            $this->redirect(array('pvQir/admin/'));
        }
    }

    public function actionCargarVin($vin) {
        $vin = trim($vin);
        $vin = strtoupper($vin);
        $model = new VinMotor;
        $model = VinMotor::model()->find(array('condition' => 'vin = :vin', 'params' => array(':vin' => $vin)));
	if(!empty($model)){
        $data = array(
            'vin' => $model->vin,
            'motor' => $model->motor,
            'script' => '$("#Qir_num_motor").attr("value","' . $model->motor . '"); $("#Qir_vin").attr("value","' . $vin . '")',
        );

        echo json_encode($data);
	}
    }

    public function actionQirPDF() {
        $model = new Qir;

        if (isset($_POST['Qir']))
            $model->attributes = $_POST['Qir'];

        $criteria = new CDbCriteria;
        $params = array();

        if ($model->dealerId) {
            $criteria->addCondition('dealerId =:dealerId');
            $params[':dealerId'] = $model->dealerId;
            $criteria->params = array(':dealerId' => $model->dealerId);
        }

        if ($model->modeloPostVentaId) {
            $criteria->addCondition('modeloPostVentaId = :modeloPostVentaId');
            $params[':modeloPostVentaId'] = $model->modeloPostVentaId;
        }

        if ($model->estado) {
            $criteria->addCondition('estado = :estado ');
            $params[':estado'] = $model->estado;
        }

        if ($model->descripcion) {
            $model->descripcion = trim($model->descripcion);
            $criteria->addCondition('modeloPostVenta.descripcion LIKE "%' . $model->descripcion . '%"', 'OR');
            $criteria->addCondition('dealer.name LIKE "%' . $model->descripcion . '%"', 'OR');
            $criteria->addCondition('titular LIKE "%' . $model->descripcion . '%"', 'OR');
            $criteria->addCondition('num_reporte LIKE "%' . $model->descripcion . '%"', 'OR');
            $criteria->addCondition('fecha_registro LIKE "%' . $model->descripcion . '%"', 'OR');
        }



        if (isset($_POST['Qir']['fechaInicio']) && isset($_POST['Qir']['fechaFin'])) {
            if (!empty($_POST['Qir']['fechaInicio']) && !empty($_POST['Qir']['fechaFin'])) {
                $fecha1 = date('Y-m-d', strtotime($_POST['Qir']['fechaInicio']));
                $fecha2 = date('Y-m-d', strtotime($_POST['Qir']['fechaFin']));
                Yii::app()->clientScript->registerScript('setF1', '$("#qirFechaInicio").val("' . date("m/d/Y", strtotime($fecha1)) . '")');
                Yii::app()->clientScript->registerScript('setF2', '$("#qirFechaFin").val("' . date("m/d/Y", strtotime($fecha2)) . '")');
                $criteria->addCondition('date(fecha_registro) BETWEEN :fecha1 and :fecha2');
                $params[':fecha1'] = $fecha1;
                $params[':fecha2'] = $fecha2;
            }
        }

        $criteria->params = $params;

        $model = Qir::model()->with('modeloPostVenta', 'dealer')->findAll($criteria);
        $html2pdf = Yii::app()->ePdf->HTML2PDF();
        $html2pdf->WriteHTML($this->renderPartial('adminPDF', array('model' => $model), TRUE));
        $html2pdf->Output();
    }

    public function actionQirViewPDF($id) {
        $model = $this->loadModel($id);

        $modelFiles = Qirfiles::model()->findAll(array('condition' => 'qirId=:id', 'params' => array(':id' => $model->id)));
        $modelAdicionales = Qiradicional::model()->findAll(array('condition' => 'qirId=:id', 'params' => array(':id' => $model->id)));
        $modelComentario = Qircomentario::model()->findAll(array('condition' => 'qirId=:id', 'params' => array(':id' => $model->id)));

        $html2pdf = Yii::app()->ePdf->HTML2PDF();
        $html2pdf->WriteHTML($this->renderPartial('viewPDF', array(
                    'model' => $model,
                    'modelFiles' => $modelFiles,
                    'modelAdicionales' => $modelAdicionales,
                    'modelComentario' => $modelComentario
                        ), TRUE));
        $html2pdf->Output();
    }

    public function actionQirExcel() {
        Yii::import('ext.phpexcel.XPHPExcel');
        $objPHPExcel = XPHPExcel::createPHPExcel();
        $objPHPExcel->getProperties()->setCreator("Call Center Kia Ecuador")
                ->setLastModifiedBy("Call Center")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");




        $model = new Qir;

        if (isset($_POST['Qir']))
            $model->attributes = $_POST['Qir'];

        $criteria = new CDbCriteria;
        $params = array();

        if ($model->dealerId) {
            $criteria->addCondition('dealerId =:dealerId');
            $params[':dealerId'] = $model->dealerId;
            $criteria->params = array(':dealerId' => $model->dealerId);
        }

        if ($model->modeloPostVentaId) {
            $criteria->addCondition('modeloPostVentaId = :modeloPostVentaId');
            $params[':modeloPostVentaId'] = $model->modeloPostVentaId;
        }

        if ($model->estado) {
            $criteria->addCondition('estado = :estado ');
            $params[':estado'] = $model->estado;
        }

        if ($model->descripcion) {
            $model->descripcion = trim($model->descripcion);
            $criteria->addCondition('modeloPostVenta.descripcion LIKE "%' . $model->descripcion . '%"', 'OR');
            $criteria->addCondition('dealer.name LIKE "%' . $model->descripcion . '%"', 'OR');
            $criteria->addCondition('titular LIKE "%' . $model->descripcion . '%"', 'OR');
            $criteria->addCondition('num_reporte LIKE "%' . $model->descripcion . '%"', 'OR');
            $criteria->addCondition('fecha_registro LIKE "%' . $model->descripcion . '%"', 'OR');
        }



        if (isset($_POST['Qir']['fechaInicio']) && isset($_POST['Qir']['fechaFin'])) {
            if (!empty($_POST['Qir']['fechaInicio']) && !empty($_POST['Qir']['fechaFin'])) {
                $fecha1 = date('Y-m-d', strtotime($_POST['Qir']['fechaInicio']));
                $fecha2 = date('Y-m-d', strtotime($_POST['Qir']['fechaFin']));
                Yii::app()->clientScript->registerScript('setF1', '$("#qirFechaInicio").val("' . date("m/d/Y", strtotime($fecha1)) . '")');
                Yii::app()->clientScript->registerScript('setF2', '$("#qirFechaFin").val("' . date("m/d/Y", strtotime($fecha2)) . '")');
                $criteria->addCondition('date(fecha_registro) BETWEEN :fecha1 and :fecha2');
                $params[':fecha1'] = $fecha1;
                $params[':fecha2'] = $fecha2;
            }
        }

        $criteria->params = $params;
		  $criteria->order = 't.id desc';
		  
        $model = Qir::model()->with('modeloPostVenta', 'dealer')->findAll($criteria);

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
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
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
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => TRUE
            )
        );
        // Se combinan las celdas A1 hasta F1, para colocar ahí el titulo del reporte
        $objPHPExcel->setActiveSheetIndex(0)
                ->mergeCells('A1:AB1');


        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Reporte Qir') // Titulo del reporte
                ->setCellValue('A2', 'ID')
                ->setCellValue('B2', 'Concesionario')
                ->setCellValue('C2', '# Reporte')
                ->setCellValue('D2', 'Fecha Registro')
                ->setCellValue('E2', 'Modelo')
                ->setCellValue('F2', 'Titular')
                ->setCellValue('G2', 'Vehiculo Afectados')
                ->setCellValue('H2', 'Prioridad')
                ->setCellValue('I2', 'Parte Defectuosa')
                ->setCellValue('J2', 'Vin')
                ->setCellValue('K2', 'Motor')
                ->setCellValue('L2', 'Transmision')
                ->setCellValue('M2', 'Parte Causal')
                ->setCellValue('N2', 'Detalle Parte Causal')
                ->setCellValue('O2', 'Codigo Naturaleza')
                ->setCellValue('P2', 'Codigo Causal')
                ->setCellValue('Q2', 'Fecha Garantia')
                ->setCellValue('R2', 'Fecha Fabricacion')
                ->setCellValue('S2', 'Kilometraje')
                ->setCellValue('T2', 'Fecha Reparacion')
                ->setCellValue('U2', 'Ingresado')
                ->setCellValue('V2', 'Email')
                ->setCellValue('W2', 'Circunstancia')
                ->setCellValue('X2', 'Periodo Tiempo')
                ->setCellValue('Y2', 'Rango Velocidad')
                ->setCellValue('Z2', 'Localizacion')
                ->setCellValue('AA2', 'Fase Manejo')
                ->setCellValue('AB2', 'Estado');

        $i = 3;

        foreach ($model as $row) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $row->id)
                    ->setCellValue('B' . $i, $row->dealer['name'])
                    ->setCellValue('C' . $i, $row->num_reporte)
                    ->setCellValue('D' . $i, $row->fecha_registro)
                    ->setCellValue('E' . $i, $row->modeloPostVenta['descripcion'])
                    ->setCellValue('F' . $i, $row->titular)
                    ->setCellValue('G' . $i, $row->num_vehiculos_afectados)
                    ->setCellValue('H' . $i, $row->prioridad)
                    ->setCellValue('I' . $i, $row->parte_defectuosa)
                    ->setCellValue('J' . $i, $row->vin)
                    ->setCellValue('K' . $i, $row->num_motor)
                    ->setCellValue('L' . $i, $row->transmision)
                    ->setCellValue('M' . $i, $row->num_parte_casual)
                    ->setCellValue('N' . $i, $row->detalle_parte_casual)
                    ->setCellValue('O' . $i, $row->codigo_naturaleza)
                    ->setCellValue('P' . $i, $row->codigo_casual)
                    ->setCellValue('Q' . $i, $row->fecha_garantia)
                    ->setCellValue('R' . $i, $row->fecha_fabricacion)
                    ->setCellValue('S' . $i, $row->kilometraje)
                    ->setCellValue('T' . $i, $row->fecha_reparacion)
                    ->setCellValue('U' . $i, $row->ingresado)
                    ->setCellValue('V' . $i, $row->email)
                    ->setCellValue('W' . $i, $row->circunstancia)
                    ->setCellValue('X' . $i, $row->periodo_tiempo)
                    ->setCellValue('Y' . $i, $row->rango_velocidad)
                    ->setCellValue('Z' . $i, $row->localizacion)
                    ->setCellValue('AA' . $i, $row->fase_manejo)
                    ->setCellValue('AB' . $i, $row->estado);

//            $objPHPExcel->getActiveSheet()->setCellValueExplicit('F' . $i, $row['cedula'], PHPExcel_Cell_DataType::TYPE_STRING);
//            $objPHPExcel->getActiveSheet()->setCellValueExplicit('N' . $i, $row['telefono'], PHPExcel_Cell_DataType::TYPE_STRING);
//            $objPHPExcel->getActiveSheet()->setCellValueExplicit('O' . $i, $row['celular'], PHPExcel_Cell_DataType::TYPE_STRING);
//            //$objPHPExcel->getActiveSheet()->setCellValueExplicit('N' . $i, $row['comentario'], PHPExcel_Cell_DataType::TYPE_STRING);
//            //$objPHPExcel->getActiveSheet()->getStyle('M' . $i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $i++;
        }

        $style1 = array(
            'font' => array(
                'name' => 'Calibri',
                'bold' => true,
                'size' => 11,
            ),
        );

        $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->applyFromArray($style1);
        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(false);
        // rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Reporte Qir');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->getStyle('A1:AB1')->applyFromArray($estiloTituloReporte);
        $objPHPExcel->getActiveSheet()->getStyle('A2:AB2')->applyFromArray($estiloTituloColumnas);

        // Inmovilizar paneles
        $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0, 3);



        // Redirect output to a client's web browser (Excel5)
        $name_file = 'Reporte_' . date("Y-m-d") . '.xls';
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
    public function actionQirvExcel($id) {
        Yii::import('ext.phpexcel.XPHPExcel');
        $objPHPExcel = XPHPExcel::createPHPExcel();
        $objPHPExcel->getProperties()->setCreator("Call Center Kia Ecuador")
                ->setLastModifiedBy("Call Center")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");




        
        $row = Qir::model()->find(array('condition' => 'id=:id', 'params' => array(':id' =>$id)));
	
        
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
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
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
				'width'=>500,
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
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => TRUE
            )
        );
        // Se combinan las celdas A1 hasta F1, para colocar ahí el titulo del reporte
        $objPHPExcel->setActiveSheetIndex(0)
                ->mergeCells('A1:B1');


        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Reporte Qir') // Titulo del reporte
                ->setCellValue('A3', 'ID')
                ->setCellValue('A4', 'Concesionario')
                ->setCellValue('A5', '# Reporte')
                ->setCellValue('A6', 'Fecha Registro')
                ->setCellValue('A7', 'Modelo')
                ->setCellValue('A8', 'Titular')
                ->setCellValue('A9', 'Vehiculo Afectados')
                ->setCellValue('A10', 'Prioridad')
                ->setCellValue('A11', 'Parte Defectuosa')
                ->setCellValue('A12', 'Vin')
                ->setCellValue('A13', 'Motor')
                ->setCellValue('A14', 'Transmision')
                ->setCellValue('A15', 'Parte Causal')
                ->setCellValue('A16', 'Detalle Parte Causal')
                ->setCellValue('A17', 'Codigo Naturaleza')
                ->setCellValue('A18', 'Codigo Causal')
                ->setCellValue('A19', 'Fecha Garantia')
                ->setCellValue('A20', 'Fecha Fabricacion')
                ->setCellValue('A21', 'Kilometraje')
                ->setCellValue('A22', 'Fecha Reparacion')
	            ->setCellValue('A23', 'Titular')
		        ->setCellValue('A24', 'Descripcion')
					
                ->setCellValue('A25', 'Ingresado')
                ->setCellValue('A26', 'Email')
                ->setCellValue('A27', 'Circunstancia')
                ->setCellValue('A28', 'Periodo Tiempo')
                ->setCellValue('A29', 'Rango Velocidad')
                ->setCellValue('A30', 'Localizacion')
                ->setCellValue('A31', 'Fase Manejo')
	            ->setCellValue('A32', 'Condicion Camino')
		        ->setCellValue('A33', 'ETC')
			    ->setCellValue('A34', 'Vin Adicional')
				->setCellValue('A35', 'Num Motor Adicional')
				->setCellValue('A36', 'Kilometraje Adicional')		
                ->setCellValue('A37', 'Estado');

        $i = "";

      //  foreach ($model as $row) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('B3' . $i, $row->id)
                    ->setCellValue('B4' . $i, $row->dealer->name)
                    ->setCellValue('B5' . $i, $row->num_reporte)
                    ->setCellValue('B6' . $i, $row->fecha_registro)
                    ->setCellValue('B7' . $i, $row->modeloPostVenta->descripcion)
                    ->setCellValue('B8' . $i, $row->titular)
                    ->setCellValue('B9' . $i, $row->num_vehiculos_afectados)
                    ->setCellValue('B10' . $i, $row->prioridad)
                    ->setCellValue('B11' . $i, $row->parte_defectuosa)
                    ->setCellValue('B12' . $i, $row->vin)
                    ->setCellValue('B13' . $i, $row->num_motor)
                    ->setCellValue('B14' . $i, $row->transmision)
                    ->setCellValue('B15' . $i, $row->num_parte_casual)
                    ->setCellValue('B16' . $i, $row->detalle_parte_casual)
                    ->setCellValue('B17' . $i, $row->codigo_naturaleza)
                    ->setCellValue('B18' . $i, $row->codigo_casual)
                    ->setCellValue('B19' . $i, $row->fecha_garantia)
                    ->setCellValue('B20' . $i, $row->fecha_fabricacion)
                    ->setCellValue('B21' . $i, $row->kilometraje)
                    ->setCellValue('B22' . $i, $row->fecha_reparacion)
					->setCellValue('B23' . $i, $row->titular)		
					->setCellValue('B24' . $i, $row->descripcion)			
                    ->setCellValue('B25' . $i, $row->ingresado)
                    ->setCellValue('B26' . $i, $row->email)
                    ->setCellValue('B27' . $i, $row->circunstancia)
                    ->setCellValue('B28' . $i, $row->periodo_tiempo)
                    ->setCellValue('B29' . $i, $row->rango_velocidad)
                    ->setCellValue('B30' . $i, $row->localizacion)
                    ->setCellValue('B31' . $i, $row->fase_manejo)
					->setCellValue('B32' . $i, $row->condicion_camino)
					->setCellValue('B33' . $i, $row->etc)
					->setCellValue('B34' . $i, $row->vin_adicional)
					->setCellValue('B35' . $i, $row->num_motor_adi)
					->setCellValue('B36' . $i, $row->kilometraje_adic)
                    ->setCellValue('B37' . $i, $row->estado);

//            $objPHPExcel->getActiveSheet()->setCellValueExplicit('F' . $i, $row['cedula'], PHPExcel_Cell_DataType::TYPE_STRING);
//            $objPHPExcel->getActiveSheet()->setCellValueExplicit('N' . $i, $row['telefono'], PHPExcel_Cell_DataType::TYPE_STRING);
//            $objPHPExcel->getActiveSheet()->setCellValueExplicit('O' . $i, $row['celular'], PHPExcel_Cell_DataType::TYPE_STRING);
//            //$objPHPExcel->getActiveSheet()->setCellValueExplicit('N' . $i, $row['comentario'], PHPExcel_Cell_DataType::TYPE_STRING);
//            //$objPHPExcel->getActiveSheet()->getStyle('M' . $i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
           // $i++;
       // }

        $style1 = array(
            'font' => array(
                'name' => 'Calibri',
                'bold' => true,
                'size' => 11,
            ),
        );

        $objPHPExcel->getActiveSheet()->getStyle('A2:B2')->applyFromArray($style1);
        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
       	$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
       /*  $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(false);*/
        // rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Reporte Qir');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->getStyle('A1:B2')->applyFromArray($estiloTituloReporte);
        $objPHPExcel->getActiveSheet()->getStyle('A2:B2')->applyFromArray($estiloTituloColumnas);

        // Inmovilizar paneles
        $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0, 3);



        // Redirect output to a client's web browser (Excel5)
        $name_file = 'Reporte_' . date("Y-m-d") . '.xls';
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

    protected function beforeAction($action) {
        //die("entrooo: " . $action->id);
        //if ($action->id != 'view') {
            if (Yii::app()->user->hasState('modal')) {

                Yii::app()->user->setState('modal', NULL);
            }
        //}
        return true;
    }

}
