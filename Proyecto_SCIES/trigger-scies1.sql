
delimiter //
CREATE TRIGGER inser_reg BEFORE INSERT ON registros FOR EACH ROW
BEGIN
	DECLARE done INT;
	DECLARE id TINYINT(4);
	DECLARE cur1 CURSOR FOR SELECT Id_Parametro FROM SONDA WHERE Id_Centro = NEW.Id_Centro AND Descripcion_Sonda = "S1";
	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

	OPEN cur1;
	FETCH cur1 INTO id;
	INSERT INTO lectura (Id_Centro, Id_Parametro, Fecha, Hora, Valor) VALUES (NEW.Id_Centro, Id , DATE_FORMAT(NEW.Fecha, "%Y-%m-%d"), DATE_FORMAT(NEW.Fecha, "%H:%i"), id);
	CLOSE cur1;
	END
//
delimiter ;
