<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.css" rel="stylesheet">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<?php
/* @var $this GestionTestDriveController */
/* @var $model GestionTestDrive */
/* @var $form CActiveForm */
$test_drive = '';
if (isset($model->test_drive))
    $test_drive = $model->test_drive;
?>

<?php
$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
$android = FALSE;
if (stripos($ua, 'android') !== false) { // && stripos($ua,'mobile') !== false) {
    $android = TRUE;
}
$criteria = new CDbCriteria(array(
    'condition' => "id='{$id_informacion}'"
        ));
$info = GestionInformacion::model()->findAll($criteria);
//echo '<pre>';
//print_r($info);
//echo '</pre>';
?>
<script type="text/javascript">
    var ident = '<?php echo $test_drive; ?>';
    console.log('IDENTIFICACION:-----------' + ident);
    $(function () {
        $('#agendamiento').datetimepicker({
            lang: 'es',
            onGenerate: function (ct) {
                $(this).find('.xdsoft_date.xdsoft_weekend')
                        .addClass('xdsoft_disabled');
            },
            weekends: ['01.01.2014', '02.01.2014', '03.01.2014', '04.01.2014', '05.01.2014', '06.01.2014'],
            minDate: '-1970/01/01', //yesterday is minimum date(for today use 0 or -1970/01/01)
            disabledDates: ['03.04.2015', '01.05.2015', '10.08.2015', '09.10.2015', '02.11.2015', '03.11.2015', '25.12.2015'], formatDate: 'd.m.Y'
        });
        $('#GestionTestDrive_test_drive').change(function () {
            var value = $(this).attr('value');
            if (value == 1) {
                $('.cont-img').show();
                $('.cont-obs').hide();
                $('.cont-bot-form-manejo').show();
            } else if (value == 2) {
                $('.cont-obs').show();
            } else {
                $('.cont-obs').show();
                $('.cont-img').hide();
                $('.cont-bot-form-manejo').hide();
                $('.cont-form-manejo').hide();
            }
        });
        $('.btn-ing-form').click(function () {
            $('.cont-form-manejo').show();
        });
        $('.btn-des-form').click(function () {
            $('.cont-form-manejo').hide();
        });
    });
    function send() {
        $('#gestion-diaria-form').validate({
            submitHandler: function (form) {
                var proximoSeguimiento = $('#agendamiento').val();
                //console.log('val of agendamiento: '+proximoSeguimiento);
                var dataform = $("#gestion-diaria-form").serialize();
                if (proximoSeguimiento != '') {
                    if ($('#GestionInformacion_check').val() != 2) {
                        console.log('enter gestion informacion check');
                        var params = proximoSeguimiento.split("/");
                        var fechaDate = params[0] + params[1] + params[2];
                        var params2 = fechaDate.split(":");
                        var endTime = parseInt(params2[1]) + 100;
                        endTime = endTime.toString();
                        var startTime = params2[0] + params2[1];
                        var href = '/intranet/ventas/index.php/gestionDiaria/calendar?date=' + fechaDate + '&startTime=' + startTime + '&endTime=' + endTime + '&subject=Cita con Cliente&desc=Cita con el cliente';
                        $('#event-download').attr('href', href);
                        $('#calendar-content').show();
                        $("#event-download").click(function () {
                            $('#GestionInformacion_calendar').val(1);
                            $('#calendar-content').hide();
                            $('#GestionInformacion_check').val(2)
                        });
                        if ($('#GestionInformacion_calendar').val() == 1) {
                            console.log('enter infomacion calendar');
                        } else {
                            alert('Debes descargar agendamiento y luego dar click en Continuar');
                        }
                    } else {
                        $.ajax({
                            url: '<?php echo Yii::app()->createAbsoluteUrl("gestionDiaria/createAjax"); ?>',
                            beforeSend: function (xhr) {
                                $('#bg_negro').show();  // #bg_negro must be defined somewhere
                            },
                            type: 'POST',
                            data: dataform,
                            success: function (data) {
                                $('#bg_negro').hide();
                                $('#cont-alert').show();
                                $('#gestion-diaria-form').get(0).reset();
                            }
                        });
                    }
                }
            }
        });
    }
