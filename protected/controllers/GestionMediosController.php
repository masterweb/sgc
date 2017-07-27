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
                'actions' => array('create', 'update','reportes','traficoDetalle','reportesBusqueda','traficoDetalleConsidera','getGrupos','getConcesionarios','getResponsables'),
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
    public function actionGetGrupos(){
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        if($id!=1000)
             $res = GrGrupo::model()->findAll(array('condition' => "FIND_IN_SET('" . $id . "',id_provincias) > 0"));
        else    
            $res = GrGrupo::model()->findAll();//GrConcesionarios::model()->findAll();

         $data = '<option value="">--Seleccione Grupo--</option><option value="1000">Todos</option>';
            if (count($res) > 0) {
                foreach ($res as $value) {
                    $data .= '<option value="' . $value['id'] . '">' . $value['nombre_grupo'] . '</option>';
                }
            }
                echo $data;

    }
     public function actionGetConcesionarios(){
        
        $idProvincia = isset($_POST["idProvincia"]) ? $_POST["idProvincia"] : "";
        $idGrupo = isset($_POST["idGrupo"]) ? $_POST["idGrupo"] : "";


         $criteria = new CDbCriteria;
        $criteria->condition = "(provincia = '{$idProvincia}')";
        


        if($idGrupo!=1000)
             $criteria->addCondition("(id_grupo = '{$idGrupo}')");
        


         $res = GrConcesionarios::model()->findAll($criteria);//GrConcesionarios::model()->findAll();

         


         $data = '<option value="">--Seleccione Concesionario--</option><option value="1000">Todos</option>';
            if (count($res) > 0) {
                foreach ($res as $value) {
                    $data .= '<option value="' . $value['dealer_id'] . '">' . $value['nombre'] . '</option>';
                }
            }
                echo $data;

    }
    public function actionGetResponsables(){

        $dealer_id = isset($_POST["idConcesionario"]) ? $_POST["idConcesionario"] : "";
       
      
       
        $con = Yii::app()->db;
        if($dealer_id!=1000)
            $sql = "SELECT * FROM usuarios WHERE dealers_id = {$dealer_id} AND estado = 'ACTIVO' ORDER BY nombres ASC";
        else 
            $sql = "SELECT * FROM usuarios WHERE estado = 'ACTIVO' ORDER BY nombres ASC";

          $requestr1 = $con->createCommand($sql);
        $requestr1 = $requestr1->queryAll();
        $data = '<option value="">--Seleccione Asesor--</option><option value="1000">TODOS</option>';
      
        foreach ($requestr1 as $value) {
              $data .= '<option value="' . $value['id'] . '">';
            $data .= strtoupper($this->getResponsableNombres($value['id']));
            $data .= '</option>';
            
        }
        echo $data ;
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
       
        $varView['cine'] = $this->getReporte('cine',$varView['fecha_inicial_actual'], $varView['fecha_actual'],
                $_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable'] );
                
               /* echo $_POST['GI']['provincia'].'-'.$_POST['GI']['grupo'].'-'.$_POST['GI']['concesionario'].'-'.$_POST['GI']['responsable'];
                        */

        $varView['cine_anterior'] = $this->getReporte('cine',$varView['fecha_inicial_anterior'], $varView['fecha_anterior'],
            $_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable']);
        $varView['television'] = $this->getReporte('television',$varView['fecha_inicial_actual'], $varView['fecha_actual'],$_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable']);
        $varView['television_anterior'] = $this->getReporte('television',$varView['fecha_inicial_anterior'], $varView['fecha_anterior'],$_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable']);
        $varView['prensa_escrita'] = $this->getReporte('prensa_escrita',$varView['fecha_inicial_actual'], $varView['fecha_actual'],$_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable']);
        $varView['prensa_escrita_anterior'] = $this->getReporte('prensa_escrita',$varView['fecha_inicial_anterior'], $varView['fecha_anterior'],$_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable']);
        $varView['radio'] = $this->getReporte('radio',$varView['fecha_inicial_actual'], $varView['fecha_actual'],$_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable']);
        $varView['radio_anterior'] = $this->getReporte('radio',$varView['fecha_inicial_anterior'], $varView['fecha_anterior'],$_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable']);
        $varView['recomendacion'] = $this->getReporte('recomendacion',$varView['fecha_inicial_actual'], $varView['fecha_actual'],$_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable']);
        $varView['recomendacion_anterior'] = $this->getReporte('recomendacion',$varView['fecha_inicial_anterior'], $varView['fecha_anterior'],$_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable']);
        $varView['pagina_web'] = $this->getReporte('pagina_web',$varView['fecha_inicial_actual'], $varView['fecha_actual'],$_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable']);
        $varView['pagina_web_anterior'] = $this->getReporte('pagina_web',$varView['fecha_inicial_anterior'], $varView['fecha_anterior'],$_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable']);
        $varView['internet'] = $this->getReporte('internet',$varView['fecha_inicial_actual'], $varView['fecha_actual'],$_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable']);
        $varView['internet_anterior'] = $this->getReporte('internet',$varView['fecha_inicial_anterior'], $varView['fecha_anterior'],$_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable']);
        $varView['redes_sociales'] = $this->getReporte('redessociales',$varView['fecha_inicial_actual'], $varView['fecha_actual'],$_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable']);
        $varView['redes_sociales_anterior'] = $this->getReporte('redessociales',$varView['fecha_inicial_anterior'], $varView['fecha_anterior'],$_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable']);
        


        /*PREGUNTA PORQUE CONSIDERO KIA*/

        $varView['garantia'] = $this->getReporteConsidera('garantia',$varView['fecha_inicial_actual'], $varView['fecha_actual'],$_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable']); 
        $varView['garantia_anterior'] = $this->getReporteConsidera('garantia',$varView['fecha_inicial_anterior'], $varView['fecha_anterior'],$_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable']);


        $varView['diseño'] = $this->getReporteConsidera('diseno',$varView['fecha_inicial_actual'], $varView['fecha_actual'],$_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable']); 
        $varView['diseño_anterior'] = $this->getReporteConsidera('diseno',$varView['fecha_inicial_anterior'], $varView['fecha_anterior'],$_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable']);

        $varView['precio'] = $this->getReporteConsidera('precio',$varView['fecha_inicial_actual'], $varView['fecha_actual'],$_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable']); 
        $varView['precio_anterior'] = $this->getReporteConsidera('precio',$varView['fecha_inicial_anterior'], $varView['fecha_anterior'],$_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable']);


        $varView['recomendacion_considera'] = $this->getReporteConsidera('recomendacion',$varView['fecha_inicial_actual'], $varView['fecha_actual'],$_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable']); 
        $varView['recomendacion_considera_anterior'] = $this->getReporteConsidera('recomendacion',$varView['fecha_inicial_anterior'], $varView['fecha_anterior'],$_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable']);


        $varView['servicio'] = $this->getReporteConsidera('servicio',$varView['fecha_inicial_actual'], $varView['fecha_actual'],$_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable']); 
        $varView['servicio_anterior'] = $this->getReporteConsidera('servicio',$varView['fecha_inicial_anterior'], $varView['fecha_anterior'],$_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable']);


        $varView['recompra'] = $this->getReporteConsidera('recompra',$varView['fecha_inicial_actual'], $varView['fecha_actual'],$_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable']); 
        $varView['recompra_anterior'] = $this->getReporteConsidera('recompra',$varView['fecha_inicial_anterior'], $varView['fecha_anterior'],$_POST['GI']['provincia'],$_POST['GI']['grupo'],$_POST['GI']['concesionario'],$_POST['GI']['responsable']);

    $varView['provincia']=$_POST['GI']['provincia'];
    $varView['grupo']=$_POST['GI']['grupo'];
    $varView['concesionario']=$_POST['GI']['concesionario'];
    $varView['responsable']=$_POST['GI']['responsable'];

    

    $varView['titleBusqueda']="";
    if($varView['provincia']!=''){

        $varView['titleBusqueda']="<span class='label label-success' style='font-size:16px;width:100%'>Resultados de la Búsqueda</span><hr/>";
        $varView['titleBusqueda']=$varView['titleBusqueda']."<div style='display:flex; height:100%'>";
       
        if($varView['provincia']!='')
            $varView['titleBusqueda']=$varView['titleBusqueda']."<span class='label label-info' style='font-size:12px;'>Provincia: ".$this->getProvincia($varView['provincia'])."</span>";
        
        if($varView['grupo']!='')
            $varView['titleBusqueda']=$varView['titleBusqueda']."<div style='width:10px;'></div><span class='label label-info' style='font-size:12px;'>Grupo: ".$this->getNombreGrupo($varView['grupo'])."</span>";


        
        if($varView['concesionario']!='')
            $varView['titleBusqueda']=$varView['titleBusqueda']."<div style='width:10px;'></div><span class='label label-info' style='font-size:12px;'>Concesionario: ".$this->getNombreConcesionario($varView['concesionario'])."</span>";

        if($varView['responsable']!='')
            $varView['titleBusqueda']=$varView['titleBusqueda']."<div style='width:10px;'></div><span class='label label-info' style='font-size:12px;'>Responsable: ".$this->getResponsableNombres($varView['responsable'])."</span>";


          $varView['titleBusqueda']=$varView['titleBusqueda']."</div>";
    }      
    
        $this->render('reportes', array('varView' => $varView));



    }
    

     

    /**
     * 
     * @param string $tipo_medio - Medio Prensa, television 
     * @param date $fecha_inicial - Fecha inicial, primer dia del mes
     * @param date $fecha_actual - Fecha actual del mes actual
     */
    public function getReporte($tipo_medio, $fecha_inicial, $fecha_actual,$provincia,$grupo,$concesionario,$responsable) {
        $criteria = new CDbCriteria;


         $criteria->select = "distinct(gi.id)";
        $criteria->alias = 'gi';
        $criteria->join = 'INNER JOIN gestion_diaria gd ON gi.id = gd.id_informacion';
        $criteria->join .= ' INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id';
     

        $criteria->condition = "medio = '{$tipo_medio}' AND bdc = 0 AND (gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico') AND gv.orden = 1";
        $criteria->addCondition("(DATE(gi.fecha) BETWEEN '{$fecha_inicial}' AND '{$fecha_actual}')");
      
         if($provincia!=''&&$provincia!=1000)   
            $criteria->addCondition("(provincia_conc= '{$provincia}')");
        if($grupo!=''&&$grupo!=1000){
            if($provincia!=''&&$provincia!=1000) 
                  $dealer_id = $this -> getConcesionarioImplodeProvinciaGrupo($provincia,$grupo);      
             else  
                $dealer_id = $this -> getConcesionarioImplodeGrupo($grupo);
            $criteria->addCondition("(dealer_id IN({$dealer_id}))");
        }
            

        if($concesionario!=''&&$concesionario!=1000)   
            $criteria->addCondition("(concesionario= '{$concesionario}')");

         if($responsable!=''&&$responsable!=1000)   
            $criteria->addCondition("(gi.responsable= '{$responsable}')");

        /* if($grupo!=''&&$grupo!=1000)   
            $criteria->addCondition("(provincia_conc= '{$grupo}')");
        */
       // echo '<pre>';
       // print_r($criteria);
       // echo '</pre>';
    //    die();    
        $posts = GestionInformacion::model()->count($criteria);
        return $posts;
    }
    public function getReporteConsidera($considera, $fecha_inicial, $fecha_actual,$provincia,$grupo,$concesionario,$responsable) {
        

        $criteria = new CDbCriteria;
        
         $criteria->select = "distinct(gi.id)";
        $criteria->alias = 'gi';

        $criteria->join = 'INNER JOIN gestion_diaria gd ON gi.id = gd.id_informacion';
        $criteria->join .= ' INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id';


        $criteria->condition = "considero = '{$considera}' AND bdc = 0 AND (gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico') AND gv.orden = 1";
        $criteria->addCondition("(DATE(gi.fecha) BETWEEN '{$fecha_inicial}' AND '{$fecha_actual}')");
       
        
         if($provincia!=''&&$provincia!=1000)   
            $criteria->addCondition("(provincia_conc= '{$provincia}')");
         if($grupo!=''&&$grupo!=1000){
            if($provincia!=''&&$provincia!=1000) 
                  $dealer_id = $this -> getConcesionarioImplodeProvinciaGrupo($provincia,$grupo);      
             else  
                $dealer_id = $this -> getConcesionarioImplodeGrupo($grupo);
            $criteria->addCondition("(dealer_id IN({$dealer_id}))");
        }


        if($concesionario!=''&&$concesionario!=1000)   
            $criteria->addCondition("(concesionario= '{$concesionario}')");

         if($responsable!=''&&$responsable!=1000)   
            $criteria->addCondition("(gi.responsable= '{$responsable}')");


        $posts = GestionInformacion::model()->count($criteria);
        return $posts;
    }
   
    public function actionTraficoDetalle() {


        $tipo_medio =  isset($_POST['tipo_medio']) ? $_POST["tipo_medio"] : "";

        $fecha_inicial = isset($_POST['fecha_inicial']) ? $_POST["fecha_inicial"] : "";
        $fecha_final = isset($_POST['fecha_final']) ? $_POST["fecha_final"] : "";
       
        $provincia = isset($_POST['provincia']) ? $_POST["provincia"] : "";
        $grupo = isset($_POST['grupo']) ? $_POST["grupo"] : "";
       
       $concesionario=isset($_POST['concesionario']) ? $_POST["concesionario"] : "";
        $responsable=isset($_POST['responsable']) ? $_POST["responsable"] : "";
       
        if($tipo_medio == 'television'){
          
           $group='medio_television';
           
        }
        else if($tipo_medio == 'prensa_escrita'){
            
            $group='medio_prensa';
           
        }
        else if($tipo_medio == 'recomendacion'){
            
            $group='recomendaron';
           
        }
        $where_provincia="";
        if($provincia!=null && $provincia!=''){
            $where_provincia=" provincia_conc={$provincia} AND ";
        }

         $where_grupo="";
        if($grupo!=null && $grupo!=''&& $grupo!=1000){


            if($provincia!=null && $provincia!=''&&$provincia!=1000) 
                  $dealer_id = $this -> getConcesionarioImplodeProvinciaGrupo($provincia,$grupo);      
             else  
                $dealer_id = $this -> getConcesionarioImplodeGrupo($grupo);
            
            $where_grupo=" (dealer_id IN({$dealer_id})) AND ";
        }

        $where_concesionario="";
        if($concesionario!=null && $concesionario!=''&& $concesionario!=1000){
            $where_concesionario=" concesionario={$concesionario} AND ";
        }
        $where_responsable="";
        if($responsable!=null && $responsable!=''&& $responsable!=1000){
            $where_responsable=" gi.responsable={$responsable} AND ";
        }
        $sql = 'SELECT ' . $group .' AS medio, count(gi.id) as cantidad FROM callcenter.gestion_informacion gi 
        INNER JOIN gestion_diaria gd ON gi.id = gd.id_informacion
        INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id
        INNER JOIN modelos m ON m.id_modelos = gv.modelo 
        LEFT JOIN versiones v ON v.id_versiones = gv.version
        INNER JOIN dealers d ON d.id = gi.dealer_id 
        LEFT JOIN usuarios u ON u.id = gi.responsable
        WHERE (DATE(gi.fecha) BETWEEN \''.$fecha_inicial.'\' AND \''.$fecha_final.'\') AND (gd.fuente_contacto = \'showroom\' OR gd.fuente_contacto = \'trafico\') and medio = \''.$tipo_medio.'\' AND '.$where_provincia.$where_grupo.$where_concesionario.$where_responsable.' 
        gi.responsable AND gi.bdc = 0 AND gv.orden = 1 GROUP BY '.$group.' HAVING cantidad>0 ORDER BY cantidad DESC';
        

       // die($sql);
        $det = Yii::app()->db->createCommand($sql)->queryAll();
        $options = array('data' => $det);
        echo json_encode($options);
    }
    
    public function actionTraficoDetalleConsidera() {


        $tipo_considera =  isset($_POST['tipo_considera']) ? $_POST["tipo_considera"] : "";

        $fecha_inicial = isset($_POST['fecha_inicial']) ? $_POST["fecha_inicial"] : "";
        $fecha_final = isset($_POST['fecha_final']) ? $_POST["fecha_final"] : "";
        

         $provincia = isset($_POST['provincia']) ? $_POST["provincia"] : "";
        $grupo = isset($_POST['grupo']) ? $_POST["grupo"] : "";
       
       $concesionario=isset($_POST['concesionario']) ? $_POST["concesionario"] : "";
        $responsable=isset($_POST['responsable']) ? $_POST["responsable"] : "";
        if($tipo_considera == 'recomendacion'){
          
           $group='considero_recomendaron';
           
        }

        $where_provincia="";
        if($provincia!=null && $provincia!=''){
            $where_provincia=" provincia_conc={$provincia} AND ";
        }

         $where_grupo="";
        if($grupo!=null && $grupo!=''&& $grupo!=1000){
           // $where_grupo=" AND dealer_id={$grupo} ";

             if($provincia!=null && $provincia!=''&&$provincia!=1000) 
                  $dealer_id = $this -> getConcesionarioImplodeProvinciaGrupo($provincia,$grupo);      
             else  
                $dealer_id = $this -> getConcesionarioImplodeGrupo($grupo);
            

            $where_grupo=" (dealer_id IN({$dealer_id})) AND ";
        }

        $where_concesionario="";
        if($concesionario!=null && $concesionario!=''&& $concesionario!=1000){
            $where_concesionario=" concesionario={$concesionario} AND ";
        }
        $where_responsable="";
        if($responsable!=null && $responsable!=''&& $responsable!=1000){
            $where_responsable=" gi.responsable={$responsable} AND ";
        }
       
        $sql = 'SELECT ' . $group .' AS medio, count(gi.id) as cantidad FROM callcenter.gestion_informacion gi 

        INNER JOIN gestion_diaria gd ON gi.id = gd.id_informacion
        INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id
        INNER JOIN modelos m ON m.id_modelos = gv.modelo 
        LEFT JOIN versiones v ON v.id_versiones = gv.version
        INNER JOIN dealers d ON d.id = gi.dealer_id 
        LEFT JOIN usuarios u ON u.id = gi.responsable

        WHERE (DATE(gi.fecha) BETWEEN \''.$fecha_inicial.'\' AND \''.$fecha_final.'\') AND (gd.fuente_contacto = \'showroom\' OR gd.fuente_contacto = \'trafico\') and considero = \''.$tipo_considera.'\' AND '.$where_provincia.$where_grupo.$where_concesionario.$where_responsable.' 
          gi.responsable AND gi.bdc = 0 AND gv.orden = 1 GROUP BY '.$group.' HAVING cantidad>0 ORDER BY cantidad DESC';
        
        $det = Yii::app()->db->createCommand($sql)->queryAll();
        $options = array('data' => $det);
        echo json_encode($options);
    }
}



