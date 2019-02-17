<?php

if(isset($_REQUEST['def']) && $_REQUEST['def']==true) //Si he elegido la opcion de restaurar colores por defecto
	{
	// Copio el archivo habitacionesColores.inic sobre habitacionesColores.css
	copy("..\Gestion_Principal\css\habitacionesColores.inic", "..\Gestion_Principal\css\habitacionesColores.css");
	}
else
	{
	// Abro el archivo habitacionesColores.css y escribo en el los diferentes estilos
	@$archivo=fopen("..\Gestion_Principal\css\habitacionesColores.css","w+");
		if($archivo)
			{
			/***********************************
				Estilos de Alberto 
			************************************/
			fwrite($archivo,".cama_libre {\r\n\theight: 25px; \r\n\twidth: 25px; \r\n\tbackground-color: ".$_REQUEST['l']."; \r\n\tcursor: default;\r\n\t}\r\n");
			fwrite($archivo,".cama_ocupada {\r\n\theight: 25px; \r\n\twidth: 25px; \r\n\tbackground-color: ".$_REQUEST['o']."; \r\n\tcursor: pointer;\r\n\t}\r\n");
			fwrite($archivo,".cama_reservada {\r\n\theight: 25px; \r\n\twidth: 25px; \r\n\tbackground-color: ".$_REQUEST['r']."; \r\n\tcursor: pointer;\r\n\t}\r\n");
			fwrite($archivo,".cama_reserva_resaltada {\r\n\theight: 25px; \r\n\twidth: 25px; \r\n\tbackground-color: ".$_REQUEST['resa']."; \r\n\tcursor: pointer;\r\n\t}\r\n");
			fwrite($archivo,".no_cama {\r\n\theight: 25px; \r\n\twidth: 25px; \r\n\tbackground-color: ".$_REQUEST['fGen']."; \r\n\tcursor:default; \r\n\t}\r\n");
 
			fwrite($archivo,".cama_temp {\r\n\theight: 25px; \r\n\twidth: 25px; \r\n\tbackground-color: ".$_REQUEST['temp']."; \r\n\tcursor: pointer; \r\n\t}\r\n");
			fwrite($archivo,".cama_resaltada {\r\n\theight: 25px; \r\n\twidth: 25px; \r\n\tbackground-color: ".$_REQUEST['camaResaltada']."; \r\n\tcursor: pointer;\r\n\t}\r\n");
			/**********************************
				Fin Estilos Alberto
			**********************************/
			
			/**********************************
				Estilos Javi Mateos
			**********************************/			
			fwrite($archivo,".cama_libre_con {\r\n\theight: 25px; \r\n\twidth: 25px; \r\n\tbackground-color: ".$_REQUEST['l']."; \r\n\tcursor: pointer;\r\n\t}\r\n");
			fwrite($archivo,"#cama_reservada_online {\r\n\theight: 25px; \r\n\twidth: 25px; \r\n\tbackground-color: ".$_REQUEST['r']."; \r\n\t}\r\n");
			fwrite($archivo,".cama_asignada {\r\n\theight: 25px; \r\n\twidth: 25px; \r\n\tbackground-color: ".$_REQUEST['a']."; \r\n\t}\r\n");
			fwrite($archivo,"#cama_ocupada_online {\r\n\theight: 25px; \r\n\twidth: 25px; \r\n\tbackground-color: ".$_REQUEST['o']."; \r\n\t}\r\n");
			fwrite($archivo,".linea_blanca {\r\n\tbackground-repeat:repeat-y; \r\n\tbackground-position:center top; \r\n\t}\r\n");
			/**********************************
				Fin Estilos Javi Mateos
			**********************************/
			
			/**********************************************************************************
			En estos 3 estilos SIEMPRE tiene que ir antes el 'background-color' que el 'color',
			sino no funcionara la seleccion de colores correctamente
			**********************************************************************************/
			fwrite($archivo,".nom_hab_online {\r\n\ttext-align: center; \r\n\tfont-size: 11px; \r\n\tfont-weight: bold; \r\n\tbackground-color: ".$_REQUEST['f']."; \r\n\tcolor: ".$_REQUEST['numHab']."; \r\n\tcursor: pointer; \r\n\t}\r\n");
			fwrite($archivo,".nom_hab {\r\n\ttext-align: center; \r\n\tfont-size: 11px; \r\n\tfont-weight: bold; \r\n\tbackground-color: ".$_REQUEST['f']."; \r\n\tcolor: ".$_REQUEST['numHab']."; \r\n\tcursor:default; \r\n\t}\r\n");
			fwrite($archivo,".nom_tipo_hab {\r\n\ttext-align: center; \r\n\tfont-size: 14px; \r\n\tfont-weight: bold; \r\n\tbackground-color: ".$_REQUEST['fGen']."; \r\n\tcolor: ".$_REQUEST['tipoHab']."; \r\n\t}\r\n");
			/**********************************************************************************/
			
			fwrite($archivo,".tabla_habitaciones {\r\n\tbackground-color: ".$_REQUEST['fGen'].";\r\n\t}\r\n");
			fwrite($archivo,".separar_hab {\r\n\tbackground-color: ".$_REQUEST['sep'].";\r\n\t}\r\n");
			
			/***********************************************************
				Estilo de Jaime, para cuando se arrastran habitaciones
				En este estilo TAMBIEN tiene que ir antes el 'background-color'
				que el 'color',	sino no funcionara la seleccion de colores correctamente
			***********************************************************/
			 fwrite($archivo,".nom_hab_seleccion {\r\n\ttext-align: center; \r\n\tfont-size: 11px; \r\n\tfont-weight: bold; \r\n\tbackground-color: ".$_REQUEST['fSelec']."; \r\n\tcolor: ".$_REQUEST['textoSelec']."; \r\n\tcursor: pointer; \r\n\t}\r\n");			
			/**********************************
				Fin Estilo Jaime
			**********************************/

			/***************************************************************************************
				NUEVO ESTILOS DE JAIME, Los de la Ventana Mini de Habitaciones.
				Tambien hay que poner antes el 'background-color' que el 'color',
				sino no funcionara la seleccion de colores correctamente
			****************************************************************************************/
			fwrite($archivo,".nom_hab_mini {\r\n\ttext-align: center; \r\n\tfont-size: 10px; \r\n\tfont-weight: bold; \r\n\tbackground-color: ".$_REQUEST['f']."; \r\n\tcolor: ".$_REQUEST['numHab']."; \r\n\tcursor:default; \r\n\t}\r\n");
			fwrite($archivo,".no_cama_mini {\r\n\twidth: 15px; \r\n\tfont-size: 1px;  \r\n\tbackground-color: ".$_REQUEST['fGen']."; \r\n\tcursor:default; \r\n\t}\r\n");
			fwrite($archivo,".cama_libre_mini {\r\n\twidth: 15px; \r\n\tfont-size: 1px; \r\n\tbackground-color: ".$_REQUEST['l']."; \r\n\tcursor: default; \r\n\t}\r\n");
			fwrite($archivo,".cama_reservada_mini {\r\n\tfont-size: 1px; \r\n\twidth: 15px; \r\n\tbackground-color: ".$_REQUEST['r']."; \r\n\tcursor: pointer; \r\n\t}\r\n");
			fwrite($archivo,".cama_ocupada_mini {\r\n\tfont-size: 1px; \r\n\twidth: 15px; \r\n\tbackground-color: ".$_REQUEST['o']."; \r\n\tcursor: pointer; \r\n\t}\r\n");
			fwrite($archivo,"div.div_tabla_habitacion_mes {\r\n\twidth: 343px; \r\n\tbackground-color: ".$_REQUEST['fGen']."; \r\n\tborder: 1px solid #3F7BCC; \r\n\toverflow-x: scroll; \r\n\t}\r\n");
			fwrite($archivo,"div.div_tabla_habitacion_mes table {\r\n\tbackground-color: ".$_REQUEST['fGen']."; \r\n\t}\r\n");
			fwrite($archivo,"div.div_tabla_habitacion_mes table tr {\r\n\theight: 15px; \r\n\t}\r\n");
			/**************************
				 FIN NUEVO JAIME 
			***************************/
			
			fclose($archivo);// Cierro el fichero
			}
	}

echo "<meta http-equiv='refresh' content='0;url=?pag=gi_skins.php'>";// Actualizo la pagina y vuelvo a gi_skins.php
?>