var listTipoPruebaActuales = null;

function downloadPDF(i) {
    var exten = '';
    if(listaPruebas[i].tipo == 'PDF'){
    	exten = 'pdf';
    }else{
    	exten = 'png';
    }
    var newlink = document.createElement('a');
    newlink.setAttribute('href', 'data:application/octet-stream;base64,' + listaPruebas[i].archivo);
    newlink.setAttribute('download' ,'Fichero.'+exten);
    
    newlink.click();
}

function eliminarPrueba(idPrueba){
	swal({
      	title: "Alerta",
		text: 'Se eliminará la Prueba complementaria.¿Desea Continuar?',
		type: "warning",
		showCancelButton: false,
		confirmButtonClass: 'btn-danger',
		confirmButtonText: 'Eliminar',
		cancelButtonText: 'Cancelar',
        cancelButtonClass: 'btn-default',
        closeOnCancel: true,
        showCancelButton: true,
		closeOnConfirm: false
    },
    function(isConfirm) {
    	if (isConfirm) {
    		$('.loader-wrap').removeClass("hide");
    		$.ajax({
    			data: {	'id': idPrueba
    				  },
    	        url:   '/private/eliminarPruebaCompl',
    	        type:  'GET',
    	        dataType: 'json',
    	        success:  function (data) {
    	        	$('.loader-wrap').addClass("hide");
    	            if(data && data.msgErr){
    	            	swal("Error", data.msgErr, "error");
    	            }
    	            if(data && data.msgOk){
    	            	swal("Prueba Complementaria Eliminada", data.msgOk, "success");
    	            	prepararVentanaPrueCompl();
    	            }
    	        }
    	      });
    	}
    });
	

}


function prepararVentanaPrueCompl(){
	$('.loader-wrap').removeClass("hide");
	$.ajax({
		data: {'idpac': paciente.id},
        url:   '/private/prepararVentanaPrueCompl',
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
      	  $('.loader-wrap').addClass("hide");
	      	var tPru = $('#tablaPruebas').DataTable();
	      	tPru.clear().draw();
            if(data ){
            	
            	if(data.listTipoPrueba && !listTipoPruebaActuales){
                	listTipoPruebaActuales = data.listTipoPrueba;
            		for(var i = 0; i < data.listTipoPrueba.length; i++){
    					$('#tipPrueba').append(
    						$('<option>', {
    					    value: data.listTipoPrueba[i].coddom,
    					    text: data.listTipoPrueba[i].desval
    					}));
            		}
            	}
            	
            	listaPruebas = data.listaPruebas;
            	
            	for(var i = 0; i < data.listaPruebas.length; i++){
        			if(!data.listaPruebas[i].codalergia){
        				var pruebaAct = data.listaPruebas[i];
	           			var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">';
	                    listaBtn= listaBtn + '<span><a href="#" onclick="downloadPDF('+ i +');" title="Fichero GesmeWeb">Descargar</a></span>';
	                    listaBtn= listaBtn + '<span><button type="button" class="btn btn-icon btn-flat btn-default" data-toggle="tooltip" data-placement="top" data-trigger="hover" onclick="eliminarPrueba(\''+pruebaAct.id+'\')" data-original-title="Eliminar Prueba"><i class="icon wb-close text-danger" aria-hidden="true"></i></button></span>';
	                    listaBtn = listaBtn + '</div>';
	           			tPru.row.add( [
	           			               	  pruebaAct.desval ,
	                                      pruebaAct.observacion ,
	                                      fechaString(pruebaAct.created_at),
	                                      pruebaAct.tipo ,
	                                      listaBtn
	                                  ] ).draw( false );
        			}
        			
        		}
        		$('[data-toggle="tooltip"]').tooltip();
            	
	        
            }
            
            
        }
      });
}





var handleFileSelectFichero = function(evt){
	var filesToUpload = evt.target.files;
	var file = filesToUpload[0];
	
	
	 
	if(file.type.indexOf('image') >= 0){
		// Create una imagen
	    var img = document.createElement("img");
	   
	    var reader = new FileReader();
	    // tratamos la imagen en el loader
	    reader.onload = function(e){
	        img.src = e.target.result;
	        
	        var canvas = document.createElement("canvas");
	        //var canvas = $("<canvas>", {"id":"testing"})[0];
	        var ctx = canvas.getContext("2d");
	        ctx.drawImage(img, 0, 0);

	        var MAX_WIDTH = 400;
	        var MAX_HEIGHT = 300;
	        var width = img.width;
	        var height = img.height;

	        if (width > height) {
	          if (width > MAX_WIDTH) {
	            height *= MAX_WIDTH / width;
	            width = MAX_WIDTH;
	          }
	        } else {
	          if (height > MAX_HEIGHT) {
	            width *= MAX_HEIGHT / height;
	            height = MAX_HEIGHT;
	          }
	        }
	        canvas.width = width;
	        canvas.height = height;
	        var ctx = canvas.getContext("2d");
	        ctx.drawImage(img, 0, 0, width, height);

	        var dataurl = canvas.toDataURL("image/png");
	      //para abrirlos
	      //  window.open(dataurl);
	        $("#archB64").val(dataurl.replace('data:image/png;base64,', ''));     
	    }
	    reader.readAsDataURL(file);
	
    }else if(file.type.indexOf('pdf') >= 0){
    	var reader = new FileReader();

        reader.onload = function(readerEvt) {
            var binaryString = readerEvt.target.result;
            document.getElementById("archB64").value = btoa(binaryString);
           //para abrirlos
            //window.open("data:application/pdf;base64," + btoa(binaryString));
            $('.loader-wrap').addClass("hide");
        };

        reader.readAsBinaryString(file);
    }
   
}


if (window.File && window.FileReader && window.FileList && window.Blob) {
    document.getElementById('filePicker').addEventListener('change', handleFileSelectFichero, false);
}

function limpiarFormPrueba(){
	$('#filePicker').val('');
	$('#filePicker')[0] = [];
	$('#tipPrueba').val('');
}

function guardarPruebaComplem(){
	var reader = new FileReader(),
    file = $('#filePicker')[0];
	if (!file.files.length) {
	    swal("Prueba Complementaria", 'Debe introducir un fichero', "error");
	    return false;
	}
	
	if(!$('#tipPrueba').val()){
	    swal("Prueba Complementaria", 'Seleccione un tipo', "error");
	    return false;
	}
	
	if(!$('#divContPruObs').val()){
	    swal("Prueba Complementaria", 'La observación es obligatoria', "error");
	    return false;
	}
	
	var extension = $('#filePicker').val().substr($('#filePicker').val().lastIndexOf('.')+1);
	
	if(extension.toUpperCase() == "PNG" || extension.toUpperCase() == "JPG"){
		extension = 'IMG';
	}else if(extension.toUpperCase() == 'PDF')
		extension = extension.toUpperCase();
	else{
		
		return false;
	}
	
	$.ajax({
		data: {'tipo' : extension, 
			'archivo' : $('#archB64').val(),
			'idpac': paciente.id,
			'tipprueba' : $('#tipPrueba').val(),
			'observacion' :$('#divContPruObs').val().replace(/["']/g, "&#34;")},
        url:   '/private/guardarPruebaComplem',
        type:  'POST',
        dataType: 'json',
        success:  function (data) {
        	$('.loader-wrap').addClass("hide");
        	if(data && data.msgOk){
        		ocultarDivNuevo('divContPru');
        		limpiarFormPrueba();
        		prepararVentanaPrueCompl();
        		swal("Prueba Complementaria", data.msgOk, "success");
        		
        	}
        }
	});
}