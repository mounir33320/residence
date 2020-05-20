<?php 
include("include/header.php");

if(isset($_SESSION["pseudo"]))
{
	header("Location: index.php");
	exit;
}


if(isset($_POST["register"]))
{
	if(!empty($_POST["reg_nom"]) && !empty($_POST["reg_prenom"]) && !empty($_POST["reg_naissance"]) && !empty($_POST["reg_pseudo"]) && !empty($_POST["reg_mdp"]) && !empty($_POST["reg_mdp2"]) && !empty($_POST["reg_email"]) && !empty($_POST["reg_email2"]))
	{
		$reg_nom = htmlspecialchars($_POST["reg_nom"]);
		$reg_prenom = htmlspecialchars($_POST["reg_prenom"]);
		$reg_naissance = $_POST["reg_naissance"];
		$reg_pseudo = htmlspecialchars($_POST["reg_pseudo"]);
		$reg_mdp = $_POST["reg_mdp"];
		$reg_mdp2 = $_POST["reg_mdp2"];
		$reg_email = $_POST["reg_email"];
		$reg_email2 = $_POST["reg_email2"];

		$donnees = array($reg_nom,$reg_prenom,$reg_naissance,$reg_pseudo,$reg_mdp,$reg_email);
		$user = new User(array("nom" => $reg_nom,
								"prenom" => $reg_prenom,
								"naissance" => $reg_naissance,
								"pseudo" => $reg_pseudo,
								"mdp" => $reg_mdp,
								"email" => $reg_email,
								"hobby" => $_POST["reg_hobby"],
								"proposer" => $_POST["reg_proposer"]));
	
		if(!$user->naissanceValide($user->naissance()))
		{
			if(!$manager->existPseudo($user->pseudo()))
			{
				if($user->pseudoValide($user->pseudo()))
				{
					if($reg_mdp == $reg_mdp2)
					{
						if(!$user->mailValide($reg_email))
						{
							if($reg_email == $reg_email2)
							{
								if(!$manager->existMail($user->email()))
								{
									$user->setIp($_SERVER["REMOTE_ADDR"]);
									$manager->add($user);
									$_SESSION["pseudo"] = $user->pseudo();
									/**********************************************PHPMAILER********************************************/
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
$mail->addAddress($user->email());
$mail->isHTML(true);
//Set the subject line
$mail->Subject = 'Confirmation de votre inscription';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$url = "<p><a href='https://www.residence-sources.fr'>www.residence-sources.fr</a></p>";
$mail->msgHTML("<p>Bienvenue !</p><p>Merci pour votre inscription, voici vos informations :</p><p>Pseudo : " .$user->pseudo(). "</p><p>Mot de passe : " .$_POST['reg_mdp']. "</p>".$url);
//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
$mail->addAttachment('images/logo_mail.png');
//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "<p align='center'>Votre compte a bien été enregistré !</p>";
}

header("Refresh: 3;URL=index.php");
exit;








									/*********************************************************************************************************/
								}
								else
								{
									$erreur = "Cet adresse email existe déjà !";
								}
							}
							else
							{
								$erreur = "Les deux adresses email sont différentes !";
							}
						}
						else
						{
							$erreur = "L'adresse email n'est pas au bon format !";
						}
					}
					else
					{
						$erreur = "Les mots de passe doivent être identiques !";
					}
				}
				else
				{
					$erreur = "Le pseudo ne doit pas dépasser 13 caractères";
				}
			}
			else
			{
				$erreur = "Ce pseudo existe déjà !";
			}
		}	
		else
		{
			$erreur = "La date de naissance n'est pas au bon format !";
		}
	}
	else
	{
		$erreur = "Tous les champs doivent être complétés !";
	}
}
?>
<p align="center" style="color:#e74c3c;"><?php if(isset($erreur)){echo $erreur;}?></p>
<div id="bloc_register">
	<h1 align="center" id="inscription">Inscription</h1>
	<form action="" method="post" id="register_form">
		<table align="center">
			<tr>
				<td><label for="reg_nom">Nom* : </label></td>
				<td><input type="text" name="reg_nom" id="reg_nom" value="<?php if(isset($_POST['reg_nom'])){echo $_POST['reg_nom'];}?>" /></td>
			</tr>
			<tr>
				<td><label for="reg_prenom">Prénom* : </label></td>	
				<td><input type="text" name="reg_prenom" id="reg_prenom" value="<?php if(isset($_POST['reg_prenom'])){echo $_POST['reg_prenom'];}?>"/></td>	
			</tr>
			<tr>
				<td><label for="reg_naissance">Date de naissance<em>(jj/mm/aaaa)*</em> : </label></td>
				<td><input type="text" name="reg_naissance" id="reg_naissance" value="<?php if(isset($_POST['reg_naissance'])){echo $_POST['reg_naissance'];}?>"/></td>
			</tr>
			<tr>
				<td><label for="reg_pseudo">Pseudo* : </label></td>
				<td><input type="text" name="reg_pseudo" id="reg_pseudo" value="<?php if(isset($_POST['reg_pseudo'])){echo $_POST['reg_pseudo'];}?>"/></td>	
			</tr>
			<tr>
				<td><label for="reg_mdp">Mot de passe* : </label></td>	
				<td><input type="password" name="reg_mdp" id="reg_mdp" /></td>
			</tr>
			<tr>
				<td><label for="reg_mdp2">Confirmez votre mot de passe* : </label></td>	
				<td><input type="password" name="reg_mdp2" id="reg_mdp2"/></td>
			</tr>
			<tr>
				<td><label for="reg_email">Email* : </label></td>	
				<td><input type="email" name="reg_email" id="reg_email" value="<?php if(isset($_POST['reg_email'])){echo $_POST['reg_email'];}?>"/></td>
			</tr>
			<tr>
				<td><label for="reg_email2">Confirmez votre email* : </label></td>	
				<td><input type="email" name="reg_email2" id="reg_email2"/></td>
			</tr>
			<tr>
				<td><label for="reg_hobby">Hobbies : </label></td>	
				<td><input type="text" name="reg_hobby" id="reg_hobby" value="<?php if(isset($_POST['reg_hobby'])){echo $_POST['reg_hobby'];}?>"/></td>
			</tr>
			<tr>
				<td><label for="reg_proposer">Proposition d'aide <em style="font-size: 0.8em;">(ex : cours de musique)</em>: </label></td>	
				<td><input type="text" name="reg_proposer" id="reg_proposer" value="<?php if(isset($_POST['reg_proposer'])){echo $_POST['reg_proposer'];}?>"/></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" value="S'enregister" name="register"/></td>
			</tr>
			
		</table>
	</form>
</div>

<?php 
include("include/footer.php");
?>