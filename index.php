<?php
include('header.php');
?>
<h1>Bienvenue sur mon we(blog)</h1>

<section>
<h2>Liste des articles</h2>
<?php
if(isset($_GET['page'])) {
	$page = $_GET['page']-1;
}
else {
	$page = 0;
}
$nb_resultats_par_page = 5;

// $page = isset($_GET['page']) ? $_GET['page']: 0 ;

$liste_articles = requete_sql("
	SELECT post.*, author.public_name FROM post
	LEFT JOIN author ON post.author_id = author.id
	ORDER BY publication_date DESC
	LIMIT ".($page*$nb_resultats_par_page).",".$nb_resultats_par_page."
	");	
while($i=mysql_fetch_assoc($liste_articles)) {
	echo '<div>
	<h3><a href="post.php?id='.$i['id'].'">'.$i['title'].'</a></h3>';
	echo '<p>'.substr($i['content'], 0, 30).'...</p>';
	echo 'Publié le '.format_date($i['publication_date']);
	echo ' par '.$i['public_name'];
	echo '</div>';
}
$nb_posts = requete_sql("SELECT count(*) as total FROM post");
$nb_posts = mysql_fetch_assoc($nb_posts);
$nb_posts = $nb_posts['total'];

// $nb_posts = mysql_fetch_assoc($nb_posts)['total'];

$nb_pages = ceil($nb_posts/$nb_resultats_par_page);

if($page != 0) {
	echo '<a href="index.php?page='.$page.'">Page précédente</a> ::: ';
}

for($i = 1; $i <= $nb_pages;$i++) {
	if($i-1 == $page) {
		echo 'Page '.$i;
	}
	else {
		echo '<a href="index.php?page='.$i.'">Page '.$i.'</a>';
	}
	if($i != $nb_pages) {
		echo ' \ ';
	}
}
if($page != $nb_pages-1) {
	echo ' ::: <a href="index.php?page='.($page+2).'">Page suivante</a>';
}

?>
</section>

<?php
include('footer.php');
