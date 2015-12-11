<script src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/jquery.validate.js" type="text/javascript" charset="utf-8"></script>
<?php
/* @var $this CasosController */
/* @var $model Casos */
/* @var $form CActiveForm */
?>
<style>
    textarea{
        /*        width: 470px !important;*/
    }
</style>
<script>
    $(function () {
        $('.content-concec').hide();
        $('#info').hide();
        $('#info2').hide();$('#info3').hide();
        $('#Casos_subtema').change(function() {
            var value = $(this).attr('value');
            if(value == 9){
                $('.content-concec').show();
            }else{
                var dataModelo = '<option value="" selected="selected">--Escoja un Modelo--</option>\n\
                                  <option value="84">Picanto R</option>\n\
                                <option value="85">Rio R</option>\n\
                                <option value="24">Cerato Forte</option>\n\
                                <option value="90">Cerato R</option>\n\
                                <option value="89">Óptima Híbrido</option>\n\
                                <option value="88">Quoris</option>\n\
                                <option value="20">Carens R</option>\n\
                                <option value="11">Grand Carnival</option>\n\
                                <option value="21">Sportage Active</option>\n\
                                <option value="83">Sportage R</option>\n\
                                <option value="10">Sorento</option>\n\
                                <option value="25">K 2700 Cabina Simple</option>\n\
                                <option value="87">K 2700 Cabina Doble</option>\n\
                                <option value="86">K 3000</option>'
                var dataVersion = '<option value="" selected="selected">Escoja una versión</option>';        
                $('#Casos_modelo').html(dataModelo);
                $('#Casos_version').html(dataVersion);

                $('.content-concec').hide();
            }
        });
        
        $('#Casos_modelo').change(function() {
            var value = $(this).attr('value');
            $.ajax({
                url:'https://www.kia.com.ec/intranet/callcenter/index.php/versiones/getversiones',
                dataType: "json",
                data:{
                    id:value
                },
                type: 'post',
                success:function(data){
                    //alert(data.options)
                    $('#Casos_version').html(data.options);
                }
            });
        });
    });
