USE SCIES;

--
-- Volcar la base de datos para la tabla `operacion_correctiva`
--

INSERT INTO `operacion_correctiva` VALUES (1, 'Se ha comprobado el correcto funcionamiento  de las bombas');
INSERT INTO `operacion_correctiva` VALUES (2, 'Operario');
INSERT INTO `operacion_correctiva` VALUES (3, 'Meteorolog�a tres d�as anteriores');
INSERT INTO `operacion_correctiva` VALUES (4, 'Lectura contador');
INSERT INTO `operacion_correctiva` VALUES (5, 'Se ha comprobado la T� de recirculaci�n');
INSERT INTO `operacion_correctiva` VALUES (6, 'Se ha revisado el buen funcionamiento de la centralita');
INSERT INTO `operacion_correctiva` VALUES (7, 'Se ha comprobado el buen funcionamiento del �nodo y el c�todo de sacrificio estaba en buen funcionamiento');
INSERT INTO `operacion_correctiva` VALUES (8, 'Se ha comprobado la presi�n del circuito primario');
INSERT INTO `operacion_correctiva` VALUES (9, 'Se ha comprobado la temperatura del acumulador');
INSERT INTO `operacion_correctiva` VALUES (10, 'Se ha observado alguna fuga o anomal�a de la instalaci�n');
INSERT INTO `operacion_correctiva` VALUES (11, 'Se ha comprobado que los colectores est�n en buen estado de uso');
INSERT INTO `operacion_correctiva` VALUES (12, 'Se ha comprobado si las bombas funcionan cuando lo indica el control');
INSERT INTO `operacion_correctiva` VALUES (13, 'T� Colectores; T� Acumulador parte superior; T� Acumulador parte inferior; Presi�n del circuito primario');


-- 
-- Volcar la base de datos para la tabla `operacion_prev`
-- 

INSERT INTO `operacion_prev` VALUES (1, 'IV de diferencias sobre el original', 6);
INSERT INTO `operacion_prev` VALUES (2, 'IV de condensaci�n', 6);
INSERT INTO `operacion_prev` VALUES (3, 'IV de suciedad', 6);
INSERT INTO `operacion_prev` VALUES (4, 'IV de agrietamientos', 6);
INSERT INTO `operacion_prev` VALUES (5, 'IV de deformaciones', 6);
INSERT INTO `operacion_prev` VALUES (6, 'IV de corrosi�n', 6);
INSERT INTO `operacion_prev` VALUES (7, 'IV de oscilaciones', 6);
INSERT INTO `operacion_prev` VALUES (8, 'IV de estado de los orificios de respiraci�n', 6);
INSERT INTO `operacion_prev` VALUES (9, 'IV de aparici�n de fugas', 6);
INSERT INTO `operacion_prev` VALUES (10, 'IV de degradaci�n', 6);
INSERT INTO `operacion_prev` VALUES (11, 'IV de indicios de corrosi�n', 6);
INSERT INTO `operacion_prev` VALUES (12, 'IV de apriete de tornillos', 6);
INSERT INTO `operacion_prev` VALUES (13, 'IV de presencia de lodos en fondo', 24);
INSERT INTO `operacion_prev` VALUES (14, 'IV del desgaste', 12);
INSERT INTO `operacion_prev` VALUES (15, 'IV de ausencia de humedad', 12);
INSERT INTO `operacion_prev` VALUES (16, 'IV de degradaci�n, protecciones, uniones y ausencia de humedad', 6);
INSERT INTO `operacion_prev` VALUES (17, 'IV de uniones y ausencia de humedad', 12);
INSERT INTO `operacion_prev` VALUES (18, 'CF de la eficiencia', 12);
INSERT INTO `operacion_prev` VALUES (19, 'CF de las prestaciones', 12);
INSERT INTO `operacion_prev` VALUES (20, 'CF de apertura y cierre para evitar agarrotamiento', 12);
INSERT INTO `operacion_prev` VALUES (21, 'CF de apertura', 12);
INSERT INTO `operacion_prev` VALUES (22, 'CF de cierre', 12);
INSERT INTO `operacion_prev` VALUES (23, 'CF seg�n presi�n de tarado', 6);
INSERT INTO `operacion_prev` VALUES (24, 'CF seg�n el control dese�ado para el sistema', 12);
INSERT INTO `operacion_prev` VALUES (25, 'Comprobaci�n de densidad', 12);
INSERT INTO `operacion_prev` VALUES (26, 'Comprobaci�n del PH', 12);
INSERT INTO `operacion_prev` VALUES (27, 'Comprobaci�n de Funcionamiento', 12);
INSERT INTO `operacion_prev` VALUES (28, 'Comprobaci�n de estanqueidad', 12);
INSERT INTO `operacion_prev` VALUES (29, 'Comprobaci�n de la presi�n', 6);
INSERT INTO `operacion_prev` VALUES (30, 'Comprobaci�n del nivel', 6);
INSERT INTO `operacion_prev` VALUES (31, 'Comprobaci�n de estanqueidad frente a entrada de polvo', 12);
INSERT INTO `operacion_prev` VALUES (32, 'Comprobaci�n de exactitud de lecturas', 12);
INSERT INTO `operacion_prev` VALUES (33, 'Comprobaci�n remota del correcto almacenamiento de los datos de temperatura y caudal registrados', 1);
INSERT INTO `operacion_prev` VALUES (34, 'Realizaci�n de prueba de presi�n', 24);
INSERT INTO `operacion_prev` VALUES (35, 'Vaciado del aire del botell�n', 6);
INSERT INTO `operacion_prev` VALUES (36, 'Limpieza interior', 60);
INSERT INTO `operacion_prev` VALUES (37, 'Limpieza', 12);


