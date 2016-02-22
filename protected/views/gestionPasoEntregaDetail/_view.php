<?php
/* @var $this GestionPasoEntregaDetailController */
/* @var $data GestionPasoEntregaDetail */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_paso')); ?>:</b>
	<?php echo CHtml::encode($data->id_paso); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha_paso')); ?>:</b>
	<?php echo CHtml::encode($data->fecha_paso); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('observaciones')); ?>:</b>
	<?php echo CHtml::encode($data->observaciones); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('placa')); ?>:</b>
	<?php echo CHtml::encode($data->placa); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('responsable')); ?>:</b>
	<?php echo CHtml::encode($data->responsable); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('foto_entrega')); ?>:</b>
	<?php echo CHtml::encode($data->foto_entrega); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('foto_hoja_entrega')); ?>:</b>
	<?php echo CHtml::encode($data->foto_hoja_entrega); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha')); ?>:</b>
	<?php echo CHtml::encode($data->fecha); ?>
	<br />

	*/ ?>

</div>