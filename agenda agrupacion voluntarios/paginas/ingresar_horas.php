

<style type="text/css">
	

</style>
<script>

function pru(){
Stamp = new Date();
var hora=Stamp.getHours();
var min= Stamp.getMinutes();
var anio= Stamp.getYear();

var mes=Stamp.getMonth();
var dia=Stamp.getDate();

alert(hora+":"+min+","+dia+"-"+mes+"-"+anio);

}
function lle(){
	Stamp = new Date();
var hora=Stamp.getHours();
var min= Stamp.getMinutes();
var anio= Stamp.getYear();

var mes=parseInt(Stamp.getMonth())+1;
var dia=Stamp.getDate();
var estado=true;
if(document.horas.indicativo.value==""){
estado=false;
	alert("Tiene que poner un indicativo");

}
if(estado==true){

				if(document.horas.preven.checked==true)
					document.horas.preventivo.value = 'si';
				else
					document.horas.preventivo.value = 'no';
if(confirm("Desea darse de alta en el servicio  con hora "+hora+":"+min+" a fecha de "+dia+"-"+mes+"-"+anio)){
			document.horas.llegada.value="si";
			
			document.horas.submit();
		 }
}
}
function sal(){
	Stamp = new Date();
var hora=Stamp.getHours();
var min= Stamp.getMinutes();
var anio= Stamp.getYear();

var mes=parseInt(Stamp.getMonth())+1;
var dia=Stamp.getDate();
estado=true;
if(document.horas.indicativo.value==""){
estado=false;
	alert("Tiene que poner un indicativo");

}
if(estado==true){
				if(document.horas.preven.checked==true)
					document.horas.preventivo.value = 'si';
				else
					document.horas.preventivo.value = 'no';
if(confirm("Desea darse de baja en el servicio con hora: "+hora+":"+min+" a fecha de "+dia+"-"+mes+"-"+anio)){
			document.horas.salida.value="si";
			
			document.horas.submit();
		 }
}
}

</script>
<script type="text/javascript" src="js/bsn.AutoSuggest_c_2.0.js"></script>

<link rel="stylesheet" href="css/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" />




<form name="horas" action="?pag=ingresar_horas.php" method="POST">
<?
@ $db = mysql_pconnect("localhost","root","");
        if (!$db){
            echo "Error: no se puede acceder a la base de datos";
            exit;
        	}
        mysql_select_db("agenda");
 	$fecha=date("Y-n-j");
	$hor=date("H:i");
if($_POST["llegada"]=="si"){
		
        
		
	$sql_insert="INSERT INTO horas (indicativo,fecha_entrada,hora_entrada,preventivo) VALUES ('". trim($_POST["indicativo"]) ."','".$fecha."','".$hor."','".$_POST["preventivo"]."')";
	
	$total=mysql_query($sql_insert);
	if($total==1){
		echo "<script>alert ('La hora de llegada se ha ingresado correctamente');</script>";
		}else{
		echo "<script>alert ('La hora de llegada NO se ha ingresado correctamente');</script>";
		}

	
	//echo "<script>alert ('');</script>";
	//echo "INSERT INTO horas (indicativo,fecha_entrada,hora_entrada,preventivo) VALUES ('". trim($_POST["indicativo"]) ."','".$fecha."','".$hor."','".$_POST["preventivo"]."')";
	echo "<script>document.horas.llegada.value='no';</script>";
	echo "<script>document.getElementById('horas').submit();</script>";
	
}
if($_POST["salida"]=="si"){
	$sql=mysql_query("select * from horas where indicativo='".trim($_POST["indicativo"])."' order by fecha_entrada desc");
	
	$result=mysql_fetch_array($sql);

	echo $result['indicativo']."--".$result['fecha_entrada']."--".$result['hora_entrada']."--".$result['fecha_salida'];
	 
	if(mysql_num_rows($sql)!=0 && $result['fecha_salida']==null){
		//echo "UPDATE datos SET fecha_salida='".$fecha."',hora_salida='". $hor ."' where indicativo='".$_POST['indicativo']."' and fecha_entrada='".$result['fecha_entrada']."' and hora_entrada='".$result['hora_entrada']."'";
		$sql_mo=mysql_query("UPDATE horas SET fecha_salida='".$fecha."',hora_salida='". $hor ."' where indicativo='".$_POST['indicativo']."' and fecha_entrada='".$result['fecha_entrada']."' and hora_entrada='".$result['hora_entrada']."'");
		if($sql_mo==1){
			echo "<script>alert ('La hora de salida se ha insertado correctamente');</script>";
		}else{
			echo "<script>alert ('La hora de salida NO se ha insertado correctamente');</script>";
		}
	}else{
		echo "<script>alert ('No hay hora de entrada que cerrar');</script>";
	}
	echo "<script>document.horas.salida.value='no';</script>";
	echo "<script>document.getElementById('horas').submit();</script>";
}
?>
<input type="hidden" value="no" name="llegada" id="llegada">
<input type="hidden" value="no" name="salida" id="salida">
<input type="hidden" value="no" name="preventivo" id="preventivo">
<fieldset>
<legend>INSERTAR HORA</legend>

	<table>
		<tr>
			<td>Indicativo:</td><td><input type="text" name="indicativo" id="indicativo"></td>
		</tr>
<tr>
<td>Preventivo</td>
<td><input type="checkbox" name="preven" id="preven"></td>
</tr>
<tr>
<td><input  type="button" value="LLEGADA" onclick="lle();" /></td>
<td><input  type="button" value="SALIDA" onclick="sal();" /></td>
</tr>
	</table>
</fieldset>

</form>



<script type="text/javascript">
	
	
</script>






