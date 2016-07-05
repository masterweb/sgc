<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilos.css" type="text/css" />

<style>
    table.detail-view td {        
        width: 500px
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-8" >
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="info">
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
            <?php else: ?>
                <h1 class="tl_seccion">View Qir #<?php echo $model->id; ?></h1>

                <?php
                $this->widget('zii.widgets.CDetailView', array(
                    'data' => $model,
                    'attributes' => array(
                        'id',
                        'dealer.name:raw:Concesionario',
                        'num_reporte',
                        'fecha_registro',
                        'modeloPostVenta.descripcion:raw:Modelo',
                        'num_vehiculos_afectados',
                        'prioridad',
                        'parte_defectuosa',
                        'vin',
                        'num_motor',
                        'transmision',
                        'num_parte_casual',
                        'detalle_parte_casual',
                        'codigo_naturaleza',
                        'codigo_casual',
                        'fecha_garantia',
                        'fecha_fabricacion',
                        'kilometraje',
                        'fecha_reparacion',
                        'titular',
                        array(
                            'name' => 'descripcion',
                            'label' => 'Descripcion',
                            'value' => $model->descripcion,
                            'type' => 'raw',
                        ),
                        'ingresado',
                        'email',
                        'circunstancia',
                        'periodo_tiempo',
                        'rango_velocidad',
                        'localizacion',
                        'fase_manejo',
                        'condicion_camino',
                        'etc',
                        'vin_adicional',
                        'num_motor_adi',
                        'kilometraje_adic',
                        'estado',
                    ),
                    
                ));
                ?>
            <?php endif; ?>
            <?php
            //$cont = 0;
            if (!empty($modelFiles)) {?>
            <br><br><br><br><br><br><br><br><br><br><br><br><br>
            <h1 class="tl_seccion">Adjuntos</h1>
            <table id="yw0" class="detail-view">
                <tbody>
                    <?php
                    $cont = 0;
                    //if (!empty($modelFiles)) {
                        foreach ($modelFiles as $file) {
                            $cont ++;
                            ?>
                            <tr class="odd">
                                <th><?php echo $cont ?></th>
                                <td><?php echo CHtml::link($file->nombre, Yii::app()->request->baseUrl . '/upload/qirfiles/' . $file->nombre, array('target' => '_blank')) ?></td>
                            </tr>
                            <?php
                        }
						//}
                    ?>

                </tbody>
            </table>   
			<?php } ?>
            <?php
            if (!empty($modelAdicionales)) {?> 
            <br><br>
            <h1 class="tl_seccion">Vin Adicional</h1>
            <table id="yw0" class="detail-view">
                <thead>
                    <tr>
                        <th>VIN</th>
                        <th>N&uacute;mero de Motor</th>
                        <th>Kilometraje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    //if (!empty($modelAdicionales)) {
                        foreach ($modelAdicionales as $adicional) {
                            ?>
                            <tr class="odd">
                                <th><?php echo $adicional->vin ?></th>
                                <th><?php echo $adicional->num_motor ?></th>
                                <th><?php echo $adicional->kilometraje ?></th>
                            </tr>
                            <?php
                        }
                   // }
                    ?>

                </tbody>
            </table>    
			<?php }
			?>
            <?php
            if (!empty($modelComentario)) {?>
            <br><br>
            <h1 class="tl_seccion">Comentarios</h1>
            <table id="yw0" class="detail-view">
                <thead>
                    <tr>
                        <th>Ingresado Por</th>
                        <th>Titular</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    //if (!empty($modelComentario)) {
                        foreach ($modelComentario as $comentario) {
                            ?>
                            <tr class="odd">
                                <td><?php echo $comentario->de ?></td>
                                <td><?php echo $comentario->asunto ?></td>
                                <td style="width: 12%;text-align: right;"><?php echo $comentario->fecha ?></td>
                            </tr>
                            <?php
                        }
						//}
                    ?>

                </tbody>
            </table>
			<?php } ?>
        </div>
    </div>
</div>