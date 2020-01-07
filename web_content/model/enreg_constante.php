<?php
/*
 *definit deux fonctions, une d'enregistrement et une de lecture de fichier
 */

define("DELIMITER_ENR_IN_CSV", ";");
define("FILE_SCORING_RULE", "scoring_rule.csv");
define("FILE_SCORING_RULE_MAHJONG", "scoring_rule_mahjong.csv");


/**
 * 
 * @param array $rule les regles de scoring a enregistrer
 * @param string $type le type des scoring rule parmis FILE_SCORING_RULE ou FILE_SCORING_RULE_MAHJONG
 * @note enregistre les regles $rule dans le fichiers des regles $type
 */
function setRule(array $rule, string $type) : void {
    $file_content = "nom" . DELIMITER_ENR_IN_CSV . "ouvert" . DELIMITER_ENR_IN_CSV . "cache" . DELIMITER_ENR_IN_CSV . "multiplicateur" . PHP_EOL;
    foreach($rule as $nom=> $value){
        $file_content .= $nom . DELIMITER_ENR_IN_CSV . $value[0] . DELIMITER_ENR_IN_CSV . $value[1] . DELIMITER_ENR_IN_CSV . $value[2] . PHP_EOL;
    }
    
    file_put_contents($type, $file_content);
}


/**
 * 
 * @param string $type le type des regles
 * @return array un tableau sous la forme "nom" => array(ouvert, cache, multiplicateur)
 */
function getRule(string $type) : array {
    $file_contents = explode(PHP_EOL, file_get_contents($type));
    $retour = array();
    $max = count($file_contents);
    for($i = 1 ; $i < $max ; $i++){//on prends pas en compte la premiere ligne
        $line = explode(DELIMITER_ENR_IN_CSV, $file_contents[$i]);
        if(isset($line[0]) && $line[0] != ""){
            $retour[$line[0]] = array($line[1], $line[2], $line[3]);
        }
    }
    
    
    return $retour;
    
}



?>