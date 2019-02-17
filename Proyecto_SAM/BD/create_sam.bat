:begin
@ECHO OFF

REM Batch para la creación de la base de datos SAM

echo .
echo .
echo .  ******************************   
echo .  * Preparado para Crear  SAM  *   
echo .  ******************************   
echo .                                   
echo .                                   


pause

type nul >C:\AppServ\www\SAM\Gestion_Interna\paginas\habitaciones.txt

IF EXIST CREATE_SAM.sql. (
	

	echo Creando Tablas...

	C:\AppServ\mysql\bin\mysql -h localhost -u root < CREATE_SAM.sql	

	echo SAM Creado.	

) ELSE (

echo CREATE_SAM.sql no existe

)

pause

:end






