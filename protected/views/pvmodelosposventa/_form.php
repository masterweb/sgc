<script src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/jquery.validate.js" type="text/javascript" charset="utf-8"></script>
<script>
    $(function () {
        $("#modelosposventa-form").validate({
            submitHandler: function(form) {
                var error = 0;
                var formTp = '';
                var codigo = $("#Modelosposventa_codigo").val();
                var descripcion = $('#Modelosposventa_descripcion').val();
                var ordenar = $('#Modelosposventa_ordenar').val();
				if(codigo == ""){
					alert('Ingrese un <?php echo utf8_encode("código, es un campo obligatorio");?>');
					$('#Modelosposventa_codigo').focus();
					error++;
				}
				if(descripcion == ""){
					alert('Ingrese una <?php echo utf8_encode("descripción, es un campo obligatorio");?>');
					$('#Modelosposventa_descripcion').focus();
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

<?php 
	$form=$this->beginWidget('CActiveForm', array(
	'id'=>'modelosposventa-form',
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
		<?php echo $form->labelEx($model,'codigo', array('class' => 'col-sm-2 control-label')); ?>
		 <div class="col-sm-4">
			<?php echo $form->textField($model,'codigo',array('size'=>60,'maxlength'=>100, 'class' => 'form-control')); ?>
			<?php echo $form->error($model,'codigo'); ?>
		</div>
			<?php echo $form->labelEx($model,'descripcion', array('class' => 'col-sm-2 control-label')); ?>
		 <div class="col-sm-4">
			<?php echo $form->textField($model,'descripcion',array('size'=>60,'maxlength'=>255, 'class' => 'form-control')); ?>
			<?php echo $form->error($model,'descripcion'); ?>
		</div>
	</div>

	

	<div class="form-group">
		<?php echo $form->labelEx($model,'ordenar', array('class' => 'col-sm-2 control-label')); ?>
		 <div class="col-sm-4">
			<?php echo $form->textField($model,'ordenar',array('size'=>60,'maxlength'=>255, 'class' => 'form-control')); ?>
			<?php echo $form->error($model,'ordenar'); ?>
		</div>
	</div>
	
	<div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', array('class' => 'btn btn-danger')); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->