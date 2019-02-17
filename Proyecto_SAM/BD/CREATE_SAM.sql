CREATE DATABASE SAM DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE SAM;

CREATE TABLE Colores(
	Id_Color SMALLINT AUTO_INCREMENT PRIMARY KEY,
	Color VARCHAR(6) NOT NULL,
	Estado VARCHAR(1) NOT NULL default '0'
) TYPE = InnoDB;

CREATE TABLE CCAA(
	Id_CCAA VARCHAR(2) NOT NULL PRIMARY KEY,
	Nombre_CCAA VARCHAR(25) NOT NULL
) TYPE = InnoDB;

CREATE TABLE Provincia(
	Id_Pro VARCHAR(2) NOT NULL PRIMARY KEY,
	Nombre_Pro VARCHAR(15) NOT NULL,
	Id_CCAA VARCHAR(2),
	FOREIGN KEY (Id_CCAA) REFERENCES CCAA (Id_CCAA) ON DELETE CASCADE ON UPDATE CASCADE
) TYPE = InnoDB;

CREATE TABLE Pais(
	Id_Pais VARCHAR(2) NOT NULL PRIMARY KEY,
	Nombre_Pais VARCHAR(50) NOT NULL,
	Carta_Europea VARCHAR(1) default 'N' NOT NULL
) TYPE = InnoDB;


CREATE TABLE REAJ(
	Id_Carnet VARCHAR(3) NOT NULL PRIMARY KEY,
	Nombre_Carnet VARCHAR(20) NOT NULL,
	Precio_Carnet FLOAT(5,2) NOT NULL,
	Stock_Carnet TINYINT NOT NULL
) TYPE = InnoDB;


CREATE TABLE Solicitante(
	DNI_Solic VARCHAR(30) NOT NULL PRIMARY KEY,
	Nombre_Solic VARCHAR(20) NOT NULL,
	Apellido1_Solic VARCHAR(30) NOT NULL,
	Apellido2_Solic VARCHAR(30),
	Fecha DATE NOT NULL,
	Id_Carnet VARCHAR(3),
	Numero VARCHAR(8),
	FOREIGN KEY (Id_Carnet) REFERENCES REAJ (Id_Carnet) ON UPDATE CASCADE ON DELETE CASCADE
) TYPE = InnoDB;


CREATE TABLE Tipo_Habitacion(
	Id_Tipo_Hab TINYINT NOT NULL PRIMARY KEY,
	Nombre_Tipo_Hab VARCHAR(20) NOT NULL,
	Reservable VARCHAR(1) default 'S',
	Compartida varchar(1) NOT NULL default 'S'
) TYPE = InnoDB;


CREATE TABLE Habitacion(
	Id_Hab VARCHAR(5) PRIMARY KEY,
	Camas_Hab TINYINT NOT NULL
) TYPE = InnoDB;


CREATE TABLE Cambio_Tipo_Habitacion(
	Id_Hab VARCHAR(5),
	Id_Tipo_Hab TINYINT,
	Fecha DATE NOT NULL,
	PRIMARY KEY(Id_Hab, Fecha),
	FOREIGN KEY (Id_Tipo_Hab) REFERENCES Tipo_Habitacion (Id_Tipo_Hab) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (Id_Hab) REFERENCES Habitacion (Id_Hab) ON DELETE CASCADE ON UPDATE CASCADE
) TYPE = InnoDB;

CREATE TABLE PRA(
	DNI_PRA VARCHAR(30) NOT NULL PRIMARY KEY, 
	Nombre_PRA VARCHAR(20) NOT NULL, 	
	Apellido1_PRA VARCHAR(30) NOT NULL, 
	Apellido2_PRA VARCHAR(30), 
	Tfno_PRA VARCHAR(13) NOT NULL,
	Email_PRA VARCHAR(100)
) TYPE = InnoDB;

