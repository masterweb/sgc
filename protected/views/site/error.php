<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>



<div class="error" style="margin-left:5px;">
<h2>Error <?php echo $code; ?></h2>
<?php echo CHtml::encode($message); ?>
<p>
	Realice un clic <b><a href="<?php echo Yii::app()->createUrl('site/login')?>">aqu&iacute;.</a></b> para redireccionar.
</p>
</div>
