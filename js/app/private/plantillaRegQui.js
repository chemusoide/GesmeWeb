$(document).ready(function(){
      
	$('.editableTable').editableTableWidget();
	
	$('#editableTable').editableTableWidget({
		cloneProperties: ['background', 'border', 'outline']
	});
	
	
	if(actquiActual){
		actquiActual = actquiActual[0];
		$('#nomPacCompleto').append(actquiActual.nompac + ' ' + actquiActual.ap1pac + ' ' + actquiActual.ap2pac);
	}
	
    
});