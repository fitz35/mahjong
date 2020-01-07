<?php
/**
 * construit une piece
 * @author clementL
 *
 */

class Piece
{
    public const FAMILLE = array(
        "C" => 9,
        "R" => 9,
        "B" => 9,
        
        "D" => 3,
        
        "V" => 4,
        
        "F" => 4,
        "S" => 4
    );
    
    public const VENT = array(
        "E" => 1,
        "S" => 2,
        "O" => 3,
        "N" => 4
    );
    /**
     * @var array convertit un chiffre en son equivalent vent
     */
    public const INVERT_VENT = array(
        1 => "E",
        2 => "S",
        3 => "O",
        4 => "N"
    );
    
    
    public const AFFECTER_BY_VENT = array(
        "V" => 1,
        "F" => 1,
        "S" => 1
    );
    
    public const TUILE_NORMALES = array(
        "C" => 1,
        "R" => 1,
        "B" => 1,
    );
    
    private const CONVERT_DRAGON = array(
        "R" => 1,
        "V" =>2 ,
        "B" => 3
    );
    /////////////////////////////////////////////////////////////////////////
    private $_famille;
    
    private $_nb;
    
    public function __construct(string $chaine)
    {
        $famille = substr($chaine, 1);
        $valeur = substr($chaine, 0, 1);
        
        if(array_key_exists($famille, self::FAMILLE) && self::isNumero($valeur, $famille)){
            $this->_famille = $famille;
            $this->_nb=$valeur;
        }else{
            trigger_error("La piece " . $chaine . " n'est pas reconnue.", E_USER_WARNING);
        }
    }
    
    
    //////////////////////////////////////////////////////////////////////////////
    /**
     *
     * @return string la famille de la ^piece
     */
    public function getFamille() : string
    {
        return $this->_famille;
    }
    
    /**
     *
     * @return string le numero de la piece
     */
    public function getNumero() : string {
        if($this->_famille == "V"){
            return self::VENT[$this->_nb];
        }elseif($this->_famille == "D"){
            return self::CONVERT_DRAGON[$this->_nb];
        }else {
            return $this->_nb;
        }
    }
    
    /**
     *
     * @return bool si la piece est affecter par les vents
     */
    public function isVentAffect() : bool{
        return isset(self::AFFECTER_BY_VENT[$this->_famille]);
    }
    
    /**
     *
     * @return bool si la piece est une majeur de tuile normale
     */
    public function isMajeur() : bool {
        return isset(self::TUILE_NORMALES[$this->_famille]) && ($this->_nb == 1 || $this->_nb == 9);
    }
    
    /**
     *
     * @return bool si la piece est un vent ou un dragon
     */
    public function isMajeur_d_v() : bool {
        return $this->_famille == "V" || $this->_famille == "D";
    }
    
    /**
     *
     * @return bool si la piece est un honneurs
     */
    public function isHonneur() : bool {
        return $this->_famille == "S" || $this->_famille == "F";
    }
    
    /**
     *
     * @return bool vrai si la piece est une des trois tuiles normale (cercle, charactere ou bambou)
     */
    public function isTuileNormal() : bool {
        return $this->_famille == "C" || $this->_famille == "R" || $this->_famille == "B";
    }
    
    
    
    /**
     *
     * @param Piece $p1
     * @param Piece $p2
     * @return bool si, la piece p1 et p2 sont egale
     */
    public static function isEgale (Piece $p1, Piece $p2) : bool {
        return $p1 ->getFamille() == $p2 ->getFamille() && $p1 ->getNumero() == $p2 ->getNumero();
    }
    
    /**
     *
     * @param Piece $p la piece a tester
     * @param string $vent le numero du vent a tester
     * @return bool si la piece est un honneur et affecter par le vent $vent
     */
    public static function isHonneursSameVent (Piece $p, string $vent) : bool {
        return ($p -> getFamille() == "S" || $p -> getFamille() == "F") && $p ->getNumero() == $vent;
    }
    
    ///////////////////////////////////////////////////////////////////////////////
    /**
     *
     * @param string $nb le nomre a tester
     * @param string $famille l famille a tester
     * @return bool si nb est valide pour la famille
     */
    private static function isNumero(string $nb, string $famille) : bool{
        if($famille == "V"){
            return isset(self::VENT[$nb]);
        }elseif($famille == "D"){
            return isset(self::CONVERT_DRAGON[$nb]);
        }
        else{
            return preg_match("#[1-" . self::FAMILLE[$famille] . "]#", $nb) == 1;
        }
    }
}
