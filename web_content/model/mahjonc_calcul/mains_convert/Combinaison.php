<?php

/**
 * convertit un set de piece en des combinaison
 * @author clahoche
 *
 */
class Combinaison
{

    
    private $_nom_combi = array();
    private $_piece = array();
    private $_etat ;
    
    
    private $_vent_j;
    private $_vent_d;
    
    /**
     * 
     * @param array $pieces un array de string representant des pieces
     * @param string $etat l'etat de la combinaison (SCORING_EXPOSE ou SCORING_CACHE)
     * @param string $vent_j le numéro du vent du joueur
     * @param string $vent_d le numéro du vent dominant
     */
    public function __construct(array $pieces, string $etat, string $vent_j, string $vent_d)
    {
        foreach($pieces as $key=>$value){
            $this -> _piece[$key] = new Piece($value);
        }
        
        $this->_vent_d = $vent_d;
        $this->_vent_j=$vent_j;
        $this ->_etat = $etat;
        
        $this ->genNomCombi();
        
    }
    
    /**
     * 
     * @return int
     */
    public function getAdd () : int {
        $etat = $this->_etat == Main::SCORING_EXPOSE ? 0 : 1;
        $retour = 0;
        foreach($this->_nom_combi as $key => $value){
            if(SCORING_RULE[$key][$etat] != null){
                $retour += SCORING_RULE[$key][$etat] * $value;
            }
        }
        
        return  $retour;
    }
    
    /**
     * 
     * @return int
     */
    public function getMult() : int {
        $retour = 1;
        foreach($this->_nom_combi as $key => $value){
            if(SCORING_RULE[$key][2] != null && SCORING_RULE[$key][2] != 0){
                $retour *= SCORING_RULE[$key][2] ** $value;
            }
        }
        
        return $retour;
    }
    
    /**
     * recupere les nom des combinaisons sous la forme array(nomCombi => nombreDeCombi)
     * @return array
     */
    public function getNom() : array {
        $retour = array();
        foreach($this->_nom_combi as $key => $value){
            $retour[$key . $this->_etat] = $value;
        }
        
        return $retour;
    }
    
    public function getFamille() : string {
        return $this->_piece[0] -> getFamille();
    }
    
    /**
     * 
     * @param int $n le numero de la piece dans la combinaison
     * @return Piece la piece correspondante
     */
    public function getPiece(int $n) : Piece {
        return $this->_piece[$n];
    }
    
    /////////////////////////////////////////////////////////////////////////////////////
    
    /**
     * @note genere le nom de la combinaison
     */
    private function genNomCombi() : void {
        $nb_piece = count($this->_piece);
        
        if($this->_piece[0] -> isHonneur()){
            if($nb_piece == 4){
                $this ->_nom_combi[Main::SCORING_HONNEUR . Main::SCORING_SAME_PIECE[4]] =  1;
            }else{
                $is_vent = false;
                foreach($this->_piece as $key => $value){
                    $is_vent = $is_vent || Piece::isHonneursSameVent($value, $this ->_vent_j);
                }
                
                if($is_vent){
                    $this ->_nom_combi[Main::SCORING_HONNEUR . Main::SCORING_V_JOUEUR] = 1;
                    if($nb_piece > 1){
                        $this ->_nom_combi[Main::SCORING_HONNEUR] = $nb_piece - 1;
                    }
                }else{
                    $this ->_nom_combi[Main::SCORING_HONNEUR] = $nb_piece;
                }
                
            }
        }else{
            $temp = "";
            if(self::toujoursPareil($this->_piece)){
                
                $temp = Main::SCORING_SAME_PIECE[$nb_piece];
                
                //si c'est une piece majeur
                $temp .= $this->_piece[0] -> isMajeur() ? Main::SCORING_MAJEUR : "";
                
                $temp .= $this->_piece[0] -> isMajeur_d_v() ? Main::SCORING_MAJEUR_D_V : "";
                
                //si c'est une piece affecter par les vents
                if($this->_piece[0] -> isVentAffect()){
                    //si le vent est egale a celuis du joueur
                    $temp .= $this->_piece[0] -> getNumero() == $this -> _vent_j ? Main::SCORING_V_JOUEUR : "";
                    
                    //si le vent est egale a celui dominant
                    $temp .= $this->_piece[0] -> getNumero() == $this->_vent_d ? Main::SCORING_V_DOMINNANT : "";
                }
                
            }else{
                $temp = Main::SCORING_SUITE;
            }
            $this ->_nom_combi[$temp] = 1;
        }
    }
    
    /**
     * 
     * @param array $tab un tableau de piece
     * @return bool si tous les elements du tbleau sont égale
     */
    private static function toujoursPareil(array $tab) : bool {
        $b_elt = count($tab);
        if($b_elt == 0 || $b_elt == 1){
            return true;
        }else{
            return Piece::isEgale($tab[0], $tab[1]) && self::toujoursPareil(array_slice($tab, 1));
        }
    }
    
    private static function toujoursMemeFamille(array $tab) : bool {
        $b_elt = count($tab);
        if($b_elt == 0 || $b_elt == 1){
            return true;
        }else{
            return $tab[0] -> getFamille() == $tab[1] -> getFamille() && self::toujoursMemeFamille(array_slice($tab, 1));
        }
    }
    
}

