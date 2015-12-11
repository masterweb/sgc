<?php

class GestionAgendamientoController extends Controller {

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
                'actions' => array('create', 'update', 'createCat'),
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
        $model = new GestionAgendamiento;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionAgendamiento'])) {
            //die('enter agendamiento');
//            echo '<pre>';
//            print_r($_POST);
//            echo '</pre>';
//            die();
            $model->attributes = $_POST['GestionAgendamiento'];
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            if (isset($_POST['GestionAgendamiento']['observacion_categorizacion']) && !empty($_POST['GestionAgendamiento']['observacion_categorizacion']))
                $model->observacion_categorizacion = $_POST['GestionAgendamiento']['observacion_categorizacion'];
            if (isset($_POST['GestionAgendamiento']['observacion_seguimiento']) && !empty($_POST['GestionAgendamiento']['observacion_seguimiento']))
                $model->observacion_seguimiento = $_POST['GestionAgendamiento']['observacion_seguimiento'];

            //if (isset($_POST['GestionAgendamiento']['tipo_form_web']) && !empty($_POST['GestionAgendamiento']['tipo_form_web']))
            if ($_POST['GestionAgendamiento']['observaciones'] == 'Otro')
                $model->otro_observacion = $_POST['GestionAgendamiento']['otro'];


            switch ($_POST['GestionAgendamiento']['observaciones']) {
                case 'Falta de tiempo':
                case 'Llamada de emergencia':
                    // poner status Seguimiento en Gestion Diaria
                    $con = Yii::app()->db;
                    $sql = "UPDATE gestion_diaria SET seguimiento = 1 ,proximo_seguimiento = '{$_POST['GestionAgendamiento']['agendamiento']}' WHERE id_informacion = {$_POST['GestionAgendamiento']['id_informacion']}";
                    $request = $con->createCommand($sql)->query();

                    break;
                //SI EL CLIENTE DESISTE VA AL CALLCENTER PARA ENCUESTA
                case 'Desiste':
                    $con = Yii::app()->db;
                    $sql = "UPDATE gestion_diaria SET desiste = 1 WHERE id_informacion = {$_POST['GestionAgendamiento']['id_informacion']}";
                    $request = $con->createCommand($sql)->query();
                    break;

                default:
                    break;
            }

            $not = new GestionNotificaciones;
            if (isset($_POST['GestionAgendamiento']['pasoconsulta'])) {
                switch ($_POST['GestionAgendamiento']['categorizacion']) {
                    case '1':
                        $model->categorizacion = 'Hot A(hasta 7 dias)';
                        $not->categorizacion = 'Hot A(hasta 7 dias)';
                        break;
                    case '2':
                        $model->categorizacion = 'Hot B(hasta 15 dias)';
                        $not->categorizacion = 'Hot B(hasta 15 dias)';
                        break;
                    case '3':
                        $model->categorizacion = 'Hot C(hasta 30 dias)';
                        $not->categorizacion = 'Hot B(hasta 15 dias)';
                        break;
                    case '4':
                        $model->categorizacion = 'Warm(hasta 3 meses)';
                        $not->categorizacion = 'Hot B(hasta 15 dias)';
                        break;
                    case '5':
                        $model->categorizacion = 'Warm(hasta 6 meses)';
                        $not->categorizacion = 'Hot B(hasta 15 dias)';
                        break;
                    case '6':
                        $model->categorizacion = 'Very Cold(mas de 6 meses)';
                        $not->categorizacion = 'Hot B(hasta 15 dias)';
                        break;

                    default:
                        break;
                }
            } elseif (isset($_POST['GestionAgendamiento']['categorizacion'])) {
                //die('enter normal');
                switch ($_POST['GestionAgendamiento']['categorizacion']) {
                    case 'Hot A (hasta 7 dias)':
                        $model->categorizacion = 'Hot A(hasta 7 dias)';
                        $not->categorizacion = 'Hot A(hasta 7 dias)';
                        break;
                    case 'Hot B (hasta 15 dias)':
                        $model->categorizacion = 'Hot B(hasta 15 dias)';
                        $not->categorizacion = 'Hot B(hasta 15 dias)';
                        break;
                    case 'Hot C (hasta 30 dias)':
                        $model->categorizacion = 'Hot C(hasta 30 dias)';
                        $not->categorizacion = 'Hot C(hasta 30 dias)';
                        break;
                    case 'Warm (hasta 3 meses)':
                        $model->categorizacion = 'Warm(hasta 3 meses)';
                        $not->categorizacion = 'Warm(hasta 3 meses)';
                        break;
                    case 'Cold (hasta 6 meses)':
                        $model->categorizacion = 'Cold (hasta 6 meses)';
                        $not->categorizacion = 'Cold (hasta 6 meses)';
                        break;
                    case 'Very Cold(mas de 6 meses)':
                        $model->categorizacion = 'Very Cold(mas de 6 meses)';
                        $not->categorizacion = 'Very Cold(mas de 6 meses)';
                        break;

                    default:
                        break;
                }
            }

