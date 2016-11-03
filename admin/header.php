<?php
require_once('../includes/config.php');
require_once('../includes/fonctions.php');

session_start();

if(!isset($_SESSION['id']) && !strpos($_SERVER['PHP_SELF'], 'login.php')) {
	header('Location:login.php');
}

if(isset($_SESSION['id']) && strpos($_SERVER['PHP_SELF'], 'login.php')) {
	header('Location:index.php');
}

?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
	<title>Admin Blog</title>
	<link rel="stylesheet" type="text/css" href="admin.css">
</head>
<body>
<?php
//if(isset($_SESSION['id'])) {
?>
<nav>
<ul>
<li><a href="index.php">Accueil</a></li>
<li><a href="../index.php">Voir le site</a></li>
<li><a href="post.php">Articles</a></li>
<li><a href="category.php">Catégories</a></li>
<li><a href="author.php">Auteurs</a></li>
<li><a href="logout.php">Déconnexion</a></li>
</ul>
</nav>
<?php
//}
?>

