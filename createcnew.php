<?php

ini_set('display_errors', 1);
$uriservicio = "http://200.31.10.92/wsa/wsa1/wsdl?targetURI=urn:aekia";
$params = array(
    "cidentifica_cliente" => '1791282264001',
    "cchasis" => '8LCDC22323E000003',
    "xmlres" => '',
    "xmlerror" => '',
);
//echo "<pre>".htmlentities(print_r($params,true))."</pre>";

$client = new SoapClient(@$uriservicio, array('trace' => 1));
//$response = $client->pws01_01_cl('','KNCWJX72AF7939017');
//$response = $client->pws01_01_cl('1702509751', '');
$response = $client->pws01_01_cl('0992786906001', '');
//$cliente= $response->RecuperarDatosIndividualResult->codigo;
echo "<pre>" . htmlentities(print_r($response, true)) . "</pre>";
//echo '<pre>' . htmlentities($response['lcxml']) . '</pre>';
//echo '---------------------</br>';
$xml = simplexml_load_string($response['lcxml']);
$params = explode('<ttvh01>', $response['lcxml']);
//echo htmlentities($params[1]);
//echo date('Y-m-d H:i:s');

$datos_search = array(
    'chasis' => 'No. Chasis', 'nombre_propietario' => 'Nombre Propietario', 'color_vehiculo' => 'Color Vehículo',
    'fecha_retail' => 'Fecha de Venta', 'anio_modelo' => 'Año', 'color_origen' => 'Color de Orígen' ,
    'numero_id_propietario' => 'Id del Propietario','precio_venta' => 'Precio de Venta',
    'calle_principal_propietario' => 'Calle Principal', 'numero_calle_propietario' => 'Número de Calle',
    'telefono_propietario' => 'Teléfono del Propietario', 'grupo_concesionario' => 'Grupo Concesionario',
    'forma_pago_retail' => 'Forma de Pago' 
);

/*foreach ($datos_search as $key => $value) {
    // primero encontramos la posicion de la cadena a encontrar
    $pos = strpos($params[1], '<'.$key.'>');
// encontramos la posicion final de la cadena final
    $posfinal = strpos($params[1], '</'.$key.'>');
// longitud de la cadena a impriimir
    $longitud_cadena = $posfinal - $pos;
// cadena a imprimir, con posicion inicial y longitud
    $str = substr($params[1], $pos, $longitud_cadena);

    echo $value.': '.$str.'<br>';
}*/

?>


