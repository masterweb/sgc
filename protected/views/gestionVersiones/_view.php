<?php
/* @var $this GestionVersionesController */
/* @var $data GestionVersiones */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_versiones')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_versiones), array('view', 'id'=>$data->id_versiones)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_modelos')); ?>:</b>
	<?php echo CHtml::encode($data->id_modelos); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_categoria')); ?>:</b>
	<?php echo CHtml::encode($data->id_categoria); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nombre_version')); ?>:</b>
	<?php echo CHtml::encode($data->nombre_version); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('precio')); ?>:</b>
	<?php echo CHtml::encode($data->precio); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('orden')); ?>:</b>
	<?php echo CHtml::encode($data->orden); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pdf')); ?>:</b>
	<?php echo CHtml::encode($data->pdf); ?>
	<br />


</div>