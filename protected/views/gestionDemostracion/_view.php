<?php
/* @var $this GestionDemostracionController */
/* @var $data GestionDemostracion */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('preg1')); ?>:</b>
	<?php echo CHtml::encode($data->preg1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('preg1_licencia')); ?>:</b>
	<?php echo CHtml::encode($data->preg1_licencia); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('preg1_agendamiento')); ?>:</b>
	<?php echo CHtml::encode($data->preg1_agendamiento); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_informacion')); ?>:</b>
	<?php echo CHtml::encode($data->id_informacion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha')); ?>:</b>
	<?php echo CHtml::encode($data->fecha); ?>
	<br />


</div>