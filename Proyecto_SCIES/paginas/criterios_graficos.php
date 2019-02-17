<HTML>
<head>
	<LINK rel="stylesheet" href="../css/listado.css">
	<LINK rel="stylesheet" href="../css/comun.css">
	<style type="text/css">
		<!--
		.grafico_selec {
			border-top-style: none;
			border-right-style: solid;
			border-bottom-style: solid;
			border-left-style: none;
			border-top-width: medium;
			border-right-width: medium;
			border-bottom-width: medium;
			border-left-width: medium;
			border-top-color: #999999;
			border-right-color: #999999;
			border-bottom-color: #999999;
			border-left-color: #999999;
		}
		.grafico_no_selec {
			border-top-style: none;
			border-right-style: none;
			border-bottom-style: none;
			border-left-style: none;
		}
		-->
</style>

	<title>Generar Gr&aacute;ficas</title>
	<script>
		var mes_seleccionado = -1;
		var dia_seleccionado = -1;
		function cambiar_fecha(valor){
			if(valor == 1 || valor == 2 || valor == 3 || valor == 4){
				document.getElementById("dia_inicio").disabled = false;
				document.getElementById("mes_inicio").disabled = false;
				if(dia_seleccionado != -1)
					document.getElementById("dia_inicio").value = dia_seleccionado;
				if(mes_seleccionado != -1)
					document.getElementById("mes_inicio").value = mes_seleccionado;
			}else if(valor == 5 || valor == 6 || valor == 7){
				document.getElementById("dia_inicio").disabled = true;
				if(dia_seleccionado == -1)
					dia_seleccionado = document.getElementById("dia_inicio").value;
				document.getElementById("dia_inicio").value = "01";
				document.getElementById("mes_inicio").disabled = false;
				if(mes_seleccionado != -1)
					document.getElementById("mes_inicio").value = mes_seleccionado;
			}else{
				document.getElementById("dia_inicio").disabled = true;
				if(dia_seleccionado == -1)
					dia_seleccionado = document.getElementById("dia_inicio").value;
				document.getElementById("dia_inicio").value = "01";

				document.getElementById("mes_inicio").disabled = true;
				if(mes_seleccionado == -1)
					mes_seleccionado = document.getElementById("mes_inicio").value;
				document.getElementById("mes_inicio").value = "01";
			}
		}
		
		function cambiar_selec(num){
			document.getElementById("grafico").value = num;
			for(i = 1; i < 4; i++){
				document.getElementById("gra"+i).className = "grafico_no_selec";
			}
			document.getElementById("gra"+num).className = "grafico_selec";
		}		

		function comprobar(form){
			if(form.ins_seleccionadas.length == 0){
				alert("Debe seleccionar alguna instalación.");
			}else if(form.para_seleccionadas.length == 0){
				alert("Debe seleccionar algún parámetro.");
			}else{	
				ok = 0;
				selec = form.ins_seleccionadas.length * form.para_seleccionadas.length;
				if(document.getElementById("grafico").value == "1" && selec > 6){
					ok = 1;
					alert("Solo puede seleccionar 6 parámetros como máximo.");
				}else if(document.getElementById("grafico").value == "2" && selec > 3){
					ok = 1;
					alert("Solo puede seleccionar 4 parámetros como máximo.");
				}else if(document.getElementById("grafico").value == "3" && selec > 4){
					ok = 1;
					alert("Solo puede seleccionar 4 parámetros como máximo.");
				}
				if(ok == 0){
					for (var v = 0; v < form.ins_seleccionadas.length; v++){
						form.ins_seleccionadas.options[v].selected = true;
					}
					for (var v = 0; v < form.para_seleccionadas.length; v++){
						form.para_seleccionadas.options[v].selected = true;
					}
					form.submit();
				}
			}
		}

		function cargaSelectDias(){
			var mes = parseInt(document.getElementById('mes_inicio').selectedIndex);
			var anyo = parseInt(document.getElementById('anio_inicio').value);
			var dia = parseInt(document.getElementById('dia_inicio').value);

			var tamanyoSelectDias = parseInt(document.getElementById('dia_inicio').length); // Nº de options del <select> de los dias
			var diasMes = obtenerDiasMes(eval(mes + 1),anyo); // Nº de dias del mes seleccionado del año seleccionado

			var dif = eval(tamanyoSelectDias - diasMes);

			if(dif < 0){
				while(diasMes > document.getElementById('dia_inicio').length)
					document.getElementById('dia_inicio').options[(document.getElementById('dia_inicio').length)] = new Option(eval(parseInt(document.getElementById('dia_inicio').length)+1),eval(parseInt(document.getElementById('dia_inicio').length)+1));
			}else if(dif > 0){
				while(diasMes < document.getElementById('dia_inicio').length)
				document.getElementById('dia_inicio').options[(document.getElementById('dia_inicio').length)-1] = null;
				if(dia > diasMes)
					document.getElementById('dia_inicio').value=parseInt(document.getElementById('dia_inicio').length);
			}
		}

		/**
		* Funcion que devuelve el nº de dias de un mes de un determinado año
		*/
		function obtenerDiasMes(mes,anyo){
			if(mes==2){ // Si el mes es Febr
				if((anyo % 4 == 0 && anyo % 100 != 0) || anyo % 400 == 0) // Si es bisiesto
					return 29;
				else
					return 28;
			}else{
				if(mes == 11 || mes == 4 || mes == 6 || mes == 9) // Si el mes es Nov, Abr, Jun o Sept
					return 30;
				else
					return 31;
			}
		}
			
		function poner(formu,num,select,accion,todos){ //recibe num que será 1 si se ha hecho doble click en un tipo de habitación o 0 si se viene de hacer click en la flecha de añadir, en ese caso habrá que comprobar que había un tipo seleccionado; select que será 1 si es uno de los select de instalaciones o 2 si es uno de los select de parámetros; accion que será 1 si es poner o 2 si es quitar; y todos que será 1 cuando se ha hecho doble click en una de las fechas dobles o 0 si no.
            var temp1 = new Array();//guardará los values de los options del select de la izquierda que se han seleccionado
			var temp2 = new Array();//guardará los textos de los options del select de la izquierda que se han seleccionado
			var tempa = new Array();//guardará los values de los options del select de la izquierda que no se han seleccionado para añadir al select de la derecha
			var tempb = new Array();//guardará los textos de los options del select de la izquierda que no se han seleccionado para añadir al select de la derecha
			var current1 = 0;
			var current2 = 0;
			var y=0;
			var attribute;

			//le damos valores a attribute1 y attribute2
			if(select == 1){
                if(accion == 1){
			     attribute1 = formu.ins_disponibles;
			     attribute2 = formu.ins_seleccionadas;
                }
                else{
			     attribute2 = formu.ins_disponibles;
			     attribute1 = formu.ins_seleccionadas;
                }
            }
            else{
                if(accion == 1){
			     attribute1 = formu.para_disponibles;
			     attribute2 = formu.para_seleccionadas;
                }
                else{
			     attribute2 = formu.para_disponibles;
			     attribute1 = formu.para_seleccionadas;
                }
            }
			//se rellena un array con los antiguos valores

			if(num == 0){ //si se ha hecho click en la flecha  hay que comprobar que haya alguna opción seleccionada
				var seleccionado=0; //por defecto se pone la variable a cero
				for (var v = 0; v < attribute1.length; v++){
					//se recorre todo el array(formado por las opciones del select) para ver si hay alguna opción seleccionada
					if (attribute1.options[v].selected){
						seleccionado=1;//si hay alguna opción seleccionada se pone la variable a 1
					}
				}
			}
			else{ 
				seleccionado=1;
			}

			if(seleccionado == 1){
				for (var i = 0; i < attribute2.length; i++){
					y=current1++;
					temp1[y] = attribute2.options[i].value;
					tempa[y] = attribute2.options[i].text;
				}
				//asignar los nuevos valores a los arrays
				for (var i = 0; i < attribute1.length; i++){
                    if(todos == 0){
    					if (attribute1.options[i].selected){
    					   y=current1++;
    						temp1[y] = attribute1.options[i].value;//se guarda el valor de la opción seleccionada
    						tempa[y] = attribute1.options[i].text;//se guarda el texto de la opción seleccionada

    					}
    					else{
    						y=current2++;
    						temp2[y] = attribute1.options[i].value;
    						tempb[y] = attribute1.options[i].text;
    					}
					}
					else{
                        y=current1++;
					    temp1[y] = attribute1.options[i].value;//se guarda el valor de la opción seleccionada
    				    tempa[y] = attribute1.options[i].text;//se guarda el texto de la opción seleccionada
                    }
				}
				attribute2.length=0;
				//se generan las nuevas opciones
				for (var i = 0; i < temp1.length; i++){
					attribute2.options[i] = new Option();
					attribute2.options[i].value = temp1[i];
					attribute2.options[i].text =  tempa[i];
					attribute2.options[i].title =  tempa[i];
					attribute2.options[i].label =  tempa[i];
				}
			
				attribute1.length=0;
				for (var i = 0; i < temp2.length; i++){
					attribute1.options[i] = new Option();
					attribute1.options[i].value = temp2[i];
					attribute1.options[i].text =  tempb[i];
					attribute1.options[i].title = tempb[i];
					attribute1.options[i].label = tempb[i];
				}
				
				if(accion == 2){    //si se ha quitado de los seleccionados, entonces puede que los disponibles no estén ordenados
                    var temp_o1 = new Array();
                    var temp_oa = new Array();
                    if(select == 1){
    			     attribute_o = formu.ins_disponibles;
                    }
                    else{
    			     attribute_o = formu.para_disponibles;
                    }
                    
                    for (var i = 0; i < attribute_o.length; i++){   //recogo todos los option del select correspondiente
    					temp_o1[i] = attribute_o.options[i].value;
    					temp_oa[i] = attribute_o.options[i].text;
    				}
    				
                    var cambio;             //ordendo los array por el value
                    do{
                        cambio = 0;
                        for(var i = 1; i < temp_o1.length; i++){
                            if(temp_o1[i] < temp_o1[i-1]){
                                temp_1 = temp_o1[i-1];
                                temp_o1[i-1] = temp_o1[i];
                                temp_o1[i] = temp_1;

                                temp_a = temp_oa[i-1];
                                temp_oa[i-1] = temp_oa[i];
                                temp_oa[i] = temp_a;
                                cambio = 1;
                            }
                        }
                    }while(cambio == 1);
                    
                    attribute_o.length=0;   //vacio el select y lo relleno ordenado
    				for (var i = 0; i < temp_o1.length; i++){
    					attribute_o.options[i] = new Option();
    					attribute_o.options[i].value = temp_o1[i];
    					attribute_o.options[i].text = temp_oa[i];
						attribute_o.options[i].title = temp_oa[i];
						attribute_o.options[i].label = temp_oa[i];
    				}
                }

				if(select == 1){
					if(accion == 1){
						for(i = 0; i < formu.ins_seleccionadas.length; i++){
							cargar_parametros(formu.ins_seleccionadas.options[i].value);
						}
					}else if(accion == 2){
						for(i = 0; i < formu.ins_disponibles.length; i++){
							quitar_parametros(formu.ins_disponibles.options[i].value);
						}
					}
				}
				
			}
			else{ //si la variable seleccionado no es 1 se muestra el error
                if(select == 1)
                    mens = "Debe seleccionar alguna instalacción.";
                else
                    mens = "Debe seleccionar algun parámetro.";
				alert(mens);
            }
        }

		function cargar_parametros(centro){
			var cont = form1.para_disponibles.length;
			for(i = 0; i < parametros.length; i++){
				if(parametros[i][0] == centro){
					for(var p = 0; p < parametros[i][1].length; p++){
						var valor = parametros[i][1][p].split('*');
						if(valor[0] != ""){
							var enc = 0;
							for(var b = 0; b < form1.para_disponibles.length; b++){
								if(form1.para_disponibles.options[b].value == valor[0])
									enc = 1;
							}
							for(var b = 0; b < form1.para_seleccionadas.length; b++){
								if(form1.para_seleccionadas.options[b].value == valor[0])
									enc = 1;
							}
							if(enc == 0){
								var obj = form1.para_disponibles;

								obj.options[cont] = new Option();
								obj.options[cont].value = valor[0];
								obj.options[cont].text = valor[1];
								obj.options[cont].title = valor[1];
								obj.options[cont].label = valor[1];
								cont++;

								temp_o1 = new Array();
								temp_oa = new Array();
								for (var o = 0; o < obj.length; o++){   //recogo todos los option del select correspondiente
									temp_o1[o] = obj.options[o].value;
									temp_oa[o] = obj.options[o].text;
								}
								var cambio;             //ordendo los array por el value
								do{
									cambio = 0;
									for(var o = 1; o < temp_o1.length; o++){
										if(temp_o1[o] < temp_o1[o-1]){
											temp_1 = temp_o1[o-1];
											temp_o1[o-1] = temp_o1[o];
											temp_o1[o] = temp_1;

											temp_a = temp_oa[o-1];
											temp_oa[o-1] = temp_oa[o];
											temp_oa[o] = temp_a;
											cambio = 1;
										}
									}
								}while(cambio == 1);
								
								obj.length=0;   //vacio el select y lo relleno ordenado
								for (var o = 0; o < temp_o1.length; o++){
									obj.options[o] = new Option();
									obj.options[o].value = temp_o1[o];
									obj.options[o].text = temp_oa[o];
									obj.options[o].title = temp_oa[o];
									obj.options[o].label = temp_oa[o];
								}
							}
						}
					}
				}
			}
		}

		function quitar_parametros(centro){
			for(i = 0; i < parametros.length; i++){
				if(parametros[i][0] == centro){	//busco los parametros del centro que se deselecciona y los recorro
					for(p = 0; p < parametros[i][1].length; p++){	
						valor = parametros[i][1][p].split('*');	
						if(valor[0] != ""){
							enc = 0;

							for(b = 0; b < form1.ins_seleccionadas.length; b++){ //recorro todas los centros seleccionados
								for(i1 = 0; i1 < parametros.length; i1++){
									if(parametros[i1][0] == form1.ins_seleccionadas.options[b].value){	//busco los parametros del centro y los recorro
										for(p1 = 0; p1 < parametros[i1][1].length; p1++){
											if(parametros[i1][1][p1] == parametros[i][1][p]){
												enc = 1;
											}
										}
									}
								}
							}

							if(enc == 0){
								var obj;
								for(b = 0; b < form1.para_disponibles.length; b++){
									if(form1.para_disponibles.options[b].value == valor[0])
										obj = form1.para_disponibles;
								}
								for(b = 0; b < form1.para_seleccionadas.length; b++){
									if(form1.para_seleccionadas.options[b].value == valor[0])
										obj = form1.para_seleccionadas;
								}
								if(obj){
									temp_o1 = new Array();
									temp_oa = new Array();
									con = 0;
									for (var o = 0; o < obj.length; o++){   //recogo todos los option del select correspondiente
										if(obj.options[o].value != valor[0]){
											temp_o1[con] = obj.options[o].value;
											temp_oa[con] = obj.options[o].text;
											con ++;
										}
									}
									obj.length = 0;
									for (var o = 0; o < temp_o1.length; o++){
										obj.options[o] = new Option();
										obj.options[o].value = temp_o1[o];
										obj.options[o].text = temp_oa[o];
										obj.options[o].title = temp_oa[o];
										obj.options[o].label = temp_oa[o];
									}
								}
							}
						}
					}
				}
			}
		}
        
		function pos_left(anc){
			return (screen.width - anc) / 2;
        }
        function pos_top(alt){
			return (screen.height - alt) / 2;
        }

		function seleccionar_imagen(valor){
			if(valor == 0){
				document.getElementById("fondo_img").value = "";
			}else{
				for (var i = 0; i < form1.ins_seleccionadas.length; i++){
						document.getElementById("centros").value=document.getElementById("centros").value+form1.ins_seleccionadas.options[i].value+"-";
					}
					
				if(document.getElementById("centros").value == ""){
					alert("Debe seleccionar alguna instalacción.");
					document.form1.fondo[0].checked = true;
				}else{
					id_cen = form1.ins_seleccionadas.options[0].value;
					ven = window.open("paginas/seleccionar_fondo_grafico.php?Id_Centro="+id_cen+"&grafico=form1&centros="+document.getElementById("centros").value,"GALERIA","location:no, height=530px, width=500px, top=" + pos_top(530) + ", left=" + pos_left(500) + ", status:NO");
					document.getElementById("centros").value = "";
				}
			}
		}

	</script>
