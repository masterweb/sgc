<?php
/* @var $this PvQiradicionalController */
/* @var $model Qiradicional */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'qirId'); ?>
		<?php echo $form->textField($model,'qirId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'vin'); ?>
		<?php echo $form->textField($model,'vin',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'num_motor'); ?>
		<?php echo $form->textField($model,'num_motor',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kilometraje'); ?>
		<?php echo $form->textField($model,'kilometraje',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'num_reporte'); ?>
		<?php echo $form->textField($model,'num_reporte',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->