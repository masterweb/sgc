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
	.tl_seccion {
		width: 100%;
	}
</style>

<div class="container">
    <div class="row">
	<?php
		$encuesta = Cquestionario::model()->findByPk($idc);
		$opcionP = Copcionpregunta::model()->findByPk($opcion);
	?>
        <h1 class="tl_seccion">Encuesta : <i><?php echo $encuesta->nombre;?></i> pregunta: <?php echo $opcionP->cpregunta->descripcion?> opci&oacute;n <i><?php echo $opcionP->detalle?></i></h1>
    </div>
    <div class="row">
        <div class="table-responsive">
		<?php 
			if(!empty($accesosUser)){
				foreach($accesosUser as $a){
		?>
		
			<?php if( ($a->accesoSistema->controlador) == 'cpregunta' &&  ($a->accesoSistema->accion) ==  'create'){?>	
				<a href="<?php echo Yii::app()->createUrl('cpregunta/seleccion/c/'.$idc.'/opcion/'.$opcion); ?>" class="btn btn-primary btn-xs btn-danger btnCrear">Crear Pregunta</a>
			<?php } ?>
			
		
		<?php
				} 
			}
			
		?><a href="<?php echo Yii::app()->createUrl('cpregunta/opcionesactualizar/id/'.$opcionP->cpregunta->id.'/c/'.$idc.'/op/'.$opcionP->cpregunta->ctipopregunta_id); ?>" class="btn btn-primary btn-xs btn-danger btnCrear">Regresar</a>
		
            <table class="tables tablesorter" id="keywords">
                <thead>
                    <tr>
                        <th><span>#</span></th>
                        <th><span>Tipo Pregunta</span></th>
                        <th><span>Pregunta</span></th>
                        <th><span>Estado</span></th>
                        <th><span>Orden</span></th>
                        <th colspan="3">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
//$model = Casos::model()->findAll();
$num=1;
                    foreach ($model as $c):
                        ?>
                        <tr>
                            <td><?php echo $num++; ?> </td>
                            <td><?php echo $c->ctipopregunta->descripcion; ?> </td>
                            <td><?php echo $c->descripcion ?> </td>
                            <td><?php echo $c->estado ?> </td>
                            <td><?php echo $c->orden ?> </td>
							<?php 
								if(!empty($accesosUser)){
									foreach($accesosUser as $a){
							?>
							
							
							
							<?php if( ($a->accesoSistema->controlador) == 'cpregunta' &&  ($a->accesoSistema->accion) ==  'eliminar'){?>	
									<td><a href="<?php echo Yii::app()->createUrl('cpregunta/eliminar', array('id' => $c->id)); ?>" onclick="return confirm('&iquest;Esta seguro que desea eliminar esta Pregunta?')" class="btn btn-primary btn-xs btn-danger">Eliminar</a></td>
							<?php } ?>
							
							<?php if( (($a->accesoSistema->controlador) == 'copcionpregunta' &&  ($a->accesoSistema->accion) ==  'admin')){?>	
									<?php if($c->ctipopregunta_id == 1){?>
									<td><a href="<?php echo Yii::app()->createUrl('cpregunta/update', array('id' => $c->id)); ?>" class="btn btn-primary btn-xs btn-danger">Editar</a></td>
									
							<?php }else if($c->ctipopregunta_id == 4){ ?>
									<td><a href="<?php echo Yii::app()->createUrl('cpregunta/matrizactualizar', array('id' => $c->id,'c'=>$c->cquestionario_id,'op'=>$c->ctipopregunta_id)); ?>" class="btn btn-primary btn-xs btn-danger">Editar</a></td>
							<?php	}else{?>
								<td><a href="<?php echo Yii::app()->createUrl('cpregunta/opcionesactualizar', array('id' => $c->id,'c'=>$c->cquestionario_id,'op'=>$c->ctipopregunta_id)); ?>" class="btn btn-primary btn-xs btn-danger">Editar</a></td>
							<?php	}
							} ?>
							
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
<?php //$this->widget('CLinkPager', array('pages' => $pages, 'maxButtonCount' => 5)); ?>
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