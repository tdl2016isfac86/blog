<?php
include('header.php');

if(isset($_POST['login'])) {
	$res=requete_sql("SELECT id,public_name FROM author WHERE username='".addslashes($_POST['login'])."' AND password='".md5($_POST['password'])."' LIMIT 1");
	if(mysql_num_rows($res) == 1) {
		$i = mysql_fetch_assoc($res);
		$_SESSION['id'] = $i['id'];
		$_SESSION['name'] = $i['public_name'];
		header('Location:index.php');
	}
	else {
		echo'<div class="error">La connexion a échouée</div>';
	}
}

if(!isset($_SESSION['id'])) {
?>
<form action="login.php" method="post">
<input type="text" name="login" value="<?php 
echo isset($_POST['login']) ? $_POST['login']:'';
 ?>">
<input type="password" name="password">
<input type="submit" value="Connexion">
</form>
<?php
}
include('footer.php');
