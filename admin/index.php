<?php 
	echo "<h1>Расписание Намазов</h1>";
	echo "Сегодняшняя дата: ".date("d-M-Y");

	//---------------- FORM SUBMIT HANDLING HERE -----------------//
	if (file_exists(CUSTOM_NAMAZ_PLUGIN_DIR. "/admin/update-db-script.php")) 
	{
		include CUSTOM_NAMAZ_PLUGIN_DIR. "/admin/update-db-script.php";
	}

	global $wpdb;

	$queryResult = $wpdb->get_results("SELECT * FROM ". custom_namaz_table(), ARRAY_A);
	$queryResult2 = $wpdb->get_results("SELECT * FROM ". custom_namaz_text_table(), ARRAY_A);
	$namazLanguage = get_option('namaz-language');
?>
<form method="post" action="">
<h2 class="title">Добавить/Удалить город</h2>
<p>Для сохранения измениний после всех добавлений нужно нажать кнопку "Сохранить измененния".</p>
<table class="form-table">
	<tr>
		<th class="thin" style="padding-left: 10px;"><label>Язык:</label></th>
		<td class="thin" colspan="2">
		<label>
			<input type="radio" name="namaz-language" value="1" <?php if ($namazLanguage == 1) echo "checked" ?> > Русский
		</label>&nbsp;&nbsp;&nbsp;
		<label>
  			<input type="radio" name="namaz-language" value="2" <?php if ($namazLanguage == 2) echo "checked" ?> > Азербайджанский
		</label>
		</td>
	</tr>
	
<?php 
	if (count($queryResult) > 0) { 
		$lastCity;

		for ($i = 0; $i < count($queryResult); $i++) {
	?>


	<?php if (empty($lastCity) || $queryResult[$i]['city'] != $lastCity) { ?>
			<tr class="city-block">
				<td colspan="3">
					<table class="form-table">
					<tbody>
						<tr>
							<th class="thin" scope="row">
								<label>Город:</label>
							</th>
							<td width="15%" class="thin">
								<code class="city-wrap"><?php echo $queryResult[$i]['city']; ?></code>
								<input name="city[]" type="hidden" value="<?php echo $queryResult[$i]['city']; ?>">
							</td>
							<td class="thin"></td>
						</tr>
<?php }?>


						<tr class="added-row">
							<th class="thin">
								Дата: <code class="mainDate" data-city="<?php echo $queryResult[$i]['city']; ?>"><?php echo $queryResult[$i]['date']; ?></code>
								<input name="date[<?php echo $queryResult[$i]['city']; ?>][]" type="hidden" value="<?php echo $queryResult[$i]['date']; ?>">
							</th>
							<td class="thin">
								<input name="altdate[<?php echo $queryResult[$i]['city']; ?>][]" class="regular-text namaz-input altdate" placeholder="Дата в Исламе" 
								value="<?php echo $queryResult[$i]['altdate']; ?>">
							</td>
							<td class="thin">
								<input name="prayer_time1[<?php echo $queryResult[$i]['city']; ?>][]" class="regular-text namaz-input-small pt1" placeholder="İmsak" 
								value="<?php echo $queryResult[$i]['prayer_time1']; ?>"><input name="prayer_time2[<?php echo $queryResult[$i]['city']; ?>][]" class="regular-text namaz-input-small pt2" placeholder="Sübh" value="<?php echo $queryResult[$i]['prayer_time2']; ?>"><input name="prayer_time3[<?php echo $queryResult[$i]['city']; ?>][]" class="regular-text namaz-input-small pt3" placeholder="Gün çıxır" 
								value="<?php echo $queryResult[$i]['prayer_time3']; ?>"><input name="prayer_time4[<?php echo $queryResult[$i]['city']; ?>][]" class="regular-text namaz-input-small pt4" placeholder="Zöhr" 
								value="<?php echo $queryResult[$i]['prayer_time4']; ?>"><input name="prayer_time5[<?php echo $queryResult[$i]['city']; ?>][]" class="regular-text namaz-input-small pt5" placeholder="Məğrib" value="<?php echo $queryResult[$i]['prayer_time5']; ?>"><span class="button button-secondary remove-city" onclick="nz.removeTimeRow(this)">Удалить</span>
							</td>
						</tr>

							
			<?php if( !$queryResult[$i+1] || $queryResult[$i+1]['city'] != $queryResult[$i]['city']) {?>
							<tr>
								<th class="thin">
									Дата: <code class="mainDate" data-city="<?php echo $queryResult[$i]['city']; ?>">
									<?php echo date('j-M-Y', strtotime($queryResult[$i]['date'] .' +1 day')); ?>
									</code>
									<input name="" type="hidden" value="">
								</th>
								<td class="thin">
									<input name="" class="regular-text namaz-input altdate" placeholder="Дата в Исламе" value="">
								</td>
								<td class="thin">
									<input name="" class="regular-text namaz-input-small pt1" placeholder="İmsak" value=""><input name="" class="regular-text namaz-input-small pt2" placeholder="Sübh" value=""><input name="" class="regular-text namaz-input-small pt3" placeholder="Gün çıxır" value=""><input name="" class="regular-text namaz-input-small pt4" placeholder="Zöhr" value=""><input name="" class="regular-text namaz-input-small pt5" placeholder="Məğrib" value=""><span class="button button-primary add-time" onclick="nz.addTimeHandler(this)">Добавить время</span>
								</td>
							</tr>
							<tr>
								<td class="thin-hor thin">
									<span class="button button-secondary remove-city" onclick="nz.removeSityRow(this)">Удалить город</span>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<?php 
				}
				$lastCity = $queryResult[$i]['city'];
			?>

	<?php } } ?>

	<tr>
		<th scope="row">
			<label>Название города:</label>
		</th>
		<td width="15%">
			<input class="regular-text"/>
		</td>
		<td>
			<span class="button button-primary add-city">Добавить</span>
		</td>
	</tr>
</table>
<h2 class="title">Текст</h2>
<table class="form-table text-table">
	<!-- Show texts on each existing day from db -->
	<?php 
	if (count($queryResult2) > 0) {
		for ($i = 0; $i < count($queryResult2); $i++) { 
			$textDate = $queryResult2[$i]['date'];
			$dateText = $queryResult2[$i]['namaz_description'];

			include CUSTOM_NAMAZ_PLUGIN_DIR . "/admin/add-text-row-template.php";
		} 

		//--------------- INSERT LAST EMPTY TEXT FIELD -------------//
		$textDate = date('j-M-Y', strtotime($queryResult2[count($queryResult2)-1]['date'] .' +1 day'));
		$dateText = "";

		include CUSTOM_NAMAZ_PLUGIN_DIR . "/admin/add-text-row-template.php";
	} else {
		$textDate = date("j-M-Y");
		$dateText = "";

		 include CUSTOM_NAMAZ_PLUGIN_DIR . "/admin/add-text-row-template.php";
	} ?>
</table>
<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="Сохранить изменения">
</p>
</form>