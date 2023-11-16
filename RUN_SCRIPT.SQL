truncate tramite_online.tramites;
truncate tramite_online.tramite_personas;
truncate tramite_online.tramite_archivos;
truncate tramite_online.tramite_archivos_firma_digital;
truncate tramite_online.rendicion_detalle;
truncate tramite_online.rendicion_encabezado;
truncate tramite_online.turnos;
truncate tramite_online.turno_dependencias;
truncate tramite_online.bloqueo_usuario;
truncate tramite_online.turno_cantidad;
truncate tramite_online.movimiento_pago;




SELECT setval('tramite_online.rendicion_detalle_seq', 1);
SELECT setval('tramite_online.rendicion_encabezado_seq', 1);
SELECT setval('tramite_online.tramites_id_tramite_seq', 1);
SELECT setval('tramite_online.personas_id_persona_seq', 1);
SELECT setval('tramite_online.tramite_archivos_id_tramite_archivo_seq', 1);
SELECT setval('tramite_online.tramite_archivos_id_tramite_archivo_seq', 1);
SELECT setval('tramite_online.turnos_id_turno_seq', 1);
SELECT setval('tramite_online.turno_dependencias_id_turno_dependencia_seq', 1);
SELECT setval('tramite_online.usuario_bloqueado_id_seq', 1);
SELECT setval('tramite_online.turno_cantidad_id_turno_cantidad_seq', 1);