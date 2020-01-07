/**
 * 
 */

$(document).ready(function() {
	//au changement dans les input pour les noms on change dans les options et tableau.
	$("#input_nom_joueurs input").on("input.changeNomJoueurs", function(e){
		var text = $(this).val();
		const numero = $(this).attr("id").split("nomJ")[1];
		
		if(text == ""){
			text = $(this).parent().children("label").text();
		}
		$(".nomJoueur" + numero).text(text);
	});
	//on le trigger au chargement
	$("#input_nom_joueurs input").trigger("input.changeNomJoueurs");
	
	
	
	//au select des vent on verifie qu'il n'est pas deja utiliser (on le fait aussi au chargement de la page)
	selectVentJInfo();
	$("#input_nom_joueurs select").on("change.ventJ", function(e){
		selectVentJInfo();
	});
	
	//au cic sur le bouton pour le hasard des vents dominants
	$("#dominantHasard").on("click.dominantHasard", function(e){
		//on desactive les option et on active celle qui faut
		//vent
		const vent = randomInt(1, 4);
		
		$("#ventDominant option").removeAttr("selected");
		$("#ventDominant option[value=\"" + vent + "\"]").attr("selected", "");
	});
	
	//pareil avec les vents des joueurs :
	$("#ventJHasard").on("click.ventjHasard", function(e){
		var tab = [1, 2, 3, 4];
		
		for(var i = 1 ; i <= 4 ; i++){
		    	const index = randomInt(0, tab.length - 1);
			$("#ventJ" + i + " option[value=\"" + tab[index] + "\"]").prop('selected', true);
			
			tab.splice(index, 1);
		}
		
		selectVentJInfo();
	});
	
	//sur le boutton de déplacement, on fait tourner les vents :
	$("#ventJDecalage").on("click.ventJDecalage", function(e){
		for( var i = 1 ; i <= 4 ; i++){
			var vent = $("#ventJ" + i).val();
			if(vent == 4){
				vent = 1;
			}else{
				vent++;
			}
			$("#ventJ" + i + " option[value=\"" + vent + "\"]").prop('selected', true);
		}
		
		
	});
	
	
	
	//au chargement, on tire le vent dominant au hasard si on est pas sur un retour :
	if(document.getElementById("button_retour_activer_pas_vent_d_hasard") == undefined){
		$("#dominantHasard").trigger("click.dominantHasard");
	}
	
	
	
	
	
//////////////////////////////////////////////////////////////////////////////////////////////////
    //autre fonction 
    //////////////////////////////////////////////////////////////////////////////////////////////////
	/**
	 * affiche/desafiche les infos des choix de vents
	 */
	function selectVentJInfo(){
		$("#input_nom_joueurs option:selected").each(function(e){
			var parent = $(this).parents(".selectVentJAlert");
			if(isMoreOneVent($(this).attr("value"))){
				//on change les infos :
				
				parent.addClass("has-error has-feedback");
				parent.children("span").removeAttr("style");
			}else{
				//on change les infos :
				parent.removeClass("has-error has-feedback");
				parent.children("span").css("display", "none");
			}
		});
	}
	
	/**
	 * @param vent le vent
	 * @return si le vent est deja utilisé
	 */
	function isMoreOneVent(vent){
		return $("#input_nom_joueurs option[value=\"" + vent + "\"]:selected").length >1;
	}
	
	function randomInt(min, max){
		return Math.floor(Math.random() * Math.floor(max - min + 1)) + min;
	}

});