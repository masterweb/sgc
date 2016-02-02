</div>
<script type="text/javascript">
//Iniciamos variables reportes
	url_footer_var_asesores = '<?php echo Yii::app()->createAbsoluteUrl("Reportes/AjaxGetAsesores"); ?>';
	url_footer_var_dealers = '<?php echo Yii::app()->createAbsoluteUrl("Reportes/AjaxGetDealers"); ?>';
	site_route = '<?php echo Yii::app()->request->baseUrl; ?>';
	resposable = '<?php echo $varView["js_responsable"] ;?>';
	dealer = '<?php echo $varView["js_dealer"] ;?>';
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