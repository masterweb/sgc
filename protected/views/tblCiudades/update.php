<?php
/* @var $this TblCiudadesController */
/* @var $model TblCiudades */

$this->breadcrumbs=array(
	'Tbl Ciudades'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List TblCiudades', 'url'=>array('index')),
	array('label'=>'Create TblCiudades', 'url'=>array('create')),
	array('label'=>'View TblCiudades', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage TblCiudades', 'url'=>array('admin')),
);
?>

<h1>Update TblCiudades <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>