<?PHP

/* BLOQUE DE CONFIGURACI�N DE LA P�GINA */

// Datos generales de la p�gina.
$conf['general']['nombre_formulario'] = 'formulario_listado';
$conf['general']['nombre_pagina'] = 'parametros_modificar.php';
$conf['general']['nombre_principal'] = 'principal.php';

// Datos referentes a la conexi�n a mysql.
$conf['database']['host'] = 'localhost';
$conf['database']['user'] = 'root';
$conf['database']['pass'] = '';

// Nombre de la base de datos,nombre de la tabla y array con la clave de la tabla.
$conf['database']['nombre_db'] = 'scies';
$conf['database']['nombre_tabla'] = 'sonda';
$conf['database']['clave_tabla'][0] = 'Id_Centro';

$conf['database']['separador_clave'] = '*';
$conf['database']['campos_sql'] = array();

$conf['database']['campos_sql'][0]['nombre_db'] = 'Nombre_Param';
$conf['database']['campos_sql'][0]['nombre_mostrar'] = 'Par�metro';
$conf['database']['campos_sql'][0]['logitud_max'] = '20';
$conf['database']['campos_sql'][0]['nombre_intext'] = 'intext_'.$conf['database']['campos_sql'][1]['nombre_db'];
$conf['database']['campos_sql'][0]['logitud_intext'] ='25';

$conf['database']['campos_sql'][1]['nombre_db'] = 'Descripcion_Sonda';
$conf['database']['campos_sql'][1]['nombre_mostrar'] = 'Sonda';
$conf['database']['campos_sql'][1]['logitud_max'] = '3';
$conf['database']['campos_sql'][1]['nombre_intext'] = 'intext_'.$conf['database']['campos_sql'][0]['nombre_db'];
$conf['database']['campos_sql'][1]['logitud_intext'] = '50';

// Configuraci�n del orden por defecto de la consulta
$conf['resultado_sql']['orden_campo'] = $conf['database']['campos_sql'][0]['nombre_db'];
$conf['resultado_sql']['orden_tipo'] = 'ASC'; //'ASC' para ascendente o 'DESC' para descendente

// Configuraci�n del n�mero de filas que se muestran en pantalla sobre el resultado.
$conf['resultado_sql']['numero_filas'] = 15;
$conf['resultado_sql']['nombre_intext_numero_filas'] = 'numero_filas';
$conf['resultado_sql']['mostrar_pagina'] = 1;
$conf['resultado_sql']['tope_paginas'] = 10;
$conf['resultado_sql']['mostrar_ventana_paginas'] = 1;

// Variables que guardan cadenas que se muestran en el listado.
$conf['texto']['titulo_tabla'] = 'Listado de Par�metros del Centro';
$conf['texto']['boton_limpiar'] = 'Limpiar<br>Campos';
$conf['texto']['boton_seleccionar'] = 'Selec.<br>Todo';
$conf['texto']['boton_nuevo'] = 'Agregar';
$conf['texto']['confirmar_eliminar_seleccionado'] = '�Seguro que desea eliminar los registros seleccionados?';
$conf['texto']['numero_pagina'] = 'P�gina';
$conf['texto']['palabra_clave'] = 'Clave';
$conf['texto']['mostrar_1'] = 'Mostrar';
$conf['texto']['mostrar_2'] = 'registros';

// Configuraci�n del men� contextual

$conf['menu_contextual'][0]['texto'] = 'Eliminar';
$conf['menu_contextual'][0]['accion'] = 'remove';



/* FIN BLOQUE DE CONFIGURACI�N DE LA P�GINA */


/////////////////////////////////////////////////////
/* BLOQUE DE CONFIGURACION DE VARIABLES DE ENTRADA */
/////////////////////////////////////////////////////

// Creamos la consulta con los posibles filtros aplicados

// Orden de la consulta
if (isset($_GET['listado_orden_campo'])) {
	if ($_GET['listado_orden_campo'] != "") {
		$conf['resultado_sql']['orden_campo'] = $_GET['listado_orden_campo'];
	}
}
if (isset($_GET['listado_orden_tipo'])) {
	if ($_GET['listado_orden_tipo'] != "") {
		$conf['resultado_sql']['orden_tipo'] = $_GET['listado_orden_tipo'];
	}
}

$conf['database']['consulta_sql'] = 'SELECT  parametro.Id_Parametro as Clave,';
$j = COUNT($conf['database']['campos_sql']);
for ($i = 0; $i < $j; $i++) {
	$conf['database']['consulta_sql'] = $conf['database']['consulta_sql'].$conf['database']['campos_sql'][$i]['nombre_db'].',';
}

//Coger la �ltima coma y la sustituya por un blanco para hacer la consulta sql...
$conf['database']['consulta_sql']=substr_replace($conf['database']['consulta_sql']," ",-1,1);

