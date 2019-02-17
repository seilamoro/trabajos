<?
session_start();
	if($_GET['recargar'] != "si"){
		$_SESSION['reserva_s']['dni'] = "";
		$_SESSION['reserva_s']['nombre'] = "";
		$_SESSION['reserva_s']['apellido1'] = "";
		$_SESSION['reserva_s']['apellido2'] = "";
		$_SESSION['reserva_s']['telefono'] = "";
		$_SESSION['reserva_s']['email'] = "";
		$_SESSION['reserva_s']['seleccionaDia'] = "";
		$_SESSION['reserva_s']['seleccionaMes'] = "";
		$_SESSION['reserva_s']['seleccionaAnyo'] = "";
		$_SESSION['reserva_s']['dias'] = 1;
		$_SESSION['reserva_s']['camas'] = 1;
		$_SESSION['reserva_s']['compartidas'] = 0;
		$_SESSION['reserva_s']['individuales'] = 0;
		$_SESSION['reserva_s']['check_tarde'] = "";
		$_SESSION['reserva_s']['tarde'] = "";
	}
	if(!isset($_SESSION['conexion'])){//si no esta creada aun la session de pag_hab
		include('../config.inc.php');	//incluimos el contenido de la pagina file_conf_skins.php
	}
?>


<html><!-- InstanceBegin template="/Templates/plantilla_php.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
	<link rel="stylesheet" href="../css/estilos.css">
	<link rel="stylesheet" href="../css/disponibilidad_online.css">	
	<title>::ALBERGUE MUNICIPAL DE LEÓN</title>
	<!-- InstanceBeginEditable name="head" -->
    <SCRIPT>
		
			
        // definimos las variables que almacenaran los componentes de la fecha actual
<?php
		if($_SESSION['reserva_s']['seleccionaDia'] == "")
			{
?>
        	ahora = new Date();
			ahora.setDate(ahora.getDate()+1);
<?php
			}
		else
			{
?>
			ahora = new Date(<?=$_SESSION['reserva_s']['seleccionaAnyo']?>, <?=$_SESSION['reserva_s']['seleccionaMes']-1?>, <?=$_SESSION['reserva_s']['seleccionaDia']?>);
<?php
			}
?>
        ahoraDay = ahora.getDate();
        ahoraMonth = ahora.getMonth();
        ahoraYear = ahora.getYear();
		
		ahora1 = new Date();
		ahora1.setDate(ahora1.getDate()+1);
        ahoraDay1 = ahora1.getDate();
        ahoraMonth1 = ahora1.getMonth();
        ahoraYear1 = ahora1.getYear();

        // Nestcape Navigator 4x cuenta el anyo a partir de 1900, por lo que es necesario sumarle esa cantidad para obtener el anyo actual adecuadamente
        if (ahoraYear < 2000)
            ahoraYear += 1900;
		if (ahoraYear1 < 2000)
            ahoraYear1 += 1900;
            
        /**
		* Esta función restablece el formulario y pone el foco en el text del 'dni'
		*/
		function limpiar()
			{
		    document.getElementById("form1").reset();
		    eliminarTodosOptions();
			ponerOptions();
//			ponDia();
			document.getElementById("dni").focus();
			}
		
		/**
		* Función para saber cuantos dias tiene cada mes
		*/
//        function cuantosDias(mes, anyo)
//			{
//            var cuantosDias = 31;
//            if (mes == "Abril" || mes == "Junio" || mes == "Septiembre" || mes == "Noviembre")
//	        	cuantosDias = 30;
//            if (mes == "Febrero" && (anyo/4) != Math.floor(anyo/4))
//         		cuantosDias = 28;
//            if (mes == "Febrero" && (anyo/4) == Math.floor(anyo/4))
//          		cuantosDias = 29;
//            return cuantosDias;
//        	}

        /**
		* una vez que sabemos cuantos dias tiene cada mes asignamos dinamicamente este numero 
		* al combo de los dias dependiendo del mes que aparezca en el combo de los meses
		*/
//        function asignaDias()
//			{
//            comboDias = document.getElementById("seleccionaDia");
//            comboMeses = document.getElementById("seleccionaMes");
//            comboAnyos = document.getElementById("seleccionaAnyo");
//
//            Month = comboMeses[comboMeses.selectedIndex].text;
//            Year = comboAnyos[comboAnyos.selectedIndex].text;
//			
//            diasEnMes = cuantosDias(Month, Year);
//            diasAhora = comboDias.length;
//
//            if(diasAhora>diasEnMes)
//				{
//                for (i=0; i<(diasAhora-diasEnMes); i++)
//                    comboDias.options[comboDias.options.length - 1] = null
//				comboDias.selectedIndex=(comboDias.options.length-1);
//                }
//            
//            if (diasEnMes > diasAhora)
//				{alert("MAs en mes q en select");
//                for (i=0; i<(diasEnMes-diasAhora); i++)
//					{
//                    sumaOpcion = new Option(comboDias.options.length + 1);
//                   comboDias.options[comboDias.options.length]=sumaOpcion;
//                	}
//				}
//            if (comboDias.selectedIndex < 0)
//            	comboDias.selectedIndex = 0;
//         	}

        /**
		* Ahora selecionamos en los combos los valores correspondientes a la fecha actual del sistema
        */
