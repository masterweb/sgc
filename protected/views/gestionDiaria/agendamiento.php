<?php
//echo print_r($_GET);
$id_informacion = $_GET['id_informacion'];
//die('id info: '.$id_informacion);
//die();
$id_asesor = Yii::app()->user->getId();
$concesionarioid = $this->getConcesionarioDealerId($id_asesor);
$nombreConcesionario = $this->getNameConcesionarioById($concesionarioid);
$nombre_cliente = $this->getNombresInfo($_GET['id_informacion']) . ' ' . $this->getApellidosInfo($_GET['id_informacion']);
$direccion_concesionario = $this->getConcesionarioDireccionById($concesionarioid);
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.css" rel="stylesheet">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<!-- Add fancyBox -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/jquery.fancybox.pack.js?v=2.1.4"></script>
<!-- Optionally add helpers - button, thumbnail and/or media -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.5"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
<script type="text/javascript">
    $(function () {
        $('#cont-agendamiento').hide();
        $('.fancybox').fancybox();
        $('#GestionAgendamiento_observaciones').change(function () {
            var value = $(this).attr('value');
            switch (value) {
                case 'Llamar en otro momento':
                case 'Crear Cita':
                    $('#cont-agendamiento').show();
                    $('#cont-otro').hide();
                    validateAgendamiento();
                    break;
                case 'Busca solo precio':
                case 'Desiste':
                    $('#cont-agendamiento').hide();
                    $('#cont-otro').hide();
                    validateNormal();
                    break;
                case 'Otro':
                    $('#cont-agendamiento').hide();
                    $('#cont-otro').show();
                    validateOtro();
                    break;
            }
        });
        $('#GestionAgendamiento_agendamiento').datetimepicker({
            lang: 'es',
            onGenerate: function (ct) {
                $(this).find('.xdsoft_date.xdsoft_weekend')
                        .addClass('xdsoft_disabled');
            },
            weekends: ['01.01.2014', '02.01.2014', '03.01.2014', '04.01.2014', '05.01.2014', '06.01.2014'],
            minDate: '-1970/01/01', //yesterday is minimum date(for today use 0 or -1970/01/01)
            disabledDates: ['03.04.2015', '01.05.2015', '10.08.2015', '09.10.2015', '02.11.2015', '03.11.2015', '25.12.2015'], formatDate: 'd.m.Y'
        });
        $('#form-gestion-agendamiento').validate({
            rules: {
                'GestionAgendamiento[agendamiento]': {
                    required: true
                },
                'GestionAgendamiento[observaciones]': {required: true}
            },
            messages: {
                'GestionAgendamiento[agendamiento]': {
                    required: 'Seleccione una fecha de agendamiento'
                },
                'GestionAgendamiento[observaciones]': {required: 'Seleccione una opción'}
            },
            submitHandler: function (form) {
                console.log('enter handler');
                var proximoSeguimiento = $('#GestionAgendamiento_agendamiento').val();
                var observaciones = $('#GestionAgendamiento_observaciones').val();
                var nombre_cliente = $('#GestionAgendamiento_nombre_cliente').val();
                var nombre_concesionario = $('#GestionAgendamiento_nombre_concesionario').val();
                var direccion_concesionario = $('#GestionAgendamiento_direccion_concesionario').val();
                //console.log(proximoSeguimiento);
                var fechaSeguimiento = proximoSeguimiento.replace('/', '-');
                fechaSeguimiento = fechaSeguimiento.replace('/', '-');
                var fechaArray = fechaSeguimiento.split(' ');
                if (proximoSeguimiento != '') {
                    //console.log('proximo: ' + proximoSeguimiento);
                    if ($('#GestionInformacion_check').val() != 2) {
                        var cliente = '';
                        var params = proximoSeguimiento.split("/");
                        var fechaDate = params[0] + params[1] + params[2];
                        var secDate = params[2].split(" ");
                        var fechaStart = params[0] + params[1] + secDate[0];
                        var start = secDate[1].split(":");
                        var startTime = start[0] + start[1];
                        var params2 = fechaDate.split(":");
                        var endTime = parseInt(startTime) + 100;
                        //console.log('start time:'+fechaStart+startTime);
                        //console.log('fecha end:'+fechaStart+endTime);
                        var href = '/intranet/usuario/index.php/gestionDiaria/ical?startTime=' + fechaStart + startTime + '&endTime=' + fechaStart + endTime + '&subject=Agendamiento Cita Cliente: '+nombre_cliente+'&desc=Cita con el cliente paso consulta: '+nombre_cliente+'&location='+nombre_concesionario+' - '+direccion_concesionario+'&to_name=' + cliente + '&conc='+nombre_concesionario;
                        //var href = '/intranet/ventas/index.php/gestionDiaria/calendar?date='+fechaDate+'&startTime='+startTime+'&endTime='+endTime+'&subject=Cita con Cliente&desc=Cita con el cliente prospección';
                        $('#event-download').attr('href', href);
                        $('#calendar-content').show();
                        $("#event-download").click(function () {
                            $('#GestionInformacion_calendar').val(1);
                            $('#calendar-content').hide();
                            $('#GestionInformacion_check').val(2)
                        });
                        if ($('#GestionInformacion_calendar').val() == 1) {
                            form.submit();
                        } else {
                            alert('Debes descargar agendamiento y luego dar click en Continuar');
                            return false;
                        }
                    }
                }
                form.submit();
            }
        });
    });
    function sendAgen() {
        var agen = $('#GestionAgendamiento_observaciones').val();
        switch (agen) {
            case 'Llamar en otro momento':
                validateAgendamiento();
                break;
            case 'Crear Cita':
                validateAgendamiento();
                break;
            case 'Busca solo precio':
                validateNormal();
                break;
            case 'Desiste':
                validateNormal();
                break;
            case 'Otro':
                validateOtro();
                break;
        }
    }
    function validateAgendamiento() {
        $("#GestionAgendamiento_agendamiento").rules("add", "required");
        $("#GestionAgendamiento_otro").rules("remove", "required");
    }
    function validateNormal() {
        $("#GestionAgendamiento_agendamiento").rules("remove", "required");
        $("#GestionAgendamiento_otro").rules("remove", "required");
    }
    function validateOtro() {
        $("#GestionAgendamiento_otro").rules("add", "required");
        $("#GestionAgendamiento_agendamiento").rules("remove", "required");
    }
