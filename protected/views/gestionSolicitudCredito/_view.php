<?php
/* @var $this GestionSolicitudCreditoController */
/* @var $data GestionSolicitudCredito */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('concesionario')); ?>:</b>
	<?php echo CHtml::encode($data->concesionario); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vendedor')); ?>:</b>
	<?php echo CHtml::encode($data->vendedor); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha')); ?>:</b>
	<?php echo CHtml::encode($data->fecha); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modelo')); ?>:</b>
	<?php echo CHtml::encode($data->modelo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('valor')); ?>:</b>
	<?php echo CHtml::encode($data->valor); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('monto_financiar')); ?>:</b>
	<?php echo CHtml::encode($data->monto_financiar); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('entrada')); ?>:</b>
	<?php echo CHtml::encode($data->entrada); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('year')); ?>:</b>
	<?php echo CHtml::encode($data->year); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('plazo')); ?>:</b>
	<?php echo CHtml::encode($data->plazo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('taza')); ?>:</b>
	<?php echo CHtml::encode($data->taza); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cuota_mensual')); ?>:</b>
	<?php echo CHtml::encode($data->cuota_mensual); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('apellido_paterno')); ?>:</b>
	<?php echo CHtml::encode($data->apellido_paterno); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('apellido_materno')); ?>:</b>
	<?php echo CHtml::encode($data->apellido_materno); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nombres')); ?>:</b>
	<?php echo CHtml::encode($data->nombres); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cedula')); ?>:</b>
	<?php echo CHtml::encode($data->cedula); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha_nacimiento')); ?>:</b>
	<?php echo CHtml::encode($data->fecha_nacimiento); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nacionalidad')); ?>:</b>
	<?php echo CHtml::encode($data->nacionalidad); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('estado_civil')); ?>:</b>
	<?php echo CHtml::encode($data->estado_civil); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('empresa_trabajo')); ?>:</b>
	<?php echo CHtml::encode($data->empresa_trabajo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('telefonos_trabajo')); ?>:</b>
	<?php echo CHtml::encode($data->telefonos_trabajo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tiempo_trabajo')); ?>:</b>
	<?php echo CHtml::encode($data->tiempo_trabajo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cargo')); ?>:</b>
	<?php echo CHtml::encode($data->cargo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('direccion_empresa')); ?>:</b>
	<?php echo CHtml::encode($data->direccion_empresa); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tipo_relacion_laboral')); ?>:</b>
	<?php echo CHtml::encode($data->tipo_relacion_laboral); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('actividad_empresa')); ?>:</b>
	<?php echo CHtml::encode($data->actividad_empresa); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('apellido_paterno_conyugue')); ?>:</b>
	<?php echo CHtml::encode($data->apellido_paterno_conyugue); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('apellido_materno_conyugue')); ?>:</b>
	<?php echo CHtml::encode($data->apellido_materno_conyugue); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nombres_conyugue')); ?>:</b>
	<?php echo CHtml::encode($data->nombres_conyugue); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cedula_conyugue')); ?>:</b>
	<?php echo CHtml::encode($data->cedula_conyugue); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha_nacimiento_conyugue')); ?>:</b>
	<?php echo CHtml::encode($data->fecha_nacimiento_conyugue); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nacionalidad_conyugue')); ?>:</b>
	<?php echo CHtml::encode($data->nacionalidad_conyugue); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('empresa_trabajo_conyugue')); ?>:</b>
	<?php echo CHtml::encode($data->empresa_trabajo_conyugue); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('telefono_trabajo_conyugue')); ?>:</b>
	<?php echo CHtml::encode($data->telefono_trabajo_conyugue); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tiempo_trabajo_conyugue')); ?>:</b>
	<?php echo CHtml::encode($data->tiempo_trabajo_conyugue); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cargo_conyugue')); ?>:</b>
	<?php echo CHtml::encode($data->cargo_conyugue); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('direccion_empresa_conyugue')); ?>:</b>
	<?php echo CHtml::encode($data->direccion_empresa_conyugue); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tipo_relacion_laboral_conyugue')); ?>:</b>
	<?php echo CHtml::encode($data->tipo_relacion_laboral_conyugue); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('domicilio_actual')); ?>:</b>
	<?php echo CHtml::encode($data->domicilio_actual); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('habita')); ?>:</b>
	<?php echo CHtml::encode($data->habita); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('avaluo_propiedad')); ?>:</b>
	<?php echo CHtml::encode($data->avaluo_propiedad); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vive')); ?>:</b>
	<?php echo CHtml::encode($data->vive); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('valor_arriendo')); ?>:</b>
	<?php echo CHtml::encode($data->valor_arriendo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('calle')); ?>:</b>
	<?php echo CHtml::encode($data->calle); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('barrio')); ?>:</b>
	<?php echo CHtml::encode($data->barrio); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('referencia_domicilio')); ?>:</b>
	<?php echo CHtml::encode($data->referencia_domicilio); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('telefono_residencia')); ?>:</b>
	<?php echo CHtml::encode($data->telefono_residencia); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('celular')); ?>:</b>
	<?php echo CHtml::encode($data->celular); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sueldo_mensual')); ?>:</b>
	<?php echo CHtml::encode($data->sueldo_mensual); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sueldo_mensual_conyugue')); ?>:</b>
	<?php echo CHtml::encode($data->sueldo_mensual_conyugue); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('banco1')); ?>:</b>
	<?php echo CHtml::encode($data->banco1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cuenta1')); ?>:</b>
	<?php echo CHtml::encode($data->cuenta1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('banco2')); ?>:</b>
	<?php echo CHtml::encode($data->banco2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cuenta2')); ?>:</b>
	<?php echo CHtml::encode($data->cuenta2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('referencia_personal1')); ?>:</b>
	<?php echo CHtml::encode($data->referencia_personal1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('referencia_personal2')); ?>:</b>
	<?php echo CHtml::encode($data->referencia_personal2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('parentesco1')); ?>:</b>
	<?php echo CHtml::encode($data->parentesco1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('parentesco2')); ?>:</b>
	<?php echo CHtml::encode($data->parentesco2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('telefono_referencia1')); ?>:</b>
	<?php echo CHtml::encode($data->telefono_referencia1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('telefono_referencia2')); ?>:</b>
	<?php echo CHtml::encode($data->telefono_referencia2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('activos')); ?>:</b>
	<?php echo CHtml::encode($data->activos); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pasivos')); ?>:</b>
	<?php echo CHtml::encode($data->pasivos); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('patrimonio')); ?>:</b>
	<?php echo CHtml::encode($data->patrimonio); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_informacion')); ?>:</b>
	<?php echo CHtml::encode($data->id_informacion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_vehiculo')); ?>:</b>
	<?php echo CHtml::encode($data->id_vehiculo); ?>
	<br />

	*/ ?>

</div>