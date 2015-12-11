<?php

/**
 * This is the model class for table "gestion_historial".
 *
 * The followings are the available columns in table 'gestion_historial':
 * @property string $id
 * @property integer $id_informacion
 * @property integer $id_responsable
 * @property integer $id_vehiculo
 * @property integer $status
 * @property string $observacion
 * @property string $paso
 * @property string $fecha
 */
class GestionHistorial extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return GestionHistorial the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'gestion_historial';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_informacion, observacion', 'required'),
            array('id_responsable,id_informacion, id_vehiculo, status', 'numerical', 'integerOnly' => true),
            array('paso', 'length', 'max' => 20),
            array('fecha', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_responsable, id_vehiculo, status, observacion, paso, fecha', 'safe', 'on' => 'search'),
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
            'id_informacion' => 'Id Informacion',
            'id_responsable' => 'Id Responsable',
            'id_vehiculo' => 'Id Vehiculo',
            'status' => 'Status',
            'observacion' => 'Observacion',
            'paso' => 'Paso',
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
        $criteria->compare('id_responsable', $this->id_responsable);
        $criteria->compare('id_vehiculo', $this->id_vehiculo);
        $criteria->compare('status', $this->status);
        $criteria->compare('observacion', $this->observacion, true);
        $criteria->compare('paso', $this->paso, true);
        $criteria->compare('fecha', $this->fecha, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}