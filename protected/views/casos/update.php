<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<!-- Add fancyBox -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/jquery.fancybox.pack.js?v=2.1.4"></script>
<!-- Optionally add helpers - button, thumbnail and/or media -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.5"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>
<script>
    $(function () {
        $(".fancybox").fancybox();
        $('#casos-form').validate({
            submitHandler: function(form) {
                var subtema = $('#Casos_subtema').val();
                var tipo_form = $('#Casos_tipo_form').val();
                //alert(subtema);
                var error = 0;
                if(subtema == 9){
                    var modelo = $('#Casos_modelo').val();
                    var version = $('#Casos_version').val();
                    if(modelo !='' && version != ''){
                        //form.submit();
                    }else{
                        alert('Seleccione un modelo y versión de auto');
                        error++;
                    }
                }
                if(tipo_form == 'caso'){
                    var tipo_venta = $('#Casos_tipo_venta').val();
                    if(tipo_venta == ''){
                        alert('Seleccione un tipo de venta');
                        error++;
                    }
                }
                if(error == 0){
                    form.submit();
                }
            }
        });
    });
    function obs(id){
        //alert(id);
        var id = id;
        //$('#myModal').modal();
        $.ajax({
            url:'https://www.kia.com.ec/intranet/callcenter/index.php/historial/getobservaciones',
            dataType: "json",
            data:{
                id:id
            },
            type: 'post',
            success:function(data){
                //alert(data.options)
                $('.modal-body').html(data.options);
                $('#myModal').modal();
            }
        });
    }
</script>
<?php
/* @var $this CasosController */
/* @var $model Casos */

$this->breadcrumbs = array(
    'Casoses' => array('index'),
    $model->id => array('view', 'id' => $model->id),
    'Update',
);

$this->menu = array(
    array('label' => 'List Casos', 'url' => array('index')),
    array('label' => 'Create Casos', 'url' => array('create')),
    array('label' => 'View Casos', 'url' => array('view', 'id' => $model->id)),
    array('label' => 'Manage Casos', 'url' => array('admin')),
);
?>

<!--<h1>Update Casos <?php echo $model->id; ?></h1>-->

<?php $rol = Yii::app()->user->getState('roles'); ?>
<style>
    .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: none;
    }
