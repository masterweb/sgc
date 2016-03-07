<?php

$fecha = date('2015-02-09 01:55:51');
$nuevafecha = strtotime ( '-5 hour' , strtotime ( $fecha ) ) ;
$nuevafecha = date ( 'Y-m-d H:i:s' , $nuevafecha );

//echo $nuevafecha;

$dt = time();
$fecha_actual = strftime("%Y-%m-%d", $dt);
$year_actual = explode('-',$fecha_actual);
echo $year_actual[0];
//echo $fecha_actual;
?>
