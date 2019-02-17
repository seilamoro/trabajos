<?php
	session_start();
	//kike:si se cumple alguna de estas condiciones significa que la página se ha recargado,es decir no es la primera vez que se entra, por tanto se destruye la variable de session 'pag_hab'que es el array con las ventanas, las posiciones y los tipos de habitacion para volver a crearlo
	if( ($_POST['creararray']==1) || ($pag_anterior) || ($pag_nueva) ||($pag_siguiente) || ($_POST['oculto_pagina_eliminar']==""))//se destruye el array para volver a crearlo
	{
		unset($_SESSION['pag_hab']);
	}
	if( $_POST['oculto_pagina_eliminar']=="" )
	{
		unset($_SESSION['pag_hab']);
	}
	//si se ha dado a este campo oculto el valor 1 antes de mandar el formulario es para que al recargar la página aparezca la ventana que actualmente sea la uno, ya que venimos de que se elimine una ventana
	if( (isset($_POST['oculto_pag_actual_mas'])) && ($_POST['oculto_pag_actual_mas']==1) )
	{
		$_SESSION['pag_actual']=$_SESSION['pag_actual']+1;
	}
	//fin de kike
	
	/**
	* Funcion Utilizada para detectar el navegador del cliente
	* Devuelve un array en el que guarda el Nombre del Navegador, la Version y la "Signature"
	* $nav['app.Name'] contiene el Nombre del Navegador
	* $nav['app.Ver'] contiene la Version del Navegador
	* $nav['app.Sig'] contiene la "Signature" del Navegador
	*/
	function detectBrowser()
		{
		$browsers = array("msie", "firefox");
		$names = array ("msie" => "Explorer", "firefox" => "Firefox");
		$nav = "Unknown";
		$sig = strToLower ($_SERVER['HTTP_USER_AGENT']);
		foreach ($browsers as $b)
			{
	    	if ($pos = strpos ($sig, $b))
				{
	    	    $nav = $names[$b];
    	    	break;
	       		}
   			}
		if ($nav == "Unknown")
			return array ("app.Name" => $nav, "app.Ver" => "?", "app.Sig" => $sig);
	   	$ver = "";
		for ( ; $pos <= strlen ($sig); $pos ++)
			{
	       	if ((is_numeric($sig[$pos])) || ($sig[$pos]=="."))
    	       $ver .= $sig[$pos];
    		else
				if ($ver)
					break;
	   		}
		return array("app.Name" => $nav, "app.Ver" => $ver, "app.Sig" => $sig);
		}
	
	$browser=detectBrowser();
	$navegador=$browser['app.Name'];
?>	
<?php

	/**
	* Funcion utilizada para devolver una cadena que empieza a partir del caracter '$subcadenaInicio' de la cadena '$cadena'
	*/
	function substring($cadena,$subcadenaInicio)
		{
		$inicioSubstring=substr($cadena,strpos($cadena,$subcadenaInicio)+1);
		return substr($inicioSubstring,0,strlen($inicioSubstring)-1);
		}
	
/* Si existe la hoja CSS, la abro y sino abro el archivo con la configuracion inicial */
	if(file_exists ("..\Gestion_Principal\css\habitacionesColores.css"))
		@$fichero=fopen("..\Gestion_Principal\css\habitacionesColores.css","r"); 
	else
		@$fichero=fopen("..\Gestion_Principal\css\habitacionesColores.inic","r"); 
/**************************************************************************************/		
	if($fichero)
		{
		$arraiCSS=array();
		while(!feof($fichero))
			{ 
			// Mientras no sea el final de fichero, voy leyendo y segun lo que encuentre voy creando diferentes indices para un array, en el cual almaceno los colores correspondientes a cada estilo leidos desde el fichero 
	   		$buffer = fgets($fichero);
	   		if(substr_count($buffer,"{")>0)
	   			{
	   			if(substr_count($buffer,"_libre {")>0)
					$claveArrai="libre";
				else
					if(substr_count($buffer,"_ocupada {")>0)
						$claveArrai="ocupada";
					else
						if(substr_count($buffer,"_reservada {")>0)
							$claveArrai="reservada";
						else
							if(substr_count($buffer,"_reserva_resaltada {")>0)
								$claveArrai="resaltada";
							else
								if(substr_count($buffer,"_asignada {")>0)
									$claveArrai="asignada";
								else
									if(substr_count($buffer,"nom_hab {")>0)
										$claveArrai="fondos";									 
									else
										if(substr_count($buffer,"nom_tipo_hab {")>0)
											$claveArrai="fondoGeneral";
										else
											if(substr_count($buffer,"nom_hab_seleccion {")>0)
												$claveArrai="fondoSeleccion";								 
											else
												if(substr_count($buffer,"cama_temp {")>0)
													$claveArrai="temporal";
												else
													if(substr_count($buffer,"separar_hab {")>0)
														$claveArrai="elSeparador";														
													else
														if(substr_count($buffer,"cama_resaltada {")>0)
															$claveArrai="camaResaltada";														
														else
															$claveArrai="noVale";
				}
	   		if(substr_count($buffer,"background-color:")>0)
	   			{
				if($claveArrai!="noVale")
		   			$arraiCSS[$claveArrai]=substring(trim($buffer),":");//Guardo el color de fondo en el array
				}
// Si la clave del array contiene la cadena "fondo" y en lo leido del fichero aparece la cadena "\tcolor", añado al array con esa clave el valor "//" y el color, para luego poder separar el color de fondo (el que esta antes de //) y el color del texto (despues de //)				
			if(substr_count($claveArrai,"fondo")>0 && substr_count($buffer,"\tcolor:")>0)
				$arraiCSS[$claveArrai].="//".substring(trim($buffer),":");
			}
		fclose($fichero);

//Separo los colores de fondo de los colores del texto
		$arraiCSS['fondoGeneral']=split("//",$arraiCSS['fondoGeneral']);
		$arraiCSS['fondos']=split("//",$arraiCSS['fondos']);
		$arraiCSS['fondoSeleccion']=split("//",$arraiCSS['fondoSeleccion']);
		}
?>

<script>

/*	if (navigator.appName == "Microsoft Internet Explorer")
		posicion=1;
	else*/
		posicion=0;
/**
* Funcion utilizada para mostrar la tabla de colores, y para saber a que elemento tiene que cambiarle el color
* Recibe el parametro 'sitio', que es el elemento al que se le aplicara el cambio de color, por el seleccionado en la tabla de colores
*/
	var cuadro;
	function mostrarTablaColores(sitio)
		{
		cuadro=sitio;
		//document.getElementById("divTituloTabla").style.width='456px';
		document.getElementById("divColores").style.display='inline';		
		}
