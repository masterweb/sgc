<?php

/**
 * This is the model class for table "qircomentario".
 *
 * The followings are the available columns in table 'qircomentario':
 * @property integer $id
 * @property integer $qirId
 * @property string $estado
 * @property string $fecha
 * @property string $para
 * @property string $de
 * @property string $asunto
 * @property string $num_reporte
 * @property string $modelo
 * @property string $comentario
 *
 * The followings are the available model relations:
 * @property QirComentarioFile[] $qirComentarioFiles
 * @property Qir $qir
 */
class Qircomentario extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'qircomentario';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('estado, fecha, para, de, asunto, num_reporte, modelo, comentario', 'required'),
			array('qirId', 'numerical', 'integerOnly'=>true),
			array('estado, para, de, asunto, modelo', 'length', 'max'=>255),
			array('num_reporte', 'length', 'max'=>100),
			//array('para', 'email'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, qirId, estado, fecha, para, de, asunto, num_reporte, modelo, comentario', 'safe', 'on'=>'search'),
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
			'qirComentarioFiles' => array(self::HAS_MANY, 'QirComentarioFile', 'qirComentarioId'),
			'qir' => array(self::BELONGS_TO, 'Qir', 'qirId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'qirId' => 'Qir',
			'estado' => 'Estado',
			'fecha' => 'Fecha',
			'para' => 'Para',
			'de' => 'De',
			'asunto' => 'Asunto',
			'num_reporte' => 'Num Reporte',
			'modelo' => 'Modelo',
			'comentario' => 'Comentario',
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
		$criteria->compare('qirId',$this->qirId);
		$criteria->compare('estado',$this->estado,true);
		$criteria->compare('fecha',$this->fecha,true);
		$criteria->compare('para',$this->para,true);
		$criteria->compare('de',$this->de,true);
		$criteria->compare('asunto',$this->asunto,true);
		$criteria->compare('num_reporte',$this->num_reporte,true);
		$criteria->compare('modelo',$this->modelo,true);
		$criteria->compare('comentario',$this->comentario,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Qircomentario the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