//		function ponDia()
//			{
//            comboDias = document.getElementById("seleccionaDia");
//            comboMeses = document.getElementById("seleccionaMes");
//            comboAnyos = document.getElementById("seleccionaAnyo");
//
//            //comboAnyos[0].selected = true;
//            comboMeses[ahoraMonth].selected = true;
//
//            asignaDias();
//
//            comboDias[ahoraDay-1].selected = true;
//	        }

		/**
		* Esta función recibe una fecha y la pone en los combos de la 'fecha de llegada'
        */
//		function cambiarFecha(fecha)
//			{
//          fec = fecha.split("/");
//            document.getElementById("seleccionaAnyo").value = fec[2];
//           document.getElementById("seleccionaMes").value = fec[1];
//            asignaDias();
//            document.getElementById("seleccionaDia").value = fec[0];
//        	}

		/**
		* Esta función cambia el estado del text area de 'llega tarde'
        */
		function estado_tarde(){
           if(document.getElementById("check_tarde").checked == true){
                document.getElementById("tarde").disabled = false;
				document.getElementById("tarde").value = "";
				document.getElementById("tarde").focus();
			}
            else{
                document.getElementById("tarde").disabled = true;
				document.getElementById("tarde").value = "(Indique la hora aproximada de llegada)";
			}
        }
		
		/**
		* Estas funciones cambian la imagen de los text numéricos, para dar el efecto de que se pulsan sobre ellos
        */
		function cambiar1(num)
			{
            if(num == 1)
                document.getElementById("img1").src = "../imagenes/flechas/flechas1a.jpg";
            if(num == 2)
                document.getElementById("img1").src = "../imagenes/flechas/flechas2a.jpg";
            if(num == 3)
                document.getElementById("img1").src = "../imagenes/flechas/flechas3a.jpg";
        	}
        function cambiar2(num)
			{
            if(num == 1)
                document.getElementById("img2").src = "../imagenes/flechas/flechas1a.jpg";
            if(num == 2)
                document.getElementById("img2").src = "../imagenes/flechas/flechas2a.jpg";
            if(num == 3)
                document.getElementById("img2").src = "../imagenes/flechas/flechas3a.jpg";
        	}
		function cambiar3(num)
			{
            if(num == 1)
                document.getElementById("img3").src = "../imagenes/flechas/flechas1a.jpg";
            if(num == 2)
                document.getElementById("img3").src = "../imagenes/flechas/flechas2a.jpg";
            if(num == 3)
                document.getElementById("img3").src = "../imagenes/flechas/flechas3a.jpg";
        	}
		function cambiar4(num)
			{
            if(num == 1)
                document.getElementById("img4").src = "../imagenes/flechas/flechas1a.jpg";
            if(num == 2)
                document.getElementById("img4").src = "../imagenes/flechas/flechas2a.jpg";
            if(num == 3)
                document.getElementById("img4").src = "../imagenes/flechas/flechas3a.jpg";
       		}
		
		/**
		* Estas funciones cambian el valor del text numérico correspondiente
		*/
        function aumentar1()
			{
            document.getElementById("dias").value = (document.getElementById("dias").value/1) + 1;
        	}
        function aumentar2()
			{
            document.getElementById("camas").value = (document.getElementById("camas").value/1) + 1;
        	}
		function aumentar3()
			{
            document.getElementById("compartidas").value = (document.getElementById("compartidas").value/1) + 1;
        	}
		function aumentar4()
			{
            document.getElementById("individuales").value = (document.getElementById("individuales").value/1) + 1;
        	}
        function disminuir1()
			{
            if(document.getElementById("dias").value != "1")
                document.getElementById("dias").value = (document.getElementById("dias").value/1) - 1;
        	}
        function disminuir2()
			{
            if(document.getElementById("camas").value != "1")
                document.getElementById("camas").value = (document.getElementById("camas").value/1) - 1;
        	}
		function disminuir3()
			{
            if(document.getElementById("compartidas").value != "0")
                document.getElementById("compartidas").value = (document.getElementById("compartidas").value/1) - 1;
        	}
		function disminuir4()
			{
            if(document.getElementById("individuales").value != "0")
                document.getElementById("individuales").value = (document.getElementById("individuales").value/1) - 1;
        	}
		
		/**
		* Estas funciones calcula la posicion del popup en la pantalla para que aparezca en el centro
		*/
        function pos_left(anc)
			{
			return (screen.width - anc) / 2;
        	}
        function pos_top(alt)
			{
			return (screen.height - alt) / 2;
        	}
		
		/**
		* Esta funciòn muestra la pagina del calendario en un popup
		*/
