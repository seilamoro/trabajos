<?php
	function reducir($imagen,$altura)
		{
		$dir_thumb = "miniaturas/"; // Carpeta en la que se guardara la miniatura de la imagen
		$prefijo_thumb = "mini_"; // Prefijo que se le añade a la miniatura. Si la original es "imagen.jpg", la miniatura sera "mini_imagen.jpg"

		$camino_nombre=explode("/",$imagen);		
		$nombre=end($camino_nombre);// Aquí guardo el nombre de la imagen.
	
		$camino=substr($imagen,0,strlen($imagen)-strlen($nombre));// Aquí se guarda la ruta especificada para buscar la imagen.
		
		// Intentamos crear el directorio de las miniaturas, si no existiera previamente.
		if (!file_exists($camino.$dir_thumb))
			mkdir ($camino.$dir_thumb, 0777) or die("No se ha podido crear el directorio $dir_thumb");
		
		$datos = getimagesize($camino.$nombre) or die("Problemas con $camino$nombre<br>n"); // Obtengo el tamaño de la imagen original
			
		// Intento escalar la imagen original a la medida que me interesa
		if($datos[1]>$altura)
			{
			$ratio = ($datos[1] / $altura);
			$anchura = round($datos[0] / $ratio);
			}
		else
			{
			copy($imagen,$camino.$dir_thumb.$prefijo_thumb.$nombre);
			return;
			}
			
		// Aquí comprovamos que la imagen que queremos crear no exista previamente
		if (!file_exists($camino.$dir_thumb.$prefijo_thumb.$nombre))
			{
			$ext=substr($imagen,-4);
			switch($ext)
				{
				case ".jpg":
					$img = imagecreatefromjpeg($camino.$nombre) or die("No se encuentra la imagen $camino$nombre<br>n");
					break;
				case "jpeg":
					$img = imagecreatefromjpeg($camino.$nombre) or die("No se encuentra la imagen $camino$nombre<br>n");				
					break;
				case ".gif":
					$img = imagecreatefromgif($camino.$nombre) or die("No se encuentra la imagen $camino$nombre<br>n");				
					break;
				case ".png":
					$img = imagecreatefrompng($camino.$nombre) or die("No se encuentra la imagen $camino$nombre<br>n");				
					break;
				}

			$thumb = imagecreatetruecolor($anchura,$altura);// Esta será la nueva imagen reescalada
			
			imagecopyresampled ($thumb, $img, 0, 0, 0, 0, $anchura, $altura, $datos[0], $datos[1]); // Con esta función la reescalamos
			
			// Guardo la miniatura con el nombre y en el lugar que fije en '$dir_thumb'.
			switch($ext)
				{
				case ".jpg":
					imagejpeg($thumb,$camino.$dir_thumb.$prefijo_thumb.$nombre);
					break;
				case "jpeg":
					imagejpeg($thumb,$camino.$dir_thumb.$prefijo_thumb.$nombre);				
					break;
				case ".gif":
					imagegif($thumb,$camino.$dir_thumb.$prefijo_thumb.$nombre);			
					break;
				case ".png":
					imagepng($thumb,$camino.$dir_thumb.$prefijo_thumb.$nombre);			
					break;
				}
			
			}

		}
?>


