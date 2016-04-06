function inicia(url_footer_var_exel){
	a = [];
	b = [];
	c = [];
	d = [];
	e = [];
	f = [];
	g = [];
	i = 1;
	while (i < 23) {
	  	a.push($('#mi_a'+i).html());
	  	b.push($('#mi_b'+i).html());
	  	c.push($('#mi_c'+i).html());
	  	d.push($('#mi_d'+i).html());
	  	e.push($('#mi_e'+i).html());
	  	f.push($('#mi_f'+i).html());
	  	g.push($('#mi_g'+i).html());
		i++;
	}
	data = [a, b, c, d, e, f, g];
	return sendData(data, url_footer_var_exel);
}


function sendData(data, url_footer_var_exel){
	container = $('#excel_form');
	container.empty();
	container.append('<input type="submit" name="" id="" value="Exportar a Excel" class="btn btn-warning">');
	for (i=0;i<data.length;i++){
		$.each(data[i], function( key, value ) {
			var input = document.createElement("input");
	        input.type = "hidden";
	        input.name = "excel[columna_" + i+']['+key+']';
	        input.value = value;
	        container.append(input);
		});

		if(i < data.length - 1){     
	    }else{	
			$( "#excel_form" ).attr('action', url_footer_var_exel);
    		$( "#excel_form" ).submit();
		}
    }
}