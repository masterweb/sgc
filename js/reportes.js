$(function () {
    
    //$(".checkboxmain").on("change", function(e) {checkboxes(e.target);}); //AJAX LOAD VERSIONES EN FILTRO DE MODELOS
    $('#traficoacumulado').hide();
    $('#cont_TAgrupo').hide();
    $('#fecha-range1').daterangepicker({locale: {format: 'YYYY-MM-DD'}});
    $('#fecha-range2').daterangepicker({locale: { format: 'YYYY-MM-DD'}});
    $('#fecha-range1').change(function () {loadmodelos($(this)); vaciar();});
    $('#fecha-range2').change(function () {loadmodelos($(this)); vaciar();});
    function loadmodelos(e){
        if(e.attr('value') != ''){
            var fecha1 = $('#fecha-range1').attr('value');
            var fecha2 = $('#fecha-range2').attr('value');
            $.ajax({
                url: url_footer_var_modelos,
                beforeSend: function (xhr) {
                },
                type: 'POST', 
                data: {fecha1: fecha1, fecha2: fecha2},
                success: function (data) {
                    $('.modelos_filtros').html(data);                   
                }
            });
        }        
    }

    //carga responsables   
    $('#GestionInformacionConcesionario').change(function () {loadresponsables($(this));});
    function loadresponsables(e){
        if(e.attr('value') != ''){
            var value = e.attr('value');
            var fecha1 = $('#fecha-range1').attr('value');
            var fecha2 = $('#fecha-range2').attr('value');
            $.ajax({
                url: url_footer_var_asesores,
                beforeSend: function (xhr) {
                },
                type: 'POST', 
                data: {dealer_id: value, resposable: resposable, fecha1: fecha1, fecha2: fecha2},
                success: function (data) {
                    $('#GestionDiariaresponsable').html(data);
                    filtros_notification();                    
                }
            });
        }        
    }
    loadresponsables($('#GestionInformacionConcesionario'));

    //selectro tipo provincia o grupo
    $(".tipo_busqueda").change(function () {vaciar();checkFiltro($(this));});

    $(".tipo_busqueda").each(function() {
        if ($(this).is(':checked')) {
            checkFiltro($(this));
        }
    });

    //TRAFICO ACUMULADO 
    $(".tipo_busqueda_TA").change(function () {checkFiltro($(this)); });
    //TODOS TA
    $('.filtros_modelos_ta').on('change', '#todos_ta', function(){
        if(this.checked){
            check = true;
        }else{
            check = false;
        }
        $('.modelos_TA input').each(function () {
            $(this).prop('checked', check);
        });
    });
    //carga responsables   
    $('#TAprovincia').change(function () {loadconcesionariosTA($(this));});
    $('#TAgrupo').change(function () {loadconcesionariosTA($(this));});
    function loadconcesionariosTA(e){
        if(e.attr('value') != ''){
            var where = '';
            var value = e.attr('value');
            var fecha1 = $('#fecha-range1').attr('value');
            var fecha2 = $('#fecha-range2').attr('value');
            if(e.attr('id') == 'TAprovincia'){
                where = "provincia = '" + value + "'";
            }else{
                where = "grupo = '"+ value + "'";
            }
            $.ajax({
                url: url_footer_var_asesoresTA,
                beforeSend: function (xhr) {
                },
                type: 'POST', 
                data: {where: where, fecha1: fecha1, fecha2: fecha2},
                success: function (data) {
                    $('#TAconcesionarios').html(data);                  
                }
            });
        }        
    }
    
    function checkFiltro(e){
        if(e.attr('value') == 'grupos'){
            $('#traficoGeneral').show();
            $('#traficoacumulado').hide();
            $('.cont_grup').show();
            $('.cont_prov').hide();           
        }else if(e.attr('value') == 'provincias'){
            $('#traficoGeneral').show();
            $('#traficoacumulado').hide();
            $('.cont_prov').show();
            $('.cont_grup').hide();            
        }else if(e.attr('value') == 'traficoacumulado'){
            $('#traficoGeneral').hide(); 
            $('#traficoacumulado').show();
            $('#fecha-range1').val('2015-12-01 - 2015-12-31');
            $('#fecha-range2').val('2015-11-01 - 2015-11-30');         
        }else if(e.attr('value') == 'TA_grupos'){
            $('#cont_TAprovincia').hide(); 
            $('#cont_TAgrupo').show();          
        }else if(e.attr('value') == 'TA_provincias'){
            $('#cont_TAprovincia').show(); 
            $('#cont_TAgrupo').hide();           
        }
    }

    //vaciar selects
    function vaciar(){
        $('#GestionDiariaresponsable').find('option').remove().end().append('<option value="">Resposable</option>').val('');
        $('#GestionInformacionConcesionario').find('option').remove().end().append('<option value="">Concesionario</option>').val('');
        $("#GestionInformacionGrupo option:selected").prop("selected", false);
        $("#GestionInformacionProvincias option:selected").prop("selected", false);
    }

    //carga concesionarios por provincia o por grupo
    $('#GestionInformacionGrupo').change(function () {loaddealers($(this), 'g');});
    $('#GestionInformacionProvincias').change(function () {loaddealers($(this), 'p');});
    function loaddealers(e, t){
        if(e.attr('value') != ''){
            var value = e.attr('value');
            $.ajax({
                url: url_footer_var_dealers,
                beforeSend: function (xhr) {
                },
                type: 'POST', 
                data: {grupo_id: value, dealer: dealer, tipo: t},
                success: function (data) {
                    $('#GestionInformacionConcesionario').html(data);
                    filtros_notification();
                    $('#GestionDiariaresponsable').find('option').remove().end().append('<option value="">Resposable</option>').val('');
                    loadresponsables($('#GestionInformacionConcesionario'));                   
                }
            });
        }        
    }
    if(active_group != ''){
        tipoactivo1 = $('#GestionInformacionGrupo');
        tipoactivo2 = 'g';
    }else{
        tipoactivo1 = $('#GestionInformacionProvincias');
        tipoactivo2 = 'p';
    }
    loaddealers(tipoactivo1, tipoactivo2);

    filtros_notification();

    //NOTIFICACION DE FILTROS Y VARIABLES ACTIVAS
    function filtros_notification(){
        var filtros_fecha1 = '<span class="filt_act"><b>Fecha Inicial:</b> ' + $('#fecha-range1').attr('value') + '</span>';
        var filtros_fecha2 = '<span class="filt_act"><b>Fecha Final:</b> ' + $('#fecha-range2').attr('value') + '</span>';
        var filtros_concesionario = '';
        var filtros_asesores = '';
        var filtros_modelos = '';

        if ( $( "#GestionInformacionConcesionario" ).length && $( "#GestionInformacionConcesionario" ).is('select')) {
            var selected_Concesionario = $('#GestionInformacionConcesionario').val();
            if(selected_Concesionario != ''){
                filtros_concesionario = '<span class="filt_act"><b>Concesionario:</b> ' + $('#GestionInformacionConcesionario option:selected' ).text() + '</span>';
            }else{
                filtros_concesionario = '<span class="filt_act">'+ nombre_concecionario +'</span>';
            }
        }
        
        if ( $( "#GestionDiariaresponsable" ).length ) {
            var selected_responsable = $('#GestionDiariaresponsable').val();
            if(selected_responsable != ''){
                filtros_asesores = '<span class="filt_act"><b>Asesor:</b> ' + $('#GestionDiariaresponsable option:selected' ).text() + '</span>';
            }else{
                if(selected_Concesionario == ''){
                    filtros_asesores = '<span class="filt_act"><b>Asesor:</b> ' + nombre_usuario + '</span>';
                }else{
                    filtros_asesores = '<span class="filt_act"><b>Asesor:</b> Todos</span>';
                }               
            }
        }

        var selected = [];
        $('.modelos_filtros input:checked').each(function() {
            selected.push($(this).parent().text());
        });

        if(!jQuery.isEmptyObject(selected)){
            filtros_modelos = '<br><br><span class="filt_act"><b>Modelos:</b> ' + selected + '</span>';
        }
        
        var var_filtros_activos = '<h4>Filtros Activos:</h4> <b>Perfil:</b> ' + nombre_usuario + '<br>' + nombre_concecionario + '<br><br>' + filtros_fecha1 + filtros_fecha2 + filtros_concesionario + filtros_asesores + filtros_modelos;
        $('.resultados_embudo').html(var_filtros_activos);
    }

    //FILTROS SHOW HIDE
    $('.filtrosReportes').hide();
    $('.trigerFiltros').on("click", function() {
        if($(this).hasClass( "abrirFiltros" )){
            $('.filtrosReportes').show();
            $('.filtrosReportes').removeClass( "close_animate" );
            $('.filtrosReportes').addClass( "animate" );
            $('.abrirFiltros').addClass( "cerrarFiltros" );
            $('.cerrarFiltros').removeClass( "abrirFiltros" );
            $(this).html('Cerrar Filtros'); 
        }else{
            $('.filtrosReportes').hide();
            $('.filtrosReportes').removeClass( "animate" );
            $('.filtrosReportes').addClass( "close_animate" );
            $('.cerrarFiltros').addClass( "abrirFiltros" );
            $('.abrirFiltros').removeClass( "cerrarFiltros" );
            $(this).html('Buscar por filtros'); 
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

    //graficas titulo posicion
    $( '.graficas' ).children('.barra').each(function () {
        anchotit = -$(this).children('span').width() - 10;
        $(this).children('span').css('margin-left', anchotit + 'px');
    });

    //FILTRO MODELOS SLECT CHECKBOXES
    //TODOS
    $('.modelos_filtros').on('change', '#todos', function(){
        if(this.checked){
            check = true;
        }else{
            check = false;
        }
        $('.modelo input').each(function () {
            $(this).prop('checked', check);
        });
    });
    $('.modelos_filtros').on('change', '.subcheckbox', function(){
        $(this).parents('.contcheck').find('.checkboxmain').prop('checked', false);
    });
    $('.modelos_filtros').on('change', '.checkboxmain', function(){
        if(this.checked){
            check = true;
        }else{
            check = false;
        }
        $(this).parents('.contcheck').find('.subcheckbox').prop('checked', check);
    });

});

//Load versiones via ajax // ACTUALMENTE NO ESTA EN USO
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