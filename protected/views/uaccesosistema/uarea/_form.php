<script src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/jquery.validate.js" type="text/javascript" charset="utf-8"></script>
<script>
    $(function () {
        $("#cargo-form").validate({
            submitHandler: function(form) {
                var error = 0;
                var formTp = '';
                var codigo = $("#Area_modulo_id").val();
                var descripcion = $('#Area_descripcion').val();
				if(codigo == ""){
					alert('Ingrese un <?php echo utf8_encode("Modulo, es un campo obligatorio");?>');
					$('#Area_modulo_id').focus();
					error++;
				}
				if(descripcion == ""){
					alert('Ingrese una <?php echo utf8_encode("descripción, es un campo obligatorio");?>');
					$('#Area_descripcion').focus();
					error++;
				}
                if(error == 0){
                    form.submit();
                }
                
            }
        })
    });
</script>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cargo-form',
		'enableAjaxValidation' => false,
        'clientOptions' => array(
        'validateOnSubmit'=>false,
        'validateOnChange'=>false,
        'validateOnType'=>false,
         ),
        'htmlOptions' => array('class' => 'form-horizontal')
	));
    ?>
	

	<?php echo $form->errorSummary($model); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'tipo',array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-4">
			
			<?php echo $form->dropDownList($model,'tipo', array('1'=>'AEKIA','2'=>'CONCESIONARIOS'), array('empty'=>'Seleccione >>','class'=>'form-control')) ?> 
			<?php echo $form->error($model,'tipo'); ?>
		</div>
		
		<?php echo $form->labelEx($model,'descripcion',array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-4">
			<?php echo $form->textField($model,'descripcion',array('size'=>150,'maxlength'=>150, 'class' => 'form-control')); ?>
			<?php echo $form->error($model,'descripcion'); ?>
		</div>
		
	</div>
	<div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', array('class' => 'btn btn-danger')); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->