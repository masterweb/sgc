<?php
/* @var $this CasosController */
/* @var $model Casos */
/* @var $form CActiveForm */
?>
<style>
    textarea{
        width: 470px !important;
    }
</style>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'casos-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('class' => 'form-horizontal')
)); ?>

	
	<?php //echo $form->errorSummary($model); ?>

	<div class="form-group">
            <?php echo $form->labelEx($model,'tema', array('class' => 'col-sm-1 control-label')); ?>
            <div class="col-sm-3">
                <?php echo $form->dropDownList($model,'tema',array('empty' => 'Seleccione'), array('class' => 'form-control')); ?>
                <?php echo $form->error($model,'tema'); ?>
            </div>
            <?php echo $form->labelEx($model,'subtema', array('class' => 'col-sm-1 control-label')); ?>
            <div class="col-sm-3">
                <?php echo $form->dropDownList($model,'subtema',array('value' => 'Seleccione'), array('class' => 'form-control')); ?>
                <?php echo $form->error($model,'subtema'); ?>
            </div>
		
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'nombre', array('class' => 'col-sm-1 control-label')); ?>
            <div class="col-sm-7">
		<?php echo $form->textField($model,'nombre',array('size'=>60,'maxlength'=>150, 'class' => 'form-control')); ?>
		<?php echo $form->error($model,'nombre'); ?>
            </div>
	</div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'provincia', array('class' => 'col-sm-1 control-label')); ?>
            <div class="col-sm-3">
                <?php echo $form->dropDownList($model,'provincia',array('value' => 'Seleccione'), array('class' => 'form-control')); ?>
                <?php echo $form->error($model,'provincia'); ?>
            </div>
            <?php echo $form->labelEx($model,'ciudad', array('class' => 'col-sm-1 control-label')); ?>
            <div class="col-sm-3">
                <?php echo $form->dropDownList($model,'ciudad',array('value' => 'Seleccione'), array('class' => 'form-control')); ?>
                <?php echo $form->error($model,'ciudad'); ?>
            </div>
		
	</div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'telefono', array('class' => 'col-sm-1 control-label')); ?>
            <div class="col-sm-3">
                <?php echo $form->textField($model,'telefono',array('size'=>50,'maxlength'=>50,'class' => 'form-control')); ?>
                <?php echo $form->error($model,'telefono'); ?>
            </div>
            <?php echo $form->labelEx($model,'celular', array('class' => 'col-sm-1 control-label')); ?>
            <div class="col-sm-3">
                <?php echo $form->textField($model,'celular',array('size'=>50,'maxlength'=>50,'class' => 'form-control')); ?>
                <?php echo $form->error($model,'celular'); ?>
            </div>
		
	</div>
    
	<div class="form-group">
		<?php echo $form->labelEx($model,'email', array('class' => 'col-sm-1 control-label')); ?>
            <div class="col-sm-7">
		<?php echo $form->textField($model,'email',array('size'=>50,'maxlength'=>50, 'class' => 'form-control')); ?>
		<?php echo $form->error($model,'email'); ?>
            </div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'comentario', array('class' => 'col-sm-1 control-label')); ?>
            <div class="col-sm-8">
		<?php echo $form->textArea($model,'comentario',array('rows'=>6, 'cols'=>50, 'class' => 'form-control')); ?>
		<?php echo $form->error($model,'comentario'); ?>
            </div>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->