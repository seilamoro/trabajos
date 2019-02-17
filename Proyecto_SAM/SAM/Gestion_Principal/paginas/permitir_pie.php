<div> 
<?php
	if((isset($_SESSION['permisoOnLine']) && $_SESSION['permisoOnLine']==true) || (isset($_SESSION['permisoReservas']) && $_SESSION['permisoReservas']==true) || (isset($_SESSION['permisoPeregrino']) && $_SESSION['permisoPeregrino']==true) || (isset($_SESSION['permisoAlberguista']) && $_SESSION['permisoAlberguista']==true) || (isset($_SESSION['permisoGrupos']) && $_SESSION['permisoGrupos']==true) || (isset($_SESSION['permisoFacturacion']) && $_SESSION['permisoFacturacion']==true) || (isset($_SESSION['permisoIncidencias']) && $_SESSION['permisoIncidencias']==true) || (isset($_SESSION['permisoREAJ']) && $_SESSION['permisoREAJ']==true))//Compruebo que se tienen todos los permisos si es asi, muestro el boton de acceso a Gestion de Datos del Albergue, sino no lo muestro
		{
?>
	<span>
		<a href="?pag=busq.php&busqueda=true" >
			<img border="0" class="img_down" height="35px" width="35px" src="../imagenes/skins-<?echo $_SESSION['aspecto']?>/busquedas.gif" alt='Búsquedas'>
		</a>
	</span>
<?php
		}
?>
<span>
	<a href="?pag=listados.php" >
		<img border="0" class="img_down" height="35px" width="35px" src="../imagenes/skins-<?echo $_SESSION['aspecto']?>/listados.gif" alt='Listados'>
	</a>
</span>
<?php
	if(isset($_SESSION['permisoEstadisticas']) && $_SESSION['permisoEstadisticas']==true)
	//if($_SESSION['logged'] == "true")
	{
?>
		<span><A href=".?pag=estadisticas.php"><img class="img_down" id="img_est" border=0 src="../imagenes/skins-<?echo $_SESSION['aspecto']?>/estadisticas.gif"  height="35px" width="35px" alt='Estadísticas'></a></span>
<?php
	}
	if(isset($_SESSION['permisoIncidencias']) && $_SESSION['permisoIncidencias']==true)
	//if($_SESSION['logged'] == "true")
	{
?>		  
		<span><a href=".?pag=incidencias.php"><img border=0 class="img_down" id="img_inc" src="../imagenes/skins-<?echo $_SESSION['aspecto']?>/incidencias.gif"  height="35px" width="35px" alt='Incidencias'> </a></span>
<?php
	}
	if(isset($_SESSION['permisoREAJ']) && $_SESSION['permisoREAJ']==true)
	//if($_SESSION['logged'] == "true")
	{
?>		  
		<span ><a href=".?pag=reaj.php"><img class="img_down" id="img_reaj" src="../imagenes/skins-<?echo $_SESSION['aspecto']?>/reaj.gif"  height="35px" width="35px"  alt='REAJ' border=0></a></span> 
<?php
	}
?>
</div>
