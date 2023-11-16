CREATE SEQUENCE public.auth_activation_attempts_id_seq
  INCREMENT 0
  MINVALUE 0
  MAXVALUE 0
  START 0
  CACHE 0;
-- Sequence: public.auth_groups_id_seq

-- DROP SEQUENCE public.auth_groups_id_seq;

CREATE SEQUENCE public.auth_groups_id_seq
  INCREMENT 0
  MINVALUE 0
  MAXVALUE 0
  START 0
  CACHE 0;


CREATE SEQUENCE public.auth_logins_id_seq
  INCREMENT 0
  MINVALUE 0
  MAXVALUE 0
  START 0
  CACHE 0;


CREATE SEQUENCE public.auth_permissions_id_seq
  INCREMENT 0
  MINVALUE 0
  MAXVALUE 0
  START 0
  CACHE 0;


CREATE SEQUENCE public.auth_reset_attempts_id_seq
  INCREMENT 0
  MINVALUE 0
  MAXVALUE 0
  START 0
  CACHE 0;


CREATE SEQUENCE public.auth_tokens_id_seq
  INCREMENT 0
  MINVALUE 0
  MAXVALUE 0
  START 0
  CACHE 0;


CREATE SEQUENCE public.auth_groups_id_se
  INCREMENT 0
  MINVALUE 0
  MAXVALUE 0
  START 0
  CACHE 0;


CREATE SEQUENCE public.users_id_seq
  INCREMENT 0
  MINVALUE 0
  MAXVALUE 0
  START 0
  CACHE 0;



-- Table: tramite_online.users

 DROP TABLE tramite_online.users;

CREATE TABLE tramite_online.users
(
  id integer NOT NULL DEFAULT nextval('users_id_seq'::regclass),
  email character varying(255) NOT NULL,
  username character varying(30),
  password_hash character varying(255) NOT NULL,
  reset_hash character varying(255),
  reset_at timestamp without time zone,
  reset_expires timestamp without time zone,
  activate_hash character varying(255),
  status character varying(255),
  status_message character varying(255),
  active smallint NOT NULL DEFAULT 0,
  force_pass_reset smallint NOT NULL DEFAULT 0,
  created_at timestamp without time zone,
  updated_at timestamp without time zone,
  deleted_at timestamp without time zone,
  CONSTRAINT pk_users PRIMARY KEY (id),
  CONSTRAINT users_email UNIQUE (email),
  CONSTRAINT users_username UNIQUE (username)
)
WITH (
  OIDS=FALSE
);

-- Table: tramite_online.auth_logins

 DROP TABLE tramite_online.auth_logins;

CREATE TABLE tramite_online.auth_logins
(
  id integer NOT NULL DEFAULT nextval('auth_logins_id_seq'::regclass),
  ip_address character varying(255),
  email character varying(255),
  user_id integer,
  date timestamp without time zone NOT NULL,
  success smallint NOT NULL,
  CONSTRAINT pk_auth_logins PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);


-- Table: tramite_online.auth_tokens

-- DROP TABLE tramite_online.auth_tokens;