//        function muestraCalendario()
//			{
//          var opciones="toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=400,height=300,top=" + pos_top(300) + ",left=" + pos_left(400);
//            var pagina = "calendario.php?";
//            window.open(pagina,"",opciones);
//        	}
		


		function calcletra(dni){
			var JuegoCaracteres="TRWAGMYFPDXBNJZSQVHLCKET";
			var Posicion= dni % 23;
			var Letra = JuegoCaracteres.charAt(Posicion);
			return Letra;
		}


		
		/**
		* Esta función comprueba que el formulario se ha rellenado correctamente y lo envia, si no da un error
		*/
		function comprobar()
			{
			form = document.getElementById("form1");
			var fecha = new Date(form.seleccionaAnyo.value, form.seleccionaMes.selectedIndex, form.seleccionaDia.value);			
			var hoy =  new Date(ahoraYear1, ahoraMonth1, ahoraDay1);
			var letra_dni = calcletra(form.dni.value.substring(0,8));

			if(form.dni.value == "" || form.dni.value.length < 9 || form.dni.value.substring(8,9) != letra_dni)
				{
				alert("Falta rellenar el D.N.I.");
				form.dni.focus();
				}
			else
				if(form.nombre.value == "")
					{
					alert("Falta rellenar el nombre");
					form.nombre.focus();
					}
				else
					if(form.apellido1.value == "")
						{
						alert("Falta rellenar el primer apellido");
						form.apellido1.focus();
						}
			//else
				//if(form.apellido2.value == ""){
				//alert("Falta rellenar el segundo apellido");
				//form.apellido2.focus();
			//}
					else
						if((form.telefono.value == "")||(form.telefono.value.length<9))
							{
							alert("El número de dígitos del teléfono no es correcto");
							form.telefono.focus();
							}
						else
							if(form.email.value == "")
								{
								alert("Falta rellenar el campo E-mail");
								form.email.focus();
								}
							else
								if(c_mail(form.email.value) == false)
									{
									alert("Rellene correctamente el E-mail");
									form.email.focus();
									form.email.select(); 
									}
								else
									if(fecha<hoy)
										{
										alert("La fecha de llegada debe ser superior a la fecha actual");
										form.seleccionaDia.focus();
										}
									else
										if (isNaN(form.dias.value) || eval(form.dias.value < 1))
											{
											alert("El número de días debe ser un número mayor que cero");
											form.dias.focus();
											form.dias.select(); 
											}
										else
											if (isNaN(form.camas.value) || eval(form.camas.value < 1))
												{
												alert("El número de camas debe ser un número mayor que cero");
												form.camas.focus();
												form.camas.select(); 
												}
											else
												if(isNaN(form.compartidas.value) || eval(form.compartidas.value < 0))
													{
													alert("El número de camas compartidas debe ser un número mayor que cero");
													form.compartidas.focus();
													form.compartidas.select(); 
													}
												else
													if(isNaN(form.individuales.value) || eval(form.individuales.value < 0))
														{
														alert("El número de camas individuales debe ser un número mayor que cero");
														form.individuales.focus();
														form.individuales.select(); 
														}
													else
														if(eval(form.camas.value) != eval(form.individuales.value) + eval(form.compartidas.value))
															{
															alert("Debe rellenar el número de camas de tipo individual o compartidas");
															form.camas.focus();
															form.camas.select(); 
															}
														else
															if(form.check_tarde.checked == true && form.tarde.value == "")
																{
																alert("Falta rellenar la hora de llegada");
																form.tarde.focus();
																}
															else
																if(form.codigo.value == "")
																	{
																	alert("Falta rellenar codigo de la imagen");
																	form.codigo.focus();
																	}
																else
																	form.submit();						
			}
		
		/**
		* Esta función comprueba que sea correcto el formato del email introducido
		*/
        function c_mail(texto)
			{ 
			var mailres = true;             
			var cadena = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890@._-"; 
			var arroba = texto.indexOf("@",0); 
			if ((texto.lastIndexOf("@")) != arroba)
				arroba = -1; 
			var punto = texto.lastIndexOf("."); 	 
			for (var contador = 0 ; contador < texto.length ; contador++)
				{ 
				if (cadena.indexOf(texto.substr(contador, 1),0) == -1)
					{ 
					mailres = false; 
					break; 
			 		} 
				} 
			if ((arroba > 1) && (arroba + 1 < punto) && (punto + 1 < (texto.length)) && (mailres == true) && (texto.indexOf("..",0) == -1)) 
				mailres = true; 
			else 
				mailres = false; 			 
			return mailres; 
			}
		
		function com_tarde()
			{
			if(document.getElementById("check_tarde").checked == true)
				document.getElementById("tarde").disabled = false;
			}
		
		var repetir;
		var tiempo = 500;
		
		function parar()
			{
			clearInterval(repetir);
			}
		
		function aumentar_noch()
			{
            document.getElementById("dias").value = (document.getElementById("dias").value/1) + 1;
        	}
        	
        function disminuir_noch()
			{
            if(document.getElementById("dias").value != "1")
                document.getElementById("dias").value = (document.getElementById("dias").value/1) - 1;
        	}
		
		function aumentar_noch_c()
			{
			repetir = setInterval("aumentar_noch()",tiempo);
			}
		
		function disminuir_noch_c()
			{
			repetir = setInterval("disminuir_noch()",tiempo);
			}
		
		function aumentar_pers()
			{
            document.getElementById("camas").value = (document.getElementById("camas").value/1) + 1;
        	}

        function disminuir_pers()
			{
            if(document.getElementById("camas").value != "1")
                document.getElementById("camas").value = (document.getElementById("camas").value/1) - 1;
        	}
		
		function aumentar_pers_c()
			{
			repetir = setInterval("aumentar_pers()",tiempo);
			}
		
		function disminuir_pers_c()
			{
			repetir = setInterval("disminuir_pers()",tiempo);
			}
		
		function aumentar_camas()
			{
            document.getElementById("compartidas").value = (document.getElementById("compartidas").value/1) + 1;
        	}
        	
        function disminuir_camas()
			{
            if(document.getElementById("compartidas").value != "0")
                document.getElementById("compartidas").value = (document.getElementById("compartidas").value/1) - 1;
        	}
		
		function aumentar_camas_c()
			{
			repetir = setInterval("aumentar_camas()",tiempo);
			}
		
		function disminuir_camas_c()
			{
			repetir = setInterval("disminuir_camas()",tiempo);
			}
		
		function aumentar_indi()
			{
            document.getElementById("individuales").value = (document.getElementById("individuales").value/1) + 1;
        	}
        	
        function disminuir_indi()
			{
            if(document.getElementById("individuales").value != "0")
                document.getElementById("individuales").value = (document.getElementById("individuales").value/1) - 1;
        	}
		
		function aumentar_indi_c()
			{
			repetir = setInterval("aumentar_indi()",tiempo);
			}
		
		function disminuir_indi_c()
			{
			repetir = setInterval("disminuir_indi()",tiempo);
			}
		
    </SCRIPT>