</script>
<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'casos-form',
        'enableAjaxValidation' => true,
        'htmlOptions' => array('class' => 'form-horizontal')
            ));
    ?>


    <?php //echo $form->errorSummary($model); ?>
    <?php
    $criteria = new CDbCriteria(array(
                'order' => 'name'
            ));
    $menu = CHtml::listData(Menu::model()->findAll($criteria), "id", "name");
    ?>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'tema', array('class' => 'col-sm-2 control-label')); ?>
        <div class="col-sm-4">
            <?php echo $form->dropDownList($model, 'tema', $menu, array('empty' => 'Selecciona un tema', 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'tema'); ?>
        </div>
        <?php echo $form->labelEx($model, 'subtema', array('class' => 'col-sm-2 control-label')); ?>
        <div class="col-sm-4">
            <div id="info"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" alt=""></div>
            <?php echo $form->dropDownList($model, 'subtema', array('' => 'Selecciona un subtema'), array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'subtema'); ?>
        </div>

    </div>
    <fieldset>
        <legend class="scheduler-border">Datos Personales</legend>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'cedula', array('class' => 'col-sm-2 control-label')); ?>
            <div class="col-sm-4">
                <?php //echo $form->textField($model, 'cedula', array('size' => 10, 'maxlength' => 10, 'class' => 'form-control')); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                    'name' => 'Casos[cedula]',
                    'value' => $model->cedula,
                    'sourceUrl' => array('casos/getcedula'), //path of the controller
                    'options' => array(
                        'minLength' => '1', // min chars to start search
                        'select' => 'js:function(event, ui) { 
                            jQuery("#Casos_nombres").val(ui.item.nombres);
                            jQuery("#Casos_apellidos").val(ui.item.apellidos);
                            jQuery("#Casos_email").val(ui.item.email);
                            jQuery("#Casos_telefono").val(ui.item.telefono);
                            jQuery("#Casos_celular").val(ui.item.celular);
                            jQuery("#Casos_direccion").val(ui.item.direccion);
                            jQuery("#Casos_sector").val(ui.item.sector);
                            }', // when the value selected
                        'showAnim' => 'fold'
                    ),
                    'htmlOptions' => array(
                        'id' => 'Casos_cedula',
                        'rel' => 'val',
                        'class' => 'form-control'
                    ),
                ));
                ?>
                <?php echo $form->error($model, 'cedula'); ?>
            </div>
            <?php echo $form->labelEx($model, 'email', array('class' => 'col-sm-2 control-label')); ?>
            <div class="col-sm-4">
                <?php echo $form->textField($model, 'email', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'email'); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'nombres', array('class' => 'col-sm-2 control-label')); ?>
            <div class="col-sm-4">
                <?php echo $form->textField($model, 'nombres', array('size' => 60, 'maxlength' => 150, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'nombres'); ?>
            </div>
            <?php echo $form->labelEx($model, 'apellidos', array('class' => 'col-sm-2 control-label')); ?>
            <div class="col-sm-4">
                <?php echo $form->textField($model, 'apellidos', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'apellidos'); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'telefono', array('class' => 'col-sm-2 control-label')); ?>
            <div class="col-sm-4">
                <?php echo $form->textField($model, 'telefono', array('size' => 50, 'maxlength' => 9, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'telefono'); ?>
            </div>
            <?php echo $form->labelEx($model, 'celular', array('class' => 'col-sm-2 control-label')); ?>
            <div class="col-sm-4">
                <?php echo $form->textField($model, 'celular', array('size' => 50, 'maxlength' => 10, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'celular'); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'direccion', array('class' => 'col-sm-2 control-label')); ?>
            <div class="col-sm-4">
                <?php echo $form->textField($model, 'direccion', array('size' => 60, 'maxlength' => 150, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'direccion'); ?>
            </div>
            <?php echo $form->labelEx($model, 'sector', array('class' => 'col-sm-2 control-label')); ?>
            <div class="col-sm-4">
                <?php echo $form->textField($model, 'sector', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'sector'); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'ciudad_domicilio', array('class' => 'col-sm-2 control-label')); ?>
            <div class="col-sm-4">
                <?php echo $form->textField($model, 'ciudad_domicilio', array('size' => 60, 'maxlength' => 150, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'ciudad_domicilio'); ?>
            </div>

        </div>
    </fieldset>

    <fieldset>
        <legend class="scheduler-border">Datos Concesionario</legend>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'provincia', array('class' => 'col-sm-2 control-label')); ?>
            <div class="col-sm-4">
                <?php
                $criteria = new CDbCriteria(array(
                            'condition' => "estado='s'",
                            'order' => 'nombre'
                        ));
                $provincias = CHtml::listData(Provincias::model()->findAll($criteria), "id_provincia", "nombre");
                ?>
                <?php echo $form->dropDownList($model, 'provincia', $provincias, array('class' => 'form-control', 'empty' => 'Selecciona una provincia')); ?>
                <?php echo $form->error($model, 'provincia'); ?>
            </div>
            <?php echo $form->labelEx($model, 'ciudad', array('class' => 'col-sm-2 control-label')); ?>
            <div class="col-sm-4">
                <div id="info2"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" alt=""></div>
                <?php echo $form->dropDownList($model, 'ciudad', array('value' => 'Seleccione'), array('class' => 'form-control')); ?>
                <?php echo $form->error($model, 'ciudad'); ?>
            </div>

        </div>
        <div class="form-group">
            <?php //echo $form->labelEx($model, 'concesionario', array('class' => 'col-sm-2 control-label')); ?>
            <label class="col-sm-2 control-label" for="Casos_concesionario">Concesionario <span class="required" style="position: absolute;right: -11px;top: 11px;">*</span></label>
            <div class="col-sm-4">
                <div id="info3"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" alt=""></div>
                <?php echo $form->dropDownList($model, 'concesionario', array('' => 'Concesionario'), array('class' => 'form-control')); ?>
                <?php echo $form->error($model, 'concesionario'); ?>
            </div>
        </div>
        <div class="form-group content-concec">
            <?php echo $form->labelEx($model, 'modelo', array('class' => 'col-sm-2 control-label')); ?>
            <div class="col-sm-4">
                <?php
                echo $form->dropDownList($model, 'modelo', array(
                    "" => "--Escoja un Modelo--",
                    "84" => "Picanto R",
                    "85" => "Rio R",
                    "24" => "Cerato Forte",
                    "90" => "Cerato R",
                    "89" => "Óptima Híbrido",
                    "88" => "Quoris",
                    "20" => "Carens R",
                    "11" => "Grand Carnival",
                    "21" => "Sportage Active",
                    "83" => "Sportage R",
                    "10" => "Sorento",
                    "25" => "K 2700 Cabina Simple",
                    "87" => "K 2700 Cabina Doble",
                    "86" => "K 3000"), array('class' => 'form-control'));
                ?>
                <?php echo $form->error($model, 'modelo'); ?>
            </div>
            <?php echo $form->labelEx($model, 'version', array('class' => 'col-sm-2 control-label')); ?>
            <div class="col-sm-4">
                <?php echo $form->dropDownList($model, 'version', array('' => 'Escoja una versión'), array('class' => 'form-control')); ?>
                <?php echo $form->error($model, 'version'); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'comentario', array('class' => 'col-sm-8 control-label')); ?>
            <div class="col-sm-12">
                <?php echo $form->textArea($model, 'comentario', array('rows' => 6, 'cols' => 50, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'comentario'); ?>
            </div>
        </div>
    </fieldset>
    <div class="form-group">
        <?php //echo $form->labelEx($model, 'tipo_form', array('class' => 'col-sm-2 control-label')); ?>
        <label class="col-sm-2 control-label required" for="Casos_tipo_form">Tipo Formulario <span class="required" style="position: absolute;right: 10px;top: 11px;">*</span></label>
        <div class="col-sm-4">
            <?php
            echo $form->dropDownList($model, 'tipo_form', array('informativo' => 'Informativo', 'caso' => 'Creación de Caso'), array('class' => 'form-control', 'empty' => '--Tipo de caso--'));
            ?>
            <?php echo $form->error($model, 'tipo_form'); ?>
        </div>
        <div class="tipo-venta">
            <?php echo $form->labelEx($model, 'tipo_venta', array('class' => 'col-sm-2 control-label')); ?>
            <div class="col-sm-4">
                <?php
                echo $form->dropDownList($model, 'tipo_venta', 
                        array('venta' => 'Venta',
                            'postventa' => 'Post Venta'), 
                        array('class' => 'form-control', 'empty' => '--Tipo Venta--'));
                ?>
                <?php echo $form->error($model, 'tipo_venta'); ?>
            </div>
        </div>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Save', array('class' => 'btn btn-danger')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->