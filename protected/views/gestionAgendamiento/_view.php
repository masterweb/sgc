<?php
/* @var $this GestionAgendamientoController */
/* @var $data GestionAgendamiento */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_informacion')); ?>:</b>
	<?php echo CHtml::encode($data->id_informacion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('paso')); ?>:</b>
	<?php echo CHtml::encode($data->paso); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('agendamiento')); ?>:</b>
	<?php echo CHtml::encode($data->agendamiento); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('observaciones')); ?>:</b>
	<?php echo CHtml::encode($data->observaciones); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha')); ?>:</b>
	<?php echo CHtml::encode($data->fecha); ?>
	<br />


</div>