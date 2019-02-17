DROP DATABASE IF EXISTS SCIES;
CREATE DATABASE SCIES;
USE SCIES;

CREATE TABLE `centro` (
  `Id_Centro` tinyint(4) NOT NULL auto_increment,
  `Nombre` varchar(255) NOT NULL,
  `Direccion` varchar(50) NOT NULL,
  `Telefono` varchar(12) NOT NULL,
  `Localidad` varchar(50) NOT NULL,
  `Fecha_Inicio` date NOT NULL,
  `Fecha_Fin` date NOT NULL,
  PRIMARY KEY  (`Id_Centro`)
) TYPE = InnoDB;

CREATE TABLE `componente` (
  `Id_Componente` tinyint(5) NOT NULL auto_increment,
  `Nombre` varchar(150) NOT NULL,
  `Marca` varchar(50) NOT NULL,
  `Modelo` varchar(50) NOT NULL,
  `Proveedor` varchar(50) default NULL,
  `Contacto` varchar(50) default NULL,
  `Telefono` varchar(12) default NULL,
  PRIMARY KEY  (`Id_Componente`)
) TYPE = InnoDB;

CREATE TABLE `Centro_comp` (
  `Id_Centro` tinyint(4) NOT NULL,
  `Id_Componente` tinyint(5) NOT NULL,
  `Fecha_Instalacion` date default NULL,
  `Cantidad` tinyint(4) NOT NULL default 1,
  PRIMARY KEY  (`Id_Centro`,`Id_Componente`),
  FOREIGN KEY (`Id_Centro`) REFERENCES `centro` (`Id_Centro`) on update cascade on delete cascade,
  FOREIGN KEY (`Id_Componente`) REFERENCES `componente` (`Id_Componente`) on update cascade on delete cascade
) TYPE = InnoDB;


CREATE TABLE `subcomponente` (
  `Id_Subcomponente` tinyint(5) NOT NULL auto_increment,
  `Nombre` varchar(150) default NULL,
  PRIMARY KEY  (`Id_Subcomponente`)
) TYPE = InnoDB;

CREATE TABLE `Comp_subcomp` (
  `Id_Componente` tinyint(5) NOT NULL,
  `Id_Subcomponente` tinyint(5) NOT NULL,
  cantidad tinyint(4) NOT NULL default 1,
  PRIMARY KEY  (`Id_Componente`,`Id_Subcomponente`),	
  FOREIGN KEY (`Id_Componente`) REFERENCES `componente` (`Id_Componente`) on update cascade on delete cascade,
  FOREIGN KEY (`Id_Subcomponente`) REFERENCES `subcomponente` (`Id_Subcomponente`) on update cascade 
) TYPE = InnoDB;

CREATE TABLE `operacion_prev` (
  `Id_operacion_prev` tinyint(4) NOT NULL auto_increment,
  `Nombre` varchar(150) default NULL,
  `Periodicidad` tinyint(4) default NULL,
  PRIMARY KEY  (`Id_operacion_prev`)
) TYPE = InnoDB;

CREATE TABLE `operaciones` (
  `Id_Subcomponente` tinyint(5) NOT NULL,
  `Id_operacion_prev` tinyint(4) NOT NULL,
  PRIMARY KEY  (`Id_Subcomponente`,`Id_operacion_prev`),
  FOREIGN KEY (`Id_Subcomponente`) REFERENCES `subcomponente` (`Id_Subcomponente`) on update cascade on delete cascade,
  FOREIGN KEY (`Id_operacion_prev`) REFERENCES `operacion_prev` (`Id_operacion_prev`) on update cascade
)TYPE = InnoDB;

CREATE TABLE `hoja_correctiva` (
  `Num_Hoja` int(4) NOT NULL auto_increment,
  `Id_Centro` tinyint(4) NOT NULL,
  `Empresa` varchar(150) default NULL,
  `Operario` varchar(150) NOT NULL,
  `Observaciones_Generales` varchar(255) default NULL,
  `Telefono` varchar(12) default NULL,
  `fecha` date not null,
  PRIMARY KEY  (`Num_Hoja`),
  FOREIGN KEY (`Id_Centro`) REFERENCES `centro` (`Id_Centro`) on update cascade
) TYPE = InnoDB;

