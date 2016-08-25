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
    @media (min-width: 992px){
        .container {
            max-width: 1150px;
        }
    }
    html{height: 100%;}

    #cometchat {
        display: none;
    }
    header{display: none;}footer{display: none;}body{padding-bottom: 0px;background: none !important;height: 100%;}
    #login-username::-webkit-input-placeholder,
    #login-password::-webkit-input-placeholder
    {
        color:    #FFFFFF;
    }

    #login-username:-moz-placeholder,
    #login-password:-moz-placeholder 
    {
        color:    #FFFFFF;
    }

    #login-username::-moz-placeholder,
    #login-password::-moz-placeholder 
    {
        color:    #FFFFFF;
    }

    #login-username:-ms-input-placeholder,
    #login-password:-ms-input-placeholder 
    {
        color:    #FFFFFF;
    }
</style>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/estilosUsuarios.css" type="text/css" />
<div class="intro">
    <div class="container">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <div class="cont-log">
                    <div class="col-md-12">
                        <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/logo-kia_03.png" alt=""/>
                    </div>
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
                    <div class="col-md-12">
                        <div style="margin-bottom: 20px" class="input-group">
                            <span class="input-group-addon"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/iconos_03.png" alt="" /></span>
                            <!--<input id="login-username" type="text" class="form-control form-input" name="username" value="" placeholder="Usuario">-->
                            <?php echo $form->textField($model, 'username', array('class' => 'form-control form-input', 'placeholder' => 'Usuario', 'id' => 'login-username')); ?>

                        </div>
                    </div>
                    <?php echo $form->error($model, 'username'); ?>
                    <div class="col-md-12">
                        <div style="margin-bottom: 10px" class="input-group">
                            <span class="input-group-addon"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/iconos_06.png" alt="" /></span>
                            <!--<input id="login-password" type="password" class="form-control form-input" name="password" placeholder="Contraseña">-->
                            <?php echo $form->passwordField($model, 'password', array('class' => 'form-control form-input', 'placeholder' => 'Contraseña', 'id' => 'login-password')); ?>

                        </div>
                    </div>
                    <?php echo $form->error($model, 'password'); ?>
                    <div class="row centrar">
                        <a href="<?php echo Yii::app()->createUrl('site/recuperar'); ?>" class="linkLogin">Olvidé mi usuario o contrase&ntilde;a</a>
                        <span style="font-size:13px"> - </span>					
                        <a href="<?php echo Yii::app()->createUrl('site/registro'); ?>" class="linkLogin">Regístrate</a>
                    </div>
                    <div style="margin-top:27px" class="form-group">
                        <!-- Button -->

                        <div class="col-sm-12 controls">
                            <?php echo CHtml::submitButton('Iniciar Sesión', array('class' => 'btn btn-large btn-danger')); ?>
                            <!--<a id="btn-fblogin" href="#" class="btn btn-primary">Login with Facebook</a>-->
                        </div>
                    </div>
                    <div class="form-group">
                        <p>Al hacer click en "Iniciar Sesión", está aceptando la directiva de uso aceptable de 
                            Kia Motors Ecuador.</p>
                    </div>
                    <div class="input-groups">
                        <div class="col-md-5 col-xs-6">
                            <div class="checkbox">
                                <label>
                                    <!--<input id="login-remember" type="checkbox" name="remember" value="1"> Remember me-->
                                    <input id="ytLoginForm_rememberMe" type="hidden" value="0" name="LoginForm[rememberMe]"><input name="LoginForm[rememberMe]" id="LoginForm_rememberMe" value="1" type="checkbox">
                                    <label for="LoginForm_rememberMe" class="rec">Recuérdame</label>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-7 col-xs-6">
                            <a href="<?php echo Yii::app()->request->baseUrl; ?>/images/Manual-Registro-Usuario-KIA.pdf" class="rec" style="margin-top: -5px;display: block;" target="_blank">Como registrarse</a>
                        </div>

                    </div>
                    <div class="col-md-12">
                        <a href="<?php echo Yii::app()->request->baseUrl; ?>/images/TERMINOS-Y-CONDICIONES-DEL-SITIO-WEB.pdf" class="linkLogin" style="margin-top:18px;display:block;" target="_blank">Términos y Condiciones</a>
                    </div>
                    <?php $this->endWidget(); ?>    
                </div>
            </div>
        </div>
    </div>
</div>
<div class="foot"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/foot_1.png">
</div>


