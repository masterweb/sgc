<?php

/**
 * This is the model class for table "gestion_cierre".
 *
 * The followings are the available columns in table 'gestion_cierre':
 * @property integer $id
 * @property string $numero_chasis
 * @property string $numero_modelo
 * @property string $nombre_propietario
 * @property string $color_vehiculo
 * @property string $factura
 * @property string $concesionario
 * @property string $fecha_venta
 * @property string $year
 * @property string $color_origen
 * @property string $identificacion
 * @property string $precio_venta
 * @property string $calle_principal
 * @property string $numero_calle
 * @property string $telefono_propietario
 * @property string $grupo_concesionario
 * @property string $forma_pago
 * @property string $observacion
 * @property string $fecha
 * @property integer $id_informacion
 * @property integer $id_vehiculo
 */
class GestionCierre extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return GestionCierre the static model class
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
		return 'gestion_cierre';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fecha, id_informacion, id_vehiculo', 'required'),
			array('id_informacion, id_vehiculo', 'numerical', 'integerOnly'=>true),
			array('numero_chasis, calle_principal', 'length', 'max'=>200),
			array('numero_modelo, factura, precio_venta', 'length', 'max'=>50),
			array('nombre_propietario', 'length', 'max'=>250),
			array('color_vehiculo, concesionario, identificacion, forma_pago', 'length', 'max'=>20),
			array('fecha_venta', 'length', 'max'=>25),
			array('year, numero_calle, telefono_propietario', 'length', 'max'=>10),
			array('color_origen, grupo_concesionario', 'length', 'max'=>30),
			array('observacion', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, numero_chasis, numero_modelo, nombre_propietario, color_vehiculo, factura, concesionario, fecha_venta, year, color_origen, identificacion, precio_venta, calle_principal, numero_calle, telefono_propietario, grupo_concesionario, forma_pago, observacion, fecha, id_informacion, id_vehiculo', 'safe', 'on'=>'search'),
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
			'numero_chasis' => 'Numero Chasis',
			'numero_modelo' => 'Numero Modelo',
			'nombre_propietario' => 'Nombre Propietario',
			'color_vehiculo' => 'Color Vehiculo',
			'factura' => 'Factura',
			'concesionario' => 'Concesionario',
			'fecha_venta' => 'Fecha Venta',
			'year' => 'Year',
			'color_origen' => 'Color Origen',
			'identificacion' => 'Identificacion',
			'precio_venta' => 'Precio Venta',
			'calle_principal' => 'Calle Principal',
			'numero_calle' => 'Numero Calle',
			'telefono_propietario' => 'Telefono Propietario',
			'grupo_concesionario' => 'Grupo Concesionario',
			'forma_pago' => 'Forma Pago',
			'observacion' => 'Observacion',
			'fecha' => 'Fecha',
			'id_informacion' => 'Id Informacion',
			'id_vehiculo' => 'Id Vehiculo',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('numero_chasis',$this->numero_chasis,true);
		$criteria->compare('numero_modelo',$this->numero_modelo,true);
		$criteria->compare('nombre_propietario',$this->nombre_propietario,true);
		$criteria->compare('color_vehiculo',$this->color_vehiculo,true);
		$criteria->compare('factura',$this->factura,true);
		$criteria->compare('concesionario',$this->concesionario,true);
		$criteria->compare('fecha_venta',$this->fecha_venta,true);
		$criteria->compare('year',$this->year,true);
		$criteria->compare('color_origen',$this->color_origen,true);
		$criteria->compare('identificacion',$this->identificacion,true);
		$criteria->compare('precio_venta',$this->precio_venta,true);
		$criteria->compare('calle_principal',$this->calle_principal,true);
		$criteria->compare('numero_calle',$this->numero_calle,true);
		$criteria->compare('telefono_propietario',$this->telefono_propietario,true);
		$criteria->compare('grupo_concesionario',$this->grupo_concesionario,true);
		$criteria->compare('forma_pago',$this->forma_pago,true);
		$criteria->compare('observacion',$this->observacion,true);
		$criteria->compare('fecha',$this->fecha,true);
		$criteria->compare('id_informacion',$this->id_informacion);
		$criteria->compare('id_vehiculo',$this->id_vehiculo);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}