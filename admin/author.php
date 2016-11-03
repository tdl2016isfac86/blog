<?php
include('header.php');

if(isset($_POST['username'])) {
	switch($_POST['action']) {
		case 'add':
			if(!empty($_POST['password1']) &&
				$_POST['password1'] == $_POST['password2']) {
				// S'exécute si passsword1 n'est vide et si password1 et password2 sont identiques
				$ajout = requete_sql("
					INSERT INTO author VALUES (
					NULL,
					'".addslashes($_POST['username'])."',
					'".addslashes($_POST['public_name'])."',
					'".addslashes($_POST['email'])."',
					'".md5($_POST['password1'])."'
					)");
			}
			else {
				echo 'Vous n\'avez pas entré de mot de passe ou alors ils sont différents !';
			}
			break;
		case 'update':
			if($_POST['password1'] == $_POST['password2']) {
				if($_POST['password1'] != '') {
					$password_update = ", password = '".md5($_POST['password1'])."'";
				}
				else {
					$password_update = '';
				}
				$maj = requete_sql("
					UPDATE author SET
					username = '".addslashes($_POST['username'])."',
					public_name = '".addslashes($_POST['public_name'])."',
					email = '".addslashes($_POST['email'])."'"
					.$password_update
					." WHERE id='".$_POST['id']."'
					");
			}
			else {
				echo 'Les mots de passes ne sont pas identiques.';
			}
			break;
	}
}

?>
<section>
<?php
if(isset($_GET['action']) && $_GET['action']=='edit') {
	$h1 = 'Modifier un auteur';
	$author = requete_sql("
		SELECT * FROM author WHERE id='".$_GET['author']."'
			");
	$i = mysql_fetch_assoc($author);
	$username = $i['username'];
	$public_name = $i['public_name'];
	$email = $i['email'];
	$id = $i['id'];
	$action = 'update';
}
elseif(isset($_GET['action']) && $_GET['action']=='delete') {
	$suppr = requete_sql(
		"DELETE FROM author WHERE id='".addslashes($_GET['author'])."' LIMIT 1;
		");
	$h1 = 'Ajouter un auteur';
	$username = $public_name = $email = $id = '';
	$action = 'add';
}
else {
	$h1 = 'Ajouter un auteur';
	$username = $public_name = $email = $id = '';
	$action = 'add';
}
echo '<h1>'.$h1.'</h1>';
?>
<form action="author.php" method="post">
<label for="username"> Nom d'utilisateur : </label>
<input type="text" name="username" value="<?php echo $username; ?>">
<label for="public_name">Nom public :</label>
<input type="text" name="public_name" value="<?php echo $public_name; ?>">
<label for="email">Courriel :</label>
<input type="email" name="email" value="<?php echo $email; ?>">
<label for="password1">Mot de passe :</label>
<input type="password" name="password1">
<label for="password2">Retapez-le s'il vous plait :</label>
<input type="password" name="password2">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="action" value="<?php echo $action; ?>">
<input type="submit" value="Publier">
</form>
</section>


<section>
<h1>Liste des auteurs</h1>
<?php
$liste=requete_sql("SELECT id,public_name FROM author");
echo '<ul class="liste">';
while($i = mysql_fetch_assoc($liste)) {
	echo '<li><a href="author.php?action=edit&author='.$i['id'].'">'
	.$i['public_name'].'</a> - '
	.'<a href="author.php?action=delete&author='
	.$i['id'].'">✖</a></li>';
}
echo '</ul>';
?>
</section>