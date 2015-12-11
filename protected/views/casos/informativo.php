<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/css/ui.daterangepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/js/date.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/js/daterangepicker.jQuery.js"></script>
<?php
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
//echo 'dealer id: '.Yii::app()->user->getState('dealer_id').'<br>';
//echo 'rol: '.Yii::app()->user->getState('roles').'<br>';
?>
<script type="text/javascript">
    var abrir=0;
    $(function() {
        $( "#Casos_fecha" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: new Date(1950, 10 - 1, 25),
            yearRange: '2013:2018'
        });
        $( "#Casos_fecha2" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: new Date(1950, 10 - 1, 25),
            yearRange: '2013:2016'
        });
        $("#Casos_fecha2").hide();
        $('#Casos_tipo_fecha').change(function() {
            var value = $(this).attr('value');
            if(value == 'between'){
                $("#Casos_fecha2").show();
            }else{
                $("#Casos_fecha2").hide();
            }
            
        });
    });
    function verN(num){
        if(num > 0){
            if(abrir == 0){
                $("#lNotificaciones").show();
                abrir=1;
            }else{
                $("#lNotificaciones").hide();
                abrir=0;
	
            }
        }
        
    }
</script>    
<style>
    .form-search{
        padding: 0;
    }
</style>
<div class="cont_notificaciones">
    <?php
    if (Yii::app()->user->getState('roles') === 'super') {
        $criteria = new CDbCriteria(array(
                    'condition' => "leido='UNREAD' AND tipo_form = 'informativo'",
                    'order' => 'id desc'
                ));
    } 

    $notificaciones = Notificaciones::model()->findAll($criteria);
    $count = count($notificaciones);
    ?>
    <div class="cont_tl_notificaciones" onclick="verN(<?php echo $count; ?>)">
        <div class="no_notificaciones"><?php echo $count; ?></div>
        <div class="tl_notificaciones">Notificaciones</div>
        <div class="clear"></div>
    </div>
    <div id="lNotificaciones" style="display: none;">
        <?php
        $data = "<ul>";
        if ($count > 0):
            foreach ($notificaciones as $value) {
                $data .= '<li><a href="' . Yii::app()->createUrl('casos/vernotificacion', array('id' => $value['id'], 'caso_id' => $value['caso_id'])) . '" >' . $value['descripcion'] . '</a></li>';
            }
        endif;
        $data .= "</ul>";
        echo $data;
        ?>
    </div>
</div>
<div class="container">
    <div class="row">
        <h1 class="tl_seccion">Información de Casos</h1>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="highlight">
                <div class="form">
                    <h4>Filtrar por:</h4>
                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'casos-form',
                        'method' => 'get',
                        'action' => Yii::app()->createUrl('casos/search'),
                        'enableAjaxValidation' => true,
                        'htmlOptions' => array('class' => 'form-horizontal form-search')
                            ));
                    ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required" for="Casos_tema">Tema</label>
                        <div class="col-sm-4">
                            <select class="form-control" name="Casos[tema]" id="Casos_tema">
                                <option value="">Selecciona un tema</option>
                                <option value="7">Asistencia Kia</option>
                                <option value="">Selecciona un tema</option>
                                <option value="2">Colisiones</option>
                                <option value="1">Flotas</option>
                                <option value="3">Información Vehículos Nuevos</option>
                                <option value="5">Servicio Postventa</option>
                                <option value="6">Sugerencias e Inquietudes</option>
                                <option value="4">Vehículos Seminuevos</option>
                            </select>
                        </div>
                        <label class="col-sm-2 control-label required" for="Casos_tema">Subtema</label>
                        <div class="col-sm-4">
                            <select class="form-control" name="Casos[subtema]" id="Casos_subtema">
                                <option value="">Selecciona un subtema</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Tipo Fecha</label>
                        <div class="col-sm-3">
                            <select class="form-control" name="Casos[tipo_fecha]" id="Casos_tipo_fecha">
                                <option value="igual" selected>Igual</option>
                                <option value="between">Entre</option>
                            </select>
                            <input type="hidden" id="tipo_form" name="Casos[tipo_form]" value="informativo">
                        </div>
                        <label class="col-sm-1 control-label" for="Casos_estado">Fecha</label>
                        <div class="col-md-3">
                            <input size="10" maxlength="10" class="form-control" name="Casos[fecha]" id="Casos_fecha" type="text">
                        </div>
                        <div class="col-md-3">
                            <input size="10" maxlength="10" class="form-control" name="Casos[fecha2]" id="Casos_fecha2" type="text">
                        </div>
                    </div>
                    
                        
                        
                    
                    <div class="row buttons">
                        <?php //echo CHtml::submitButton($model->isNewRecord ? 'Buscar' : 'Save', array('class' => 'btn btn-danger'));  ?>
                        <input class="btn btn-danger" type="submit" name="yt0" value="Buscar">
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-md-offset-2">
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'casos-excel',
                'method' => 'post',
                'action' => Yii::app()->createUrl('casos/exportExcel'),
                'enableAjaxValidation' => true,
                'htmlOptions' => array('class' => 'form-horizontal form-search')
                    ));
            ?>
            <input type="hidden" name="Casos[tema]" id="Casos_tema" value="<?php if (!empty($_GET["Casos"]['tema'])) {
                echo $_GET["Casos"]['tema'];
            } else {
                echo '';
            } ?>">
            <input type="hidden" name="Casos[subtema]" id="Casos_subtema" value="<?php if (!empty($_GET["Casos"]['subtema'])) {
                echo $_GET["Casos"]['subtema'];
            } else {
                echo '';
            } ?>">
            <input type="hidden" name="Casos[fecha]" id="Casos_fecha" value="<?php if (!empty($_GET["Casos"]['fecha'])) {
                echo $_GET["Casos"]['fecha'];
            } else {
                echo '';
            } ?>">
            <input type="hidden" name="Casos[estado]" id="Casos_estado" value="<?php if (!empty($_GET["Casos"]['estado'])) {
                echo $_GET["Casos"]['estado'];
            } else {
                echo '';
            } ?>">
            <input type="hidden" name="Casos[ciudad]" id="Casos_ciudad" value="<?php if (!empty($_GET["Casos"]['ciudad'])) {
                echo $_GET["Casos"]['ciudad'];
            } else {
                echo '';
            } ?>">
            <input class="btn btn-primary" type="submit" name="yt0" value="Guardar en Excel">
