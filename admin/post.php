<?php
include('header.php');

if(isset($_POST['title'])) {
	switch($_POST['action']) {
		case 'add':
			$ajout = requete_sql("
				INSERT INTO post VALUES (
				NULL,
				'".addslashes($_POST['title'])."',
				now(),
				'".addslashes($_POST['content'])."',
				'".$_SESSION['id']."'
				)");
			 // $ajout == le dernier ID créé !

			foreach($_POST['category'] as $id_cat) {
				$liaisons = requete_sql("
					INSERT INTO post_category
					VALUES('".$ajout."','".$id_cat."');
					");
			}
			break;
		case 'update':
			$maj = requete_sql("
				UPDATE post SET
				title = '".addslashes($_POST['title'])."',
				content = '".addslashes($_POST['content'])."'
				WHERE id='".$_POST['id']."'
				");
			$suppr=requete_sql("
				DELETE FROM post_category
				WHERE post_id='".$_POST['id']."'");
			foreach($_POST['category'] as $id_cat) {
				$liaisons = requete_sql("
					INSERT INTO post_category
					VALUES('".$_POST['id']."','".$id_cat."');
					");
			}
			break;
	}
}

?>
<section>
<?php
if(isset($_GET['action']) && $_GET['action']=='edit') {
	$h1 = 'Modifier un article';
	$article = requete_sql("
		SELECT * FROM post WHERE id='".$_GET['article']."'
			");
	$i = mysql_fetch_assoc($article);
	$titre = $i['title'];
	$content = $i['content'];
	$id = $i['id'];
	$action = 'update';
}
elseif(isset($_GET['action']) && $_GET['action']=='delete') {
	$suppr = requete_sql(
		"DELETE FROM post WHERE id='".addslashes($_GET['article'])."' LIMIT 1;
		");
	$h1 = 'Ajouter un article';
	$titre = $content = $id = '';
	$action = 'add';
}
else {
	$h1 = 'Ajouter un article';
	$titre = $content = $id = '';
	$action = 'add';
}
echo '<h1>'.$h1.'</h1>';
?>
<form action="post.php" method="post">
<label for="title"> Titre : </label>
<input type="text" name="title" value="<?php echo $titre; ?>">
<label for="content">Article :</label>
<textarea name="content" cols="60" rows="15"><?php echo $content; ?></textarea>
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="action" value="<?php echo $action; ?>">
<?php
if($action == "update") {
	$categories_associees = requete_sql("
		SELECT category_id
		FROM post_category
		WHERE post_id='".$id."'
		");
	$array_cat_assoc = array();
	while($j = mysql_fetch_assoc($categories_associees)) {
		array_push($array_cat_assoc, $j['category_id']);
	}
	// J'ai créé un tableau des catégories associées à mon article
}
$liste_categories = requete_sql("SELECT id, name FROM category");
while($i = mysql_fetch_assoc($liste_categories)) {
?>
<input type="checkbox" name="category[]" value="<?php echo $i['id']; ?>"
<?php
if($action=="update" && in_array($i['id'], $array_cat_assoc)) {
	echo ' checked';
}
?>
>
<?php
echo $i['name'];
}
?>
<input type="submit" value="Publier">
</form>
</section>


<section>
<h1>Liste des articles</h1>
<?php
$liste=requete_sql("SELECT id,title,publication_date FROM post");
echo '<ul class="liste">';
while($i = mysql_fetch_assoc($liste)) {
	echo '<li><a href="post.php?action=edit&article='.$i['id'].'">'
	.$i['title'].'</a> - '
	.$i['publication_date']
	.' - <a href="post.php?action=delete&article='
	.$i['id'].'">✖</a></li>';
}
echo '</ul>';
?>
</section>