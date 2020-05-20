<?php
include("include/header.php");
$commentairemanager = new CommentaireManager($db);
$user = $manager->get($_SESSION["pseudo"]);

$com = $commentairemanager->get($_GET["delete"]);
if($user->pseudo() == $com->auteur())
{
	$idAnnonce = $com->idMessage();
	$commentairemanager->delete($com);
	header("Location: commentaires.php?annonce=".$idAnnonce);
	exit;	
}
else
{
	header("Location: index.php");
	exit;
}

?>