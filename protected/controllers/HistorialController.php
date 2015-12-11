<?php

class HistorialController extends Controller {

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
                'actions' => array('create', 'update', 'getobservaciones', 'exportexcel'),
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
        $model = new Historial;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Historial'])) {
            $model->attributes = $_POST['Historial'];
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

        if (isset($_POST['Historial'])) {
            $model->attributes = $_POST['Historial'];
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
        $dataProvider = new CActiveDataProvider('Historial');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Historial('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Historial']))
            $model->attributes = $_GET['Historial'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Historial the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Historial::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Historial $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'historial-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionGetobservaciones() {
        $idObs = isset($_POST["id"]) ? $_POST["id"] : "";
        $criteria = new CDbCriteria(array(
                    'condition' => "id='{$idObs}'"
                ));
        $obs = Historial::model()->findAll($criteria);
        $observacion = '';
        foreach ($obs as $model) {
            $observacion = $model['observaciones'];
        }
        $options = array('options' => $observacion);
        echo json_encode($options);
    }

    public function actionExportExcel() {
        $concesionario = Yii::app()->user->getState('dealer_id');
        $rol = Yii::app()->user->getState('roles');
        if (isset($_POST['Historial'])) {
            $criteria = new CDbCriteria;
            $criteria->condition = "id_caso={$_POST['Historial']['id_caso']}";
            $criteria->order = 'id desc';
            $casos = Historial::model()->findAll($criteria);
            $tituloReporte = "Reporte de Historial Totales " . date("Y-m-d");
            $name_file = 'Reporte de Historial Total ' . date("Y-m-d") . '.xls';
        }

        Yii::import('ext.phpexcel.XPHPExcel');
        $objPHPExcel = XPHPExcel::createPHPExcel();
        $objPHPExcel->getProperties()->setCreator("Call Center Kia Ecuador")
                ->setLastModifiedBy("Call Center")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");
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
                ->mergeCells('A1:J1');


        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', $tituloReporte) // Titulo del reporte
                ->setCellValue('A2', 'Id')
                ->setCellValue('B2', 'Fecha')
                ->setCellValue('C2', 'Tema')
                ->setCellValue('D2', 'Subtema')
                ->setCellValue('E2', 'Responsable')
                ->setCellValue('F2', 'Estado')
                ->setCellValue('G2', 'Observaciones');

        $i = 3;
        
        foreach ($casos as $row) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $row['id'])
                    ->setCellValue('B' . $i, $row['fecha'])
                    ->setCellValue('C' . $i, $this->getTema($row['tema']))
                    ->setCellValue('D' . $i, $this->getSubtema($row['subtema']))
                    ->setCellValue('E' . $i, $this->getResponsable($row['id_responsable']))
                    ->setCellValue('F' . $i, $row['estado'])
                    ->setCellValue('G' . $i, $row['observaciones']);

            $objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $i, $row['fecha'], PHPExcel_Cell_DataType::TYPE_STRING);
            //$objPHPExcel->getActiveSheet()->getStyle('M' . $i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $i++;
        }
        $style1 = array(
            'font' => array(
                'name' => 'Calibri',
                'bold' => true,
                'size' => 11,
            ),
        );
        $objPHPExcel->getActiveSheet()->getStyle('A2:G2')->applyFromArray($style1);
        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("G")->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension("H")->setAutoSize(true);
        
        // rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Reporte de historial');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($estiloTituloReporte);
        $objPHPExcel->getActiveSheet()->getStyle('A2:G2')->applyFromArray($estiloTituloColumnas);

        // Inmovilizar paneles
        $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0, 3);

        // Redirect output to a client's web browser (Excel5)
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

}
