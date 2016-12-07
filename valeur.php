<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"/>
	<meta name="robots" content="noindex">
	<title>MAGAM manager - Valeurs</title>
	<link rel="icon" type="image/png" href="images/favicon.png" />
</head>
<body>
	
	<?php
	$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
	$bdd = new PDO('mysql:host=localhost;dbname=mfa', 'root', '', $pdo_options);
	
	include "init_values.php";
	session_start();
	$_SESSION['page'] = "valeur";
	include "menu.php";
	?>
	
	<!-- Création des valeurs lorsqu'elles sont absentes -->
	<?php	
	$type = "inconnu";
	$type2 = "inconnu";
	
	if(isset($_GET['affiche']))
	{
		initialiser_valeurs($_GET['affiche'], $bdd); //Creation des nouvelles valeurs
		
		//initialisation de variables de gestion
		if($_GET['affiche']=="utilisateurs")
		{
			$type = "utilisateur";
			$type2 = "uti";
		}
		else if($_GET['affiche']=="activites")
		{
			$type = "activite";
			$type2 = "act";
		}
		else if($_GET['affiche']=="repetition")
		{
			echo "Page inexistante pour l'instant.";
		}
	}
	?>
	
	<!-- Fonction pour l'ouverture et fermeture des groupes -->
	<?php
	function open_close($close0_open1, $type_groupe, $id_groupe, $bdd)
	{
		if($close0_open1 == 0 || $close0_open1 == 1)
		{
			try
			{
				$Q='UPDATE `groupe_'.$type_groupe.'` SET `open` = '.$close0_open1.' WHERE `id` = '.$id_groupe.'';
				$bdd->exec($Q);
			}
			catch(Exception $e)
			{
				echo '<span class=rouge>erreur pendant la modification du nom d\'un groupe</span>';
				die('Erreur : '.$e->getMessage());
			}
		}
		else
		{ //afficher un message d'erreur
			echo '<span class=rouge>Erreur dans la valeur open ou close</span>';
		}
	}
	?>
	
	<!-- Ouverture et fermeture des groupes -->
	<?php
	if (isset($_GET['open']))
	{
		open_close(1, $_GET['type'], $_GET['open'], $bdd);
	}
	if (isset($_GET['close']))
	{
		open_close(0, $_GET['type'], $_GET['close'], $bdd);
	}
	?>
	
	<!-- Mise à jour des valeurs-->
	<?php
	if($type != "inconnu")
		try
		{	
			//récupération des id des éléments
			$list_id_pr = $bdd->query('SELECT id FROM propriete ORDER BY id');
			$ligne = 0;
			while ($un_id_pr = $list_id_pr->fetch())
				$tab_id_pr[$ligne++] = $un_id_pr;
			$list_id_elem = $bdd->query('SELECT id FROM '.$type.' ORDER BY id');
			$ligne = 0;
			while ($un_id_elem = $list_id_elem->fetch())
				$tab_id_elem[$ligne++] = $un_id_elem;
			
			//mise é jour é partir des valeurs
			$change = false;
			foreach($tab_id_pr as $id_pr)
				foreach($tab_id_elem as $id_elem)
				{
					$name = $id_pr['id'] * 10000 + $id_elem['id'];
					if(isset($_POST[$name]))
					{
						$change = true;
						$Q='UPDATE `valeur_'.$type.'` SET `valeur` = '.$_POST[$name].' WHERE `id_propriete` = '.$id_pr['id'].' AND `id_'.$type.'` = '.$id_elem['id'].'';
						$bdd->exec($Q);
						//echo '<div class=vert >Modification de la valeur pour prop '.$id_pr['id'].' et '.$type2.' '.$id_elem['id'].' </div>';
					}
				}
			if($change)
				echo '<div class=vert >Modification enregistrées </div>';
		}
		catch(Exception $e)
		{
			echo '<span class=rouge>erreur pendant la mise à jour des valeurs</span>';
			die('Erreur : '.$e->getMessage());
		}
	?>
	
	<fieldset>
	<legend class="multi">
		<a href="valeur.php?affiche=activites" class="
			<?php if(!(isset($_GET['affiche'])) || $_GET['affiche']!="activites") echo "bouton_lvl2"; else echo "legende";?> first">
				Profils <span class="vert2">activités</span>
		</a>
		<a href="valeur.php?affiche=utilisateurs" class=" 
			<?php if(!(isset($_GET['affiche'])) || $_GET['affiche']!="utilisateurs") echo "bouton_lvl2"; else echo "legende";?> last">
				Profils <span class="bleu">utilisateurs</span>
		</a>
	</legend>
	
	<form method="post" action="valeur.php?affiche=<?php echo $type; ?>s" id="modifier_valeurs">
	<table class="valeurs">
	<?php
	if($type != "inconnu")
	{
		try
		{
			//ligne 1
			echo '<tr class="center">
				<td class="noborder">
					<input class="bouton3" type="submit" value="Enregistrer" title="Valider les valeurs"/>
				</td> 
				<td style="width:160px;" class="noborder">
					
				</td>';
			$liste_des_groupes_elem = $bdd->query('SELECT * FROM groupe_'.$type.' WHERE bd = "'.$_SESSION['bd'].'" ORDER BY id');
			while ($donnees_gp_elem = $liste_des_groupes_elem->fetch())
			{
				$nom_affiche = $donnees_gp_elem['nom'];
				if($donnees_gp_elem['open'] == 0)
					$nom_affiche = substr($donnees_gp_elem['nom'], 0, 10)."...";
				echo '<td>';
				echo '<span title="('.$donnees_gp_elem['id'].') '.$donnees_gp_elem['nom'].'">'.$nom_affiche.'</span>';
				if($donnees_gp_elem['open'] == 0)
					echo '<a class="bouton3" href="valeur.php?affiche='.$type.'s&type='.$type.'&open='.$donnees_gp_elem['id'].'" title="afficher les valeurs">+</a>';
				else
					echo '<a class="bouton3" href="valeur.php?affiche='.$type.'s&type='.$type.'&close='.$donnees_gp_elem['id'].'" title="masquer les valeurs">-</a>';
				echo '</td>';
			}
			echo '</tr>';
			//ligne 2
			echo '<tr class="reduc"> <td class="noborder"></td><td class="noborder"></td>';
			$liste_des_groupes_elem = $bdd->query('SELECT * FROM groupe_'.$type.' WHERE bd = "'.$_SESSION['bd'].'" ORDER BY id');
			while ($donnees_gp_elem = $liste_des_groupes_elem->fetch())
			{
				echo '<td>';
				
				if($donnees_gp_elem['open'] == 1)
				{
					echo '<table class="inside_valeurs"> <tr>';
					$liste_des_elem = $bdd->query('SELECT * FROM '.$type.' WHERE id_groupe = '.$donnees_gp_elem['id'].' ORDER BY id');
					while ($donnees_elem = $liste_des_elem->fetch())
					{
						echo '<td style="width:65px;"><span title="('.$donnees_elem['id'].') '.$donnees_elem['nom'].'">['.substr($donnees_elem['nom'], 0, 9).']</span></td>';
					}
					echo '</tr> </table>';
				}
				
				echo '</td>';
			}
			echo '</tr>';
			//autres lignes
			$liste_des_groupes_pr = $bdd->query('SELECT * FROM groupe_propriete WHERE bd = "'.$_SESSION['bd'].'" ORDER BY id');
			while ($donnees_gp_pr = $liste_des_groupes_pr->fetch())
			{
				echo '<tr>';
				// colonne 1
				echo '<td>';
				if($donnees_gp_pr['open'] == 0)
					echo '<a class="bouton3" href="valeur.php?affiche='.$type.'s&type=propriete&open='.$donnees_gp_pr['id'].'" title="afficher les valeurs">+</a>';
				else
					echo '<a class="bouton3" href="valeur.php?affiche='.$type.'s&type=propriete&close='.$donnees_gp_pr['id'].'" title="masquer les valeurs">-</a>';
				echo '<span title="('.$donnees_gp_pr['id'].') '.$donnees_gp_pr['nom'].'">'.$donnees_gp_pr['nom'].'</span>';
				echo '</td>';
				//colonne 2
				echo '<td> <table class="inside_valeurs">';
				
					if($donnees_gp_pr['open'] == 1)
					{
						$liste_des_pr = $bdd->query('SELECT * FROM propriete WHERE id_groupe = '.$donnees_gp_pr['id'].' ORDER BY id');
						while ($donnees_pr = $liste_des_pr->fetch())
						{
							echo '<tr><td><span title="('.$donnees_pr['id'].') '.$donnees_pr['nom'].'">'.substr($donnees_pr['nom'], 0, 19).'</span></td></tr>';
						}
					}
					
				echo '</table> </td>';
				//autres colonnes
				$liste_des_groupes_elem = $bdd->query('SELECT * FROM groupe_'.$type.' WHERE bd = "'.$_SESSION['bd'].'" ORDER BY id');
				while ($donnees_gp_elem = $liste_des_groupes_elem->fetch())
				{
					echo '<td>';
					if($donnees_gp_elem['open'] == 0 || $donnees_gp_pr['open'] == 0)
						echo '';
					else
					{
						
						$liste_id_pr = 0;
						$liste_id_elem = 0;
						unset($liste_id_pr);
						unset($liste_id_elem);
						//construction de tableaux avec la liste des id
						$liste_des_id_pr = $bdd->query('SELECT id FROM propriete WHERE id_groupe = '.$donnees_gp_pr['id'].' ORDER BY id');
						$ligne = 0;
						while ($l_id_pr = $liste_des_id_pr->fetch())
							$liste_id_pr[$ligne++] = $l_id_pr;
						$liste_des_id_elem = $bdd->query('SELECT id FROM '.$type.' WHERE id_groupe = '.$donnees_gp_elem['id'].' ORDER BY id');
						$ligne = 0;
						while ($l_id_elem = $liste_des_id_elem->fetch())
							$liste_id_elem[$ligne++] = $l_id_elem;
						
						if(isset($liste_id_pr) && isset ($liste_id_elem))
						{
							//faire un tableau avec les valeurs
							echo '<table class="inside_valeurs center">';
							foreach( $liste_id_pr as $id_pr )
							{
								echo '<tr>';
								foreach( $liste_id_elem as $id_elem )
								{
									echo '<td>';
									//echo 'SELECT valeur FROM valeur_'.$type.' WHERE id_'.$type.' = '.$id_elem['id'].' AND id_propriete =  '.$id_pr['id'].'';
									$val = $bdd->query('SELECT valeur FROM valeur_'.$type.' WHERE id_'.$type.' = '.$id_elem['id'].' AND id_propriete =  '.$id_pr['id'].'');
									while($res = $val->fetch())
									{
										//echo ''.$id_elem['id'].'&'.$id_pr['id'].'';
										$name = $id_pr['id'] * 10000 + $id_elem['id'];
										echo '<input class="small" 
													type="number" 
													name="'.$name.'" 
													min='.$donnees_gp_pr['min_'.$type2.''].' 
													max='.$donnees_gp_pr['max_'.$type2.''].' 
													value='.$res['valeur'].' 
													step="'.$donnees_gp_pr['pas_'.$type2.''].'">';
									}
									echo '</td>';
								}
								echo '</tr>';
							}
							echo '</table>';
						}
					}
					echo '</td>';
				}
				echo '</tr>';
			}
					
		}
		catch(Exception $e)
		{
			echo '<span class=rouge>erreur pendant la création des valeurs par défaut</span>';
			die('Erreur : '.$e->getMessage());
		}
	}
	?>
	
	</table>
	</form>
	
	</fieldset>
	
</body>
</html>
