<?php 
//echo '<pre>';
//print_r($varView);
//echo '</pre>';
 ?>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered" id="main_info_1">
                <thead>
                    <tr>
                        <th style="background:#d5d5d5;" id="mi_a1"></th>
                        <th style="background:#5cb85c;" id="mi_b1"><?= 'Fecha Inicial<br>'.$varView['fecha_inicial_actual'].' - '.$varView['fecha_actual'];  ?></th>
                        <th style="background:#f0ad4e;" id="mi_c1"><?= 'Fecha Final<br>'.$varView['fecha_inicial_anterior'].' - '.$varView['fecha_anterior']; ?></th>
                        <th style="background:#848485;" id="mi_d1">Variación</th>
                        <th style="background:#b6b5b6;" id="mi_e1">Diferencia</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($tipo != 'exhibicion' && $tipo != 'externas' && $tipo != 'tw'): ?>
                        <tr>
                            <td id="mi_a2">PROSPECCIÓN</td>
                            <td id="mi_b2"><?= $varView['prospeccion_mes_actual']; ?></td>
                            <td id="mi_c2"><?= $varView['prospeccion_mes_anterior']; ?></td>
                            <td id="mi_d2"><?= $varView['var_prp']?></td>
                            <td id="mi_e2"><?= $varView['dif_prp']?></td>
                        </tr>
                    <?php endif; ?>
                    
                    <?php if($tipo == 'externas' || $tipo == 'tw'): // SI TIPO DE VENTAS CON ASESORES DE VENTAS EXTERNAS?>
                        <tr>
                        <?php if($tipo == 'externas'): ?>
                            <td id="mi_a2">SOLICITUDES WEB</td>
                        <?php elseif($tipo == 'tw'): ?>
                            <td id="mi_a2">SOLICITUDES RECIBIDAS</td>
                        <?php endif; ?>
                            <td id="mi_b2"><?= $varView['prospeccion_mes_actual']; ?></td>
                            <td id="mi_c2"><?= $varView['prospeccion_mes_anterior']; ?></td>
                            <td id="mi_d2"><?= $varView['var_prp']?></td>
                            <td id="mi_e2"><?= $varView['dif_prp']?></td>
                        </tr>
                   <!-- <tr><td id="mi_a3">RESPUESTAS AUTOMÁTICAS</td>
                        <td id="mi_b3"><?= $varView['respuestas_enviadas_actual']; ?></td>
                        <td id="mi_c3"><?= $varView['respuestas_enviadas_anterior']; ?></td>
                        <td id="mi_d3"><?= $varView['var_raa']?></td>
                        <td id="mi_e3"><?= $varView['dif_rac']?></td>
                    </tr>-->
                    
                        <tr>
                        <?php if($tipo == 'externas'): ?>
                            <td id="mi_a3">PROFORMA</td>
                        <?php elseif($tipo == 'tw'): ?>
                            <td id="mi_a3">PROFORMAS ENVIADAS</td>
                        <?php endif; ?>
                            <td id="mi_b3"><?= $varView['proforma_mes_actual']; ?></td>
                            <td id="mi_c3"><?= $varView['proforma_mes_anterior']; ?></td>
                            <td id="mi_d3"><?= $varView['var_pr']?></td>
                            <td id="mi_e3"><?= $varView['dif_pr']?></td>
                        </tr>
                        <tr>
                        <?php if($tipo == 'externas'): ?>
                            <td id="mi_a4">CITAS</td>
                        <?php elseif($tipo == 'tw'): ?>
                            <td id="mi_a4">CITAS GENERADAS</td>
                        <?php endif; ?>
                        
                            <td id="mi_b4"><?= $varView['trafico_citas_mes_actual']; ?></td>
                            <td id="mi_c4"><?= $varView['trafico_citas_mes_anterior']; ?></td>
                            <td id="mi_d4"><?= $varView['var_citas_generadas']?></td>
                            <td id="mi_e4"><?= $varView['dif_citas_generadas']?></td>
                        </tr>
                        <?php if($tipo == 'tw'):?>
                        <tr><td id="mi_a5">CITAS CONCRETADAS</td>
                            <td id="mi_b5"><?= $varView['trafico_citas_concretadas_mes_actual']; ?></td>
                            <td id="mi_c5"><?= $varView['trafico_citas_concretadas_mes_anterior']; ?></td>
                            <td id="mi_d5"><?= $varView['var_citas_concretadas']?></td>
                            <td id="mi_e5"><?= $varView['dif_citas_concretadas']?></td>
                        </tr>
                        <?php endif; ?>
                    <?php else: ?>
                        <tr><td id="mi_a3">TRÁFICO</td>
                            <td id="mi_b3"><?= $varView['trafico_mes_actual']; ?></td>
                            <td id="mi_c3"><?= $varView['trafico_mes_anterior']; ?></td>
                            <td id="mi_d3"><?= $varView['var_tr']?></td>
                            <td id="mi_e3"><?= $varView['dif_tr']?></td>
                        </tr>
                        <tr><td id="mi_a4">PROFORMA</td>
                            <td id="mi_b4"><?= $varView['proforma_mes_actual']; ?></td>
                            <td id="mi_c4"><?= $varView['proforma_mes_anterior']; ?></td>
                            <td id="mi_d4"><?= $varView['var_pr']?></td>
                            <td id="mi_e4"><?= $varView['dif_pr']?></td>
                        </tr>    
                    <?php endif; ?>
                    
                    <tr><td id="mi_a5">TESTDRIVE</td>
                        <td id="mi_b5" style="background:#c6f4c6;"><?= $varView['td_mes_actual']; ?></td>
                        <td id="mi_c5" style="background:#f9d3a5;"><?= $varView['td_mes_anterior']; ?></td>
                        <td id="mi_d5"><?= $varView['var_td']?></td>
                        <td id="mi_e5"><?= $varView['dif_td']?></td>
                    </tr>                                        
                    <tr><td id="mi_a6">VENTAS</td>
                        <td id="mi_b6" style="background:#c6f4c6;"><?= $varView['vh_mes_actual']; ?></td>
                        <td id="mi_c6" style="background:#f9d3a5;"><?= $varView['vh_mes_anterior']; ?></td>
                        <td id="mi_d6"><?= $varView['var_ve']; ?></td>
                        <td id="mi_e6"><?= $varView['dif_ve']; ?></td>
                    </tr>



                    <tr><td id="mi_a7">VENTAS CONTADO</td>
                        <td id="mi_b7" style="background:#c6f4c6;"><?= $varView['vcont_mes_actual']; ?></td>
                        <td id="mi_c7" style="background:#f9d3a5;"><?= $varView['vcont_mes_anterior']; ?></td>
                        <td id="mi_d7"><?= $varView['var_cont']; ?></td>
                        <td id="mi_e7"><?= $varView['dif_cont']; ?></td>
                    </tr>



                    <tr><td id="mi_a8">VENTAS CREDITO</td>
                        <td id="mi_b8" style="background:#c6f4c6;"><?= $varView['vcred_mes_actual']; ?></td>
                        <td id="mi_c8" style="background:#f9d3a5;"><?= $varView['vcred_mes_anterior']; ?></td>
                        <td id="mi_d8"><?= $varView['var_cred']; ?></td>
                        <td id="mi_e8"><?= $varView['dif_cred']; ?></td>
                    </tr>




                    <tr>
                        <td colspan="5" bgcolor="#ccc"></td>
                    </tr>
                    <?php if($tipo == 'tw'): ?>
                        <tr>
                            <td id="mi_a9">TASA DE TEST DRIVE</td>
                            <td id="mi_b9"><?= $varView['tasa_td_tw_actual']  ?></td>
                            <td id="mi_c9"> <?= $varView['tasa_td_tw_anterior'] ?></td>
                            <td id="mi_d9"><?= $varView['tasa_dif_testdrive'] ?></td>
                            <td id="mi_e9"></td>
                        </tr>                                  
                        <tr>
                            <td id="mi_a10">TASA DE CIERRE</td>
                            <td id="mi_b10"><?= $varView['tasa_cierre_tw_actual'] ?></td>
                            <td id="mi_c10"><?=  $varView['tasa_cierre_tw_anterior'] ?></td>
                            <td id="mi_d10"><?= $varView['tasa_dif_cierre'] ?></td>
                            <td id="mi_e10"></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td id="mi_a9">TASA DE PROFORMAS</td>
                            <td id="mi_b9" style="background:#c6f4c6;"><?= $varView['tasa_proforma_actual'] ?></td>
                            <td id="mi_c9" style="background:#f9d3a5;"><?= $varView['tasa_proforma_anterior'] ?></td>
                            <td id="mi_d9"><?= $varView['tasa_dif_proforma'] ?></td>
                            <td id="mi_e9"></td>
                        </tr>
                        <tr>
                            <td id="mi_a10">TASA DE TEST DRIVE</td>
                            <td id="mi_b10"><?= $varView['tasa_testdrive_actual']  ?></td>
                            <td id="mi_c10"> <?= $varView['tasa_testdrive_anterior'] ?></td>
                            <td id="mi_d10"><?= $varView['tasa_dif_testdrive'] ?></td>
                            <td id="mi_e10"></td>
                        </tr>                                  
                        <tr>
                            <td id="mi_a11">TASA DE CIERRE</td>
                            <td id="mi_b11"><?= $varView['tasa_cierre_actual'] ?></td>
                            <td id="mi_c11"><?=  $varView['tasa_cierre_anterior'] ?></td>
                            <td id="mi_d11"><?= $varView['tasa_dif_cierre'] ?></td>
                            <td id="mi_e11"></td>
                        </tr>
                    <?php endif; ?>
                                        
                </tbody>
            </table>
        </div>
    </div>
