<?php
/* @var $this GestionSolicitudCreditoController */
/* @var $model GestionSolicitudCredito */

$this->breadcrumbs=array(
	'Gestion Solicitud Creditos'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionSolicitudCredito', 'url'=>array('index')),
	array('label'=>'Create GestionSolicitudCredito', 'url'=>array('create')),
	array('label'=>'View GestionSolicitudCredito', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionSolicitudCredito', 'url'=>array('admin')),
);
?>

<h1>Update GestionSolicitudCredito <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>