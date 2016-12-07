
<?php

//TOTO : découper avec
//1 -> faire les calculs et mettre le res dans un tableau 2D (+ index sur distances)
//2 -> afficher le résultat
//Je pourrai ensuite faire des calculs de sélection entre les étapes 1 et 2.

	if(isset($_POST['datelim']))
		$_SESSION['datelim'] = $_POST['datelim'];	

	if(isset($_GET['affiche']) && $_GET['affiche']=="selections")
	{
		if(isset($id_app_fusion))
		{
			//récupérer la liste des selections appliquées
			try
			{
				$les_selections = $bdd->query('SELECT application.id, id_operation as id_selec, operation.nom as nom
												FROM application
												JOIN operation
												ON application.id_operation = operation.id
												WHERE application.id_groupe_pr IS NULL
												AND application.bd = "'.$_SESSION['bd'].'"
												AND operation.type_operation = '.$SELECTION.' ');
			
				$ligne = 0;
				while($une_selec = $les_selections->fetch()){
					$index['selec'][$ligne++] = $une_selec;
				}
				$nb_selec = $ligne;
			}
			catch(Exception $e)
			{
				echo '<span class=rouge>erreur sur la récupération de la sélection</span>';
				die('Erreur : '.$e->getMessage());
			}
			
			echo '<table>';
			
				//première ligne : liste des selections
				echo '<tr>';
				
					echo '<td></td>';
					echo '<td></td>';
					
					// Les sélections existantes
					for($t = 0; $t < $nb_selec; $t++)
					{
						echo '<td>';
							$rec = "";
							if(isset($_GET['rec']))
								$rec = '&rec=yes';
							echo '<form method="post" action="recommandation.php?affiche=selections'.$rec.'" class="inline">';
								echo '<a href="recommandation.php?affiche=selections&suppr_appli='.$index['selec'][$t]['id'].$rec.'" title="supprimer cette sélection" > <img class="small" src="'.$sup.'" alt="supprimer" /> </a> ';
								echo '<label for="mod_selec"> Sélection : </label>';
								echo '<br/>';
								echo '<select name="mod_selec">';
									echo '<option value=0> </option>';
									try
									{
										$selec = $bdd->query('SELECT * 
																FROM operation
																WHERE type_operation = '.$SELECTION.'
																ORDER BY id');
										while($selec_ = $selec->fetch())
										{
											echo '<option value='.$selec_['id'].' '; if($selec_['id'] == $index['selec'][$t]['id_selec']) echo " selected "; echo '>';
											echo '('.$selec_['id'].') '.$selec_['nom'].' ';
											echo '</option>';
										}
									}
									catch(Exception $e)
									{
										echo '<span class=rouge>erreur sur la récupération des fusions</span>';
										die('Erreur : '.$e->getMessage());
									}
								echo '</select>';
								echo '<input type="hidden" name="id_appli_selec" value="'.$index['selec'][$t]['id'].'">';
								echo '<input class="bouton3" type="submit" value="ok" title="Appliquer la sélection"/>';
							echo '</form>';
						echo '</td>';
					}
					
					echo '<td>'; // Nouvelle sélection
						echo '<form method="post" action="recommandation.php?affiche=selections'.$rec.'" class="inline">';
							echo '<label for="new_selection"> Nouvelle selection : </label>';
							echo '<br/>';
							echo '<select name="new_selection">';
								echo '<option value=0> </option>';
								try
								{
									$selec = $bdd->query('SELECT * 
															FROM operation
															WHERE type_operation = '.$SELECTION.'
															ORDER BY id');
									while($selec_ = $selec->fetch())
									{
										echo '<option value='.$selec_['id'].'>';
										echo '('.$selec_['id'].') '.$selec_['nom'].' ';
										echo '</option>';
									}
								}
								catch(Exception $e)
								{
									echo '<span class=rouge>erreur sur la récupération des fusions</span>';
									die('Erreur : '.$e->getMessage());
								}
							echo '</select>';
							echo '<input class="bouton3" type="submit" value="ok" title="Appliquer la sélection"/>';
						echo '</form>';
					echo '</td>';
					
				echo '</tr>';
			
				//deuxième ligne : resultat des fusions puis application des sélections
				echo '<tr>';
			
					// 1er bloc : le tableau avec les résultats de la fusion
					echo '<td>';
						if(!isset($_GET['rec']))
						{
							echo '<table>';
							echo '<tr><td></td>';//première ligne
							for($a = 0; $a < $nb_activites; $a++)
							{
								echo '<td class="center" title="'.$index['ac'][$a]['nom'].'">['.substr($index['ac'][$a]['nom'], 0, 8).']</td>';
							}
							echo '</tr>';	
							for($u = 0; $u < $nb_utilisateurs; $u++) //pour chaque utilisateur
							{
								echo '<tr>';
								$id_user = $index['ut'][$u]['id'];
								echo '<td> '.$index['ut'][$u]['nom'].' </td>';
								for($a = 0; $a < $nb_activites; $a++)
								{
									$id_activite = $index['ac'][$a]['id'];
									echo '<td class="center">'.$resultat[$id_app_fusion][$id_user][$id_activite].'</td>';
								}
								echo '</tr>';
							}
							echo '</table>';
						}
					echo '</td>';
											
					//2ème bloc : la flèche
					echo '<td>';
						echo '<div class="center"> <img src="images/fleche.png" alt="" style="width:60px;height:14px;"> &nbsp;&nbsp;</div>';
					echo '</td>';
				
					//les blocs suivants : les résultats des sélections
					$rec_mail = NULL;
					$recommandation = 0;
					for($t = 0; $t < $nb_selec; $t++)
					{
						$id_selection = $index['selec'][$t]['id_selec'];
						$id_app_selec = $index['selec'][$t]['id'];
						$recommandation = selectionner($id_selection, $id_app_selec, $id_app_fusion, $index, $resultat, $nb_utilisateurs, $nb_activites, $recommandation);
						echo '<td>';
							echo '<table>';
							echo '<tr>'; //première ligne
								echo '<td> utilisateur </td>';
								echo '<td> activité </td>';
								echo '<td> score </td>';
							echo '</tr>';
							for($u = 0; $u < $nb_utilisateurs; $u++) //pour chaque utilisateur
							{
								echo '<tr>';
									$id_util = $index['ut'][$u]['id'];
									echo '<td class="horiz verti"> '.$index['ut'][$u]['nom'].' </td>';
									$nom_activite = $recommandation[$id_app_selec][$id_util]['nom_activite'];
									echo '<td class="center horiz verti"> ['.substr($nom_activite, 0, 12).'] </td>';
									echo '<td class="center horiz verti"> '.$recommandation[$id_app_selec][$id_util]['score'].' </td>';
								echo '</tr>';
								$rec_mail[$t][$id_util] = $nom_activite;
							}
							echo '</table>';
							echo 'score moyen : '.$recommandation['score_moyen'].' ';
							echo '<br/>';
							echo 'nombre d\'activites : '.$recommandation['nb_activites'].' ';
						echo '</td>';
					}
					//FINAL : bloc pour les mails
					/*$datelim = '';
					if(isset($_SESSION['datelim'])) 
						$datelim = $_SESSION['datelim'];
					else
						$_SESSION['datelim'] = '';
					if($nb_selec >= 3)
					{
						echo '<td>';
							echo '<table>';
								echo '<tr>'; //première ligne
									echo '<td> utilisateur </td>';	
									echo '<td> rec1 </td>';
									echo '<td> rec2 </td>';
									echo '<td> rec3 </td>';
									echo '<td>';
										echo '<form method="post" action="recommandation.php?affiche=selections'.$rec.'" class="inline">';
											echo 'lim : ';
											echo '<input type="text" name="datelim" size="10" maxlength="20" value="'.$datelim.'" />';
											echo '<input class="bouton3" type="submit" value="ok" />';
										echo '</form>';
									echo '</td>';
								echo '</tr>';
								for($u = 0; $u < $nb_utilisateurs; $u++) //pour chaque utilisateur
								{
									echo '<tr>';
										$id_util = $index['ut'][$u]['id'];
										echo '<td class="horiz verti"> '.$index['ut'][$u]['mail'].' </td>';
										echo '<td class="horiz verti"> '.$rec_mail[$nb_selec-3][$id_util].' </td>';
										echo '<td class="horiz verti"> '.$rec_mail[$nb_selec-2][$id_util].' </td>';
										echo '<td class="horiz verti"> '.$rec_mail[$nb_selec-1][$id_util].' </td>';
										echo '<td class="horiz">';
											echo '<form method="post" action="recommandation.php?affiche=selections'.$rec.'" class="inline">';
												echo '<input type="hidden" name="destinataire" value="'.$index['ut'][$u]['mail'].'"/>';
												echo '<input type="hidden" name="act1" value="'.$rec_mail[$nb_selec-3][$id_util].'"/>';
												echo '<input type="hidden" name="act2" value="'.$rec_mail[$nb_selec-2][$id_util].'"/>';
												echo '<input type="hidden" name="act3" value="'.$rec_mail[$nb_selec-1][$id_util].'"/>';
												echo '<input type="hidden" name="idMoodle" value="'.substr($index['ut'][$u]['nom'], 0, 4).$index['ut'][$u]['id'].'"/>';
												echo '<input type="hidden" name="nom" value="'.$index['ut'][$u]['nom'].'"/>';
												echo '<input type="hidden" name="deadline" value="'.$_SESSION['datelim'].'">';
												if(isset($_SESSION['datelim']) && $_SESSION['datelim'] != '')
													echo '<input type="submit" class="bouton" value="Envoyer mail"/>'; 
												else
													echo 'indiquer une date';
											echo '</form>';
										echo '</td>';
									echo '</tr>';
								}
							echo '</table>';
						echo '</td>';
					}*/					
				echo '</tr>';
				
			echo '</table>';
		}
		else
		{
			echo '<div class=rouge> Aucun résultat à afficher, car il n\'y a pas de fusion.<div>';
		}
	}
	echo '</td>';
?>
	