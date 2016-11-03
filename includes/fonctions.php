<?php

function requete_sql($requete) {
	$sql = mysql_connect(sql_server, sql_user,sql_pass);
	mysql_select_db(sql_database, $sql);
	$resultat = mysql_query($requete,$sql);
	// $resultat fournit la valeur de retour par défaut de mysql_query

	if(preg_match("/INSERT/", $requete) && $resultat) {
		$resultat = mysql_insert_id($sql);
	}

	mysql_close($sql);

	return $resultat;
}

function format_date($str) {
	$mois = array('Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre');

	$tableau = explode(' ', $str);
	$date = explode('-', $tableau[0]);
	$heure = explode(':', $tableau[1]);

	return $date[2].' '.$mois[$date[1]-1].' '.$date[0].
	' à '.$heure[0].'h'.$heure[1];
}