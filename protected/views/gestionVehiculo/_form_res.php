<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<?php
$count = 0;
if (isset($id)) {

    $criteria = new CDbCriteria(array(
                'condition' => "id_informacion='{$id}'"
            ));
    $vec = GestionVehiculo::model()->findAll($criteria);
    $count = count($vec);
}
?>
<script type="text/javascript">
    function send()
    {
        $('#gestion-vehiculo-form').validate({
            rules:{
                'GestionVehiculo[modelo]':{
                    required:true
                },
                'GestionVehiculo[version]':{
                    required:true
                },'GestionVehiculo[observaciones]':{
                    required:true
                }
            },
            messages:{
                'GestionVehiculo[modelo]':{
                    required:'Seleccione modelo'
                },'GestionVehiculo[version]':{
                    required:'Seleccione versión'
                },'GestionVehiculo[observaciones]':{
                    required:'Ingresar observaciones'
                }},
            submitHandler: function(form) {
                var dataform=$("#gestion-vehiculo-form").serialize();
                $.ajax({
                    url: '<?php echo Yii::app()->createAbsoluteUrl("gestionVehiculo/createAjax"); ?>',
                    beforeSend: function(xhr){
                        $('#bg_negro').show();  // #bg_negro must be defined somewhere
                    },
                    type: 'POST',
                    data:dataform,
                    success:function(data){
                        $('#bg_negro').hide();
                        //alert('Datos grabados');
                        $('.vehicle-cont').remove();
                        $.ajax({
                            url: '<?php echo Yii::app()->createAbsoluteUrl("site/getVec"); ?>',
                            type: 'post',dataType: 'json',data: {id:<?php echo $id; ?>},
                            success:function(data){
                                $('.highlight').html(data.options);
                            }
                        });
                    }
                });
            }
        });
        
    }
    
    function createVec(id){
        /*$.ajax({
            url: '<?php echo Yii::app()->createAbsoluteUrl("site/createVec"); ?>',
            beforeSend: function(xhr){
                $('#bg_negro').show();  // #bg_negro must be defined somewhere
            },
            type: 'POST',dataType : 'json',data:{id:id},
            success:function(data){
                $('#bg_negro').hide();$('.vehicle-cont').append(data.options);
            }
        });*/
        $('.form-content').show();
    }
    function cancelVec(){
        //$('#gestion-vehiculo-form').remove();
        $('.form-content').hide();$('#gestion-vehiculo-form').get(0).reset();  
    }
    
</script>
<style>
    @media (min-width: 768px){
        .bs-example {
            margin-right: 0;
            margin-left: 0;
            background-color: #E4E4E4;
            border-color: #ddd;
            border-width: 1px;
            border-radius: 4px 4px 0 0;
            -webkit-box-shadow: none;
            box-shadow: none;
        }
    }
    .bs-example {
        position: relative;
        padding: 5px 15px 15px;
        border-color: #e5e5e5 #eee #eee;
        border-style: solid;
        border-width: 1px 0;
        -webkit-box-shadow: inset 0 3px 6px rgba(0,0,0,.05);
        box-shadow: inset 0 3px 6px rgba(0,0,0,.05);
    }
