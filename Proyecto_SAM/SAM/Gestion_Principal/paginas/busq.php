<script src="./paginas/capas.js" type="text/javascript"></script>
<?PHP
//abro la base de datos
@ $db = mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
			if (!$db){
				echo ("Error al acceder a la base de datos, intentelo mas tarde");
				exit();
			}
			mysql_select_db($_SESSION['conexion']['db']);

//session_start();

if($eliminar_anio_fact){
	$suprimir="DELETE FROM factura WHERE SUBSTRING(Fecha_Factura,1,4)='".$eliminar_anio_fact."';";
		//echo $suprimir;
	mysql_query($suprimir);
}

//vacio todas las sessiones
if($busqueda){
	$_SESSION['retorno']=null;
	$_SESSION['orreaj']=array();
	$_SESSION['bus']=array();
	$_SESSION['orfacturas']=array();
	$_SESSION['oralber']=array();
	$_SESSION['orgrupo']=array();
	$_SESSION['orincidencia']=array();
	$_SESSION['orpere']=array();
	$_SESSION['orreser']=array();
	$_SESSION['orreservas_on']=array();
}
if($limpiar){
	$_SESSION['retorno']=$limpiar;
}
if($rese==true){
	$_SESSION['bus']=array();
	$rese=false;
}

if($facturando){
	$_SESSION['retorno']=2;
	$_SESSION['bus']['opc2']=$facturando;
}

?>

<script language="Javascript">

/////////////////////////////////////////////////////////////////////////////////////
	function eliminar_factura(aniete) {
		var response = window.confirm('¿Desea eliminar las facturas del año '+aniete+'?');
		if (response) {	
			window.location='?pag=busq.php&eliminar_anio_fact='+aniete+'';
		}else{
			window.alert('Ha cancelado la operación');
		}
	}
/////////////////////////////////////////////////////////////////////////////////////

//funciones para mostrar las capas segun la opcion q elijas
function mostrar_alber(){
	
		ocultar();
		document.getElementById("alber").style.visibility = "visible";
	
}

function mostrar_reaj(){
	
		ocultar();
		document.getElementById("reaj").style.visibility = "visible";
	
}

function mostrar_incidencias(){

	ocultar();
	document.getElementById("incidencias").style.visibility = "visible";

}

function mostrar_facturas(){

	ocultar();
	document.getElementById("facturas").style.visibility = "visible";

}

function mostrar_reservas(){

	ocultar();
	document.getElementById("reservas").style.visibility = "visible";

}

function mostrar_reservas_on(){

	ocultar();
	document.getElementById("reservas_on").style.visibility = "visible";

}

function mostrar_pere(){

	ocultar();
	document.getElementById("pere").style.visibility = "visible";

}

function mostrar_grupo(){

	ocultar();
	document.getElementById("grupo").style.visibility = "visible";

}

//funcion para ocultar las capas de las opciones
function ocultar(){

	document.getElementById("alber").style.visibility = "hidden";
	document.getElementById("pere").style.visibility = "hidden";	
	document.getElementById("incidencias").style.visibility = "hidden";
	document.getElementById("facturas").style.visibility = "hidden";
	document.getElementById("reservas_on").style.visibility = "hidden";
	document.getElementById("reservas").style.visibility = "hidden";
	document.getElementById("grupo").style.visibility = "hidden";
	document.getElementById("pere").style.visibility = "hidden";
	document.getElementById("reaj").style.visibility = "hidden";

	
	
	
	
	
	
}



//comprobar fechas

 //definimos las variables que almacenaran los componentes de la fecha actual
        ahora = new Date();
        ahoraDay = ahora.getDate();
        ahoraMonth = ahora.getMonth();
        ahoraYear = ahora.getYear();

        // Nestcape Navigator 4x cuenta el anyo a partir de 1900, por lo que es necesario sumarle esa cantidad para obtener el anyo actual adecuadamente
        if (ahoraYear < 2000)
            ahoraYear += 1900;

        //funcion para saber cuantos dias tiene cada mes
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

        //una vez que sabemos cuantos dias tiene cada mes asignamos dinamicamente este numero al combo de los dias dependiendo del mes que aparezca en el combo de los meses
        function asignaDias(dia,mes,anyo){
            comboDias = document.getElementById(dia);
            comboMeses = document.getElementById(mes);
            comboAnyos = document.getElementById(anyo);

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
                    sumaOpcion = new Option(comboDias.options.length + 1);
                    comboDias.options[comboDias.options.length]=sumaOpcion;
                }
            }
            if (comboDias.selectedIndex < 0)
              comboDias.selectedIndex = 0;
         }

        //ahora selecionamos en los combos los valores correspondientes a la fecha actual del sistema
        
    
</script>

