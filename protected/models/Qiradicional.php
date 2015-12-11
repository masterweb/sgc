<?php

/**
 * This is the model class for table "qiradicional".
 *
 * The followings are the available columns in table 'qiradicional':
 * @property integer $id
 * @property integer $qirId
 * @property string $vin
 * @property string $num_motor
 * @property string $kilometraje
 * @property string $num_reporte
 *
 * The followings are the available model relations:
 * @property Qir $qir
 */
class Qiradicional extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'qiradicional';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vin, num_motor, kilometraje, num_reporte', 'required'),
			array('qirId', 'numerical', 'integerOnly'=>true),
			array('vin, num_motor', 'length', 'max'=>255),
			array('kilometraje, num_reporte', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, qirId, vin, num_motor, kilometraje, num_reporte', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'qir' => array(self::BELONGS_TO, 'Qir', 'qirId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'qirId' => 'Qir',
			'vin' => 'Vin',
			'num_motor' => 'Num Motor',
			'kilometraje' => 'Kilometraje',
			'num_reporte' => 'Num Reporte',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('qirId',$this->qirId);
		$criteria->compare('vin',$this->vin,true);
		$criteria->compare('num_motor',$this->num_motor,true);
		$criteria->compare('kilometraje',$this->kilometraje,true);
		$criteria->compare('num_reporte',$this->num_reporte,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Qiradicional the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
