<?php
$criteria4 = new CDbCriteria(array(
    'condition' => "id_informacion={$id}"
        ));
$art = GestionProspeccionRp::model()->findAll($criteria4);
foreach ($art as $c) {
    if ($c['preg1'] == 1) {
        ?>
        <div class="col-md-8"><h3>No estoy interesado</h3></div>
        <?php
    }
    if ($c['preg1'] == 2) {
        ?>
        <div class="col-md-8"><h3>Falta de dinero</h3></div>
        <?php
    }
    if ($c['preg1'] == 3) { // COMPRO OTRO VEHICULO
        $params = explode('@', $c['preg3_sec3']);
        //print_r($params);
        $modelo_auto = $params[1] . ' ' . $params[2];
        ?>
        <div class="col-md-8"><h3>Compró otro vehículo</h3></div>
        <div class="col-md-4">
            <p><strong>Marca:</strong> <?php echo $c['preg3_sec2']; ?> </p>
        </div>
        <div class="col-md-4">
            <p><strong>Modelo:</strong> <?php echo $modelo_auto; ?></p>
        </div>
        <div class="col-md-4">
            <p><strong>Año:</strong> <?php echo $c['preg3_sec4']; ?></p>
        </div>
    <?php } if ($c['preg1'] == 4) { // SI ESTOY INTERESADO ?>
        <div class="col-md-8"><h3>Si estoy interesado</h3></div>
        <div class="col-md-8">
            <p><strong>Fecha de agendamiento:</strong> <?php echo $c['preg4_sec1']; ?></p>
        </div>
        <div class="col-md-8">
            <?php if ($c['preg4_sec2'] == 0) { // SI LA CITA ES EN EL CONCESIONARIO  ?>
                <p><strong>Lugar de encuentro: </strong><?php echo $this->getConcesionario($c['preg4_sec4']); ?></p>
            </div>
        <?php } else { // ELSE ES LUGAR DE TRABAJO O DIRECCION ?>
            <p><strong>Lugar de encuentro: </strong><?php echo $c['preg4_sec5']; ?></p>
        <?php
        }
    }
    if ($c['preg1'] == 5) { // NO CONTESTA 
        ?>
        <div class="col-md-8"><h3>No contesta</h3></div>
        <div class="col-md-8">
            <p><strong>Re agendar llamada :</strong> <?php echo $c['preg5_sec1']; ?></p>
        </div>
        <?php
    }
    if ($c['preg1'] == 6) { // TELEFONO EQUIVOCADO 
        ?>
        <div class="col-md-8"><h3>Teléfono equivocado</h3></div>
        <?php
    }
}// end foreach

