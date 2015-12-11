<?php

/**
 * This is the model class for table "accesoregistro".
 *
 * The followings are the available columns in table 'accesoregistro':
 * @property integer $idconfirmado
 * @property integer $usuarios_id
 * @property integer $administrador
 * @property string $descripcion
 * @property string $estado
 * @property string $fecha
 */
class Accesoregistro extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'accesoregistro';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('usuarios_id, administrador, descripcion, estado, fecha', 'required'),
			array('usuarios_id, administrador', 'numerical', 'integerOnly'=>true),
			array('estado, fecha', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('idconfirmado, usuarios_id, administrador, descripcion, estado, fecha', 'safe', 'on'=>'search'),
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
			'idconfirmado' => 'Idconfirmado',
			'usuarios_id' => 'Usuarios',
			'administrador' => 'Administrador',
			'descripcion' => 'Descripcion',
			'estado' => 'Confirmar/Rechazar',
			'fecha' => 'Fecha',
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

		$criteria->compare('idconfirmado',$this->idconfirmado);
		$criteria->compare('usuarios_id',$this->usuarios_id);
		$criteria->compare('administrador',$this->administrador);
		$criteria->compare('descripcion',$this->descripcion,true);
		$criteria->compare('estado',$this->estado,true);
		$criteria->compare('fecha',$this->fecha,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Accesoregistro the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
