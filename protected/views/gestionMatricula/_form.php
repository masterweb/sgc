<?php
/* @var $this GestionMatriculaController */
/* @var $model GestionMatricula */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'gestion-matricula-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'factura_ingreso'); ?>
		<?php echo $form->textField($model,'factura_ingreso'); ?>
		<?php echo $form->error($model,'factura_ingreso'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'envio_factura'); ?>
		<?php echo $form->textField($model,'envio_factura'); ?>
		<?php echo $form->error($model,'envio_factura'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pago_consejo'); ?>
		<?php echo $form->textField($model,'pago_consejo'); ?>
		<?php echo $form->error($model,'pago_consejo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'venta_credito'); ?>
		<?php echo $form->textField($model,'venta_credito'); ?>
		<?php echo $form->error($model,'venta_credito'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'entrega_documentos_gestor'); ?>
		<?php echo $form->textField($model,'entrega_documentos_gestor'); ?>
		<?php echo $form->error($model,'entrega_documentos_gestor'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ente_regulador_placa'); ?>
		<?php echo $form->textField($model,'ente_regulador_placa'); ?>
		<?php echo $form->error($model,'ente_regulador_placa'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'vehiculo_matricula_placas'); ?>
		<?php echo $form->textField($model,'vehiculo_matricula_placas'); ?>
		<?php echo $form->error($model,'vehiculo_matricula_placas'); ?>
	</div>

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
		<?php echo $form->labelEx($model,'fecha'); ?>
		<?php echo $form->textField($model,'fecha'); ?>
		<?php echo $form->error($model,'fecha'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->