</script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/css/jasny-bootstrap.css" rel="stylesheet"/>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/jasny-bootstrap.js"></script>
<div class="container">
    <div role="tabpanel">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">            
            <li role="presentation"><a aria-controls="profile" role="tab"><span>1</span> Prospección / <span>2</span> Cita</a></li>
            <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/recepcion.png" alt="" /></span> Recepción</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('gestionVehiculo/create/' . $id_informacion); ?>" aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/consulta.png" alt="" /></span> Consulta</a></li>
            <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/presentacion.png" alt="" /></span> Presentación</a></li>
            <li role="presentation" class="active"><a aria-controls="profile" role="tab"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/demostracion.png" alt="" /></span> Demostración</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/financiamiento/' . $id_informacion); ?>" aria-controls="settings" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/negociacion.png" alt="" /></span> Negociación</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/factura/' . $id_informacion); ?>" aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cierre.png" alt="" /></span> Cierre</a></li>
            <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/entrega.png" alt="" /></span> Entrega</a></li>
            <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/seguimiento.png" alt="" /></span>Seguimiento</a></li>
        </ul>
        <!-- Tab panels -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="profile">
                <div class="row">
                    <h1 class="tl_seccion_rf">Test Drive: Modelo - <?php echo $this->getModeloTestDrive($id); ?>, Versión - <?php echo $this->getVersionTestDrive($id); ?></h1>
                    <div class="highlight">

                        <div class="form">

                            <?php
                            $form = $this->beginWidget('CActiveForm', array(
                                'id' => 'gestion-test-drive-form',
                                'enableAjaxValidation' => false,
                                'htmlOptions' => array('enctype' => 'multipart/form-data'),
                            ));
                            ?>
