<?php
/* @var $this GestionComentariosController */
/* @var $model GestionComentarios */

$this->breadcrumbs=array(
	'Gestion Comentarioses'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GestionComentarios', 'url'=>array('index')),
	array('label'=>'Create GestionComentarios', 'url'=>array('create')),
	array('label'=>'View GestionComentarios', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GestionComentarios', 'url'=>array('admin')),
);
?>

<h1>Update GestionComentarios <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>