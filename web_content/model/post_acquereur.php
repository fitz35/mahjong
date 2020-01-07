<?php
//si on a appuyer sur le boutton pour supprimer une manche
if(isset($_POST["supprManche"])){
    $manche = $_POST["supprManche"];
    //on delete le cookie correspondant et on ajuste les suivant
    $score_dernier = json_decode($_COOKIE["mahjong_partie" . "scores" . $manche], true);
    aff_debug($_COOKIE, "cookie avant effacement :");
    
    
    $i = $manche + 1;
    while(isset($_COOKIE["mahjong_partie" . "scores" . $i])){
        setCookieManche($_COOKIE["mahjong_partie" . "scores" . $i], ($i - 1));
        $i++;
    }
    
    //on ajuste le cookie des joueurs pour la manche :
    $data = json_decode($_COOKIE["mahjong_partie" . "joueurs"], true);
    $data["nb_manches"] = $data["nb_manches"] - 1;
    
    setCookieJoueur(json_encode($data));
    unsetCookieManche(($i - 1));
    
    aff_debug($_COOKIE, "cookie apres effacement :");
}

/////////////////////////////////////////////////////////////////////////////////////////////////

//pas de elseif pour garder les score.

//si on a appuyer sur le boutton nouvelle donne
if(isset($_POST["nouvelleDonneConfirmer"])){
    function acquierMains(array &$mains, string $etat, int $j) :void {
        $nb_piece = 1;
        $nb_combinaison = 1;
        $mains[$j][$etat] = array();
        
        
        while(isset($_POST[genIdInput($j, $etat, $nb_combinaison, 1)])
            && $_POST[genIdInput($j, $etat, $nb_combinaison, 1)] != ""){
                
                while(isset($_POST[genIdInput($j, $etat, $nb_combinaison, $nb_piece)])
                    && $_POST[genIdInput($j, $etat, $nb_combinaison, $nb_piece)] != ""){
                        
                        $mains[$j][$etat][$nb_combinaison - 1][$nb_piece - 1] = $_POST[genIdInput($j, $etat, $nb_combinaison, $nb_piece)];
                        $nb_piece +=1;
                }
                $nb_piece = 1;
                $nb_combinaison += 1;
        }
    }
    //on acquiert les mains :
    $mains = array();
    for($j = 1 ; $j <= 4 ; $j+=1){
        $mains[$j] = array();
        acquierMains($mains, Main::SCORING_EXPOSE, $j);
        acquierMains($mains, Main::SCORING_CACHE, $j);
        acquierMains($mains, Main::SCORING_HONNEUR, $j);
    }
    
    
    /**
     *
     * @var string définit le vent dominant
     */
    define("VENT_DOMINANT", $_POST["ventDominant"]);
    
    
    
    
    $j1 = new Joueur("1", $_POST["score_totalJ1"], $_POST["ventJ1"], $_POST["joueurMahjong"] == 1);
    $j1 ->setScore($mains[1], $_POST["joueurMahjong"] == 1 && $_POST["mahjongSpecial"] != "" ? array($_POST["mahjongSpecial"] => 1) : array());
    
    $j2 = new Joueur("2", $_POST["score_totalJ2"], $_POST["ventJ2"], $_POST["joueurMahjong"] == 2);
    $j2 ->setScore($mains[2], $_POST["joueurMahjong"] == 2 && $_POST["mahjongSpecial"] != "" ? array($_POST["mahjongSpecial"] => 1) : array());
    
    $j3 = new Joueur("3", $_POST["score_totalJ3"], $_POST["ventJ3"], $_POST["joueurMahjong"] == 3);
    $j3 ->setScore($mains[3], $_POST["joueurMahjong"] == 3 && $_POST["mahjongSpecial"] != "" ? array($_POST["mahjongSpecial"] => 1) : array());
    
    $j4 = new Joueur("4", $_POST["score_totalJ4"], $_POST["ventJ4"], $_POST["joueurMahjong"] == 4);
    $j4 ->setScore($mains[4], $_POST["joueurMahjong"] == 4 && $_POST["mahjongSpecial"] != "" ? array($_POST["mahjongSpecial"] => 1) : array());
    
    $manche_object = new Manche($j1, $j2, $j3, $j4);
    
    /////////////////////////////////////////////////////////////////////////
    //enregistrement des cookies => 1 cookie par manche + 1 cookie pour les nom des personnes et les vents
    aff_debug($_COOKIE, "Cookie avant traitements :");
    
    
    $manche = 1; //on recupere la manche
    while(isset($_COOKIE["mahjong_partie" . "scores" . $manche])){
        $manche ++;
    }
    $valeur_j = array();
    $valeur_m = array();
    
    //si on est a la manche 17, on reinitialise
    if($manche >= 17){
        for($m = 1 ; $m <= 16 ; $m++){
            setcookie("mahjong_partie" . "scores" . $m, "0", time() -1);
            unset($_COOKIE["mahjong_partie" . "scores" . $m]);
        }
        //on sauvegarde les scores totaux
        for($j = 1 ; $j <= 4 ; $j++){
            //on enleve la manche 17
            $valeur_j["score_init"][$j - 1] = $manche_object->getJoueur($j) ->getScoreFinal() - $manche_object->getJoueur($j) ->getScorePdtRedis();
        }
        $manche = 1;
    }
    //on verifie qu'elle existe, et si c'est le cas, on les resauvegarde
    else if (isset($_COOKIE["mahjong_partie" . "joueurs"])){
        $data = json_decode($_COOKIE["mahjong_partie" . "joueurs"], true);
        if(isset($data["score_init"][0])){
            //on sauvegarde les scores totaux
            for($j = 1 ; $j <= 4 ; $j++){
                $valeur_j["score_init"][$j - 1] = $data["score_init"][$j - 1];
            }
        }
    }
    
    //on parcourt les noms et on enregistre les données
    for($joueur = 1 ; $joueur <= 4 ; $joueur ++){
        //on ajoute les scores actuels
        $valeur_m["scoreJ" . $joueur] = $manche_object->getJoueur($joueur) ->getScorePdtRedis();
        //les noms, et les vent décalé
        $valeur_j["nomJ" . $joueur] = isset($_POST["nomJ" . $joueur]) ? $_POST["nomJ" . $joueur] : "";
        $valeur_j["ventJ" . $joueur] = $_POST["ventJ" . $joueur];
        
    }
    
    //on enregistre le nombre de manches, pour déclencher des event qd on ai a un multiple de 4 manches
    $valeur_j["nb_manches"] = $manche;
    
    setCookieManche(json_encode($valeur_m), $manche);
        
    setCookieJoueur(json_encode($valeur_j));
    
    aff_debug($_COOKIE, "Cookie apres traitements :");
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//bouton retour appuyer
elseif(isset($_POST["retourNouvelleDonne"])){
    function acquierMains(array &$mains, string $etat, int $j) :void {
        $nb_combinaison = 1;
        $mains[$j][$etat] = array();
        
        
        while(isset($_POST["prec" . genIdCombinaison($j, $etat, $nb_combinaison)])
            && $_POST["prec" . genIdCombinaison($j, $etat, $nb_combinaison)] != ""){
                $array_piece = explode(", ", $_POST["prec" . genIdCombinaison($j, $etat, $nb_combinaison)]);
                $max_piece = count($array_piece);
                
                for($piece = 0 ; $piece < $max_piece ; $piece ++){
                    $mains[$j][$etat][$nb_combinaison - 1][$piece] = $array_piece[$piece];
                }
                
                $nb_combinaison += 1;
        }
    }
    //on acquiert les mains :
    $mains = array();
    for($j = 1 ; $j <= 4 ; $j+=1){
        $mains[$j] = array();
        acquierMains($mains, Main::SCORING_EXPOSE, $j);
        acquierMains($mains, Main::SCORING_CACHE, $j);
        acquierMains($mains, Main::SCORING_HONNEUR, $j);
    }
    
    
    aff_debug($mains, "mains des joueurs :");
    //on supprime le dernier cookie de manche et on recupere sa valeur
    $manche = 1;
    while(isset($_COOKIE["mahjong_partie" . "scores" . $manche])){
        $manche ++;
    }
    $manche --;
    $score_dernier = json_decode($_COOKIE["mahjong_partie" . "scores" . $manche], true);
    aff_debug($_COOKIE, "cookie avant effacement :");
    
    //on modifie le cookie de la partie
    $informations_manche = json_decode($_COOKIE["mahjong_partiejoueurs"], true);
    $informations_manche["nb_manches"] = $manche - 1;

    setCookieJoueur(json_encode($informations_manche));
    unsetCookieManche($manche);

    aff_debug($_COOKIE, "cookie après effacement :");
    
    for ($j = 1; $j <= 4; $j ++) {
        aff_debug($_POST["score_totalJ" . $j], "score total du joueur " . $j);
        aff_debug($score_dernier["scoreJ" . $j], "score de derniere manche du joueur " . $j);
    }
    
    
    
    
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//ajustement des données pour l'affichage
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$aff_score_totaux = array(0, 0, 0, 0);

$aff_scores_manches = array();

$aff_nb_manche = 0;

$aff_vent_joueur = array(1, 1, 1, 1);

//on cherche dans les cookkies et on remplis le tableau des scores :
if(isset($_COOKIE["mahjong_partie" . "joueurs"])){
    $joueur = 1;
    $manche = 1;
    
    while(isset($_COOKIE["mahjong_partie" . "scores" . $manche])){
        $data = json_decode($_COOKIE["mahjong_partie" . "scores" . $manche], true);
        for($joueur = 1 ; $joueur <= 4 ; $joueur ++){
            $aff_scores_manches[$manche][$joueur] = $data["scoreJ" . $joueur];
            $aff_score_totaux[$joueur - 1] += $data["scoreJ" . $joueur];//pour afficher les score totaux
        }
        $manche ++;
    }
    
    ///////////////////////////////////////////////////////////////////////////////////////////
    
    
    $data = json_decode($_COOKIE["mahjong_partie" . "joueurs"], true);
    
    //si une partie dure :
    if(isset($data["score_init"][0])){
        for($joueur = 1 ; $joueur <= 4 ; $joueur ++){
            $aff_score_totaux[$joueur - 1] += $data["score_init"][$joueur - 1];
        }
    }
    
    for($joueur = 1 ; $joueur <= 4 ; $joueur ++){
        $_POST["nomJ" . $joueur] = $data["nomJ" . $joueur];
        
        //pour les vents : on decale vers la droites si on n'a pas appuyer sur le boutton nouvelle donne, sinon on prends ceux dans les cookies 
        //(précédens);
        if(isset($_POST["retourNouvelleDonne"])){
            $aff_vent_joueur[$joueur - 1] = $data["ventJ" . $joueur];
        }else{
            $aff_vent_joueur[$joueur - 1] = $data["ventJ" . $joueur] + 1 > 4 ? 1 : $data["ventJ" . $joueur] + 1;
        }
    }
    
    $aff_nb_manche = $data["nb_manches"];
}

aff_debug($aff_score_totaux, "scores totals :");

aff_debug($aff_scores_manches, "scores des manches :");

aff_debug($aff_vent_joueur, "vents des joueurs :");


?>