<!----- MI SCRIPT ------>

	<script language="JavaScript">
			
			/** Variables en las que se guardara la fecha que estaba seleccionada antes de cambiar la fecha **/
			var fechaTemp=new Date();
			fechaTemp.setDate(fechaTemp.getDate()+1);
			var anyoTemp=parseInt(fechaTemp.getYear());
			var mesTemp=parseInt(fechaTemp.getMonth());
			var diaTemp=parseInt(fechaTemp.getDate());
			/*************************************************************************************************/
			
			/**
			* Esta funcion carga en los <select> la fecha correspondiente a mañana
			*/

			var iniciar = 0;
			function ponerOptions()
				{
				var fecha=new Date();
				fecha.setDate(fecha.getDate()+1);
				var diaFecha=fecha.getDate();
				var mesFecha=fecha.getMonth();
				var anyoFecha=fecha.getYear();
			
				for(var i=1;i<=obtenerDiasMes(eval(parseInt(mesFecha)+1),anyoFecha);i++)
					document.getElementById('seleccionaDia').options[(document.getElementById('seleccionaDia').length)]=new Option(i,i);

				document.getElementById('seleccionaDia').selectedIndex=eval(parseInt(diaFecha)-1);
				document.getElementById('seleccionaMes').selectedIndex=mesFecha;
				document.getElementById('seleccionaAnyo').value=anyoFecha;
				

				if(iniciar == 0){		//-------------- matener la fecha
					<?
						if($_SESSION['reserva_s']['seleccionaDia'] != ""){
					?>
							var dia_sel = <?=$_SESSION['reserva_s']['seleccionaDia']?>;
							document.getElementById('seleccionaDia').selectedIndex=eval(<?=$_SESSION['reserva_s']['seleccionaDia']?>-1);
							document.getElementById('seleccionaMes').selectedIndex=eval(<?=$_SESSION['reserva_s']['seleccionaMes']?>-1);
							document.getElementById('seleccionaAnyo').value="<?=$_SESSION['reserva_s']['seleccionaAnyo']?>";
							iniciar = 1;
					<?
						}	
					?>
				

			
					iniciar = 1;
				}

				}
			
			/**
			* Esta funcion elimina todos los <option> del select de los dias y restablece las variables temporales de fecha a la fecha de mañana
			*/
			function eliminarTodosOptions()
				{
				var tamanyo=document.getElementById('seleccionaDia').length;
				for(var i=tamanyo;i>=0;i--)
					document.getElementById('seleccionaDia').options[i]=null;
				anyoTemp=parseInt(fechaTemp.getYear());
				mesTemp=parseInt(fechaTemp.getMonth());
				diaTemp=parseInt(fechaTemp.getDate());
				}
	
			/**
			* Esta funcion comprueba que no se ponga una fecha anterior a mañana
			*/
			function comprueba()
				{
				var fecha=new Date(); // Obtengo la fecha actual
				fecha.setDate(fecha.getDate()+1);
				var diaFecha=parseInt(fecha.getDate()); // El dia actual
				var mesFecha=parseInt(fecha.getMonth()); // El mes actual
				var anyoFecha=parseInt(fecha.getYear()); // El año actual
				
				var anyoElegido=parseInt(document.getElementById('seleccionaAnyo').value); // El año seleccionado
				var mesElegido=parseInt(document.getElementById('seleccionaMes').selectedIndex); // El mes seleccionado
				var diaElegido=parseInt(document.getElementById('seleccionaDia').value); // El dia seleccionado
				
				if(anyoElegido==anyoFecha && mesElegido==mesFecha && diaElegido<diaFecha) // Si el año y el mes son los actuales y el dia es anterior a mañana
					{
					//alert("año actual, mes actual y dia anterior a mañana");
					document.getElementById('seleccionaDia').value=diaFecha;
					}
				else
					if(anyoElegido==anyoFecha && mesElegido<mesFecha) // Si el mes es uno anterior al actual del año actual
						{
						//alert("año actual y mes anterior");
						document.getElementById('seleccionaMes').selectedIndex=mesFecha;
						if(diaElegido<diaFecha) // Si el dia seleccionado es anterior al dia de mañana
							document.getElementById('seleccionaDia').value=diaFecha;
						else
							document.getElementById('seleccionaDia').value=diaTemp;
						}
					else
						{
						//alert("dia valido");
						anyoTemp=parseInt(document.getElementById('seleccionaAnyo').value);
						mesTemp=parseInt(document.getElementById('seleccionaMes').selectedIndex);
						diaTemp=parseInt(document.getElementById('seleccionaDia').value);
						}
				cargaSelectDias();
				}
			
			/**
			* Esta funcion carga el select de los dias dependiendo de que mes este seleccionado
			*/
			function cargaSelectDias()
				{
				var mes=parseInt(document.getElementById('seleccionaMes').selectedIndex);
				var anyo=parseInt(document.getElementById('seleccionaAnyo').value);
				var dia=parseInt(document.getElementById('seleccionaDia').value);
								
				var tamanyoSelectDias=parseInt(document.getElementById('seleccionaDia').length); // Nº de options del <select> de los dias
				var diasMes=obtenerDiasMes(eval(mes+1),anyo); // Nº de dias del mes seleccionado del año seleccionado
				
				var dif=eval(tamanyoSelectDias-diasMes);
				
				//alert("DIA="+dia+"\nDIAS DEL MES="+diasMes);
				if(dif<0)
					{
					while(diasMes>document.getElementById('seleccionaDia').length)
						document.getElementById('seleccionaDia').options[(document.getElementById('seleccionaDia').length)]=new Option(eval(parseInt(document.getElementById('seleccionaDia').length)+1),eval(parseInt(document.getElementById('seleccionaDia').length)+1));
					}
				else
					if(dif>0)
						{
						while(diasMes<document.getElementById('seleccionaDia').length)
							document.getElementById('seleccionaDia').options[(document.getElementById('seleccionaDia').length)-1] = null;
						if(dia>diasMes)
							document.getElementById('seleccionaDia').value=parseInt(document.getElementById('seleccionaDia').length);
						}
				}

			/**
			* Funcion que devuelve el nº de dias de un mes de un determinado año
			*/
			function obtenerDiasMes(mes,anyo)
				{
				if(mes==2) // Si el mes es Febr
					{
					if((anyo%4==0 && anyo%100!=0) || anyo%400==0) // Si es bisiesto
						return 29;
					else
						return 28;
					}
				else
					if(mes==11 || mes==4 || mes==6 || mes==9) // Si el mes es Nov, Abr, Jun o Sept
						return 30;
					else
						return 31;
				}

	</script>

