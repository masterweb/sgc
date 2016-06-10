<?php
/* @var $this GestionComentariosController */
/* @var $model GestionComentarios */

$this->breadcrumbs=array(
	'Gestion Comentarioses'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GestionComentarios', 'url'=>array('index')),
	array('label'=>'Manage GestionComentarios', 'url'=>array('admin')),
);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>