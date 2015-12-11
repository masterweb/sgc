<?php

/**
 * This is the model class for table "gestion_presentacion".
 *
 * The followings are the available columns in table 'gestion_presentacion':
 * @property string $id
 * @property string $preg1_duda
 * @property string $preg2_necesidades
 * @property string $preg3_satisfecho
 * @property string $preg1_sec1_duda
 * @property string $preg2_sec1_necesidades
 * @property integer $id_vehiculo
 * @property string $fecha
 * @property integer $id_informacion
 */
class GestionPresentacion extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return GestionPresentacion the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'gestion_presentacion';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('preg1_duda, preg2_necesidades, preg3_satisfecho, preg1_sec1_duda, preg2_sec1_necesidades', 'length', 'max' => 20),
            array('id_vehiculo, id_informacion', 'numerical', 'integerOnly' => true),
            array('fecha', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, preg1_duda, preg2_necesidades, preg3_satisfecho, preg1_sec1_duda, preg2_sec1_necesidades, fecha', 'safe', 'on' => 'search'),
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
            'preg1_duda' => 'Preg1 Duda',
            'preg2_necesidades' => 'Preg2 Necesidades',
            'preg3_satisfecho' => 'Preg3 Satisfecho',
            'preg1_sec1_duda' => 'Preg1 Sec1 Duda',
            'preg2_sec1_necesidades' => 'Preg2 Sec1 Necesidades',
            'id_vehiculo' => 'Id Vehiculo',
            'fecha' => 'Fecha',
            'id_informacion' => 'Id Informacion'
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
        $criteria->compare('preg1_duda', $this->preg1_duda, true);
        $criteria->compare('preg2_necesidades', $this->preg2_necesidades, true);
        $criteria->compare('preg3_satisfecho', $this->preg3_satisfecho, true);
        $criteria->compare('preg1_sec1_duda', $this->preg1_sec1_duda, true);
        $criteria->compare('preg2_sec1_necesidades', $this->preg2_sec1_necesidades, true);
        $criteria->compare('fecha', $this->fecha, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
