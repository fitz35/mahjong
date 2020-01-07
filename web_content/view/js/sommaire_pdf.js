/**
 * 
 */



$(document).ready(function() {
	$("#sommaire_pdf_regles .pdf-p").on("click.sommaire", function(e){
		$("#pdf-view").attr("src", "ressources/riichi-fr.pdf#page=" + $(this).val());
	});
	
});