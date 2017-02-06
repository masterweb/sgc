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
$cargo_id = (int) Yii::app()->user->getState('cargo_id');
$id_responsable = Yii::app()->user->getId();
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
            yearRange: '2013:2017'
        });
        $( "#Casos_fecha2" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: new Date(1950, 10 - 1, 25),
            yearRange: '2013:2017'
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
    .form-search{padding: 0;}
</style>
<div class="cont_notificaciones">
    <?php
    /* $criteria = new CDbCriteria(array(
      'condition' => "leido='UNREAD' AND tipo_form = 'caso'",
      'order' => 'id desc'
      ));


      $notificaciones = Notificaciones::model()->findAll($criteria); */

    $sql = "SELECT DISTINCT n.id as id,n.caso_id as caso_id,c.estado as estado, n.descripcion as descripcion, c.comentario as comentario FROM notificaciones n 	inner join casos c on c.id = n.caso_id where n.leido='UNREAD' and n.tipo_form='caso' order by  c.estado,n.id DESC";
    $notificaciones = Yii::app()->db->createCommand($sql)->query();
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
        <div class="leido" onclick="leidas()">Marcar todo como leído</div>
        <div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
            <ul id="myTab" class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#abierto" id="abierto-tab" role="tab" data-toggle="tab" aria-controls="abierto" aria-expanded="true">Abierto</a></li>
                <li role="presentation" class=""><a href="#proceso" role="tab" id="proceso-tab" data-toggle="tab" aria-controls="proceso" aria-expanded="false">En Proceso</a></li>
                <li role="presentation" class=""><a href="#cerrado" role="tab" id="cerrado-tab" data-toggle="tab" aria-controls="cerrado" aria-expanded="false">Cerrado</a></li>
            </ul>
            <div id="myTabContent" class="tab-content">
                <div role="tabpanel" class="tab-pane fade active in" id="abierto" aria-labelledby="abierto-tab">
                    <?php
                    $abierto = "";
                    $abiertoSQL = "SELECT DISTINCT n.id as id,n.caso_id as caso_id,c.estado as estado, n.descripcion as descripcion, c.comentario as comentario FROM notificaciones n 	inner join casos c on c.id = n.caso_id where n.leido='UNREAD' and n.tipo_form='caso' and c.estado ='Abierto' order by  c.estado,n.id DESC limit 10";
                    $notificacionesAbiertas = Yii::app()->db->createCommand($abiertoSQL)->query();
                    echo '<ul id="lAbierto">';
                    if (count($notificacionesAbiertas) > 0) {
                        foreach ($notificacionesAbiertas as $value) {
                            $abierto .= '<li class="tol" data-toggle="tooltip" data-placement="top" title="' . utf8_decode(utf8_encode(utf8_decode(substr(ucfirst(strtolower($value["comentario"])), 0, 380)))) . '"><a href="' . Yii::app()->createUrl('casos/vernotificacion/id/' . $value['id'] . '/caso_id/' . $value['caso_id']) . '" >' . $value['descripcion'] . '</a></li>';
                        }
                        echo $abierto;

                        echo '</ul>';
                        echo '<input type="hidden" id="actualAbierto" value="10">';
                        echo '<div id="vAbierto" class="mas" onclick=\'traerMas("Abierto")\'><span>VER M&Aacute;S</span></div>';
                    } else {
                        echo "No existen notificaciones para mostrar.";
                    }
                    ?>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="proceso" aria-labelledby="proceso-tab">
                    <?php
                    $proceso = "";
                    $procesoSQL = "SELECT DISTINCT n.id as id,n.caso_id as caso_id,c.estado as estado, n.descripcion as descripcion, c.comentario as comentario FROM notificaciones n 	inner join casos c on c.id = n.caso_id where n.leido='UNREAD' and n.tipo_form='caso' and c.estado ='Proceso' order by  c.estado,n.id DESC limit 10";
                    $notificacionesProceso = Yii::app()->db->createCommand($procesoSQL)->query();
                    if (count($notificacionesProceso) > 0) {
                        echo '<ul id="lProceso">';
                        foreach ($notificacionesProceso as $value) {
                            $proceso .= '<li class="tol" data-toggle="tooltip" data-placement="top" title="' . utf8_decode(utf8_encode(utf8_decode(substr(ucfirst(strtolower($value["comentario"])), 0, 380)))) . '"><a href="' . Yii::app()->createUrl('casos/vernotificacion/id/' . $value['id'] . '/caso_id/' . $value['caso_id']) . '" >' . $value['descripcion'] . '</a></li>';
                        }
                        echo $proceso;

                        echo '<input type="hidden" id="actualProceso" value="10">';
                        echo '</ul>';
                        echo '<div id="vProceso" class="mas" onclick=\'traerMas("Proceso")\'><span>VER M&Aacute;S</span></div>';
                    } else {
                        echo "No existen notificaciones para mostrar.";
                    }
                    ?>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="cerrado" aria-labelledby="cerrado-tab">

                    <?php
                    $cerrado = "";
                    $cerradoSQL = "SELECT DISTINCT n.id as id,n.caso_id as caso_id,c.estado as estado, n.descripcion as descripcion, c.comentario as comentario FROM notificaciones n 	inner join casos c on c.id = n.caso_id where n.leido='UNREAD' and n.tipo_form='caso' and c.estado ='Cerrado' order by  c.estado,n.id DESC limit 10";
                    $notificacionesCerradas = Yii::app()->db->createCommand($cerradoSQL)->query();
                    if (count($notificacionesCerradas) > 0) {
                        echo '<ul id="lCerrado">';
                        foreach ($notificacionesCerradas as $value) {
                            $cerrado .= '<li class="tol" data-toggle="tooltip" data-placement="top" title="' . utf8_decode(utf8_encode(utf8_decode(substr(ucfirst(strtolower($value["comentario"])), 0, 380)))) . '"><a href="' . Yii::app()->createUrl('casos/vernotificacion/id/' . $value['id'] . '/caso_id/' . $value['caso_id']) . '" >' . $value['descripcion'] . '</a></li>';
                        }
                        echo $cerrado;


                        echo '</ul>';
                        echo '<div id="vCerrrado" class="mas" onclick=\'traerMas("Cerrado")\'><span>VER M&Aacute;S</span></div>';
                        echo '<input type="hidden" id="actualCerrado" value="10">';
                    } else {
                        echo "No existen notificaciones para mostrar.";
                    }
                    ?>

                </div>
            </div>
        </div>
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
            <?php //if (($cargo_id === 83) || (Yii::app()->user->getState('first_name') === 'Supervisor') || (Yii::app()->user->getState('roles') === 'admin')): ?>
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
                                   echo '<input type="hidden" name="Casos[estado' . $i . ']" id="Casos_estado" value="' . $_GET["Casos"]['estado'][$i] . '">';
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
            <?php //endif; ?>   
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
                        <th><span>Tipo de Identificación</span></th>
                        <th><span>No. de Identificación</span></th>
                        <th><span>Ciudad</span></th>
                        <th><span>Concesionario</span></th>
                        <th><span>Responsable</span></th>
                        <th><span>Estado</span></th>
                        <th><span>Fecha</span></th>
                        <?php if ($cargo_id == 83) : ?>
                            <th>Edición</th>
                        <?php elseif ($cargo_id == 82): ?>
                            <th>Ver</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $ident;
                    $tipoIden;
                    foreach ($model as $c):
                        if ($c['concesionario'] == NULL) {
                            $concesionario = 'NA';
                        } else {
                            $concesionario = $this->getConcesionario($c['concesionario']);
                        }
                        switch ($c['identificacion']) {
                            case 'ci':
                                $ident = $c['cedula'];
                                $tipoIden = 'Cédula';
                                break;
                            case 'ruc':
                                $ident = $c['ruc'];
                                $tipoIden = 'Ruc';
                                break;
                            case 'pasaporte':
                                $ident = $c['pasaporte'];
                                $tipoIden = 'Pasaporte';
                                break;
                            default:
                                break;
                        }
                        ?>
                        <tr>
                            <td><?php echo $c['id']; ?> </td>
                            <td><?php
                                    switch ($c['origen']) {
                                        case 0:
                                            echo '1800';
                                            break;
                                        case 1:
                                            echo 'web';
                                            break;
                                        case 2:
                                            echo 'sgc';
                                            break;

                                        default:
                                            break;
                                    }
                            //echo ($c['origen'] == 0) ? '1800' : 'web' 
                            ?> 
                            </td>
                            <td><?php echo $this->getTema($c['tema']); ?> </td>
                            <td><?php echo $this->getSubtema($c['subtema']); ?> </td>
                            <td><?php echo $c['nombres']; ?> </td>
                            <td><?php echo $c['apellidos']; ?> </td>
                            <td><?php echo $tipoIden; ?> </td>
                            <td><?php echo $ident; ?> </td>
                            <td><?php echo $this->getCiudad($c['ciudad']); ?> </td>
                            <td><?php echo $concesionario; ?></td>
                            <td><?php if ($c['responsable'] == 0): echo 'Web' ?>
                                <?php else: ?>
                                    <?php echo $this->getResponsable($c['responsable']); ?>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $c['estado']; ?> </td>
                            <td><?php echo $c['fecha']; ?> </td>
                            <?php if ($cargo_id == 83 || $cargo_id == 82) : ?>
                                <td><a href="<?php echo Yii::app()->createUrl('casos/update', array('id' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-danger">Editar</a></td>
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
        <div class="col-md-8">
            <?php $this->widget('CLinkPager', array('pages' => $pages, 'maxButtonCount' => 5)); ?>
        </div>
        <br /><br />
        <div class="col-md-12 links-tabs">
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
            <?php if ($cargo_id === 83 || $rol === 'admin' || $rol === 'super'): ?><div class="col-md-3"><a href="<?php echo Yii::app()->createUrl('casos/create'); ?>" class="creacion-btn">Registro de Llamadas</a></div><?php endif; ?>
            <?php if ($cargo_id === 83 || $rol === 'admin' || $rol === 'super'): ?><div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('casos/reportes'); ?>" class="reportes-btn">Reportes</a></div><?php endif; ?>
            <div class="col-md-1"><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></div>

        </div>
    </div>
</div>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
    function traerMas(vl){
        var numero = 0;
        switch(vl){
            case 'Abierto':
                numero = $("#actualAbierto").val();
                break;
            case 'Proceso':
                numero = $("#actualProceso").val();
                break;
            case 'Cerrado':
                numero = $("#actualCerrado").val();
                break;
        }

        $.ajax({
            url: '<?php echo Yii::app()->createUrl("site/traernotificaciones") ?>',
            type:'POST',
            async:true,
            data:{
                vl : vl,
                numero : numero,
            },
            success:function(result){
                if(result != 0){
                    var data = result.split("|--|");
                    $("#actual"+vl).val(data[1]);
                    $("#l"+vl+":last").after(data[0]);
                }else{
                    $("#l"+vl+":last").after('<div class="noMas">No hay más notificaciones para mostrar.</div>');
                    $("#v"+vl).hide();
                }
            }
        });
    }
    function leidas(){
        if(confirm("Esta seguro que marcar todo como leído?")){
            $.ajax({
                url: '<?php echo Yii::app()->createUrl("site/marcarleido") ?>',
                type:'POST',
                async:true,
                data:{
                },
                success:function(result){
                    if(result != 0){
                        $(".no_notificaciones").html('0');
                        $("#lNotificaciones").hide();
                    }
                }
            });
        }
    }
</script>