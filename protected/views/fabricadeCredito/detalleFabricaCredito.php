<style type="text/css">
	body{
		overflow-x: hidden;
	}
	.table{
		font-size: 14px;

	}
	.table th{
		border-right: 1px solid #fff;
		width: 30%;
		background-color: #d9534f;
		color: #FFF;
		text-align: right;
		font-size: 14px;
		border-left: 1px solid #d9534f;
	}
	.table .titulo{
		border-right: 1px solid #fff;
		width: 30%;
		background-color: #d9534f;
		color: #FFF;
		text-align: center !important;
		font-size: 14px;
		border-top: 1px solid #d9534f;
		border-right: 4px solid #d9534f;
	}
	.table td{
		border-right: 1px solid #fff;
		width: 70%;
		background-color: #FFF;
		color: #000;
		border-right: 4px solid #d9534f;
	}
	.table .separador{
		background-color: transparent;
		border:none;
		border-top: 4px solid #d9534f;
	}
</style>
<?php foreach ($datos as $key) {
	$id=$key["id_informacion"];
} ?>
<h2 align="center">Detalle de Gestion #<?php echo $id;?></h2>

<br>
<div class="row">
	<div class="col-md-2">
		&nbsp
	</div>
	<div class="col-md-8">
		<div class="table-responsive" style="margin:0px 10px 0 10px !important">
            <table class="table " id="keywords">
            	<?php foreach ($datos as $key) { ?>
            	<tr>
            		<th colspan="2" class="titulo">DATOS PERSONALES</th>
            	</tr>
            	<tr>
            		<th>Id Cliente</th>
            		<td><?php echo $id=$key["id_informacion"];?></td>
            	</tr>
                <tr>
            		<th>Id Solicitud</th>
            		<td><?php echo $id=$key["id_solicitud"];?></td>
            	</tr>
            	<tr>
            		<th>Nombres</th>
            		<td><?php echo $id=$key["nombres"];?></td>
            	</tr>
            	<tr>
            		<th>Apellidos</th>
            		<td><?php echo $id=$key["apellidos"];?></td>
            	</tr>
            	<tr>
            		<th>Cédula</th>
            		<td><?php echo $id=$key["cedula"];?></td>
            	</tr>
            	<tr>
            		<th>RUC</th>
            		<td><?php echo $id=$key["ruc"];?></td>
            	</tr>
            	<tr>
            		<th>Email</th>
            		<td><?php echo $id=$key["email"];?></td>
            	</tr>
            	<tr>
            		<th>Celular</th>
            		<td><?php echo $id=$key["celular"];?></td>
            	</tr>
            	<tr>
            		<th>Apellido Paterno</th>
            		<td><?php echo $id=$key["apellido_paterno"];?></td>
            	</tr>
            	<tr>
            		<th>Apellido Materno</th>
            		<td><?php echo $id=$key["apellido_materno"];?></td>
            	</tr>
            	<tr>
            		<th>Fecha de Nacimiento</th>
            		<td><?php echo $id=$key["fecha_nacimiento"];?></td>
            	</tr>
            	<tr>
            		<th>Nacionalidad</th>
            		<td><?php echo $id=$key["nacionalidad"];?></td>
            	</tr>
            	<tr>
            		<th>Estado Civil</th>
            		<td><?php echo $id=$key["estado_civil"];?></td>
            	</tr>
            	
            	<tr>
            		<th>Pasaporte</th>
            		<td><?php echo $id=$key["pasaporte"];?></td>
            	</tr>
            	<!-- datos laborales -->
            	<tr>
            		<td colspan="2" class="separador"></td>
            	</tr>
            	<!--datos laborales-->
            	<tr>
            		<th colspan="2" class="titulo">DATOS LABORALES</th>
            	</tr>
            	<tr>
            		<th>Empresa de Trabajo</th>
            		<td><?php echo $id=$key["empresa_trabajo"];?></td>
            	</tr>
            	<tr>
            		<th>Teléfonos del Trabajo</th>
            		<td><?php echo $id=$key["telefono_trabajo"];?></td>
            	</tr>
            	<tr>
            		<th>Tiempo de Trabajo</th>
            		<td><?php echo $id=$key["tiempo_trabajo"];?></td>
            	</tr>
            	<tr>
            		<th>Meses de Trabajo</th>
            		<td><?php echo $id=$key["meses_trabajo"];?></td>
            	</tr>
            	<tr>
            		<th>Cargo</th>
            		<td><?php echo $id=$key["cargo"];?></td>
            	</tr>
            	<tr>
            		<th>Dirección de la Empresa</th>
            		<td><?php echo $id=$key["direccion_empresa"];?></td>
            	</tr>
            	<tr>
            		<th>Tipo de Relación Laboral</th>
            		<td><?php echo $id=$key["tipo_relacion_laboral"];?></td>
            	</tr>
            	<tr>
            		<th>Actividad de la Empresa</th>
            		<td><?php echo $id=$key["actividad_empresa"];?></td>
            	</tr>
            	<tr>
            		<th>Sueldo Mensual</th>
            		<td><?php echo $id=$key["sueldo_mensual"];?></td>
            	</tr>
            	<!-- fin datos laborales -->
            	<tr>
            		<td colspan="2" class="separador"></td>
            	</tr>
            	<!--datos del conyugue -->
            	<tr>
            		<th colspan="2" class="titulo">DATOS DEL CONYUGUE</th>
            	</tr>
            	<tr>
            		<th>Nombres</th>
            		<td><?php echo $id=$key["nombres_conyugue"];?></td>
            	</tr>
            	<tr>
            		<th>Trabaja</th>
            		<td><?php echo ($key["trabaja_conyugue"] == 1) ? 'Si' : 'No';?></td>
            	</tr>
            	<tr>
            		<th>Apellido Paterno</th>
            		<td><?php echo $id=$key["apellido_paterno_conyugue"];?></td>
            	</tr>
            	<tr>
            		<th>Apellido Materno</th>
            		<td><?php echo $id=$key["apellido_materno_conyugue"];?></td>
            	</tr>
            	<tr>
            		<th>Cédula</th>
            		<td><?php echo $id=$key["cedula_conyugue"];?></td>
            	</tr>
            	<tr>
            		<th>Fecha de Nacimiento</th>
            		<td><?php echo $id=$key["fecha_nacimiento_conyugue"];?></td>
            	</tr>
            	<tr>
            		<th>Nacionalidad</th>
            		<td><?php echo $id=$key["nacionalidad_conyugue"];?></td>
            	</tr>
            	<tr>
            		<th>Empresa donde trabaja</th>
            		<td><?php echo $id=$key["empresa_trabajo_conyugue"];?></td>
            	</tr>
            	<tr>
            		<th>Teléfono de Trabajo</th>
            		<td><?php echo $id=$key["telefono_trabajo_conyugue"];?></td>
            	</tr>
            	<tr>
            		<th>Tiempo de trabajo</th>
            		<td><?php echo $id=$key["tiempo_trabajo_conyugue"];?></td>
            	</tr>
            	<tr>
            		<th>Meses de trabajo</th>
            		<td><?php echo $id=$key["meses_trabajo_conyugue"];?></td>
            	</tr>
            	<tr>
            		<th>Cargo</th>
            		<td><?php echo $id=$key["cargo_conyugue"];?></td>
            	</tr>
            	<tr>
            		<th>Dirección de la empresa donde trabaja</th>
            		<td><?php echo $id=$key["direccion_empresa_conyugue"];?></td>
            	</tr>
            	<tr>
            		<th>Tipo de Relación Laboral</th>
            		<td><?php echo $id=$key["tipo_relacion_laboral_conyugue"];?></td>
            	</tr>
            	<tr>
            		<th>Sueldo Mensual</th>
            		<td><?php echo $id=$key["sueldo_mensual_conyugue"];?></td>
            	</tr>
            	<!-- fin datos del conyugue -->
            	<tr>
            		<td colspan="2" class="separador"></td>
            	</tr>
            	<!-- datos de la vivienda -->
            	<tr>
            		<th colspan="2" class="titulo">DATOS DEL DOMICILIO</th>
            	</tr>
                <tr>
            		<th>Número</th>
            		<td><?php echo $id=$key["numero"];?></td>
            	</tr>
            	<tr>
            		<th>Habita</th>
            		<td><?php echo $id=$key["habita"];?></td>
            	</tr>
            	<tr>
            		<th>Avalúo de la Propiedad</th>
            		<td><?php echo $id=$key["avaluo_propiedad"];?></td>
            	</tr>
            	<tr>
            		<th>Valor de Arriendo</th>
            		<td><?php echo $id=$key["valor_arriendo"];?></td>
            	</tr>
            	<tr>
            		<th>Calle</th>
            		<td><?php echo $id=$key["calle"];?></td>
            	</tr>
            	<tr>
            		<th>Barrio</th>
            		<td><?php echo $id=$key["barrio"];?></td>
            	</tr>
            	<tr>
            		<th>Referencia del Domicilio</th>
            		<td><?php echo $id=$key["referencia_domicilio"];?></td>
            	</tr>
            	<tr>
            		<th>Teléfono de Residencia</th>
            		<td><?php echo $id=$key["telefono_residencia"];?></td>
            	</tr>
            	<tr>
            		<th>Provincia de Domicilio</th>
            		<td><?php echo $this->getProvincia($key["provincia_domicilio"]);?></td>
            	</tr>
            	<tr>
            		<th>Ciudad de Domicilio</th>
            		<td><?php echo $this->getCiudadDom($key["ciudad_domicilio"]);?></td>
            	</tr>           	
            	
            	<tr>
            		<th>Dirección Sector I</th>
            		<td><?php echo $id=$key["direccion_sector1"];?></td>
            	</tr>
            	<tr>
            		<th>Dirección Sector II</th>
            		<td><?php echo $id=$key["direccion_sector2"];?></td>
            	</tr>
            	<tr>
            		<th>Dirección Activo I</th>
            		<td><?php echo $id=$key["direccion_activo1"];?></td>
            	</tr>
            	<tr>
            		<th>Dirección Activo II</th>
            		<td><?php echo $id=$key["direccion_activo2"];?></td>
            	</tr>
            	<tr>
            		<th>Dirección Valor Comercial I</th>
            		<td><?php echo $id=$key["direccion_valor_comercial1"];?></td>
            	</tr>
            	<tr>
            		<th>Dirección Valor Comercial II</th>
            		<td><?php echo $id=$key["direccion_valor_comercial2"];?></td>
            	</tr>
            	<!-- fin datos de la vivienda -->
            	<tr>
            		<td colspan="2" class="separador"></td>
            	</tr>
            	<!-- datos bancarios -->
            	<tr>
            		<th colspan="2" class="titulo">DATOS BANCARIOS</th>
            	</tr>
            	<tr>
            		<th>Banco</th>
            		<td><?php echo $this->getNameBanco($key["banco2"]);?></td>
            	</tr>            	
            	
            	<tr>
            		<th>Otro Banco</th>
            		<td><?php echo $this->getNameBanco($key["banco1"]);?></td>
            	</tr>
            	<tr>
            		<th>Cuenta de Ahorros I</th>
            		<td><?php echo $id=$key["cuenta_ahorros1"];?></td>
            	</tr>
            	<tr>
            		<th>Cuenta de Ahorros II</th>
            		<td><?php echo $id=$key["cuenta_ahorros2"];?></td>
            	</tr>
            	<tr>
            		<th>Cuenta Corriente I</th>
            		<td><?php echo $id=$key["cuenta_corriente1"];?></td>
            	</tr>
            	<tr>
            		<th>Cuenta Corriente II</th>
            		<td><?php echo $id=$key["cuenta_corriente2"];?></td>
            	</tr>
            	<!-- fin datos bancarios -->
            	<tr>
            		<td colspan="2" class="separador"></td>
            	</tr>
            	<!-- referencias personales -->
            	<tr>
            		<th class="titulo" colspan="2">REFERENCIAS PERSONALES</th>
            	</tr>
            	<tr>
            		<th>Referencia Personal I</th>
            		<td><?php echo $id=$key["referencia_personal1"];?></td>
            	</tr>
            	<tr>
            		<th>Referencia Personal II</th>
            		<td><?php echo $id=$key["referencia_personal2"];?></td>
            	</tr>
            	<tr>
            		<th>Parentezco de la Referencia I</th>
            		<td><?php echo $id=$key["parentesco1"];?></td>
            	</tr>
            	<tr>
            		<th>Parentezco de la Referencia II</th>
            		<td><?php echo $id=$key["perentesco2"];?></td>
            	</tr>
            	<tr>
            		<th>Teléfono de la Referencia I</th>
            		<td><?php echo $id=$key["telefono_referencia1"];?></td>
            	</tr>
            	<tr>
            		<th>Teléfono de la Referencia II</th>
            		<td><?php echo $id=$key["telefono_referencia2"];?></td>
            	</tr>
            	<!-- fin refrencias -->
            	<tr>
            		<td colspan="2" class="separador"></td>
            	</tr>
            	<!-- DATOS CONTABLES -->
            	<tr>
            		<th colspan="2" class="titulo">DATOS CONTABLES</th>
            	</tr>
            	<tr>
            		<th>Activos</th>
            		<td><?php echo $id=$key["activos"];?></td>
            	</tr>
            	<tr>
            		<th>Pasivos</th>
            		<td><?php echo $id=$key["pasivos"];?></td>
            	</tr>
            	<tr>
            		<th>Patrimonio</th>
            		<td><?php echo $id=$key["Patrimonio"];?></td>
            	</tr>
            	<tr>
            		<th>Otros Ingresos</th>
            		<td><?php echo $id=$key["otros_ingresos"];?></td>
            	</tr>
            	<tr>
            		<th>Marca del Vehículo I</th>
            		<td><?php echo $id=$key["vehiculo_marca1"];?></td>
            	</tr>
            	<tr>
            		<th>Modelo Vehículo I</th>
            		<td><?php echo $id=$key["vehiculo_modelo1"];?></td>
            	</tr>
            	<tr>
            		<th>Año del Vehículo I</th>
            		<td><?php echo $id=$key["vehiculo_year1"];?></td>
            	</tr>
            	<tr>
            		<th>Valor del Vehículo I</th>
            		<td><?php echo $id=$key["vehiculo_valor1"];?></td>
            	</tr>
            	<tr>
            		<th>Marca del Vehículo II</th>
            		<td><?php echo $id=$key["vehiculo_marca2"];?></td>
            	</tr>
            	<tr>
            		<th>Modelo del Vehículo II</th>
            		<td><?php echo $id=$key["vehiculo_modelo2"];?></td>
            	</tr>
            	<tr>
            		<th>Año del Vehículo II</th>
            		<td><?php echo $id=$key["vehiculo_year2"];?></td>
            	</tr>
            	<tr>
            		<th>Valor del Vehículo II</th>
            		<td><?php echo $id=$key["vehiculo_valor2"];?></td>
            	</tr>
            	<tr>
            		<th>Tipo de Inversión</th>
            		<td><?php echo $id=$key["tipo_inversion"];?></td>
            	</tr>
            	<tr>
            		<th>Intituto de Inversión</th>
            		<td><?php echo $id=$key["instituto_inversion"];?></td>
            	</tr>
            	<tr>
            		<th>Valor de Inversión</th>
            		<td><?php echo $id=$key["valor_inversion"];?></td>
            	</tr>
            	<tr>
            		<th>Otros Activos</th>
            		<td><?php echo $id=$key["otros_activos"];?></td>
            	</tr>
            	<tr>
            		<th>Descripción I</th>
            		<td><?php echo $id=$key["descripcion1"];?></td>
            	</tr>
            	<tr>
            		<th>Valor Otros Activos I</th>
            		<td><?php echo $id=$key["valor_otros_activos1"];?></td>
            	</tr>
            	<tr>
            		<th>Otros Activos II</th>
            		<td><?php echo $id=$key["otros_activos2"];?></td>
            	</tr>
            	<tr>
            		<th>Descripción II</th>
            		<td><?php echo $id=$key["descripcion2"];?></td>
            	</tr>
            	<tr>
            		<th>Valor Otros Activos II</th>
            		<td><?php echo $id=$key["valor_otros_activos2"];?></td>
            	</tr>
            	
            	<tr>
            		<th>Total Activos</th>
            		<td><?php echo $id=$key["total_activos"];?></td>
            	</tr>
            	<tr>
            		<th>Tipo Activo I</th>
            		<td><?php echo $id=$key["tipo_activo1"];?></td>
            	</tr>
            	<tr>
            		<th>Tipo Activo II</th>
            		<td><?php echo $id=$key["tipo_activo2"];?></td>
            	</tr>
            	<tr>
            		<th>Tipo Cotización</th>
            		<td><?php echo $id=$key["tipo_cotizacion"];?></td>
            	</tr>
            	<!-- datos contables -->
            	<tr>
            		<td colspan="2" class="separador"></td>
            	</tr>
            	<!-- datos concesionario-->
            	<tr>
            		<th class="titulo" colspan="2">DATOS CONCESIONARIO</th>
            	</tr>
            	<tr>
            		<th>Dealer Id</th>
            		<td><?php echo $key["dealer_id"];?></td>
            	</tr>
            	
            	<tr>
            		<th>Nombre Concesionario</th>
            		<td><?php echo $id=$key["NombreConcesionario"];?></td>
            	</tr>
            	<tr>
            		<th>Vendedor</th>
            		<td><?php echo $this->getResponsableNombres($key["vendedor"]);?></td>
            	</tr>
            	<tr>
            		<th>Fecha</th>
            		<td><?php echo $id=$key["fecha"];?></td>
            	</tr>
            	<tr>
            		<th>Id Vehículo</th>
            		<td><?php echo $id=$key["id_vehiculo"];?></td>
            	</tr>            	
            	<tr>
            		<th>Modelo</th>
            		<td><?php echo $id=$key["modelo"];?></td>
            	</tr>
            	<tr>
            		<th>Año</th>
            		<td><?php echo $id=$key["year"];?></td>
            	</tr>
            	<tr>
            		<th>Valor</th>
            		<td><?php echo $id=$key["valor"];?></td>
            	</tr>
            	<!--fin datos concesionario-->
            	<tr>
            		<td colspan="2" class="separador"></td>
            	</tr>
            	<!-- datos de credito -->
            	<tr>
            		<th colspan="2" class="titulo">DATOS DE CRÉDITO</th>
            	</tr>
            	<tr>
            		<th>Entrada</th>
            		<td><?php echo $id=$key["entrada"];?></td>
            	</tr>
            	<tr>
            		<th>Monto a Financiar</th>
            		<td><?php echo $id=$key["monto_financiar"];?></td>
            	</tr>
            	<tr>
            		<th>Plazo</th>
            		<td><?php echo $id=$key["plazo"].' meses';?></td>
            	</tr>
            	<tr>
            		<th>Tasa</th>
            		<td><?php echo $id=$key["taza"].' %';?></td>
            	</tr>
            	<tr>
            		<th>Cuota Mensual</th>
            		<td><?php echo $id=$key["cuota_mensual"];?></td>
            	</tr>
            	<tr>
            		<th>Estatus</th>
            		<td><?php echo $id=$key["status"];?></td>
            	</tr>
            	<!--fin datos credito -->
            	<tr>
            		<td colspan="2" class="separador"></td>
            	</tr>
	            <?php } ?>
            </table>
            <div class="col-md-12" align="center">
            	<a href="#" onclick="goBack();" class="btn btn-primary btn-xs btn-warning">Regresar</a>
            </div>
            <script>
				function goBack() {
				    window.history.back();
				}
			</script>
        </div>
	</div>
</div>