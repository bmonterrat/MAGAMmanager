
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
			echo '<span class=rouge>erreur sur la récupération de l\'utilisateur ref</span>';
			die('Erreur : '.$e->getMessage());
		}
	?>
	
