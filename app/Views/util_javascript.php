<script type="text/javascript">
function limpiarBarrio() {
	$('#id_barrio').val(''); // se limpia el barrio
    $('#barrio').val('');
}

$("select[name=id_departamento]").change(function () {
// 	$("#loading").modal("show");
	id_departamento = $(this).val();
	//alert('aa='+id_departamento);
    if (id_departamento === '') {
    	return false;
    }

    resetaCombo('id_localidad');
    $.getJSON('<?php echo base_url(); ?>/localidad/getLocalidades/' + id_departamento, function (data) {
        var option = new Array(); //alert(data.localidades);
        $.each(data.localidades, function (i, obj) {
            option[i] = document.createElement('option');
            $(option[i]).attr({value: obj.id_localidad});
            $(option[i]).append(obj.localidad);
            $("select[name=id_localidad]").append(option[i]);
        });

        limpiarBarrio();

        var isVisible = $("#div_dependencia").is(":visible");
        if(!isVisible) {
        	return false;
        }

        if(data.dependencias.length != 0) {
        	resetaCombo('id_dependencia');
            var option2 = new Array();
            $.each(data.dependencias, function (i, obj) {
            	option2[i] = document.createElement('option');
                $(option2[i]).attr({value: obj.id_dependencia});
                $(option2[i]).append(obj.dependencia);
                $("select[name=id_dependencia]").append(option2[i]);
            });
        }
     });
});

$("select[name=id_localidad]").change(function () {
	limpiarBarrio();
});

$("#id_tipo_documento").change(function() {
	if(this.value == 1) {
		$("#divNroTramiteDni").show();
		documento = document.getElementById("documento");
		documento.type = "number";
	}else {
		$("#divNroTramiteDni").hide();
		documento.type = "text";
	}	
});

$("#linkNroTramiteDni").click(function() {
	var box = bootbox.alert({
	    message: '<div class="text-center"><img src="<?php echo base_url('assets/img/nro-tramite-dni.jpg'); ?>" class="img-fluid" /></div>',
	    locale: 'es'
	});
});

$("select[name=id_dependencia]").change(function () {
	var id_dependencia = $(this).val();
	var id_tipo_tramite = $('#id_tipo_tramite').val();
	var tipo_tramite_constancia_denuncia = '<?php echo TIPO_TRAMITE_CONSTANCIA_DENUNCIA ?>';
	//alert('aa='+id_departamento);
    if (id_dependencia === '' || id_tipo_tramite===tipo_tramite_constancia_denuncia) {
    	return false;
    }
    if (id_tipo_tramite == '<?php echo TIPO_TRAMITE_PLANILLA_PRONTUARIAL ?>') {
    	var tipo_planilla = $("input[name='tipo_planilla']:checked").val();

    	$.getJSON('<?php echo base_url(); ?>/turno/hayTurnoParaLaDependenciaPorTramite/'+id_dependencia+'/'+id_tipo_tramite+'/'+tipo_planilla, function (data) {
    		if(!data) {
    			$("#btnEnvioDatoPersonales").prop("disabled", true);
    			var dependencia = $( "#id_dependencia option:selected" ).text();
    			var box = bootbox.alert({
            	    message: 'Disculpe, no hay turnos disponibles para '+dependencia+'.',
            	    size: 'small',
            	    title: "Alerta",
            	    locale: 'es'
            	});
    		}else {
    			$("#btnEnvioDatoPersonales").removeAttr('disabled');
    		}		
         });
    }else {
    	$.getJSON('<?php echo base_url(); ?>/turno/hayTurnoParaLaDependencia/' + id_dependencia, function (data) {
    		if(!data) {
    			$("#btnEnvioDatoPersonales").prop("disabled", true);
    			var dependencia = $( "#id_dependencia option:selected" ).text();
    			var box = bootbox.alert({
            	    message: 'Disculpe, no hay turnos disponibles para '+dependencia+'.',
            	    size: 'small',
            	    title: "Alerta",
            	    locale: 'es'
            	});
    		}else {
    			$("#btnEnvioDatoPersonales").removeAttr('disabled');
    		}		
         });
    }
});

$('#checkSinBarrio').change(function() {
	var sin_barrio = '<?php echo SIN_BARRIO ?>';
    if(this.checked) {
    	$('#barrio').val(sin_barrio);
    	$('#barrio').prop("readonly", true);
    }else {
    	$('#barrio').val('');
    	$('#barrio').prop("readonly", false);
    }        
});

$('#checkSinNumero').change(function() {
	var sin_numero = '<?php echo SIN_NUMERO ?>';
    if(this.checked) {
    	$('#numero').val(sin_numero);
    	$('#numero').prop("readonly", true);
    }else {
    	$('#numero').val('');
    	$('#numero').prop("readonly", false);
    }        
});

$('#barrio').autocomplete({
	source: function(request, response) {
		$.ajax({
            url: "<?php echo site_url('barrio/autocomplete');?>",
            dataType: "json",
            global: false, // hace que no se muestre el loading 'cargando...'
            data: {
            	term : request.term,
            	id_localidad : $('#id_localidad').val()
            },
            success: function(data) {
            	response(data);
            }
        });
	},	
    minLength: 3,
    select: function (event, ui) {
        $('#barrio').val(ui.item.label);
        $('#id_barrio').val(ui.item.value);
        return false;
    },
    focus: function(event, ui){
    	$('#barrio').val(ui.item.label);
        return false;
    },
});

<?php if (empty($userInSession)) { ?>
$("#cuil").change(function () {
	var cuil = $(this).val();
	var id_tipo_tramite = $('#id_tipo_tramite').val();
	var tipo_tramite_constancia_denuncia = '<?php echo TIPO_TRAMITE_CONSTANCIA_DENUNCIA ?>';
    if (cuil === '' || id_tipo_tramite === '' || id_tipo_tramite === tipo_tramite_constancia_denuncia) {
    	return false;
    }

	if (cuil.length == 11){
		$.getJSON('<?php echo base_url(); ?>/tramite/isPersonaValidada/'+cuil+'/'+id_tipo_tramite, function (data) {
			$('#div_dependencia').show();
			if(data) {
				$('#isPersonaValidada').val('true');
				$('#div_dependencia').hide();
				
				$('#divTitleSubirDni').show();
				$('#divFotoDniFrente').show();
				$('#divFotoDniDorso').show();
			}
    	});
	}
});
<?php } ?>

$( "#btnDescargarTurno" ).click(function() {
	var id_tramite = $('#id_tramite').val();
	location.href = '<?php echo base_url(); ?>/turno/descargar/' + id_tramite;
});

$( "#btnDescargarTurno2" ).click(function() {
	var id_tramite = $('#id_tramite').val();
	location.href = '<?php echo base_url(); ?>/turno/descargar/' + id_tramite;
});

$( "#btnDescargarTurno3" ).click(function() {
	var id_tramite = $('#id_turno').val();
	location.href = '<?php echo base_url(); ?>/turno/descargar/' + id_tramite;
});
</script>