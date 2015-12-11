<?php

/**
 * This is the model class for table "control_boletin".
 *
 * The followings are the available columns in table 'control_boletin':
 * @property integer $id
 * @property integer $modelosposventa_id
 * @property integer $boletinpostventa_id
 */
class ControlBoletin extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'control_boletin';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('modelosposventa_id, boletinpostventa_id', 'required'),
			array('modelosposventa_id, boletinpostventa_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, modelosposventa_id, boletinpostventa_id', 'safe', 'on'=>'search'),
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
			'boletinesposventa' => array(self::BELONGS_TO, 'Boletinpostventa', 'boletinpostventa_id'),
			'modelosposventa' => array(self::BELONGS_TO, 'Modelosposventa', 'modelosposventa_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'modelosposventa_id' => 'Modelosposventa',
			'boletinpostventa_id' => 'Boletinpostventa',
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
		$criteria->compare('modelosposventa_id',$this->modelosposventa_id);
		$criteria->compare('boletinpostventa_id',$this->boletinpostventa_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ControlBoletin the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
