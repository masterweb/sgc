<?= $this->renderPartial('//reportes/modulos/header', array('title' => 'Reportes'));?>
    <div class="row">
        <div class="col-md-12">
            <div class="highlight">
                <?= $this->renderPartial('//reportes/modulos/filtros', array('modelos_car' => $varView['modelos_car']));?>
            </div>
        </div>
    </div>
    <br />
    <?php if (!empty($varView['$titulo'])): ?>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info" role="alert"><?= $varView['titulo']; ?></div>
            </div>
        </div>
    <?php endif; ?>
    <div class="row">
        <h1 class="tl_seccion">Embudo</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th><?= $varView['nombre_mes_actual']; ?></th>
                            <th><?= $varView['nombre_mes_anterior']; ?></th>
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
                    </tbody>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered" id="table-fixed">
                    <tbody>
                        <tr>
                            <td>TASA DE PROFORMAS</td>
                            <td>
                                <?php
                                $ts1 = 0;
                                if($varView['trafico_mes_actual'] > 0){
                                    $ts1 = ($varView['proforma_mes_actual'] / $varView['trafico_mes_actual']) * 100;
                                    //die('ts: '.$ts);
                                    $ts1 = round($ts1, 2);
                                    echo $ts1 . ' %';
                                }else{
                                    echo '0 %';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $ts2 = 0;
                                if($varView['trafico_mes_anterior'] > 0){
                                    $ts2 = ($varView['proforma_mes_anterior'] / $varView['trafico_mes_anterior']) * 100;
                                    $ts2 = round($ts2, 2);
                                    echo $ts2 . ' %';
                                }else{
                                    echo '0 %';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $dfpr = $ts1 - $ts2;
                                if($df > 0){
                                    echo $dfpr.' %';
                                }else{
                                    echo '<span class="dif">('.abs($dfpr).' %)</span>';
                                }
                                ?>
                            </td>
                            
                        </tr>
                        <tr>
                            <td>TASA DE TEST DRIVE</td>
                            <td>
                                <?php
                                $td1 = 0;
                                if($varView['trafico_mes_actual'] > 0){
                                    $td1 = ($varView['td_mes_actual'] / $varView['trafico_mes_actual'])* 100;
                                    $td1 = round($td1, 2);
                                    echo $td1 . ' %';
                                }else{
                                    echo '0 %';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $td2 = 0;
                                if($varView['trafico_mes_anterior'] > 0){
                                    $td2 = ($varView['td_mes_anterior'] / $varView['trafico_mes_anterior']) * 100;
                                    $td2 = round($td2, 2);
                                    echo $td2 . ' %';
                                }else{
                                    echo '0 %';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $dftd = $td1 - $td2;
                                if($dftd > 0){echo $dftd.' %';}else{echo '<span class="dif">('.abs($dftd).' %)</span>';}
                                
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>TASA DE CIERRE</td>
                            <td>
                                <?php
                                $tc1 = 0;
                                if($varView['trafico_mes_actual'] > 0){
                                    $tc1 = ($varView['vh_mes_actual'] / $varView['trafico_mes_actual']) * 100;
                                    $tc1 = round($tc1, 2);
                                    echo $tc1 . ' %';
                                }else{
                                    echo '0 %';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $tc2 = 0;
                                if($varView['trafico_mes_anterior'] > 0){
                                    $tc2 = ($varView['vh_mes_anterior'] / $varView['trafico_mes_anterior']) * 100;
                                    $tc2 = round($tc2, 2);
                                    echo $tc2 . ' %';
                                }else{
                                    echo '0 %';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $dftc = $tc1 - $tc2;
                                if($dftc > 0){echo $dftc.' %';}else{echo '<span class="dif">('.abs($dftc).' %)</span>';}
                                ?>
                            </td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>

     <!--GRAFICOS-->
    <?= $this->renderPartial('//reportes/modulos/graficos', array( 'varView' => $varView));?>
    <!--FIN GRAFICOS-->
    
    <div class="row">
        <div class="col-md-4">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="45%" scope="col">&nbsp;</th>
                            <th colspan="2" scope="col"><?= $varView['nombre_mes_actual']; ?></th>
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
                            <td>
                                <?php
                                $tasapr2 = 0;
                                if ($varView['traficockd2'] > 0) {
                                    $tasapr2 = ($varView['proformackd2'] / $varView['traficockd2']) * 100;
                                    $tasapr2 = round($tasapr2, 2);
                                    echo $tasapr2 . ' %';
                                } else {
                                    echo '0 %';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $tasapr2cbu = 0;
                                if ($varView['traficocbu2'] > 0) {
                                    $tasapr2cbu = ($varView['proformacbu2'] / $varView['traficocbu2']) * 100;
                                    $tasapr2cbu = round($tasapr2cbu, 2);
                                    echo $tasapr2cbu . ' %';
                                } else {
                                    echo '0 %';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>TASA TESTDRIVE</td>
                            <td>
                                <?php
                                $tasatd2 = 0;
                                if ($varView['traficockd2'] > 0) {
                                    $tasatd2 = ($varView['tdckd2'] / $varView['traficockd2']) * 100;
                                    $tasatd2 = round($tasatd2, 2);
                                    echo $tasatd2 . ' %';
                                } else {
                                    echo '0 %';
                                }
                                ?>    
                            </td>
                            <td>
                                <?php
                                $tasatd2cbu = 0;
                                if ($varView['traficocbu2'] > 0) {
                                    $tasatd2cbu = ($varView['tdcbu2'] / $varView['traficocbu2']) * 100;
                                    $tasatd2cbu = round($tasatd2cbu, 2);
                                    echo $tasatd2cbu . ' %';
                                } else {
                                    echo '0 %';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>TASA CIERRE</td>
                            <td>
                                <?php
                                $tasac2 = 0;
                                if ($varView['traficockd2'] > 0) {
                                    $tasac2 = ($varView['vhckd2'] / $varView['traficockd2']) * 100;
                                    $tasac2 = round($tasac2, 2);
                                    echo $tasac2 . ' %';
                                } else {
                                    echo '0 %';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $tasac2cbu = 0;
                                if ($varView['traficocbu2'] > 0) {
                                    $tasac2cbu = ($varView['vhcbu2'] / $varView['traficocbu2']) * 100;
                                    $tasac2cbu = round($tasac2cbu, 2);
                                    echo $tasac2cbu . ' %';
                                } else {
                                    echo '0 %';
                                }
                                ?>
                            </td>
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
                            <th colspan="2" scope="col"><?= $varView['nombre_mes_anterior']; ?></th>
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
                            <td>
                                <?php
                                $tasapr2ant = 0;
                                if ($varView['traficockd1'] > 0) {
                                    $tasapr2ant = ($varView['proformackd1'] / $varView['traficockd1']) * 100;
                                    $tasapr2ant = round($tasapr2ant, 2);
                                    echo $tasapr2ant . ' %';
                                } else {
                                    echo '0 %';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $tasapr2antcbu = 0;
                                if ($varView['traficocbu1'] > 0) {
                                    $tasapr2antcbu = ($varView['proformacbu1'] / $varView['traficocbu1']) * 100;
                                    $tasapr2antcbu = round($tasapr2antcbu, 2);
                                    echo $tasapr2antcbu . ' %';
                                } else {
                                    echo '0 %';
                                }
                                ?>
                        </tr>
                        <tr>
                            <td>
                                <?php
                                $tasatd2ant = 0;
                                if ($varView['traficockd1'] > 0) {
                                    $tasatd2ant = ($varView['tdckd1'] / $varView['traficockd1']) * 100;
                                    $tasatd2ant = round($tasatd2ant, 2);
                                    echo $tasatd2ant . ' %';
                                } else {
                                    echo '0 %';
                                }
                                ?>    
                            </td>
                            <td>
                                <?php
                                $tasatd2antcbu = 0;
                                if ($varView['traficocbu1'] > 0) {
                                    $tasatd2antcbu = ($varView['tdcbu1'] / $varView['traficocbu1']) * 100;
                                    $tasatd2antcbu = round($tasatd2antcbu, 2);
                                    echo $tasatd2antcbu . ' %';
                                } else {
                                    echo '0 %';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php
                                $tasac2ant = 0;
                                if ($varView['traficockd1'] > 0) {
                                    $tasac2ant = ($varView['vhckd1'] / $varView['traficockd1']) * 100;
                                    $tasac2ant = round($tasac2ant, 2);
                                    echo $tasac2ant . ' %';
                                } else {
                                    echo '0 %';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                 $tasac2antcbu = 0;
                                if ($varView['traficocbu1'] > 0) {
                                    $tasac2antcbu = ($varView['vhcbu1'] / $varView['traficocbu1']) * 100;
                                    $tasac2antcbu = round($tasac2antcbu, 2);
                                    echo $tasac2antcbu . ' %';
                                } else {
                                    echo '0 %';
                                }
                                ?>
                            </td>
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
                            <th colspan="2" scope="col">DIFERENCIA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="38%" class="t-caption">CKD</td>
                            <td width="39%" class="t-caption">CBU</td>
                        </tr>
                        <tr>
                            <td>
                                <?= $varView['dif_ckd_trafico'] ?>
                            </td>
                            <td>
                                <?= $varView['dif_cbu_trafico'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php
                                $dif1 = $varView['proformackd2'] - $varView['proformackd1'];
                                echo $dif1;
                                ?>
                            </td>
                            <td>
                                <?php
                                $dif1 = $varView['proformacbu2'] - $varView['proformacbu1'];
                                echo $dif1;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php
                                $dif1 = $varView['tdckd2'] - $varView['tdckd1'];
                                echo $dif1;
                                ?>
                            </td>
                            <td>
                                <?php
                                $dif1 = $varView['tdcbu2'] - $varView['tdcbu1'];
                                echo $dif1;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php
                                $dif1 = $varView['vhckd2'] - $varView['vhckd1'];
                                echo $dif1;
                                ?>
                            </td>
                            <td>
                                <?php
                                $dif1 = $varView['vhcbu2'] - $varView['vhcbu1'];
                                echo $dif1;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php
                                $difckd = $tasapr2 - $tasapr2ant;
                                echo $difckd .'%';
                                ?>
                            </td>
                            <td>
                                <?php
                                $difcbu = $tasapr2cbu - $tasapr2antcbu;
                                echo $difcbu.'%';;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                               <?php
                                $difckdtd = $tasatd2 - $tasatd2ant;
                                echo $difckdtd.'%';;
                                ?> 
                            </td>
                            <td>
                                <?php
                                $difcbutd = $tasatd2cbu - $tasatd2antcbu;
                                echo $difcbutd.'%';;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php
                                $difckdtc = $tasac2 - $tasac2ant;
                                echo $difckdtc.'%';;
                                ?> 
                            </td>
                            <td>
                                <?php
                                $difcbutc = $tasac2cbu - $tasac2antcbu;
                                echo $difcbutc.'%';;
                                ?>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>    
        </div>
    </div>
<?= $this->renderPartial('//layouts/reportes/footer');?>
