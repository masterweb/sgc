<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilos.css" type="text/css" />

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/assets/fancybox/source/jquery.fancybox.pack.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl ?>/assets/fancybox/source/jquery.fancybox.css" type="text/css" media="screen" />
<?php
/* @var $this PvQirController */
/* @var $model Qir */

$this->breadcrumbs = array(
    'Qirs' => array('index'),
    $model->id,
);

$this->menu = array(
    array('label' => 'List Qir', 'url' => array('index')),
    array('label' => 'Manage Qir', 'url' => array('admin')),
);
?>

<div class="container">
    <div class="row">
        <div class="col-md-8">
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
						'descripcion',
						'analisis',
						'investigacion',
						'acciones',
						'comentarios',
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
            <br>
            <h1 class="tl_seccion">Adjuntos</h1>
            <table id="yw0" class="detail-view">
                <tbody>
                    <?php
                    $cont = 0;
                    if (!empty($modelFiles)) {
                        foreach ($modelFiles as $file) {
                            $cont ++;
                            ?>
                            <tr class="odd">
                                <th><?php echo $cont ?></th>
                                <td><?php echo CHtml::link($file->nombre, Yii::app()->request->baseUrl . '/upload/qirfiles/' . $file->nombre, array('target' => '_blank')) ?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>

                </tbody>
            </table>    
            <br>
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
                    if (!empty($modelAdicionales)) {
                        foreach ($modelAdicionales as $adicional) {
                            ?>
                            <tr class="odd">
                                <th><?php echo $adicional->vin ?></th>
                                <th><?php echo $adicional->num_motor ?></th>
                                <th><?php echo $adicional->kilometraje ?></th>
                            </tr>
                            <?php
                        }
                    }
                    ?>

                </tbody>
            </table>    
            <br>
            <h1 class="tl_seccion">Comentarios</h1>
            <table id="yw0" class="detail-view">
                <thead>
                    <tr>
                        <th>Ingresado Por</th>
                        <th>Titular</th>
                        <th>Fecha</th>
                        <th>Acci&oacute;n</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    
                    Yii::app()->user->setState('modal','fancybox');
                    
                    if (!empty($modelComentario)) {
                        foreach ($modelComentario as $comentario) {
                            ?>
                            <tr class="odd">
                                <td><?php echo $comentario->de ?></td>
                                <td><?php echo $comentario->asunto ?></td>
                                <td style="width: 12%;text-align: right;"><?php echo $comentario->fecha ?></td>
                                <td style="width: 12%;text-align: center;">
                                    <?php echo CHtml::link('<img width="20px" src="' . Yii::app()->request->baseUrl . '/upload/edit.png" />', array('pvQircomentario/view', 'id' => $comentario->id), array('class'=>'fancybox fancybox.iframe')) ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>

                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align: right">
                            <?php echo CHtml::link('<img width="20px" src="' . Yii::app()->request->baseUrl . '/upload/create.png" />', array('pvQircomentario/create', 'id' => $model->id), array('class'=>'fancybox fancybox.iframe')) ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="col-md-3 col-md-offset-1 cont_der">

            <p class="border-bt">Tambi&eacute;n puedes ir a:</p>
            <ul>
                <li><a href="<?php echo Yii::app()->createUrl('pvQir/admin'); ?>" class="seguimiento-btn">Administrador de QIR</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('pvQir/qirViewPDF/' . $model->id); ?>" class="seguimiento-btn" target="_blank">Exportar PDF</a></li>
				<li><a href="<?php echo Yii::app()->createUrl('pvQir/qirvExcel/' . $model->id); ?>" class="seguimiento-btn">Exportar Excel</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></li>
				<li><a href="javascript:history.back(1)" class="back-btn-go">Volver</a></li>
            </ul>
        </div>
    </div>
</div>


<script type="text/javascript">

    $(".fancybox").fancybox({
        'transitionIn': 'elastic',
        'transitionOut': 'elastic',
        'speedIn': 600,
        'speedOut': 200,
        'overlayShow': false
    });
    
    function cerrarFancy(){
        $.fancybox.close();
        location.href = "<?php echo Yii::app()->createAbsoluteUrl('pvQir/view/'.$model->id)?>"
    }

</script>
