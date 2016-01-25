<?php

class ReportesController extends Controller {

	public function actionInicio($id_informacion = null, $id_vehiculo = null) {
		date_default_timezone_set('America/Guayaquil'); 
        $dt = time();
        setlocale(LC_TIME, 'es_ES.UTF-8');

        $varView = array();

        $varView['grupo_id'] = (int) Yii::app()->user->getState('grupo_id');
        $varView['cargo_id'] = (int) Yii::app()->user->getState('cargo_id');
        $varView['id_responsable'] = Yii::app()->user->getId();
        $varView['dealer_id'] = $this->getDealerId($varView['id_responsable']);
        $varView['titulo'] = '';
        $varView['concesionario'] = 2000;
        $varView['tipos'] = null;

        $varView['fecha_actual'] = strftime("%Y-%m-%d", $dt);
        $varView['fecha_actual2'] = strtotime('+1 day', strtotime($varView['fecha_actual']));
        $varView['fecha_actual2'] = date('Y-m-d', $varView['fecha_actual2']);
        $varView['fecha_inicial_actual'] = (new DateTime('first day of this month'))->format('Y-m-d');
        $varView['fecha_anterior'] = strftime( "%Y-%m-%d", strtotime( '-1 month', $dt ) );
        $varView['fecha_inicial_anterior'] = strftime( "%Y-%m", strtotime( '-1 month', $dt ) ). '-01';
        $varView['nombre_mes_actual'] = strftime("%B - %Y", $dt);
        $varView['nombre_mes_anterior'] = strftime( "%B - %Y", strtotime( '-1 month', $dt ) );

        $con = Yii::app()->db;

        //GET MODELOS Y VERSIONES PARA BUSCADOR
        $sqlModelos_nv = "SELECT nombre_modelo, id_modelos from modelos";
        $requestModelos_nv = $con->createCommand($sqlModelos_nv);
        $varView['modelos_car'] = $requestModelos_nv->queryAll();

        //SI BUSCAN POR VERSION O MODELO Y RECIBE VARIABLES PARA LA CONSULTA
        $lista_datos = array();
        if (isset($_GET['modelo'])) {array_push($lista_datos, array('modelos' => $_GET['modelo']));}
        if (isset($_GET['version'])) {array_push($lista_datos, array('versiones' =>$_GET['version']));}

        $SQLmodelos = '';
        foreach ($lista_datos as $key => $value) {
            foreach ($value as $key => $carros) {
                if($key == 'modelos'){$campo_car= 'modelo';}
                else{$campo_car= 'version';}              
                $id_carros_nv[$key] = implode(', ', $carros);
                $SQLmodelos[$key] = " AND gi.".$campo_car." IN (".$id_carros_nv[$key].") ";
            }
        }

        //GET Asesores
        $mod = new GestionDiaria;
        $cre = new CDbCriteria();
        $varView['dealer_resp']= $this->getConcesionarioDealerId($varView['id_responsable']);
        if(!empty($dealer_resp)){
            $varView['dealer_id'] = $varView['dealer_resp'];
        }
        $cre->condition = " cargo_id = 71 AND dealers_id = ".$varView['dealer_id'];
        $cre->order = " nombres ASC";
        $usu = CHtml::listData(Usuarios::model()->findAll($cre), "id", "fullname");

        //variables busqueda por defecto
        $tit_ext = '';
        $join_ext = null;
        $group_ext = null;
        $select_ext = null;
        $tit_init = 'Búsqueda entre ';
        switch ($varView['cargo_id']) { 
            case 71: // asesor de ventas 
                $id_persona = "gi.responsable = ".$varView['id_responsable'];
                $tit_init = 'Búsqueda entre ';
                break;
            case 70: // jefe de sucursal 
                $id_persona = "gi.dealer_id = ".$varView['dealer_id'];
                $tit_init = 'Búsqueda entre ';              
                break;
            case 69: // GERENTE COMERCIAL
                $id_persona = 'u.grupo_id = '.$varView['grupo_id'];
                $tit_init = 'Búsqueda por defecto entre ';                
                $tit_ext = ', Grupo: ' . $this->getNombreGrupo($varView['grupo_id']);
                $join_ext = 'INNER JOIN usuarios u ON u.id = gi.responsable ';
                $group_ext = null;
                $select_ext = ', u.grupo_id ';
                break;
        }

       
        if (isset($_GET['GestionInformacion'])) {
            $tit_ext = '';            
            $tipo_busqueda_trafico2 = '';
            $tipo_busqueda_proforma2 = '';
            $tipo_busqueda_testdrive2 = '';
            $tipo_busqueda_ventas2 = '';

            //SET GET VARS
            $tip_us = $_GET['GestionInformacion']['tipousuario'];

            if (!empty($_GET['GestionDiaria']['responsable']) && $tip_us != 3) {
                $responsable = $_GET['GestionDiaria']['responsable'];
                $tit_ext = '. Asesor: ' . $this->getResponsableNombres($responsable);
                $id_persona = 'gi.responsable = '.$responsable;
                $id_persona .= " AND gi.dealer_id = ".$varView['dealer_id'].' ';
            }else if($tip_us != 1 && $tip_us != 3 && empty($_GET['GestionDiaria']['responsable'])){       
                $responsable = key($usu);
                $tit_ext .= '. Asesor: ' . reset($usu);
                $id_persona = 'gi.responsable = '.$responsable;
                $id_persona .= " AND gi.dealer_id = ".$varView['dealer_id'].' ';
            }else if($tip_us == 3 && !empty($_GET['GestionInformacion']['concesionario'])){
                $responsable = $_GET['GestionDiaria']['responsable'];
                if($responsable){
                    $id_persona .= " AND gi.responsable = ".$responsable.' ';
                    $tit_ext .= '. Asesor: ' . $this->getResponsableNombres($responsable);
                }

                $tit_ext .= '. Concesionario: ' . $this->getNameConcesionarioById($_GET['GestionInformacion']['concesionario']);
            }
                       

            //===========================VARIABLES para getSelectCKDCBU()================================
            if ($tip_us == 1) {
                $tipo_busqueda_trafico2 = 1;
                $tipo_busqueda_proforma2 = 2;
                $tipo_busqueda_testdrive2 = 3;
                $tipo_busqueda_ventas2 = 4;
             }else if (($tip_us == 2) && !empty($_GET['GestionDiaria']['responsable'])) {
                $tipo_busqueda_trafico2 = 5;
                $tipo_busqueda_proforma2 = 6;
                $tipo_busqueda_testdrive2 = 7;
                $tipo_busqueda_ventas2 = 8;

            }else if ($tip_us == 3 && !empty($_GET['GestionInformacion']['concesionario'])) {
                $tipo_busqueda_trafico2 = 9;
                $tipo_busqueda_proforma2 = 10;
                $tipo_busqueda_testdrive2 = 11;
                $tipo_busqueda_ventas2 = 12;
            }else if ($tip_us == 3 && (($_GET['GestionInformacion']['concesionario'] == 0) || empty($_GET['GestionInformacion']['concesionario'])) && empty($_GET['GestionDiaria']['responsable'])) {
                $tipo_busqueda_trafico2 = 13;
                $tipo_busqueda_proforma2 = 14;
                $tipo_busqueda_testdrive2 = 15;
                $tipo_busqueda_ventas2 = 16;
                $tit_ext .= '. Grupo: ' . $this->getNombreGrupo($varView['grupo_id']);
            }

            $varView['fecha1'] = explode(' - ', $_GET['GestionInformacion']['fecha1']);
            $varView['fecha2'] = explode(' - ', $_GET['GestionInformacion']['fecha2']);
            $responsable = 0;

            $varView['fecha_inicial_anterior'] = trim($varView['fecha1'][0]);
            $varView['fecha_anterior'] = trim($varView['fecha1'][1]);

            $varView['fecha_inicial_actual'] = trim($varView['fecha2'][0]);                
            $varView['fecha_actual'] = trim($varView['fecha2'][1]);            

            $varView['nombre_mes_actual'] = strftime( "%B - %Y", strtotime($varView['fecha_inicial_actual']));
            $varView['nombre_mes_anterior'] = strftime( "%B - %Y", strtotime($varView['fecha_inicial_anterior']));

            $tipos = array();
            array_push($tipos, $tipo_busqueda_trafico2, $tipo_busqueda_proforma2, $tipo_busqueda_testdrive2, $tipo_busqueda_ventas2);  
        }      
    
        $retorno = $this->buscar(
            $varView['cargo_id'], 
            $varView['id_responsable'], 
            $select_ext, 
            $join_ext, 
            $id_persona, 
            $group_ext, 
            $varView['fecha_inicial_anterior'], 
            $varView['fecha_anterior'], 
            $varView['fecha_inicial_actual'], 
            $varView['fecha_actual'], 
            $varView['$concesionario'], 
            $tipos, 
            $SQLmodelos
        );
        $varView['trafico_mes_anterior'] = $retorno[0];
        $varView['trafico_mes_actual'] = $retorno[1];
        $varView['vhcbu2'] = $retorno[23];
        $varView['vhckd2'] = $retorno[22];
        $varView['vhcbu1'] = $retorno[21];
        $varView['vhckd1'] = $retorno[20];
        $varView['vh_mes_actual']= $retorno[19];
        $varView['vh_mes_anterior'] = $retorno[18];
        $varView['tdcbu2'] = $retorno[17];
        $varView['tdckd2'] = $retorno[16];
        $varView['tdcbu1'] = $retorno[15];
        $varView['tdckd1'] = $retorno[14];
        $varView['td_mes_actual'] = $retorno[13];
        $varView['td_mes_anterior'] = $retorno[12];
        $varView['proformacbu2'] = $retorno[11];
        $varView['proformackd2'] = $retorno[10];
        $varView['proformacbu1'] = $retorno[9];
        $varView['proformackd1'] = $retorno[8];
        $varView['proforma_mes_actual'] = $retorno[7];
        $varView['proforma_mes_anterior'] = $retorno[6];
        $varView['traficocbu2'] = $retorno[5];
        $varView['traficockd2'] = $retorno[4];
        $varView['traficocbu1'] = $retorno[3];            
        $varView['traficockd1'] = $retorno[2];
        $varView['dif_ckd_trafico'] = $varView['traficockd2'] - $varView['traficockd1'];
        $varView['dif_cbu_trafico'] =  $varView['traficocbu2'] - $varView['traficocbu1'];
        $varView['lista_datos'] = $lista_datos;
        $varView['usu'] = $usu;
        $varView['mod'] = $mod;

        //set diferencias
        $varView['var_tr'] = $this->DIFconstructor($varView['trafico_mes_actual'], $varView['trafico_mes_anterior'], 'var');
        $varView['dif_tr'] = $this->DIFconstructor($varView['trafico_mes_actual'], $varView['trafico_mes_anterior'], 'dif');

        $varView['var_pr'] = $this->DIFconstructor($varView['proforma_mes_actual'], $varView['proforma_mes_anterior'], 'var');
        $varView['dif_pr'] = $this->DIFconstructor($varView['proforma_mes_actual'], $varView['proforma_mes_anterior'], 'dif');

        $varView['var_td'] = $this->DIFconstructor($varView['td_mes_actual'], $varView['td_mes_anterior'], 'var');
        $varView['dif_td'] = $this->DIFconstructor($varView['td_mes_actual'], $varView['td_mes_anterior'], 'dif');

        $varView['var_ve'] = $this->DIFconstructor($varView['vh_mes_actual'], $varView['vh_mes_anterior'], 'var');
        $varView['dif_ve'] = $this->DIFconstructor($varView['vh_mes_actual'], $varView['vh_mes_anterior'], 'dif');

        $varView['titulo'] = $tit_init. $fecha_inicial_actual . ' / ' . $fecha_actual . ', y ' . $fecha_inicial_anterior . ' / ' . $fecha_anterior.$tit_ext;

        $this->render('inicio', array('varView' => $varView));
    }

    
    function DIFconstructor($var1, $var2, $tipo){
        $dif = $var1 - $var2;
        $unidad = '';
        if($tipo == 'var'){
            $unidad = '%';
            if($var2 == 0){$var2 = $var1;}       
            $dif = ($dif * 100) / $var2;
            $dif = round($dif, 2);            
        }

        $resp = '<span';
        if ($dif >= 0) {
            $resp .= '>' . $dif.$unidad;
        } else {
            $resp .= ' class="dif">(' . abs($dif) . $unidad. ')';
        }
        $resp .= '</span>';

        return $resp;
    }

