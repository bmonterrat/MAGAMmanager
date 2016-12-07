<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"/>
	<meta name="robots" content="noindex">
	<title>Adaptation Multi Aspects - Utilisateurs</title>
	<link rel="icon" type="image/png" href="images/favicon.png" />
</head>
<body>
	
	<?php
	$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
	//$bdd = new PDO('mysql:host=localhost;dbname=mfa', 'root', '', $pdo_options);
	$bdd = new PDO('mysql:host=193.37.145.61;dbname=bmont721766', 'bmont721766', 'd6H4FAGw', $pdo_options); //commenter en local
	
	session_start();
	$_SESSION['page'] = "utilisateur";
	include "menu.php";
	include "functions.php";
	?>
	
	<?php include "utilisateur.operations.php" ?>
	
	<!-- Affichage de la liste des utilisateur --> 
	<fieldset>
		<legend> Utilisateurs </legend>
		<table id="utilisateurss_table">
		<?php
			try
			{
				$liste_des_groupes = $bdd->query('SELECT * FROM groupe_utilisateur WHERE bd = "'.$_SESSION['bd'].'" ORDER BY id');
				while ($donnees_gp = $liste_des_groupes->fetch())
				{
					echo '<tr> <td>';
					echo '<a href="utilisateur.php?mod_gp='.$donnees_gp['id'].'" title="modifier ce groupe" > <img class="small" src="'.$mod.'" alt="supprimer" /> </a>';
					echo '<a href="utilisateur.php?suppr_gp='.$donnees_gp['id'].'" title="supprimer ce groupe d\'utilisateur" > <img class="small" src="'.$sup.'" alt="supprimer" /> </a>';
					if(isset($_GET['mod_gp']) && $_GET['mod_gp'] == $donnees_gp['id'])
					{
						?>
							<form method="post" action="utilisateur.php" class="inline" id="modifier_groupe">
								<input type="text" name="edit_nom_groupe" size="30" maxlength="80" value="<?php echo $donnees_gp['nom'] ?>" />
								<input type="hidden" name="edit_id_groupe" <?php echo 'value="'.$donnees_gp['id'].'"'; ?> />
								<input class="bouton3" type="submit" value="Valider" title="confirmer le nouveau nom"/>
							</form>
							<?php
					}
					else
						echo '<label class="h2"> '.$donnees_gp['nom'].' : </label>';
					echo '<br>';

					
					$liste_des_utilisateurs = $bdd->query('SELECT * FROM utilisateur WHERE id_groupe = '.$donnees_gp['id'].' ORDER BY nom');
					while ($donnees_ut = $liste_des_utilisateurs->fetch())
					{
						echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
						echo '<a href="utilisateur.php?mod_ut='.$donnees_ut['id'].'" title="modifier cette utilisateur" > <img class="small" src="'.$mod.'" alt="modifier" /> </a>';
						echo '<a href="utilisateur.php?suppr_ut='.$donnees_ut['id'].'" title="supprimer cette utilisateur" > <img class="small" src="'.$sup.'" alt="supprimer" /> &nbsp </a>';
						if(isset($_GET['mod_ut']) && $_GET['mod_ut'] == $donnees_ut['id'])
						{
							?>
							<form method="post" action="utilisateur.php" class="inline" id="modifier_utilisateur">
								<input type="text" name="edit_nom_utilisateur" size="25" maxlength="50" value="<?php echo $donnees_ut['nom'] ?>" />
								<input type="hidden" name="edit_id_utilisateur" <?php echo 'value="'.$donnees_ut['id'].'"'; ?> />
								<input class="bouton3" type="submit" value="Valider" title="confirmer le nouveau nom"/>
							</form>
							<?php
						}
						else
							echo $donnees_ut['nom'];
						//echo '<span class="groupe_xp '.get_color($donnees_ut['groupe_xp']).'"> ['.$donnees_ut['groupe_xp'].'] </span>';
						echo '<br>';
					}
					$liste_des_utilisateurs->closeCursor();
					
					?>
					<form method="post" action="utilisateur.php" id="ajouter_utilisateur">
						&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
						<input class="gris" type="text" name="new_utilisateur" size="30" maxlength="100" value="nouvel utilisateur" />
						<input name="nouveau_id_gp" type="hidden" <?php echo 'value="'.$donnees_gp['id'].'"'; ?> />
						<input class="bouton2" type="submit" value="Ajouter"  title="ajouter une utilisateur"/>
					</form>
					</td>
					<?php
				}
				$liste_des_groupes->closeCursor();
				echo '</tr>';
			}
			catch(Exception $e)
			{
				echo '<span class=rouge> erreur lors du chargement des utilisateurs</span>';
				die('Erreur : '.$e->getMessage());
			}
		?>
		</table>
	</fieldset>
	
	<br>
	
	<!-- Création d'un nouveau groupe d'utilisateurs -->
	<fieldset>
		<legend> Nouveau groupe d'utilisateurs </legend>
		<form method="post" action="utilisateur.php" id="new_groupe">
			<label for="new_groupe_nom"> Nom: </label>
			<input class="new" type="text" name="new_groupe_nom" size="30" maxlength="80" value="<?php if(isset($_POST['new_groupe_nom'])) echo $_POST['new_groupe_nom']; else echo 'nom du groupe'; ?>" />
			<input class="bouton3" type="submit" value="Ajouter"  title="ajouter un groupe d'utilisateurs">
		</form>
	</fieldset>
	
</body>
</html>
