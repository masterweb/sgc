<?php
/* @var $this VersionesController */
/* @var $model Versiones */
/* @var $form CActiveForm */
?>
<script type="text/javascript">
	$(document).ready(function () {
		$('#versiones-form').validate({
			rules:{
				'Versiones[id_modelos]': {required: true},
				'Versiones[categoria]': {required: true},
				'Versiones[nombre_version]': {required: true},
				'Versiones[precio]': {required: true},
				'Versiones[status]': {required: true},
				'Versiones[pdf]': {required : true}
			},
			messages: {},
            submitHandler: function (form) {
            	form.submit();
            }
		});
	});
	function validateNumbers(c) {
        var d = (document.all) ? c.keyCode : c.which;
        if (d < 48 || d > 57) {
            if (d == 8) {
                return true
            } else {
                return false
            }
        }
        return true
    }
</script>
<div class="row">
	<div class="col-md-12">
		<h2>Formulario para Versiones</h2>
	</div>
</div>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'versiones-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<div class="col-md-3">
		<label for="">Modelo</label>
		<?php
            $modelos = CHtml::listData(Modelos::model()->findAll(array('condition' => 'active = 1', 'order' => 'nombre_modelo')), "id_modelos", "nombre_modelo");
        ?>
		
		<?php echo $form->dropDownList($model,'id_modelos', $modelos, array('empty' => '---Seleccione un modelo---','class' => 'form-control')); ?>
		<?php echo $form->error($model,'id_modelos'); ?>
		</div>
		<div class="col-md-3">
		<label for="">Categoría</label>
		<?php echo $form->dropDownList($model,'categoria', array('' => '---Seleccione una categoria---',
		'1' => 'Auto', '2' => 'SUV', '3' => 'MPV', '4' => 'Camion'),array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'categoria'); ?>
		</div>
	</div>


	<div class="row">
		<div class="col-md-3">
		<?php echo $form->labelEx($model,'nombre_version'); ?>
		<?php echo $form->textField($model,'nombre_version',array('size'=>60,'maxlength'=>100,'class' => 'form-control')); ?>
		<?php echo $form->error($model,'nombre_version'); ?>
		</div>
		<div class="col-md-3">
		<?php echo $form->labelEx($model,'precio'); ?>
		<?php echo $form->textField($model,'precio',array('size'=>11,'maxlength'=>11,'class' => 'form-control', 'onkeypress' => 'return validateNumbers(event)')); ?>
		<?php echo $form->error($model,'precio'); ?>
		</div>
	</div>

	<div class="row">
		<div class="col-md-3">
		<label for="">Status</label>
		<?php echo $form->dropDownList($model,'status', array('' => '---Seleccione una categoria---',
		'1' => 'Activo', '2' => 'Desactualizado', '3' => 'No Concesionario'),array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'status'); ?>
		</div>
	</div>

    
	<div class="row">
		<div class="col-md-4">
			<?php echo $form->labelEx($model,'pdf'); ?>
			<?php echo $form->FileField($model,'pdf',array('size'=>60,'maxlength'=>255,'class' => 'form-control')); ?>
			<?php echo $form->error($model,'pdf'); ?>
		</div>
	</div>

	<div class="row buttons">
		<div class="col-md-2">
			<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-danger')); ?>
		</div>
		
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.js"></script>