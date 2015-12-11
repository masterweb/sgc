<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilos.css" type="text/css" />
<?php
/* @var $this CasosController */
/* @var $model Casos */

$this->breadcrumbs = array(
    'Casoses' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'List Casos', 'url' => array('index')),
    array('label' => 'Manage Casos', 'url' => array('admin')),
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
            <h1 class="tl_seccion">Bolet&iacute;n</h1>
			<div class="row">
				<div class="table-responsive">
					<table class="tables tablesorter cabeceraTabla" id="keywords">
						<thead>
							<tr>
								<th><span>ID</span></th>
								<th><span>Titular</span></th>
								<th><span>C&oacute;digo</span></th>
								<th><span>Fecha</span></th>
							</tr>
						</thead>
						<tbody>
							<tr>
									<td><?php echo $boletin->id; ?> </td>
									<td><?php echo $boletin->titulo ?> </td>
									<td><?php echo $boletin->codigo ?> </td>
									<td><?php echo $boletin->fecha ?> </td>
									
							</tr>
						</tbody>
					</table>
				</div>

			</div>
			<h2 class="tl_seccion">Adjuntos</h2>
			<div style="display:none">
			<?php $this->widget('zii.widgets.CDetailView', array(
				'data'=>$model,
				'attributes'=>array(
					'id',
					'titulo',
					'codigo',
					'contenido',
					'fecha',
					'publicar',
				),
			)); ?>
			</div>
			<table id="yw0" class="detail-view"><tbody><tr class="odd"><th>ID</th><td><?php echo $model->id?></td></tr>
			<tr class="even"><th>Detalle</th><td><?php echo $model->detalle?></td></tr>
			<tr class="odd"><th>Nombre</th><td><a href="<?php echo Yii::app()->request->baseUrl; ?>/upload/boletines/<?php echo $model->nombre ?>" target="_blank"><?php $ext = explode('.',$model->nombre); echo $model->detalle.'.'.$ext[1] ?></a></td></tr>
			
			</tbody></table>
 <?php endif; ?>
        </div>
       <div class="col-md-3 col-md-offset-1 cont_der">
           
            <p class="border-bt">Tambi&eacute;n puedes ir a:</p>
            <ul>
                <li><a href="<?php echo Yii::app()->createUrl('pvboletinpostventa/adminAdjunto/'.$id); ?>" class="seguimiento-btn">Ir a documentos adjuntos del Bolet&iacute;n</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></li>
				<li><a href="javascript:history.back(1)" class="back-btn-go">Volver</a></li>
            </ul>
        </div>
    </div>
</div>

