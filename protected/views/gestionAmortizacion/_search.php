<?php
/* @var $this GestionAmortizacionController */
/* @var $model GestionAmortizacion */
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
		<?php echo $form->label($model,'interes'); ?>
		<?php echo $form->textField($model,'interes'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'capital_reducido'); ?>
		<?php echo $form->textField($model,'capital_reducido'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'capital'); ?>
		<?php echo $form->textField($model,'capital'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'seguro_desgravamen'); ?>
		<?php echo $form->textField($model,'seguro_desgravamen'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->