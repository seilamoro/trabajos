<!-- EMPIEZA TABLA ALERTAS -->
	<?php
	if(isset($_SESSION['permisoInternaREAJ']) && $_SESSION['permisoInternaREAJ']==true){
	?>
	<div class='elemento_alertas'>
		<a href="../Gestion_Interna/principal_gi.php?pag=gi_reaj.php" >
		<?php
			@ $db=mysql_pconnect("localhost","root","");
			mysql_select_db("SAM",$db);
			$qry="SELECT * FROM reaj ";
			$res=mysql_query($qry);
			$reaj_on=false;
			for($i=0;$i<mysql_num_rows($res);$i++){
				$fila=mysql_fetch_array($res);
				if($fila['Stock_Carnet']<6){
					$reaj_on=true;
				}
			}
			if($reaj_on == false){
				echo("<IMG src='../imagenes/skins-".$_SESSION['aspecto']."/alerta_reaj.gif' border=0>");
			}else{
				echo("<IMG src='../imagenes/skins-".$_SESSION['aspecto']."/alerta_reaj_on.gif' border=0 alt='Bajo Stock'>");
			}
		?>
		</a>
	</div>
	<?php
	}else{echo '<div id="span_menu_alertas_reaj" style="display:block;"></div>';}
	
	if(isset($_SESSION['permisoOnLine']) && $_SESSION['permisoOnLine']==true){
	?>
	<div class='elemento_alertas'>
		<a href=".?pag=reservas_online.php" onClick="cambia_alertas('alertas_reaj');">
		<?
			$qry="SELECT * FROM reserva WHERE Id_Hab = 'PRA' ";
			$res=mysql_query($qry);
			$num = mysql_num_rows($res);
			if($num == '0'){
		?>
				<IMG src='../imagenes/skins-<?echo $_SESSION['aspecto']?>/alerta_online.gif' border=0>
		<?
			}else{
		?>
				<IMG src='../imagenes/skins-<?echo $_SESSION['aspecto']?>/alerta_online_on.gif' border=0>
		<?
			}
		?>
		</a>
	</div>
	<?php
		}else{echo '<div style="display:block;"></div>';}
		
		if(isset($_SESSION['permisoFacturacion']) && $_SESSION['permisoFacturacion']==true){
	?>
	
	<div class='elemento_alertas'>
<?
		// Código para ver si hay estancias sin facturar con fecha de salida de ayer o anterior:
		$sin_facturar = false;
		
		//Estancias de alberguistas:	  	
		$sql = "SELECT DNI_Cl,Fecha_Llegada FROM pernocta WHERE (DNI_Cl,Fecha_Llegada) NOT IN (SELECT DNI_Cl,Fecha_Llegada FROM genera) AND Fecha_Salida < (SELECT CURDATE());";
		$result = MYSQL_QUERY($sql);
		if (MYSQL_NUM_ROWS($result) > 0) {
		  	$sin_facturar = true;
		}
		//echo $sql;
		
		//Estancias de peregrinos:	  	
		$sql = "SELECT DNI_Cl,Fecha_Llegada FROM pernocta_p WHERE (DNI_Cl,Fecha_Llegada) NOT IN (SELECT DNI_Cl,Fecha_Llegada FROM genera_p) AND Fecha_Salida < (SELECT CURDATE());";
		$result = MYSQL_QUERY($sql);
		if (MYSQL_NUM_ROWS($result) > 0) {
		  	$sin_facturar = true;
		}
		//echo $sql;
		
		//Estancias de grupos:	  	
		$sql = "SELECT Nombre_Gr,Fecha_Llegada FROM estancia_gr WHERE (Nombre_Gr,Fecha_Llegada) NOT IN (SELECT Nombre_Gr,Fecha_Llegada FROM genera_gr) AND Fecha_Salida < (SELECT CURDATE());";
		$result = MYSQL_QUERY($sql);
		if (MYSQL_NUM_ROWS($result) > 0) {
		  	$sin_facturar = true;
		}
		//echo $sql;
		
		if ($sin_facturar == true) {
?>

<script language="JavaScript">

	//Funcion que muestra el listado de estancias sin facturar en la parte estancias en el albergue
	function estancias_sin_facturar() {
	  	window.location.href('?pag=gdh.php&no_facturas=true');
	}

</script>
			<IMG src='../imagenes/skins-<?echo $_SESSION['aspecto']?>/alerta_facturas_on.gif' title="Hay estancias sin facturar con fecha de salida de ayer o anterior." border=0 onclick="estancias_sin_facturar();" style="cursor:pointer;">
<?PHP
		}
		else {
?>
			<a href="#" onClick="">
				<IMG src='../imagenes/skins-<?echo $_SESSION['aspecto']?>/alerta_facturas.gif' border=0>
			</a>
<?PHP
		}		
		// Fin Código para... 
?>		
	</div>

	<?php
		}else{echo '<div style="display:block;"></div>';}
	?>
	<!--<tr style="display:block;" onClick="pliega()"><td >X</td></tr>-->

		
<!-- ACABA TABLA ALERTAS -->



