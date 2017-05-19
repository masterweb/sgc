<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<script type="text/javascript">
    var showDetalles=false;
    $(document).ready(function() {
        
    });
    function detailmedios(tipo_medio, fecha_inicial, fecha_final,fecha_anterior_inicial, fecha_anterior_final, provincia,obj){


       var detalleActual=[];
       var detalleAnterior=[];
        $('.detalleRow').remove();
        if(!showDetalles){
                     $.ajax({
                        url: '<?php echo Yii::app()->createAbsoluteUrl("gestionMedios/traficoDetalle"); ?>',
                        beforeSend: function (xhr) { 
                            $('#bg_black').show();  // #info must be defined somehwere
                        },
                        type: 'POST',
                        dataType: 'json',
                        data: {tipo_medio:tipo_medio, fecha_inicial:fecha_inicial,fecha_final:fecha_final,provincia:provincia},
                        success: function (data) {
                            
                            var contadorActual=data.data.length;
                            detalleActual=data.data;
                            var contadorAnterior=0;
                            var contadorMayor=0;
                            var cadena='';
                            $.ajax({
                                url: '<?php echo Yii::app()->createAbsoluteUrl("gestionMedios/traficoDetalle"); ?>',
                                beforeSend: function (xhr) { 
                                    $('#bg_black').show();  // #info must be defined somehwere
                                },
                                type: 'POST',
                                dataType: 'json',
                                data: {tipo_medio:tipo_medio, fecha_inicial:fecha_anterior_inicial,fecha_final:fecha_anterior_final,provincia:provincia},
                                success: function (data1) {
                                    detalleAnterior=data1.data;
                                    contadorAnterior=data1.data.length;
                                    if(contadorActual>contadorAnterior)contadorMayor=contadorActual;
                                    else contadorMayor=contadorAnterior;

                                    for(i=0;i<contadorMayor;i++){

                                            if(i<contadorActual){
                                                medioActual=detalleActual[i].medio;
                                                cantidadActal=detalleActual[i].cantidad;
                                            }
                                            else{
                                                 medioActual="";
                                                cantidadActal="";
                                            }
                                             if(i<contadorAnterior){
                                                medioAnterior=detalleAnterior[i].medio;
                                                cantidadAnterior=detalleAnterior[i].cantidad;
                                            }
                                            else{
                                                 medioAnterior="";
                                                cantidadAnterior="";
                                            }
                                            cadena+='<tr class="detalleRow"><td style="background-color:#6777F0; text-align:center">'+medioActual+'</td><td style="background-color:#6777F0;">'+cantidadActal+'</td><td style="background-color:#6777F0; text-align:center">'+medioAnterior+'</td><td style="background-color:#6777F0;">'+cantidadAnterior+'</td></tr>';
                                    }
                                     var newrow = $(cadena);
                                     
                                     $(obj).after(newrow);
                                     showDetalles=true;
                                },
                                error:function(error1){
                                    alert(JSON.stringify(error1));
                                }
                            });
                        },
                        error:function(error){
                            alert(JSON.stringify(error));
                        }
                    });
         }
         else{
            showDetalles=false;
         }
    }
</script>
<style type="text/css">
    .tl_seccion_gris{margin-left: 0px;}
