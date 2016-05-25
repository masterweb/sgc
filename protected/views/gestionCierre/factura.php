<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$fa = GestionCierre::model()->find(array('condition' => "id_informacion = {$id_informacion} AND id_vehiculo = {$id_vehiculo}"));
?>
<style>
    /*.container{width: 800px;}*/
    .row{margin-bottom: 1px !important;margin-left: 20px !important;margin-right: 20px !important;}
    h4{font-weight: bold;margin-top: 5px !important;}
    hr{margin-top: 3px !important;margin-bottom: 3px !important;}
    .target{font-size: 13px !important;}
    .target strong{color:#911714 !important;}
    .target p{margin: 0 !important;}
    .container{margin-left: -3% !important;}
    .title .col-xs-3{width: 24% !important;}
    .img-logo{position: relative; right: 150px;}
</style>
<div class="container cont-print">
    <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 14px;">
        <img src="images/header_mail.jpg">
            <br>
        <h4>FACTURA DE CIERRE</h4>
        <?php //echo 'id informacion: '.$fa['numero_chasis']; ?>
        <div align="">
            <div class="row">
                <div class="col-xs-4">Número de Chasis</div>
                <div class="col-md-6"><?php echo $fa['numero_chasis'] ?></div>
            </div>
            <div class="row">
                <div class="col-xs-4">Número de Modelo</div>
                <div class="col-md-6"><?php echo $fa['numero_modelo'] ?></div>
            </div>
            <div class="row">
                <div class="col-xs-4">Nombre del Propietario</div>
                <div class="col-md-6"><?php echo $fa['nombre_propietario'] ?></div>
            </div>
            <div class="row">
                <div class="col-xs-4">Color de Vehículo</div>
                <div class="col-md-6"><?php echo $fa['color_vehiculo'] ?></div>
            </div>
            <div class="row">
                <div class="col-xs-4">Factura</div>
                <div class="col-md-6"><?php echo $fa['factura'] ?></div>
            </div>
            <div class="row">
                <div class="col-xs-4">Concesionario</div>
                <div class="col-md-6"><?php echo $fa['concesionario'] ?></div>
            </div>
            <div class="row">
                <div class="col-xs-4">Fecha de Venta</div>
                <div class="col-md-6"><?php echo $fa['fecha_venta'] ?></div>
            </div>
            <div class="row">
                <div class="col-xs-4">Año</div>
                <div class="col-md-6"><?php echo $fa['year'] ?></div>
            </div>
            <div class="row">
                <div class="col-xs-4">Color de Orígen</div>
                <div class="col-md-6"><?php echo $fa['color_origen'] ?></div>
            </div>
            <div class="row">
                <div class="col-xs-4">Identificación</div>
                <div class="col-md-6"><?php echo $fa['identificacion'] ?></div>
            </div>
            <div class="row">
                <div class="col-xs-4">Precio de Venta</div>
                <div class="col-md-6"><?php echo $fa['precio_venta'] ?></div>
            </div>
            <div class="row">
                <div class="col-xs-4">Calle Principal</div>
                <div class="col-md-6"><?php echo $fa['calle_principal'] ?></div>
            </div>
            <div class="row">
                <div class="col-xs-4">Número de Calle</div>
                <div class="col-md-6"><?php echo $fa['numero_calle'] ?></div>
            </div>
            <div class="row">
                <div class="col-xs-4">Teléfono Propietario</div>
                <div class="col-md-6"><?php echo $fa['telefono_propietario'] ?></div>
            </div>
            <div class="row">
                <div class="col-xs-4">Grupo Concesionario</div>
                <div class="col-md-6"><?php echo $fa['grupo_concesionario'] ?></div>
            </div>
            <div class="row">
                <div class="col-xs-4">Forma de Pago</div>
                <div class="col-md-6"><?php echo $fa['forma_pago'] ?></div>
            </div>
            <div class="row">
                <div class="col-xs-4">Observaciones</div>
                <div class="col-md-6"><?php echo $fa['observacion'] ?></div>
            </div>
            <div class="row">
                <div class="col-xs-4">Número de Chasis</div>
                <div class="col-md-6"><?php echo $fa['numero_chasis'] ?></div>
            </div>
            <div class="row">
                <div class="col-xs-4">Fecha</div>
                <div class="col-md-6"><?php echo $fa['fecha'] ?></div>
            </div>
        </div>
    </div>
</div>

