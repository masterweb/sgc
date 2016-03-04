<?php

/**
 * This is the model class for table "cencuestadoscquestionario".
 *
 * The followings are the available columns in table 'cencuestadoscquestionario':
 * @property integer $id
 * @property integer $cencuestados_id
 * @property integer $cquestionario_id
 * @property integer $usuarios_id
 * @property string $audio
 * @property string $tiempoinicio
 * @property string $tiempofinal
 * @property string $estado
 * @property string $observaciones
 *
 * The followings are the available model relations:
 * @property Cencuestados $cencuestados
 * @property Cquestionario $cquestionario
 * @property Cencuestadospreguntas[] $cencuestadospreguntases
 */
class Cencuestadoscquestionario extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cencuestadoscquestionario';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cencuestados_id, cquestionario_id, usuarios_id, estado', 'required'),
			array('cencuestados_id, cquestionario_id, usuarios_id', 'numerical', 'integerOnly'=>true),
			array('audio', 'length', 'max'=>250), 
			//array('estado, tiempo, sugerido', 'length', 'max'=>45),   
			array('estado, tiempo', 'length', 'max'=>45),   
			array('tiempoinicio, tiempofinal, observaciones', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, cencuestados_id, cquestionario_id, usuarios_id, audio, tiempoinicio, tiempofinal, estado, observaciones', 'safe', 'on'=>'search'),
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
			'usuarios' => array(self::BELONGS_TO, 'Usuarios', 'usuarios_id'),
			'cencuestados' => array(self::BELONGS_TO, 'Cencuestados', 'cencuestados_id'),
			'cquestionario' => array(self::BELONGS_TO, 'Cquestionario', 'cquestionario_id'),
			'cencuestadospreguntases' => array(self::HAS_MANY, 'Cencuestadospreguntas', 'cencuestadoscquestionario_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'cencuestados_id' => 'Cencuestados',
			'cquestionario_id' => 'Cquestionario',
			'usuarios_id' => 'Usuarios',
			'audio' => 'Audio de llamada',
			'tiempoinicio' => 'Tiempoinicio',
			'tiempofinal' => 'Tiempofinal',
			'estado' => 'Estado',
			'tiempo' => 'Tiempo',
			'observaciones' => 'Observaciones',
			//'sugerido' => 'Sugerido',
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
		$criteria->compare('cencuestados_id',$this->cencuestados_id);
		$criteria->compare('cquestionario_id',$this->cquestionario_id);
		$criteria->compare('usuarios_id',$this->usuarios_id);
		$criteria->compare('audio',$this->audio,true);
		$criteria->compare('tiempoinicio',$this->tiempoinicio,true);
		$criteria->compare('tiempofinal',$this->tiempofinal,true);
		$criteria->compare('estado',$this->estado,true);
		$criteria->compare('tiempo',$this->tiempo,true);
		//$criteria->compare('sugerido',$this->sugerido,true);
		$criteria->compare('observaciones',$this->observaciones,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Cencuestadoscquestionario the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
