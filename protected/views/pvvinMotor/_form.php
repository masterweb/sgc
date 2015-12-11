<script src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/jquery.validate.js" type="text/javascript" charset="utf-8"></script>
<script>
    $(function () {
        $("#vin-motor-form").validate({
            submitHandler: function(form) {
                var error = 0;
                var formTp = '';
                var codigo = $("#VinMotor_vin").val();
                var descripcion = $('#VinMotor_motor').val();
				if(codigo == ""){
					alert('Ingrese un <?php echo utf8_encode("VIN, es un campo obligatorio");?>');
					$('#VinMotor_vin').focus();
					error++;
				}
				if(descripcion == ""){
					alert('Ingrese un <?php echo utf8_encode("MOTOR, es un campo obligatorio");?>');
					$('#VinMotor_motor').focus();
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
	'id'=>'vin-motor-form',
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
		<?php echo $form->labelEx($model,'vin', array('class' => 'col-sm-2 control-label')); ?>
		 <div class="col-sm-4">
			<?php echo $form->textField($model,'vin',array('size'=>60,'maxlength'=>255, 'class' => 'form-control')); ?>
			<?php echo $form->error($model,'vin'); ?>
		</div>
		<?php echo $form->labelEx($model,'motor', array('class' => 'col-sm-2 control-label')); ?>
		 <div class="col-sm-4">
			<?php echo $form->textField($model,'motor',array('size'=>60,'maxlength'=>255, 'class' => 'form-control')); ?>
			<?php echo $form->error($model,'motor'); ?>
		</div>
	</div>

	
	<div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', array('class' => 'btn btn-danger')); ?>
    </div>
<?php $this->endWidget(); ?>

</div><!-- form -->