<?php 
include("include/header.php");



$user = $manager->get((int)$_GET["id"]);
if($user->pseudo() == $_SESSION["pseudo"])
{
	$manager->delete($user);
	session_destroy();
	header("Location: index.php");
	exit;
}
else
{
	header("Location: index.php");
	exit;
}

?>