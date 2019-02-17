<?php
	if(isset($_SESSION['permisoUsuarios']) && $_SESSION['permisoUsuarios']==true) //Comprobando que se tiene permiso para acceder a la pagina
		{
/* Comprobacion de la ordenacion del listado */
	if(!isset($_REQUEST['orden']) || ($_REQUEST['orden']!="ua" && $_REQUEST['orden']!="ud" && $_REQUEST['orden']!="pa" && $_REQUEST['orden']!="pd"))//Si no se ha especificado el orden, el listado se ordena por PERFIL DESCENDENTEMENTE
		{
		$_REQUEST['orden']="pd";
		$sql="select * from usuario order by OnLine DESC, Reservas DESC, Peregrino DESC, Alberguista DESC, Grupos DESC, Facturacion DESC, Incidencias DESC, TarifasEdades DESC, Estadisticas DESC, InfPolicial DESC, REAJ DESC, InternaREAJ DESC, Usuarios DESC, Taquillas DESC, InternaTaquillas DESC, Habitaciones DESC, InternaHabitaciones DESC";
		}
	else
		{
		$sql="select * from usuario order by ";
		switch($_REQUEST['orden'])//Selecciono el orden del listado
			{
			case "ua": //Ordenar por Usuario Ascendentemente
				$sql=$sql."Usuario ASC";
				break;
			case "ud": //Ordenar por Usuario Descendentemente
				$sql=$sql."Usuario DESC";
				break;
			case "pa": //Ordenar por Perfil Ascendentemente
				$sql=$sql."OnLine ASC, Reservas ASC, Peregrino ASC, Alberguista ASC, Grupos ASC, Facturacion ASC, Incidencias ASC, TarifasEdades ASC, Estadisticas ASC, InfPolicial ASC, REAJ ASC, InternaREAJ ASC, Usuarios ASC, Taquillas ASC, InternaTaquillas ASC, Habitaciones ASC, InternaHabitaciones ASC";
				break;
			case "pd": //Ordenar por Perfil Descendentemente
				$sql=$sql."OnLine DESC, Reservas DESC, Peregrino DESC, Alberguista DESC, Grupos DESC, Facturacion DESC, Incidencias DESC, TarifasEdades DESC, Estadisticas DESC, InfPolicial DESC, REAJ DESC, InternaREAJ DESC, Usuarios DESC, Taquillas DESC, InternaTaquillas DESC, Habitaciones DESC, InternaHabitaciones DESC";
				break;
			}
		}
		$consulta=mysql_query($sql); //Obtengo los datos de los usuarios en el orden elegido anteriormente
			echo "
				<table border='0' cellpadding='0' cellspacing='0'>
					<tr style='padding:0px 0px 0px 0px;'>
						<td colspan='5'>
							<div class='champi_izquierda'>&nbsp;</div>
							<div class='champi_centro' style='width: 358px;'>
								<div class='titulo' style='text-align: center;'>Listado de Usuarios</div>
							</div>
							<div class='champi_derecha'>&nbsp;</div>
						</td>
					</tr>
					
<tr style='padding-top:0px;'>
	<td style='border:1px solid #3F7BCC;'>
		<div style='height:390;' style='border:none;border-left:none' class='tableContainer' id='tabla_detalles'>
			<table style='margin:0px 0px 0px 0px;' align='center' cellspacing='0' cellpadding='0' class='scrollTable'>";/*				
					
					
					<tr style='padding-top:0px;'>
						<td align='center'>
							<div class='tableContainer' style='height:390px;' id='tabla_detalles'>
								<table class='scrollTable' border='0' cellpadding='0' cellspacing='0'>";*/
		if(mysql_num_rows($consulta)>0)
			{
			echo "<thead class='fixedHeader'>
					<tr style='cursor:default;' style='padding:0px 0px 0px 0px;'>
						<th align='left' style='border-left:none;' onclick='location.href=\"?pag=gi_usuarios.php&orden=";if($_REQUEST['orden']=="ua") echo "ud"; else echo "ua"; echo "\";'>Usuario</th>
						<th align='left' onclick='location.href=\"?pag=gi_usuarios.php&orden=";if($_REQUEST['orden']=="pa") echo "pd"; else echo "pa"; echo "\";'>Perfil</th>
						<th align='center' >D</th>
						<th align='center' >M</th>
						<th align='center' >E</th>
					</tr>
				</thead>
				<tbody class='scrollContent'>
				<form name='formularioListado' action='?pag=gi_usuarios.php&cuad1=4' method='post'><input type='hidden' name='user' /><input type='hidden' name='p' /><input type='hidden' name='orden' value='".$_REQUEST['orden']."'/>";
	
				for($i=0;$i<mysql_num_rows($consulta);$i++)
					{
					$adm=true; //Por defecto fijo esto a TRUE, esto indica que el usuario sera un Administrador
	    	    	echo "<tr style='background-color: rgb(244, 252, 255); color: rgb(63, 123, 204); cursor: pointer;' id='texto_listados' onmouseover='resaltar_seleccion(this);resaltar_input(\"input".$i."\");' onmouseout='desresaltar_seleccion(this);desresaltar_input(\"input".$i."\");'>";
					$fila=mysql_fetch_row($consulta);
						echo "<td align='left' width='130px'>
									<input type='text' name='input".$i."' id='texto_listados' readonly style='border:none;background-color:#F4FCFF;' size='20px' value='".$fila[0]."'/>
							</td>
							<td align='left' width='130px'>";
							for($ind=2;$ind<mysql_num_fields($consulta);$ind++) //Recorro todos los permisos de un usuario
								{
								if($fila[$ind]=="N")//Si no tiene algun permiso, cambio la variable $adm a FALSE(lo cual indica que estamos ante un Usuario Normal), y salgo del bucle
									{
									$adm=false;
									break;
									}
								}
								if($adm)
									echo "Administrador";
								else
									echo "Usuario";

								echo"</td>
							<td align='center' width='22px'><img src='../imagenes/botones/detalles.gif' onclick='document.formularioListado.user.value=\"".$fila[0]."\";document.formularioListado.p.value=\"";if($adm) echo "a"; else echo "u"; echo "\";document.formularioListado.action=\"?pag=gi_usuarios.php&cuad1=3\";document.formularioListado.submit();' title='Detalles'></td>";
							if($fila[0]=="admin" && $fila[0]!=$_SESSION['usuario'])
								{
								echo "<td align='center' width='22px' style='cursor:default;'><img src='../imagenes/botones/modificar.gif' style='filter:alpha(opacity=20);-moz-opacity:0.2;' title='No Puede Modificar Al Administrador Principal.\nInicie Sesion Como admin Para Poder Modificarlo.'></td>";
								}
							else
								{
								if($fila[0]=="admin" && $fila[0]==$_SESSION['usuario'] && $adm)
									{
									echo "<td align='center' width='22px'><img src='../imagenes/botones/modificar.gif' onclick='document.formularioListado.user.value=\"".$fila[0]."\";document.formularioListado.p.value=\"a\";document.formularioListado.submit();' title='Cambiar Contraseña del Administrador Principal'></td>";
									}
								else
									{
									echo "<td align='center' width='22px'><img src='../imagenes/botones/modificar.gif' onclick='document.formularioListado.user.value=\"".$fila[0]."\";document.formularioListado.p.value=\"";
									if($adm)
										echo "a";
									else
										echo "u";
									echo "\";document.formularioListado.submit();' title='Modificar'></td>";
									}
								}							
							if($fila[0]!="admin" && $fila[0]!=$_SESSION['usuario'])
								{
								echo "<td align='center' width='22px'><img src='../imagenes/botones/eliminar.gif' onclick='document.formularioListado.user.value=\"".$fila[0]."\";document.formularioListado.p.value=\"";
								if($adm)
									echo "a";
								else
									echo "u";
								echo "\";document.formularioListado.action=\"?pag=gi_usuarios.php&cuad1=5\";document.formularioListado.submit();' title='Eliminar'></td>";
								}
							else
								if($fila[0]=="admin")
									echo "<td align='center' width='22px' style='cursor:default;'><img style='filter:alpha(opacity=20);-moz-opacity:0.2;' src='../imagenes/botones/eliminar.gif' title='No Se Puede Eliminar Al Administrador Principal'/></td>";
								else
									if($fila[0]==$_SESSION['usuario'])
										echo "<td align='center' width='22px' style='cursor:default;'><img src='../imagenes/botones/eliminar.gif' style='filter:alpha(opacity=20);-moz-opacity:0.2;' title='No Se Puede Eliminar Al Usuario Actual' /></td>";
						echo "</tr>";
					}							
			}
		else
			echo "<tbody>
					<tr style='background-color: rgb(244, 252, 255); color: rgb(63, 123, 204); ' id='texto_listados'><td width='50%' align='center'>No existen usuarios en el Sistema</td></tr>";
						echo "</form></tbody>
						</table>
					</div>
				</td>
			</tr>
		</table>";
	} //FIN DE LA COMPROBACION DE PERMISOS
?>