/**
* Funcion  utilizada para cambiar el borde de una celda de la tabla de colores
* Esta funcion depende del navegador. Segun que navegador sea pondra un tipo de borde u otro
* Recibe como parametro 'elemento', que es el elemento al cual se le cambiara el borde
*/	
	function resaltarBorde(elemento)
		{
		if(navigator.appName == "Microsoft Internet Explorer")
			elemento.style.border='dashed 2px #0000FF';
		else
			elemento.style.border='inset 2px #0000FF';
		}

/**
* Funcion  utilizada para volver a poner el borde original a una celda de la tabla de colores
* Recibe como parametro 'elemento', que es el elemento al cual se le cambiara el borde
*/
	function ponerBordeOriginal(elemento)
		{
		elemento.style.border='solid 2px #3F7BCC';
		}

</script>
<script language="javascript">
	function enviar()
		{
			document.rep.submit();
		}
</script>
<!--kike: función a la que se llama desde poner() y quitar() para dejar unna array vacío (el contenido del select que se le pasa como parámetro)-->
<script language='javascript'>
		function ClearList(OptionList, TitleName) 
		{
			OptionList.length = 0;
		}
		
		//función que añade el tipo de habitación seleccionado en el menú de la izquierda al de la derecha, recibe el formulario y un número (1 o 0) que indican si se ha pulsado la flecha(0) o se ha hecho doble click en un tipode habitación(1)
		function poner(formu,num)//recibe num que será uno si se ha hecho doble click en un tipo de habitación o cero si se viene de hacer click en la flecha de añadir, en ese caso habrá que comprobar que había un tipo seleccionado 
		{   
			var temp1 = new Array();//guardará los values de los options del select de la izquierda que se han seleccionado
			var temp2 = new Array();//guardará los textos de los options del select de la izquierda que se han seleccionado
			var tempa = new Array();//guardará los values de los options del select de la izquierda que no se han seleccionado para añadir al select de la derecha
			var tempb = new Array();//guardará los textos de los options del select de la izquierda que no se han seleccionado para añadir al select de la derecha
			var current1 = 0;
			var current2 = 0;
			var y=0;
			var attribute;

			var nueva_opcion;
			//le damos valores a attribute1 y attribute2
			attribute1 = formu.category_name; 
			attribute2 = formu.category_selected;
			//se rellena un array con los antiguos valores
			
			if(num == 0) //si se ha hecho click en la flecha de poner un tipo de habitación hay que comprobar que haya alguna opción seleccionada
			{
				var seleccionado=0; //por defecto se pone la variable a cero
				for (var v = 0; v < attribute1.length; v++)
				{  
					//se recorre todo el array(formado por las opciones del select) para ver si hay alguna opción seleccionada
					if ( attribute1.options[v].selected )
					{  
						seleccionado=1;//si hay alguna opción seleccionada se pone la variable a 1	
					}
				}
			}
			else //si no se ha pulsado ninguna flecha de poner tipos de habitación es porque se ha hecho doble click en un tipo de habitación por lo tanto ya no hay que comprobar que haya una opción seleccionada
			{
				seleccionado=1;
			}
			if(seleccionado ==1)//si la variable es 1 se procede a poner un tipo de habitación en el select de la derecha llamado attribute2
			{
				for (var i = 0; i < attribute2.length; i++)
				{  
					y=current1++
					temp1[y] = attribute2.options[i].value;
					tempa[y] = attribute2.options[i].text;
				}
				//asignar los nuevos valores a los arrays
				for (var i = 0; i < attribute1.length; i++)
				{   
					if ( attribute1.options[i].selected )
					{  
						
							nueva_opcion=attribute1.options[i].value;//para saber cual es la opción que hay que añadir en el fichero de texto, se guarda en esta variable intermedia para unas filas más abajo guardarla en un campo oculto y así tener el valor cunado se haga un submit y se recargue la página
							y=current1++
						
						temp1[y] = attribute1.options[i].value;//se guarda el valor de la opción seleccionada
						tempa[y] = attribute1.options[i].text;//se guarda el texto de la opción seleccionada
						
					}
					else
					{  
						y=current2++
						temp2[y] = attribute1.options[i].value; 
						tempb[y] = attribute1.options[i].text;
					}
				}
				//se generan las nuevas opciones 
				for (var i = 0; i < temp1.length; i++)
				{  
					attribute2.options[i] = new Option();
					attribute2.options[i].value = temp1[i];
					attribute2.options[i].text =  tempa[i];
				}
				formu.oculto_poner.value=1;//para que al recargarse la página se sepa que hay que añadir un tipo de habitación, el que se guarda en oculto_poner_id
				formu.oculto_poner_id.value=nueva_opcion;
				formu.creararray.value=1;//para que al recargar la página se destruya la varibale de sesión pag_hab
				formu.submit();
			}
			else //si la variable seleccionado no es 1 se muestra el 
			{
				alert("Debe seleccionar un tipo de habitación del menú de la izquierda");
			}
		}
		//-------------------------------------------------------------------------//
		//función que quita el tipo de habitación seleccionado en el menú de la derecha, recibe el formulario y un número (1 o 0) que indican si se ha pulsado la flecha(0) o se ha hecho doble click en un tipode habitación(1)
		//funciona igual que poner() pero a la inversa, llamando ahora atribute1 al select de la derecha y attribute2 al de la izquierda
		function quitar(formu,num)
		{   
			var temp1 = new Array();
			var temp2 = new Array();
			var tempa = new Array();
			var tempb = new Array();
			var current1 = 0;
			var current2 = 0;
			var y=0;
			var attribute;
			attribute1 = formu.category_selected;
			//asignar los nuevos valores a los arrays
			if(num == 0) //se ha hecho click en la flecha
			{
				var seleccionado=0;
				for (var x = 0; x < attribute1.length; x++)
				{  
					if ( attribute1.options[x].selected )
					{  
						seleccionado=1;	
					}
				}
			}
			else
			{
				seleccionado=1;
			}
			
			if(seleccionado ==1)
			{
				for (var i = 0; i < attribute1.length; i++)
				{   
					if ( attribute1.options[i].selected )
					{  
						var permiso=1;
						var confirmar=confirm("¿Seguro que desea eliminar el tipo de habitación \"" + attribute1.options[i].text +"\" de esta ventana?");
						if(!confirmar)
						{
							return;

						}
						else
						{
							nueva_opcion=attribute1.options[i].value;
						}
						
						
						
						i++;
						nueva_opcion_posicion=i;
						i--;
						y=current1++
						temp1[y] = attribute1.options[i].value;
						tempa[y] = attribute1.options[i].text;
					}
					else
					{  
						y=current2++
						temp2[y] = attribute1.options[i].value; 
						tempb[y] = attribute1.options[i].text;
					}
				}
				//generating new options
				ClearList(attribute1,attribute1);
				if (temp2.length>0)
				{	
					for (var i = 0; i < temp2.length; i++)
					{   
						attribute1.options[i] = new Option();
						attribute1.options[i].value = temp2[i];
						attribute1.options[i].text =  tempb[i];
					}
				}
				else
				{
					formu.oculto_pagina_vacia.value=1;
				}
				formu.oculto_quitar.value=1;
				formu.oculto_quitar_id.value=nueva_opcion;
				formu.oculto_quitar_posicion.value=nueva_opcion_posicion;
				formu.creararray.value=1;
				formu.submit();
				
			}
			else
			{
				alert("Debe seleccionar un tipo de habitación del menú de la derecha");
			}
		}
	</script>

