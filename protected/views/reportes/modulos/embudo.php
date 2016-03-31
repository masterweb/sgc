<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="background:#d5d5d5;"></th>
                        <th style="background:#5cb85c;"><?= 'Fecha Inicial<br>'.$varView['fecha_inicial_actual'].' - '.$varView['fecha_actual'];  ?></th>
                        <th style="background:#f0ad4e;"><?= 'Fecha Final<br>'.$varView['fecha_inicial_anterior'].' - '.$varView['fecha_anterior']; ?></th>
                        <th style="background:#848485;">Variación</th>
                        <th style="background:#b6b5b6;">Diferencia</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>TRÁFICO</td>
                        <td><?= $varView['trafico_mes_actual']; ?></td>
                        <td><?= $varView['trafico_mes_anterior']; ?></td>
                        <td><?= $varView['var_tr']?></td>
                        <td><?= $varView['dif_tr']?></td>
                    </tr>
                    <tr><td>TESTDRIVE</td>
                        <td style="background:#c6f4c6;"><?= $varView['td_mes_actual']; ?></td>
                        <td style="background:#f9d3a5;"><?= $varView['td_mes_anterior']; ?></td>
                        <td><?= $varView['var_td']?></td>
                        <td><?= $varView['dif_td']?></td>
                    </tr>
                    <tr><td>PROFORMA</td>
                        <td><?= $varView['proforma_mes_actual']; ?></td>
                        <td><?= $varView['proforma_mes_anterior']; ?></td>
                        <td><?= $varView['var_pr']?></td>
                        <td><?= $varView['dif_pr']?></td>
                    </tr>                    
                    <tr><td>VENTAS</td>
                        <td style="background:#c6f4c6;"><?= $varView['vh_mes_actual']; ?></td>
                        <td style="background:#f9d3a5;"><?= $varView['vh_mes_anterior']; ?></td>
                        <td><?= $varView['var_ve']; ?></td>
                        <td><?= $varView['dif_ve']; ?></td>
                    </tr>
                    <tr>
                        <td colspan="5" bgcolor="#ccc"></td>
                    </tr>
                    <tr>
                        <td>TASA DE TEST DRIVE</td>
                        <td><?= $varView['tasa_testdrive_actual']  ?></td>
                        <td> <?= $varView['tasa_testdrive_anterior'] ?></td>
                        <td><?= $varView['tasa_dif_testdrive'] ?></td>
                        <td></td>
                    </tr>                  
                    <tr>
                        <td>TASA DE PROFORMAS</td>
                        <td style="background:#c6f4c6;"><?= $varView['tasa_proforma_actual'] ?></td>
                        <td style="background:#f9d3a5;"><?= $varView['tasa_proforma_anterior'] ?></td>
                        <td><?= $varView['tasa_dif_proforma'] ?></td>
                        <td></td>
                    </tr>                    
                    <tr>
                        <td>TASA DE CIERRE</td>
                        <td><?= $varView['tasa_cierre_actual'] ?></td>
                        <td><?=  $varView['tasa_cierre_anterior'] ?></td>
                        <td><?= $varView['tasa_dif_cierre'] ?></td>
                        <td></td>
                    </tr>                    
                </tbody>
            </table>
        </div>
    </div>
</div>

 <!--GRAFICOS-->
<?= $this->renderPartial('//reportes/modulos/graficos', array( 'varView' => $varView));?>
<!--FIN GRAFICOS-->

