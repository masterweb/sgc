<?php
/* @var $this GestionDemostracionController */
/* @var $model GestionDemostracion */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'gestion-demostracion-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'preg1'); ?>
		<?php echo $form->textField($model,'preg1',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'preg1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'preg1_licencia'); ?>
		<?php echo $form->textField($model,'preg1_licencia',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'preg1_licencia'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'preg1_agendamiento'); ?>
		<?php echo $form->textField($model,'preg1_agendamiento',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'preg1_agendamiento'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_informacion'); ?>
		<?php echo $form->textField($model,'id_informacion'); ?>
		<?php echo $form->error($model,'id_informacion'); ?>
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