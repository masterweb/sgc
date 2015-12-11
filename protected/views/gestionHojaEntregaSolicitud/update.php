<?php
/* @var $this GestionHojaEntregaSolicitudController */
/* @var $model GestionHojaEntregaSolicitud */

$this->breadcrumbs=array(
	'Gestion Hoja Entrega Solicituds'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionHojaEntregaSolicitud', 'url'=>array('index')),
	array('label'=>'Create GestionHojaEntregaSolicitud', 'url'=>array('create')),
	array('label'=>'View GestionHojaEntregaSolicitud', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionHojaEntregaSolicitud', 'url'=>array('admin')),
);
?>

<h1>Update GestionHojaEntregaSolicitud <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>