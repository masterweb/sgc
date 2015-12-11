<script src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/jquery.validate.js" type="text/javascript" charset="utf-8"></script>
<script>
    $(function () {
        $("#cargo-form").validate({
            submitHandler: function(form) {
                var error = 0;
                var formTp = '';
              
                var descripcion = $('#Cargo_descripcion').val();
				
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
		<label class="col-sm-2 control-label" for="Cargo_area_id">Ubicaci&oacute;n</label>
		<div class="col-sm-4">
			<?php 
					echo '<select class="form-control" id="slUbicacion" onchange="traerArea(this.value)">';
					echo '<option value="0">--Seleccione la Ubicaci&oacute;n--</option>';
					echo '<option value="1">AEKIA</option>';
					echo '<option value="2">CONCESIONARIO</option>';
					echo '</select>';
			?> 
			
		</div>
		<?php echo $form->labelEx($model,'area_id',array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-4">
			<div id="dArea"> <p style="font-size:13px;font-weight:bold;padding-top:5px"><< Seleccione una Ubicaci&oacute;n</p></div>
			<?php echo $form->error($model,'area_id'); ?>
		</div>
		
	</div>
	
	<div class="form-group">
		<?php echo $form->labelEx($model,'descripcion',array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
			<?php echo $form->textField($model,'descripcion',array('size'=>50,'maxlength'=>50, 'class' => 'form-control')); ?>
			<?php echo $form->error($model,'descripcion'); ?>
		</div>
		
	</div>
	<div class="form-group">
		<!--<div class="col-sm-2">
			<h5><b>Fuente</b></h5>
		</div>
		<div class="col-sm-4">-->
			<?php 
				/*$fuente = Fuente::model()->findAll();
				if(!empty($fuente)){
					foreach($fuente as $f){
						$chk = '';
						if(!empty($ufuentes)){
							foreach($ufuentes as $um){
								if($um->fuente_id == $f->id){
									$chk = 'checked="true"';
								}
							}
						}
						//echo '<div class="checkbox" style="font-size:13px;"><label><input type="checkbox" '.$chk.' name="fuente[]" value="'.$f->id.'"> '.$f->descripcion.'</label></div>';
					}
				}*/
			?>
		<!--</div>-->
		<div class="col-sm-2">
			<h5><b>Modulos</b></h5>
		</div>
		<div class="col-sm-8">
			<?php 
				$modulos = Modulo::model()->findAll();
				if(!empty($modulos)){
					foreach($modulos as $f){
						$chk = '';
						if(!empty($umodulos)){
							foreach($umodulos as $um){
								if($um->modulo_id == $f->id){
									$chk = 'checked="true"';
								}
							}
						}
						echo '<div class="checkbox" style="font-size:13px;"><label><input '.$chk.' type="checkbox" name="modulo[]" value="'.$f->id.'"> '.$f->descripcion.'</label></div>';
					}
				}
			?>
		</div>
		
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'codigo',array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-4">
			<?php echo $form->textField($model,'codigo',array('size'=>10,'maxlength'=>10, 'class' => 'form-control')); ?>
			<?php echo $form->error($model,'codigo'); ?>
		</div>
		<?php echo $form->labelEx($model,'estado',array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-4">
			
			<?php echo $form->dropDownList($model,'estado',array("ACTIVO"=>"ACTIVO","INACTIVO"=>"INACTIVO"),array('class' => 'form-control')); ?>
			<?php echo $form->error($model,'estado'); ?>
		</div>
	</div>
	<?php	foreach(Yii::app()->user->getFlashes() as $key => $message) {
		echo '<div class=" row flash-' . $key . '">' . $message . "</div>\n";
	}?>
	<div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', array('class' => 'btn btn-danger')); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<script>
	function traerArea(vl){
		if(vl>0){
			$.ajax({
				url: '<?php echo Yii::app()->createUrl("/site/traerArea")?>',
				type:'POST',
				async:false,
				data:{
					rs : vl,
				},
				success:function(result){
					if(result != 0){
						$("#dArea").html(result);
					}else{
						 $("#dArea").html('Seleccione una Ubicacion por favor.');
					}
				}
			});
		}
	}
	function buscarCargo(vl){
		return true;
	}
</script>