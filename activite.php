<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"/>
	<meta name="robots" content="noindex">
	<title>MAGAM manager - activite</title>
	<link rel="icon" type="image/png" href="images/favicon.png" />
</head>
<body>
	
	<?php
	$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
	$bdd = new PDO('mysql:host=localhost;dbname=mfa', 'root', '', $pdo_options);
		
	session_start();
	$_SESSION['page'] = "activite";
	include "menu.php";
	?>
	
	<?php include "activite.operations.php" ?>
	
	<!-- Affichage de la liste des activités --> 
	<fieldset>
		<legend> Activités </legend>
		<table id="activites_table">
		<?php
			try
			{
				$liste_des_groupes = $bdd->query('SELECT * FROM groupe_activite WHERE bd = "'.$_SESSION['bd'].'" ORDER BY id');
				while ($donnees_gp = $liste_des_groupes->fetch())
				{
					echo '<tr> <td>';
					echo '<a href="activite.php?mod_gp='.$donnees_gp['id'].'" title="modifier ce groupe" > <img class="small" src="'.$mod.'" alt="supprimer" /> </a>';
					echo '<a href="activite.php?suppr_gp='.$donnees_gp['id'].'" title="supprimer ce groupe d\'activités" > <img class="small" src="'.$sup.'" alt="supprimer" /> </a>';
					if(isset($_GET['mod_gp']) && $_GET['mod_gp'] == $donnees_gp['id'])
					{
						?>
							<form method="post" action="activite.php" class="inline" id="modifier_groupe">
								<input type="text" name="edit_nom_groupe" size="30" maxlength="80" value="<?php echo $donnees_gp['nom'] ?>" />
								<input type="hidden" name="edit_id_groupe" <?php echo 'value="'.$donnees_gp['id'].'"'; ?> />
								<input class="bouton3" type="submit" value="Valider" title="confirmer le nouveau nom"/>
							</form>
							<?php
					}
					else
						echo '<label class="h2"> '.$donnees_gp['nom'].' : </label>';
					echo '<br>';

					
					$liste_des_activites = $bdd->query('SELECT * FROM activite WHERE id_groupe = '.$donnees_gp['id'].' ORDER BY id');
					while ($donnees_ac = $liste_des_activites->fetch())
					{
						echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
						echo '<a href="activite.php?mod_ac='.$donnees_ac['id'].'" title="modifier cette activités" > <img class="small" src="'.$mod.'" alt="modifier" /> </a>';
						echo '<a href="activite.php?suppr_ac='.$donnees_ac['id'].'" title="supprimer cette activité" > <img class="small" src="'.$sup.'" alt="supprimer" /> &nbsp </a>';
						if(isset($_GET['mod_ac']) && $_GET['mod_ac'] == $donnees_ac['id'])
						{
							?>
							<form method="post" action="activite.php" class="inline" id="modifier_activite">
								<input type="text" name="edit_nom_activite" size="25" maxlength="50" value="<?php echo $donnees_ac['nom'] ?>" />
								<input type="hidden" name="edit_id_activite" <?php echo 'value="'.$donnees_ac['id'].'"'; ?> />
								<input class="bouton3" type="submit" value="Valider" title="confirmer le nouveau nom"/>
							</form>
							<?php
						}
						else
							echo $donnees_ac['nom'];
						echo '<br>';
					}
					$liste_des_activites->closeCursor();
					
					?>
					<form method="post" action="activite.php" id="ajouter_activite">
						&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
						<input class="gris" type="text" name="new_activite" size="30" maxlength="100" value="nouvelle activité" />
						<input name="nouveau_id_gp" type="hidden" <?php echo 'value="'.$donnees_gp['id'].'"'; ?> />
						<input class="bouton2" type="submit" value="Ajouter"  title="ajouter une activité"/>
					</form>
					</td>
					<?php
				}
				$liste_des_groupes->closeCursor();
				echo '</tr>';
			}
			catch(Exception $e)
			{
				echo '<span class=rouge> erreur lors du chargement des activités</span>';
				die('Erreur : '.$e->getMessage()); 
			}
		?>
		</table>
	</fieldset>
	
	<br>
	
	<!-- Création d'un nouveau groupe d'activités -->
	<fieldset>
		<legend> Nouveau groupe d'activités </legend>
		<form method="post" action="activite.php" id="new_groupe">
			<label for="new_groupe_nom"> Nom: </label>
			<input class="new" type="text" name="new_groupe_nom" size="30" maxlength="80" value="<?php if(isset($_POST['new_groupe_nom'])) echo $_POST['new_groupe_nom']; else echo 'nom du groupe'; ?>" />
			<input class="bouton3" type="submit" value="Ajouter"  title="ajouter un groupe d'activités">
		</form>
	</fieldset>
	
	
</body>
</html>