<form name="rep" action="?pag=gi_skins.php" method="POST">
	<input type='hidden' name='oculto_poner' value=''>
	<input type='hidden' name='oculto_poner_id' value=''>
	<input type='hidden' name='oculto_quitar' value=''>
	<input type='hidden' name='oculto_quitar_id' value=''>
	<input type='hidden' name='oculto_quitar_posicion' value=''>
	<input type='hidden' name='oculto_pagina_eliminar' value=''>
	<input type='hidden' name='oculto_pagina_vacia' value=''>
	<input type='hidden' name='oculto' value=''>
	<input type='hidden' name='oculto_pag_actual_mas' value=''>
	<input type='hidden' name='oculto_quitar_uno' value=''>
	<input type='hidden' name='creararray' value=''>
	
	<table style="margin-top:20px;">
		<tr>
			<td style="vertical-align:top;">
<!--AQUI EMPIEZA LO DE JAVI CASTRO-->
				<div id="divColoresHabitaciones" style="display:inline;width:50%;">
					<table cellspacing="0" cellspacing="0" style="padding:0px 0px 0px 0px;" id="tabla_detalles">					
						<tr id="titulo_tablas">
							<td colspan="5" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
								<div class='champi_izquierda'>&nbsp;</div>
								<div class='champi_centro' style="width:460px;">Colores Tabla de Habitaciones</div>
								<div class='champi_derecha'>&nbsp;</div>
							</td>
						</tr>
						<tr>
							<td colspan="5" style="padding:0px 0px 0px 0px;">
								<div style="height:430px;width:520px;border:1px solid #3F7BCC;border-bottom:none;">
									<div style="display:inline;float:left;width:55%;">
										<table align="center">
											<tr>
												<td colspan='2' height='20' valign='bottom'>
												&nbsp;
												</td>
											</tr>
											<tr>
												<td><label class="label_formulario">Cama Libre :</label></td>
												<td><input readonly type="text" class="input_formulario" id="libre" name="libre" style="cursor:pointer;background-color:<?php echo $arraiCSS['libre'];?>;" onclick="mostrarTablaColores(this);" size="1"/></td>
											</tr>
											<tr>
												<td><label class="label_formulario">Cama Reservada :</label></td>
												<td><input readonly type="text" class="input_formulario" id="reservada" name="reservada" style="cursor:pointer;background-color:<?php echo $arraiCSS['reservada'];?>;" onclick="mostrarTablaColores(this);" size="1"/></td>
											</tr>
											<tr>
												<td><label class="label_formulario">Cama Ocupada :</label></td>
												<td><input readonly type="text" class="input_formulario" id="ocupada" name="ocupada" style="cursor:pointer;background-color:<?php echo $arraiCSS['ocupada'];?>;" onclick="mostrarTablaColores(this);" size="1"/></td>
											</tr>
											<tr>
												<td><label class="label_formulario">Cama Resaltada :</label></td>
												<td><input readonly type="text" class="input_formulario" id="camaResaltada" name="camaResaltada" style="cursor:pointer;background-color:<?php echo $arraiCSS['camaResaltada'];?>;" onclick="mostrarTablaColores(this);" size="1"/></td>
											</tr>
											<tr>
												<td><label class="label_formulario">Reserva Resaltada :</label></td>
												<td><input readonly type="text" class="input_formulario" id="resaltada" name="resaltada" style="cursor:pointer;background-color:<?php echo $arraiCSS['resaltada'];?>;" onclick="mostrarTablaColores(this);" size="1"/></td>
											</tr>
											<tr>
												<td><label class="label_formulario">Reserva Asignada :</label></td>
												<td><input readonly type="text" class="input_formulario" id="asignada" name="asignada" style="cursor:pointer;background-color:<?php echo $arraiCSS['asignada'];?>;" onclick="mostrarTablaColores(this);" size="1"/></td>
											</tr>
											<tr>
												<td><label class="label_formulario">Cama Ocupada Temporalmente :</label></td>
												<td><input readonly type="text" class="input_formulario" id="temporal" name="temporal" style="cursor:pointer;background-color:<?php echo $arraiCSS['temporal'];?>;" onclick="mostrarTablaColores(this);" size="1"/></td>
											</tr>
											<tr>
												<td><label class="label_formulario">Texto Tipo de Habitación :</label></td>
												<td><input readonly type="text" class="input_formulario" id="textoTipo" name="textoTipo" style="cursor:pointer;background-color:<?php echo $arraiCSS['fondoGeneral'][1];?>;" onclick="mostrarTablaColores(this);" size="1"/></td>
											</tr>
											<tr>
												<td><label class="label_formulario">Texto Nº Habitación :</label></td>
												<td><input readonly type="text" class="input_formulario" id="textoNumHab" name="textoNumHab" style="cursor:pointer;background-color:<?php echo $arraiCSS['fondos'][1];?>;" onclick="mostrarTablaColores(this);" size="1"/></td>
											</tr>
											<tr>
												<td><label class="label_formulario">Fondo Habitaciones :</label></td>
												<td><input readonly type="text" class="input_formulario" id="fondos" name="fondos" style="cursor:pointer;background-color:<?php echo $arraiCSS['fondos'][0];?>;" onclick="mostrarTablaColores(this);" size="1"/></td>
											</tr>
											<tr>
												<td><label class="label_formulario">Fondo General :</label></td>
												<td><input readonly type="text" class="input_formulario" id="fondoGeneral" name="fondoGeneral" style="cursor:pointer;background-color:<?php echo $arraiCSS['fondoGeneral'][0];?>;" onclick="mostrarTablaColores(this);" size="1"/></td>
											</tr>
											<tr>
												<td><label class="label_formulario">Separador Habitaciones :</label></td>
												<td><input readonly type="text" class="input_formulario" id="lineaSeparacion" name="lineaSeparacion" style="cursor:pointer;background-color:<?php echo $arraiCSS['elSeparador'];?>;" onclick="mostrarTablaColores(this);" size="1"/></td>
											</tr>											
											<tr>
												<td><label class="label_formulario">Fondo Habitación Seleccionada :</label></td>
												<td><input readonly type="text" class="input_formulario" id="fondoSeleccion" name="fondoSeleccion" style="cursor:pointer;background-color:<?php echo $arraiCSS['fondoSeleccion'][0];?>;" onclick="mostrarTablaColores(this);" size="1"/></td>
											</tr>
											<tr>
												<td><label class="label_formulario">Texto Habitación Seleccionada :</label></td>
												<td><input readonly type="text" class="input_formulario" id="textoSeleccion" name="textoSeleccion" style="cursor:pointer;background-color:<?php echo $arraiCSS['fondoSeleccion'][1];?>;" onclick="mostrarTablaColores(this);" size="1"/></td>
											</tr>
										</table>
									</div>
									<div id="divColores" name="divColores" style="display:none;float:right;width:45%;">
										<table border="1" bordercolor="#3F7BCC" style="cursor:pointer;">													