</div>

 <!--GRAFICOS-->
<?= $this->renderPartial('//reportes/modulos/graficos', array( 'varView' => $varView, 'tipo' => $tipo));?>
<!--FIN GRAFICOS-->

<?php if($varView['triger'] != 1): //se muestra si no existe consulta de modelos y versiones?>
<div class="row">
    <h2>Ensamblado Nacional (CKD) e internacional (CBU)</h2>
    <div class="col-md-4">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th id="mi_a14" width="45%" scope="col" style="background:#d5d5d5;">&nbsp;</th>
                        <th id="mi_b14" colspan="2" scope="col" style="background:#5cb85c;"><?= 'Fecha inicial<br>'.$varView['fecha_inicial_actual'].' - '.$varView['fecha_actual']; ?></th>
                    </tr>
                </thead>  
                <tbody>
                    <tr>
                        <td id="mi_a15">&nbsp;</td>
                        <td id="mi_b15" class="t-caption">CKD</td>
                        <td id="mi_c15" class="t-caption">CBU</td>
                    </tr>
                    <?php if($tipo == 'tw'): ?>
                        <tr>
                            <td id="mi_a16">CITAS GENERADAS</td>
                            <td id="mi_b16" style="background:#c6f4c6;"><?= $varView['traficockd2']; ?></td>
                            <td id="mi_c16" style="background:#c6f4c6;"><?= $varView['traficocbu2']; ?></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td id="mi_a16">TRAFICO</td>
                            <td id="mi_b16" style="background:#c6f4c6;"><?= $varView['traficockd2']; ?></td>
                            <td id="mi_c16" style="background:#c6f4c6;"><?= $varView['traficocbu2']; ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td id="mi_a18">PROFORMA</td>
                        <td id="mi_b18" style="background:#c6f4c6;"><?= $varView['proformackd2']; ?></td>
                        <td id="mi_c18" style="background:#c6f4c6;"><?= $varView['proformacbu2']; ?></td>
                    </tr>
                    <tr>
                        <td id="mi_a17">TESDRIVE</td>
                        <td id="mi_b17"><?= $varView['tdckd2']; ?></td>
                        <td id="mi_c17"><?= $varView['tdcbu2']; ?></td>
                    </tr>                                        
                    <tr>
                        <td id="mi_a19">VENTAS</td>
                        <td id="mi_b19"><?= $varView['vhckd2']; ?></td>
                        <td id="mi_c19"><?= $varView['vhcbu2']; ?></td>
                    </tr>
                    <?php if($tipo != 'tw'): ?>
                    <tr>
                        <td id="mi_a21">TASA PROFORMAS</td>
                        <td id="mi_b21"><?= $varView['tasa_proforma_ckd_m1'] ?></td>
                        <td id="mi_c21"><?= $varView['tasa_proforma_cbu_m1'] ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td id="mi_a20">TASA TESTDRIVE</td>
                        <td id="mi_b20" style="background:#c6f4c6;"> <?= $varView['tasa_testdrive_ckd_m1'] ?> </td>
                        <td id="mi_c20" style="background:#c6f4c6;"><?= $varView['tasa_testdrive_cbu_m1'] ?></td>
                    </tr>                                        
                    <tr>
                        <td id="mi_a22">TASA CIERRE</td>
                        <td id="mi_b22" style="background:#c6f4c6;"><?= $varView['tasa_cierre_ckd_m1'] ?></td>
                        <td id="mi_c22" style="background:#c6f4c6;"><?= $varView['tasa_cierre_cbu_m1'] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div> 
    <div class="col-md-4">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th id="mi_d14" colspan="2" scope="col" style="background:#f0ad4e;"><?= 'Fecha final<br>'.$varView['fecha_inicial_anterior'].' - '.$varView['fecha_anterior']; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td id="mi_d15" width="38%" class="t-caption">CKD</td>
                        <td id="mi_e15" width="39%" class="t-caption">CBU</td>
                    </tr>
                    <tr>
                        <td id="mi_d16" style="background:#f9d3a5;"><?= $varView['traficockd1']; ?></td>
                        <td id="mi_e16" style="background:#f9d3a5;"><?= $varView['traficocbu1']; ?></td>
                    </tr>
                    <tr>
                        <td id="mi_d18" style="background:#f9d3a5;"><?= $varView['proformackd1']; ?></td>
                        <td id="mi_e18" style="background:#f9d3a5;"><?= $varView['proformacbu1']; ?></td>
                    </tr> 
                    <tr>
                        <td id="mi_d17"><?= $varView['tdckd1']; ?></td>
                        <td id="mi_e17"><?= $varView['tdcbu1']; ?></td>
                    </tr>                                       
                    <tr>
                        <td id="mi_d19"><?= $varView['vhckd1']; ?></td>
                        <td id="mi_e19"><?= $varView['vhcbu1']; ?></td>
                    </tr>
                    <tr>
                        <td id="mi_d21"><?= $varView['tasa_proforma_ckd_m2'] ?></td>
                        <td id="mi_e21"><?= $varView['tasa_proforma_cbu_m2'] ?></td>
                    </tr>
                    <?php if($tipo != 'tw'): ?>
                    <tr>
                        <td id="mi_d20" style="background:#f9d3a5;"><?= $varView['tasa_testdrive_ckd_m2'] ?></td>
                        <td id="mi_e20" style="background:#f9d3a5;"><?= $varView['tasa_testdrive_cbu_m2'] ?></td>
                    </tr>   
                    <?php endif; ?>                                    
                    <tr>
                        <td id="mi_d22" style="background:#f9d3a5;"><?= $varView['tasa_cierre_ckd_m2'] ?></td>
                        <td id="mi_e22" style="background:#f9d3a5;"><?= $varView['tasa_cierre_cbu_m2'] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>    
    </div>
    <div class="col-md-4">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th id="mi_f14" colspan="2" scope="col"  style="background:#b6b5b6;"><br>DIFERENCIA</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td id="mi_f15" width="38%" class="t-caption">CKD</td>
                        <td id="mi_g15" width="39%" class="t-caption">CBU</td>
                    </tr>
                    <tr>
                        <td id="mi_f16"><?= $varView['dif_ckd_trafico'] ?></td>
                        <td id="mi_g16"><?= $varView['dif_cbu_trafico'] ?></td>
                    </tr>
                    <tr>
                        <td id="mi_f18"><?= $varView['proformackd2'] - $varView['proformackd1']?></td>
                        <td id="mi_g18"><?= $varView['proformacbu2'] - $varView['proformacbu1']?></td>
                    </tr>
                    <tr>
                        <td id="mi_f17"><?= $varView['tdckd2'] - $varView['tdckd1']?></td>
                        <td id="mi_g17"><?= $varView['tdcbu2'] - $varView['tdcbu1']?></td>
                    </tr>                                        
                    <tr>
                        <td id="mi_f19"><?= $varView['vhckd2'] - $varView['vhckd1']?></td>
                        <td id="mi_g19"><?= $varView['vhcbu2'] - $varView['vhcbu1']?></td>
                    </tr>
                    <tr>
                        <td id="mi_f20"><?= $varView['tasa_td_dif_ckd'].'%';?> </td>
                        <td id="mi_g20"><?= $varView['tasa_td_dif_cbu'].'%';?></td>
                    </tr>
                    <?php if($tipo != 'tw'): ?>
                    <tr>
                        <td id="mi_f21"><?= $varView['tasa_pr_dif_ckd'].'%'; ?></td>
                        <td id="mi_g21"><?= $varView['tasa_pr_dif_cbu'].'%';  ?></td>
                    </tr> 
                    <?php endif; ?>                        
                    <tr>
                        <td id="mi_f22"><?= $varView['tasa_cierre_dif_ckd'].'%';?></td>
                        <td id="mi_g22"><?= $varView['tasa_cierre_dif_cbu'].'%';?></td>
                    </tr>
                </tbody>
            </table>
        </div>    
    </div>
</div>
<?php endif ?>