insert into componente(Id_Componente,Nombre,Marca,Modelo,Proveedor,Contacto,Telefono) values(1,"Paneles Solares","Chromagen","CR10_SN","Chromagen","Manuel Garc�a","610574715");
insert into componente(Id_Componente,Nombre,Marca,Modelo,Proveedor,Contacto,Telefono) values(2,"Interacumulador","Emmetti","Comfort C3F-L","San Francisco Calef.","Sonia","987224434");
insert into componente(Id_Componente,Nombre,Marca,Modelo,Proveedor,Contacto,Telefono) values(3,"Bomba","Wilo","Star RS 25/7","San Francisco Calef.","Sonia","987224434");
insert into componente(Id_Componente,Nombre,Marca,Modelo,Proveedor,Contacto,Telefono) values(4,"Bomba recirculaci�n","Grundfos","UPS 25/40","Ricardo Chao","Oliver","987260815");
insert into componente(Id_Componente,Nombre,Marca,Modelo,Proveedor,Contacto,Telefono) values(5,"Centralita","Resol","Delta Sol B","Saclima","Benjam�n","961516162");
insert into componente(Id_Componente,Nombre,Marca,Modelo,Proveedor,Contacto,Telefono) values(6,"Contador de calor�as","ABB","RV 531","San Francisco Calef.","Sonia","987224434");
insert into componente(Id_Componente,Nombre,Marca,Modelo,Proveedor,Contacto,Telefono) values(7,"Anticongelante","Solahart","Hartgard","Saclima","Benjam�n","961516162");
insert into componente(Id_Componente,Nombre,Marca,Modelo,Proveedor,Contacto,Telefono) values(8,"Paneles solares","Solahart","M","Saclima","Benjam�n","961516162");
insert into componente(Id_Componente,Nombre,Marca,Modelo,Proveedor,Contacto,Telefono) values(9,"Acumulador","Portela","316 L","Ricardo Chao","Oliver","987260815");
insert into componente(Id_Componente,Nombre,Marca,Modelo,Proveedor,Contacto,Telefono) values(10,"Intercambiador de placas externo","Cetetherm","CT40CH MST 750l","Ricardo Chao","Oliver","987260815");
insert into componente(Id_Componente,Nombre,Marca,Modelo,Proveedor,Contacto,Telefono) values(11,"Bomba","Myson","CP-63","Ricardo Chao","Oliver","987260815");
insert into componente(Id_Componente,Nombre,Marca,Modelo,Proveedor,Contacto,Telefono) values(12,"Contador de calor�as","Viterra","Sensonic 1.5","Ricardo Chao","Oliver","987260815");
insert into componente(Id_Componente,Nombre,Marca,Modelo,Proveedor,Contacto,Telefono) values(13,"Bomba","Wilo","Top S 30/7","San Francisco Calef.","Sonia","987224434");
insert into componente(Id_Componente,Nombre,Marca,Modelo,Proveedor,Contacto,Telefono) values(14,"Bomba recirculaci�n","Grundfos","UPS 25/40","San Francisco Calef.","Sonia","987224434");
insert into componente(Id_Componente,Nombre,Marca,Modelo,Proveedor,Contacto,Telefono) values(15,"Paneles Solares","Chromagen","CR12-SN","Chromagen","Manuel Garc�a","610574715");
insert into componente(Id_Componente,Nombre,Marca,Modelo,Proveedor,Contacto,Telefono) values(16,"Acumulador","Portela","316 V/P 750l.","Ricardo Chao","Oliver","987260815");
insert into componente(Id_Componente,Nombre,Marca,Modelo,Proveedor,Contacto,Telefono) values(17,"Intercambiador de Placas Externo","Cetetherm","CT40CH MST","Ricardo Chao","Oliver","987260815");
insert into componente(Id_Componente,Nombre,Marca,Modelo,Proveedor,Contacto,Telefono) values(18,"Bomba","Wilo","Star TOP-S 30/10","San Francisco Calef.","Sonia","987224434");
insert into componente(Id_Componente,Nombre,Marca,Modelo,Proveedor,Contacto,Telefono) values(19,"Centralita","Resol","Delta Sol Pro","Saclima","Benjam�n","961516162");
insert into componente(Id_Componente,Nombre,Marca,Modelo,Proveedor,Contacto,Telefono) values(20,"Paneles Solares","Isofot�n","Isonox II","Isofot�n","Enma Gonz�lez","914147862");
insert into componente(Id_Componente,Nombre,Marca,Modelo,Proveedor,Contacto,Telefono) values(21,"Bomba","Wilo","RS 30/7 180","San Francisco Calef.","Sonia","987224434");
insert into componente(Id_Componente,Nombre,Marca,Modelo,Proveedor,Contacto,Telefono) values(22,"Centralita","Resol","Delta Sol ","Saclima","Benjam�n","961516162");


