<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilos.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/css/ui.daterangepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.min.js"></script>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

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
.derecha_align{
	padding-left:45px;
}
</style>

<div class="container">
    <div class="row">
        <h1 class="tl_seccion">Asignar Accesos al Cargo: <?php echo strtoupper($rols->descripcion); ?></h1>
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
        <div class="col-md-8">
		 <?php if (Yii::app()->user->hasFlash('success')){ ?>
                <div class="info">
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
		 <?php }else{ ?>
		<?php if(!empty($modulos) && !empty($accesos)){ ?>
            <!--<div class="checkbox" ><label><input  onclick="todos(this)"  type="checkbox" value="1"><span id="chkk" style="font-weight:bold;font-size:13px;padding-top:5px;">SELECCIONAR TODO.</span></label></div>-->
			<div id="accordion">
			
			<?php 
				$state= 0;
				
				$style = "";
				$iid = 0;
				foreach($accesos as $item){
				//	print_r($cargados);
				$class = "";
					if(!empty($cargados)){
						foreach($cargados as $c){
							if($c->accesoSistemaId == $item->id){
								$class="checked='true'";
								break;
							}
						}
						
					}
					//
					if($item->accion == "admin"){
						$pad = '';
						$style = "style='font-weight:bold'";
						$iid = $item->id;
					}else{
						$style = "style='font-weight:normal'";
						$pad = 'derecha_align';
					}
					if($item->modulo_id != $state){
						if($state >0){
							echo '</ul></div>';
						}
						echo '<h3><b>Modulo de '.$item->modulo->descripcion.'</b></h3><div><ul>';
						echo '<div class="checkbox" ><label><input  onclick="todoCHK(this,'.$item->modulo->id.')"  type="checkbox" value="1"><span id="chkk" style="font-weight:bold;font-size:13px;padding-top:5px;">SELECCIONAR TODO.</span></label></div>';
						echo '<li><div  class="checkbox"><label '.$style.'><input id="chhk-'.$iid.'"  style="display:none"  onclick="agregar()" class="ch-'.$item->modulo->id.' chk" '.$class.' type="checkbox" value="'.$item->id.'">'.$item->descricion.'</label></div></li>';
					}else{
						if($pad)
							echo '<li class="'.$pad.'"><div class="checkbox"><label '.$style.' ><input  onclick=" activar(this,'.$iid.')" class="vl-'.$iid.' ch-'.$item->modulo->id.' chk" '.$class.' type="checkbox" value="'.$item->id.'">'.$item->descricion.'</label></div></li>';
						else
							echo '<li class="'.$pad.'"><div class="checkbox"><label '.$style.' ><input id="chhk-'.$iid.'"  style="display:none" onclick="agregar()" class="ch-'.$item->modulo->id.' chk" '.$class.' type="checkbox" value="'.$item->id.'">'.$item->descricion.'</label></div></li>';
					}
					$state = $item->modulo_id;
					
				}
				echo '</ul></div>';
			?>
				
			</div>
			<form id="grabaraccesos" class="form-horizontal" method="post" action="<?php echo Yii::app()->request->baseUrl; ?>/index.php/ucargo/asignar/rol/<?php echo $rols->id?>">
				<input type="hidden" name="rol" id="rol" value="<?php echo $rols->id;?>">
				<input type="hidden" name="accesos" id="accesos" value="">
				<input type="submit" value="Grabar Accesos" class="btn btn-danger">
			</form>
		<?php }else echo '<b>No hay accesos asociados a este perfil.</b>'; ?>
		<?php } ?>
        </div>
		  <div class="col-md-3 col-md-offset-1 cont_der">
            <p class="border-bt">Tambi&eacute;n puedes ir a:</p>
            <ul>
                <li><a href="<?php echo Yii::app()->createUrl('ucargo/admin'); ?>" class="seguimiento-btn">Administrador de Cargos</a></li>
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
 <script>
 function todos(vl){
	 if(vl.checked){
		  $('.chk').attr("checked",true);
		$("#chkk").html("DESELECCIONAR TODO.");
	 }else{
		 $('.chk').attr("checked",false);
		 $("#chkk").html("SELECCIONAR TODO.");
	 }
	 
	agregar();
 }
 function activar(vl,cl){
 	
 	if(vl.checked){
	    $('#chhk-'+cl).attr("checked",true);
	    //break;
	}else{
		//$('#chhk-'+cl).attr("checked",false);
		
		var cont = 0;
		var num = 0;
		$('.vl-'+cl).each(function() {

	       if($(this).prop('checked')){
	       	 cont = 1;
	       	 return false;
	       }else{
	       	 cont = 0;
	       }
	       //num = num+1;
	     });
		if(cont==0){
			$('#chhk-'+cl).attr("checked",false);
		}
	}
	agregar();	
 }

 function todoCHK(vl,cl){
	 if(vl.checked){
		  $('.ch-'+cl).attr("checked",true);
	 }else{
		 $('.ch-'+cl).attr("checked",false);
	 }
	 
	agregar();
 }
$(function() {
	agregar();//verifica y extrae todos los cargados
	$( "#accordion" ).accordion({
		active: false, collapsible: true
	});
});
function agregar(){
	var str = "";
	$('.chk').each(function() {
		str += this.checked ? this.value+"|" : "";
	});
	$("#accesos").val(str);
}
</script>