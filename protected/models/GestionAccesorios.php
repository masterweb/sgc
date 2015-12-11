<?php

/**
 * This is the model class for table "gestion_accesorios".
 *
 * The followings are the available columns in table 'gestion_accesorios':
 * @property string $id
 * @property integer $id_vehiculo
 * @property integer $id_version
 * @property string $accesorio
 * @property integer $precio
 * @property string $fecha
 */
class GestionAccesorios extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return GestionAccesorios the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'gestion_accesorios';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('fecha', 'required'),
            array('id_vehiculo, id_version, precio', 'numerical', 'integerOnly' => true),
            array('accesorio', 'length', 'max' => 150),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_vehiculo, id_version, accesorio, precio, fecha', 'safe', 'on' => 'search'),
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
            'id_vehiculo' => 'Id Vehiculo',
            'id_version' => 'Id Version',
            'accesorio' => 'Accesorio',
            'precio' => 'Precio',
            'fecha' => 'Fecha',
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
        $criteria->compare('id_vehiculo', $this->id_vehiculo);
        $criteria->compare('accesorio', $this->accesorio, true);
        $criteria->compare('precio', $this->precio);
        $criteria->compare('fecha', $this->fecha, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
