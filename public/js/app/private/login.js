
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
                       
                       
                    if (data && data > 0)
                    	window.location.href = "/private/perfil";
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