<?php

/**
 * This is the model class for table "qir".
 *
 * The followings are the available columns in table 'qir':
 * @property integer $id
 * @property integer $dealerId
 * @property string $num_reporte
 * @property string $fecha_registro
 * @property integer $modeloPostVentaId
 * @property integer $num_vehiculos_afectados
 * @property string $prioridad
 * @property string $parte_defectuosa
 * @property string $vin
 * @property string $num_motor
 * @property string $transmision
 * @property string $num_parte_casual
 * @property string $detalle_parte_casual
 * @property string $codigo_naturaleza
 * @property string $codigo_casual
 * @property string $fecha_garantia
 * @property string $fecha_fabricacion
 * @property string $kilometraje
 * @property string $fecha_reparacion
 * @property string $titular
 * @property string $descripcion
 * @property string $ingresado
 * @property string $email
 * @property string $circunstancia
 * @property string $periodo_tiempo
 * @property string $rango_velocidad
 * @property string $localizacion
 * @property string $fase_manejo
 * @property string $condicion_camino
 * @property string $etc
 * @property string $vin_adicional
 * @property string $num_motor_adi
 * @property string $kilometraje_adic
 * @property string $estado
 *
 * The followings are the available model relations:
 * @property Dealers $dealer
 * @property Modelosposventa $modeloPostVenta
 * @property Qiradicional[] $qiradicionals
 * @property Qircomentario[] $qircomentarios
 * @property Qirfiles[] $qirfiles
 */
