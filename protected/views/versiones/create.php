<?php
/* @var $this VersionesController */
/* @var $model Versiones */

$this->breadcrumbs=array(
	'Versiones'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Versiones', 'url'=>array('index')),
	array('label'=>'Manage Versiones', 'url'=>array('admin')),
);
?>

<div class="container">
	<h1 class="tl_seccion">Crear Nueva Version </h1>
	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>

