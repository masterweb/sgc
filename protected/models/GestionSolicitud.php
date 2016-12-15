<?php

/**
 * This is the model class for table "gestion_solicitud".
 *
 * The followings are the available columns in table 'gestion_solicitud':
 * @property string $id
 * @property integer $id_concesionario
 * @property integer $id_informacion
 * @property integer $id_vehiculo
 * @property string $fecha
 */
class GestionSolicitud extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return GestionSolicitud the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'gestion_solicitud';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_vehiculo, fecha', 'required'),
            array('id_concesionario, id_vehiculo, id_informacion', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_concesionario, id_vehiculo, fecha', 'safe', 'on' => 'search'),
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
            'id_concesionario' => 'Id Concesionario',
            'id_informacion' => 'Id Informacion',
            'id_vehiculo' => 'Id Vehiculo',
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
        $criteria->compare('id_concesionario', $this->id_concesionario);
        $criteria->compare('id_informacion', $this->id_informacion);
        $criteria->compare('id_vehiculo', $this->id_vehiculo);
        $criteria->compare('fecha', $this->fecha, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
