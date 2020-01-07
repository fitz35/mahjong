<?php

/**
 *
 * @author clahoche
 *        
 */
class Manche
{
    private $_joueurs;

    /**
     */
    public function __construct(Joueur $j1, Joueur $j2, Joueur $j3, Joueur $j4)
    {
        $this->_joueurs = array(
            $j1,
            $j2,
            $j3,
            $j4
        );
        $this->verifieJoueurs($this->_joueurs);
        // on trie les joueurs par leurs score (pour la redistribution des points)
        usort($this->_joueurs, "Joueur::trieScoreJoueurs");
        $this->equilibrageScore();
    }

    // //////////////////////////////////////////////////////////////////////////////////
    /**
     *
     * @param array $joueurs
     *            un tableau de 4 joueurs
     * @note genere des erreurs si les joueurs ne sont pas correctes ensembles
     */
    private function verifieJoueurs(array $joueurs): void
    {}

    // //////////////////////////////////////////////////////////////////////////////////
    /**
     *
     * @param string $n
     *            le nom du joueur
     * @return Joueur le joueur corespondant
     */
    public function getJoueur(string $n): Joueur
    {
        foreach ($this->_joueurs as $key => $value) {
            if ($value->getNom() == $n) {
                return $value;
            }
        }

        trigger_error("Aucun joueur ne correspond à ce nom !", E_USER_WARNING);
    }

    // /////////////////////////////////////////////////////////////////////////////////////
    private function equilibrageScore()
    {
        $joueur_mahjong = 3;
        $joueur_vent_est = self::getVentEast($this->_joueurs);
        $score_mahjong = $this->_joueurs[$joueur_mahjong] ->getScoreManche();
        //si le mahjong est aussi l'est :
        if($joueur_vent_est == $joueur_mahjong){
            $this->_joueurs[$joueur_mahjong] ->addScoreInRedis(6 * $score_mahjong);
            for ($j = 0; $j < 3; $j ++) {
               $this->_joueurs[$j] ->addScoreInRedis(-2 * $score_mahjong);
            }
        }else{
            $this->_joueurs[$joueur_mahjong] ->addScoreInRedis(4 * $score_mahjong);
            for ($j = 0; $j < 3; $j ++) {
                if($j == $joueur_vent_est){
                    $this->_joueurs[$j] ->addScoreInRedis(-2 * $score_mahjong);
                }else{
                    $this->_joueurs[$j] ->addScoreInRedis(- $score_mahjong);
                }
            }
        }
        
        // ensuite les autres joueur s'échange les points entre eux
        for ($i = 2; $i >= 0; $i --) {
                for ($j = 0; $j < $i; $j ++) {
                    $difference = ($this->_joueurs[$i]->getScoreManche() - $this->_joueurs[$j]->getScoreManche());
                    if($j == $joueur_vent_est || $i == $joueur_vent_est){
                        $difference *= 2;
                    }
                    $this->_joueurs[$i] ->addScoreInRedis($difference);
                    $this->_joueurs[$j] ->addScoreInRedis(-$difference);
                }
        }
        //on remplis les scores globaux :
        for ($j = 0; $j < 4; $j ++) {
            $this->_joueurs[$j] ->addScoreGlobal($this->_joueurs[$j] ->getScorePdtRedis());
            
        }
        
    }

    // /**
    // *
    // * @param array $joueurs les joueurs
    // * @return int le numéro du joueur qui a le plus haut score ou celui qui a le majong
    // */
    // private static function getPlusHautScore(array $joueurs) : int{
    // $retour = 0;
    // foreach ($joueurs as $key => $value){
    // if($joueur[$retour] -> getScoreManche() > $value -> getScoreManche()){
    // $retour = $key;
    // }
    // }
    // return $retour;
    // }

    /**
     *
     * @param array $joueur
     *            les joueurs
     * @return int le numéro du joueur qui a le vent d'est
     */
    private static function getVentEast(array $joueurs): int
    {
        foreach ($joueurs as $key => $value) {
            if ($value->getVent() == Piece::VENT["E"]) {
                return $key;
            }
        }
        trigger_error("Aucun joueur n'a le vent d'est !", E_USER_WARNING);
    }
}
