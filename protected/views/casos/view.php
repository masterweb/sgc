<script>
    $(function () {
        $(".fancybox").fancybox();
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
$rol = Yii::app()->user->getState('roles');
$this->breadcrumbs = array(
    'Casoses' => array('index'),
    $model->id,
);

$this->menu = array(
    array('label' => 'List Casos', 'url' => array('index')),
    array('label' => 'Create Casos', 'url' => array('create')),
    array('label' => 'Update Casos', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete Casos', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Manage Casos', 'url' => array('admin')),
);
?>
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
        <h1 class="tl_seccion">Vista de Casos</h1>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="highlight">
                <table class="detail-view" id="yw0">
                    <tbody>
                        <tr class="odd"><th>ID</th><td><?php echo $model->id; ?></td></tr>
                        <tr class="even"><th>Tema</th><td><?php echo $this->getTema($model->tema); ?></td></tr>
                        <tr class="odd"><th>Subtema</th><td><?php echo $this->getSubtema($model->subtema); ?></td></tr>
                        <tr class="even"><th>Nombres</th><td><?php echo $model->nombres; ?></td></tr>
                        <tr class="odd"><th>Apellidos</th><td><?php echo $model->apellidos; ?></td></tr>
                        <tr class="even"><th>Cedula</th><td><?php echo $model->cedula; ?></td></tr>
                        <tr class="odd"><th>Provincia</th><td><?php echo $this->getProvincia($model->provincia); ?></td></tr>
                        <tr class="even"><th>Ciudad</th><td><?php echo $this->getCiudad($model->ciudad); ?></td></tr>
                        <tr class="odd"><th>Direccion</th><td><?php echo $model->direccion; ?></td></tr>
                        <tr class="even"><th>Sector</th><td><?php echo $model->sector; ?></td></tr>
                        <tr class="odd"><th>Telefono</th><td><?php echo $model->telefono; ?></td></tr>
                        <tr class="even"><th>Celular</th><td><?php echo $model->celular; ?></td></tr>
                        <tr class="odd"><th>Email</th><td><?php echo $model->email; ?></td></tr>
                        <tr class="even"><th>Comentario</th><td><?php echo $model->comentario; ?></td></tr>
                        <tr class="odd"><th>Fecha</th><td><?php echo $model->fecha; ?></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-3 col-md-offset-1 cont_der">
            <p>También puedes ir a:</p>
            <ul>
                <li><a href="<?php echo Yii::app()->createUrl('casos/seguimiento'); ?>" class="seguimiento-btn">Seguimiento de Casos</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('casos/reportes'); ?>" class="reportes-btn">Reportes</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="form">
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'casos-form',
                    'method' => 'post',
                    'action' => Yii::app()->createUrl('casos/observacion', array('id' => $model->id)),
                    'enableAjaxValidation' => true,
                    'htmlOptions' => array('class' => 'form-horizontal form-search')
                        ));
                ?>
                <input type="hidden" name="Casos[id]" value="<?php echo $model->id; ?>">
                <input type="hidden" name="Casos[tema]" value="<?php echo $model->tema; ?>">
                <input type="hidden" name="Casos[subtema]" value="<?php echo $model->subtema; ?>">
                <input type="hidden" name="Casos[nombres]" value="<?php echo $model->nombres; ?>">
                <input type="hidden" name="Casos[apellidos]" value="<?php echo $model->apellidos; ?>">
                <input type="hidden" name="Casos[cedula]" value="<?php echo $model->cedula; ?>">
                <input type="hidden" name="Casos[provincia]" value="<?php echo $model->provincia; ?>">
                <input type="hidden" name="Casos[ciudad]" value="<?php echo $model->ciudad; ?>">
                <input type="hidden" name="Casos[telefono]" value="<?php echo $model->telefono; ?>">
                <input type="hidden" name="Casos[celular]" value="<?php echo $model->celular; ?>">
                <input type="hidden" name="Casos[email]" value="<?php echo $model->email; ?>">
                <input type="hidden" name="Casos[comentario]" value="<?php echo $model->comentario; ?>">
                <input type="hidden" name="Casos[concesionario]" value="<?php echo $model->concesionario; ?>">
                <input type="hidden" name="Casos[estado]" value="Proceso">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'observaciones', array('class' => 'col-md-6 control-label')); ?>
                    <div class="col-sm-12">
                        <?php echo $form->textArea($model, 'observaciones', array('rows' => 6, 'cols' => 50, 'class' => 'form-control')); ?>
                        <?php echo $form->error($model, 'observaciones'); ?>
                    </div>
                </div>
                <div class="row buttons">
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', array('class' => 'btn btn-danger')); ?>
                </div>
                <?php $this->endWidget(); ?>
            </div> 
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6"><h3>Historial</h3></div>
                <?php if (Yii::app()->user->getState('roles') === 'super'): ?>
                    <div class="col-md-6">
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
                    </div>
                <?php endif; ?>
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
</div>