<HTML>
<HEAD>
<title>Galería de Fotos</title>
<META NAME="Generator" CONTENT="EditPlus">

	
	<link rel="StyleSheet" href="../css/css_principal.css" type="text/css">
	<link rel="StyleSheet" href="../css/listados.css" type="text/css">
	<SCRIPT language="javaScript">
	var Pausa=0;
	var Play=1;
	
	var currentImage=1;
	var buttonStatus=Pausa;
	var toPlay=null;
	var numImagenes=0;
	var direccion;
	var img=null;
	var num_renovar;//Para validar cuando hay q renovar la pagina padre
	var preImgPlay=new Image();
	preImgPlay.src="../imagenes/botones/play.gif";
	
	var preImgPause=new Image();
	preImgPause.src="../imagenes/botones/stop.gif";
	
	
	
	

	//Function confirmar
	function Confirmar(centro,imagen)
	{
	  //alert("Centro"+centro);
	  //alert("imagen"+imagen);
	if(img=null)
	{
	  alert("hola");
	}
   				 	var entrar = confirm("¿Está seguro que desea eliminar la imagen?");
					if ( !entrar ) 
					{return false;}
					else {
					  if(imagen=="undefined")
					  {
					    alert("no se puede eliminar");
						return false;
					}
					  
					  
					 location.href="galeria.php?hacer=del&Id_Centro="+centro+"&img="+imagen;
					  }

    		

	  
	}
	

	
	function cambioimg(num,hola){
	  
	  
		if(!document.Formulario.seleccion.disabled)	{
		if(document.Formulario.seleccion.checked) {
			document.getElementById(hola).className = 'unchecked';
			document.Formulario.seleccion.checked = false;
		}
		else {
			document.getElementById(hola).className = 'checked';
			document.Formulario.seleccion.checked = true;
		}	
	  
	  }
	  
	  
	  if(document.Formulario.seleccion.checked) {
	    	
	    
	    	var grande=img.replace("miniaturas/mini_","");
							var rutas=grande;
						
			var entrar = confirm("¿Está seguro que desea seleccionar esta imagen como la principal?");
					if ( !entrar ) 
					{
						
					  return false;
					  } else {
					    
					    
							
					window.location.href("galeria.php?hacer=sel&img="+rutas+"&Id_Centro="+num);	
					
					   
					 		    
					   //window.opener.document.location.href("../principal.php?pag=instalaciones.php&id="+num);
					    
					    
					}
					
					
					
		}
	  
	  
	  
	  
	}
	
	
	
	function renovar(num,num_renovar){
		if(num_renovar==1){
			num_renovar=0;
			//setTimeout(renovar(num,num_renovar),70500);
			
		}
		//window.opener.document.location.href("../principal.php?pag=instalaciones.php);
		//alert("entra?"+num);
		window.opener.document.location.href("../principal.php?pag=instalaciones.php&id="+num);
	}
	
	
	
	
	//Funcion que carga el numero de imagenes para el array
	function Total_Img(num,n,d)
		{
		numImagenes=num;
		
		Formulario.imgFoto.src=d+n;
//		document.images["imgFoto"].width=200;
//		document.images["imgFoto"].height=200;
		}

	var lstImages=new Array(numImagenes);  // Array donde van las imágenes
	var lstImagesWidth=new Array(numImagenes); // Array ancho de imágenes
	var lstImagesHeight=new Array(numImagenes); // Array alto de imágenes
	var lstImagesWidthReal=new Array(numImagenes); // Array ancho de imágenes
	var lstImagesHeightReal=new Array(numImagenes); // Array alto de imágenes

	
	//Funcion que carga en un array los datos de las imagenes(el indice,nombre y direccion)
	function CargarImagenes(num,nom,d)
		{	
		direccion=d;
		lstImages[num]=nom;	
		lstImages[num].src=d+nom;
		lstImagesWidth[num]=30;
	    lstImagesHeight[num]=30;
	    lstImagesWidthReal[num]=30; 
	    lstImagesHeightReal[num]=30; 
		}
	//Funcion que carga la imagen por defecto de los centros. Esta funcion se usara cuando un centro no tenga imagenes	
	function CargarSinImagenes()
		{//alert("entreee");
		window.resizeTo(605,628);
		Formulario.imgFoto.src="../imagenes/fotos/null.jpg";
		document.getElementById('ampliar').href="#";
		document.getElementById('ampliar').style.cursor="default";
		document.getElementById('ampliar').title="No hay imagenes de la instalación";
		document.getElementById('etiqueta').style.display='none';
		
		
	
		document.getElementById('eliminar').style.display='none';
		document.getElementById('sele').style.display='none';
		<?$sineliminar=0;?>
	

}
	
	function CargarSinImagenesg()
		{//alert("entreee");
		window.resizeTo(605,600);
		Formulario.imgFoto.src="../imagenes/fotos/null.jpg";
		
		document.getElementById('etiqueta').style.display='none';
		document.getElementById('sele').style.display='none';
		
	
		
		
		<?$sineliminar=0;?>
	

		}

	//Funcion para evaluar el cambio de la imagen(se le pasa el numero de imagen que le corresponde cargar)
	function CambioImg(_numImage)
	
		{
		  
		// Cambia de imágen
  		if((_numImage>=1)&&(_numImage<=numImagenes))
		  	{
		  	  //alert("llega4?");
			lstImages[_numImage].src=direccion+lstImages[_numImage];
			img=direccion+lstImages[_numImage];
		//	alert("entra");
			Formulario.etiqueta.value=_numImage + " de " +numImagenes ;
		    Formulario.imgFoto.src=direccion+lstImages[_numImage];
//		    document.images["imgFoto"].width=200; 
//		    document.images["imgFoto"].height=200; 
		    document.Formulario.FotoEnlace.value=lstImages[_numImage]; 
		    document.Formulario.FotoEnlaceAncho.value = lstImagesWidthReal[_numImage]; 
		    document.Formulario.FotoEnlaceAlto.value = lstImagesHeightReal[_numImage];
			if (currentImage==numImagenes)
				{
				  <?if(!isset($_GET['grafico'])){?>
					document.images["imgPlay"].src=preImgPause.src;
					document.images["imgPlay"].title='stop';
					<?}?>
				}
			} else { Formulario.etiqueta.value=_numImage + " de " +numImagenes ;}
		}

	<?if(!isset($_GET['grafico'])){?>
	function PlayImg()
		{
		if(buttonStatus==Pausa )
			{
			document.images["imgPlay"].src=preImgPause.src;
		    document.images["imgPlay"].title='stop';
		    buttonStatus=Play;
		    Movimiento();
			}
		else
			PauseImg();
		}

	function PauseImg()
		{
		buttonStatus=Pausa;
		clearTimeout(toPlay);
		if (currentImage<numImagenes)
			{
			document.images["imgPlay"].src=preImgPlay.src;
			document.images["imgPlay"].title='play';
  			}
  		}
  	<?}?>

	// Muestra la imagen anterior
	function AntImg()
		{  
		currentImage=currentImage-1;
		if(currentImage<=0){
		  currentImage=1;
		}
		CambioImg(currentImage); 
			<?if(!isset($_GET['grafico'])){?>
			PauseImg();
			<?}?>
		}

	function SigImg()
		{ 
		currentImage=currentImage+1;
		if(currentImage>numImagenes)
			{
			currentImage=numImagenes;
			}
		CambioImg(currentImage);
		}

	// Sirve para cambiar la imagen, cada 3000 msg = 3 sg
	function Movimiento()
		{
		SigImg();
  		toPlay=setTimeout("Movimiento()",3000);
		}

	function Inic()
		{
		  //alert("llega2?");
  		CambioImg(currentImage);
  		
  	//	PlayImg();
		}

	function Ventana(num)
		{
		//location.href="img_grande.php?img="+img;
		//window.open("img_grande.php?img="+img+"&cen="+num,"ventana2"," height=600,width=650, scrollbars=1, menubar=no, location=no, resizable=1");
		var grande=img.replace("miniaturas/mini_","");
		//window.open("img_grande.php?img="+grande+"&cen="+num,"ventana2","scrollbars=1,menubar=no,location=no,resizable=no,fullscreen=yes,menubar=no");
		window.open("../Centro"+num+"/"+grande);
		}
	function ruta(num,graf,hola)
	{
	  
	  
	  if(!document.Formulario.seleccion.disabled)	{
		if(document.Formulario.seleccion.checked) {
			document.getElementById(hola).className = 'unchecked';
			document.Formulario.seleccion.checked = false;
		}
		else {
			document.getElementById(hola).className = 'checked';
			document.Formulario.seleccion.checked = true;
		}	
	  
	  }
	  
	  
	  	if(document.Formulario.seleccion.checked){
	  	var grande=img.replace("miniaturas/mini_","");
		var rutas=grande;
		window.opener.document.getElementById(graf).fondo_img.value=rutas;
		window.close();
		}
	}
	
	
	function mensaje(texto)
		{
		setTimeout("alert(\""+texto+"\")",500);
		}
	
