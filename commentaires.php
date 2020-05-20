<?php 
include("include/header.php");

$annoncemanager = new AnnonceManager($db);

$_GET["annonce"] = (int) $_GET["annonce"];

if($annoncemanager->exist($_GET["annonce"]))
{
	$annonce = $annoncemanager->get($_GET["annonce"]);
}
else
{
	header("Location: index.php");
	exit;
}

$commentairemanager = new CommentaireManager($db);

if(isset($_POST["inserer"]) && isset($_POST["commentaire"]))
{
	if(isset($_SESSION["pseudo"]))
	{
		$unCommentaire = new Commentaire([
		"idMessage" => $_GET["annonce"],
		"auteur" => $_SESSION["pseudo"],
		"contenu" => $_POST["commentaire"]]);
		
		if($unCommentaire->nomValide())
		{
			$commentairemanager->add($unCommentaire);
			$annonceur = $manager->get($annonce->pseudo());

			if($annonceur->pseudo() == $unCommentaire->auteur())
			{
				$erreur = "Votre commentaire a bien été inséré";
			}
			else
			{
				/*PHP MAILER----------------------------------------------------------------------------------------------*/
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
$mail->addAddress($annonceur->email());
$mail->isHTML(true);
//Set the subject line
$mail->Subject = 'Commentaire posté !';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$url = "<p><a href='https://www.residence-sources.fr/commentaires?annonce=".$_GET['annonce']."'>www.residence-sources.fr</a></p>";
$mail->msgHTML("<p>Bonjour !</p><p>Quelqu'un a posté un commentaire dans votre annonce !<p>".$unCommentaire->auteur()." : <em>\"".$unCommentaire->contenu()."\"</em></p>".$url);
//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
$mail->addAttachment('images/logo_mail.png');
//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    $erreur = "Votre commentaire a bien été inséré";
}
				

			/*-----------------------------------------------------------------------------------------------------------*/
			}
			
		}
		else
		{
			$erreur = "Veuillez ne pas laisser le champ libre !";
		}
	}
	else
	{
		$erreur = "Veuillez vous connecter pour écrire un message !";
	}
}
?>
<section id="section_commentaires">
	<article>
		<a href="annonce.php">Retour aux annonces</a>
		
		<div id="annonce_commentaire">
						<h1><?php echo $annonce->pseudo(). " <em>" .$annonce->dateCreation(). "</em>";?></h1>
						<p><?php echo nl2br($annonce->message());?></p>
		</div>

		<div id="commentaires">
			<h1>Commentaire(s)</h1>
			<?php
			$listeCommentaires = $commentairemanager->getList($_GET["annonce"]);
			foreach($listeCommentaires as $commentaire)
			{
			?>
			<h2><?php echo $commentaire->auteur(). " <em>" .$commentaire->dateCommentaire(). "</em>";?></h2>
			<p><?php echo $commentaire->contenu();?><br/>
			<?php
			if(isset($_SESSION["pseudo"]))
			{
				if($commentaire->auteur() == $_SESSION["pseudo"])
				{
					echo "<a href='delete_com?delete=".$commentaire->id()."'>Supprimer</a>";
				}
			}
			?>	
			</p>
			<?php
			}
			?>
			<form action="" method="post">
				<textarea name="commentaire" placeholder="Insérer votre commentaire" rows="4" cols="40"></textarea><br/>
				<input type="submit" value="Inserer" name="inserer"/>
				<p><?php if(isset($erreur)){echo $erreur;}?></p>
			</form>
		</div>
	</article>
</section>

<?php include("include/footer.php");?>