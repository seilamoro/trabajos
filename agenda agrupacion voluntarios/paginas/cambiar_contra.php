<div style="width:290px">
<fieldset>
<legend>CAMBIAR CONTRASE&#209;A</legend>
<br />
<form name="contra" id="contra" action="?pag=cambiar_contra.php" method="POST">
<?
@ $db = mysql_pconnect("localhost","root","");
        if (!$db){
            echo "Error: no se puede acceder a la base de datos";
            exit;
        	}
        mysql_select_db("agenda");
if($_POST['p1']){

		if($_POST['p1']==$_POST['p2']){
		$sql_insert="UPDATE contra SET password='". trim($_POST["p1"]) ."'";
	
	$dd=mysql_query($sql_insert);
	if($dd==1){
	echo "<script>alert ('La contraseña se ha cambiado correctamente.');</script>";
	}
		}else{
		echo "<script>alert ('Tienen que coincidir las dos contraseñas.');</script>";
		}


}
?>
<table>
	<tr>
    	<td>CONTRASE&#209;A:</td>
        <td><input type="password" name="p1" id="p1" size="14" /></td>
    </tr>
    <tr>
    	<td>CONTRASE&#209;A:</td>
        <td><input type="password" name="p2" id="p2" size="14" /></td>
    </tr>
    <tr>
    	<td colspan="2" align="center"><br /><br /><input type="submit" value="Cambiar"  name="Cambiar" /></td>
        
    </tr>
</table>
</form>
<br />
</fieldset>
</div>