<?php

/**
 * This is the model class for table "cencuestadospreguntas".
 *
 * The followings are the available columns in table 'cencuestadospreguntas':
 * @property integer $id
 * @property integer $pregunta_id
 * @property string $respuesta
 * @property string $fecha
 * @property integer $cencuestadoscquestionario_id
 *
 * The followings are the available model relations:
 * @property Cencuestadoscquestionario $cencuestadoscquestionario
 */
class Cencuestadospreguntas extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cencuestadospreguntas';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pregunta_id, respuesta, fecha, cencuestadoscquestionario_id', 'required'),
			array('pregunta_id, cencuestadoscquestionario_id', 'numerical', 'integerOnly'=>true),
			array('respuesta', 'length', 'max'=>450),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, pregunta_id, respuesta, fecha, cencuestadoscquestionario_id', 'safe', 'on'=>'search'),
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
			'cencuestadoscquestionario' => array(self::BELONGS_TO, 'Cencuestadoscquestionario', 'cencuestadoscquestionario_id'),
			'copcionpregunta' => array(self::BELONGS_TO, 'Copcionpregunta', 'copcionpregunta_id'),
			'cmatrizpregunta' => array(self::BELONGS_TO, 'Cmatrizpregunta', 'cmatrizpregunta_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'pregunta_id' => 'Pregunta',
			'copcionpregunta_id'=>'copcionpregunta_id',
			'cmatrizpregunta_id'=>'cmatrizpregunta_id',
			'respuesta' => 'Respuesta',
			'fecha' => 'Fecha',
			'cencuestadoscquestionario_id' => 'Cencuestadoscquestionario',
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
		//cmatrizpregunta_id @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('pregunta_id',$this->pregunta_id);
		$criteria->compare('respuesta',$this->respuesta,true);
		$criteria->compare('fecha',$this->fecha,true);
		$criteria->compare('cencuestadoscquestionario_id',$this->cencuestadoscquestionario_id);
		$criteria->compare('copcionpregunta_id',$this->copcionpregunta_id);
		$criteria->compare('cmatrizpregunta_id',$this->cmatrizpregunta_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Cencuestadospreguntas the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
