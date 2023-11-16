ALTER TABLE tramite_online.tramites ADD observaciones_antecedente varchar(255) NULL;
INSERT INTO public.roles
(id_rol, rol, descripcion, usuario_alta, fecha_alta, usuario_modificacion, fecha_modificacion)
VALUES(11, 'ANTECEDENTE', 'Antecedente', NULL, NULL, NULL, NULL);
