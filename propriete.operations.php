	<!-- suppression d'une propriété --> <?php
	if (isset($_GET['suppr_pr']) && $_GET['suppr_pr']!="")
	{			
		$suppr = '"'.$_GET['suppr_pr'].'"';
		try
		{
			$bdd->exec('DELETE FROM valeur_activite
						WHERE id_propriete ='.$suppr.''); //supprimer les valeurs_activites attachees
			$bdd->exec('DELETE FROM valeur_utilisateur
						WHERE id_propriete ='.$suppr.''); //supprimer les valeurs_utilisateur attachees
			$bdd->exec('DELETE FROM propriete WHERE id='.$suppr.''); //supprimer la propriété
			echo '<div class=vert >La propriété a été supprimée </div>';			
		}
		catch(Exception $e)
		{
			echo '<span class=rouge>erreur pendant la suppression de la propriété</span>';
			die('Erreur : '.$e->getMessage());
		}
	}
	?>
	
	<!-- suppression d'un groupe --> <?php
	if (isset($_GET['suppr_gp']) && $_GET['suppr_gp']!="")
	{
		$suppr = ''.$_GET['suppr_gp'].'';
		try
		{
			$nb_appli_ref = $bdd->query('SELECT COUNT(*) as nb FROM application WHERE id_groupe_pr='.$suppr);
			$res = $nb_appli_ref->fetch();
			$nb_referenced_group = $res['nb'];
			if($nb_referenced_group == 0)
			{
				$nb_prop = $bdd->query('SELECT COUNT(*) as nb FROM propriete WHERE id_groupe='.$suppr);
				$res = $nb_prop->fetch();
				$nb = $res['nb'];
				$nb_prop->closeCursor();
				$bdd->exec('DELETE FROM valeur_activite
							WHERE id_propriete IN 
								(SELECT id FROM propriete
								WHERE id_groupe='.$suppr.')'); //supprimer les valeurs_activites attachees
				$bdd->exec('DELETE FROM valeur_utilisateur
							WHERE id_propriete IN 
								(SELECT id FROM propriete
								WHERE id_groupe='.$suppr.')'); //supprimer les valeurs_utilisateur attachees
				$bdd->exec('DELETE FROM propriete WHERE id_groupe='.$suppr.''); //supprimer les proprietes attachees
				$bdd->exec('DELETE FROM groupe_propriete WHERE id='.$suppr.''); //supprimer le groupe
				echo '<div class=vert >Le groupe de propriétés et ses '.$nb.' propriétés ont été supprimés </div>';
			}
			else
			{
				echo '<div class=rouge>Impossible de supprimer ce groupe de propriétés car un calcul s\'applique à ce groupe. </div>';
			}
		}
		catch(Exception $e)
		{
			echo '<span class=rouge>erreur pendant la suppression du groupe de propriétés</span>';
			die('Erreur : '.$e->getMessage());
		}
	}
	?>
	
	<!-- ajout d'une nouvelle propriété --> <?php
	if (isset($_POST['new_propriete']))
	{
		if($_POST['new_propriete']!="")
		{
			try
			{
				$Q='INSERT INTO `propriete`(`id`, `id_groupe`, `nom`)
						VALUES (NULL,
						'.$_POST['nouveau_id_gp'].',
						"'.addslashes($_POST['new_propriete']).'"
						)';
				$bdd->exec($Q);
				echo '<div class=vert > Nouvelle propriété ajoutée </div>';
				
				//spécifier qu'une nouvelle entité a été ajoutée
				$Q='UPDATE `bd` 
						SET `new_entity` = 1
						WHERE id = "'.$_SESSION['bd'].'"';
				$bdd->exec($Q);
			}
			catch(Exception $e)
			{
				echo '<span class=rouge>erreur pendant l\'ajout d\'une propriété dans la base de données</span>';
				die('Erreur : '.$e->getMessage());
			}
		}
		else
		{ //afficher un message d'erreur
			echo '<span class=rouge>impossible d\'ajouter une propriété sans nom</span>';
		}
	}
	?>	

	<!-- ajout d'un nouveau groupe --> <?php
	if (isset($_POST['new_groupe_nom']))
	{
		$erreur = "ok";
		if($_POST['new_groupe_nom']==""){
			$erreur = "no_name";
			echo '<span class=rouge>Erreur : impossible d\'ajouter un groupe de propriétés sans nom</span>';
		}
		else if($_POST['new_groupe_min_act'] > $_POST['new_groupe_max_act'] || $_POST['new_groupe_min_act'] == $_POST['new_groupe_max_act'] || $_POST['new_groupe_min_uti'] > $_POST['new_groupe_max_uti'] || $_POST['new_groupe_min_uti'] == $_POST['new_groupe_max_uti']){
			$erreur = "min_max";
			echo '<span class=rouge>Erreur : la valeur max n\'est pas plus grande que la valeur min</span>';
		}
		else if($_POST['new_groupe_defaut_act'] > $_POST['new_groupe_max_act'] || $_POST['new_groupe_defaut_act'] < $_POST['new_groupe_min_act'] || $_POST['new_groupe_defaut_uti'] > $_POST['new_groupe_max_uti'] || $_POST['new_groupe_defaut_uti'] < $_POST['new_groupe_min_uti']){
			$erreur = "defaut";
			echo '<span class=rouge>Erreur : la valeur par défaut n\'est pas comprise entre min et max</span>';
		}
		if($erreur == "ok")
		{			
			try
			{
				$Q='INSERT INTO `groupe_propriete`(`id`, `nom`, `for_activite`, `for_utilisateur`, `min_act`, `max_act`, `pas_act`, `defaut_act`, `min_uti`, `max_uti`, `pas_uti`, `defaut_uti`, `open`, `bd`)
						VALUES (NULL,
						"'.addslashes($_POST['new_groupe_nom']).'",
						"'.addslashes($_POST['new_groupe_description_act']).'",
						"'.addslashes($_POST['new_groupe_description_uti']).'",
						'.$_POST['new_groupe_min_act'].',
						'.$_POST['new_groupe_max_act'].',
						'.$_POST['new_groupe_pas_act'].',
						'.$_POST['new_groupe_defaut_act'].',
						'.$_POST['new_groupe_min_uti'].',
						'.$_POST['new_groupe_max_uti'].',
						'.$_POST['new_groupe_pas_uti'].',
						'.$_POST['new_groupe_defaut_uti'].',
						0,
						"'.$_SESSION['bd'].'"
						)';
				$bdd->exec($Q);
				echo '<div class=vert > Nouveau groupe de propriétés ajouté </div>';
			}
			catch(Exception $e)
			{
				echo '<span class=rouge>erreur pendant l\'ajout d\'une propriété dans la base de données</span>';
				die('Erreur : '.$e->getMessage());
			}
		}
	}
	?>	
	
	<!-- modification d'une propriété --> <?php
	if (isset($_POST['edit_nom_propriete']))
	{
		if($_POST['edit_nom_propriete']!="")
		{
			try
			{
				$Q='UPDATE `propriete` SET `nom` = "'.addslashes($_POST['edit_nom_propriete']).'" WHERE `id` = '.$_POST['edit_id_propriete'];
				$bdd->exec($Q);
				echo '<div class=vert > propriété modifiée </div>';
			}
			catch(Exception $e)
			{
				echo '<span class=rouge>erreur pendant la modification du nom d\'une propriété</span>';
				die('Erreur : '.$e->getMessage());
			}
		}
		else
		{ //afficher un message d'erreur
			echo '<span class=rouge>impossible d\'ajouter une propriété sans nom</span>';
		}
	}
	?>

	<!-- modification d'un groupe (seulement le nom) --> <?php
	if (isset($_POST['edit_nom_groupe']))
	{
		$erreur = "ok";
		if($_POST['edit_nom_groupe']==""){
			$erreur = "no_name";
			echo '<span class=rouge>Erreur : impossible d\'ajouter un groupe de propriétés sans nom</span>';
		}
		if($erreur == "ok")
		{
			try
			{
				$Q='UPDATE `groupe_propriete`
					SET 
						`nom` = "'.addslashes($_POST['edit_nom_groupe']).'"
					WHERE `id` = '.$_POST['edit_id_groupe'].'';
				$bdd->exec($Q);
				echo '<div class=vert > groupe modifié </div>';
			}
			catch(Exception $e)
			{
				echo '<span class=rouge>erreur pendant la modification du nom d\'un groupe</span>';
				die('Erreur : '.$e->getMessage());
			}
		}
		else
		{ //afficher un message d'erreur
			echo '<span class=rouge>impossible d\'ajouter un groupe sans nom</span>';
		}
	}
	?>
	
	<!-- modification d'un groupe (détails, sauf le nom) --> <?php
	if (isset($_POST['edit_details']))
	{
		$erreur = "ok";
		if($_POST['new_groupe_min_act'] > $_POST['new_groupe_max_act'] || $_POST['new_groupe_min_act'] == $_POST['new_groupe_max_act'] || $_POST['new_groupe_min_uti'] > $_POST['new_groupe_max_uti'] || $_POST['new_groupe_min_uti'] == $_POST['new_groupe_max_uti'])
		{
			$erreur = "min_max";
			echo '<div class=rouge>Erreur : la valeur max n\'est pas plus grande que la valeur min</div>';
		}
		else if($_POST['new_groupe_defaut_act'] > $_POST['new_groupe_max_act'] || $_POST['new_groupe_defaut_act'] < $_POST['new_groupe_min_act'] || $_POST['new_groupe_defaut_uti'] > $_POST['new_groupe_max_uti'] || $_POST['new_groupe_defaut_uti'] < $_POST['new_groupe_min_uti'])
		{
			$erreur = "defaut";
			echo '<div class=rouge>Erreur : la valeur par défaut n\'est pas comprise entre min et max</div>';
		}
		if($erreur == "ok")
		{
			try
			{
				$Q='UPDATE `groupe_propriete`
					SET 
						`for_activite` = "'.addslashes($_POST['new_groupe_description_act']).'",
						`for_utilisateur` = "'.addslashes($_POST['new_groupe_description_uti']).'",
						`min_act` = '.$_POST['new_groupe_min_act'].',
						`max_act` = '.$_POST['new_groupe_max_act'].',
						`pas_act` = '.$_POST['new_groupe_pas_act'].',
						`defaut_act` = '.$_POST['new_groupe_defaut_act'].',
						`min_uti` = '.$_POST['new_groupe_min_uti'].',
						`max_uti` = '.$_POST['new_groupe_max_uti'].',
						`pas_uti` = '.$_POST['new_groupe_pas_uti'].',
						`defaut_uti` = '.$_POST['new_groupe_defaut_uti'].'
					WHERE `id` = '.$_POST['edit_id_gp'].'';
				$bdd->exec($Q);
				echo '<div class=vert > groupe modifiée </div>';
			}
			catch(Exception $e)
			{
				echo '<span class=rouge>erreur pendant la modification du nom d\'un groupe</span>';
				die('Erreur : '.$e->getMessage());
			}
		}
	}
	?>
