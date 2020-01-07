<?php
// gere les erreurs (pour intercepter toute les erreurs et continuer le script)
set_error_handler('exceptions_error_handler');

function exceptions_error_handler($severity, $message, $filename, $lineno)
{
    if (error_reporting() == 0) {
        return;
    }
    if (error_reporting() & $severity) {
        echo $message . "/ severite : " . $severity . "/ filename : " . $filename . "/ lineo : " . $lineno . "<br/><br/>";
    }
}

function RedistributionTest()
{
    include_once '../../web_content/model/mahjonc_calcul/mains_convert/Combinaison.php';
    include_once '../../web_content/model/mahjonc_calcul/mains_convert/Main.php';
    include_once '../../web_content/model/mahjonc_calcul/mains_convert/Piece.php';
    
    $test_u = 1;
    function template(){
        global $test_u;
        echo "test n°" . $test_u . " : \n";
        $test_u += 1;
        
        $pieces = array (
            "1C", "1C", "1C"
        );
        
        $combinaison = new Combinaison($pieces, Main::SCORING_EXPOSE, "1", "1");
        $attendu = array (
            Main::SCORING_SAME_PIECE[3] . Main::SCORING_MAJEUR . Main::SCORING_EXPOSE => 1
        );
        
        assert($combinaison ->getNom() == $attendu, "attendu : \n" . print_r($attendu, true) . "\n eu : \n " . print_r($combinaison ->getNom(), true));
        
    }
    template();
    
    function test1(){
        global $test_u;
        echo "test n°" . $test_u . " : \n";
        $test_u += 1;
        
        $pieces = array (
            "EV", "EV", "EV"
        );
        
        $combinaison = new Combinaison($pieces, Main::SCORING_EXPOSE, "1", "1");
        $attendu = array (
            Main::SCORING_SAME_PIECE[3] . Main::SCORING_MAJEUR_D_V . Main::SCORING_V_JOUEUR . Main::SCORING_V_DOMINNANT . Main::SCORING_EXPOSE => 1
        );
        
        assert($combinaison ->getNom() == $attendu, "attendu : \n" . print_r($attendu, true) . "\n eu : \n " . print_r($combinaison ->getNom(), true));
        
    }
    
    test1();
    
    
    function test2(){
        global $test_u;
        echo "test n°" . $test_u . " : \n";
        $test_u += 1;
        
        $pieces = array (
            "1R", "2R", "3R"
        );
        
        $combinaison = new Combinaison($pieces, Main::SCORING_EXPOSE, "1", "1");
        $attendu = array (
            Main::SCORING_SUITE . Main::SCORING_EXPOSE=> 1
        );
        
        assert($combinaison ->getNom() == $attendu, "attendu : \n" . print_r($attendu, true) . "\n eu : \n " . print_r($combinaison ->getNom(), true));
        
    }
    
    test2();
    
    function test3(){
        global $test_u;
        echo "test n°" . $test_u . " : \n";
        $test_u += 1;
        
        $pieces = array (
            "1S", "2S", "3S"
        );
        
        $combinaison = new Combinaison($pieces, Main::SCORING_EXPOSE, "1", "1");
        $attendu = array (
            Main::SCORING_HONNEUR . Main::SCORING_EXPOSE=> 2,
            Main::SCORING_HONNEUR . Main::SCORING_V_JOUEUR . Main::SCORING_EXPOSE => 1
        );
        
        assert($combinaison ->getNom() == $attendu, "attendu : \n" . print_r($attendu, true) . "\n eu : \n " . print_r($combinaison ->getNom(), true));
        
    }
    
    test3();
    
}

RedistributionTest();

?>