<?php if($varView['triger'] != 1): //se muestra si no existe consulta de modelos y versiones?>
<div class="row">
    <h2>Ensamblado Nacional (CKD) e internacional (CBU)</h2>
    <div class="col-md-4">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="45%" scope="col" style="background:#d5d5d5;">&nbsp;</th>
                        <th colspan="2" scope="col" style="background:#5cb85c;"><?= 'Fecha inicial<br>'.$varView['fecha_inicial_actual'].' - '.$varView['fecha_actual']; ?></th>
                    </tr>
                </thead>  
                <tbody>
                    <tr>
                        <td>&nbsp;</td>
                        <td class="t-caption">CKD</td>
                        <td class="t-caption">CBU</td>
                    </tr>
                    <tr>
                        <td>TRAFICO</td>
                        <td style="background:#c6f4c6;"><?= $varView['traficockd2']; ?></td>
                        <td style="background:#c6f4c6;"><?= $varView['traficocbu2']; ?></td>
                    </tr>
                    <tr>
                        <td>TESDRIVE</td>
                        <td><?= $varView['tdckd2']; ?></td>
                        <td><?= $varView['tdcbu2']; ?></td>
                    </tr>
                    <tr>
                        <td>PROFORMA</td>
                        <td style="background:#c6f4c6;"><?= $varView['proformackd2']; ?></td>
                        <td style="background:#c6f4c6;"><?= $varView['proformacbu2']; ?></td>
                    </tr>                    
                    <tr>
                        <td>VENTAS</td>
                        <td><?= $varView['vhckd2']; ?></td>
                        <td><?= $varView['vhcbu2']; ?></td>
                    </tr>
                    <tr>
                        <td>TASA TESTDRIVE</td>
                        <td style="background:#c6f4c6;"> <?= $varView['tasa_testdrive_ckd_m1'] ?> </td>
                        <td style="background:#c6f4c6;"><?= $varView['tasa_testdrive_cbu_m1'] ?></td>
                    </tr>
                    <tr>
                        <td>TASA PROFORMAS</td>
                        <td><?= $varView['tasa_proforma_ckd_m1'] ?></td>
                        <td><?= $varView['tasa_proforma_cbu_m1'] ?></td>
                    </tr>                    
                    <tr>
                        <td>TASA CIERRE</td>
                        <td style="background:#c6f4c6;"><?= $varView['tasa_cierre_ckd_m1'] ?></td>
                        <td style="background:#c6f4c6;"><?= $varView['tasa_cierre_cbu_m1'] ?></td>
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
                        <th colspan="2" scope="col" style="background:#f0ad4e;"><?= 'Fecha final<br>'.$varView['fecha_inicial_anterior'].' - '.$varView['fecha_anterior']; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td width="38%" class="t-caption">CKD</td>
                        <td width="39%" class="t-caption">CBU</td>
                    </tr>
                    <tr>
                        <td style="background:#f9d3a5;"><?= $varView['traficockd1']; ?></td>
                        <td style="background:#f9d3a5;"><?= $varView['traficocbu1']; ?></td>
                    </tr>
                    <tr>
                        <td><?= $varView['tdckd1']; ?></td>
                        <td><?= $varView['tdcbu1']; ?></td>
                    </tr>
                    <tr>
                        <td style="background:#f9d3a5;"><?= $varView['proformackd1']; ?></td>
                        <td style="background:#f9d3a5;"><?= $varView['proformacbu1']; ?></td>
                    </tr>                    
                    <tr>
                        <td><?= $varView['vhckd1']; ?></td>
                        <td><?= $varView['vhcbu1']; ?></td>
                    </tr>
                    <tr>
                        <td style="background:#f9d3a5;"><?= $varView['tasa_testdrive_ckd_m2'] ?></td>
                        <td style="background:#f9d3a5;"><?= $varView['tasa_testdrive_cbu_m2'] ?></td>
                    </tr>
                    <tr>
                        <td><?= $varView['tasa_proforma_ckd_m2'] ?></td>
                        <td><?= $varView['tasa_proforma_cbu_m2'] ?></td>
                    </tr>                    
                    <tr>
                        <td style="background:#f9d3a5;"><?= $varView['tasa_cierre_ckd_m2'] ?></td>
                        <td style="background:#f9d3a5;"><?= $varView['tasa_cierre_cbu_m2'] ?></td>
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
                        <th colspan="2" scope="col"  style="background:#b6b5b6;"><br>DIFERENCIA</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td width="38%" class="t-caption">CKD</td>
                        <td width="39%" class="t-caption">CBU</td>
                    </tr>
                    <tr>
                        <td><?= $varView['dif_ckd_trafico'] ?></td>
                        <td><?= $varView['dif_cbu_trafico'] ?></td>
                    </tr>
                    <tr>
                        <td><?= $varView['tdckd1'] - $varView['tdckd2']?></td>
                        <td><?= $varView['tdcbu1'] - $varView['tdcbu2']?></td>
                    </tr>
                    <tr>
                        <td><?= $varView['proformackd1'] - $varView['proformackd2']?></td>
                        <td><?= $varView['proformacbu1'] - $varView['proformacbu2']?></td>
                    </tr>                    
                    <tr>
                        <td><?= $varView['vhckd1'] - $varView['vhckd2']?></td>
                        <td><?= $varView['vhcbu1'] - $varView['vhcbu2']?></td>
                    </tr>
                    <tr>
                        <td><?= $varView['tasa_td_dif_ckd'].'%';?> </td>
                        <td><?= $varView['tasa_td_dif_cbu'].'%';?></td>
                    </tr>
                    <tr>
                        <td><?= $varView['tasa_pr_dif_ckd'].'%'; ?></td>
                        <td><?= $varView['tasa_pr_dif_cbu'].'%';  ?></td>
                    </tr>                    
                    <tr>
                        <td><?= $varView['tasa_cierre_dif_ckd'].'%';?></td>
                        <td><?= $varView['tasa_cierre_dif_cbu'].'%';?></td>
                    </tr>
                </tbody>
            </table>
        </div>    
    </div>
</div>
<?php endif ?>