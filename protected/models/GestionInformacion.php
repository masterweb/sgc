<?php

/**
 * This is the model class for table "gestion_informacion".
 *
 * The followings are the available columns in table 'gestion_informacion':
 * @property string $id
 * @property string $id_cotizacion
 * @property string $nombres
 * @property string $apellidos
 * @property string $identificacion
 * @property string $cedula
 * @property string $direccion
 * @property string $provincia_domicilio
 * @property string $ciudad_domicilio
 * @property string $ruc
 * @property string $pasaporte
 * @property string $direccion
 * @property string $email
 * @property string $visita
 * @property string $fecha_cita
 * @property string $celular
 * @property string $telefono_oficina
 * @property string $telefono_casa
 * @property string $ciudad
 * @property integer $provincia_conc
 * @property integer $ciudad_conc
 * @property integer $concesionario
 * @property integer $responsable
 * @property integer $responsable_origen
 * @property integer $dealer_id
 * @property string $fecha
 * @property string $tipo_form_web
 * @property string $presupuesto
 * @property integer $modelo
 * @property integer $id_solicitud
 * @property integer $version
 * @property string $marca_usado
 * @property string $modelo_usado
 * @property integer $bdc
 * @property string $img
 * @property integer $porcentaje_discapacidad
 * @property integer $senae
 * @property string $tipo_ex
 * @property integer $id_comentario
 * @property string $medio
 * 
 * The followings are the available model relations:
 * @property GestionVehiculo[] $gestionVehiculos
 */
