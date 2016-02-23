

<style type="text/css">
	

</style>
<script>
function obtenerDiasMes(mes,anyo){
			if(mes==2){ // Si el mes es Febr
				if((anyo % 4 == 0 && anyo % 100 != 0) || anyo % 400 == 0) // Si es bisiesto
					return 29;
				else
					return 28;
			}else{
				if(mes == 11 || mes == 4 || mes == 6 || mes == 9) // Si el mes es Nov, Abr, Jun o Sept
					return 30;
				else
					return 31;
			}
		}
function enviar(){
	if(document.getElementById("valorOpcion").value==2){
		document.getElementById("diafin").value=obtenerDiasMes(document.getElementById("mesmensual").value,document.getElementById("annomensual").value); 
	}
	document.getElementById("horas").submit();
}
function valorActual(valor){ 
document.getElementById("valorOpcion").value = valor; 
} 
function env(){
document.getElementById("ex").submit();
}
</script>
<script type="text/javascript" src="js/bsn.AutoSuggest_c_2.0.js"></script>

<link rel="stylesheet" href="css/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" />




<form name="horas" action="?pag=listado_horas.php" method="POST">
<?
@ $db = mysql_pconnect("localhost","root","");
        if (!$db){
            echo "Error: no se puede acceder a la base de datos";
            exit;
        	}
        mysql_select_db("agenda");
 	
$sql=mysql_query("select * from horas where indicativo='v13'");
$result=mysql_fetch_array($sql);

$fecha_entrada = mktime(substr($result['hora_entrada'],0,2), substr($result['hora_entrada'],4,2), 0, substr($result['fecha_entrada'],5,2), substr($result['fecha_entrada'],8,2), substr($result['fecha_entrada'],0,4));
$fecha_salida = mktime(substr($result['hora_salida'],0,2), substr($result['hora_salida'],4,2), 0, substr($result['fecha_salida'],5,2), substr($result['fecha_salida'],8,2), substr($result['fecha_salida'],0,4));
$resultado=round($fecha_salida - $fecha_entrada);


//lo paso a minutos
$minutos2=$resultado/60;
if($minutos2%60==0){//son horas exactas
	$hora=$minutos2/60;
	$minutos=0;
}else{
	$horapro=$minutos2/60;
	$f=split("\.",$horapro);
	$hora=$f[0];
	$minutos=$minutos2%60;
	
	
}
echo $hora.":".$minutos;

?>
<input name="valorOpcion" id="valorOpcion" type="hidden" value="1" /> 

<input type="hidden" value="no" name="preventivo" id="preventivo">
<fieldset>
<legend>CRITERIOS</legend>

	<table>
		<tr>
			<td>Indicativo:</td><td><select multiple name="indicativo[]" id="indicativo[]">   
			<?
				$sql_indicativo=mysql_query("select DISTINCT  indicativo from horas ");
				$filas_indicativo=mysql_num_rows($sql_indicativo);
				for ($i=0; $i<$filas_indicativo; $i++){
									
					$row=mysql_fetch_array($sql_indicativo);
					echo "<option value='".$row['indicativo']."'>".$row['indicativo']."</option>";
					}

			?>
    
   
  
</select></td>
		</tr>
