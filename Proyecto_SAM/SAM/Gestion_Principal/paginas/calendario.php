
<!-- CALENDARIO!!!!!!!!!!!!!!!!!! -->
				
				
<div class='cale_container' id='cale_container' style='display:block;'>



<SCRIPT type="text/javascript" src="./paginas/capas.js"></script>
<SCRIPT language="JavaScript">	
	<?php 
SESSION_START();
	
	if(isset($_GET['fecha_cal']) && $_GET['fecha_cal']!=""){
	 	$fecha_partida=split("-",$_GET['fecha_cal']);
	 	echo "fecha_selec = new Date(20".substr($fecha_partida[2],2).",".(intval($fecha_partida[1])-1).",".$fecha_partida[0].");";
		if (isset($_SESSION['gdh'])){			
			$_SESSION['gdh']['dia'] = $fecha_partida[0];
			$_SESSION['gdh']['mes'] = $fecha_partida[1];
			$_SESSION['gdh']['anio'] = "20".substr($fecha_partida[2],2);
		}
	}else{
		if(isset($_POST['fecha_cal']) && $_POST['fecha_cal']!=""){
			$fecha_partida=split("-",$_POST['fecha_cal']);
			echo "fecha_selec = new Date(20".substr($fecha_partida[2],2).",".(intval($fecha_partida[1])-1).",".$fecha_partida[0].");";
			if (isset($_SESSION['gdh'])){
				$_SESSION['gdh']['dia'] = $fecha_partida[0];
				$_SESSION['gdh']['mes'] = $fecha_partida[1];
				$_SESSION['gdh']['anio'] = "20".substr($fecha_partida[2],2);
			}
		}else{
			if (isset($_SESSION['gdh'])){
				
				echo "fecha_selec = new Date(20".substr($_SESSION['gdh']['anio'],2).",".(intval($_SESSION['gdh']['mes'])-1).",".$_SESSION['gdh']['dia'].");";

			}else{
				echo "fecha_selec = new Date();";
			}
		}
	}
	

	 ?>
	
	

	dia_selec = fecha_selec.getDate();
	mes_selec = fecha_selec.getMonth();
	anio_selec = fecha_selec.getYear();
	
	//alert ("FECHA_SELECT=="+dia_selec+"-"+mes_selec+"-"+anio_selec);
	ahora = new Date();
	//ahora.setDate(ahora.getDate()+7);
	ahoraDay = ahora.getDate();
	ahoraMonth = ahora.getMonth();
	ahoraYear = ahora.getYear();

	// Nestcape Navigator 4x cuenta el anyo a partir de 1900, por lo que es necesario 
	// sumarle esa cantidad para obtener el anyo actual adecuadamente
	if (ahoraYear < 2000)
		ahoraYear += 1900;


	function ver_dia(dia) {
	  	window.opener.formulario.gdh_dia_calendario.value = dia + "*" + formulario.s_mes.value + "*" + formulario.s_anio.value;
	  	window.opener.formulario.submit();
	}	
	
	function cambiar_hoy(){
		dia = new Date();
		cambiar_dia(dia.getDate(),dia.getMonth()+1,dia.getYear());
	}
	
	
	calendario(dia_selec, mes_selec, anio_selec);
	
	function calendario(dia, mes, anio){
		texto = '<span class="calendario_mes">';
		
		
		//Meto el boton HOY
		texto = texto + " <div style='margin-bottom:15px;'><A href='#' onClick='cambiar_hoy()'><IMG src='../imagenes/skins-<?php echo $_SESSION['aspecto'];?>/boton_hoy_";
		
		var hoy=new Date();
		if (dia_selec == hoy.getDate() && mes_selec == hoy.getMonth() && anio == hoy.getYear()){
			texto = texto + "inactivo.gif' border=0></A> <br><br>";
		}else{
			texto = texto + "activo.gif' border=0></A> <br><br>";
		}

		
		texto = texto + '</div><SELECT name="s_mes" onChange=calendario("'+dia+'",this.value,"'+anio+'")>';
		
		meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
		for (i = 0; i <= 11; i++){
			if(i < 10)
				esc = "0"+i;
			else
				esc = i;
			if(esc == mes)
				texto = texto + '<OPTION value="' + esc + '" selected>'+meses[i];
			else
				texto = texto + '<OPTION value="' + esc + '">'+meses[i];		  	
		}
	
		texto = texto + '</select>';
		
		
		texto = texto + '<select name="s_anio" onChange=calendario("'+dia+'","'+mes+'",this.value)>';
		for (i = 2006; i <= ahoraYear + 10; i++) {
			if (i == anio) 
				texto = texto + '<OPTION value="' + i + '" selected>'+i;   
			else
				texto = texto + '<OPTION value="' + i + '">'+i;   		  	
		}
	 
		texto = texto + '</select>';
		//texto = texto + '<a href="#" onClick=ver_calendario("1") title="Cerrar el Calendario" OnMouseOver="window.status=\'Cerrar el Calendario\' ; return true" onMouseOut="window.status=\'Listo\' ; return true">';
		//texto = texto + '<font color="#FFFFFF">X</font></a>';
		
		
		
		
		texto = texto + '</span><span class="calendario_dias">';
		
		
		texto = texto + '<table cellpadding="0" cellspacing="0">';
		//texto = texto + '<tbody class="scrollContentCalendar">';
		//???????????????????'mes = mes-1;
		fecha = new Date(anio,mes,1);
		dia_semana_ini = fecha.getDay();
		if(dia_semana_ini == 0)
			dia_semana_ini = 7;
		mes_actual = mes;
		mes_comparar = mes;
		pasar_semana = 1;

		texto = texto + '<tr>';
	
		for (i = 0; i < dia_semana_ini - 1; i++) {
			pasar_semana++;
			texto = texto + '<td>&nbsp;</td>';
		}
		con = 1;
		
		semana = 0;
		//alert ("SELECT("+dia_selec+"-"+mes_selec+"-"+anio_selec+") == TEMP("+dia+"-"+mes+"-"+anio+")");
		while(mes_actual == mes_comparar) {
			mes_env = fecha.getMonth();
			//if(mes_env < 10)
			//	mes_env = "0"+mes_env;
			//alert ("SELECT("+dia_selec+"-"+mes_selec+"-"+anio_selec+") == TEMP("+con+"-"+mes+"-"+anio+")");
			if(mes == mes_selec && con == dia_selec && anio == anio_selec){
			// alert ("NI DE COÑA!!!!!!!!!!!!!!!!!!!!!!!!!");
				if (pasar_semana > 5){
					texto = texto + '<td id="dia_selected" style="color:#007980;">'+fecha.getDate()+'</td>';
				}else{
					texto = texto + '<td id="dia_selected">'+fecha.getDate()+'</td>';
				}
				//alert ("dia seleccionado = "+dia_selec+"-"+mes_selec+"-"+anio_selec);
			}else{
				//alert ("-"+mes_env+"-");
				//var prrr=parseInt(mes_env);
				//alert (prrr);//POR QUEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
				if (pasar_semana > 5){
					texto = texto + '<td style="color:#A9473D;" onclick=cambiar_dia("' + con + '","' + (parseInt(mes_env)+1) + '","' + anio + '")>'+fecha.getDate()+'</td>';
				}else{
					texto = texto + '<td onclick=cambiar_dia("'+con+'","'+(parseInt(mes_env)+1)+'","'+anio+'")>'+fecha.getDate()+'</td>';
				}
			}
			pasar_semana++;
			if (pasar_semana == 8) {
				pasar_semana = 1;
				semana++;
				texto = texto + '</tr><tr>';
			}
			con++;
			fecha = new Date(anio,mes,con);
			mes_comparar = fecha.getMonth();
		}
		for (i = pasar_semana; i <= 7; i++) {
			pasar_semana++;
			texto = texto + '<td>&nbsp;</td>';
		}
		semana++;
		texto = texto + '</tr>';
		if(semana < 6){
			texto = texto + '<td colspan="7">&nbsp;</td>';
		}
		texto = texto + '</tr></table></span>';
		//alert(texto);
		xInnerHtml('cale_container',texto);
		
	}
	
</SCRIPT>


</div>
<!-- FIN del CANEDARIO -->