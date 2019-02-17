<?php
SESSION_START();
$hoy=date("d-m-Y");
		
	
	

	//Funcion que nos devuelve la edad de una persona pasandole la fecha de nacimiento y la fecha de llegada al albe	rgue
	function edad($f_nacimiento,$f_llegada) {
		  	$fecha_nacimiento = SPLIT("-",$f_nacimiento);
	  		$fecha_llegada = SPLIT("-",$f_llegada);
	  		$edad = INTVAL($fecha_llegada[0]) - INTVAL($fecha_nacimiento[0]);
	  		if (INTVAL($fecha_llegada[1]) < INTVAL($fecha_nacimiento[1])) {
		    	$edad--;
			}
			else if (INTVAL($fecha_llegada[1]) == INTVAL($fecha_nacimiento[1])) {
		  		if (INTVAL($fecha_llegada[2]) < INTVAL($fecha_nacimiento[2])) {
			    	$edad--;
				}
			}
			return $edad;
		}
		
		function parteCadenas($cadena, $num) {
	$nom = $cadena;
	$nom_partido = "";
	
	//Separa las palabras distintas de la línea
	$array_nom = split(" ", $nom);
	for($u=0; $u<count($array_nom); $u++) {
		if(strlen($array_nom[$u]) > $num) {
			$palabras_largas = 'si';
		} else {
			$palabras_largas = 'no';
		}
	}

	if($palabras_largas == 'si') {
	for($b=0; $b<count($array_nom); $b++) {
		$cont_act = 0;
		//Separa por guiones
		$array_nom_i = split("-", $array_nom[$b]);
		if(count($array_nom_i) > 1) {
			$guiones = 'si';
		} else {
			$guiones = 'no';
		}
		for($j=0; $j<count($array_nom_i); $j++) {
			$cont_act = $cont_act +1;
					
			$resto = strlen($array_nom_i[$j]) % $num;
			$cociente = (strlen($array_nom_i[$j]) - $resto) / $num;
			
			
			if($guiones == 'no') {
				if(strlen($array_nom_i[$j]) > $num) {
					$cont = 0;
					for($n=1; $n<=$cociente; $n++) {
						if($nom_partido == "") {
							$nom_partido = $nom_partido." ".substr($array_nom_i[$j], $cont, $num);
							$cont = $cont + $num;
						} else {
							$nom_partido = $nom_partido."<br>".substr($array_nom_i[$j], $cont, $num);
							$cont = $cont + $num;
						}
					}
					$nom_partido = $nom_partido."<br>".substr($array_nom_i[$j], $cont, $resto);
				} else {
					$nom_partido = $nom_partido. " ".$array_nom_i[$j];
				} 

			} else {
				if(strlen($array_nom_i[$j]) > $num) {
					$cont = 0;
					for($n=1; $n<=$cociente; $n++) {
						if($nom_partido == "") {
							$nom_partido = $nom_partido." ".substr($array_nom_i[$j], $cont, $num);
							$cont = $cont + $num;
						} else {
							$nom_partido = $nom_partido."<br>".substr($array_nom_i[$j], $cont, $num);
							$cont = $cont + $num;
						}
					}
					$nom_partido = $nom_partido."<br>".substr($array_nom_i[$j], $cont, $resto);
				} else {
					if($nom_partido == "") {
						$nom_partido = $nom_partido. " ".$array_nom_i[$j]."-";
					} else {
						if($cont_act == count($array_nom_i)) {
							$nom_partido = $nom_partido. " ".$array_nom_i[$j];
						} else {
							$nom_partido = $nom_partido. " ".$array_nom_i[$j]."-";
						}
					}
				} 
			}
		}
	} 
	return $nom_partido;
	} else {
		return $cadena;
	}
} 
		
	
		

	  @$db=mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);  //Conexión a la base de datos
	  if (!$db){
		echo "Error : No se ha podido conectar a la base de datos";
		exit;
		}
 		  mysql_select_db($_SESSION['conexion']['db']);	





//Recoger el número de factura que se almacenará en la variable factura
   if(isset($_GET['numf'])){

   $factura=$_GET['numf'];
   } 
 
// Cabeceras para abrir excel con el nombre de la factura
	header("Content-type: application/vnd.ms-excel;");
	header("Content-Disposition: attachment; filename=".$factura.".xls");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	


echo iconv("UTF-8","CP1252","±àºÅ\t");

echo iconv("UTF-8","CP1252","ÉêÞzÈÕ\t");

echo iconv("UTF-8","CP1252","¤ªÃûÇ°\t");

echo iconv("UTF-8","CP1252","¥í©`¥Þ×Ö\t");

echo iconv("UTF-8","CP1252","ÇÚ„ÕÏÈ\t");

echo iconv("UTF-8","CP1252","²¿Êð\t");

echo iconv("UTF-8","CP1252","ÒÛÂš\t");

echo iconv("UTF-8","CP1252","×¡Ëù\t");

echo iconv("UTF-8","CP1252","à]±ã·¬ºÅ\t");

//echo iconv("UTF-8","CP1252","TEL\t");

//echo iconv("UTF-8","CP1252","FAX\t");

//echo iconv("UTF-8","CP1252","E-mail\t");

//echo iconv("UTF-8","CP1252","http://\t");

echo iconv("UTF-8","CP1252","¤´À´ˆöÄ¿µÄ\t");

