<script type="text/javascript">
    var abrir=0;
    $(function() {
        $("#keywords").tablesorter();
    });
</script>    
<?php 
$rol = Yii::app()->user->getState('roles');
?>
<div class="container">
    <?php
//    echo '<h3>Nombres</h3>';
//    echo '<pre>';
//    print_r($nombres);
//    echo '</pre>';
//    
//    echo '<h3>Temas</h3>';
//    echo '<pre>';
//    print_r($temas);
//    echo '</pre>';
//    
//    echo '<h3>Subtemas</h3>';
//    echo '<pre>';
//    print_r($subtemas);
//    echo '</pre>';
//    
//    echo '<h3>Provincia</h3>';
//    echo '<pre>';
//    print_r($provincias);
//    echo '</pre>';
//    
//    echo '<h3>Ciudades</h3>';
//    echo '<pre>';
//    print_r($ciudades);
//    echo '</pre>';
//    
//    echo '<h3>Cedula</h3>';
//    echo '<pre>';
//    print_r($cedula);
//    echo '</pre>';
    ?>
    <div class="row">
        <h1 class="tl_seccion">Búsqueda</h1>
        <p>Clave de búsqueda: <b><?php echo $txt; ?></b></p>
    </div>
    <div class="row">
        
        <?php if (!empty($ids)): ?>
            <h3>Ids</h3>
            <div class="table-responsive">
                <table class="table" id="keywords">
                    <thead>
                        <tr>
                            <th><span>ID</span></th>
                            <th><span>Tema</span></th>
                            <th><span>Subtema</span></th>
                            <th><span>Nombres</span></th>
                            <th><span>Apellidos</span></th>
                            <th><span>Cédula</span></th>
                            <th><span>Ciudad</span></th>
                            <th><span>Concesionario</span></th>
                            <th><span>Responsable</span></th>
                            <th><span>Estado</span></th>
                            <th><span>Fecha</span></th>
                            <?php if(Yii::app()->user->getState('roles') === 'admin' || Yii::app()->user->getState('roles') === 'super' ||  Yii::app()->user->getState('roles') === 'adminvpv'  ) : ?>
                                <th><span>Edición</span></th>
                            <?php elseif (Yii::app()->user->getState('roles') === 'asesor'): ?>
                                <th><span>Ver</span></th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <?php
                    //$model = Casos::model()->findAll();
                    foreach ($ids as $c):
                        ?>
                        <tr>
                            <td><?php echo $c['id']; ?> </td>
                            <td><?php echo $this->getTema($c['tema']); ?> </td>
                            <td><?php echo $this->getSubtema($c['subtema']); ?> </td>
                            <td><?php echo str_ireplace($txt, "<span style='color: red'>" . $txt . "</span>", $c["nombres"]); ?> </td>
                            <td><?php echo $c['apellidos']; ?> </td>
                            <td><?php echo $c['cedula']; ?> </td>
                            <td><?php echo $this->getCiudad($c['ciudad']); ?> </td>
                            <td><?php echo $this->getConcesionario($c['concesionario']); ?> </td>
                            <td><?php echo $this->getResponsable($c['responsable']); ?> </td>
                            <td><?php echo $c['estado']; ?> </td>
                            <td><?php echo $c['fecha']; ?> </td>
                            <?php if(Yii::app()->user->getState('roles') === 'admin' || Yii::app()->user->getState('roles') === 'super' ||  Yii::app()->user->getState('roles') === 'adminvpv'  ) : ?>
                                <td><a href="<?php echo Yii::app()->createUrl('casos/update', array('id' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-danger">Editar</a></td>
                            <?php elseif (Yii::app()->user->getState('roles') === 'asesor'): ?>
                                <td><a href="<?php echo Yii::app()->createUrl('casos/view', array('id' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-danger">Ver</a></td>
                            <?php endif; ?>
                        </tr>
                        <?php
                    endforeach;
                    ?>
                </table>

            </div>
        <?php endif; ?>
        <?php if (!empty($nombres)): ?>
            <h3>Nombres</h3>
            <div class="table-responsive">
                <table class="table" id="keywords">
                    <thead>
                        <tr>
                            <th><span>ID</span></th>
                            <th><span>Tema</span></th>
                            <th><span>Subtema</span></th>
                            <th><span>Nombres</span></th>
                            <th><span>Apellidos</span></th>
                            <th><span>Cédula</span></th>
                            <th><span>Ciudad</span></th>
                            <th><span>Concesionario</span></th>
                            <th><span>Responsable</span></th>
                            <th><span>Estado</span></th>
                            <th><span>Fecha</span></th>
                            <?php if(Yii::app()->user->getState('roles') === 'admin' || Yii::app()->user->getState('roles') === 'super' ||  Yii::app()->user->getState('roles') === 'adminvpv'  ) : ?>
                                <th><span>Edición</span></th>
                            <?php elseif (Yii::app()->user->getState('roles') === 'asesor'): ?>
                                <th><span>Ver</span></th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <?php
                    //$model = Casos::model()->findAll();
                    foreach ($nombres as $c):
                        ?>
                        <tr>
                            <td><?php echo $c['id']; ?> </td>
                            <td><?php echo $this->getTema($c['tema']); ?> </td>
                            <td><?php echo $this->getSubtema($c['subtema']); ?> </td>
                            <td><?php echo str_ireplace($txt, "<span style='color: red'>" . $txt . "</span>", $c["nombres"]); ?> </td>
                            <td><?php echo $c['apellidos']; ?> </td>
                            <td><?php echo $c['cedula']; ?> </td>
                            <td><?php echo $this->getCiudad($c['ciudad']); ?> </td>
                            <td><?php echo $this->getConcesionario($c['concesionario']); ?> </td>
                            <td><?php echo $this->getResponsable($c['responsable']); ?> </td>
                            <td><?php echo $c['estado']; ?> </td>
                            <td><?php echo $c['fecha']; ?> </td>
                            <?php if(Yii::app()->user->getState('roles') === 'admin' || Yii::app()->user->getState('roles') === 'super' ||  Yii::app()->user->getState('roles') === 'adminvpv'  ) : ?>
                                <td><a href="<?php echo Yii::app()->createUrl('casos/update', array('id' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-danger">Editar</a></td>
                            <?php elseif (Yii::app()->user->getState('roles') === 'asesor'): ?>
                                <td><a href="<?php echo Yii::app()->createUrl('casos/view', array('id' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-danger">Ver</a></td>
                            <?php endif; ?>
                        </tr>
                        <?php
                    endforeach;
                    ?>
                </table>

            </div>
        <?php endif; ?>

        <?php if (!empty($temas)): ?>
            <h3>Temas</h3>
            <div class="table-responsive">
                <table class="table" id="keywords">
                    <thead>
                        <tr>
                            <th><span>ID</span></th>
                            <th><span>Tema</span></th>
                            <th><span>Subtema</span></th>
                            <th><span>Nombres</span></th>
                            <th><span>Apellidos</span></th>
                            <th><span>Cédula</span></th>
                            <th><span>Ciudad</span></th>
                            <th><span>Concesionario</span></th>
                            <th><span>Responsable</span></th>
                            <th><span>Estado</span></th>
                            <th><span>Fecha</span></th>
                            <?php if(Yii::app()->user->getState('roles') === 'admin' || Yii::app()->user->getState('roles') === 'super' ||  Yii::app()->user->getState('roles') === 'adminvpv'  ) : ?>
                                <th><span>Edición</span></th>
                            <?php elseif (Yii::app()->user->getState('roles') === 'asesor'): ?>
                                <th><span>Ver</span></th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <?php
                    //$model = Casos::model()->findAll();
                    foreach ($temas as $c):
                        ?>
                        <tr>
                            <td><?php echo $c['id']; ?> </td>
                            <td><?php echo str_ireplace($txt, "<span style='color: red'>" . $txt . "</span>", $this->getTema($c['tema'])); ?> </td>
                            <td><?php echo $this->getSubtema($c['subtema']); ?> </td>
                            <td><?php echo $c["nombres"]; ?> </td>
                            <td><?php echo $c['apellidos']; ?> </td>
                            <td><?php echo $c['cedula']; ?> </td>
                            <td><?php echo $this->getCiudad($c['ciudad']); ?> </td>
                            <td><?php echo $this->getConcesionario($c['concesionario']); ?> </td>
                            <td><?php echo $this->getResponsable($c['responsable']); ?> </td>
                            <td><?php echo $c['estado']; ?> </td>
                            <td><?php echo $c['fecha']; ?> </td>
                            <?php if(Yii::app()->user->getState('roles') === 'admin' || Yii::app()->user->getState('roles') === 'super' ||  Yii::app()->user->getState('roles') === 'adminvpv'  ) : ?>
                                <td><a href="<?php echo Yii::app()->createUrl('casos/update', array('id' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-danger">Editar</a></td>
                            <?php elseif (Yii::app()->user->getState('roles') === 'asesor'): ?>
                                <td><a href="<?php echo Yii::app()->createUrl('casos/view', array('id' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-danger">Ver</a></td>
                            <?php endif; ?>
                        </tr>
                        <?php
                    endforeach;
                    ?>
                </table>

            </div>
        <?php endif; ?> 
        <?php if (!empty($subtemas)): ?>
            <h3>Subtemas</h3>
            <div class="table-responsive">
                <table class="table" id="keywords">
                    <thead>
                        <tr>
                            <th><span>ID</span></th>
                            <th><span>Tema</span></th>
                            <th><span>Subtema</span></th>
                            <th><span>Nombres</span></th>
                            <th><span>Apellidos</span></th>
                            <th><span>Cédula</span></th>
                            <th><span>Ciudad</span></th>
                            <th><span>Concesionario</span></th>
                            <th><span>Responsable</span></th>
                            <th><span>Estado</span></th>
                            <th><span>Fecha</span></th>
                            <?php if(Yii::app()->user->getState('roles') === 'admin' || Yii::app()->user->getState('roles') === 'super' ||  Yii::app()->user->getState('roles') === 'adminvpv'  ) : ?>
                                <th><span>Edición</span></th>
                            <?php elseif (Yii::app()->user->getState('roles') === 'asesor'): ?>
                                <th><span>Ver</span></th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <?php
                    //$model = Casos::model()->findAll();
                    foreach ($subtemas as $c):
                        ?>
                        <tr>
                            <td><?php echo $c['id']; ?> </td>
                            <td><?php echo $c['subtema']; ?> </td>
                            <td><?php echo str_ireplace($txt, "<span style='color: red'>" . $txt . "</span>", $this->getSubtema($c['subtema'])); ?> </td>
                            <td><?php echo $c["nombres"]; ?> </td>
                            <td><?php echo $c['apellidos']; ?> </td>
                            <td><?php echo $c['cedula']; ?> </td>
                            <td><?php echo $this->getCiudad($c['ciudad']); ?> </td>
                            <td><?php echo $this->getConcesionario($c['concesionario']); ?> </td>
                            <td><?php echo $this->getResponsable($c['responsable']); ?> </td>
                            <td><?php echo $c['estado']; ?> </td>
                            <td><?php echo $c['fecha']; ?> </td>
                            <?php if(Yii::app()->user->getState('roles') === 'admin' || Yii::app()->user->getState('roles') === 'super' ||  Yii::app()->user->getState('roles') === 'adminvpv'  ) : ?>
                                <td><a href="<?php echo Yii::app()->createUrl('casos/update', array('id' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-danger">Editar</a></td>
                            <?php elseif (Yii::app()->user->getState('roles') === 'asesor'): ?>
                                <td><a href="<?php echo Yii::app()->createUrl('casos/view', array('id' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-danger">Ver</a></td>
                            <?php endif; ?>
                        </tr>
                        <?php
                    endforeach;
                    ?>
                </table>

            </div>
        <?php endif; ?>
        <?php if (!empty($ciudades)): ?>
            <h3>Subtemas</h3>
            <div class="table-responsive">
                <table class="table" id="keywords">
                    <thead>
                        <tr>
                            <th><span>ID</span></th>
                            <th><span>Tema</span></th>
                            <th><span>Subtema</span></th>
                            <th><span>Nombres</span></th>
                            <th><span>Apellidos</span></th>
                            <th><span>Cédula</span></th>
                            <th><span>Ciudad</span></th>
                            <th><span>Concesionario</span></th>
                            <th><span>Responsable</span></th>
                            <th><span>Estado</span></th>
                            <th><span>Fecha</span></th>
                            <?php if(Yii::app()->user->getState('roles') === 'admin' || Yii::app()->user->getState('roles') === 'super' ||  Yii::app()->user->getState('roles') === 'adminvpv'  ) : ?>
                                <th><span>Edición</span></th>
                            <?php elseif (Yii::app()->user->getState('roles') === 'asesor'): ?>
                                <th><span>Ver</span></th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <?php
                    //$model = Casos::model()->findAll();
                    foreach ($ciudades as $c):
                        ?>
                        <tr>
                            <td><?php echo $c['id']; ?> </td>
                            <td><?php echo $c['subtema']; ?> </td>
                            <td><?php echo $c['subtema']; ?> </td>
                            <td><?php echo $c["nombres"]; ?> </td>
                            <td><?php echo $c['apellidos']; ?> </td>
                            <td><?php echo $c['cedula']; ?> </td>
                            <td><?php echo str_ireplace($txt, "<span style='color: red'>" . $txt . "</span>", $this->getCiudad($c['ciudad'])); ?> </td>
                            <td><?php echo $this->getConcesionario($c['concesionario']); ?> </td>
                            <td><?php echo $this->getResponsable($c['responsable']); ?> </td>
                            <td><?php echo $c['estado']; ?> </td>
                            <td><?php echo $c['fecha']; ?> </td>
                            <?php if(Yii::app()->user->getState('roles') === 'admin' || Yii::app()->user->getState('roles') === 'super' ||  Yii::app()->user->getState('roles') === 'adminvpv'  ) : ?>
                                <td><a href="<?php echo Yii::app()->createUrl('casos/update', array('id' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-danger">Editar</a></td>
                            <?php elseif (Yii::app()->user->getState('roles') === 'asesor'): ?>
                                <td><a href="<?php echo Yii::app()->createUrl('casos/view', array('id' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-danger">Ver</a></td>
                            <?php endif; ?>
                        </tr>
                        <?php
                    endforeach;
                    ?>
                </table>

            </div>
        <?php endif; ?>
        <?php if (!empty($cedula)): ?>
            <h3>Subtemas</h3>
            <div class="table-responsive">
                <table class="table" id="keywords">
                    <thead>
                        <tr>
                            <th><span>ID</span></th>
                            <th><span>Tema</span></th>
                            <th><span>Subtema</span></th>
                            <th><span>Nombres</span></th>
                            <th><span>Apellidos</span></th>
                            <th><span>Cédula</span></th>
                            <th><span>Ciudad</span></th>
                            <th><span>Concesionario</span></th>
                            <th><span>Responsable</span></th>
                            <th><span>Estado</span></th>
                            <th><span>Fecha</span></th>
                            <?php if(Yii::app()->user->getState('roles') === 'admin' || Yii::app()->user->getState('roles') === 'super' ||  Yii::app()->user->getState('roles') === 'adminvpv'  ) : ?>
                                <th><span>Edición</span></th>
                            <?php elseif (Yii::app()->user->getState('roles') === 'asesor'): ?>
                                <th><span>Ver</span></th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <?php
                    //$model = Casos::model()->findAll();
                    foreach ($cedula as $c):
                        ?>
                        <tr>
                            <td><?php echo $c['id']; ?> </td>
                            <td><?php echo $c['subtema']; ?> </td>
                            <td><?php echo $c['subtema']; ?> </td>
                            <td><?php echo $c["nombres"]; ?> </td>
                            <td><?php echo $c['apellidos']; ?> </td>
                            <td><?php echo str_ireplace($txt, "<span style='color: red'>" . $txt . "</span>", $c['cedula']); ?> </td>
                            <td><?php echo $c['ciudad']; ?> </td>
                            <td><?php echo $this->getConcesionario($c['concesionario']); ?> </td>
                            <td><?php echo $this->getResponsable($c['responsable']); ?> </td>
                            <td><?php echo $c['estado']; ?> </td>
                            <td><?php echo $c['fecha']; ?> </td>
                            <?php if(Yii::app()->user->getState('roles') === 'admin' || Yii::app()->user->getState('roles') === 'super' ||  Yii::app()->user->getState('roles') === 'adminvpv'  ) : ?>
                                <td><a href="<?php echo Yii::app()->createUrl('casos/update', array('id' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-danger">Editar</a></td>
                            <?php elseif (Yii::app()->user->getState('roles') === 'asesor'): ?>
                                <td><a href="<?php echo Yii::app()->createUrl('casos/view', array('id' => $c['id'])); ?>" class="btn btn-primary btn-xs btn-danger">Ver</a></td>
                            <?php endif; ?>
                        </tr>
                        <?php
                    endforeach;
                    ?>
                </table>
            </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-md-2">
        </div>
        <div class="col-md-10 links-tabs">
            <div class="col-md-3">
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
            </div>
            <div class="col-md-2" style="padding-left: 0px;"><p>También puedes ir a:</p></div>
            <?php if($rol === 'admin' || $rol === 'super'):  ?><div class="col-md-3"><a href="<?php echo Yii::app()->createUrl('casos/create'); ?>" class="creacion-btn">Creación de Casos</a></div><?php endif; ?>
            <?php if($rol === 'admin' || $rol === 'super'):  ?><div class="col-md-3 seg"><a href="<?php echo Yii::app()->createUrl('casos/seguimiento'); ?>" class="seguimiento-btn">Seguimiento de Casos</a></div><?php endif; ?>
            <div class="col-md-1"><a href="javascript:history.back(1)" class="back-btn-go">Atrás</a></div>
        </div>
    </div>
</div>