<!-- FIN DE MI SCRIPT -->

	<!-- InstanceEndEditable -->
	</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="ponerOptions()">
<table width="100%" height="100%" cellpadding="0" cellspacing="0"><tr><td valign="top">
<table width="950" height="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#F0FAD3">
        <tr> 
          	<td height="120" colspan="2" align="left" valign="top" bgcolor="#3333FF"> 
			<div><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="100%" height="100%">
                    <param name="movie" value="../swf/superior.swf">
                    <param name=quality value=high>
                    <embed src="../swf/superior.swf" quality=high pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="100%" height="100%"></embed> 
                  </object>
			</div>
			</td>
        </tr>
        <tr> 
          <td align="left" valign="top" width="200px" rowspan="2"> 
				<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#67720C">
					<tr> 
						<td align="center" valign="top" height="300">
						<?
							$nom = split("/",$PHP_SELF);
						?>
							<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="100%" height="300">
								<param name="movie" value="../swf/menu.swf">
								<param name=quality value=high>
								<PARAM NAME=FlashVars VALUE="pagina=<?=$nom[count($nom)-1]?>">
								<embed src="../swf/menu.swf" quality=high pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="100%" height="300"></embed> 
							</object>
						</td>
					</tr>
					<tr> 
						<td align="center" valign="top">							
						</td>
					</tr>
					<tr> 
					<td height="65">
							<table width="95%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
								
                      			<td width="61"><img src="../imagenes/logo_peque.gif" width="61" height="50"></td> 
								<td>
									<font size="2" color="#FFFFFF"> 
									<?
										@ $db = mysql_pconnect($_SESSION['conexion']['host'], $_SESSION['conexion']['user'],  $_SESSION['conexion']['pass']);
										if (!$db){
											echo "Error : No se ha podido conectar a la base de datos";
											exit;
										}
										mysql_select_db($_SESSION['conexion']['db']);
										$sql = "select *, pais.nombre_pais as Pais_Alb, provincia.nombre_pro as Provincia_Alb  from albergue inner join pais on ";
										$sql = $sql . "(albergue.id_pais = pais.id_pais) inner join provincia on (albergue.id_pro = provincia.id_pro)";
										$res = mysql_query($sql);
										$datos = mysql_fetch_array($res);
										echo $datos['Direccion_Alb'] . "<br>";
										echo "C.P.: " . $datos['CP_Alb'] . " (" . $datos['Localidad_Alb'] . ")<br>";
										echo "Tlf.: " . $datos['Tfno1_Alb'] . "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $datos['Tfno2_Alb'];
									?>
									</font>
								</td>
							</tr>
							</table>
					</td>
				</tr>
				</table>
			</td> 
          	<td align="left" valign="top">
				<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
              <tr> 
                <td width="31" height="35" valign="top"><img src="../imagenes/sep.gif" width="31" height="31"> 
                </td>
                <td width="719" align="center" valign="middle"><u><!-- InstanceBeginEditable name="titulo" -->
					<b><font face="Arial, Helvetica, sans-serif" size="5" color="#C40056">Solicitud de Reserva On-Line</font></b>
					<!-- InstanceEndEditable --></u> <br>
                </td>
              </tr>
              <tr> 
                <td colspan="2" align="center" valign="top"> <!-- InstanceBeginEditable name="contenido" -->
