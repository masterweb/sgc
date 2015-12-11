<?php

/**
 * This is the model class for table "permiso".
 *
 * The followings are the available columns in table 'permiso':
 * @property integer $id
 * @property integer $cargoId
 * @property integer $accesoSistemaId
 * @property string $fecha
 * @property string $estado
 *
 * The followings are the available model relations:
 * @property Cargo $cargo
 * @property Accesosistema $accesoSistema
 */
class Permiso extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'permiso';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cargoId, accesoSistemaId, fecha, estado', 'required'),
			array('cargoId, accesoSistemaId', 'numerical', 'integerOnly'=>true),
			array('estado', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, cargoId, accesoSistemaId, fecha, estado', 'safe', 'on'=>'search'),
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
			'cargo' => array(self::BELONGS_TO, 'Cargo', 'cargoId'),
			'accesoSistema' => array(self::BELONGS_TO, 'Accesosistema', 'accesoSistemaId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'cargoId' => 'Cargo',
			'accesoSistemaId' => 'Acceso Sistema',
			'fecha' => 'Fecha',
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
		$criteria->compare('cargoId',$this->cargoId);
		$criteria->compare('accesoSistemaId',$this->accesoSistemaId);
		$criteria->compare('fecha',$this->fecha,true);
		$criteria->compare('estado',$this->estado,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Permiso the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
