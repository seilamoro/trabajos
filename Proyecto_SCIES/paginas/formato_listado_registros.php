<div class="paglistado">


<script language="JavaScript">
	function paginas(valor,valor2){
		
		location.href='?pag=formato_listado_registros.php&pagi='+valor+'&salto='+valor2;
	}

	function cambiar_ventana_paginas(valor){
		location.href='?pag=formato_listado_registros.php&salto='+valor+'&pagi=1';
	}
	function mostrar_s(valor){
		location.href='?pag=formato_listado_registros.php&nf_m='+valor;
	}
</script>




<?php 
session_start();
$db = mysql_pconnect('localhost','root','');
	if (!$db){
		echo ("Error al acceder a la base de datos, intentelo mas tarde");
		exit();
	}
mysql_select_db("scies");
$conf['general']['volver'] = true;
$sql_centros='SELECT Nombre FROM centro where Id_Centro='.$_SESSION['instalacion'];
$result_centros=mysql_query($sql_centros);
@$centros = MYSQL_FETCH_ARRAY($result_centros);

$_SESSION['centro']=$centros['Nombre'];
if(!isset($_GET['pagi']) && !isset($_GET['salto']) && !isset($_GET['nf_m'])){
	$pagi=0;
	$_SESSION['dia']=$_GET['dia_i'];
	$_SESSION['mes']=$_GET['mes_i'];
	$_SESSION['anio']=$_GET['anio_i'];
	$_SESSION['dia_fin']=$_GET['dia_f'];
	$_SESSION['mes_fin']=$_GET['mes_f'];
	$_SESSION['anio_fin']=$_GET['anio_f'];
	$_SESSION['hora']=$_GET['hora_i'];
	$_SESSION['minuto']=$_GET['minuto_i'];
	$_SESSION['hora_fin']=$_GET['hora_f'];
	$_SESSION['minuto_fin']=$_GET['minuto_f'];
	$_SESSION['sema']=$_GET['seman'];
	
}
if(!isset($_GET['nf_m']) || $_GET['nf_m']==0){
	if($_SESSION['nf_mostrar']==0){
		$_SESSION['nf_mostrar']=15;
	}
}else{
	if(isset($_GET['nf_m'])){
		$_SESSION['nf_mostrar']=$_GET['nf_m'];
	}
}

if(!isset($_GET['pagi']) && !isset($_GET['salto']) && !isset($_GET['nf_m'])){
	if($_SESSION['instalacion']!=''){
		$_SESSION['sele']=array();
		$sql_parametros="select * from parametro inner join sonda on parametro.Id_Parametro=sonda.Id_Parametro where sonda.Id_Centro=".$_SESSION['instalacion'];
	
		$result_parametros=mysql_query($sql_parametros);
		$nume_parametros=mysql_num_rows($result_parametros);
	
		$_SESSION['ndis']=$_GET['ndis'];
		$_SESSION['nsel']=$_GET['nsel'];
	
		for($i=0;$i<$nume_parametros;$i++){
			$parametros = MYSQL_FETCH_ARRAY($result_parametros);
		
			for($f=0;$f<$ndis;$f++){
				$_SESSION['disp'][$f]=$_GET['dis'.$f];
				if($parametros['Id_Parametro']==$_SESSION['disp'][$f]){
					$_SESSION['disp']['pa'][$f]=$parametros['Nombre_Param'];
					//echo $_SESSION['disp'][$f].'-'.$_SESSION['disp']['pa'][$f].' ---dis<br>';
				}
			}
			for($f=0;$f<$nsel;$f++){
				$_SESSION['sele'][$f]=$_GET['sel'.$f];
				if($parametros['Id_Parametro']==$_SESSION['sele'][$f]){
					$_SESSION['sele']['pa'][$f]=$parametros['Nombre_Param'];
					//echo $_SESSION['sele'][$f].'-'.$_SESSION['sele']['pa'][$f].' ---sel<br>';
				}
			}
		}
	}
}
////////////////////////////////////////////////////////////////////
$sql_lecturas='SELECT * FROM lectura where Id_Centro='.$_SESSION['instalacion'];
				
if($nsel>1){
	$sql_lecturas.=' and (';
	for($f=0;$f<$nsel;$f++){
		$sql_lecturas.='Id_Parametro='.$_SESSION['sele'][$f];
		if($_SESSION['sele'][$f+1]!=null){
			$sql_lecturas.=' or ';
		}else{
			$sql_lecturas.=')';
		}
	}
}else{
	$sql_lecturas.=' and Id_Parametro='.$_SESSION['sele'][0];
}



