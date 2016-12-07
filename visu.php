			<!-- choisir l'utilisateur visu -->
			<?php
				$rec = '';
				if(isset($_GET['rec']))
					$rec = '&rec=yes';
			?>
			<tr class="espace_up_down"><td colspan="3">
				<?php echo '<form method="post" action="recommandation.php?affiche='.$_GET['affiche'].$rec.'" class=block id="change_visu">'; ?>
					<img src="images/voir.png" alt="" style="width:20px;height:14px;">
					<label for="mod_visu_user">
						Visualiser sur 
						<?php if(!(isset($_GET['affiche'])) || $_GET['affiche']!="transformations") echo "l'utilisateur";
								else echo "le groupe de"; ?>
					</label>
					<select name="mod_visu_user">
					<?php
						try
						{
							$liste_users = $bdd->query('SELECT groupe_utilisateur.id as id_gp, groupe_utilisateur.nom as nom_gp, utilisateur.id as id_ut, utilisateur.nom as nom_ut 
														FROM utilisateur, groupe_utilisateur 
														WHERE utilisateur.id_groupe = groupe_utilisateur.id
														AND groupe_utilisateur.bd = "'.$_SESSION['bd'].'"
														ORDER BY id_gp, nom_ut');
							while($util = $liste_users->fetch()){
								echo '<option value='.$util['id_ut'].' '; if($util['id_ut'] == $user_visu) echo " selected "; echo '>';
								echo '(gp '.$util['id_gp'].') '.$util['nom_ut'].'';
								echo '</option>';
							}
						}				
						catch(Exception $e)
						{
							echo '<span class=rouge>erreur sur la récupération des utilisateurs</span>';
							die('Erreur : '.$e->getMessage());
						}
					?>
					</select>
					<label for="mod_visu_act"> et les activités du groupe : </label>
					<select name="mod_visu_act">
					<?php
						try
						{
							$liste_gp_acti = $bdd->query('SELECT groupe_activite.id as id_gp, groupe_activite.nom as nom_gp
														FROM groupe_activite
														WHERE groupe_activite.bd = "'.$_SESSION['bd'].'"
														ORDER BY id_gp');
							while($acti = $liste_gp_acti->fetch()){
								echo '<option value='.$acti['id_gp'].''; if($acti['id_gp'] == $activite_ref_groupe) echo ' selected '; echo '>';
								echo '('.$acti['id_gp'].') '.$acti['nom_gp'].'';
								echo '</option>';
							}
						}				
						catch(Exception $e)
						{
							echo '<span class=rouge>erreur sur la récupération des utilisateurs</span>';
							die('Erreur : '.$e->getMessage());
						}
					?>
					</select>
					<input class="bouton3" type="submit" value="ok" />
				</form>
			</td></tr>
