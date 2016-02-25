<script type="text/javascript">

  function UploadPic() {

        // generate the image data
        var data = document.getElementById("colors_sketch2").toDataURL("image/png");
        var output = data.replace(/^data:image\/(png|jpg);base64,/, "");
        // Sending the image data to Server
        if (confirm("Antes de continuar, esta seguro que ha realizado su firma correctamente?")) {
            $.ajax({
                type: 'POST',
                url: '<?php echo Yii::app()->createUrl("/site/grabarFirma") ?>',
                data: {imageData: output, id_informacion: "<?php echo $id_informacion; ?>", tipo: 1},
                success: function (msg) {
                    if (msg == 1) {
                        alert('Firma cargada exitosamente.');
                        //var ur = 'https://www.kia.com.ec/intranet/ventas/index.php/gestionTestDrive/create/'+<?php echo $_GET['id'] ?>+'?id_informacion='+<?php echo $id_informacion; ?>;
                        //$(location).attr('href', ur)
                        $('#cont-firma').hide();
                        $.ajax({
                            type: 'POST',
                            dataType: "json",
                            url: '<?php echo Yii::app()->createUrl("/site/getFirma") ?>',
                            data: {id_informacion: "<?php echo $id_informacion; ?>"},
                            success: function (data) {
                                //console.log('firma digital: '+data.firma);
                                $('#img-firma').attr('src', '/intranet/usuario/upload/firma/' + data.firma);
                                $('#cont-firma').hide();
                                $('#cont-firma-img').show();
                                //$('#cont-btn').show();
                            }
                        });

                        $.fancybox.close();
                    }
                }
            });

        }
    }                                     


</script>
 <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/canvas/signature-pad.css" rel="stylesheet">
 <div id="signature-pad" class="m-signature-pad">
    <div class="m-signature-pad--body">
      <canvas id="colors_sketch2"></canvas>
    </div>
    <div class="m-signature-pad--footer">
      <div class="description">Firma arriba</div>
      <button type="button" class="button clear" data-action="clear">Borrar</button>
      <button type="button" class="button save" data-action="save" style="display:none;">Guardar</button>
      <button type="button" class="button save" onclick="UploadPic()" >Guardar</button>

      <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/canvas/signature_pad.js"></script>
      <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/canvas/app.js"></script>

      <!--ORIGINLAES-->
      <!--button type="button" class="button save" data-action="save" >Guardar</button>
      <input type="button"  onclick="UploadPic()" class=" btn btn-info" value="Subir Firma"-->
    </div>
  </div>     

