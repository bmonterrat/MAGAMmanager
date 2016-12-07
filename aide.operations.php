
<?php

	if(isset($_GET["construire"]))
	{
		include "init_values.php";
		
		echo '<br/> <span class="vert"> Initialisation des valeurs des Q-matrices à partir des noms : </span><br/>';
		
		try
		{
			$liste_proprietes = $bdd->query('SELECT propriete.id, propriete.nom FROM propriete
											JOIN groupe_propriete ON propriete.id_groupe = groupe_propriete.id
											WHERE groupe_propriete.bd = "'.$_SESSION['bd'].'"');
			$count = 0;
			while($propriete = $liste_proprietes->fetch())
			{
				$liste_activites = $bdd->query('SELECT activite.id, activite.nom FROM activite
											JOIN groupe_activite ON activite.id_groupe = groupe_activite.id
											WHERE groupe_activite.bd = "'.$_SESSION['bd'].'"');
				while($activite = $liste_activites->fetch())
				{
					$match = false;
					$match = matching($propriete['nom'], $activite['nom']);
					
					if($match)
					{
						echo '<span class="vert">';
						echo 'prop '.$propriete['id'].'('.$propriete['nom'].') -> act '.$activite['id'].'('.$activite['nom'].') <br/>';
						echo '</span>';
						$Q='UPDATE `valeur_activite` SET `valeur` = 1 WHERE `id_propriete` = '.$propriete['id'].' AND `id_activite` = '.$activite['id'].'';
						$bdd->exec($Q);
					}
				}
			}
			if($count =+ 0)
			{
				echo '<span class="vert"> Aucune valeur correspondante. </span><br/>';
			}
		}
		catch(Exception $e)
		{
			echo '<span class=rouge>erreur la construction automatique des Q-matrices : </span>';
			die('Erreur : '.$e->getMessage());
		}
	}

	if(isset($_GET["mail"]))
	{
		sendmailtest();
	}
?>