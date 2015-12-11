<?php
/* @var $this CbasedatosController */
/* @var $model Cbasedatos */

$this->breadcrumbs=array(
	'Cbasedatoses'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Cbasedatos', 'url'=>array('index')),
	array('label'=>'Manage Cbasedatos', 'url'=>array('admin')),
);
?>

<h1>Create Cbasedatos</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>