CREATE TABLE Detalles(
	DNI_PRA VARCHAR(30) NOT NULL,
	Fecha_Llegada DATE NOT NULL,
	Fecha_Salida DATE NOT NULL,
	PerNocta TINYINT NOT NULL,
	Llegada_Tarde VARCHAR(100),
	Ingreso FLOAT(6,2) default 0,
	Observaciones_PRA VARCHAR(255),
	Nombre_Empleado VARCHAR(50) NOT NULL,
	Fecha_Reserva DATE NOT NULL,
	Localizador_Reserva VARCHAR(20),
	PRIMARY KEY(DNI_PRA, Fecha_Llegada),
	FOREIGN KEY (DNI_PRA) REFERENCES PRA (DNI_PRA) ON DELETE CASCADE ON UPDATE CASCADE
) TYPE = InnoDB;

CREATE TABLE Reserva(
	DNI_PRA VARCHAR(30) NOT NULL,
	Fecha_Llegada DATE NOT NULL, 
	Id_Hab VARCHAR(5),
	Num_Camas TINYINT,
	Num_Camas_Indiv TINYINT,
	PRIMARY KEY(DNI_PRA, Fecha_Llegada, Id_Hab),
	index (DNI_PRA, Fecha_Llegada),
	index (Id_Hab),
	FOREIGN KEY (DNI_PRA, Fecha_Llegada) REFERENCES Detalles (DNI_PRA, Fecha_Llegada) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (Id_Hab) REFERENCES Habitacion (Id_Hab) ON UPDATE CASCADE
) TYPE = InnoDB;

CREATE TABLE Incidencias (
	DNI_Inc VARCHAR(30) NOT NULL,
	Fecha_Inc DATE NOT NULL,
	Nombre_Inc VARCHAR(50) NOT NULL,
	Apellido1_Inc VARCHAR(30),
	Apellido2_Inc VARCHAR(30),
	Concepto VARCHAR(255) NOT NULL,
	Resolucion VARCHAR(255),
	Nombre_Resp VARCHAR(50) NOT NULL,
	PRIMARY KEY(DNI_Inc,Fecha_Inc)
) TYPE = InnoDB;

CREATE TABLE Cliente(
	DNI_Cl VARCHAR(30) NOT NULL PRIMARY KEY,
	Nombre_Cl VARCHAR(20) NOT NULL,
	Apellido1_Cl VARCHAR(30) NOT NULL,
	Apellido2_Cl VARCHAR(30),
	Direccion_Cl VARCHAR(150),
	CP_Cl VARCHAR(5),
	Localidad_Cl VARCHAR(150),
	Id_Pro VARCHAR(2) default NULL,
	Id_Pais VARCHAR(2) default NULL,
	Fecha_Nacimiento_Cl DATE NOT NULL,
	Sexo_Cl VARCHAR(1) NOT NULL,
	Tfno_Cl VARCHAR(13),
	Observaciones_Cl VARCHAR(256),
	Email_Cl VARCHAR(100),
	Tipo_documentacion VARCHAR(1) NOT NULL,
        Fecha_Expedicion DATE NOT NULL,
	FOREIGN KEY (Id_Pro) REFERENCES Provincia(Id_Pro) ON DELETE SET NULL ON UPDATE CASCADE,
	FOREIGN KEY (Id_Pais) REFERENCES Pais(Id_Pais) ON DELETE SET NULL ON UPDATE CASCADE
)TYPE = InnoDB;

CREATE TABLE Pernocta(
	DNI_Cl VARCHAR(30) NOT NULL,
	Id_Hab VARCHAR(5),
	Fecha_Llegada DATE NOT NULL, 
	Fecha_Salida DATE NOT NULL,
	PerNocta TINYINT NOT NULL, 
	Noches_Pagadas TINYINT default 0, 
	Ingreso FLOAT(6,2) default 0,
	PRIMARY KEY(DNI_Cl, Id_Hab, Fecha_Llegada),
	FOREIGN KEY (DNI_Cl) REFERENCES Cliente(DNI_Cl) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (Id_Hab) REFERENCES Habitacion(Id_Hab) ON UPDATE CASCADE
) TYPE=InnoDB;

