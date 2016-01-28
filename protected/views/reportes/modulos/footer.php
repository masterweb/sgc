</div>
<script type="text/javascript">
//Iniciamos variables reportes
	url_1 = '<?php echo Yii::app()->createAbsoluteUrl("Reportes/AjaxGetAsesores"); ?>';
	site_route = '<?php echo Yii::app()->request->baseUrl; ?>';
	resposable = '<?php echo $varView["js_responsable"] ;?>';
</script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/moment.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/daterangepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/reportes.js"></script>