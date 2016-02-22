<?php
/* @var $this GestionPasoEntregaController */
/* @var $model GestionPasoEntrega */

$this->breadcrumbs=array(
	'Gestion Paso Entregas'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionPasoEntrega', 'url'=>array('index')),
	array('label'=>'Manage GestionPasoEntrega', 'url'=>array('admin')),
);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'id_informacion' => $id_informacion,'id_vehiculo' => $id_vehiculo)); ?>