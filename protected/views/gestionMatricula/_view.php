<?php
/* @var $this GestionMatriculaController */
/* @var $data GestionMatricula */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('factura_ingreso')); ?>:</b>
	<?php echo CHtml::encode($data->factura_ingreso); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('envio_factura')); ?>:</b>
	<?php echo CHtml::encode($data->envio_factura); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pago_consejo')); ?>:</b>
	<?php echo CHtml::encode($data->pago_consejo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('venta_credito')); ?>:</b>
	<?php echo CHtml::encode($data->venta_credito); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('entrega_documentos_gestor')); ?>:</b>
	<?php echo CHtml::encode($data->entrega_documentos_gestor); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ente_regulador_placa')); ?>:</b>
	<?php echo CHtml::encode($data->ente_regulador_placa); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('vehiculo_matricula_placas')); ?>:</b>
	<?php echo CHtml::encode($data->vehiculo_matricula_placas); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_informacion')); ?>:</b>
	<?php echo CHtml::encode($data->id_informacion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_vehiculo')); ?>:</b>
	<?php echo CHtml::encode($data->id_vehiculo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha')); ?>:</b>
	<?php echo CHtml::encode($data->fecha); ?>
	<br />

	*/ ?>

</div>