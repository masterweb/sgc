<?php
/* @var $this CencuestadoscquestionarioController */
/* @var $model Cencuestadoscquestionario */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cencuestadoscquestionario-form',
	'enableAjaxValidation' => false,
        'clientOptions' => array(
        'validateOnSubmit'=>false,
        'validateOnChange'=>false,
        'validateOnType'=>false,
         ),
        'htmlOptions' => array('class' => 'form-horizontal','enctype' => 'multipart/form-data',)
            ));
    ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'audio', array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-12">
			<?php echo CHtml::activeFileField($model, 'audio',array("class"=>"form-control subir")); ?>
			<?php echo $form->error($model,'audio'); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'observaciones', array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-12">
		<?php echo $form->textArea($model,'observaciones',array('rows'=>6, 'cols'=>50,'class' => 'form-control')); ?>
		<?php echo $form->error($model,'observaciones'); ?>
		</div>
	</div>

	<div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Finalizar' : 'Finalizar', array('class' => 'btn btn-danger')); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->