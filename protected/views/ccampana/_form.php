<?php
/* @var $this CcampanaController */
/* @var $model Ccampana */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ccampana-form',
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
		
		<?php echo $form->labelEx($model,'nombre', array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
			<?php echo $form->textField($model,'nombre',array('size'=>60,'maxlength'=>150, 'class' => 'form-control')); ?>
			<?php echo $form->error($model,'nombre'); ?>
		</div>
		
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'descripcion', array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-4">
			<?php echo $form->textField($model,'descripcion',array('size'=>60,'maxlength'=>250, 'class' => 'form-control')); ?>
			<?php echo $form->error($model,'descripcion'); ?>
		</div>
		<?php echo $form->labelEx($model,'estado', array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-4">
			<?php echo $form->dropDownList($model,'estado',array("ACTIVO"=>"ACTIVO","INACTIVO"=>"INACTIVO"),array('class' => 'form-control')); ?>
			<?php echo $form->error($model,'estado'); ?>
		</div>
		
	</div>	

	<div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', array('class' => 'btn btn-danger')); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->