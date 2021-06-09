function crearMenu(lRol){
	if(lRol){
		$('#menuLeft').append('<li class="site-menu-category">Menú</li>');
		for(var i = 0; i < lRol.length; i++){
			var rol = lRol[i];
				
			$('#menuLeft').append('<li class="site-menu-item" name = "'+rol.name+'">' 
				      +'<a class="animsition-link" href="'+generarUrl(rol.href)+'" data-slug="app-users">'
				        +'<i class="site-menu-icon '+rol.icon+'" aria-hidden="true"></i>'
				       +' <span class="site-menu-title">'+rol.title+'</span>'
				      +'</a>'
				    +'</li>');
			    
			    //$('#menuPeque').append('<li><a href="/private/listado-usuarios"><i class="icon wb-user"></i><span>Usuarios</span></a></li>');
			
		}	
	}
     
}

function opcionMenuClick(url){
	window.href(url);
}

function fechaString(date){
	if(!date || date == '0000-00-00')
		return '';
	year = date.substring(0, 4);
    mes = date.substring(5, 7);
    dia = date.substring(8, 10);
    if(year.indexOf("/") >= 0 || mes.indexOf("/") >= 0 || dia.indexOf("/") >= 0  )
    	return null;
    return dia + '/'+ mes + '/' + year;
}

function fechaStringEncode(date){
	if(!date)
		return null;
    year = date.substring(6, 10);
    mes = date.substring(3, 5);
    dia = date.substring(0, 2);
    return year + '/'+ mes + '/' + dia;
}

function generarUrl(accion){
	var url = '';
	if(location.hostname == 'localhost'){
		url = accion;
	}else{
		//cambiar por lo que toque
		url = '/public' + accion;
	}
	
	return url;
}

function obtenerDatos(){
 /*if(localStorage.getItem("src")){
        $('#imgHead').attr('src', localStorage.getItem("data.src"));
        crearMenu( localStorage.getItem("data.rolUsr"));
  }else{*/
     $.ajax({
      url:   generarUrl('/private/menuConfig'),
      type:  'GET',
      dataType: 'json',
      success:  function (data) {

          if(data){
            localStorage.setItem("data.rolUsr", data.usuario.rolusr);
            $('#alias').append(data.usuario.nomusr + ' - ' + data.empresa.nombre);
            crearMenu(data.listaRoles);
           
             return data;
          }
      }
     });
//  }
}
obtenerDatos();
$(document).ready(function($) {
  Site.run();
});

function mostrarDivNuevo(divMostrar){
	
	$('.'+divMostrar).removeClass('hide');
	$('.b'+divMostrar).addClass('hide');
	
}

function ocultarDivNuevo(divMostrar){
	
	$('.'+divMostrar).addClass('hide');
	$('.b'+divMostrar).removeClass('hide');
	$('#'+divMostrar+'Obs').val('');
	$('#'+divMostrar+'Des').val('');
	
}


function paginador(c, f, pagSelect, total, paginador){
	var tot_pag = Math.ceil(total/paginador);

    if(parseInt(pagSelect) <=3  || tot_pag <=5 ){
        var totFor = 5;
        if(tot_pag < 5)
            totFor = tot_pag;
        for (var i = 0; i < totFor; i++) {
            var sum = 1+i;
            if(sum == parseInt(pagSelect))
                c.append($('<span class="page-numbers current">'+sum+'</span>' ));
            else
                c.append($('<a class="page-numbers" href="#"'+
                    'onclick="javascript:'+f+'('+sum+')"'
                    +'>'+sum+'</a>' ));
        };
    }else if(tot_pag - parseInt(pagSelect) <= 2){
        var totFor = tot_pag - 5;
        
        for (var i = totFor; i < tot_pag; i++) {
            var sum = 1+i;
            if(sum == parseInt(pagSelect))
                c.append($('<span class="page-numbers current">'+sum+'</span>' ));
            else
                c.append($('<a class="page-numbers" href="#"'+
                    'onclick="javascript:'+f+'('+sum+')"'
                    +'>'+sum+'</a>' ));
        };
    }else{
        var v1 = parseInt(pagSelect)-2;
        var v2 = parseInt(pagSelect)-1;
        var v3 = parseInt(pagSelect);
        var v4 = parseInt(pagSelect)+1;
        var v5 = parseInt(pagSelect)+2;

        c.append($('<a class="page-numbers" href="#"'+
                    'onclick="javascript:'+f+'('+v1+')"'
                    +'>'+v1+'</a>' ));
        c.append($('<a class="page-numbers" href="#"'+
                    'onclick="javascript:'+f+'('+v2+')"'
                    +'>'+v2+'</a>' ));
        c.append($('<span class="page-numbers current">'+v3+'</span>' ));
        c.append($('<a class="page-numbers" href="#"'+
                    'onclick="javascript:'+f+'('+v4+')"'
                    +'>'+v4+'</a>' ));
         c.append($('<a class="page-numbers" href="#"'+
                    'onclick="javascript:'+f+'('+v5+')"'
                    +'>'+v5+'</a>' ));
    }
}

function convertirFormEnJson(arr, reqVal, arrExcep){
	var obReult = {};
	for(var i = 0; i < arr.length; i++){  if(reqVal == "S" && (!arrExcep || !arrExcep[arr[i].name]) && !arr[i].value)return{'err': 'Rellene todos los campos obligatorios'};  obReult[arr[i].name] = arr[i].value;}  
	return obReult;
}



