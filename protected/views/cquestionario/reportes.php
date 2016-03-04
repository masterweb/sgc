<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/nuevosEstilos.css" type="text/css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/third-party/jQuery-UI-Date-Range-Picker/css/ui.daterangepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/jquery-ui-bootstrap/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootbox.min.js"></script>

<?php
	$accesosUser = Permiso::model()->findAll(array( 'condition' => "cargoId=:match", 'params' => array(':match' =>(int)Yii::app()->user->getState('cargo_id'))));

$case = ''; // para busqueda por defecto

$rol = Yii::app()->user->getState('roles');
?>
<div class="container">
	<div class="row">
        <h1 class="tl_seccion">Reportes Call Center</h1>
    </div>

	<div class="row">
	    <!-- FORMULARIO DE BUSQUEDA -->
	    <div class="col-md-12">
	        <div class="highlight">
                <div class="form row">
                	<?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'codigo-naturaleza-form',
                        'method' => 'get',
                        'action' => Yii::app()->createUrl('cquestionario/reportesv/'),
                        'enableAjaxValidation' => true,
                        'htmlOptions' => array('class' => 'form-horizontal form-search')
                            ));
                    ?>  
                    <h4>Filtrar por:</h4>
                    
                    <label class="col-sm-2 control-label" for="Casos_estado">Campañas</label>
                    <div class="col-md-4">
						<?php 
							$campanas = Ccampana::model()->findAll();
							if(!empty($campanas)){
								echo '<select name="cbocampana" id="cbocampana" class="form-control" onchange="obternerencuesta(this.value)">';
								echo '<option value="0"> -- Seleccione la campa&ntilde;a --</option>';
								foreach($campanas as $c){
									echo '<option value="'.$c->id.'">'.$c->nombre.' ['.$c->descripcion.']</option>';
								}
								echo '</select>';
							}
						?>
					</div>
                    
                    <label class="col-sm-2 control-label" for="Casos_estado">Encuestas</label>
                    <div class="col-md-4" id="d_encuestas">
                       
					</div>

                </div>
					<div class="row">
					<div class="col-md-12">
                        <input class="btn btn-danger" type="submit" name="yt0" id="btn" value="Buscar">
                    </div>
                    </div>
                
				
				<?php	foreach(Yii::app()->user->getFlashes() as $key => $message) {
					echo '<div class=" row flash-' . $key . '">' . $message . "</div>\n";
				}?>
                
                <?php $this->endWidget(); ?>
            </div>
        </div>
		
    </div>
		
