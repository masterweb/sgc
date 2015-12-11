<?php
/* @var $this GestionAmortizacionController */
/* @var $model GestionAmortizacion */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'gestion-amortizacion-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'interes'); ?>
		<?php echo $form->textField($model,'interes'); ?>
		<?php echo $form->error($model,'interes'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'capital_reducido'); ?>
		<?php echo $form->textField($model,'capital_reducido'); ?>
		<?php echo $form->error($model,'capital_reducido'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'capital'); ?>
		<?php echo $form->textField($model,'capital'); ?>
		<?php echo $form->error($model,'capital'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'seguro_desgravamen'); ?>
		<?php echo $form->textField($model,'seguro_desgravamen'); ?>
		<?php echo $form->error($model,'seguro_desgravamen'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->