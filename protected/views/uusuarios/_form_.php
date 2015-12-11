<script src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/jquery.validate.js" type="text/javascript" charset="utf-8"></script>
<script>
    $(function () {
        $("#cargo-form").validate({
            submitHandler: function(form) {
                var error = 0;
                var formTp = '';
                var codigo = $("#Cargo_codigo").val();
                var descripcion = $('#Cargo_descripcion').val();
				if(codigo == ""){
					alert('Ingrese un <?php echo utf8_encode("código, es un campo obligatorio");?>');
					$('#Cargo_codigo').focus();
					error++;
				}
				if(descripcion == ""){
					alert('Ingrese una <?php echo utf8_encode("descripción, es un campo obligatorio");?>');
					$('#Cargo_descripcion').focus();
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
	'id'=>'usuarios-form',
	'enableAjaxValidation' => true,
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
		<?php echo $form->labelEx($model,'cargo_id',array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
			<?php echo $form->dropDownList($model,'cargo_id', CHtml::listData(Cargo::model()->findAll(), 'id', 'descripcion'), array('empty'=>'Seleccione >>','class'=>'form-control')) ?> 
			<?php echo $form->error($model,'cargo_id'); ?>
		</div>
	</div>
	<div class="form-group">
		<label class = 'col-sm-2 control-label'>Ciudad</label>
		<div class="col-sm-4">
			<select class = 'form-control' onchange="buscarDealer(this.value)">
				<option>Seleccione un ciudad >></option>
				<?php
				
					if(!empty($ciudades)){
						foreach($ciudades as $c){
							echo '<option value="'.$c->id.'">'.$c->name.'</option>';
						}
					}
				?>
			</select>
		</div>
		<?php echo $form->labelEx($model,'dealers_id',array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-4">
			<div id="concesionarioscbo"><h6><< Primero seleccione una ciudad</h6></div>
			<?php echo $form->error($model,'dealers_id'); ?>
		</div>
		
	</div>
	
	<div class="form-group">
		<?php echo $form->labelEx($model,'nombres',array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
			<?php echo $form->textField($model,'nombres',array('size'=>60,'maxlength'=>250, 'class' => 'form-control')); ?>
			<?php echo $form->error($model,'nombres'); ?>
		</div>
		
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'correo',array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-4">
			<?php echo $form->textField($model,'correo',array('size'=>60,'maxlength'=>150, 'class' => 'form-control')); ?>
			<?php echo $form->error($model,'correo'); ?>
		</div>
		<?php echo $form->labelEx($model,'celular',array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-4">
			<?php echo $form->textField($model,'celular',array('size'=>45,'maxlength'=>45, 'class' => 'form-control')); ?>
			<?php echo $form->error($model,'celular'); ?>
		</div>
	</div>

	
	<div class="form-group">
		<?php echo $form->labelEx($model,'telefono',array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-4">
			<?php echo $form->textField($model,'telefono',array('size'=>15,'maxlength'=>15, 'class' => 'form-control')); ?>
			<?php echo $form->error($model,'telefono'); ?>
		</div>
		<?php echo $form->labelEx($model,'extension',array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-4">
			<?php echo $form->textField($model,'extension',array('size'=>10,'maxlength'=>10, 'class' => 'form-control')); ?>
			<?php echo $form->error($model,'extension'); ?>
		</div>
		
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'usuario',array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-4">
			
			<?php echo $form->textField($model,'usuario',array('size'=>45,'maxlength'=>45, 'class' => 'form-control')); ?>
			<?php echo $form->error($model,'usuario'); ?>
		</div>

		<?php echo $form->labelEx($model,'estado',array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-4">
			
			<?php echo $form->dropDownList($model,'estado',array("PENDIENTE"=>"PENDIENTE","ACTIVO"=>"ACTIVO","INACTIVO"=>"INACTIVO","BAJA"=>"BAJA"),array('class' => 'form-control')); ?>
			<?php echo $form->error($model,'estado'); ?>
		</div>
	</div>

	<div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', array('class' => 'btn btn-danger')); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<script>
	function buscarDealer(vl){
		//concesionarioscbo
		$.ajax({
			url: '<?php  echo Yii::app()->request->baseUrl; ?>/uusuarios/traerconsesionario',
			type:'POST',
			async:true,
			data:{
				rs : vl,
			},
			success:function(result){
				if(result == 0){
					alert("No se pudo realizar la consulta de concesionarios.");
					
				}else{
					$("#concesionarioscbo").html(result);
				}
			}
		});
	}
</script>