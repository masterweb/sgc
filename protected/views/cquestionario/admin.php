<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilos.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/css/ui.daterangepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.min.js"></script>
<?php
	$accesosUser = Permiso::model()->findAll(array( 'condition' => "cargoId=:match", 'params' => array(':match' =>(int)Yii::app()->user->getState('cargo_id'))));

$case = ''; // para busqueda por defecto
//$getParams = '';    // para busqueda por parametros de GET
//if (isset($getParams)) {
//    echo '<pre>';
//    print_r($getParams);
//    echo '</pre>';
//}
//echo '<pre>';
//print_r($_GET);
//echo '</pre>';
//echo 'id call center: '.Yii::app()->user->getId().'<br>';
//echo 'rol: '.Yii::app()->user->getState('roles').'<br>';
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
        <h1 class="tl_seccion">Encuestas</h1>
    </div>
	<div class="row">
        <!-- FORMULARIO DE BUSQUEDA -->
        <div class="col-md-8">
            <div class="highlight">
                <div class="form">
                    <h4>Filtrar por:</h4>
                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'codigo-naturaleza-form',
                        'method' => 'get',
                        'action' => Yii::app()->createUrl('cquestionario/search/'.$idc),
                        'enableAjaxValidation' => true,
                        'htmlOptions' => array('class' => 'form-horizontal form-search')
                            ));
                    ?>
                    <label class="col-sm-2 control-label" for="Casos_estado">Descripci&oacute;n</label>
                        <div class="col-md-6">
                            <input size="10" maxlength="10" value="<?php echo $busqueda;?>" class="form-control" name="Modelos[Descripcion]" id="Modelos_descripcion" type="text">
							<div class="row col-md-19">
								<?php	foreach(Yii::app()->user->getFlashes() as $key => $message) {
									echo '<div class=" row flash-' . $key . '">' . $message . "</div>\n";
								}?>
							</div>   
						</div>
                    </div>
					
					
                    <div class="row buttons">
                        <?php //echo CHtml:submitButton($model->isNewRecord ? 'Buscar' : 'Save', array('class' => 'btn btn-danger'));     ?>
                        <input class="btn btn-danger" type="submit" name="yt0" value="Buscar">
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
				
            </div>
			
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
    <div class="row">
        <div class="table-responsive">
		<?php 
			if(!empty($accesosUser)){
				foreach($accesosUser as $a){
		?>
		
			
			<?php if( ($a->accesoSistema->controlador) == 'cquestionario' &&  ($a->accesoSistema->accion) ==  'create'){?>	
				<a href="<?php echo Yii::app()->createUrl('cquestionario/create/c/'.$idc); ?>" class="btn btn-primary btn-xs btn-danger btnCrear">Crear Encuesta</a>
			<?php } ?>
			
		
		<?php
				}
			}
		?><a href="<?php echo Yii::app()->createUrl('ccampana/admin'); ?>" class="btn btn-primary btn-xs btn-danger btnCrear">Regresar</a>
            <table class="tables tablesorter" id="keywords">
                <thead>
                    <tr>
                        <th><span>#</span></th>
                        <th><span>Base de Datos</span></th>
                        <th><span>Nombre</span></th>
                        <th><span>Descripci&oacute;n</span></th>
                        <th><span>Fecha Inicio</span></th>
                        <th><span>Fecha Finalizaci&oacute;n</span></th>
                        <th><span>Actualizar Auto.</span></th>
                        <th><span>Estado</span></th>
                        <th colspan="6">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
//$model = Casos::model()->findAll();
$cont = 1;
                    foreach ($model as $c):
                        ?>
                        <tr>
                            <td><?php echo $cont++ ?> </td>
                            <td><?php echo $c->cbasedatos->nombre; ?> </td>
                            <td><?php echo $c->nombre ?> </td>
                            <td><?php echo $c->descripcion ?> </td>
                            <td><?php echo $c->fechainicio ?> </td>
                            <td><?php echo $c->fechafin ?> </td>
                            <td><?php echo $c->automatico ?> </td>
                             <td><?php echo $c->estado ?> </td>
							<?php 
								if(!empty($accesosUser)){
									foreach($accesosUser as $a){
							?>
							<?php if( ($a->accesoSistema->controlador) == 'cquestionario' &&  ($a->accesoSistema->accion) ==  'update'){?>	
									<td><a href="<?php echo Yii::app()->createUrl('cquestionario/update', array('id' => $c->id)); ?>" class="btn btn-primary btn-xs btn-danger">Editar</a></td>
							<?php } ?>
							<?php if( ($a->accesoSistema->controlador) == 'cquestionario' &&  ($a->accesoSistema->accion) ==  'duplicar'){?>	
									<td><a href="<?php echo Yii::app()->createUrl('cquestionario/duplicar', array('id' => $c->id,'c'=>$idc)); ?>" onclick="return confirm('&iquest;Esta seguro que desea duplicar esta Encuesta, con todas sus preguntas?')" class="btn btn-primary btn-xs btn-info">Duplicar</a></td>
							<?php } ?>
							
							
							<?php if( ($a->accesoSistema->controlador) == 'cquestionario' &&  ($a->accesoSistema->accion) ==  'eliminar'){?>	
									<td><a href="<?php echo Yii::app()->createUrl('cquestionario/eliminar', array('id' => $c->id)); ?>" onclick="return confirm('&iquest;Esta seguro que desea eliminar esta Encuesta?')" class="btn btn-primary btn-xs btn-danger">Eliminar</a></td>
							<?php } ?>
							
							<?php if( ($a->accesoSistema->controlador) == 'cquestionario' &&  ($a->accesoSistema->accion) ==  'view'){?>	
									<td><a href="<?php echo Yii::app()->createUrl('cquestionario/view', array('id' => $c->id)); ?>" class="btn btn-primary btn-xs btn-danger">Ver</a></td>
							<?php } ?>
							
							<?php if( ($a->accesoSistema->controlador) == 'cpregunta' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
									<td><a href="<?php echo Yii::app()->createUrl('cpregunta/admin', array('id' => $c->id)); ?>" class="btn btn-primary btn-xs btn-danger">Preguntas</a></td>
							<?php } ?>

							<?php if( ($a->accesoSistema->controlador) == 'cencuestadoscquestionario' &&  ($a->accesoSistema->accion) ==  'generar'){?>	
									<td><a href="<?php echo Yii::app()->createUrl('cencuestadoscquestionario/generar', array('id' => $c->id)); ?>" class="btn btn-success btn-xs btn-success">Asignar Encuestados</a></td>
							<?php } ?>
							
							<?php
									}
								}
							?>
                        </tr>
                        <?php
                    endforeach;
                    ?>
                </tbody>
            </table>
		
        </div>

    </div>
   <div class="row">
        <div class="col-md-5">
<?php $this->widget('CLinkPager', array('pages' => $pages, 'maxButtonCount' => 5)); ?>
        </div>
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