CREATE TABLE Grupo(
	Nombre_Gr VARCHAR(50) NOT NULL PRIMARY KEY,
	Direccion_Gr VARCHAR(150),
	Localidad_Gr VARCHAR(150),
	Id_Pro VARCHAR(2) default NULL,
	Id_Pais VARCHAR(2) default NULL,
	Email_Gr VARCHAR(100),
	CIF VARCHAR(13),
	FOREIGN KEY (Id_Pro) REFERENCES Provincia(Id_Pro) ON DELETE SET NULL ON UPDATE CASCADE,
	FOREIGN KEY (Id_Pais) REFERENCES Pais(Id_Pais) ON DELETE SET NULL ON UPDATE CASCADE
)TYPE=InnoDB;
    
CREATE TABLE Estancia_Gr(
	Nombre_Gr VARCHAR(50) NOT NULL,
	Fecha_Llegada DATE NOT NULL,
	Fecha_Salida DATE NOT NULL,
	DNI_Repres VARCHAR(30) NOT NULL,
	Nombre_Repres VARCHAR(20) NOT NULL,
	Apellido1_Repres VARCHAR(30) NOT NULL,
	Apellido2_Repres VARCHAR(30),
	Tfno_Repres VARCHAR(13),
	Ingreso FLOAT(6,2) default 0,
	Noches_Pagadas TINYINT default 0,
	Num_Personas TINYINT NOT NULL,
	Num_Mujeres TINYINT NOT NULL,
	Num_Hombres TINYINT NOT NULL,
	PerNocta TINYINT NOT NULL,
	Llegada_Tarde VARCHAR(100),
	HGr0_9 TINYINT NOT NULL default 0,
	HGr10_19 TINYINT NOT NULL default 0,
	HGr20_25 TINYINT NOT NULL default 0,
	HGr26_29 TINYINT NOT NULL default 0,
	HGr30_39 TINYINT NOT NULL default 0,
	HGr40_49 TINYINT NOT NULL default 0,
	HGr50_59 TINYINT NOT NULL default 0,
	HGr60_69 TINYINT NOT NULL default 0,
	HGrOtras TINYINT NOT NULL default 0,
	FGr0_9 TINYINT NOT NULL default 0,
	FGr10_19 TINYINT NOT NULL default 0,
	FGr20_25 TINYINT NOT NULL default 0,
	FGr26_29 TINYINT NOT NULL default 0,
	FGr30_39 TINYINT NOT NULL default 0,
	FGr40_49 TINYINT NOT NULL default 0,
	FGr50_59 TINYINT NOT NULL default 0,
	FGr60_69 TINYINT NOT NULL default 0,
	FGrOtras TINYINT NOT NULL default 0,
	Id_Color SMALLINT,
	Tipo_documentacion VARCHAR(1) NOT NULL,
        Fecha_Expedicion DATE NOT NULL,
        Fecha_nacimiento DATE NOT NULL,
        Id_Pais_nacionalidad varchar(2) NOT NULL,
	PRIMARY KEY(Nombre_Gr,Fecha_Llegada),
	FOREIGN KEY (Id_Pais_nacionalidad) REFERENCES Pais(Id_Pais),
	FOREIGN KEY(Id_Color) REFERENCES Colores(Id_Color) ON DELETE SET NULL ON UPDATE CASCADE,
	FOREIGN KEY(Nombre_Gr) REFERENCES Grupo(Nombre_Gr) ON DELETE CASCADE ON UPDATE CASCADE
)TYPE=InnoDB;

