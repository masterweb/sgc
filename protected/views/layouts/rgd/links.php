<?php
$cargo_id = (int) Yii::app()->user->getState('cargo_id');
$url = Yii::app()->createUrl('gestionInformacion/seguimiento');
switch ($cargo_id) {
    case 71: // asesor ventas
    case 70: // jefe sucursal
        $url = Yii::app()->createUrl('gestionInformacion/seguimiento');
        break;
    case 77: // asesor usados
        $url = Yii::app()->createUrl('gestionInformacion/seguimientousados');
        break;
    case 75: // asesor exonerados
        $url = Yii::app()->createUrl('gestionInformacion/seguimientoexonerados');
        break;
    case 73: // asesor bdc
        $url = Yii::app()->createUrl('gestionInformacion/seguimientobdc');
        break;
    case 71:
        break;

    default:
        break;
}
?>
<div class="row">
    <div class="col-md-8 col-xs-12 links-tabs">
        <div class="col-md-2 col-xs-4"><p>También puedes ir a:</p></div>
        <div class="col-md-2 col-xs-4"><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></div>
        <div class="col-md-2 col-xs-4"><a href="<?php echo $url; ?>" class="creacion-btn">RGD</a></div>
        <div class="col-md-3 col-xs-4"><a href="<?php echo Yii::app()->createUrl('uusuarios/contactos'); ?>" class="directorio-btn">Directorio de Contactos</a></div>
    </div>
</div>

