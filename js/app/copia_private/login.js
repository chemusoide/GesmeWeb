
function loginUsuario(){
     // $(".alert").addClass("hide");
   var parametros = {
            "emailusr" : $("#email_log").val(),
            "password" : $("#password_log").val()
    };

    $.ajax({
            data:  parametros,
            url:   './private/loginUsuario',
            type:  'GET',
            dataType: 'json',
            success:  function (data) {

                if(data){
                    if(data.msgError){
                        $("form .form-group").addClass("has-error");
                         swal('Ups!', data.msgError, "error");
                    }
                       
                       
                    if (data && data.msgOk){
                    	if(data.listEmpresa && data.listEmpresa.length == 1)
                    		window.location.href = "./private/perfil";
                    	else{
                    		$('#flog').addClass('hide');
                    		$('#fselect').removeClass('hide');
                    		$('#empresaSel').append(
        					$('<option>', {
        						value: '',
        						text: 'Selecciona una empresa'
        					}));
                    		for(var i = 0; i < data.listEmpresa.length; i++){
                    			$('#empresaSel').append(
            					$('<option>', {
            						value: data.listEmpresa[i].idempresa,
            						text: data.listEmpresa[i].nombre
            					}));
                    		}
                    		
                    	}
                    }
                    	
                }
            }
    });
}



function restablecerContrasena(){

   var parametros = {
            "emailusr" : $("#email_log").val()
    };

    $.ajax({
            data:  parametros,
            url:   './private/restablecerContrasena',
            type:  'GET',
            dataType: 'json',
            success:  function (data) {

                if(data && data.msgOk){
                    $("form .form-group").addClass("has-error");
                    //swal('Estupendo!', data.msgOk, "success");
                    swal('Estupendo!', 'Mientras se activa el servicio de envio de contraseñas por mail, se mostrara por pantalla, modifiquela: ' + data.pass, "success");
                }else if(data && data.msgErr){
                    $("form .form-group").addClass("has-error");
                    swal('Ups!', data.msgErr, "error");
                }
            }
    });
}

function accederEmpresa(){
	
	if(!$('#empresaSel').val()){
		swal('Ups!', 'Seleccione una empresa', "error");
		return false;
	}
	
	var parametros = {
            "idempresa" : $('#empresaSel').val()
    };
	
	$.ajax({
        data:  parametros,
        url:   './private/accederEmpresa',
        type:  'GET',
        dataType: 'json',
        success:  function (data) {

	        if (data && data.msgOk)
	            	window.location.href = "./private/perfil";
        }
	});
}