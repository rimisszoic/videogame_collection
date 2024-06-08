create database videogame_collection;

use videogame_collection;

create table roles(
	id int not null auto_increment primary key,
    nombre varchar(50) not null
);

insert into roles (nombre) values ('registrado'), ('admin'), ('super-admin');

create table usuarios(
	id int not null auto_increment,
    nombre_completo varchar(100) not null,
    nombre_usuario varchar(30) not null,
    email varchar(255) not null,
    fecha_nacimiento date not null,
    ultimo_acceso DATETIME not null,
    password varchar(255) not null,
    rol int not null,
    PRIMARY KEY (id),
    FOREIGN KEY (rol) REFERENCES roles(id)
);


create table generos(
    id int not null auto_increment primary key,
    nombre varchar(100) not null
);

create table plataformas(
    id int not null auto_increment primary key,
    nombre varchar(100) not null
);

create table juegos(
    id int not null auto_increment primary key,
    nombre varchar(100) not null,
    genero int NOT NULL,
    plataforma int NOT NULL,
    portada varchar(255) not null,
    FOREIGN KEY (genero) REFERENCES generos(id),
    FOREIGN KEY (plataforma) REFERENCES plataformas(id)
);

create table colecciones(
    id int not null auto_increment primary key,
    usuario int not null,
    FOREIGN KEY (usuario) REFERENCES usuarios(id)
);

create table coleccion_juegos(
    coleccion int not null,
    juego int not null,
    PRIMARY KEY (coleccion, juego),
    FOREIGN KEY (coleccion) REFERENCES colecciones(id),
    FOREIGN KEY (juego) REFERENCES juegos(id)
);

create table imagenes_galeria(
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    imagen varchar(255) NOT NULL,
);

SET GLOBAL log_bin_trust_function_creators = 1; 

DELIMITER $$
CREATE TRIGGER crear_coleccion_nuevo_usuario
AFTER INSERT ON usuarios
FOR EACH ROW
BEGIN
    INSERT INTO colecciones (usuario) VALUES (NEW.id);
END$$

DELIMITER ;

DELIMITER $$
CREATE TRIGGER borrar_coleccion_usuario_borrado
BEFORE DELETE ON usuarios
FOR EACH ROW
BEGIN
<<<<<<< HEAD
=======
    -- Obtener el id de la coleccion del usuario
    DECLARE id_coleccion INT;

    SELECT c.id
    from coleccion_juegos cj join colecciones c on cj.coleccion = c.id
    where c.usuario = OLD.id
    INTO id_coleccion;

    -- Borrar los juegos de la coleccion
    DELETE FROM coleccion_juegos WHERE coleccion = id_coleccion;
    -- Borrar la coleccion
>>>>>>> aed674e701dca1fe8b4cb1fa9fac086f377c54dd
    DELETE FROM colecciones WHERE usuario = OLD.id;
END$$

DELIMITER ;

SET GLOBAL log_bin_trust_function_creators = 0; 