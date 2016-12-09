<?php
require_once('database.php');

class FormCotizacion {

    protected static $table_name = "form_cotizacion";
    protected static $db_fields = array(
        'nombre', 'apellido','cedula', 'direccion','telefono','celular','id_modelos', 'email','id_modelos','id_version', 'id_provincia', 'cityid',
        'dealerid', 'id_atencion', 'fecha_form', 'fecha_email','ips','id_origen','motivo',
        'codigo','navegador','plataforma','fuente','ruc');
    //public $id_cotizacion;
    public $nombre;
    public $apellido;
    public $cedula;
    public $direccion;
    public $telefono;
    public $celular;
    public $email;
    public $id_modelos;
    public $id_version;
    public $id_provincia;
    public $cityid;
    public $dealerid;
    public $id_atencion;
    public $fecha_form;
    public $fecha_email;
    public $ips;
    public $id_origen;
    public $motivo;
    public $codigo;
    public $navegador;
    public $plataforma;
    public $fuente;
    public $ruc;
    
    public $insert_id;

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