</div>
<div class="container">
	<div class="row">
		<?php 
		$ttEncuestados=0;
		$ttNEncuestados = 0;
		$ttPendientes = 0;
		$tt = null;
		$tts = null;
		$idc =0;
		if(!empty($campana)){
			foreach ($campana as $key) {
				echo '<h1 class="text-center"><b>'.$key->descripcion.'</b></h1><hr>';
				if(!empty($model)){
					$tabla=0;
					foreach ($model as $value) {
						if($key->id == $value->ccampana_id){
							$idc = $value->id;
							echo '<div class="col-md-12">';
							echo '<h4>Encuesta: <b>'.strtoupper(strtolower($value->descripcion)).'</b></h4>';
							echo '<h5>Expira: <b>'.$value->fechafin.'</b></h5>';
							
							echo '<table><thead><th>Detalle</th><th>Valor</th></thead><tbody>';
							$totalEncuestados = Cencuestados::model()->count(array('condition'=>'cquestionario_id = '.$value->id));
							echo '<tr><td>Total de Encuestados</td><td>'.$totalEncuestados.'</td></tr>';
							//ENCUESTAS REALIZADAS
							$totalRealizadas = Cencuestadoscquestionario::model()->count(array('condition'=>'estado = "COMPLETADO" && cquestionario_id = '.$value->id));
							echo '<tr><td>Encuestas Completadas</td><td>'.$totalRealizadas.'</td></tr>';
							//ENCUESTADOS CANCELADOS
							$totalNoRealizadas = Cencuestadoscquestionario::model()->count(array('condition'=>'estado = "CANCELADA" && cquestionario_id = '.$value->id));
							echo '<tr><td>Encuestas no Deseadas/Canceladas</td><td>'.$totalNoRealizadas.'</td></tr>';
							echo '<tr><td>Encuestas Pendientes</td><td>'.Cencuestadoscquestionario::model()->count(array('condition'=>'estado = "PENDIENTE" && cquestionario_id = '.$value->id)).'</td></tr>';
							
							echo '</tbody></table></div>';
							$tt .=strtoupper(strtolower($value->descripcion)).'|'.$totalEncuestados.'|'.$totalRealizadas.'|'.$totalNoRealizadas.'--';
							$tts .='{ label: "'.strtoupper(strtolower($value->descripcion)).'", y: '.$totalEncuestados.' },';

						}
					}
					
				}
			}
		} 
		//print_r($tts);
		?>
	</div>
	<?php 
	if(!empty($campana)){?>
		<div class="row">
			<div class="col-md-12">
				<div style="width: 100%">
					<div id="chartContainer" style="height: 300px; width: 100%;">
						<?php 
							echo '<h1>No existen datos para graficar, seleccione una encuesta.</h1>';
						?>				
					</div>
						<?php 
							echo '<div class="row" style="margin-top:15px"><div class="col-md-12"><a href="'.Yii::app()->createUrl('cquestionario/detallereporte/'.$value->id).'"><input type="button" value="Ver Detalles" class="btn btn-primary"></a></div></div>';
						?>
				</div>
			</div>
		</div>
	<?php
	}
	?>
	<hr>
	<?php 
	if(!empty($model)){
		?>
	<div class="row">
		<div class="col-md-12">
			<h3><b>Resultado de la Encuesta</b></h3>
			<?php
				
				//$preguntas = Cpregunta::model()->findAll(array('condition'=>'cquestionario_id = '.(int)$idc,'order'=>'ctipopregunta_id ASC, id ASC'));
				$preguntas = Cpregunta::model()->findAll(array('condition'=>'copcionpregunta_id IS NULL AND cquestionario_id = '.(int)$idc));
				if(!empty($preguntas)){
					echo '<table class="table">';
					echo '<thead>';
					echo '<th>#</th>';
					echo '<th>Tipo Pregunta</th>';
					echo '<th>Pregunta</th>';
					echo '<th>Total de Respuestas</th>';
					echo '<tbody>';
					$cont = 1;
					foreach ($preguntas	 as $keyp) {
						echo '<tr style="background:#ccc;margin:5px auto;">';
						echo '<td>'.$cont.'</td>';
						echo '<td>'.$keyp->ctipopregunta->descripcion.'</td>';
						echo '<td>'.$keyp->descripcion.'</td>';
						if($keyp->ctipopregunta_id >1){
								//echo '<td><span class="btn btn-link" onclick="abrirMatriz('.$keyp->id.',\''.$keyp->descripcion.'\');">Ver</span></td>';
							if($keyp->ctipopregunta_id ==4)
								echo '<td><span class="btn btn-link">&nbsp;</span></td>';
							else
								echo '<td><span class="btn btn-link" onclick="abrirChart('.$keyp->id.',\''.$keyp->descripcion.'\');">Ver</span></td>';

							$opciones= Copcionpregunta::model()->findAll(array('condition'=>'cpregunta_id = '.(int)$keyp->id));
							if(!empty($opciones)){
								
								$conto=1;
								echo '<tr colspan="4"><td colspan="4">';
								echo '<ul>';
								foreach ($opciones as $keyo) {
									$numerorespuestas =0;

									$numerorespuestas = Cencuestadospreguntas::model()->count(array('condition'=>'pregunta_id ='.(int)$keyp->id.' and copcionpregunta_id ='.$keyo->id));
									/*PARA MATRIZ*/

									if($keyp->ctipopregunta_id ==4){
										$table='';
										//echo '<li style="padding:10px;width:100%;border:1px solid #ccc;margin:2px auto;">'.$cont.'.'.$conto.') &nbsp;<label style="width:250px;height:15px;font-weight:bold;font-size:13px;">'.$keyo->detalle.'</label><span style="margin:width:200px;auto auto auto 100px; padding:2px;">Respuestas: '.$numerorespuestas.'</span></li>';
										$table .='<div style="width:100%;padding:5px;margin:5px auto;border:1px solid #ccc;"><b>Opci&oacute;n:</b> '.$keyo->detalle.' | Respuestas: '.$numerorespuestas.'</div>';
										$matriz= Cmatrizpregunta::model()->findAll(array('condition'=>'cpregunta_id = '.(int)$keyp->id));
										if(!empty($matriz)){
											$table .='<table class="table"><tr>';
											foreach ($matriz as $keym) {
												$table .='<th style="background:#CCC;text-align:center"><span >'.$keym->detalle.' [ '.$keym->valor.' ]</span></th>';
												//$table.='<li style="width:100%;padding:5px; margin:5px auto;"></li>';
											}
											$table .= '</tr>';
											//COMPROBAR SI HUBO RESPUESTA
											$table .= '<tr>';
											foreach ($matriz as $keym) {
												$RESP = 0;
												$RESP= Cencuestadospreguntas::model()->count(array('condition'=>'cmatrizpregunta_id = '.(int)$keym->id.' and copcionpregunta_id='.$keyo->id));
												$table .= '<td style="font-size:13px;font-weight:100;text-align:center;">'.$RESP.'</td>';
											}
											$table .='</tr>';
											$table .='</table>';

										}
										echo $table;
									}else{
										echo '<li style="padding:10px;width:100%;border:1px solid #ccc;margin:2px auto;">'.$cont.'.'.$conto.') &nbsp;<label style="width:250px;height:15px;font-weight:bold;font-size:13px;">'.$keyo->detalle.'</label><span style="margin:auto auto auto 100px; padding:2px;width:200px;">Respuestas: '.$numerorespuestas.'</span></li>';
									}
									/*FIN DE MATRIZ*/
									$preguntass = Cpregunta::model()->findAll(array('condition'=>'copcionpregunta_id ='.$keyo->id.' AND cquestionario_id = '.(int)$idc));
									if(!empty($preguntass)){
										echo '<table class="table">';
										echo '<thead>';
										echo '<th>#</th>';
										echo '<th>Tipo Pregunta</th>';
										echo '<th>Pregunta</th>';
										echo '<th>Total de Respuestas</th>';
										echo '<tbody>';
										$conts = 1;
										foreach ($preguntass	 as $keyps) {
																	echo '<tr style="background:#CCCCCC">';
											echo '<td>'.$conts.'</td>';
											echo '<td>'.$keyps->ctipopregunta->descripcion.'</td>';
											echo '<td>'.$keyps->descripcion.'</td>';
											if($keyps->ctipopregunta_id >1){
												if($keyps->ctipopregunta_id ==4)
													echo '<td><span class="btn btn-link">&nbsp;</span></td>';
													//echo '<td><span class="btn btn-link" onclick="abrirMatriz('.$keyps->id.',\''.$keyps->descripcion.'\');">Ver</span></td>';
												else
													echo '<td><span class="btn btn-link" onclick="abrirChart('.$keyps->id.',\''.$keyps->descripcion.'\');">Ver</span></td>';
												$opcioness= Copcionpregunta::model()->findAll(array('condition'=>'cpregunta_id = '.(int)$keyps->id));
												if(!empty($opcioness)){
													
													$conto=1;
													echo '<tr colspan="4" ><td colspan="4">';
													echo '<ul>';
													foreach ($opcioness as $keyos) {
														
														$numerorespuestass=0;

														$numerorespuestass = Cencuestadospreguntas::model()->count(array('condition'=>'pregunta_id ='.(int)$keyps->id.' and copcionpregunta_id ='.$keyos->id));
														
														if($keyps->ctipopregunta_id ==4){
															$table='';
															//echo '<li style="padding:10px;width:100%;border:1px solid #ccc;margin:2px auto;">'.$cont.'.'.$conto.') &nbsp;<label style="width:250px;height:15px;font-weight:bold;font-size:13px;">'.$keyo->detalle.'</label><span style="margin:width:200px;auto auto auto 100px; padding:2px;">Respuestas: '.$numerorespuestas.'</span></li>';
															$table .='<div style="width:100%;padding:5px;margin:5px auto;border:1px solid #ccc;"><b>Opci&oacute;n:</b> '.$keyos->detalle.' | Respuestas: '.$numerorespuestass.'</div>';
															$matriz= Cmatrizpregunta::model()->findAll(array('condition'=>'cpregunta_id = '.(int)$keyps->id));
															if(!empty($matriz)){
																$table .='<table class="table"><tr>';
																foreach ($matriz as $keyms) {
																	$table .='<th style="background:#CCC;text-align:center"><span >'.$keyms->detalle.' [ '.$keyms->valor.' ]</span></th>';
																	//$table.='<li style="width:100%;padding:5px; margin:5px auto;"></li>';
																}
																$table .= '</tr>';
																//COMPROBAR SI HUBO RESPUESTA
																$table .= '<tr>';
																foreach ($matriz as $keyms) {
																	$RESP = 0;
																	$RESPs= Cencuestadospreguntas::model()->count(array('condition'=>'cmatrizpregunta_id = '.(int)$keyms->id.' and copcionpregunta_id='.$keyos->id));
																	$table .= '<td style="font-size:13px;font-weight:100;text-align:center;">'.$RESPs.'</td>';
																}
																$table .='</tr>';
																$table .='</table>';

															}
															echo $table;
														}else{
															echo '<li style="padding:10px;width:100%;border:1px solid #ccc;margin:2px auto;">&nbsp;<label style="width:250px;height:15px;font-weight:bold;font-size:13px;">'.$keyos->detalle.'</label><span style="margin:auto auto auto 100px; padding:2px;">Respuestas: '.$numerorespuestass.'</span></li>';
														}
														//-----------------------------------
															$preguntasss = Cpregunta::model()->findAll(array('condition'=>'copcionpregunta_id ='.$keyos->id.' AND cquestionario_id = '.(int)$idc));
															if(!empty($preguntasss)){
																echo '<table class="table">';
																echo '<thead>';
																echo '<th>#</th>';
																echo '<th>Tipo Pregunta</th>';
																echo '<th>Pregunta</th>';
																echo '<th>Total de Respuestas</th>';
																echo '<tbody>';
																$contss = 1;
																foreach ($preguntasss	 as $keypss) {
																							echo '<tr>';
																	echo '<td>'.$contss.'</td>';
																	echo '<td>'.$keypss->ctipopregunta->descripcion.'</td>';
																	echo '<td>'.$keypss->descripcion.'</td>';
																	if($keypss->ctipopregunta_id >1){
																		if($keypss->ctipopregunta_id ==4)
																			echo '<td><span class="btn btn-link">&nbsp;</span></td>';
																			//echo '<td><span class="btn btn-link" onclick="abrirMatriz('.$keypss->id.',\''.$keypss->descripcion.'\');">Ver</span></td>';
																		else	
																			echo '<td><span class="btn btn-link" onclick="abrirChart('.$keypss->id.',\''.$keypss->descripcion.'\');">Ver</span></td>';
																		
																		$opcionesss= Copcionpregunta::model()->findAll(array('condition'=>'cpregunta_id = '.(int)$keypss->id));
																		if(!empty($opcionesss)){
																			
																			$conto=1;
																			echo '<tr colspan="4" ><td colspan="4">';
																			echo '<ul>';
																			foreach ($opcionesss as $keyoss) {
																				$numerorespuestasss=0;
																				$numerorespuestasss = Cencuestadospreguntas::model()->count(array('condition'=>'pregunta_id ='.(int)$keypss->id.' and copcionpregunta_id ='.$keyoss->id));
																				

																				if($keypss->ctipopregunta_id ==4){
																					$tabless='';
																					//echo '<li style="padding:10px;width:100%;border:1px solid #ccc;margin:2px auto;">'.$cont.'.'.$conto.') &nbsp;<label style="width:250px;height:15px;font-weight:bold;font-size:13px;">'.$keyo->detalle.'</label><span style="margin:width:200px;auto auto auto 100px; padding:2px;">Respuestas: '.$numerorespuestas.'</span></li>';
																					$tabless .='<div style="width:100%;padding:5px;margin:5px auto;border:1px solid #ccc;"><b>Opci&oacute;n:</b> '.$keyoss->detalle.' | Respuestas: '.$numerorespuestasss.'</div>';
																					$matriz= Cmatrizpregunta::model()->findAll(array('condition'=>'cpregunta_id = '.(int)$keypss->id));
																					if(!empty($matriz)){
																						$tabless .='<table class="table"><tr>';
																						foreach ($matriz as $keymss) {
																							$tabless .='<th style="background:#CCC;text-align:center"><span >'.$keymss->detalle.' [ '.$keymss->valor.' ]</span></th>';
																							//$table.='<li style="width:100%;padding:5px; margin:5px auto;"></li>';
																						}
																						$tabless .= '</tr>';
																						//COMPROBAR SI HUBO RESPUESTA
																						$tabless .= '<tr>';
																						foreach ($matriz as $keymss) {
																							$RESP = 0;
																							$RESPss= Cencuestadospreguntas::model()->count(array('condition'=>'cmatrizpregunta_id = '.(int)$keymss->id.' and copcionpregunta_id='.$keyoss->id));
																							$tabless .= '<td style="font-size:13px;font-weight:100;text-align:center;">'.$RESPss.'</td>';
																						}
																						$tabless .='</tr>';
																						$tabless .='</table>';

																					}
																					echo $tabless;
																				}else{
																					echo '<li style="padding:10px;width:100%;border:1px solid #ccc;margin:2px auto;">&nbsp;<label style="width:250px;height:15px;font-weight:bold;font-size:13px;">'.$keyoss->detalle.'</label><span style="margin:auto auto auto 100px; padding:2px;">Respuestas: '.$numerorespuestasss.'</span></li>';
																				}
																			}
																			echo '</ul>';
																			echo '</td><tr>';
																		}
																	}else{
																		$contarAbiertas = Cencuestadospreguntas::model()->count(array('condition'=>'pregunta_id ='.(int)$keypss->id));
																		echo '<td><span class="btn btn-link" onclick="traerRespuestas('.$keypss->id.',\''.$keypss->descripcion.'\');">'.$contarAbiertas.'</span></td>';
																	}
																			echo '</tr>';
																	$contss++;
																	
																}
																echo '</tbody>';
																echo '</table>';
															}

														//-----------------------------------
													}
													echo '</ul>';
													echo '</td><tr>';
												}
											}else{
												$contarAbiertas = Cencuestadospreguntas::model()->count(array('condition'=>'pregunta_id ='.(int)$keyps->id));
												echo '<td><span class="btn btn-link" onclick="traerRespuestas('.$keyps->id.',\''.$keyps->descripcion.'\');">'.$contarAbiertas.'</span></td>';
											}
												
											echo '</tr>';
											$conts++;
											
										}
										echo '</tbody>';
										echo '</table>';
									}
									$conto++;
								}
								echo '</ul>';
								echo '</td><tr>';
							}
						}else{
							//echo $keyp->id;
							$contarAbiertas = Cencuestadospreguntas::model()->count(array('condition'=>'pregunta_id ='.(int)$keyp->id));
							echo '<td><span class="btn btn-link" onclick="traerRespuestas('.$keyp->id.',\''.$keyp->descripcion.'\');">'.$contarAbiertas.'</span></td>';
						}
						echo '</tr>';
						$cont++;
						
					}
					echo '</tbody>';
					echo '</table>';
				}
				
			?>
			<hr>
			<h3><b>Resultados de encuestadores</b></h3>
			<div class="col-md-12">
				<?php


					$encuestados = Cencuestadoscquestionario::model()->findAll(array('condition'=>'tiempo is not null and cquestionario_id='.$idc,'select'=>' usuarios_id','distinct'=>true));
					$encuestaF = Cencuestadoscquestionario::model()->findAll(array('condition'=>'tiempo is not null and cquestionario_id='.$idc));
					$tiempo = 0;
					$time = array();
					if(!empty($encuestaF)){
						$phpExcelPath = Yii::getPathOfAlias('ext');
						include($phpExcelPath . "/" . 'time.php');
						$tiempoInicial = new SumaTiempos();
						foreach ($encuestaF	 as $t) {
							$tiempoInicial->sumaTiempo(new SumaTiempos(($t->tiempo.'.00')));
						}
						//print_r($tiempoInicial);
						$tiempo = $tiempoInicial->verTiempoFinal('horas');
					}
					//print_r($encuestados);
				?>
				<h4>Tiempo total generado en las encuesta: <b><?php echo $tiempo ?></b></h4>
				<h5>Número de encuestadores: <b><?php echo count($encuestados); ?></b></h5>
				<?php 
					if(!empty($encuestados)){
						echo '<table><thead><th>Encuestador</th><th>Encuestas realizadas</th><th>Tiempo total</th><th>Encuestas Pendientes</th><th>Encuestas Canceladas</th></thead><tbody>';
						foreach ($encuestados as $keye) {
							$tt = 0;
							$encuestaFs = Cencuestadoscquestionario::model()->findAll(array('condition'=>'tiempo is not null and cquestionario_id='.$idc.' and usuarios_id = '.$keye->usuarios_id));
							$pendients = Cencuestadoscquestionario::model()->count(array('condition'=>'estado = "PENDIENTE" and cquestionario_id='.$idc.' and usuarios_id = '.$keye->usuarios_id));
							$cancelads = Cencuestadoscquestionario::model()->count(array('condition'=>'estado = "CANCELADA" and cquestionario_id='.$idc.' and usuarios_id = '.$keye->usuarios_id));
							$tt = count($encuestaFs);
							$tiempoInicial = new SumaTiempos();
							foreach ($encuestaFs	 as $ts) {
								$tiempoInicial->sumaTiempo(new SumaTiempos(($ts->tiempo.'.00')));
							}
							//print_r($tiempoInicial);
							$tiempos = $tiempoInicial->verTiempoFinal('horas');
							echo '<tr>';
							echo '<td>'.$keye->usuarios->nombres.' '.$keye->usuarios->apellido.'</td>';
							echo '<td>'.$tt.'</td>';
							echo '<td>'.$tiempos.'</td>';
							echo '<td>'.$pendients.'</td>';
							echo '<td>'.$cancelads.'</td>';
						}
						echo '</table>';

					}
				?>
			</div>
		</div>
	</div>

	<div id="mymodal" class="modal fade" tabindex="-1" role="dialog">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Representación Gráfica</h4>
	      </div>
	      <div class="modal-body">
	        <div id="chartContainerV" style="height: 370px; width: 100%;">
				<?php 
					echo '<h1>No existen datos para graficar, seleccione una encuesta.</h1>';
				?>				
			</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>

	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<div id="mymodalM" class="modal fade" tabindex="-1" role="dialog">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Representación Gráfica</h4>
	      </div>
	      <div class="modal-body">
	        <div id="chartContainerVM" style="height: 370px; width: 100%;">
				<?php 
					echo '<h1>No existen datos para graficar, seleccione una encuesta.</h1>';
				?>				
			</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>

	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<?php } ?>
