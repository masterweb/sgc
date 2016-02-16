<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilosCall.css" type="text/css" />
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
	.ui-icon.ui-icon-triangle-1-e {
		display: none;
	}
	.ui-icon.ui-icon-triangle-1-s {
		display: none;
	}
	.ui-accordion-content.ui-helper-reset.ui-widget-content.ui-corner-bottom.ui-accordion-content-active {
    height: auto !important;
}
</style>

<div class="container">
    <div class="row">
        <h1 class="tl_seccion">Asignar Encuestados a: <?php echo strtoupper($questionario->descripcion); ?></h1>
    </div>


	<div class="row">
        <div class="col-md-8">
		 <?php 

		 if (Yii::app()->user->hasFlash('success')){ ?>
                <div class="info">
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
		 <?php }else{ ?>
		
            <pre>
            	<div class="col-xs-12 col-md-8">Encuestados Disponibles: <b><?php echo count($encuestados);?> personas</b></div>
            	<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('cencuestadoscquestionario/seleccionar/'.$id); ?>"><input type="button" value="Seleccionar Nuevamente" class="btn btn-default"></a></div>
  			</pre>
  		<?php if(!empty($encuestados)){ ?>
  			<h4>Asesores</h4>
  			<form id="grabarasesores" class="form-horizontal" method="post" action="<?php echo Yii::app()->request->baseUrl; ?>/index.php/cencuestadoscquestionario/generar/id/<?php echo $questionario->id?>">
			
  			<?php 

  			if(!empty($asesores)){ ?>
  				<table class="table table-condensed">
			      <thead>
			        <tr>
			          <th>#</th>
			          <th>Nombre</th>
			          <th>Cargo</th>
			          <th>Activar</th>
			        </tr>
			      </thead>
			      <tbody>
			      	<?php 
			      	$cont = 1;
			      	foreach ($asesores as $item) {
			      		$check = '';
			      		if(!empty($asesoresYaAsignados)){
			      			foreach ($asesoresYaAsignados as $it) {
			      				if($it->usuarios_id == $item->id)
			      					$check = 'checked ="true"';
			      			}
			      		}
			      		echo '
			      		<tr>
				          <th scope="row">'.$cont.'</th>
				          <td>'.$item->apellido.' '.$item->nombres.'</td>
				          <td>'.$item->cargo->descripcion.' '.$item->cargo->area->descripcion.'</td>
				          <td><input type="checkbox" '.$check.' name="check['.$item->id.']" id="chk_'.$item->id.'" value="'.$item->id.'"></td>
				        </tr>';
				        $cont ++;
			      	} ?>
			        
			       
			      </tbody>
			    </table>
			    <input type="submit" value="Guardar y Asignar" class="btn btn-danger">
			</form>
  			<?php } ?>
		<?php 

			}else echo '<b>No hay encuestados disponibles para realizar este questionario.</b>';
		
		} ?>
        </div>
		  <div class="col-md-3 col-md-offset-1 cont_der">
            <p class="border-bt">Tambi&eacute;n puedes ir a:</p>
            <ul>
                <li><a href="<?php echo Yii::app()->createUrl('cquestionario/admin/'.$questionario->ccampana_id); ?>" class="seguimiento-btn">Administrador de encuestas</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></li>
				<li><a href="javascript:history.back(1)" class="back-btn-go">Volver</a></li>
            </ul>
        </div>
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
			
					
			<?php
					}
				}
			?>
        </div>
    </div>
</div>