<?php
/* @var $this GestionFinanciamientoOpController */
/* @var $data GestionFinanciamientoOp */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_informacion')); ?>:</b>
	<?php echo CHtml::encode($data->id_informacion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_vehiculo')); ?>:</b>
	<?php echo CHtml::encode($data->id_vehiculo); ?>
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

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('plazos')); ?>:</b>
	<?php echo CHtml::encode($data->plazos); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cuota_mensual')); ?>:</b>
	<?php echo CHtml::encode($data->cuota_mensual); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('avaluo')); ?>:</b>
	<?php echo CHtml::encode($data->avaluo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('categoria')); ?>:</b>
	<?php echo CHtml::encode($data->categoria); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha_cita')); ?>:</b>
	<?php echo CHtml::encode($data->fecha_cita); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('observaciones')); ?>:</b>
	<?php echo CHtml::encode($data->observaciones); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('precio_vehiculo')); ?>:</b>
	<?php echo CHtml::encode($data->precio_vehiculo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tasa')); ?>:</b>
	<?php echo CHtml::encode($data->tasa); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('seguro')); ?>:</b>
	<?php echo CHtml::encode($data->seguro); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('valor_financiamiento')); ?>:</b>
	<?php echo CHtml::encode($data->valor_financiamiento); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('forma_pago')); ?>:</b>
	<?php echo CHtml::encode($data->forma_pago); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('entidad_financiera')); ?>:</b>
	<?php echo CHtml::encode($data->entidad_financiera); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha')); ?>:</b>
	<?php echo CHtml::encode($data->fecha); ?>
	<br />

	*/ ?>

</div>