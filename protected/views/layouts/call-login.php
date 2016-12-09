<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$model = new LoginForm;
?>
<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Intranet</title>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
        <!-- Bootstrap -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/css/bootstrap.css" rel="stylesheet">
        <!--[if lt IE 8]>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
        <![endif]-->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/estilos.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->
<!--        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/angular.min.js" ></script>-->
        <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
        <?php Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
    </head>
    <style type="text/css">
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
    <body>
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
                                <div style="margin-bottom: 25px" class="input-group">
                                    <span class="input-group-addon"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/iconos_03.png" alt="" /></span>
                                    <!--<input id="login-username" type="text" class="form-control form-input" name="username" value="" placeholder="Usuario">-->
                                    <?php echo $form->textField($model, 'username', array('class' => 'form-control form-input', 'placeholder' => 'Usuario','id' => 'login-username')); ?>
                                    <?php echo $form->error($model, 'username'); ?>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div style="margin-bottom: 10px" class="input-group">
                                    <span class="input-group-addon"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/iconos_06.png" alt="" /></span>
                                    <!--<input id="login-password" type="password" class="form-control form-input" name="password" placeholder="Contraseña">-->
                                    <?php echo $form->passwordField($model, 'password', array('class' => 'form-control form-input', 'placeholder' => 'Contraseña','id' => 'login-password')); ?>
                                    <?php echo $form->error($model, 'password'); ?>
                                </div>
                            </div>
                            <div class="row centrar">
                                <a href="<?php echo Yii::app()->createUrl('site/recuperar'); ?>" class="linkLogin">Olvidé mi usuario o contrase&ntilde;a</a>
                                <span style="font-size:13px"> - </span>					
                                <a href="<?php echo Yii::app()->createUrl('site/registro'); ?>" class="linkLogin">Regístrate</a>
                            </div>
                            <div style="margin-top:10px" class="form-group">
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
                            <div class="input-group">
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
                                    <label for="" class="rec">Como registrarse</label>
                                </div>

                            </div>
                            <?php $this->endWidget(); ?>    
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </body>
</html>