echo iconv("UTF-8","CP1252","±×Éç¤Î˜I„ÕÄÚÈÝ\t");

echo iconv("UTF-8","CP1252","±¾Õ¹Ê¾»á¤ò¤É¤ÎÃ½Ìå¤Ç¤ªÖª¤ê¤Ë¤Ê¤ê¤Þ¤·¤¿¤«\t");

echo iconv("UTF-8","CP1252","½ñáá¡¢½Uœg?×Ô„ÓÜ‡év?Çéˆó?JETROév?¥¤¥Ù¥ó¥È¤Î¤´°¸ÄÚ¤ò²î¤·ÉÏ¤²¤Æ¤â¤è¤í¤·¤¤¤Ç¤¹¤«\t");










// Recoger datos del albergue

	$sqlalb="SELECT * FROM albergue";
	$resulalb=mysql_query($sqlalb);
	$filaalb=mysql_fetch_array($resulalb);






//Inicializar array que contendra todas las posibles combinaciones de habitaciones con sus tarifas.Contendrá las variables:
//número de clientes facturados, tarifa, edad, tipo de habitación, tipopersona, número de exentos,ingreso,pernoctas,dni.
//Se irá añadiento clientes si la pernocta es la misma o el cliente es el mismo, sino se añadirá una nueva entrada en el array que contendrá los mismos datos menos la pernocta diferente.
$tarifas=array();
$cont=0;
$sqltar="SELECT *  FROM tarifa ";
$resultar=mysql_query($sqltar);
for ($i=0;$i<mysql_num_rows($resultar);$i++){
        $fila=mysql_fetch_array($resultar);
        $tarifas[$cont]['numclientes']=0;
        $tarifas[$cont]['tarifa']=$fila['Tarifa'];
        $tarifas[$cont]['idedad']=$fila['Id_Edad'];
        $tarifas[$cont]['idtipohabitacion']=$fila['Id_Tipo_Hab'];
        $tarifas[$cont]['idtipopersona']=$fila['Id_Tipo_Persona'];
        $tarifas[$cont]['numex']=0;
        $tarifas[$cont]['ingreso']=0.00;
        $tarifas[$cont]['pernoctas']=0;
        $tarifas[$cont]['dni']=array();
        $tarifas[$cont]['servicio']='';
        $tarifas[$cont]['tarservicio']=0;
        $cont++;

}


/*
//Visualizar Array

for ($i=0;$i<mysql_num_rows($resultar);$i++){
       
        print($tarifas[$i]['numclientes']);
        print($tarifas[$i]['tarifa']);
        print($tarifas[$i]['idedad']);
        print($tarifas[$i]['idtipohabitacion']);
        print($tarifas[$i]['idtipopersona']);
        print($tarifas[$i]['numex']);
        print($tarifas[$i]['ingreso']);
        ?><br><?

}*/


