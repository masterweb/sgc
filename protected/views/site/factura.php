<script type="text/javascript">
    $(document).ready(function () {
        $('#Factura_tipo').change(function(){
            var value = $(this).attr('value');
            switch(value){
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
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/entrega/', array('id_informacion' => $id_informacion)); ?>" aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/entrega.png" alt="" /></span> Entrega</a></li>
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
                        <h4>Para cerrar la venta ingresa la identificación del cliente para conectar con el sistema de facturación</h4>
                        <?php
//echo 'id info'.$id_informacion;
//echo 'id vehiculo'.$id_vehiculo;
                        ?>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="factura">Ingreso</label>
                                <select name="Factura[tipo]" id="Factura_tipo" class="form-control">
                                    <option value="">--Seleccione--</option>
                                    <option value="cedula">Cédula</option>
                                    <option value="ruc">RUC</option>
                                    <option value="chasis">Chasis</option>
                                </select>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="cont-cedula" style="display: none;">
                                    <label for="cedula">Número de Cédula</label>
                                    <input type="text" name="Factura[cedula]" id="Factura_cedula" class="form-control" maxlength="10"/>
                                </div>
                                <div class="cont-ruc" style="display: none;">
                                    <label for="ruc">Número de RUC</label>
                                    <input type="text" name="Factura[ruc]" id="Factura_ruc" class="form-control" maxlength="13"/>
                                </div>
                                <div class="cont-chasis" style="display: none;">
                                    <label for="chasis">Número de Chasis</label>
                                    <input type="text" name="Factura[chasis]" id="Factura_chasis" class="form-control" maxlength="17"/>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="submit" class="btn btn-danger" id="send" name="send" value="Enviar">
                                <input type="hidden" id="send" name="Factura[id_informacion]" value="<?php echo $_GET['id_informacion']; ?>">
                                <input type="hidden" id="send" name="Factura[id_vehiculo]" value="<?php echo $_GET['id_vehiculo']; ?>">
                                <input type="hidden" id="Factura_identificacion" name="Factura[identificacion]" value="<?php echo $this->getIdentificacionTipo($id_informacion); ?>"/>
                            </div>
                        </div>
                        
                        


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
