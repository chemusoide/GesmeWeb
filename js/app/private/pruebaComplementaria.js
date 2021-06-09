var listTipoPruebaActuales = null;

function downloadPDF(i) {
    var exten = '';
    if(listaPruebas[i].tipo == 'PDF'){
    	exten = 'pdf';
    }else{
    	exten = 'png';
    }
    var newlink = document.createElement('a');
	newlink.href = 'data:application/octet-stream;base64,' + listaPruebas[i].archivo;
	newlink.download ='Fichero.'+exten;
	document.body.appendChild(newlink);
	newlink.click();
	document.body.removeChild(newlink);
    
	/*newlink.setAttribute('href', 'data:application/octet-stream;base64,' + listaPruebas[i].archivo);
    newlink.setAttribute('download' ,'Fichero.'+exten);
    
    newlink.click();*/
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
    	        url:   generarUrl('/private/eliminarPruebaCompl'),
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


function prepararVentanaPrueCompl(idactqui){
	if($('#actQui')){
		$('#actQui').empty();
		$('#actQui').append(
				$('<option>', {
			    value: '',
			    text: 'Acto quirurjico'
			}));
	}
	
	$('.loader-wrap').removeClass("hide");
	console.info(window.location.href.indexOf("gestion_cita"));
	$.ajax({
		data: {'idpac': paciente.id,
				'swiEncryp' : window.location.href.indexOf("gestion_cita")>=0 || idactqui?true:false,
				'idvisita' :typeof visitaMod != 'undefined' && visitaMod?visitaMod.id:'',
				'idactqui' :typeof idactqui != 'undefined' && idactqui?idactqui:''
				},
		url:   generarUrl('/private/prepararVentanaPrueCompl'),
        type:  'GET',
        dataType: 'json',
        success:  function (data) {
      	  $('.loader-wrap').addClass("hide");
	      	var tPru = $('#tablaPruebas').DataTable();
	      	tPru.clear().draw();
            if(data ){
            	
            	if($('#tipPrueba') && data.listTipoPrueba && !listTipoPruebaActuales){
                	listTipoPruebaActuales = data.listTipoPrueba;
            		for(var i = 0; i < data.listTipoPrueba.length; i++){
    					$('#tipPrueba').append(
    						$('<option>', {
    					    value: data.listTipoPrueba[i].coddom,
    					    text: data.listTipoPrueba[i].desval
    					}));
            		}
            	}
            	
            	if($('#actQui') && data.listaActQui){
            		for(var i = 0; i < data.listaActQui.length; i++){
    					$('#actQui').append(
    						$('<option>', {
    					    value: data.listaActQui[i].id,
    					    text: data.listaActQui[i].id + ' - ' + data.listaActQui[i].fecint
    					}));
            		}
            	}
            	
            	listaPruebas = data.listaPruebas;
            	
            	for(var i = 0; i < data.listaPruebas.length; i++){
        			if(!data.listaPruebas[i].codalergia){
        				var pruebaAct = data.listaPruebas[i];
	           			var listaBtn ='<div class="btn-group" aria-label="Acciones Usuario" role="group">';
	                    listaBtn= listaBtn +'<span data-toggle="tooltip" data-placement="top" data-trigger="hover" data-original-title="Descargar"><button type="button" onclick="downloadPDF('+i+')" class="btn btn-warning btn-icon waves-effect waves-light" ><i class="icon wb-download " aria-hidden="true"></i> Descargar</button></span>';
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
	    	
	    	img.onload = function() {
	            // access image size here 
	    		var canvas = document.createElement("canvas");
		        //var canvas = $("<canvas>", {"id":"testing"})[0];
		        var ctx = canvas.getContext("2d");
		        ctx.drawImage(img, 0, 0);

		        var MAX_WIDTH = 400;
		        var MAX_HEIGHT = 300;
		        var width = this.width;
		        var height = this.height;

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
		        ctx.drawImage(this, 0, 0, width, height);

		        var dataurl = canvas.toDataURL("image/png");
		      //para abrirlos
		      //  window.open(dataurl);
		        $("#archB64").val(dataurl.replace('data:image/png;base64,', ''));   
	        };
	        img.src = e.target.result;
	          
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
	if(document.getElementById('filePicker'))
		document.getElementById('filePicker').addEventListener('change', handleFileSelectFichero, false);
}

function limpiarFormPrueba(){
	$('#filePicker').val('');
	$('#filePicker')[0] = [];
	$('#tipPrueba').val('');
	$('#actQui').val('');
	$('#actQui').empty();
	
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
			'idactqui' : $('#actQui').val(),
			'observacion' :$('#divContPruObs').val().replace(/["']/g, "&#34;"),
			'swiEncryp' : window.location.href.indexOf("gestion_cita")>=0?true:false,
			'idvisita' :typeof visitaMod != 'undefined' && visitaMod?visitaMod.id:''},
        url:   generarUrl('/private/guardarPruebaComplem'),
        type:  'POST',
        dataType: 'json',
        success:  function (data) {
        	console.info(data);
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