<div class="borde" id="borde1" style="float:left;">
</div>	
<div class="title" id="title1">
	<a href="#" onclick="mostrarSubmenu(1);" class="fondo_menu_principal">
		<br>
	  	<div>
		  	Instalaciones
		</div>
	</a>
</div>		
<div class="title" id="title2">
	<a href="#" onclick="mostrarSubmenu(2);" class="fondo_menu_principal">
		<br>
	  	<div>
		  	Estad�sticas
		</div>
	</a>
</div>
<div class="title" id="title3">
	<a href="#" onclick="mostrarSubmenu(3);" class="fondo_menu_principal">
		<br>
	 	Mantenimiento
	</a>
</div>
<div class="title" id="title4">
	<a href="#" onclick="mostrarSubmenu(4);" class="fondo_menu_principal">
		<br>
	  	Gesti�n Interna
	</a>
</div>
<div class="borde" id="borde2" style="float:left;">
</div>	

<div class="submenu" id="submenu1">
	<table class="fila_submenu" cellspacing="0" cellpadding="0">
<?PHP

	//$db_link = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
	$db_link = MYSQL_CONNECT('localhost','root','');
	if (!$db_link) {
	   die("Could not connect: ".MYSQL_ERROR());
	}
	
	MYSQL_SELECT_DB('scies') or die("Could not select database");		

	$sql = "SELECT * FROM centro order by Nombre;";
	$result = MYSQL_QUERY($sql,$db_link);
	MYSQL_CLOSE();
	$num_rows = MYSQL_NUM_ROWS($result);
	
	for ($i = 0; $i < $num_rows; $i++) {
		$fila = MYSQL_FETCH_ARRAY($result);
?>
		<tr>
			<td class="fila_submenu_izq">
			</td>
			<td class="fila_submenu_centro">
				<a class="asubmenu" href="principal.php?pag=instalaciones.php&id=<?PHP echo $fila['Id_Centro']; ?>">
					<?PHP echo SUBSTR($fila['Nombre'],0,50); ?>
				</a>
			</td>
			<td class="fila_submenu_der">
			</td>
		</tr>
<?PHP
	}
?>
	</table>
</div>

<div class="submenu" id="submenu2">
	<table class="fila_submenu" cellspacing="0" cellpadding="0">
		<tr>
			<td class="fila_submenu_izq">
			</td>
			<td class="fila_submenu_centro">
				<a class="asubmenu" href="principal.php?pag=criterios_listados.php">
					Listados
				</a>
			</td>
			<td class="fila_submenu_der">
			</td>
		</tr>
		<tr>
			<td class="fila_submenu_izq">
			</td>
			<td class="fila_submenu_centro">
				<a class="asubmenu" href="principal.php?pag=criterios_graficos.php">
					Gr�ficos
				</a>
			</td>
			<td class="fila_submenu_der">
			</td>
		</tr>
		<tr>
			<td class="fila_submenu_izq">
			</td>
			<td class="fila_submenu_centro">
				<a class="asubmenu" href="principal.php?pag=temperaturas_dia.php">
					T� Diaria
				</a>
			</td>
			<td class="fila_submenu_der">
			</td>
		</tr>
	</table>
</div>

<div class="submenu" id="submenu3">
	<table class="fila_submenu" cellspacing="0" cellpadding="0">
		<tr>
			<td class="fila_submenu_izq">
			</td>
			<td class="fila_submenu_centro">
				<a class="asubmenu" href="principal.php?pag=mtto_preventivo.php">
					Preventivo
				</a>
			</td>
			<td class="fila_submenu_der">
			</td>
		</tr>
		<tr>
			<td class="fila_submenu_izq">
			</td>
			<td class="fila_submenu_centro">
				<a class="asubmenu" href="principal.php?pag=mtto_correctivo.php">
					Correctivo
				</a>
			</td>
			<td class="fila_submenu_der">
			</td>
		</tr>
	</table>
</div>

