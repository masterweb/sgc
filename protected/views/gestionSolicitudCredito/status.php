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
            /*if (value == '2') {
                $('.cont-obs').hide();
            } else {
                $('.cont-obs').show();
            }*/
        });
    });
</script>
<div class="container">
    <div class="row">
        <h1 class="tl_seccion">Status Solicitudes de Crédito</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <tbody>
                    <tr class="odd"><th>ID</th><td>146</td></tr>
                    <tr class="even"><th>Concesionario</th><td><?php echo $this->getConcesionario($this->getDealerId(Yii::app()->user->getId())); ?></td></tr>
                    <tr class="odd"><th>Vendedor</th><td><?php echo util::getResponsableCon($modelst->vendedor); ?></td></tr>
                    <tr class="even"><th>Fecha</th><td><?php echo $modelst->fecha; ?></td></tr>
                    <tr class="odd"><th>Modelo</th><td><?php echo $modelst->modelo; ?></td></tr>
                    <tr class="even"><th>Valor</th><td>$. <?php echo number_format($modelst->valor); ?></td></tr>
                    <tr class="odd"><th>Monto Financiar</th><td>$. <?php echo number_format($modelst->monto_financiar); ?></td></tr>
                    <tr class="even"><th>Entrada</th><td>$. <?php echo number_format($modelst->entrada); ?></td></tr>
                    <tr class="odd"><th>Año</th><td><?php echo $modelst->year; ?></td></tr>
                    <tr class="even"><th>Plazo</th><td><?php echo $modelst->plazo; ?> meses</td></tr>
                    <tr class="odd"><th>Tasa</th><td><?php echo $modelst->taza; ?> %</td></tr>
                    <tr class="even"><th>Cuota Mensual</th><td>$. <?php echo number_format($modelst->cuota_mensual); ?></td></tr>
                    <tr class="odd"><th>Apellido Paterno</th><td><?php echo $modelst->apellido_paterno; ?></td></tr>
                    <tr class="even"><th>Apellido Materno</th><td><?php echo $modelst->apellido_materno; ?></td></tr>
                    <tr class="odd"><th>Nombres</th><td><?php echo $modelst->nombres; ?></td></tr>
                    <?php if(!empty($modelst->cedula)){ ?>
                     <tr class="even"><th>Cédula</th><td><?php echo $modelst->cedula; ?></td></tr>
                    <?php } ?>
                     <?php if(!empty($modelst->ruc)){ ?>
                     <tr class="even"><th>Ruc</th><td><?php echo $modelst->ruc; ?></td></tr>
                    <?php } ?>
                     <?php if(!empty($modelst->pasaporte)){ ?>
                     <tr class="even"><th>Pasaporte</th><td><?php echo $modelst->pasaporte; ?></td></tr>
                    <?php } ?>
                   
                    <tr class="odd"><th>Fecha Nacimiento</th><td><?php echo $modelst->fecha_nacimiento; ?></td></tr>
                    <tr class="even"><th>Nacionalidad</th><td><?php echo $modelst->nacionalidad; ?></td></tr>
                    <tr class="odd"><th>Estado Civil</th><td><?php echo $modelst->estado_civil; ?></td></tr>
                    <tr class="even"><th>Empresa Trabajo</th><td><?php echo $modelst->empresa_trabajo; ?></td></tr>
                    <tr class="odd"><th>Teléfonos Trabajo</th><td><?php echo $modelst->telefonos_trabajo; ?></td></tr>
                    <tr class="even"><th>Años de Trabajo</th><td><?php echo util::getTiempoTrabajo($modelst->id_informacion); ?></td></tr>
                    <tr class="odd"><th>Cargo</th><td><?php echo $modelst->cargo; ?></td></tr>
                    <tr class="even"><th>Dirección Empresa</th><td><?php echo $modelst->direccion_empresa; ?></td></tr>
                    <tr class="odd"><th>Tipo Relación Laboral</th><td><?php echo $modelst->tipo_relacion_laboral; ?></td></tr>
                    <tr class="even"><th>Email</th><td><?php echo $modelst->email; ?></td></tr>
                    <tr class="odd"><th>Actividad Empresa</th><td><?php echo $modelst->actividad_empresa; ?></td></tr>
                    <?php if($modelst->estado_civil == 'Casado' || $modelst->estado_civil == 'Casado sin separación de bienes' || $modelst->estado_civil == 'Union Libre'){ ?>
                    <tr class="even"><th>Apellido Paterno Cónyugue</th><td><?php echo $modelst->apellido_paterno_conyugue; ?></td></tr>
                    <tr class="odd"><th>Apellido Materno Cónyugue</th><td><?php echo $modelst->apellido_materno_conyugue; ?></td></tr>
                    <tr class="even"><th>Nombres Cónyugue</th><td><?php echo $modelst->nombres_conyugue; ?></td></tr>
                    <tr class="odd"><th>Cedula Cónyugue</th><td><?php echo $modelst->cedula_conyugue; ?></td></tr>
                    <tr class="even"><th>Fecha Nacimiento Cónyugue</th><td><?php echo $modelst->fecha_nacimiento_conyugue; ?></td></tr>
                    <tr class="odd"><th>Nacionalidad Cónyugue</th><td><?php echo $modelst->nacionalidad_conyugue; ?></td></tr>
                    <tr class="even"><th>Empresa Trabajo</th><td><?php echo $modelst->empresa_trabajo_conyugue; ?></td></tr>
                    <tr class="odd"><th>Teléfono Trabajo</th><td><?php echo $modelst->telefono_trabajo_conyugue; ?></td></tr>
                    <tr class="even"><th>Años de Trabajo</th><td><?php echo util::getTiempoTrabajoConyugue($modelst->id_informacion); ?></td></tr>
                    <tr class="odd"><th>Cargo Cónyugue</th><td><?php echo ucfirst($modelst->cargo_conyugue); ?></td></tr>
                    <tr class="even"><th>Dirección Empresa Cónyugue</th><td><?php echo $modelst->direccion_empresa_conyugue; ?></td></tr>
                    <tr class="odd"><th>Tipo Relación Laboral Cónyugue</th><td><?php echo $modelst->tipo_relacion_laboral_conyugue; ?></td></tr>
                    <?php } ?>
                    <tr class="even"><th>Domicilio Actual</th><td><?php echo $this->getProvincia($modelst->provincia_domicilio); ?></td></tr>
                    <tr class="odd"><th>Tipo de Propiedad</th><td><?php echo $modelst->habita; ?></td></tr>
                    <?php if($modelst->habita == 'Propia'){ ?>
                    <tr class="even"><th>Avalúo Propiedad</th><td><?php echo '$. '.number_format($modelst->avaluo_propiedad); ?></td></tr>
                    <?php } ?>
                    <?php if($modelst->habita == 'Rentada'){ ?>
                    <tr class="even"><th>Valor de Arriendo</th><td><?php echo '$. '.number_format($modelst->valor_arriendo); ?></td></tr>
                    <?php } ?>
                    
                    <tr class="odd"><th>Calle Principal</th><td><?php echo $modelst->calle; ?></td></tr>
                    <tr class="even"><th>Barrio</th><td><?php echo $modelst->barrio; ?></td></tr>
                    <tr class="odd"><th>Referencia Domicilio</th><td><?php echo $modelst->referencia_domicilio; ?></td></tr>
                    <tr class="even"><th>Teléfono Residencia</th><td><?php echo $modelst->telefono_residencia; ?></td></tr>
                    <tr class="odd"><th>Celular</th><td><?php echo $modelst->celular; ?></td></tr>
                    <tr class="even"><th>Sueldo Mensual</th><td>$. <?php echo $modelst->sueldo_mensual; ?></td></tr>
                    <?php if($modelst->estado_civil == 'Casado' || $modelst->estado_civil == 'Casado sin separación de bienes' || $modelst->estado_civil == 'Union Libre'){ ?>
                    <tr class="odd"><th>Sueldo Mensual Conyugue</th><td>$. <?php echo $modelst->sueldo_mensual_conyugue; ?></td></tr>
                    <?php } ?>
                    <tr class="even"><th>Banco 1</th><td><?php echo util::getBanco($modelst->banco1); ?></td></tr>
                    <tr class="odd"><th>Cuenta1</th><td><?php echo $modelst->cuenta_ahorros1; ?></td></tr>
                    <?php if(!empty($modelst->banco2)){ ?>
                    <tr class="even"><th>Banco 2</th><td><?php echo util::getBanco($modelst->banco2); ?></td></tr>
                    <tr class="odd"><th>Cuenta2</th><td><?php echo $modelst->cuenta_ahorros2; ?></td></tr>
                    <?php } ?>
                    <tr class="even"><th>Referencia Personal 1</th><td><?php echo $modelst->referencia_personal1; ?></td></tr>
                    <tr class="even"><th>Parentesco</th><td><?php echo $modelst->parentesco1; ?></td></tr>
                    <tr class="even"><th>Teléfono Referencia 1</th><td><?php echo $modelst->telefono_referencia1; ?></td></tr>
                    <tr class="odd"><th>Referencia Personal 2</th><td><?php echo $modelst->referencia_personal2; ?></td></tr>
                    <tr class="odd"><th>Parentesco</th><td><?php echo $modelst->parentesco2; ?></td></tr>
                    <tr class="odd"><th>Teléfono Referencia 2</th><td><?php echo $modelst->telefono_referencia2; ?></td></tr>
                    <tr class="even"><th>Activos</th><td><?php echo $modelst->activos; ?></td></tr>
                    <tr class="odd"><th>Pasivos</th><td><?php echo $modelst->pasivos; ?></td></tr>
                    <tr class="even"><th>Patrimonio</th><td><?php echo $modelst->patrimonio; ?></td></tr>
                </tbody>
            </table>
            <?php
