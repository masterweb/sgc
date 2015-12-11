<?php
/* @var $this GestionHojaEntregaSolicitudController */
/* @var $model GestionHojaEntregaSolicitud */

$this->breadcrumbs=array(
	'Gestion Hoja Entrega Solicituds'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionHojaEntregaSolicitud', 'url'=>array('index')),
	array('label'=>'Manage GestionHojaEntregaSolicitud', 'url'=>array('admin')),
);
?>

<h1>Create GestionHojaEntregaSolicitud</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>