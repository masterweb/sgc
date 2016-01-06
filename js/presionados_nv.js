//Cambia de clase si es que ha sido presionado usa local storage
//inciar cliente_nv antes de llamar a este script 
$(document).ready(function () {
    var ids = [];
    if(localStorage["btn_usados" + cliente_nv]){
        var stored = JSON.parse(localStorage["btn_usados" + cliente_nv]);
        ids = stored;
    }
    $(".btn-cat").each(function(){ pintar($(this), "btn_usados", ids, 1); }); 
    $(".btn-cat").click(function() {
        activar($(this));                                 
        pintar($(this), "btn_usados", ids, 0);                                  
    });

    var ids_p = [];
    if(localStorage["btn_usados_pres" + cliente_nv]){
        var stored_p = JSON.parse(localStorage["btn_usados_pres" + cliente_nv]);
        ids_p = stored_p;
    }
    $(".btn-pres").each(function(){ pintar($(this), "btn_usados_pres", ids_p, 1); }); 
    $(".btn-pres").click(function() {
        activar($(this));                                 
        pintar($(this), "btn_usados_pres", ids_p, 0);                                  
    });

    function activar(e){
        if(e.hasClass( "btn-danger" )){
            e.removeClass( "btn-danger" );
            e.addClass( "btn-success" );
        } 
    }                                    

    function pintar(e, strage_name, ids_f, t){
        itemid = e.attr('usados');
        exist = $.inArray(itemid, ids_f);
        if(exist === -1){
            if(t === 0){                                     
                ids_f.push(itemid);
                localStorage[strage_name + cliente_nv] = JSON.stringify(ids_f);
            }
            if(!e.hasClass( "btn-danger" )){
                e.addClass( "btn-danger" );
            }
        }else{
            if(!e.hasClass( "btn-success" )){
                e.addClass( "btn-success" );
            }                                            
        }
    }                                   
}); 