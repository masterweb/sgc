<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//die('id informacion: '.$id_informacion);
//echo '<pre>';
//print_r($_GET);
//echo '</pre>';
//die();
?>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#form-status').validate({
            rules: {
                'GestionStatus[status]': {
                    required: true
                }
            },
            messages: {
                'GestionStatus[status]': {
                    required: 'Seleccione una opción'
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
        $('#GestionStatus_status').change(function () {
            var value = $(this).attr('value');
            if (value == '2') {
                $('.cont-obs').hide();
            } else {
                $('.cont-obs').show();
            }
        });
    });
</script>
<div class="container">
    <div class="row">
        <h1 class="tl_seccion">Status Solicitudes de Crédito</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php
            $this->widget('zii.widgets.CDetailView', array(
                'data' => $modelst,
                'htmlOptions' => array('class' => 'table table-striped'),
                'attributes' => array(
                    'id',
                    array(
                        'name' => 'concesionario',
                        'type' => 'raw', //because of using html-code <br/>
                        'value' => $this->getConcesionario($this->getDealerId(Yii::app()->user->getId()))
                    ),
                    array(
                        'name' => 'vendedor',
                        'type' => 'raw', //because of using html-code <br/>
                        //call the controller method gridProduct for each row
                        'value' => util::getResponsableCon($modelst->vendedor),
                    ),
                    'fecha',
                    'modelo',
                    array(
                        'name' => 'valor',
                        'type' => 'raw', //because of using html-code <br/>
                        'value' => '$. '.number_format($modelst->valor)
                    ),
                    array(
                        'name' => 'monto_financiar',
                        'type' => 'raw', //because of using html-code <br/>
                        'value' => '$. '.number_format($modelst->monto_financiar)
                    ),
                    array(
                        'name' => 'entrada',
                        'type' => 'raw', //because of using html-code <br/>
                        'value' => '$. '.number_format($modelst->entrada)
                    ),
                    'year',
                    array(
                        'name' => 'plazo',
                        'type' => 'raw', //because of using html-code <br/>
                        'value' => $modelst->plazo.' meses'
                    ),
                    array(
                        'name' => 'taza',
                        'type' => 'raw', //because of using html-code <br/>
                        'value' => $modelst->taza.' %'
                    ),
                    array(
                        'name' => 'cuota_mensual',
                        'type' => 'raw', //because of using html-code <br/>
                        'value' => '$. '.number_format($modelst->cuota_mensual)
                    ),
                    'apellido_paterno',
                    'apellido_materno',
                    'nombres',
                    'cedula',
                    'fecha_nacimiento',
                    'nacionalidad',
                    'estado_civil',
                    'empresa_trabajo',
                    'telefonos_trabajo',
                    array(
                        'name' => 'tiempo_trabajo',
                        'type' => 'raw', //because of using html-code <br/>
                        'value' => util::getTiempoTrabajo($modelst->id_informacion)
                    ),
                    'cargo',
                    'direccion_empresa',
                    'tipo_relacion_laboral',
                    'email',
                    'actividad_empresa',
                    'apellido_paterno_conyugue',
                    'apellido_materno_conyugue',
                    'nombres_conyugue',
                    'cedula_conyugue',
                    'fecha_nacimiento_conyugue',
                    'nacionalidad_conyugue',
                    'empresa_trabajo_conyugue',
                    'telefono_trabajo_conyugue',
                    'tiempo_trabajo_conyugue',
                    'cargo_conyugue',
                    'direccion_empresa_conyugue',
                    'tipo_relacion_laboral_conyugue',
                    'domicilio_actual',
                    'habita',
                    'avaluo_propiedad',
                    'vive',
                    array(
                        'name' => 'valor_arriendo',
                        'type' => 'raw', //because of using html-code <br/>
                        'value' => '$. '.($modelst->valor_arriendo)
                    ),
                    'calle',
                    'barrio',
                    'referencia_domicilio',
                    'telefono_residencia',
                    'celular',
                    array(
                        'name' => 'sueldo_mensual',
                        'type' => 'raw', //because of using html-code <br/>
                        'value' => '$. '.($modelst->sueldo_mensual)
                    ),
                    array(
                        'name' => 'sueldo_mensual_conyugue',
                        'type' => 'raw', //because of using html-code <br/>
                        'value' => '$. '.number_format ((int)$modelst->sueldo_mensual_conyugue)
                    ),
                    array(
                        'name' => 'banco1',
                        'type' => 'raw', //because of using html-code <br/>
                        'value' => util::getBanco($modelst->banco1)
                    ),
                    'cuenta1',
                    array(
                        'name' => 'banco2',
                        'type' => 'raw', //because of using html-code <br/>
                        'value' => util::getBanco($modelst->banco2)
                    ),
                    'cuenta2',
                    'referencia_personal1',
                    'referencia_personal2',
                    'parentesco1',
                    'parentesco2',
                    'telefono_referencia1',
                    'telefono_referencia2',
                    'activos',
                    'pasivos',
                    'patrimonio',
                    'id_informacion',
//                    array(
//                        'name' => 'id_vehiculo',
//                        'type' => 'raw', //because of using html-code <br/>
//                        'value' => $this->getModeloTestDrive($modelst->id_vehiculo).' '.$this->getVersionTestDrive($modelst->id_vehiculo)
//                    ),
                ),
            ));
            ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="highlight">
            <div class="form">
                <form action="<?php echo Yii::app()->createUrl('gestionSolicitudCredito/status/', array('id' => $_GET['id'], 'id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo)); ?>" method="post" id="form-status">
                    <div class="row">
                        <label for="">Estado de Solicitud de Crédito</label>
                        <div class="row">
                            <div class="col-md-4">
                                <select name="GestionStatus[status]" class="form-control" id="GestionStatus_status">
                                    <option value="">--Seleccione--</option>
                                    <option value="1">En Análisis</option>
                                    <option value="2">Aprobado</option>
                                    <option value="3">Negado</option>
                                    <option value="4">Condicionado</option>
                                </select>
                            </div>
                        </div>
                        <div class="cont-obs">
                            <label for="">Observaciones</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <textarea name="GestionStatus[observaciones]" id="" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row buttons">
                        <div class="col-md-3">
                            <input type="hidden" name="GestionStatus[id_informacion]" id="GestioStatus_id_informacion" value="<?php echo $id_informacion; ?>" />
                            <input type="hidden" name="GestionStatus[id_vehiculo]" id="GestioStatus_id_informacion" value="<?php echo $id_vehiculo; ?>" />
                            <input type="hidden" name="GestionStatus[id_informacion]" id="GestioStatus_id_informacion" value="<?php echo $id_informacion; ?>" />
                            <input type="hidden" name="GestionStatus[id_status]" id="GestioStatus_id_status" value="<?php echo $id_status; ?>" />
                            <input class="btn btn-danger" id="finalizar" onclick="sendInfo();" type="submit" name="yt0" value="Enviar">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>