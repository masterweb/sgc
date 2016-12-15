<?php

/**
 * This is the model class for table "gestion_solicitud_credito".
 *
 * The followings are the available columns in table 'gestion_solicitud_credito':
 * @property string $id
 * @property integer $concesionario
 * @property integer $vendedor
 * @property string $fecha
 * @property string $modelo
 * @property integer $valor
 * @property integer $monto_financiar
 * @property integer $entrada
 * @property integer $year
 * @property integer $plazo
 * @property string $taza
 * @property string $cuota_mensual
 * @property string $apellido_paterno
 * @property string $apellido_materno
 * @property string $nombres
 * @property string $cedula
 * @property string $fecha_nacimiento
 * @property string $nacionalidad
 * @property string $estado_civil
 * @property string $empresa_trabajo
 * @property string $telefonos_trabajo
 * @property integer $tiempo_trabajo
 * @property string $meses_trabajo
 * @property string $cargo
 * @property string $direccion_empresa
 * @property string $tipo_relacion_laboral
 * @property string $email
 * @property string $actividad_empresa
 * @property int $trabaja_conyugue
 * @property string $apellido_paterno_conyugue
 * @property string $apellido_materno_conyugue
 * @property string $nombres_conyugue
 * @property string $cedula_conyugue
 * @property string $fecha_nacimiento_conyugue
 * @property string $nacionalidad_conyugue
 * @property string $empresa_trabajo_conyugue
 * @property string $telefono_trabajo_conyugue
 * @property integer $tiempo_trabajo_conyugue
 * @property string $meses_trabajo_conyugue
 * @property string $cargo_conyugue
 * @property string $direccion_empresa_conyugue
 * @property string $tipo_relacion_laboral_conyugue
 * @property string $domicilio_actual
 * @property string $habita
 * @property string $avaluo_propiedad
 * @property string $vive
 * @property string $valor_arriendo
 * @property string $calle
 * @property string $barrio
 * @property string $referencia_domicilio
 * @property string $telefono_residencia
 * @property string $celular
 * @property string $sueldo_mensual
 * @property string $sueldo_mensual_conyugue
 * @property string $banco1
 * @property string $cuenta_ahorros1
 * @property string $banco2
 * @property string $cuenta_ahorros2
 * @property string $referencia_personal1
 * @property string $referencia_personal2
 * @property string $parentesco1
 * @property string $parentesco2
 * @property string $telefono_referencia1
 * @property string $telefono_referencia2
 * @property integer $activos
 * @property integer $pasivos
 * @property integer $patrimonio
 * @property integer $id_informacion
 * @property integer $id_vehiculo
 * @property integer $status
 * @property integer $provincia_domicilio
 * @property integer $ciudad_domicilio
 * @property string $otros_ingresos
 * @property string $cuenta_corriente1
 * @property string $cuenta_corriente2
 * @property string $direccion_activo1
 * @property string $direccion_sector1
 * @property string $direccion_valor_comercial1
 * @property string $direccion_activo2
 * @property string $direccion_sector2
 * @property string $direccion_valor_comercial2
 * @property string $vehiculo_marca1    
 * @property string $vehiculo_modelo1
 * @property string $vehiculo_year1
 * @property string $vehiculo_valor1
 * @property string $vehiculo_marca2
 * @property string $vehiculo_modelo2
 * @property string $vehiculo_year2
 * @property string $vehiculo_valor2
 * @property string $tipo_inversion
 * @property string $institucion_inversion
 * @property string $valor_inversion
 * @property string $otros_activos
 * @property string $descripcion1
 * @property string $valor_otros_activos1
 * @property string $otros_activos2
 * @property string $descripcion2
 * @property string $valor_otros_activos2
 * @property string $numero
 * @property string $total_activos
 * @property string $tipo_activo1
 * @property string $tipo_activo2
 * @property integer $id_solicitud
 */
