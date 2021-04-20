<?php

// génére le tableau des scores

/**
 * 
 * @param string $joueur le joueur
 * @param string $etat etat parmis Main::SCORING_EXPOSE, Main::SCORING_CACHE, Main::SCORING_HONNEUR
 * @param array $img le tableau des images sous forme $etat => array(nb combi => array(piece)) (par default vide)
 */
function genereInputPieceNewDonne(string $joueur, string $etat, array $img = array())
{  
    if(count($img) > 0){
        $combi = count($img[$etat]);
        $piece = 0;
        
        for($i_combi = 1 ; $i_combi <= $combi ; $i_combi ++){
            $max_piece = count($img[$etat][$i_combi - 1]);
            ?>
            
            <div id="<?php echo genIdCombinaison($joueur * 1, $etat, $i_combi);?>" class="combinaison">
            
            	<?php for($piece = 1 ; $piece <= $max_piece ; $piece++) { // piece?>
                    <span class="input_piece_new_donne input_piece_new_donne_with_image"> 
                    	<input name="<?php echo genIdInput($joueur * 1, $etat, $i_combi, $piece);?>" 
                        	id="<?php echo genIdInput($joueur * 1, $etat, $i_combi, $piece);?>" 
                        	type="text" maxlength="2" size="2" 
                        	hidden="hidden" value="<?php echo $img[$etat][$i_combi - 1][$piece - 1];?>">
                    	
                    	<img alt="<?php echo $img[$etat][$i_combi - 1][$piece - 1];?>" class="img<?php echo $img[$etat][$i_combi - 1][$piece - 1];?>"
                    			src="ressources/images/indiv/<?php echo $img[$etat][$i_combi - 1][$piece - 1];?>.png">
                    </span>
                <?php }?>
                <span class="input_piece_new_donne"> 
                	<input name="<?php echo genIdInput($joueur * 1, $etat, $combi, $piece);?>"
            		id="<?php echo genIdInput($joueur * 1, $etat, $combi, $piece);?>"
            		type="text" maxlength="2" size="2" class="form-control last">
            
            		<button type="button" class="btn btn-primary supprPiece" value="1">
            			<span class="glyphicon glyphicon-remove"></span>
            		</button> <img alt="" src="" hidden="hidden">
            	</span>
                
            </div>
        <?php }
    }else{
        $combi = 0;
        $piece = 0;
    }
        $combi++;
        $piece ++;?>
<div id="<?php echo genIdCombinaison($joueur * 1, $etat, $combi);?>"
	class="combinaison last">
	<span class="input_piece_new_donne"> <input
		name="<?php echo genIdInput($joueur * 1, $etat, $combi, 1);?>"
		id="<?php echo genIdInput($joueur * 1, $etat, $combi, 1);?>"
		type="text" maxlength="2" size="2" class="form-control last">

		<button type="button" class="btn btn-primary supprPiece" value="1">
			<span class="glyphicon glyphicon-remove"></span>
		</button> <img alt="" src="" hidden="hidden">
	</span>
</div>
<?php
}
?>




<!-- pour cloner -->
<div id="combiClone" class="combinaison last" style="display:none;">
    <span id="inputClone" class="input_piece_new_donne"> 
    	<input name="" id="" type="text"
    	maxlength="2" size="2" class="form-control last">
    	
        <button type="button"
        	class="btn btn-primary supprPiece" value="1">
        	<span class="glyphicon glyphicon-remove"></span>
        </button> 
    	
    	<img alt="" src="" hidden="hidden">
    </span>
</div>

<!-- tableau a proprement parler -->
<section id="section_tab_new_donne" class="col-lg-12 table-responsive">
	<table id="tab_new_donne"
		class="table table-bordered table-striped table-condensed">
		<thead>
			<tr>
				<th>joueurs</th>
				<th class="pieces_decouvre">pieces découvertes</th>
				<th class="pieces_cache">pieces cachées</th>
				<th class="pieces_bonus">pieces bonus</th>
			</tr>
		</thead>
		<tbody>		
    			<?php
    // pour chaque joueur, on genere une ligne
    for ($joueur = 0; $joueur < 4; $joueur ++) {
        ?>
    		<tr id="tab_new_donne_tr_j-<?php echo $joueur + 1;?> tab_new_donne_body">
				<th class="nomJoueur<?php echo $joueur + 1;?> nomj">joueur n°<?php echo $joueur + 1;?></th>
				<td id="caseNewDonneDecou:<?php echo $joueur + 1;?>" class="tab_new_donne-combi">
					<?php if(isset($_POST["retourNouvelleDonne"])){
					    genereInputPieceNewDonne(($joueur + 1), Main::SCORING_EXPOSE, $mains[$joueur + 1]);
					}else{
					    genereInputPieceNewDonne(($joueur + 1), Main::SCORING_EXPOSE);
					}?>
    				<div class="tab-new-donne-btn-combi">
                    	 <span class="supprCombi glyphicon glyphicon-minus"></span>
                         <span class ="addCombi glyphicon glyphicon-plus"></span>
					</div>
    			</td>
				<td id="caseNewDonneCache:<?php echo $joueur + 1;?>" class="tab_new_donne-combi">
    				<?php if(isset($_POST["retourNouvelleDonne"])){
					    genereInputPieceNewDonne(($joueur + 1), Main::SCORING_CACHE, $mains[$joueur + 1]);
					}else{
					    genereInputPieceNewDonne(($joueur + 1), Main::SCORING_CACHE);
					}?>
    				<div class="tab-new-donne-btn-combi">
                    	 <span class="supprCombi glyphicon glyphicon-minus"></span>
                         <span class ="addCombi glyphicon glyphicon-plus"></span>
					</div>
    			</td>
				<td id="caseNewDonneBonus:<?php echo $joueur + 1;?>" class="tab_new_donne-combi">
    				<?php if(isset($_POST["retourNouvelleDonne"])){
					    genereInputPieceNewDonne(($joueur + 1), Main::SCORING_HONNEUR, $mains[$joueur + 1]);
					}else{
					    genereInputPieceNewDonne(($joueur + 1), Main::SCORING_HONNEUR);
					}?>
    				<div class="tab-new-donne-btn-combi">
                    	 <span class="supprCombi glyphicon glyphicon-minus"></span>
                         <span class ="addCombi glyphicon glyphicon-plus"></span>
					</div>
    			</td>
			</tr>
    			    <?php
    }
    ?>
    		</tbody>
		</table>
	</section>



