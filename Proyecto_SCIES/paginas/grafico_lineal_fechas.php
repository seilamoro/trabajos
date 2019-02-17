<?
	session_start();	
?>
<html>
<head>
	<style type="text/css">
		/* Estilo para las tablas con scroll */
		div.tableContainer {
			clear: both;
			overflow-y: scroll;
		}
		
		div.tableContainer1 {
			clear: both;
			overflow-x: scroll;
		}
		
		div.tableContainer2 {
			clear: both;
			overflow-y: scroll;
			overflow-x: scroll;
		}
		
		div.tableContainer table {
			float: left;
		}
		/* Fin tablas con scroll */
	</style>
	<LINK rel="stylesheet" href="../css/listado.css">
	<LINK rel="stylesheet" href="../css/comun.css">
	<script type='text/javascript' src='../x_core.js'></script>
	<script>
		function cargar(rango){
			location.href="principal.php?pag=crear_imagen_grafico_lineas_fecha.php&rango="+rango;
		}
		
		// Esta funciòn muestra la pagina del calendario en un popup
        function imprimir(){
            var opciones="toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=640,height=440,top=" + pos_top(440) + ",left=" + pos_left(640);
            var pagina = "./paginas/imprimir_grafico.php";
            ven = window.open(pagina,"vent",opciones);		
        }

		// Estas funciones calcula la posicion del popup en la pantalla para que aparezca en el centro
		function pos_left(anc){
		  return (screen.width - anc) / 2;
		}
		function pos_top(alt){
		  return (screen.height - alt) / 2;
		}
	</script>
</head>
<body>
<?php
	@ $db = mysql_pconnect("localhost" , "root" , "");
	if (!$db){
		echo "Error : No se ha podido conectar a la base de datos";
		exit;
	}
	mysql_select_db("SCIES");

	$rango = $_GET['rango'];
	$fecha = $_SESSION['fecha_grafico'];
	$fecha1 = substr($fecha,8,2) . "/" . substr($fecha,5,2) . "/" . substr($fecha,0,4);
	$instalaciones = $_SESSION['instalaciones_selec_grafico'];
	$parametros = $_SESSION['parametros_selec_grafico'];

	if(count($_SESSION['leyenda_colores_grafico'][0]) != 0){
?>

<div class="" id="leyenda" style="position: absolute; background-color: #EBEBEB; border: 1px solid #000000; margin-left: 343px; margin-top: 123px; width: 168px; height: 615px;"> 
  <table width="100%" cellpadding="0" cellspacing="2" border="0" class="" style="height:0px" id="tabla_leyenda">
		<tr><td align="center" colspan="2" width="168px" height="20px"><font size="3px" face="Arial, Helvetica, sans-serif"><b>Leyenda</b></font></td><td>&nbsp;</td></tr>
		<?
			$alto = 25;
			$ancho = 0;
			$inst = array();
			$sql = "select * from centro";
			$res = mysql_query($sql);
			$num = mysql_num_rows($res);
			for ($i = 0; $i < $num; $i++){
				$fila = mysql_fetch_array($res);
				$inst[0][$i] = $fila['Id_Centro'];
				$inst[1][$i] = $fila['Nombre'];
			}
			$para = array();
			$sql = "select * from parametro";
			$res = mysql_query($sql);
			$num = mysql_num_rows($res);
			for ($i = 0; $i < $num; $i++){
				$fila = mysql_fetch_array($res);
				$para[0][$i] = $fila['Id_Parametro'];
				$para[1][$i] = $fila['Nombre_Param'];
				$para[2][$i] = $fila['Descripcion_Param'];
			}
			$color = 0;
			for($i = 0; $i < count($instalaciones); $i++){
				for($x = 0; $x < count($inst[0]); $x++){
					if($instalaciones[$i] == $inst[0][$x]){
						if(strlen($inst[1][$x]) > $ancho)
							$ancho = strlen($inst[1][$x]);
						?>
							<tr><td colspan="3" height="20px" align="left"><font size="2px" face="Arial, Helvetica, sans-serif"><li><?=$inst[1][$x]?></li></font></td></tr>
						<?
						$alto += 22;
						for($i1 = 0; $i1 < count($parametros); $i1++){
							for($x1 = 0; $x1 < count($para[0]); $x1++){
								if($parametros[$i1] == $para[0][$x1]){
									if(strlen($para[1][$x1]) > $ancho)
										$ancho = strlen($para[1][$x1]);
										if($_SESSION['grafico_grafico'] != 2){
									?>
					<tr><td widht="15px" align="center"><hr size="2" width="15px" color="<?=$_SESSION['leyenda_colores_grafico'][$color]?>"></td>
					<td align="left" title="Descripci&oacute;n: '<?=$para[2][$x1]?>'"><font size="1.5px" face="Arial, Helvetica, sans-serif"><?=$para[1][$x1]?></font></td></tr>
									<?
										}else{
									?>
					<tr><td widht="15px" align="center">
						<table width="15px" height="15px" bgcolor="<?=$_SESSION['leyenda_colores_grafico'][$color][0]?>" border="1" bordercolor="#000000"><tr><td></td></tr></table>
					</td>
					<td align="left"><font size="1.5px" face="Arial, Helvetica, sans-serif">Máximo/Mínimo</font></td></tr>
					<tr><td width="15px" align="center"><hr size="2" width="15px" color="<?=$_SESSION['leyenda_colores_grafico'][$color][1]?>"></td>
					<td align="left" title="Descripci&oacute;n: '<?=$para[2][$x1]?>'"><font size="1.5px" face="Arial, Helvetica, sans-serif"><?=$para[1][$x1]?></font></td></tr>
									<?
										}
									$alto += 22;	
									$color++;
								}
							}
						}
					}
				}
			}
		?>
	</table>
</div>

<script>
	var alto = <?=$alto;?>;
	var ancho = <?=$ancho;?>;
	var browserType=navigator.userAgent;
	if (browserType.indexOf("MSIE")==-1){
		document.getElementById("leyenda").style.top = 13;
	}
	if (alto > 615 && ancho > 25){
		document.getElementById("leyenda").className = "tableContainer2";
		document.getElementById("tabla_leyenda").className = "scrollTable";
		document.getElementById("tabla_leyenda").style.width = ancho*8;
	}else{
		if (alto > 615){
			document.getElementById("leyenda").className = "tableContainer";
			document.getElementById("tabla_leyenda").className = "scrollTable";
		}else{
			document.getElementById("leyenda").style.height = alto;
		}
		if (ancho > 25){
			document.getElementById("leyenda").className = "tableContainer1";
			document.getElementById("tabla_leyenda").className = "scrollTable";
			document.getElementById("tabla_leyenda").style.width = ancho*8;
		}
	}
</script>

<?
	}	
