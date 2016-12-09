<?php
require_once('database.php');

class Atencion {

    protected static $table_name = "atencion_detalle";
    protected static $db_fields = array('nombre', 'apellido',
        'cedula', 'direccion', 'telefono', 'celular', 'email', 'id_modelos', 'id_version',
        'horario', 'id_provincia', 'cityid', 'dealerid', 'trabajo_solicitado',
        'modelo_vehiculo', 'numero_chasis', 'fecha_soli_atencion', 'obs_requerim_comen',
        'ocupacion', 'id_atencion', 'fecha_form', 'ips',
        'motivo', 'tema', 'otro', 'id_origen', 'navegador', 'plataforma','ruc'
    );
    public $nombre;
    public $apellido;
    public $cedula;
    public $direccion;
    public $telefono;
    public $celular;
    public $email;
    public $id_modelos;
    public $id_version;
    public $horario;
    public $id_provincia;
    public $cityid;
    public $dealerid;
    public $trabajo_solicitado;
    public $modelo_vehiculo;
    public $numero_chasis;
    public $fecha_soli_atencion;
    public $obs_requerim_comen;
    public $ocupacion;
    public $id_atencion;
    public $fecha_form;
    public $ips;
    public $motivo;
    public $tema;
    public $otro;
    public $id_origen;
    public $navegador;
    public $plataforma;
    public $ruc;
    public $insert_id;

    /*
     * Ingresar email del usuario para cancelar suscripcion
     * @param1 STRING id_atencion 
     * @param2 STRING email 
     */

    public function setEmailUnsuscribe($id_atencion, $email) {
        global $database;
        $fecha = date("Y-m-d");
        $sql = "INSERT INTO suscripciones (id_atencion_detalle,email,fecha) 
           VALUES ({$id_atencion},'{$email}', '{$fecha}')";
        $result = $database->getQuery($sql);
        return $result;
    }

    /*
     * Saca el email y la id_atencion-detalle del cliente
     * si encuentra un valor no se envia el email al destinatario
     * @param1 STRING id_atencion 
     * @param2 STRING email 
     * return true o false
     */

