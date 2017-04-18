<?php 
	global $wpdb;

	$currentDate = date("j-M-Y");
	$queryResult = $wpdb->get_results("SELECT * FROM ". custom_namaz_table(), ARRAY_A);
	$queryResult2 = $wpdb->get_results("SELECT * FROM ". custom_namaz_text_table() ." WHERE date='".$currentDate."'", ARRAY_A);
	$namazLanguage = get_option('namaz-language');
	$namazes;
	$uniqueDate = array();
	$uniqueCity = array();
	$currentDateData;

	foreach ($queryResult as $value) {
		if ($value['date'] == $currentDate) {
			$currentDateData = $value;
			break;
		}
	}

	if ($namazLanguage == 1) {
		$namazes = array('Имсак', 'Фаджр', 'Восход', 'Зухр', 'Магриб');
	} else {
		$namazes = array('İmsak', 'Sübh', 'Gün çıxır', 'Zöhr', 'Məğrib');
	} 
?>

<div id="namaz-plugin">
<div class="np-top-wrap">
	<div class="np-info-block">
		<select id="namaz-plugin-date-select" name="date" class="np-widget-select">
			<?php for($i=0; $i < count($queryResult); $i++) { ?>

				<?php if (in_array($queryResult[$i]['date'], $uniqueDate)) continue; ?>

				<option 
				<?php if ($queryResult[$i]['date'] == $currentDate) { echo "selected"; } ?> 
				value="<?php echo $queryResult[$i]['date'];?>"><?php echo $queryResult[$i]['date'];?></option>

			<?php array_push($uniqueDate, $queryResult[$i]['date']); } ?>
		</select>
	</div>
	<div class="np-info-block">
		<select id="namaz-plugin-select" name="city" class="np-widget-select">
		<?php for($i=0; $i < count($queryResult); $i++) {?>

			<?php if (in_array($queryResult[$i]['city'], $uniqueCity)) continue; ?>

			<option 
			<?php if ($queryResult[$i]['city'] == $currentDateData['city']) { echo "selected"; } ?>
			value="<?php echo $queryResult[$i]['city'] ; ?>"><?php echo $queryResult[$i]['city'] ; ?></option>

		<?php array_push($uniqueCity, $queryResult[$i]['city']); } ?>
		</select>
	</div>
	<div class="np-info-block np-info-altdate"><?php echo $currentDateData['altdate'];?></div>
</div>
<div class="np-mid-wrap">
	<div class="np-time-block a">
		<div class="np-time-title"><?php echo $namazes[0]; ?></div>
		<div class="np-icon">
			<img src="<?php echo plugins_url( '/images/1.png', __FILE__ ); ?>" />
		</div>
		<div class="np-time"><?php echo $currentDateData['prayer_time1']; ?></div>
	</div>
	<div class="np-time-block b">
		<div class="np-time-title"><?php echo $namazes[1]; ?></div>
		<div class="np-icon">
			<img src="<?php echo plugins_url( '/images/2.png', __FILE__ ); ?>" />
		</div>
		<div class="np-time"><?php echo $currentDateData['prayer_time2']; ?></div>
	</div>
	<div class="np-time-block c">
		<div class="np-time-title"><?php echo $namazes[2]; ?></div>
		<div class="np-icon">
			<img src="<?php echo plugins_url( '/images/3.png', __FILE__ ); ?>" />
		</div>
		<div class="np-time"><?php echo $currentDateData['prayer_time3']; ?></div>
	</div>
	<div class="np-time-block d">
		<div class="np-time-title"><?php echo $namazes[3]; ?></div>
		<div class="np-icon">
			<img src="<?php echo plugins_url( '/images/4.png', __FILE__ ); ?>" />
		</div>
		<div class="np-time"><?php echo $currentDateData['prayer_time4']; ?></div>
	</div>
	<div class="np-time-block e">
		<div class="np-time-title"><?php echo $namazes[4]; ?></div>
		<div class="np-icon">
			<img src="<?php echo plugins_url( '/images/5.png', __FILE__ ); ?>" />
		</div>
		<div class="np-time"><?php echo $currentDateData['prayer_time5']; ?></div>
	</div>
</div>
<div class="np-text-block">
	<?php echo $queryResult2[0]['namaz_description']; ?>
</div>
</div>