<!-- 
	Al añadir una fila con nuevos colores hay que cambiar el ID de los <TD> y el ID de los <SPAN>
-->
											<tr>
												<td id="td1" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(244, 252, 255);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span1" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td2" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(240, 250, 255);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span2" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td3" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(230, 250, 255);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span3" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td4" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(220, 250, 255);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span4" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td5" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(210, 250, 255);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span5" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
											</tr>
											<tr>
												<td id="td6" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(143, 187, 226);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span6" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td7" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(86, 156, 215);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span7" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td8" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(92, 141, 190);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span8" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td9" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(85, 125, 174);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span9" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td10" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(6, 76, 135);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span10" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
											</tr>
											<tr>
												<td id="td11" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(93, 127, 171);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span11" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td12" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(72, 108, 153);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span12" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td13" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(51, 102, 153);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span13" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td14" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(62, 92, 130);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span14" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td15" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(49, 73, 102);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span15" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
											</tr>
											<tr>
												<td id="td16" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(246, 245, 240);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span16" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td17" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(192, 192, 192);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span17" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td18" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(172, 172, 172);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span18" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td19" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(136, 136, 136);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span19" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td20" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(85, 85, 85);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span20" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
											</tr>
											<tr>
												<td id="td21" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(212, 255, 212);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span21" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td22" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(159, 239, 134);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span22" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td23" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(124, 233, 88);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span23" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td24" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(85, 226, 67);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span24" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td25" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(80, 240, 80);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span25" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
											</tr>
											<tr>
												<td id="td26" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(255, 255, 255);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span26" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td27" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(240, 240, 240);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span27" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td28" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(229, 229, 229);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span28" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td29" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(213, 213, 213);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span29" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td30" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(195, 195, 195);;" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span30" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
											</tr>
											<tr>
												<td id="td31" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(255, 145, 130);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span31" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td32" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(255, 130, 130);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span32" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td33" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(255, 89, 89);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span33" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td34" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(238, 0, 0);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span34" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td35" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(205, 0, 0);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span35" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
											</tr>
											<tr>
												<td id="td36" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(255, 255, 212);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span36" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td37" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(250, 253, 134);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span37" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td38" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(254, 226, 114);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span38" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td39" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(253, 182, 85);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span39" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td40" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(252, 162, 57);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span40" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
											</tr>
											<tr>
												<td id="td41" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(220, 199, 174);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span41" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td42" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(199, 178, 153);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span42" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td43" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(198, 156, 109);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span43" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td44" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(166, 124, 82);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span44" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td45" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(140, 98, 57);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span45" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
											</tr>
											<tr>
												<td id="td46" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(235, 188, 231);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span46" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td47" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(226, 169, 221);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span47" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td48" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(198, 126, 191);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span48" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td49" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(163, 73, 155);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span49" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
												<td id="td50" onclick="cuadro.style.backgroundColor=this.style.backgroundColor;divColores.style.display='none';" style="border:solid 2px #3F7BCC;background-color:rgb(140, 55, 132);" onMouseOver="resaltarBorde(this);" onMouseOut="ponerBordeOriginal(this);"><span id="span50" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
											</tr>
<!-- 
	Al añadir una fila con nuevos colores hay que cambiar el ID de los <TD> y el ID de los <SPAN>
-->
										</table>			
									</div>
								</div>
							</td>
						</tr>						
					</table>
<!-- Nuevo -->
					<table id="tabla_detalles" style="margin-top:-1px;margin-left:0px;width:<?php if($navegador=="Explorer"){echo "520px";} else{echo "522px";}?>;border:1px solid #3F7BCC;border-top:none;">
						<tr>
							<td align="center">
								<img border="0" src="../imagenes/botones-texto/aceptar.jpg" id="boton" onclick="location.href='?pag=guardarCSS.php&l=' + libre.style.backgroundColor.substring(posicion,libre.style.backgroundColor.length) + '&r=' + reservada.style.backgroundColor.substring(posicion,reservada.style.backgroundColor.length) + '&o=' + ocupada.style.backgroundColor.substring(posicion,ocupada.style.backgroundColor.length) + '&a=' + asignada.style.backgroundColor.substring(posicion,asignada.style.backgroundColor.length) + '&f=' + fondos.style.backgroundColor.substring(posicion,fondos.style.backgroundColor.length) + '&fGen=' + fondoGeneral.style.backgroundColor.substring(posicion,fondoGeneral.style.backgroundColor.length) + '&tipoHab=' + textoTipo.style.backgroundColor.substring(posicion,textoTipo.style.backgroundColor.length) + '&numHab=' + textoNumHab.style.backgroundColor.substring(posicion,textoNumHab.style.backgroundColor.length) + '&fSelec=' + fondoSeleccion.style.backgroundColor.substring(posicion,fondoSeleccion.style.backgroundColor.length) + '&textoSelec=' + textoSeleccion.style.backgroundColor.substring(posicion,textoSeleccion.style.backgroundColor.length) + '&temp=' + temporal.style.backgroundColor.substring(posicion,temporal.style.backgroundColor.length) + '&sep=' + lineaSeparacion.style.backgroundColor.substring(posicion,lineaSeparacion.style.backgroundColor.length) + '&resa=' + resaltada.style.backgroundColor.substring(posicion,resaltada.style.backgroundColor.length) + '&camaResaltada=' + camaResaltada.style.backgroundColor.substring(posicion,camaResaltada.style.backgroundColor.length);" alt="Guardar configuración de colores actual" title="Guardar configuración de colores actual" />
							
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;							
								<img border="0" src="../imagenes/botones-texto/restaurar.jpg" id="boton" onclick="location.href='?pag=guardarCSS.php&def=true'" alt="Poner configuración de colores inicial" title="Poner configuración de colores inicial" />
							</td>
						</tr>
					</table>
