<?php
require "class/usermanager.class.php";
require "class/user.class.php";
require "class/annonce.class.php";
require "class/annoncemanager.class.php";
require "class/commentaire.class.php";
require "class/commentairemanager.class.php";


session_start();

if(isset($_GET["deco"]))
{
	session_destroy();
	header("Location: index.php");
	exit;
}

try
{
	$db = new PDO("mysql:host=localhost;dbname=residence;charset=utf8", "root", "root", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch(EXCEPTION $e)
{
	die("Erreur :" .$e->getMessage());
}

$manager = new UserManager($db);

if(isset($_POST["connect"]))
{
	if(!empty($_POST["id"]) && !empty($_POST["mdp"]))
	{
		if($manager->connect($_POST["id"], sha1($_POST["mdp"])))
		{
			$user = $manager->get($_POST["id"]);
			
			$_SESSION["pseudo"] = $user->pseudo();
			$_SESSION["mdp"] = $user->mdp();

			header("Location: profil?id=".$user->id());
			exit;
		}
		else
		{
			$erreur = "Votre identifiant ou votre mot de passe est incorrect !";
		}
	}
	else
	{
		$erreur = "Tous les champs doivent être complétés !";
	}
}

?>


<!DOCTYPE html>
<html>
	<head>
		<title>Domaine des Sources à Eysines - Une bonne relation entre voisins !</title>
		<link rel="stylesheet" href="style.css"/>
		<link rel="icon" href="images/logo_entete.png" type="image/x-icon"/>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width"/>
		<script type="text/javascript" src="https://www.httpcs.com/certified/js/58384/www.residence-sources.fr"></script>
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-90561862-2', 'auto');
		  ga('send', 'pageview');
		</script>
	</head>

	<body>
		<header>
			<div id="header">
				<div id="logo">
					<p id="logo_accueil"><a href="index"><img src="images/logo2.png"/></a></p>
					<h1 id="img_smart"><a href="index"><img src="images/logo_smart.png" alt="logo"/></a></h1>
					<h2 class="smart_h1">Pour de bonnes relations entre voisins !</h2>
				</div>
				
				<div id="reglog">
					<div id="log">
					<?php if(isset($_SESSION["pseudo"]))
					{
					$user = $manager->get($_SESSION["pseudo"]);
					?>
					<h2 id="bonjour" style="color: rgb(0,68,131);margin-right: 70px;font-style: normal;margin-top: 1px;font-size: 1.2em;" >Bonjour <?php echo $_SESSION["pseudo"];?></h2>
					<div id="compte_deco">
						<p><a id="mon_compte" href="profil?id=<?php echo $user->id();?>" style="text-decoration:none;color: white;background-color:rgb(0,68,131);padding: 8px 5px 8px 5px;border-radius: 5px 5px  5px 5px;" >Mon profil</a></p>
						<p><a href="?deco" style="margin-left: 15px; color: black;">Se déconnecter</a></p>
					</div>	
					<?php	
					}
					else
					{
					?>
					<form action="" method="POST">
							<table>
								<tr>
									<td><label for="id">Identifiant :</label></td>
									<td><input class="input_log" height="" type="text" name="id" id="id" value="<?php if(isset($_SESSION['pseudo'])){echo $_SESSION['pseudo'];}?>"/></td>
								</tr>

								<tr>
									<td><label for="mdp">Mot de passe : </label></td>
									<td><input class="input_log" height="" type="password" name="mdp" id="mdp"/></td>
								</tr>
								<tr>
									<td></td>
									<td><input id="log_submit" type="submit" name="connect" value="Connexion"/></td>
								</tr>
								<tr>
									<td></td>
									<td><p id="inscription_smart"><a href="register">S'inscrire</a></p></td>
								</tr>
							</table>
							<?php if(isset($erreur)){echo "<p style='font-size:0.8em;' >" .$erreur. "</p>";}?>
						</form>

						</div>
					<div id="reg">
						<p>Pas encore inscrit ?</p>
						<p style="margin-left: 20px; "><a href="register.php">Inscrivez-vous !</a></p>
					</div>

					<?php	
					}
					?>
						
				
				</div>
			</div>
		</header>

		<div id="nav">
			<nav>
				<ul>
					<li><a href="index.php">Accueil</a></li>
					<li><a href="annonce.php">Annonces</a></li>
					<li><a href="contact.php">Contactez-moi</a></li>
					<li><a href="qui-suis-je.php">Qui suis-je ?</a></li>
				</ul>
			</nav>
		</div>


