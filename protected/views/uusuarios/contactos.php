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
        <h1 class="tl_seccion">Contactos de Usuarios</h1>
    </div>
	<div class="row">
        <!-- FORMULARIO DE BUSQUEDA -->
        <div class="col-md-12">
            <div class="highlight">
                <div class="form">
                    <h4>Filtrar por:</h4>
                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'search-form',
                        'method' => 'get',
                        'action' => Yii::app()->createUrl('uusuarios/searchC'),
                        'enableAjaxValidation' => true,
                        'htmlOptions' => array('class' => 'form-horizontal form-search')
                            ));
                    ?>
					<div class="row">
                        <div class="form-group">
						    <label class="col-sm-2 control-label" for="Casos_estado">Nombres</label>
                            <div class="col-sm-4">
                                <input size="80" maxlength="80" value="<?php echo $busqueda;?>" class="form-control" name="Usuarios[nombres]" id="Search_nombres" type="text">
							
                             </div>
                             <label class="col-sm-2 control-label" for="Casos_estado">Apellidos</label>
                            <div class="col-sm-4">
                                <input size="80" maxlength="80" value="<?php echo $apellidos;?>" class="form-control" name="Usuarios[apellidos]" id="Search_nombres" type="text">
                            
                             </div>
                             </div>
						
                         <div class="form-group">
						<label class="col-sm-2 control-label" for="Casos_estado">Correo Electrónico</label>
                        <div class="col-sm-10">
                            <input size="80" maxlength="80" value="<?php echo $email;?>" class="form-control" name="Usuarios[email]" id="Search_email" type="text">
							
                        </div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
                            <label class="col-sm-2 control-label" for="Cargo_area_id">Ubicaci&oacute;n</label>
                            <div class="col-sm-4">
                                <?php 
                                        echo '<select class="form-control" onchange="traerArea(this.value)">';
                                        echo '<option value="0">--Seleccione la Ubicaci&oacute;n--</option>';
                                        echo '<option value="1">AEKIA</option>';
                                        echo '<option value="2">CONCESIONARIO</option>';
                                        echo '</select>';
                                ?> 
                                
                            </div>
                            <label class = 'col-sm-2 control-label'>&Aacute;rea</label>
                            <div class="col-sm-4">
                                <div id="dArea"> <p style="font-size:13px;font-weight:bold;padding-top:5px"><< Seleccione una Ubicaci&oacute;n</p></div>
                                
                            </div>
                            
                        </div>
                        <div class="form-group">
                            <label class = 'col-sm-2 control-label'>Cargo</label>
                            <div class="col-sm-4">
                                <?php //echo $form->dropDownList($model,'cargo_id', CHtml::listData(Cargo::model()->findAll(), 'id', 'descripcion'), array('empty'=>'Seleccione >>','class'=>'form-control')) ?> 
                                <div id="ccargos"><h6>Primero seleccione un &aacute;rea por favor</h6></div>
                                
                            </div>
                        
                            <label class = 'col-sm-2 control-label'>Grupo Concesionarios</label>
                            <div class="col-sm-4">
                                <!--<select class = 'form-control' name="Usuarios[grupo_id]" id="Usuarios_grupo_id" onchange="buscarConcesionario(this.value)">-->
                                <select class = 'form-control' name="Usuarios[grupo_id]" id="Usuarios_grupo_id">
                                    <option> -- Seleccione -- </option>
                                    <?php
                                        $grupo = GrGrupo::model()->findAll();
                                        if(!empty($grupo)){
                                            foreach($grupo as $c){
                                                echo '<option value="'.$c->id.'">'.$c->nombre_grupo.'</option>';
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                            <!--<label class = 'col-sm-2 control-label'>Concesionarios</label>
                            <div class="col-sm-4">
                                <div id="concesionarioscbo"><h6><< Primero seleccione un grupo de concesionario</h6></div>
                                
                            </div>-->
                            
                        </div>
					</div>
					<div class="row">
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
	<?php if(!empty($model)){?>
    <div class="row">
        <div class="table-responsive">
		<div>
			<a href="<?php echo Yii::app()->createUrl('uusuarios/celebraciones'); ?>" class="btn btn-primary btn-xs btn-danger btnCrear">Ver Cumplea&ntilde;eros del mes</a>
			
		</div>
		<div class="input-group"> <span class="input-group-addon">Filtrar por Apellidos y Nombres</span>
			<input id="filter" type="text" class="form-control" placeholder="Ingrese aqu&iacute;">
		</div>
            <table class="tables tablesorter" id="keywords">
                <thead>
                    <tr>
                        <th><span>ID</span></th>
                        <th><span>&aacute;rea</span></th>
                        <th><span>Cargo</span></th>
						<th><span>Nombres</span></th>
						<th><span>Apellidos</span></th>
						<th><span>Grupo</span></th>
                        <th><span>Concesionario</span></th>
                        <th><span>Tel&eacute;fono</span></th>
                        <th><span>Extensi&oacute;n</span></th>
                        <th><span>Email</span></th>
						<th><span>Celular</span></th>
                        
                    </tr>
                </thead>
                <tbody class="searchable">
                    <?php
//$model = Casos::model()->findAll();
					$cont=0;
                    foreach ($model as $c):
                        $cont++;
						?>
                        <tr>
                            <td><?php echo $cont;/*$c->id;*/ ?> </td>
                            <td><?php echo strtoupper($c->cargo->area->descripcion) ?> </td>
                            <td><?php echo strtoupper($c->cargo->descripcion) ?> </td>
                            <td><?php 
							$cumple = explode('-',$c->fechanacimiento);
                            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
							if($cumple[1].$cumple[2] == date("md")){
								echo '<img src="'.Yii::app()->request->baseUrl.'/images/usuarios/cumple.png" width="16">&nbsp;';
							}
							echo $c->nombres ?> </td>
                            <td><?php echo $c->apellido ?> </td>
                            <td><?php echo (!empty($c->grupo_id))?$c->grupo->nombre_grupo:'--'; ?> </td>
                            <td><?php 
							//echo $c->concesionario_id;
							//if(!empty($c->concesionario_id))
								//echo $c->concesionario_id;
								echo ($c->concesionario_id>=1)?$this->traerConcesionariosGR($c->concesionario_id,1):$this->traerConcesionariosU($c->id,1);
							//else
								//echo '--';
							?> </td>
                            <td><a href="tel://<?php echo $c->telefono ?>"><?php echo $c->telefono ?></a> </td>
                            <td><?php echo $c->extension ?> </td>
                            <td><?php echo $c->correo ?> </td>
							<td><a href="tel://<?php echo $c->celular ?>"><?php echo $c->celular ?></a></td>
                            
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
	<?php }else{
		echo '<p>No hay datos para mostrar</p>';
	} ?>
</div>
<script>
	$(function(){
		$("#Search_cargo").val('<?php echo $cargos ?>');
		$("#Usuarios_grupo_id").val('<?php echo $concesionarioss ?>');
		
	});
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
    function buscarDealer(vl){
        //concesionarioscbo
        $.ajax({
            url: '<?php echo Yii::app()->createUrl("site/traerconsesionario")?>',
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
    function buscarConcesionario(vl){
        //concesionarioscbo
        $.ajax({
            url: '<?php echo Yii::app()->createUrl("site/traerconsesionario")?>',
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
    function buscarCargo(vl){
        //concesionarioscbo
        $.ajax({
            url: '<?php echo Yii::app()->createUrl("site/traercargos")?>',
            type:'POST',
            async:true,
            data:{
                rs : vl,
            },
            success:function(result){
                if(result == 0){
                    alert("No se pudo realizar la consulta de cargos.");
                    $("#ccargos").html("<p style='font-size:13px;font-weight:bold;padding-top:5px;'>Seleccione un &aacute;rea.</p>");
                }else{
                    //alert(result)
                    $("#ccargos").html(result);
                }
            }
        });
    }



    function traerArea(vl){
        if(vl>0){
            $.ajax({
                url: '<?php echo Yii::app()->createUrl("/site/traerArea")?>',
                type:'POST',
                async:false,
                data:{
                    rs : vl,
                },
                success:function(result){
                    if(result != 0){
                        $("#dArea").html(result);
                        buscarCargo($("#Cargo_area_id").val());
                    }else{
                         $("#dArea").html('Seleccione una Ubicacion por favor.');
                    }
                }
            });
        }
    }
    function verciudadcon(vl){
        $("#descripcionUbicaciones").hide();
        if(vl>0){
            $.ajax({
                url: '<?php echo Yii::app()->createUrl("/site/traerciudadcon")?>',
                type:'POST',
                async:false,
                data:{
                    rs : vl,
                },
                success:function(result){
                    if(result != 0){
                        $("#descripcionUbicaciones").html(result);
                        $("#descripcionUbicaciones").show();
                        if($('#Usuarios_concesionario_id :selected').length >1){
                                    $('#pppc').html('--');
                            }
                    }else{
                         $("#descripcionUbicaciones").html('Se produjo un error vuelva a cargar el sitio e intente nuevamente.');
                    }
                }
            });
        }
    }
</script>