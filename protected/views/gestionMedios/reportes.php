<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<script type="text/javascript">
  
    $(document).ready(function() {
        
    });
    function detailmedios(tipo_medio, fecha_inicial, fecha_final,fecha_anterior_inicial, fecha_anterior_final, provincia,obj,obj_sign,obj_row){

        var sign = $(obj_sign).html();
        if(sign==='+')
        {
                    $(obj_sign).html("<img src= \"<?php echo Yii::app()->createUrl("../images/ajax-loader.gif")?>\" />");
                   var detalleActual=[];
                   var detalleAnterior=[];
                   // $('.detalleRow').remove();

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
                                            if(i % 2 != 0){
                                                cadena+='<tr class="'+obj.replace('#','')+'"><td style="background-color:#cccccc; text-align:center">'+medioActual+'</td><td style="background-color:#cccccc;">'+cantidadActal+'</td><td style="background-color:#cccccc; text-align:center">'+medioAnterior+'</td><td style="background-color:#cccccc;">'+cantidadAnterior+'</td></tr>';
                                            }
                                            if(i % 2 == 0){
                                                cadena+='<tr class="'+obj.replace('#','')+'"><td style="background-color:#999999; text-align:center">'+medioActual+'</td><td style="background-color:#999999;">'+cantidadActal+'</td><td style="background-color:#999999; text-align:center">'+medioAnterior+'</td><td style="background-color:#999999;">'+cantidadAnterior+'</td></tr>';
                                            }

                                            
                                    }
                                     var newrow = $(cadena);
                                     
                                     $(obj).after(newrow);
                                      $(obj_sign).html('-');
                                     
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
        }else {
                $(obj_row).remove();
                $(obj_sign).html('+');    
        }
    }

    function detailConsidera(tipo_considera, fecha_inicial, fecha_final,fecha_anterior_inicial, fecha_anterior_final, provincia,obj,obj_sign,obj_row){

        var sign = $(obj_sign).html();
        if(sign==='+')
        {
            $(obj_sign).html("<img src= \"<?php echo Yii::app()->createUrl("../images/ajax-loader.gif")?>\" />");
                   var detalleActual=[];
                   var detalleAnterior=[];
                   // $('.detalleRow').remove();

                     $.ajax({
                        url: '<?php echo Yii::app()->createAbsoluteUrl("gestionMedios/traficoDetalleConsidera"); ?>',
                        beforeSend: function (xhr) { 
                            $('#bg_black').show();  // #info must be defined somehwere
                        },
                        type: 'POST',
                        dataType: 'json',
                        data: {tipo_considera:tipo_considera, fecha_inicial:fecha_inicial,fecha_final:fecha_final,provincia:provincia},
                        success: function (data) {
                            
                            var contadorActual=data.data.length;
                            detalleActual=data.data;
                            var contadorAnterior=0;
                            var contadorMayor=0;
                            var cadena='';
                            $.ajax({
                                url: '<?php echo Yii::app()->createAbsoluteUrl("gestionMedios/traficoDetalleConsidera"); ?>',
                                beforeSend: function (xhr) { 
                                    $('#bg_black').show();  // #info must be defined somehwere
                                },
                                type: 'POST',
                                dataType: 'json',
                                data: {tipo_considera:tipo_considera, fecha_inicial:fecha_anterior_inicial,fecha_final:fecha_anterior_final,provincia:provincia},
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
                                            if(i % 2 != 0){
                                                cadena+='<tr class="'+obj.replace('#','')+'"><td style="background-color:#cccccc; text-align:center">'+medioActual+'</td><td style="background-color:#cccccc;">'+cantidadActal+'</td><td style="background-color:#cccccc; text-align:center">'+medioAnterior+'</td><td style="background-color:#cccccc;">'+cantidadAnterior+'</td></tr>';
                                            }
                                            if(i % 2 == 0){
                                                cadena+='<tr class="'+obj.replace('#','')+'"><td style="background-color:#999999; text-align:center">'+medioActual+'</td><td style="background-color:#999999;">'+cantidadActal+'</td><td style="background-color:#999999; text-align:center">'+medioAnterior+'</td><td style="background-color:#999999;">'+cantidadAnterior+'</td></tr>';
                                            }
                                            
                                    }
                                     var newrow = $(cadena);
                                     
                                     $(obj).after(newrow);
                                      $(obj_sign).html('-');
                                     
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
        }else {
                $(obj_row).remove();
                $(obj_sign).html('+');    
        }
    }
     function tabletoExcel(table, name) {
        var tab_text = '<table border="1px" style="font-size:14px" ">';
        var tab = document.getElementById('main_info_1'); // id of table
        var lines = tab.rows.length;
        //tab_text = tab_text + template;
        // the first headline of the table
        if (lines > 0) {
            tab_text = tab_text + '<tr bgcolor="#DFDFDF">' + tab.rows[0].innerHTML + '</tr>';
        }

        // table data lines, loop starting from 1
        for (j = 1 ; j < lines; j++) {     
            tab_text = tab_text + "<tr>" + tab.rows[j].innerHTML + "</tr>";
        }

        tab_text = tab_text + "</table>";
        tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");             //remove if u want links in your table
        tab_text = tab_text.replace(/<img[^>]*>/gi,"");                 // remove if u want images in your table
        tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, "");    // reomves input params
        // console.log(tab_text); // aktivate so see the result (press F12 in browser)
        var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><?xml version="1.0" encoding="UTF-8" standalone="yes"?><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(tab_text))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) };
  
      if (!table.nodeType) table = document.getElementById(table)
      var ctx = { worksheet: name || 'Worksheet', table: table.innerHTML };
        window.location.href = uri + base64(format(template, ctx));

    }