</div>

    <!--SECCION DE ENLACES DIRECTOS-->
<div class="container">
     <div class="row">

        <div class="col-md-12 links-tabs links-footer">
            
            <div class="col-md-2"><p>Tambi&eacute;n puedes ir a:</p></div>
			<?php 
				if(!empty($accesosUser)){
					foreach($accesosUser as $a){
			?>
					<?php if( ($a->accesoSistema->controlador) == 'pvQir' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvQir/admin'); ?>" class="qir-btn"><span class="textoFEnlace">QIR</span></a></div>
					<?php } ?>
							
					<?php if( ($a->accesoSistema->controlador) == 'pvboletinpostventa' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvboletinpostventa/admin'); ?>" class="boletines-btn"><span class="textoFEnlace">Boletines</span></a></div>
					<?php } ?>
							
					<?php if( ($a->accesoSistema->controlador) == 'pvvinMotor' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvvinMotor/admin'); ?>" class="vin-btn"><span class="textoFEnlace">Vin-Motor</span></a></div>
					<?php } ?>
								
					<?php if( ($a->accesoSistema->controlador) == 'pvmodelosposventa' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvmodelosposventa/admin'); ?>" class="modelospv-btn"><span class="textoFEnlace">Modelos</span></a></div>
					<?php } ?>
							
					<?php if( ($a->accesoSistema->controlador) == 'pvcodigoCausal' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvcodigoCausal/admin'); ?>" class="causal-btn"><span class="textoFEnlace">C&oacute;digo Causal</span></a></div>
					<?php } ?>
							
							
					<?php if( ($a->accesoSistema->controlador) == 'pvcodigoNaturaleza' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('pvcodigoNaturaleza/admin'); ?>" class="naturaleza-btn"><span class="textoFEnlace">C&oacute;digo Naturaleza</span></a></div>
					<?php } ?>
					
					<?php if( ($a->accesoSistema->controlador) == 'uaccesosistema' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('uaccesosistema/admin'); ?>" class="accesos-btn"><span class="textoFEnlace">Accesos al Sistema</span></a></div>
					<?php } ?>
					
					<?php if( ($a->accesoSistema->controlador) == 'ucargo' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('ucargo/admin'); ?>" class="cargos-btn"><span class="textoFEnlace">Cargos y Perfiles</span></a></div>
					<?php } ?>
					
					<?php if( ($a->accesoSistema->controlador) == 'uusuarios' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('uusuarios/admin'); ?>" class="usuarios-btn"><span class="textoFEnlace">Usuarios Kia</span></a></div>
					<?php } ?>
					
					<?php if( ($a->accesoSistema->controlador) == 'uusuarios' &&  ($a->accesoSistema->accion) ==  'contactos'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('uusuarios/contactos'); ?>" class="contactos-btn"><span class="textoFEnlace">Cont&aacute;ctos</span></a></div>
					<?php } ?>
				<?php if( ($a->accesoSistema->controlador) == 'uarea' &&  ($a->accesoSistema->accion) ==  'admin'){?>	
						<div class="col-md-2"><a href="<?php echo Yii::app()->createUrl('uarea/admin'); ?>" class="contactos-btn"><span class="textoFEnlace">&Aacute;reas</span></a></div>
					<?php } ?>
					
			<?php
					}
				}
			?>
			<div class="col-md-1"><a href="<?php echo Yii::app()->createUrl('site/menu'); ?>" class="back-btn">Inicio</a></div>	
        </div>
    </div>
