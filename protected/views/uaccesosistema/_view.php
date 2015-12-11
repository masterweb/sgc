<?php
/* @var $this UaccesosistemaController */
/* @var $data Accesosistema */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('controlador')); ?>:</b>
	<?php echo CHtml::encode($data->controlador); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('accion')); ?>:</b>
	<?php echo CHtml::encode($data->accion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('descricion')); ?>:</b>
	<?php echo CHtml::encode($data->descricion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('estado')); ?>:</b>
	<?php echo CHtml::encode($data->estado); ?>
	<br />


</div>