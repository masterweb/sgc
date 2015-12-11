<?php
/* @var $this GestionSolicitudCreditoController */
/* @var $model GestionSolicitudCredito */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'concesionario'); ?>
		<?php echo $form->textField($model,'concesionario'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'vendedor'); ?>
		<?php echo $form->textField($model,'vendedor'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fecha'); ?>
		<?php echo $form->textField($model,'fecha'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'modelo'); ?>
		<?php echo $form->textField($model,'modelo'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'valor'); ?>
		<?php echo $form->textField($model,'valor'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'monto_financiar'); ?>
		<?php echo $form->textField($model,'monto_financiar'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'entrada'); ?>
		<?php echo $form->textField($model,'entrada'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'year'); ?>
		<?php echo $form->textField($model,'year'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'plazo'); ?>
		<?php echo $form->textField($model,'plazo'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'taza'); ?>
		<?php echo $form->textField($model,'taza',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cuota_mensual'); ?>
		<?php echo $form->textField($model,'cuota_mensual',array('size'=>25,'maxlength'=>25)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'apellido_paterno'); ?>
		<?php echo $form->textField($model,'apellido_paterno',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'apellido_materno'); ?>
		<?php echo $form->textField($model,'apellido_materno',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nombres'); ?>
		<?php echo $form->textField($model,'nombres',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cedula'); ?>
		<?php echo $form->textField($model,'cedula',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fecha_nacimiento'); ?>
		<?php echo $form->textField($model,'fecha_nacimiento',array('size'=>60,'maxlength'=>75)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nacionalidad'); ?>
		<?php echo $form->textField($model,'nacionalidad',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'estado_civil'); ?>
		<?php echo $form->textField($model,'estado_civil',array('size'=>60,'maxlength'=>120)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'empresa_trabajo'); ?>
		<?php echo $form->textField($model,'empresa_trabajo',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'telefonos_trabajo'); ?>
		<?php echo $form->textField($model,'telefonos_trabajo',array('size'=>60,'maxlength'=>75)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tiempo_trabajo'); ?>
		<?php echo $form->textField($model,'tiempo_trabajo'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cargo'); ?>
		<?php echo $form->textField($model,'cargo',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'direccion_empresa'); ?>
		<?php echo $form->textField($model,'direccion_empresa',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tipo_relacion_laboral'); ?>
		<?php echo $form->textField($model,'tipo_relacion_laboral',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'actividad_empresa'); ?>
		<?php echo $form->textField($model,'actividad_empresa',array('size'=>60,'maxlength'=>80)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'apellido_paterno_conyugue'); ?>
		<?php echo $form->textField($model,'apellido_paterno_conyugue',array('size'=>60,'maxlength'=>80)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'apellido_materno_conyugue'); ?>
		<?php echo $form->textField($model,'apellido_materno_conyugue',array('size'=>60,'maxlength'=>80)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nombres_conyugue'); ?>
		<?php echo $form->textField($model,'nombres_conyugue',array('size'=>60,'maxlength'=>80)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cedula_conyugue'); ?>
		<?php echo $form->textField($model,'cedula_conyugue',array('size'=>60,'maxlength'=>80)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fecha_nacimiento_conyugue'); ?>
		<?php echo $form->textField($model,'fecha_nacimiento_conyugue',array('size'=>60,'maxlength'=>85)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nacionalidad_conyugue'); ?>
		<?php echo $form->textField($model,'nacionalidad_conyugue',array('size'=>60,'maxlength'=>80)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'empresa_trabajo_conyugue'); ?>
		<?php echo $form->textField($model,'empresa_trabajo_conyugue',array('size'=>60,'maxlength'=>80)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'telefono_trabajo_conyugue'); ?>
		<?php echo $form->textField($model,'telefono_trabajo_conyugue',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tiempo_trabajo_conyugue'); ?>
		<?php echo $form->textField($model,'tiempo_trabajo_conyugue'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cargo_conyugue'); ?>
		<?php echo $form->textField($model,'cargo_conyugue',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'direccion_empresa_conyugue'); ?>
		<?php echo $form->textField($model,'direccion_empresa_conyugue',array('size'=>60,'maxlength'=>120)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tipo_relacion_laboral_conyugue'); ?>
		<?php echo $form->textField($model,'tipo_relacion_laboral_conyugue',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'domicilio_actual'); ?>
		<?php echo $form->textField($model,'domicilio_actual',array('size'=>60,'maxlength'=>150)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'habita'); ?>
		<?php echo $form->textField($model,'habita',array('size'=>60,'maxlength'=>150)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'avaluo_propiedad'); ?>
		<?php echo $form->textField($model,'avaluo_propiedad',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'vive'); ?>
		<?php echo $form->textField($model,'vive',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'valor_arriendo'); ?>
		<?php echo $form->textField($model,'valor_arriendo',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'calle'); ?>
		<?php echo $form->textField($model,'calle',array('size'=>60,'maxlength'=>80)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'barrio'); ?>
		<?php echo $form->textField($model,'barrio',array('size'=>60,'maxlength'=>80)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'referencia_domicilio'); ?>
		<?php echo $form->textField($model,'referencia_domicilio',array('size'=>60,'maxlength'=>80)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'telefono_residencia'); ?>
		<?php echo $form->textField($model,'telefono_residencia',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'celular'); ?>
		<?php echo $form->textField($model,'celular',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sueldo_mensual'); ?>
		<?php echo $form->textField($model,'sueldo_mensual',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sueldo_mensual_conyugue'); ?>
		<?php echo $form->textField($model,'sueldo_mensual_conyugue',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'banco1'); ?>
		<?php echo $form->textField($model,'banco1',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cuenta1'); ?>
		<?php echo $form->textField($model,'cuenta1',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'banco2'); ?>
		<?php echo $form->textField($model,'banco2',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cuenta2'); ?>
		<?php echo $form->textField($model,'cuenta2',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'referencia_personal1'); ?>
		<?php echo $form->textField($model,'referencia_personal1',array('size'=>60,'maxlength'=>150)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'referencia_personal2'); ?>
		<?php echo $form->textField($model,'referencia_personal2',array('size'=>60,'maxlength'=>150)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'parentesco1'); ?>
		<?php echo $form->textField($model,'parentesco1',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'parentesco2'); ?>
		<?php echo $form->textField($model,'parentesco2',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'telefono_referencia1'); ?>
		<?php echo $form->textField($model,'telefono_referencia1',array('size'=>60,'maxlength'=>60)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'telefono_referencia2'); ?>
		<?php echo $form->textField($model,'telefono_referencia2',array('size'=>60,'maxlength'=>60)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'activos'); ?>
		<?php echo $form->textField($model,'activos'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pasivos'); ?>
		<?php echo $form->textField($model,'pasivos'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'patrimonio'); ?>
		<?php echo $form->textField($model,'patrimonio'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_informacion'); ?>
		<?php echo $form->textField($model,'id_informacion'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_vehiculo'); ?>
		<?php echo $form->textField($model,'id_vehiculo'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->