<?php // echo link_tag('css/style.css'); ?>
<style type="text/css">
	    /* Estilo que muestra la capa flotante */
    #flotante
    {
        position: absolute;
        display:none;
        font-family:Arial;
        font-size:1;
        width:480px;
/*        height:40px;*/
        border:1px solid #808080;
/*        background-color:#f1f1f1;*/
        background-color:#f1f1f1;                
        padding:5px;
    }
    
    .text {font-weight:bold;}/* Estilo que muestra la capa flotante */

</style>

    <?php

$nombre = array(
    '1' => 'Pulgar',
    '2' => 'Indice',
    '3' => 'Mayor',
    '4' => 'Anular',
    '5' => 'Miñique',       
    '6' => 'Pulgar',
    '7' => 'Indice',
    '8' => 'Mayor',
    '9' => 'Anular',
    '10' => 'Miñique',           
);    

$nom_dedos = array(
    '1' => 'Pulgar Derecho',
    '2' => 'Indice Derecho',
    '3' => 'Mayor Derecho',
    '4' => 'AnularDerecho',
    '5' => 'Miñique Derecho',       
    '6' => 'Pulgar Izquierdo',
    '7' => 'Indice Izquierdo',
    '8' => 'Mayor Izquierdo',
    '9' => 'Anular Izquierdo',
    '10' => 'Miñique Izquierdo',           
); 

?>


<!-- ---- Fotos Incio--- -->
<!--<div class='text' content="Este texto aparece en el segundotexto de la pantalla" style='margin-top:30px;'>Por el raton encima para ver la capa</div>-->

<h1>Informaicon 23 </h1>

<?php 
   $ob = NULL;
   $i= 0; ?>
<!-- --- Fin Fotos ---- -->


<h2>Informaicon 44</h2>

    <?php  if (!empty($huellass)) { 
                $valor = 1;
            }else {$valor = 0; } ?>


<button></button>

 <button id="plan61" content="<?php echo $ob ?>" style='margin-top:3px;'  class="agrega, text" type="button" name="plan61" onclick="modal_prueba($(this).attr('id'), '<?php echo base_url() . 'huellas/form_observacion/' . $rcuil ?>', '', 700, 360 , 'tabs_persona-7', <?= $rcuil ?>)" href="javascript:void(0);" > Observación<div id="flotante" height="340" width="235"></div></button>                     


 <button id="plan33" class="agrega" type="button" name="plan53" onclick="modal_prueba($(this).attr('id'), '<?php echo base_url() . 'huellas/form_imagen/' . $rcuil ?>', '', 800, 360 , 'tabs_persona-7', <?= $rcuil ?>)" href="javascript:void(0);" >Subir Imagen Huella</button>                    

&nbsp;&nbsp;&nbsp;&nbsp;
<!--<a id="plan55" onclick="modal($(this).attr('id'),'<?php echo base_url() . 'huellas/form_amputado/' . $rcuil?>', 300, 500)" href="javascript:void(0);">
    <img src="<?php echo base_url() ?>images/icons/16/edit.png" alt="Modificar Huellas" title="Mmodificar Huellas" /> <strong> Dedo Amputado</strong>                                     
</a>    -->
 <button id="plan55" class="agrega" type="button" name="plan53" onclick="modal_prueba($(this).attr('id'), '<?php echo base_url() . 'huellas/form_amputado/' . $rcuil ?>', '', 800, 410 , 'tabs_persona-7', <?= $rcuil ?>)" href="javascript:void(0);" >Dedo Amputado sin Imagen</button>                    

&nbsp;&nbsp;&nbsp;&nbsp;
<?php //if($valor == 0) {?>
<!--title="<?php // echo $ob ?>"-->
<!--
 <button id="plan56" class="agrega" type="button" name="plan56" onclick="modal_prueba($(this).attr('id'), '<?php echo base_url() . 'huellas/huellas_existe/' . $rcuil . '/' . $pers . '/' . $valor ?>', '', 800, 360 , 'tabs_persona-7', <?= $rcuil ?>)" href="javascript:void(0);" >Clasificar sin Imagen de Huella </button>                    
-->
<?php // echo "&nbsp&nbsp&nbsp<a href=# class='cambiarObservacion'> Observaciones</a>";  ?>   
<?php $id_max = 7308; ?> 
<!-- <button id="plan57" class="agrega" type="button" name="plan57" onclick="modal_prueba($(this).attr('id'), '<?php echo base_url() . 'huellas/actuliza_path/' . $id_max ?>', '', 800, 360 , 'tabs_persona-7', <?= $rcuil ?>)" href="javascript:void(0);" >Actulizar URL-antigua </button>                    -->
 

 
 <br>

