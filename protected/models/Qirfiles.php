<?php

/**
 * This is the model class for table "qirfiles".
 *
 * The followings are the available columns in table 'qirfiles':
 * @property integer $id
 * @property integer $qirId
 * @property string $nombre
 * @property string $num_reporte
 *
 * The followings are the available model relations:
 * @property Qir $qir
 */
class Qirfiles extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Qirfiles the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'qirfiles';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('nombre, num_reporte', 'required'),
            array('qirId', 'numerical', 'integerOnly' => true),
            array('nombre', 'length', 'max' => 255),
            array('num_reporte', 'length', 'max' => 100),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, qirId, nombre, num_reporte', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'qir' => array(self::BELONGS_TO, 'Qir', 'qirId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'qirId' => 'Qir',
            'nombre' => 'Nombre',
            'num_reporte' => 'Num Reporte',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('qirId', $this->qirId);
        $criteria->compare('nombre', $this->nombre, true);
        $criteria->compare('num_reporte', $this->num_reporte, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
