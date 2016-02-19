<script src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/jquery.validate.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/css/custom-theme/jquery-ui-1.10.0.custom.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/jquery.fancybox.css?v=2.1.4" type="text/css"/>               
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css"/>        
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css"/>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/mask.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<!-- Add fancyBox -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/jquery.fancybox.pack.js?v=2.1.4"></script>
<!-- Optionally add helpers - button, thumbnail and/or media -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.5"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/sketch.js"></script>
<script type="text/javascript">
    $(function () {
        $('#Usuarios_celular').keyup(function(){
           $('#celular2').hide(); 
        });
        $('#Usuarios_usuario').keyup(function () {
            this.value = this.value.toLowerCase();
        });
        $('#usuarios-form').validate({
            rules:{
                'area':{required:true},'Usuarios[cedula]':{required:true},'Usuarios[apellido]':{required:true},'Usuarios[nombres]':{required:true},
                'Usuarios[usuario]':{required:true},'Usuarios[fechaingreso]':{required:true},'Usuarios[correo]':{required:true, email:true},
                'Usuarios[fechanacimiento]':{required:true},'Usuarios[celular]':{required:true},'Usuarios[telefono]':{required:true},
                'Usuarios[extension]':{required:true},'Usuarios[codigo_asesor]':{required:true}
            },
            messages: {
                'area':{required:'Seleccione una ubicación'},'Usuarios[cedula]':{required:'Ingrese su cédula'},'Usuarios[apellido]':{required:'Ingrese su apellido'},'Usuarios[nombres]':{required:'Ingrese su nombre'},
                'Usuarios[usuario]':{required:'Ingrese su nickname'},'Usuarios[fechaingreso]':{required:'Ingrese su fecha de ingreso'},'Usuarios[correo]':{required:'Ingrese su email', email:'Ingrese un email válido'},
                'Usuarios[fechanacimiento]':{required:'Ingrese su fecha de nacimiento'},'Usuarios[celular]':{required:'Ingrese su celular'},'Usuarios[telefono]':{required:'Ingrese su teléfono'},
                'Usuarios[extension]':{required:'Ingrese su extensión'},'Usuarios[codigo_asesor]':{required:'Ingrese su código'}
            },
            submitHandler: function (form) {
                var celular = $('#Usuarios_celular').val();
                var telefono = $('#Usuarios_telefono').val();
                var celular_t = celular.substring(0,4);
                var telefono_t = telefono.substring(0,4);
                if(celular_t != '(09)'){
                    $('#celular2').show(); 
                    $('#Usuarios_celular').focus();
                    return false;
                }
                if(telefono_t == '(01)' || telefono_t == '(00)'){
                    alert('Ingrese correctamente su teléfono');
                    $('#Usuarios_telefono').focus();
                    return false;
                }
                //console.log('enter submit');
                if ($("#Usuarios_cargo_id").val() > 0) {
                    //return true;
                } else {
                    alert('Debe seleccionar un cargo.');
                    return false;
                }
                if ($("#Usuarios_grupo_id").val() > 0) {
                    //return true;
                } else {
                    alert('Debe seleccionar un grupo de concesionarios.');
                    return false;
                }

                var firma = $('#Usuarios_firma').val();
                if (firma == '') { // debe firma
                    alert('Debe ingresar y seleccionar una firma');
                    return false;
                } else { //  cliente no desea hacer el test drive
                    form.submit();
                }
            }
        });
        $('.fancybox').fancybox();
        $('#colors_sketch').sketch();
        var sktch = $('#colors_sketch').sketch();
        var cleanCanvas = $('#colors_sketch')[0];

        //Get the canvas &
        var c = $('#colors_sketch');
        var ct = c.get(0).getContext('2d');
        var container = $(c).parent();
        $('.reset-canvas').click(function () {
            var cnv = $('#colors_sketch').get(0);
            var ctx = cnv.getContext('2d');
            clearCanvas(cnv, ctx); // note, this erases the canvas but not the drawing history!
            $('#colors_sketch').sketch().actions = [10]; // found it... probably not a very nice solution though

        });
    });

    function validateUploadSize(x) {
        //var x = document.getElementById("myFile");
        var txt = "";
        if ('files' in x) {
            if (x.files.length == 0) {
                txt = "Select one or more files.";
            } else {
                for (var i = 0; i < x.files.length; i++) {
                    var file = x.files[i];
                    if (file.size > 2097152) {
                        return false;
                    } else {
                        return true;
                    }
                }
            }
        }
    }
    function clearCanvas(canvas, context) {
        context.clearRect(0, 0, canvas.width, canvas.height);
        // Store the current transformation matrix
        context.save();

        // Use the identity matrix while clearing the canvas
        context.setTransform(1, 0, 0, 1, 0, 0);
        context.clearRect(0, 0, canvas.width, canvas.height);

        // Restore the transform
        context.restore();
        context.beginPath();
    }

    function UploadPic() {

        // generate the image data
        var data = document.getElementById("colors_sketch").toDataURL("image/png");
        var output = data.replace(/^data:image\/(png|jpg);base64,/, "");
        // Sending the image data to Server
        if (confirm("Antes de continuar, esta seguro que ha realizado su firma correctamente?")) {
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: '<?php echo Yii::app()->createUrl("/site/grabarFirmaAjax") ?>',
                data: {imageData: output, id_informacion: "0", tipo: 1},
                success: function (data) {
                    if (data.result == true) {
                        alert('Firma cargada exitosamente.');
                        //$(location).attr('href', ur)
                        $('#cont-firma').hide();
                        $.ajax({
                            type: 'POST',
                            dataType: "json",
                            url: '<?php echo Yii::app()->createUrl("/site/getFirmaAjax") ?>',
                            data: {id: data.id},
                            success: function (data) {
                                //console.log('firma digital: '+data.firma);
                                $('#img-firma').attr('src', '/intranet/usuario/upload/firma/' + data.firma);
                                $('#Usuarios_firma').val(data.firma);
                                $('#cont-firma').hide();
                                $('#cont-firma-img').show();
                                //$('#cont-btn').show();
                            }
                        });
                        $.fancybox.close();
                    }
                }
            });

        }
    }
