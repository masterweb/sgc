<script src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/jquery.validate.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/css/custom-theme/jquery-ui-1.10.0.custom.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/mask.js" type="text/javascript" charset="utf-8"></script>
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
		<?php echo $form->labelEx($model,'usuario',array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-4">
			<?php echo $form->textField($model,'usuario',array('size'=>45,'maxlength'=>45, 'class' => 'form-control')); ?>
			<div id="errorUser" style="display:none;color: red;position: relative;top: 0px;left: 2px;font-size:11px">Usuario en uso, intente con otra descripci&oacute;n.</div>
			<?php echo $form->error($model,'usuario'); ?>
		</div>
		<?php echo $form->labelEx($model,'correo',array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-4">
			<?php echo $form->textField($model,'correo',array('size'=>60,'maxlength'=>150, 'class' => 'form-control')); ?>
			<div id="errorEmail" style="display:none;color: red;position: relative;top: 0px;left: 2px;font-size:11px">Correo Inv&aacute;lido.</div>
			<?php echo $form->error($model,'correo'); ?>
		</div>
		
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'fechanacimiento',array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-4">
			
			<?php echo $form->textField($model,'fechanacimiento',array('size'=>45,'maxlength'=>45,'readonly'=>'true', 'class' => 'form-control datepicker')); ?>
			<?php echo $form->error($model,'fechanacimiento'); ?>
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
		<?php echo $form->labelEx($model,'estado',array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-4">
			<?php echo $form->dropDownList($model,'estado',array("PENDIENTE"=>"PENDIENTE","CONFIRMADO"=>"CONFIRMADO","ACTIVO"=>"ACTIVO","INACTIVO"=>"INACTIVO","BAJA"=>"BAJA"),array('class' => 'form-control')); ?>
			<?php echo $form->error($model,'estado'); ?>
		</div>
	</div>

	<div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', array('class' => 'btn btn-danger')); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<script>
var eeec = 0;
var eee = 1;
 $(function() {
        //$("#Usuario_cedula").mask('9999999999');
        //$("#Usuario_fechaNacimiento").mask('99/99/1999');
		 $(".datepicker").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: new Date(1950, 10 - 1, 25),
            yearRange: '1970:2016'
        });
        $("#Usuarios_celular").mask('(09)-999-99999');
        $("#Usuarios_telefono").mask('(09)-999-9999');
		
	$('#usuarios-form').submit(function(){     
		//verificaNick($("#Usuarios_usuario").val());
		 if( validateEmail($("#Usuarios_correo").val())) { 
            $("#errorEmail").hide();
			eee = 0;
        }else{
            $("#errorEmail").show();
			eee = 1;
        }
		if(eee==0 && eeec == 0){
			return true;
			
		}else{
			return false;
		}
		alert('Existen errores en ciertos campos, debe corregirlos');
		return false;
	});
 });
	
	function buscarDealer(vl){
		//concesionarioscbo
		$.ajax({
			url: '<?php  echo Yii::app()->request->baseUrl; ?>/site/traerconsesionario',
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
	function verificaNick(vl){
		//concesionarioscbo
		
		$.ajax({
			url: '<?php  echo Yii::app()->request->baseUrl; ?>/site/verificaNick',
			type:'POST',
			async:false,
			data:{
				rs : vl,
			},
			success:function(result){
				if(result == 1){
					alert("Este usuario ya se encuentra en uso.");
					$("#errorUser").show();
					eeec = 1;
				}else{
					 $("#errorUser").hide();
					eeec = 0;
				}
			}
		});
	}
	function validateEmail($email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if( !emailReg.test( $email ) ) {
            return false;
        } else {
            return true;
        }
    }
	$("#Usuarios_correo").change(function() {
        if( validateEmail($("#Usuarios_correo").val())) { 
            $("#errorEmail").hide();
			eee = 0;
        }else{
            $("#errorEmail").show();
			eee = 1;
        }
    });
	$("#Usuarios_usuario").change(function() {
       // verificaNick($("#Usuarios_usuario").val());
    });
	function CedulaValida(cedula) {

        //Si no tiene el gui?n, se lo pone para la validaci?n
        if (cedula.match(/\d{10}/)) {
            cedula = cedula.substr(0, 9) + "-" + cedula.substr(9);
        }

        //Valida que la c?dula sea de la forma ddddddddd-d
        if (!cedula.match(/^\d{9}-\d{1}$/))
            return false;

        //Valida que el # formado por los dos primeros d?gitos est? entre 1 y 24
        var dosPrimerosDigitos = parseInt(cedula.substr(0, 2), 10);
        if (dosPrimerosDigitos < 1 || dosPrimerosDigitos > 24)
            return false;
        //Valida que el valor acumulado entre los primeros 9 n?meros coincida con el ?ltimo
        var acumulado = 0, digito, aux;
        for (var i = 1; i <= 9; i++) {
            digito = parseInt(cedula.charAt(i - 1));
            if (i % 2 == 0) { //si est? en una posici?n par
                acumulado += digito;
            } else { //si est? en una posici?n impar
                aux = 2 * digito;
                if (aux > 9)
                    aux -= 9;
                acumulado += aux;
            }
        }
        acumulado = 10 - (acumulado % 10);
        if (acumulado == 10)
            acumulado = 0;
        var ultimoDigito = parseInt(cedula.charAt(10));
        if (ultimoDigito != acumulado)
            return false;
        //				alert('asd');

        //La c?dula es v?lida
        return true;
        //		alert('bien');
    }
</script>