<?php
/* @var $this GestionAmortizacionController */
/* @var $data GestionAmortizacion */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('interes')); ?>:</b>
	<?php echo CHtml::encode($data->interes); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('capital_reducido')); ?>:</b>
	<?php echo CHtml::encode($data->capital_reducido); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('capital')); ?>:</b>
	<?php echo CHtml::encode($data->capital); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('seguro_desgravamen')); ?>:</b>
	<?php echo CHtml::encode($data->seguro_desgravamen); ?>
	<br />


</div>