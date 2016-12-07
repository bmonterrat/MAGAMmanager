
			<?php
			//récupérer les applications de calculs et fusions
			try
			{
				//les calculs
				$application = $bdd->query('SELECT application.id, id_operation as id_calcul, operation.nom as nom
											FROM application
											JOIN operation
											ON application.id_operation = operation.id
											JOIN groupe_propriete
											ON application.id_groupe_pr = groupe_propriete.id
											WHERE application.id_groupe_pr IS NOT NULL
											AND (operation.type_operation = '.$CALCUL.' OR operation.type_operation = '.$FUSION.')
											AND groupe_propriete.bd = "'.$_SESSION['bd'].'"
											ORDER BY id');
				$ligne = 0;
				while($appli = $application->fetch())
				{
					$index['app'][$ligne++] = $appli;
				}
				$nb_app_calcul = $ligne;
				
				//les fusions
				$application = $bdd->query('SELECT application.id, id_operation as id_calcul, operation.nom as nom
											FROM application
											JOIN operation
											ON application.id_operation = operation.id
											WHERE application.id_groupe_pr IS NULL
											AND (operation.type_operation = '.$CALCUL.' OR operation.type_operation = '.$FUSION.')
											AND application.bd = "'.$_SESSION['bd'].'"
											');
				$ligne = $nb_app_calcul;
				while($appli = $application->fetch())
				{
					$index['app'][$ligne++] = $appli;
				}
				$nb_app_fusion = $ligne - $nb_app_calcul;
				
				//les références de fusion
				$reference = $bdd->query('SELECT * FROM fusion_references');
				while($ref = $reference->fetch())
				{
					$references[$ref['reference']][$ref['referenced']] = $ref['poids'];
				}
				
				//AFFICHAGE
				if(isset($_GET['affiche']) && $_GET['affiche']=="fusions")
				{
					//afficher les applications des calculs
					echo '<table class="visu_border_">';
					
					echo '<tr class="bold center">'; //ligne des titres
						echo '<td></td>';
						for($c = 0; $c < $nb_app_calcul; $c++)
						{
							$nom_appli = $index['app'][$c]['nom'];
							$id_appli = $index['app'][$c]['id'];
							echo '<td colspan='.($nb_activites+1).'>';
								echo '<a href="recommandation.php?affiche=fusions&suppr_appli='.$id_appli.'" title="supprimer cette application" > <img class="small" src="'.$sup.'" alt="supprimer" /> </a> ';
								echo '<span>'.$nom_appli.' </span>';
							echo '</td>';
						}
						for($f = 0; $f < $nb_app_fusion; $f++)
						{
							$nom_appli = $index['app'][$c+$f]['nom'];
							$id_appli = $index['app'][$c+$f]['id'];
							echo '<td colspan='.($nb_activites+1).'>';
								echo '<a href="recommandation.php?affiche=fusions&suppr_appli='.$id_appli.'" title="supprimer cette application" > <img class="small" src="'.$sup.'" alt="supprimer" /> </a>';
								echo '<span class="bold orange">'.$nom_appli.' </span>';
							echo '</td>';
						}
					echo '</tr>';
					
					echo '<tr class="bold">'; //ligne des valeurs de calculs
						echo '<td></td>';
						for($c = 0; $c < $nb_app_calcul; $c++)
						{
							$id_appli = $index['app'][$c]['id'];
							for($i = 0; $i < $nb_activites; $i++)
							{
								echo '<td class="center horiz verti">'.$resultat[$id_appli][$user_visu][$index['ac'][$i]['id']].'</td>';
							}
							echo '<td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>';
						}
					echo '</tr>';
				}
				
				$inc = $nb_app_calcul;
				for($f = 0; $f < $nb_app_fusion; $f++) //lignes des applications des fusions
				{
					$id_app_fusion = $index['app'][$f+$nb_app_calcul]['id'];
					$id_fusion = $index['app'][$f+$nb_app_calcul]['id_calcul'];
					
					if(isset($_GET['affiche']) && $_GET['affiche']=="fusions")
					{
						echo '<tr class="center">'; //ligne pour une application
						echo '<td> fusion '.$id_app_fusion.'</td>';
					}
					$ligne = 0;
					for($a = 0; $a < $inc; $a++) //un bloc peut-être fusionné
					{
						if(isset($_GET['affiche']) && $_GET['affiche']=="fusions")
						{
							echo '<td colspan='.$nb_activites.'>';
						}
						$id_appli = $index['app'][$a]['id'];
						if(isset($references[$id_app_fusion][$id_appli]))
						{
							if(isset($_GET['affiche']) && $_GET['affiche']=="fusions")
							{
								echo 'Fusionné [';
								if($id_fusion == 5 || $id_fusion == 6)
									echo $references[$id_app_fusion][$id_appli];
								else
									echo 'x';
								echo '] ->>';
							}
							$index_dans_resultat[$ligne] = $id_appli;
							$index_des_poids[$ligne] = $references[$id_app_fusion][$id_appli];
							$ligne++;
						}
						if(isset($_GET['affiche']) && $_GET['affiche']=="fusions")
						{
							echo '</td>';
							echo '<td></td>';
						}
					}
					$nb_referenced_applications = $ligne;
					$inc++;
					
					//ajouter la fusion au résultat
					$resultat = fusionner($id_fusion, $id_app_fusion, $index, $resultat, $index_dans_resultat, $index_des_poids, $nb_referenced_applications, $nb_utilisateurs, $nb_activites);
					
					if(isset($_GET['affiche']) && $_GET['affiche']=="fusions")
					{
						for($i = 0; $i < $nb_activites; $i++) //le résultat de la fusion
						{
							echo '<td class="center horiz verti orange bold">'.$resultat[$id_app_fusion][$user_visu][$index['ac'][$i]['id']].'</td>';
						}
						echo '<td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>';
						echo '</tr>';
					}
				}
				
				if(isset($_GET['affiche']) && $_GET['affiche']=="fusions") //Formulaire pour ajouter une fusion
				{
					echo '<tr>';
						echo '<td> nouvelle fusion : </td>';
						echo '<form method="post" action="recommandation.php?affiche=fusions" class="inline">';
							for($a = 0; $a < $nb_app_calcul + $nb_app_fusion; $a++)
							{
								$id_appli = $index['app'][$a]['id'];
						
								echo '<td class="center" colspan='.$nb_activites.'>';
									echo '<input type="checkbox" name="ref'.$id_appli.'" value="yes">';
									echo '<select name="poids'.$id_appli.'">';
										echo '<option value=1> 1 </option>';
										echo '<option value=2> 2 </option>';
										echo '<option value=3> 3 </option>';
										echo '<option value=4> 4 </option>';
										echo '<option value=5> 5 </option>';
								echo '</select>';
								echo '</td>';
								echo '<td></td>';
							}
							echo '<td>';
								echo '<label for="new_fusion"> Fusion : </label>';
								echo '<select name="new_fusion" id="new_fusion">';
									echo '<option value=0> </option>';
									try
									{
										$fusions = $bdd->query('SELECT * 
																FROM operation
																WHERE type_operation = '.$FUSION.'
																ORDER BY id');
										while($fusion = $fusions->fetch())
										{
											echo '<option value='.$fusion['id'].'> ('.$fusion['id'].') '.$fusion['nom'].' </option>';
										}
									}
									catch(Exception $e)
									{
										echo '<span class=rouge>erreur sur la récupération des fusions</span>';
										die('Erreur : '.$e->getMessage());
									}
								echo '</select>';
							echo '</td>';
							echo '<td>';
								echo '<input class="bouton3" type="submit" value="Ajouter" title="Ajouter une nouvelle fusion"/>';
							echo '</td>';
						echo '</form>';
					echo '</tr>';
					echo '</table>';
				}
			}
			catch(Exception $e)
			{
				echo '<span class=rouge>erreur les applications des fusions</span>';
				die('Erreur : '.$e->getMessage());
			}
			?>
