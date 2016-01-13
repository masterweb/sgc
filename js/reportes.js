$(function () {
    $(".checkboxmain").on("change", function(e) {checkboxes(e.target);});

    $('#fecha-range1').daterangepicker({locale: {format: 'YYYY-MM-DD'}});
    $('#fecha-range2').daterangepicker({locale: { format: 'YYYY-MM-DD'}});
    
    $('#GestionInformacionConcesionario').change(function () {
        var value = $(this).attr('value');
        $.ajax({
            url: url_1,
            beforeSend: function (xhr) {
            },
            type: 'POST', 
            data: {dealer_id: value},
            success: function (data) {
                $('#GestionDiariaresponsable').html(data);

            }
        });
    });
});


//Load versiones via ajax
function checkboxes(e){                                              
    if(e.checked === true) {                                                             
        if(e.checked) {
            if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {                                                            
                e.parentNode.parentNode.children[1].style.display = "block";
                e.parentNode.parentNode.children[1].innerHTML = xmlhttp.responseText;                                                                                                                    
            }
        };
        var id = e.value;
        xmlhttp.open("GET","/index.php/ajax/modelos?id="+id);
        xmlhttp.send();
        }
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