CREATE TABLE Edad(
	Id_Edad TINYINT NOT NULL PRIMARY KEY auto_increment,
	Nombre_Edad VARCHAR(30) NOT NULL,
	Edad_Min TINYINT NOT NULL,
	Edad_Max TINYINT NOT NULL
)TYPE = InnoDB;

CREATE TABLE Pernocta_P (
	DNI_Cl VARCHAR(30) NOT NULL, 
	Id_Hab VARCHAR(5), 
	Fecha_Llegada DATE NOT NULL, 
	Fecha_Salida DATE NOT NULL, 
	PerNocta TINYINT NOT NULL default 1, 
	Noches_Pagadas TINYINT default 0,
	M_P VARCHAR(1) NOT NULL, 
	Lesion VARCHAR(1) DEFAULT "N", 
	PRIMARY KEY(DNI_Cl, Fecha_Llegada), 
	FOREIGN KEY (DNI_Cl) REFERENCES Cliente(DNI_Cl) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (Id_Hab) REFERENCES Habitacion(Id_Hab) ON UPDATE CASCADE
) TYPE=InnoDB;

CREATE TABLE Pernocta_Gr(
	Nombre_Gr VARCHAR(50) NOT NULL,
	Id_Hab VARCHAR(5),
	Id_Edad TINYINT NOT NULL,
	Fecha_Llegada DATE NOT NULL,
	Num_Personas TINYINT NOT NULL,
	PRIMARY KEY (Nombre_Gr,Id_Hab,Id_Edad,Fecha_Llegada),
	FOREIGN KEY(Nombre_Gr, Fecha_Llegada) REFERENCES Estancia_Gr(Nombre_gr, Fecha_Llegada) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY(Id_Hab) REFERENCES Habitacion(Id_Hab) ON UPDATE CASCADE,
	FOREIGN KEY(Id_Edad) REFERENCES Edad(Id_Edad) ON UPDATE CASCADE
)TYPE=InnoDB;

CREATE TABLE Factura(
	Num_Factura VARCHAR (10) NOT NULL PRIMARY KEY,
	DNI VARCHAR(30) NOT NULL,
	Fecha_Factura DATE NOT NULL,
	Desperfecto VARCHAR(255),
	Cuantia_Desperfecto FLOAT(6,2),
	Nombre VARCHAR(20) NOT NULL,
	Apellido1 VARCHAR(30) NOT NULL,
	Apellido2 VARCHAR(30),
	Ingreso FLOAT(6,2) default 0
)TYPE = InnoDB;

CREATE TABLE Tipo_Persona(
	Id_Tipo_Persona TINYINT NOT NULL PRIMARY KEY,
	Nombre_Tipo_Persona VARCHAR(15) NOT NULL
)TYPE = InnoDB;

CREATE TABLE Tarifa(
	Tarifa FLOAT(5,2) NOT NULL,
	Id_Edad TINYINT NOT NULL,
	Id_Tipo_Hab TINYINT,
	Id_Tipo_Persona TINYINT,
	PRIMARY KEY (Id_Edad,Id_Tipo_Hab,Id_Tipo_Persona),
	FOREIGN KEY (Id_Edad) REFERENCES Edad (Id_Edad) ON UPDATE CASCADE,
	FOREIGN KEY (Id_Tipo_Hab) REFERENCES Tipo_Habitacion (Id_Tipo_Hab) ON UPDATE CASCADE,
	FOREIGN KEY (Id_Tipo_Persona) REFERENCES Tipo_Persona (Id_Tipo_Persona)	ON UPDATE CASCADE
)TYPE = InnoDB;

