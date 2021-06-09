
var tieneDatos = false;
$( document ).ready(function() {
	$('#sRoles').on("select2:select", function (e) { 
		if(e && e.params && e.params.data && (e.params.data.id == 'MED' || e.params.data.id == 'FIS' || e.params.data.id == 'OTE') ){
			$('#fgColeg').removeClass("hide");
			$('#fgEsp').removeClass("hide");
		}
	});
	$('#sRoles').on("select2:unselect", function (e) {
		if(e && e.params && e.params.data && (e.params.data.id == 'MED' || e.params.data.id == 'FIS' || e.params.data.id == 'OTE') ){
			$('#fgColeg').addClass("hide");
			$('#fgEsp').addClass("hide");
			$('#colegiado').val('');
			$("#sEspecialidad").select2("val", "");
		}
			
	});
	console.info(window.location.pathname);
	if(window.location.pathname.indexOf('/registro'))
		initPantalla();
});

function generarUrlReg(accion){
	var url = '';
	if(location.hostname == 'localhost'){
		url = accion;
	}else{
		//cambiar por lo que toque
		url = '/public' + accion;
	}
	
	return url;
}

function validateEmail(email) 
{
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}

function mostrar_mensaje(msg){
	if( $("#msgInfo").offset()){
		 $('html, body').animate({
		      scrollTop: $("#msgInfo").offset().top
		  }, 2000);
		  $(".alert").removeClass("hide");
		  $(".alert").delay(500).addClass("animation-shake animation-duration-1");
		  $(".alert").empty();
		  $(".alert").append(msg);
	}
	 
	}

	function initPantalla (){
		$('.loader-wrap').removeClass("hide");
		$.ajax({
		        url:   generarUrlReg('/private/obtenerDatosInitAlta'),
		        type:  'GET',
		        dataType: 'json',
		        success:  function (data) {
		        	$('.loader-wrap').addClass("hide");
		        	if(data){
		        		if(!tieneDatos){
			        			tieneDatos = true;
			            	if(data.roles){
			            		for(var i = 0; i < data.roles.length; i++){
									$('#roles').append(
										$('<option>', {
									    value: data.roles[i].codrol,
									    text: data.roles[i].nomrol
									}));
			            		}
			            		
			            	}
			            	if(data.especialidades){
			            		for(var i = 0; i < data.especialidades.length; i++){
									$('#especialidad').append(
										$('<option>', {
									    value: data.especialidades[i].codesp,
									    text: data.especialidades[i].especialidad
									}));
			            		}
			            		
			            	}
		        		}
		            }
		        }
		}); 
	}
	
	function addError(c){
	    c.addClass("has-error");
	}
	
	function mostrarNumDoc(extension){
		//var strCompTip = "#tipdoc";
		
		if($( "#tipdoc" + extension).val()== 'DNI'){
			$( "#dniusr" + extension ).removeClass('hide');
			$( "#nieusr" + extension ).addClass('hide');
			$( "#passusr" + extension ).addClass('hide');
		}
		if($( "#tipdoc" + extension ).val()== 'NIE'){
			$( "#dniusr" + extension ).addClass('hide');
			$( "#nieusr" + extension ).removeClass('hide');
			$( "#passusr" + extension ).addClass('hide');
		}
		if($( "#tipdoc" + extension ).val()== 'PAS'){
			$( "#dniusr" + extension ).addClass('hide');
			$( "#nieusr" + extension ).addClass('hide');
			$( "#passusr" + extension ).removeClass('hide');
		}
	}
	
	$( "#tipdoc" ).change(function(v) {
		$( "#dniusr" ).val('');
		$( "#nieusr" ).val('');
		$( "#passusr" ).val('');
		
		mostrarNumDoc('');
	});

	
	function guardarUsuario (esPrivate){
		
		var numdoc = $( "#tipdoc" ).val()== 'DNI'? $("#dniusr").val(): $( "#tipdoc" ).val()== 'NIE'? $("#nieusr").val():$( "#tipdoc" ).val()== 'PAS'? $("#passusr").val():'';
		numdoc = (numdoc=='        - ')|| numdoc==''?null:numdoc;
		var error = false;
		$("form .form-group").removeClass("has-error");
		if(!$("#nomusr").val()){error = true; addError($("#fgNombre"));}
	    if(!$("#apusr").val()){error = true; addError($("#fgSurName"));}
	    if(!numdoc){error = true; addError($("#fgDni"));}
	    if(!$("#emailusr").val() || !validateEmail($("#emailusr").val())){error = true; addError($("#fgEma"));}
	    
	    
		
	    
	    var rolSelect = []; 
	    var espSelect = []; 
	    var swimed = false;
		$('#sRoles :selected').each(function(i, selected){ 
			rolSelect.push(selected.value);
			if(selected.value == 'MED')
				swimed = true;
		});
		if(swimed == true){
			
			if(!$("#colegiado").val()){
				addError($("#fgColeg"));
				mostrar_mensaje('El número de colegiado es obligatorio');
				return false;
			}
			
			$('#sEspecialidad :selected').each(function(i, selected){ 
				espSelect.push(selected.value);
			});
			
			if(!espSelect || espSelect.length == 0){
				mostrar_mensaje('Seleccione al menos una especialidad');
				return false;
			}
		}
		
		if(!rolSelect || rolSelect.length == 0){
			mostrar_mensaje('Seleccione almenos un rol');
			return false;
		}else if(error){
			mostrar_mensaje('Debe de Rellenar los campos Obligatorios: Nombre, Apellidos, DNI y Email');
			return false;
		}
		$('.loader-wrap').removeClass("hide");
		$.ajax({
			data: {
				'id': $('#id').val(),
				'nomusr' : $('#nomusr').val(),
				'apusr' : $('#apusr').val(),
				'tipdoc': numdoc?$('#tipdoc').val():null,
				'dniusr' : numdoc,
				'numtel1' : $('#numtel1').val(),
				'numtel2' : $('#numtel2').val(),
				'emailusr' : $('#emailusr').val(),
				'rolesSelec' :rolSelect,
				'numcoleg' : $('#colegiado').val(),
				'espeSelect' : espSelect,
				'esPrivate' : esPrivate
			},
	        url:   generarUrlReg('/private/altaUsuario'),
	        type:  'GET',
	        dataType: 'json',
	        success:  function (data) {
	        	$('.loader-wrap').addClass("hide");
	        	if(data && data.usuario && esPrivate == 'S'){
	        		limpiarPrivate();
	        		swal("Correcto", 'Usuario dado de alta', "success");
	        		return true;
	        	}
	        	else if(data.msgError && esPrivate == 'S'){
	        		//usuariosRegistrados.js
	        		swal("Error", data.msgError, "error");
	        		return false;
	        	}
	        		
	        	if(data && data.usuario)
	        		window.location.href = '/public/registro-completado';
	        	else if(data.msgError)
	        		mostrar_mensaje(data.msgError);
	        }
	}); 
		
		
		
			/*
		
		*/
	}
		