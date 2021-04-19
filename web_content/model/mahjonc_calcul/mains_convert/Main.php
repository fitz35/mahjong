<?php
/**
 * convertit un set de piece assez long en des combinaisons et établis les regles de scoring.
 * @author clahoche
 *
 */

class Main
{
    /**
     * score maximal pouvant etre gagner par une main avant redistribution
     */
    public const SCORE_LIMITE = 3000;
    
    /**
     * array sous la forme : { nombre de pieces : nom}
     */
    public const SCORING_SAME_PIECE = array(
        2 => "Paire",
        3 => "Brelan",
        4 => "Carre"
    );
    
    public const SCORING_SUITE = "suite";
    
    
    /**
     * si la combinaison est exposée
     */
    public const SCORING_EXPOSE = " expose";
    public const SCORING_CACHE = " cache";
    
    public const SCORING_V_DOMINNANT = " dominant";
    public const SCORING_V_JOUEUR = " du joueur";
    
    /**
     * dragon, vent
     */
    public const SCORING_MAJEUR_D_V = " de vents ou de dragons";
    /**
     * 1 et 9
     */
    public const SCORING_MAJEUR = " de 1 ou de 9";
    
    /**
     * saison et fleur
     */
    public const SCORING_HONNEUR = "honneur ";
    
    /**
     * permet la creation d'une autre regle a partir d'une méme combinaison
     */
    public const SCORING_MULT = " autre";
    
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    
    public const SCORING_MAHJONG_NORMAL = "Mahjong normal";
    
    public const SCORING_MAHJONG_QUE_BRELAN = "Mahjong avec que des brelans";
    
    public const SCORING_MAHJONG_COULEUR_TROUBLE = "Mahjong couleur 'trouble'";
    
    public const SCORING_MAHJONG_QUE_SUITE = "Mahjong avec que des suites";
    
    
    //..................................................................................
    
    public const SCORING_MAHJONG_COULEUR_PUR = "Mahjong couleur 'pur'";
    
    public const SCORING_MAHJONG_COULEUR_PUR_DV = "Mahjong que de Dragons ou que de Vents";
    
    public const SCORING_MAHJONG_QUE_MAJEUR = "Mahjong de 1 ou de 9";
    
    public const SCORING_MAHJONG_QUE_PAIRES = "Mahjong avec que des paires";
    
    //.....................................................................................
    //mahjong non detectable par l'analyse de main :
    public const SCORING_MAHJONG_SEC = "Mahjong sec";
    public const SCORING_MAHJONG_TUILE_PIOCHE = "Mahjong avec une tuile de la pioche";
    public const SCORING_MAHJONG_DERNIERE_TUILE = "Mahjong avec la derniere tuile";
    
    /**
     * mahjong qui ne peuvent pas etre detecte par une analyse de main
     */
    public const NON_DETECTABLE = array(
        self::SCORING_MAHJONG_SEC,
        self::SCORING_MAHJONG_TUILE_PIOCHE,
        self::SCORING_MAHJONG_DERNIERE_TUILE
    );
    
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    
    private $_vent_dominant = 1;
    private $_score_de_main = 0;
    private $_combinaisons = array();
    private $_nomCombinaison = array();
    /**
     * 
     * @var array le tableau des mahjong si il y a lieu sous la forme "nom" => q
     */
    private $_mahjong = array();
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * 
     * @param array $tab_piece un tableau sous la forme : (SCORING_EXPOSE => array(Combinaison), SCORING_CACHE => array(Combinaison),
     *            SCORING_HONNEUR => array(Combinaison))
     * @param Joueur $j
     * @param array $mahjong_speciaux un tableau sous la forme "nom" => 1
     */
    public function __construct(array $tab_piece, Joueur $j, array $mahjong_speciaux = array()){
        if(defined("VENT_DOMINANT")){
            $this->_vent_dominant = VENT_DOMINANT;
        }
        
        if(isset($tab_piece[self::SCORING_EXPOSE])){
            foreach($tab_piece[self::SCORING_EXPOSE] as $value){
                $this->_combinaisons[] = new Combinaison($value, self::SCORING_EXPOSE, $j ->getVent(), $this->_vent_dominant);
            }
        }
        
        if(isset($tab_piece[self::SCORING_CACHE])){
            foreach($tab_piece[self::SCORING_CACHE] as $value){
                $this->_combinaisons[] = new Combinaison($value, self::SCORING_CACHE, $j ->getVent(), $this->_vent_dominant);
            }
        }
        
        if(isset($tab_piece[self::SCORING_HONNEUR])){
            foreach($tab_piece[self::SCORING_HONNEUR] as $value){
                $this->_combinaisons[] = new Combinaison($value, self::SCORING_EXPOSE, $j ->getVent(), $this->_vent_dominant);
            }
        }
        
        //si on a des combinaisons on calcl les score 
        if(count($this->_combinaisons) > 0){
            //on test si on est mahjong :
            if($j ->isMahjong()){
                $this->_mahjong = $mahjong_speciaux;//on commence par prendre les mahjong spéciaux
                $this->getMahjong();
            }
            
            //on additionne
            foreach($this->_combinaisons  as $value){
                $this->_score_de_main += $value -> getAdd();
            }
            if($j ->isMahjong()){
                foreach ($this->_mahjong as $key => $value){
                    if(SCORING_RULE_MAHJONG[$key][0] != null && SCORING_RULE_MAHJONG[$key][0] != ""){
                        $this->_score_de_main += SCORING_RULE_MAHJONG[$key][0] * $value;
                    }
                }
            }
            
            //on multiplie
            foreach($this->_combinaisons  as $value){
                $this->_score_de_main *= $value -> getMult();
            }
            
            if($j ->isMahjong()){
                foreach ($this->_mahjong as $key => $value){
                    if(SCORING_RULE_MAHJONG[$key][2] != null && SCORING_RULE_MAHJONG[$key][2] != 0 && $value != 0){
                        $this->_score_de_main *= SCORING_RULE_MAHJONG[$key][2] ** $value;
                    }
                }
            }
            
            //on calcul les noms :
            foreach ($this->_combinaisons as $value) {
                foreach($value -> getNom() as $nomCombi => $nombreCombi)
                {
                    if(array_key_exists($nomCombi , $this -> _nomCombinaison))
                    {
                        $this -> _nomCombinaison[$nomCombi] += $nombreCombi;
                    }else{
                        $this -> _nomCombinaison[$nomCombi] = $nombreCombi;
                    }
                }
                
               
            }
            $this->_nomCombinaison = array_merge ( $this->_nomCombinaison, $this->_mahjong);
        }
    }
    
