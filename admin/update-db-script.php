<?php 
global $wpdb;
	
if (count($_POST) > 0) {
	//----------------DELETE ALL FIELDS FROM TIME TABLE---------------------//
	$wpdb->query("DELETE FROM ". custom_namaz_table());
	$wpdb->query("DELETE FROM ". custom_namaz_text_table());

	//--------------------------UPDATE LANGUAGE-------------------------------//
	update_option('namaz-language', $_POST['namaz-language']);

	//----------------REWRITE NEW FILELDS IN TABLE -------------------------//
	for ($i = 0; $i < count($_POST['city']); $i++)
	{
	   $city = $_POST['city'][$i];
	   
	   for ($k = 0; $k < count($_POST['date'][$city]); $k++)
	   {
	   		$wpdb->query
			(
				$wpdb->prepare
				(
					"INSERT INTO " . custom_namaz_table() . "(city, date, altdate, prayer_time1, prayer_time2,
					prayer_time3, prayer_time4, prayer_time5) VALUES(%s, %s, %s, %s, %s, %s, %s, %s)",
					$city,
					$_POST['date'][$city][$k],
					$_POST['altdate'][$city][$k],
					$_POST['prayer_time1'][$city][$k],
					$_POST['prayer_time2'][$city][$k],
					$_POST['prayer_time3'][$city][$k],
					$_POST['prayer_time4'][$city][$k],
					$_POST['prayer_time5'][$city][$k]
				)
			);
	   }
	}

	//-------------------- INSERT TEXT FIELDS ----------------------//
	for ($i = 0; $i < count($_POST['namaz-description']); $i++) {

		if ( !empty($_POST['namaz-description'][$i]) ) {
			$wpdb->query
				(
					$wpdb->prepare
					(
						"INSERT INTO " . custom_namaz_text_table() . "(date, namaz_description) VALUES(%s, %s)",
						$_POST['desc-date'][$i],
						$_POST['namaz-description'][$i]
					)
				);
		}

	}
 }
?>