//Trozo para redimensionar la pagina
	function disminuirindice(){	
		//window.moveTo(800,200);
		window.resizeTo(605,700);
		
		
	}
	
	function disminuirgrafico(){	
	
		window.resizeTo(605,650);
		
		
	}

	
	
//----//
</SCRIPT>
</HEAD>





	<BODY background="../imagenes/fondos/fondot.jpg" >



<?php


if($_GET['indice']){
  
 echo "	<script>	
 			disminuirindice();
		</script>";	

$centro_f=substr($_GET['indice'],21,1);	//
$centro_f=substr($_GET['indice'],21,strlen($_GET['indice']));
list($numero,$nada) = split('/',$centro_f);
$centro_f= $numero;
$long_f=strlen($_GET['indice']);
$total_cortar=$long_f-28;
//$img_f=substr($_GET['indice'],30,$total_cortar);
list($ext,$imag) = split('_',$_GET['indice']);
list($imag_f,$i2)=explode('.',$imag);


}

if($_GET['grafico']){
  echo "<script>	
 			disminuirgrafico();
		</script>";	
  
	}
	
	
	if(isset($_GET['hacer']))
		{
		switch($_GET['hacer'])
			{
			  
			  
			  
			case 'sel':// Seleccionar una imagen y hacer el cambio de nombres
			
			
					//ruta de la primera
					$direcc="../imagenes/fotos/Centro".$_GET['Id_Centro']."/";
					$dir = @opendir($direcc);//abrimos el directorio
					$cont=0;
					while($elemento =readdir($dir))
					{//recogemos cada uno de las imagenes que hay en esa carpeta					
						$trozo=substr($elemento, -4);  //compruebo q sean jpg, jpeg, gif, bmp o png
						if(strtolower($trozo)=='.jpg' || strtolower($trozo)=='jpeg' || strtolower($trozo)=='.gif' || strtolower($trozo)=='.png')
						{	
						$cont++;
						$listImage[$cont]=$elemento;				
						}  
	    			}			
					$primera=$listImage[1];
					$extensionprim=explode(".",$primera); // extensión de la primera imagen
					
					
		
			
					$ruta=str_replace("/","\\",$_GET['img']);
				
					$extensionrec=explode(".",$ruta); // extensión de la imagen seleccionada que se pondrá a la primera $extensionrec[3]
					$extensionmini=explode("\\",$extensionrec[2]);
					
				if($ruta!="..\\imagenes\\fotos\\Centro".$_GET['Id_Centro']."\\".$primera)
				{
					
					//Renombrar la primera imagen a su ruta mas aux
					//print("COPY ..\\imagenes\\fotos\\Centro".$_GET['Id_Centro']."\\".$primera." ..\\imagenes\\fotos\\Centro".$_GET['Id_Centro']."\\".$primera."aux");
					copy("..\\imagenes\\fotos\\Centro".$_GET['Id_Centro']."\\".$primera ,"..\\imagenes\\fotos\\Centro".$_GET['Id_Centro']."\\".$primera."aux");
					copy("..\\imagenes\\fotos\\Centro".$_GET['Id_Centro']."\\miniaturas\\mini_".$primera , "..\\imagenes\\fotos\\Centro".$_GET['Id_Centro']."\\miniaturas\\mini_".$primera."aux");
					
					system("del/Q ..\\imagenes\\fotos\\Centro".$_GET['Id_Centro']."\\".$primera);
					system("del/Q ..\\imagenes\\fotos\\Centro".$_GET['Id_Centro']."\\miniaturas\\mini_".$primera);
				
				
				
				
				
					
				
					//Renombrar la imagen recibida a la primera
					
					copy( $ruta,"..\\imagenes\\fotos\\Centro".$_GET['Id_Centro']."\\Centro".$_GET['Id_Centro']."_1.".$extensionrec[3]);
					
				copy("..\\".$extensionmini[1]."\\".$extensionmini[2]."\\".$extensionmini[3]."\\miniaturas\\mini_".$extensionmini[4].".".$extensionrec[3] , "..\\imagenes\\fotos\\Centro".$_GET['Id_Centro']."\\miniaturas\\mini_Centro".$_GET['Id_Centro']."_1.".$extensionrec[3]);
					
			
					system("del/Q ".$ruta);
					system("del/Q ..\\".$extensionmini[1]."\\".$extensionmini[2]."\\".$extensionmini[3]."\\miniaturas\\mini_".$extensionmini[4].".".$extensionrec[3]);
					
					
					//Renombrar la imagen aux que era la primera a la ruta de la imagen recibida
				
					copy("..\\imagenes\\fotos\\Centro".$_GET['Id_Centro']."\\".$primera."aux" , "..".$extensionrec[2].".".$extensionprim[1]);
					copy("..\\imagenes\\fotos\\Centro".$_GET['Id_Centro']."\\miniaturas\\mini_".$primera."aux" , "..\\".$extensionmini[1]."\\".$extensionmini[2]."\\".$extensionmini[3]."\\miniaturas\\mini_".$extensionmini[4].".".$extensionprim[1]);
					system("del/Q ..\\imagenes\\fotos\\Centro".$_GET['Id_Centro']."\\".$primera."aux");
					system("del/Q ..\\imagenes\\fotos\\Centro".$_GET['Id_Centro']."\\miniaturas\\mini_".$primera."aux");
				}	
					echo "
                		<script>
                		num_renovar=1;
                		//alert(".$_GET['Id_Centro'].");
						renovar(".$_GET['Id_Centro'].",num_renovar);
                		window.close();
                		
						</script>";

	
	
					break;  
			case 'del': // Eliminar una imagen
				$ruta=str_replace("/","\\",$_GET['img']);
				
				//print($_GET['img']);
				//print($ruta);
				system("del/Q ".$ruta); // Borro la miniatura
				$ruta=str_replace("miniaturas\\mini_","",$ruta);
				system("del/Q ".$ruta); // Borro la imagen original
				break;
			case 'up': // Subir una imagen
				$extension=explode(".",$archivo_name); // Separo el nombre del archivo por '.' para poder obtener la extension
				//print($archivo_name);
				//print($extension[1]);
				$ind=count($extension)-1; // Obtengo el ultimo indice del array en el que almacene el nombre del fichero separado por '.'
				//print($extension[$ind]);	
				if((strtolower($extension[$ind])=="jpg") || (strtolower($extension[$ind])=="jpeg") || (strtolower($extension[$ind])=="gif") || (strtolower($extension[$ind])=="png")) // Compruebo que la extension del archivo es valida (jpg, jpeg, gif o png)
				
					{
/******************************************
** AQUI FALTA PONER EL TAMAÑO DEFINITIVO **
*******************************************/
					$tamMax=3145728; // Fijo el tamaño maximo del archivo a subir. Tiene que ser el mismo que este puesto en el php.ini
					if($archivo_size < $tamMax) // Compruebo que el archivo no excede de tamaño.
						{
						$x=1;
						$exten=array(".jpg",".jpeg",".gif",".png");
						while($x>0)
							{
							$arch="../imagenes/fotos/Centro".$_GET['Id_Centro']."/Centro".$_GET['Id_Centro']."_".$x;
							if($_GET['indice']){//Si vengo de flash, cargo con el parametro que le paso
								$arch="../imagenes/fotos/Centro".$centro_f."/Centro".$centro_f."_".$x;
							}
							$x2=$x;
							for($p=0;$p<count($exten);$p++)
								{
								if(file_exists($arch.$exten[$p]))
									{
									$x++;
									break;
									}
								}
							if($x2==$x)
								$x=0;
							}
						$arch.=".".strtolower($extension[$ind]);
						//print($arch);
					//	print($archivo);
						if(!copy($archivo,$arch))
							echo "<script>
									mensaje('Se ha producido un error al copiar la imagen.\\\\nVuelva a intentarlo');
								</script>";
						else
							reducir($arch,200);
						}
					else //Si el archivo excedia del tamaño maximo
						echo "<script>
								mensaje('El archivo excede del tamaño maximo (".substr(($tamMax/1048576),0,4)." MB)');
							</script>";
					}
				else // Si el archivo no tenia una extension permitida(jpg, jpeg, gif o png) muestro un aviso
					echo "<script>
							mensaje('El formato del archivo no es correcto\\\\nSolo son válidos archivos de imagen (jpg, jpeg, png y gif)');
						</script>";
				break;
			}
		}
