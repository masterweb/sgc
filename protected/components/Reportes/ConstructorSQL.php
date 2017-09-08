<?php


class ConstructorSQL {

    function SQLconstructor($selection, $table, $join, $where, $group = null) {
        $con = Yii::app()->db;
        $sql_cons = "SELECT {$selection} from {$table} {$join}
        WHERE {$where} {$group}";
      // echo '<pre>'.$sql_cons.'</pre>';

        $request_cons = $con->createCommand($sql_cons);
        return $request_cons->queryAll();
    }


    function buscar($cargo_id, $id_responsable, $select_ext, $join_ext, $id_persona, $group_ext, $fecha_inicial_anterior, $fecha_anterior, $fecha_inicial_actual, $fecha_actual, $concesionario = 0, $tipos = null, $carros, $INERmodelos, $INERmodelos_td, $INERProspeccion,$consultaBDC, $condicion_GP = null, $tipo = NULL) {

        
        if (!empty($carros['modelos']) || !empty($carros['versiones']) && $tipo!='super'  ) {
            $datos_solo_modelos = $this->renderBusqueda($cargo_id, $id_responsable, $select_ext, $join_ext, $id_persona, $group_ext, $fecha_inicial_anterior, $fecha_anterior, $fecha_inicial_actual, $fecha_actual, $concesionario, $tipos, $carros, $INERmodelos, $INERmodelos_td, $INERProspeccion, $consultaBDC, $condicion_GP, $tipo);
            if ($_GET['todos']) {
                $datos_enteros = $this->renderBusqueda($cargo_id, $id_responsable, $select_ext, $join_ext, $id_persona, $group_ext, $fecha_inicial_anterior, $fecha_anterior, $fecha_inicial_actual, $fecha_actual, $concesionario, $tipos, null, null, null, null,$consultaBDC, $condicion_GP, $tipo);
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
            } else {
                $varView = $datos_solo_modelos;
            }
        } 
        else if($tipo=='super'){
           // die($join_ext);
             $varView = $this->renderBusquedaSuper($cargo_id, $id_responsable, $select_ext, $join_ext, $id_persona, $group_ext, $fecha_inicial_anterior, $fecha_anterior, $fecha_inicial_actual, $fecha_actual, $concesionario, $tipos, $carros, $INERmodelos, $INERmodelos_td, $INERProspeccion,$consultaBDC, $condicion_GP, $tipo);
        }
        else {
            $varView = $this->renderBusqueda($cargo_id, $id_responsable, $select_ext, $join_ext, $id_persona, $group_ext, $fecha_inicial_anterior, $fecha_anterior, $fecha_inicial_actual, $fecha_actual, $concesionario, $tipos, $carros, $INERmodelos, $INERmodelos_td, $INERProspeccion,$consultaBDC, $condicion_GP, $tipo);
        }
        return $varView;
    }
    /*se agrega la funcion para la consulta del super embudo*/
     function getVentasTotal($join_ext,$id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial,$fecha_fin,$fuente_contacto,$whereExh,$whereTw, $group_ext,$bdc){/*ventas de showroom, exhibicion y web*/

         $ventas = $this->SQLconstructor(
                'COUNT(DISTINCT gf.id_vehiculo) ', 'gestion_factura gf ', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' . $join_ext .
                'INNER JOIN gestion_vehiculo gv ON gv.id  = gf.id_vehiculo', $id_persona/*"gi.responsable  AND gi.bdc = " .$bdc*/. $consultaBDC . $modelos . $versiones . $consulta_gp .
                " AND gd.cierre = 1 AND gf.status = 'ACTIVO' AND (DATE(gf.fecha) BETWEEN '" . $fecha_inicial . "' AND '" . $fecha_fin . "') AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
        );


        $ventas = $ventas[0]['COUNT(DISTINCT gf.id_vehiculo)'];

        return $ventas;

    }

    function getTestDriveTotal($join_ext,$innerGestionDiaria,$INERmodelos_td, $id_persona , $consultaBDC , $modelos , $versiones , $consulta_gp, $fecha_inicial,$fecha_fin,$fuente_contacto,$whereExh,$whereTw, $group_ext,$bdc){/*testdrive de showroom, exhibicion y web*/
         $testDrive = $this->SQLconstructor(
                'COUNT(*) ', 'gestion_test_drive  gt', 'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion ' . $join_ext . $innerGestionDiaria . $INERmodelos_td, /*"gi.responsable  AND gi.bdc = ".$bdc*/ $id_persona. $consultaBDC . $modelos . $versiones . $consulta_gp . " AND gt.test_drive = 1 AND gt.order = 1 AND (DATE(gt.fecha) BETWEEN '" .
                $fecha_inicial . "' AND '" . $fecha_fin . "')AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
        );
        $testDrive = $testDrive[0]['COUNT(*)'];
        return $testDrive;
    }
    function getResumenVentasTotal($join_ext,$id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial,$fecha_anterior,$fuente_contacto,$whereExh,$whereTw, $group_ext,$tipo_credito,$bdc ){

         $ventas = $this->SQLconstructor(
                'COUNT(DISTINCT gf.id_vehiculo) ', 'gestion_factura gf ', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' . $join_ext .
                'INNER JOIN gestion_vehiculo gv ON gv.id  = gf.id_vehiculo', $id_persona /*"gi.responsable  AND gi.bdc = ".$bdc*/. $consultaBDC . $modelos . $versiones . $consulta_gp .
                " AND gd.cierre = 1 AND gv.tipo_credito=".$tipo_credito." AND gf.status = 'ACTIVO' AND (DATE(gf.fecha) BETWEEN '" . $fecha_inicial . "' AND '" . $fecha_anterior . "') AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
            );


            $ventas = $ventas[0]['COUNT(DISTINCT gf.id_vehiculo)'];

            return $ventas;
    }

    function getTraficoNacionalCKD($select_ext,$join_ext,$innerExternas,$id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$date_fecha,$fecha_inicial,$fecha_final,$CKDsRender,$fuente_contacto,$whereExt,$whereTw, $group_ext,$bdc){


        if($bdc==1)
          $traficockd=$this->SQLconstructor(
                'DISTINCT gi.id, gv.version ' . $select_ext, 'gestion_informacion gi', $join_ext .
                ' LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id  INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' . $innerExternas, /*"gi.responsable  AND gi.bdc = ".$bdc*/$id_persona. $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(".$date_fecha.".fecha) BETWEEN '" . $fecha_inicial .
                "' AND '" . $fecha_final . "') AND ((gv.modelo IN (" . $CKDsRender . ")) OR gi.modelo IN (" . $CKDsRender . ")) AND ({$fuente_contacto}) AND gv.orden = 1 " . $whereExt.$whereTw, $group_ext
            );

      else 

         $traficockd=$this->SQLconstructor(
                'DISTINCT gi.id, gv.version ' . $select_ext, 'gestion_informacion gi', $join_ext .
                ' LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id  INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' /*. $innerExternas*/, /*"gi.responsable  AND gi.bdc = ".$bdc*/$id_persona. $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(".$date_fecha.".fecha) BETWEEN '" . $fecha_inicial .
                "' AND '" . $fecha_final . "') AND ((gv.modelo IN (" . $CKDsRender . ")) OR gi.modelo IN (" . $CKDsRender . ")) AND ({$fuente_contacto}) AND gv.orden = 1 " /*. $whereExt.$whereTw*/, $group_ext
            );



            $traficockd = count($traficockd);

            return $traficockd;

    }

