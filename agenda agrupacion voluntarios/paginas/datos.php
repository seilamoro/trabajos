<?php

	$datos[count($datos)] = "Afganistan";
	

	$texto = $_GET["texto"];
	@ $db = mysql_pconnect("localhost","root","");
        if (!$db){
            echo "Error: no se puede acceder a la base de datos";
            exit;
        }
        mysql_select_db("agenda");
      $datos=array();
		$sql=mysql_query("select nombre from datos where nombre LIKE '%".$texto."%'
");
		
		for ($i=0; $i<mysql_num_rows($sql); $i++)
		{
		
		$row=mysql_fetch_array($sql);
	
		$datos[$i]=$row['nombre'];
		
		}
	// Devuelvo el XML con la palabra que mostramos (con los '_') y si hay éxito o no
	$xml  = '<?xml version="1.0" standalone="yes"?>';
	$xml .= '<datos>';
	foreach ($datos as $dato) {
		if (strpos(strtoupper($dato), strtoupper($texto)) === 0 OR $texto == "") {
			$xml .= '<nombre>'.$dato.'</nombre>';
		}
	}
	$xml .= '</datos>';
	header('Content-type: text/xml');
	echo $xml;		
?>