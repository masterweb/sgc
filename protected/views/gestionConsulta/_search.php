<?php
/* @var $this GestionConsultaController */
/* @var $model GestionConsulta */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'preg1_sec1'); ?>
		<?php echo $form->textField($model,'preg1_sec1',array('size'=>60,'maxlength'=>80)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'preg1_sec2'); ?>
		<?php echo $form->textField($model,'preg1_sec2',array('size'=>60,'maxlength'=>80)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'preg1_sec3'); ?>
		<?php echo $form->textField($model,'preg1_sec3',array('size'=>60,'maxlength'=>80)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'preg1_sec4'); ?>
		<?php echo $form->textField($model,'preg1_sec4',array('size'=>60,'maxlength'=>80)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'preg1_sec5'); ?>
		<?php echo $form->textField($model,'preg1_sec5',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'preg2'); ?>
		<?php echo $form->textField($model,'preg2',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'preg2_sec1'); ?>
		<?php echo $form->textArea($model,'preg2_sec1',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'preg3'); ?>
		<?php echo $form->textField($model,'preg3',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'preg3_sec1'); ?>
		<?php echo $form->textField($model,'preg3_sec1',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'preg3_sec2'); ?>
		<?php echo $form->textField($model,'preg3_sec2',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'preg3_sec3'); ?>
		<?php echo $form->textField($model,'preg3_sec3',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'preg3_sec4'); ?>
		<?php echo $form->textField($model,'preg3_sec4',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'preg4'); ?>
		<?php echo $form->textField($model,'preg4',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'preg5'); ?>
		<?php echo $form->textField($model,'preg5',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'preg6'); ?>
		<?php echo $form->textField($model,'preg6',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fecha'); ?>
		<?php echo $form->textField($model,'fecha'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->