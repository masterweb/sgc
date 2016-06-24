<?php
 
class ConstructorSQL{
	function SQLconstructor($selection, $table, $join, $where, $group = null){
        $con = Yii::app()->db;
        $sql_cons = "SELECT {$selection} from {$table} {$join}
        WHERE {$where} {$group}";
        //echo $sql_cons.'<br><br>';

        $request_cons = $con->createCommand($sql_cons);
        return  $request_cons->queryAll();
    }

    function buscar($cargo_id, $id_responsable, $select_ext, $join_ext, $id_persona, $group_ext, $fecha_inicial_anterior, $fecha_anterior, $fecha_inicial_actual, $fecha_actual, $concesionario = 0, $tipos = null, $carros, $INERmodelos, $INERmodelos_td, $consultaBDC, $condicion_GP = null){
        if(!empty($carros['modelos']) || !empty($carros['versiones'])){
            $datos_solo_modelos = $this->renderBusqueda($cargo_id, $id_responsable, $select_ext, $join_ext, $id_persona, $group_ext, $fecha_inicial_anterior, $fecha_anterior, $fecha_inicial_actual, $fecha_actual, $concesionario, $tipos, $carros, $INERmodelos, $INERmodelos_td, $consultaBDC, $condicion_GP);
            if($_GET['todos']){
                $datos_enteros = $this->renderBusqueda($cargo_id, $id_responsable, $select_ext, $join_ext, $id_persona, $group_ext, $fecha_inicial_anterior, $fecha_anterior, $fecha_inicial_actual, $fecha_actual, $concesionario, $tipos, null, null, null, $consultaBDC, $condicion_GP); 
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
            $varView = $this->renderBusqueda($cargo_id, $id_responsable, $select_ext, $join_ext, $id_persona, $group_ext, $fecha_inicial_anterior, $fecha_anterior, $fecha_inicial_actual, $fecha_actual, $concesionario, $tipos, $carros, $INERmodelos, $INERmodelos_td, $consultaBDC, $condicion_GP);
        } 
        return $varView;     
    }

    function renderBusqueda($cargo_id, $id_responsable, $select_ext, $join_ext, $id_persona, $group_ext, $fecha_inicial_anterior, $fecha_anterior, $fecha_inicial_actual, $fecha_actual, $concesionario = 0, $tipos = null, $carros, $INERmodelos, $INERmodelos_td, $consultaBDC, $consulta_gp = null){
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
            $join_ext.$INERmodelos.' LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id ', 
            $id_persona.$consultaBDC.$modelos.$versiones.$consulta_gp." AND (DATE(gi.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."') AND (gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'exhibicion'  OR gd.fuente_contacto = 'trafico' ) ", 
            $group_ext
        );
        $trafico_mes_anterior = $trafico_mes_anterior[0]['COUNT(*)'];
        $retorno[] = $trafico_mes_anterior;                                      
        
        $trafico_mes_actual = $this->SQLconstructor(
            'COUNT(*) '.$select_ext, 
            'gestion_informacion gi', 
            $join_ext.$INERmodelos.' LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id ', 
            $id_persona.$consultaBDC.$modelos.$versiones.$consulta_gp." AND (DATE(gi.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."') AND (gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'exhibicion'  OR gd.fuente_contacto = 'trafico' ) ", 
            $group_ext
        );
        $trafico_mes_actual = $trafico_mes_actual[0]['COUNT(*)'];
        $retorno[] = $trafico_mes_actual;
        

            $traficockd1 = $this->SQLconstructor(
                'COUNT(*) '.$select_ext, 
                'gestion_informacion gi', 
                $join_ext.' LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id  LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id ', 
                $id_persona.$consultaBDC.$modelos.$versiones.$consulta_gp." AND (DATE(gi.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."') AND ((gv.modelo IN (".$CKDsRender.")) OR gi.modelo IN (".$CKDsRender.")) AND (gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'exhibicion'  OR gd.fuente_contacto = 'trafico' ) ", 
                $group_ext
            );
            $traficockd1 = $traficockd1[0]['COUNT(*)'];
            $retorno[] = $traficockd1;
            $traficocbu1 = $trafico_mes_anterior - $traficockd1; // resto de modelos
            $retorno[] = $traficocbu1;        

            $traficockd2 = $this->SQLconstructor(
                'COUNT(*) '.$select_ext, 
                'gestion_informacion gi', 
                $join_ext.' LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id  LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id ', 
                $id_persona.$consultaBDC.$modelos.$versiones.$consulta_gp." AND (DATE(gi.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."')  AND ((gv.modelo IN (".$CKDsRender.")) OR gi.modelo IN (".$CKDsRender.")) AND (gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'exhibicion'  OR gd.fuente_contacto = 'trafico' ) ", 
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
            $id_persona.$consultaBDC.$modelos.$versiones.$consulta_gp." AND (DATE(gf.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."') ", 
            $group_ext
        );
        $proforma_mes_anterior = $proforma_mes_anterior[0]['COUNT(*)'];
        $retorno[] = $proforma_mes_anterior;        
       
        $proforma_mes_actual = $this->SQLconstructor(
            'COUNT(*) '.$select_ext, 
            'gestion_financiamiento gf', 
            'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion '.$join_ext.$INERmodelos, 
            $id_persona.$consultaBDC.$modelos.$versiones.$consulta_gp." AND (DATE(gf.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."') ", 
            $group_ext
        );                
        $proforma_mes_actual = $proforma_mes_actual[0]['COUNT(*)'];
        $retorno[] = $proforma_mes_actual;


            $proformackd1 = $this->SQLconstructor(
                'COUNT(*) '.$select_ext, 
                'gestion_financiamiento gf', 
                'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo '.$join_ext, 
                $id_persona.$consultaBDC.$modelos.$versiones.$consulta_gp." AND (DATE(gf.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."') AND ((gv.modelo IN (".$CKDsRender.")) OR gi.modelo IN (".$CKDsRender."))", 
                $group_ext
            );
            $proformackd1 = $proformackd1[0]['COUNT(*)'];
            $retorno[] = $proformackd1;

            $proformacbu1 = $this->SQLconstructor(
                'COUNT(*) '.$select_ext, 
                'gestion_financiamiento gf', 
                'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo '.$join_ext, 
                $id_persona.$consultaBDC.$modelos.$versiones.$consulta_gp." AND (DATE(gf.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."') ", 
                $group_ext
            );
            $proformacbu1 = ($proformacbu1[0]['COUNT(*)'] - $proformackd1);
            $retorno[] = $proformacbu1;


            $proformackd2 = $this->SQLconstructor(
                'COUNT(*) '.$select_ext, 
                'gestion_financiamiento gf', 
                'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo '.$join_ext, 
                $id_persona.$consultaBDC.$modelos.$versiones.$consulta_gp." AND (DATE(gf.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."') AND ((gv.modelo IN (".$CKDsRender.")) OR gi.modelo IN (".$CKDsRender."))", 
                $group_ext
            );
            $proformackd2 = $proformackd2[0]['COUNT(*)'];
            $retorno[] = $proformackd2;

            $proformacbu2 = $this->SQLconstructor(
                'COUNT(*) '.$select_ext, 
                'gestion_financiamiento gf', 
                'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo '.$join_ext, 
                $id_persona.$consultaBDC.$modelos.$versiones.$consulta_gp." AND (DATE(gf.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."')", 
                $group_ext
            );
            $proformacbu2 = ($proformacbu2[0]['COUNT(*)'] - $proformackd2);
            $retorno[] = $proformacbu2;
        
        // BUSQUEDA POR TEST DRIVE
        $td_mes_anterior = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_test_drive  gt', 
            'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion '.$join_ext.$INERmodelos_td, 
            $id_persona.$consultaBDC.$modelos.$versiones.$consulta_gp." AND gt.test_drive = 1 AND (DATE(gt.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."')", 
            $group_ext
        );
        $td_mes_anterior = $td_mes_anterior[0]['COUNT(*)'];
        $retorno[] = $td_mes_anterior;

