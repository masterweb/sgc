<?php

/**
 * This is the model class for table "cquestionario".
 *
 * The followings are the available columns in table 'cquestionario':
 * @property integer $id
 * @property string $nombre
 * @property string $descripcion
 * @property string $fechainicio
 * @property string $fechafin
 * @property string $fecha
 * @property string $estado
 * @property integer $ccampana_id
 * @property integer $cbasedatos_id
 * @property string $automatico
 */
class Cquestionario extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cquestionario';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre, descripcion,guion, fechainicio, fechafin, fecha, estado, ccampana_id, cbasedatos_id', 'required'),
			array('ccampana_id, cbasedatos_id', 'numerical', 'integerOnly'=>true),
			array('nombre', 'length', 'max'=>85),
			array('descripcion', 'length', 'max'=>150),
			array('guion', 'length', 'max'=>500),
			array('estado', 'length', 'max'=>45),
			array('automatico', 'length', 'max'=>2),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, nombre, descripcion, fechainicio, fechafin, fecha, estado, ccampana_id, cbasedatos_id, automatico', 'safe', 'on'=>'search'),
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
			'cbasedatos' => array(self::BELONGS_TO, 'Cbasedatos', 'cbasedatos_id'),
			'ccampana' => array(self::BELONGS_TO, 'Ccampana', 'ccampana_id'),
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
			'descripcion' => 'Descripción',
			'fechainicio' => 'Fecha inicio',
			'fechafin' => 'Fecha fin',
			'fecha' => 'Fecha',
			'estado' => 'Estado',
			'ccampana_id' => 'Campaña',
			'cbasedatos_id' => 'Base de datos',
			'automatico' => 'Actualizar siempre',
			'guion' => 'Guión al encuestar',
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
		$criteria->compare('descripcion',$this->descripcion,true);
		$criteria->compare('guion',$this->guion,true);
		$criteria->compare('fechainicio',$this->fechainicio,true);
		$criteria->compare('fechafin',$this->fechafin,true);
		$criteria->compare('fecha',$this->fecha,true);
		$criteria->compare('estado',$this->estado,true);
		$criteria->compare('ccampana_id',$this->ccampana_id);
		$criteria->compare('cbasedatos_id',$this->cbasedatos_id);
		$criteria->compare('automatico',$this->automatico,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Cquestionario the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
