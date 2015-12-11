<?php
/* @var $this GestionFinanciamientoController */
/* @var $data GestionFinanciamiento */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_informacion')); ?>:</b>
	<?php echo CHtml::encode($data->id_informacion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cuota_inicial')); ?>:</b>
	<?php echo CHtml::encode($data->cuota_inicial); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('saldo_financiar')); ?>:</b>
	<?php echo CHtml::encode($data->saldo_financiar); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tarjeta_credito')); ?>:</b>
	<?php echo CHtml::encode($data->tarjeta_credito); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('otro')); ?>:</b>
	<?php echo CHtml::encode($data->otro); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('plazos')); ?>:</b>
	<?php echo CHtml::encode($data->plazos); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('cuota_mensual')); ?>:</b>
	<?php echo CHtml::encode($data->cuota_mensual); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('avaluo')); ?>:</b>
	<?php echo CHtml::encode($data->avaluo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('observaciones')); ?>:</b>
	<?php echo CHtml::encode($data->observaciones); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha')); ?>:</b>
	<?php echo CHtml::encode($data->fecha); ?>
	<br />

	*/ ?>

</div>