<?php
/* @var $this CencuestadoscquestionarioController */
/* @var $model Cencuestadoscquestionario */
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
		<?php echo $form->label($model,'cencuestados_id'); ?>
		<?php echo $form->textField($model,'cencuestados_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cquestionario_id'); ?>
		<?php echo $form->textField($model,'cquestionario_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'usuarios_id'); ?>
		<?php echo $form->textField($model,'usuarios_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'audio'); ?>
		<?php echo $form->textField($model,'audio',array('size'=>60,'maxlength'=>250)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tiempoinicio'); ?>
		<?php echo $form->textField($model,'tiempoinicio'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tiempofinal'); ?>
		<?php echo $form->textField($model,'tiempofinal'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'estado'); ?>
		<?php echo $form->textField($model,'estado',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'observaciones'); ?>
		<?php echo $form->textArea($model,'observaciones',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->