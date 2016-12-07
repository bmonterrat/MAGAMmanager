<?php

function calculer($id_calcul, $valeurs, $index, $nb_proprietes, $nb_utilisateurs, $nb_activites)
{
	
	isset($resultat);
	$iter_random = 0;
	
	for($u = 0; $u < $nb_utilisateurs; $u++) //pour chaque utilisateur
	{
		$id_util = $index['ut'][$u]['id'];
		for($a = 0; $a < $nb_activites; $a++) //pour chaque activité
		{
			$id_acti = $index['ac'][$a]['id'];
				
			if($id_calcul != 13)
			{	
				if($nb_proprietes > 0)
				{
					if($id_calcul == 1) //CALCUL PRODUIT
					{
						$somme = 0;
						$res = 0;
						for($p = 0; $p < $nb_proprietes; $p++)
						{
							$id_prop = $index['pr'][$p]['id'];
							$va = $valeurs['acti'][$id_acti][$id_prop];
							$vu = $valeurs['util'][$id_util][$id_prop];
							$somme += $vu * $va; //calcul
							//echo 'recuperation sur utilisateur '.$id_util.'<br/>';
						}
						$val = $somme / $nb_proprietes;
						
						//normalisation
						$max_raisonnable = 8; //difficile de décider commen choisir ces valeurs...
						$min_raisonnable = -0;
						if($val > $max_raisonnable)
							$res = 1;
						else if($val < $min_raisonnable)
							$res = 0;
						else
							$res = ($val - $min_raisonnable) / ($max_raisonnable - $min_raisonnable);
						
						//solution temporaire pour grossir un résultat trop petit :
						$res = $res * 10;
						
						$resultat[$id_util][$id_acti] = number_format($res, 2);
					}
					else if($id_calcul == 2) //CALCUL DIFFERENCE
					{
						$somme = 0;
						for($p = 0; $p < $nb_proprietes; $p++)
						{
							$id_prop = $index['pr'][$p]['id'];
							$va = $valeurs['acti'][$id_acti][$id_prop];
							$vu = $valeurs['util'][$id_util][$id_prop];
							$somme += 1 - abs($va * $vu); //calcul
						}
						
						//normalisation
						//...
						
						$resultat[$id_util][$id_acti] = number_format($somme/$nb_proprietes, 2);
					}
					else if($id_calcul == 7) //BESOIN D'APPRENDRE
					{
						$somme = 0;
						for($p = 0; $p < $nb_proprietes; $p++)
						{
							$id_prop = $index['pr'][$p]['id'];
							$va = $valeurs['acti'][$id_acti][$id_prop];
							$vu = $valeurs['util'][$id_util][$id_prop];
							$somme += (1 - $vu) * $va; //calcul
						}
						
						//solution temporaire pour grossir un résultat trop petit :
						$somme = $somme * 100;
						
						//normalisation ?
						//...
						
						$resultat[$id_util][$id_acti] = number_format($somme/$nb_proprietes, 2);
					}
					else if($id_calcul == 12) //FACTEUR ALEATOIRE
					{					
						$list_alea[0] = 0.058789879; $list_alea[1] = 0.060817219; $list_alea[2] = 0.004858176; $list_alea[3] = 0.064991672; $list_alea[4] = 0.07930714;
						$list_alea[5] = 0.035118176; $list_alea[6] = 0.029100761; $list_alea[7] = 0.05155764; $list_alea[8] = 0.029476226; $list_alea[9] = 0.005821429;
						$list_alea[10] = 0.083117939; $list_alea[11] = 0.078816472; $list_alea[12] = 0.076916519; $list_alea[13] = 0.025457583; $list_alea[14] = 0.038506069;
						$list_alea[15] = 0.051595817; $list_alea[16] = 0.095011843; $list_alea[17] = 0.021108113; $list_alea[18] = 0.032993812; $list_alea[19] = 0.026390791;
						$list_alea[20] = 0.053037954; $list_alea[21] = 0.062520496; $list_alea[22] = 0.081040332; $list_alea[23] = 0.011013603; $list_alea[24] = 0.081105678;
						$list_alea[25] = 0.098945756; $list_alea[26] = 0.082778801; $list_alea[27] = 0.083411356; $list_alea[28] = 0.03443252; $list_alea[29] = 0.07849645;
						$list_alea[30] = 0.018432885; $list_alea[31] = 0.085722928; $list_alea[32] = 0.001245459; $list_alea[33] = 0.00811219; $list_alea[34] = 0.066584689;
						$list_alea[35] = 0.030940799; $list_alea[36] = 0.081406251; $list_alea[37] = 0.096949535; $list_alea[38] = 0.007043143;	$list_alea[39] = 0.030714411;
						$list_alea[40] = 0.09196076;
						
						$graine = $id_util+$id_acti+$iter_random;
						$resultat[$id_util][$id_acti] = number_format($list_alea[$graine%40], 2);
						$iter_random++;
					}
					/*else if($id_calcul == 8) //EVITER REPETITION
					{
						$res = 0;
						for($p = 0; $p < $nb_proprietes; $p++)
						{
							$id_prop = $index['pr'][$p]['id'];
							$va = $valeurs['acti'][$id_acti][$id_prop];
							$vu = $valeurs['util'][$id_util][$id_prop];
							if($va==0)
							{
								echo "ERREUR : le profil de l'utilisateur est nul. Les valeurs calculées sont erronées.<br/>";
								$res = 0;
							}
							else
							{
								if($va > $vu)
									$res = ($va - $vu) / $va;
								else
									$res = 0;
							}
						}
						
						//pas besoin de normalisation
						
						$resultat[$id_util][$id_acti] = number_format($res, 2);
					}*/
					else
						$resultat[$id_util][$id_acti] = "erreur";
				}
				else
						$resultat[$id_util][$id_acti] = "0 prop";
			}
			else
			{
				$resultat[$id_util][$id_acti] = $valeurs['croi'][$id_acti][$id_util];
			}
		}
	}
	
	return $resultat;
}


