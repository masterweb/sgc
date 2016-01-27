<div class="row">        
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th><?= 'Mes actual<br>'.$varView['nombre_mes_actual']; ?></th>
                            <th><?= 'Mes anterior<br>'.$varView['nombre_mes_anterior']; ?></th>
                            <th>Variación</th>
                            <th>Diferencia</th>
                        </tr>
                    <tbody>
                        <tr><td>TRÁFICO</td>
                            <td><?= $varView['trafico_mes_actual']; ?></td>
                            <td><?= $varView['trafico_mes_anterior']; ?></td>
                            <td><?= $varView['var_tr']?></td>
                            <td><?= $varView['dif_tr']?></td>
                        </tr>
                        <tr><td>PROFORMA</td>
                            <td><?= $varView['proforma_mes_actual']; ?></td>
                            <td><?= $varView['proforma_mes_anterior']; ?></td>
                            <td><?= $varView['var_pr']?></td>
                            <td><?= $varView['dif_pr']?></td>
                        </tr>
                        <tr><td>TESTDRIVE</td>
                            <td><?= $varView['td_mes_actual']; ?></td>
                            <td><?= $varView['td_mes_anterior']; ?></td>
                            <td><?= $varView['var_td']?></td>
                            <td><?= $varView['dif_td']?></td>
                        </tr>
                        <tr><td>VENTAS</td>
                            <td><?= $varView['vh_mes_actual']; ?></td>
                            <td><?= $varView['vh_mes_anterior']; ?></td>
                            <td><?= $varView['var_ve']; ?></td>
                            <td><?= $varView['dif_ve']; ?></td>
                        </tr>
                        <tr>
                            <td colspan="5" bgcolor="#ccc"></td>
                        </tr>                   
                        <tr>
                            <td>TASA DE PROFORMAS</td>
                            <td><?= $varView['tasa_proforma_actual'] ?></td>
                            <td><?= $varView['tasa_proforma_anterior'] ?></td>
                            <td><?= $varView['tasa_dif_proforma'] ?></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>TASA DE TEST DRIVE</td>
                            <td><?= $varView['tasa_testdrive_actual']  ?></td>
                            <td> <?= $varView['tasa_testdrive_anterior'] ?></td>
                            <td><?= $varView['tasa_dif_testdrive'] ?></td>
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

    <?php if($varView['triger'] != 1): //muestra si no existe consulta de modelos y versiones?>
    <div class="row">
        <div class="col-md-4">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="45%" scope="col">&nbsp;</th>
                            <th colspan="2" scope="col"><?= 'Mes actual<br>'.$varView['nombre_mes_actual']; ?></th>
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
                            <td><?= $varView['traficockd2']; ?></td>
                            <td><?= $varView['traficocbu2']; ?></td>
                        </tr>
                        <tr>
                            <td>PROFORMA</td>
                            <td><?= $varView['proformackd2']; ?></td>
                            <td><?= $varView['proformacbu2']; ?></td>
                        </tr>
                        <tr>
                            <td>TESDRIVE</td>
                            <td><?= $varView['tdckd2']; ?></td>
                            <td><?= $varView['tdcbu2']; ?></td>
                        </tr>
                        <tr>
                            <td>VENTAS</td>
                            <td><?= $varView['vhckd2']; ?></td>
                            <td><?= $varView['vhcbu2']; ?></td>
                        </tr>
                        <tr>
                            <td>TASA PROFORMAS</td>
                            <td><?= $varView['tasa_proforma_ckd_m1'] ?></td>
                            <td><?= $varView['tasa_proforma_cbu_m1'] ?></td>
                        </tr>
                        <tr>
                            <td>TASA TESTDRIVE</td>
                            <td> <?= $varView['tasa_testdrive_ckd_m1'] ?> </td>
                            <td><?= $varView['tasa_testdrive_cbu_m1'] ?></td>
                        </tr>
                        <tr>
                            <td>TASA CIERRE</td>
                            <td><?= $varView['tasa_cierre_ckd_m1'] ?></td>
                            <td><?= $varView['tasa_cierre_cbu_m1'] ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div> 
        <div class="col-md-3">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="2" scope="col"><?= 'Mes anterior<br>'.$varView['nombre_mes_anterior']; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="38%" class="t-caption">CKD</td>
                            <td width="39%" class="t-caption">CBU</td>
                        </tr>
                        <tr>
                            <td><?= $varView['traficockd1']; ?></td>
                            <td><?= $varView['traficocbu1']; ?></td>
                        </tr>
                        <tr>
                            <td><?= $varView['proformackd1']; ?></td>
                            <td><?= $varView['proformacbu1']; ?></td>
                        </tr>
                        <tr>
                            <td><?= $varView['tdckd1']; ?></td>
                            <td><?= $varView['tdcbu1']; ?></td>
                        </tr>
                        <tr>
                            <td><?= $varView['vhckd1']; ?></td>
                            <td><?= $varView['vhcbu1']; ?></td>
                        </tr>
                        <tr>
                            <td><?= $varView['tasa_proforma_ckd_m2'] ?></td>
                            <td><?= $varView['tasa_proforma_cbu_m2'] ?></td>
                        </tr>
                        <tr>
                            <td><?= $varView['tasa_testdrive_ckd_m2'] ?></td>
                            <td><?= $varView['tasa_testdrive_cbu_m2'] ?></td>
                        </tr>
                        <tr>
                            <td><?= $varView['tasa_cierre_ckd_m2'] ?></td>
                            <td><?= $varView['tasa_cierre_cbu_m2'] ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>    
        </div>
        <div class="col-md-3">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="2" scope="col"><br>DIFERENCIA</th>
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
                            <td><?= $varView['proformackd2'] - $varView['proformackd1'] ?></td>
                            <td><?= $varView['proformacbu2'] - $varView['proformacbu1'] ?></td>
                        </tr>
                        <tr>
                            <td><?= $varView['tdckd2'] - $varView['tdckd1'] ?></td>
                            <td><?= $varView['tdcbu2'] - $varView['tdcbu1'] ?></td>
                        </tr>
                        <tr>
                            <td><?= $varView['vhckd2'] - $varView['vhckd1'] ?></td>
                            <td><?= $varView['vhcbu2'] - $varView['vhcbu1'] ?></td>
                        </tr>
                        <tr>
                            <td><?= ($tasapr2 - $tasapr2ant).'%'; ?></td>
                            <td><?= ($tasapr2cbu - $tasapr2antcbu).'%';  ?></td>
                        </tr>
                        <tr>
                            <td><?= $tasatd2 - $tasatd2ant.'%';?> </td>
                            <td><?= $tasatd2cbu - $tasatd2antcbu.'%';?></td>
                        </tr>
                        <tr>
                            <td><?= $tasac2 - $tasac2ant.'%';?></td>
                            <td> <?= $tasac2cbu - $tasac2antcbu.'%';?></td>
                        </tr>

                    </tbody>
                </table>
            </div>    
        </div>
    </div>
<?php endif ?>