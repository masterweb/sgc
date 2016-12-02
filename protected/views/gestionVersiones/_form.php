<?php
/* @var $this GestionVersionesController */
/* @var $model GestionVersiones */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'gestion-versiones-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id_modelos'); ?>
		<?php echo $form->textField($model,'id_modelos'); ?>
		<?php echo $form->error($model,'id_modelos'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_categoria'); ?>
		<?php echo $form->textField($model,'id_categoria'); ?>
		<?php echo $form->error($model,'id_categoria'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'nombre_version'); ?>
		<?php echo $form->textField($model,'nombre_version',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'nombre_version'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'precio'); ?>
		<?php echo $form->textField($model,'precio',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'precio'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'orden'); ?>
		<?php echo $form->textField($model,'orden'); ?>
		<?php echo $form->error($model,'orden'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pdf'); ?>
		<?php echo $form->textField($model,'pdf',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'pdf'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->