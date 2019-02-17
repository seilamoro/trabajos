<script language="JavaScript">
	function desresaltar_seleccion(tr) {
	  	tr.style.backgroundColor = '#F4FCFF';
	  	tr.style.color = '#3F7BCC';
	}
	function resaltar_seleccion(tr) {
	  	tr.style.backgroundColor = '#3F7BCC';
	  	tr.style.color = '#F4FCFF';
	  //	tr.style.cursor = 'pointer';
	}
</script>
<table border="0" id="tabla_detalles">
					<tr id="titulo_tablas">
						<td colspan="8">
							<div style="height:25px;width:30px;background-image:url('img_tablas/esquina_arriba_izquierda.jpg');background-repeat:no-repeat;float:left;" id="alerta_esquina_izquierda">&nbsp;</div>
							<div style="height:25px;text-align:center;background-image:url('img_tablas/linea_horizontal.jpg');background-repeat:repeat-x;float:left;">
							<div class="titulo" style="width:800px;text-align:center;">Listado de
							Taquillas</div>
							</div>
							<div style="height:25px;width:30px;background-image:url('img_tablas/esquina_arriba_derecha.jpg');float:left;">&nbsp;</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="tableContainer" style="height:405px;">
								<table border="0" cellpadding="0" cellspacing="0" width="98%" class="scrollTable">
									<thead class="fixedHeader">
										<tr align="left">
										<TH width="150px">Grupo</Th>
										   <TH width="75px">D.N.I.</Th>
											<TH width="100px">Nombre</Th>
											<TH width="150px">Apellidos </Th>
											<th width="75px">Tipo</th>
											<TH width="50px">Taquilla</Th>
											<TH width="50px">Habitación</Th>
											
											
											<TH width="50px">Desocupar</th>
											
										</tr>
									</thead>
									<tbody class="scrollContent">
										<tr align='left' id="texto_listados" onmouseover="resaltar_seleccion(this);" onmouseout="desresaltar_seleccion(this);">
                                                <td>Colegio El Cid
</td>
                                                <td>73567428F
</td>
												<TD >Pedro 
												</TD>
												<TD >Lopez Alvarez
												</TD>
												
												<TD >Habitación</td>
												<TD align='center'>9</td>
												<TD align='center'>10</td>
												
              <TD align='center'><img src="./imagenes/botones/desocupar.jpg" width="30" height="30"></td>
												
										</tr>
										<tr align='left' id="texto_listados" onmouseover="resaltar_seleccion(this);" onmouseout="desresaltar_seleccion(this);">
											<td>
</td>
                                                <td>73489528F
</td>
												<TD >Lucas
												</TD>
												<TD >Ríos Alonso
												</TD>

												<TD >Pasillo</td>
												<TD align='center'>4</td>
												<TD align='center'></td>
												
              <TD align='center'><img src="./imagenes/botones/desocupar.jpg" width="30" height="30"></td>
											
											
										</tr>

										
									</tbody>
								</table>
							</div>							
						</td>
					</tr>
					<tr>
						<td align="left">
							<a href=".?pag=busq.php"><img src="./imagenes/botones/volver.gif" border="0" alt="Volver"></a>
						</td>
					</tr>	
				</table>
