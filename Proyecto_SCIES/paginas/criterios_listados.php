<script>
	sortitems = 1;
//INICIO Selecionar los parametros
	function move(fbox,tbox) {
		for(var i=0; i<fbox.options.length; i++) {
			if(fbox.options[i].selected && fbox.options[i].value != "") {
				var no = new Option();
				no.value = fbox.options[i].value;
				no.text = fbox.options[i].text;
				tbox.options[tbox.options.length] = no;
				fbox.options[i].value = "";
				fbox.options[i].text = "";
			}
		}
		BumpUp(fbox);
		if (sortitems) SortD(tbox);
			//selecciones(fbox,tbox);
	}

	function moveall(fbox,tbox) {
		for(var i=0; i<fbox.options.length; i++) {
			var no = new Option();
			no.value = fbox.options[i].value;
			no.text = fbox.options[i].text;
			tbox.options[tbox.options.length] = no;
			fbox.options[i].value = "";
			fbox.options[i].text = "";
		}
		BumpUp(fbox);
		if (sortitems) SortD(tbox);
			//selecciones(fbox,tbox);
	}

	function BumpUp(box) {
		for(var i=0; i<box.options.length; i++) {
			if(box.options[i].value == "") {
				for(var j=i; j<box.options.length-1; j++) {
					box.options[j].value = box.options[j+1].value;
					box.options[j].text = box.options[j+1].text;
				}
				var ln = i;
				break;
			}
		}
		if(ln < box.options.length) {
			box.options.length -= 1;
			BumpUp(box);
		}
	}
	
	function SortD(box) {
		var temp_opts = new Array();
		var temp = new Object();
		for(var i=0; i<box.options.length; i++){
			temp_opts[i] = box.options[i];
		}
		for(var x=0; x<temp_opts.length-1; x++){
			for(var y=(x+1); y<temp_opts.length; y++){
				if(temp_opts[x].text > temp_opts[y].text){
					temp = temp_opts[x].text;
					temp_opts[x].text = temp_opts[y].text;
					temp_opts[y].text = temp;
					temp = temp_opts[x].value;
					temp_opts[x].value = temp_opts[y].value;
					temp_opts[y].value = temp;
				}
			}
		}
		for(var i=0; i<box.options.length; i++) {
			box.options[i].value = temp_opts[i].value;
			box.options[i].text = temp_opts[i].text;
		}
	}
//FIN Selecionar los parametros

//INICIO Carga el tipo de listado segun la periocidad
	function cargar_parametros(valor){
		location.href='principal.php?pag=criterios_listados.php&instalacion='+valor;
	}
	function semana(valor,valor2){
		location.href='principal.php?pag=criterios_listados.php&seman='+valor2+'&indic='+valor;
	}
	function mes(valor){
		location.href='principal.php?pag=criterios_listados.php&mess='+valor;
	}
	function ayo(valor){
		location.href='principal.php?pag=criterios_listados.php&anyio='+valor;
	}
//FIN Carga el tipo de listado segun la periocidad
	
