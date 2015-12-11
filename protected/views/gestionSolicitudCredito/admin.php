<?php
/* @var $this GestionSolicitudCreditoController */
/* @var $model GestionSolicitudCredito */

$this->breadcrumbs = array(
    'Gestion Solicitud Creditos' => array('index'),
    'Manage',
);

$this->menu = array(
    array('label' => 'List GestionSolicitudCredito', 'url' => array('index')),
    array('label' => 'Create GestionSolicitudCredito', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#gestion-solicitud-credito-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<script type="text/javascript">
    $(function () {
        $("#keywords").tablesorter();
        $("#keywords2").tablesorter();
    });
    function aprobar(id) {
        if (confirm('Desea aprobar esta solicitud de crédito')) {
            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("gestionSolicitudCredito/aprobar"); ?>',
                /*beforeSend: function (xhr) {
                 $('#bg_negro').show();  // #bg_negro must be defined somewhere
                 },*/
                datatype: "json",
                type: 'POST',
                data: {id: id},
                success: function (data) {
                    var returnedData = JSON.parse(data);
                    // alert(returnedData.result);
                    location.reload();
                }
            });
        }

    }
    function aprobarhj(id) {
        if (confirm('Desea aprobar esta hoja de entrega')) {
            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("gestionSolicitudCredito/aprobarhj"); ?>',
                /*beforeSend: function (xhr) {
                 $('#bg_negro').show();  // #bg_negro must be defined somewhere
                 },*/
                datatype: "json",
                type: 'POST',
                data: {id: id},
                success: function (data) {
                    var returnedData = JSON.parse(data);
                    // alert(returnedData.result);
                    location.reload();
                }
            });
        }
    }
</script>

<?php
$id_asesor = Yii::app()->user->getId();
$concesionarioid = $this->getConcesionarioDealerId($id_asesor);
//die('conce: '.$concesionarioid);

if (isset($search)) {
    $sol = $users;
} else {
    $cr = new CDbCriteria(array(
        "condition" => "concesionario = {$concesionarioid} ",
        'order' => "id DESC"
    ));
    $sol = GestionSolicitudCredito::model()->findAll($cr);
}

