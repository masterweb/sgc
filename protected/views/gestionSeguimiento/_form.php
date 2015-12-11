<?php
/* @var $this GestionSeguimientoController */
/* @var $model GestionSeguimiento */
/* @var $form CActiveForm */
?>
<?php $this->widget('application.components.Notificaciones'); ?>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/jquery.fancybox.css?v=2.1.4" type="text/css"/>               
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css"/>        
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css"/>
<!-- Add fancyBox -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/jquery.fancybox.pack.js?v=2.1.4"></script>
<!-- Optionally add helpers - button, thumbnail and/or media -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.5"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
<script type="text/javascript">
    $(function () {
        //$("#hidden_link").fancybox().trigger('click');
        $('#closemodal').click(function () {
            var url="https://www.kia.com.ec/intranet/usuario/index.php/gestionInformacion/seguimiento";
            $(location).attr('href',url);
        });
    });
</script>
<script type="text/javascript">
    $(window).load(function () {
        $('#myModal').modal('show');
    });
</script>
<style type="text/css">
    .fancybox-inner{overflow: hidden !important;}
</style>
<div role="tabpanel">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">            
        <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/prospeccion.png" alt="" /></span> Prospección / <span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cita.png" alt="" /></span> Cita</a></li>
        <?php
        $criteria = new CDbCriteria(array(
            'condition' => "id={$id_informacion}"
        ));
        $info = GestionInformacion::model()->count($criteria);
        ?>
        <?php if ($info > 0): ?>
            <li role="presentation"><a href="<?php echo Yii::app()->createUrl('gestionInformacion/update/', array('id' => $id_informacion, 'tipo' => 'gestion')); ?>"  aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/recepcion.png" alt="" /></span> Recepción</a></li>
        <?php else: ?>
            <li role="presentation"><a aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/recepcion.png" alt="" /></span> Recepción</a></li>
        <?php endif; ?>
        <li role="presentation"><a href="<?php echo Yii::app()->createUrl('gestionVehiculo/create/' . $id_informacion); ?>" aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/consulta.png" alt="" /></span> Consulta</a></li>
        <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/presentacion/' . $id_informacion); ?>" aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/presentacion.png" alt="" /></span> Presentación</a></li>
        <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/demostracion/' . $id_informacion); ?>" aria-controls="profile" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/demostracion.png" alt="" /></span> Demostración</a></li>
        <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/negociacion/' . $id_informacion); ?>" aria-controls="settings" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/negociacion.png" alt="" /></span> Negociación</a></li>
        <li role="presentation"><a href="<?php echo Yii::app()->createUrl('site/cierre/' . $id_informacion); ?>" aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cierre.png" alt="" /></span> Cierre</a></li>
        <li role="presentation"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/entrega.png" alt="" /></span> Entrega</a></li>
        <li role="presentation" class="active"><a aria-controls="messages" role="tab"><span><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/seguimiento_on.png" alt="" /></span>Seguimiento</a></li>
    </ul>
    <!-- Tab panels -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="profile">

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
                            <tr class="odd"><th>Nombres</th><td><?php echo $cl[0]['nombres']; ?></td></tr> 
                            <tr class="odd"><th>Apellidos</th><td><?php echo $cl[0]['apellidos']; ?></td></tr> 
                            <tr class="odd"><th>Cédula</th><td><?php echo $cl[0]['cedula']; ?></td></tr> 
                            <tr class="odd"><th>Email</th><td><?php echo $cl[0]['email']; ?></td></tr> 
                            <tr class="odd"><th>Celular</th><td><?php echo $cl[0]['celular']; ?></td></tr> 
                            <tr class="odd"><th>Teléfono Domicilio</th><td><?php echo $cl[0]['telefono_casa']; ?></td></tr>
                            <tr class="odd"><th>Dirección</th><td><?php echo $cl[0]['direccion']; ?></td></tr>
                            <tr class="odd"><th>Modelo</th><td><?php echo $this->getModeloTestDrive($_GET['id_vehiculo']); ?></td></tr>
                            <tr class="odd"><th>Versión</th><td><?php echo $this->getVersionTestDrive($_GET['id_vehiculo']); ?></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <h1 class="tl_seccion">Seguimiento</h1>
            </div>
            <div class="row">
                <div class="form">

                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'gestion-seguimiento-form',
                        'enableAjaxValidation' => false,
                    ));
                    ?>

                    <p class="note">Campos con <span class="required">*</span> son requeridos.</p>

                    <?php echo $form->errorSummary($model); ?>

                    <div class="row">
                        <div class="col-md-4">
                            <?php //echo $form->labelEx($model, 'satisfecho'); ?>
                            <label for="">1.	¿Está completamente satisfecho con su nuevo vehículo?</label>
                            <?php echo $form->dropDownList($model, 'satisfecho', array('Si' => 'Si', 'No' => 'No'), array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'satisfecho'); ?>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <?php //echo $form->labelEx($model, 'asistencia'); ?>
                            <label for="">2.	¿Necesita asistencia de alguna clase?</label>
                            <?php echo $form->dropDownList($model, 'asistencia', array('Si' => 'Si', 'No' => 'No'), array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'asistencia'); ?>
                        </div>
                    </div>
                    <div class="row buttons">
                        <div class="col-md-2">
                            <?php echo CHtml::submitButton($model->isNewRecord ? 'Finalizar' : 'Save', array('class' => 'btn btn-danger')); ?>
                        </div>
                    </div>

                    <?php $this->endWidget(); ?>

                </div><!-- form -->
            </div>
            <div class="modal fade" id="myModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">SGC</h4>
                        </div>
                        <div class="modal-body">
                            <h4>El proceso ha finalizado</h4>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" id="closemodal">Cerrar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <div id="hidden_link" style="display: none;">
                <div class="row">
                    <div class="col-md-5"><img class="img-logo" src="<?php echo Yii::app()->request->baseUrl; ?>/images/logo_pdf.jpg" alt=""></div>
                    <div class="col-md-7"><h4>El Proceso ha finalizado</h4></div>
                </div>
            </div>
        </div>
    </div>
</div>