</script>
<style>
    .form-control{
        color:#555555 !important;
    }
    .ui-datepicker th {color: white !important;}
</style><style>
    #cometchat {
        display: none;
    }
</style>
<div id="inline1" style="width:800px;display: none;height: 400px;">
    <div class="row">
        <h1 class="tl_seccion_rf">Ingreso de firma</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <canvas id="colors_sketch" width="800" height="300"></canvas>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="tools">
                <!--<a href="#colors_sketch" data-download="png" class="btn btn-success">Descargar firma</a>-->
                <input type="button"  data-clear='true' class="reset-canvas btn btn-warning" value="Borrar Firma">
                <input type="button"  onclick="UploadPic()" class=" btn btn-info" value="Subir Firma">
            </div>
        </div>
    </div>
</div>
<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'usuarios-form',
        'enableAjaxValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => false,
            'validateOnChange' => false,
            'validateOnType' => false,
        ),
        'htmlOptions' => array('class' => 'form-horizontal')
    ));
    ?>


    <?php echo $form->errorSummary($model); ?>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="Cargo_area_id">Ubicaci&oacute;n</label>
        <div class="col-sm-4">
            <?php
            echo '<select required id="cboAreaP" class="form-control" onchange="traerArea(this.value)" name="area">';
            echo '<option value="0">--Seleccione la Ubicaci&oacute;n--</option>';
            echo '<option value="1">AEKIA</option>';
            echo '<option value="2">CONCESIONARIO</option>';
            echo '</select>';
            ?> 

        </div>
        <label class = 'col-sm-2 control-label'>&Aacute;rea</label>
        <div class="col-sm-4">
            <div id="dArea"> <p style="font-size:13px;font-weight:bold;padding-top:5px">-- Seleccione una Ubicaci&oacute;n --</p></div>
            <?php echo $form->error($model, 'area_id'); ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'cargo_id', array('class' => 'col-sm-2 control-label')); ?>
        <div class="col-sm-10">
            <?php //echo $form->dropDownList($model,'cargo_id', CHtml::listData(Cargo::model()->findAll(), 'id', 'descripcion'), array('empty'=>'Seleccione >>','class'=>'form-control'))  ?> 
            <div id="ccargos"><h6>Primero seleccione un &aacute;rea por favor</h6></div>
            <?php echo $form->error($model, 'cargo_id'); ?>
        </div>
    </div>
    <div class="form-group">
        <label class = 'col-sm-2 control-label'>Grupo Concesionarios</label>
        <div class="col-sm-10">
            <select class = 'form-control' name="Usuarios[grupo_id]" id="Usuarios_grupo_id" onchange="buscarConcesionario(this.value)">
                <option> -- Seleccione -- </option>
                <?php
                $grupo = GrGrupo::model()->findAll();
                if (!empty($grupo)) {
                    foreach ($grupo as $c) {
                        echo '<option value="' . $c->id . '">' . $c->nombre_grupo . '</option>';
                    }
                }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <?php //echo $form->labelEx($model, 'dealers_id', array('class' => 'col-sm-2 control-label')); ?>
        <label class="col-sm-2 control-label" for="Usuarios_dealers_id">Concesionarios 
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/usuarios/help.png" style="cursor:pointer;" onclick="mensaje('Para seleccionar m&aacute;s de un elemento presione la tecla CTRL y realice clic en los items que desea agregar.')">
        </label>
        <div class="col-sm-10">
            <div id="concesionarioscbo"><h6> -- Primero seleccione un <b>Grupo de Concesionario</b> -- </h6></div>
            <?php echo $form->error($model, 'dealers_id'); ?>
        </div>

    </div>
    <div class="form-group" id="descripcionUbicaciones" style="display:none">
        <label class = "col-sm-2 control-label">Provincia</label>
        <div class="col-sm-4">
            <p style="font-size:13px; font-weight:bold;padding-top:-5px;">PICHINCHA</p>
            <input type="hidden" name="Usuarios[provincia_id]" id="Usuarios_provincia_id">
        </div>
        <label class = "col-sm-2 control-label">Direcci&oacute;n</label>
        <div class="col-sm-4">
            <p style="font-size:13px; font-weight:bold;padding-top:-5px;">PICHINCHA</p>
        </div>
    </div>
  
        <div class="form-group">
        <label class="col-sm-2 control-label required" for="Usuarios_cedula">Cédula <span class="required">*</span></label>        
        <div class="col-sm-10">
            <input type="tel" size="10" maxlength="10" min="10" max="10" class="form-control" onkeypress="return numeros(event);"  name="Usuarios[cedula]" id="Usuarios_cedula">            
            <div id="errorCedula" style="display:none;color: red;position: relative;top: 0px;left: 2px;font-size:11px">Cédula ingresada es incorrecta.</div>
                    
        </div>
    </div>


    <div class="form-group">
        <?php echo $form->labelEx($model, 'nombres', array('class' => 'col-sm-2 control-label')); ?>
        <div class="col-sm-4">
            <?php echo $form->textField($model, 'nombres', array('size' => 60, 'maxlength' => 250, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'nombres'); ?>
        </div>
        <?php echo $form->labelEx($model, 'apellido', array('class' => 'col-sm-2 control-label')); ?>
        <div class="col-sm-4">
            <?php echo $form->textField($model, 'apellido', array('size' => 60, 'maxlength' => 250, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'apellido'); ?>
        </div>

    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'usuario', array('class' => 'col-sm-2 control-label', 'style' => 'text-transform: lowercase;')); ?>
        <div class="col-sm-4">
            <?php echo $form->textField($model, 'usuario', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
            <div id="errorUser" style="display:none;color: red;position: relative;top: 0px;left: 2px;font-size:11px">Ya existe ese nombre de usuario. Vuelva a intentarlo.</div>
            <?php echo $form->error($model, 'usuario'); ?>
        </div>
        <?php echo $form->labelEx($model, 'fechaingreso', array('class' => 'col-sm-2 control-label')); ?>
        <div class="col-sm-4">
            <?php echo $form->textField($model, 'fechaingreso', array('size' => 60, 'maxlength' => 45, 'readonly' => 'true', 'class' => 'form-control datepicker')); ?>
            <?php echo $form->error($model, 'fechaingreso'); ?>
        </div>

    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'correo', array('class' => 'col-sm-2 control-label')); ?>
        <div class="col-sm-4">
            <?php echo $form->textField($model, 'correo', array('size' => 60, 'maxlength' => 150, 'class' => 'form-control')); ?>
            <div id="errorEmail" style="display:none;color: red;position: relative;top: 0px;left: 2px;font-size:11px">Correo Inv&aacute;lido.</div>
            <?php echo $form->error($model, 'correo'); ?>
        </div>
        <label class="col-sm-2 control-label required" for="">Repetir Correo <span class="required">*</span></label>
        <div class="col-sm-4">
            <input size="60" maxlength="150" class="form-control" name="rcorreo" id="rcorreo" type="text" onpaste="alert('Los datos deben ser ingresados manualmente.');return false">			
            <div class="errorMessage" id="Usuarios_correo_em_" style="display:none"></div>		
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'fechanacimiento', array('class' => 'col-sm-2 control-label')); ?>
        <div class="col-sm-4">

            <?php echo $form->textField($model, 'fechanacimiento', array('size' => 45, 'maxlength' => 45, 'readonly' => 'true', 'class' => 'form-control datepicker')); ?>
            <?php echo $form->error($model, 'fechanacimiento'); ?>
        </div>
        <?php echo $form->labelEx($model, 'celular', array('class' => 'col-sm-2 control-label')); ?>
        <div class="col-sm-4">
            <input size="10" maxlength="10" class="form-control" placeholder="09999999999" name="Usuarios[celular]" id="Usuarios_celular" type="tel" onkeypress="return numeros(event);">
            <label class="error" style="display: none;" id="celular2">Ingrese correctamente su celular</label>
            <?php echo $form->error($model, 'celular'); ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'telefono', array('class' => 'col-sm-2 control-label')); ?>
        <div class="col-sm-4">
            <input  maxlength="9" min="9" max="9" placeholder="022222222" class="form-control" name="Usuarios[telefono]" onkeypress="return numeros(event);" id="Usuarios_telefono" type="tel">                    

            <?php echo $form->error($model, 'telefono'); ?>
        </div>
        <?php echo $form->labelEx($model, 'extension', array('class' => 'col-sm-2 control-label')); ?>
        <div class="col-sm-4">
            <?php echo $form->textField($model, 'extension', array('size' => 10, 'maxlength' => 10, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'extension'); ?>
        </div>

    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'codigo_asesor', array('class' => 'col-sm-2 control-label')); ?>
        <div class="col-sm-4">
            <?php echo $form->textField($model, 'codigo_asesor', array('size' => 15, 'maxlength' => 15, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'codigo_asesor'); ?>
        </div>

    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="Usuarios_codigo_asesor">Firma</label>
        <div class="col-sm-4" id="cont-firma">
            <a href="#inline1" class="fancybox btn btn-xs btn-primary">Ingresar Firma</a>      
        </div>
        <div class="col-md-5" id="cont-firma-img" style="display: none;">
            <img src="" alt="" width="200" height="100" id="img-firma">
            <hr>
            Firma Asesor
        </div>
    </div>
    <div class="formRegisterR">
        <div class="row-fluid">            
            <div class="span12">
                <?php
                $this->widget('application.extensions.recaptcha.EReCaptcha', array(
                    'model' => $model,
                    'attribute' => 'body',
                    'theme' => 'black', 'language' => 'es_ES',
                    'publicKey' => Yii::app()->params['recaptcha']['publicKey'],
                    'htmlOptions' => array('width' => 100)
                ))
                ?>
            </div>            
        </div>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Registrar' : 'Actualizar', array('class' => 'btn btn-danger', 'id' => 'btnSubmit')); ?>
        <input type="hidden" name="Usuarios[firma]" id="Usuarios_firma" value=""/>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
<script src="<?php echo Yii::app()->baseUrl ?>/js/bootbox.min.js"></script>
<script>
                var eeec = 1;
                var eee = 1;
                function mensaje(vl) {
                    bootbox.alert(vl);
                }
                $(function () {
                    //			$("#btnSubmit").hide();
                    
                   // $("#Usuarios_cedula").mask('9999999999');
                    //$("#Usuario_fechaNacimiento").mask('99/99/1999');
                    $(".datepicker").datepicker({
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: 'dd-mm-yy',
                        minDate: new Date(1950, 10 - 1, 25),
                        yearRange: '1950:2016'
                    });
                    $.datepicker.regional['es'] = {
                        closeText: 'Cerrar',
                        prevText: '<Ant',
                        nextText: 'Sig>',
                        currentText: 'Hoy',
                        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
                        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
                        weekHeader: 'Sm',
                        dateFormat: 'dd/mm/yy',
                        firstDay: 1,
                        isRTL: false,
                        showMonthAfterYear: false,
                        yearSuffix: ''
                    };
                    $.datepicker.setDefaults($.datepicker.regional['es']);
                    //$("#Usuarios_celular").mask('(09)-999-99999');
                    //$("#Usuarios_telefono").mask('(09)-999-9999');

                    $('#usuarios-form').submit(function () {
                        verificaNick($("#Usuarios_usuario").val());
                        if (validateEmail($("#Usuarios_correo").val())) {
                            $("#errorEmail").hide();
                            eee = 0;
                        } else {
                            $("#errorEmail").html("Correo inv&aacute;lido.");
                            $("#errorEmail").show();
                            eee = 1;
                        }
                        if ($("#Usuarios_correo").val() != $("#rcorreo").val()) {
                            $("#errorEmail").html("Correos ingresados no son identicos.");
                            $("#errorEmail").show();
                            $("#rcorreo").css({"border": "1px solid red"});
                            eee = 1;
                        }

                        if (eee == 0 && eeec == 0) {
                            return true;

                        } else {
                            return false;
                        }
                        alert('Existen errores en ciertos campos, debe corregirlos');
                        return false;
                    });
                });

                function buscarDealer(vl) {
                    //concesionarioscbo
                    $.ajax({
                        url: '<?php echo Yii::app()->createUrl("site/traerconsesionario") ?>',
                        type: 'POST',
                        async: true,
                        data: {
                            rs: vl,
                        },
                        success: function (result) {
                            if (result == 0) {
                                alert("No se pudo realizar la consulta de concesionarios.");

                            } else {
                                $("#concesionarioscbo").html(result);
                            }
                        }
                    });
                }
                function buscarConcesionario(vl) {
                    //concesionarioscbo
                    $.ajax({
                        url: '<?php echo Yii::app()->createUrl("site/traerconsesionario") ?>',
                        type: 'POST',
                        async: true,
                        data: {
                            rs: vl,
                        },
                        success: function (result) {
                            if (result == 0) {
                                alert("No se pudo realizar la consulta de concesionarios.");

                            } else {
                                $("#concesionarioscbo").html(result);
                            }
                        }
                    });
                }
                function buscarCargo(vl) {
                    //concesionarioscbo
                    $.ajax({
                        url: '<?php echo Yii::app()->createUrl("site/traercargos") ?>',
                        type: 'POST',
                        async: true,
                        data: {
                            rs: vl,
                        },
                        success: function (result) {
                            if (result == 0) {
                                alert("No se pudo realizar la consulta de cargos.");
                                $("#ccargos").html("<p style='font-size:13px;font-weight:bold;padding-top:5px;'>Seleccione un &aacute;rea.</p>");
                            } else {
                                //alert(result)
                                $("#ccargos").html(result);
                            }
                        }
                    });
                }
                function verificaNick(vl) {
                    //concesionarioscbo
                    if (vl.length > 0) {
                        $.ajax({
                            url: '<?php echo Yii::app()->createUrl("/site/verificaNick") ?>',
                            type: 'POST',
                            async: false,
                            data: {
                                rs: vl,
                            },
                            success: function (result) {
                                if (result == 1) {
                                    //alert("Este usuario ya se encuentra en uso.");
                                    $("#errorUser").show();
                                    $("#Usuarios_usuario").css({"border": '1px border red'});
                                    eeec = 1;
                                } else {
                                    $("#errorUser").hide();
                                    eeec = 0;
                                }
                            }
                        });
                    }
                }
                function verificaEmail(vl) {
                    //concesionarioscbo
                    if (vl.length > 0) {
                        $.ajax({
                            url: '<?php echo Yii::app()->createUrl("/site/verificaEmail") ?>',
                            type: 'POST',
                            async: false,
                            data: {
                                rs: vl,
                            },
                            success: function (result) {
                                if (result == 1) {
                                    //alert("Este usuario ya se encuentra en uso.");
                                    $("#errorEmail").html("El correo ingresado ya se encuentra en uso.");
                                    $("#errorEmail").show();
                                    $("#Usuarios_correo").css({"border": '1px border red'});
                                    eee = 1;
                                } else {
                                    $("#errorEmail").hide();
                                    eee = 0;
                                }
                            }
                        });
                    }
                }
                function validateEmail($email) {
                    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                    if (!emailReg.test($email)) {
                        return false;
                    } else {
                        return true;
                    }
                }
                $("#Usuarios_correo").change(function () {
                    if (validateEmail($("#Usuarios_correo").val())) {
                        $("#errorEmail").hide();
                        eee = 0;
                    } else {
                        $("#errorEmail").show();
                        eee = 1;
                    }
                    verificaEmail($("#Usuarios_correo").val());
                });
                $("#Usuarios_usuario").change(function () {
                    verificaNick($("#Usuarios_usuario").val());
                });
                $("#Usuarios_cedula").change(function () {
                    if (CedulaValida($("#Usuarios_cedula").val())) {
                        $("#errorCedula").hide();
                        eee = 0;
                    } else {
                        $("#errorCedula").show();
                        eee = 1;
                    }
                });
                function CedulaValida(cedula) {

                    //Si no tiene el gui?n, se lo pone para la validaci?n
                    if (cedula.match(/\d{10}/)) {
                        cedula = cedula.substr(0, 9) + "-" + cedula.substr(9);
                    }

                    //Valida que la c?dula sea de la forma ddddddddd-d
                    if (!cedula.match(/^\d{9}-\d{1}$/))
                        return false;

                    //Valida que el # formado por los dos primeros d?gitos est? entre 1 y 24
                    var dosPrimerosDigitos = parseInt(cedula.substr(0, 2), 10);
                    if (dosPrimerosDigitos < 1 || dosPrimerosDigitos > 24)
                        return false;
                    //Valida que el valor acumulado entre los primeros 9 n?meros coincida con el ?ltimo
                    var acumulado = 0, digito, aux;
                    for (var i = 1; i <= 9; i++) {
                        digito = parseInt(cedula.charAt(i - 1));
                        if (i % 2 == 0) { //si est? en una posici?n par
                            acumulado += digito;
                        } else { //si est? en una posici?n impar
                            aux = 2 * digito;
                            if (aux > 9)
                                aux -= 9;
                            acumulado += aux;
                        }
                    }
                    acumulado = 10 - (acumulado % 10);
                    if (acumulado == 10)
                        acumulado = 0;
                    var ultimoDigito = parseInt(cedula.charAt(10));
                    if (ultimoDigito != acumulado)
                        return false;
                    //				alert('asd');

                    //La c?dula es v?lida
                    return true;
                    //		alert('bien');
                }
                function traerArea(vl) {
                    if (vl > 0) {
                        $.ajax({
                            url: '<?php echo Yii::app()->createUrl("/site/traerArea") ?>',
                            type: 'POST',
                            async: false,
                            data: {
                                rs: vl,
                            },
                            success: function (result) {
                                if (result != 0) {
                                    $("#dArea").html(result);
                                    buscarCargo($("#Cargo_area_id").val());
                                    $("#btnSubmit").show();
                                } else {
                                    $("#dArea").html('Seleccione una Ubicacion por favor.');
                                }
                            }
                        });
                    }
                }
                function verciudadcon(vl) {
                    $("#descripcionUbicaciones").hide();

                    if (vl > 0) {
                        $.ajax({
                            url: '<?php echo Yii::app()->createUrl("/site/traerciudadcon") ?>',
                            type: 'POST',
                            async: false,
                            data: {
                                rs: vl,
                            },
                            success: function (result) {
                                if (result != 0) {
                                    $("#descripcionUbicaciones").html(result);
                                    $("#descripcionUbicaciones").show();
                                    if ($('#Usuarios_concesionario_id :selected').length > 1) {
                                        $('#pppc').html('--');
                                    }
                                } else {
                                    $("#descripcionUbicaciones").html('Se produjo un error vuelva a cargar el sitio e intente nuevamente.');
                                }
                            }
                        });
                    }

                }
                 function numeros(evt)
    {
        var code = (evt.which) ? evt.which : evt.keyCode;
        if(code==8)
        {
            //backspace
            return true;
        }
        else if(code>=48 && code<=57)
        {
            //is a number
            return true;
        }
        else
        {
            return false;
        }
    }
    $( "#Usuarios_celular" ).focus(function() {
        celular(this.id)
    });
    function celular(vl){
        $("#"+vl).val('09');
    }
  
</script>