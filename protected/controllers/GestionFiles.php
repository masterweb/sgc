<?php

/**
 * This is the model class for table "gestion_files".
 *
 * The followings are the available columns in table 'gestion_files':
 * @property string $id
 * @property string $nombre
 * @property string $descripcion
 * @property integer $tipo
 * @property string $provincia
 * @property string $modelo
 * @property string $fecha_actualizacion
 * @property string $fecha
 */
class GestionFiles extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return GestionFiles the static model class
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
		return 'gestion_files';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tipo', 'required'),
			array('tipo', 'numerical', 'integerOnly'=>true),
			array('nombre, provincia, modelo', 'length', 'max'=>255),
			array('descripcion, fecha_actualizacion, fecha', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nombre, descripcion, tipo, provincia, modelo, fecha_actualizacion, fecha', 'safe', 'on'=>'search'),
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
			'nombre' => 'Nombre',
			'descripcion' => 'Descripcion',
			'tipo' => 'Tipo',
			'provincia' => 'Provincia',
			'modelo' => 'Modelo',
			'fecha_actualizacion' => 'Fecha Actualizacion',
			'fecha' => 'Fecha',
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
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('descripcion',$this->descripcion,true);
		$criteria->compare('tipo',$this->tipo);
		$criteria->compare('provincia',$this->provincia,true);
		$criteria->compare('modelo',$this->modelo,true);
		$criteria->compare('fecha_actualizacion',$this->fecha_actualizacion,true);
		$criteria->compare('fecha',$this->fecha,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}