<?PHP
//Archivo de configuraci�n de conexi�n a la Base de Datos

// $_SESSION['conexion']['host'] = '<Nombre del servidor que contiene la base de datos>'
$_SESSION['conexion']['host'] = 'localhost';

// $_SESSION['conexion']['user'] = '<Usuario de la base de datos>'
$_SESSION['conexion']['user'] = 'root';

// $_SESSION['conexion']['pass'] = '<Contrase�a del usuario de la base de datos>'
$_SESSION['conexion']['pass'] = '';

// $_SESSION['conexion']['db'] = '<Nombre de la base de datos>'
$_SESSION['conexion']['db'] = 'sam';

/*
Ejemplo de conexi�n:
$db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
	
Ejemplo de selecci�n de base de datos:
MYSQL_SELECT_DB($_SESSION['conexion']['db']);
*/


//Configuraci�n del correo smtp

// $_SESSION['email_online']['servidor'] = '<Nombre del servidor de correo smtp>';
$_SESSION['email_online']['servidor'] = 'servidor.es';

// $_SESSION['email_online']['usuario'] = '<Usuario del correo smtp>';
$_SESSION['email_online']['usuario'] = 'server@servidor.es';

// $_SESSION['email_online']['password'] = '<Contrase�a del correo smtp>';
$_SESSION['email_online']['password'] = 'test123';

?>
