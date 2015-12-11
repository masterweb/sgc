<?php

/**
 * This is the model class for table "tbl_marcas".
 *
 * The followings are the available columns in table 'tbl_marcas':
 * @property string $id
 * @property string $categoria1
 * @property string $categoria2
 * @property string $marca
 * @property string $modelo
 * @property string $submodelo
 * @property string $descripcion
 * @property integer $pvp
 * @property integer $ptoma
 * @property string $year
 * @property string $combustible
 * @property string $cilindrada
 * @property string $tipo
 */
class Marcas extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_marcas';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pvp, ptoma', 'numerical', 'integerOnly'=>true),
			array('categoria1, categoria2', 'length', 'max'=>150),
			array('marca, modelo, submodelo', 'length', 'max'=>100),
			array('year, combustible, cilindrada, tipo', 'length', 'max'=>50),
			array('descripcion', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, categoria1, categoria2, marca, modelo, submodelo, descripcion, pvp, ptoma, year, combustible, cilindrada, tipo', 'safe', 'on'=>'search'),
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
			'categoria1' => 'Categoria1',
			'categoria2' => 'Categoria2',
			'marca' => 'Marca',
			'modelo' => 'Modelo',
			'submodelo' => 'Submodelo',
			'descripcion' => 'Descripcion',
			'pvp' => 'Pvp',
			'ptoma' => 'Ptoma',
			'year' => 'Year',
			'combustible' => 'Combustible',
			'cilindrada' => 'Cilindrada',
			'tipo' => 'Tipo',
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
		$criteria->compare('categoria1',$this->categoria1,true);
		$criteria->compare('categoria2',$this->categoria2,true);
		$criteria->compare('marca',$this->marca,true);
		$criteria->compare('modelo',$this->modelo,true);
		$criteria->compare('submodelo',$this->submodelo,true);
		$criteria->compare('descripcion',$this->descripcion,true);
		$criteria->compare('pvp',$this->pvp);
		$criteria->compare('ptoma',$this->ptoma);
		$criteria->compare('year',$this->year,true);
		$criteria->compare('combustible',$this->combustible,true);
		$criteria->compare('cilindrada',$this->cilindrada,true);
		$criteria->compare('tipo',$this->tipo,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Marcas the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
