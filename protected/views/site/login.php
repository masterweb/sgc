<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle = Yii::app()->name . ' - Login';
$this->breadcrumbs = array(
    'Login',
);
?>
<style>
	#cometchat {
		display: none;
	}
</style>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/estilosUsuarios.css" type="text/css" />
<div class="container">    
    <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
        <div class="panel panel-info" >
            <div class="panel-heading">
                <div class="panel-title">Acceso de Usuarios</div>
                <!--<div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="#">Forgot password?</a></div>-->
            </div>     

            <div style="padding-top:30px" class="panel-body" >

                <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'login-form',
                    'enableClientValidation' => true,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                    ),
                    'htmlOptions' => array('class' => 'form-horizontal', 'role' => 'form')
                        ));
                ?>

                <div style="margin-bottom: 25px" class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <!--<input id="login-username" type="text" class="form-control" name="username" value="" placeholder="username or email">--> 
                    <?php echo $form->textField($model, 'username', array('class' => 'form-control', 'placeholder' => 'Nombre de usuario')); ?>

                </div>
                <?php echo $form->error($model, 'username'); ?>

                <div style="margin-bottom: 25px" class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <!--<input id="login-password" type="password" class="form-control" name="password" placeholder="password">-->
                    <?php echo $form->passwordField($model, 'password', array('class' => 'form-control', 'placeholder' => 'Password')); ?>

                </div>
                <?php echo $form->error($model, 'password'); ?>
                <div class="input-group">
                    <div class="checkbox">
                        <label>
                            <!--<input id="login-remember" type="checkbox" name="remember" value="1"> Remember me-->
                            <input id="ytLoginForm_rememberMe" type="hidden" value="0" name="LoginForm[rememberMe]"><input name="LoginForm[rememberMe]" id="LoginForm_rememberMe" value="1" type="checkbox">
                            <label for="LoginForm_rememberMe">Recordar Usuario o Contraseña</label>
                        </label>
                    </div>
					
                </div>
                <div style="margin-top:10px" class="form-group">
                    <!-- Button -->

                    <div class="col-sm-12 controls">
                        <?php echo CHtml::submitButton('Acceder', array('class' => 'btn btn-large btn-danger')); ?>
                        <!--<a id="btn-fblogin" href="#" class="btn btn-primary">Login with Facebook</a>-->
                    </div>
                </div>
				<div class="row centrar">
					<a href="<?php echo Yii::app()->createUrl('site/recuperar'); ?>" class="linkLogin">Olvide mi usuario o contrase&ntilde;a</a>
					<span style="font-size:13px"> - </span>					
					<a href="<?php echo Yii::app()->createUrl('site/registro'); ?>" class="linkLogin">Registrarse en la intranet</a>
				</div>
                </form>
                <?php $this->endWidget(); ?>
            </div>                     
        </div>  
    </div>
    <!--    <div id="signupbox" style="display:none; margin-top:50px" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">Sign Up</div>
                    <div style="float:right; font-size: 85%; position: relative; top:-10px"><a id="signinlink" href="#" onclick="$('#signupbox').hide(); $('#loginbox').show()">Sign In</a></div>
                </div>  
                <div class="panel-body" >
                    <form id="signupform" class="form-horizontal" role="form">
    
                        <div id="signupalert" style="display:none" class="alert alert-danger">
                            <p>Error:</p>
                            <span></span>
                        </div>
    
    
    
                        <div class="form-group">
                            <label for="email" class="col-md-3 control-label">Email</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="email" placeholder="Email Address">
                            </div>
                        </div>
    
                        <div class="form-group">
                            <label for="firstname" class="col-md-3 control-label">First Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="firstname" placeholder="First Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lastname" class="col-md-3 control-label">Last Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="lastname" placeholder="Last Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-md-3 control-label">Password</label>
                            <div class="col-md-9">
                                <input type="password" class="form-control" name="passwd" placeholder="Password">
                            </div>
                        </div>
    
                        <div class="form-group">
                            <label for="icode" class="col-md-3 control-label">Invitation Code</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="icode" placeholder="">
                            </div>
                        </div>
    
                        <div class="form-group">
                             Button                                         
                            <div class="col-md-offset-3 col-md-9">
                                <button id="btn-signup" type="button" class="btn btn-info"><i class="icon-hand-right"></i> &nbsp Sign Up</button>
                                <span style="margin-left:8px;">or</span>  
                            </div>
                        </div>
    
                        <div style="border-top: 1px solid #999; padding-top:20px"  class="form-group">
    
                            <div class="col-md-offset-3 col-md-9">
                                <button id="btn-fbsignup" type="button" class="btn btn-primary"><i class="icon-facebook"></i>   Sign Up with Facebook</button>
                            </div>                                           
    
                        </div>
                    </form>
                </div>
            </div>
        </div> -->
</div>


