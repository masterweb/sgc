<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilos.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/css/ui.daterangepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.min.js"></script>
<?php
$accesosUser = Permiso::model()->findAll(array( 'condition' => "cargoId=:match", 'params' => array(':match' =>(int)Yii::app()->user->getState('cargo_id'))));

$case = '';
$rol = Yii::app()->user->getState('roles');
?>
<script type="text/javascript">
    var abrir=0;
    $(function() {
        $("#keywords").tablesorter();
    });
    function verN(num){
        if(num > 0){
            if(abrir == 0){
                $("#lNotificaciones").show();
                abrir=1;
            }else{
                $("#lNotificaciones").hide();
                abrir=0;
	
            }
        }
        
    }
</script>    
<style>
    .form-search{
        padding: 0;
    }
</style>

<div class="container">
    <div class="row">
        <h1 class="tl_seccion">QIR Adicional</h1>
    </div>
    <div class="row">
        <!-- FORMULARIO DE BUSQUEDA -->
        <div class="col-md-8">
            <div class="highlight">
                <div class="form">
                    <h4>Filtrar por:</h4>
                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'Qir-form',
                        'method' => 'get',
                        'action' => Yii::app()->createUrl('pvQiradicional/search/id/' . $id),
                        'enableAjaxValidation' => true,
                        'htmlOptions' => array('class' => 'form-horizontal form-search')
                            ));
                    ?>
                    <label class="col-sm-4 control-label" for="Casos_estado">Busqueda QIR adicional</label>
                    <div class="col-md-5">
                        <?php echo $form->textField($modelPost, 'vin', array('class' => "form-control")) ?>                        
                        <div class="row col-md-19"></div>
                    </div>                    
                </div>

                <div class="row buttons">
                    <?php //echo CHtml:submitButton($model->isNewRecord ? 'Buscar' : 'Save', array('class' => 'btn btn-danger'));      ?>
                    <input class="btn btn-danger" type="submit" name="yt0" value="Buscar">
                </div>
                <?php $this->endWidget(); ?>
            </div>

            <div class="row col-md-6 " id="msnError">
                <?php
                foreach (Yii::app()->user->getFlashes() as $key => $message) {
                    echo '<div class="row flash-error">' . $message . "</div>\n";
                }
                ?>
                <div class="row col-md-19"></div>
            </div>

        </div>
        <div class="col-md-3 col-md-offset-1 cont_der">

            <p class="border-bt">Tambi&eacute;n puedes ir a:</p>
            <ul>
                <li><a href="<?php echo Yii::app()->createUrl('pvQir/admin'); ?>" class="seguimiento-btn">Administrador de QIR</a></li>
                <!--<li><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></li>-->
               <li><a href="javascript:history.back(1)" class="back-btn-go">Volver</a></li>
            </ul>
        </div>
        <br>
    </div>

    <div class="row">
        <div class="col-md-9">
            <?php
            if (isset($title_busqueda)):
                echo '<h4 style="color:#AA1F2C;border-bottom: 1px solid;">' . $title_busqueda . '</h4>';
            endif;
            ?>
        </div>
    </div>

    <?php
    if (Yii::app()->user->hasFlash('message')) {
        ?>
        <div class="message">
            <?php
            echo Yii::app()->user->getFlash('message');
            ?>
        </div>
        <?php
    }
    ?>


    <div class="row">
        <div class="table-responsive">
            <div>
				<?php 
			if(!empty($accesosUser)){
				foreach($accesosUser as $a){
					if( ($a->accesoSistema->controlador) == 'pvQiradicional' &&  ($a->accesoSistema->accion) ==  'create'){?>	
						<a href="<?php echo Yii::app()->createUrl('pvQiradicional/create/id/' . $id); ?>" class="btn btn-primary btn-xs btn-danger btnCrear">Crear Vin adicional</a>                
						<?php }
					}
				}?>
            </div>
            <table class="tables tablesorter" id="keywords">
                <thead>
                    <tr>
                        <th><span>ID</span></th>
                        <th><span>Vin adicional</span></th>
                        <th><span>Num Motor</span></th>
                        <th><span>Kilometraje</span></th>
                        <th><span>Num Reporte</span></th>
                        <th colspan="3">Opciones</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php
                    //$model = new Qiradicional();
                    if ($model) {
                        foreach ($model as $c):
                            ?>
                            <tr>
                                <td><?php echo $c->id; ?> </td>
                                <td><?php echo $c->vin ?> </td>
                                <td><?php echo $c->num_motor ?> </td>
                                <td><?php echo $c->kilometraje ?> </td>
                                <td><?php echo $c->num_reporte ?> </td>
								<?php if(!empty($accesosUser)){
									foreach($accesosUser as $a){
									?>
										<?php if( ($a->accesoSistema->controlador) == 'pvQiradicional' &&  ($a->accesoSistema->accion) ==  'view'){?>	
											<td><a href="<?php echo Yii::app()->createUrl('pvQiradicional/view', array('id' => $c->id)); ?>" class="btn btn-primary btn-xs btn-danger">Ver</a></td>
										<?php } ?>
										
										<?php if( ($a->accesoSistema->controlador) == 'pvQiradicional' &&  ($a->accesoSistema->accion) ==  'update'){?>	                           
											<td><a href="<?php echo Yii::app()->createUrl('pvQiradicional/update', array('id' => $c->id)); ?>" class="btn btn-primary btn-xs btn-danger">Editar</a></td>
										<?php } ?>
										
										<?php if( ($a->accesoSistema->controlador) == 'pvQiradicional' &&  ($a->accesoSistema->accion) ==  'eliminar'){?>	                           
											<td><?php echo CHtml::link('Eliminar', array('pvQiradicional/eliminar', 'id' => $c->id), array('class' => 'btn btn-primary btn-xs btn-danger', 'onclick' => "return confirm('Esta seguro que desea eliminar este Código Causal?')")) ?>
										<?php } 
								
									}
								}
							?>
                            </tr>
                            <?php
                        endforeach;
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-5">
            <?php $this->widget('CLinkPager', array('pages' => $pages, 'maxButtonCount' => 5)); ?>
        </div>
    </div>

    <div class="col-md-8">
        <h1 class="tl_seccion">Ver Qir #<?php echo $modelQir->id; ?></h1>
        <?php
        $this->widget('zii.widgets.CDetailView', array(
            'data' => $modelQir,
            'attributes' => array(
                'id',
                'dealer.name:raw:Concesionario',
                'num_reporte',
                'fecha_registro',
                'modeloPostVenta.descripcion:raw:Modelo',
                'num_vehiculos_afectados',
                'prioridad',
                'parte_defectuosa',
                'vin',
                'num_motor',
                'transmision',
                'num_parte_casual',
                'detalle_parte_casual',
                'codigo_naturaleza',
                'codigo_casual',
                'fecha_garantia',
                'fecha_fabricacion',
                'kilometraje',
                'fecha_reparacion',
                'titular',
                array(
                    'name' => 'descripcion',
                    'label' => 'Descripcion',
                    'value' => $modelQir->descripcion,
                    'type' => 'raw'
                ),
                'ingresado',
                'email',
                'circunstancia',
                'periodo_tiempo',
                'rango_velocidad',
                'localizacion',
                'fase_manejo',
                'condicion_camino',
                'etc',
                'vin_adicional',
                'num_motor_adi',
                'kilometraje_adic',
                'estado',
            ),
        ));
        ?>
    </div>
    <div class="row">        
       <div class="col-md-12 links-tabs links-footer">
            
            <div class="col-md-2"><p>Tambi&eacute;n puedes ir a:</p></div>
			<?php 
				if(!empty($accesosUser)){
					foreach($accesosUser as $a){
			?>
					<?php if( ($a->accesoSistema->controlador) == 'pvQir' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvQir/admin'); ?>" class="qir-btn"><span class="textoFEnlace">QIR</span></a></div>
					<?php } ?>
							
					<?php if( ($a->accesoSistema->controlador) == 'pvboletinpostventa' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvboletinpostventa/admin'); ?>" class="boletines-btn"><span class="textoFEnlace">Boletines</span></a></div>
					<?php } ?>
							
					<?php if( ($a->accesoSistema->controlador) == 'pvvinMotor' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvvinMotor/admin'); ?>" class="vin-btn"><span class="textoFEnlace">Vin-Motor</span></a></div>
					<?php } ?>
								
					<?php if( ($a->accesoSistema->controlador) == 'pvmodelosposventa' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvmodelosposventa/admin'); ?>" class="modelospv-btn"><span class="textoFEnlace">Modelos</span></a></div>
					<?php } ?>
							
					<?php if( ($a->accesoSistema->controlador) == 'pvcodigoCausal' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvcodigoCausal/admin'); ?>" class="causal-btn"><span class="textoFEnlace">C&oacute;digo Causal</span></a></div>
					<?php } ?>
							
							
					<?php if( ($a->accesoSistema->controlador) == 'pvcodigoNaturaleza' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvcodigoNaturaleza/admin'); ?>" class="naturaleza-btn"><span class="textoFEnlace">C&oacute;digo Naturaleza</span></a></div>
					<?php } ?>
					
					<?php if( ($a->accesoSistema->controlador) == 'uaccesosistema' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('uaccesosistema/admin'); ?>" class="accesos-btn"><span class="textoFEnlace">Accesos al Sistema</span></a></div>
					<?php } ?>
					
					<?php if( ($a->accesoSistema->controlador) == 'ucargo' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('ucargo/admin'); ?>" class="cargos-btn"><span class="textoFEnlace">Cargos y Perfiles</span></a></div>
					<?php } ?>
					
					<?php if( ($a->accesoSistema->controlador) == 'uusuarios' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('uusuarios/admin'); ?>" class="usuarios-btn"><span class="textoFEnlace">Usuarios Kia</span></a></div>
					<?php } ?>
					
					<?php if( ($a->accesoSistema->controlador) == 'uusuarios' &&  ($a->accesoSistema->accion) ==  'contactos'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('uusuarios/contactos'); ?>" class="contactos-btn"><span class="textoFEnlace">Cont&aacute;ctos</span></a></div>
					<?php } ?>
				<?php if( ($a->accesoSistema->controlador) == 'uarea' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('uarea/admin'); ?>" class="contactos-btn"><span class="textoFEnlace">&Aacute;reas</span></a></div>
					<?php } ?>
					
			<?php
					}
				}
			?>
			<div class="col-md-1"><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></div>	
        </div>
    </div>
</div>