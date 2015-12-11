<?php
/* @var $this GestionDiariaController */
/* @var $data GestionDiaria */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_informacion')); ?>:</b>
	<?php echo CHtml::encode($data->id_informacion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('observaciones')); ?>:</b>
	<?php echo CHtml::encode($data->observaciones); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('medio_contacto')); ?>:</b>
	<?php echo CHtml::encode($data->medio_contacto); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fuente_contacto')); ?>:</b>
	<?php echo CHtml::encode($data->fuente_contacto); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('codigo_vehiculo')); ?>:</b>
	<?php echo CHtml::encode($data->codigo_vehiculo); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('proximo_seguimiento')); ?>:</b>
	<?php echo CHtml::encode($data->proximo_seguimiento); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('categoria')); ?>:</b>
	<?php echo CHtml::encode($data->categoria); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('test_drive')); ?>:</b>
	<?php echo CHtml::encode($data->test_drive); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('exonerado')); ?>:</b>
	<?php echo CHtml::encode($data->exonerado); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('venta_concretada')); ?>:</b>
	<?php echo CHtml::encode($data->venta_concretada); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('forma_pago')); ?>:</b>
	<?php echo CHtml::encode($data->forma_pago); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('chasis')); ?>:</b>
	<?php echo CHtml::encode($data->chasis); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha_venta')); ?>:</b>
	<?php echo CHtml::encode($data->fecha_venta); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha')); ?>:</b>
	<?php echo CHtml::encode($data->fecha); ?>
	<br />

	*/ ?>

</div>