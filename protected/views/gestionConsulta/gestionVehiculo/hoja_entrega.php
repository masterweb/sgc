<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$id_asesor = Yii::app()->user->getId();
$emailAsesor = $this->getAsesorEmail($id_asesor);
$concesionarioid = $this->getConcesionarioDealerId($id_asesor);
$telefono = $this->getAsesorTelefono($id_asesor);
$celular = $this->getAsesorCelular($id_asesor);
$codigo_asesor = $this->getAsesorCodigo($id_asesor);
$codigoconcesionario = $this->getCodigoConcesionario($concesionarioid);
//echo $this->getResponsable($id_asesor);
$mpdf = Yii::app()->ePdf->mpdf();
?>
<style>
    /*.container{width: 800px;}*/
    .row{margin-bottom: 5px;}
    h4{font-weight: bold;margin-top: 5px !important;}
    .checkbox{margin-top: -5px;margin-bottom: -5px !important;font-size: 12px;}
    .col-xs-1 { min-width: 5.333333333333332%;}
    .title .col-xs-3{width: 22% !important;}
    .target{font-size: 12px !important;}
    .target strong{color:#911714 !important;}
    .target p{margin: 0 !important;}
    #bar{padding: 15px 13px 5px 3px;border: 1px solid;}
    #bar .row{border-bottom: 1px dotted;margin-left: 0px;}

    /*.row{margin-bottom: 1px !important;margin-left: 20px !important;margin-right: 20px !important;}
    .checkbox{margin-top: -5px;margin-bottom: -5px !important;font-size: 12px;}
    .col-xs-1 { min-width: 8.333333333333332%;}
    .target{font-size: 12px !important;}
    .target strong{color:#911714 !important;}
    .target p{margin: 0 !important;}
    .container{margin-left: -3% !important;}
    .title .col-xs-3{width: 24% !important;}
    .img-logo{position: relative; right: 150px;}*/
</style>
<div class="container cont-print">
    <div class="row title">
        <div class="col-xs-3"><img class="img-logo" src="<?php echo Yii::app()->request->baseUrl ?>/images/logo_pdf2.png" alt=""></div>
        <div class="col-xs-8" style="border-left:1px solid #888890;">
            <h4><?php echo strtoupper($this->getNameConcesionario($id_asesor)); ?></h4>
            <div class="target">

                <div class="col-xs-12"><p><?php echo $this->getResponsable($id_asesor); ?></p></div>
                <div class="col-xs-12"><strong>Dirección: <?php echo $this->getConcesionarioDireccion($id_asesor); ?></strong></div>
                <div class="col-xs-5"><p><strong>T </strong> (593) <?php echo $telefono; ?></p></div>
                <div class="col-xs-5"><p><strong>M </strong> (593 9) <?php echo $celular; ?></p></div>
                <div class="col-xs-5"><p><strong>E </strong><?php echo $emailAsesor; ?> </p></div>
                <div class="col-xs-5"><p><strong>W </strong> www.kia.com.ec</p></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12"><h4><strong>HOJA DE ENTREGA DE VEHÍCULOS</strong></h4></div>
    </div>
    <div class="row">
        <div class="col-xs-12"><h5><strong>Hoja No <?php echo $codigoconcesionario; ?>-HE-0000-<?php echo $id_hoja; ?>-<?php echo $id_informacion; ?></strong></h5></div>
    </div>
    <?php foreach ($data as $key => $value) : ?>
        <div class="row">
            <div class="col-xs-10">
                <table class="table table-striped">
                    <tbody>
                        <tr class="odd"><th>Modelo</th><td><?php echo $this->getModeloTestDrive($value['id_vehiculo']); ?></td><th>Cliente</th><td><?php echo $value['nombres']; ?> <?php echo $value['apellidos']; ?></td></tr>
                        <tr class="odd"><th>Versión</th><td><?php echo $this->getVersionTestDrive($value['id_vehiculo']); ?></td><th>Fecha Factura</th><td></td></tr>
                        <tr class="odd"><th>Motor</th><td></td><th>Factura</th><td></td></tr>
                        <tr class="odd"><th>No. Chasis</th><td></td><th>Color</th><td></td></tr>
                        <tr class="odd"><th>Fecha de Entrega</th><td><?php echo $value['agendamiento1'] ?></td><th>Color</th><td></td></tr> 
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6"><h4><strong>ACCESORIOS DEL VEHÍCULO</strong></h4></div>
        </div>
        <?php
        $stringEquip = substr($value['accesorios'], 0, -1);
        $params = explode('@', $stringEquip);
        ?>
        <div id="bar">
            <div class="row">
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            ACEITE MOTOR
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Aceite Motor', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>    
                </div>
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            AGUA LIMPIA PARABRISAS
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Agua Limpia Parabrisas', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            ANTENAS DE RADIO
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Antenas de radio', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            AROS/TAPACUBOS/PERNOS
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Aros/Tapacubos/Pernos', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            BATERÍA
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Batería', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            CERTIFICADO DE PRODUCCIÓN CAE
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Certificado de Producción CAE', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            A/C CALEFACCIÓN
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('A/C Calefacción', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            CENICERO
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Cenicero', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            CINTURONES DE SEGURIDAD
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Cinturones de Seguridad', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            EMBLEMAS
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Emblemas', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            ESPEJO INTERIOR IZQUIERDO
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Espejo Interior Izquierdo', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            ESPEJO EXTERIOR DERECHO
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Espejo Exterior Derecho', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            ESPEJO INTERIOR
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Espejo Exterior Derecho', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            FACTURA
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Espejo Exterior Derecho', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            FALDONES
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Faldones', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            GATA
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Gata', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            HERRAMIENTAS
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Herramientas', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            LÍQUIDO DE FRENOS
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Líquido de Frenos', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            LLANTAS DE EMERGENCIA
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Llantas de Energencia', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            2 LLAVES (ENCENDIDO Y PUERTAS)
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('2 Llaves Encendido y Puertas', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            LLAVE DE RUEDAS
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Llave de Ruedas', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            LUCES (FUNCIONAMIENTO)
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Luces Funcionamiento', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            MOLDURAS Y NIQUELADOS
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Molduras y Niquelados', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            MANUAL DE GARANTÍA
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Manual de Garantía', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            MANUAL DE PROPIETARIO
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Manual de Propietario', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            NEBLINEROS
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Neblineros', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            PARASOLES
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Parasoles', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            PARLANTES
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Parlantes', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            PINTURA
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Pintura', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            PITO
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Pito', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            PLUMAS LIMPIAPARABRISAS
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Plumas Limpiadoras', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            RADIO CON PANTALLA
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Radio con Pantalla', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            REFRIGERANTE DE RADIADOR
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Refrigerante de Radiador', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            SEGUROS PUERTAS (FUNCIONAMIENTO)
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Seguros Puertas Funcionamiento', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            SEGURO LLANTA DE EMERGENCIA
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Segurl Llanta de Emergencia', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            TABLERO DE CONTROL
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Tablero de Control', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            TAPA DE GASOLINA 
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Tablero de Gasolina', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            TAPA VÁLVULAS
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Tapa Válvulas', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            TAPICERÍA
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Tapicería', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
                <div class="col-xs-4">
                    <div class="checkbox">
                        <label>
                            VIDRIOS (FUNCIONAMIENTO)
                        </label>
                    </div>
                </div>
                <div class="col-xs-1">
                    <?php if (in_array('Vidrios Funcionamiento', $params)) { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min.png" alt="">
                    <?php } else { ?>
                        <img src="<?php Yii::app()->request->baseUrl; ?>'/images/check-min-empty.png" alt="">
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12"><h4><strong>OBSERVACIONES</strong></h4></div>
            <div class="col-md-6">
                <p><?php echo $value['observaciones']; ?></p>
            </div>
        </div>
    </div>
<?php endforeach; ?>
