<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/moment.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/daterangepicker.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/daterangepicker.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/jquery-ui-bootstrap/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/css/ui.daterangepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jquery-ui-bootstrap/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/js/date.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/js/daterangepicker.jQuery.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	
	$('#GestionFiles_fecha_actualizacion').daterangepicker({locale: {format: 'YYYY/MM/DD'},
	//startDate: '<?php echo $vartrf['fecha_inicial']; ?>',
	//endDate: '<?php echo $vartrf['fecha_actual']; ?>'
	}
	);
});
</script>
<?php
/* @var $this GestionFilesController */
/* @var $model GestionFiles */
/* @var $form CActiveForm */
?>
<div class="container">
<div class="row">
	<div class="col-md-12">
		<h2>Formulario para Biblioteca</h2>
	</div>
</div>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'gestion-files-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>

	

	<?php //echo $form->errorSummary($model); ?>

	<div class="row">
		<div class="col-md-4">
			<?php echo $form->labelEx($model,'nombre'); ?>
			<?php echo $form->FileField($model,'nombre',array('size'=>60,'maxlength'=>255,'class' => 'form-control')); ?>
			<?php echo $form->error($model,'nombre'); ?>
		</div>
	</div>

	<div class="row">
		<div class="row">
		<div class="col-md-4">
			<?php echo $form->labelEx($model,'descripcion'); ?>
			<?php echo $form->textField($model,'descripcion', array('class' => 'form-control')); ?>
			<?php echo $form->error($model,'descripcion'); ?>
		</div>
	</div>

	<div class="row">
		<div class="row">
		<div class="col-md-4">
			<?php echo $form->labelEx($model,'year'); ?>
			<?php echo $form->dropDownList($model,'year',array('2017' => '2017', '2018' => '2018'), array('class' => 'form-control')); ?>
			<?php echo $form->error($model,'year'); ?>
		</div>
	</div>
	<div class="col-md-4">
		<?php echo $form->labelEx($model,'mes'); ?>
		<?php echo $form->dropDownList($model,'mes',array(
		'Enero' => 'Enero',
		'Febrero' => 'Febrero',
		'Marzo' => 'Marzo',
		'Abril' => 'Abril',
		'Mayo' => 'Mayo',
		'Junio' => 'Junio',
		'Julio' => 'Julio',
		'Agosto' => 'Agosto',
		'Septiembre' => 'Septiembre',
		'Octubre' => 'Octubre',
		'Noviembre' => 'Noviembre',
		'Diciembre' => 'Diciembre'
		), array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'mes'); ?>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<?php echo $form->labelEx($model,'tipo'); ?>
			<?php echo $form->dropDownList($model,'tipo',array('1' => 'Libros de Producto', '2' => 'Presentación de Producto', '3' => 
			'Proceso de Ventas', '4' => 'Precios de Mercado', '5' => 'Cuadros Comparativos Competencia','6' => 'Porta Precios', '7' => 'Catálogo Producto'), array('class' => 'form-control')); ?>
			<?php echo $form->error($model,'tipo'); ?>
		</div>
	</div>

	<div class="row">
	<div class="col-md-4">
		<?php echo $form->labelEx($model,'provincia'); ?>
		<?php
        $provincias = CHtml::listData(Provincias::model()->findAll(array('condition' => "estado = 's'", 'order' => "nombre")), "id_provincia", "nombre");
        ?>
		<?php echo $form->dropDownList($model,'provincia', $provincias, array('class' => 'form-control', 'empty' => 'Seleccione una provincia')); ?>
		<?php echo $form->error($model,'provincia'); ?></div>
	</div>

	<div class="row">
	<div class="col-md-4">
		<?php echo $form->labelEx($model,'modelo'); ?>
		<?php echo $form->textField($model,'modelo',array('size'=>60,'maxlength'=>255,'class' => 'form-control')); ?>
		<?php echo $form->error($model,'modelo'); ?>
		</div>
	</div>

	<div class="row">
	<!--<div class="col-md-3">
		<?php echo $form->labelEx($model,'fecha_actualizacion'); ?>
		<?php echo $form->textField($model,'fecha_actualizacion', array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'fecha_actualizacion'); ?>
		</div>
	</div>-->

	<div class="row buttons">
		<div class="col-md-4">
			<?php echo CHtml::submitButton($model->isNewRecord ? 'Grabar' : 'Save', array('class' => 'btn btn-danger')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
</div>