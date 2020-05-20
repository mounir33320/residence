<?php 
include("include/header.php");
/*Liste des messages*/
$annoncemanager = new AnnonceManager($db);

/*Supression de message*/
if(isset($_GET["delete"]))
{
	$_GET["delete"] = (int) $_GET["delete"];

	if($annoncemanager->exist($_GET["delete"]))
	{
		$annonce = $annoncemanager->get($_GET["delete"]);
		$annoncemanager->delete($annonce);
		echo "<p align='center'>Votre annonce a bien été supprimée !</p>";
		header("Refresh: 2;URL=annonce.php");
		exit;
	}
	else
	{
		header("Location: annonce.php");
		exit;
	}
}

if(isset($_POST["enregistrer"]))
{
	if(isset($_SESSION["pseudo"]))
	{
		if(!empty($_POST["annonce_message"]))
		{
			$annonce = new Annonce([
			"pseudo" => $_SESSION["pseudo"],
			"message" => $_POST["annonce_message"]]);

			$annoncemanager->add($annonce);
			
			
			/*PHP MAILER---------------------------------------------------------------------------------------------------*/
			$listeAdresses = [];	
			$users = $manager->adressList($_SESSION["pseudo"]);
			foreach($users as $user)
			{
				$listeAdresses[] = $user->email();
			}
			

/**
 * This example shows making an SMTP connection with authentication.
 */
//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Etc/UTC');
require 'PHPMailer-master/PHPMailerAutoload.php';
//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;
//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';
$mail->SMTPOptions = array(
"ssl" => array(
  "verify_peer" => false,
  "verify_peer_name" => false,
  "allow_self_signed" => true)  );
//Set the hostname of the mail server
$mail->Host = "smtp.residence-sources.fr";
$mail->Mailer = "smtp";
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 465;
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication
$mail->Username = "admin@residence-sources.fr";
//Password to use for SMTP authentication
$mail->SMTPSecure = "ssl";
$mail->Password = "040290zizou";
//Set who the message is to be sent from
$mail->From = "admin@residence-sources.fr";
//Nom de l'envoyeur
$mail->FromName = "Résidence Sources";
//Set an alternative reply-to address
$mail->addReplyTo('replyto@example.com', 'First Last');
//Set who the message is to be sent to
foreach($listeAdresses as $value)
{
	$mail->addAddress($value);

}
$mail->isHTML(true);
//Set the subject line
$mail->Subject = 'Annonce postée';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$url = "<p><a href='https://www.residence-sources.fr/annonce.php'>www.residence-sources.fr</a></p>";
$mail->msgHTML("<p>Bonjour !</p><p>Une annonce vient d'être postée :</p><p>" .$_SESSION["pseudo"]. " : <em>\"" .$annonce->message(). "\"</em>" .$url);
//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
$mail->addAttachment('images/logo_mail.png');
//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    $erreur = "Votre message a bien été inséré";
}
			

			/*----------------------------------------------------------------------------------------------------------------*/
		}
		else
		{
			$erreur = "Veuillez ne pas laisser le champ vide !";	
		}
	}
	else
	{
		header("Location: register.php");
		exit;
	}
}
?>
		<div id="blocpage">
			<section id="annonce_section">
				<article id="annonce_article">
					<p align="center" style="color:red"><?php if(isset($erreur)){echo $erreur;}?>
					<p id="liste_voisin"><a href="liste_smart">Liste des voisins</a></p>
					<p><em>Dans cette section vous pouvez demander de l'aide ou autre chose. Ex : Quelqu'un s'y connait en mécanique car ma voiture est en panne ? / Quelqu'un est-il intéressé par du babysitting / Quelqu'un est-il motivé pour aller faire du vélo etc...</em></p>
					<form action="" method="post">
						<textarea rows="" cols="" name="annonce_message"></textarea><br/>
						<input type="submit" value="Enregistrer" name="enregistrer"/>
					</form>
					<?php 
					$annonces = $annoncemanager->getList();
					foreach($annonces as $uneAnnonce)
					{
						
					?>
					<div id="annonce_billet">
						<h1><?php echo $uneAnnonce->pseudo(). " <em>" .$uneAnnonce->dateCreation(). "</em>	";?></h1>
						<p><?php echo nl2br($uneAnnonce->message());?></p>
						<em><a id="lien_commentaire" href="commentaires?annonce=<?php echo $uneAnnonce->id();?>">Commentaire(s)</a></em>
						<?php 
						if(isset($_SESSION["pseudo"]) && isset($uneAnnonce))
						{
							if($_SESSION["pseudo"] == $uneAnnonce->pseudo())
							{
							?>
							<a href="?delete=<?php echo $uneAnnonce->id();?>">Supprimer</a>	
						<?php	
							}
						}
						?>
						</em>
					</div>	
					<?php	
					}

					?>
				</article>
				
				<aside id="annonce_aside">
					<h1 align="center">Liste des voisins :</h1>
					<ul>
					<?php 
					$users = $manager->getList();

					if(isset($_SESSION["pseudo"]))
					{
						foreach($users as $user)
						{
							echo "<li style='margin-bottom: 20px;'><a href='profil.php?id=".$user->id()."'>" .$user->pseudo(). "</a></li>";
						}
					}
					else
					{
						foreach($users as $user)
						{
						echo "<li style='margin-bottom: 20px;'><a href='register.php'>" .$user->pseudo(). "</a></li>";
						}
					}
					
					?>
					</ul>
				</aside>
			</section>
		</div>
<?php 
include("include/footer.php");
?>