insert into centro(Id_Centro,Nombre,Direccion,Telefono,Localidad) values (1,"Coto Escolar Municipal de Le�n","Paseo del Parque s/n., 24005","987213119","Le�n");
insert into centro(Id_Centro,Nombre,Direccion,Telefono,Localidad) values (2,"�rea Deportiva Puente Castro","Calle La Flecha s/n., 24005","987202556","Le�n");
insert into centro(Id_Centro,Nombre,Direccion,Telefono,Localidad) values (3,"Campo de F�tbol Puente Castro","Calle Jacinto Barrio Aller, s/n 24005 ","","Puente Castro (Le�n)");
insert into centro(Id_Centro,Nombre,Direccion,Telefono,Localidad) values (4,"Piscina Saenz de Miera V.Chicos","Calle Ingeniero Saez de Miera, s/n 24009","987209823","Le�n");
insert into centro(Id_Centro,Nombre,Direccion,Telefono,Localidad) values (5,"Piscina Saenz de Miera V.Chicas","Calle Ingeniero Saez de Miera, s/n 24009","987209823","Le�n");
insert into centro(Id_Centro,Nombre,Direccion,Telefono,Localidad) values (6,"Gumersindo Azc�rate","Calle Francisco Fern�ndez D�ez, s/n 24009","987253101","Le�n");
insert into centro(Id_Centro,Nombre,Direccion,Telefono,Localidad) values (7,"Pabell�n Pol�gono X","Calle Mois�s de Le�n, s/n Pol�gono 10 - 24005","987253101","Le�n");
insert into centro(Id_Centro,Nombre,Direccion,Telefono,Localidad) values (8,"Residencia de Ancianos Virgen del Camino","C/ San Mam�s, 8 - 24007","987225582","Le�n");
insert into centro(Id_Centro,Nombre,Direccion,Telefono,Localidad) values (9,"Pabell�n San Esteban","Calle Dama de Arintero s/n, 24008 LE�N","987241645","Le�n");