<table border="0">
  <tr>
    <td valign="top">
    <FORM name="form1" id="form1" action="confirmar_reserva_online.php" method="POST">

    <TABLE border="0" align="center" width="90%" cellspacing="0" cellpadding="0">
        <TR height="40">
            <TD width="20"><IMG src="../imagenes/img_cuadro_a/cuadro_arriba1_a.gif"></TD>
            <TD>
                <TABLE height="100%" width="100%" cellspacing="0" cellpadding="0"><TR>
                    <TD width="265">
                        <FONT color="#3366FF" size="4"><B>&nbsp;<font color="#F79239">Formulario de Solicitud Reserva<B></font></FONT>
                    </TD>
                    <TD background="../imagenes/img_cuadro_a/cuadro_L_arr_a.gif">&nbsp;</TD>
                </TR>
                </TABLE>
            </TD>
            <TD width="20"><IMG src="../imagenes/img_cuadro_a/cuadro_arriba2_a.gif"></TD>
        </TR>
       
        <TR>
            <TD width="20" background="../imagenes/img_cuadro_a/cuadro_L_izq_a.gif">&nbsp;</TD>
            <TD>
                <TABLE border="0" align="center" width="90%" cellspacing="0" cellpadding="0">
                <TR><TD>
                    <font face="Arial, Helvetica, sans-serif" size="2">Rellene el siguiente formulario con sus datos. TODOS los campos son obligatorios, para que podamos gestionar correctamente su solicitud de reserva.</font>
                </TD></TR>
				</TABLE>
                <TABLE border="0" align="center" width="90%" cellspacing="0" cellpadding="0">
                    <TR height="40">
                        <TD width="20"><IMG src="../imagenes/img_cuadro/cuadro_arriba1.gif"></TD>
                        <TD>
                            <TABLE height="100%" width="100%" cellspacing="0" cellpadding="0"><TR>
                                <TD width="150"><FONT color="#F9AF6D" size="3" face="Arial, Helvetica, sans-serif"><B>&nbsp;Datos Personales</B></font></TD>
                                <TD background="../imagenes/img_cuadro/cuadro_L_arr.gif">&nbsp;</TD>
                            </TR></TABLE>
                        </TD>
                        <TD width="20"><IMG src="../imagenes/img_cuadro/cuadro_arriba2.gif"></TD>
                    </TR>
                    <TR>
                    <TD width="20" background="../imagenes/img_cuadro/cuadro_L_izq.gif">&nbsp;</TD>
                    <TD>
                        <TABLE align="center">
                            <TR>
                                <TD width="150" align="right"><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>D.N.I.:</b></font></TD>
                                <TD><INPUT name="dni" id="dni" type="text" size="40" maxlength="30" value="<?=$_SESSION['reserva_s']['dni']?>"></TD>
                            </TR>
                            <TR>
                                <TD width="150" align="right"><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>Nombre:</b></font></TD>
                                <TD><INPUT name="nombre" id="nombre" type="text" size="40" maxlength="20" value="<?=$_SESSION['reserva_s']['nombre']?>"></TD>
                            </TR>
                            <TR>
                                <TD align="right"><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>Primer Apellido:</b></font></TD>
                                <TD><INPUT name="apellido1" id="apellido1" type="text" size="40" maxlength="30" value="<?=$_SESSION['reserva_s']['apellido1']?>"></TD>
                            </TR>
                            <TR>
                                <TD align="right"><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>Segundo Apellido:</b></font></TD>
                                <TD><INPUT name="apellido2" id="apellido2" type="text" size="40" maxlength="30" value="<?=$_SESSION['reserva_s']['apellido2']?>"></TD>
                           </tr>
							<tr>
                                <TD width="150" align="right"><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>Teléfono:</b></font></TD>
                                <TD><INPUT name="telefono" id="telefono" type="text" size="40" maxlength="12" onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" value="<?=$_SESSION['reserva_s']['telefono']?>"></TD>
                            </TR>
                            <TR>
                                <TD align="right"><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>E-mail:</b></font></TD>
                                <TD><INPUT name="email" id="email" type="text" size="40" maxlength="50" value="<?=$_SESSION['reserva_s']['email']?>"></TD>
                            </TR>
                        </TABLE>
                    </TD>
                    <TD width="20" background="../imagenes/img_cuadro/cuadro_L_der.gif">&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD width="20"><IMG src="../imagenes/img_cuadro/cuadro_abajo1.gif"></TD>
                        <TD background="../imagenes/img_cuadro/cuadro_L_aba.gif">&nbsp;</TD>
                        <TD width="20"><IMG src="../imagenes/img_cuadro/cuadro_abajo2.gif"></TD>
                    </TR>
                </TABLE>

                <TABLE border="0" align="center" width="90%" cellspacing="0" cellpadding="0">
                    <TR height="40">
                        <TD width="20"><IMG src="../imagenes/img_cuadro/cuadro_arriba1.gif"></TD>
                        <TD>
                            <TABLE height="100%" width="100%" cellspacing="0" cellpadding="0"><TR>
                                <TD width="170"><FONT color="#F9AF6D" size="3" face="Arial, Helvetica, sans-serif"><B>&nbsp;Datos de la Reserva</B></TD>
                                <TD background="../imagenes/img_cuadro/cuadro_L_arr.gif">&nbsp;</TD>
                            </TR></TABLE>
                        </TD>
                        <TD width="20"><IMG src="../imagenes/img_cuadro/cuadro_arriba2.gif"></TD>
                    </TR>
                    <TR>
                    <TD width="20" background="../imagenes/img_cuadro/cuadro_L_izq.gif">&nbsp;</TD>
                    <TD>
                        <TABLE align="center">
                            <TR>
                                <TD width="150" align="right"><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>Fecha de Llegada:</b></font></TD>
                                <TD>
                                    <select name='seleccionaDia' id='seleccionaDia' onchange='comprueba()' size='1'></select>
									<select name='seleccionaMes' id='seleccionaMes' onchange='comprueba()' size='1'>
<?php
									$meses=array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
									for($i=0;$i<count($meses);$i++)
										echo "<option value='".($i+1)."'>".$meses[$i]."</option>";
?>
									</select>
									<select name='seleccionaAnyo' id='seleccionaAnyo' onchange='comprueba()'  size='1'>
