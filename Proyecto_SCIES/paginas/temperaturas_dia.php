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
</head>
<script>
	function asignaDias(dia,mes,anyo){
		
            comboDias = document.getElementById("dia_inicio");
            comboMeses = document.getElementById("mes_inicio");
            comboAnyos = document.getElementById("anio_inicio");

			Month = comboMeses[comboMeses.selectedIndex].text;
            Year = comboAnyos[comboAnyos.selectedIndex].text;

            diasEnMes = cuantosDias(Month, Year);
            diasAhora = comboDias.length;

            if (diasAhora > diasEnMes){
                for (i=0; i<(diasAhora-diasEnMes); i++){
				
                    comboDias.options[comboDias.options.length - 1] = null
                }
            }
            if (diasEnMes > diasAhora){
                for (i=0; i<(diasEnMes-diasAhora); i++){
                    sumaOpcion = new Option(comboDias.options.length+1);
                    comboDias.options[comboDias.options.length]=sumaOpcion;
                }
            }
            if (comboDias.selectedIndex < 0)
              comboDias.selectedIndex = 0;
         }
		

	// función para saber cuantos días tiene cada mes
	function cuantosDias(mes, anyo){
		var cuantosDias = 31;
		if (mes == "Abril" || mes == "Junio" || mes == "Septiembre" || mes == "Noviembre")
	  cuantosDias = 30;
		if (mes == "Febrero" && (anyo/4) != Math.floor(anyo/4))
	  cuantosDias = 28;
		if (mes == "Febrero" && (anyo/4) == Math.floor(anyo/4))
	  cuantosDias = 29;
	 
		return cuantosDias;
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
					attribute2.options[i].text = tempa[i];
					attribute2.options[i].title = tempa[i];
					attribute2.options[i].label = tempa[i];
				}
			
				attribute1.length=0;
				for (var i = 0; i < temp2.length; i++){
					attribute1.options[i] = new Option();
					attribute1.options[i].value = temp2[i];
					attribute1.options[i].text = tempb[i];
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

				//***************
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
				//**************
				
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
			cont = form1.para_disponibles.length;
			for(i = 0; i < parametros.length; i++){
				if(parametros[i][0] == centro){
					for(p = 0; p < parametros[i][1].length; p++){
						valor = parametros[i][1][p].split('*');
						if(valor[0] != ""){
							enc = 0;
							for(var b = 0; b < form1.para_disponibles.length; b++){
								if(form1.para_disponibles.options[b].value == valor[0])
									enc = 1;
							}
							for(var b = 0; b < form1.para_seleccionadas.length; b++){
								if(form1.para_seleccionadas.options[b].value == valor[0])
									enc = 1;
							}
							if(enc == 0){
								obj = form1.para_disponibles;

								obj.options[cont] = new Option();
								obj.options[cont].value = valor[0];
								obj.options[cont].text =  valor[1];
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
			for(var i = 0; i < parametros.length; i++){
				if(parametros[i][0] == centro){	//busco los parametros del centro que se deselecciona y los recorro
					for(var p = 0; p < parametros[i][1].length; p++){	
						var valor = parametros[i][1][p].split('*');	
						if(valor[0] != ""){
							var enc = 0;

							for(var b = 0; b < form1.ins_seleccionadas.length; b++){ //recorro todas los centros seleccionados
								for(var i1 = 0; i1 < parametros.length; i1++){
									if(parametros[i1][0] == form1.ins_seleccionadas.options[b].value){	//busco los parametros del centro y los recorro
										for(var p1 = 0; p1 < parametros[i1][1].length; p1++){
											if(parametros[i1][1][p1] == parametros[i][1][p]){
												enc = 1;
											}
										}
									}
								}
							}

							if(enc == 0){
								var obj;
								for(var b = 0; b < form1.para_disponibles.length; b++){
									if(form1.para_disponibles.options[b].value == valor[0])
										obj = document.getElementById("para_disponibles");
								}
								for(var b = 0; b < form1.para_seleccionadas.length; b++){
									if(form1.para_seleccionadas.options[b].value == valor[0])
										obj = document.getElementById("para_seleccionadas");
								}
								
								var temp_o1 = new Array();
								var temp_oa = new Array();
								var con = 0;
								if(obj){
								
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
		
function horas(obj,tipo){
//Hinicio
cuadro = document.getElementById(obj);
	
	if(tipo=="sumar"){
	if(cuadro.options.selectedIndex!=24)
	 	cuadro.options[cuadro.options.selectedIndex+1].selected = true;
	}else{
	if(cuadro.options.selectedIndex!=0)
		cuadro.options[cuadro.options.selectedIndex-1].selected = true;
	}
	
}



//funcion para comprobar el formulario y enviarlo
function comprobar(){
	hh1 =document.form1.Hinicio.value.substr(0,2);
			hh2 = document.form1.Hfin.value.substr(0,2);
	
	if(document.form1.ins_seleccionadas.length==0){//compruebo que al menos este seleccionado un centro
		alert("Debe de seleccionar al menos una Instalación");
	}else if(document.form1.para_seleccionadas.length==0){//compruebo que al menos este seleccionado un parámetro
		alert("Debe de seleccionar al menos un Parámetro");
	}else if(hh1>hh2){
		alert("La hora de inicio no puede ser mayor que la hora fin.");
	}else if(hh1==hh2){
		alert("Las horas de inicio y la fin no pueden  ser iguales.");
	}
	else{
		ok = 0;
				selec = form1.ins_seleccionadas.length * form1.para_seleccionadas.length;
				if(document.getElementById("grafico").value == "1" && selec > 6){
					ok = 1;
					alert("Debe seleccionar menos parámetros.");
				}else if(document.getElementById("grafico").value == "2" && selec > 3){
					ok = 1;
					alert("Debe seleccionar menos parámetros.");
				}else if(document.getElementById("grafico").value == "3" && selec > 4){
					ok = 1;
					alert("Debe seleccionar menos parámetros.");
				}
				if(ok == 0){
					for (var i = 0; i < form1.ins_seleccionadas.length; i++){
						form1.ins_seleccionadas.options[i].selected = true;
					}
					for (var i = 0; i < form1.para_seleccionadas.length; i++){
						form1.para_seleccionadas.options[i].selected = true;
					}
					form1.submit();
					}
	}
	
}

function cambiar_selec(num){
			document.getElementById("grafico").value = num;
			for(i = 1; i < 4; i++){
				document.getElementById("gra"+i).className = "grafico_no_selec";
			}
			document.getElementById("gra"+num).className = "grafico_selec";
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
<body>

<form name="form1" id="form1" method="post" action="principal.php?pag=crear_imagen_temperaturas_dia.php">
<input type="hidden" name="grafico" id="grafico" value="1">
<input type="hidden" name="centros" id="centros" >
<?php
$db = mysql_pconnect('localhost','root','');
	if (!$db){
		echo ("Error al acceder a la base de datos, intentelo mas tarde");
		exit();
	}
mysql_select_db("scies");
?>
<div class="pagina">
<div class="listado1" >
      <div class="titulo1">Criterios</div>
					<table border="0" class="opcion_triple" >
        			<thead>						
						<tr>						
							<th class="titulo2" colspan="2">Instalaciones</th>													
						</tr>
					</thead>
					<thead>	
						<tr>
							
								<table border="0" class="opcion_triple"  cellpadding="0" cellspacing="0">			
									<thead>						
									<tr class="titulo3">
										<th style="text-indent:0px;">Disponibles</th>
										<th ></th>
										<th style="text-indent:0px;">Seleccionados</th>
									</tr>
									</thead>
									<thead >
									<tr>
										<th >
											<select multiple size="5" style="width:250px" name="ins_disponibles" id="ins_disponibles" onDblClick="poner(this.form,1,1,1,0)">
    			
    <?
							$sql = "select * from centro";
							$res = mysql_query($sql);
							$num = mysql_num_rows($res);
							//**************
							?>
							<script>
								var parametros = new Array(<?=$num?>);
							</script>
							<?
							//*************
							for ($i = 0; $i < $num; $i++){
								$fila = mysql_fetch_array($res);
						?>
								<option value="<?=$fila['Id_Centro']?>" label="<?=$fila['Nombre']?>" title="<?=$fila['Nombre']?>"><?=$fila['Nombre']?></option>
						<?

							//***************
							
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
							//************


							}
						?>
    										</select>
    									</th>
    									<th><input type="button" value=">>" onClick="poner(this.form,1,1,1,1);" class="boton_small" title="Seleccionar todas las Instalaciones"><br>
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
													
							
						</tr>
					</thead>	
				</table>
				<table border="0" class="opcion_triple">
        			<thead>						
						<tr>						
							<th class="titulo2" colspan="2">Parámetros</th>													
						</tr>
					</thead>
					<thead>	
						<tr>
							
								<table border="0" class="opcion_triple">			
									<thead>						
									<tr>
										<th class="titulo3" style="text-indent:0px;">Disponibles</td>
										<th class="titulo3"></th>
										<th class="titulo3" style="text-indent:0px;">Seleccionados</td>
									</tr>
									</thead>
									<thead >
									<tr>
										<th >
											<select multiple size="5" style="width:250px" name="para_disponibles" id="para_disponibles" onDblClick="poner(this.form,1,2,1,0)">
    
    										</select>
    									</th>
    									<th><input type="button" value=">>" onClick="poner(this.form,1,2,1,1);" class="boton_small" title="Seleccionar todos los Parámetros"><br>
						<input type="button" value=">" onClick="poner(this.form,0,2,1,0);" class="boton_small" title="Seleccionar el Parámetro"><br>
						<input type="button" value="<" onClick="poner(this.form,0,2,2,0);" class="boton_small" title="Deseleccionar el Parámetro"><br>
						<input type="button" value="<<" onClick="poner(this.form,1,2,2,1);" class="boton_small" title="Deseleccionar todos los Parámetros"></th>
										<th>
											<select multiple size="5" style="width:250px" name="para_seleccionadas[]" id="para_seleccionadas" onDblClick="poner(this.form,1,2,2,0);">

    										</select>
    									</th>
									</tr>
									</thead>
								</table>
													
							</th>
						</tr>
					</thead>	
				</table>
				
										
											
				
				<table border="0" class="opcion_triple">			
					<thead>						
					<tr>						
						<th class="titulo2" colspan="4">Tipo de Gr&aacute;fico</th>
					</tr>
					</thead>
					
					<thead>	
					<tr>
						<table border="0" class="opcion_triple">			
							<thead>						
								<tr class="titulo3" style="text-align:left;">
					<th width="33%"><img name="gra1" id="gra1" style="cursor:pointer;" class="grafico_selec" src="./imagenes/graficos/grafico1.jpg" alt="Gr&aacute;fico lineal" align="absmiddle" onclick="cambiar_selec(1)"></th>
					<th><img name="gra2" id="gra2" style="cursor:pointer;" class="grafico_no_selec" src="./imagenes/graficos/grafico2.jpg" alt="Gr&aacute;fico con medias" align="absmiddle" onclick="cambiar_selec(2)"></th>
					<th width="33%"><img name="gra3" id="gra3" style="cursor:pointer;" class="grafico_no_selec" src="./imagenes/graficos/grafico3.jpg" alt="Gr&aacute;fico barras" align="absmiddle" onclick="cambiar_selec(3)"></th>
				</tr>
							</thead>
							
										</table>
										</tr>
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
						<input type="hidden" name="Finicio" id="Finicio">
						<input type="hidden" name="fondo_img" id="fondo_img" value="">
						<SELECT name="dia_inicio" id="dia_inicio" >
							<? $actuald=date(d);
							 for($i=01;$i<32;$i++){?>
							<option value="<?if($i<10){print("0".$i);}else{print($i);}?>"<? if($i==$actuald){print("selected");}?>><?print($i);?>
						 <?}?>			
						</SELECT>
						<SELECT name="mes_inicio" id="mes_inicio" onchange="asignaDias('dia_inicio','mes_inicio','anio_inicio')">
							<?$m=date(m);?>
							<option value=01 <?if($m==1){print("selected");}?>>Enero</option>
							<option value=02 <?if($m==2){print("selected");}?>>Febrero</option>
							<option value=03 <?if($m==3){print("selected");}?>>Marzo</option>
							<option value=04 <?if($m==4){print("selected");}?>>Abril</option>
							<option value=05 <?if($m==5){print("selected");}?>>Mayo</option>
							<option value=06 <?if($m==6){print("selected");}?>>Junio</option>
							<option value=07 <?if($m==7){print("selected");}?>>Julio</option>
							<option value=08 <?if($m==8){print("selected");}?>>Agosto</option>
							<option value=09 <?if($m==9){print("selected");}?>>Septiembre</option>
							<option value=10 <?if($m==10){print("selected");}?>>Octubre</option>
							<option value=11 <?if($m==11){print("selected");}?>>Noviembre</option>
							<option value=12 <?if($m==12){print("selected");}?>>Diciembre</option>
						</SELECT>
						<SELECT name="anio_inicio" id="anio_inicio" >
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
								  <th><input type="radio" name="fondo" value="1" onclick="seleccionar_imagen(1)">Imagen</th>
							</tr>
						</thead>
					</table>
					</th>
				</tr>
				</thead>
			</table>				
		</thead>
	</table>
	<script>asignaDias('dia_inicio','mes_inicio','anio_inicio');</script>
	<table border="0" class="opcion_triple">
		<thead>						
			<tr>						
				<th class="titulo2" width="50%">Hora Inicio</th>
				<th class="titulo2" style="text-indent:10px;">Hora Fin</th>
			</tr>
		</thead>
		<thead>	
			<table border="0" class="opcion_triple" cellpadding="0" cellspacing="0">			
				<thead>						
				<tr>						
					<th class="titulo3" style="text-align:center;" width="50%">
							<input type='button' value='  <  ' onClick="horas('Hinicio','restar');" class="boton_small" title="Retroceder una Hora">
						&nbsp;
						<select name="Hinicio" id="Hinicio">
						<?
								for($i=0;$i<25;$i++){
									if(strlen($i)==2){
										if($i.":00"==date("H",time()-(2 * 60 * 60)).":00")
											echo "<option value='".$i.":00' selected>".$i.":00</option>";
										else
											echo "<option value='".$i.":00'>".$i.":00</option>";
									}else{
										if("0".$i.":00"==date("H",time()-(2 * 60 * 60)).":00")
											echo "<option value='0".$i.":00' selected>0".$i.":00</option>";
										else
											echo "<option value='0".$i.":00'>0".$i.":00</option>";
										}
									
								}
						
						?>
						</select>
						 &nbsp;
						<input type='button' value='  >  ' onClick="horas('Hinicio','sumar');" class="boton_small" title="Avanzar una Hora">		
					</th>
					<th class="titulo3" style="text-align:center;">
						<input type='button' value='  <  ' onClick="horas('Hfin','restar');" class="boton_small" title="Retroceder una Hora">&nbsp;
														
														<select name="Hfin" id="Hfin">
						<?
								for($i=0;$i<25;$i++){
									if(strlen($i)==2){
										if($i.":00"==date('H',time()).':00')
											echo "<option value='".$i.":00' selected>".$i.":00</option>";
										else
											echo "<option value='".$i.":00'>".$i.":00</option>";
									}else{
										if("0".$i.":00"==date('H',time()).':00')
											echo "<option value='0".$i.":00' selected>0".$i.":00</option>";
										else
											echo "<option value='0".$i.":00'>0".$i.":00</option>";
										}
									
								}
						
						?>
						</select>
														&nbsp;
														<input type='button' value='  >  ' onClick="horas('Hfin','sumar');" class="boton_small" title="Avanzar una Hora">
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
					<input class="boton_big" type="button" value="Aceptar"  onClick="comprobar();" title="Generar la Gráfica"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input class="boton_big" type="button" value="Limpiar" onClick="location.href='principal.php?pag=temperaturas_dia.php'" title="Borrar los Criterios"> 		
				</th>
			</tr>													
		</thead>
	</table>									
			
		
</div>
</div>
<?PHP


?>
</form>
</body>
</HTML>
