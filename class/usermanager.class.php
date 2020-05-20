<?php 
class UserManager
{
	protected $db;

	public function __construct($db)
	{
		$this->db = $db;
	}

	public function add(User $user)
	{
		$q = $this->db->prepare("INSERT INTO users(nom, prenom, naissance, pseudo, mdp, email, hobby, proposer, dateInscription, ip) 
									VALUES(:nom, :prenom, :naissance, :pseudo, :mdp, :email, :hobby, :proposer, NOW(), :ip)");
		$q->bindValue(":nom", $user->nom());
		$q->bindValue(":prenom", $user->prenom());
		$q->bindValue(":naissance", $user->naissance());
		$q->bindValue(":pseudo", $user->pseudo());
		$q->bindValue(":mdp", $user->mdp());
		$q->bindValue(":email", $user->email());
		$q->bindValue(":hobby", $user->hobby());
		$q->bindValue(":proposer", $user->proposer());
		$q->bindValue(":ip", $user->ip());
		$q->execute();

		$user->hydrate([
			"id" => $this->db->lastInsertId()]);


	}

	public function delete(User $user)
	{
		$this->db->exec("DELETE FROM users WHERE id = " .$user->id());
	}

	public function get($info)
	{
		if(is_string($info))
		{
			$q = $this->db->prepare("SELECT * FROM users WHERE pseudo = :pseudo");
			$q->bindValue(":pseudo", $info);
			$q->execute();
			$donnees = $q->fetch(PDO::FETCH_ASSOC);

			return new User($donnees);
		}
		if(is_int($info))
		{
			$info = (int) $info;
			$q = $this->db->prepare("SELECT * FROM users WHERE id = :id");
			$q->bindValue(":id", $info, PDO::PARAM_INT);
			$q->execute();
			$donnees = $q->fetch(PDO::FETCH_ASSOC);

			return new User($donnees);
		}
	}

	public function getList()
	{
			$users = [];
		
			$q = $this->db->query("SELECT * FROM users ORDER BY pseudo");
				
			while($donnees = $q->fetch(PDO::FETCH_ASSOC))
			{				
				$users[] = new User($donnees);
			}
			return $users;	
	}

	public function adressList($pseudo)
	{
		$users = [];

		$q = $this->db->prepare("SELECT * FROM users WHERE pseudo <> :pseudo");
		$q->execute(["pseudo" => $pseudo]);

		while($donnees = $q->fetch(PDO::FETCH_ASSOC))
		{
			$users[] = new User($donnees);
		}
		return $users;
	}

	public function count()
	{
		return $this->db->query("SELECT COUNT(*) FROM users ")->fetchColumn();
	}

	public function existPseudo($info)
	{
		$q = $this->db->prepare("SELECT COUNT(*) FROM users WHERE pseudo = :pseudo");
		$q->bindValue(":pseudo", $info);
		$q->execute();

		return (bool) $q->fetchColumn();
	}

	public function existId($id)
	{
		$q = $this->db->prepare("SELECT COUNT(*) FROM users WHERE id = :id ");
		$q->bindValue(":id", $id, PDO::PARAM_INT);
		$q->execute();

		return (bool) $q->fetchColumn();
	}

	public function existMail($mail)
	{
		$q = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
		$q->bindValue(":email", $mail);
		$q->execute();
		return (bool) $q->fetchColumn();
	}
	
	public function connect($pseudo, $mdp)
	{
		$q = $this->db->prepare("SELECT COUNT(*) FROM users WHERE pseudo = :pseudo AND mdp = :mdp");
		$q->bindValue(":pseudo", $pseudo);
		$q->bindValue(":mdp", $mdp);
		$q->execute();
		
		return (bool) $q->fetchColumn();

	}

	public function update(User $user)
	{
		$q = $this->db->prepare("UPDATE SET pseudo = :pseudo, mdp = :mdp, email = :email, hobby = :hobby WHERE id = :id");
		$q->bindValue(":nom", $user->nom());
		$q->bindValue(":prenom", $user->prenom());
		$q->bindValue(":naissance", $user->naissance());
		$q->bindValue(":pseudo", $user->pseudo());
		$q->bindValue(":mdp", $user->mdp());
		$q->bindValue(":email", $user->email());
		$q->bindValue(":hobby", $user->hobby());
		$q->bindValue(":id", $user->id());
		$q->execute();
	}
}

?>