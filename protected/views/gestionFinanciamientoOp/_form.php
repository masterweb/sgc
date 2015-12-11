<?php
/* @var $this GestionFinanciamientoOpController */
/* @var $model GestionFinanciamientoOp */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'gestion-financiamiento-op-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id_informacion'); ?>
		<?php echo $form->textField($model,'id_informacion'); ?>
		<?php echo $form->error($model,'id_informacion'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_vehiculo'); ?>
		<?php echo $form->textField($model,'id_vehiculo'); ?>
		<?php echo $form->error($model,'id_vehiculo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cuota_inicial'); ?>
		<?php echo $form->textField($model,'cuota_inicial',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'cuota_inicial'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'saldo_financiar'); ?>
		<?php echo $form->textField($model,'saldo_financiar',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'saldo_financiar'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tarjeta_credito'); ?>
		<?php echo $form->textField($model,'tarjeta_credito',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'tarjeta_credito'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'otro'); ?>
		<?php echo $form->textField($model,'otro',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'otro'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'plazos'); ?>
		<?php echo $form->textField($model,'plazos',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'plazos'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cuota_mensual'); ?>
		<?php echo $form->textField($model,'cuota_mensual',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'cuota_mensual'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'avaluo'); ?>
		<?php echo $form->textField($model,'avaluo',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'avaluo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'categoria'); ?>
		<?php echo $form->textField($model,'categoria',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'categoria'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fecha_cita'); ?>
		<?php echo $form->textField($model,'fecha_cita'); ?>
		<?php echo $form->error($model,'fecha_cita'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'observaciones'); ?>
		<?php echo $form->textArea($model,'observaciones',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'observaciones'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'precio_vehiculo'); ?>
		<?php echo $form->textField($model,'precio_vehiculo',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'precio_vehiculo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tasa'); ?>
		<?php echo $form->textField($model,'tasa',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'tasa'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'seguro'); ?>
		<?php echo $form->textField($model,'seguro',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'seguro'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'valor_financiamiento'); ?>
		<?php echo $form->textField($model,'valor_financiamiento',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'valor_financiamiento'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'forma_pago'); ?>
		<?php echo $form->textField($model,'forma_pago',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'forma_pago'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'entidad_financiera'); ?>
		<?php echo $form->textField($model,'entidad_financiera',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'entidad_financiera'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fecha'); ?>
		<?php echo $form->textField($model,'fecha'); ?>
		<?php echo $form->error($model,'fecha'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->