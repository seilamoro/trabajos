<HTML>
<HEAD>
	<link rel="StyleSheet" href="../css/css_principal.css" type="text/css">
	<script>
		function cambiar_centro(centro){
			location.href="seleccionar_fondo_grafico.php?Id_Centro=" + centro + "&grafico=<?=$_GET['grafico']?>&centros=<?=$_GET['centros']?>";
		}
		function comprobar_img(graf){
			if(window.opener.document.getElementById(graf).fondo_img.value == "")
				window.opener.document.form1.fondo[0].checked = true;
		}
	</script>
</HEAD>
<BODY bgcolor='#e6e3b6' onUnload="comprobar_img('<?=$_GET['grafico']?>');" >
	<TABLE width="100%" height="100%">
	<tr>
		<td colspan='2' class='boton_ton' vAlign='bottom' align='center' style='padding:20px'>
			<span style='color:ACA867;font-family:arial;font-size:24px;font-weight:bold;'>GALERÍA DE FOTOS</span>
		</td>
	</tr>
	<tr>
		<td align="center" style='padding:15px'>
			<?
			$cent=$_GET['centros'];		
			$centros=split("-",$cent);
			if($_GET['Id_Centro'] == "")
				$_GET['Id_Centro'] = $centros[0];	
			@ $db = mysql_pconnect("localhost" , "root" , "");
			if (!$db){
				echo "Error : No se ha podido conectar a la base de datos";
				exit;
			}
			mysql_select_db("SCIES");
			?>
			<span style='color:ACA867;font-family:arial;font-size:18px;font-weight:bold;'>Instalaciones:</span>
			<select name="ins_disponibles" id="ins_disponibles" onchange="cambiar_centro(this.value)">
			<?
				$sql = "select * from centro";
				$res = mysql_query($sql);
				$num = mysql_num_rows($res);
				for ($i = 0; $i < $num; $i++){
					$fila = mysql_fetch_array($res);
					if(in_array($fila['Id_Centro'],$centros)){
					?>
					<option value="<?=$fila['Id_Centro']?>" <? if($fila['Id_Centro']==$_GET['Id_Centro']) echo 'selected' ?>><?=$fila['Nombre']?></option>
					<?
					}
				}
			?>
			</select>
			
		</td>
	</tr>
	<tr>
		<td>
			<?
				include "./galeria.php";
			?>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	</TABLE>
</BODY>
</HTML>