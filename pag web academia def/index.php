<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CEALQUIMIA</title>
<link rel="stylesheet" type="text/css" href="pag/css.css" media="screen" />
<style type="text/css">

</style>
<script Language="JavaScript">

</script>
</head>

<body >

<form name="formu" id="formu" action="index.php" method="POST">

<div id="cabezera"> <img src="imagenes/DSC_0006.jpg" width="20%" height="100%" /><img src="imagenes/academia.JPG" width="60%" height="100%" /><img src="imagenes/DSC_0007.jpg" width="20%" height="100%" /></div>
<div id="informacion">
<b>Cerramos desde el día 1 al 19 de septiembre (incluido). Para contactar con CEA durante este periodo envíe un correo a cealquimia@gmail.com. Muchas gracias</b>
</div>
<div id="menu">

<div id="m_inicio">
<a href='index.php?pag=inicio.html'  >
<img  src="imagenes/MENU/inicio.gif"  onmouseover="javascript:this.src='imagenes/MENU/inicio2.gif';" 
onmouseout="javascript:this.src='imagenes/MENU/inicio.gif';" border=0/>
</a>
</div>
<div id="m_tarifas">
<a href='index.php?pag=tarifas.html'  >
<img  src="imagenes/MENU/tarifas.gif"  onmouseover="javascript:this.src='imagenes/MENU/tarifas2.gif';" 
onmouseout="javascript:this.src='imagenes/MENU/tarifas.gif';" border=0/>
</a>
</div>
<div id="m_talleres">
<a href='index.php?pag=tludicos.html'  >
<img  src="imagenes/MENU/talleres.gif"  onmouseover="javascript:this.src='imagenes/MENU/talleres2.gif';" 
onmouseout="javascript:this.src='imagenes/MENU/talleres.gif';" border=0/>
</a>
</div>
<div id="m_matricula">
<img  src="imagenes/MENU/matricula.gif"  onmouseover="javascript:this.src='imagenes/MENU/matricula2.gif';" 
onmouseout="javascript:this.src='imagenes/MENU/matricula.gif';" border=0/>
</div>
<div id="m_enlace">
<img  src="imagenes/MENU/enlace.gif"  onmouseover="javascript:this.src='imagenes/MENU/enlace2.gif';" 
onmouseout="javascript:this.src='imagenes/MENU/enlace.gif';" border=0/>
</div>
<div id="m_recursos">
<img  src="imagenes/MENU/recursos.gif"  onmouseover="javascript:this.src='imagenes/MENU/recursos2.gif';" 
onmouseout="javascript:this.src='imagenes/MENU/recursos.gif';" border=0/>
</div>
<div id="m_filosofia">
<a href='index.php?pag=filosofia.html'  >
<img  src="imagenes/MENU/filosofia.gif"  onmouseover="javascript:this.src='imagenes/MENU/filosofia2.gif';" 
onmouseout="javascript:this.src='imagenes/MENU/filosofia.gif';" border=0/>
</a>
</div>
<div id="m_horario">
<img  src="imagenes/MENU/horario.gif"  onmouseover="javascript:this.src='imagenes/MENU/horario2.gif';" 
onmouseout="javascript:this.src='imagenes/MENU/horario.gif';" border=0/>
</div>
</div>
</form>
<?

if(isset($_GET['pag']) && $_GET['pag']!=""){//si existe una pagina para cargar y no esta vacia
						include('pag/'.$_GET['pag']);//incluimos el contenido de la pagina contenida en la variable
					}else{		
							
						include('pag/inicio.html');
					}


?>

</body>
</html>
