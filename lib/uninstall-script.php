<?php 
$sql = "DROP TABLE ". custom_namaz_table();
dbDelta( $sql );

$sql2 = "DROP TABLE ". custom_namaz_text_table();
dbDelta( $sql2 );
?>