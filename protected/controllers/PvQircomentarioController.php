<?php

class PvQircomentarioController extends Controller {

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
                'actions' => array('admin','create', 'update', 'Eliminar', 'Search'),
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
        if(Yii::app()->user->hasState('modal')){
            Yii::app()->clientScript->registerScript('close','$(".cont_der").remove();$("#yw2").remove();');            
        }
        
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($id) {
        
        if(Yii::app()->user->hasState('modal')){
            Yii::app()->clientScript->registerScript('remove','$(".cont_der").remove();$("#yw1").remove();');            
        }
        
        $model = new Qircomentario;
        $modelA = new QirComentarioFile;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Qircomentario'])) {
            $transaction = Yii::app()->db->beginTransaction();
			require_once 'email/mail_func.php';
            $model->attributes = $_POST['Qircomentario'];
			$mailPara = "";
			if(empty($model->para)){
				$model->para = 'Concesión';
			}else{
				$mailPara = $model->para;
			}
            $modelA->attributes = $_POST['QirComentarioFile'];

            $model->qirId = $id;
            $model->fecha = ($model->fecha) ? date("Y-m-d", strtotime($model->fecha)) : "";

            $uploadedFile = CUploadedFile::getInstance($modelA, 'nombre_file');

           // try {

                if (!empty($uploadedFile)) {
                    if ($model->save()) {                        
                        $modelA->qirComentarioId = $model->id;
                        $rnd = rand(0, 9999);
                        $fecha = date("Ymd");
                        $extension = explode('.', $uploadedFile);
                        $fileName = date("Y-m-d-h-i-s") . '.' . $extension[1];
                        $modelA->nombre_file = $fileName;
                        if ($uploadedFile->size > 2048576) {
                            Yii::app()->user->setFlash('error', "El archivo que usted ha enviado tiene un peso mayor a 2MB.");
                        } else {
                            if ($uploadedFile->saveAs(Yii::app()->basePath . '/../upload/qircomentariofile/' . $fileName))
                                $error = 0;                            
                        }
                        $modelA->save(FALSE);

                         $asunto = "Nuevo Comentario al QIR, ingrese al portal de Servicios KIA para darle seguimiento";
                         $HTML =
                            '<body style="margin: 10px;">
                            <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;margin:auto">
                                <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;" align="center"><img src="images/header_mail.jpg"/><br>';
                        $HTML .=  "<table style='width:100%'>";
                        $HTML.= "<tr><td style='width:150px'>Titular</td><td>" .utf8_encode( $model->qir->titular) . "</td></tr>";
                        $HTML.= "<tr><td style='width:150px'># Reporte</td><td>" . utf8_encode($model->qir->num_reporte). "</td></tr>";
                        $HTML.= "<tr><td style='width:150px'>Modelo</td><td>" .utf8_encode( $model->qir->modeloPostVenta->descripcion) . "</td></tr>";
                        $HTML.= "<tr><td style='width:150px'>Fecha Registro</td><td>" . $model->qir->fecha_registro . "</td></tr>";
                        $HTML.= "<tr><td style='width:150px'>Descripci&oacute;n</td><td>" . utf8_encode($model->comentario ). "</td></tr>";
                        $HTML.= "</table>";
                        $HTML.=' <img src="images/footer_mail.jpg"/>';
                        //$ccToFrom = array('ssalvador@aekia.com.ec','golivo@aekia.com.ec','rlopez@aekia.com.ec');
                        //$this->sendMail($HTML, $subject, 'fjacome@aekia.com.ec', $ccToFrom, 'comunidad@kiamail.com.ec', 'KIA Ecuador');
                        
                        //$cc = array('jorge.rodriguez@ariadna.com.ec','jaeproa@gmail.com');
                      //  $this->sendMail($HTML, $subject, $model->para, $ccToFrom, 'comunidad@kiamail.com.ec', 'KIA Ecuador');
                        
						$codigohtml = $HTML;
                        
                        //$ccToFrom = array('ssalvador@aekia.com.ec','golivo@aekia.com.ec','rlopez@aekia.com.ec');
                        //$this->sendMail($HTML, $subject, 'fjacome@aekia.com.ec', $ccToFrom, 'comunidad@kiamail.com.ec', 'KIA Ecuador');
						$codigohtml = $HTML;
						$tipo = 'informativo';
						$headers = 'From: info@kia.com.ec' . "\r\n";
						$headers .= 'Content-type: text/html' . "\r\n";
						$email = $mailPara;
						$cc = array('jorge.rodriguez@ariadna.com.ec', 'ssalvador@aekia.com.ec', 'rlopez@aekia.com.ec', 'fjacome@aekia.com.ec');
						//$cc = array('jael@walkerbrand.com');
						if(sendEmailFunction('info@kia.com.ec',  (utf8_decode(utf8_encode("Kia -  Sistema de Prospección"))), $email, ($asunto), utf8_decode(utf8_encode($codigohtml)), $tipo,$cc,'','')){
							$transaction->commit();
						}
						
                        if(!Yii::app()->user->hasState("modal"))
                            $this->redirect(array('admin', 'id' => $model->qirId));
                        else
                            Yii::app()->clientScript->registerScript('close','parent.cerrarFancy();');
                    }
                } else {
					 if ($model->save()) {                        
                        $asunto = "Nuevo Comentario al QIR, ingrese al portal de Servicios KIA para darle seguimiento";
                        $HTML =
                            '<body style="margin: 10px;">
                            <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;margin:auto">
                                <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 12px;" align="center"><img src="images/header_mail.jpg"/><br>';
                        $HTML .= "<table style='width:100%'>";
                        $HTML.= "<tr><td style='width:150px'>Titular</td><td>" . $model->qir->titular . "</td></tr>";
                        $HTML.= "<tr><td style='width:150px'># Reporte</td><td>" . $model->qir->num_reporte . "</td></tr>";
                        $HTML.= "<tr><td style='width:150px'>Modelo</td><td>" . $model->qir->modeloPostVenta->descripcion . "</td></tr>";
                        $HTML.= "<tr><td style='width:150px'>Fecha Registro</td><td>" . $model->qir->fecha_registro . "</td></tr>";
                        $HTML.= "<tr><td style='width:150px'>Descripci&oacute;n</td><td>" . $model->comentario . "</td></tr>";
                        $HTML.= "</table>";
                        $HTML.=' <img src="images/footer_mail.jpg"/>';
						$codigohtml = $HTML;
						$tipo = 'informativo';
						$headers = 'From: info@kia.com.ec' . "\r\n";
						$headers .= 'Content-type: text/html' . "\r\n";
						$email = $mailPara;
					
						$cc = array('jorge.rodriguez@ariadna.com.ec','ssalvador@aekia.com.ec', 'rlopez@aekia.com.ec');
						
						if(sendEmailFunction('info@kia.com.ec', html_entity_decode("Kia -  Sistema de Prospecci&oacute;n"), $email, html_entity_decode($asunto), $codigohtml, $tipo,$cc,'','')){
							$transaction->commit();
						}
						
                        if(!Yii::app()->user->hasState("modal"))
                            $this->redirect(array('admin', 'id' => $model->qirId));
                        else
                            Yii::app()->clientScript->registerScript('close','parent.cerrarFancy();');
                    }
                    
                }
           /* } catch (Exception $exc) {
                var_dump($exc);
                $transaction->rollback();
            }*/
			/*try {

                if (!empty($uploadedFile)) {
                    if ($model->save()) {                        
                        $modelA->qirComentarioId = $model->id;
                        $rnd = rand(0, 9999);
                        $fecha = date("Ymd");
                        $extension = explode('.', $uploadedFile);
                        $fileName = date("Y-m-d-h-i-s") . '.' . $extension[1];
                        $modelA->nombre_file = $fileName;
                        if ($uploadedFile->size > 2048576) {
                            Yii::app()->user->setFlash('error', "El archivo que usted ha enviado tiene un peso mayor a 2MB.");
                        } else {
                            if ($uploadedFile->saveAs(Yii::app()->basePath . '/../upload/qircomentariofile/' . $fileName))
                                $error = 0;                            
                        }
                        $modelA->save(FALSE);

                        $subject = "Nuevo Comentario al QIR, ingrese al portal de Servicios KIA para darle seguimiento";
                        $HTML = "<table style='width:100%'>";
                        $HTML.= "<tr><td style='width:150px'>Titular</td><td>" . $model->qir->titular . "</td></tr>";
                        $HTML.= "<tr><td style='width:150px'># Reporte</td><td>" . $model->qir->num_reporte . "</td></tr>";
                        $HTML.= "<tr><td style='width:150px'>Modelo</td><td>" . $model->qir->modeloPostVenta->descripcion . "</td></tr>";
                        $HTML.= "<tr><td style='width:150px'>Fecha Registro</td><td>" . $model->qir->fecha_registro . "</td></tr>";
                        $HTML.= "<tr><td style='width:150px'>Descripci&oacute;n</td><td>" . $model->comentario . "</td></tr>";
                        $HTML.= "</table>";
                        
                        //$ccToFrom = array('ssalvador@aekia.com.ec','golivo@aekia.com.ec','rlopez@aekia.com.ec');
                        //$this->sendMail($HTML, $subject, 'fjacome@aekia.com.ec', $ccToFrom, 'comunidad@kiamail.com.ec', 'KIA Ecuador');
                        
                        $ccToFrom = array('jorge.rodriguez@ariadna.com.ec', 'ssalvador@aekia.com.ec', 'rlopez@aekia.com.ec');
                        $this->sendMail($HTML, $subject, $model->para, $ccToFrom, 'comunidad@kiamail.com.ec', 'KIA Ecuador');
                        
                        $transaction->commit();
						
                        if(!Yii::app()->user->hasState("modal"))
                            $this->redirect(array('admin', 'id' => $model->qirId));
                        else
                            Yii::app()->clientScript->registerScript('close','parent.cerrarFancy();');
                    }
                } else {
                    $model->validate();
                    $model->addError("id", "No a seleccionado ningun adjunto");
                }
            } catch (Exception $exc) {
                var_dump($exc);
                $transaction->rollback();
            }*/
        }

