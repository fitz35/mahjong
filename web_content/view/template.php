<?php $temporisation = ob_get_contents();ob_end_clean();//on libere le flux?>
<!DOCTYPE html>
<head>
<title>mah jonc</title>


<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
    <?php require("web_content/view/css/import_css.php");?>
</head>
<body>
	<header style="margin-left:25px;margin-right:25px;">
    	<nav class="navbar navbar-inverse">
    		<div class="container-fluid">
    			<ul class="nav navbar-nav">
    				<li class="active"><a href="#" id="button-nouvelle-partie">nouvelle partie !</a></li>
    			</ul>
    			<ul class="nav navbar-nav navbar-right">
    				<li class="active"><a href="#" id="button-define-scoring-rule"><span class="glyphicon glyphicon-list"></span></a></li>
    			</ul>
    		</div>
    	</nav>
	</header>
	<?php if(defined("DEBUG") && DEBUG){?>
		<!-- indiquer au javascript qu'on est en debug -->
		<span id="debug" style="display:none;"></span>
	<?php }?>
	
	<div class="container">
		<form id="form_nouvelle_donne" class="form-inline" method="post"
			action="index.php" autocomplete="off">
			<div id="definition-scoring-rules" class="row" style="margin-top:-20px">
				<?php require("web_content/view/tableau_constante.php")?>
			</div>
			
			<div class="row">
				<div class="col-lg-12">
					<!-- on dit au javascript que si on est dans un multiple de 4, on retire les vents au hasard -->
					<h1 id="titre_nb_manche">Manche n°<?php echo $aff_nb_manche + 1;?> : <?php echo getJoueurPlusHautScoreUi($aff_score_totaux);?>
						<span style="display:none;"><?php echo $aff_nb_manche + 1;?></span>
					</h1>
				</div>
			</div>
			
			<?php require("web_content/view/info_joueurs_view.php")?>
			
			
			
        	<div class="row">
				<div class="col-lg-12">
					<h1>Mains des joueurs (cliquer sur les images des pièces à droite)</h1>
					<div class="row">
						<div class="col-lg-8">
            				<?php require("web_content/view/tableau_nouvelle_donne.php");?>
            			</div>
            			<div class="col-lg-4">
            				<?php require "web_content/view/img_mahjong.php";?>
            			</div>
            		</div>
				</div>
			</div>



			<div class="row">
				<div class="col-lg-offset-8 col-lg-3" style="margin-top:10px;text-align:left;">
					<button type="submit" id="nouvelleDonneConfirmer"
						name="nouvelleDonneConfirmer" class="btn btn-primary btn-block">
						valider la donne</button>
				</div>
			</div>
			
			<div class="row" style="margin-top: 10px;">
				<div class="col-lg-push-9 col-lg-3">
					<div id="panel-info-submit" class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">Informations</h3>
						</div>
						<div class="panel-body"></div>
					</div>
				</div>
				
				<section class="col-lg-pull-3 col-lg-9 table-responsive">
					<?php require 'web_content/view/tableau_score.php';?>
				</section>
			
				
			</div>
			
			
			<div class="row">
				<?php require 'web_content/view/tableau_detail_manche.php';?>
			</div>
				
			<?php if(isset($_POST["nouvelleDonneConfirmer"])){?>
			<div class="row">
				<div class="col-lg-offset-11 col-lg-1">
					<button type="submit" id="retourNouvelleDonne" name="retourNouvelleDonne" class="btn btn-primary btn-block">retour</button>
				</div>
			</div>
			<?php }?>


			</form>
	
	<?php require 'web_content/view/regles_view.php';?>
	</div>
	
	<?php 
        if($temporisation != ""){?>
        	<pre id="aff_debug0">
        		<?php echo $temporisation;?>
        		<a href="index.php#aff_debug0">remonter tout en haut</a>
        	</pre>
    <?php }?>
	<?php require("web_content/js/import_js.php");?>
</body>