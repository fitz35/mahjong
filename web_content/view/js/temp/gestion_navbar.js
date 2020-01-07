/**
 * 
 */

$(document).ready(function() {
    
    $("#button-nouvelle-partie").on("click.nouvelle_partie", function(e){
		if(window.confirm('Êtes-vous sûr de vouloir commencer une autre partie ? Ceci écrasera celle en cours définitivement.')){
		    eraseCookie("mahjong_partiejoueurs");
		    const manche = $("#tableau_score .tr-pas-total").length;
		    for(var i = 1 ; i <= manche ; i ++){
			eraseCookie("mahjong_partiescores" + i);
		    }
		    
		    window.location = window.location.href.substring(0, window.location.href.lastIndexOf( "/" ) + 1 ) + "index.php";
		}
    });
	    
	$("#button-define-scoring-rule").on("click.define", function(e){
		$("#definition-scoring-rules").slideToggle("slow");
    });
	
	//on cache la bar si on ne la modifie pas :
	if($("#modifConstante").length == 1){
		$("#definition-scoring-rules").hide();
	}
    
    
    /////////////////////////////////////////////////////////////////////////////////
    //autre fonction
    ////////////////////////////////////////////////////////////////////////////////
    function eraseCookie(name) {
	var date = new Date();
	date.setTime(date.getTime());
	var expires = "; expires="+date.toGMTString();
	    
	document.cookie = name+"="+expires;
    }
});