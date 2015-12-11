<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilos.css" type="text/css" />
<style>
	.errorSummary{
		display:none;
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
		 <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="info">
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
            <?php else: ?>
            <h1 class="tl_seccion">Adjuntar Archivos al bolent&iacute;n</h1>
			
			 <div class="col-md-12">
				
				<div class="form">
					
					<div class="form">

					<?php $form=$this->beginWidget('CActiveForm', array(
						'id'=>'adjunto-boletin-form',
						'enableAjaxValidation' => false,
						'clientOptions' => array(
						'validateOnSubmit'=>false,
						'validateOnChange'=>false,
						'validateOnType'=>false,
						 ),
						'htmlOptions' => array('class' => 'form-horizontal','enctype' => 'multipart/form-data',)
							));
					?>

						

						<?php echo $form->errorSummary($model); ?>
						<div class="row">
							<div class="table-responsive">
								<table class="tables tablesorter cabeceraTabla" id="keywords">
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
						<div class="form-group">
							<?php echo $form->labelEx($model,'detalle', array('class' => 'col-sm-2 control-label')); ?>
							<div class="col-sm-4">
								<?php echo $form->textField($model,'detalle',array('size'=>60,'maxlength'=>255, 'class' => 'form-control')); ?>
								<?php echo $form->error($model,'detalle'); ?>
							</div>
							
							<?php echo $form->labelEx($model,'nombre', array('class' => 'col-sm-2 control-label')); ?>
							<div class="col-sm-4">
								<?php echo CHtml::activeFileField($model, 'nombre',array('size'=>60,'maxlength'=>255, 'class' => 'form-control file')); ?>
								<?php echo $form->error($model,'nombre'); ?>
								<?php	foreach(Yii::app()->user->getFlashes() as $key => $message) {
								echo '<div class=" row flash-' . $key . '">' . $message . "</div>\n";
							}?>
							</div>
							
							<?php echo $form->hiddenField($model,'boletinpostventa_id',array("value"=>$id)); ?>
						</div>
						
						<div class="row buttons">
							<?php echo CHtml::submitButton($model->isNewRecord ? 'Enviar' : 'Actualizar', array('class' => 'btn btn-danger')); ?>
						</div>
					<?php $this->endWidget(); ?>
					</div><!-- form -->
				</div>
					
			</div>
			 <?php endif; ?>
			 <?php endif; ?>
        </div>
        <div class="col-md-3 col-md-offset-1 cont_der">
           
            <p class="border-bt">Tambi&eacute;n puedes ir a:</p>
            <ul>
                <li><a href="<?php echo Yii::app()->createUrl('pvboletinpostventa/adminAdjunto/'.$id); ?>" class="seguimiento-btn">Ir a documentos adjuntos del Bolet&iacute;n</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></li>
            </ul>
        </div>
    </div>
</div>
