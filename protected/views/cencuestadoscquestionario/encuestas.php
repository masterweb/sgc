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
        <?php $nombre = Cquestionario::model()->findByPk($id); ?>
        <h1 class="tl_seccion">Encuestados de: <?php echo $nombre->nombre; ?></h1>
    </div>
	<div class="row">
        <!-- FORMULARIO DE BUSQUEDA -->
        <div class="col-md-8">
            <div class="highlight">
                <div class="form">
                    <h4>Filtrar por:</h4>
                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'codigo-naturaleza-form',
                        'method' => 'get',
                        'action' => Yii::app()->createUrl('cencuestadoscquestionario/encuestas/'.$id),
                        'enableAjaxValidation' => true,
                        'htmlOptions' => array('class' => 'form-horizontal form-search')
                            ));
                    ?>
                    <label class="col-sm-2 control-label" for="Casos_estado">Descripci&oacute;n</label>
                        <div class="col-md-6">
                            <input size="10" maxlength="10" value="<?php echo $busqueda;?>" class="form-control" name="Modelos[Descripcion]" id="Modelos_descripcion" type="text">
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
    <div class="row">
        <div class="table-responsive col-md-12">
            <div class="input-group"> <span class="input-group-addon">Filtrar por Apellidos y Nombres</span>
                <input id="filter" type="text" class="form-control" placeholder="Ingrese aqu&iacute;">
            </div>
            <table class="tables tablesorter" id="keywords">
                <thead>
                    <tr>
                        <th><span>ID</span></th>
                        <th><span>Nombre</span></th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Estado</th>
                        <th colspan="5">Opciones</th>
                    </tr>
                </thead>
                <tbody class="searchable">
                    <?php
//$model = Casos::model()->findAll();
                   if(!empty($model)){
                    $cont =0;
                    foreach ($model as $c){
                    	$cont++;
						if(!empty($c->cencuestados->nombre)){
                        ?>
                        <tr>
                            <td><?php echo $cont; ?> </td>
                            <td><?php echo $c->cencuestados->nombre ?> </td>
                            <td><?php echo $c->tiempoinicio?> </td>
                            <td><?php echo $c->tiempofinal?> </td>
                            <td><?php echo $c->estado?> </td>
                            <?php 
								if(!empty($accesosUser)){
									foreach($accesosUser as $a){
								 if( ($a->accesoSistema->controlador) == 'cencuestadoscquestionario' &&  ($a->accesoSistema->accion) ==  'encuestas'){?>	
									
                                    <?php if( $c->estado == 'COMPLETADO'){?>
                                    <td>
                                        <a href="#" class="btn  btn-xs btn-success">Encuesta Completada</a>
                                    </td>
                                    <?php } ?>
                                    <?php if( $c->estado == 'PENDIENTE'){?>
                                    <td>
                                        <a href="<?php echo Yii::app()->createUrl('cencuestadoscquestionario/encuestar', array('id' => $c->id)); ?>" class="btn btn-primary btn-xs btn-danger">Iniciar Encuesta</a>
                                    </td>
                                    <?php } ?>
                                    <?php if( $c->estado == 'CANCELADA'){?>
                                    <td>
                                        <a href="#" class="btn  btn-xs btn-warning">Encuesta Cancelada</a>
                                    </td>
                                    <?php } ?>
                                    <?php if( $c->estado == 'EN PROCESO'){?>
                                    <td>
                                        <a href="<?php echo Yii::app()->createUrl('cencuestadoscquestionario/encuestar', array('id' => $c->id)); ?>" class="btn btn-xs btn-info">Encuesta en proceso</a>
                                    </td>
                                    <?php } ?>
							<?php 
									} 
							
								}
							}
							?>
                        </tr>
                        <?php
						}
                    }
                }
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
</div>
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
</script>