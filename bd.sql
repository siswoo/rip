DROP DATABASE IF EXISTS modulo1;
CREATE DATABASE modulo1;
USE modulo1;

DROP TABLE IF EXISTS artes_subidas1;
CREATE TABLE artes_subidas1 (
	id INT AUTO_INCREMENT,
	nombre VARCHAR(250) NOT NULL,
	formato VARCHAR(250) NOT NULL,
	item_line VARCHAR(250) NOT NULL,
	external_id VARCHAR(250) NOT NULL,
	job_id VARCHAR(250) NOT NULL,
	item VARCHAR(250) NOT NULL,
	url VARCHAR(250) NOT NULL,
	desplegable1 INT NOT NULL,
	desplegable2 INT NOT NULL,
	comentarios VARCHAR(40) NOT NULL,
	estatus INT NOT NULL,
	responsable INT NOT NULL,
	fecha_inicio DATE NOT NULL,
	fecha_modificada DATE NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE artes_subidas1 CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS extras1;
CREATE TABLE extras1 (
	id INT AUTO_INCREMENT,
	arte VARCHAR(250) NOT NULL,
	nombre VARCHAR(250) NOT NULL,
	formato VARCHAR(250) NOT NULL,
	item_line VARCHAR(250) NOT NULL,
	external_id VARCHAR(250) NOT NULL,
	job_id VARCHAR(250) NOT NULL,
	item VARCHAR(250) NOT NULL,
	url VARCHAR(250) NOT NULL,
	fecha_inicio DATE NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE extras1 CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS pdfs;
CREATE TABLE pdfs (
	id INT AUTO_INCREMENT,
	arte VARCHAR(250) NOT NULL,
	formato VARCHAR(250) NOT NULL,
	item_line VARCHAR(250) NOT NULL,
	external_id VARCHAR(250) NOT NULL,
	job_id VARCHAR(250) NOT NULL,
	item VARCHAR(250) NOT NULL,
	url VARCHAR(250) NOT NULL,
	responsable INT NOT NULL,
	fecha_inicio DATE NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE pdfs CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS roles;
CREATE TABLE roles (
	id INT AUTO_INCREMENT,
	nombre VARCHAR(250) NOT NULL,
	estatus INT NOT NULL,
	responsable INT NOT NULL,
	fecha_inicio DATE NOT NULL,
	fecha_modificada DATE NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE roles CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO roles (nombre,estatus,responsable,fecha_inicio) VALUES 
('admin',1,1,'2022-02-28'),
('designer',1,1,'2022-02-28'),
('workorder_admin',1,1,'2022-06-04'),
('workorder_view',1,1,'2022-06-04');

DROP TABLE IF EXISTS usuarios;
CREATE TABLE usuarios (
	id INT AUTO_INCREMENT,
	id_roles INT NOT NULL,
	id_roles2 INT NOT NULL,
	id_roles3 INT NOT NULL,
	unicaid VARCHAR(250) NOT NULL UNIQUE,
	nombre VARCHAR(250) NOT NULL UNIQUE,
	usuario VARCHAR(250) NOT NULL,
	password VARCHAR(250) NOT NULL,
	correo_personal VARCHAR(250) NOT NULL,
	estatus INT NOT NULL,
	responsable INT NOT NULL,
	fecha_modificacion DATE NOT NULL,
	fecha_inicio DATE NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE usuarios CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO usuarios(unicaid,id_roles,nombre,usuario,correo_personal,password,estatus,responsable,fecha_modificacion,fecha_inicio) VALUES 
("asd789",1,"juan maldonado","admin","juanmaldonado.co@gmail.com","71b3b26aaa319e0cdf6fdb8429c112b0",1,1,"2022-02-28","2022-02-07");

DROP TABLE IF EXISTS tipos_artes;
CREATE TABLE tipos_artes (
	id INT AUTO_INCREMENT,
	nombre VARCHAR(250) NOT NULL,
	diminutivo VARCHAR(250) NOT NULL,
	fecha_inicio DATE NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE tipos_artes CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO tipos_artes(nombre,diminutivo,fecha_inicio) VALUES 
("Active Sport","AS","2022-02-25"),
("Xtreme Color","XC","2022-02-25"),
("Premium Plus","PP","2022-02-25");

DROP TABLE IF EXISTS desplegable1;
CREATE TABLE desplegable1 (
	id INT AUTO_INCREMENT,
	nombre VARCHAR(250) NOT NULL,
	estatus INT NOT NULL,
	responsable INT NOT NULL,
	fecha_inicio DATE NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE desplegable1 CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO desplegable1(id,nombre,estatus,responsable,fecha_inicio) VALUES 
(1,"Pending Art Approval",0,1,"2022-02-25"),
(2,"In Artwork",0,1,"2022-02-25"),
(3,"Pending Customer Approval",1,1,"2022-02-25"),
(4,"Pending FIC Assignment",1,1,"2022-02-25"),
(5,"Rejected by Customer",0,1,"2022-02-25"),
(6,"Sent to Production",0,1,"2022-02-25"),
(7,"Screen Room",0,1,"2022-02-25"),
(8,"Production",0,1,"2022-02-25"),
(9,"Quality Control",0,1,"2022-02-25"),
(10,"Cutting",0,1,"2022-02-25"),
(11,"Packing",0,1,"2022-02-25"),
(12,"Shipped",0,1,"2022-02-25"),
(13,"Pending Return",0,1,"2022-02-25"),
(14,"Returned",0,1,"2022-02-25");

DROP TABLE IF EXISTS desplegable2;
CREATE TABLE desplegable2 (
	id INT AUTO_INCREMENT,
	id_tipos_artes INT NOT NULL,
	id_desplegable1 INT NOT NULL,
	texto TEXT NOT NULL,
	estatus INT NOT NULL,
	responsable INT NOT NULL,
	fecha_inicio DATE NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE desplegable2 CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO desplegable2(id_tipos_artes,id_desplegable1,texto,estatus,responsable,fecha_inicio) VALUES 
(1,3,"The artwork does not meet the 2 point spacing for the knocked out areas",1,1,"2022-02-25"),
(1,3,"The artwork does not meet the minimum 1 point line thickness for the printing areas ",1,1,"2022-02-25"),
(1,3,"1-3 color orders need to submitted the artwork in vector format AI, PDF, SVG, or EPS",1,1,"2022-02-25"),
(1,3,"1-3 color orders need to submitted the artwork in vector format AI, PDF, SVG, or EPS",1,1,"2022-02-25"),
(1,3,"Art Approval for Artwork Redraw",1,1,"2022-02-25"),
(1,3,"Art Approval for Resize Service",1,1,"2022-02-25"),
(1,3,"Art Approval for artwork adjustment on the register trademark to have it meet our printing requirements",1,1,"2022-02-25"),
(1,3,"Art approval on the comments made in the order",1,1,"2022-02-25"),
(1,3,"THIS DOES NOT APPLY TO ACTIVE SPORT OR PREMIUM PLUS ORDERS",1,1,"2022-02-25"),
(1,3,"Art sent for approval because the text was made thicker to meet our minimum 1 point line thickness for the printing areas",1,1,"2022-02-25"),
(1,3,"1-3 color orders need to submitted the artwork in vector format AI, PDF, SVG, or EPS",1,1,"2022-02-25"),
(1,3,"The artwork submitted is very pixelated, 1-3 color orders need to submitted the artwork in vector format AI, PDF, SVG, or EPS",1,1,"2022-02-25"),
(1,3,"The artwork size was adjusted to have the art meet our printing requirements",1,1,"2022-02-25"),
(1,3,"Art approval because the spacing in the letters were given more space to meet our 2 point spacing requirements for knocked out areas",1,1,"2022-02-25"),
(1,3,"The color picked requires a white backer, if the transfer is going on any color shirt that is not white we will need to charge the additional color white to have as a backer. If the transfer is going on white, the white backer is not needed.",1,1,"2022-02-25"),
(1,3,"The color picked requires a white backer, if the transfer is going on any color shirt that is not white we will need to charge the additional color white to have as a backer. If the transfer is going on white, the white backer is not needed.",1,1,"2022-02-25"),
(1,3,"THIS IS THE SAME AS THE FIRST CONCERN ON THIS LIST ",1,1,"2022-02-25"),
(1,3,"The order is placed on hold because the art file submitted is considered a gang sheet and this part of the order is the single image program, to proceed sent back a single image artwork or we can cancel this part and refund you for. you to submitted a new order on the gang sheet.",1,1,"2022-02-25"),
(1,3,"The order is placed on hold because it's not clear on how to proceed with the colors requested",1,1,"2022-02-25"),
(2,3,"The artwork does not meet the 2 point spacing for the knocked out areas",1,1,"2022-02-25"),
(2,3,"The artwork does not meet the minimum 3 point line thickness for the printing areas, standalone white can be 1 point.",1,1,"2022-02-25"),
(2,3,"Full color orders can be sent in vector format or at 300 DPI",1,1,"2022-02-25"),
(2,3,"Full color orders can be sent in vector format or at 300 DPI",1,1,"2022-02-25"),
(2,3,"Art Approval for Artwork Redraw",1,1,"2022-02-25"),
(2,3,"Art Approval for Resize Service",1,1,"2022-02-25"),
(2,3,"Art Approval for artwork adjustment on the register trademark to have it meet our printing requirements",1,1,"2022-02-25"),
(2,3,"Art approval on the comments made in the order",1,1,"2022-02-25"),
(2,3,"Art Approval for having an outline added to have the artwork meet our printing requierment",1,1,"2022-02-25"),
(2,3,"Art sent for approval because the text was made thicker to meet our minimum 3 point line thickness for the printing areas",1,1,"2022-02-25"),
(2,3,"THIS DOES NOT APPLY TO XTREME COLOR ORDERS ",1,1,"2022-02-25"),
(2,3,"The artwork submitted is very pixelated, Full color orders can be sent in vector format or at 300 DPI",1,1,"2022-02-25"),
(2,3,"The artwork size was adjusted to have the art meet our printing requirements",1,1,"2022-02-25"),
(2,3,"Art approval because the spacing in the letters were given more space to meet our 2 point spacing requirements for knocked out areas",1,1,"2022-02-25"),
(2,3,"THIS DOES NOT APPLY TO XTREME COLOR ORDERS ",1,1,"2022-02-25"),
(2,3,"THIS DOES NOT APPLY TO XTREME COLOR ORDERS ",1,1,"2022-02-25"),
(2,3,"THIS IS THE SAME AS THE FIRST CONCERN ON THIS LIST ",1,1,"2022-02-25"),
(2,3,"The order is placed on hold because the art file submitted is considered a gang sheet and this part of the order is the single image program, to proceed sent back a single image artwork or we can cancel this part and refund you for. you to submitted a new order on the gang sheet.",1,1,"2022-02-25"),
(2,3,"THIS DOES NOT APPLY TO XTREME COLOR ORDERS ",1,1,"2022-02-25"),
(3,3,"The artwork does not meet the 2 point spacing for the knocked out areas",1,1,"2022-02-25"),
(3,3,"The artwork does not meet the minimum 1 point line thickness for the printing areas ",1,1,"2022-02-25"),
(3,3,"1-3 color orders need to submitted the artwork in vector format AI, PDF, SVG, or EPS",1,1,"2022-02-25"),
(3,3,"1-3 color orders need to submitted the artwork in vector format AI, PDF, SVG, or EPS",1,1,"2022-02-25"),
(3,3,"Art Approval for Artwork Redraw",1,1,"2022-02-25"),
(3,3,"Art Approval for Resize Service",1,1,"2022-02-25"),
(3,3,"Art Approval for artwork adjustment on the register trademark to have it meet our printing requirements",1,1,"2022-02-25"),
(3,3,"Art approval on the comments made in the order",1,1,"2022-02-25"),
(3,3,"THIS DOES NOT APPLY TO ACTIVE SPORT OR PREMIUM PLUS ORDERS",1,1,"2022-02-25"),
(3,3,"Art sent for approval because the text was made thicker to meet our minimum 1 point line thickness for the printing areas",1,1,"2022-02-25"),
(3,3,"1-3 color orders need to submitted the artwork in vector format AI, PDF, SVG, or EPS",1,1,"2022-02-25"),
(3,3,"The artwork submitted is very pixelated, 1-3 color orders need to submitted the artwork in vector format AI, PDF, SVG, or EPS",1,1,"2022-02-25"),
(3,3,"The artwork size was adjusted to have the art meet our printing requirements",1,1,"2022-02-25"),
(3,3,"Art approval because the spacing in the letters were given more space to meet our 2 point spacing requirements for knocked out areas",1,1,"2022-02-25"),
(3,3,"The color picked requires a white backer, if the transfer is going on any color shirt that is not white we will need to charge the additional color white to have as a backer. If the transfer is going on white, the white backer is not needed.",1,1,"2022-02-25"),
(3,3,"The color picked requires a white backer, if the transfer is going on any color shirt that is not white we will need to charge the additional color white to have as a backer. If the transfer is going on white, the white backer is not needed.",1,1,"2022-02-25"),
(3,3,"THIS IS THE SAME AS THE FIRST CONCERN ON THIS LIST ",1,1,"2022-02-25"),
(3,3,"The order is placed on hold because the art file submitted is considered a gang sheet and this part of the order is the single image program, to proceed sent back a single image artwork or we can cancel this part and refund you for. you to submitted a new order on the gang sheet.",1,1,"2022-02-25"),
(3,3,"The order is placed on hold because it's not clear on how to proceed with the colors requested",1,1,"2022-02-25");

DROP TABLE IF EXISTS ordenes;
CREATE TABLE ordenes (
	id INT AUTO_INCREMENT,
	external_id VARCHAR(250) NOT NULL,
	trand_id VARCHAR(250) NOT NULL,
	job_id VARCHAR(250) NOT NULL,
	item_line INT NOT NULL,
	item VARCHAR(250) NOT NULL,
	item_estatus INT NOT NULL,
	unicaid VARCHAR(250) NOT NULL,
	url1 VARCHAR(250) NOT NULL,
	url2 VARCHAR(250) NOT NULL,
	descripcion VARCHAR(250) NOT NULL,
	quantity INT NOT NULL,
	rate INT NOT NULL,
	fecha_item DATE NOT NULL,
	responsable INT NOT NULL,
	fecha_inicio DATE NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE ordenes CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS logintokens;
CREATE TABLE logintokens (
	id INT AUTO_INCREMENT,
	access_token TEXT NOT NULL,
	refresh_token TEXT NOT NULL,
	fecha_inicio DATE NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE logintokens CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO logintokens(access_token,refresh_token,fecha_inicio) VALUES 
('eyJraWQiOiJjLjY4MTYyODcuMjAyMi0wMi0wMl8wMC0wOC0yMCIsInR5cCI6IkpXVCIsImFsZyI6IlJTMjU2In0.eyJzdWIiOiIxMDI2OzE3MjA3OTMiLCJhdWQiOlsiOEVCRDIwNjUtMDFBQy00NkY1LTk1NEQtMkNDMDZEN0EyODA2OzY4MTYyODciLCI5MDIwYTE2MzU2MTFiODkzODgyNTg4OWVjMWMwYWI2YmU4MmJiYzhhNTkwNjZhYzUyZTkyYmVkNTVjNDgwMDVkIl0sInNjb3BlIjpbInJlc3Rfd2Vic2VydmljZXMiXSwiaXNzIjoiaHR0cHM6XC9cL3N5c3RlbS5uZXRzdWl0ZS5jb20iLCJvaXQiOjE2NDU2Mjk5MzEsImV4cCI6MTY0NTYzMzUzMSwiaWF0IjoxNjQ1NjI5OTMxLCJqdGkiOiI2ODE2Mjg3LmEtYS5mNDUwYWExYy0zNmUzLTRjNmItYWIwMy1lMGFmYTZjN2JmNTNfMTY0NTYyOTkzMTE3Mi4xNjQ1NjI5OTMxMTcyIn0.MfsUYY9KyAlgBwVck0j69A9lyCwlCI-EElm3u08o6z5GFXm0W3_hKUtZO6Yfv60bxOHnuX5mEPdu57Z7fgrQqpvWCpzV4aXN5AB8MgM1E4l8cktyYZpMxdVhkLbrV02DMSWJ2gXglkyEZy1Hq2FixTJv9i95QLV3YvgAB_797ZvhKBRmeFcfeKCAZeybYbnH0g8bCuzaojrbqojevtQwELWR52hrPdqVfgmryGIRIBBH2UqNhow00RgT9LzJR0AkvC1SGC8cnxKx1xlo4sHJn3zVAP0jA_4nobxRFTZTyfd2W2HFO3d10E_D62AheAEh2oZUjUpWAWEjTkzTgKp5xcIpz28euQbyY0KOBs7_4tgLnXJQqWhqoCbMlpeH4T-2sG1Uv14fBBmuko11Cq8pDODvq9KQduyr-KF1DfbJJ8UHeKc7hBinQvSDqcr_qeouZoNjzaoTxifcggn1feEbBFJ8xZxPTGE-EkxWDXdFhasytgb7jaAxt5Ulwri1Ni9y5OVz1WAUpZz0_t8rdibUgej0MMWt-TNawvffdAl1rLu7hU-2sSse2NrYTRB7Yd9iS0kHbQ5AYIx3dq8FUUJDS1s3ENOw4JNZQKeoJsVw3AT8am-QKq0WFo8dy0iT2EJ9QWtdFMyg82KRBpnSa_fb_EiIVl4wwpL59evbeWcn9cw',"eyJraWQiOiJjLjY4MTYyODcuMjAyMi0wMi0wMl8wMC0wOC0yMCIsInR5cCI6IkpXVCIsImFsZyI6IlJTMjU2In0.eyJzdWIiOiIxMDI2OzE3MjA3OTMiLCJhdWQiOlsiOEVCRDIwNjUtMDFBQy00NkY1LTk1NEQtMkNDMDZEN0EyODA2OzY4MTYyODciLCI5MDIwYTE2MzU2MTFiODkzODgyNTg4OWVjMWMwYWI2YmU4MmJiYzhhNTkwNjZhYzUyZTkyYmVkNTVjNDgwMDVkIl0sInNjb3BlIjpbInJlc3Rfd2Vic2VydmljZXMiXSwiaXNzIjoiaHR0cHM6XC9cL3N5c3RlbS5uZXRzdWl0ZS5jb20iLCJvaXQiOjE2NDU2Mjk5MzEsImV4cCI6MTY0NjIzNDczMSwiaWF0IjoxNjQ1NjI5OTMxLCJqdGkiOiI2ODE2Mjg3LnItYS5mNDUwYWExYy0zNmUzLTRjNmItYWIwMy1lMGFmYTZjN2JmNTNfMTY0NTYyOTkzMTE3Mi4wIn0.iVwOXftW0OWhEftsPAvSWNk5Eeh3Rgj4KJOvnDgaIHe4zhHN83-QMhj6Hj5fF049on-ySKuHO9Eu976ZTb8Y1a_USN6j__Tfxvf-W9h9AQBHDMlhsFQt_qyT0MzIxTX0obo6UdeGpNRCTXf7eImxHgdgQvc1YiX61VMR-5_Qihm2oXnp0-CPBBme8o1cJnK4qTiMB45wU48kTRJTiv_2p7JuIk-fKvBygI49OUsoFupGYjLUFs2jtp0v0lW3it6gtlYwZV9X3kDNhA9suI4ZngPa72X5xd14HdtmO1mGJNXpZ4hZJbIaNe81G1d6EY0xHqQGlKyr98iR3wfQhysc-4TsdhG0P3TqsCbPQCiw4yOoH8vkl2XPnL8rvUJ0Vwkffv9M2q8mJ2qOuTly6z3jn9TpCZCMxvR50YcAaYAm9T80z0dDWlQ3PC7j9b_u_vQg64dAm5LtNp_puluc3V_RmGIAxfZ_KBitWrEpS2oKBmdPpNhHj6g4ONYYgVZeN6sMKDtNQsGjhyzXoi84Aav5pqbLQbp17V0zJX4jgFLZC3Sf2t57KG4peJUca4KFc7tqKJcpW6n0F6zKwwr-_XEs5lbvLxntm76q9d-Poydtydt0LIx2amlUrVmBUttEbJAnNEStxzyalsOOSo37q51MfVoD8YwksuV7a88roNZROr0","2022-02-23");

DROP TABLE IF EXISTS test1;
CREATE TABLE test1 (
	id INT AUTO_INCREMENT,
	nombre VARCHAR(250) NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE test1 CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO test1(nombre) VALUES 
("hola");

DROP TABLE IF EXISTS log_job1;
CREATE TABLE log_job1 (
	id INT AUTO_INCREMENT,
	id_job INT NOT NULL,
	respuesta TEXT NOT NULL,
	hora TIME NOT NULL,
	fecha_inicio DATE NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE log_job1 CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS previews;
CREATE TABLE previews (
	id INT AUTO_INCREMENT,
	nombre VARCHAR(250) NOT NULL,
	formato VARCHAR(250) NOT NULL,
	item_line INT NOT NULL,
	external_id VARCHAR(250) NOT NULL,
	trand_id VARCHAR(250) NOT NULL,
	job_id VARCHAR(250) NOT NULL,
	item VARCHAR(250) NOT NULL,
	responsable INT NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE previews CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS password_recovery;
CREATE TABLE password_recovery (
	id INT AUTO_INCREMENT,
	id_usuario INT NOT NULL,
	codigo_generado VARCHAR(250) NOT NULL,
	fecha_inicio DATE NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE password_recovery CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS job1;
CREATE TABLE job1 (
	id INT AUTO_INCREMENT,
	nombre VARCHAR(250) NOT NULL,
	external_id VARCHAR(250) NOT NULL,
	item_line VARCHAR(250) NOT NULL,
	custcol_item_status VARCHAR(250) NOT NULL,
	custcol_final_artwork_list_comment VARCHAR(250) NOT NULL,
	custcol_final_artwork_free_comment VARCHAR(250) NOT NULL,
	custcol_final_artwork_url VARCHAR(250) NOT NULL,
	custcol_final_artwork_preview_url VARCHAR(250) NOT NULL,
	custcol_final_artwork_extras_url TEXT NOT NULL,
	comentarios VARCHAR(40) NOT NULL,
	estatus INT NOT NULL,
	responsable INT NOT NULL,
	fecha_inicio DATE NOT NULL,
	fecha_modificada DATE NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE job1 CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS funciones;
CREATE TABLE funciones (
	id INT AUTO_INCREMENT,
	nombre VARCHAR(250) NOT NULL,
	url VARCHAR(250) NOT NULL,
	id_roles INT NOT NULL,
	estatus INT NOT NULL,
	responsable INT NOT NULL,
	fecha_inicio DATE NOT NULL,
	fecha_modificada DATE NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE funciones CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO funciones (nombre,id_roles,url,estatus,responsable,fecha_inicio) VALUES 
('Upload Art',1,'index4.php',1,1,'2022-03-22'),
('Arts List',1,'index3.php',1,1,'2022-03-22'),
('Users',1,'users1.php',1,1,'2022-03-22'),
('Order',1,'ordenes1.php',1,1,'2022-03-22'),
('Tareas PDF',1,'nose.php',1,1,'2022-03-22'),
('Roles',1,'roles1.php',1,1,'2022-03-22'),
('Funciones',1,'funciones1.php',1,1,'2022-03-22'),
('prueba1',1,'prueba1.php',1,1,'2022-03-22'),
('Horas',1,'horas1.php',1,1,'2022-05-27'),
('Horas',2,'horas2.php',1,1,'2022-05-30');

DROP TABLE IF EXISTS aprobacion_clientes1;
CREATE TABLE aprobacion_clientes1 (
	id INT AUTO_INCREMENT,
	codigo VARCHAR(250) NOT NULL,
	url VARCHAR(250) NOT NULL,
	external VARCHAR(250) NOT NULL,
	id_ordenes INT NOT NULL,
	job INT NOT NULL,
	estatus INT NOT NULL,
	fecha_inicio DATE NOT NULL,
	hora_inicio TIME NOT NULL,
	fecha_modificacion DATE NOT NULL,
	hora_modificacion TIME NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE aprobacion_clientes1 CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS wo;
CREATE TABLE wo (
	id INT AUTO_INCREMENT,
	trand_id VARCHAR(250) NOT NULL,
	estatus INT NOT NULL,
	responsable INT NOT NULL,
	fecha_inicio DATE NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE wo CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS galeria_wo1;
CREATE TABLE galeria_wo1 (
	id INT AUTO_INCREMENT,
	wo_id INT NOT NULL,
	imagen VARCHAR(250) NOT NULL,
	responsable INT NOT NULL,
	fecha_inicio DATE NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE galeria_wo1 CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS horas1;
CREATE TABLE horas1 (
	id INT AUTO_INCREMENT,
	desde DATETIME NOT NULL,
	hasta DATETIME NOT NULL,
	responsable INT NOT NULL,
	fecha_inicio DATE NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE horas1 CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS folders1;
CREATE TABLE folders1 (
	id INT AUTO_INCREMENT,
	nombre VARCHAR(250) NOT NULL,
	estatus INT NOT NULL,
	responsable INT NOT NULL,
	fecha_inicio DATE NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE folders1 CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO folders1 (nombre,estatus,responsable,fecha_inicio) VALUES 
('Spot_Color',1,1,'2022-06-22'),
('4_Color',1,1,'2022-06-22');