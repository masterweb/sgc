<?php
/* @var $this GestionVehiculoController */
/* @var $data GestionVehiculo */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_informacion')); ?>:</b>
	<?php echo CHtml::encode($data->id_informacion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modelo')); ?>:</b>
	<?php echo CHtml::encode($data->modelo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('precio')); ?>:</b>
	<?php echo CHtml::encode($data->precio); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dispositivo')); ?>:</b>
	<?php echo CHtml::encode($data->dispositivo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('accesorios')); ?>:</b>
	<?php echo CHtml::encode($data->accesorios); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('seguro')); ?>:</b>
	<?php echo CHtml::encode($data->seguro); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('plazo')); ?>:</b>
	<?php echo CHtml::encode($data->plazo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('total')); ?>:</b>
	<?php echo CHtml::encode($data->total); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('forma_pago')); ?>:</b>
	<?php echo CHtml::encode($data->forma_pago); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha')); ?>:</b>
	<?php echo CHtml::encode($data->fecha); ?>
	<br />

	*/ ?>

</div>