<?php
/* @var $this PvvinMotorController */
/* @var $data VinMotor */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vin')); ?>:</b>
	<?php echo CHtml::encode($data->vin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('motor')); ?>:</b>
	<?php echo CHtml::encode($data->motor); ?>
	<br />


</div>