<div class="row">
	<h1>noms :</h1>
	<div class="col-md-10" id="input_nom_joueurs">
		<div class="row">
        	<?php for($i = 1 ; $i <= 4 ; $i++){?>
        		<div class="col-md-3 form-group">
        			<label for="nomJ<?php echo $i;?>">joueur n°<?php echo $i;?> : </label>
        			<input id="nomJ<?php echo $i;?>" type="text" class="form-control"
        				name="nomJ<?php echo $i;?>" value="<?php if(isset($_POST["nomJ" . $i])){echo $_POST["nomJ" . $i];}?>">
        
        			<div class="has-error has-feedback selectVentJAlert">
        				<select class="form-control" name="ventJ<?php echo $i;?>"
        					id="ventJ<?php echo $i;?>">
        					<option value="1" <?php if($aff_vent_joueur[$i - 1] == 1){?>selected<?php }?>>Est</option>
        					<option value="2" <?php if($aff_vent_joueur[$i - 1] == 2){?>selected<?php }?>>Sud</option>
        					<option value="3" <?php if($aff_vent_joueur[$i - 1] == 3){?>selected<?php }?>>Ouest</option>
        					<option value="4" <?php if($aff_vent_joueur[$i - 1] == 4){?>selected<?php }?>>Nord</option>
        				</select> <span class="help-block">ce vent est déjà pris</span>
        			</div>
    			</div>
        	<?php }?>
    	</div>
    		<div class="row infoContenantJButton">
				<div class="col-lg-6 infoJButton"
					style="text-align: center;">
						<button type="button" id="ventJHasard" class="btn btn-primary">
						vent des joueurs
							au hasard
							<span class="glyphicon glyphicon-repeat"></span>
						</button>
				</div>
				<div class="col-lg-6 infoJButton"
					style="text-align: center;">
						<button type="button" id="ventJDecalage" class="btn btn-primary">décaler les vents des joueurs
							<span class="glyphicon glyphicon-arrow-right"></span>
						</button>
				</div>
			</div>
		
	</div>
	<div class="col-md-2">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">vent dominant</h3>
			</div>
			<div id="ventDominantPanelBody" class="panel-body">
				<div class="input-group-btn">
					<?php //pour le retour, on sauvegarde le vent dominant
					if(isset($_POST["ventDominant"])){?>
						<input type="hidden" name="ventDominantPrec" value="<?php echo $_POST["ventDominant"];?>">
					<?php }?>
					<?php //pour le javascript => on ne retire pas au hasard le vent dominant quand on a demander un retour
					if(isset($_POST["retourNouvelleDonne"])){?>
						<div id="button_retour_activer_pas_vent_d_hasard"></div>
					<?php }?>
					
					<select class="form-control" name="ventDominant" id="ventDominant">
						<option value="1" <?php if(isset($_POST["ventDominantPrec"]) && (isset($_POST["retourNouvelleDonne"]) && 
						    $_POST["ventDominantPrec"] == 1)){?>selected<?php }?>>Est</option>
						<option value="2" <?php if(isset($_POST["ventDominantPrec"]) && (isset($_POST["retourNouvelleDonne"]) && 
						    $_POST["ventDominantPrec"] == 2)){?>selected<?php }?>>Sud</option>
						<option value="3" <?php if(isset($_POST["ventDominantPrec"]) && (isset($_POST["retourNouvelleDonne"]) && 
						    $_POST["ventDominantPrec"] == 3)){?>selected<?php }?>>Ouest</option>
						<option value="4" <?php if(isset($_POST["ventDominantPrec"]) && (isset($_POST["retourNouvelleDonne"]) && 
						    $_POST["ventDominantPrec"] == 4)){?>selected<?php }?>>Nord</option>
					</select>
					<button type="button" id="dominantHasard" class="btn btn-primary">
						<span class="glyphicon glyphicon-repeat"></span>
					</button>
				</div>
			</div>
		</div>

		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Mahjong</h3>
			</div>
			<div id="mahjongPanelBody" class="panel-body">
				<div class="input-group-btn">
					<select class="form-control" name="joueurMahjong"
						id="selectJoueurMahjong">
						<option value="1" class="nomJoueur1">Joueur n°1</option>
						<option value="2" class="nomJoueur2">Joueur n°2</option>
						<option value="3" class="nomJoueur3">Joueur n°3</option>
						<option value="4" class="nomJoueur4">Joueur n°4</option>
					</select>
				</div>
				
				<!-- Mahjong spéciaux -->
				<?php if(isset($_POST["mahjongSpecial"])){//pour les retour arriere?>
					<input type="hidden" name="mahjongSpecialPrecedent" value="<?php echo $_POST["mahjongSpecial"];?>">
				<?php }?>
				<select class="form-control" name="mahjongSpecial"
						id="mahjongSpecial">
					<?php 
					   $selected_g = false;
					   foreach(Main::NON_DETECTABLE as $value){
					       $value_format = preg_replace("#\s#", "_", $value);//aime pas les espaces
					       $selected = isset($_POST["retourNouvelleDonne"]) && $_POST["mahjongSpecialPrecedent"] == $value_format;
					       $selected_g = $selected_g || $selected;
					    ?>
						<option value="<?php echo $value_format;?>" <?php if($selected){?>selected<?php }?>>
								<?php echo $value;?>
						</option>
					<?php }?>
						<option value="" <?php if(!$selected_g){?>selected<?php }?>>autre</option>
				</select>
				
			</div>
		</div>
	</div>
</div>