        $model->qirId = $id;
        $model->num_reporte = $model->qir->num_reporte;
        $model->modelo = $model->qir->modeloPostVenta->descripcion;

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
        
        if(Yii::app()->user->hasState('modal')){
            Yii::app()->user->setState('modal',NULL);
            Yii::app()->clientScript->registerScript('close','$(".cont_der").remove()');
        }
        
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Qircomentario'])) {
            $model->attributes = $_POST['Qircomentario'];
            $model->fecha = ($model->fecha) ? date("Y-m-d", strtotime($model->fecha)) : "";

            if ($model->save())
                $this->redirect(array('admin', 'id' => $model->qirId));
        }

        $model->fecha = ($model->fecha) ? date("m/d/Y", strtotime($model->fecha)) : "";

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
        $dataProvider = new CActiveDataProvider('Qircomentario');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin($id) {
        $modelQir = Qir::model()->findByPk($id);
        $modelV = new Qircomentario;
        $concesionario = Yii::app()->user->getState('dealer_id');
        $rol = Yii::app()->user->getState('roles');


        $criteria = new CDbCriteria;


        if ($rol === 'admin' || $rol === 'super' || $rol === 'adminvpv'):
            $criteria->order = 'id asc';
        else:
            $criteria->order = 'id asc';
        endif;

        $criteria->addCondition('qirId = :id');
        $criteria->params = array(':id' => $id);

        if (Yii::app()->user->hasState('errorDelete')) {
            Yii::app()->user->setFlash('message', Yii::app()->user->getState('errorDelete'));
            Yii::app()->user->setState('errorDelete', NULL);
        }


        // Count total records
        $pages = new CPagination(Qircomentario::model()->count($criteria));

        // Set Page Limit
        $pages->pageSize = 10;

        // Apply page criteria to CDbCriteria
        $pages->applyLimit($criteria);

        // Grab the records
        $posts = Qircomentario::model()->findAll($criteria);

        // Render the view
        $this->render('admin', array(
            'model' => $posts,
            'pages' => $pages,
            'busqueda' => '',
            'modelPost' => $modelV,
            'modelQir' => $modelQir,
            'id' => $id,
                )
        );
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Qircomentario the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Qircomentario::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Qircomentario $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'qircomentario-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionEliminar($id) {
        $model = $this->loadModel($id);
        $qirId = $model->qirId;
        if ($model) {
            if (!$model->delete()) {
                $model->getErrors();
                Yii::app()->user->setState('errorDelete', 'No se puede eliminar porque existe datos relacionados con el mismo');
            }
        }

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin', 'id' => $qirId));
    }

    public function actionSearch($id) {
        //$p = new CHtmlPurifier();

        if (isset($_GET['Qircomentario']) && !empty($_GET['Qircomentario'])) {
            $modelQir = Qir::model()->findByPk($id);
            $model = new Qircomentario;
            $modelV = new Qircomentario;
            $model->attributes = $_GET['Qircomentario'];
            $modelV->attributes = $_GET['Qircomentario'];


            $criteria = new CDbCriteria;
            $params = array();


            $criteria->addCondition('estado like "%' . $model->estado . '%"', 'OR');
            $criteria->addCondition('fecha like "%' . $model->estado . '%"', 'OR');
            $criteria->addCondition('para like "%' . $model->estado . '%"', 'OR');
            $criteria->addCondition('de like "%' . $model->estado . '%"', 'OR');
            $criteria->addCondition('asunto like "%' . $model->estado . '%"', 'OR');
            $criteria->addCondition('num_reporte like "%' . $model->estado . '%"', 'OR');
            $criteria->addCondition('modelo like "%' . $model->estado . '%"', 'OR');
            $criteria->addCondition('comentario like "%' . $model->estado . '%"', 'OR');

            $criteria->addCondition('qirId = :id', 'AND');
            $params[':id'] = $id;

            $criteria->params = $params;

            // Count total records
            $pages = new CPagination(Qircomentario::model()->count($criteria));

            // Set Page Limit
            $pages->pageSize = 10;

            // Apply page criteria to CDbCriteria
            $pages->applyLimit($criteria);

            // Grab the records
            $posts = Qircomentario::model()->findAll($criteria);


            /* Verificamos que tenga parametros de busqueda caso contrario redirect al admin */

            if (!$posts) {
                Yii::app()->user->setFlash('error', "No se encontraron datos con la busqueda realizada.");
                $this->redirect(array('pvQircomentario/admin/id/' . $id));
            }

            $this->render('admin', array(
                'model' => $posts,
                'pages' => $pages,
                'busqueda' => '',
                'modelPost' => $modelV,
                'id' => $id,
                'modelQir' => $modelQir,
            ));
        } else {
            Yii::app()->user->setFlash('error', "Ingrese un valor para realizar la busqueda.");
            $this->redirect(array('pvQircomentario/admin/id/' . $id));
        }
    }

}
