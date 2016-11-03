<?php 
include('header.php');

if(isset($_POST['commentor'])) {
	$insert = requete_sql("
		INSERT INTO `comment` VALUES (
		NULL,
		'".addslashes($_POST['id'])."',
		'".addslashes($_POST['commentor'])."',
		now(),
		'".addslashes($_POST['content'])."'
		)");
}


if(isset($_REQUEST['id'])) {
	$article = requete_sql("
		SELECT post.*, author.public_name
		FROM post
		LEFT JOIN author
		ON post.author_id = author.id
		WHERE post.id='".addslashes($_REQUEST['id'])."'
		");

	$a = mysql_fetch_assoc($article);

	echo '<article>
	<h1>'.$a['title'].'</h1>';
	echo 'Publié le '.format_date($a['publication_date']);
	echo ' par <a href="author.php?id='.$a['author_id'].'">'.$a['public_name'].'</a>';
	echo '<p>'.$a['content'].'</p>';
	echo '</article>';
	$liste_categories = requete_sql("
		SELECT category.id, category.name
		FROM post_category
		LEFT JOIN category
		ON post_category.category_id = category.id
		WHERE post_category.post_id = '".addslashes($_REQUEST['id'])."'
		");

	echo '<div><h3>Catégories associées</h3><ul>';
	while($c = mysql_fetch_assoc($liste_categories)) {
		echo '<li><a href="category.php?id='.$c['id'].'">'.$c['name'].'</a></li>';
	}
	echo  '</ul></div>';
// On permet aux visiteurs de publier des commentaires
?>
<h3>Commentez !</h3>
<form action="post.php" method="post">
	<input type="text" name="commentor" placeholder="Pseudo" required>
	<textarea name="content" required></textarea>
	<input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>">
	<input type="submit" value="Publier">
</form>

<?php
// On liste les commentaires
	$list_comments = requete_sql("
		SELECT * FROM `comment` WHERE post_id='".$_REQUEST['id']."'
		");
	while($c = mysql_fetch_assoc($list_comments)) {
		echo $c['commentor'].' a dit le '.format_date($c['comment_date']);
		echo '<div>'.$c['content'].'</div>';
	}
}
include('footer.php');