CREATE TABLE Genera (
	DNI_Cl VARCHAR(30) NOT NULL,
	Id_Hab VARCHAR(5) NOT NULL,
	Fecha_Llegada DATE NOT NULL,
	Num_Factura VARCHAR(10) NOT NULL,
	Exento VARCHAR(1) NOT NULL,
	PRIMARY KEY (DNI_Cl,Id_Hab,Fecha_Llegada,Num_Factura),
	FOREIGN KEY (DNI_Cl, Id_Hab, Fecha_Llegada) REFERENCES PERNOCTA(DNI_Cl, Id_Hab, Fecha_Llegada) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (Num_Factura) REFERENCES FACTURA(Num_Factura) ON DELETE CASCADE ON UPDATE CASCADE
) TYPE = InnoDB;

CREATE TABLE Genera_Gr (
	Nombre_Gr VARCHAR(50),
	Fecha_Llegada DATE,
	Id_Hab VARCHAR(5),
	Num_Factura VARCHAR(10),
	Id_Edad TINYINT,
	Num_Facturados TINYINT,
	Num_Exentos TINYINT,
	PRIMARY KEY (Nombre_Gr,Fecha_Llegada,Id_Hab,Num_Factura,Id_Edad),
	FOREIGN KEY (Nombre_Gr, Id_Hab, Id_Edad, Fecha_Llegada) REFERENCES PERNOCTA_GR(Nombre_Gr, Id_Hab, Id_Edad, Fecha_Llegada) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (Num_Factura) REFERENCES FACTURA(Num_Factura) ON DELETE CASCADE ON UPDATE CASCADE
) TYPE = InnoDB;

CREATE TABLE Genera_Reserva (
	DNI_PRA VARCHAR(30) NOT NULL,
	Id_Hab VARCHAR(5) NOT NULL,
	Fecha_Llegada DATE NOT NULL,
	Num_Factura VARCHAR(10) NOT NULL,
	Presentado VARCHAR(1) NOT NULL,
	PRIMARY KEY (DNI_PRA,Id_Hab,Fecha_Llegada,Num_Factura),
	FOREIGN KEY (DNI_PRA,Fecha_Llegada,Id_Hab) REFERENCES Reserva (DNI_PRA,Fecha_Llegada,Id_Hab) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (Num_Factura) REFERENCES Factura (Num_Factura) ON DELETE CASCADE ON UPDATE CASCADE
) TYPE= InnoDB;

CREATE TABLE Genera_P (
	DNI_CL VARCHAR(30) NOT NULL,
	Id_Hab VARCHAR(5) NOT NULL,
	Fecha_Llegada DATE NOT NULL,
	Num_Factura VARCHAR(10) NOT NULL,
	PRIMARY KEY (DNI_CL,Fecha_Llegada,Num_Factura),
	FOREIGN KEY (DNI_CL,Fecha_Llegada) REFERENCES Pernocta_P (DNI_CL, Fecha_Llegada) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (Num_Factura) REFERENCES Factura (Num_Factura) ON DELETE CASCADE ON UPDATE CASCADE
) TYPE= InnoDB;

CREATE TABLE Taquilla(
	Id_Taquilla TINYINT NOT NULL,
	Id_Hab VARCHAR(5),
	DNI_Cl VARCHAR(30),
	Estado_Taq VARCHAR(1) DEFAULT 'H',
	Nombre_Gr VARCHAR(50),
	PRIMARY KEY (Id_Hab,Id_Taquilla),
	FOREIGN KEY (Id_Hab) REFERENCES Habitacion (Id_Hab) ON UPDATE CASCADE
) TYPE = InnoDB;

CREATE TABLE Usuario(
	Usuario VARCHAR(16) not null primary key,
	Password VARCHAR(41) not null,
	OnLine VARCHAR(1) not null,
	Reservas VARCHAR(1) not null,
	Peregrino VARCHAR(1) not null,
	Alberguista VARCHAR(1) not null,	
	Grupos VARCHAR(1) not null,
	Facturacion VARCHAR(1) not null,
	Incidencias VARCHAR(1) not null,
	TarifasEdades VARCHAR(1) not null,
	Estadisticas VARCHAR(1) not null,
	InfPolicial VARCHAR(1) not null,
	REAJ VARCHAR(1) not null,
	InternaREAJ VARCHAR(1) not null,
	Usuarios VARCHAR(1) not null,
	Taquillas VARCHAR(1) not null,
	InternaTaquillas VARCHAR(1) not null,
	Habitaciones VARCHAR(1) not null,
	InternaHabitaciones VARCHAR(1) not null,
	Skin VARCHAR(10) NOT NULL default 'azul' 
) TYPE = InnoDB;

