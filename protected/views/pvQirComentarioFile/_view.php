<?php
/* @var $this PvQirComentarioFileController */
/* @var $data QirComentarioFile */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('qirComentarioId')); ?>:</b>
	<?php echo CHtml::encode($data->qirComentarioId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nombre_file')); ?>:</b>
	<?php echo CHtml::encode($data->nombre_file); ?>
	<br />


</div>