<?php
/* @var $this GestionCierreController */
/* @var $model GestionCierre */
/* @var $form CActiveForm */

?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.css" rel="stylesheet">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/datetimepicker/jquery.datetimepicker.js"></script>
<script type="text/javascript">
    $(function () {
        $('#GestionCierre_fecha').datetimepicker({
            lang: 'es',
            onGenerate: function (ct) {
                //$(this).find('.xdsoft_date.xdsoft_weekend')
                //        .addClass('xdsoft_disabled');
            },
            timepicker:false,
            format:'Y-m-d'
            //weekends: ['01.01.2014', '02.01.2014', '03.01.2014', '04.01.2014', '05.01.2014', '06.01.2014'],
            //minDate: '-1970/01/01', //yesterday is minimum date(for today use 0 or -1970/01/01)
            //disabledDates: ['03.04.2015', '01.05.2015', '10.08.2015', '09.10.2015', '02.11.2015', '03.11.2015', '25.12.2015'], formatDate: 'd.m.Y'
        });
        $('#GestionCierre_fecha_venta').datetimepicker({
            lang: 'es',
            onGenerate: function (ct) {
                //$(this).find('.xdsoft_date.xdsoft_weekend')
                //        .addClass('xdsoft_disabled');
            },
            timepicker:false,
            format:'Y-m-d'
            //weekends: ['01.01.2014', '02.01.2014', '03.01.2014', '04.01.2014', '05.01.2014', '06.01.2014'],
            //minDate: '-1970/01/01', //yesterday is minimum date(for today use 0 or -1970/01/01)
            //disabledDates: ['03.04.2015', '01.05.2015', '10.08.2015', '09.10.2015', '02.11.2015', '03.11.2015', '25.12.2015'], formatDate: 'd.m.Y'
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
                    $criteria = new CDbCriteria(array('condition' => "id = {$_GET['id_informacion']}"));
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
                        <h1 class="tl_seccion">Datos de Cliente a Facturar</h1>
                    </div>
                    <div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'gestion-cierre-form',
	'enableAjaxValidation'=>false,
    'htmlOptions'=>array(
        'class'=>'form-horizontal',
    ),
    
)); ?>


	<?php echo $form->errorSummary($model); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'numero_chasis', array('class' => 'control-label col-md-2')); ?>
            <div class="col-md-5">
		<?php echo $form->textField($model,'numero_chasis',array('size'=>60,'maxlength'=>40, 'class' => 'form-control')); ?>
            </div>    
		<?php echo $form->error($model,'numero_chasis'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'numero_modelo', array('class' => 'control-label col-md-2')); ?>
            <div class="col-md-5">
		<?php echo $form->textField($model,'numero_modelo',array('size'=>50,'maxlength'=>50, 'class' => 'form-control')); ?>
            </div>    
		<?php echo $form->error($model,'numero_modelo'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'nombre_propietario', array('class' => 'control-label col-md-2')); ?>
            <div class="col-md-5">
		<?php echo $form->textField($model,'nombre_propietario',array('size'=>60,'maxlength'=>250, 'class' => 'form-control')); ?>
                </div> 
		<?php echo $form->error($model,'nombre_propietario'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'color_vehiculo', array('class' => 'control-label col-md-2')); ?>
            <div class="col-md-5">
		<?php echo $form->textField($model,'color_vehiculo',array('size'=>20,'maxlength'=>20, 'class' => 'form-control')); ?>
                </div> 
		<?php echo $form->error($model,'color_vehiculo'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'factura', array('class' => 'control-label col-md-2')); ?>
            <div class="col-md-5">
		<?php echo $form->textField($model,'factura',array('size'=>50,'maxlength'=>50, 'class' => 'form-control')); ?>
                </div> 
		<?php echo $form->error($model,'factura'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'concesionario', array('class' => 'control-label col-md-2')); ?>
            <div class="col-md-5">
		<?php echo $form->textField($model,'concesionario',array('size'=>20,'maxlength'=>20, 'class' => 'form-control')); ?>
                </div> 
		<?php echo $form->error($model,'concesionario'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'fecha_venta', array('class' => 'control-label col-md-2')); ?>
            <div class="col-md-5">
		<?php echo $form->textField($model,'fecha_venta',array('size'=>25,'maxlength'=>25, 'class' => 'form-control')); ?>
                </div> 
		<?php echo $form->error($model,'fecha_venta'); ?>
	</div>

	<div class="form-group">
		<?php //echo $form->labelEx($model,'year', array('class' => 'control-label col-md-2')); ?>
            <label for="" class="control-label col-md-2">Año</label>
            <div class="col-md-5">
		<?php echo $form->dropDownList($model,'year',array('2015'=>2015, '2016' => 2016), array('class' => 'form-control')); ?>
                </div> 
		<?php echo $form->error($model,'year'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'color_origen', array('class' => 'control-label col-md-2')); ?>
            <div class="col-md-5">
		<?php echo $form->textField($model,'color_origen',array('size'=>30,'maxlength'=>30, 'class' => 'form-control')); ?>
                </div> 
		<?php echo $form->error($model,'color_origen'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'identificacion', array('class' => 'control-label col-md-2')); ?>
            <div class="col-md-5">
		<?php echo $form->textField($model,'identificacion',array('size'=>20,'maxlength'=>20, 'class' => 'form-control')); ?>
                </div> 
		<?php echo $form->error($model,'identificacion'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'precio_venta', array('class' => 'control-label col-md-2')); ?>
            <div class="col-md-5">
		<?php echo $form->textField($model,'precio_venta',array('size'=>50,'maxlength'=>50, 'class' => 'form-control')); ?></div> 
                
		<?php echo $form->error($model,'precio_venta'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'calle_principal', array('class' => 'control-label col-md-2')); ?>
            <div class="col-md-5">
		<?php echo $form->textField($model,'calle_principal',array('size'=>60,'maxlength'=>200, 'class' => 'form-control')); ?></div> 
		<?php echo $form->error($model,'calle_principal'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'numero_calle', array('class' => 'control-label col-md-2')); ?>
            <div class="col-md-5">
		<?php echo $form->textField($model,'numero_calle',array('size'=>10,'maxlength'=>10, 'class' => 'form-control')); ?></div> 
		<?php echo $form->error($model,'numero_calle'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'telefono_propietario', array('class' => 'control-label col-md-2')); ?>
            <div class="col-md-5">
		<?php echo $form->textField($model,'telefono_propietario',array('size'=>10,'maxlength'=>10, 'class' => 'form-control')); ?></div> 
		<?php echo $form->error($model,'telefono_propietario'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'grupo_concesionario', array('class' => 'control-label col-md-2')); ?>
            <div class="col-md-5">
                <?php
                $grupos = CHtml::listData(Grupo::model()->findAll(array('condition' => "ruc <> ''",'order'=> 'nombre_grupo ASC')), "id", "nombre_grupo");
                ?>
		<?php echo $form->dropDownList($model,'grupo_concesionario',$grupos, array('class' => 'form-control')); ?></div> 
		<?php echo $form->error($model,'grupo_concesionario'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'forma_pago', array('class' => 'control-label col-md-2')); ?>
            <div class="col-md-5">
		<?php echo $form->dropDownList($model,'forma_pago',array('Credito' => 'Crédito','Contado'=>'Contado'), array('class' => 'form-control')); ?></div> 
		<?php echo $form->error($model,'forma_pago'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'observacion', array('class' => 'control-label col-md-2')); ?>
            <div class="col-md-5">
		<?php echo $form->dropDownList($model,'observacion',array(
                    'Factura a Nombre de Cliente'=>'Factura a Nombre de Cliente',
                    'Factura a Nombre de Familiar' => 'Factura a Nombre de Familiar',
                    'Factura a Nombre de 3ra Persona' => 'Factura a Nombre de 3ra Persona',
                    'Factura a Nombre de Empresa' => 'Factura a Nombre de Empresa'
                    ), 
                        array('class' => 'form-control')); ?></div> 
		<?php echo $form->error($model,'observacion'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'fecha', array('class' => 'control-label col-md-2')); ?>
            <div class="col-md-5">
		<?php echo $form->textField($model,'fecha',array('class' => 'form-control')); ?></div> 
		<?php echo $form->error($model,'fecha'); ?>
	</div>
        

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Grabar' : 'Grabar', array('class' => 'btn btn-danger')); ?>
            <input type="hidden" name="id_informacion" value="<?php echo $_GET['id_informacion']; ?>" />
            <input type="hidden" name="id_vehiculo" value="<?php echo $_GET['id_vehiculo']; ?>" />
	</div>
        

<?php $this->endWidget(); ?>

</div><!-- form -->
                </div> 
                <br />
                <br />
                <?= $this->renderPartial('//layouts/rgd/links');?>
            </div>
        </div>
    </div>
    
</div>
