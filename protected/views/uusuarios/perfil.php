<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/jquery.fancybox.css?v=2.1.4" type="text/css"/>               
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css"/>        
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css"/>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/jquery.validate.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/css/custom-theme/jquery-ui-1.10.0.custom.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/mask.js" type="text/javascript" charset="utf-8"></script>

<!-- Add fancyBox -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/jquery.fancybox.pack.js?v=2.1.4"></script>
<!-- Optionally add helpers - button, thumbnail and/or media -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.5"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/sketch.js"></script>
<script type="text/javascript">
    $(function () {
        $('#usuarios-form').validate({
            submitHandler: function (form) {
                //console.log('enter submit');
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
    .tl_seccion{
        background-color:#aa1f2c;
        padding:5px 28px;
        font-family:Arial, Helvetica, sans-serif;
        font-size:21px;
        color:#fff;
        font-weight:bold;
        margin-left:16px;
        margin-top:20px;
        width: 80%;
    }

</style>
<div class="container">
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
    <div class="row">
        <div class="col-md-8">
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="info">
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
            <?php else: ?>
                <h1 class="tl_seccion">Editar tu perfil <?php echo $model->apellido . ' ' . $model->nombres ?></h1>
                <div class="form">

                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'usuarios-form',
                        'enableAjaxValidation' => true,
                        'clientOptions' => array(
                            'validateOnSubmit' => false,
                            'validateOnChange' => false,
                            'validateOnType' => false,
                        ),
                        'htmlOptions' => array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data',)
                    ));
                    ?>


                    <?php echo $form->errorSummary($model); ?>

                    <div class="form-group">
                        <label class="col-sm-2 control-label required" for="Usuarios_cargo_id">Área</label>
                        <div class="col-sm-4">
                            <label><?php echo ($model->cargo->area->tipo == 1) ? 'AEKIA' : 'CONCESIONARIO'; ?> </label>
                        </div>

                        <?php echo $form->labelEx($model, 'cargo_id', array('class' => 'col-sm-2 control-label')); ?>
                        <div class="col-sm-4">
                            <label><?php echo $model->cargo->descripcion; ?> </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class = 'col-sm-2 control-label'>Grupo</label>
                        <div class="col-sm-4">
                            <label><?php
                                echo $model->grupo->nombre_grupo;
                                ?></label>	

                        </div>
                        <label class = 'col-sm-2 control-label'>Concesionario</label>
                        <div class="col-sm-4">
                            <label><?php
                                if ($model->concesionario_id > 0 && !empty($model->concesionario_id))
                                    echo $model->consecionario->nombre;
                                else {
                                    $concesionarios = Grupoconcesionariousuario::model()->findAll(array('condition' => 'usuario_id =' . (int) $model->id));
                                    if (!empty($concesionarios)) {
                                        echo '<ul>';
                                        foreach ($concesionarios as $c) {
                                            echo '<li> - ' . $c->consecionario->nombre . '</li>';
                                        }
                                        echo '</ul>';
                                    }
                                }
                                ?></label>	

                        </div>
                    </div>

                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'nombres', array('class' => 'col-sm-2 control-label')); ?>
                        <div class="col-sm-10">
                            <?php echo $form->textField($model, 'nombres', array('size' => 60, 'maxlength' => 250, 'class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'nombres'); ?>
                        </div>

                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'foto', array('class' => 'col-sm-2 control-label')); ?>
                        <div class="col-sm-4">
                            <?php echo CHtml::activeFileField($model, 'foto', array("class" => "form-control subir")); ?>
                            <?php echo $form->error($model, 'foto'); ?>
                        </div>
                        <?php echo $form->labelEx($model, 'correo', array('class' => 'col-sm-2 control-label')); ?>
                        <div class="col-sm-4">
                            <label><?php echo $model->correo; ?></label>
                            <?php echo $form->error($model, 'correo'); ?>
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
                            <?php echo $form->textField($model, 'celular', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'celular'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'telefono', array('class' => 'col-sm-2 control-label')); ?>
                        <div class="col-sm-4">
                            <?php echo $form->textField($model, 'telefono', array('size' => 15, 'maxlength' => 15, 'class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'telefono'); ?>
                        </div>
                        <?php echo $form->labelEx($model, 'extension', array('class' => 'col-sm-2 control-label')); ?>
                        <div class="col-sm-4">
                            <?php echo $form->textField($model, 'extension', array('size' => 10, 'maxlength' => 10, 'class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'extension'); ?>
                        </div>

                    </div>

                    <div class="form-group">
                        <label class = 'col-sm-8 control-label'>Para actualizar la informaci&oacute;n ingrese su contrase&ntilde;a <span class="required">*</span></label>
                        <div class="col-sm-4">
                            <input type="password" name="repetirpass" id="repetirpass" class="form-control" autocomplete="off" placeholder="Ingresa tu contrase&ntilde;a aqu&iacute;">
                        </div>
                    </div>
                    <?php
                    //die('id usuario: '.$model->id);
                    $criteria = new CDbCriteria(array("condition" => "id = {$model->id}"));
                    $user = Usuarios::model()->find($criteria);
                    $firma = $user->firma;
                    //echo 'firma: '.$firma;
                    ?>
                    <?php if (empty($firma)): ?>
                        <div class="form-group">
                            <label for="" class="col-sm-3">Firma</label>
                            <div class="col-sm-4" id="cont-firma">
                                <a href="#inline1" class="fancybox btn btn-xs btn-primary">Ingresar Firma</a>      
                            </div>
                            <div class="col-md-5" id="cont-firma-img" style="display: none;">
                                <img src="" alt="" width="200" height="100" id="img-firma">
                                <hr>
                                Firma Asesor
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($firma)): ?>

                    <?php endif; ?>
                    <?php if (Yii::app()->user->hasFlash('error')) { ?>
                        <div class="infos">
                            <?php echo Yii::app()->user->getFlash('error'); ?>
                        </div>
                    <?php } ?>
                    <div class="row buttons">
                        <?php echo CHtml::submitButton($model->isNewRecord ? 'Actualizar' : 'Actualizar', array('class' => 'btn btn-danger')); ?>
                        <input type="hidden" name="Usuarios[firma]" id="Usuarios_firma" value=""/>
                    </div>

                    <?php $this->endWidget(); ?>

                </div><!-- form -->
            <?php endif; ?>
        </div>
        <div class="col-md-3 col-md-offset-1 cont_der">
            <?php if (!empty($model->foto)) { ?>
                <div>
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/upload/perfiles/<?php echo $model->foto; ?>" width="205" height="250">
                </div>
            <?php } ?>
            <p class="border-bt">Tambi&eacute;n puedes ir a:</p>
            <ul>
                <li><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></li>
                <li><a href="javascript:history.back(1)" class="back-btn-go">Volver</a></li>
            </ul>
        </div>
    </div>
</div>
<script>
    var eeec = 0;
    var eee = 1;
    $(function () {
        //$("#Usuario_cedula").mask('9999999999');
        //$("#Usuario_fechaNacimiento").mask('99/99/1999');
        $(".datepicker").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: new Date(1950, 10 - 1, 25),
            yearRange: '1970:2016'
        });
        $("#Usuarios_celular").mask('(09)-999-99999');
        $("#Usuarios_telefono").mask('(09)-999-9999');


    });


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
    });
    $("#Usuarios_usuario").change(function () {
        // verificaNick($("#Usuarios_usuario").val());
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
</script>