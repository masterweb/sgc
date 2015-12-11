<?php
/* @var $this GestionNuevaCotizacionController */
/* @var $model GestionNuevaCotizacion */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'gestion-nueva-cotizacion-form',
        'enableAjaxValidation' => false,
            ));
    ?>


    <?php //echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-md-3">
            <?php echo $form->labelEx($model, 'fuente'); ?>
            <?php
            echo $form->dropDownList($model, 'fuente', array(
                'showroom' => 'Showroom',
                'web' => 'Web',
                'redes-sociales' => 'Redes Sociales',
                'prospeccion' => 'Prospección',
                'exhibicion' => 'Exhibición',
                'exonerados' => 'Exonerados',
                'referido' => 'Referido',
                'otro' => 'Otro'), array('class' => 'form-control'));
            ?>
            <?php echo $form->error($model, 'fuente'); ?>
        </div>
    </div>
    <div class="row otro-cont" style="display: none;">
        <div class="col-md-3">
            <label for="GestionNuevaCotizacion_fuente">Otro</label>
            <input size="40" maxlength="20" class="form-control" name="GestionNuevaCotizacion[otro]" id="GestionNuevaCotizacion_otro" type="text">
            
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?php echo $form->labelEx($model, 'cedula'); ?>
            <?php echo $form->textField($model, 'cedula', array('size' => 40, 'maxlength' => 10, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'cedula'); ?>
        </div>
    </div>
    <div class="row buttons">
        <div class="col-md-3">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Crear Cotización' : 'Save', array('class' => 'btn btn-danger')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->