<?php
									$anyo=date("Y");
									for($i=$anyo;$i<($anyo+5);$i++)
										echo "<option value='".$i."'>".$i."</option>";
?>		
									</select>
<!-- ENLACE AL CALENDARIO
                                    <a href="javascript:muestraCalendario()"><IMG src="../imagenes/icon_calendario.gif" border="0" align="absmiddle"></a>
-->
                                    <FONT title="Ver Disponibilidad de Habitaciones" style="padding:2px 2px 2px 2px;cursor:pointer;font-weight:bold;text-decoration:underline;" color="#67720C" size="2" face="Arial, Helvetica, sans-serif" onmouseout="this.style.textDecoration='underline';" onmouseover="this.style.textDecoration='none';" onclick="window.open('disponibilidad_online.php?fecha_cal='+ diaTemp +'-'+ (mesTemp+1) +'-'+ anyoTemp +'&limit=0','Disponibilidad','dependent=yes,directories=no,location=no,menubar=no,height=768px,width=1024px,left=136,top=152,toolbar=no,status=no;');">Disponibilidad</font>
                                </TD>
                            </TR>
                            <TR>
                                
                        <TD align="right"><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b><a name="diasa" id="diasa"></a>Número 
                          de Días:</b></font></TD>
                                <TD>
                                    <TABLE cellspacing="0" cellpadding="0"><TR>
                                        <TD><INPUT name="dias" id="dias" type="text" value="<?=$_SESSION['reserva_s']['dias']?>" size="2" maxlength="2"></TD>
                                        <TD><IMG src="../imagenes/flechas/flechas1a.jpg" align="absmiddle" usemap="#mapa1" border="0" name="img1" id="img1"></TD>
                                                <TD align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>N&uacute;mero 
                                                  de Personas:</b></font></TD>
                                        <TD>&nbsp;&nbsp;
                                <INPUT name="camas" id="camas" type="text" value="<?=$_SESSION['reserva_s']['camas']?>" size="2" maxlength="2"></TD>
                                        <TD><IMG src="../imagenes/flechas/flechas1a.jpg" align="absmiddle" usemap="#mapa2" border="0" name="img2" id="img2"></TD>
                                    </TR></TABLE>
                                </TD>
                            </TR>
							<tr>
								<td colspan="2" align="center"><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif">
									<b>- Número de camas -</b>
								</font></td>
							</tr>
							<TR>
                                <TD align="right"><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>Compartidas:</b></font></TD>
                                <TD>
                                    <TABLE cellspacing="0" cellpadding="0" border="0"><TR>
                                        <TD><INPUT name="compartidas" id="compartidas" type="text" value="<?=$_SESSION['reserva_s']['compartidas']?>" size="2" maxlength="2"></TD>
                                        <TD><IMG src="../imagenes/flechas/flechas1a.jpg" align="absmiddle" usemap="#mapa3" border="0" name="img3" id="img3"></TD>
                                        <TD align="right" width="148"><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>Individuales:</b></font></TD>
                                        <TD>&nbsp;&nbsp;
                                		<INPUT name="individuales" id="individuales" type="text" value="<?=$_SESSION['reserva_s']['individuales']?>" size="2" maxlength="2"></TD>
                                        <TD><IMG src="../imagenes/flechas/flechas1a.jpg" align="absmiddle" usemap="#mapa4" border="0" name="img4" id="img4"></TD>
                                    </TR></TABLE>
                                </TD>
                            </TR>
                            <TR>
                                <TD valign="top" align="right">
									<? if($_SESSION['reserva_s']['check_tarde'] == "on"){ ?>
										<INPUT type="checkbox" onclick="estado_tarde()" name="check_tarde" id="check_tarde" checked>
									<? }else{ ?>
										<INPUT type="checkbox" onclick="estado_tarde()" name="check_tarde" id="check_tarde">
									<? } ?>
									<FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>Llega Tarde</b></font></TD>
                                <TD>
									<? if($_SESSION['reserva_s']['tarde'] == ""){ ?>
                                    	<TEXTAREA rows="2" cols="30" disabled name="tarde" id="tarde">(Indique la hora aproximada de llegada)</TEXTAREA>
                               		<? }else{ ?>
										<TEXTAREA rows="2" cols="30" disabled name="tarde" id="tarde"><?=$_SESSION['reserva_s']['tarde']?></TEXTAREA>
									<? } ?>
							    </TD>
                            </TR>
                        </TABLE>
                    </TD>
                    <TD width="20" background="../imagenes/img_cuadro/cuadro_L_der.gif">&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD width="20"><IMG src="../imagenes/img_cuadro/cuadro_abajo1.gif"></TD>
                        <TD background="../imagenes/img_cuadro/cuadro_L_aba.gif">&nbsp;</TD>
                        <TD width="20"><IMG src="../imagenes/img_cuadro/cuadro_abajo2.gif"></TD>
                    </TR>
                </TABLE>

                <TABLE border="0" align="center" width="90%" cellspacing="0" cellpadding="0">
					<TR>
						<TD width="50%" align="left">
							<br>
<?php
									require_once "src/jpgraph_antispam.php";
									$spam = new AntiSpam();
									$cod = $spam-> Rand(5);
									$_SESSION['codigo'] = $cod;
