
	<?php include "get_visu.php"; ?>
	
	<!-- suppression d'une activité --> <?php
	if (isset($_GET['suppr_ac']) && $_GET['suppr_ac']!="")
	{
		$suppr = ''.$_GET['suppr_ac'].'';
		try
		{
			$bdd->exec('DELETE FROM valeur_activite
						WHERE id_activite ='.$suppr.''); //supprimer les valeurs_activites attachees
			$bdd->exec('DELETE FROM activite WHERE id='.$suppr.''); //supprimer l'activité
			echo '<div class=vert >L\'activité a été supprimée </div>';
		}
		catch(Exception $e)
		{
			echo '<span class=rouge>erreur pendant la suppression de l\'activité : </span>';
			die('Erreur : '.$e->getMessage());
		}
	}
	?>
	
	<!-- suppression d'un groupe --> <?php
	if (isset($_GET['suppr_gp']) && $_GET['suppr_gp']!="")
	{
		$suppr = ''.$_GET['suppr_gp'].'';
		if($activite_ref_groupe==$suppr){
			echo '<span class=rouge>Il est impossible de supprimer le groupe d\'activités utilisé pour visualiser les opérations. Rendez-vous sur la page des <a class=bouton href="recommandation.php?affiche=calculs">calculs</a> pour en choisir un autre. </span>';
		}
		else
		{
			try
			{
				$nb_acti = $bdd->query('SELECT COUNT(*) as nb FROM activite WHERE id_groupe="'.$suppr.'"');
				$res = $nb_acti->fetch();
				$nb = $res['nb'];
				$nb_acti->closeCursor();
				$bdd->exec('DELETE FROM valeur_activite
							WHERE id_activite IN 
								(SELECT id FROM activite
								WHERE id_groupe="'.$suppr.'")'); //supprimer les valeurs_activites attachees
				$bdd->exec('DELETE FROM activite WHERE id_groupe="'.$suppr.'"'); //supprimer les activités attachees
				$bdd->exec('DELETE FROM groupe_activite WHERE id="'.$suppr.'"'); //supprimer le groupe
				echo '<div class=vert >Le groupe d\'activités et ses '.$nb.' activités ont été supprimés </div>';
			}
			catch(Exception $e)
			{
				echo '<span class=rouge>erreur pendant la suppression du groupe de activite : </span>';
				die('Erreur : '.$e->getMessage());
			}
		}
	}
	?>
	
	<!-- ajout d'une nouvelle activité --> <?php
	if (isset($_POST['new_activite']))
	{
		if($_POST['new_activite']!="")
		{
			try
			{
				//création de l'activité
				$Q='INSERT INTO `activite`(`id`, `id_groupe`, `nom`)
						VALUES (NULL,
						'.$_POST['nouveau_id_gp'].',
						"'.addslashes($_POST['new_activite']).'"
						)';
				$bdd->exec($Q);
				echo '<div class=vert > Nouvelle activité ajoutée </div>';
				
				//spécifier qu'une nouvelle entité a été ajoutée
				$Q='UPDATE `bd` 
						SET `new_entity` = 1
						WHERE id = "'.$_SESSION['bd'].'"';
				$bdd->exec($Q);
			}
			catch(Exception $e)
			{
				echo '<span class=rouge>erreur pendant l\'ajout d\'une activité dans la base de données : </span>';
				die('Erreur : '.$e->getMessage());
			}
		}
		else
		{ //afficher un message d'erreur
			echo '<span class=rouge>impossible d\'ajouter une activité sans nom : </span>';
		}
	}
	?>	

	<!-- ajout d'un nouveau groupe --> <?php
	if (isset($_POST['new_groupe_nom']))
	{
		$erreur = "ok";
		if($_POST['new_groupe_nom']=="")
		{
			$erreur = "no_name";
			echo '<span class=rouge>Erreur : impossible d\'ajouter un groupe d\'activités sans nom : </span>';
		}
		if($erreur == "ok")
		{			
			try
			{
				$Q='INSERT INTO `groupe_activite`(`id`, `nom`, `open`, `bd`)
						VALUES (NULL,
						"'.addslashes($_POST['new_groupe_nom']).'",
						0,
						"'.$_SESSION['bd'].'"
						)';
				$bdd->exec($Q);
				echo '<div class=vert > Nouveau groupe d\'activités ajouté </div>';
			}
			catch(Exception $e)
			{
				echo '<span class=rouge>erreur pendant l\'ajout d\'un groupe d\'activités dans la base de données : </span>';
				die('Erreur : '.$e->getMessage());
			}
		}
	}
	?>	
	
	<!-- modification d'une activité --> <?php
	if (isset($_POST['edit_nom_activite']))
	{
		if($_POST['edit_nom_activite']!="")
		{
			try
			{
				$Q='UPDATE `activite` SET `nom` = "'.addslashes($_POST['edit_nom_activite']).'" WHERE `id` = '.$_POST['edit_id_activite'];
				$bdd->exec($Q);
				echo '<div class=vert > activité modifiée </div>';
			}
			catch(Exception $e)
			{
				echo '<span class=rouge>erreur pendant la modification du nom d\'une activité : </span>';
				die('Erreur : '.$e->getMessage());
			}
		}
		else
		{ //afficher un message d'erreur
			echo '<span class=rouge>impossible d\'ajouter une activité sans nom : </span>';
		}
	}
	?>

	<!-- modification d'un groupe --> <?php
	if (isset($_POST['edit_nom_groupe']))
	{
		if($_POST['edit_nom_groupe']!="")
		{
			try
			{
				$Q='UPDATE `groupe_activite` SET `nom` = "'.addslashes($_POST['edit_nom_groupe']).'" WHERE `id` = '.$_POST['edit_id_groupe'];
				$bdd->exec($Q);
				echo '<div class=vert > groupe modifiée </div>';
			}
			catch(Exception $e)
			{
				echo '<span class=rouge>erreur pendant la modification du nom d\'un groupe : </span>';
				die('Erreur : '.$e->getMessage());
			}
		}
		else
		{ //afficher un message d'erreur
			echo '<span class=rouge>impossible d\'ajouter un groupe sans nom : </span>';
		}
	}
	?>
	