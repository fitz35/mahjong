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





}

RedistributionTest();

?>