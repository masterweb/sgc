function inicia(estado, site_route){
	if(estado == 'activar'){
		$( ".loading" ).remove();
		$("body").append("<div class='loading' style='position: fixed;top: 0px;bottom: 0px;left: 0px;right: 0px;z-index: 999; background: rgba(0,0,0,0.5);text-align: center;'><img src='"+site_route+"/images/loader.gif' style='margin-top:15%;'></div>");
	}else{
		$( ".loading" ).remove();
	}
}