<?php // ================== NEW MOSTRAR HUELLAS ====================== ?>
    <?php   if (!empty($huellass)) { 
        $i= 0;
            ?>
        <fieldset  class="fiel_1">
           <legend><strong>&nbsp; Mano Derecha - SERIE &nbsp;</strong></legend>
               <table class="tablesorter5" WIDTH=100% border="0" cellpadding="15" cellspacing="0">
                <tr align="center">
                <td><b>Pulgar</b></td> <td><b>Indice</b></td> <td><b>Mayor</b></td> <td><b>Anular</b></td> <td><b>Meñique</b> </td>
                </tr>
                <?php
                    $dedos = array(
                        '1' => array(
                                'id' => '',
                                'url' => '',
                                'huella' => '', 
                                'path_archivo' => '', 
                                    ),
                        '2' => array(
                                'id' => '',
                                'url' => '',
                                'huella' => '',
                                'path_archivo' => '', 
                                    ),
                        '3' => array(
                                'id' => '',
                                'url' => '',
                                'huella' => '',     
                                'path_archivo' => '', 
                                    ),
                        '4' => array(
                                'id' => '',
                                'url' => '',
                                'huella' => '',                                
                                'path_archivo' => '', 
                                    ),
                        '5' => array(
                                'id' => '',
                                'url' => '',
                                'huella' => '',                                
                                'path_archivo' => '', 
                                    ),
                        '6' => array(
                                'id' => '',
                                'url' => '',
                                'huella' => '',                                
                                'path_archivo' => '', 
                                    ),
                        '7' => array(
                                'id' => '',
                                'url' => '',
                                'huella' => '',                                
                                'path_archivo' => '', 
                                    ),
                        '8' => array(
                                'id' => '',
                                'url' => '',
                                'huella' => '',                                
                                'path_archivo' => '', 
                                    ),
                        '9' => array(
                                'id' => '',
                                'url' => '',
                                'huella' => '',                                
                                'path_archivo' => '', 
                                    ),
                        '10' => array(
                                'id' => '',
                                'url' => '',
                                'huella' => '',                                
                                'path_archivo' => '', 
                                    ),
                                );
                    
                    foreach ($huellass as $h):  
                      //  echo $h->url . '/ ';
                        if($h->dedo < 11) { 
                            $dedos[$h->dedo]['id'] = $h->id_huella;
                            $dedos[$h->dedo]['url'] = $h->url;
                            $dedos[$h->dedo]['huella'] = $h->huella;
                            $dedos[$h->dedo]['path_archivo'] = $h->path_archivo;
                        }
                    endforeach;                        
                    $ban = 0;
                        ?>
                    <tr id="fila">

                    <?php for ($i = 1; $i < 6; $i++) { ?>
                               <td height="140" width="135">      
                               <?php // echo $dedos[$i]['url'] . ' '; ?>
                          <?php if($dedos[$i]['url'] != '') { ?>                                                              
                                    <center><img src="<?php echo base_url($dedos[$i]['path_archivo'].'/'.$dedos[$i]['url']) ?>" class="magnify" style="width: 125px; height: 100px; opacity: 1"/></center>                                 
                                    <?php
                                    if($dedos[$i]['huella'] == '' or $dedos[$i]['huella'] == NULL) {  $ban = 1;?>
                                            <center><marquee>
                                            <font color="red"><b> --- </b></font>                                            
                                            </marquee></center>
                                    <?php }else { ?>
                                    
                                            <center> <?php  echo $dedos[$i]['huella']; ?></center>                         
                                    
                                    <?php } ?>
                                            
                          <?php } elseif($dedos[$i]['huella'] == 'O') { ?>
                             <center><img src="<?php echo base_url('cuil/dedo_amputado.bmp') ?>" class="magnify" style="width: 125px; height: 100px; opacity: 1"/></center>  
                             <center> <?php  echo $dedos[$i]['huella']; ?></center>   
                                            
                            <?php }else  { 
                                $hu = $dedos[$i]['id'];
                                if($hu != NULL and $hu != '' and $hu != 0) {?>                                      
                                <center><img src="<?php echo base_url('cuil/mensaje.bmp') ?>" class="magnify" style="width: 125px; height: 100px; opacity: 1"/></center>                                                               
                                <center> <?php  echo $dedos[$i]['huella']; ?></center>                                                                                                                           
                                  <?php  } ?>                                            
                          <?php  } ?>                                            
                                </td>     
                     <?php } ?>                                                                                    
                    </tr>                                                                     
            </table> 
           </fieldset>
        <fieldset  class="fiel_1">
           <legend><strong>&nbsp; Mano Izquierda - SECCIÓN &nbsp;</strong></legend>
               <table class="tablesorter5" WIDTH=100% border="0" cellpadding="15" cellspacing="0">
                <tr align="center">
                <td><b>Pulgar</b></td> <td><b>Indice</b></td> <td><b>Mayor</b></td> <td><b>Anular</b></td> <td><b>Meñique</b> </td>
                </tr>
                    <tr id="fila">
                    <?php for ($i = 6; $i < 11; $i++) { ?>
                               <td height="140" width="135">      
                               <?php // echo $dedos[$i]['url'] . ' '; ?>
                          <?php if($dedos[$i]['url'] != '') { ?>                                                              
                                    <center><img src="<?php echo base_url($dedos[$i]['path_archivo'].'/'.$dedos[$i]['url']) ?>" class="magnify" style="width: 125px; height: 100px; opacity: 1"/></center>                                 
                                    <?php
                                    if($dedos[$i]['huella'] == '' or $dedos[$i]['huella'] == NULL) { $ban = 1;?>
                                            <marquee>
                                            <font color="red"><b> --- </b></font>                                            
                                            </marquee>
                                    <?php }else { ?>
                                            <center> <?php  echo $dedos[$i]['huella']; ?></center>                                                             
                                    <?php } ?>
                                            
                          <?php } elseif($dedos[$i]['huella'] == 'O') { ?>
                             <center><img src="<?php echo base_url('cuil/dedo_amputado.bmp') ?>" class="magnify" style="width: 125px; height: 100px; opacity: 1"/></center>  
                             <center> <?php  echo $dedos[$i]['huella']; ?></center>   
                                            
                            <?php }else {
                                $hu = $dedos[$i]['id'];
                                if($hu != NULL and $hu != '' and $hu != 0){
                                ?>    
                                <center><img src="<?php echo base_url('cuil/mensaje.bmp') ?>" class="magnify" style="width: 125px; height: 100px; opacity: 1"/></center>                                                               
                                <center> <?php  echo $dedos[$i]['huella']; ?></center>                                 
                                <?php  } ?> 
                          <?php  } ?>                                            
                                </td>     
                     <?php } ?>                                                                                                                                                                                                                                                                                                            
                    </tr>                                                      
            </table> 
           </fieldset>
            <center>
            <a id="plan44" onclick="modal_prueba($(this).attr('id'),'<?php echo base_url() . 'huellas/modificar_hue/' . $rcuil?>', '', 1000, 660 , 'tabs_persona-7', <?= $rcuil ?>)" href="javascript:void(0);">
                <img src="<?php echo base_url() ?>images/icons/16/edit.png" alt="Tipificar Huellas" title="Tipificar Huellas" /> <strong> Clasificar Huellas Dactilar</strong>                                     
            </a>    
                
            </center>
           <?php } else {         
 
             
                echo "<br><blink><span class='form_required'><p><center> - - - LA PERSONA NO REGISTRA HUELLAS - - - </center></p></span></blink>";

            
            return;

               } ?>

           <?php 
           if (!empty($ban)){
           if($ban != 0){ ?>
                <marquee scrolldelay="700" scrollamount="100">
                <font color="red"><b>DEBE CLASIFICAR HUELLA </b></font>
                </marquee>
           <?php }}?>

 <button id="mostrar" class="mostrar" type="button" name="mostrar" onclick="javascript:mostrarOcultarTablas('tabla1')">Historial - Mostrar/Ocultar</button>                      

