

<style type="text/css">
	 #itsthetable { font: 11px Verdana, Arial, Helvetica, sans-serif; }
.estila { color:#f60;	text-decoration:none; }
.estila:hover { text-decoration:underline; }
.estila:visited { color:#ffb17d; }
.estiltable caption{ font-family:Georgia, "Times New Roman", Times, serif; }
.estiltable caption, .estilth, .estiltfoot .estiltd { font-family:Georgia, "Times New Roman", Times, serif; }
.estiltable caption { font-size:40px; font-weight:normal; font-variant:small-caps;
	color:#FF9900;	letter-spacing:.3em; text-align:center; padding-bottom:.5em; }
.estiltable { width:650px; border-collapse:collapse; border:4px solid #FF9900;}
.estiltd,.estilth { padding:5px; }
	 /* THEAD*/
.estilthead .estilth { font-size:15px; font-weight:normal; font-variant:small-caps;
	color:#fff2ea;	background-color:#FF9900; }
	 /* TBODY */
.estiltbody .estiltd, .estiltbody .estilth { line-height:140%; background-color:#fff; color:#666; }
.estiltbody .estiltr.odd .estiltd, .estiltbody .estiltr.odd .estilth { background-color:#fff2ea;	border:1px solid #FF9900; border-width:1px 0; }
.estiltbody .estilth .estila { font-size:13px; font-weight:normal; font-variant:small-caps; }

/* ONLY REAL GOOD BROWSERS */
/* GENERAL */
.estiltbody td+td+td+td {	text-align:right; }
.estiltbody tr th:before { content:"\2588\00A0\00A0"; color:#FF9900;}
.estiltbody td+td+td+td:before { content:"\21E9"; color:#FF9900; }
/* HOVER */
.estiltbody .estiltr:hover .estiltd, .estiltbody .estiltr:hover .estilth:before { color:#000; }
.estiltbody .estiltr:hover .estiltd .estila, .estiltbody .estiltr:hover .estilth .estila { color:#000; text-decoration:underline; }
.estiltbody .estiltr:hover .estiltd .estila:visited, .estiltbody .estiltr:hover .estilth a:visited { color:#ccc; }
.estiltbody .estiltr:hover .estiltd+.estiltd+.estiltd+.estiltd a { font-weight:bold; }
.estiltbody .estiltr:hover .estiltd+.estiltd+.estiltd+.estiltd:before { color:#000; }
	 
form.listado fieldset {
	margin-bottom: 10px;
		
}
form.listado legend {
  padding: 0 2px;
  font-weight: bold;
}
form.listado label {
  display: inline-block;
  line-height: 1.8;
  vertical-align: top;
}


form.listado em {
  font-weight: bold;
  font-style: normal;
  color: #f00;
}
form.listado label {
  width: 120px; /* Width of labels */
}

</style>
<script>
 function mos(){

  //alert(document.getElementById("input-fill").value);
  document.getElementById("listado").submit();
  }
function cambiarImagen(obj, ok) {
		var marcada = obj.src.indexOf("/_") > 0;
		
		if (ok) {
			if (!marcada) {
			  var ruta = obj.src.substring(
				0, 
				obj.src.lastIndexOf("/")+1)+
				"_"+obj.src.substring(
					obj.src.lastIndexOf("/")+1);
			  obj.src = ruta;
			}
		} else {
			if (marcada) {
				var ruta = ""+obj.src.substring(
					0, obj.src.lastIndexOf("_"))+
					obj.src.substring(
						obj.src.lastIndexOf("/")+2);
				obj.src = ruta;
			}
		}
	
	}
	function intro(e) { 
  tecla = document.all ? e.keyCode : e.which; 
  if(tecla==113) alert('Gracias por pulsarme'); 
} 

	function intro(e){
tecla=(document.all) ? e.keyCode : e.which;

if(tecla==13) {

window.event.keyCode=0;

alert("has apretado intro");

}

}
function iSubmitEnter(oEvento, oFormulario){ 
     var iAscii; 

     if (oEvento.keyCode) 
         iAscii = oEvento.keyCode; 
     else if (oEvento.which) 
         iAscii = oEvento.which; 
     else 
         return false; 
	alert(iAscii+"ddwwww");
     if (iAscii == 13) oFormulario.submit(); 

     return true; 
}
</script>
<script type="text/javascript" src="js/bsn.AutoSuggest_c_2.0.js"></script>

<link rel="stylesheet" href="css/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" />
<!--
<style type="text/css">

	body {
		font-family: Lucida Grande, Arial, sans-serif;
		font-size: 10px;
		text-align: center;
		margin: 0;
		padding: 0;
	}
	
	table
	{
		border: 1px;
		background-color: #999;
		font-size: 10px;
	}
	tr
	{
		vertical-align: top;
	}
	th
	{
		text-align: left;
		background-color: #ccc;
	}
	th,
	td
	{
		padding: 2px;
		font-family: Lucida Grande, Arial, sans-serif;
		font-size: 1.2em;
	}
	td
	{
		background-color: #fff;
	}
	
	a {
		font-weight: bold;
		text-decoration: none;
		color: #f30;
	}
	
	a:hover {
		color: #fff;
		background-color: #f30; 
	}
	
	#wrapper {
		width: 600px;
		margin: 10px auto;
		text-align: left;
	}
	
	#content {
		font-size: 1.2em;
		line-height: 1.8em;
	}
	
	#content h1 {
		font-size: 1.6em;
		border-bottom: 1px solid #ccc;
		padding: 5px 0 5px 0;
	}

	#content h2 {
		font-size: 1.2em;
		margin-top: 3em;
	}








	label
	{
		font-weight: bold;
	}








	
</style>
-->




<form name="listado" action="?pag=listado.php" method="POST">
<?
//miro lo de los privilegios
 if(!isset($_SESSION['password'])){//sinohay session y en el campo session
 	?>
    <input type="hidden" name="privilegio" id="privilegio" value="no" />
    <?
 }else{
?>
    <input type="hidden" name="privilegio" id="privilegio" value="si" />
    <?
 }
?>

<div style="width:350px;">
		<fieldset>
		<legend>Buscar</legend>
        		
	
	<input type="hidden" id="sesion" name="sesion" value="no" style="width:300px"   />	
	
	<input type="text" id="testinput_xml" name="testinput_xml" value="<? echo $_POST['testinput_xml']; ?>" style="width:300px"   /> 
	<img src="./paginas/abajo.gif" border="0" onmouseover="cambiarImagen(this, true)" 
		     onmouseout="cambiarImagen(this, false)" class="boton" 
			 onclick="mos()" alt="Mostrar" />

 </fieldset>
 </div>
<?


if($_POST['testinput_xml']||$_GET['nombre']||$_GET['nombres']){

@ $db = mysql_pconnect("localhost","root","");
        if (!$db){
            echo "Error: no se puede acceder a la base de datos";
            exit;
        }
        mysql_select_db("agenda");
		if($_POST['testinput_xml']){
			$bus=$_POST['testinput_xml'];
		}else if($_GET['nombre']){
			$bus=$_GET['nombre'];
		}else if($_GET['nombres']){
			$bus=$_GET['nombres'];
		}
		
		if($bus==""){
		$sql=mysql_query("select * from datos");
		}else{
			//mirar lo de las busquedas
			if($_GET['nombres']){
				if(!isset($_SESSION['password'])){
					$sql=mysql_query("select * from datos where nombre='".$_GET['nombres']."' and privilegio='no'");
				}else{
					$sql=mysql_query("select * from datos where nombre='".$_GET['nombres']."'");
				}
			}else{
				if(!isset($_SESSION['password'])){
					$sql=mysql_query("select * from datos where nombre LIKE '".$bus."%' and privilegio='no'");
				}else{
						$sql=mysql_query("select * from datos where nombre LIKE '".$bus."%'");
				}
			}
		
		}
		$filas=mysql_num_rows($sql);
	
		if($filas==1||$_GET['nombres']){
		
			$result=mysql_fetch_array($sql);
			
			?>
            <center>
<br />

<table>
	<tr>
        <td>NOMBRE:</td>
        <td><? echo $result['nombre'];?></td>
        <td>DIRECCION:</td>
        <td><? echo $result['direccion'];?></td>
    </tr>
    <tr>
        <td>TELEFONO 1:</td>
        <td><? echo $result['telefono1'];?></td>
        <td>TELEFONO 2:</td>
        <td><? echo $result['telefono2'];?></td>
    </tr>
    <tr>
        <td>FAX:</td>
        <td><? echo $result['fax'];?></td>
        <td>EMAIL:</td>
        <td><? echo $result['email'];?></td>
    </tr>
    <tr>
    	<td valign="top">COMENTARIOS:</td>
        <td colspan="3"><textarea name="comentarios" rows="10" cols="30" readonly><? echo $result['comentarios'];?></textarea>
</td>
     </tr>
     <?
	 if(isset($_SESSION['password'])){
		 ?>
         	<tr align="center">
            	<td colspan="2"><br />
            		<a href='index.php?pag=modificar.php&nombre=<? echo $result['nombre'];?>' style="text-decoration:none"> <font color="#0000FF" style="font-family:Arial, Helvetica, sans-serif; font-size:14px"><b>MODIFICAR</b></font></a>
                </td>
                <td colspan="2"><br />
              	  <a href='index.php?pag=eliminar.php&nombre=<? echo $result['nombre'];?>' style="text-decoration:none"><font color="#0000FF" style="font-family:Arial, Helvetica, sans-serif; font-size:14px"><b>ELIMINAR</b></font></a>
                </td>
            </tr>
            
         <?
	 }
	 ?>
    
    
</table>

            <?
		}else{
		?>
        	<table class="estiltable">
            <!--    <tr>
                    <td>NOMBRE</td>
                    <td>TELEFONO</td>
                    <td>DIRECCION</td>
                    <td>FAX</td>
                    <td>DETALLES</td>
                   
                </tr>-->
                <caption>CONTACTOS</caption>
			<thead class="estilthead">
				<tr class="estiotr">
               
					<th scope="col" class="estilth">NOMBRE</th>
					<th scope="col" class="estilth">TELEFONO</th>
					<th scope="col" class="estilth">DIRECCION</th>
					<th scope="col" class="estilth">FAX</th>
					<th scope="col" class="estilth">DETALLES</th>
				</tr>
			</thead>
            <tbody class="estiltbody">
        <?
			if($filas>1){
				for ($i=0; $i<$filas; $i++)
					{
					
					$row=mysql_fetch_array($sql);
					if($row['privilegio']=="si"){
					echo "<tr   style='background-color:#CCCCCC;border:1px solid #FF9900; border-width:1px 0;'>";
					}else{
					echo "<tr class='odd'>";
					}
				
				echo "<td class='estiltd'>";
				echo $row['nombre'];
				echo "</td><td class='estiltd'>";
				echo $row['telefono1'];
				echo "</td><td class='estiltd'>";
				echo $row['direccion'];
				echo "</td><td class='estiltd'>";
					echo $row['fax'];
					echo "</td><td class='estiltd' align='center'><a href='index.php?pag=listado.php&nombres=".$row['nombre']."' class='estila' >detalles<a/>";
					echo "</td></tr>";
					
					}
			}
			?></tbody></table>
            <?
		}
}
?>
</form>



<script type="text/javascript">
	var options = {
	
		script:"test.php?json=true&",
		varname:"input",
		json:true,
		callback: function (obj) { document.getElementById('testid').value = obj.id; }
	};
	var as_json = new AutoSuggest('testinput', options);
	
	
	var options_xmlsi = {
	//alert("entra");
		script:"testsi.php?",
		varname:"input"
	};
	var options_xmlno = {
	//alert("entra");
		script:"testno.php?",
		varname:"input"
	};
	
	if(document.getElementById('privilegio').value=="si"){
	
	var as_xml = new AutoSuggest('testinput_xml', options_xmlsi);
	}else if(document.getElementById('privilegio').value=="no"){
	
	var as_xml = new AutoSuggest('testinput_xml', options_xmlno);
	}
	
</script>






