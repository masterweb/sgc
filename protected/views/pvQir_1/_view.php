<?php
/* @var $this PvQirController */
/* @var $data Qir */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dealerId')); ?>:</b>
	<?php echo CHtml::encode($data->dealerId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('num_reporte')); ?>:</b>
	<?php echo CHtml::encode($data->num_reporte); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha_registro')); ?>:</b>
	<?php echo CHtml::encode($data->fecha_registro); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modeloPostVentaId')); ?>:</b>
	<?php echo CHtml::encode($data->modeloPostVentaId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('num_vehiculos_afectados')); ?>:</b>
	<?php echo CHtml::encode($data->num_vehiculos_afectados); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('prioridad')); ?>:</b>
	<?php echo CHtml::encode($data->prioridad); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('parte_defectuosa')); ?>:</b>
	<?php echo CHtml::encode($data->parte_defectuosa); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vin')); ?>:</b>
	<?php echo CHtml::encode($data->vin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('num_motor')); ?>:</b>
	<?php echo CHtml::encode($data->num_motor); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('transmision')); ?>:</b>
	<?php echo CHtml::encode($data->transmision); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('num_parte_casual')); ?>:</b>
	<?php echo CHtml::encode($data->num_parte_casual); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('detalle_parte_casual')); ?>:</b>
	<?php echo CHtml::encode($data->detalle_parte_casual); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('codigo_naturaleza')); ?>:</b>
	<?php echo CHtml::encode($data->codigo_naturaleza); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('codigo_casual')); ?>:</b>
	<?php echo CHtml::encode($data->codigo_casual); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha_garantia')); ?>:</b>
	<?php echo CHtml::encode($data->fecha_garantia); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha_fabricacion')); ?>:</b>
	<?php echo CHtml::encode($data->fecha_fabricacion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kilometraje')); ?>:</b>
	<?php echo CHtml::encode($data->kilometraje); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha_reparacion')); ?>:</b>
	<?php echo CHtml::encode($data->fecha_reparacion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('titular')); ?>:</b>
	<?php echo CHtml::encode($data->titular); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('descripcion')); ?>:</b>
	<?php echo CHtml::encode($data->descripcion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ingresado')); ?>:</b>
	<?php echo CHtml::encode($data->ingresado); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('circunstancia')); ?>:</b>
	<?php echo CHtml::encode($data->circunstancia); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('periodo_tiempo')); ?>:</b>
	<?php echo CHtml::encode($data->periodo_tiempo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rango_velocidad')); ?>:</b>
	<?php echo CHtml::encode($data->rango_velocidad); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('localizacion')); ?>:</b>
	<?php echo CHtml::encode($data->localizacion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fase_manejo')); ?>:</b>
	<?php echo CHtml::encode($data->fase_manejo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('condicion_camino')); ?>:</b>
	<?php echo CHtml::encode($data->condicion_camino); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('etc')); ?>:</b>
	<?php echo CHtml::encode($data->etc); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vin_adicional')); ?>:</b>
	<?php echo CHtml::encode($data->vin_adicional); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('num_motor_adi')); ?>:</b>
	<?php echo CHtml::encode($data->num_motor_adi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kilometraje_adic')); ?>:</b>
	<?php echo CHtml::encode($data->kilometraje_adic); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('estado')); ?>:</b>
	<?php echo CHtml::encode($data->estado); ?>
	<br />

	*/ ?>

</div>