
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
        $('#form-id').validate({
            rules: {
                'Factura[observaciones]': {required: true}
            },
            messages: {},
            submitHandler: function (form) {
                $('#bg_negro').show(); // #bg_negro must be defined somewhere
                form.submit();
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
            <li role="presentation" class="active"><a href="<?php echo Yii::app()->createUrl('site/factura/' . $id_vehiculo); ?>" aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cierre_on.png" alt="" /></span> Cierre</a></li>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/entrega/', array('id_vehiculo' => $id_vehiculo, 'id_informacion' => $id_informacion)); ?>" aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/entrega.png" alt="" /></span> Entrega</a></li>
            <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/seguimiento.png" alt="" /></span>Seguimiento</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="profile">

                <div class="highlight">
                    <?php if (Yii::app()->user->hasFlash('error')): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo Yii::app()->user->getFlash('error'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <h1 class="tl_seccion">Datos de Cliente</h1>
                    </div>
                    <?php
                    $criteria = new CDbCriteria(array('condition' => "id = {$id_informacion}"));
                    $cl = GestionInformacion::model()->findAll($criteria);
                    ?>
                    <div class="row">
                        <div class="col-md-8">
                            <table class="table table-striped">
                                <tbody>
                                    <tr class="odd"><th>ID</th><td><?php echo $cl[0]['id']; ?></td></tr>
                                    <tr class="odd"><th>Nombres</th><td><?php echo $cl[0]['nombres']; ?></td></tr> 
                                    <tr class="odd"><th>Apellidos</th><td><?php echo $cl[0]['apellidos']; ?></td></tr> 
                                    <tr class="odd">
                                        <?php if ($cl[0]['cedula'] != ''): ?>
                                            <th>Cédula</th><td><?php echo $cl[0]['cedula']; ?></td>
                                        <?php endif; ?>
                                        <?php if ($cl[0]['ruc'] != ''): ?>
                                            <th>Ruc</th><td><?php echo $cl[0]['ruc']; ?></td>
                                        <?php endif; ?>
                                        <?php if ($cl[0]['pasaporte'] != ''): ?>
                                            <th>Pasaporte</th><td><?php echo $cl[0]['pasaporte']; ?></td>
                                        <?php endif; ?>
                                    </tr> 
                                    <tr class="odd"><th>Email</th><td><?php echo $cl[0]['email']; ?></td></tr> 
                                    <tr class="odd"><th>Celular</th><td><?php echo $cl[0]['celular']; ?></td></tr> 
                                    <tr class="odd"><th>Teléfono Domicilio</th><td><?php echo $cl[0]['telefono_casa']; ?></td></tr>
                                    <tr class="odd"><th>Dirección</th><td><?php echo $cl[0]['direccion']; ?></td></tr> 
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <br />
                <div class="highlight">
                    <div class="row">
                        <h1 class="tl_seccion">Datos de Cliente Createc</h1>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            $res = $result;
                            //echo $res;
                            switch ($result) {
                                case 'nofind':
                                    ?>                                            
                                    <div class="alert alert-warning" role="alert">
                                        <strong>FACTURA NO REGISTRADA</strong>
                                    </div>
                                    <br />
                                    <a href="<?php echo Yii::app()->createUrl('site/facturaNoRegistered', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo)); ?>" class="btn btn-danger">Continuar</a>
                                    <?php
                                    break;
                                case 'equal':
                                    ?>
                                    <div class="alert alert-success" role="alert">
                                        <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/check.fw.png" />
                                        <strong>Venta Realizada</strong>
                                    </div>
                                    <table class="table table-striped">
                                        <tbody><?php echo $data; ?></tbody>
                                    </table>
                                    <?php
                                    $form = $this->beginWidget('CActiveForm', array(
                                        'id' => 'form-id-ct',
                                        'method' => 'post',
                                        'action' => Yii::app()->createUrl('site/facturaCorrecta', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo)), //<- your form action here
                                    ));
                                    ?>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <input type="submit" name="send" value="Continuar" class="btn btn-primary"/>
                                            <input type="hidden" name="id_informacion" value="<?php echo $id_informacion; ?>" />
                                            <input type="hidden" name="id_vehiculo" value="<?php echo $id_vehiculo; ?>" />
                                            <input type="hidden" name="datos_vehiculo" value="<?php echo implode(',', $data_save); ?>"/>
                                        </div>
                                    </div>
                                    <?php $this->endWidget(); ?>
                                    <?php
                                    break;
                                case 'noequal':
                                    ?>

                                    <div class="alert alert-warning" role="alert">
                                        <strong>No coincide identificación</strong>
                                    </div>
                                    <table class="table table-striped">
                                        <tbody><?php echo $data; ?></tbody>
                                    </table>
                                    <div class="form">
                                        <?php
                                        $form = $this->beginWidget('CActiveForm', array(
                                            'id' => 'form-id',
                                            'method' => 'post',
                                            'action' => Yii::app()->createUrl('site/facturaIncorrecta', array('id_informacion' => $id_informacion, 'id_vehiculo' => $id_vehiculo)), //<- your form action here
                                        ));
                                        ?>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="observaciones">Ingrese observación</label>
                                                <select name="Factura[observaciones]" id="Factura_observaciones" class="form-control">
                                                    <option value="">--Seleccione--</option>
                                                    <option value="Factura a Nombre de Familiar">Factura a Nombre de Familiar</option>
                                                    <option value="Factura a Nombre de 3ra Persona">Factura a Nombre de 3ra Persona</option>
                                                    <option value="Factura a Nombre de Empresa">Factura a Nombre de Empresa</option>
                                                </select> 
                                            </div> 
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <input type="submit" name="send" value="Aceptar" class="btn btn-primary"/>
                                                <input type="hidden" name="id_informacion" value="<?php echo $id_informacion; ?>" />
                                                <input type="hidden" name="id_vehiculo" value="<?php echo $id_vehiculo; ?>" />
                                                <input type="hidden" name="datos_vehiculo" value="<?php echo implode(',', $data_save); ?>"/>
                                            </div>
                                        </div>
                                        <?php $this->endWidget(); ?>
                                    </div>
                                    <br />
                                    <?php
                                    break;
                                default:
                                    break;
                            }
                            ?>

                        </div>
                    </div>
                </div>

                <?php if ($search): ?>

                <?php else: ?>


                    <!--                <div class="row">
                                        <div class="col-md-3">
                                            <a href="<?php echo Yii::app()->createUrl('site/factura/' . $id_informacion); ?>" class="btn btn-danger">Ingresar Chasis</a>
                                        </div>
                                    </div>-->
                <?php endif; ?>
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
