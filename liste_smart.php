<?php 
include("include/header.php");
/*Liste des messages*/
?>


<div id="liste_smart">
	<h1 align="center">Liste des voisins :</h1>

	<ul>
	<?php 
	$users = $manager->getList();
	if(isset($_SESSION["pseudo"]))
	{
		foreach($users as $user)
		{
			echo "<li><a href='profil?id=".$user->id()."'>" .$user->pseudo(). "</a></li>";
		}
	}
	else
	{
		foreach($users as $user)
		{
		echo "<li><a href='register.php'>" .$user->pseudo(). "</a></li>";
		}
	}
	?>
	</ul>
</div>
<?php include("include/footer.php");?>