insert into subcomponente(Id_Subcomponente,Nombre) values(1,"Colectores");
insert into subcomponente(Id_Subcomponente,Nombre) values(2,"Cubierta transparente");
insert into subcomponente(Id_Subcomponente,Nombre) values(3,"Juntas de degradaci�n");
insert into subcomponente(Id_Subcomponente,Nombre) values(4,"Absorbedor");
insert into subcomponente(Id_Subcomponente,Nombre) values(5,"Carcasa");
insert into subcomponente(Id_Subcomponente,Nombre) values(6,"Conexiones");
insert into subcomponente(Id_Subcomponente,Nombre) values(7,"Estructura");
insert into subcomponente(Id_Subcomponente,Nombre) values(8,"Dep�sito");
insert into subcomponente(Id_Subcomponente,Nombre) values(9,"�nodos sacrificio");
insert into subcomponente(Id_Subcomponente,Nombre) values(10,"Aislamiento");
insert into subcomponente(Id_Subcomponente,Nombre) values(11,"Intercambiador de placas");
insert into subcomponente(Id_Subcomponente,Nombre) values(12,"Intercambiador de serpent�n");
insert into subcomponente(Id_Subcomponente,Nombre) values(13,"Fluido caloportador");
insert into subcomponente(Id_Subcomponente,Nombre) values(14,"Estanqueidad");
insert into subcomponente(Id_Subcomponente,Nombre) values(15,"Aislamiento exterior");
insert into subcomponente(Id_Subcomponente,Nombre) values(16,"Aislamiento interior");
insert into subcomponente(Id_Subcomponente,Nombre) values(17,"Purgador autom�tico");
insert into subcomponente(Id_Subcomponente,Nombre) values(18,"Purgador manual");
insert into subcomponente(Id_Subcomponente,Nombre) values(19,"Bomba");
insert into subcomponente(Id_Subcomponente,Nombre) values(20,"Vaso de expansi�n cerrado");
insert into subcomponente(Id_Subcomponente,Nombre) values(21,"Vaso de expansi�n abierto");
insert into subcomponente(Id_Subcomponente,Nombre) values(22,"Sistema de llenado");
insert into subcomponente(Id_Subcomponente,Nombre) values(23,"V�lvula de corte");
insert into subcomponente(Id_Subcomponente,Nombre) values(24,"V�lvula de seguridad");
insert into subcomponente(Id_Subcomponente,Nombre) values(25,"Cuadro el�ctrico");
insert into subcomponente(Id_Subcomponente,Nombre) values(26,"Control diferencial");
insert into subcomponente(Id_Subcomponente,Nombre) values(27,"Termostato");
insert into subcomponente(Id_Subcomponente,Nombre) values(28,"Almacenamiento de datos");


INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (1,1);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (2,2);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (2,3);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (3,4);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (3,5);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (4,5);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (4,6);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (5,5);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (5,7);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (5,8);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (6,9);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (7,10);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (7,11);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (7,12);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (8,13);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (9,14);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (10,15);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (11,18);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (11,19);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (11,36);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (12,18);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (12,19);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (12,36);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (13,25);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (13,26);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (14,34);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (15,10);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (15,15);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (15,16);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (15,17);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (16,15);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (16,17);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (17,27);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (17,37);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (18,35);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (19,28);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (20,29);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (21,30);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (22,23);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (23,20);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (24,21);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (24,22);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (25,31);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (26,24);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (27,32);
INSERT INTO operaciones (Id_Subcomponente,Id_operacion_prev) VALUES (28,33);

insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(1,5,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(1,8,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(1,9,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(1,10,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(1,11,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(1,12,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(2,5,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(2,8,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(2,9,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(2,10,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(2,11,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(2,12,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(3,5,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(3,8,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(3,9,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(3,10,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(3,11,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(3,12,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(4,5,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(4,8,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(4,9,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(4,10,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(4,11,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(4,12,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(5,5,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(5,8,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(5,9,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(5,10,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(5,11,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(5,12,'2006-11-15',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(6,1,'2004-11-01',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(6,2,'2004-11-01',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(6,3,'2004-11-01',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(6,4,'2004-11-01',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(6,5,'2004-11-01',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(6,6,'2004-11-01',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(6,7,'2004-11-01',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(7,1,'2004-11-01',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(7,2,'2004-11-01',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(7,5,'2004-11-01',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(7,6,'2004-11-01',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(7,7,'2004-11-01',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(7,13,'2004-11-01',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(7,14,'2004-11-01',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(8,7,'2004-11-01',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(8,12,'2004-11-01',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(8,14,'2004-11-01',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(8,15,'2004-11-01',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(8,16,'2004-11-01',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(8,17,'2004-11-01',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(8,18,'2004-11-01',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(8,19,'2004-11-01',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(9,2,'2004-11-01',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(9,6,'2004-11-01',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(9,7,'2004-11-01',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(9,20,'2004-11-01',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(9,21,'2004-11-01',1);
insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(9,22,'2004-11-01',1);




insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(1,1,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(1,2,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(1,3,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(1,4,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(1,5,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(1,6,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(1,7,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(2,8,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(2,9,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(2,10,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(2,11,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(2,12,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(3,13,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(3,14,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(3,15,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(3,16,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(3,17,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(3,18,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(3,19,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(3,20,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(3,21,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(3,22,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(3,23,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(3,24,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(4,13,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(4,14,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(4,15,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(4,16,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(4,17,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(4,18,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(4,19,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(4,20,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(4,21,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(4,22,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(4,23,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(4,24,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(5,25,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(5,26,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(5,27,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(5,28,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(6,25,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(6,26,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(6,27,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(6,28,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(7,13,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(7,14,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(7,15,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(7,16,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(7,17,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(7,18,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(7,19,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(7,20,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(7,21,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(7,22,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(7,23,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(7,24,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(8,1,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(8,2,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(8,3,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(8,4,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(8,5,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(8,6,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(8,7,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(9,8,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(9,9,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(9,10,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(10,11,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(10,12,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(11,13,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(11,14,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(11,15,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(11,16,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(11,17,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(11,18,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(11,19,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(11,20,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(11,21,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(11,22,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(11,23,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(11,24,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(12,25,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(12,26,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(12,27,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(12,28,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(13,13,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(13,14,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(13,15,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(13,16,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(13,17,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(13,18,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(13,19,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(13,20,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(13,21,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(13,22,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(13,23,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(13,24,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(14,13,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(14,14,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(14,15,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(14,16,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(14,17,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(14,18,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(14,19,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(14,20,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(14,21,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(14,22,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(14,23,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(14,24,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(15,1,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(15,2,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(15,3,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(15,4,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(15,5,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(15,6,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(15,7,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(16,8,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(16,9,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(16,10,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(17,11,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(17,12,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(18,13,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(18,14,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(18,15,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(18,16,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(18,17,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(18,18,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(18,19,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(18,20,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(18,21,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(18,22,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(18,23,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(18,24,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(19,25,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(19,26,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(19,27,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(19,28,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(20,1,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(20,2,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(20,3,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(20,4,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(20,5,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(20,6,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(20,7,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(21,13,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(21,14,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(21,15,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(21,16,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(21,17,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(21,18,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(21,19,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(21,20,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(21,21,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(21,22,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(21,23,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(21,24,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(22,25,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(22,26,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(22,27,1);
insert into comp_subcomp(Id_Componente,Id_Subcomponente,cantidad) values(22,28,1);

insert into parametro values

	(1,"Paneles1","Temperatura Salida de Paneles Bater�a 1"),
	(2,"Paneles2","Temperatura Salida de Paneles Bater�a 2"),
	(3,"Sup Acumulador","Temperatura Superior del Acumulador"),
	(4,"Inf Acumulador","Temperatura Inferior del Acumulador"),
	(5,"Bomba","Estado de la Bomba"), 
	(6,"Kcal","Contador de Impulsos / Kilocalor�as"),
	(7,"Radiac","Radiaci�n Solar"),
	(8,"Ambiente","Temperatura Ambiente Exterior"),
	(9,"Agua Sanitaria","Temperatura de Salida de Agua Sanitaria"),
	(10,"Agua Red","Temperatura del Agua de Red");
	

INSERT INTO sonda (Id_Centro,Id_Parametro,Descripcion_Sonda) VALUES(1,1,"S1");
INSERT INTO sonda (Id_Centro,Id_Parametro,Descripcion_Sonda) VALUES(1,4,"S2");
INSERT INTO sonda (Id_Centro,Id_Parametro,Descripcion_Sonda) VALUES(1,5,"S14");
INSERT INTO sonda (Id_Centro,Id_Parametro,Descripcion_Sonda) VALUES(1,7,"CS10");
INSERT INTO sonda (Id_Centro,Id_Parametro,Descripcion_Sonda) VALUES(1,8,"S3");
INSERT INTO sonda (Id_Centro,Id_Parametro,Descripcion_Sonda) VALUES(2,1,"S1");
INSERT INTO sonda (Id_Centro,Id_Parametro,Descripcion_Sonda) VALUES(2,4,"S2");
INSERT INTO sonda (Id_Centro,Id_Parametro,Descripcion_Sonda) VALUES(2,5,"S14");
INSERT INTO sonda (Id_Centro,Id_Parametro,Descripcion_Sonda) VALUES(2,7,"CS10");
INSERT INTO sonda (Id_Centro,Id_Parametro,Descripcion_Sonda) VALUES(2,8,"S3");
INSERT INTO sonda (Id_Centro,Id_Parametro,Descripcion_Sonda) VALUES(3,1,"S1");
INSERT INTO sonda (Id_Centro,Id_Parametro,Descripcion_Sonda) VALUES(3,4,"S2");
INSERT INTO sonda (Id_Centro,Id_Parametro,Descripcion_Sonda) VALUES(3,5,"S14");
INSERT INTO sonda (Id_Centro,Id_Parametro,Descripcion_Sonda) VALUES(3,7,"CS10");
INSERT INTO sonda (Id_Centro,Id_Parametro,Descripcion_Sonda) VALUES(3,8,"S3");
INSERT INTO sonda (Id_Centro,Id_Parametro,Descripcion_Sonda) VALUES(4,1,"S1");
INSERT INTO sonda (Id_Centro,Id_Parametro,Descripcion_Sonda) VALUES(4,4,"S2");
INSERT INTO sonda (Id_Centro,Id_Parametro,Descripcion_Sonda) VALUES(4,5,"S14");
INSERT INTO sonda (Id_Centro,Id_Parametro,Descripcion_Sonda) VALUES(4,7,"CS10");
INSERT INTO sonda (Id_Centro,Id_Parametro,Descripcion_Sonda) VALUES(4,8,"S3");
INSERT INTO sonda (Id_Centro,Id_Parametro,Descripcion_Sonda) VALUES(5,1,"S1");
INSERT INTO sonda (Id_Centro,Id_Parametro,Descripcion_Sonda) VALUES(5,4,"S2");
INSERT INTO sonda (Id_Centro,Id_Parametro,Descripcion_Sonda) VALUES(5,5,"S14");
INSERT INTO sonda (Id_Centro,Id_Parametro,Descripcion_Sonda) VALUES(5,7,"CS10");
INSERT INTO sonda (Id_Centro,Id_Parametro,Descripcion_Sonda) VALUES(5,8,"S3");