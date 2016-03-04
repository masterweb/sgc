<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilos.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/css/ui.daterangepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.min.js"></script>
<style>
	.cat {
	    width: 193px;
	    height: 15px;
	    padding: 7px;
	    font-weight: bold;
	    text-align: right;
	    font-size: 13px;
	}
</style>
<?php
	$accesosUser = Permiso::model()->findAll(array( 'condition' => "cargoId=:match", 'params' => array(':match' =>(int)Yii::app()->user->getState('cargo_id'))));

$case = ''; // para busqueda por defecto

$rol = Yii::app()->user->getState('roles');
?>
<div class="container">
	<div class="row">
        <h1 class="tl_seccion">Detalle de Reporte</h1>
    </div>

	<div class="row">
	    <!-- FORMULARIO DE BUSQUEDA -->
	    <div class="col-md-12">
	        <div class="highlight">
                <div class="form row">
                	<h3 class="text-center" style="background:#fff;width:100%;padding:10px;border:1px solid #ccc;">Detalles de la Encuesta <b>'<?php echo strtoupper($model->nombre)?>'</b></h3>
                	<div class="row" ><div class="col-md-6"><label class="cat">CAMPAÑA: </label><?php echo $model->ccampana->descripcion?></div>
					<div class="col-md-6"><label class="cat">NOMBRE: </label><?php echo $encuesta->cencuestados->nombre?></div>
					<div class="col-md-6"><label class="cat">EMCUESTADOR: </label><?php echo $encuesta->usuarios->apellido .' '.$encuesta->usuarios->nombres?></div>
					<div class="col-md-6"><label class="cat">TIEMPO: </label><?php echo $encuesta->tiempo?></div>
					<div class="col-md-6"><label class="cat">FECHA DE INICIO: </label><?php echo $encuesta->tiempoinicio?></div>
					<div class="col-md-6"><label class="cat">FECHA DE FINALIZACIÓN: </label><?php echo $encuesta->tiempofinal?></div>
					<div class="col-md-6"><label class="cat">AUDIO : </label><?php echo '<a target="_blank" href="'.Yii::app()->request->baseUrl.'/upload/audio/'.$encuesta->audio.'">Click para escuchar</a>'?></div>
					<div class="col-md-6"><label class="cat">OBSERVACIONES : </label><?php echo $encuesta->tiempofinal?></div></div>
                </div>
					<div class="row">
					<div class="col-md-12">
                        <a href="<?php echo Yii::app()->createUrl('cquestionario/detallereporte/'.(int)$model->id)?>"><input class="btn btn-danger" type="button" name="yt0" value="Regresar"></a>
                    </div>
                    </div>
                
				
				<?php	foreach(Yii::app()->user->getFlashes() as $key => $message) {
					echo '<div class=" row flash-' . $key . '">' . $message . "</div>\n";
				}?>
                
                
            </div>
        </div>
		
    </div>
		
</div>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h3><b>Resultado de la Encuesta</b></h3>
			<?php
				$preguntas = Cpregunta::model()->findAll(array('condition'=>'cquestionario_id = '.(int)$model->id));
				if(!empty($preguntas)){
					foreach ($preguntas as $key) {
						//RESPUESTA
						$respuesta = Cencuestadospreguntas::model()->find(array("condition"=>'cencuestadoscquestionario_id='.$encuesta->id.' and pregunta_id = '.$key->id));
						if(!empty($respuesta)){
							echo '<div style="background:#fff;width:100%;padding:5px;border:1px solid #ccc;margin:5px auto;height:25px"><label style="width: 95px;background:#ccc;font-weight:100;height: 24px;padding: 5px;margin: -5px auto auto -5px;font-size: 13px;">'.$key->ctipopregunta->descripcion.'</label><label style="margin:0 0 0 10px;font-size:13px;font-weight:bold;">'.$key->descripcion.'</label></div>';
							$resp = '';
							switch($key->ctipopregunta_id){
								case '4':
									$respuestas = Cencuestadospreguntas::model()->findAll(array("condition"=>'cencuestadoscquestionario_id='.$encuesta->id.' and pregunta_id = '.$key->id));
								if(!empty($respuestas)){
									foreach ($respuestas as $keyr) {
										$resp .= '<div style="padding:2px;font-size:13px;margin-left:5px;"> &nbsp;'.$keyr->respuesta.'</div>';
									}
									
								}
									
								break;
								default:
									$resp = $respuesta->respuesta;
								break;
							}
							echo '<div style="width:100%;padding:5px;border:1px solid #ccc;margin:5px auto;height:auto"><label style="margin:0 0 0 10px;font-size:13px;font-weight:bold;">'.$resp.'</label></div>';

						}
					}
				}
			?>
		</div>
	</div>

</div>

    <!--SECCION DE ENLACES DIRECTOS-->
<div class="container">
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
