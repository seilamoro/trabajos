<body style="background-color:#E9E7CE;">
<center>
<br><br>
<?php

$conexion = $_GET['conexion'];
$centro = $_GET['id_centro'];

$nombre_archivo = "..\..\..\..\actualizar_".$conexion.".bat";
$contenido = "java -jar C:\SCIES\SCIES.jar ".$conexion." ".$centro." ";


if (!$gestor = fopen($nombre_archivo, 'w')) {
	 echo "No se puede crear el archivo";
	 exit;
}

if (fwrite($gestor, $contenido) === FALSE) {
	echo "No se puede escribir al archivo ($nombre_archivo)";
	exit;
}

echo "Se ha creado un archivo de conexión:   C:\actualizar_".$conexion.".bat ";

fclose($gestor);


?>
</center>
</body>