//INICIO Carga los parametros dependiendo del centro
	var dir='';
	var dir2='';
	function cargar_listado(valor,fbox,tbox){
		for(var i=0; i<fbox.options.length; i++) {
			dir+='&dis'+i;
			dir+='='+fbox.options[i].value;
		}
		for(var i=0; i<tbox.options.length; i++) {
			dir2+='&sel'+i;
			dir2+='='+tbox.options[i].value;
		}
		location.href='principal.php?pag=criterios_listados.php&listad='+valor+dir+dir2+'&ndis='+fbox.options.length+'&nsel='+tbox.options.length;
	}
	function listar_personalizado(fbox,tbox,dia_ini,mes_ini,ayo_ini,dia_fin,mes_fin,ayo_fin,hora_ini,minuto_ini,hora_fin,minuto_fin){
		if(comprobar_form(fo) == false){
			return false;
		}
		for(var i=0; i<fbox.options.length; i++) {
			dir+='&dis'+i;
			dir+='='+fbox.options[i].value;
		}
		for(var i=0; i<tbox.options.length; i++) {
			dir2+='&sel'+i;
			dir2+='='+tbox.options[i].value;
		}
		location.href='principal.php?pag=formato_listado_registros.php&&ndis=' + fbox.options.length + dir + dir2 +  '&nsel=' + tbox.options.length + '&dia_i=' + dia_ini + '&mes_i=' + mes_ini + '&anio_i=' + ayo_ini + '&dia_f=' + dia_fin + '&mes_f=' + mes_fin + '&anio_f=' + ayo_fin + '&hora_i=' + hora_ini + '&minuto_i=' + minuto_ini + '&hora_f=' + hora_fin + '&minuto_f=' + minuto_fin;
	}

	function listar_diario(fbox,tbox,dia_ini,mes_ini,ayo_ini){
		if(comprobar_form(fo) == false){
			return false;
		}
		for(var i=0; i<fbox.options.length; i++) {
			dir+='&dis'+i;
			dir+='='+fbox.options[i].value;
		}
		for(var i=0; i<tbox.options.length; i++) {
			dir2+='&sel'+i;
			dir2+='='+tbox.options[i].value;
		}
		location.href='principal.php?pag=formato_listado_registros.php&ndis=' + fbox.options.length + dir + dir2 + '&nsel=' + tbox.options.length + '&dia_i=' + dia_ini + '&mes_i=' + mes_ini + '&anio_i=' + ayo_ini;
	}

	function listar_semanal(fbox,tbox,semana,mes_ini,ayo_ini){
		if(comprobar_form(fo) == false){
			return false;
		}
		for(var i=0; i<fbox.options.length; i++) {
			dir+='&dis'+i;
			dir+='='+fbox.options[i].value;
		}
		for(var i=0; i<tbox.options.length; i++) {
			dir2+='&sel'+i;
			dir2+='='+tbox.options[i].value;
		}
		location.href='principal.php?pag=formato_listado_registros.php&ndis=' + fbox.options.length + dir + dir2 + '&nsel=' + tbox.options.length + '&seman=' + semana + '&mes_i=' + mes_ini + '&anio_i=' + ayo_ini;
	}

	function listar_mensual(fbox,tbox,mes_ini,ayo_ini){
		if(comprobar_form(fo) == false){
			return false;
		}
		for(var i=0; i<fbox.options.length; i++) {
			dir+='&dis'+i;
			dir+='='+fbox.options[i].value;
		}
		for(var i=0; i<tbox.options.length; i++) {
			dir2+='&sel'+i;
			dir2+='='+tbox.options[i].value;
		}
		location.href='principal.php?pag=formato_listado_registros.php&ndis=' + fbox.options.length + dir + dir2 + '&nsel=' + tbox.options.length + '&mes_i=' + mes_ini + '&anio_i=' + ayo_ini;
	}

	function listar_anual(fbox,tbox,ayo_ini){
		if(comprobar_form(fo) == false){
			return false;
		}
		for(var i=0; i<fbox.options.length; i++) {
			dir+='&dis'+i;
			dir+='='+fbox.options[i].value;
		}
		for(var i=0; i<tbox.options.length; i++) {
			dir2+='&sel'+i;
			dir2+='='+tbox.options[i].value;
		}
		location.href='principal.php?pag=formato_listado_registros.php&ndis=' + fbox.options.length + dir + dir2 + '&nsel=' + tbox.options.length + '&anio_i=' + ayo_ini;
	}
//FIN Carga los parametros dependiendo del centro

