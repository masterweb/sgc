<?php

/**
 * This is the model class for table "gestion_cita".
 *
 * The followings are the available columns in table 'gestion_cita':
 * @property string $id
 * @property integer $id_informacion
 * @property integer $order
 * @property string $fecha
 */
class GestionCita extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return GestionCita the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'gestion_cita';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_informacion', 'required'),
            array('id_informacion, order', 'numerical', 'integerOnly' => true),
            array('fecha', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_informacion, orden, fecha', 'safe', 'on' => 'search'),
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
            'order' => 'Orden',
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
        $criteria->compare('order', $this->order);
        $criteria->compare('fecha', $this->fecha, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
