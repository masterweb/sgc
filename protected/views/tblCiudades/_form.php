<?php
/* @var $this TblCiudadesController */
/* @var $model TblCiudades */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tbl-ciudades-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id_provincia'); ?>
		<?php echo $form->textField($model,'id_provincia'); ?>
		<?php echo $form->error($model,'id_provincia'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_ciudad'); ?>
		<?php echo $form->textField($model,'id_ciudad'); ?>
		<?php echo $form->error($model,'id_ciudad'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'nombre'); ?>
		<?php echo $form->textField($model,'nombre',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'nombre'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->