class GestionInformacion extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return GestionInformacion the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'gestion_informacion';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
           array('nombres, apellidos, cedula, direccion, provincia_domicilio,ciudad_domicilio,email, celular,'
                . 'telefono_casa, provincia_conc, ciudad_conc, concesionario', 'required','on' => 'gestion'),
            array('nombres, apellidos, ruc, direccion, provincia_domicilio,ciudad_domicilio,email, celular, '
                . 'telefono_casa, provincia_conc, ciudad_conc, concesionario', 'required','on' => 'ruc'),
            array('nombres, apellidos, pasaporte, direccion, provincia_domicilio,ciudad_domicilio,email, celular, '
                . 'telefono_casa, provincia_conc, ciudad_conc, concesionario', 'required','on' => 'pasaporte'),
            array('nombres, apellidos, ruc, direccion, provincia_domicilio,ciudad_domicilio,email, celular, '
                . 'telefono_casa', 'required','on' => 'rucusado'),
            array('nombres, apellidos, pasaporte, direccion, provincia_domicilio,ciudad_domicilio,email, celular, '
                . 'telefono_casa', 'required','on' => 'pasaporteusado'),
            array('nombres, apellidos, email, celular', 'required','on' => 'prospeccion'),
            array('nombres, apellidos, cedula, celular', 'required','on' => 'trafico'),
            array('nombres, direccion', 'required','on' => 'createc'),
            array('concesionario, modelo, id_solicitud, version', 'numerical', 'integerOnly' => true),
            array('nombres, apellidos, email, ciudad', 'length', 'max' => 100),
            array('nombres, apellidos', 'match', 'pattern' => '/^[a-zA-Z áéíóúÁÉÍÓÚÑñ.\s]+$/', 'message' => "{attribute} debe contener sólo letras"),
            array('cedula', 'length', 'max' => 20),
            array('direccion', 'length', 'max' => 100),
            array('celular, telefono_oficina, telefono_casa', 'length', 'max' => 15),
            array('cedula, ruc, telefono_oficina, telefono_casa, celular, bdc', 'numerical', 'integerOnly' => true),
            array('cedula', 'validateDocument','on' => 'gestion'),
            array('celular', 'validateCel'),
            array('telefono', 'validateTel','on' => 'gestion'),
            array('email', 'email'),
            array('fecha', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, nombres, apellidos, cedula, direccion, email, celular, telefono, ciudad, concesionario, fecha', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(

        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'id_cotizacion' => 'ID Cotizacion',
            'nombres' => 'Nombres',
            'apellidos' => 'Primer Apellido',
            'cedula' => 'Cedula',
            'ruc' => 'Ruc',
            'pasaporte' => 'Pasaporte',
            'direccion' => 'Direccion',
            'provincia_domicilio' => 'Provincia Domicilio',
            'ciudad_domicilio' => 'Ciudad Domicilio',
            'email' => 'Email',
            'fecha_cita' => 'Fecha de Visita',
            'visita' => 'Desea Visitarnos?',
            'celular' => 'Celular',
            'telefono_oficina' => 'Telefono Oficina',
            'telefono_casa' => 'Telefono Domicilio',
            'ciudad' => 'Ciudad',
            'provincia_conc' => 'Provincia',
            'ciudad_conc' => 'Ciudad Concesionario',
            'concesionario' => 'Concesionario',
            'responsable' => 'Responsable',
            'responsable_origen' => 'Responsable Origen',
            'dealer_id' => 'Dealer ID',
            'fecha' => 'Fecha',
            'tipo_form_web' => 'Tipo Web',
            'presupuesto' => 'Presupuesto',
            'marca_usado' => 'Marca Usado',
            'modelo_usado' => 'Modelo Usado',
            'bdc' => 'BDC',
            'img' => 'Img',
            'porcentaje_discapacidad' => 'porcentaje_discapacidad',
            'senae' => 'senae',
            'tipo_ex' => 'Tipo Ex',
            'id_comentario' => 'Id Comentario',
            'medio' => 'Medio',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('nombres', $this->nombres, true);
        $criteria->compare('apellidos', $this->apellidos, true);
        $criteria->compare('cedula', $this->cedula, true);
        $criteria->compare('direccion', $this->direccion, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('celular', $this->celular, true);
        $criteria->compare('telefono', $this->telefono, true);
        $criteria->compare('ciudad', $this->ciudad, true);
        $criteria->compare('concesionario', $this->concesionario);
        $criteria->compare('responsable', $this->responsable);
        $criteria->compare('fecha', $this->fecha, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }
    
    public function validateCel() {
        $pos = strpos($this->celular, "0");
        $pos9 = strpos($this->celular, "9");
        if ($pos !== 0) {
            $this->addError('celular', 'Ingrese correctamente el celular');
        }
        if ($pos9 !== 1) {
            $this->addError('celular', 'Ingrese correctamente el celular');
        }
    }

    public function validateTel() {
        $pos = strpos($this->telefono_oficina, "0");
        if ($pos !== 0) {
            $this->addError('telefono_oficina', 'Ingrese el código provincial');
        }
    }

    /* Funcion para validar el numero de la cedula */

    public function validateDocument() {
        $numero = $this->cedula;
        if (strlen($numero) == '10') {
            $suma = 0;
            $residuo = 0;
            $pri = 0;
            $pub = 0;
            $nat = 0;
            $numeroProvincias = 22;
            $modulo = 11;
            /* Aqui almacenamos los digitos de la cedula en variables. */
            $d1 = substr($numero, 0, 1);
            $d2 = substr($numero, 1, 1);
            $d3 = substr($numero, 2, 1);
            $d4 = substr($numero, 3, 1);
            $d5 = substr($numero, 4, 1);
            $d6 = substr($numero, 5, 1);
            $d7 = substr($numero, 6, 1);
            $d8 = substr($numero, 7, 1);
            $d9 = substr($numero, 8, 1);
            $d10 = substr($numero, 9, 1);
            /* El tercer digito es: */
            /* 9 para sociedades privadas y extranjeros */
            /* 6 para sociedades publicas */
            /* menor que 6 (0,1,2,3,4,5) para personas naturales */
            if ($d3 == 7 || $d3 == 8) {
                //echo"El tercer d&iacute;gito ingresado es inv&aacute;lido";
                $this->addError('cedula', 'Ingrese correctamente el Número de Cédula');
                return 0;
            }
            /* Solo para personas naturales (modulo 10) */
            if ($d3 < 6) {
                $nat = 1;
                $p1 = $d1 * 2;
                if ($p1 >= 10)
                    $p1 -= 9;
                $p2 = $d2 * 1;
                if ($p2 >= 10)
                    $p2 -= 9;
                $p3 = $d3 * 2;
                if ($p3 >= 10)
                    $p3 -= 9;
                $p4 = $d4 * 1;
                if ($p4 >= 10)
                    $p4 -= 9;
                $p5 = $d5 * 2;
                if ($p5 >= 10)
                    $p5 -= 9;
                $p6 = $d6 * 1;
                if ($p6 >= 10)
                    $p6 -= 9;
                $p7 = $d7 * 2;
                if ($p7 >= 10)
                    $p7 -= 9;
                $p8 = $d8 * 1;
                if ($p8 >= 10)
                    $p8 -= 9;
                $p9 = $d9 * 2;
                if ($p9 >= 10)
                    $p9 -= 9;
                $modulo = 10;
            }
            /* Solo para sociedades publicas (modulo 11) */
            /* Aqui el digito verficador esta en la posicion 9, en las otras 2 en la pos. 10 */
            else if ($d3 == 6) {
                $pub = 1;
                $p1 = $d1 * 3;
                $p2 = $d2 * 2;
                $p3 = $d3 * 7;
                $p4 = $d4 * 6;
                $p5 = $d5 * 5;
                $p6 = $d6 * 4;
                $p7 = $d7 * 3;
                $p8 = $d8 * 2;
                $p9 = 0;
            }
            /* Solo para entidades privadas (modulo 11) */ else if ($d3 == 9) {
                $pri = 1;
                $p1 = $d1 * 4;
                $p2 = $d2 * 3;
                $p3 = $d3 * 2;
                $p4 = $d4 * 7;
                $p5 = $d5 * 6;
                $p6 = $d6 * 5;
                $p7 = $d7 * 4;
                $p8 = $d8 * 3;
                $p9 = $d9 * 2;
            }
            $suma = $p1 + $p2 + $p3 + $p4 + $p5 + $p6 + $p7 + $p8 + $p9;
            $residuo = $suma % $modulo;
            /* Si residuo=0, dig.ver.=0, caso contrario 10 - residuo */
            $digitoVerificador = $residuo == 0 ? 0 : $modulo - $residuo;
            /* ahora comparamos el elemento de la posicion 10 con el dig. ver. */
            if ($pub == 1) {
                if ($digitoVerificador != $d9) {
                    //echo"El ruc de la empresa del sector p&uacute;blico es incorrecto.";
                    $this->addError('cedula', 'Ingrese correctamente el Número de Cédula');
                    return 0;
                }
                /* El ruc de las empresas del sector publico terminan con 0001 */
                if (substr($numero, 9, 4) != '0001') {
                    //echo "El ruc de la empresa del sector p&uacute;blico debe terminar con 0001";
                    $this->addError('cedula', 'Ingrese correctamente el Número de Cédula');
                    return 0;
                }
            } elseif ($pri == 1) {
                if ($digitoVerificador != $d10) {
                    //echo"El ruc de la empresa del sector privado es incorrecto.";
                    $this->addError('cedula', 'Ingrese correctamente el Número de Cédula');
                    return 0;
                }
                if (substr($numero, 10, 3) != '001') {
                    //echo"El ruc de la empresa del sector privado debe terminar con 001";
                    $this->addError('cedula', 'Ingrese correctamente el Número de Cédula');
                    return 0;
                }
            } elseif ($nat == 1) {
                if ($digitoVerificador != $d10) {
                    //echo"El n&uacute;mero de c&eacute;dula de la persona natural es incorrecto.";
                    $this->addError('cedula', 'Ingrese correctamente el Número de Cédula');
                    return 0;
                }
                if (strlen($numero) > 10 && substr($numero, 10, 3) != '001') {
                    //echo"El ruc de la persona natural debe terminar con 001";
                    $this->addError('cedula', 'Ingresa correctamente el Número de Cédula');
                    return 0;
                }
            }
        } else {
            $this->addError('cedula', 'El número ingresado no tiene 10 dígitos');
            return 0;
        }
        return 1;
    }

}