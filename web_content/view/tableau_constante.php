
<?php 

function affArrayScoringRule(array $tab, bool $modif) : void {
    
    foreach ($tab as $nom => $valeur){ ?>
        	    <tr>
        	    	<td><?php echo $nom;?></td>
        	    	<?php for($i = 0 ; $i <= 2 ; $i ++){
        	    	    $etat = "";
        	    	    switch($i){
        	    	        case 0:
        	    	            $line = "Visible";
        	    	            break;
        	    	        case 1:
        	    	            $line = "Cache";
        	    	            break;
        	    	        case 2:
        	    	            $line = "Multiplicateur";
        	    	            break;
        	    	            
        	    	    }
        	    	    //les posts n'aime pas les espaces : 
        	    	    $nom = preg_replace("#\s#", "_", $nom);
        	    	    ?>
        	    	<td>
        	    		<?php if($modif){?>
        	    			<input name="<?php echo $nom . $line;?>" type="text"
    							maxlength="2" size="2" class="form-control" 
    							value="<?php echo $valeur[$i] == null || 
    							             ($line != "Multiplicateur" && $valeur[$i] == 0) ||
    							             ($line == "Multiplicateur" && $valeur[$i] == 1) ? "" : $valeur[$i];?>"
    							>
        	    		<?php }else{?>
        	    			<?php echo $valeur[$i] == null || 
    							             ($line != "Multiplicateur" && $valeur[$i] == 0) ||
    							             ($line == "Multiplicateur" && $valeur[$i] == 1) ? "" : $valeur[$i];?>
        	    		<?php }?>
        	    		
        	    	</td>
        	    <?php }?>
        	    </tr>
    <?php } 
    
}
?>



<div class="row">
	<div class="col-lg-12">
    	<h3 style="float: left;">coefficients :</h3> 
    	<?php if(!isset($_POST["modifConstante"])){?>
        	<!-- pour modifier les constantes -->
        	<button style="float: right;margin-top:15px;" type="submit" id="modifConstante" name="modifConstante" class="btn btn-primary" value="1">
                 <span class="glyphicon glyphicon-pencil"></span>
            </button>
        <?php }?>
    </div>
</div>
<?php //on affiche le champ de mot de passe pour pouvoir changer les constantes
if(isset($_POST["modifConstante"])){?>
<div class="row">
	<div class="col-lg-12">
		<?php if(isset($mdp_constante) && !$mdp_constante){?>
		<div class="has-error has-feedback">
		<?php }?>
        	<div class="form-group">
              <label for="mdp_constantes">mot de passe :</label>
              <input id="mdp_constantes" name="mdp_constantes" type="password" class="form-control">
            </div>
        <?php if(isset($mdp_constante) && !$mdp_constante){?>
            <span class="help-block">Le mot de passe est incorrect.</span>
        </div>
        <?php }?>
    </div>
</div>
<?php }?>
<div class="row">
    <section class="col-lg-12 table-responsive">
        <table id="tab_constantes"
            class="table table-bordered table-striped table-condensed">
            <thead>
            	<tr>
            		<th>nom de la règle</th>
            		<th><span class="glyphicon glyphicon-eye-open"></span></th>
            		<th><span class="glyphicon glyphicon-eye-close"></span></th>
            		<th><span class="glyphicon glyphicon-remove"></span></th>
            	
            	
            	</tr>
            </thead>
            <tbody>
                <!-- combinaison normales -->
            	<tr>
            		<th colspan="4" class="categorie">
            			combinaisons
            		</th>
            	</tr>
            	
            	
            	<?php affArrayScoringRule(SCORING_RULE, isset($_POST["modifConstante"]));?>
            	
            	
            	
            	<!-- mahjong -->
            	<tr>
            		<th colspan="4" class="categorie">
            			mah-jong
            		</th>
            	</tr>
            
            	<?php affArrayScoringRule(SCORING_RULE_MAHJONG, isset($_POST["modifConstante"]));?>
            
            </tbody>
        </table>
    </section>
</div>
<?php //on affiche le boutton
if(isset($_POST["modifConstante"])){?>
<div class="row">
	<div class="col-lg-12">
		<div style="float: right;">
    		<?php if(isset($mdp_constante) && !$mdp_constante){?>
    		<div class="has-error has-feedback">
    		<?php }?>
            	<button type="submit" id="modifConstanteValider" name="modifConstanteValider" class="btn btn-primary" value="1">
                	valider les modifications
                </button>
            <?php if(isset($mdp_constante) && !$mdp_constante){?>
                <span class="help-block">Le mot de passe est incorrect.</span>
            </div>
            <?php }?>
        </div>
    </div>
</div>
<?php }?>