function fusionner($id_fusion, $id_application, $index, $resultat, $index_dans_resultat, $index_des_poids, $nb_referenced_applications, $nb_utilisateurs, $nb_activites)
{
	for($u = 0; $u < $nb_utilisateurs; $u++) //pour chaque utilisateur
	{
		$id_util = $index['ut'][$u]['id'];
		for($a = 0; $a < $nb_activites; $a++) //pour chaque activité
		{
			$id_acti = $index['ac'][$a]['id'];
			if($nb_referenced_applications > 0)
			{
				if($id_fusion == 3) //FUSION MIN
				{
					$min = 1000000000000000;
					for($r = 0; $r < $nb_referenced_applications; $r++)
					{
						$valeur = $resultat[$index_dans_resultat[$r]][$id_util][$id_acti];
						if($valeur < $min)
							$min = $valeur;
					}
					$resultat[$id_application][$id_util][$id_acti] = number_format($min, 2);
				}
				else if($id_fusion == 4) //FUSION MAX
				{
					$max = -1000000000000000;
					for($r = 0; $r < $nb_referenced_applications; $r++)
					{
						$valeur = $resultat[$index_dans_resultat[$r]][$id_util][$id_acti];
						if($valeur > $max)
							$max = $valeur;
					}
					$resultat[$id_application][$id_util][$id_acti] = number_format($max, 2);
				}
				else if($id_fusion == 5) //FUSION PRODUIT
				{
					$res = 1;
					for($r = 0; $r < $nb_referenced_applications; $r++)
					{
						for($iter_poids = 0; $iter_poids < $index_des_poids[$r]; $iter_poids++)
						{
							$valeur = $resultat[$index_dans_resultat[$r]][$id_util][$id_acti];
							$res = $res * $valeur;
						}
					}
					$resultat[$id_application][$id_util][$id_acti] = number_format($res, 2);
				}
				else if($id_fusion == 6) //FUSION MOYENNE
				{
					$res = 0;
					$nb_appli_ponderees = 0;
					for($r = 0; $r < $nb_referenced_applications; $r++)
					{
						for($iter_poids = 0; $iter_poids < $index_des_poids[$r]; $iter_poids++)
						{
							$valeur = $resultat[$index_dans_resultat[$r]][$id_util][$id_acti];
							$res = $res + $valeur;
							$nb_appli_ponderees++;
						}
					}
					$resultat[$id_application][$id_util][$id_acti] = number_format($res / $nb_appli_ponderees, 2);
				}
				
				else if($id_fusion == 11) //FUSION SOMME
				{
					$res = 0;
					$nb_appli_ponderees = 0;
					for($r = 0; $r < $nb_referenced_applications; $r++)
					{
						for($iter_poids = 0; $iter_poids < $index_des_poids[$r]; $iter_poids++)
						{
							$valeur = $resultat[$index_dans_resultat[$r]][$id_util][$id_acti];
							$res = $res + $valeur;
							$nb_appli_ponderees++;
						}
					}
					$resultat[$id_application][$id_util][$id_acti] = number_format($res, 2);
				}
				else //si FUSION NON RECONNUE
					$resultat[$id_application][$id_util][$id_acti] = "erreur";
			}
			else
			{
				$resultat[$id_application][$id_util][$id_acti] = "manque ref";
			}
		}
	}
	
	return $resultat;
}

