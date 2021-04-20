<script type="text/javascript">
	console.log(Date.now());

    <?php require 'node_modules/jquery/dist/jquery.min.js';?>
    <?php require 'bootstrap-3.3.7/js/bootstrap.min.js';?>

    <?php require 'web_content/view/js/gestion_navbar.js';?>
	<?php
		if(DEBUG){
			require 'web_content/view/js/gestion_area_piece.js';
		}else{
			require 'web_content/view/js/min/gestion_area_piece.min.js';
		}
	?>
	<?php require 'web_content/view/js/info_joueur_gestion.js';?>
	<?php require 'web_content/view/js/gestion_submit.js';?>
	<?php require 'web_content/view/js/gestion_detail_manche.js';?>
	<?php require 'web_content/view/js/sommaire_pdf.js';?>
	
$(document).ready(function() {
	console.log(Date.now());
});
</script>


<!-- 
<script type="text/javascript"
	src="node_modules/jquery/dist/jquery.min.js"></script>





<script type="text/javascript" src="bootstrap-3.3.7/js/bootstrap.min.js"></script>




<script type="text/javascript" 
	src="web_content/view/js/gestion_navbar.js"></script>
<script type="text/javascript"
	src="web_content/view/js/min/gestion_area_piece.min.js"></script>
<script type="text/javascript"
	src="web_content/view/js/info_joueur_gestion.js"></script>
<script type="text/javascript"
	src="web_content/view/js/gestion_submit.js"></script>
<script type="text/javascript"
	src="web_content/view/js/gestion_detail_manche.js"></script>
<script type="text/javascript"
	src="web_content/view/js/sommaire_pdf.js"></script>
!-->