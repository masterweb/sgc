<?php
$cargo_id = (int) Yii::app()->user->getState('cargo_id');
$grupo_id = (int) Yii::app()->user->getState('grupo_id');
$id_asesor = Yii::app()->user->getId();
$nombre_responsable = $this->getResponsableNombres($id_asesor);
//$nombre_responsable = mb_convert_case($nombre_responsable, MB_CASE_UPPER, "UTF-8");
$dealer_id = $this->getDealerId($id_asesor);
$nombre_jefe_sucursal = $this->getNombresJefeConcesion(70, $grupo_id, $dealer_id); //email administrador
$nombre_cliente = $this->getNombreClienteRGD($id_informacion);
$responsable_id_vendedor = $this->getResponsableId($id_informacion);
$nombre_vendedor = $this->getResponsableNombres($responsable_id_vendedor);
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#Factura_tipo').change(function () {
            var value = $(this).attr('value');
            switch (value) {
                case 'cedula':
                    $('.cont-cedula').show();
                    $('.cont-ruc').hide();
                    $('.cont-chasis').hide();
                    break;
                case 'ruc':
                    $('.cont-cedula').hide();
                    $('.cont-ruc').show();
                    $('.cont-chasis').hide();
                    break;
                case 'chasis':
                    $('.cont-cedula').hide();
                    $('.cont-ruc').hide();
                    $('.cont-chasis').show();
                    break;
            }

        });
    });
</script>
<style type="text/css">
    .alert-message
    {
        margin: 20px 0;
        padding: 20px;
        border-left: 3px solid #eee;
    }
    .alert-message h4
    {
        margin-top: 0;
        margin-bottom: 5px;
    }
    .alert-message p:last-child
    {
        margin-bottom: 0;
    }
    .alert-message code
    {
        background-color: #fff;
        border-radius: 3px;
    }
    .alert-message-success
    {
        background-color: #F4FDF0;
        border-color: #3C763D;
    }
    .alert-message-success h4
    {
        color: #3C763D;
    }
    .alert-message-danger
    {
        background-color: #fdf7f7;
        border-color: #d9534f;
    }
    .alert-message-danger h4
    {
        color: #d9534f;
    }
    .alert-message-warning
    {
        background-color: #fcf8f2;
        border-color: #f0ad4e;
    }
    .alert-message-warning h4
    {
        color: #f0ad4e;
    }
    .alert-message-info
    {
        background-color: #f4f8fa;
        border-color: #5bc0de;
    }
    .alert-message-info h4
    {
        color: #5bc0de;
    }
    .alert-message-default
    {
        background-color: #EEE;
        border-color: #B4B4B4;
    }
    .alert-message-default h4
    {
        color: #000;
    }
    .alert-message-notice
    {
        background-color: #FCFCDD;
        border-color: #BDBD89;
    }
    .alert-message-notice h4
    {
        color: #444;
    }
</style>

