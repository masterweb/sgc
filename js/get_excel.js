function inicia(url_footer_var_exel){
	a = [];
	b = [];
	c = [];
	d = [];
	e = [];
	f = [];
	g = [];
	i = 1;
	while (i < 22) {
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
	sendData(data, url_footer_var_exel);
}


function sendData(data, url_footer_var_exel){

	$.post(url_footer_var_exel, { id: "1", next_id: "2" }, function(data) {
        alert("Data Loaded: " + data);
    });

	/*$.ajax({            
        url: url_footer_var_exel,
        beforeSend: function (xhr) {
        },
        type: 'POST',
        data: {
        	data: data
        },
        success: function (data) {
            
        }
    });*/
}