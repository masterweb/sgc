<?php
$con = Yii::app()->db;
/* @var $this Controller */
//echo 'dealer id: '.Yii::app()->user->getState('dealer_id').'<br>';
date_default_timezone_set('America/Guayaquil'); // Zona horaria de Guayaquil Ecuador
//echo 'fecha actual: '.$fecha_actual;
$fecha="2015/10/28 07:00:00";
//echo 'fecha actual: '.strtotime('now');
//echo '<br />';
//echo 'fecha seguimiento: '.strtotime($fecha);
//echo '<br />';
//$fecha="2015-10-31 11:00:00";
$segundos= strtotime('now') - strtotime($fecha);
//echo 'diferencia segundos: '.$segundos;
$diferencia_horas=intval($segundos/60/60);
//echo '<br />'."La cantidad de horas entre el ".$fecha." y hoy es <b>".$diferencia_horas."</b>";
// SACAR FECHAS AGENDAMIENTO DE TABLA GESTION AGENDAMIENTO

$cr = GestionAgendamiento::model()->findAll();
foreach ($cr as $c) {
    if($c['agendamiento'] != ''){
        // update gestion informacion BDC 1
        $fecha = $c['agendamiento'];
        //echo 'FECHA AGENDAMIENTO: '.$fecha.'<br />';
        $segundos= strtotime('now') - strtotime($fecha);
        $diferencia_horas=intval($segundos/60/60);
        //echo '<br />'."La cantidad de horas para id informacion: ".$c['id_informacion'].", entre el ".$fecha." y hoy es <b>".$diferencia_horas."</b>"; 
        if ($diferencia_horas > 12) {
            $sql = "UPDATE gestion_informacion SET bdc = 1 WHERE id = {$c['id_informacion']}";
            $request = $con->createCommand($sql)->query();
        }
    }
}

?>
<!doctype html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Intranet</title>
        <!-- blueprint CSS framework -->
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
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/estilosUsuarios.css" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->
        <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
        <?php Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/functions.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.tablesorter.min.js"></script>
        <?php 
        $controller = Yii::app()->controller->id.'<br />'; 
        $action = Yii::app()->controller->action->id.'<br />';
        ?>
        <?php if(Yii::app()->user->getState('area_id') == 17): ?>
        <script type="text/javascript">
//            $(document).ready(function () {
//                var img = '<?php echo Yii::app()->request->baseUrl; ?>/images/logo_SGC_nuevo.png';
//                var imgmenu = '<?php echo Yii::app()->request->baseUrl; ?>/images/stage_ventas.jpg';
//                $('#imglogo').attr('src' , img);
//                $('#imgmenu').attr('src' , imgmenu);
//            });
        </script>
        <?php endif; ?>
        <script>
            var abrir = 0;
            function goBack() {
                window.history.back()
            }
            function verN(num) {
                if (num > 0) {
                    if (abrir == 0) {
                        $("#lNotificaciones").slideUp("slow");
                        abrir = 1;
                    } else {
                        $("#lNotificaciones").slideDown("slow");
                        abrir = 0;

                    }
                }

            }
        </script>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <style>
            .cometchat_trayiconimage {display: none !important;}
        </style>
<!--        <link type="text/css" href="/intranet.kia.com.ec/web/usuario/cometchat/cometchatcss.php" rel="stylesheet" charset="utf-8">
        <script type="text/javascript" src="/intranet.kia.com.ec/web/usuario/cometchat/cometchatjs.php" charset="utf-8"></script>-->
    </head>
    <body>
        <div id="bg_negro" style="display:none">
            <div class="cont_lbox">
                <div class="img_preload"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader-big.gif" width="32" height="32" /></div>
                <div class="txt1_form">Grabando...</div>
                <div class="clear"></div>
            </div>
        </div>
        
        <header>
            <div class="logo">
                <!--a href="<?php echo Yii::app()->createUrl('site/menu'); ?>"-->
                <?php if((Yii::app()->user->getState('area_id') == 1887) && ($controller != 'site') && ($action != 'dashboard')): ?>
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/logo_SGC_nuevo.png" class="img-responsive" id="imglogo">
                <?php else: ?>
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/intranet.png" class="img-responsive" id="imglogo">
                <?php endif; ?>
                <!--/a-->
            </div>
            <div class="sesion">
            <?php if (Yii::app()->user->id > 0) { ?>
                    <div class="ico_sesion">
                    <?php
                    $user = Usuarios::model()->find(array('condition' => "id=:match", 'params' => array(':match' => (int) Yii::app()->user->id)));
                    if (!empty($user->foto)) {
                        ?>
                            <img src="<?php echo Yii::app()->request->baseUrl; ?>/upload/perfiles/thumb/<?php echo $user->foto; ?>" width="26" height="34">
                        <?php } else { ?>
                            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/img_06.jpg" width="26" height="34">
                        <?php } ?>
                        <div class="btn-group ">
                            <button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default btn-sm dropdown-toggle">
                                <?php echo Yii::app()->user->getState('first_name'); ?> <span class="caret"></span>
                            </button>
                            <ul role="menu" class="dropdown-menu">
                                <li><a href="<?php echo Yii::app()->createUrl('uusuarios/perfil'); ?>">Editar Perfil</a></li>
                                <li class="divider"></li>
                                <li><a href="<?php echo Yii::app()->createUrl('site/logout'); ?>">Salir </a></li>
                            </ul>
                        </div>
                        <div class="progress ">
                            <?php
                            $total = 100;
                            if (empty($user->telefono))
                                $total = (int) $total - 20;
                            if (empty($user->extension))
                                $total = (int) $total - 10;
                            if (empty($user->foto))
                                $total = (int) $total - 10;
                            if (empty($user->celular))
                                $total = (int) $total - 10;
                            if (empty($user->correo))
                                $total = (int) $total - 20;
                            if (empty($user->nombres))
                                $total = (int) $total - 5;
                            if (empty($user->fechanacimiento))
                                $total = (int) $total - 5;
                            ?>

                            <div class="progress-bar  progress-bar-danger" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $total; ?>%;">
                            <?php echo $total; ?>%
                            </div>

                        </div>
                        <span class="ul"><?php echo '&Uacute;ltima visita: ' . $user->ultimavisita; ?></span>
                    </div>
<?php } ?>
<?php
/* $this->widget('zii.widgets.CMenu', array(
  'items' => array(
  array('label' => 'Salir (' . Yii::app()->user->getState('first_name') . ' ' . Yii::app()->user->getState('last_name') . ')', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest)
  ),
  'htmlOptions' => array(
  'class' => 'nav navbar-nav navbar-right',
  ),
  ));
 */
?>
<!--<div class="btn_sesion"><a href=""><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/img_09.jpg" width="75" height="13"></a></div>-->

                <div class="clear"></div>

            </div>

            <div class="clear"></div>
        </header>
<?php echo $content; ?>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/bootstrap.min.js"></script>
        <footer><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/img_31.jpg" width="175" height="75"></footer>
    </body>
</html>
