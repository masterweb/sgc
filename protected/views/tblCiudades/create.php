<?php
/* @var $this TblCiudadesController */
/* @var $model TblCiudades */

$this->breadcrumbs=array(
	'Tbl Ciudades'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List TblCiudades', 'url'=>array('index')),
	array('label'=>'Manage TblCiudades', 'url'=>array('admin')),
);
?>

<h1>Create TblCiudades</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>