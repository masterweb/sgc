<?php
/* @var $this CopcionpreguntaController */
/* @var $model Copcionpregunta */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'copcionpregunta-form',
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

	<div class="row">
		<?php echo $form->labelEx($model,'detalle'); ?>
		<?php echo $form->textField($model,'detalle',array('size'=>60,'maxlength'=>150)); ?>
		<?php echo $form->error($model,'detalle'); ?>
	</div>

<!--	<div class="row">
		<?php //echo $form->labelEx($model,'valor'); ?>
		<?php //echo $form->textField($model,'valor',array('size'=>45,'maxlength'=>45)); ?>
		<?php //echo $form->error($model,'valor'); ?>
	</div>
-->

	<?php echo $form->hiddenField($model,'cpregunta_id',array('value'=>$idc)); ?>
	<div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', array('class' => 'btn btn-danger')); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->