<?php

// affiche les pieces de mahjong et les area corespondantes
// carre toujours de la meme taille
function genereImg()
{
    $x_init = 128;
    $y_init = 60;
    $width = 58;
    $height = 74;
    // espace entre les tuiles
    $espace_w = 1;
    $espace_h = 6;

    $x = $x_init;
    $y = $y_init;

    ?>
<map id="map_pieces_majong" name="map_pieces_majong">
    <?php
    // on genere les 3 * 9 premiere pieces
    for ($line = 1; $line <= 3; $line ++) {
        for ($colonne = 1; $colonne <= 9; $colonne ++) {
            $nom = "";
            $premier_lettre = "";

            switch ($line) {
                case 1:
                    $nom = "Charactère";
                    $premier_lettre = "C";
                    break;
                case 2:
                    $nom = "Cercle";
                    $premier_lettre = "R"; // pour rond
                    break;
                case 3:
                    $nom = "Bambou";
                    $premier_lettre = "B";
                    break;
            }
            ?>
            <area id="areaP:<?php echo $colonne . $premier_lettre;?>"
		shape="rect"
		coords="<?php echo $x . "," . $y . "," . ($x + $width) . "," . ($y + $height);?>"
		alt="<?php echo $colonne . " de " . $nom;?>"
		title="<?php echo $colonne . " de " . $nom;?>"
		>
            
            
            <?php
            $x += $width + $espace_w;
        }
        $x = $x_init;
        $y += $height + $espace_h;
    }

    // on genere les vents
    for ($colonne = 1; $colonne <= 4; $colonne ++) {
        $nom = "";
        $voyelle = false;

        switch ($colonne) {
            case 1:
                $nom = "Est";
                $voyelle = true;
                break;
            case 2:
                $nom = "Sud";
                break;
            case 3:
                $nom = "Ouest";
                $voyelle = true;
                break;
            case 4:
                $nom = "Nord";
                break;
        }
        ?>
            <area id="areaP:<?php echo substr($nom, 0, 1) . "V";?>"
		shape="rect"
		coords="<?php echo $x . "," . $y . "," . ($x + $width) . "," . ($y + $height);?>"
		alt="<?php echo "vent " . ($voyelle ? "de l'" : "du ") . $nom;?>"
		title="<?php echo "vent " . ($voyelle ? "de l'" : "du ") . $nom;?>">
            
        <?php
        $x += $width + $espace_w;
    }

    $x = $x_init;
    $y += $height + $espace_h;

    // dragons
    for ($colonne = 1; $colonne <= 3; $colonne ++) {
        $nom = "";

        switch ($colonne) {
            case 1:
                $nom = "Rouge";
                break;
            case 2:
                $nom = "Vert";
                break;
            case 3:
                $nom = "Blanc";
                break;
        }
        ?>
            <area id="areaP:<?php echo substr($nom, 0, 1) . "D";?>"
		shape="rect"
		coords="<?php echo $x . "," . $y . "," . ($x + $width) . "," . ($y + $height);?>"
		alt="<?php echo "dragon " . $nom;?>"
		title="<?php echo "dragon " . $nom;?>">
            
        <?php
        $x += $width + $espace_w;
    }

    $x = $x_init;
    $y += $height + $espace_h;

    // on genere les fleurs
    for ($colonne = 1; $colonne <= 4; $colonne ++) {
        ?>
            <area id="areaP:<?php echo $colonne . "F";?>" shape="rect"
		coords="<?php echo $x . "," . $y . "," . ($x + $width) . "," . ($y + $height);?>"
		alt="<?php echo "fleur " . $colonne;?>"
		title="<?php echo "fleur " . $colonne;?>">
            
        <?php
        $x += $width + $espace_w;
    }

    $x = $x_init;
    $y += $height + $espace_h;

    // on genere les season
    for ($colonne = 1; $colonne <= 4; $colonne ++) {
        ?>
            <area id="areaP:<?php echo $colonne . "S";?>" shape="rect"
		coords="<?php echo $x . "," . $y . "," . ($x + $width) . "," . ($y + $height);?>"
		alt="<?php echo "Saison " . $colonne;?>"
		title="<?php echo "Saison " . $colonne;?>">
            
        <?php
        $x += $width + $espace_w;
    }
    ?>
    </map>
<img id="img_mahjong_piece_map" usemap="#map_pieces_majong"
	alt="pieces de mahjong" src="ressources/images/pieces_mahjong.jpg">
<?php
}

genereImg();
?>



