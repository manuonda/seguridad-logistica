-- tramite_online.tramite_votacion definition

-- tramite_online.tramite_votacion definition

-- Drop table

-- DROP TABLE tramite_online.tramite_votacion;

CREATE TABLE tramite_online.tramite_votacion (
	id_tramite_votacion bigserial NOT NULL,
	motivo varchar NULL,
	descripcion varchar NULL,
	fecha_votacion date NULL,
	nombre_eleccion varchar NULL,
	usuario_alta int4 NULL,
	usuario_modificacion int4 NULL,
	fecha_alta timestamp NULL,
	fecha_modificacion timestamp NULL,
	id_tramite int8 NOT NULL,
	id_provincia int4 NULL,
	razon_social varchar NULL,
	CONSTRAINT tramite_votacion_pk PRIMARY KEY (id_tramite_votacion)
);