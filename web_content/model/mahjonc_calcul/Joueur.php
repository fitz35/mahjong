<?php

class Joueur
{
    
    // ///////////////////////////////////////////////////////////////////////////////////////////////
    // constante de class
    // ///////////////////////////////////////////////////////////////////////////////////////////////

    // ///////////////////////////////////////////////////////////////////////////////////////////////
    // variable de class
    // ///////////////////////////////////////////////////////////////////////////////////////////////
    // variables non destiné a changer
    /**
     *
     * @var string nom du joueur
     */
    private $_nom;

    /**
     *
     * @var string vent du joueur
     */
    private $_vent;

    // ///////////////////////////////////////////////////////////////////////////////////////////////
    /**
     *
     * @var integer score global
     */
    private $_score = 0;

    /**
     *
     * @var Main la main des joueurs .
     */
    private $_main;

    /**
     *
     * @var int score avant la redistribution
     */
    private $_score_avant_redis = 0;
    
    /**
     * 
     * @var int score pendant la redistribution
     */
    private $_score_pendant_redis = 0;
    
    
    /**
     * 
     * @var boolean si le joueur a fait mahjong
     */
    private $_mahjong ;
    
    
    // ///////////////////////////////////////////////////////////////////////////////////////////////
    // constructeur
    // ///////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * 
     * @param string $nom le nom du joueur
     * @param int $score le score avant la manche en cours
     * @param string $vent le numéro du vent du joueru
     * @param bool $mahjong si le joueur a fait mahjong (false pardefaut)
     */
    public function __construct(string $nom, int $score, string $vent, bool $mahjong = false)
    {
        $this->_nom = $nom;
        $this->_score = $score;
        $this->isVent($vent);
        $this->_vent = $vent;
        $this ->_mahjong = $mahjong;
    }

    // ///////////////////////////////////////////////////////////////////////////////////////////////
    // mutateur/assesseur
    // ///////////////////////////////////////////////////////////////////////////////////////////////
    /**
     *
     * @return string le nom du joueur
     */
    public function getNom(): string
    {
        return $this->_nom;
    }

    // ///////////////////////////////////////////////////////////////////////////////////////////////

    /**
     *
     * @param array $tab_score
     *            le tableau des pieces du joueur sous la forme : (SCORING_EXPOSE => array(Combinaison), SCORING_CACHE => array(Combinaison),
     *            SCORING_HONNEUR => array(Combinaison))
     *            
     * @param array $mahjong_speciaux un tableau sous la forme "nom" => 1 avec les espaces transformer en _ (post)
     *            
     */
    public function setScore(array $tab_score, array $mahjong_speciaux = array()): void
    {
        if(defined("DEBUG") && DEBUG){
            echo "joueur " . $this->_nom . ", score initial : " . $this -> _score;
        }
        
        $tab_mahjong_speciaux = array();
        
        foreach($mahjong_speciaux as $key => $value){
            $tab_mahjong_speciaux[preg_replace("#_#", " ", $key)] = 1;
        }
        
        $this-> _main = new Main($tab_score, $this, $tab_mahjong_speciaux);
        $this->_score_avant_redis = $this->_main->getPoint();
        
        if(defined("DEBUG") && DEBUG){
            echo ".\nregles de score : \n";
            print_r($this->getScoringRuleManche());
            echo "\n score de la manche avant redistribution : " . $this->_score_avant_redis . "\n";
        }
        
    }

    /**
     *
     * @param int $score
     *            le score a ajouter
     * @return int le nouveau score
     * @note ajoute le $score au score global
     */
    public function addScoreGlobal(int $score): int
    {
        $this->_score += $score;
        return $this->_score;
    }

    /**
     *
     * @param int $score
     *            le score a ajouter
     * @return int le nouveau score
     * @note ajoute le $score au score de pendant le redistribution
     */
    public function addScoreInRedis(int $score): int {
        $this->_score_pendant_redis += $score;
        return $this->_score_pendant_redis;
    }
    
    /**
     *
     * @return int le score de pendant le redistribution
     */
    public function getScorePdtRedis() : int {
        return $this->_score_pendant_redis;
    }
    
    /**
     *
     * @return int le score global
     */
    public function getScoreFinal(): int
    {
        return $this->_score;
    }

    /**
     *
     * @return int le score avant redistribution des points
     */
    public function getScoreManche(): int
    {
        return $this->_score_avant_redis;
    }

    /**
     *
     * @return string le numero du vent du joueur
     */
    public function getVent(): string
    {
        return $this->_vent;
    }

    /**
     * 
     * @return array les scoring rule du joueur sous la forme ("nom de la regles" => q)
     */
    public function getScoringRuleManche() : array {
        return $this->_main->getNomScoringRule();
    }
    
    
    // ///////////////////////////////////////////////////////////////////////////////////////////////
    // methode public
    // ///////////////////////////////////////////////////////////////////////////////////////////////
    /**
     *
     * @return bool si le joueur a fait mahjong
     */
    public function isMahjong(): bool
    {
        return $this->_mahjong;
    }

    // ///////////////////////////////////////////////////////////////////////////////////////////////
    // fonction public
    // ///////////////////////////////////////////////////////////////////////////////////////////////

    /**
     *
     * @param Joueur $j1
     *            le premier joueur
     * @param Joueur $j2
     *            le deuxième joueur
     * @return int fonction de tri qui place le joueur qui a fait mahjong en premier, et les autres sont trier par ordre de score de manche
     */
    public static function trieScoreJoueurs(Joueur $j1, Joueur $j2): int
    {
        if ($j2->isMahjong()) {
            return - 1;
        } else if($j1->isMahjong()){
                return 1;
        }else if ($j1->getScoreManche() <= $j2->getScoreManche()){
            return - 1;
        }else if ($j1->getScoreManche() >= $j2->getScoreManche()){
            return  1;
        }else{
            return 0;
        }
    }

    // ///////////////////////////////////////////////////////////////////////////////////////////////
    // methode privée
    // ///////////////////////////////////////////////////////////////////////////////////////////////

    /**
     *
     * @param string $vent
     *            un string quelquonque
     * @note prvoque une erreur si le string n'est pas un vent parmis les constantes "vent".
     */
    private function isVent(string $vent): void
    {
        if (!isset(Piece::INVERT_VENT[$vent * 1])) {
            trigger_error("Le vent n'est pas correcte.", E_USER_WARNING);
        }
    }
}

?>