//Continuaci�n de la consulta...
$conf['database']['consulta_sql'] = $conf['database']['consulta_sql'].' FROM '.$conf['database']['nombre_tabla'].' INNER JOIN parametro ON '.$conf['database']['nombre_tabla'].'.'.Id_Parametro.'='.parametro.'.'.Id_Parametro. ' WHERE '.Id_Centro. ' = ' .$_GET['Id_Centro'];
$j = COUNT($conf['database']['campos_sql']);
for ($i = 0; $i < $j; $i++) {
	if ($_GET[$conf['database']['campos_sql'][$i]['nombre_intext']] != '') {
		$conf['database']['consulta_sql'] = $conf['database']['consulta_sql'].' AND '.$conf['database']['campos_sql'][$i]['nombre_db'].' LIKE "'.$_GET[$conf['database']['campos_sql'][$i]['nombre_intext']].'%"';
    }
}
$conf['database']['consulta_sql'] = $conf['database']['consulta_sql'].' ORDER BY '.$conf['resultado_sql']['orden_campo'].' '.$conf['resultado_sql']['orden_tipo'];
$conf['database']['consulta_sql'] = $conf['database']['consulta_sql'].';';


// Si se ha pulsado el n�mero de otra p�gina
if (isset($_GET['listado_pagina'])) {
	if ($_GET['listado_pagina'] != "") {
		$conf['resultado_sql']['mostrar_pagina'] = INTVAL($_GET['listado_pagina']);
	}
}

// Si se ha pulsado el n�mero de otra ventana de p�ginas
if (isset($_GET['listado_ventana'])) {
	if ($_GET['listado_ventana'] != "") {
		$conf['resultado_sql']['mostrar_ventana_paginas'] = INTVAL($_GET['listado_ventana']);
	}
}







if ($_GET['listado_accion'] == 'new') {
    $parametro_nuevo=$_GET['parametros'];
    $sonda_nueva=strtoupper($_GET['sond']);
    $centro_id=$_GET['Id_Centro'];
	$sql_ini = "INSERT INTO ".$conf['database']['nombre_tabla']." (Id_Centro,Id_Parametro,Descripcion_Sonda) VALUES ('$centro_id','$parametro_nuevo','$sonda_nueva');";
	/* Connecting, selecting database */
	$db_link = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
	if (!$db_link) {
	   die("Could not connect: ".MYSQL_ERROR());
	}
	MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");

    $existe=mysql_query("select Descripcion_Sonda from sonda where Descripcion_Sonda='".$sonda_nueva."'");
    $hay=mysql_num_rows($existe);
    if($hay>=1 or $sonda_nueva==""){
      echo "<script>alert('Esa sonda ya tiene un par�metro asociado');</script>";
    }
    else{
    	$result = MYSQL_QUERY($sql_ini,$db_link);
    }
/* Closing connection */
MYSQL_CLOSE($db_link);
}





// N�mero de filas a mostrar en pantalla
if ((isset($_GET['numero_filas']))&&(floor($_GET['numero_filas']) > 0)) {
	$conf['resultado_sql']['numero_filas'] = $_GET['numero_filas'];
}


/* SI HEMOS ESCOGIDO UNA OPCI�N EN EL MEN� CONTEXTUAL */
if ($_GET['menu_accion'] != '') {
	$conf['menu_accion'] = $_GET['menu_accion'];
	$conf['menu_valor'] = $_GET['menu_valor'];
	if ($conf['menu_accion'] == 'rs') { // Remove selected - Eliminar campos selecionados
		if (count($_GET['checks']) > 0) {
			$conf['remove_selected'] = TRUE;
		}
	}
	else {
		$conf['remove_selected'] = FALSE;
	}
}
else {
	$conf['remove_selected'] = FALSE;
	$conf['menu_accion'] = NULL;
	$conf['menu_valor'] = $_GET['clave'];
}










if ($_GET['confirmar_eliminar'] != '') {
	if ($_GET['confirmar_eliminar'] == 'rs') {
		$j = COUNT($_GET['checks']);
		for ($i = 0; $i < $j; $i++) {
			$sql = 'DELETE FROM '.$conf['database']['nombre_tabla'].' WHERE ';
			$tope = count($conf['database']['clave_tabla']);
			$aux = split('\\'.$conf['database']['separador_clave'],$_GET['checks'][$i]);
			for ($k = 0; $k < $tope; $k++) {
				$sql = $sql.$conf['database']['clave_tabla'][$k].' LIKE "'.$aux[$k].'" AND ';
			}
			$sql = SUBSTR($sql,0,-5).';';
			$db_link = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
			if (!$db_link) {
			   die("Could not connect: ".MYSQL_ERROR());
			}
			MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");
			/* Performing SQL query */
			$result = MYSQL_QUERY($sql,$db_link);

			/* Closing connection */
			MYSQL_CLOSE($db_link);
		}
	}
	else {
		$sql = 'DELETE FROM '.$conf['database']['nombre_tabla'].' WHERE ';
		$tope = count($conf['database']['clave_tabla']);
		$aux = split('\\'.$conf['database']['separador_clave'],$_GET['confirmar_eliminar']);
		for ($i = 0; $i < $tope; $i++) {
			$sql = $sql.$conf['database']['clave_tabla'][$i].' LIKE "'.$aux[$i].'" AND ';
		}
		$sql = SUBSTR($sql,0,-5).';';
		$db_link = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
		if (!$db_link) {
		   die("Could not connect: ".MYSQL_ERROR());
		}
		MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");
		/* Performing SQL query */
		$result = MYSQL_QUERY($sql,$db_link);

		/* Closing connection */
		MYSQL_CLOSE($db_link);
	}
}

