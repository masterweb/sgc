<?php

/**
 * This is the model class for table "dealers".
 *
 * The followings are the available columns in table 'dealers':
 * @property string $id
 * @property integer $cityid
 * @property string $name
 * @property string $direccion
 * @property string $addres
 * @property string $name
 * @property string $email
 * @property integer $statusid
 */
class Dealers extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Dealers the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'dealers';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cityid, statusid', 'numerical', 'integerOnly' => true),
            array('name, email', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, cityid, name, email, statusid', 'safe', 'on' => 'search'),
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
            'cityid' => 'Cityid',
            'name' => 'Name',
            'email' => 'Email',
            'direccion' => 'Direccion',
            'addres' => 'Addres',
            'statusid' => 'Statusid',
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
        $criteria->compare('cityid', $this->cityid);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('statusid', $this->statusid);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}