<?php
require_once('database.php');

class FormContactanos {

    protected static $table_name = "form_contactos_sugerencias";
    protected static $db_fields = array(
        'nombre', 'apellido','cedula', 'telefono','celular','email', 'cityid',
        'dealerid', 'id_atencion', 'fecha_form','ips','id_origen','obs_requerim_comen','motivo','tipo_reclamo','navegador','plataforma','modelo');
    
    public $nombre;
    public $apellido;
    public $cedula;
    public $telefono;
    public $celular;
    public $email;
    public $cityid;
    public $dealerid;
    public $id_atencion;
    public $fecha_form;
    public $ips;
    public $id_origen;
    public $obs_requerim_comen;
    public $motivo;
    public $tipo_reclamo;
    public $navegador;
    public $plataforma;
    public $modelo;

    // Metodos comunes para la base de datos
    public function search_name($busqueda) {
        global $database;
        $sql = "";
        return $result = $database->query($sql);
    }

    public function find_by_user_fb($user_fb) {
        $result_array = self::find_by_sql("SELECT id_fb FROM " . self::$table_name . " WHERE id_fb='$user_fb'");
        return !empty($result_array) ? array_shift($result_array) : false;
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
        if ($database->getQuery($sql)) {
            $this->id = $database->insert_id();
            return true;
        } else {
            return false;
        }
    }

}
?>


