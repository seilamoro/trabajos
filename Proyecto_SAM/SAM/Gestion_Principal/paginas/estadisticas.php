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
	$_SESSION['inf_graf']['prov']=1;//si vale 1 se muetsra el informe, si vale 2 se muestra la gr�fica

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

//el siguiente campo oculto, ke viene de la p�gina tablas_estadisticas.php se usa para indicar en tabla_periodicidad.php si la opci�n a mostrar es el informe o la gr�fica  
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

	/*Esta es la p�gina principal de la estad�sticas y es donde se incluyen todas las p�ginas que se usan para mostrar toda la informaci�n 
	que se requiere en el subsistema de estad�sticas, tanto los gr�ficos como la tabla para cambiar de un gr�fico a otro y asi como para 
	cambiar el tipo de gr�fico requerido, y su rango temporal, como tablas que aclaran ciertos gr�ficos */

// Aqui se incluyen las variables de sesion que se usan para el resto de la p�gina
include('./paginas/valores_estadisticas.php');
?>

<div id="caja_superior" border="1">
	
  <div  style="position:relative;"> 
    <?
	
			/*Aqui se carga la p�gina con los botones para seleccionar el tipo de gr�fico que se desea asi como el la imagen del gr�fico
			seleccionado*/
			include('./paginas/tabla_estadisticas.php');
	
		?>
  </div>
			
	
  <div > 
    <div style="position:relative; top:-06px; left:30px;"> 
      <?
	  			/*Esta p�gina que se incluye aqui es donde se elige el gr�fico que se quiere asi como el rango temporal del que se desea
				la estad�stica y tambi�n se le da la opci�n a trav�s de esta p�gina de imprimir el gr�fico que se muestre en la p�gina*/
				include('./paginas/tabla_periodicidad.php');
		?>
    </div>
    <div style="position:relative; left:30px; top:-06px;"> 
      <?php 
	  
	  		/*Aqui se muestra una tabla en la que se muestra el nombre del pa�s al que corresponde la ide del pa�s que sale en el 
			gr�fico de pa�ses y esta p�gina solo se muestra cuando esta seleccionado el gr�fico de paises de procedencia*/
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
				NO TIENE PERMISOS PARA ACCEDER A ESTA P�GINA
			</div>";
	}
//echo "<BR>" .$_SESSION['anio_inicio'];
//echo "<BR>" .$_SESSION['mes_inicio'];
//echo "<BR>Tipo Cliebnte:" .$_SESSION['tipo_cliente'];
//echo "<BR>Perido:" .$_SESSION['periodicidad'];
?>