</script>    
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="highlight">
                <div class="row">
                    <h1 class="tl_seccion_rf">Datos del Cliente</h1>
                    <?php
                    $con = Yii::app()->db;
                    $sql = "SELECT gi.id as id_info, gi. nombres, gi.apellidos, gi.cedula, gi.ruc, gi. pasaporte, gi.email, gi.direccion,gi.celular, 
                        gi.telefono_oficina, gi.id_cotizacion, gi.responsable as id_resp, gi.tipo_form_web, gi.presupuesto, gi.marca_usado, gi.modelo_usado, gd.* FROM gestion_diaria gd 
                                INNER JOIN gestion_informacion gi ON gi.id = gd.id_informacion 
                                WHERE gi.id = {$_GET['id_informacion']} GROUP BY gi.id ORDER BY gd.id_informacion DESC";
                    //die($sql);
                    $request = $con->createCommand($sql);
                    $users = $request->queryAll();
                    /* echo '<pre>';
                      print_r($users);
                      echo '</pre>'; */
                    ?>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php foreach ($users as $key => $value): ?>
                            <table class="table">
                                <tr>
                                    <td><strong>Nombres:</strong> <?php echo $value['nombres']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Apellidos:</strong> <?php echo $value['apellidos']; ?></td>
                                </tr>
                                <?php if ($value['cedula'] != ''): ?>
                                    <tr>
                                        <td><strong>Cédula:</strong> <?php echo $value['cedula']; ?></td>
                                    </tr>
                                <?php endif; ?>
                                <?php if ($value['ruc'] != ''): ?>
                                    <tr>
                                        <td><strong>Ruc:</strong> <?php echo $value['ruc']; ?></td>
                                    </tr>
                                <?php endif; ?>
                                <?php if ($value['pasaporte'] != ''): ?>
                                    <tr>
                                        <td><strong>Pasaporte:</strong> <?php echo $value['pasaporte']; ?></td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td><strong>Email:</strong> <?php echo $value['email']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Dirección:</strong> <?php echo $value['direccion']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Celular:</strong> <?php echo $value['celular']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Teléfono Oficina:</strong> <?php echo $value['telefono_oficina']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Fuente de Contacto:</strong> <?php echo ucfirst($this->getFuente($value['id_cotizacion'])); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Tipo Formulario:</strong> <?php echo ucfirst($value['tipo_form_web']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Presupuesto:</strong> <?php echo $value['presupuesto']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Marca:</strong> <?php echo ($value['marca_usado']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Modelo:</strong> <?php
                                        $paramAutos = explode('@', $value['modelo_usado']);
                                        echo $paramAutos[1] . ' ' . $paramAutos[2];
                                        ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Responsable:</strong> <?php echo $this->getResponsable($value['id_resp']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Email Asesor:</strong> <a href="mailto:asesor@kia.com.ec">mailto:asesor@kia.com.ec</a></td>
                                </tr>
                                <?php if ($value['tipo_form_web'] == 'usadopago'): ?>
                                    <?php
                                    $stringAutos = $this->getGaleriaUsados($value['id_info']);
                                    if ($stringAutos != '') {
                                        $stringAutos = trim($stringAutos);
                                        $stringAutos = substr($stringAutos, 0, strlen($stringAutos) - 1);
                                        $paramAutos = explode('@', $stringAutos);
                                        $tag = 0;
                                        ?>
                                        <tr>
                                            <td><strong>Fotos Vehículo:</strong>
                                                <div class="row">
            <?php foreach ($paramAutos as $val) { ?>
                                                        <div class="col-md-4">
                <!--                                                        <a class="fancybox" data-fancybox-group="gallery" href="#fc<?php echo $tag; ?>">-->
                                                            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/uploads/<?php echo $val; ?>" alt="" width="300" class="img-thumbnail">
                                                            <!--                                                        </a>-->
                                                        </div>
                                                        <div class="cont_galeria" id="fc<?php echo $tag; ?>" style="width: auto; padding: 5px;display:none">
                                                            <img alt="" src="<?php echo Yii::app()->request->baseUrl; ?>/images/uploads/<?php echo $val; ?>" />
                                                        </div>
                                                        <?php
                                                        $tag++;
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                <?php } ?>
                            <?php endif; ?>
                            </table>                       
                <?php endforeach; ?>
                    </div>
                </div>
                <?php
                $crit = new CDbCriteria(array('condition' => "id_informacion={$id_informacion} AND paso = '1-2'", 'order' => 'id DESC'));
                $agen = GestionAgendamiento::model()->count($crit);
                $ag = GestionAgendamiento::model()->findAll($crit);
                ?>
<?php if ($agen > 0) { ?>
                    <div class="row">
                        <div class="col-md-8"><h4 class="tl-agen">Agendamientos</h4>
                            <ul class="list-group">
    <?php foreach ($ag as $a) { ?>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?php if ($a['observaciones'] == 'Otro'): ?>
                                                    <strong>Observación: </strong><?php echo $a['otro_observacion']; ?>
                                                <?php else: ?>
                                                    <strong>Fecha Agendamiento: </strong><?php echo $a['agendamiento']; ?>
        <?php endif; ?>
                                            </div>
                                            <div class="col-md-6"><strong>Motivo: </strong><?php echo $a['observaciones']; ?></div>
                                        </div>
                                    </li>
    <?php } ?>
                            </ul>
                        </div>
                    </div>
<?php } ?>
            </div>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-md-12">
            <div class="highlight">
                <div class="row">
                    <h1 class="tl_seccion_green">Seguimiento</h1>
                </div>
                <div class="form">
                    <?php $agendamiento = new GestionAgendamiento; ?>

                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'action' => Yii::app()->createUrl('gestionAgendamiento/create'),
                        'id' => 'form-gestion-agendamiento',
                        'enableAjaxValidation' => false,
                    ));
                    ?>
                            <?php //echo $form->errorSummary($agendamiento);       ?>
                    <div class="row">
                        <div class="col-md-4">
                            <?php echo $form->labelEx($agendamiento, 'observaciones'); ?>
                            <?php
                            echo $form->dropDownList($agendamiento, 'observaciones', array('' => '--Seleccione--',
                                'Llamar en otro momento' => 'Llamar en otro momento',
                                'Crear Cita' => 'Crear Cita',
                                'Busca solo precio' => 'Busca solo precio',
                                'Desiste' => 'Desiste',
                                'Otro' => 'Otro'), array('class' => 'form-control'));
                            ?>
                            <?php echo $form->error($agendamiento, 'observaciones'); ?>
                        </div>
                        <div class="col-md-4" id="cont-agendamiento">
                            <?php echo $form->labelEx($agendamiento, 'agendamiento'); ?>
<?php echo $form->textField($agendamiento, 'agendamiento', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control')); ?>
<?php echo $form->error($agendamiento, 'agendamiento'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div id="cont-otro" style="display: none;">
                                <label for="">Comentarios</label>
                                <input type="text" class="form-control" name="GestionAgendamiento[otro]" id="GestionAgendamiento_otro"/>
                            </div>
                        </div>
                    </div>
                    <div class="row buttons">
                        <input type="hidden" name="GestionInformacion[calendar]" id="GestionInformacion_calendar" value="0">
                        <input type="hidden" name="GestionInformacion[check]" id="GestionInformacion_check" value="1">
                        <input type="hidden" name="GestionAgendamiento[paso]" id="GestionAgendamiento_paso" value="1-2">
                        <input type="hidden" name="GestionInformacion[tipo_form_web]" id="GestionInformacion_tipo_form_web" value="usado">
                        <input type="hidden" name="GestionAgendamiento[id_informacion]" id="GestionAgendamiento_id_informacion" value="<?php echo $id_informacion; ?>">
                        <input type="hidden" name="GestionAgendamiento[nombre_cliente]" id="GestionAgendamiento_nombre_cliente" value="<?php echo $nombre_cliente; ?>">
                        <input type="hidden" name="GestionAgendamiento[nombre_concesionario]" id="GestionAgendamiento_nombre_concesionario" value="<?php echo $nombreConcesionario; ?>">
                        <input type="hidden" name="GestionAgendamiento[direccion_concesionario]" id="GestionAgendamiento_direccion_concesionario" value="<?php echo $direccion_concesionario; ?>">
                        <div class="col-md-2">
<?php echo CHtml::submitButton($agendamiento->isNewRecord ? 'Grabar' : 'Save', array('class' => 'btn btn-danger')); ?>
                        </div>
                        <div class="col-md-3">
                            <div id="calendar-content" style="display: none;">
                                <a href="" class="btn btn-primary" id="event-download">Descargar Evento</a>
                            </div>
                        </div>
                    </div>
<?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
