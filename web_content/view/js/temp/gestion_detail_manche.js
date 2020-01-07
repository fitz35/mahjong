/**
 * 
 */

$(document).ready(function() {
    var tab_detail_manche_display = false;
    $("#tab_detail_manche").hide();
    
    $("#annonce_detail_manche").on("click.display_detail", function(e) {
	if(tab_detail_manche_display){
    		rotate(0, $(this).children(".glyphicon"));
    	
    		$("#tab_detail_manche").slideToggle("slow");
	}else{
	    rotate(90, $(this).children(".glyphicon"));
	    	
	    $("#tab_detail_manche").slideToggle("slow");
	    window.location.href = "#tab_detail_manche";
	}
    	
	tab_detail_manche_display = !tab_detail_manche_display;
    });

    // /////////////////////////////////////////////////////////////////////////////////////////////////
    function rotate(degre, elt) {
	    elt.css({
		'-webkit-transform' : 'rotate(' + degre + 'deg)',
		'-moz-transform' : 'rotate(' + degre + 'deg)',
		'-ms-transform' : 'rotate(' + degre + 'deg)',
		'-o-transform' : 'rotate(' + degre + 'deg)',
		'transform' : 'rotate(' + degre + 'deg)',
		'zoom' : 1
	    });
    }

});