<?php

class GestionDiariaController extends Controller {

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
                'actions' => array('create', 'update', 'calendar', 'createAjax', 'icalEvent'),
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
    public function actionCreate($id_informacion = null, $id = null) {
        $model = new GestionDiaria;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionDiaria'])) {
//            echo '<pre>';
//            print_r($_POST);
//            echo '</pre>';
//            die('enter post');
            $model->attributes = $_POST['GestionDiaria'];
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            $model->status = $_POST['GestionDiaria']['status'][0];
            // subir imagen principal del test drive al servidor
            $archivoThumb = CUploadedFile::getInstance($model, 'img');
            $fileName = "{$archivoThumb}";  // file name
            if ($archivoThumb != "") {
                //die('enter file');
                $model->img = $fileName;
                if ($model->save()) {
                    $archivoThumb->saveAs(Yii::getPathOfAlias("webroot") . "/images/uploads/" . $fileName);
                    $link = Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName;
                    $image = new EasyImage($link);
                    $image->resize(600, 480); // resize images for thumbs
                    $image->save(Yii::getPathOfAlias('webroot') . '/images/uploads/' . $fileName);
                }
            }
            if ($model->save())
                $this->redirect(array('gestionVehiculo/create/' . $id_informacion));
        }

        $this->render('create', array(
            'model' => $model, 'id_informacion' => $id_informacion, 'id' => $id
        ));
    }

    /**
     * Creates a new model via Ajax send.     * If creation is successful, the browser will be render with new alert.
     */
    public function actionCreateAjax($id_informacion = null, $id = null) {
        $model = new GestionDiaria;
        if (isset($_POST['GestionDiaria'])) {
//            echo '<pre>';
//            print_r($_POST);
//            echo '</pre>';
//            die('enter post');
            $model->attributes = $_POST['GestionDiaria'];
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $model->fecha = date("Y-m-d H:i:s");
            $model->id_informacion = $_POST['GestionDiaria']['id_informacion'];
            $model->id_vehiculo = $_POST['GestionDiaria']['id_vehiculo'];
            $model->observaciones = $_POST['GestionDiaria']['opciones_seguimiento'];
            $model->medio_contacto = 'visita';
            $model->fuente_contacto = 'na';
            $model->codigo_vehiculo = 0;
            if (isset($_POST['GestionDiaria']['primera_visita'])):
                $model->primera_visita = $_POST['GestionDiaria']['primera_visita'];
            endif;
            $model->seguimiento = $_POST['GestionDiaria']['seguimiento'];
            $model->proximo_seguimiento = $_POST['GestionDiaria']['agendamiento'];
            $model->fecha = date("Y-m-d H:i:s");
            if ($model->validate()) {
                $res = $model->save();
                //die('res: '.$res);
                if ($res == TRUE)
                    echo TRUE;
                else
                    echo FALSE;
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

        if (isset($_POST['GestionDiaria'])) {
            $model->attributes = $_POST['GestionDiaria'];
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
        $dataProvider = new CActiveDataProvider('GestionDiaria');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new GestionDiaria('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['GestionDiaria']))
            $model->attributes = $_GET['GestionDiaria'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return GestionDiaria the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = GestionDiaria::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param GestionDiaria $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'gestion-diaria-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * 
     */
    public function actionCalendar($date, $startTime, $endTime, $subject, $desc, $cliente, $location, $tipo, $conc) {
        //set correct content-type-header
        header('Content-type: text/calendar; charset=utf-8');
        header('Content-Disposition: inline; filename=calendarsdf.ics');
        //date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador

        $from_name = 'Asiauto Mariana de Jesús';
        $from_address = 'carli-c@hotmail.com';
        $to_name = $cliente;
        $to_address = 'carli-c@hotmail.com';
        //$location = 'Quicentro Shopping';
        if ($conc == 'si') {
            $location = $this->getConcesionario($location);
        }
        $date = $date;
        $startTime = $startTime;
        $endTime = "1400";
        $subject = $subject;
        $description = $desc;
        $location = 'IESS';

        // PRUEBA PARA OUTLOOK 
        /* $ical = "BEGIN:VCALENDAR\n";
          $ical .= "VERSION:2.0\n";
          $ical .= "PRODID:-//Foobar Corporation//NONSGML Foobar//EN\n";
          $ical .= "METHOD:REQUEST\n"; // requied by Outlook
          $ical .= "BEGIN:VEVENT\n";
          $ical .= 'ORGANIZER;CN="' . $from_name . '":MAILTO:' . $from_address . "\n";
          $ical .= 'ATTENDEE;CN="' . $to_name . '";ROLE=REQ-PARTICIPANT;RSVP=TRUE:MAILTO:' . $to_address . "\n";
          $ical .= "UID:" . date('Ymd') . 'T' . date('His') . "-" . rand() . "-kia.com.ec\n"; // required by Outlok
          $ical .= "DTSTAMP:" . date('Ymd') . 'T' . date('His') . "\n"; // required by Outlook
          $ical .= "DTSTART:" . $date . "T" . $startTime . "00\n";
          $ical .= "SUMMARY:Cita con Cliente " . $cliente . "\n";
          if ($tipo == 'in') {
          $ical .= 'LOCATION:' . $location . "\r\n";
          }
          $ical .= "DESCRIPTION: Cita con cliente para seguimiento\n";
          $ical .= "END:VEVENT\n";
          $ical .= "END:VCALENDAR\n";
          echo $ical; */

        // PRUEBA PARA ANDROID----------------------------
        $ical = "BEGIN:VCALENDAR
          VERSION:2.0
          PRODID:-//hacksw/handcal//NONSGML v1.0//EN
          BEGIN:VEVENT
          UID:" . md5(uniqid(mt_rand(), true)) . "example.com
          DTSTAMP:" . gmdate('Ymd') . 'T' . gmdate('His') . "Z
          DTSTART:" . $date . "T" . $startTime . "00Z
          DTEND:" . $date . "T" . $endTime . "00Z
          SUMMARY:" . $subject . "
          DESCRIPTION:" . $description . "
          END:VEVENT
          END:VCALENDAR";
        echo $ical;
    }

    public function actionIcalEvent($from_name, $from_address, $to_name, $to_address, $startTime, $endTime, $subject, $description, $location) {
        header('Content-type: text/calendar; charset=utf-8');
        header('Content-Disposition: inline; filename=calendar.ics');
        $domain = 'www.kia.com.ec';
        $ical = 'BEGIN:VCALENDAR' . "\r\n" .
                'PRODID:-//Microsoft Corporation//Outlook 10.0 MIMEDIR//EN' . "\r\n" .
                'VERSION:2.0' . "\r\n" .
                'METHOD:REQUEST' . "\r\n" .
                'BEGIN:VTIMEZONE' . "\r\n" .
                'TZID:Eastern Time' . "\r\n" .
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
                'DTSTART;TZID="Eastern Time":' . date("Ymd\THis", strtotime($startTime)) . "\r\n" .
                'DTEND;TZID="Eastern Time":' . date("Ymd\THis", strtotime($endTime)) . "\r\n" .
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

}
