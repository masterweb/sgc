<?php

class GestionMatriculaController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

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
                'actions' => array('create', 'update','createAjax'),
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
        $model = new GestionMatricula;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionMatricula'])) {
            $model->attributes = $_POST['GestionMatricula'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }
    
    public function actionCreateAjax() {
        $model = new GestionMatricula;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionMatricula'])) {
            /*echo '<pre>';
            print_r($_POST);
            echo '</pre>';
            die();*/
            $result = FALSE;
            $model->attributes = $_POST['GestionMatricula'];
            $model->agendamiento = $_POST['GestionMatricula']['agendamiento1'];
            $con = Yii::app()->db;
            $sql = "UPDATE gestion_diaria SET seguimiento = 1 ,proximo_seguimiento = '{$_POST['GestionMatricula']['agendamiento1']}' WHERE id_informacion = {$_POST['GestionMatricula']['id_informacion']}";
            $request = $con->createCommand($sql)->query();
            $emailCliente = $this->getEmailCliente($_POST['GestionMatricula']['id_informacion']);
            if ($model->save()){
                //  ENVIO DE EMAILS SEGUN STATUS DE MATRICULA
                require_once 'email/mail_func.php';
                $asunto = 'Kia Motors Ecuador - Status de Entrega de Vehículo';
                if(isset($_POST['GestionMatricula']['factura_ingreso']) && !empty($_POST['GestionMatricula']['factura_ingreso'])){
                    $general = '<body style="margin: 10px;">
                                <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;">
                                    <div align="center">
                                    <img src="images/header_mail.jpg">
                                    <br>
                                        <table width="600">
                                            <tr><td><p><strong>Status de Entrega:</strong>  Envío de Factura Electrónica para Pago de matrícula, revisión vehicular y otros en entidades bancarias autorizadas.</p></td></tr>
                                            <tr><td><p><strong>Fecha de Entrega:</strong>  '.$_POST['GestionMatricula']['agendamiento1'].'</p></td></tr>
                                        </table>
                                    </div>
                                    <img src="images/footer.png">
                                </div>
                            </body>';
                }
                if(isset($_POST['GestionMatricula']['envio_factura']) && !empty($_POST['GestionMatricula']['envio_factura'])){
                    $general = '<body style="margin: 10px;">
                                <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;">
                                    <div align="center">
                                    <img src="images/header_mail.jpg">
                                    <br>
                                        <table width="600">
                                            <tr><td><p"><strong>Status de Entrega:</strong>  Envío de Factura Electrónica para Pago de matrícula, revisión vehicular, y otros en entidades bancarias autorizadas</p></td></tr>
                                            <tr><td><p><strong>Fecha de Entrega:</strong>  '.$_POST['GestionMatricula']['agendamiento1'].'</p></td></tr>
                                        </table>
                                    </div>
                                    <img src="images/footer.png">
                                </div>
                            </body>';
                }
                
                if(isset($_POST['GestionMatricula']['pago_consejo']) && !empty($_POST['GestionMatricula']['pago_consejo'])){
                    $general = '<body style="margin: 10px;">
                                <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;">
                                    <div align="center">
                                    <img src="images/header_mail.jpg">
                                    <br>
                                        <table width="600">
                                            <tr><td><p><strong>Status de Entrega:</strong> Pago al Consejo Provincial y Pago al Rodaje (si se aplica)</p></td></tr>
                                            <tr><td><p><strong>Fecha de Entrega:</strong>  '.$_POST['GestionMatricula']['agendamiento1'].'</p></td></tr>
                                        </table>
                                    </div>
                                    <img src="images/footer.png">
                                </div>
                            </body>';
                }
                if(isset($_POST['GestionMatricula']['venta_credito']) && !empty($_POST['GestionMatricula']['venta_credito'])){
                    $general = '<body style="margin: 10px;">
                                <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;">
                                    <div align="center">
                                    <img src="images/header_mail.jpg">
                                    <br>
                                        <table width="600">
                                            <tr><td><p><strong>Status de Entrega:</strong>Entrega de Contratos de la Entidad Bancaria para proceder con la matriculación</p></td></tr>
                                            <tr><td><p><strong>Fecha de Entrega:</strong>  '.$_POST['GestionMatricula']['agendamiento1'].'</p></td></tr>
                                        </table>
                                    </div>
                                    <img src="images/footer.png">
                                </div>
                            </body>';
                }
                if(isset($_POST['GestionMatricula']['entrega_documentos_gestor']) && !empty($_POST['GestionMatricula']['entrega_documentos_gestor'])){
                    $general = '<body style="margin: 10px;">
                                <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;">
                                    <div align="center">
                                    <img src="images/header_mail.jpg">
                                    <br>
                                        <table width="600">
                                            <tr><td><p><strong>Status de Entrega:</strong>Entregar todos los documentos al Gestor Calificado</p></td></tr>
                                            <tr><td><p><strong>Fecha de Entrega:</strong>  '.$_POST['GestionMatricula']['agendamiento1'].'</p></td></tr>
                                        </table>
                                    </div>
                                    <img src="images/footer.png">
                                </div>
                            </body>';
                }
                if(isset($_POST['GestionMatricula']['ente_regulador_placa']) && !empty($_POST['GestionMatricula']['ente_regulador_placa'])){
                    $general = '<body style="margin: 10px;">
                                <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;">
                                    <div align="center">
                                    <img src="images/header_mail.jpg">
                                    <br>
                                        <table width="600">
                                            <tr><td><p><strong>Status de Entrega:</strong>Entrega de Placas al Gestor</p></td></tr>
                                            <tr><td><p><strong>Fecha de Entrega:</strong>  '.$_POST['GestionMatricula']['agendamiento1'].'</p></td></tr>
                                        </table>
                                    </div>
                                    <img src="images/footer.png">
                                </div>
                            </body>';
                }
                if(isset($_POST['GestionMatricula']['vehiculo_matricula_placas']) && !empty($_POST['GestionMatricula']['vehiculo_matricula_placas'])){
                    $general = '<body style="margin: 10px;">
                                <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;">
                                    <div align="center">
                                    <img src="images/header_mail.jpg">
                                    <br>
                                        <table width="600">
                                            <tr><td><p><strong>Status de Entrega:</strong>Entrega de Vehículo con Matrícula y Placas de acuerdo a la Resolución 123-DIR-2013-ANT.</p></td></tr>
                                            <tr><td><p><strong>Fecha de Entrega:</strong>  '.$_POST['GestionMatricula']['agendamiento1'].'</p></td></tr>
                                        </table>
                                    </div>
                                    <img src="images/footer.png">
                                </div>
                            </body>';
                }
                $codigohtml = $general;
                $headers = 'From: servicioalcliente@kiamail.com.ec' . "\r\n";
                $headers .= 'Content-type: text/html' . "\r\n";
                $email = $emailCliente; //email cliente
                //die('before send email info');
                $send = sendEmailInfo('servicioalcliente@kiamail.com.ec', "Kia Motors Ecuador", $email, html_entity_decode($asunto), $codigohtml);
                $result = TRUE;
                $options = array('result' => $result);
                echo json_encode($options);
            }
        }

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

        if (isset($_POST['GestionMatricula'])) {
            $model->attributes = $_POST['GestionMatricula'];
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
        $dataProvider = new CActiveDataProvider('GestionMatricula');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new GestionMatricula('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['GestionMatricula']))
            $model->attributes = $_GET['GestionMatricula'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return GestionMatricula the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = GestionMatricula::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param GestionMatricula $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'gestion-matricula-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
