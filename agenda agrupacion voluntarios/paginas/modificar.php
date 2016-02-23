<script>
	
	function comprobar(){
	 var estado=true;
	 if(document.modi.nombre.value==""){
			estado=false;
			
		}else{
		if(document.modi.email.value != ""){
				if(document.modi.email.value.search(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/ig)){
					alert("La cuenta de correo no es válida");
					estado = false;}}
		if(document.modi.tf1.value.length>0){		
					if(document.modi.tf1.value.length<9){
						alert("El teléfono debe de tener al menos nueve dígitos");
								estado = false;
					}
				}
		if(document.modi.tf2.value.length>0){		
					if(document.modi.tf2.value.length<9){
						alert("El teléfono debe de tener al menos nueve dígitos");
								estado = false;
					}
				}
		if(document.modi.fax.value.length>0){		
					if(document.modi.fax.value.length<9){
						alert("El fax debe de tener al menos nueve dígitos");
								estado = false;
					}
				}
		
		
		}
		return estado;
		
	}
	function enviar(){
	
		
		var adelante=comprobar();

		//comprobaciones
		
					if(document.modi.nombre_checkbox.checked==true)
					document.modi.privi.value = 'si';
				else
					document.modi.privi.value = 'no';
				if(adelante==true){	
					
				document.getElementById("modificar").value="si";

					document.getElementById("modi").submit();

					}else{
					
					document.getElementById("modificar").value="no";
					}
					
	}
</script>
<form name="modi"  action="?pag=modificar.php" method="POST">
	<input type="hidden" name="modificar" id="modificar" value="<? echo $_POST["modificar"];?>" />
<input type="hidden" name="privi" id="privi" value="<? echo $_POST["privi"];?>" />
    	<?
@ $db = mysql_pconnect("localhost","root","");
        if (!$db){
            echo "Error: no se puede acceder a la base de datos";
            exit;
        	}
        mysql_select_db("agenda");
		
		$sql=mysql_query("select * from datos where nombre ='".$_GET['nombre']."'");
	$result=mysql_fetch_array($sql);
	
	if($_POST['modificar']=="si"){
//echo "<script>alert('llega2');</script>";
	$sql=mysql_query("select * from datos where nombre ='".$_POST['nombre']."'");
	$result=mysql_fetch_array($sql);
//echo "<script>alert('llega');</script>";
	
		$operacion_actualiza=mysql_query("UPDATE datos SET direccion='".$_POST['dire']."',telefono1='". trim($_POST['tf1']) ."', telefono2='". trim($_POST['tf2']) ."', fax='". trim($_POST['fax']) ."', email='". trim($_POST['email']) ."', comentarios='".trim($_POST['comentarios'])."',privilegio='".$_POST['privi']."' where nombre='".$_POST['nombre']."'");
	echo "<script>alert('Se ha insertado bien el contacto');</script>";
	
	}
		?>
        <center>
<fieldset>
<legend>MODIFICAR DATOS</legend>

<table>
	<tr>
        <td>NOMBRE:</td>
        <td><input type="hidden" name="nombre" id="nombre"  value="<? echo $result['nombre']; ?>" size="15" /><? echo $result['nombre']; ?></td>
        <td>DIRECCION:</td>
        <td><input type="text" name="dire" id="dire" value="<? echo $result['direccion'];?>" size="15" /></td>
    </tr>
    <tr>
        <td>TELEFONO 1:</td>
        <td><input type="text" name="tf1" id="tf1" value="<? echo $result['telefono1'];?>" size="15" onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"/></td>
        <td>TELEFONO 2:</td>
        <td><input type="text" name="tf2" id="tf2" value="<? echo $result['telefono2'];?>" size="15" onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"/></td>
    </tr>
    <tr>
        <td>FAX:</td>
        <td><input type="text" name="fax" id="fax" value="<? echo $result['fax'];?>" size="15" onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" /></td>
        <td>EMAIL:</td>
        <td><input type="text" name="email" id="email" value="<? echo $result['email'];?>" size="15" /></td>
    </tr>
    <tr>
    	<td valign="top">COMENTARIOS:</td>
        <td colspan="3"><textarea name="comentarios" rows="10" cols="30"><? echo $result['comentarios'];?></textarea>
</td>
     </tr>
     <tr>
    	<td valign="top">NO VISIBLE A TODOS:</td>
        <td colspan="3">
        <? 
			
				if($result['privilegio']=="si"){
				
				?>
        <input type="checkbox" value="false" name="nombre_checkbox" id="nombre_checkbox" checked>
        <? 
				}else{
				?>
        <input type="checkbox" value="false" name="nombre_checkbox" id="nombre_checkbox">
        <?
				}
			
		?>
</td>
     </tr>
     <tr>
     	<td colspan="4" align="center"><br /><a href="" onClick="enviar();" style="text-decoration:none"><font color="#0000FF" style="font-family:Arial, Helvetica, sans-serif; font-size:14px"><b>MODIFICAR</b></font></a></td></td>
     </tr>
</table>
</fieldset>
</center>
</form>