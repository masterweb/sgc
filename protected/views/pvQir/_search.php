<?php
/* @var $this PvQirController */
/* @var $model Qir */
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
		<?php echo $form->label($model,'dealerId'); ?>
		<?php echo $form->textField($model,'dealerId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'num_reporte'); ?>
		<?php echo $form->textField($model,'num_reporte',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fecha_registro'); ?>
		<?php echo $form->textField($model,'fecha_registro'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'modeloPostVentaId'); ?>
		<?php echo $form->textField($model,'modeloPostVentaId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'num_vehiculos_afectados'); ?>
		<?php echo $form->textField($model,'num_vehiculos_afectados'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'prioridad'); ?>
		<?php echo $form->textField($model,'prioridad',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'parte_defectuosa'); ?>
		<?php echo $form->textField($model,'parte_defectuosa',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'vin'); ?>
		<?php echo $form->textField($model,'vin',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'num_motor'); ?>
		<?php echo $form->textField($model,'num_motor',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'transmision'); ?>
		<?php echo $form->textField($model,'transmision',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'num_parte_casual'); ?>
		<?php echo $form->textField($model,'num_parte_casual',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'detalle_parte_casual'); ?>
		<?php echo $form->textField($model,'detalle_parte_casual',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'codigo_naturaleza'); ?>
		<?php echo $form->textField($model,'codigo_naturaleza',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'codigo_casual'); ?>
		<?php echo $form->textField($model,'codigo_casual',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fecha_garantia'); ?>
		<?php echo $form->textField($model,'fecha_garantia'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fecha_fabricacion'); ?>
		<?php echo $form->textField($model,'fecha_fabricacion'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kilometraje'); ?>
		<?php echo $form->textField($model,'kilometraje',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fecha_reparacion'); ?>
		<?php echo $form->textField($model,'fecha_reparacion'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'titular'); ?>
		<?php echo $form->textField($model,'titular',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'descripcion'); ?>
		<?php echo $form->textArea($model,'descripcion',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ingresado'); ?>
		<?php echo $form->textField($model,'ingresado',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'circunstancia'); ?>
		<?php echo $form->textField($model,'circunstancia',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'periodo_tiempo'); ?>
		<?php echo $form->textField($model,'periodo_tiempo',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'rango_velocidad'); ?>
		<?php echo $form->textField($model,'rango_velocidad',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'localizacion'); ?>
		<?php echo $form->textField($model,'localizacion',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fase_manejo'); ?>
		<?php echo $form->textField($model,'fase_manejo',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'condicion_camino'); ?>
		<?php echo $form->textField($model,'condicion_camino',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'etc'); ?>
		<?php echo $form->textField($model,'etc',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'vin_adicional'); ?>
		<?php echo $form->textField($model,'vin_adicional',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'num_motor_adi'); ?>
		<?php echo $form->textField($model,'num_motor_adi',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kilometraje_adic'); ?>
		<?php echo $form->textField($model,'kilometraje_adic',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'estado'); ?>
		<?php echo $form->textField($model,'estado',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->