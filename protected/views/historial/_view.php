<?php
/* @var $this HistorialController */
/* @var $data Historial */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_caso')); ?>:</b>
	<?php echo CHtml::encode($data->id_caso); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha')); ?>:</b>
	<?php echo CHtml::encode($data->fecha); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tema')); ?>:</b>
	<?php echo CHtml::encode($data->tema); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('subtema')); ?>:</b>
	<?php echo CHtml::encode($data->subtema); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('estado')); ?>:</b>
	<?php echo CHtml::encode($data->estado); ?>
	<br />


</div>