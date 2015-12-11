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
//$response = $client->pws01_01_cl('8LCDC22323E000003');
$response = $client->pws01_01_cl('1715415152', '');
//$cliente= $response->RecuperarDatosIndividualResult->codigo;
echo "<pre>" . htmlentities(print_r($response, true)) . "</pre>";
//echo '<pre>' . htmlentities($response['lcxml']) . '</pre>';
//echo '---------------------</br>';
$coin1 = FALSE;
$coin2 = FALSE;
$coincidencias;
$coincidencias2;
$count;
$count2;
$dataCr = array();
if(preg_match_all('/<ttga35>/', $response['lcxml'], $coincidencias,PREG_OFFSET_CAPTURE)){
    //echo "HAY COINCIDENCIA<br>";
//    echo '<pre>';
//    echo print_r($coincidencias);
//    echo '</pre>';
    
    $count = count($coincidencias[0]);
    //echo 'numero de coincidencias: '.$count;
    $coin1 = TRUE;
}else{
    //echo "NO HAY COINCIDENCIA";
}

if(preg_match_all('/<\/ttga35>/', $response['lcxml'], $coincidencias2,PREG_OFFSET_CAPTURE)){
    //echo "HAY COINCIDENCIA<br>";
//    echo '<pre>';
//    echo print_r($coincidencias2);
//    echo '</pre>';
    
    $count2 = count($coincidencias2[0]);
    //echo 'numero de coincidencias2: '.$count2;
    $coin2 = TRUE;
}else{
    //echo "NO HAY COINCIDENCIA";
}

// datos de la tabla ttga35 a ser mostrados
$datos_tga35 = array('chasis' => 'Chasis','tipo_id_cliente' => 'Tipo Identificación','identifica_cliente' => 'Identificación',
    'nombre_cliente' => 'Nombre del Cliente', 'direccion_cliente' => 'Dirección del Cliente', 'telefono_cliente' => 'Teléfono',
    'telefono_celular' => 'Celular', 'mail_1'=>'Email','fecha_reparacion' => 'Fecha de reparación','reparaciones_solicitadas' => 'Reparaciones Solicitadas',
    'tipo_ingreso' => 'Tipo de Ingreso'
);
if($coin1 && $coin2){// si las dos busquedas son true en etiqueta de apertura y cierre
    if($count == $count2){// si las dos busquedas devuelven el mismo numero de coincidencias
        for($i = 0; $i < $count; $i++){
            $ini = $coincidencias[0][$i][1];
            $fin = $coincidencias2[0][$i][1];
            $ini +=8; // sumamos el numero de caracteres (8) de <ttga35>
            $longitudchr = $fin - $ini;// longitud de la cadena a imprimir
            $str = substr($response['lcxml'], $ini, $longitudchr);
            //echo htmlentities($str);
            //echo '<hr />';
        }
    }
}

$datos_search = array(
    'chasis' => 'No. Chasis', 'nombre_propietario' => 'Nombre Propietario', 'color_vehiculo' => 'Color Vehículo',
    'fecha_retail' => 'Fecha de Venta', 'anio_modelo' => 'Año', 'color_origen' => 'Color de Orígen' ,
    'numero_id_propietario' => 'Id del Propietario','precio_venta' => 'Precio de Venta',
    'calle_principal_propietario' => 'Calle Principal', 'numero_calle_propietario' => 'Número de Calle',
    'telefono_propietario' => 'Teléfono del Propietario', 'grupo_concesionario' => 'Grupo Concesionario',
    'forma_pago_retail' => 'Forma de Pago' 
);



//foreach ($datos_search as $key => $value) {
//    // primero encontramos la posicion de la cadena a encontrar
//    $pos = strpos($params[1], '<'.$key.'>');
//// encontramos la posicion final de la cadena final
//    $posfinal = strpos($params[1], '</'.$key.'>');
//// longitud de la cadena a impriimir
//    $longitud_cadena = $posfinal - $pos;
//// cadena a imprimir, con posicion inicial y longitud
//    $str = substr($params[1], $pos, $longitud_cadena);
//
//    echo $value.': '.$str.'<br>';
//}

?>


