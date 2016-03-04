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
                        'action' => Yii::app()->createUrl('cquestionario/reportesv/'),
                        'enableAjaxValidation' => true,
                        'htmlOptions' => array('class' => 'form-horizontal form-search')
                            ));
                    ?>  
                    <h4>Filtrar por:</h4>
                    
                    <label class="col-sm-2 control-label" for="Casos_estado">Campa&ntilde;as</label>
                    <div class="col-md-4">
						<?php 
							$campanas = Ccampana::model()->findAll();
							if(!empty($campanas)){
								echo '<select name="cbocampana" id="cbocampana" class="form-control" onchange="obternerencuesta(this.value)">';
								echo '<option value="0"> -- Seleccione la campa&ntilde;a --</option>';
								foreach($campanas as $c){
									echo '<option value="'.$c->id.'">'.$c->nombre.' ['.$c->descripcion.']</option>';
								}
								echo '</select>';
							}
						?>
					</div>
                    
                    <label class="col-sm-2 control-label" for="Casos_estado">Encuestas</label>
                    <div class="col-md-4" id="d_encuestas">
                       
					</div>

                </div>
					<div class="row">
					<div class="col-md-12">
                        <input class="btn btn-danger" type="submit" name="yt0" id="btn" value="Buscar">
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
		 <div class="col-md-12">
		<?php 

			if(!empty($encuestados)){
				echo '<h3 class="text-center" style="background:#fff;width:100%;padding:10px;border:1px solid #ccc;">Detalles de la Encuesta <b>'.strtoupper($model->nombre).'</b></h3>';
				echo '<div class="row" ><div class="col-md-6"><label class="cat">CAMPAÑA: </label>'.$model->ccampana->descripcion.'</div>';
				echo '<div class="col-md-6"><label class="cat">FECHA INICIO: </label>'.$model->fecha.'</div>';
				echo '<div class="col-md-6"><label class="cat">FECHA FINALIZACION: </label>'.$model->fechafin.'</div>';
				$est = ($model->fechafin < date('Y-m-d h:i:s'))?'CERRADA':'ACTIVA';
				echo '<div class="col-md-6"><label class="cat">ESTADO: </label>'.$est.'</div></div>';
				echo '<hr>';
				echo '<div class="input-group"> <span class="input-group-addon">Filtrar </span>
					<input id="filter" type="text" class="form-control" placeholder="Ingrese aqu&iacute; lo que desea buscar">
				</div>';
				echo '<table class="table table-striped">';
				echo '<thead>';
				echo '<tr>';
				echo '<th>Nombre</th>';
				echo '<th>Tel&eacute;fono</th>';
				echo '<th>Celular</th>';
				echo '<th>Email</th>';
				echo '<th>Ciudad</th>';
				echo '<th>Encuestador</th>';
				echo '<th>Realizada</th>';
				echo '<th>Fecha</th>';
				echo '<th>Tiempo</th>';
				echo '<th>Audio</th>';
				echo '<th>Observaciones</th>';
				echo '<th>Encuesta</th>';
				echo '</tr>';
				echo '</thead>';
				echo '<tbody class="searchable">';
				foreach($encuestados as $e){
					echo '<tr>';
					echo '<td>'.$e->nombre.'</td>';
					echo '<td>'.$e->telefono.'</td>';
					echo '<td>'.$e->celular.'</td>';
					echo '<td>'.$e->email.'</td>';
					echo '<td>'.$e->ciudad.'</td>';
					$usuario =$this->getUsuario($e->id,$e->cquestionario_id); 
					$name ='ND';
					if(!empty($usuario))
						$name = $usuario->nombres.' '.$usuario->apellido;

					$q = $this->getEncuesta($e->id,$e->cquestionario_id);
					$realizado = $q->estado;
					$fecha = (!empty($q->tiempofinal))?$q->tiempofinal:'--';
					$tiempo = (!empty($q->tiempo))?$q->tiempo:'--';
					$obs = (!empty($q->observaciones))?$q->observaciones:'';
					$qq = Cencuestadoscquestionario::model()->find(array("condition"=>'cencuestados_id ='.$e->id.' and cquestionario_id='.$e->cquestionario_id));
					echo '<td>'.$name.'</td>';
					echo '<td>'.$realizado.'</td>';
					echo '<td>'.$fecha.'</td>';
					echo '<td>'.$tiempo.'</td>';
					echo '<td><a target="_blank" href="'.Yii::app()->request->baseUrl.'/upload/audio/'.$qq->audio.'">Audio</a></td>';
					echo '<td>'.$obs.'</td>';
					if(!empty($q->tiempo)){
						echo '<td><a href="'.Yii::app()->createUrl('cquestionario/detallereporteusuario/u/'.$e->id.'/q/'.$e->cquestionario_id).'">Ver</a></td>';
					}else{
						echo '<td></td>';
					}
					echo '</tr>';
				}
				echo '</tbody>';
				echo '</table>';
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
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/canvasjs.min.js"></script>
  <script>
  	$(document).ready(function () {
	    (function ($) {
	        $('#filter').keyup(function () {
	            var rex = new RegExp($(this).val(), 'i');
	            $('.searchable tr').hide();
	            $('.searchable tr').filter(function () {
	                return rex.test($(this).text());
	            }).show();
	        })
	    }(jQuery));
	});
	$(function(){

		$("#btn").hide();
		$("#d_encuestas").html("<span style='font-size:13px;font-weight:bold;'> << Seleccione una campa&ntilde;a</span>");
	})
	function obternerencuesta(vl){
		
		$("#d_encuestas").html("<span style='font-size:13px;font-weight:bold;'> << Seleccione una campa&ntilde;a</span>");
		if(vl > 0){
			$.ajax({
				type: 'POST',
				url: '<?php echo Yii::app()->createUrl("/cquestionario/getencuestas") ?>',
				data: {vl: vl},
				success: function (data) {
					if(data){
						$("#btn").show();
						$("#d_encuestas").html(data);
					}else{
						
						$("#d_encuestas").html("<span style='font-size:13px;font-weight:bold;'>No hay encuestas para mostrar.</span>");
					}
				}
			});
		}
	}

  </script> 

  <?php 
  /*echo '
	<script type="text/javascript">
  window.onload = function () {
    var chart = new CanvasJS.Chart("chartContainer", {

      title:{
        text: "Total de Personas a encuestar"              
      },
      data: [//array of dataSeries              
        { //dataSeries object

         /*** Change type "column" to "bar", "area", "line" or "pie"***/
       /*  type: "column",
         dataPoints: [
         	'.$tts.'
         ]
       }
       ]
     });

    chart.render();
  }
  </script>';*/


 