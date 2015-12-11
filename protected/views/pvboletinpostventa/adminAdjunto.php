<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilos.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/css/ui.daterangepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.min.js"></script>
<?php
/* @var $this CasosController */
/* @var $model Casos */
$accesosUser = Permiso::model()->findAll(array( 'condition' => "cargoId=:match", 'params' => array(':match' =>(int)Yii::app()->user->getState('cargo_id'))));

$this->breadcrumbs = array(
    'Casoses' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'List Casos', 'url' => array('index')),
    array('label' => 'Manage Casos', 'url' => array('admin')),
);
?>
<script type="text/javascript">
	 $(function() {
        $("#keywords").tablesorter();
	 });
</script>
<style>
.row.buttons {
    margin-top: -14px;
}
</style>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1 class="tl_seccion">Archivos adjuntos al bolent&iacute;n</h1>
			 <div class="col-md-12">
				<div class="row">
				<!-- FORMULARIO DE BUSQUEDA -->
				<div class="col-md-12">
					<div class="highlight">
						<div class="form">
							<h4>Filtrar por:</h4>
							<?php
							$form = $this->beginWidget('CActiveForm', array(
								'id' => 'modelosposventa-form',
								'method' => 'get',
								'action' => Yii::app()->createUrl('pvboletinpostventa/searchAdjunto/'.$boletin->id),
								'enableAjaxValidation' => true,
								'htmlOptions' => array('class' => 'form-horizontal form-search')
									));
							?>
							<label class="col-sm-4 control-label" for="Casos_estado">Nombre del archivo</label>
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
				<div class="table-responsive">
					<table class="tables tablesorter cabeceraTabla" >
						<thead>
							<tr>
								<th><span>ID</span></th>
								<th><span>Titular</span></th>
								<th><span>C&oacute;digo</span></th>
								<th><span>Fecha</span></th>
							</tr>
						</thead>
						<tbody>
							<tr>
									<td><?php echo $boletin->id; ?> </td>
									<td><?php echo $boletin->titulo ?> </td>
									<td><?php echo $boletin->codigo ?> </td>
									<td><?php echo $boletin->fecha ?> </td>
									
							</tr>
						</tbody>
					</table>
				</div>

			</div>
			
			<div>
			<?php 
			if(!empty($accesosUser)){
				foreach($accesosUser as $a){
					if( ($a->accesoSistema->controlador) == 'pvboletinpostventa' &&  ($a->accesoSistema->accion) ==  'adjuntar'){?>	
						<a href="<?php echo Yii::app()->createUrl('pvboletinpostventa/adjuntar/'.$boletin->id); ?>" class="btn btn-primary btn-xs btn-danger btnCrear">Adjuntar Bolet&iacute;n</a>
				<?php }
					}
				}?>
			</div>
			<?php if(!empty($model)){?>
			<div class="row">
				<div class="table-responsive">
					<table class="tables tablesorter" id="keywords">
						<thead>
							<tr>
								<th><span>ID</span></th>
								<th><span>Detalle</span></th>
								<th><span>Nombre</span></th>
								<th colspan="3"><span>Opciones</span></th>
								
							</tr>
						</thead>
						<tbody>
						<?php foreach($model as $boletn){?>
							<tr>
								
									<td><?php echo $boletn->id; ?> </td>
									<td><?php echo $boletn->detalle; ?></td>
									<td><a href="<?php echo Yii::app()->request->baseUrl; ?>/upload/boletines/<?php echo $boletn->nombre ?>" target="_blank"><?php echo $boletn->nombre ?></a></td>
									<?php 
								if(!empty($accesosUser)){
									foreach($accesosUser as $a){
									?>
									<?php if( ($a->accesoSistema->controlador) == 'pvboletinpostventa' &&  ($a->accesoSistema->accion) ==  'updateAdjunto'){?>	
										<td><a href="<?php echo Yii::app()->createUrl('pvboletinpostventa/updateAdjunto', array('idA' => $boletn->id,'id' => $boletn->boletinpostventa_id)); ?>" class="btn btn-primary btn-xs btn-danger">Editar</a></td>
									<?php } ?>
								
									<?php if( ($a->accesoSistema->controlador) == 'pvboletinpostventa' &&  ($a->accesoSistema->accion) ==  'eliminarAdjunto'){?>	                           
											<td><a href="<?php echo Yii::app()->createUrl('pvboletinpostventa/eliminarAdjunto', array('idA' => $boletn->id,'id' => $boletn->boletinpostventa_id)); ?>" onclick="return confirm('&iquest;Esta seguro que desea eliminar este adjunto?')" class="btn btn-primary btn-xs btn-danger">Eliminar</a></td>
									<?php } ?>
								
									<?php if( ($a->accesoSistema->controlador) == 'pvboletinpostventa' &&  ($a->accesoSistema->accion) ==  'verAdjunto'){?>	                           
											<td><a href="<?php echo Yii::app()->createUrl('pvboletinpostventa/verAdjunto', array('id' => $boletn->id)); ?>" class="btn btn-primary btn-xs btn-danger">Ver</a></td>
									<?php } 
								
										}
									}
								?>
							</tr>
						<?php } ?>
						</tbody>
					</table>
				</div>

			</div>
			<?php }else
					echo 'No existen documentos adjuntos para este bolet&iacute;n.';
			?>
					
			</div>
        </div>
        <div class="col-md-3 col-md-offset-1 cont_der">
           
            <p class="border-bt">Tambi&eacute;n puedes ir a:</p>
            <ul>
                <li><a href="<?php echo Yii::app()->createUrl('pvboletinpostventa/admin'); ?>" class="seguimiento-btn">Volver a Boletines Posventa</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></li>
				<li><a href="javascript:history.back(1)" class="back-btn-go">Volver</a></li>
            </ul>
        </div>
    </div>
</div>