<?php

/**
 * This is the model class for table "gr_concesionarios".
 *
 * The followings are the available columns in table 'gr_concesionarios':
 * @property string $id
 * @property integer $id_grupo
 * @property string $nombre
 * @property integer $provincia
 * @property dealer_id $provincia
 */
class Concesionarios extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Concesionarios the static model class
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
		return 'gr_concesionarios';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_grupo, provincia', 'numerical', 'integerOnly'=>true),
			array('nombre', 'length', 'max'=>150),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_grupo, nombre, provincia', 'safe', 'on'=>'search'),
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
			'id_grupo' => 'Id Grupo',
			'nombre' => 'Nombre',
			'provincia' => 'Provincia',
                    'dealer_id' => 'Dealer id',
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
		$criteria->compare('id_grupo',$this->id_grupo);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('provincia',$this->provincia);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}