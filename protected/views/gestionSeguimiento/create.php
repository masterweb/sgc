<?php
/* @var $this GestionSeguimientoController */
/* @var $model GestionSeguimiento */

$this->breadcrumbs=array(
	'Gestion Seguimientos'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionSeguimiento', 'url'=>array('index')),
	array('label'=>'Manage GestionSeguimiento', 'url'=>array('admin')),
);
?>
<div class="container">
    <?php echo $this->renderPartial('_form', array('model'=>$model,'id_informacion' => $id_informacion)); ?>
</div>