?>
<FORM Name="Formulario" method="POST" enctype="multipart/form-data"   action="galeria.php">
	<INPUT Type="Hidden" Name="FotoEnlace" Value="">
	<INPUT Type="Hidden" Name="FotoEnlaceAncho" Value="">
	<INPUT Type="Hidden" Name="FotoEnlaceAlto" Value="">
	<TABLE cellPadding='0' cellSpacing='0' width='100%' bgcolor='#e6e3b6' border=0>

	  	<tr>
	   		<td colspan='2' class='boton_ton' vAlign='bottom' align='center' style='padding:30px'>
	   			<?if(!isset($_GET['grafico'])){?>
				<span style='color:ACA867;font-family:arial;font-size:24px;font-weight:bold;'>GALERÍA DE FOTOS</span>
				<?}?>
			</td>
		</tr>
<?php
	/*** Conexion a la base de datos ***/
	@$db=mysql_pconnect("localhost" , "root" , "");
	if(!$db)
		{
		echo "Error al conectar a la base de datos";
		exit;
		}
/***********************************/
	mysql_select_db("scies");
	
	if(isset($centro_f)){
	  	$cons=mysql_query("select Nombre from centro where Id_Centro='".$centro_f."'");	
	  
	  
	}else {
	  	$cons=mysql_query("select Nombre from centro where Id_Centro='".$_GET['Id_Centro']."'"); // Consulta para obtener el nombre del centro
	  
	}
	

	
	$nombre=mysql_fetch_row($cons); // Guardo el resultado obtenido en el array '$nombre'. Se guardara en la posicion 0
	
	echo "<tr>
			<td colspan='2' align='center' style='padding:10px;color:white;font-size:28px;font-weight:bold;text-align:center;cursor:default;font-style:italic;'>".$nombre[0]."</td>
		</tr>";
	mysql_close($db);