if($_SESSION['listado']=="personalizado" || !isset($_SESSION['listado'])){
	$_SESSION['fecha']="Desde ".$_SESSION['dia']."/".$_SESSION['mes']."/".$_SESSION['anio']." ".$_SESSION['hora'].":".$_SESSION['minuto']." hasta ".$_SESSION['dia_fin']."/".$_SESSION['mes_fin']."/".$_SESSION['anio_fin']." ".$_SESSION['hora_fin'].":".$_SESSION['minuto_fin'];
	if( (($_SESSION['anio_fin'] == $_SESSION['anio']) && ($_SESSION['mes_fin'] == $_SESSION['mes'])) && ($_SESSION['dia_fin'] == $_SESSION['dia']) ){
		$sql_lecturas .= ' and Fecha =\'' .$_SESSION['anio']. '-' .$_SESSION['mes']. '-' .$_SESSION['dia']. '\' and Hora >= \'' .$_SESSION['hora']. ':' .$_SESSION['minuto']. '\' and Hora <\'' .$_SESSION['hora_fin']. ':' .$_SESSION['minuto_fin']. '\'';
	}else{
		$sql_lecturas .= ' and ((Fecha >\'' .$_SESSION['anio']. '-' .$_SESSION['mes']. '-' .$_SESSION['dia']. '\' AND Fecha<\'' .$_SESSION['anio_fin']. '-' .$_SESSION['mes_fin']. '-' .$_SESSION['dia_fin']. '\') OR (Fecha =\'' .$_SESSION['anio']. '-' .$_SESSION['mes']. '-' .$_SESSION['dia']. '\' AND Hora >= \'' .$_SESSION['hora']. ':' .$_SESSION['minuto']. '\')OR (Fecha =\'' .$_SESSION['anio_fin']. '-' .$_SESSION['mes_fin']. '-' .$_SESSION['dia_fin']. '\' AND Hora <=\'' .$_SESSION['hora_fin']. ':' .$_SESSION['minuto_fin']. '\'))';
	}
}

if($_SESSION['listado']=="diario"){
	$_SESSION['fecha']=$_SESSION['dia']."/".$_SESSION['mes']."/".$_SESSION['anio'];
	$sql_lecturas.=' and Fecha=\''.$_SESSION['anio'].'-'.$_SESSION['mes'].'-'.$_SESSION['dia'].'\'';
}

if($_SESSION['listado']=="semanal"){
	$_SESSION['fecha']=$_SESSION['sema'];
	$sql_lecturas.=' and Fecha>=\''. $_SESSION['anio'] .'-'. $_SESSION['mes'] .'-'. substr($_SESSION['sema'],0,2) .'\' and Fecha<=\''. $_SESSION['anio'] .'-'. $_SESSION['mes'] .'-'. substr($_SESSION['sema'],11,2) .'\'';
}

if($_SESSION['listado']=="mensual"){
	$_SESSION['fecha']=$_SESSION['mes']."/".$_SESSION['anio'];
	$sql_lecturas.=' and SUBSTRING(Fecha,1,7)=\''.$_SESSION['anio'].'-'.$_SESSION['mes'].'\'';
}

if($_SESSION['listado']=="anual"){
	$_SESSION['fecha']=$_SESSION['anio'];
	$sql_lecturas.=' and SUBSTRING(Fecha,1,4)='.$_SESSION['anio'];
}

				
$_SESSION['sentencia']=$sql_lecturas;
$result_lecturas=mysql_query($sql_lecturas);
@$n_lecturas=mysql_num_rows($result_lecturas);
if($n_lecturas%$_SESSION['nf_mostrar']==0){
	$num_pag=intval($n_lecturas/$_SESSION['nf_mostrar']);
}else{
	$num_pag=intval($n_lecturas/$_SESSION['nf_mostrar'])+1;
}
if($n_lecturas!=0){
	if($n_lecturas%$_SESSION['nf_mostrar']<$_SESSION['nf_mostrar']){
		$ls=$n_lecturas%$_SESSION['nf_mostrar'];
	}else{
		$ls=15;
	}
	if($pagi != 0){
		$sql_lecturas.=' limit '.(($salto+$pagi-1)*$_SESSION['nf_mostrar']).','.$_SESSION['nf_mostrar'];
	}else{
		$sql_lecturas.=' limit 0,'.$_SESSION['nf_mostrar'];
	}
	$result_lecturas=mysql_query($sql_lecturas);
}
if ($salto==0 || !$salto){
			if(!isset($_GET['salto'])){
				$pagi=1;
			}
			if($n_lecturas < $pagi * $_SESSION['nf_mostrar']){
				$s=$n_lecturas%$_SESSION['nf_mostrar'];
			}else{
				$s=$_SESSION['nf_mostrar'];
			}
		}else{
			if($n_lecturas < ( $salto + $pagi ) * $_SESSION['nf_mostrar']){
				$s=$n_lecturas%$_SESSION['nf_mostrar'];
			}else{
				$s=$_SESSION['nf_mostrar'];
			}
		}
////////////////////////////////////////////////////////////////////////
?>

