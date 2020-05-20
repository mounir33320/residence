<?php 
include("include/header.php");

if(isset($_POST["envoyer"]))
{
	if(isset($_SESSION["pseudo"]))
	{
		if(!empty($_POST["message"]) && !empty($_POST["objet"]))
		{
			$user = $manager->get($_SESSION["pseudo"]);

		/*PHPMailer------------------------------------------------------------------------------------------------*/
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
			//Nom de l'envoyeur
			$mail->FromName = $user->nom(). " " .$user->prenom();
			//Set an alternative reply-to address
			$mail->addReplyTo($user->email());
			//Set who the message is to be sent to
			$mail->addAddress("mounir.senaoui@hotmail.fr");
			$mail->addAddress("nirmou0402@hotmail.fr");
			$mail->addAddress("nirmou0402@gmail.fr");
			$mail->addAddress("residence.sources@gmail.com");
			$mail->addAddress("admin@residence-sources.fr");
			$mail->isHTML(true);
			//Set the subject line
			$mail->Subject = $_POST["objet"];
			//Read an HTML message body from an external file, convert referenced images to embedded,
			//convert HTML into a basic plain-text alternative body
			$mail->msgHTML($_POST["message"]);
			//Replace the plain text body with one created manually
			$mail->AltBody = 'This is a plain-text message body';
			//Attach an image file
			$mail->addAttachment('images/logo_mail.png');
			//send the message, check for errors
			if (!$mail->send()) {
			    echo "Mailer Error: " . $mail->ErrorInfo;
			} else {
			    $message = "Votre message a bien été envoyé";
			}
/*-------------------------------------------------------------------------------------------------------------*/
		}
		else
		{
			$message = "Veuillez ne pas laisser de champ libre !";
		}
	}
	else
	{
		$message = "Vous devez être connecté ou bien créer un compte !";
	}
}

?>
<div id="contact_message">
	<p id="contact_erreur"><?php if(isset($message)){echo $message;}?></p>
	<h1>Contactez-moi !</h1>
	<p>Voici un formulaire de contact. Vous pouvez l'utiliser si jamais vous rencontrez un problème sur le site ou pour quoi que ce soit d'autre. 
	Egalement, vous pouvez m'envoyer votre avis sur ce que vous pensez du site.</p>
	<p>En tout cas, je ferai de mon mieux pour répondre le plus rapidement possible !</p>
</div>

<div id="contact_form" align="center">
	<form action="" method="POST">
		<table>
			<tr>
				<td><input id="input_objet" type="text" name="objet" placeholder="Votre objet"></td>
			</tr>
			<tr>
				<td><textarea name="message" cols="" rows="" placeholder="Votre message"></textarea></td>
			</tr>
			<tr>
				<td><input type="submit" value="Envoyer" name="envoyer"/></td>
			</tr>
		</table>
	</form>
</div>


<?php
include("include/footer.php");
?>