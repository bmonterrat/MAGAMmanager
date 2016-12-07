
			<?php
				try
				{
					$application = $bdd->query('SELECT application.id, id_operation as id_calcul, operation.nom as c_nom, id_groupe_pr, groupe_propriete.nom as p_nom
												FROM application
												JOIN groupe_propriete
												ON application.id_groupe_pr = groupe_propriete.id
												JOIN operation
												ON application.id_operation = operation.id
												WHERE application.id_groupe_pr IS NOT NULL
												AND groupe_propriete.bd = "'.$_SESSION['bd'].'"
												ORDER BY id');
					while($appli = $application->fetch())
					{
						$id_application = $appli['id'];
						
					//affichage de l'entéte
						if(isset($_GET['affiche']) && $_GET['affiche']=="calculs")
						{
							echo '<tr><td><a href="recommandation.php?affiche=calculs&suppr_appli='.$appli['id'].'" title="supprimer cette application" > <img class="small" src="'.$sup.'" alt="supprimer" /> </a>';
							echo '<span class="violet"> Application du calcul <span class="rouge">('.$appli['id_calcul'].') "'.$appli['c_nom'].'" </span> sur les </span> <span class="bold">"'.$appli['p_nom'].'"</span> :</td></tr>';
						}
						
					//récupération de l'index des PROPRIETES (id et nom)
						unset($index);
						$liste_prop = $bdd->query('SELECT id, nom 
													FROM propriete
													WHERE id_groupe = '.$appli['id_groupe_pr'].'
													ORDER BY id');
						$ligne = 0;
						while ($l_pr = $liste_prop->fetch())
							$index['pr'][$ligne++] = $l_pr;
						$nb_proprietes = $ligne;
						
					//récupération de l'index des UTILISATEURS (id et nom)
						$liste_user = $bdd->query('SELECT id, nom, mail
													FROM utilisateur
													WHERE id_groupe = '.$user_ref_groupe.'
													ORDER BY nom');
						$ligne = 0;
						while ($l_ut = $liste_user->fetch())
							$index['ut'][$ligne++] = $l_ut;
						$nb_utilisateurs = $ligne;
						
					//récupération de l'index des activités (id et nom)
						$liste_acti = $bdd->query('SELECT id, nom 
													FROM activite
													WHERE id_groupe = '.$activite_ref_groupe.'
													ORDER BY id');
						$ligne = 0;
						while ($l_ac = $liste_acti->fetch())
							$index['ac'][$ligne++] = $l_ac;
						$nb_activites = $ligne;
														
					//FAIRE LES CALCULS
						$resultat[$id_application] = calculer($appli['id_calcul'], $valeurs, $index, $nb_proprietes, $nb_utilisateurs, $nb_activites);
								
					//visualisation des valeurs du calcul
						if(isset($_GET['affiche']) && $_GET['affiche']=="calculs")
						{
							echo '<tr><td>';
							echo '<table>';
							
								//ligne 1 (titre activités)
								echo '<tr><td></td><td class="verti"> '.$user_visu_nom.' </td>';
								for($a = 0; $a < $nb_activites; $a++)
								{
									echo '<td class="verti" title="'.$index['ac'][$a]['nom'].'">['.substr($index['ac'][$a]['nom'], 0, 8).']</td>';
								}
								echo '</tr>';	
								
								//lignes suivantes
								if($appli['id_calcul'] != 13)
								{
									for($l = 0; $l < $nb_proprietes ; $l++)
									{
										$id_propriete = $index['pr'][$l]['id'];
										$nom_propriete = $index['pr'][$l]['nom'];
										echo '<tr>';
											echo '<td class="horiz">';
												echo ''.$nom_propriete.' : ';
											echo '</td>';
											echo '<td class="horiz verti">';
												echo ' '.$valeurs['util'][$user_visu][$id_propriete].'';
											echo '</td>';
											for($a = 0; $a < $nb_activites; $a++)
											{
												$id_activite = $index['ac'][$a]['id'];
												echo '<td class="center horiz verti"> '.$valeurs['acti'][$id_activite][$id_propriete].' </td>';
											}
										echo '</tr>';
									}
								}
								
								//ligne finale (résultat)
								echo '<tr class="bold"><td class="horiz"></td><td class="horiz verti"></td>';
								for($i = 0; $i < $nb_activites; $i++)
								{
									echo '<td class="center horiz verti">'.$resultat[$id_application][$user_visu][$index['ac'][$i]['id']].'</td>';
								}
								echo '</tr>';	
							echo '</table>'; 
							echo '</td></tr>';
						}
					//fin visualisation
					}
				}
				catch(Exception $e)
				{
					echo '<span class=rouge>erreur sur l\'application des claculs : </span>';
					die('Erreur : '.$e->getMessage());
				}
			?>
			
			<!-- ajout d'un nouveau calcul -->
			<?php if(isset($_GET['affiche']) && $_GET['affiche']=="calculs")
			{ ?>
				<tr class="espace_up_down"><td colspan="3">
				<form method="post" action="recommandation.php?affiche=calculs" id="nouveau calcul">
					Appliquer un nouveau calcul : <br>
					<label for="new_groupe"> Propriétés : </label>
					<select name="new_groupe" id="groupe">
						<option value=0> </option>
						<?php
						try
						{
							$groupes = $bdd->query('SELECT * FROM groupe_propriete WHERE bd = "'.$_SESSION['bd'].'"');
							while($groupe = $groupes->fetch())
							{
								echo '<option value='.$groupe['id'].'> '.$groupe['nom'].' </option>';
							}
						}
						catch(Exception $e)
						{
							echo '<span class=rouge>erreur sur la récupération des groupes de propriétés</span>';
							die('Erreur : '.$e->getMessage());
						}
						?>
					</select>
					<label for="new_calcul"> Calcul : </label>
					<select name="new_calcul" id="new_calcul">
						<option value=0> </option>
						<?php
						try
						{
							$calculs = $bdd->query('SELECT * 
													FROM operation
													WHERE type_operation = '.$CALCUL.'
													ORDER BY id');
							while($calcul = $calculs->fetch())
							{
								echo '<option value='.$calcul['id'].'> ('.$calcul['id'].') '.$calcul['nom'].' </option>';
							}
						}
						catch(Exception $e)
						{
							echo '<span class=rouge>erreur sur la récupération des calculs : </span>';
							die('Erreur : '.$e->getMessage());
						}
						?>
					</select>
					<input class="bouton3" type="submit" value="Créer" />
				
				</form>
				</td></tr>
			<?php } ?>