CREATE TABLE `operacion_correctiva` (
  `Id_operacion_corr` tinyint(4) NOT NULL auto_increment,
  `Descripcion` varchar(150) not NULL,
  PRIMARY KEY  (`Id_operacion_corr`)
) TYPE = InnoDB;

CREATE TABLE `lineas_hoja` (
  `Num_Hoja` int(4) NOT NULL,
  `Id_operacion_corr` tinyint(4) NOT NULL,
  Correcto bit default null,
  Observaciones varchar(255) default null,
  PRIMARY KEY  (`Num_Hoja`,`Id_operacion_corr`),
  FOREIGN KEY (`Num_Hoja`) REFERENCES `hoja_correctiva` (`Num_Hoja`) on update cascade on delete cascade,
  FOREIGN KEY (`Id_operacion_corr`) REFERENCES `operacion_correctiva` (`Id_operacion_corr`) on update cascade
) TYPE = InnoDB;

CREATE TABLE `parametro` (
  `Id_Parametro` tinyint(4) NOT NULL auto_increment,
  `Nombre_Param` varchar(30) NOT NULL,
  `Descripcion_Param` varchar(150) default NULL,
  PRIMARY KEY  (`Id_Parametro`)
) TYPE = InnoDB;

CREATE TABLE `sonda` (
  `Id_Centro` tinyint(4) NOT NULL,
  `Id_Parametro` tinyint(4) NOT NULL,
  `Descripcion_Sonda` varchar(5) NOT NULL,
  PRIMARY KEY  (`Id_Centro`,`Id_Parametro`),
  FOREIGN KEY (`Id_Centro`) REFERENCES `centro` (`Id_Centro`) on update cascade on delete cascade,
  FOREIGN KEY (`Id_Parametro`) REFERENCES `parametro` (`Id_Parametro`) on update cascade on delete cascade
) TYPE = InnoDB;

CREATE TABLE `lectura` (
  `Id_Centro` tinyint(4) NOT NULL,
  `Id_Parametro` tinyint(4) NOT NULL,
  `Fecha` date NOT NULL,
  `Hora` time NOT NULL,
  `Valor` float(5,2) NOT NULL,
  PRIMARY KEY  (`Id_Centro`,`Id_Parametro`,`Fecha`,`Hora`),
  FOREIGN KEY (`Id_Centro`) REFERENCES `centro` (`Id_Centro`) on update cascade on delete cascade,
  FOREIGN KEY (`Id_Parametro`) REFERENCES `parametro` (`Id_Parametro`) on update cascade on delete cascade
) TYPE = InnoDB;



