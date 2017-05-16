<?php

/**
 * This is the model class for table "gestion_vehiculo".
 *
 * The followings are the available columns in table 'gestion_vehiculo':
 * @property string $id
 * @property string $id_informacion
 * @property string $modelo
 * @property string $version
 * @property string $precio
 * @property string $precio_accesorios
 * @property string $dispositivo
 * @property string $accesorios
 * @property string $seguro
 * @property string $plazo
 * @property string $total
 * @property string $fecha
 * @property string $cierre
 * @property string $num_pdf
 * @property int $tipo_credito
 * @property int $orden
 *
 * The followings are the available model relations:
 * @property GestionInformacion $idInformacion
 */
class GestionVehiculo extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return GestionVehiculo the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'gestion_vehiculo';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_informacion, modelo', 'required'),
            array('modelo, version, tipo_credito', 'numerical', 'integerOnly'=>true),
            array('id_informacion', 'length', 'max' => 15),
            array('modelo, precio, dispositivo, accesorios, seguro, plazo, total, forma_pago', 'length', 'max' => 45),
            array('observaciones, fecha', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_informacion, modelo, precio, dispositivo, accesorios, seguro, plazo, total, fecha, cierre', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'idInformacion' => array(self::BELONGS_TO, 'GestionInformacion', 'id_informacion'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'id_informacion' => 'Id Informacion',
            'modelo' => 'Modelo',
            'precio' => 'Precio',
            'precio_accesorios' => 'Precio Accesorios',
            'dispositivo' => 'Dispositivo',
            'accesorios' => 'Accesorios',
            'seguro' => 'Seguro',
            'plazo' => 'Plazo',
            'total' => 'Total',
            'forma_pago' => 'Forma Pago',
            'fecha' => 'Fecha',
            'cierre' => 'Cierre',
            'num_pdf' => 'Numero de Proforma',
            'tipo_credito' => 'Tipo Credito',
            'orden' => 'Orden vehiculo',
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
        $criteria->compare('id', $this->id, true);
        $criteria->compare('id_informacion', $this->id_informacion, true);
        $criteria->compare('modelo', $this->modelo, true);
        $criteria->compare('precio', $this->precio, true);
        $criteria->compare('dispositivo', $this->dispositivo, true);
        $criteria->compare('accesorios', $this->accesorios, true);
        $criteria->compare('seguro', $this->seguro, true);
        $criteria->compare('plazo', $this->plazo, true);
        $criteria->compare('total', $this->total, true);
        $criteria->compare('forma_pago', $this->forma_pago, true);
//        $criteria->compare('cuota_inicial', $this->cuota_inicial, true);
//        $criteria->compare('saldo_financiar', $this->saldo_financiar, true);
//        $criteria->compare('tarjeta_credito', $this->tarjeta_credito, true);
//        $criteria->compare('otro', $this->otro, true);
//        $criteria->compare('plazos', $this->plazos, true);
//        $criteria->compare('cuota_mensual', $this->cuota_mensual, true);
//        $criteria->compare('avaluo', $this->avaluo, true);
//        $criteria->compare('observaciones', $this->observaciones, true);
        $criteria->compare('fecha', $this->fecha, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}