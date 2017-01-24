<?php
$emailCliente = $this->getEmailCliente($id_informacion);
$id_asesor = Yii::app()->user->getId();
$dealer_id = $this->getDealerId($id_asesor);
$nombre_asesor = $this->getResponsableNombres($id_asesor);
$cargo_id = (int) Yii::app()->user->getState('cargo_id');
$grupo_id = (int) Yii::app()->user->getState('grupo_id');
$nombre_cliente = $this->getNombresInfo($id_informacion) . ' ' . $this->getApellidosInfo($id_informacion);
$modelo = $this->getModeloInfo($id_vehiculo);
$ciudadCliente = $this->getCiudadConcesionario($id_informacion);
$ciudad = $this->getCiudad($id_asesor);
$foto_entrega = $this->getFotoEntregaDetail($id_informacion);
date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
$fecha_m = date("d m Y");
$fecha_m = $this->getFormatFecha($fecha_m);
?>
<style>
    /*.container{width: 800px;}*/
    .row{margin-bottom: 1px !important;margin-left: 20px !important;margin-right: 20px !important;}
    h4{font-weight: bold;margin-top: 5px !important;}
    hr{margin-top: 3px !important;margin-bottom: 3px !important;}
    .target{font-size: 12px !important;}
    .target strong{color:#911714 !important;}
    .target p{margin: 0 !important;}
    .container{margin-left: -3% !important;}
    .title .col-xs-3{width: 24% !important;}
    .img-logo{position: relative; right: 150px;}
</style>
<div class="container cont-print">
    <div style="width:600px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size: 11px;">
        <div align="">
            <img src="images/header_mail.jpg">
            <br>
            <p><?php echo $ciudadCliente; ?> <?php echo $fecha_m; ?></p><br />
            <p>Señor(a)</p>
            <p><?php echo $nombre_cliente; ?></p>
            <p><?php echo $ciudadCliente; ?></p>
            <br />
            <p>
                KIA MOTORS ECUADOR le da la bienvenida, agradecemos la confianza al haber escogido uno de nuestros vehículos KIA, con la mejor tecnología Coreana. 
            </p><br />
            <p>
                Le recordamos que su vehículo cuenta con una
                <?php if ($modelo == 86 || $modelo == 93) { ?>
                    garantía de 7 años o 120.000 Km, ';
                <?php } else { ?>
                    garantía de 7 años o 150.000 Km, ';
                <?php } ?>
                para mantener dicha garantía, usted debe realizar los mantenimientos en nuestro <a href="https://www.kia.com.ec/concesionarios.html"> concesionario KIA a nivel nacional</a>. 
            </p><br />
            <p>
                Nuestra prioridad es servirle de la mejor manera, por lo que usted tiene a su disposición la nueva línea gratuita de Servicio al Cliente 1800 KIA KIA (1800 542 542), donde Usted podrá obtener información de Vehículos Nuevos, Seminuevos, Talleres de Servicio Autorizado Kia, Costos de Mantenimiento Preventivos, Repuestos y Accesorios, Políticas de Garantías de su Vehículo, etc. Nuestro personal de la línea 1800 KIAKIA podrá ayudarle también a realizar su próxima cita de mantenimiento para que Ud. pueda continuar disfrutando de su vehículo Kia en todo momento, basta con llamar y uno de nuestros asesores podrá brindarle el mejor servicio para su próxima cita.
            </p><br />
            <p>
                Para complementar nuestro servicio hemos incorporado para usted, totalmente gratis por un año, un producto denominado “Asistencia KIA” el cual le asiste ante cualquier desperfecto mecánico, las 24 horas del día, los 365 días del año. La cobertura del producto “Asistencia KIA” comprende de:
            </p><br />
            <ul>
                <li><strong>Remolque o traslado de su vehículo en caso de avería o accidente hasta el concesionario Kia más cercano</strong></ol>
                <li><strong>Auxilio Mecánico: </strong>asistencia en caso llanta baja, falta de combustible, carga de batería.</ol>
                <li><strong>Traslado en ambulancia en caso accidente de tránsito</strong></ol>
                <li><strong>Cobertura en caso de viaje: </strong>en caso de que su vehículo quede inmovilizado, sufra hurto simple o calificado se cubrirá la estancia y desplazamiento de los ocupantes.</ol>
                <li><strong>Transporte ó custodia del vehículo reparado o recuperado en caso de encontrarse en una ciudad que no es de su residencia. </strong></ol>
                <li><strong>Servicio de conductor profesional: </strong>en caso de imposibilidad del conductor para conducir el vehículo por muerte, accidente o cualquier enfermedad grave.</ol>
            </ul>
            <p>
                Si desea obtener mayor información le invitamos a visitar y registrarse en nuestra página Web en www.kia.com.ec o comunicándose a 1800KIAKIA.
            </p>
            <p>Para KIA MOTORS ECUADOR es importante poner a su disposición productos de calidad para poder brindarle el mejor servicio.</p>

            <a href="https://www.kia.com.ec<?php echo Yii::app()->request->baseUrl; ?>/images/uploads/<?php echo $foto_entrega; ?>">Foto de Entrega</a>

            <p><strong>Atentamente</strong></p>
            <p><strong>KIA MOTORS ECUADOR</strong></p>
        </div>
    </div>
</div>
