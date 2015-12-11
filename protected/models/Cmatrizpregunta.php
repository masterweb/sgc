<?php

/**
 * This is the model class for table "cmatrizpregunta".
 *
 * The followings are the available columns in table 'cmatrizpregunta':
 * @property integer $id
 * @property string $detalle
 * @property string $valor
 * @property string $fecha
 * @property integer $cpregunta_id
 *
 * The followings are the available model relations:
 * @property Cpregunta $cpregunta
 */
class Cmatrizpregunta extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cmatrizpregunta';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('detalle, valor, fecha, cpregunta_id', 'required'),
			array('cpregunta_id', 'numerical', 'integerOnly'=>true),
			array('detalle', 'length', 'max'=>250),
			array('valor', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, detalle, valor, fecha, cpregunta_id', 'safe', 'on'=>'search'),
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
			'fecha' => 'Fecha',
			'cpregunta_id' => 'Cpregunta',
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
		$criteria->compare('detalle',$this->detalle,true);
		$criteria->compare('valor',$this->valor,true);
		$criteria->compare('fecha',$this->fecha,true);
		$criteria->compare('cpregunta_id',$this->cpregunta_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Cmatrizpregunta the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
