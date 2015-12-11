<?php

/**
 * This is the model class for table "cpregunta".
 *
 * The followings are the available columns in table 'cpregunta':
 * @property integer $id
 * @property string $descripcion
 * @property string $fecha
 * @property string $estado
 * @property integer $ctipopregunta_id
 * @property integer $cquestionario_id
 * @property string $tipocontenido
 *
 * The followings are the available model relations:
 * @property Copcionpregunta[] $copcionpreguntas
 * @property Cquestionario $cquestionario
 * @property Ctipopregunta $ctipopregunta
 */
class Cpregunta extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cpregunta';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('descripcion, fecha, estado, ctipopregunta_id, cquestionario_id', 'required'),
			array('ctipopregunta_id, cquestionario_id, orden,copcionpregunta_id', 'numerical', 'integerOnly'=>true),
			array('descripcion', 'length', 'max'=>350),
			array('estado', 'length', 'max'=>45),
			array('tipocontenido', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, descripcion, fecha, estado, ctipopregunta_id, cquestionario_id, tipocontenido', 'safe', 'on'=>'search'),
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
			'copcionpreguntas' => array(self::HAS_MANY, 'Copcionpregunta', 'cpregunta_id'),
			'cquestionario' => array(self::BELONGS_TO, 'Cquestionario', 'cquestionario_id'),
			'ctipopregunta' => array(self::BELONGS_TO, 'Ctipopregunta', 'ctipopregunta_id'),
			'copcionpregunta' => array(self::BELONGS_TO, 'Copcionpregunta', 'copcionpregunta_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'descripcion' => ('Descripción'),
			'fecha' => 'Fecha',
			'estado' => 'Estado',
			'ctipopregunta_id' => 'Ctipopregunta',
			'cquestionario_id' => 'Cquestionario',
			'tipocontenido' => 'Tipo de contenido',
			'orden' => 'Orden de la pregunta',
			'copcionpregunta_id' => 'Opcion de pregunta',
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
		$criteria->compare('descripcion',$this->descripcion,true);
		$criteria->compare('fecha',$this->fecha,true);
		$criteria->compare('estado',$this->estado,true);
		$criteria->compare('ctipopregunta_id',$this->ctipopregunta_id);
		$criteria->compare('cquestionario_id',$this->cquestionario_id);
		$criteria->compare('copcionpregunta_id',$this->copcionpregunta_id);
		$criteria->compare('orden',$this->orden);
		$criteria->compare('tipocontenido',$this->tipocontenido,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Cpregunta the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