?>
<div class="container">
    <div class="row">
        <h1 class="tl_seccion">Solicitudes de Crédito</h1>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="highlight">
                <div class="form">
                    <h4>Búsqueda:</h4>
                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'casos-form',
                        'method' => 'get',
                        'action' => Yii::app()->createUrl('gestionSolicitudCredito/admin'),
                        'enableAjaxValidation' => true,
                        'htmlOptions' => array(
                            'class' => 'form-horizontal form-search'
                        ),
                    ));
                    ?>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="GestionDiariafecha">Búsqueda General</label>
                            <input type="text" name="GestionSolicitudCredito[general]" id="GestionSolicitudCredito_general" class="form-control"/>
                        </div>
                        <div class="col-md-6">
                            <label for="GestionNuevaCotizacion_fuente">Status</label>
                            <select type="text" id="" name="GestionSolicitudCredito[status]" class="form-control">
                                <option value="">--Seleccione status--</option>
                                <option value="1">En Análisis</option>
                                <option value="2">Aprobado</option>
                                <option value="4">Condicionado</option>
                                <option value="3">Negado</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">


                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Responsable</label>
                            <select name="GestionSolicitudCredito[responsable]" id="" class="form-control">
                                <option value="">--Seleccione responsable--</option>
                                <option value="Jorge Rodriguez">Jorge Rodriguez</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="submit" name="" id="" value="Buscar" class="btn btn-danger"/>
                        </div>
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <h1 class="tl_seccion">RGD</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
        <div class="table-responsive">
            <table class="tables tablesorter" id="keywords">
                <thead>
                    <tr>
                        <th><span>ID</span></th>
                        <th><span>Nombres</span></th>
                        <th><span>Apellidos</span></th>
                        <th><span>Identificación</span></th>
                        <th><span>Responsable</span></th>
                        <th><span>Email</span></th>
                        <th><span>Ver</span></th>
                        <th><span>Status</span></th>
                        <th><span>Edición</span></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sol as $c): ?>
                        <tr>
                            <td><?php echo $c['id_informacion']; ?></td>
                            <td><?php echo $c['nombres']; ?></td>
                            <td><?php echo $c['apellido_paterno'] . ' ' . $c['apellido_materno']; ?></td>
                            <td>
                                <?php 
                                 
                                if($c['cedula'] != ''){
                                   echo $c['cedula']; 
                                }
                                if($c['pasaporte'] != ''){
                                   echo $c['pasaporte'];
                                }
                                if($c['ruc'] != ''){
                                   echo $c['ruc']; 
                                }
                                ?> 
                            </td>
                            <td><?php echo $this->getResponsable($c['vendedor']); ?></td>
                            <td><?php echo $c['email']; ?></td>
                            <td><a href="<?php echo Yii::app()->createUrl('gestionSolicitudCredito/cotizacion/', array('id_informacion' => $c['id_informacion'], 'id_vehiculo' => $c['id_vehiculo'])); ?>" class="btn btn-info btn-xs" target="_blank">Solicitud</a></td>
                            <td>
                                <?php
                                $status = $this->getStatusSolicitud($c['id_informacion'], $c['id_vehiculo']);
                                //echo 'status: '.$status;
                                switch ($status) {
                                    case 'na':
                                        echo '<a class="btn btn-warning btn-xs" target="_blank">En Análisis</a>';
                                        break;
                                    case 1:
                                        echo '<a class="btn btn-warning btn-xs" target="_blank">En Análisis</a>';
                                        break;
                                    case 2:
                                        echo '<a class="btn btn-success btn-xs" target="_blank">Aprobado</a>';
                                        break;
                                    case 3:
                                        echo '<a class="btn btn-danger btn-xs" target="_blank">Negado</a>';
                                        break;
                                    case 4:
                                        echo '<a class="btn btn-danger btn-xs" target="_blank">Condicionado</a>';
                                        break;
                                    default:
                                        break;
                                }
                                ?>
                            </td>
                            <td>
                                <?php if ($status == 1 || $status == 4 || $status == 'na') { ?>
                                    <a href="<?php echo Yii::app()->createUrl('gestionSolicitudCredito/status/', array('id' => $c['id'], 'id_informacion' => $c['id_informacion'], 'id_vehiculo' => $c['id_vehiculo'], 'id_status' => $c['id'])); ?>" class="btn btn-primary btn-xs">Ingresar</a>
                                <?php } else { ?>

                                <?php } ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
            </div>
    </div>
    <div class="row">
        <h1 class="tl_seccion">Hojas de Entrega</h1>
    </div>
    <?php
    $con = Yii::app()->db;
    $sql = "SELECT gi.nombres, gi.apellidos, gi.email,gi.cedula,ge.*, gc.`status` from gestion_hoja_entrega_solicitud ge 
INNER JOIN gestion_informacion gi ON ge.id_informacion = gi.id 
INNER JOIN gestion_solicitud_credito gc ON gc.id_informacion = gi.id 
WHERE gc.`status` = 1 ORDER BY gc.`status` = 'pendiente'";
    $request = $con->createCommand($sql)->query();
    ?>
    <div class="row">
        <div class="col-md-12">
        <div class="table-responsive">
            <table class="tables tablesorter" id="keywords2">
                <thead>
                    <tr>
                        <th><span>ID</span></th>
                        <th><span>Nombres</span></th>
                        <th><span>Apellidos</span></th>
                        <th><span>Email</span></th>
                        <th><span>Cédula</span></th>
                        <th><span>Hoja de Entrega</span></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($request as $key => $c): ?>
                        <tr>
                            <td><?php echo $c['id_informacion']; ?></td>
                            <td><?php echo $c['nombres']; ?></td>
                            <td><?php echo $c['apellidos']; ?></td>
                            <td><?php echo $c['email']; ?></td>
                            <td><?php echo $c['cedula']; ?></td>
                            <td>
                                <?php
                                if ($c['status'] == 'aprobado'):
                                    ?>
                                    <a class="btn btn-success btn-xs">Aprobado</a>
                                <?php else: ?>
                                    <a class="btn btn-danger btn-xs" onclick="aprobarhj(<?php echo $c['id']; ?>);">Aprobar</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
            </div>
    </div>
</div>
