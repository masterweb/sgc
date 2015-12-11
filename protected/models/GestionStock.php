<?php

/**
 * This is the model class for table "gestion_stock".
 *
 * The followings are the available columns in table 'gestion_stock':
 * @property string $id
 * @property string $fecha_w
 * @property string $embarque
 * @property string $bloque
 * @property string $familia
 * @property string $code
 * @property string $version
 * @property string $equip
 * @property string $fsc
 * @property string $referencia
 * @property string $aeade
 * @property string $segmento
 * @property string $grupo
 * @property string $concesionario
 * @property string $color_origen
 * @property string $color_plano
 * @property string $my
 * @property string $chasis
 * @property string $edad
 * @property string $rango
 * @property string $fact
 * @property string $cod_aeade
 * @property string $pvc
 * @property string $stock
 */
class GestionStock extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return GestionStock the static model class
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
		return 'gestion_stock';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('embarque, bloque, code, fsc, aeade, grupo, concesionario, color_origen, color_plano, pvc', 'length', 'max'=>50),
			array('familia, referencia, segmento, fact', 'length', 'max'=>80),
			array('version', 'length', 'max'=>100),
			array('my, chasis, edad, rango, cod_aeade', 'length', 'max'=>20),
			array('stock', 'length', 'max'=>30),
			array('fecha_w, equip', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, fecha_w, embarque, bloque, familia, code, version, equip, fsc, referencia, aeade, segmento, grupo, concesionario, color_origen, color_plano, my, chasis, edad, rango, fact, cod_aeade, pvc, stock', 'safe', 'on'=>'search'),
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
			'fecha_w' => 'Fecha W',
			'embarque' => 'Embarque',
			'bloque' => 'Bloque',
			'familia' => 'Familia',
			'code' => 'Code',
			'version' => 'Version',
			'equip' => 'Equip',
			'fsc' => 'Fsc',
			'referencia' => 'Referencia',
			'aeade' => 'Aeade',
			'segmento' => 'Segmento',
			'grupo' => 'Grupo',
			'concesionario' => 'Concesionario',
			'color_origen' => 'Color Origen',
			'color_plano' => 'Color Plano',
			'my' => 'My',
			'chasis' => 'Chasis',
			'edad' => 'Edad',
			'rango' => 'Rango',
			'fact' => 'Fact',
			'cod_aeade' => 'Cod Aeade',
			'pvc' => 'Pvc',
			'stock' => 'Stock',
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
		$criteria->compare('fecha_w',$this->fecha_w,true);
		$criteria->compare('embarque',$this->embarque,true);
		$criteria->compare('bloque',$this->bloque,true);
		$criteria->compare('familia',$this->familia,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('version',$this->version,true);
		$criteria->compare('equip',$this->equip,true);
		$criteria->compare('fsc',$this->fsc,true);
		$criteria->compare('referencia',$this->referencia,true);
		$criteria->compare('aeade',$this->aeade,true);
		$criteria->compare('segmento',$this->segmento,true);
		$criteria->compare('grupo',$this->grupo,true);
		$criteria->compare('concesionario',$this->concesionario,true);
		$criteria->compare('color_origen',$this->color_origen,true);
		$criteria->compare('color_plano',$this->color_plano,true);
		$criteria->compare('my',$this->my,true);
		$criteria->compare('chasis',$this->chasis,true);
		$criteria->compare('edad',$this->edad,true);
		$criteria->compare('rango',$this->rango,true);
		$criteria->compare('fact',$this->fact,true);
		$criteria->compare('cod_aeade',$this->cod_aeade,true);
		$criteria->compare('pvc',$this->pvc,true);
		$criteria->compare('stock',$this->stock,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}