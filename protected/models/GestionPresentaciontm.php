<?php

/**
 * This is the model class for table "gestion_presentaciontm".
 *
 * The followings are the available columns in table 'gestion_presentaciontm':
 * @property string $id
 * @property integer $id_informacion
 * @property string $seguimiento
 * @property integer $presentacion
 * @property string $img
 * @property string $observaciones
 * @property string $fecha
 */
class GestionPresentaciontm extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'gestion_presentaciontm';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_informacion, presentacion', 'numerical', 'integerOnly'=>true),
			array('seguimiento', 'length', 'max'=>255),
			array('img', 'length', 'max'=>300),
			array('observaciones, fecha', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_informacion, seguimiento, presentacion, img, observaciones, fecha', 'safe', 'on'=>'search'),
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
			'id_informacion' => 'Id Informacion',
			'seguimiento' => 'Seguimiento',
			'presentacion' => 'Presentacion',
			'img' => 'Img',
			'observaciones' => 'Observaciones',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('id_informacion',$this->id_informacion);
		$criteria->compare('seguimiento',$this->seguimiento,true);
		$criteria->compare('presentacion',$this->presentacion);
		$criteria->compare('img',$this->img,true);
		$criteria->compare('observaciones',$this->observaciones,true);
		$criteria->compare('fecha',$this->fecha,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GestionPresentaciontm the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
