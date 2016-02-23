<?PHP session_start();

//miro lo de los privilegios
 if(!isset($_SESSION['password'])){//sinohay session y en el campo session
 
 }

?>
 <style type="text/css">




	div.contenedor {
		position: relarive;
		width: 30px;
	}
	
	.estiloinput {
		font-family: Arial;
		color: #008585;
		font-size: 8pt;
		border: 1px solid #008585;
		padding-left: 3px;
		width: 130px;
	}

	div.fill {
		font-family: Arial;
		font-size: 8pt;
		display: none;
		width: 128px;
		position:relative;
		color: #E0EBEB;
		background-color: #E0EBEB;
		border: 1px solid #008585;
		overflow: auto;
		height: 150px;
		top: -1px;
	}

	.estilotr.fill {
		font-family: Arial;
		font-size: 8pt;
		color: #E0EBEB;
		background-color: #008585;
		border: 1px solid #008585;
	}

	.estilotr {
		font-family: Arial;
		font-size: 8pt;
		background-color: #E0EBEB;
		color: #008585;
		border: 1px solid #E0EBEB;
	}
	
</style>
<html>
	<head>
 

   <script type="text/javascript" src="./js/bsn.AutoSuggest_c_2.0.js"></script>

<link rel="stylesheet" href="./css/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" />
    <script>
		function logear(){
			document.getElementById("pag").value="login.php";
			alert(document.getElementById("pag").value);
			document.getElementById("formu").submit();
		}
	</script>
    </head>
    <body>

 
    	<table  width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" align="center">
        	<tr height="100">
            	<td  bgcolor="#FF9900" colspan="4" align="center">
                <font color="#FFFFFF" style="font-family:Arial, Helvetica, sans-serif; font-size:33px" ><b>PROTECCION CIVIL VILLAQUILAMBRE</b></font>
                </td>
             </tr>
             
             <tr  height="25" >
             	
               <?
              if(!isset($_SESSION['password'])){
			  ?>
              <td bgcolor="#FF9900" colspan="1" align="left">
              	<a  href="index.php?pag=listado.php" style="text-decoration:none"> <font color="#FFFFFF" style="font-family:Arial, Helvetica, sans-serif; font-size:14px"><b>LISTADO</b></font></a></td>

<td bgcolor="#FF9900" colspan="1" align="left">
              	<a  href="index.php?pag=ingresar_horas.php" style="text-decoration:none"> <font color="#FFFFFF" style="font-family:Arial, Helvetica, sans-serif; font-size:14px"><b>HORAS</b></font></a></td>
			  <td bgcolor="#FF9900" colspan="1" align="right">
              <a  href="index.php?pag=login.php" style="text-decoration:none"> <font color="#FFFFFF" style="font-family:Arial, Helvetica, sans-serif; font-size:14px"><b>LOGIN</b></font></a>
                
                </td>
			  <?
				}else{
               ?> 
               <td bgcolor="#FF9900" >
              <a href="index.php?pag=insertar.php" style="text-decoration:none"> <font color="#FFFFFF" style="font-family:Arial, Helvetica, sans-serif; font-size:14px"><b>INSERTAR</b></font></a>
                
                </td>
                <td bgcolor="#FF9900" >
              <a href="index.php?pag=listado.php" style="text-decoration:none"> <font color="#FFFFFF" style="font-family:Arial, Helvetica, sans-serif; font-size:14px"><b>LISTADO</b></font></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;
              <a href="index.php?pag=cambiar_contra.php" style="text-decoration:none"> <font color="#FFFFFF" style="font-family:Arial, Helvetica, sans-serif; font-size:14px"><b>CAMBIAR CONTRASEÑA</b></font></a>
                
                </td>
                <td bgcolor="#FF9900" align="center" >
              <a  href="index.php?pag=listado_horas.php" style="text-decoration:none"> <font color="#FFFFFF" style="font-family:Arial, Helvetica, sans-serif; font-size:14px"><b>LISTADO HORAS</b></font></a>
                
                </td>
<td bgcolor="#FF9900" align="center" >
              <a  href="index.php?pag=logout.php" style="text-decoration:none"> <font color="#FFFFFF" style="font-family:Arial, Helvetica, sans-serif; font-size:14px"><b>SALIR DE LA SESSION</b></font></a>
                
                </td>
                <?
				}
				?>
             </tr>
             <tr>
             <td></td>
             	<td align="center" valign="top">
                <br><br>
                <?
               if(isset($_GET['pag']) && $_GET['pag']!=""){//si existe una pagina para cargar y no esta vacia
						include('paginas/'.$_GET['pag']);//incluimos el contenido de la pagina contenida en la variable
					}else{		
							
						include('paginas/listado.php');
					}
                ?>
                
                </td>
                <td></td>
             </tr>
             
             <tr>
             <td></td>
             </tr>
        </table>
    </body>
</html>