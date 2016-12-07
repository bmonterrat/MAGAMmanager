<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="robots" content="noindex">
	<title>Adaptation Multi Aspects - Propriétés</title>
	<link rel="icon" type="image/png" href="images/favicon.png" />
</head>
<body>	
	
	<?php
	$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
	//$bdd = new PDO('mysql:host=localhost;dbname=mfa', 'root', '', $pdo_options);
	$bdd = new PDO('mysql:host=193.37.145.61;dbname=bmont721766', 'bmont721766', 'd6H4FAGw', $pdo_options); //commenter en local
	
	session_start();
	$_SESSION['page'] = "propriete";
	include "menu.php";
	$test = "test";
	?>
	
	<?php include "propriete.operations.php" ?>
	
	<!-- Affichage de la liste des propriétés --> 
	<fieldset>
		<legend> Propriétés </legend>
		<table id="proprietes_table">
		<?php
			try
			{
				$liste_des_groupes = $bdd->query('SELECT * FROM groupe_propriete WHERE bd = "'.$_SESSION['bd'].'" ORDER BY id');
				while ($donnees_gp = $liste_des_groupes->fetch())
				{
					echo '<tr> <td id="proprietes_table_td">';
					echo '<a href="propriete.php?mod_gp='.$donnees_gp['id'].'" title="modifier le nom de ce groupe" > <img class="small" src="'.$mod.'" alt="[modifier]" /> </a>';
					echo '<a href="propriete.php?suppr_gp='.$donnees_gp['id'].'" title="supprimer ce groupe de propriétés" > <img class="small" src="'.$sup.'" alt="[supprimer]" /> </a>';
					if(isset($_GET['mod_gp']) && $_GET['mod_gp'] == $donnees_gp['id'])
					{
						?> 	<!-- modifier le nom d'un groupe -->
							<form method="post" action="propriete.php" class="inline" id="modifier_nom_groupe">
								<input type="text" name="edit_nom_groupe" size="25" maxlength="80" value="<?php echo $donnees_gp['nom'] ?>" />
								<input type="hidden" name="edit_id_groupe" <?php echo 'value="'.$donnees_gp['id'].'"'; ?> />
								<input class="bouton3" style="text-align: right;" type="submit" value="Valider" title="confirmer le nouveau nom"/>
							</form>
						<?php
					}
					else
						echo '<label class="h2"> '.$donnees_gp['nom'].' : </label>';
					echo '<br>';
					
					$liste_des_proprietes = $bdd->query('SELECT * FROM propriete WHERE id_groupe = '.$donnees_gp['id'].' ORDER BY id');
					while ($donnees_pr = $liste_des_proprietes->fetch())
					{
						echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
						echo '<a href="propriete.php?mod_pr='.$donnees_pr['id'].'" title="modifier cette propriété" > <img class="small" src="'.$mod.'" alt="[modifier]" /> </a>';
						echo '<a href="propriete.php?suppr_pr='.$donnees_pr['id'].'" title="supprimer cette propriété" > <img class="small" src="'.$sup.'" alt="[supprimer]" /> &nbsp </a>';
						if(isset($_GET['mod_pr']) && $_GET['mod_pr'] == $donnees_pr['id'])
						{
							?>	<!-- modifier une propriete -->
								<form method="post" action="propriete.php" class="inline" id="modifier_propriete">
									<input type="text" name="edit_nom_propriete" size="25" maxlength="50" value="<?php echo $donnees_pr['nom'] ?>" />
									<input type="hidden" name="edit_id_propriete" <?php echo 'value="'.$donnees_pr['id'].'"'; ?> />
									<input class="bouton3" type="submit" value="Valider" title="confirmer le nouveau nom"/>
								</form>
							<?php
						}
						else
							echo $donnees_pr['nom'];
						echo '<br>';
					}
					$liste_des_proprietes->closeCursor();
					?> <!-- ajouter une nouvelle propriete -->
						<form method="post" action="propriete.php" id="ajouter_propriete">
							&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
							<input class="gris" type="text" name="new_propriete" size="25" maxlength="50" value="nouvelle proriété" />
							<input name="nouveau_id_gp" type="hidden" <?php echo 'value="'.$donnees_gp['id'].'"'; ?> />
							<input class="bouton2" type="submit" value="Ajouter"  title="ajouter une propriété"/>
						</form>
					</td>
					
					<?php
					if(isset($_GET['mod_gp_details']) && $_GET['mod_gp_details'] == $donnees_gp['id'])
					{
						?>
						<td id="proprietes_table_td">
							<form method="post" action="propriete.php" class="inline" id="modifier_details_groupe">
								<input name="edit_id_gp" type="hidden" <?php echo 'value="'.$donnees_gp['id'].'"'; ?> />
								<input name="edit_details" type="hidden" <?php echo 'value="'.$donnees_gp['id'].'"'; ?> />
								<?php include "details_groupe_pr.php"; ?>
								<input class="bouton3" style="text-align: right;" type="submit" value="Valider" title="Valider les valeurs"/>
							</form>
						</td>
						<?php
					}
					else
					{
						?>
						<td id="proprietes_table_td">
							<?php echo '<a href="propriete.php?mod_gp_details='.$donnees_gp['id'].'" title="modifier les détails de ce groupe" > <img class="small" src="'.$mod.'" alt="[modifier]" /> </a>' ?>
							<span class="h3"><?php echo $donnees_gp['nom'] ?> appliquées aux activités&nbsp;:</span><br>
							<div class="decalage">
							<span class="bold">val min = <?php echo $donnees_gp['min_act'] ?>, val max = <?php echo $donnees_gp['max_act'] ?>, défaut = <?php echo $donnees_gp['defaut_act'] ?></span><br>
							<span><?php echo $donnees_gp['for_activite'] ?></span>
							</div>
						</td>
						<td id="proprietes_table_td">
							<span class="h3" ><?php echo $donnees_gp['nom'] ?> appliquées aux utilisateurs&nbsp;:</span><br>
							<div class="decalage">
							<span class="bold">val min = <?php echo $donnees_gp['min_uti'] ?>, val max = <?php echo $donnees_gp['max_uti'] ?>, défaut = <?php echo $donnees_gp['defaut_uti'] ?></span><br>
							<span><?php echo $donnees_gp['for_utilisateur'] ?></span>
							</div>
						</td>
						<?php
					}
				}
				$liste_des_groupes->closeCursor();
				echo '</tr>';
			}
			catch(Exception $e)
			{ 
				echo '<span class=rouge> erreur lors du chargement des propriétés</span>';
				die('Erreur : '.$e->getMessage());
			}
		?>
		</table>
	</fieldset>
	<br>
	
	<!-- Création d'un nouveau groupe de propriétés -->
	<fieldset>
		<legend> Nouveau groupe de propriétés </legend>
		<form method="post" action="propriete.php" id="new_groupe">
			<label for="new_groupe_nom"> Nom: </label>
			<input class="new" type="text" name="new_groupe_nom" size="25" maxlength="50" value="<?php if(isset($_POST['new_groupe_nom'])) echo $_POST['new_groupe_nom']; else echo 'nom du groupe'; ?>" />
			<br>
			<table>
				<tr><td id="proprietes_table_td"> <?php include "details_groupe_pr.php"; ?>
				</td>
				<td>
				<input class="bouton3" type="submit" value="Ajouter"  title="ajouter un groupe de propriétés">
				</td></tr>
			<table>
		</form>
	</fieldset>
	<br>
	
</body>
</html>
