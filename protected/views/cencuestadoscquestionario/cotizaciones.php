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
        <h1 class="tl_seccion">Cotización Web</h1>
    </div>
    <?php 
    	if(!empty($datos)){

    ?>
	<div class="col-md-12">
	
	<div style="background:#fff;width:100%;padding:5px;margin:10px auto;">
		<h4>Datos Informativos de la persona</h4>
		<hr>
		<ul>
			<li><label style="width: 80px;font-weight:bold;padding: 0px;font-size:13px">Oficina:</label> <?php echo $datos->trabajo?></li>
			<li><label style="width: 80px;font-weight:bold;padding: 0px;font-size:13px">Teléfono:</label> <?php echo $datos->telefono?></li>
			<li><label style="width: 80px;font-weight:bold;padding: 0px;font-size:13px">Celular:</label> <?php echo $datos->celular?></li>
		</ul>
	</div>
    <p>Buenos días me  comunica por favor con el Sr./a <b><?php echo utf8_decode($datos['nombre'])?></b>, mucho gusto Sr/a <b><?php echo utf8_decode($datos['nombre'])?></b>., le saludo de KIA MOTORS, mi nombre es <b><?php echo Yii::app()->user->getState('first_name'); ?></b>.  el motivo de mi llamada es validar la información que ingresó en nuestra página WEB para enviarle la cotización solicitada con mayor efectividad.</p>
    <p>¿Me permite un minuto de su tiempo?:</p>
    	<div class="radio">
	        <label>
	          <input type="radio" name="optionsRadios" id="optionsRadios1" value="NO" onclick="registrarnoencuesta(<?php echo $datos['id']?>)">
	          <h5>No estoy interesado en continuar la encuesta.</h5>
	        </label>
      	</div>
      	<div class="radio">
	        <label>
	          <input type="radio" name="optionsRadios" id="optionsRadios2" value="SI" onclick="cotizarencuesta()">
	          <h5>Si estoy interesado en continuar la encuesta.</h5>
	        </label>
      	</div>
		<div class="col-md-12" id="contenido__formulario__actualizar" style="display:none">
			<h4><b>Confirma  la información del cliente</b></h4>
			<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'cpregunta-form',
			'htmlOptions' => array('class' => 'form-horizontal')
			));

			?>

	<?php echo $form->errorSummary($informacion); ?>
			<div class="form-group">
				<label class = 'col-sm-2 control-label'>Nombres:</label>
				<div class="col-sm-4">
					<input type="text" required value="<?php echo utf8_decode($datos['nombre']) ?>" name="data[nombre]" id="data_nombre" class = 'form-control'>
				</div>
				<label class = 'col-sm-2 control-label'>Apellidos:</label>
				<div class="col-sm-4">
					<input type="text" required value="<?php echo utf8_decode($datos['apellido']) ?>"  name="data[apellido]" id="data_apellido" class = 'form-control'>
				</div>
			</div>
			<div class="form-group">
				<label class = 'col-sm-2 control-label'>Cédula:</label>
				<div class="col-sm-4">
					<input type="text" required value="<?php echo $datos['cedula'] ?>"  name="data[cedula]" id="data_cedula" class = 'form-control'>
				</div>
				<label class = 'col-sm-2 control-label'>Dirección:</label>
				<div class="col-sm-4">
					<input type="text" required value="<?php echo $datos['direccion'] ?>"  name="data[direccion]" id="data_direccion" class = 'form-control'>
				</div>
			</div>
			<div class="form-group">
				<label class = 'col-sm-2 control-label'>Provincia Domicilio:</label>
				<div class="col-sm-4">
					<?php 
						$pr = Provincias::model()->findAll();
						if(!empty($pr)){
							echo '<select required   name="data[provincia]" id="data_provincia" class = "form-control">';
							foreach($pr as $p){
								echo '<option value="'.$p->nombre.'">'.$p->nombre.'</option>';
							}
							echo '</select>';
						}
					?>
					
				</div>
				<label class = 'col-sm-2 control-label'>Ciudad Domicilio:</label>
				<div class="col-sm-4">
					<input type="text" required value=""  name="data[ciudaddo]" id="data_ciudaddo" class = 'form-control'>
				</div>
			</div>
			<div class="form-group">
				<label class = 'col-sm-2 control-label'>Teléfono Trabajo:</label>
				<div class="col-sm-4">
					<input type="text" required maxlength="9" onkeypress="return numeros(event);" value="<?php echo $datos['telefono'] ?>"  name="data[telefono]" id="data_telefono" class = 'form-control'>
				</div>
				<label class = 'col-sm-2 control-label'>Celular:</label>
				<div class="col-sm-4">
					<input type="text" required maxlength="10" onkeypress="return numeros(event);" value="<?php echo '09'.$datos['celular'] ?>"  name="data[celular]" id="data_celular" class = 'form-control'>
				</div>
			</div>
			<div class="form-group">
				<label class = 'col-sm-2 control-label'>Teléfono Domicilio:</label>
				<div class="col-sm-4">
					<input type="text" required value="" maxlength="9" onkeypress="return numeros(event);" name="data[telefonocasa]" id="data_telefonocasa" class = 'form-control'>
				</div>
			
				<label class = 'col-sm-2 control-label'>Email:</label>
				<div class="col-sm-4">
					<input type="text" required value="<?php echo $datos['email'] ?>"  name="data[email]" id="data_email" class = 'form-control'>
				</div>
			</div>
			<div class="form-group">
				<label class = 'col-sm-2 control-label'>Modelo:</label>
				<div class="col-sm-4">
					<?php
						//traer modelos
						$sql = 'SELECT * FROM modelos';
			        	$modelos = Yii::app()->db2->createCommand($sql)->queryAll();
			        	if(!empty($modelos)){
			        		echo '<select name="data[modelo]" id="data_modelo" class = "form-control" onchange="traerversion(this.value)">';
			        		$s ='';
			        		foreach($modelos as $m){
			        			if($m['id_modelos'] == $datos['modelo_id'])
			        				$s = 'selected';
			        			else
			        				$s='';

			        			echo '<option value="'.$m["id_modelos"].'" '.$s.'>'.$m["nombre_modelo"].'</option>';
			        		}
			        		echo '</select>';
			        	}
					?>
				</div>
				<label class = 'col-sm-2 control-label'>Versión:</label>
				<div class="col-sm-4" id="div_versiones">
					<input type="text" name="data[version]" id="data_version" class = 'form-control'>
				</div>
			</div>
			<div class="form-group">
				<label class = 'col-sm-2 control-label'>Ciudad:</label>
				<div class="col-sm-4">
					<?php
						//traer modelos
						$sql = 'SELECT * FROM dealercities';
			        	$modelos = Yii::app()->db2->createCommand($sql)->queryAll();
			        	if(!empty($modelos)){
			        		echo '<select name="data[ciudad]" id="data_ciudad" class = "form-control" onchange="traerciudad(this.value)">';
			        		$s ='';
			        		foreach($modelos as $m){
			        			if($m['cityid'] == $datos['ciudadconcesionario_id'])
			        				$s = 'selected';
			        			else
			        				$s='';

			        			echo '<option value="'.$m["cityid"].'" '.$s.'>'.$m["name"].'</option>';
			        		}
			        		echo '</select>';
			        	}
					?>
				</div>
				<label class = 'col-sm-2 control-label'>Concesionario:</label>
				<div class="col-sm-4" id="div_concesionario">
					
				</div>
			</div>
			<input type="hidden" name="data[id_atencion]" id="id_item" value="<?php echo $datos['id'] ?>">
			<input type="submit" value="Actualizar Datos" class="btn btn-danger">
			<?php $this->endWidget(); ?>
		</div>

    </div>
    <?php }else{
    	echo '<p>No existen personas para realizar la encuesta de cotización</p>';
    } ?>
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
<script type="text/javascript">
	$( window ).load(function() {
		traerversion('<?php echo $datos["modelo_id"] ?>');
		traerciudad('<?php echo $datos["ciudadconcesionario_id"] ?>');

	});
	function registrarnoencuesta(vl){
		$('#contenido__formulario__actualizar').hide();
		if(confirm('Esta seguro que no desea realizar la encuesta el cliente?')){
			if(vl>0){
				$.ajax({
					url: '<?php echo Yii::app()->createUrl("/site/cancelarcotizacion")?>',
					type:'POST',
					async:false,
					data:{
						rs : vl,
					},
					success:function(result){
						if(result ==1){
							url_ok = '<?php echo Yii::app()->createUrl("/cencuestadoscquestionario/cotizacionok")?>';
							location.href=url_ok;
						}else{
							alert('Se produjo un error al grabar por favor actualice la página para continuar.');
						}
					}
				});
			}
		}else{
			$('#optionsRadios1').attr('checked',false);
		}
	}
	function cotizarencuesta(){
		$('#contenido__formulario__actualizar').show();
	}
	function traerversion(vl){
		if(vl>0){
			$.ajax({
				url: '<?php echo Yii::app()->createUrl("/site/traerversioncotizacion")?>',
				type:'POST',
				async:false,
				data:{
					rs : '<?php echo $datos["version_id"] ?>',
					vl : vl,
				},
				success:function(result){
					if(result !=0){
						$('#div_versiones').html(result);
					}else{
						//alert('Se produjo un error al recuperar la versión del vehiculo, es posible que ya no esta disponible.');
					}
				}
			});
		}
	}
	function traerciudad(vl){
		if(vl>0){
			$.ajax({
				url: '<?php echo Yii::app()->createUrl("/site/traerciudadcotizacion")?>',
				type:'POST',
				async:false,
				data:{
					rs : '<?php echo $datos["concesionario_id"] ?>',
					vl : vl,
				},
				success:function(result){
					if(result !=0){
						$('#div_concesionario').html(result);
					}else{
						alert('Se produjo un error al recuperar los datos actualice la página por favor.');
					}
				}
			});
		}
	}
	function numeros(evt)
    {
        var code = (evt.which) ? evt.which : evt.keyCode;
        if(code==8)
        {
            //backspace
            return true;
        }
        else if(code>=48 && code<=57)
        {
            //is a number
            return true;
        }
        else
        {
            return false;
        }
    }
    
</script>