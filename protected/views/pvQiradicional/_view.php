<?php
/* @var $this PvQiradicionalController */
/* @var $data Qiradicional */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('qirId')); ?>:</b>
	<?php echo CHtml::encode($data->qirId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vin')); ?>:</b>
	<?php echo CHtml::encode($data->vin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('num_motor')); ?>:</b>
	<?php echo CHtml::encode($data->num_motor); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kilometraje')); ?>:</b>
	<?php echo CHtml::encode($data->kilometraje); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('num_reporte')); ?>:</b>
	<?php echo CHtml::encode($data->num_reporte); ?>
	<br />


</div>