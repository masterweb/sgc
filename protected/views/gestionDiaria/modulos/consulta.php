<?php
$criteria4 = new CDbCriteria(array(
    'condition' => "id_informacion={$id}",
    'limit' => 1,
        ));
$art = GestionConsulta::model()->findAll($criteria4);
foreach ($art as $c) {
    //if ($c['preg1_sec5'] == 0 || !empty($c['preg1_sec5'])){ // SI TIENE VEHICULO
    $params = explode('@', $c['preg1_sec2']);
    //print_r($params);
    $modelo_auto = $params[1] . ' ' . $params[2];
    ?>
    <div class="col-md-8">
        <h4 class="text-danger">1. ¿Qué clase de vehículo conduce en la actualidad?</h4>
        <div class="col-md-4">
            <p><strong>Marca:</strong> <?php echo $c['preg1_sec1']; ?></p>
        </div>
        <div class="col-md-4">
            <p><strong>Modelo:</strong> <?php echo $modelo_auto; ?></p>
        </div>
        <div class="col-md-4">
            <p><strong>Año:</strong> <?php echo $c['preg1_sec3']; ?></p>
        </div>
        <div class="col-md-4">
            <p><strong>Kilometraje:</strong> <?php echo $c['preg1_sec4']; ?></p>
        </div>
    </div>
    <div class="col-md-8">
        <h4 class="text-danger">2. ¿Qué tiene pensado hacer con su vehículo actual?</h4>
        <?php if ($c['preg2'] == 0) { ?>
            <div class="col-md-4"><p>Vender</p></div>
            <?php
        }
        if ($c['preg2'] == 1) {
            ?>
            <div class="col-md-12"><p>Utilizar como parte de pago</p></div>
            <?php
            if ($c['preg2_sec1'] != '') {
                $stringAutos = $c['preg2_sec1'];
                $stringAutos = trim($stringAutos);
                $stringAutos = substr($stringAutos, 0, strlen($stringAutos) - 1);
                $paramAutos = explode('@', $stringAutos);
                ?>        
                <div class="row">
                    <?php foreach ($paramAutos as $value) { ?>
                        <div class="col-md-3">
                            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/uploads/<?php echo $value; ?>" alt="" width="125" class="img-thumbnail">
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="col-md-5"><a href="<?php $c['link']; ?>" target="_blank">Link de fotos auto usado</a></div>
                <?php
            }
        }
        if ($c['preg2'] == 2) {
            ?>
            <div class="col-md-4"><p>Mantenerlo</p></div>
            <?php
        }
        //} // END SI TIENE VEHICULO 
        ?>
    </div>
    <div class="col-md-8">
        <h4 class="text-danger">3. ¿Para qué utilizará el nuevo vehículo?</h4>
        <?php if ($c['preg3'] == 0) { ?>
            <div class="col-md-4"><p>Primer vehículo del hogar</p></div>
            <?php if ($c['preg3_sec1'] == 0) { ?>
                <div class="col-md-4"><p>Familiar</p></div>
            <?php } else { ?>
                <div class="col-md-4"><p>Trabajo</p></div>
                <?php
            }
        }
        if ($c['preg3'] == 1) {
            ?>
            <div class="col-md-4"><p>Segundo vehículo del hogar</p></div>
            <?php if ($c['preg3_sec2'] == 0) { ?>
                <div class="col-md-4"><p>Familiar</p></div>
            <?php } else { ?>
                <div class="col-md-4"><p>Trabajo</p></div>
                <?php
            }
        }
        if ($c['preg3'] == 2) {
            ?>
            <div class="col-md-4"><p>Renovación de vehículo</p></div>
        <?php } ?>
    </div>
    <div class="col-md-8"><h4 class="text-danger">4. ¿Quién más participa en la decisión de compra?</h4>
        <?php if ($c['preg4'] == 0) { ?>
            <div class="col-md-4"><p>Esposa/o</p></div>
            <?php
        }
        if ($c['preg4'] == 1) {
            ?>
            <div class="col-md-4"><p>Familiar</p></div>
            <?php
        }
        if ($c['preg4'] == 2) {
            ?>
            <div class="col-md-4"><p>Departamento de compras</p></div>
        <?php } ?>
    </div>
    <div class="col-md-8"><h4 class="text-danger">5. ¿Qué clase de presupuesto tiene previsto para su nuevo vehículo?</h4>
        <div class="col-md-4"><p>$ <?php echo number_format($c['preg5'], 2); ?></p></div>
    </div>
    <div class="col-md-8"><h4 class="text-danger">6. ¿Cuál sería su forma de pago para su nuevo vehículo?</h4>
        <?php if ($c['preg6'] == 0) { ?>
            <div class="col-md-4"><p>Contado</p></div>
            <?php
        }
        if ($c['preg6'] == 1) {
            ?>
            <div class="col-md-4"><p>Financiado</p></div>
        <?php } ?>
    </div>
    <div class="col-md-8">
        <h4 class="text-danger">7. ¿En qué tiempo estima realizar su compra?</h4>
        <div class="col-md-4"><p><?php echo $c['preg7']; ?></p></div>
    </div>
    <div class="col-md-8">
        <h4 class="text-danger">8. ¿Cuál es su necesidad básica?</h4>
        <div class="col-md-4"><p><?php echo $this->getArg($c['preg8']); ?></p></div>
    </div>
    <div class="col-md-8">
        <h4 class="text-danger">Vehículos Recomendados</h4>
        <?php
        $vh = GestionVehiculo::model()->findAll(array('condition' => "id_informacion={$id}"));
        foreach ($vh as $val) {
            ?>
            <div class="row">
                <div class="col-md-4"><strong>Modelo: </strong><?php echo $this->getModel($val['modelo']); ?></div>
                <div class="col-md-4"><strong>Versión: </strong><?php echo $this->getVersion($val['version']); ?></div>
                <div class="col-md-4"><strong>Precio: </strong>$ <?php if (!empty($val['precio'])) {
            echo number_format($val['precio'], 2);
        } ?></div>
            </div>
    <?php } ?>
    </div>
    <?php
    $crit = new CDbCriteria(array('condition' => "id_informacion={$id} AND paso = 4"));
    $agen = GestionAgendamiento::model()->count($crit);
    $ag = GestionAgendamiento::model()->findAll($crit);
    if ($agen > 0) {
        ?>
        <div class="col-md-8"><h4 class="tl-agen">Agendamientos</h4></div>
    <?php } foreach ($ag as $a) { ?>
        <div class="row">
            <div class="col-md-4"><strong>Fecha Agendamiento: </strong><?php echo $a['agendamiento']; ?></div>
            <div class="col-md-4"><strong>Motivo: </strong><?php echo $a['observaciones']; ?></div>
            <div class="col-md-4"><strong>Observaciones: </strong><?php echo $a['otro_observacion']; ?></div>
        </div>
    <?php } ?>

<?php } //endforeach ?>