<!-- Fin Nuevo -->
				</div>
<!--AQUI ACABA LO DE JAVI CASTRO-->
			</td>
			<td style="vertical-align:top;">
<!-- NUEVA COLOCACION -->
			<table style="margin-top:-3px;">
				<tr>
					<td style="vertical-align:top;">
<!-- FIN NUEVA COLOCACION -->

	<!-- LO DE TIPO DE INTERFAZ -->
						<div style="width:50%;">
							<table cellspacing="0" cellpadding="0" id="tabla_detalles">
								<tr id="titulo_tablas" style="padding-bottom:0px;">
									<td colspan="5" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
										<div class='champi_izquierda'>&nbsp;</div>
										<div class='champi_centro' style="width:505px;">Tipo de Interfaz</div>
										<div class='champi_derecha'>&nbsp;</div>
									</td>
								</tr>
								<tr style="padding-top:0px;">
									<td>
										<div style="width:565px;border:1px solid #3F7BCC;">
											<table align="center" border='0'>
												<tr>
													<td height="30" width='110' align='center'><img src="../imagenes/pantallas/boton_azul.gif" style='width=65px;height=65px'></td>
													<td height="30" width='110' align='center'><img src="../imagenes/pantallas/boton_dorado.gif" style='width=65px;height=65px'></td>
													<td height="30" width='110' align='center'><img src="../imagenes/pantallas/boton_negro.gif" style='width=65px;height=65px'></td>
													<td height="30" width='110' align='center'><img src="../imagenes/pantallas/boton_verde.gif" style='width=65px;height=65px'></td>
												</tr>
												
												<tr>
												    <td align="center"><input type="radio" name="theme" value="azul" <?php if(isset($_SESSION['aspecto']) && $_SESSION['aspecto']=="azul")echo "checked=\"true\"";?></td>
													<td align="center"><input type="radio" name="theme" value="dorado" <?php if(isset($_SESSION['aspecto']) && $_SESSION['aspecto']=="dorado")echo "checked=\"true\"";?></td>
													<td align="center"><input type="radio" name="theme" value="negro" <?php if(isset($_SESSION['aspecto']) && $_SESSION['aspecto']=="negro")echo "checked=\"true\"";?></td>
													<td align="center"><input type="radio" name="theme" value="verde" <?php if(isset($_SESSION['aspecto']) && $_SESSION['aspecto']=="verde")echo "checked=\"true\"";?></td>
												</tr>
												<tr>
													<td height="30" align='center'><img src="../imagenes/pantallas/boton_fucsia.gif" style='width=65px;height=65px'></td>
													<td height="30" align='center'><img src="../imagenes/pantallas/boton_naranja.gif" style='width=65px;height=65px'></td>
													<td height="30" align='center'><img src="../imagenes/pantallas/boton_rosa.gif" style='width=65px;height=65px'></td>
													<td height="30" align='center'><img src="../imagenes/pantallas/boton_gris.gif" style='width=65px;height=65px'></td>
												</tr>
												<tr>
													<td align="center"><input type="radio" name="theme" value="leon" <?php if(isset($_SESSION['aspecto']) && $_SESSION['aspecto']=="leon")echo "checked=\"true\"";?></td>
													<td align="center"><input type="radio" name="theme" value="naranja" <?php if(isset($_SESSION['aspecto']) && $_SESSION['aspecto']=="naranja")echo "checked=\"true\"";?></td>
													<td align="center"><input type="radio" name="theme" value="rosa" <?php if(isset($_SESSION['aspecto']) && $_SESSION['aspecto']=="rosa")echo "checked=\"true\"";?></td>
													<td align="center"><input type="radio" name="theme" value="gris" <?php if(isset($_SESSION['aspecto']) && $_SESSION['aspecto']=="gris")echo "checked=\"true\"";?></td>
													
													
												</tr>
												<tr>
													<td align="center" colspan="8"><a href="#" onclick="enviar()"><img border="0" src="../imagenes/botones-texto/aceptar.jpg" alt="Aplicar la interfaz seleccionada" title="Aplicar la interfaz seleccionada"></a></td>
												</tr>
											</table>
										</div>
									</td>
								</tr>
							</table>
						</div>
	<!-- FIN DE LO DE TIPO DE INTERFAZ -->

<!-- NUEVA COLOCACION -->
					</td>
				</tr>
				<tr>
					<td align="center" style="padding-top:13px;">
<!-- FIN NUEVA COLOCACION -->

	<!--------------------------------------------------------------- LO DE KIKE ------------------------------------------------------------------------->