<div class="submenu" id="submenu4">
	<table class="fila_submenu" cellspacing="0" cellpadding="0">
		<tr>
			<td class="fila_submenu_izq">
			</td>
			<td class="fila_submenu_centro">
				<a class="asubmenu" href="principal.php?pag=listado_centros.php">
					Instalaciones
				</a>
			</td>
			<td class="fila_submenu_der">
			</td>
		</tr>
		<tr>
			<td class="fila_submenu_izq">
			</td>
			<td class="fila_submenu_centro">
				<a class="asubmenu" href="principal.php?pag=listado_componentes.php&Id_Centro=todos">
					Componentes
				</a>
			</td>
			<td class="fila_submenu_der">
			</td>
		</tr>
		<tr>
			<td class="fila_submenu_izq">
			</td>
			<td class="fila_submenu_centro">
				<a class="asubmenu" href="principal.php?pag=listado_subcomponentes.php&id_componente=todos">
					Subcomponentes
				</a>
			</td>
			<td class="fila_submenu_der">
			</td>
		</tr>
		<tr>
			<td class="fila_submenu_izq">
			</td>
			<td class="fila_submenu_centro">
				<a class="asubmenu" href="principal.php?pag=listado_parametros.php">
					Par�metros
				</a>
			</td>
			<td class="fila_submenu_der">
			</td>
		</tr>
		<tr>
			<td class="fila_submenu_izq">
			</td>
			<td class="fila_submenu_centro">
				<a class="asubmenu" href="principal.php?pag=listado_op_preventivas.php">
					Op. Preventivas</a>
				</a>
			</td>
			<td class="fila_submenu_der">
			</td>
		</tr>
		<tr>
			<td class="fila_submenu_izq">
			</td>
			<td class="fila_submenu_centro">
				<a class="asubmenu" href="principal.php?pag=listado_op_correctivas.php">
					Op. Correctivas
				</a>
			</td>
			<td class="fila_submenu_der">
			</td>
		</tr>
	</table>
</div>

<script language="JavaScript">

var nom = 4; // N�mero de menus
var last = 0;
var margen = 1 / 2.5;
var margin = 35;

var menu_visible = new Array(nom + 1);

for (var i = 1; i <= nom; i++) {
	menu_visible[i] = false;
}

function ajustarMenuTamanio() {
	document.getElementById('borde1').style.width = parseInt((margen / 2) * 100) + '%';
	document.getElementById('borde2').style.width = parseInt((margen / 2) * 100) + '%';
	for (var i = 1; i <= nom; i++) {
		document.getElementById('title' + i).style.width = parseInt((1 - margen) * 100 / nom) + '%';
		document.getElementById('submenu' + i).style.left = parseInt(document.getElementById('title' + i).offsetLeft + (document.getElementById('title' + i).offsetWidth / 2) - (document.getElementById('submenu' + i).offsetWidth / 2)) + 'px';
		//document.getElementById('submenu' + i).style.left = ((margen / 2 * 100) + ((i - 1) * ((1 - margen) / nom * 100))) + '%';
	    //document.getElementById('submenu' + i).style.marginLeft = (margin - 3 * i) + 'px';
	}
}

function mostrarSubmenu(n) {
	if ((n != last) && (last > 0)) {
		if (menu_visible[last] == true) {
			ocultar(last);
		}
	}
	if (menu_visible[n] == false) {
		mostrar(n);
	}
	else {
		ocultar(n)		
	}
	last = n;
}

function ocultar(n) {
    document.getElementById('submenu' + n).filters[0].Apply();
	document.getElementById('submenu' + n).style.visibility = 'hidden';
    document.getElementById('submenu' + n).filters[0].Play(duration = 0.4);
	menu_visible[n] = false;
}

function mostrar(n) {
    document.getElementById('submenu' + n).filters[0].Apply();
	document.getElementById('submenu' + n).style.visibility = 'visible';
	//document.getElementById('submenu' + n).style.left = ((margen / 2 * 100) + ((n - 1) * ((1 - margen) / nom * 100))) + '%';
    //document.getElementById('submenu' + n).style.marginLeft = (margin - 3 * n) + 'px';
	document.getElementById('submenu' + n).style.left = parseInt(document.getElementById('title' + n).offsetLeft + (document.getElementById('title' + n).offsetWidth / 2) - (document.getElementById('submenu' + n).offsetWidth / 2)) + 'px';
	document.getElementById('submenu' + n).filters[0].Play(duration = 0.4);
	menu_visible[n] = true;
}

</script>