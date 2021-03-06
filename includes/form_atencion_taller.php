<?php
require_once('database.php');

class FormAtencion {

    protected static $table_name = "form_atencion_taller";
    protected static $db_fields = array(
        'nombre', 'apellido','cedula','telefono','celular','email','trabajo_solicitado','modelo_vehiculo', 'numero_chasis', 'fecha_soli_atencion',
        'cityid','dealerid', 'id_atencion', 'fecha_form', 'ips','id_origen','navegador','plataforma');
    //public $id_cotizacion;
    public $nombre;
    public $apellido;
    public $cedula;
    public $telefono;
    public $celular;
    public $email;
    public $trabajo_solicitado;
    public $modelo_vehiculo;
    public $numero_chasis;
    public $fecha_soli_atencion;
    public $cityid;
    public $dealerid;
    public $id_atencion;
    public $fecha_form;
    public $ips;
    public $id_origen;
    public $navegador;
    public $plataforma;
    

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

    // Limpia caracteres extraños de las entradas del usuario
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
        // Un nuevo registro no tendría id todavía
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
        //die('sql:  '.$sql);
        if ($database->getQuery($sql)) {
            $this->insert_id = $database->insert_id();
            return true;
        } else {
            return false;
        }
    }

}
?>