?>


<div class="pagina">
<div class="listado1">

	<table class="opcion_triple" cellpadding="0" cellspacing="0" width="100%" align="center" style="width:1060px;" border="1" bordercolor="#ACA867">
		<thead>
			<tr class="titulo1">
				<th colspan="4">Generar Gr&aacute;ficas</th>
				<th width="120px" class="titulo2" style='text-align:center'><input type="button" value="Volver" class="boton_big" onClick="location.href='principal.php?pag=criterios_graficos.php'" title="Ir a la página de Criterios"></th>
			</tr>
		</thead>
		<thead>						
			<tr style="text-indent:0px;">
				<th class="titulo2" style='text-align:center' width="100px">Rango:</th>
				<th class="titulo3" style='text-align:left' width="54%">
					<table width="100%" cellpadding="0" cellspacing="0">
						<tr> 
						  <th>
						    <?
							if($rango == 1){
								echo "<font color='#988E8E'>1 d&iacute;a</font>, ";
							}else{
								echo "<a href='javascript:cargar(1)' title='Cambia el rango de la imagen a 1 d&iacute;a'>1 d&iacute;a</a>, ";
							}
							if($rango == 2){
								echo "<font color='#988E8E'>3 d&iacute;as</font>, ";
							}else{
								echo "<a href='javascript:cargar(2)' title='Cambia el rango de la imagen a 3 d&iacute;as'>3 d&iacute;as</a>, ";
							}
							if($rango == 3){
								echo "<font color='#988E8E'>1 semana</font>, ";
							}else{
								echo "<a href='javascript:cargar(3)' title='Cambia el rango de la imagen a 1 semana'>1 semana</a>, ";
							}
							if($rango == 4){
								echo "<font color='#988E8E'>2 semanas</font>, ";
							}else{
								echo "<a href='javascript:cargar(4)' title='Cambia el rango de la imagen a 2 semanas'>2 semanas</a>, ";
							}
							if($rango == 5){
								echo "<font color='#988E8E'>1 mes</font>, ";
							}else{
								echo "<a href='javascript:cargar(5)' title='Cambia el rango de la imagen a 1 mes'>1 mes</a>, ";
							}
							if($rango == 6){
								echo "<font color='#988E8E'>3 meses</font>, ";
							}else{
								echo "<a href='javascript:cargar(6)' title='Cambia el rango de la imagen a 3 meses'>3 meses</a>, ";
							}
							if($rango == 7){
								echo "<font color='#988E8E'>6 meses</font>, ";
							}else{
								echo "<a href='javascript:cargar(7)' title='Cambia el rango de la imagen a 6 meses'>6 meses</a>, ";
							}
							if($rango == 8){
								echo "<font color='#988E8E'>1 a&ntilde;o</font>.";
							}else{
								echo "<a href='javascript:cargar(8)' title='Cambia el rango de la imagen a 1 a&ntilde;o'>1 a&ntilde;o</a>.";
							}
							?>
					  </th>
					</tr>
				  </table>
				</th>
				<?
				if($rango < 5){
				?>
					<th class="titulo2" style='text-align:center' width="100px">Fecha:</th>
					<th class="titulo3" width="100px"><?=$fecha1;?></th>
				<?
				}else if($rango < 8){
					$meses_c = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
				?>
					<th class="titulo2" style='text-align:center' width="100px">Mes:</th>
					<th class="titulo3" width="100px"><?=$meses_c[substr($fecha1,3,2)/1];?></th>
				<?
				}else if($rango == 8){
					$meses_c = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
				?>
					<th class="titulo2" style='text-align:center' width="100px">A&ntilde;o:</th>
					<th class="titulo3" width="100px"><?=substr($fecha1,6,4);?></th>
				<?
				}
				if($_SESSION['valores_grafico'] == 0){
				?>
					<th class="titulo2" style='text-align:center'><input type="button" value="Imprimir" class="boton_big" title="No existe un Gráfico para imprimir"></th>
				<?
				}else{
				?>
					<th class="titulo2" style='text-align:center'><input type="button" value="Imprimir" class="boton_big" onClick="imprimir();" title="Imprimir el Gráfico"></th>
				<?
				}
				?>
			</tr>
		</thead>
	</TABLE>

	<table border="0" cellpadding="0" cellspacing="0" height='690' width='1050'>
		<tr><td align="center">
			<img src="./imagenes/graficos/imagen.png?d=<?=$_SESSION['id_foto_grafico_lineal_fechas']?>">
		</td></tr>
	</table>
</div>
</div>

<?
	mysql_close($db);
?>
</body>
</html>
