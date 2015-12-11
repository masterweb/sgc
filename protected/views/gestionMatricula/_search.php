<?php
/* @var $this GestionMatriculaController */
/* @var $model GestionMatricula */
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
		<?php echo $form->label($model,'factura_ingreso'); ?>
		<?php echo $form->textField($model,'factura_ingreso'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'envio_factura'); ?>
		<?php echo $form->textField($model,'envio_factura'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pago_consejo'); ?>
		<?php echo $form->textField($model,'pago_consejo'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'venta_credito'); ?>
		<?php echo $form->textField($model,'venta_credito'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'entrega_documentos_gestor'); ?>
		<?php echo $form->textField($model,'entrega_documentos_gestor'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ente_regulador_placa'); ?>
		<?php echo $form->textField($model,'ente_regulador_placa'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'vehiculo_matricula_placas'); ?>
		<?php echo $form->textField($model,'vehiculo_matricula_placas'); ?>
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
		<?php echo $form->label($model,'fecha'); ?>
		<?php echo $form->textField($model,'fecha'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->