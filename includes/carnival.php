<?php
include_once('database_call_carnival.php');

class Carnival {

    protected static $table_name = "carnival_promo";
    protected static $db_fields = array(
        'nombre','apellido','cedula','convencional','celular','ciudad',
        'email','placa','fecha','provincia');
    //public $id_cotizacion;
    
    public $nombre;
    public $apellido;
    public $cedula;
    public $ciudad;
    public $convencional;
    public $celular;
    public $email;
    public $placa;
    public $fecha;
    public $provincia;

    // Hace la consulta a la base de datos
    public static function find_by_sql($sql = "") {
        global $database_call;
        $result_set = $database_call->query($sql);
        $object_array = array();
        while ($row = $database_call->fetch_array($result_set)) {
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

    // Limpia caracteres extraños de las entradas del usuario
    protected function sanitized_attributes() {
        $database_call = new MySQLDatabaseCall();
        $clean_attributes = array();
        // Nota: no altera el valor actual de cada atributo
        foreach ($this->attributes() as $key => $value) {
            $clean_attributes[$key] = $database_call->escape_value($value);
        }
        return $clean_attributes;
    }

    public function save() {
        // Un nuevo registro no tendría id todavía
        return isset($this->id) ? $this->update() : $this->create();
    }

    // crea un nuevo usuario
    public function create() {
        $database_call = new MySQLDatabaseCall();

        $attributes = $this->sanitized_attributes();
        $sql = "INSERT INTO " . self::$table_name . " (";
        $sql .= join(", ", array_keys($attributes));
        $sql .= ") VALUES ('";
        $sql .= join("', '", array_values($attributes));
        $sql .= "')";
        //die('sql:  '.$sql);
        if ($database_call->getQueryCall($sql)) {
            $this->insert_id = $database_call->insert_id();
            return true;
        } else {
            return false;
        }
    }
    
    /** Function get last query id inserted
     * 
     */
    public function getLastId() {
        $database_call = new MySQLDatabaseCall();
        $sql = 'SELECT id FROM casos ORDER BY id DESC LIMIT 0,1';
        $result = $database_call->getQueryCall($sql);
        $row = mysql_fetch_assoc($result);
        return $row['id'];
    }
    
    public function getProvincia($id_provincia) {
       $database_call = new MySQLDatabaseCall();
       $sql = "SELECT nombre FROM tbl_provincias WHERE id_provincia = {$id_provincia}"; 
       $result = $database_call->getQueryCall($sql);
       $row = mysql_fetch_assoc($result);
       return $row['nombre'];
    }
    
    public function getCiudad($id_ciudad) {
       $database_call = new MySQLDatabaseCall();
       $sql = "SELECT nombre FROM tbl_ciudades WHERE id_ciudad = {$id_ciudad}"; 
       $result = $database_call->getQueryCall($sql);
       $row = mysql_fetch_assoc($result);
       return $row['nombre'];
    }

}
?>