<div id="tabla1" name"tabla1" style="display: none" >
<?php if (!empty($huella_all)) { ?>
    <br>
    <table id="mitabla_h" class="tablesorter" border="0" cellpadding="0" cellspacing="1" >
        <caption style="background:#DDECF3; border:1px solid black; border-radius:10px 10px 0px 0px"><font size=3><b>Historial de Huellas</b></font></caption>
        <thead>            
            <tr style="text-align:center;">
                <th width="30px">#&nbsp;</th>
                <th width="100px">Dedo </th>
                <th width="100px">Clasificación</th> 
                <th width="120px">Fecha Alta</th>
                <th width="100px">Mostrar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            $j = 0;
            foreach ($huella_all as $h_all):
                $sip = 0;
                for ($j = 1; $j <= 10; $j++) {
                    if($dedos[$j]['id'] == $h_all->id_huella)
                    { $sip = 1; }                    
                }
                if($sip != 1 ){    
                    $fechah=date("d-m-Y",strtotime($h_all->fecha_alta));
                ?>
                <tr id="fila">            
                    <td align="center"><?php echo $i; ?>   </td>
                    <td> <?php // echo $h_all->dedo; 
                                echo $nom_dedos[$h_all->dedo];?>     </td>                                       
                    <td> <?php echo $h_all->huella; ?> </td>
                    <td> <?php echo $fechah; ?></td>
                    <td align="center">
                     <?php 
                        if($h_all->huella == 'O'){ ?>
                            <center><img src="<?php echo base_url('cuil/dedo_amputado.bmp') ?>" class="magnify" style="width: 30px; height: 20px; opacity: 1"/></center>  
                     <?php
                        }else{
                        ?>
                            <center><img src="<?php echo base_url($h_all->path_archivo.'/'.$h_all->url) ?>" class="magnify" style="width: 30px; height: 20px; opacity: 1"/></center>
                        <?php }
                        ?>
                    </td>                    
                </tr>
                <?php
                $i++;
                }                
            endforeach;
            ?> 
    </tbody>          
    </table>

    <?php }?>      
</div>


    