<?php

class TraficoController extends Controller {

    public function actionInicio() {
        date_default_timezone_set('America/Guayaquil');
        $dt = time();
        setlocale(LC_TIME, 'es_ES.UTF-8');
        $vartrf = array();
        $vartrf['grupo_id'] = (int) Yii::app()->user->getState('grupo_id');
        $vartrf['cargo_id'] = (int) Yii::app()->user->getState('cargo_id');
        $vartrf['cargo_adicional'] = (int) Yii::app()->user->getState('cargo_adicional');
        //$varView['cargo_adicional'] = 85;
        $vartrf['id_responsable'] = Yii::app()->user->getId();
        // SACAR MES ACTUAL
        $vartrf['year_actual'] = date("Y");
        $vartrf['mes_actual'] = date('n');
        //echo $vartrf['mes_actual'];
        $vartrf['dia_inicial'] = '01';
        $vartrf['dia_actual'] = date("d");
        $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['dia_inicial'], $vartrf['dia_actual']);
        $vartrf['fechas_date'] = $this->getNumeroMesesDate($vartrf['mes_actual'], $vartrf['dia_actual']);
        $vartrf['modelos'] = $this->getModelosTrafico();
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
        if (isset($_GET['GestionDiaria'])) {
//            echo '<pre>';
//            print_r($_GET);
//            echo '</pre>';
//            die();
            $id_responsable = Yii::app()->user->getId();
            //die('enter get');
            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
            $fechaActual = date("Y/m/d");
            $flag_search = 0;

            // BUSQUEDA POR FECHAS ========================================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0) {
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde '.$_GET['GestionDiaria']['fecha1'].' al '.$_GET['GestionDiaria']['fecha2'].'-'.$_GET['GestionDiaria']['year'];
                $this->render('inicio', array('vartrf' => $vartrf));
                $flag_search = 1;
                exit();
            }
            // BUSQUEDA POR FECHAS - PROVINCIA ============================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 1 && $_GET['grupo'] == 0 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0) {
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['where'] = ' AND gi.provincia_conc = '.$_GET['GestionDiaria']['provincia'];
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde '.$_GET['GestionDiaria']['fecha1'].'-'.$_GET['GestionDiaria']['fecha2'].'-'.$_GET['GestionDiaria']['year'].', Provincia: '.  $this->getProvincia($_GET['GestionDiaria']['provincia']);
                $this->render('inicio', array('vartrf' => $vartrf));
                $flag_search = 2;
                exit();
            }
            // BUSQUEDA POR FECHAS - PROVINCIA - GRUPO ============================================================================================
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 1 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0) {
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                $vartrf['search']['where'] = ' AND gi.provincia_conc = '.$_GET['GestionDiaria']['provincia'].' AND gi.dealer_id IN('.$dealerList.')';
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde '.$_GET['GestionDiaria']['fecha1'].'-'.$_GET['GestionDiaria']['fecha2'].'-'.$_GET['GestionDiaria']['year'].', Provincia: '.  $this->getProvincia($_GET['GestionDiaria']['provincia']).', Grupo: '.  $this->getNombreGrupo($_GET['GestionDiaria']['grupo']);
                $this->render('inicio', array('vartrf' => $vartrf));
                $flag_search = 3;
                exit();
            }
            // BUSQUEDA POR FECHAS - GRUPO
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 0 && $_GET['responsable'] == 0) {
                //die('fecha grupo');
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['grupo'] = TRUE;
                $array_dealers = $this->getDealerGrupoConc($_GET['GestionDiaria']['grupo']);
                $dealerList = implode(', ', $array_dealers);
                $vartrf['search']['where'] = ' AND gi.dealer_id IN('.$dealerList.')';
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde '.$_GET['GestionDiaria']['fecha1'].'-'.$_GET['GestionDiaria']['fecha2'].'-'.$_GET['GestionDiaria']['year'].', Grupo: '.  $this->getNombreGrupo($_GET['GestionDiaria']['grupo']);
                $this->render('inicio', array('vartrf' => $vartrf));
                $flag_search = 4;
                exit();
            }
            // BUSQUEDA POR CONCESIONARIO
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 0) {
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['grupo'] = TRUE;
                $vartrf['search']['concesionario'] = TRUE;
                $vartrf['search']['where'] = ' AND gi.dealer_id IN('.$_GET['GestionDiaria']['concesionario'].')';
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde '.$_GET['GestionDiaria']['fecha1'].'-'.$_GET['GestionDiaria']['fecha2'].'-'.$_GET['GestionDiaria']['year'].', Grupo: '.  $this->getNombreGrupo($_GET['GestionDiaria']['grupo']).', Concesionario: '.  $this->getConcesionario($_GET['GestionDiaria']['concesionario']);
                $this->render('inicio', array('vartrf' => $vartrf));
                $flag_search = 5;
                exit();
            }
            // BUSQUEDA POR RESPONSABLE
            if ($_GET['fecha1'] == 1 && $_GET['fecha2'] == 1 && $_GET['provincia'] == 0 && $_GET['grupo'] == 1 && $_GET['concesionario'] == 1 && $_GET['responsable'] == 1) {
                //echo 'enter responsable';
                $vartrf['dia_inicial'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['fecha'] = true;
                $vartrf['search']['dia_anterior'] = $_GET['GestionDiaria']['fecha1'];
                $vartrf['search']['dia_actual'] = $_GET['GestionDiaria']['fecha2'];
                $vartrf['search']['grupo'] = TRUE;
                $vartrf['search']['concesionario'] = TRUE;
                $vartrf['search']['where'] = ' AND gi.responsable = '.$_GET['GestionDiaria']['responsable'];
                $vartrf['fechas'] = $this->getNumeroMeses($vartrf['mes_actual'], $vartrf['search']['dia_anterior'], $vartrf['search']['dia_actual']);
                $vartrf['search']['titulo'] = 'Búsqueda por Fechas - Desde '.$_GET['GestionDiaria']['fecha1'].'-'.$_GET['GestionDiaria']['fecha2'].'-'.$_GET['GestionDiaria']['year'].', Grupo: '.  $this->getNombreGrupo($_GET['GestionDiaria']['grupo']).', Concesionario: '.  $this->getNameConcesionarioById($_GET['GestionDiaria']['concesionario']).', Responsable: '.  $this->getResponsableNombres($_GET['GestionDiaria']['responsable']);
                $this->render('inicio', array('vartrf' => $vartrf));
                $flag_search = 6;
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
        $modelos = $this->getModelosTrafico();
        foreach ($modelos as $value) {
            switch ($value['id']) {
                case 1:
                    $data_picanto = $this->getDataTableDiario($dia_inicial, $dia_actual, $value['id_versiones'], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 2:
                    $data_carens = $this->getDataTableDiario($dia_inicial, $dia_actual, $value['id_versiones'], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 3:
                    $data_cerato_forte = $this->getDataTableDiario($dia_inicial, $dia_actual, $value['id_versiones'], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 4:
                    $data_cerato_koup = $this->getDataTableDiario($dia_inicial, $dia_actual, $value['id_versiones'], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 5:
                    $data_cerato_r = $this->getDataTableDiario($dia_inicial, $dia_actual, $value['id_versiones'], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 6:
                    $data_grand_carnival = $this->getDataTableDiario($dia_inicial, $dia_actual, $value['id_versiones'], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                case 7:
                    $data_k3000 = $this->getDataTableDiario($dia_inicial, $dia_actual, $value['id_versiones'], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 8:
                    $data_optima = $this->getDataTableDiario($dia_inicial, $dia_actual, $value['id_versiones'], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 10:
                    $data_rio_sedan = $this->getDataTableDiario($dia_inicial, $dia_actual, $value['id_versiones'], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 11:
                    $data_rio_hb = $this->getDataTableDiario($dia_inicial, $dia_actual, $value['id_versiones'], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 12:
                    $data_soul_ev = $this->getDataTableDiario($dia_inicial, $dia_actual, $value['id_versiones'], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 13:
                    $data_soul_r = $this->getDataTableDiario($dia_inicial, $dia_actual, $value['id_versiones'], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 14:
                    $data_sportage_active = $this->getDataTableDiario($dia_inicial, $dia_actual, $value['id_versiones'], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 15:
                    $data_sportage_r = $this->getDataTableDiario($dia_inicial, $dia_actual, $value['id_versiones'], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 16:
                    $data_sportage_gt = $this->getDataTableDiario($dia_inicial, $dia_actual, $value['id_versiones'], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                case 17:
                    $data_sportage_r_ckd = $this->getDataTableDiario($dia_inicial, $dia_actual, $value['id_versiones'], $year, $mes, $value['nombre_modelo'], $i, $where, $value['id']);
                    break;
                default:
                    break;
            }
        }
        $options = array('data_picanto' => $data_picanto, 'data_carens' => $data_carens, 'data_cerato_forte' => $data_cerato_forte,
            'data_cerato_koup' => $data_cerato_koup, 'data_cerato_r' => $data_cerato_r, 'data_grand_carnival' => $data_grand_carnival,
            'data_k3000' => $data_k3000, 'data_optima' => $data_optima, 'data_rio_sedan' => $data_rio_sedan, 'data_rio_hb' => $data_rio_hb,
            'data_soul_ev' => $data_soul_ev, 'data_soul_r' => $data_soul_r, 'data_sportage_active' => $data_sportage_active,
            'data_sportage_r' => $data_sportage_r, 'data_sportage_gt' => $data_sportage_gt, 'data_sportage_r_ckd' => $data_sportage_r_ckd);
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
        if($dia_inicial != '01'){
            $search_array['fecha'] = TRUE;
            $search_array['dia_anterior'] = $dia_inicial;
            $search_array['dia_actual'] = $dia_actual;
        }
            
        $fmes = $this->getNombreMes($mes);

        $button = '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" onclick="closemodal(\'' . $mes . '\',\'' . $i . '\');">×</span></button>';
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
                        $datatr = $this->getTraficoVersion($mes, $versiones, $year, $d, 0, $search_array);
                        $trafico_diario[] = $datatr;
                        $datata = $datata + $datatr;
                        $data .= "<td>" . $datata . "</td>";
                        break;
                    case 2:
                        //$datatr = $this->getDataDia($versiones, $year, $mes, $d);
                        //$trafico[] = $datatr;
                        $data .= "<td>" . $trafico_diario[$j-$dia_inicial] . "</td>";
                        break;
                    case 3:
                        //$datapf = $this->getDataProforma($versiones, $year, $mes, $d);
                        $datapf = $this->getProformaVersion($mes, $versiones, $year, $d, 0, $search_array);
                        $data .= "<td>" . $datapf . "</td>";
                        break;
                    case 4:
                        //$datatd = $this->getDataTestDrive($versiones, $year, $mes, $d);
                        $datatd = $this->getTestDriveVersion($mes, $versiones, $year, $d, 0, $search_array);
                        $testdrive[] = $datatd;
                        $data .= "<td>" . $datatd . "</td>";
                        break;
                    case 5:
                        //$datavt = $this->getDataVentas($versioneds, $year, $mes, $d);
                        $datavt = $this->getVentasVersion($mes, $versiones, $year, $dia, 0, $search_array);
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

    public function actionGetGrupos() {
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        $res = GrGrupo::model()->findAll(array('condition' => "FIND_IN_SET('" . $id . "',id_provincias) > 0"));
        $data = '<option value="">--Seleccione Grupo--</option>';
        if (count($res) > 0) {
            foreach ($res as $value) {
                $data .= '<option value="' . $value['id'] . '">' . $value['nombre_grupo'] . '</option>';
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
                $sql = "SELECT * FROM usuarios WHERE dealers_id = {$dealer_id} AND cargo_id IN (75) ORDER BY nombres ASC";
                break;
            case 'seg':
                $sql = "SELECT * FROM usuarios WHERE dealers_id = {$dealer_id} AND cargo_id IN (71,70) ORDER BY nombres ASC";
                break;
            case 'web':
                //$sql = "SELECT * FROM usuarios WHERE dealers_id = {$dealer_id} AND cargo_id IN (85,86) ORDER BY nombres ASC";
                $sql = "SELECT u.* FROM usuarios u
                        INNER JOIN grupoconcesionariousuario gr ON gr.usuario_id = u.id
                        WHERE cargo_id IN (85,86) AND gr.concesionario_id = {$dealer_id} ORDER BY nombres ASC";
                break;

            default:
                $sql = "SELECT * FROM usuarios WHERE dealers_id = {$dealer_id} AND cargo_id IN (71,70) ORDER BY nombres ASC";
                break;
        }

        /* else{
          $sql = "SELECT * FROM usuarios WHERE dealers_id = {$dealer_id} AND cargo_id = 71 ORDER BY nombres ASC";
          } */

        //die($sql);
        $requestr1 = $con->createCommand($sql);
        $requestr1 = $requestr1->queryAll();
        $data = '<option value="">--Seleccione Asesor--</option>';
        //$data .= '<option value="all">Todos</option>';
        foreach ($requestr1 as $value) {
            $data .= '<option value="' . $value['id'] . '">';
            $data .= $this->getResponsableNombres($value['id']);
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

}
