<?php

include_once ('database_call.php');

class FormHistorial {

    protected static $table_name = "historial";
    protected static $db_fields = array(
        'id_caso','id_responsable','fecha','tema','subtema','observaciones','estado');
    public $id_caso;
    public $id_responsable;
    public $fecha;
    public $tema;
    public $subtema;
    public $observaciones;
    public $estado;

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

    public function update($value) {
        $database_call = new MySQLDatabaseCall();
        $sql = "UPDATE " . self::$table_name;
        $sql = " SET number = {$value} WHERE id = 1";
        if ($database->getQueryCall($sql)) {
            return true;
        } else {
            return false;
        }
    }

    // crea un nuevo objeto historial
    public function create() {
        $database_call = new MySQLDatabaseCall();

        $attributes = $this->sanitized_attributes();
        $sql = "INSERT INTO " . self::$table_name . " (";
        $sql .= join(", ", array_keys($attributes));
        $sql .= ") VALUES ('";
        $sql .= join("', '", array_values($attributes));
        $sql .= "')";
        if ($database_call->getQueryCall($sql)) {
            $this->id = $database_call->insert_id();
            return true;
        } else {
            return false;
        }
    }

    

}
?>


