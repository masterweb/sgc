<?php
 
class GlobalFunctions{

	public function getConcecionario($grupo_id){
        $info = new GestionInformacion;
        $con = Yii::app()->db;
        $sql = "SELECT * FROM gr_concesionarios WHERE id_grupo = ".$grupo_id." ORDER BY nombre ASC";                
        $requestr1 = $con->createCommand($sql);                
        return $requestr1->queryAll();
    }

    public function tasa($var1, $var2){
        $resp = 0;
        if($var1 > 0){
            $resp = ($var2 / $var1) * 100;
            $resp = round($resp, 2);
            return $resp . ' %';
        }else{
            return '0 %';
        }
    }
    
	public function tasa_dif($var1, $var2){
        $dfpr = $var1 - $var2;
        if($dfpr >= 0){
            return $dfpr.' %';
        }else{
            return '<span class="dif">-'.abs($dfpr).' %</span>';
        }
    }
    
    public function DIFconstructor($var1, $var2, $tipo){
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
}