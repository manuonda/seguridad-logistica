
$.fn.datetimepicker.Constructor.Default = $.extend({}, $.fn.datetimepicker.Constructor.Default, {
    icons: {
        time: 'oi oi-timer',
        date: 'oi oi-calendar',
        up: 'oi oi-chevron-top',
        down: 'oi oi-chevron-bottom',
        previous: 'oi oi-chevron-left',
        next: 'oi oi-chevron-right',
        today: 'far fa-calendar-check-o',
        clear: 'far fa-trash',
        close: 'far fa-times'
    } });

$(document).on({
    ajaxStart: function() {
        $("#loading").modal("show");
    },
    ajaxStop: function() {
        $("#loading").modal("hide");
    }
});

function showPleaseWait() {
    var modalLoading = '<div class="modal" id="pleaseWaitDialog" data-backdrop="static" data-keyboard="false role="dialog">\
        <div class="modal-dialog">\
            <div class="modal-content">\
                <div class="modal-header">\
                    <h4 class="modal-title">Please wait...</h4>\
                </div>\
                <div class="modal-body">\
                    <div class="progress">\
                      <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"\
                      aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%; height: 40px">\
                      </div>\
                    </div>\
                </div>\
            </div>\
        </div>\
    </div>';
    $(document.body).append(modalLoading);
    $("#pleaseWaitDialog").modal("show");
}

/**
 * Hides "Please wait" overlay. See function showPleaseWait().
 */
function hidePleaseWait() {
    $("#pleaseWaitDialog").modal("hide");
}

function showAlert(mensaje, idElemento) {
	var box = bootbox.alert({
	    message: mensaje,
	    size: 'small',
	    title: "Alerta",
	    locale: 'es'
	});
	box.on('hidden.bs.modal',function() {
		$("#"+idElemento).focus();
    });
}

function isValidEmail(mail) {
	return /^\w+([\.\+\-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(mail); 
}

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function isNumberConPunto(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if(charCode == 46) {
        return true;
    }
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function resetaCombo(el) {
    $("select[name='" + el + "']").empty();
    var option = document.createElement('option');
    $(option).attr({value: ''});
    $(option).append('-- Seleccionar --');
    $("select[name='" + el + "']").append(option);
}

function fechaEnLetras(fecha) {
	var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	var diasSemana = new Array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
	var fechaTurno = fecha.split("-");
	var f = new Date(parseInt(fechaTurno[0]), parseInt(fechaTurno[1])-1, parseInt(fechaTurno[2]));
	return diasSemana[f.getDay()] + ", " + f.getDate() + " de " + meses[f.getMonth()] + " de " + f.getFullYear();
}
