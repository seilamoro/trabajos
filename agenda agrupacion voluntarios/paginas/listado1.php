<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "xhtml1-transitional.dtd">
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
     <style type="text/css">
	div.contenedor {
		position: relative;
		width: 30px;
	}
	
	.estiloinput {
		font-family: Arial;
		color: #008585;
		font-size: 8pt;
		border: 1px solid #008585;
		padding-left: 3px;
		width: 130px;
	}

	div.fill {
		font-family: Arial;
		font-size: 8pt;
		display: none;
		width: 128px;
		position:relative;
		color: #E0EBEB;
		background-color: #E0EBEB;
		border: 1px solid #008585;
		overflow: auto;
		height: 150px;
		top: -1px;
	}

	.estilotr.fill {
		font-family: Arial;
		font-size: 8pt;
		color: #E0EBEB;
		background-color: #008585;
		border: 1px solid #008585;
	}

	.estilotr {
		font-family: Arial;
		font-size: 8pt;
		background-color: #E0EBEB;
		color: #008585;
		border: 1px solid #E0EBEB;
	}
</style>
<script type="text/javascript">

	var IE = navigator.appName.toLowerCase().indexOf("microsoft") > -1;
	var Mozilla = navigator.appName.toLowerCase().indexOf("netscape") > -1;

	var textoAnt = "";
	var posicionListaFilling = 0;

	var datos = new Array();
	

	function ajaxobj() {
		try {
			_ajaxobj = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				_ajaxobj = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (E) {
				_ajaxobj = false;
			}
		}
	   
		if (!_ajaxobj && typeof XMLHttpRequest!='undefined') {
			_ajaxobj = new XMLHttpRequest();
		}
		
		return _ajaxobj;
	}
	
	function cargaLista(evt, obj, txt) {
		ajax = ajaxobj();
		ajax.open("GET", "paises.php?texto="+txt, true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				var datos = ajax.responseXML;
				var paises = datos.getElementsByTagName("pais");
				
				var listaPaises = new Array();
				if (paises) {
					for (var i=0; i<paises.length; i++) {
						listaPaises[listaPaises.length] = paises[i].firstChild.data;
					}
				}
				escribeLista(obj, listaPaises);
			}
		}
		ajax.send(null);
	}
	
	function escribeLista(obj, lista) {
		var html = "";
		var fill = document.getElementById('lista');
		
		if (lista.length == 0) {
			// Si la lista es vacia no la mostramos
			fill.style.display = "none";
		} else {
			// Creamos una tabla con 
			// todos los elementos encontrados
			fill.style.display = "block";
			var html='<table cellspacing="0" '+
				'cellpadding="0" border="0" width="100%">';
			for (var i=0; i<lista.length; i++) {
				html += '<tr id="tr'+obj.id+i+
					'" '+(posicionListaFilling == i? 
						' class="fill" ': '')+
					' onmouseover="seleccionaFilling(\'tr'+
					obj.id+'\', '+i+
					')" onmousedown="seleccionaTextoFilling(\'tr'+
					obj.id+'\', '+i+')">';
				html += '<td>'+lista[i]+'</td></tr>';
			}
			html += '</table>';
		}

		// Escribimos la lista
		fill.innerHTML = html;
	}

	// Muestra las coincidencias en la lista
	function inputFilling(evt, obj) {
		var fill = document.getElementById('lista');

		var elems = datos;
		
		var tecla = "";
		var lista = new Array();
		var res = obj.value;
		var borrar = false;
		
		// Almaceno la tecla pulsada
		if (!IE) {
		  tecla = evt.which;
		} else {
		  tecla = evt.keyCode;
		}
		
		var texto;
		// Si la tecla que pulso es una
		// letra o un espacio, o el intro
		// o la tecla borrar, almaceno lo 
		// que debo buscar
		if (!String.fromCharCode(tecla).match(/(\w|\s)/) && 
				tecla != 8 && 
				tecla != 13) {
			texto = textoAnt;
		} else {
			texto = obj.value;
		}
		
		textoAnt = texto;
		
		// Si el texto es distinto de vacio
		// o se pulsa ARRIBA o ABAJO
		// hago llamada AJAX para que 
		// me devuelva la lista de palabras
		// que coinciden con lo que hay
		// escrito en la caja
		if ((texto != null && texto != "") 
			|| (tecla == 40 || tecla == 38)) {
			cargaLista(evt, obj, texto);
		}
		
		
		// Según la letra que se pulse
		if (tecla == 37) { // Izquierda
			// No hago nada
		} else if (tecla == 38) { // Arriba
			// Subo la posicion en la
			// lista desplegable una posición
			if (posicionListaFilling > 0) {
				posicionListaFilling--;
			}
			// Corrijo la posición del scroll
			fill.scrollTop = posicionListaFilling*14;
		} else if (tecla == 39) { // Derecha
			// No hago nada
		} else if (tecla == 40) { // Abajo
			if (obj.value != "") {
				// Si no es la última palabra
				// de la lista
				if (posicionListaFilling < lista.length-1) { 
					// Corrijo el scroll
					fill.scrollTop = posicionListaFilling*14;
					// Bajo la posición de la lista
					posicionListaFilling++;
				} 
			}
		} else if (tecla == 8) { // Borrar <-
			// Se sube la lista del todo
			posicionListaFilling = 0;
			// Se permite borrar
			borrar = true;
		} else if (tecla == 13) { // Intro
			// Deseleccionamos el texto
			if (obj.createTextRange) {
				var r = obj.createTextRange();
				r.moveStart("character", 
					obj.value.length+1);
				r.moveEnd("character", 
					obj.value.length+1);
				r.select();
			} else if (obj.setSelectionRange) {
				obj.setSelectionRange(
					obj.value.length+1, 
					obj.value.length+1);
			}
			// Ocultamos la lista
			fill.style.display = "none";
			// Ponemos el puntero de 
			// la lista arriba del todo
			posicionListaFilling = 0;
			// Controlamos el scroll
			fill.scrollTop = 0;
			return true;
		} else {
			// En otro caso que siga
			// escribiendo
			posicionListaFilling = 0;
			fill.scrollTop = 0;
		}	
		
		// Si no se ha borrado
		if (!borrar) {
			if (lista.length != 0) {
				// Seleccionamos la parte del texto
				// que corresponde a lo que aparece
				// en la primera posición de la lista
				// menos el texto que realmente hemos
				// escrito
				obj.value = lista[posicionListaFilling];
				if (obj.createTextRange) {
					var r = obj.createTextRange();
					r.moveStart("character", 
						texto.length);
					r.moveEnd("character", 
						lista[posicionListaFilling].length);
					r.select();
				} else if (obj.setSelectionRange) {
					obj.setSelectionRange(
						texto.length, 
						lista[posicionListaFilling].length);
				}
			}
		}
		return true;
	}
  
  
	// Introduce el texto seleccionado
	function setInput(obj, fill) {
		obj.value = textoAnt;
		fill.style.display = "none";
		posicionListaFilling = 0;
	}

  
	// Cambia el estilo de
	// la palabra seleccionada
	// de la lista
	function seleccionaFilling(id, n) {
		document.getElementById(id + 
			n).className = "fill";
		document.getElementById(id + 
			posicionListaFilling).className = "";  	
		posicionListaFilling = n;
	}
  
	// Pasa el texto del filling a la caja
	function seleccionaTextoFilling (id, n) {
		textoAnt = document.getElementById(id + 
			n).firstChild.innerHTML;
		posicionListaFilling = 0;
	}
  	
 
	// Cambia la imagen cuando se pone 
	// encima el raton (nombre.ext 
	// por _nombre.ext)
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
  

  function mos(){

  //alert(document.getElementById("input-fill").value);
  document.getElementById("listado").submit();
  }

