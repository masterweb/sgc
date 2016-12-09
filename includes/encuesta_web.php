<?php

require_once('database.php');

class EncuestaWeb {

    protected static $table_name = "encuesta_web";
    protected static $db_fields = array('edad', 'visitas_sitio', 'volver_visita', 'motivo_visita',
        'nivel_satisfaccion', 'calidad_contenidos', 'diseno_atractivo', 'actualizacion_contenidos','rapidez_descarga',
        'variedad_modelos', 'facilidad_uso', 'servicio_atencion', 'facilidad_uso_exp', 'rapidez_descarga_exp',
        'diseno_atractivo_exp', 'servicio_atencion_exp', 'actualizacion_contenidos_exp', 'calidad_contenidos_exp',
        'variedad_contenidos_exp', 'mas_gusta_kia', 'menos_gusta_kia', 'comentario', 'boletin_email', 'ip', 'fecha');
    public $edad;
    public $visitas_sitio;
    public $volver_visita;
    public $motivo_visita;
    public $nivel_satisfaccion;
    public $calidad_contenidos;
    public $diseno_atractivo;
    public $actualizacion_contenidos;
    public $rapidez_descarga;
    public $variedad_modelos;
    public $facilidad_uso;
    public $servicio_atencion;
    public $facilidad_uso_exp;
    public $rapidez_descarga_exp;
    public $diseno_atractivo_exp;
    public $ervicio_atencion_exp;
    public $actualizacion_contenidos_exp;
    public $calidad_contenidos_exp;
    public $variedad_contenidos_exp;
    public $mas_gusta_kia;
    public $menos_gusta_kia;
    public $comentario;
    public $boletin_email;
    public $ip;
    public $fecha;

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
