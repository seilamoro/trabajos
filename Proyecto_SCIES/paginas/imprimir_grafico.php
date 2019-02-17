<?php 
session_start();

$img = imagecreatefrompng('../imagenes/graficos/imagen.png');
$thumb = imagecreatetruecolor(620,420);
// redimensionar imagen original copiandola en la imagen
imagecopyresampled($thumb,$img,0,0,0,0,620,420,imagesx($img),imagesy($img));
// guardar la imagen redimensionada
imagepng($thumb,'../imagenes/graficos/imagen_imprimir.png',$img_nueva_calidad);
imagedestroy($thumb);

?> 
<html> 
<head> 
	<LINK rel="stylesheet" href="../../css/listado.css">
	<LINK rel="stylesheet" href="../../css/comun.css">
</head> 
<body bgcolor="#FFFFFF" text="#000000" onload="window.print();"> 

<?php
	@ $db = mysql_pconnect("localhost" , "root" , "");
	if (!$db){
		echo "Error : No se ha podido conectar a la base de datos";
		exit;
	}
	mysql_select_db("SCIES");

	$instalaciones = $_SESSION['instalaciones_selec_grafico'];
	$parametros = $_SESSION['parametros_selec_grafico'];

	if(count($_SESSION['leyenda_colores_grafico'][0]) != 0){
?>

<div id="leyenda" style="position: absolute; background-color: #EBEBEB; border: 1px solid #000000; margin-left: 515px; margin-top: 24px; width: 102px; height: 365px; visibility:visible;"> 
  <table width="100%" cellpadding="0" cellspacing="2" border="0" class="" style="height:0px">
		<tr><td align="center" colspan="2" width="150px" height="15px"><font size="1px" face="Arial, Helvetica, sans-serif"><b>Leyenda</b></font></td><td>&nbsp;</td></tr>
		<?
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
						?>
							<tr><td colspan="3" height="20px" align="left"><font size="1px" face="Arial, Helvetica, sans-serif"><li><?=$inst[1][$x]?></li></font></td></tr>
						<?
						$alto += 22;
						for($i1 = 0; $i1 < count($parametros); $i1++){
							for($x1 = 0; $x1 < count($para[0]); $x1++){
								if($parametros[$i1] == $para[0][$x1]){
									if(strlen($para[1][$x1]) > $ancho)
										$ancho = strlen($para[1][$x1]);
										if($_SESSION['grafico_grafico'] != 2){
									?>
					<tr><td widht="8px" align="center"><hr size="2" width="7px" color="<?=$_SESSION['leyenda_colores_grafico'][$color]?>"></td>
					<td align="left"><font size="-9" ><?=$para[1][$x1]?></font></td></tr>
									<?
										}else{
									?>
					<tr><td widht="8px" align="center">
						<table width="8px" height="8px" bgcolor="<?=$_SESSION['leyenda_colores_grafico'][$color][0]?>" border="1" bordercolor="#000000"><tr><td></td></tr></table>
					</td>
					<td align="left"><font size="-9">Max/Min</font></td></tr>
					<tr><td width="8px" align="center"><hr size="2" width="7px" color="<?=$_SESSION['leyenda_colores_grafico'][$color][1]?>"></td>
					<td align="left"><font size="-9"><?=$para[1][$x1]?></font></td></tr>
									<?
										}
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

<?
	}	
?>

	<table border='0' width='100%' height='100%'><tr><td align='left' valign="top">
		<img src="../imagenes/graficos/imagen_imprimir.png">
	</td></tr></table>

</body> 
</html>  