        $td_mes_actual = $this->SQLconstructor(
            'COUNT(*) ', 
            'gestion_test_drive  gt', 
            'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion '.$join_ext.$INERmodelos_td, 
            $id_persona.$consultaBDC.$modelos.$versiones.$consulta_gp." AND gt.test_drive = 1 AND (DATE(gt.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."')", 
            $group_ext
        );
        $td_mes_actual = $td_mes_actual[0]['COUNT(*)'];
        $retorno[] = $td_mes_actual;

    
            $tdckd1 = $this->SQLconstructor(
                'COUNT(*) ', 
                'gestion_test_drive  gt', 
                'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo '.$join_ext, 
                $id_persona.$consultaBDC.$modelos.$versiones.$consulta_gp." AND gt.test_drive = 1 AND (DATE(gt.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."') AND ((gv.modelo IN (".$CKDsRender.")) OR gi.modelo IN (".$CKDsRender."))", 
                $group_ext
            );
            $tdckd1 = $tdckd1[0]['COUNT(*)'];
            $retorno[] = $tdckd1;

            $tdcbu1 = $this->SQLconstructor(
                'COUNT(*) ', 
                'gestion_test_drive  gt', 
                'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo '.$join_ext, 
                $id_persona.$consultaBDC.$modelos.$versiones.$consulta_gp." AND gt.test_drive = 1 AND (DATE(gt.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."')", 
                $group_ext
            );
            $tdcbu1 = ($tdcbu1[0]['COUNT(*)'] - $tdckd1);
            $retorno[] = $tdcbu1;

            $tdckd2 = $this->SQLconstructor(
                'COUNT(*) ', 
                'gestion_test_drive  gt', 
                'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo '.$join_ext, 
                $id_persona.$consultaBDC.$modelos.$versiones.$consulta_gp." AND gt.test_drive = 1 AND (DATE(gt.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."') AND ((gv.modelo IN (".$CKDsRender.")) OR gi.modelo IN (".$CKDsRender."))", 
                $group_ext
            );
            $tdckd2 = $tdckd2[0]['COUNT(*)'];
            $retorno[] = $tdckd2;

