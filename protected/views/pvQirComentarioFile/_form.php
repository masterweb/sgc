<?php
/* @var $this PvQirComentarioFileController */
/* @var $model QirComentarioFile */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'qir-comentario-file-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('class' => 'form-horizontal','enctype' => 'multipart/form-data',)
    ));
    ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="col-sm-6">
        <label>Archivo</label>
        <?php echo CHtml::activeFileField($model, 'nombre_file', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control file')); ?>
        <?php echo $form->error($model, 'nombre_file'); ?>
        <?php
        foreach (Yii::app()->user->getFlashes() as $key => $message) {
            echo '<div class=" row flash-' . $key . '">' . $message . "</div>\n";
        }
        ?>
    </div>

    <div class="row buttons col-sm-12">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Crear' : 'Guardar', array('class' => 'btn btn-danger')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->