<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilos.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/css/ui.daterangepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.min.js"></script>

<?php
	$accesosUser = Permiso::model()->findAll(array( 'condition' => "cargoId=:match", 'params' => array(':match' =>(int)Yii::app()->user->getState('cargo_id'))));

$case = ''; // para busqueda por defecto

$rol = Yii::app()->user->getState('roles');
?>
<div class="container">
	<div class="row">
        <h1 class="tl_seccion">Reportes Call Center</h1>
    </div>

	<div class="row">
	    <!-- FORMULARIO DE BUSQUEDA -->
	    <div class="col-md-12">
	        <div class="highlight">
                <div class="form row">
                	<?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'codigo-naturaleza-form',
                        'method' => 'get',
                        'action' => Yii::app()->createUrl('cquestionario/search/'),
                        'enableAjaxValidation' => true,
                        'htmlOptions' => array('class' => 'form-horizontal form-search')
                            ));
                    ?>
                    <h4>Filtrar por:</h4>
                    
                    <label class="col-sm-2 control-label" for="Casos_estado">Campañas</label>
                    <div class="col-md-4">
                        <input size="10" maxlength="10" value="" class="form-control" name="Modelos[Descripcion]" id="Modelos_descripcion" type="text">
					</div>
                    
                    <label class="col-sm-2 control-label" for="Casos_estado">Encuestas</label>
                    <div class="col-md-4">
                        <input size="10" maxlength="10" value="" class="form-control" name="Modelos[Descripcion]" id="Modelos_descripcion" type="text">
					</div>

					<div class="row buttons">
                        <input class="btn btn-danger" type="submit" name="yt0" value="Buscar">
                    </div>
                </div>
                
				
				<?php	foreach(Yii::app()->user->getFlashes() as $key => $message) {
					echo '<div class=" row flash-' . $key . '">' . $message . "</div>\n";
				}?>
                
                <?php $this->endWidget(); ?>
            </div>
        </div>
		
    </div>
		
</div>
<div class="container">
	<div class="row">
		<?php 
		$ttEncuestados=0;
		$ttNEncuestados = 0;
		$ttPendientes = 0;
		$tt = null;
		$tts = null;
		if(!empty($campana)){
			foreach ($campana as $key) {
				echo '<h4>Campaña: '.$key->descripcion.'</h4>';
				if(!empty($model)){
					$tabla=0;
					foreach ($model as $value) {
						if($key->id == $value->ccampana_id){
							echo '<div class="col-md-6">';
							echo '<h5>'.strtoupper(strtolower($value->descripcion)).' exprira el '.$value->fechafin.'</h5>';
							
							echo '<table><thead><th>Detalle</th><th>Valor</th></thead><tbody>';
							$totalEncuestados = Cencuestados::model()->count(array('condition'=>'cquestionario_id = '.$value->id));
							echo '<tr><td>Total de Encuestados</td><td>'.$totalEncuestados.'</td></tr>';
							//ENCUESTAS REALIZADAS
							$totalRealizadas = Cencuestadoscquestionario::model()->count(array('condition'=>'estado = "COMPLETADO" && cquestionario_id = '.$value->id));
							echo '<tr><td>Encuestas Completadas</td><td>'.$totalRealizadas.'</td></tr>';
							//ENCUESTADOS CANCELADOS
							$totalNoRealizadas = Cencuestadoscquestionario::model()->count(array('condition'=>'estado = "CANCELADA" && cquestionario_id = '.$value->id));
							echo '<tr><td>Encuestas no Deseadas/Canceladas</td><td>'.$totalNoRealizadas.'</td></tr>';
							echo '<tr><td>Encuestas Pendientes</td><td>'.Cencuestadoscquestionario::model()->count(array('condition'=>'estado = "PENDIENTE" && cquestionario_id = '.$value->id)).'</td></tr>';
							
							echo '</tbody></table></div>';
							$tt .=strtoupper(strtolower($value->descripcion)).'|'.$totalEncuestados.'|'.$totalRealizadas.'|'.$totalNoRealizadas.'--';
							$tts .='{ label: "'.strtoupper(strtolower($value->descripcion)).'", y: '.$totalEncuestados.' },';

						}
					}
					
				}
			}
		} ?>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div style="width: 100%">
				<div id="chartContainer" style="height: 300px; width: 100%;">
			</div>
		</div>
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
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/canvasjs.min.js"></script>
  <?php 
  echo '
	<script type="text/javascript">
  window.onload = function () {
    var chart = new CanvasJS.Chart("chartContainer", {

      title:{
        text: "Total de Personas a encuestar"              
      },
      data: [//array of dataSeries              
        { //dataSeries object

         /*** Change type "column" to "bar", "area", "line" or "pie"***/
         type: "column",
         dataPoints: [
         	'.$tts.'
         ]
       }
       ]
     });

    chart.render();
  }
  </script>';
