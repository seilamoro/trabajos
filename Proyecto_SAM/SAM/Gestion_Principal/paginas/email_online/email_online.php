<?
	session_start();
	@ $db = mysql_pconnect($_SESSION['conexion']['host'], $_SESSION['conexion']['user'],  $_SESSION['conexion']['pass']);
	if (!$db){
		echo "Error : No se ha podido conectar a la base de datos";
		exit;
	}
	mysql_select_db($_SESSION['conexion']['db']);
	$arch = "presupuesto.pdf";
	if (file_exists($arch)){	//si el archivo existe lo borro, para volver a crearlo
		unlink($arch);
	}

	$sql = "select *, pais.nombre_pais as Pais_Alb, provincia.nombre_pro as Provincia_Alb  from albergue inner join pais on ";
	$sql = $sql . "(albergue.id_pais = pais.id_pais) inner join provincia on (albergue.id_pro = provincia.id_pro)";
	$res = mysql_query($sql);
	$datos = mysql_fetch_array($res);//recogo los datos del albergue
	

	require('../fpdf.php');		//--- Inicio de la creación del PDF
				
	$pdf=new FPDF('P','pt','A4');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Times','BU',20);
	$pdf->Image('../../../imagenes/logo.jpg', 50, 50);
	$pdf->Cell(650,125,$datos['Nombre_Alb'],0,1,'C');
	$pdf->SetFont('Times','',12);
	$pdf->Cell(350,30,'',0,0,'C');
	$pdf->Cell(200,30,$datos['Direccion_Alb'],0,1,'D');
	$pdf->Cell(350,30,'',0,0,'C');
	$pdf->Cell(200,30,'C.P. ' . $datos['CP_Alb'] . " - " . $datos['Localidad_Alb'],0,1,'D');
	$pdf->Cell(350,30,'',0,0,'C');
	$pdf->Cell(200,30,$datos['Provincia_Alb'] . " - " . $datos['Pais_Alb'],0,1,'D');
	
	
	$tab = '                    ';	//espacios de tabulación al inicio de cada parrafo	
	$text = 'Estimado/a alberguista:';
	$pdf->MultiCell(0,30,$text);
	$text = $tab . 'Le enviamos información sobre las tarjetas y tarifas de nuestro albergue.';
	$pdf->MultiCell(0,30,$text);

	$alto = 25;
	$pdf->SetFont('Times','B',12);
	$text = 'TARIFAS';
	$pdf->MultiCell(0,30,$text);
	$pdf->SetFont('Times','',12);
	$pdf->MultiCell(0,30,$tab . 'Las tarifas vigentes atendiendo a la edad y al servicio solicitado son las siguientes:');
	$pdf->Ln();

	// obtenemos los tipos de persona
	$query="SELECT Id_Tipo_Persona,Nombre_Tipo_Persona FROM tipo_persona ORDER BY Id_Tipo_Persona ASC";
	@$res = mysql_query($query);
	@$num = mysql_num_rows($res);

	// obtenemos las habitaciones que sean Reservables pero no compartidas, como la triple
	$query="SELECT Nombre_Tipo_Hab FROM tipo_habitacion WHERE Reservable='S' AND Compartida='N';";
	@$res1 = mysql_query($query);
	@$num1 = mysql_num_rows($res1);

	$tamayo = 535 / ($num + $num1 + 1);

	// obtenemos los tipos de persona
	$query="SELECT Id_Tipo_Persona,Nombre_Tipo_Persona FROM tipo_persona ORDER BY Id_Tipo_Persona ASC";
	@$res = mysql_query($query);
	@$num_tipo_persona = mysql_num_rows($res);

		
		$pdf->Cell($tamayo,60,'Edades',1,0,'C');
		
		
		for($i=0;$i<$num_tipo_persona;$i++)
		{
			
			$fila=mysql_fetch_array($res);
			if($fila['Nombre_Tipo_Persona']=="Alberguista"){
			  $fila['Nombre_Tipo_Persona']="Múltiple";
			}
			//Creo un array con los id de tipos de persona, para poder colocar las tarifas en donde corresponden
			$cont_tipo_per[$i]=$fila['Id_Tipo_Persona'];	
			
			
			$pdf->Cell($tamayo,60,$fila['Nombre_Tipo_Persona'],1,0,'C');
			
			
		}
		
		// obtenemos las habitaciones que sean Reservables pero no compartidas, como la triple
		$query="SELECT Nombre_Tipo_Hab,Id_Tipo_Hab FROM tipo_habitacion WHERE Reservable='S' AND Compartida='N';";
		
		@$res = mysql_query($query);
		@$num_tipo_hab = mysql_num_rows($res);

		for($i=0;$i<$num_tipo_hab;$i++)
		{
			$fila=mysql_fetch_array($res);	
			if($i != $num1-1)
				$pdf->Cell($tamayo,60,$fila['Nombre_Tipo_Hab'],1,0,'C');
			else
				$pdf->Cell($tamayo,60,$fila['Nombre_Tipo_Hab'],1,1,'C');
					
		}
		
	   
		$query="SELECT Nombre_Edad,Id_Edad from edad";
		@$res_edad = mysql_query($query);
		@$num_edad = mysql_num_rows($res_edad);
		for($i=0;$i<$num_edad;$i++)
			{
		   
			$fila_edad=mysql_fetch_array($res_edad);
			$pdf->Cell($tamayo,60,$fila_edad['Nombre_Edad'],1,0,'C');
		   
		   
			for($j=0;$j<$num_tipo_persona;$j++)
			{	
				
				$query="SELECT DISTINCT Tarifa,tarifa.Id_Edad,tarifa.Id_Tipo_Persona
					FROM tarifa
				   INNER JOIN tipo_habitacion ON tipo_habitacion.Id_Tipo_Hab = tarifa.Id_Tipo_Hab
						 AND NOT (tipo_habitacion.Reservable='S' AND tipo_habitacion.Compartida='N')
					WHERE Id_Edad=".$fila_edad['Id_Edad']."  and tarifa.Id_Tipo_Persona=".$cont_tipo_per[$j]." ORDER BY tarifa.Id_Tipo_Persona ASC";

			@$res = mysql_query($query);
			@$num = mysql_num_rows($res);
				
			   $fila=mysql_fetch_array($res);
			   if ($num==0){
				   $precio='0';
			   }
			   else{
				   $precio = $fila['Tarifa'];
			   }
			 
			   
					$pdf->Cell($tamayo,60,$precio,1,0,'C');
				
			}
			$query="SELECT DISTINCT Tarifa,tarifa.Id_Edad
					FROM tarifa
					INNER JOIN tipo_habitacion ON tipo_habitacion.Id_Tipo_Hab = tarifa.Id_Tipo_Hab
						  AND (tipo_habitacion.Reservable='S' AND tipo_habitacion.Compartida='N')
					WHERE Id_Edad=".$fila_edad['Id_Edad'].";";
			@$res = mysql_query($query);
			@$num = mysql_num_rows($res);
			for($j=0;$j<$num_tipo_hab;$j++)
			{
			   $fila=mysql_fetch_array($res);
			   if ($num==0) $precio='0';
			   else $precio = $fila['Tarifa'];
			   $pdf->Cell($tamayo,60,$precio,1,1,'C');
			}
			
		}
															
	$pdf->Ln();
	$text = $tab . $tab . '* Grupo: Mínimo diez personas.';
	$pdf->MultiCell(0,30,$text);
	$text = $tab . $tab . '* Triple: Habitaciones individuales de dos o tres camas.';
	$pdf->MultiCell(0,30,$text);
	$pdf->AddPage();
	if($_GET['dif'] >= 1){
		$pdf->Ln();
		$pdf->SetFont('Times','B',12);
		$text = 'RESERVAS';
		$pdf->MultiCell(0,30,$text);
		$pdf->SetFont('Times','',12);
		$text = $tab . 'A partir de la fecha de reserva el grupo dispone de';
		if($_GET['dif'] > 1 && $_GET['dif'] <= 7)
			$text .= ' 24 horas ';
		else
			$text .= ' 7 días ';
		$text .= 'para hacer  el ingreso de la fianza correspondiente al ' . $datos['Fianza'] . '% del ';
		$text .= 'importe de la actividad y así dar por formalizada la reserva. Este ingreso no se puede realizar por tarjeta de crédito.';
		$pdf->MultiCell(0,30,$text);
		$text = $tab . 'Una vez realizado el ingreso se enviará por fax, al número ' . $datos['Fax_Alb'] . ', el resguardo correspondiente facilitado por el banco ';
		$text .= ' en el que deberá figuar claramente el D.N.I. y el nombre de la persona que realizó la reserva y la fecha de llegada al albergue.';
		$pdf->MultiCell(0,30,$text);
		$text = $tab . 'Todos los grupos a la llegada al albergue deberán traer un relación de las personas que componen el grupo, diferenciadas ';
		$text .= 'por sexos y en el que figure el D.N.I., nombres y apellidos, fecha de nacimiento, la fecha de expedición del D.N.I. y la nacionalidad.';
		$pdf->MultiCell(0,30,$text);
		$text = $tab . 'Muy importante!!!: Cualquier modificación en cuanto al número de personas, la fecha de llegada o la fecha de salida ';
		$text .= 'se notificará vía telefónica al número ' . $datos['Tfno1_Alb'] . ' o al ' . $datos['Tfno2_Alb'] . '.';
		$pdf->MultiCell(0,30,$text);
		$pdf->Ln();
		$pdf->SetFont('Times','B',12);
		$text = 'Datos Bancarios:';
		$pdf->MultiCell(0,30,$text);
		$pdf->SetFont('Times','',12);
		$text = $tab . 'Titular: Ayuntamiento Municipal de León';
		$pdf->MultiCell(0,30,$text);
		$text = $tab . 'Entidad: ' . $datos['Entidad'];
		$pdf->MultiCell(0,30,$text);
		$text = $tab . 'Cógido WIN: ' . $datos['Win'];
		$pdf->MultiCell(0,30,$text);
		$text = $tab . 'Cógido IBAN: ' . $datos['Iban'] . ' (Transferencias desde el extranjero)';
		$pdf->MultiCell(0,30,$text);
		
		$pdf->Ln();
		$pdf->SetFont('Times','U',12);
		$pdf->Cell(134,30,'ENTIDAD','LT',0,'C');
		$pdf->Cell(134,30,'SUCURSAL','T',0,'C');
		$pdf->Cell(134,30,'DC','T',0,'C');
		$pdf->Cell(134,30,'Nº CUENTA','RT',1,'C');
		$pdf->SetFont('Times','',12);
		$pdf->Cell(134,30, substr($datos['Num_Cuenta'],0,4),'LB',0,'C');
		$pdf->Cell(134,30, substr($datos['Num_Cuenta'],4,4),'B',0,'C');
		$pdf->Cell(134,30, substr($datos['Num_Cuenta'],8,2),'B',0,'C');
		$pdf->Cell(134,30, substr($datos['Num_Cuenta'],10,10),'RB',1,'C');
	}
	
	$pdf->Ln();
	$pdf->SetFont('Times','B',12);
	$text = 'TARJETAS DE ALBERGUISTA';
	$pdf->MultiCell(0,30,$text);
	$pdf->SetFont('Times','',12);
	$text = $tab . 'Siempre le vamos a exigir la posesión del D.N.I. o pasaporte y la tarjeta de alberguista. En caso de no disponer de está última, podrá conseguirla en recepción abonando el importe correspondiente reflejado en la siguiente tabla:';
	$pdf->MultiCell(0,30,$text);
	
	$pdf->Ln();
	$pdf->Cell(134,40,' ',0,0,'C');
	$pdf->Cell(134,40,'Tipo de carnet',1,0,'C');
	$pdf->Cell(134,40,'Precio (en Euros)',1,0,'C');
	$pdf->Cell(134,40,' ',0,1,'C');
	$sql = "select * from reaj";
	$res = mysql_query($sql);
	$num = mysql_num_rows($res);
	for($x = 0; $x < $num; $x++){
		$fila = mysql_fetch_array($res);
		$pdf->Cell(134,40,' ',0,0,'C');
		$pdf->Cell(134,40,$fila['Nombre_Carnet'],1,0,'C');
		$pdf->Cell(134,40,$fila['Precio_Carnet'],1,0,'C');
		$pdf->Cell(134,40,' ',0,1,'C');
	}
	
	$pdf->Ln();
	$text = 'Atentamente les saluda,';
	$pdf->MultiCell(0,30,$text);
	$pdf->Cell(536,30,$datos[Nombre_Alb],0,1,'C');
	$pdf->Ln();
	$meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

	$mes = strftime("%m",time()) / 1;
	$dia = strftime("%d",time()) / 1;
	$pdf->Cell(536,30, $datos[Localidad_Alb] . ', ' . $dia . ' de ' . $meses[$mes] . ' de ' . strftime("%Y",time()),0,1,'C');
	
	$pdf->Output('presupuesto.pdf',F); //--- Fin de la creación del PDF
	
	$meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

	$cuerpo = '<body><table width="100%" border="0" align="center" bgcolor="#FFFFCC" cellpadding="5" cellspacing="5"><tr>';
	$cuerpo = $cuerpo . '<td width="80%" align="center"><b>' . $_GET['nom'] . ' ' . $_GET['ape1'] . ' ' . $_GET['ape2'] . '</b></td><td align="center">';
	$cuerpo = $cuerpo . '<img src="cid:logo" width="79" height="75"></td></tr><tr>';
	$cuerpo = $cuerpo . '<td colspan="2">Se le comunica que su solicitud de reserva se ha completado con &eacute;xito.</td>';
	$cuerpo = $cuerpo . '</tr><tr><td colspan="2"><table width="95%" border="0" align="center"><tr> ';
	$cuerpo = $cuerpo . '<td><strong>D.N.I.:</strong></td><td>' . $_GET['dni'] . '</td>';
	$cuerpo = $cuerpo . '</tr><tr> <td><strong>Nombre:</strong></td><td>' . $_GET['nom'] . '</td></tr>';
	$cuerpo = $cuerpo . '<tr><td><strong>Apellidos:</strong></td><td>' . $_GET['ape1'] . ' ' . $_GET['ape2'] . '</td></tr><tr>';
	$cuerpo = $cuerpo . '<td><strong>Tel&eacute;fono:</strong></td><td>' . $_GET['tel'] . '</td>';
	$cuerpo = $cuerpo . '</tr><tr><td><strong>E-mail:</strong></td><td>' . $_GET['email'] . '</td></tr>';
	$cuerpo = $cuerpo . '<tr><td><strong>Fecha Llegada:</strong></td><td>' . $_GET['lleg'] . '</td></tr>';
	$cuerpo = $cuerpo . '<tr><td><strong>Fecha Salida:</strong></td><td>' . $_GET['sal'] . '</td></tr>';
	$cuerpo = $cuerpo . '<tr><td><strong>N&ordm;. de Personas:</strong></td><td>' . $_GET['cam'] . '</td></tr>';
	$cuerpo = $cuerpo . '<tr><td><strong>Camas Individuales:</strong></td><td>' . $_GET['ind'] . '</td></tr>';
	$cuerpo = $cuerpo . '<tr><td><strong>Camas Compartidas:</strong></td><td>' . $_GET['comp'] . '</td></tr>';
	$cuerpo = $cuerpo . '<tr><td><strong>Localizador:</strong></td><td>' . $_GET['loc'] . '</td></tr>';
	$cuerpo = $cuerpo . '</table></td></tr>';
	if($_GET['dif'] >= 1){
		$cuerpo = $cuerpo . '<tr><td colspan="2">FIANZA: ' . $datos['Fianza'] . '% EN EUROS (El ingreso de la fianza es imprescindible para la formalizaci&oacute;n de la reserva).<br>';
		if($_GET['dif'] > 1 && $_GET['dif'] <= 7)
			$cuerpo = $cuerpo . 'Si la fecha de llegada al Albergue Municipal de Le&oacute;n es superior a un d&iacute;a e inferior a siete el ingreso se debe hacer en menos de 24 horas';
		else
			$cuerpo = $cuerpo . 'Si la fecha de llegada al Albergue Municipal de Le&oacute;n es superior a siete d&iacute;as el ingreso se debe hacer antes de 7 d&iacute;as';
	}
	$cuerpo = $cuerpo . '</td></tr><tr><td colspan="2">Se adjunta un archivo en pdf con informaci&oacute;n sobre las tarifas del albergue.';
	$cuerpo = $cuerpo . '</td></tr><tr><td colspan="2">Para cualquier duda o consulta no dude en ponerse en contacto con nosotros mediante ';
	$cuerpo = $cuerpo . 'correo electr&oacute;nico a la direcci&oacute;n ' . $datos[Email_Alb] . ' o llamando a tel&eacute;fono ' . $datos[Tfno1_Alb] . ' &oacute; al ' . $datos[Tfno2_Alb] . '.';
	$cuerpo = $cuerpo . '</td></tr><tr><td colspan="2"><blockquote><blockquote><p><br>Muchas gracias por su confianza.</p>';
	$cuerpo = $cuerpo . '<p>' . $datos[Nombre_Alb] . '</p>';
	$cuerpo = $cuerpo . '<p>' . $datos[Localidad_Alb] . ', a ' . strftime("%d",time()) . ' de ' . $meses[strftime("%m",time())] . ' de ' . strftime("%Y",time()) . '.</p></blockquote></blockquote></td></tr></table></body>';


	require("../class.phpmailer.php");	//esta clase permite enviar emails con archivos adjuntos
	$mail = new PHPMailer();//instanciamos un objeto de la clase phpmailer 
	$mail->Mailer = "smtp";
	$mail->SMTPAuth = true;

	//Indicamos cual es nuestra dirección de correo y el nombre que queremos que vea el usuario que lee nuestro correo
	$mail->From = $datos[Email_Alb];
	$mail->FromName = $datos[Nombre_Alb];
	$mail->Username = $_SESSION['email_online']['usuario'];
	$mail->Password = $_SESSION['email_online']['password'];
	$mail->Host = $_SESSION['email_online']['servidor'];

	$mail->Timeout=30; //el valor por defecto 10 de Timeout es un poco escaso, por tanto lo pongo a 30  
	
	$mail->AddAddress($_GET['email']); //Indicamos cual es la dirección de destino del correo
	$mail->AddEmbeddedImage('../../../imagenes/logo.gif', 'logo', 'logo.gif');
	
	$mail->Subject = "Verificación de reserva on-line"; //Asignamos asunto y cuerpo del mensaje. el cuerpo del mensaje lo ponemos en formato html
	$mail->Body = $cuerpo;
	
	$mail->AltBody = $cuerpo; //Definimos AltBody por si el destinatario del correo no admite email con formato html 
	
	//genero el presupuesto que se envia con el correo

	$mail->AddAttachment ($arch, "presupuesto.pdf"); //adjunto el archivo
	$mail->Send(); //se envia el mensaje
	mysql_close($db);
	echo "<script>window.close();</script>";
?>