<!--                            <p class="note">Campos con <span class="required">*</span> son requeridos.</p>-->
                            <?php //echo $form->errorSummary($model); ?>
                            <div class="cont-seguimiento">
                                <label for="">1. ¿Desea realizar una prueba de manejo?</label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <select name="GestionTestDrive[preg1]" id="GestionTestDrive_preg1" class="form-control">
                                            <option value="1">Si</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <input name="GestionTestDrive[id_informacion]" id="GestionTestDrive_id_informacion" type="hidden" value="<?php echo $id_informacion; ?>">
                                <input name="GestionTestDrive[id_vehiculo]" id="GestionTestDrive_id_vehiculo" type="hidden" value="<?php echo $id; ?>">
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <?php echo $form->labelEx($model, 'test_drive'); ?>
                                    <?php echo $form->dropDownList($model, 'test_drive', array('' => 'Seleccione', '0' => 'NO', '1' => 'SI', '2' => 'REPITE'), array('class' => 'form-control valid')); ?>

                                    <?php echo $form->error($model, 'test_drive'); ?>
                                </div>
                            </div>
                            <?php if ($test_drive == 1 && $test_drive != NULL): ?>

                                <div class="row cont-img">
                                    <div class="col-md-4">
                                        <?php //echo $form->FileField($model, 'img', array('class' => 'form-control')); ?>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
                                            <div>
                                                <span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imágen</span><span class="fileinput-exists">Cambiar</span>
                                                    <?php echo $form->FileField($model, 'img', array('class' => 'form-control')); ?>
                                                </span>
                                                <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>
                                            </div>
                                        </div>
                                        <?php echo $form->error($model, 'img'); ?>
                                    </div>
                                </div>
                                <div class="row cont-obs" style="display: none;">
                                    <div class="col-md-4">
                                        <?php echo $form->labelEx($model, 'observacion'); ?>
                                        <?php echo $form->textArea($model, 'observacion', array('rows' => 6, 'cols' => 50)); ?>
                                        <?php echo $form->error($model, 'observacion'); ?>
                                    </div>
                                </div>
                            <?php elseif ($test_drive == 0 && $test_drive != NULL): ?>
                                <div class="row cont-img" style="display: none;">
                                    <div class="col-md-4">
                                        <?php //echo $form->FileField($model, 'img', array('class' => 'form-control')); ?>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
                                            <div>
                                                <span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imágen</span><span class="fileinput-exists">Cambiar</span>
                                                    <?php echo $form->FileField($model, 'img', array('class' => 'form-control')); ?>
                                                </span>
                                                <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>
                                            </div>
                                        </div>
                                        <?php echo $form->error($model, 'img'); ?>
                                    </div>
                                </div>
                                <div class="row cont-obs">
                                    <div class="col-md-4">
                                        <?php echo $form->labelEx($model, 'observacion'); ?>
                                        <?php echo $form->textArea($model, 'observacion', array('rows' => 6, 'cols' => 50)); ?>
                                        <?php echo $form->error($model, 'observacion'); ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="row cont-bot-form-manejo" style="display: none;">
                                    <div class="col-md-3"><button type="button" class="btn btn-primary btn-ing-form">Ingresar Formulario</button></div>
                                    <div class="col-md-3"><a href="<?php echo Yii::app()->createUrl('site/downloadForm', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id)); ?>" class="btn btn-primary btn-des-form" target="_blank">Descargar Formulario</a></div>
                                </div>
                                <div class="cont-form-manejo well well-sm col-md-8" style="display: none;">
                                    <?php foreach ($info as $key => $value): ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h3>Formulario de Prueba de Manejo</h3>
                                                <p>Kia Motors Ecuador</p>
                                            </div> 
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Nombres y Apellidos</label>
                                                <input type="text" class="form-control" name="GestionTestDrive[nombre]" id="GestionTestDrive_nombres" value="<?php echo $value['nombres'] . ' ' . $value['apellidos']; ?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">C.I.</label>
                                                <input type="text" class="form-control" name="GestionTestDrive[cedula]" id="GestionTestDrive_cedula" value="<?php echo $value['cedula']; ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Fecha</label>
                                                <input type="text" class="form-control" name="GestionTestDrive[fecha]" id="GestionTestDrive_fecha">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Dirección</label>
                                                <input type="text" class="form-control" name="GestionTestDrive[direccion]" id="GestionTestDrive_cedula" value="<?php echo $value['direccion']; ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Teléfono Convencional</label>
                                                <input type="text" class="form-control" name="GestionTestDrive[telefono_convencional]" id="GestionTestDrive_telefono_convencional" value="<?php echo $value['telefono_casa']; ?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Teléfono Celular</label>
                                                <input type="text" class="form-control" name="GestionTestDrive[celular]" id="GestionTestDrive_celular" value="<?php echo $value['celular']; ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Email</label>
                                                <input type="text" class="form-control" name="GestionTestDrive[email]" id="GestionTestDrive_email" value="<?php echo $value['email']; ?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Modelo de vehículo que posee actualmente</label>
                                                <input type="text" class="form-control" name="GestionTestDrive[modelo_actual]" id="GestionTestDrive_modelo_actual">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="">Marca</label>
                                                <input type="text" class="form-control" name="GestionTestDrive[marca]" id="GestionTestDrive_marca">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">Modelo</label>
                                                <input type="text" class="form-control" name="GestionTestDrive[modelo]" id="GestionTestDrive_modelo">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">Año</label>
                                                <input type="text" class="form-control" name="GestionTestDrive[year]" id="GestionTestDrive_year">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Modelo de vehículo Kia que realiza la prueba de manejo</label>
                                                <input type="text" class="form-control" name="GestionTestDrive[modelo_kia]" id="GestionTestDrive_modelo_kia" value="<?php echo $this->getModeloTestDrive($id); ?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="">Nombre Asesor</label>
                                                <input type="text" class="form-control" name="GestionTestDrive[nombre_asesor]" id="GestionTestDrive_nombre_asesor" value="<?php echo $this->getResponsable($value['responsable']); ?>">
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="">Observaciones</label>
                                            <textarea name="GestionTestDrive[observaciones_form]" id="GestionTestDrive_observaciones_form" cols="30" rows="10"></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p style="font-size: 12px;">Por medio del presente acepto relizar una prueba de manejo en este establecimiento, declaro que dejo la copia
                                                de la licencia de conducción vigente. Me comprometo a conducir el vehículo de prueba de manera responsable y cumpliendo 
                                                con las normas de tránsito respectivas.</p>
                                        </div>
                                    </div>
                                    <div class="row"></div>
                                    <br><br>
                                    <div class="row">

                                        <div class="col-md-4">
                                            <hr>
                                            Firma del Cliente
                                        </div>
                                        <div class="col-md-4">

                                        </div>
                                        <div class="col-md-4">
                                            <hr>
                                            Firma Asesor
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="file" name="GestionTestDrive[licencia]" id="GestionTestDrive_licencia">
                                        </div>
                                    </div>
                                </div>
                                <div class="row cont-img" style="display: none;">
                                    <div class="col-md-8">
                                        <?php //echo $form->FileField($model, 'img', array('class' => 'form-control')); ?>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
                                            <div>
                                                <span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imágen</span><span class="fileinput-exists">Cambiar</span>
                                                    <?php echo $form->FileField($model, 'img', array('class' => 'form-control')); ?>
                                                </span>
                                                <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>
                                            </div>
                                        </div>
                                        <?php echo $form->error($model, 'img'); ?>
                                    </div>
                                </div>
                                <div class="row cont-obs" style="display: none;">
                                    <div class="col-md-8">
                                        <?php echo $form->labelEx($model, 'observacion'); ?>
                                        <?php echo $form->textArea($model, 'observacion', array('rows' => 6, 'cols' => 50)); ?>
                                        <?php echo $form->error($model, 'observacion'); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="row buttons">
                                <div class="col-md-6">
                                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Crear' : 'Save', array('class' => 'btn btn-danger', 'id' => 'finalizar')); ?>
                                </div>
                            </div>

                            <?php $this->endWidget(); ?>

                        </div><!-- form -->

                    </div>
                </div>
                <br>
                <!--                <div class="row">
                                    <div class="highlight">
                                        <h4>Seguimiento</h4>
                                        <div class="alert alert-success alert-dismissible" role="alert" id="cont-alert" style="display: none;">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            Datos grabados correctamente en seguimiento.
                                        </div>
                                        <div class="form cont-seguimiento">
                                            <form onsubmit="return false;" onkeypress=" if(event.keyCode == 13){ send(); } " id="gestion-diaria-form" action="/intranet/callcenter/index.php/gestionDiaria/create/<?php echo $id_informacion; ?>" method="post">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="">Opciones</label>
                                                        <select class="form-control" name="GestionDiaria[opciones_seguimiento]" id="opciones_seguimiento">
                                                            <option value="">Escoja una versión</option>
                                                            <option value="Opcion 1">Opcion 1</option>
                                                            <option value="Opcion 2">Opcion 2</option>
                                                            <option value="Opcion 2">Opcion 3</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="">Agendamiento</label>
                                                        <input type="text" name="GestionDiaria[agendamiento]" id="agendamiento" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="row buttons">
                                                    <input type="hidden" name="GestionInformacion[calendar]" id="GestionInformacion_calendar" value="0">
                                                    <input type="hidden" name="GestionInformacion[check]" id="GestionInformacion_check" value="1">
                                                    <input name="GestionDiaria[id_informacion]" id="GestionDiaria_id_informacion" type="hidden" value="<?php echo $id_informacion; ?>">
                                                    <input name="GestionDiaria[id_vehiculo]" id="GestionDiaria_id_vehiculo" type="hidden" value="<?php echo $id; ?>">
                                                    <input name="GestionDiaria[seguimiento]" id="GestionDiaria_seguimiento" type="hidden" value="1">
                                                    <div class="col-md-3">
                                                        <input class="btn btn-danger" id="finalizar" type="submit" name="yt0" value="Crear Seguimiento" onclick="send();">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div id="calendar-content" style="display: none;">
                                                            <a href="" class="btn btn-primary" id="event-download">Descargar Evento</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>   
                                    </div>
                                </div>-->
            </div>
            <?php
            $obs = $this->getTestObs($id_informacion, $id);
            if ($obs > 0):
                ?>
                <div class="row">
                    <div class="col-md-6"><h3>Historial Observaciones</h3></div>
                </div>
                <?php
                $criteria = new CDbCriteria(array(
                    'condition' => "id_informacion={$id_informacion} AND id_vehiculo = {$id} AND test_drive = 0",
                    'order' => 'id DESC'
                ));
                $historial = GestionTestDrive::model()->findAll($criteria);
                ?>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Observación</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            foreach ($historial as $c):
                                $fecha = '';
                                $nuevafecha = '';
                                $fecha = date($c['fecha']);
                                $nuevafecha = strtotime('-5 hour', strtotime($fecha));
                                $nuevafecha = date('Y-m-d H:i:s', $nuevafecha);
                                ?>
                                <tr>
                                    <td><?php echo $c['fecha']; ?> </td>
                                    <td><?php echo $c['observacion']; ?> </td>

                                </tr>
                                <?php
                            endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            <?php
            $obs = $this->getTestDriveRep($id_informacion, $id);
            if ($obs > 0):
                ?>
                <div class="row">
                    <div class="col-md-6"><h3>Historial Test Drive</h3></div>
                </div>
                <?php
                $criteria = new CDbCriteria(array(
                    'condition' => "(id_informacion={$id_informacion} AND id_vehiculo = {$id}) AND (test_drive = 1 OR test_drive = 2)",
                    'order' => 'id DESC'
                ));
                $historial = GestionTestDrive::model()->findAll($criteria);
                ?>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Observación</th>
                                <th>Foto</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            foreach ($historial as $c):
                                $fecha = '';
                                $nuevafecha = '';
                                $fecha = date($c['fecha']);
                                $nuevafecha = strtotime('-5 hour', strtotime($fecha));
                                $nuevafecha = date('Y-m-d H:i:s', $nuevafecha);
                                ?>
                                <tr>
                                    <td><?php echo $c['fecha']; ?> </td>
                                    <td><?php echo $c['observacion']; ?> </td>
                                    <td>
                                        <?php if (!empty($c['img'])): ?>
                                            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/uploads/<?php echo $c['img']; ?>" width="80" height="80" /> 
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>
</div>