//INICIO Comprueba los campos y la fecha para realizar las sentencias SQL
	function comprobar_form(fo){
		if(fo.centro.value == ""){
			alert('Rellene todos los campos');
			return false;
		}
		if(fo.list2.options.length == 0){
			alert('Seleccione al menos un parámetro');
			return false;
		}
		if(fo.anio_inicio_v.value == ''){
			alert('Rellene el año de inicio');
			return false;
		}
		if(fo.listado[0].checked == true){
			if(fo.anio_fin_v.value == ""){
				alert('Rellene el año de fin');
				return false;
			}else{
				if(fo.anio_inicio_v.value > fo.anio_fin_v.value){
					alert('El año de inicio debe ser menor o igual que el de fin');
					return false;
				}else{
					if(fo.anio_inicio_v.value == fo.anio_fin_v.value && fo.mes_inicio_v.value > fo.mes_fin_v.value){
						alert('El mes de inicio debe ser menor o igual que el de fin');
						return false;
					}else{
						if(fo.mes_inicio_v.value == fo.mes_fin_v.value && fo.dia_inicio_v.value > fo.dia_fin_v.value){
							alert('El dia de inicio debe ser menor o igual que el de fin');
							return false;
						}else{
							if(fo.dia_inicio_v.value == fo.dia_fin_v.value && fo.hora_inicio_v.value > fo.hora_fin_v.value){
								alert('La hora de inicio debe ser menor o igual que la de fin');
								return false;
							}else{
								if(((((fo.anio_inicio_v.value == fo.anio_fin_v.value) && (fo.mes_inicio_v.value == fo.mes_fin_v.value)) && (fo.dia_inicio_v.value == fo.dia_fin_v.value)) && (fo.hora_inicio_v.value == fo.hora_fin_v.value)) && (fo.minuto_inicio_v.value >= fo.minuto_fin_v.value)){
									alert('El minuto de incio debe ser menor que el de fin');
									return false;
								}
							}
						}
					}
				}
			}
		}
		if(fo.listado[2].checked == true){
			if(fo.semana_v.value == ""){
				alert('Rellene el intevalo de la semana');
				return false;
			}
		}
	}
//FIN Comprueba los campos y la fecha para realizar las sentencias SQL

//INICIO Fechas   --------------

	ahora = new Date();
	ahoraDay = ahora.getDate();
	ahoraMonth = ahora.getMonth();
	ahoraYear = ahora.getYear();

	if (ahoraYear < 2000){
		ahoraYear += 1900;
	}
	function cuantosDias(mes, anyo){
		var cuantosDias = 31;
		if (mes == "Abril" || mes == "Junio" || mes == "Septiembre" || mes == "Noviembre"){
			cuantosDias = 30;
		}
		if (mes == "Febrero" && (anyo/4) != Math.floor(anyo/4)){
			cuantosDias = 28;
		}
		if (mes == "Febrero" && (anyo/4) == Math.floor(anyo/4)){
			cuantosDias = 29;
		}
		return cuantosDias;
	}

	function asignaDias(dia,mes,anyo){
		comboDias = document.getElementById(dia);
		comboMeses = document.getElementById(mes);
		comboAnyos = document.getElementById(anyo);
		Month = comboMeses[comboMeses.selectedIndex].text;
		Year = comboAnyos[comboAnyos.selectedIndex].text;
		
		diasEnMes = cuantosDias(Month, Year);
		diasAhora = comboDias.length;
		if (diasAhora > diasEnMes){
			for (i=0; i<(diasAhora-diasEnMes); i++){
				comboDias.options[comboDias.options.length - 1] = null;
			}
		}
		if (diasEnMes > diasAhora){
			for (i=0; i<(diasEnMes-diasAhora); i++){
				sumaOpcion = new Option(comboDias.options.length + 1);
				comboDias.options[comboDias.options.length]=sumaOpcion;
			}
		}
		if (comboDias.selectedIndex < 0){
			comboDias.selectedIndex = 0;
		}
	}
//FIN Fechas


</script>

<?php
//session_start();
//INICIO Conexion a base de datos
$db = mysql_pconnect('localhost','root','');
	if (!$db){
		echo ("Error al acceder a la base de datos, intentelo mas tarde");
		exit();
	}
mysql_select_db("scies");
//FIN Conexion a base de datos

if($_GET['seman']){
	$_SESSION['sema']=$_GET['seman'];
	$_SESSION['indic']=$_GET['indic'];
}
if(isset($mess)){
	$_SESSION['mes']=$mess;
}
if(isset($anyio)){
	$_SESSION['anio']=$anyio;
}

//INICIO Listado de Centros
$sql_centros='SELECT * FROM centro';			

$result_centros=mysql_query($sql_centros);
$nume_centros=mysql_num_rows($result_centros);
//FIN Listado de Centros

    /*$arrayseleccionados=$_REQUEST['list2'];
    foreach ($arrayseleccionados as $par){
        echo "$par\n";  }*/      



   

if($_GET['instalacion']!=""){
	$_SESSION['instalacion']=$_GET['instalacion'];
	//$_SESSION['instalacion']=$instalacion;
}
if($_GET['listad']!=""){
	$_SESSION['listado']=$_GET['listad'];
}