function selectionner($id_transformation, $id_app_transfo, $id_app_fusion, $index, $resultat, $nb_utilisateurs, $nb_activites, $rec)
{
	$interdiction['0'] = 'initialisation';
	if(isset($rec['interdiction1']))
	{
		for($u = 0; $u < $nb_utilisateurs; $u++) //pour chaque utilisateur
		{
			$id_util = $index['ut'][$u]['id'];
			$interdiction['1'][$id_util] = $rec['interdiction1'][$id_util];
			$recommandation['interdiction1'][$id_util] = $rec['interdiction1'][$id_util];
		}
	}
	if(isset($rec['interdiction2']))
	{
		for($u = 0; $u < $nb_utilisateurs; $u++) //pour chaque utilisateur
		{
			$id_util = $index['ut'][$u]['id'];
			$interdiction['2'][$id_util] = $rec['interdiction2'][$id_util];
		}
	}
	$recommandation['0'] = 'initialisation';
	
	if($id_transformation == 9) //1e ACTIVITE MEILLEURE POUR CHACUN
	{
		$score_total = 0;
		for($u = 0; $u < $nb_utilisateurs; $u++) //pour chaque utilisateur
		{
			$id_util = $index['ut'][$u]['id'];
			$current_max = -10000000;
			$current_activite_position = "erreur";
			for($a = 0; $a < $nb_activites; $a++) //pour chaque activite
			{
				$id_acti = $index['ac'][$a]['id'];
				if($resultat[$id_app_fusion][$id_util][$id_acti] > $current_max)
				{
					$current_max = $resultat[$id_app_fusion][$id_util][$id_acti];
					$current_activite_position = $a;
				}
			}
			$recommandation[$id_app_transfo][$id_util]['id_activite'] = $index['ac'][$current_activite_position]['id'];
			$recommandation[$id_app_transfo][$id_util]['nom_activite'] = $index['ac'][$current_activite_position]['nom'];
			$recommandation['interdiction1'][$id_util] = $index['ac'][$current_activite_position]['nom'];
			$recommandation[$id_app_transfo][$id_util]['score'] = number_format($current_max, 2);
			$score_total += $current_max;
		}
		$recommandation['score_moyen'] = number_format($score_total / $nb_utilisateurs, 2);
		$recommandation['nb_activites'] = "à calculer...";
	}
	else if($id_transformation == 10) //1e ACTIVITE MEILLEURE POUR LE GROUPE
	{
		isset($scores_moyens);
		for($a = 0; $a < $nb_activites; $a++) //pour chaque activite
		{
			$id_acti = $index['ac'][$a]['id'];
			$somme_scores = 0;
			for($u = 0; $u < $nb_utilisateurs; $u++) //pour chaque utilisateur
			{
				$id_util = $index['ut'][$u]['id'];
				$somme_scores += $resultat[$id_app_fusion][$id_util][$id_acti];
			}
			$score_moyen[$a] = $somme_scores / $nb_utilisateurs;
		}
		$current_max = -10000000;
		$current_activite_position = "erreur";
		for($a = 0; $a < $nb_activites; $a++) //pour chaque activite
		{
			if($score_moyen[$a] > $current_max)
			{
				$current_max = $score_moyen[$a];
				$current_activite_position = $a;
			}
		}
		for($u = 0; $u < $nb_utilisateurs; $u++) //pour chaque utilisateur
		{
			$id_util = $index['ut'][$u]['id'];
			$recommandation[$id_app_transfo][$id_util]['id_activite'] = $index['ac'][$current_activite_position]['id'];
			$recommandation[$id_app_transfo][$id_util]['nom_activite'] = $index['ac'][$current_activite_position]['nom'];
			$recommandation[$id_app_transfo][$id_util]['score'] = number_format($current_max, 2);
		}
		$recommandation['score_moyen'] = number_format($current_max, 2);
		$recommandation['nb_activites'] = 1;
	}
	else if($id_transformation == 15) //2e ACTIVITE MEILLEURE POUR CHACUN
	{
		$score_total = 0;
		for($u = 0; $u < $nb_utilisateurs; $u++) //pour chaque utilisateur
		{
			$id_util = $index['ut'][$u]['id'];
			$current_max = -10000000;
			$current_second_max = -10000001;
			$current_activite_position = "erreur 303";
			$current_best_id = "erreur 304";
			for($a = 0; $a < $nb_activites; $a++) //pour chaque activite
			{
				if(substr($interdiction['1'][$id_util], 0, 3) != substr($index['ac'][$a]['nom'], 0, 3))
				{
					$id_acti = $index['ac'][$a]['id'];
					$valeur = $resultat[$id_app_fusion][$id_util][$id_acti];
					if($valeur > $current_max && $valeur > $current_second_max)
					{
						$current_second_max = $current_max;
						$current_activite_position = $current_best_id;
						$current_max = $valeur;
						$current_best_id = $a;
					}
					else if($valeur <= $current_max && $valeur > $current_second_max)
					{
						$current_second_max = $valeur;
						$current_activite_position = $a;
					}
				}
			}
			$recommandation[$id_app_transfo][$id_util]['id_activite'] = $index['ac'][$current_activite_position]['id'];
			$recommandation[$id_app_transfo][$id_util]['nom_activite'] = $index['ac'][$current_activite_position]['nom'];
			$recommandation[$id_app_transfo][$id_util]['score'] = number_format($current_second_max, 2);
			$recommandation['interdiction2'][$id_util] = $index['ac'][$current_activite_position]['nom'];
			$score_total += $current_second_max;
		}
		$recommandation['score_moyen'] = number_format($score_total / $nb_utilisateurs, 2);
		$recommandation['nb_activites'] = "à calculer...";
	}
	else if($id_transformation == 16) //3e ACTIVITE MEILLEURE POUR CHACUN
	{
		$score_total = 0;
		for($u = 0; $u < $nb_utilisateurs; $u++) //pour chaque utilisateur
		{
			$id_util = $index['ut'][$u]['id'];
			$current_max = -10000000;
			$current_second_max = -10000001;
			$current_third_max = -10000002;
			$current_third_id = "erreur 306";
			$current_second_id = "erreur 307";
			$current_best_id = "erreur 308";
			for($a = 0; $a < $nb_activites; $a++) //pour chaque activite
			{
				if(substr($interdiction['1'][$id_util], 0, 3) != substr($index['ac'][$a]['nom'], 0, 3) && substr($interdiction['2'][$id_util], 0, 3) != substr($index['ac'][$a]['nom'], 0, 3))
				{
					$id_acti = $index['ac'][$a]['id'];
					$valeur = $resultat[$id_app_fusion][$id_util][$id_acti];
					if($valeur > $current_max)
					{
						$current_third_max = $current_second_max;
						$current_third_id = $current_second_id;
						$current_second_max = $current_max;
						$current_second_id = $current_best_id;
						$current_max = $valeur;
						$current_best_id = $a;
					}
					else if($valeur <= $current_max && $valeur > $current_second_max)
					{
						$current_third_max = $current_second_max;
						$current_third_id = $current_second_id;
						$current_second_max = $valeur;
						$current_second_id = $a;
					}
					else if($valeur <= $current_second_max && $valeur > $current_third_max)
					{
						$current_third_max = $valeur;
						$current_third_id = $a;
					}
				}
			}
			$recommandation[$id_app_transfo][$id_util]['id_activite'] = $index['ac'][$current_third_id]['id'];
			$recommandation[$id_app_transfo][$id_util]['nom_activite'] = $index['ac'][$current_third_id]['nom'];
			$recommandation[$id_app_transfo][$id_util]['score'] = number_format($current_third_max, 2);
			$score_total += $current_third_max;
		}
		$recommandation['score_moyen'] = number_format($score_total / $nb_utilisateurs, 2);
		$recommandation['nb_activites'] = "à calculer...";
	}
	else //si TRANSFORMATION NON RECONNUE
	{
		for($u = 0; $u < $nb_utilisateurs; $u++) //pour chaque utilisateur
		{
			$id_util = $index['ut'][$u]['id'];
			$recommandation[$id_app_transfo][$id_util]['id_activite'] = "erreur";
			$recommandation[$id_app_transfo][$id_util]['nom_activite'] = "erreur";
			$recommandation[$id_app_transfo][$id_util]['score'] = "erreur";
		}
		$recommandation['score_moyen'] = "erreur";
		$recommandation['nb_activites'] = "erreur";
	}
	
	return $recommandation;
}
?>