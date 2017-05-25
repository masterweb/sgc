<?php
/* @var $this GestionFilesController */
/* @var $model GestionFiles */
$bl = GestionFiles::model()->findAll(array('condition' => "tipo = {$tipo}"));

/*$this->breadcrumbs=array(
	'Gestion Files'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List GestionFiles', 'url'=>array('index')),
	array('label'=>'Create GestionFiles', 'url'=>array('create')),
);*/

/*Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#gestion-files-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");*/
?>
<div class="container">
	<div class="row">
        <h1 class="tl_seccion">Biblioteca</h1>
    </div>
	<div class="row">
		<div class="col-md-12">
			<div class="table-responsive">
				<table class="table tablesorter table-striped" id="keywords">
					<thead>
                        <tr>
                        	<th>Nombre</th>
                            <th><span>Año</span></th>
                            <th><span>Mes</span></th>
                            <?php  if($tipo == 2 ): ?>
                            	<th><span>Modelo</span></th>
                        	<?php endif; ?>
                            <th><span>Fecha de actualización</span></th>
                            <th>File</th>
                        </tr>
                    </thead>
                    <tbody>
                    	
	                    	<?php foreach ($bl as $c): ?>
		                    	<tr>
		                    		<td><?php echo $c['descripcion']; ?></td>
									<td><?php echo $c['year']; ?></td>
									<td><?php echo $c['mes']; ?></td>
									<?php  if($tipo == 2 ): ?>
										<td><?php echo $c['modelo']; ?></td>
									<?php endif; ?>
									<td><?php echo $c['fecha_actualizacion']; ?></td>
									<td><a href="<?php echo Yii::app()->request->baseUrl; ?>/images/uploads/libreria/<?php echo $c['nombre']; ?>" class="btn btn-primary btn-xs btn-danger" target="_blank">Descarga</a></td>
								</tr>	
	                    	<?php endforeach; ?>
                    	
                    </tbody>
				</table>	
			</div>
		</div>
	</div>


<?php // echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none"></div><!-- search-form -->

<?php //$this->renderPartial('_search',array('model'=>$model,)); ?>



</div>