//INICIO Listado de parametros dependiendo del centro
if($_GET['ndis']!=0 || $_GET['nsel']!=0 || $_GET['instalacion']!=""){
	$sql_parametros="select * from parametro inner join sonda on parametro.Id_Parametro=sonda.Id_Parametro where sonda.Id_Centro=".$_SESSION['instalacion'];
	//echo $sql_parametros."<br>";
	
	//$nume_parametros=mysql_num_rows($result_parametros);


//FIN Listado de parametros dependiendo del centro
//echo $_SESSION['listado'];

	$result_parametros=mysql_query($sql_parametros);
	@$nume_parametros=mysql_num_rows($result_parametros);
	
	$_SESSION['ndis']=$_GET['ndis'];
	$_SESSION['nsel']=$_GET['nsel'];

	for($i=0;$i<$nume_parametros;$i++){
		$parametros = MYSQL_FETCH_ARRAY($result_parametros);
		//echo $dis.''.$i."<br>";
	
		for($f=0;$f<$nume_parametros;$f++){
			$_SESSION['disp'][$f]=$_GET['dis'.$f];
			if($parametros['Id_Parametro']==$_SESSION['disp'][$f]){
				$_SESSION['disp']['pa'][$f]=$parametros['Nombre_Param'];
				//echo $_SESSION['disp'][$f].'-'.$_SESSION['disp']['pa'][$f].' ';
			}
		}
		for($f=0;$f<$nume_parametros;$f++){
			//echo $dis.''.$i."<br>";
			$_SESSION['sele'][$f]=$_GET['sel'.$f];
			if($parametros['Id_Parametro']==$_SESSION['sele'][$f]){
				$_SESSION['sele']['pa'][$f]=$parametros['Nombre_Param'];
				//echo $_SESSION['sele'][$f].'-'.$_SESSION['sele']['pa'][$f].' ';
			}
		}
	}
}
?>

