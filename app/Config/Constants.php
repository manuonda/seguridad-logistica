<?php

//--------------------------------------------------------------------
// App Namespace
//--------------------------------------------------------------------
// This defines the default Namespace that is used throughout
// CodeIgniter to refer to the Application directory. Change
// this constant to change the namespace that all application
// classes should use.
//
// NOTE: changing this will require manually modifying the
// existing namespaces of App\* namespaced-classes.
//
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
|--------------------------------------------------------------------------
| Composer Path
|--------------------------------------------------------------------------
|
| The path that Composer's autoload file is expected to live. By default,
| the vendor folder is in the Root directory, but you can customize that here.
*/
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
|--------------------------------------------------------------------------
| Timing Constants
|--------------------------------------------------------------------------
|
| Provide simple ways to work with the myriad of PHP functions that
| require information to be in seconds.
*/
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2592000);
defined('YEAR')   || define('YEAR', 31536000);
defined('DECADE') || define('DECADE', 315360000);

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

define('TITLE_SISTEMA', 'Tramite online');

# define Mercado Pago Constantes 
define('COUNTR_ID_MP','AR');
define('STATE_ID_MP','AR-Y');
// Estado definido de Mercado Pago
define('STATE_NAME_MP','Jujuy');
// Category correspondiente a Mercado Pago
define('CATEGORY_GASTRONOMIA_MP', 621102 );
// define unit_measure
define('UNIT_MEASURE', 'unit');

// define estados del pago
define('ESTADO_PAGO_PAGADO','PAGADO');
define('ESTADO_PAGO_PENDIENTE','PENDIENTE');
define('ESTADO_PAGO_CANCELADO','CANCELADO');
define('ESTADO_PAGO_NO_EXISTE','NO_EXISTE');
define('ESTADO_PAGO_VALIDADO', 'VALIDADO');
define('ESTADO_PAGO_EXPIRADA','EXPIRADA');
define('ESTADO_PAGO_IMPAGO', 'IMPAGO');


// define estados del tramite
define('TRAMITE_PENDIENTE_VALIDACION', 'PENDIENTE VALIDACION');
define('TRAMITE_VALIDADO', 'VALIDADO');
define('TRAMITE_NO_VERIFICADO', 'NO VERIFICADO');
define('TRAMITE_VALIDADO_VERIFICADO', 'VALIDADO Y VERIFICADO');
define('TRAMITE_INVALIDADO', 'INVALIDADO');
define('TRAMITE_APROBADO', 'APROBADO');
define('TRAMITE_EMAIL_ENVIADO','EMAIL ENVIADO');
define('TRAMITE_EMAIL_NO_ENVIADO',' EMAIL NO ENVIADO');

// define estados de verificacion para los tramites de planillas
define('TRAMITE_PENDIENTE_VERIFICACION', 'PENDIENTE VERIFICACION');
define('TRAMITE_VERIFICADO', 'VERIFICADO');
define('TRAMITE_VERIFICADO_CON_OBSERVACION', 'VERIFICADO CON OBSERVACION');
define('TRAMITE_VERIFICADO_CON_INFORME', 'VERIFICADO CON INFORME');

define('STATUS_MP_APROBADO','approved');
define('STATUS_MP_PENDIENTE','pending');

// Base url
define('BASE_URL_MP', 'https://api.mercadopago.com/');
define('BASE_URL_ML','https://api.mercadolibre.com/');

define('INT_CERO', 0);
define('INT_UNO', 1);
define('INT_DIEZ', 10);
define('INT_MENOS_UNO', -1);

// tipo de convivencia
define('CONVIVE_CON_OTRAS_PERSONAS', 'CONVIVE_CON_OTRAS_PERSONAS');
define('CONVIVE_SOLO', 'CONVIVE_SOLO');

define('TIPO_TRAMITE_CERTIFICADO_RESIDENCIA', 1);
define('TIPO_TRAMITE_CERTIFICADO_RESIDENCIA_CONVIVENCIA', 2);
define('TIPO_TRAMITE_CERTIFICADO_SUPERVIVENCIA',3);
define('TIPO_TRAMITE_CONSTANCIA_DENUNCIA', 16);
define('TIPO_TRAMITE_PLANILLA_PRONTUARIAL', 17);
define('TIPO_TRAMITE_CONSTANCIA_EXTRAVIO', 7);
define('TIPO_TRAMITE_PAGO_REBA', 47);
define('TIPO_TRAMITE_TRAMITAR_REBA', 49);
define('TIPO_TRAMITE_EXPOSICION_POR_JUSTIFICATIVO_LABORAL_POR_FALTA_DE_TRANSPORTE', 50);
define('TIPO_TRAMITE_CONSTANCIA_POR_NO_VOTACION', 51);

//para Contravencion
define('TIPO_TRAMITE_PAGO_CONTAVENCION', 48);