//            $this->widget('zii.widgets.CDetailView', array(
//                'data' => $modelst,
//                'htmlOptions' => array('class' => 'table table-striped'),
//                'attributes' => array(
//                    'id',
//                    array(
//                        'name' => 'concesionario',
//                        'type' => 'raw', //because of using html-code <br/>
//                        'value' => $this->getConcesionario($this->getDealerId(Yii::app()->user->getId()))
//                    ),
//                    array(
//                        'name' => 'vendedor',
//                        'type' => 'raw', //because of using html-code <br/>
//                        //call the controller method gridProduct for each row
//                        'value' => util::getResponsableCon($modelst->vendedor),
//                    ),
//                    'fecha',
//                    'modelo',
//                    array(
//                        'name' => 'valor',
//                        'type' => 'raw', //because of using html-code <br/>
//                        'value' => '$. ' . number_format($modelst->valor)
//                    ),
//                    array(
//                        'name' => 'monto_financiar',
//                        'type' => 'raw', //because of using html-code <br/>
//                        'value' => '$. ' . number_format($modelst->monto_financiar)
//                    ),
//                    array(
//                        'name' => 'entrada',
//                        'type' => 'raw', //because of using html-code <br/>
//                        'value' => '$. ' . number_format($modelst->entrada)
//                    ),
//                    'year',
//                    array(
//                        'name' => 'plazo',
//                        'type' => 'raw', //because of using html-code <br/>
//                        'value' => $modelst->plazo . ' meses'
//                    ),
//                    array(
//                        'name' => 'tasa',
//                        'type' => 'raw', //because of using html-code <br/>
//                        'value' => $modelst->taza . ' %'
//                    ),
//                    array(
//                        'name' => 'cuota_mensual',
//                        'type' => 'raw', //because of using html-code <br/>
//                        'value' => '$. ' . number_format($modelst->cuota_mensual)
//                    ),
//                    'apellido_paterno',
//                    'apellido_materno',
//                    'nombres',
//                    'cedula',
//                    'fecha_nacimiento',
//                    'nacionalidad',
//                    'estado_civil',
//                    'empresa_trabajo',
//                    'telefonos_trabajo',
//                    array(
//                        'name' => 'tiempo_trabajo',
//                        'type' => 'raw', //because of using html-code <br/>
//                        'value' => util::getTiempoTrabajo($modelst->id_informacion)
//                    ),
//                    'cargo',
//                    'direccion_empresa',
//                    'tipo_relacion_laboral',
//                    'email',
//                    'actividad_empresa',
//                    'apellido_paterno_conyugue',
//                    'apellido_materno_conyugue',
//                    'nombres_conyugue',
//                    'cedula_conyugue',
//                    'fecha_nacimiento_conyugue',
//                    'nacionalidad_conyugue',
//                    'empresa_trabajo_conyugue',
//                    'telefono_trabajo_conyugue',
//                    'tiempo_trabajo_conyugue',
//                    'cargo_conyugue',
//                    'direccion_empresa_conyugue',
//                    'tipo_relacion_laboral_conyugue',
//                    'domicilio_actual',
//                    'habita',
//                    'avaluo_propiedad',
//                    'vive',
//                    array(
//                        'name' => 'valor_arriendo',
//                        'type' => 'raw', //because of using html-code <br/>
//                        'value' => '$. ' . ($modelst->valor_arriendo)
//                    ),
//                    'calle',
//                    'barrio',
//                    'referencia_domicilio',
//                    'telefono_residencia',
//                    'celular',
//                    array(
//                        'name' => 'sueldo_mensual',
//                        'type' => 'raw', //because of using html-code <br/>
//                        'value' => '$. ' . ($modelst->sueldo_mensual)
//                    ),
//                    array(
//                        'name' => 'sueldo_mensual_conyugue',
//                        'type' => 'raw', //because of using html-code <br/>
//                        'value' => '$. ' . number_format((int) $modelst->sueldo_mensual_conyugue)
//                    ),
//                    array(
//                        'name' => 'banco1',
//                        'type' => 'raw', //because of using html-code <br/>
//                        'value' => util::getBanco($modelst->banco1)
//                    ),
//                    'cuenta1',
//                    array(
//                        'name' => 'banco2',
//                        'type' => 'raw', //because of using html-code <br/>
//                        'value' => util::getBanco($modelst->banco2)
//                    ),
//                    'cuenta2',
//                    'referencia_personal1',
//                    'referencia_personal2',
//                    'parentesco1',
//                    'parentesco2',
//                    'telefono_referencia1',
//                    'telefono_referencia2',
//                    'activos',
//                    'pasivos',
//                    'patrimonio',
//                    'id_informacion',
////                    array(
////                        'name' => 'id_vehiculo',
////                        'type' => 'raw', //because of using html-code <br/>
////                        'value' => $this->getModeloTestDrive($modelst->id_vehiculo).' '.$this->getVersionTestDrive($modelst->id_vehiculo)
////                    ),
//                ),
//            ));
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