    public function getEmailUnsuscribe($id_atencion, $email) {
        global $database;
        $sql = "SELECT id_atencion_detalle, email FROM suscripciones 
           WHERE id_atencion_detalle = {$id_atencion} AND email = '{$email}'";
        //die('sql: '.$sql);
        $result = $database->getQuery($sql);
        $rows = mysql_fetch_row($result);
        if ($rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    // sacar clientes la fecha de ingreso para mandar newsletter
    public function traerClientes($nuevafecha) {
        global $database;
        $sql = "select id_atencion_detalle,email,dealerid,id_atencion,fecha_form, ips 
            from atencion_detalle where fecha_form > '{$nuevafecha}' and id_atencion = 1 group by email";
            //die('sql: '.$sql);
        $result = $database->getQuery($sql);
        return $result;
    }
    
    // sacar clientes de prueba para emails
    public function traerClientesPrueba() {
        global $database;
        $sql = "select id_atencion_detalle,email,dealerid,fecha_form
            from atencion_detalle_prueba";
            //die('sql: '.$sql);
        $result = $database->getQuery($sql);
        return $result;
    }

    // obtiene modelos de autos
    public function getModels($id_modelo) {
        global $database;
        $sql = "SELECT id_versiones, nombre_version FROM versiones WHERE id_modelos = {$id_modelo} ";
        //die('sql:'.$sql);
        $result = $database->getQuery($sql);
        $html = '<option value="">--Escoja una versión--</option>';
        while ($row = mysql_fetch_assoc($result)) {
            $html .= '<option value="' . $row['id_versiones'] . '">' . $row['nombre_version'] . '</option>';
        }
        return $html;
    }

    // obtiene emails de concesionarios con tipo de atencion: Solicitud de Cotizacion
    public function getEmailsDealer($dealerid) {
        global $database;
        $sql = "select para_email from dealers_email_encuestas WHERE id_tipo_atencion = 1 AND dealersid = $dealerid";
        $result = $database->getQuery($sql);
        $row = mysql_fetch_assoc($result);
        $emails = $row['para_email'];
        return $emails;
    }

    // obtiene emails  con copia adjunta de concesionarios con tipo de atencion: Solicitud de Cotizacion
    public function getCCEmailsDealer($dealerid) {
        global $database;
        $sql = "select cc_email from dealers_email_encuestas WHERE id_tipo_atencion = 1 AND dealersid = $dealerid";
        $result = $database->getQuery($sql);
        $row = mysql_fetch_assoc($result);
        $emails = $row['cc_email'];
        return $emails;
    }

    // obtiene emails con copia oculta de concesionarios con tipo de atencion: Solicitud de Cotizacion
    public function getBCCEmailsDealer($dealerid) {
        global $database;
        $sql = "select cco_email from dealers_email_encuestas WHERE id_tipo_atencion = 1 AND dealersid = $dealerid";
        $result = $database->getQuery($sql);
        $row = mysql_fetch_assoc($result);
        $emails = $row['cco_email'];
        return $emails;
    }

    // funcion para ingresar fecha de envio del email
    public function setFechaEnvio($fecha, $id) {
        global $database;
        $token_id = md5((string) $id);
        $sql = "UPDATE atencion_detalle SET fecha_email ='{$fecha}',token_id_atencion='{$token_id}' 
            WHERE id_atencion_detalle = $id";
        //die('sql: '.$sql);
        $result = $database->getQuery($sql);
    }

    // funcion para generar reporte de emails enviados
    // @param1 id_atencion_detalle id del email enviado a encuesta
    // @param2 email email del usuario para encuesta
    // @param3 fecha fecha de envio de encuesta
    public function setReporte($id_atencion_detalle, $email, $fecha) {
        global $database;
        $sql = "INSERT INTO reporte_encuesta (id_atencion_detalle, email_enviado, fecha)
                VALUES ({$id_atencion_detalle}, '{$email}', '{$fecha}')";
        $database->getQuery($sql);
    }

    /* SACAR LISTA DE CONCESIONARIOS CON SUS IDS DE TABLA dealers email encuestas */
    /* return array de dealersid */

    public function getDealers() {
        global $database;
        $sql = "SELECT dealersid FROM dealers_email_encuestas order by dealersid ASC";
        $result = $database->getQuery($sql);
        $object_array = array();
        while ($row = mysql_fetch_assoc($result)) {
            $object_array[] = $row['dealersid'];
        }
        return $object_array;
    }

    public function getDealerId($dealerid) {
        global $database;
        $sql = "SELECT dealerid FROM envio_reportes WHERE dealerid = $dealerid";
        $result = $database->getQuery($sql);
        $rows = mysql_affected_rows();
        if ($rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Funcion que devuelve la encuesta dentro de una fecha estblecida
     * @param1  int     $dealerid   id del concesionario
     * @param2  string  $fecha  fecha actual
     * return arrayData array de datos para highchart con graficos
     */

    public function getEncuestaConcesionarioId($fechaInicio, $fechaFin, $dealerid) {
        global $database;
        $numEnvios = $this->getNumEnvios($dealerid);
        $linkActivo = $this->getLinkActivo($dealerid);
        //die('numero envios: '.$numEnvios);
        // ingresar fecha nueva
        $this->setFechaEnvioAnterior($fechaInicio, $dealerid);

        $sql = "select e.id_atencion_detalle, e.preg1, e.preg2, e.preg3, e.preg4, e.preg5,a.dealerid 
                FROM encuestas e, atencion_detalle a WHERE e.id_atencion_detalle = a.id_atencion_detalle 
                and a.dealerid = {$dealerid} AND e.fecha BETWEEN '{$fechaInicio}' AND '{$fechaFin}' GROUP BY email";

//        $sql = "select e.id_atencion_detalle, e.preg1, e.preg2, e.preg3, e.preg4, e.preg5,a.dealerid 
//                FROM encuestas e, atencion_detalle a WHERE e.id_atencion_detalle = a.id_atencion_detalle 
//                and a.dealerid = {$dealerid} AND e.fecha >= '{$fecha}'";
        //die('sql: '.$sql);
        $result = $database->getQuery($sql);
        $count = mysql_affected_rows();

        $arrayDatos = array();
        $data = '<form action="https://www.kia.com.ec/administrador/ficheroExcel.php" method="post" target="_blank" id="FormularioExportacion">';

        $res1 = 0;
        $res2 = 0;
        $res3 = 0;
        $res4 = 0;
        $res5 = 0;
        $res6 = 0;
        $res7 = 0;
        $res8 = 0;
        $res9 = 0;
        $res10 = 0;
        $res11 = 0;
        $res12 = 0;
        while ($row = mysql_fetch_assoc($result)) {
            $r1 = ($row['preg1'] == 1) ? 'Si' : 'No';
            $r2 = ($row['preg2'] == 1) ? 'Si' : 'No';
            $r3 = ($row['preg3'] == 1) ? 'Si' : 'No';
            $pregunta1 = $row['preg1'];
            switch ($pregunta1) {
                case 1:
                    $res1++;
                    break;
                case 0:
                    $res2++;
                    break;

                default:
                    break;
            }
            $pregunta2 = $row['preg2'];
            //die('pregunta2: '.$pregunta2);
            switch ($pregunta2) {
                case 1:
                    $res3++;
                    break;
                case 0:
                    $res4++;
                    break;

                default:
                    break;
            }
            $pregunta3 = $row['preg3'];
            switch ($pregunta3) {
                case 1:
                    $res5++;
                    break;
                case 0:
                    $res6++;
                    break;
                default:
                    break;
            }
            $pregunta4 = $row['preg4'];
            switch ($pregunta4) {
                case 1:
                    $res7++;
                    break;
                case 2:
                    $res8++;
                    break;
                case 3:
                    $res9++;
                    break;

                default:
                    break;
            }
            $pregunta5 = $row['preg5'];
            //$values = explode('-', $pregunta5);
            //print_r($values);
            switch ($pregunta5) {
                case 'excelente':
                    $res10++;
                    break;
                case 'buena':
                    $res11++;
                    break;
                case 'regular':
                    $res12++;
                    break;

                default:
                    break;
            }
        }
        $data .='<input type="hidden" id="concesionario" name="concesionario" value="' . $dealerid . '"/>
                 <input type="hidden" id="fecha" name="fecha" value="' . $fechaInicio . '"/>
                 <input type="hidden" id="fecha2" name="fecha2" value="' . $fechaFin . '"/>
                 <input type="submit" id="datos_a_enviar" name="datos_a_enviar" value="Enviar a Excel" class="btn btn-danger"/>
                 </form>';
        $arrayDatos['preg1'] = array($res1, $res3, $res5, $res7, $res10);
        $arrayDatos['preg2'] = array($res2, $res4, $res6, $res8, $res11);
        $arrayDatos['preg3'] = array(0, 0, 0, $res9, $res12);
        $arrayDatos['data'] = $data;
        return $arrayDatos;
    }
    
    /*
     * Funcion que devuelve la encuesta dentro de una fecha estblecida
     * @param1  int     $dealerid   id del concesionario
     * @param2  string  $fecha  fecha actual
     * return arrayData array de datos para highchart con graficos
     */

    public function getEncuestaConcesionarioIdCerrado($fechaInicio, $fechaFin, $dealerid) {
        global $database;
        $numEnvios = $this->getNumEnvios($dealerid);
        $linkActivo = $this->getLinkActivo($dealerid);
        //die('numero envios: '.$numEnvios);
        // ingresar fecha nueva
        $this->setFechaEnvioAnterior($fechaInicio, $dealerid);

        $sql = "select e.id_atencion_detalle, e.preg1, e.preg2, e.preg3, e.preg4, e.preg5,a.dealerid 
                FROM encuestas e, atencion_detalle a WHERE e.id_atencion_detalle = a.id_atencion_detalle 
                and a.dealerid = {$dealerid} AND e.fecha BETWEEN '{$fechaInicio}' AND '{$fechaFin}' GROUP BY email";

//        $sql = "select e.id_atencion_detalle, e.preg1, e.preg2, e.preg3, e.preg4, e.preg5,a.dealerid 
//                FROM encuestas e, atencion_detalle a WHERE e.id_atencion_detalle = a.id_atencion_detalle 
//                and a.dealerid = {$dealerid} AND e.fecha >= '{$fecha}'";
        //die('sql: '.$sql);
        $result = $database->getQuery($sql);
        $count = mysql_affected_rows();

        $arrayDatos = array();
        $data = '<form action="https://www.kia.com.ec/administrador/ficheroExcel.php" method="post" target="_blank" id="FormularioExportacion">';

        $res1 = 0;
        $res2 = 0;
        $res3 = 0;
        $res4 = 0;
        $res5 = 0;
        $res6 = 0;
        
        while ($row = mysql_fetch_assoc($result)) {
            $r1 = ($row['preg1'] == 1) ? 'Si' : 'No';
            $r2 = ($row['preg2'] == 1) ? 'Si' : 'No';
            $r3 = ($row['preg3'] == 1) ? 'Si' : 'No';
            $pregunta1 = $row['preg1'];
            switch ($pregunta1) {
                case 1:
                    $res1++;
                    break;
                case 0:
                    $res2++;
                    break;

                default:
                    break;
            }
            $pregunta2 = $row['preg2'];
            //die('pregunta2: '.$pregunta2);
            switch ($pregunta2) {
                case 1:
                    $res3++;
                    break;
                case 0:
                    $res4++;
                    break;

                default:
                    break;
            }
            $pregunta3 = $row['preg3'];
            switch ($pregunta3) {
                case 1:
                    $res5++;
                    break;
                case 0:
                    $res6++;
                    break;
                default:
                    break;
            }
        }
        $data .='<input type="hidden" id="concesionario" name="concesionario" value="' . $dealerid . '"/>
                 <input type="hidden" id="fecha" name="fecha" value="' . $fechaInicio . '"/>
                 <input type="hidden" id="fecha2" name="fecha2" value="' . $fechaFin . '"/>
                 <input type="submit" id="datos_a_enviar" name="datos_a_enviar" value="Enviar a Excel" class="btn btn-danger"/>
                 </form>';
        $arrayDatos['preg1'] = array($res1, $res3, $res5);
        $arrayDatos['preg2'] = array($res2, $res4, $res6);
        $arrayDatos['data'] = $data;
        return $arrayDatos;
    }

    public function getEncuestaConcesionarioIdExcelData($dealerid, $nuevafecha) {
        $database = new DatabaseCon();
        $sql = "SELECT e.id_atencion_detalle, e.ips,e.preg1, e.preg2, e.preg3, e.preg4, e.preg5, a.cityid, a.dealerid 
                FROM encuestas e, atencion_detalle a 
                WHERE e.id_atencion_detalle = a.id_atencion_detalle 
                AND a.dealerid = {$dealerid} AND e.fecha >= '{$nuevafecha}'";

        //die('sql: '.$sql);
        $result = $database->getConsulta($sql);
        return $result;
    }

    // devuelve numero de envios para los reportes de concesionarios
    public function getNumEnvios($dealerid) {
        global $database;
        $sql = "SELECT num_envios FROM envio_reportes WHERE dealerid = {$dealerid}";
        $result = $database->getQuery($sql);
        $row = mysql_fetch_assoc($result);
        $envios = $row['num_envios'];
        return $envios;
    }

    public function setEnvios($dealerid) {
        global $database;
        $fecha = date("Y-m-d");
        $sql = "INSERT INTO envio_reportes (num_envios, dealerid, fecha) 
            VALUES (0,$dealerid,'{$fecha}')";
        $result = $database->getQuery($sql);
    }

    // establece el numero de envios
    public function updateNumEnvios($dealerid, $numEnvios) {
        global $database;
        $sql = "UPDATE envio_reportes SET num_envios = {$numEnvios} WHERE dealerid = {$dealerid}";
        $result = $database->getQuery($sql);
    }

    // pone link expirado una vez que el usuario ingresa al reporte
    public function updateLinkActivo($dealerid, $fecha_anterior) {
        global $database;
        $fecha_actual= date("Y-m-d");
        $dias = (strtotime($fecha_actual) - strtotime($fecha_anterior)) / 86400;
        $dias = abs($dias);
        $dias = floor($dias);
        $sql = "UPDATE envio_reportes SET activo = 0 WHERE dealerid = {$dealerid}";
        $result = $database->getQuery($sql);
    }

    // obtiene valor del link activo
    public function getLinkActivo($dealerid) {
        global $database;
        $sql = "SELECT activo FROM envio_reportes WHERE dealerid = {$dealerid}";
        $result = $database->getQuery($sql);
        $row = mysql_fetch_assoc($result);
        $activo = $row['activo'];
        return $activo;
    }

    // activa el link
    public function activateLinkActivo($dealerid) {
        global $database;
        $sql = "UPDATE envio_reportes SET activo = 1 WHERE dealerid = {$dealerid}";
        $result = $database->getQuery($sql);
    }

    // ingresar o actualizar fecha de envio anterior
    public function setFechaEnvioAnterior($fecha, $dealerid) {
        global $database;
        $sql = "UPDATE envio_reportes SET fecha_nueva = '{$fecha}' WHERE dealerid = {$dealerid}";
        //die('sql: '.$sql);
        $result = $database->getQuery($sql);
    }

    // obtiene fecha anterior de los reportes
    public function getFechaEnvioAnterior($dealerid) {
        global $database;
        $sql = "SELECT fecha_nueva FROM envio_reportes WHERE dealerid = {$dealerid}";
        //die('sql: '.$sql);
        $result = $database->getQuery($sql);
        $row = mysql_fetch_assoc($result);
        $fecha = $row['fecha_nueva'];
        return $fecha;
    }

    // encriptar la cadena a ser enviada por la URL
    // @param1 string $string cadena de URL 
    // return string con cadena encriptada
    public function encode_this($string) {
        $string = utf8_encode($string);
        $control = "ASFrtyrTJlk"; //defino la llave para encriptar la cadena, cambiarla por la que deseamos usar
        $string = $control . $string . $control; //concateno la llave para encriptar la cadena
        $string = base64_encode($string); //codifico la cadena
        return($string);
    }

    // desencriptar la cadena enviada por la URL
    function decode_get2($string) {
        $cad = split("[?]", $string); //separo la url desde el ?
        $string = $cad[1]; //capturo la url desde el separador ? en adelante
        $string = base64_decode($string); //decodifico la cadena
        $control = "ASFrtyrTJlk"; //defino la llave con la que fue encriptada la cadena
        $string = str_replace($control, "", "$string"); //quito la llave de la cadena
        //procedo a dejar cada variable en el $_GET
        $cad_get = split("[&]", $string); //separo la url por &
        foreach ($cad_get as $value) {
            $val_get = split("[=]", $value); //asigno los valosres al GET
            $_GET[$val_get[0]] = utf8_decode($val_get[1]);
        }
    }

    // Hace la consulta a la base de datos
    public static function find_by_sql($sql = "") {
        global $database;
        $result_set = $database->query($sql);
        $object_array = array();
        while ($row = $database->fetch_array($result_set)) {
            $object_array[] = self::instantiate($row);
        }
        return $object_array;
    }

    // instanciar las variables del objeto
    private static function instantiate($record) {
        $object = new self;
        foreach ($record as $attribute => $value) {
            if ($object->has_attribute($attribute)) {
                $object->$attribute = $value;
            }
        }
        return $object;
    }

    private function has_attribute($attribute) {
        // Se quiere saber si la clave existe 
        // Retorna true o false
        return array_key_exists($attribute, $this->attributes());
    }

    // retorna un arreglo asociativo con nombres y valores
    protected function attributes() {
        // return an array of attribute names and their values
        $attributes = array();
        foreach (self::$db_fields as $field) {
            if (property_exists($this, $field)) {
                $attributes[$field] = $this->$field;
            }
        }
        return $attributes;
    }

    // Limpia caracteres extranos de las entradas del usuario
    protected function sanitized_attributes() {
        global $database;
        $clean_attributes = array();
        // Nota: no altera el valor actual de cada atributo
        foreach ($this->attributes() as $key => $value) {
            $clean_attributes[$key] = $database->escape_value($value);
        }
        return $clean_attributes;
    }

    public function save() {
        // Un nuevo registro no tendria id todavia
        return isset($this->id) ? $this->update() : $this->create();
    }

    // crea un nuevo usuario
    public function create() {
        global $database;
        $attributes = $this->sanitized_attributes();
        $sql = "INSERT INTO " . self::$table_name . " (";
        $sql .= join(", ", array_keys($attributes));
        $sql .= ") VALUES ('";
        $sql .= join("', '", array_values($attributes));
        $sql .= "')";
        //die('sql :'.$sql);
        if ($database->getQuery($sql)) {
            $this->insert_id = $database->insert_id();
            return true;
        } else {
            return false;
        }
    }

}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
