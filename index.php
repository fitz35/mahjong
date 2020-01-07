<?php


session_start();
ob_start();//il faut temporiser la sortie pour pouvoir mettre les cookie et afficher les debug plus proprement
define("DEBUG", true);
/**
 * 
 * @var string le mot de passe pour pouvoir modifier les constantes
 */
define("MDP_CONSTANTE", "1234");

$aff_debug = 0;

/**
 * 
 * @param mixed $a_aff le contenue a afficher
 * @param string $commentaire un commentaire avant
 * @note affiche $commentaire \n $a-aff si debug et a true
 */
function aff_debug($a_aff, string $commentaire) : void {
    global $aff_debug;
    if(defined("DEBUG") && DEBUG){
        $aff_debug ++;
        echo "\n<a href='index.php#aff_debug" . $aff_debug . "'>prochain affichage (" . ($aff_debug + 1) . ")</a>";
        echo "\n " . $commentaire . " \n" ;
        print_r($a_aff);
        echo "\n<div id='aff_debug" . $aff_debug . "'></div>\n";
    }
}




require("web_content/model/enreg_constante.php");

//si on a appuyer sur le boutton de modification des regles => maintenant pour que les changements s'applique tout de suite
if(isset($_POST["modifConstanteValider"])){
    $scoring_rule = getRule(FILE_SCORING_RULE);
    $scoring_rule_mahjong = getRule(FILE_SCORING_RULE_MAHJONG);
    
    //on verifie le password
    if(isset($_POST["mdp_constantes"]) && $_POST["mdp_constantes"] == MDP_CONSTANTE){
        //on acquier les champs
        $nouveau_rule = array();
        foreach($scoring_rule as $nom => $value){
            $nom_format = preg_replace("#\s#", "_", $nom);//aime pas les espaces
            
            $nouveau_rule[$nom] = array($_POST[$nom_format . "Visible"], $_POST[$nom_format . "Cache"], $_POST[$nom_format . "Multiplicateur"]);
        }
        aff_debug($nouveau_rule, "nouvelle regles normale :");
        setRule($nouveau_rule, FILE_SCORING_RULE);
        
        $nouveau_rule = array();
        foreach($scoring_rule_mahjong as $nom => $value){
            $nom_format = preg_replace("#\s#", "_", $nom);
            $nouveau_rule[$nom] = array($_POST[$nom_format . "Visible"], $_POST[$nom_format . "Cache"], $_POST[$nom_format . "Multiplicateur"]);
        }
        aff_debug($nouveau_rule, "nouvelle regles mahjong :");
        setRule($nouveau_rule, FILE_SCORING_RULE_MAHJONG);
    }else{
        $_POST["modifConstante"] = 1;
        $mdp_constante = false; //=> affichage
    }
}

require 'web_content/model/mahjonc_calcul/define_scoring_rule.php';
require("web_content/model/gestion_id.php");




require("web_content/model/post_acquereur.php");

require "web_content/view/fonctions_conversions/conversions.php";

require ("web_content/view/template.php");