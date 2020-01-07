/**
 * gere les area des pieces de mahjong sur la photo
 */

$(document).ready(function() {
    const url_image = document.location.href.substring( 0 ,document.location.href.lastIndexOf( "/" ) + 1) + "ressources/images/indiv/";
    
    //taille initiale pour les coordonnées de l'image
    const IMG_WIDTH_INIT = 700;
    const IMG_HEIGHT_INIT = 613;
    //on recupère la taille de l'image
    const img_width_reel = $("#img_mahjong_piece_map").width();
    const img_height_reel = $("#img_mahjong_piece_map").height();
    
    var tab_title = new Object();
    
    //on adapte les coordonnées des areas
    $('#map_pieces_majong>area').each(function (i){
		var coord = $(this).attr("coords").split(",");
		var new_coord = [];
		
		//les x sont paire, y sont impaires
		for(var i = 0 ; i < coord.length ; i++){
		    if(i % 2 == 0){
		    	new_coord[i] = coord[i] * img_width_reel / IMG_WIDTH_INIT;
		    }else{
		    	new_coord[i] = coord[i] * img_height_reel / IMG_HEIGHT_INIT;
		    }
		}
		$(this).attr("coords", new_coord.join(","));
		//on stocke le title
		tab_title[$(this).attr('id')] = $(this).attr('title');
	    
    });
    
    //separateur des id
    const separateur_id = "-";
    
    //variable pour stocker les event
    var id_area = "";  //la souris sur les area de la foto
    var id_combi = ""; //la souris click sur les case du tableau pour sélectionner l'endroit a remplir
    
    buildTableauEvent();
    
    
    //on setup les listener pour la notif
    builtAreaEvent();
    //buildTrEvent();
    
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //build des evenements
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    function builtAreaEvent(){
	 $('#map_pieces_majong>area').on("mouseover.area", function(e) {
	            // Récupérer la valeur de l'attribut title et l'assigner à une variable
	     	    id_area = $(this).attr("id"); 
	            // Supprimer la valeur de l'attribut title pour éviter l'infobulle native
	            $(this).attr('title','');
	            
	            // Insérer notre infobulle avec son texte dans la page => en verifiant les statut de requete
	            
	            const piece = $(this).attr("id").split(":")[1];
	            var type = "success";
	            var text = "";
	            if(id_combi != ""){
		            const etat = getEtatFromId(id_combi);
		            if(!isPiece(piece, etat)){
		            	type = "warning";
		            	text = "La piece est sois déjà utilisée trop de fois, soit pas dans la bonne combinaison.";
		            }else if (!isPieceValideInCombi(piece, id_combi)){
		            	type = "warning";
		            	text = "La piece n'est pas valide dans cette combinaison.";
		            }
	            }
	            $(document.body).append(genTooltip(text , tab_title[id_area], type));
	            
	            
	         // Ajuster les coordonnées de l'infobulle
	            $('#tooltip').css('top', e.pageY + 10 );
	            //on test si elle n'est pas sortie de l'ecran :
	            const largeur_fenetre = $(window).width();
	            if($("#tooltip").width() + e.pageX > largeur_fenetre){
	            	$('#tooltip').css('left', e.pageX - $("#tooltip").width() - 20 );
	            }else{
	            	$('#tooltip').css('left', e.pageX + 20 );
	            }
	    }).on("mousemove.area", function(e) {
		    //on verifie que l'element existe sinon on trigger l'event
		    if (!document.getElementById("tooltip")){
		    	$(this).triggerHandler("mouseover.map_visio");
		    }
		    // Ajuster la position de l'infobulle au déplacement de la souris
	            $('#tooltip').css('top', e.pageY + 10 );
	            const largeur_fenetre = $(window).width();
	            if($("#tooltip").width() + e.pageX > largeur_fenetre){
	            	$('#tooltip').css('left', e.pageX - $("#tooltip").width() - 20 );
	            }else{
	            	$('#tooltip').css('left', e.pageX + 20 );
	            }
		
	    }).on("mouseout.area", function() {
	    	
	            // Réaffecter la valeur de l'attribut title
	            $(this).attr('title', tab_title[id_area]);
	     
	            // Supprimer notre infobulle
	            $('#tooltip').remove();
	    }).on("click.area.addPiece",function(e){
	    	if(id_combi != ""){
			    var img_sym = $(this).attr("id").split(":")[1];
			    var input = $("#" + id_combi + " .last");
			    
			    input.val(img_sym);
			    convertToImage(input);
			}
	    });
	    
   }
    
    /*function buildTrEvent(){
    	 $('#tab_new_donne .tab_new_donne_body').on("mouseover.infoJ", function(e) {
	            // Récupérer l'id du joueur
    		 	
	     	    id_joueur = $(this).attr("id").split("-")[1];
	            
	            // Insérer notre infobulle avec son texte dans la page => en verifiant les statut de requete
	            $(document.body).append('<div id="tooltip">' + $(".nomJoueur" + id_joueur).first().text() + '</div>');
	            
	            
	         // Ajuster les coordonnées de l'infobulle
	            $('#tooltip').css('top', e.pageY + 10 );
	            $('#tooltip').css('left', e.pageX + 20 );
	    }).on("mousemove.infoJ", function(e) {
		    //on verifie que l'element existe sinon on trigger l'event
		    if (!document.getElementById("tooltip")){
		    	$(this).triggerHandler("mouseover.map_visio");
		    }
		    // Ajuster la position de l'infobulle au déplacement de la souris
	            $('#tooltip').css('top', e.pageY + 10 );
	            $('#tooltip').css('left', e.pageX + 20 );
		
	    }).on("mouseout.infoJ", function() {
	            // Supprimer notre infobulle
	            $('#tooltip').remove();
	    });
    }*/
    
    
    function buildTableauEvent (){
		$("#tab_new_donne").on("click.combinaison", ".combinaison", function(e){
		    //on met le fond en bleu
		    if($(this).parent("td").attr("id").match(/tab_new_donne_nom_joueurs[1-4]/g) == null){
		    	if(id_combi != ""){
	    		     $("#tab_new_donne .combinaison").removeClass('highlight');
	    	    }
	        	$(this).addClass('highlight');
	        	id_combi = $(this).attr("id");
	        	//on focus
	        	getFocusOnLast(id_combi);
		    }
		    //a chaque fois que les input se remplisse, on essaye de le convertir en image de piece
		}).on("input.inputNewDonne", ".input_piece_new_donne>.last", function(e){
		    
		    convertToImage($(this));
		    //on s'occupe des boutton remove
		}).on("click.remove", ".supprPiece", function(e){
		    deleteBeforeLastInputDiv($(this));
		}).on("click.removeCombi", ".supprCombi", function(e){
			deleteLastCombi($(this));
		}).on("click.addCombi", ".addCombi", function(e){
			addCombiInTd($(this));
		});
    }
    
//////////////////////////////////////////////////////////////////////////////////////////////////
    //fonction d'ajout/suppression d'élément
    //////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @param elt l'element input dans lequel on essaye de crer une image 
     * @ajoute un span et transforme l'input en image si il existe
     */
	function convertToImage(elt) {
		elt.val(elt.val().toUpperCase());
		if (isPiece(elt.val(), getEtatFromId(elt.attr("id")))  && isPieceValideInCombi(elt.val(), id_combi)) {//on verifie qu'elle est valide et dans la combi
			addPieceInputInCombi(elt);
			switchImageInput(elt, elt.val());
			getFocusOnLast(id_combi);
		}
	}
	
	/**
	 * @param elt le bouton dans lequel l'event est trigger : 
	 */
	function addCombiInTd(elt){
		var combinaison = elt.parents("td.tab_new_donne-combi").children(".combinaison.last");
		//on commence par clone et modifier les id
		var clone = $("#combiClone").clone();
		
		const id_init = [
				getJoueurFromId(combinaison.attr("id")), 
				getCombiFromId(combinaison.attr("id")) * 1 + 1, 
				getEtatFromId(combinaison.attr("id")), 
				1
			];
		
		const id_combi = genIdCombinaison(id_init[0], id_init[1], id_init[2]);
		const id_input = genIdInput(id_init[0], id_init[1], id_init[2], id_init[3]);
		
		combinaison.removeClass("last");//ce n'est plus le dernier
		
		clone.removeAttr( 'style' );//on le remet visible
		clone.attr("id", id_combi);
		clone.find("input").attr({"id" : id_input, "name" : id_input}).val("");
		
		clone.insertAfter("#" + genIdCombinaison(id_init[0], id_init[1]*1 - 1, id_init[2]));
	}
	
	/**
	 * @param elt l'element input qui genere cette réaction
	 * @note clone span input_piece_new_donne
	 */
	function addPieceInputInCombi(elt){
		var clone = $("#inputClone").clone();
		elt.removeClass("last");//on est plus le last
		var id_init = [
							getJoueurFromId(elt.attr("id")), 
							getCombiFromId(elt.attr("id")), 
							getEtatFromId(elt.attr("id")), 
							getPieceFromId(elt.attr("id"))*1 + 1
					];
		const id_input = genIdInput(id_init[0], id_init[1], id_init[2], id_init[3]);
		//on remplace les attribut
		clone.find("input").attr({"id" : id_input, "name" : id_input}).val("");
		clone.removeAttr("id");
		//on colle
		clone.appendTo("#" + genIdCombinaison(id_init[0], id_init[1], id_init[2]));
	}
	
	/**
	 * @param elt l'element input qui a provoquer la raiponse
	 * @param image le nom de l'image
	 * @note switch l'input et l'image
	 */
	function switchImageInput(elt, image){
		elt.attr("hidden", "hidden").removeClass("form-control last");//on enleve la class form control (qui passe par dessus lehidden)
		var parent = elt.parent(".input_piece_new_donne");
		parent.addClass("input_piece_new_donne_with_image");//on change la classe pour s'adapter a l'image
		parent.children("button").remove();
		parent.children("img").removeAttr("hidden").attr({"src" : url_image + image + ".png", "alt" : image}).addClass("img" + image);
	}
	
	
	/**
	 * @param string text le text a l'interieurs
	 * @param titre le titre du panel
	 * @param type parmis primary, success, warning, danger
	 * @return string le panel
	 */
	function genTooltip (text, titre, type){
		var retour = '<div id="tooltip" class="panel panel-' + type + '">' + 
					'<div class="panel-heading">' + 
						'<h3 class="panel-title">' + titre + '</h3>' + 
					'</div>';
		if(text != ""){
			retour += '<div class="panel-body">' + text + '</div>';
		}
				
		return	retour + '</div>';
	}
	
	
	
	
	
	
	
	/**
	 * @param elt le button qui a declencher l'action
	 */
	function deleteBeforeLastInputDiv(elt){
		var combi = elt.parents(".combinaison");
		var span_input = combi.children(".input_piece_new_donne");
		
		if(span_input.eq(-2).length == 1){
			span_input.eq(-2).detach();
			span_input = combi.children(".input_piece_new_donne");
		    //on modifie l'id de l'input d'après
		    const id_init = [
					getJoueurFromId(combi.attr("id")), 
					getCombiFromId(combi.attr("id")), 
					getEtatFromId(combi.attr("id"))
				];
		    const id_combi = genIdCombinaison(id_init[0], id_init[1], id_init[2]);
		    var input = $("#" + id_combi + " .last");
		    const id_input = genIdInput(id_init[0], id_init[1], id_init[2], getPieceFromId(input.attr("id")) * 1 - 1);
		    
		    input.attr({"id": id_input, "name" : id_input});//.last().children("input")
		}
	}
	
	/**
	 * @param elt le bouton qui a generer l'event
	 */
	function deleteLastCombi (elt){
		var combinaison = elt.parents("td.tab_new_donne-combi").children(".combinaison");
		
		if(combinaison.length > 1){
			combinaison.last().remove();
			combinaison.eq(-2).addClass("last");
		}
		
	}
	
	
    //////////////////////////////////////////////////////////////////////////////////////////////////
    //autre fonction 
    //////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @param string piece
     * @param td l'etat de la piece
     * @return si piece est une piece de mahjong valide
     */
    function isPiece (piece, td){
		//on verifie qu'elle existe, puis on compte le nombre de piece deja afficher
		if(document.getElementById("areaP:" + piece)){
		    const nb = piece.charAt(1) == "S" || piece.charAt(1) == "F" ? 1 : 4;
		    
		    if((nb == 1 && td != "pieceBonus") || (nb != 1 && td == "pieceBonus")){
		    	return false;
		    }else{
		    	return $(".img" + piece).length <nb;
		    }
		}else{
		    return false;
		}
    }
    
    /**
     * @param string piece la piece
     * @param string id_combi l'id de la combinaisons a tester
     * @return boolean si la piece est valide dans la combinaisons
     */
    function isPieceValideInCombi(piece, id_combi){
    	var piece_in_combi = [];
    	var i = 0;
    	$("#" + id_combi + " img").each(function(index){
    		if($(this).attr('alt') != ""){
    			piece_in_combi[i] = $(this).attr('alt');
    			i++;
    		}
    	});
    	
    	//si aucune piece, elle est forcément valide
    	if(i >= 1 ){
    		//sinon si plus de 4 piece elle est forcement invalide:
    		if(i >= 4){
    			return false;
    		}else{
    		
	    		const famille = piece_in_combi[0].charAt(1);
	    		//si c'est pas la meme famille, elle n'est forcement pas valide
	    		if(famille != piece.charAt(1)){
	    			return false;
	    		}else{//si c'est des honneurs, ils suffisent qu'ils soient dans la meme famille
	    			if(piece.charAt(1) == "S" || piece.charAt(1) == "F"){
    					return true;
    				}else{
		    			//si c'est une suite de trois piece
		    			if(i <= 2 && (piece.charAt(1) == "C" || piece.charAt(1) == "R" || piece.charAt(1) == "C") && 
		    					isSequence([piece].concat(piece_in_combi))){
		    				return true;
		    			}else{//on test les brelans
		    				if(isPareil([piece].concat(piece_in_combi))){
			    				return true;
			    			}else{
			    				return false;
			    			}
		    			}
    				}
	    		}
    		}
    	}else{
    		return true;
    	}
    }
    
    /**
     * @param array un tableau de piece telle que "1C"
     * @return true si les nombres dans le tableau sont une suite de nombre valide
     */
    function isSequence(array){
    	array.sort(function(a, b){
    		a =a.charAt(0) * 1;
    		b = b.charAt(0)*1;
    		
    		if(a == b){
    			return 0;
    		}else if(a<b){
    			return -1;
    		}else{
    			return 1;
    		}
    	});
    	
    	for(var i = 1 ; i < array.length ; i ++){
    		if(array[i].charAt(0) * 1 - array[i-1].charAt(0) * 1 != 1){
    			return false;
    		}
    	}
    	return true;
    }
    
    /**
     * @param array un tableau
     * @return true si tout les element du tableau sont identique
     */
    function isPareil(array){
    	for(var i = 1 ; i < array.length ; i ++){
    		if(array[i]  != array[i-1]){
    			return false;
    		}
    	}
    	return true;
    }
    
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    //id
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @param joueur string le joueur
     * @param combinaison le numéro de combinaison
     * @param etat l'etat des piece (pieceDecou, pieceCache, pieceBonus)
     * @return string l'id de la combinaison corespondante sans #
     */
    function genIdCombinaison(joueur, combinaison, etat) {
    	return "c_combi" + separateur_id + combinaison + separateur_id + "joueur" + separateur_id 
    				+ joueur + separateur_id + "etat" + separateur_id + etat;
    }
    
    
    /**
     * @param joueur string le joueur
     * @param combinaison le numéro de combinaison
     * @param etat l'etat des piece (pieceDecou, pieceCache, pieceBonus)
     * @param le numero de la piece dans la combinaison
     * @return string l'id de la piece (input) corespondante sans #
     */
    function genIdInput(joueur, combinaison, etat, piece) {
    	return "input_joueur" + separateur_id + joueur + separateur_id + "combi" + separateur_id + combinaison + 
    				separateur_id + etat + separateur_id + piece;
    }
    
    /**
     * @param id a tester
     * @return l'id du joueur
     */
    function getJoueurFromId(id){
    	var split = id.split(separateur_id);
    	if(split[0] == "c_combi"){
    		return split[3];
    	}else if(split[0] == "input_joueur"){
    		return split[1];
    	}else{
    		console.log("getJoueurFromId/id pas reconue : " + id);
    		return 0;
    	}
    }
    
    /**
     * @param id a tester
     * @return l'id de la combinaison
     */
    function getCombiFromId(id){
    	var split = id.split(separateur_id);
    	if(split[0] == "c_combi"){
    		return split[1];
    	}else if(split[0] == "input_joueur"){
    		return split[3];
    	}else{
    		console.log("getCombiFromId/id pas reconue : " + id);
    		return 0;
    	}
    }
    
    /**
     * @param id a tester
     * @return l'id de l'état
     */
    function getEtatFromId(id){
    	var split = id.split(separateur_id);
    	if(split[0] == "c_combi"){
    		return split[5];
    	}else if(split[0] == "input_joueur"){
    		return split[4];
    	}else{
    		console.log("getEtatFromId/id pas reconue : " + id);
    		return 0;
    	}
    }
    
    /**
     * @param id a tester
     * @return l'id de la piece
     */
    function getPieceFromId(id){
    	var split = id.split(separateur_id);
    	if(split[0] == "input_joueur"){
    		return split[5];
    	}else{
    		console.log("getPieceFromId/id pas reconue : " + id);
    		return 0;
    	}
    }
    
    
    
    function getFocusOnLast(id){
    	$("#" + id + " .last").focus();
    }
});