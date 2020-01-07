/**
 * gere le submit
 */

$(document).ready(function() {
    
	var new_donne = false;
	
    $("#form_nouvelle_donne").submit(function(e){
    	//on ne verifie que si c'est le boutton nouvelle donne qui est appeler
    	if(new_donne){
			var text = "";
			var submit = true;
			
		    	//on verifie que chaque joueur a un vent differents
		    	for (var i = 1; i <= 4; i++) {
		    	    if($("#ventJ" + i).parent().children("span").css("display") != "none"){
		    		text += "Le joueur " + i + " a un vent en commun avec un autre.<br/>";
		    		submit = false;
		    	    }
		    	}
			
			//text += "<br/>";
			
			//on clear les input visibles
			$("#tab_new_donne input").each(function( i ) {
			   if($(this).css("display") != "none"){
			       $(this).val("");
			   }  
			});
			
			gestionPanelInfo(text, "warning");
			new_donne = false;
			return submit;
    	}else {
    		return true;
    	}
    });
    
    $("#nouvelleDonneConfirmer").click(function(e){
    	new_donne = true;
    });
    
    /////////////////////////////////////////////////////////////////////////////
    //function annexe
    /////////////////////////////////////////////////////////////////////////////
    
    /**
     * @param string message le message a afficher
     * @param string severite parmis (info, danger, warning, success)
     */
    function gestionPanelInfo(message, severite){
	$("#panel-info-submit").removeClass().addClass("panel panel-" + severite);
	$("#panel-info-submit .panel-body").html(message);
    }
    
});