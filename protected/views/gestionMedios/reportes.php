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
    function detailmedios(tipo_medio, fecha_inicial, fecha_final, provincia){
        $.ajax({
            url: '<?php echo Yii::app()->createAbsoluteUrl("gestionMedios/traficoDetalle"); ?>',
            beforeSend: function (xhr) {
                $('#bg_black').show();  // #info must be defined somehwere
            },
            type: 'POST',
            dataType: 'json',
            data: {tipo_medio:tipo_medio, fecha_inicial:fecha_inicial,fecha_final:fecha_final,provincia:provincia},
            success: function (data) {
                alert(data.data);
            }
        });
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
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="main_info_1">
                    <thead>
                        <th colspan="2" scope="col">Fecha Inicial: <?php echo $varView['fecha_inicial_actual'].' al '. $varView['fecha_actual'] ?></th><th colspan="2"  scope="col">Fecha Final: <?php echo $varView['fecha_anterior'].' al '. $varView['fecha_inicial_anterior'] ?></th>
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
                            <td>Cine</td><td><?php echo $varView['cine_anterior']; ?></td></tr>
                        <tr>
                            <td onclick="detailmedios('television','<?php echo $varView['fecha_inicial_actual']; ?>', '<?php echo $varView['fecha_actual']; ?>',100 )">Televisión</td>
                            <td><?php echo $varView['television']; ?></td>
                            <td>Television</td>
                            <td><?php echo $varView['television_anterior']; ?></td>
                        </tr>
                        <tr class="television-td"></tr>
                        <tr><td onclick="detailmedios('prensa_escrita','<?php echo $varView['fecha_inicial_actual']; ?>', '<?php echo $varView['fecha_actual']; ?>',100 )">Prensa Escrita</td>
                            <td><?php echo $varView['prensa_escrita']; ?></td>
                            <td>Prensa Escrita</td>
                            <td><?php echo $varView['prensa_escrita_anterior']; ?></td>
                        </tr>
                        <tr class="prensa_escrita-td"></tr>
                        <tr><td>Radio</td><td><?php echo $varView['radio']; ?></td>
                            <td>Radio</td><td><?php echo $varView['radio_anterior']; ?></td></tr>
                        <tr><td>Le recomendaron</td><td><?php echo $varView['recomendacion']; ?></td>
                            <td>Le recomendaron</td><td><?php echo $varView['recomendacion_anterior']; ?></td></tr>
                        <tr><td>Página Web</td><td><?php echo $varView['pagina_web']; ?></td>
                            <td>Radio</td><td><?php echo $varView['pagina_web_anterior']; ?></td></tr>
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