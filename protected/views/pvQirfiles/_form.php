<?php
/* @var $this PvQirfilesController */
/* @var $model Qirfiles */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'qirfiles-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('class' => 'form-horizontal','enctype' => 'multipart/form-data',)
    ));
    ?>


    <?php echo $form->errorSummary($model); ?>

    <div class="col-sm-6">
        <label>Archivo</label>
        <?php echo CHtml::activeFileField($model, 'nombre', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control file')); ?>
        <?php echo $form->error($model, 'nombre'); ?>
        <?php
        foreach (Yii::app()->user->getFlashes() as $key => $message) {
            echo '<div class=" row flash-' . $key . '">' . $message . "</div>\n";
        }
        ?>
    </div>
    <!--<div class="col-sm-6">
        <?php //echo $form->labelEx($model, 'nombre'); ?>
        <?php //echo $form->textField($model, 'nombre', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
        <?php //echo $form->error($model, 'nombre'); ?>
    </div>-->

    <div class="col-sm-6">
        <?php echo $form->labelEx($model, 'num_reporte'); ?>
        <?php echo $form->textField($model, 'num_reporte', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control', 'OnFocus' => "this.blur()")); ?>
        <?php echo $form->error($model, 'num_reporte'); ?>
    </div>

    <div class="row buttons col-sm-12">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Crear' : 'Guardar', array('class' => 'btn btn-danger')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->