</script>
<?PHP session_start();



?>

<form name="listado" action="?pag=listado.php" method="POST">
<div style="width:250px;">
		<fieldset>
		<legend>Buscar</legend>
        		
	
		
	Nombre:


<!--<input class="estiloinput" type="text" id="input-fill" value="<? echo $_POST['input-fill']; ?>" name="input-fill" autocomplete="off" 
		onkeyup="inputFilling(event, this)" 
		onblur="setInput(this, document.getElementById('lista'))">
		&nbsp;
		<img src="./paginas/abajo.gif" border="0" onmouseover="cambiarImagen(this, true)" 
		     onmouseout="cambiarImagen(this, false)" class="boton" 
			 onclick="cargaLista(event, document.getElementById('input-fill'), '');mos()" alt="Mostrar" />
            
<div class="contenedor"><div id="lista" class="fill"></div></div>-->
 </fieldset>
 </div>
 <div>
<form method="get" action="">
	<label for="testinput_xml">Nombre</label>
	<input type="text" id="testinput_xml" value="" style="width:300px" /> 
	<br /><br /><input type="submit" value="submit" />
</form>
</div>



<script type="text/javascript">
	
	
	
	var options_xml = {
		script:"test.php?",
		varname:"input"
	};
	var as_xml = new AutoSuggest('testinput_xml', options_xml);
</script>


<br />
<?

if($_POST['input-fill']||$_GET['nombre']||$_GET['nombres']){

@ $db = mysql_pconnect("localhost","root","");
        if (!$db){
            echo "Error: no se puede acceder a la base de datos";
            exit;
        }
        mysql_select_db("agenda");
		if($_POST['input-fill']){
			$bus=$_POST['input-fill'];
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
			$sql=mysql_query("select * from datos where nombre='".$_GET['nombres']."'");
			
			}else{
			
			$sql=mysql_query("select * from datos where nombre LIKE '".$bus."%'");
			}
		
		}
		$filas=mysql_num_rows($sql);
	
		if($filas==1||$_GET['nombres']){
		
			$result=mysql_fetch_array($sql);
			
			?>
            <center>


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
         	<tr>
            	<td colspan="2">
            		<a href='index.php?pag=modificar.php&nombre=<? echo $result['nombre'];?>'>modificar</a>
                </td>
                <td colspan="2">
              	  <a href='index.php?pag=eliminar.php&nombre=<? echo $result['nombre'];?>'>eliminar</a>
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
				echo "<tr class='estiltr' class='odd'><td class='estiltd'>";
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