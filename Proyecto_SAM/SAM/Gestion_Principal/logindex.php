<?PHP session_start();?>
<?
$login=$_POST['login'];//usuario que intenta acceder al sistema
$pass=$_POST['pass']; //contraseña con la que el usuario intenta acceder al sistema

if(!isset($_SESSION['cont']))//si no se a creado aun la session de cont se le da valor uno(primer intento realizado)
{
  $_SESSION['cont']=1;//variable que contabilizará los intentos de acceso al sistema del usuario
}
//------if($_SESSION['cont']<=2){//si el numero de intentos de acceso es < o = a 2 podemos seguir intentandolo
/*Inicio conexion bd*/
	@$db  = mysql_pconnect($_SESSION['conexion']['host'], $_SESSION['conexion']['user'], $_SESSION['conexion']['pass']);
	if (!$db)
	{		
		?>
			<script language='javascript'>
				//se recarga la página de login con el parámetro $msg2 para mostrar el mensaje correspondiente
				window.location.href="login.php?bd=yes";
			</script>
			<?php
		exit;
	}
	mysql_select_db($_SESSION['conexion']['db'],$db);	
/*Fin inicio conexion bd*/
	$sql="SELECT COUNT(*) as num FROM usuario WHERE usuario = '" .$login ."';";
	$resul=mysql_query($sql);
	for($i=0;$i<mysql_num_rows($resul);$i++)
	{
		$fila=mysql_fetch_array($resul);
		$permiso=$fila['num'];
		//valdrá 1 si ecuentra una fila coincidente(una como máximo ya que el campo usuario es clave primaria) y cero si el usuario no existe en la base de datos
	}
	if($permiso==1)//si se a encontrado algun usuario con ese nombre de login
	{	
		$sql2="SELECT COUNT(*) as num2 FROM usuario WHERE usuario = '" .$login ."' AND password = PASSWORD('" .$pass ."');";
		$resul2=mysql_query($sql2);
		for($i=0;$i<mysql_num_rows($resul2);$i++)
		{
			$fila2=mysql_fetch_array($resul2);
			$permiso2=$fila2['num2'];
			//valdrá 1 si ecuentra una fila coincidente(una como máximo ya que el campo usuario es clave primaria) y cero si el usuario no existe en la base de datos
		}
		if($permiso2==1)
		{	 
		  	$sql_aspecto="select * from usuario where usuario= '" .$login ."' AND password = PASSWORD('" .$pass ."');";	
		  	$resul_aspecto=mysql_query($sql_aspecto);
			for($i=0;$i<mysql_num_rows($resul_aspecto);$i++)
			{
				$fila_aspecto=mysql_fetch_array($resul_aspecto);
				$aspecto=$fila_aspecto['Skin'];
				//valdrá 1 si ecuentra una fila coincidente(una como máximo ya que el campo usuario es clave primaria) y cero si el usuario no existe en la base de datos
			}
			$_SESSION=array();//vacio las variables de session
			$_SESSION['aspecto']=$aspecto;//aspecto seleccionado por el usuario
			$_SESSION['usuario']=$login;//damos valor al usuario de session				
			$_SESSION['pass']=$pass;//damos valor a la contraseña de session				
			$_SESSION['logged']=true;//avisamos de que estamos loggeados, para tener en cuenta los permisos.			
			$_SESSION['cont']=0;//ponemos a cero el valor de intentos de acceso al sistema.					
			?>
			<script language='javascript'>
				//se recarga la página de login con el parámetro $msg2 para mostrar el mensaje correspondiente
				window.location.href="index.php";
			</script>
			<?php
			
		}
		else //usuario bien pero contraseña mal 
		{
		  	
			if($_SESSION['cont']<=2){		
			?>
			<script language='javascript'>
				//se recarga la página de login con el parámetro $msg2 para mostrar el mensaje correspondiente
				window.location.href="login.php?pa=yes";//$pa hace referencia al password
			</script>
			<?php
			}
			else{
			?>
			<script language='javascript'>
				//se recarga la página de login con el parámetro $msg2 para mostrar el mensaje correspondiente
				window.location.href="login.php?tres=yes";
			</script>
			<?php  
			}
		
		}
		mysql_close($db);
	}
	else{//usuario mal		
	  	
	  	if($_SESSION['cont']<2){		
	  	?>
		<script language='javascript'>
			//se recarga la página de login con el parámetro $msg2 para mostrar el mensaje correspondiente
			window.location.href="login.php?usu=yes";
		</script>
		<?php
		}
		else{
		?>
		<script language='javascript'>
			//se recarga la página de login con el parámetro $msg2 para mostrar el mensaje correspondiente
			window.location.href="login.php?tres=yes";
		</script>
		<?php  
		}
	}	  

?>
