<?php
include('header.php');

if(isset($_POST['name'])) {
	switch($_POST['action']) {
		case 'add':
			$ajout = requete_sql("
				INSERT INTO category VALUES (
				NULL,
				'".addslashes($_POST['name'])."',
				'".addslashes($_POST['description'])."'
				)");
			break;
		case 'update':
			$maj = requete_sql("
				UPDATE category SET
				name = '".addslashes($_POST['name'])."',
				description = '".addslashes($_POST['description'])."'
				WHERE id='".$_POST['id']."'
				");
			break;
	}
}

?>
<section>
<?php
if(isset($_GET['action']) && $_GET['action']=='edit') {
	$h1 = 'Modifier une catégorie';
	$category = requete_sql("
		SELECT * FROM category WHERE id='".$_GET['category']."'
			");
	$i = mysql_fetch_assoc($category);
	$name = $i['name'];
	$description = $i['description'];
	$id = $i['id'];
	$action = 'update';
}
elseif(isset($_GET['action']) && $_GET['action']=='delete') {
	$suppr = requete_sql(
		"DELETE FROM category WHERE id='".addslashes($_GET['category'])."' LIMIT 1;
		");
	$h1 = 'Ajouter une catégorie';
	$name = $description = $id = '';
	$action = 'add';
}
else {
	$h1 = 'Ajouter une catégorie';
	$name = $description = $id = '';
	$action = 'add';
}
echo '<h1>'.$h1.'</h1>';
?>
<form action="category.php" method="post">
<label for="name"> Nom : </label>
<input type="text" name="name" maxlength="50" value="<?php echo $name; ?>">
<label for="description">Description :</label>
<textarea name="description" cols="60" rows="15"><?php echo $description; ?></textarea>
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="action" value="<?php echo $action; ?>">
<input type="submit" value="Publier">
</form>
</section>


<section>
<h1>Liste des catégories</h1>
<?php
$liste=requete_sql("SELECT id,name FROM category");
echo '<ul class="liste">';
while($i = mysql_fetch_assoc($liste)) {
	echo '<li><a href="category.php?action=edit&category='.$i['id'].'">'
	.$i['name'].'</a> - '
	.'<a href="category.php?action=delete&category='
	.$i['id'].'">✖</a></li>';
}
echo '</ul>';
?>
</section>