<div class="container">
    <div role="tabpanel">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/prospeccion.png" alt="" /></span> Prospección / <span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cita.png" alt="" /></span> Cita</a></li>
            <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/recepcion.png" alt="" /></span> Recepción</a></li>
            <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/consulta.png" alt="" /></span> Consulta</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/presentacion/' . $id_informacion); ?>" saria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/presentacion.png" alt="" /></span> Presentación</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/demostracion/' . $id_informacion); ?>" saria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/demostracion.png" alt="" /></span> Demostración</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/negociacion/' . $id_informacion); ?>" aria-controls="settings" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/negociacion.png" alt="" /></span> Negociación</a></li>
            <li role="presentation" class="active"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cierre_on.png" alt="" /></span> Cierre</a></li>
            <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/entrega.png" alt="" /></span> Entrega</a></li>
            <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/seguimiento.png" alt="" /></span>Seguimiento</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="profile">
                <div class="row">
                    <div class="form">
                        <?php
                        $form = $this->beginWidget('CActiveForm', array(
                            'id' => 'form-id',
                            'method' => 'post',
                            'action' => Yii::app()->createUrl('site/facturacierre'), //<- your form action here
                        ));
                        ?>
                        <div class="row highlight">
                            <div class="col-md-12">
                                <?php if($cargo_id == 71): ?>
                                <div class="alert-message alert-message-danger">
                                    <h4><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Aviso</h4>
                                    <p><?php echo $nombre_responsable; ?>, para ingresar la factura del Cliente: <?php echo $nombre_cliente; ?>, 
                                        debe acercarse al Jefe de Sucursal <?php echo $nombre_jefe_sucursal; ?> para registrarla.</p>
                                    <p>Una vez registrada la factura podrá continuar al paso de Entrega.</p>
                                </div>
                                <?php else: ?>
                                <div class="alert-message alert-message-danger">
                                    <h4><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Aviso</h4>
                                    <p><?php echo $nombre_responsable; ?>, debe ingresar la factura del Cliente: <?php echo $nombre_cliente; ?>, 
                                        registrado en el SGC por el asesor/a: <?php echo $nombre_vendedor; ?>.</p>
                                    <p>Una vez registrada la factura, <?php echo $nombre_vendedor; ?> podrá continuar al paso de Entrega.</p>
                                </div>
                                <?php endif; ?>
                            </div>

                            <?php
                            $fa = GestionFactura::model()->count(array('condition' => "id_informacion = {$id_informacion} AND id_vehiculo = {$id_vehiculo}"));
                            if ($fa > 0) {
                                echo '<div class="row">
                                        <div class="col-md-4">
                                            <div class="btn-group btn-group-xs" role="group" aria-label="Extra-small button group"> 
                                            <button type="button" class="btn btn-default">Factura Registrada</button> 
                                            <button type="button" class="btn btn-success">Si</button>'; 
                                if($cargo_id == 70){echo '<a href="' . Yii::app()->createUrl('gestionCierre/update', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo)) . '" class="btn btn-danger">Editar</a>'; }
                                        echo '</div>
                                        
                                        <div class="row">
                                            <a href="' . Yii::app()->createUrl('site/entrega', array('id_informacion' => $_GET['id_informacion'])) . '" class="btn btn-danger" id="send" name="send" value="Continuar">Continuar</a>
                                            <input type="hidden" id="send" name="Factura[id_informacion]" value="' . $_GET['id_informacion'] . '">
                                            <input type="hidden" id="send" name="Factura[id_vehiculo]" value="' . $_GET['id_vehiculo'] . '">
                                            <input type="hidden" id="Factura_identificacion" name="Factura[identificacion]" value="' . $this->getIdentificacionTipo($id_informacion) . '"/>
                                        </div>                                        
                                        </div>
                                    </div>';
                            } else {
                                echo '<div class="row">
                                      <div class="col-md-6">
                                        <div class="btn-group btn-group-xs" role="group" aria-label="Extra-small button group"> 
                                              <button type="button" class="btn btn-default">Factura Registrada</button> 
                                              <button type="button" class="btn btn-danger">No</button> 
                                              </div>
                                        </div>
                                      </div>  ';
                                if ($cargo_id == 70) {
                                    echo '<div class="row"><div class="col-md-2"><a href="' . Yii::app()->createUrl('gestionCierre/create', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo)) . '" class="btn btn-danger" id="send" name="send">Ingresar Factura</a>'
                                    . '</div>';
                                }
                            }
//echo 'id info'.$id_informacion;
//echo 'id vehiculo'.$id_vehiculo;
                            ?>
                        </div>
                        <?php $this->endWidget(); ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-8  col-xs-12 links-tabs">
                        <div class="col-md-2 col-xs-4"><p>También puedes ir a:</p></div>
                        <div class="col-md-2 col-xs-4"><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></div>
                        <div class="col-md-2 col-xs-4"><a href="<?php echo Yii::app()->createUrl('gestionInformacion/seguimiento'); ?>" class="creacion-btn">RGD</a></div>                         <div class="col-md-3 col-xs-4"><a href="<?php echo Yii::app()->createUrl('uusuarios/contactos'); ?>" class="directorio-btn">Directorio de Contactos</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
