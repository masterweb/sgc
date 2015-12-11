<?php
/* @var $this GestionVehiculoController */
/* @var $model GestionVehiculo */

$this->breadcrumbs = array(
    'Gestion Vehiculos' => array('index'),
    $model->id,
);

$this->menu = array(
    array('label' => 'List GestionVehiculo', 'url' => array('index')),
    array('label' => 'Create GestionVehiculo', 'url' => array('create')),
    array('label' => 'Update GestionVehiculo', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete GestionVehiculo', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Manage GestionVehiculo', 'url' => array('admin')),
);
?>
<div class="container">
    <h1 class="tl_seccion">Ver Vehículo #<?php echo $model->id; ?></h1>

    <?php
    $this->widget('zii.widgets.CDetailView', array(
        'data' => $model,
        'attributes' => array(
            'id',
            'id_informacion',
            array(
                'name' => 'Modelo',
                'label' => 'Modelo',
                'value' => $this->getModel($model->modelo)
            ),
            'precio',
            'dispositivo',
            'accesorios',
            'seguro',
            'plazo',
            'total',
            'forma_pago',
            'fecha',
        ),
    ));
    ?>
</div>