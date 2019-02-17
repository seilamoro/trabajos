<?PHP session_start();?>

<?PHP 

// Archivo de configuración de las conexiones al servidor de bbdd.
include './config.inc.php';

?>

<?php

if(!isset($_SESSION['logged'])){//si no existe la session de logged 
  include('login.php');//cargamos la pagina de login.php
}
else {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<TITLE> Sistema de Gestión del Albergue Municipal</TITLE>
	<META NAME="Generator" CONTENT="EditPlus">
	<META NAME="Author" CONTENT="Elena Matilla Álvarez">
	<script src="orden.js" type="text/javascript"></script>
<?php
	// Inicio Actualización de la Página (Javier Castro)
	if(isset($_GET['actualizar']) && $_GET['actualizar']==true)//si existe actualizar y tiene valor true
	{
		$_GET['actualizar']=false;//le cambio el valor a false
		unset($_GET['actualizar']);//se vacia la variable
		echo '<meta http-equiv="refresh" content="0;url=.">';//actualizar la página
	}
	//Fin Actualización de la Página 
?>
<?php
		if(!file_exists ("css/habitacionesColores.css"))//Comprueba si existe la CSS habitacionesColores.css
			copy ("css/habitacionesColores.inic", "css/habitacionesColores.css")//copia lo q contiene habtitacionesColores.inic en habitacionesColores.css
?>
	
	<?PHP
	if($_SESSION['logged'] == true){//si estamos loggeados
		include ("paginas/permitir.inc");//incluidmos el contenido de la pagina de permisos de acceso permitir.inc
	}?>
	<?php include ("paginas/estilos_index.php");/*incluimos los estilos para el index de la pagina estilos_index.php*/?>
	<?php
		if(!isset($_SESSION['pag_hab'])){//si no esta creada aun la session de pag_hab
			include('paginas/file_conf_skins.php');	//incluimos el contenido de la pagina file_conf_skins.php
		}
	?>	
</HEAD>
<BODY>
	<center>
		<div id="parent">
			<div id="cabecera">
                <?php include('paginas/calendario.php');/*Incluye el contenido del calendario*/ ?>
				<div id="cab1">						
					<?php
						if($_SESSION['logged'] == true){//si estamos loggeados
					?>
					<div id="caja_botones_sup"><!--permitir que se muestren los botones de superiores de la izquierda-->
						<?php include('paginas/permitir_caja_botones_sup.php');/*incluimos el contenido de la pagina permitir_caja_botones.php*/?>	
					</div>
					<div id="subtitulos_superior">
						<?php include('paginas/permitir_sub_superior.php');/*incluimos el contenido de la pagina de permitir_sub_superior.php*/ ?>					
					</div>
				</div>
				<div class='cab_derecha'><!-- EMPIEZA CABECERA DERECHA -->
					<!-- EMPIEZA TABLA ALERTAS -->
					<div id='caja_alertas'>
					<?php include('paginas/alertas.php');?>
					</div>
					<!-- ACABA TABLA ALERTAS -->								
					<div id="gestion_interna"><!-- EMPIEZA GESTION INTERNA -->												<div style='margin-left:25px;'>
							<A href='./../Gestion_Interna/principal_gi.php'><IMG src='../imagenes/skins-<?echo $_SESSION['aspecto']?>/gestion_interna.gif' border=0 alt='Gestión Interna'></A>
							<A href='login.php?cambiar=true'><IMG src='../imagenes/skins-<?echo $_SESSION['aspecto']?>/cambiar_usuario.gif' border=0 alt='Cambiar Usuario'></A>					
						</div>
						<div id="iconos_pie"><!-- EMPIEZA BOTONES DERECHA SUPERIOR-->	
							<div>														
								<?php include('paginas/permitir_pie.php');/*incluimos el contenido de la pagina permitir_pie.php. botones de*/?>
							</div>	
						</div><!-- ACABA BOTONES DERECHA SUPERIOR -->
					</div><!-- ACABA GESTION INTERNA -->
				</div><!-- ACABA CABECERA DERECHA -->				
				<?php	}?>				
			</div><!--cierro CABECERA-->			
			<div id="contenido">			
				<?
				if($_SESSION['logged'] == true){//si estamos logeados
					if(isset($_GET['pag']) && $_GET['pag']!=""){//si existe una pagina para cargar y no esta vacia
						include('paginas/'.$_GET['pag']);//incluimos el contenido de la pagina contenida en la variable
					}else{		//  echo"aspecto:".$_SESSION['aspecto'];				
						include('paginas/gdh.php');//incluimos el contenido de la pagina gdh.php
					}
				}
				else{
				  	//se muestra pantalla de login 
				  	?>					  
					<script language='javascript'>
						//se recarga la página de login 
						window.location.href="login.php";
					</script>	
					<?
				}
				?>				
			</div>
			<div id="pie" >
				<div id='pie_pagina'></div>
			</div>	
		</div>
	</center>	
</BODY>
</HTML>
<?php
}
?>