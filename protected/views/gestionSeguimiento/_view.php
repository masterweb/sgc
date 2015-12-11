<?php
/* @var $this GestionSeguimientoController */
/* @var $data GestionSeguimiento */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('concesionario')); ?>:</b>
	<?php echo CHtml::encode($data->concesionario); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('asesor')); ?>:</b>
	<?php echo CHtml::encode($data->asesor); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha')); ?>:</b>
	<?php echo CHtml::encode($data->fecha); ?>
	<br />


</div>