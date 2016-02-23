<?php
/* @var $this GestionPasoEntregaController */
/* @var $model GestionPasoEntrega */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'gestion-paso-entrega-form',
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
		<?php echo $form->labelEx($model,'envio_factura'); ?>
		<?php echo $form->textField($model,'envio_factura'); ?>
		<?php echo $form->error($model,'envio_factura'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'emision_contrato'); ?>
		<?php echo $form->textField($model,'emision_contrato'); ?>
		<?php echo $form->error($model,'emision_contrato'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'agendar_firma'); ?>
		<?php echo $form->textField($model,'agendar_firma'); ?>
		<?php echo $form->error($model,'agendar_firma'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'alistamiento_unidad'); ?>
		<?php echo $form->textField($model,'alistamiento_unidad'); ?>
		<?php echo $form->error($model,'alistamiento_unidad'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pago_matricula'); ?>
		<?php echo $form->textField($model,'pago_matricula'); ?>
		<?php echo $form->error($model,'pago_matricula'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'recepcion_contratos'); ?>
		<?php echo $form->textField($model,'recepcion_contratos'); ?>
		<?php echo $form->error($model,'recepcion_contratos'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'recepcion_matricula'); ?>
		<?php echo $form->textField($model,'recepcion_matricula'); ?>
		<?php echo $form->error($model,'recepcion_matricula'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'vehiculo_revisado'); ?>
		<?php echo $form->textField($model,'vehiculo_revisado'); ?>
		<?php echo $form->error($model,'vehiculo_revisado'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'entrega_vehiculo'); ?>
		<?php echo $form->textField($model,'entrega_vehiculo'); ?>
		<?php echo $form->error($model,'entrega_vehiculo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'foto_entrega'); ?>
		<?php echo $form->textField($model,'foto_entrega'); ?>
		<?php echo $form->error($model,'foto_entrega'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'foto_hoja_entrega'); ?>
		<?php echo $form->textField($model,'foto_hoja_entrega'); ?>
		<?php echo $form->error($model,'foto_hoja_entrega'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fecha'); ?>
		<?php echo $form->textField($model,'fecha'); ?>
		<?php echo $form->error($model,'fecha'); ?>
	</div>
        <div class="row">
		<?php echo $form->labelEx($model,'paso'); ?>
		<?php echo $form->textField($model,'paso'); ?>
		<?php echo $form->error($model,'paso'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->