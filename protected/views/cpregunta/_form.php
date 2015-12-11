<?php
/* @var $this CpreguntaController */
/* @var $model Cpregunta */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cpregunta-form',
	'enableAjaxValidation' => false,
        'clientOptions' => array(
        'validateOnSubmit'=>false,
        'validateOnChange'=>false,
        'validateOnType'=>false,
         ),
        'htmlOptions' => array('class' => 'form-horizontal')
            ));
    ?>


	<?php echo $form->errorSummary($model); ?>
	<div class="form-group">
		<?php echo $form->labelEx($model,'descripcion', array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
		<?php echo $form->textField($model,'descripcion',array('size'=>60,'maxlength'=>350, 'class' => 'form-control')); ?>
		<?php echo $form->error($model,'descripcion'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'tipocontenido', array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-4">
			<?php echo $form->dropDownList($model,'tipocontenido',array("mixto"=>"Números y Letras","numeros"=>"Solo números","letras"=>"Solo Letras"),array('class' => 'form-control')); ?>
			<?php echo $form->error($model,'tipocontenido'); ?>
		</div>
		<?php echo $form->labelEx($model,'estado', array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-4">
			<?php echo $form->dropDownList($model,'estado',array("ACTIVO"=>"ACTIVO","INACTIVO"=>"INACTIVO"),array('class' => 'form-control')); ?>
			<?php echo $form->error($model,'estado'); ?>
		</div>
	</div>

	

	<?php echo $form->hiddenField($model,'cquestionario_id',array('value'=>$idc)); ?>
	<?php echo $form->hiddenField($model,'ctipopregunta_id',array('value'=>1)); ?>
	 <div class="form-group pad-all">
		<?php echo $form->labelEx($model,'orden', array('class' => 'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
		
		<?php echo $form->textField($model,'orden',array('size'=>60,'maxlength'=>350, 'class' => 'form-control','value'=>$nump)); ?>
		<?php echo $form->error($model,'orden'); ?>
		</div>
	</div>

	<div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', array('class' => 'btn btn-danger')); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->