<?php if(isset($_POST["nouvelleDonneConfirmer"])){?>
<section class="col-lg-12 table-responsive">
	<div id="annonce_detail_manche">
		<span class="glyphicon glyphicon-chevron-right"></span>
		<span >details de la manche n°<?php echo $aff_nb_manche;?></span>
		<span class="glyphicon glyphicon-chevron-right"></span>
	</div>
	<div id="contient_tab_detail_manche">
    	<table id="tab_detail_manche"
    		class="table table-bordered table-striped table-condensed">
    		<thead>
    			<tr>
    				<th colspan="2"></th>
    				<th class="nomJoueur1">joueur n°1</th>
    				<th class="nomJoueur2">joueur n°2</th>
    				<th class="nomJoueur3">joueur n°3</th>
    				<th class="nomJoueur4">joueur n°4</th>
    			</tr>
    		
    		</thead>
    		<tbody>
    			<!-- mains -->
    			<?php 
    			/**
    			 * 
    			 * @param string $etat l'etat 
    			 * @param array mains les mains des joueurs
    			 * @note affiche les mains correspondantes à l'etats
    			 */
    			function afficheMain (string $etat, array $mains) : void {
    			    for($j = 1 ; $j <= 4 ; $j ++){
    			        echo "<td>";
    				    $max_combi = count($mains[$j][$etat]);
    				    for($i = 0 ; $i < $max_combi ; $i++){
    				        $max_piece = count($mains[$j][$etat][$i]);
    				        $chaine = "";
    				    	for($p = 0 ; $p < $max_piece ; $p ++){
    				    	    if($p != 0){
    				    	        $chaine .= ", ";
        				    	}
        				    	$chaine .= $mains[$j][$etat][$i][$p];
    				    	}
    				    	echo $chaine;
    				    	echo '<input name="prec' . genIdCombinaison($j, $etat, $i + 1) . '" id="prec' . genIdCombinaison($j, $etat, $i + 1) . '" type="hidden" value="' . $chaine . '">';
    				    
    				    	echo "<br/>";
    				   }
    				   echo "</td>";	
    			    }
    			}
    			?>
    			
    			<tr>
    				<td rowspan="3" class="titre-ligne">mains</td>
    				<td>ouvertes</td>
    				<?php afficheMain(Main::SCORING_EXPOSE, $mains);?>
    			</tr>
    			<tr>
    				<td>cachées</td>
    				<?php afficheMain(Main::SCORING_CACHE, $mains);?>
    			</tr>
    			<tr>
    				<td>honneurs</td>
    				<?php afficheMain(Main::SCORING_HONNEUR, $mains);?>
    			</tr>
    			
    			
    			
    			
    			<!-- combinaisons -->
    			<tr>
    				<td colspan="2" class="titre-ligne">combinaisons</td>
    				<?php for($j = 1; $j <= 4 ; $j++){?>
    				    <td>
    				    	<table class="table table-bordered table-striped table-condensed">
    				    		<thead>
    				    			<tr>
    				    				<th rowspan="2" style="vertical-align: middle;">nom</th>
    				    				<th rowspan="2" style="vertical-align: middle;">q</th>
            							<th rowspan="2" style="vertical-align: middle;">état</th>
            							<th colspan="2">points</th>
    				    			</tr>
    				    			<tr>
    				    				<th><span class="glyphicon glyphicon-plus"></span></th>
    				    				<th><span class="glyphicon glyphicon-remove"></span></th>
    				    			</tr>
    				    		</thead>
    				    		<tbody>
    				    			<tr>
                                		<th colspan="5" class="categorie">
                                			combinaisons
                                		</th>
                                	</tr>
                				    <?php 
                				    $scoring_rule = $manche_object ->getJoueur($j) ->getScoringRuleManche();
                				    $string_mahjong = "";
                				    foreach($scoring_rule as $nom => $value){
                				        if(!preg_match("#[a-zA-Z\'\s19]*" . Main::SCORING_MULT . "#", $nom)){//si on est dans le cans d'un autre, on l'enleve
                				            //on determine la constante initiale, ainsi que le nombre de remplacement pour afficher le glyphicon
                				            $nb_expose = 0;
                				            $const = preg_replace("#" . Main::SCORING_EXPOSE . "#", "", $nom, -1, $nb_expose);
                				            $nb_invis = 0;
                				            $const = preg_replace("#" . Main::SCORING_CACHE . "#", "", $const, -1, $nb_invis);
                				            if(!isset(SCORING_RULE[$const])){
                				                ob_start();
                				            }
                				            ?>
                				            <tr>
                				            	<td><?php echo $const;?></td>
                				            	<td style="text-align: center;"><?php echo $value;?></td>
                				            	
                				            	<?php if($nb_expose > 0 || isset(SCORING_RULE_MAHJONG[$const])){?>
                				            	
                				            		<td style="text-align: center;"><span class="glyphicon glyphicon-eye-open"></span></td>
                				            		
                				            		<?php if(isset(SCORING_RULE[$const])){?>
                				            			<td><?php echo SCORING_RULE[$const][0]?></td>
                				            			<td><?php echo SCORING_RULE[$const][2]?></td>
                				            		<?php }else{?>
                				            			<td><?php echo SCORING_RULE_MAHJONG[$const][0]?></td>
                				            			<td><?php echo SCORING_RULE_MAHJONG[$const][2]?></td>
                				            		<?php }?>
                				            	<?php }else{?>
                				            	
                				            		<td style="text-align: center;"><span class="glyphicon glyphicon-eye-close"></span></td>
                				            		
                				            		<td><?php echo SCORING_RULE[$const][1]?></td>
                				            		<td><?php echo SCORING_RULE[$const][2]?></td>
                				            		
                				            	 <?php }?>
                				            </tr>
                				            <?php if(!isset(SCORING_RULE[$const])){
                				                $string_mahjong .= ob_get_clean();
                				            }
                				            
                				        }//if MULT
                				    }
                				    ?>
                				    <tr>
            							<th colspan="5" class="categorie">
                                			Mah-jong
                                		</th>
                                	</tr>
                				    <?php echo $string_mahjong; ?>
            				    </tbody>
    				    	</table>
    				    </td>
    				<?php }?>
    			
    			</tr>
    			<!-- score de manches avant redistributions -->
    			<tr>
    				<td colspan="2" class="titre-ligne">scores avant redistribution</td>
    				<?php for($j = 1; $j <= 4 ; $j++){
    				    
    				    echo "<td>" . $manche_object ->getJoueur($j) ->getScoreManche() . "</td>";
    				    
    				}?>
    			
    			</tr>
    		
    		
    		</tbody>
    	</table>
	</div>
</section>
<?php }?>