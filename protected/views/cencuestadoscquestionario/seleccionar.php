<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilosCall.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilos.css" type="text/css" />


<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/css/custom-theme/jquery-ui-1.10.0.custom.css" type="text/css" />
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
.excel{
	border: 1px dashed #ccc;
	padding: 10px;
	margin:auto;
	width: 100%;
}
.padd-all{
	padding: 15px;
}
.total__personas{
	font-size: 16px;
}
.filtros{
	border: 1px dashed #ccc;
	width: 66%;
}
hr{
	border: 1px solid;
}
</style>

<div class="container">
    <div class="row">
        <h1 class="tl_seccion">Personas para la encuesta: <?php echo strtoupper($questionario->descripcion); ?></h1>
    </div>


	<div class="row">
        <div class="col-md-8">
		 <?php 
			if (Yii::app()->user->hasFlash('success')){ ?>
                <div class="info">
                    <?php echo Yii::app()->user->getFlash('success'); ?>
					<br>
					<div class="row text-center"><a href="<?php echo Yii::app()->createUrl('cencuestadoscquestionario/generar/'.$id); ?>"><input type="button" value="Continuar" class="btn btn-danger"></a></div>
                </div>
		 <?php 
			}else{ 

					echo '<div class="container"><h3>Base de datos seleccionada: '.$questionario->cbasedatos->nombre.'</h3></div>';
					echo '<section class="container pad-all">';
				?>
				<p class="total__personas">Total de usuarios registrados en esta base: <b id="totalPersonas"><?php echo ($questionario->cbasedatos_id >2) ?  $totalUBD[0]['total']: count($totalUBD);  ?></b> personas.</p>
				<div class="">
					<div class="row container filtros pad-all">
						<?php $form=$this->beginWidget('CActiveForm', array(
						'id'=>'filtarBase',
						'enableAjaxValidation' => true,
							'clientOptions' => array(
							'validateOnSubmit'=>false,
							'validateOnChange'=>false,
							'validateOnType'=>false,
							 ),
							'htmlOptions' => array('class' => 'form-horizontal','enctype' => 'multipart/form-data',)
						));
						?>
						<h5>Filtrar Usuarios de la base de datos</h5>
						<div class="col-sm-3">
							<select name="Usuarios[ciudad]" id="usuarios_ciudad" class = 'form-control cccc' onchange="buscarDealer(this.value)">
							<option value='0'>Ciudad>></option>
							<?php
								$ciudades = Dealercities::model()->findAll();
								if(!empty($ciudades)){
									foreach($ciudades as $c){
										echo '<option value="'.$c->id.'">'.$c->name.'</option>';
									}
								}
							?>
							</select>
						</div>
						<div class="col-sm-3">
							<div id="concesionarioscbo"><select name="Usuarios[dealers_id]" id="Usuarios_dealers_id" class="form-control cccc"><option>Concesionarios</option></select></div>
						</div>
						<div class="col-sm-3">
							<input size="60" maxlength="45" readonly="readonly" placeholder="Fecha desde" class="form-control datepicker cccc" name="Usuarios[desde]" id="Usuarios_desde" type="text">
						</div>
						<div class="col-sm-3">
							<input size="60" maxlength="45" readonly="readonly" placeholder="Fecha hasta" class="form-control datepicker cccc" name="Usuarios[hasta]" id="Usuarios_hasta" type="text">
						</div>

						<div class="row buttons col-sm-8 pad-all">
							<p id="pp" style="display:none">Buscando...</p>
							<br>
							<input type=submit value="Seleccionar" id="btnSeleccion" class='btn btn-danger '>
						</div>
						<?php $this->endWidget(); ?>
					</div>
				</div>
				<?php
					echo '</section>';?>
				<hr>
			<div class="row excel">
				<div class="container">
					<h3>Tambien puede cargar personas desde un excel <a target="_blank" href="<?php echo Yii::app()->request->baseUrl; ?>/docs/Encuestados.xlsx"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/callcenter/export_excel.png" title="Descargar formato de carga de usuarios"></a></h3>
				</div>
				
			
				<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'cargarasesores',
				'enableAjaxValidation' => true,
					'clientOptions' => array(
					'validateOnSubmit'=>false,
					'validateOnChange'=>false,
					'validateOnType'=>false,
					 ),
					'htmlOptions' => array('class' => 'form-horizontal','enctype' => 'multipart/form-data',)
				));
				?>
				<div class="form-group row ">
					<label class = 'col-sm-2 control-label' style="text-align:left">Seleccione Excel:</label>
					<div class="col-sm-4">
						<?php echo CHtml::activeFileField($model, 'upload_file',array("class"=>"form-control subir")); ?>
						<?php echo $form->error($model,'upload_file'); ?>
					</div>
					
				
					<label class = 'col-sm-2 control-label' style="text-align:left">Eliminar anteriores:</label>
					<div class="col-sm-1">
						<select id="eliminar" name="eliminar">
							<option value="NO">NO</option>
							<option value="SI">SI</option>
						</select>
					</div>
				</div>
				<div class="row col-md-8">
					<?php	foreach(Yii::app()->user->getFlashes() as $key => $message) {
						echo '<div class=" row flash-' . $key . '">' . $message . "</div>\n";
					}?>
				</div>    
				<div class="row buttons col-sm-8">
						<input type=submit value="Enviar y Usar" class='btn btn-danger'>
				</div>
			<?php $this->endWidget(); ?>
			</div>
		<?php 
			} ?>
        </div>
		  <div class="col-md-3 col-md-offset-1 cont_der">
            <p class="border-bt">Tambi&eacute;n puedes ir a:</p>
            <ul>
                <li><a href="<?php echo Yii::app()->createUrl('cencuestadoscquestionario/generar/'.$id); ?>" class="seguimiento-btn">Administrador de Encuestados</a></li>
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
<script type="text/javascript">
$(function(){
	 $(".datepicker").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: new Date(1950, 10 - 1, 25),
            yearRange: '1970:2016'
        });
	
	$(".cccc").change(function() {
        validarPersonas();
    });//
	
});
function buscarDealer(vl){
		//concesionarioscbo
		$.ajax({
			url: '<?php echo Yii::app()->createUrl("site/traerconsesionarioE")?>',
			type:'POST',
			async:true,
			data:{
				rs : vl,
			},
			success:function(result){
				if(result == 0){
					alert("No se pudo realizar la consulta de concesionarios.");
					
				}else{
					$("#concesionarioscbo").html(result);
				}
			}
		});
	}
	
    function validarPersonas(){
    	$('#pp').show();
    		$.ajax({
				url: '<?php echo Yii::app()->createUrl("site/consultarUsuarioEncuesta")?>',
				type:'POST',
				async:true,
				data:{
					rs : " <?php echo $questionario->cbasedatos_id ?>",
					ciudad : $("#usuarios_ciudad").val(),
					concesionario : $("#Usuarios_dealers_id").val(),
					desde : $("#Usuarios_desde").val(),
					hasta : $("#Usuarios_hasta").val(),

				},
				success:function(result){
					$('#pp').hide();
					if(result >0){
						$("#btnSeleccion").attr('disabled',false);
					}
					else
						$("#btnSeleccion").attr('disabled',true);	
					
					$("#totalPersonas").html(result);
					
				}
			});
    	

    }
</script>