<?php
	//__________________________________si se ha añadido un tipo de habitación a una página__________________________________________________//
	$var=file("paginas/habitaciones.txt");//hace un open y close del fichero y guarda cada línea del mismo en la variable que es un array, hay que guardarlo porque el fopen con la opción a+ sobreescribe todo lo que había
	if( (isset($_POST['oculto_poner'])) && ($_POST['oculto_poner']==1) )//significa que venimos de la función poner
	{
		$id=$_POST['oculto_poner_id']; //$id es el id del tipo de habitacion que quiero añadir a la página
		$gestor=fopen("paginas/habitaciones.txt","a+"); //se abre el fichero con la opción "a+", lectura y escritura pero sin machacar lo que había
		
		//para saber la posición de dicho tipo de habitación cuando se escriba en el fichero de texto
		$pos_mayor=0;
			
		for($i=0;$i < COUNT($var) ; $i++)//se recorren todas las líneas del fichero guardadas en el array $var 
		{
			list($page,$order,$id_corte,$nada)=split(",",$var[$i]);//esta función separa por comas cada línea del fichero de texto contenida en el array $var y guarda los trozos en las variables indicadas. 
			if( $page == $_SESSION['pag_actual'])//si $page(el número de la ventana) es igual al número de la ventana activa en el select, es decir la ventana a la que vamos a añadir un tipo de habitación
			{
				$pos=$order +0; //le sumo cero para convertir el string en cadena y así poder compararlo con >
				if($pos > $pos_mayor )
				{
					$pos_mayor=$pos;
				}
			}
		}
		$pos_mayor++;//al acabar de recorrer todas las líneas del fichero tenemos la posición mayor dentro de esa ventana actualmente así que se el suma uno para obtener la posición del nuevo elemento
		
		//para comprobar que no se repita la habitación en una página
		$linea=$_SESSION['pag_actual'] ."," .$id;//creamos este string compuesto por la ventana actual y el tipo de habitación para comprobar que no exista ya este tipo de habitación en la ventana actual, en cuyo caso se le muestra un alert al usuario y se pone la variable $permiso a 0 para que no se continúe añadiendo el tipo de habitación
		$permiso=1;//por defecto valdrá 1 para permitir añadir la habitación
		for($i=0; $i<count($var); $i++)//se recorren todas las líneas del fichero guardadas en el array $var
		{
			list($page,$order,$id_corte,$nada)=split(",",$var[$i]);
			$linea2=$page ."," .$id_corte;
			//$linea2=SUBSTR($linea2,0,3);//para asegurarse de no cojer ningún caracter extraño
			if( $linea2 == $linea ) //entonces ya existe el tipo de habitación en esa ventana
			{
				$permiso=0;
				?>	
					<script language='javascript'>
						alert("No se puede repetir un tipo de habitación en la misma ventana");
					</script>
				<?php
			}
		}
		if($permiso == 1) //no existía ese tipo de habitación en esa página, por lo tanto se escribe en el fichero.Al abrirse con fopen opción +a, escribe una línea acontinuación de la última
		{
				if(COUNT($var) > 0)//si ya había alguna línea en el fichero(es decir el array $var tiene al menos una línea) hay que empezar a escribir con un salto de línea
				{
					fwrite($gestor , chr(13) .chr(10) .$_SESSION['pag_actual'] ."," .$pos_mayor ."," .$id .",");
				}
				else //si es el primer elemento, no se empieza con un salto de línea pues dejaría la primera línea en blanco
				{
					fwrite($gestor , $_SESSION['pag_actual'] ."," .$pos_mayor ."," .$id .",");
				}
		}
		fclose($gestor);
		//--comprobar que no se ha añadido un undefined--//
			$fallo=0;
			$var2=file("paginas/habitaciones.txt");
			for($i=0; $i<count($var2); $i++)//se recorren todas las líneas del fichero guardadas en el array $var
			{
				list($page,$order,$id_corte,$nada)=split(",",$var2[$i]);
				if($id_corte == "undefined")
				{
					$fallo=1;	
				}
			}
			if($fallo==1)
			{
				$gestor=fopen("paginas/habitaciones.txt","w+");
				$tope=count($var2) -1;
				for($z=0;$z < $tope; $z++)
				{
					list($page,$order,$id_corte,$nada)=split(",",$var2[$z]);
					//fwrite($gestor , $var2[$z]);
					if($z==0)
						fwrite($gestor,$page ."," .$order ."," .$id_corte .",");
					else
						fwrite($gestor,chr(13) .chr(10) .$page ."," .$order ."," .$id_corte .",");
				}
				fclose($gestor);
			}
		//------------------------comprobar si hay líneas en blanco---------------------------------------------//
		
		$var3=file("paginas/habitaciones.txt");
		$gestor=fopen("paginas/habitaciones.txt","w+");
		for($y=0; $y<count($var3); $y++)//se recorren todas las líneas del fichero guardadas en el array $var
			{
				if( $var3[$y] !=  ( chr(13).chr(10) ) )
				{
					fwrite($gestor,$var3[$y]);
				}
			}
		
			
		fclose($gestor);
		//----------------------------------------------//
		$_SESSION['pag_actual']=$_SESSION['pag_actual']+1;
	}	
	//_______________________hasta aquí la parte de añadir habitacion__________________________________
	

	//_______________________si se ha quitado un tipo de habitación a una página_______________________
	
	if( (isset($_POST['oculto_quitar'])) && ($_POST['oculto_quitar']==1) )
	{
		$var=file("paginas/habitaciones.txt");//se guarda lo que hay en el fichero en un array ya que se va abrir con fopen opción w+, con lo cual se perderá su contenido
		$id=$_POST['oculto_quitar_id']; //$id es el id del tipo de habitacion que quiero quitar de la página
		$posicion=$_POST['oculto_quitar_posicion']; //contiene la posición que ocupabba el tipo de habitación que se quiere eliminar dentro de la ventana en la que estaba
		$linea=$_SESSION['pag_actual'] ."," .$id;
		$gestor=fopen("paginas/habitaciones.txt","w+");//se abre el fichero con la opción w+ que trunca su longitud a cero, es decir lo abre en blanco borrando lo que había
				
		$pag_mayor=0;//contador para conocer el número de ventanas existentes
		for($i=0;$i<count($var);$i++)
		{
			list($page,$order,$id_corte,$nada)=split(",",$var[$i]);
			if($page > $pag_mayor)
			{
				$pag_mayor=$page;
			}
		}

		$total_tipos=COUNT($var)-1; //porque se va a copiar lo que había en el fichero al nuevo fichero excepto el tipo que se quiere eliminar de una página
		$conta_total_tipos=0;
		for($j=1;$j<=$pag_mayor;$j++) //bucle para recorrer todas las ventabnas existentes
		{
			$contador=1;
			
			for($i=0; $i<count($var); $i++)//bucle para recorrer dentro de cada ventana existente los tipos de habitación
			{
				list($page,$order,$id_corte,$nada)=split(",",$var[$i]);
				if($page==$j)
				{
					$linea2=$page ."," .$id_corte;
					if( $linea2 != $linea ) //si el tipo de habitación en esta ventana(que es lo que contiene $linea2) es distinto al que se quiere eliminar entonces se escribe en el fichero de texto
					{
                  		if($conta_total_tipos == $total_tipos)
						{
							fwrite($gestor, $page ."," .$contador ."," .$id_corte .",");	//si es la primera línea que se escribe en el fichero, no se empieza por salto de línea, en caso contrario, sí.
						}
						else
						{
							fwrite($gestor, $page ."," .$contador ."," .$id_corte ."," .chr(13) .chr(10));	
						}
					}
					else //si el tipo de habitación en la ventana es justo el que no queremos escribir, es decir el que queremos quitar, hay que reducir el contador para  poner escribir bien la posición del siguiente tipo de habitación
					{
						$contador--;
					}
					$contador++;
					$conta_total_tipos++;
				}
			}
			
		}
		if( $_POST['oculto_pagina_eliminar']=="" )//si este campo oculto está vacío es que al eliminar el tipo de habitación de la ventana aún quedan tipos de habitación en ella y por tanto no hay que eliminar la ventana
		{
			$_SESSION['pag_actual']=$_SESSION['pag_actual']+1 ;
		}
		else //se envía el formulario para recargar la página y así volver a crear el array donde ya no estará la ventana que hay que eliminar
		{
			$_SESSION['pag_actual']=$_SESSION['pag_actual']+1 ;
			?>
				<script language='javascript'>
					rep.creararray.value=1;
					rep.submit();
				</script>
			<?php
		}
	}
	//_______________________hasta aquí la parte de quitar habitacion__________________________________


	//_______________Crear array leyendo del fichero____________________________________________________________________________
	$var=file("paginas/habitaciones.txt"); //esto hace un fopen y un fclose implícitos y deja en un array cada línea del fichero
	
		$pag_mayor=1;
		if(COUNT($var) >0)//si el fichero no tiene nada no lo leo para que no me cree un primer elemento del array vacío
		{
			for($i=0;$i < COUNT($var) ; $i++)//se recorre el array $var donde cada elemento es una del fichero de texto para crear el array variable de sessión
			{
				list($page,$order,$id_corte,$nada)=split(",",$var[$i]);
				$pag=$page +0; //le sumo cero para convertir el string en cadena y así poder compararlo con >
				if($pag > $pag_mayor )
				{
					$pag_mayor=$pag;
				}
				
				//se crea el array variable de sesión
				$_SESSION['pag_hab'][$i]['pagina']=$page;
				$_SESSION['pag_hab'][$i]['orden']=$order;
				$_SESSION['pag_hab'][$i]['Id_Tipo_Hab']=$id_corte;	
			}
			if(!isset($_SESSION['total_paginas']))//si es la primera vez que se entra en esta sesión y aún no está definida esta variable de sesión que indica cuántas ventanas existen
			{
				$_SESSION['total_paginas']=$pag_mayor;
			}
		}
		
	//__________________________ hasta aquí la parte de crear el array leyendo del fichero__________________________//
	
	//_______________________para calcular la página actual que se muestra__________________________________________//
	
	if(!isset($_SESSION['pag_actual']))//si es la primera vez que se entra en esta sesión y aún no está definida esta variable de sesión que indica en cada momento la ventana activa, es decir la que se muestra en el select de la derecha
	{
		$_SESSION['pag_actual']=1;
	}
	else
	{
		if( (isset($_POST['oculto'])) && ($_POST['oculto']==1) )//se pulsó pag siguiente
		{
			$_SESSION['pag_actual']++;
			if($_SESSION['pag_actual'] > $_SESSION['total_paginas'])//si se sigue pulsando siguiente y ya estamos en la última ventana no se sigue incrementando la variable página actual y además se le muestra un alert
			{
				$_SESSION['pag_actual']=$_SESSION['total_paginas'];	
				?>
					<script language='javascript'>
						alert("Actualmente hay <?php if($_SESSION['total_paginas']==1){echo $_SESSION['total_paginas'] .' ventana ';}else{echo $_SESSION['total_paginas'] .' ventanas';} ?>. Para crear una nueva haga clic en el botón \"Nueva\"" );
					</script>
				<?php
			}
		}
		if( (isset($_POST['oculto'])) && ($_POST['oculto']==0) )//se pulsó pag anterior
		{
			$_SESSION['pag_actual']--;
			if($_SESSION['pag_actual']==0)//si se sigue pulsando anterior y ya estamos en la primera ventana no se sigue decrementando la variable página actual
			{
				$_SESSION['pag_actual']=1;
			}
		}
		if( (isset($_POST['oculto'])) && ($_POST['oculto']==2) )//se pulsó pag nueva
		{
			$_SESSION['total_paginas']++;
			$_SESSION['pag_actual']=$_SESSION['total_paginas'];
		}
		if($_POST['oculto_quitar_uno']==1)//para eliminar la ventana 1 cuando es la única que queda se abre el fichero de modo que trunque la longitud a cero y se recarga la página, además se pone el campo oculto creararray a 1 para que al recargarse la página se destruye la variable de sesión que contiene el array con los tipos de habitación y se vuelva luego a crear leyendo del fichero de texto que estará vacío
		{
			$gestor=fopen("paginas/habitaciones.txt","w+");
			fclose($gestor);
			?>
				<script language='javascript'>
					rep.creararray.value=1;
					rep.submit();
				</script>
			<?php
		}
		else if($_POST['oculto_pagina_vacia']!="") //se ha eliminado una página que no es la última luego a todas las posteriores hay que reducirles 1 para no pasar por ejemplo de la página 1 a la 3
		{
			$pag_vacia=$_SESSION['pag_actual'];
			$var=file("paginas/habitaciones.txt");
			$gestor=fopen("paginas/habitaciones.txt","w+");
			$contatore=0;//conatdor que se usará para al escribir en el fichero saber si es la primera línea que se escribe y por tanto no se empieza con salto de línea
			for($i=0; $i<count($var); $i++)
			{
				list($page,$order,$id_corte,$nada)=split(",",$var[$i]);
				if($page!=$pag_vacia) //si la página que estamos leyendo en la línea del fichero es distinto de la que se ha eliminado
				{
					$page=$page+0;//le sumo 0 para convertirlo de caracter a número y poder compararlo luego
					if($page > $pag_vacia)//si la página es posterior a la que se ha eliminado hay que restarle uno
					{
						$page--;	
					}
					if($contatore==0)
					{
						fwrite($gestor, $page ."," .$order ."," .$id_corte .",");
						$contatore++;
					}
					else
					{	
						fwrite($gestor, chr(13) .chr(10) .$page ."," .$order ."," .$id_corte .",");
					}
				}
			}
			fclose($gestor);
			$_SESSION['total_paginas']--; //comno se ha eliminado una página hay que reducir la variable de sesión que indica el número de páginas existentes
			if($_SESSION['total_paginas']<1)//para que el número de páginas existentes nunca sea negativo
			{
				$_SESSION['total_paginas']=1;
				?>
					<!--se recarga la página y se pone el campo oculto creararray a 1 para que al recargarse la página se destruye la variable de sesión que contiene el array con los tipos de habitación y se vuelva luego a crear leyendo del fichero de texto. -->
					<script language='javascript'>
						rep.oculto_quitar_uno.value=1;//para que al recargarse la página se elimine la ventana cuando es la única que queda
						rep.creararray.value=1;
						rep.submit();
					</script>
				<?php
			}
			else
			{
				?>
					<script language='javascript'>
						rep.creararray.value=1;
						rep.submit();
					</script>
				<?php
			}
		}
	}
	//______________________fin de calcular la página actual que se muestra_______________________________________//
