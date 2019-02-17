<?php
session_start();
?>

<html>
<head>
<title>Imprimir estadistica</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

	<SCRIPT LANGUAGE="JavaScript">
	// esta funcion es para imprimir el grafico que se muestra en la ventana al dar a imprimir
			  function imprimir()
				  {
					   version = parseInt(navigator.appVersion);
					   if (version >= 4)
						 window.print();
				  }
	</SCRIPT>
<?php
if(isset($_SESSION['permisoEstadisticas']) && $_SESSION['permisoEstadisticas']=true)
{
$_SESSION['aspecto'];
?>
		<link rel="stylesheet" type="text/css" href="../css/hoja_formularios.css">
		<link rel="stylesheet" type="text/css" href="../css/habitaciones.css">
		<link rel="stylesheet" type="text/css" href="../css/estilos_tablas.css">
		<link rel="stylesheet" type="text/css" href="../css/hoja_principal_azul.css">

		
</head>


<body style="background-color:white;">
<?php

/* En este punto se incluye la p�gina de valores_estadistica.php ya que es donde se encuentran las variables de sesion que se 
tienen que usar para el correcto funcionamiento de esta p�gina*/

include('../paginas/valores_estadisticas.php');
?>
<table align="left">
<?php	

//Aqui se elige el gr�fico que se va a cargar para imprimir cuando se pulse el bot�n de imprimir la estad�stica que este seleccionada
// en este caso ser�a la estad�stica de grupos de edades

	if($_SESSION['tipo_estadisticas']=="edades")
		{
			echo'<td colspan="7" align="center">';
					include('../paginas/informe_edades.php');
				echo'</td>
			</tr>';
		}
		
// en este caso ser�a la estad�stica de tipo de alberguista
		
	if($_SESSION['tipo_estadisticas']=="t_alb")
		{
			echo'<tr valign="top">';
				echo'<td colspan="7" align="center">';
			
		if( (!isset($_SESSION['grafico'])) || ($_SESSION['grafico']==1) || ($_SESSION['grafico']==3) )
			{
				//por sectores
				echo '<img src="../paginas/grafico_tipo_alberguista_sectores.php" ></td>';
			}
		else
			{	
				//por barras
				echo '<img src="../paginas/grafico_tipo_alberguista_barras.php"></td>';
			}
				echo'</tr>';
		}
		
// en este caso ser�a la estad�stica de pa�s de procedencia
		
	if($_SESSION['tipo_estadisticas']=="pais")
		{
			// este caso es como el de modo de peregrinaci�n donde la elecci�n del gr�fico se realiza dentro de la p�gina de graficos_paises.php
			echo'<tr valign="top">';
				echo'<td colspan="7" align="center">';
					include('../paginas/informe_pais.php');
				echo'</td>
			</tr>';
		}
		
// en este caso ser�a la estad�stica de provincia de procedencia
		
	if($_SESSION['tipo_estadisticas']=="prov")
		{
			// este caso es como el de modo de peregrinaci�n donde la elecci�n del gr�fico se realiza dentro de la p�gina de graficos_provincias.php
			echo'<tr valign="top">';
				echo'<td colspan="7" align="center">';
					include('../paginas/informe_prov.php');
				echo'</td>
			</tr>';
		}
		
// en este caso ser�a la estad�stica que la junta de castilla y le�n va a recibir
		
	if($_SESSION['tipo_estadisticas']=="jcyl")
		{
			/* en este caso lo qeu se hace es un include de la pagina de estad�stica_junta.php ya que la estad�stica de la junta es una tabla
			en vez de un gr�fico*/
			
			echo'<tr valign="top">';
				echo'<td colspan="7" align="center">';
					include('../paginas/estadistica_junta.php');
				echo'</td>
			</tr>';
		}

		
?>
</table>
</body>
	<script>
	
		/* aqui es donde se ejecuta la funci�n de imprimir la estad�stica que este mostrandose 
		en el momento en el que se pulsa el bot�n de imprimir */
		
		 imprimir();
	</script>
</html>
<?php
} //Fin del IF de comprobacion de acceso a la pagina
	else
		 echo "<div>
				<table border='0' id='tabla_detalles'>
					<tr id='titulo_tablas'>
						<td colspan='2'>
							<div style='width: 30px; height: 25px; background-image: url(../imagenes/img_tablas/esquina_arriba_izquierda.jpg); background-repeat: no-repeat; float: left;' id='alerta_esquina_izquierda'>&nbsp;</div>
							<div style='width: 290px; height: 25px; text-align: center; background-image: url(../imagenes/img_tablas/linea_horizontal.jpg); background-repeat: repeat-x; float: left;'>
								<div class='titulo' style='text-align: center;'>�ERROR!</div>
							</div>
							<div style='width: 30px; height: 25px; background-image: url(../imagenes/img_tablas/esquina_arriba_derecha.jpg); float: left;'>&nbsp;</div>
						</td>
					</tr>
					<tr>
						<td width='350' align='center'>
							<label id='texto_detalles' style='color:black; font-weight:bold;'>NO TIENES PERMITIDO ACCEDER A ESTA SECCI�N</label>
						</td>
					</tr>
				</table>
			</div>";
?>