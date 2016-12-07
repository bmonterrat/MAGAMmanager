
		<table>
			<tr>
				<td align="left">
				<label for="new_groupe_description_uti"> Propriété appliquée aux <span class="vert2">activités</span> : </label>
				</td>
			</tr>
			<tr>
				<td>
				<label for="new_groupe_min_act"> min: </label>
				<input class="small" type="number" name="new_groupe_min_act" 
					value=<?php echo $donnees_gp['min_act']; ?> 
					step="0.1"
				>
				<label for="new_groupe_max_act"> max: </label>
				<input class="small" type="number" name="new_groupe_max_act" 
					value=<?php echo $donnees_gp['max_act'];  ?>
					step="0.1"
				>
				<label for="new_groupe_pas_act"> pas: </label>
				<select name="new_groupe_pas_act" id="groupe">
					<option value=0.1 <?php if($donnees_gp['pas_act'] == 0.1) echo 'selected' ;?>	> 0,1 </option>
					<option value=0.5 <?php if($donnees_gp['pas_act'] == 0.5) echo 'selected' ;?>	> 0,5 </option>
					<option value=1   <?php if($donnees_gp['pas_act'] == 1) echo 'selected' ;?>		> 1 </option>
					<option value=5   <?php if($donnees_gp['pas_act'] == 5) echo 'selected' ;?>		> 5 </option>
					<option value=10  <?php if($donnees_gp['pas_act'] == 10) echo 'selected' ;?>	> 10 </option>
				</select>
				<label for="new_groupe_defaut_act"> défaut: </label>
				<input class="small" type="number" name="new_groupe_defaut_act" 
					value=<?php echo $donnees_gp['defaut_act']; ?>
					step="0.1"
				>
			</td></tr>
			<tr><td>
				<textarea type="number" name="new_groupe_description_act" rows="5" cols="40"><?php echo $donnees_gp['for_activite'];  ?> </textarea>
			</td>
			</tr>
		</table>
	</td><td id="proprietes_table_td">
		<table>
			<tr>
				<td align="left">
				<label for="new_groupe_description_uti"> Propriété appliquée aux <span class="bleu">utilisateurs</span> : </label>
				</td>
			</tr>
			<tr>
				<td>
				<label for="new_groupe_min_uti"> min:</label>
				<input class="small" type="number" name="new_groupe_min_uti" 
					value=<?php echo $donnees_gp['min_uti']; ?>
					step="0.1"
				>
				<label for="new_groupe_max_uti"> max:</label>
				<input class="small" type="number" name="new_groupe_max_uti" 
					value=<?php echo $donnees_gp['max_uti']; ?>
					step="0.1"
				>
				<label for="new_groupe_pas_uti"> pas: </label>
				<select name="new_groupe_pas_uti" id="groupe">
					<option value=0.1 <?php if($donnees_gp['pas_uti'] == 0.1) echo 'selected' ;?>	> 0,1 </option>
					<option value=0.5 <?php if($donnees_gp['pas_uti'] == 0.5) echo 'selected' ;?>	> 0,5 </option>
					<option value=1   <?php if($donnees_gp['pas_uti'] == 1) echo 'selected' ;?>		> 1 </option>
					<option value=5   <?php if($donnees_gp['pas_uti'] == 5) echo 'selected' ;?>		> 5 </option>
					<option value=10  <?php if($donnees_gp['pas_uti'] == 10) echo 'selected' ;?>	> 10 </option>
				</select>
				<label for="new_groupe_defaut_uti"> défaut: </label>
				<input class="small" type="number" name="new_groupe_defaut_uti" 
					value=<?php echo $donnees_gp['defaut_uti']; ?>
					step="0.1"
				>
			</td></tr>
			<tr><td>
				<textarea type="number" name="new_groupe_description_uti" rows="5" cols="40"><?php echo $donnees_gp['for_utilisateur']; ?></textarea>
			</td>
			</tr>
		</table>		
	</td><td>
						
