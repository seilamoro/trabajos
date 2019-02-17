<?PHP session_start();?>
<HTML>
	<center>
<HEAD>
	<TITLE> SCIES </TITLE>
	<META NAME="Generator" CONTENT="EditPlus">
	<META NAME="Author" CONTENT="Elena Matilla Álvarez">	
		<link rel="StyleSheet" href="css/principal.css" type="text/css">
		<link rel="StyleSheet" href="css/listados.css" type="text/css">
		<link rel="stylesheet" href="css/menu.css" type="text/css">
		<link rel="stylesheet" href="css/menu_principal.css" type="text/css">
		<link rel="StyleSheet" href="css/pestayas.css" type="text/css">
    	<link rel="StyleSheet" href="css/menu.css" type="text/css">
		
</HEAD>


<BODY style ="background-image:url(imagenes/fondos/fondo_paginas.jpg);" onLoad="ajustarMenuTamanio()">


	<?php	include('paginas/menu.php');?>

<div style="margin-top:80px;" >
<?php
if(isset($_GET['pag']) && $_GET['pag']!=""){//si existe una pagina para cargar y no esta vacia
	include('paginas/'.$_GET['pag']);//incluimos el contenido de la pagina contenida en la variable	
	
}else{		//  echo"aspecto:".$_SESSION['aspecto'];				
	include('paginas/informacion.php');//incluimos el contenido de la pagina gdh.php
}
?>
</div>




</BODY> </center>
</HTML>