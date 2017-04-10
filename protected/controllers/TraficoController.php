<?php

class TraficoController extends Controller {

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
                'actions' => array('inicio', 'getAsesores', 'getGrupos', 'graficos', 'getTraficoDiario', 'prueba', 'reportes', 'getNombreGrupo',
                    'getNombreConcesionario','getTitulo','getConcesionariosGrupo','getResponsablesConcecionario'),
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

    public function actionPrueba() {
        $this->render('prueba', array('vartrf' => $vartrf));
    }

    public function actionInicio() {
        date_default_timezone_set('America/Guayaquil');
        $dt = time();
        setlocale(LC_TIME, 'es_ES.UTF-8');
        $vartrf = array();
        $vartrf['area_id'] = (int) Yii::app()->user->getState('area_id');
        $vartrf['grupo_id'] = (int) Yii::app()->user->getState('grupo_id');
        $vartrf['cargo_id'] = (int) Yii::app()->user->getState('cargo_id');
        $vartrf['cargo_adicional'] = (int) Yii::app()->user->getState('cargo_adicional');
        //$varView['cargo_adicional'] = 85;
        $vartrf['id_responsable'] = Yii::app()->user->getId();
        $vartrf['dealer_id'] = $this->getDealerId($vartrf['id_responsable']);
        // SACAR MES ACTUAL
        $vartrf['year_actual'] = date("Y");
        $vartrf['mes_actual'] = date('n');
        //echo $vartrf['mes_actual'];
        $vartrf['dia_inicial'] = '01';
        $vartrf['dia_actual'] = date("d");
        $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['dia_inicial'], $vartrf['dia_actual']);
        $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
        $vartrf['modelos'] = $this->getModelosTrafico(5);
        $vartrf['versiones'] = $this->getModelosTraficoVersion(5);
        $vartrf['id_modelos'] = array();
        foreach ($vartrf['modelos'] as $value) {
            $vartrf['id_modelos'][] = $value['id'];
        }
        $vartrf['trafico_suma_total'] = array();
        $vartrf['proforma_suma_total'] = array();
        $vartrf['testdrive_suma_total'] = array();
        $vartrf['venta_suma_total'] = array();
        $vartrf['tasa_td_total'] = array();
        $vartrf['tasa_cierre_total'] = array();
        $vartrf['trafico_nacional'] = 0;
        $vartrf['testdrive_nacional'] = 0;
        $vartrf['proforma_nacional'] = 0;
        $vartrf['ventas_nacional'] = 0;
        $vartrf['search'] = array();
        $vartrf['search']['fecha'] = false; // busqueda por fecha predeterminada
        $vartrf['dia_inicial'] = '01';
        $vartrf['search']['dia_anterior'] = '01';
        $vartrf['search']['titulo'] = ' Búsqueda por Defecto Categoría: Todos';
        $vartrf['search']['categoria'] = 5;
        $vartrf['categoria'] = 5;
        $vartrf['flag_search'] = 0;
        if (isset($_GET['GestionDiaria'])) {
//            echo '<pre>';
//            print_r($_GET);
//            echo '</pre>';
//            die();
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $fechaActual = date("Y/m/d");


            // BUSQUEDA POR CATEGORIA========================================================================================================
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 1) {
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                    $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['categoria'] = $_GET['GestionDiaria']['categoria'];
                $vartrf['search']['titulo'] = 'Búsqueda por ' . $tit . ' Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                $vartrf['flag_search'] = 1;
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR FECHAS ========================================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0) {
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . ' al ' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Categoría: Autos';
                $vartrf['flag_search'] = 2;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR FECHAS - PROVINCIA ============================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 1 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0) {
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                if($_GET['GestionDiaria']['provincia'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.provincia_conc = ' . $_GET['GestionDiaria']['provincia'];
                }
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Categoría: Todos';
                $vartrf['flag_search'] = 3;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR FECHAS - PROVINCIA - GRUPO ============================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0) {
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Categoría: Todos';
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] == 1000){
                    $vartrf['search']['titulo'] = 'Búsqueda por Fechas0 - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Grupo: Todos, Categoría: Todos';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000){
                    $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                    $dealerList = implode(', ', $array_dealers);
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                if($_GET['GestionDiaria']['provincia'] != 1000 && $_GET['GestionDiaria']['grupo'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.provincia_conc = ' . $_GET['GestionDiaria']['provincia'] . ' AND gi.dealer_id IN(' . $dealerList . ')';
                    $vartrf['search']['titulo'] = 'Búsqueda por Fechas1 - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Categoría: Todos';
                }
                if($_GET['GestionDiaria']['provincia'] != 1000 && $_GET['GestionDiaria']['grupo'] == 1000){
                    $vartrf['search']['where'] = ' AND gi.provincia_conc = ' . $_GET['GestionDiaria']['provincia'] ;
                    $vartrf['search']['titulo'] = 'Búsqueda por Fechas2 - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Grupo: Todos, Categoría: Todos';
                }
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                
                $vartrf['flag_search'] = 4;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR FECHAS - GRUPO
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0) {
                //die('fecha grupo');
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                if($_GET['GestionDiaria']['grupo'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Categoría: Todos';
                $vartrf['flag_search'] = 5;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR GRUPO
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0) {
                //die('fecha grupo');
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                    $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                if($_GET['GestionDiaria']['grupo'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                
                //$vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por ' . $tit . ' Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Categoría: Todos';
                $vartrf['flag_search'] = 6;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR CONCESIONARIO
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0) {
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['grupo'] = TRUE;
                $vartrf['search']['concesionario'] = TRUE;
                $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                if($_GET['GestionDiaria']['concesionario'] == 1000){
                    $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                    $dealerList = implode(', ', $array_dealers);
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                //$vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por '.$tit.' Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getConcesionario($_GET['GestionDiaria']['concesionario']) . ', Categoría: Todos';
                $vartrf['flag_search'] = 7;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR FECHA - CONCESIONARIO
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0) {
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['grupo'] = TRUE;
                $vartrf['search']['concesionario'] = TRUE;
                $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                if($_GET['GestionDiaria']['concesionario'] == 1000){
                    $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                    $dealerList = implode(', ', $array_dealers);
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getConcesionario($_GET['GestionDiaria']['concesionario']) . ', Categoría: Todos';
                $vartrf['flag_search'] = 8;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR FECHAS - RESPONSABLE
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1 && $_GET['categoria'] == 0) {
                //echo 'enter responsable';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['grupo'] = TRUE;
                $vartrf['search']['concesionario'] = TRUE;
                $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                if($_GET['GestionDiaria']['responsable'] ==  10000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: Todos';
                $vartrf['flag_search'] = 9;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR RESPONSABLE
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1 && $_GET['categoria'] == 0) {
                //echo 'enter responsable';
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['grupo'] = TRUE;
                $vartrf['search']['concesionario'] = TRUE;
                
                if($_GET['GestionDiaria']['grupo'] !=  1000 && $_GET['GestionDiaria']['concesionario'] !=  1000 && $_GET['GestionDiaria']['responsable'] !=  10000){
                    $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                }
                if($_GET['GestionDiaria']['grupo'] !=  1000 && $_GET['GestionDiaria']['concesionario'] ==  1000 && $_GET['GestionDiaria']['responsable'] ==  10000){
                    $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                    $dealerList = implode(', ', $array_dealers);
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                if($_GET['GestionDiaria']['grupo'] !=  1000 && $_GET['GestionDiaria']['concesionario'] !=  1000 && $_GET['GestionDiaria']['responsable'] ==  10000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                /*if($_GET['GestionDiaria']['responsable'] ==  10000 && $_GET['GestionDiaria']['concesionario'] ==  1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }*/
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por '.$tit.' Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: Todos';
                $vartrf['flag_search'] = 10;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }


            // BUSQUEDA POR FECHAS - PROVINCIA - GRUPO - CONCESIONARIO - RESPONSABLE ============================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1 && $_GET['categoria'] == 0) {
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                if($_GET['GestionDiaria']['provincia'] != 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] == 1000 && $_GET['GestionDiaria']['responsable'] == 1000){
                    $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                    $dealerList = implode(', ', $array_dealers);
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] == 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable'] == 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }

                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] == 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable']!= 1000){
                    $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                }
                
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: Todos';
                $vartrf['flag_search'] = 11;
                $this->render('inicio', array('vartrf' => $vartrf));
                $flag_search = 3;
                exit();
            }
            // BUSQUEDA POR FECHAS Y CATEGORIA========================================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 1) {
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                    $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . ' al ' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                $vartrf['flag_search'] = 12;
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR FECHAS - PROVINCIA - CATEGORIA ============================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 1 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 1) {
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                if($_GET['GestionDiaria']['provincia'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.provincia_conc = ' . $_GET['GestionDiaria']['provincia'];
                }
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 13;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR FECHAS - PROVINCIA - GRUPO - CATEGORIA ============================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 1) {
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                if($_GET['GestionDiaria']['provincia'] != 1000 && $_GET['GestionDiaria']['grupo'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.provincia_conc = ' . $_GET['GestionDiaria']['provincia'] . ' AND gi.dealer_id IN(' . $dealerList . ')';
                }

                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 14;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR FECHAS - GRUPO - CATEGORIA
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 1) {
                //die('fecha grupo');
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                if($_GET['GestionDiaria']['grupo'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 15;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR CONCESIONARIO - CATEGORIA
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 0 && $_GET['categoria'] == 1) {
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                    $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['concesionario'] = TRUE;
                $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';

                $vartrf['search']['titulo'] = 'Búsqueda por ' . $tit . ' Concesionario: ' . $this->getConcesionario($_GET['GestionDiaria']['concesionario']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 16;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR FECHA - RESPONSABLE - CATEGORIA
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1 && $_GET['categoria'] == 1) {
                //echo 'enter responsable';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['grupo'] = TRUE;
                $vartrf['search']['concesionario'] = TRUE;
                if($_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable'] != 10000){
                    $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                }
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                switch ($vartrf['cargo_id']) {
                    case 69:
                        $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                        break;
                    case 70:
                        $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                        break;
                    case 71:
                        $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                        break;

                    default:
                        break;
                }
                switch ($vartrf['area_id']) {
                    case 4:
                    case 12:
                    case 13:
                    case 14:
                        $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                        break;
                    default:
                        break;
                }
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 17;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR FECHAS - PROVINCIA - GRUPO - CONCESIONARIO - RESPONSABLE - CATEGORIA============================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1 && $_GET['categoria'] == 1) {
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestiodnDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);

                if($_GET['GestionDiaria']['provincia'] != 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable'] != 10000){
                    $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] == 1000 && $_GET['GestionDiaria']['responsable'] == 10000){
                    $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                    $dealerList = implode(', ', $array_dealers);
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable'] == 10000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable'] != 10000){
                    $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                }
                
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 18;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR PROVINCIA - GRUPO - CONCESIONARIO - RESPONSABLE - CATEGORIA============================================================================================
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1 && $_GET['categoria'] == 1) {
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);

                if($_GET['GestionDiaria']['provincia'] != 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable'] != 10000){
                    $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] == 1000 && $_GET['GestionDiaria']['responsable'] == 10000){
                    $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                    $dealerList = implode(', ', $array_dealers);
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable'] == 10000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable'] != 10000){
                    $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                }
                
                $vartrf['search']['titulo'] = 'Búsqueda por '.$tit.'Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 19;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }


            // BUSQUEDA POR PROVINCIA - GRUPO - CONCESIONARIO - CATEGORIA============================================================================================
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 0 && $_GET['categoria'] == 1) {
                //die('enter sin fecha');
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                    $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                
                if($_GET['GestionDiaria']['provincia'] != 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000) {
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000) {
                    $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                    $dealerList = implode(', ', $array_dealers);
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] == 1000 && $_GET['GestionDiaria']['concesionario'] != 1000) {
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                
                $vartrf['search']['titulo'] = 'Búsqueda por ' . $tit . 'Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 20;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR RESPONSABLE - CATEGORIA
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1 && $_GET['categoria'] == 1) {
                //echo 'enter responsable';
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                    $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['grupo'] = TRUE;
                $vartrf['search']['concesionario'] = TRUE;
                if($_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable'] != 1000) {
                    $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                }
                if($_GET['GestionDiaria']['grupo'] == 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable'] != 1000) {
                    $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                    $dealerList = implode(', ', $array_dealers);
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }

                
                switch ($vartrf['cargo_id']) {
                    case 69:
                        $vartrf['search']['titulo'] = 'Búsqueda por defecto - Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                        break;
                    case 70:
                        $vartrf['search']['titulo'] = 'Búsqueda por defecto - Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                        break;
                    case 71:
                        $vartrf['search']['titulo'] = 'Búsqueda por defecto - Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                        break;

                    default:
                        break;
                }
                switch ($vartrf['area_id']) {
                    case 4:
                    case 12:
                    case 13:
                    case 14:
                        $vartrf['search']['titulo'] = 'Búsqueda por defecto - Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                        break;
                    default:
                        break;
                }
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 21;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR CONCESIONARIO - RESPONSABLE - CATEGORIA============================================================================================
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1 && $_GET['categoria'] == 1) {
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                    $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                $vartrf['search']['titulo'] = 'Búsqueda por ' . $tit . ' Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 22;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR CONCESIONARIO - RESPONSABLE ============================================================================================
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1 && $_GET['categoria'] == 0) {
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                    $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                $vartrf['search']['titulo'] = 'Búsqueda por ' . $tit . ' Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']);
                
                $vartrf['flag_search'] = 23;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR FECHAS - CONCESIONARIO - RESPONSABLE ============================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1 && $_GET['categoria'] == 0) {
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: Autos';
                $vartrf['flag_search'] = 24;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR FECHAS - CONCESIONARIO - RESPONSABLE - CATEGORIA============================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1 && $_GET['categoria'] == 1) {
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 25;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR FECHAS - CONCESIONARIO - CATEGORIA============================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 0 && $_GET['categoria'] == 1) {
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 26;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }
            //BUSQUEDA POR CONCESIONARIO Y AÑO
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0 && $_GET['year'] == 1) {
                $vartrf['mes_actual'] = 12;
                $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                $vartrf['search']['concesionario'] = TRUE;
                $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Defecto - Año: ' . $_GET['GestionDiaria']['year'] . ', Concesionario: ' . $this->getConcesionario($_GET['GestionDiaria']['concesionario']) . ', Categoría: Autos';
                $vartrf['flag_search'] = 27;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR FECHAS - CONCESIONARIO ============================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0) {
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['flag_search'] = 28;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }
            //BUSQUEDA POR AÑO
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0 && $_GET['year'] == 1) {
                $vartrf['mes_actual'] = 12;
                $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Defecto - Año: ' . $_GET['GestionDiaria']['year'] . ', Categoría: Autos';
                $vartrf['flag_search'] = 29;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR GRUPO - CONCESIONARIO - RESPONSABLE ============================================================================================
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1 && $_GET['categoria'] == 0) {
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                    $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                $vartrf['search']['titulo'] = 'Búsqueda por ' . $tit . ' Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: Autos';
                $vartrf['flag_search'] = 30;
                $this->render('inicio', array('vartrf' => $vartrf));
                $flag_search = 28;
                exit();
            }
            // BUSQUEDA POR PROVINCIA - CATEGORIA ============================================================================================
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 1 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 1) {
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                    $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                if($_GET['GestionDiaria']['provincia'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.provincia_conc = ' . $_GET['GestionDiaria']['provincia'];
                }
                
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por ' . $tit . ' Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 31;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR PROVINCIA - GRUPO ============================================================================================
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0) {
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                    $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                if($_GET['GestionDiaria']['provincia'] != 1000 && $_GET['GestionDiaria']['grupo'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.provincia_conc = ' . $_GET['GestionDiaria']['provincia'] . ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }

                if($_GET['GestionDiaria']['provincia'] != 1000 && $_GET['GestionDiaria']['grupo'] == 1000){
                    $vartrf['search']['where'] = ' AND gi.provincia_conc = ' . $_GET['GestionDiaria']['provincia'] . ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                
                //$vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por ' . $tit . 'Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['flag_search'] = 32;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR PROVINCIA - GRUPO - CONCESIONARIO============================================================================================
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0) {
                //die('enter sin fecha');
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                    $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                if($_GET['GestionDiaria']['provincia'] != 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000){
                    $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                    $dealerList = implode(', ', $array_dealers);
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] == 1000 && $_GET['GestionDiaria']['concesionario'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                
                $vartrf['search']['titulo'] = 'Búsqueda por ' . $tit . ' Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']);

                $vartrf['flag_search'] = 33;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR FECHAS - PROVINCIA - GRUPO - CONCESIONARIO ============================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0) {
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                    $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                if($_GET['GestionDiaria']['provincia'] != 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000){
                    $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                    $dealerList = implode(', ', $array_dealers);
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] == 1000 && $_GET['GestionDiaria']['concesionario'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Categoría: Autos';
                $vartrf['flag_search'] = 34;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR PROVINCIA - GRUPO - CONCESIONARIO - RESPONSABLE ============================================================================================
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1 && $_GET['categoria'] == 0) {
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                    $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                if($_GET['GestionDiaria']['provincia'] != 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable'] != 10000){
                    $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] == 1000 && $_GET['GestionDiaria']['responsable'] == 10000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] == 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable'] == 10000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                if($_GET['GestionDiaria']['provincia'] != 1000 && $_GET['GestionDiaria']['grupo'] == 1000 && $_GET['GestionDiaria']['concesionario'] == 1000 && $_GET['GestionDiaria']['responsable'] == 10000){
                    $vartrf['search']['where'] = ' AND gi.provincia_conc = ' . $_GET['GestionDiaria']['provincia'];
                }
                
                $vartrf['search']['titulo'] = 'Búsqueda por ' . $tit . ' Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: Autos';
                $vartrf['flag_search'] = 35;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR  GRUPO - CONCESIONARIO - CATEGORIA============================================================================================
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 0 && $_GET['categoria'] == 1) {
                //die('enter sin fecha');
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                    $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                if($_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                if($_GET['GestionDiaria']['grupo'] == 1000 && $_GET['GestionDiaria']['concesionario'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                
                $vartrf['search']['titulo'] = 'Búsqueda por ' . $tit . ' Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 36;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR FECHAS - GRUPO - CONCESIONARIO - CATEGORIA============================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 0 && $_GET['categoria'] == 1) {
                //die('enter sin fecha');
                $tit = '- Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                    $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = '- Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['grupo'] = TRUE;
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                if($_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                if($_GET['GestionDiaria']['grupo'] == 1000 && $_GET['GestionDiaria']['concesionario'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . $tit . ' Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 37;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR PROVINCIA ============================================================================================
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 1 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0) {
                 $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                
                if($_GET['GestionDiaria']['provincia'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.provincia_conc = ' . $_GET['GestionDiaria']['provincia'];
                }
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por por ' . $tit.' Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Categoría: Todos';
                $vartrf['flag_search'] = 38;
                $this->render('inicio', array('vartrf' => $vartrf));
                exit();
            }
            //$posts = $this->searchSql($cargo_id, $grupo_id, $id_responsable, $fechaPk, 'seg', '');
        }
        $this->render('inicio', array('vartrf' => $vartrf));
    }

    /**
     * 
     */
    public function actionGetTraficoDiario() {
        //die('enter get trafico diario');
        $versiones = isset($_POST["versiones"]) ? $_POST["versiones"] : "";
        $dia_inicial = isset($_POST["dia_inicial"]) ? $_POST["dia_inicial"] : "";
        $dia_actual = isset($_POST["dia_actual"]) ? $_POST["dia_actual"] : "";
        $year = isset($_POST["year"]) ? $_POST["year"] : "";
        $mes = isset($_POST["mes"]) ? $_POST["mes"] : "";
        $nombre_modelo = isset($_POST["nombre_modelo"]) ? $_POST["nombre_modelo"] : "";
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        $i = isset($_POST["i"]) ? $_POST["i"] : "";
        $where = isset($_POST["where"]) ? $_POST["where"] : "";
        $categoria = isset($_POST["categoria"]) ? $_POST["categoria"] : "";
        $modelos = $this->getModelosTrafico($categoria);
        $versiones = $this->getModelosTraficoVersion($categoria);
        $flag = 0;
        foreach ($modelos as $value) {
            switch ($value['id']) {
                case 1:
                    $data_picanto = $this->getDataTableDiario($dia_inicial, $dia_actual, $versiones[$flag], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 2:
                    $data_carens = $this->getDataTableDiario($dia_inicial, $dia_actual, $versiones[$flag], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 3:
                    $data_cerato_forte = $this->getDataTableDiario($dia_inicial, $dia_actual, $versiones[$flag], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 4:
                    $data_cerato_koup = $this->getDataTableDiario($dia_inicial, $dia_actual, $versiones[$flag], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 5:
                    $data_cerato_r = $this->getDataTableDiario($dia_inicial, $dia_actual, $versiones[$flag], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 6:
                    $data_grand_carnival = $this->getDataTableDiario($dia_inicial, $dia_actual, $versiones[$flag], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                case 7:
                    $data_k3000 = $this->getDataTableDiario($dia_inicial, $dia_actual, $versiones[$flag], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 8:
                    $data_optima = $this->getDataTableDiario($dia_inicial, $dia_actual, $versiones[$flag], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 10:
                    $data_rio_sedan = $this->getDataTableDiario($dia_inicial, $dia_actual, $versiones[$flag], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 11:
                    $data_rio_hb = $this->getDataTableDiario($dia_inicial, $dia_actual, $versiones[$flag], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 12:
                    $data_soul_ev = $this->getDataTableDiario($dia_inicial, $dia_actual, $versiones[$flag], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 13:
                    $data_soul_r = $this->getDataTableDiario($dia_inicial, $dia_actual, $versiones[$flag], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 14:
                    $data_sportage_active = $this->getDataTableDiario($dia_inicial, $dia_actual, $versiones[$flag], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 15:
                    $data_sportage_r = $this->getDataTableDiario($dia_inicial, $dia_actual, $versiones[$flag], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 16:
                    $data_sportage_gt = $this->getDataTableDiario($dia_inicial, $dia_actual, $versiones[$flag], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 17:
                    $data_sportage_r_ckd = $this->getDataTableDiario($dia_inicial, $dia_actual, $versiones[$flag], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 19:
                    $data_sportage_xline = $this->getDataTableDiario($dia_inicial, $dia_actual, $versiones[$flag], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;    
                default:
                    break;
            }
            $flag++;
        }
        $options = array('data_picanto' => $data_picanto, 'data_carens' => $data_carens, 'data_cerato_forte' => $data_cerato_forte,
            'data_cerato_koup' => $data_cerato_koup, 'data_cerato_r' => $data_cerato_r, 'data_grand_carnival' => $data_grand_carnival,
            'data_k3000' => $data_k3000, 'data_optima' => $data_optima, 'data_rio_sedan' => $data_rio_sedan, 'data_rio_hb' => $data_rio_hb,
            'data_soul_ev' => $data_soul_ev, 'data_soul_r' => $data_soul_r, 'data_sportage_active' => $data_sportage_active,
            'data_sportage_r' => $data_sportage_r, 'data_sportage_gt' => $data_sportage_gt, 'data_sportage_r_ckd' => $data_sportage_r_ckd, 'data_sportage_xline' => $data_sportage_xline);
        echo json_encode($options);
        /* for ($i = 1; $i <= $dia; $i++) {
          $d = $i;
          if ($i < 10) {
          $d = '0' . $i;
          }
          $data .= '<td>' . $d . '-' . $fmes . '</td>';
          }
          $data .= "</tr>";
          for ($i = 1; $i <= 7; $i++) {

          $data .= "<tr class='odd-desc'><td>" . $names[$i - 1] . "</td>";
          for ($j = 1; $j <= $dia; $j++) {
          $d = $j;
          if ($j < 10) {
          $d = '0' . $j;
          }
          switch ($i) {
          case 1:
          $datatr = $this->getDataDia($versiones, $year, $mes, $d);
          $trafico[] = $datatr;
          $datata = $datata + $datatr;
          $data .= "<td>" . $datata . "</td>";
          break;
          case 2:
          //$datatr = $this->getDataDia($versiones, $year, $mes, $d);
          //$trafico[] = $datatr;
          $data .= "<td>" . $trafico[$j - 1] . "</td>";
          break;
          case 3:
          $datapf = $this->getDataProforma($versiones, $year, $mes, $d);
          $data .= "<td>" . $datapf . "</td>";
          break;
          case 4:
          $datatd = $this->getDataTestDrive($versiones, $year, $mes, $d);
          $testdrive[] = $datatd;
          $data .= "<td>" . $datatd . "</td>";
          break;
          case 5:
          $datavt = $this->getDataVentas($versiones, $year, $mes, $d);
          $ventas[] = $datavt;
          $data .= "<td>" . $datavt . "</td>";
          break;
          case 6:
          $data .= "<td>" . $this->getTasaTD($testdrive[$j - 1], $trafico[$j - 1]) . "</td>";
          break;
          case 7:
          $data .= "<td>" . $this->getTasaCierre($ventas[$j - 1], $trafico[$j - 1]) . "</td>";
          break;
          default:
          break;
          }
          }
          $data .= "</tr>";
          }
          $data .= "<tr><td>&nbsp;</td></tr>";
          $data .= "</table>";
          echo $data; */
    }

    /**
     * 
     * @param type $dia_inicial
     * @param type $dia_actual
     * @param type $versiones
     * @param type $year
     * @param type $mes
     * @param type $nombre_modelo
     * @param type $i
     * @param type $where
     * @return string
     */
    private function getDataTableDiario($dia_inicial, $dia_actual, $versiones, $year, $mes, $nombre_modelo, $i, $where, $id_modelo) {
        $area_id = (int) Yii::app()->user->getState('area_id');
        $grupo_id = (int) Yii::app()->user->getState('grupo_id');
        $cargo_id = (int) Yii::app()->user->getState('cargo_id');
        $cargo_adicional = (int) Yii::app()->user->getState('cargo_adicional');
        $id_responsable = Yii::app()->user->getId();
        $dealer_id = $this->getDealerId($id_responsable);
        $dia_inicial = (int) $dia_inicial;
        $data = "";
        $data_traf_acum = "";
        $data_trafico = "";
        $data_proforma = "";
        $data_test_drive = "";
        $data_ventas = "";
        $colspan = $dia_actual + 1;
        //$datatr; $datapf; $datatd; $datavt;
        $trafico_diario = array();
        $testdrive = array();
        $ventas = array();
        $trafico_acumulado = array();
        $search_array = array();
        $search_array['fecha'] = FALSE;
        $search_array['where'] = $where;
        if ($dia_inicial != '01') {
            $search_array['fecha'] = TRUE;
            $search_array['dia_anterior'] = $dia_inicial;
            $search_array['dia_actual'] = $dia_actual;
        }

        $fmes = $this->getNombreMes($mes);

        $button = '<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closemodal(\'' . $mes . '\',\'' . $i . '\');"><span aria-hidden="true">×</span></button>';
        //$button = '';
        $data .= "<table class='det_" . $mes . "'>";
        $data .= "<tr class='odd-mh' style='font-size:13px;'><td colspan='" . $colspan . "' class='cir-{$id_modelo}'>Detalle de: " . $nombre_modelo . ", Fecha: Desde el " . $dia_inicial . "-" . $fmes . "-" . $year . " al " . $dia_actual . "-" . $fmes . "-" . $year . " " . $button . "</td></tr>";
        $data .= "<tr class='odd-mt'><td>Funnel</td>";
        $names = array('Tráfico Acumulado', 'Tráfico', 'Proforma', 'Test Drive', 'Ventas', 'Tasa de Test Drive', 'Tasa de Cierre');
        // ARMAR MESES Y DIAS 
        for ($i = $dia_inicial; $i <= $dia_actual; $i++) {
            $d = $i;
            if ($i < 10) {
                $d = '0' . $i;
            }
            $data .= '<td>' . $d . '-' . $fmes . '</td>';
        }
        $data .= "</tr>";

        // SACAR TITULOS DE LAS FILAS array $names
        for ($i = 1; $i <= 7; $i++) {

            $data .= "<tr class='odd-desc'><td>" . $names[$i - 1] . "</td>";
            //echo 'dia inicial: '.$dia_inicial.', dia actual; '.$dia_actual;
            for ($j = $dia_inicial; $j <= $dia_actual; $j++) {
                $d = $j;
                if ($j < 10) {
                    $d = '0' . $j;
                }
                switch ($i) {
                    case 1:
                        //$datatr = $this->getDataDia($versiones, $year, $mes, $d);
                        $datatr = $this->getTraficoVersion($mes, $versiones, $year, $d, 0, $search_array, $cargo_id, $dealer_id, $id_responsable);
                        $trafico_diario[] = $datatr;
                        $datata = $datata + $datatr;
                        $data .= "<td>" . $datata . "</td>";
                        break;
                    case 2:
                        //$datatr = $this->getDataDia($versiones, $year, $mes, $d);
                        //$trafico[] = $datatr;
                        $data .= "<td>" . $trafico_diario[$j - $dia_inicial] . "</td>";
                        break;
                    case 3:
                        //$datapf = $this->getDataProforma($versiones, $year, $mes, $d);
                        $datapf = $this->getProformaVersion($mes, $versiones, $year, $d, 0, $search_array, $cargo_id, $dealer_id, $id_responsable);
                        $data .= "<td>" . $datapf . "</td>";
                        break;
                    case 4:
                        //$datatd = $this->getDataTestDrive($versiones, $year, $mes, $d);
                        $datatd = $this->getTestDriveVersion($mes, $versiones, $year, $d, 0, $search_array, $cargo_id, $dealer_id, $id_responsable);
                        $testdrive[] = $datatd;
                        $data .= "<td>" . $datatd . "</td>";
                        break;
                    case 5:
                        //$datavt = $this->getDataVentas($versioneds, $year, $mes, $d);
                        $datavt = $this->getVentasVersion($mes, $versiones, $year, $d, 0, $search_array, $cargo_id, $dealer_id, $id_responsable);
                        $ventas[] = $datavt;
                        $data .= "<td>" . $datavt . "</td>";
                        break;
                    case 6:
                        $data .= "<td>" . $this->getTasaTD($testdrive[$j - 1], $trafico_diario[$j - 1]) . "</td>";
                        break;
                    case 7:
                        $data .= "<td>" . $this->getTasaCierre($ventas[$j - 1], $trafico_diario[$j - 1]) . "</td>";
                        break;
                    default:
                        break;
                }
            }
            $data .= "</tr>";
        }
        $data .= "<tr><td>&nbsp;</td></tr>";
        $data .= "</table>";
        return $data;
    }

    private function getDataDia($versiones, $year, $mes, $d) {
        $criteria = new CDbCriteria;
        //$criteria->select = "*";
        $criteria->alias = 'gi';
        $criteria->join = "LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id ";
        $criteria->join .= "LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id ";
        $criteria->condition = "gi.bdc = 1 OR gi.bdc = 0";
        $criteria->addCondition("DATE(gi.fecha) = '" . $year . "-" . $mes . "-" . $d . "' ");
        //$criteria->addCondition("DATE(gi.fecha) BETWEEN '2016-05-01' AND '2016-05-15' ");
        $criteria->addCondition("gv.version IN (" . $versiones . ")");
        $criteria->addCondition("gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico'");
        $count = GestionInformacion::model()->count($criteria);
        return $count;
    }

    private function getDataProforma($versiones, $year, $mes, $d) {
        //echo "DATE(gi.fecha) = '" . $year . "-" . $mes . "-" . $d . "' <br />";
        $criteria = new CDbCriteria;
        $criteria->alias = 'gf';
        $criteria->join = "INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion ";
        $criteria->join .= "INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id ";
        $criteria->join .= "INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id";
        $criteria->condition = "gi.bdc = 1 OR gi.bdc = 0";
        $criteria->addCondition("DATE(gf.fecha) = '" . $year . "-" . $mes . "-" . $d . "' ");
        $criteria->addCondition("gv.version IN (" . $versiones . ")");
        $criteria->addCondition("gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico'");
        $count = GestionFinanciamiento::model()->count($criteria);
        return $count;
    }

    private function getDataTestDrive($versiones, $year, $mes, $d) {
        $criteria = new CDbCriteria;
        $criteria->alias = 'gt';
        $criteria->join = "INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion ";
        $criteria->join .= "INNER JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo ";
        $criteria->join .= "INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ";
        $criteria->condition = "gi.bdc = 1 OR gi.bdc = 0";
        $criteria->addCondition("gt.test_drive = 1 AND gt.order = 1");
        $criteria->addCondition("DATE(gt.fecha) = '" . $year . "-" . $mes . "-" . $d . "' ");
        //$criteria->addCondition("DATE(gi.fecha) BETWEEN '2016-05-01' AND '2016-05-15' ");
        $criteria->addCondition("gv.version IN (" . $versiones . ")");
        $criteria->addCondition("gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico'");
        $count = GestionTestDrive::model()->count($criteria);
        return $count;
    }

    private function getDataVentas($versiones, $year, $mes, $d) {
        $criteria = new CDbCriteria;
        $criteria->select = "*";
        $criteria->alias = 'gf';
        $criteria->join = "INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion ";
        $criteria->join .= "INNER JOIN gestion_vehiculo gv ON gv.id_informacion  = gf.id_informacion ";
        $criteria->join .= "INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ";
        $criteria->condition = "gi.bdc = 1 OR gi.bdc = 0";
        $criteria->addCondition("gd.cierre = 1 AND gf.status = 'ACTIVO'");
        $criteria->addCondition("DATE(gf.fecha) = '" . $year . "-" . $mes . "-" . $d . "' ");
        $criteria->addCondition("gv.version IN (" . $versiones . ")");
        $criteria->addCondition("gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico'");
        $count = GestionFactura::model()->count($criteria);
        return $count;
    }

    public function getNombreMes($mes_actual) {
        $fmes = '';
        switch ($mes_actual) {
            case 1:
                $fmes = 'ene';
                break;
            case 2:
                $fmes = 'feb';
                break;
            case 3:
                $fmes = 'mar';
                break;
            case 4:
                $fmes = 'abr';
                break;
            case 5:
                $fmes = 'may';
                break;
            case 6:
                $fmes = 'jun';
                break;
            case 7:
                $fmes = 'jul';
                break;
            case 8:
                $fmes = 'ago';
                break;
            case 9:
                $fmes = 'sep';
                break;
            case 10:
                $fmes = 'oct';
                break;
            case 11:
                $fmes = 'nov';
                break;
            case 12:
                $fmes = 'dic';
                break;
            default:
                break;
        }
        return $fmes;
    }

    /**
     * 
     */
    public function actionGetGrupos() {
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        $cargo_id = isset($_POST["cargo_id"]) ? $_POST["cargo_id"] : "";
        $area_id = isset($_POST["area_id"]) ? $_POST["area_id"] : "";
        $grupo_id = isset($_POST["grupo_id"]) ? $_POST["grupo_id"] : "";
        if ($id == 1000) {
            switch ($cargo_id) {
                case 69: # GERENTE COMERCIAL
                    $res = GrConcesionarios::model()->findAll(array('condition' => "id_grupo = {$grupo_id}"));
                    $data = '<option value="">--Seleccione Concesionario--</option><option value="1000">Todos</option>';
                    if (count($res) > 0) {
                        foreach ($res as $value) {
                            $data .= '<option value="' . $value['dealer_id'] . '">' . $value['nombre'] . '</option>';
                        }
                    }
                    break;
                case 61: # AEKIA JEFE DE RED
                        $data = '<option value="">--Seleccione Grupo--</option>
                            <option value="1000">Todos</option>
                            <option value="6">AUTHESA</option>
                            <option value="2">GRUPO ASIAUTO</option>
                            <option value="5">GRUPO EMPROMOTOR</option>
                            <option value="3">GRUPO KMOTOR</option>
                            <option value="8">GRUPO MERQUIAUTO</option>
                            <option value="9">GRUPO MOTRICENTRO</option>
                            <option value="4">IOKARS</option>';
                        break;    
                
                default:

                    break;
            }
            
        } else {
            // GERENTE COMERCIAL - EJEMPLO ASIAUTO
            if ($cargo_id == 69) {
                $res = GrConcesionarios::model()->findAll(array('condition' => "provincia = {$id}"));
                $data = '<option value="">--Seleccione Concesionario--</option>';
                if (count($res) > 0) {
                    foreach ($res as $value) {
                        $data .= '<option value="' . $value['dealer_id'] . '">' . $value['nombre'] . '</option>';
                    }
                }
            // USUARIO AEKIA GERENTE
            } else {
                $res = GrGrupo::model()->findAll(array('condition' => "FIND_IN_SET('" . $id . "',id_provincias) > 0"));
                $data = '<option value="">--Seleccione Grupo--</option>';
                switch(count($res)){
                    case 1:
                        foreach ($res as $value) {
                            $data .= '<option value="' . $value['id'] . '">' . $value['nombre_grupo'] . '</option>';
                        }
                        break;
                    case 2:
                        $data .= '<option value="1000">Todos</option>';
                        foreach ($res as $value) {
                            $data .= '<option value="' . $value['id'] . '">' . $value['nombre_grupo'] . '</option>';
                        }
                        break;    
                }
                //if (count($res) > 0) {
                //    foreach ($res as $value) {
                //        $data .= '<option value="' . $value['id'] . '">' . $value['nombre_grupo'] . '</option>';
                //    }
                //}else if (count($res) > 1) {
                //    $data .= '<option value="1000">Todos</option>';
                //    foreach ($res as $value) {
                //        $data .= '<option value="' . $value['id'] . '">' . $value['nombre_grupo'] . '</option>';
                //    }
                //}
            }
        }
        $options = array('data' => $data);
        echo json_encode($options);
    }

    public function actionGetAsesores() {
        $dealer_id = isset($_POST["dealer_id"]) ? $_POST["dealer_id"] : "";
        $tipo = isset($_POST["tipo"]) ? $_POST["tipo"] : "";
        $cargo_id = (int) Yii::app()->user->getState('cargo_id');
        $area_id = (int) Yii::app()->user->getState('area_id');
        $con = Yii::app()->db;
        switch ($tipo) {
            case 'exo':
                $sql = "SELECT * FROM usuarios WHERE dealers_id = {$dealer_id} AND cargo_id IN (75) AND estado = 'ACTIVO' ORDER BY nombres ASC";
                break;
            case 'showroom':
            case 'prospeccion':
            case 'exhibicion':
                $sql = "SELECT * FROM usuarios WHERE dealers_id = {$dealer_id} AND cargo_id IN (71,70)  AND estado = 'ACTIVO' ORDER BY nombres ASC";
                break;
            case 'web':
                //$sql = "SELECT * FROM usuarios WHERE dealers_id = {$dealer_id} AND cargo_id IN (85,86) ORDER BY nombres ASC";
                $sql = "SELECT u.* FROM usuarios u
                        INNER JOIN grupoconcesionariousuario gr ON gr.usuario_id = u.id
                        WHERE (cargo_id IN (85,86) OR cargo_adicional IN (86)) AND gr.concesionario_id = {$dealer_id}  AND estado = 'ACTIVO' ORDER BY nombres ASC";
                break;

            default:
                $sql = "SELECT * FROM usuarios WHERE dealers_id = {$dealer_id} AND cargo_id IN (71,70)  AND estado = 'ACTIVO' ORDER BY nombres ASC";
                break;
        }
        

        /* else{
          $sql = "SELECT * FROM usuarios WHERE dealers_id = {$dealer_id} AND cargo_id = 71 ORDER BY nombres ASC";
          } */

        //die($sql);
        $requestr1 = $con->createCommand($sql);
        $requestr1 = $requestr1->queryAll();
        $data = '<option value="">--Seleccione Asesor--</option><option value="10000">Todos</option>';
        //$data .= '<option value="all">Todos</option>';
        foreach ($requestr1 as $value) {
            $data .= '<option value="' . $value['id'] . '">';
            $data .= strtoupper($this->getResponsableNombres($value['id']));
            $data .= '</option>';
        }

        echo $data;
    }

    private function convertJSONtoArray($data, $level) {  // level - уровень вложенности чтобы табуляцию писать правильную
        foreach ($data as $key1 => $value1) {
            if (is_array($value1)) {
                echo str_repeat("\t", $level) . '["' . $key1 . '"] => array (' . "\n";
                convertJSONtoArray($value1, $level + 1);
                echo str_repeat("\t", $level) . ")," . "\n";
            } else {
                if (( $value1 == 'true' ) or ( $value1 == 'false' ) or ( is_numeric($value1) )) {  // if numeris or boolean we dont'add quotes
                    echo str_repeat("\t", $level) . '["' . $key1 . '"] => ' . $value1 . ',' . "\n";
                } else {
                    echo str_repeat("\t", $level) . '["' . $key1 . '"] => "' . $value1 . '",' . "\n";
                }
            }
        }
    }

    /**
     * Function que devuelve array con datos de busqueda para Trafico, Showroom, Web o Exhibicion
     * @param string $fecha - Fecha o rango de fechas de busqueda
     * @param int $grupo - Grupo de Concesionarios
     * @param int $concesionario - Concesionario 
     * @param int $responsable - Responsable 
     * @param string $fuente_contacto - Fuente de contacto, showroom, prospeccion, web, exhibicion
     * @param int $tipo_reporte - Tipo de Reporte: Trafico, Proforma, TestDrive, Ventas
     * return array 
     */
    public function getReporteTrafico($fecha, $grupo, $concesionario, $responsable, $fuente_contacto, $tipo_reporte) {
        $data = array();
        switch ($fuente_contacto) {
            case 'showroom':
                $fuente = " AND (gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico')";
                $bdc = " AND gi.bdc = 0";
                break;
            case 'web':
                $fuente = " AND (gd.fuente_contacto = 'web')";
                $bdc = " AND gi.bdc = 1";
                break;
            case 'prospeccion':
                $fuente = " AND (gd.fuente_contacto = 'prospeccion' OR gd.fuente_contacto_historial = 'prospeccion')";
                $bdc = " ";
                break;
            case 'exhibicion':
                $fuente = " AND (gd.fuente_contacto = 'exhibicion' OR gd.fuente_contacto = 'exhibicion quierounkia' OR gd.fuente_contacto = 'exhibicion quierounkiatd')";
                $bdc = " AND gi.bdc = 0";
                break;
            default:
                break;
        }
        switch ($tipo_reporte) {
            case 1: // TRAFICO
                $from = " FROM gestion_informacion gi";
                $select_ini = "SELECT d.`name`, gi.id, ";
                $select_fin = " gi.fecha, gv.version, u.nombres AS nombre_responsable, u.apellido AS apellido_responsable, gi.medio as pregunta, gi.recomendaron AS opcion_recomendacion, gi.medio_prensa, gi.medio_television";
                $inner = " INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id 
INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id
INNER JOIN modelos m ON m.id_modelos = gv.modelo 
LEFT JOIN versiones v ON v.id_versiones = gv.version
INNER JOIN dealers d ON d.id = gi.dealer_id 
LEFT JOIN usuarios u ON u.id = gi.responsable";
                $date = "gi";
                $and = "";
                $group_order = " GROUP BY gi.id";
                $titulo_reporte = 'Reporte Tráfico desde el ' . $_GET['GestionDiaria']['fecha'] . ' - ' . $_GET['GestionDiaria']['fuente_contacto'];
                break;
            case 2: // PROFORMAS
                $from = " FROM gestion_financiamiento gf";
                $select_ini = "SELECT d.`name`, gv.id, ";
                $select_fin = " gf.fecha, gv.version";
                $inner = " INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion 
INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id
INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id 
INNER JOIN dealers d ON d.id = gi.dealer_id 
INNER JOIN modelos m ON m.id_modelos = gv.modelo 
LEFT JOIN versiones v ON v.id_versiones = gv.version";
                $date = "gf";
                $and = "";
                $group_order = " GROUP BY gf.id ORDER BY gf.fecha";
                $titulo_reporte = 'Reporte Proformas desde el ' . $_GET['GestionDiaria']['fecha'] . ' - ' . $_GET['GestionDiaria']['fuente_contacto'];
                break;
            case 3: // TESTDRIVE
                $from = " FROM gestion_test_drive gt";
                $select_ini = "SELECT d.`name`, gi.id, ";
                $select_fin = " gt.fecha, gt.test_drive, gv.version";
                $inner = " INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion 
LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id 
INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id 
INNER JOIN dealers d ON d.id = gi.dealer_id 
INNER JOIN modelos m ON m.id_modelos = gv.modelo 
LEFT JOIN versiones v ON v.id_versiones = gv.version";
                $date = "gt";
                $and = " AND gt.test_drive = 1 AND gt.`order` = 1";
                $group_order = " GROUP BY gt.id_vehiculo";
                $titulo_reporte = 'Reporte TestDrive desde el ' . $_GET['GestionDiaria']['fecha'] . ' - ' . $_GET['GestionDiaria']['fuente_contacto'];
                break;
            case 4: // VENTAS
                $from = " FROM gestion_factura gf";
                $select_ini = "SELECT DISTINCT(gf.id_vehiculo),d.`name`, gi.id, ";
                $select_fin = " gf.fecha, gv.version, gf.`status`, gd.cierre, u.nombres AS nombre_responsable, u.apellido AS apellido_responsable";
                $inner = " INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion 
INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id 
INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id 
INNER JOIN dealers d ON d.id = gi.dealer_id 
INNER JOIN modelos m ON m.id_modelos = gv.modelo 
LEFT JOIN versiones v ON v.id_versiones = gv.version 
INNER JOIN usuarios u ON u.id = gi.responsable";
                $date = "gf";
                $and = " AND gd.cierre = 1 AND gf.status = 'ACTIVO'";
                $group_order = " GROUP BY gf.id_vehiculo";
                $titulo_reporte = 'Reporte Ventas desde el ' . $_GET['GestionDiaria']['fecha'] . ' - ' . $_GET['GestionDiaria']['fuente_contacto'];
                break;

            default:
                break;
        }
        $grupo_sql = '';
        $concesionario = '';
        $responsable = '';
        $titulo_rep = '';
        
        if($_GET['grupo'] == 1 && $_GET['concesionario'] == 0){
            $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
            $dealerList = implode(', ', $array_dealers);
            $grupo_sql = " AND gi.dealer_id IN ({$dealerList})";
            $titulo_rep = ", GRUPO: ".$this->getNombreGrupo($_GET['GestionDiaria']['grupo']);
        }
        if($_GET['concesionario'] == 1){
            $concesionario = " AND gi.dealer_id = {$_GET['GestionDiaria']['concesionario']}";
            $titulo_rep = ", GRUPO: ".$this->getNombreGrupo($_GET['GestionDiaria']['grupo']).", CONCESIONARIO: ".$this->getConcesionario($_GET['GestionDiaria']['concesionario']);
        }
        
        if($_GET['responsable'] == 1){
            $responsable = " AND gi.responsable = {$_GET['GestionDiaria']['responsable']}";
            $titulo_rep = ", CONCESIONARIO: ".$this->getConcesionario($_GET['GestionDiaria']['concesionario']).", RESPONSABLE: ".$this->getResponsableNombres($_GET['GestionDiaria']['responsable']);
        }
        
        $params = explode('-', $fecha);
        $params1 = trim($params[0]);
        $params2 = trim($params[1]);
        $sql = $select_ini;
        $sql .= " gi.nombres, gi.apellidos, gi.cedula, gi.ruc, gi.pasaporte, gi.email, gi.celular, gi.telefono_casa, gi.direccion, gi.bdc, m.nombre_modelo, v.nombre_version, gd.fuente_contacto, ";
        $sql .= $select_fin;
        $sql .= $from;
        $sql .= $inner;
        $sql .= " WHERE (DATE(".$date.".fecha) BETWEEN '{$params1}' AND '{$params2}') ";
        $sql .= $grupo_sql;
        $sql .= $concesionario;
        $sql .= $responsable;
        $sql .= $fuente;
        $sql .= $bdc;
        $sql .= $and;
        $sql .= $group_order;
        //die('sql '.$sql);
        $post = Yii::app()->db->createCommand($sql)->queryAll();
        $data['posts'] = $post;
        $data['titulo_reporte'] = $titulo_reporte.$titulo_rep;
        return $data;

//        $post = GestionInformacion::model()->findAll($criteria);
    }

    public function actionGraficos() {
        date_default_timezone_set('America/Guayaquil');
        $dt = time();
        setlocale(LC_TIME, 'es_ES.UTF-8');
        $vartrf = array();
        $vartrf['area_id'] = (int) Yii::app()->user->getState('area_id');
        $vartrf['grupo_id'] = (int) Yii::app()->user->getState('grupo_id');
        $vartrf['cargo_id'] = (int) Yii::app()->user->getState('cargo_id');
        $vartrf['cargo_adicional'] = (int) Yii::app()->user->getState('cargo_adicional');
        //$varView['cargo_adicional'] = 85;
        $vartrf['id_responsable'] = Yii::app()->user->getId();
        $vartrf['dealer_id'] = $this->getDealerId($vartrf['id_responsable']);
        // SACAR MES ACTUAL
        $vartrf['year_actual'] = date("Y");
        $vartrf['mes_actual'] = date('n');
        if ($vartrf['mes_actual'] < 4) { // menor que marzo traer 12 meses anteriores con año 2016 y 2017
            $vartrf['fechas_activas'] = $this->getFechasActivas($vartrf['mes_actual']);
        }
        $vartrf['mes_anterior'] = strftime("%m", strtotime('-1 month', $dt));
        $vartrf['mes_inicial'] = 0; // MES INICIAL MES DE ENERO PARA REPORTES DEL 2017
        //echo $vartrf['mes_actual'];
        $vartrf['dia_inicial'] = '01';
        $vartrf['dia_actual'] = date("d");
        $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['dia_inicial'], $vartrf['dia_actual']);
        $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
        $vartrf['modelos'] = $this->getModelosTrafico(5);
        $vartrf['versiones'] = $this->getModelosTraficoVersion(5);
        $vartrf['id_modelos'] = array();
        foreach ($vartrf['modelos'] as $value) {
            $vartrf['id_modelos'][] = $value['id'];
        }
        $vartrf['trafico_suma_total'] = array();
        $vartrf['proforma_suma_total'] = array();
        $vartrf['testdrive_suma_total'] = array();
        $vartrf['venta_suma_total'] = array();
        $vartrf['tasa_td_total'] = array();
        $vartrf['tasa_cierre_total'] = array();
        $vartrf['trafico_nacional'] = 0;
        $vartrf['testdrive_nacional'] = 0;
        $vartrf['proforma_nacional'] = 0;
        $vartrf['ventas_nacional'] = 0;
        $vartrf['search'] = array();
        $vartrf['search']['fecha'] = false; // busqueda por fecha predeterminada
        $vartrf['dia_inicial'] = '01';
        $vartrf['search']['dia_anterior'] = '01';
        $vartrf['search']['titulo'] = ' Búsqueda General por Defecto';
        $vartrf['search']['categoria'] = 5;
        $vartrf['categoria'] = 5;
        $vartrf['flag_search'] = 0;
        if (isset($_GET['GestionDiaria'])) {

//            echo '<pre>';
//            print_r($_GET);
//            echo '</pre>';
//            die();
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $fechaActual = date("Y/m/d");


            // BUSQUEDA POR CATEGORIA========================================================================================================
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 1) {
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                    $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['categoria'] = $_GET['GestionDiaria']['categoria'];
                $vartrf['search']['titulo'] = 'Búsqueda por ' . $tit . ' Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                $vartrf['flag_search'] = 1;
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR FECHAS ========================================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0) {
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . ' al ' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Categoría: Autos';
                $vartrf['flag_search'] = 2;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR FECHAS - PROVINCIA ============================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 1 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0) {
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                if($_GET['GestionDiaria']['provincia'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.provincia_conc = ' . $_GET['GestionDiaria']['provincia'];
                }
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Categoría: Todos';
                $vartrf['flag_search'] = 3;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR FECHAS - PROVINCIA - GRUPO ============================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0) {
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Categoría: Todos';
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] == 1000){
                    $vartrf['search']['titulo'] = 'Búsqueda por Fechas0 - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Grupo: Todos, Categoría: Todos';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000){
                    $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                    $dealerList = implode(', ', $array_dealers);
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                if($_GET['GestionDiaria']['provincia'] != 1000 && $_GET['GestionDiaria']['grupo'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.provincia_conc = ' . $_GET['GestionDiaria']['provincia'] . ' AND gi.dealer_id IN(' . $dealerList . ')';
                    $vartrf['search']['titulo'] = 'Búsqueda por Fechas1 - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Categoría: Todos';
                }
                if($_GET['GestionDiaria']['provincia'] != 1000 && $_GET['GestionDiaria']['grupo'] == 1000){
                    $vartrf['search']['where'] = ' AND gi.provincia_conc = ' . $_GET['GestionDiaria']['provincia'] ;
                    $vartrf['search']['titulo'] = 'Búsqueda por Fechas2 - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Grupo: Todos, Categoría: Todos';
                }
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                
                $vartrf['flag_search'] = 4;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR FECHAS - GRUPO
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0) {
                //die('fecha grupo');
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                if($_GET['GestionDiaria']['grupo'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Categoría: Todos';
                $vartrf['flag_search'] = 5;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR GRUPO
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0) {
                //die('fecha grupo');
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                    $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                if($_GET['GestionDiaria']['grupo'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                
                //$vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por ' . $tit . ' Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Categoría: Todos';
                $vartrf['flag_search'] = 6;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR CONCESIONARIO
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0) {
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['grupo'] = TRUE;
                $vartrf['search']['concesionario'] = TRUE;
                $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                if($_GET['GestionDiaria']['concesionario'] == 1000){
                    $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                    $dealerList = implode(', ', $array_dealers);
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                //$vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por '.$tit.' Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getConcesionario($_GET['GestionDiaria']['concesionario']) . ', Categoría: Todos';
                $vartrf['flag_search'] = 7;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR FECHA - CONCESIONARIO
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0) {
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['grupo'] = TRUE;
                $vartrf['search']['concesionario'] = TRUE;
                $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                if($_GET['GestionDiaria']['concesionario'] == 1000){
                    $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                    $dealerList = implode(', ', $array_dealers);
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getConcesionario($_GET['GestionDiaria']['concesionario']) . ', Categoría: Todos';
                $vartrf['flag_search'] = 8;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR FECHAS - RESPONSABLE
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1 && $_GET['categoria'] == 0) {
                //echo 'enter responsable';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['grupo'] = TRUE;
                $vartrf['search']['concesionario'] = TRUE;
                $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                if($_GET['GestionDiaria']['responsable'] ==  10000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: Todos';
                $vartrf['flag_search'] = 9;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR RESPONSABLE
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1 && $_GET['categoria'] == 0) {
                //echo 'enter responsable';
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['grupo'] = TRUE;
                $vartrf['search']['concesionario'] = TRUE;
                
                if($_GET['GestionDiaria']['grupo'] !=  1000 && $_GET['GestionDiaria']['concesionario'] !=  1000 && $_GET['GestionDiaria']['responsable'] !=  10000){
                    $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                }
                if($_GET['GestionDiaria']['grupo'] !=  1000 && $_GET['GestionDiaria']['concesionario'] ==  1000 && $_GET['GestionDiaria']['responsable'] ==  10000){
                    $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                    $dealerList = implode(', ', $array_dealers);
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                if($_GET['GestionDiaria']['grupo'] !=  1000 && $_GET['GestionDiaria']['concesionario'] !=  1000 && $_GET['GestionDiaria']['responsable'] ==  10000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                /*if($_GET['GestionDiaria']['responsable'] ==  10000 && $_GET['GestionDiaria']['concesionario'] ==  1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }*/
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por '.$tit.' Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: Todos';
                $vartrf['flag_search'] = 10;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }


            // BUSQUEDA POR FECHAS - PROVINCIA - GRUPO - CONCESIONARIO - RESPONSABLE ============================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1 && $_GET['categoria'] == 0) {
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                if($_GET['GestionDiaria']['provincia'] != 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] == 1000 && $_GET['GestionDiaria']['responsable'] == 1000){
                    $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                    $dealerList = implode(', ', $array_dealers);
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] == 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable'] == 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }

                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] == 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable']!= 1000){
                    $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                }
                
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: Todos';
                $vartrf['flag_search'] = 11;
                $this->render('graficos', array('vartrf' => $vartrf));
                $flag_search = 3;
                exit();
            }
            // BUSQUEDA POR FECHAS Y CATEGORIA========================================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 1) {
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                    $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . ' al ' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                $vartrf['flag_search'] = 12;
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR FECHAS - PROVINCIA - CATEGORIA ============================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 1 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 1) {
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                if($_GET['GestionDiaria']['provincia'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.provincia_conc = ' . $_GET['GestionDiaria']['provincia'];
                }
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 13;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR FECHAS - PROVINCIA - GRUPO - CATEGORIA ============================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 1) {
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                if($_GET['GestionDiaria']['provincia'] != 1000 && $_GET['GestionDiaria']['grupo'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.provincia_conc = ' . $_GET['GestionDiaria']['provincia'] . ' AND gi.dealer_id IN(' . $dealerList . ')';
                }

                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 14;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR FECHAS - GRUPO - CATEGORIA
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 1) {
                //die('fecha grupo');
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                if($_GET['GestionDiaria']['grupo'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 15;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR CONCESIONARIO - CATEGORIA
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 0 && $_GET['categoria'] == 1) {
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                    $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['concesionario'] = TRUE;
                $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';

                $vartrf['search']['titulo'] = 'Búsqueda por ' . $tit . ' Concesionario: ' . $this->getConcesionario($_GET['GestionDiaria']['concesionario']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 16;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR FECHA - RESPONSABLE - CATEGORIA
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1 && $_GET['categoria'] == 1) {
                //echo 'enter responsable';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['grupo'] = TRUE;
                $vartrf['search']['concesionario'] = TRUE;
                if($_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable'] != 10000){
                    $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                }
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                switch ($vartrf['cargo_id']) {
                    case 69:
                        $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                        break;
                    case 70:
                        $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                        break;
                    case 71:
                        $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                        break;

                    default:
                        break;
                }
                switch ($vartrf['area_id']) {
                    case 4:
                    case 12:
                    case 13:
                    case 14:
                        $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                        break;
                    default:
                        break;
                }
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 17;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR FECHAS - PROVINCIA - GRUPO - CONCESIONARIO - RESPONSABLE - CATEGORIA============================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1 && $_GET['categoria'] == 1) {
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);

                if($_GET['GestionDiaria']['provincia'] != 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable'] != 10000){
                    $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] == 1000 && $_GET['GestionDiaria']['responsable'] == 10000){
                    $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                    $dealerList = implode(', ', $array_dealers);
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable'] == 10000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable'] != 10000){
                    $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                }
                
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 18;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR PROVINCIA - GRUPO - CONCESIONARIO - RESPONSABLE - CATEGORIA============================================================================================
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1 && $_GET['categoria'] == 1) {
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);

                if($_GET['GestionDiaria']['provincia'] != 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable'] != 10000){
                    $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] == 1000 && $_GET['GestionDiaria']['responsable'] == 10000){
                    $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                    $dealerList = implode(', ', $array_dealers);
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable'] == 10000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable'] != 10000){
                    $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                }
                
                $vartrf['search']['titulo'] = 'Búsqueda por '.$tit.'Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 19;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }


            // BUSQUEDA POR PROVINCIA - GRUPO - CONCESIONARIO - CATEGORIA============================================================================================
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 0 && $_GET['categoria'] == 1) {
                //die('enter sin fecha');
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                    $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                
                if($_GET['GestionDiaria']['provincia'] != 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000) {
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000) {
                    $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                    $dealerList = implode(', ', $array_dealers);
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] == 1000 && $_GET['GestionDiaria']['concesionario'] != 1000) {
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                
                $vartrf['search']['titulo'] = 'Búsqueda por ' . $tit . 'Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 20;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR RESPONSABLE - CATEGORIA
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1 && $_GET['categoria'] == 1) {
                //echo 'enter responsable';
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                    $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['grupo'] = TRUE;
                $vartrf['search']['concesionario'] = TRUE;
                if($_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable'] != 1000) {
                    $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                }
                if($_GET['GestionDiaria']['grupo'] == 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable'] != 1000) {
                    $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                    $dealerList = implode(', ', $array_dealers);
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }

                
                switch ($vartrf['cargo_id']) {
                    case 69:
                        $vartrf['search']['titulo'] = 'Búsqueda por defecto - Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                        break;
                    case 70:
                        $vartrf['search']['titulo'] = 'Búsqueda por defecto - Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                        break;
                    case 71:
                        $vartrf['search']['titulo'] = 'Búsqueda por defecto - Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                        break;

                    default:
                        break;
                }
                switch ($vartrf['area_id']) {
                    case 4:
                    case 12:
                    case 13:
                    case 14:
                        $vartrf['search']['titulo'] = 'Búsqueda por defecto - Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                        break;
                    default:
                        break;
                }
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 21;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR CONCESIONARIO - RESPONSABLE - CATEGORIA============================================================================================
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1 && $_GET['categoria'] == 1) {
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                    $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                $vartrf['search']['titulo'] = 'Búsqueda por ' . $tit . ' Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 22;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR FECHAS - CONCESIONARIO - RESPONSABLE ============================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1 && $_GET['categoria'] == 0) {
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: Autos';
                $vartrf['flag_search'] = 23;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR FECHAS - CONCESIONARIO - RESPONSABLE - CATEGORIA============================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1 && $_GET['categoria'] == 1) {
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 24;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR FECHAS - CONCESIONARIO - CATEGORIA============================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 0 && $_GET['categoria'] == 1) {
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 25;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }
            //BUSQUEDA POR CONCESIONARIO Y AÑO
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0 && $_GET['year'] == 1) {
                $vartrf['mes_actual'] = 12;
                $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                $vartrf['search']['concesionario'] = TRUE;
                $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Defecto - Año: ' . $_GET['GestionDiaria']['year'] . ', Concesionario: ' . $this->getConcesionario($_GET['GestionDiaria']['concesionario']) . ', Categoría: Autos';
                $vartrf['flag_search'] = 26;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR FECHAS - CONCESIONARIO ============================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0) {
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['flag_search'] = 27;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }
            //BUSQUEDA POR AÑO
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0 && $_GET['year'] == 1) {
                $vartrf['mes_actual'] = 12;
                $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Defecto - Año: ' . $_GET['GestionDiaria']['year'] . ', Categoría: Autos';
                $vartrf['flag_search'] = 28;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }

            // BUSQUEDA POR GRUPO - CONCESIONARIO - RESPONSABLE ============================================================================================
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1 && $_GET['categoria'] == 0) {
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                    $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                $vartrf['search']['titulo'] = 'Búsqueda por ' . $tit . ' Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: Autos';
                $vartrf['flag_search'] = 29;
                $this->render('graficos', array('vartrf' => $vartrf));
                $flag_search = 28;
                exit();
            }
            // BUSQUEDA POR PROVINCIA - CATEGORIA ============================================================================================
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 1 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 1) {
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                    $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                if($_GET['GestionDiaria']['provincia'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.provincia_conc = ' . $_GET['GestionDiaria']['provincia'];
                }
                
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por ' . $tit . ' Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 30;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR PROVINCIA - GRUPO ============================================================================================
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0) {
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                    $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                if($_GET['GestionDiaria']['provincia'] != 1000 && $_GET['GestionDiaria']['grupo'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.provincia_conc = ' . $_GET['GestionDiaria']['provincia'] . ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }

                if($_GET['GestionDiaria']['provincia'] != 1000 && $_GET['GestionDiaria']['grupo'] == 1000){
                    $vartrf['search']['where'] = ' AND gi.provincia_conc = ' . $_GET['GestionDiaria']['provincia'] . ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                
                //$vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por ' . $tit . 'Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['flag_search'] = 31;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR PROVINCIA - GRUPO - CONCESIONARIO============================================================================================
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0) {
                //die('enter sin fecha');
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                    $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                if($_GET['GestionDiaria']['provincia'] != 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000){
                    $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                    $dealerList = implode(', ', $array_dealers);
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] == 1000 && $_GET['GestionDiaria']['concesionario'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                
                $vartrf['search']['titulo'] = 'Búsqueda por ' . $tit . ' Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']);

                $vartrf['flag_search'] = 32;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR FECHAS - PROVINCIA - GRUPO - CONCESIONARIO ============================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0) {
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                    $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                if($_GET['GestionDiaria']['provincia'] != 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000){
                    $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                    $dealerList = implode(', ', $array_dealers);
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] == 1000 && $_GET['GestionDiaria']['concesionario'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . '-' . $_GET['GestionDiaria']['year'] . ', Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Categoría: Autos';
                $vartrf['flag_search'] = 33;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR PROVINCIA - GRUPO - CONCESIONARIO - RESPONSABLE ============================================================================================
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1 && $_GET['categoria'] == 0) {
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                    $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                if($_GET['GestionDiaria']['provincia'] != 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable'] != 10000){
                    $vartrf['search']['where'] = ' AND gi.responsable = ' . $_GET['GestionDiaria']['responsable'];
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] == 1000 && $_GET['GestionDiaria']['responsable'] == 10000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $dealerList . ')';
                }
                if($_GET['GestionDiaria']['provincia'] == 1000 && $_GET['GestionDiaria']['grupo'] == 1000 && $_GET['GestionDiaria']['concesionario'] != 1000 && $_GET['GestionDiaria']['responsable'] == 10000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                if($_GET['GestionDiaria']['provincia'] != 1000 && $_GET['GestionDiaria']['grupo'] == 1000 && $_GET['GestionDiaria']['concesionario'] == 1000 && $_GET['GestionDiaria']['responsable'] == 10000){
                    $vartrf['search']['where'] = ' AND gi.provincia_conc = ' . $_GET['GestionDiaria']['provincia'];
                }
                
                $vartrf['search']['titulo'] = 'Búsqueda por ' . $tit . ' Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Responsable: ' . $this->getResponsableNombres($_GET['GestionDiaria']['responsable']) . ', Categoría: Autos';
                $vartrf['flag_search'] = 34;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR  GRUPO - CONCESIONARIO - CATEGORIA============================================================================================
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 0 && $_GET['categoria'] == 1) {
                //die('enter sin fecha');
                $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $vartrf['dia_inicial'];
                    $vartrf['search']['dia_actual'] = $vartrf['dia_actual'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                if($_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                if($_GET['GestionDiaria']['grupo'] == 1000 && $_GET['GestionDiaria']['concesionario'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                
                $vartrf['search']['titulo'] = 'Búsqueda por ' . $tit . ' Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 35;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR FECHAS - GRUPO - CONCESIONARIO - CATEGORIA============================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 0 && $_GET['categoria'] == 1) {
                //die('enter sin fecha');
                $tit = '- Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $vartrf['search']['fecha'] = true;
                    $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                    $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                    $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                    $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
                    $tit = '- Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['grupo'] = TRUE;
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                if($_GET['GestionDiaria']['grupo'] != 1000 && $_GET['GestionDiaria']['concesionario'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                if($_GET['GestionDiaria']['grupo'] == 1000 && $_GET['GestionDiaria']['concesionario'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.dealer_id IN(' . $_GET['GestionDiaria']['concesionario'] . ')';
                }
                
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde ' . $_GET['GestionDiaria']['fecha1'] . '-' . $_GET['GestionDiaria']['fecha2'] . $tit . ' Grupo: ' . $this->getNombreGrupo($_GET['GestionDiaria']['grupo']) . ', Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']) . ', Categoría: ' . $this->getNameCategoria($_GET['GestionDiaria']['categoria']);
                $vartrf['modelos'] = $this->getModelosTrafico($_GET['GestionDiaria']['categoria']);
                $vartrf['versiones'] = $this->getModelosTraficoVersion($_GET['GestionDiaria']['categoria']);
                $vartrf['id_modelos'] = array();
                $vartrf['categoria'] = $_GET['GestionDiaria']['categoria'];
                foreach ($vartrf['modelos'] as $value) {
                    $vartrf['id_modelos'][] = $value['id'];
                }
                $vartrf['flag_search'] = 36;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }
            // BUSQUEDA POR PROVINCIA ============================================================================================
            if ($_GET['fecha1'] == 0 && $_GET['fecha2'] == 0 && $_GET['provincia'] == 1 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['categoria'] == 0) {
                 $tit = ' defecto - Año: 2017, ';
                if ($_GET['year'] == 1) {
                    $vartrf['mes_actual'] = 12;
                    $vartrf['year_actual'] = $_GET['GestionDiaria']['year'];
                    $tit = ' defecto - Año: ' . $_GET['GestionDiaria']['year'] . ',';
                }
                
                if($_GET['GestionDiaria']['provincia'] != 1000){
                    $vartrf['search']['where'] = ' AND gi.provincia_conc = ' . $_GET['GestionDiaria']['provincia'];
                }
                $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por por ' . $tit.' Provincia: ' . $this->getProvincia($_GET['GestionDiaria']['provincia']) . ', Categoría: Todos';
                $vartrf['flag_search'] = 37;
                $this->render('graficos', array('vartrf' => $vartrf));
                exit();
            }
            //$posts = $this->searchSql($cargo_id, $grupo_id, $id_responsable, $fechaPk, 'seg', '');
        
        }
        $this->render('graficos', array('vartrf' => $vartrf));
    }

    /**
     * 
     */
    public function actionReportes() {
        $cargo_id = Yii::app()->getModulePath();
        $vartrf = array();
//        $criteria = new CDbCriteria;
//        $criteria->select = "d.`name`, gi.id, gi.nombres, gi.apellidos, gi.cedula, gi.ruc, gi.pasaporte, gi.email, gi.celular, gi.telefono_casa,
//gi.fecha, gi.direccion, gi.bdc, m.nombre_modelo, v.nombre_version, gv.version, u.nombres, u.apellido, gi.medio as pregunta,
// gi.recomendaron AS opcion_recomendacion, gi.medio_prensa, gi.medio_television";
//        $criteria->join = " INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ";
//        $criteria->join .= " INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id";
//        $criteria->alias = 'gi';
//        $post = GestionInformacion::model()->findAll($criteria);
        date_default_timezone_set('America/Guayaquil');
        $time = time();
        $fecha = new DateTime();
        $fecha->modify('first day of this month');
        $vartrf['fecha_actual'] = date("Y-m-d", $time);
        $vartrf['fecha_inicial'] = $fecha->format('Y-m-d');
        if (isset($_GET['GestionDiaria'])) {
//            echo '<pre>';
//            print_r($_GET);
//            echo '</pre>';
//            die();
            // BUSQUEDA POR DEFECTO SHOWROOM, PROSPECCION, WEB, EXHIBICION
            if ($_GET['fecha'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['fuente_contacto'] == 1 && $_GET['tipo_reporte'] == 1) {
                $data = $this->getReporteTrafico($_GET['GestionDiaria']['fecha'], $_GET['GestionDiaria']['grupo'], $_GET['GestionDiaria']['concesionario'], $_GET['GestionDiaria']['responsable'], $_GET['GestionDiaria']['fuente_contacto'], $_GET['GestionDiaria']['tipo_reporte']);
                $posts = $data['posts'];
                $tituloReporte = $data['titulo_reporte'];
                $flag_search = 1;
            }
            
            // BUSQUEDA POR FECHAS
            if ($_GET['fecha'] == 1 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['fuente_contacto'] == 1 && $_GET['tipo_reporte'] == 1) {
                $data = $this->getReporteTrafico($_GET['GestionDiaria']['fecha'], $_GET['GestionDiaria']['grupo'], $_GET['GestionDiaria']['concesionario'], $_GET['GestionDiaria']['responsable'], $_GET['GestionDiaria']['fuente_contacto'], $_GET['GestionDiaria']['tipo_reporte']);
                $posts = $data['posts'];
                $tituloReporte = $data['titulo_reporte'];
                $flag_search = 2;
            }
            
            // BUSQUEDA POR FECHAS - GRUPO
            if ($_GET['fecha'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['fuente_contacto'] == 1 && $_GET['tipo_reporte'] == 1) {
                
                $data = $this->getReporteTrafico($_GET['GestionDiaria']['fecha'], $_GET['GestionDiaria']['grupo'], $_GET['GestionDiaria']['concesionario'], $_GET['GestionDiaria']['responsable'], $_GET['GestionDiaria']['fuente_contacto'], $_GET['GestionDiaria']['tipo_reporte']);
                $posts = $data['posts'];
                $tituloReporte = $data['titulo_reporte'];
                $flag_search = 3;
            }
            
            // BUSQUEDA POR FECHAS - GRUPO - CONCESIONARIO
            if ($_GET['fecha'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 0 && $_GET['fuente_contacto'] == 1 && $_GET['tipo_reporte'] == 1) {
                $data = $this->getReporteTrafico($_GET['GestionDiaria']['fecha'], $_GET['GestionDiaria']['grupo'], $_GET['GestionDiaria']['concesionario'], $_GET['GestionDiaria']['responsable'], $_GET['GestionDiaria']['fuente_contacto'], $_GET['GestionDiaria']['tipo_reporte']);
                $posts = $data['posts'];
                $tituloReporte = $data['titulo_reporte'];
                $flag_search = 4;
            }
            
            // BUSQUEDA POR FECHAS - GRUPO - CONCESIONARIO -RESPONSABLE
            if ($_GET['fecha'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1 && $_GET['fuente_contacto'] == 1 && $_GET['tipo_reporte'] == 1) {
                $data = $this->getReporteTrafico($_GET['GestionDiaria']['fecha'], $_GET['GestionDiaria']['grupo'], $_GET['GestionDiaria']['concesionario'], $_GET['GestionDiaria']['responsable'], $_GET['GestionDiaria']['fuente_contacto'], $_GET['GestionDiaria']['tipo_reporte']);
                $posts = $data['posts'];
                $tituloReporte = $data['titulo_reporte'];
                $flag_search = 5;
            }
                        
            // BUSQUEDA POR GRUPO
            if ($_GET['fecha'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0 && $_GET['fuente_contacto'] == 1 && $_GET['tipo_reporte'] == 1) {
                
                $data = $this->getReporteTrafico($_GET['GestionDiaria']['fecha'], $_GET['GestionDiaria']['grupo'], $_GET['GestionDiaria']['concesionario'], $_GET['GestionDiaria']['responsable'], $_GET['GestionDiaria']['fuente_contacto'], $_GET['GestionDiaria']['tipo_reporte']);
                $posts = $data['posts'];
                $tituloReporte = $data['titulo_reporte'];
                $flag_search = 6;
            }
            
            // BUSQUEDA POR GRUPO - CONCESIONARIO
            if ($_GET['fecha'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 0 && $_GET['fuente_contacto'] == 1 && $_GET['tipo_reporte'] == 1) {
                $data = $this->getReporteTrafico($_GET['GestionDiaria']['fecha'], $_GET['GestionDiaria']['grupo'], $_GET['GestionDiaria']['concesionario'], $_GET['GestionDiaria']['responsable'], $_GET['GestionDiaria']['fuente_contacto'], $_GET['GestionDiaria']['tipo_reporte']);
                $posts = $data['posts'];
                $tituloReporte = $data['titulo_reporte'];
                $flag_search = 7;
            }
            
            // BUSQUEDA POR GRUPO - CONCESIONARIO - RESPONSABLE
            if ($_GET['fecha'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1 && $_GET['fuente_contacto'] == 1 && $_GET['tipo_reporte'] == 1) {
                $data = $this->getReporteTrafico($_GET['GestionDiaria']['fecha'], $_GET['GestionDiaria']['grupo'], $_GET['GestionDiaria']['concesionario'], $_GET['GestionDiaria']['responsable'], $_GET['GestionDiaria']['fuente_contacto'], $_GET['GestionDiaria']['tipo_reporte']);
                $posts = $data['posts'];
                $tituloReporte = $data['titulo_reporte'];
                $flag_search = 8;
            }
            
            
            Yii::import('ext.phpexcel.XPHPExcel');
            $objPHPExcel = XPHPExcel::createPHPExcel();
            $objPHPExcel->getProperties()->setCreator("SGC Kia Ecuador")
                    ->setLastModifiedBy("SGC")
                    ->setTitle("Office 2007 XLSX Test Document")
                    ->setSubject("Office 2007 XLSX Test Document")
                    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                    ->setKeywords("office 2007 openxml php")
                    ->setCategory("Test result file");
            $estiloTituloReporte = array(
                'font' => array('name' => 'Tahoma','bold' => true,'italic' => false,'strike' => false,'size' => 11,'color' => array('rgb' => 'B6121A')),
                'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,'rotation' => 0,'wrap' => TRUE)
            );

            $estiloTituloColumnas = array(
                'font' => array('name' => 'Arial','bold' => true,'size' => 9,'color' => array('rgb' => '333333')),
                'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'F1F1F1')),
                'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM,'color' => array('rgb' => '143860')),
                    'bottom' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM,'color' => array('rgb' => '143860')),
                    'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '143860')),
                    'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('rgb' => '143860'))
                ),
                'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    'wrap' => TRUE
                )
            );
            // Se combinan las celdas A1 hasta F1, para colocar ahí el titulo del reporte
            $objPHPExcel->setActiveSheetIndex(0)
                    ->mergeCells('A1:Q1');
            $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth('33');
            // Add some data

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', $tituloReporte) // Titulo del reporte
                    ->setCellValue('A2', 'Nombre')
                    ->setCellValue('B2', 'id')
                    ->setCellValue('C2', 'nombres')
                    ->setCellValue('D2', 'apellidos')
                    ->setCellValue('E2', 'cedula')
                    ->setCellValue('F2', 'ruc')
                    ->setCellValue('G2', 'pasaporte')
                    ->setCellValue('H2', 'email')
                    ->setCellValue('I2', 'celular')
                    ->setCellValue('J2', 'telefono_casa')
                    ->setCellValue('K2', 'fecha')
                    ->setCellValue('L2', 'direccion')
                    ->setCellValue('M2', 'bdc')
                    ->setCellValue('N2', 'nombre_modelo')
                    ->setCellValue('O2', 'nombre_version');
            switch($_GET['GestionDiaria']['tipo_reporte']){
                case 1: // TRAFICO
                    $objPHPExcel->setActiveSheetIndex(0) 
                    ->setCellValue('P2', 'version')
                    ->setCellValue('Q2', 'nombre')
                    ->setCellValue('R2', 'apellido')
                    ->setCellValue('S2', 'pregunta')
                    ->setCellValue('T2', 'opcion_recomendaron')
                    ->setCellValue('U2', 'medio_prensa')
                    ->setCellValue('V2', 'medio_television')
                    ->setCellValue('W2', 'fuente_contacto');
                    $name_file = "Reporte Trafico";
                    break;
                case 2: // PROFORMAS
                    $objPHPExcel->setActiveSheetIndex(0) 
                    ->setCellValue('P2', 'version')
                    ->setCellValue('Q2', 'fuente_contacto');
                    $name_file = "Reporte Proformas";
                    break;
                case 3: // TESTDRIVE
                    $objPHPExcel->setActiveSheetIndex(0) 
                    ->setCellValue('P2', 'test_drive')
                    ->setCellValue('Q2', 'version')
                    ->setCellValue('R2', 'fuente_contacto');
                    $name_file = "Reporte TestDrive";
                    break;
                case 4: // VENTAS
                    $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', $tituloReporte) // Titulo del reporte
                    ->setCellValue('A2', 'id_vehiculo')    
                    ->setCellValue('B2', 'Nombre')
                    ->setCellValue('C2', 'id')
                    ->setCellValue('D2', 'nombres')
                    ->setCellValue('E2', 'apellidos')
                    ->setCellValue('F2', 'cedula')
                    ->setCellValue('G2', 'ruc')
                    ->setCellValue('H2', 'pasaporte')
                    ->setCellValue('I2', 'email')
                    ->setCellValue('J2', 'celular')
                    ->setCellValue('K2', 'telefono_casa')
                    ->setCellValue('L2', 'fecha')
                    ->setCellValue('M2', 'direccion')
                    ->setCellValue('N2', 'bdc')
                    ->setCellValue('O2', 'nombre_modelo')
                    ->setCellValue('P2', 'nombre_version');
                    $objPHPExcel->setActiveSheetIndex(0) 
                    ->setCellValue('Q2', 'version')
                    ->setCellValue('R2', 'status')
                    ->setCellValue('S2', 'cierre')
                    ->setCellValue('T2', 'Nombres')
                    ->setCellValue('U2', 'Apellidos')
                    ->setCellValue('V2', 'fuente_contacto');
                    $name_file = "Reporte Ventas";
                    break;
                
            }
            
            $i = 3;
            foreach ($posts as $row) {
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $row['name'])
                        ->setCellValue('B' . $i, $row['id'])
                        ->setCellValue('C' . $i, ($row['nombres']))
                        ->setCellValue('D' . $i, ($row['apellidos']))
                        ->setCellValue('E' . $i, ($row['cedula']))
                        ->setCellValue('F' . $i, $row['ruc'])
                        ->setCellValue('G' . $i, $row['pasaporte'])
                        ->setCellValue('H' . $i, $row['email'])
                        ->setCellValue('I' . $i, $row['celular'])
                        ->setCellValue('J' . $i, $row['telefono_casa'])
                        ->setCellValue('K' . $i, $row['fecha'])
                        ->setCellValue('L' . $i, $row['direccion'])
                        ->setCellValue('M' . $i, $row['bdc'])
                        ->setCellValue('N' . $i, $row['nombre_modelo'])
                        ->setCellValue('O' . $i, $row['nombre_version']);
                switch($_GET['GestionDiaria']['tipo_reporte']){
                    case 1: // TRAFICO
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('P' . $i, $row['version'])
                        ->setCellValue('Q' . $i, $row['nombre_responsable'])
                        ->setCellValue('R' . $i, $row['apellido_responsable'])
                        ->setCellValue('S' . $i, $row['pregunta'])
                        ->setCellValue('T' . $i, $row['opcion_recomendacion'])
                        ->setCellValue('U' . $i, $row['medio_prensa'])
                        ->setCellValue('V' . $i, $row['medio_television'])
                        ->setCellValue('W' . $i, $row['fuente_contacto']);
                        $objPHPExcel->getActiveSheet()->getStyle('A2:W2')->applyFromArray($estiloTituloColumnas);
                        break;
                    case 2: // PROFORMAS
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('P' . $i, $row['version'])
                        ->setCellValue('Q' . $i, $row['fuente_contacto']);
                        $objPHPExcel->getActiveSheet()->getStyle('A2:Q2')->applyFromArray($estiloTituloColumnas);
                        break;
                    case 3: // TESTDRIVE
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('P' . $i, $row['test_drive'])
                        ->setCellValue('Q' . $i, $row['version'])
                        ->setCellValue('R' . $i, $row['fuente_contacto']);
                        $objPHPExcel->getActiveSheet()->getStyle('A2:R2')->applyFromArray($estiloTituloColumnas);
                        break;
                    case 4: // VENTAS
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $row['id_vehiculo'])
                        ->setCellValue('B' . $i, $row['name'])
                        ->setCellValue('C' . $i, $row['id'])
                        ->setCellValue('D' . $i, ($row['nombres']))
                        ->setCellValue('E' . $i, ($row['apellidos']))
                        ->setCellValue('F' . $i, ($row['cedula']))
                        ->setCellValue('G' . $i, $row['ruc'])
                        ->setCellValue('H' . $i, $row['pasaporte'])
                        ->setCellValue('I' . $i, $row['email'])
                        ->setCellValue('J' . $i, $row['celular'])
                        ->setCellValue('K' . $i, $row['telefono_casa'])
                        ->setCellValue('L' . $i, $row['fecha'])
                        ->setCellValue('M' . $i, $row['direccion'])
                        ->setCellValue('N' . $i, $row['bdc'])
                        ->setCellValue('O' . $i, $row['nombre_modelo'])
                        ->setCellValue('P' . $i, $row['nombre_version']);
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('Q' . $i, $row['version'])
                        ->setCellValue('R' . $i, $row['status'])
                        ->setCellValue('S' . $i, $row['cierre'])
                        ->setCellValue('T' . $i, $row['nombre_responsable'])
                        ->setCellValue('U' . $i, $row['apellido_responsable'])
                        ->setCellValue('V' . $i, $row['fuente_contacto']);
                        $objPHPExcel->getActiveSheet()->getStyle('A2:V2')->applyFromArray($estiloTituloColumnas);
                        break;
                }
                
                $objPHPExcel->getActiveSheet()->setCellValueExplicit('E' . $i, $row['cedula'], PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit('F' . $i, $row['ruc'], PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit('I' . $i, $row['celular'], PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit('J' . $i, $row['telefono_casa'], PHPExcel_Cell_DataType::TYPE_STRING);
                $i++;
            }
            $style1 = array(
                'font' => array(
                    'name' => 'Calibri',
                    'bold' => true,
                    'size' => 11,
                ),
            );

            $objPHPExcel->getActiveSheet()->getStyle('A2:M2')->applyFromArray($style1);
            $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension("G")->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension("H")->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension("I")->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension("J")->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension("K")->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension("L")->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension("M")->setAutoSize('33');
            $objPHPExcel->getActiveSheet()->getColumnDimension("N")->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension("O")->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension("P")->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension("Q")->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension("R")->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension("S")->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension("T")->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension("U")->setAutoSize('136');
            $objPHPExcel->getActiveSheet()->getColumnDimension("V")->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension("W")->setAutoSize(true);
            // rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Reporte de casos');

            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->applyFromArray($estiloTituloReporte);
            

            // Inmovilizar paneles
            $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0, 3);

            // Redirect output to a client's web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=' . $name_file . '.xls');
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
        $this->render('reportes', array('vartrf' => $vartrf));
    }

    public function actionGetNombreGrupo() {
        $grupo = isset($_POST["grupo"]) ? $_POST["grupo"] : "";
        $criteria = new CDbCriteria(array(
            'condition' => "id={$grupo}"
        ));
        $dealer = GrGrupo::model()->find($criteria);
        echo $dealer->nombre_grupo;
    }

    public function actionGetNombreConcesionario() {
        $concesionario = isset($_POST["concesionario"]) ? $_POST["concesionario"] : "";
        //die('id-----: '.$id);
        $criteria = new CDbCriteria(array(
            'condition' => "id={$concesionario}"
        ));
        $usuarios = Dealers::model()->find($criteria);
        if ($usuarios) {
            echo $usuarios->name;
        } else {
            echo 'NA';
        }
    }

    public function actionGetTitulo(){
        $titulo = 'Reporte ';
        $trafico_fecha = isset($_POST['trafico_fecha']) ? $_POST["trafico_fecha"] : "";
        $trafico_grupo = isset($_POST['trafico_grupo']) ? $_POST["trafico_grupo"] : "";
        $trafico_concesionario = isset($_POST['trafico_concesionario']) ? $_POST["trafico_concesionario"] : "";
        $trafico_responsable = isset($_POST['trafico_responsable']) ? $_POST["trafico_responsable"] : "";
        $trafico_fuente_contacto = isset($_POST['trafico_fuente_contacto']) ? $_POST["trafico_fuente_contacto"] : "";
        $trafico_tipo_reporte = isset($_POST['trafico_tipo_reporte']) ? $_POST["trafico_tipo_reporte"] : "";
        $grupo = isset($_POST['grupo']) ? $_POST["grupo"] : "";
        $concesionario = isset($_POST['concesionario']) ? $_POST["concesionario"] : "";
        $responsable = isset($_POST['responsable']) ? $_POST["responsable"] : "";
        switch ($trafico_tipo_reporte) {
            case 1: // TRAFICO
                $titulo .= ' Tráfico desde el '.$trafico_fecha;
                break;
            case 2: // PROFORMAS
                $titulo .= ' Proformas desde el '.$trafico_fecha;
                break;
            case 3: // TESTDRIVE
                $titulo .= ' TestDrive desde el '.$trafico_fecha;
                break;
            case 4: // VENTAS
                $titulo .= ' Ventas desde el '.$trafico_fecha;
                break;            
            
            default:
                # code...
                break;
        }
        if ($grupo == 1 && $concesionario == 0) {
            $titulo_rep = ", GRUPO: ".$this->getNombreGrupo($trafico_grupo);
        }
        if($concesionario == 1){
            $titulo_rep = ", GRUPO: ".$this->getNombreGrupo($trafico_grupo).", CONCESIONARIO: ".$this->getConcesionario($trafico_concesionario);
        }
        if($responsable == 1){
            $titulo_rep = ", CONCESIONARIO: ".$this->getConcesionario($trafico_concesionario).", RESPONSABLE: ".$this->getResponsableNombres($trafico_responsable);
        }
        $titulo .= $titulo_rep;
        echo $titulo;
    }

    public function actionGetConcesionariosGrupo(){
        $dealer_id =  isset($_POST['dealer_id']) ? $_POST["dealer_id"] : "";
        $grupo_id = isset($_POST['grupo_id']) ? $_POST["grupo_id"] : "";

        $data = '<option value="">--Seleccione concesionario--</option><option value="1000">Todos</option>';
        if($grupo_id == 1000)
            $conc = GrConcesionarios::model()->findAll(array('condition' => "dealer_id <> 0", 'order' => 'nombre'));
        else
            $conc = GrConcesionarios::model()->findAll(array("condition" => "id_grupo = {$grupo_id}"));

        foreach ($conc as $value) {
            $data .= '<option value="'.$value['dealer_id'].'"';
            if($value['dealer_id'] == $dealer_id){
                $data .= ' selected';
            }
            $data .= '>'.$value['nombre'].'</option>';
        }
        $options = array('options' => $data);
        echo json_encode($options);
    }

    public function actionGetResponsablesConcecionario(){
        $dealer_id =  isset($_POST['dealer_id']) ? $_POST["dealer_id"] : "";
        $responsable = isset($_POST['responsable']) ? $_POST["responsable"] : "";
        $data = '<option value="">--Seleccione responsable--</option><option value="10000">Todos</option>';
        $res = Usuarios::model()->findAll(array("condition" => "dealers_id = {$dealer_id} AND cargo_id IN (71) AND estado = 'ACTIVO'","order" => "nombres ASC"));
        
        foreach ($res as $value) {
            $data .= '<option value="'.$value['id'].'"';
            if($value['id'] == $responsable){
                $data .= ' selected';
            }
            $data .= '>'.strtoupper($this->getResponsableNombres($value['id'])).'</option>';
        }
        $options = array('options' => $data);
        echo json_encode($options);
    }

}
