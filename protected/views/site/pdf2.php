<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$id_responsable = Yii::app()->user->getId();
//die('id responsable: '.$id_responsable);
?>
<style type="text/css">
    .col-xs-6 { width: 45% !important;}
    .col-xs-12 { width: 94% !important;}
    #GestionTestDrive_observaciones_form{height: 15% !important;}
</style>
<div class="row">
    <?php
    foreach ($data as $key => $value):
        $modelo = $this->getNombreModelo($value['id'], $id_vehiculo);
        ?>
        <div class="cont-form-manejo col-md-8" style="">
            <div class="row">
                <div class="col-xs-12">
                    <img src=   "<?php echo Yii::app()->request->baseUrl; ?>/images/header-test.jpg" alt="" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <hr style="color: #c51230;"/>
                </div> 
            </div>
            <div class="row">
                <div class="col-md-6 col-xs-6">
                    <label for="">Nombres y Apellidos</label>
                    <input type="text" class="form-control" name="GestionTestDrive[nombre]" id="GestionTestDrive_nombres" value="<?php echo $value['nombres'] . ' ' . $value['apellidos'] ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <label for="">C.I.</label>
                    <input type="text" class="form-control" name="GestionTestDrive[cedula]" id="GestionTestDrive_cedula" value="<?php echo $value['cedula']; ?>">
                </div>
                <div class="col-xs-6">
                    <label for="">Fecha</label>
                    <input type="text" class="form-control" name="GestionTestDrive[fecha]" id="GestionTestDrive_fecha" value="<?php echo date("d") . "/" . date("m") . "/" . date("Y"); ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <label for="">Dirección</label>
                    <input type="text" class="form-control" name="GestionTestDrive[direccion]" id="GestionTestDrive_cedula" value="<?php echo $value['direccion']; ?>">
                </div>
                <div class="col-xs-6">
                    <label for="">Teléfono Convencional</label>
                    <input type="text" class="form-control" name="GestionTestDrive[telefono_convencional]" id="GestionTestDrive_telefono_convencional" value="<?php echo $value['telefono_casa']; ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <label for="">Teléfono Celular</label>
                    <input type="text" class="form-control" name="GestionTestDrive[celular]" id="GestionTestDrive_celular" value="<?php echo $value['celular']; ?>">
                </div>
                <div class="col-xs-6">
                    <label for="">Email</label>
                    <input type="text" class="form-control" name="GestionTestDrive[email]" id="GestionTestDrive_email" value="<?php echo $value['email']; ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <label for="">Modelo de vehículo que posee actualmente</label>
                    <input type="text" class="form-control" name="GestionTestDrive[modelo_actual]" id="GestionTestDrive_modelo_actual">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-3">
                    <label for="">Marca</label>
                    <input type="text" class="form-control" name="GestionTestDrive[marca]" id="GestionTestDrive_marca">
                </div>
                <div class="col-xs-3">
                    <label for="">Modelo</label>
                    <input type="text" class="form-control" name="GestionTestDrive[modelo]" id="GestionTestDrive_modelo">
                </div>
                <div class="col-xs-3">
                    <label for="">Año</label>
                    <input type="text" class="form-control" name="GestionTestDrive[year]" id="GestionTestDrive_year">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <label for="">Modelo de vehículo Kia que realiza la prueba de manejo</label>
                    <input type="text" class="form-control" name="GestionTestDrive[modelo_kia]" id="GestionTestDrive_modelo_kia" value="<?php echo $modelo; ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <label for="">Nombre Asesor</label>
                    <input type="text" class="form-control" name="GestionTestDrive[nombre_asesor]" id="GestionTestDrive_nombre_asesor" value="<?php echo $nombre_responsable; ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <label for="">Observaciones</label>
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/obs-text.jpg" alt="" />
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <p style="font-size: 12px;">Por medio del presente acepto relizar una prueba de manejo en este establecimiento, declaro que dejo la copia
                        de la licencia de conducción vigente. Me comprometo a conducir el vehículo de prueba de manera responsable y cumpliendo 
                        con las normas de tránsito respectivas.</p>
                </div>
            </div>
            <div class="row"></div>

            <div class="row">
                <div class="col-xs-2"></div>
                <div class="col-xs-4" style="margin-left: 100px;">
                    <?php
                    $cri = new CDbCriteria(array(
                                'condition' => "id_informacion={$_GET['id_informacion']}"
                            ));
                    $firma = GestionFirma::model()->count($cri);
                    if ($firma > 0):
                    $fr = GestionFirma::model()->find($cri);
                    $imgfr = $fr->firma;
                    ?>
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/upload/firma/<?php echo $imgfr; ?>" alt="" width="200" height="100">
                    <?php
                    else:
                    ?>
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/uploads/<?php echo $this->getFirma($id_vehiculo); ?>" alt="" width="200" height="100" />
                    <?php endif; ?>
                </div>
                <div class="col-xs-6">
                    <?php 
                    $cri2 = new CDbCriteria(array(
                                'condition' => "id={$id_responsable}"
                            ));
                    $firmaasesor = Usuarios::model()->find($cri2);
                    $firma = $firmaasesor->firma;
                    ?>
                    <?php if(!empty($firma)): ?>
                        <img src="<?php echo Yii::app()->request->baseUrl; ?>/upload/firma/<?php echo $firma; ?>" alt="" width="200" height="100"/>
                    <?php else: ?>
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/firma3.png" alt="" width="200" height="100"/>
                    <?php endif; ?>
                </div>
            </div>
<?php endforeach; ?>
        <div class="row">
            <div class="col-xs-12">
                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/footer-ext.jpg" alt="" />
            </div>
        </div>
    </div>
</div>
