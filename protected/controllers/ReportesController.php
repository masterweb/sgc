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
        $INERmodelos = '';
        $INERmodelos_td = '';
        $varView['triger'] = 0;
        foreach ($lista_datos as $key => $value) {
            foreach ($value as $key => $carros) {
                if($key == 'modelos'){$campo_car= 'modelo';}
                else{$campo_car= 'version';}              
                $id_carros_nv[$key] = implode(', ', $carros);
                $SQLmodelos[$key] = " AND gv.".$campo_car." IN (".$id_carros_nv[$key].") ";
                $INERmodelos = ' INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id ';
                $INERmodelos_td =' INNER JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo ';
                $varView['triger'] = 1;
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
            case 69: // GERENTE COMERCIAL EN CURSO----->
                $id_persona = 'u.grupo_id = '.$varView['grupo_id'];               
                $tit_ext = ', Grupo: ' . $this->getNombreGrupo($varView['grupo_id']);
                $join_ext = 'INNER JOIN usuarios u ON u.id = gi.responsable ';
                $varView['lista_conce'] = $this->getConcecionario($varView['grupo_id']);
                break;
            case 70: // jefe de sucursal 
                $id_persona = "gi.dealer_id = ".$varView['dealer_id'];            
                break;                
            case 71: // asesor de ventas TERMINADO------>
                $id_persona = "gi.responsable = ".$varView['id_responsable'];
                break; 
            case 72: //jefe BDC
                $id_persona = "gi.responsable = ".$varView['id_responsable'];
                break; 
            case 73: //asesor bdc
                $id_persona = "gi.responsable = ".$varView['id_responsable'];
                break; 
            case 75: //asesor exonerados
                $id_persona = "gi.responsable = ".$varView['id_responsable'];
                break; 
            case 76: //jefe usados
                $id_persona = "gi.responsable = ".$varView['id_responsable'];
                break; 
            case 77: //asesor usados
                $id_persona = "gi.responsable = ".$varView['id_responsable'];
                break;                                 
        }

         // procesamos las variables de busqueda del filtro
        $varView['js_responsable'] = 'null';
        if($_GET['GI']){            
            if($_GET['GI']['fecha1'] != ''){
                //echo('fecha1');
                $gi_fecha1 = explode(" - ", $_GET['GI']['fecha1']);
                $varView['fecha_inicial_actual'] = $gi_fecha1[0]; 
                $varView['fecha_actual'] = $gi_fecha1[1];
                $date = new DateTime($varView['fecha_actual']);
                $varView['nombre_mes_actual'] = $date->format('F - Y');             
            }            
            if($_GET['GI']['fecha2'] != ''){
                //echo('fecha2');
                $gi_fecha2 = explode(" - ", $_GET['GI']['fecha2']);
                $varView['fecha_inicial_anterior'] = $gi_fecha2[0];
                $varView['fecha_anterior'] = $gi_fecha2[1];
                $date = new DateTime($varView['fecha_anterior']);
                $varView['nombre_mes_anterior'] = $date->format('F - Y');
            }
            if($_GET['GI']['responsable'] != ''){
                //echo('responsable');
                $varView['id_responsable'] = $_GET['GI']['responsable'];
                $id_persona = "gi.responsable = ".$varView['id_responsable'];
                $varView['js_responsable'] = $varView['id_responsable'];
            }
            if($_GET['GI']['concesionario'] != ''){
                $varView['$concesionario'] = $_GET['GI']['concesionario'];
                if($_GET['GI']['responsable'] == ''){
                    $id_persona = "gi.dealer_id = ".$varView['$concesionario'];
                }                
            }
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
            $varView['concesionario'], 
            $tipos, 
            $SQLmodelos,
            $INERmodelos,
            $INERmodelos_td
        );

        $varView['trafico_mes_anterior'] = $retorno[0];
        $varView['trafico_mes_actual'] = $retorno[1];               
        $varView['traficockd1'] = $retorno[2];
        $varView['traficocbu1'] = $retorno[3];
        $varView['traficockd2'] = $retorno[4];
        $varView['traficocbu2'] = $retorno[5];
        $varView['proforma_mes_anterior'] = $retorno[6];
        $varView['proforma_mes_actual'] = $retorno[7];
        $varView['proformackd1'] = $retorno[8];
        $varView['proformacbu1'] = $retorno[9];
        $varView['proformackd2'] = $retorno[10];
        $varView['proformacbu2'] = $retorno[11];
        $varView['td_mes_anterior'] = $retorno[12];
        $varView['td_mes_actual'] = $retorno[13];
        $varView['tdckd1'] = $retorno[14];
        $varView['tdcbu1'] = $retorno[15];
        $varView['tdckd2'] = $retorno[16];
        $varView['tdcbu2'] = $retorno[17];
        $varView['vh_mes_anterior'] = $retorno[18];
        $varView['vh_mes_actual']= $retorno[19];
        $varView['vhckd1'] = $retorno[20];
        $varView['vhcbu1'] = $retorno[21];
        $varView['vhckd2'] = $retorno[22];
        $varView['vhcbu2'] = $retorno[23];
        $varView['lista_datos'] = $lista_datos;             
        
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

        //set Tasas
        //tasa proformas
        $varView['tasa_proforma_actual'] = $this->tasa($varView['trafico_mes_actual'], $varView['proforma_mes_actual']);
        $varView['tasa_proforma_anterior'] = $this->tasa($varView['trafico_mes_anterior'], $varView['proforma_mes_anterior']);
        $varView['tasa_dif_proforma'] = $this->tasa_dif($varView['tasa_proforma_actual'], $varView['tasa_proforma_anterior']);

        //tasa test drive
        $varView['tasa_testdrive_actual'] = $this->tasa($varView['trafico_mes_actual'], $varView['td_mes_actual']);
        $varView['tasa_testdrive_anterior'] = $this->tasa($varView['trafico_mes_anterior'], $varView['td_mes_anterior']);
        $varView['tasa_dif_testdrive'] = $this->tasa_dif($varView['tasa_testdrive_actual'], $varView['tasa_testdrive_anterior']);

         //tasa cierre
        $varView['tasa_cierre_actual'] = $this->tasa($varView['trafico_mes_actual'], $varView['vh_mes_actual']);
        $varView['tasa_cierre_anterior'] = $this->tasa($varView['trafico_mes_anterior'], $varView['vh_mes_anterior']);
        $varView['tasa_dif_cierre'] = $this->tasa_dif($varView['tasa_cierre_actual'], $varView['tasa_cierre_anterior']);

        //tasa proformas ckd y cbu
        $varView['tasa_proforma_ckd_m1'] = $this->tasa($varView['traficockd2'], $varView['proformackd2']);
        $varView['tasa_proforma_cbu_m1'] = $this->tasa($varView['traficocbu2'], $varView['proformacbu2']);
        $varView['tasa_proforma_ckd_m2'] = $this->tasa($varView['traficockd1'], $varView['proformackd1']);
        $varView['tasa_proforma_cbu_m2'] = $this->tasa($varView['traficocbu1'], $varView['proformacbu1']);

        //tasa testdrive ckd y cbu
        $varView['tasa_testdrive_ckd_m1'] = $this->tasa($varView['traficockd2'], $varView['tdckd2']);
        $varView['tasa_testdrive_cbu_m1'] = $this->tasa($varView['traficocbu2'], $varView['tdcbu2']); 
        $varView['tasa_testdrive_ckd_m2'] = $this->tasa($varView['traficockd1'], $varView['tdckd1']);
        $varView['tasa_testdrive_cbu_m2'] = $this->tasa($varView['traficocbu1'], $varView['tdcbu1']); 

        //tasa cierre ckd y cbu
        $varView['tasa_cierre_ckd_m1'] = $this->tasa($varView['traficockd2'], $varView['vhckd2']);
        $varView['tasa_cierre_cbu_m1'] = $this->tasa($varView['traficocbu2'], $varView['vhcbu2']);
        $varView['tasa_cierre_ckd_m2'] = $this->tasa($varView['traficockd1'], $varView['vhckd1']);
        $varView['tasa_cierre_cbu_m2'] = $this->tasa($varView['traficocbu1'], $varView['vhcbu1']);  

        $this->render('inicio', array('varView' => $varView));
    }

    public function actionAjaxGetAsesores() {
        $dealer_id = isset($_POST["dealer_id"]) ? $_POST["dealer_id"] : "";
        $resposable = isset($_POST["resposable"]) ? $_POST["resposable"] : "";
        $cargo_id = (int) Yii::app()->user->getState('cargo_id');
        $con = Yii::app()->db;

        if($cargo_id == 69 || $cargo_id == 70){
            $sql = "SELECT * FROM usuarios WHERE dealers_id = {$dealer_id} AND cargo_id IN (71,70) ORDER BY nombres ASC";
        
            $request = $con->createCommand($sql);
            $request = $request->queryAll();

            $data = '<option value="">--Seleccione Asesor--</option>';
            foreach ($request as $value) {
                $data .= '<option value="' . $value['id'].'" ';
                if($resposable == $value['id']){
                    $data .= 'selected';
                }
                $data .= '>'.$this->getResponsableNombres($value['id']);
                $data .= '</option>';
            }

            echo $data;
        }
    }

    function getConcecionario($grupo_id){
        $info = new GestionInformacion;
        $con = Yii::app()->db;
        $sql = "SELECT * FROM gr_concesionarios WHERE id_grupo = ".$grupo_id." ORDER BY nombre ASC";                
        $requestr1 = $con->createCommand($sql);                
        return $requestr1->queryAll();
    }

    function tasa($var1, $var2){
        $resp = 0;
        if($var1 > 0){
            $resp = ($var2 / $var1) * 100;
            $resp = round($resp, 2);
            return $resp . ' %';
        }else{
            return '0 %';
        }
    }

    function tasa_dif($var1, $var2){
        $dfpr = $var1 - $var2;
        if($df > 0){
            return $dfpr.' %';
        }else{
            return '<span class="dif">('.abs($dfpr).' %)</span>';
        }
    }
    
    function DIFconstructor($var1, $var2, $tipo){
        $dif = $var1 - $var2;
        $unidad = '';        
        if($tipo == 'var'){
            $unidad = '%';
            if($var2 == 0){
                if($var1 != 0){
                    $var2 = $var1;
                }else{
                    $dif = 100;
                    $var2 = 100;
                }                
            }      
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

    function buscar($cargo_id, $id_responsable, $select_ext, $join_ext, $id_persona, $group_ext, $fecha_inicial_anterior, $fecha_anterior, $fecha_inicial_actual, $fecha_actual, $concesionario = 0, $tipos = null, $carros, $INERmodelos, $INERmodelos_td){
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
            $join_ext.$INERmodelos, 
            $id_persona." AND DATE(gi.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."' ".$modelos.$versiones, 
            $group_ext
        );
        $trafico_mes_anterior = $trafico_mes_anterior[0]['COUNT(*)'];
        $retorno[] = $trafico_mes_anterior;                                      
        
        $trafico_mes_actual = $this->SQLconstructor(
            'COUNT(*) '.$select_ext, 
            'gestion_informacion gi', 
            $join_ext.$INERmodelos, 
            $id_persona." AND DATE(gi.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."' ".$modelos.$versiones, 
            $group_ext
        );
        $trafico_mes_actual = $trafico_mes_actual[0]['COUNT(*)'];
        $retorno[] = $trafico_mes_actual;
        
        $traficockd1 = $this->SQLconstructor(
            'COUNT(*) '.$select_ext, 
            'gestion_informacion gi', 
            $join_ext.' LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id ', 
            "DATE(gi.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."' AND ".$id_persona." AND ((gv.modelo IN (24,21)) OR gi.modelo IN (24,21))", 
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
            "DATE(gi.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."' AND ".$id_persona.' '.$modelos.$versiones." AND ((gv.modelo IN (24,21)) OR gi.modelo IN (24,21))", 
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
            'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion '.$join_ext.$INERmodelos, 
            "DATE(gf.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."' AND ".$id_persona.' '.$modelos.$versiones, 
            $group_ext
        );
        $proforma_mes_anterior = $proforma_mes_anterior[0]['COUNT(*)'];
        $retorno[] = $proforma_mes_anterior;        
       
        $proforma_mes_actual = $this->SQLconstructor(
            'COUNT(*) '.$select_ext, 
            'gestion_financiamiento gf', 
            'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion '.$join_ext.$INERmodelos, 
            "DATE(gf.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."' AND ".$id_persona.' '.$modelos.$versiones, 
            $group_ext
        );                
        $proforma_mes_actual = $proforma_mes_actual[0]['COUNT(*)'];
        $retorno[] = $proforma_mes_actual;

        $proformackd1 = $this->SQLconstructor(
            'COUNT(*) '.$select_ext, 
            'gestion_financiamiento gf', 
            'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo '.$join_ext, 
            "DATE(gf.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."' AND ".$id_persona.' '.$modelos.$versiones." AND ((gv.modelo IN (24,21)) OR gi.modelo IN (24,21))", 
            $group_ext
        );
        $proformackd1 = $proformackd1[0]['COUNT(*)'];
        $retorno[] = $proformackd1;

        $proformacbu1 = $this->SQLconstructor(
            'COUNT(*) '.$select_ext, 
            'gestion_financiamiento gf', 
            'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo '.$join_ext, 
            "DATE(gf.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."' AND ".$id_persona.' '.$modelos.$versiones, 
            $group_ext
        );
        $proformacbu1 = ($proformacbu1[0]['COUNT(*)'] - $proformackd1);
        $retorno[] = $proformacbu1;


        $proformackd2 = $this->SQLconstructor(
            'COUNT(*) '.$select_ext, 
            'gestion_financiamiento gf', 
            'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo '.$join_ext, 
            "DATE(gf.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."' AND ".$id_persona.' '.$modelos.$versiones." AND ((gv.modelo IN (24,21)) OR gi.modelo IN (24,21))", 
            $group_ext
        );
        $proformackd2 = $proformackd2[0]['COUNT(*)'];
        $retorno[] = $proformackd2;

        $proformacbu2 = $this->SQLconstructor(
            'COUNT(*) '.$select_ext, 
            'gestion_financiamiento gf', 
            'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo '.$join_ext, 
            "DATE(gf.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."' AND ".$id_persona.' '.$modelos.$versiones, 
            $group_ext
        );
        $proformacbu2 = ($proformacbu2[0]['COUNT(*)'] - $proformackd2);
        $retorno[] = $proformacbu2;

        // BUSQUEDA POR TEST DRIVE
        $td_mes_anterior = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_test_drive  gt', 
            'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion '.$join_ext.$INERmodelos_td, 
            "gt.test_drive = 1 AND DATE(gt.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."' AND ".$id_persona.' '.$modelos.$versiones, 
            $group_ext
        );
        $td_mes_anterior = $td_mes_anterior[0]['COUNT(*)'];
        $retorno[] = $td_mes_anterior;

        $td_mes_actual = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_test_drive  gt', 
            'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion '.$join_ext.$INERmodelos_td, 
            "gt.test_drive = 1 AND DATE(gt.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."' AND ".$id_persona.' '.$modelos.$versiones, 
            $group_ext
        );
        $td_mes_actual = $td_mes_actual[0]['COUNT(*)'];
        $retorno[] = $td_mes_actual;

        $tdckd1 = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_test_drive  gt', 
            'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo '.$join_ext, 
            "gt.test_drive = 1 AND DATE(gt.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."' AND ".$id_persona.' '.$modelos.$versiones." AND ((gv.modelo IN (24,21)) OR gi.modelo IN (24,21))", 
            $group_ext
        );
        $tdckd1 = $tdckd1[0]['COUNT(*)'];
        $retorno[] = $tdckd1;

        $tdcbu1 = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_test_drive  gt', 
            'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo '.$join_ext, 
            "gt.test_drive = 1 AND DATE(gt.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."' AND ".$id_persona.' '.$modelos.$versiones, 
            $group_ext
        );
        $tdcbu1 = ($tdcbu1[0]['COUNT(*)'] - $tdckd1);
        $retorno[] = $tdcbu1;

        $tdckd2 = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_test_drive  gt', 
            'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo '.$join_ext, 
            "gt.test_drive = 1 AND DATE(gt.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."' AND ".$id_persona.' '.$modelos.$versiones." AND ((gv.modelo IN (24,21)) OR gi.modelo IN (24,21))", 
            $group_ext
        );
        $tdckd2 = $tdckd2[0]['COUNT(*)'];
        $retorno[] = $tdckd2;

        $tdcbu2 = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_test_drive  gt', 
            'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo '.$join_ext, 
            "gt.test_drive = 1 AND DATE(gt.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."' AND ".$id_persona.' '.$modelos.$versiones, 
            $group_ext
        );
        $tdcbu2 = ($tdcbu2[0]['COUNT(*)'] - $tdckd2);
        $retorno[] = $tdcbu2;

        // BUSQUEDA POR VENTAS 
        $vh_mes_anterior = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_diaria gd ', 
            'INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion '.$join_ext.$INERmodelos, 
            "gd.cierre = 1 AND (DATE(gd.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."') AND ".$id_persona.' '.$modelos.$versiones, 
            $group_ext
        );
        
        $vh_mes_anterior = $vh_mes_anterior[0]['COUNT(*)'];
        $retorno[] = $vh_mes_anterior;
        
        $vh_mes_actual = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_diaria gd ', 
            'INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion '.$join_ext.$INERmodelos, 
            "gd.cierre = 1 AND (DATE(gd.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."') AND ".$id_persona.' '.$modelos.$versiones, 
            $group_ext
        );
        $vh_mes_actual = $vh_mes_actual[0]['COUNT(*)'];
        $retorno[] = $vh_mes_actual;        

        $vhckd1 = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_diaria gd ', 
            'INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id_informacion  = gd.id_informacion  '.$join_ext, 
            "gd.cierre = 1 AND (DATE(gd.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."') AND ".$id_persona.' '.$modelos.$versiones." AND ((gv.modelo IN (24,21)) OR gi.modelo IN (24,21))", 
            $group_ext
        );
        $vhckd1 = $vhckd1[0]['COUNT(*)'];
        $retorno[] = $vhckd1;

        $vhcbu1 = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_diaria gd ', 
            'INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion '.$join_ext.$INERmodelos, 
            "gd.cierre = 1 AND (DATE(gd.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."') AND ".$id_persona.' '.$modelos.$versiones, 
            $group_ext
        );
        $vhcbu1 = ($vhcbu1[0]['COUNT(*)'] - $vhckd1);
        $retorno[] = $vhcbu1;

        $vhckd2 = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_diaria gd ', 
            'INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id_informacion  = gd.id_informacion  '.$join_ext, 
            "gd.cierre = 1 AND (DATE(gd.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."') AND ".$id_persona.' '.$modelos.$versiones." AND ((gv.modelo IN (24,21)) OR gi.modelo IN (24,21))", 
            $group_ext
        );
        $vhckd2 = $vhckd2[0]['COUNT(*)'];
        $retorno[] = $vhckd2;

        $vhcbu2 = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_diaria gd ', 
            'INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion '.$join_ext.$INERmodelos, 
            "gd.cierre = 1 AND (DATE(gd.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."') AND ".$id_persona.' '.$modelos.$versiones, 
            $group_ext
        );
        $vhcbu2 = ($vhcbu2[0]['COUNT(*)'] - $vhckd2);
        $retorno[] = $vhcbu2;

        return $retorno;
    }
}