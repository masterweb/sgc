<?php

class Modelos {

    public function getModleosActivos($fecha1_1, $fecha1_2, $fecha2_1, $fecha2_2, $lista_datos, $tipo_b, $tipo_busqueda_por = null, $concesion_active = null, $resp_active = null, $GestionInformacionProvincias = null, $GestionInformacionGrupo = null) {
        $condicion = '';
        $con = Yii::app()->db;

        if ($concesion_active != null) {
            $condicion .= ' dealer_id = ' . $concesion_active . ' AND ';
        } else {
            if ($GestionInformacionGrupo != null || $GestionInformacionProvincias != null) {
                if ($tipo_busqueda_por == 'grupos') {
                    $cond_conce = 'id_grupo';
                    $id_busqueda = $GestionInformacionGrupo;
                } else if ($tipo_busqueda_por == 'provincias') {
                    $cond_conce = 'provincia';
                    $id_busqueda = $GestionInformacionProvincias;
                }
                $grupos_sql = "SELECT * from gr_concesionarios WHERE " . $cond_conce . " = " . $id_busqueda;
                if($GestionInformacionProvincias == 1000 || $GestionInformacionGrupo == 1000){
                    $grupos_sql = "SELECT * from gr_concesionarios";
                }
                //die('grupos sql: '.$grupos_sql);
                $request_sql = $con->createCommand($grupos_sql);
                $request_sql = $request_sql->queryAll();

                foreach ($request_sql as $key3 => $value3) {
                    $conse_active .= $value3['dealer_id'] . ', ';
                }
                $conse_active = rtrim($conse_active, ", ");
                $condicion .= ' dealer_id IN (' . $conse_active . ') AND ';
            }
        }

        if ($resp_active != null) {
            $condicion .= ' responsable = ' . $resp_active . ' AND ';
        }

        //controlador de tipo de busqueda
        $bdcs = '';

        if ($tipo_b == 'bdc') {
            $condicion .= ' bdc = 1 AND ';
        } else if ($tipo_b == 'exonerados') {
            $condicion .= ' tipo_ex IS NOT NULL AND ';
        } else {
            $condicion .= '';
        }

        $extra_where = '';
        $extra_where = "SELECT id FROM gestion_informacion WHERE " . $condicion . " (DATE(fecha) BETWEEN '" . $fecha1_1 . "' AND '" . $fecha1_2 . "' OR DATE(fecha) BETWEEN '" . $fecha2_1 . "' AND '" . $fecha2_2 . "')";

        $request_BDC = $con->createCommand($extra_where);
        $request_BDC = $request_BDC->queryAll();
        $bdcs = '';
        foreach ($request_BDC as $key2 => $value2) {
            $bdcs .= $value2['id'] . ', ';
        }
        $bdcs = rtrim($bdcs, ", ");

        if ($bdcs != '') {
            $bdcs = ' id_informacion IN (' . $bdcs . ') AND ';
        } else {
            $bdcs = '';
        }

        //Modelos y versiones activos
        $sql_modelos_act = "SELECT distinct version, modelo FROM gestion_vehiculo WHERE " . $bdcs . " (DATE(fecha) BETWEEN '" . $fecha1_1 . "' AND '" . $fecha1_2 . "' OR DATE(fecha) BETWEEN '" . $fecha2_1 . "' AND '" . $fecha2_2 . "')";
        $request_ma = $con->createCommand($sql_modelos_act);
        $request_ma = $request_ma->queryAll();

        $modelos_ma = '';
        $versiones_activas = [];
        $modelos_no_rep = [];
        foreach ($request_ma as $id_modelo) {
            if (!in_array($id_modelo['modelo'], $modelos_no_rep)) {
                array_push($modelos_no_rep, $id_modelo['modelo']);
                $modelos_ma .= $id_modelo['modelo'] . ', ';
            }
            array_push($versiones_activas, $id_modelo['version']);
        }
        $modelos_ma = rtrim($modelos_ma, ", ");
        if ($modelos_ma != '') {
            $modelos_ma = "AND id_modelos IN (" . $modelos_ma . ")";
        }

        //GET MODELOS Y VERSIONES PARA BUSCADOR
        $sqlModelos_nv = "SELECT nombre_modelo, id_modelos from modelos WHERE active = 1 " . $modelos_ma;
        $requestModelos_nv = $con->createCommand($sqlModelos_nv);
        $varView['modelos_car'] = $requestModelos_nv->queryAll();

        $activos = array();
        $filtro_modelos_main = '<div class="row"><input class="checkboxmain" type="checkbox" value="activo" name="todos" id="todos"> <label><b>Todos</b></label></div>';
//        echo '<pre>';
//        print_r($lista_datos);
//        echo '</pre>';
        //die();
        foreach ($varView['modelos_car'] as $key => $value) {
            $checked = '';
            if ($lista_datos && !is_null($lista_datos[0]['modelos'])) {
                if (in_array($value['id_modelos'], $lista_datos[0]['modelos'])) {
                    $activos[] = $value['id_modelos'];
                    $checked = 'checked';
                }
            }
            $filtro_modelos = '<div class="col-md-4 modelo">
                        <div class="checkbox contcheck">
                            <div class="checkmodelo col-md-1">
                                <span style="display:none;">' . $value['nombre_modelo'] . '</span>
                                <input class="checkboxmain" type="checkbox" value="' . $value['id_modelos'] . '" name="modelo[]" id="cc' . $value['id_modelos'] . '" ' . $checked . '>
                            </div>
                            <div class="model_info col-md-10">
                                <label>                                
                                    ' . $value['nombre_modelo'] . '
                                </label>
                                <div id="result" class="result">' .
                    $this->getVersiones($value['id_modelos'], $lista_datos[1]['versiones'], $versiones_activas)
                    . '</div>
                            </div>
                        </div>
                    </div>';

            $filtro_modelos_main .= $filtro_modelos;
        }

        if ($modelos_ma == '') {
            $filtro_modelos_main = 'No exiten modelos activos para este rango de fechas - Seleccione un nuevo rango de fechas';
        }
        return $filtro_modelos_main;
    }

    function getVersiones($id, $versiones, $versiones_activas) {
        $con = Yii::app()->db;

        $sqlModelos_nv = "SELECT id_versiones, id_modelos, nombre_version from versiones WHERE id_modelos = '{$id}' AND `status` = 1";
        //$sqlModelos_nv = "SELECT id_versiones, id_modelos, nombre_version from versiones WHERE id_modelos = '{$id}' AND `status` IN (1,3)";
        $requestModelos_nv = $con->createCommand($sqlModelos_nv);
        $versiones_car = $requestModelos_nv->queryAll();

        $resp = '<ul class="versiones">';
        foreach ($versiones_car as $key => $value) {
            if (in_array($value['id_versiones'], $versiones_activas)) {
                if ($versiones) {
                    if (in_array($value['id_versiones'], $versiones)) {
                        $checked = 'checked';
                    }
                }
                $resp .= '<li><input class="subcheckbox" type="checkbox" name="version[]" value="' . $value['id_versiones'] . '" ' . $checked . '>' . $value['nombre_version'] . '</li>';
            }
        }
        $resp .= '</ul>';

        return $resp;
    }

}
