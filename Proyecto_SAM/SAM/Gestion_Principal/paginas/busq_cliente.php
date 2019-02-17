<?PHP
@ $db = mysql_pconnect("localhost" , "root" , "");
	mysql_select_db("sam");
	
	$query = "SELECT * FROM cliente WHERE DNI_Cl='".$nif."'";
	$result = mysql_query($query);
	if (mysql_num_rows($result) != 0) {
		
		$fila = mysql_fetch_array($result);
		$igor1 = utf8_encode($fila['Nombre_Cl']);
		$igor = utf8_encode($fila['Apellido1_Cl']);
		$estado = utf8_encode(1);
		print "estado=".$estado."&nombre=".$igor1."&apellido1=".$igor;
	}
	else {
		$estado = utf8_encode(0);
		print "estado=".$estado;
	}

	
?>	