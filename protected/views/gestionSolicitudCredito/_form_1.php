<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/css/ui.daterangepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/js/date.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/js/daterangepicker.jQuery.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<?php
/* @var $this GestionSolicitudCreditoController */
/* @var $model GestionSolicitudCredito */
/* @var $form CActiveForm */
//echo 'id vehiculo: '.$id_vehiculo;
$dealer_id = $this->getConcesionarioId($id_informacion);
$id_responsable = $this->getResponsableId($id_informacion);
$id_modelo = $this->getIdModelo($id_vehiculo);
//$nombre_modelo = $this->getVersion($id_vehiculo);
//$id_version = $this->getVersion($id_vehiculo);
//echo 'id repsonsable:'.$id_responsable;
?>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<style type="text/css">
    .tl_seccion_rf_s {
        background-color: #aa1f2c;
        padding: 5px 28px;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 18px;
        color: #fff;
        font-weight: bold;
        margin-top: 20px;
        width: 100%;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        $( "#GestionSolicitudCredito_fecha_nacimiento" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: new Date(1950, 10 - 1, 25),
            yearRange: '1950:1994'
        });
        $('#GestionSolicitudCredito_habita').change(function(){
            var value = $(this).attr('value');
            //alert(value);
            if(value == 'Rentada'){
                $('#cont-arriendo').show();
            }else{
                $('#cont-arriendo').hide();
            }
        });
        $('#GestionSolicitudCredito_banco1').change(function(){
            var value = $(this).attr('value');
            if(value == 'Otros'){
                $('.otro-bn-1').show();
            }else{
                $('.otro-bn-1').hide();
            }
        }        
        );
        $('#GestionSolicitudCredito_banco2').change(function(){
            var value = $(this).attr('value');
            if(value == 'Otros'){
                $('.otro-bn-2').show();
            }else{
                $('.otro-bn-2').hide();
            }
        }        
        );
        $('#GestionSolicitudCredito_provincia_domicilio').change(function () {
            var value = $("#GestionSolicitudCredito_provincia_domicilio option:selected").val();
            //console.log('valor seleccionado: '+value);
            var data = '';
            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("site/getciudades"); ?>',
                //dataType: "json",
                beforeSend: function (xhr) {
                    $('#info3').show();  // #info must be defined somehwere
                },
                type: 'post',
                data: {
                    id: value
                },
                success: function (data) {
                    //alert(data.options)
                    $('#GestionInformacion_ciudad_domicilio').html(data);
                    $('#info3').hide();
                }
            });
            //alert(value)
            $('#GestionInformacion_ciudad_domicilio').html(data);

        });
    });
    function send() {
        $('#gestion-solicitud-credito-form').validate({
            rules: {'GestionSolicitudCredito[modelo]': {required: true},
            'GestionSolicitudCredito[modelo]': {required: true},
        'GestionSolicitudCredito[valor]': {required: true},
    'GestionSolicitudCredito[monto_financiar]': {required: true},
    'GestionSolicitudCredito[entrada]': {required: true},
    'GestionSolicitudCredito[year]': {required: true},
    'GestionSolicitudCredito[plazo]': {required: true},
    'GestionSolicitudCredito[taza]': {required: true},
    'GestionSolicitudCredito[cuota_mensual]': {required: true},
    'GestionSolicitudCredito[apellido_paterno]': {required: true},
    'GestionSolicitudCredito[nombres]': {required: true},
    'GestionSolicitudCredito[cedula]': {required: true},
    'GestionSolicitudCredito[fecha_nacimiento]': {required: true},
    'GestionSolicitudCredito[empresa_trabajo]': {required: true},
    'GestionSolicitudCredito[telefonos_trabajo]': {required: true},
    'GestionSolicitudCredito[tiempo_trabajo]': {required: true},
    'GestionSolicitudCredito[cargo]': {required: true},
    'GestionSolicitudCredito[direccion_empresa]': {required: true},
    'GestionSolicitudCredito[email]': {required: true},
    'GestionSolicitudCredito[actividad_empresa]': {required: true},
    'GestionSolicitudCredito[ciudad_domicilio]': {required: true},
    'GestionSolicitudCredito[barrio]': {required: true},
    'GestionSolicitudCredito[calle]': {required: true},
    'GestionSolicitudCredito[telefono_residencia]': {required: true},
    'GestionSolicitudCredito[celular]': {required: true},
    },
            messages: {},
            submitHandler: function (form) {
                var dataform = $("#gestion-solicitud-credito-form").serialize();
                $.ajax({
                    url: '<?php echo Yii::app()->createAbsoluteUrl("gestionSolicitudCredito/createAjax"); ?>',
                    beforeSend: function (xhr) {
                        $('#bg_negro').show();  // #bg_negro must be defined somewhere
                    },
                    datatype: "json",
                    type: 'POST',
                    data: dataform,
                    success: function (data) {
                        var returnedData = JSON.parse(data);
                        //alert(returnedData.result);
                        $('#bg_negro').hide();
                        $('#finalizar').hide();
                        $('#generatepdf').show();
                        //$('#btn-continuar').show();
                        //$('#GestionFinanciamiento_ipdfid').val(returnedData.id);
                    }
                });
            }
        });

    }
