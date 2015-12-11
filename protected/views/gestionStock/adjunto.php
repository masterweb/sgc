<div class="container">
    <div class="row">
        <div class="col-md-8">
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="info">
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
            <?php else: ?>
                <h1 class="tl_seccion">Adjuntar Archivo de Excel</h1>
                <div class="alert alert-info moverIzq">
                    <strong>ATENCI&Oacute;N!</strong> Solo se permite archivos de <b>EXCEL</b> con extensiones xls o xlsx y el nombre no debe tener espacios en blanco ni signos de puntuaci&oacute;n (.) adicionales.
                </div>
                <div class="col-md-12">
                    <div class="form">
                        <form method="POST" ENCTYPE="multipart/form-data" action="<?php echo Yii::app()->createUrl('gestionStock/adjunto'); ?>" style="padding:0">
                            <div class="form-group row">
                                <label class = 'col-sm-3 control-label' style="text-align:right">Seleccione un archivo:</label>
                                <div class="col-sm-4">
                                    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
                                    <input name="userfile" type="file" class = 'form-control file'/>
                                </div>

                            </div>
                            <div class="row col-md-7">
                                <?php
                                foreach (Yii::app()->user->getFlashes() as $key => $message) {
                                    echo '<div class=" row flash-' . $key . '">' . $message . "</div>\n";
                                }
                                ?>
                            </div>    
                            <div class="row buttons col-sm-8">
                                <input type=submit value="Enviar" class='btn btn-danger'>
                            </div>
                        </form>
                    </div>

                </div>
<?php endif; ?>
        </div>
        
    </div>
</div>
