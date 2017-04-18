<?php
global $wpdb;
$current_date = date("d-M-Y");
$charset_collate = $wpdb->get_charset_collate();

$sql = "CREATE TABLE ". custom_namaz_table() ." (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  city VARCHAR(100),
  date VARCHAR(100),
  altdate VARCHAR(100),
  prayer_time1 text,
  prayer_time2 text,
  prayer_time3 text,
  prayer_time4 text,
  prayer_time5 text,
  PRIMARY KEY  (id)
) $charset_collate;";

dbDelta( $sql );


$sql2 = "CREATE TABLE ". custom_namaz_text_table() ." (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  city VARCHAR(100),
  date VARCHAR(100),
  namaz_description text,
  PRIMARY KEY  (id)
) $charset_collate;";

dbDelta( $sql2 );
?>