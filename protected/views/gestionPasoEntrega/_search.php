<?php
/* @var $this GestionPasoEntregaController */
/* @var $model GestionPasoEntrega */
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
		<?php echo $form->label($model,'id_informacion'); ?>
		<?php echo $form->textField($model,'id_informacion'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_vehiculo'); ?>
		<?php echo $form->textField($model,'id_vehiculo'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'envio_factura'); ?>
		<?php echo $form->textField($model,'envio_factura'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'emision_contrato'); ?>
		<?php echo $form->textField($model,'emision_contrato'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'agendar_firma'); ?>
		<?php echo $form->textField($model,'agendar_firma'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'alistamiento_unidad'); ?>
		<?php echo $form->textField($model,'alistamiento_unidad'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pago_matricula'); ?>
		<?php echo $form->textField($model,'pago_matricula'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'recepcion_contratos'); ?>
		<?php echo $form->textField($model,'recepcion_contratos'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'recepcion_matricula'); ?>
		<?php echo $form->textField($model,'recepcion_matricula'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'vehiculo_revisado'); ?>
		<?php echo $form->textField($model,'vehiculo_revisado'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'entrega_vehiculo'); ?>
		<?php echo $form->textField($model,'entrega_vehiculo'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'foto_entrega'); ?>
		<?php echo $form->textField($model,'foto_entrega'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'foto_hoja_entrega'); ?>
		<?php echo $form->textField($model,'foto_hoja_entrega'); ?>
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