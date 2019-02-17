<?
	session_start();
?>
<html>
	<head>
		<SCRIPT LANGUAGE="JavaScript">
		//FUNCIÓN PARA QUE AUTOMÁTICAMENTE TE SALGA LA PANTALLA DE IMPRIMIR
			  function imprimir()
				  {
					   version = parseInt(navigator.appVersion);
					   if (version >= 4)
						 window.print();
				  }
		</script>
		<link rel="stylesheet" type="text/css" href="../css/estilo_online.css">
	<link rel="stylesheet" href="../css/estilos.css">
	</head>
<BODY>
<!-- tabla 1 -->
<table align='center' border='0'>
    <tr>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td align='center'>
		  <b><font face="Arial, Helvetica, sans-serif" size="5" color="#C40056">Tarifas <?php echo date("Y");?></font></b>
        </td>
      
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td ></td>
        <td valign="top">
        <table border='0' cellpadding="0" cellspacing="0" width="100%" class="scrollTable" style='width:570px;'>
        <?php
@ $db = mysql_pconnect($_SESSION['conexion']['host'], $_SESSION['conexion']['user'],  $_SESSION['conexion']['pass']);
if (!$db){
	echo "Error : No se ha podido conectar a la base de datos";
	exit;
}
mysql_select_db($_SESSION['conexion']['db']);

// obtenemos los tipos de persona
$query="SELECT Id_Tipo_Persona,Nombre_Tipo_Persona FROM tipo_persona ORDER BY Id_Tipo_Persona ASC";
@$res = mysql_query($query);
@$num_tipo_persona = mysql_num_rows($res);

?>
      <table border='1' width='100%'>
        <tr class=fondo_celda_tarifa>
        <th>Edades</th>
<?php
    for($i=0;$i<$num_tipo_persona;$i++)
    {
        $fila=mysql_fetch_array($res);
		$cont_tipo_per[$i]=$fila['Id_Tipo_Persona'];	
        echo "<th>".$fila['Nombre_Tipo_Persona']."</th>";
    }
    // obtenemos las habitaciones que sean Reservables pero no compartidas, como la triple
    $query="SELECT Nombre_Tipo_Hab FROM tipo_habitacion WHERE Reservable='S' AND Compartida='N';";
    @$res = mysql_query($query);
    @$num_tipo_hab = mysql_num_rows($res);
    for($i=0;$i<$num_tipo_hab;$i++)
    {
        $fila=mysql_fetch_array($res);
        echo "<th>".$fila['Nombre_Tipo_Hab']."</th>";
    }
?>
        </tr>
<?php
    $query="SELECT Nombre_Edad,Id_Edad from edad";
    @$res_edad = mysql_query($query);
    @$num_edad = mysql_num_rows($res_edad);
	for($i=0;$i<$num_edad;$i++)
	{
        echo "<tr>";
	    echo "<td id='texto_listados'>";
	    $fila_edad=mysql_fetch_array($res_edad);
	    echo $fila_edad['Nombre_Edad']."</td>";

 	    for($j=0;$j<$num_tipo_persona;$j++){
			$query="SELECT DISTINCT Tarifa,tarifa.Id_Edad,tarifa.Id_Tipo_Persona
					FROM tarifa
					INNER JOIN tipo_habitacion ON tipo_habitacion.Id_Tipo_Hab = tarifa.Id_Tipo_Hab
					AND NOT (tipo_habitacion.Reservable='S' AND tipo_habitacion.Compartida='N')
					WHERE Id_Edad=".$fila_edad['Id_Edad']."  and tarifa.Id_Tipo_Persona=".$cont_tipo_per[$j]." ORDER BY tarifa.Id_Tipo_Persona ASC";
           
             @$res = mysql_query($query);
			 @$num = mysql_num_rows($res);
			 $fila=mysql_fetch_array($res);
             if ($num==0){
					 $precio='0';
			 }
             else{
				 $precio = $fila['Tarifa'];
			 }
											 
			echo "<td align='center' id='texto_listados'>".$precio."</td>";											  
											 
											  
                                            }
           
        $query="SELECT DISTINCT Tarifa,tarifa.Id_Edad
                FROM tarifa
                INNER JOIN tipo_habitacion ON tipo_habitacion.Id_Tipo_Hab = tarifa.Id_Tipo_Hab
                AND (tipo_habitacion.Reservable='S' AND tipo_habitacion.Compartida='N')
                WHERE Id_Edad=".$fila_edad['Id_Edad'].";";
        @$res = mysql_query($query);
	    @$num = mysql_num_rows($res);
        for($j=0;$j<@$num;$j++)
	    {
            $fila=mysql_fetch_array($res);
		    if ($num==0) $precio='0';
            else $precio = $fila['Tarifa'];
	        echo "<td align='center' id='texto_listados'>".$precio."</td>";
        }
		if(@$num==0){
			$precio='0';
			echo "<td align='center' id='texto_listados'>".$precio."</td>";
		}
        echo "</tr>";
	  }

?>
            </td>
	   </tr>
	   </table> <!-- fin tabla 2-->
    <tr>
        <td></td>
        <td></td>
    </tr>
    <TR>
	    <td></td>
	    <TD> <b><FONT color="#00000" size="3">*Grupo Mínimo diez personas.</TD>
    </TR>
    <TR>
	    <TD></TD>
	    <TD><b><FONT color="#00000" size="3">*Triple Habitaciones individuales de dos o tres camas.<b></FONT></TD>
    </TR>
    <tr>
        <td>
            <script>
	           imprimir();
            </script>
            </td>
    </tr>

</table><!-- fin tabla 1-->


