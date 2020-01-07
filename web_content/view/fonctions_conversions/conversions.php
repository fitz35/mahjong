<?php
/**
 * definit des fonctions utiles pour des conversions données/ui
 */

/**
 * 
 * @param array $score_joueur les score totaux des joueurs
 * @return string representant le joueur (les) ayant le score le plus haut
 */
function getJoueurPlusHautScoreUi(array $score_joueur) : string {
    $score = 0;
    $joueurs= array();
    
    for($j = 1 ; $j <= 4 ; $j ++){
        if($score_joueur[$j - 1] > $score){
            $score = $score_joueur[$j - 1];
            $joueurs = array($j); 
        }elseif($score_joueur[$j - 1] == $score){
            $joueurs[] = $j;
        }
    }
    
    $retour = "";
    $nb_j = count($joueurs);
    switch($nb_j){
        case 4 :
            $retour = "Tout le monde est à égalité !";
            break;
        case 1 :
            $retour = "Le joueur <span class='nomJoueur" . $joueurs[0] . "'>joueur n°" . $joueurs[0] . "</span> est en tête, avec " . $score . " points !";
            break;
        default :
            $retour .= "Les joueurs ";
            for($j = 1 ; $j <= $nb_j ; $j++){
                $retour .= "<span class='nomJoueur" . $joueurs[0] . "'>joueur n°" . $joueurs[0] . "</span>";
                if($j != $nb_j){
                    $retour .= ", ";
                }
            }
            $retour .= " sont en tête, avec " . $score . " points !";
            break;
    }
    
    return $retour;
}






?>