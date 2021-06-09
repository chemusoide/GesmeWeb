$(document).ready(function(){
	initFormaDocDetalle();
	$('#simple_sketch').sketch();
});

var posArr = 0;
var iDocActual = null;
var emptyFirma = "iVBORw0KGgoAAAANSUhEUgAAASwAAACWCAYAAABkW7XSAAAEYklEQVR4Xu3UAQkAAAwCwdm/9HI83BLIOdw5AgQIRAQWySkmAQIEzmB5AgIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlAABg+UHCBDICBisTFWCEiBgsPwAAQIZAYOVqUpQAgQMlh8gQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEDBYfoAAgYyAwcpUJSgBAgbLDxAgkBEwWJmqBCVAwGD5AQIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlAABg+UHCBDICBisTFWCEiBgsPwAAQIZAYOVqUpQAgQMlh8gQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEDBYfoAAgYyAwcpUJSgBAgbLDxAgkBEwWJmqBCVAwGD5AQIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlAABg+UHCBDICBisTFWCEiBgsPwAAQIZAYOVqUpQAgQMlh8gQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEDBYfoAAgYyAwcpUJSgBAgbLDxAgkBEwWJmqBCVAwGD5AQIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlAABg+UHCBDICBisTFWCEiBgsPwAAQIZAYOVqUpQAgQMlh8gQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEDBYfoAAgYyAwcpUJSgBAgbLDxAgkBEwWJmqBCVAwGD5AQIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlAABg+UHCBDICBisTFWCEiBgsPwAAQIZAYOVqUpQAgQMlh8gQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEDBYfoAAgYyAwcpUJSgBAgbLDxAgkBEwWJmqBCVAwGD5AQIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlAABg+UHCBDICBisTFWCEiBgsPwAAQIZAYOVqUpQAgQMlh8gQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEDBYfoAAgYyAwcpUJSgBAgbLDxAgkBEwWJmqBCVAwGD5AQIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlAABg+UHCBDICBisTFWCEiBgsPwAAQIZAYOVqUpQAgQMlh8gQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEDBYfoAAgYyAwcpUJSgBAgbLDxAgkBEwWJmqBCVAwGD5AQIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlAABg+UHCBDICBisTFWCEiBgsPwAAQIZAYOVqUpQAgQMlh8gQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEDBYfoAAgYyAwcpUJSgBAgbLDxAgkBEwWJmqBCVAwGD5AQIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlAABg+UHCBDICBisTFWCEiBgsPwAAQIZAYOVqUpQAgQMlh8gQCAjYLAyVQlKgIDB8gMECGQEDFamKkEJEDBYfoAAgYyAwcpUJSgBAgbLDxAgkBEwWJmqBCVAwGD5AQIEMgIGK1OVoAQIGCw/QIBARsBgZaoSlACBB1YxAJfjJb2jAAAAAElFTkSuQmCC";

function obtenerDocAFirmar(){
	iDocActual = null;
	if(listIdFirma[posArr]){
		$('.loader-wrap').removeClass("hide");
		
		$.ajax({
	      url:   generarUrl('/firma/obtenerDocAFirmar'),
	      data: {'id' : listIdFirma[posArr].id},
	      type:  'GET',
	      dataType: 'json',
	      success:  function (data) {
	    	  $('.loader-wrap').addClass("hide");
	    	  if(data && data.infoDoc){
	    		  $('#subMsg').html('Documento '+ (posArr+ 1) + ' de ' + listIdFirma.length);
	    		  
	    		  iDocActual = data.infoDoc;
	    		  $('.contenidoDoc').empty();
				  var contHtml = iDocActual.stringdoc.replace('@@FIRMA@@', '');
				  contHtml = contHtml.replace('@@FIRMA_MEDICO@@', '');
				  contHtml = contHtml.replace('@@FIRMA_NO@@', '');
	    		  $('.contenidoDoc').append(contHtml);
	    		  
	    		  document.getElementById('selOptSi').checked = false;
	    		  document.getElementById('selOptNo').checked = false;
	    		  if(iDocActual.swiactrev && iDocActual.swiactrev == 'S')
	    			  $('#divAcpRev').removeClass('hide');
	    		  else
	    			  $('#divAcpRev').addClass('hide');
	    		  
	    		  
	    	  }
	      }
		});
	}else{
		 $('.contenidoDoc').empty();
		 $('.contenidoDoc').append('<p>Ha firmado todos los documentos Gracias</p>');
		 $('#simple_sketch').addClass('hide');
		 $('#btnGuarda').addClass('hide');
		 setTimeout(function () {
			 window.location.href = generarUrl('/private/firma-documentos'); 
		  }, 5000);
	}
	
}

function guardarDocFirmado(){
	
	var selectedVal = null;
	if(iDocActual.swiactrev && iDocActual.swiactrev == 'S'){
		var selected = $("input[type='radio'][name='acp']:checked");
		if (selected.length > 0) {
		    selectedVal = selected.val();
		}else{
			swal("Error", 'Debe Seleccionar si acepta o revoca', "error");
			return false;
		}
	}	
	
	if(emptyFirma == document.getElementById("simple_sketch").toDataURL().replace('data:image/png;base64,', '')){
		swal("Error", 'Debe firmar el documento para continuar', "error");
		return false;
	}
	$('.loader-wrap').removeClass("hide");
	
	$.ajax({
      url:   generarUrl('/firma/guardarDocFirmado'),
      data: {'id' : listIdFirma[posArr].id, 
    	  'firma' : document.getElementById("simple_sketch").toDataURL().replace('data:image/png;base64,', ''),
    	  'tipFirma' : tipFirma,
    	  'actrev': selectedVal},
    	  
      type:  'POST',
      dataType: 'json',
      success:  function (data) {
    	  $('.loader-wrap').addClass("hide");
    	  if(data && data.msgOk){ 
    		  $('.contenidoDoc').empty();
    		  var canvas = document.getElementById('simple_sketch');  
    		  canvas.getContext('2d').clearRect(0,0,1920,2000);
    		  $('#simple_sketch').sketch('actions',[]);
    		  posArr++;
    		  obtenerDocAFirmar();
    	  }
      }
	});
}

function initFormaDocDetalle(){
		
		$('#titMsg').html('Documentos pendientes de firmar');
		
		obtenerDocAFirmar();
	
}


