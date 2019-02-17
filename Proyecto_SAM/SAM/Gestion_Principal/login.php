<?PHP session_start();?>
<?PHP 
// Archivo de configuración de las conexiones al servidor de bbdd.
include './config.inc.php';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
	<TITLE> Sistema de Gestión del Albergue Municipal</TITLE>
	<META NAME="Generator" CONTENT="EditPlus">
	<META NAME="Author" CONTENT="Elena Matilla Álvarez">
	<?php include ("paginas/estilos_index.php");?>
	<script language="javascript">
	
	//busca caracteres que no sean espacio en blanco en una cadena. (Elena)
	function vacio(q) {
	        for ( i = 0; i < q.length; i++ ) {
	                if ( q.charAt(i) != " " ) {
	                    return true
	                }
	        }
	        return false
	}	
	//valida que el campo no este vacio y no tenga solo espacios en blanco. (Elena)
	function valida(F){
	        
	        if( vacio(F.login.value) == false ) {
	            window.location.href="login.php?vacio=yes";	            
				return false
	        } else {
			  if( vacio(F.pass.value) == false ) {
	                window.location.href="login.php?vacio=yes";	                
	                return false
	        } else {
	                //la contraseña no es vacia
	                return true
	        }	               
	                //el usuario no es vacio
	                return true
	        }
  
	}
	//funcion que te envia a index directamente sin cambiar nada de la session, porq ha sido pulsado el boton cancelar.(Elena)
	function irindex(form_login){
		this.form_login.action="index.php";
	}
	//funcion que cierra la ventana. (Elena)
	function cerrarventana(){
		window.opener=true;
		window.close();
	}
	</script>