?>
	  	<tr>
			<td colspan='2' align='center'>
				<div style='height:250;width:95%;overflow-y:hidden;overflow-x:auto;'>
					<table height='95%' >
						<tr>
							<td style='vertical-align:middle;'>
									<?if(!isset($_GET['grafico'])){
									  ?>
									<a name='ampliar' id='ampliar' href='javascript:Ventana("<? echo $_GET['Id_Centro']?>")' title='Ampliar Imagen'>
									<?} else{} ?>
								
									
									<IMG style='border:none;' name='imgFoto' /><!-- width='250' height='220' />-->
					<!--<IMG border='0' src='../imagenes/fotos/Centro1/Centro1_1.jpg'/>--><!-- width='250' height='220' />-->
								</a>
							</td>
						</tr>
					</table>
					
				
				</div>
				
			</td>
		</tr>
	
		<tr>
			<td align="center" style="display:block;" id="sele"  style="">
				
				<?if(isset($_GET['indice']))
				//echo "toy aki";
				{?>
				<div class="checkbox" style="position:absolte;margin-left:-105px;" title="Seleccionar imagen como imagen principal">
					<div class="unchecked" id="checks" onclick="cambioimg(<?print($centro_f);?>,this.id);">
						<input  type="checkbox"  id="seleccion" value="1" onclick='' >&nbsp;
					</div>
				
				</div>
				<div style="font-size:14px;color:#ACA867;font-family:arial;font-weight:bold;height:18px;position:absolute;margin-left:-30px;margin-top:-18px;">Seleccionar</div>
				
			
				
			   <?}?>
				<?if(isset($_GET['grafico']))
				{?>
				<div class="checkbox" style="position:absolte;margin-left:-105px;"  title="Seleccionar imagen">
					<div class="unchecked" id="checks"onclick='ruta("<? echo $_GET['Id_Centro'];?>","<? echo $_GET['grafico'];?>",this.id);'>
						<input  type="checkbox" id="seleccion" value="1"  onclick='ruta("<? echo $_GET['Id_Centro'];?>","<? echo $_GET['grafico'];?>",this.id);'> &nbsp;
					</div>
					
					
				</div>
				<div id="sele" style="font-size:14px;color:#ACA867;font-family:arial;font-weight:bold;height:18px;position:absolute;margin-left:-30px;margin-top:-18px;display:block;" onclick="alert(this.id);">Seleccionar
				</div>
			   <?}?>
			</td>
			
		</tr>		

		<tr>
			<td colspan='2' align='center' style=''>
				<input type='text' style='background-color:transparent;border-right:0px;border-bottom: none 0px;border-top: none 0px;border-left:none 0px;color:white;font-size:20;text-align:center;cursor:default;display:block;' size='8' disabled='true' name='etiqueta' />
				<?
			
				if(((!isset($_GET['grafico']))) && ((!isset($_GET['indice']))  )){?>
				<a  id="eliminar"   title='Eliminar esta imagen' onclick='Confirmar(<?php echo $_GET['Id_Centro'];?>,img);' style='font: 14px Georgia;color:white;text-decoration:none;cursor:hand;display:block;position:absolute;margin-left:40px;margin-top:-25px' ><img src="../imagenes/botones/papelera_hover.gif" style='border:none;'></a>
				<?}?>
			</td> 			 
		</tr>
		
		<tr>
			<td colspan='2' align='center' style='padding:10px'>
				<A href='javascript:AntImg()' style='padding:10px;text-decoration:none;'>
					<IMG title='Imagen anterior' border='0' src='../imagenes/botones/volver_hover.gif'>
				</A>
				<?if(!isset($_GET['grafico'])){?>
				<A href='javascript:PlayImg()' style='padding:10px;text-decoration:none;'>
					<IMG name='imgPlay' title='' border='0' src='../imagenes/botones/play.gif'>
				</A>
				<?}?>
				<A href='javascript:SigImg()' style='padding:10px;text-decoration:none;'>
					<IMG title='Siguiente imagen' border='0' src='../imagenes/botones/flecha_sig.gif'>
				</A>
			</td>
		</tr>
		<?if(((!isset($_GET['grafico']))) && ((!isset($_GET['indice'])))){?>
	  	<tr>
			<td align='center'>
			<div style='cursor:default;font-size:14px;color:#ACA867;font-family:arial;font-weight:bold;height:25px;'>AÑADIR UNA IMAGEN A LA GALERÍA</div>
			<input type='file'  name='archivo'   onchange='this.form.action="galeria.php?hacer=up&Id_Centro=<?php echo $_GET['Id_Centro'];?>";this.form.submit();' title='Pulse para seleccionar una imagen y añadirla a la galeria' />
	
			</td>

	  </tr>
	  <tr><td height="">&nbsp;</td></tr>
	  <?}?>
	</TABLE>
