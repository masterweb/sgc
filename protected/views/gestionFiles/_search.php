<?php
/* @var $this GestionFilesController */
/* @var $model GestionFiles */
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
		<?php echo $form->label($model,'nombre'); ?>
		<?php echo $form->textField($model,'nombre',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'descripcion'); ?>
		<?php echo $form->textArea($model,'descripcion',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tipo'); ?>
		<?php echo $form->textField($model,'tipo'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'provincia'); ?>
		<?php echo $form->textField($model,'provincia',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'modelo'); ?>
		<?php echo $form->textField($model,'modelo',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fecha_actualizacion'); ?>
		<?php echo $form->textField($model,'fecha_actualizacion'); ?>
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