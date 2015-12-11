<?php
/* @var $this PvQirfilesController */
/* @var $data Qirfiles */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('qirId')); ?>:</b>
	<?php echo CHtml::encode($data->qirId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nombre')); ?>:</b>
	<?php echo CHtml::encode($data->nombre); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('num_reporte')); ?>:</b>
	<?php echo CHtml::encode($data->num_reporte); ?>
	<br />


</div>