    function SQLconstructor($selection, $table, $join, $where, $group = null){
        $con = Yii::app()->db;
        $sql_cons = "SELECT {$selection} from {$table} {$join}
        WHERE {$where} {$group}";
        //echo $sql_cons.'<br><br>';

        $request_cons = $con->createCommand($sql_cons);
        return  $request_cons->queryAll();
    }

    function buscar($cargo_id, $id_responsable, $select_ext, $join_ext, $id_persona, $group_ext, $fecha_inicial_anterior, $fecha_anterior, $fecha_inicial_actual, $fecha_actual, $concesionario = 0, $tipos = null, $carros){
        $modelos = null;
        $versiones = null;
        if(!empty($carros['modelos'])){ $modelos = $carros['modelos'];  }
        if(!empty($carros['versiones'])){ $versiones = $carros['versiones'];}

        if(empty($tipos)){
            $tipos = array();
            array_push($tipos,1,2,3,4);       
        }

        $retorno = array();
        
        //BUSQUEDA POR TRAFICO      
        $trafico_mes_anterior = $this->SQLconstructor(
            'COUNT(*) '.$select_ext, 
            'gestion_informacion gi', 
            $join_ext, 
            $id_persona." AND DATE(gi.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."'".$modelos.$versiones, 
            $group_ext
        );
        $trafico_mes_anterior = $trafico_mes_anterior[0]['COUNT(*)'];
        $retorno[] = $trafico_mes_anterior;                               
        
        $trafico_mes_actual = $this->SQLconstructor(
            'COUNT(*) '.$select_ext, 
            'gestion_informacion gi', 
            $join_ext, 
            $id_persona." AND DATE(gi.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."'".$modelos.$versiones, 
            $group_ext
        );
        $trafico_mes_actual = $trafico_mes_actual[0]['COUNT(*)'];
        $retorno[] = $trafico_mes_actual;

        
        $traficockd1 = $this->SQLconstructor(
            'COUNT(*) '.$select_ext, 
            'gestion_informacion gi', 
            $join_ext.' LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id ', 
            "DATE(gi.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."' AND ".$id_persona.$modelos.$versiones." AND ((gv.modelo IN (24,21)) OR gi.modelo IN (24,21))", 
            $group_ext
        );
        $traficockd1 = $traficockd1[0]['COUNT(*)'];
        $retorno[] = $traficockd1;
        $traficocbu1 = $trafico_mes_anterior - $traficockd1; // resto de modelos
        $retorno[] = $traficocbu1;


        $traficockd2 = $this->SQLconstructor(
            'COUNT(*) '.$select_ext, 
            'gestion_informacion gi', 
            $join_ext.' LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id ', 
            "DATE(gi.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."' AND ".$id_persona.$modelos.$versiones." AND ((gv.modelo IN (24,21)) OR gi.modelo IN (24,21))", 
            $group_ext
        );
        $traficockd2 = $traficockd2[0]['COUNT(*)'];
        $retorno[] = $traficockd2;

        $traficocbu2 = $trafico_mes_actual - $traficockd2; // resto de modelos
        $retorno[] = $traficocbu2;


        // BUSQUEDA POR PROFORMA
        $proforma_mes_anterior = $this->SQLconstructor(
            'COUNT(*) '.$select_ext, 
            'gestion_financiamiento gf', 
            'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion '.$join_ext, 
            "DATE(gf.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."' AND ".$id_persona.$modelos.$versiones, 
            $group_ext
        );
        $proforma_mes_anterior = $proforma_mes_anterior[0]['COUNT(*)'];
        $retorno[] = $proforma_mes_anterior;        
       
        $proforma_mes_actual = $this->SQLconstructor(
            'COUNT(*) '.$select_ext, 
            'gestion_financiamiento gf', 
            'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion '.$join_ext, 
            "DATE(gf.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."' AND ".$id_persona.$modelos.$versiones, 
            $group_ext
        );                
        $proforma_mes_actual = $proforma_mes_actual[0]['COUNT(*)'];
        $retorno[] = $proforma_mes_actual;

        $proformackd1 = $this->SQLconstructor(
            'COUNT(*) '.$select_ext, 
            'gestion_financiamiento gf', 
            'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo'.$join_ext, 
            "DATE(gf.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."' AND ".$id_persona.$modelos.$versiones." AND ((gv.modelo IN (24,21)) OR gi.modelo IN (24,21))", 
            $group_ext
        );
        $proformackd1 = $proformackd1[0]['COUNT(*)'];
        $retorno[] = $proformackd1;

        $proformacbu1 = $this->SQLconstructor(
            'COUNT(*) '.$select_ext, 
            'gestion_financiamiento gf', 
            'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo'.$join_ext, 
            "DATE(gf.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."' AND ".$id_persona.$modelos.$versiones, 
            $group_ext
        );
        $proformacbu1 = ($proformacbu1[0]['COUNT(*)'] - $proformackd1);
        $retorno[] = $proformacbu1;


        $proformackd2 = $this->SQLconstructor(
            'COUNT(*) '.$select_ext, 
            'gestion_financiamiento gf', 
            'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo'.$join_ext, 
            "DATE(gf.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."' AND ".$id_persona.$modelos.$versiones." AND ((gv.modelo IN (24,21)) OR gi.modelo IN (24,21))", 
            $group_ext
        );
        $proformackd2 = $proformackd2[0]['COUNT(*)'];
        $retorno[] = $proformackd2;

        $proformacbu2 = $this->SQLconstructor(
            'COUNT(*) '.$select_ext, 
            'gestion_financiamiento gf', 
            'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo'.$join_ext, 
            "DATE(gf.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."' AND ".$id_persona.$modelos.$versiones, 
            $group_ext
        );
        $proformacbu2 = ($proformacbu2[0]['COUNT(*)'] - $proformackd2);
        $retorno[] = $proformacbu2;


        // BUSQUEDA POR TEST DRIVE
        $td_mes_anterior = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_test_drive  gt', 
            'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion '.$join_ext, 
            "gt.test_drive = 1 AND DATE(gt.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."' AND ".$id_persona.$modelos.$versiones, 
            $group_ext
        );
        $td_mes_anterior = $td_mes_anterior[0]['COUNT(*)'];
        $retorno[] = $td_mes_anterior;

