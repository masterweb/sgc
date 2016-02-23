<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilosCall.css" type="text/css" />
<script src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/jquery.validate.js" type="text/javascript" charset="utf-8"></script>
<script>
    $(function () {
        $("#grabarestado").validate({
		  invalidHandler: function(event, validator) {
			// 'this' refers to the form
			var errors = validator.numberOfInvalids();
			if (errors) {
				alert("Complete todas las preguntas.");
			  
			} else {
			  $(".error").hide();
			}
		  }
		});
    });
</script>
<?php
/* @var $this CasosController */
/* @var $model Casos */

$this->breadcrumbs = array(
    'Casoses' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'List Casos', 'url' => array('index')),
    array('label' => 'Manage Casos', 'url' => array('admin')),
);
?>
<style>
    .lblrespuesta{
        font-size: 12px !important;
        margin: 2px auto;
        font-weight: bold;
    }
    .row.pad-all {
        font-weight: bold;
        font-size: 13px;
        margin-bottom: 10px;
    }
	.tl_seccion {
		width: 99%;
	}
	.highlight {
		background-color: #fff;
		margin: auto 15px 20px;
		border: 10px;
	}
	.membrete h4{
		margin: 0;
		font-weight: 600;
		font-size: 16px;
	}
	.membrete .form .row {
		margin: 0;
	}
    .ddopcion {
    width: 100%;
    margin: 10px auto;
    padding: 0;
    border: 1px dashed #010101;
    padding-left: 30px;
    padding-top: 5px;
}
.error{
	font-size:10px;
	color:red;
}
.timer{
	margin-bottom:5px;
}
</style>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="info">
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
            <?php else: ?>

            <h1 class="tl_seccion">Realizar la encuesta <?php echo $questionario->cquestionario->descripcion?></h1>
		<div class="container timer">
			<INPUT TYPE="input" NAME="boton" id="tiempo" VALUE="00:00:00" class="btn btn-default">
			</div>
					
            <div class="highlight membrete">
                <div class="form">
                    <h4>Datos Informativos:</h4>
					<hr>
                     <div class="row">
                      <div class="col-md-2 text-right"><h5>Nombres:</h5></div>
                      <div class="col-md-3 text-left"><h5><?php echo $questionario->cencuestados->nombre;?></h5></div>
                      <div class="col-md-3 text-right"><h5><b>Tel&eacute;fono:</b></h5></div>
                      <div class="col-md-3 text-left"><h5><?php echo $questionario->cencuestados->telefono;?></h5></div>
                  </div>
                  <div class="row">
                    <div class="col-md-2 text-right"><h5><b>Celular:</b></h4></div>
                      <div class="col-md-3 text-left"><h5><?php echo $questionario->cencuestados->celular;?></h5></div>
                      <div class="col-md-3 text-right"><h5><b>Ciudad:</b></h5></div>
                      <div class="col-md-3 text-left"><h5><?php echo $questionario->cencuestados->ciudad;?></h5></div>        
                    </div>   
                </div>
                <div class="row">
                      <div class="col-md-2 text-right"><h5><b>Correo:</b></h5></div>
                      <div class="col-md-4 text-left"><h5><?php echo $questionario->cencuestados->email;?></h5></div>
                      <div class="col-md-2 text-right"><h5><b>Fecha Nacimiento:</b></h5></div>
                      <div class="col-md-3 text-left"><h5><?php echo $questionario->cencuestados->fechanacimiento;?></h5></div>
                  </div> 
            </div>
			<div class="highlight membrete">
                <?php
					if(!empty($questionario->cquestionario->guion)){
						echo '<p>'.$questionario->cquestionario->guion.'</p>';
					}
				?>
            </div>
            <form id="grabarestado" class="form-horizontal" method="post" action="<?php echo Yii::app()->request->baseUrl; ?>/index.php/cencuestadoscquestionario/encuestarusuario/<?php echo $id?>">
            <input type="hidden" name="tt" id="tt">
                <?php
                    if(!empty($preguntas)){
                        foreach ($preguntas as $value) {
                            
                            if($value->ctipopregunta_id == 1){
                                echo '<div class="row pad-all">';
                                echo $value->descripcion.'</div>';
                                echo '<div class="row"><textarea class="required" onkeypress="return '.$value->tipocontenido.'(event);" id="txtpregunta'.$value->id.'" name="respuesta['.$value->id.']" placeholder="Ingrese la respuesta aquí"></textarea>';
                                echo '</div>';

                            }else if($value->ctipopregunta_id == 2){
                                echo '<div class="row pad-all">';
                                echo $value->descripcion.'</div>';
                                $opciones = Copcionpregunta::model()->findAll(array('condition'=>'cpregunta_id='.$value->id));
                                if(!empty($opciones)){
                                    echo '<div class="row"><div class="highlight">';
                                    $chk = 'checked="true"';
                                    $chk = '';
                                    foreach ($opciones as $op) {
                                        echo '<div class="radio">
                                              <label class="lblrespuesta">
                                                
                                                <input id="ckk'.$op->id.'" class="required" type="checkbox" onclick="verificarSubPregunta(1,this.id,'.$op->id.','.$value->id.')" '.$chk.' name="respuesta['.$value->id.'][]" id="respuestaCheck" value="'.$op->detalle.'|'.$op->id.'">
                                                '.$op->detalle.'
                                              </label>';
                                        echo '<div id="divopcion-'.$op->id.'" class=" cl-'.$value->id.'"></div>';
                                        echo '</div>';
                                            $chk ='';
                                    }
                                    echo '</div></div>';
                                }
                                //
                            }else if($value->ctipopregunta_id == 3){
                                echo '<div class="row pad-all">';
                                echo $value->descripcion.'</div>';
                                $opciones = Copcionpregunta::model()->findAll(array('condition'=>'cpregunta_id='.$value->id));
                                if(!empty($opciones)){
                                    echo '<div class="row"><div class="highlight">';
                                    foreach ($opciones as $op) {
										$vv = (!empty($op->valor))?$op->valor:0;
                                        echo '<div class="radio">
                                              <label class="lblrespuesta">
                                             
                                                <input class="required" type="radio" onclick="oculta('.$value->id.','.$vv.'); verificarSubPregunta(0,this.id,'.$op->id.','.$value->id.')"  name="respuesta['.$value->id.'][respuesta]" id="respuestaOption" value="'.$op->detalle.'|'.$op->id.'">
                                                '.$op->detalle.'
                                                    </label>';
											if(!empty($op->valor)){
													echo '<input class="required form-control" style="margin-top:10px;display:none" type="text" class="form-control" name="respuesta['.$value->id.'][justifica]" id="j'.$value->id.'" placeholder="&iquest;Por qu&eacute;?">';
												}	
                                        echo '<div id="divopcion-'.$op->id.'"  class=" cl-'.$value->id.'"></div>';
                                        echo '</div>';
										
                                    }
                                    echo '</div></div>';
                                }
                                //
                            }else{
                                echo '<div class="row pad-all">';
                                echo $value->descripcion.'</div>';
                                $opciones = Copcionpregunta::model()->findAll(array('condition'=>'cpregunta_id='.$value->id));
                                $matriz = Cmatrizpregunta::model()->findAll(array('condition'=>'cpregunta_id='.$value->id));
                                if(!empty($opciones) && !empty($matriz)){
                                    echo '<div class="row"><div class="highlight">';
                                    echo '<table>';
                                    echo '<tr>';
                                    echo '<td><b>Opci&oacute;n</b></td>';
                                    foreach ($matriz as $key) {
                                        echo '<td>'.$key->detalle.'</td>';
                                    }
                                    echo '</tr>';
                                    foreach ($opciones as $op) {
                                        echo '<tr>';
                                        echo '<td><input type="hidden" name="respuesta['.$op->id.'][idop]" value="'.$op->id.'">'.$op->detalle.'</td>';
                                        foreach ($matriz as $key) {
                                            echo '<td>';
                                            echo '<div class="radio">
                                              <label class="lblrespuesta">
                                              
                                                <input type="radio" class="required" checked="true" name="respuesta['.$value->id.']['.$op->id.']" id="respuestaOptionMatriz" value="'.$op->detalle.' - '.$key->detalle.' ( '.$key->valor.' )|'.$key->id.'">
                                                
                                              </label>
                                            </div>';
                                            echo '</td>';
                                        }
                                        
                                        echo '</tr>';
                                    }
                                    echo '</table>';
                                    echo '</div></div>';
                                }
                            }
                 
                 } 
            }?>
            <input type="hidden" name="inicio" value="<?php echo $inicio ?>">
            <?php if (Yii::app()->user->hasFlash('error')){ ?>
                    <div class="infos">
                        <?php echo Yii::app()->user->getFlash('error'); ?>
                    </div>
                <?php } ?> 
            <input type="submit" value="Enviar" class="btn btn-danger">
        </form>
            <?php endif; ?>
        </div>
        <div class="col-md-3 col-md-offset-1 cont_der">
           
            <p class="border-bt">Tambi&eacute;n puedes ir a:</p>
            <ul>
               <li><a href="<?php echo Yii::app()->createUrl('cencuestadoscquestionario/encuestas/id/'.$idq); ?>" class="seguimiento-btn">Administrador de Encuestas</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></li>
                <li><a href="javascript:history.back(1)" class="back-btn-go">Volver</a></li>
            </ul>
        </div>
    </div>
