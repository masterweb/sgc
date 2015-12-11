<?php

/**
 * This is the model class for table "cbasedatos".
 *
 * The followings are the available columns in table 'cbasedatos':
 * @property integer $id
 * @property string $nombre
 * @property string $descripcion
 * @property string $base
 * @property string $usuario
 * @property string $password
 * @property string $tabla
 * @property string $estado
 *
 * The followings are the available model relations:
 * @property Ccampana[] $ccampanas
 */
class Cbasedatos extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cbasedatos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre, descripcion, base, usuario, password, tabla, estado', 'required'),
			array('nombre, base, estado', 'length', 'max'=>45),
			array('descripcion, password', 'length', 'max'=>250),
			array('usuario, tabla', 'length', 'max'=>150),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, nombre, descripcion, base, usuario, password, tabla, estado', 'safe', 'on'=>'search'),
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
			'ccampanas' => array(self::HAS_MANY, 'Ccampana', 'cbasedatos_id'),
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
			'base' => 'Base',
			'usuario' => 'Usuario',
			'password' => 'Password',
			'tabla' => 'Tabla',
			'estado' => 'Estado',
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
		$criteria->compare('base',$this->base,true);
		$criteria->compare('usuario',$this->usuario,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('tabla',$this->tabla,true);
		$criteria->compare('estado',$this->estado,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Cbasedatos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