CREATE TABLE `registros` (
  `Id_Centro` tinyint(4) NOT NULL,
  `Fecha` datetime NOT NULL,
  `temperature_sensor_1` varchar(10) NOT NULL,
  `temperature_sensor_2` varchar(10) NOT NULL,
  `temperature_sensor_3` varchar(10) NOT NULL,
  `temperature_sensor_4` varchar(10) NOT NULL,
  `temperature_sensor_5` varchar(10) NOT NULL,
  `temperature_sensor_6` varchar(10) NOT NULL,
  `temperature_sensor_7` varchar(10) NOT NULL,
  `temperature_sensor_8` varchar(10) NOT NULL,
  `temperature_sensor_9` varchar(10) NOT NULL,
  `temperature_sensor_10` varchar(10) NOT NULL,
  `temperature_sensor_11` varchar(10) NOT NULL,
  `temperature_sensor_12` varchar(10) NOT NULL,
  `irradiation` int(11) NOT NULL,
  `impulse_input_1` bigint(20) NOT NULL,
  `impulse_input_2` bigint(20) NOT NULL,
  `sensor_broke_mask` int(11) NOT NULL,
  `sensor_short_mask` int(11) NOT NULL,
  `sensor_usage_mask` int(11) NOT NULL,
  `pump_speed_1` int(11) NOT NULL,
  `pump_speed_2` int(11) NOT NULL,
  `pump_speed_3` int(11) NOT NULL,
  `pump_speed_4` int(11) NOT NULL,
  `pump_speed_5` int(11) NOT NULL,
  `pump_speed_6` int(11) NOT NULL,
  `pump_speed_7` int(11) NOT NULL,
  `pump_speed_8` int(11) NOT NULL,
  `pump_speed_9` int(11) NOT NULL,
  `relais_usage_mask` int(11) NOT NULL,
  `error_mask` int(11) NOT NULL,
  `warning_mask` int(11) NOT NULL,
  `controler_version` int(11) NOT NULL,
  PRIMARY KEY  (`Id_Centro`,`Fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `registros`
  ADD CONSTRAINT `registros_ibfk_1` FOREIGN KEY (`Id_Centro`) REFERENCES `centro` (`Id_Centro`) ON DELETE CASCADE ON UPDATE CASCADE;



delimiter //
CREATE TRIGGER inser_reg AFTER INSERT ON registros FOR EACH ROW
BEGIN
	DECLARE done INT;
	DECLARE id1 TINYINT(4);
	DECLARE id2 TINYINT(4);
	DECLARE id3 TINYINT(4);
	DECLARE id4 TINYINT(4);
	DECLARE id5 TINYINT(4);
	DECLARE id6 TINYINT(4);
	DECLARE id7 TINYINT(4);
	DECLARE id8 TINYINT(4);
	DECLARE id9 TINYINT(4);
	DECLARE id10 TINYINT(4);
	DECLARE id11 TINYINT(4);
	DECLARE id12 TINYINT(4);
	DECLARE id13 TINYINT(4);
	DECLARE id14 TINYINT(4);

	DECLARE cur1 CURSOR FOR SELECT Id_Parametro FROM SONDA WHERE Id_Centro = NEW.Id_Centro AND Descripcion_Sonda = "S1";
	DECLARE cur2 CURSOR FOR SELECT Id_Parametro FROM SONDA WHERE Id_Centro = NEW.Id_Centro AND Descripcion_Sonda = "S2";
	DECLARE cur3 CURSOR FOR SELECT Id_Parametro FROM SONDA WHERE Id_Centro = NEW.Id_Centro AND Descripcion_Sonda = "S3";
	DECLARE cur4 CURSOR FOR SELECT Id_Parametro FROM SONDA WHERE Id_Centro = NEW.Id_Centro AND Descripcion_Sonda = "S4";
	DECLARE cur5 CURSOR FOR SELECT Id_Parametro FROM SONDA WHERE Id_Centro = NEW.Id_Centro AND Descripcion_Sonda = "S5";
	DECLARE cur6 CURSOR FOR SELECT Id_Parametro FROM SONDA WHERE Id_Centro = NEW.Id_Centro AND Descripcion_Sonda = "S6";
	DECLARE cur7 CURSOR FOR SELECT Id_Parametro FROM SONDA WHERE Id_Centro = NEW.Id_Centro AND Descripcion_Sonda = "S7";
	DECLARE cur8 CURSOR FOR SELECT Id_Parametro FROM SONDA WHERE Id_Centro = NEW.Id_Centro AND Descripcion_Sonda = "S8";
	DECLARE cur9 CURSOR FOR SELECT Id_Parametro FROM SONDA WHERE Id_Centro = NEW.Id_Centro AND Descripcion_Sonda = "S9";
	DECLARE cur10 CURSOR FOR SELECT Id_Parametro FROM SONDA WHERE Id_Centro = NEW.Id_Centro AND Descripcion_Sonda = "S10";
	DECLARE cur11 CURSOR FOR SELECT Id_Parametro FROM SONDA WHERE Id_Centro = NEW.Id_Centro AND Descripcion_Sonda = "S11";
	DECLARE cur12 CURSOR FOR SELECT Id_Parametro FROM SONDA WHERE Id_Centro = NEW.Id_Centro AND Descripcion_Sonda = "S12";
	DECLARE cur13 CURSOR FOR SELECT Id_Parametro FROM SONDA WHERE Id_Centro = NEW.Id_Centro AND Descripcion_Sonda = "CS10";
	DECLARE cur14 CURSOR FOR SELECT Id_Parametro FROM SONDA WHERE Id_Centro = NEW.Id_Centro AND Descripcion_Sonda = "S14";
	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

	SET done = 0;
	OPEN cur1;
	FETCH cur1 INTO id1;
	IF NEW.temperature_sensor_1 <> '888.8' THEN
		IF NOT done = 1 THEN
			INSERT INTO lectura (Id_Centro, Id_Parametro, Fecha, Hora, Valor) VALUES (NEW.Id_Centro, Id1 , DATE_FORMAT(NEW.Fecha, "%Y-%m-%d"), DATE_FORMAT(NEW.Fecha, "%H:%i"), NEW.temperature_sensor_1);
		END IF;
	END IF;
	SET done = 0;
	CLOSE cur1;

	OPEN cur2;
	FETCH cur2 INTO id2;
	IF NEW.temperature_sensor_2 <> '888.8' THEN
		IF NOT done = 1 THEN
			INSERT INTO lectura (Id_Centro, Id_Parametro, Fecha, Hora, Valor) VALUES (NEW.Id_Centro, Id2 , DATE_FORMAT(NEW.Fecha, "%Y-%m-%d"), DATE_FORMAT(NEW.Fecha, "%H:%i"), NEW.temperature_sensor_2);
		END IF;
	END IF;
	SET done = 0;
	CLOSE cur2;

	OPEN cur3;
	FETCH cur3 INTO id3;
	IF NEW.temperature_sensor_3 <> '888.8' THEN
		IF NOT done = 1 THEN
			INSERT INTO lectura (Id_Centro, Id_Parametro, Fecha, Hora, Valor) VALUES (NEW.Id_Centro, Id3 , DATE_FORMAT(NEW.Fecha, "%Y-%m-%d"), DATE_FORMAT(NEW.Fecha, "%H:%i"), NEW.temperature_sensor_3);
		END IF;
	END IF;
	SET done = 0;
	CLOSE cur3;

	OPEN cur4;
	FETCH cur4 INTO id4;
	IF NEW.temperature_sensor_4 <> '888.8' THEN
		IF NOT done = 1 THEN
			INSERT INTO lectura (Id_Centro, Id_Parametro, Fecha, Hora, Valor) VALUES (NEW.Id_Centro, Id4 , DATE_FORMAT(NEW.Fecha, "%Y-%m-%d"), DATE_FORMAT(NEW.Fecha, "%H:%i"), NEW.temperature_sensor_4);
		END IF;
	END IF;
	SET done = 0;
	CLOSE cur4;

	OPEN cur5;
	FETCH cur5 INTO id5;
	IF NEW.temperature_sensor_5 <> '888.8' THEN
		IF NOT done = 1 THEN
			INSERT INTO lectura (Id_Centro, Id_Parametro, Fecha, Hora, Valor) VALUES (NEW.Id_Centro, Id5 , DATE_FORMAT(NEW.Fecha, "%Y-%m-%d"), DATE_FORMAT(NEW.Fecha, "%H:%i"), NEW.temperature_sensor_5);
		END IF;
	END IF;
	SET done = 0;
	CLOSE cur5;

	OPEN cur6;
	FETCH cur6 INTO id6;
	IF NEW.temperature_sensor_6 <> '888.8' THEN
		IF NOT done = 1 THEN
			INSERT INTO lectura (Id_Centro, Id_Parametro, Fecha, Hora, Valor) VALUES (NEW.Id_Centro, Id6 , DATE_FORMAT(NEW.Fecha, "%Y-%m-%d"), DATE_FORMAT(NEW.Fecha, "%H:%i"), NEW.temperature_sensor_6);
		END IF;
	END IF;
	SET done = 0;
	CLOSE cur6;

	OPEN cur7;
	FETCH cur7 INTO id7;
	IF NEW.temperature_sensor_7 <> '888.8' THEN
		IF NOT done = 1 THEN
			INSERT INTO lectura (Id_Centro, Id_Parametro, Fecha, Hora, Valor) VALUES (NEW.Id_Centro, Id7 , DATE_FORMAT(NEW.Fecha, "%Y-%m-%d"), DATE_FORMAT(NEW.Fecha, "%H:%i"), NEW.temperature_sensor_7);
		END IF;
	END IF;
	SET done = 0;
	CLOSE cur7;

	OPEN cur8;
	FETCH cur8 INTO id8;
	IF NEW.temperature_sensor_8 <> '888.8' THEN
		IF NOT done = 1 THEN
			INSERT INTO lectura (Id_Centro, Id_Parametro, Fecha, Hora, Valor) VALUES (NEW.Id_Centro, Id8 , DATE_FORMAT(NEW.Fecha, "%Y-%m-%d"), DATE_FORMAT(NEW.Fecha, "%H:%i"), NEW.temperature_sensor_8);
		END IF;
	END IF;
	SET done = 0;
	CLOSE cur8;

	OPEN cur9;
	FETCH cur9 INTO id9;
	IF NEW.temperature_sensor_9 <> '888.8' THEN
		IF NOT done = 1 THEN
			INSERT INTO lectura (Id_Centro, Id_Parametro, Fecha, Hora, Valor) VALUES (NEW.Id_Centro, Id9 , DATE_FORMAT(NEW.Fecha, "%Y-%m-%d"), DATE_FORMAT(NEW.Fecha, "%H:%i"), NEW.temperature_sensor_9);
		END IF;
	END IF;
	SET done = 0;
	CLOSE cur9;

	OPEN cur10;
	FETCH cur10 INTO id10;
	IF NEW.temperature_sensor_10 <> '888.8' THEN
		IF NOT done = 1 THEN
			INSERT INTO lectura (Id_Centro, Id_Parametro, Fecha, Hora, Valor) VALUES (NEW.Id_Centro, Id10 , DATE_FORMAT(NEW.Fecha, "%Y-%m-%d"), DATE_FORMAT(NEW.Fecha, "%H:%i"), NEW.temperature_sensor_10);
		END IF;
	END IF;
	SET done = 0;
	CLOSE cur10;

	OPEN cur11;
	FETCH cur11 INTO id11;
	IF NEW.temperature_sensor_11 <> '888.8' THEN
		IF NOT done = 1 THEN
			INSERT INTO lectura (Id_Centro, Id_Parametro, Fecha, Hora, Valor) VALUES (NEW.Id_Centro, Id11 , DATE_FORMAT(NEW.Fecha, "%Y-%m-%d"), DATE_FORMAT(NEW.Fecha, "%H:%i"), NEW.temperature_sensor_11);
		END IF;
	END IF;
	SET done = 0;
	CLOSE cur11;

	OPEN cur12;
	FETCH cur12 INTO id12;
	IF NEW.temperature_sensor_12 <> '888.8' THEN
		IF NOT done = 1 THEN
			INSERT INTO lectura (Id_Centro, Id_Parametro, Fecha, Hora, Valor) VALUES (NEW.Id_Centro, Id12 , DATE_FORMAT(NEW.Fecha, "%Y-%m-%d"), DATE_FORMAT(NEW.Fecha, "%H:%i"), NEW.temperature_sensor_11);
		END IF;
	END IF;
	SET done = 0;
	CLOSE cur12;

	OPEN cur13;
	FETCH cur13 INTO id13;
	IF NEW.irradiation <> 888 THEN
		IF NOT done = 1 THEN
			INSERT INTO lectura (Id_Centro, Id_Parametro, Fecha, Hora, Valor) VALUES (NEW.Id_Centro, Id13 , DATE_FORMAT(NEW.Fecha, "%Y-%m-%d"), DATE_FORMAT(NEW.Fecha, "%H:%i"), NEW.irradiation);
		END IF;
	END IF;
	SET done = 0;
	CLOSE cur13;

	OPEN cur14;
	FETCH cur14 INTO id14;
	IF NEW.pump_speed_1  <> 888 THEN
		IF NOT done = 1 THEN
			INSERT INTO lectura (Id_Centro, Id_Parametro, Fecha, Hora, Valor) VALUES (NEW.Id_Centro, Id14 , DATE_FORMAT(NEW.Fecha, "%Y-%m-%d"), DATE_FORMAT(NEW.Fecha, "%H:%i"), NEW.pump_speed_1);
		END IF;
	END IF;
	SET done = 0;
	CLOSE cur14;

END
//
delimiter ;
