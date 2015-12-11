<?php
/* @var $this CencuestadoscquestionarioController */
/* @var $data Cencuestadoscquestionario */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cencuestados_id')); ?>:</b>
	<?php echo CHtml::encode($data->cencuestados_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cquestionario_id')); ?>:</b>
	<?php echo CHtml::encode($data->cquestionario_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('usuarios_id')); ?>:</b>
	<?php echo CHtml::encode($data->usuarios_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('audio')); ?>:</b>
	<?php echo CHtml::encode($data->audio); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tiempoinicio')); ?>:</b>
	<?php echo CHtml::encode($data->tiempoinicio); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tiempofinal')); ?>:</b>
	<?php echo CHtml::encode($data->tiempofinal); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('estado')); ?>:</b>
	<?php echo CHtml::encode($data->estado); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('observaciones')); ?>:</b>
	<?php echo CHtml::encode($data->observaciones); ?>
	<br />

	*/ ?>

</div>