</div>
<script type="text/javascript">
	function oculta(id,op){
		if($("#j"+id)){
			if(op == 1){
				$("#j"+id).show();
			}else{
				$("#j"+id).hide();
				$("#j"+id).val('');
			}
		}
	}
    function numeros(evt)
    {
        var code = (evt.which) ? evt.which : evt.keyCode;
        if(code==8)
        {
            //backspace
            return true;
        }
        else if(code>=48 && code<=57)
        {
            //is a number
            return true;
        }
        else
        {
            return false;
        }
    }
    function letras(evt)
     {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
           return true;
 
        return false;
     }
     function mixto(evt){
        return true;
     }
     function verificarSubPregunta(tipo,ob,vl,cl){
        $('#divopcion-'+vl).hide();
        $("#dd"+cl).remove();
        $('.cl-'+cl).hide();
		//alert(ob);
		 if(tipo == 1 && $('#'+ob).prop('checked') == false){
		//	alert('false')
			return false;
		 }
		 
        if(vl != '' && vl >0){
            $.ajax({
            url: '<?php echo Yii::app()->createUrl("site/Subpregunta")?>',
            type:'POST',
            async:true,
            data:{
                rs : vl,
                cl : cl,
            },
            success:function(result){
                if(result != 0){
                   $('#divopcion-'+vl).show();
                   $('#divopcion-'+vl).html(result);
                }
            }
        });
        }
     }
	 /*
	 $("#grabarestado").submit(function( event ) {
		$(".required").each(function() {
			alert($(this).val());
		});
		return false;
	});*/
	
	
	horas=0; minutos=0; segundos=0;
puntos=true;
function Tiempo() {
if(puntos) ++segundos;
if(segundos==60) { segundos=0; ++minutos }
if(minutos==60) { minutos=0; ++horas }
if(horas==24) horas=0;
cad="";
if(horas<10) cad+="0";
cad+=horas;
if(puntos) cad+=":"; else cad+=" ";
if(minutos<10) cad+="0";
cad=cad+minutos;
if(puntos) cad+=":"; else cad+=" ";
if(segundos<10) cad+="0";
cad=cad+segundos;
puntos=!puntos;
$("#tiempo").val(cad);
$("#tt").val(cad);
}
var id;
function sigue() { id=setInterval("Tiempo()",500) }
function para() { clearInterval(id) }
$(function(){sigue()});
</script>