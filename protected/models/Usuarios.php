<?php

/**
 * This is the model class for table "usuarios".
 *
 * The followings are the available columns in table 'usuarios':
 * @property integer $id
 * @property string $nombres
 * @property string $correo
 * @property string $telefono
 * @property string $extension
 * @property string $celular
 * @property string $usuario
 * @property string $password
 * @property string $fecharegistro
 * @property string $estado
 * @property integer $cargo_id
 * @property integer $dealers_id
 * @property integer $area_id
 * @property string $fechaactivacion
 * @property string $ultimavisita
 * @property string $ultimaedicion
 * @property string $fechanacimiento
 * @property string $codigo_asesor
 * @property string $firma
 *
 * The followings are the available model relations:
 * @property Permisosespeciales[] $permisosespeciales
 * @property Cargo $cargo
 */
class Usuarios extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'usuarios';
    }

    public $body;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('nombres,apellido,cedula,fechaingreso, correo, usuario, password, fecharegistro, estado, cargo_id, fechanacimiento,celular', 'required'),
            array('cargo_id, dealers_id,grupo_id,concesionario_id,provincia_id, area_id', 'numerical', 'integerOnly' => true),
            array('nombres, password, firma', 'length', 'max' => 250),
            array('correo', 'length', 'max' => 150),
            array('apellido', 'length', 'max' => 150),
            array('cedula', 'length', 'max' => 10),
            array('correo', 'unique', 'message' => "Este CORREO ya se encuentra registrado."),
            array('usuario', 'unique', 'message' => "Este USUARIO ya se encuentra registrado."),
            array('telefono', 'length', 'max' => 15),
            array('extension', 'length', 'max' => 10),
            array('celular, usuario, fecharegistro,fechaingreso, estado, fechaactivacion, ultimaedicion', 'length', 'max' => 45),
            array('ultimavisita, codigo_asesor', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, nombres, correo, telefono, extension, celular, usuario, password, fecharegistro, estado, cargo_id, dealers_id, fechaactivacion, ultimavisita, ultimaedicion, fechanacimiento', 'safe', 'on' => 'search'),
            array('verifyCode',
                'application.extensions.recaptcha.EReCaptchaValidator',
                'privateKey' => Yii::app()->params['recaptcha']['privateKey']),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'permisosespeciales' => array(self::HAS_MANY, 'Permisosespeciales', 'usuarios_id'),
            'cargo' => array(self::BELONGS_TO, 'Cargo', 'cargo_id'),
            'dealer' => array(self::BELONGS_TO, 'Dealers', 'dealers_id'),
            'grupo' => array(self::BELONGS_TO, 'GrGrupo', 'grupo_id'),
            'consecionario' => array(self::BELONGS_TO, 'GrConcesionarios', 'concesionario_id'),
            'provincia' => array(self::BELONGS_TO, 'TblProvincias', 'provincia_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'nombres' => 'Nombres',
            'cedula' => 'C&eacute;dula',
            'apellido' => 'Apellidos',
            'fechaingreso' => 'Fecha de ingreso a la empresa',
            'correo' => 'Correo',
            'telefono' => 'Tel&eacute;fono',
            'extension' => 'Extensi&oacute;n',
            'celular' => 'Celular',
            'usuario' => 'Usuario/nickname',
            'password' => 'Password',
            'fecharegistro' => 'Fecha de registro',
            'estado' => 'Estado',
            'cargo_id' => 'Cargo',
            'dealers_id' => 'Concesionarios',
            'fechaactivacion' => 'Fecha de activaci&oacute;n',
            'ultimavisita' => '&Uacute;ltima visita',
            'ultimaedicion' => '&Uacute;ltima edici&oacute;n',
            'fechanacimiento' => 'Fecha de nacimiento',
            'grupo_id' => 'Grupo',
            'concesionario_id' => 'Concesionario_id',
            'provincia_id' => 'Provincia',
            'area_id' => 'Area',
            'codigo_asesor' => 'Codigo Asesor',
            'firma' => 'Firma',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('nombres', $this->nombres, true);
        $criteria->compare('cedula', $this->cedula, true);
        $criteria->compare('apellido', $this->apellido, true);
        $criteria->compare('correo', $this->correo, true);
        $criteria->compare('telefono', $this->telefono, true);
        $criteria->compare('extension', $this->extension, true);
        $criteria->compare('celular', $this->celular, true);
        $criteria->compare('usuario', $this->usuario, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('fecharegistro', $this->fecharegistro, true);
        $criteria->compare('estado', $this->estado, true);
        $criteria->compare('cargo_id', $this->cargo_id);
        $criteria->compare('dealers_id', $this->dealers_id);
        $criteria->compare('grupo_id', $this->grupo_id);
        $criteria->compare('concesionario_id', $this->concesionario_id);
        $criteria->compare('provincia_id', $this->provincia_id);
        $criteria->compare('fechaactivacion', $this->fechaactivacion, true);
        $criteria->compare('ultimavisita', $this->ultimavisita, true);
        $criteria->compare('ultimaedicion', $this->ultimaedicion, true);
        $criteria->compare('fechaingreso', $this->fechaingreso, true);
        $criteria->compare('fechanacimiento', $this->fechanacimiento, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Usuarios the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function getFullName() {
        //echo 'enter fullname';
        return ucfirst($this->nombres) . " " . ucfirst($this->apellido);
    }

}
