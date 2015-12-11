<?php
/* @var $this TblCiudadesController */
/* @var $data TblCiudades */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_provincia')); ?>:</b>
	<?php echo CHtml::encode($data->id_provincia); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_ciudad')); ?>:</b>
	<?php echo CHtml::encode($data->id_ciudad); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nombre')); ?>:</b>
	<?php echo CHtml::encode($data->nombre); ?>
	<br />


</div>