</div>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/canvasjs.min.js"></script>
  <script type="text/javascript">
 
  	function Grafica(vll,des){
  		/*var vl = [
		    { label: "apple",  y: 10  },
				{ label: "orange", y: 15  },
				{ label: "banana", y: 25  },
				{ label: "mango",  y: 30  },
				{ label: "grape",  y: 28  }
		];
  		alert(vl)*/
  		chart(vll,des);	
  	$('#mymodal').modal()
  	}
  	function abrirChart(vl,des){
  		var vlS = [];
  		$.ajax({
            url: '<?php echo Yii::app()->createUrl("/cquestionario/graficar") ?>',
            type: 'POST',
            async: false,
            data: {
                vl: vl,
            },
            success: function (result) {
                if(result !=''){
                	data = result.split('|');
                	for(i=0 ; i < data.length; i++){
                		dat = data[i].split('-');
                		if(dat.length >0){
                			if(dat[0] !='' && dat[1] >0){
                			//alert(dat[0]+''+dat[1])
	                		vlS.push({label:dat[0],y:dat[1]});
                				
                			}
                		}
                	}
                	//alert(vlS)
                }else{
                	bootbox.alert('No hay datos para mostrar.')
                }
            }
        });
		Grafica(vlS,des);
	}

	function traerRespuestas(vl,des){
  		$.ajax({
            url: '<?php echo Yii::app()->createUrl("/cquestionario/traerRespuestas") ?>',
            type: 'POST',
            async: false,
            data: {
                vl: vl,
            },
            success: function (result) {
                if(result !='0'){
                	 bootbox.dialog({
		              title: des,
		              message: result
		            });
                }else{
                	bootbox.alert('No hay datos para mostrar.')
                }
            }
        });
	}

	function abrirMatriz(vl,des){
  		$.ajax({
            url: '<?php echo Yii::app()->createUrl("/cquestionario/abrirMatriz") ?>',
            type: 'POST',
            async: false,
            data: {
                vl: vl,
            },
            success: function (result) {
                if(result !=''){
                	 bootbox.dialog({
		              title: des,
		              message: result
		            });
                }else{
                	bootbox.alert('No hay datos para mostrar.')
                }
            }
        });
	}

	function chart(vl,des){
		var chart = new CanvasJS.Chart("chartContainerV", {

      title:{
        text: des              
      },
      data: [//array of dataSeries              
        { //dataSeries object

         /*** Change type "column" to "bar", "area", "line" or "pie"***/
         type: "pie",
        dataPoints:vl
       } 
       ]
     });

    chart.render();
  }
  </script>	
  <?php 
  /*echo '
	<script type="text/javascript">
  window.onload = function () {
    var chart = new CanvasJS.Chart("chartContainer", {

      title:{
        text: "Total de Personas a encuestar"              
      },
      data: [//array of dataSeries              
        { //dataSeries object

         /*** Change type "column" to "bar", "area", "line" or "pie"***/
       /*  type: "column",
         dataPoints: [
         	'.$tts.'
         ]
       }
       ]
     });

    chart.render();
  }
  </script>';*/
  if(!empty($campana)){
  echo '
	<script type="text/javascript">
  window.onload = function () {
    var chart = new CanvasJS.Chart("chartContainer", {

      title:{
        text: "Personas totales para encuesta: '.$totalEncuestados.'"              
      },
      data: [//array of dataSeries              
        { //dataSeries object

         /*** Change type "column" to "bar", "area", "line" or "pie"***/
         type: "pie",
         dataPoints: [
         	
         	{ label: "Encuestas Completadas", y: '.$totalRealizadas.' },
         	{ label: "Encuestas no Deseadas/Canceladas", y: '.$totalNoRealizadas.' },
         	{ label: "Encuestas Pendientes", y: '.($totalEncuestados-$totalRealizadas).' },
         ]
       } 
       ]
     });

    chart.render();
  }
  </script>';
}
  ?>
  <script>
	$(function(){

		$("#btn").hide();
		$("#d_encuestas").html("<span style='font-size:13px;font-weight:bold;'> << Seleccione una campa&ntilde;a</span>");
	})
	function obternerencuesta(vl){
		
		$("#d_encuestas").html("<span style='font-size:13px;font-weight:bold;'> << Seleccione una campa&ntilde;a</span>");
		if(vl > 0){
			$.ajax({
				type: 'POST',
				url: '<?php echo Yii::app()->createUrl("/cquestionario/getencuestas") ?>',
				data: {vl: vl},
				success: function (data) {
					if(data){
						$("#btn").show();
						$("#d_encuestas").html(data);
					}else{
						
						$("#d_encuestas").html("<span style='font-size:13px;font-weight:bold;'>No hay encuestas para mostrar.</span>");
					}
				}
			});
		}
	}

  </script> 
