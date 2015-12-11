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
//echo 'id call center: '.Yii::app()->user->getId().'<br>';
//echo 'rol: '.Yii::app()->user->getState('roles').'<br>';
$rol = Yii::app()->user->getState('roles');
?>
<script type="text/javascript">
    var abrir=0;
    $(function() {
        $("#keywords").tablesorter();
        $( "#Casos_fecha" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: new Date(1950, 10 - 1, 25),
            yearRange: '2013:2016'
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
    $criteria = new CDbCriteria(array(
                'condition' => "leido='UNREAD' AND tipo_form = 'caso'",
                'order' => 'id desc'
            ));


    $notificaciones = Notificaciones::model()->findAll($criteria);
    $count = count($notificaciones);
    $criteria2 = new CDbCriteria(array(
                'order' => 'name'
            ));
    $menu = CHtml::listData(Menu::model()->findAll($criteria2), "id", "name");
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
                $data .= '<li><a href="' . Yii::app()->createUrl('casos/vernotificacion/id/' . $value['id'] . '/caso_id/' . $value['caso_id']) . '" >' . $value['descripcion'] . '</a></li>';
            }
        endif;
        $data .= "</ul>";
        echo $data;
        ?>
    </div>
</div>

<div class="container">
    <div class="row">
        <h1 class="tl_seccion">Seguimiento de Casos</h1>
    </div>
    <div class="row">
        <!-- FORMULARIO DE BUSQUEDA -->
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
                        </div>
                        <label class="col-sm-1 control-label" for="Casos_estado">Fecha</label>
                        <div class="col-md-3">
                            <input size="10" maxlength="10" class="form-control" name="Casos[fecha]" id="Casos_fecha" type="text">
                        </div>
                        <div class="col-md-3">
                            <input size="10" maxlength="10" class="form-control" name="Casos[fecha2]" id="Casos_fecha2" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="Casos_estado">Estado</label>
                        <div class="col-sm-4">
                            <select class="form-control" name="Casos[estado][]" id="Casos_estado" multiple>
                                <option value="ABIERTO">Abierto</option>
                                <option value="PROCESO">En Proceso</option>
                                <option value="CERRADO">Cerrado</option>
                            </select>
                        </div>
                        <input type="hidden" id="tipo_form" name="Casos[tipo_form]" value="caso">
                    </div>
                    <div class="row buttons">
                        <?php //echo CHtml:submitButton($model->isNewRecord ? 'Buscar' : 'Save', array('class' => 'btn btn-danger'));     ?>
                        <input class="btn btn-danger" type="submit" name="yt0" value="Buscar">
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
        <!-- FIN DE FORMULARIO DE BUSQUEDA -->
        <div class="col-md-4">
            <?php if ((Yii::app()->user->getState('roles') === 'super') || (Yii::app()->user->getState('first_name') === 'Supervisor') || (Yii::app()->user->getState('roles') === 'admin')): ?>
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'casos-excel',
                    'method' => 'post',
                    'action' => Yii::app()->createUrl('casos/exportExcel'),
                    'enableAjaxValidation' => true,
                    'htmlOptions' => array('class' => 'form-horizontal form-search')
                        ));
                ?>
                <input type="hidden" name="Casos[tema]" id="Casos_tema" value="<?php
            if (!empty($_GET["Casos"]['tema'])) {
                echo $_GET["Casos"]['tema'];
            } else {
                echo '';
            }
                ?>">
                <input type="hidden" name="Casos[subtema]" id="Casos_subtema" value="<?php
                   if (!empty($_GET["Casos"]['subtema'])) {
                       echo $_GET["Casos"]['subtema'];
                   } else {
                       echo '';
                   }
                ?>">
                <input type="hidden" name="Casos[fecha]" id="Casos_fecha" value="<?php
                   if (!empty($_GET["Casos"]['fecha'])) {
                       echo $_GET["Casos"]['fecha'];
                   } else {
                       echo '';
                   }
                ?>">
                <input type="hidden" name="Casos[fecha2]" id="Casos_fecha" value="<?php
                   if (!empty($_GET["Casos"]['fecha2'])) {
                       echo $_GET["Casos"]['fecha2'];
                   } else {
                       echo '';
                   }
                ?>">
                       <?php
                       if (isset($_GET["Casos"]['estado'])) {
                           $number_estados = count($_GET["Casos"]['estado']);
                           if ($number_estados > 1) {
                               for ($i = 0; $i < count($_GET["Casos"]['estado']); $i++) {
                                   echo '<input type="hidden" name="Casos[estado'.$i.']" id="Casos_estado" value="' . $_GET["Casos"]['estado'][$i] . '">';
                               }
                           } else {
                               echo '<input type="hidden" name="Casos[estado0]" id="Casos_estado" value="' . $_GET["Casos"]['estado'][0] . '">';
                           }
                       }
                       ?>
                <input type="hidden" name="Casos[ciudad]" id="Casos_ciudad" value="<?php
                   if (!empty($_GET["Casos"]['ciudad'])) {
                       echo $_GET["Casos"]['ciudad'];
                   } else {
                       echo '';
                   }
                       ?>">
                <input class="btn btn-primary" type="submit" name="yt0" value="Guardar en Excel" style="float: right;">
                <?php $this->endWidget(); ?>
            <?php endif; ?>   
        </div>
    </div>
    <div class="row">
        <div class="col-md-9">
            <?php
            if (isset($title_busqueda)):
                echo '<h4 style="color:#AA1F2C;border-bottom: 1px solid;">' . $title_busqueda . '</h4>';
            endif;
            ?>
        </div>
    </div>
    <div class="row">
        <div class="table-responsive">
            <table class="tables tablesorter" id="keywords">
                <thead>
                    <tr>
                        <th><span>ID</span></th>
                        <th><span>Tipo</span></th>
                        <th><span>Tema</span></th>
                        <th><span>Subtema</span></th>
                        <th><span>Nombres</span></th>
                        <th><span>Apellidos</span></th>
                        <th><span>Cédula</span></th>
                        <th><span>Ciudad</span></th>
                        <th><span>Concesionario</span></th>
                        <th><span>Responsable</span></th>
                        <th><span>Estado</span></th>
                        <th><span>Fecha</span></th>
                        <?php if (Yii::app()->user->getState('roles') === 'admin' || Yii::app()->user->getState('roles') === 'super' || Yii::app()->user->getState('roles') === 'adminvpv') : ?>
                            <th>Edición</th>
                        <?php elseif (Yii::app()->user->getState('roles') === 'asesor'): ?>
                            <th>Ver</th>
