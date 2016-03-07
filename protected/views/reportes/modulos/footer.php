</div>
<script type="text/javascript">
	//Iniciamos variables reportes.js
	url_footer_var_asesores = '<?php echo Yii::app()->createAbsoluteUrl("Reportes/AjaxGetAsesores"); ?>';
	url_footer_var_dealers = '<?php echo Yii::app()->createAbsoluteUrl("Reportes/AjaxGetDealers"); ?>';
	url_footer_var_modelos = '<?php echo Yii::app()->createAbsoluteUrl("Reportes/AjaxGetModelos"); ?>';
	url_footer_var_asesoresTA = '<?php echo Yii::app()->createAbsoluteUrl("Reportes/AjaxGetConsecionariosTA"); ?>';
	url_footer_var_provincia = '<?php echo Yii::app()->createAbsoluteUrl("Reportes/AjaxGetProvincias"); ?>';
	url_footer_var_grupo = '<?php echo Yii::app()->createAbsoluteUrl("Reportes/AjaxGetGrupo"); ?>';
	site_route = '<?php echo Yii::app()->request->baseUrl; ?>';
	resposable = '<?php echo $varView["js_responsable"] ;?>';
	dealer = '<?php echo $varView["js_dealer"] ;?>';
	cargo_id = '<?= $varView["cargo_id"]?>';
	<?php
		switch ($varView['cargo_id']) {
            case 4: // GERENTE GENERAL
            case 45: // SUBGERENCIA GENERAL
            case 46: // SUPER ADMINISTRADOR
            case 48: // GERENTE MARKETING
            case 57: // INTELIGENCIA DE MERCADO MARKETING
            case 58: // JEFE DE PRODUCTO MARKETING
            case 60: // GERENTE VENTAS
            case 61: // JEFE DE RED VENTAS
            case 62: // SUBGERENTE DE FLOTAS VENTAS
            //case 69: // GERENTE COMERCIAL EN CURSO TERMINADO
				$nombre_print = 'Todos';
				$cargo_print = 'Todos';
				$activar_dealer = 'si';
				break; 
			default:
				$nombre_print = $varView["nombre_usuario"]->nombres." ".$varView["nombre_usuario"]->apellido;
				$cargo_print = $varView["cargo_usuario"]->descripcion;
				$activar_dealer = 'no';
				break;                                 
		}
	?>
	nombre_usuario = '<?= $nombre_print; ?>' + ' | <span class="cargo_rep">' + '<?= $cargo_print; ?>' + '</span>';
	nombre_concecionario = '<?= $varView["consecionario_usuario"]; ?>';
	active_group = '<?= $varView["grupo"] ?>';
	active_prov = '<?= $varView["provincias"] ?>';
	activar_dealer = '<?= $activar_dealer?>';

	id_provincia = '<?= $varView["id_provincia"] ?>';
	id_grupo = '<?= $varView["id_grupo"] ?>';

	TAchecked_gp = '<?= $varView["TAchecked_gp"] ?>';
	TAresp_activo = '<?= $varView["TAresp_activo"] ?>';
</script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/moment.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/daterangepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/reportes.js"></script>