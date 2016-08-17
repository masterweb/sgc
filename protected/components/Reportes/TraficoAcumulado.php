<?php

// protected/components/MyClass.php

class TraficoAcumulado {

    public static function ini_filtros($activo, $TAmodelo) {
        $con = Yii::app()->db;

        //Modelos
        $sqlModelos_nv = "SELECT distinct modelo from trafico_acumulado";
        $requestModelos_nv = $con->createCommand($sqlModelos_nv);
        $varView['modelo'] = $requestModelos_nv->queryAll();
        $varView['modelo'] = self::modelos($varView['modelo'], $TAmodelo);

        //provincia
        $sqlModelos_nv = "SELECT distinct provincia from trafico_acumulado";
        $requestModelos_nv = $con->createCommand($sqlModelos_nv);
        $varView['provincia'] = $requestModelos_nv->queryAll();
        $varView['provincia'] = self::selectConstructor($varView['provincia'], 'provincia', $activo);

        //grupo
        $sqlModelos_nv = "SELECT distinct grupo from trafico_acumulado";
        $requestModelos_nv = $con->createCommand($sqlModelos_nv);
        $varView['grupo'] = $requestModelos_nv->queryAll();
        $varView['grupo'] = self::selectConstructor($varView['grupo'], 'grupo', $activo);

        return $varView;
    }

    public static function ConcesionariosTA($where, $fecha1, $fecha2, $TAresp_activo, $model_info) {
        //concesionario
        $fecha1 = explode(" - ", $fecha1);
        $fecha2 = explode(" - ", $fecha2);
        $fecha1[0] = str_replace('-', '/', $fecha1[0]);
        $fecha1[1] = str_replace('-', '/', $fecha1[1]);
        $fecha2[0] = str_replace('-', '/', $fecha2[0]);
        $fecha2[1] = str_replace('-', '/', $fecha2[1]);
        //echo $fecha1[0].' / '.$fecha1[1].' / '.$fecha1[0].' / '.$fecha2[1];
        $con = Yii::app()->db;
        $sql = "SELECT distinct concesionario from trafico_acumulado where " . $where . " ((DATE(fecha) BETWEEN '" . $fecha1[0] . "' AND '" . $fecha1[1] . "') OR (DATE(fecha) BETWEEN '" . $fecha2[0] . "' AND '" . $fecha2[1] . "'))";
        $request = $con->createCommand($sql);
        $concesionarios = $request->queryAll();

        $respuestaJson = array();
        $concesionarios = self::optionsConstructor($concesionarios, 'concesionario', $TAresp_activo);
        array_push($respuestaJson, $concesionarios);

        //Modelos
        $sqlModelos_nv = "SELECT distinct modelo from trafico_acumulado where " . $model_info[0] . " = '" . $model_info[1] . "' AND ((DATE(fecha) BETWEEN '" . $fecha1[0] . "' AND '" . $fecha1[1] . "') OR (DATE(fecha) BETWEEN '" . $fecha2[0] . "' AND '" . $fecha2[1] . "'))";
        $requestModelos_nv = $con->createCommand($sqlModelos_nv);
        $modelos = $requestModelos_nv->queryAll();
        $modelos = self::modelos($modelos, []);
        array_push($respuestaJson, $modelos);

        echo json_encode($respuestaJson);
    }

    public static function modelos2TA($fecha1, $fecha2, $model_info) {
        //Modelos
        $fecha1 = explode(" - ", $fecha1);
        $fecha2 = explode(" - ", $fecha2);
        $fecha1[0] = str_replace('-', '/', $fecha1[0]);
        $fecha1[1] = str_replace('-', '/', $fecha1[1]);
        $fecha2[0] = str_replace('-', '/', $fecha2[0]);
        $fecha2[1] = str_replace('-', '/', $fecha2[1]);

        $con = Yii::app()->db;
        $sqlModelos_nv = "SELECT distinct modelo from trafico_acumulado where concesionario = '" . $model_info . "' AND ((DATE(fecha) BETWEEN '" . $fecha1[0] . "' AND '" . $fecha1[1] . "') OR (DATE(fecha) BETWEEN '" . $fecha2[0] . "' AND '" . $fecha2[1] . "'))";
        $requestModelos_nv = $con->createCommand($sqlModelos_nv);
        $modelos = $requestModelos_nv->queryAll();
        $modelos = self::modelos($modelos, []);

        echo $modelos;
    }