/*Modificar

if(isset($_POST['n_factura'])){

    

                 $sql="UPDATE factura SET DNI = '".$_POST['dni']. "',Nombre = '".$_POST['nombre']. "',Apellido1 = '".$_POST['apellido1']. "',
            Apellido2 = '".$_POST['apellido2']. "',Desperfecto = '".$_POST['daños']. "',Cuantia_Desperfecto = '".$_POST['suma_daños']. "' WHERE `Num_Factura` = '".$_POST['n_factura']. "'";
          

                  $resul = mysql_query($sql);
                }

*/







   //seleccionar de que tipo de persona es la factura que se almacenará en la variable cliente


   $sql="SELECT *  FROM factura Where Num_Factura like '".$factura. "'";
 
   $resul1 = mysql_query($sql);
   $fila1 = mysql_fetch_array($resul1);
   $DNIres = $fila1['DNI'];
   
   
   
   $sql="SELECT * FROM genera WHERE Num_Factura like '".$factura. "'";
   $resul2 = mysql_query($sql);
 
  



       if( mysql_num_rows($resul2) <1){
                  
                   $sql="SELECT * FROM genera_gr WHERE Num_Factura like '".$factura. "'";
                   $resul2 = mysql_query($sql);
                   
                    if(mysql_num_rows($resul2) < 1){



                             $sql="SELECT *  FROM detalles Where DNI_PRA = '".$DNIres. "' and Llegada_Tarde ='NO PRESENTADO'";
                             $resulres =mysql_query($sql);
                             if(mysql_num_rows($resulres) >= 1)
                                 {

                                   //Cliente que ha realizado una reserva, ha ingresado pero no se ha presentado
                                  $cliente="res";

                                  }else {

                         //peregrino
                         $cliente="per";
                         $sql="SELECT * FROM genera_p WHERE Num_Factura like '".$factura. "'";
                      
                         $resul2 = mysql_query($sql);
                         
                         
                      

                         


                       }}
                       else {
                         //grupo
                         $cliente="gru";
                         
                         
                        
                        
                         }

                  



          } else {
              
               //alberguista
               
                $cliente="alb";
          
                   



               }
              

        //$cliente="res";  
      //print($cliente);
      
          
  

          
          
        //Si el cliente es un alberguista se harán las siguientes querys para conseguir los datos para rellenar el array con los ids
    
       if ($cliente=="alb"){

                
             for ($i=0;$i<mysql_num_rows($resul2);$i++){
                  $fila2=mysql_fetch_array($resul2);
               
               //print(mysql_num_rows($resul2));
                $DNI=$fila2['DNI_Cl'];
                $fechalleg=$fila2['Fecha_Llegada'];
   
                //print ("DNI base de datos".$DNI);

                    $sql="SELECT * FROM pernocta WHERE DNI_Cl like '".$DNI. "' and Fecha_Llegada='".$fechalleg."'";

               $resul3 = mysql_query($sql);
               $fila3=mysql_fetch_array($resul3);
               $ingreso=$fila3['Ingreso']; // Ingreso
               $pernocta=$fila3['PerNocta']; //Número noches
               $servicio=$fila3['Id_Servicios'];//Nombre servicio
               
               //Datos Cliente
               $fecha=split("-",$fila3['Fecha_Llegada']);
               $fechaentrada=$fecha[2]."-".$fecha[1]."-".$fecha[0];
               $fecha=split("-",$fila3['Fecha_Salida']);
               $fechasalida=$fecha[2]."-".$fecha[1]."-".$fecha[0];
               $sql="SELECT * FROM cliente WHERE DNI_Cl like '".$DNI. "'";
               $resulc= mysql_query($sql);
               $filac=mysql_fetch_array($resulc);
               $idn=$filac['Id_Pais'];
               $idp=$filac['Id_Pro'];
               $sql="SELECT * FROM pais WHERE Id_Pais like '".$idn. "'";
               $resul= mysql_query($sql);
               $fila=mysql_fetch_array($resul);
               $pais=$fila['Nombre_Pais'];
               $sql="SELECT * FROM provincia WHERE Id_Pro like '".$idp. "'";
               $resul= mysql_query($sql);
               $fila=mysql_fetch_array($resul);
               $provincia=$fila['Nombre_Pro'];
               
                
                
                $sql="SELECT pernocta.*, cambio_tipo_habitacion.* FROM pernocta INNER JOIN cambio_tipo_habitacion ON pernocta.Id_Hab=cambio_tipo_habitacion.Id_Hab WHERE DNI_Cl like '".$DNI. "' and Fecha_Llegada='".$fechalleg."' and Fecha<='".$fechalleg."'";
                $resul4 = mysql_query($sql);
                $fila4=mysql_fetch_array($resul4);
                $tipohab=$fila4['Id_Tipo_Hab'];
                //print($tipohab);
                
                $sql="SELECT * FROM tipo_habitacion WHERE Id_Tipo_Hab like '".$tipohab. "'";
                $resul5 = mysql_query($sql);
                $fila5=mysql_fetch_array($resul5);
                $tipopersona="1";
              //print($tipopersona);
                
                $sql="SELECT * FROM cliente WHERE DNI_Cl like '".$DNI. "'";
                $resulc= mysql_query($sql);
                $filac=mysql_fetch_array($resulc);
                $edad=edad($filac['Fecha_Nacimiento_Cl'] ,$fila2['Fecha_Llegada']);
               //print($edad);
                $sql="SELECT * FROM edad WHERE Edad_Min <= ".$edad." AND Edad_Max >= ".$edad;
                
                $resul=mysql_query($sql);
                $fila=mysql_fetch_array($resul);
                 $idedad=$fila['Id_Edad'];
                //print($idedad);?><br><?
                
                $sql="SELECT * FROM tarifa WHERE Id_Edad = ".$idedad." AND Id_Tipo_Hab = ".$tipohab." AND Id_Tipo_Persona = ".$tipopersona;
                $resul=mysql_query($sql);
               	$fila=mysql_fetch_array($resul);
                $tarif=$fila['Tarifa'];
                
                
               	$tarservicio=0;
               	$sql="SELECT * FROM servicios WHERE Id_Edad = ".$idedad." AND Id_Servicios = '".$servicio."'";
                $resul=mysql_query($sql);
                if($resul>0)
                {
                $fila=mysql_fetch_array($resul);
                $tarservicio=$fila['Precio'];
                }

                $contador=count($tarifas);
               
                $encontrado="no"; //Variable para controlar si en el tipo de tarifa hay clientes con diferente número de pernoctas
                
                $encontradodni="no"; //Variable para controlar si agrupamos un mismo alberguista con estancias diferentes
                
                
                

                
                
                
                
                
                

                
                //Aumentar el número de clientes facturados, el ingreso y marcar como exento cuando coincida con el array
                for ($j=0;$j<$contador;$j++){
                       
                        //Entra en este if si es la primera vez que encontramos esa coincidencia, es el caso en que la pernocta vale 1, o si ha realizado la misma pernocta
                        if(($tarifas[$j]['idedad']==$idedad) and ($tarifas[$j]['idtipohabitacion']==$tipohab)and ($tarifas[$j]['idtipopersona']==$tipopersona)and($tarifas[$j]['servicio']==$servicio or $tarifas[$j]['servicio']=='' ) and ($tarifas[$j]['pernoctas']==0 or $tarifas[$j]['pernoctas']==$pernocta)) {
                            
                            //print($DNI);
                            
                            
                            $encontrado="si";
                            $c=count($tarifas[$j]['dni']);
                           
                            // print("c vale".$c."jota vale=".$j);?><br><?
                            
                            if(($encontradodni=="no")and($tarifas[$j]['servicio']==$servicio or $tarifas[$j]['servicio']=='')){

                                    $tarifas[$j]['dni'][$c]=$DNI;
                                   // print("primera vez".$tarifas[$j]['dni'][$c]);?><br><?
                                    $tarifas[$j]['numclientes']++;
                                    $tarifas[$j]['ingreso']= $tarifas[$j]['ingreso']+$ingreso;
                                    $tarifas[$j]['pernoctas']=$pernocta;
                                    $tarifas[$j]['servicio']=$servicio;
                                    $tarifas[$j]['tarservicio']=$tarservicio;
                                    if($fila2['Exento']=="S"){$tarifas[$j]['numex']++;}
									$encontradodni="si";

                           }
                           
                           
                           
                        
                         
                              
                             
                             
                           
                          
                          }
                          if(($encontradodni=="no")and($tarifas[$j]['servicio']==$servicio)){
                           
                            for ($d=0;$d<count($tarifas[$j]['dni']);$d++){
                               //print("j vale".$j."de vale".$d);?><br><?
                               //print("segunda vez".$tarifas[$j]['dni'][$d]);?><br><?
                             //El alberguista ya ha hecho una pernocta en otras fechas
                             if($tarifas[$j]['dni'][$d]==$DNI){
                                //print("ya existe=".$c);?><br><?
                                     //Se suma el ingreso y las pernoctas
                                     $tarifas[$j]['ingreso']= $tarifas[$j]['ingreso']+$ingreso;
                                     $tarifas[$j]['pernoctas']=$tarifas[$j]['pernoctas']+$pernocta;
                                     $encontradodni="si";
                                     $encontrado="si";

                             } }}}
                          
                          
                          //No existe el DNI, es la primera vez en ese tipo de tarifa o tiene una pernocta igual a otro cliente
                          
                          //insertar al final del array una nueva entrada
                         if($encontrado=="no"){
                           //print("nueva fila array?");
                            $tarifas[$contador]['numclientes']="1";
                            $tarifas[$contador]['tarifa']=$tarif;
                            $tarifas[$contador]['idedad']=$idedad;
                            $tarifas[$contador]['idtipohabitacion']=$tipohab;
                            $tarifas[$contador]['idtipopersona']=$tipopersona;
                            if($fila2['Exento']=="S"){$tarifas[$contador]['numex']="1";}else {$tarifas[$contador]['numex']=0;}
                            $tarifas[$contador]['ingreso']=$ingreso;
                            $tarifas[$contador]['pernoctas']=$pernocta;
                            $tarifas[$contador]['dni'][0]=$DNI;
                            $tarifas[$contador]['servicio']=$servicio;
                            $tarifas[$contador]['tarservicio']=$tarservicio;
                            
                            
                           
                          
                          }}
                          
             }


             
             

             
    



       
       
         //Si el cliente es un peregrino se haran las siguientes querys para conseguir los datos para rellenar el array con los ids
              if ($cliente=="per"){
              


             for ($i=0;$i<mysql_num_rows($resul2);$i++){
                  $fila2=mysql_fetch_array($resul2);

                  $DNI=$fila2['DNI_CL'];
                  $fechalleg=$fila2['Fecha_Llegada'];
                  
                  //print($DNI);
  
               //print(mysql_num_rows($resul2));
                
                  //datos del cliente
                 $sql="SELECT * FROM pernocta_p WHERE DNI_Cl like '".$DNI. "'  and Fecha_Llegada='".$fechalleg."'";

                   $resul3 = mysql_query($sql);
                   $fila3=mysql_fetch_array($resul3);
                   $servicio=$fila3['Id_Servicios'];
                   $pernocta=$fila3['PerNocta']; //Número noches	
                   $fecha=split("-",$fila3['Fecha_Llegada']);
                   $fechaentrada=$fecha[2]."-".$fecha[1]."-".$fecha[0];
                   $fecha=split("-",$fila3['Fecha_Salida']);
                   $fechasalida=$fecha[2]."-".$fecha[1]."-".$fecha[0];
                   $sql="SELECT * FROM cliente WHERE DNI_Cl like '".$DNI. "'";
                   $resulc= mysql_query($sql);
                   $filac=mysql_fetch_array($resulc);
                   $idn=$filac['Id_Pais'];

                   $idp=$filac['Id_Pro'];
                   $sql="SELECT * FROM pais WHERE Id_Pais like '".$idn. "'";
                   $resul= mysql_query($sql);
                   $fila=mysql_fetch_array($resul);
                   $pais=$fila['Nombre_Pais'];
                   $sql="SELECT * FROM provincia WHERE Id_Pro like '".$idp. "'";
                   $resul= mysql_query($sql);
                   $fila=mysql_fetch_array($resul);
                   $provincia=$fila['Nombre_Pro'];




                $sql="SELECT pernocta_p.*, cambio_tipo_habitacion.* FROM pernocta_p INNER JOIN cambio_tipo_habitacion ON pernocta_p.Id_Hab=cambio_tipo_habitacion.Id_Hab WHERE DNI_Cl like '".$DNI. "' and Fecha_Llegada='".$fechalleg."' and cambio_tipo_habitacion.Fecha<='".$fechalleg."'";
                //print($sql);
                $resul4 = mysql_query($sql);
                $fila4=mysql_fetch_array($resul4);
                $tipohab=$fila4['Id_Tipo_Hab'];
                //print($tipohab);

                $sql="SELECT * FROM tipo_habitacion WHERE Id_Tipo_Hab like '".$tipohab. "'";
                $resul5 = mysql_query($sql);
                $fila5=mysql_fetch_array($resul5);
                $tipopersona="0"; // Cuando es 0 corresponde a peregrino en la base de datos
              //print($tipopersona);

                $sql="SELECT * FROM cliente WHERE DNI_Cl like '".$DNI. "'";
                $resulc= mysql_query($sql);
                $filac=mysql_fetch_array($resulc);
                $edad=edad($filac['Fecha_Nacimiento_Cl'] ,$fila2['Fecha_Llegada']);
               //print($edad);
                $sql="SELECT * FROM edad WHERE Edad_Min <= ".$edad." AND Edad_Max >= ".$edad;

                $resul=mysql_query($sql);
                $fila=mysql_fetch_array($resul);
                 $idedad=$fila['Id_Edad'];
                //print($idedad);?><br><?
                $sql="SELECT * FROM tarifa WHERE Id_Edad = ".$idedad." AND Id_Tipo_Hab = ".$tipohab." AND Id_Tipo_Persona = ".$tipopersona;
                //print($sql);
                $resul=mysql_query($sql);
                $fila=mysql_fetch_array($resul);
                $tarif=$fila['Tarifa'];
                //print($tarif);
                
                 $tarservicio=0;
                 $sql="SELECT * FROM servicios WHERE Id_Edad = ".$idedad." AND Id_Servicios = '".$servicio."'";
                $resul=mysql_query($sql);
                if($resul>0)
				{
                $fila=mysql_fetch_array($resul);
                $tarservicio=$fila['Precio'];
                }
                 $contador=count($tarifas);
                 $encontrado="no";
                 


                 //Aumentar el número de clientes facturados y el ingreso 
                for ($j=0;$j<$contador;$j++){

                        if(($tarifas[$j]['idedad']==$idedad) and ($tarifas[$j]['idtipohabitacion']==$tipohab)and ($tarifas[$j]['idtipopersona']==$tipopersona)and(($tarifas[$j]['servicio']==$servicio)or ($tarifas[$j]['servicio']==''))  )           {
                            
                             $tarifas[$j]['numclientes']++;
                             $tarifas[$j]['pernoctas']=$pernocta;
                             $tarifas[$j]['ingreso']= $tarifas[$j]['ingreso']+$tarif+$tarservicio;
                             $tarifas[$j]['servicio']=$servicio;
                             $tarifas[$j]['tarservicio']=$tarservicio;
                             $encontrado='si';


                          }}
                          //Si realiza una estancia diferente a la existente en el array, se crea un nuevo caso
                          if($encontrado=='no'){
                            //print("aki?");
                                $tarifas[$contador]['numclientes']="1";
                            $tarifas[$contador]['tarifa']=$tarif+$tarservicio;
                            $tarifas[$contador]['idedad']=$idedad;
                            $tarifas[$contador]['idtipohabitacion']=$tipohab;
                            $tarifas[$contador]['idtipopersona']=$tipopersona;
                            if($fila2['Exento']=="S"){$tarifas[$contador]['numex']="1";}else {$tarifas[$contador]['numex']=0;}
                            $tarifas[$contador]['ingreso']=$tarif;
                            $tarifas[$contador]['pernoctas']=$pernocta;
                            $tarifas[$contador]['dni'][0]=$DNI;
                            $tarifas[$contador]['servicio']=$servicio;
                             $tarifas[$contador]['tarservicio']=$tarservicio;

                               }
                            




             }



              }

