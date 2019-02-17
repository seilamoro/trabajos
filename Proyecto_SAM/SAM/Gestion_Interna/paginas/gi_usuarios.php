<?php
	if(isset($_SESSION['permisoUsuarios']) && $_SESSION['permisoUsuarios']==true) //Comprobando que se tiene permiso para acceder a la pagina
		{
?>
<?php
/**
* Funcion Utilizada para detectar el navegador del cliente
* Devuelve un array en el que guarda el Nombre del Navegador, la Version y la "Signature"
* $nav['app.Name'] contiene el Nombre del Navegador
* $nav['app.Ver'] contiene la Version del Navegador
* $nav['app.Sig'] contiene la "Signature" del Navegador
*/
function detectBrowser()
	{
	$browsers = array("msie", "firefox"); //- Add here
	$names = array ("msie" => "Explorer", "firefox" => "Firefox"); //- The same
	$nav = "Unknown";
	$sig = strToLower ($_SERVER['HTTP_USER_AGENT']);
	foreach ($browsers as $b)
		{
    	if ($pos = strpos ($sig, $b))
			{
	        $nav = $names[$b];
    	    break;
       		}
   		}
	if ($nav == "Unknown")
		return array ("app.Name" => $nav, "app.Ver" => "?", "app.Sig" => $sig);
   	$ver = "";
	for ( ; $pos <= strlen ($sig); $pos ++)
		{
       	if ((is_numeric($sig[$pos])) || ($sig[$pos]=="."))
           $ver .= $sig[$pos];
    	else
			if ($ver)
				break;
   		}
	return array("app.Name" => $nav, "app.Ver" => $ver, "app.Sig" => $sig);
	}

$nav = detectBrowser();
//echo "Your browser is: " . $nav['app.Name'] . " " . $nav['app.Ver'] . "<br />Your signature was: " . $nav['app.Sig'];

?>
<link rel="stylesheet" type="text/css" href="css/estilos_tablas.css" />
<style>
	#listadoPermisos
		{
		overflow-y:auto;
		height:80px;
		width:230px;
		/*Barra de desplazamiento
		scrollbar-face-color: #5A90CE;
		scrollbar-shadow-color: #DEEBF5;
		scrollbar-highlight-color: #DEEBF5;
		scrollbar-3dlight-color: #5F8ABD;
		scrollbar-darkshadow-color: #5F8ABD;
		scrollbar-track-color: #F5F5F5;
		scrollbar-arrow-color: #FFFFFF;*/
		}
</style>
<script language="javascript">

	/**
	* Funcion utilizada para cambiar el color(de texto y de fondo) y el puntero de una fila del listado de usuarios
	*/
	function resaltar_seleccion(tr)
		{
	  	tr.style.backgroundColor = '#569CD7';
		tr.style.color = '#F4FCFF';
	  	tr.style.cursor = 'pointer';
		}
	
	/**
	* Funcion utilizada para poner el formato original a una fila del listado de usuarios
	*/	
	function desresaltar_seleccion(tr)
		{
	  	tr.style.backgroundColor = '#F4FCFF';
		tr.style.color = '#3F7BCC';
		}
	
	/**
	* Funcion utilizada para cambiar el color(de texto y de fondo) y el puntero de un INPUT del listado de usuarios
	*/
	function resaltar_input(elInput)
		{
	  	document.formularioListado[elInput].style.backgroundColor = '#569CD7';
		document.formularioListado[elInput].style.color = '#F4FCFF';
	  	document.formularioListado[elInput].style.cursor = 'pointer';
		}
	
	/**
	* Funcion utilizada para poner el formato original a un INPUT del listado de usuarios
	*/
	function desresaltar_input(elInput)
		{
		document.formularioListado[elInput].style.backgroundColor = '#F4FCFF';
		document.formularioListado[elInput].style.color = '#3F7BCC';
		}	
	
</script>

<div id="caja_superior">
	<div id="caja_superior_izquierda" style='float:left;margin-left:<?php if($nav['app.Name']=="Firefox"){echo "130";}else{echo "80";}?>px;margin-top:20px;'>
	<?php
	$inicioBorde='<tr style="padding-top:0px;" id="tabla_detalles"><td style="border:1px solid #3F7BCC;"><div style="height:390;"><table align="center" cellspacing="0" cellpadding="0">';
	$finBorde='</table></div></td></tr>';

/*	$inicioBorde='<tr style="padding-top:0px;" id="tabla_detalles"><td style="border:1px solid #3F7BCC;"><div style="height:390;"><table align="center" cellspacing="0" cellpadding="0">';
	$finBorde='</table></div></td></tr>';*/
	
	@ $db = mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
		if (!$db)
		    {
			echo "Error : No se ha podido conectar a la base de datos";
			exit;
			}
		mysql_select_db($_SESSION['conexion']['db']);
		if(isset($_REQUEST['nuevo'])) // INSERTAR / EDITAR USUARIOS
			{
			if(!empty($_POST['check_online']) && !empty($_POST['check_alberguistas']) && !empty($_POST['check_grupos']) && !empty($_POST['check_peregrino']) && !empty($_POST['check_facturacion']) && !empty($_POST['check_tarifas']) && !empty($_POST['check_reservas']) && !empty($_POST['check_estadisticas'])	&& !empty($_POST['check_reaj']) && !empty($_POST['check_int_reaj']) && !empty($_POST['check_incidencias']) && !empty($_POST['check_usuarios']) && !empty($_POST['check_informe_policial']) && !empty($_POST['check_gest_habitaciones']) && !empty($_POST['check_int_habitaciones']) && !empty($_POST['check_gest_taquillas']) && !empty($_POST['check_int_taquillas'])) //Si han sido marcados todos los los checkbox de los permisos, entonces se trata de un administrador, sino se tratara de un usuario
				{
// NUEVO ADMINISTRADOR
				if($_REQUEST['nuevo']!="act") //Compruebo que no se trata de una actualizacion
					{
  					//Insercion de un nuevo administrador
					mysql_query("insert into usuario (Usuario,Password,OnLine,Reservas,Peregrino,Alberguista,Grupos,Facturacion,Incidencias,TarifasEdades,Estadisticas,InfPolicial,REAJ,InternaREAJ,Usuarios,Taquillas,InternaTaquillas,Habitaciones,InternaHabitaciones) values('".$_POST['usuario']."',PASSWORD('".$_POST['password']."'),'Y','Y','Y','Y','Y','Y','Y','Y','Y','Y','Y','Y','Y','Y','Y','Y','Y');");
					}
				else
					{
					//Editar un usuario(convirtiendolo en administrador) o un administrador
					if(isset($_POST['password'])) //Comprobacion de si se ha cambiado el password
						$poner="Password = PASSWORD('".$_POST['password']."'),	";
					else
						$poner="";
					mysql_query("UPDATE usuario SET ".$poner." OnLine = 'Y', Reservas = 'Y', Peregrino = 'Y', Alberguista = 'Y', Grupos = 'Y', Facturacion = 'Y', Incidencias = 'Y', TarifasEdades = 'Y', Estadisticas = 'Y', InfPolicial = 'Y', REAJ = 'Y', InternaREAJ = 'Y', Usuarios = 'Y', Taquillas = 'Y', InternaTaquillas = 'Y', Habitaciones = 'Y', InternaHabitaciones = 'Y' WHERE Usuario = '".$_POST['usuario']."';");
					}
				}
			else
				{
//NUEVO USUARIO	
				if($_REQUEST['nuevo']!="act") //Compruebo que no se trata de una actualizacion
					{
					//Insercion de un nuevo usuario		  
					$nuevo="insert into usuario (Usuario,Password,OnLine,Reservas,Peregrino,Alberguista,Grupos,Facturacion,Incidencias,TarifasEdades,Estadisticas,InfPolicial,REAJ,InternaREAJ,Usuarios,Taquillas,InternaTaquillas,Habitaciones,InternaHabitaciones) values('".$_POST['usuario']."',PASSWORD('".$_POST['password']."')";
					//Ahora compruebo todos los permisos, si se ha marcado el checkbox correspondiente a un permiso entonces le asigno el valor 'Y', sino le asigno 'N'
					if(isset($_POST['check_online']))
							$nuevo=$nuevo.",'Y'";
						else
							$nuevo=$nuevo.",'N'";
				
					if(isset($_POST['check_reservas']))
							$nuevo=$nuevo.",'Y'";
						else
							$nuevo=$nuevo.",'N'";
				
					if(isset($_POST['check_peregrino']))
							$nuevo=$nuevo.",'Y'";
						else
							$nuevo=$nuevo.",'N'";				

					if(isset($_POST['check_alberguistas']))
							$nuevo=$nuevo.",'Y'";
						else
							$nuevo=$nuevo.",'N'";

					if(isset($_POST['check_grupos']))
							$nuevo=$nuevo.",'Y'";
						else
							$nuevo=$nuevo.",'N'";

					if(isset($_POST['check_facturacion']))
							$nuevo=$nuevo.",'Y'";
						else
							$nuevo=$nuevo.",'N'";
				
					if(isset($_POST['check_incidencias']))
							$nuevo=$nuevo.",'Y'";
						else
							$nuevo=$nuevo.",'N'";
					
					if(isset($_POST['check_tarifas']))
							$nuevo=$nuevo.",'Y'";
						else
							$nuevo=$nuevo.",'N'";
				
					if(isset($_POST['check_estadisticas']))
							$nuevo=$nuevo.",'Y'";
						else
							$nuevo=$nuevo.",'N'";
				
					if(isset($_POST['check_informe_policial']))
							$nuevo=$nuevo.",'Y'";
						else
							$nuevo=$nuevo.",'N'";
				
					if(isset($_POST['check_reaj']))
							$nuevo=$nuevo.",'Y'";
						else
							$nuevo=$nuevo.",'N'";
					
					if(isset($_POST['check_int_reaj']))
							$nuevo=$nuevo.",'Y'";
						else
							$nuevo=$nuevo.",'N'";
				
					if(isset($_POST['check_usuarios']))
							$nuevo=$nuevo.",'Y'";
						else
							$nuevo=$nuevo.",'N'";

					if(isset($_POST['check_gest_taquillas']))
							$nuevo=$nuevo.",'Y'";
						else
							$nuevo=$nuevo.",'N'";				

					if(isset($_POST['check_int_taquillas']))
							$nuevo=$nuevo.",'Y'";
						else
							$nuevo=$nuevo.",'N'";
/************************************************************************************************************

	Para asignar el permiso de Gestion Principal de Habitaciones en funcion de estar marcado o desmarcado 
	el checkbox de Gest. Habitaciones, descomentar estas lineas y comentar la linea:
		$nuevo=$nuevo.",'Y'"

*************************************************************************************************************/	
					/*
					if(isset($_POST['check_gest_habitaciones']))
							$nuevo=$nuevo.",'Y'";
						else
							$nuevo=$nuevo.",'N'";
					*/
					$nuevo=$nuevo.",'Y'";
/***********************************************************************************************************/					
					if(isset($_POST['check_int_habitaciones']))
							$nuevo=$nuevo.",'Y');";
						else
							$nuevo=$nuevo.",'N');";
					mysql_query($nuevo);
					}
				else
					{
					//Editar un usuario
					if(isset($_POST['password'])) //Comprobacion de si se ha cambiado el password
						$poner="Password = PASSWORD('".$_POST['password']."'),";
					else
						$poner="";
					$actualiza="update usuario set ".$poner;
					//Ahora compruebo todos los permisos, si se ha marcado el checkbox correspondiente a un permiso entonces le asigno el valor 'Y', sino le asigno 'N'
					if(isset($_POST['check_online']))
							$actualiza=$actualiza."OnLine='Y'";
						else
							$actualiza=$actualiza."OnLine='N'";
				
					if(isset($_POST['check_reservas']))
							$actualiza=$actualiza.",Reservas='Y'";
						else
							$actualiza=$actualiza.",Reservas='N'";
				
					if(isset($_POST['check_peregrino']))
							$actualiza=$actualiza.",Peregrino='Y'";
						else
							$actualiza=$actualiza.",Peregrino='N'";				

					if(isset($_POST['check_alberguistas']))
							$actualiza=$actualiza.",Alberguista='Y'";
						else
							$actualiza=$actualiza.",Alberguista='N'";

					if(isset($_POST['check_grupos']))
							$actualiza=$actualiza.",Grupos='Y'";
						else
							$actualiza=$actualiza.",Grupos='N'";

					if(isset($_POST['check_facturacion']))
							$actualiza=$actualiza.",Facturacion='Y'";
						else
							$actualiza=$actualiza.",Facturacion='N'";
				
					if(isset($_POST['check_incidencias']))
							$actualiza=$actualiza.",Incidencias='Y'";
						else
							$actualiza=$actualiza.",Incidencias='N'";
					
					if(isset($_POST['check_tarifas']))
							$actualiza=$actualiza.",TarifasEdades='Y'";
						else
							$actualiza=$actualiza.",TarifasEdades='N'";
				
					if(isset($_POST['check_estadisticas']))
							$actualiza=$actualiza.",Estadisticas='Y'";
						else
							$actualiza=$actualiza.",Estadisticas='N'";
				
					if(isset($_POST['check_informe_policial']))
							$actualiza=$actualiza.",InfPolicial='Y'";
						else
							$actualiza=$actualiza.",InfPolicial='N'";
				
					if(isset($_POST['check_reaj']))
							$actualiza=$actualiza.",REAJ='Y'";
						else
							$actualiza=$actualiza.",REAJ='N'";
					
					if(isset($_POST['check_int_reaj']))
							$actualiza=$actualiza.",InternaREAJ='Y'";
						else
							$actualiza=$actualiza.",InternaREAJ='N'";
				
					if(isset($_POST['check_usuarios']))
							$actualiza=$actualiza.",Usuarios='Y'";
						else
							$actualiza=$actualiza.",Usuarios='N'";

					if(isset($_POST['check_gest_taquillas']))
							$actualiza=$actualiza.",Taquillas='Y'";
						else
							$actualiza=$actualiza.",Taquillas='N'";				

					if(isset($_POST['check_int_taquillas']))
							$actualiza=$actualiza.",InternaTaquillas='Y'";
						else
							$actualiza=$actualiza.",InternaTaquillas='N'";
/**************************************************************************************************************
	
	Para asignar el permiso de Gestion Principal de Habitaciones en funcion de estar marcado o desmarcado 
	el checkbox de Gest. Habitaciones, descomentar estas lineas y comentar la linea:
		$actualiza=$actualiza.",Habitaciones='Y'"

***************************************************************************************************************/					
					/*
					if(isset($_POST['check_gest_habitaciones']))
							$actualiza=$actualiza.",Habitaciones='Y'";
						else
							$actualiza=$actualiza.",Habitaciones='N'";
					*/
					$actualiza=$actualiza.",Habitaciones='Y'";
/**************************************************************************************************************/					

					if(isset($_POST['check_int_habitaciones']))
							$actualiza=$actualiza.",InternaHabitaciones='Y' WHERE Usuario='".$_POST['usuario']."';";
						else
							$actualiza=$actualiza.",InternaHabitaciones='N' WHERE Usuario='".$_POST['usuario']."';";
					mysql_query($actualiza); 
					}							
				}
			}
		if(!isset($_REQUEST['cuad1'])) //VENTANA NUEVO USUARIO
			{
			echo '
				<script>
					//Funcion utilizada para comprobar que han sido rellenados todos los campos del formulario de alta de usuario, y que las dos contraseñas son iguales
					function comprobar(formulario)
						{
						if(formulario.usuario.value!="" && formulario.usuario.value!=null && formulario.password.value!="" && formulario.password.value!=null && formulario.password2.value!="" && formulario.password2.value!=null)
							{
							if(formulario.password.value==formulario.password2.value)
								formulario.submit();
							else
								alert("Las contraseñas no coinciden");
							}
						else
							alert("No has rellenado todos los campos");
						}
				</script>
				<form name="altaUsuario" method="POST" action="?pag=gi_usuarios.php&cuad1=2&orden=';if(isset($_REQUEST['orden'])) echo $_REQUEST['orden']; else echo 'pd'; echo '">
					<table cellspacing="0" cellpadding="0" height="400" border=0 style="border:solid 0px;">
						<thead>
							<td colspan="5" height=18 style="padding:0px 0px 0px 0px;">
								<div class="champi_izquierda">&nbsp;</div>
								<div class="champi_centro" style="width: 350px;">
									<div class="titulo" style="text-align: center;">Nuevo Usuario</div>
								</div>
								<div class="champi_derecha">&nbsp;</div>
							</td>
						</thead>'.$inicioBorde;

						if(isset($_POST['existe']) && !isset($_REQUEST['actua'])) //Comprobacion de si ya existe el usuario y que la accion a realizar no sea una actualizacion
							echo '<tr><td colspan="2" align="center"><label class="label_formulario" style="color:red;">ERROR : Ya existe el usuario <b><i>"'.$_POST['existe'].'"</i></b></label></td></tr>';
					echo '<tr><td height="50"><br><label class="label_formulario">Usuario :</label></td><td><br>&nbsp;&nbsp;&nbsp;<input class="input_formulario" type="text" name="usuario" maxLength="16"/></td></tr>
						<tr><td height="50"><label class="label_formulario">Contraseña :</label></td><td>&nbsp;&nbsp;&nbsp;<input class="input_formulario" type="password" name="password" maxLength="20"/></td></tr>
						<tr><td height="50"><label class="label_formulario">Repita Contraseña :</label></td><td>&nbsp;&nbsp;&nbsp;<input class="input_formulario" type="password" name="password2" maxLength="20"/></td></tr>
						<tr><td height="50"><label class="label_formulario">Perfil :</label></td><td>&nbsp;&nbsp;&nbsp;
							<select class="select_formulario" name="perfil">
								<option value="u">Usuario</option>
								<option value="a">Administrador</option>
							</select></td></tr>
						<tr><td colspan="2" align="center" height="50"><br><br><img style="border:none;" src="../imagenes/botones-texto/continuar.jpg" alt="Continuar" title="Continuar" onclick="comprobar(altaUsuario)" id="boton"/></td></tr>'.$finBorde.'
					</table>
					<script>document.altaUsuario.usuario.focus();</script>
					<input type="hidden" name="checkboxPassword" value="on" />
				</form>';
				}
			else
				{
				switch($_REQUEST['cuad1'])
					{
					case 2: //VENTANA DAR PERMISOS
						if(!isset($_REQUEST['actua'])) //Si no es una actualizacion
							{
							/* Comprobacion de que el usuario no exista ya en la BD */
							$existeUsuario=mysql_query("select * from usuario where usuario= '".$_POST['usuario']."'");
							if(mysql_num_rows($existeUsuario)>0)
								{
								echo "<form name='fError' action='?pag=gi_usuarios.php&orden=".$_REQUEST['orden']."' method='post'><input type='hidden' name='existe' value='".$_POST['usuario']."'/></form><script>document.fError.submit();</script>";
								exit;
								}
							}
						echo '<script>
										//Funcion utilizada para comprobar que al menos se le da un permiso al usuario
										function comprobarPermisos()
											{
											/**************************************************************************
											 Si se añade el checkbox de Permiso para Gest. Habitaciones, hay que añadir esta linea al if:
												|| document.permisos.check_gest_habitaciones.checked
											***************************************************************************/
											if(document.permisos.check_online.checked || document.permisos.check_alberguistas.checked || document.permisos.check_grupos.checked || document.permisos.check_peregrino.checked || document.permisos.check_facturacion.checked || document.permisos.check_tarifas.checked || document.permisos.check_reservas.checked || document.permisos.check_estadisticas.checked || document.permisos.check_reaj.checked ||  document.permisos.check_int_reaj.checked || document.permisos.check_incidencias.checked || document.permisos.check_usuarios.checked || document.permisos.check_informe_policial.checked || document.permisos.check_int_habitaciones.checked || document.permisos.check_gest_taquillas.checked || document.permisos.check_int_taquillas.checked)
												document.permisos.submit();
											else
												alert("No has asignado permisos al usuario");
											
											}
								</script>
								<form method="POST" name="permisos" action="?pag=gi_usuarios.php&nuevo=true&orden='.$_REQUEST['orden'].'">
									<input type="hidden" name="usuario" value="'.$_POST['usuario'].'" />
									<input type="hidden" name="user" value="'.$_POST['usuario'].'" />';
						if($_POST['checkboxPassword']=='on')
							echo '<input type="hidden" name="password" value="'.$_POST['password'].'" />';
							echo '<input type="hidden" name="p" value="'.$_POST['perfil'].'"/>
									<table style="width: 435px" cellpadding="0" cellspacing="0">
										<tr style="padding:0px 0px 0px 0px;">
											<td colspan="3">
												<div class="champi_izquierda">&nbsp;</div>
												<div class="champi_centro">
													<div class="titulo" style="width: 375px; text-align: center;">Permisos del Usuario</div>
												</div>
												<div class="champi_derecha">&nbsp;</div>
											</td>
										</tr>
										'.$inicioBorde;
										
if($nav['app.Name']=="Firefox")
	{
/****************************************************************************************
						TABLA DE PERMISOS PARA MOZILLA FIREFOX
****************************************************************************************/

/********************* Versión con checkboxes de Permisos de Gest. Habitaciones y Gest. Int. Habitaciones ******************************************

	echo '<tr>
											<td width="231px"><input type="checkbox" class="check_formulario" name="check_online">&nbsp;&nbsp;&nbsp;<label class="label_formulario">Reservas On-Line</label></td>
											<td width="204px"><input type="checkbox" class="check_formulario" name="check_alberguistas">&nbsp;&nbsp;&nbsp;<label class="label_formulario">Alberguista</label></td>
										</tr>
										<tr>
											<td width="231px"><input type="checkbox" class="check_formulario" name="check_reservas">&nbsp;&nbsp;&nbsp;<label class="label_formulario">Reservas</label></td>										
											<td width="204px"><input type="checkbox" class="check_formulario" name="check_peregrino">&nbsp;&nbsp;&nbsp;<label class="label_formulario">Peregrino</label></td>
										</tr>
										<tr>
											<td width="231px"><input type="checkbox" class="check_formulario" name="check_facturacion">&nbsp;&nbsp;&nbsp;<label class="label_formulario">Facturación</label></td>
											<td width="204px"><input type="checkbox" class="check_formulario" name="check_grupos">&nbsp;&nbsp;&nbsp;<label class="label_formulario">Grupos</label></td>
										</tr>
										<tr>
											<td width="231px"><input type="checkbox" class="check_formulario" name="check_tarifas">&nbsp;&nbsp;&nbsp;<label class="label_formulario">Tarifas / Edades</label></td>											
											<td width="204px"><input type="checkbox" class="check_formulario" name="check_estadisticas">&nbsp;&nbsp;&nbsp;<label class="label_formulario">Estadisticas</label></td>
										</tr>
										
										<tr>
											<td width="231px"><input type="checkbox" class="check_formulario" name="check_incidencias">&nbsp;&nbsp;&nbsp;<label class="label_formulario">Incidencias</label></td>
											<td width="204px"><input type="checkbox" class="check_formulario" name="check_informe_policial">&nbsp;&nbsp;&nbsp;<label class="label_formulario">Informe Policial</label></td>
										</tr>
										<tr>
											<td width="231px" valign="top"><input type="checkbox" class="check_formulario" name="check_usuarios">&nbsp;&nbsp;&nbsp;<label class="label_formulario">Usuarios</label></td>
											<td valign="top" width="204px">
												<input type="checkbox" class="check_formulario" name="check_taquillas" onclick="if(this.checked==true){divHab_REAJ.style.marginTop=-38;divTaquillas.style.visibility=\'visible\';divTaquillas.style.display=\'block\';}else{divHab_REAJ.style.marginTop=0;divTaquillas.style.visibility=\'hidden\';divTaquillas.style.display=\'none\';check_gest_taquillas.checked=false;check_int_taquillas.checked=false;}">&nbsp;&nbsp;&nbsp;<label class="label_formulario" >Taquillas</label>
												<div id="divTaquillas"'; if($_POST['perfil']!="a") echo ' style="display:none;visibility:hidden;"'; echo '>
													&nbsp;&nbsp;<input type="checkbox" class="check_formulario" name="check_gest_taquillas">
													&nbsp;&nbsp;<label class="label_formulario">Gest. Taquillas</label><br>
													&nbsp;&nbsp;<input type="checkbox" class="check_formulario" name="check_int_taquillas">
													&nbsp;&nbsp;<label class="label_formulario">Gest. Int. Taquillas</label>
												</div>
											</td>
										</tr>
										<tr>											
											<td valign="top" width="231px">
												<div id="divHab_REAJ">
													<input type="checkbox" class="check_formulario" name="check_habitaciones" onclick="if(this.checked==true){divHabitaciones.style.visibility=\'visible\';divHabitaciones.style.display=\'block\';}else{divHabitaciones.style.visibility=\'hidden\';divHabitaciones.style.display=\'none\';check_gest_habitaciones.checked=false;check_int_habitaciones.checked=false;}">&nbsp;&nbsp;&nbsp;<label class="label_formulario">Habitaciones</label>
													<div id="divHabitaciones"'; if($_POST['perfil']!="a") echo ' style="display:none;visibility:hidden;"'; echo '>
														&nbsp;&nbsp;<input type="checkbox" class="check_formulario" name="check_gest_habitaciones">
														&nbsp;&nbsp;<label class="label_formulario">Gest. Habitaciones</label><br>
														&nbsp;&nbsp;<input type="checkbox" class="check_formulario" name="check_int_habitaciones">
														&nbsp;&nbsp;<label class="label_formulario">Gest. Int. Habitaciones</label>
													</div>
													<div style="margin-top:4px;">
														<input type="checkbox" class="check_formulario" name="check_general_reaj" onclick="if(this.checked==true){divREAJ.style.visibility=\'visible\';divREAJ.style.display=\'block\';}else{divREAJ.style.visibility=\'hidden\';divREAJ.style.display=\'none\';check_reaj.checked=false;check_int_reaj.checked=false;}">&nbsp;&nbsp;&nbsp;<label class="label_formulario">REAJ</label>
														<div id="divREAJ"'; if($_POST['perfil']!="a") echo ' style="display:none;visibility:hidden;"'; echo '>
															&nbsp;&nbsp;<input type="checkbox" class="check_formulario" name="check_reaj">
															&nbsp;&nbsp;<label class="label_formulario">Venta REAJ</label><br>
															&nbsp;&nbsp;<input type="checkbox" class="check_formulario" name="check_int_reaj">
															&nbsp;&nbsp;<label class="label_formulario">Gest. Int. REAJ</label>
														</div>
													</div>
												</div>
											</td>											
										</tr>
										<tr><td colspan="3" align="center"><img style="border:none;" src="../imagenes/botones-texto/aceptar.jpg" alt="Aceptar" title="Aceptar" onclick="comprobarPermisos();" id="boton"/>&nbsp;&nbsp;&nbsp;<img style="border:none;" src="../imagenes/botones-texto/cancelar.jpg" alt="Cancelar" title="Cancelar" onclick="';
									if(isset($_REQUEST['actua'])&& $_REQUEST['actua']=="si") //Si es una actualizacion, cambio el action del formulario (para que al pulsar en Cancelar en vez de volver a la Ventana de Nuevo Usuario, vuelva a la de Modificar Usuario) y envio el formulario al pulsar en Cancelar
											{
											echo 'document.permisos.action=\'?pag=gi_usuarios.php&cuad1=4&orden='.$_REQUEST['orden'].'\'; document.permisos.submit();" id="boton"/></td></tr>
									'.$finBorde.'</table>';
											//En este script cambio el action del formulario, para que al enviarlo y entrar en la zona de inserccion de usuarios en la BD, sepa que se trata de una actulizacion
											echo '<script>
													document.permisos.action=\'?pag=gi_usuarios.php&nuevo=act&orden='.$_REQUEST['orden'].'\';
												</script>';
																					
												
											//Selecciono los permisos del usuario de la BD y marco los checkbox de los permisos que ya tiene asignados
											$result=mysql_query("select OnLine, Reservas, Peregrino, Alberguista, Grupos, Facturacion, Incidencias, TarifasEdades, Estadisticas, InfPolicial, REAJ, InternaREAJ, Usuarios, Taquillas, InternaTaquillas, Habitaciones, InternaHabitaciones from usuario where usuario='".$_POST['user']."'");
											if(mysql_num_rows($result)>0)
												{
												$fila=mysql_fetch_array($result);
												echo "<script language='javascript'>";
												if($fila['OnLine']=="Y")echo "document.permisos.check_online.checked=true;";
												if($fila['Reservas']=="Y")echo "document.permisos.check_reservas.checked=true;";
												if($fila['Peregrino']=="Y")echo "document.permisos.check_peregrino.checked=true;";
												if($fila['Alberguista']=="Y")echo "document.permisos.check_alberguistas.checked=true;";
												if($fila['Grupos']=="Y")echo "document.permisos.check_grupos.checked=true;";
												if($fila['Facturacion']=="Y")echo "document.permisos.check_facturacion.checked=true;";
												if($fila['Incidencias']=="Y")echo "document.permisos.check_incidencias.checked=true;";
												if($fila['TarifasEdades']=="Y")echo "document.permisos.check_tarifas.checked=true;";
												if($fila['Estadisticas']=="Y")echo "document.permisos.check_estadisticas.checked=true;";
												if($fila['InfPolicial']=="Y")echo "document.permisos.check_informe_policial.checked=true;";
												if($fila['REAJ']=="Y")echo "divREAJ.style.display='block';divREAJ.style.visibility='visible';document.permisos.check_general_reaj.checked=true;document.permisos.check_reaj.checked=true;";
												if($fila['InternaREAJ']=="Y"){echo "divREAJ.style.display='block';divREAJ.style.visibility='visible';document.permisos.check_general_reaj.checked=true;document.permisos.check_int_reaj.checked=true;";}
												if($fila['Usuarios']=="Y")echo "document.permisos.check_usuarios.checked=true;";
												if($fila['Taquillas']=="Y"){echo "divHab_REAJ.style.marginTop=-38;divTaquillas.style.display='block';divTaquillas.style.visibility='visible';document.permisos.check_taquillas.checked=true;document.permisos.check_gest_taquillas.checked=true;";}
												if($fila['InternaTaquillas']=="Y"){echo "divHab_REAJ.style.marginTop=-38;divTaquillas.style.display='block';divTaquillas.style.visibility='visible';document.permisos.check_taquillas.checked=true;document.permisos.check_int_taquillas.checked=true;";}
												if($fila['Habitaciones']=="Y"){echo "divHabitaciones.style.display='block';divHabitaciones.style.visibility='visible';document.permisos.check_habitaciones.checked=true;document.permisos.check_gest_habitaciones.checked=true;";}
												if($fila['InternaHabitaciones']=="Y"){echo "divHabitaciones.style.display='block';divHabitaciones.style.visibility='visible';document.permisos.check_habitaciones.checked=true;document.permisos.check_int_habitaciones.checked=true;";}
												echo "</script>";
												}																				
											}
									else
										echo 'location.href=\'?pag=gi_usuarios.php&orden='.$_REQUEST['orden'].'\'" id="boton"/></td></tr>
									'.$finBorde.'
								</table>';
								if($_POST['perfil']=="a") //Si en la ventana de Nuevo Usuario o en la de Modificar Usuario, elegimos como perfil Administrador, entonces marco todos los checkbox de permisos
									echo '<script>
												divHab_REAJ.style.marginTop=-38;
												divTaquillas.style.display="block";
												divHabitaciones.style.display="block";
												divREAJ.style.display="block";
												document.permisos.check_online.checked=true;
												document.permisos.check_alberguistas.checked=true;
												document.permisos.check_grupos.checked=true;
												document.permisos.check_peregrino.checked=true;
												document.permisos.check_facturacion.checked=true;
												document.permisos.check_tarifas.checked=true;
												document.permisos.check_reservas.checked=true;
												document.permisos.check_estadisticas.checked=true;												
												document.permisos.check_incidencias.checked=true;
												document.permisos.check_usuarios.checked=true;
												document.permisos.check_informe_policial.checked=true;
												document.permisos.check_general_reaj.checked=true;
													document.permisos.check_reaj.checked=true;
													document.permisos.check_int_reaj.checked=true;
												document.permisos.check_habitaciones.checked=true;
													document.permisos.check_gest_habitaciones.checked=true;
													document.permisos.check_int_habitaciones.checked=true;
												document.permisos.check_taquillas.checked=true;
													document.permisos.check_gest_taquillas.checked=true;
													document.permisos.check_int_taquillas.checked=true;
										</script>';
										
****************************** Fin de la Version con los Permisos de Gest. Habitaciones y Gest. Int. Habitaciones ********************************/

/***************************** Version que solo tiene Permiso de Gest. Int. Habitaciones *********************************************************/

	echo '<tr>
											<td width="231px"><input type="checkbox" class="check_formulario" name="check_online">&nbsp;&nbsp;&nbsp;<label class="label_formulario">Reservas On-Line</label></td>
											<td width="204px"><input type="checkbox" class="check_formulario" name="check_alberguistas">&nbsp;&nbsp;&nbsp;<label class="label_formulario">Alberguista</label></td>
										</tr>
										<tr>
											<td width="231px"><input type="checkbox" class="check_formulario" name="check_reservas">&nbsp;&nbsp;&nbsp;<label class="label_formulario">Reservas</label></td>										
											<td width="204px"><input type="checkbox" class="check_formulario" name="check_peregrino">&nbsp;&nbsp;&nbsp;<label class="label_formulario">Peregrino</label></td>
										</tr>
										<tr>
											<td width="231px"><input type="checkbox" class="check_formulario" name="check_facturacion">&nbsp;&nbsp;&nbsp;<label class="label_formulario">Facturación</label></td>
											<td width="204px"><input type="checkbox" class="check_formulario" name="check_grupos">&nbsp;&nbsp;&nbsp;<label class="label_formulario">Grupos</label></td>
										</tr>
										<tr>
											<td width="231px"><input type="checkbox" class="check_formulario" name="check_tarifas">&nbsp;&nbsp;&nbsp;<label class="label_formulario">Tarifas / Edades</label></td>											
											<td width="204px"><input type="checkbox" class="check_formulario" name="check_estadisticas">&nbsp;&nbsp;&nbsp;<label class="label_formulario">Estadisticas</label></td>
										</tr>
										
										<tr>
											<td width="231px"><input type="checkbox" class="check_formulario" name="check_incidencias">&nbsp;&nbsp;&nbsp;<label class="label_formulario">Incidencias</label></td>
											<td width="204px"><input type="checkbox" class="check_formulario" name="check_informe_policial">&nbsp;&nbsp;&nbsp;<label class="label_formulario">Informe Policial</label></td>
										</tr>
										<tr>
											<td width="231px" valign="top"><input type="checkbox" class="check_formulario" name="check_usuarios">&nbsp;&nbsp;&nbsp;<label class="label_formulario">Usuarios</label></td>
											<td valign="top" width="204px">
												<input type="checkbox" class="check_formulario" name="check_taquillas" onclick="if(this.checked==true){divHab_REAJ.style.marginTop=-38;divTaquillas.style.visibility=\'visible\';divTaquillas.style.display=\'block\';}else{divHab_REAJ.style.marginTop=0;divTaquillas.style.visibility=\'hidden\';divTaquillas.style.display=\'none\';check_gest_taquillas.checked=false;check_int_taquillas.checked=false;}">&nbsp;&nbsp;&nbsp;<label class="label_formulario" >Taquillas</label>
												<div id="divTaquillas"'; if($_POST['perfil']!="a") echo ' style="display:none;visibility:hidden;"'; echo '>
													&nbsp;&nbsp;<input type="checkbox" class="check_formulario" name="check_gest_taquillas">
													&nbsp;&nbsp;<label class="label_formulario">Gest. Taquillas</label><br>
													&nbsp;&nbsp;<input type="checkbox" class="check_formulario" name="check_int_taquillas">
													&nbsp;&nbsp;<label class="label_formulario">Gest. Int. Taquillas</label>
												</div>
											</td>
										</tr>
										<tr>											
											<td valign="top" width="231px">
												<div id="divHab_REAJ">
													<input type="checkbox" class="check_formulario" name="check_int_habitaciones">&nbsp;&nbsp;&nbsp;<label class="label_formulario">Habitaciones</label>
													<div style="margin-top:4px;">
														<input type="checkbox" class="check_formulario" name="check_general_reaj" onclick="if(this.checked==true){divREAJ.style.visibility=\'visible\';divREAJ.style.display=\'block\';}else{divREAJ.style.visibility=\'hidden\';divREAJ.style.display=\'none\';check_reaj.checked=false;check_int_reaj.checked=false;}">&nbsp;&nbsp;&nbsp;<label class="label_formulario">REAJ</label>
														<div id="divREAJ"'; if($_POST['perfil']!="a") echo ' style="display:none;visibility:hidden;"'; echo '>
															&nbsp;&nbsp;<input type="checkbox" class="check_formulario" name="check_reaj">
															&nbsp;&nbsp;<label class="label_formulario">Venta REAJ</label><br>
															&nbsp;&nbsp;<input type="checkbox" class="check_formulario" name="check_int_reaj">
															&nbsp;&nbsp;<label class="label_formulario">Gest. Int. REAJ</label>
														</div>
													</div>
												</div>
											</td>											
										</tr>
										<tr><td colspan="3" align="center"><img style="border:none;" src="../imagenes/botones-texto/aceptar.jpg" alt="Aceptar" title="Aceptar" onclick="comprobarPermisos();" id="boton"/>&nbsp;&nbsp;&nbsp;<img style="border:none;" src="../imagenes/botones-texto/cancelar.jpg" alt="Cancelar" title="Cancelar" onclick="';
									if(isset($_REQUEST['actua'])&& $_REQUEST['actua']=="si") //Si es una actualizacion, cambio el action del formulario (para que al pulsar en Cancelar en vez de volver a la Ventana de Nuevo Usuario, vuelva a la de Modificar Usuario) y envio el formulario al pulsar en Cancelar
											{
											echo 'document.permisos.action=\'?pag=gi_usuarios.php&cuad1=4&orden='.$_REQUEST['orden'].'\'; document.permisos.submit();" id="boton"/></td></tr>
									'.$finBorde.'</table>';
											//En este script cambio el action del formulario, para que al enviarlo y entrar en la zona de inserccion de usuarios en la BD, sepa que se trata de una actulizacion
											echo '<script>
													document.permisos.action=\'?pag=gi_usuarios.php&nuevo=act&orden='.$_REQUEST['orden'].'\';
												</script>';
																					
												
											//Selecciono los permisos del usuario de la BD y marco los checkbox de los permisos que ya tiene asignados
											$result=mysql_query("select OnLine, Reservas, Peregrino, Alberguista, Grupos, Facturacion, Incidencias, TarifasEdades, Estadisticas, InfPolicial, REAJ, InternaREAJ, Usuarios, Taquillas, InternaTaquillas, Habitaciones, InternaHabitaciones from usuario where usuario='".$_POST['user']."'");
											if(mysql_num_rows($result)>0)
												{
												$fila=mysql_fetch_array($result);
												echo "<script language='javascript'>";
												if($fila['OnLine']=="Y")echo "document.permisos.check_online.checked=true;";
												if($fila['Reservas']=="Y")echo "document.permisos.check_reservas.checked=true;";
												if($fila['Peregrino']=="Y")echo "document.permisos.check_peregrino.checked=true;";
												if($fila['Alberguista']=="Y")echo "document.permisos.check_alberguistas.checked=true;";
												if($fila['Grupos']=="Y")echo "document.permisos.check_grupos.checked=true;";
												if($fila['Facturacion']=="Y")echo "document.permisos.check_facturacion.checked=true;";
												if($fila['Incidencias']=="Y")echo "document.permisos.check_incidencias.checked=true;";
												if($fila['TarifasEdades']=="Y")echo "document.permisos.check_tarifas.checked=true;";
												if($fila['Estadisticas']=="Y")echo "document.permisos.check_estadisticas.checked=true;";
												if($fila['InfPolicial']=="Y")echo "document.permisos.check_informe_policial.checked=true;";
												if($fila['REAJ']=="Y")echo "divREAJ.style.display='block';divREAJ.style.visibility='visible';document.permisos.check_general_reaj.checked=true;document.permisos.check_reaj.checked=true;";
												if($fila['InternaREAJ']=="Y"){echo "divREAJ.style.display='block';divREAJ.style.visibility='visible';document.permisos.check_general_reaj.checked=true;document.permisos.check_int_reaj.checked=true;";}
												if($fila['Usuarios']=="Y")echo "document.permisos.check_usuarios.checked=true;";
												if($fila['Taquillas']=="Y"){echo "divHab_REAJ.style.marginTop=-38;divTaquillas.style.display='block';divTaquillas.style.visibility='visible';document.permisos.check_taquillas.checked=true;document.permisos.check_gest_taquillas.checked=true;";}
												if($fila['InternaTaquillas']=="Y"){echo "divHab_REAJ.style.marginTop=-38;divTaquillas.style.display='block';divTaquillas.style.visibility='visible';document.permisos.check_taquillas.checked=true;document.permisos.check_int_taquillas.checked=true;";}
												if($fila['InternaHabitaciones']=="Y"){echo "document.permisos.check_int_habitaciones.checked=true;";}
												echo "</script>";
												}																				
											}
									else
										echo 'location.href=\'?pag=gi_usuarios.php&orden='.$_REQUEST['orden'].'\'" id="boton"/></td></tr>
									'.$finBorde.'
								</table>';
								if($_POST['perfil']=="a") //Si en la ventana de Nuevo Usuario o en la de Modificar Usuario, elegimos como perfil Administrador, entonces marco todos los checkbox de permisos
									echo '<script>
												divHab_REAJ.style.marginTop=-38;
												divTaquillas.style.display="block";
												divREAJ.style.display="block";
												document.permisos.check_online.checked=true;
												document.permisos.check_alberguistas.checked=true;
												document.permisos.check_grupos.checked=true;
												document.permisos.check_peregrino.checked=true;
												document.permisos.check_facturacion.checked=true;
												document.permisos.check_tarifas.checked=true;
												document.permisos.check_reservas.checked=true;
												document.permisos.check_estadisticas.checked=true;												
												document.permisos.check_incidencias.checked=true;
												document.permisos.check_usuarios.checked=true;
												document.permisos.check_informe_policial.checked=true;
												document.permisos.check_general_reaj.checked=true;
													document.permisos.check_reaj.checked=true;
													document.permisos.check_int_reaj.checked=true;
												document.permisos.check_int_habitaciones.checked=true;
												document.permisos.check_taquillas.checked=true;
													document.permisos.check_gest_taquillas.checked=true;
													document.permisos.check_int_taquillas.checked=true;
										</script>';

/****************************** Fin de la Version que solo tiene el Permiso de Gest. Int. Habitaciones ********************************************/
									
/****************************************************************************************
					FIN DE LA TABLA DE PERMISOS PARA MOZILLA FIREFOX
****************************************************************************************/
	}
else
	if($nav['app.Name']=="Explorer")
	  {
/****************************************************************************************
						TABLA DE PERMISOS PARA INTERNET EXPLORER
****************************************************************************************/	    

/********************* Versión con checkboxes de Permisos de Gest. Habitaciones y Gest. Int. Habitaciones ******************************************

echo '<tr>
			<td>
		<div>
			<div style="border:solid #F4FCFF;display:inline;width:200px;"><INPUT TYPE="checkbox" NAME="check_online" class="check_formulario" /><label class="label_formulario">Reservas On-Line</label></div>	
			<div style="border:solid #F4FCFF;display:inline;width:200px;"><INPUT TYPE="checkbox" NAME="check_alberguistas" class="check_formulario"/><label class="label_formulario">Alberguista</label></div>			
		</div>
		
		<div>
			<div style="border:solid #F4FCFF;display:inline;width:200px;"><INPUT TYPE="checkbox" NAME="check_reservas" class="check_formulario" /><label class="label_formulario">Reservas</label></div>
			<div style="border:solid #F4FCFF;display:inline;width:200px;"><INPUT TYPE="checkbox" NAME="check_peregrino" class="check_formulario" /><label class="label_formulario">Peregrino</label></div>
		</div>
		
		<div>
			<div style="border:solid #F4FCFF;display:inline;width:200px;"><INPUT TYPE="checkbox" NAME="check_facturacion" class="check_formulario" /><label class="label_formulario">Facturación</label></div>
			<div style="border:solid #F4FCFF;display:inline;width:200px;"><INPUT TYPE="checkbox" NAME="check_grupos" class="check_formulario" /><label class="label_formulario">Grupos</label></div>
		</div>				
		
		<div>
			<div style="border:solid #F4FCFF;display:inline;width:200px;"><INPUT TYPE="checkbox" NAME="check_tarifas" class="check_formulario" /><label class="label_formulario">Tarifas / Edades</label></div>
			<div style="border:solid #F4FCFF;display:inline;width:200px;"><INPUT TYPE="checkbox" NAME="check_estadisticas" class="check_formulario" /><label class="label_formulario">Estadísticas</label></div>
		</div>
		
		<div>
			<div style="border:solid #F4FCFF;display:inline;width:200px;"><INPUT TYPE="checkbox" NAME="check_incidencias" class="check_formulario" /><label class="label_formulario">Incidencias</label></div>
			<div style="border:solid #F4FCFF;display:inline;width:200px;"><INPUT TYPE="checkbox" NAME="check_informe_policial" class="check_formulario" /><label class="label_formulario">Informe Policial</label></div>
		</div>
		
		<div>
			<div style="border:solid #F4FCFF;display:inline;width:200px;"><INPUT TYPE="checkbox" NAME="check_usuarios" class="check_formulario" /><label class="label_formulario">Usuarios</label></div>
			<div style="border:solid #F4FCFF;display:inline;width:200px;"><INPUT TYPE="checkbox" NAME="check_general_reaj" onclick="if(this.checked){divREAJ.style.display=\'block\'; divPrincipalHab.style.marginTop=-76; if(check_taquillas.checked)divHab.style.marginTop=-96; else divHab.style.marginTop=-52;} else {check_reaj.checked=false;check_int_reaj.checked=false;divREAJ.style.display=\'none\'; divPrincipalHab.style.marginTop=0;divHab.style.marginTop=0;}" class="check_formulario"/><label class="label_formulario">REAJ</label></div>
			<div style="border:solid #F4FCFF;display:none;margin-left:210px;width:190px;" id="divREAJ">
				<div><INPUT TYPE="checkbox" NAME="check_reaj" class="check_formulario" /><label class="label_formulario">Venta REAJ</label></div>
				<div><INPUT TYPE="checkbox" NAME="check_int_reaj" class="check_formulario" /><label class="label_formulario">Gest. Int. REAJ</label></div>
			</div>
		</div>

		<div>
			<div style="border:solid #F4FCFF;display:inline;width:200px;" id="divPrincipalHab"><INPUT TYPE="checkbox" NAME="check_habitaciones" onclick="if(this.checked){divHab.style.display=\'inline\';divTaq.style.marginLeft=10;}else{check_gest_habitaciones.checked=false;check_int_habitaciones.checked=false;divHab.style.display=\'none\';divTaq.style.marginLeft=210;}" class="check_formulario" /><label class="label_formulario">Habitaciones</label></div>
			<div style="border:solid #F4FCFF;display:inline;width:200px;"><INPUT TYPE="checkbox" NAME="check_taquillas" onclick="if(this.checked){divTaq.style.display=\'inline\';if(check_general_reaj.checked)divHab.style.marginTop=-96;}else{check_gest_taquillas.checked=false;check_int_taquillas.checked=false;divTaq.style.display=\'none\';if(check_general_reaj.checked)divHab.style.marginTop=-52}" class="check_formulario" /><label class="label_formulario">Taquillas</label></div>
		</div>	

		<div>
			<div style="border:solid #F4FCFF;display:none;margin-left:10px;width:190px;" id="divHab">
				<div><INPUT TYPE="checkbox" NAME="check_gest_habitaciones" class="check_formulario" /><label class="label_formulario">Gest. Habitaciones</label></div>
				<div><INPUT TYPE="checkbox" NAME="check_int_habitaciones" class="check_formulario" /><label class="label_formulario">Gest. Int. Habitaciones</label></div>
			</div>
			<div style="border:solid #F4FCFF;display:none;margin-left:210px;width:190px;" id="divTaq">
				<div><INPUT TYPE="checkbox" NAME="check_gest_taquillas" class="check_formulario" /><label class="label_formulario">Gest. Taquillas</label></div>
				<div><INPUT TYPE="checkbox" NAME="check_int_taquillas" class="check_formulario" /><label class="label_formulario">Gest. Int. Taquillas</label></div>
			</div>
		</div>
											</td>
											
										</tr>
										<tr><td colspan="3" align="center"><img style="border:none;" src="../imagenes/botones-texto/aceptar.jpg" alt="Aceptar" title="Aceptar" onclick="comprobarPermisos();" id="boton"/>&nbsp;&nbsp;&nbsp;<img style="border:none;" src="../imagenes/botones-texto/cancelar.jpg" alt="Cancelar" title="Cancelar" onclick="';
									if(isset($_REQUEST['actua'])&& $_REQUEST['actua']=="si")//Si es una actualizacion, cambio el action del formulario (para que al pulsar en Cancelar en vez de volver a la Ventana de Nuevo Usuario, vuelva a la de Modificar Usuario) y envio el formulario al pulsar en Cancelar
											{
											echo 'document.permisos.action=\'?pag=gi_usuarios.php&cuad1=4&orden='.$_REQUEST['orden'].'\'; document.permisos.submit();" id="boton"/></td></tr>
									'.$finBorde.'</table>';
											//En este script cambio el action del formulario, para que al enviarlo y entrar en la zona de inserccion de usuarios en la BD, sepa que se trata de una actulizacion
											echo '<script>
													document.permisos.action=\'?pag=gi_usuarios.php&nuevo=act&orden='.$_REQUEST['orden'].'\';
												</script>';
																					
											//Selecciono los permisos del usuario de la BD y marco los checkbox de los permisos que ya tiene asignados	
											$result=mysql_query("select OnLine, Reservas, Peregrino, Alberguista, Grupos, Facturacion, Incidencias, TarifasEdades, Estadisticas, InfPolicial, REAJ, InternaREAJ, Usuarios, Taquillas, InternaTaquillas, Habitaciones, InternaHabitaciones from usuario where usuario='".$_POST['user']."'");
											if(mysql_num_rows($result)>0)
												{
												$fila=mysql_fetch_array($result);
												echo "<script language='javascript'>";
												if($fila['OnLine']=="Y")echo "document.permisos.check_online.checked=true;";
												if($fila['Reservas']=="Y")echo "document.permisos.check_reservas.checked=true;";
												if($fila['Peregrino']=="Y")echo "document.permisos.check_peregrino.checked=true;";
												if($fila['Alberguista']=="Y")echo "document.permisos.check_alberguistas.checked=true;";
												if($fila['Grupos']=="Y")echo "document.permisos.check_grupos.checked=true;";
												if($fila['Facturacion']=="Y")echo "document.permisos.check_facturacion.checked=true;";
												if($fila['Incidencias']=="Y")echo "document.permisos.check_incidencias.checked=true;";
												if($fila['TarifasEdades']=="Y")echo "document.permisos.check_tarifas.checked=true;";
												if($fila['Estadisticas']=="Y")echo "document.permisos.check_estadisticas.checked=true;";
												if($fila['InfPolicial']=="Y")echo "document.permisos.check_informe_policial.checked=true;";
												if($fila['REAJ']=="Y")echo "document.permisos.check_general_reaj.checked=true;document.permisos.check_reaj.checked=true; divREAJ.style.display='block'; divPrincipalHab.style.marginTop=-76; if(document.permisos.check_taquillas.checked)divHab.style.marginTop=-96; else divHab.style.marginTop=-52;";
												
												if($fila['InternaREAJ']=="Y")echo "document.permisos.check_general_reaj.checked=true;document.permisos.check_int_reaj.checked=true;divREAJ.style.display='block';  divPrincipalHab.style.marginTop=-76; if(document.permisos.check_taquillas.checked)divHab.style.marginTop=-96; else divHab.style.marginTop=-52;";												
												if($fila['Usuarios']=="Y")echo "document.permisos.check_usuarios.checked=true;";
												if($fila['Taquillas']=="Y"){echo "document.permisos.check_taquillas.checked=true;document.permisos.check_gest_taquillas.checked=true;divTaq.style.display='inline';if(document.permisos.check_general_reaj.checked)divHab.style.marginTop=-96;";}
												if($fila['InternaTaquillas']=="Y"){echo "document.permisos.check_taquillas.checked=true;document.permisos.check_int_taquillas.checked=true;divTaq.style.display='inline';if(document.permisos.check_general_reaj.checked)divHab.style.marginTop=-96;";}
												if($fila['Habitaciones']=="Y"){echo "document.permisos.check_habitaciones.checked=true;document.permisos.check_gest_habitaciones.checked=true;divHab.style.display='inline';divTaq.style.marginLeft=10;";}
												if($fila['InternaHabitaciones']=="Y"){echo "document.permisos.check_habitaciones.checked=true;document.permisos.check_int_habitaciones.checked=true;divHab.style.display='inline';divTaq.style.marginLeft=10;";}
												echo "</script>";
												}																				
											}
									else
										echo 'location.href=\'?pag=gi_usuarios.php&orden='.$_REQUEST['orden'].'\'" id="boton"/></td></tr>
									'.$finBorde.'
								</table>';
								if($_POST['perfil']=="a") //Si en la ventana de Nuevo Usuario o en la de Modificar Usuario, elegimos como perfil Administrador, entonces marco todos los checkbox de permisos
									echo '<script>
												divHab.style.display="inline";
												divTaq.style.display="inline";
												divREAJ.style.display="block";
												divHab.style.marginTop=-96;
												divPrincipalHab.style.marginTop=-76;
												document.permisos.check_online.checked=true;
												document.permisos.check_alberguistas.checked=true;
												document.permisos.check_grupos.checked=true;
												document.permisos.check_peregrino.checked=true;
												document.permisos.check_facturacion.checked=true;
												document.permisos.check_tarifas.checked=true;
												document.permisos.check_reservas.checked=true;
												document.permisos.check_estadisticas.checked=true;
												document.permisos.check_general_reaj.checked=true;
												document.permisos.check_reaj.checked=true;
												document.permisos.check_int_reaj.checked=true;
												document.permisos.check_incidencias.checked=true;
												document.permisos.check_usuarios.checked=true;
												document.permisos.check_informe_policial.checked=true;												
												document.permisos.check_habitaciones.checked=true;
												divTaq.style.marginLeft=10;
													document.permisos.check_gest_habitaciones.checked=true;
													document.permisos.check_int_habitaciones.checked=true;
												document.permisos.check_taquillas.checked=true;
													document.permisos.check_gest_taquillas.checked=true;
													document.permisos.check_int_taquillas.checked=true;
										</script>';

****************************** Fin de la Version con los Permisos de Gest. Habitaciones y Gest. Int. Habitaciones ********************************/

/***************************** Version que solo tiene Permiso de Gest. Int. Habitaciones *********************************************************/
	  echo '<tr>
			<td>
		<div>
			<div style="border:solid #F4FCFF;display:inline;width:200px;"><INPUT TYPE="checkbox" NAME="check_online" class="check_formulario" /><label class="label_formulario">Reservas On-Line</label></div>	
			<div style="border:solid #F4FCFF;display:inline;width:200px;"><INPUT TYPE="checkbox" NAME="check_alberguistas" class="check_formulario"/><label class="label_formulario">Alberguista</label></div>			
		</div>
		
		<div>
			<div style="border:solid #F4FCFF;display:inline;width:200px;"><INPUT TYPE="checkbox" NAME="check_reservas" class="check_formulario" /><label class="label_formulario">Reservas</label></div>
			<div style="border:solid #F4FCFF;display:inline;width:200px;"><INPUT TYPE="checkbox" NAME="check_peregrino" class="check_formulario" /><label class="label_formulario">Peregrino</label></div>
		</div>
		
		<div>
			<div style="border:solid #F4FCFF;display:inline;width:200px;"><INPUT TYPE="checkbox" NAME="check_facturacion" class="check_formulario" /><label class="label_formulario">Facturación</label></div>
			<div style="border:solid #F4FCFF;display:inline;width:200px;"><INPUT TYPE="checkbox" NAME="check_grupos" class="check_formulario" /><label class="label_formulario">Grupos</label></div>
		</div>				
		
		<div>
			<div style="border:solid #F4FCFF;display:inline;width:200px;"><INPUT TYPE="checkbox" NAME="check_tarifas" class="check_formulario" /><label class="label_formulario">Tarifas / Edades</label></div>
			<div style="border:solid #F4FCFF;display:inline;width:200px;"><INPUT TYPE="checkbox" NAME="check_estadisticas" class="check_formulario" /><label class="label_formulario">Estadísticas</label></div>
		</div>
		
		<div>
			<div style="border:solid #F4FCFF;display:inline;width:200px;"><INPUT TYPE="checkbox" NAME="check_incidencias" class="check_formulario" /><label class="label_formulario">Incidencias</label></div>
			<div style="border:solid #F4FCFF;display:inline;width:200px;"><INPUT TYPE="checkbox" NAME="check_informe_policial" class="check_formulario" /><label class="label_formulario">Informe Policial</label></div>
		</div>
		
		<div>
			<div style="border:solid #F4FCFF;display:inline;width:200px;"><INPUT TYPE="checkbox" NAME="check_usuarios" class="check_formulario" /><label class="label_formulario">Usuarios</label></div>
			<div style="border:solid #F4FCFF;display:inline;width:200px;"><INPUT TYPE="checkbox" NAME="check_general_reaj" onclick="if(this.checked){divREAJ.style.display=\'block\'; divPrincipalHab.style.marginTop=-76;} else {check_reaj.checked=false;check_int_reaj.checked=false;divREAJ.style.display=\'none\'; divPrincipalHab.style.marginTop=0;}" class="check_formulario"/><label class="label_formulario">REAJ</label></div>
			<div style="border:solid #F4FCFF;display:none;margin-left:210px;width:190px;" id="divREAJ">
				<div><INPUT TYPE="checkbox" NAME="check_reaj" class="check_formulario" /><label class="label_formulario">Venta REAJ</label></div>
				<div><INPUT TYPE="checkbox" NAME="check_int_reaj" class="check_formulario" /><label class="label_formulario">Gest. Int. REAJ</label></div>
			</div>
		</div>

		<div>
			<div style="border:solid #F4FCFF;display:inline;width:200px;" id="divPrincipalHab"><INPUT TYPE="checkbox" NAME="check_int_habitaciones" class="check_formulario" /><label class="label_formulario">Gest. Int. Habitaciones</label></div>
			<div style="border:solid #F4FCFF;display:inline;width:200px;"><INPUT TYPE="checkbox" NAME="check_taquillas" onclick="if(this.checked){divTaq.style.display=\'inline\';}else{check_gest_taquillas.checked=false;check_int_taquillas.checked=false;divTaq.style.display=\'none\';}" class="check_formulario" /><label class="label_formulario">Taquillas</label></div>
		</div>	

		<div>
			<div style="border:solid #F4FCFF;display:none;margin-left:210px;width:190px;" id="divTaq">
				<div><INPUT TYPE="checkbox" NAME="check_gest_taquillas" class="check_formulario" /><label class="label_formulario">Gest. Taquillas</label></div>
				<div><INPUT TYPE="checkbox" NAME="check_int_taquillas" class="check_formulario" /><label class="label_formulario">Gest. Int. Taquillas</label></div>
			</div>
		</div>
											</td>
											
										</tr>
										<tr><td colspan="3" align="center"><img style="border:none;" src="../imagenes/botones-texto/aceptar.jpg" alt="Aceptar" title="Aceptar" onclick="comprobarPermisos();" id="boton"/>&nbsp;&nbsp;&nbsp;<img style="border:none;" src="../imagenes/botones-texto/cancelar.jpg" alt="Cancelar" title="Cancelar" onclick="';
									if(isset($_REQUEST['actua'])&& $_REQUEST['actua']=="si")//Si es una actualizacion, cambio el action del formulario (para que al pulsar en Cancelar en vez de volver a la Ventana de Nuevo Usuario, vuelva a la de Modificar Usuario) y envio el formulario al pulsar en Cancelar
											{
											echo 'document.permisos.action=\'?pag=gi_usuarios.php&cuad1=4&orden='.$_REQUEST['orden'].'\'; document.permisos.submit();" id="boton"/></td></tr>
									'.$finBorde.'</table>';
											//En este script cambio el action del formulario, para que al enviarlo y entrar en la zona de inserccion de usuarios en la BD, sepa que se trata de una actulizacion
											echo '<script>
													document.permisos.action=\'?pag=gi_usuarios.php&nuevo=act&orden='.$_REQUEST['orden'].'\';
												</script>';
																					
											//Selecciono los permisos del usuario de la BD y marco los checkbox de los permisos que ya tiene asignados	
											$result=mysql_query("select OnLine, Reservas, Peregrino, Alberguista, Grupos, Facturacion, Incidencias, TarifasEdades, Estadisticas, InfPolicial, REAJ, InternaREAJ, Usuarios, Taquillas, InternaTaquillas, Habitaciones, InternaHabitaciones from usuario where usuario='".$_POST['user']."'");
											if(mysql_num_rows($result)>0)
												{
												$fila=mysql_fetch_array($result);
												echo "<script language='javascript'>";
												if($fila['OnLine']=="Y")echo "document.permisos.check_online.checked=true;";
												if($fila['Reservas']=="Y")echo "document.permisos.check_reservas.checked=true;";
												if($fila['Peregrino']=="Y")echo "document.permisos.check_peregrino.checked=true;";
												if($fila['Alberguista']=="Y")echo "document.permisos.check_alberguistas.checked=true;";
												if($fila['Grupos']=="Y")echo "document.permisos.check_grupos.checked=true;";
												if($fila['Facturacion']=="Y")echo "document.permisos.check_facturacion.checked=true;";
												if($fila['Incidencias']=="Y")echo "document.permisos.check_incidencias.checked=true;";
												if($fila['TarifasEdades']=="Y")echo "document.permisos.check_tarifas.checked=true;";
												if($fila['Estadisticas']=="Y")echo "document.permisos.check_estadisticas.checked=true;";
												if($fila['InfPolicial']=="Y")echo "document.permisos.check_informe_policial.checked=true;";
												if($fila['REAJ']=="Y")echo "document.permisos.check_general_reaj.checked=true;document.permisos.check_reaj.checked=true; divREAJ.style.display='block'; divPrincipalHab.style.marginTop=-76;";
												
												if($fila['InternaREAJ']=="Y")echo "document.permisos.check_general_reaj.checked=true;document.permisos.check_int_reaj.checked=true;divREAJ.style.display='block';  divPrincipalHab.style.marginTop=-76;";												
												if($fila['Usuarios']=="Y")echo "document.permisos.check_usuarios.checked=true;";
												if($fila['Taquillas']=="Y"){echo "document.permisos.check_taquillas.checked=true;document.permisos.check_gest_taquillas.checked=true;divTaq.style.display='inline';";}
												if($fila['InternaTaquillas']=="Y"){echo "document.permisos.check_taquillas.checked=true;document.permisos.check_int_taquillas.checked=true;divTaq.style.display='inline';";}
												if($fila['InternaHabitaciones']=="Y"){echo "document.permisos.check_int_habitaciones.checked=true;";}
												echo "</script>";
												}																				
											}
									else
										echo 'location.href=\'?pag=gi_usuarios.php&orden='.$_REQUEST['orden'].'\'" id="boton"/></td></tr>
									'.$finBorde.'
								</table>';
								if($_POST['perfil']=="a") //Si en la ventana de Nuevo Usuario o en la de Modificar Usuario, elegimos como perfil Administrador, entonces marco todos los checkbox de permisos
									echo '<script>
												divTaq.style.display="inline";
												divREAJ.style.display="block";
												divPrincipalHab.style.marginTop=-76;
												document.permisos.check_online.checked=true;
												document.permisos.check_alberguistas.checked=true;
												document.permisos.check_grupos.checked=true;
												document.permisos.check_peregrino.checked=true;
												document.permisos.check_facturacion.checked=true;
												document.permisos.check_tarifas.checked=true;
												document.permisos.check_reservas.checked=true;
												document.permisos.check_estadisticas.checked=true;
												document.permisos.check_general_reaj.checked=true;
												document.permisos.check_reaj.checked=true;
												document.permisos.check_int_reaj.checked=true;
												document.permisos.check_incidencias.checked=true;
												document.permisos.check_usuarios.checked=true;
												document.permisos.check_informe_policial.checked=true;												
													document.permisos.check_int_habitaciones.checked=true;
												document.permisos.check_taquillas.checked=true;
													document.permisos.check_gest_taquillas.checked=true;
													document.permisos.check_int_taquillas.checked=true;
										</script>';
/****************************** Fin de la Version que solo tiene el Permiso de Gest. Int. Habitaciones ********************************************/							
/****************************************************************************************
					FIN DE LA TABLA DE PERMISOS PARA INTERNET EXPLORER
****************************************************************************************/		
		}										
							echo '</form>';
							
						break;
					case 3: //VENTANA DETALLES DE USUARIO
						echo '<form method="POST">
								<table cellpadding="0" cellspacing="0">
									<thead>
										<td colspan="5" style="padding:0px 0px 0px 0px;">
											<div class="champi_izquierda">&nbsp;</div>
											<div class="champi_centro">
												<div class="titulo" style="width: 350px; text-align: center;">Detalles del Usuario</div>
											</div>
											<div class="champi_derecha">&nbsp;</div>
										</td>
									</thead>
									'.$inicioBorde.'
									<tr><td height="50"><br><label class="label_formulario">Usuario :</label></td><td height="50"><br><label id="texto_detalles">'.$_POST['user'].'</label></td></tr>
									<tr><td height="50"><label class="label_formulario">Perfil :</label></td><td height="50"><label id="texto_detalles">';if($_POST['p']=="u") echo'Usuario'; else echo 'Administrador'; echo'</label></td></tr>
									<tr><td valign="top" height="100"><label class="label_formulario">Permisos :</label></td>
										<td>
											<table cellspacing="0" height="200">';
											if($_POST['p']=="a") //Si el perfil es de ADMINISTRADOR, muestro todos los permisos
												{
												echo "<tr>
														<td height='200'>
															<div id='listadoPermisos' style='height:240;'>
																<label id='texto_detalles'>Reservas On-Line<br></label>
																<label id='texto_detalles'>Reservas<br></label>
																<label id='texto_detalles'>Peregrinos<br></label>
																<label id='texto_detalles'>Alberguistas<br></label>
																<label id='texto_detalles'>Grupos<br></label>
																<label id='texto_detalles'>Facturación<br></label>
																<label id='texto_detalles'>Incidencias<br></label>
																<label id='texto_detalles'>Tarifas/Edades<br></label>
																<label id='texto_detalles'>Estadisticas<br></label>
																<label id='texto_detalles'>Informe Policial<br></label>
																<label id='texto_detalles'>Venta REAJ<br></label>
																<label id='texto_detalles'>Gest. Int. REAJ<br></label>
																<label id='texto_detalles'>Usuarios<br></label>
																<label id='texto_detalles'>Gest. Taquillas<br></label>
																<label id='texto_detalles'>Gest. Int. Taquillas<br></label>";
/***************************************************************************************************************************************
	Descomentar esta linea si se ha activado la version con los Permisos de Gest. Habitaciones y Gest. Int. Habitaciones:
										
															echo "<label id='texto_detalles'>Gest. Habitaciones<br></label>";
***************************************************************************************************************************************/																
															echo "<label id='texto_detalles'>Gest. Int. Habitaciones<br></label>
															</div>
														</td>
													</tr>";
												}
											else
												{
												//Si el Perfil es de Usuario, obrtengo los permisos de la BD y los muestro
												$verPermisos=mysql_query("select * from usuario where usuario='".$_POST['user']."'");
												if(mysql_num_rows($verPermisos)>0)
													{
													$fila=mysql_fetch_array($verPermisos);
													echo "<tr><td><div id='listadoPermisos' style='height:240;'>";
													if($fila['OnLine']=="Y")
														echo "<label id='texto_detalles'>Reservas On-Line<br></label>";
													if($fila['Reservas']=="Y")
														echo "<label id='texto_detalles'>Reservas<br></label>";
													if($fila['Peregrino']=="Y")
														echo "<label id='texto_detalles'>Peregrinos<br></label>";
													if($fila['Alberguista']=="Y")
														echo "<label id='texto_detalles'>Alberguistas<br></label>";
													if($fila['Grupos']=="Y")
														echo "<label id='texto_detalles'>Grupos<br></label>";
													if($fila['Facturacion']=="Y")
														echo "<label id='texto_detalles'>Facturación<br></label>";
													if($fila['Incidencias']=="Y")
														echo "<label id='texto_detalles'>Incidencias<br></label>";
													if($fila['TarifasEdades']=="Y")
														echo "<label id='texto_detalles'>Tarifas/Edades<br></label>";
													if($fila['Estadisticas']=="Y")
														echo "<label id='texto_detalles'>Estadisticas<br></label>";
													if($fila['InfPolicial']=="Y")
														echo "<label id='texto_detalles'>Informe Policial<br></label>";
													if($fila['REAJ']=="Y")
														echo "<label id='texto_detalles'>Venta REAJ<br></label>";
													if($fila['InternaREAJ']=="Y")
														echo "<label id='texto_detalles'>Gest. Int. REAJ<br></label>";
													if($fila['Usuarios']=="Y")
														echo "<label id='texto_detalles'>Usuarios<br></label>";
													if($fila['Taquillas']=="Y")
														echo "<label id='texto_detalles'>Gest. Taquillas<br></label>";
													if($fila['InternaTaquillas']=="Y")
														echo "<label id='texto_detalles'>Gest. Int. Taquillas<br></label>";
/**********************************************************************************************************************************************
	Descomentar estas 2 lineas si se usa la version que tiene los checkbox de Gest. Habitaciones y Gest. Int. Habitaciones
					
													if($fila['Habitaciones']=="Y")
														echo "<label id='texto_detalles'>Gest. Habitaciones<br></label>";
**********************************************************************************************************************************************/														
													if($fila['InternaHabitaciones']=="Y")
														echo "<label id='texto_detalles'>Gest. Int. Habitaciones<br></label>";  
													echo "</div></td></tr>";	   	   	   	   	   	   	 														
													}
												}
										echo '</table>
										</td>
									</tr>
									<tr><td colspan="2" align="center">
											<img style="border:none;" src="../imagenes/botones-texto/aceptar.jpg" alt="Aceptar" title="Aceptar" id="boton" name="aceptar" onclick="location.href=\'?pag=gi_usuarios.php&orden='.$_REQUEST['orden'].'\'"/>
										</td>
									</tr>
									'.$finBorde.'												
								</table>
							</form>';
						break; 
					case 4: //VENTANA MODIFICAR USUARIO
						if($_SESSION['usuario']==$_POST['user'] && $_POST['user']=="admin" && $_POST['p']=="a") //Compruebo si el usuario a modificar es el Administrador Principal y que sea el usuario actual, en cuyo caso solo dejo que se le pueda cambiar la contraseña
							{
							echo '<script>
									//Funcion utilizada para comprobar que han sido rellenados todos los campos del formulario de modificar usuario, y que las dos contraseñas son iguales
									function comprobar(formulario)
										{
										if((formulario.passwordActual.value!="" && formulario.passwordActual.value!=null && formulario.password.value!="" && formulario.password.value!=null && formulario.password.value==formulario.password2.value))
											formulario.submit();
										else
											if(formulario.password.value=="" || formulario.password.value==null || formulario.password2.value=="" || formulario.password2.value==null || formulario.passwordActual.value=="" || formulario.passwordActual.value==null)
												alert("No has rellenado todos los campos");										
											else
												if(formulario.password.value!=formulario.password2.value)
													alert("Las contraseñas no coinciden");
										}
								</script>
								<form name="modificarUsuario" method="POST" action="?pag=gi_usuarios.php&cuad1=6&orden='.$_REQUEST['orden'].'">
									<table cellspacing="0" cellpadding="0">
										<thead>
											<td colspan="5" style="padding:0px 0px 0px 0px;">
												<div class="champi_izquierda">&nbsp;</div>
												<div class="champi_centro">
													<div class="titulo" style="width: 350px; text-align: center;">Modificar Usuario</div>
												</div>
												<div class="champi_derecha">&nbsp;</div>
											</td>
										</thead>
										'.$inicioBorde;
								if(isset($_POST['errorPassword']) && $_POST['errorPassword']=='true')
										echo '<tr><td colspan="2" align="center"><label class="label_formulario" style="color:red;">ERROR : La contraseña no es correcta</label></td></tr>';	
										echo '<tr><td height="50"><br><label class="label_formulario">Usuario :</label></td><td><br>&nbsp;&nbsp;&nbsp;<label id="input_text">'.$_POST['user'].'</label></td></tr>
										<tr><td height="50"><label class="label_formulario">Perfil :</label></td><td>&nbsp;&nbsp;&nbsp;
				<select class="select_formulario" name="perfil">';
				// Compruebo el perfil que tiene actualmente el usuario y lo selecciono
			  	echo '<option value="a" selected>Administrador</option>
				</select></td></tr>
										<tr>
											<td colspan="2" height="150">
												<fieldset style="border:1px solid #3F7BCC" >
													<legend>
														<label class="label_formulario"> Cambiar Contraseña </label>
													</legend>
													<table>									
														<tr><td><label class="label_formulario" id="contrasenaActual">Contraseña Actual:</label></td><td>&nbsp;&nbsp;&nbsp;<input class="input_formulario" type="password" id="passwordActual" name="passwordActual" maxLength="20" /></td></tr>
														<tr><td><label class="label_formulario" id="contrasena">Nueva Contraseña :</label></td><td>&nbsp;&nbsp;&nbsp;<input class="input_formulario" type="password" id="password" name="password" maxLength="20" /></td></tr>
														<tr><td><label class="label_formulario" id="contrasena2">Repita Contraseña :</label></td><td>&nbsp;&nbsp;&nbsp;<input class="input_formulario" type="password" id="password2" name="password2" maxLength="20" /></td></tr>
													</table>
												</fieldset>
											</td>
										</tr>														
										<tr><td align="center" height="50"><br><br><img style="border:none;" src="../imagenes/botones-texto/continuar.jpg" alt="Continuar" title="Continuar" id="boton" name="continuar" onclick="comprobar(modificarUsuario);"/></td><td align="center"><br><br><img style="border:none;" src="../imagenes/botones-texto/cancelar.jpg" alt="Cancelar" title="Cancelar" id="boton" name="cancelar" onclick="location.href=\'?pag=gi_usuarios.php&orden='.$_REQUEST['orden'].'\'" /></td></tr>
	
									'.$finBorde.'											
									</table>
								</form>
								<script>
									document.getElementById("passwordActual").focus();				
								</script>';
							}
						else
							{
							echo '
								<script>
									//Funcion utilizada para comprobar que han sido rellenados todos los campos del formulario de modificar usuario, y que las dos contraseñas son iguales
									function comprobar(formulario)
										{
										if((formulario.checkboxPassword.checked && formulario.password.value!="" && formulario.password.value!=null && formulario.password.value==formulario.password2.value) || !formulario.checkboxPassword.checked)
											formulario.submit();
										else
											if(formulario.password.value=="" || formulario.password.value==null || formulario.password2.value=="" || formulario.password2.value==null)
												alert("No has rellenado todos los campos");										
											else
												if(formulario.password.value!=formulario.password2.value)
													alert("Las contraseñas no coinciden");
										}
										
									//Funcion utilizada para activar los campos de texto referentes al cambio de contraseña
									function activarCambiarPassword(formulario)
										{
										document.getElementById("contrasena").className="label_formulario";
										document.getElementById("contrasena2").className="label_formulario"; 
										document.getElementById("password").className="input_formulario";
										document.getElementById("password2").className="input_formulario";									
									  	formulario.password.disabled=false;
										formulario.password2.disabled=false;
									  	formulario.password.focus();
										}
										
									//Funcion utilizada para desactivar los campos de texto referentes al cambio de contraseña, y para vaciarlos
									function desactivarCambiarPassword(formulario)
										{
										formulario.password.value="";									
										formulario.password2.value="";
										formulario.password.disabled=true;
										formulario.password2.disabled=true;
										document.getElementById("contrasena").className="label_formulario_desac";
										document.getElementById("contrasena2").className="label_formulario_desac";
										document.getElementById("password").className="input_formulario_desac";
										document.getElementById("password2").className="input_formulario_desac";
										}
								</script>
								<form name="modificarUsuario" method="POST" action="?pag=gi_usuarios.php&cuad1=2&actua=si&orden='.$_REQUEST['orden'].'">
									<input type="hidden" name="user" value="'.$_POST['user'].'"/>
									<input type="hidden" name="usuario" value="'.$_POST['user'].'"/> 
									<table cellspacing="0" cellpadding="0">
										<thead>
											<td colspan="5" style="padding:0px 0px 0px 0px;">
												<div class="champi_izquierda">&nbsp;</div>
												<div class="champi_centro">
													<div class="titulo" style="width: 350px; text-align: center;">Modificar Usuario</div>
												</div>
												<div class="champi_derecha">&nbsp;</div>
											</td>
										</thead>
										'.$inicioBorde.'
										<tr><td height="50"><br><label class="label_formulario">Usuario :</label></td><td><br>&nbsp;&nbsp;&nbsp;<label id="input_text">'.$_POST['user'].'</label></td></tr>
										<tr><td height="50"><label class="label_formulario">Perfil :</label></td><td>&nbsp;&nbsp;&nbsp;
				<select class="select_formulario" name="perfil">';
				// Compruebo el perfil que tiene actualmente el usuario y lo selecciono
			  	echo '<option value="u"';if($_POST['p']=="u")echo ' selected'; echo '>Usuario</option>
					<option value="a"';if($_POST['p']=="a")echo ' selected'; echo '>Administrador</option>
				</select></td></tr>
										<tr>
											<td colspan="2" height="150">
												<fieldset style="border:1px solid #3F7BCC" >
													<legend>
														<input class="input_formulario" type="checkbox" name="checkboxPassword" onClick="if(this.checked==true){activarCambiarPassword(modificarUsuario);}else{desactivarCambiarPassword(modificarUsuario);}"/>
														<label class="label_formulario"> Cambiar Contraseña </label>
													</legend>
													<table>									
														<tr><td><label class="label_formulario_desac" id="contrasena">Nueva Contraseña :</label></td><td>&nbsp;&nbsp;&nbsp;<input class="input_formulario_desac" type="password" id="password" name="password" maxLength="20" disabled="true" /></td></tr>
														<tr><td><label class="label_formulario_desac" id="contrasena2">Repita Contraseña :</label></td><td>&nbsp;&nbsp;&nbsp;<input class="input_formulario_desac" type="password" id="password2" name="password2" maxLength="20" disabled="true" /></td></tr>
													</table>
												</fieldset>
											</td>
										</tr>														
										<tr><td align="center" height="50"><br><br><img style="border:none;" src="../imagenes/botones-texto/continuar.jpg" alt="Continuar" title="Continuar" id="boton" name="continuar" onclick="comprobar(modificarUsuario);"/></td><td align="center"><br><br><img style="border:none;" src="../imagenes/botones-texto/cancelar.jpg" alt="Cancelar" title="Cancelar" id="boton" name="cancelar" onclick="location.href=\'?pag=gi_usuarios.php&orden='.$_REQUEST['orden'].'\'" /></td></tr>
	
									'.$finBorde.'											
									</table>
								</form>';
								}
						break;
					case 5: //VENTANA ELIMINAR USUARIO
						if($_POST['user']!=$_SESSION['usuario'] && $_POST['user']!="admin") // Compruebo que el usuario a eliminar no es el Administrador Principal ni el usuario actual
							{
							if(!isset($_REQUEST['actua'])||$_REQUEST['actua']!="si") //Comprobacion de que no es una actualizacion, esto se producira al aceptar la eliminacion 
								{
								//Este es el formulario en el que se solicita confirmar la eliminacion
								echo '<form method="POST">
										<table cellpadding="0" cellspacing="0">
											<thead>
												<td colspan="5" style="padding:0px 0px 0px 0px;">
													<div class="champi_izquierda">&nbsp;</div>
													<div class="champi_centro">
														<div class="titulo" style="width: 350px; text-align: center;">Eliminar Usuario</div>
													</div>
													<div class="champi_derecha">&nbsp;</div>
												</td>
											</thead>
											'.$inicioBorde.'												
											<tr><td height="50"><br><label class="label_formulario">Usuario :</label></td><td height="50"><br><label id="texto_detalles">'.$_POST['user'].'</label></td></tr>
											<tr><td height="50"><label class="label_formulario">Perfil :</label></td><td height="50"><label id="texto_detalles">';if($_POST['p']=="a") echo 'Administrador'; else echo 'Usuario';  echo'</label></td></tr>
											<tr><td colspan="2" align="center" height="50"><label class="label_formulario"><br><br><br><br><br><br>¿Está seguro de que desea eliminar el Usuario?</label></td></tr>
											<tr><td align="center" height="50"><br><br><img title="Aceptar" alt="Aceptar" id="boton" style="border:none;" src="../imagenes/botones-texto/aceptar.jpg" name="aceptar" onclick="location.href=\'?pag=gi_usuarios.php&cuad1=5&actua=si&user='.$_POST['user'].'&p='.$_POST['p'].'&orden='.$_REQUEST['orden'].'\';"/></td><td align="center"><br><br><img style="border:none;" src="../imagenes/botones-texto/cancelar.jpg" alt="Cancelar" title="Cancelar" id="boton" name="cancelar" onclick="location.href=\'?pag=gi_usuarios.php&orden='.$_REQUEST['orden'].'\'" /></td></tr>
											'.$finBorde.'											
										</table>
									</form>';
									}
								else
									{
									//Aqui ya se borra al usuario
									mysql_query("delete from usuario where usuario='".$_REQUEST['user']."'");
									
									//Este script lo uso para escribir el mensaje de Cargando y añadirle puntos suspensivos, y tras 3 segundos ir a la Ventana de Nuevo Usuario
									echo '<script>
												setInterval(\'escribe()\',100);
												setTimeout(\'cargar()\',3000);
												function escribe()//Añade un punto a la caja de texto "cargando"
													{formulario.cargando.value=formulario.cargando.value+\'.\';}
								                function cargar()//Abre la pagina principal de usuarios
								                    {location.href=\'?pag=gi_usuarios.php&orden='.$_REQUEST['orden'].'\';}
								            </script>
											<form name="formulario">
												<table cellspacing="0" cellpadding="0">					
													<tr style="padding-bottom:0px;">
														<td colspan="5" style="padding:0px 0px 0px 0px;">
															<div class="champi_izquierda">&nbsp;</div>
															<div class="champi_centro">
																<div class="titulo" style="width: 350px; text-align: center;">Eliminar Usuario</div>
															</div>
															<div class="champi_derecha">&nbsp;</div>
														</td>
													</tr>
													'.$inicioBorde.'
													<tr><td colspan="5" align="center"><label class="label_formulario">El Usuario se ha eliminado con éxito</label></td></tr>
													<tr><td colspan="5" align="center"><input readonly style="border:none;background-color:#F4FCFF;" name="cargando" value="Cargando" class="label_formulario" /></td></tr>
													<tr>
														<td colspan="5"><br><br></td>
													</tr>
													'.$finBorde.'
												</table>
											</form>';
									}
								}
						break;
						case 6:
							if($_SESSION['usuario']=='admin')
								{
								$instruccionSQL=mysql_query("Select Count(Usuario) from usuario where(Password=PASSWORD('".$_POST['passwordActual']."') and Usuario='admin')");
								$registro=mysql_fetch_row($instruccionSQL);
								if(intval($registro[0])>0)
									{
									mysql_query("UPDATE usuario SET Password=PASSWORD('".$_POST['password']."') WHERE (Password=PASSWORD('".$_POST['passwordActual']."') and usuario='admin')");
									echo "<meta http-equiv='refresh' content='0;url=?pag=gi_usuarios.php&orden=".$_REQUEST['orden']."'>";
									}
								else
									{
									echo "<form name='formularioFalloPassword' action='?pag=gi_usuarios.php&cuad1=4&orden=".$_REQUEST['orden']."' method='post'>
											<input type='text' name='p' value='a' />
											<input type='text' name='user' value='admin' />
											<input type='text' name='errorPassword' value='true' />
										</form>
										<script>
											document.formularioFalloPassword.submit();
									</script>";
									}
								}
							break;
						}
					}
				
	?>			
	</div>
	<div id="caja_superior_derecha" style="float:right;margin-right:<?php if($nav['app.Name']=="Firefox"){echo "130";}else{echo "80";}?>px;margin-top:20px;">
			<?php include("list_usuarios.inc"); /* Cargo el listado de los usuarios */?>
	</div>
</div>
	
<?php	
		mysql_close($db);//Cerrar la conexion MySQL
		} //Fin del IF de comprobacion de acceso a la pagina
	else	//Muestro una ventana de error de permisos de acceso a la pagina
		 echo "<div class='error'>
				NO TIENE PERMISOS PARA ACCEDER A ESTA PÁGINA
			</div>";
?>
