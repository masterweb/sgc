<?php
/* @var $this GestionStockController */
/* @var $model GestionStock */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'gestion-stock-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'fecha_w'); ?>
		<?php echo $form->textField($model,'fecha_w'); ?>
		<?php echo $form->error($model,'fecha_w'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'embarque'); ?>
		<?php echo $form->textField($model,'embarque',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'embarque'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bloque'); ?>
		<?php echo $form->textField($model,'bloque',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'bloque'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'familia'); ?>
		<?php echo $form->textField($model,'familia',array('size'=>60,'maxlength'=>80)); ?>
		<?php echo $form->error($model,'familia'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'version'); ?>
		<?php echo $form->textField($model,'version',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'version'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'equip'); ?>
		<?php echo $form->textArea($model,'equip',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'equip'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fsc'); ?>
		<?php echo $form->textField($model,'fsc',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'fsc'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'referencia'); ?>
		<?php echo $form->textField($model,'referencia',array('size'=>60,'maxlength'=>80)); ?>
		<?php echo $form->error($model,'referencia'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'aeade'); ?>
		<?php echo $form->textField($model,'aeade',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'aeade'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'segmento'); ?>
		<?php echo $form->textField($model,'segmento',array('size'=>60,'maxlength'=>80)); ?>
		<?php echo $form->error($model,'segmento'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'grupo'); ?>
		<?php echo $form->textField($model,'grupo',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'grupo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'concesionario'); ?>
		<?php echo $form->textField($model,'concesionario',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'concesionario'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'color_origen'); ?>
		<?php echo $form->textField($model,'color_origen',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'color_origen'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'color_plano'); ?>
		<?php echo $form->textField($model,'color_plano',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'color_plano'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'my'); ?>
		<?php echo $form->textField($model,'my',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'my'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'chasis'); ?>
		<?php echo $form->textField($model,'chasis',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'chasis'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'edad'); ?>
		<?php echo $form->textField($model,'edad',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'edad'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'rango'); ?>
		<?php echo $form->textField($model,'rango',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'rango'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fact'); ?>
		<?php echo $form->textField($model,'fact',array('size'=>60,'maxlength'=>80)); ?>
		<?php echo $form->error($model,'fact'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cod_aeade'); ?>
		<?php echo $form->textField($model,'cod_aeade',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'cod_aeade'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pvc'); ?>
		<?php echo $form->textField($model,'pvc',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'pvc'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'stock'); ?>
		<?php echo $form->textField($model,'stock',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'stock'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->