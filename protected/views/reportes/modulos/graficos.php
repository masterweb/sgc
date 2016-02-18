<div class="row embudo">
    <h2>Embudo</h2>
    <div class="col-md-12">
        <?php
            $valores = array(
                $varView['trafico_mes_actual'], 
                $varView['trafico_mes_anterior'], 
                $varView['proforma_mes_actual'], 
                $varView['proforma_mes_anterior'], 
                $varView['td_mes_actual'], 
                $varView['td_mes_anterior'], 
                $varView['vh_mes_actual'], 
                $varView['vh_mes_anterior']
            );
            //$valores = array(22, 8, 7, 7, 4, 5, 0, 5);
            $valormax = max($valores);
            $keymax = array_keys($valores, $valormax);

            porcentGraph(
                $valormax, 
                $varView['trafico_mes_actual'], 
                $varView['trafico_mes_anterior'], 
                $varView['proforma_mes_actual'], 
                $varView['proforma_mes_anterior'], 
                $varView['td_mes_actual'], 
                $varView['td_mes_anterior'], 
                $varView['vh_mes_actual'], 
                $varView['vh_mes_anterior']
            );
        
            function porcentGraph($maxvalue, $trafico_mes_actual, $trafico_mes_anterior, $proforma_mes_actual, $proforma_mes_anterior, $td_mes_actual, $td_mes_anterior, $vh_mes_actual, $vh_mes_anterior){
                if($maxvalue >= 1){     
                    $tmac = ($trafico_mes_actual * 100)/$maxvalue;
                    $tman = ($trafico_mes_anterior * 100)/$maxvalue;
                    $pmac = ($proforma_mes_actual * 100)/$maxvalue;
                    $pman = ($proforma_mes_anterior * 100)/$maxvalue;
                    $tdmac = ($td_mes_actual * 100)/$maxvalue;
                    $tdman = ($td_mes_anterior * 100)/$maxvalue;
                    $vmac = ($vh_mes_actual * 100)/$maxvalue;
                    $vman = ($vh_mes_anterior * 100)/$maxvalue;
           
                    echo '<div class="graficas"><div class="barra roja g1" style="width:'.$tmac.'%;"><span class="barratit trojo">Tráfico mes actual</span>'.$trafico_mes_actual.'</div>'.
                     '<div class="barra azul g2" style="width:'.$tman.'%;"><span class="barratit tazul">Tráfico mes anterior</span>'.$trafico_mes_anterior.'</div><br/>'.
                     '<div class="barra roja g3" style="width:'.$tdmac.'%;"><span class="barratit trojo">Test Drive mes actual</span>'.$td_mes_actual.'</div>'.
                     '<div class="barra azul g4" style="width:'.$tdman.'%;"><span class="barratit tazul">Test Drive mes anterior</span>'.$td_mes_anterior.'</div><br/>'.
                     '<div class="barra roja g5" style="width:'.$pmac.'%;"><span class="barratit trojo">Proforma mes actual</span>'.$proforma_mes_actual.'</div>'.
                     '<div class="barra azul g6" style="width:'.$pman.'%;"><span class="barratit tazul">Proforma mes anterior</span>'.$proforma_mes_anterior.'</div><br/>'.
                     '<div class="barra roja g7" style="width:'.$vmac.'%;"><span class="barratit trojo">Ventas mes actual</span>'.$vh_mes_actual.'</div>'.
                     '<div class="barra azul g8" style="width:'.$vman.'%;"><span class="barratit tazul">Ventas mes anterior</span>'.$vh_mes_anterior.'</div><br/><br/></div>';
                }else{
                    echo '<h3>Estos valores no pueden ser graficados</h3>';
                }
            }
        ?>
    </div>
</div>