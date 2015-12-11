<?php
/* @var $this GestionPresentacionController */
/* @var $data GestionPresentacion */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('preg1_duda')); ?>:</b>
	<?php echo CHtml::encode($data->preg1_duda); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('preg2_necesidades')); ?>:</b>
	<?php echo CHtml::encode($data->preg2_necesidades); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('preg3_satisfecho')); ?>:</b>
	<?php echo CHtml::encode($data->preg3_satisfecho); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('preg1_sec1_duda')); ?>:</b>
	<?php echo CHtml::encode($data->preg1_sec1_duda); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('preg2_sec1_necesidades')); ?>:</b>
	<?php echo CHtml::encode($data->preg2_sec1_necesidades); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha')); ?>:</b>
	<?php echo CHtml::encode($data->fecha); ?>
	<br />


</div>