/* FIN MEN� CONTEXTUAL */

/////////////////////////////////////////////////////////
/* FIN BLOQUE DE CONFIGURACION DE VARIABLES DE ENTRADA */
/////////////////////////////////////////////////////////

?>

<html>

<head>
	<link rel="StyleSheet" href="./css/listados.css" type="text/css">
</head>

<body>

<script language="JavaScript">

	function cambiar_pagina(valor) {
		<?PHP echo $conf['general']['nombre_formulario']; ?>.listado_pagina.value = valor;
		<?PHP echo $conf['general']['nombre_formulario']; ?>.submit();
	}

	function cambiar_ventana_paginas(valor) {
		<?PHP echo $conf['general']['nombre_formulario']; ?>.listado_ventana.value = valor;
		<?PHP echo $conf['general']['nombre_formulario']; ?>.submit();
	}

	function nuevo() {
		<?PHP echo $conf['general']['nombre_formulario']; ?>.listado_accion.value = 'new';
		<?PHP echo $conf['general']['nombre_formulario']; ?>.submit();
	}

	function filtrar(foco) {
		var tecla = event.keyCode;
	  	if (tecla == 13) {
			<?PHP echo $conf['general']['nombre_formulario']; ?>.listado_foco.value = foco;
			if (foco != -1) {
				<?PHP echo $conf['general']['nombre_formulario']; ?>.listado_pagina.value = 1;
			}
			document.<?PHP echo $conf['general']['nombre_formulario']; ?>.submit();
		}
	}

	// Funci�n que asigna el foco al cuadro de texto que se modifico por �ltima vez
	function foco(i,s,e)	{
		if (i.setSelectionRange) {
			i.focus();
			i.setSelectionRange(s,e);
		}
		else if (i.createTextRange) {
			var range = i.createTextRange();
			range.collapse(true);
			range.moveEnd('character', e);
			range.moveStart('character', s);
			range.select();
		}
	}

	// Funci�n que recarga la p�gina con el fin de ordenar por alg�n campo las filas.
	function ordenar(campo,tipo) {
		<?PHP echo $conf['general']['nombre_formulario']; ?>.listado_orden_campo.value = campo;
		<?PHP echo $conf['general']['nombre_formulario']; ?>.listado_orden_tipo.value = tipo;
		document.<?PHP echo $conf['general']['nombre_formulario']; ?>.submit();
	}
</script>