?>

<div style="display:inline;position:relative;top:20;">	
<table border='0' cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;" id="tabla_detalles" width='565px'>
	<tr class="titulo_tablas">
		<td colspan="3" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
			<div class='champi_izquierda'>&nbsp;</div>
			<div class='champi_centro' style="width:504px;">Configurar Distribución de Habitaciones</div>
			<div class='champi_derecha'>&nbsp;</div>
		</td>
	</tr>
	<tr align='left'>
		<td colspan='5' align="center" style="padding:0px 0px 0px 0px;">
			<div class="tableContainer" style="height:180px;">
			<!------------------------------------------------------------------------->
			<?php
			@$db=mysql_pconnect("localhost","root","");//se crea una conexión persistente a la base de datos en la máquina localhost, con usuario root y contraseña vacía
			if(!$db)
			{
				echo "Error, no se puede conectar a la base de datos";
				exit;
			}
			mysql_select_db("sam");								
			$query='Select * from tipo_habitacion';	
			$result= mysql_query($query);
			$sql_num_tipos="Select count(*) as numero from tipo_habitacion;"; //para saber cuantos tipos de habitación hay en la bases de datos, ese número será el tamaño que se le da al select de la izquierda
			$resultado=mysql_query($sql_num_tipos);
			for($h=0;$h<mysql_num_rows($resultado);$h++)
			{
				$resultado2=mysql_fetch_array($resultado);
				$total_tipos=$resultado2['numero'];
			}
			?>
			<table border='0' cellspacing='0px' cellpadding="0" style="padding:0px 0px 0px 0px;" width='100%'>
				<tr>
					<td class='label_formulario' height='30' align='center'>Tipos de Habitación</td>
					<td height='30'>&nbsp;</td>
					<!--A continuación se imprime la ventana actual encima del selectde la derecha, si es la primera vez que se entra se pone 1 y si no se escribe la variable de sesión pág actual -->
					<td class='label_formulario' height='30' align='center'>Ventana <?php if(!isset($_SESSION['pag_actual'])){echo " 1";}else{echo $_SESSION['pag_actual'];} ?></td><?php $_SESSION['num_ventana']=$_SESSION['pag_actual'];?>
				</tr>
				<tr>
					<td align='center' width='220'>
						<!-- el select de la izquierda que contiene los tipos de habitación existentes, si se hace doble click en un tipo de habitación se llama a la función poner con el parámetro 1, si se pulsa la flecha se llama a dicha función con el parámetro 0 lo que le indicará que tiene que comprobar si había un tipo de habitación seleccionado -->
						<?php
							$var_num=file("paginas/habitaciones.txt");
							if(count($var_num) < $total_tipos)
							{
								$tamanio=$total_tipos;
							}
							else
							{
								$tamanio=count($var_num);
							}
						?>
						<select name="category_name" size="<?php echo $tamanio; ?>" style="width=158px;" onDblClick="poner(this.form,1);">	
						<?php
						$var_comparar=file("paginas/habitaciones.txt");
						for($i=0;$i<mysql_num_rows($result);$i++)
						{
							$fila=mysql_fetch_array($result);
							$repeticion=0;
							for($f=0;$f<count($var_comparar);$f++)
							{
								list($page,$order,$id_corte,$nada)=split(",",$var_comparar[$f]);
								if($id_corte==$fila['Id_Tipo_Hab'])
								{
									$repeticion=1;
								}
							}
							if($repeticion ==0)
							{
								echo "<option value='" .$fila['Id_Tipo_Hab'] ."'>" .$fila['Nombre_Tipo_Hab'] ."</option>";
							}
						}
						?>
						</select>
					</td>
					<td align='center' width='85'>
						<input type="button" value="  >>  " onclick="poner(this.form,0);" alt="Añadir el tipo de habitación seleccionado a esta ventana" title="Añadir el tipo de habitación seleccionado a esta ventana"><br>
						<!--el botón para quitar un tipo de habitación seleccionado.si se pulsa la flecha se llama a la función quitar() con el parámetro 0 lo que le indicará que tiene que comprobar si había un tipo de habitación seleccionado -->
						<input type="button" value="  <<  "  onclick="quitar(this.form,0)" alt="Eliminar el tipo de habitación seleccionado de esta ventana" title="Eliminar el tipo de habitación seleccionado de esta ventana">
					</td>
					<td align='center'>
						<!-- el select de la derecha que contiene los tipos de habitación existentes en una ventana, que será la que indique la variable de sesión pág actual. si se hace doble click en un tipo de habitación se llama a la función quitar con el parámetro 1, si se pulsa la flecha se llama a dicha función con el parámetro 0 lo que le indicará que tiene que comprobar si había un tipo de habitación seleccionado -->
						<select name="category_selected" size="<?php echo $total_tipos; ?>" style="width:158px;" onDblClick="quitar(this.form,1);">
							
						<?php
						//se recorre la variable de sesión que contiene el array con los tipos de habitación, la ventana y la posición que ocupan en ella
						for($i=0;$i<COUNT($_SESSION['pag_hab']);$i++)
						{
							if($_SESSION['pag_hab'][$i]['pagina']==$_SESSION['pag_actual'])//si el elemento del array se corresponde con la página actual escribimos el option al select accediendo con el id del tipo de habitación a la tabla Tipo_Habitación para obtener también el nombre del tipo
							{
								$sql="Select * from Tipo_Habitacion where Id_Tipo_Hab=" .$_SESSION['pag_hab'][$i]['Id_Tipo_Hab'] .";";
								$result=mysql_query($sql);
								for($h=0;$h<mysql_num_rows($result);$h++)
								{
									$fila=mysql_fetch_array($result);
								}
								echo "<option value='" .$_SESSION['pag_hab'][$i]['Id_Tipo_Hab'] ."'>" .$fila['Nombre_Tipo_Hab'] ."</option>";
							}
						}
						?>
						</select>
					</td>
				</tr>	
				
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td height='40px' valign='bottom' align='center'>
						<!--<a href="#"> <input type='submit' name='pag_anterior' value='Anterior' onclick='oculto.value=0'></a>
						<a href="#"> <input type='submit' name='pag_siguiente' value='Siguiente' onclick='oculto.value=1'></a>
						<a href="#"> <input type='submit' name='pag_nueva' value='Nueva' onclick='oculto.value=2'></a>-->

						<input type='image' src='./../imagenes/botones-texto/anterior.jpg' name='pag_anterior' alt='Ver página anterior' title='Ver página anterior' onclick='oculto.value=0;this.submit();'>
						<input type='image' src='./../imagenes/botones-texto/siguiente.jpg' name='pag_siguiente' alt='Ver página siguiente' title='Ver página siguiente' onclick='oculto.value=1;this.submit();'>
						<input type='image' src='./../imagenes/botones-texto/nueva.jpg' name='pag_nueva' alt='Crear nueva página' title='Crear nueva página' onclick='oculto.value=2;this.submit();'>
					</td>
				</tr>			
			</table>
			<?php
				mysql_close();//se cierra la conexión persistente a la base de datos
			?>
					<!------------------------------------------------------------------------->
			</div>
		</td>
	</tr>
</table>
</div>
	<!--------------------------------------------------------------- FIN DE LO DE KIKE ----------------------------------------------------------------->
<!-- NUEVA COLOCACION -->
					</td>
				</tr>
			</table>
<!-- FIN NUEVA COLOCACION -->
		</tr>
	</table>
</form>