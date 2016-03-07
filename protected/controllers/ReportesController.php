<?php
require_once( dirname(__FILE__) . '/../components/Reportes/TraficoAcumulado.php');
require_once( dirname(__FILE__) . '/../components/Reportes/TraficoUsados.php');

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
        $varView['nombre_usuario'] = Usuarios::model()->findByPk($varView['id_responsable']);
        $varView['cargo_usuario'] = Cargo::model()->findByPk($varView['nombre_usuario']->cargo_id);
        if($varView['nombre_usuario']->dealers_id != ''){
            $varView['consecionario_usuario'] = '<b>Concesionario:</b> '.GrConcesionarios::model()->find('dealer_id='.$varView['nombre_usuario']->dealers_id)['nombre'];
        }
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
        $varView['lista_datos'] = $lista_datos;

        $traficousados = new TraficoUsados;
        $varView['filtro_modelos_us'] = $traficousados->GetModelos('2015-12-01', '2015-12-31', '2015-11-01', '2015-11-30');
        //variables busqueda por defecto
        $tit_ext = '';
        $join_ext = null;
        $group_ext = null;
        $select_ext = null;
        $tit_init = 'Búsqueda entre ';
        $varView['AEKIA'] = false;
       
        //TIPOS DE USUARIO QUE VEN REPORTES
        //MODIFICAR LA SELECCION DE RESPONSABLES
        switch ($varView['cargo_id']) {
            case 4: // GERENTE GENERAL
            case 45: // SUBGERENCIA GENERAL
            case 46: // SUPER ADMINISTRADOR
            case 48: // GERENTE MARKETING
            case 57: // INTELIGENCIA DE MERCADO MARKETING
            case 58: // JEFE DE PRODUCTO MARKETING
            case 60: // GERENTE VENTAS
            case 61: // JEFE DE RED VENTAS
            case 62: // SUBGERENTE DE FLOTAS VENTAS
                $id_persona = 'gi.responsable '; 
                $varView['lista_grupo'] = Grupo::model()->findAll();                
                $varView['lista_provincias'] = Provincias::model()->findAll();
                $varView['lista_conce'] = 'null';
                $varView['AEKIA'] = true;
                $varView['consecionario_usuario'] = '<b>Grupo:</b> TODOS';
                //TRAFICO ACUMULADO
                if($_GET['TA']['provincia']  != ''){
                    $TAactivo = $_GET['TA']['provincia'];
                }else{
                    $TAactivo = $_GET['TA']['grupo'];
                }
                $varView['TAresp_activo'] = $_GET['TA']['concesionarios'];
                 if($_GET['TA']['modelo'] != ''){
                    $TAmodelo = $_GET['TA']['modelo'];
                }
                
                $traficoAcumulado = new traficoAcumulado;
                $varView['traficoAcumulado']['ini_filtros'] = $traficoAcumulado->ini_filtros($TAactivo, $TAmodelo);
                break;
            case 69: // GERENTE COMERCIAL EN CURSO TERMINADO----->
                $id_persona = 'u.grupo_id = '.$varView['grupo_id'];               
                $join_ext = 'INNER JOIN usuarios u ON u.id = gi.responsable ';
                $varView['lista_conce'] = $this->getConcecionario($varView['grupo_id']);
                $varView['consecionario_usuario'] = '<b>Grupo:</b> '.$this->getNombreGrupo($varView['grupo_id']);
                break;
            case 70: // jefe de sucursal TERMINADO------>
                $id_persona = "gi.dealer_id = ".$varView['dealer_id'];            
                break;                
            case 71: // asesor de ventas TERMINADO------>
                $id_persona = "gi.responsable = ".$varView['id_responsable'];
                break; 
            case 72: //jefe BDC y exonerados TERMINADO------> PROBAR
                $id_persona = "gi.dealer_id = ".$varView['dealer_id'].' AND (gi.bdc = 1 OR gi.tipo_form_web = "exonerados") ';
                break; 
            case 73: //asesor bdc TERMINADO------> PROBAR
                $id_persona = "gi.responsable = ".$varView['id_responsable'].' AND gi.bdc = 1 ';
                break; 
            case 75: //asesor exonerados TERMINADO------> PROBAR
                $id_persona = "gi.responsable = ".$varView['id_responsable'].' AND gi.tipo_form_web = "exonerados" ';
                break; 
            case 76: //jefe usados TERMINADO------> PROBAR
                $id_persona = "gi.dealer_id = ".$varView['dealer_id'].' AND gi.tipo_form_web = "usado" ';
                break; 
            case 77: //asesor usados TERMINADO------> PROBAR
                $id_persona = "gi.responsable = ".$varView['id_responsable'].' AND gi.tipo_form_web = "usado" ';
                break;                                 
        }

         // procesamos las variables de busqueda del filtro
        $varView['js_responsable'] = 'null';
        $consultaBDC = '';
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
                $varView["js_dealer"] = $_GET['GI']['concesionario'];
                if($_GET['GI']['responsable'] == ''){
                    $id_persona = "gi.dealer_id = ".$varView['$concesionario'];
                }                
            }
            if($_GET['GI']['tipo_t'] != ''){
                if($_GET['GI']['tipo_t'] == 'provincias'){
                    $varView['checked_p']  = true; 
                    $varView['checked_g']  = false;
                    $varView['id_provincia'] = $_GET['GI']['provincias'];                    
                }else{
                    $varView['id_grupo'] = $_GET['GI']['grupo'];
                    $varView['checked_g']  = true;
                    $varView['checked_p'] = false;
                }                      
            }
            if($_GET['GI']['tipo'] != ''){
               if($_GET['GI']['tipo'] == 'traficoacumulado'){
                    $varView['checked_ta'] = true;
                    $varView['checked_ge']  = false; 
                    $varView['checked_us']  = false;
                    $varView['checked_ex']  = false;
                    $varView['checked_bdc']  = false;
                }else if($_GET['GI']['tipo'] == 'general'){
                    $varView['checked_ta'] = false;
                    $varView['checked_ge']  = true; 
                    $varView['checked_us']  = false;
                    $varView['checked_ex']  = false;
                    $varView['checked_bdc']  = false;
                }else if($_GET['GI']['tipo'] == 'usados'){
                    $varView['checked_ta'] = false;
                    $varView['checked_ge']  = false; 
                    $varView['checked_us']  = true;
                    $varView['checked_ex']  = false;
                    $varView['checked_bdc']  = false;
                }else if($_GET['GI']['tipo'] == 'bdc'){
                    $varView['checked_ta'] = false;
                    $varView['checked_ge']  = false; 
                    $varView['checked_us']  = false;
                    $varView['checked_ex']  = false;
                    $varView['checked_bdc']  = true;
                    $consultaBDC = " AND gi.bdc = 1 ";
                } else if($_GET['GI']['tipo'] == 'exonerados'){
                    $varView['checked_ta'] = false;
                    $varView['checked_ge']  = false; 
                    $varView['checked_us']  = false;
                    $varView['checked_ex']  = true;
                    $varView['checked_bdc']  = false;
                    $consultaBDC = " AND gi.tipo_ex IS NOT NULL ";
                }                   
            }

            
        }else{
                $varView['checked_g'] = true;
                $varView['checked_ge']  = true; 
        }

        //GET Modelos activos en rango de fechas
        $modelos_ma = $this->getModleosActivos($varView['fecha_inicial_anterior'], $varView['fecha_anterior'], $varView['fecha_inicial_actual'], $varView['fecha_actual'], $varView['lista_datos'], 'general');
        $varView['filtro_modelos'] = $modelos_ma;

        //Check if TRAFICO ACUMLADO ESTA ACTIVO
        $varView['TA'] = false;
        $varView['TAchecked_gp'] = 'p';
        if($_GET['GI']['tipo'] == 'traficoacumulado'){
            $varView['TA'] = true;
            $varView['TAconsulta'] = [];
             if($_GET['TA']['modelo'] != ''){
                $varView['TAmodelo'] = $_GET['TA']['modelo'];
            }
            if($_GET['TA']['concesionarios'] != ''){
                $varView['TAconcesionarios'] = $_GET['TA']['concesionarios'];
                $varView['TAconsulta']['concesionarios'] = $_GET['TA']['concesionarios'];
            }
            if($_GET['TA']['provincia'] != ''){
                $varView['TAprovincia'] = $_GET['TA']['provincia'];
                $varView['TAconsulta']['provincia']  = $_GET['TA']['provincia'];
               $varView['TAchecked_gp'] = 'p';
            }
            if($_GET['TA']['grupo'] != ''){
                $varView['TAgrupo'] = $_GET['TA']['grupo'];
                $varView['TAconsulta']['grupo'] = $_GET['TA']['grupo'];
               $varView['TAchecked_gp'] = 'g';
            }

            $traficoAcumulado = new traficoAcumulado;
            $fechas[0] = $varView['fecha_inicial_anterior'];
            $fechas[1] = $varView['fecha_anterior'];
            $fechas[2] = $varView['fecha_inicial_actual'];
            $fechas[3] = $varView['fecha_actual'];

            $retorno = $traficoAcumulado->buscar($varView['TAconsulta'], $varView['TAmodelo'], $fechas);
            $contador = [];
            $ckd = array(
                'CERATO MT', 
                'CERATO AT', 
                'SPORTAGE CKD LX TM', 
                'SPORTAGE R MT', 
                'SPORTAGE R AT', 
                'RIO CKD', 
                'RIO R 4P', 
                'PREGIO GRAND'
            );
            foreach ($retorno['mant'] as $tipo) {
                if($tipo['tipo'] == 'TRAFICO'){
                    $contador['trafico_mant'][] = $tipo['tipo'];
                    if(in_array($tipo['modelo'], $ckd)){
                        $contador['trafico_ckd_mant'][] = $tipo['modelo'];
                    }
                }else if($tipo['tipo'] == 'TESTDRIVE'){
                    $contador['testdrive_mant'][] = $tipo['tipo'];
                    if(in_array($tipo['modelo'], $ckd)){
                        $contador['testdrive_ckd_mant'][] = $tipo['modelo'];
                    }
                }else if($tipo['tipo'] == 'PROFORMA'){
                    $contador['proforma_mant'][] = $tipo['tipo'];
                    if(in_array($tipo['modelo'], $ckd)){
                        $contador['proforma_ckd_mant'][] = $tipo['modelo'];
                    }
                }else if($tipo['tipo'] == 'VENTAS'){
                    $contador['ventas_mant'][] = $tipo['tipo'];
                    if(in_array($tipo['modelo'], $ckd)){
                        $contador['ventas_ckd_mant'][] = $tipo['modelo'];
                    }
                }
            }

            foreach ($retorno['mact'] as $tipo) {
                if($tipo['tipo'] == 'TRAFICO'){
                    $contador['trafico_mact'][] = $tipo['tipo'];
                     if(in_array($tipo['modelo'], $ckd)){
                        $contador['trafico_ckd_mact'][] = $tipo['modelo'];
                    }
                }else if($tipo['tipo'] == 'TESTDRIVE'){
                    $contador['testdrive_mact'][] = $tipo['tipo'];
                    if(in_array($tipo['modelo'], $ckd)){
                        $contador['testdrive_ckd_mact'][] = $tipo['modelo'];
                    }
                }else if($tipo['tipo'] == 'PROFORMA'){
                    $contador['proforma_mact'][] = $tipo['tipo'];
                    if(in_array($tipo['modelo'], $ckd)){
                        $contador['proforma_ckd_mact'][] = $tipo['modelo'];
                    }
                }else if($tipo['tipo'] == 'VENTAS'){
                    $contador['ventas_mact'][] = $tipo['tipo'];
                    if(in_array($tipo['modelo'], $ckd)){
                        $contador['ventas_ckd_mact'][] = $tipo['modelo'];
                    }
                }
            }

            $varView['trafico_mes_anterior'] = count($contador['trafico_mant']);
            $varView['trafico_mes_actual'] = count($contador['trafico_mact']);
            $varView['traficockd1'] = count($contador['trafico_ckd_mant']);
            $varView['traficocbu1'] = count($contador['trafico_mant']) - count($contador['trafico_ckd_mant']);
            $varView['traficockd2'] = count($contador['trafico_ckd_mact']);
            $varView['traficocbu2'] = count($contador['trafico_mact']) - count($contador['trafico_ckd_mact']);            
            $varView['proforma_mes_anterior'] = count($contador['proforma_mant']);
            $varView['proforma_mes_actual'] = count($contador['proforma_mact']);
            $varView['proformackd1'] = count($contador['proforma_ckd_mant']);
            $varView['proformacbu1'] = count($contador['proforma_mant']) - count($contador['proforma_ckd_mant']);
            $varView['proformackd2'] = count($contador['proforma_ckd_mact']);
            $varView['proformacbu2'] = count($contador['proforma_mact']) - count($contador['proforma_ckd_mact']);
            $varView['td_mes_anterior'] = count($contador['testdrive_mant']);
            $varView['td_mes_actual'] = count($contador['testdrive_mact']);
            $varView['tdckd1'] = count($contador['testdrive_ckd_mant']);
            $varView['tdcbu1'] = count($contador['testdrive_mant']) - count($contador['testdrive_ckd_mant']);
            $varView['tdckd2'] = count($contador['testdrive_ckd_mact']);
            $varView['tdcbu2'] = count($contador['testdrive_mact']) - count($contador['testdrive_ckd_mact']);
            $varView['vh_mes_anterior'] = count($contador['ventas_mant']);
            $varView['vh_mes_actual']= count($contador['ventas_mact']);
            $varView['vhckd1'] = count($contador['ventas_ckd_mant']);
            $varView['vhcbu1'] = count($contador['ventas_mant']) - count($contador['ventas_ckd_mant']);
            $varView['vhckd2'] = count($contador['ventas_ckd_mact']);
            $varView['vhcbu2'] = count($contador['ventas_mact']) - count($contador['ventas_ckd_mact']); 
        }else{
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
                $INERmodelos_td,
                $consultaBDC
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
        }

        $varView['dif_ckd_trafico'] = $varView['traficockd1'] - $varView['traficockd2'];
        $varView['dif_cbu_trafico'] = $varView['traficocbu1'] - $varView['traficocbu2'];
        //$varView['usu'] = $usu;
        //$varView['mod'] = $mod;      

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

        //set diferencias ckd y cbu
        $varView['tasa_td_dif_ckd'] = rtrim($varView['tasa_testdrive_ckd_m2'], "%") - rtrim($varView['tasa_testdrive_ckd_m1'], "%");
        $varView['tasa_td_dif_cbu'] = rtrim($varView['tasa_testdrive_cbu_m2'], "%") - rtrim($varView['tasa_testdrive_cbu_m1'], "%");
        $varView['tasa_pr_dif_ckd'] = rtrim($varView['tasa_proforma_ckd_m2'], "%") - rtrim($varView['tasa_proforma_ckd_m1'], "%");
        $varView['tasa_pr_dif_cbu'] = rtrim($varView['tasa_proforma_cbu_m2'], "%") - rtrim($varView['tasa_proforma_cbu_m1'], "%");
        $varView['tasa_cierre_dif_ckd'] = rtrim($varView['tasa_cierre_ckd_m2'], "%") - rtrim($varView['tasa_cierre_ckd_m1'], "%");
        $varView['tasa_cierre_dif_cbu'] = rtrim($varView['tasa_cierre_cbu_m2'], "%") - rtrim($varView['tasa_cierre_cbu_m1'], "%");

        $this->render('inicio', array('varView' => $varView));
    }

    public function getModleosActivos($fecha1_1, $fecha1_2, $fecha2_1, $fecha2_2, $lista_datos, $tipo_b){

        $con = Yii::app()->db;


        //controlador de tipo de busqueda
        $bdcs = '';
        if($tipo_b == 'bdc'){
            $extra_where = '';
            $extra_where = "SELECT id FROM gestion_informacion WHERE bdc = 1 AND DATE(fecha) BETWEEN '".$fecha1_1."' AND '".$fecha1_2."' OR DATE(fecha) BETWEEN '".$fecha2_1."' AND '".$fecha2_2."'";
            $request_BDC = $con->createCommand($extra_where);
            $request_BDC = $request_BDC->queryAll();
            $bdcs = '';
            foreach ($request_BDC as $key2 => $value2) {
                $bdcs .= $value2['id'].', ';
            }
            $bdcs = rtrim($bdcs, ", ");
            $bdcs = ' id_informacion IN ('.$bdcs.') AND ';
        }else if($tipo_b == 'exonerados'){
            $extra_where = '';
            $extra_where = "SELECT id FROM gestion_informacion WHERE tipo_ex IS NOT NULL AND DATE(fecha) BETWEEN '".$fecha1_1."' AND '".$fecha1_2."' OR DATE(fecha) BETWEEN '".$fecha2_1."' AND '".$fecha2_2."'";
            $request_BDC = $con->createCommand($extra_where);
            $request_BDC = $request_BDC->queryAll();
            $bdcs = '';
            foreach ($request_BDC as $key2 => $value2) {
                $bdcs .= $value2['id'].', ';
            }
            $bdcs = rtrim($bdcs, ", ");
            $bdcs = ' id_informacion IN ('.$bdcs.') AND ';
        }             

        $sql_modelos_act = "SELECT distinct modelo FROM gestion_vehiculo WHERE ".$bdcs."DATE(fecha) BETWEEN '".$fecha1_1."' AND '".$fecha1_2."' OR DATE(fecha) BETWEEN '".$fecha2_1."' AND '".$fecha2_2."'";

        $request_ma = $con->createCommand($sql_modelos_act);
        $request_ma = $request_ma->queryAll();
        $modelos_ma = '';
        foreach ($request_ma as $id_modelo) {
            $modelos_ma .= $id_modelo['modelo'].', ';
        }
        $modelos_ma = rtrim($modelos_ma, ", ");
        if($modelos_ma != ''){
            $modelos_ma = "AND id_modelos IN (".$modelos_ma.")";
        }

        //GET MODELOS Y VERSIONES PARA BUSCADOR
        $sqlModelos_nv = "SELECT nombre_modelo, id_modelos from modelos WHERE active = 1 ".$modelos_ma;
        $requestModelos_nv = $con->createCommand($sqlModelos_nv);
        $varView['modelos_car'] = $requestModelos_nv->queryAll();

        $activos = array();
        $filtro_modelos_main = '<div class="row"><input class="checkboxmain" type="checkbox" value="activo" name="todos" id="todos"> <label><b>Todos</b></label></div>';
        
        foreach ($varView['modelos_car'] as $key => $value) {
            $checked = '';
            if ($lista_datos) {
                if (in_array($value['id_modelos'], $lista_datos[0]['modelos'])) {
                    $activos[] = $value['id_modelos'];
                    $checked = 'checked';
                }
            }                                     
            $filtro_modelos = '<div class="col-md-4 modelo">
                        <div class="checkbox contcheck">
                            <div class="checkmodelo col-md-1">
                                <span style="display:none;">'.$value['nombre_modelo'].'</span>
                                <input class="checkboxmain" type="checkbox" value="'.$value['id_modelos'].'" name="modelo[]" id="cc'.$value['id_modelos'].'" '.$checked.'>
                            </div>
                            <div class="model_info col-md-10">
                                <label>                                
                                    '.$value['nombre_modelo'].'
                                </label>
                                <div id="result" class="result">'.
                                    $this->getVersiones($value['id_modelos'], $lista_datos[1]['versiones'])
                                .'</div>
                            </div>
                        </div>
                    </div>';

            $filtro_modelos_main .= $filtro_modelos;
        }

        if($modelos_ma == ''){
            $filtro_modelos_main = 'No exiten modelos activos para este rango de fechas - Seleccione un nuevo rango de fechas';
        }
        return $filtro_modelos_main;  
    }

    public function actionAjaxGetModelos() {
        //FECHAS RENDER
        $fecha1 = isset($_POST["fecha1"]) ? $_POST["fecha1"] : "";
        $fecha1 = explode(" - ", $fecha1);

        $fecha2 = isset($_POST["fecha2"]) ? $_POST["fecha2"] : "";
        $fecha2 = explode(" - ", $fecha2);

        $tipo_b = isset($_POST["tipo_b"]) ? $_POST["tipo_b"] : "";

        $modelos_ma = $this->getModleosActivos($fecha1[0], $fecha1[1], $fecha2[0], $fecha2[1], null, $tipo_b);
        echo ''.$modelos_ma;
    }

    public function actionAjaxGetAsesores() {
        $dealer_id = isset($_POST["dealer_id"]) ? $_POST["dealer_id"] : "";
        $resposable = isset($_POST["resposable"]) ? $_POST["resposable"] : "";
        $tipo_b = isset($_POST["tipo_b"]) ? $_POST["tipo_b"] : "";

        //FECHAS RENDER
        $fecha1 = isset($_POST["fecha1"]) ? $_POST["fecha1"] : "";
        $fecha1 = explode(" - ", $fecha1);

        $fecha2 = isset($_POST["fecha2"]) ? $_POST["fecha2"] : "";
        $fecha2 = explode(" - ", $fecha2);

        //controlador de tipo de busqueda
        $extra_where = '';
        if($tipo_b == 'bdc'){
            $extra_where = " bdc = 1 AND ";
            //echo $extra_where;
        }else if($tipo_b == 'exonerados'){
            $extra_where = " tipo_ex IS NOT NULL AND ";
        }

        //GET asesores activos en rango de fechas
        $con_aa = Yii::app()->db;
        $sql_asesores_act = "SELECT distinct responsable FROM gestion_informacion WHERE ".$extra_where." DATE(fecha) BETWEEN '".$fecha1[0]."' AND '".$fecha1[1]."' OR DATE(fecha) BETWEEN '".$fecha2[0]."' AND '".$fecha2[1]."'";           
        echo $sql_asesores_act;
        $request_aa = $con_aa->createCommand($sql_asesores_act);
        $request_aa = $request_aa->queryAll();


        if(!empty($request_aa)){
            $asesores_aa = '';
            foreach ($request_aa as $id_asesor) {
                if($id_asesor['responsable'] != ''){
                    $asesores_aa .= $id_asesor['responsable'].', ';
                }
                
            }
            $asesores_aa = rtrim($asesores_aa, ", ");
            //FIN

            $cargo_id = (int) Yii::app()->user->getState('cargo_id');
            $con = Yii::app()->db;

            if( $cargo_id == 69 || 
                $cargo_id == 70 || 
                $cargo_id == 4 || 
                $cargo_id == 45 ||
                $cargo_id == 46 ||
                $cargo_id == 48 ||
                $cargo_id == 57 ||
                $cargo_id == 58 ||
                $cargo_id == 60 ||
                $cargo_id == 61 ||
                $cargo_id == 62){
                $sql = "SELECT * FROM usuarios WHERE dealers_id = {$dealer_id} AND cargo_id IN (70, 71, 72, 73, 75, 76, 77) AND id IN (".$asesores_aa.") ORDER BY nombres ASC";
                $request = $con->createCommand($sql);
                $request = $request->queryAll();

                if(empty($request)){
                    $data = '<option value=""> No hay asesores activos en este rango de fechas</option>';
                }else{
                    $data = '<option value="">--Seleccione Asesor--</option>'; 
                }
                
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
        }else{
            $data = '<option value=""> No hay asesores activos en este rango de fechas</option>';
            echo $data;
        }
    }

     public function actionAjaxGetDealers() {
        $grupo_id = isset($_POST["grupo_id"]) ? $_POST["grupo_id"] : "";
        $active  = isset($_POST["dealer"]) ? $_POST["dealer"] : "";
        $tipo  = isset($_POST["tipo"]) ? $_POST["tipo"] : "";
        $tipo_b = isset($_POST["tipo_b"]) ? $_POST["tipo_b"] : "";
        $con = Yii::app()->db;
        //FECHAS RENDER
        $fecha1 = isset($_POST["fecha1"]) ? $_POST["fecha1"] : "";
        $fecha1 = explode(" - ", $fecha1);

        $fecha2 = isset($_POST["fecha2"]) ? $_POST["fecha2"] : "";
        $fecha2 = explode(" - ", $fecha2);

        //controlador de tipo de busqueda
        $extra_where = '';
        if($tipo_b == 'bdc'){
            $extra_where = " bdc = 1 AND ";
            //echo $extra_where;
        }else if($tipo_b == 'exonerados'){
            $extra_where = " tipo_ex IS NOT NULL AND ";
        }

        $sql = "SELECT distinct dealer_id FROM gestion_informacion WHERE ".$extra_where." DATE(fecha) BETWEEN '".$fecha1[0]."' AND '".$fecha1[1]."' OR DATE(fecha) BETWEEN '".$fecha2[0]."' AND '".$fecha2[1]."' ORDER BY dealer_id ASC";
        echo $sql;
        $request = $con->createCommand($sql);
        $request = $request->queryAll();
        foreach ($request as $id_concesionario) {
            $concesionario .= $id_concesionario['dealer_id'].', ';
        }
        $concesionario = rtrim($concesionario, ", ");
        
        if(!empty($concesionario)){
            $concesionario = " dealer_id IN (".$concesionario.") ";
        }
        //echo $concesionario;

        if($tipo == 'p'){
            $where = "provincia = {$grupo_id} AND ";
        }else{
            $where = "id_grupo = {$grupo_id} AND ";
        }
        $sql = "SELECT distinct nombre, dealer_id FROM gr_concesionarios WHERE ".$where.$concesionario." ORDER BY nombre ASC";
        $request = $con->createCommand($sql);
        $request = $request->queryAll();

        $data = '<option value="">--Seleccione Concesionario--</option>';
        foreach ($request as $value) {
            if($value['nombre'] != 'TODOS'){
                $data .= '<option value="' . $value['dealer_id'].'" ';
                    if($active == $value['dealer_id']){
                        $data .= 'selected';
                    }
                $data .= '>'.$value['nombre'];
                $data .= '</option>';
            }
        }

        echo $data;
    }

    public function actionAjaxGetProvincias() {
        //FECHAS RENDER
        $active  = isset($_POST["active"]) ? $_POST["active"] : "";
        $fecha1 = isset($_POST["fecha1"]) ? $_POST["fecha1"] : "";
        $tipo_b = isset($_POST["tipo_b"]) ? $_POST["tipo_b"] : "";
        $fecha1 = explode(" - ", $fecha1);

        $fecha2 = isset($_POST["fecha2"]) ? $_POST["fecha2"] : "";
        $fecha2 = explode(" - ", $fecha2);

        //GET asesores activos en rango de fechas

        //controlador de tipo de busqueda
        $extra_where = '';
        if($tipo_b == 'bdc'){
            $extra_where = " bdc = 1 AND ";
            //echo $extra_where;
        }else if($tipo_b == 'exonerados'){
            $extra_where = " tipo_ex IS NOT NULL AND ";
        }

        $con = Yii::app()->db;
        $sql = "SELECT distinct provincia_conc FROM gestion_informacion WHERE ".$extra_where." DATE(fecha) BETWEEN '".$fecha1[0]."' AND '".$fecha1[1]."' OR DATE(fecha) BETWEEN '".$fecha2[0]."' AND '".$fecha2[1]."'";           
        echo $sql;
        $request = $con->createCommand($sql);
        $request = $request->queryAll();

        if(!empty($request)){
            $provincias = '';
            foreach ($request as $id_asesor) {
                $provincias.= $id_asesor['provincia_conc'].', ';
            }
            $provincias = rtrim($provincias, ", ");
            //FIN

            $cargo_id = (int) Yii::app()->user->getState('cargo_id');
            $con = Yii::app()->db;

            if( $cargo_id == 69 || 
                $cargo_id == 70 || 
                $cargo_id == 4 || 
                $cargo_id == 45 ||
                $cargo_id == 46 ||
                $cargo_id == 48 ||
                $cargo_id == 57 ||
                $cargo_id == 58 ||
                $cargo_id == 60 ||
                $cargo_id == 61 ||
                $cargo_id == 62){
                $sql = "SELECT * FROM provincias WHERE id_provincia IN (".$provincias.") ORDER BY nombre ASC";

                $request = $con->createCommand($sql);
                $request = $request->queryAll();

                if(empty($request)){
                    $data = '<option value=""> No hay Provincias activas en este rango de fechas</option>';
                }else{
                    $data = '<option value="">--Seleccione Provincia--</option>'; 
                }
                
                foreach ($request as $value) {
                    $data .= '<option value="' . $value['id_provincia'].'" ';
                    if($active == $value['id_provincia']){
                        $data .= 'selected';
                    }
                    $data .= '>'.$value['nombre'];
                    $data .= '</option>';
                }

                echo $data;
            }
        }else{
            $data = '<option value=""> No hay Provincias activas en este rango de fechas</option>';
            echo $data;
        }
    }

    public function actionAjaxGetGrupo() {
        //FECHAS RENDER
        $active  = isset($_POST["active"]) ? $_POST["active"] : "";
        $fecha1 = isset($_POST["fecha1"]) ? $_POST["fecha1"] : "";
        $tipo_b = isset($_POST["tipo_b"]) ? $_POST["tipo_b"] : "";
        $fecha1 = explode(" - ", $fecha1);

        $fecha2 = isset($_POST["fecha2"]) ? $_POST["fecha2"] : "";
        $fecha2 = explode(" - ", $fecha2);

        //GET concesionarios activos en rango de fechas

        //controlador de tipo de busqueda
       //controlador de tipo de busqueda
        $extra_where = '';
        if($tipo_b == 'bdc'){
            $extra_where = " bdc = 1 AND ";
            //echo $extra_where;
        }else if($tipo_b == 'exonerados'){
            $extra_where = " tipo_ex IS NOT NULL AND ";
        }

        $con = Yii::app()->db;
        $sql = "SELECT distinct dealer_id FROM gestion_informacion WHERE".$extra_where." DATE(fecha) BETWEEN '".$fecha1[0]."' AND '".$fecha1[1]."' OR DATE(fecha) BETWEEN '".$fecha2[0]."' AND '".$fecha2[1]."'";           
        $request = $con->createCommand($sql);
        $request = $request->queryAll();

        if(!empty($request)){
            $grupos = '';
            foreach ($request as $grupo) {
                $grupos .= $grupo['dealer_id'].', ';
            }
            $grupos = rtrim($grupos, ", ");

            //GET grupos activos en rango de fechas
            $con = Yii::app()->db;
            $sql = "SELECT distinct id_grupo FROM gr_concesionarios WHERE dealer_id IN (".$grupos.") ORDER BY nombre ASC";           
            $request2 = $con->createCommand($sql);
            $request2 = $request2->queryAll();

            $grupos = '';
            foreach ($request2 as $grupo) {
                $grupos .= $grupo['id_grupo'].', ';
            }
            $grupos = rtrim($grupos, ", ");
        }
        
        if(!empty($request2)){
            $cargo_id = (int) Yii::app()->user->getState('cargo_id');
            $con = Yii::app()->db;

            if( $cargo_id == 69 || 
                $cargo_id == 70 || 
                $cargo_id == 4 || 
                $cargo_id == 45 ||
                $cargo_id == 46 ||
                $cargo_id == 48 ||
                $cargo_id == 57 ||
                $cargo_id == 58 ||
                $cargo_id == 60 ||
                $cargo_id == 61 ||
                $cargo_id == 62){
                $sql = "SELECT * FROM gr_grupo WHERE id IN (".$grupos.") ORDER BY nombre_grupo ASC";
            
                $request = $con->createCommand($sql);
                $request = $request->queryAll();

                if(empty($request2)){
                    $data = '<option value=""> No hay grupos activos en este rango de fechas</option>';
                }else{
                    $data = '<option value="">--Seleccione Grupo--</option>'; 
                }
                
                foreach ($request as $value) {
                    $data .= '<option value="' . $value['id'].'" ';
                    if($active == $value['id']){
                        $data .= 'selected';
                    }
                    $data .= '>'. $value['nombre_grupo'];
                    $data .= '</option>';
                }

                echo $data;
            }
        }else{
            $data = '<option value=""> No hay grupo activos en este rango de fechas</option>';
            echo $data;
        }
    }

    function getVersiones($id, $versiones) {
        $con = Yii::app()->db;

        $sqlModelos_nv = "SELECT id_versiones, id_modelos, nombre_version from versiones WHERE id_modelos = '{$id}'";
        $requestModelos_nv = $con->createCommand($sqlModelos_nv);
        $versiones_car = $requestModelos_nv->queryAll();

        $resp = '<ul class="versiones">';
        foreach ($versiones_car as $key => $value) {
            if ($versiones) {
                if (in_array($value['id_versiones'], $versiones)) {
                    $checked = 'checked';
                }
            }    
            $resp .= '<li><input class="subcheckbox" type="checkbox" name="version[]" value="' . $value['id_versiones'] . '" '.$checked.'>' . $value['nombre_version'] . '</li>';
        }
        $resp .= '</ul>';

        return $resp;
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
        $dfpr = $var2 - $var1;
        if($dfpr >= 0){
            return $dfpr.' %';
        }else{
            return '<span class="dif">-'.abs($dfpr).' %</span>';
        }
    }
    
    function DIFconstructor($var1, $var2, $tipo){
        $dif = $var2 - $var1;

        if($dif < 0){
            $divisor = $var1;
        }else{
            $divisor = $var2; 
        }
        $unidad = '';        
        if($tipo == 'var'){
            $unidad = '%';
            if($divisor == 0){
                if($var1 != 0){
                    $divisor = $var1;
                }else{
                    $dif = 100;
                    $divisor = 100;
                }                
            }     
            $dif = ($dif * 100) / $divisor;            
            $dif = round($dif, 2);
        }

        if($var1 == 0 && $var2 == 0){$dif = 0;}

        $resp = '<span';
        if ($dif >= 0) {
            $resp .= '>' . $dif.$unidad;
        } else {
            $resp .= ' class="dif">-' . abs($dif) . $unidad;
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

    function buscar($cargo_id, $id_responsable, $select_ext, $join_ext, $id_persona, $group_ext, $fecha_inicial_anterior, $fecha_anterior, $fecha_inicial_actual, $fecha_actual, $concesionario = 0, $tipos = null, $carros, $INERmodelos, $INERmodelos_td, $consultaBDC){
        if(!empty($carros['modelos']) || !empty($carros['versiones'])){
            $datos_solo_modelos = $this->renderBusqueda($cargo_id, $id_responsable, $select_ext, $join_ext, $id_persona, $group_ext, $fecha_inicial_anterior, $fecha_anterior, $fecha_inicial_actual, $fecha_actual, $concesionario, $tipos, $carros, $INERmodelos, $INERmodelos_td, $consultaBDC);
            if($_GET['todos']){
                $datos_enteros = $this->renderBusqueda($cargo_id, $id_responsable, $select_ext, $join_ext, $id_persona, $group_ext, $fecha_inicial_anterior, $fecha_anterior, $fecha_inicial_actual, $fecha_actual, $concesionario, $tipos, null, null, null, $consultaBDC); 
                $varView[0] = ($datos_enteros[0] - $datos_solo_modelos[0]) + $datos_solo_modelos[0];
                $varView[1] = ($datos_enteros[1] - $datos_solo_modelos[1]) + $datos_solo_modelos[1];               
                $varView[2] = ($datos_enteros[2] - $datos_solo_modelos[2]) + $datos_solo_modelos[2];
                $varView[3] = ($datos_enteros[3] - $datos_solo_modelos[3]) + $datos_solo_modelos[3];
                $varView[4] = ($datos_enteros[4] - $datos_solo_modelos[4]) + $datos_solo_modelos[4];
                $varView[5] = ($datos_enteros[5] - $datos_solo_modelos[5]) + $datos_solo_modelos[5];
                $varView[6] = ($datos_enteros[6] - $datos_solo_modelos[6]) + $datos_solo_modelos[6];
                $varView[7] = ($datos_enteros[7] - $datos_solo_modelos[7]) + $datos_solo_modelos[7];
                $varView[8] = ($datos_enteros[8] - $datos_solo_modelos[8]) + $datos_solo_modelos[8];
                $varView[9] = ($datos_enteros[9] - $datos_solo_modelos[9]) + $datos_solo_modelos[9];
                $varView[10] = ($datos_enteros[10] - $datos_solo_modelos[10]) + $datos_solo_modelos[10];
                $varView[11] = ($datos_enteros[11] - $datos_solo_modelos[11]) + $datos_solo_modelos[11];
                $varView[12] = ($datos_enteros[12] - $datos_solo_modelos[12]) + $datos_solo_modelos[12];
                $varView[13] = ($datos_enteros[13] - $datos_solo_modelos[13]) + $datos_solo_modelos[13];
                $varView[14] = ($datos_enteros[14] - $datos_solo_modelos[14]) + $datos_solo_modelos[14];
                $varView[15] = ($datos_enteros[15] - $datos_solo_modelos[15]) + $datos_solo_modelos[15];
                $varView[16] = ($datos_enteros[16] - $datos_solo_modelos[16]) + $datos_solo_modelos[16];
                $varView[17] = ($datos_enteros[17] - $datos_solo_modelos[17]) + $datos_solo_modelos[17];
                $varView[18] = ($datos_enteros[18] - $datos_solo_modelos[18]) + $datos_solo_modelos[18];
                $varView[19] = ($datos_enteros[19] - $datos_solo_modelos[19]) + $datos_solo_modelos[19];
                $varView[20] = ($datos_enteros[20] - $datos_solo_modelos[20]) + $datos_solo_modelos[20];
                $varView[21] = ($datos_enteros[21] - $datos_solo_modelos[21]) + $datos_solo_modelos[21];
                $varView[22] = ($datos_enteros[22] - $datos_solo_modelos[22]) + $datos_solo_modelos[22];
                $varView[23] = ($datos_enteros[23] - $datos_solo_modelos[23]) + $datos_solo_modelos[23];
            }else{
                $varView = $datos_solo_modelos;
            }
        }else{
            $varView = $this->renderBusqueda($cargo_id, $id_responsable, $select_ext, $join_ext, $id_persona, $group_ext, $fecha_inicial_anterior, $fecha_anterior, $fecha_inicial_actual, $fecha_actual, $concesionario, $tipos, $carros, $INERmodelos, $INERmodelos_td, $consultaBDC);
        } 
        return $varView;     
    }

    function renderBusqueda($cargo_id, $id_responsable, $select_ext, $join_ext, $id_persona, $group_ext, $fecha_inicial_anterior, $fecha_anterior, $fecha_inicial_actual, $fecha_actual, $concesionario = 0, $tipos = null, $carros, $INERmodelos, $INERmodelos_td, $consultaBDC){
        $CKDs = Yii::app()->db->createCommand()->select('id_modelos')->from('modelos')->where("ensamblaje = 'CKD' AND active = 1")->queryAll();
        $CKDsRender = '';
        foreach ($CKDs as $key => $value) {
            $CKDsRender .= $value['id_modelos'].', '; 
        }
        $CKDsRender = rtrim($CKDsRender, ", ");

        $modelos = null;
        $versiones = null;
        if(!empty($carros['modelos'])){ $modelos = $carros['modelos'];}
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
            $id_persona." AND DATE(gi.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."' ".$modelos.$versiones.$consultaBDC, 
            $group_ext
        );
        $trafico_mes_anterior = $trafico_mes_anterior[0]['COUNT(*)'];
        $retorno[] = $trafico_mes_anterior;                                      
        
        $trafico_mes_actual = $this->SQLconstructor(
            'COUNT(*) '.$select_ext, 
            'gestion_informacion gi', 
            $join_ext.$INERmodelos, 
            $id_persona." AND DATE(gi.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."' ".$modelos.$versiones.$consultaBDC, 
            $group_ext
        );
        $trafico_mes_actual = $trafico_mes_actual[0]['COUNT(*)'];
        $retorno[] = $trafico_mes_actual;
        

            $traficockd1 = $this->SQLconstructor(
                'COUNT(*) '.$select_ext, 
                'gestion_informacion gi', 
                $join_ext.' LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id ', 
                "DATE(gi.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."' AND ".$id_persona." AND ((gv.modelo IN (".$CKDsRender.")) OR gi.modelo IN (".$CKDsRender."))".$consultaBDC, 
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
                "DATE(gi.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."' AND ".$id_persona.' '.$modelos.$versiones." AND ((gv.modelo IN (".$CKDsRender.")) OR gi.modelo IN (".$CKDsRender."))".$consultaBDC, 
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
            "DATE(gf.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."' AND ".$id_persona.' '.$modelos.$versiones.$consultaBDC, 
            $group_ext
        );
        $proforma_mes_anterior = $proforma_mes_anterior[0]['COUNT(*)'];
        $retorno[] = $proforma_mes_anterior;        
       
        $proforma_mes_actual = $this->SQLconstructor(
            'COUNT(*) '.$select_ext, 
            'gestion_financiamiento gf', 
            'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion '.$join_ext.$INERmodelos, 
            "DATE(gf.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."' AND ".$id_persona.' '.$modelos.$versiones.$consultaBDC, 
            $group_ext
        );                
        $proforma_mes_actual = $proforma_mes_actual[0]['COUNT(*)'];
        $retorno[] = $proforma_mes_actual;


            $proformackd1 = $this->SQLconstructor(
                'COUNT(*) '.$select_ext, 
                'gestion_financiamiento gf', 
                'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo '.$join_ext, 
                "DATE(gf.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."' AND ".$id_persona.' '.$modelos.$versiones." AND ((gv.modelo IN (".$CKDsRender.")) OR gi.modelo IN (".$CKDsRender."))".$consultaBDC, 
                $group_ext
            );
            $proformackd1 = $proformackd1[0]['COUNT(*)'];
            $retorno[] = $proformackd1;

            $proformacbu1 = $this->SQLconstructor(
                'COUNT(*) '.$select_ext, 
                'gestion_financiamiento gf', 
                'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo '.$join_ext, 
                "DATE(gf.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."' AND ".$id_persona.' '.$modelos.$versiones.$consultaBDC, 
                $group_ext
            );
            $proformacbu1 = ($proformacbu1[0]['COUNT(*)'] - $proformackd1);
            $retorno[] = $proformacbu1;


            $proformackd2 = $this->SQLconstructor(
                'COUNT(*) '.$select_ext, 
                'gestion_financiamiento gf', 
                'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo '.$join_ext, 
                "DATE(gf.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."' AND ".$id_persona.' '.$modelos.$versiones." AND ((gv.modelo IN (".$CKDsRender.")) OR gi.modelo IN (".$CKDsRender."))".$consultaBDC, 
                $group_ext
            );
            $proformackd2 = $proformackd2[0]['COUNT(*)'];
            $retorno[] = $proformackd2;

            $proformacbu2 = $this->SQLconstructor(
                'COUNT(*) '.$select_ext, 
                'gestion_financiamiento gf', 
                'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo '.$join_ext, 
                "DATE(gf.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."' AND ".$id_persona.' '.$modelos.$versiones.$consultaBDC, 
                $group_ext
            );
            $proformacbu2 = ($proformacbu2[0]['COUNT(*)'] - $proformackd2);
            $retorno[] = $proformacbu2;
        
        // BUSQUEDA POR TEST DRIVE
        $td_mes_anterior = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_test_drive  gt', 
            'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion '.$join_ext.$INERmodelos_td, 
            "gt.test_drive = 1 AND DATE(gt.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."' AND ".$id_persona.' '.$modelos.$versiones.$consultaBDC, 
            $group_ext
        );
        $td_mes_anterior = $td_mes_anterior[0]['COUNT(*)'];
        $retorno[] = $td_mes_anterior;

        $td_mes_actual = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_test_drive  gt', 
            'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion '.$join_ext.$INERmodelos_td, 
            "gt.test_drive = 1 AND DATE(gt.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."' AND ".$id_persona.' '.$modelos.$versiones.$consultaBDC, 
            $group_ext
        );
        $td_mes_actual = $td_mes_actual[0]['COUNT(*)'];
        $retorno[] = $td_mes_actual;

    
            $tdckd1 = $this->SQLconstructor(
                'COUNT(*) ', 
                'gestion_test_drive  gt', 
                'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo '.$join_ext, 
                "gt.test_drive = 1 AND DATE(gt.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."' AND ".$id_persona.' '.$modelos.$versiones." AND ((gv.modelo IN (".$CKDsRender.")) OR gi.modelo IN (".$CKDsRender."))".$consultaBDC, 
                $group_ext
            );
            $tdckd1 = $tdckd1[0]['COUNT(*)'];
            $retorno[] = $tdckd1;

            $tdcbu1 = $this->SQLconstructor(
                'COUNT(*) ', 
                'gestion_test_drive  gt', 
                'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo '.$join_ext, 
                "gt.test_drive = 1 AND DATE(gt.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."' AND ".$id_persona.' '.$modelos.$versiones.$consultaBDC, 
                $group_ext
            );
            $tdcbu1 = ($tdcbu1[0]['COUNT(*)'] - $tdckd1);
            $retorno[] = $tdcbu1;

            $tdckd2 = $this->SQLconstructor(
                'COUNT(*) ', 
                'gestion_test_drive  gt', 
                'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo '.$join_ext, 
                "gt.test_drive = 1 AND DATE(gt.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."' AND ".$id_persona.' '.$modelos.$versiones." AND ((gv.modelo IN (".$CKDsRender.")) OR gi.modelo IN (".$CKDsRender."))".$consultaBDC, 
                $group_ext
            );
            $tdckd2 = $tdckd2[0]['COUNT(*)'];
            $retorno[] = $tdckd2;

            $tdcbu2 = $this->SQLconstructor(
                'COUNT(*) ', 
                'gestion_test_drive  gt', 
                'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo '.$join_ext, 
                "gt.test_drive = 1 AND DATE(gt.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."' AND ".$id_persona.' '.$modelos.$versiones.$consultaBDC, 
                $group_ext
            );
            $tdcbu2 = ($tdcbu2[0]['COUNT(*)'] - $tdckd2);
            $retorno[] = $tdcbu2;
        

        // BUSQUEDA POR VENTAS 
        $vh_mes_anterior = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_diaria gd ', 
            'INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion '.$join_ext.$INERmodelos, 
            "gd.cierre = 1 AND (DATE(gd.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."') AND ".$id_persona.' '.$modelos.$versiones.$consultaBDC, 
            $group_ext
        );
        
        $vh_mes_anterior = $vh_mes_anterior[0]['COUNT(*)'];
        $retorno[] = $vh_mes_anterior;
        
        $vh_mes_actual = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_diaria gd ', 
            'INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion '.$join_ext.$INERmodelos, 
            "gd.cierre = 1 AND (DATE(gd.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."') AND ".$id_persona.' '.$modelos.$versiones.$consultaBDC, 
            $group_ext
        );
        $vh_mes_actual = $vh_mes_actual[0]['COUNT(*)'];
        $retorno[] = $vh_mes_actual;        

        
            $vhckd1 = $this->SQLconstructor(
                'COUNT(*) ', 
                'gestion_diaria gd ', 
                'INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id_informacion  = gd.id_informacion  '.$join_ext, 
                "gd.cierre = 1 AND (DATE(gd.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."') AND ".$id_persona.' '.$modelos.$versiones." AND ((gv.modelo IN (".$CKDsRender.")) OR gi.modelo IN (".$CKDsRender."))".$consultaBDC, 
                $group_ext
            );
            $vhckd1 = $vhckd1[0]['COUNT(*)'];
            $retorno[] = $vhckd1;

            $vhcbu1 = $this->SQLconstructor(
                'COUNT(*) ', 
                'gestion_diaria gd ', 
                'INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion '.$join_ext.$INERmodelos, 
                "gd.cierre = 1 AND (DATE(gd.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."') AND ".$id_persona.' '.$modelos.$versiones.$consultaBDC, 
                $group_ext
            );
            $vhcbu1 = ($vhcbu1[0]['COUNT(*)'] - $vhckd1);
            $retorno[] = $vhcbu1;

            $vhckd2 = $this->SQLconstructor(
                'COUNT(*) ', 
                'gestion_diaria gd ', 
                'INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id_informacion  = gd.id_informacion  '.$join_ext, 
                "gd.cierre = 1 AND (DATE(gd.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."') AND ".$id_persona.' '.$modelos.$versiones." AND ((gv.modelo IN (".$CKDsRender.")) OR gi.modelo IN (".$CKDsRender."))".$consultaBDC, 
                $group_ext
            );
            $vhckd2 = $vhckd2[0]['COUNT(*)'];
            $retorno[] = $vhckd2;

            $vhcbu2 = $this->SQLconstructor(
                'COUNT(*) ', 
                'gestion_diaria gd ', 
                'INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion '.$join_ext.$INERmodelos, 
                "gd.cierre = 1 AND (DATE(gd.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."') AND ".$id_persona.' '.$modelos.$versiones.$consultaBDC, 
                $group_ext
            );
            $vhcbu2 = ($vhcbu2[0]['COUNT(*)'] - $vhckd2);
            $retorno[] = $vhcbu2;
        
        return $retorno;
    }

    //TRAFICO ACUMULADO
    public function actionAjaxGetConsecionariosTA() {
        $where = isset($_POST["where"]) ? $_POST["where"] : "";
        $fecha1 = isset($_POST["fecha1"]) ? $_POST["fecha1"] : "";
        $fecha2 = isset($_POST["fecha2"]) ? $_POST["fecha2"] : "";
        $TAresp_activo = isset($_POST["TAresp_activo"]) ? $_POST["TAresp_activo"] : "";
        $traficoAcumulado = new traficoAcumulado;
        $traficoAcumulado->ConcesionariosTA($where, $fecha1, $fecha2, $TAresp_activo);
    }
}