<table  class="listados" align="center" width='800'>
	<caption>
					<?php 
						echo $centros['Nombre']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <label style=font-size:16px>(".$_SESSION['fecha'].")</label>";
					?>
	</caption>

	<thead>
	
		<tr>
			<th class="titulo" id="borde_idb" align='center'>
				<div class="borde"></div>
				<div class="centro">
					Parámetro
				</div>
			</th>
			<?php
				if($_SESSION['listado']!="diario"){
			?>
			<th class="titulo" id="borde_idb" align='center'>
				<div class="borde"></div>
				<div class="centro">
					Fecha
				</div>
			</th>
			<?php
				}
			?>
			<th class="titulo" id="borde_idb" align='center'>
				<div class="borde"></div>
				<div class="centro">
					Hora
				</div>
			</th>
			<th class="titulo" id="borde_idb" align='center'>
				<div class="borde"></div>
				<div class="centro">
					Valor
				</div>
			</th>
		</tr>
	</thead>
	<tbody>
		<?php
		
			
			for($i=0;$i<$s;$i++){
				@$lecturas = MYSQL_FETCH_ARRAY($result_lecturas);
				$sql_parametros2="select * from parametro inner join sonda on parametro.Id_Parametro=sonda.Id_Parametro where sonda.Id_Centro=".$_SESSION['instalacion']." and parametro.Id_Parametro=".$lecturas['Id_Parametro'];
				$result_parametros2=mysql_query($sql_parametros2);
				@$parametros2 = MYSQL_FETCH_ARRAY($result_parametros2);
				echo "<a href='#'><tr oncontextmenu='if (!event.ctrlKey){displayMenu();return false;}'>";
				if($i==0){
					$nombre_p=$parametros2['Nombre_Param'];
				}
				echo "<td class='celda' id='borde_i'>";
				if($parametros2['Nombre_Param']==$nombre_p){
					if($i==0){
						echo $parametros2['Nombre_Param'];
					}else{
						echo "&nbsp;";
					}
				}else{
					$nombre_p=$parametros2['Nombre_Param'];
					echo $parametros2['Nombre_Param'];
				}
				echo "</td>";
				if($_SESSION['listado']!="diario"){
					echo "<td class='celda' id='borde_i'>".$lecturas['Fecha']."</td>";
				}
				echo "<td class='celda' id='borde_i'>".$lecturas['Hora']."</td>";
				echo "<td class='celda' id='borde_id'>".$lecturas['Valor']."</td>";
				echo "</tr></a>";
			}
		?>
	</tbody>
	<tfoot>
			
				<tr>
					<th colspan=4 class="paginas" id="borde_idb">
					<?php
						if ($conf['general']['volver'] == true) {
					?>
							<a href="#" align=center class="volver">
								<div style="float: left; width:4px;">
								</div>
								<div class="volver" title="Página Aterior" onclick="location.href='?pag=criterios_listados.php';">
								</div>
								<div style="float: left; width:20px;">
								</div>
							</a>
					<?PHP
						}
					?>
					<div id="fleft">
						<?php 
							$n_paginas=intval($n_lecturas/$_SESSION['nf_mostrar']);
							if($n_lecturas%$_SESSION['nf_mostrar']>0){
								$n_paginas=$n_paginas+1;
							}
							//echo $n_paginas;
							$lim_pag=13;
							if(!$salto){
								$salto=0;
							}
							if($salto>0){
								?>
								<a class="noseleccionada" href="#" onclick="cambiar_ventana_paginas(<?php echo $salto-$lim_pag; ?>);"><...</a>
							<?php
							}

							for ($i=0;$lim_pag>$i;$i++){
								if($salto+$i<$n_paginas){
									if ($pagi == ($i+1)){
						?>
										<a class="seleccionada"><?PHP echo $i+1+$salto; ?></a>
						<?php
									}else{
						?>
										<a class="noseleccionada" href="#" onclick="paginas(<?PHP echo $i+1; ?>,<?PHP echo $salto; ?>);"><?PHP echo $i+1+$salto; ?></a>
						<?php
									}
								}
							}
							if($n_paginas>$salto && $salto+$lim_pag<$n_paginas){
								//$salto=+$lim_pag;
						?>		
						<a class="noseleccionada" href="#" onclick="cambiar_ventana_paginas(<?php echo $salto+$lim_pag; ?>);">...></a>
						<?php
							}
						?>
						</div>
						<div class="intexto" id="fright">
							Mostrar
							<input type="text" name="<?PHP echo $_SESSION['nf_mostrar']; ?>" class="tresultados" size="1" value="<?PHP echo $_SESSION['nf_mostrar']; ?>"   onkeyup="mostrar_s(value);">
							Registros
						</div>
					</th>
				</tr>
			</tfoot>
</table>
</div>

<?php
	//echo $sql_lecturas."<br>".$n_lecturas."<br>";
	//echo $salto."<br>";
	//echo $pagi."<br>";
	//echo $s;
?>