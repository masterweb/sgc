<?php
/* @var $this GestionAgendamientoController */
/* @var $model GestionAgendamiento */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'gestion-agendamiento-form',
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
		<?php echo $form->labelEx($model,'paso'); ?>
		<?php echo $form->textField($model,'paso',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'paso'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'agendamiento'); ?>
		<?php echo $form->textField($model,'agendamiento',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'agendamiento'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'observaciones'); ?>
		<?php echo $form->textArea($model,'observaciones',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'observaciones'); ?>
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