    function getProformaCKD($select_ext,$join_ext,$innerGestionDiaria, $id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial,$fecha_fin,$CKDsRender,$fuente_contacto,$whereExh,$whereTw, $group_ext,$bdc){

             $proformackd =  $this->SQLconstructor(
                'COUNT(DISTINCT gf.id) ', 'gestion_financiamiento gf', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo ' .
                $join_ext . $innerGestionDiaria, /*"gi.responsable  AND gi.bdc = ".$bdc*/$id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(gf.fecha) BETWEEN '" . $fecha_inicial .
                "' AND '" . $fecha_fin . "') AND ((gv.modelo IN (" . $CKDsRender . ")) OR gi.modelo IN (" . $CKDsRender . ")) AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
            );
            $proformackd = $proformackd[0]['COUNT(DISTINCT gf.id)'];

            return $proformackd;

    }
    function getProformaCBU($select_ext,$join_ext ,$innerGestionDiaria, $id_persona,$consultaBDC ,$modelos,$versiones ,$consulta_gp,$fecha_inicial,$fecha_fin,$fuente_contacto,$whereExh,$whereTw, $group_ext,$bdc ){


        $proformacbu = $this->SQLconstructor(
                    'COUNT(DISTINCT gf.id) ' . $select_ext, 'gestion_financiamiento gf', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo ' .
                    $join_ext . $innerGestionDiaria, /*"gi.responsable  AND gi.bdc = ".$bdc*/$id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(gf.fecha) BETWEEN '" . $fecha_inicial .
                    "' AND '" . $fecha_fin . "') AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
            );
            $proformacbu = ($proformacbu[0]['COUNT(DISTINCT gf.id)']);

            return $proformacbu;


    }
    function getTestDriveCKD($join_ext,$innerGestionDiaria, $id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial,$fecha_fin,$CKDsRender,$fuente_contacto,$whereTw, $group_ext,$bdc){

        $tdckd = $this->SQLconstructor(
                'COUNT(*) ', 'gestion_test_drive  gt', 'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo ' . $join_ext . $innerGestionDiaria, /*"gi.responsable  AND gi.bdc = ".$bdc*/$id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND gt.test_drive = 1 AND gt.order = 1 AND (DATE(gt.fecha) BETWEEN '" . $fecha_inicial . "' AND '" . $fecha_fin . "') AND ((gv.modelo IN (" . $CKDsRender . ")) OR gi.modelo IN (" . $CKDsRender . ")) AND ({$fuente_contacto})".$whereTw, $group_ext
            );
            $tdckd = $tdckd[0]['COUNT(*)'];


            return $tdckd;


    }
    function getTestDriveCBU($join_ext,$innerGestionDiaria, $id_persona,$consultaBDC,$modelos ,$versiones,$consulta_gp,$fecha_inicial,$fecha_fin,$fuente_contacto,$whereExh,$whereTw, $group_ext,$bdc){

             $tdcbu = $this->SQLconstructor(
                    'COUNT(*) ', 'gestion_test_drive  gt', 'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo ' . $join_ext . $innerGestionDiaria, /*"gi.responsable  AND gi.bdc = ".$bdc*/$id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND gt.test_drive = 1 AND gt.order = 1 AND (DATE(gt.fecha) BETWEEN '" .
                    $fecha_inicial . "' AND '" . $fecha_fin . "') AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
            );
            $tdcbu = ($tdcbu[0]['COUNT(*)']);

            return $tdcbu;

    }
    function getVentasCKD( $join_ext, $id_persona ,$consultaBDC , $modelos , $versiones , $consulta_gp,$fecha_inicial,$fecha_final,$CKDsRender,$fuente_contacto,$whereExh,$whereTw, $group_ext,$bdc ){

             $vhckd = $this->SQLconstructor(
                'COUNT(DISTINCT gf.id_vehiculo) ', 'gestion_factura gf ', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id  = gf.id_vehiculo  INNER JOIN  gestion_diaria gd ON gd.id_informacion = gi.id ' .
                $join_ext, /*"gi.responsable  AND gi.bdc = ".$bdc*/$id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp .
                " AND gd.cierre = 1 AND gf.status = 'ACTIVO' AND (DATE(gf.fecha) BETWEEN '" . $fecha_inicial . "' AND '" . $fecha_final . "') AND ((gv.modelo IN (" . $CKDsRender . ")) OR gi.modelo IN (" . $CKDsRender . ")) AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
            );
            $vhckd = $vhckd[0]['COUNT(DISTINCT gf.id_vehiculo)'];
            return  $vhckd;

    }
    function getVentasCBU($join_ext,$INERmodelos, $id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial,$fecha_fin,$fuente_contacto,$whereExh,$whereTw, $group_ext,$bdc){

            $vhcbu = $this->SQLconstructor(
                    'COUNT(DISTINCT gf.id_vehiculo) ', 'gestion_factura gf ', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' .
                    $join_ext . $INERmodelos, /*"gi.responsable  AND gi.bdc = ".$bdc*/$id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND gd.cierre = 1 AND gf.status = 'ACTIVO' AND (DATE(gf.fecha) BETWEEN '" . $fecha_inicial . "' AND '" . $fecha_fin . "') AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
            );
            $vhcbu = ($vhcbu[0]['COUNT(DISTINCT gf.id_vehiculo)']);

            return $vhcbu;
    }
     function renderBusquedaSuper($cargo_id, $id_responsable, $select_ext, $join_ext, $id_persona, $group_ext, $fecha_inicial_anterior, $fecha_anterior, $fecha_inicial_actual, $fecha_actual, $concesionario = 0, $tipos = null, $carros, $INERmodelos, $INERmodelos_td, $INERProspeccion,$consultaBDC, $consulta_gp = null, $tipo) {


         $retorno = array();
         $fuente_prospeccion = " gd.fuente_contacto = 'prospeccion' OR gd.fuente_contacto_historial = 'prospeccion'";
         $fuente_contacto_historial = "";


        $CKDs = Yii::app()->db->createCommand()->select('id_modelos')->from('modelos')->where("ensamblaje = 'CKD' AND active = 1")->queryAll();
        $CKDsRender = '';
        foreach ($CKDs as $key => $value) {
            $CKDsRender .= $value['id_modelos'] . ', ';
        }
        $CKDsRender = rtrim($CKDsRender, ", ");

        $modelos = null;
        $versiones = null;
        if (!empty($carros['modelos'])) {
            $modelos = $carros['modelos'];
        }
        if (!empty($carros['versiones'])) {
            $versiones = $carros['versiones'];
        }

        if (empty($tipos)) {
            $tipos = array();
            array_push($tipos, 1, 2, 3, 4);
        }

       // die($id_persona);
       //   echo "<h4>BUSQUEDA POR PROSPECCION</h4>";


         $prospeccion_mes_anterior = $this->SQLconstructor('COUNT(DISTINCT(gi.id)) ' . $select_ext, 'gestion_informacion gi', $join_ext . $INERProspeccion . ' LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id ', $id_persona. $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(gi.fecha) BETWEEN '" .
                $fecha_inicial_anterior . "' AND '" . $fecha_anterior . "') AND (" . $fuente_prospeccion . $fuente_contacto_historial . " ) ".$whereTw, $group_ext);
        $prospeccion_mes_anterior = $prospeccion_mes_anterior[0]['COUNT(DISTINCT(gi.id))'];
        
     

        $prospeccion_mes_actual = $this->SQLconstructor('COUNT(DISTINCT(gi.id)) ' . $select_ext, 'gestion_informacion gi', $join_ext . $INERProspeccion . ' LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id ', $id_persona. $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(gi.fecha) BETWEEN '" .
                $fecha_inicial_actual . "' AND '" . $fecha_actual . "') AND (" . $fuente_prospeccion . $fuente_contacto_historial . " ) ".$whereTw, $group_ext);
        $prospeccion_mes_actual = $prospeccion_mes_actual[0]['COUNT(DISTINCT(gi.id))'];
        
          //   echo "PROSPECCION ".$prospeccion_mes_actual;


   //     echo "<h4>BUSQUEDA POR CITAS</h4>";


         $fuente_contacto_web = "gd.fuente_contacto = 'web' OR gd.fuente_contacto = 'web' OR gd.fuente_contacto = 'web_espectaculo'";

          $innerExternas = " INNER JOIN gestion_cita gc ON gc.id_informacion = gi.id ";



        $date_tw = 'ga';  
         $whereExt = " AND gc.order = 1 ";
         $whereCita = " AND gv.orden = 1 AND gc.order = 1 AND gd.desiste = 0 AND gi.bdc = 1 AND ga.observaciones = 'Cita' ";  
       // $whereObs = " AND ga.observaciones = 'Cita'";
           
            $trafico_mes_anterior = $this->SQLconstructor(
              'DISTINCT gi.id, gv.version ' . $select_ext, 'gestion_informacion gi', $join_ext . $INERmodelos .
              ' LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' . $innerExternas. " INNER JOIN gestion_agendamiento ga ON ga.id_informacion = gi.id ". $innerTw, /*$id_persona*/str_replace("gi.bdc = 0","gi.bdc = 1",$id_persona). $consultaBDC . $modelos . $versiones . $consulta_gp .
              " AND (DATE(".$date_tw.".fecha) BETWEEN '" . $fecha_inicial_anterior . "' AND '" . $fecha_anterior . " 23:59:59') AND ({$fuente_contacto_web}) " .$whereCita. $whereExt.$whereTw, $group_ext
            );


            $trafico_mes_anterior = count($trafico_mes_anterior);
            //$retorno['trafico_citas_mes_anterior'] = $trafico_mes_anterior;    

               
            $trafico_mes_actual = $this->SQLconstructor(
              'DISTINCT gi.id, gv.version ' . $select_ext, 'gestion_informacion gi', $join_ext . $INERmodelos .
              ' LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' . $innerExternas. " INNER JOIN gestion_agendamiento ga ON ga.id_informacion = gi.id ". $innerTw, /*$id_persona*/str_replace("gi.bdc = 0","gi.bdc = 1",$id_persona). $consultaBDC . $modelos . $versiones . $consulta_gp .
              " AND (DATE(".$date_tw.".fecha) BETWEEN '" . $fecha_inicial_actual . "' AND '" . $fecha_actual . " 23:59:59') AND ({$fuente_contacto_web}) " . $whereCita. $whereExt.$whereTw.$whereObs, $group_ext
            );
            $trafico_mes_actual = count($trafico_mes_actual);
            //$retorno['trafico_citas_mes_actual'] = $trafico_mes_actual;


            $retorno['prospeccion_mes_anterior'] = $prospeccion_mes_anterior + $trafico_mes_anterior;
            $retorno['prospeccion_mes_actual'] = $prospeccion_mes_actual+ $trafico_mes_actual; 

         //   echo "citas concreatas ".$trafico_mes_actual;


     //   echo "<h4>BUSQUEDA POR trafico ehibicion</h4>";

        $date_fecha = "gi";
        $fuente_contacto_exhibicion = "gd.fuente_contacto = 'exhibicion' OR gd.fuente_contacto = 'exhibicion quierounkia' OR gd.fuente_contacto = 'exhibicion quierounkiatd'";

             $trafico_mes_anterior_exhibicion = $this->SQLconstructor(
                'DISTINCT gi.id, gv.version ' . $select_ext, 'gestion_informacion gi', $join_ext . $INERmodelos .
                ' LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id '/* . $innerExternas*/, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp .
                " AND (DATE(".$date_fecha.".fecha) BETWEEN '" . $fecha_inicial_anterior . "' AND '" . $fecha_anterior . " 23:59:59') AND ({$fuente_contacto_exhibicion}) AND gv.orden = 1" /*. $whereExt.$whereTw*/, $group_ext
            );


            $trafico_mes_anterior_exhibicion = count($trafico_mes_anterior_exhibicion);
                   
            $trafico_mes_actual_exhibicion = $this->SQLconstructor(
                'DISTINCT gi.id, gv.version ' . $select_ext, 'gestion_informacion gi', $join_ext . $INERmodelos .
                ' LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' /*. $innerExternas*/, $id_persona. $consultaBDC . $modelos . $versiones . $consulta_gp .
                " AND (DATE(".$date_fecha.".fecha) BETWEEN '" . $fecha_inicial_actual . "' AND '" . $fecha_actual . " 23:59:59') AND ({$fuente_contacto_exhibicion}) AND gv.orden = 1 " /*. $whereExt.$whereTw*/, $group_ext
            );
            $trafico_mes_actual_exhibicion = count($trafico_mes_actual_exhibicion);

    // echo "<h4>BUSQUEDA POR tafico showroom</h4>";




        $fuente_contacto_showroom = "gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico'";

             $trafico_mes_anterior_showroom = $this->SQLconstructor(
                'DISTINCT gi.id, gv.version ' . $select_ext, 'gestion_informacion gi', $join_ext . $INERmodelos .
                ' LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id '/* . $innerExternas*/, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp .
                " AND (DATE(".$date_fecha.".fecha) BETWEEN '" . $fecha_inicial_anterior . "' AND '" . $fecha_anterior . " 23:59:59') AND ({$fuente_contacto_showroom}) AND gv.orden = 1"/* . $whereExt.$whereTw*/, $group_ext
            );


            $trafico_mes_anterior_showroom = count($trafico_mes_anterior_showroom);
                   
            $trafico_mes_actual_showroom = $this->SQLconstructor(
                'DISTINCT gi.id, gv.version ' . $select_ext, 'gestion_informacion gi', $join_ext . $INERmodelos .
                ' LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id '/* . $innerExternas*/, $id_persona. $consultaBDC . $modelos . $versiones . $consulta_gp .
                " AND (DATE(".$date_fecha.".fecha) BETWEEN '" . $fecha_inicial_actual . "' AND '" . $fecha_actual . " 23:59:59') AND ({$fuente_contacto_showroom}) AND gv.orden = 1 "/* . $whereExt.$whereTw*/, $group_ext
            );
            $trafico_mes_actual_showroom = count($trafico_mes_actual_showroom);

            
       //     echo "<h4>BUSQUEDA POR citas concretadas</h4>";

/*aqui muere*/
            $trafico_citas_concretadas_mes_anterior = $this->SQLconstructor(
                  'DISTINCT gi.id, gv.version ' . $select_ext, 'gestion_informacion gi', $join_ext . $INERmodelos .
                  ' LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id INNER JOIN gestion_presentaciontm gtm ON gtm.id_informacion = gi.id' , /*$id_persona*/str_replace("gi.bdc = 0","gi.bdc = 1",$id_persona) . $consultaBDC . $modelos . $versiones . $consulta_gp .
                  " AND (DATE(gtm.fecha) BETWEEN '" . $fecha_inicial_anterior . "' AND '" . $fecha_anterior . " 23:59:59') AND gv.orden = 1 and gi.reasignado_tm=1 AND gtm.presentacion = 1 AND gd.desiste = 0 AND gi.bdc = 1 "/* . $whereExt.$whereTw*/, $group_ext
                );
                $trafico_citas_concretadas_mes_anterior = count($trafico_citas_concretadas_mes_anterior);
             //  die('here2');
                       
                $trafico_citas_concretadas_mes_actual = $this->SQLconstructor(
                  'DISTINCT gi.id, gv.version ' . $select_ext, 'gestion_informacion gi', $join_ext . $INERmodelos .
                  ' LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id INNER JOIN gestion_presentaciontm gtm ON gtm.id_informacion = gi.id' , /*$id_persona*/str_replace("gi.bdc = 0","gi.bdc = 1",$id_persona) . $consultaBDC . $modelos . $versiones . $consulta_gp .
                  " AND (DATE(gtm.fecha) BETWEEN '" . $fecha_inicial_actual . "' AND '" . $fecha_actual . " 23:59:59') AND gv.orden = 1 and gi.reasignado_tm=1 AND gtm.presentacion = 1 AND gd.desiste = 0 AND gi.bdc = 1 "/*. $whereExt.$whereTw*/, $group_ext
                );
                $trafico_citas_concretadas_mes_actual = count($trafico_citas_concretadas_mes_actual);
                
             /*cálculo tráfico super embudo*/   
            $retorno['trafico_mes_actual']=$trafico_mes_actual_exhibicion+$trafico_mes_actual_showroom+$trafico_citas_concretadas_mes_actual;
            $retorno['trafico_mes_anterior']=$trafico_mes_anterior_exhibicion+$trafico_mes_anterior_showroom+ $trafico_citas_concretadas_mes_anterior;


         //   echo "<h4>BUSQUEDA POR proformas showroom</h4>";
            $innerGestionDiaria = " INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ";

            

            $proforma_mes_anterior_showroom = $this->SQLconstructor(
                    'COUNT(DISTINCT gf.id) ' . $select_ext, 'gestion_financiamiento gf', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion ' . $join_ext .
                    $innerGestionDiaria . 'INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo', $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(gf.fecha) BETWEEN '" .
                    $fecha_inicial_anterior . "' AND '" . $fecha_anterior . "') AND ({$fuente_contacto_showroom})" . $whereExh.$whereTw, $group_ext
            );
            $proforma_mes_anterior_showroom = $proforma_mes_anterior_showroom[0]['COUNT(DISTINCT gf.id)'];
            

            $proforma_mes_actual_showroom = $this->SQLconstructor(
                    'COUNT(DISTINCT gf.id) ' . $select_ext, 'gestion_financiamiento gf', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion ' . $join_ext .
                    $innerGestionDiaria . 'INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo', $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(gf.fecha) BETWEEN '" .
                    $fecha_inicial_actual . "' AND '" . $fecha_actual . "') AND ({$fuente_contacto_showroom}) " . $whereExh.$whereTw, $group_ext
            );
            $proforma_mes_actual_showroom = $proforma_mes_actual_showroom[0]['COUNT(DISTINCT gf.id)'];
           


        //    echo "<h4>BUSQUEDA POR proformas exhibicion</h4>";

            $proforma_mes_anterior_exhibicion = $this->SQLconstructor(
                    'COUNT(DISTINCT gf.id) ' . $select_ext, 'gestion_financiamiento gf', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion ' . $join_ext .
                    $innerGestionDiaria . 'INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo', $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(gf.fecha) BETWEEN '" .
                    $fecha_inicial_anterior . "' AND '" . $fecha_anterior . "') AND ({$fuente_contacto_exhibicion})" . $whereExh.$whereTw, $group_ext
            );
            $proforma_mes_anterior_exhibicion = $proforma_mes_anterior_exhibicion[0]['COUNT(DISTINCT gf.id)'];
            

            $proforma_mes_actual_exhibicion = $this->SQLconstructor(
                    'COUNT(DISTINCT gf.id) ' . $select_ext, 'gestion_financiamiento gf', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion ' . $join_ext .
                    $innerGestionDiaria . 'INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo', $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(gf.fecha) BETWEEN '" .
                    $fecha_inicial_actual . "' AND '" . $fecha_actual . "') AND ({$fuente_contacto_exhibicion}) " . $whereExh.$whereTw, $group_ext
            );
            $proforma_mes_actual_exhibicion = $proforma_mes_actual_exhibicion[0]['COUNT(DISTINCT gf.id)'];


            $retorno['proforma_mes_actual'] = $proforma_mes_actual_showroom + $proforma_mes_actual_exhibicion ;

            $retorno['proforma_mes_anterior'] = $proforma_mes_anterior_showroom + $proforma_mes_anterior_exhibicion;




        // echo "<h4>BUSQUEDA POR test drive</h4>";   



        $td_mes_anterior_showroom =  $this->getTestDriveTotal($join_ext,$innerGestionDiaria,$INERmodelos_td, $id_persona,$consultaBDC,$modelos ,$versiones , $consulta_gp,$fecha_inicial_anterior,$fecha_anterior,$fuente_contacto_showroom,$whereExh,$whereTw, $group_ext,0);

        $td_mes_anterior_exhibicion =  $this->getTestDriveTotal($join_ext,$innerGestionDiaria,$INERmodelos_td, $id_persona,$consultaBDC,$modelos ,$versiones , $consulta_gp,$fecha_inicial_anterior,$fecha_anterior,$fuente_contacto_exhibicion,$whereExh,$whereTw, $group_ext,0);

        $td_mes_anterior_web =  $this->getTestDriveTotal($join_ext,$innerGestionDiaria,$INERmodelos_td, /*$id_persona*/str_replace("gi.bdc = 0","gi.bdc = 1",$id_persona),$consultaBDC,$modelos ,$versiones , $consulta_gp,$fecha_inicial_anterior,$fecha_anterior,$fuente_contacto_web,$whereExh,$whereTw, $group_ext,1);


        $retorno['td_mes_anterior'] =  $td_mes_anterior_showroom+$td_mes_anterior_exhibicion+$td_mes_anterior_web;//$td_mes_anterior;


        $td_mes_actual_showroom =  $this->getTestDriveTotal($join_ext,$innerGestionDiaria,$INERmodelos_td, $id_persona,$consultaBDC,$modelos ,$versiones , $consulta_gp,$fecha_inicial_actual,$fecha_actual,$fuente_contacto_showroom,$whereExh,$whereTw, $group_ext,0);


        // echo "td showroom ".$td_mes_actual_showroom;  



        $td_mes_actual_exhibicion =  $this->getTestDriveTotal($join_ext,$innerGestionDiaria,$INERmodelos_td, $id_persona,$consultaBDC,$modelos ,$versiones , $consulta_gp,$fecha_inicial_actual,$fecha_actual,$fuente_contacto_exhibicion,$whereExh,$whereTw, $group_ext,0);

        
        //echo "td exhibicion ".$td_mes_actual_exhibicion;


        $td_mes_actual_web =  $this->getTestDriveTotal($join_ext,$innerGestionDiaria,$INERmodelos_td, /*$id_persona*/str_replace("gi.bdc = 0","gi.bdc = 1",$id_persona),$consultaBDC,$modelos ,$versiones , $consulta_gp,$fecha_inicial_actual,$fecha_actual,$fuente_contacto_web,$whereExh,$whereTw, $group_ext,1);


       // echo "td web ".$td_mes_actual_web;





        $retorno['td_mes_actual'] = $td_mes_actual_showroom +$td_mes_actual_exhibicion+$td_mes_actual_web;//$td_mes_actual;

       


       // echo "<h4>BUSQUEDA POR ventas</h4>"; 

        /*ventas mes anterior*/
       
        $vh_mes_anterior_showroom = $this->getVentasTotal($join_ext,$id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_anterior,$fecha_anterior,$fuente_contacto_showroom,$whereExh,$whereTw, $group_ext,0);
      // echo "ventas showroom: ".$vh_mes_anterior_showroom;

       $vh_mes_anterior_exhibicion = $this->getVentasTotal($join_ext,$id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_anterior,$fecha_anterior,$fuente_contacto_exhibicion,$whereExh,$whereTw, $group_ext,0);

      // echo "ventas exhibicion : ".$vh_mes_anterior_exhibicion;


       $vh_mes_anterior_web = $this->getVentasTotal($join_ext,/*$id_persona*/str_replace("gi.bdc = 0","gi.bdc = 1",$id_persona),$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_anterior,$fecha_anterior,$fuente_contacto_web,$whereExh,$whereTw, $group_ext,1);


       // echo "ventas web : ".$vh_mes_anterior_web;


        $retorno['vh_mes_anterior'] = $vh_mes_anterior_showroom+$vh_mes_anterior_exhibicion+$vh_mes_anterior_web;

       


        /*ventas mes actual*/


        $vh_mes_actual_showroom = $this->getVentasTotal($join_ext,$id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_actual,$fecha_actual,$fuente_contacto_showroom,$whereExh,$whereTw, $group_ext,0);
       

        //echo "ventas showroom: ".$vh_mes_actual_showroom;




        $vh_mes_actual_exhibicion = $this->getVentasTotal($join_ext,$id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_actual,$fecha_actual,$fuente_contacto_exhibicion,$whereExh,$whereTw, $group_ext,0);


       // echo "ventas exhibicion : ".$vh_mes_actual_exhibicion;


        $vh_mes_actual_web = $this->getVentasTotal($join_ext,/*$id_persona*/str_replace("gi.bdc = 0","gi.bdc = 1",$id_persona),$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_actual,$fecha_actual,$fuente_contacto_web,$whereExh,$whereTw, $group_ext,1);

        //echo "ventas web : ".$vh_mes_actual_web;




        $retorno['vh_mes_actual'] = $vh_mes_actual_showroom+$vh_mes_actual_exhibicion+$vh_mes_actual_web;




        /*resumen ventas contado y credito*/




         $vcont_mes_anterior_showroom = $this->getResumenVentasTotal($join_ext,$id_persona , $consultaBDC , $modelos , $versiones , $consulta_gp,$fecha_inicial_anterior,$fecha_anterior,$fuente_contacto_showroom,$whereExh,$whereTw, $group_ext,0,0);

         $vcont_mes_anterior_exhibicion = $this->getResumenVentasTotal($join_ext,$id_persona , $consultaBDC , $modelos , $versiones , $consulta_gp,$fecha_inicial_anterior,$fecha_anterior,$fuente_contacto_exhibicion,$whereExh,$whereTw, $group_ext,0,0);

         $vcont_mes_anterior_web = $this->getResumenVentasTotal($join_ext,/*$id_persona */str_replace("gi.bdc = 0","gi.bdc = 1",$id_persona), $consultaBDC , $modelos , $versiones , $consulta_gp,$fecha_inicial_anterior,$fecha_anterior,$fuente_contacto_web,$whereExh,$whereTw, $group_ext,0,1);



            $retorno['vcont_mes_anterior'] = $vcont_mes_anterior_showroom+$vcont_mes_anterior_exhibicion+$vcont_mes_anterior_web;



             $vcred_mes_anterior_showroom = $this->getResumenVentasTotal($join_ext,$id_persona , $consultaBDC , $modelos , $versiones , $consulta_gp,$fecha_inicial_anterior,$fecha_anterior,$fuente_contacto_showroom,$whereExh,$whereTw, $group_ext,1,0);

         $vcred_mes_anterior_exhibicion = $this->getResumenVentasTotal($join_ext,$id_persona , $consultaBDC , $modelos , $versiones , $consulta_gp,$fecha_inicial_anterior,$fecha_anterior,$fuente_contacto_exhibicion,$whereExh,$whereTw, $group_ext,1,0);

         $vcred_mes_anterior_web = $this->getResumenVentasTotal($join_ext,/*$id_persona*/str_replace("gi.bdc = 0","gi.bdc = 1",$id_persona) , $consultaBDC , $modelos , $versiones , $consulta_gp,$fecha_inicial_anterior,$fecha_anterior,$fuente_contacto_web,$whereExh,$whereTw, $group_ext,1,1);


            $retorno['vcred_mes_anterior'] = $vcred_mes_anterior_showroom+$vcred_mes_anterior_exhibicion+$vcred_mes_anterior_web;


           
             $vcontmes_actual_showroom = $this->getResumenVentasTotal($join_ext,$id_persona , $consultaBDC , $modelos , $versiones , $consulta_gp,$fecha_inicial_actual,$fecha_actual,$fuente_contacto_showroom,$whereExh,$whereTw, $group_ext,0,0);


          //   echo "ventas contado mes actual showroom: ".$vcontmes_actual_showroom;


         $vcont_mes_actual_exhibicion = $this->getResumenVentasTotal($join_ext,$id_persona , $consultaBDC , $modelos , $versiones , $consulta_gp,$fecha_inicial_actual,$fecha_actual,$fuente_contacto_exhibicion,$whereExh,$whereTw, $group_ext,0,0);



        // echo "ventas contado mes actual exhibicion: ".$vcont_mes_actual_exhibicion;



         $vcont_mes_actual_web = $this->getResumenVentasTotal($join_ext,/*$id_persona*/str_replace("gi.bdc = 0","gi.bdc = 1",$id_persona) , $consultaBDC , $modelos , $versiones , $consulta_gp,$fecha_inicial_actual,$fecha_actual,$fuente_contacto_web,$whereExh,$whereTw, $group_ext,0,1);



        // echo "ventas contado mes actual web: ".$vcont_mes_actual_web;



            $retorno['vcont_mes_actual'] = $vcontmes_actual_showroom+$vcont_mes_actual_exhibicion+$vcont_mes_actual_web;

            


             $vcredmes_actual_showroom = $this->getResumenVentasTotal($join_ext,$id_persona , $consultaBDC , $modelos , $versiones , $consulta_gp,$fecha_inicial_actual,$fecha_actual,$fuente_contacto_showroom,$whereExh,$whereTw, $group_ext,1,0);

            // echo "ventas credito mes actual showroom: ".$vcredmes_actual_showroom;




         $vcred_mes_actual_exhibicion = $this->getResumenVentasTotal($join_ext,$id_persona , $consultaBDC , $modelos , $versiones , $consulta_gp,$fecha_inicial_actual,$fecha_actual,$fuente_contacto_exhibicion,$whereExh,$whereTw, $group_ext,1,0);


         //echo "ventas credito mes actual ehibicion: ".$vcred_mes_actual_exhibicion;



         $vcred_mes_actual_web = $this->getResumenVentasTotal($join_ext,/*$id_persona*/str_replace("gi.bdc = 0","gi.bdc = 1",$id_persona) , $consultaBDC , $modelos , $versiones , $consulta_gp,$fecha_inicial_actual,$fecha_actual,$fuente_contacto_web,$whereExh,$whereTw, $group_ext,1,1);

        // echo "ventas credito mes actual web: ".$vcred_mes_actual_web;




            $retorno['vcred_mes_actual'] = $vcredmes_actual_showroom+$vcred_mes_actual_exhibicion+$vcred_mes_actual_web;


            /*DATOS ENSAMBLADO NACIONAL E INTERNACIONAL*/

            /*TRAFICO*/

           // echo "<h4>BUSQUEDA POR trafico CKD</h4>"; 


            $traficockd1_showroom = $this->getTraficoNacionalCKD($select_ext,$join_ext,$innerExternas, $id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$date_fecha,$fecha_inicial_anterior,$fecha_anterior,$CKDsRender,$fuente_contacto_showroom,$whereExt,$whereTw, $group_ext,0);

           // echo "ckd showroom : ".$traficockd1_showroom; 

            $traficockd1_exhibicion = $this->getTraficoNacionalCKD($select_ext,$join_ext,$innerExternas, $id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$date_fecha,$fecha_inicial_anterior,$fecha_anterior,$CKDsRender,$fuente_contacto_exhibicion,$whereExt,$whereTw, $group_ext,0);

            // echo "ckd exhibicion : ".$traficockd1_exhibicion; 


            $traficockd1_web = $this->getTraficoNacionalCKD($select_ext,$join_ext,$innerExternas, /*$id_persona*/str_replace("gi.bdc = 0","gi.bdc = 1",$id_persona),$consultaBDC,$modelos,$versiones,$consulta_gp,$date_fecha,$fecha_inicial_anterior,$fecha_anterior,$CKDsRender,$fuente_contacto_web,$whereExt,$whereTw, $group_ext,1);

            // echo "ckd web : ".$traficockd1_web; 

          /*  $this->SQLconstructor(
                'DISTINCT gi.id, gv.version ' . $select_ext, 'gestion_informacion gi', $join_ext .
                ' LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id  INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' . $innerExternas, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(".$date_fecha.".fecha) BETWEEN '" . $fecha_inicial_anterior .
                "' AND '" . $fecha_anterior . "') AND ((gv.modelo IN (" . $CKDsRender . ")) OR gi.modelo IN (" . $CKDsRender . ")) AND ({$fuente_contacto}) AND gv.orden = 1 " . $whereExt.$whereTw, $group_ext
            );
            $traficockd1 = count($traficockd1);*/


            $traficockd1 = $traficockd1_showroom + $traficockd1_exhibicion + $traficockd1_web;

            $retorno['traficockd1'] = $traficockd1;


            $traficocbu1 =  $retorno['trafico_mes_anterior'] - $traficockd1; 

            $retorno['traficocbu1'] = $traficocbu1;





             $traficockd2_showroom = $this->getTraficoNacionalCKD($select_ext,$join_ext,$innerExternas, $id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$date_fecha,$fecha_inicial_actual,$fecha_actual,$CKDsRender,$fuente_contacto_showroom,$whereExt,$whereTw, $group_ext,0);

         

            $traficockd2_exhibicion = $this->getTraficoNacionalCKD($select_ext,$join_ext,$innerExternas, $id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$date_fecha,$fecha_inicial_actual,$fecha_actual,$CKDsRender,$fuente_contacto_exhibicion,$whereExt,$whereTw, $group_ext,0);

        


            $traficockd2_web = $this->getTraficoNacionalCKD($select_ext,$join_ext,$innerExternas, /*$id_persona*/str_replace("gi.bdc = 0","gi.bdc = 1",$id_persona),$consultaBDC,$modelos,$versiones,$consulta_gp,$date_fecha,$fecha_inicial_actual,$fecha_actual,$CKDsRender,$fuente_contacto_web,$whereExt,$whereTw, $group_ext,1);

     


             $traficockd2 = $traficockd2_showroom+$traficockd2_exhibicion+$traficockd2_web;

             $retorno['traficockd2'] = $traficockd2;

             $traficocbu2 = $retorno['trafico_mes_actual'] - $traficockd2; 
             $retorno['traficocbu2'] = $traficocbu2;




             /*proforma ckb y cbu*/


              $proformackd1_showroom = $this->getProformaCKD($select_ext,$join_ext,$innerGestionDiaria, $id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_anterior, $fecha_anterior,$CKDsRender,$fuente_contacto_showroom,$whereExh,$whereTw, $group_ext,0);
              

              $proformackd1_exhibicion = $this->getProformaCKD($select_ext,$join_ext,$innerGestionDiaria, $id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_anterior, $fecha_anterior,$CKDsRender,$fuente_contacto_exhibicion,$whereExh,$whereTw, $group_ext,0);


             $proformackd1_web = $this->getProformaCKD($select_ext,$join_ext,$innerGestionDiaria, /*$id_persona*/str_replace("gi.bdc = 0","gi.bdc = 1",$id_persona),$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_anterior, $fecha_anterior,$CKDsRender,$fuente_contacto_web,$whereExh,$whereTw, $group_ext,1);

   

            $proformackd1 = $proformackd1_showroom+$proformackd1_exhibicion+$proformackd1_web ;



            $retorno['proformackd1'] = $proformackd1;




        //    $proformacbu1_showroom = $this->getProformaCBU($select_ext,$join_ext,$innerGestionDiaria, $id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_anterior,$fecha_anterior,$fuente_contacto_showroom, $whereExh,$whereTw, $group_ext,0);

          //  $proformacbu1_exhibicion = $this->getProformaCBU($select_ext,$join_ext,$innerGestionDiaria, $id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_anterior,$fecha_anterior,$fuente_contacto_exhibicion, $whereExh,$whereTw, $group_ext,0);

          //  $proformacbu1_web = $this->getProformaCBU($select_ext,$join_ext,$innerGestionDiaria, /*$id_persona*/str_replace("gi.bdc = 0","gi.bdc = 1",$id_persona),$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_anterior,$fecha_anterior,$fuente_contacto_web, $whereExh,$whereTw, $group_ext,1);


 


            $retorno['proformacbu1'] =  $retorno['proforma_mes_anterior'] - $proformackd1;


         

           
             

            //($proformacbu1_showroom+$proformacbu1_exhibicion+$proformacbu1_web)-$proformackd1;






          
            $proformackd2_showroom = $this->getProformaCKD($select_ext,$join_ext,$innerGestionDiaria, $id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_actual, $fecha_actual,$CKDsRender,$fuente_contacto_showroom,$whereExh,$whereTw, $group_ext,0);

          //  echo "proforma ckd showroom ".$proformackd2_showroom;


              $proformackd2_exhibicion = $this->getProformaCKD($select_ext,$join_ext,$innerGestionDiaria, $id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_actual, $fecha_actual,$CKDsRender,$fuente_contacto_exhibicion,$whereExh,$whereTw, $group_ext,0);


            //  echo "proforma ckd exhibicion ".$proformackd2_exhibicion;

             $proformackd2_web = $this->getProformaCKD($select_ext,$join_ext,$innerGestionDiaria, /*$id_persona*/str_replace("gi.bdc = 0","gi.bdc = 1",$id_persona),$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_actual, $fecha_actual,$CKDsRender,$fuente_contacto_web,$whereExh,$whereTw, $group_ext,1);


            // echo "proforma ckd web ".$proformackd2_web;

             $proformackd2 = $proformackd2_showroom + $proformackd2_exhibicion + $proformackd2_web;

            $retorno['proformackd2'] = $proformackd2;





            //$proformacbu2_showroom = $this->getProformaCBU($select_ext,$join_ext,$innerGestionDiaria, $id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_actual,$fecha_actual,$fuente_contacto_showroom, $whereExh,$whereTw, $group_ext,0);

           // $proformacbu2_exhibicion = $this->getProformaCBU($select_ext,$join_ext,$innerGestionDiaria, $id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_actual,$fecha_actual,$fuente_contacto_exhibicion, $whereExh,$whereTw, $group_ext,0);

          //  $proformacbu2_web = $this->getProformaCBU($select_ext,$join_ext,$innerGestionDiaria, /*$id_persona*/str_replace("gi.bdc = 0","gi.bdc = 1",$id_persona),$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_actual,$fecha_actual,$fuente_contacto_web, $whereExh,$whereTw, $group_ext,1);

   

            $retorno['proformacbu2'] = $retorno['proforma_mes_actual'] - $proformackd2;


            //($proformacbu2_showroom+$proformacbu2_exhibicion+$proformacbu2_web)-$proformackd2;


            /*test drive ckd y cbu*/



             $tdckd1_showroom = $this->getTestDriveCKD($join_ext,$innerGestionDiaria, $id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_anterior,$fecha_anterior,$CKDsRender,$fuente_contacto_showroom,$whereTw, $group_ext,0);

               
             $tdckd1_exhibicion = $this->getTestDriveCKD($join_ext,$innerGestionDiaria, $id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_anterior,$fecha_anterior,$CKDsRender,$fuente_contacto_exhibicion,$whereTw, $group_ext,0);
             

             $tdckd1_web = $this->getTestDriveCKD($join_ext,$innerGestionDiaria, /*$id_persona*/str_replace("gi.bdc = 0","gi.bdc = 1",$id_persona),$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_anterior,$fecha_anterior,$CKDsRender,$fuente_contacto_web,$whereTw, $group_ext,1);

            

           
            $retorno['tdckd1'] = $tdckd1_showroom+$tdckd1_exhibicion+$tdckd1_web;






            $tdcbu1_showroom = $this->getTestDriveCBU($join_ext,$innerGestionDiaria, $id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_anterior,$fecha_anterior,$fuente_contacto_showroom,$whereExh,$whereTw, $group_ext,0);

             $tdcbu1_exhibicion = $this->getTestDriveCBU($join_ext,$innerGestionDiaria, $id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_anterior,$fecha_anterior,$fuente_contacto_exhibicion,$whereExh,$whereTw, $group_ext,0);

              $tdcbu1_web = $this->getTestDriveCBU($join_ext,$innerGestionDiaria, /*$id_persona*/str_replace("gi.bdc = 0","gi.bdc = 1",$id_persona),$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_anterior,$fecha_anterior,$fuente_contacto_web,$whereExh,$whereTw, $group_ext,1);


           /* $this->SQLconstructor(
                    'COUNT(*) ', 'gestion_test_drive  gt', 'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo ' . $join_ext . $innerGestionDiaria, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND gt.test_drive = 1 AND gt.order = 1 AND (DATE(gt.fecha) BETWEEN '" .
                    $fecha_inicial_anterior . "' AND '" . $fecha_anterior . "') AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
            );*/
            


            $tdcbu1 = ($tdcbu1_showroom+$tdcbu1_exhibicion+$tdcbu1_web)- $retorno['tdckd1'] ;




            $retorno['tdcbu1'] = $tdcbu1;

            



          

            $tdckd2_showroom = $this->getTestDriveCKD($join_ext,$innerGestionDiaria, $id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_actual,$fecha_actual,$CKDsRender,$fuente_contacto_showroom,$whereTw, $group_ext,0);

           //   echo "test drive ckd showroom ".$tdckd2_showroom;  
             $tdckd2_exhibicion = $this->getTestDriveCKD($join_ext,$innerGestionDiaria, $id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_actual,$fecha_actual,$CKDsRender,$fuente_contacto_exhibicion,$whereTw, $group_ext,0);
           //  echo "test drive ckd exhibicion ".$tdckd2_exhibicion  ;

             $tdckd2_web = $this->getTestDriveCKD($join_ext,$innerGestionDiaria, /*$id_persona*/str_replace("gi.bdc = 0","gi.bdc = 1",$id_persona),$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_actual,$fecha_actual,$CKDsRender,$fuente_contacto_web,$whereTw, $group_ext,1);

           // echo "test drive ckd web ".$tdckd2_web ;



            $retorno['tdckd2'] = $tdckd2_showroom+$tdckd2_exhibicion+$tdckd2_web;





             $tdcbu2_showroom = $this->getTestDriveCBU($join_ext,$innerGestionDiaria, $id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_actual,$fecha_actual,$fuente_contacto_showroom,$whereExh,$whereTw, $group_ext,0);

             $tdcbu2_exhibicion = $this->getTestDriveCBU($join_ext,$innerGestionDiaria, $id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_actual,$fecha_actual,$fuente_contacto_exhibicion,$whereExh,$whereTw, $group_ext,0);

              $tdcbu2_web = $this->getTestDriveCBU($join_ext,$innerGestionDiaria, /*$id_persona*/str_replace("gi.bdc = 0","gi.bdc = 1",$id_persona),$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_actual,$fecha_actual,$fuente_contacto_web,$whereExh,$whereTw, $group_ext,1);


               $tdcbu2 = ($tdcbu2_showroom+$tdcbu2_exhibicion+$tdcbu2_web)- $retorno['tdckd2'] ;




            $retorno['tdcbu2'] = $tdcbu2;


            /*ventas ckd y cbu*/

          

            $vhckd1_showroom = $this->getVentasCKD($join_ext, $id_persona , $consultaBDC , $modelos , $versiones , $consulta_gp,$fecha_inicial_anterior,$fecha_anterior,$CKDsRender,$fuente_contacto_showroom,$whereExh,$whereTw, $group_ext,0);


            $vhckd1_exhibicion = $this->getVentasCKD($join_ext, $id_persona , $consultaBDC , $modelos , $versiones , $consulta_gp,$fecha_inicial_anterior,$fecha_anterior,$CKDsRender,$fuente_contacto_exhibicion,$whereExh,$whereTw, $group_ext,0);


            $vhckd1_web = $this->getVentasCKD($join_ext, /*$id_persona*/str_replace("gi.bdc = 0","gi.bdc = 1",$id_persona) , $consultaBDC , $modelos , $versiones , $consulta_gp,$fecha_inicial_anterior,$fecha_anterior,$CKDsRender,$fuente_contacto_web,$whereExh,$whereTw, $group_ext,1);


         


            $retorno['vhckd1'] = $vhckd1_showroom+$vhckd1_exhibicion+$vhckd1_web;







            $vhcbu1_showroom =  $this->getVentasCBU($join_ext,$INERmodelos, $id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_anterior,$fecha_anterior,$fuente_contacto_showroom,$whereExh,$whereTw, $group_ext,0);


             $vhcbu1_exhibicion =  $this->getVentasCBU($join_ext,$INERmodelos, $id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_anterior,$fecha_anterior,$fuente_contacto_exhibicion,$whereExh,$whereTw, $group_ext,0);


              $vhcbu1_web =  $this->getVentasCBU($join_ext,$INERmodelos, /*$id_persona*/str_replace("gi.bdc = 0","gi.bdc = 1",$id_persona),$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_anterior,$fecha_anterior,$fuente_contacto_web,$whereExh,$whereTw, $group_ext,1);



           

            $vhcbu1 = ($vhcbu1_showroom +$vhcbu1_exhibicion +$vhcbu1_web ) -  $retorno['vhckd1'] ;
           


            $retorno['vhcbu1'] = $vhcbu1;







            $vhckd2_showroom = $this->getVentasCKD($join_ext, $id_persona , $consultaBDC , $modelos , $versiones , $consulta_gp,$fecha_inicial_actual,$fecha_actual,$CKDsRender,$fuente_contacto_showroom,$whereExh,$whereTw, $group_ext,0);

           //  echo "ventas showroom ckd: ".$vhckd2_showroom;


            $vhckd2_exhibicion = $this->getVentasCKD($join_ext, $id_persona , $consultaBDC , $modelos , $versiones , $consulta_gp,$fecha_inicial_actual,$fecha_actual,$CKDsRender,$fuente_contacto_exhibicion,$whereExh,$whereTw, $group_ext,0);

            // echo "ventas showroom ckd: ".$vhckd2_exhibicion;


            $vhckd2_web = $this->getVentasCKD($join_ext, /*$id_persona*/str_replace("gi.bdc = 0","gi.bdc = 1",$id_persona) , $consultaBDC , $modelos , $versiones , $consulta_gp,$fecha_inicial_actual,$fecha_actual,$CKDsRender,$fuente_contacto_web,$whereExh,$whereTw, $group_ext,1);

            // echo "ventas showroom ckd: ".$vhckd2_web;



            $retorno['vhckd2'] = $vhckd2_showroom+$vhckd2_exhibicion+$vhckd2_web;





            $vhcbu2_showroom =  $this->getVentasCBU($join_ext,$INERmodelos, $id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_actual,$fecha_actual,$fuente_contacto_showroom,$whereExh,$whereTw, $group_ext,0);


             $vhcbu2_exhibicion =  $this->getVentasCBU($join_ext,$INERmodelos, $id_persona,$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_actual,$fecha_actual,$fuente_contacto_exhibicion,$whereExh,$whereTw, $group_ext,0);


              $vhcbu2_web =  $this->getVentasCBU($join_ext,$INERmodelos, /*$id_persona*/str_replace("gi.bdc = 0","gi.bdc = 1",$id_persona),$consultaBDC,$modelos,$versiones,$consulta_gp,$fecha_inicial_actual,$fecha_actual,$fuente_contacto_web,$whereExh,$whereTw, $group_ext,1);


              $vhcbu2 = ($vhcbu2_showroom+$vhcbu2_exhibicion+$vhcbu2_web) - $retorno['vhckd2'];
            $retorno['vhcbu2'] = $vhcbu2;





        return $retorno;

    }      


   
    /**/
    function renderBusqueda($cargo_id, $id_responsable, $select_ext, $join_ext, $id_persona, $group_ext, $fecha_inicial_anterior, $fecha_anterior, $fecha_inicial_actual, $fecha_actual, $concesionario = 0, $tipos = null, $carros, $INERmodelos, $INERmodelos_td, $INERProspeccion,$consultaBDC, $consulta_gp = null, $tipo) {
        //echo 'TIPO: '.$tipo;
        //die('id persona render: '.$id_persona);
        //die( $INERmodelos);
        //die('asdasdasd:'.$innerGestionDiaria .'1 <br>'. $INERmodelos.'2 <br>'. $id_persona .'3 <br>'. $consultaBDC .'4 <br>'. $modelos .'5 <br>'. $versiones .'6 <br>'. $consulta_gp);
    //    echo '<pre>';
    //    print_r($_GET);
    //    echo '</pre>';

        $cargo_adicional = (int) Yii::app()->user->getState('cargo_adicional');
        $area_id = (int) Yii::app()->user->getState('area_id');
        $fuente_contacto_historial = "";
        $fuente_contacto = "gd.fuente_contacto = 'showroom' OR gd.fuente_contacto = 'trafico'";
        $fuente_prospeccion = " gd.fuente_contacto = 'prospeccion' OR gd.fuente_contacto_historial = 'prospeccion'";
        $innerGestionDiaria = " INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ";
        $innerExternas = "";
        $innerTw = "";
        $date_fecha = "gi";
        $date_tw = "gi";
        $whereExh = "";
        $whereExt = "";
        $whereTw = "";
        $whereObs = "";
        $whereCita = "";


        if ($tipo == 'exhibicion') {
            $fuente_contacto = "gd.fuente_contacto = 'exhibicion' OR gd.fuente_contacto = 'exhibicion quierounkia' OR gd.fuente_contacto = 'exhibicion quierounkiatd'";
            $whereExh = " AND (gd.fuente_contacto = 'exhibicion' OR gd.fuente_contacto = 'exhibicion quierounkia' OR gd.fuente_contacto = 'exhibicion quierounkiatd') ";
        }
        if ($tipo == 'externas' || $tipo == 'tw') {
            $innerExternas = " INNER JOIN gestion_cita gc ON gc.id_informacion = gi.id ";
            

            if($tipo == 'externas'){
                $whereExt = " AND gc.order = 1 ";
                $whereCita = " AND gv.orden = 1 AND gc.order = 1 AND gd.desiste = 0 AND gi.bdc = 1 AND ga.observaciones = 'Cita'";
                $date_fecha = "gi";
                if((int) Yii::app()->user->getState('cargo_id') == 89) {
                    $whereExt = " AND (gc.order = 1 AND gc.tw = 1)";
                    $date_fecha = "gi";
                }   
            }
            if($tipo == 'tw'){
                $whereCita = " AND gv.orden = 1 AND gi.reasignado_tm = 1  AND gc.order = 1 AND gc.tw = 1 AND gd.desiste = 0 AND gi.bdc = 1 AND ga.observaciones = 'Cita'";
            }
            
            if($tipo == 'tw' || ($tipo == 'externas' && $cargo_adicional == 89)){
                //$con = new Controller;
                //$array_dealers = $con->getDealerGrupoConc(2);
                //$innerTw = ' INNER JOIN gestion_agendamiento ga ON ga.id_informacion = gi.id ';
                $date_tw = 'ga';
                $dealers = GrConcesionarios::model()->findAll(array("condition" => "id_grupo = 2"));
                $counter = 0;
                foreach ($dealers as $value) {
                        //echo 'asdasd'.$value['concesionario_id'];
                        $array_dealers[$counter] = $value['dealer_id'];
                        $counter++;
                }
                $dealerList = implode(', ', $array_dealers);               

                $tw = Usuarios::model()->findAll(array('condition' => "cargo_id = 89"));
                $counter = 0;
                foreach ($tw as $val) {
                        //echo 'asdasd'.$value['concesionario_id'];
                        $array_tw[$counter] = $val['id'];
                        $counter++;
                }
                $usuarioList = implode(', ', $array_tw);
                if(empty($_GET['GI']['responsable'])){
                    switch ($cargo_id) {
                        case 4: // GERENTE GENERAL
                        case 45: // SUBGERENCIA GENERAL
                        case 46: // SUPER ADMINISTRADOR
                        case 48: // GERENTE MARKETING
                        case 57: // INTELIGENCIA DE MERCADO MARKETING
                        case 58: // JEFE DE PRODUCTO MARKETING
                        case 60: // GERENTE VENTAS
                        case 61: // JEFE DE RED VENTAS
                            $whereTw = " AND gi.dealer_id IN ({$dealerList}) ";
                            break;
                        case 70:
                            $sq = "SELECT gc.* FROM grupoconcesionariousuario gc
                            INNER JOIN usuarios u ON u.id = gc.usuario_id 
                            WHERE cargo_id = 89 and gc.concesionario_id = {$value['dealer_id']}";
                            $conc1 = Yii::app()->db->createCommand($sq)->queryAll();
                            $counter = 0;
                            foreach ($conc1 as $val) {
                                //echo 'asdasd'.$value['concesionario_id'];
                                $array_tw[$counter] = $val['usuario_id'];
                                $counter++;
                            }
                            $usuarioList = implode(', ', $array_tw);
                            $whereTw .= " AND (gi.responsable IN({$usuarioList}) OR gi.responsable_origen IN({$usuarioList}) OR gi.responsable_origen_tm IN({$usuarioList})) AND gd.desiste = 0";
                            break;    
                        
                        default:
                            # code...
                            break;
                    }
                    
                    $whereObs = " AND ga.observaciones = 'Cita'";
                    if($area_id == 4 || $area_id == 12 || $area_id == 13 || $area_id == 14)
                        $whereTw .= " AND (gi.responsable IN({$usuarioList}) OR gi.responsable_origen IN({$usuarioList}) OR gi.responsable_origen_tm IN({$usuarioList})) AND gd.desiste = 0";
                }
            //    if(!empty($_GET['GI']['responsable'])){
            //        $whereTw .= " AND (gi.responsable IN({$usuarioList}) OR gi.responsable_origen IN({$usuarioList}) OR gi.responsable_origen_tm IN({$usuarioList})) AND gd.desiste = 0";
            //    }
                
            }

            $fuente_contacto = "gd.fuente_contacto = 'web' OR gd.fuente_contacto = 'web' OR gd.fuente_contacto = 'web_espectaculo'";

            $fuente_prospeccion = "";
            $fuente_contacto_historial = " gd.fuente_contacto = 'web' OR gd.fuente_contacto = 'web_espectaculo' OR gd.fuente_contacto_historial = 'web'";
          //  $date_fecha = "gc";
        }
       /* if($tipo == 'tw'){

        }*/

        $CKDs = Yii::app()->db->createCommand()->select('id_modelos')->from('modelos')->where("ensamblaje = 'CKD' AND active = 1")->queryAll();
        $CKDsRender = '';
        foreach ($CKDs as $key => $value) {
            $CKDsRender .= $value['id_modelos'] . ', ';
        }
        $CKDsRender = rtrim($CKDsRender, ", ");

        $modelos = null;
        $versiones = null;
        if (!empty($carros['modelos'])) {
            $modelos = $carros['modelos'];
        }
        if (!empty($carros['versiones'])) {
            $versiones = $carros['versiones'];
        }

        if (empty($tipos)) {
            $tipos = array();
            array_push($tipos, 1, 2, 3, 4);
        }

        $retorno = array();

       


       /* if($cargo_id == 61){
            
        }*/

        // BUSQUEDA POR TRAFICO - PROSPECCION========================================================================================================================================
     //  die($id_persona);

       // echo "<h4>BUSQUEDA POR PROSPECCION</h4>";

       if($cargo_id==89) $idPersona='(gi.responsable ='. $id_responsable.' OR gi.responsable_origen= '.$id_responsable.' OR gi.responsable_origen_tm = '.$id_responsable.') AND gd.desiste = 0 AND gi.bdc = 1';
       else $idPersona=$id_persona;

        $prospeccion_mes_anterior = $this->SQLconstructor('COUNT(DISTINCT(gi.id)) ' . $select_ext, 'gestion_informacion gi', $join_ext . $INERProspeccion . ' LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id ', $idPersona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(gi.fecha) BETWEEN '" .
                $fecha_inicial_anterior . "' AND '" . $fecha_anterior . "') AND (" . $fuente_prospeccion . $fuente_contacto_historial . " ) ".$whereTw, $group_ext);
        $prospeccion_mes_anterior = $prospeccion_mes_anterior[0]['COUNT(DISTINCT(gi.id))'];
        $retorno['prospeccion_mes_anterior'] = $prospeccion_mes_anterior;
     

        $prospeccion_mes_actual = $this->SQLconstructor('COUNT(DISTINCT(gi.id)) ' . $select_ext, 'gestion_informacion gi', $join_ext . $INERProspeccion . ' LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id ', $idPersona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(gi.fecha) BETWEEN '" .
                $fecha_inicial_actual . "' AND '" . $fecha_actual . "') AND (" . $fuente_prospeccion . $fuente_contacto_historial . " ) ".$whereTw, $group_ext);
        $prospeccion_mes_actual = $prospeccion_mes_actual[0]['COUNT(DISTINCT(gi.id))'];
        $retorno['prospeccion_mes_actual'] = $prospeccion_mes_actual;

       // echo "<h4>BUSQUEDA POR TRAFICO O CITAS WEB</h4>";

        //BUSQUEDA POR TRAFICO O CITAS WEB=====================================================================================================================================       
       
        $validateAsiauto=$this->validateAsiautoDealer($concesionario);
        //die('validateAsiauto: '.$validateAsiauto);
        //die($cargo_id);
        if(($cargo_id == 70 && $cargo_adicional==89 && ($tipo == 'externas' || $tipo == 'tw')) || ($cargo_id == 61 && $validateAsiauto==true && ($tipo == 'externas' || $tipo == 'tw')) || (($cargo_id == 89 || $cargo_adicional == 89 || $cargo_id == 86) && ($tipo == 'externas' || $tipo == 'tw')) || ($cargo_id == 70 && ($tipo == 'externas'))  ){/*reportes web para los jefes de agencia y AEKIA*/


            $date_tw = 'ga';    
           
            $trafico_mes_anterior = $this->SQLconstructor(
              'DISTINCT gi.id, gv.version ' . $select_ext, 'gestion_informacion gi', $join_ext . $INERmodelos .
              ' LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' . $innerExternas. " INNER JOIN gestion_agendamiento ga ON ga.id_informacion = gi.id ". $innerTw, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp .
              " AND (DATE(".$date_tw.".fecha) BETWEEN '" . $fecha_inicial_anterior . "' AND '" . $fecha_anterior . " 23:59:59') AND ({$fuente_contacto}) " .$whereCita. $whereExt.$whereTw.$whereObs, $group_ext
            );


            $trafico_mes_anterior = count($trafico_mes_anterior);
                   
            $trafico_mes_actual = $this->SQLconstructor(
              'DISTINCT gi.id, gv.version ' . $select_ext, 'gestion_informacion gi', $join_ext . $INERmodelos .
              ' LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' . $innerExternas. " INNER JOIN gestion_agendamiento ga ON ga.id_informacion = gi.id ". $innerTw, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp .
              " AND (DATE(".$date_tw.".fecha) BETWEEN '" . $fecha_inicial_actual . "' AND '" . $fecha_actual . " 23:59:59') AND ({$fuente_contacto}) " . $whereCita. $whereExt.$whereTw.$whereObs, $group_ext
            );
            $trafico_mes_actual = count($trafico_mes_actual);

            if($tipo == 'tw'){
                // CITAS CONCRETADAS================================================================================================================================================

             //  echo "<h4>BUSQUEDA POR citas concretadas</h4>";



                $trafico_citas_generadas_mes_anterior = $this->SQLconstructor(
                  'DISTINCT gi.id, gv.version ' . $select_ext, 'gestion_informacion gi', $join_ext . $INERmodelos .
                  ' LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id INNER JOIN gestion_presentaciontm gtm ON gtm.id_informacion = gi.id' , str_replace("AND gd.desiste = 0","",$id_persona) . $consultaBDC . $modelos . $versiones . $consulta_gp .
                  " AND (DATE(gtm.fecha) BETWEEN '" . $fecha_inicial_anterior . "' AND '" . $fecha_anterior . " 23:59:59') AND ({$fuente_contacto}) AND gv.orden = 1 and gi.reasignado_tm=1 AND gtm.presentacion = 1 AND gi.bdc = 1 " . $whereExt.$whereTw, $group_ext
                );
                $trafico_citas_generadas_mes_anterior = count($trafico_citas_generadas_mes_anterior);
                $retorno['trafico_citas_concretadas_mes_anterior'] = $trafico_citas_generadas_mes_anterior;
                       
                $trafico_citas_generadas_mes_actual = $this->SQLconstructor(
                  'DISTINCT gi.id, gv.version ' . $select_ext, 'gestion_informacion gi', $join_ext . $INERmodelos .
                  ' LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id INNER JOIN gestion_presentaciontm gtm ON gtm.id_informacion = gi.id' , str_replace("AND gd.desiste = 0","",$id_persona) . $consultaBDC . $modelos . $versiones . $consulta_gp .
                  " AND (DATE(gtm.fecha) BETWEEN '" . $fecha_inicial_actual . "' AND '" . $fecha_actual . " 23:59:59') AND ({$fuente_contacto}) AND gv.orden = 1 and gi.reasignado_tm=1 AND gtm.presentacion = 1 AND gi.bdc = 1 " . $whereExt.$whereTw, $group_ext
                );
                $trafico_citas_generadas_mes_actual = count($trafico_citas_generadas_mes_actual);
                $retorno['trafico_citas_concretadas_mes_actual'] = $trafico_citas_generadas_mes_actual;
            }
                    
        }else{
            $trafico_mes_anterior = $this->SQLconstructor(
                'DISTINCT gi.id, gv.version ' . $select_ext, 'gestion_informacion gi', $join_ext . $INERmodelos .
                ' LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' . $innerExternas, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp .
                " AND (DATE(".$date_fecha.".fecha) BETWEEN '" . $fecha_inicial_anterior . "' AND '" . $fecha_anterior . " 23:59:59') AND ({$fuente_contacto}) AND gv.orden = 1" . $whereExt.$whereTw, $group_ext
            );


            $trafico_mes_anterior = count($trafico_mes_anterior);
                   
            $trafico_mes_actual = $this->SQLconstructor(
                'DISTINCT gi.id, gv.version ' . $select_ext, 'gestion_informacion gi', $join_ext . $INERmodelos .
                ' LEFT JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' . $innerExternas, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp .
                " AND (DATE(".$date_fecha.".fecha) BETWEEN '" . $fecha_inicial_actual . "' AND '" . $fecha_actual . " 23:59:59') AND ({$fuente_contacto}) AND gv.orden = 1 " . $whereExt.$whereTw, $group_ext
            );
            $trafico_mes_actual = count($trafico_mes_actual);

         }
        // die('trafico_mes_actual: '.$trafico_mes_actual.', trafico_mes_anterior: '.$trafico_mes_anterior);

        if ($tipo != 'externas' && $tipo != 'tw') {
             $retorno['trafico_mes_anterior'] = $trafico_mes_anterior;
            $retorno['trafico_mes_actual'] = $trafico_mes_actual;
        }
        else{

            $retorno['trafico_citas_mes_anterior'] = $trafico_mes_anterior;
            $retorno['trafico_citas_mes_actual'] = $trafico_mes_actual;
        }
        
        //echo "<h4>traafico ckd</h4>";
        $traficockd1 = $this->SQLconstructor(
                'DISTINCT gi.id, gv.version ' . $select_ext, 'gestion_informacion gi', $join_ext .
                ' LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id  INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' . $innerExternas, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(".$date_fecha.".fecha) BETWEEN '" . $fecha_inicial_anterior .
                "' AND '" . $fecha_anterior . "') AND ((gv.modelo IN (" . $CKDsRender . ")) OR gi.modelo IN (" . $CKDsRender . ")) AND ({$fuente_contacto}) AND gv.orden = 1 " . $whereExt.$whereTw, $group_ext
        );
        $traficockd1 = count($traficockd1);
        //$traficockd1 = $traficockd1[0]['DISTINCT gi.id, gv.version'];
        $retorno['traficockd1'] = $traficockd1;
        $traficocbu1 = $trafico_mes_anterior - $traficockd1; // resto de modelos
        $retorno['traficocbu1'] = $traficocbu1;

        $traficockd2 = $this->SQLconstructor(
                'DISTINCT gi.id, gv.version ' . $select_ext, 'gestion_informacion gi', $join_ext .
                ' LEFT JOIN gestion_vehiculo gv ON gv.id_informacion = gi.id  INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' . $innerExternas, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(".$date_fecha.".fecha) BETWEEN '" . $fecha_inicial_actual .
                "' AND '" . $fecha_actual . "')  AND ((gv.modelo IN (" . $CKDsRender . ")) OR gi.modelo IN (" . $CKDsRender . ")) AND ({$fuente_contacto}) AND gv.orden = 1 " . $whereExt.$whereTw, $group_ext
        );
        $traficockd2 = count($traficockd2);
        //$traficockd2 = $traficockd2[0]['DISTINCT gi.id, gv.version'];
        $retorno['traficockd2'] = $traficockd2;

        $traficocbu2 = $trafico_mes_actual - $traficockd2; // resto de modelos
        $retorno['traficocbu2'] = $traficocbu2;

       // echo "<h4>BUSQUEDA POR PROFORMA</h4>";
        // BUSQUEDA POR PROFORMA==================================================================================================================================================== 

    
        if(($cargo_id==70 && $cargo_adicional==89 || $cargo_id==61) && ($tipo=='tw')){//consulta proforma para para jefe de agencia y aekia
            
            $tw = Usuarios::model()->findAll(array('condition' => "cargo_id = 89"));
            $counter = 0;
            foreach ($tw as $val) {
                //echo 'asdasd'.$value['concesionario_id'];
                $array_tw[$counter] = $val['id'];
                $counter++;
            }
            $usuarioList = implode(', ', $array_tw);

            $whereExh .= " AND (gi.responsable_origen_tm IN ({$usuarioList})) ";
        }
        if($tipo == 'tw'){
            //die('whereExh: '.$whereExh);
            $proforma_mes_anterior = $this->SQLconstructor(
                    'COUNT(DISTINCT gf.id) ' . $select_ext, 'gestion_financiamiento gf', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion ' . $join_ext .
                    $innerGestionDiaria . 'INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo', $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(gf.fecha) BETWEEN '" .
                    $fecha_inicial_anterior . "' AND '" . $fecha_anterior . "') AND ({$fuente_contacto})" . $whereTw, $group_ext
            );
            $proforma_mes_anterior = $proforma_mes_anterior[0]['COUNT(DISTINCT gf.id)'];
            $retorno['proforma_mes_anterior'] = $proforma_mes_anterior;

            $proforma_mes_actual = $this->SQLconstructor(
                    'COUNT(DISTINCT gf.id) ' . $select_ext, 'gestion_financiamiento gf', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion ' . $join_ext .
                    $innerGestionDiaria . 'INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo', $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(gf.fecha) BETWEEN '" .
                    $fecha_inicial_actual . "' AND '" . $fecha_actual . "') AND ({$fuente_contacto}) " . $whereTw, $group_ext
            );
            $proforma_mes_actual = $proforma_mes_actual[0]['COUNT(DISTINCT gf.id)'];
            $retorno['proforma_mes_actual'] = $proforma_mes_actual;


               /*$proformackd1 = $this->SQLconstructor('COUNT(DISTINCT(gi.id)) ' . $select_ext, 'gestion_informacion gi', $join_ext . $INERProspeccion . ' INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo  INNER JOIN gestion_financiamiento gf on gf.id_informacion=gi.id ', $idPersona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(gi.fecha) BETWEEN '" .
                        $fecha_inicial_actual . "' AND '" . $fecha_actual . "') AND (" . $fuente_prospeccion . $fuente_contacto_historial . " ) AND ((gv.modelo IN (" . $CKDsRender . ")) OR gi.modelo IN (" . $CKDsRender . "))".$whereTw, $group_ext);
                $proformackd1 = $proformackd1[0]['COUNT(DISTINCT(gi.id))'];
                $retorno['proformackd1'] = $proformackd1;*/





             //$whereExh .= " AND (gi.responsable_origen_tm IN ({$usuarioList}) OR gi.responsable_origen IN ({$usuarioList}) OR gi.responsable IN ({$usuarioList})) ";
       }
       else{
            $proforma_mes_anterior = $this->SQLconstructor(
                    'COUNT(DISTINCT gf.id) ' . $select_ext, 'gestion_financiamiento gf', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion ' . $join_ext .
                    $innerGestionDiaria . 'INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo', $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(gf.fecha) BETWEEN '" .
                    $fecha_inicial_anterior . "' AND '" . $fecha_anterior . "') AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
            );
            $proforma_mes_anterior = $proforma_mes_anterior[0]['COUNT(DISTINCT gf.id)'];
            $retorno['proforma_mes_anterior'] = $proforma_mes_anterior;

            $proforma_mes_actual = $this->SQLconstructor(
                    'COUNT(DISTINCT gf.id) ' . $select_ext, 'gestion_financiamiento gf', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion ' . $join_ext .
                    $innerGestionDiaria . 'INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo', $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(gf.fecha) BETWEEN '" .
                    $fecha_inicial_actual . "' AND '" . $fecha_actual . "') AND ({$fuente_contacto}) " . $whereExh.$whereTw, $group_ext
            );
            $proforma_mes_actual = $proforma_mes_actual[0]['COUNT(DISTINCT gf.id)'];
            $retorno['proforma_mes_actual'] = $proforma_mes_actual;


             $proformackd1 = $this->SQLconstructor(
                'COUNT(DISTINCT gf.id) ' . $select_ext, 'gestion_financiamiento gf', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo ' .
                $join_ext . $innerGestionDiaria, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(gf.fecha) BETWEEN '" . $fecha_inicial_anterior .
                "' AND '" . $fecha_anterior . "') AND ((gv.modelo IN (" . $CKDsRender . ")) OR gi.modelo IN (" . $CKDsRender . ")) AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
            );
            $proformackd1 = $proformackd1[0]['COUNT(DISTINCT gf.id)'];
            $retorno['proformackd1'] = $proformackd1;

     }
        


        $proformacbu1 = $this->SQLconstructor(
                'COUNT(DISTINCT gf.id) ' . $select_ext, 'gestion_financiamiento gf', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo ' .
                $join_ext . $innerGestionDiaria, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(gf.fecha) BETWEEN '" . $fecha_inicial_anterior .
                "' AND '" . $fecha_anterior . "') AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
        );
        $proformacbu1 = ($proformacbu1[0]['COUNT(DISTINCT gf.id)'] - $proformackd1);
        $retorno['proformacbu1'] = $proformacbu1;


        $proformackd2 = $this->SQLconstructor(
                'COUNT(DISTINCT gf.id) ' . $select_ext, 'gestion_financiamiento gf', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo ' .
                $join_ext . $innerGestionDiaria, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(gf.fecha) BETWEEN '" . $fecha_inicial_actual .
                "' AND '" . $fecha_actual . "') AND ((gv.modelo IN (" . $CKDsRender . ")) OR gi.modelo IN (" . $CKDsRender . ")) AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
        );
        $proformackd2 = $proformackd2[0]['COUNT(DISTINCT gf.id)'];
        $retorno['proformackd2'] = $proformackd2;

        $proformacbu2 = $this->SQLconstructor(
                'COUNT(DISTINCT gf.id) ' . $select_ext, 'gestion_financiamiento gf', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gf.id_vehiculo ' .
                $join_ext . $innerGestionDiaria, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(gf.fecha) BETWEEN '" . $fecha_inicial_actual . "' AND '" .
                $fecha_actual . "') AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
        );
        $proformacbu2 = ($proformacbu2[0]['COUNT(DISTINCT gf.id)'] - $proformackd2);
        $retorno['proformacbu2'] = $proformacbu2;

    //    echo "<h4>BUSQUEDA POR TEST DRIVE</h4>";
        // BUSQUEDA POR TEST DRIVE=============================================================================================================  
        $td_mes_anterior = $this->SQLconstructor(
                'COUNT(*) ', 'gestion_test_drive  gt', 'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion ' . $join_ext . $innerGestionDiaria . $INERmodelos_td, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND gt.test_drive = 1 AND gt.order = 1 AND (DATE(gt.fecha) BETWEEN '" .
                $fecha_inicial_anterior . "' AND '" . $fecha_anterior . "')AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
        );
        $td_mes_anterior = $td_mes_anterior[0]['COUNT(*)'];
        $retorno['td_mes_anterior'] = $td_mes_anterior;

        $td_mes_actual = $this->SQLconstructor(
                'COUNT(*) ', 'gestion_test_drive  gt', 'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion ' . $join_ext . $innerGestionDiaria . $INERmodelos_td, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND gt.test_drive = 1 AND gt.order = 1 AND (DATE(gt.fecha) BETWEEN '" .
                $fecha_inicial_actual . "' AND '" . $fecha_actual . "') AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
        );
        $td_mes_actual = $td_mes_actual[0]['COUNT(*)'];
        $retorno['td_mes_actual'] = $td_mes_actual;

        // echo "<h4> ckd test drive</h4>";
        $tdckd1 = $this->SQLconstructor(
                'COUNT(*) ', 'gestion_test_drive  gt', 'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo ' . $join_ext . $innerGestionDiaria, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND gt.test_drive = 1 AND gt.order = 1 AND (DATE(gt.fecha) BETWEEN '" . $fecha_inicial_anterior . "' AND '" . $fecha_anterior . "') AND ((gv.modelo IN (" . $CKDsRender . ")) OR gi.modelo IN (" . $CKDsRender . ")) AND ({$fuente_contacto})".$whereTw, $group_ext
        );
        $tdckd1 = $tdckd1[0]['COUNT(*)'];
        $retorno['tdckd1'] = $tdckd1;

        $tdcbu1 = $this->SQLconstructor(
                'COUNT(*) ', 'gestion_test_drive  gt', 'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo ' . $join_ext . $innerGestionDiaria, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND gt.test_drive = 1 AND gt.order = 1 AND (DATE(gt.fecha) BETWEEN '" .
                $fecha_inicial_anterior . "' AND '" . $fecha_anterior . "') AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
        );
        $tdcbu1 = ($tdcbu1[0]['COUNT(*)'] - $tdckd1);
        $retorno['tdcbu1'] = $tdcbu1;

        $tdckd2 = $this->SQLconstructor(
                'COUNT(*) ', 'gestion_test_drive  gt', 'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo ' . $join_ext . $innerGestionDiaria, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND gt.test_drive = 1 AND gt.order = 1 AND (DATE(gt.fecha) BETWEEN '" .
                $fecha_inicial_actual . "' AND '" . $fecha_actual . "') AND ((gv.modelo IN (" . $CKDsRender . ")) OR gi.modelo IN (" . $CKDsRender . ")) AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
        );
        $tdckd2 = $tdckd2[0]['COUNT(*)'];
        $retorno['tdckd2'] = $tdckd2;

        $tdcbu2 = $this->SQLconstructor(
                'COUNT(*) ', 'gestion_test_drive  gt', 'INNER JOIN gestion_informacion gi ON gi.id = gt.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id = gt.id_vehiculo ' . $join_ext . $innerGestionDiaria, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND gt.test_drive = 1 AND gt.order = 1 AND (DATE(gt.fecha) BETWEEN '" .
                $fecha_inicial_actual . "' AND '" . $fecha_actual . "') AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
        );
        $tdcbu2 = ($tdcbu2[0]['COUNT(*)'] - $tdckd2);
        $retorno['tdcbu2'] = $tdcbu2;

     //  echo "<h4>BUSQUEDA POR VENTAS</h4>";
        // BUSQUEDA POR VENTAS=============================================================================================================  
        $vh_mes_anterior = $this->SQLconstructor(
                'COUNT(DISTINCT gf.id_vehiculo) ', 'gestion_factura gf ', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' . $join_ext .
                'INNER JOIN gestion_vehiculo gv ON gv.id  = gf.id_vehiculo', $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp .
                " AND gd.cierre = 1 AND gf.status = 'ACTIVO' AND (DATE(gf.fecha) BETWEEN '" . $fecha_inicial_anterior . "' AND '" . $fecha_anterior . "') AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
        );


        $vh_mes_anterior = $vh_mes_anterior[0]['COUNT(DISTINCT gf.id_vehiculo)'];
        $retorno['vh_mes_anterior'] = $vh_mes_anterior;

        $vh_mes_actual = $this->SQLconstructor(
                'COUNT(DISTINCT gf.id_vehiculo) ', 'gestion_factura gf ', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' . $join_ext .
                'INNER JOIN gestion_vehiculo gv ON gv.id  = gf.id_vehiculo', $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp .
                " AND gd.cierre = 1 AND gf.status = 'ACTIVO' AND (DATE(gf.fecha) BETWEEN '" . $fecha_inicial_actual . "' AND '" . $fecha_actual . "') AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
        );
        $vh_mes_actual = $vh_mes_actual[0]['COUNT(DISTINCT gf.id_vehiculo)'];
        $retorno['vh_mes_actual'] = $vh_mes_actual;

  //  echo "<h4>BUSQUEDA POR VENTAS vhckd1</h4>";
        
        $vhckd1 = $this->SQLconstructor(
                'COUNT(DISTINCT gf.id_vehiculo) ', 'gestion_factura gf ', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id  = gf.id_vehiculo  INNER JOIN  gestion_diaria gd ON gd.id_informacion = gi.id ' .
                $join_ext, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp .
                " AND gd.cierre = 1 AND gf.status = 'ACTIVO' AND (DATE(gf.fecha) BETWEEN '" . $fecha_inicial_anterior . "' AND '" . $fecha_anterior . "') AND ((gv.modelo IN (" . $CKDsRender . ")) OR gi.modelo IN (" . $CKDsRender . ")) AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
        );
        $vhckd1 = $vhckd1[0]['COUNT(DISTINCT gf.id_vehiculo)'];
        $retorno['vhckd1'] = $vhckd1;

        $vhcbu1 = $this->SQLconstructor(
                'COUNT(DISTINCT gf.id_vehiculo) ', 'gestion_factura gf ', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' .
                $join_ext . $INERmodelos, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND gd.cierre = 1 AND gf.status = 'ACTIVO' AND (DATE(gf.fecha) BETWEEN '" . $fecha_inicial_anterior . "' AND '" . $fecha_anterior . "') AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
        );
        $vhcbu1 = ($vhcbu1[0]['COUNT(DISTINCT gf.id_vehiculo)'] - $vhckd1);
        $retorno['vhcbu1'] = $vhcbu1;

        $vhckd2 = $this->SQLconstructor(
                'COUNT(DISTINCT gf.id_vehiculo) ', 'gestion_factura gf ', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_vehiculo gv ON gv.id  = gf.id_vehiculo INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' .
                $join_ext, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND gd.cierre = 1 AND gf.status = 'ACTIVO' AND (DATE(gf.fecha) BETWEEN '" . $fecha_inicial_actual . "' AND '" . $fecha_actual . "') AND ((gv.modelo IN (" . $CKDsRender . ")) OR gi.modelo IN (" . $CKDsRender . ")) AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
        );
        $vhckd2 = $vhckd2[0]['COUNT(DISTINCT gf.id_vehiculo)'];
        $retorno['vhckd2'] = $vhckd2;

        $vhcbu2 = $this->SQLconstructor(
                'COUNT(DISTINCT gf.id_vehiculo) ', 'gestion_factura gf ', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' .
                $join_ext . $INERmodelos, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND gd.cierre = 1 AND (DATE(gf.fecha) BETWEEN '" . $fecha_inicial_actual . "' AND '" . $fecha_actual . "') AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
        );
        $vhcbu2 = ($vhcbu2[0]['COUNT(DISTINCT gf.id_vehiculo)'] - $vhckd2);
        $retorno['vhcbu2'] = $vhcbu2;
        $area = 0;
        $area_id = (int) Yii::app()->user->getState('area_id');
        if ($area_id == 4 || $area_id == 12 || $area_id == 13 || $area_id == 14) { // AEKIA USERS
            $area = 1;
        }

    //echo '==========================================================================================================';    

        if ($cargo_id == 85 || $cargo_id == 86 || $cargo_adicional == 85 || $cargo_adicional == 86 || $area == 1 || $cargo_id == 69) {
            // GENERACI0N DE CONSULTAS PARA AGENTES DE VENTAS EXTERNAS=========================================================================================
            // COTIZACIONES SOLICITADAS DEL CLIENTE DE PROSPECCION Y DE WEB
            $cotizaciones_enviadas_anterior = $this->SQLconstructor('COUNT(*)', 'gestion_informacion gi', 'INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id' .
                    $join_ext . $INERmodelos, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(gi.fecha) BETWEEN '" . $fecha_inicial_anterior . "' AND '" . $fecha_anterior . "') AND ({$fuente_contacto})" . $whereExh, $group_ext);

            $cotizaciones_enviadas_anterior = $cotizaciones_enviadas_anterior[0]['COUNT(*)'];

            $retorno['cotizaciones_enviadas_anterior'] = $cotizaciones_enviadas_anterior;

            $cotizaciones_enviadas_actual = $this->SQLconstructor('COUNT(*)', 'gestion_informacion gi', 'INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id' .
                    $join_ext . $INERmodelos, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(gi.fecha) BETWEEN '" . $fecha_inicial_actual . "' AND '" . $fecha_actual . "') AND ({$fuente_contacto})" . $whereExh, $group_ext);

            $cotizaciones_enviadas_actual = $cotizaciones_enviadas_actual[0]['COUNT(*)'];
            $retorno['cotizaciones_enviadas_actual'] = $cotizaciones_enviadas_actual;

            // RESPUESTAS AUTOMATICAS ENVIADAS AL CLIENTE
            $respuestas_enviadas_anterior = $this->SQLconstructor('COUNT(*)', 'gestion_emails_enviados ge', 'INNER JOIN gestion_informacion gi ON gi.id = ge.id_informacion INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' .
                    $join_ext . $INERmodelos, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(gi.fecha) BETWEEN '" . $fecha_inicial_anterior . "' AND '" . $fecha_anterior . "') AND ({$fuente_contacto})" . $whereExh, $group_ext);

            $respuestas_enviadas_anterior = $respuestas_enviadas_anterior[0]['COUNT(*)'];
            $retorno['respuestas_enviadas_anterior'] = $respuestas_enviadas_anterior;

            $respuestas_enviadas_actual = $this->SQLconstructor('COUNT(*)', 'gestion_emails_enviados ge', 'INNER JOIN gestion_informacion gi ON gi.id = ge.id_informacion INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' .
                    $join_ext . $INERmodelos, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(gi.fecha) BETWEEN '" . $fecha_inicial_actual . "' AND '" . $fecha_actual . "') AND ({$fuente_contacto})" . $whereExh, $group_ext);

            $respuestas_enviadas_actual = $respuestas_enviadas_actual[0]['COUNT(*)'];
            $retorno['respuestas_enviadas_actual'] = $respuestas_enviadas_actual;

            // PROFORMAS O COTIZACIONES ENVIADAS EL CLIENTE
            $proformas_enviadas_anterior = $this->SQLconstructor('COUNT(*)', 'gestion_proformas_enviadas gp', 'INNER JOIN gestion_informacion gi ON gi.id = gp.id_informacion INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' .
                    $join_ext . $INERmodelos, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(gp.fecha) BETWEEN '" . $fecha_inicial_anterior . "' AND '" . $fecha_anterior . "') AND ({$fuente_contacto})" . $whereExh, $group_ext);

            $proformas_enviadas_anterior = $proformas_enviadas_anterior[0]['COUNT(*)'];
            $retorno['proformas_enviadas_anterior'] = $proformas_enviadas_anterior;

            $proformas_enviadas_actual = $this->SQLconstructor('COUNT(*)', 'gestion_proformas_enviadas gp', 'INNER JOIN gestion_informacion gi ON gi.id = gp.id_informacion INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' .
                    $join_ext . $INERmodelos, $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp . " AND (DATE(gp.fecha) BETWEEN '" . $fecha_inicial_actual . "' AND '" . $fecha_actual . "') AND ({$fuente_contacto})" . $whereExh, $group_ext);

            $proformas_enviadas_actual = $proformas_enviadas_actual[0]['COUNT(*)'];
            $retorno['proformas_enviadas_actual'] = $proformas_enviadas_actual;
        }


        /* UPDATED FOR GETTING CREDIT OR COUNTED DETAILS*/


             $vcont_mes_anterior = $this->SQLconstructor(
                'COUNT(DISTINCT gf.id_vehiculo) ', 'gestion_factura gf ', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' . $join_ext .
                'INNER JOIN gestion_vehiculo gv ON gv.id  = gf.id_vehiculo', $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp .
                " AND gd.cierre = 1 AND gv.tipo_credito=0 AND gf.status = 'ACTIVO' AND (DATE(gf.fecha) BETWEEN '" . $fecha_inicial_anterior . "' AND '" . $fecha_anterior . "') AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
            );


            $vcont_mes_anterior = $vcont_mes_anterior[0]['COUNT(DISTINCT gf.id_vehiculo)'];
            $retorno['vcont_mes_anterior'] = $vcont_mes_anterior;



            $vcred_mes_anterior = $this->SQLconstructor(
                'COUNT(DISTINCT gf.id_vehiculo) ', 'gestion_factura gf ', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' . $join_ext .
                'INNER JOIN gestion_vehiculo gv ON gv.id  = gf.id_vehiculo', $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp .
                " AND gd.cierre = 1 AND gv.tipo_credito=1 AND gf.status = 'ACTIVO' AND (DATE(gf.fecha) BETWEEN '" . $fecha_inicial_anterior . "' AND '" . $fecha_anterior . "') AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
            );


            $vcred_mes_anterior = $vcred_mes_anterior[0]['COUNT(DISTINCT gf.id_vehiculo)'];
            $retorno['vcred_mes_anterior'] = $vcred_mes_anterior;




            $vcont_mes_actual = $this->SQLconstructor(
                'COUNT(DISTINCT gf.id_vehiculo) ', 'gestion_factura gf ', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' . $join_ext .
                'INNER JOIN gestion_vehiculo gv ON gv.id  = gf.id_vehiculo', $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp .
                " AND gd.cierre = 1 AND gv.tipo_credito=0 AND gf.status = 'ACTIVO' AND (DATE(gf.fecha) BETWEEN '" . $fecha_inicial_actual . "' AND '" . $fecha_actual . "') AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
            );
            $vcont_mes_actual = $vcont_mes_actual[0]['COUNT(DISTINCT gf.id_vehiculo)'];
            $retorno['vcont_mes_actual'] = $vcont_mes_actual;

            $vcred_mes_actual = $this->SQLconstructor(
                'COUNT(DISTINCT gf.id_vehiculo) ', 'gestion_factura gf ', 'INNER JOIN gestion_informacion gi ON gi.id = gf.id_informacion INNER JOIN gestion_diaria gd ON gd.id_informacion = gi.id ' . $join_ext .
                'INNER JOIN gestion_vehiculo gv ON gv.id  = gf.id_vehiculo', $id_persona . $consultaBDC . $modelos . $versiones . $consulta_gp .
                " AND gd.cierre = 1 AND gv.tipo_credito=1 AND gf.status = 'ACTIVO' AND (DATE(gf.fecha) BETWEEN '" . $fecha_inicial_actual . "' AND '" . $fecha_actual . "') AND ({$fuente_contacto})" . $whereExh.$whereTw, $group_ext
            );
            $vcred_mes_actual = $vcred_mes_actual[0]['COUNT(DISTINCT gf.id_vehiculo)'];
            $retorno['vcred_mes_actual'] = $vcred_mes_actual;


            
             /*echo $vcont_mes_anterior.'-'.$vcred_mes_anterior;
            */
        /**/

        return $retorno;






    }

    public function validateAsiautoDealer($dealerId){/*valida si cliente es asiauto para envio de correo de notificacion tanto a cliente como a asesor reaisgnado*/

    //    echo 'dealer_id: '.$dealer_id;
        switch ($dealerId) {
            case 2:
            case 5:
            case 6:
            case 7:
            case 14:
            case 20:
            case 38:
            case 60:
            case 62:
            case 63:
            case 65:
            case 76:
            case 82:
            case 83:
            case 84:
                return true;
                break;
            
            default:

                $area_id = (int) Yii::app()->user->getState('area_id');
                if($area_id == 13 || $area_id == 14 || $area_id == 17)
                    return true;
                else
                    return false;
                break;
        }


    }

}