</style>
<div role="tabpanel">

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <?php
        echo '<li role="presentation"><a aria-controls="profile" role="tab"><span>1</span> Prospección / <span>2</span> Cita</a></li>';
        echo '<li role="presentation" class="active"><a href="' . Yii::app()->createUrl('gestionVehiculo/create') . '" aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/recepcion.png" alt="" /></span> Recepción / <span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/consulta.png" alt="" /></span> Consulta</a></li>';
        ?>
        <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/presentacion.png" alt="" /></span> Presentación</a></li>
        <li role="presentation"><a aria-controls="profile" role="tab"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/demostracion.png" alt="" /></span> Demostración</a></li>
        <li role="presentation"><a aria-controls="settings" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/negociacion.png" alt="" /></span> Negociación</a></li>
        <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cierre.png" alt="" /></span> Cierre</a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane" id="home">
        </div>
        <div role="tabpanel" class="tab-pane active" id="profile">
            <div class="row"><div class="col-md-3"><a class="btn btn-success" style="margin: 20px 44px;" onclick="createVec(<?php echo $id; ?>)">Agregar otro vehículo</a></div></div>
            <div class="row"><p class="note" style="margin-left: 28px;">Campos con <span class="required">*</span> son requeridos.</p></div>
            <div class="row highlight">
                <div class="form vehicle-cont">
                    <?php if ($count == 0): ?>
                        <?php
                        $form = $this->beginWidget('CActiveForm', array(
                            'id' => 'gestion-vehiculo-form',
                            'enableAjaxValidation' => false,
                            'htmlOptions' => array(
                                'onsubmit' => "return false;", /* Disable normal form submit */
                                'onkeypress' => " if(event.keyCode == 13){ send(); } " /* Do ajax call when user presses enter key */
                            ),
                                ));
                        ?>
                        <div class="col-md-6">
                            <?php // echo $form->errorSummary($model); ?>

                            <div class="row">
                                <?php //echo $form->hiddenField($model, 'id_informacion', array('size' => 15, 'maxlength' => 15));  ?>
                                <input type="hidden" name="GestionVehiculo[id_informacion]" id="GestionVehiculo_id_informacion" value="<?php echo $id; ?>">
                                <?php //echo $form->error($model,'id_informacion');  ?>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <?php echo $form->labelEx($model, 'modelo'); ?>
                                    <?php
                                    echo $form->dropDownList($model, 'modelo', array(
                                        "" => "--Escoja un Modelo--",
                                        "84" => "Picanto R",
                                        "85" => "Rio R",
                                        "24" => "Cerato Forte",
                                        "90" => "Cerato R",
                                        "89" => "Óptima Híbrido",
                                        "88" => "Quoris",
                                        "20" => "Carens R",
                                        "11" => "Grand Carnival",
                                        "21" => "Sportage Active",
                                        "83" => "Sportage R",
                                        "10" => "Sorento",
                                        "25" => "K 2700 Cabina Simple",
                                        "87" => "K 2700 Cabina Doble",
                                        "86" => "K 3000"), array('class' => 'form-control'));
                                    ?>
                                    <?php echo $form->error($model, 'modelo'); ?>
                                </div>
                                <div class="col-md-6">
                                    <?php echo $form->labelEx($model, 'version'); ?>
                                    <?php echo $form->dropDownList($model, 'version', array('' => 'Escoja una versión'), array('class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'version'); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <?php echo $form->labelEx($model, 'precio'); ?>
                                    <?php echo $form->textField($model, 'precio', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'precio'); ?>
                                </div>
                                <div class="col-md-6">
                                    <?php echo $form->labelEx($model, 'dispositivo'); ?>
                                    <?php echo $form->textField($model, 'dispositivo', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'dispositivo'); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <?php echo $form->labelEx($model, 'accesorios'); ?>
                                    <?php echo $form->textField($model, 'accesorios', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'accesorios'); ?>
                                </div>
                                <div class="col-md-6">
                                    <?php echo $form->labelEx($model, 'seguro'); ?>
                                    <?php echo $form->textField($model, 'seguro', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'seguro'); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <?php echo $form->labelEx($model, 'total'); ?>
                                    <?php echo $form->textField($model, 'total', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'total'); ?>
                                </div>
                                <div class="col-md-6">
                                    <?php echo $form->labelEx($model, 'plazo'); ?>
                                    <?php echo $form->textField($model, 'plazo', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'plazo'); ?>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-md-6">
                                    <?php echo $form->labelEx($model, 'forma_pago'); ?>
                                    <?php echo $form->textField($model, 'forma_pago', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'forma_pago'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <?php echo $form->labelEx($model, 'cuota_inicial'); ?>
                                    <?php echo $form->textField($model, 'cuota_inicial', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'cuota_inicial'); ?>
                                </div>
                                <div class="col-md-6">
                                    <?php echo $form->labelEx($model, 'saldo_financiar'); ?>
                                    <?php echo $form->textField($model, 'saldo_financiar', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'saldo_financiar'); ?>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <?php echo $form->labelEx($model, 'tarjeta_credito'); ?>
                                    <?php echo $form->textField($model, 'tarjeta_credito', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'tarjeta_credito'); ?>
                                </div>
                                <div class="col-md-6">
                                    <?php echo $form->labelEx($model, 'otro'); ?>
                                    <?php echo $form->textField($model, 'otro', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'otro'); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <?php echo $form->labelEx($model, 'plazos'); ?>
                                    <?php echo $form->textField($model, 'plazos', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'plazos'); ?>
                                </div>
                                <div class="col-md-6">
                                    <?php echo $form->labelEx($model, 'cuota_mensual'); ?>
                                    <?php echo $form->textField($model, 'cuota_mensual', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'cuota_mensual'); ?>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <?php echo $form->labelEx($model, 'avaluo'); ?>
                                    <?php echo $form->textField($model, 'avaluo', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'avaluo'); ?>
                                </div>
                                <div class="col-md-6">
                                    <?php echo $form->labelEx($model, 'observaciones'); ?>
                                    <?php echo $form->textArea($model, 'observaciones', array('rows' => 6, 'cols' => 50)); ?>
                                    <?php echo $form->error($model, 'observaciones'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row buttons">
                            <div class="col-md-6">
                                <?php echo CHtml::submitButton($model->isNewRecord ? 'Crear' : 'Grabar', array('class' => 'btn btn-danger', 'style' => 'margin-left: 14px;', 'onclick' => 'send();')); ?>
                            </div>
                        </div>
                        <?php $this->endWidget(); ?>
                    <?php else: ?>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="tables tablesorter" id="keywords">
                                    <thead>
                                        <tr>
                                            <th><span>Modelo</span></th>
                                            <th><span>Precio</span></th>
                                            <th><span>Dispositivo</span></th>
                                            <th><span>Accesorios</span></th>
                                            <th><span>Seguro</span></th>
                                            <th><span>Edición</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($vec as $c):
                                            ?>
                                            <tr>
                                                <td><?php echo $this->getModel($c['modelo']); ?> </td>
                                                <td><?php echo $c['precio'] ?> </td>
                                                <td><?php echo $c['dispositivo']; ?> </td>
                                                <td><?php echo $c['accesorios']; ?> </td>
                                                <td><?php echo $c['seguro']; ?> </td>
                                                <td><a href="<?php echo Yii::app()->createUrl('gestionVehiculo/update', array('id' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-danger">Editar</a>
                                                    <a href="<?php echo Yii::app()->createUrl('gestionVehiculo/view', array('id' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-danger">Ver</a>
                                                    <a href="<?php echo Yii::app()->createUrl('gestionDiaria/create', array('id_informacion' => $c['id_informacion'], 'id' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-success">Gestión Diaria</a></td>

                                            </tr>
                                            <?php
                                        endforeach;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                        </div>
                        <div class="form-content" style="display: none;">
                            <?php
                            $form = $this->beginWidget('CActiveForm', array(
                                'id' => 'gestion-vehiculo-form',
                                'enableAjaxValidation' => false,
                                'htmlOptions' => array(
                                    'onsubmit' => "return false;", /* Disable normal form submit */
                                    'onkeypress' => " if(event.keyCode == 13){ send(); } " /* Do ajax call when user presses enter key */
                                ),
                                    ));
                            ?>
                            <div class="col-md-6">
                                <?php // echo $form->errorSummary($model); ?>

                                <div class="row">
                                    <?php //echo $form->hiddenField($model, 'id_informacion', array('size' => 15, 'maxlength' => 15));  ?>
                                    <input type="hidden" name="GestionVehiculo[id_informacion]" id="GestionVehiculo_id_informacion" value="<?php echo $id; ?>">
                                    <?php //echo $form->error($model,'id_informacion');  ?>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php echo $form->labelEx($model, 'modelo'); ?>
                                        <?php
                                        echo $form->dropDownList($model, 'modelo', array(
                                            "" => "--Escoja un Modelo--",
                                            "84" => "Picanto R",
                                            "85" => "Rio R",
                                            "24" => "Cerato Forte",
                                            "90" => "Cerato R",
                                            "89" => "Óptima Híbrido",
                                            "88" => "Quoris",
                                            "20" => "Carens R",
                                            "11" => "Grand Carnival",
                                            "21" => "Sportage Active",
                                            "83" => "Sportage R",
                                            "10" => "Sorento",
                                            "25" => "K 2700 Cabina Simple",
                                            "87" => "K 2700 Cabina Doble",
                                            "86" => "K 3000"), array('class' => 'form-control'));
                                        ?>
                                        <?php echo $form->error($model, 'modelo'); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?php echo $form->labelEx($model, 'version'); ?>
                                        <?php echo $form->dropDownList($model, 'version', array('' => 'Escoja una versión'), array('class' => 'form-control')); ?>
                                        <?php echo $form->error($model, 'version'); ?>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <?php echo $form->labelEx($model, 'precio'); ?>
                                        <?php echo $form->textField($model, 'precio', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                        <?php echo $form->error($model, 'precio'); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?php echo $form->labelEx($model, 'dispositivo'); ?>
                                        <?php echo $form->textField($model, 'dispositivo', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                        <?php echo $form->error($model, 'dispositivo'); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php echo $form->labelEx($model, 'accesorios'); ?>
                                        <?php echo $form->textField($model, 'accesorios', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                        <?php echo $form->error($model, 'accesorios'); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?php echo $form->labelEx($model, 'seguro'); ?>
                                        <?php echo $form->textField($model, 'seguro', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                        <?php echo $form->error($model, 'seguro'); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php echo $form->labelEx($model, 'total'); ?>
                                        <?php echo $form->textField($model, 'total', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                        <?php echo $form->error($model, 'total'); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?php echo $form->labelEx($model, 'plazo'); ?>
                                        <?php echo $form->textField($model, 'plazo', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                        <?php echo $form->error($model, 'plazo'); ?>
                                    </div>

                                </div>
                                <div class="row">

                                    <div class="col-md-6">
                                        <?php echo $form->labelEx($model, 'forma_pago'); ?>
                                        <?php echo $form->textField($model, 'forma_pago', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                        <?php echo $form->error($model, 'forma_pago'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php echo $form->labelEx($model, 'cuota_inicial'); ?>
                                        <?php echo $form->textField($model, 'cuota_inicial', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                        <?php echo $form->error($model, 'cuota_inicial'); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?php echo $form->labelEx($model, 'saldo_financiar'); ?>
                                        <?php echo $form->textField($model, 'saldo_financiar', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                        <?php echo $form->error($model, 'saldo_financiar'); ?>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php echo $form->labelEx($model, 'tarjeta_credito'); ?>
                                        <?php echo $form->textField($model, 'tarjeta_credito', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                        <?php echo $form->error($model, 'tarjeta_credito'); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?php echo $form->labelEx($model, 'otro'); ?>
                                        <?php echo $form->textField($model, 'otro', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                        <?php echo $form->error($model, 'otro'); ?>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <?php echo $form->labelEx($model, 'plazos'); ?>
                                        <?php echo $form->textField($model, 'plazos', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                        <?php echo $form->error($model, 'plazos'); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?php echo $form->labelEx($model, 'cuota_mensual'); ?>
                                        <?php echo $form->textField($model, 'cuota_mensual', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                        <?php echo $form->error($model, 'cuota_mensual'); ?>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <?php echo $form->labelEx($model, 'avaluo'); ?>
                                        <?php echo $form->textField($model, 'avaluo', array('size' => 45, 'maxlength' => 45, 'class' => 'form-control')); ?>
                                        <?php echo $form->error($model, 'avaluo'); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?php echo $form->labelEx($model, 'observaciones'); ?>
                                        <?php echo $form->textArea($model, 'observaciones', array('rows' => 6, 'cols' => 50)); ?>
                                        <?php echo $form->error($model, 'observaciones'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row buttons">
                                <div class="col-md-6">
                                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Crear' : 'Grabar', array('class' => 'btn btn-danger', 'style' => 'margin-left: 14px;', 'onclick' => 'send();')); ?>
                                    <input class="btn btn-primary" style="margin-left: 14px;" onclick="cancelVec();" type="submit" name="yt0" value="Cancelar">
                                </div>
                            </div>
                            <?php $this->endWidget(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>
<div role="tabpanel" class="tab-pane" id="settings"></div>
<div role="tabpanel" class="tab-pane" id="messages"></div>
</div>

</div>