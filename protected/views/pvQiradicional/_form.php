<script src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/jquery.validate.js" type="text/javascript" charset="utf-8"></script>
<?php
/* @var $this PvQiradicionalController */
/* @var $model Qiradicional */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'qiradicional-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
            ));
    ?>

    <?php echo $form->errorSummary($model); ?>

<!--    <div class="col-sm-6">
        <?php // echo $form->labelEx($model, 'qirId'); ?>
        <?php // echo $form->textField($model, 'qirId',array('class' => 'form-control')); ?>
        <?php // echo $form->error($model, 'qirId'); ?>
    </div>-->

    <div class="col-sm-6">
        <?php echo $form->labelEx($model, 'vin'); ?>
        <?php echo $form->textField($model, 'vin', array('size' => 60, 'maxlength' => 255,'class' => 'form-control')); ?>
        <?php echo $form->error($model, 'vin'); ?>
    </div>

    <div class="col-sm-6">
        <?php echo $form->labelEx($model, 'num_motor'); ?>
        <?php echo $form->textField($model, 'num_motor', array('maxlength' => 255,'class' => 'form-control')); ?>
        <?php echo $form->error($model, 'num_motor'); ?>
    </div>

    <div class="col-sm-6">
        <?php echo $form->labelEx($model, 'kilometraje'); ?>
        <?php echo $form->textField($model, 'kilometraje', array('maxlength' => 100,'class' => 'form-control')); ?>
        <?php echo $form->error($model, 'kilometraje'); ?>
    </div>

    <div class="col-sm-6">
        <?php echo $form->labelEx($model, 'num_reporte'); ?>
        <?php echo $form->textField($model, 'num_reporte', array('class' => 'span2 form-control','OnFocus'=>"this.blur()")); ?>
        <?php echo $form->error($model, 'num_reporte'); ?>
    </div>

    <div class="row buttons col-sm-12">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Crear' : 'Guardar',array('class' => 'btn btn-danger')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->