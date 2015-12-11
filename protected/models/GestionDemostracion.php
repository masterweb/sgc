<?php

/**
 * This is the model class for table "gestion_demostracion".
 *
 * The followings are the available columns in table 'gestion_demostracion':
 * @property string $id
 * @property string $preg1
 * @property string $preg1_licencia
 * @property string $preg1_agendamiento
 * @property string $preg1_observaciones
 * @property integer $id_informacion
 * @property integer $id_vehiculo
 * @property string $fecha
 */
class GestionDemostracion extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return GestionDemostracion the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'gestion_demostracion';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_informacion,id_vehiculo', 'numerical', 'integerOnly' => true),
            array('preg1', 'length', 'max' => 20),
            array('preg1_licencia, preg1_agendamiento, preg1_observaciones', 'length', 'max' => 100),
            array('fecha', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, preg1, preg1_licencia, preg1_agendamiento, id_informacion, fecha', 'safe', 'on' => 'search'),
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
            'preg1' => 'Preg1',
            'preg1_licencia' => 'Preg1 Licencia',
            'preg1_agendamiento' => 'Preg1 Agendamiento',
            'preg1_Observaciones' => 'Observaciones',
            'id_informacion' => 'Id Informacion',
            'id_vechiculo' => 'Id Vehiculo',
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
        $criteria->compare('preg1', $this->preg1, true);
        $criteria->compare('preg1_licencia', $this->preg1_licencia, true);
        $criteria->compare('preg1_agendamiento', $this->preg1_agendamiento, true);
        $criteria->compare('id_informacion', $this->id_informacion);
        $criteria->compare('fecha', $this->fecha, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