<script>
	function visibilidades(formulario){
		if(formulario==null || formulario==1){
			mostrar_alber();
		}
		if(formulario==2){
			mostrar_facturas();
		}
		if(formulario==3){
			mostrar_pere();
		}
		if(formulario==5){
			mostrar_grupo();
		}
		if(formulario==6){
			mostrar_incidencias();
		}
		if(formulario==7){
			mostrar_reservas();
		}
		if(formulario==8){
			mostrar_reaj();
		}
		if(formulario==9){
			mostrar_reservas_on();
		}

	}

	function cambiar_tipo_facturas(valor){
		
		location.href='?pag=busq.php&facturando='+valor;
		
	}

</script>


<!-- tabla criterios-->
<body onload="visibilidades(<?php echo $_SESSION['retorno']; ?>)">
  <table border="0" width="100%">
  <tr ><td colspan="3" height="35px"></td></tr>
    <tr> 
      <td width="14"></td>
      
    <td width="420" valign="top"> <div id="criterios" style="position:relative;visibility:visible;"> 
        <form name="form1">
          <table border='0' width="100%" cellpadding="0px" cellspacing="0px">
              <tr>
              <td align='center' colspan="3" style="padding:0px 0px 0px 0px;">
			  <div class='champi_izquierda'>&nbsp;</div>
			  <div class='champi_centro'  style='width:360px;'>
						Búsqueda
						</div>
						<div class='champi_derecha'>&nbsp;</div>
						<!--
			  <div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_izquierda.jpg');background-repeat:no-repeat;float:left;" id="alerta_esquina_izquierda">&nbsp;</div>
                <div style="height:25px;text-align:center;background-image:url('../imagenes/img_tablas/linea_horizontal.jpg');background-repeat:repeat-x;float:left;"> 
                  <div class="titulo" style="text-align:center;width:360px;">Búsqueda</div>
                </div>
                <div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_derecha.jpg');float:left;">&nbsp;</div>--></td>
				</tr>
				<tr>
              <td align="left" style="padding:0px 0px 0px 0px;" class='tabla_detalles'> <table border="0" width="100%" style="border: 1px solid #3F7BCC;">
                  <tr> 
                    <td> <table width="100%" border="0" >
                        <tr align="left" width="100%" > 
                          <td width="100%" colspan="3" >&nbsp;</td>
                        </tr>
                        <tr align="left" width="100%" > 
                          <td width="175" > 
<?php
	if(isset($_SESSION['permisoAlberguista']) && $_SESSION['permisoAlberguista']=true) //Comprobando que se tiene permiso para realizar busquedas de Alberguistas
		{                            
		if($_SESSION['retorno']=="" || $_SESSION['retorno']==1)
			echo '<input type="radio" value="1"  name="seleccion"  onClick="mostrar_alber()" onselec="mostrar_alber();" checked>';
		else
			echo '<input type="radio" value="1"  name="seleccion"  onClick="mostrar_alber()">';
        echo '&nbsp;<label class="label_formulario">Alberguistas</label>'; 
		}
	else
		echo '<input type="radio" disabled="true">&nbsp;<label disabled="true" class="label_formulario">Alberguistas</label>';
?>
                          </td>
                          <td width="10" >&nbsp;</td>
                          <td > 
<?php 
	if(isset($_SESSION['permisoFacturacion']) && $_SESSION['permisoFacturacion']=true) //Comprobando que se tiene permiso para realizar busquedas de Facturas
		{
		if($_SESSION['retorno']==2)
			echo '<input type="radio" value="2"  name="seleccion" onClick="mostrar_facturas()" checked>';
		else
			echo '<input type="radio" value="2"  name="seleccion" onClick="mostrar_facturas()">';
        echo '&nbsp;<label class="label_formulario">Facturas</label>';
		}
	else
		echo '<input type="radio" disabled="true">&nbsp;<label disabled="true" class="label_formulario">Facturas</label>';
?>
                          </td>
                        </tr>
                        <tr align="left" width="100%" > 
                          <td width="175"> 
<?php 
	if(isset($_SESSION['permisoPeregrino']) && $_SESSION['permisoPeregrino']=true) //Comprobando que se tiene permiso para realizar busquedas de Peregrinos
		{
		if($_SESSION['retorno']==3)
			echo '<input type="radio" value="3"  name="seleccion" onClick="mostrar_pere()" checked>';
		else
			echo '<input type="radio" value="3"  name="seleccion" onClick="mostrar_pere()">';
        echo '&nbsp;<label class="label_formulario">Peregrinos</label>';
		}
	else
		echo '<input type="radio" disabled="true">&nbsp;<label disabled="true" class="label_formulario">Peregrinos</label>';
?>
		                          </td>
                          <td width="10">&nbsp;</td>
                          <td> 
