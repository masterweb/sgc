<?php

/**
 * This is the model class for table "gestion_paso_once".
 *
 * The followings are the available columns in table 'gestion_paso_once':
 * @property string $id
 * @property integer $id_informacion
 * @property integer $id_vehiculo
 * @property integer $responsable
 * @property integer $paso
 * @property integer $tipo
 * @property string $observacion
 * @property string $fecha
 */
class GestionPasoOnce extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return GestionPasoOnce the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'gestion_paso_once';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_informacion, responsable', 'required'),
            array('id_informacion, id_vehiculo, paso, tipo, responsable', 'numerical', 'integerOnly' => true),
            array('observacion, fecha', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_informacion, id_vehiculo, paso, tipo, observacion, fecha', 'safe', 'on' => 'search'),
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
            'id_vehiculo' => 'Id Vehiculo',
            'responsable' => 'Id Responsable',
            'paso' => 'Paso',
            'tipo' => 'Tipo',
            'observacion' => 'Observación',
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
        $criteria->compare('id_informacion', $this->id_informacion);
        $criteria->compare('id_vehiculo', $this->id_vehiculo);
        $criteria->compare('paso', $this->paso);
        $criteria->compare('tipo', $this->tipo);
        $criteria->compare('observacion', $this->observacion, true);
        $criteria->compare('fecha', $this->fecha, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
