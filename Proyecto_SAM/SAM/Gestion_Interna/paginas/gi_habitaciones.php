	<head>
	<meta name='author' content='Enrique Álvarez Macías'>
	<script language="javascript">	




//Esta funcion comprueba que el campo tipo de habitacion no este en blanco y luego manda el formulario se utiliza
//en el formulario de modificar tipo de habitacion
function comprobar(val){
	if(form_modif_tipo.tipo_hab_modif.value==""){
		alert("Debe introducir un tipo de habitacion"); 
	}else{
		form_modif_tipo.opcion_tipo.value=2;
		form_modif_tipo.oculto_nombre_tipo.value=val;
		form_modif_tipo.oculto_modif_tipo.value=1;
		form_modif_tipo.submit();
	}
}

	//para resaltar la fila del listado sobre la que está el cursor mediante cambiar el fondo y el color de la fuente de dicha línea así como hacer que el puntero del ratón se convierta en una mano 
		function resaltar_seleccion(tr) {
	  		tr.style.backgroundColor = '#3F7BCC';
	  		tr.style.color = '#F4FCFF';
	  		tr.style.cursor = 'pointer';
		}
	//para desresaltar la fila del listado en el momento en que el cursor se va de encima de ella
		function desresaltar_seleccion(tr) {
	  		tr.style.backgroundColor = '#F4FCFF';
	  		tr.style.color = '#3F7BCC';
		}	
	//Estas 4 funciones (2 para el formulario de alta y 2 para el de modificación son para que al pulsar en las flechas de arriba y abajo en el campo Camas de ambos formularios, el número indicado aparezca en el campo de texto que hay a la izquierda de las flechas, sumando o restando 1 al número que había en dicho campo de texto. 
		function aumentar1(){
            form_alta.camas.value = (form_alta.camas.value/1) + 1;
		}
		function aumentar1b(){
            form_alta.camas.value = (form_alta.camas.value/1) + 1;
		}
        function aumentar2(){
            form_modif.camas_modif.value = (form_modif.camas_modif.value/1) + 1;
		}
		function aumentar3(){
            form_modif2.camas_modif.value = (form_modif2.camas_modif.value/1) + 1;
		}
        function disminuir1(){
            if(form_alta.camas.value != "-1")
                form_alta.camas.value = (form_alta.camas.value/1) - 1;
		}
		function disminuir1b(){
            if(form_alta.camas.value != "-1")
                form_alta.camas.value = (form_alta.camas.value/1) - 1;
		}
        function disminuir2(){
            if(form_modif.camas_modif.value != "-1")
                form_modif.camas_modif.value = (form_modif.camas_modif.value/1) - 1;
		}
		  function disminuir3(){
            if(form_modif2.camas_modif.value != "-1")
                form_modif2.camas_modif.value = (form_modif2.camas_modif.value/1) - 1;
		}
	</script>