<?php
	if(isset($_SESSION['permisoIncidencias']) && $_SESSION['permisoIncidencias']=true) //Comprobando que se tiene permiso para realizar busquedas de Incidencias
		{
		if($_SESSION['retorno']==6)
			echo '<input type="radio" value="6"  name="seleccion" onClick="mostrar_incidencias()" checked>';
		else
			echo '<input type="radio" value="6"  name="seleccion" onClick="mostrar_incidencias()">';
		echo '&nbsp;<label class="label_formulario">Incidencias</label>';
		}
	else
		echo '<input type="radio" disabled="true">&nbsp;<label disabled="true" class="label_formulario">Incidencias</label>';
?>
                          </td>
                        </tr >
                        <tr align="left" width="100%" > 
                          <td width="175"> 
<?php
	if(isset($_SESSION['permisoGrupos']) && $_SESSION['permisoGrupos']=true) //Comprobando que se tiene permiso para realizar busquedas de Grupos
		{
		if($_SESSION['retorno']==5)
			echo '<input type="radio" value="5"  name="seleccion" onClick="mostrar_grupo()" checked>';
		else
			echo '<input type="radio" value="5"  name="seleccion" onClick="mostrar_grupo()">';
		echo '&nbsp;<label class="label_formulario">Grupos</label>';
		}
	else
		echo '<input type="radio" disabled="true">&nbsp;<label disabled="true" class="label_formulario">Grupos</label>';
?>
                          </td>
                          <td width="10">&nbsp;</td>
                          <td> 
<?php
	if(isset($_SESSION['permisoREAJ']) && $_SESSION['permisoREAJ']=true) //Comprobando que se tiene permiso para realizar busquedas de Reaj
		{
		if($_SESSION['retorno']==8)
			echo '<input type="radio" value="8"  name="seleccion" onClick="mostrar_reaj()" checked>';
		else
			echo '<input type="radio" value="8"  name="seleccion" onClick="mostrar_reaj()">';
		echo '&nbsp;<label class="label_formulario">Reaj</label>';
		}
	else
		echo '<input type="radio" disabled="true">&nbsp;<label disabled="true" class="label_formulario">Reaj</label>';
?>            
                          </td>
                        </tr>
                        <tr align="left" width="100%" > 
                          <td width="175"> 
<?php 
	if(isset($_SESSION['permisoReservas']) && $_SESSION['permisoReservas']=true) //Comprobando que se tiene permiso para realizar busquedas de Reservas
		{
		if($_SESSION['retorno']==7)
			echo '<input type="radio" value="7"  name="seleccion" onClick="mostrar_reservas()" checked>';
		else
			echo '<input type="radio" value="7"  name="seleccion" onClick="mostrar_reservas()">';
		echo '&nbsp;<label class="label_formulario">Reservas</label>';
		}
else
		echo '<input type="radio" disabled="true">&nbsp;<label disabled="true" class="label_formulario">Reservas</label>';
?>
                          </td>
                          <td width="10">&nbsp;</td>
                          <td > 
<?php 
	if(isset($_SESSION['permisoReservas']) && $_SESSION['permisoReservas']=true) //Comprobando que se tiene permiso para realizar busquedas de Reservas
		{
		if($_SESSION['retorno']==9)
			echo '<input type="radio" value="9"  name="seleccion" onClick="mostrar_reservas_on()" checked>';
		else
			echo '<input type="radio" value="9"  name="seleccion" onClick="mostrar_reservas_on()">';
		echo '&nbsp;<label class="label_formulario">Reservas Online</label>';
		}
	else
		echo '<input type="radio" disabled="true">&nbsp;<label disabled="true" class="label_formulario">Reservas Online</label>';
?>		
                          </td>
                        </tr>
                        <tr> 
                          <td colspan="3">&nbsp;</td>
                        </tr>
                      </table></td>
                  </tr>
                </table>
				</td>
				</tr>
            </table>
        </form>
      </div>
      <!-- finalizar tabla criterios-->
    </td>
      <td width="276" align="left" valign="top"> 
        <!-- tabla capas cri-->

      <div id="total" style="position:relative; visibility:visible; width: 400px; z-index: 1; overflow: visible;"> 
        <!-- capa criterio alberguista-->
			<?php include("capa_criterio_alberguista.inc");?>
        <!-- capa criterio peregrino-->
			<?php include("capa_criterio_peregrino.inc");?>
        <!-- capa criterio grupos-->
        	<?php include("capa_criterio_grupos.inc");?>
        <!-- capa criterio reservas-->
        	<?php include("capa_criterio_reservas.inc");?>
        <!-- capa criterio reservas_on-->
			<?php include("capa_criterio_reservas_online.inc");?>
        <!-- capa criterio facturas-->
        	<?php include("capa_criterio_facturas.inc");?>
        <!-- capa criterio incidencias-->
	        <?php include("capa_criterio_incidencias.inc");?>
        <!-- capa criterio reaj-->
        	<?php include("capa_criterio_reaj.inc");?>
      </div></td>
      <td width="42" align="left" valign="top">&nbsp;</td>
    </tr>
  </table>
