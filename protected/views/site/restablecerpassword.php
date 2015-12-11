<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/estilosUsuarios.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/strength.css" type="text/css" />
<div class="container">
    <div class="row">
        <div class="col-md-12">
			
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="info">
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
            <?php else: ?>
            <h1 class="tl_seccion">Recuperar contrase&ntilde;a de usuario</h1>
			<div class="alert alert-info">
                <strong>Importante!</strong> Ingrese su USUARIO o CORREO ELECTR&Oacute;NICO, para buscar su identidad.
			</div>
            <div class="form">

			<form id="recuperarfrm" class="form-horizontal" method="post" action="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/restablecerpassword/t/<?php echo $t;?>/pz/<?php echo $pz;?>">
				<div class="form-group">
					<label class = 'col-sm-2 control-label'>Contrase&ntilde;a:</label>
					<div class="col-sm-4">
						<input type="password" class="form-control" name="password" id="password">
					</div>
					<label class = 'col-sm-2 control-label'>Repetir Contrase&ntilde;a:</label>
					<div class="col-sm-4">
						<input type="password" class="form-control" name="repetir_password" id="repetir_password">
					</div>
				</div>
				<?php if (Yii::app()->user->hasFlash('error')){ ?>
					<div class="infos">
						<?php echo Yii::app()->user->getFlash('error'); ?>
					</div>
				<?php } ?>
				<div class="row buttons">
					<input type="submit"  class='btn btn-danger' value="Enviar">
				</div>
				
			</form>
			</div><!-- form -->
            <?php endif; ?>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/strength.js"></script>
<script>

var eee = 1;
 $(function() {
       
	 $('#password').strength({
	             strengthClass: 'strength',
	             strengthMeterClass: 'strength_meter',

	 });
	$('#recuperarfrm').submit(function(){     
		 if($("#password").val() != "" && $("#password").val() == $("#repetir_password").val()) { 
			 if(($("#password").val()).length >= 6) 
				 return true;
			 else{
	 			alert('Las <?php echo utf8_decode(utf8_encode("contraseñas son muy corta debe tener mínimo 6 caracteres entre números y letras."))?>');
	 			return false;
			 }
			
		}else{
			alert('Las <?php echo utf8_decode(utf8_encode("contraseñas ingresadas son incorrectas o se encuentran en blanco."))?>');
			return false;
		}
		
	});
 });
	
	
</script>