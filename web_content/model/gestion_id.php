<?php
/**
 * implemente des fonctions de generation d'id
 */

/**
 *
 * @param int $j le numero du joueur
 * @param string $etat l'etat de la piece
 * @param int $combi le numero de combinaison
 * @param int $piece le numero de la piece
 * @return string l'id de l'input correspondant
 */
function genIdInput(int $j, string $etat, int $combi, int $piece) : string {
    
    return "input_joueur-" . $j . "-combi-" . $combi . "-" . convertEtat($etat) . "-" . $piece;
}

/**
 * 
 * @param int $j le numero du joueur
 * @param string $etat l'etat de la piece
 * @param int $combi le numero de combinaison
 * @return string l'id de la combinaison correspondante
 */
function genIdCombinaison(int $j, string $etat, int $combi) : string {
    return "c_combi-" . $combi .  "-joueur-" . $j . "-etat-" . convertEtat($etat);
}

/**
 * 
 * @param string $etat Main::SCORING_EXPOSE, Main::SCORING_CACHE ou Main::SCORING_HONNEUR
 * @return string $etat sous une forme plus facile pour representer un id
 */ 
function convertEtat(string $etat) : string {
    switch($etat){
        case Main::SCORING_EXPOSE :
            $etat = "pieceDecou";
            break;
        case Main::SCORING_CACHE :
            $etat = "pieceCache";
            break;
        case Main::SCORING_HONNEUR :
            $etat = "pieceBonus";
            break;
        default:
            trigger_error("etat non reconnue.");
    }
    
    return $etat;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * 
 * @param string $value un string représentant une valeur json pour le cookie de joueurs
 * @note enregistre le cookie chez le client et sur le serveur pour cette page
 */
function setCookieJoueur(string $value) : void {
    setcookie("mahjong_partie" . "joueurs", $value, time() + 360*24*60*60);
    $_COOKIE["mahjong_partie" . "joueurs"] = $value;
}

/**
 * 
 * @param string $value  un string représentant une valeur json pour le cookie de manches
 * @param int $manche le numero de la manche
 * @note enregistre le cookie chez le client et sur le serveur pour cette page
 */
function setCookieManche(string $value, int $manche) : void {
    setcookie("mahjong_partie" . "scores" . $manche, $value, time() + 360*24*60*60);
    $_COOKIE["mahjong_partie" . "scores" . $manche] = $value;
}

/**
 *
 * @param int $manche le numero de la manche
 * @note enregistre la modification du cookie chez le client et sur le serveur pour cette page
 */
function unsetCookieManche(int $manche) : void {
    setcookie("mahjong_partie" . "scores" . $manche, "", time() - 1);
    unset($_COOKIE["mahjong_partie" . "scores" . $manche]);
}



?>