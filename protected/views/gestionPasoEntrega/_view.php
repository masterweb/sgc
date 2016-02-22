<?php
/* @var $this GestionPasoEntregaController */
/* @var $data GestionPasoEntrega */
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

	<b><?php echo CHtml::encode($data->getAttributeLabel('envio_factura')); ?>:</b>
	<?php echo CHtml::encode($data->envio_factura); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('emision_contrato')); ?>:</b>
	<?php echo CHtml::encode($data->emision_contrato); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('agendar_firma')); ?>:</b>
	<?php echo CHtml::encode($data->agendar_firma); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('alistamiento_unidad')); ?>:</b>
	<?php echo CHtml::encode($data->alistamiento_unidad); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('pago_matricula')); ?>:</b>
	<?php echo CHtml::encode($data->pago_matricula); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('recepcion_contratos')); ?>:</b>
	<?php echo CHtml::encode($data->recepcion_contratos); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('recepcion_matricula')); ?>:</b>
	<?php echo CHtml::encode($data->recepcion_matricula); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vehiculo_revisado')); ?>:</b>
	<?php echo CHtml::encode($data->vehiculo_revisado); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('entrega_vehiculo')); ?>:</b>
	<?php echo CHtml::encode($data->entrega_vehiculo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('foto_entrega')); ?>:</b>
	<?php echo CHtml::encode($data->foto_entrega); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('foto_hoja_entrega')); ?>:</b>
	<?php echo CHtml::encode($data->foto_hoja_entrega); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha')); ?>:</b>
	<?php echo CHtml::encode($data->fecha); ?>
	<br />

	*/ ?>

</div>