CREATE TABLE componentes_grupo(
DNI varchar( 30 ) NOT NULL ,
Nombre_Gr varchar( 50 ) NOT NULL ,
Fecha_Llegada date NOT NULL ,
Tipo_documentacion varchar( 1 ) NOT NULL ,
Fecha_Expedicion date NOT NULL ,
Nombre varchar( 20 ) NOT NULL ,
Apellido1 varchar( 30 ) NOT NULL ,
Apellido2 varchar( 30 ) default NULL ,
Sexo varchar( 1 ) NOT NULL ,
Fecha_nacimiento date NOT NULL ,
Id_Pais_nacionalidad varchar( 2 ) NOT NULL ,
PRIMARY KEY (DNI, Nombre_Gr, Fecha_Llegada ) ,
FOREIGN KEY ( Nombre_Gr, Fecha_Llegada ) REFERENCES estancia_gr( Nombre_Gr, Fecha_Llegada ) ON UPDATE CASCADE ON DELETE CASCADE ,
FOREIGN KEY ( Id_Pais_nacionalidad ) REFERENCES pais( Id_Pais ) ON UPDATE CASCADE 

)TYPE = InnoDB;

CREATE TABLE Albergue(
        CIF  VARCHAR(9) PRIMARY KEY,
	Nombre_Alb VARCHAR(30) NOT NULL,
	Num_Cuenta VARCHAR(20) NOT NULL,
	Entidad	VARCHAR(30) NOT NULL,
	Win VARCHAR(20) NOT NULL,
	Iban VARCHAR(10) NOT NULL,
	Direccion_Alb VARCHAR(150),
	CP_Alb VARCHAR(5),
	Localidad_Alb VARCHAR(150),
	Id_Pro VARCHAR(2) default NULL,
	Id_Pais VARCHAR(2) default NULL,
	Tfno1_Alb VARCHAR(13),
	Tfno2_Alb VARCHAR(13),
	Fax_Alb VARCHAR(13),
	Email_Alb VARCHAR(100),
	Fianza VARCHAR(2),
	Tipo_registro_Alb VARCHAR(1) NOT NULL,
	Tipo_registro_Datos VARCHAR(1) NOT NULL,
	Cod_Establecimiento VARCHAR(10) NOT NULL,
	FOREIGN KEY (Id_Pro) REFERENCES Provincia(Id_Pro) ON DELETE SET NULL ON UPDATE CASCADE,
	FOREIGN KEY (Id_Pais) REFERENCES Pais(Id_Pais) ON DELETE SET NULL ON UPDATE CASCADE
)TYPE = InnoDB;



/* PROCEDIMIENTOS ALMACENADOS PARA LÓGICA DE SERVICIOS */

delimiter //

Create procedure drop_foreings()

begin      
                         
                   
ALTER TABLE pernocta DROP FOREIGN KEY pernocta_s;
ALTER TABLE pernocta_p DROP FOREIGN KEY pernocta_p_s;
ALTER TABLE estancia_gr DROP FOREIGN KEY estancia_gr_s;

end;//

Create procedure drop_columns()

begin
                   
ALTER TABLE pernocta DROP column Id_Servicios;
ALTER TABLE pernocta_p DROP column Id_Servicios;
ALTER TABLE estancia_gr DROP column Id_Servicios;        

end;//
     
Create procedure add_columns()

