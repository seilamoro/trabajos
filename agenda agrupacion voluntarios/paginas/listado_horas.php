

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
		function asignaDias(dia,mes,anyo){
		
            comboDias = document.getElementById(dia);
            comboMeses = document.getElementById(mes);
            comboAnyos = document.getElementById(anyo);

            Month = comboMeses[comboMeses.selectedIndex].text;
            Year = comboAnyos[comboAnyos.selectedIndex].text;

            diasEnMes = cuantosDias(Month, Year);
            diasAhora = comboDias.length-1;

            if (diasAhora > diasEnMes){
                for (i=0; i<(diasAhora-diasEnMes); i++){
				
                    comboDias.options[comboDias.options.length - 1] = null
                }
            }
            if (diasEnMes > diasAhora){
                for (i=0; i<(diasEnMes-diasAhora); i++){
                    sumaOpcion = new Option(comboDias.options.length);
                    comboDias.options[comboDias.options.length]=sumaOpcion;
                }
            }
            if (comboDias.selectedIndex < 0)
              comboDias.selectedIndex = 0;
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
document.getElementById("excel").value=="si"; 
document.getElementById("horas").submit();
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
<input type="hidden" value="no" name="excel" id="excel">
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
<td>
<table width="50%" border="0">
					<tr>
					<td><? //date('j - n - Y'); ?>
					<select name="dia_semanal"  id="dia_semanal" class="input_formulario" >
					
					<?PHP 
					
					for ($i=1;$i<=31;$i++) {
						if(date('j')==$i){
						?>
					<option value='<?echo $i?>' selected><?echo $i?></option>
					<?PHP
						}else{
						?>
					<option value='<?echo $i?>'><?echo $i?></option>
					<?PHP
						}
					
					}
					?>
					
					</select></td><td><select name="mes_semanal" id="mes_semanal" onchange="asignaDias('dia_semanal','mes_semanal','anno_semanal')">
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
					</select></td><td>
					<select name="anno_semanal"  id="anno_semanal"  onchange="asignaDias('dia_semanal','mes_semanal','anno_semanal')">
					<?PHP
					
						for ($i=2007;$i<=date("Y");$i++) {
						if($i==date("Y")){
							?>
					<option value='<?echo $i?>' selected><?echo $i?></option>
					<?PHP
							}else{
							?><option value='<?echo $i?>'><?echo $i?></option><?
						}
						}
					?>
					</select>
					
					</td>
					</tr>
					</table>
					N� semanas:<select name="semanas_semanal"  id="semanas_semanal">
						<?
							for($i=1;$i<=100;$i++){
								?><option value='<?echo $i?>'><?echo $i?></option><?
								
							}
						?>
					</select>
</td>
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
<td><input type="radio" name="rango" onclick="valorActual(this.value)" value="3" >MESES</td>
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
function sacar_hora($minutos){
			
				if($minutos%60==0){//son horas exactas
								$hora=$minutos/60;
								$minutos=0;
								return $hora.":00";
							}else{
								$horapro=$minutos/60;
								
								$f=split("\.",$horapro);
								$hora=$f[0];
								$minutos_tol=$minutos%60;
							if(strlen($minutos_tol)==1){
							$minutos_tol="0".$minutos_tol;
								}
								return $hora.":".$minutos_tol;
							}
			}
	
		$indicativos=array();
		$datos_indicativos=array();
		$indicativos=$_POST['indicativo'];
		//print_r($indicativos);
		//echo "<script>alert('".count($indicativos)."');
		for ($x=0;$x<count($indicativos);$x++){
			
		//creo las fechas de mes inicio y fin para realizar la busqueda
		$fecha_inicio=$_POST['annomensual']."-".$_POST['mesmensual']."-01";
			
		if($_POST['valorOpcion']==2){
			$fecha_inicio=$_POST['annomensual']."-".$_POST['mesmensual']."-01";
			$fecha_fin=$_POST['annomensual']."-".$_POST['mesmensual']."-".$_POST['diafin'];
			
		}else if($_POST['valorOpcion']==1){
			
			$fecha_inicio=$_POST['anno_semanal']."-".$_POST['mes_semanal']."-".$_POST['dia_semanal'];
			$fecha_nueva=mktime(0,0,0,$_POST['mes_semanal'],$_POST['dia_semanal']+7*$_POST['semanas_semanal'],$_POST['anno_semanal']); 
			


$fecha_fin=date ("Y-m-d",$fecha_nueva);
		}
		$sql2=mysql_query("select * from horas where indicativo='".$indicativos[$x]."' and fecha_entrada>='".$fecha_inicio."' and fecha_entrada<='".$fecha_fin."'");
		$filas=mysql_num_rows($sql2);
		
		echo $indicativos[$x];
		$datos_indicativos[$x]['indicativo']=$indicativos[$x];
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
					$fecha_entrada = mktime(substr($row['hora_entrada'],0,2), substr($row['hora_entrada'],3,2), 0, substr($row['fecha_entrada'],5,2), substr($row['fecha_entrada'],8,2), substr($row['fecha_entrada'],0,4));
					if($row['hora_salida']!=null){
							$fecha_salida = mktime(substr($row['hora_salida'],0,2), substr($row['hora_salida'],3,2), 0, substr($row['fecha_salida'],5,2), substr($row['fecha_salida'],8,2), substr($row['fecha_salida'],0,4));
							$resultado=round($fecha_salida - $fecha_entrada);

							
							//lo paso a minutos
							$minutos2=$resultado/60;
							
							
							
							//miro las horas minimas
							//busco la ultima fecha
							$hora_minima=6*60;
							$minimo=0;
							if($row['preventivo']=="no"){
								
								if($minutos2<$hora_minima){
										$minimo=0;
										
								}else{
									$minimo=1;
									
								}
							}else{
								$minimo=1;	
							}
							if($minimo==1){
								$total_minutos=$total_minutos+$minutos2;
							}
							
							
							
					}
					echo "<tr align='center'>";
					echo "<td>".$row['fecha_entrada']."</td>";
					$datos_indicativos[$x][$i]['fecha_entrada']=$row['fecha_entrada'];
					if($row['fecha_salida']!=null){
						echo "<td>".$row['fecha_salida']."</td>";
						$datos_indicativos[$x][$i]['fecha_salida']=$row['fecha_salida'];
					}else{
					$datos_indicativos[$x][$i]['fecha_salida']="";
						echo "<td></td>";
					}
					
					echo "<td>".$row['hora_entrada']."</td>";
					$datos_indicativos[$x][$i]['hora_entrada']=$row['hora_entrada'];
					if($row['hora_salida']!=null){
						echo "<td>".$row['hora_salida']."</td>";
						$datos_indicativos[$x][$i]['hora_salida']=$row['hora_salida'];
					}else{
						echo "<td></td>";
						$datos_indicativos[$x][$i]['hora_salida']="";
					}
					
					echo "<td>".$row['preventivo']."</td>";
					$datos_indicativos[$x][$i]['preventivo']=$row['preventivo'];
					if($row['fecha_salida']!=null){
					$sacar_hora=sacar_hora($minutos2);
						if($minimo==1){
							
						$datos_indicativos[$x][$i]['sacar_hora']=$sacar_hora;
						$datos_indicativos[$x][$i]['minimo']="si";
							echo "<td>".$sacar_hora."</td></tr>";
						}else{
						$datos_indicativos[$x][$i]['sacar_hora']=$sacar_hora;
						$datos_indicativos[$x][$i]['minimo']="no";
							echo "<td><font color='FF0000'>".$sacar_hora."</font></td></tr>";
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
							$datos_indicativos[$x]['total_hora']=$hora.":"."0".$minutos;
						echo "0".$minutos;}else{
						echo $minutos;
						$datos_indicativos[$x]['total_hora']=$hora.":".$minutos;
						
						}
				?>
			</td>
		</tr>
		</table><?
		}
		$_SESSION['horas']=$datos_indicativos;
	//echo print_r($datos_indicativos);
	for($m=0;$m<=count($datos_indicativos);$m++){
		//echo $datos_indicativos[$m]['indicativo']."<br>";
		for($n=0;$n<=count($datos_indicativos[$m]);$n++){
		
			//echo $datos_indicativos[$m][$n]['fecha_entrada'];
		}
	}
	
?>
</form>


<a  href="paginas/pru.php" style="text-decoration:none"> <font color="#0099CC" style="font-family:Arial, Helvetica, sans-serif; font-size:14px"><b>IMPRIMIR</b></font></a>
<script type="text/javascript">
	
	
</script>






