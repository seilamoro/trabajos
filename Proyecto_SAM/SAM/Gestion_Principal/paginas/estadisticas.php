<?php
session_start();
?>

<?php

if(!isset($_SESSION['tipo_estadisticas']))
{
	$_SESSION['tipo_estadisticas']='jcyl';
	$_SESSION['inf_jcyl']=1;
}
if( (!isset($_SESSION['periodicidad'])) &&(!isset($_POST['tiempo'])) )
{
	$_SESSION['periodicidad']=2;
	$_SESSION['tiempo']=2;
}
if( (!isset($_SESSION['tipo_cliente'])) &&(!isset($_POST['tipo_cliente'])) )
	$_SESSION['tipo_cliente']=1;
if( (!isset($_SESSION['mes_inicio'])) &&(!isset($_POST['mes_ini_est'])) )
	$_SESSION['mes_inicio']=date("m");
if( (!isset($_SESSION['anio_inicio'])) &&(!isset($_POST['anio_ini_est'])) )
	$_SESSION['anio_inicio']=date("Y");

if(!isset($_SESSION['inf_graf']['prov']))
	$_SESSION['inf_graf']['prov']=1;//si vale 1 se muetsra el informe, si vale 2 se muestra la gráfica

if(!isset($_SESSION['inf_graf']['pais']))
	$_SESSION['inf_graf']['pais']=1;

if(!isset($_SESSION['inf_graf']['ccaa']))
	$_SESSION['inf_graf']['ccaa']=1;

if(!isset($_SESSION['inf_graf']['edades']))
	$_SESSION['inf_graf']['edades']=1;


if(isset($_POST['mes_ini_est']))
{
	$_SESSION['mes_inicio']=$_POST['mes_ini_est'];
	$_SESSION['mes_est']=$_POST['mes_ini_est'];
}
if(isset($_POST['anio_ini_est']))
{
	$_SESSION['anio_inicio']=$_POST['anio_ini_est'];
	$_SESSION['anio_est']=$_POST['anio_ini_est'];

}
if(isset($_POST['tiempo']))
{
	$_SESSION['periodicidad']=$_POST['tiempo'];
	$_SESSION['tiempo']=$_POST['tiempo'];
}
if(isset($_POST['tipo_cliente']))
	$_SESSION['tipo_cliente']=$_POST['tipo_cliente'];

//el siguiente campo oculto, ke viene de la página tablas_estadisticas.php se usa para indicar en tabla_periodicidad.php si la opción a mostrar es el informe o la gráfica  
if( (isset($_POST['num_oculto'])) && ($_POST['num_oculto']==2) )
{
	switch($_POST['tipo'])
	{
		case 'edades':
					$_SESSION['inf_graf']['edades']=2;
					$_SESSION['inf_jcyl']=0;
					break;
		case 'prov':
					$_SESSION['inf_graf']['prov']=2;
					$_SESSION['inf_jcyl']=0;
					break;
		case 'pais':
					$_SESSION['inf_graf']['pais']=2;
					$_SESSION['inf_jcyl']=0;
					break;
		case 'ccaa':
					$_SESSION['inf_graf']['ccaa']=2;
					$_SESSION['inf_jcyl']=0;
					break;
		$_SESSION['inf_jcyl']=0;
	}
}
if( (isset($_POST['num_oculto'])) && ($_POST['num_oculto']==1) )
{
	if($_POST['tipo'] != 'jcyl' )
	{
		$_SESSION['inf_jcyl']=0;
	}
}

if(isset($_POST['ver_informe']))
{
	switch($_SESSION['tipo_estadisticas'])
	{
		case 'edades':
					$_SESSION['inf_graf']['edades']=1;
		case 'prov':
					$_SESSION['inf_graf']['prov']=1;
					break;
		case 'pais':
					$_SESSION['inf_graf']['pais']=1;
					break;
		case 'ccaa':
					$_SESSION['inf_graf']['ccaa']=1;
					break;

	}
}
$_SESSION['mes_inicio']=SUBSTR($_SESSION['mes_inicio'],-2);
//echo "UUUUIIII" .$_SESSION['anio_est'];
//echo "<br>" .$_SESSION['mes_est'];
//echo "<br>" .$_SESSION['tiempo'];

if(isset($_SESSION['permisoEstadisticas']) && $_SESSION['permisoEstadisticas']=true)
{

	/*Esta es la página principal de la estadísticas y es donde se incluyen todas las páginas que se usan para mostrar toda la información 
	que se requiere en el subsistema de estadísticas, tanto los gráficos como la tabla para cambiar de un gráfico a otro y asi como para 
	cambiar el tipo de gráfico requerido, y su rango temporal, como tablas que aclaran ciertos gráficos */

// Aqui se incluyen las variables de sesion que se usan para el resto de la página
include('./paginas/valores_estadisticas.php');
?>

<div id="caja_superior" border="1">
	
  <div  style="position:relative;"> 
    <?
	
			/*Aqui se carga la página con los botones para seleccionar el tipo de gráfico que se desea asi como el la imagen del gráfico
			seleccionado*/
			include('./paginas/tabla_estadisticas.php');
	
		?>
  </div>
			
	
  <div > 
    <div style="position:relative; top:-06px; left:30px;"> 
      <?
	  			/*Esta página que se incluye aqui es donde se elige el gráfico que se quiere asi como el rango temporal del que se desea
				la estadística y también se le da la opción a través de esta página de imprimir el gráfico que se muestre en la página*/
				include('./paginas/tabla_periodicidad.php');
		?>
    </div>
    <div style="position:relative; left:30px; top:-06px;"> 
      <?php 
	  
	  		/*Aqui se muestra una tabla en la que se muestra el nombre del país al que corresponde la ide del país que sale en el 
			gráfico de países y esta página solo se muestra cuando esta seleccionado el gráfico de paises de procedencia*/
			include('./paginas/tabla_pais.php');
		?>
    </div>
  </div>
  
</div>


<?php
} //Fin del IF de comprobacion de acceso a la pagina
	else
	{
		 echo "<div class='error'>
				NO TIENE PERMISOS PARA ACCEDER A ESTA PÁGINA
			</div>";
	}
//echo "<BR>" .$_SESSION['anio_inicio'];
//echo "<BR>" .$_SESSION['mes_inicio'];
//echo "<BR>Tipo Cliebnte:" .$_SESSION['tipo_cliente'];
//echo "<BR>Perido:" .$_SESSION['periodicidad'];
?>