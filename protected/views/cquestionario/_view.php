<?php
/* @var $this CquestionarioController */
/* @var $data Cquestionario */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nombre')); ?>:</b>
	<?php echo CHtml::encode($data->nombre); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('descripcion')); ?>:</b>
	<?php echo CHtml::encode($data->descripcion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fechainicio')); ?>:</b>
	<?php echo CHtml::encode($data->fechainicio); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fechafin')); ?>:</b>
	<?php echo CHtml::encode($data->fechafin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha')); ?>:</b>
	<?php echo CHtml::encode($data->fecha); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('estado')); ?>:</b>
	<?php echo CHtml::encode($data->estado); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('ccampana_id')); ?>:</b>
	<?php echo CHtml::encode($data->ccampana_id); ?>
	<br />

	*/ ?>

</div>