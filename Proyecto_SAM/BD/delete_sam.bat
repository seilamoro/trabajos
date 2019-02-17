:begin
@ECHO OFF

REM Batch para el borrado de la base de datos SAM

echo .
echo .
echo .  ******************************   
echo .  * Preparado para Borrar SAM  *   
echo .  ******************************   
echo .                                   
echo .                                  
echo .                                   
echo .  Esta seguro que quiere borrarla? 
echo .                                   
echo .
echo Presione CTRL-C para salir 

pause



IF EXIST DELETE_SAM.sql (

	echo Borrando base de datos...

	C:\AppServ\mysql\bin\mysql -h localhost -u root < DELETE_SAM.sql	

	echo SAM Borrado.
	

) ELSE (

echo DELETE_SAM.sql no existe.

)
del C:\AppServ\www\SAM\Gestion_Interna\paginas\habitaciones.txt
pause








