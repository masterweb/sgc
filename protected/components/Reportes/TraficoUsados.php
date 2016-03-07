<?php
 
class TraficoUsados{
	//CARGA DE MODELOS USADOS
	function GetModelos($fecha2_1, $fecha2_2){
		 $con = Yii::app()->db;
        $sql_modelos_act = "SELECT distinct modelo FROM gestion_vehiculo WHERE DATE(fecha) BETWEEN '".$fecha1_1."' AND '".$fecha1_2."' OR DATE(fecha) BETWEEN '".$fecha2_1."' AND '".$fecha2_2."'";
        

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
        //$sqlModelos_nv = "SELECT nombre_modelo, id_modelos from modelos WHERE active = 1 ".$modelos_ma;
        $sqlModelos_nv = "SELECT marca, id FROM tbl_marcas GROUP BY marca";

        $requestModelos_nv = $con->createCommand($sqlModelos_nv);
        $varView['modelos_car'] = $requestModelos_nv->queryAll();

        $activos = array();
        $filtro_modelos_main = '<div class="row"><input class="checkboxmain" type="checkbox" value="activo" name="todos_usados" id="todos_usados"> <label><b>Todos</b></label></div>';
        
        foreach ($varView['modelos_car'] as $key => $value) {
            $checked = '';
            if ($lista_datos) {
                if (in_array($value['id_modelos'], $lista_datos[0]['modelos'])) {
                    $activos[] = $value['id_modelos'];
                    $checked = 'checked';
                }
            }                                    
            $filtro_modelos = '<div class="col-md-4 modelo mod_usado">
                        <div class="checkbox contcheck">
                            <div class="checkmodelo col-md-1">
                                <span style="display:none;">'.$value['marca'].'</span>
                                <input class="checkboxmain" type="checkbox" value="'.$value['id'].'" name="modelo[]" id="cc'.$value['id'].'" '.$checked.'>
                            </div>
                            <div class="model_info col-md-10">
                                <label>                                
                                    '.$value['marca'].'
                                </label>
                                <div id="result" class="result">';
                                    $filtro_modelos .= self::getVersiones($value['marca']);
                $filtro_modelos .='</div>
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

	function getVersiones($marca, $versiones = null) {
        $con = Yii::app()->db;

        $sqlModelos_nv = "SELECT modelo, submodelo, id from tbl_marcas WHERE marca = '{$marca}'";
        $requestModelos_nv = $con->createCommand($sqlModelos_nv);
        $versiones_car = $requestModelos_nv->queryAll();

        $resp = '<select multiple class="versiones">';
        foreach ($versiones_car as $key => $value) {
            if ($versiones) {
                if (in_array($value['id_versiones'], $versiones)) {
                    $checked = 'checked';
                }
            }    
            $resp .= '<option class="multiple" value="' . $value['id'] . '" '.$checked.'>' . $value['modelo'] . ' / '.$value['submodelo'].'</option>';
        }
        $resp .= '</select>';

        return $resp;
    }
}