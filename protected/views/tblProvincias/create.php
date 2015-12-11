<?php
/* @var $this TblProvinciasController */
/* @var $model TblProvincias */

$this->breadcrumbs=array(
	'Tbl Provinciases'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List TblProvincias', 'url'=>array('index')),
	array('label'=>'Manage TblProvincias', 'url'=>array('admin')),
);
?>

<h1>Create TblProvincias</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>