<?php

/**
 * This is the model class for table "gestion_prospeccion_rp".
 *
 * The followings are the available columns in table 'gestion_prospeccion_rp':
 * @property string $id
 * @property string $id_informacion
 * @property integer $preg1
 * @property integer $preg2
 * @property integer $preg3
 * @property integer $preg4
 * @property integer $preg5
 * @property integer $preg6
 * @property integer $preg3_sec1
 * @property string $preg3_sec2
 * @property string $preg3_sec3
 * @property string $preg3_sec4
 * @property string $preg4_sec1
 * @property string $preg4_sec2
 * @property string $preg4_sec3
 * @property string $preg4_sec4
 * @property string $preg4_sec5
 * @property string $preg5_sec1
 * @property string $preg5_sec2
 * @property string $preg5_sec3
 * @property string $fecha
 */
class GestionProspeccionRp extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return GestionProspeccionRp the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'gestion_prospeccion_rp';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('preg1, preg2, preg3, preg4, preg5, preg6, preg3_sec1', 'numerical', 'integerOnly' => true),
            array('preg3_sec2, preg3_sec3, preg3_sec4', 'length', 'max' => 20),
            array('preg4_sec1, preg5_sec1, preg5_sec2, preg5_sec3', 'length', 'max' => 80),
            array('preg4_sec2', 'length', 'max' => 150),
            array('preg4_sec3, preg4_sec4, preg4_sec5', 'length', 'max' => 100),
            array('fecha', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, preg1, preg2, preg3, preg4, preg5, preg6, preg3_sec1, preg3_sec2, preg3_sec3, preg3_sec4, preg4_sec1, preg4_sec2, preg4_sec3, preg4_sec4, preg5_sec1, preg5_sec2, preg5_sec3, fecha', 'safe', 'on' => 'search'),
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
            'id_informacion' => 'ID Info',
            'preg1' => 'Preg1',
            'preg2' => 'Preg2',
            'preg3' => 'Preg3',
            'preg4' => 'Preg4',
            'preg5' => 'Preg5',
            'preg6' => 'Preg6',
            'preg3_sec1' => 'Preg3 Sec1',
            'preg3_sec2' => 'Preg3 Sec2',
            'preg3_sec3' => 'Preg3 Sec3',
            'preg3_sec4' => 'Preg3 Sec4',
            'preg4_sec1' => 'Preg4 Sec1',
            'preg4_sec2' => 'Preg4 Sec2',
            'preg4_sec3' => 'Preg4 Sec3',
            'preg4_sec4' => 'Preg4 Sec4',
            'preg4_sec5' => 'Preg4 Sec5',
            'preg5_sec1' => 'Preg5 Sec1',
            'preg5_sec2' => 'Preg5 Sec2',
            'preg5_sec3' => 'Preg5 Sec3',
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
        $criteria->compare('preg1', $this->preg1);
        $criteria->compare('preg2', $this->preg2);
        $criteria->compare('preg3', $this->preg3);
        $criteria->compare('preg4', $this->preg4);
        $criteria->compare('preg5', $this->preg5);
        $criteria->compare('preg6', $this->preg6);
        $criteria->compare('preg3_sec1', $this->preg3_sec1);
        $criteria->compare('preg3_sec2', $this->preg3_sec2, true);
        $criteria->compare('preg3_sec3', $this->preg3_sec3, true);
        $criteria->compare('preg3_sec4', $this->preg3_sec4, true);
        $criteria->compare('preg4_sec1', $this->preg4_sec1, true);
        $criteria->compare('preg4_sec2', $this->preg4_sec2, true);
        $criteria->compare('preg4_sec3', $this->preg4_sec3, true);
        $criteria->compare('preg4_sec4', $this->preg4_sec4, true);
        $criteria->compare('preg5_sec1', $this->preg5_sec1, true);
        $criteria->compare('preg5_sec2', $this->preg5_sec2, true);
        $criteria->compare('preg5_sec3', $this->preg5_sec3, true);
        $criteria->compare('fecha', $this->fecha, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}