?>
							<div style='width:20%;display:inline;float:left;text-align:center;'>	
								<div style='width:100%;display:inline;float:left;text-align:center;'>
									<img src="imagen.php">
								</div>
								<div style='width:100%;display:inline;float:left;text-align:center;'>
									<input type="text" name="codigo" id="codigo" size="14" maxlength="19" value="">
								</div>
							</div>
							<div style='display:inline;float:left;overflow:none;width:25%;text-align:center;color:rgb(249,175,109);font-size:13px;font-weight:bold;'>
								<font face='Arial, Helvetica, sans-serif'>(Escriba los caracteres que ve en la imagen)</font>
							</div>
							<div style='width:49%;display:inline;float:right;vertical-align:middle;'>
								<a href="javascript:comprobar();"><img align="left" src='../imagenes/botones/enviar.gif' border="0"></a>
							    <a href="javascript:limpiar();"><img align="right" src='../imagenes/botones/limpiar.gif' border="0"></a>
							</div>
						</TD>
					</TR>
               	</TABLE>
              
                <MAP name="mapa1">
                    <AREA shape="rect" coords="0,0,14,10" href="#diasa" onmouseover="cambiar1(2)" onmouseout="parar();cambiar1(1)" onclick="aumentar1()" onMouseDown="aumentar_noch_c()" onMouseUp="parar()">
                    <AREA shape="rect" coords="0,10,14,21" href="#diasa" onmouseover="cambiar1(3)" onmouseout="parar();cambiar1(1)" onclick="disminuir1()" onMouseDown="disminuir_noch_c()" onMouseUp="parar()">
                </MAP>
                <MAP name="mapa2">
                    <AREA shape="rect" coords="0,0,14,10" href="#diasa" onmouseover="cambiar2(2)" onmouseout="parar();cambiar2(1)" onclick="aumentar2()" onMouseDown="aumentar_pers_c()" onMouseUp="parar()">
                    <AREA shape="rect" coords="0,10,14,21" href="#diasa" onmouseover="cambiar2(3)" onmouseout="parar();cambiar2(1)" onclick="disminuir2()" onMouseDown="disminuir_pers_c()" onMouseUp="parar()">
                </MAP>
            	<MAP name="mapa3">
                    <AREA shape="rect" coords="0,0,14,10" href="#diasa" onmouseover="cambiar3(2)" onmouseout="parar();cambiar3(1)" onclick="aumentar3()" onMouseDown="aumentar_camas_c()" onMouseUp="parar()">
                    <AREA shape="rect" coords="0,10,14,21" href="#diasa" onmouseover="cambiar3(3)" onmouseout="parar();cambiar3(1)" onclick="disminuir3()" onMouseDown="disminuir_camas_c()" onMouseUp="parar()">
                </MAP>
				<MAP name="mapa4">
                    <AREA shape="rect" coords="0,0,14,10" href="#diasa" onmouseover="cambiar4(2)" onmouseout="parar();cambiar4(1)" onclick="aumentar4()" onMouseDown="aumentar_indi_c()" onMouseUp="parar()">
                    <AREA shape="rect" coords="0,10,14,21" href="#diasa" onmouseover="cambiar4(3)" onmouseout="parar();cambiar4(1)" onclick="disminuir4()" onMouseDown="disminuir_indi_c()" onMouseUp="parar()">
                </MAP>
            <TD width="20" background="../imagenes/img_cuadro_a/cuadro_L_der_a.gif">&nbsp;</TD>
        </TR>
        <TR>
            <TD width="20"><IMG src="../imagenes/img_cuadro_a/cuadro_abajo1_a.gif"></TD>
            <TD background="../imagenes/img_cuadro_a/cuadro_L_aba_a.gif">&nbsp;</TD>
            <TD width="20"><IMG src="../imagenes/img_cuadro_a/cuadro_abajo2_a.gif"></TD>
        </TR>
    </TABLE>
    </FORM>
<!--    
    <TABLE border="0" align="center" width="90%" cellspacing="0" cellpadding="0">
    <TR><TD>
      <p><FONT size="2">Los datos de carácter personal que usted nos envía a través de este formulario podrán ser incorporados a nuestros ficheros de tratamiento manual o automatizado. Los afectados tendrán derecho en los términos previstos en la Ley Orgánica 15/99 de 13 de diciembre, de protección de datos de carácter personal y en las demás normas que la desarrollan, a conocer, rectificar y cancelar los datos de carácter personal incluidos en estos ficheros. Al enviar los anteriores datos el afectado acepta el tratamiento automatizado que podamos dar a los mismos.</FONT>
</p>
      </TD></TR>
    </TABLE>
-->    
</table>
<script>
	//ponDia();
	com_tarde();
	document.getElementById('dni').focus()
</script>

							<!-- InstanceEndEditable --> 
                  </td>
              </tr>
              <tr> 
                <td height="35" colspan="2" align="center"> <font size="-1"> [:: 
                  <a href="../index.html">Inicio</a> ::] [:: Informaci&oacute;n 
                  [: <a href="info_juvenil_online.php">Albergue Juvenil</a> 
                  :] - [: <a href="info_pere_online.php">Albergue Peregrino</a> 
                  :] - [: <a href="servicios_online.php">Servicios</a> :] - [: 
                  <a href="accesos_online.php">Accesos</a> :] ::] <br>
                  [:: <a href="disponibilidad_online.php">Disponibilidad</a> ::] 
                  [:: Reservas On-Line [: <a href="reserva_online.php">Nueva</a> 
                  :] - [: <a href="anular_reserva_online.php">Eliminar</a> :] 
                  ::] [:: <a href="tarifa_listado_online.php">Tarifas</a> ::] 
                  </font> </td>
              </tr>
            </table>
          </td>
        </tr>

      </table>
</td></tr></table>
<?
mysql_close($db);
?>
</body>
<!-- InstanceEnd --></html>