begin

  ALTER TABLE pernocta ADD column Id_Servicios varchar(4)  default 'sa';
  ALTER TABLE pernocta_p ADD column Id_Servicios varchar(4) default 'sa';
  ALTER TABLE estancia_gr ADD column Id_Servicios varchar(4) default 'sa';

end;//   

Create procedure add_constraints()

begin

  ALTER TABLE pernocta ADD CONSTRAINT pernocta_s FOREIGN KEY (Id_Servicios) REFERENCES tipo_servicios(Id_Servicios) ON DELETE SET NULL ON UPDATE CASCADE;
  ALTER TABLE pernocta_p ADD CONSTRAINT pernocta_p_s FOREIGN KEY (Id_Servicios) REFERENCES tipo_servicios(Id_Servicios) ON DELETE SET NULL ON UPDATE CASCADE;
  ALTER TABLE estancia_gr ADD CONSTRAINT estancia_gr_s FOREIGN KEY (Id_Servicios) REFERENCES tipo_servicios(Id_Servicios) ON DELETE SET NULL ON UPDATE CASCADE;

end;//         

Create procedure add_table_tipo_servicios()

begin

DECLARE descripcion varchar(255) charset latin2;
DECLARE defecto varchar(4) default 'sa';

SET descripcion = 'Sólo alojamiento';

CREATE TABLE tipo_servicios (
	Id_Servicios varchar(4) not null,
	Descripcion Varchar(255),
	PRIMARY KEY (Id_Servicios)      
) ENGINE = INNODB;

INSERT INTO tipo_servicios VALUES (defecto,convert(descripcion using latin2));

end;//

Create procedure add_table_servicios()

begin


DECLARE precio float(6,2) default 0;
DECLARE Edad tinyint(4);
DECLARE defecto varchar(4) default 'sa';
DECLARE error INT DEFAULT 0;
DECLARE cur1 CURSOR FOR SELECT Id_Edad from edad;
DECLARE EXIT HANDLER FOR SQLSTATE '02000' SET error = 1;

open cur1;

CREATE TABLE servicios (
	Id_Servicios varchar(4),
	Id_Edad tinyint(4),	
	Precio float(6,2),
	PRIMARY KEY (Id_Servicios,Id_Edad),
	FOREIGN KEY (Id_Edad) REFERENCES edad(Id_Edad),
	FOREIGN KEY (Id_Servicios) REFERENCES tipo_servicios(Id_Servicios) on update cascade
) ENGINE = INNODB;



bucle:LOOP

	FETCH cur1 INTO Edad;        

	IF error THEN LEAVE bucle;
	END IF;      

	INSERT INTO servicios VALUES (defecto,Edad,precio);

END LOOP bucle;

CLOSE cur1;

end;//

Create procedure annadir_edad_servicios()

begin

DECLARE id_serv varchar(4);
DECLARE descrip varchar(255);
DECLARE prec float(6,2);
DECLARE edad tinyint(4);
DECLARE error INT DEFAULT 0;
DECLARE cursor_edad CURSOR FOR select id_edad from edad where id_edad not in (select distinct(id_edad ) from servicios);
DECLARE cursor_servicio CURSOR FOR SELECT DISTINCT  Id_Servicios FROM tipo_servicios ORDER BY Id_Servicios;
DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET error = 1;


OPEN cursor_edad;

bucle_edad:LOOP

	FETCH cursor_edad INTO edad;

	IF error THEN LEAVE bucle_edad;
	END IF;

	OPEN cursor_servicio;

	bucle_serv:LOOP		

		FETCH cursor_servicio INTO id_serv ;

		IF error THEN LEAVE bucle_serv;
		END IF;

		INSERT INTO servicios (Id_Servicios, Id_Edad, Precio) VALUES (id_serv,edad,0);

	END LOOP bucle_serv;
	
	SET error = 0;

	CLOSE cursor_servicio;
	

END LOOP bucle_edad;


CLOSE cursor_edad;

end;//

delimiter ;



