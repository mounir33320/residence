<?php 
include("include/header.php");
if(isset($_SESSION["pseudo"]))
{
	$user = $manager->get($_SESSION["pseudo"]);
	if(!$manager->existId($_GET["id"]))
	{
		header("Location: index.php");
		exit;
	}
}
else
{
	header("Location: register.php");
	exit;
}

if(isset($_POST["envoyer"]))
{
	if(!empty($_POST["message"]))
	{
		$envoyeur = $manager->get($_SESSION["pseudo"]);
		$receveur = $manager->get((int)$_GET["id"]);
		/******************************************************PHPMAILER*******************************************************************/
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
$mail->addReplyTo($envoyeur->email());
//Set who the message is to be sent to
$mail->addAddress($receveur->email());
$mail->isHTML(true);
//Set the subject line
$mail->Subject = 'Message de ' .$envoyeur->pseudo();
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML("<p>Bonjour,</p><p>" .$envoyeur->pseudo(). " vous a envoyé un message : </p><em>".$_POST["message"]. "</em><p><a href='https://www.residence-sources.fr/profil?id=".$envoyeur->id()."'>Répondez lui !</a></p>");
//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
$mail->addAttachment('images/logo_mail.png');
//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    $message = "Votre message a bien été envoyé";;
}


		/***************************************************************************************************************************/
	}
	else
	{
		$message = "Veuillez ne pas laisser le champ libre !";
	}
}
?>
<section id="profil_section">
	<article>
	<p align="center" style="color:red;"><?php if(isset($message)){echo $message;}?></p>
		<?php 
		if($user->id() == $_GET["id"])
		{
		?>
		<h1>Mon profil</h1>
		<div id="profil_patronyme">
			<p><strong>Pseudo :</strong> <?php echo $user->pseudo(); ?></p>
			<p><strong>Nom :</strong> <?php echo $user->nom(); ?></p>
			<p><strong>Prénom :</strong> <?php echo $user->prenom(); ?></p>
			
		</div>
		<div>
			<p><strong>Né(e) le </strong><?php echo $user->naissance(); ?></p>
			<p><strong>Email :</strong> <?php echo $user->email();?></p>
			<p><strong>Hobbies :</strong> <?php echo $user->hobby();?></p>
			<p><strong>Proposition d'aide :</strong> <?php echo $user->proposer();?></p>
		</div>
		<a href="delete_profil.php?id=<?php echo $user->id();?>" style="color:red;">Supprimer mon compte</a>
		<?php	
		}
		else
		{
			$user = $manager->get((int)$_GET["id"]);
		?>
		<h1>Profil de <?php echo $user->pseudo();?></h1>
		<div id="profil_patronyme">
			<p><strong>Pseudo</strong> : <?php echo $user->pseudo(); ?></p>
			<p><strong>Nom</strong> : <?php echo $user->nom(); ?></p>
			<p><strong>Prénom</strong> : <?php echo $user->prenom(); ?></p>
			
		</div>
		<div>
			<p><strong>Né(e) le</strong> <?php echo $user->naissance(); ?></p>
			<p><strong>Hobbies</strong> : <?php echo $user->hobby();?></p>
			<p><strong>Proposition d'aide :</strong> <?php echo $user->proposer();?></p>
		</div>
		<form action ="" method="POST">
			<label for="message"><p>Lui écrire un message : </p></label>	
			<textarea name="message" id="message" cols="" rows=""></textarea><br/>
			<input type ="submit" name="envoyer" value="Envoyer"/>
		</form>
		<?php	
		}
		
		?>

	</article>
</section>

<?php 
include("include/footer.php");

?>