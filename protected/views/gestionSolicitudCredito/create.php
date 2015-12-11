<?php
/* @var $this GestionSolicitudCreditoController */
/* @var $model GestionSolicitudCredito */

$this->breadcrumbs=array(
	'Gestion Solicitud Creditos'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionSolicitudCredito', 'url'=>array('index')),
	array('label'=>'Manage GestionSolicitudCredito', 'url'=>array('admin')),
);
?>


<?php echo $this->renderPartial('_form', array('model'=>$model,'id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo)); ?>