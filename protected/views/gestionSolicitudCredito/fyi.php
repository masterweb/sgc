<?php 
$id_asesor = Yii::app()->user->getId();
$concesionarioid = $this->getConcesionarioDealerId($id_asesor);
$cargo_id = (int) Yii::app()->user->getState('cargo_id');
$grupo_id = (int) Yii::app()->user->getState('grupo_id');



//$exh = GestionInformacion::model()->findAll($sql);

?>
<style>
    .tl_seccion{margin-left: 0px !important;}
</style>
 <div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="tl_seccion">Solicitudes de Crédito</h1>
        </div>
        
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
                        'action' => Yii::app()->createUrl('gestionSolicitudCredito/fyi'),
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
        <div class="col-md-12">
            <h1 class="tl_seccion">RGD</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="tables tablesorter" id="keywords">
                    <thead>
                        <tr>
                            <th><span>ID</span></th>
                            <th><span>Nombres Apellidos</span></th>
                            <th><span>Identificación</span></th>
                            <th><span>Responsable FYI</span></th>
                            <th><span>Responsable Asesor</span></th>
                            <th><span>Concesionario</span></th>
                            <th><span>Status SC</span></th>
                            <th><span>&nbsp;</span></th>
                            <th><span>Edición</span></th>
                            <th>Firma</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($exh as $value) { ?>
                            <tr>
                                <td><?php echo $value['id_informacion']; ?></td>
                                <td><?php echo $this->getNombreCliente($value['id_informacion']); ?></td>
                                <td>
                                    <?php
                                    $identificacion = $this->getIdentificacionTipoFYI($value['id_informacion']);
                                    echo $identificacion;
                                    //if ($value['cedula'] != '') {
                                    //    echo $value['cedula'];
                                    //}
                                    //if ($value['pasaporte'] != '') {
                                    //    echo $value['pasaporte'];
                                    //}
                                    //if ($value['ruc'] != '') {
                                    //    echo $value['ruc'];
                                    //}
                                    ?> 
                                </td>
                                <td><?php 
                                    $id_editor = $this->getResponsableEditSc($value['id_informacion'], $value['id']);
                                    if(!empty($id_editor)){
                                        echo $this->getResponsableFyi($id_editor);
                                    } 
                                     
                                    ?>
                                </td>
                                <td><?php echo $this->getResponsableInformacion($value['id_informacion']); ?></td>
                                <td><?php echo $this->getConcesionarioFYI($value['id_informacion']); ?></td>
                                <td>
                                    <?php
                                    $status = $this->getStatusSolicitud($value['id_informacion'], $value['id']);
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
                                    <?php
                                    $sc = GestionSolicitudCredito::model()->count(array('condition' => "id_informacion = {$value['id_informacion']} AND id_vehiculo = {$value['id']}")); 
                                    if($sc > 0){
                                        $scc = GestionSolicitudCredito::model()->findAll(array('condition' => "id_informacion = {$value['id_informacion']} AND id_vehiculo = {$value['id']}",'limit'=>1,));
                                        foreach ($scc as $key) {
                                            echo '<a href="'. Yii::app()->createUrl('gestionSolicitudCredito/status/', array('id' => $key['id'], 'id_informacion' => $key['id_informacion'], 'id_vehiculo' => $key['id_vehiculo'], 'id_status' => $key['id'])).'" class="btn btn-primary btn-xs">Ver</a>';
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    $sc = GestionSolicitudCredito::model()->count(array("condition" => "id_informacion = {$value['id_informacion']} AND id_vehiculo = {$value['id']}"));
                                    if($sc > 0):
                                    ?>
                                    <a href="<?php echo Yii::app()->createUrl('gestionSolicitudCredito/update/', array('id_informacion' => $value['id_informacion'], 'id_vehiculo' => $value['id'])); ?>" class="btn btn-primary btn-xs btn-danger">Editar</a>
                                    <?php else: ?>
                                    <a href="<?php echo Yii::app()->createUrl('gestionSolicitudCredito/create/', array('id_informacion' => $value['id_informacion'], 'id_vehiculo' => $value['id'])); ?>" class="btn btn-primary btn-xs btn-danger">Ingresar datos</a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $firma = GestionFirma::model()->count(array('condition' => "id_informacion = {$value['id_informacion']}"));
                                    if($firma > 0){
                                        echo '<button type="button" class="btn btn-primary btn-xs btn-warning">Si</button>';
                                    }else{
                                        echo '<button type="button" class="btn btn-primary btn-xs btn-danger">No</button>';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-9">
            <?php $this->widget('CLinkPager', array('pages' => $pages, 'maxButtonCount' => 10)); ?>
        </div>
    </div>
 </div>