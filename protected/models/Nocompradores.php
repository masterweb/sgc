<?php

/**
 * This is the model class for table "nocompradores".
 *
 * The followings are the available columns in table 'nocompradores':
 * @property integer $id
 * @property string $preguntauno
 * @property string $experienciaasesor
 * @property string $caracteristicas
 * @property string $otros
 * @property string $compro
 * @property string $nuevo
 * @property string $marca
 * @property string $modelo
 * @property string $porque
 * @property string $donde
 * @property string $fecha
 * @property integer $usuario_id
 * @property integer $gestiondiaria_id
 * @property string $nombre
 * @property string $apellido
 * @property string $cedula
 * @property string $email
 * @property string $ceular
 */
class Nocompradores extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nocompradores';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('usuario_id, gestiondiaria_id', 'required'),
			array('usuario_id, gestiondiaria_id', 'numerical', 'integerOnly'=>true),
			array('preguntauno, compro, nuevo, marca, modelo, porque, donde', 'length', 'max'=>250),
			array('fecha, cedula, ceular', 'length', 'max'=>45),
			array('nombre, apellido', 'length', 'max'=>155),
			array('email', 'length', 'max'=>145),
			array('experienciaasesor, caracteristicas, otros', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, preguntauno, experienciaasesor, caracteristicas, otros, compro, nuevo, marca, modelo, porque, donde, fecha, usuario_id, gestiondiaria_id, nombre, apellido, cedula, email, ceular', 'safe', 'on'=>'search'),
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
			'preguntauno' => 'Preguntauno',
			'experienciaasesor' => 'Experienciaasesor',
			'caracteristicas' => 'Caracteristicas',
			'otros' => 'Otros',
			'compro' => 'Compro',
			'nuevo' => 'Nuevo',
			'marca' => 'Marca',
			'modelo' => 'Modelo',
			'porque' => 'Porque',
			'donde' => 'Donde',
			'fecha' => 'Fecha',
			'usuario_id' => 'Usuario',
			'gestiondiaria_id' => 'Gestiondiaria',
			'nombre' => 'Nombre',
			'apellido' => 'Apellido',
			'cedula' => 'Cedula',
			'email' => 'Email',
			'ceular' => 'Ceular',
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
		$criteria->compare('preguntauno',$this->preguntauno,true);
		$criteria->compare('experienciaasesor',$this->experienciaasesor,true);
		$criteria->compare('caracteristicas',$this->caracteristicas,true);
		$criteria->compare('otros',$this->otros,true);
		$criteria->compare('compro',$this->compro,true);
		$criteria->compare('nuevo',$this->nuevo,true);
		$criteria->compare('marca',$this->marca,true);
		$criteria->compare('modelo',$this->modelo,true);
		$criteria->compare('porque',$this->porque,true);
		$criteria->compare('donde',$this->donde,true);
		$criteria->compare('fecha',$this->fecha,true);
		$criteria->compare('usuario_id',$this->usuario_id);
		$criteria->compare('gestiondiaria_id',$this->gestiondiaria_id);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('apellido',$this->apellido,true);
		$criteria->compare('cedula',$this->cedula,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('ceular',$this->ceular,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Nocompradores the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
