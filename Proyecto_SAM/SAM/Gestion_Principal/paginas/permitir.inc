<?php
	
		@ $db = mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
		if (!$db)
		    {
			echo "Error : No se ha podido conectar a la base de datos";
			exit;
			}
		mysql_select_db($_SESSION['conexion']['db']);
		$obtenerPermisos=mysql_query("select * from usuario where usuario='".$_SESSION['usuario']."'");
		if(mysql_num_rows($obtenerPermisos)>0)
			{
			$fila=mysql_fetch_array($obtenerPermisos);
			if($fila['OnLine']=="Y")$_SESSION['permisoOnLine']=true;
			if($fila['Reservas']=="Y")$_SESSION['permisoReservas']=true;
			if($fila['Peregrino']=="Y")$_SESSION['permisoPeregrino']=true;
			if($fila['Alberguista']=="Y")$_SESSION['permisoAlberguista']=true;
			if($fila['Grupos']=="Y")$_SESSION['permisoGrupos']=true;
			if($fila['Facturacion']=="Y")$_SESSION['permisoFacturacion']=true;
			if($fila['Incidencias']=="Y")$_SESSION['permisoIncidencias']=true;
			if($fila['TarifasEdades']=="Y")$_SESSION['permisoTarifas']=true;
			if($fila['Estadisticas']=="Y")$_SESSION['permisoEstadisticas']=true;
			if($fila['InfPolicial']=="Y")$_SESSION['permisoInfPolicial']=true;
      		       	if($fila['REAJ']=="Y")$_SESSION['permisoREAJ']=true;
			if($fila['InternaREAJ']=="Y")$_SESSION['permisoInternaREAJ']=true;
			if($fila['Usuarios']=="Y")$_SESSION['permisoUsuarios']=true;
			if($fila['Taquillas']=="Y")$_SESSION['permisoTaquillas']=true;
			if($fila['InternaTaquillas']=="Y")$_SESSION['permisoInternaTaquillas']=true;
			if($fila['Habitaciones']=="Y")$_SESSION['permisoHabitaciones']=true;
			if($fila['InternaHabitaciones']=="Y")$_SESSION['permisoInternaHabitaciones']=true;			
			}

		
?>