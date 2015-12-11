<?php
if(Yii::app()->user->getState('roles')=== 'asesor'){
    $this->redirect(array('site/menu'));
}
?>
<?php
/* @var $this CasosController */
/* @var $model Casos */

$this->breadcrumbs = array(
    'Casoses' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'List Casos', 'url' => array('index')),
    array('label' => 'Manage Casos', 'url' => array('admin')),
);
?>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="info">
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
            <?php else: ?>
            <h1 class="tl_seccion">Creación de Casos</h1>
            <?php echo $this->renderPartial('_form', array('model' => $model)); ?>
            <?php endif; ?>
        </div>
        <div class="col-md-3 col-md-offset-1 cont_der">
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'casos-form-search',
                'method' => 'post',
                'action' => Yii::app()->createUrl('site/busqueda'),
                'htmlOptions' => array('class' => 'form-search-case')
                    ));
            ?>
            <div id="custom-search-input">
                <div class="input-group col-md-12">
                    <input type="text" class="search-query form-control" placeholder="Buscar" name="buscar"/>
                    <span class="input-group-btn">
                        <button class="btn btn-danger btn-search" type="submit">
                            <span class=" glyphicon glyphicon-search"></span>
                        </button>
                    </span>
                </div>
            </div>
            <?php $this->endWidget(); ?>
            <p class="border-bt">También puedes ir a:</p>
            <ul>
                <li><a href="<?php echo Yii::app()->createUrl('casos/seguimiento'); ?>" class="seguimiento-btn">Seguimiento de Casos</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('casos/reportes'); ?>" class="reportes-btn">Reportes</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></li>
                <li><a href="javascript:history.back(1)" class="back-btn-go">Volver</a></li>
            </ul>
        </div>
    </div>
</div>