class Qir extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'qir';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dealerId, num_reporte, fecha_registro, modeloPostVentaId, num_vehiculos_afectados, prioridad, parte_defectuosa, vin, num_motor, transmision, num_parte_casual, detalle_parte_casual, codigo_naturaleza, codigo_casual, fecha_garantia, fecha_fabricacion, kilometraje, titular, descripcion,analisis,investigacion,acciones,comentarios, ingresado, email, circunstancia, periodo_tiempo, rango_velocidad, localizacion, fase_manejo, condicion_camino', 'required','message'=>'El campo {attribute} no puede estar vacio'),
			array('dealerId, modeloPostVentaId, num_vehiculos_afectados', 'numerical', 'integerOnly'=>true),
			array('num_reporte, prioridad, parte_defectuosa, vin, num_motor, transmision, num_parte_casual, detalle_parte_casual, codigo_naturaleza, codigo_casual, kilometraje, vin_adicional, num_motor_adi, estado', 'length', 'max'=>100),
			array('titular, ingresado, email, circunstancia, periodo_tiempo, rango_velocidad, localizacion, fase_manejo, condicion_camino, etc', 'length', 'max'=>255),
			array('kilometraje_adic', 'length', 'max'=>200),
			array('fecha_reparacion', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, dealerId, num_reporte, fecha_registro, modeloPostVentaId, num_vehiculos_afectados, prioridad, parte_defectuosa, vin, num_motor, transmision, num_parte_casual, detalle_parte_casual, codigo_naturaleza, codigo_casual, fecha_garantia, fecha_fabricacion, kilometraje, fecha_reparacion, titular, descripcion,analisis,investigacion,acciones,comentarios, ingresado, email, circunstancia, periodo_tiempo, rango_velocidad, localizacion, fase_manejo, condicion_camino, etc, vin_adicional, num_motor_adi, kilometraje_adic, estado', 'safe', 'on'=>'search'),
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
			'dealer' => array(self::BELONGS_TO, 'Dealers', 'dealerId'),
			'modeloPostVenta' => array(self::BELONGS_TO, 'Modelosposventa', 'modeloPostVentaId'),
			'qiradicionals' => array(self::HAS_MANY, 'Qiradicional', 'qirId'),
			'qircomentarios' => array(self::HAS_MANY, 'Qircomentario', 'qirId'),
			'qirfiles' => array(self::HAS_MANY, 'Qirfiles', 'qirId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'dealerId' => 'Dealer',
			'num_reporte' => 'Num Reporte',
			'fecha_registro' => 'Fecha Registro',
			'modeloPostVentaId' => 'Modelo Post Venta',
			'num_vehiculos_afectados' => 'Num Veh&iacute;culos Afectados',
			'prioridad' => 'Prioridad',
			'parte_defectuosa' => 'Parte Defectuosa',
			'vin' => 'Vin',
			'num_motor' => 'Num Motor',
			'transmision' => 'Transmisi&oacute;n',
			'num_parte_casual' => 'Num Parte Casual',
			'detalle_parte_casual' => 'Detalle Parte Casual',
			'codigo_naturaleza' => 'C&oacute;digo Naturaleza',
			'codigo_casual' => 'C&oacute;digo Casual',
			'fecha_garantia' => 'Fecha Garantia',
			'fecha_fabricacion' => 'Fecha Fabricaci&oacute;n',
			'kilometraje' => 'Kilometraje',
			'fecha_reparacion' => 'Fecha Reparaci&oacute;n',
			'titular' => 'Titular',
			'descripcion' => 'Descripci&oacute;n',
			'analisis' => 'An&aacute;lisis',
			'investigacion' => 'Investigaci&oacute;n',
			'acciones' => 'Acciones',
			'comentarios' => 'Comentarios', 
			'email' => 'Email',
			'circunstancia' => 'Circunstancia',
			'periodo_tiempo' => 'Periodo Tiempo',
			'rango_velocidad' => 'Rango Velocidad',
			'localizacion' => 'Localizaci&oacute;n',
			'fase_manejo' => 'Fase Manejo',
			'condicion_camino' => 'Condici&oacute;n Camino',
			'etc' => 'Etc',
			'vin_adicional' => 'Vin Adicional',
			'num_motor_adi' => 'Num Motor Adi',
			'kilometraje_adic' => 'Kilometraje Adic',
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
		$criteria->compare('dealerId',$this->dealerId);
		$criteria->compare('num_reporte',$this->num_reporte,true);
		$criteria->compare('fecha_registro',$this->fecha_registro,true);
		$criteria->compare('modeloPostVentaId',$this->modeloPostVentaId);
		$criteria->compare('num_vehiculos_afectados',$this->num_vehiculos_afectados);
		$criteria->compare('prioridad',$this->prioridad,true);
		$criteria->compare('parte_defectuosa',$this->parte_defectuosa,true);
		$criteria->compare('vin',$this->vin,true);
		$criteria->compare('num_motor',$this->num_motor,true);
		$criteria->compare('transmision',$this->transmision,true);
		$criteria->compare('num_parte_casual',$this->num_parte_casual,true);
		$criteria->compare('detalle_parte_casual',$this->detalle_parte_casual,true);
		$criteria->compare('codigo_naturaleza',$this->codigo_naturaleza,true);
		$criteria->compare('codigo_casual',$this->codigo_casual,true);
		$criteria->compare('fecha_garantia',$this->fecha_garantia,true);
		$criteria->compare('fecha_fabricacion',$this->fecha_fabricacion,true);
		$criteria->compare('kilometraje',$this->kilometraje,true);
		$criteria->compare('fecha_reparacion',$this->fecha_reparacion,true);
		$criteria->compare('titular',$this->titular,true);
		$criteria->compare('descripcion',$this->descripcion,true);
		$criteria->compare('analisis',$this->analisis,true);
		$criteria->compare('investigacion',$this->investigacion,true);
		$criteria->compare('acciones',$this->acciones,true);
		$criteria->compare('comentarios',$this->comentarios,true);
		$criteria->compare('ingresado',$this->ingresado,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('circunstancia',$this->circunstancia,true);
		$criteria->compare('periodo_tiempo',$this->periodo_tiempo,true);
		$criteria->compare('rango_velocidad',$this->rango_velocidad,true);
		$criteria->compare('localizacion',$this->localizacion,true);
		$criteria->compare('fase_manejo',$this->fase_manejo,true);
		$criteria->compare('condicion_camino',$this->condicion_camino,true);
		$criteria->compare('etc',$this->etc,true);
		$criteria->compare('vin_adicional',$this->vin_adicional,true);
		$criteria->compare('num_motor_adi',$this->num_motor_adi,true);
		$criteria->compare('kilometraje_adic',$this->kilometraje_adic,true);
		$criteria->compare('estado',$this->estado,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Qir the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
