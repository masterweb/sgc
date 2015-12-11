<?php

/**
 * This is the model class for table "gr_concesionarios".
 *
 * The followings are the available columns in table 'gr_concesionarios':
 * @property string $id
 * @property integer $id_grupo
 * @property string $nombre
 * @property integer $provincia
 * @property integer $dealer_id
 */
class GrConcesionarios extends CActiveRecord
{
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
			array('id_grupo, provincia, dealer_id', 'numerical', 'integerOnly'=>true),
			array('nombre', 'length', 'max'=>150),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_grupo, nombre, provincia, dealer_id', 'safe', 'on'=>'search'),
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
		'dealer' => array(self::BELONGS_TO, 'Dealers', 'dealer_id'),
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
			'dealer_id' => 'Dealer',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('id_grupo',$this->id_grupo);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('provincia',$this->provincia);
		$criteria->compare('dealer_id',$this->dealer_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GrConcesionarios the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
