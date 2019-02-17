<?PHP
session_start();

if(isset($_SESSION['permisoUsuarios']) && $_SESSION['permisoUsuarios']==true) //Comprobando que se tiene permiso para acceder a la pagina
		{
?>
<!--Este estilos es para que resalte el enlace en los listados al darles para que ordenen-->
<style type="text/css"> 
a.enlace { 
color: white; 
width: 100%; 
height: 100%; 
text-decoration: none; 
} 
a.enlace:hover { 
text-decoration: none; 
} 
</style>
<script language="javascript">
//para resaltar la fila del listado sobre la que está el cursor mediante cambiar el fondo y el color de la fuente de dicha línea así como hacer que el puntero del ratón se convierta en una mano 
	function resaltar_seleccion(tr) 
		{
			tr.style.backgroundColor = '#3F7BCC';
			tr.style.color = '#F4FCFF';
			tr.style.cursor = 'pointer';
		}
// Esta función anula la función anterior
	function desresaltar_seleccion(tr) 
		{
			tr.style.backgroundColor = '#F4FCFF';
			tr.style.color = '#3F7BCC';
		}
//Función para mostrar Nueva Tarifa (es la opción 1), Modificar Tarifa (es la opción 2) o Eliminar Tarifa(es la opción 3) sin quitar de la pantalla en ningún momento el listado de las Tarifas 
	function cambiar_opcion(op,ta,hab,ed,per,n_hab,n_ed,n_per)	
		{
			if (op==1)
				{				
					document.getElementById('nueva_tarifa').style.display='block';
					document.getElementById('modificar_tarifa').style.display='none';
					document.getElementById('eliminar_tarifa').style.display='none';
				}
			if (op==2)
				{
					document.getElementById('nueva_tarifa').style.display='none';
					document.getElementById('modificar_tarifa').style.display='block';
					document.getElementById('eliminar_tarifa').style.display='none';

					//Es para mandar los datos al formulario cuando se llame a la opción de modificar

					document.forms["modifica_tarifa"].tarifa_mod_antiguo.value = ta;						
					document.forms["modifica_tarifa"].edad_mod_antiguo.value = ed;					
					document.forms["modifica_tarifa"].tipo_persona_mod_antiguo.value = per;
					document.forms["modifica_tarifa"].habitacion_mod_antiguo.value = hab;

  					document.forms["modifica_tarifa"].tarifa_mod.value = ta;
					document.forms["modifica_tarifa"].edad_mod.value = ed;					
					document.forms["modifica_tarifa"].tipo_persona_mod.value = per;
					document.forms["modifica_tarifa"].habitacion_mod.value = hab;

				}
			if (op==3)
				{
					document.getElementById('nueva_tarifa').style.display='none';
					document.getElementById('modificar_tarifa').style.display='none';
					document.getElementById('eliminar_tarifa').style.display='block';	
					
					//Es para mandar los datos al formulario cuando se llame a la opción de eliminar

					document.forms["elimina_tarifa"].nombre_tipo_persona_eli.value = n_per;
					document.forms["elimina_tarifa"].nombre_tipo_hab_eli.value = n_hab;
					document.forms["elimina_tarifa"].nombre_edad_eli.value = n_ed;
					
				

                    document.forms["elimina_tarifa"].id_tipo_persona_eli.value = per;
                    document.forms["elimina_tarifa"].id_tipo_hab_eli.value = hab;
					document.forms["elimina_tarifa"].id_edad_eli.value = ed;
					
					
					document.forms["elimina_tarifa"].tarifa_eli.value = ta;				

				
				}
			}
//Esta función comprueba si hay algún campo vacio, en ese caso mostrará una alerta comunicando que hay que rellenarlo para seguir. Estos son para los despegables.
	/*function vacia(algo)
		{
			if (algo=="null")
			{
				alert("Tiene que seleccionar alguna opción en el Tipo de Persona.");
			}
		}
		function vacia3(algo)
		{
			if (algo=="null")
			{
				alert("Tiene que seleccionar alguna opción en el Tipo de Habitación.");
			}
		}
		function vacia4(algo)
		{
			if (algo=="null")
			{
				alert("Tiene que seleccionar alguna opción en el Grupo de Edad.");
			}
		}
		
//Esta función comprueba si algún campo está vacio, en ese caso mostrará una alerta que hay que rellenarlo para seguir con los pasos
	function vacia2(algo)
		{
			if(algo=="")
			{
				alert ("No ha puesto precio en la Tarifa.");
			}
		}
			//Terminan las funciones

*/

function vacios(tp,hab,edad,tar){

  if(tp=="null" || hab=="null" || edad=="null" || tar==""){
    alert ("Rellene todos los campos correctamente");
  }
  else{
    document.forms["nueva_tarifa"].submit();
  }
}

			
</script>
<?php
//Siempre al cargar la página realiza una conexión persistente a la base de datos que servirá para hacer cualquier consulta sql

@$db = mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
@mysql_select_db($_SESSION['conexion']['db']);

if (!$db)
	{
	   echo "Error, no se puede conectar a la base de datos";
	   exit;
	}
?>
<br><br>
<p style='margin-left:750px;margin-right:100px;'>
<table border='0' width='350'>
 <tr>
  <td align="right">
	  <table>
		  <tr>
			  <td>
					<a href='principal_gi.php?pag=gi_servicios.php'>
                        <img src='../imagenes/botones-texto/servicios.jpg' alt='Abre la página de servicios' border=0 >
                    </a>
			  </td>		  
			 <td>
				  <a href='principal_gi.php?pag=gi_grupo_edades.php'>
                    <img src='../imagenes/botones-texto/edades.jpg' alt='Abre la página de grupo edades' border=0>
                  </a>
			</td>
		</tr>
	 </table>
</td>
</tr>
</table>
</p>
<div id="caja_superior" style="width:100%;height:auto;">
    <div id="caja_superior_izquierda" style='margin-left:30px;float:left;margin-bottom:50px;margin-top:0px;width:350px; padding:0px 0px 0px 0px;'>

	<!-- EMPIEZA CREAR UNA NUEVA TARIFA -->
        <div id='nueva_tarifa' style='margin-left:0px;display:block;'>
            <table border='0' id='tabla_detalles'  cellspacing='0' cellpadding='0'>
				<form name= "nueva_tarifa" action='#' method='POST'>							
					<thead>
						<td colspan='9' align='center' style='padding-bottom:0px'>
							<div class='champi_izquierda'>&nbsp;</div>
							<div class='champi_centro' style="width:250px;">
								<div class="titulo" style="width:280px;text-align:center;">Nueva Tarifa</div>
							</div>
							<div class='champi_derecha'>&nbsp;</div>
						</td>
					</thead>
					<?php
						$tipo_persona=trim($_POST['tipo_persona']);
						$habitacion=$_POST['habitacion'];
						$edades=$_POST['edades'];
						$tarifa=$_POST['tarifa'];
						
							
						$query='INSERT INTO tarifa (tarifa, id_edad, Id_tipo_hab, id_tipo_persona)
                                VALUES ("'.$tarifa.'",'.$edades.','.$habitacion.', '.$tipo_persona.' );';
                      
                        // Si ha dado al botón aceptar, recibimos en el formulario 'tipo_persona' distinto de nulo.
                        if (isset($_POST['tipo_persona']) && ($_POST['tipo_persona']!="null"))
                        {
                            $res = mysql_query($query);
                            if($res)
								{
                                ?>
									<!--es para que salga la cabecera de los textos al hacer la inserción-->
									<script>
                                        document.getElementById('nueva_tarifa').style.display='block';
                                        document.getElementById('modificar_tarifa').style.display='none';
                                        document.getElementById('eliminar_tarifa').style.display='none';
                                    </script>
                                    <tr style='padding-top:0px;'>
                                        <td>
										<table border="0" width="100%" cellspacing='0' cellpadding='0'style="border: 1px solid #3F7BCC;"
                                               class="label_formulario">
                                          <tr align='center' class="label_formulario">
                                            <td>
                                                &nbsp;
                                            </td>
                                          </tr>
                                         <tr align='center' class="label_formulario">
                                            <td class="label_formulario" align="center">
                                               Se ha añadido la nueva tarifa a la base de datos.
                                               <br>
									       </td>
                                        </tr>
									     <tr align='center' class="label_formulario">
                                            <td align='center'>
                                                <br>
									            <a href='?pag=gi_tarifas.php'>
                                                    <img src='../imagenes/botones-texto/aceptar.jpg' border=0>
                                                </a>
									            <br><br>
                                            </td>
                                        </tr>
                                        </table>
                                        </td>
                                     </tr>
								
                                <?php
								}
                            // Si la consulta sql falla...
                            else{
 ?>
								
									<script>
                                        document.getElementById('nueva_tarifa').style.display='block';
                                        document.getElementById('modificar_tarifa').style.display='none';
                                        document.getElementById('eliminar_tarifa').style.display='none';
                                    </script>
                                    <tr style='padding-top:0px;'>
                                        <td>
										<table border="0" width="98%" cellspacing='0' cellpadding='0'
                                               style="border: 1px solid #3F7BCC;" class="label_formulario">
                                          <tr align='center' class="label_formulario">
                                            <td>

                                            </td>
                                          </tr>
                                         <tr align='center' class="label_formulario">
                                            <td class="label_formulario" align="center">
                                                No puedes añadir la nueva tarifa, inténtelo más tarde.
                                               <br>
									       </td>
                                        </tr>
									     <tr align='center' class="label_formulario">
                                            <td align='center'>
                                                <br>
									            <a href='?pag=gi_tarifas.php'>
                                                    <img src='../imagenes/botones-texto/aceptar.jpg' border=0>
                                                </a>
									            <br><br>
                                            </td>
                                        </tr>
                                     </table>
                                     </td>
                                     </tr>
								
                                <?php
        }
      }
      // Si no ha introducido aún los datos...
      else {
					?>	
					<tr style='padding-top:0px;'>
						<td>
							<table border="0" width="100%" style="border: 1px solid #3F7BCC;">
								<tr height='25'>
									<td></td><td align='left' class='label_formulario'>Tipo de Persona:</td>
									<td align='left'>
										<?php
												//aparece un option vacío por defecto para que el usuario tenga que elegir una de las tres opciones
												//conexión a la base de datos para obtener los tipos de persona que mostrará el select
											$sql = "SELECT *  FROM Tipo_Persona;";
											$result = MYSQL_QUERY($sql);
											echo '<SELECT name="tipo_persona">';
												echo '<OPTION value="null" selected></OPTION>';
													for ($i = 0; $i< MYSQL_NUM_ROWS($result); $i++)
														{
															$fila = MYSQL_FETCH_ARRAY($result);
															echo '<OPTION value="'.$fila['Id_Tipo_Persona'].'">'.$fila['Nombre_Tipo_Persona'].'</OPTION>';
														}
											echo '</SELECT>';
										?>
									</td>
								</tr>
								<tr height='25'>
									<td></td>
                                    <td align='left' class='label_formulario'>Tipo de Habitación:</td>
									<td>
										<?php
											//aparece un option vacío por defecto para que el usuario tenga que elegir una de las tres opciones
											//conexión a la base de datos para obtener los tipos de habitación que mostrará el select
											MYSQL_SELECT_DB("sam");
											$sql = "SELECT * FROM Tipo_Habitacion;";
											$result = MYSQL_QUERY($sql);
												echo '<SELECT name="habitacion">';
													echo '<OPTION value="null" selected></OPTION>';
														for ($i = 0; $i < MYSQL_NUM_ROWS($result); $i++)
															{
																$fila = MYSQL_FETCH_ARRAY($result);
																echo '<OPTION value="'.$fila['Id_Tipo_Hab'].'">'.$fila['Nombre_Tipo_Hab'].'</OPTION>';
															}
											echo '</SELECT>';
										?>
									</td>
								</tr>
								<tr height='25'>
									<td></td>
                                    <td align='left' class='label_formulario'>Edad:</td>
									<td>							
										<?php		
											//aparece un option vacío por defecto para que el usuario tenga que elegir una de las tres opciones
											//conexión a la base de datos para obtener las edades que mostrará el select
											MYSQL_SELECT_DB("sam");
											$sql = "SELECT * FROM edad;";
											$result = MYSQL_QUERY($sql);
												echo '<SELECT name="edades">';
													echo '<OPTION value="null" selected></OPTION>';
														for ($i = 0; $i < MYSQL_NUM_ROWS($result); $i++) 
															{
																$fila = MYSQL_FETCH_ARRAY($result);
																echo '<OPTION value="'.$fila['Id_Edad'].'">'.$fila['Nombre_Edad'].'</OPTION>';
															}
												echo '</SELECT>';									
										?>
									</td>
								</tr>
								<tr height='25'>
									<td>

                                    </td>
                                    <td align='left' class='label_formulario'>
                                        Tarifa:
                                    </td>
									<td>
                                        <input type='text' size='4' class='input_formulario' name='tarifa'>
                                        <label class='label_formulario'>(en euros)</label>
                                    </td>
								</tr>
								<tr>
      <!----------------------------------------------------->
									<td colspan='9' align='center' height='65'>
									   <a href='#'>
                                        <img name='aceptar' src='..\imagenes\botones-texto\aceptar.jpg' border=0
                                             onclick="vacios(tipo_persona.value,habitacion.value,edades.value,tarifa.value)"
                                             alt='Dar de alta la nueva tarifa'>
                                       </a>
                                     </td>
								</tr>
							</table>
						</td>
					</tr>
					<?
                        }
					?>
				</form>
            </table>			
        </div>
		<!--  ACABA LA CREACIÓN DE UN NUEVA TARIFA-->

		<!-- EMPIEZA MODIFICAR  LA TARIFA -->
		<div id='modificar_tarifa' style='display:none'>			
			<table border='0' id='tabla_detalles' cellspacing='0' cellpadding='0'>
				<form name= "modifica_tarifa" action='#' method='POST'>								
					<thead>
						<td colspan='9' align='center' style='padding-bottom:0px'>
							<div class='champi_izquierda'></div>
							<div class='champi_centro' style="width:200px;">
								<div class="titulo" style="width:280px;text-align:center;">Modificar Tarifa</div>
							</div>
							<div class='champi_derecha'></div>
						</td>
					</thead>
					<?php	
                        // Si ha dado al botón aceptar, recibimos en el formulario 'edad_mod' distinto de nulo.
						if(isset($_POST['edad_mod']) && $_POST['edad_mod']!="")
							{									
								//Son los input hidden q llevan los datos antiguos
								$tarifa_antigua=$_REQUEST['tarifa_mod_antiguo'];
								$habitaciones_antigua=$_REQUEST['habitacion_mod_antiguo'];
								$tipo_persona_antigua=$_REQUEST['tipo_persona_mod_antiguo'];
								$edades_antigua=$_REQUEST['edad_mod_antiguo'];
							
								$tarifa_nueva=$_REQUEST['tarifa_mod'];
								$habitaciones_nueva=$_REQUEST['habitacion_mod'];
								$tipo_persona_nueva=$_REQUEST['tipo_persona_mod'];
								$edades_nueva=$_REQUEST['edad_mod'];
								
								$query="UPDATE tarifa SET tarifa='".$tarifa_mod."',id_edad=".$edad_mod.",id_tipo_hab=".$habitacion_mod.",
                                       id_tipo_persona=".$tipo_persona_mod."
                                       WHERE  id_tipo_hab=".$habitacion_mod_antiguo." AND id_tipo_persona=".$tipo_persona_mod_antiguo."
                                              AND id_edad=".$edad_mod_antiguo.";";

                                $res =mysql_query($query);
								if($res)
									{	
                                ?>
										<!--es para que salga la cabecera de los textos al hacer la modificación-->
										<script>
                                            document.getElementById('nueva_tarifa').style.display='none';
                                            document.getElementById('modificar_tarifa').style.display='block';
                                        </script>
                                        <tr style='padding-top:0px;'>
                                        <td>
										<table border="0" width="100%" cellspacing='0' cellpadding='0'
                                               style="border: 1px solid #3F7BCC;" class="label_formulario">
										   <tr align='center' class="label_formulario">
                                            <td>
                                                
                                            </td>
                                          </tr>
										   <tr>
                                            <td class='label_formulario' align='center'>
                                                Se ha modificado la tarifa en la base de datos.
                                                <br>
										    </td>
                                          </tr>
										   <tr>
                                            <td align='center'>
                                                <br>
                                                <a href='?pag=gi_tarifas.php'>
                                                    <img src='../imagenes/botones-texto/aceptar.jpg' alt='Dar de alta nueva tarifa' border=0>
                                                </a>
                                                <br><br>
                                            </td>
                                          </tr>
                                        </table>
										</td>
										
                               <?php
									}	
                                // Si la consulta sql falla...
								else 
									{
                               ?>
                                        <script>
                                            document.getElementById('nueva_tarifa').style.display='none';
                                            document.getElementById('modificar_tarifa').style.display='block';
                                        </script>
                                        <tr style='padding-top:0px;'>
                                        <td>
										<table border="0" width="98.5%" cellspacing='0' cellpadding='0'
                                               style="border: 1px solid #3F7BCC;" class="label_formulario">
										   <tr align='center' class="label_formulario">
                                            <td>
                                                
                                            </td>
                                          </tr>
										   <tr>
                                            <td class='label_formulario' align='center'>
                                                No puedes modificar la tarifa, inténtelo más tarde.
                                                <br>
										    </td>
                                          </tr>
										   <tr>
                                            <td align='center'>
                                                <br>
                                                <a href='?pag=gi_tarifas.php'>
                                                    <img src='../imagenes/botones-texto/aceptar.jpg' border=0>
                                                </a>
                                                <br><br>
                                            </td>
                                          </tr>
                                        </table>
										</td>
                                <?php
									}
							// Si aun no se han introducido los datos...
							}else
                            {
					?>
					<tr style='padding-top:0px;'>
						<td>
							<table border="0" width="100%" style="border: 1px solid #3F7BCC;">
								<tr height='25'>
									<td></td><td align='left' class='label_formulario'>Tipo de Persona:</td>
										<td>
										<?php		
										/* aparece el option con el valor que se desea modificar para que el usuario  sepa que tipo de
                                           persona tenia seleccionado
                                        */
												$sql = "SELECT *  FROM Tipo_Persona;";
												$result = MYSQL_QUERY($sql);
													echo '<SELECT name="tipo_persona_mod" onblur="vacia(tip_per.value)">';
														echo '<OPTION value="null"></OPTION>';
															for ($i = 0; $i < MYSQL_NUM_ROWS($result); $i++)
																{
																	$fila = MYSQL_FETCH_ARRAY($result);
																	echo '<OPTION value="'.$fila['Id_Tipo_Persona'].'">'.$fila['Nombre_Tipo_Persona'].'</OPTION>';
																}
													echo '</SELECT>';
										?>
										<!--Es el tipo de persona que se quiere modificar de la tabla tarifa-->
										<input type='hidden' name='tipo_persona_mod_antiguo'>
									</td>
								</tr> 
								<tr height='25'>
									<td>
                                        
                                    </td>
                                    <td align='left' class='label_formulario'>
                                        Tipo de Habitación:
                                    </td>				   
									<td>
										<?php
										/* aparece el option  con el valor que se desea modificar para que el usuario sepa que tipo de habitación
                                           tenía seleccionado
                                        */
                                          
										//conexión a la base de datos para obtener los tipos de habitaciones que mostrará el select 
										MYSQL_SELECT_DB("sam");
										$sql = "SELECT * FROM Tipo_Habitacion;";
										$result = MYSQL_QUERY($sql);
											echo '<SELECT name="habitacion_mod" onblur="vacia(tip_hab.value)">';
												echo '<OPTION value="null"></OPTION>';
													for ($i = 0; $i < MYSQL_NUM_ROWS($result); $i++)
														{
															$fila = MYSQL_FETCH_ARRAY($result);
															echo '<OPTION value="'.$fila['Id_Tipo_Hab'].'">'.$fila['Nombre_Tipo_Hab'].'</OPTION>';
														}
											echo '</SELECT>';
										?>
										<!--Es el tipo de habitación que se quiere modificar de la tabla tarifa-->
										<input type='hidden' name='habitacion_mod_antiguo'>
									</td>
								</tr>
								<tr height='25'>
									<td>
                                        
                                    </td>
                                    <td align='left' class='label_formulario'>
                                        Edad:
                                    </td>				
									<td>
										<?php
										/* aparece la option con el valor que se desea modificar para que el usuario sepa que edad tenía seleccionado
										conexión a la base de datos para obtener las edades que mostrará el select
										*/
										MYSQL_SELECT_DB("sam");
										$sql = "SELECT * FROM edad;";
										$result = MYSQL_QUERY($sql);
											echo '<SELECT name="edad_mod" onblur="vacia(edades.value)">';
												echo '<OPTION value="null" ></OPTION>';
												for ($i = 0; $i < MYSQL_NUM_ROWS($result); $i++) 
													{
														$fila = MYSQL_FETCH_ARRAY($result);
														echo '<OPTION value="'.$fila['Id_Edad'].'">'.$fila['Nombre_Edad'].'</OPTION>';
													}
											echo '</SELECT>';							
										?>
										<!--Es la edad que se quiere modificar de la tabla tarifa-->
										<input type='hidden' name='edad_mod_antiguo'>
									</td>
								</tr>
								<tr height='25'>
									<td></td><td align='left' class='label_formulario'>Tarifa:</td>
										<td align='left'>
                                            <input type='text' size='6'  name='tarifa_mod' class='input_formulario'>
                                            <label class="label_formulario">&nbsp(en euros)</label>
										<!--Es la tarifa que se quiere modificar de la tabla tarifa-->
										<input type='hidden' size='3' name='tarifa_mod_antiguo'>
									</td>		
								</tr>							       	
								<tr height='65'>
									<td></td>
									
									<td align='center' height='50'>
									   <a href='#'>
                                            <img name='aceptar' src='../imagenes/botones-texto/aceptar.jpg' alt='Modificar el servicio'
                                                 border=0 onclick='document.forms["modifica_tarifa"].submit();'>
                                       </a>
                                   </td>
									<td align='center' height='50'>
                                        <a href='?pag=gi_tarifas.php'>
                                        <img name='cancelar' src='../imagenes/botones-texto/cancelar.jpg' border=0>
                                        </a>
                                    </td>
								</tr>
							</table>
						</td>
					</tr>		
					<?
							}
					?>
				</form>
			</table>
		</div>	
		<!-- ACABA LO DE MODIFICAR TARIFA-->		

		<!--  EMPIEZA ELIMINAR UNA TARIFA-->        
		<div id='eliminar_tarifa' style='display:none;'>
			<table border='0' id='tabla_detalles' cellspacing='0' cellpadding='0'>
				<form name="elimina_tarifa" action="#" method="POST">					
					<thead>
						<td colspan='9' align='center' style='padding-bottom:0px'>
							<div class='champi_izquierda'>
                            </div>
							<div class='champi_centro' style="width:81%;">
							   <div class="titulo" style="width:286px;text-align:center;">
                                    Eliminar Tarifa
							   </div>
							</div>
							<div class='champi_derecha'>
                            </div>
						</td>
					</thead>
					<?php 
					if(isset($_POST['id_edad_eli']) && ($_POST['id_edad_eli']!=""))
						{
							//Son los input hidden que recogen los id. 				
							$id_tipo_persona=trim($_POST['id_tipo_persona_eli']);
							$id_tipo_hab=trim($_POST['id_tipo_hab_eli']);
							$id_edad=trim($_POST['id_edad_eli']);

       						$query="delete from tarifa
                                    where id_tipo_persona=".$id_tipo_persona." and id_tipo_hab=".$id_tipo_hab." and id_edad=".$id_edad.";";
							if(mysql_query($query))
									{	
                                     ?>
         <!--es para que salga la cabecera de los textos al hacer la inserción-->
									<script>
                                        document.getElementById('nueva_tarifa').style.display='none';
                                        document.getElementById('modificar_tarifa').style.display='none';
                                        document.getElementById('eliminar_tarifa').style.display='block';
                                    </script>
									<tr style='padding-top:0px;'>
                                        <td>
										<table border="0" width="100%" cellspacing='0' cellpadding='0'
                                               style="border: 1px solid #3F7BCC;" class="label_formulario">
                                         <tr>
                                            <td>
                                                
                                            </td>
                                          </tr>
                                        <tr>
                                            <td class="label_formulario" align="center">
                                               Se ha borrado la tarifa de la base de datos.
                                               <br>
									       </td>
                                        </tr>
									    <tr>
                                            <td align='center'>
                                                <br>
									            <a href='?pag=gi_tarifas.php'>
                                                    <img src='../imagenes/botones-texto/aceptar.jpg' border=0>
                                                 </a>
									            <br><br>
                                            </td>
                                        </tr>
                                     </table>
                                     </td>
                                     </tr>

									<?
							   }
                               else
                               {
                                    ?>
                                    <script>
                                        document.getElementById('nueva_tarifa').style.display='none';
                                        document.getElementById('modificar_tarifa').style.display='none';
                                        document.getElementById('eliminar_tarifa').style.display='block';
                                    </script>
									<tr style='padding-top:0px;'>
                                        <td>
										<table border="0" width="100%" cellspacing='0' cellpadding='0'
                                               style="border: 1px solid #3F7BCC;" class="label_formulario">
                                         <tr>
                                            <td>
                                                
                                            </td>
                                          </tr>
                                        <tr>
                                            <td class="label_formulario" align="center">
                                                No se ha podido borrar la tarifa, inténtelo más tarde.
                                               <br>
									       </td>
                                        </tr>
									    <tr>
                                            <td align='center'>
                                                <br>
									            <a href='?pag=gi_tarifas.php'>
                                                    <img src='../imagenes/botones-texto/aceptar.jpg' border=0>
                                                </a>
									            <br><br>
                                            </td>
                                        </tr>
                                     </table>
                                     </td>
									</tr>
									<?php
                               }
							}
                            else{
					?>
					<tr style='padding-top:0px;'>
						<td>	 
							<table border="0" width="100%" style="border: 1px solid #3F7BCC;">
								<tr height='25'>
									<td>
                                        
                                    </td>
                                    <td align='left' class='label_formulario'>
                                        Tipo de Persona:
                                    </td>
									<td align='left'>
                                        <input type='text' class='input_formulario' name='nombre_tipo_persona_eli' id='texto_detalles'
                                               style="border:none;" readonly><input type='hidden' name='id_tipo_persona_eli'>
                                    </td>
								</tr> 								
								<tr height='25'>
									<td>
                                        
                                    </td>
                                    <td align='left' class='label_formulario'>
                                        Tipo de Habitación:
                                    </td>
									<td align='left'>
                                        <input type='text' class='input_formulario' name='nombre_tipo_hab_eli' id='texto_detalles' style="border:none;"
                                               readonly>
									   <input type='hidden' name='id_tipo_hab_eli'>
                                    </td>
								</tr>
								<tr>
									<td>
                                        
                                    </td>
                                    <td align='left' class='label_formulario'>
                                        Edades:
                                    </td>
									<td align='left'>
                                        <input type='text' class='input_formulario' size='30' maxlength='30' name='nombre_edad_eli' id='texto_detalles'
                                               style="border:none;" readonly>
    									<input type='hidden' name='id_edad_eli'>
                                    </td>
								</tr>
								
								<tr height='25'>
									<td>
                                        
                                    </td>
                                    <td align='left' class='label_formulario'>
                                        Tarifa:
                                    </td>
									<td align='left'>
                                        <input type='text' size='6' maxlength='6' id="texto_detalles" class='input_formulario' name='tarifa_eli'
                                               style="border:none;" readonly>
                                        <label class="label_formulario">
                                            (en euros)
                                        </label>
                                    </td>
								</tr>							
								<tr>
									<td>
                                        
                                    </td>
								</tr>
								<tr height='25'>
									<td colspan='9' class='label_formulario' style='text-align:center;font-size:13px;margin-left:2px;'>
                                        ¿Está seguro de que desea eliminar la Tarifa?
                                    </td>
								</tr>
								<tr>
									<td>
                                        
                                    </td>
								</tr>
								<tr height='45px'>
									<td>
                                        
                                    </td>
                                    <td align='center'>
									   <a href='#'>
                                        <img  onClick="document.forms['elimina_tarifa'].submit();" name="aceptar"
                                              src='../imagenes\botones-texto\aceptar.jpg' border=0 alt='Eliminar la tarifa seleccionada'>
                                       </a>
                                    </td>
									<td align='center'>
                                        <a href='?pag=gi_tarifas.php'>
                                            <img src='../imagenes/botones-texto/cancelar.jpg' border=0>
                                        </a>
                                    </td>
                                    <td>
                                        
                                    </td>
								</tr>
							</table>
						</td>
					</tr>
			</form>
			<?
				}	
			?>
		</table>		
	</div>		
		<!-- ACABA LA ELIMINACIÓN DE UNA TARIFA -->
</div>
		<!--  EMPIEZA EL LISTADO DE LAS TARIFAS-->    
    <div id="caja_superior_derecha_b" style='border:float:right;margin-bottom:50px;margin-top:0px;width:400px;padding:0px 0px 0px 0px;'>
	<div id='listado_grupo_edades' style='display:block;'>
		<table border="0"  align="center" cellpadding='0' cellspacing='0'>
			<thead>
				<td colspan="9" align="center" style='padding-bottom:0px'>
					<div class='champi_izquierda'></div>
					<div class='champi_centro'>
						<div class="titulo" style="width:650px;text-align:center;">Listado de Tarifas</div>
					</div>
					<div class='champi_derecha'>
                    </div>
				</td>
			</thead>
			<tr >			
				<td style='padding-top:0px;'>
					<div  class="tableContainer" align="center" style="width:710px;height:370px;padding-top:0px;background-color:#F4FCFF;">
						<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" class="scrollTable" style="width:690px;"
                               name='formulario'>
							<thead class="fixedHeader">
									<?php									
										$tipo_persona=$_POST['tipo_persona'];
										$habitacion=$_POST['room'];
										$edades=$_POST['edades'];
										$tarifa=$_POST['tarifa'];		
										
										mysql_select_db("sam");	

										//Para sacar el nombre por el id de Habitación según la tabla Tarifa
										$tip_habit="Select * from Tipo_habitacion;";
										$comp=mysql_query($tip_habit);
										@$cont=mysql_num_rows($comp);
										
										for($i=0;$i<$cont;$i++)
												{
													$otra=mysql_fetch_array($comp);
													$id_tipo_hab=$otra['Id_Tipo_Hab'];
													$tipo_hab[$id_tipo_hab]=$otra['Nombre_Tipo_Hab'];
												}
										//Para sacar el nombre por el id de persona según la tabla Tarifa
										$tip_pers="Select *from Tipo_Persona;";
										$componer=mysql_query($tip_pers);
										@$cuenta=mysql_num_rows($componer);
										
										for($i=0;$i<$cuenta;$i++)
												{
													$nuevo=mysql_fetch_array($componer);
													$id_tipo_person=$nuevo['Id_Tipo_Persona'];
													$tipo_persona[$id_tipo_person]=$nuevo['Nombre_Tipo_Persona'];
												}
										//Para sacar el nombre por el id de edad según la tabla Tarifa
										$tip_edad='Select * from edad';
										$comprobar=mysql_query($tip_edad);
										@$con=mysql_num_rows($comprobar);
										
										for($i=0;$i<$con;$i++)
												{
													$year=mysql_fetch_array($comprobar);
													$id_eda=$year['Id_Edad'];
													$tipo_edad[$id_eda]=$year['Nombre_Edad'];
												}
										//Aquí acaba para sacar el nombre a traves de los id de la tabla tarifa

											echo "<input type='hidden' name='criterio' value=".$_GET['criterio']." >";

										/* recojo el criterio por el que quiero ordenar el listado y creo la session que me controla si es
                                           ascendente o descendente.
                                        */

												if($_GET['ordenado']=='Id_Tipo_Persona')
													{
														/* esta es la operación por la que se une el id_tipo_persona de la tabla tarifa con la
                                                           de la tabla  Tipo_Persona. Para ordenar coge las letras
                                                        */
              	                                        $query="SELECT DISTINCT tarifa,tarifa.Id_Edad,tarifa.Id_Tipo_Persona,Nombre_Edad,
                                                                tarifa.Id_Tipo_Hab,Nombre_Tipo_Persona,Nombre_Tipo_Hab FROM tarifa
                                                                INNER JOIN tipo_persona ON tipo_persona.Id_Tipo_Persona = tarifa.Id_Tipo_Persona
                                                                INNER JOIN edad ON edad.Id_Edad = tarifa.Id_Edad
                                                                INNER JOIN tipo_habitacion ON tipo_habitacion.Id_Tipo_Hab = tarifa.Id_Tipo_Hab
                                                                order by Nombre_Tipo_Persona";
														
														if($_GET['criterio']==0)
															{
																$query=$query." ASC;";																
															}											
														else
															{
																 $query=$query." DESC;";																
															}
													}
												elseif($_GET['ordenado']=='Id_Tipo_Hab')
													{
														/* esta es la operación por la que se une el id_tipo_habitación de la tabla tarifa con
                                                           la de la tabla Tipo_Habitación. Para ordenar coge las letras
                                                        */
														$query="SELECT DISTINCT tarifa,tarifa.Id_Edad,tarifa.Id_Tipo_Persona,Nombre_Edad,
                                                                tarifa.Id_Tipo_Hab,Nombre_Tipo_Persona,Nombre_Tipo_Hab FROM tarifa
                                                                INNER JOIN tipo_persona ON tipo_persona.Id_Tipo_Persona = tarifa.Id_Tipo_Persona
                                                                INNER JOIN edad ON edad.Id_Edad = tarifa.Id_Edad
                                                                INNER JOIN tipo_habitacion ON tipo_habitacion.Id_Tipo_Hab = tarifa.Id_Tipo_Hab
                                                                order by Nombre_Tipo_Hab";
														if($_GET['criterio']==0)
															{																
																 $query=$query." ASC;";															
															}
														else
															{
																$query= $query." DESC;";																
															}
													}
												elseif($_GET['ordenado']=='Id_Edad')
													{
														/* esta es la operación por la que se une el id_edad de la tabla tarifa con la de la
                                                           tabla Edad. Para ordenar coge las letras
                                                        */
														$query="SELECT DISTINCT tarifa,tarifa.Id_Edad,tarifa.Id_Tipo_Persona,
                                                                Nombre_Edad,tarifa.Id_Tipo_Hab,Nombre_Tipo_Persona,Nombre_Tipo_Hab FROM tarifa
                                                                INNER JOIN tipo_persona ON tipo_persona.Id_Tipo_Persona = tarifa.Id_Tipo_Persona
                                                                INNER JOIN edad ON edad.Id_Edad = tarifa.Id_Edad
                                                                INNER JOIN tipo_habitacion ON tipo_habitacion.Id_Tipo_Hab = tarifa.Id_Tipo_Hab
                                                                order by Nombre_Edad";
														if($_GET['criterio']==0)
															{
																 $query=$query." DESC;";
															}
														else 
															{
																$query=$query." ASC;";																
															}
													}	
													//esta es la operación para sacar la tarifa
												elseif($_GET['ordenado']=='Tarifa')
													{
														$query="Select tarifa.* from tarifa order by tarifa";
														if($_GET['criterio']==0)
															{
																 $query=$query." ASC;";																
															}		
														else
															{
																$query=$query." DESC";															
															}
													}
                                                /* este else es por si no coge ningún dato para que lo haga la ordenación por la tabla
                                                   tarifa cogiendo los id
                                                */
												else
												
													{
														$query="SELECT DISTINCT tarifa,tarifa.Id_Edad,tarifa.Id_Tipo_Persona,tarifa.Id_Tipo_Hab,
                                                                Nombre_Edad,Nombre_Tipo_Persona,Nombre_Tipo_Hab FROM tarifa
                                                                INNER JOIN tipo_persona ON tipo_persona.Id_Tipo_Persona = tarifa.Id_Tipo_Persona
                                                                INNER JOIN edad ON edad.Id_Edad = tarifa.Id_Edad
                                                                INNER JOIN tipo_habitacion ON tipo_habitacion.Id_Tipo_Hab = tarifa.Id_Tipo_Hab
                                                                ";
                                                    }
									            $result=mysql_query($query);
									           

									//Esta variablete dice como va a escoger el orden.
									if($_GET['criterio']==1){
									?>									
								<tr  id='titulo_listado' style='padding-top:0px;'>
									<th align='center'>
                                        <a class='enlace' href="?pag=gi_tarifas.php&ordenado=Id_Tipo_Persona&criterio=0">
                                            Tipo de Persona
                                        </a>
                                    </th>
									<th align='center'>
                                        <a class='enlace' href="?pag=gi_tarifas.php&ordenado=Id_Tipo_Hab&criterio=0"
                                            >Tipo de Habitación
                                        </a>
                                    </th>
									<th align='center'>
                                        <a class='enlace' href="?pag=gi_tarifas.php&ordenado=Id_Edad&criterio=0">
                                            Edades
                                        </a>
                                    </th>
									<th align='center'>
                                        <a class='enlace' href="?pag=gi_tarifas.php&ordenado=Tarifa&criterio=0">
                                            Tarifa (en Euros)
                                        </a>
                                    </th>
									<th align='center'>
                                        Modificar
                                    </th>
									<th align='center'>
                                        Eliminar
                                    </th>
                                 </tr>
									
										<?PHP		
									}
									else{
										?>
								 <tr  id='titulo_listado' style='padding-top:0px;'>
									<th align='center'>
                                        <a class='enlace' href="?pag=gi_tarifas.php&ordenado=Id_Tipo_Persona&criterio=1">
                                            Tipo de Persona
                                        </a>
                                    </th>
									<th align='center'>
                                        <a class='enlace' href="?pag=gi_tarifas.php&ordenado=Id_Tipo_Hab&criterio=1">
                                            Tipo de Habitación
                                        </a>
                                    </th>
									<th align='center'>
                                        <a class='enlace' href="?pag=gi_tarifas.php&ordenado=Id_Edad&criterio=1">
                                            Edades
                                        </a>
                                    </th>
									<th align='center'>
                                        <a class='enlace' href="?pag=gi_tarifas.php&ordenado=Tarifa&criterio=1">
                                            Tarifa (en Euros)
                                        </a>
                                    </th>
									<th align='center'>
                                        Modificar
                                    </th>
									<th align='center'>
                                        Eliminar
                                    </th>
                                </tr>

									<?php
									}
									?>
									<tbody class="scrollContent" >
									<?php
										$contador=mysql_num_rows($result);
											for ($i=0;$i<$contador;$i++)
												{
                                                    //listado
													$columna = mysql_fetch_array($result);
													?>
													<tr id='texto_listados' onmouseover='resaltar_seleccion(this);'
                                                        onmouseout='desresaltar_seleccion(this);'>
                                                        <td align='center' name='tarifa'>
                                                            <? echo $columna['Nombre_Tipo_Persona'];?>
                                                        </td>
                                                        <td align='center' name='habitacion'>
                                                            <? echo $columna['Nombre_Tipo_Hab'];?>
                                                        </td>
                                                        <td align='center' name='edad'>
                                                            <?echo $columna['Nombre_Edad'];?>
                                                        </td><td align='center' name='tipo_persona'>
                                                            <? echo $columna['tarifa']; ?>
                                                        </td>
                                                        
													<!--botón modificar-->
													<td align='center'>
                                                        <a href='#'
                                                        <? echo "onClick='cambiar_opcion(2,".$columna['tarifa'].",".$columna['Id_Tipo_Hab'].",
                                                                   ".$columna['Id_Edad'].",".$columna['Id_Tipo_Persona'].");'";
                                                        ?>
                                                        >
                                                            <IMG name='modificar' alt='Modificar la Tarifa' src='../imagenes/botones/modificar.gif'
                                                                 border=0>
                                                        </a>
                                                    </td>
													<!--botón eliminar-->
													<td align='center'>
                                                        <a href='#'
                                                        	function cambiar_opcion(op,ta,hab,ed,per,n_hab,n_ed,n_per)
                                                        <? echo "onClick='cambiar_opcion(3, ".$columna['tarifa'].", \"".$columna['Id_Tipo_Hab']."\",
                                                              \"".$columna['Id_Edad']."\", \"".$columna['Id_Tipo_Persona']."\",
                                                              \"".$columna['Nombre_Tipo_Hab']."\",\"".$columna['Nombre_Edad']."\",
                                                              \"".$columna['Nombre_Tipo_Persona']."\");'";
                                                         ?>
                                                         >
                                                            <IMG name='eliminar' alt='Eliminar la Tarifa' src='../imagenes/botones/eliminar.gif'
                                                                 border=0>
                                                        </a>
                                                    </td>
													</tr>
                                                    <?php
												}										
									?>
								</tbody>
							</thead>
						</table>
					</div>
				</td>
			</tr>
			<tr>
                <td align='center'>
                 
                    <a href='#' onclick='window.open("paginas/imprimir_tarifa.php", "Tarifas",
                       "menubar=no,location=no,resizable=yes,scrollbars=yes,status=no");'>
                        <img src='../imagenes/botones-texto/imprimir.jpg' border=0 >
                    </a>
                </td>
			</tr>
			</table>
			<!--El botón de Imprimir. Te manda a otra pantalla, en el que te da la opción de imprimir la hoja de tarifas-->
	
        </div>
    </div>
</div>
<div id="caja_inferior">
</div>
<?php
mysql_close($db);
	} //Fin del IF de comprobacion de acceso a la pagina
	else	//Muestro una ventana de error de permisos de acceso a la pagina
	  echo "<div class='error'>NO TIENE PERMISO PARA ACCEDER A ESTA PÁGINA</div>";  
?>
