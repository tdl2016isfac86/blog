<?php 
include('header.php');

if(isset($_GET['id'])) {
	$author = requete_sql("
		SELECT *
		FROM author
		WHERE id='".addslashes($_GET['id'])."'
		");

	$a = mysql_fetch_assoc($author);

	echo '<div>
	<h1>'.$a['public_name'].'</h1>';
	echo '<a href="mailto:'.$a['email'].'">'.$a['email'].'</a>';
	echo '</div>';

	if(isset($_GET['page'])) {
		$page = $_GET['page']-1;
	}
	else {
		$page = 0;
	}
	$nb_resultats_par_page = 5;
	$liste_articles = requete_sql("
		SELECT *
		FROM post
		WHERE author_id = '".addslashes($_GET['id'])."'
		ORDER BY publication_date DESC
		LIMIT ".($page*$nb_resultats_par_page).",".$nb_resultats_par_page."
		");

	echo '<div><h3>Les articles de '.$a['public_name'].'</h3>';
	while($i = mysql_fetch_assoc($liste_articles)) {
		echo '<div>
		<h3><a href="post.php?id='.$i['id'].'">'.$i['title'].'</a></h3>';
		echo '<p>'.substr($i['content'], 0, 30).'...</p>';
		echo 'Publié le '.format_date($i['publication_date']);
		echo '</div>';
	}
	echo  '</div>';
	$nb_posts = requete_sql("
		SELECT count(*) as total
		FROM post
		WHERE author_id='".addslashes($_GET['id'])."'");

	$nb_posts = mysql_fetch_assoc($nb_posts);
	$nb_posts = $nb_posts['total'];
	$nb_pages = ceil($nb_posts/$nb_resultats_par_page);

	if($page != 0) {
		echo '<a href="author.php?id='.$_GET['id'].'&page='.$page.'">Page précédente</a> ::: ';
	}

	for($i = 1; $i <= $nb_pages;$i++) {
		if($i-1 == $page) {
			echo 'Page '.$i;
		}
		else {
			echo '<a href="author.php?id='.$_GET['id'].'&page='.$i.'">Page '.$i.'</a>';
		}
		if($i != $nb_pages) {
			echo ' \ ';
		}
	}
	if($page != $nb_pages-1) {
		echo ' ::: <a href="author.php?id='.$_GET['id'].'&page='.($page+2).'">Page suivante</a>';
	}

}


include('footer.php');