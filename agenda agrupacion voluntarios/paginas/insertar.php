<script>
	
	function comprobar(){
	 var estado=true;
	 if(document.insert.nombre.value==""){
			estado=false;
			
		}else{
		if(document.insert.email.value != ""){
				if(document.insert.email.value.search(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/ig)){
					alert("La cuenta de correo no es válida");
					estado = false;}}
		if(document.insert.tf1.value.length>0){		
					if(document.insert.tf1.value.length<9){
						alert("El teléfono debe de tener al menos nueve dígitos");
								estado = false;
					}
				}
		if(document.insert.tf2.value.length>0){		
					if(document.insert.tf2.value.length<9){
						alert("El teléfono debe de tener al menos nueve dígitos");
								estado = false;
					}
				}
		if(document.insert.fax.value.length>0){		
					if(document.insert.fax.value.length<9){
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
		
					if(document.insert.nombre_checkbox.checked==true)
					document.insert.privi.value = 'si';
				else
					document.insert.privi.value = 'no';
				if(adelante==true){	
				document.getElementById("insertar").value="si";
					document.getElementById("insert").submit();
					}else{
					
					document.getElementById("insertar").value="no";
					}
					
	}
</script>
<form name="insert" action="?pag=insertar.php" method="POST">
<input type="hidden" name="insertar" value="<? echo $_POST["insertar"];?>" />
<input type="hidden" name="privi" value="<? echo $_POST["privi"];?>" />
<?
@ $db = mysql_pconnect("localhost","root","");
        if (!$db){
            echo "Error: no se puede acceder a la base de datos";
            exit;
        	}
        mysql_select_db("agenda");
if($_POST["insertar"]=="si"){
		
        
		$sql=mysql_query("select * from datos where nombre ='".$_POST['nombre']."'");
	$result=mysql_fetch_array($sql);
	if(mysql_num_rows($sql)==0){
	$sql_insert="INSERT INTO datos (nombre,direccion,telefono1,telefono2,fax,email,comentarios,privilegio) VALUES ('". trim($_POST["nombre"]) ."','".$_POST["dire"]."','".$_POST["tf1"]."','".$_POST["tf2"]."','".$_POST['fax']."','".$_POST['email']."','".$_POST['comentarios']."','".$_POST['privi']."')";
	
	mysql_query($sql_insert);
	//compruebo si se ha inserttado bien con todos los datos
	$sql_comprobacion=mysql_query("select * from datos where nombre ='".$_POST['nombre']."'");
	$result2=mysql_fetch_array($sql_comprobacion);
	if(mysql_num_rows($sql_comprobacion)==1){
		echo "<script>alert ('Se ha insertado bien el contacto');</script>";
	}else{
		echo "<script>alert ('Se ha producido un error');</script>";
	}
	
	}else{
	echo "<script>alert ('No se ha podido insertar el contacto porque ya hay otro contacto con el mismo nombre');</script>";
	}
	echo "<script>document.getElementById('insertar').value='no';</script>";
	?>
	<script>document.getElementById("insert").submit();</script>
    <?
}
?>
<center>
<fieldset>
<legend>INSERTAR DATOS</legend>
<table>
	<tr>
        <td>NOMBRE:</td>
        <td><input type="text" name="nombre" id="nombre"  value="<? if($_POST["nombre"]){echo $_POST["nombre"];}?>" size="15" /></td>
        <td>DIRECCION:</td>
        <td><input type="text" name="dire" id="dire" value="<? echo $_POST["dire"];?>" size="15" /></td>
    </tr>
    <tr>
        <td>TELEFONO 1:</td>
        <td><input type="text" name="tf1" id="tf1" value="<? echo $_POST["tf1"];?>" size="15" onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"/></td>
        <td>TELEFONO 2:</td>
        <td><input type="text" name="tf2" id="tf2" value="<? echo $_POST["tf2"];?>" size="15" onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"/></td>
    </tr>
    <tr>
        <td>FAX:</td>
        <td><input type="text" name="fax" id="fax" value="<? echo $_POST["fax"];?>" size="15" onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" /></td>
        <td>EMAIL:</td>
        <td><input type="text" name="email" id="email" value="<? echo $_POST["email"];?>" size="15" /></td>
    </tr>
    <tr>
    	<td valign="top">COMENTARIOS:</td>
        <td colspan="3"><textarea name="comentarios" rows="10" cols="30"><? echo $_POST["comentarios"];?></textarea>
</td>
     </tr>
     <tr>
    	<td valign="top">NO VISIBLE A TODOS:</td>
        <td colspan="3">
        <? echo $_POST["comentarios"];
			if($_POST["privi"]){
				if($_POST["privi"]=="si"){
				?>
        <input type="checkbox" value="false" name="nombre_checkbox" checked>
        <? 
				}else{
				?>
        <input type="checkbox" value="false" name="nombre_checkbox">
        <?
				}
			}else{
		?>
        <input type="checkbox" value="false" name="nombre_checkbox">
        <? 
			}
		?>
</td>
     </tr>
     <tr>
     	<td colspan="4" align="center"><input  type="button" value="Insertar" onclick="enviar();" /></td>
     </tr>
</table>
</fieldset>
</center>
</form>