</head>
<!--Siempre al cargar la página realiza una conexión persistente a la base de datos que servirá para hacer cualquier consulta sql--> <?php
	if(isset($_SESSION['permisoInternaHabitaciones']) && $_SESSION['permisoInternaHabitaciones']=true){
	@ $db = mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
	if (!$db)
	{
		echo "Error : No se ha podido conectar a la base de datos";
		exit;
	}
	mysql_select_db($_SESSION['conexion']['db']);
?>

<div style='width:100%;'>

<div style='width:400px;float:left;margin-left:50px;margin-top:50px;'
<!--------------------------------------------------------------------------------------------------------->
<div id='caja_superior_izquierda' style='width:100%;'>
<!--------------------------------------------------------------------------------------------------------->			
				
				
	<!------- Alta de habitación ------------------------------------------------->
	<?php
		if(!(isset($_POST['oculto_opcion'])) || ($_POST['oculto_opcion']=="1"))
		//si se ha pulsado el icono de modificar o el de eliminar $_POST['oculto_opcion']será=1, si esta variable no está definida es que se carga por primera vez la pagina con lo que también se carga por defecto el div del alta 
		{
			if(isset($_POST['tipo']))//si existe es que se ha mandado el formulario de alta, es decir se ha pulsado aceptar para intentar dar de lata una habitación
			{
				if(($_POST['num_hab']=='')||($_POST['tipo']=='-'))//se comprueba si el campo número de habitación está vacío ya que es obligatorio rellenarlo y también si el tipo == '-' que es el valor por defecto es que no ha elegido ningún tipo de habitación en la lista desplegable
				{
					//se le vuelve a mostrar el formulario con los valores que introdujo el usuario, están todos en la variable $_POST, aunque antes le saldrán los alert correspondientes que están más abajo escritos	
					echo"
				<FORM name='form_alta' action='principal_gi.php?pag=gi_habitaciones.php' method='POST'>                 
					<TABLE  width='100%' cellspacing='0px'  cellpadding='0' id='tabla_detalles' style='padding:0px 0px 0px 0px;'>
						<tr id='titulo_tablas' style='padding:0px 0px 0px 0px;'>
							<td colspan='4' style='padding:0px 0px 0px 0px;' cellspacing='0px'  cellpadding='0'>
								<div class='champi_izquierda'>&nbsp;</div>
								<div class='champi_centro'>
								<div class='titulo' style='width:340px;text-align:center;padding:0px 0px 0px 0px;'>Nueva Habitación</div>
								</div>
								<div class='champi_derecha'>&nbsp</div>
							</td>
						</tr>
						<TR style='padding:0px 0px 0px 0px;'>
							<td style='padding:0px 0px 0px 0px;' >
								<TABLE width='100%'  cellspacing='0px'  cellpadding='0' style='border: 1px solid #3F7BCC;padding:0px 0px 0px 0px;'>
											<td style='padding: 0px 0px 0px 0px' width='70'>&nbsp;</td>	
											<TD style='padding: 0px 0px 0px 0px' width='160' class='label_formulario' align='left'>Habitación:</td>
											<td cellpadding='0'align='left' cellspacing='0px'>";
												echo "
												<input type='text' name='num_hab' value='" .$_POST['num_hab'] ."' class='input_formulario' size='5' maxlength='5'>";
												echo "
											</TD>
											<td width='70'>&nbsp;</td>	
										</TR>
										<TR>
											<td width='70'>&nbsp;</td>	
											<TD class='label_formulario' width='160' align='left'>Tipo:</TD>
											<TD>
												<SELECT NAME='tipo' class='input_formulario'>
													<option value='-' class='input_formulario'>&nbsp;";
													//conexión a la base de datos para obtener los tipos de habitación que mostrar en el select
													$sql="select * from tipo_habitacion;";
													$result=mysql_query($sql);
													for($i=0;$i<mysql_num_rows($result);$i++)
													{
														$fila=mysql_fetch_array($result);
														//se comprueba si el option es igual al que había elegido el usuario y que está en $_POST['tipo'] para si es el mism ponerle el atributo selected
														if($fila['Id_Tipo_Hab'] == $_POST['tipo'])
														{
															echo "
															<OPTION value='" .$fila['Id_Tipo_Hab'] ."' class='input_formulario' selected>" 	.$fila['Nombre_Tipo_Hab'];
														}
														else
														{
															echo "
															<OPTION value='" .$fila['Id_Tipo_Hab'] ."' class='input_formulario'>" 	.$fila['Nombre_Tipo_Hab'];
														}
													}
													echo"
												</SELECT>
												
											</TD>
											<td width='70'>&nbsp;
											</td>	
										</TR>
										<TR>
											<td width='70'>&nbsp;</td>	
											<TD align='left' class='label_formulario' width='160'>Camas:</TD>
											<TD>
												<TABLE cellspacing='0' cellpadding='0'>
													<TR>
														<TD>";
															echo "
															<INPUT type='text' size='3' name='camas' value='" .$_POST['camas'] ."' class='select_formulario' maxlength='3'>";	
															echo "
														</TD>
														<TD>
															<IMG src='../imagenes/botones/flecha.jpg' height='22' align='absmiddle' usemap='#mapa1' border='0' name='img1'>
														</TD>
													</tr>
												</table>
											</td>
											<td width='70'>&nbsp;</td>	
										</tr>
										<tr>
											<td colspan='4' height='50' align='center'>
												<input type='image' src='../imagenes/botones-texto/aceptar.jpg' alt='Dar de alta la habitación' title='Dar de alta la habitación'>
											</td>
											<MAP name='mapa1'>
												<AREA shape='rect' coords='0,0,11,11' href='#' onclick='aumentar1b()'>
												<AREA shape='rect' coords='0,11,11,21' href='#' onclick='disminuir1b()'>
											</MAP>
										</tr>
									</table>		
								</td>
							</tr>
						</table>
					</FORM>";
					?>
					<script language='javascript'>
						form_alta.num_hab.focus();
					</script>
					<?php
					//---Para mostrar los alert correspondientes a los campos sin rellenar
					if(($_POST['num_hab']=='') && ($_POST['tipo']=='-'))//si el usuario dejo en blanco el campo Número de habitación y no selecciono una opción en el select
					{
						?>
						<script language='javascript'>
							alert("Por favor rellene el campo Número de Habitación y seleccione un tipo de habitación");
						</script>
						<?php
					}
					else if($_POST['num_hab']=='')//si el usuario dejo en blanco el campo Número de habitación
					{
						?>
						<script language='javascript'>
							alert("Por favor rellene el campo Número de Habitación");
						</script>
						<?php
					}
					else if($_POST['tipo']=='-')//Si el usuario no selecciono una opción en el select
					{
						?>
							<script language='javascript'>
								alert("Por favor seleccione un tipo de habitación");
							</script>
						<?php
					}
				}
				else //si se ha enviado el formulario y los datos son correctos, se conecta a la BD y hace la consulta.
				{
					$sql="insert into habitacion(Id_Hab,Camas_Hab) values('" .$_POST['num_hab'] ."'," .$_POST['camas'] .");";
					$sql2="insert into cambio_tipo_habitacion(Id_Hab,Id_Tipo_Hab,Fecha) values ('" .$_POST['num_hab'] ."'," .$_POST['tipo'] .",'" .date("Y-m-d") ."')";
					//echo $sql2;
					$resultado=mysql_query($sql);
					//si no me deja hacer la inserción en la tabla habitación, es porque ya existe esa habitación, no hago la inserción en cambio_tipo_habitacion porque ahí si me dejaría y sería un problema
					if($resultado==1)
					{
						$resultado2=mysql_query($sql2);
					
						//se muestra el mensaje de que se ha producido el alta con un botón de aceptar que recarga la página con lo que por defecto al no estar definido $_POST['opcion'] hará que se cargue el div de alta de formulario con el formulario en blanco	
						echo "
						<TABLE border='0' width='100%' cellspacing='0px' id='tabla_detalles' style='padding: 0px 0px 0px 0px;'>
							<tr id='titulo_tablas'>
								<td colspan='4' style='padding: 0px 0px 0px 0px;'>
									<div class='champi_izquierda'>&nbsp;</div>
									<div class='champi_centro'>
									<div class='titulo' style='width:340px;text-align:center;'>Alta Habitación</div>
									</div>
									<div class='champi_derecha'>&nbsp;</div>
								</td>
							</tr>
							<tr>
								<td style='padding: 0px 0px 0px 0px;'>
									<TABLE width='100%' border='0' cellpadding='0' cellspacing='0px' style='border: 1px solid #3F7BCC;padding: 0px 0px 0px 0px;'>
										<tr>
											<td>
												<table border='0' bgcolor='#F4FCFF'><tr><td align='center' height='60' width='400'  class='label_formulario'>La habitación se ha dado de alta</td></tr><tr><td height='60' valign='middle' align='center'><input type='image' src='../imagenes/botones-texto/aceptar.jpg' onclick='window.location.href=\"principal_gi.php?pag=gi_habitaciones.php\"' alt='Continuar' title='Continuar'></td></tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>";
					}
					else // Si la sentencia sql de insertar en habitacion da error entonces es que ya existe la habitacion , se le comunica al usuario y no se ha llegado a hacer la inserción en cambio_tipo_habitación.
					{
						echo "
						<TABLE border='0' width='100%' cellspacing='0px' id='tabla_detalles'style='padding: 0px 0px 0px 0px;'>
							<tr id='titulo_tablas'>
								<td colspan='4' style='padding: 0px 0px 0px 0px;'>
									<div class='champi_izquierda'>&nbsp;</div>
									<div class='champi_centro'>
									<div class='titulo' style='width:340px;text-align:center;'>Alta de Habitación</div>
									</div>
									<div class='champi_derecha'>&nbsp;</div>
								</td>
							</tr>
							<tr>
								<td style='padding: 0px 0px 0px 0px;'>
									<TABLE width='100%' border='0' cellspacing='0px' style='border: 1px solid #3F7BCC;padding: 0px 0px 0px 0px;'>
										<tr>
											<td>
												<table border='0' bgcolor='#F4FCFF'><tr><td align='center' height='60' width='300'  class='label_formulario'>La habitación " .$_POST['num_hab'] ." no se puede dar de alta porque ya existe</td></tr><tr><td height='60' valign='middle' align='center'><input type='image' src='../imagenes/botones-texto/aceptar.jpg' onclick='window.location.href=\"principal_gi.php?pag=gi_habitaciones.php\"' alt='Continuar' title='Continuar'></td></tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>";	
					}
				}
			}
			else //Si no se ha enviado nunca el formulario, aparece con todos los campos en blanco
			{
				echo "
				<FORM name='form_alta' action='principal_gi.php?pag=gi_habitaciones.php' method='POST'>                 
					<TABLE  width='100%' cellspacing='0px'  cellpadding='0' id='tabla_detalles' style='padding:0px 0px 0px 0px;'>
						<tr id='titulo_tablas' style='padding:0px 0px 0px 0px;'>
							<td colspan='4' style='padding:0px 0px 0px 0px;' cellspacing='0px'  cellpadding='0'>
								<div class='champi_izquierda'>&nbsp;</div>
								<div class='champi_centro'>
								<div class='titulo' style='width:340px;text-align:center;padding:0px 0px 0px 0px;'>Nueva Habitación</div>
								</div>
								<div class='champi_derecha'>&nbsp</div>
							</td>
						</tr>
						<TR style='padding:0px 0px 0px 0px;'>
							<td style='padding:0px 0px 0px 0px;' >
								<TABLE width='100%'  cellspacing='0px'  cellpadding='0' style='border: 1px solid #3F7BCC;padding:0px 0px 0px 0px;'>
									<tr>
										<td width='70'>&nbsp;</td>	
										<TD width='160' class='label_formulario' align='left'>Habitación:</td>
										<td align='left'><input type='text'  name='num_hab' value='' class='input_formulario' size='5' maxlength='5'></TD>
										<td width='70'>&nbsp;</td>	
									</TR>
									<TR>
										<td width='70'>&nbsp;</td>	
										<TD class='label_formulario' width='160'>Tipo:</TD>
										<TD ALIGN='LEFT'>
											<SELECT NAME='tipo' class='input_formulario'>
												<option value='-' class='input_formulario'>&nbsp;";
												//aparece un option vacío por defecto para que el usuario tenga que elegir una de las tres opciones
												//conexión a la base de datos para obtener los tipos de habitación que mostrar en el select
												$sql="select * from tipo_habitacion;";
												$result=mysql_query($sql);
												for($i=0;$i<mysql_num_rows($result);$i++)
												{
													$fila=mysql_fetch_array($result);
													echo "
													<OPTION value='" .$fila['Id_Tipo_Hab'] ."' class='input_formulario'>" .$fila['Nombre_Tipo_Hab'];
												}
												echo "
											</SELECT>
										</TD>
										<td width='70'>&nbsp;</td>
									</TR>
									<TR>
										<td width='70'>&nbsp;</td>
										<TD align='left' class='label_formulario' width='160'>Camas:</TD>
										<TD>
											<TABLE cellspacing='0' cellpadding='0'>
												<TR>
													<TD>
														<INPUT type='text' size='3' name='camas' value='0' class='select_formulario' maxlength='3'>
													</TD>
													<TD>
														<IMG src='../imagenes/botones/flecha.jpg' height='22' align='absmiddle' usemap='#mapa1' border='0' name='img1'>
													</TD>
												</tr>
											</table>
										</td>
										<td width='70'>&nbsp;</td>
									</tr>
									<tr>
										<td colspan='4' height='50' align='center'>
											<input type='image' src='../imagenes/botones-texto/aceptar.jpg' alt='Dar de alta la habitación' title='Dar de alta la habitación'>
										</td>
										<MAP name='mapa1'>
											<AREA shape='rect' coords='0,0,11,11' href='#'  onclick='aumentar1()'>
											<AREA shape='rect' coords='0,11,11,21' href='#' onclick='disminuir1()'>
										</MAP>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</FORM>	";
				?>
				<script language='javascript'>
					form_alta.num_hab.focus();
				</script>
				<?php
			}
		}
		?>
		<!---------------------------------------Baja de habitación -------------------------------------------->
		<?php
			if( (isset($_POST['oculto_opcion']))&&($_POST['oculto_opcion']=="3") )
			//si es igual a 3 es que se ha pulsado el icono eliminar habitación en el listado
			{
				if( (isset($_POST['oculto_eliminar'])) && ($_POST['oculto_eliminar']=="1") )
				//ha pulsado aceptar eliminar por lo que se hace la sentencia y se le muestra el mensaje
				{
					$id_borrar=$_POST['oculto'];
					$sql="delete from habitacion where Id_Hab='" .$id_borrar ."';"; 
					//$_POST['oculto'] tiene el valor de la habitación de la fila del listado en la que se pulsó el icono de eliminar.
					$resul_sentencia=mysql_query($sql);	
					if($resul_sentencia==1)
					{
						//si se ha ejecutado la sentencia primera se borran las entradas de esa habitación en la tabla cambio_tipo_habitacion
						$sql2="delete from cambio_tipo_habitacion where Id_Hab ='" .$id_borrar."'";
						echo "
						<TABLE border='0' width='100%' cellspacing='0px' id='tabla_detalles'  style='padding:0px 0px 0px 0px;'>
							<tr id='titulo_tablas'>
								<td colspan='4'  style='padding:0px 0px 0px 0px;'>
									<div class='champi_izquierda'>&nbsp;</div>
									<div class='champi_centro'>
									<div class='titulo' style='width:340px;text-align:center;padding:0px 0px 0px 0px;'>Baja de Habitación</div>
									</div>
									<div class='champi_derecha'>&nbsp;</div>
								</td>
							</tr>
							<tr>
								<td  style='padding:0px 0px 0px 0px;'>
									<TABLE width='100%' border='0' cellspacing='0px' style='border: 1px solid #3F7BCC;padding:0px 0px 0px 0px;''>
										<tr>
											<td  style='padding:0px 0px 0px 0px;'>
												<table border='0' bgcolor='#F4FCFF'><tr>
												<td align='center' height='60' width='400'  class='label_formulario'>La habitación ha sido eliminada</td></tr><tr><td height='60' valign='middle' align='center'><input type='image' src='../imagenes/botones-texto/aceptar.jpg' onclick='window.location.href=\"principal_gi.php?pag=gi_habitaciones.php\"' alt='Continuar' title='Continuar'></td></tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>";
					}
					else// La habitacion ya está ocupada y no se puede eliminar
					{
						echo "
						<TABLE border='0' width='100%' cellspacing='0px' id='tabla_detalles' style='padding: 0px 0px 0px 0px;'>
							<tr id='titulo_tablas'>
								<td colspan='4' style='padding: 0px 0px 0px 0px;'>
									<div class='champi_izquierda'>&nbsp;</div>
									<div class='champi_centro'>
									<div class='titulo' style='width:340px;text-align:center;padding: 0px 0px 0px 0px;''>Baja de Habitación</div>
									</div>
									<div class='champi_derecha'>&nbsp;</div>
								</td>
							</tr>
							<tr>
								<td style='padding: 0px 0px 0px 0px;'>
									<TABLE width='100%' border='0' cellspacing='0px' style='border: 1px solid #3F7BCC;padding 0px 0px 0px 0px;'>
										<tr>
											<td colspan='4' style='padding 0px 0px 0px 0px;'>
												<table border='0' bgcolor='#F4FCFF'><tr><td align='center' style='text-align:justify;' height='60'   class='label_formulario'>No se ha podido eliminar la habitación " .$_POST['oculto'] ." porque hay personas actualmente en ella o taquillas asignadas a ella. Debe vaciar la habitación para poder eliminarla y/o eliminar las taquillas asignadas a ella.</td></tr><tr><td height='60' valign='middle' align='center'><input type='image' src='../imagenes/botones-texto/aceptar.jpg' onclick='window.location.href=\"principal_gi.php?pag=gi_habitaciones.php\"' alt='Continuar' title='Continuar'></td></tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>";
					}
				}
				else
				//No se ha pulsado aceptar eliminar por lo que se le pide confirmación
				{
					$hab=$_POST['oculto'];
					$sql="select * from habitacion where Id_Hab='" .$hab ."';";
					$result=mysql_query($sql);
					for($i=0;$i<mysql_num_rows($result);$i++)
					{
						$fila=mysql_fetch_array($result);
					}
					//$sql2="select Id_Tipo_Hab from tipo_habitacion where Id_Tipo_Hab='" .$fila['Id_Tipo_Hab'] ."';";
					//para obtener el nombre del tipo de habitación (en la tabla 'tipo_habitacion' a partir del número que representa al tipo de habitación en la tabla 'habitacion'
					
					$sql2="SELECT consulta2.Id_Tipo_Hab,habitacion.Camas_Hab FROM habitacion INNER JOIN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= curdate() GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab AND cambio_tipo_habitacion.Id_Hab = '" .$hab ."') AS consulta2 ON habitacion.Id_Hab=consulta2.Id_Hab";
					
					$result2=mysql_query($sql2);
					for($i=0;$i<mysql_num_rows($result2);$i++)
					{
						$fila2=mysql_fetch_array($result2);
					}
					echo "
					<div id='eliminar_habitacion'>
						<form action='principal_gi.php?pag=gi_habitaciones.php' method='post'>
							<input type='hidden' value='' name='oculto_opcion'>"; 
							//toma valor al pulsar el botón aceptar o cancelar la baja
							echo "
							<input type='hidden' value='0' name='oculto_eliminar'>";
							//toma valor al pulsar el botón aceptar o cancelar la baja
							echo "
							<input type='hidden'  value='' name='oculto'>
							<TABLE border='0' width='100%' cellspacing='0px' id='tabla_detalles'  style='padding:0px 0px 0px 0px;'>
								<tr>
									<td colspan='4'  style='padding:0px 0px 0px 0px;'>
										<div class='champi_izquierda'>&nbsp;</div>						
										<div class='champi_centro'>
										<div class='titulo' style='width:340px;text-align:center;0px 0px 0px 0px;'>Eliminar Habitación</div>
										</div>
										<div class='champi_derecha'>&nbsp;</div>
									</td>
								</tr>
								<tr>
									<td  style='padding:0px 0px 0px 0px;'>
										<TABLE width='100%' border='0' cellspacing='0px' style='border: 1px solid #3F7BCC;adding:0px 0px 0px 0px;'>
											<TR  style='padding:0px 0px 0px 0px;'>
												<TD style='padding:0px 0px 0px 0px;'>
													<TABLE cellspacing='0px' width='100%'>
														<TR>
															<td width='30'>&nbsp;</td>
															<TD class='label_formulario' align='left'>Habitación:</td>
															<td align='left'><label style='font-size:11;font-weight:bold;'>" .$fila['Id_Hab'] ."</label>
															</TD>
														</TR>
														<TR>";
															$query_nombre_tipo="Select Nombre_Tipo_Hab from tipo_habitacion where Id_Tipo_Hab ='" .$fila2['Id_Tipo_Hab'] ."'";
															//echo $query_nombre_tipo;
															$resul_nombre_tipo=mysql_query($query_nombre_tipo);
															for($y=0;$y<mysql_num_rows($resul_nombre_tipo);$y++)
															{
																$fila_nombre_tipo=mysql_fetch_array($resul_nombre_tipo);
																$nombre_tipo=$fila_nombre_tipo['Nombre_Tipo_Hab'];
															}
															
															echo "
															<td width='30'>&nbsp;</td>
															<TD class='label_formulario' width='100'>Tipo:</TD>
															<TD ALIGN='LEFT'>
																<label style='font-size:11;font-weight:bold;'>" .$nombre_tipo ."</label>	
															</TD>
														</TR>
														<TR>
															<td width='30'>&nbsp;</td>
															<TD align='left' class='label_formulario' width='100'>Camas:</TD>
															<TD>
																<TABLE cellspacing='0'>
																	<TR>
																		<TD><label style='font-size:11;font-weight:bold;'>" .$fila['Camas_Hab'] ."</label></TD>
																	</tr>
																</table>
															</td>
														</tr>
														<tr>
															<td colspan='3' height='50' align='center' class='label_formulario'>¿Está seguro de que desea eliminar la habitación?</td>
														</tr>
														<tr>
															<td colspan='3' align='center'>";
															//al pulsar el botón aceptar se le da valor al campo hidden
																echo "
																<input type='image' src='../imagenes/botones-texto/aceptar.jpg' alt='Eliminar la habitación del sistema' value='" .$hab ."' onclick='oculto_opcion.value=3;oculto.value=value;oculto_eliminar.value=1;'>
																<input type='image' src='../imagenes/botones-texto/cancelar.jpg' alt='No eliminar la habitación' value='" .$hab ."'  onclick='oculto_opcion.value=1;oculto.value=value;'>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</form>
					</div>"; //fin del div eliminar habitación
				}
			}
			?>
		
			<!--------------------------------------Modificar habitación ------------------------------------------------>
			<?php
			if( (isset($_POST['oculto_opcion']))&&($_POST['oculto_opcion']=="2") ) //existe $_POST['oculto_opcion'] porque ya ha sido enviado el formulario de nombre 'formulario' que hay en el div del listado, pulsando el icono modificar ya que su valor es 2, el valor que toma ese campo hidden al pulsar dicho icono
			{
				//-----------------------común tanto para el if como para el else que vienen a continuación----------------
				$hab=$_POST['oculto']; //al pulsar el icono modificar en el listado de habitaciones, el campo hidden 'oculto' toma el valor de la habitación que se quiere modificar
		
				//$sql="select * from habitacion where Id_Hab='" .$hab ."';";
				//$sql_tipo="select 
				$sql="SELECT consulta2.Fecha,consulta2.Id_Hab,consulta2.Id_Tipo_Hab,habitacion.Camas_Hab FROM habitacion INNER JOIN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= curdate() GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab AND cambio_tipo_habitacion.Id_Hab = '" .$hab ."') AS consulta2 ON habitacion.Id_Hab=consulta2.Id_Hab";
				//echo "****" .$sql ."***";
				$result=mysql_query($sql);
				for($i=0;$i<mysql_num_rows($result);$i++)
				{
					$fila=mysql_fetch_array($result);
				}
				$sql2="select * from tipo_habitacion";
				$result2=mysql_query($sql2);
				$tipo_actual=$fila['Id_Tipo_Hab'];//el tipo de habitación que está regisrado en la base de datos
				//-----------------------------------------------------------------------------------
				if( (isset($_POST['oculto_modificar']))&&($_POST['oculto_modificar']==1) )//ha pulsado aceptar modificar
				{
					$hab_nueva=$_POST['hab_modif']; //el número de habitación que se pretende modificar, el que aparece en el formulario cuando el usuario pulsa 'Aceptar Modificar'
					if(($hab_nueva == "")||($hab_nueva == "-"))
					{
						//no se hace la sentencia, se le muestra un alert y tras este el formulario con todos los valores que tenía éste cuando el usuario pulsó 'Aceptar Modificar', éstos valores están en $_POST['tipo_modif'],$_POST['hab_modif'] y $_POST['camas_modif']
						if($hab_nueva=="")
						{
							?>
							<script language='javascript'>
								alert("Debe rellenar el campo Número de Habitación");
							</script>
							<?php
						}
						echo "
						<FORM name='form_modif2' action='principal_gi.php?pag=gi_habitaciones.php' method='post'>
							<input type='hidden' value='' name='oculto_opcion'>
							<input type='hidden' value='0' name='oculto_modificar'>
							<input type='hidden'  value='' name='oculto'>";
	
							/*estos 3 campos ocultos guardan siempre los valores registrados actualmente en la base de datos para cuando haya que hacer la sentencia update comprobar si cambian los valores sin tener que hacer una nueva consulta de selección*/
							echo "
							<input type='hidden' name='hab_anterior' value='" .$fila['Id_Hab'] ."'>
							<input type='hidden' name='tipo_anterior' value='" .$fila['Id_Tipo_Hab'] ."'>
							<input type='hidden' name='camas_anterior' value='" .$fila['Camas_Hab'] ."'>

							<TABLE border='0' width='100%' cellpadding='0px'cellspacing='0px' id='tabla_detalles' style='padding:0px 0px 0px 0px;'>
								<tr  style='padding:0px 0px 0px 0px;' cellpadding='0px'cellspacing='0px'>
									<td colspan='5' style='padding:0px 0px 0px 0px;' cellpadding='0px'cellspacing='0px'>
										<div class='champi_izquierda'>&nbsp;</div>
										<div class='champi_centro'>
										<div class='titulo' style='width:340px;text-align:center;padding:0px 0px 0px 0px;'>Modificar Habitación</div>
										</div>
										<div class='champi_derecha'>&nbsp;</div>
									</td>
								</tr>
								<TR  style='padding:0px 0px 0px 0px;' cellpadding='0px'cellspacing='0px'>
									<td style='padding:0px 0px 0px 0px;' cellpadding='0px'cellspacing='0px'>
										<TABLE width='100%' border='0' cellpadding='0px'cellspacing='0px' style='border: 1px solid #3F7BCC;padding:0px 0px 0px 0px;'>
											<tr>
												<td style='padding:0px 0px 0px 0px;' cellpadding='0px'cellspacing='0px'>
													<TABLE cellpadding='0px'cellspacing='0px' width='100%' style='padding:0px 0px 0px 0px;'>
														<tr>
															<td width='30'>&nbsp;</td>
															<TD width='200' class='label_formulario' align='left'>Habitación:</td>
															<td align='left'><input type='text' name='hab_modif' value='' class='input_formulario' size='5' maxlength='5'></TD>
														</TR>
														<TR>
															<td width='30'>&nbsp;</td>
															<TD class='label_formulario' width='200'>Tipo:</TD>
															<TD ALIGN='LEFT'>"; 
																$consulta_name="select Nombre_Tipo_Hab from tipo_habitacion where Id_Tipo_Hab ='" .$fila['Id_Tipo_Hab'] ."'";
																$resul_name=mysql_query($consulta_name);
																for($u=0;$u<mysql_num_rows($resul_name);$u++)
																{
																	$fila_name=mysql_fetch_array($resul_name);
																	$nombre_tipo=$fila_name['Nombre_Tipo_Hab'];
																}
																echo "<label class='label_formulario'>" .$nombre_tipo;
															echo
															"</TD>
														</TR>";
														$query_camas="Select Camas_Hab from habitacion where Id_Hab='" .$hab ."'";
														$resul_camas=mysql_query($query_camas);
														for($e=0;$e<mysql_num_rows($resul_camas);$e++)
														{
															$fila_camas=mysql_fetch_array($resul_camas);
															$num_camas=$fila_camas['Camas_Hab'];
														}
														echo "
														<TR>
															<td width='30'>&nbsp;</td>
															<TD align='left' class='label_formulario' width='200'>Camas:</TD>
															<TD>
																<TABLE cellspacing='0' cellpadding='0'>
																	<TR>
																		<TD><INPUT type='text' size='3' name='camas_modif' value='" .$num_camas ."' class='select_formulario' maxlength='3'></TD>
																		<TD><IMG src='../imagenes/botones/flecha.jpg' height='22' align='absmiddle' usemap='#mapa1' border='0' name='img1'></TD>
																	</tr>
																</table>
															</td>
														</tr>
														<tr>
															<td colspan='3' align='center' height='50'>";
																/*el botón 'Aceptar Modificar' tiene el valor de la habitación a modificar y al hacer click en él se asigna éste valor al campo oculto del formulario 'oculto' y asigna un 1 al campo 'oculto_modificar' que indica que se ha pulsado 'Aceptar Modificar'. Como es un input type='image' al hacer click se envía el formulario */		
																echo "<input type='image' src='../imagenes/botones-texto/aceptar.jpg' alt='Modificar la habitación' value='" .$hab ."' onclick='oculto_opcion.value=2;oculto.value=value;oculto_modificar.value=1;'>
																<input type='image' src='../imagenes/botones-texto/cancelar.jpg' alt='No modificar la habitación' title='No modificar la habitación' onclick='oculto_opcion.value=1'>
															</td>
															<MAP name='mapa1'>
																<AREA shape='rect' coords='0,0,11,11' href='#'  onclick='aumentar3()'>
																<AREA shape='rect' coords='0,11,11,21' href='#' onclick='disminuir3()'>
															</MAP>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</FORM>";
						?>
						<script language='javascript'>
							form_modif2.hab_modif.focus();
						</script>
						<?php
					}
					else //se ha pulsado aceptar modificar y el id_hab es distinto de ""
					{
						//para comprobar si el nuevo nombre de habitación no existe ya en la base de datos
						$query_check_name="Select count(*) as numero from habitacion where Id_Hab = '" .$hab_nueva ."'";
						//echo $query_check_name ."<br>" .$hab_anterior;
						
								$resul_check=mysql_query($query_check_name);
								for($y=0;$y<mysql_num_rows($resul_check);$y++)
								{
									$fila_check=mysql_fetch_array($resul_check);
									$num=$fila_check['numero'];
								}
								
								//si ya existe ese núemro de habitación
								if(($num > 0)&&($hab_nueva != $hab_anterior) )
								{
									?>
									<script language='javascript'>
										alert("No se puede modificar. Ya existe la habitación <?php echo $hab_nueva; ?>");
									</script>
									<?php
										echo "
										<FORM action='principal_gi.php?pag=gi_habitaciones.php' method='post' name='form_modif'>
										<input type='hidden' value='' name='oculto_opcion'>
										<input type='hidden' value='0' name='oculto_modificar'>
										<input type='hidden'  value='' name='oculto'>

										<input type='hidden' name='hab_anterior' value='" .$hab ."'>
										<input type='hidden' name='tipo_anterior' value='" .$fila['Id_Tipo_Hab'] ."'>
										<input type='hidden' name='camas_anterior' value='" .$fila['Camas_Hab'] ."'>

										<TABLE border='0' width='100%' cellspacing='0px' id='tabla_detalles' style='padding:0px 0px 0px 0px;'>
											<tr>	
												<td colspan='5' style='padding:0px 0px 0px 0px;'>
													<div class='champi_izquierda'>&nbsp;</div>
													<div class='champi_centro'>
													<div class='titulo' style='width:340px;text-align:center;'>Modificar Habitación</div>
													</div>
													<div class='champi_derecha'>&nbsp;</div>
												</td>
											</tr>
											<TR>
												<td style='padding:0px 0px 0px 0px;'>
													<TABLE width='100%' border='0' cellspacing='0px' style='border: 1px solid #3F7BCC;padding:0px 0px 0px 0px;'>
														<tr>
															<td style='padding:0px 0px 0px 0px;'>
																<TABLE cellspacing='0px' width='100%' style='padding:0px 0px 0px 0px;'>
																	<tr>
																		<td width='50'>&nbsp;</td>
																		<TD class='label_formulario' align='left' width='200'>Habitación:</td>
																		<td align='left'><input type='text' name='hab_modif' value='" .$fila['Id_Hab'] ."' class='input_formulario' size='5' maxlength='5'></TD>
																	</TR>
																	<TR>
																		<td width='50'>&nbsp;</td>
																		<TD class='label_formulario' width='200'>Tipo:</TD>
																		<TD ALIGN='LEFT'>";
																			$consulta_name="select Nombre_Tipo_Hab from tipo_habitacion where Id_Tipo_Hab =" 	.$fila['Id_Tipo_Hab'];
																			$resul_name=mysql_query($consulta_name);
																			for($u=0;$u<mysql_num_rows($resul_name);$u++)
																			{
																				$fila_name=mysql_fetch_array($resul_name);
																				$nombre_tipo=$fila_name['Nombre_Tipo_Hab'];
																			}
																			echo "<label class='label_formulario'>" .$nombre_tipo;
																			echo
																		"</TD>
																	</tr>";
																	$query_camas="Select Camas_Hab from habitacion where Id_Hab='" .$hab ."'";
																	$resul_camas=mysql_query($query_camas);
																	for($e=0;$e<mysql_num_rows($resul_camas);$e++)
																	{
																		$fila_camas=mysql_fetch_array($resul_camas);
																		$num_camas=$fila_camas['Camas_Hab'];
																	}
																	echo"
																	<TR>
																		<td width='50'>&nbsp;</td>
																		<TD align='left' class='label_formulario' width='200'>Camas:</TD>
																		<TD>
																			<TABLE cellspacing='0' cellpadding='0'>
																				<TR>
																					<TD><INPUT type='text' size='3' name='camas_modif' value='" .$num_camas ."' class='select_formulario' maxlength='3'></TD>
																					<TD><IMG src='../imagenes/botones/flecha.jpg' height='22' align='absmiddle' usemap='#mapa1' border='0' name='img1'></TD>
																				</tr>
																			</table>
																		</td>
																	</tr>
																	<tr>
																		<td colspan='3' align='center' height='50'>
																			<input type='image' src='../imagenes/botones-texto/aceptar.jpg' alt='Eliminar la habitación del sistema' title='Eliminar la habitación del sistema' value='" .$hab ."' onclick='oculto_opcion.value=2;oculto.value=value;oculto_modificar.value=1;'>
																			<input type='image' src='../imagenes/botones-texto/cancelar.jpg' alt='No eliminar la habitación' onclick='oculto_opcion.value=1'>
																		</td>
																		<MAP name='mapa1'>
																			<AREA shape='rect' coords='0,0,11,11' href='#'  onclick='aumentar2()'>
																			<AREA shape='rect' coords='0,11,11,21' href='#' onclick='disminuir2()'>
																		</MAP>
																	</tr>
																</table> 
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</FORM>";
								?>
								<script language='javascript'>
									form_modif.hab_modif.focus();
								</script>
								<?php
						}
						else
						{
							$mysql_datetime = date("Y-m-d");//cojo la fecha
							$fech=$mysql_datetime;

							//Comprueba si ha modificado el nº de camas y ya estan ocupadas
							$sql1="SELECT * from pernocta where Id_Hab='".$hab_modif."' and Fecha_Salida>'". $fech."';";
							$res1=mysql_query($sql1);
							$t_pernocta=mysql_num_rows($res1);//compruebo en la tabla pernocta
							$sql2="SELECT pernocta_gr.Num_Personas as n from pernocta_gr INNER JOIN estancia_gr WHERE pernocta_gr.Id_Hab='".$hab_modif."' and estancia_gr.Nombre_Gr = pernocta_gr.Nombre_Gr";
							$res2=mysql_query($sql2);
							$t=0;
							if(isset($res2)){
								for($i=0;$i<mysql_num_rows($res2);$i++){
									$fila=mysql_fetch_array($res2);
									$t=$fila['n'];
								}
							}
							else
							{
								$fila=mysql_fetch_array($res2);
								$t=$fila['n'];
							}
						
							$sql3="SELECT * from pernocta_p where Id_Hab='".$hab_modif."' and Fecha_Salida>'". $fech."';";
							$res3=mysql_query($sql3);
							$t_p_persona=mysql_num_rows($res3);//compruebo en la tabla pernocta persona
							//Ahora compruebo si todas las camas estan completas
							//$sql4="SELECT  *  from habitacion where Id_Hab='".$hab_modif."';";
							$sql4="SELECT consulta2.Fecha,consulta2.Id_Hab,consulta2.Id_Tipo_Hab,habitacion.Camas_Hab FROM habitacion INNER JOIN (SELECT 	cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= curdate() GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab AND cambio_tipo_habitacion.Id_Hab = '" .$hab_modif ."') AS consulta2 ON habitacion.Id_Hab=consulta2.Id_Hab";
							$res4=mysql_query($sql4);
							$tot=mysql_num_rows($res4);
							$fi=mysql_fetch_array($res4);
					
							$pern=$t+$t_p_persona+$t_pernocta;
						
				
								//Si cambia el numero de camas y no estan ocupadas todavia
					
							
								if(($_POST['camas_modif']>=$_POST['camas_anterior']) ||  $pern<=$_POST['camas_modif'] ){
							
								//estas 3 variables hacen referencia a los valores actualmente registrados en la base de datos
								//$hab_anterior=$_POST['hab_anterior'];
								//$hab_anterior=$fi['Id_Hab'];
								$hab_anterior=$_POST['hab_anterior'];
								$tipo_anterior=$_POST['tipo_anterior'];
								$camas_anterior=$_POST['camas_anterior'];

								//estas 3 a los valores que había en el formulario en el momento en que el usuario pulsó 'Aceptar Modificar'
								$hab_nueva=$_POST['hab_modif'];
								$tipo_nuevo=$_POST['tipo_modif'];
								$camas_nuevo=$_POST['camas_modif'];
					
								//se empieza a crear la sentencia update
								$sentencia="update habitacion set ";
								//se comprueba si cambian los valores nuevos con los registrados en la base de datos y si cambian se añade el cambio a la sentencia update
								if($hab_nueva != $hab_anterior)
								{
									$sentencia .= "Id_Hab='" .$hab_nueva ."'";
								}
								if($camas_nuevo != $camas_anterior)
								{
									if( ($hab_nueva != $hab_anterior))
									{
										$sentencia .= "," ."Camas_Hab=" .$camas_nuevo;
									}
									else
									{
										$sentencia .= "Camas_Hab=" .$camas_nuevo;
									}
								}
								$sentencia .= " where Id_Hab='" .$hab_anterior ."';";
								//echo $sentencia;
								if( strlen($sentencia) > 41 )//significa que ha introducido algún cambio, pues la sentencia por defecto tendrá 41 caracteres "update habitacion set  where Id_Hab=12345"
								{
									//echo $sentencia;
									$resultado=mysql_query($sentencia);
								
									if($resultado==1)
									{
										echo "
										<TABLE border='0' width='100%' cellpadding='0px' cellspacing='0px' id='tabla_detalles' style='padding:0px 0px 0px 0px;'>
											<tr id='titulo_tablas' style='padding:0px 0px 0px 0px;'>
												<td colspan='4' style='padding:0px 0px 0px 0px;'>
													<div class='champi_izquierda'>&nbsp;</div>
													<div class='champi_centro'>
													<div class='titulo' style='width:340px;text-align:center;padding:0px 0px 0px 0px;'>Modificar Habitación</div>
													</div>
													<div class='champi_derecha'>&nbsp;</div>
												</td>
											</tr>
											<tr cellpadding='0px'cellspacing='0px'  style='padding:0px 0px 0px 0px;'> 
												<td style='padding:0px 0px 0px 0px;'>
													<TABLE width='100%' border='0' cellpadding='0px'cellspacing='0px' style='border: 1px solid #3F7BCC;padding:0px 0px 0px 0px;'>
														<tr>
															<td style='padding:0px 0px 0px 0px;'>									
																<table border='0' bgcolor='#F4FCFF'><tr>
																<td width='30'>&nbsp;</td>
																<td align='center' height='60' width='325'  class='label_formulario'>La habitación ha sido modificada</td></tr><tr><td height='60' colspan='2' valign='middle' align='center'><input type='image' src='../imagenes/botones-texto/aceptar.jpg' onclick='window.location.href=\"principal_gi.php?pag=gi_habitaciones.php\"' alt='Continuar' title='Continuar'></td></tr></table>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>";
									}
									else//del resultado
									{
										echo "
										<form action='principal_gi.php?pag=gi_habitaciones.php' method='post'>
											<input type='hidden' name='oculto_opcion' value='2'>
											<input type='hidden' name='hab_modif' value='-'>
											<input type='hidden' name='oculto_modificar' value='1'>
											<input type='hidden' name='tipo_modif' value='" 
											.$tipo_nuevo ."'>
											<input type='hidden' name='camas_modif' value='" .$camas_nuevo ."'>
											<TABLE border='0' width='100%' cellspacing='0px' id='tabla_detalles' style='padding:0px 0px 0px 0px;'>
												<tr id='titulo_tablas'>
													<td colspan='4' style='padding:0px 0px 0px 0px;'>
														<div class='champi_izquierda'>&nbsp;</div>
														<div class='champi_centro'>
														<div class='titulo' style='width:340px;text-align:center;padding:0px 0px 0px 0px;'>Modificar Habitación</div>
														</div>
														<div class='champi_derecha'>&nbsp;</div>
													</td>
												</tr>
												<tr>
													<td style='padding:0px 0px 0px 0px;'>
														<TABLE width='100%' border='0' cellspacing='0px' style='border: 1px solid #3F7BCC;padding:0px 0px 0px 0px;'>
															<tr>
																<td>
																	<table border='0' bgcolor='#F4FCFF'>
																	<tr>
																		<td	width='65'>&nbsp;</td>
																		<td align='center' height='60' width='250'  class='label_formulario'>La Habitación " .$hab_anterior ." ya existe. Elija otro número de habitación</td></tr><tr><td width='30'>&nbsp;</td><td height='60'  valign='middle' align='center'><input type='image' src='../imagenes/botones-texto/aceptar.jpg' alt='Continuar' title='Continuar'>
																		</td>
																	</tr>
																	</table>
																	</form>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</form>";
									}
								}
								else //ha pulsado aceptar modificar pero no ha introducido ningún cambio
								{	
									?>
									<script language='javascript'>
										window.location.href="principal_gi.php?pag=gi_habitaciones.php";
									</script>
									<?php
								}
								}//fin del if que comprueba si el numero de camas es menor q el anterior y las hab estan ya ocupadas
								else{?>
							
							
							
							<script language='javascript'>
								alert("No es posible modificar. Las camas ya están ocupadas");
							</script>
							
							<? 
							
								$sql2="select * from tipo_habitacion";
								$result2=mysql_query($sql2);
								$tipo_actual=$fila['Id_Tipo_Hab'];//el tipo de habitación que está regisrado en la base de datos
								//Vuelvo al formulario de moficar puesto que no le he dejado modificar y tiene que cambiar el numero de camas
								echo "
							<FORM name='form_modif2' action='principal_gi.php?pag=gi_habitaciones.php' method='post'>
							<input type='hidden' value='' name='oculto_opcion'>
							<input type='hidden' value='0' name='oculto_modificar'>
							<input type='hidden'  value='' name='oculto'>";
	
							/*estos 3 campos ocultos guardan siempre los valores registrados actualmente en la base de datos para cuando haya que hacer la sentencia update comprobar si cambian los valores sin tener que hacer una nueva consulta de selección*/
							echo "
							<input type='hidden' name='hab_anterior' value='" .$hab ."'>
							<input type='hidden' name='tipo_anterior' value='".$_POST['hab_anterior'] ."'>
							<input type='hidden' name='camas_anterior' value='". $_POST['camas_anterior'] ."'>

							<TABLE border='0' width='100%' cellspacing='0px' id='tabla_detalles' style='padding:0px 0px 0px 0px;'>
								<tr>
									<td colspan='5' style='padding:0px 0px 0px 0px;'>
										<div class='champi_izquierda'>&nbsp;</div>
										<div class='champi_centro'>
										<div class='titulo' style='width:340px;text-align:center;padding:0px 0px 0px 0px;'>Modificar Habitación</div>
										</div>
										<div class='champi_derecha'>&nbsp;</div>
									</td>
								</tr>
								<TR>
									<td style='padding:0px 0px 0px 0px;'>
										<TABLE width='100%' border='0' cellspacing='0px' style='border: 1px solid #3F7BCC;padding:0px 0px 0px 0px;'>
											<tr>
												<td>
													<TABLE cellspacing='0px' width='100%'>
														<tr>
															<td width='30'>&nbsp;</td>
															<TD class='label_formulario' align='left' width='200'>Habitación:</td>
															<td align='left'><input type='text' name='hab_modif' value='".$_POST['hab_anterior']."' class='input_formulario' size='5' maxlength='5'></TD>
														</TR>
														<TR>
															<td width='30'>&nbsp;</td>
															<TD class='label_formulario' width='200'>Tipo:</TD>
															<TD ALIGN='LEFT'>"; 
																$consulta_name="select Nombre_Tipo_Hab from tipo_habitacion where Id_Tipo_Hab =" .$fila['Id_Tipo_Hab'];
																$resul_name=mysql_query($consulta_name);
																for($u=0;$u<mysql_num_rows($resul_name);$u++)
																{
																	$fila_name=mysql_fetch_array($resul_name);
																	$nombre_tipo=$fila_name['Nombre_Tipo_Hab'];
																}
																echo "<label class='label_formulario'>" .$nombre_tipo;
															echo
															"</TD>
														</tr>";
														$query_camas="Select Camas_Hab from habitacion where Id_Hab='" .$hab ."'";
														$resul_camas=mysql_query($query_camas);
														for($e=0;$e<mysql_num_rows($resul_camas);$e++)
														{
															$fila_camas=mysql_fetch_array($resul_camas);
															$num_camas=$fila_camas['Camas_Hab'];
														}
														echo "
														<TR>
															<td width='30'>&nbsp;</td>
															<TD align='left' class='label_formulario' width='200'>Camas:</TD>
															<TD>
																<TABLE cellspacing='0' cellpadding='0'>
																	<TR>
																		<TD><INPUT type='text' size='3' name='camas_modif' value='" .$num_camas ."' class='select_formulario' maxlength='3'></TD>
																		<TD><IMG src='../imagenes/botones/flecha.jpg' height='22' align='absmiddle' usemap='#mapa1' border='0' name='img1'></TD>
																	</tr>
																</table>
															</td>
														</tr>
														
														<tr>
															<td colspan='3' align='center' height='50'>";
																/*el botón 'Aceptar Modificar' tiene el valor de la habitación a modificar y al hacer click en él se asigna éste valor al campo oculto del formulario 'oculto' y asigna un 1 al campo 'oculto_modificar' que indica que se ha pulsado 'Aceptar Modificar'. Como es un input type='image' al hacer click se envía el formulario */		
																echo "<input type='image' src='../imagenes/botones-texto/aceptar.jpg' alt='Modificar la habitación' title='Modificar la habitación' value='" .$hab ."' onclick='oculto_opcion.value=2;oculto.value=value;oculto_modificar.value=1;'>
																<input type='image' src='../imagenes/botones-texto/cancelar.jpg' alt='No modificar la habitación' title='No modificar la habitación' onclick='oculto_opcion.value=1'>
															</td>
															<MAP name='mapa1'>
																<AREA shape='rect' coords='0,0,11,11' href='#'  onclick='aumentar3()'>
																<AREA shape='rect' coords='0,11,11,21' href='#' onclick='disminuir3()'>
															</MAP>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</FORM>";
						}

						?>





						<?}
					}//fin del else de se ha pulsado aceptar modificar e id_hab es != ""
				}	
				else //no se ha pulsado ACEPTAR MODIFICAR
				{
					echo "
					<FORM action='principal_gi.php?pag=gi_habitaciones.php' method='post' name='form_modif'>
						<input type='hidden' value='' name='oculto_opcion'>
						<input type='hidden' value='0' name='oculto_modificar'>
						<input type='hidden'  value='' name='oculto'>

						<input type='hidden' name='hab_anterior' value='" .$hab ."'>
						<input type='hidden' name='tipo_anterior' value='" .$fila['Id_Tipo_Hab'] ."'>
						<input type='hidden' name='camas_anterior' value='" .$fila['Camas_Hab'] ."'>

						<TABLE border='0' width='100%' cellspacing='0px' id='tabla_detalles' style='padding:0px 0px 0px 0px;'>
							<tr>	
								<td colspan='5' style='padding:0px 0px 0px 0px;'>
									<div class='champi_izquierda'>&nbsp;</div>
									<div class='champi_centro'>
									<div class='titulo' style='width:340px;text-align:center;'>Modificar Habitación</div>
									</div>
									<div class='champi_derecha'>&nbsp;</div>
								</td>
							</tr>
							<TR>
								<td style='padding:0px 0px 0px 0px;'>
									<TABLE width='100%' border='0' cellspacing='0px' style='border: 1px solid #3F7BCC;padding:0px 0px 0px 0px;'>
										<tr>
											<td style='padding:0px 0px 0px 0px;'>
												<TABLE cellspacing='0px' width='100%' style='padding:0px 0px 0px 0px;'>
													<tr>
														<td width='50'>&nbsp;</td>
														<TD class='label_formulario' align='left' width='200'>Habitación:</td>
														<td align='left'><input type='text' name='hab_modif' value='" .$fila['Id_Hab'] ."' class='input_formulario' size='5' maxlength='5'></TD>
													</TR>
													<TR>
														<td width='50'>&nbsp;</td>
														<TD class='label_formulario' width='200'>Tipo:</TD>
														<TD ALIGN='LEFT'>";
																$consulta_name="select Nombre_Tipo_Hab from tipo_habitacion where Id_Tipo_Hab =" .$fila['Id_Tipo_Hab'];
																$resul_name=mysql_query($consulta_name);
																for($u=0;$u<mysql_num_rows($resul_name);$u++)
																{
																	$fila_name=mysql_fetch_array($resul_name);
																	$nombre_tipo=$fila_name['Nombre_Tipo_Hab'];
																}
																echo "<label class='label_formulario'>" .$nombre_tipo;
															echo
															"</TD>
													</tr>";
														$query_camas="Select Camas_Hab from habitacion where Id_Hab='" .$hab ."'";
														$resul_camas=mysql_query($query_camas);
														for($e=0;$e<mysql_num_rows($resul_camas);$e++)
														{
															$fila_camas=mysql_fetch_array($resul_camas);
															$num_camas=$fila_camas['Camas_Hab'];
														}
													echo"
													<TR>
														<td width='50'>&nbsp;</td>
														<TD align='left' class='label_formulario' width='200'>Camas:</TD>
														<TD>
															<TABLE cellspacing='0' cellpadding='0'>
																<TR>
																	<TD><INPUT type='text' size='3' name='camas_modif' value='" .$num_camas ."' class='select_formulario' maxlength='3'></TD>
																	<TD><IMG src='../imagenes/botones/flecha.jpg' height='22' align='absmiddle' usemap='#mapa1' border='0' name='img1'></TD>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td colspan='3' align='center' height='50'>
															<input type='image' src='../imagenes/botones-texto/aceptar.jpg' alt='Eliminar la habitación del sistema' title='Eliminar la habitación del sistema' value='" .$hab ."' onclick='oculto_opcion.value=2;oculto.value=value;oculto_modificar.value=1;'>
															<input type='image' src='../imagenes/botones-texto/cancelar.jpg' alt='No eliminar la habitación' onclick='oculto_opcion.value=1'>
														</td>
														<MAP name='mapa1'>
															<AREA shape='rect' coords='0,0,11,11' href='#'  onclick='aumentar2()'>
															<AREA shape='rect' coords='0,11,11,21' href='#' onclick='disminuir2()'>
														</MAP>
													</tr>
												</table> 
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</FORM>";
					?>
					<script language='javascript'>
						form_modif.hab_modif.focus();
					</script>
					<?php
				}//fin del else (si no se ha pulsado aceptar modificar)
			}		
	?>
	<!---fin del div caja superior izquierda-------->
	</div>

	<!------ Este es el div para el alta de un tipo de habitación -----------------siempre visible-------------------->
	
	<div style='width:100%;position:relative;top:25;'>
	<?php
	//Se evalua el error de Tipo de Habitacion  y se muestra
	if(isset($_GET['mensaje']))
	{
		?>
		<TABLE cellspacing='0px' class='tabla_detalles' style='padding: 0px 0px 0px 0px;'>
			<tr class='titulo_tablas'>
				<td colspan="5" style='padding: 0px 0px 0px 0px;'>
					<div class='champi_izquierda'>
					&nbsp;
					</div>
					<div class='champi_centro'>
					<div class="titulo" style="width:338px;text-align:center;padding: 0px 0px 0px 0px;'">
						Tipo de habitación
					</div>
					</div>
					<div class='champi_derecha'>
					&nbsp;
					</div>
				</td>
			</tr>
			<tr>
				<td style='padding: 0px 0px 0px 0px;'>
					<TABLE width='100%' height='182px' border='0' cellspacing='0px' style='border: 1px solid #3F7BCC;padding: 0px 0px 0px 0px;'>
						<tr>
							<td style='padding: 0px 0px 0px 0px;'>
								<table border='0' bgcolor='#F4FCFF' width='100%' height='100%' cellpadding='0' cellspacing='0' style='padding: 0px 0px 0px 0px;'>
									<tr>
										<?php
										switch($_GET['mensaje'])
										{
											case 1://se ha producido una baja de tipo de habitación
												echo "
												<td cellpadding='0' cellspacing='0' style='padding: 0px 0px 0px 0px;' align='center' valign='bottom' height='50'  class='label_formulario'>
													Se ha eliminado el tipo de habitación</td></tr><tr><td  height='80' valign='middle' align='center'><input type='image' src='../imagenes/botones-texto/aceptar.jpg' onclick='window.location.href=\"principal_gi.php?pag=gi_habitaciones.php\"' alt='Continuar' title='Continuar'>
												</td>";
												break;

											case 2: //se ha producido una alta de tipo de habitación
												echo "
												<td  cellpadding='0' cellspacing='0' style='padding 0px 0px 0px 0px;' style='padding 0px 0px 0px 0px;' align='center' valign='bottom' height='50' class='label_formulario'>
													El tipo de habitación se ha dado de alta</td></tr><tr><td  cellpadding='0' cellspacing='0' style='padding 0px 0px 0px 0px;' height='80' valign='middle' align='center'><input type='image' src='../imagenes/botones-texto/aceptar.jpg' onclick='window.location.href=\"principal_gi.php?pag=gi_habitaciones.php\"' alt='Continuar' title='Continuar'>
												</td>";
												break;

											case 3: //se ha producido una modificación de tipo de habitación
												echo "
												<td   cellpadding='0' cellspacing='0' style='padding 0px 0px 0px 0px;' style='padding 0px 0px 0px 0px;' align='center' valign='bottom' height='50' class='label_formulario'>
													El tipo de habitación se ha modificado</td></tr><tr><td height='80' valign='middle' align='center'><input type='image' src='../imagenes/botones-texto/aceptar.jpg' onclick='window.location.href=\"principal_gi.php?pag=gi_habitaciones.php\"' alt='Continuar' title='Continuar'>
												</td>";
												break;

											case 4: // no hay permiso para eliminar el tipo
												echo "
												<td  cellpadding='0' cellspacing='0' style='padding 0px 0px 0px 0px;'align='left' valign='bottom' height='50' class='label_formulario'>
													<p style='text-align:justify;'> El tipo de habitación no se ha podido eliminar porque hay personas o taquillas asignadas actualmente a habitaciones de ese tipo. Vacíe de personas y/o taquillas las habitaciones de ese tipo para poder eliminarlo.</p></td></tr><tr><td  cellpadding='0' cellspacing='0' style='padding 0px 0px 0px 0px;' height='80' valign='middle' align='center'><input type='image' src='../imagenes/botones-texto/aceptar.jpg' onclick='window.location.href=\"principal_gi.php?pag=gi_habitaciones.php\"' alt='Continuar' title='Continuar'>
												</td>";
												break;
										}
										?>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>				
		<?php	
	}
	else
	{
		if(isset($_POST['tipo_hab']) && ( (isset($_POST['mostrar_listado']))&&($_POST['mostrar_listado']==0) )) //se ha enviado el formulario de nuevo tipo
		{
			if($_POST['tipo_hab'] == '')
			{
				
				//Si el tipo de habitacion no ha sido rellenado se muestra el error y luego se carga el formulario?>
				
				<script language='javascript'>
					alert("Debe rellenar el campo Tipo Habitación"); 
				</script>
				<FORM name='form_alta_tipo' action='principal_gi.php?pag=gi_habitaciones.php' method='POST'  border='0' cellspacing='0px'>        
					<TABLE border='0' width='100%' cellspacing='0px' id='tabla_detalles' style='padding:0px 0px 0px 0px;'>
						<tr>
							<td colspan='4' style='padding:0px 0px 0px 0px;'>
								<div class='champi_izquierda'>&nbsp;</div>
								<div class='champi_centro'>
								<div class='titulo' style='width:338px;text-align:center;'>Nuevo Tipo de Habitación</div>
								</div>
								<div class='champi_derecha'>&nbsp;</div>
							</td>
						</tr>
						<TR>
							<td style='padding:0px 0px 0px 0px;'>
								<TABLE  width='100%' height='182' border='0' cellspacing='0px' style='border: 1px solid #3F7BCC;padding: 0px 0px 0px 0px;'>
									<tr>
										<td style='padding: 0px 0px 0px 0px;'>
											<TABLE cellspacing='0px' width='100%'>
												<tr>
													<TD height='50' class='label_formulario' align='left'>Tipo de Habitación:</td>
													<td align='left'>
														<input type='text' name='tipo_hab' value='' class='input_formulario' size='20' maxlength='20'>
													</TD>
												</TR>
												<tr>
													<TD height='35' align='center'><label class='label_formulario'>Reservable:</label></td>
													<td align='left'>
														<input type='radio' name='reservable' value='S' checked><label class='label_formulario'>Sí</label>
														<input type='radio' name='reservable' value='N'><label class='label_formulario'>No</label>
													</TD>
												</TR>
												<tr>
													<TD height='35' align='center'><label class='label_formulario'>Compartida:</label></td>
													<td align='left'>
														<input type='radio' name='compartida' value='S' checked><label class='label_formulario'>Sí</label>
														<input type='radio' name='compartida' value='N'><label class='label_formulario'>No</label>
													</TD>
												</TR>
												<tr>
													<td height='50' align='center'>
														<input type='image' src='../imagenes/botones-texto/aceptar.jpg' alt='Dar de alta el tipo de habitación' title='Dar de alta el tipo de habitación'>
													</td>
													<td height='50' align='center'>
														<input type='image' src='../imagenes/botones-texto/cancelar.jpg' alt='Cancelar el alta del tipo de habitación' title='Cancelar el alta del tipo de habitación'>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</FORM>
				<script language='javascript'>
					form_alta_tipo.tipo_hab.focus();
				</script>
				<?php
			}
			else //si se ha enviado el formulario y se han rellenado los  campos 
			{
				$sql="SELECT COUNT(*) as num FROM tipo_habitacion WHERE Nombre_Tipo_hab = '" .$_POST['tipo_hab'] ."';";
				$resul=mysql_query($sql);
				for($i=0;$i<mysql_num_rows($resul);$i++)
				{
					$fila=mysql_fetch_array($resul);
					$permiso=$fila2['num'];
					//valdrá 1 si ecuentra una fila coincidente y cero si no existe en la base de datos, con lo cual se podrá dar de alta
				}
				if($permiso==0)// se hace la insercción
				{
					$query="select Id_Tipo_Hab from tipo_habitacion order by Id_Tipo_Hab;"; 
					$result=mysql_query($query);
					for($i=0;$i<mysql_num_rows($result);$i++)
					{
						$ultimo=mysql_fetch_array($result);
					}
					//$ultimo['Id_Tipo_Hab'] tiene el valor más alto de los id_tipo_hab, le sumamos uno para dar de alta el nuevo tipo de habitación con ese id
					
					$new_id= ($ultimo['Id_Tipo_Hab']) +1;
					$query2="Insert into tipo_habitacion (Id_Tipo_Hab, Nombre_Tipo_Hab,Reservable,Compartida) values(" .$new_id .",'" .$_POST['tipo_hab'] ."','" .$_POST['reservable'] ."','" .$_POST['compartida'] ."');";
					mysql_query($query2);
					?>
					<script language='javascript'>
						window.location.href="principal_gi.php?pag=gi_habitaciones.php&mensaje=2";
					</script>
					<?php
				}
				else //ya existe un tipo habitación con ese nombre por tanto se le muestra al usuario ese error y se vuelve a cargar el formulario
				{
					?>
					<script language='javascript'>
						alert("Ya existe un tipo de habitación con ese nombre, por favor elija otro");
					</script>
					<FORM name='form_alta_tipo' action='principal_gi.php?pag=gi_habitaciones.php' method='POST'> <input type='hidden' name='mostrar_listado' value='0'>     
						<TABLE border='0' width='100%' cellspacing='0px' id='tabla_detalles' style='padding: 0px 0px 0px 0px;'>
							<tr>
								<td colspan='4' style='padding: 0px 0px 0px 0px;'>
									<div class='champi_izquierda'>&nbsp;</div>
									<div class='champi_centro'>
									<div class='titulo' style='width:340px;text-align:center;'>Nuevo Tipo de Habitación</div>
									</div>
									<div class='champi_derecha'>&nbsp;</div>
								</td>
							</tr>
							<TR>
								<td style='padding: 0px 0px 0px 0px;'>
									<TABLE width='100%' height='182' border='0' cellspacing='0px' style='border: 1px solid #3F7BCC;padding: 0px 0px 0px 0px;'>
										<tr height='88'>
											<td style='padding: 0px 0px 0px 0px;'>
												<TABLE cellspacing='0px' width='100%'>
													<tr>
														<TD class='label_formulario' align='left' height='50'>Tipo de Habitación:</td>
														<td align='left'>
															<input type='text' name='tipo_hab' value='' class='input_formulario' size='20' maxlength='20'>
														</TD>
													</TR>
													<tr>
														<TD height='35' align='center'><label class='label_formulario'>Reservable:</label></td>
														<td align='left'>
															<input type='radio' name='reservable' value='S' checked><label class='label_formulario'>Sí</label>
															<input type='radio' name='reservable' value='N'><label class='label_formulario'>No</label>
														</TD>
													</TR>
													<tr>
														<TD height='35' align='center'><label class='label_formulario'>Compartida:</label></td>
														<td align='left'>
															<input type='radio' name='compartida' value='S' checked><label class='label_formulario'>Sí</label>
															<input type='radio' name='compartida' value='N'><label class='label_formulario'>No</label>
														</TD>
													</TR>
													<tr>
														<td height='50' align='center'>
															<input type='image' src='../imagenes/botones-texto/aceptar.jpg' alt='Dar de alta el tipo de habitación' title='Dar de alta el tipo de habitación'>
														</td>
														<td height='50' align='center'>
															<input type='image' src='../imagenes/botones-texto/cancelar.jpg' 	alt='Cancelar el alta de tipo de habitación' title='Cancelar el alta de tipo de habitación' onclick='mostrar_listado.value=1;'>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</FORM>
					<script language='javascript'>
						form_alta_tipo.tipo_hab.focus();
					</script>
					<?php
				}
			}
		} //fin de se ha enviado el formulario de nuevo tipo
		else //aún no se ha mandado el formulario, se le muestra.
		{
			if( (isset($_POST['opcion_tipo'])) && ($_POST['opcion_tipo'] !="") ) //se ha pulsado el icono de nuevo tipo o el de eliminar tipo
			{
				if($_POST['opcion_tipo'] == 1) //ha pulsado nuevo tipo
				{
					?>
					<FORM name='form_alta_tipo' action='principal_gi.php?pag=gi_habitaciones.php' method='POST'  >        
						<input type='hidden' name='mostrar_listado' value='0'>
						<TABLE border='0' width='100%' cellspacing='0px' id='tabla_detalles'  cellpadding='0' cellspacing='0' style='padding: 0px 0px 0px 0px;'>
							<tr>
								<td colspan='4'  cellpadding='0' cellspacing='0' style='padding: 0px 0px 0px 0px;'>
								<div class='champi_izquierda'>&nbsp;</div>
								<div class='champi_centro'>
								<div class='titulo' style='width:338px;text-align:center;padding: 0px 0px 0px 0px;'>Nuevo Tipo de Habitación</div>
								</div>
								<div class='champi_derecha'>&nbsp;</div>
								</td>
							</tr>
							<TR>
								<td style='padding: 0px 0px 0px 0px;'  border='0' cellspacing='0px' cellpadding='0'>
									<TABLE width='100%' height='182' border='0' cellspacing='0px'  style='border: 1px solid #3F7BCC;padding:0px 0px 0px 0px;'>
										<tr height='88'>
											<td style='padding: 0px 0px 0px 0px;'>
												<TABLE cellspacing='0px' width='100%'  cellpadding='0' cellspacing='0' style='padding 0px 0px 0px 0px;'>
													<tr>
														<TD height='50' class='label_formulario' align='center'>Tipo de Habitación:</td>
														<td align='left'>
															<input type='text' name='tipo_hab' value='' class='input_formulario' size='20' maxlength='20'>
														</TD>
													</TR>
													<tr>
														<TD height='35' align='center'><label class='label_formulario'>Reservable:</label></td>
														<td align='left'>
															<input type='radio' name='reservable' value='S' checked><label class='label_formulario'>Sí</label>
															<input type='radio' name='reservable' value='N'><label class='label_formulario'>No</label>
														</TD>
													</TR>
													<tr>
														<TD height='35' align='center'><label class='label_formulario'>Compartida:</label></td>
														<td align='left'>
															<input type='radio' name='compartida' value='S' checked><label class='label_formulario'>Sí</label>
															<input type='radio' name='compartida' value='N'><label class='label_formulario'>No</label>
														</TD>
													</TR>
													<tr>
														<td height='50' align='center'>
															<input type='image' src='../imagenes/botones-texto/aceptar.jpg' alt='Dar de alta el tipo de habitación' title='Dar de alta el tipo de habitación'>
														</td>
														<td height='50' align='center'>
															<input type='image' src='../imagenes/botones-texto/cancelar.jpg' alt='Cancelar el alta del tipo de habitación' title='Cancelar el alta del tipo de habitación' onClick='mostrar_listado.value=1;'>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</FORM>
					<script language='javascript'>
						form_alta_tipo.tipo_hab.focus();
					</script>
					<?php
				}
				else //si $_POST['opcion_tipo'] existe y no es 1, tiene que ser 2(modificar) o 3(eliminar tipo de habitación)
				{
					//elminar tipo de habitación
					if($_POST['opcion_tipo']==3)
					{
						//si se ha pulsado aceptar eliminar tipo
						if( (isset($_POST['oculto_eliminar_tipo'])) && ($_POST['oculto_eliminar_tipo']==1) )
						{
							$query="delete from tipo_habitacion where Nombre_Tipo_Hab='" .$_POST['oculto_nombre_tipo'] ."';";
							$resul_sentencia=mysql_query($query);
							if($resul_sentencia==1)
							{
								?>
								<script language='javascript'>
									window.location.href="principal_gi.php?pag=gi_habitaciones.php&mensaje=1";
								</script>
								<?php
							}
							else
							{
								?>
								<script language='javascript'>
									window.location.href="principal_gi.php?pag=gi_habitaciones.php&mensaje=4";
								</script>
								<?php
							}
						}
						else //no se ha confirmado la eliminación de tipo
						{
							
							$tipo_hab=$_POST['oculto_nombre_tipo'];
							/*
							$query="select Id_Tipo_Hab from tipo_habitacion where Nombre_Tipo_Hab ='" .$tipo_hab ."';";

							$result=mysql_query($query);
							for($i=0;$i<mysql_num_rows($result);$i++)
							{
								$fila=mysql_fetch_array($result);	
							}
							$tipo_hab_id=$fila['Id_Tipo_Hab'];
							$sql="SELECT COUNT(*) as num FROM habitacion WHERE Id_Tipo_hab = " .$tipo_hab_id .";";
							$resul=mysql_query($sql);
							for($i=0;$i<mysql_num_rows($resul);$i++)
							{
								$fila2=mysql_fetch_array($resul);
								$permiso=$fila2['num'];
								//contiene el número de habitaciones dadas de alta con ese tipo de habitación
							}
							*/
							//------------------------------------
							//if($permiso == 0)
							//{
								echo "
								<form action='principal_gi.php?pag=gi_habitaciones.php' method='post'>
									<input type='hidden' name='opcion_tipo' value=''>
									<input type='hidden' name='oculto_nombre_tipo' value=''>
									<input type='hidden' name='oculto_eliminar_tipo' value=''>
									<TABLE border='0' width='100%'  cellpadding='0px'cellspacing='0px' id='tabla_detalles' style='padding:0px 0px 0px 0px;'>
										<tr>
											<td colspan='4' style='padding:0px 0px 0px 0px;'>
												<div class='champi_izquierda'>&nbsp;</div>						
												<div class='champi_centro'>
												<div class='titulo' style='width:340px;text-align:center;padding:0px 0px 0px 0px;'>Eliminar Tipo de Habitación</div>
												</div>
												<div class='champi_derecha'>&nbsp;</div>
											</td>
										</tr>
										<tr>
											<td style='padding:0px 0px 0px 0px;'>
												<TABLE width='100%' border='0' cellspacing='0px' cellpadding='0px' height='182' style='border: 1px solid #3F7BCC;padding:0px 0px 0px 0px;'>
													<TR>
														<TD>
															<TABLE cellspacing='0px' width='100%' style='padding:0px 0px 0px 0px;'>
																<tr>
																	<td colspan='2' height='58' valign='bottom' align='center' class='label_formulario'>¿Está seguro de que desea eliminar el tipo de habitación \"" .$tipo_hab ."\"?</td>
																</tr>
																<tr>
																	<td colspan='2' height='80' align='center'>";
																		//al pulsar el botón aceptar se le da valor al campo hidden
																		echo "
																		<input type='image' src='../imagenes/botones-texto/aceptar.jpg' alt='Eliminar el tipo de habitación del sistema' value='" .$tipo_hab ."' onclick='opcion_tipo.value=3;oculto_nombre_tipo.value=value;oculto_eliminar_tipo.value=1;'>
																		<input type='image' src='../imagenes/botones-texto/cancelar.jpg' alt='No eliminar el tipo de habitación'>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</form>";		
							//}
							/*else //$permiso > 0. Existe habitaciones con el tipo que el usuario desea eliminar
							{
								echo "
								<form action='principal_gi.php?pag=gi_habitaciones.php' method='post'>
									<input type='hidden' name='opcion_tipo' value=''>
									<input type='hidden' name='oculto_nombre_tipo' value=''>
									<input type='hidden' name='oculto_eliminar_tipo' value=''>
									<TABLE border='0' width='100%' cellspacing='0px' cellpadding='0px'id='tabla_detalles' style='padding:0px 0px 0px 0px;'>
										<tr style='padding:0px 0px 0px 0px;' cellspacing='0px' cellpadding='0px'>
											<td colspan='4' style='padding:0px 0px 0px 0px;' cellspacing='0px' cellpadding='0px'>
												<div class='champi_izquierda'>&nbsp;</div>						<div class='champi_centro'>
												<div class='titulo' style='width:340px;text-align:center;padding:0px 0px 0px 0px;'>Eliminar Tipo de Habitación</div>
												</div>
												<div class='champi_derecha'>&nbsp;</div>
											</td>
										</tr>
										<tr>
											<td cellspacing='0px' cellpadding='0px'style='padding:0px 0px 0px 0px;'>
												<TABLE width='100%' height='182' border='0' cellspacing='0px' style='border: 1px solid #3F7BCC;padding:0px 0px 0px 0px;'>
													<TR>
														<TD>
															<TABLE cellspacing='0px' width='100%' style='padding:0px 0px 0px 0px;'>
																<tr>
																	<td colspan='2' height='58' valign='bottom' align='center' class='label_formulario'>No se puede eliminar el tipo de habitación \"" .$tipo_hab ."\" porque hay habitaciones de ese tipo registradas en el sistema</td>
																</tr>
																<tr>
																	<td colspan='2' height='80' align='center'>";
																		//al pulsar el botón aceptar se le da valor al campo hidden
																		echo "
																		<input type='image' src='../imagenes/botones-texto/aceptar.jpg' alt='No eliminar el tipo habitación'>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</form>";
							}*/
						}
					}
					else //la opción es modificar TIPO de  habitación
					{	
						$id=$_POST['id_hab'];
						$compartida=$_POST['compartida'];
						$tipo_hab=$_POST['oculto_nombre_tipo'];
						$tipo_hab_anterior=$_POST['oculto_nombre_tipo_anterior'];
						$reservable_anterior=$_POST['reservable'];
						//si se ha pulsado aceptar modificar tipo
						if( (isset($_POST['oculto_modif_tipo'])) && ($_POST['oculto_modif_tipo']==1) )
						{
							$tipo_hab=$_POST['tipo_hab_modif'];
							$sql="SELECT COUNT(*) as num FROM tipo_habitacion WHERE Nombre_Tipo_hab = '" .$_POST['tipo_hab_modif'] ."';";
							$resul=mysql_query($sql);
							for($i=0;$i<mysql_num_rows($resul);$i++)
							{
								$fila=mysql_fetch_array($resul);
								$permiso=$fila['num'];
								//valdrá 1 si ecuentra una fila coincidente y cero si no existe en la base de datos, con lo cual se podrá dar de alta
							}
							$query="update tipo_habitacion set Nombre_Tipo_Hab = '" .$_POST['tipo_hab_modif'] ."',Reservable='" .$_POST['reservable'] ."',Compartida='" .$compartida."'  where Id_Tipo_Hab ='" .$id ."';";
							if( ($_POST['reservable'] == $_POST['oculto_reservable']) && ($_POST['tipo_hab_modif'] == $tipo_hab_anterior)  )
							{
								?>
								<script language='javascript'>
									window.location.href="principal_gi.php?pag=gi_habitaciones.php";
								</script>
								<?php
							}
							else
							{
								mysql_query($query);
								?>
								<script language='javascript'>
									window.location.href="principal_gi.php?pag=gi_habitaciones.php&mensaje=3";
								</script>
								<?php	
							}
						}
						else //no se pulsado aceptar modifiación tipo, luego se le muestra el formulario de modificación
						{
					
						?>
							
							
								
							<form action='principal_gi.php?pag=gi_habitaciones.php' method='post' name='form_modif_tipo' style='padding:0px 0px 0px 0px;'>
							<input type='hidden' name='opcion_tipo' value=''>
							<input type='hidden' name='oculto_nombre_tipo' value=''>
							<input type='hidden' name='oculto_modif_tipo' value=''>
							<input type='hidden' name='oculto_nombre_tipo_anterior' value='" .$tipo_hab ."'>
							<TABLE border='0' width='100%' cellspacing='0px' id='tabla_detalles' style='padding:0px 0px 0px 0px;'>
								<tr id='titulo_tablas'>
									<td colspan='4' style='padding:0px 0px 0px 0px;'>
										<div class='champi_izquierda'>&nbsp;</div>
										<div class='champi_centro'>
										<div class='titulo' style='width:340px;text-align:center;padding:0px 0px 0px 0px;'>Modificar Tipo de Habitación</div>
										</div>
										<div class='champi_derecha'>&nbsp;</div>
									</td>
								</tr>
								<tr>
									<td style='padding:0px 0px 0px 0px;'>
										<TABLE width='100%' height='182' border='0' cellspacing='0px' style='border: 1px solid #3F7BCC;padding:0px 0px 0px 0px;''>
											<tr height='88'>
												<td>
													<TABLE cellspacing='0px' width='100%'>
														<?
														$sql_reser="Select * from tipo_habitacion where Id_Tipo_Hab ='" .$tipo_hab ."';";
														$result_reser=mysql_query($sql_reser);
														$fila_reser=mysql_fetch_array($result_reser);?>
														<tr>
															<TD height='50' class='label_formulario' align='left'>Tipo de Habitación:</td>
															<td align='left'>
																<input type='text' name='tipo_hab_modif' value="<?=$fila_reser['Nombre_Tipo_Hab']?>" class='input_formulario' size='20' maxlength='20'>
															</TD>
														</TR>
														<tr>
															<TD height='35' align='center'><label class='label_formulario'>Reservable:</label></td>
															<td align='left'>
															
																<input type='hidden' id='id_hab' name='id_hab' value="<?=$fila_reser['Id_Tipo_Hab']?>">
																<?echo "
																<input type='hidden' name='oculto_reservable' value='" .$fila_reser['Reservable'] ."'>";
																
																if($fila_reser['Reservable']=='S')
																{
																	echo "<input type='radio' name='reservable' value='S' checked><label class='label_formulario'>Sí</label>
																	<input type='radio' name='reservable' value='N'><label class='label_formulario'>No</label>";
																}
																else
																{
																	echo "<input type='radio' name='reservable' value='S'><label class='label_formulario'>Sí</label>
																	<input type='radio' name='reservable' value='N' checked><label class='label_formulario'>No</label>";
																}
															?>
															</TD></tr>
															<tr>
															<TD height='35' align='center'><label class='label_formulario'>Compartida:</label></td>
															<td align='left'>

															
															
																
																<input type='hidden' name='oculto_compartida' value='"<?=$fila_reser['Compartida']?>"'>
																
																<?if($fila_reser['Compartida']=='S')
																{
																	echo "<input type='radio' name='compartida' value='S' checked><label class='label_formulario'>Si</label>";
																	echo"<input type='radio' name='compartida' value='N'><label class='label_formulario'>No</label>";
																}
																else
																{
																	echo "<input type='radio' name='compartida' value='S'><label class='label_formulario'>Sí</label>";
																	echo" <input type='radio' name='compartida' value='N' checked><label class='label_formulario'>No</label>";
																}
															?>
															</TD>
														</TR>
														<tr>
															<td colspan='2' height='56' align='center'>
															<!--al pulsar el botón aceptar se le da valor al campo hidden-->
															<? $temp=$fila_reser['Id_Tipo_Hab']; ?>
																<img src='../imagenes/botones-texto/aceptar.jpg' alt='Modificar el tipo de habitación del sistema' onclick='comprobar("<?=$temp?>");' style="cursor:pointer;">
																<input type='image' src='../imagenes/botones-texto/cancelar.jpg' alt='Cancelar la modificación'>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
							</form>
							<script language='javascript'>
								form_modif_tipo.tipo_hab_modif.focus();
							</script>
							<?php
						}	
					}
				}
			}else //aún no se ha pulsado nada, se le muestra el listado de Tipo de habitación
			{
				echo "
				<form action='principal_gi.php?pag=gi_habitaciones.php' method='post' name='form_tipo'>
					<TABLE border='0' width='100%' cellspacing='0' id='tabla_detalles' cellpadding='0' style='padding: 0px 0px 0px 0px;'>
						<tr id='titulo_tablas'>
							<td colspan='4' style='padding: 0px 0px 0px 0px;'>
								<div class='champi_izquierda'>&nbsp;</div>
								<div class='champi_centro'>
								<div class='titulo' style='width:340px;text-align:center;padding: 0px 0px 0px 0px;'>Tipo de Habitación</div>
								</div>
								<div class='champi_derecha'>&nbsp;</div>
							</td>
						</tr>
						<tr>
							<td style='padding: 0px 0px 0px 0px;'>
								<TABLE width='100%' border='0' cellpadding='0' cellspacing='0' style='border: 1px solid #3F7BCC;padding: 0px 0px 0px 0px;'>
									<tr>
										<td style='padding: 0px 0px 0px 0px;'>	
										
										<TABLE border='0' cellpadding='0' cellspacing='0px' width='100%' style='border: 1px solid #3F7BCC;padding: 0px 0px 0px 0px;' >	
											<thead class='fixedHeader'>

													<tr>
														<th  width='180'align='center' onclick='orden_tipo.value=1;form_tipo.submit()'>Tipo de Habitación</th>
														<th width='45'  onclick='orden_tipo.value=2;form_tipo.submit()'>Res</th>
														<th width='46'  onclick='orden_tipo.value=3;form_tipo.submit()'>Comp</th>
														<th width='30'>M</th>
														<th width='40'>E</th>
														
													</tr>
												</thead>
														<tr><input type='hidden' name='orden_tipo' value=''></tr>
											</table>
												<div class='tableContainer' style='border:none;height:209px;width:398px;adding: 0px 0px 0px 0px;' name='listado_scroll' >

												<table border='0' cellpadding='0' cellspacing='0px' class='scrollTable' style='padding: 0px 0px 0px 0px;'>
												
												<input type='hidden' name='opcion_tipo' value=''>
													<input type='hidden' name='oculto_nombre_tipo' value=''>";
													echo "
													<tbody class='scrollContent2'>";

													if(isset($_POST['orden_tipo']))
													{
														
														if($_POST['orden_tipo']==1)
														{
															if ($_SESSION['criterio']==0){
																$query="Select * from tipo_habitacion order by 'Nombre_Tipo_Hab' ASC";
																$_SESSION['criterio']=1;
															}
															else{
																$query="Select * from tipo_habitacion order by 'Nombre_Tipo_Hab' DESC";
																$_SESSION['criterio']=0;

															}
														}
														if($_POST['orden_tipo']==2)
														{
															if ($_SESSION['criterio']==0){
																$query="Select * from tipo_habitacion order by 'Reservable' ";
																$_SESSION['criterio']=1;
															
															}
															else{
																$query="Select * from tipo_habitacion order by 'Reservable' DESC ";
																$_SESSION['criterio']=0;

															}
														}
														if($_POST['orden_tipo']==3)
														{
															if ($_SESSION['criterio']==0){
																$query="Select * from tipo_habitacion order by 'Compartida' ";
																$_SESSION['criterio']=1;
															}
														
															else{
																
																$query="Select * from tipo_habitacion order by 'Compartida' DESC ";
																$_SESSION['criterio']=0;

															}
														}
													}
													else{
														$query="Select * from tipo_habitacion order by 'Nombre_Tipo_Hab' ASC";
														
														
													}
													
													$result=mysql_query($query);
													for($i=0;$i<mysql_num_rows($result);$i++)
													{
														$fila=mysql_fetch_array($result);
														echo "
														<tr id='texto_listados' align='center' 		onmouseover='resaltar_seleccion(this);' onmouseout='desresaltar_seleccion(this);'>";
															
															$lon=(strlen($fila['Nombre_Tipo_Hab'] )+120);
														echo"
															<td align='left' width='182'>" .$fila['Nombre_Tipo_Hab'] ."</td>
															
															<td width='48'>";
															if( ($fila['Reservable']=="S") || ($fila['Reservable']=="") )
															{
																echo "Sí";
															}
															else
															{
																echo "No";
															}
															echo "
															</td>
															<td width='48'>";
															if( ($fila['Compartida']=="S") || ($fila['Compartida']=="") )
															{
																echo "Sí";
															}
															else
															{
																echo "No";
															}
															echo "
															</td>
															<td width='31'>
																<img src='../imagenes/botones/modificar.gif' title='Modificar el tipo de habitación' border='0' value='" .$fila['Id_Tipo_Hab'] ."' onclick='oculto_nombre_tipo.value=value;opcion_tipo.value=2;form_tipo.submit();'>
															</td>
															<td width='23' >
																<img src='../imagenes/botones/eliminar.gif' title='Eliminar tipo de habitación' border='0' value='" .$fila['Nombre_Tipo_Hab'] ."' onclick='oculto_nombre_tipo.value=value;opcion_tipo.value=3;form_tipo.submit();'>
															</td>
														</tr>";
													}
													echo"
													</tbody>
												</table>
											</div>	
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<table width='100%' cellpadding='0'>
						<tr>
							<td valign='bottom' height='50' align='center'>
								<input type='image' src='../imagenes/botones-texto/nuevo.jpg' onclick='opcion_tipo.value=1;' alt='Dar de alta un tipo de habitación' title='Dar de alta un tipo de habitación'>
							</td>
						</tr>
					</table>
					
				</form>";
			} //fin del else de no se  ha pulsado ningún icono
		}//fin del else, si existe $_POST['tipo_hab']
	}//fin del else, no existe $_GET, no hay ningún mensaje que mostrar
	?>
	</div>
</div>

	<!----------------------- Div parte derecha, siempre el listado de habitaciones ------------------>
	<div style='width:520px;float:right;margin-right:40px;margin-top:50px;'>
		<?php
		if(isset($_POST['orden']))
		{
			if($_POST['orden']==1) //ordenar por id_habitación
			{
				if (($_SESSION['criterio']==0))
				{
					/*$query="Select * from habitacion order by 'Id_Hab' ASC";*/
					$query="SELECT consulta2.Fecha,consulta2.Id_Hab,consulta2.Id_Tipo_Hab,habitacion.Camas_Hab FROM habitacion INNER JOIN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= curdate() GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AS consulta2 ON habitacion.Id_Hab=consulta2.Id_Hab ORDER BY consulta2.Id_Hab ASC";
					$_SESSION['criterio']=1;
					
				}
				else
				{
					//$query="Select * from habitacion order by 'Id_Hab' DESC";
					$query="SELECT consulta2.Fecha,consulta2.Id_Hab,consulta2.Id_Tipo_Hab,habitacion.Camas_Hab FROM habitacion INNER JOIN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= curdate() GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AS consulta2 ON habitacion.Id_Hab=consulta2.Id_Hab ORDER BY consulta2.Id_Hab DESC";
					$_SESSION['criterio']=0;
					
				}
			}
			elseif($_POST['orden']==2) //ordenar por tipo de habitación
			{
				if (($_SESSION['criterio']==0))
				{
					/*$query="SELECT * FROM habitacion iNNER JOIN tipo_habitacion WHERE habitacion.Id_Tipo_Hab = tipo_habitacion.Id_Tipo_Hab					ORDER BY tipo_habitacion.Nombre_Tipo_Hab ASC";*/
					$query="SELECT consulta2.Fecha,consulta2.Id_Hab,consulta2.Id_Tipo_Hab,habitacion.Camas_Hab FROM habitacion INNER JOIN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= curdate() GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AS consulta2 ON habitacion.Id_Hab=consulta2.Id_Hab ORDER BY consulta2.Id_Tipo_Hab ASC";
					$_SESSION['criterio']=1;
				}
				else
				{

				//$query=" SELECT * FROM habitacion iNNER JOIN tipo_habitacion WHERE habitacion.Id_Tipo_Hab = tipo_habitacion.Id_Tipo_Hab					ORDER BY tipo_habitacion.Nombre_Tipo_Hab DESC";
					$query="SELECT consulta2.Fecha,consulta2.Id_Hab,consulta2.Id_Tipo_Hab,habitacion.Camas_Hab FROM habitacion INNER JOIN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= curdate() GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AS consulta2 ON habitacion.Id_Hab=consulta2.Id_Hab ORDER BY consulta2.Id_Tipo_Hab DESC";
					$_SESSION['criterio']=0;
				}
			}
			elseif($_POST['orden']==3) //ordenar por número de camas
			{
				if (($_SESSION['criterio']==0))
				{
					//$query="Select * from habitacion order by 'Camas_Hab' ASC";
					$query="SELECT consulta2.Fecha,consulta2.Id_Hab,consulta2.Id_Tipo_Hab,habitacion.Camas_Hab FROM habitacion INNER JOIN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= curdate() GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AS consulta2 ON habitacion.Id_Hab=consulta2.Id_Hab ORDER BY habitacion.Camas_Hab ASC";
					$_SESSION['criterio']=1;
				}
				else
				{
					$query="SELECT consulta2.Fecha,consulta2.Id_Hab,consulta2.Id_Tipo_Hab,habitacion.Camas_Hab FROM habitacion INNER JOIN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= curdate() GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AS consulta2 ON habitacion.Id_Hab=consulta2.Id_Hab ORDER BY habitacion.Camas_Hab DESC";
					$_SESSION['criterio']=0;
				}
			}
			else
			{
				$query="SELECT consulta2.Fecha,consulta2.Id_Hab,consulta2.Id_Tipo_Hab,habitacion.Camas_Hab FROM habitacion INNER JOIN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= curdate() GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AS consulta2 ON habitacion.Id_Hab=consulta2.Id_Hab";
				
			}
		}
		else //si no existe $_POST['orden']nunca se ha enviado el formulario
		{
		
			$query="SELECT consulta2.Fecha,consulta2.Id_Hab,consulta2.Id_Tipo_Hab,habitacion.Camas_Hab FROM habitacion INNER JOIN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= curdate() GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AS consulta2 ON habitacion.Id_Hab=consulta2.Id_Hab";
		}
		$result=mysql_query($query);
		echo "
		<TABLE border='0' cellspacing='0' id='tabla_detalles' cellpadding='0' style='padding: 0px 0px 0px 0px'>
			<tr id='titulo_tablas' style='padding:0px 0px 0px 0px;'>
				<td colspan='4' style='padding: 0px 0px 0px 0px'>
					<div class='champi_izquierda'>&nbsp;</div>
					<div class='champi_centro'>
					<div class='titulo' style='width:460px;text-align:center;padding:0px 0px 0px 0px;'>Listado de Habitaciones</div>
					</div>
					<div class='champi_derecha'>&nbsp;</div>
				</td>
			</tr>
			<tr>
				<td style='padding: 0px 0px 0px 0px' cellspacing='0px'cellpadding='0'>
				
					<TABLE border='0' cellspacing='0px'cellpadding='0' style='padding: 0px 0px 0px 0px'>
						<tr>
							<td style='padding: 0px 0px 0px 0px'  cellspacing='0px'cellpadding='0'>
								<TABLE border='0' cellpadding='0' cellspacing='0px' width='100%' style='border: 1px solid #3F7BCC;padding: 0px 0px 0px 0px;' >	
									<thead class='fixedHeader'>
										<tr >
											<th width='' align='center' onclick='formulario.orden.value=1;formulario.submit()'>Habitación</th>
											<th width='154' align='center' onclick='formulario.orden.value=2;formulario.submit()'>Tipo</th>
											<th  align='center'onclick='formulario.orden.value=3;formulario.submit()'>Camas</th>
											<th  align='center'>Modificar</th>
										
											<th align='center' width='' style='padding:0px 0px 0px 0px;' cellspacing='0px'cellpadding='0'>Eliminar</th>
										</tr>
									</thead>
									<tr style='padding:0px 0px 0px 0px;'>	<input type='hidden' name='orden' value=''></tr>
								</table>
							
								<div class='tableContainer'  style='width:520px;height:450px;padding:0px 0px 0px 0px;' name='listado_scroll' width='100%'>
									<table border='0' cellpadding='0' cellspacing='0px' class='scrollTable' style='padding: 0px 0px 0px 0px;'>
										
										<form action='principal_gi.php?pag=gi_habitaciones.php' method='post' name='formulario' >";
										if(isset($_POST['oculto_opcion']))
										{
											echo "
											<input type='hidden' name='oculto' value='" .$_POST['oculto'] ."'>
											<input type='hidden' name='orden' value=''>
											<input type='hidden' name='contador' value=''>
											<input type='hidden' name='oculto_opcion' value='" .$_POST['oculto_opcion'] ."'>";
										}
										else
										{
											echo "<input type='hidden' name='oculto' value=''>
											<input type='hidden' name='orden' value=''>
											<input type='hidden' name='contador' value=''>
											<input type='hidden' name='oculto_opcion' value='1'>";
										}
										echo "
										<tbody class='scrollContent'>";
										//si vamos a ordenar por el tipo de habitación
										if( (isset($_POST['orden'])) && ($_POST['orden']==2) )
										{
											$result=mysql_query($query);
											for($j=0;$j<mysql_num_rows($result);$j++)
											{
													//obtener el nombre del tipo de habitación a partir del Id_Tipo_Hab
													$query3="Select Nombre_Tipo_Hab from tipo_habitacion where Id_Tipo_Hab=" .
												
													$fila2=mysql_fetch_array($result);
													$query3="Select Nombre_Tipo_Hab from tipo_habitacion where Id_Tipo_Hab=" .$fila2['Id_Tipo_Hab'];
													$resul3=mysql_query($query3);
													for($g=0;$g<mysql_num_rows($resul3);$g++)
													{
														$fila3=mysql_fetch_array($resul3);
														$nombre=$fila3['Nombre_Tipo_Hab'];
													}
													echo "
													<tr id='texto_listados' align='center' onmouseover='resaltar_seleccion(this);' onmouseout='desresaltar_seleccion(this);'><td width='92'>" .$fila2['Id_Hab'] ."</td>
														<td align='left' width='157' >" .$nombre ."</td>
														<td width='60'>" .$fila2['Camas_Hab'] ."</td>
														<td width='80'>
															<input type='image' src='../imagenes/botones/modificar.gif' alt='Modificar habitación' value='" .$fila2['Id_Hab'] ."' border='0' onclick='oculto.value=value;oculto_opcion.value=2;'>
														</td>
														<td width='56' ><input type='image' src='../imagenes/botones/eliminar.gif' alt='Eliminar habitación' value='" .$fila2['Id_Hab'] ."'border='0' onclick='oculto.value=value;oculto_opcion.value=3;'>
														</td>
													</tr>";
												
											}
											/*
											//La siguiente comprobación es por si se pudira eliminar un tipo de habitación con habitaciones asignadas, para poner un guión en el listado en caso de no tener tipo la habitación porhaberse eliminado
											$query3="select * from habitacion where Id_Tipo_Hab is null;";
											$result3=mysql_query($query3);
											for($i=0;$i<mysql_num_rows($result3);$i++)
											{
												$fila3=mysql_fetch_array($result3);
												echo "
												<tr id='texto_listados' align='center' onmouseover='resaltar_seleccion(this);' onmouseout='desresaltar_seleccion(this);'><td width='92'>" .$fila3['Id_Hab'] ."</td>
													<td align='left' align='center' width='157' >-</td>
													<td width='60'>" .$fila3['Camas_Hab'] ."</td>
													<td width='90'>
														<input type='image' src='../imagenes/botones/modificar.gif' title='Modificar habitación' value='" .$fila3['Id_Hab'] ."' border='0' onclick='oculto.value=value;oculto_opcion.value=2;'>
													</td>
													<td width='30'><input type='image' src='../imagenes/botones/eliminar.gif' title='Eliminar habitación' border='0' value='" .$fila3['Id_Hab'] ."' onclick='oculto.value=value;oculto_opcion.value=3;'>
													</td>
												</tr>";
											}*/
										}
										else //Orden por id o por número de camas
										{
											for($i=0;$i<mysql_num_rows($result);$i++)
											{
												$query2='Select * from tipo_habitacion;';
												$result2=mysql_query($query2);
												$fila=mysql_fetch_array($result);
												echo "
												<tr id='texto_listados' align='center' onmouseover='resaltar_seleccion(this);' onmouseout='desresaltar_seleccion(this);'><td width='95'>" .$fila['Id_Hab'] ."</td>
													<td align='left' width='155'>";
													$permiso=0;
													for($j=0;$j<mysql_num_rows($result2);$j++)
													{
														$fila2=mysql_fetch_array($result2);
														if($fila['Id_Tipo_Hab'] == $fila2['Id_Tipo_Hab'])
														{
															echo $fila2['Nombre_Tipo_Hab'];
															$permiso=1;
														}
													}
													if($permiso==0)
													{
														echo "-";
													}
													echo "
													</td>
													<td width='60'>" .$fila['Camas_Hab'] ."</td>
													<td width='85'>
														<input type='image' src='../imagenes/botones/modificar.gif' alt='Modificar habitación' value='" .$fila['Id_Hab'] ."' border='0' onclick='oculto.value=value;oculto_opcion.value=2;'>
													</td>
													<td width='45'><input type='image' src='../imagenes/botones/eliminar.gif' alt='Eliminar habitación' border='0' value='" .$fila['Id_Hab'] ."' onclick='oculto.value=value;oculto_opcion.value=3;'>
													</td>
												</tr>";
											}
										}
										
										echo " </tbody>
										</form>
									</table>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>
</div>	";
 mysql_close();
} //Fin del IF de comprobacion de acceso a la pagina
	else	//Muestro una ventana de error de permisos de acceso a la pagina
		 echo "
			<div class='error'>
				NO TIENES PERMISOS PARA ACCEDER A ESTA PÁGINA
			</div>";
?>