<form name=fo>
<div class="pagina">
<div class="listado1" >
			<div class="listado" width="100%">	
				<div class="titulo1">Criterios</div>					
				<table border="0" class="opcion_triple" width="100%">		
					<thead>						
						<tr>						
							<th class="titulo2" colspan="2">Instalaciones</th>
						</tr>
					</thead>				
					<thead>						
						<tr>
							<th class="titulo3">Centro</th>
							<th class="titulo3_centro">
								<SELECT name='centro' style="width:300px;" align="absmiddle" onchange='cargar_parametros(this.form.centro.value);'>
									<OPTION></option>
										<?php
											for ($i = 0; $i < $nume_centros; $i++) {
			  									$centros = MYSQL_FETCH_ARRAY($result_centros);
												if( $_SESSION['instalacion'] == $centros['Id_Centro']){
										?>
													<OPTION VALUE='<?php echo $centros['Id_Centro']; ?>' selected>
														<?php echo $centros['Nombre']; ?>
													</option>
										<?php
												}else{
										?>
													<OPTION VALUE='<?php echo $centros['Id_Centro']; ?>'>
														<?php echo $centros['Nombre']; ?>
													</option>
										<?php
												}
											}			
										?>
								</SELECT>
							</th>
						</tr>
					</thead>
				</table>					
			</div>		
			<div class="listado" width="100%">
				<table border="0" class="opcion_triple"  cellpadding="0" cellspacing="0">		
					<thead>						
						<tr>						
							<th class="titulo2"  colspan="3">Parámetros</th>
						</tr>
					</thead>	
					<thead>	
						<tr>
							<th class="titulo3_centro">
											
									<thead>						
									<tr>
										<th class="titulo3" style="text-indent:0px;">Disponibles</th>
										<th class="titulo3">&nbsp;</th>
										<th class="titulo3" style="text-indent:0px;">Seleccionados</th>
									</tr>
									<tr>
										<th class="opcion_fondo">
											<select name="list1" size="5" style="width:160px;">
												<?php
													if($_SESSION['ndis']!=0 || $_SESSION['nsel']!=0){
														for ($i = 0; $i<$_SESSION['ndis']; $i++){
												?>
												<OPTION VALUE='<?php echo $_SESSION['disp'][$i]; ?>'>
													<?php echo $_SESSION['disp']['pa'][$i]; ?>
												</option>
												<?php
														}
													}else{
														$result_parametros=mysql_query($sql_parametros);
														for ($i = 0; $i < $nume_parametros; $i++) {
		  													$parametros = MYSQL_FETCH_ARRAY($result_parametros);
												?>
												<OPTION VALUE='<?php echo $parametros['Id_Parametro']; ?>'>
													<?php echo $parametros['Nombre_Param']; ?>
												</option>
												<?php
														}
													}
												?>
											</select>
										</th>
										<th>
											<br>
											<input type="button" class="boton_Small" value="  >>  " onclick="moveall(this.form.list1,this.form.list2)" name="B1">
											<br>
											<input type="button" class="boton_Small" value="   >   " onclick="move(this.form.list1,this.form.list2)" name="B1">
											<br>
											<input type="button" class="boton_Small" value="   <   " onclick="move(this.form.list2,this.form.list1)" name="B2">
											<br>
											<input type="button" class="boton_Small" value="  <<  " onclick="moveall(this.form.list2,this.form.list1)" name="B2">
										</th>
										<th>
											<select name="list2" size="5" style="width:158px;">
												<?php
													if($_SESSION['nsel']!=0){
														for ($i = 0; $i < $_SESSION['nsel']; $i++){
												?>
												<OPTION VALUE='<?php echo $_SESSION['sele'][$i]; ?>'>
													<?php echo $_SESSION['sele']['pa'][$i]; ?>
												</option>
												<?php
														}
													}
												?>
											</select>
										</th>
									</tr>
									</thead>
													
							</th>
						</tr>
					</thead>	
				</table>
			</div>							
			<div class="listado" >		
				<table border="0" class="opcion_triple">			
					<thead>						
					<tr>						
						<th class="titulo2">Fechas</th>
					</tr>
					</thead>
					<thead>	
					<tr>
						<table border="0" class="opcion_triple" >			
							<thead>						
							<tr>
								<th class="titulo3" style="text-align:left;">
									<?php 
										if($_SESSION['listado']=="personalizado" || !isset($_SESSION['listado'])){
											echo '<INPUT TYPE="radio" name="listado" value="personalizado" onClick="cargar_listado(\'personalizado\',this.form.list1,this.form.list2);" onselec="cargar_listado(\'personalizado\',this.form.list1,this.form.list2);" checked>Personalizado';
											$_SESSION['listado']="personalizado";
										}else{
											echo '<INPUT TYPE="radio" name="listado" value="personalizado" onClick="cargar_listado(\'personalizado\',this.form.list1,this.form.list2);" onselec="cargar_listado(\'personalizado\',this.form.list1,this.form.list2);">Personalizado';
										}
									?>
								</th>
								<th class="titulo3" style="text-align:left;">
									<?php 
										if($_SESSION['listado']=="diario"){
											echo '<INPUT TYPE="radio" name="listado" value="diario" onClick="cargar_listado(\'diario\',this.form.list1,this.form.list2);" onselec="cargar_listado(\'diario\',this.form.list1,this.form.list2);" checked>Diario';
										}else{
											echo '<INPUT TYPE="radio" name="listado" value="diario" onClick="cargar_listado(\'diario\',this.form.list1,this.form.list2);" onselec="cargar_listado(\'diario\',this.form.list1,this.form.list2);">Diario';
										}
									?>
								</th>	
								<th class="titulo3" style="text-align:left;">
									<?php 
										if($_SESSION['listado']=="semanal"){
											echo '<INPUT TYPE="radio" name="listado" value="semanal"	onClick="cargar_listado(\'semanal\',this.form.list1,this.form.list2);" onselec="cargar_listado(\'semanal\',this.form.list1,this.form.list2);" checked>Semanal';
										}else{
											echo '<INPUT TYPE="radio" name="listado" value="semanal" onClick="cargar_listado(\'semanal\',this.form.list1,this.form.list2);" onselec="cargar_listado(\'semanal\',this.form.list1,this.form.list2);">Semanal';
										}
									?>
								</th>	
								</tr>
								<tr>
								<th class="titulo3" style="text-align:left;">
									<?php 
										if($_SESSION['listado']=="mensual"){
											echo '<INPUT TYPE="radio" name="listado" value="mensual" onClick="cargar_listado(\'mensual\',this.form.list1,this.form.list2);" onselec="cargar_listado(\'mensual\',this.form.list1,this.form.list2);" checked>Mensual';
										}else{
											echo '<INPUT TYPE="radio" name="listado" value="mensual" onClick="cargar_listado(\'mensual\',this.form.list1,this.form.list2);" onselec="cargar_listado(\'mensual\',this.form.list1,this.form.list2);">Mensual';
										}
									?>
								</th>
								
								<th class="titulo3" colspan="2" style="text-align:left;">
									<?php 
										if($_SESSION['listado']=="anual"){
											echo '<INPUT TYPE="radio" name="listado" value="anual" onClick="cargar_listado(\'anual\',this.form.list1,this.form.list2);" onselec="cargar_listado(\'anual\',this.form.list1,this.form.list2);" checked>Anual';
										}else{
											echo '<INPUT TYPE="radio" name="listado" value="anual" onClick="cargar_listado(\'anual\',this.form.list1,this.form.list2);" onselec="cargar_listado(\'anual\',this.form.list1,this.form.list2);">Anual';
										}
									?>
								</th>
							</tr>
							</thead>
							<thead>						
							<tr>
								<div>
								<?php 
									if($_SESSION['listado']=="personalizado" || !isset($_SESSION['listado'])){
										include("personal.inc.php");
									}
									if($_SESSION['listado']=="diario"){
										include("diario.inc.php");
									}
								
									if($_SESSION['listado']=="semanal"){
										include("semanal.inc.php");
									}
								
									if($_SESSION['listado']=="mensual"){
										include("mensual.inc.php");
									}
				
									if($_SESSION['listado']=="anual"){
										include("anual.inc.php");
									}
								?>
								</div>
							</tr>
							</thead>
						</table>																			
					</tr>					
					</thead>
				</table>
			</div>			
			<div class="opcion_boton" >									
				<table width="100%">
				<thead>	
					<tr>
						<th class="opcion_boton">
							<!--<INPUT TYPE="button" NAME="Búsqueda" VALUE="Búsqueda" class="boton_ton" onClick=" window.location.href='listado_registros.php';">-->	
						<?php 
									if($_SESSION['listado']=="personalizado" || !isset($_SESSION['listado'])){
										echo '<INPUT TYPE="button" NAME="Búsqueda" VALUE="Búsqueda" class="boton_big" onclick="listar_personalizado(this.form.list1, this.form.list2, this.form.dia_inicio_v.value, this.form.mes_inicio_v.value, this.form.anio_inicio_v.value, this.form.dia_fin_v.value, this.form.mes_fin_v.value, this.form.anio_fin_v.value, this.form.hora_inicio_v.value, this.form.minuto_inicio_v.value, this.form.hora_fin_v.value, this.form.minuto_fin_v.value);";>';
									}
									if($_SESSION['listado']=="diario"){
										echo '<INPUT TYPE="button" NAME="Búsqueda" VALUE="Búsqueda" class="boton_big" onclick="listar_diario(this.form.list1, this.form.list2, this.form.dia_inicio_v.value, this.form.mes_inicio_v.value, this.form.anio_inicio_v.value);";>';
									}
								
									if($_SESSION['listado']=="semanal"){
										echo '<INPUT TYPE="button" NAME="Búsqueda" VALUE="Búsqueda" class="boton_big" onclick="listar_semanal(this.form.list1, this.form.list2, this.form.semana_v.value, this.form.mes_inicio_v.value, this.form.anio_inicio_v.value);";>';
									}
								
									if($_SESSION['listado']=="mensual"){
										echo '<INPUT TYPE="button" NAME="Búsqueda" VALUE="Búsqueda" class="boton_big" onclick="listar_mensual(this.form.list1, this.form.list2, this.form.mes_inicio_v.value, this.form.anio_inicio_v.value);";>';
									}
				
									if($_SESSION['listado']=="anual"){
										echo '<INPUT TYPE="button" NAME="Búsqueda" VALUE="Búsqueda" class="boton_big" onclick="listar_anual(this.form.list1, this.form.list2, this.form.anio_inicio_v.value);";>';
									}
								?>
						</th>
					</tr>
				</thead>
				</table>		
			</div>

</div>
</div>
</form>




