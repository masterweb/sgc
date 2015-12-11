<?php

$fecha = date('2015-02-09 01:55:51');
$nuevafecha = strtotime ( '-5 hour' , strtotime ( $fecha ) ) ;
$nuevafecha = date ( 'Y-m-d H:i:s' , $nuevafecha );

echo $nuevafecha;
?>
