<?php //echo 'tipo :'.$tipo ?>
<div class="row embudo">
    <h2>Embudo</h2>
    <div class="col-md-12">
        <?php
            $data = '';
            $tit_mac = 'Tráfico mes actual';
            $tit_man = 'Tráfico mes anterior';
            if($tipo == 'externas'){
                $tit_mac = 'Citas mes actual';
                $tit_man = 'Citas mes anterior';
            }
            if($tipo == 'tw'){
                $tit_mac = 'Citas mes actual';
                $tit_man = 'Citas mes anterior';
            }
            $valores = array(
                $varView['trafico_mes_actual'], 
                $varView['trafico_mes_anterior'], 
                $varView['proforma_mes_actual'], 
                $varView['proforma_mes_anterior'], 
                $varView['td_mes_actual'], 
                $varView['td_mes_anterior'], 
                $varView['vh_mes_actual'], 
                $varView['vh_mes_anterior'],
                $varView['cotizaciones_enviadas_anterior'],
                $varView['cotizaciones_enviadas_actual'],
                $varView['trafico_citas_mes_anterior'],
                $varView['trafico_citas_mes_actual'],
                $varView['trafico_citas_concretadas_mes_anterior'],
                $varView['trafico_citas_concretadas_mes_actual']

            );
            //$valores = array(22, 8, 7, 7, 4, 5, 0, 5);
            if($tipo == 'tw'){
                $valormax = max($varView['prospeccion_mes_actual'],$varView['prospeccion_mes_anterior']);
            }else{
                $valormax = max($valores);
            }
            //die('valormax: '.$valormax);
            $keymax = array_keys($valores, $valormax);

            porcentGraph(
                $valormax, 
                $varView['prospeccion_mes_actual'],
                $varView['prospeccion_mes_anterior'],
                $varView['trafico_mes_actual'], 
                $varView['trafico_mes_anterior'], 
                $varView['proforma_mes_actual'], 
                $varView['proforma_mes_anterior'], 
                $varView['td_mes_actual'], 
                $varView['td_mes_anterior'], 
                $varView['vh_mes_actual'], 
                $varView['vh_mes_anterior'],
                $varView['cotizaciones_enviadas_anterior'],
                $varView['cotizaciones_enviadas_actual'],
                $varView['trafico_citas_mes_anterior'],
                $varView['trafico_citas_mes_actual'],
                $varView['trafico_citas_concretadas_mes_anterior'],
                $varView['trafico_citas_concretadas_mes_actual'],
                $tit_mac,$tit_man, $tipo
            );
        
            function porcentGraph($maxvalue, $prospeccion_mes_actual, $prospeccion_mes_anterior, $trafico_mes_actual, $trafico_mes_anterior, $proforma_mes_actual, $proforma_mes_anterior, $td_mes_actual,
                    $td_mes_anterior, $vh_mes_actual, $vh_mes_anterior, $cotizaciones_enviadas_anterior,$cotizaciones_enviadas_actual, 
                    $trafico_citas_mes_anterior, $trafico_citas_mes_actual,$trafico_citas_concretadas_mes_anterior,$trafico_citas_concretadas_mes_actual,
                    $tit_mac, $tit_man, $tipo){
                if($maxvalue >= 1){   
                    $prmac = ($prospeccion_mes_actual * 100) / $maxvalue;
                    $prman = ($prospeccion_mes_anterior * 100) / $maxvalue;  
                    $tmac = ($trafico_mes_actual * 100)/$maxvalue;
                    $tman = ($trafico_mes_anterior * 100)/$maxvalue;
                    $pmac = ($proforma_mes_actual * 100)/$maxvalue;
                    $pman = ($proforma_mes_anterior * 100)/$maxvalue;
                    $tdmac = ($td_mes_actual * 100)/$maxvalue;
                    $tdman = ($td_mes_anterior * 100)/$maxvalue;
                    $vmac = ($vh_mes_actual * 100)/$maxvalue;
                    $vman = ($vh_mes_anterior * 100)/$maxvalue;
                    $cmac = ($cotizaciones_enviadas_actual * 100)/$maxvalue;
                    $cman = ($cotizaciones_enviadas_anterior * 100)/$maxvalue;
                    $citgmac = ($trafico_citas_mes_actual * 100)/$maxvalue;
                    //die('citgmac: '.$citgmac);
                    $citgman = ($trafico_citas_mes_anterior * 100)/$maxvalue;
                    $citcmac = ($trafico_citas_concretadas_mes_actual * 100)/$maxvalue;
                    $citcman = ($trafico_citas_concretadas_mes_anterior * 100)/$maxvalue;
                    
                    $data .= '<div class="graficas">';
                    if($tipo == 'externas'){
                        $data .= '<div class="barra azul g2" style="width:'.$cmac.'%;"><span class="barratit tazul">Solicitudes web mes actual</span>'.$cotizaciones_enviadas_actual.'</div><br/>'
                                . '<div class="barra roja g2" style="width:'.$cman.'%;"><span class="barratit tazul">Solicitudes web mes anterior</span>'.$cotizaciones_enviadas_anterior.'</div><br/>';
                        
                        $data .= '<div class="barra roja g5" style="width:'.$pmac.'%;"><span class="barratit trojo">Proforma mes actual</span>'.$proforma_mes_actual.'</div>'.
                     '<div class="barra azul g6" style="width:'.$pman.'%;"><span class="barratit tazul">Proforma mes anterior</span>'.$proforma_mes_anterior.'</div><br/>'; 

                        $data .= '<div class="barra roja g1" style="width:'.$tmac.'%;"><span class="barratit trojo">'.$tit_mac.'</span>'.$trafico_mes_actual.'</div>'
                        . '<div class="barra azul g2" style="width:'.$tman.'%;"><span class="barratit tazul">'.$tit_man.'</span>'.$trafico_mes_anterior.'</div><br/>'; 

                    }
                    if($tipo == 'tw'){
                        $data .= '<div class="barra roja g2" style="width:'.$prmac.'%;"><span class="barratit trojo">Solicitudes Recibidas Mes Actual</span>'.$prospeccion_mes_actual.'</div><br/>'
                                . '<div class="barra azul g2" style="width:'.$prman.'%;"><span class="barratit tazul">Solicitudes Recibibas Mes Anterior</span>'.$prospeccion_mes_anterior.'</div><br/>';
                        $data .= '<div class="barra roja g5" style="width:'.$pmac.'%;"><span class="barratit trojo">Proforma mes actual</span>'.$proforma_mes_actual.'</div>'.
                     '<div class="barra azul g6" style="width:'.$pman.'%;"><span class="barratit tazul">Proforma mes anterior</span>'.$proforma_mes_anterior.'</div><br/>';
                        $data .= '<div class="barra roja g2" style="width:'.$citgmac.'%;"><span class="barratit trojo">Citas Generadas mes actual</span>'.$trafico_citas_mes_actual.'</div><br/>'
                                . '<div class="barra azul g2" style="width:'.$citgman.'%;"><span class="barratit tazul">Citas Generadas mes anterior</span>'.$trafico_citas_mes_anterior.'</div><br/>';
                        $data .= '<div class="barra roja g2" style="width:'.$citcmac.'%;"><span class="barratit trojo">Citas Concretadas mes actual</span>'.$trafico_citas_concretadas_mes_actual.'</div><br/>'
                                . '<div class="barra azul g2" style="width:'.$citcman.'%;"><span class="barratit tazul">Citas Concretadas mes anterior</span>'.$trafico_citas_concretadas_mes_anterior.'</div><br/>';
                    }
                    if($tipo == '' || $tipo == 'exhibicion'){
                        $data .= '<div class="barra roja g1" style="width:'.$tmac.'%;"><span class="barratit trojo">'.$tit_mac.'</span>'.$trafico_mes_actual.'</div>'
                        . '<div class="barra azul g2" style="width:'.$tman.'%;"><span class="barratit tazul">'.$tit_man.'</span>'.$trafico_mes_anterior.'</div><br/>';
                        $data .= '<div class="barra roja g5" style="width:'.$pmac.'%;"><span class="barratit trojo">Proforma mes actual</span>'.$proforma_mes_actual.'</div>'.
                     '<div class="barra azul g6" style="width:'.$pman.'%;"><span class="barratit tazul">Proforma mes anterior</span>'.$proforma_mes_anterior.'</div><br/>';        
                    }
                    $data .= '<div class="barra roja g3" style="width:'.$tdmac.'%;"><span class="barratit trojo">Test Drive mes actual</span>'.$td_mes_actual.'</div>'.
                     '<div class="barra azul g4" style="width:'.$tdman.'%;"><span class="barratit tazul">Test Drive mes anterior</span>'.$td_mes_anterior.'</div><br/>'.
                     '<div class="barra roja g7" style="width:'.$vmac.'%;"><span class="barratit trojo">Ventas mes actual</span>'.$vh_mes_actual.'</div>'.
                     '<div class="barra azul g8" style="width:'.$vman.'%;"><span class="barratit tazul">Ventas mes anterior</span>'.$vh_mes_anterior.'</div><br/><br/></div>';
                }else{
                    $data .= '<h3>Estos valores no pueden ser graficados</h3>';
                }
                echo $data;
            }
        ?>
    </div>
</div>