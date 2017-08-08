<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/moment.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/daterangepicker.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/daterangepicker.css" type="text/css" />
<script type="text/javascript">
    $(document).ready(function() {
        $('#trafico_fecha').daterangepicker({locale: {format: 'YYYY/MM/DD'},startDate: '<?php echo $vartrf['fecha_inicial']; ?>',endDate: '<?php echo $vartrf['fecha_actual']; ?>'});
        $('#trafico_grupo').change(function(){var value = $(this).attr('value');var provincia = $('#trafico_provincia').val();
            $.ajax({url: '<?php echo Yii::app()->createAbsoluteUrl("site/getConcesionarios"); ?>',
                beforeSend: function (xhr) {
                    $('#info3').show();  // #info must be defined somehwere
                },
                type: 'POST', data: {id: value,provincia: provincia},
                success: function (data) { $('#trafico_concesionario').html(data);}
            });
        });
        $('#trafico_concesionario').change(function(){
            var value = $(this).attr('value');
            var trafico_fuente_contacto = $('#trafico_fuente_contacto').val();
            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("trafico/getAsesores"); ?>',
                beforeSend: function (xhr) {
                //$('#info-3').show();  // #bg_negro must be defined somewhere
                },
                type: 'POST',
                //dataType: 'json', 
                data: {dealer_id: value, tipo:trafico_fuente_contacto},
                success: function (data) {
                        $('#trafico_responsable').html(data);
                }
            });
        });
        $('#trafico_fecha').change(function(){var value = $(this).attr('value');if ($(this).val() != ''){$('#fecha').val(1);} else{$('#fecha').val(0);}});
        $('#trafico_grupo').change(function(){var value = $(this).attr('value');if ($(this).val() != ''){$('#grupo').val(1);} else{$('#grupo').val(0);}});
        $('#trafico_concesionario').change(function(){var value = $(this).attr('value');if ($(this).val() != ''){$('#concesionario').val(1);} else{$('#concesionario').val(0);}});
        $('#trafico_responsable').change(function(){if ($(this).val() != ''){$('#responsable').val(1);} else{$('#responsable').val(0);}});
        $('#trafico_fuente_contacto').change(function(){
            if ($(this).val() != ''){$('#fuente_contacto').val(1);} else{$('#fuente_contacto').val(0);}
            var value = $('#trafico_fuente_contacto option:selected').val();
            var options = '';
            switch(value){
                case 'showroom':
                case 'exhibicion':
                    options += `<option value="">--Seleccione tipo--</option>
                                <option value="1" selected="">Trafico</option>
                                <option value="2">Proformas</option>
                                <option value="3">TestDrive</option>
                                <option value="4">Ventas</option>`;
                break;
                case 'web':
                    options += `<option value="">--Seleccione tipo--</option>
                                <option value="5" selected="">Solicitudes Web</option>
                                <option value="6">Proformas</option>
                                <option value="7">Citas</option>
                                <option value="8">TestDrive</option>
                                <option value="9">Ventas</option>`;
                break;
                case 'asiautoweb':
                options += `<option value="">--Seleccione tipo--</option>
                                <option value="10" selected="">Solicitudes Recibidas</option>
                                <option value="11">Proformas Enviadas</option>
                                <option value="12">Citas Generadas</option>
                                <option value="13">Citas Concretadas</option>
                                <option value="14">TestDrive</option>
                                <option value="15">Ventas</option>`;
                break;
            }
            $('#trafico_tipo_reporte').html(options);

        });
        $('#send-excel').click(function(){
            var fecha = $('#fecha').val();
            var grupo = $('#grupo').val();
            var concesionario = $('#concesionario').val();
            var responsable = $('#responsable').val();
            var fuente_contacto = $('#fuente_contacto').val();
            var tipo_reporte = $('#tipo_reporte').val();
            var trafico_fecha = $('#trafico_fecha').val();
            var trafico_grupo = $('#trafico_grupo').val();
            var trafico_concesionario = $('#trafico_concesionario').val();
            var trafico_responsable = $('#trafico_responsable').val();
            var trafico_fuente_contacto = $('#trafico_fuente_contacto').val();
            var trafico_tipo_reporte = $('#trafico_tipo_reporte').val();
            var flag_search = 0;
            
            // BUSQUEDA POR DEFECTO SHOWROOM, PROSPECCION, WEB, EXHIBICION
            if (fecha == 0 && grupo == 0 && concesionario == 0 && responsable == 0 && fuente_contacto == 1 && tipo_reporte == 1) {
                getTitulo(trafico_fecha,trafico_grupo,trafico_concesionario,trafico_responsable,trafico_fuente_contacto,trafico_tipo_reporte, grupo, concesionario, responsable);
                
                flag_search = 1;
            }
            // BUSQUEDA POR FECHAS - GRUPO
            if (fecha == 1 && grupo == 1 && concesionario == 0 && responsable == 0 && fuente_contacto == 1 && tipo_reporte == 1) {
                getTitulo(trafico_fecha,trafico_grupo,trafico_concesionario,trafico_responsable,trafico_fuente_contacto,trafico_tipo_reporte, grupo, concesionario, responsable);
                
                flag_search = 2;
            }

            // BUSQUEDA POR FECHAS - GRUPO - CONCESIONARIO
            if (fecha == 1 && grupo == 1 && concesionario == 1 && responsable == 0 && fuente_contacto == 1 && tipo_reporte == 1) {
                getTitulo(trafico_fecha,trafico_grupo,trafico_concesionario,trafico_responsable,trafico_fuente_contacto,trafico_tipo_reporte, grupo, concesionario, responsable);
                
                flag_search = 3;
            }

            // BUSQUEDA POR FECHAS - GRUPO - CONCESIONARIO -RESPONSABLE
            if (fecha == 1 && grupo == 1 && concesionario == 1 && responsable == 1 && fuente_contacto == 1 && tipo_reporte == 1) {
                getTitulo(trafico_fecha,trafico_grupo,trafico_concesionario,trafico_responsable,trafico_fuente_contacto,trafico_tipo_reporte, grupo, concesionario, responsable);
                
                flag_search = 4;
            }

            // BUSQUEDA POR GRUPO
            if (fecha == 0 && grupo == 1 && concesionario == 0 && responsable == 0 && fuente_contacto == 1 && tipo_reporte == 1) {
                getTitulo(trafico_fecha,trafico_grupo,trafico_concesionario,trafico_responsable,trafico_fuente_contacto,trafico_tipo_reporte, grupo, concesionario, responsable);
                
                flag_search = 5;
            }

            // BUSQUEDA POR GRUPO - CONCESIONARIO
            if (fecha == 0 && grupo == 1 && concesionario == 1 && responsable == 0 && fuente_contacto == 1 && tipo_reporte == 1) {
                getTitulo(trafico_fecha,trafico_grupo,trafico_concesionario,trafico_responsable,trafico_fuente_contacto,trafico_tipo_reporte, grupo, concesionario, responsable);
               
                flag_search = 6;
            }

            // BUSQUEDA POR GRUPO - CONCESIONARIO - RESPONSABLE
            if (fecha == 0 && grupo == 1 && concesionario == 1 && responsable == 1 && fuente_contacto == 1 && tipo_reporte == 1) {
                getTitulo(trafico_fecha,trafico_grupo,trafico_concesionario,trafico_responsable,trafico_fuente_contacto,trafico_tipo_reporte, grupo, concesionario, responsable);
                
                flag_search = 7;
            }
            //console.log('flag_search: '+flag_search);

        });
    });
    function getTitulo(trafico_fecha,trafico_grupo,trafico_concesionario,trafico_responsable,trafico_fuente_contacto,trafico_tipo_reporte, grupo, concesionario, responsable){
        $.ajax({
            url: '<?php echo Yii::app()->createAbsoluteUrl("trafico/getTitulo"); ?>',
            type: 'POST',
            data:{trafico_fecha:trafico_fecha, trafico_grupo:trafico_grupo, trafico_concesionario:trafico_concesionario,trafico_responsable:trafico_responsable,trafico_fuente_contacto:trafico_fuente_contacto,trafico_tipo_reporte:trafico_tipo_reporte, grupo:grupo, concesionario:concesionario, responsable: responsable},
            success: function(data){
                $('#title-comp').html(data);
                //alert('Titulo cambiado: '+data);
            }
        });
    }
