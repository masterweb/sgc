/**** funcion para deshabilitar el boton de guardar cuando este procesando 
 **** le paso 4 parametros que son var2: id del check
 **** var3: id del boton de submit, var4: nombre del formulario
 ****/
function deshabilitarBoton(var2,var3,var4){
    if(document.getElementById(var2).value >1){
        document.getElementById(var3).disabled = true;
        document.forms[var4].submit();
    }
} 

/**** funcion para deshabilitar el boton de guardar cuando este procesando 
 **** le paso 4 parametros que son var1: id del dropdown, var2: id del input requerido 
 **** var3: id del boton de submit, var4: nombre del formulario
 ****/
function deshabilitarBoton2(var1,var2,var3,var4){
    if(document.getElementById(var1).selectedIndex !=0 || document.getElementById(var2).value != ""){
        document.getElementById(var3).disabled = true;
        document.forms[var4].submit();
    }
}    