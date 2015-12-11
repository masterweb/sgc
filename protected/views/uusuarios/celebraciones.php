<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilos.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/css/ui.daterangepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.min.js"></script>
<?php
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
			<a href="<?php echo Yii::app()->createUrl('uusuarios/contactos'); ?>" class="btn btn-primary btn-xs btn-danger btnCrear">Ver todos los Contactos</a>
			
		</div>
            <table class="tables tablesorter" id="keywords">
                <thead>
                    <tr>
                        <th><span>ID</span></th>
                        <th><span>Cargo</span></th>
						<th><span>Nombres</span></th>
						<th><span>Apellidos</span></th>
						<th><span>Ubicaci&oacute;n</span></th>
                        <th><span>Tel&eacute;fono</span></th>
                        <th><span>Extensi&oacute;n</span></th>
                        <th><span>Email</span></th>
						<th><span>Celular</span></th>
						<th><span>Cumplea&ntilde;os</span></th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php
//$model = Casos::model()->findAll();
					$cont=0;
                    foreach ($model as $c):
                        $cont++;
						?>
                        <tr>
                            <td><?php echo $cont;/*$c->id;*/ ?> </td>
                            <td><?php echo strtoupper($c->cargo->descripcion) ?> </td>
                            <td><?php 
							$cumple = explode('-',$c->fechanacimiento);
                            date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
							if($cumple[1].$cumple[2] == date("md")){
								echo '<img src="'.Yii::app()->request->baseUrl.'/images/usuarios/cumple.png" width="16">&nbsp;';
							}
							echo $c->nombres ?> </td>
                            <td><?php echo $c->apellido ?> </td>
                            <td><?php echo $c->dealer->name ?> </td>
                            <td><?php echo $c->telefono ?> </td>
                            <td><?php echo $c->extension ?> </td>
                            <td><?php echo $c->correo ?> </td>
							<td><?php echo $c->celular ?> </td>
							<td><?php
							$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
 
							setlocale(LC_ALL,"es_ES"); $cumple = explode('-',$c->fechanacimiento); echo $cumple[2].' de '.$meses[date('n')-1]; ?> </td>
                            
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
			<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvQir/admin'); ?>" class="qir-btn"><span class="textoFEnlace">QIR</span></a></div>
			<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvboletinpostventa/admin'); ?>" class="boletines-btn"><span class="textoFEnlace">Boletines</span></a></div>
			<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvvinMotor/admin'); ?>" class="vin-btn"><span class="textoFEnlace">Vin-Motor</span></a></div>
			<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvmodelosposventa/admin'); ?>" class="modelospv-btn"><span class="textoFEnlace">Modelos</span></a></div>
			<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvcodigoCausal/admin'); ?>" class="causal-btn"><span class="textoFEnlace">C&oacute;digo Causal</span></a></div>
			<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvcodigoNaturaleza/admin'); ?>" class="naturaleza-btn"><span class="textoFEnlace">C&oacute;digo Naturaleza</span></a></div>
			<div class="col-md-1"><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></div>
			<li><a href="javascript:history.back(1)" class="back-btn-go">Volver</a></li>

        </div>
    </div>
	<?php }else{
		echo '<p>No hay datos para mostrar</p>';
	} ?>
</div>
<script>
	$(function(){
		$("#Search_cargo").val('<?php echo $cargos ?>');
		$("#Search_concesionario").val('<?php echo $concesionarioss ?>');
		
	});
</script>