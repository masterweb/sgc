<?php
/* @var $this GestionFirmaController */
/* @var $model GestionFirma */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'gestion-firma-form',
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
		<?php echo $form->labelEx($model,'firma'); ?>
		<?php echo $form->textArea($model,'firma',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'firma'); ?>
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