</style>
<div class="container">
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                </div>
                <div class="modal-body">                  
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <?php if (Yii::app()->user->hasFlash('success')): ?>
            <div class="info">
                <?php echo Yii::app()->user->getFlash('success'); ?>
            </div>
        <?php else: ?>
            <div class="col-md-8">
                <h1 class="tl_seccion">Actualización de Casos</h1>
                <div class="form">
                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'casos-form',
                        'enableAjaxValidation' => true,
                        'htmlOptions' => array('class' => 'form-horizontal readonly-form')
                            ));
                    ?>
                    <?php
                    $criteria = new CDbCriteria(array(
                                'order' => 'name'
                            ));
                    $menu = CHtml::listData(Menu::model()->findAll($criteria), "id", "name");
                    $criteria = new CDbCriteria(array(
                                'condition' => "id_menu='{$model->tema}'",
                                'order' => 'name'
                            ));
                    $subtemas = CHtml::listData(Submenu::model()->findAll($criteria), "id", "name");
                    ?>
                    <?php //echo $form->errorSummary($model); ?>
                    <div class="highlight">
                        <div class="row">
                            <label for="" class="col-md-2 control-label">Tema:</label>
                            <div class="col-sm-4">
                                <?php echo $form->dropDownList($model, 'tema', $menu, array('empty' => 'Selecciona un tema', 'class' => 'form-control', 'readonly' => true)); ?>
                                <input type="hidden" name="tema" id="Casos_tema" value="<?php echo $model->tema; ?>">
                            </div>
                            <div class="col-md-3 col-md-offset-3">
                                <button type="button" class="btn btn-primary btn-xs" id="edit-btn">Editar</button>
                                <button type="button" class="btn btn-warning btn-xs" id="save-btn" style="display: none;">Cancelar</button>
                            </div>
                        </div>
                        <div class="row">
                            <label for="" class="col-md-2 control-label">Subtema:</label>
                            <div class="col-sm-4">
                                <?php echo $form->dropDownList($model, 'subtema', $subtemas, array('empty' => 'Selecciona un subtema', 'class' => 'form-control', 'readonly' => true)); ?>
                            </div>
                        </div>
                        <div class="row">
                            <label for="" class="col-md-2 control-label">Nombres:</label>
                            <div class="col-sm-4">
                                <?php echo $form->textField($model, 'nombres', array('size' => 60, 'maxlength' => 150, 'class' => 'form-control', 'readonly' => true)); ?>
                            </div>


                        </div>
                        <div class="row">
                            <label for="" class="col-md-2 control-label">Apellidos:</label>
                            <div class="col-sm-4">
                                <?php echo $form->textField($model, 'apellidos', array('size' => 60, 'maxlength' => 150, 'class' => 'form-control', 'readonly' => true)); ?>
                            </div>
                            <label for="" class="col-md-2 control-label">Cédula:</label>
                            <div class="col-sm-4">
                                <?php echo $form->textField($model, 'cedula', array('size' => 60, 'maxlength' => 10, 'class' => 'form-control', 'readonly' => true)); ?>
                            </div>
                        </div>
                        <div class="row">
                            <label for="" class="col-md-2 control-label">Provincia Domicilio:</label>
                            <div class="col-sm-4">
                                <?php
                                $criteria = new CDbCriteria(array(
                                            //'condition' => "id_provincia={$model->provincia_domicilio}",
                                            'order' => 'nombre'
                                        ));
                                $provinciasDom = CHtml::listData(TblProvincias::model()->findAll($criteria), "id_provincia", "nombre");
                                ?>
                                <?php echo $form->dropDownList($model, 'provincia_domicilio', $provinciasDom, array('class' => 'form-control', 'empty' => 'Selecciona una provincia', 'readonly' => true)); ?>
                                <?php echo $form->error($model, 'provincia_domicilio'); ?>
                            </div>
                            <label for="" class="col-md-2 control-label">Ciudad Domicilio:</label>
                            <div class="col-sm-4">
                                <?php
                                $criteria = new CDbCriteria(array(
                                            'condition' => "id_ciudad='{$model->ciudad_domicilio}'",
                                            'order' => 'nombre'
                                        ));
                                $ciudadesDom = CHtml::listData(TblCiudades::model()->findAll($criteria), "id_ciudad", "nombre");
                                ?>
                                <?php echo $form->dropDownList($model, 'ciudad_domicilio', $ciudadesDom, array('class' => 'form-control', 'readonly' => true)); ?>
                                <?php echo $form->error($model, 'ciudad_domicilio'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <label for="" class="col-md-2 control-label">Provincia:</label>
                            <div class="col-sm-4">
                                <?php
                                $criteria = new CDbCriteria(array(
                                            'condition' => "estado='s'",
                                            'order' => 'nombre'
                                        ));
                                $provincias = CHtml::listData(Provincias::model()->findAll($criteria), "id_provincia", "nombre");
                                ?>
                                <?php echo $form->dropDownList($model, 'provincia', $provincias, array('class' => 'form-control', 'empty' => 'Selecciona una provincia', 'readonly' => true)); ?>
                                <?php echo $form->error($model, 'provincia'); ?>
                            </div>
                            <label for="" class="col-md-2 control-label">Ciudad:</label>
                            <div class="col-sm-4">
                                <?php
                                $criteria = new CDbCriteria(array(
                                            'condition' => "id_provincia='{$model->provincia}'",
                                            'order' => 'name'
                                        ));
                                $ciudades = CHtml::listData(Dealercities::model()->findAll($criteria), "id", "name");
                                ?>
                                <?php echo $form->dropDownList($model, 'ciudad', $ciudades, array('class' => 'form-control', 'readonly' => true)); ?>
                                <?php echo $form->error($model, 'ciudad'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <?php if ($model->concesionario != ''): ?>
                                <label for="" class="col-md-2 control-label">Concesionario:</label>
                                <div class="col-sm-4">
                                    <?php
                                    $criteria = new CDbCriteria(array(
                                                'condition' => "id={$model->concesionario}",
                                                'order' => 'name'
                                            ));
                                    $concesionarios = CHtml::listData(Dealers::model()->findAll($criteria), "cityid", "name");
                                    ?>
                                    <?php echo $form->dropDownList($model, 'concesionario', $concesionarios, array('class' => 'form-control', 'readonly' => true)); ?>
                                    <?php echo $form->error($model, 'concesionario'); ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($model->tipo_form === 'caso'): ?>
                                <label for="" class="col-md-2 control-label">Tipo Venta:</label>
                                <div class="col-sm-4">

                                    <?php
                                    echo $form->dropDownList($model, 'tipo_venta', array('venta' => 'Venta',
                                        'postventa' => 'Post Venta'), array('class' => 'form-control', 'readonly' => true, 'venta' => 'selected'));
                                    ?>
                                    <?php echo $form->error($model, 'tipo_venta'); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="row">
                            <label for="" class="col-md-2 control-label">Teléfono:</label>
                            <div class="col-sm-4">
                                <?php echo $form->textField($model, 'telefono', array('size' => 50, 'maxlength' => 9, 'class' => 'form-control', 'readonly' => true)); ?>
                                <?php echo $form->error($model, 'telefono'); ?>
                            </div>
                            <label for="" class="col-md-2 control-label">Celular:</label>
                            <div class="col-sm-4">
                                <?php echo $form->textField($model, 'celular', array('size' => 50, 'maxlength' => 10, 'class' => 'form-control', 'readonly' => true)); ?>
                                <?php echo $form->error($model, 'celular'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <label for="" class="col-md-2 control-label">Email:</label>
                            <div class="col-sm-4">
                                <?php echo $form->textField($model, 'email', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control', 'readonly' => true)); ?>
                                <?php echo $form->error($model, 'email'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <label for="" class="col-md-2 control-label">Responsable:</label>
                            <div class="col-sm-4">
                                <?php //echo $form->textField($model, 'responsable', array('size' => 50, 'maxlength' => 9, 'class' => 'form-control', 'readonly' => true)); ?>
                                <input size="50" maxlength="50" class="form-control" readonly="readonly" name="Casos[responsable]" id="Casos_responsable" type="text" value="<?php echo $this->getResponsable($model->responsable); ?>">
                                <?php echo $form->error($model, 'responsable'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <label for="" class="col-md-2 control-label">Comentario:</label>
                            <div class="col-sm-8">
                                <?php echo $form->textArea($model, 'comentario', array('rows' => 6, 'cols' => 50, 'class' => 'form-control', 'readonly' => true)); ?>
                                <?php echo $form->error($model, 'comentario'); ?>
                            </div>
                            <input type="hidden" name="Casos[concesionario]" id="Casos_concesionario" value="<?php echo $model->concesionario; ?>">
    <!--                        <input type="hidden" name="Casos[cedula]" id="Casos_cedula" value="<?php echo $model->cedula; ?>">-->
                            <input type="hidden" name="Casos[id]" id="Casos_id" value="<?php echo $model->id; ?>">
                            <input type="hidden" name="Casos[tipo_form]" id="Casos_tipo_form" value="<?php echo $model->tipo_form; ?>">
                            <input type="hidden" name="tema" id="tema" value="<?php echo $model->tema; ?>">
                            <input type="hidden" name="subtema" id="subtema" value="<?php echo $model->subtema; ?>">
                            <input type="hidden" name="nombre" id="nombre" value="<?php echo $model->nombres; ?>">
                            <input type="hidden" name="apellido" id="apellido" value="<?php echo $model->apellidos; ?>">
                            <input type="hidden" name="cedula" id="cedula" value="<?php echo $model->cedula; ?>">
                            <input type="hidden" name="provinciadomicilio" id="provinciadomicilio" value="<?php echo $model->provincia_domicilio; ?>">
                            <input type="hidden" name="ciudaddomicilio" id="ciudaddomicilio" value="<?php echo $model->ciudad_domicilio; ?>">
                            <input type="hidden" name="tipoventa" id="tipoventa" value="<?php echo $model->tipo_venta; ?>">
                            <input type="hidden" name="telefono" id="telefono" value="<?php echo $model->telefono; ?>">
                            <input type="hidden" name="celular" id="celular" value="<?php echo $model->celular; ?>">
                            <input type="hidden" name="email" id="email" value="<?php echo $model->email; ?>">
                            <input type="hidden" name="responsable" id="responsable" value="<?php echo $model->responsable; ?>">
                            <input type="hidden" name="comentario" id="comentario" value="<?php echo $model->comentario; ?>">
                        </div>
                    </div>
                    <?php if ($model->tipo_form != 'informativo') { ?>
                        <?php if ($rol === 'adminvpv'): ?>
                            <input type="hidden" name="Casos[estado]" value="Proceso" id="Casos_estado">
                        <?php else: ?>
                            <?php if ($model->estado === 'Abierto' || $model->estado === 'Proceso'): ?>
                                <span id="Casos_estado">
                                    <input id="Casos_estado_1" value="Proceso" type="radio" checked="checked" name="Casos[estado]"> <label for="Casos_estado_1">En Proceso</label> 
                                    <input id="Casos_estado_2" value="Cerrado" type="radio" name="Casos[estado]"> <label for="Casos_estado_2">Cerrado</label>
                                </span>
                            <?php endif; ?>
                            <?php if ($model->estado === 'Cerrado'): ?>
                                <span id="Casos_estado">
                                    <input id="Casos_estado_1" value="Proceso" type="radio" name="Casos[estado]"> <label for="Casos_estado_1">En Proceso</label> 
                                    <input id="Casos_estado_2" value="Cerrado" type="radio" checked="checked" name="Casos[estado]"> <label for="Casos_estado_2">Cerrado</label>
                                    <input type="hidden" name="closed_case" id="closed_case" value="1">
                                </span>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php }else { ?>
                        <input type="hidden" name="Casos[estado]" value="Proceso" id="Casos_estado"> 
                    <?php } ?>


                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'observaciones', array('class' => 'col-md-6 control-label')); ?>
                        <div class="col-sm-12">
                            <?php //echo $form->textArea($model, 'observaciones', array('rows' => 6, 'cols' => 50, 'class' => 'form-control')); ?>
                            <textarea rows="6" cols="50" class="form-control" name="Casos[observaciones]" id="Casos_observaciones"></textarea>
                            <?php echo $form->error($model, 'observaciones'); ?>
                        </div>
                    </div>
                    <div class="row buttons">
                        <?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', array('class' => 'btn btn-danger')); ?>
                    </div>

                    <?php $this->endWidget(); ?>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6"><h3>Historial</h3></div>
                        <div class="col-md-6">
                            <?php if ($rol === 'super'): ?>
                                <?php
                                $form = $this->beginWidget('CActiveForm', array(
                                    'id' => 'casos-excel',
                                    'method' => 'post',
                                    'action' => Yii::app()->createUrl('historial/exportExcel'),
                                    'enableAjaxValidation' => false,
                                    'htmlOptions' => array('class' => 'form-horizontal form-search')
                                        ));
                                ?>
                                <input type="hidden" name="Historial[id_caso]" id="Casos_tema" value="<?php echo $model->id; ?>">
                                <input class="btn btn-primary" type="submit" name="yt0" value="Guardar en Excel" style="float: right;">
                                <?php $this->endWidget(); ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php
                    $criteria = new CDbCriteria(array(
                                'condition' => "id_caso={$model->id}",
                                'order' => 'fecha desc'
                            ));
                    $historial = Historial::model()->findAll($criteria);
                    ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tema</th>
                                    <th>Subtema</th>
                                    <th>Responsable</th>
                                    <th>Estado</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($historial as $c):
                                    $fecha = '';
                                    $nuevafecha = '';
                                    $fecha = date($c['fecha']);
                                    $nuevafecha = strtotime('-5 hour', strtotime($fecha));
                                    $nuevafecha = date('Y-m-d H:i:s', $nuevafecha);
                                    ?>
                                    <tr>
                                        <td><?php echo $c['fecha']; ?> </td>
                                        <td><?php echo $this->getTema($c['tema']); ?> </td>
                                        <td><?php echo $this->getSubtema($c['subtema']); ?> </td>
                                        <td><?php echo $this->getResponsable($c['id_responsable']); ?> </td>
                                        <td><?php echo $c['estado']; ?> </td>
                                        <td><a class="" id="" onclick="obs(<?php echo $c['id']; ?>)" style="cursor: pointer;"><?php echo substr($c['observaciones'], 0, 11) . "..."; ?></a></td>
                                    </tr>
                                    <?php
                                endforeach;
                                ?>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="col-md-3 col-md-offset-1 cont_der">
            <p>También puedes ir a:</p>
            <ul>
                <li><a href="<?php echo Yii::app()->createUrl('casos/create'); ?>" class="creacion-btn">Crear Casos</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('casos/reportes'); ?>" class="reportes-btn">Reportes</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></li>
                <li><a href="javascript:history.back(1)" class="back-btn-go">Volver</a></li>
                <!--<li><a href="<?php echo Yii::app()->createUrl('casos/seguimiento'); ?>" class="back-btn">Volver</a></li>-->
            </ul>
        </div>

    </div>
</div>