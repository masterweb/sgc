<script src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/jquery.validate.js" type="text/javascript" charset="utf-8"></script>
<script>
    $(function () {
        $("#accesosistema-form").validate({
            submitHandler: function(form) {
                var error = 0;
                var formTp = '';
                var descripcion = $("#Accesosistema_descricion").val();
                var codigo = $("#Accesosistema_accion").val();
                var controlador = $('#Accesosistema_controlador').val();
				if(codigo == ""){
					alert('Ingrese un <?php echo utf8_encode("Acción, es un campo obligatorio");?>');
					$('#Accesosistema_accion').focus();
					error++;
				}
				if(descripcion == ""){
					alert('Ingrese una <?php echo utf8_encode("descripción, es un campo obligatorio");?>');
					$('#Accesosistema_descricion').focus();
					error++;
				}if(controlador == ""){
					alert('Ingrese un <?php echo utf8_encode("controlador, es un campo obligatorio");?>');
					$('#Accesosistema_controlador').focus();
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
	'id'=>'accesosistema-form',
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
		<?php echo $form->labelEx($model,'descricion',array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-4">
			<?php echo $form->textField($model,'descricion',array('size'=>100,'maxlength'=>100, 'class' => 'form-control')); ?>
			<?php echo $form->error($model,'descricion'); ?>
		</div>
		<?php echo $form->labelEx($model,'modulo_id',array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-4">
			<?php echo $form->dropDownList($model,'modulo_id', CHtml::listData(Modulo::model()->findAll(array('condition'=>'descripcion !="TOTAL"')), 'id', 'descripcion'), array('empty'=>'Seleccione >>','class'=>'form-control')) ?> 
			<?php echo $form->error($model,'modulo_id'); ?>
		</div>
	</div>
	
	
	<div class="form-group">
		<?php echo $form->labelEx($model,'controlador',array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-4">
			<?php echo $form->textField($model,'controlador',array('size'=>100,'maxlength'=>100, 'class' => 'form-control')); ?>
			<?php echo $form->error($model,'controlador'); ?>
		</div>
		<?php echo $form->labelEx($model,'accion',array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-4">
			<?php echo $form->textField($model,'accion',array('size'=>100,'maxlength'=>100, 'class' => 'form-control')); ?>
			<?php echo $form->error($model,'accion'); ?>
		</div>
	</div>
	
	<div class="form-group">
		<?php echo $form->labelEx($model,'estado',array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
			<?php echo $form->dropDownList($model,'estado',array("ACTIVO"=>"ACTIVO","INACTIVO"=>"INACTIVO"),array('class' => 'form-control')); ?>
			<?php echo $form->error($model,'estado'); ?>
		</div>
	</div>

	
	<div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', array('class' => 'btn btn-danger')); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->