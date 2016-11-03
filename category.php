<?php
include("header.php");

// Liste de toutes les catégories 

	// On va chercher toutes les catégories, sans filtre
$liste_cat = requete_sql("SELECT id,name FROM category");
	// On affiches toutes les catégories dans une liste grâce à une boucle
echo '<div><h3>Catégories</h3><ul>';
while($c = mysql_fetch_assoc($liste_cat)) {
	echo '<li><a href="category.php?id='.$c['id'].'">'.$c['name'].'</a></li>';
}
echo '</ul></div>';


// Informations sur la catégorie pour laquelle on a un $_GET['id']
// Seulement si on a un $_GET['id']

if(isset($_GET['id'])) {
		// On obtient les données de la catégorie avec une requête
	$data_cat=requete_sql("SELECT * FROM category WHERE id='".addslashes($_GET['id'])."'");
		// On a qu'un seul résultat donc on n'utilise pas de boucle
	$d = mysql_fetch_assoc($data_cat);
		// Toutes les données sont dans un tableau stocké dans $d, on les affiche
	echo '<h1>'.$d['name'].'</h1>';
	echo '<p>'.$d['description'].'</p>';

	if(isset($_GET['page'])) {
		$page = $_GET['page']-1;
	}
	else {
		$page = 0;
	}
	$nb_resultats_par_page = 5;

	// On cherche à afficher tous les articles de cette catégorie

	$list_posts = requete_sql("
		SELECT
		post.id AS post_id,
		post.title,
		post.publication_date,
		post.content,
		author.id AS author_id,
		author.public_name
		FROM post_category
		LEFT JOIN post ON post_category.post_id = post.id
		LEFT JOIN author ON post.author_id = author.id
		WHERE category_id='".addslashes($_GET['id'])."'
		ORDER BY post.publication_date DESC
		LIMIT ".($page*$nb_resultats_par_page).",".$nb_resultats_par_page."
		");
	while($a=mysql_fetch_assoc($list_posts)) {
		echo '<div>
		<h3><a href="post.php?id='.$a['post_id'].'">'.$a['title'].'</a></h3>';
		echo '<p>'.substr($a['content'], 0, 30).'...</p>';
		echo 'Publié le '.format_date($a['publication_date']);
		echo ' par <a href="author.php?id='.$a['author_id'].'">'.$a['public_name'].'</a>';
		echo '</div>';
	}
	$nb_posts = requete_sql("
		SELECT
		count(*) as total
		FROM post_category
		WHERE category_id='".addslashes($_GET['id'])."'
		");
	$nb_posts = mysql_fetch_assoc($nb_posts);
	$nb_posts = $nb_posts['total'];

	$nb_pages = ceil($nb_posts/$nb_resultats_par_page);

if($page != 0) {
	echo '<a href="category.php?id='.$_GET['id'].'&page='.$page.'">Page précédente</a> :::';
}
for($i=1;$i <= $nb_pages; $i++) {
	if($i != $page+1) {
		echo '<a href="category.php?id='.$_GET['id'].'&page='.$i.'">Page '.$i.'</a>';
	}
	else {
		echo 'Page '.$i;
	}
	if($i != $nb_pages) {echo ' / ';}
}
if($page < $nb_pages-1) {
	echo ' ::: <a href="category.php?id='.$_GET['id'].'&page='.($page+2).'">Page suivante</a>';
}
}
include("footer.php");