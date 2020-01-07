<table id="tableau_score" class="table table-bordered table-striped table-condensed">
	<thead>
		<tr>
			<th>manches</th>
			<th class="nomJoueur1">Joueur n°1</th>
			<th class="nomJoueur2">Joueur n°2</th>
			<th class="nomJoueur3">Joueur n°3</th>
			<th class="nomJoueur4">Joueur n°4</th>
			<th>suppr</th>
		</tr>
	</thead>
	<tbody>
		<!-- score total -->
		<tr class="total">
			<td>total</td>
			<td><?php echo $aff_score_totaux[0];?><input type="hidden" name="score_totalJ1" value="<?php echo $aff_score_totaux[0];?>"></td>
			<td><?php echo $aff_score_totaux[1];?><input type="hidden" name="score_totalJ2" value="<?php echo $aff_score_totaux[1];?>"></td>
			<td><?php echo $aff_score_totaux[2];?><input type="hidden" name="score_totalJ3" value="<?php echo $aff_score_totaux[2];?>"></td>
			<td><?php echo $aff_score_totaux[3];?><input type="hidden" name="score_totalJ4" value="<?php echo $aff_score_totaux[3];?>"></td>
			<td></td>
		</tr>
		<!-- score manche par manche -->
		<?php
		  //si on a des manches, on les affiches :
		  $max_manches = count($aff_scores_manches);
		  if($max_manches != 0){
    		  for($m = 1 ; $m <= $max_manches ; $m ++){?>
    		      <tr class="tr-pas-total">
    		      		<td><?php echo $m;?></td>
    		      		<?php 
    		      		for($j = 1 ; $j <= 4 ; $j++){ ?>
        		      		    <td>
        		      		    	<?php echo $aff_scores_manches[$m][$j];?>
        		      		    </td>
        		      		<?php }
    		      		?>
    		      		<td>
        		      		<button type="submit" name="supprManche" class="btn btn-primary" value="<?php echo $m;?>">
                    			<span class="glyphicon glyphicon-remove"></span>
                    		</button>
                		</td>
    		      </tr>
    		      
    		  <?php 
     		  }
		  }?>
	
	
	</tbody>

</table>