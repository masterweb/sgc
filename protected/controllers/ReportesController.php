<?php

require_once( dirname(__FILE__) . '/../components/Reportes/GlobalFunctions.php');
require_once( dirname(__FILE__) . '/../components/Reportes/ConstructorSQL.php');
require_once( dirname(__FILE__) . '/../components/Reportes/Modelos.php');
require_once( dirname(__FILE__) . '/../components/Reportes/AjaxCalls.php');
require_once( dirname(__FILE__) . '/../components/Reportes/TraficoAcumulado.php');
require_once( dirname(__FILE__) . '/../components/Reportes/TraficoUsados.php');

class ReportesController extends Controller {
    
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
                'actions' => array('inicio','bdc','teleweb','ajaxgetmodels','AjaxGetTipoBDC','AjaxGetExonerados',
                    'AjaxGetAsesores','AjaxGetDealers','AjaxGetProvincias','AjaxGetGrupo','AjaxGetExcel',
                    'AjaxGetConsecionariosTA','AjaxGetModelos2TA','AjaxGetModelos'),
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
    public function actionTeleweb($id_informacion = null, $id_vehiculo = null, $tipo = null, $grupo = null){

            /* FUNCION GENERADA PARA REPORTES TELEWEB*/
            $GF = new GlobalFunctions;
       
            date_default_timezone_set('America/Guayaquil');
            $dt = time();
            setlocale(LC_TIME, 'es_ES.UTF-8');
            if($_GET['GI']['grupo']=='')
                $_GET['GI']['grupo']=$grupo;
            else $grupo = $_GET['GI']['grupo'];
            $varView = array();

            /*informacion de quien realiza el reporte*/
            $varView['grupo_id'] = (int) Yii::app()->user->getState('grupo_id');
            $varView['cargo_id'] = (int) Yii::app()->user->getState('cargo_id');
            $varView['area_id'] = (int) Yii::app()->user->getState('area_id');
            $varView['cargo_adicional'] = (int) Yii::app()->user->getState('cargo_adicional');
            $varView['id_responsable'] = Yii::app()->user->getId();
            $varView['dealer_id'] = $this->getDealerId($varView['id_responsable']);
            /*informacion de quien realiza el reporte*/
            /*fechas*/
            $varView['fecha_actual'] = strftime("%Y-%m-%d", $dt);
            $varView['fecha_actual2'] = strtotime('+1 day', strtotime($varView['fecha_actual']));
            $varView['fecha_actual2'] = date('Y-m-d', $varView['fecha_actual2']);
            $varView['fecha_inicial_actual'] = (new DateTime('first day of this month'))->format('Y-m-d');
            $varView['fecha_anterior'] = strftime("%Y-%m-%d", strtotime('-1 month', $dt));
            $varView['fecha_inicial_anterior'] = strftime("%Y-%m", strtotime('-1 month', $dt)) . '-01';
            $varView['nombre_mes_actual'] = strftime("%B - %Y", $dt);
            $varView['nombre_mes_anterior'] = strftime("%B - %Y", strtotime('-1 month', $dt));
            /*fechas*/

             //SI BUSCAN POR VERSION O MODELO Y RECIBE VARIABLES PARA LA CONSULTA
            $lista_datos = array();
            if (isset($_GET['modelo'])) {
                array_push($lista_datos, array('modelos' => $_GET['modelo']));
            }
            if (isset($_GET['version'])) {
                array_push($lista_datos, array('versiones' => $_GET['version']));
            }
            /*SI BUSCAN POR VERSION O MODELO*/

            //variables busqueda por defecto
            $tit_ext = '';
            $join_ext = null;
            $group_ext = null;
            $select_ext = null;
            $tit_init = 'Búsqueda entre ';
            $varView['AEKIA'] = false;
            $bdcfalse = ' AND gi.bdc = 1 ';



            switch ($varView['cargo_id']) 
            {
                case 4: // GERENTE GENERAL
                case 45: // SUBGERENCIA GENERAL
                case 46: // SUPER ADMINISTRADOR
                case 48: // GERENTE MARKETING
                case 57: // INTELIGENCIA DE MERCADO MARKETING
                case 58: // JEFE DE PRODUCTO MARKETING
                case 60: // GERENTE VENTAS
                case 61: // JEFE DE RED VENTAS
                   
                case 62: // SUBGERENTE DE FLOTAS VENTAS
                      $con = Yii::app()->db;
                   
                     $varView['lista_grupo'] = Grupo::model()->findAll(array('condition' => "id = 3"));

                     $responsable=Grupoconcesionariousuario::model()->findAll(array('condition' => "id = 3"));

                     $sql='SELECT distinct(usuario_id) as id FROM callcenter.grupoconcesionariousuario g inner join usuarios u on u.id=g.'.'usuario_id and'.
                     ' u.cargo_id=89 inner join gr_concesionarios c on c.dealer_id=u.dealers_id and c.id_grupo='.$grupo;
                    $responsable = $con->createCommand($sql)->queryAll();

                   // die('<pre>'.print_r($responsable).'</pre>');
                    $i=0;
                    $cadena_responsables="";
                    foreach ($responsable as $resp) {
                        
                            if($i>0)$cadena_responsables .=',';
                            $cadena_responsables .= $resp['id'];
                            $i++;
                        
                    }



                      $id_persona = '( gi.responsable in ('.$cadena_responsables .') or gi.responsable_origen in ('.$cadena_responsables .') or gi.responsable_origen_tm in ('.$cadena_responsables .')    )';   


                      $id_persona_pro = '( gi.responsable_origen_tm in ('.$cadena_responsables .')    )';  


                    $varView['lista_provincias'] = Provincias::model()->findAll();
                    $varView['lista_conce'] = 'null';
                    $varView['AEKIA'] = true;
                    $varView['consecionario_usuario'] = '<b>Grupo:</b> TODOS';
                    //TRAFICO ACUMULADO
                    if ($_GET['TA']['provincia'] != '') {
                        $TAactivo = $_GET['TA']['provincia'];
                    } else {
                        $TAactivo = $_GET['TA']['grupo'];
                    }
                    $varView['TAresp_activo'] = $_GET['TA']['concesionarios'];
                    if ($_GET['TA']['modelo'] != '') {
                        $TAmodelo = $_GET['TA']['modelo'];
                    }
                    $traficoAcumulado = new traficoAcumulado;
                    $varView['traficoAcumulado']['ini_filtros'] = $traficoAcumulado->ini_filtros($TAactivo, $TAmodelo);
                    $INERProspeccion = ' LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id ';

                    break;
                case 69: // GERENTE COMERCIAL EN CURSO TERMINADO----->
                
                             $join_ext = ' INNER JOIN usuarios u ON u.id = gi.responsable ';
                             $id_persona = 'u.grupo_id = ' . $varView['grupo_id'];
                             $varView['lista_conce'] = $GF->getConcecionario($varView['grupo_id']);
                             $varView['consecionario_usuario'] = '<b>Grupo:</b> ' . $this->getNombreGrupo($varView['grupo_id']);
                    break;
                case 70: // jefe de sucursal TERMINADO------>
                    $con = Yii::app()->db;  
                    //$id_persona_pro = "gi.dealer_id = " . $varView['dealer_id'];
                    $varView['lista_conce'] = $GF->getConcecionario((int) Yii::app()->user->getState('grupo_id'));
                    

                     $array_dealers = $this->getDealerGrupoConcesionario($varView['id_responsable']);
                     $dealerList = implode(', ', $array_dealers);
                    $id_persona = "gi.dealer_id IN (" . $dealerList . ")  ";


                     $sql='SELECT distinct(usuario_id) as id FROM callcenter.grupoconcesionariousuario g inner join usuarios u on u.id=g.'.'usuario_id and'.
                     ' u.cargo_id=89 inner join gr_concesionarios c on c.dealer_id=u.dealers_id and c.id_grupo='.$grupo;
                    $responsable = $con->createCommand($sql)->queryAll();

                    $id_persona_pro =  $id_persona;

                   // die('<pre>'.print_r($responsable).'</pre>');
                    $i=0;
                    $cadena_responsables="";
                    foreach ($responsable as $resp) {
                        
                            if($i>0)$cadena_responsables .=',';
                            $cadena_responsables .= $resp['id'];
                            $i++;
                        
                    }

                    $id_persona .= ' AND ( gi.responsable in ('.$cadena_responsables .') or gi.responsable_origen in ('.$cadena_responsables .') or gi.responsable_origen_tm in ('.$cadena_responsables .')    )';

                     $id_persona_pro = "gi.dealer_id IN (" . $dealerList . ")  AND gi.responsable_origen_tm in (".$cadena_responsables.")";
                    
                    
                    break;
                    case 71:

                         $id_persona = ' gi.responsable = '.$varView['id_responsable'] ;
                         $id_persona_pro =  $id_persona;
                        break;
               
                case 89:// asesor TELEMARKETING WEB------> TRABAJAR
                    
                    $id_persona = "(gi.responsable = " . $varView['id_responsable'] . " OR gi.responsable_origen= ".$varView['id_responsable']." OR gi.responsable_origen_tm = " . $varView['id_responsable'] . ") ";
                     $id_persona_pro = ' gi.responsable_origen_tm = '.$varView['id_responsable'];  
                    break;    
            }

            if ($_GET['GI']) 
            {

                        if ($_GET['GI']['fecha1'] != '') {
                            $gi_fecha1 = explode(" - ", $_GET['GI']['fecha1']);
                            $varView['fecha_inicial_actual'] = $gi_fecha1[0];
                            $varView['fecha_actual'] = $gi_fecha1[1];
                            $date = new DateTime($varView['fecha_actual']);
                            $varView['nombre_mes_actual'] = $date->format('F - Y');
                            $varView['flag_search'] = 1;
                        }
                        if ($_GET['GI']['fecha2'] != '') {
                            $gi_fecha2 = explode(" - ", $_GET['GI']['fecha2']);
                            $varView['fecha_inicial_anterior'] = $gi_fecha2[0];
                            $varView['fecha_anterior'] = $gi_fecha2[1];
                            $date = new DateTime($varView['fecha_anterior']);
                            $varView['nombre_mes_anterior'] = $date->format('F - Y');
                            $varView['flag_search'] = 2;
                        }


                        /*inicio busqueda por responsable*/
                        if ($_GET['GI']['responsable'] != '') {  


                           
                            $varView['id_responsable'] = $_GET['GI']['responsable'];
                            
                            $id_persona = "(gi.responsable IN({$varView['id_responsable']}) OR gi.responsable_origen IN({$varView['id_responsable']}) OR gi.responsable_origen_tm IN({$varView['id_responsable']})) AND gd.desiste = 0"; 
                            

                            if($varView['id_responsable'] == 1000 && $_GET['GI']['concesionario']!='')
                                $id_persona = "gi.dealer_id = " . $_GET['GI']['concesionario'];
                           
                           
                        }

                     /*   else {
                                $id_persona = "gi.dealer_id = " . $varView['$concesionario'];
                        }*/
                        /* fin busqueda por responsable*/
                        /*busqueda por grupo y concesionario*/
                        if ($_GET['GI']['tipo_t'] != '' AND $_GET['GI']['grupo'] != '' AND $_GET['GI']['grupo'] != 1000)/*quizaas se necesite incluir el grupo 1000 dentro del if con otro if*/ {
                            $con = Yii::app()->db;

                             $varView['checked_g'] = true;
                             $varView['checked_p'] = false;
                             $varView['id_grupo'] = $_GET['GI']['grupo'];
                             

                             if($_GET['GI']['concesionario'] == 1000 || $_GET['GI']['concesionario'] == ''){

                                $grupos_sql = "SELECT * from gr_concesionarios WHERE id_grupo = " . $varView['id_grupo'];

                                 $request_sql = $con->createCommand($grupos_sql)->queryAll();

                                foreach ($request_sql as $key3 => $value3) {
                                    $conse_active .= $value3['dealer_id'] . ', ';
                                }
                                $conse_active = rtrim($conse_active, ", ");
                                $condicion_GP = ' AND (dealer_id IN (' . $conse_active . ')) ';
                             }
                             else {
                                     $condicion_GP = ' AND (dealer_id = ' . $_GET['GI']['concesionario']  . ') ';
                             }
                        }
                        /*fin busqueda por grupo y concesionario*/

            }


             $constructor = new ConstructorSQL;

            
            /* die($varView['cargo_id']. $varView['id_responsable']. $select_ext. $join_ext. $id_persona. $group_ext. $varView['fecha_inicial_anterior']. $varView['fecha_anterior']. $varView['fecha_inicial_actual']. $varView['fecha_actual']. $conc. $tipos. $SQLmodelos. $INERmodelos. $INERmodelos_td. $INERProspeccion.$consultaBDC. $condicion_GP. $tipo);*/
            
            $retorno = $constructor->buscarTeleweb(
                        $varView['cargo_id'], $varView['id_responsable'], $select_ext, $join_ext, $id_persona, $group_ext, $varView['fecha_inicial_anterior'], $varView['fecha_anterior'], $varView['fecha_inicial_actual'], $varView['fecha_actual'], /*$varView['concesionario']*/$conc, $tipos, $SQLmodelos, $INERmodelos, $INERmodelos_td, $INERProspeccion,$consultaBDC, $condicion_GP, $tipo,$id_persona_pro 
            );



            $varView['prospeccion_mes_anterior'] = $retorno['prospeccion_mes_anterior'];
            $varView['prospeccion_mes_actual'] = $retorno['prospeccion_mes_actual'];
            $varView['trafico_mes_anterior'] = $retorno['trafico_mes_anterior'];
            $varView['trafico_mes_actual'] = $retorno['trafico_mes_actual'];
            $varView['traficockd1'] = $retorno['traficockd1'];
            $varView['traficocbu1'] = $retorno['traficocbu1'];
            $varView['traficockd2'] = $retorno['traficockd2'];
            $varView['traficocbu2'] = $retorno['traficocbu2'];
            $varView['trafico_mes_anterior'] = $retorno['trafico_mes_anterior'];
            $varView['trafico_mes_actual'] = $retorno['prospeccion_mes_actual'];
              

            $varView['trafico_citas_mes_anterior'] = $retorno['trafico_mes_anterior'];
            $varView['trafico_citas_mes_actual'] = $retorno['trafico_mes_actual'];

            $varView['trafico_citas_concretadas_mes_anterior'] = $retorno['trafico_citas_concretadas_mes_anterior'];
            $varView['trafico_citas_concretadas_mes_actual'] = $retorno['trafico_citas_concretadas_mes_actual'];

            $varView['proforma_mes_anterior'] = $retorno['proforma_mes_anterior'];
            $varView['proforma_mes_actual'] = $retorno['proforma_mes_actual'];
            $varView['proformackd1'] = $retorno['proformackd1'];
            $varView['proformacbu1'] = $retorno['proformacbu1'];
            $varView['proformackd2'] = $retorno['proformackd2'];
            $varView['proformacbu2'] = $retorno['proformacbu2'];
            $varView['td_mes_anterior'] = $retorno['td_mes_anterior'];
            $varView['td_mes_actual'] = $retorno['td_mes_actual'];
            $varView['tdckd1'] = $retorno['tdckd1'];
            $varView['tdcbu1'] = $retorno['tdcbu1'];
            $varView['tdckd2'] = $retorno['tdckd2'];
            $varView['tdcbu2'] = $retorno['tdcbu2'];
            $varView['vh_mes_anterior'] = $retorno['vh_mes_anterior'];
            $varView['vh_mes_actual'] = $retorno['vh_mes_actual'];
            $varView['vhckd1'] = $retorno['vhckd1'];
            $varView['vhcbu1'] = $retorno['vhcbu1'];
            $varView['vhckd2'] = $retorno['vhckd2'];
            $varView['vhcbu2'] = $retorno['vhcbu2'];
           
            $varView['cotizaciones_enviadas_anterior'] = $retorno['cotizaciones_enviadas_anterior'];
            $varView['cotizaciones_enviadas_actual'] = $retorno['cotizaciones_enviadas_actual'];
            $varView['respuestas_enviadas_anterior'] = $retorno['respuestas_enviadas_anterior'];
            $varView['respuestas_enviadas_actual'] = $retorno['respuestas_enviadas_actual'];
            $varView['proformas_enviadas_anterior'] = $retorno['proformas_enviadas_anterior'];
            $varView['proformas_enviadas_actual'] = $retorno['proformas_enviadas_actual'];

             $cont=count($retorno)-1;   
             $varView['vcont_mes_anterior'] = $retorno['vcont_mes_anterior'];
             $varView['vcred_mes_anterior'] = $retorno['vcred_mes_anterior'];
             $varView['vcont_mes_actual'] = $retorno['vcont_mes_actual'];
             $varView['vcred_mes_actual'] = $retorno['vcred_mes_actual'];



               $varView['dif_ckd_trafico'] = $varView['traficockd2'] - $varView['traficockd1'];
        $varView['dif_cbu_trafico'] = $varView['traficocbu2'] - $varView['traficocbu1'];
        //$varView['usu'] = $usu;
        //$varView['mod'] = $mod;      
        //set diferencias
        
        $varView['var_prp'] = $GF->DIFconstructor($varView['prospeccion_mes_actual'], $varView['prospeccion_mes_anterior'], 'var');
        $varView['dif_prp'] = $GF->DIFconstructor($varView['prospeccion_mes_actual'], $varView['prospeccion_mes_anterior'], 'dif');
        
        $varView['var_raa'] = $GF->DIFconstructor($varView['respuestas_enviadas_actual'], $varView['respuestas_enviadas_anterior'], 'var');
        $varView['dif_rac'] = $GF->DIFconstructor($varView['respuestas_enviadas_actual'], $varView['respuestas_enviadas_anterior'], 'dif');
        
        $varView['var_tr'] = $GF->DIFconstructor($varView['trafico_mes_actual'], $varView['trafico_mes_anterior'], 'var');
        $varView['dif_tr'] = $GF->DIFconstructor($varView['trafico_mes_actual'], $varView['trafico_mes_anterior'], 'dif');

        $varView['var_pr'] = $GF->DIFconstructor($varView['proforma_mes_actual'], $varView['proforma_mes_anterior'], 'var');
        $varView['dif_pr'] = $GF->DIFconstructor($varView['proforma_mes_actual'], $varView['proforma_mes_anterior'], 'dif');

        $varView['var_td'] = $GF->DIFconstructor($varView['td_mes_actual'], $varView['td_mes_anterior'], 'var');
        $varView['dif_td'] = $GF->DIFconstructor($varView['td_mes_actual'], $varView['td_mes_anterior'], 'dif');

        $varView['var_ve'] = $GF->DIFconstructor($varView['vh_mes_actual'], $varView['vh_mes_anterior'], 'var');
        $varView['dif_ve'] = $GF->DIFconstructor($varView['vh_mes_actual'], $varView['vh_mes_anterior'], 'dif');

        
        $varView['var_cred'] = $GF->DIFconstructor($varView['vcred_mes_actual'], $varView['vcred_mes_anterior'], 'var');
        $varView['dif_cred'] = $GF->DIFconstructor($varView['vcred_mes_actual'], $varView['vcred_mes_anterior'], 'dif');


        $varView['var_cont'] = $GF->DIFconstructor($varView['vcont_mes_actual'], $varView['vcont_mes_anterior'], 'var');
        $varView['dif_cont'] = $GF->DIFconstructor($varView['vcont_mes_actual'], $varView['vcont_mes_anterior'], 'dif');

        $varView['var_citas_generadas'] = $GF->DIFconstructor($varView['trafico_citas_mes_actual'], $varView['trafico_citas_mes_anterior'], 'var');
        $varView['dif_citas_generadas'] = $GF->DIFconstructor($varView['trafico_citas_mes_actual'], $varView['trafico_citas_mes_anterior'], 'dif');
        $varView['var_citas_concretadas'] = $GF->DIFconstructor($varView['trafico_citas_concretadas_mes_actual'], $varView['trafico_citas_concretadas_mes_anterior'], 'var');
        $varView['dif_citas_concretadas'] = $GF->DIFconstructor($varView['trafico_citas_concretadas_mes_actual'], $varView['trafico_citas_concretadas_mes_anterior'], 'dif');        



        $varView['titulo'] = $tit_init . $fecha_inicial_actual . ' / ' . $fecha_actual . ', y ' . $fecha_inicial_anterior . ' / ' . $fecha_anterior . $tit_ext;


        //set Tasas
        //tasa proformas

        $varView['tasa_proforma_actual'] = $GF->tasa($varView['trafico_mes_actual'], $varView['proforma_mes_actual']);
        $varView['tasa_proforma_anterior'] = $GF->tasa($varView['trafico_mes_anterior'], $varView['proforma_mes_anterior']);
        $varView['tasa_dif_proforma'] = $GF->tasa_dif($varView['tasa_proforma_actual'], $varView['tasa_proforma_anterior']);

        //tasa test drive
        $varView['tasa_testdrive_actual'] = $GF->tasa($varView['trafico_mes_actual'], $varView['td_mes_actual']);
        $varView['tasa_testdrive_anterior'] = $GF->tasa($varView['trafico_mes_anterior'], $varView['td_mes_anterior']);
        $varView['tasa_dif_testdrive'] = $GF->tasa_dif($varView['tasa_testdrive_actual'], $varView['tasa_testdrive_anterior']);

        //tasa cierre
        
        $varView['tasa_cierre_actual'] = $GF->tasa($varView['trafico_mes_actual'], $varView['vh_mes_actual']);
        $varView['tasa_cierre_anterior'] = $GF->tasa($varView['trafico_mes_anterior'], $varView['vh_mes_anterior']);
        $varView['tasa_dif_cierre'] = $GF->tasa_dif($varView['tasa_cierre_actual'], $varView['tasa_cierre_anterior']);


        //tasa cierre credito
        $varView['tasa_credito_actual'] = $GF->tasa($varView['trafico_mes_actual'], $varView['vcred_mes_actual']);
        $varView['tasa_credito_anterior'] = $GF->tasa($varView['trafico_mes_anterior'], $varView['vcred_mes_anterior']);
        $varView['tasa_dif_credito'] = $GF->tasa_dif($varView['tasa_credito_actual'], $varView['tasa_credito_anterior']);

        //tasa cierre contado
        $varView['tasa_contado_actual'] = $GF->tasa($varView['trafico_mes_actual'], $varView['vcont_mes_actual']);
        $varView['tasa_contado_anterior'] = $GF->tasa($varView['trafico_mes_anterior'], $varView['vcont_mes_anterior']);
        $varView['tasa_dif_contado'] = $GF->tasa_dif($varView['tasa_contado_actual'], $varView['tasa_contado_anterior']);


        //tasa proformas ckd y cbu
        $varView['tasa_proforma_ckd_m1'] = $GF->tasa($varView['traficockd2'], $varView['proformackd2']);
        $varView['tasa_proforma_cbu_m1'] = $GF->tasa($varView['traficocbu2'], $varView['proformacbu2']);
        $varView['tasa_proforma_ckd_m2'] = $GF->tasa($varView['traficockd1'], $varView['proformackd1']);
        $varView['tasa_proforma_cbu_m2'] = $GF->tasa($varView['traficocbu1'], $varView['proformacbu1']);

        //tasa testdrive ckd y cbu
        $varView['tasa_testdrive_ckd_m1'] = $GF->tasa($varView['traficockd2'], $varView['tdckd2']);
        $varView['tasa_testdrive_cbu_m1'] = $GF->tasa($varView['traficocbu2'], $varView['tdcbu2']);
        $varView['tasa_testdrive_ckd_m2'] = $GF->tasa($varView['traficockd1'], $varView['tdckd1']);
        $varView['tasa_testdrive_cbu_m2'] = $GF->tasa($varView['traficocbu1'], $varView['tdcbu1']);

        //tasa cierre ckd y cbu
        $varView['tasa_cierre_ckd_m1'] = $GF->tasa($varView['traficockd2'], $varView['vhckd2']);
        $varView['tasa_cierre_cbu_m1'] = $GF->tasa($varView['traficocbu2'], $varView['vhcbu2']);
        $varView['tasa_cierre_ckd_m2'] = $GF->tasa($varView['traficockd1'], $varView['vhckd1']);
        $varView['tasa_cierre_cbu_m2'] = $GF->tasa($varView['traficocbu1'], $varView['vhcbu1']);

        //set diferencias ckd y cbu
        $varView['tasa_td_dif_ckd'] = rtrim($varView['tasa_testdrive_ckd_m1'], "%") - rtrim($varView['tasa_testdrive_ckd_m2'], "%");
        $varView['tasa_td_dif_cbu'] = rtrim($varView['tasa_testdrive_cbu_m1'], "%") - rtrim($varView['tasa_testdrive_cbu_m2'], "%");
        $varView['tasa_pr_dif_ckd'] = rtrim($varView['tasa_proforma_ckd_m1'], "%") - rtrim($varView['tasa_proforma_ckd_m2'], "%");
        $varView['tasa_pr_dif_cbu'] = rtrim($varView['tasa_proforma_cbu_m1'], "%") - rtrim($varView['tasa_proforma_cbu_m2'], "%");
        $varView['tasa_cierre_dif_ckd'] = rtrim($varView['tasa_cierre_ckd_m1'], "%") - rtrim($varView['tasa_cierre_ckd_m2'], "%");
        $varView['tasa_cierre_dif_cbu'] = rtrim($varView['tasa_cierre_cbu_m1'], "%") - rtrim($varView['tasa_cierre_cbu_m2'], "%");

        // tasa de testdrive teleweb
        $varView['tasa_td_tw_actual'] = $GF->tasa($varView['trafico_citas_concretadas_mes_actual'], $varView['td_mes_actual']);
        $varView['tasa_td_tw_anterior'] = $GF->tasa($varView['trafico_citas_concretadas_mes_anterior'], $varView['td_mes_anterior']); 
        
        // tasa de cierre ventas teleweb
        $varView['tasa_cierre_tw_actual'] = $GF->tasa($varView['trafico_citas_concretadas_mes_actual'], $varView['vh_mes_actual']);
        $varView['tasa_cierre_tw_anterior'] = $GF->tasa($varView['trafico_citas_concretadas_mes_anterior'], $varView['vh_mes_anterior']); 
               

        $this->render('inicio', array('varView' => $varView, 'tipo' => $tipo,'grupo' => $grupo,'teleweb'=>true ));


    }
    public function actionInicio($id_informacion = null, $id_vehiculo = null, $tipo = null) {
        if(!is_null($tipo)){
            $tipo = $tipo;
        }


        $GF = new GlobalFunctions;
       
        date_default_timezone_set('America/Guayaquil');
        $dt = time();
        setlocale(LC_TIME, 'es_ES.UTF-8');

        $varView = array();

        $varView['grupo_id'] = (int) Yii::app()->user->getState('grupo_id');
        $varView['cargo_id'] = (int) Yii::app()->user->getState('cargo_id');
        $varView['area_id'] = (int) Yii::app()->user->getState('area_id');
        $varView['cargo_adicional'] = (int) Yii::app()->user->getState('cargo_adicional');
        //$varView['cargo_adicional'] = 85;
        $varView['id_responsable'] = Yii::app()->user->getId();
        $varView['dealer_id'] = $this->getDealerId($varView['id_responsable']);


         

        //echo 'dealer id: '.$varView['dealer_id'];
        $varView['nombre_usuario'] = Usuarios::model()->findByPk($varView['id_responsable']);
        $varView['provincia_id'] = $varView['nombre_usuario']->provincia_id;
        $varView['cargo_usuario'] = Cargo::model()->findByPk($varView['nombre_usuario']->cargo_id);
       

        if ($varView['nombre_usuario']->dealers_id != '') {
            $varView['consecionario_usuario'] = '<b>Concesionario:</b> ' . GrConcesionarios::model()->find('dealer_id=' . $varView['nombre_usuario']->dealers_id)['nombre'];
        }


        $varView['titulo'] = '';
        $varView['concesionario'] = 2000;
        $varView['tipos'] = null;

        $varView['fecha_actual'] = strftime("%Y-%m-%d", $dt);
        $varView['fecha_actual2'] = strtotime('+1 day', strtotime($varView['fecha_actual']));
        $varView['fecha_actual2'] = date('Y-m-d', $varView['fecha_actual2']);
        $varView['fecha_inicial_actual'] = (new DateTime('first day of this month'))->format('Y-m-d');
        $varView['fecha_anterior'] = strftime("%Y-%m-%d", strtotime('-1 month', $dt));
        $varView['fecha_inicial_anterior'] = strftime("%Y-%m", strtotime('-1 month', $dt)) . '-01';
        $varView['nombre_mes_actual'] = strftime("%B - %Y", $dt);
        $varView['nombre_mes_anterior'] = strftime("%B - %Y", strtotime('-1 month', $dt));


        $varView['flag_search'] = 0;
        

        $con = Yii::app()->db;

        //SI BUSCAN POR VERSION O MODELO Y RECIBE VARIABLES PARA LA CONSULTA
        $lista_datos = array();
        if (isset($_GET['modelo'])) {
            array_push($lista_datos, array('modelos' => $_GET['modelo']));
        }
        if (isset($_GET['version'])) {
            array_push($lista_datos, array('versiones' => $_GET['version']));
        }
//        echo '<pre>';
//        print_r($_GET);
//        echo '</pre>';
//        die();
        
//        echo '<pre>';
//        print_r($lista_datos);
//        echo '</pre>';
//        die();

        $SQLmodelos = '';
        $INERmodelos = ' INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id';
        $INERmodelos_td = '';
        $INERProspeccion = '';
        $varView['triger'] = 0;
        foreach ($lista_datos as $key => $value) {
            foreach ($value as $key => $carros) {
                if ($key == 'modelos') {
                    $campo_car = 'modelo';
                    $separ = ' AND (';
                    $separ_fin = ')';
                } else {
                    $campo_car = 'version';
                    $separ = ' AND ';
                    $separ_fin = '';
                }
                $id_carros_nv[$key] = implode(', ', $carros);
                $SQLmodelos[$key] = $separ . " gv." . $campo_car . " IN (" . $id_carros_nv[$key] . ")" . $separ_fin . ' ';
                //0$INERmodelos = ' INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id ';
                $INERmodelos_td = ' INNER JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo ';
                $INERProspeccion = ' LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id';
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
        if( $tipo == 'externas' || $tipo == 'tw' ){
            $bdcfalse = ' AND gi.bdc = 1 ';
            $INERmodelos = ' LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id';
        }
        if($tipo == 'prospeccionweb' /*|| $tipo == 'super'*/)
            $bdcfalse = ' AND gi.bdc = 1 ';
        // GRUPO ASIAUTO Y KMOTOR CON CARGO JEFE VENTAS WEB O CARGO ADICIONAL ASESOR VENTAS WEB. SUMA AL EMBUDO BDC = 1
        if(($varView['grupo_id'] == 2 || $varView['grupo_id'] == 3) && ($varView['cargo_id'] == 85 || $varView['cargo_id'] == 86)){
            //die('enter gret');
            $bdcfalse = ' AND gi.bdc = 1 ';
        }
        // IOKARS, AUTHESA - ASESORES VENTAS Y ASESORES WEB. SUMA AL EMBUDO BDC = 0 Y BDC = 1
        if(($varView['grupo_id'] != 2 || $varView['grupo_id'] != 3) && ($varView['cargo_id'] == 71 || $varView['cargo_id'] == 86))
            $bdcfalse = '';

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
               // $id_persona = "gd.desiste = 0 ";
                //break;   


            case 62: // SUBGERENTE DE FLOTAS VENTAS
                $id_persona = 'gi.responsable ';
                if($tipo == 'tw'){
                    $varView['lista_grupo'] = Grupo::model()->findAll(array('condition' => "id = 2"));
                }else{
                    $varView['lista_grupo'] = Grupo::model()->findAll();
                }
                
                $varView['lista_provincias'] = Provincias::model()->findAll();
                $varView['lista_conce'] = 'null';
                $varView['AEKIA'] = true;
                $varView['consecionario_usuario'] = '<b>Grupo:</b> TODOS';
                //TRAFICO ACUMULADO
                if ($_GET['TA']['provincia'] != '') {
                    $TAactivo = $_GET['TA']['provincia'];
                } else {
                    $TAactivo = $_GET['TA']['grupo'];
                }
                $varView['TAresp_activo'] = $_GET['TA']['concesionarios'];
                if ($_GET['TA']['modelo'] != '') {
                    $TAmodelo = $_GET['TA']['modelo'];
                }

                $traficoAcumulado = new traficoAcumulado;
                $varView['traficoAcumulado']['ini_filtros'] = $traficoAcumulado->ini_filtros($TAactivo, $TAmodelo);
                //$INERmodelos = ' INNER JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id ';
                $INERProspeccion = ' LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id ';
                if($tipo == 'prospeccionweb'){
                    $join_ext = ' INNER JOIN usuarios u ON u.id = gi.responsable ';
                    $id_persona = " u.cargo_id = 86 "; 
                }
                if($tipo == 'tw'){
                   // $id_persona = " AND gd.desiste = 0";
                }
                   
                break;
            case 69: // GERENTE COMERCIAL EN CURSO TERMINADO----->
                //die('here');

                if($tipo == 'super'){
                    $id_persona = 'u.grupo_id = ' . $varView['grupo_id'];
                    $varView['lista_conce'] = $GF->getConcecionario($varView['grupo_id']);
                    $varView['consecionario_usuario'] = '<b>Grupo:</b> ' . $this->getNombreGrupo($varView['grupo_id']);
                   $conces= $this->getDealerGrupoConcUsuario($varView['id_responsable'],0); 
                   $dealerList = implode(', ', $conces);
                    $id_persona = "gi.responsable AND gi.dealer_id IN (".$dealerList.")  ";
                }
                else{
                    $join_ext = ' INNER JOIN usuarios u ON u.id = gi.responsable ';
                    $id_persona = 'u.grupo_id = ' . $varView['grupo_id'];
                    $varView['lista_conce'] = $GF->getConcecionario($varView['grupo_id']);
                    $varView['consecionario_usuario'] = '<b>Grupo:</b> ' . $this->getNombreGrupo($varView['grupo_id']);
                }
                break;
            case 70: // jefe de sucursal TERMINADO------>
                $id_persona = "gi.dealer_id = " . $varView['dealer_id'];
                $varView['lista_conce'] = $GF->getConcecionario((int) Yii::app()->user->getState('grupo_id'));
                
                if($varView['cargo_adicional'] == 85){ // CARGO ADICIONAL JEFE DE VENTAS EXTERNAS
                    $join_ext = ' INNER JOIN usuarios u ON u.id = gi.responsable ';
                    $did = $this->getDealerIdUnique($varView['id_responsable']);
                    if($did != 0){
                        $id_persona = "gi.dealer_id IN (" . $varView['dealer_id'] . ") AND u.cargo_id IN (70,71) ";
                    }else{
                        $array_dealers = $this->getDealerGrupoConcesionario($varView['id_responsable']);
                        $dealerList = implode(', ', $array_dealers);
                        $id_persona = "gi.dealer_id IN (" . $dealerList . ") AND u.cargo_id IN (70,71) ";
                    }
                    if($tipo == 'prospeccionweb')
                        $id_persona = "gi.dealer_id IN (" . $dealerList . ") AND u.cargo_adicional = 86 ";

                    if($tipo == 'externas')
                        $id_persona = "gi.dealer_id IN (" . $dealerList . ") AND (u.cargo_id = 86 OR u.cargo_adicional = 86 ) ";
                   

                }
                /*if($varView['cargo_adicional'] == 89){
                    if($tipo == 'externas'){

                        $tw = Usuarios::model()->findAll(array('condition' => "cargo_id = 89"));
                        $counter = 0;
                        foreach ($tw as $val) {
                                //echo 'asdasd'.$value['concesionario_id'];
                                $array_tw[$counter] = $val['id'];
                                $counter++;
                        }
                        $usuarioList = implode(', ', $array_tw);
                        $id_persona .= " AND gi.responsable_origen_tm IN (" . $usuarioList . ")";
                    }
                }  */

                //die('id persona case: '.$id_persona);
                break;
            case 71: // asesor de ventas TERMINADO------>
                $id_persona = "gi.responsable = " . $varView['id_responsable'];
                break;
            case 72: //jefe BDC y exonerados TERMINADO------> PROBAR
                $id_persona = "gi.dealer_id = " . $varView['dealer_id'] . ' AND (gi.bdc = 1 OR gi.tipo_form_web = "exonerados") ';
                break;
            case 73: //asesor bdc TERMINADO------> PROBAR
                $id_persona = "gi.responsable = " . $varView['id_responsable'] . ' AND gi.bdc = 1 ';
                break;
            case 75: //asesor exonerados TERMINADO------> PROBAR
                $id_persona = "gi.responsable = " . $varView['id_responsable'] . ' AND gi.tipo_form_web = "exonerados" ';
                break;
            case 76: //jefe usados TERMINADO------> PROBAR
                $id_persona = "gi.dealer_id = " . $varView['dealer_id'] . ' AND gi.tipo_form_web = "usado" ';
                break;
            case 77: //asesor usados TERMINADO------> PROBAR
                $id_persona = "gi.responsable = " . $varView['id_responsable'] . ' AND gi.tipo_form_web = "usado" ';
                break;
            case 86:// asesor de ventas externas------> TRABAJAR
                $id_persona = "gi.responsable = " . $varView['id_responsable'];
                break;
            case 85:// jefe de ventas web------> PROBAR
                $varView['lista_conce'] = $GF->getConcecionario((int) Yii::app()->user->getState('grupo_id'));
                //print_r($varView['lista_conce']);
                $array_dealers = $this->getDealerGrupoConc($varView['grupo_id']);
                $dealerList = implode(', ', $array_dealers);
                $join_ext = ' INNER JOIN usuarios u ON u.id = gi.responsable ';
                $id_persona = "gi.dealer_id IN (" . $dealerList . ") AND u.cargo_id = 86 ";
                if($tipo == 'prospeccionweb')
                    $INERProspeccion = ' LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id ';
                break;
            case 89:// asesor TELEMARKETING WEB------> TRABAJAR
              // $id_persona = "(gi.responsable = " . $varView['id_responsable'] . " OR gi.responsable_origen_tm = " . $varView['id_responsable'] . ") AND gd.desiste = 0";

            $id_persona = "(gi.responsable = " . $varView['id_responsable'] . " OR gi.responsable_origen= ".$varView['id_responsable']." OR gi.responsable_origen_tm = " . $varView['id_responsable'] . ") AND gd.desiste = 0";
            //$id_persona = "(gi.responsable_origen_tm = " . $varView['id_responsable'] . ") AND gd.desiste = 0";
                break;    
        }

        // procesamos las variables de busqueda del filtro
        $varView['js_responsable'] = 'null';
        $consultaBDC = '';

        if ($_GET['GI']) {
        //    echo '<pre>';
        //    print_r($_GET);
        //    echo '</pre>';
        //    die();

            if($varView['cargo_id']==61 && $_GET['GI']['grupo']==2){/*agrega todos los asesores teleweb a la busqueda*/


                /*$array_teleweb = array();
                //$array_teleweb = Usuarios::model()->findAll(array("condition" => 'cargo_id = 89'));
                $criteria = new CDbCriteria(array(
                    'condition' => "cargo_id = 89"
                ));

                $teleweb = Usuarios::model()->findAll($criteria);
                 $counter = 0;
                foreach ($teleweb as $value) {
                    $array_teleweb[$counter] = $value['id'];
                    $counter++;
                }
       
                    $telewebList = implode(', ', $array_teleweb);
                   
                    $id_persona .= " AND ( 
                        gi.responsable IN ({$telewebList}) OR 
                        gi.responsable_origen IN ({$telewebList}) OR 
                        gi.responsable_origen_tm IN ({$telewebList}) 
                        )";    */    
            }


            if ($_GET['GI']['fecha1'] != '') {
                //echo('fecha1');
                $gi_fecha1 = explode(" - ", $_GET['GI']['fecha1']);
                $varView['fecha_inicial_actual'] = $gi_fecha1[0];
                $varView['fecha_actual'] = $gi_fecha1[1];
                $date = new DateTime($varView['fecha_actual']);
                $varView['nombre_mes_actual'] = $date->format('F - Y');
                $varView['flag_search'] = 1;
            }
            if ($_GET['GI']['fecha2'] != '') {
                //echo('fecha2');
                $gi_fecha2 = explode(" - ", $_GET['GI']['fecha2']);
                $varView['fecha_inicial_anterior'] = $gi_fecha2[0];
                $varView['fecha_anterior'] = $gi_fecha2[1];
                $date = new DateTime($varView['fecha_anterior']);
                $varView['nombre_mes_anterior'] = $date->format('F - Y');
                $varView['flag_search'] = 2;
            }
            if ($_GET['GI']['responsable'] != '') {


                $cargo = $this->getCargo($_GET['GI']['responsable']);
                // SI EL USUARIO TIENE DOBLE CARGO, ASESOR VENTAS Y ASESOR WEB
                if($cargo == 71 || $cargo == 86 || $tipo=='super'){
                    $bdcfalse = '';
                }
                $varView['id_responsable'] = $_GET['GI']['responsable'];
                $id_persona = "gi.responsable = " . $varView['id_responsable'];
                if($tipo == 'tw')
                   $id_persona = "(gi.responsable IN({$varView['id_responsable']}) OR gi.responsable_origen IN({$varView['id_responsable']}) OR gi.responsable_origen_tm IN({$varView['id_responsable']})) AND gd.desiste = 0"; 
                if($varView['id_responsable'] == 1000 && $_GET['GI']['concesionario']!='')
                    $id_persona = "gi.dealer_id = " . $_GET['GI']['concesionario'];
                $varView['js_responsable'] = $varView['id_responsable'];
                $varView['flag_search'] = 3;
            }
            if ($_GET['GI']['concesionario'] != '') {


                $varView['$concesionario'] = $_GET['GI']['concesionario'];


                $varView["js_dealer"] = $_GET['GI']['concesionario'];
                if ($_GET['GI']['responsable'] == '') {
                    $id_persona = "gi.dealer_id = " . $varView['$concesionario'];
                }
                if($varView['$concesionario'] == 1000){ # SI LA BUSQUEDA ES TODOS LOS CONCESIONARIOS
                    $array_dealers = $this->getDealerGrupoConc($_GET['GI']['grupo']);
                    $dealerList = implode(', ', $array_dealers);
                    $id_persona = "gi.dealer_id IN ({$dealerList})";
                }
                // ASESOR WEB PARA GRUPO ASIAUTO Y KMOTOR, SE SUMA EL CONCESIONARIO SELECCIONADO EN LA BUSQUEDA
                if($varView['cargo_id'] == 85 && ($varView['grupo_id'] == 2 || $varView['grupo_id'] == 3) && $varView['$concesionario'] != 1000){
                   $id_persona .= " AND gi.dealer_id = " . $varView['$concesionario']; 
                }

              /*  if($tipo='super'){
                     $id_persona .= " AND gi.dealer_id = " . $varView['$concesionario']; 
                }*/

                $varView['flag_search'] = 4;
            }
            //echo('get tipo t: '.$_GET['GI']['tipo_t'].', get grupo: '.$_GET['GI']['grupo']).'<br />';
            if ($_GET['GI']['tipo_t'] != '' AND $_GET['GI']['grupo'] != '') {
                $con = Yii::app()->db;
                /*if ($_GET['GI']['tipo_t'] == 'provincias') {
                    $varView['checked_p'] = true;
                    $varView['checked_g'] = false;
                    $varView['id_provincia'] = $_GET['GI']['provincias'];
                    $cond_conce = 'provincia';
                    $id_busqueda = $varView['id_provincia'];
              
                } else {
                    $varView['checked_g'] = true;
                    $varView['checked_p'] = false;
                    $varView['id_grupo'] = $_GET['GI']['grupo'];
                    $cond_conce = 'id_grupo';
                    $id_busqueda = $varView['id_grupo'];
                }*/
                if($_GET['GI']['concesionario'] != 1000 && $_GET['GI']['concesionario'] != ''){
                   // die('first');
                    $varView['flag_search'] = 51;
                    $varView['$concesionario'] = $_GET['GI']['concesionario'];
                    $varView['checked_g'] = true;
                    $varView['checked_p'] = false;
                    $varView['id_grupo'] = $_GET['GI']['grupo'];
                    $varView["js_dealer"] = $_GET['GI']['concesionario'];
                    $cond_conce = 'id_grupo';
                    $id_busqueda = $varView['id_grupo'];
                    //$grupos_sql = "SELECT * from gr_concesionarios WHERE " . $cond_conce . " = " . $id_busqueda;
                    $grupos_sql = "SELECT * from gr_concesionarios WHERE dealer_id = " . $_GET['GI']['concesionario'];
                }
                if($_GET['GI']['concesionario'] != 1000 && $_GET['GI']['responsable'] == 1000){

                    // die('second');
                    $varView['flag_search'] = 52;
                    $varView['$concesionario'] = $_GET['GI']['concesionario'];
                    $varView['checked_g'] = true;
                    $varView['checked_p'] = false;
                    $varView['id_grupo'] = $_GET['GI']['grupo'];
                    $varView["js_dealer"] = $_GET['GI']['concesionario'];
                    $cond_conce = 'id_grupo';
                    $id_busqueda = $varView['id_grupo'];
                    $grupos_sql = "SELECT * from gr_concesionarios WHERE " . $cond_conce . " = " . $id_busqueda;
                }
                if($_GET['GI']['grupo'] != 1000 && $_GET['GI']['concesionario'] == ''){

                    // die('tjirf');
                    $varView['flag_search'] = 53;
                    $varView['checked_g'] = true;
                    $varView['checked_p'] = false;
                    $varView['id_grupo'] = $_GET['GI']['grupo'];
                    $cond_conce = 'id_grupo';
                    $id_busqueda = $varView['id_grupo'];
                    $grupos_sql = "SELECT * from gr_concesionarios WHERE id_grupo = " . $varView['id_grupo'];
                }
                if($_GET['GI']['grupo'] == 1000 && $_GET['GI']['concesionario'] == '' && $_GET['tipo'] == 'tw'){

                     //die('four');
                    $varView['flag_search'] = 56;
                    $varView['checked_g'] = true;
                    $varView['checked_p'] = false;
                    $varView['id_grupo'] = $_GET['GI']['grupo'];
                    $cond_conce = 'id_grupo';
                    $id_busqueda = $varView['id_grupo'];
                    $grupos_sql = "SELECT * from gr_concesionarios WHERE id_grupo = 2";
                }
                if($_GET['GI']['grupo'] != 1000 && $_GET['GI']['concesionario'] == 1000){
                     //die('five');
                    $varView['flag_search'] = 55;
                    $varView['checked_g'] = true;
                    $varView['checked_p'] = false;
                    $varView['id_grupo'] = $_GET['GI']['grupo'];
                    $cond_conce = 'id_grupo';
                    $id_busqueda = $varView['id_grupo'];
                    $grupos_sql = "SELECT * from gr_concesionarios WHERE id_grupo = " . $varView['id_grupo'];
                }
                elseif($_GET['tipo'] == 'tw'){

                    //die('here');
                    $varView['flag_search'] = 54;
                    $varView['$concesionario'] = $_GET['GI']['concesionario'];
                    $varView['checked_g'] = true;
                    $varView['checked_p'] = false;
                    $varView['id_grupo'] = $_GET['GI']['grupo'];
                    $varView["js_dealer"] = $_GET['GI']['concesionario'];
                    $grupos_sql = "SELECT * from gr_concesionarios";
                    //die('here');
                }
                //die('sql: '.$grupos_sql);
                
                $request_sql = $con->createCommand($grupos_sql)->queryAll();

                foreach ($request_sql as $key3 => $value3) {
                    $conse_active .= $value3['dealer_id'] . ', ';
                }
                $conse_active = rtrim($conse_active, ", ");
                $condicion_GP = ' AND (dealer_id IN (' . $conse_active . ')) ';
                //echo $condicion_GP;
                //die();
                //$varView['flag_search'] = 5;
            }
            //die('flag_search: '.$varView['flag_search']);

            if ($_GET['GI']['tipo_t'] != '' AND $_GET['GI']['provincias'] != '' AND $_GET['GI']['provincias'] != 1000) {
                //die('enter provincia');
                $con = Yii::app()->db;
                /*if ($_GET['GI']['tipo_t'] == 'provincias') {
                    $varView['checked_p'] = true;
                    $varView['checked_g'] = false;
                    $varView['id_provincia'] = $_GET['GI']['provincias'];
                    $cond_conce = 'provincia';
                    $id_busqueda = $varView['id_provincia'];
              
                } else {
                    $varView['checked_g'] = true;
                    $varView['checked_p'] = false;
                    $varView['id_grupo'] = $_GET['GI']['grupo'];
                    $cond_conce = 'id_grupo';
                    $id_busqueda = $varView['id_grupo'];
                }*/
                if($_GET['GI']['concesionario'] != 1000 && $_GET['GI']['concesionario'] != ''){
                    $varView['checked_p'] = true;
                    $varView['checked_g'] = false;
                    $varView['id_provincia'] = $_GET['GI']['provincias'];
                    $cond_conce = 'provincia';
                    $id_busqueda = $varView['id_provincia'];
                    //$grupos_sql = "SELECT * from gr_concesionarios WHERE " . $cond_conce . " = " . $id_busqueda;
                    $grupos_sql = "SELECT * from gr_concesionarios WHERE dealer_id = " . $_GET['GI']['concesionario'];
                }
                if($_GET['GI']['concesionario'] != 1000 && $_GET['GI']['responsable'] == 1000){

                    $varView['checked_p'] = true;
                    $varView['checked_g'] = false;
                    $varView['id_provincia'] = $_GET['GI']['provincias'];
                    $cond_conce = 'provincia';
                    $id_busqueda = $varView['id_provincia'];
                    $grupos_sql = "SELECT * from gr_concesionarios WHERE " . $cond_conce . " = " . $id_busqueda;

                }
                if($_GET['GI']['concesionario'] == 1000 || $_GET['GI']['concesionario'] == ''){
                    $varView['checked_p'] = true;
                    $varView['checked_g'] = false;
                    $varView['id_provincia'] = $_GET['GI']['provincias'];
                    $cond_conce = 'provincia';
                    $id_busqueda = $varView['id_provincia'];
                    $grupos_sql = "SELECT * from gr_concesionarios WHERE " . $cond_conce . " = " . $id_busqueda;
                }
                /*else{ # BUQUEDA POR DEFECTO AL DAR CLICK EN TODOS
                    $varView['$concesionario'] = $_GET['GI']['concesionario'];
                    $varView['checked_p'] = true;
                    $varView['checked_g'] = false;
                    $varView['id_provincia'] = $_GET['GI']['provincias'];
                    $grupos_sql = "SELECT * from gr_concesionarios";
                }*/
                
                //die('grupos sql: '.$grupos_sql);
                $request_sql = $con->createCommand($grupos_sql);
                $request_sql = $request_sql->queryAll();

                foreach ($request_sql as $key3 => $value3) {
                    $conse_active .= $value3['dealer_id'] . ', ';
                }
                $conse_active = rtrim($conse_active, ", ");
                $condicion_GP = ' AND (dealer_id IN (' . $conse_active . ')) ';
                //echo $condicion_GP;
                //die();
                $varView['flag_search'] = 6;
            }

            if ($_GET['GI']['tipo'] != '') {
                if ($_GET['GI']['tipo'] == 'traficoacumulado') {
                    $varView['checked_ta'] = true;
                    $varView['checked_ge'] = false;
                    $varView['checked_us'] = false;
                    $varView['checked_ex'] = false;
                    $varView['checked_bdc'] = false;
                } else if ($_GET['GI']['tipo'] == 'general') {
                    $varView['checked_ta'] = false;
                    $varView['checked_ge'] = true;
                    $varView['checked_us'] = false;
                    $varView['checked_ex'] = false;
                    $varView['checked_bdc'] = false;
                } else if ($_GET['GI']['tipo'] == 'usados') {
                    $varView['checked_ta'] = false;
                    $varView['checked_ge'] = false;
                    $varView['checked_us'] = true;
                    $varView['checked_ex'] = false;
                    $varView['checked_bdc'] = false;
                } else if ($_GET['GI']['tipo'] == 'bdc') {
                    $varView['checked_ta'] = false;
                    $varView['checked_ge'] = false;
                    $varView['checked_us'] = false;
                    $varView['checked_ex'] = false;
                    $varView['checked_bdc'] = true;
                    if ($_GET['GI']['estadoBDC'] != '') {
                        $estadoBDC = $_GET['GI']['estadoBDC'];
                        if ($estadoBDC == 'desiste') {
                            $estadoBDC_val = ' desiste != 0';
                        } else {
                            $estadoBDC_val = ' desiste = 0';
                        }
                        $con = Yii::app()->db;

                        $sql_estadoBDC = "select distinct id_informacion, desiste from  gestion_diaria where" . $estadoBDC_val;
                        $request_estadoBDC = $con->createCommand($sql_estadoBDC);
                        $request_estadoBDC = $request_estadoBDC->queryAll();

                        $id_info_estadoBD = '';
                        foreach ($request_estadoBDC as $key_estadoBDC => $value_estadoBDC) {
                            $id_info_estadoBD .= $value_estadoBDC['id_informacion'] . ', ';
                        }
                        $id_info_estadoBD = rtrim($id_info_estadoBD, ", ");
                        //die($id_info_estadoBD);
                        $consultaBDC .= " AND gi.id IN (" . $id_info_estadoBD . ") ";
                    } else {
                        $consultaBDC .= " AND gi.bdc = 1 ";
                    }
                } else if ($_GET['GI']['tipo'] == 'exonerados') {
                    $varView['checked_ta'] = false;
                    $varView['checked_ge'] = false;
                    $varView['checked_us'] = false;
                    $varView['checked_ex'] = true;
                    $varView['checked_bdc'] = false;
                    if ($_GET['GI']['tipoExo'] != '') {
                        $tipoexo = $_GET['GI']['tipoExo'];
                        $consultaBDC = " AND  gi.tipo_ex = '" . $tipoexo . "' ";
                    } else {
                        $consultaBDC .= " AND gi.tipo_ex IS NOT NULL ";
                    }
                }
            }
         } else {
            $varView['checked_g'] = true;
            $varView['checked_ge'] = true;
        }

        //GET Modelos activos en rango de fechas
        if ($_GET['GI']['tipo'] == 'traficoacumulado') {
            $modelosClass = new Modelos;
            $modelos_ma = $modelosClass->getModleosActivos('2016-01-01', '2016-01-31', '2016-02-01', '2016-02-28', $varView['lista_datos'], 'general');
        } else {
            $modelosClass = new Modelos;
            $modelos_ma = $modelosClass->getModleosActivos($varView['fecha_inicial_anterior'], $varView['fecha_anterior'], $varView['fecha_inicial_actual'], $varView['fecha_actual'], $varView['lista_datos'], 'general');
        }
        $varView['filtro_modelos'] = $modelos_ma;

        //Check if TRAFICO ACUMLADO ESTA ACTIVO
        $varView['TA'] = false;
        $varView['TAchecked_gp'] = 'p';

        if ($_GET['GI']['tipo'] == 'traficoacumulado') {
            $varView['TA'] = true;
            $varView['TAconsulta'] = [];
            if ($_GET['TA']['modelo'] != '') {
                $varView['TAmodelo'] = $_GET['TA']['modelo'];
            }
            if ($_GET['TA']['concesionarios'] != '') {
                $varView['TAconcesionarios'] = $_GET['TA']['concesionarios'];
                $varView['TAconsulta']['concesionarios'] = $_GET['TA']['concesionarios'];
            }
            if ($_GET['TA']['provincia'] != '') {
                $varView['TAprovincia'] = $_GET['TA']['provincia'];
                $varView['TAconsulta']['provincia'] = $_GET['TA']['provincia'];
                $varView['TAchecked_gp'] = 'p';
            }
            if ($_GET['TA']['grupo'] != '') {
                $varView['TAgrupo'] = $_GET['TA']['grupo'];
                $varView['TAconsulta']['grupo'] = $_GET['TA']['grupo'];
                $varView['TAchecked_gp'] = 'g';
            }

            $traficoAcumulado = new traficoAcumulado;
            $fechas[0] = $varView['fecha_inicial_anterior'];
            $fechas[1] = $varView['fecha_anterior'];
            $fechas[2] = $varView['fecha_inicial_actual'];
            $fechas[3] = $varView['fecha_actual'];

            // TRAER TRAFICO ACUMULADO DE BASES DE DATOS ENVIADOS POR DAVID EN EXCEL
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
            // TRAFICO ACUMULADO MES ANTERIOR
            foreach ($retorno['mant'] as $tipo) {
                if ($tipo['tipo'] == 'PROSPECCION') {
                    $contador['prospecion_mant'][] = $tipo['tipo'];
                    if (in_array($tipo['modelo'], $ckd)) {
                        $contador['prospecion_ckd_mant'][] = $tipo['modelo'];
                    }
                }
                else if ($tipo['tipo'] == 'TRAFICO') {
                    $contador['trafico_mant'][] = $tipo['tipo'];
                    if (in_array($tipo['modelo'], $ckd)) {
                        $contador['trafico_ckd_mant'][] = $tipo['modelo'];
                    }
                } else if ($tipo['tipo'] == 'TESTDRIVE') {
                    $contador['testdrive_mant'][] = $tipo['tipo'];
                    if (in_array($tipo['modelo'], $ckd)) {
                        $contador['testdrive_ckd_mant'][] = $tipo['modelo'];
                    }
                } else if ($tipo['tipo'] == 'PROFORMA') {
                    $contador['proforma_mant'][] = $tipo['tipo'];
                    if (in_array($tipo['modelo'], $ckd)) {
                        $contador['proforma_ckd_mant'][] = $tipo['modelo'];
                    }
                } else if ($tipo['tipo'] == 'VENTAS') {

                    $contador['ventas_mant'][] = $tipo['tipo'];
                    if (in_array($tipo['modelo'], $ckd)) {
                        $contador['ventas_ckd_mant'][] = $tipo['modelo'];
                    }

                }
            }

            // TRAFICO ACUMULADO MES ACTUAL
            foreach ($retorno['mact'] as $tipo) {
                if ($tipo['tipo'] == 'PROSPECCION') {
                    $contador['prospecion_mact'][] = $tipo['tipo'];
                    if (in_array($tipo['modelo'], $ckd)) {
                        $contador['prospecion_ckd_mact'][] = $tipo['modelo'];
                    }
                }
                else if ($tipo['tipo'] == 'TRAFICO') {
                    $contador['trafico_mact'][] = $tipo['tipo'];
                    if (in_array($tipo['modelo'], $ckd)) {
                        $contador['trafico_ckd_mact'][] = $tipo['modelo'];
                    }
                } else if ($tipo['tipo'] == 'TESTDRIVE') {
                    $contador['testdrive_mact'][] = $tipo['tipo'];
                    if (in_array($tipo['modelo'], $ckd)) {
                        $contador['testdrive_ckd_mact'][] = $tipo['modelo'];
                    }
                } else if ($tipo['tipo'] == 'PROFORMA') {
                    $contador['proforma_mact'][] = $tipo['tipo'];
                    if (in_array($tipo['modelo'], $ckd)) {
                        $contador['proforma_ckd_mact'][] = $tipo['modelo'];
                    }
                } else if ($tipo['tipo'] == 'VENTAS') {
                    $contador['ventas_mact'][] = $tipo['tipo'];
                    if (in_array($tipo['modelo'], $ckd)) {
                        $contador['ventas_ckd_mact'][] = $tipo['modelo'];
                    }
                }
            }

            $varView['prospeccion_mes_anterior'] = count($contador['prospecion_mant']);
            $varView['prospeccion_mes_actual'] = count($contador['prospecion_mact']);
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
            $varView['vh_mes_actual'] = count($contador['ventas_mact']);




            $varView['vhckd1'] = count($contador['ventas_ckd_mant']);
            $varView['vhcbu1'] = count($contador['ventas_mant']) - count($contador['ventas_ckd_mant']);
            $varView['vhckd2'] = count($contador['ventas_ckd_mact']);
            $varView['vhcbu2'] = count($contador['ventas_mact']) - count($contador['ventas_ckd_mact']);
        } else {
            if ($varView['cargo_id'] != 72 || $varView['cargo_id'] != 73) {
                $id_persona = $id_persona . $bdcfalse;
            }

            $constructor = new ConstructorSQL;

            if($_GET['GI']['grupo']==2 && $_GET['GI']['concesionario']=="")$conc=2;
            else $conc=$_GET['GI']['concesionario'];
            
          

            if($_GET['GI']['grupo']==2 && $_GET['GI']['concesionario']=="")$conc=2;
            else $conc=$_GET['GI']['concesionario'];

           // die($id_persona.' '.$join_ext);
           /*if($tipo!='super')*/
                $retorno = $constructor->buscar(
                        $varView['cargo_id'], $varView['id_responsable'], $select_ext, $join_ext, $id_persona, $group_ext, $varView['fecha_inicial_anterior'], $varView['fecha_anterior'], $varView['fecha_inicial_actual'], $varView['fecha_actual'], /*$varView['concesionario']*/$conc, $tipos, $SQLmodelos, $INERmodelos, $INERmodelos_td, $INERProspeccion,$consultaBDC, $condicion_GP, $tipo
                );

        //    echo '<pre>';
        //    print_r($retorno);
        //    echo '</pre>';
        //    die();


            //$varView['prospeccion_mes_anterior'] = $retorno[0];
            $varView['prospeccion_mes_anterior'] = $retorno['prospeccion_mes_anterior'];
            //$varView['prospeccion_mes_actual'] = $retorno[1];
            $varView['prospeccion_mes_actual'] = $retorno['prospeccion_mes_actual'];
           // $varView['trafico_mes_anterior'] = $retorno[2];
            $varView['trafico_mes_anterior'] = $retorno['trafico_mes_anterior'];
            //$varView['trafico_mes_actual'] = $retorno[3];
            $varView['trafico_mes_actual'] = $retorno['trafico_mes_actual'];
            $varView['traficockd1'] = $retorno['traficockd1'];
            $varView['traficocbu1'] = $retorno['traficocbu1'];
            $varView['traficockd2'] = $retorno['traficockd2'];
            $varView['traficocbu2'] = $retorno['traficocbu2'];
            if($tipo == 'externas'||$tipo == 'tw'){
               

                $varView['trafico_mes_anterior'] = $retorno['trafico_mes_anterior'];//$retorno['prospeccion_mes_anterior'];
                $varView['trafico_mes_actual'] = $retorno['prospeccion_mes_actual'];//$retorno['trafico_mes_actual'];
              

                $varView['trafico_citas_mes_anterior'] = $retorno['trafico_citas_mes_anterior'];
                $varView['trafico_citas_mes_actual'] = $retorno['trafico_citas_mes_actual'];

                $varView['trafico_citas_concretadas_mes_anterior'] = $retorno['trafico_citas_concretadas_mes_anterior'];
                $varView['trafico_citas_concretadas_mes_actual'] = $retorno['trafico_citas_concretadas_mes_actual'];

                
            }

            $varView['proforma_mes_anterior'] = $retorno['proforma_mes_anterior'];
            $varView['proforma_mes_actual'] = $retorno['proforma_mes_actual'];
            $varView['proformackd1'] = $retorno['proformackd1'];
            $varView['proformacbu1'] = $retorno['proformacbu1'];
            $varView['proformackd2'] = $retorno['proformackd2'];
            $varView['proformacbu2'] = $retorno['proformacbu2'];
            $varView['td_mes_anterior'] = $retorno['td_mes_anterior'];
            $varView['td_mes_actual'] = $retorno['td_mes_actual'];
            $varView['tdckd1'] = $retorno['tdckd1'];
            $varView['tdcbu1'] = $retorno['tdcbu1'];
            $varView['tdckd2'] = $retorno['tdckd2'];
            $varView['tdcbu2'] = $retorno['tdcbu2'];
            $varView['vh_mes_anterior'] = $retorno['vh_mes_anterior'];
            $varView['vh_mes_actual'] = $retorno['vh_mes_actual'];
            $varView['vhckd1'] = $retorno['vhckd1'];
            $varView['vhcbu1'] = $retorno['vhcbu1'];
            $varView['vhckd2'] = $retorno['vhckd2'];
            $varView['vhcbu2'] = $retorno['vhcbu2'];
           
            $varView['cotizaciones_enviadas_anterior'] = $retorno['cotizaciones_enviadas_anterior'];
            $varView['cotizaciones_enviadas_actual'] = $retorno['cotizaciones_enviadas_actual'];
            $varView['respuestas_enviadas_anterior'] = $retorno['respuestas_enviadas_anterior'];
            $varView['respuestas_enviadas_actual'] = $retorno['respuestas_enviadas_actual'];
            $varView['proformas_enviadas_anterior'] = $retorno['proformas_enviadas_anterior'];
            $varView['proformas_enviadas_actual'] = $retorno['proformas_enviadas_actual'];

             $cont=count($retorno)-1;   
             $varView['vcont_mes_anterior'] = $retorno['vcont_mes_anterior'];
             $varView['vcred_mes_anterior'] = $retorno['vcred_mes_anterior'];
             $varView['vcont_mes_actual'] = $retorno['vcont_mes_actual'];
             $varView['vcred_mes_actual'] = $retorno['vcred_mes_actual'];

        }

        $varView['dif_ckd_trafico'] = $varView['traficockd2'] - $varView['traficockd1'];
        $varView['dif_cbu_trafico'] = $varView['traficocbu2'] - $varView['traficocbu1'];
        //$varView['usu'] = $usu;
        //$varView['mod'] = $mod;      
        //set diferencias
        
        $varView['var_prp'] = $GF->DIFconstructor($varView['prospeccion_mes_actual'], $varView['prospeccion_mes_anterior'], 'var');
        $varView['dif_prp'] = $GF->DIFconstructor($varView['prospeccion_mes_actual'], $varView['prospeccion_mes_anterior'], 'dif');
        
        $varView['var_raa'] = $GF->DIFconstructor($varView['respuestas_enviadas_actual'], $varView['respuestas_enviadas_anterior'], 'var');
        $varView['dif_rac'] = $GF->DIFconstructor($varView['respuestas_enviadas_actual'], $varView['respuestas_enviadas_anterior'], 'dif');
        
        $varView['var_tr'] = $GF->DIFconstructor($varView['trafico_mes_actual'], $varView['trafico_mes_anterior'], 'var');
        $varView['dif_tr'] = $GF->DIFconstructor($varView['trafico_mes_actual'], $varView['trafico_mes_anterior'], 'dif');

        $varView['var_pr'] = $GF->DIFconstructor($varView['proforma_mes_actual'], $varView['proforma_mes_anterior'], 'var');
        $varView['dif_pr'] = $GF->DIFconstructor($varView['proforma_mes_actual'], $varView['proforma_mes_anterior'], 'dif');

        $varView['var_td'] = $GF->DIFconstructor($varView['td_mes_actual'], $varView['td_mes_anterior'], 'var');
        $varView['dif_td'] = $GF->DIFconstructor($varView['td_mes_actual'], $varView['td_mes_anterior'], 'dif');

        $varView['var_ve'] = $GF->DIFconstructor($varView['vh_mes_actual'], $varView['vh_mes_anterior'], 'var');
        $varView['dif_ve'] = $GF->DIFconstructor($varView['vh_mes_actual'], $varView['vh_mes_anterior'], 'dif');

        
        $varView['var_cred'] = $GF->DIFconstructor($varView['vcred_mes_actual'], $varView['vcred_mes_anterior'], 'var');
        $varView['dif_cred'] = $GF->DIFconstructor($varView['vcred_mes_actual'], $varView['vcred_mes_anterior'], 'dif');


        $varView['var_cont'] = $GF->DIFconstructor($varView['vcont_mes_actual'], $varView['vcont_mes_anterior'], 'var');
        $varView['dif_cont'] = $GF->DIFconstructor($varView['vcont_mes_actual'], $varView['vcont_mes_anterior'], 'dif');

        $varView['var_citas_generadas'] = $GF->DIFconstructor($varView['trafico_citas_mes_actual'], $varView['trafico_citas_mes_anterior'], 'var');
        $varView['dif_citas_generadas'] = $GF->DIFconstructor($varView['trafico_citas_mes_actual'], $varView['trafico_citas_mes_anterior'], 'dif');
        $varView['var_citas_concretadas'] = $GF->DIFconstructor($varView['trafico_citas_concretadas_mes_actual'], $varView['trafico_citas_concretadas_mes_anterior'], 'var');
        $varView['dif_citas_concretadas'] = $GF->DIFconstructor($varView['trafico_citas_concretadas_mes_actual'], $varView['trafico_citas_concretadas_mes_anterior'], 'dif');        



        $varView['titulo'] = $tit_init . $fecha_inicial_actual . ' / ' . $fecha_actual . ', y ' . $fecha_inicial_anterior . ' / ' . $fecha_anterior . $tit_ext;


        //set Tasas
        //tasa proformas

        $varView['tasa_proforma_actual'] = $GF->tasa($varView['trafico_mes_actual'], $varView['proforma_mes_actual']);
        $varView['tasa_proforma_anterior'] = $GF->tasa($varView['trafico_mes_anterior'], $varView['proforma_mes_anterior']);
        $varView['tasa_dif_proforma'] = $GF->tasa_dif($varView['tasa_proforma_actual'], $varView['tasa_proforma_anterior']);

        //tasa test drive
        $varView['tasa_testdrive_actual'] = $GF->tasa($varView['trafico_mes_actual'], $varView['td_mes_actual']);
        $varView['tasa_testdrive_anterior'] = $GF->tasa($varView['trafico_mes_anterior'], $varView['td_mes_anterior']);
        $varView['tasa_dif_testdrive'] = $GF->tasa_dif($varView['tasa_testdrive_actual'], $varView['tasa_testdrive_anterior']);

        //tasa cierre
        
        $varView['tasa_cierre_actual'] = $GF->tasa($varView['trafico_mes_actual'], $varView['vh_mes_actual']);
        $varView['tasa_cierre_anterior'] = $GF->tasa($varView['trafico_mes_anterior'], $varView['vh_mes_anterior']);
        $varView['tasa_dif_cierre'] = $GF->tasa_dif($varView['tasa_cierre_actual'], $varView['tasa_cierre_anterior']);


        //tasa cierre credito
        $varView['tasa_credito_actual'] = $GF->tasa($varView['trafico_mes_actual'], $varView['vcred_mes_actual']);
        $varView['tasa_credito_anterior'] = $GF->tasa($varView['trafico_mes_anterior'], $varView['vcred_mes_anterior']);
        $varView['tasa_dif_credito'] = $GF->tasa_dif($varView['tasa_credito_actual'], $varView['tasa_credito_anterior']);

        //tasa cierre contado
        $varView['tasa_contado_actual'] = $GF->tasa($varView['trafico_mes_actual'], $varView['vcont_mes_actual']);
        $varView['tasa_contado_anterior'] = $GF->tasa($varView['trafico_mes_anterior'], $varView['vcont_mes_anterior']);
        $varView['tasa_dif_contado'] = $GF->tasa_dif($varView['tasa_contado_actual'], $varView['tasa_contado_anterior']);


        //tasa proformas ckd y cbu
        $varView['tasa_proforma_ckd_m1'] = $GF->tasa($varView['traficockd2'], $varView['proformackd2']);
        $varView['tasa_proforma_cbu_m1'] = $GF->tasa($varView['traficocbu2'], $varView['proformacbu2']);
        $varView['tasa_proforma_ckd_m2'] = $GF->tasa($varView['traficockd1'], $varView['proformackd1']);
        $varView['tasa_proforma_cbu_m2'] = $GF->tasa($varView['traficocbu1'], $varView['proformacbu1']);

        //tasa testdrive ckd y cbu
        $varView['tasa_testdrive_ckd_m1'] = $GF->tasa($varView['traficockd2'], $varView['tdckd2']);
        $varView['tasa_testdrive_cbu_m1'] = $GF->tasa($varView['traficocbu2'], $varView['tdcbu2']);
        $varView['tasa_testdrive_ckd_m2'] = $GF->tasa($varView['traficockd1'], $varView['tdckd1']);
        $varView['tasa_testdrive_cbu_m2'] = $GF->tasa($varView['traficocbu1'], $varView['tdcbu1']);

        //tasa cierre ckd y cbu
        $varView['tasa_cierre_ckd_m1'] = $GF->tasa($varView['traficockd2'], $varView['vhckd2']);
        $varView['tasa_cierre_cbu_m1'] = $GF->tasa($varView['traficocbu2'], $varView['vhcbu2']);
        $varView['tasa_cierre_ckd_m2'] = $GF->tasa($varView['traficockd1'], $varView['vhckd1']);
        $varView['tasa_cierre_cbu_m2'] = $GF->tasa($varView['traficocbu1'], $varView['vhcbu1']);

        //set diferencias ckd y cbu
        $varView['tasa_td_dif_ckd'] = rtrim($varView['tasa_testdrive_ckd_m1'], "%") - rtrim($varView['tasa_testdrive_ckd_m2'], "%");
        $varView['tasa_td_dif_cbu'] = rtrim($varView['tasa_testdrive_cbu_m1'], "%") - rtrim($varView['tasa_testdrive_cbu_m2'], "%");
        $varView['tasa_pr_dif_ckd'] = rtrim($varView['tasa_proforma_ckd_m1'], "%") - rtrim($varView['tasa_proforma_ckd_m2'], "%");
        $varView['tasa_pr_dif_cbu'] = rtrim($varView['tasa_proforma_cbu_m1'], "%") - rtrim($varView['tasa_proforma_cbu_m2'], "%");
        $varView['tasa_cierre_dif_ckd'] = rtrim($varView['tasa_cierre_ckd_m1'], "%") - rtrim($varView['tasa_cierre_ckd_m2'], "%");
        $varView['tasa_cierre_dif_cbu'] = rtrim($varView['tasa_cierre_cbu_m1'], "%") - rtrim($varView['tasa_cierre_cbu_m2'], "%");

        // tasa de testdrive teleweb
        $varView['tasa_td_tw_actual'] = $GF->tasa($varView['trafico_citas_concretadas_mes_actual'], $varView['td_mes_actual']);
        $varView['tasa_td_tw_anterior'] = $GF->tasa($varView['trafico_citas_concretadas_mes_anterior'], $varView['td_mes_anterior']); 
        
        // tasa de cierre ventas teleweb
        $varView['tasa_cierre_tw_actual'] = $GF->tasa($varView['trafico_citas_concretadas_mes_actual'], $varView['vh_mes_actual']);
        $varView['tasa_cierre_tw_anterior'] = $GF->tasa($varView['trafico_citas_concretadas_mes_anterior'], $varView['vh_mes_anterior']); 
               

        $this->render('inicio', array('varView' => $varView, 'tipo' => $tipo));
    }

    /* AJAX CALLS */
    
    public function actionBdc($id_informacion = null, $id_vehiculo = null, $tipo = null){
        // CARGA POR DEFECTO DE LAS BUSQUEDAS POR FECHAS DE INICIO
        if(!is_null($tipo)){
            $tipo = $tipo;
        }
        $GF = new GlobalFunctions;

        date_default_timezone_set('America/Guayaquil');
        $dt = time();
        setlocale(LC_TIME, 'es_ES.UTF-8');

        $varView = array();
        
        // USUARIOS PREDETERMINADOS, CARGOS, RESPONSABLE        
        $varView['grupo_id'] = (int) Yii::app()->user->getState('grupo_id');
        $varView['cargo_id'] = (int) Yii::app()->user->getState('cargo_id');
        $varView['id_responsable'] = Yii::app()->user->getId();
        $varView['dealer_id'] = $this->getDealerId($varView['id_responsable']);
        $varView['nombre_usuario'] = Usuarios::model()->findByPk($varView['id_responsable']);
        $varView['provincia_id'] = $varView['nombre_usuario']->provincia_id;
        $varView['cargo_usuario'] = Cargo::model()->findByPk($varView['nombre_usuario']->cargo_id);
       

        if ($varView['nombre_usuario']->dealers_id != '') {
            $varView['consecionario_usuario'] = '<b>Concesionario:</b> ' . GrConcesionarios::model()->find('dealer_id=' . $varView['nombre_usuario']->dealers_id)['nombre'];
        }


        $varView['titulo'] = '';
        $varView['concesionario'] = 2000;
        $varView['tipos'] = null;

        $varView['fecha_actual'] = strftime("%Y-%m-%d", $dt);
        $varView['fecha_actual2'] = strtotime('+1 day', strtotime($varView['fecha_actual']));
        $varView['fecha_actual2'] = date('Y-m-d', $varView['fecha_actual2']);
        $varView['fecha_inicial_actual'] = (new DateTime('first day of this month'))->format('Y-m-d');
        $varView['fecha_anterior'] = strftime("%Y-%m-%d", strtotime('-1 month', $dt));
        $varView['fecha_inicial_anterior'] = strftime("%Y-%m", strtotime('-1 month', $dt)) . '-01';
        $varView['nombre_mes_actual'] = strftime("%B - %Y", $dt);
        $varView['nombre_mes_anterior'] = strftime("%B - %Y", strtotime('-1 month', $dt));
        
                
        $this->render('reportesbdc', array());
    }

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

    /* FIN AJAX CALLS */

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
