<?php

/**
 * This is the model class for table "copcionpregunta".
 *
 * The followings are the available columns in table 'copcionpregunta':
 * @property integer $id
 * @property string $detalle
 * @property string $valor
 * @property integer $cpregunta_id
 *
 * The followings are the available model relations:
 * @property Cpregunta $cpregunta
 */
class Copcionpregunta extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Copcionpregunta the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'copcionpregunta';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('detalle, cpregunta_id', 'required'),
			array('cpregunta_id', 'numerical', 'integerOnly'=>true),
			array('detalle', 'length', 'max'=>150),
			array('valor', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, detalle, valor, cpregunta_id', 'safe', 'on'=>'search'),
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
			'cpregunta' => array(self::BELONGS_TO, 'Cpregunta', 'cpregunta_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'detalle' => 'Detalle',
			'valor' => 'Valor',
			'cpregunta_id' => 'Cpregunta',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('detalle',$this->detalle,true);
		$criteria->compare('valor',$this->valor,true);
		$criteria->compare('cpregunta_id',$this->cpregunta_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}