<?php
/* @var $this GestionPresentacionController */
/* @var $model GestionPresentacion */

$this->breadcrumbs=array(
	'Gestion Presentacions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionPresentacion', 'url'=>array('index')),
	array('label'=>'Manage GestionPresentacion', 'url'=>array('admin')),
);
?>

<div class="container">

<?php echo $this->renderPartial('_form', array('model'=>$model,'id_informacion' => $id_informacion,'id_vehiculo' => $id_vehiculo)); ?>
</div>