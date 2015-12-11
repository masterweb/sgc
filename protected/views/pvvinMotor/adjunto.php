<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilos.css" type="text/css" />
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
            <h1 class="tl_seccion">Adjuntar Archivo de Excel</h1>
			<div class="alert alert-info moverIzq">
				<strong>ATENCI&Oacute;N!</strong> Solo se permite archivos de <b>EXCEL</b> con extensiones xls o xlsx y el nombre no debe tener espacios en blanco ni signos de puntuaci&oacute;n (.) adicionales.
			</div>
			 <div class="col-md-12">
				<div class="form">
					<!--<form method="POST" ENCTYPE="multipart/form-data" action="<?php //echo Yii::app()->request->baseUrl; ?>/index.php/pvvinMotor/adjunto" style="padding:0">-->
					<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'usuarios-form',
				'enableAjaxValidation' => true,
					'clientOptions' => array(
					'validateOnSubmit'=>false,
					'validateOnChange'=>false,
					'validateOnType'=>false,
					 ),
					'htmlOptions' => array('class' => 'form-horizontal','enctype' => 'multipart/form-data',)
				));
				?>
						<div class="form-group row">
							<label class = 'col-sm-3 control-label' style="text-align:right">Seleccione un archivo:</label>
							<div class="col-sm-4">
								<?php echo CHtml::activeFileField($model, 'upload_file',array("class"=>"form-control subir")); ?>
								<?php echo $form->error($model,'upload_file'); ?>
							</div>
							
						</div>
						<div class="row col-md-7">
							<?php	foreach(Yii::app()->user->getFlashes() as $key => $message) {
								echo '<div class=" row flash-' . $key . '">' . $message . "</div>\n";
							}?>
						</div>    
						<div class="row buttons col-sm-8">
								<input type=submit value="Enviar" class='btn btn-danger'>
						</div>
							<?php $this->endWidget(); ?>
				</div>
					
			</div>
			 <?php endif; ?>
        </div>
        <div class="col-md-3 col-md-offset-1 cont_der">
           
            <p class="border-bt">Tambi&eacute;n puedes ir a:</p>
            <ul>
                <li><a href="<?php echo Yii::app()->createUrl('pvvinMotor/admin'); ?>" class="seguimiento-btn">Seguimiento de Vin Motor</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></li>
				<li><a href="javascript:history.back(1)" class="back-btn-go">Volver</a></li>
            </ul>
        </div>
    </div>
</div>