</script>
<div class="container">
    <div role="tabpanel">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/prospeccion.png" alt="" /></span> Prospección / <span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cita.png" alt="" /></span> Cita</a></li>
            <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/recepcion.png" alt="" /></span> Recepción</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('gestionVehiculo/create/' . $id_informacion); ?>" aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/consulta.png" alt="" /></span> Consulta</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/presentacion/' . $id_informacion); ?>" saria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/presentacion.png" alt="" /></span> Presentación</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/demostracion/' . $id_informacion); ?>" saria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/demostracion.png" alt="" /></span> Demostración</a></li>
            <li role="presentation" class="active"><a href="<?php echo Yii::app()->createUrl('site/negociacion/' . $id_informacion); ?>" aria-controls="settings" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/negociacion_on.png" alt="" /></span> Negociación</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/cierre/' . $id_informacion); ?>" aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cierre.png" alt="" /></span> Cierre</a></li>
            <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/entrega.png" alt="" /></span> Entrega</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('gestionSeguimiento/create/', array('id_vehiculo' => $id_vehiculo, 'id_informacion' => $id_informacion)); ?>" aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/seguimiento.png" alt="" /></span>Seguimiento</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="profile">
                <div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'gestion-solicitud-credito-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array(
            'onsubmit' => "return false;", /* Disable normal form submit */
            'onkeypress' => " if(event.keyCode == 13){ send(); } " /* Do ajax call when user presses enter key */
        ),
    ));
    ?>
    <p class="note">Campos con <span class="required">*</span> son requeridos.</p>
    <?php //echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-md-3">
            <?php //echo $form->labelEx($model, 'concesionario'); ?>
            <?php //echo $form->textField($model, 'concesionario', array('class' => 'form-control', 'value' => 'Asiauto Mariana de Jesús', 'disabled' => 'true')); ?>
            <?php //echo $form->error($model, 'concesionario'); ?>
            <label for="GestionSolicitudCredito_concesionario">Concesionario</label>
            <input class="form-control" value="Asiauto Mariana de Jesús" disabled="disabled" name="GestionSolicitudCredito[concesionarioh]" id="GestionSolicitudCredito_concesionarioh" type="text">       
        </div>

        <div class="col-md-3">
            <?php //echo $form->labelEx($model, 'vendedor'); ?>
            <?php //echo $form->textField($model, 'vendedor', array('class' => 'form-control')); ?>
            <?php //echo $form->error($model, 'vendedor'); ?>
            <label for="GestionSolicitudCredito_concesionario">Vendedor</label>
            <input class="form-control" value="<?php echo $this->getResponsable($id_responsable); ?>" disabled="disabled" name="GestionSolicitudCredito[vendedorh]" id="GestionSolicitudCredito_vendedorh" type="text">       
        </div>
        <div class="col-md-3">
            <?php echo $form->labelEx($model, 'fecha'); ?>
            <?php echo $form->textField($model, 'fecha', array('class' => 'form-control', 'value' => date("d") . "/" . date("m") . "/" . date("Y"))); ?>
            <?php echo $form->error($model, 'fecha'); ?></div>
        <div class="col-md-3"></div>
        <input type="hidden" name="GestionSolicitudCredito[concesionario]" id="GestionSolicitudCredito_concesionario" value="<?php echo $dealer_id; ?>"/>
        <input type="hidden" name="GestionSolicitudCredito[vendedor]" id="GestionSolicitudCredito_vendedor" value="<?php echo $id_responsable; ?>"/>
        <input type="hidden" name="GestionSolicitudCredito[id_informacion]" id="GestionSolicitudCredito_id_informacion" value="<?php echo $id_informacion; ?>"/>
        <input type="hidden" name="GestionSolicitudCredito[id_vehiculo]" id="GestionSolicitudCredito_id_vehiculo" value="<?php echo $id_vehiculo; ?>"/>
    </div>
    <div class="row">
        <h1 class="tl_seccion_rf">Datos del Vehículo</h1>
    </div>  
    <?php
    $criteria = new CDbCriteria(array(
        'condition' => "id_informacion={$id_informacion}",
        'order' => 'id DESC',
        'limit' => 1
    ));
    $vec = GestionFinanciamiento::model()->findAll($criteria);
    /* echo '<pre>';
      print_r($vec);
      echo '</pre>'; */
    ?>
    <?php foreach ($vec as $value) { ?>
        <div class="row">
            <div class="col-md-3">
                <?php echo $form->labelEx($model, 'modelo'); ?>
                <?php echo $form->textField($model, 'modelo', array('class' => 'form-control', 'value' => $id_modelo)); ?>
                <?php echo $form->error($model, 'modelo'); ?>
            </div>
            <div class="col-md-3">
                <?php echo $form->labelEx($model, 'valor'); ?>
                <?php echo $form->textField($model, 'valor', array('class' => 'form-control', 'value' => $value['precio_vehiculo'])); ?>
                <?php echo $form->error($model, 'valor'); ?>
            </div>
            <div class="col-md-3">
                <?php echo $form->labelEx($model, 'monto_financiar'); ?>
                <?php echo $form->textField($model, 'monto_financiar', array('class' => 'form-control', 'value' => $value['valor_financiamiento'])); ?>
                <?php echo $form->error($model, 'monto_financiar'); ?>
            </div>

        </div>



        <div class="row">
            <div class="col-md-2">
                <?php echo $form->labelEx($model, 'entrada'); ?>
                <?php echo $form->textField($model, 'entrada', array('class' => 'form-control', 'value' => $value['cuota_inicial'])); ?>
                <?php echo $form->error($model, 'entrada'); ?>
            </div>
            <div class="col-md-2">
                <?php echo $form->labelEx($model, 'year'); ?>
                <?php echo $form->textField($model, 'year', array('class' => 'form-control')); ?>
                <?php echo $form->error($model, 'year'); ?>
            </div>
            <div class="col-md-2">
                <?php echo $form->labelEx($model, 'plazo'); ?>
                <?php echo $form->textField($model, 'plazo', array('class' => 'form-control', 'value' => $value['plazos'])); ?>
                <?php echo $form->error($model, 'plazo'); ?>
            </div>
            <div class="col-md-2">
                <?php echo $form->labelEx($model, 'taza'); ?>
                <?php echo $form->textField($model, 'taza', array('size' => 20, 'maxlength' => 20, 'class' => 'form-control', 'value' => $value['tasa'])); ?>
                <?php echo $form->error($model, 'taza'); ?>
            </div>
            <div class="col-md-2">
                <?php echo $form->labelEx($model, 'cuota_mensual'); ?>
                <?php echo $form->textField($model, 'cuota_mensual', array('size' => 25, 'maxlength' => 25, 'class' => 'form-control', 'value' => $value['cuota_mensual'])); ?>
                <?php echo $form->error($model, 'cuota_mensual'); ?>
            </div>

        </div>
    <?php } ?>
    <div class="row">
        <h1 class="tl_seccion_rf">Datos del Solicitante</h1>
    </div>
    <?php
    $criteria2 = new CDbCriteria(array(
        'condition' => "id={$id_informacion}"
    ));
    $inf = GestionInformacion::model()->findAll($criteria2);
    ?>
    <?php foreach ($inf as $val) { ?>
        <div class="row">
            <div class="col-md-3">
                <?php echo $form->labelEx($model, 'apellido_paterno'); ?>
                <?php echo $form->textField($model, 'apellido_paterno', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control', 'value' => $val['apellidos'])); ?>
                <?php echo $form->error($model, 'apellido_paterno'); ?>
            </div>
            <div class="col-md-3">
                <?php echo $form->labelEx($model, 'apellido_materno'); ?>
                <?php echo $form->textField($model, 'apellido_materno', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'apellido_materno'); ?>
            </div>
            <div class="col-md-3">
                <?php echo $form->labelEx($model, 'nombres'); ?>
                <?php echo $form->textField($model, 'nombres', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control', 'value' => $val['nombres'])); ?>
                <?php echo $form->error($model, 'nombres'); ?>
            </div>

        </div>


        <div class="row">
            <div class="col-md-3">
                <?php echo $form->labelEx($model, 'cedula'); ?>
                <?php echo $form->textField($model, 'cedula', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control', 'value' => $val['cedula'])); ?>
                <?php echo $form->error($model, 'cedula'); ?>
            </div>
            <div class="col-md-3">
                <?php echo $form->labelEx($model, 'fecha_nacimiento'); ?>
                <?php echo $form->textField($model, 'fecha_nacimiento', array('size' => 60, 'maxlength' => 75, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'fecha_nacimiento'); ?>
            </div>
            <div class="col-md-3">
                <?php echo $form->labelEx($model, 'nacionalidad'); ?>
                <?php echo $form->textField($model, 'nacionalidad', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'nacionalidad'); ?>
            </div>

        </div>


        <div class="row">
            <div class="col-md-3">
                <?php echo $form->labelEx($model, 'estado_civil'); ?>
                <?php echo $form->dropDownList($model, 'estado_civil', array('Casado sin separación de bienes' => 'Casado sin separación de bienes',
                   'Casado con separación de bienes' => 'Casado con separación de bienes',
                    'Soltero' => 'Soltero',
                    'Viudo' => 'Viudo',
                    'Divorciado' => 'Divorciado',
                    'Union Libre' => 'Union Libre'),array( 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'estado_civil'); ?>
            </div>
        </div>
    <?php } ?>
    <div class="row">
        <h1 class="tl_seccion_rf">Empleo/Actividad Actual del Solicitante</h1>
    </div> 
    <div class="row">
        <div class="col-md-2">
            <?php echo $form->labelEx($model, 'empresa_trabajo'); ?>
            <?php echo $form->textField($model, 'empresa_trabajo', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'empresa_trabajo'); ?></div>
        <div class="col-md-2">
            <?php echo $form->labelEx($model, 'telefonos_trabajo'); ?>
            <?php echo $form->textField($model, 'telefonos_trabajo', array('size' => 60, 'maxlength' => 75, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'telefonos_trabajo'); ?></div>
        <div class="col-md-2">
            <?php echo $form->labelEx($model, 'tiempo_trabajo'); ?>
            <?php echo $form->textField($model, 'tiempo_trabajo', array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'tiempo_trabajo'); ?></div>
        <div class="col-md-2">
            <?php echo $form->labelEx($model, 'cargo'); ?>
            <?php echo $form->textField($model, 'cargo', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'cargo'); ?></div>

    </div>

    <div class="row">
        <div class="col-md-4">
            <?php echo $form->labelEx($model, 'direccion_empresa'); ?>
            <?php echo $form->textField($model, 'direccion_empresa', array('size' => 60, 'maxlength' => 200, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'direccion_empresa'); ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->labelEx($model, 'tipo_relacion_laboral'); ?>
            <?php echo $form->dropDownList($model, 'tipo_relacion_laboral', array(
                'Independiente Negocio Propio' => 'Independiente Negocio Propio',
                'Dependiente' => 'Dependiente',
                'Jubilado No Trabaja' => 'Jubilado No Trabaja'
                ),array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'tipo_relacion_laboral'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?php echo $form->labelEx($model, 'email'); ?>
            <?php echo $form->textField($model, 'email', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'email'); ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->labelEx($model, 'actividad_empresa'); ?>
            <?php echo $form->textField($model, 'actividad_empresa', array('size' => 60, 'maxlength' => 80, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'actividad_empresa'); ?>
        </div>
    </div>
    <div class="row">
        <h1 class="tl_seccion_rf">Datos del Cónyugue</h1>
    </div> 

    <div class="row">
        <div class="col-md-3">
            <?php echo $form->labelEx($model, 'apellido_paterno_conyugue'); ?>
            <?php echo $form->textField($model, 'apellido_paterno_conyugue', array('size' => 60, 'maxlength' => 80, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'apellido_paterno_conyugue'); ?>
        </div>
        <div class="col-md-3">
            <?php echo $form->labelEx($model, 'apellido_materno_conyugue'); ?>
            <?php echo $form->textField($model, 'apellido_materno_conyugue', array('size' => 60, 'maxlength' => 80, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'apellido_materno_conyugue'); ?>
        </div>
        <div class="col-md-3">
            <?php echo $form->labelEx($model, 'nombres_conyugue'); ?>
            <?php echo $form->textField($model, 'nombres_conyugue', array('size' => 60, 'maxlength' => 80, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'nombres_conyugue'); ?>
        </div>
    </div>


    <div class="row">
        <div class="col-md-3">
            <?php echo $form->labelEx($model, 'cedula_conyugue'); ?>
            <?php echo $form->textField($model, 'cedula_conyugue', array('size' => 60, 'maxlength' => 80, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'cedula_conyugue'); ?>
        </div>
        <div class="col-md-3">
            <?php echo $form->labelEx($model, 'fecha_nacimiento_conyugue'); ?>
            <?php echo $form->textField($model, 'fecha_nacimiento_conyugue', array('size' => 60, 'maxlength' => 85, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'fecha_nacimiento_conyugue'); ?>
        </div>
        <div class="col-md-3">
            <?php echo $form->labelEx($model, 'nacionalidad_conyugue'); ?>
            <?php echo $form->textField($model, 'nacionalidad_conyugue', array('size' => 60, 'maxlength' => 80, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'nacionalidad_conyugue'); ?>
        </div>

    </div>

    <div class="row">
        <h1 class="tl_seccion_rf">Empleo/Actividad Actual del Cónyugue</h1>
    </div>
    <div class="row">
        <div class="col-md-2">
            <?php echo $form->labelEx($model, 'empresa_trabajo_conyugue'); ?>
            <?php echo $form->textField($model, 'empresa_trabajo_conyugue', array('size' => 60, 'maxlength' => 80, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'empresa_trabajo_conyugue'); ?>
        </div>
        <div class="col-md-2">
            <?php echo $form->labelEx($model, 'telefono_trabajo_conyugue'); ?>
            <?php echo $form->textField($model, 'telefono_trabajo_conyugue', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'telefono_trabajo_conyugue'); ?>
        </div>
        <div class="col-md-2">
            <?php echo $form->labelEx($model, 'tiempo_trabajo_conyugue'); ?>
            <?php echo $form->textField($model, 'tiempo_trabajo_conyugue', array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'tiempo_trabajo_conyugue'); ?>
        </div>
        <div class="col-md-2">
            <?php echo $form->labelEx($model, 'cargo_conyugue'); ?>
            <?php echo $form->textField($model, 'cargo_conyugue', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'cargo_conyugue'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?php echo $form->labelEx($model, 'direccion_empresa_conyugue'); ?>
            <?php echo $form->textField($model, 'direccion_empresa_conyugue', array('size' => 60, 'maxlength' => 120, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'direccion_empresa_conyugue'); ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->labelEx($model, 'tipo_relacion_laboral_conyugue'); ?>
            <?php echo $form->dropDownList($model, 'tipo_relacion_laboral_conyugue', array('Independiente Negocio Propio' => 'Independiente Negocio Propio',
                'Dependiente' => 'Dependiente',
                'Jubilado No Trabaja' => 'Jubilado No Trabaja'), array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'tipo_relacion_laboral_conyugue'); ?>
        </div>

    </div>

    <div class="row">
        <h1 class="tl_seccion_rf">Domicilio Actual</h1>
    </div>

    <div class="row">
        <div class="col-md-3">
            <?php echo $form->labelEx($model, 'habita'); ?>
            <?php echo $form->dropDownList($model, 'habita', array(
                'Propia' => 'Propia',
                'Rentada' => 'Rentada',
                'Familiares' => 'Familiares'
                ),array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'habita'); ?>
        </div>
        <div class="col-md-3" id="cont-arriendo" style="display: none;">
            <?php echo $form->labelEx($model, 'valor_arriendo'); ?>
            <?php echo $form->textField($model, 'valor_arriendo', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'valor_arriendo'); ?>
        </div>
        <div class="col-md-3">
            <?php echo $form->labelEx($model, 'avaluo_propiedad'); ?>
            <?php echo $form->textField($model, 'avaluo_propiedad', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'avaluo_propiedad'); ?>
        </div>
        
        
    </div>
    <div class="row">
        <div class="col-md-3">
            <?php //echo $form->labelEx($model, 'email');  ?>
            <label class="" for="">Provincia Domicilio </label>
            <?php
            $criteria = new CDbCriteria(array(
                'order' => 'nombre'
            ));
            $provincias = CHtml::listData(Provincias::model()->findAll($criteria), "id_provincia", "nombre");
            ?>
            <?php
            //echo $form->dropDownList($model, 'provincia_domicilio', $provincias,array('empty' => '---Seleccione una provincia---','class' => 'form-control'));
            ?>
            <?php
            $this->widget('ext.select2.ESelect2', array(
                'model' => $model,
                'attribute' => 'provincia_domicilio',
                'data' => $provincias
            ));
            ?>
            <?php echo $form->error($model, 'provincia_domicilio'); ?>
        </div>
        <div class="col-md-3">
            <?php //echo $form->labelEx($model, 'celular'); ?>
            <label class="" for="">Ciudad Domicilio </label>
            <div id="info3" style="display: none;"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif" alt=""></div>
            <?php //echo $form->dropDownList($model, 'ciudad_domicilio', array('' => '---Seleccione una ciudad---'),array('class' => 'form-control'));   ?>
            <?php
            $this->widget('ext.select2.ESelect2', array(
                'name' => 'GestionInformacion[ciudad_domicilio]',
                'id' => 'GestionInformacion_ciudad_domicilio',
                'data' => array(
                    '' => '--Seleccione una ciudad--'
                ),
            ));
            ?>
            <?php echo $form->error($model, 'ciudad_domicilio'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?php echo $form->labelEx($model, 'barrio'); ?>
            <?php echo $form->textField($model, 'barrio', array('size' => 60, 'maxlength' => 80, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'barrio'); ?>
        </div>
        <div class="col-md-8">
            <?php echo $form->labelEx($model, 'calle'); ?>
            <?php echo $form->textField($model, 'calle', array('size' => 60, 'maxlength' => 80, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'calle'); ?>
        </div>
        
    </div>



    <div class="row">
        <div class="col-md-4">
            <?php echo $form->labelEx($model, 'referencia_domicilio'); ?>
            <?php echo $form->textField($model, 'referencia_domicilio', array('size' => 60, 'maxlength' => 80, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'referencia_domicilio'); ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->labelEx($model, 'telefono_residencia'); ?>
            <?php echo $form->textField($model, 'telefono_residencia', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'telefono_residencia'); ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->labelEx($model, 'celular'); ?>
            <?php echo $form->textField($model, 'celular', array('size' => 10, 'maxlength' => 10, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'celular'); ?>
        </div>

    </div>

    <div class="row">
        <h1 class="tl_seccion_rf">Ingresos</h1>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?php echo $form->labelEx($model, 'sueldo_mensual'); ?>
            <?php echo $form->textField($model, 'sueldo_mensual', array('size' => 20, 'maxlength' => 20, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'sueldo_mensual'); ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->labelEx($model, 'sueldo_mensual_conyugue'); ?>
            <?php echo $form->textField($model, 'sueldo_mensual_conyugue', array('size' => 20, 'maxlength' => 20, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'sueldo_mensual_conyugue'); ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->labelEx($model, 'otros_ingresos'); ?>
            <?php echo $form->textField($model, 'otros_ingresos', array('size' => 20, 'maxlength' => 20, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'otros_ingresos'); ?>
        </div>
    </div>
    <div class="row">
        <h1 class="tl_seccion_rf">Referencias Bancarias</h1>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?php echo $form->labelEx($model, 'banco1'); ?>
            <?php echo $form->dropDownList($model, 'banco1', array(
                'Banco del Pichincha' => 'Banco del Pichincha',
                'Banco del Pacífico' => 'Banco del Pacífico',
                'Banco de Guayaquil' => 'Banco de Guayaquil',
                'Produbanco' => 'Produbanco',
                'Banco Bolivariano' => 'Banco Bolivariano',
                'Banco Internacional' => 'Banco Internacional',
                'Banco del Austro' => 'Banco del Austro',
                'Banco Promerica (Ecuador)' => 'Banco Promerica (Ecuador)',
                'Banco de Machala' => 'Banco de Machala',
                'BGR' => 'BGR',
                'Citibank' => 'Citibank',
                'Banco ProCredit' => 'Banco ProCredit',
                'Banco Solidario' => 'Banco Solidario',
                'Otros' => 'Otros',
                ),
                    array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'banco1'); ?>
        </div>
        <div class="otro-bn-1" style="display: none;">
            <div class="col-md-3">
                <label for="">Otra institución</label>
                <input type="text" name="GestionSolicitudCredito[otro1]" id="GestionSolicitudCredito_otro1" class="form-control"/>
              
            </div>
        </div>
        <div class="col-md-3">
            <?php echo $form->labelEx($model, 'cuenta_ahorros1'); ?>
            <?php echo $form->textField($model, 'cuenta_ahorros1', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'cuenta_ahorros1'); ?>
        </div>
        <div class="col-md-3">
            <?php echo $form->labelEx($model, 'cuenta_corriente1'); ?>
            <?php echo $form->textField($model, 'cuenta_corriente1', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'cuenta_corriente1'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <?php echo $form->labelEx($model, 'banco2'); ?>
            <?php echo $form->dropDownList($model, 'banco2', array(
                'Banco del Pichincha' => 'Banco del Pichincha',
                'Banco del Pacífico' => 'Banco del Pacífico',
                'Banco de Guayaquil' => 'Banco de Guayaquil',
                'Produbanco' => 'Produbanco',
                'Banco Bolivariano' => 'Banco Bolivariano',
                'Banco Internacional' => 'Banco Internacional',
                'Banco del Austro' => 'Banco del Austro',
                'Banco Promerica (Ecuador)' => 'Banco Promerica (Ecuador)',
                'Banco de Machala' => 'Banco de Machala',
                'BGR' => 'BGR',
                'Citibank' => 'Citibank',
                'Banco ProCredit' => 'Banco ProCredit',
                'Banco Solidario' => 'Banco Solidario',
                'Otros' => 'Otros',
                    ),
                    array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'banco2'); ?>
        </div>
        <div class="otro-bn-2" style="display: none;">
            <div class="col-md-3">
                <label for="">Otra institución</label>
                <input type="text" name="GestionSolicitudCredito[otro2]" id="GestionSolicitudCredito_otro2" class="form-control"/>
                
            </div>
        </div>
        <div class="col-md-3">
            <?php echo $form->labelEx($model, 'cuenta_ahorros2'); ?>
            <?php echo $form->textField($model, 'cuenta_ahorros2', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'cuenta_ahorros2'); ?>
        </div>
        <div class="col-md-3">
            <?php echo $form->labelEx($model, 'cuenta_corriente2'); ?>
            <?php echo $form->textField($model, 'cuenta_corriente2', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'cuenta_corriente2'); ?>
        </div>
    </div>

    <div class="row">
        <h1 class="tl_seccion_rf">Referencias Personales</h1>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?php echo $form->labelEx($model, 'referencia_personal1'); ?>
            <?php echo $form->textField($model, 'referencia_personal1', array('size' => 60, 'maxlength' => 150, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'referencia_personal1'); ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->labelEx($model, 'parentesco1'); ?>
            <?php echo $form->dropDownList($model, 'parentesco1', array(
                '' => '-Seleccione-',
                'Padre' => 'Padre',
                'Madre' => 'Madre',
                'Primo' => 'Primo',
                'Tio' => 'Tio',
                'Abuelo' => 'Abuelo',
                'Amigo' => 'Amigo'),array( 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'parentesco1'); ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->labelEx($model, 'telefono_referencia1'); ?>
            <?php echo $form->textField($model, 'telefono_referencia1', array('size' => 60, 'maxlength' => 60, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'telefono_referencia1'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?php echo $form->labelEx($model, 'referencia_personal2'); ?>
            <?php echo $form->textField($model, 'referencia_personal2', array('size' => 60, 'maxlength' => 150, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'referencia_personal2'); ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->labelEx($model, 'parentesco2'); ?>
            <?php echo $form->dropDownList($model, 'parentesco2', array(
                '' => '-Seleccione-',
                'Padre' => 'Padre',
                'Madre' => 'Madre',
                'Primo' => 'Primo',
                'Tio' => 'Tio',
                'Abuelo' => 'Abuelo',
                'Amigo' => 'Amigo'),array( 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'parentesco2'); ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->labelEx($model, 'telefono_referencia2'); ?>
            <?php echo $form->textField($model, 'telefono_referencia2', array('size' => 60, 'maxlength' => 60, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'telefono_referencia2'); ?>
        </div>
    </div>
    <div class="row">
        <h1 class="tl_seccion_rf">Activos y Propiedades</h1>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="col-md-4">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" value="Local" name="activos[]" id="">
                        Local
                    </label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" value="Finca" name="activos[]" id="">
                        Finca
                    </label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" value="Casa" name="activos[]" id="">
                        Casa
                    </label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" value="Dpto" name="activos[]" id="">
                        Dpto
                    </label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" value="Lote" name="activos[]" id="">
                        Lote
                    </label>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <label for="">Dirección</label>
            <input type="text" class="form-control" name="GestionSolicitudCredito[direccion_activo1]"/>
        </div>
        <div class="col-md-3">
            <label for="">Sector</label>
            <input type="text" class="form-control" name="GestionSolicitudCredito[direccion_activo1]"/>
        </div><div class="col-md-3">
            <label for="">Valor Comercial</label>
            <input type="text" class="form-control" name="GestionSolicitudCredito[direccion_activo1]"/>
        </div>
        
        <div class="col-md-4f">
            <div class="form-group">
                <label for="exampleInputName2" class="col-sm-1 control-label">Casa</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="casa" name="casa">
                </div>

            </div>
        </div>
        <div class="col-md-4f">
            <div class="form-group">
                <label for="exampleInputName2" class="col-sm-1 control-label">Cuentas x Pagar Bancos</label>
                <div class="col-sm-3"><input type="text" class="form-control" id="casa" name="casa"></div>

            </div>
        </div>
        <div class="col-md-4f">
            <div class="form-group">
                <label for="exampleInputName2" class="col-sm-1 control-label">Activos</label>
                <div class="col-sm-3"><input type="text" class="form-control" id="casa" name="casa"></div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4f">
            <div class="form-group">
                <label for="exampleInputName2" class="col-sm-1 control-label">Vehículo</label>
                <div class="col-sm-3"><input type="text" class="form-control" id="casa" name="casa"></div>

            </div>
        </div>
        <div class="col-md-4f">
            <div class="form-group">
                <label for="exampleInputName2" class="col-sm-1 control-label">Cuentas x Pagar Proveedores</label>
                <div class="col-sm-3"><input type="text" class="form-control" id="casa" name="casa"></div>

            </div>
        </div>
        <div class="col-md-4f">
            <div class="form-group">
                <label for="exampleInputName2" class="col-sm-1 control-label">Pasivos</label>
                <div class="col-sm-3"><input type="text" class="form-control" id="casa" name="casa"></div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4f">
            <div class="form-group">
                <label for="exampleInputName2" class="col-sm-1 control-label">Departamento</label>
                <div class="col-sm-3"><input type="text" class="form-control" id="casa" name="casa"></div>

            </div>
        </div>
        <div class="col-md-4f">
            <div class="form-group">
                <label for="exampleInputName2" class="col-sm-1 control-label">Tarjetas de Crédito</label>
                <div class="col-sm-3"><input type="text" class="form-control" id="casa" name="casa"></div>

            </div>
        </div>
        <div class="col-md-4f">
            <div class="form-group">
                <label for="exampleInputName2" class="col-sm-1 control-label">Total Patrimonio</label>
                <div class="col-sm-3"><input type="text" class="form-control" id="casa" name="casa"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4f">
            <div class="form-group">
                <label for="exampleInputName2" class="col-sm-1 control-label">Terreno</label>
                <div class="col-sm-3"><input type="text" class="form-control" id="casa" name="casa"></div>

            </div>
        </div>
        <div class="col-md-4f">
            <div class="form-group">
                <label for="exampleInputName2" class="col-sm-1 control-label">Otros</label>
                <div class="col-sm-3"><input type="text" class="form-control" id="casa" name="casa"></div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4f">
            <div class="form-group">
                <label for="exampleInputName2" class="col-sm-1 control-label">Otros</label>
                <div class="col-sm-3"><input type="text" class="form-control" id="casa" name="casa"></div>
            </div>
        </div>
        <div class="col-md-4f">
            <div class="form-group">
                <label for="exampleInputName2" class="col-sm-1 control-label">Total Pasivos</label>
                <div class="col-sm-3"><input type="text" class="form-control" id="casa" name="casa"></div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4f">
            <div class="form-group">
                <label for="exampleInputName2" class="col-sm-1 control-label">Total Activos</label>
                <div class="col-sm-3"><input type="text" class="form-control" id="casa" name="casa"></div>
            </div>
        </div>
    </div>
    <div class="row buttons">
        <div class="col-md-4">

            <input class="btn btn-danger" id="finalizar" type="submit" name="yt0" value="Enviar" onclick="send();">
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <a href="<?php echo Yii::app()->createUrl('gestionSolicitudCredito/cotizacion/', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo)); ?>" class="btn btn-warning" id="generatepdf" style="display: none;" target="_blank">Solicitud de Crédito</a>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
            </div>
        </div>
    </div>

</div>