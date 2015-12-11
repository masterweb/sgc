<?php
/* @var $this TblProvinciasController */
/* @var $model TblProvincias */

$this->breadcrumbs=array(
	'Tbl Provinciases'=>array('index'),
	$model->id_provincia=>array('view','id'=>$model->id_provincia),
	'Update',
);

$this->menu=array(
	array('label'=>'List TblProvincias', 'url'=>array('index')),
	array('label'=>'Create TblProvincias', 'url'=>array('create')),
	array('label'=>'View TblProvincias', 'url'=>array('view', 'id'=>$model->id_provincia)),
	array('label'=>'Manage TblProvincias', 'url'=>array('admin')),
);
?>

<h1>Update TblProvincias <?php echo $model->id_provincia; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>