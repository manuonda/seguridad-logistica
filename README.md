# Instalation Mercado Pago 
chmod 775 -R  seguridad-tramite-comisaria-online/

composer require "mercadopago/dx-php"
composer require tecnickcom/tcpdf
composer require myth/auth:1.*@dev
sudo apt-get install php-curl
sudo apt-get install php-intl

# Para cargar las clases 
composer install
composer dump-autoload -o



curl -X POST \
-H "Content-Type: application/json" \
"https://api.mercadopago.com/users/test_user?access_token=TEST-3960517786757313-060103-0b0aa5fe87bba8a81224101d8a3fbed0-146439894" \
-d '{"site_id":"MLA"}'

{"id":628518695,"nickname":"TESTUKYV3XHT","password":"qatest659","site_status":"active","email":"test_user_27394103@testuser.com"}
{"id":628520940,"nickname":"TEST2ALVANBE","password":"qatest8664","site_status":"active","email":"test_user_45516489@testuser.com"}
{"id":628520998,"nickname":"TETE8962641","password":
"qatest7609","site_status":"active","email":"test_user_21325474@testuser.com"}

Nota 1 
------------
La persona puede tener mas de un tramite realizado.
Porque puede ser que saque por ejemplo del mismo dni => 
 Certificado de Residencia , 
 Certificado de escolaridad. 
 Le va a mostrar los tramites a descargar de la persona, pero tiene 
 que ver el tramite si esta verificado por el usuario del sisteam y esta pagado .
 Al momento de buscar la informacion puede pregutnar al mercado pago si esta pagado. por cada tramite 
 que tenga la persona y no esta vencido tambien.
 - Esta verificado sus datos por el usuario administrador 
 - Esta verificado por mercado pago 
 - Tendria que establecer si se permite descargar y la fecha de vencimiento.


---------------------
Nota preguntar a Jorge
----------------------

En al pagina podria ingresar el codigo de operacion si realizo 
el pago por algun cajero o rapipago.


Read Ion Auth 4 
----------------
Este es un fork de Ion Auth 4 no tiene mucho seguimiento
https://github.com/bvrignaud/CodeIgniter-Ion-Auth/tree/4
https://forum.codeigniter.com/thread-74712-post-369353.html#pid369353


Tank
-------
Quedo deprecate la ultima version 1.0.8 Indica: 
1.0.8
    Some technical changes to make the library compatible with CodeIgniter v.2.0.0. 
1.0.9 : Habla PHPass settings


my-auth : 222 star
----------
https://github.com/lonnieezell/myth-auth
es otra libreria pero tenia problemas al migrate probe 
https://www.youtubp.com/watch?v=mA3wCQHaUpI


Se toma un login de un C4
--------------------------
https://github.com/alexlancer/codeigniter4login

1 -  TERMINTAR LOS 5 CERTIFICADOS DE LA PARTE DE VISTA DEL FRONTEND
2 -  MODIFICAR LA EDICION DE CERTIFICADO DE RESIDENCIA , CERYC de la parte del backend que estaban funcionando antes como estaba, ver esta parte bien.
3 -  Terminar de completar la edicion de los 3 otros controllers de certificados.
4 -  VER EL TEMA DE LOS PAGOS ONLINE Y POR MP EN EL PANEL DE ADMINISTRACION PARA MOSTRAR UNA COLUMNA MAS.



Fecha 9-02-2020
---------------
1 - Revisar error en certificado de ResidenciaConvivencia no carga las localidades. 
2 - Preguntar  Jorg el editar si cargaba las personas que se cargaban en el certificado. 
3 - Continuar con los otros 3 formularios para el wizard.
4 - Cambiar y mostrar en el wizard de view , los datos del turno.

Fecha 11-02-2020.
-----------------
1 - Revisar la parte de "estados" en los formularios al editar.
2 - Agregar la columna de fecha de alta y el usuario que lo cargo 
3 - Como esta el pago y como esta el estado de los datos.
4 - Agregar una parte de filtros en la cabecera pendiente de validacion y pendiente 
de pagos para filtrar
5 - Mostrar el turno al retornar el wizard.


17-02-2020
--------------------


-------------------------------------------
--- HABLAR CON JORGE 
------------------------------------------
Comento el metodo de BaseController -> 

     public function isDatoPersonaValidado($documento) {
        $personaModel = new PersonaModel();
        $persona = $personaModel->where('documento', $documento)
                                ->where('es_titular_tramite', INT_UNO)
                                ->where('validado', INT_UNO)->first();
    
        return true;
                                //FIX ME : AQUI COMENTO Y RETORN SIEMPRE TRUE                            
    //    if(empty($persona)) {
    //         return false;
    //     }else {
    //         return true;
    //     }
    }


     1 -  TERMINAR EDICION DE LOS ABMS DE TODOS LOS TRAMITES 6

---------------------- 
1 - FILTROS DE FECHAS 
2 - AGREGAR UN ESTADO DE ENVIO DE EMAIL 
3 - NO MOSTRAR SI TENES LOS 2 ESTADOS EN APROBADO Y ENVIADO POR EMAIL A LA PERSONA. 
AL REALIZAR LA CARGA INICIAL
4 - TRAMITE CON FECHA ACTUALES SOLAMENTE

5 - En el modal mostrar si existe el archivo de la firma digital para descargarlo puesto que ya existe y puede ser 
reemplazado. Entonces en el modal mostrar si existe uno poder descargarlo



https://www.positronx.io/codeigniter-upload-multiple-images-files-example/

Este es el controller que carga todos los datos correspondientes 
controllers/persona/panel.php


 

Como armar un cronjob : 
* crontab -e
*  

----------------------------------------------------------------------
Cada 5 minutos :
*/5  * * * * curl https://tramites.seguridad.jujuy.gob.ar/BancoMacroJob


$ crontab e – para crear y editar un archivo crontab.
$ crontab -u nombre de usuario -e – para editar el archivo crontab de otro usuario con acceso de superusuario.
$ crontab -l: para ver la lista de archivos crontab de los usuarios actuales.
$ crontab -r: para eliminar los archivos crontab.
$ crontab -a nombredearchivo: para instalar el nombre de archivo como un archivo crontab (en algunos sistemas, –a no es necesario)



 - Finanza es quien cobra si el tramite tiene INT_UNO se cobra el precio del tramite 
 sino se toma el valor del tipoTramite finanzas/12345678
 - Ver cuando cobra finanzas que valor tomar para pagar 
 - Ver porque no guarda cero el valor


-- INSERT REGISTRO 
INSERT INTO tramite_online_2.tipo_tramites
(id_tipo_tramite, tipo_tramite, precio, habilitado, id_origen, venta, usuario_alta, fecha_alta, usuario_modificacion, fecha_modificacion, controlador, controlador_view, controlador_title, importe_adicional)
VALUES(51, 'Exposicion por justificavo no votación', 100, true, NULL, false, 1, '2022-07-18 19:03:35.000', NULL, NULL, 'exposicionPorJustificativoNoVotacion', 'exposicionPorJustificativoNoVotacion', 'Exposición por justificativo no votación', NULL);


ALTER TABLE tramite_online.tramites ADD razon_social varchar NULL;