<?PHP
	/* Connecting, selecting database */
	$db_link = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
	if (!$db_link) {
	   die("Could not connect: ".MYSQL_ERROR());
	}
	MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");
	/* Performing SQL query */
  	$result = MYSQL_QUERY($conf['database']['consulta_sql'],$db_link);
	/* Closing connection */
	MYSQL_CLOSE($db_link);

  	if (MYSQL_NUM_ROWS($result) >= 0) {
?>



<form name="<?PHP echo $conf['general']['nombre_formulario']; ?>" action="principal.php?pag=parametros_modificar.php">

<input type="hidden" name="listado_accion" value="">
<input type="hidden" name="pag" value="<?PHP echo $conf['general']['nombre_pagina'] = 'parametros_modificar.php'; ?>">
<input type="hidden" name="listado_pagina" value="<?PHP echo $conf['resultado_sql']['mostrar_pagina']; ?>">
<input type="hidden" name="listado_ventana" value="<?PHP echo $conf['resultado_sql']['mostrar_ventana_paginas']; ?>">
<input type="hidden" name="listado_orden_campo" value="<?PHP echo $conf['resultado_sql']['orden_campo']; ?>">
<input type="hidden" name="listado_orden_tipo" value="<?PHP echo $conf['resultado_sql']['orden_tipo']; ?>">
<input type="hidden" name="listado_foco" value="">
<input type="hidden" name="menu_valor" value="">
<input type="hidden" name="menu_accion" value="">

<input type="hidden" name="confirmar_eliminar" value="">
<input type="hidden" name="result" value="<?PHP echo $result; ?>">
<input type="hidden" name="Id_Centro" value="<?PHP echo $_GET['Id_Centro'];?>">




<?PHP
$db_link = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
    if (!$db_link) {
	   die("Could not connect: ".MYSQL_ERROR());
	}
	MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");
    $consulta=mysql_query("SELECT Nombre FROM centro WHERE Id_Centro='".$_GET['Id_Centro']."'");
    $nombre=mysql_fetch_array($consulta);
?>




	<table class="listados" width="700">
		<caption><?PHP	echo $conf['texto']['titulo_tabla']; echo ": ".$nombre['Nombre']; ?>	</caption>
		<thead>
			<tr>
<?PHP
		$script = '<SCRIPT language="JavaScript">'; // Construiremos el script para limpiar los campos de texto
		$script = $script.'function limpiar_campos() {';
		$tope = COUNT($conf['database']['campos_sql']);
?>

				<th class="boton" id="borde_idb">
					<a class="boton" href="#">
						<div class="boton" <?PHP if ($conf['remove_selected'] == FALSE) { ?> onclick="seleccionar_todo();" <?PHP } ?> >
<?PHP
		echo $conf['texto']['boton_seleccionar'];
?>
						</div>
					</a>
				</th>

<?PHP
		for ($i = 0; $i < $tope; $i++) {
?>
				<th class="titulo" id="borde_db">
					<div class="borde"></div>
					<div class="centro">
<?PHP
			if ($conf['resultado_sql']['orden_campo'] == $conf['database']['campos_sql'][$i]['nombre_db']) {
				if ($conf['resultado_sql']['orden_tipo'] == 'ASC') {
?>
						<a class="nombre" href="#" onclick="ordenar('<?PHP echo $conf['database']['campos_sql'][$i]['nombre_db']; ?>','DESC');">
<?PHP
				}
				else {
?>
						<a class="nombre" href="#" onclick="ordenar('<?PHP echo $conf['database']['campos_sql'][$i]['nombre_db']; ?>','ASC');">
<?PHP
				}
			}
			else {
?>
						<a class="nombre" href="#" onclick="ordenar('<?PHP echo $conf['database']['campos_sql'][$i]['nombre_db']; ?>','ASC');">
<?PHP
			}
?>
							<div>
<?PHP
			echo $conf['database']['campos_sql'][$i]['nombre_mostrar'];
?>
							</div>
						</a>

						<div class="intexto">
						<input type="text" name="<?PHP echo $conf['database']['campos_sql'][$i]['nombre_intext']; ?>" value="<?PHP echo $_GET[$conf['database']['campos_sql'][$i]['nombre_intext']]; ?>" size="<?PHP echo $conf['database']['campos_sql'][$i]['logitud_intext']; ?>" maxLength="<?PHP echo $conf['database']['campos_sql'][$i]['logitud_max']; ?>" onkeyup="filtrar(<?PHP echo $i; ?>);">

<?PHP

			// Continuaci�n del script de limpiar campos
			$script = $script.$conf['general']['nombre_formulario'].'.'.$conf['database']['campos_sql'][$i]['nombre_intext'].'.value = "";';

?>
						</div>
					</div>
				</th>
<?PHP
		}

		// Terminamos de construir el script
		$script = $script.'}</script>';
		echo $script;
?>
				<th colspan="3" rowspan="2" class="boton" id="borde_idb" style="width:68px;">
					<a class="boton" href="#" onclick="limpiar_campos();">
						<div class="boton">
<?PHP
		echo $conf['texto']['boton_limpiar'];
?>
						</div>
					</a>
				</th>
			</tr>
		</thead>
		<tfoot>
<?PHP
		if ($conf['remove_selected'] == TRUE) {
?>
			<tr style="height:34px;">
				<th colspan="<? echo COUNT($conf['database']['campos_sql']) + 1; ?>" id="borde_i">
					<div class="intexto">
<?PHP
			echo $conf['texto']['confirmar_eliminar_seleccionado'];
?>
					</div>
				</th>
				<a href="#" class="confirmar_aceptar">
					<th onclick="confirmarEliminar('rs');" title="Eliminar los Registros">
					</th>
				</a>
				<a href="#" class="confirmar_cancelar">
					<th onclick="confirmarEliminar('NULL');" title="Cancelar" id="borde_d" colspan="3">
					</th>
				</a>
			</tr>
<?PHP
		}
		else {
?>
			<tr>
				<th id="borde_i" align=center>
					<a href="#" align=center class="papelera">
						<div style="float: left; width:4px;">
						</div>
						<div class="papelera" title="Borrar entradas seleccionadas" onclick="enviarAccion('rs','sel');">
						</div>
					</a>
				</th>
<?PHP
			$tope = COUNT($conf['database']['campos_sql']);

			for ($i = 0; $i < $tope; $i++) {
				if ($conf['database']['campos_sql'][$i]['rango_valores'] == TRUE) {
					if ($conf['database']['campos_sql'][$i]['foreign'] == TRUE) {
?>
				<th>
					<div class="intexto">
						<select name="<?PHP echo 'n'.$conf['database']['campos_sql'][$i]['nombre_intext']; ?>" size="<?PHP echo $conf['database']['campos_sql'][$i]['logitud_intext']; ?>">
<?PHP
							$db_link = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
							if (!$db_link) {
							   die("Could not connect: ".MYSQL_ERROR());
							}
							MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");
							/* Performing SQL query */
							$result_select = MYSQL_QUERY("SELECT ".$conf['database']['campos_sql'][$i]['campo_id'].",".$conf['database']['campos_sql'][$i]['campo_texto']." FROM ".$conf['database']['campos_sql'][$i]['tabla']." WHERE 1;", $db_link);
							//echo $result;
							/* Closing connection */
							MYSQL_CLOSE($db_link);
							for ($m = 0; $m < @ MYSQL_NUM_ROWS($result_select); $m++) {
								$option = MYSQL_FETCH_ARRAY($result_select);
?>
								<OPTION value="<?PHP echo $option[$conf['database']['campos_sql'][$i]['campo_id']]?>"><?PHP echo $option[$conf['database']['campos_sql'][$i]['campo_texto']];?>
<?PHP
							}
?>
						</select>
					</div>
				</th>
<?PHP
					}
					else {
?>



				<th>
					<div class="intexto">
						<select name="<?PHP echo 'n'.$conf['database']['campos_sql'][$i]['nombre_intext']; ?>" size="<?PHP echo $conf['database']['campos_sql'][$i]['logitud_intext']; ?>">
                            <?PHP
							 for ($m = 0; $m < COUNT($conf['database']['campos_sql'][$i]['valores_id']); $m++) {
                            ?>
		                    <OPTION value="<?PHP echo $conf['database']['campos_sql'][$i]['valores_id'][$m]?>"><?PHP echo $conf['database']['campos_sql'][$i]['valores_texto'][$m];?>
                            <?PHP
							}
                            ?>
						</select>
					</div>
				</th>




<?PHP
					}
				}
				else {
?>
				<th>
					<div class="intexto">
					<?PHP
						$db_link = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
							if (!$db_link) {
	   							die("Could not connect: ".MYSQL_ERROR());
							}
							MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");
							mysql_select_db('scies');
							if($i==0){

							  	$sql_parametro="SELECT * FROM parametro WHERE id_parametro NOT IN (SELECT `id_parametro` FROM sonda WHERE id_centro ='".$_GET['Id_Centro']."' ORDER BY Nombre_Param)";
                                $result_parametro=mysql_query($sql_parametro);
								$num_results_parametro=mysql_num_rows($result_parametro);
								?>

								<select name="parametros" id="parametros" onchange ="formulario_listado.submit(); formulario_listado.sond.enabled=false">


                                <?PHP
                                $p=1;
                                print ("<option value='s'>-- Seleccione Par�metro --</option>");
								for($m=0;$m<$num_results_parametro+$i;$m++){
  									$fila_parametro=mysql_fetch_array($result_parametro);
  									if ((!isset($parametros) and (!isset($cont))) or ((!isset($cont)) and ($_GET['listado_accion'] == 'new')) or (isset($conf['resultado_sql']['orden_tipo']))){
    									$parametros="0";
    									$cont=0;
                                        if($_GET['parametros']==($fila_parametro['Id_Parametro'])){?>
                                            <option selected value="<?PHP echo $fila_parametro['Id_Parametro'];?>"><?PHP echo $fila_parametro['Nombre_Param'];?></option>
                                        <?}
                                        ?>
    									
                                        <option  value="<?PHP echo $fila_parametro['Id_Parametro'];?>"><?PHP echo $fila_parametro['Nombre_Param'];?></option>
									<? }else{
									  $fila_parametro=mysql_fetch_array($result_parametro);
										if($parametros==($fila_parametro['Id_Parametro'])){
											$p=0;?>

									    	<option value="<?PHP echo $fila_parametro['Id_Parametro'];?>"><?PHP echo $fila_parametro['Nombre_Param'];?></option>
                                <?php } else{
             ?>
											<option value="<?PHP echo $fila_parametro['Id_Parametro'];?>"><?PHP echo $fila_parametro['Nombre_Param']; ?></option>
                                <?php
										}
									}
							     }?>
								</select>


<?PHP
							}else{


                              if(isset($_GET['parametros'])){?>
                                     <input type='text' maxLength='5' name='sond' id='sond'>
                                  <?}?>
                                  
                              
                              <script>
                                if(formulario_listado.parametros.value!='s'){
                                  formulario_listado.sond.disabled=false;
                                  
                                }
                                else{
                                    formulario_listado.sond.disabled=true;
                                }</script>
                              
							  	



<?PHP
							}

?>
					</div>
				</th>
<?PHP
				}
			}
?>
				<th colspan="3" id="borde_d">
					<a class="boton2" href="#">
						<div class="boton" onclick="nuevo();">
<?PHP
			echo $conf['texto']['boton_nuevo'];
?>
						</div>
					</a>
				</th>
			</tr>
<?PHP
		}
?>
			<tr>
				<th colspan="<?PHP echo COUNT($conf['database']['campos_sql']) + 4; ?>" class="paginas" id="borde_idb">
					<div id="fleft">
<?PHP


echo $conf['texto']['numero_pagina'];

		$resto = INTVAL(MYSQL_NUM_ROWS($result) / $conf['resultado_sql']['numero_filas']);
		if (MYSQL_NUM_ROWS($result) % $conf['resultado_sql']['numero_filas'] > 0) {
			$resto++;
		}

		if ($conf['resultado_sql']['mostrar_pagina'] > $resto) {
			$conf['resultado_sql']['mostrar_pagina'] = 1;
		}

		$ventana_paginas = INTVAL($resto / $conf['resultado_sql']['tope_paginas']);
		if ($resto % $conf['resultado_sql']['tope_paginas'] > 0) {
			$ventana_paginas++;
		}

		if ($conf['resultado_sql']['mostrar_ventana_paginas'] > $ventana_paginas) {
			$conf['resultado_sql']['mostrar_ventana_paginas'] = 1;
		}

		if (($ventana_paginas > 1) && ($conf['resultado_sql']['mostrar_ventana_paginas'] > 1)) {
?>
					<a class="noseleccionada" href="#" onclick="cambiar_ventana_paginas(<?PHP echo $conf['resultado_sql']['mostrar_ventana_paginas'] - 1; ?>);"><...</a>
<?PHP
		}

		for ($i = ($conf['resultado_sql']['mostrar_ventana_paginas'] - 1) * $conf['resultado_sql']['tope_paginas'] + 1; $i <= $conf['resultado_sql']['mostrar_ventana_paginas'] * $conf['resultado_sql']['tope_paginas']; $i++) {
			if ($i <= $resto) {
				if ($i == $conf['resultado_sql']['mostrar_pagina']) {
?>
					<a class="seleccionada"><?PHP echo $i; ?></a>
<?PHP
				}
				else {
?>
					<a class="noseleccionada" href="#" onclick="cambiar_pagina(<?PHP echo $i; ?>);"><?PHP echo $i; ?></a>
<?PHP
				}
			}
			else {
				$i = $conf['resultado_sql']['mostrar_ventana_paginas'] * $conf['resultado_sql']['tope_paginas'] + 1;
			}
		}

		if (($ventana_paginas > 1) && ($conf['resultado_sql']['mostrar_ventana_paginas'] < $ventana_paginas)) {
?>
					<a class="noseleccionada" href="#" onclick="cambiar_ventana_paginas(<?PHP echo $conf['resultado_sql']['mostrar_ventana_paginas'] + 1; ?>);">...></a>
<?PHP
		}
?>

					</div>
					<div class="intexto" id="fright">
<?PHP
			echo $conf['texto']['mostrar_1'];
?>
						<input type="text" name="<?PHP echo $conf['resultado_sql']['nombre_intext_numero_filas']; ?>" class="tresultados" size="1" value="<?PHP echo $conf['resultado_sql']['numero_filas']; ?>" onkeyup="filtrar(-1);">
<?PHP
			echo $conf['texto']['mostrar_2'];
?>
					</div>
				</th>
			</tr>
		</tfoot>
<?PHP
		// Buscar las filas requeridas dependiendo de la p�gina seleccionada:
		$inicio = ($conf['resultado_sql']['mostrar_pagina'] - 1) * $conf['resultado_sql']['numero_filas'];
		for ($i = 0; $i < $inicio; $i++) {
			$fila = MYSQL_FETCH_ARRAY($result,MYSQL_BOTH);
		}
		// Fin Buscar...

?>
		<tbody>
			<tr class="blanco">
			</tr>

<?PHP
		$pasa = MYSQL_NUM_ROWS($result) - $conf['resultado_sql']['mostrar_pagina'] * $conf['resultado_sql']['numero_filas'];
		if ($pasa < 0) {
			$tope = $conf['resultado_sql']['numero_filas'] + $pasa;
		}
		else {
			$tope = $conf['resultado_sql']['numero_filas'];
		}

		if ($conf['remove_selected'] == TRUE) {
			$script_seleccionar = "<script>";
		}
		else {
			$script_seleccionar = "<script> function seleccionar_todo() { ";
		}

		for ($i = 0; $i < $tope; $i++) {
			$fila = MYSQL_FETCH_ARRAY($result,MYSQL_BOTH);

			if ($conf['menu_valor'] == $fila['Clave']) {
				if ($conf['menu_accion'] == 'modify') {
				/* MODIFICAR REGISTRO */
?>
				<tr id="<?PHP echo $fila['Clave']; ?>" class="accion">
					<td id="borde_i" align="center" class="accion">
					</td>
<?PHP
					$tope2 = COUNT($conf['database']['campos_sql']);
					for ($j = 0; $j < $tope2; $j++) {
?>
					<td class="accion">
						<div class="intexto">
							<input type="text" name="<?PHP echo 'm'.$conf['database']['campos_sql'][$j]['nombre_intext']; ?>" size="<?PHP echo $conf['database']['campos_sql'][$j]['logitud_intext']; ?>" maxLength="<?PHP echo $conf['database']['campos_sql'][$j]['logitud_max']; ?>" value="<?PHP echo $fila[$conf['database']['campos_sql'][$j]['nombre_db']]; ?>">
						</div>
					</td>
<?PHP
					}
?>
					<a href="#" class="confirmar_aceptar">
						<td onclick="confirmarModificar('<?PHP echo $conf['menu_valor']; ?>');" title="Aceptar Modificaci�n">
						</td>
					</a>
					<a href="#" class="confirmar_cancelar">
						<td onclick="confirmarModificar('NULL');" title="Cancelar" id="borde_d" colspan="2">
						</td>
					</a>
				</tr>
<?PHP
				/* FIN MODIFICAR REGISTRO */
				}
				else if ($conf['menu_accion'] == 'remove') {
				/* ELIMINAR REGISTRO */
?>
				<tr id="<?PHP echo $fila['Clave']; ?>" class="accion" >
					<td id="borde_i" align="center" class="accion">
					</td>
<?PHP
				$tope2 = COUNT($conf['database']['campos_sql']);
				for ($j = 0; $j < $tope2; $j++) {
?>
					<td class="accion">
<?PHP
					echo $fila[$conf['database']['campos_sql'][$j]['nombre_db']];
?>
					</td>
<?PHP
				}
?>			
	<a href="#" class="confirmar_aceptar">
						<td   onclick="confirmarEliminar('<?PHP echo $conf['menu_valor']; ?>');" width="6px" title="Eliminar el Registro" style="background-repeat:no-repeat;">
						</td>
					</a>
						<td    class="accion"  ></td>
					<a href="#" class="confirmar_cancelar">
						<td onclick="confirmarEliminar('NULL');" title="Cancelar" id="borde_d" style="background-repeat:no-repeat;" width="6px">

						</td>
					</a>
				</tr>
<?PHP
				/* FIN ELIMINAR REGISTRO */
				}
				//else if ... {
					/* Preparado para otra funci�n del men� contextual */
				//}
			}
			else {
?>
			<a href="#">
				<tr id="<?PHP echo $fila['Clave']; ?>" oncontextmenu="mostrarMenu('<?PHP echo $fila['Clave']; ?>');">
					<td id="borde_i" align="center" style="padding:0 0 0 0;">
						<div class="checkbox">
<?PHP		
				if ($conf['remove_selected'] == TRUE) {
				
					if (IN_ARRAY($fila['Clave'],$_GET['checks'])) {
						$script_seleccionar =  $script_seleccionar.'pulsarCheckbox("checkbox_'.$fila['Clave'].'","fondo_checkbox_'.$fila['Clave'].'");';
?>
							<div class="unchecked" id="fondo_checkbox_<?PHP echo $fila['Clave']; ?>">
								<input type="checkbox" name="checks[]" value="<?PHP echo $fila['Clave']; ?>" id="checkbox_<?PHP echo $fila['Clave']; ?>"/>&nbsp;
							</div>
<?PHP
					}
				}
				else {
						
					$script_seleccionar =  $script_seleccionar.'pulsarCheckbox("checkbox_'.$fila['Clave'].'","fondo_checkbox_'.$fila['Clave'].'");';
?>
							<div class="unchecked" id="fondo_checkbox_<?PHP echo $fila['Clave']; ?>" onclick="pulsarCheckbox('checkbox_<?PHP echo $fila['Clave']; ?>', this.id);">
								<input type="checkbox" name="checks[]" value="<?PHP echo $fila['Clave']; ?>" id="checkbox_<?PHP echo $fila['Clave']; ?>"/>&nbsp;
							</div>
<?PHP
				}
?>
						</div>
					</td>
<?PHP
				$tope2 = COUNT($conf['database']['campos_sql']);
				for ($j = 0; $j < $tope2; $j++) {
?>
					<td>
<?PHP
					echo $fila[$conf['database']['campos_sql'][$j]['nombre_db']];
?>
					</td>
<?PHP
				}
?>
					<td>
						<!--<input type="image" src="./modificar">-->
					</td>
					<td id="borde_d" colspan="2">
						<!--<input type="image" src="./eliminar">-->
					</td>
				</tr>
			</a>
<?PHP
			}
		}
		if ($conf['remove_selected'] == TRUE) {
			$script_seleccionar = $script_seleccionar." </script>";
		}
		else {
			$script_seleccionar = $script_seleccionar."} </script>";
		}
?>
		</tbody>
	</table>


<div id="menu" style="position:absolute;visibility:hidden;padding:0 0 0 0;margin:0px;">
	<div class="item_esquina"></div>
	<table cellpadding=0 cellspacing=0>


<?PHP
		$tope = count($conf['menu_contextual']);
		for ($i = 0; $i < $tope; $i++) {
?>
		<tr>
			<td style="padding: 0 0 0 0;">
				<div class="item_izq"></div>
<?PHP
			if ($conf['menu_contextual'][$i]['link'] != '') {
?>
				<div class="item" align="center" style="width:100px;" onclick="irLink('<?PHP echo $conf['menu_contextual'][$i]['link']; ?>');">
<?PHP
			}
			else {
?>
				<div class="item" align="center" style="width:100px;" onclick="enviarAccion('<?PHP echo $conf['menu_contextual'][$i]['accion']; ?>');">
<?PHP
			}
			echo $conf['menu_contextual'][$i]['texto'];
?>
				</div>
				<div class="item_der"></div>
			</td>
		</tr>
<?PHP
		}
?>
	</table>
</div>

<script language="javascript">

function irLink(pag) {
	window.location.href('<? echo $conf['general']['nombre_principal']; ?>?pag=' + pag + '&clave=' + document.<?PHP echo $conf['general']['nombre_formulario']; ?>.menu_valor.value);
}

function pulsarCheckbox(hiddenChkBox,skinnedCheckBoxId) {

	if(!document.getElementById(hiddenChkBox).disabled)	{
		if(document.getElementById(hiddenChkBox).checked) {
			document.getElementById(skinnedCheckBoxId).className = 'unchecked';
			document.getElementById(hiddenChkBox).checked = false;
		}
		else {
			document.getElementById(skinnedCheckBoxId).className = 'checked';
			document.getElementById(hiddenChkBox).checked = true;
		}
	}
}

function confirmarModificar(clave) {
	if (clave != 'NULL') {
		document.<?PHP echo $conf['general']['nombre_formulario']; ?>.confirmar_modificar.value = clave;
	}
	document.<?PHP echo $conf['general']['nombre_formulario']; ?>.submit();
}

function confirmarEliminar(clave) {
	if (clave != 'NULL') {
		document.<?PHP echo $conf['general']['nombre_formulario']; ?>.confirmar_eliminar.value = clave;
	}
	document.<?PHP echo $conf['general']['nombre_formulario']; ?>.submit();
}

function enviarAccion(accion) {
	document.<?PHP echo $conf['general']['nombre_formulario']; ?>.menu_accion.value = accion;
	document.<?PHP echo $conf['general']['nombre_formulario']; ?>.submit();
}

function mostrarMenu(valor) {
	var rightedge = document.body.clientWidth - event.clientX;
	var bottomedge = document.body.clientHeight - event.clientY;
	if (rightedge < menu.offsetWidth)
		menu.style.left = document.body.scrollLeft + event.clientX - menu.offsetWidth;
	else
		menu.style.left = document.body.scrollLeft + event.clientX;
	if (bottomedge < menu.offsetHeight)
		menu.style.top = document.body.scrollTop + event.clientY - menu.offsetHeight;
	else
		menu.style.top = document.body.scrollTop + event.clientY;
	document.<?PHP echo $conf['general']['nombre_formulario']; ?>.menu_valor.value = valor;
	menu.style.visibility = "visible";
}

function mostrarMenuNormal() {
	if (menu.style.visibility == "visible") {
		return false;
	}
	else {
		return true;
	}
}

function ocultarMenu() {
	menu.style.visibility = "hidden";
}

document.body.oncontextmenu = mostrarMenuNormal;
document.body.onclick = ocultarMenu;

</script>

</form>

<?PHP
		echo $script_seleccionar;
	}
?>

</body>

<?PHP

// Devolvemos el foco de la p�gina a los campos de filtrado
echo '<SCRIPT language"JavaScript">';
if ($_GET['listado_foco'] != '') {
	if ($_GET['listado_foco'] == '-1') { // Para el campo de mostrar filas.
		echo 'foco('.$conf['general']['nombre_formulario'].'.'.$conf['resultado_sql']['nombre_intext_numero_filas'].','.STRLEN($conf['general']['nombre_formulario'].'.'.$conf['resultado_sql']['nombre_intext_numero_filas'].'value').');';
	}
	else {
	    echo 'foco('.$conf['general']['nombre_formulario'].'.'.'intext_'.$conf['database']['campos_sql'][INTVAL($_GET['listado_foco'])]['nombre_db'].','.STRLEN($_GET[$conf['database']['campos_sql'][INTVAL($_GET['listado_foco'])]['nombre_intext']]).');';
	}
  	unset($_GET['listado_foco']);
}
echo '</SCRIPT>';

?>

</html>