</style>
<div class="container">
    <div class="row">
        <h1 class="tl_seccion">Reporte de Medios</h1>
    </div>
    <div class="row paleta">
        
    </div>
    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'reporte-gestion',
                        'enableAjaxValidation' => false,
                        'htmlOptions' => array(
                            //'onsubmit' => "return false;", /* Disable normal form submit */
                            //'onkeypress' => " if(event.keyCode == 13){ send(); } " /* Do ajax call when user presses enter key */
                        ),
                    ));

    ?>
    <div class="row highlight filtrosReportes"><!-- filtro de fechas-->
        <div class="col-md-6">
            <label for="">Fecha Inicial</label>
              <input type="text" name="GI[fecha1]" class="form-control" id="fecha-range1" value="<?= $varView['fecha_inicial_actual'].' - '.$varView['fecha_actual'] ?>"/>
                    <i class="fa fa-calendar glyphicon glyphicon-calendar cal_out"></i>
         </div>
          <div class="col-md-6">
              <label for="">Fecha Final</label>
               <input type="text" name="GI[fecha2]" class="form-control fecha_rep" id="fecha-range2" value="<?= $varView['fecha_inicial_anterior'].' - '.$varView['fecha_anterior'] ?>"/>
                    <i class="fa fa-calendar glyphicon glyphicon-calendar cal_out"></i>
         </div>

        <div class="col-md-6">
            <input class="btn btn-primary" type="submit" name="yt0"  id="generarReporteGestionMedios" value="Buscar">
        </div>
    </div>
            
    <?php $this->endWidget(); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="main_info_1">
                    <thead>
                        <th colspan="2" scope="col">Fecha Inicial: <span id="fecha_inicial" name="fecha_inicial"><?php echo $varView['fecha_inicial_actual'].' al '. $varView['fecha_actual'] ?></span>
                            
                        </th>
                        <th colspan="2"  scope="col">Fecha Final: <span id="fecha_final"><?php echo $varView['fecha_inicial_anterior'].' al '. $varView['fecha_anterior'] ?></span>
                            
                        </th>
                    </thead>
                    <thead>
                        <tr>
                            <th>Medio</th>
                            <th>Cantidad</th>
                            <th>Medio</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>Cine</td><td><?php echo $varView['cine']; ?></td>
                            <td>Cine</td><td><?php echo $varView['cine_anterior']; ?></td>
                        </tr>
                        <tr id="detalleTelevision">
                            <td style="cursor: pointer" onclick="detailmedios('television','<?php echo $varView['fecha_inicial_actual']; ?>', '<?php echo $varView['fecha_actual']; ?>','<?php echo $varView['fecha_inicial_anterior']; ?>', '<?php echo $varView['fecha_anterior']; ?>',null,'#detalleTelevision' )">Televisión</td>
                            <td><?php echo $varView['television']; ?></td>
                            <td style="cursor: pointer" onclick="detailmedios('television','<?php echo $varView['fecha_inicial_actual']; ?>', '<?php echo $varView['fecha_actual']; ?>','<?php echo $varView['fecha_inicial_anterior']; ?>', '<?php echo $varView['fecha_anterior']; ?>',null,'#detalleTelevision' )">Television</td>
                            <td><?php echo $varView['television_anterior']; ?></td>
                        </tr>

                       
                        <tr id="detallePrensa"><td style="cursor: pointer" onclick="detailmedios('prensa_escrita','<?php echo $varView['fecha_inicial_actual']; ?>', '<?php echo $varView['fecha_actual']; ?>','<?php echo $varView['fecha_inicial_anterior']; ?>', '<?php echo $varView['fecha_anterior']; ?>',null,'#detallePrensa' )">Prensa Escrita</td>
                            <td><?php echo $varView['prensa_escrita']; ?></td>
                            <td style="cursor: pointer" onclick="detailmedios('prensa_escrita','<?php echo $varView['fecha_inicial_actual']; ?>', '<?php echo $varView['fecha_actual']; ?>','<?php echo $varView['fecha_inicial_anterior']; ?>', '<?php echo $varView['fecha_anterior']; ?>',null,'#detallePrensa' )">Prensa Escrita</td>
                            <td><?php echo $varView['prensa_escrita_anterior']; ?></td>
                        </tr>
                        <tr class="prensa_escrita-td"></tr>
                        <tr><td>Radio</td><td><?php echo $varView['radio']; ?></td>
                            <td>Radio</td><td><?php echo $varView['radio_anterior']; ?></td></tr>
                        <tr><td>Le recomendaron</td><td><?php echo $varView['recomendacion']; ?></td>
                            <td>Le recomendaron</td><td><?php echo $varView['recomendacion_anterior']; ?></td></tr>
                        <tr><td>Página Web</td><td><?php echo $varView['pagina_web']; ?></td>
                            <td>Página Web</td><td><?php echo $varView['pagina_web_anterior']; ?></td></tr>
                        <tr><td>Internet</td><td><?php echo $varView['internet']; ?></td>
                            <td>Internet</td><td><?php echo $varView['internet_anterior']; ?></td></tr>
                        <tr><td>Redes Sociales</td><td><?php echo $varView['redes_sociales']; ?></td>
                            <td>Redes Sociales</td><td><?php echo $varView['redes_sociales_anterior']; ?></td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->renderPartial('//reportes/modulos/footer-gestionMedios', array('varView' => $varView)); ?>