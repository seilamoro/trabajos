<script type="text/javascript">
	function confirmar(){
		if(confirm("Desea eliminar el contacto")){
			document.formu_eli.eli.value="si";
			
			document.formu_eli.submit();
		 }else{
		 document.formu_eli.submit();
		 }
	}
</script>
 <form name="formu_eli" action="?pag=eliminar.php" method="POST">
<?PHP session_start();
@ $db = mysql_pconnect("localhost","root","");
        if (!$db){
            echo "Error: no se puede acceder a la base de datos";
            exit;
        }
        mysql_select_db("agenda");
		
		
if($_POST['eli']=="si"){

	mysql_query("DELETE from datos WHERE nombre='".$_POST['nombre']."'");
	$sql=mysql_query("select * from datos where nombre ='".$_POST['nombre']."'");
	$result=mysql_fetch_array($sql);
	if(mysql_num_rows($sql)==0){
		echo "<script>alert ('El contacto con el nombre ".$_POST['nombre']." ha sido eliminado');</script>";
	}else{
	echo "<script>alert ('No se ha podido eliminar el contacto');</script>";
	}
}else{
	if($_GET['nombre']){
		$nombre=$_GET['nombre'];
	}else if($_POST['nombre']){
		$nombre=$_POST['nombre'];
	}
	
		$sql=mysql_query("select * from datos where nombre ='".$nombre."'");
		$result=mysql_fetch_array($sql);
		
 ?>

 <input type="hidden" name="eli" id="eli" >
<input type="hidden" name="nombre" id="nombre" value="<? echo $nombre; ?>">
<fieldset>
<legend>ELIMINAR DATOS</legend>
<table>
	<tr>
        <td>NOMBRE:</td>
        <td><? echo $result['nombre'];?></td>
        <td>DIRECCION:</td>
        <td><? echo $result['direccion'];?></td>
    </tr>
    <tr>
        <td>TELEFONO 1:</td>
        <td><? echo $result['telefono1'];?></td>
        <td>TELEFONO 2:</td>
        <td><? echo $result['telefono2'];?></td>
    </tr>
    <tr>
        <td>FAX:</td>
        <td><? echo $result['fax'];?></td>
        <td>EMAIL:</td>
        <td><? echo $result['email'];?></td>
    </tr>
    <tr>
    	<td valign="top">COMENTARIOS:</td>
        <td colspan="3"><textarea name="comentarios" rows="10" cols="30" readonly><? echo $result['comentarios'];?></textarea>
</td>
     </tr>
     <tr>
     	<td colspan="4" align="center"><br /><a href="" onClick="confirmar();" style="text-decoration:none"><font color="#0000FF" style="font-family:Arial, Helvetica, sans-serif; font-size:14px"><b>ELIMINAR</b></font></a></td>
     </tr>
</table>
</fieldset>
</form>
<?
}
?>