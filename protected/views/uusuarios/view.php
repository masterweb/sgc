<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilos.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilos.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/css/ui.daterangepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.min.js"></script>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
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
<?php
/* @var $this CasosController */
/* @var $model Casos */

$this->breadcrumbs = array(
    'Casoses' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'List Casos', 'url' => array('index')),
    array('label' => 'Manage Casos', 'url' => array('admin')),
);
?>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="info">
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
            <?php else: ?>
            <h1 class="tl_seccion">Ver Usuario</h1>

			<?php $this->widget('zii.widgets.CDetailView', array(
				'data'=>$model,
				'attributes'=>array(
					'id',
					array(
					'label' => 'Cargo',
						'value' => Cargo::model()->findByPk($model->cargo_id)->descripcion
					),
					array(
					'label' => 'Ubicaci&oacute;n',
						'value' => (!empty($model->dealers_id))?Dealers::model()->findByPk($model->dealers_id)->name:'--'
					),
					array(
					'label' => 'Concesionario',
						'value' => ($model->concesionario_id>0)?$model->consecionario->nombre:$this->traerConcesionariosU($model->id,1)
					),
					'cedula',
					'nombres',
					'apellido',
					'correo',
					'telefono',
					'extension',
					'celular',
					'usuario',
					'fecharegistro',
					'fechaactivacion',
					'fechaingreso',
					'ultimaedicion',
					'ultimavisita',
					'estado',
					
				),
			)); ?>
<?php endif; ?>
<br>
			<div class="row">
				<div class="table-responsive">
					<?php if(!empty($modulos) && !empty($accesos)){ ?>
					<div id="accordion">
					<?php 
						$state= 0;
						$style = "";
						foreach($accesos as $item){
						//	print_r($cargados);
						$class = "";
							if(!empty($cargados)){
								foreach($cargados as $c){
									if($c->accesoSistemaId == $item->id){
										if($item->accion == "admin"){
											$style = "style='font-weight:bold'";
										}else
											$style = "style='font-weight:normal'";
										
										if($item->modulo_id != $state){
											if($state >0){
												echo '</ul></div>';
											}
											echo '<h3><b>Modulo de '.$item->modulo->descripcion.'</b></h3><div><ul>';
											echo '<li><div  class="checkbox"><label '.$style.'>'.$item->descricion.'</label></div></li>';
										}else{
											echo '<li><div class="checkbox"><label '.$style.' >'.$item->descricion.'</label></div></li>';
										}
										$state = $item->modulo_id;
										break;
									}
								}
								
							}
							
							
						}
							echo '</ul></div></div>';
							}else echo '<b>No hay accesos asociados a este perfil.</b>';
					?>
				</div>
			</div>
        </div>
        <div class="col-md-3 col-md-offset-1 cont_der">
           
            <p class="border-bt">Tambi&eacute;n puedes ir a:</p>
            <ul>
                <li><a href="<?php echo Yii::app()->createUrl('uusuarios/admin'); ?>" class="seguimiento-btn">Administrador de Usuarios</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></li>
				<li><a href="javascript:history.back(1)" class="back-btn-go">Volver</a></li>
            </ul>
        </div>
    </div>
</div>


 <script>
$(function() {
	$( "#accordion" ).accordion({
		active: false, collapsible: true
	});
});

</script>