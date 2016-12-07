<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"/>
	<meta name="robots" content="noindex">
	<title>MAGAM manager - Opérations</title>
	<link rel="icon" type="image/png" href="images/favicon.png" />
</head>
<body>
	
	<?php
	$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
	$bdd = new PDO('mysql:host=localhost;dbname=mfa', 'root', '', $pdo_options);
	
	include "calculateur.php";
	include "init_values.php";
	session_start();
	$_SESSION['page'] = "recommandation";
	include "functions.php";
	include "menu.php";
	$CALCUL = 0;
	$FUSION = 1;
	$SELECTION = 2;
	?>
	
	
	<?php 
		include "recommandation.operations.php";
		include "get_visu.php";
	?>
	
	<fieldset> <!-- tous les contenus -->
	
		<!-- légende (calculs ou fusions ou selections) -->
		<legend class="multi">
		<a 
			href="recommandation.php?affiche=calculs" class=" 
			<?php if(!(isset($_GET['affiche'])) || $_GET['affiche']!="calculs") echo "bouton_lvl2"; else echo "legende";?> first"
			>Appliquer des <span class=bold>Calculs</span>
		</a>
		<a 
			href="recommandation.php?affiche=fusions" class="
			<?php if(!(isset($_GET['affiche'])) || $_GET['affiche']!="fusions") echo "bouton_lvl2"; else echo "legende";?>"
			>Appliquer des <span class=bold>Fusions</span>
		</a>
		<a 
			href="recommandation.php?affiche=selections" class="
			<?php if(!(isset($_GET['affiche'])) || $_GET['affiche']!="selections" || isset($_GET['rec'])) echo "bouton_lvl2"; else echo "legende";?>"
			>Appliquer une <span class=bold>Sélection</span>
		</a>
		<a 
			href="recommandation.php?affiche=selections&rec=yes" class="
			<?php if(!(isset($_GET['affiche'])) || $_GET['affiche']!="selections" || !(isset($_GET['rec']))) echo "bouton_lvl2"; else echo "legende";?> last"
			>Voir les <span class=bold>Recommandations</span>
		</a>
		</legend>
		
		<table class="espace_left"> <!-- tous les contenus -->
		
		<!-- Création des valeurs lorsqu'elles sont absentes -->
		<?php
		initialiser_valeurs("utilisateurs", $bdd);
		initialiser_valeurs("activites", $bdd);
		initialiser_valeurs("croisees", $bdd);
		//spécifier qu'on a plus besoin de l'initialisation
		try {
			$Q='UPDATE `bd` SET `new_entity` = 0 WHERE id = "'.$_SESSION['bd'].'"';
			$bdd->exec($Q);
		}
		catch(Exception $e) {
			die('Erreur : '.$e->getMessage());
		}
		?>
		
		<?php 
			if(isset($_GET['affiche']))
				include "visu.php";	
		?>
			
			<!-- récupération des valeurs dans $valeurs['util'], $valeurs['acti'] et $valeurs['croi']--> <?php
				unset($valeurs);
				try
				{
					//récupération des valeurs sur les utilisateurs
					$liste_val = $bdd->query('SELECT id_utilisateur, id_propriete, valeur 
												FROM valeur_utilisateur');
					while ($l_val = $liste_val->fetch())
						$valeurs['util'][$l_val['id_utilisateur']][$l_val['id_propriete']] = $l_val['valeur'];
					//récupération des valeurs sur les activités
					$liste_val = $bdd->query('SELECT id_activite, id_propriete, valeur FROM valeur_activite');
					while ($l_val = $liste_val->fetch())
						$valeurs['acti'][$l_val['id_activite']][$l_val['id_propriete']] = $l_val['valeur'];
					//récupération des valeurs croisees
					$liste_val = $bdd->query('SELECT id_activite, id_utilisateur, valeur FROM valeur_croisee');
					while ($l_val = $liste_val->fetch())
						$valeurs['croi'][$l_val['id_activite']][$l_val['id_utilisateur']] = $l_val['valeur'];
				}
				catch(Exception $e)
				{
					echo '<span class=rouge>erreur sur la récupération des valeurs :</span>';
					die('Erreur : '.$e->getMessage());
				}
			?>
			
			<?php
			$ready = true;
			try
			{
				$prop = $bdd->query('SELECT count(*) as nb FROM propriete JOIN groupe_propriete ON propriete.id_groupe = groupe_propriete.id WHERE bd = "'.$_SESSION['bd'].'"');
				$nb_prop = $prop->fetch();
				if($nb_prop['nb'] == 0)
				{
					echo '<span class=rouge>/!\ Vous devez définir des propriétés avant de pouvoir appliquer des calculs, fusions ou sélections.</span><br/>';
					$ready = false;
				}
				$acti = $bdd->query('SELECT count(*) as nb FROM activite JOIN groupe_activite ON activite.id_groupe = groupe_activite.id WHERE bd = "'.$_SESSION['bd'].'"');
				$nb_acti = $acti->fetch();
				if($nb_acti['nb'] == 0)
				{
					echo '<span class=rouge>/!\ Vous devez définir des activités avant de pouvoir appliquer des calculs, fusions ou sélections.</span><br/>';
					$ready = false;
				}
				$util = $bdd->query('SELECT count(*) as nb FROM utilisateur JOIN groupe_utilisateur ON utilisateur.id_groupe = groupe_utilisateur.id WHERE bd = "'.$_SESSION['bd'].'"');
				$nb_util = $util->fetch();
				if($nb_util['nb'] == 0)
				{
					echo '<span class=rouge>/!\ Vous devez définir des utilisateurs avant de pouvoir appliquer des calculs, fusions ou sélections.</span><br/>';
					$ready = false;
				}
			}
			catch(Exception $e)
			{
				echo '<span class=rouge>erreur pendant le comptage des propriétés / activités / utilisateurs : </span>';
				die('Erreur : '.$e->getMessage());
			}
			?>
				
			<?php
				if($ready==true)
				{
					if($user_visu=="")
						echo '<span class="rouge"> Vous devez confirmer le choix de l\'utilisateur de visualisation des opérations pour continuer. </span>';
					else
					{
						include "recommandation.calcul.php"; //visualiser les calculs
						$ready_fusion = true;
						try
						{
							$calc = $bdd->query('SELECT count(*) as nb FROM application 
												JOIN groupe_propriete ON application.id_groupe_pr = groupe_propriete.id 
												JOIN operation ON application.id_operation = operation.id
												WHERE groupe_propriete.bd = "'.$_SESSION['bd'].'" AND operation.type_operation = '.$CALCUL.'');
							$nb_calc = $calc->fetch();
							if($nb_calc['nb'] == 0)
							{
								$ready_fusion = false;
								if($_GET['affiche'] == "fusions" || $_GET['affiche'] == "selections")
									echo '<span class="rouge"> Vous devez définir des calculs avant d\'appliquer des fusions et sélections. </span>';
							}
						}						
						catch(Exception $e)
						{
							echo '<span class=rouge>erreur pendant le comptage des calculs : </span>';
							die('Erreur : '.$e->getMessage());
						}
						if($ready_fusion)
						{
							include "recommandation.fusion.php"; //visualiser les fusiosn
							include "recommandation.selection.php"; //visualiser les transformations
						}
					}
				}
			?>
			
		</table>
	</fieldset>
	<br>
	
</body>
</html>
