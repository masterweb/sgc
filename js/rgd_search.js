$(function () {
    // asignar valores a los input hidden para la busqueda del RGD combinada
    // hasta 6 formas de busqueda
    var value_storm; // flag de busqueda
    $('#gestion_diaria_categorizacion').change(function(){
        var value = $(this).attr('value');
        if(value != ''){$('#categorizacion').val(1);}else{$('#categorizacion').val(0);}
    });
    $('#gestion_diaria_status').change(function(){
        var value = $(this).attr('value');
        if(value != ''){$('#status').val(1);}else{$('#status').val(0);}
    });
    $('#GestionDiaria_responsable').change(function(){
        var value = $(this).attr('value');
        if(value != ''){$('#responsable').val(1);}else{$('#responsable').val(0);}
    });
    
});
// busqueda de campos combinados a traves de los flags indicativos, 
// tenemos hasta 16 busquedas principales


