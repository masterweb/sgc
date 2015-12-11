<?php

/**
 * This is the model class for table "cencuestados".
 *
 * The followings are the available columns in table 'cencuestados':
 * @property integer $id
 * @property string $nombre
 * @property string $telefono
 * @property string $celular
 * @property string $email
 * @property string $ciudad
 * @property string $estado
 * @property integer $cquestionario_id
 */
class Cencuestados extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cencuestados';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre, telefono, celular, email, ciudad, estado, cquestionario_id', 'required'),
			array('cquestionario_id', 'numerical', 'integerOnly'=>true),
			array('nombre, email', 'length', 'max'=>150),
			array('telefono, celular', 'length', 'max'=>18),
			array('ciudad, estado,fechanacimiento', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, nombre, telefono, celular, email, ciudad, estado, cquestionario_id', 'safe', 'on'=>'search'),
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
			'cquestionario' => array(self::BELONGS_TO, 'Cquestionario', 'cquestionario_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nombre' => 'Nombre',
			'telefono' => 'Telefono',
			'celular' => 'Celular',
			'email' => 'Email',
			'ciudad' => 'Ciudad',
			'estado' => 'Estado',
			'fechanacimiento' => 'Fecha de Nacimiento',
			'cquestionario_id' => 'Cquestionario',
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
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('telefono',$this->telefono,true);
		$criteria->compare('celular',$this->celular,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('ciudad',$this->ciudad,true);
		$criteria->compare('estado',$this->estado,true);
		$criteria->compare('fechanacimiento',$this->fechanacimiento,true);
		$criteria->compare('cquestionario_id',$this->cquestionario_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Cencuestados the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
