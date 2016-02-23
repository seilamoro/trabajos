<?
session_start();
// Cabeceras para abrir excel con el nombre de la factura
	header("Content-type: application/vnd.ms-excel;");
	header("Content-Disposition: attachment; filename=imprimir_horas.xls");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	
$datos=array();
$datos=$_SESSION['horas'];


	for($m=0;$m<count($datos);$m++){
		echo $datos[$m]['indicativo']."<br>";
	?>
    <table>
    	<tr>
        	<td>FECHA INICIO</td>
            <td>FECHA FIN</td>
            <td>HORA INICIO</td>
            <td>HORA FIN</td>
            <td>PREVENTIVO</td>
            <td>HORAS</td>
        </tr>
        <?
		
		for($n=0;$n<count($datos[$m]);$n++){
			echo "<tr>";
			echo "<td>".$datos[$m][$n]['fecha_entrada']."</td>";
			echo "<td>".$datos[$m][$n]['fecha_salida']."</td>";
			echo "<td>".$datos[$m][$n]['hora_entrada']."</td>";
			echo "<td>".$datos[$m][$n]['hora_salida']."</td>";
			echo "<td>".$datos[$m][$n]['preventivo']."</td>";
			if($datos[$m][$n]['minimo']=="si"){
				echo "<td>".$datos[$m][$n]['sacar_hora']."</td>";
			}else{
				echo "<td><font color='FF0000'>".$datos[$m][$n]['sacar_hora']."</font></td>";
			}
			echo "</tr>";
		}
	
		
		?>
        <tr>
        <td colspan="5" align="right">TOTAL:</td>
			<td >
            <?
			echo $datos[$m]['total_hora'];
			
			?>
            </td>
            </tr>
            </table>
    
    <?
	}
/*
echo iconv("UTF-8","CP1252","±àºÅ\t");

echo iconv("UTF-8","CP1252","ÉêÞzÈÕ\t");

echo iconv("UTF-8","CP1252","¤ªÃûÇ°\t");

echo iconv("UTF-8","CP1252","¥í©`¥Þ×Ö\t");

echo iconv("UTF-8","CP1252","ÇÚ„ÕÏÈ\t");

echo iconv("UTF-8","CP1252","²¿Êð\t");

echo iconv("UTF-8","CP1252","ÒÛÂš\t");

echo iconv("UTF-8","CP1252","×¡Ëù\t");

echo iconv("UTF-8","CP1252","à]±ã·¬ºÅ\t");*/

//echo iconv("UTF-8","CP1252","TEL\t");

//echo iconv("UTF-8","CP1252","FAX\t");

//echo iconv("UTF-8","CP1252","E-mail\t");

//echo iconv("UTF-8","CP1252","http://\t");

/*echo iconv("UTF-8","CP1252","¤´À´ˆöÄ¿µÄ\t");

echo iconv("UTF-8","CP1252","±×Éç¤Î˜I„ÕÄÚÈÝ\t");

echo iconv("UTF-8","CP1252","±¾Õ¹Ê¾»á¤ò¤É¤ÎÃ½Ìå¤Ç¤ªÖª¤ê¤Ë¤Ê¤ê¤Þ¤·¤¿¤«\t");

echo iconv("UTF-8","CP1252","½ñáá¡¢½Uœg?×Ô„ÓÜ‡év?Çéˆó?JETROév?¥¤¥Ù¥ó¥È¤Î¤´°¸ÄÚ¤ò²î¤·ÉÏ¤²¤Æ¤â¤è¤í¤·¤¤¤Ç¤¹¤«\t");*/

?>