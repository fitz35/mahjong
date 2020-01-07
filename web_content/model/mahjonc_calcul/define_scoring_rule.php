<?php

$temp = "";

// on load la class Score
require ($temp . "web_content/model/mahjonc_calcul/mains_convert/Combinaison.php");
// on load la class joueur (qui a besoin des rêgles de scoring)
require ($temp . "web_content/model/mahjonc_calcul/Joueur.php");
// on load la class de manche
require ($temp . "web_content/model/mahjonc_calcul/Manche.php");
//on load la class de piece
require ($temp . "web_content/model/mahjonc_calcul/mains_convert/Piece.php");
// on load la class de convertisseur
require ($temp . "web_content/model/mahjonc_calcul/mains_convert/Main.php");


/**
 *
 * @var array sous la forme :
 *      "nom" => array(score expose, score cache, moyen de scoring)
 */
define("SCORING_RULE", getRule(FILE_SCORING_RULE));
aff_debug(SCORING_RULE, "Scoring rule :");

/*
 * array (
        self::SCORING_SUITE => array(null, null, null),
        /*combinaison
        self::SCORING_SAME_PIECE[2] => array(null, null, null),
        self::SCORING_SAME_PIECE[2] . self::SCORING_MAJEUR => array(null, null, null),
        self::SCORING_SAME_PIECE[2] . self::SCORING_MAJEUR_D_V . self::SCORING_V_DOMINNANT => array(2, 2, 1),
        self::SCORING_SAME_PIECE[2] . self::SCORING_MAJEUR_D_V . self::SCORING_V_JOUEUR => array(2, 2, 1),
        self::SCORING_SAME_PIECE[2] . self::SCORING_MAJEUR_D_V=> array(2, 2, 1),
        self::SCORING_SAME_PIECE[2] . self::SCORING_MAJEUR_D_V . self::SCORING_V_JOUEUR . self::SCORING_V_DOMINNANT => array(2, 2, 1),
        
        self::SCORING_SAME_PIECE[3] => array(2, 4, 1),
        self::SCORING_SAME_PIECE[3] . self::SCORING_MAJEUR_D_V => array(4, 8, 2),
        self::SCORING_SAME_PIECE[3] . self::SCORING_MAJEUR => array(4, 8, 1),
        self::SCORING_SAME_PIECE[3] . self::SCORING_MAJEUR_D_V . self::SCORING_V_JOUEUR => array(4, 8, 4),
        self::SCORING_SAME_PIECE[3] . self::SCORING_MAJEUR_D_V . self::SCORING_V_DOMINNANT => array(4, 8, 4),
        self::SCORING_SAME_PIECE[3] . self::SCORING_MAJEUR_D_V . self::SCORING_V_JOUEUR . self::SCORING_V_DOMINNANT => array(4, 8, 4),
        
        self::SCORING_SAME_PIECE[4] => array(8, 16, 2),
        self::SCORING_SAME_PIECE[4] . self::SCORING_MAJEUR_D_V => array(16, 32, 4),
        self::SCORING_SAME_PIECE[4] . self::SCORING_MAJEUR => array(16, 32, 2),
        self::SCORING_SAME_PIECE[4] . self::SCORING_MAJEUR_D_V . self::SCORING_V_JOUEUR => array(16, 32, 8),
        self::SCORING_SAME_PIECE[4] . self::SCORING_MAJEUR_D_V . self::SCORING_V_DOMINNANT => array(16, 32, 8),
        self::SCORING_SAME_PIECE[4] . self::SCORING_MAJEUR_D_V . self::SCORING_V_JOUEUR . self::SCORING_V_DOMINNANT => array(16, 32, 4),
        
        
        
        
        self::SCORING_HONNEUR => array(4, null, 1),
        
        self::SCORING_HONNEUR . self::SCORING_V_JOUEUR => array(4, null, 2),
        self::SCORING_HONNEUR . self::SCORING_SAME_PIECE[4] => array(16, null, 4),
        
        
    );
 */



/**
 *
 * @var array sous la forme :
 *      "nom" => array(score expose, score cache, moyen de scoring)
 */
define("SCORING_RULE_MAHJONG", getRule(FILE_SCORING_RULE_MAHJONG));
aff_debug(SCORING_RULE_MAHJONG, "Scoring rule mahjong :");


/*SCORING_RULE_MAHJONG_DEFAULT = array(
        self::SCORING_MAHJONG_NORMAL => array(20, null, null),
        self::SCORING_MAHJONG_QUE_BRELAN => array(20, null, null),
        self::SCORING_MAHJONG_COULEUR_TROUBLE => array(null, null, 2),
        self::SCORING_MAHJONG_QUE_SUITE => array(20, null, null),
        
        
        self::SCORING_MAHJONG_COULEUR_PUR => array(null, null, 8),
        self::SCORING_MAHJONG_COULEUR_PUR_DV => array(null, null, 16),
        self::SCORING_MAHJONG_QUE_MAJEUR => array(null, null, 16),
        self::SCORING_MAHJONG_QUE_PAIRES => array(3000, null, null),
    );*/



?>