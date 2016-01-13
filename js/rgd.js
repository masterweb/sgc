$( document ).ready(function() {
    $( "#GestionInformacion_nombres[type='text']" ).on('change', function() {
        console.log($(this).val());
        $("#name_cli").html($(this).val());
    });

     $( "#GestionInformacion_apellidos[type='text']" ).on('change', function() {
        console.log($(this).val());
        $("#apellido_cli").html($(this).val());
    });

    $('.'+tipo_ac).addClass('active');
});
