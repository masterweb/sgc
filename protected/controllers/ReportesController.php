<?php
require_once( dirname(__FILE__) . '/../components/Reportes/ConstructorSQL.php');
require_once( dirname(__FILE__) . '/../components/Reportes/Modelos.php');
require_once( dirname(__FILE__) . '/../components/Reportes/AjaxCalls.php');
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
        $varView['provincia_id'] = $varView['nombre_usuario']->provincia_id;
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
                if($key == 'modelos'){
                    $campo_car= 'modelo';
                    $separ = ' AND (';
                    $separ_fin = '';
                }
                else{
                    $campo_car= 'version';
                    $separ = ' OR';
                    $separ_fin = ')';
                }              
                $id_carros_nv[$key] = implode(', ', $carros);
                $SQLmodelos[$key] = $separ." gv.".$campo_car." IN (".$id_carros_nv[$key].")".$separ_fin.' ';
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
        $bdcfalse = ' AND gi.bdc = 0 ';
        
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
                $varView['lista_conce'] = $this->getConcecionario($varView['grupo_id']);  
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

            if($_GET['GI']['tipo_t'] != '' AND $_GET['GI']['grupo'] != '' ){
                $con = Yii::app()->db;
                if($_GET['GI']['tipo_t'] == 'provincias'){
                    $varView['checked_p']  = true; 
                    $varView['checked_g']  = false;
                    $varView['id_provincia'] = $_GET['GI']['provincias']; 
                    $cond_conce = 'provincia'; 
                    $id_busqueda = $varView['id_provincia'];                 
                }else{
                    $varView['checked_g']  = true;
                    $varView['checked_p'] = false;
                    $varView['id_grupo'] = $_GET['GI']['grupo'];
                    $cond_conce = 'id_grupo'; 
                    $id_busqueda = $varView['id_grupo'];   
                }
                $grupos_sql = "SELECT * from gr_concesionarios WHERE ".$cond_conce." = ".$id_busqueda;

                $request_sql = $con->createCommand($grupos_sql);
                $request_sql = $request_sql->queryAll();

                foreach ($request_sql as $key3 => $value3) {
                    $conse_active .= $value3['dealer_id'].', ';
                }
                $conse_active = rtrim($conse_active, ", ");
                $condicion_GP = ' AND (dealer_id IN ('.$conse_active.')) ';                 
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
                    if($_GET['GI']['estadoBDC'] != ''){
                        $estadoBDC = $_GET['GI']['estadoBDC'];
                        if($estadoBDC == 'desiste'){
                            $estadoBDC_val = ' desiste != 0';
                        }else{
                            $estadoBDC_val = ' desiste = 0';
                        }
                        $con = Yii::app()->db;

                        $sql_estadoBDC = "select distinct id_informacion, desiste from  gestion_diaria where".$estadoBDC_val;           
                        $request_estadoBDC = $con->createCommand($sql_estadoBDC);
                        $request_estadoBDC = $request_estadoBDC->queryAll();

                        $id_info_estadoBD = '';
                        foreach ( $request_estadoBDC as $key_estadoBDC => $value_estadoBDC) {
                            $id_info_estadoBD .= $value_estadoBDC['id_informacion'].', ';
                        }
                        $id_info_estadoBD = rtrim($id_info_estadoBD, ", ");
                        //die($id_info_estadoBD);
                        $consultaBDC .= " AND gi.id IN (".$id_info_estadoBD.") ";
                    }else{
                        $consultaBDC .= " AND gi.bdc = 1 ";
                    }
                } else if($_GET['GI']['tipo'] == 'exonerados'){
                    $varView['checked_ta'] = false;
                    $varView['checked_ge']  = false; 
                    $varView['checked_us']  = false;
                    $varView['checked_ex']  = true;
                    $varView['checked_bdc']  = false;
                    if($_GET['GI']['tipoExo'] != ''){
                        $tipoexo = $_GET['GI']['tipoExo'];
                        $consultaBDC = " AND  gi.tipo_ex = '".$tipoexo."' ";
                    }else{
                        $consultaBDC .= " AND gi.tipo_ex IS NOT NULL ";
                    }
                }                   
            }           
        }else{
                $varView['checked_g'] = true;
                $varView['checked_ge']  = true; 
        }

        //GET Modelos activos en rango de fechas
        if($_GET['GI']['tipo'] == 'traficoacumulado'){
            $modelosClass = new Modelos;
            $modelos_ma = $modelosClass->getModleosActivos('2016-01-01', '2016-01-31', '2016-02-01', '2016-02-28', $varView['lista_datos'], 'general');
        }else{
            $modelosClass = new Modelos;
            $modelos_ma = $modelosClass->getModleosActivos($varView['fecha_inicial_anterior'], $varView['fecha_anterior'], $varView['fecha_inicial_actual'], $varView['fecha_actual'], $varView['lista_datos'], 'general');  
        }
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
            if($varView['cargo_id'] != 72 || $varView['cargo_id'] != 73 ){
               $id_persona = $id_persona.$bdcfalse; 
            }
            
            $constructor = new ConstructorSQL;
            $retorno = $constructor->buscar(
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
                $consultaBDC,
                $condicion_GP
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

        $varView['dif_ckd_trafico'] = $varView['traficockd2'] - $varView['traficockd1'];
        $varView['dif_cbu_trafico'] = $varView['traficocbu2'] - $varView['traficocbu1'];
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
        $varView['tasa_td_dif_ckd'] = rtrim($varView['tasa_testdrive_ckd_m1'], "%") - rtrim($varView['tasa_testdrive_ckd_m2'], "%");
        $varView['tasa_td_dif_cbu'] = rtrim($varView['tasa_testdrive_cbu_m1'], "%") - rtrim($varView['tasa_testdrive_cbu_m2'], "%");
        $varView['tasa_pr_dif_ckd'] = rtrim($varView['tasa_proforma_ckd_m1'], "%") - rtrim($varView['tasa_proforma_ckd_m2'], "%");
        $varView['tasa_pr_dif_cbu'] = rtrim($varView['tasa_proforma_cbu_m1'], "%") - rtrim($varView['tasa_proforma_cbu_m2'], "%");
        $varView['tasa_cierre_dif_ckd'] = rtrim($varView['tasa_cierre_ckd_m1'], "%") - rtrim($varView['tasa_cierre_ckd_m2'], "%");
        $varView['tasa_cierre_dif_cbu'] = rtrim($varView['tasa_cierre_cbu_m1'], "%") - rtrim($varView['tasa_cierre_cbu_m2'], "%");

        $this->render('inicio', array('varView' => $varView));
    }

    /*AJAX CALLS*/
    public function actionAjaxGetModelos() {        
        $getModelos = new AjaxCalls;
        echo $getModelos->AjaxGetModelos();
    }

    public function actionAjaxGetTipoBDC() {      
        $getModelos = new AjaxCalls;
        echo $getModelos->AjaxGetTipoBDC();
    }

    public function actionAjaxGetExonerados() {
        $getModelos = new AjaxCalls;
        echo $getModelos->AjaxGetExonerados();
    }

    public function actionAjaxGetAsesores() {
        $getModelos = new AjaxCalls;
        echo $getModelos->AjaxGetAsesores();
    }

    public function actionAjaxGetDealers() {
        $getModelos = new AjaxCalls;
        echo $getModelos->AjaxGetDealers();
    }

    public function actionAjaxGetProvincias() {
        $getModelos = new AjaxCalls;
        echo $getModelos->AjaxGetProvincias();
    }

    public function actionAjaxGetGrupo() {
        $getModelos = new AjaxCalls;
        echo $getModelos->AjaxGetGrupo();   
    }
    public function actionAjaxGetExcel() {
        $getModelos = new AjaxCalls;
        return $getModelos->AjaxGetExcel(); 
    }

    /*FIN AJAX CALLS*/

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
        if($dfpr >= 0){
            return $dfpr.' %';
        }else{
            return '<span class="dif">-'.abs($dfpr).' %</span>';
        }
    }
    
    function DIFconstructor($var1, $var2, $tipo){
        $dif = $var1 - $var2;

        if($dif < 0){
            $divisor = $var1;
        }else{
            $divisor = $var2; 
        }
        $unidad = '';        
        if($tipo == 'var'){
            $unidad = '%';
            if($var1 == 0){
                $dif = -$var2;
            }else if($var2 == 0){
                $dif = $var1;
            }else{
               $dif = ($var2 * 100) / $var1; 
               $dif = 100 - round($dif, 2);
            }
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

    //TRAFICO ACUMULADO
    public function actionAjaxGetConsecionariosTA() {
        $where = isset($_POST["where"]) ? $_POST["where"] : "";
        $fecha1 = isset($_POST["fecha1"]) ? $_POST["fecha1"] : "";
        $fecha2 = isset($_POST["fecha2"]) ? $_POST["fecha2"] : "";
        $model_info = isset($_POST["model_info"]) ? $_POST["model_info"] : "";
        $TAresp_activo = isset($_POST["TAresp_activo"]) ? $_POST["TAresp_activo"] : "";
        $traficoAcumulado = new traficoAcumulado;
        $traficoAcumulado->ConcesionariosTA($where, $fecha1, $fecha2, $TAresp_activo, $model_info);
    }

    public function actionAjaxGetModelos2TA() {
        $model_info = isset($_POST["model_info"]) ? $_POST["model_info"] : "";
        $fecha1 = isset($_POST["fecha1"]) ? $_POST["fecha1"] : "";
        $fecha2 = isset($_POST["fecha2"]) ? $_POST["fecha2"] : "";

        $traficoAcumulado = new traficoAcumulado;
        $traficoAcumulado->modelos2TA($fecha1, $fecha2, $model_info);
    }
}