    public static function optionsConstructor($array, $name, $TAresp_activo) {
        $filtro_main = '<option value="">--Seleccione ' . $name . '--</option>';
        foreach ($array as $key => $value) {
            $filtro = ' <option value="' . $value[$name] . '"';
            if ($TAresp_activo == $value[$name]) {
                $filtro .= 'selected';
            }
            $filtro .= '>' . $value[$name] . '</option>';
            $filtro_main .= $filtro;
        }
        return $filtro_main;
    }

    public static function selectConstructor($array, $name, $activo) {
        $filtro_main = '<div class="col-md-6" id="cont_TA' . $name . '">
                <label for="">' . ucwords($name) . '</label><select name="TA[' . $name . ']" id="TA' . $name . '" class="form-control">
                <option value="">--Seleccione ' . $name . '--</option>';
        foreach ($array as $key => $value) {
            $filtro = ' <option value="' . $value[$name] . '"';
            if ($value[$name] == $activo) {
                $filtro .= 'selected';
            }
            $filtro .= '>' . $value[$name] . '</option>';
            $filtro_main .= $filtro;
        }
        $filtro_main .= '</select></div>';

        return $filtro_main;
    }

    public static function modelos($modelos, $TAmodelo) {
        $checked = '';
        $activos = array();

        $filtro_main = '<div class="filtros_modelos_ta"><h3>Modelos</h3><div class="row"><input class="checkboxmain" type="checkbox" value="activo" name="todos" id="todos_ta"> <label><b>Todos</b></label></div><div class="modelos_TA">';

        foreach ($modelos as $key => $value) {
            if ($TAmodelo) {
                if (in_array($value['modelo'], $TAmodelo)) {
                    $activos[] = $value['modelo'];
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
            }

            $filtro = '<div class="col-md-4">
                        <div class="checkbox contcheck">                            
                            <input class="checkboxmain" type="checkbox" value="' . $value['modelo'] . '" name="TA[modelo][]" id="cc' . $value['id_modelos'] . '" ' . $checked . '>
                        	<label>' . $value['modelo'] . '</label>
                        </div>
                    </div>';

            $filtro_main .= $filtro;
        }
        $filtro_main .= '</div></div>';

        return $filtro_main;
    }

    public static function buscar($consulta, $modelos, $fechas) {
        $where = '';

        if ($modelos != '') {
            $modelos_ta = '';
            foreach ($modelos as $key => $id_modelo) {
                $modelos_ta .= "'" . $id_modelo . "', ";
            }
            $modelos_ta = rtrim($modelos_ta, ", ");

            $modelos = "modelo IN (" . $modelos_ta . ") AND";
            $where = "";
        }

        if ($consulta['concesionarios']) {
            $where = "concesionario = '" . $consulta['concesionarios'] . "' AND ";
        } else {
            if ($consulta['provincia']) {
                $where = "provincia = '" . $consulta['provincia'] . "' AND ";
            } else if ($consulta['grupo']) {
                $where = "grupo = '" . $consulta['grupo'] . "' AND ";
            }
        }
        $con = Yii::app()->db;
        $sql = "SELECT tipo, modelo from trafico_acumulado where " . $where . " " . $modelos . " (DATE(fecha) BETWEEN '" . $fechas[0] . "' AND '" . $fechas[1] . "')";
        //die($sql);
        $request = $con->createCommand($sql);
        $busqueda['mant'] = $request->queryAll();

        $sql = "SELECT tipo, modelo from trafico_acumulado where " . $where . " " . $modelos . " (DATE(fecha) BETWEEN '" . $fechas[2] . "' AND '" . $fechas[3] . "')";
        $request = $con->createCommand($sql);
        $busqueda['mact'] = $request->queryAll();

        return $busqueda;
    }

}
