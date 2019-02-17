<?php
	session_start();
?>
	
<?php

	/* En esta página es donde se van a recoger las variables que son de sesion en las páginas de estadistica 
	y es a partir de estas de donde va an a salir la información que se necesita para la construcción de las 
	diferentes páginas que forman parte del subsistema de estadísticas */
	
	
	/* estas variables es para cuando la página se carga por primera vez  para que cargue de inicio la estadística de numero y sexo del mes 
	en el que se este en caso de que no sea la primera vez que se entra, la estadística que se mostrará será la que se reciba por el POST de 
	tipo*/
	 
	if(empty($_SESSION['tipo_estadisticas']))
		{
			$_SESSION['tipo_estadisticas']="num_sex";
			$_SESSION['mes_est']=date("m");
			$_SESSION['anio_est']=date("Y");
		}
	else 
		{
			$_SESSION['tipo_estadisticas']==$_POST['tipo'];
		}
		
	/*Aqui es para que cargue el gráfico de sectores por defecto si no hay ninguno seleccionado, para la primera carga del gráfico, 
	saldrá por defecto el de sectores y si ya hay alguna selecciondo sera esa la que quede seleccionado*/
	
	if(!isset($_POST['grafico']))
		{
			$_SESSION['grafico']=1;
		}
	else
		{
			$_SESSION['grafico']=$_POST['grafico'];
		}
		
	/* Aqui se mira si existe la variable global que es el periodo del cual se desea la estadística, si no existiera si la estadística
	que se elige no es la de la junta de castilla y león que solo puede ser de un mes, el resto por defecto cargaría la opcion mensual, 
	si ya existe la variable de tiempo entonces se cargaría el rango temporal que se haya seleccionado anteriormente*/
	
	if(!isset($_POST['tiempo']))
	{
		if(  (isset($_SESSION['tipo_estadisticas'])) && ($_SESSION['tipo_estadisticas']=='jcyl') )
			{
				$_SESSION['mes_est']=date('m');
				$_SESSION['anio_est']=date('Y');
			}
 		//$_SESSION['tiempo']=2;
	}
	else
	{
		$_SESSION['tiempo']=$_POST['tiempo'];
		if(isset($_POST['mes_ini_est']))
			{
				$_SESSION['mes_est']=$_POST['mes_ini_est'];
				if( ($_SESSION['mes_est'] < 10) )
				{
					// si el mes de inicio de la estadistica es menor de 10 se le añade por defecto un 0 delante del dígito seleccionado
					//$_SESSION['mes_est']="0" .$_SESSION['mes_est'];
				}
				$_SESSION['anio_est']=$_POST['anio_ini_est'];
			}
		else
			{
				$_SESSION['mes_est']=date('m');
				
			}
			/*Aqui se mira si existe la veriable del año de la estadística, si no existe se pone por defecto el año en el que se este, 
			sino se muestra el año que haya sido seleccionado por el usuario*/ 
			
		if(isset($_POST['anio_ini_est']))
			{
				$_SESSION['anio_est']=$_POST['anio_ini_est'];
				$_SESSION['mes_est']=$_POST['mes_ini_est'];
				if( ($_SESSION['mes_est'] < 10) )
				{
					$_SESSION['mes_est']="0" .$_SESSION['mes_est'];
				}
			}
		else
			{
				$_SESSION['anio_est']=date('Y');
			}
	}
	
	if(isset($_POST['tipo']))
	{
		$_SESSION['tipo_estadisticas']=$_POST['tipo'];
	}
	
	/*Aqui se contempla lo que se va a cargar de inicio si la estadística que se usa es la de la junta de castilla y leon,
	en la cual si el mes es igual a 0 se carga el mes actual por defecto, sino si exite el mes de inicio y este no es vacio
	el mes de la estadística es el que se haya seleccionado y si es vacío se cargará el mes en el que se este en ese momento,
	y lo mismo se hará con el año de la estadiística*/
	
	if( (isset($_SESSION['tipo_estadisticas'])) && ($_SESSION['tipo_estadisticas']=='jcyl') )
	{
		if($_SESSION['mes_ini_est']==0)
		{
			$_SESSION['mes_est']=date("m");
			
		}
		else
		{	
			if(  (isset($_POST['mes_ini_est'])) && ($_POST['mes_ini_est']!="") )
			{
				$_SESSION['mes_est']=$_POST['mes_ini_est'];
				
			}
			else
			{
				$_SESSION['mes_est']=date('m');
				
			}
		}
		if(empty($_SESSION['anio_est']))
			{
				$_SESSION['anio_est']=date("Y");
			}
		else
			{
				$_SESSION['anio_est']=$_POST['anio_ini_est'];
			}
	}
	
	if($_SESSION['mes_est']==0)
	{
		$_SESSION['mes_est']=date("m");
		
	}
	
	// Aqui se crea el array del que se van a sacar los nombres de los meses del año para los títulos de las estadísticas
	$meses_est=array(" ","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		
		//esta variable corresponde al año desde el que se pueden sacar estadísticas 
	$anyo_inicio_albergue=2004;
	$long_mes_est=count($meses_est);
	
	/* aqui se crea la variable global con el año desde el que se pueden sacar las estadísticas para su posterior uso en las demás páginas, 
	y otra variable global con la fecha del dia en el que se este*/
		
	$_SESSION['anyo_inicio_albergue']=$anyo_inicio_albergue;	
	$_SESSION['fecha_de_hoy']=$anyo_est."-".$mes_est."-".$dia_est;
	
?>

