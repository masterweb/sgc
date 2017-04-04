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
    function detail(tipo_medio, fecha_inicial, fecha_final, provincia){
        $.ajax({
            url: '<?php echo Yii::app()->createAbsoluteUrl("gestionMedios/traficoMedio"); ?>',
            beforeSend: function (xhr) {
                $('#bg_black').show();  // #info must be defined somehwere
            },
            type: 'POST',
            dataType: 'json',
            data: {tipo_medio:tipo_medio, fecha_inicial:fecha_inicial,fecha_final:fecha_final,provincia:provincia},
            success: function (data) {
                
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
        <div class="col-md-12">
            <h2 class="tl_seccion_gris">Reporte de medios desde <?php echo $varView['fecha_inicial_actual'].' al '. $varView['fecha_actual'] ?></h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="main_info_1">
                    <thead>
                        <tr>
                            <th>Medio</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td onclick="detail('television','<?php echo $varView['fecha_inicial_actual']; ?>', '<?php echo $varView['fecha_actual']; ?>',100 )">Televisión</td><td><?php echo $varView['television']; ?></td></tr>
                        <tr><td onclick="detail('prensa_escrita','<?php echo $varView['fecha_inicial_actual']; ?>', '<?php echo $varView['fecha_actual']; ?>',100 )">Prensa Escrita</td><td><?php echo $varView['prensa_escrita']; ?></td></tr>
                        <tr><td>Radio</td><td><?php echo $varView['radio']; ?></td></tr>
                        <tr><td onclick="detail('recomendacion','<?php echo $varView['fecha_inicial_actual']; ?>', '<?php echo $varView['fecha_actual']; ?>',100 )">Le recomendaron</td><td><?php echo $varView['recomendacion']; ?></td></tr>
                        <tr><td>Página Web</td><td><?php echo $varView['pagina_web']; ?></td></tr>
                        <tr><td>Internet</td><td><?php echo $varView['internet']; ?></td></tr>
                        <tr><td>Redes Sociales</td><td><?php echo $varView['redes_sociales']; ?></td></tr>
                        <tr><td>Conoce el Concesionario</td><td><?php echo $varView['conoce_concesionario']; ?></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>