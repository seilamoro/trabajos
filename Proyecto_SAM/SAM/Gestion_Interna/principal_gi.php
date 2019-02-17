<?PHP
	session_start();
	@$db = mysql_pconnect("localhost","root","");
	mysql_select_db("sam");
	if(!$db){
		echo "<script>Alert('No se ha podido realizar la conexion con la base de datos')</script>";
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<TITLE> Gestión interna Albergue Municipal de León</TITLE>		
<?PHP
	if (!isset($_SESSION['aspecto'])) {
		
		?>
		<link rel="stylesheet" type="text/css" href="css/estilos_principal_gi_azul.css">
		<link rel="stylesheet" type="text/css" href="css/hoja_formularios.css">
		<link rel="stylesheet" type="text/css" href="css/habitaciones.css">
		<link rel="stylesheet" type="text/css" href="css/estilos_tablas.css">
	<?
	$_SESSION['aspecto'] = "azul";
	}
	else {
		if (isset($theme)) {
			$_SESSION['aspecto'] = $theme;
			$sql="UPDATE usuario SET Skin = '".$theme."' WHERE Usuario = '".$_SESSION['usuario']. "'";
	    	$resul = mysql_query($sql);
			
		}
		?>
		
		<link rel="stylesheet" type="text/css" href="css/estilos_principal_gi_<?echo $_SESSION['aspecto']?>.css">
		<link rel="stylesheet" type="text/css" href="css/hoja_formularios.css">
		<link rel="stylesheet" type="text/css" href="css/habitaciones.css">
		<link rel="stylesheet" type="text/css" href="css/estilos_tablas.css">
		<?
	}
	?>
	</HEAD>

	<BODY>
		<center>
			<div id="parent">
				<div id="cabecera">
					<div id="caja_botones_sup_gi">
						<table align="center" width="100%">
							<tr>
				<?php
					if(isset($_SESSION['permisoInternaHabitaciones']) && $_SESSION['permisoInternaHabitaciones']==true) //Compruebo que se tienen Permiso para acceder a Gest. Int. de Habitaciones, si es asi, muestro el boton de acceso, sino no lo muestro
						{
				?>	<td align="center">
						<a href="principal_gi.php?pag=gi_habitaciones.php" class="botones_superiores_gi" id="boton_h" alt="Gestión de Habitaciones" title="Gestión de Habitaciones"><div class="boton_superior_gi_hab"></div></a>
					</td>
				<?php
						}
					else
						echo '<td class="boton_superior_gi_vacio">&nbsp;</td>';
					
					if(isset($_SESSION['permisoInternaTaquillas']) && $_SESSION['permisoInternaTaquillas']==true) //Compruebo que se tienen Permiso para acceder a Gest. Int. de Taquillas, si es asi, muestro el boton de acceso, sino no lo muestro
						{
				?>	<td align="center">
						<a href="?pag=gi_taquillas.php" class="botones_superiores_gi" id="boton_t" alt="Gestión de Taquillas" title="Gestión de Taquillas"><div class="boton_superior_gi_taq"></div></a>
					</td>
				<?php
						}
					else
						echo '<td class="boton_superior_gi_vacio">&nbsp;</td>';

					if(isset($_SESSION['permisoUsuarios']) && $_SESSION['permisoUsuarios']==true) //Compruebo que se tienen Permiso para acceder a Gest.  de Usuarios, si es asi, muestro el boton de acceso, sino no lo muestro
						{
				?>	<td align="center">
						<a href="?pag=gi_usuarios.php" class="botones_superiores_gi" id="boton_u" alt="Gestión de Usuarios" title="Gestión de Usuarios"><div class="boton_superior_gi_usu"></div></a>
					</td>
				<?php
						}
					else
						echo '<td class="boton_superior_gi_vacio">&nbsp;</td>';

					if(isset($_SESSION['permisoInternaREAJ']) && $_SESSION['permisoInternaREAJ']==true) //Compruebo que se tienen Permiso para acceder a Gest. Int. de Carnet REAJ, si es asi, muestro el boton de acceso, sino no lo muestro
						{
				?>	<td align="center">
						<a href="?pag=gi_reaj.php"class="botones_superiores_gi" id="boton_c" alt="Gestión de Carnet REAJ" title="Gestión de Carnet REAJ"><div class="boton_superior_gi_carne"></div></a>
					</td>
				
				<?php
						}
					else
						echo '<td class="boton_superior_gi_vacio">&nbsp;</td>';

					if(isset($_SESSION['permisoTarifas']) && $_SESSION['permisoTarifas']==true) //Compruebo que se tienen Permiso para acceder a Gest. de Tarifas, si es asi, muestro el boton de acceso, sino no lo muestro
						{
				?>	<td align="center">
						<a href="principal_gi.php?pag=gi_tarifas.php" class="botones_superiores_gi" id="boton_te" alt="Gestión de Tarifas,Grupos de Edades y Servicios" title="Gestión de Tarifas,Grupos de Edades y Servicios"><div class="boton_superior_gi_tar"></div></a>
					</td>
				<?php
						}
					else
						echo '<td class="boton_superior_gi_vacio">&nbsp;</td>';

					if(isset($_SESSION['permisoInfPolicial']) && $_SESSION['permisoInfPolicial']==true) //Compruebo que se tienen Permiso para acceder a Informe Policial, si es asi, muestro el boton de acceso, sino no lo muestro
						{
				?>	<td align="center">		
						<a href="?pag=gi_informe_policial.php" class="botones_superiores_gi" id="boton_ip" alt="Informe Policial" title="Informe Policial"><div  class="boton_superior_gi_policial"></div></a>
					</td>
				<?php
						}
					else
						echo '<td class="boton_superior_gi_vacio">&nbsp;</td>';

					if((isset($_SESSION['permisoOnLine']) && $_SESSION['permisoOnLine']==true) && (isset($_SESSION['permisoReservas']) && $_SESSION['permisoReservas']==true) && (isset($_SESSION['permisoPeregrino']) && $_SESSION['permisoPeregrino']==true) && (isset($_SESSION['permisoAlberguista']) && $_SESSION['permisoAlberguista']==true) && (isset($_SESSION['permisoGrupos']) && $_SESSION['permisoGrupos']==true) && (isset($_SESSION['permisoFacturacion']) && $_SESSION['permisoFacturacion']==true) && (isset($_SESSION['permisoIncidencias']) && $_SESSION['permisoIncidencias']==true) && (isset($_SESSION['permisoTarifas']) && $_SESSION['permisoTarifas']==true) && (isset($_SESSION['permisoEstadisticas']) && $_SESSION['permisoEstadisticas']==true) && (isset($_SESSION['permisoInternaREAJ']) && $_SESSION['permisoInternaREAJ']==true) && (isset($_SESSION['permisoInfPolicial']) && $_SESSION['permisoInfPolicial']==true) && (isset($_SESSION['permisoREAJ']) && $_SESSION['permisoREAJ']==true) && (isset($_SESSION['permisoUsuarios']) && $_SESSION['permisoUsuarios']==true) && (isset($_SESSION['permisoTaquillas']) && $_SESSION['permisoTaquillas']==true) && (isset($_SESSION['permisoInternaTaquillas']) && $_SESSION['permisoInternaTaquillas']==true) && (isset($_SESSION['permisoHabitaciones']) && $_SESSION['permisoHabitaciones']==true) && (isset($_SESSION['permisoInternaHabitaciones']) && $_SESSION['permisoInternaHabitaciones']==true))//Compruebo que se tienen todos los permisos si es asi, muestro el boton de acceso a Gestion de Datos del Albergue, sino no lo muestro					
						{
				?>	<td align="center">
						<a href="?pag=gi_datosalbergue.php" class="botones_superiores_gi" id="boton_r" alt="Gestión de Datos Albergue" title="Gestión de Datos Albergue"><div class="boton_superior_gi_reaj"></div></a>
						
						
					</td>
				<?php
						}
					else
						echo '<td class="boton_superior_gi_vacio">&nbsp;</td>';
				?>
					<td align="center">
						<a href="?pag=gi_skins.php" class="botones_superiores_gi" id="boton_a" alt="Cambiar Apariencia" title="Cambiar Apariencia"><div class="boton_superior_gi_skin"></div></a>
					</td>
					<td align="center">
						<a href="../Gestion_Principal/index.php?actualizar=true" class="botones_superiores_gi" id="boton_sam" alt="Volver a la página principal" title="Volver a la página principal"><div class="boton_superior_gi_sam"></div></a>
					</td>
							</tr>
						</table>
					</div>
				</div>
				<div id="cuerpo">
					<?
					if(isset($_GET['pag']) && $_GET['pag']!=""){
						include('paginas/'.$_GET['pag']);
					}else{
						include("paginas/gi_skins.php");
					}
				?>
					
				</div>
			</div>
			<div id="pie">
				</div>
		</center>
	</BODY>	
</HTML>
