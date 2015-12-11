<?php
/* @var $this CpreguntaController */
/* @var $data Cpregunta */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('descripcion')); ?>:</b>
	<?php echo CHtml::encode($data->descripcion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha')); ?>:</b>
	<?php echo CHtml::encode($data->fecha); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('estado')); ?>:</b>
	<?php echo CHtml::encode($data->estado); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ctipopregunta_id')); ?>:</b>
	<?php echo CHtml::encode($data->ctipopregunta_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cquestionario_id')); ?>:</b>
	<?php echo CHtml::encode($data->cquestionario_id); ?>
	<br />


</div>