<?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
//$model = Casos::model()->findAll();
                    foreach ($model as $c):
                        if ($c['concesionario'] == NULL) {
                            $concesionario = 'NA';
                        } else {
                            $concesionario = $this->getConcesionario($c['concesionario']);
                        }
                        ?>
                        <tr>
                            <td><?php echo $c['id']; ?> </td>
                            <td><?php echo ($c['origen'] == 0) ? '1800' : 'web' ?> </td>
                            <td><?php echo $this->getTema($c['tema']); ?> </td>
                            <td><?php echo $this->getSubtema($c['subtema']); ?> </td>
                            <td><?php echo $c['nombres']; ?> </td>
                            <td><?php echo $c['apellidos']; ?> </td>
                            <td><?php echo $c['cedula']; ?> </td>
                            <td><?php echo $this->getCiudad($c['ciudad']); ?> </td>
                            <td><?php echo $concesionario; ?></td>
    <!--                        <td><?php // echo $this->getConcesionario($c['concesionario']);  ?> </td>-->
                            <td><?php if ($c['responsable'] == 0): echo 'Web' ?>
                                <?php else: ?>
        <?php echo $this->getResponsable($c['responsable']); ?>
    <?php endif; ?>
                            </td>
                            <td><?php echo $c['estado']; ?> </td>
                            <td><?php echo $c['fecha']; ?> </td>
                            <?php if (Yii::app()->user->getState('roles') === 'admin' || Yii::app()->user->getState('roles') === 'super' || Yii::app()->user->getState('roles') === 'adminvpv') : ?>
                                <td><a href="<?php echo Yii::app()->createUrl('casos/update', array('id' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-danger">Editar</a></td>
                            <?php elseif (Yii::app()->user->getState('roles') === 'asesor'): ?>
                                <td><a href="<?php echo Yii::app()->createUrl('casos/view', array('id' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-danger">Ver</a></td>
                        <?php endif; ?>

                        </tr>
                        <?php
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>

    </div>
    <div class="row">
        <div class="col-md-4">
<?php $this->widget('CLinkPager', array('pages' => $pages, 'maxButtonCount' => 5)); ?>
        </div>
        <div class="col-md-8 links-tabs">
            <div class="col-md-3">
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'casos-form-search',
                    'method' => 'get',
                    'action' => Yii::app()->createUrl('site/busqueda'),
                    'htmlOptions' => array('class' => 'form-search-case')
                        ));
                ?>
                <div id="custom-search-input">
                    <div class="input-group col-md-12">
                        <input type="text" class="search-query form-control" placeholder="Buscar" name="buscar"/>
                        <span class="input-group-btn">
                            <button class="btn btn-danger btn-search" type="submit">
                                <span class=" glyphicon glyphicon-search"></span>
                            </button>
                        </span>
                    </div>
                </div>
            <?php $this->endWidget(); ?>
            </div>
            <div class="col-md-2"><p>También puedes ir a:</p></div>
<?php if ($rol === 'admin' || $rol === 'super'): ?><div class="col-md-3"><a href="<?php echo Yii::app()->createUrl('casos/create'); ?>" class="creacion-btn">Registro de Llamadas</a></div><?php endif; ?>
<?php if ($rol === 'admin' || $rol === 'super'): ?><div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('casos/reportes'); ?>" class="reportes-btn">Reportes</a></div><?php endif; ?>
            <div class="col-md-1"><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></div>

        </div>
    </div>
</div>