</script>
<style type="text/css">
    .tl_seccion{margin-left:0px;}
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="tl_seccion">Reportes Tráfico</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsives">
                <table class="table table-bordered" id="trafico_nacional" name="main_info_1">
                    <tr>
                        <th colspan="" align="left" scope="col" id="title-comp">TRÁFICO SHOWROOM DESDE EL <?php echo $vartrf['fecha_inicial']; ?> AL <?php echo $vartrf['fecha_actual']; ?></th>
                    </tr>
                </table>
            </div>
        </div>        
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="highlight">
                <div class="form">
                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'casos-form',
                        'method' => 'get',
                        'action' => Yii::app()->createUrl('trafico/reportes'),
                        'enableAjaxValidation' => true,
                        'htmlOptions' => array(
                            'class' => 'form-horizontal form-search'
                        ),
                    ));
                    ?>
                    <h4>Búsqueda:</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Fuente de Contacto</label>
                            <select name="GestionDiaria[fuente_contacto]" id="trafico_fuente_contacto" class="form-control">
                                <option value="">--Seleccione fuente--</option>
                                <option value="showroom" selected="">Showroom</option>
                                <!--<option value="prospeccion">Prospección</option>-->
                                <option value="web">Web Nacional</option>
                                <option value="asiautoweb">Web Asiauto</option>
                                <option value="exhibicion">Exhibicion</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">Tipo de Reporte</label>
                            <select name="GestionDiaria[tipo_reporte]" id="trafico_tipo_reporte" class="form-control">
                                <option value="">--Seleccione tipo--</option>
                                <option value="1" selected="">Trafico</option>
                                <option value="2">Proformas</option>
                                <option value="3">TestDrive</option>
                                <option value="4">Ventas</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="Trafico_fecha">Fecha inicio</label>
                            <input type="text" name="GestionDiaria[fecha]" id="trafico_fecha" class="form-control"/>
                        </div>
                        <div class="col-md-6">
                            <label for="Trafico_grupo">Grupo</label>
                            <select name="GestionDiaria[grupo]" id="trafico_grupo" class="form-control">
                                <option value="">--Seleccione Grupo--</option>
                                <option value="1000">TODOS</option>
                                <option value="6">AUTHESA</option>
                                <option value="2">GRUPO ASIAUTO</option>
                                <option value="5">GRUPO EMPROMOTOR</option>
                                <option value="3">GRUPO KMOTOR</option>
                                <option value="8">GRUPO MERQUIAUTO</option>
                                <option value="9">GRUPO MOTRICENTRO</option>
                                <option value="4">IOKARS</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Concesionario</label>
                            <select name="GestionDiaria[concesionario]" id="trafico_concesionario" class="form-control">
                                
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">Responsable</label>
                            <select name="GestionDiaria[responsable]" id="trafico_responsable" class="form-control">
                                
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="submit" name="" id="send-excel" value="Descargar Excel" class="btn btn-danger"/>
                            <input type="hidden" name="fecha" id="fecha" value="0" />
                            <input type="hidden" name="grupo" id="grupo" value="0" />
                            <input type="hidden" name="concesionario" id="concesionario" value="0" />
                            <input type="hidden" name="responsable" id="responsable" value="0" />
                            <input type="hidden" name="fuente_contacto" id="fuente_contacto" value="1" />
                            <input type="hidden" name="tipo_reporte" id="tipo_reporte" value="1" />
                        </div>
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

