<?php
/* @var $this GestionFirmaController */
/* @var $data GestionFirma */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_informacion')); ?>:</b>
	<?php echo CHtml::encode($data->id_informacion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('firma')); ?>:</b>
	<?php echo CHtml::encode($data->firma); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha')); ?>:</b>
	<?php echo CHtml::encode($data->fecha); ?>
	<br />


</div>