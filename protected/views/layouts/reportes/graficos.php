<div class="row">
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
           
                    echo '<span class="barratit trojo">TrÃ¡fico mes actual</span><div class="barra roja" style="width:'.$tmac.'%;">'.$trafico_mes_actual.'</div>'.
                     '<span class="barratit tazul">TrÃ¡fico mes anterior</span><div class="barra azul" style="width:'.$tman.'%;">'.$trafico_mes_anterior.'</div><hr/>'.
                     '<span class="barratit trojo">Proforma mes actual</span><div class="barra roja" style="width:'.$pmac.'%;">'.$proforma_mes_actual.'</div>'.
                     '<span class="barratit tazul">Proforma mes anterior</span><div class="barra azul" style="width:'.$pman.'%;">'.$proforma_mes_anterior.'</div><hr/>'.
                     '<span class="barratit trojo">Test Drive mes actual</span><div class="barra roja" style="width:'.$tdmac.'%;">'.$td_mes_actual.'</div>'.
                     '<span class="barratit tazul">Test Drive mes anterior</span><div class="barra azul" style="width:'.$tdman.'%;">'.$td_mes_anterior.'</div><hr/>'.
                     '<span class="barratit trojo">Ventas mes actual</span><div class="barra roja" style="width:'.$vmac.'%;">'.$vh_mes_actual.'</div>'.
                     '<span class="barratit tazul">Ventas mes anterior</span><div class="barra azul" style="width:'.$vman.'%;">'.$vh_mes_anterior.'</div><br/><br/>';
                }else{
                    echo '<h3>Estos valores no pueden ser graficados</h3>';
                }
            }
        ?>
    </div>
</div>