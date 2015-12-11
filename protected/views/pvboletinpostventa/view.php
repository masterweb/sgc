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
            <h1 class="tl_seccion">Ver Bolet&iacute;n</h1>
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
	<tr class="even"><th>Titulo</th><td><?php echo $model->titulo?></td></tr>
	<tr class="odd"><th>Codigo</th><td><?php echo $model->codigo?></td></tr>
	<tr class="even"><th>Contenido</th><td><?php echo $model->contenido?></td></tr>
	<tr class="odd"><th>Fecha</th><td><?php echo $model->fecha?></td></tr>
	<tr class="even"><th>Publicar</th><td><?php echo $model->publicar?></td></tr>
	<tr class="even"><th>Modelos</th><td>
		<?php
			foreach($modelos as $item){
				echo '<p>'.$item->modelosposventa->descripcion.'</p>';
			}
		?>
	</td></tr>
	</tbody></table>
	<?php if(!empty($adjuntos)){?>
	<br>
			<div class="row">
				<div class="table-responsive">
					<table class="tables tablesorter cabeceraTabla" id="keywords">
						<thead>
							<tr>
								<th><span>ID</span></th>
								<th><span>Detalle</span></th>
								<th><span>Nombre</span></th>
							</tr>
						</thead>
						<tbody>
						<?php foreach($adjuntos as $boletn){?>
							<tr>
								
									<td><?php echo $boletn->id; ?> </td>
									<td><?php echo $boletn->detalle; ?></td>
									<!--<td><a href="<?php //echo Yii::app()->request->baseUrl; ?>/upload/boletines/<?php //echo $boletn->nombre ?>" target="_blank"><?php //$ext = explode('.',$boletn->nombre); echo $boletn->detalle.'.'.$ext[1] ?></a></td>-->
									<td><a href="<?php echo Yii::app()->request->baseUrl; ?>/upload/boletines/<?php echo $boletn->nombre ?>" target="_blank"><?php echo $boletn->nombre ?></a></td>
									
									
							</tr>
						<?php } ?>
						</tbody>
					</table>
				</div>

			</div>
			<?php }else
					echo 'No existen documentos adjuntos para este bolet&iacute;n.';
			?>
	 <?php endif; ?>
        </div>
        <div class="col-md-3 col-md-offset-1 cont_der">
           
            <p class="border-bt">Tambi&eacute;n puedes ir a:</p>
            <ul>
                <li><a href="<?php echo Yii::app()->createUrl('pvboletinpostventa/admin'); ?>" class="seguimiento-btn">Administrador de Boletines</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></li>
				<li><a href="javascript:history.back(1)" class="back-btn-go">Volver</a></li>
            </ul>
        </div>
    </div>
</div>