            if (isset($_POST['GestionAgendamiento']['categorizacion'])) {
                $con = Yii::app()->db;
                $sql = "UPDATE gestion_diaria SET seguimiento = 1 ,proximo_seguimiento = '{$_POST['GestionAgendamiento']['agendamiento']}' WHERE id_informacion = {$_POST['GestionAgendamiento']['id_informacion']}";
                $request = $con->createCommand($sql)->query();
            }

            if (isset($_POST['GestionInformacion']['tipo_form_web'])) {
                $con = Yii::app()->db;
                $sql = "UPDATE gestion_diaria SET seguimiento = 1 ,proximo_seguimiento = '{$_POST['GestionAgendamiento']['agendamiento']}' WHERE id_informacion = {$_POST['GestionAgendamiento']['id_informacion']}";
                $request = $con->createCommand($sql)->query();
                if ($model->save())
                    $this->redirect(array('gestionInformacion/seguimientoUsados'));
            }

            // CONOCER SI EL TIPO DE FUENTE EN EL FORMULARIO ES EXONERADO PARA REDIRECCIONAR AL RGD DE EXONERADOS
            $fuente = $this->getFuenteExonerados($_POST['GestionAgendamiento']['id_informacion']);
            if ($fuente == 'exonerados') {
                $con = Yii::app()->db;
                $sql = "UPDATE gestion_diaria SET seguimiento = 1 ,proximo_seguimiento = '{$_POST['GestionAgendamiento']['agendamiento']}' WHERE id_informacion = {$_POST['GestionAgendamiento']['id_informacion']}";
                $request = $con->createCommand($sql)->query();
                if ($model->save())
                    $this->redirect(array('gestionInformacion/seguimientoexonerados'));
            }


            if ($model->save()) {
                $id_asesor = Yii::app()->user->getId();
                $not->tipo = 1; // tipo seguimiento
                $not->paso = $_POST['GestionAgendamiento']['paso'];
                $not->id_informacion = $_POST['GestionAgendamiento']['id_informacion'];
                $not->id_asesor = $id_asesor;
                $not->id_dealer = $this->getDealerId(Yii::app()->user->getId());
                $not->descripcion = 'Se ha creado un nuevo seguimiento';
                $not->fecha = date("Y-m-d H:i:s");
                $not->id_agendamiento = $model->id;
                $not->save();
                $this->redirect(array('gestionInformacion/seguimiento'));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionCreateCat() {
        if (isset($_POST['GestionAgendamiento'])) {

//            echo '<pre>';
//            print_r($_POST);
//            echo '</pre>';
//            die();

            $connection = Yii::app()->db;
            $sql = "UPDATE gestion_consulta SET preg7 = '{$_POST['GestionAgendamiento']['categorizacion']}' "
                    . "WHERE id_informacion = {$_POST['GestionAgendamiento']['id_informacion']} ";

            $command = $connection->createCommand($sql);
            $rowCount = $command->execute();

            if ($rowCount > 0)
                echo TRUE;
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

        if (isset($_POST['GestionAgendamiento'])) {
            $model->attributes = $_POST['GestionAgendamiento'];
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
        $dataProvider = new CActiveDataProvider('GestionAgendamiento');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new GestionAgendamiento('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['GestionAgendamiento']))
            $model->attributes = $_GET['GestionAgendamiento'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return GestionAgendamiento the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = GestionAgendamiento::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param GestionAgendamiento $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'gestion-agendamiento-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