</script>
<style type="text/css">
    .tl_seccion_gris{margin-left: 0px;}
</style>
<div class="container">
    <div class="row">
        <h1 class="tl_seccion">Reporte de Gestión de Información</h1>
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
        <h3>¿Por qué medio tuvo información de nuestra marca que motivó su visita?</h3>   
        <div class="col-md-12">
         <button type="button" class="btn btn-warning" aria-label="Close" onclick="tabletoExcel('main_info_1', '¿Por qué medio tuvo información de nuestra marca que motivó su visita?');">Descargar Excel</button>
            <div style="height: 10px;"></div>
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
                            <td style="cursor: pointer" onclick="detailmedios('television','<?php echo $varView['fecha_inicial_actual']; ?>', '<?php echo $varView['fecha_actual']; ?>','<?php echo $varView['fecha_inicial_anterior']; ?>', '<?php echo $varView['fecha_anterior']; ?>',null,'#detalleTelevision','.sign-television','.detalleTelevision' )">Televisión<span class="sign-television" style="float: right; font-weight: bold; font-size: 10pt;">+</span></td>
                            <td><?php echo $varView['television']; ?></td>
                            <td style="cursor: pointer" onclick="detailmedios('television','<?php echo $varView['fecha_inicial_actual']; ?>', '<?php echo $varView['fecha_actual']; ?>','<?php echo $varView['fecha_inicial_anterior']; ?>', '<?php echo $varView['fecha_anterior']; ?>',null,'#detalleTelevision','.sign-television','.detalleTelevision' )">Television<span class="sign-television" style="float: right; font-weight: bold;font-size: 10pt;">+</span></td>
                            <td><?php echo $varView['television_anterior']; ?></td>
                        </tr>

                       
                        <tr id="detallePrensa"><td style="cursor: pointer" onclick="detailmedios('prensa_escrita','<?php echo $varView['fecha_inicial_actual']; ?>', '<?php echo $varView['fecha_actual']; ?>','<?php echo $varView['fecha_inicial_anterior']; ?>', '<?php echo $varView['fecha_anterior']; ?>',null,'#detallePrensa','.sign-prensa_escrita','.detallePrensa' )">Prensa Escrita<span class="sign-prensa_escrita" style="float: right; font-weight: bold;font-size: 10pt;">+</span></td>
                            <td><?php echo $varView['prensa_escrita']; ?></td>
                            <td style="cursor: pointer" onclick="detailmedios('prensa_escrita','<?php echo $varView['fecha_inicial_actual']; ?>', '<?php echo $varView['fecha_actual']; ?>','<?php echo $varView['fecha_inicial_anterior']; ?>', '<?php echo $varView['fecha_anterior']; ?>',null,'#detallePrensa','.sign-prensa_escrita','.detallePrensa' )">Prensa Escrita<span class="sign-prensa_escrita" style="float: right; font-weight: bold;font-size: 10pt;">+</span></td>
                            <td><?php echo $varView['prensa_escrita_anterior']; ?></td>
                        </tr>
                 
                        <tr><td>Radio</td><td><?php echo $varView['radio']; ?></td>
                            <td>Radio</td><td><?php echo $varView['radio_anterior']; ?></td></tr>
                        <tr id="detalleRecomendacion"><td style="cursor: pointer" onclick="detailmedios('recomendacion','<?php echo $varView['fecha_inicial_actual']; ?>', '<?php echo $varView['fecha_actual']; ?>','<?php echo $varView['fecha_inicial_anterior']; ?>', '<?php echo $varView['fecha_anterior']; ?>',null,'#detalleRecomendacion','.sign-recomendacion','.detalleRecomendacion' )">Le recomendaron<span class="sign-recomendacion" style="float: right; font-weight: bold;font-size: 10pt;">+</span></td><td><?php echo $varView['recomendacion']; ?></td>
                            <td style="cursor: pointer" onclick="detailmedios('recomendacion','<?php echo $varView['fecha_inicial_actual']; ?>', '<?php echo $varView['fecha_actual']; ?>','<?php echo $varView['fecha_inicial_anterior']; ?>', '<?php echo $varView['fecha_anterior']; ?>',null,'#detalleRecomendacion','.sign-recomendacion','.detalleRecomendacion' )">Le recomendaron<span class="sign-recomendacion" style="float: right;font-weight: bold;font-size: 10pt;">+</span></td><td><?php echo $varView['recomendacion_anterior']; ?></td></tr>
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
    <div class="row">
            <h3>¿Por qué consideró la marca KIA?</h3>
            
            <div class="col-md-12">
                <button type="button" class="btn btn-warning" aria-label="Close" onclick="tabletoExcel('main_info_2', '¿Por qué consideró la marca KIA?');">Descargar Excel</button>
                <div style="height: 10px;"></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="main_info_2">
                        <thead>
                            <th colspan="2" scope="col">Fecha Inicial: <span id="fecha_inicial" name="fecha_inicial"><?php echo $varView['fecha_inicial_actual'].' al '. $varView['fecha_actual'] ?></span>
                                
                            </th>
                            <th colspan="2"  scope="col">Fecha Final: <span id="fecha_final"><?php echo $varView['fecha_inicial_anterior'].' al '. $varView['fecha_anterior'] ?></span>
                                
                            </th>
                        </thead>
                        <thead>
                            <tr>
                                <th>Consideración</th>
                                <th>Cantidad</th>
                                <th>Consideración</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>Garantía</td><td><?php echo $varView['garantia']; ?></td>
                                <td>Garantía</td><td><?php echo $varView['garantia_anterior']; ?></td>
                            </tr>
                            <tr>
                                <td>Diseño</td>
                                <td><?php echo $varView['diseño']; ?></td>
                                <td>Diseño</td>
                                <td><?php echo $varView['diseño_anterior']; ?></td>
                            </tr>

                           
                            <tr><td>Precio</td>
                                <td><?php echo $varView['precio']; ?></td>
                                <td>Precio</td>
                                <td><?php echo $varView['precio_anterior']; ?></td>
                            </tr>
                              <tr id="detalleRecomendacion2"><td style="cursor: pointer" onclick="detailConsidera('recomendacion','<?php echo $varView['fecha_inicial_actual']; ?>', '<?php echo $varView['fecha_actual']; ?>','<?php echo $varView['fecha_inicial_anterior']; ?>', '<?php echo $varView['fecha_anterior']; ?>',null,'#detalleRecomendacion2','.sign-recomendacion-considera','.detalleRecomendacion2' )">Le recomendaron<span class="sign-recomendacion-considera" style="float: right;font-weight: bold;font-size: 10pt;">+</span></td><td><?php echo $varView['recomendacion_considera']; ?></td>
                                <td style="cursor: pointer" onclick="detailConsidera('recomendacion','<?php echo $varView['fecha_inicial_actual']; ?>', '<?php echo $varView['fecha_actual']; ?>','<?php echo $varView['fecha_inicial_anterior']; ?>', '<?php echo $varView['fecha_anterior']; ?>',null,'#detalleRecomendacion2','.sign-recomendacion-considera','.detalleRecomendacion2' )">Le recomendaron<span class="sign-recomendacion-considera" style="float: right;font-weight: bold;font-size: 10pt;">+</span></td><td><?php echo $varView['recomendacion_considera_anterior']; ?></td></tr>
                            <tr><td>Servicio</td><td><?php echo $varView['servicio']; ?></td>
                                <td>Servicio</td><td><?php echo $varView['servicio_anterior']; ?></td></tr>
                          


                            <tr><td>Recompra</td><td><?php echo $varView['recompra']; ?></td>
                                <td>Recompra</td><td><?php echo $varView['recompra_anterior']; ?></td></tr>
                            
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<?= $this->renderPartial('//reportes/modulos/footer-gestionMedios', array('varView' => $varView)); ?>