//Si la factura es de un grupo
 if ($cliente=="gru"){


             for ($i=0;$i<mysql_num_rows($resul2);$i++){
                  $fila2=mysql_fetch_array($resul2);
                  $Nombreg=$fila2['Nombre_Gr'];
                  $fechalleg=$fila2['Fecha_Llegada'];
            
                 
                  $Id_Hab=$fila2['Id_Hab'];
                  $sql4="SELECT * FROM cambio_tipo_habitacion WHERE Id_Hab = '".$Id_Hab."' and Fecha<='".$fechalleg."'";
          
                
					$resul4 = mysql_query($sql4);
                    $fila4=mysql_fetch_array($resul4);
                    $tipohab=$fila4['Id_Tipo_Hab'];
                 
                    $idedad=$fila2['Id_Edad'];
                    $tipopersona=2;
                    $numex=$fila2['Num_Exentos'];
                    $numfact=$fila2['Num_Facturados'];
                    
                    
                



              	//print($tipohab);
               	//print($idedad);
              	//print($tipopersona);
              	//print($numex);
               	//print($numfact);?><br><?
              
				$sql="SELECT * FROM estancia_gr WHERE Nombre_Gr='".$Nombreg."' and Fecha_Llegada='".$fechalleg."'";
				$resul5 = mysql_query($sql);
				$fila5=mysql_fetch_array($resul5);
				$pernocta=$fila5['PerNocta'];
				$tarservicio=0; 
				$servicio=$fila5['Id_Servicios'];
				 
				$sql="SELECT * FROM servicios WHERE Id_Edad = ".$idedad." AND Id_Servicios = '".$servicio."'";
                $resul=mysql_query($sql);
                if($resul>0)
				{
                $fila=mysql_fetch_array($resul);
                $tarservicio=$fila['Precio'];
              }
          
                $contador=count($tarifas);
				$encontrado="no";



                 //Aumentar el número de clientes facturados, el ingreso y marcar como exento cuando coincida con el array
                for ($j=0;$j<$contador;$j++){

                        if(($tarifas[$j]['idedad']==$idedad) and ($tarifas[$j]['idtipohabitacion']==$tipohab)and ($tarifas[$j]['idtipopersona']==$tipopersona)and(($tarifas[$j]['servicio']==$servicio)or ($tarifas[$j]['servicio']==''))  )           {

                            $tarifas[$j]['numclientes']=$tarifas[$j]['numclientes']+ $numfact+ $numex;
							$tarifas[$j]['pernoctas']=$pernocta;
                             //$tarifas[$j]['ingreso']= $tarifas[$j]['ingreso'];
							$tarifas[$j]['servicio']=$servicio;
							$tarifas[$j]['tarservicio']=$tarservicio;
							$tarifas[$j]['numex']=$tarifas[$j]['numex']+$numex;
							$encontrado='si';


                          }}
                          //En principio un grupo siempre tendrá las mismas noches y el mismo tipo de servicio, no sería necesario este caso 
                          if($encontrado=='no'){
                            //print("aki?");
                            $tarifas[$j]['numclientes']=$tarifas[$j]['numclientes']+ $numfact+ $numex;
                            $tarifas[$contador]['tarifa']=$tarif+$tarservicio;
                            $tarifas[$contador]['idedad']=$idedad;
                            $tarifas[$contador]['idtipohabitacion']=$tipohab;
                            $tarifas[$contador]['idtipopersona']=$tipopersona;
							$tarifas[$contador]['ingreso']=$tarif;
                            $tarifas[$contador]['pernoctas']=$pernocta;
                            $tarifas[$contador]['dni'][0]=$DNI;
                            $tarifas[$contador]['servicio']=$servicio;
							$tarifas[$contador]['tarservicio']=$tarservicio;
							$tarifas[$j]['numex']=$tarifas[$j]['numex']+$numex;

                               }







                  $fecha=split("-",$fila5['Fecha_Llegada']);
                  $fechaentrada=$fecha[2]."-".$fecha[1]."-".$fecha[0];
                  $fecha=split("-",$fila5['Fecha_Salida']);
                  $fechasalida=$fecha[2]."-".$fecha[1]."-".$fecha[0];



              }}
              
              
              
              
              
              
              
              
              //qeuerys cuando se trata de una reserva de un cliente no presentado
              if ($cliente=="res"){
					if($resulres>0){
                    $filares=mysql_fetch_array($resulres);
                    $fecha=split("-",$filares['Fecha_Llegada']);
                    $fechaentrada=$fecha[2]."-".$fecha[1]."-".$fecha[0];
                    $fecha=split("-",$filares['Fecha_Salida']);
                    $fechasalida=$fecha[2]."-".$fecha[1]."-".$fecha[0];
                    $DNI=$DNIres;
                    }


              }








    //Cambiar en el array id habitaciones por el nombre del tipo de habitación

    $sql="SELECT *  FROM tipo_habitacion ";
    $resul=mysql_query($sql);
    for ($i=0;$i<mysql_num_rows($resul);$i++){
      $fila=mysql_fetch_array($resul);
        for ($k=0;$k<count($tarifas)+1;$k++){


            if($tarifas[$k]['idtipohabitacion']==$fila['Id_Tipo_Hab'])
              {$tarifas[$k]['idtipohabitacion']=$fila['Nombre_Tipo_Hab'];}

    }

    }


 //Cambiar en el array id edad por el nombre de la edad

    $sql="SELECT *  FROM edad ";
    $resul=mysql_query($sql);
    for ($i=0;$i<mysql_num_rows($resul);$i++){
      $fila=mysql_fetch_array($resul);
        for ($k=0;$k<count($tarifas)+1;$k++){


            if($tarifas[$k]['idedad']==$fila['Id_Edad'])
              {$tarifas[$k]['idedad']=$fila['Nombre_Edad'];}

    }

    }

