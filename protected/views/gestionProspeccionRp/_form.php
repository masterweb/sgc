<?php
/* @var $this GestionProspeccionRpController */
/* @var $model GestionProspeccionRp */
/* @var $form CActiveForm */
echo 'tipo: '.$tipo;
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'gestion-prospeccion-rp-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'preg1'); ?>
		<?php echo $form->textField($model,'preg1'); ?>
		<?php echo $form->error($model,'preg1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'preg2'); ?>
		<?php echo $form->textField($model,'preg2'); ?>
		<?php echo $form->error($model,'preg2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'preg3'); ?>
		<?php echo $form->textField($model,'preg3'); ?>
		<?php echo $form->error($model,'preg3'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'preg4'); ?>
		<?php echo $form->textField($model,'preg4'); ?>
		<?php echo $form->error($model,'preg4'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'preg5'); ?>
		<?php echo $form->textField($model,'preg5'); ?>
		<?php echo $form->error($model,'preg5'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'preg6'); ?>
		<?php echo $form->textField($model,'preg6'); ?>
		<?php echo $form->error($model,'preg6'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'preg3_sec1'); ?>
		<?php echo $form->textField($model,'preg3_sec1'); ?>
		<?php echo $form->error($model,'preg3_sec1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'preg3_sec2'); ?>
		<?php echo $form->textField($model,'preg3_sec2',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'preg3_sec2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'preg3_sec3'); ?>
		<?php echo $form->textField($model,'preg3_sec3',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'preg3_sec3'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'preg3_sec4'); ?>
		<?php echo $form->textField($model,'preg3_sec4',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'preg3_sec4'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'preg4_sec1'); ?>
		<?php echo $form->textField($model,'preg4_sec1',array('size'=>60,'maxlength'=>80)); ?>
		<?php echo $form->error($model,'preg4_sec1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'preg4_sec2'); ?>
		<?php echo $form->textField($model,'preg4_sec2',array('size'=>60,'maxlength'=>150)); ?>
		<?php echo $form->error($model,'preg4_sec2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'preg4_sec3'); ?>
		<?php echo $form->textField($model,'preg4_sec3',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'preg4_sec3'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'preg4_sec4'); ?>
		<?php echo $form->textField($model,'preg4_sec4',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'preg4_sec4'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'preg5_sec1'); ?>
		<?php echo $form->textField($model,'preg5_sec1',array('size'=>60,'maxlength'=>80)); ?>
		<?php echo $form->error($model,'preg5_sec1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'preg5_sec2'); ?>
		<?php echo $form->textField($model,'preg5_sec2',array('size'=>60,'maxlength'=>80)); ?>
		<?php echo $form->error($model,'preg5_sec2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'preg5_sec3'); ?>
		<?php echo $form->textField($model,'preg5_sec3',array('size'=>60,'maxlength'=>80)); ?>
		<?php echo $form->error($model,'preg5_sec3'); ?>
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