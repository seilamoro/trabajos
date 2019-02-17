<!-- SAM. Gestión de Incidencias.	
	 Autor. José Luis Gutiérrez Fernández.
	 Fecha. Agosto 2006 - Enero 2007. -->



<?

	//Comprobando que se tienen permisos para acceder a la página
	if(isset($_SESSION['permisoIncidencias']) && $_SESSION['permisoIncidencias'] == true) {

	//Código para volver a la página de búsquedas tras una modificación o una eliminación si se viene
	//de Búsquedas. Se ejectuta al principio para que no de tiempo a que haya un "pantallazo".
	if ( ($_GET['regreso'] == 'si') && ($_SESSION['busq'] == 'si') ) {
		
		//Caso de una modificación.
		if ($_GET['modificar'] == 'si') {
			// Coge los datos del formulario de "modificar incidencia". 
			$dni_mod =  ($_POST['dni_mod']);
			$nombre_mod = ($_POST['nombre_mod']);
			$apellido1_mod = ($_POST['apellido1_mod']);
			$apellido2_mod = ($_POST['apellido2_mod']);
			$empleado_mod = ($_POST['empleado_mod']);
			$concepto_mod =  ($_POST['concepto_mod']);
			$resolucion_mod = ($_POST['resolucion_mod']);
			$fecha_mod_dia = ($_POST['fecha_mod_dia']);
			$fecha_mod_dia = (int)$fecha_mod_dia;
			$fecha_mod_mes = ($_POST['fecha_mod_mes']);
			$fecha_mod_mes = (int)$fecha_mod_mes;
			$fecha_mod_anyo = ($_POST['fecha_mod_anyo']);
			$fecha_mod_anyo = (int)$fecha_mod_anyo;
			$fecha_mod = $fecha_mod_anyo."-".$fecha_mod_mes."-".$fecha_mod_dia;
			$dni_original = ($_POST['DNImod']);
			$fecha_original = $_POST['fecha_mod'];

			if($dni_mod && $fecha_mod && $nombre_mod && $empleado_mod && $concepto_mod) {

				//Se actualizan los datos del registro.
				$sql = "UPDATE incidencias SET DNI_Inc='".$dni_mod."', Fecha_Inc='".$fecha_mod."', Nombre_Inc='".$nombre_mod."', Apellido1_Inc='".$apellido1_mod."', Apellido2_Inc='".$apellido2_mod."', Concepto='".$concepto_mod."', Resolucion='".$resolucion_mod."', Nombre_Resp='".$empleado_mod."' WHERE DNI_Inc='".$dni_original."' AND Fecha_Inc = '".$fecha_original."'";
				$_SESSION['modificado'] = 'si';

				$result = mysql_query($sql);
			}	
		}


		//Caso de una eliminación.
		if($_GET['borrar'] == 'si') {
			
			$dni_elim = $_GET['dni_be'];
			$fechax_elim = $_GET['fecha_be'];
			$sql="DELETE FROM incidencias where DNI_Inc = '".$dni_elim."' AND Fecha_Inc = '".$fechax_elim."'";
			$resul = mysql_query($sql);
		}

?> 

<!-- Regresa al listado de búsquedas. -->
<?php

	echo "<script>location.href='?pag=listado_criterio_incidencias.php'</script>";

	} else {

?>


<!-- En caso de que no venga de búsqueda se carga la página. -->
<script language="javascript">

//Función que sitúa el foco de la aplicación en caso de que los bloques de nueva incidencia o 
//de modificación incidencia sean visibles.
function foco() {
	if (document.getElementById("alta_incidencia").style.display=="block") {
		document.nueva.dni_nueva.focus();
	}
	if (document.getElementById("modificar_incidencia").style.display=="block") {
		document.modificar.dni_mod.focus();
	}
}

//Cuando se ordena el listado por nombre, mantiene en la parte izquierda el bloque actual
function ordenar_nombre() {
		var l = location.href;
		var j = l.replace('&ordenar=dni', '');
		var k = j.replace('&ordenar=nombre', '');
		var x = k.replace('&ordenar=apellidos', '');
		var z = x.replace('&ordenar=fecha', '');
		var z2 = z.replace('#', '');
		l = z2 + "&ordenar=nombre";
		location.href = l;
}

//Cuando se ordena el listado por DNI, mantiene en la parte izquierda el bloque actual
function ordenar_dni() {
		var l = location.href;
		var j = l.replace('&ordenar=dni', '');
		var k = j.replace('&ordenar=nombre', '');
		var x = k.replace('&ordenar=apellidos', '');
		var z = x.replace('&ordenar=fecha', '');
		var z2 = z.replace('#', '');
		l = z2 + "&ordenar=dni";
		location.href = l;
}

//Cuando se ordena el listado por apellidos, mantiene en la parte izquierda el bloque actual
function ordenar_apellidos() {
		var l = location.href;
		var j = l.replace('&ordenar=dni', '');
		var k = j.replace('&ordenar=nombre', '');
		var x = k.replace('&ordenar=apellidos', '');
		var z = x.replace('&ordenar=fecha', '');
		var z2 = z.replace('#', '');
		l = z2 + "&ordenar=apellidos";
		location.href = l;
}

//Cuando se ordena el listado por fecha, mantiene en la parte izquierda el bloque actual
function ordenar_fecha() {
		var l = location.href;
		var j = l.replace('&ordenar=dni', '');
		var k = j.replace('&ordenar=nombre', '');
		var x = k.replace('&ordenar=apellidos', '');
		var z = x.replace('&ordenar=fecha', '');
		var z2 = z.replace('#', '');
		l = z2 + "&ordenar=fecha";
		location.href = l;
}

//Abre una ventana para buscar incidencias tras pinchar en la lupa.
function abrir_busqueda(n_gr, f_ll){
	window.open( "paginas/inc_busq_dni.php?form=0&g="+n_gr+"&f="+f_ll, "_blank", "width=650px,height=650px,resizable=no,toolbar=no,channelmode=no,directories=no,location=no,status=no,titlebar=no,top=30px,left=160px");
}
</script>


<!-- Estilos de los links en el listado. -->
<style>
	table A:link{
		text-decoration:none;
		color:#f3f3f3;
	}
	table A:hover{
		text-decoration:none;
		color:#f3f3f3;
	}
	table A:visited{
		text-decoration:none;
		color:#f3f3f3;
	}
</style>



<!-- Código Javascript que valida un formulario de "nueva incidencia". Comprueba que no queden
	 campos sin rellenar y que la fecha sea correcta.-->
<script language="javascript">
function valida_formulario() {
	
	//Valida DNI. No valida la letra, ya que los pasaportes de otras naciones pueden no tener letra. 
	//Los pasaportes de otras naciones también pueden contener varias letras.
	if(document.nueva.dni_nueva.value.length==0) {
		alert("Tiene que escribir el DNI");
		document.nueva.dni_nueva.focus();
		return 0;
	}

	//Valida nombre.
	if(document.nueva.nombre_nueva.value.length==0) {
		alert("Tiene que escribir el nombre");
		document.nueva.nombre_nueva.focus();
		return 0;
	}

	/*Valida primer apellido.
	if(document.nueva.apellido1_nueva.value.length==0) {
		alert("Tiene que escribir el primer apellido");
		document.nueva.apellido1_nueva.focus();
		return 0;
	}*/

	//Segundo apellido no se valida. Extranjeros podrían no tener un segundo apellido.

	//Valida fecha. Comprueba que el día seleccionado exista y no sea mayor que el día actual.
	var fecha = '<? echo date("d/m/Y");?>';
	var dia = document.nueva.fecha_nueva_dia.value;
	var mes = document.nueva.fecha_nueva_mes.value;
	var anyo = document.nueva.fecha_nueva_anyo.value;
	var fecha_array = fecha.split("/");

	dia = parseInt(dia, 10);
	mes = parseInt(mes, 10);
	anyo = parseInt(anyo, 10);

	if(anyo > parseInt(fecha_array[2], 10)) {
		alert("El año seleccionado es mayor que el actual");
		return 0;
	}

	if(anyo == parseInt(fecha_array[2], 10)) {
		
		if(mes > parseInt(fecha_array[1], 10)) {
			alert("El mes seleccionado es mayor que el actual");
			return 0;
		}

		if(mes == parseInt(fecha_array[1], 10)) {
			
			if(dia > parseInt(fecha_array[0], 10)) {
				alert("El día seleccionado es mayor que el actual");
				return 0;
			}
		}
	}

	var bisiestos = new Array(5);
	bisiestos[0] = 2004;
	bisiestos[1] = 2008;
	bisiestos[2] = 2012;
	bisiestos[3] = 2016;
	bisiestos[4] = 2020;

	var bisiesto = false;
	for(var i=0; i<5; i++) {
		if(anyo==bisiestos[i]) {
			bisiesto = true;
		}
	}
	
	if(bisiesto==true) {
		if( (mes==2) && (dia>29) ) { alert("La fecha seleccionada no existe"); return 0}
		if( (mes==4) && (dia>30) ) { alert("La fecha seleccionada no existe"); return 0}
		if( (mes==6) && (dia>30) ) { alert("La fecha seleccionada no existe"); return 0}
		if( (mes==9) && (dia>30) ) { alert("La fecha seleccionada no existe"); return 0}
		if( (mes==11) && (dia>30) ) { alert("La fecha seleccionada no existe"); return 0}
	}

	if(bisiesto==false) {
		if( (mes==2) && (dia>28) ) { alert("La fecha seleccionada no existe"); return 0}
		if( (mes==4) && (dia>30) ) { alert("La fecha seleccionada no existe"); return 0}
		if( (mes==6) && (dia>30) ) { alert("La fecha seleccionada no existe"); return 0}
		if( (mes==9) && (dia>30) ) { alert("La fecha seleccionada no existe"); return 0}
		if( (mes==11) && (dia>30) ) { alert("La fecha seleccionada no existe"); return 0}
	}

	//Valida nombre del empleado.
	if(document.nueva.empleado_nueva.value.length==0) {
		alert("Tiene que escribir el nombre del empleado");
		document.nueva.empleado_nueva.focus();
		return 0;
	}

	//Valida concepto.
	if(document.nueva.concepto_nueva.value.length==0) {
		alert("Tiene que escribir el concepto");
		document.nueva.concepto_nueva.focus();
		return 0;
	}

	//Valida resolución.
	//if(document.nueva.resolucion_nueva.value.length==0) {
		//alert("Tiene que escribir la resolución");
		//document.nueva.resolucion_nueva.focus();
		//return 0;
	//}

	//Finalmente envía el formulario.
	document.nueva.submit();
}

</script>





<!-- Código PHP que conecta a la base de datos. La acción puede ser "nueva", "modificar",
	 "eliminar" y "detalles". -->
<?
	
	//@ $db = mysql_pconnect("localhost","root","");
	@ $db = mysql_pconnect($_SESSION['conexion']['host'], $_SESSION['conexion']['user'], $_SESSION['conexion']['pass']);
	if(!$db) { 
		echo("ERROR DURANTE LA CONEXIÓN A LA BASE DE DATOS");
	} 

	//mysql_select_db("sam",$db);
	mysql_select_db($_SESSION['conexion']['db']);

	if($_GET['accion']) {
		$accion = $_GET['accion'];
	} else {
		$accion='nueva';
	}

	$block="style='display:block'";
	$none="style='display:none'";

?>




<!--Si viene de la página de "Búsquedas". Acción a realizar. -->
<?
	
	if( ($_GET['busqueda'] == 'si') && ($_GET['detalles'] == 'si') && ($_GET['dni']) ) {
		$accion='det';
	}

	if( ($_GET['busqueda'] == 'si') && ($_GET['modificar'] == 'si') && ($_GET['dni']) ) {
		$accion='mod';
	}

	if( ($_GET['busqueda'] == 'si') && ($_GET['eliminar'] == 'si') && ($_GET['dni']) ) {
		$accion='elim';
	}

?>


<!-- Formulario para volver a búsquedas cuando no se realiza ninguna acción. -->
<?
	if(($_SESSION['busq'] == 'si') || ($_GET['busqueda'] == 'si')) {
?>
	<form name='recoge_datos_bus' method="POST" action='?pag=listado_criterio_incidencias.php'>
	</form>
<?
	}
?>


<!-- Función que envía un formulario vacío y regresa al listado de búsquedas cuando no se ha realizado
	ninguna acción (botones cancelar o botón aceptar de detalles). -->
<script>
	function regresar(){
		document.recoge_datos_bus.submit();
	}
</script>



<!-- Código Javascript que valida un formulario de "modificar incidencia". Comprueba que no queden
	 campos sin rellenar y que la fecha sea correcta. -->
<script language="javascript">

function valida_formulario2() {
	//Valida DNI. No valida la letra, ya que los pasaportes de otras naciones pueden no tener letra. 
	//Los pasaportes de otras naciones también pueden contener varias letras.
	if(document.modificar.dni_mod.value.length==0) {
		alert("Tiene que escribir el DNI");
		document.modificar.dni_mod.focus();
		return 0;
	}

	//Valida nombre.
	if(document.modificar.nombre_mod.value.length==0) {
		alert("Tiene que escribir el nombre");
		document.modificar.nombre_mod.focus();
		return 0;
	}

	/*Valida primer apellido.
	if(document.modificar.apellido1_mod.value.length==0) {
		alert("Tiene que escribir el primer apellido");
		document.modificar.apellido1_mod.focus();
		return 0;
	}*/

	//Segundo apellido no se valida. Extranjeros podrían no tener un segundo apellido.

	//Valida fecha. Comprueba que el día seleccionado exista y no sea mayor que el día actual.
	var fecha = '<? echo date("d/m/Y");?>';
	var dia = document.modificar.fecha_mod_dia.value;
	var mes = document.modificar.fecha_mod_mes.value;
	var anyo = document.modificar.fecha_mod_anyo.value;
	var fecha_array = fecha.split("/");

	dia = parseInt(dia, 10);
	mes = parseInt(mes, 10);
	anyo = parseInt(anyo, 10);

	if(anyo > parseInt(fecha_array[2], 10)) {
		alert("El año seleccionado es mayor que el actual");
		return 0;
	}

	if(anyo == parseInt(fecha_array[2], 10)) {
		
		if(mes > parseInt(fecha_array[1], 10)) {
			alert("El mes seleccionado es mayor que el actual");
			return 0;
		}

		if(mes == parseInt(fecha_array[1], 10)) {
			
			if(dia > parseInt(fecha_array[0], 10)) {
				alert("El día seleccionado es mayor que el actual");
				return 0;
			}
		}
	}

	var bisiestos = new Array(5);
	bisiestos[0] = 2004;
	bisiestos[1] = 2008;
	bisiestos[2] = 2012;
	bisiestos[3] = 2016;
	bisiestos[4] = 2020;

	var bisiesto = false;
	for(var i=0; i<5; i++) {
		if(anyo==bisiestos[i]) {
			bisiesto = true;
		}
	}
	
	if(bisiesto==true) {
		if( (mes==2) && (dia>29) ) { alert("La fecha seleccionada no existe"); return 0}
		if( (mes==4) && (dia>30) ) { alert("La fecha seleccionada no existe"); return 0}
		if( (mes==6) && (dia>30) ) { alert("La fecha seleccionada no existe"); return 0}
		if( (mes==9) && (dia>30) ) { alert("La fecha seleccionada no existe"); return 0}
		if( (mes==11) && (dia>30) ) { alert("La fecha seleccionada no existe"); return 0}
	}

	if(bisiesto==false) {
		if( (mes==2) && (dia>28) ) { alert("La fecha seleccionada no existe"); return 0}
		if( (mes==4) && (dia>30) ) { alert("La fecha seleccionada no existe"); return 0}
		if( (mes==6) && (dia>30) ) { alert("La fecha seleccionada no existe"); return 0}
		if( (mes==9) && (dia>30) ) { alert("La fecha seleccionada no existe"); return 0}
		if( (mes==11) && (dia>30) ) { alert("La fecha seleccionada no existe"); return 0}
	}

	//Valida nombre del empleado.
	if(document.modificar.empleado_mod.value.length==0) {
		alert("Tiene que escribir el nombre del empleado");
		document.modificar.empleado_mod.focus();
		return 0;
	}

	//Valida concepto.
	if(document.modificar.concepto_mod.value.length==0) {
		alert("Tiene que escribir el concepto");
		document.modificar.concepto_mod.focus();
		return 0;
	}

	//Valida resolución.
	//if(document.modificar.resolucion_mod.value.length==0) {
		//alert("Tiene que escribir la resolución");
		//document.modificar.resolucion_mod.focus();
		//return 0;
	//}

	//Finalmente envía el formulario.
	document.modificar.submit();
}

</script>





<!-- Bloque que corresponde a todo el panel. -->
<!-- div 01: caja superior -->
<div id="caja_superior" style="width:100%">



<!-- Bloque que corresponde a la parte izquierda del panel. -->	
<!-- div 02: caja superior izda -->
<div id="caja_superior_izquierda" style='float:left;margin-left:0px;padding-left:0px;padding-right:0px; margin-top:0px;width:423px;'>


<?php

	function parteCadenas($cadena, $num) {
	$nom = $cadena;
	$nom_partido = "";
	
	//Separa las palabras distintas de la línea
	$array_nom = split(" ", $nom);
	for($u=0; $u<count($array_nom); $u++) {
		if(strlen($array_nom[$u]) > $num) {
			$palabras_largas = 'si';
		} else {
			$palabras_largas = 'no';
		}
	}

	if($palabras_largas == 'si') {
	for($b=0; $b<count($array_nom); $b++) {
		$cont_act = 0;
		//Separa por guiones
		$array_nom_i = split("-", $array_nom[$b]);
		if(count($array_nom_i) > 1) {
			$guiones = 'si';
		} else {
			$guiones = 'no';
		}
		for($j=0; $j<count($array_nom_i); $j++) {
			$cont_act = $cont_act +1;
					
			$resto = strlen($array_nom_i[$j]) % $num;
			$cociente = (strlen($array_nom_i[$j]) - $resto) / $num;
			
			
			if($guiones == 'no') {
				if(strlen($array_nom_i[$j]) > $num) {
					$cont = 0;
					for($n=1; $n<=$cociente; $n++) {
						if($nom_partido == "") {
							$nom_partido = $nom_partido." ".substr($array_nom_i[$j], $cont, $num);
							$cont = $cont + $num;
						} else {
							$nom_partido = $nom_partido."<br>".substr($array_nom_i[$j], $cont, $num);
							$cont = $cont + $num;
						}
					}
					$nom_partido = $nom_partido."<br>".substr($array_nom_i[$j], $cont, $resto);
				} else {
					$nom_partido = $nom_partido. " ".$array_nom_i[$j];
				} 

			} else {
				if(strlen($array_nom_i[$j]) > $num) {
					$cont = 0;
					for($n=1; $n<=$cociente; $n++) {
						if($nom_partido == "") {
							$nom_partido = $nom_partido." ".substr($array_nom_i[$j], $cont, $num);
							$cont = $cont + $num;
						} else {
							$nom_partido = $nom_partido."<br>".substr($array_nom_i[$j], $cont, $num);
							$cont = $cont + $num;
						}
					}
					$nom_partido = $nom_partido."<br>".substr($array_nom_i[$j], $cont, $resto);
				} else {
					if($nom_partido == "") {
						$nom_partido = $nom_partido. " ".$array_nom_i[$j]."-";
					} else {
						if($cont_act == count($array_nom_i)) {
							$nom_partido = $nom_partido. " ".$array_nom_i[$j];
						} else {
							$nom_partido = $nom_partido. " ".$array_nom_i[$j]."-";
						}
					}
				} 
			}
		}
	} 
	return $nom_partido;
	} else {
		return $cadena;
	}
} 

?>




<!--NUEVA INCIDENCIA-->
<?

	$n_gr = "no";
	$f_ll = "no";
	//Viene de "gestión de habitaciones". Tras realizar la operación, regresa.
	//Caso 1. Incidencia causada por un cliente individual.
	if( ($_GET['gdh']=='si') && ($_GET['dni']) ) {

		$dni_gdh = $_GET['dni'];
		$sql="SELECT * FROM cliente WHERE DNI_Cl = '".$dni_gdh."'";
		$result = mysql_query($sql);

		for ($i=0;$i<mysql_num_rows($result);$i++){
			$fila = mysql_fetch_array($result);
		}
			
		$dni_n = $fila['DNI_Cl'];
		$nombre_n = $fila['Nombre_Cl'];
		$apellido1_n = $fila['Apellido1_Cl'];
		$apellido2_n = $fila['Apellido2_Cl'];
		$empleado_n = "";
		$concepto_n = "";
		$resolucion_n = "";
		$link="?pag=incidencias.php&volver=si";
		$gdh_hab = 'si';
		$_SESSION['volver_gdh'] = 'si';
		
	//Caso 2. Incidencia causada por un grupo.
	} else if(($_GET['gdh']=='si') && ($_GET['nombre_gr']) && ($_GET['fecha_llegada'])) {
		$dni_n = "";
		$nombre_n = "";
		$apellido1_n = "";
		$apellido2_n = "";
		$empleado_n = "";
		$concepto_n = "";
		$resolucion_n = "";
		$link="?pag=incidencias.php&volver=si";
		$gdh_hab = 'si';
		$_SESSION['volver_gdh'] = 'si';
		$n_gr = $_GET['nombre_gr'];
		$f_ll = $_GET['fecha_llegada'];
		  
	//Caso 3. No se viene de "gestión de habitaciones".
	} else {
		$dni_n = "";
		$nombre_n = "";
		$apellido1_n = "";
		$apellido2_n = "";
		$empleado_n = "";
		$concepto_n = "";
		$resolucion_n = "";
		$link="?pag=incidencias.php";
		
		if($_GET['volver']=='si') {
			$_SESSION['volver_gdh'] = 'si';
			$gdh_hab = 'si';
		} else {
			$_SESSION['volver_gdh'] = 'no';
			$gdh_hab = 'no';
		}
	}

?>


<?php	

	/* Viene de "modificar". Actualiza los datos de una incidencia. */
	//Prescindible????
	if($_GET['modificar'] == 'si') {
		
		$dni_mod = $_GET['dni_modificar'];
		$sql="UPDATE incidencias SET DNI_Inc='".$dni_mod."', Fecha_Inc='".$fecha_mod."', Nombre_Inc='".$nombre_mod."', Apellido1_Inc='".$apellido1_mod."', Apellido2_Inc='".$apellido2_mod."', Concepto='".$concepto_mod."', Resolucion='".$resolucion_mod."', Nombre_Resp='".$empleado_mod."'";
		//echo $sql;
        $resul = mysql_query($sql);   
    }
		
		
	/* Viene de "borrar". Borra una incidencia seleccionada. */
	if($_GET['borrar'] == 'si'){
		
		$dni_elim = $_GET['dni_eliminar'];
		$fechaz_elim = $_GET['fecha_eliminar'];
        $sql="DELETE FROM incidencias where DNI_Inc = '".$dni_elim."' AND Fecha_Inc = '".$fechaz_elim."'";
        $resul = mysql_query($sql);
    }

?>

<? 

	/* Formulario que recoge los valores enviados por "alta incidencia" */
	$dni_nueva =  ($_POST['dni_nueva']);
	$nombre_nueva = ($_POST['nombre_nueva']);
	$apellido1 = ($_POST['apellido1_nueva']);
	$apellido2 = ($_POST['apellido2_nueva']);
	$empleado = ($_POST['empleado_nueva']);
	$concepto_nueva =  ($_POST['concepto_nueva']);
	$resolucion_nueva = ($_POST['resolucion_nueva']);
	$fecha_nueva_dia = ($_POST['fecha_nueva_dia']);
	$fecha_nueva_dia = (int)$fecha_nueva_dia;
	$fecha_nueva_mes = ($_POST['fecha_nueva_mes']);
	$fecha_nueva_mes = (int)$fecha_nueva_mes;
	$fecha_nueva_anyo = ($_POST['fecha_nueva_anyo']);
	$fecha_nueva_anyo = (int)$fecha_nueva_anyo;
	$fecha_nueva = $fecha_nueva_anyo."-".$fecha_nueva_mes."-".$fecha_nueva_dia;

	if( $dni_nueva && $fecha_nueva && $nombre_nueva	&& $empleado && $concepto_nueva ) {

		//Inserta la nueva incidencia.
		$sql = "INSERT INTO incidencias (DNI_Inc, Fecha_Inc, Nombre_Inc, Apellido1_Inc, Apellido2_Inc, Concepto, Resolucion, Nombre_Resp) VALUES ('$dni_nueva', '$fecha_nueva', '$nombre_nueva', '$apellido1', '$apellido2','$concepto_nueva', '$resolucion_nueva', '$empleado' )";
		$insertado='si';

		$result = mysql_query($sql);

		if( ($_SESSION['volver_gdh'] == 'si') && ($insertado =='si')  && ($gdh_hab == 'si') ) {
			?> <script language="javascript">window.location.href="?pag=gdh.php"</script> <?
		}
	}		
	
?>
		

<!-- div 13: alta incidencia -->
<div id='alta_incidencia' <?if($accion=='nueva'){print($block);}else{print($none);}?>>
<form name='nueva' method="POST" action=<? echo $link ?>>
<table border='0' width='100%' cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
<thead>
<td colspan='9' align='center' style="padding:0px 0px 0px 0px;">

<div class='champi_izquierda'>&nbsp;</div>
<div class='champi_centro'  style='width:363px;'>
Nueva Incidencia
</div>
<div class='champi_derecha'>&nbsp;</div>

</td>
</thead>
<table border="0" class='tabla_detalles' style="border: 1px solid #3F7BCC;" cellpadding="0" cellspacing="0" width="100%">
<tr height="40" style="padding-top:21px;">
<td width='39%' align='left' class='label_formulario'>D.N.I. / C.I.F:</td>
<td width='4%'></td>
<td width='57%' align='left'><input type='text' maxlength='11' value='<? echo $dni_n ?>' class='input_formulario' name='dni_nueva'>&nbsp;&nbsp;
<a href="#" id="lupa_alta" onClick="abrir_busqueda('<? echo $n_gr; ?>', '<? echo $f_ll; ?>');"><img src="../imagenes/botones/lupa.png" alt="Búsqueda por DNI" style="vertical-align:bottom;border:none;width:22px;height:22px;" /></a></td>
</tr>
<tr height='40'>
<td width='39%' align='left' class='label_formulario'>Nombre:</td>
<td width='4%'></td>
<td width='57%' align='left'><input type='text' onBlur='this.value=this.value.toUpperCase();' maxlength='50' value='<? echo $nombre_n ?>' name='nombre_nueva' class='input_formulario' ></td>
</tr>
<tr height='40'>
<td width='39%' align='left' class='label_formulario'>Primer Apellido:</td>
<td width='4%'></td>
<td width='57%' align='left'><input type='text' onBlur='this.value=this.value.toUpperCase();' maxlength='30' value='<? echo $apellido1_n ?>' name='apellido1_nueva' class='input_formulario' ></td>
</tr>
<tr height='40'>
<td width='39%' align='left' class='label_formulario'>Segundo Apellido:</td>
<td width='4%'></td>
<td width='57%' align='left'><input type='text' onBlur='this.value=this.value.toUpperCase();' maxlength='30' value='<? echo $apellido2_n ?>' name='apellido2_nueva' class='input_formulario' ></td>
</tr>
<tr height='40'>
<td width='30%' align='left' class='label_formulario'>Fecha:</td>
<td width='4%'></td>
<td width='57%' align='left'>
<SELECT class='select_formulario' name='fecha_nueva_dia'>
<?
	$actuald=date(d);
    for($i=01;$i<32;$i++){?>
		<option value="<?if($i<10) { $i="0".$i; } print($i);?>"<? if($i==$actuald){print("selected");}?>><?print($i);?>
    <?}?>
</SELECT>
<SELECT class='select_formulario' name='fecha_nueva_mes'>
	<?$actualm=date(m); ?>
		<option value="01"<? if('01'==$actualm){print("selected");}?>><?print("Enero");?>
		<option value="02"<? if('02'==$actualm){print("selected");}?>><?print("Febrero");?>
		<option value="03"<? if('03'==$actualm){print("selected");}?>><?print("Marzo");?>
		<option value="04"<? if('04'==$actualm){print("selected");}?>><?print("Abril");?>
		<option value="05"<? if('05'==$actualm){print("selected");}?>><?print("Mayo");?>
		<option value="06"<? if('06'==$actualm){print("selected");}?>><?print("Junio");?>
		<option value="07"<? if('07'==$actualm){print("selected");}?>><?print("Julio");?>
		<option value="08"<? if('08'==$actualm){print("selected");}?>><?print("Agosto");?>
		<option value="09"<? if('09'==$actualm){print("selected");}?>><?print("Septiembre");?>
		<option value="10"<? if('10'==$actualm){print("selected");}?>><?print("Octubre");?>
		<option value="11"<? if('11'==$actualm){print("selected");}?>><?print("Noviembre");?>
		<option value="12"<? if('12'==$actualm){print("selected");}?>><?print("Diciembre");?>
</SELECT>
<SELECT class='select_formulario' name='fecha_nueva_anyo'>
	<?$actual=date(Y);
    for($i=$actual;$i<($actual+3);$i++){?>
		<option value="<?print($i);?>"<? if($i==$actual){print("selected");}?>><?print($i);?>
    <?}?>
</SELECT>
</td>
</tr>
<tr height='40'>
<td width='39%' align='left' class='label_formulario'>Nombre del Empleado:</td>
<td width='4%'></td>
<td width='57%' align='left'><input type='text' maxlength='50' value='<? echo $empleado_n ?>' name='empleado_nueva' class='input_formulario' ></td>
</tr>
<tr height='100'>
<td width='39%' valign='top' align='left' class='label_formulario'>Concepto:</td>
<td width='4%'></td>
<td width='57%' align='left'><textarea maxlength='255' cols='31' rows='7' value='<? echo $concepto_n ?>' name='concepto_nueva' class='input_formulario'>
</textarea></td>
</tr>
<tr height='100'>
<td width='39%' valign='top' align='left' class='label_formulario'>Resolución:</td>
<td width='4%'></td>
<td width='57%' align='left'><textarea maxlength='255' cols='31' rows='7' value='<? echo $resolucion_n ?>' name='resolucion_nueva' class='input_formulario'>
</textarea></td>
</tr>
<input type="hidden" name="gdh" value='<? $gdh_hab ?>'>
<tr>

<?php
	if ($_GET['gdh']=='si') {
	$insertado = 'si';
?>
	<td align='right' height='65'> <a href='#' onClick="valida_formulario();"><img src='../imagenes/botones-texto/aceptar.jpg' border="0" alt="Aceptar la nueva incidencia"></a></td>
	<td></td>
	<td align='center' height='65'> <a href="?pag=gdh.php"><img src='../imagenes/botones-texto/cancelar.jpg' border="0" alt="Aceptar la nueva incidencia"></a></td>
<?
	} else {
?>
	<td colspan='9' align='center' height='65'> <a href='#' onClick="valida_formulario();"><img src='../imagenes/botones-texto/aceptar.jpg' border="0" alt="Aceptar la nueva incidencia"></a></td>
<?
	}
?>
</tr>
</table>
</table>
</form>

<!-- fin div 13: alta incidencia -->
</div>










<!--DETALLES INCIDENCIA-->
<!-- div 08: detalles incidencia -->
<div id='detalles_incidencia' <?if($accion=='det'){print($block);}else{print($none);}?>>

<table border='0' align='center' width="100%" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">


<?
	//Si viene de búsqueda.
	if( ($_GET['busqueda'] == 'si') && ($_GET['detalles'] == 'si') && ($_GET['dni']) && ($_GET['fecha_inc']) ) {
		
		$dni_det = $_GET['dni'];
		$fecha_det = $_GET['fecha_inc'];

		$sql = "Select * from incidencias where DNI_Inc = '".$dni_det."' AND Fecha_Inc = '".$fecha_det."'";

		$result = mysql_query($sql);

		for ($i=0;$i<mysql_num_rows($result);$i++){
			$fila = mysql_fetch_array($result);
		}
		
		$nombre_det = $fila['Nombre_Inc'];
		$apellidos_det = $fila['Apellido1_Inc']." ".$fila['Apellido2_Inc'];
		$apellido1_det = $fila['Apellido1_Inc'];
		$apellido2_det = $fila['Apellido2_Inc'];
		$fecha_det = $fila['Fecha_Inc'];
		$empleado_det = $fila['Nombre_Resp'];
		$concepto_det = $fila['Concepto'];
		$resolucion_det = $fila['Resolucion'];

		$fecha_partida = split("-", $fecha_det);
		$dia_p=$fecha_partida[2];
		$mes_p=$fecha_partida[1];
		$anio_p=$fecha_partida[0];
		$fecha_nueva = $dia_p."-".$mes_p."-".$anio_p;
		$link_busq = "?pag=listado_criterio_incidencias.php";

	 //Carga los datos de la persona seleccionada. 
	 } else {

		$dni_det = $_GET['dni_detalles'];
		$fecha_det = $_GET['fecha_detalles'];

		$sql = "Select * from incidencias where DNI_Inc = '".$dni_det."' AND Fecha_Inc = '".$fecha_det."'";

		$result = mysql_query($sql);

		for ($i=0;$i<mysql_num_rows($result);$i++){
			$fila = mysql_fetch_array($result);
		}
		
		$nombre_det = $fila['Nombre_Inc'];
		$apellidos_det = $fila['Apellido1_Inc']." ".$fila['Apellido2_Inc'];
		$apellido1_det = $fila['Apellido1_Inc'];
		$apellido2_det = $fila['Apellido2_Inc'];
		$fecha_det = $fila['Fecha_Inc'];
		$empleado_det = $fila['Nombre_Resp'];
		$concepto_det = $fila['Concepto'];
		$resolucion_det = $fila['Resolucion'];

		$fecha_partida = split("-", $fecha_det);
		$dia_p=$fecha_partida[2];
		$mes_p=$fecha_partida[1];
		$anio_p=$fecha_partida[0];
		$fecha_nueva = $dia_p."-".$mes_p."-".$anio_p;
		$link_busq ="?pag=incidencias.php";
	}
			
?>

<thead>
<td colspan="9" align="center" style="padding:0px 0px 0px 0px;">
<div class='champi_izquierda'>&nbsp;</div>
<div class='champi_centro'  style='width:363px;'>
Detalles de Incidencia
</div>
<div class='champi_derecha'>&nbsp;</div>
</td>					
</thead>

<table border="0" height='500px' width='100%' class='tabla_detalles' style="border: 1px solid #3F7BCC;" cellpadding="0" cellspacing="0" width="100%">
<tr height="40" style="padding-top:21px;">
<td width='40%' align='left'><span class='label_formulario'>D.N.I. / C.I.F.:</span></td>
<td width='60%' align='left'> <span class='texto_detalles'><? echo $dni_det; ?></span></td>
</tr>
<tr height='30'>
<td width='40%' align='left'><span class='label_formulario'>Nombre:</span> </td>
<td width='60%' align='left'><span class='texto_detalles'><? echo $nombre_det; ?></span></td>
</tr>
<tr height='30'>
<td width='40%' align='left'><span class='label_formulario'>Apellidos:</span> </td>
<td width='60%' align='left'><span class='texto_detalles'><? echo $apellido1_det." ".$apellido2_det; ?></span></td>
</tr>
<tr height='30'>
<td width='40%' align='left'><span class='label_formulario'>Fecha:</span> </td>
<td width='60%' align='left'><span class='texto_detalles'><? echo $fecha_nueva ?></span></td>
</tr>

<?

	//Para partir adecuadamente los nombres de empleados que sean demasiado largo.
	$nom_empleado = $empleado_det;
	$nom_empleado_partido = "";
	$array_nom_empleado = split(" ", $nom_empleado);
	for($b=0; $b<count($array_nom_empleado); $b++) {
		$array_nom_empleado_i = split("-", $array_nom_empleado[$b]);
		if(count($array_nom_empleado_i) > 1) {
			$guiones = 'si';
		} else {
			$guiones = 'no';
		}
		for($j=0; $j<count($array_nom_empleado_i); $j++) {
				
			if($guiones == 'no') {
				if(strlen($array_nom_empleado_i[$j]) > 25) {
					$nom_empleado_partido = $nom_empleado_partido." ".substr($array_nom_empleado_i[$j], 0, 25)."-<br>".substr($array_nom_empleado_i[$j], 25, 25);
				} else {
					$nom_empleado_partido = $nom_empleado_partido. " ".$array_nom_empleado_i[$j];
				} 
			} else {
				if(strlen($array_nom_i[$j]) > 25) {
					$nom_empleado_partido = $nom_empleado_partido." ".substr($array_nom_empleado_i[$j], 0, 25)."-<br>".substr($array_nom_empleado_i[$j], 25, 25);
				} else {
					if($nom_empleado_partido == "") {
						$nom_empleado_partido = $nom_empleado_partido. " ".$array_nom_empleado_i[$j];
					} else {
						$nom_empleado_partido = $nom_empleado_partido. "-".$array_nom_empleado_i[$j];
					}
				}
			} 
		}
	}

?>
<tr height='60'>
<td width='40%' align='left'><span class='label_formulario'>Nombre del Empleado:</span> </td>
<td width='60%' align='left'><span class='texto_detalles'><? echo $nom_empleado_partido; ?></span></td>
</tr>
<? 

	//Función para separar adecuadamente los textos con palabras excesivamente largas. Si el párrafo no
	//contiene palabras con más de 20 letras, entonces el sangrado es automático.
	$con_det = $concepto_det;
	$concepto_det_partido = split(" ", $concepto_det);
	$num_partes = count($concepto_det_partido);
	$palabras_largas = "falso";

	for($n=0; $n<$num_partes; $n++) {
		if(strlen($concepto_det_partido[$n]) > 20) {
			$palabras_largas="verdad";
		}
	}

	if($palabras_largas == "verdad") {
		$concepto_det = "";
		$restantes = 20;

		for($i=0; $i<$num_partes; $i++) {
			if(strlen($concepto_det_partido[$i]) > 20) {
				$n_car = strlen($concepto_det_partido[$i]);
				$n_sub = ($n_car / 20);
				$cont_car = 0;
				$subcadenas = array();
				for($j=0; $j<$n_sub; $j++) {
					$subcadenas[] = substr($concepto_det_partido[$i], $cont_car, 20);
					$cont_car = $cont_car + 20;
				}
				for($k=0; $k<count($subcadenas); $k++) {
					if($concepto_det == "") {
						$concepto_det = $subcadenas[$k]."-<br>";
						$restantes = 20;
					} else {
						if(strlen($subcadenas[$k]) < $restantes) {
							$concepto_det = $concepto_det." ".$subcadenas[$k]." ";
							$restantes = $restantes - strlen($subcadenas[$k]) - 1;
						} else { 
							$concepto_det = $concepto_det.$subcadenas[$k]."-<br>";
							$restantes = 20;	
						}
					}
				}
			} else { 
				if($concepto_det == "") {
					$concepto_det = $concepto_det_partido[$i]." ";
					$restantes = $restantes - strlen($concepto_det_partido[$i]) - 1;
				} else {
					if(strlen($concepto_det_partido[$i]) < $restantes) {
						$concepto_det = $concepto_det." ".$concepto_det_partido[$i]." ";
						$restantes = $restantes - strlen($concepto_det_partido[$i]) - 1;
					} else { 
						$concepto_det = $concepto_det."<br>".$concepto_det_partido[$i];
						$restantes = 20;
					}
				}
			}
		}
	} else { 
		$concepto_det = $concepto_det;
	}

?>
<tr height='120'>
<td width='40%' align='left' valign='top'><span class='label_formulario'>Concepto:</span> </td>
<td width='60%' align='left' valign='top'><textarea readonly maxlength='255' cols='31' rows='7' name='concepto_det' class='input_formulario'><? echo $con_det ?></textarea></td>
<!--<td width='60%' align='left' valign='top'><span class='texto_detalles'><? echo $concepto_det ?></span></td>-->
</tr>
<? 

	//Función para separar adecuadamente los textos con palabras excesivamente largas. Si el párrafo no
	//contiene palabras con más de 20 letras, entonces el sangrado es automático.
	$res_det = $resolucion_det;
	$resolucion_det_partido = split(" ", $resolucion_det);
	$num_partes = count($resolucion_det_partido);
	$palabras_largas = "falso";

	for($i=0; $i<$num_partes; $i++) {
		if(strlen($resolucion_det_partido[$i]) > 20) {
			$palabras_largas="verdad";
		}
	}

	if($palabras_largas == "verdad") {
		$resolucion_det = "";
		$restantes = 20;

		for($i=0; $i<$num_partes; $i++) {
			if(strlen($resolucion_det_partido[$i]) > 20) {
				$n_car = strlen($resolucion_det_partido[$i]);
				$n_sub = ($n_car / 20);
				$cont_car = 0;
				$subcadenas2 = array();
				for($j=0; $j<$n_sub; $j++) {
					$subcadenas2[] = substr($resolucion_det_partido[$i], $cont_car, 20);
					$cont_car = $cont_car + 20;
				}
				for($k=0; $k<count($subcadenas2); $k++) {
					if($resolucion_det == "") {
						$resolucion_det = $subcadenas2[$k]."-<br>";
						$restantes = 20;
					} else {
						if(strlen($subcadenas2[$k]) < $restantes) {
							$resolucion_det = $resolucion_det." ".$subcadenas2[$k]." ";
							$restantes = $restantes - strlen($subcadenas2[$k]) - 1;
						} else { 
							$resolucion_det = $resolucion_det.$subcadenas2[$k]."-<br>";
							$restantes = 20;
						}
					}
				}
			} else { 
				if($resolucion_det == "") {
					$resolucion_det = $resolucion_det_partido[$i]." ";
					$restantes = $restantes - strlen($resolucion_det_partido[$i]) - 1;
				} else {
					if(strlen($resolucion_det_partido[$i]) < $restantes) {
						$resolucion_det = $resolucion_det." ".$resolucion_det_partido[$i]." ";
						$restantes = $restantes - strlen($resolucion_det_partido[$i]) - 1;
					} else { 
						$resolucion_det = $resolucion_det."<br>".$resolucion_det_partido[$i];
						$restantes = 20;
					}
				}
			}
		}
	} else { 
		$resolucion_det = $resolucion_det;
	}

?>
<tr height='115'>
<td width='40%' align='left' valign='top'><span class='label_formulario'>Resolución:</span> </td>
<!--<td width='60%' align='left' valign='top'><span class='texto_detalles'><? echo $resolucion_det ?></span></td>-->
<td width='60%' align='left' valign='top'><textarea readonly maxlength='255' cols='31' rows='7' name='resolucion_det' class='input_formulario'><? echo $res_det ?></textarea></td>
</tr>
<tr height='84'>
<?
	if($_GET['busqueda']=='si') {
?>
	<td colspan='2' align='center'><img src='../imagenes/botones-texto/aceptar.jpg' style="cursor:pointer;" onClick='regresar();' border="0" alt="Volver a Nueva Incidencia"></a></td>
<? 
	} else {
?>
<td colspan='2' align='center'><a href='?pag=incidencias.php'><img src='../imagenes/botones-texto/aceptar.jpg' border="0" alt="Volver a Nueva Incidencia"></a></td>
<?
	}
?>
</tr>

</table>
</table>

<!-- fin div 08: detalles incidencia -->
</div>










<!--MODIFICAR INCIDENCIA-->
<? 

	/* Coge los datos del formulario de "modificar incidencia". */
	$dni_mod =  ($_POST['dni_mod']);
	$nombre_mod = ($_POST['nombre_mod']);
	$apellido1_mod = ($_POST['apellido1_mod']);
	$apellido2_mod = ($_POST['apellido2_mod']);
	$empleado_mod = ($_POST['empleado_mod']);
	$concepto_mod =  ($_POST['concepto_mod']);
	$resolucion_mod = ($_POST['resolucion_mod']);
	$fecha_mod_dia = ($_POST['fecha_mod_dia']);
	$fecha_mod_dia = (int)$fecha_mod_dia;
	$fecha_mod_mes = ($_POST['fecha_mod_mes']);
	$fecha_mod_mes = (int)$fecha_mod_mes;
	$fecha_mod_anyo = ($_POST['fecha_mod_anyo']);
	$fecha_mod_anyo = (int)$fecha_mod_anyo;
	$fecha_mod = $fecha_mod_anyo."-".$fecha_mod_mes."-".$fecha_mod_dia;
	$dni_original = ($_POST['DNImod']);

	if($dni_mod && $fecha_mod && $nombre_mod && $empleado_mod && $concepto_mod) {

		$sql = "UPDATE incidencias SET DNI_Inc='".$dni_mod."', Fecha_Inc='".$fecha_mod."', Nombre_Inc='".$nombre_mod."', Apellido1_Inc='".$apellido1_mod."', Apellido2_Inc='".$apellido2_mod."', Concepto='".$concepto_mod."', Resolucion='".$resolucion_mod."', Nombre_Resp='".$empleado_mod."' WHERE DNI_Inc='".$dni_original."' AND Fecha_Inc='".$fecha_mod."'";
		$_SESSION['modificado'] = 'si';

		$result = mysql_query($sql);
	}			
?>

<?
	//Si viene de búsqueda
	if( ($_GET['busqueda']=='si') && ($_GET['modificar']=='si') && ($_GET['dni']) && ($_GET['fecha_inc']) ) {
		$dni_mod = $_GET['dni'];
		$fecha_mod = $_GET['fecha_inc'];
		$_SESSION['busq'] = 'si';


		$sql = "Select * from incidencias where DNI_Inc = '".$dni_mod."' AND Fecha_Inc = '".$fecha_mod."'";

		$result = mysql_query($sql);

		for ($i=0;$i<mysql_num_rows($result);$i++){
			$fila = mysql_fetch_array($result);
		}
		
		$nombre_mod = $fila['Nombre_Inc'];
		$apellido1_mod = $fila['Apellido1_Inc'];
		$apellido2_mod = $fila['Apellido2_Inc'];
		$fecha_mod = $fila['Fecha_Inc'];
		$empleado_mod = $fila['Nombre_Resp'];
		$concepto_mod = $fila['Concepto'];
		$resolucion_mod = $fila['Resolucion'];
		$fecha_des = split("-", $fecha_mod);

		$diar=$fecha_des[2];
		$mesr=$fecha_des[1];
		$anior=$fecha_des[0];
		$link_busq_mod = "?pag=incidencias.php&regreso=si&modificar=si";

	//Carga los datos de la persona seleccionada para que se puedan modificar. 
	} else {

		$dni_mod = $_GET['dni_modificar'];
		$fecha_modif = $_GET['fecha_modif'];

		$sql = "Select * from incidencias where DNI_Inc = '".$dni_mod."' AND Fecha_Inc = '".$fecha_modif."'";

		$result = mysql_query($sql);

		for ($i=0;$i<mysql_num_rows($result);$i++){
			$fila = mysql_fetch_array($result);
		}
		
		$nombre_mod = $fila['Nombre_Inc'];
		$apellido1_mod = $fila['Apellido1_Inc'];
		$apellido2_mod = $fila['Apellido2_Inc'];
		$fecha_mod = $fila['Fecha_Inc'];
		$empleado_mod = $fila['Nombre_Resp'];
		$concepto_mod = $fila['Concepto'];
		$resolucion_mod = $fila['Resolucion'];
		$fecha_des = split("-", $fecha_mod);

		$diar=$fecha_des[2];
		$mesr=$fecha_des[1];
		$anior=$fecha_des[0];
		$link_busq_mod ="?pag=incidencias.php";
	}

			
?>
<div id='modificar_incidencia' <?if($accion=='mod'){print($block);}else{print($none);}?>>
<form name="modificar" action="<? echo $link_busq_mod ?>" method="POST">
<table border='0' width='100%' cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;height:100%;">

<thead>
<td colspan='9' align='center' style="padding:0px 0px 0px 0px;">
<div class='champi_izquierda'>&nbsp;</div>
<div class='champi_centro'  style='width:363px;'>
Modificar Incidencia
</div>
<div class='champi_derecha'>&nbsp;</div>
</td>
</thead>
<table border="0" cellpadding="0" cellspacing="0" class='tabla_detalles' style="border: 1px solid #3F7BCC;" width='100%'>
<tr height="40" style="padding-top:21px;">
<td width='39%' align='left' class='label_formulario'>D.N.I. / C.I.F.:</td>
<td width='4%'></td>
<td width='57%' align='left'><input type='text' maxlength='11' class='input_formulario' name='dni_mod' value='<? echo $dni_mod ?>'></td>
</tr>
<tr height='40'>
<td width='39%' align='left' class='label_formulario'>Nombre:</td>
<td width='4%'></td>
<td width='57%' align='left'><input type='text' onBlur='this.value=this.value.toUpperCase();' maxlength='50' name='nombre_mod' class='input_formulario' value="<? echo $nombre_mod ?>"></td>
</tr>
<tr height='40'>
<td width='39%' align='left' class='label_formulario'>Primer Apellido:</td>
<td width='4%'></td>
<td width='57%' align='left'><input type='text' onBlur='this.value=this.value.toUpperCase();' maxlength='30' name='apellido1_mod' class='input_formulario' value='<? echo $apellido1_mod ?>'></td>
</tr>
<tr height='40'>
<td width='39%' align='left' class='label_formulario'>Segundo Apellido:</td>
<td width='4%'></td>
<td width='57%' align='left'><input type='text' onBlur='this.value=this.value.toUpperCase();' maxlength='30' name='apellido2_mod' class='input_formulario' value='<? echo $apellido2_mod ?>'></td>
</tr>
<tr height='40'>
<td width='39%' align='left' class='label_formulario'>Fecha:</td>
<td width='4%'></td>
<td width='57%' align='left'>
<SELECT class='select_formulario' name="fecha_mod_dia">
	<?for($i=1;$i<32;$i++){?>
		<option value="<?if($i<10) { $i="0".$i; } print($i);?>"<? if($i==$diar){print("selected");}?>><?print($i);?>
    <?}?>
</SELECT>
<SELECT class='select_formulario' name="fecha_mod_mes">
	<?$actualm=date(m); ?>
		<option value="01"<? if('01'==$mesr){print("selected");}?>><?print("Enero");?>
		<option value="02"<? if('02'==$mesr){print("selected");}?>><?print("Febrero");?>
		<option value="03"<? if('03'==$mesr){print("selected");}?>><?print("Marzo");?>
		<option value="04"<? if('04'==$mesr){print("selected");}?>><?print("Abril");?>
		<option value="05"<? if('05'==$mesr){print("selected");}?>><?print("Mayo");?>
		<option value="06"<? if('06'==$mesr){print("selected");}?>><?print("Junio");?>
		<option value="07"<? if('07'==$mesr){print("selected");}?>><?print("Julio");?>
		<option value="08"<? if('08'==$mesr){print("selected");}?>><?print("Agosto");?>
		<option value="09"<? if('09'==$mesr){print("selected");}?>><?print("Septiembre");?>
		<option value="10"<? if('10'==$mesr){print("selected");}?>><?print("Octubre");?>
		<option value="11"<? if('11'==$mesr){print("selected");}?>><?print("Noviembre");?>
		<option value="12"<? if('12'==$mesr){print("selected");}?>><?print("Diciembre");?>
</SELECT>
<SELECT class='select_formulario' name="fecha_mod_anyo">
	<?  $actual=date(Y);
		for($i=$actual;$i<($actual+3);$i++){?>
		<option value="<?print($i);?>"<? if($i==$anior){print("selected");}?>><?print($i);?>
    <?}?>
</SELECT>
</td>
</tr>
<tr height='40'>
<td width='39%' align='left' class='label_formulario'>Nombre del Empleado:</td>
<td width='4%'></td>
<td width='57%' align='left'><input type='text' maxlength='50' name='empleado_mod' class='input_formulario' value='<? echo $empleado_mod ?>'></td>
</tr>
<tr height='100'>
<td width='39%' valign='top' align='left' class='label_formulario'>Concepto:</td>
<td width='4%'></td>
<td width='57%' align='left'><textarea maxlength='255' cols='31' rows='7' name='concepto_mod' class='input_formulario'><? echo $concepto_mod ?></textarea></td>
</tr>
<tr height='100'>
<td width='39%' valign='top' align='left' class='label_formulario'>Resolución:</td>
<td width='4%'></td>
<td width='57%' align='left'><textarea maxlength='255' cols='31' rows='7' name='resolucion_mod' class='input_formulario'><? echo $resolucion_mod ?></textarea></td>
</tr>
<input type="hidden" name="DNImod" value="<?print($dni_mod);?>">
<input type="hidden" name="modificado_busqueda" value='<? echo $mb ?>'>
<input type="hidden" name="fecha_mod" value="<? print($fecha_mod); ?>">

<tr>
<td align='right' height='65'> <a href='#' onClick="valida_formulario2();"><img src='../imagenes/botones-texto/aceptar.jpg' border="0" alt="Aceptar la modificación"></a></td>
<td></td>
<?
	if($_GET['busqueda']=='si') {
?>
	<td align='center' height='65'><img src='../imagenes/botones-texto/cancelar.jpg' style="cursor:pointer;" onClick='regresar();' border="0" alt="Cancelar la modificación"></a></td>
<? 
	} else {
?>
	<td align='center' height='65'> <a href='?pag=incidencias.php'><img src='../imagenes/botones-texto/cancelar.jpg' border="0" alt="Cancelar la modificación"></a></td>
<?
	}
?>
</tr>


</table>
</table>
</form>
<!-- fin div 03: modificar incidencia -->
</div>










<!--ELIMINAR INCIDENCIA-->	
<!-- div 18: eliminar incidencia -->
<div id='eliminar_incidencia' <?if($accion=='elim'){print($block);}else{print($none);}?>>
<table border='0' width="100%" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
<?

	//Si viene de búsqueda.
	if( ($_GET['busqueda'] == 'si') && ($_GET['eliminar'] == 'si') && ($_GET['dni']) && ($_GET['fecha_inc']) ) {
		$dni_elim = $_GET['dni'];
		$fecha_elim = $_GET['fecha_inc'];
		$_SESSION['busq'] = 'si';

		/* Selecciona los datos de la incidencia a eliminar. */
		$sql = "Select * from incidencias where DNI_Inc = '".$dni_elim."' AND Fecha_Inc = '".$fecha_elim."'";

		$result = mysql_query($sql);

		for ($i=0;$i<mysql_num_rows($result);$i++){
			$fila = mysql_fetch_array($result);
		}
		
		$nombre_elim = $fila['Nombre_Inc'];
		$apellidos_elim = $fila['Apellido1_Inc']." ".$fila['Apellido2_Inc'];
		$apellido1_elim = $fila['Apellido1_Inc'];
		$apellido2_elim = $fila['Apellido2_Inc'];
		$fecha_elim = $fila['Fecha_Inc'];
		$empleado_elim = $fila['Nombre_Resp'];
		$concepto_elim = $fila['Concepto'];
		$resolucion_elim = $fila['Resolucion'];

		$fecha_partida = split("-", $fecha_elim);
		$dia_p=$fecha_partida[2];
		$mes_p=$fecha_partida[1];
		$anio_p=$fecha_partida[0];
		$fecha_nueva = $dia_p."-".$mes_p."-".$anio_p;
		$link_busq_elim = "?pag=incidencias.php&regresar=si";

	//Selecciona los datos de la incidencia a eliminar. 
	} else {

		$dni_elim = $_GET['dni_eliminar'];
		$fecha_el = $_GET['fecha_el'];

		$sql = "Select * from incidencias where DNI_Inc = '".$dni_elim."' AND Fecha_Inc = '".$fecha_el."'";

		$result = mysql_query($sql);

		for ($i=0;$i<mysql_num_rows($result);$i++){
			$fila = mysql_fetch_array($result);
		}
		
		$nombre_elim = $fila['Nombre_Inc'];
		$apellidos_elim = $fila['Apellido1_Inc']." ".$fila['Apellido2_Inc'];
		$apellido1_elim = $fila['Apellido1_Inc'];
		$apellido2_elim = $fila['Apellido2_Inc'];
		$fecha_elim = $fila['Fecha_Inc'];
		$empleado_elim = $fila['Nombre_Resp'];
		$concepto_elim = $fila['Concepto'];
		$resolucion_elim = $fila['Resolucion'];

		$fecha_partida = split("-", $fecha_elim);
		$dia_p=$fecha_partida[2];
		$mes_p=$fecha_partida[1];
		$anio_p=$fecha_partida[0];
		$fecha_nueva = $dia_p."-".$mes_p."-".$anio_p;
		$link_busq_elim ="?pag=incidencias.php";
	}

?>

<thead>
<td colspan='9' align='center' style="padding:0px 0px 0px 0px;">
<div class='champi_izquierda'>&nbsp;</div>
<div class='champi_centro'  style='width:363px;'>
Eliminar Incidencia
</div>
<div class='champi_derecha'>&nbsp;</div>
</td>
</thead>

<table border="0" width='100%' height='500px' cellpadding="0" cellspacing="0" class='tabla_detalles' style="border: 1px solid #3F7BCC;">
<tr height="40" style="padding-top:21px;">
<td align='left' width='40%'><span class='label_formulario'>D.N.I. / C.I.F.:</span></td>
<td align='left' width='60%'> <span class='texto_detalles'><? echo $dni_elim; ?></span></td>
</tr>
<tr height='30'>
<td align='left' width='40%'><span class='label_formulario'>Nombre:</span> </td>
<td align='left' width='60%'><span class='texto_detalles'><? echo $nombre_elim; ?></span></td>
</tr>
<tr height='30'>
<td align='left' width='40%'><span class='label_formulario'>Apellidos:</span> </td>
<td align='left' width='60%'><span class='texto_detalles'><? echo $apellido1_elim." ".$apellido2_elim; ?></span></td>
</tr>
<tr height='30'>
<td align='left' width='40%'><span class='label_formulario'>Fecha:</span> </td>
<td align='left' width='60%'><span class='texto_detalles'><? echo $fecha_nueva ?></span></td>
</tr>
</tr>
<?

	//Función para partir nombres de empleados demasiado largos.
	$nom_empleado = $empleado_elim;
	$nom_empleado_partido = "";
	$array_nom_empleado = split(" ", $nom_empleado);
	for($b=0; $b<count($array_nom_empleado); $b++) {
		$array_nom_empleado_i = split("-", $array_nom_empleado[$b]);
		if(count($array_nom_empleado_i) > 1) {
			$guiones = 'si';
		} else {
			$guiones = 'no';
		}
		for($j=0; $j<count($array_nom_empleado_i); $j++) {
				
			if($guiones == 'no') {
				if(strlen($array_nom_empleado_i[$j]) > 25) {
					$nom_empleado_partido = $nom_empleado_partido." ".substr($array_nom_empleado_i[$j], 0, 25)."-<br>".substr($array_nom_empleado_i[$j], 25, 25);
				} else {
					$nom_empleado_partido = $nom_empleado_partido. " ".$array_nom_empleado_i[$j];
				} 
			} else {
				if(strlen($array_nom_i[$j]) > 25) {
					$nom_empleado_partido = $nom_empleado_partido." ".substr($array_nom_empleado_i[$j], 0, 25)."-<br>".substr($array_nom_empleado_i[$j], 25, 25);
				} else {
					if($nom_empleado_partido == "") {
						$nom_empleado_partido = $nom_empleado_partido. " ".$array_nom_empleado_i[$j];
					} else {
						$nom_empleado_partido = $nom_empleado_partido. "-".$array_nom_empleado_i[$j];
					}
				}
			} 
		}
	}

?>
<tr height='40'>
<td align='left' width='40%'><span class='label_formulario'>Nombre del Empleado:</span> </td>
<td align='left' width='60%'><span class='texto_detalles'><? echo $nom_empleado_partido; ?></span></td>
</tr>
<tr height='110'>
<? 

	//Función para separar adecuadamente los textos con palabras excesivamente largas. Si el párrafo no
	//contiene palabras con más de 20 letras, entonces el sangrado es automático.
	$con_elim = $concepto_elim;
	$concepto_elim_partido = split(" ", $concepto_elim);
	$num_partes = count($concepto_elim_partido);
	$palabras_largas = "falso";

	for($i=0; $i<$num_partes; $i++) {
		if(strlen($concepto_elim_partido[$i]) > 20) {
			$palabras_largas="verdad";
		}
	}

	if($palabras_largas == "verdad") {
		$concepto_elim = "";
		$restantes = 20;

		for($i=0; $i<$num_partes; $i++) {
			if(strlen($concepto_elim_partido[$i]) > 20) {
				$n_car = strlen($concepto_elim_partido[$i]);
				$n_sub = ($n_car / 20);
				$cont_car = 0;
				$subcadenas = array();
				for($j=0; $j<$n_sub; $j++) {
					$subcadenas[] = substr($concepto_elim_partido[$i], $cont_car, 20);
					$cont_car = $cont_car + 20;
				}
				for($k=0; $k<count($subcadenas); $k++) {
					if($concepto_elim == "") {
						$concepto_elim = $subcadenas[$k]."-<br>";
						$restantes = 20;
					} else {
						if(strlen($subcadenas[$k]) < $restantes) {
							$concepto_elim = $concepto_elim." ".$subcadenas[$k]." ";
							$restantes = $restantes - strlen($subcadenas[$k]) - 1;
						} else { 
							$concepto_elim = $concepto_elim.$subcadenas[$k]."-<br>";
							$restantes = 20;
						}
					}
				}
			} else { 
				if($concepto_elim == "") {
					$concepto_elim = $concepto_elim_partido[$i]." ";
					$restantes = $restantes - strlen($concepto_elim_partido[$i]) - 1;
				} else {
					if(strlen($concepto_elim_partido[$i]) < $restantes) {
						$concepto_elim = $concepto_elim." ".$concepto_elim_partido[$i]." ";
						$restantes = $restantes - strlen($concepto_elim_partido[$i]) - 1;
					} else { 
						$concepto_elim = $concepto_elim."<br>".$concepto_elim_partido[$i];
						$restantes = 20;
					}
				}
			}
		}
	} else { 
		$concepto_elim = $concepto_elim;
	}

?>
<td width='40%' valign='top' align='left'><span class='label_formulario'>Concepto:</span> </td>
<!--<td width='60%' valign='top' align='left'><span class='texto_detalles'><? echo $concepto_elim; ?></span></td>-->
<td width='60%' valign='top' align='left'><textarea readonly maxlength='255' cols='31' rows='7' name='concepto_elim' class='input_formulario'><? echo $con_elim ?></textarea></td>
</tr>
<tr height='115'>
<? 

	//Función para separar adecuadamente los textos con palabras excesivamente largas. Si el párrafo no
	//contiene palabras con más de 20 letras, entonces el sangrado es automático.
	$res_elim = $resolucion_elim;
	$resolucion_elim_partido = split(" ", $resolucion_elim);
	$num_partes = count($resolucion_elim_partido);
	$palabras_largas = "falso";

	for($i=0; $i<$num_partes; $i++) {
		if(strlen($resolucion_elim_partido[$i]) > 20) {
			$palabras_largas="verdad";
		}
	}

	if($palabras_largas == "verdad") {
		$resolucion_elim = "";
		$restantes = 20;

		for($i=0; $i<$num_partes; $i++) {
			if(strlen($resolucion_elim_partido[$i]) > 20) {
				$n_car = strlen($resolucion_elim_partido[$i]);
				$n_sub = ($n_car / 20);
				$cont_car = 0;
				$subcadenas = array();
				for($j=0; $j<$n_sub; $j++) {
					$subcadenas[] = substr($resolucion_elim_partido[$i], $cont_car, 20);
					$cont_car = $cont_car + 20;
				}
				for($k=0; $k<count($subcadenas); $k++) {
					if($resolucion_elim == "") {
						$resolucion_elim = $subcadenas[$k]."-<br>";
						$restantes = 20;
					} else {
						if(strlen($subcadenas[$k]) < $restantes) {
							$resolucion_elim = $resolucion_elim." ".$subcadenas[$k]." ";
							$restantes = $restantes - strlen($subcadenas[$k]) - 1;
						} else { 
							$resolucion_elim = $resolucion_elim.$subcadenas[$k]."-<br>";
							$restantes = 20;
						}
					}
				}
			} else { 
				if($resolucion_elim == "") {
					$resolucion_elim = $resolucion_elim_partido[$i]." ";
					$restantes = $restantes - strlen($resolucion_elim_partido[$i]) - 1;
				} else {
					if(strlen($resolucion_elim_partido[$i]) < $restantes) {
						$resolucion_elim = $resolucion_elim." ".$resolucion_elim_partido[$i]." ";
						$restantes = $restantes - strlen($resolucion_elim_partido[$i]) - 1;
					} else { 
						$resolucion_elim = $resolucion_elim."<br>".$resolucion_elim_partido[$i];
						$restantes = 20;
					}
				}
			}
		}
	} else { 
		$resolucion_elim = $resolucion_elim;
	}

?>
<td width='40%' valign='top' align='left'><span class='label_formulario'>Resolución:</span> </td>
<!--<td width='60%' valign='top' align='left'><span class='texto_detalles'><? echo $resolucion_elim; ?></span></td>-->
<td width='60%' valign='top' align='left'><textarea readonly maxlength='255' cols='31' rows='7' name='resolucion_elim' class='input_formulario'><? echo $res_elim ?></textarea></td>
</tr>
<tr height='65'>
<td colspan='2' class='label_formulario' style='text-align:center;font-size:13px;margin-left:2px;'>¿Está seguro de que desea <br>eliminar la incidencia?</td>
</tr>

<tr height='45px'>
<td colspan="2">
<table id='tabla_detalles' border="0" width='100%'>
<tr>

<?php

	if($_GET['busqueda'] == 'si') {
		$hrefaceptare=".?pag=incidencias.php&regreso=si&borrar=si&dni_be=".$dni_elim."&fecha_be=".$fecha_elim."";
	} else {
		$hrefaceptare=".?pag=incidencias.php&accion=nueva&borrar=si&dni_eliminar=".$dni_elim."&fecha_eliminar=".$fecha_elim."";
	}

?>
<td align='center'><a href='<?print($hrefaceptare)?>'><img src='../imagenes/botones-texto/aceptar.jpg' border="0" alt="Eliminar la incidencia seleccionada"></a></td>
<?
	if($_GET['busqueda']=='si') {
?>
	<td align='center'><img src='../imagenes/botones-texto/cancelar.jpg' style="cursor:pointer;" onClick='regresar();' border="0" alt="No eliminar la incidencia seleccionada"></a></td>
<?
	} else {
?>
	<td align='center'> <a href='?pag=incidencias.php'><img src='../imagenes/botones-texto/cancelar.jpg' border="0" alt="No eliminar la incidencia seleccionada"></a></td>
<?
	}
?>
</tr>
</table>
</td>
</tr>

</table>

</table>
<!-- fin div 18: eliminar incidencia -->
</div>



<!-- Bloque que corresponde a la parte izquierda del panel. -->	
<!-- div 02: caja superior izda -->
</div>










<!-- LISTADO DE INCIDENCIAS. -->
<!-- Bloque derecho. Se muestra permanentemente un listado con las incidencias ocurridas durante el año actual. -->
<!-- div 23: caja superior dcha -->
<div id="caja_superior_derecha" style='float:left;margin-left:0px;padding-left:15px;padding-right:0px; margin-top:0px;'>

<!-- div 24: listado incidencias -->
<div id='listado_incidencias' style='display:block;'>
<table border="0" align="center" width="100%" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
<tr>
<td colspan="9" align="center" style="padding:0px 0px 0px 0px;">

<div class='champi_izquierda'>&nbsp;</div>
<div class='champi_centro'  style='width:689px;'>
Listado de Incidencias
</div>
<div class='champi_derecha'>&nbsp;</div>

</td>					
</tr>
<tr style="padding-top:0px;">
<td style="padding:0px 0px 0px 0px;" width="100%">
<!-- div 29: contenedor de tabla -->
<div id="tableContainer" class="tableContainer" style='width:747px;height:560px;background-color:#F4FCFF'>
<table border="0" cellpadding="0" cellspacing="0" class="scrollTable" width='730px'> 
<thead class="fixedHeader" cellspacing="0" class="scrollTable" >

<th width="16%"  style="padding:0px 0px 0px 0px;" align="center"><a href="#" onClick="ordenar_dni();">D.N.I.</a></th>
<th width="23%" align="center"><a href="#" onClick="ordenar_nombre();">Nombre</a></th>
<th width="34%" align="center"><a href="#" onClick="ordenar_apellidos();">Apellidos</a></th>
<th width="15%" align="center"><a href="#" onClick="ordenar_fecha();">Fecha</a></th>
<th width="4%" align="center">D</th>
<th width="4%" align="center">M</th>
<th width="4%" align="center">E</th>

</thead>
<tbody class="scrollContent">

<?

	//Carga el listado de incidencias desde la base de datos. Ordena por defecto por fecha descendente. 
	$sql = "SELECT * from Incidencias order by Fecha_Inc DESC";

	
	//Ordenación por nombre.
	if($_GET['ordenar'] == 'nombre') {

		if ( (isset($_SESSION['nom_orden'])) && ($_SESSION['nom_orden'] == 'desc') ) {
			$sql = "SELECT * from Incidencias order by Nombre_Inc DESC";
			$_SESSION['nom_orden'] = 'asc';
		}

		else if ( (isset($_SESSION['nom_orden'])) && ($_SESSION['nom_orden'] == 'asc') ) {
			$sql = "SELECT * from Incidencias order by Nombre_Inc";
			$_SESSION['nom_orden'] = 'desc';
		}

		else {
			$sql = "SELECT * from Incidencias order by Nombre_Inc";
			$_SESSION['nom_orden'] = 'desc';
		}
	}


	//Ordenación por DNI.
	if($_GET['ordenar'] == 'dni') {

		if ( (isset($_SESSION['dni_orden'])) && ($_SESSION['dni_orden'] == 'desc') ) {
			$sql = "SELECT * from Incidencias order by DNI_Inc DESC";
			$_SESSION['dni_orden'] = 'asc';
		}

		else if ( (isset($_SESSION['dni_orden'])) && ($_SESSION['dni_orden'] == 'asc') ) {
			$sql = "SELECT * from Incidencias order by DNI_Inc";
			$_SESSION['dni_orden'] = 'desc';
		}

		else {
			$sql = "SELECT * from Incidencias order by DNI_Inc";
			$_SESSION['dni_orden'] = 'desc';
		}
	}


	//Ordenación por apellidos.
	if($_GET['ordenar'] == 'apellidos') {

		if ( (isset($_SESSION['apellidos_orden'])) && ($_SESSION['apellidos_orden'] == 'desc') ) {
			$sql = "SELECT * from Incidencias order by Apellido1_Inc DESC, Apellido2_Inc DESC";
			$_SESSION['apellidos_orden'] = 'asc';
		}

		else if ( (isset($_SESSION['apellidos_orden'])) && ($_SESSION['apellidos_orden'] == 'asc') ) {
			$sql = "SELECT * from Incidencias order by Apellido1_Inc, Apellido2_Inc";
			$_SESSION['apellidos_orden'] = 'desc';
		}

		else {
			$sql = "SELECT * from Incidencias order by Apellido1_Inc, Apellido2_Inc";
			$_SESSION['apellidos_orden'] = 'desc';
		}
	}
	

	//Ordenación por fecha.
	if($_GET['ordenar'] == 'fecha') {

		if ( (isset($_SESSION['fecha_orden'])) && ($_SESSION['fecha_orden'] == 'desc') ) {
			$sql = "SELECT * from Incidencias order by Fecha_Inc DESC";
			$_SESSION['fecha_orden'] = 'asc';
		}

		else if ( (isset($_SESSION['fecha_orden'])) && ($_SESSION['fecha_orden'] == 'asc') ) {
			$sql = "SELECT * from Incidencias order by Fecha_Inc";
			$_SESSION['fecha_orden'] = 'desc';
		}

		else {
			$sql = "SELECT * from Incidencias order by Fecha_Inc";
			$_SESSION['fecha_orden'] = 'desc';
		}
	}


	$result = mysql_query($sql);

	for ($i=0;$i<mysql_num_rows($result);$i++){
		$fila = mysql_fetch_array($result);
		echo "<tr class='texto_listados' height='30px' >";
		$dni = $fila['DNI_Inc'];
		$dni_partido = parteCadenas($dni, 12);
		echo "<td valign='top' align='left' width='16%'>";
		echo $dni_partido;
		echo "</td>";

		
		//Revisa las longitudes de los nombres demasiado largos para que aparezcan	
		//adecuadamente en el listado.
		$nom = $fila['Nombre_Inc'];
		$nom_partido = parteCadenas($nom, 14);
		echo "<td valign='top' align='left' width='23%'>";
		echo $nom_partido;
		echo "</td>";

		
		//Revisa las longitudes de los apellidos demasiado largos para que aparezcan	
		//adecuadamente en el listado.
		$ap = $fila['Apellido1_Inc']." ".$fila['Apellido2_Inc'];
		$ap_partido = parteCadenas($ap, 21);
		echo "<td valign='top' align='left' width='34%'>";
		echo $ap_partido;
		echo "</td>";

		echo "<td valign='top' align='left' width='15%'>";
			$fecha_partida = split("-", $fila['Fecha_Inc']);
			$dia_p=$fecha_partida[2];
			$mes_p=$fecha_partida[1];
			$anio_p=$fecha_partida[0];
			$fecha_nueva = $dia_p."-".$mes_p."-".$anio_p;
		echo $fecha_nueva;
		echo "</td>";
									
		echo "<td valign='top' width='4%'>";
		echo "<a href='?pag=incidencias.php&accion=det&dni_detalles=".$fila['DNI_Inc']."&fecha_detalles=".$fila['Fecha_Inc']."'>";
		echo "<IMG alt='Detalles' src='../imagenes/botones/detalles.gif' border=0>";
		echo "</a>";
		echo "</td>";

		echo "<td valign='top' width='4%'>";
		echo "<a href='?pag=incidencias.php&accion=mod&dni_modificar=".$fila['DNI_Inc']."&fecha_modif=".$fila['Fecha_Inc']."'>";
		echo "<IMG alt='Modificar' src='../imagenes/botones/modificar.gif' border=0>";
		echo "</a>";
		echo "</td>";

		echo "<td valign='top' width='4%'>";
		echo "<a href='?pag=incidencias.php&accion=elim&dni_eliminar=".$fila['DNI_Inc']."&fecha_el=".$fila['Fecha_Inc']."'>";
		echo "<IMG alt='Eliminar' src='../imagenes/botones/eliminar.gif' border=0>";
		echo "</a>";
		echo "</td>";
									
		echo "</tr>";
	}

?>

</tbody>
</table> 
<!-- fin div 29: contenedor de tabla -->
</div>
</td>
</tr>
</table>

<!-- fin div 24: listado incidencias -->
</div>




<!-- Bloque derecho. Se muestra permanentemente un listado con las incidencias ocurridas durante el año actual. -->
<!-- div 23: caja superior dcha -->
</div>


<!-- Bloque que corresponde a todo el panel. -->
<!-- div 01: caja superior -->
</div>


<!-- Por compatibilidad con Firefox, el script lo situamos aquí. -->
<script language="javascript">
	foco();
</script>


<?php
		mysql_close ($db);
	}

	//Fin del IF de comprobación de acceso a la página
	//Se muestra una ventana de error de permisos de acceso a la página
	} else {
		echo "<br>";
		echo "<br>";
		echo "<br>";
		echo "<br>";
		echo "<div class='error'><center>";	
		echo "<b>";
		echo "<label id='texto_detalles' style='color:red;'>NO TIENE PERMISOS PARA ACCEDER A ESTA PÁGINA</label>";
		echo "</b></center></div>";
	}
?>
