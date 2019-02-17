:begin
@ECHO OFF

REM Batch para la inserción de datos en la base de datos SAM

echo .
echo .
echo .  ****************************************   
echo .  * Preparado para Insertar datos  SAM   *   
echo .  ****************************************   
echo .                                   
echo .                                   


pause

IF EXIST INSERT_SAM.sql (

			echo Introduciendo datos...

			C:\AppServ\mysql\bin\mysql -h localhost -u root  --default-character-set=latin1 < INSERT_SAM.sql
			) ELSE (

	echo INSERT_SAM.sql no existe.
	)

echo Datos introducidos.

pause