            $tdcbu2 = $this->SQLconstructor(
                'COUNT(*) ', 
                'gestion_test_drive  gt', 
                'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo '.$join_ext, 
                $id_persona.$consultaBDC.$modelos.$versiones.$consulta_gp." AND gt.test_drive = 1 AND (DATE(gt.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."')", 
                $group_ext
            );
            $tdcbu2 = ($tdcbu2[0]['COUNT(*)'] - $tdckd2);
            $retorno[] = $tdcbu2;
        

        // BUSQUEDA POR VENTAS 
        $vh_mes_anterior = $this->SQLconstructor(
            'COUNT(DISTINCT gf.id_vehiculo) ', 
            'gestion_factura gf ', 
            'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id '.$join_ext.$INERmodelos, 
            $id_persona.$consultaBDC.$modelos.$versiones.$consulta_gp." AND gd.cierre = 1 AND gf.status = 'ACTIVO' AND (DATE(gf.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."')", 
            $group_ext
        );
        
        $vh_mes_anterior = $vh_mes_anterior[0]['COUNT(DISTINCT gf.id_vehiculo)'];
        $retorno[] = $vh_mes_anterior;
        
        $vh_mes_actual = $this->SQLconstructor(
            'COUNT(DISTINCT gf.id_vehiculo) ', 
            'gestion_factura gf ', 
            'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id '.$join_ext.$INERmodelos, 
            $id_persona.$consultaBDC.$modelos.$versiones.$consulta_gp." AND gd.cierre = 1 AND gf.status = 'ACTIVO' AND (DATE(gf.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."')", 
            $group_ext
        );
        $vh_mes_actual = $vh_mes_actual[0]['COUNT(DISTINCT gf.id_vehiculo)'];
        $retorno[] = $vh_mes_actual;        

        
            $vhckd1 = $this->SQLconstructor(
                'COUNT(DISTINCT gf.id_vehiculo) ', 
                'gestion_factura gf ', 
                'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id_informacion  = gf.id_informacion  INNER JOIN  gestion_diaria gd ON gd.id_informacion = gi.id '.$join_ext, 
                $id_persona.$consultaBDC.$modelos.$versiones.$consulta_gp." AND gd.cierre = 1 AND gf.status = 'ACTIVO' AND (DATE(gf.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."') AND ((gv.modelo IN (".$CKDsRender.")) OR gi.modelo IN (".$CKDsRender."))", 
                $group_ext
            );
            $vhckd1 = $vhckd1[0]['COUNT(DISTINCT gf.id_vehiculo)'];
            $retorno[] = $vhckd1;

            $vhcbu1 = $this->SQLconstructor(
                'COUNT(DISTINCT gf.id_vehiculo) ', 
                'gestion_factura gf ', 
                'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id '.$join_ext.$INERmodelos, 
                $id_persona.$consultaBDC.$modelos.$versiones.$consulta_gp." AND gd.cierre = 1 AND gf.status = 'ACTIVO' AND (DATE(gf.fecha) BETWEEN '".$fecha_inicial_anterior."' AND '".$fecha_anterior."')", 
                $group_ext
            );
            $vhcbu1 = ($vhcbu1[0]['COUNT(DISTINCT gf.id_vehiculo)'] - $vhckd1);
            $retorno[] = $vhcbu1;

            $vhckd2 = $this->SQLconstructor(
                'COUNT(DISTINCT gf.id_vehiculo) ', 
                'gestion_factura gf ', 
                'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id_informacion  = gf.id_informacion INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id '.$join_ext, 
                $id_persona.$consultaBDC.$modelos.$versiones.$consulta_gp." AND gd.cierre = 1 AND gf.status = 'ACTIVO' AND (DATE(gf.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."') AND ((gv.modelo IN (".$CKDsRender.")) OR gi.modelo IN (".$CKDsRender."))", 
                $group_ext
            );
            $vhckd2 = $vhckd2[0]['COUNT(DISTINCT gf.id_vehiculo)'];
            $retorno[] = $vhckd2;

            $vhcbu2 = $this->SQLconstructor(
                'COUNT(DISTINCT gf.id_vehiculo) ', 
                'gestion_factura gf ', 
                'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id '.$join_ext.$INERmodelos, 
                $id_persona.$consultaBDC.$modelos.$versiones.$consulta_gp." AND gd.cierre = 1 AND (DATE(gf.fecha) BETWEEN '".$fecha_inicial_actual."' AND '".$fecha_actual."')", 
                $group_ext
            );
            $vhcbu2 = ($vhcbu2[0]['COUNT(DISTINCT gf.id_vehiculo)'] - $vhckd2);
            $retorno[] = $vhcbu2;
        
        return $retorno;
    }

}