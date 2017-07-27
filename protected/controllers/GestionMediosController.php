<?php

class GestionMediosController extends Controller {

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
                'actions' => array('create', 'update','reportes','traficoDetalle','reportesBusqueda','traficoDetalleConsidera'),
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
        $model = new GestionMedios;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['GestionMedios'])) {
            $model->attributes = $_POST['GestionMedios'];
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

        if (isset($_POST['GestionMedios'])) {
            $model->attributes = $_POST['GestionMedios'];
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
        $dataProvider = new CActiveDataProvider('GestionMedios');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new GestionMedios('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['GestionMedios']))
            $model->attributes = $_GET['GestionMedios'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return GestionMedios the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = GestionMedios::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param GestionMedios $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'gestion-medios-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionReportes() {
    	/*echo '<pre>';
    	print_r($_POST);
    	echo '</pre>';
    	die();
    	*/

    	if(isset($_POST['GI'])){

			
	    	$fechas1=$_POST['GI']['fecha1'];
	    	$fechas2=$_POST['GI']['fecha2'];
	      
    	
    	}
		else{
			$fechas1=null;
			$fechas2=null;
		}
		//$fechasDesde = $fechas1 //array();
		//$fechasHasta = array();
	    		
    		//die($_POST['GI'] );
    		//die();
    		


        date_default_timezone_set('America/Guayaquil');
        $dt = time();
        setlocale(LC_TIME, 'es_ES.UTF-8');
        $varView = array();
        $varView['grupo_id'] = (int) Yii::app()->user->getState('grupo_id');
        $varView['cargo_id'] = (int) Yii::app()->user->getState('cargo_id');
        $varView['area_id'] = (int) Yii::app()->user->getState('area_id');
        $varView['cargo_adicional'] = (int) Yii::app()->user->getState('cargo_adicional');
        $varView['id_responsable'] = Yii::app()->user->getId();
        $varView['dealer_id'] = $this->getDealerId($varView['id_responsable']);
        $varView['nombre_usuario'] = Usuarios::model()->findByPk($varView['id_responsable']);
        $varView['provincia_id'] = $varView['nombre_usuario']->provincia_id;
        $varView['cargo_usuario'] = Cargo::model()->findByPk($varView['nombre_usuario']->cargo_id);
       
        
        


        if(!is_null($fechas1)&&!is_null($fechas2)){
        	$rangoFechas1=explode(" - ", $fechas1);//str_split($fechas1,' - ');
        	$rangoFechas2=explode(" - ", $fechas2);//str_split($fechas2);
        	

			
        	$varView['fecha_actual']=$rangoFechas1[1];//$fecha_final1;
	        
	        $varView['fecha_inicial_actual'] = $rangoFechas1[0];//$fecha_inicial1;
	        
	        $varView['fecha_anterior'] = $rangoFechas2[1];//$fecha_final2;
	        $varView['fecha_inicial_anterior'] = $rangoFechas2[0];//$fecha_inicial2;	

        }
        else{
        	
        	 $varView['fecha_actual'] = strftime("%Y-%m-%d", $dt);
	        $varView['fecha_actual2'] = strtotime('+1 day', strtotime($varView['fecha_actual']));
	        $varView['fecha_actual2'] = date('Y-m-d', $varView['fecha_actual2']);
	        $varView['fecha_inicial_actual'] = (new DateTime('first day of this month'))->format('Y-m-d');
	        $varView['fecha_anterior'] = strftime("%Y-%m-%d", strtotime('-1 month', $dt));
	        $varView['fecha_inicial_anterior'] = strftime("%Y-%m", strtotime('-1 month', $dt)) . '-01';
        }
       
        $varView['cine'] = $this->getReporte('cine',$varView['fecha_inicial_actual'], $varView['fecha_actual']);

        $varView['cine_anterior'] = $this->getReporte('cine',$varView['fecha_inicial_anterior'], $varView['fecha_anterior']);
        $varView['television'] = $this->getReporte('television',$varView['fecha_inicial_actual'], $varView['fecha_actual']);
        $varView['television_anterior'] = $this->getReporte('television',$varView['fecha_inicial_anterior'], $varView['fecha_anterior']);
        $varView['prensa_escrita'] = $this->getReporte('prensa_escrita',$varView['fecha_inicial_actual'], $varView['fecha_actual']);
        $varView['prensa_escrita_anterior'] = $this->getReporte('prensa_escrita',$varView['fecha_inicial_anterior'], $varView['fecha_anterior']);
        $varView['radio'] = $this->getReporte('radio',$varView['fecha_inicial_actual'], $varView['fecha_actual']);
        $varView['radio_anterior'] = $this->getReporte('radio',$varView['fecha_inicial_anterior'], $varView['fecha_anterior']);
        $varView['recomendacion'] = $this->getReporte('recomendacion',$varView['fecha_inicial_actual'], $varView['fecha_actual']);
        $varView['recomendacion_anterior'] = $this->getReporte('recomendacion',$varView['fecha_inicial_anterior'], $varView['fecha_anterior']);
        $varView['pagina_web'] = $this->getReporte('pagina_web',$varView['fecha_inicial_actual'], $varView['fecha_actual']);
        $varView['pagina_web_anterior'] = $this->getReporte('pagina_web',$varView['fecha_inicial_anterior'], $varView['fecha_anterior']);
        $varView['internet'] = $this->getReporte('internet',$varView['fecha_inicial_actual'], $varView['fecha_actual']);
        $varView['internet_anterior'] = $this->getReporte('internet',$varView['fecha_inicial_anterior'], $varView['fecha_anterior']);
        $varView['redes_sociales'] = $this->getReporte('redessociales',$varView['fecha_inicial_actual'], $varView['fecha_actual']);
        $varView['redes_sociales_anterior'] = $this->getReporte('redessociales',$varView['fecha_inicial_anterior'], $varView['fecha_anterior']);
        

        /*PREGUNTA PORQUE CONSIDERO KIA*/

        $varView['garantia'] = $this->getReporteConsidera('garantia',$varView['fecha_inicial_actual'], $varView['fecha_actual']);	
		$varView['garantia_anterior'] = $this->getReporteConsidera('garantia',$varView['fecha_inicial_anterior'], $varView['fecha_anterior']);


		$varView['diseño'] = $this->getReporteConsidera('diseno',$varView['fecha_inicial_actual'], $varView['fecha_actual']);	
		$varView['diseño_anterior'] = $this->getReporteConsidera('diseno',$varView['fecha_inicial_anterior'], $varView['fecha_anterior']);

		$varView['precio'] = $this->getReporteConsidera('precio',$varView['fecha_inicial_actual'], $varView['fecha_actual']);	
		$varView['precio_anterior'] = $this->getReporteConsidera('precio',$varView['fecha_inicial_anterior'], $varView['fecha_anterior']);


		$varView['recomendacion_considera'] = $this->getReporteConsidera('recomendacion',$varView['fecha_inicial_actual'], $varView['fecha_actual']);	
		$varView['recomendacion_considera_anterior'] = $this->getReporteConsidera('recomendacion',$varView['fecha_inicial_anterior'], $varView['fecha_anterior']);


		$varView['servicio'] = $this->getReporteConsidera('servicio',$varView['fecha_inicial_actual'], $varView['fecha_actual']);	
		$varView['servicio_anterior'] = $this->getReporteConsidera('servicio',$varView['fecha_inicial_anterior'], $varView['fecha_anterior']);


		$varView['recompra'] = $this->getReporteConsidera('recompra',$varView['fecha_inicial_actual'], $varView['fecha_actual']);	
		$varView['recompra_anterior'] = $this->getReporteConsidera('recompra',$varView['fecha_inicial_anterior'], $varView['fecha_anterior']);



        $this->render('reportes', array('varView' => $varView));



    }
    

     

    /**
     * 
     * @param string $tipo_medio - Medio Prensa, television 
     * @param date $fecha_inicial - Fecha inicial, primer dia del mes
     * @param date $fecha_actual - Fecha actual del mes actual
     */
    public function getReporte($tipo_medio, $fecha_inicial, $fecha_actual) {
        $criteria = new CDbCriteria;
        $criteria->select = "DISTINCT gi.nombres, gi.apellidos, gi.cedula, gi.ruc, gi.pasaporte, gi.email, gi.celular, gi.telefono_casa, gi.direccion, m.nombre_modelo, v.nombre_version, gi.fecha, u.nombres AS nombre_responsable, u.apellido AS apellido_responsable, gi.medio as pregunta, 
gi.recomendaron AS opcion_recomendacion, gi.medio_prensa, gi.medio_television, gi.considero as marca_kia, 
gi.considero_recomendaron as marca_kia_recomendacion, gi.bdc";
        $criteria->alias = 'gi';
        $criteria->join = "INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ";
        $criteria->join .= " INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id ";
        $criteria->join .= " INNER JOIN modelos m ON m.id_modelos = gv.modelo"; 
        $criteria->join .= " LEFT JOIN versiones v ON v.id_versiones = gv.version";
        $criteria->join .= " INNER JOIN dealers d ON d.id = gi.dealer_id ";
        $criteria->join .= " LEFT JOIN usuarios u ON u.id = gi.responsable ";
        $criteria->condition = "gi.medio = '{$tipo_medio}'";
        $criteria->addCondition("(DATE(gi.fecha) BETWEEN '{$fecha_inicial}' AND '{$fecha_actual}')");
        $criteria->addCondition("gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico'");
        $criteria->addCondition(" gi.bdc = 0 AND gv.orden = 1");
    //    echo '<pre>';
    //    print_r($criteria);
    //   echo '</pre>';
    //    die();
       
        $posts = GestionInformacion::model()->findAll($criteria);
        return count($posts);
    }
    public function getReporteConsidera($considera, $fecha_inicial, $fecha_actual) {
        $criteria = new CDbCriteria;
        $criteria->select = "DISTINCT gi.nombres, gi.apellidos, gi.cedula, gi.ruc, gi.pasaporte, gi.email, gi.celular, gi.telefono_casa, gi.direccion, m.nombre_modelo, v.nombre_version, gi.fecha, u.nombres AS nombre_responsable, u.apellido AS apellido_responsable, gi.medio as pregunta, 
gi.recomendaron AS opcion_recomendacion, gi.medio_prensa, gi.medio_television, gi.considero as marca_kia, 
gi.considero_recomendaron as marca_kia_recomendacion, gi.bdc";
        $criteria->alias = 'gi';
        $criteria->join = "INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ";
        $criteria->join .= " INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id ";
        $criteria->join .= " INNER JOIN modelos m ON m.id_modelos = gv.modelo"; 
        $criteria->join .= " LEFT JOIN versiones v ON v.id_versiones = gv.version";
        $criteria->join .= " INNER JOIN dealers d ON d.id = gi.dealer_id ";
        $criteria->join .= " LEFT JOIN usuarios u ON u.id = gi.responsable ";
        $criteria->condition = "gi.considero = '{$considera}'";
        $criteria->addCondition("(DATE(gi.fecha) BETWEEN '{$fecha_inicial}' AND '{$fecha_actual}')");
        $criteria->addCondition("gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico'");
        $criteria->addCondition(" gi.bdc = 0 AND gv.orden = 1");
       
        $posts = GestionInformacion::model()->findAll($criteria);
        return count($posts);
    }
   
    public function actionTraficoDetalle() {


        $tipo_medio =  isset($_POST['tipo_medio']) ? $_POST["tipo_medio"] : "";

        $fecha_inicial = isset($_POST['fecha_inicial']) ? $_POST["fecha_inicial"] : "";
        $fecha_final = isset($_POST['fecha_final']) ? $_POST["fecha_final"] : "";
        //$provincia = isset($_POST['provincia']) ? $_POST["provincia"] : "";

        if($tipo_medio == 'television'){
          
           $group='gi.medio_television';
           
        }
        else if($tipo_medio == 'prensa_escrita'){
        	
        	$group='gi.medio_prensa';
           
        }
        else if($tipo_medio == 'recomendacion'){
        	
        	$group='gi.recomendaron';
           
        }
 		$sql = 'SELECT DISTINCT ' . $group .' AS medio, count(gi.id) as cantidad FROM gestion_informacion gi 
        INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id  
        INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id  
        INNER JOIN modelos m ON m.id_modelos = gv.modelo 
        LEFT JOIN versiones v ON v.id_versiones = gv.version 
        INNER JOIN dealers d ON d.id = gi.dealer_id 
        LEFT JOIN usuarios u ON u.id = gi.responsable 
        WHERE gi.medio = \''.$tipo_medio.'\' AND (DATE(gi.fecha) BETWEEN \''.$fecha_inicial.'\' AND \''.$fecha_final.'\') 
        AND (gd.fuente_contacto = "showroom" OR gd.fuente_contacto = "trafico") AND ( gi.bdc = 0 AND gv.orden = 1)
        GROUP BY '.$group.' HAVING cantidad>0 ORDER BY cantidad DESC';
        //die($sql);
 		
        $det = Yii::app()->db->createCommand($sql)->queryAll();
        $options = array('data' => $det);
        echo json_encode($options);
    }
    
    public function actionTraficoDetalleConsidera() {


        $tipo_considera =  isset($_POST['tipo_considera']) ? $_POST["tipo_considera"] : "";

        $fecha_inicial = isset($_POST['fecha_inicial']) ? $_POST["fecha_inicial"] : "";
        $fecha_final = isset($_POST['fecha_final']) ? $_POST["fecha_final"] : "";
        //$provincia = isset($_POST['provincia']) ? $_POST["provincia"] : "";

        
       
       
       
        if($tipo_considera == 'recomendacion'){
          
           $group='gi.considero_recomendaron';
           
        }
       
 		$sql = 'SELECT DISTINCT ' . $group .' AS medio, count(gi.id) as cantidad 
        FROM callcenter.gestion_informacion gi 
        INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id  
        INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id  
        INNER JOIN modelos m ON m.id_modelos = gv.modelo 
        LEFT JOIN versiones v ON v.id_versiones = gv.version 
        INNER JOIN dealers d ON d.id = gi.dealer_id 
        LEFT JOIN usuarios u ON u.id = gi.responsable 
        WHERE gi.considero = \''.$tipo_considera.'\' 
        AND (DATE(gi.fecha) BETWEEN \''.$fecha_inicial.'\' AND \''.$fecha_final.'\') 
        AND (gd.fuente_contacto = "showroom" OR gd.fuente_contacto = "trafico") AND ( gi.bdc = 0 AND gv.orden = 1) 
        GROUP BY '.$group.' HAVING cantidad>0 ORDER BY cantidad DESC';
 		
        $det = Yii::app()->db->createCommand($sql)->queryAll();
        $options = array('data' => $det);
        echo json_encode($options);
    }
}



