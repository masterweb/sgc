<?php

/**
 * This is the model class for table "cotizacionesnodeseadas".
 *
 * The followings are the available columns in table 'cotizacionesnodeseadas':
 * @property integer $id
 * @property integer $atencion_detalle_id
 * @property string $fecha
 * @property integer $usuario_id
 * @property string $realizado
 * @property string $nombre
 * @property string $apellido
 * @property string $cedula
 * @property string $direccion
 * @property string $provincia
 * @property string $ciudad
 * @property string $telefono
 * @property string $celular
 * @property string $trabajo
 * @property string $email
 * @property integer $modelo_id
 * @property integer $version_id
 * @property integer $ciudadconcesionario_id
 * @property integer $concesionario_id
 */
class Cotizacionesnodeseadas extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Cotizacionesnodeseadas the static model class
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
		return 'cotizacionesnodeseadas';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('atencion_detalle_id, fecha, usuario_id, realizado, modelo_id', 'required'),
			array('atencion_detalle_id, usuario_id, modelo_id, version_id, ciudadconcesionario_id, concesionario_id', 'numerical', 'integerOnly'=>true),
			array('fecha, realizado', 'length', 'max'=>45),
			array('nombre, apellido, email', 'length', 'max'=>250),
			array('cedula', 'length', 'max'=>25),
			array('provincia, ciudad, telefono, celular, trabajo', 'length', 'max'=>50),
			array('direccion', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, atencion_detalle_id, fecha, usuario_id, realizado, nombre, apellido, cedula, direccion, provincia, ciudad, telefono, celular, trabajo, email, modelo_id, version_id, ciudadconcesionario_id, concesionario_id', 'safe', 'on'=>'search'),
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
			'atencion_detalle_id' => 'Atencion Detalle',
			'fecha' => 'Fecha',
			'usuario_id' => 'Usuario',
			'realizado' => 'Realizado',
			'nombre' => 'Nombre',
			'apellido' => 'Apellido',
			'cedula' => 'Cedula',
			'direccion' => 'Direccion',
			'provincia' => 'Provincia',
			'ciudad' => 'Ciudad',
			'telefono' => 'Telefono',
			'celular' => 'Celular',
			'trabajo' => 'Trabajo',
			'email' => 'Email',
			'modelo_id' => 'Modelo',
			'version_id' => 'Version',
			'ciudadconcesionario_id' => 'Ciudadconcesionario',
			'concesionario_id' => 'Concesionario',
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
		$criteria->compare('atencion_detalle_id',$this->atencion_detalle_id);
		$criteria->compare('fecha',$this->fecha,true);
		$criteria->compare('usuario_id',$this->usuario_id);
		$criteria->compare('realizado',$this->realizado,true);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('apellido',$this->apellido,true);
		$criteria->compare('cedula',$this->cedula,true);
		$criteria->compare('direccion',$this->direccion,true);
		$criteria->compare('provincia',$this->provincia,true);
		$criteria->compare('ciudad',$this->ciudad,true);
		$criteria->compare('telefono',$this->telefono,true);
		$criteria->compare('celular',$this->celular,true);
		$criteria->compare('trabajo',$this->trabajo,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('modelo_id',$this->modelo_id);
		$criteria->compare('version_id',$this->version_id);
		$criteria->compare('ciudadconcesionario_id',$this->ciudadconcesionario_id);
		$criteria->compare('concesionario_id',$this->concesionario_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}