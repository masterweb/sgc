
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilos.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/css/ui.daterangepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.min.js"></script>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<?php
$accesosUser = Permiso::model()->findAll(array( 'condition' => "cargoId=:match", 'params' => array(':match' =>(int)Yii::app()->user->getState('cargo_id'))));

$case = ''; // para busqueda por defecto
//$getParams = '';    // para busqueda por parametros de GET
//if (isset($getParams)) {
//    echo '<pre>';
//    print_r($getParams);
//    echo '</pre>';
//}
//echo '<pre>';
//print_r($_GET);
//echo '</pre>';
//echo 'id call center: '.Yii::app()->user->getId().'<br>';
//echo 'rol: '.Yii::app()->user->getState('roles').'<br>';
$rol = Yii::app()->user->getState('roles');
?>
<style>
    .lblrespuesta{
        font-size: 12px !important;
        margin: 2px auto;
        font-weight: bold;
    }
    .row.pad-all {
        font-weight: bold;
        font-size: 13px;
        margin-bottom: 10px;
    }
    .tl_seccion {
        width: 95%;
    }
    .highlight {
        background-color: #fff;
        margin: auto 15px 20px;
        border: 10px;
    }
    .membrete h4{
        margin: 0;
        font-weight: 600;
        font-size: 16px;
    }
    .membrete .form .row {
        margin: 0;
    }
    div#formCerrar {
        margin: 15px auto;
        width: 95%;
        border: 1px solid #E7E7E7;
        background-color: #fff;
        border-radius: 5px;
    }
    form#grabarestado {
        background-color: #fff;
        width: 95%;
        margin: 15px auto;
        border: 1px solid #ECECEC;
        border-radius: 5px;
    }
    textarea#observacion {
        padding: 5px;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            
            
        <h1 class="tl_seccion">Duplicar la encuesta <?php echo $model->descripcion?></i></h1>
     
            <div class="row" id="formPosponer">
				
                <form id="grabarestado" class="form-horizontal" method="post" action="<?php echo Yii::app()->request->baseUrl; ?>/index.php/cquestionario/duplicar/<?php echo $model->id?>">
                   <h4>Seleccione la campaña en la cual se desea duplicar la encuesta </h4>
					<hr>
                    <div class="form-group">
                        <label class = 'col-sm-2 control-label text-right'>Campañas:</label>
                        <div class="col-sm-10">
                            <?php
                                $ccampana = Ccampana::model()->findAll();
                                if(!empty($ccampana)){
                                    echo '<select name="campana_item" id="campana_item">';
                                    foreach ($ccampana as $key) {
                                        echo '<option value="'.$key->id.'">'.$key->nombre.' - '.$key->descripcion.'</option>';
                                    }
                                    echo '</select>';
                                }
                            ?>
                        </div>
                    </div>
					<div class="form-group">
                        <label class = 'col-sm-2 control-label text-right'>Nombre:</label>
                        <div class="col-sm-10">
                           <input type="text" class="form-control" name="campana_nombre" id="campana_nombre" placeholder="Ingrese el nuevo nombre para la encuesta">
                        </div>
                    </div>
                    <?php if (Yii::app()->user->hasFlash('error')){ ?>
                        <div class="infos">
                            <?php echo Yii::app()->user->getFlash('error'); ?>
                        </div>
                    <?php } ?> 
                    <input type="submit" value="Enviar" class="btn btn-danger">
                </form>
            </div>
            </div>
          <div class="col-md-3 col-md-offset-1 cont_der">
            <p class="border-bt">Tambi&eacute;n puedes ir a:</p>
            <ul>
                <li><a href="<?php echo Yii::app()->createUrl('cquestionario/admin/'.$model->ccampana_id); ?>" class="seguimiento-btn">Administrador de encuestas</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></li>
                <li><a href="javascript:history.back(1)" class="back-btn-go">Volver</a></li>
            </ul>
        </div>
    </div>
   <div class="row">
        <div class="col-md-12 links-tabs links-footer">
            
             <div class="col-md-2"><p>Tambi&eacute;n puedes ir a:</p></div>
            <?php 
                if(!empty($accesosUser)){
                    foreach($accesosUser as $a){
            ?>
                    <?php if( ($a->accesoSistema->controlador) == 'pvQir' &&  ($a->accesoSistema->accion) ==  'admin'){?>   
                        <div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvQir/admin'); ?>" class="qir-btn"><span class="textoFEnlace">QIR</span></a></div>
                    <?php } ?>
                            
                    <?php if( ($a->accesoSistema->controlador) == 'pvboletinpostventa' &&  ($a->accesoSistema->accion) ==  'admin'){?>  
                        <div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvboletinpostventa/admin'); ?>" class="boletines-btn"><span class="textoFEnlace">Boletines</span></a></div>
                    <?php } ?>
                            
                    <?php if( ($a->accesoSistema->controlador) == 'pvvinMotor' &&  ($a->accesoSistema->accion) ==  'admin'){?>  
                        <div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvvinMotor/admin'); ?>" class="vin-btn"><span class="textoFEnlace">Vin-Motor</span></a></div>
                    <?php } ?>
                                
                    <?php if( ($a->accesoSistema->controlador) == 'pvmodelosposventa' &&  ($a->accesoSistema->accion) ==  'admin'){?>   
                        <div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvmodelosposventa/admin'); ?>" class="modelospv-btn"><span class="textoFEnlace">Modelos</span></a></div>
                    <?php } ?>
                            
                    <?php if( ($a->accesoSistema->controlador) == 'pvcodigoCausal' &&  ($a->accesoSistema->accion) ==  'admin'){?>  
                        <div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvcodigoCausal/admin'); ?>" class="causal-btn"><span class="textoFEnlace">C&oacute;digo Causal</span></a></div>
                    <?php } ?>
                            
                            
                    <?php if( ($a->accesoSistema->controlador) == 'pvcodigoNaturaleza' &&  ($a->accesoSistema->accion) ==  'admin'){?>  
                        <div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvcodigoNaturaleza/admin'); ?>" class="naturaleza-btn"><span class="textoFEnlace">C&oacute;digo Naturaleza</span></a></div>
                    <?php } ?>
                    
                    <?php if( ($a->accesoSistema->controlador) == 'uaccesosistema' &&  ($a->accesoSistema->accion) ==  'admin'){?>  
                        <div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('uaccesosistema/admin'); ?>" class="accesos-btn"><span class="textoFEnlace">Accesos al Sistema</span></a></div>
                    <?php } ?>
                    
                    <?php if( ($a->accesoSistema->controlador) == 'ucargo' &&  ($a->accesoSistema->accion) ==  'admin'){?>  
                        <div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('ucargo/admin'); ?>" class="cargos-btn"><span class="textoFEnlace">Cargos y Perfiles</span></a></div>
                    <?php } ?>
                    
                    <?php if( ($a->accesoSistema->controlador) == 'uusuarios' &&  ($a->accesoSistema->accion) ==  'admin'){?>   
                        <div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('uusuarios/admin'); ?>" class="usuarios-btn"><span class="textoFEnlace">Usuarios Kia</span></a></div>
                    <?php } ?>
                    
                    <?php if( ($a->accesoSistema->controlador) == 'uusuarios' &&  ($a->accesoSistema->accion) ==  'contactos'){?>   
                        <div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('uusuarios/contactos'); ?>" class="contactos-btn"><span class="textoFEnlace">Cont&aacute;ctos</span></a></div>
                    <?php } ?>
            
                    
            <?php
                    }
                }
            ?>
        </div>
    </div>
</div>