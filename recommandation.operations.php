
	<!-- envoi des mails de recommandation --> <?php
	if (isset($_POST['destinataire']) && $_POST['destinataire']!="")
	{
		echo 'envoi : ';
		sendmail($_POST['destinataire'], $_POST['act1'], $_POST['act2'], $_POST['act3'], $_POST['idMoodle'], $_POST['deadline'], $_POST['nom']);
	}
	?>
	
	<!-- suppression d'une application --> <?php
	if (isset($_GET['suppr_appli']) && $_GET['suppr_appli']!="")
	{
		try
		{
			$is_referenced = $bdd->query('SELECT count(*) as nb FROM fusion_references WHERE referenced='.$_GET['suppr_appli'].'');
			$is_ref = $is_referenced->fetch();
			if($is_ref['nb'] == 1)
			{
				echo '<div class=rouge > Il est impossible de supprimer cette application car elle est réféencée par une fusion </div>';
			}
			else if($is_ref['nb'] > 1)
			{
				echo '<div class=rouge > Il est impossible de supprimer cette application car elle est réféencée par des fusions </div>';
			}
			else
			{
				$bdd->exec('DELETE FROM fusion_references WHERE reference='.$_GET['suppr_appli'].''); //supprimer les références associées
				$bdd->exec('DELETE FROM application WHERE id='.$_GET['suppr_appli'].''); //supprimer l'application
				echo '<div class=vert >L\'application de l\'opération a été supprimée </div>';
			}
		}
		catch(Exception $e)
		{
			echo '<span class=rouge>erreur pendant la suppression de l\'application : </span>';
			die('Erreur : '.$e->getMessage());
		}
	}
	?>
	
	<!-- ajouter un calcul--> <?php
	if (isset($_POST['new_calcul']) && isset($_POST['new_groupe']))
	{
		if($_POST['new_calcul']!="" && $_POST['new_calcul']!=0 && $_POST['new_groupe']!="" && $_POST['new_groupe']!=0)
		{
			try
			{
				$Q='INSERT INTO `application`(`id_groupe_pr`, `id_operation`)
						VALUES ('.$_POST['new_groupe'].','.$_POST['new_calcul'].')';
				$bdd->exec($Q);
				echo '<div class=vert > Nouvelle application de calcul ajoutée </div>';
			}
			catch(Exception $e)
			{
				echo '<span class=rouge>erreur pendant l\'ajout d\'un application de calcul : </span>';
				die('Erreur : '.$e->getMessage());
			}
		}
		else
		{ //afficher un message d'erreur
			echo '<span class=rouge>erreur : sélectionnez un groupe de propriétés et un mode de calcul : </span>';
		}
	}
	?>
	
	<!-- ajouter une fusion--> <?php
	if (isset($_POST['new_fusion']))
	{
		if($_POST['new_fusion']!="" && $_POST['new_fusion']!=0)
		{
			try
			{
				$Q='INSERT INTO `application`(`id_operation`, `bd`)
						VALUES ('.$_POST['new_fusion'].', "'.$_SESSION['bd'].'")';
				$bdd->exec($Q);
				echo '<div class=vert > Nouvelle application de fusion ajoutée </div>';
				
				$id_applications = $bdd->query('SELECT id FROM application ORDER BY id DESC');
				$reference = $id_applications->fetch();
				while($id_appli = $id_applications->fetch())
				{
					if( isset($_POST['ref'.$id_appli['id']]) && isset($_POST['poids'.$id_appli['id']]) )
					{
						$Q='INSERT INTO `fusion_references`(`reference`, `referenced`, `poids`)
							VALUES ('.$reference['id'].', '.$id_appli['id'].', '.$_POST['poids'.$id_appli['id']].')';
						$bdd->exec($Q);
						echo '<div class=vert > Référence ajoutée à a fusion </div>';
					}
				}
			}
			catch(Exception $e)
			{
				echo '<span class=rouge>erreur pendant l\'ajout d\'une application de fusion : </span>';
				die('Erreur : '.$e->getMessage());
			}
		}
		else
		{ //afficher un message d'erreur
			echo '<span class=rouge>erreur : sélectionnez un mode de fusion</span>';
		}
	}
	?>
	
	<!-- ajouter une sélection--> <?php
	if (isset($_POST['new_selection']))
	{
		if($_POST['new_selection']!="" && $_POST['new_selection']!=0)
		{
			try
			{
				// enregistrer la nouvelle application de sélection
				$Q='INSERT INTO `application`(`id_operation`, `bd`)
						VALUES ('.$_POST['new_selection'].', "'.$_SESSION['bd'].'")';
				$bdd->exec($Q);
				echo '<div class=vert > Sélection ajoutée </div>';
			}
			catch(Exception $e)
			{
				echo '<span class=rouge>erreur pendant l\'ajout d\'une application de fusion : </span>';
				die('Erreur : '.$e->getMessage());
			}
		}
		else
		{ //afficher un message d'erreur
			echo '<span class=rouge>erreur : choisissez une sélection</span>';
		}
	}
	?>
	
	<!-- remplacer une sélection--> <?php
	if (isset($_POST['mod_selec']))
	{
		if($_POST['mod_selec']!="" && $_POST['mod_selec']!=0)
		{
			try
			{
				$Q='UPDATE `application` 
					SET `id_operation` = '.$_POST['mod_selec'].'
					WHERE `id` = '.$_POST['id_appli_selec'].'';
				$bdd->exec($Q);
				echo '<div class=vert > Sélection modifiée </div>';
			}
			catch(Exception $e)
			{
				echo '<span class=rouge>erreur pendant la modification de l\'utilisateur référent : </span>';
				die('Erreur : '.$e->getMessage());
			}
		}
		else
		{ //afficher un message d'erreur
			echo '<span class=rouge>erreur : choisissez une sélection</span>';
		}
	}
	?>
	
	<!-- remplacer l'utilisateur visu--> <?php
	if (isset($_POST['mod_visu_user']))
	{
		if($_POST['mod_visu_user']!="" && $_POST['mod_visu_user']!=0)
		{
			try
			{
				$Q='UPDATE `visu` 
					SET `id_user` = '.$_POST['mod_visu_user'].',
						`id_groupe_act` = '.$_POST['mod_visu_act'].'
						WHERE bd = "'.$_SESSION['bd'].'"
						';
				$bdd->exec($Q);
				//echo '<div class=vert > utilisateur référent modifié </div>';
			}
			catch(Exception $e)
			{
				echo '<span class=rouge>erreur pendant la modification de l\'utilisateur référent : </span>';
				die('Erreur : '.$e->getMessage());
			}
		}
		else
		{ //afficher un message d'erreur
			echo '<span class=rouge>erreur : mauvaise valeur pour l\'utilisateur référent</span>';
		}
	}
	?>

	<!-- récupération l'utilisateur et le groupe_act pour la visualisation --> <?php
		$user_ref = 0;
		try
		{
			$get_user_visu = $bdd->query('SELECT `id_user`, `nom`, `id_groupe`, `id_groupe_act`
										FROM `visu`
										JOIN `utilisateur`
										ON `visu`.`id_user` = `utilisateur`.`id`
										WHERE visu.bd = "'.$_SESSION['bd'].'"');
			$visu = $get_user_visu->fetch();
			$user_visu = $visu['id_user'];
			$user_visu_nom = $visu['nom'];
			$user_ref_groupe = $visu['id_groupe'];
			$activite_ref_groupe = $visu['id_groupe_act'];
		}
		catch(Exception $e)
		{
			echo '<span class=rouge>erreur sur la récupération de l\'utilisateur ref : </span>';
			die('Erreur : '.$e->getMessage());
		}
	?>
	