        $td_mes_actual = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_test_drive  gt', 
            'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion '.$join_ext, 
            "gt.test_drive = 1 AND DATE(gt.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."' AND ".$id_persona.$modelos.$versiones, 
            $group_ext
        );
        $td_mes_actual = $td_mes_actual[0]['COUNT(*)'];
        $retorno[] = $td_mes_actual;

        $tdckd1 = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_test_drive  gt', 
            'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo'.$join_ext, 
            "gt.test_drive = 1 AND DATE(gt.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."' AND ".$id_persona.$modelos.$versiones." AND ((gv.modelo IN (24,21)) OR gi.modelo IN (24,21))", 
            $group_ext
        );
        $tdckd1 = $tdckd1[0]['COUNT(*)'];
        $retorno[] = $tdckd1;

        $tdcbu1 = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_test_drive  gt', 
            'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo'.$join_ext, 
            "gt.test_drive = 1 AND DATE(gt.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."' AND ".$id_persona.$modelos.$versiones, 
            $group_ext
        );
        $tdcbu1 = ($tdcbu1[0]['COUNT(*)'] - $tdckd1);
        $retorno[] = $tdcbu1;

        $tdckd2 = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_test_drive  gt', 
            'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo'.$join_ext, 
            "gt.test_drive = 1 AND DATE(gt.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."' AND ".$id_persona.$modelos.$versiones." AND ((gv.modelo IN (24,21)) OR gi.modelo IN (24,21))", 
            $group_ext
        );
        $tdckd2 = $tdckd2[0]['COUNT(*)'];
        $retorno[] = $tdckd2;

        $tdcbu2 = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_test_drive  gt', 
            'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo'.$join_ext, 
            "gt.test_drive = 1 AND DATE(gt.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."' AND ".$id_persona.$modelos.$versiones, 
            $group_ext
        );
        $tdcbu2 = ($tdcbu2[0]['COUNT(*)'] - $tdckd2);
        $retorno[] = $tdcbu2;

        // BUSQUEDA POR VENTAS 
        $vh_mes_anterior = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_diaria gd ', 
            'INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion '.$join_ext, 
            "gd.cierre = 1 AND (DATE(gd.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."') AND ".$id_persona.$modelos.$versiones, 
            $group_ext
        );
        
        $vh_mes_anterior = $vh_mes_anterior[0]['COUNT(*)'];
        $retorno[] = $vh_mes_anterior;
        
        $vh_mes_actual = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_diaria gd ', 
            'INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion '.$join_ext, 
            "gd.cierre = 1 AND (DATE(gd.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."') AND ".$id_persona.$modelos.$versiones, 
            $group_ext
        );
        $vh_mes_actual = $vh_mes_actual[0]['COUNT(*)'];
        $retorno[] = $vh_mes_actual;        

        $vhckd1 = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_diaria gd ', 
            'INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id_informacion  = gd.id_informacion  '.$join_ext, 
            "gd.cierre = 1 AND (DATE(gd.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."') AND ".$id_persona.$modelos.$versiones." AND ((gv.modelo IN (24,21)) OR gi.modelo IN (24,21))", 
            $group_ext
        );
        $vhckd1 = $vhckd1[0]['COUNT(*)'];
        $retorno[] = $vhckd1;

        $vhcbu1 = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_diaria gd ', 
            'INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion '.$join_ext, 
            "gd.cierre = 1 AND (DATE(gd.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."') AND ".$id_persona.$modelos.$versiones, 
            $group_ext
        );
        $vhcbu1 = ($vhcbu1[0]['COUNT(*)'] - $vhckd1);
        $retorno[] = $vhcbu1;


        $vhckd2 = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_diaria gd ', 
            'INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id_informacion  = gd.id_informacion  '.$join_ext, 
            "gd.cierre = 1 AND (DATE(gd.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."') AND ".$id_persona.$modelos.$versiones." AND ((gv.modelo IN (24,21)) OR gi.modelo IN (24,21))", 
            $group_ext
        );
        $vhckd2 = $vhckd2[0]['COUNT(*)'];
        $retorno[] = $vhckd2;

        $vhcbu2 = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_diaria gd ', 
            'INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion '.$join_ext, 
            "gd.cierre = 1 AND (DATE(gd.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."') AND ".$id_persona.$modelos.$versiones, 
            $group_ext
        );
        $vhcbu2 = ($vhcbu2[0]['COUNT(*)'] - $vhckd2);
        $retorno[] = $vhcbu2;

        return $retorno;
    }
}