CREATE TABLE tramite_online.auth_tokens
(
  id integer NOT NULL DEFAULT nextval('auth_tokens_id_seq'::regclass),
  selector character varying(255) NOT NULL,
  "hashedValidator" character varying(255) NOT NULL,
  user_id integer NOT NULL,
  expires timestamp without time zone NOT NULL,
  CONSTRAINT pk_auth_tokens PRIMARY KEY (id),
  CONSTRAINT auth_tokens_user_id_foreign FOREIGN KEY (user_id)
      REFERENCES tramite_online.users (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);


-- Table: tramite_online.auth_reset_attempts

 DROP TABLE tramite_online.auth_reset_attempts;

CREATE TABLE tramite_online.auth_reset_attempts
(
  id integer NOT NULL DEFAULT nextval('auth_reset_attempts_id_seq'::regclass),
  email character varying(255) NOT NULL,
  ip_address character varying(255) NOT NULL,
  user_agent character varying(255) NOT NULL,
  token character varying(255),
  created_at timestamp without time zone NOT NULL,
  CONSTRAINT pk_auth_reset_attempts PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

-- Table: tramite_online.auth_activation_attempts

DROP TABLE tramite_online.auth_activation_attempts;

CREATE TABLE tramite_online.auth_activation_attempts
(
  id integer NOT NULL DEFAULT nextval('auth_activation_attempts_id_seq'::regclass),
  ip_address character varying(255) NOT NULL,
  user_agent character varying(255) NOT NULL,
  token character varying(255),
  created_at timestamp without time zone NOT NULL,
  CONSTRAINT pk_auth_activation_attempts PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);


-- Table: tramite_online.auth_groups

DROP TABLE tramite_online.auth_groups;

CREATE TABLE tramite_online.auth_groups
(
  id integer NOT NULL DEFAULT nextval('auth_groups_id_seq'::regclass),
  name character varying(255) NOT NULL,
  description character varying(255) NOT NULL,
  CONSTRAINT pk_auth_groups PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

 DROP TABLE tramite_online.auth_permissions;

CREATE TABLE tramite_online.auth_permissions
(
  id integer NOT NULL DEFAULT nextval('auth_permissions_id_seq'::regclass),
  name character varying(255) NOT NULL,
  description character varying(255) NOT NULL,
  CONSTRAINT pk_auth_permissions PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

-- Table: tramite_online.auth_groups_permissions

-- DROP TABLE tramite_online.auth_groups_permissions;

CREATE TABLE tramite_online.auth_groups_permissions
(
  group_id integer NOT NULL DEFAULT 0,
  permission_id integer NOT NULL DEFAULT 0,
  CONSTRAINT auth_groups_permissions_group_id_foreign FOREIGN KEY (group_id)
      REFERENCES tramite_online.auth_groups (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE,
  CONSTRAINT auth_groups_permissions_permission_id_foreign FOREIGN KEY (permission_id)
      REFERENCES tramite_online.auth_permissions (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);


-- Index: tramite_online.auth_groups_permissions_group_id_permission_id

-- DROP INDEX tramite_online.auth_groups_permissions_group_id_permission_id;

CREATE INDEX auth_groups_permissions_group_id_permission_id
  ON tramite_online.auth_groups_permissions
  USING btree
  (group_id, permission_id);

-- Table: tramite_online.auth_groups_users

 -- DROP TABLE tramite_online.auth_groups_users;

CREATE TABLE tramite_online.auth_groups_users
(
  group_id integer NOT NULL DEFAULT 0,
  user_id integer NOT NULL DEFAULT 0,
  CONSTRAINT auth_groups_users_group_id_foreign FOREIGN KEY (group_id)
      REFERENCES tramite_online.auth_groups (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE,
  CONSTRAINT auth_groups_users_user_id_foreign FOREIGN KEY (user_id)
      REFERENCES tramite_online.users (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);

-- Index: tramite_online.auth_groups_users_group_id_user_id

-- DROP INDEX tramite_online.auth_groups_users_group_id_user_id;

CREATE INDEX auth_groups_users_group_id_user_id
  ON tramite_online.auth_groups_users
  USING btree
  (group_id, user_id);




-- DROP INDEX tramite_online.auth_logins_email;

CREATE INDEX auth_logins_email
  ON tramite_online.auth_logins
  USING btree
  (email COLLATE pg_catalog."default");

-- Index: tramite_online.auth_logins_user_id

-- DROP INDEX tramite_online.auth_logins_user_id;

CREATE INDEX auth_logins_user_id
  ON tramite_online.auth_logins
  USING btree
  (user_id);



-- Table: tramite_online.auth_users_permissions

-- DROP TABLE tramite_online.auth_users_permissions;

CREATE TABLE tramite_online.auth_users_permissions
(
  user_id integer NOT NULL DEFAULT 0,
  permission_id integer NOT NULL DEFAULT 0,
  CONSTRAINT auth_users_permissions_permission_id_foreign FOREIGN KEY (permission_id)
      REFERENCES tramite_online.auth_permissions (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE,
  CONSTRAINT auth_users_permissions_user_id_foreign FOREIGN KEY (user_id)
      REFERENCES tramite_online.users (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);



-- Index: tramite_online.auth_tokens_selector

-- DROP INDEX tramite_online.auth_tokens_selector;

CREATE INDEX auth_tokens_selector
  ON tramite_online.auth_tokens
  USING btree
  (selector COLLATE pg_catalog."default");

-- Table: tramite_online.auth_users_permissions

-- DROP TABLE tramite_online.auth_users_permissions;

CREATE TABLE tramite_online.auth_users_permissions
(
  user_id integer NOT NULL DEFAULT 0,
  permission_id integer NOT NULL DEFAULT 0,
  CONSTRAINT auth_users_permissions_permission_id_foreign FOREIGN KEY (permission_id)
      REFERENCES tramite_online.auth_permissions (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE,
  CONSTRAINT auth_users_permissions_user_id_foreign FOREIGN KEY (user_id)
      REFERENCES tramite_online.users (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);

-- Index: tramite_online.auth_users_permissions_user_id_permission_id

-- DROP INDEX tramite_online.auth_users_permissions_user_id_permission_id;

CREATE INDEX auth_users_permissions_user_id_permission_id
  ON tramite_online.auth_users_permissions
  USING btree
  (user_id, permission_id);







