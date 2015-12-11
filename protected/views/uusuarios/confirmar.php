<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilos.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilos.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/css/ui.daterangepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.min.js"></script>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
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
	//$verifica = Permiso:.model()->findAll(array(''));
	//if(){}
?>
<div class="container">
    <div class="row">
        <div class="col-md-8">
           
            <h1 class="tl_seccion">Confirma el acceso al usuario</h1>

			<?php $this->widget('zii.widgets.CDetailView', array(
				'data'=>$model,
				'attributes'=>array(
					array(
					'label' => 'Ubicaci&oacute;n',
						'value' => ($model->cargo->area->tipo==1)?'AEKIA':'CONCESIONARIO'
					),array(
					'label' => '&Aacute;rea',
						'value' => $model->cargo->area->descripcion
					),array(
					'label' => 'Cargo',
						'value' => Cargo::model()->findByPk($model->cargo_id)->descripcion
					),array(
					'label' => 'Grupo',
						'value' => $model->grupo->nombre_grupo
					),array(
					'label' => 'Concesionario',
						'value' => ($model->concesionario_id>0)?$model->consecionario->nombre:$this->traerConcesionariosU($model->id,1)
					),
					array(
					'label' => 'Direcci&oacute;n',
						'value' => (!empty($model->dealers_id))?Dealers::model()->findByPk($model->dealers_id)->name:'--'
					),
					'apellido',
					'nombres',
					'correo',
					'telefono',
					'extension',
					'celular',
					'usuario',
					'estado',
					
				),
			)); ?>

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
			<div class="row">
			
            <?php if (Yii::app()->user->hasFlash('success')){ ?>
                <div class="info">
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
				<?php }else{
					if($model->estado == "CONFIRMADO"){?>
						<div class="form">
						  <h1 class="tl_seccion">Confirmar o denegar el acceso al usuario</h1>
						<?php $form=$this->beginWidget('CActiveForm', array(
							'id'=>'accesoregistro-form',
							'enableAjaxValidation' => true,
								'clientOptions' => array(
								'validateOnSubmit'=>false,
								'validateOnChange'=>false,
								'validateOnType'=>false,
								 ),
								'htmlOptions' => array('class' => 'form-horizontal')
							));
    ?>


							<?php echo $form->errorSummary($acceso); ?>
							<?php echo $form->hiddenField($acceso,'usuarios_id',array("value"=>$model->id)); ?>
							<div class="form-group">
							<?php echo $form->labelEx($acceso,'estado',array('class' => 'col-sm-3 control-label')); ?>
								<div class="col-sm-8">
									<?php echo $form->dropDownList($acceso,'estado',array("RECHAZADO"=>"RECHAZADO","CONFIRMADO"=>"CONFIRMADO"),array('empty'=>'Seleccione >>','onchange'=>'acitvar(this.value)','class' => 'form-control')); ?>
									<?php echo $form->error($acceso,'estado'); ?>
								</div>
							</div>
							<div class="form-group ver" style="display:none">
								<?php echo $form->labelEx($acceso,'descripcion',array('class' => 'col-sm-3 control-label')); ?>
								<div class="col-sm-8">
									<?php echo $form->textArea($acceso,'descripcion',array('rows'=>6, 'cols'=>50, 'class' => 'form-control','disabled'=>true)); ?>
									<?php echo $form->error($acceso,'descripcion'); ?>
								</div>
								
							</div>

							<div class="row buttons">
								<?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Enviar', array('class' => 'btn btn-danger')); ?>
							</div>

						<?php $this->endWidget(); ?>

						</div><!-- form -->
					<?php }else if($model->estado == "PENDIENTE"){
						echo '<div class="alert alert-danger"><strong>Atenci&oacute;n!</strong> El usuario <b>'.$model->nombres.'</b> a&uacute;n no ha confirmado su registro mediante la validaci&oacute;n de correo electr&oacute;nico.</div>';
					}
				}
				?>
			</div>
        </div>
        <div class="col-md-3 col-md-offset-1 cont_der">
           
            <p class="border-bt">Tambi&eacute;n puedes ir a:</p>
            <ul>
                <li><a href="<?php echo Yii::app()->createUrl('uusuarios/admin'); ?>" class="seguimiento-btn">Administrador de Usuarios</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></li>
            </ul>
        </div>
    </div>
</div>
<script>
	function acitvar(vl){
		if(vl == "RECHAZADO"){
			$("#Accesoregistro_descripcion").attr("disabled",false);
			$(".ver").show();
		}else{
			$("#Accesoregistro_descripcion").attr("disabled",true);
			$(".ver").hide();
		}
	}
</script>
 <script>
$(function() {
	$( "#accordion" ).accordion({
		active: false, collapsible: true
	});
});

</script>

