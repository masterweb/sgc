<?php

/**
 * This is the model class for table "gestion_amortizacion".
 *
 * The followings are the available columns in table 'gestion_amortizacion':
 * @property string $id
 * @property double $interes
 * @property double $capital_reducido
 * @property double $capital
 * @property double $seguro_desgravamen
 */
class GestionAmortizacion extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return GestionAmortizacion the static model class
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
		return 'gestion_amortizacion';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('interes, capital_reducido, capital, seguro_desgravamen', 'numerical'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, interes, capital_reducido, capital, seguro_desgravamen', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'interes' => 'Interes',
			'capital_reducido' => 'Capital Reducido',
			'capital' => 'Capital',
			'seguro_desgravamen' => 'Seguro Desgravamen',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('interes',$this->interes);
		$criteria->compare('capital_reducido',$this->capital_reducido);
		$criteria->compare('capital',$this->capital);
		$criteria->compare('seguro_desgravamen',$this->seguro_desgravamen);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}