<?php $this->endWidget(); ?>
<?php if ($case === 'default'): ?>
                        <!--<a href="<?php echo Yii::app()->createUrl('casos/exportExcel', array('param' => $case)); ?>" class="btn btn-primary">Guardar en Excel</a>-->
<?php endif; ?>
<?php if (isset($getParams)): ?> 
                        <!--<a href="<?php echo Yii::app()->createUrl('casos/exportExcel', array('param' => $getParams)); ?>" class="btn btn-primary">Guardar en Excel</a>-->
<?php endif; ?>    
        </div>
    </div>
    <div class="row">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tema</th>
                        <th>Subtema</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Cédula</th>
                        <th>Provincia</th>
                        <th>Ciudad</th>
                        <th>Teléfono</th>
                        <th>Celular</th>
                        <th>Email</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Edición</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
                <?php
                if (isset($searchCasos)):
                    //die('enter searchcasos');
                    foreach ($searchCasos as $c):
                        ?>
                        <tr>
                            <td><?php echo $c['id']; ?> </td>
                            <td><?php echo $this->getTema($c['tema']); ?> </td>
                            <td><?php echo $this->getSubtema($c['subtema']); ?> </td>
                            <td><?php echo $c['nombres']; ?> </td>
                            <td><?php echo $c['apellidos']; ?> </td>
                            <td><?php echo $c['cedula']; ?> </td>
                            <td><?php echo $this->getProvincia($c['provincia']); ?> </td>
                            <td><?php echo $this->getCiudad($c['ciudad']); ?> </td>
                            <td><?php echo $c['telefono']; ?> </td>
                            <td><?php echo $c['celular']; ?> </td>
                            <td><?php echo $c['email']; ?> </td>
                            <td><?php echo $c['estado']; ?> </td>
                            <td><?php echo $c['fecha']; ?> </td>
                            <td><a href="<?php echo Yii::app()->createUrl('casos/update', array('id' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-danger">Editar</a></td>
                        </tr>
        <?php
    endforeach;
else:
    ?>
                    <?php
                    //$model = Casos::model()->findAll();
                    foreach ($model as $c):
                        ?>
                        <tr>
                            <td><?php echo $c['id']; ?> </td>
                            <td><?php echo $this->getTema($c['tema']); ?> </td>
                            <td><?php echo $this->getSubtema($c['subtema']); ?> </td>
                            <td><?php echo $c['nombres']; ?> </td>
                            <td><?php echo $c['apellidos']; ?> </td>
                            <td><?php echo $c['cedula']; ?> </td>
                            <td><?php echo $this->getProvincia($c['provincia']); ?> </td>
                            <td><?php echo $this->getCiudad($c['ciudad']); ?> </td>
                            <td><?php echo $c['telefono']; ?> </td>
                            <td><?php echo $c['celular']; ?> </td>
                            <td><?php echo $c['email']; ?> </td>
                            <td><?php echo $c['estado']; ?> </td>
                            <td><?php echo $c['fecha']; ?> </td>
                            <td><a href="<?php echo Yii::app()->createUrl('casos/update', array('id' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-danger">Editar</a></td>
                        </tr>
        <?php
    endforeach;
endif;
?>
            </table>
        </div>

    </div>
    <div class="row">
        <div class="col-md-4">
<?php $this->widget('CLinkPager', array('pages' => $pages)); ?>
        </div>
        <div class="col-md-8 links-tabs">
            <div class="col-md-3"><p>También puedes ir a:</p></div>
            <div class="col-md-4"><a href="<?php echo Yii::app()->createUrl('casos/create'); ?>" class="creacion-btn">Creación de Casos</a></div>
            <div class="col-md-3"><a href="<?php echo Yii::app()->createUrl('casos/reportes'); ?>" class="reportes-btn">Reportes</a></div>
            <div class="col-md-1"><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></div>
<!--            <p>También puedes ir a:</p>
            <ul>
                <li><a href="<?php echo Yii::app()->createUrl('casos/create'); ?>" class="creacion-btn">Creación de Casos</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('casos/reportes'); ?>" class="reportes-btn">Reportes</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Atrás</a></li>
            </ul>-->
        </div>
    </div>
</div>