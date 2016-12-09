<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilos.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/css/ui.daterangepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/js/date.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/js/daterangepicker.jQuery.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl ?>/ckeditor/ckeditor.js"></script>
<style>
    .errorSummary{
        display:none;
    }
</style>
<script>

    $(function () {
        //$('#modelos').val(18).attr("selected", "selected");
        CKEDITOR.replace('Boletinpostventa_contenido', {
            customConfig: '<?php echo Yii::app()->request->baseUrl ?>/ckeditor/minimal.js'
        });

        $("#Boletinpostventa_fecha").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: new Date(1950, 10 - 1, 25),
            yearRange: '2013:2016'
        });
    });

    $(function () {
        $("#boletinpostventa-form").validate({
            submitHandler: function (form) {
                var error = 0;
                var formTp = '';
                var codigo = $("#modelos").val();

                if (codigo == "") {
                    alert('Selecciones por lo menos un <?php echo utf8_encode("modelo, es un campo obligatorio"); ?>');
                    $('#modelos').focus();
                    error++;
                }

                if (error == 0) {
                    form.submit();
                }

            }
        })
    });
</script>
<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'boletinpostventa-form',
        'htmlOptions' => array('class' => 'form-horizontal')
    ));
    ?>


    <?php echo $form->errorSummary($model); ?>
    <div class="form-group">

        <?php echo $form->labelEx($model, 'titulo', array('class' => 'col-sm-2 control-label')); ?>
        <div class="col-sm-4">
            <?php echo $form->textField($model, 'titulo', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'titulo'); ?>
        </div>


        <?php echo $form->labelEx($model, 'codigo', array('class' => 'col-sm-2 control-label')); ?>
        <div class="col-sm-4">
            <?php echo $form->textField($model, 'codigo', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'codigo'); ?>
        </div>
    </div>
    <div class="form-group">

        <?php echo $form->labelEx($model, 'contenido', array('class' => 'col-sm-2 control-label')); ?>
        <div class="col-sm-10">
            <?php echo $form->textArea($model, 'contenido', array('rows' => 8, 'class' => 'form-control')); ?>

            <?php echo $form->error($model, 'contenido'); ?>
        </div>
    </div>
    <div class="form-group">
        <?php if (!empty($models)) { ?>
            <label class ='col-sm-2 control-label'>Modelos:<span class="required">*</span></label>
            <div class="col-sm-10">
                <?php //echo CHtml::dropDownList('modelos', 'modelos',CHtml::listData(Modelosposventa::model()->findAll(array("order"=>"ordenar ASC")),'id', 'descripcion'),array('empty'=>'-Todos-','multiple' => 'multiple')); ?>
                <select id="modelos" name="modelos[]" multiple="multiple">
                    
                    <?php
                    foreach ($models as $item) {
                        $selected = "";
                        if ($control != 0) {
                            foreach ($control as $ctrl) {
                                if ($ctrl->modelosposventa_id == $item->id) {
                                    $selected = "selected='selected'";
                                }
                            }
                        }
                        echo '<option value="' . $item->id . '" ' . $selected . '>' . $item->descripcion . '</option>';
                    }
                    ?>
                </select>
                <?php if (Yii::app()->user->hasFlash('error')) { ?>

                    <?php echo Yii::app()->user->getFlash('error'); ?>

                <?php } ?>
            </div>
        <?php } else echo 'No hay modelos de vehiculos para registrar un bolet&iacute;n' ?>
    </div>
    <div class="form-group">

        <?php echo $form->labelEx($model, 'fecha', array('class' => 'col-sm-2 control-label')); ?>
        <div class="col-sm-4">
            <?php echo $form->textField($model, 'fecha', array('rows' => 6, 'cols' => 50, 'class' => 'form-control', 'readonly' => 'true')); ?>
            <?php echo $form->error($model, 'fecha'); ?>
        </div>

        <?php echo $form->labelEx($model, 'publicar', array('class' => 'col-sm-1 control-label')); ?>
        <div class="col-sm-1">
            <?php echo $form->checkBox($model, 'publicar', array('size' => 1, 'value' => 's', 'maxlength' => 1, 'class' => 'form-control')); ?>

            <?php echo $form->error($model, 'publicar'); ?>
        </div>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Guardar', array('class' => 'btn btn-danger')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->