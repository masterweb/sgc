<?php

/**
 * This is the model class for table "gestion_codigocc".
 *
 * The followings are the available columns in table 'gestion_codigocc':
 * @property string $id
 * @property string $codigo_concesionario
 * @property integer $concesionario_id
 */
class GestionCodigocc extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return GestionCodigocc the static model class
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
		return 'gestion_codigocc';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('concesionario_id', 'numerical', 'integerOnly'=>true),
			array('codigo_concesionario', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, codigo_concesionario, concesionario_id', 'safe', 'on'=>'search'),
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
			'codigo_concesionario' => 'Codigo Concesionario',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('codigo_concesionario',$this->codigo_concesionario,true);
		$criteria->compare('concesionario_id',$this->concesionario_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}