//Cambiar en el array id tipo persona por el nombre tipo persona

    $sql="SELECT *  FROM tipo_persona ";
    $resul=mysql_query($sql);
    for ($i=0;$i<mysql_num_rows($resul);$i++){
      $fila=mysql_fetch_array($resul);
        for ($k=0;$k<count($tarifas)+1;$k++){


            if($tarifas[$k]['idtipopersona']==$fila['Id_Tipo_Persona'])
              {$tarifas[$k]['idtipopersona']=$fila['Nombre_Tipo_Persona'];}

    }

  }
/*

//Imprimir Array para ver el contenido
for ($i=0;$i<count($tarifas);$i++){

        print($tarifas[$i]['numclientes']);
        print($tarifas[$i]['tarifa']);
        print($tarifas[$i]['idedad']);
        print($tarifas[$i]['idtipohabitacion']);
        print($tarifas[$i]['idtipopersona']);
        print($tarifas[$i]['numex']);
        print ($tarifas[$i]['ingreso']);
        print ($tarifas[$i]['pernoctas']);
        ?><br><?
        
       
      for ($d=0;$d<count($tarifas[$i]['dni']);$d++){
     
             print ($tarifas[$i]['dni'][$d]);}

        ?><br><?

}*/






//tabla para datos del albergue y datos del cliente
?>
<div id="cabecera">

   
     <table  style='font-family:Verdana;font-size:12px;	'>
        <tr><td rowspan="6">&nbsp;</td></tr>
    	 <tr>
            <td rowspan="5"  colspan="2" align="right">
            
                <img   width="70px" height="85px" src="C:\SAM\logo.jpg">
            </td>
            
        </tr>
	<tr><td></td></tr>
	<tr><td></td></tr>
	<tr><td></td></tr>
	<tr><td></td></tr>
	<tr><td></td></tr>
	<tr><td></td></tr>
        <tr>
            <td colspan="3" align ="left">
            	&nbsp;&nbsp;&nbsp;&nbsp;EXCMO. AYUNTAMIENTO<br>
				&nbsp;&nbsp;&nbsp;&nbsp;DE LEÓN<BR>
              	&nbsp;&nbsp;&nbsp;&nbsp;Avda Ordoño II, 10<br>
				&nbsp;&nbsp;&nbsp;&nbsp;N.I.F.:P2409100<br>
				
				
            </td>
            <td colspan="3" rowspan="5" align ="left">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?print($filaalb['Nombre_Alb']) ;?><br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?print($filaalb['Direccion_Alb']) ;?><br>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;C.P.:&nbsp;<?print($filaalb['CP_Alb']) ;?><br>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tlf.:&nbsp;<?print($filaalb['Tfno1_Alb']);?><br>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fax:&nbsp;<?print($filaalb['Fax_Alb']) ;?><br>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Email:&nbsp;<?print($filaalb['Fax_Alb']) ;?>
	
			
				
            </td>
            
         </tr>
		 
	   
  
   <br><br><br><br>
   
   
   
   <?//tabla para los datos de la factura
   ?>
   
	<table style='font-family:Verdana;font-size:12px;' border=0>
      
   
       <tr>
        <td colspan="7"><b>Nombre:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?print($fila1['Nombre']);?></td>
        </tr>
        <tr><td></td></tr>
         <tr>
        <td colspan="7"><b>Apellidos:</b>&nbsp;&nbsp;&nbsp;<?print($fila1['Apellido1']." ".$fila1['Apellido2']);?></td>
        </tr>
         <tr><td></td></tr>
       <tr>
            <td><b>DNI:</b></td>
            <td align="left"><?print($DNIres);?></td>
		</tr>
        <tr><td></td></tr>
        
		<tr>
		
			
			<?
			 $facturabarra=split("-",$fila1['Num_Factura']);
			
			?>
			<td colspan="4"><b>Fecha Factura:</b>&nbsp;&nbsp;&nbsp;<?print($hoy)?>&nbsp;&nbsp;&nbsp;&nbsp;<b>Factura Nº:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?print($facturabarra[0]."/".$facturabarra[1])?></td>
			
		</tr>
		</tr>
        <tr><td></td></tr>
        <tr>
            <td colspan="4"><b>Fecha Entrada:</b>&nbsp;&nbsp;&nbsp;<?print($fechaentrada);?>&nbsp;&nbsp;&nbsp;<b>Fecha Salida:</b>&nbsp;&nbsp;&nbsp;<?print($fechasalida);?></td>
           
	                      
         </tr>

	</table>
         
         
    
         <br><br>
         
       
     <?//tabla para los datos de las tarifas
	?>    
        
        
        
	<table align='center' width='600'  style="border:1pt solid;padding:5px;font-family:Verdana;font-size:12px;" border=0>
     <tr><td></td></tr>          
		<?//tabla diferente si la factura es de una reserva que no se ha presentado 
		if($cliente=="res"){?>
			<tr></tr>
			<tr>
				<td align="right"></td>
				<td colspan="" align="right">&nbsp;&nbsp;&nbsp;No presentado <input type="checkbox" checked></td>
			</tr>
			<tr></tr>
			<tr>
				<td></td>
				<td  colspan="5" align="left">Ingresado en Cuenta</td>
				<td><?print($fila1['Ingreso']);?></td>
			</tr>
			<tr></tr>


		<?  }else {?>
               
				<tr  cellpadding="4" cellspacing="4" style="font-size:12px;font-family:Verdana;">
					<td align="center"><b></b></td>
					<td align="center"><b><u>Habitación</u></b></td>
					<td align="center"><b><u>Edad</u></label></b>
					<td align="center"><b><u>UDS.</u>&nbsp;&nbsp;&nbsp;&nbsp;<u>Noches</u></b></td>
					<td align="center"><b><u>Servicios</u></b></td>
					<td align="center"><b><u>Ex.</u></label></b>
					<td align="center"><b><u>TOTAL</u></b></td>
               </tr>
 				<tr><td></td></tr>
               <?

               for  ($i=0;$i<count($tarifas);$i++){

                        if(($tarifas[$i]['numclientes'])>0)
                              {

               ?>
				
				<tr>
					<td  align="center"><?print(substr($tarifas[$i]['idtipopersona'],0,1))?></td>
					<td  align="left"><?print($tarifas[$i]['idtipohabitacion']." (".$tarifas[$i]['tarifa']." €)");?></td>
					<td  align="left"><?print($tarifas[$i]['idedad'])?></td>
					<td  align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?print($tarifas[$i]['numclientes']);?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?print($tarifas[$i]['pernoctas']);?></td>
					<td  align="center"><?print(strtoupper($tarifas[$i]['servicio'])." (".$tarifas[$i]['tarservicio']." €)")?></td>
					<td  align="center"><?print($tarifas[$i]['numex'])?></td>
	                   
                       
					<td  align="center">
                       <?

                          if($cliente=="per"){printf("%1.2f",$tarifas[$i]['ingreso']);}
                          if($cliente=="alb"){printf("%1.2f", $tarifas[$i]['ingreso']);}
                          if($cliente=="gru"){printf("%1.2f",(($tarifas[$i]['numclientes']-$tarifas[$i]['numex'])*$tarifas[$i]['tarifa']*$tarifas[$i]['pernoctas'])+($tarifas[$i]['tarservicio']*($tarifas[$i]['numclientes']-$tarifas[$i]['numex'])*$tarifas[$i]['pernoctas']));}
                          
                        

                       ?>
					</td>
	                   
				</tr>

                <?}}if($fila1['Cuantia_Desperfecto']!=""){?>

				<tr>
					<td></td>
					<td  colspan="3" align="left">Desperfecto</td>
					<td  align="center"><?/*if($fila1['Desperfecto']==""){print("0");}else{print('1');}*/?></td>
					<td></td>
					<td  align="left"><?print($fila1['Cuantia_Desperfecto']);?></td>
               </tr>
               <?}?>
               <tr><td></td></tr>
				<tr>
					<td></td>
					<td colspan="2" style="font-size:14px;"><b>TOTAL (I.V.A. incluido)</b></td>
					<td colspan="3"></td>
					<td style="font-size:14px;" align="left"><b><?printf("%1.2f",($fila1['Cuantia_Desperfecto']+$fila1['Ingreso']));print(" €");?></b></td>
               </tr>
               <tr><td></td></tr>
             <?}?>

		</table> 


        <br><br>
        <?
        
			
        $sql="SELECT * FROM tipo_persona";
                $resul=mysql_query($sql);
                
                $filaper=mysql_fetch_array($resul);
        
		
		
		
		?>
        <table style="border:1pt solid;padding:5px;font-family:Verdana;font-size:12px;">
        
        <tr><td></td></tr> 
        <?if($fila1['Cuantia_Desperfecto']!=""){?>
		<tr><td >
		
					 <table>
          
			<tr>
				<td colspan="3" >&nbsp;&nbsp;<B>COSTE DESPERFECTO </B></td> 
	        </tr>
			<tr>
				
				<td colspan="3" >&nbsp;&nbsp;<b>Concepto&nbsp;&nbsp;<b></td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;&nbsp;<?print(substr($fila1['Desperfecto'],0,100));?></td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;&nbsp;<?print(substr($fila1['Desperfecto'],100,200));?></td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;&nbsp;<?print(substr($fila1['Desperfecto'],200,300));?></td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;&nbsp;<b>Daños&nbsp;&nbsp; </b><?
				printf("%1.2f",$fila1['Cuantia_Desperfecto']);print(" €");
				?></td>
			</tr>

         </table>
		    
        
        </td></tr>
        
         <tr><td></td></tr>
          <tr><td></td></tr>
        
        <?}?>
        <tr><td >

        
			<table>

			<tr>
				<td >
				<table>
					<tr>
						<td></td>
						<td colspan ="2">&nbsp;&nbsp;<B>CLIENTE<B></td>
					</tr>
				 
        	<?
		
        		$sql="SELECT * FROM tipo_persona";
                $resul=mysql_query($sql); 
                for ($i=0;$i<mysql_num_rows($resul);$i++){
                
                $filaper=mysql_fetch_array($resul);
            
			
			?>
		
				<tr><td></td><td colspan ="2">&nbsp;&nbsp;<?printf(strtoupper(substr($filaper['Nombre_Tipo_Persona'],0,1)).":");?>&nbsp;&nbsp;<?printf($filaper['Nombre_Tipo_Persona']);?></td></tr>
			
				<?}?>
					
				</table>
				</td>
				<td>
				<table>
		
        		<?
        		 $sql="SELECT * FROM tipo_servicios";
                $resul=mysql_query($sql); 
                 if($resul>0){
                   ?>
                 	<tr>
				
					<td colspan ="3">&nbsp;&nbsp;<B>TIPO DE SERVICIO<B></td>
					
				</tr>
				<?  
                for ($i=0;$i<mysql_num_rows($resul);$i++){
                
                $filaser=mysql_fetch_array($resul);
                ?>
			
				<tr>
					<td colspan ="2">&nbsp;&nbsp;<?printf(strtoupper(substr($filaser['Id_Servicios'],0,2)).":");?>&nbsp;&nbsp;<?printf($filaser['Descripcion']);?></td>
				</tr>
		
			<?}}else {?>
			
						<td colspan ="3">&nbsp;&nbsp;</td>
			
			<?}?>
					
				</table>
				</td>
			</tr>
			<tr>
				<td colspan ="3"></td>
			</tr>
				
			
		
        
        
        
        </table>
        
        



         <tr><td></td></tr>
	

	</td></tr>
	 
	</table>
	
	<br><br><br><br>
	
	<table>
		<tr>
			<td></td>
			<td colspan="2">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Conforme <br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;el Cliente,
			</td>
	
			<td colspan="4">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Conforme <br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;el Encargado,
			</td>

		<tr>
	</table>



<?
MYSQL_CLOSE($db); //Desconexion base de datos
?>


</div>



