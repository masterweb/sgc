<?php
/* @var $this GestionCierreController */
/* @var $data GestionCierre */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('numero_chasis')); ?>:</b>
	<?php echo CHtml::encode($data->numero_chasis); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('numero_modelo')); ?>:</b>
	<?php echo CHtml::encode($data->numero_modelo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nombre_propietario')); ?>:</b>
	<?php echo CHtml::encode($data->nombre_propietario); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('color_vehiculo')); ?>:</b>
	<?php echo CHtml::encode($data->color_vehiculo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('factura')); ?>:</b>
	<?php echo CHtml::encode($data->factura); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('concesionario')); ?>:</b>
	<?php echo CHtml::encode($data->concesionario); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha_venta')); ?>:</b>
	<?php echo CHtml::encode($data->fecha_venta); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('year')); ?>:</b>
	<?php echo CHtml::encode($data->year); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('color_origen')); ?>:</b>
	<?php echo CHtml::encode($data->color_origen); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('identificacion')); ?>:</b>
	<?php echo CHtml::encode($data->identificacion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('precio_venta')); ?>:</b>
	<?php echo CHtml::encode($data->precio_venta); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('calle_principal')); ?>:</b>
	<?php echo CHtml::encode($data->calle_principal); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('numero_calle')); ?>:</b>
	<?php echo CHtml::encode($data->numero_calle); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('telefono_propietario')); ?>:</b>
	<?php echo CHtml::encode($data->telefono_propietario); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('grupo_concesionario')); ?>:</b>
	<?php echo CHtml::encode($data->grupo_concesionario); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('forma_pago')); ?>:</b>
	<?php echo CHtml::encode($data->forma_pago); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('observacion')); ?>:</b>
	<?php echo CHtml::encode($data->observacion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha')); ?>:</b>
	<?php echo CHtml::encode($data->fecha); ?>
	<br />

	*/ ?>

</div>