class GestionSolicitudCredito extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return GestionSolicitudCredito the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'gestion_solicitud_credito';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('modelo, valor, monto_financiar, entrada, plazo, taza, cuota_mensual,'
                . ' apellido_paterno, empresa_trabajo, telefonos_trabajo, tiempo_trabajo, cargo,'
                . ' direccion_empresa, tipo_relacion_laboral, email, actividad_empresa, year,'
                . ' sueldo_mensual,estado_civil,fecha_nacimiento,'
                . 'calle,telefono_residencia, celular', 'required'),
            array('concesionario, vendedor, valor, monto_financiar, entrada, year, plazo, tiempo_trabajo, tiempo_trabajo_conyugue, activos, pasivos, patrimonio, id_informacion, id_vehiculo, status, provincia_domicilio, ciudad_domicilio, trabaja_conyugue, id_solicitud', 'numerical', 'integerOnly' => true),
            array('taza, sueldo_mensual, sueldo_mensual_conyugue', 'length', 'max' => 20),
            array('cuota_mensual', 'length', 'max' => 25),
            array('apellido_paterno, apellido_materno, nacionalidad, cargo, email, telefono_trabajo_conyugue, cargo_conyugue, vive, valor_arriendo, telefono_residencia, banco1, cuenta_ahorros1, banco2, cuenta_ahorros2, parentesco1, parentesco2, otros_ingresos, cuenta_corriente1, cuenta_corriente2, direccion_valor_comercial1, direccion_valor_comercial2, vehiculo_marca1, vehiculo_modelo1, vehiculo_year1, vehiculo_valor1, vehiculo_marca2, vehiculo_modelo2, vehiculo_year2, vehiculo_valor2, tipo_inversion, valor_otros_activos1, valor_otros_activos2, total_activos, tipo_activo1, tipo_activo2', 'length', 'max' => 50),
            array('nombres, cedula, empresa_trabajo, tipo_relacion_laboral, tipo_relacion_laboral_conyugue, avaluo_propiedad, otros_activos, otros_activos2', 'length', 'max' => 100),
            array('fecha_nacimiento, telefonos_trabajo', 'length', 'max' => 75),
            array('estado_civil, direccion_empresa_conyugue', 'length', 'max' => 120),
            array('direccion_empresa, direccion_activo1, direccion_sector1, direccion_activo2, direccion_sector2', 'length', 'max' => 200),
            array('actividad_empresa, apellido_paterno_conyugue, apellido_materno_conyugue, nombres_conyugue, cedula_conyugue, nacionalidad_conyugue, empresa_trabajo_conyugue, calle, barrio, referencia_domicilio, institucion_inversion, valor_inversion', 'length', 'max' => 80),
            array('fecha_nacimiento_conyugue', 'length', 'max' => 85),
            array('domicilio_actual, habita, referencia_personal1, referencia_personal2', 'length', 'max' => 150),
            array('celular', 'length', 'max' => 10),
            array('telefono_referencia1, telefono_referencia2', 'length', 'max' => 60),
            array('descripcion1, descripcion2', 'length', 'max' => 70),
            array('fecha', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, concesionario, vendedor, fecha, modelo, valor, monto_financiar, entrada, year, plazo, taza, cuota_mensual, apellido_paterno, apellido_materno, nombres, cedula, fecha_nacimiento, nacionalidad, estado_civil, empresa_trabajo, telefonos_trabajo, tiempo_trabajo, cargo, direccion_empresa, tipo_relacion_laboral, email, actividad_empresa, apellido_paterno_conyugue, apellido_materno_conyugue, nombres_conyugue, cedula_conyugue, fecha_nacimiento_conyugue, nacionalidad_conyugue, empresa_trabajo_conyugue, telefono_trabajo_conyugue, tiempo_trabajo_conyugue, cargo_conyugue, direccion_empresa_conyugue, tipo_relacion_laboral_conyugue, domicilio_actual, habita, avaluo_propiedad, vive, valor_arriendo, calle, barrio, referencia_domicilio, telefono_residencia, celular, sueldo_mensual, sueldo_mensual_conyugue, banco1, cuenta_ahorros1, banco2, cuenta_ahorros2, referencia_personal1, referencia_personal2, parentesco1, parentesco2, telefono_referencia1, telefono_referencia2, activos, pasivos, patrimonio, id_informacion, id_vehiculo, status, provincia_domicilio, ciudad_domicilio, otros_ingresos, cuenta_corriente1, cuenta_corriente2, direccion_activo1, direccion_sector1, direccion_valor_comercial1, direccion_activo2, direccion_sector2, direccion_valor_comercial2, vehiculo_marca1, vehiculo_modelo1, vehiculo_year1, vehiculo_valor1, vehiculo_marca2, vehiculo_modelo2, vehiculo_year2, vehiculo_valor2, tipo_inversion, institucion_inversion, valor_inversion, otros_activos, descripcion1, valor_otros_activos1, otros_activos2, descripcion2, valor_otros_activos2', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'concesionario' => 'Concesionario',
            'vendedor' => 'Vendedor',
            'fecha' => 'Fecha',
            'modelo' => 'Modelo',
            'valor' => 'Valor',
            'monto_financiar' => 'Monto Financiar',
            'entrada' => 'Entrada',
            'year' => 'Año',
            'plazo' => 'Plazo',
            'taza' => 'Taza',
            'cuota_mensual' => 'Cuota Mensual',
            'apellido_paterno' => 'Apellido Paterno',
            'apellido_materno' => 'Apellido Materno',
            'nombres' => 'Nombres',
            'cedula' => 'Cédula',
            'fecha_nacimiento' => 'Fecha Nacimiento',
            'nacionalidad' => 'Nacionalidad',
            'estado_civil' => 'Estado Civil',
            'empresa_trabajo' => 'Empresa Trabajo',
            'telefonos_trabajo' => 'Teléfonos Trabajo',
            'tiempo_trabajo' => 'Años de Trabajo',
            'cargo' => 'Cargo',
            'direccion_empresa' => 'Dirección Empresa',
            'tipo_relacion_laboral' => 'Tipo Relación Laboral',
            'email' => 'Email',
            'actividad_empresa' => 'Actividad Empresa',
            'trabaja_conyugue' => 'Conyugue Trabaja',
            'apellido_paterno_conyugue' => 'Apellido Paterno Cónyugue',
            'apellido_materno_conyugue' => 'Apellido Materno Cónyugue',
            'nombres_conyugue' => 'Nombres Cónyugue',
            'cedula_conyugue' => 'Cedula Cónyugue',
            'fecha_nacimiento_conyugue' => 'Fecha Nacimiento Cónyugue',
            'nacionalidad_conyugue' => 'Nacionalidad Cónyugue',
            'empresa_trabajo_conyugue' => 'Empresa Trabajo',
            'telefono_trabajo_conyugue' => 'Teléfono Trabajo',
            'tiempo_trabajo_conyugue' => 'Años de Trabajo',
            'cargo_conyugue' => 'Cargo Cónyugue',
            'direccion_empresa_conyugue' => 'Dirección Empresa Cónyugue',
            'tipo_relacion_laboral_conyugue' => 'Tipo Relación Laboral Cónyugue',
            'domicilio_actual' => 'Domicilio Actual',
            'habita' => 'Tipo de Propiedad',
            'avaluo_propiedad' => 'Avalúo Propiedad',
            'vive' => 'Vive',
            'valor_arriendo' => 'Valor Arriendo',
            'calle' => 'Calle Principal',
            'barrio' => 'Barrio',
            'referencia_domicilio' => 'Referencia Domicilio',
            'telefono_residencia' => 'Teléfono Residencia',
            'celular' => 'Celular',
            'sueldo_mensual' => 'Sueldo Mensual',
            'sueldo_mensual_conyugue' => 'Sueldo Mensual Conyugue',
            'banco1' => 'Banco 1',
            'cuenta_ahorros1' => 'Cta Ahorros',
            'banco2' => 'Banco 2',
            'cuenta_ahorros2' => 'Cta Ahorros ',
            'referencia_personal1' => 'Nombres Completos',
            'referencia_personal2' => 'Nombres Completos',
            'parentesco1' => 'Parentesco',
            'parentesco2' => 'Parentesco',
            'telefono_referencia1' => 'Teléfono',
            'telefono_referencia2' => 'Teléfono',
            'activos' => 'Activos',
            'pasivos' => 'Pasivos',
            'patrimonio' => 'Patrimonio',
            'id_informacion' => 'Id Informacion',
            'id_vehiculo' => 'Id Vehiculo',
            'status' => 'Status',
            'cuenta_corriente1' => 'Cta Corriente ',
            'cuenta_corriente2' => 'Cta Corriente ',
            'direccion_activo1' => 'direccion_activo1',
            'direccion_sector1' => 'direccion_sector1',
            'direccion_valor_comercial1' => 'direccion_valor_comercial1',
            'direccion_valor_comercial1' => 'direccion_valor_comercial1',
            'direccion_sector2' => 'direccion_sector2',
            'direccion_valor_comercial2' => 'direccion_valor_comercial2',
            'vehiculo_marca1' => 'vehiculo_marca1',
            'vehiculo_modelo1' => 'vehiculo_modelo1',
            'vehiculo_year1' => 'vehiculo_year1',
            'vehiculo_valor1' => 'vehiculo_valor1',
            'vehiculo_marca2' => 'vehiculo_marca2',
            'vehiculo_modelo2' => 'vehiculo_modelo2',
            'vehiculo_year2' => 'vehiculo_year2',
            'vehiculo_valor2' => 'vehiculo_valor2',
            'tipo_inversion' => 'tipo_inversion',
            'institucion_inversion' => 'institucion_inversion',
            'valor_inversion' => 'valor_inversion',
            'otros_activos' => 'otros_activos',
            'descripcion1' => 'descripcion1',
            'valor_otros_activos1' => 'valor_otros_activos1',
            'otros_activos2' => 'otros_activos2',
            'descripcion2' => 'descripcion2',
            'valor_otros_activos2' => 'valor_otros_activos2',
            'numero' => 'Número',
            'total_activos' => 'Total Activos',
            'tipo_activo1' => 'tipo_activo1',
            'tipo_activo2' => 'tipo_activo2',
            'id_solicitud' => 'Id Solicitud',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('concesionario', $this->concesionario);
        $criteria->compare('vendedor', $this->vendedor);
        $criteria->compare('fecha', $this->fecha, true);
        $criteria->compare('modelo', $this->modelo);
        $criteria->compare('valor', $this->valor);
        $criteria->compare('monto_financiar', $this->monto_financiar);
        $criteria->compare('entrada', $this->entrada);
        $criteria->compare('year', $this->year);
        $criteria->compare('plazo', $this->plazo);
        $criteria->compare('taza', $this->taza, true);
        $criteria->compare('cuota_mensual', $this->cuota_mensual, true);
        $criteria->compare('apellido_paterno', $this->apellido_paterno, true);
        $criteria->compare('apellido_materno', $this->apellido_materno, true);
        $criteria->compare('nombres', $this->nombres, true);
        $criteria->compare('cedula', $this->cedula, true);
        $criteria->compare('fecha_nacimiento', $this->fecha_nacimiento, true);
        $criteria->compare('nacionalidad', $this->nacionalidad, true);
        $criteria->compare('estado_civil', $this->estado_civil, true);
        $criteria->compare('empresa_trabajo', $this->empresa_trabajo, true);
        $criteria->compare('telefonos_trabajo', $this->telefonos_trabajo, true);
        $criteria->compare('tiempo_trabajo', $this->tiempo_trabajo);
        $criteria->compare('cargo', $this->cargo, true);
        $criteria->compare('direccion_empresa', $this->direccion_empresa, true);
        $criteria->compare('tipo_relacion_laboral', $this->tipo_relacion_laboral, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('actividad_empresa', $this->actividad_empresa, true);
        $criteria->compare('apellido_paterno_conyugue', $this->apellido_paterno_conyugue, true);
        $criteria->compare('apellido_materno_conyugue', $this->apellido_materno_conyugue, true);
        $criteria->compare('nombres_conyugue', $this->nombres_conyugue, true);
        $criteria->compare('cedula_conyugue', $this->cedula_conyugue, true);
        $criteria->compare('fecha_nacimiento_conyugue', $this->fecha_nacimiento_conyugue, true);
        $criteria->compare('nacionalidad_conyugue', $this->nacionalidad_conyugue, true);
        $criteria->compare('empresa_trabajo_conyugue', $this->empresa_trabajo_conyugue, true);
        $criteria->compare('telefono_trabajo_conyugue', $this->telefono_trabajo_conyugue, true);
        $criteria->compare('tiempo_trabajo_conyugue', $this->tiempo_trabajo_conyugue);
        $criteria->compare('cargo_conyugue', $this->cargo_conyugue, true);
        $criteria->compare('direccion_empresa_conyugue', $this->direccion_empresa_conyugue, true);
        $criteria->compare('tipo_relacion_laboral_conyugue', $this->tipo_relacion_laboral_conyugue, true);
        $criteria->compare('domicilio_actual', $this->domicilio_actual, true);
        $criteria->compare('habita', $this->habita, true);
        $criteria->compare('avaluo_propiedad', $this->avaluo_propiedad, true);
        $criteria->compare('vive', $this->vive, true);
        $criteria->compare('valor_arriendo', $this->valor_arriendo, true);
        $criteria->compare('calle', $this->calle, true);
        $criteria->compare('barrio', $this->barrio, true);
        $criteria->compare('referencia_domicilio', $this->referencia_domicilio, true);
        $criteria->compare('telefono_residencia', $this->telefono_residencia, true);
        $criteria->compare('celular', $this->celular, true);
        $criteria->compare('sueldo_mensual', $this->sueldo_mensual, true);
        $criteria->compare('sueldo_mensual_conyugue', $this->sueldo_mensual_conyugue, true);
        $criteria->compare('banco1', $this->banco1, true);
        $criteria->compare('cuenta_ahorros1', $this->cuenta_ahorros1, true);
        $criteria->compare('banco2', $this->banco2, true);
        $criteria->compare('cuenta_ahorros2', $this->cuenta_ahorros2, true);
        $criteria->compare('referencia_personal1', $this->referencia_personal1, true);
        $criteria->compare('referencia_personal2', $this->referencia_personal2, true);
        $criteria->compare('parentesco1', $this->parentesco1, true);
        $criteria->compare('parentesco2', $this->parentesco2, true);
        $criteria->compare('telefono_referencia1', $this->telefono_referencia1, true);
        $criteria->compare('telefono_referencia2', $this->telefono_referencia2, true);
        $criteria->compare('activos', $this->activos);
        $criteria->compare('pasivos', $this->pasivos);
        $criteria->compare('patrimonio', $this->patrimonio);
        $criteria->compare('id_informacion', $this->id_informacion);
        $criteria->compare('id_vehiculo', $this->id_vehiculo);
        $criteria->compare('cuenta_corriente1', $this->cuenta_corriente1, true);
        $criteria->compare('cuenta_corriente2', $this->cuenta_corriente2, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