<tr>
<td><input type="radio" name="rango" onclick="valorActual(this.value)" value="1" checked>SEMANAL</td>
<td>fecha</td>
</tr>
<tr>
<td><input type="radio" name="rango" onclick="valorActual(this.value)" value="2" >MENSUAL</td>
<td>	<input name="diafin" id="diafin" type="hidden" value="1" /> 
	<select name="mesmensual" id="mesmensual">
	<?PHP 
					$meses = array("","Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
					
					for ($i=1;$i<=12;$i++) {
						if($i==date("n")){
							?>
					<option value='<?echo $i?>' selected><?echo $meses[$i]?></option>
					<?PHP
							}else{
						?><option value='<?echo $i?>'><?echo $meses[$i]?></option><?
					}
					}
					?>
	</select> 
	<select name="annomensual" id="annomensual" ><?PHP
					
						for ($i=2007;$i<=date("Y");$i++) {
						if($i==date("Y")){
							?>
					<option value='<?echo $i?>' selected><?echo $i?></option>
					<?PHP
							}else{
							?><option value='<?echo $i?>'><?echo $i?></option><?
						}
						}
					?></select>
	</td>
</tr>
<tr>
<td><input type="radio" name="rango" onclick="valorActual(this.value)" value="3" >TRIMESTRAL</td>
<td><select name="trimestral" id="trimestral">
	<option value="01">ENERO</option>
	<option value="02">FEBRERO</option>
	<option value="03">MARZO</option>
	<option value="04">ABRIL</option>
	<option value="05">MAYO</option>
	<option value="06">JUNIO</option>
	<option value="07">JULIO</option>
	<option value="08">AGOSTO</option>
	<option value="09">SEPTIEMBRE</option>
	<option value="10">OCTUBRE</option>
	<option value="11">NOVIEMBRE</option>
	<option value="12">DICIEMBRE</option>
	</select></td>
</tr>
<tr>
<td colspan="2"><input  type="button" value="VER" onclick="enviar();" /></td>

</tr>
	</table>
</fieldset>
<?

	if($_POST['valorOpcion']==2){
		$indicativos=array();
		$indicativos=$_POST['indicativo'];
		//print_r($indicativos);
		echo "<script>alert('".count($indicativos)."');</script>";
		for ($x=0;$x<count($indicativos);$x++){
			echo "<script>alert('".$indicativos[$x]."');</script>";
		//creo las fechas de mes inicio y fin para realizar la busqueda
		$fecha_inicio=$_POST['annomensual']."-".$_POST['mesmensual']."-01";
			
		$fecha_fin=$_POST['annomensual']."-".$_POST['mesmensual']."-".$_POST['diafin'];
		$sql2=mysql_query("select * from horas where indicativo='".$indicativos[$x]."' and fecha_entrada>='".$fecha_inicio."' and fecha_entrada<='".$fecha_fin."'");
		$filas=mysql_num_rows($sql2);
		echo $indicativos[$x];
		?>
		<table border="1">
			<tr>
				<td>FECHA INICIO</td>
				<td>FECHA FIN</td>
				<td>HORA INICIO</td>
				<td>HORA FIN</td>
				<td>PREVENTIVO</td>
				<td>HORAS</td>
			</tr>
		<?
		$total_minutos=0;
		for ($i=0; $i<$filas; $i++)
					{
					
					$row=mysql_fetch_array($sql2);
					$fecha_entrada = mktime(substr($row['hora_entrada'],0,2), substr($row['hora_entrada'],3,2), 0, substr($row['fecha_entrada'],8,2), substr($row['fecha_entrada'],0,4), substr($row['fecha_entrada'],5,2));
					if($row['hora_salida']!=null){
							$fecha_salida = mktime(substr($row['hora_salida'],0,2), substr($row['hora_salida'],3,2), 0, substr($row['fecha_salida'],8,2), substr($row['fecha_salida'],0,4), substr($row['fecha_salida'],5,2));
							$resultado=round($fecha_salida - $fecha_entrada);

							
							//lo paso a minutos
							$minutos2=$resultado/60;
							echo "<script>alert('minutos-".$resultado."');</script>";
							$total_minutos=$total_minutos+$minutos2;
							
							if($minutos2%60==0){//son horas exactas
								$hora=$minutos2/60;
								$minutos=0;
							}else{
								$horapro=$minutos2/60;
								$f=split("\.",$horapro);
								$hora=$f[0];
								$minutos=$minutos2%60;
			
			
							}
					}
					echo "<tr align='center'>";
					echo "<td>".$row['fecha_entrada']."</td>";
					if($row['fecha_salida']!=null){
						echo "<td>".$row['fecha_salida']."</td>";
					}else{
						echo "<td></td>";
					}
					
					echo "<td>".$row['hora_entrada']."</td>";
					if($row['hora_salida']!=null){
						echo "<td>".$row['hora_salida']."</td>";
					}else{
						echo "<td></td>";
					}
					
					echo "<td>".$row['preventivo']."</td>";
					if($row['fecha_salida']!=null){
						echo "<td>".$hora.":";
					
						if(strlen($minutos)==1){
							
						echo "0".$minutos."</td></tr>";}else{
						echo $minutos."</td></tr>";
						
						}
					}else{
						echo "<td></td>";
					}
					
		
		}?>
		
		<tr>
			<td colspan="5" align="right">TOTAL:</td>
			<td align='center'><?
			//lo paso a minutos
							
							if($total_minutos%60==0){//son horas exactas
								$hora=$total_minutos/60;
								$minutos=0;
							}else{
								$horapro=$total_minutos/60;
								$f=split("\.",$horapro);
								$hora=$f[0];
								$minutos=$total_minutos%60;
							}
		
				echo $hora.":";
					
						if(strlen($minutos)==1){
							
						echo "0".$minutos;}else{
						echo $minutos;
						
						}
				?>
			</td>
		</tr>
		</table><?
		}
	}
?>

</form>
<form name="ex" id="ex" action="paginas/generar_excel/generar_excel.php" method="POST">
</form>
<input type="button" name="exportar" id="exportar" value="exportar" onclick="env();" />


<script type="text/javascript">
	
	
</script>