define('ROL_UNIDAD_ADMINISTRATIVA', 1);
define('ROL_COMISARIA_SECCIONAL', 2);
define('ROL_DAF', 3);
define('ROL_CIAC', 4);
define('ROL_JEFE_DAP', 5);
define('ROL_JEFE_UNIDAD_ADMINISTRATIVA', 6);
define('DAP_RENDICION', 7);
define('ROL_UAD_UNIDAD_REGIONAL', 8);
define('ROL_UAD_REBA_CENTRAL', 9);
define('ROL_UAD_UNIDAD_REGIONAL_UR5', 10);
define('ROL_ANTECEDENTE', 11);

//para el rol de Contravencion
define('ROL_DEPARTAMENTO_CONTRAVENCION', 12);

define('PRIMERA_VEZ', 'PRIMERA_VEZ');
define('RENOVACION', 'RENOVACION');

define('ID_DEP_CENTRAL_DE_POLICIA', 700);
define('ID_DEP_SECCIONAL_23', 23);
define('ID_DEP_SECCIONAL_47', 47);
define('ID_DEP_SUBCRIA_RIO_BLANCO', 327);
define('ID_DEP_UAD_CENTRAL', 900);
define('ID_DEP_UAD_SAN_PEDRO_UR2', 902);
define('ID_DEP_UAD_HUMAHUACA_UR3', 903);
define('ID_DEP_UAD_LGSM_UR4', 904);
define('ID_DEP_UAD_LA_QUIACA_UR5', 905);
define('ID_DEP_UAD_PERICO_UR6', 906);
define('ID_DEP_UAD_ALTO_COMEDERO_UR7', 907);
define('ID_DEP_UAD_PALPALA_UR8', 908);
define('ID_DEP_UAD_MOVIL_SS_DE_JUJUY', 920);
define('ID_DEP_UAD_TILCARA', 1010);
define('ID_DEP_UAD_ABRAPAMPA', 1011);
define('ID_DEP_UAD_EL_CARMEN', 1012);
define('ID_DEP_DAF', 105);

# definition of id_tipo_pago
define('TIPO_PAGO_ONLINE',2);
define('TIPO_PAGO_CONTADO', 1);


define('MP_PENDING', "pending");
define('MP_APPROVED','approved');
define('MP_AUTHORIZED','authorized');
define('MP_IN_PROCESS','in_process');
define('MP_IN_MEDITATION','in_mediation');
define('MP_REJECTED','rejected');
define('MP_CANCELLED','cancelled');
define('MP_REFUNDED','refunded');
define('MP_CHARGED_BACK','charged_back');


// PAYMENT_TYPE DE MOVIMIENTO_PAGO
define('PAYMENT_TYPE_CONTADO','contado');

// SITE 
define('SITE_POLICIA', 'POLICIA');
define('SITE_MLA','MLA');

// REFERENCE PAGO 
define('MERCADO_PAGO','MERCADO_PAGO');
define('BANCO_MACRO','BANCO_MACRO');
define('COMISARIA_PAGO','COMISARIA_PAGO');
define('GRATIS','GRATIS');





// REFERENCIA A FOTOS 
define('FOTO_FRENTE','FOTO_FRENTE');
define('FOTO_DORSO','FOTO_DORSO');
define('FOTO_FACTURA_SERVICIO','FOTO_FACTURA_SERVICIO');
define('FOTO_ROSTRO','FOTO_ROSTRO');
define('ARCHIVO_PLANILLA','ARCHIVO_PLANILLA');
define('FOTO_COLOR','FOTO_COLOR');
define('HUELLA_DIGITAL','HUELLA_DIGITAL');
define('PLANILLA_ARCHIVO_INFORME','PLANILLA_ARCHIVO_INFORME');


define('SIN_NUMERO', "S/N");
define('SIN_BARRIO', "S/B");


# nombre de directorios
define("ARCHIVOS_DIGITALES","archivos_digitales");
define("ARCHIVOS_CERTIFICADOS","archivos_certificados");
define("ARCHIVOS_PLANILLA","archivos_planilla");

#id cuenta de email 
define("CUENTA_EMAIL_1",1);
define("CUENTA_EMAIL_2",2);
define("CANTIDAD_POR_EMAIL",99);
define("MAX_NUMERO_ID_CUENTA_EMAIL",17);

// tipos de documentos
define("TIPO_DOC_DNI",1);

//solo tendran deuda los que tengan los siguientes estados
define("MOVIMIENTO_JUZGADO_1",9);
define("MOVIMIENTO_JUZGADO_2",15);


// tipo de elecciones 
define("ELECCIONES_PROVINCIALES","ELECCIONES (P.A.S.O.), PRIMARIAS, ABIERTAS, SIMULTÁNEAS Y OBLIGATORIAS");
define("FECHA_VOTACION","10/22/2023"); // Mont/Date/Year => MM/DD/YYYY

//define elecciones nacionales 
define("ELECCIONES_GENERALES"," Elecciones generales");


define('TITLE_SISTEMA', 'SISTEMA DE LOGÍSTICA');
define('OPER_INSERTAR', '1');
define('OPER_EDITAR', '2');
define('RUTA_SERVER', "19.168.10.6" . "/logistica/");