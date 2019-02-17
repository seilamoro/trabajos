<?php session_start(); ?>
	
	<SCRIPT LANGUAGE="JavaScript">
		// esta funcion es para enviar los datos del formulario
		function carga(f) 
		{
		  f.submit();
		}
	</SCRIPT>


	<TABLE>   
		<TR>
			<TD valign=top  style="boder:1px solid;">
				<TABLE>
					<TR>
						<TD  valign=top>
							<form name="estadistica" action="?pag=estadisticas.php" method="post">

							<!-- Esto es la cabecera de la tabla donde va el t�tulo de lo que contiene la tabla que se muestra debajo -->
							
							<table  border="0" width="100%" align="center" cellspacing="0" cellpadding="0" >
								<tr style="padding:0px;">
									<td align="left" style="padding:0px 0px 0px 0px;" width="100%">
										<div class="champi_izquierda" id="alerta_esquina_izquierda"></div>
										<div class="champi_centro">
										<div class="titulo" style="width:293px;text-align:center;">
										<?php
										switch($_SESSION['tipo_estadisticas'])
										{
											case 'edades':
													if( $_SESSION['inf_graf']['edades']==2)
													{
													?>
														Criterios de la Gr�fica
													<?php
													}
													else
													{
													?>
														Criterios del Informe
													<?php
													}
													break;

											case 'pais':
													if( $_SESSION['inf_graf']['pais']==2)
													{
													?>
														Criterios de la Gr�fica
													<?php
													}
													else
													{
													?>
														Criterios del Informe
													<?php
													}
													break;
															
											case 'ccaa':
													if( $_SESSION['inf_graf']['ccaa']==2)
													{
													?>
														Criterios de la Gr�fica
													<?php
													}
													else
													{
													?>
														Criterios del Informe
													<?php
													}
													break;
			
											case 'prov':
													if( $_SESSION['inf_graf']['prov']==2)
													{
													?>
														Criterios de la Gr�fica
													<?php
													}
													else
													{
													?>
														Criterios del Informe
													<?php
													}
													break;
											default:
													{
													?>
														Criterios de la Gr�fica
													<?php
													}
													break;
										}
										?>
										</div>
										</div>
										<div class="champi_derecha"></div>
									<TD>
								</tr>
							</table>
							<table align="center" valign="top" class="tabla_detalles" style="border: 1px solid #3F7BCC;" width="350px">
			
							<!------------------------------------------------------------------------------------------------>	
							<?php
							if( (isset($_SESSION['tipo_estadisticas'])) && ($_SESSION['tipo_estadisticas']=='jcyl') )
							{
								?>
								
									<tr>
										<td height="40"></td>
									</tr>
								
								<tr>
								 <!--fila con los select de mes y a�o aqui se elige el mes y el a�o de la del cual se desea que se muestre
								 la estad�stica que el usuario seleccione en este caso si la estad�stica seleccionada es la de la junta de
								 castilla y leon-->
													<td class="label_formulario">
													<!-- Mes -->
														Mes : <select name="mes_ini_est" onchange="carga(estadistica)">
														<?
														/*if(isset($_SESSION['mes_est']))
														{
															$mes_checked=$_SESSION['mes_est'];
														}
														else
														{
															$mes_checked=date("m");
														}*/
														for($i=1;$i<=12;$i++)
														{
															if($i <= 9 )
																$ii="0" .$i; //para que enero lo mande como 01 y no como 1 y as� sucesivamente, pues es necesario para las consultas
															else
																$ii=$i;
															
															if($i==$_SESSION['mes_inicio'])
															{
																echo '<option selected value='.$ii.'>'.$meses_est[$i];
															}
															else
															{
																echo '<option value='.$ii.'>'.$meses_est[$i];
															}
														}
														?>
														</select>
													</td>
													<td class="label_formulario">
													<!-- A�o -->
														A�o : <select  name="anio_ini_est" onchange="carga(estadistica)">
														<?php 
														if(isset($_SESSION['anio_est']))
														{
															$anio_checked=$_SESSION['anio_est'];
														}
														else
														{
															$anio_checked=date('Y');
														}
														for($i=$anyo_inicio_albergue;$i<=date('Y');$i++)
														{
															if($i==$anio_checked)
															{
																echo'<option selected value='.$i.'>'.$i;
															}
															else
															{
																echo'<option value='.$i.'>'.$i;
															}
														}
														?>
														</select>
													</TD>
												</TR>
												<tr>
													<td height="40"></td>
												</tr>	
												<?php
							}
							else if ( (isset($_SESSION['tipo_estadisticas'])) && ($_SESSION['tipo_estadisticas']!='jcyl') )
							{
								/*A partir de aqui se muestra lo que se va a cargar en la p�gina de la tabla de periodicidad si 
								la estad�stica que se elige no es la correspondiente a la junta de castilla y leon*/
								
								if( (isset($_SESSION['tipo_estadisticas'])) && ($_SESSION['tipo_estadisticas']=='t_alb') )
								{
								/*Si la opci�n seleccionada es la de tipo de alberguista, se le mostrar� en la parte de superior del cuadro 
								la posibilidad de seleccionar entre el gr�fico de sectores o el de barras*/
									?>
									<tr>
										<td height="15"></td>
									</tr>
									<tr>
										<td colspan="6" align="center" class="label_formulario">Tipo de Gr�fica </td>
									</tr>
									<tr>
										<td height="20"></td>
									</tr>
									<TR>
										<td colspan=6 align=center>
											<table>
												<tr>
													<td colspan="2" align="center"><img src="./../imagenes/graficos/sectores.jpg" alt="Gr�fico de Sectores"></td>
													<td colspan="2" align="center"><img src="./../imagenes/graficos/barras.jpg" alt="Gr�fico de Barras"></td>
												</tr>
												<tr>
													<td width="20"></td>
												</tr>
												<tr>
													<?php
													if(isset($_SESSION['grafico']))
													{
														if($_SESSION['grafico']==1)
														{
															?>
															<TD><input type="radio" name="grafico" value="1" align="right" checked onclick="carga(estadistica)"></TD>
															<TD width=50 class="label_formulario">Sectores</label></TD>
															<TD><input type="radio" name="grafico" value="2" onclick="carga(estadistica)"></TD>
															<TD width=50 class="label_formulario">Barras</TD>
															<?php 
														}
														if($_SESSION['grafico']==2)
														{
															?>
															<TD><input type="radio" name="grafico" value="1" align="right"  onclick="carga(estadistica)"></TD>
															<TD width=50 class="label_formulario">Sectores</label></TD>
															<TD><input type="radio" name="grafico" value="2" checked onclick="carga(estadistica)"></TD>
															<TD width=50 class="label_formulario">Barras</TD>
															
															<?php 
														}
														if($_SESSION['grafico']==3)
														{
															?>
															<TD><input type="radio" name="grafico" value="1" align="right" checked onclick="carga(estadistica)"></TD>
															<TD width=50 class="label_formulario">Sectores</label></TD>
															<TD><input type="radio" name="grafico" value="2" onclick="carga(estadistica)"></TD>
															<TD width=50 class="label_formulario">Barras</TD>
															<?php
														}
													}
													else //si no existe $_SESSION['grafico']
													{
														?>
														<TD><input type="radio" name="grafico" value="1" align="right" checked onclick="carga(estadistica)"></TD>
														<TD width=50 class="label_formulario">Sectores</label></TD>
														<TD><input type="radio" name="grafico" value="2" onclick="carga(estadistica)"></TD>
														<TD width=50 class="label_formulario">Barras</TD>
														<?php
													}
													?>
												</tr>
											</table>
										</td>
									</tr>
									<?php
								}
								else 
								{
								
								/*Si la opci�n seleccionada no es la de tipo de alberguista, ni la de la de la junta de castilla y le�n,
								se le mostrar� en la parte de superior del cuadro la posibilidad de seleccionar entre el gr�fico de sectores,
								el de barras o el de pol�gonos */
								
								//if($_SESSION['tipo_estadisticas']=='edades'
								//$second_arg=
									if($_SESSION['inf_graf'][$_SESSION['tipo_estadisticas']] ==2)
									{
										?>
										<tr>
											<td height="15"></td>
										</tr>
										<tr>
											<td colspan="6" align="center" class="label_formulario">Tipo de Gr�fica </td>
										</tr>
										<tr>
											<td height="20"></td>
										</tr>
										<TR>
											<td colspan=6 align=center>
												<table>
													<tr>
														<td colspan="2" align="center"><img src="./../imagenes/graficos/sectores.jpg" alt="Gr�fico de Sectores"></td>
														<td colspan="2" align="center"><img src="./../imagenes/graficos/barras.jpg" alt="Gr�fico de Barras"></td>
														<td colspan="2" align="center"><img src="./../imagenes/graficos/poligono.jpg" alt="Gr�fico de Pol�gonos"></td>
													</tr>
													<tr>
														<td width="20"></td>
													</tr>
													<tr>
														<?php
														if(isset($_SESSION['grafico']))
														{
															if($_SESSION['grafico']==1)
															{
																?>
																<TD><input type="radio" name="grafico" value="1" align="right" checked onclick="carga(estadistica)"></TD>
																<TD width=50 class="label_formulario">Sectores</label></TD>
																<TD><input type="radio" name="grafico" value="2" onclick="carga(estadistica)"></TD>
																<TD width=50 class="label_formulario">Barras</TD>
																<TD><input type="radio" name="grafico" value="3" onclick="carga(estadistica)"></TD>
																<TD width=50 class="label_formulario">Pol�gono</TD>
																<?php 
															}
															if($_SESSION['grafico']==2)
															{
																?>
																<TD><input type="radio" name="grafico" value="1" align="right"  onclick="carga(estadistica)"></TD>
																<TD width=50 class="label_formulario">Sectores</label></TD>
																<TD><input type="radio" name="grafico" value="2" checked onclick="carga(estadistica)"></TD>
																<TD width=50 class="label_formulario">Barras</TD>
																<TD><input type="radio" name="grafico" value="3" onclick="carga(estadistica)"></TD>
																<TD width=50 class="label_formulario">Pol�gono</TD>
																<?php 
															}
															if($_SESSION['grafico']==3)
															{
																?>
																<TD><input type="radio" name="grafico" value="1" align="right"  onclick="carga(estadistica)"></TD>
																<TD width=50 class="label_formulario">Sectores</label></TD>
																<TD><input type="radio" name="grafico" value="2" onclick="carga(estadistica)"></TD>
																<TD width=50 class="label_formulario">Barras</TD>
																<TD><input type="radio" name="grafico" value="3" checked onclick="carga(estadistica)"></TD>
																<TD width=50 class="label_formulario">Pol�gono</TD>
																<?php
															}
														}
														else //si no existe $_SESSION['grafico']
														{
															?>
															<TD><input type="radio" name="grafico" value="1" checked align="right"  onclick="carga(estadistica)"></TD>
															<TD width=50 class="label_formulario">Sectores</label></TD>
															<TD><input type="radio" name="grafico" value="2" onclick="carga(estadistica)"></TD>
															<TD width=50 class="label_formulario">Barras</TD>
															<TD><input type="radio" name="grafico" value="3" onclick="carga(estadistica)"></TD>
															<TD width=50 class="label_formulario">Pol�gono</TD>
															<?php
														}
														?>
													</tr>
												</table>
											</td>
										</tr>
									<?php			
									}
								}
							}
									if( ($_SESSION['tipo_estadisticas'] != 'jcyl') && ($_SESSION['inf_graf'][$_SESSION['tipo_estadisticas']] ==1 ))
									{
									
									/*Aqui lo que se controla es que el sistema muestre el rango de periodicidad del cual se desea el gr�fico
									que se vaya a seleccionar, en el caso de que la estad�stica seleccionada sea cualquier otra que no sea la
									referente a la junta de castilla y le�n el sistema mostrar� un listado que dejar� seleccionar entre el 
									dia actual, el mes, el trimestre, semestre o a�o que se desee*/
									
										?>
												<!----Este select es el del rango de temporal---->
												<tr>
													<td height="15"></td>
												</tr>
												<tr>
													<TD class="label_formulario" align="left"  >
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Periodicidad :
													</TD>
													<td align="left" >
														<select name='tiempo' onchange="carga(estadistica)">
															<?php
															
															// listado con las opciones de rango temporal que se pueden requerir al sistema
															if(!isset($_SESSION['tiempo']))
															{
																echo'<option value=1>D�a actual';
																echo'<option value=2 selected>Mensual';
																echo'<option value=3>Trimestral';
																echo'<option value=4>Semestral';
																echo'<option value=5>Anual';
															}
															else
															{
																if($_SESSION['tiempo']==1)
																{
																?>
																	<option value=1 selected>D�a actual
																<?php
																}
																else
																{
																?>
																	<option value=1>D�a actual
																<?php
																}
																if($_SESSION['tiempo']==2)
																{
																?>
																	<option value=2 selected>Mensual
																<?php
																}
																else
																{
																?>
																	<option value=2>Mensual
																<?php
																}
																if($_SESSION['tiempo']==3)
																{
																?>
																	<option value=3 selected>Trimestral
																<?php
																}
																else
																{
																?>
																	<option value=3>Trimestral
																<?php
																}
																if($_SESSION['tiempo']==4)
																{
																?>
																	<option value=4 selected>Semestral
																<?php
																}
																else
																{
																?>
																	<option value=4>Semestral
																<?php
																}
																if($_SESSION['tiempo']==5)
																{
																?>
																	<option value=5 selected>Anual
																	<?php
																}
																else
																{	
																?>
																	<option value=5>Anual
																	<?php
																}
															}
															?>
														</select>
													</td>
												</tr>
											<?php
									}
												/* si el rango temporal que se desea es el del dia, debajo del listado del rango temporal 
												saldr� una la fecha del dia en el que se este*/
													if( (!isset($_SESSION['tiempo'])) || ($_SESSION['tiempo']==1) )
													{
														if($_SESSION['tipo_estadisticas'] != 'jcyl')
														{
														echo"
														<tr>
															<td height='15'></td>
														</tr>
														<tr>
															<td class='label_formulario' align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Estad�stica del d�a:</td><td class='label_formulario' align='left'>&nbsp;&nbsp;";
															echo date('d') ."-" .date("m") ."-" .date('Y') ."</td>
														</tr>";
														}
													}
													 if( ((isset($_SESSION['tiempo'])) && $_SESSION['tiempo']!=5) && (((isset($_SESSION['tiempo'])) && $_SESSION['tipo_estadisticas'] != 'jcyl') && $_SESSION['tiempo']!=1) ) 
													{
													
													/*si el rango temporal seleccionado no es ni el del dia ni el del a�o, se mostraran dos 
													listados uno con el mes que se desee elegir y otro con el a�o, para que el usuario 
													seleccione el mes de inicio y a�o de inicio del cual desea el gr�fico de la estad�stica*/
														if($_SESSION['inf_graf'][$_SESSION['tipo_estadisticas']] ==1)
														{
															?>
															<tr>
																<td height='15'></td>
															</tr>
															<tr> <!--fila con los select de mes y a�o -->
																<td class="label_formulario" align='center'>
																	Mes :&nbsp;<select name="mes_ini_est" onchange="carga(estadistica)">
																	<?
																	if(isset($_SESSION['mes_est']))
																	{
																		$mes_checked=$_SESSION['mes_est'];
																	}
																	else
																	{
																		$mes_checked=date("m");
																	}
																	for($i=1;$i<=12;$i++)
																	{
																		if($i <= 9 )
																			$ii="0" .$i; //para que enero lo mande como 01 y no como 1 y as� sucesivamente, pues es necesario para las consultas
																		else
																			$ii=$i;
																		if($i==$mes_checked)
																		{
																			echo '<option selected value='.$ii.'>'.$meses_est[$i];
																		}
																		else
																		{
																			echo '<option value='.$ii.'>'.$meses_est[$i];
																		}
																	}
																	?>
																	</select>
																</td>
																<td class="label_formulario" align='left'>
																	A�o :&nbsp;<select  name="anio_ini_est" onchange="carga(estadistica)">
																	<?php 
																		if(isset($_SESSION['anio_est']))
																		{
																			$anio_checked=$_SESSION['anio_est'];
																		}
																		else
																		{
																			$anio_checked=date('Y');
																		}
																		for($i=$anyo_inicio_albergue;$i<=date('Y');$i++)
																		{
																			if($i==$anio_checked)
																			{
																				echo'<option selected value='.$i.'>'.$i;
																			}
																			else
																			{
																				echo'<option value='.$i.'>'.$i;
																			}
																		}
																	?>
																	</select>
																</TD>
															</TR>
															<?
														}
													}
													else if ( (isset($_SESSION['tiempo'])) && ($_SESSION['tiempo']==5) && ($_SESSION['inf_graf'][$_SESSION['tipo_estadisticas']] ==1) )
													{
													/*si el rango temporal que el usuario ha seleccionado es el del a�o, se le mostrar� 
													un listado con los a�os que puede elegir para que el sistema le muestre el gr�fico 
													seleccionado */
													?>
														<tr>
															<td height='15'></td>
														</tr>
														<td class="label_formulario" align='left'>
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A�o :</td>
														<td align='left'>
														<select  name="anio_ini_est" onchange="carga(estadistica)">
														<?php 
														if(isset($_SESSION['anio_est']))
														{
															$anio_checked=$_SESSION['anio_est'];
														}
														else
														{
															$anio_checked=date('Y');
														}
														for($i=$anyo_inicio_albergue;$i<=date('Y');$i++)
														{
															if($i==$anio_checked)
															{
																echo'<option selected value='.$i.'>'.$i;
															}
															else
															{
																echo'<option value='.$i.'>'.$i;
															}
														}
														?>
														</select>
													</TD>
												</TR>
												<?php
													}
											if($_SESSION['inf_graf'][$_SESSION['tipo_estadisticas']] ==1)
													{
													?>
												<tr>
													<td height="15"></td>
												</tr>
												<tr>
													<TD class="label_formulario" align="left"  >
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tipo Cliente :
													</TD>	
													<td align="left">
													<select name='tipo_cliente' onchange="carga(estadistica);">
														<?php
															if($_SESSION['tipo_cliente']==1)
															{
																?>
																<option value="1" selected>Todos
																<?php
															}
															else
															{
																?>
																<option value="1">Todos
																<?php
															}
															if($_SESSION['tipo_cliente']==2)
															{
																?>
																<option value="2" selected>Alberguista
																<?php
															}
															else
															{
																?>
																<option value="2">Alberguista
																<?php
															}
															if($_SESSION['tipo_cliente']==3)
															{
																?>
																<option value="3" selected>Peregrino
																<?php
															}
															else
															{
																?>
																<option value="3">Peregrino
																<?php
															}
															?>
														</select>
													</td>
												</tr>
												<tr>
													<td height="15"></td>
												</tr>
													<?php
													}
													?>
										</table>

							</form>
						
						</TD>
					</TR>
					<tr>
						<form name='formu_botones' action='?pag=estadisticas.php' method='POST'>
						<?php
						if($_SESSION['inf_graf'][$_SESSION['tipo_estadisticas']] ==2)
						{
							?>
							<td colspan="6" align="center" >
								<!-- Este es el bot�n para que el programa imprima la estad�stica que este mostr�ndose en 
								ese determinado momento-->
								<a href="#" onclick="window.open('paginas/imprimir_estadistica.php?pulsado_imprimir=yes') "><img src="./../imagenes/botones-texto/imprimir.jpg" border=0 alt="Imprimir Estad�stica"></a>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type='image' src="./../imagenes/botones-texto/informe.jpg" border="0" alt="Ver Informe">
								<?php
									//para que al pulsar el bot�n informe cuando se ve una de las gr�ficas se muestre el informe correspondiente
									if($_SESSION['inf_graf']['prov']==2)
										echo "<input type='hidden' name='ver_informe' value='prov'>";
									if($_SESSION['inf_graf']['pais']==2)
										echo "<input type='hidden' name='ver_informe' value='pais'>";
									if($_SESSION['inf_graf']['ccaa']==2)
										echo "<input type='hidden' name='ver_informe' value='ccaa'>";
									if($_SESSION['inf_graf']['edades']==2)
										echo "<input type='hidden' name='ver_informe' value='edades'>";
								?>
							</td>
						<?php
						}
						?>
						</form>
					</tr>
				</TABLE>
			</TD>
		</TR>
	</TABLE> 
        