</HEAD>
<BODY>
	
	<center>
		<div id="parent">
			<div id="cabecera">
				<div id="cab1">
					<span style="font-size:40px;color:#f3f3f3;float:left;padding-left:50px;font-family:Verdana;">Albergue Municipal de León</span>
				</div>										
			</div>		
			<div id="contenido">	
				<div id="div_login">
					<div id="cabecera_login">
						Login
					</div>
					<form name="form_login" action="logindex.php" method="POST" >
					<div id="contenido_login" >			
					<?php
					//cuando se validan los datos en la base de datos y no son correctos, según cual sea el fallo se recarga la página y se le muestra el mensaje correspondiente según el parámetro que se reciba
					if($usu)
					{//si se ha recibido la variable $usu, se muestra este mensaje error
						?>
						<span class="error" style="position:relative;left:7px;top:95px;">Usuario incorrecto </span>	
						<?php
						unset($_SESSION['aspecto']);//se vacia la variable de session del aspecto
						unset($_SESSION['usuario']);//se vacia la variable de session de usuario
						unset($_SESSION['pass']);//se vacia la variable de session de contraseña
						$_SESSION['logged']=false;//se vacia la variable de session de logeado
						$_SESSION['cont']=$_SESSION['cont']+1;	//aumentamos el valor de las variable de session cont
						
						
					}
					if($pa)
					{	//si se ha recibido la variable $pa, se muestra este mensaje error
						?>
						<span class="error" style="position:relative;left:7px;top:95px;">Contraseña incorrecta </span>
						<?php
						unset($_SESSION['aspecto']);//se vacia la variable de session del aspecto
						unset($_SESSION['usuario']);//se vacia la variable de session de usuario
						unset($_SESSION['pass']);//se vacia la variable de session de contraseña
						$_SESSION['logged']=false;//se vacia la variable de session de logeado
						$_SESSION['cont']=$_SESSION['cont']+1;	//aumentamos el valor de las variable de session cont
						
						
					}
					if($vacio)
					{	//si se ha recibido la variable $vacio, se muestra este mensaje error
						?>						
						<span class="error" style="position:relative;left:7px;top:95px;">Rellene Usuario y Contraseña </span>	
						<?php
						unset($_SESSION['aspecto']);//se vacia la variable de session del aspecto
						unset($_SESSION['usuario']);//se vacia la variable de session de usuario
						unset($_SESSION['pass']);//se vacia la variable de session de contraseña
						$_SESSION['logged']=false;//se vacia la variable de session de logeado
					}
					if($bd)
					{	//si se ha recibido la variable $bd, se muestra este mensaje error
						unset($_SESSION['aspecto']);//se vacia la variable de session del aspecto
						unset($_SESSION['usuario']);//se vacia la variable de session de usuario
						unset($_SESSION['pass']);//se vacia la variable de session de contraseña
						$_SESSION['logged']=false;//se vacia la variable de session de logeado
						?>						
						<span class="error" style="position:relative;left:7px;top:95px;">Error conexión a base de datos</span>	
						<?php
					}	
					else{
					?>  <span class="error" style="position:relative;left:7px;top:95px;"> &nbsp;</span>										
					<?php
					}			
					
					if($tres)//comprobacion de intentos de acceso al sistema por el usuario.
					{//si existe la variable $tres, se mostrará el siguiente mensage de error y un boton de acceptar, que al pulsuar cerrará la ventana del sistema
					  ?>
						<div id="contenido_login" >
							<div id="id_login" >
					  		<span class="error" style="position:relative;left:7px;top:0px;">Ha superado el número de intentos en el sistema.<br>A continuación la aplicación se cerrará.</span>
					  		</div>
					  	</div>
				 		<div id="div_submit_login">
		<?php				echo "<input type='image' name='boton_enviar' border='0' src='../imagenes/botones-texto/aceptar.jpg' alt='Pulse para entrar' title='Pulse para entrar' onclick='cerrarventana();'>";  
		?>				</div>														  
		<?php
					}				
					else{
					//si no existe se mostrará el formulario de inserccion de datos de usuario
					//echo"aspecto:".$_SESSION['aspecto'];
		?>			
										
						
							<div id="id_login" >
								<span style="float:left;margin-top:3px;">
									<label class="label_formulario" >Usuario : </label>
								</span>	
								<input class="input_formulario" type="text" name="login" style="float:right;">						
							</div>
							<span style="clear:both;"></span>
							<div id="pass_login">
								<span style="float:left;margin-top:3px;"><label class="label_formulario" style="padding-top:10px;">Contraseña : </label></span>
								<input class="input_formulario" type="password" name="pass" style="float:right;"><br><br>
							</div><br>						
							<div id="div_submit_login">
							<?php							
								if($_GET['cambiar']){	//si se viene desde las paginas del sistema, y se desea cambiar de usuario se mostraran los siguientes botones						  	
									echo "<input type='image' name='boton_enviar' border='0' src='../imagenes/botones-texto/aceptar.jpg' alt='Pulse para entrar' title='Pulse para entrar' onclick='return valida(form_login);'>"; 
									//boton de aceptar que comprueba que se hayan introducido datos en los campos. Si devuelve true envia a la pagina del action del formulario (logindex.php)
									?>
									&nbsp;
									<?php
									echo "<input type='image' name='boton_cancelar' border='0' src='../imagenes/botones-texto/cancelar.jpg' alt='Pulse para entrar' title='Pulse para cancelar' onclick='irindex(form_login);'>";
									//boton de cancelar que devuelve al sistema principal con el mismo usuario y sus permisos con que nos encontrabamos.									
									$_SESSION['cont']=1;
								}else{//si no se viene del sistema. Se esta intentando entrar en él.
									echo "<input type='image' name='boton_enviar' border='0' src='../imagenes/botones-texto/aceptar.jpg' alt='Pulse para entrar' title='Pulse para entrar' onclick='return valida(form_login);'>";  
									//boton de aceptar que comprueba que se hayan introducido datos en los campos. Si devuelve true envia a la pagina del action del formulario (logindex.php)									
								}								
							?>
							</div>
						</div>
						</form>
				<?	}	?>
					<script language='javascript'>
						document.form_login.login.focus();//se le da el foco al campo de login.
					</script>
				</div>				
			</div>
		</div>
		<div id="pie">
			<div id='pie_pagina'></div>
		</div>
	
	</center>	
</BODY>
</HTML>
