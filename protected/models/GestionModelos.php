<?php

/**
 * This is the model class for table "gestion_modelos".
 *
 * The followings are the available columns in table 'gestion_modelos':
 * @property string $id
 * @property integer $id_modelo
 * @property string $id_versiones
 * @property string $nombre_modelo
 * @property string $status
 * @property string $ensamblaje
 * @property integer $orden
 */
class GestionModelos extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return GestionModelos the static model class
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
		return 'gestion_modelos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id,id_modelo', 'required'),
			array('id_modelo,orden', 'numerical', 'integerOnly'=>true),
			array('id_versiones', 'length', 'max'=>30),
			array('nombre_modelo', 'length', 'max'=>50),
			array('status', 'length', 'max'=>20),
			array('ensamblaje', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_modelo, id_versiones, nombre_modelo, status, ensamblaje', 'safe', 'on'=>'search'),
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
			'id_modelo' => 'Id Modelo',
			'id_versiones' => 'Id Versiones',
			'nombre_modelo' => 'Nombre Modelo',
			'status' => 'Status',
			'ensamblaje' => 'Ensamblaje',
                    'orded' => 'Orden',
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
		$criteria->compare('id_modelo',$this->id_modelo);
		$criteria->compare('id_versiones',$this->id_versiones,true);
		$criteria->compare('nombre_modelo',$this->nombre_modelo,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('ensamblaje',$this->ensamblaje,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}