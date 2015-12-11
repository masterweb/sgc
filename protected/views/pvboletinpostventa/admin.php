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
	
	 $(function() {

        $( "#Modelos_fecha,#Modelos_fechaf" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: new Date(1950, 10 - 1, 25),
            yearRange: '2013:2016'
        });
	 });
</script>    
<style>
    .form-search{
        padding: 0;
    }
	.b{
		margin-left: 15px;
	}
</style>

<div class="container">
    <div class="row">
        <h1 class="tl_seccion">Boletines Posventa</h1>
    </div>
	<div class="row">
        <!-- FORMULARIO DE BUSQUEDA -->
        <div class="col-md-11">
            <div class="highlight">
                 <div class="form">
					<h4>Filtrar por:</h4>
					<?php
					$form = $this->beginWidget('CActiveForm', array(
						'id' => 'codigonaturaleza-form',
						'method' => 'get',
						'action' => Yii::app()->createUrl('pvboletinpostventa/search'),
						'enableAjaxValidation' => true,
						'htmlOptions' => array('class' => 'form-horizontal form-search')
							));
					?>
					<div class="form-group row">
						<label class="col-sm-2 control-label" for="Casos_estado">Fecha Inicio</label>
						<div class="col-md-4">
							<input size="10" maxlength="10" value="<?php echo $fechaBusqueda;?>" class="form-control" name="fechaInicio" id="Modelos_fecha" type="text">
						</div>
						
						<label class="col-sm-2 control-label" for="Casos_estado">Fecha Fin</label>
						<div class="col-md-4">
							<input size="10" maxlength="10" value="<?php echo $fechaBusquedaFin;?>" class="form-control" name="fechaFin" id="Modelos_fechaf" type="text">
						</div>
						
					</div>
						
					
			
				  <div class="form-group row">
					
						<label class="col-sm-2 control-label" for="Casos_estado">C&oacute;digo o Titular</label>
						<div class="col-md-10">
							<input size="10" maxlength="10" value="<?php echo $busqueda;?>" class="form-control" name="Modelos[Descripcion]" id="Modelos_descripcion" type="text">
							<div class="row col-md-19">
								<?php	foreach(Yii::app()->user->getFlashes() as $key => $message) {
									echo '<div class=" row flash-' . $key . '">' . $message . "</div>\n";
								}?>
							</div>   
						</div>
				  </div>
				  	 
                    <div class="row buttons b">
                        <?php //echo CHtml:submitButton($model->isNewRecord ? 'Buscar' : 'Save', array('class' => 'btn btn-danger'));     ?>
                        <input class="btn btn-danger" type="submit" name="yt0" value="Buscar">
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
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
		<div>
		<?php 
			if(!empty($accesosUser)){
				foreach($accesosUser as $a){
					if( ($a->accesoSistema->controlador) == 'pvboletinpostventa' &&  ($a->accesoSistema->accion) ==  'create'){?>	
					<a href="<?php echo Yii::app()->createUrl('pvboletinpostventa/create'); ?>" class="btn btn-primary btn-xs btn-danger btnCrear">Crear Bolet&iacute;n</a>
					<?php }
					}
				}?>
			</div>
            <table class="tables tablesorter" id="keywords">
                <thead>
                    <tr>
                        <th><span>ID</span></th>
                        <th><span>Titular</span></th>
                        <th><span>C&oacute;digo</span></th>
                        <th><span>Fecha</span></th>
                        <th><span>Publicar:</span></th>
                        <th colspan="4">Opciones</th>
                        
						
                    </tr>
                </thead>
                <tbody>
                    <?php
//$model = Casos::model()->findAll();
                    foreach ($model as $c):
                        ?>
                        <tr>
                            <td><?php echo $c->id; ?> </td>
                            <td><?php echo $c->titulo ?> </td>
                            <td><?php echo $c->codigo ?> </td>
                            <td><?php echo $c->fecha ?> </td>
                            <td><?php echo ($c->publicar =='s')?'SI':'NO'; ?> </td>
							
							<?php 
						if(!empty($accesosUser)){
							foreach($accesosUser as $a){
							?>
								<?php if( ($a->accesoSistema->controlador) == 'pvboletinpostventa' &&  ($a->accesoSistema->accion) ==  'update'){?>	
									<td><a href="<?php echo Yii::app()->createUrl('pvboletinpostventa/update', array('id' => $c->id)); ?>" class="btn btn-primary btn-xs btn-danger">Editar</a></td>
                            	<?php } ?>
								
								<?php if( ($a->accesoSistema->controlador) == 'pvboletinpostventa' &&  ($a->accesoSistema->accion) ==  'eliminar'){?>	                           
									<td><a href="<?php echo Yii::app()->createUrl('pvboletinpostventa/eliminar', array('id' => $c->id)); ?>" onclick="return confirm('&iquest;Esta seguro que desea eliminar este Bolet&iacute;n y todos sus archivos adjuntos?')" class="btn btn-primary btn-xs btn-danger">Eliminar</a></td>
								<?php } ?>
								
								<?php if( ($a->accesoSistema->controlador) == 'pvboletinpostventa' &&  ($a->accesoSistema->accion) ==  'view'){?>	                           
									<td><a href="<?php echo Yii::app()->createUrl('pvboletinpostventa/view', array('id' => $c->id)); ?>" class="btn btn-primary btn-xs btn-danger">Ver</a></td>
								<?php } ?>
								
								<?php if( ($a->accesoSistema->controlador) == 'pvboletinpostventa' &&  ($a->accesoSistema->accion) ==  'adminAdjunto'){?>	                           
									<td><a href="<?php echo Yii::app()->createUrl('pvboletinpostventa/adminAdjunto', array('id' => $c->id)); ?>" class="btn btn-primary btn-xs btn-danger">Adjuntar</a></td>
								<?php } 
							
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