    /**
     * 
     * @return int le nombre de point avant redistribution de cette main
     * 
     */
    public function getPoint() : int {
        return $this->_score_de_main > self::SCORE_LIMITE ? self::SCORE_LIMITE : $this->_score_de_main;
    }
    
    /**
     * 
     * @return array le tableau ("nom de la regle" => q)
     */
    public function getNomScoringRule() : array{
        return $this -> _nomCombinaison;
    }
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @note ajoute au tableau 
     */
    private function getMahjong() : void  {
        //deja on a fait un mahjong normal :
        $this->_mahjong[self::SCORING_MAHJONG_NORMAL] =  1;
        
        //definition des memoire pour les mahjong : 
            $que_brelan = true;
            
            $couleur_string_normale = "";
            $couleur_trouble = true;
            $couleur_pur = true;
            
            $couleur_string_v_d = "";
            $couleur_v_ou_d = true;
            
            $que_paire = true;
            
            $que_suite = true;
            
            $que_majeur = true;//que des 1 ou des 9
        //definition des valeurs de debug
        $temp_debug = array();
            
        
        foreach($this->_combinaisons as $value){
            $nb_exposer = 0;
            
            
            $value_format = preg_replace("#" . self::SCORING_EXPOSE . "#", "", key($value -> getNom()), -1, $nb_exposer);
            $value_format = preg_replace("#" . self::SCORING_CACHE . "#", "", $value_format);
            $temp_debug[] = $value_format;
            
            $couleur_actu = $value -> getFamille();
            
            $que_suite = $que_suite && $value_format == self::SCORING_SUITE;
            $que_brelan = $que_brelan && $value_format != self::SCORING_SUITE;
            
            $que_paire = $que_paire && preg_match("#^". self::SCORING_SAME_PIECE[2] . ".*$#", $value_format);
            $que_majeur = $que_majeur && $value_format != self::SCORING_SUITE && $value -> getPiece(0)-> isMajeur();
            
            
            //couleur pur ou trouble ou que de vent/dragons
            if($couleur_actu == "R" || $couleur_actu == "C" || $couleur_actu == "B"){
                $couleur_v_ou_d = false;
                
                if($couleur_string_normale == ""){
                    $couleur_string_normale = $couleur_actu;
                }else{
                    $couleur_pur = $couleur_pur && $couleur_actu == $couleur_string_normale;
                    $couleur_trouble = $couleur_trouble && $couleur_actu == $couleur_string_normale;
                }
                
            }elseif($couleur_actu == "D" || $couleur_actu =="V"){
                $couleur_pur = false;
                if($couleur_string_v_d == ""){
                    $couleur_string_v_d = $couleur_actu;
                }else{
                    $couleur_v_ou_d = $couleur_string_v_d && $couleur_actu == $couleur_string_v_d;
                }
            }
            
        }
        aff_debug($temp_debug, "Definition des mahjong du joueur " . $this->_vent_dominant . " avec les combinaisons suivantes :");
        
        //////////////////////////////////////////////////////////////////////////////
        
        if($que_brelan){
            $this->_mahjong [self::SCORING_MAHJONG_QUE_BRELAN] = 1;
        }
        
        if($que_paire){
            $this->_mahjong[self::SCORING_MAHJONG_QUE_PAIRES] = 1;
        }
        
        if($que_suite){
            $this->_mahjong[self::SCORING_MAHJONG_QUE_SUITE]= 1;
        }
        
        if($que_majeur){
            $this->_mahjong[self::SCORING_MAHJONG_QUE_MAJEUR] = 1;
        }
        
        if($couleur_pur){
            $this->_mahjong[self::SCORING_MAHJONG_COULEUR_PUR] = 1;
        }elseif($couleur_v_ou_d){
            $this->_mahjong[self::SCORING_MAHJONG_COULEUR_PUR_DV] = 1;
        }elseif($couleur_trouble){
            $this->_mahjong[self::SCORING_MAHJONG_COULEUR_TROUBLE] = 1;
        }
        
        
    }
    
    
    
}

