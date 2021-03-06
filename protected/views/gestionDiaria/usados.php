<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="highlight">
                <div class="row">
                    <h1 class="tl_seccion_rf">Datos del Cliente</h1>
                    <?php
                    $con = Yii::app()->db;
//                    $sql = "SELECT gi.id as id_info, gi. nombres, gi.apellidos, gi.cedula, gi.email, gi.direccion,gi.celular, 
//                        gi.telefono_oficina, gi.id_cotizacion, gi.responsable, gi.tipo_form_web, gi.presupuesto, gd.* FROM gestion_diaria gd 
//                                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
//                                WHERE gi.id = {$_GET['id_informacion']} GROUP BY gi.id ORDER BY gd.id_informacion DESC";
                    $sql = "SELECT * FROM gestion_informacion WHERE id = {$_GET['id_informacion']} GROUP BY id";
                    //            die($sql);
                    $request = $con->createCommand($sql);
                    $users = $request->queryAll();
                    /* echo '<pre>';
                      print_r($users);
                      echo '</pre>'; */
                    ?>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php foreach ($users as $key => $value): ?>
                            <table class="table">
                                <tr>
                                    <td><strong>Nombres:</strong> <?php echo $value['nombres']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Apellidos:</strong> <?php echo $value['apellidos']; ?></td>
                                </tr>
                                <?php if ($value['cedula'] != ''): ?>
                                    <tr>
                                        <td><strong>Cédula:</strong> <?php echo $value['cedula']; ?></td>
                                    </tr>
                                <?php endif; ?>
                                <?php if ($value['ruc'] != ''): ?>
                                    <tr>
                                        <td><strong>Ruc:</strong> <?php echo $value['ruc']; ?></td>
                                    </tr>
                                <?php endif; ?>
                                <?php if ($value['pasaporte'] != ''): ?>
                                    <tr>
                                        <td><strong>Pasaporte:</strong> <?php echo $value['pasaporte']; ?></td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td><strong>Email:</strong> <?php echo $value['email']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Dirección:</strong> <?php echo $value['direccion']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Celular:</strong> <?php echo $value['celular']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Teléfono Oficina:</strong> <?php echo $value['telefono_casa']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Fuente de Contacto:</strong> <?php echo ucfirst($this->getFuente($value['id_cotizacion'])); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Tipo Formulario:</strong> <?php echo ucfirst($value['tipo_form_web']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Presupuesto:</strong> <?php echo $value['presupuesto']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Responsable:</strong> <?php echo $this->getResponsable($value['responsable']); ?></td>
                                </tr>
                            </table>                       
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php
            $crit = new CDbCriteria(array('condition' => "id_informacion={$id_informacion} AND paso = '1-2'"));
            $agen = GestionAgendamiento::model()->count($crit);
            $ag = GestionAgendamiento::model()->findAll($crit);
            if ($agen > 0) {
                ?>
                <div class="row">
                    <div class="col-md-8"><h4 class="tl-agen">Agendamientos</h4>
                        <ul class="list-group">
                            <?php foreach ($ag as $a) { ?>
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php if ($a['observaciones'] == 'Otro'): ?>
                                                <strong>Observación: </strong><?php echo $a['otro_observacion']; ?>
                                            <?php else: ?>
                                                <strong>Fecha Agendamiento: </strong><?php echo $a['agendamiento']; ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6"><strong>Motivo: </strong><?php echo $a['observaciones']; ?></div>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>
</div>