</FORM>




<? //Ahora abro la carpeta del centro seleccionado  y cargo las imagenes en una matriz
	if(isset($_GET['Id_Centro']) || isset($_GET['indice'])){//Si vengo de flash, cargo con el parametro que le paso
			
		if($_GET['indice'])	{
			$direcc="../imagenes/fotos/Centro".$centro_f."/miniaturas/";
			
		}else{
			$direcc="../imagenes/fotos/Centro".$_GET['Id_Centro']."/miniaturas/";
		}

		$dir = @opendir($direcc);//abrimos el directorio
		$cont=0;
		while($elemento =readdir($dir))
			{//recogemos cada uno de las imagenes que hay en esa carpeta					
			$trozo=substr($elemento, -4);  //compruebo q sean jpg, jpeg, gif, bmp o png
			if(strtolower($trozo)=='.jpg' || strtolower($trozo)=='jpeg' || strtolower($trozo)=='.gif' || strtolower($trozo)=='.png')
				{	
				$cont++;
				$listImage[$cont]=$elemento;				
				}  
	    }	
		$j=0;
		foreach ($listImage as $llave => $fila) {					
					$numero=substr($fila,strrpos($fila,'_')+1,strrpos($fila,'.')-strrpos($fila,'_')-1);	//selecciono el numero de la imagen				
					$array_numeros[$j]=$numero;//lo meto en el array
					$j++;
				}		
			
			ARRAY_MULTISORT($array_numeros, SORT_ASC,  $listImage);// coloco con lo numeros el array de las imagenes

			


			
		
		$a=$listImage[1];
		//Si le paso desde flash 
		if($_GET['indice']){
			$a="mini_Centro".$centro_f.$img_f;
			
			
		}
		if($cont>0){?>					
					 <script>
						
						Total_Img("<?echo $cont?>","<?echo $a?>","<?echo $direcc?>");// el nº de imagenes para crear un array , y cargo la 1ª imagen
					 </script>
				
						

<?		
			//print($a);
			for($i=1;$i<=$cont;$i++)
				{//pasamos el array a una funcion javascript y carga los datos de la imagen en un array
				$imagen= $listImage[$i-1];
			
					if ($a==$imagen){
						$foto_carga=$i;							
					}?>
				<script>						
					CargarImagenes("<?echo $i?>","<?echo $imagen?>","<?echo $direcc?>");
				</script>	
					
							
			<?}//cierre for?>
			<script>	
			//alert("llega1?");				
				Inic();
			</script>					
			<?if($_GET['indice']){?>
				<script>		
				CambioImg(<?echo $imag_f?>);
				buttonStatus=Pausa;
				currentImage=<?echo $imag_f?>;
				PauseImg();
				</script>
			<?}
			}
			
		else{
		
		if(!isset($_GET['grafico'])){
			echo "<script>
						CargarSinImagenes();
				</script>";
			}
					else {
		 					 echo "<script>
									CargarSinImagenesg();
								</script>";
			}
		}
		closedir($dir); //Cerramos el directorio
?>
				
<?
		}//Fin del IF
?>

	
</BODY>
</HTML>
