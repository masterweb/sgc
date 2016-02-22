<?php
/* @var $this GestionPasoEntregaDetailController */
/* @var $model GestionPasoEntregaDetail */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'gestion-paso-entrega-detail-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id_paso'); ?>
		<?php echo $form->textField($model,'id_paso'); ?>
		<?php echo $form->error($model,'id_paso'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fecha_paso'); ?>
		<?php echo $form->textField($model,'fecha_paso'); ?>
		<?php echo $form->error($model,'fecha_paso'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'observaciones'); ?>
		<?php echo $form->textArea($model,'observaciones',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'observaciones'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'placa'); ?>
		<?php echo $form->textField($model,'placa',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'placa'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'responsable'); ?>
		<?php echo $form->textField($model,'responsable',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'responsable'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'foto_entrega'); ?>
		<?php echo $form->textField($model,'foto_entrega',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'foto_entrega'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'foto_hoja_entrega'); ?>
		<?php echo $form->textArea($model,'foto_hoja_entrega',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'foto_hoja_entrega'); ?>
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