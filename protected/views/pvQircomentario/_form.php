<script src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/jquery.validate.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/css/custom-theme/jquery-ui-1.10.0.custom.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.min.js"></script>
<?php
/* @var $this PvQircomentarioController */
/* @var $model Qircomentario */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'qircomentario-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
        'htmlOptions' => array(
            'class' => 'form-horizontal',
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>

    <?php echo $form->errorSummary($model); ?> 

    <div class="col-sm-6">
        <?php echo $form->labelEx($model, 'estado'); ?>
        <?php
        $estados = array(
            'Pendiente' => 'Pendiente',
            'En Estudio' => 'En Estudio',
            'Mas Informacion Requerida' => 'Más Información Requerida',
            'Mejorado' => 'Mejorado',
            'Cerrado' => 'Cerrado',
        );
        echo $form->dropDownList($model, 'estado', $estados, array('class' => 'form-control', 'prompt' => ''));
        ?>
        <?php echo $form->error($model, 'estado'); ?>
    </div>

    <div class="col-sm-6">
        <?php echo $form->labelEx($model, 'fecha'); ?>
        <?php //echo $form->textField($model, 'fecha', array('class' => 'form-control datepicker','readonly'=>true,'value'=>date("Y-m-d"))); ?>
        <?php echo $form->textField($model, 'fecha', array('class' => 'form-control','readonly'=>true,'value'=>date("Y-m-d"))); ?>
        <?php echo $form->error($model, 'fecha'); ?>
    </div>

    <div class="col-sm-6">
        <?php echo $form->labelEx($model, 'de'); ?>
        <?php echo $form->textField($model, 'de', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
        <?php echo $form->error($model, 'de'); ?>
    </div>

    <div class="col-sm-6">
        <?php echo $form->labelEx($model, 'para'); ?>
        <?php echo $form->textField($model, 'para', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
        <?php echo $form->error($model, 'para'); ?>
    </div>

    <div class="col-sm-6">
        <?php echo $form->labelEx($model, 'asunto'); ?>
        <?php echo $form->textField($model, 'asunto', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
        <?php echo $form->error($model, 'asunto'); ?>
    </div>

    <div class="col-sm-6">
        <?php echo $form->labelEx($model, 'num_reporte'); ?>
        <?php echo $form->textField($model, 'num_reporte', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control', 'OnFocus' => "this.blur()")); ?>
        <?php echo $form->error($model, 'num_reporte'); ?>
    </div>

    <div class="col-sm-6">
        <?php echo $form->labelEx($model, 'modelo'); ?>
        <?php echo $form->textField($model, 'modelo', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control', 'OnFocus' => "this.blur()")); ?>
        <?php echo $form->error($model, 'modelo'); ?>
    </div>

    <div class="col-sm-6">
        <?php echo $form->labelEx($model, 'comentario'); ?>
        <?php echo $form->textArea($model, 'comentario', array('rows' => 6, 'cols' => 50, 'class' => 'form-control')); ?>
        <?php echo $form->error($model, 'comentario'); ?>
    </div>

    <?php
    if (isset($modelA)) {
        ?>
        <div class="col-sm-12">
            <?php echo $form->labelEx($modelA, 'Adjuntos'); ?>
            <?php echo CHtml::activeFileField($modelA, 'nombre_file', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control file')); ?>
            <?php echo $form->error($modelA, 'nombre_file'); ?>
        </div>
    <?php } ?>

    <div class="row buttons col-sm-12">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Crear' : 'Guardar', array('class' => 'btn btn-danger')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->

<script type="text/javascript">
    $(function() {
        $(".datepicker").datepicker({
            changeMonth: true,
            changeYear: true
        });
    });
</script>