</head>
<body>


<?
	@ $db = mysql_pconnect("localhost" , "root" , "");
	if (!$db){
		echo "Error : No se ha podido conectar a la base de datos";
		exit;
	}
	mysql_select_db("SCIES");
?>
<form name="form1" id="form1" method="post" action="principal.php?pag=crear_imagen_grafico_lineas_fecha.php">
<input type="hidden" name="centros" id="centros" >
<div class="pagina">
<div class="listado1">
<div class="titulo1">Criterios</div>

	<table class="opcion_triple" border="0">
		<thead>						
			<tr>						
				<th class="titulo2" colspan="2">Instalaciones</th>													
			</tr>
		</thead>
		<thead>	
			<table border="0" class="opcion_triple" cellpadding="0" cellspacing="0">			
				<thead>						
				<tr>
					<th class="titulo3" style="text-indent:0px;">Disponibles</td>
					<th class="titulo3"></th>
					<th class="titulo3" style="text-indent:0px;">Seleccionados</td>
				</tr>
				</thead>
				<thead>
				<tr>
					<th>
					<select multiple size="5" style="width:250px" name="ins_disponibles[]" id="ins_disponibles" onDblClick="poner(this.form,1,1,1,0)">
						<?
							$sql = "select * from centro";
							$res = mysql_query($sql);
							$num = mysql_num_rows($res);
							?>
							<script>
								var parametros = new Array(<?=$num?>);
							</script>
							<?
							for ($i = 0; $i < $num; $i++){
								$fila = mysql_fetch_array($res);
						?>
								<option value="<?=$fila['Id_Centro']?>" label="<?=$fila['Nombre']?>" title="<?=$fila['Nombre']?>"><?=$fila['Nombre']?></option>
						<?

								$sql = "SELECT parametro.* FROM parametro inner join sonda on (parametro.id_parametro = sonda.id_parametro) where sonda.id_centro = '" . $fila['Id_Centro'] . "'";
								$res1 = mysql_query($sql);
								$num1 = mysql_num_rows($res1);
								?>
								<script>
									parametros[<?=$i?>] = new Array(2);
									parametros[<?=$i?>][0] = "<?=$fila['Id_Centro']?>";
									parametros[<?=$i?>][1] = new Array(<?=$num1?>);
								</script>
								<?
								for ($i1 = 0; $i1 < $num1; $i1++){
									$fila1 = mysql_fetch_array($res1);
									?>
									<script>
										parametros[<?=$i?>][1][<?=$i1?>] = "<?=$fila1['Id_Parametro'].'*'.$fila1['Nombre_Param']?>";
									</script>
									<?
								}

							}
						?>
						</select>
					</th>
					<th>
						<input type="button" value=">>" onClick="poner(this.form,1,1,1,1);" class="boton_small" title="Seleccionar todas las Instalaciones"><br>
						<input type="button" value=">" onClick="poner(this.form,0,1,1,0);" class="boton_small" title="Seleccionar la Instalación"><br>
						<input type="button" value="<" onClick="poner(this.form,0,1,2,0);" class="boton_small" title="Deseleccionar la Instalación"><br>
						<input type="button" value="<<" onClick="poner(this.form,1,1,2,1);" class="boton_small" title="Deseleccionar todas las Instalaciones">
					</th>
					<th>
						<select multiple size="5" style="width:250px" name="ins_seleccionadas[]" id="ins_seleccionadas" onDblClick="poner(this.form,1,1,2,0);">
			
						</select>
					</th>
				</tr>
				</thead>
			</table>
		</thead>	
	</table>

	<table border="0" class="opcion_triple">
		<thead>						
			<tr>						
				<th class="titulo2" colspan="2">Parámetros</th>													
			</tr>
		</thead>
		<thead>	

			<table border="0" class="opcion_triple" cellpadding="0" cellspacing="0">			
				<thead>						
				<tr>
					<th class="titulo3" style="text-indent:0px;">Disponibles</td>
					<th class="titulo3"></th>
					<th class="titulo3" style="text-indent:0px;">Seleccionados</td>
				</tr>
				</thead>
				<thead>
				<tr>
					<th>
						<select multiple size="5" style="width:250px" name="para_disponibles[]" id="para_disponibles" onDblClick="poner(this.form,1,2,1,0)">
						</select>
					</th>
					<th>
						<input type="button" value=">>" onClick="poner(this.form,1,2,1,1);" class="boton_small" title="Seleccionar todos los Parámetros"><br>
						<input type="button" value=">" onClick="poner(this.form,0,2,1,0);" class="boton_small" title="Seleccionar el Parámetro"><br>
						<input type="button" value="<" onClick="poner(this.form,0,2,2,0);" class="boton_small" title="Deseleccionar el Parámetro"><br>
						<input type="button" value="<<" onClick="poner(this.form,1,2,2,1);" class="boton_small" title="Deseleccionar todos los Parámetros">
					</th>
					<th>
						<select multiple size="5" style="width:250px" name="para_seleccionadas[]" id="para_seleccionadas" onDblClick="poner(this.form,1,2,2,0);">

						</select>
					</th>
				</tr>
				</thead>
			</table>				
		</thead>
	</table>
	
	<input type="hidden" name="grafico" id="grafico" value="1">
	<table border="0" class="opcion_triple">
		<thead>						
			<tr>						
				<th class="titulo2">Tipo de Gr&aacute;fico</th>													
			</tr>
		</thead>
		<thead>	
			<table border="0" class="opcion_triple" cellpadding="0" cellspacing="0">			
				<thead>						
				<tr class="titulo3" style="text-align:left;">
					<th width="33%"><img name="gra1" id="gra1" style="cursor:pointer;" class="grafico_selec" src="./imagenes/graficos/grafico1.jpg" alt="Gr&aacute;fico lineal" align="absmiddle" onclick="cambiar_selec(1)"></th>
					<th><img name="gra2" id="gra2" style="cursor:pointer;" class="grafico_no_selec" src="./imagenes/graficos/grafico2.jpg" alt="Gr&aacute;fico con medias" align="absmiddle" onclick="cambiar_selec(2)"></th>
					<th width="33%"><img name="gra3" id="gra3" style="cursor:pointer;" class="grafico_no_selec" src="./imagenes/graficos/grafico3.jpg" alt="Gr&aacute;fico barras" align="absmiddle" onclick="cambiar_selec(3)"></th>
				</tr>
				</thead>
			</table>				
		</thead>
	</table>

	<table border="0" class="opcion_triple">
		<thead>						
			<tr>						
				<th class="titulo2">Periodicidad</th>													
			</tr>
		</thead>
		<thead>	
			<table border="0" class="opcion_triple" cellpadding="0" cellspacing="0">			
				<thead>										
					<tr class="titulo3" style="text-align:left;"> 
						  <th><input type="radio" name="rango" onclick="cambiar_fecha(1)" value="1" checked>1 d&iacute;a</th>
						  <th><input type="radio" name="rango" onclick="cambiar_fecha(2)" value="2">3 d&iacute;as</th>
						  <th><input type="radio" name="rango" onclick="cambiar_fecha(3)" value="3">1 semana</th>
						  <th><input type="radio" name="rango" onclick="cambiar_fecha(4)" value="4">2 semanas</th>
					</tr>
					<tr class="titulo3" style="text-align:left;"> 
						  <th><input type="radio" name="rango" onclick="cambiar_fecha(5)" value="5">1 mes</th>
						  <th><input type="radio" name="rango" onclick="cambiar_fecha(6)" value="6">3 meses</th>
						  <th><input type="radio" name="rango" onclick="cambiar_fecha(7)" value="7">6 meses</th>
						  <th><input type="radio" name="rango" onclick="cambiar_fecha(8)" value="8">1 a&ntilde;o</th>
					</tr>
				</thead>
			</table>				
		</thead>
	</table>

	
	<table border="0" class="opcion_triple">
		<thead>						
			<tr>						
				<th class="titulo2" width="50%">Fecha</th>
				<th class="titulo2" style="text-indent:10px;">Fondo</th>
			</tr>
		</thead>
		<thead>	
			<table border="0" class="opcion_triple" cellpadding="0" cellspacing="0">			
				<thead>						
				<tr>						
					<th class="titulo3" style="text-align:left;" width="50%">
					<input type="hidden" name="fondo_img" id="fondo_img" value="">
						<SELECT name="dia_inicio" id="dia_inicio" onchange="dia_seleccionado=this.value" >
							<? 
							 $actuald=date(d);
							 $dias_mes = date("t");
							 for($i=01;$i<=$dias_mes;$i++){
							?>
							<option value="<?if($i<10){print("0".$i);}else{print($i);}?>"<? if($i==$actuald){print("selected");}?>><?print($i);?>
						 <?}?>			
						</SELECT>
						<SELECT name="mes_inicio" id="mes_inicio" onchange="mes_seleccionado=this.value;cargaSelectDias()">
							<?$m=date(m);?>
							<option value=01 <?if($m==1){print("selected");}?>>Enero
							<option value=02 <?if($m==2){print("selected");}?>>Febrero
							<option value=03 <?if($m==3){print("selected");}?>>Marzo
							<option value=04 <?if($m==4){print("selected");}?>>Abril
							<option value=05 <?if($m==5){print("selected");}?>>Mayo
							<option value=06 <?if($m==6){print("selected");}?>>Junio
							<option value=07 <?if($m==7){print("selected");}?>>Julio
							<option value=08 <?if($m==8){print("selected");}?>>Agosto
							<option value=09 <?if($m==9){print("selected");}?>>Septiembre
							<option value=10 <?if($m==10){print("selected");}?>>Octubre
							<option value=11 <?if($m==11){print("selected");}?>>Noviembre
							<option value=12 <?if($m==12){print("selected");}?>>Diciembre
						</SELECT>
						<SELECT name="anio_inicio" id="anio_inicio" onchange="cargaSelectDias()">
						<?$actual=date(Y);
						for($i=$actual;$i<($actual+3);$i++){?>
						<option value="<?print($i);?>" <? if($i==$actual){print("selected");}?>><?print($i);?>
						<?}?>									
						</SELECT>			
					</th>
					<th class="titulo3" style="text-align:left;">
						<table border="0" class="opcion_triple" cellpadding="0" cellspacing="0">			
						<thead>										
							<tr class="titulo3" style="text-indent:0px;text-align:left;"> 
								  <th><input type="radio" name="fondo" value="0" checked onclick="seleccionar_imagen(0)">Blanco</th>
								  <th><input type="radio" name="fondo" value="1" onclick="seleccionar_imagen(1)">Imagen </th>
							</tr>
						</thead>
					</table>
					</th>
				</tr>
				</thead>
			</table>				
		</thead>
	</table>

	<table width="100%" height="100%" border="0">
		<thead>	
			<tr colspan="4">
				<th class="opcion_boton" align="center">
					<input class="boton_big" type="button" value="Aceptar" onClick="comprobar(this.form);" title="Generar la Gráfica"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" value="Limpiar" class="boton_big" onClick="location.href='principal.php?pag=criterios_graficos.php'" title="Borrar los Criterios"> 		
				</th>
			</tr>													
		</thead>
	</table>
  </div>
</div>
</form>
<?
	mysql_close($db);
?>





</body>
</HTML>
