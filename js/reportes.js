$(function () {
    $(".checkboxmain").on("change", function(e) {checkboxes(e.target);});
    $('#fecha-range1').daterangepicker({locale: {format: 'YYYY-MM-DD'}});
    $('#fecha-range2').daterangepicker({locale: { format: 'YYYY-MM-DD'}});    
    $('#GestionInformacionConcesionario').change(function () {loadresponsables($(this));});
    function loadresponsables(e){
        if(e.attr('value') != ''){
            var value = e.attr('value');
            $.ajax({
                url: url_1,
                beforeSend: function (xhr) {
                },
                type: 'POST', 
                data: {dealer_id: value, resposable: resposable},
                success: function (data) {
                    $('#GestionDiariaresponsable').html(data);
                    filtros_notification();                    
                }
            });
        }        
    }
    loadresponsables($('#GestionInformacionConcesionario'));
    filtros_notification();

    //NOTIFICACION DE FILTROS Y VARIABLES ACTIVAS
    function filtros_notification(){
        var filtros_fecha1 = '<b>Fecha 1:</b> ' + $('#fecha-range1').attr('value');
        var filtros_fecha2 = ' <b>/</b> <b>Fecha 2:</b> ' + $('#fecha-range2').attr('value');
        var filtros_concesionario = '';
        var filtros_asesores = '';
        var filtros_modelos = '';

        if ( $( "#GestionInformacionConcesionario" ).length && $( "#GestionInformacionConcesionario" ).is('select')) {
            var selected_Concesionario = $('#GestionInformacionConcesionario').val();
            if(selected_Concesionario != ''){
                filtros_concesionario = ' <b>/</b> <b>Concesionario:</b> ' + $('#GestionInformacionConcesionario option:selected' ).text();
            }
        }
        
        if ( $( "#GestionDiariaresponsable" ).length ) {
            var selected_responsable = $('#GestionDiariaresponsable').val();
            if(selected_responsable != ''){
                filtros_asesores = ' <b>/</b> <b>Asesor:</b> ' + $('#GestionDiariaresponsable option:selected' ).text();
            }
        }

        var selected = [];
        $('.modelos_filtros input:checked').each(function() {
            selected.push($(this).parent().text());
        });

        if(!jQuery.isEmptyObject(selected)){
            filtros_modelos = ' <br><b>Modelos:</b> ' + selected;
        }
        
        var var_filtros_activos = '<h4>Filtros Activos:</h4>' + filtros_fecha1 + filtros_fecha2 + filtros_concesionario + filtros_asesores + filtros_modelos;
        $('.resultados_embudo').html(var_filtros_activos);
    }

    //FILTROS SHOW HIDE
    $('.filtrosReportes').hide();
    $('.trigerFiltros').on("click", function() {
        if($(this).hasClass( "abrirFiltros" )){
            $('.filtrosReportes').show();
            $('.abrirFiltros').addClass( "cerrarFiltros" );
            $('.cerrarFiltros').removeClass( "abrirFiltros" );
            $(this).html('Cerrar Filtros'); 
        }else{
            $('.filtrosReportes').hide();
            $('.cerrarFiltros').addClass( "abrirFiltros" );
            $('.abrirFiltros').removeClass( "cerrarFiltros" );
            $(this).html('Abrir Filtros'); 
        }        
    });

    //TABS
    $('#tabs_repo').children('.tab_repo').each(function () {
        if(!$(this).hasClass( "active" )){
            $(this).hide();
        }        
    });

    $('.tabs_triger').children('.tit_repo').each(function () {
        $(this).on("click", function() {
            $('#tabs_repo').children('.tab_repo').each(function () {
                $(this).hide();
            });
            $('.tabs_triger').children('.active').removeClass( "active" );
            $(this).addClass( "active" );
            tabtoshow = $(this).attr('triger');
            $('#'+ tabtoshow ).show();
        });
    });
});

//Load versiones via ajax
function checkboxes(e){                                              
    if(e.checked === true) {
        var id = e.value;
        $.ajax({            
            url: site_route + "/index.php/ajax/modelos?id="+id,
            beforeSend: function (xhr) {
            },
            type: 'POST',
            data: {},
            success: function (data) {
                e.parentNode.parentNode.children[1].style.display = "block";
                e.parentNode.parentNode.children[1].innerHTML = data;
            }
        });
    }else if(e.checked === false) {                            
        e.parentNode.parentNode.children[1].style.display = "none";
        var children = e.parentNode.parentNode.children[1].children[0].childNodes;
        if(children != 0){
            for(child in children){
                children[child].children[0].checked = false;
            }
        }                                                    
    }
}