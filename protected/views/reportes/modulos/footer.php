</div>
<script type="text/javascript">
	//Iniciamos variables reportes.js
	url_footer_var_asesores = '<?php echo Yii::app()->createAbsoluteUrl("Reportes/AjaxGetAsesores"); ?>';
	url_footer_var_dealers = '<?php echo Yii::app()->createAbsoluteUrl("Reportes/AjaxGetDealers"); ?>';
	site_route = '<?php echo Yii::app()->request->baseUrl; ?>';
	resposable = '<?php echo $varView["js_responsable"] ;?>';
	dealer = '<?php echo $varView["js_dealer"] ;?>';
	nombre_usuario = '<?= $varView["nombre_usuario"]->nombres." ".$varView["nombre_usuario"]->apellido; ?>' + ' | <span class="cargo_rep">' + '<?= $varView["cargo_usuario"]->descripcion; ?>' + '</span>';
	nombre_concecionario = '<?= $varView["consecionario_usuario"]; ?>';
	active_group = '<?= $varView["grupo"] ?>';
	active_prov = '<?= $varView["provincias"] ?>';
</script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/moment.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/daterangepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/reportes.js"></script>
<script type="text/javascript">
$(function() {
    /*$('#fecha-range1').daterangepicker({
        locale: {
            format: 'DD-MM-YYYY'
        }
    });¨*/
});
</script>