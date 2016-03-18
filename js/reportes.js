$(function () {
    tipo_b = $(".tipo_busqueda").val();

    //selectro tipo provincia o grupo
    $(".tipo_busqueda").change(function () {
        tipo_b = $(this).val();
        if(cargo_id != '69'){
            vaciar();  
        }
        
        checkFiltro($(this)); 
    });

    $('#GestionDiariaresponsable').change(function () {
        loadmodelos($(this));
        filtros_notification();
    });

    $(".tipo_busqueda").each(function() {
        if ($(this).is(':checked')) {
            checkFiltro($(this));
        }
    });
    $(".tipo_busqueda_por").change(function() {
        checkFiltro($(this));
    });
    $(".tipo_busqueda_por").each(function() {
        if ($(this).is(':checked')) {
            checkFiltro($(this));
        }
    });
    //$(".checkboxmain").on("change", function(e) {checkboxes(e.target);}); //AJAX LOAD VERSIONES EN FILTRO DE MODELOS
    //$('#traficoacumulado').hide();
    $('#cont_TAgrupo').hide();

    $('#fecha-range1').daterangepicker({locale: {format: 'YYYY-MM-DD'}});
    $('#fecha-range2').daterangepicker({locale: { format: 'YYYY-MM-DD'}});

    $('#fecha-range1').change(function () {
        if(activar_dealer == 'si'){ 
            loadmodelos($(this));
            loadgp($('#GestionInformacionGrupo'), url_footer_var_grupo);
            loadgp($('#GestionInformacionProvincias'), url_footer_var_provincia);

            loadgp($('#TA_provincias'), url_footer_var_grupo);
            loadgp($('#TA_grupos'), url_footer_var_provincia);
            vaciar();
        }else{
            loadresponsables($('#GestionInformacionConcesionario'));
        }
    });

    $('#fecha-range2').change(function () {
        if(activar_dealer == 'si'){ 
            loadmodelos($(this));
            loadgp($('#GestionInformacionGrupo'), url_footer_var_grupo);
            loadgp($('#GestionInformacionProvincias'), url_footer_var_provincia);

            loadgp($('#TA_provincias'), url_footer_var_grupo);
            loadgp($('#TA_grupos'), url_footer_var_provincia);
            vaciar();
        }{
           loadresponsables($('#GestionInformacionConcesionario'));
        }
    });
    if(activar_dealer == 'si'){ 
        $('#GestionInformacionGrupo').change(function () {loaddealers($(this), 'g');});
        $('#GestionInformacionProvincias').change(function () {loaddealers($(this), 'p');});
        loadgp($('#GestionInformacionGrupo'), url_footer_var_grupo, 'g');
        loadgp($('#GestionInformacionProvincias'), url_footer_var_provincia, 'p');
    }
    $('#GestionInformacionConcesionario').change(function () {loadresponsables($(this));});    
    loadresponsables($('#GestionInformacionConcesionario'));

    if(activar_dealer == 'si'){ 
        if(id_grupo != ''){  
            loaddealers($('#GestionInformacionGrupo'), 'g');
        }else{    
            loaddealers($('#GestionInformacionProvincias'), 'p');
        }
    }

    function loadmodelos(e){    

        tipo_busqueda_por = $('.tipo_busqueda_por:checked').val();
        concesion_active = $('#GestionInformacionConcesionario option:selected').val();
        resp_active = $('#GestionDiariaresponsable option:selected').val();
        GestionInformacionProvincias = $('#GestionInformacionProvincias option:selected').val();
        GestionInformacionGrupo = $('#GestionInformacionGrupo option:selected').val();
        //alert(tipo_busqueda_por + ' / GestionInformacionGrupo = ' + GestionInformacionGrupo + ' / GestionInformacionProvincias = ' + GestionInformacionProvincias + ' / concesion_active = ' + concesion_active + ' / resp_active = ' + resp_active);     

        if(e.attr('value') != ''){
            var fecha1 = $('#fecha-range1').attr('value');
            var fecha2 = $('#fecha-range2').attr('value');
            $.ajax({
                url: url_footer_var_modelos,
                beforeSend: function (xhr) {
                },
                type: 'POST', 
                data: {
                    fecha1: fecha1, 
                    fecha2: fecha2,
                    tipo_b: tipo_b,
                    tipo_busqueda_por: tipo_busqueda_por,
                    concesion_active: concesion_active,
                    resp_active: resp_active,
                    GestionInformacionProvincias: GestionInformacionProvincias,
                    GestionInformacionGrupo: GestionInformacionGrupo
                },
                success: function (data) {
                    $('.modelos_filtros').html(data);                   
                }
            });
        }        
    }   

    function loadgp(e, url, tipo){
           // tipo_b = $(".tipo_busqueda").val();
        //if(e.attr('value') != ''){
            var fecha1 = $('#fecha-range1').attr('value');
            var fecha2 = $('#fecha-range2').attr('value');
            active = e.attr('value');

            $.ajax({
                url: url,
                beforeSend: function (xhr) {
                },
                type: 'POST', 
                data: {
                    fecha1: fecha1, 
                    fecha2: fecha2,
                    active: active,
                    tipo_b: tipo_b
                },
                success: function (data) {
                    e.html(data);                   
                }
            });
        //}        
    }

    //carga concesionarios por provincia o por grupo
    function loaddealers(e, t){
        vaciar2();
        loadmodelos(e);
        if(e.attr('value') != ''){
            var value = e.attr('value');

            var fecha1 = $('#fecha-range1').attr('value');
            var fecha2 = $('#fecha-range2').attr('value');
            $.ajax({
                url: url_footer_var_dealers,
                beforeSend: function (xhr) {
                },
                type: 'POST', 
                data: {
                    grupo_id: value, 
                    dealer: dealer, 
                    tipo: t, 
                    fecha1: fecha1, 
                    fecha2: fecha2,
                    tipo_b: tipo_b
                },
                success: function (data) {
                    $('#GestionInformacionConcesionario').html(data);
                    filtros_notification();
                    $('#GestionDiariaresponsable').find('option').remove().end().append('<option value="">Responsable</option>').val('');
                    loadresponsables($('#GestionInformacionConcesionario'));                   
                }
            });
        }        
    }
    //carga responsables   
    function loadresponsables(e){
        loadmodelos(e);
        if(e.attr('value') != ''){
            var value = e.attr('value');
            var fecha1 = $('#fecha-range1').attr('value');
            var fecha2 = $('#fecha-range2').attr('value');
            $.ajax({
                url: url_footer_var_asesores,
                beforeSend: function (xhr) {
                },
                type: 'POST', 
                data: {
                    dealer_id: value, 
                    responsable: responsable, 
                    fecha1: fecha1, 
                    fecha2: fecha2,
                    tipo_b: tipo_b
                },
                success: function (data) {
                    $('#GestionDiariaresponsable').html(data);
                    filtros_notification();                    
                }
            });
        }        
    }
    $('#TAconcesionarios').change(function () {
        loadmodelosTA($(this));
        filtros_notification();
    });
    function loadmodelosTA(e){
        var fecha1 = $('#fecha-range1').attr('value');
        var fecha2 = $('#fecha-range2').attr('value');
        var model_info = $('#TAconcesionarios option:selected').attr('value');
        $.ajax({
            url: url_footer_var_modelosTA,
            beforeSend: function (xhr) {
            },
            type: 'POST', 
            data: {
                fecha1: fecha1, 
                fecha2: fecha2,
                model_info: model_info
            },
            success: function (data) {
                $('.filtros_modelos_ta').html(data);                  
            }
        });
    }
    function loadconcesionariosTA(e){
            if(e.attr('value') != ''){
                var where = '';
                var value = e.attr('value');
                var fecha1 = $('#fecha-range1').attr('value');
                var fecha2 = $('#fecha-range2').attr('value');
                if(e.attr('id') == 'TAprovincia'){
                    where = "provincia = '" + value + "' AND ";
                    model_info = ['provincia', value];
                }else{
                    where = "grupo = '"+ value + "' AND ";
                    model_info = ['grupo', value];
                }
                $.ajax({
                    url: url_footer_var_asesoresTA,
                    beforeSend: function (xhr) {
                    },
                    type: 'POST', 
                    data: {
                        where: where, 
                        fecha1: fecha1, 
                        fecha2: fecha2,
                        TAresp_activo: TAresp_activo,
                        model_info: model_info
                    },
                    dataType: 'json',
                    cache: false,
                    success: function (data) {
                        $('#TAconcesionarios').html(data[0]); 
                        $('.filtros_modelos_ta').html(data[1]);                  
                    }
                });
            }        
        }

    if(activar_dealer == 'si'){  
        //TRAFICO ACUMULADO 
        $(".tipo_busqueda_TA").change(function () {
            checkFiltro($(this)); 
            //loadprovincia($('#TA_provincias'));
            //loadgrupo($('#TA_grupos')); 
        });
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
        $('#TAprovincia').change(function () {
            $('#TAgrupo option:selected').prop("selected", false);
            $('#TAconcesionarios option:selected').prop("selected", false);
            loadconcesionariosTA($(this)); 
            filtros_notification();     
        });
        $('#TAgrupo').change(function () {
            $('#TAprovincia option:selected').prop("selected", false);
            $('#TAconcesionarios option:selected').prop("selected", false);
            
            loadconcesionariosTA($(this));
            filtros_notification();  
        });

        if(TAchecked_gp === 'p'){
            loadconcesionariosTA($('#TAprovincia'));
            $('#cont_TAprovincia').show(); 
            $('#cont_TAgrupo').hide(); 
        }else{
            loadconcesionariosTA($('#TAgrupo')); 
            $('#cont_TAprovincia').hide(); 
            $('#cont_TAgrupo').show(); 
        }

        
    }

    checkFiltro($('.tipo_busqueda_por:checked'));   
    function checkFiltro(e){
        if(e.attr('value') == 'grupos'){
            $('.cont_grup').show();
            $('.cont_prov').hide();           
        }else if(e.attr('value') == 'provincias'){
            $('.cont_grup').hide();
            $('.cont_prov').show();           
        }else if(e.attr('value') == 'general'){
            $('#traficoGeneral').show();
            $('#traficoacumulado').hide();
            $('#traficousados').hide();
            $('#traficobdc').hide();
            $('#trafico_todo').show();
            $('#traficoexonerados').hide();
            if(cargo_id != '69'){
                loadgp($('#GestionInformacionGrupo'), url_footer_var_grupo, 'g');
                loadgp($('#GestionInformacionProvincias'), url_footer_var_provincia, 'p');
            }else{
                loaddealers($('#GestionInformacionProvincias'), 'p');
            }   
            loadmodelos($('#fecha-range1'));
            $('.cont_grup').show();
            $('.cont_prov').hide();         
        }if(e.attr('value') == 'usados'){
            $('#traficoGeneral').hide(); 
            $('#traficoacumulado').hide();
            $('#traficousados').show();
            $('#traficobdc').hide();
            $('#traficoexonerados').hide();
            $('#trafico_todo').show(); 
            if(cargo_id != '69'){
                loadgp($('#GestionInformacionGrupo'), url_footer_var_grupo, 'g');
                loadgp($('#GestionInformacionProvincias'), url_footer_var_provincia, 'p');   
            }else{
                loaddealers($('#GestionInformacionProvincias'), 'p');
            }
            $('.cont_grup').show();
            $('.cont_prov').hide();           
        }else if(e.attr('value') == 'bdc'){
            $('#traficoGeneral').hide(); 
            $('#traficoacumulado').hide();
            $('#traficousados').hide();
            $('#traficobdc').show();
            $('#traficoexonerados').hide();
            $('#trafico_todo').show();  
            if(cargo_id != '69'){
                loadgp($('#GestionInformacionGrupo'), url_footer_var_grupo, 'g');
                loadgp($('#GestionInformacionProvincias'), url_footer_var_provincia, 'p'); 
            }else{
                loaddealers($('#GestionInformacionProvincias'), 'p');
            }  
            loadmodelos($('#fecha-range1')); 
            $('.cont_grup').show();
            $('.cont_prov').hide(); 
        }else if(e.attr('value') == 'exonerados'){
            $('#traficoGeneral').hide(); 
            $('#traficoacumulado').hide();
            $('#traficousados').hide();
            $('#traficobdc').hide();
            $('#traficoexonerados').show();
            $('#trafico_todo').show();
            if(cargo_id != '69'){   
                loadgp($('#GestionInformacionGrupo'), url_footer_var_grupo, 'g');
                loadgp($('#GestionInformacionProvincias'), url_footer_var_provincia, 'p');
            }else{
                loaddealers($('#GestionInformacionProvincias'), 'p');
            }  
            loadmodelos($('#fecha-range1')); 
            $('.cont_grup').show();
            $('.cont_prov').hide();
        }else if(e.attr('value') == 'traficoacumulado'){
            $('#traficoGeneral').hide(); 
            $('#traficoacumulado').show();
            $('#traficousados').hide();
            $('#traficobdc').hide();
            $('#traficoexonerados').hide();
            $('#trafico_todo').hide();
            $('#fecha-range1').val('2015-12-01 - 2015-12-31');
            $('#fecha-range2').val('2015-11-01 - 2015-11-30');
            $('.cont_grup').show();
            $('.cont_prov').hide();        
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
        $('#GestionDiariaresponsable').find('option').remove().end().append('<option value="">--Responsable--</option>').val('');
        $('#GestionInformacionConcesionario').find('option').remove().end().append('<option value="">--Concesionario--</option>').val('');
        $("#GestionInformacionGrupo option:selected").prop("selected", false);
        $("#GestionInformacionProvincias option:selected").prop("selected", false);
    }

    function vaciar2(){
        if($(".tipo_busqueda_por:checked").val() == 'provincias'){        
            $('#GestionInformacionGrupo option:selected').prop("selected", false);
        }else{
            $('#GestionInformacionProvincias option:selected').prop("selected", false);
        }
        $('#GestionDiariaresponsable').find('option').remove().end().append('<option value="">--Responsable--</option>').val('');
        $('#GestionInformacionConcesionario').find('option').remove().end().append('<option value="">--Concesionario--</option>').val('');
        $('#TAprovincia').prop("selected", false);
        $('#TAgrupo').prop("selected", false);
    }

    function vaciarTA(){
        $('#TAprovincia option').prop("selected", false);
        $('#TAgrupo option').prop("selected", false);
        $('#TAconcesionarios option').prop("selected", false);
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
        active_selects = '';
        if(tipo_b != 'traficoacumulado'){
            vaciarTA();            
            tipo_form = $('#gestion-nueva-cotizacion-form option:selected');
        }else{
            vaciar();
            tipo_form = $('#gestion-nueva-cotizacion-form option:selected');
        }
        tipo_form.each(function( index ) {
            console.log(index + ": " + $( this ).text());
            char_ini = $( this ).text().substr(0, 2);
            if(char_ini != '--'){
                campo = $( this ).parent().attr('name');
                campo = campo.substr(3, campo.length);
                campo = campo.substring(0,campo.length - 1);

                campo = campo.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                    return letter.toUpperCase();
                });
                active_selects = active_selects +'<b>'+ campo +':</b> ' + $( this ).text()+' <b>/</b> ';
            }
        });
        
        var var_filtros_activos = '<h4>Filtros Activos:</h4> <b>Perfil:</b> ' + nombre_usuario + '<br>'+active_selects;
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