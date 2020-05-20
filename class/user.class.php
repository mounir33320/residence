<?php 
class User
{
	protected $id;
	protected $nom;
	protected $prenom;
	protected $naissance; 
	protected $pseudo;
	protected $mdp;
	protected $email;
	protected $hobby;
	protected $proposer;
	protected $dateInscription;
	protected $ip;

	public function __construct($donnees)
	{
		$this->hydrate($donnees);
	}

	public function hydrate(array $donnees)
	{
		foreach($donnees as $key => $value)
		{
			$method = "set".ucfirst($key);
			
			if(method_exists($this, $method))
			{
				$this->$method($value);
			}
		}
	}

	/*Getters*/
	public function id()
	{
		return $this->id;
	}

	public function nom()
	{
		return $this->nom;
	}

	public function prenom()
	{
		return $this->prenom;
	}

	public function naissance()
	{
		return $this->naissance;
	}

	public function pseudo()
	{
		return $this->pseudo;
	}

	public function mdp()
	{
		return $this->mdp;
	}

	public function email()
	{
		return $this->email;
	}

	public function hobby()
	{
		return $this->hobby;
	}

	public function proposer()
	{
		return $this->proposer;
	}

	public function dateInscription()
	{
		return $this->dateInscription;
	}

	public function ip()
	{
		return $this->ip;
	}

	/*Setters*/
	public function setId($id)
	{
		$id = (int) $id;
		$this->id = $id;
		
	}

	public function setNom($nom)
	{
		if(is_string($nom))
		{
			$this->nom = htmlspecialchars($nom);
		}
	}

	public function setPrenom($prenom)
	{
		if(is_string($prenom))
		{
			$this->prenom = htmlspecialchars($prenom);
		}
	}

	public function setNaissance($naissance)
	{
		if(preg_match("#^[0-9]{2}/[0-9]{2}/[0-9]{4}$#", $naissance))
		{
			$this->naissance = $naissance;
		}
	}

	public function setPseudo($pseudo)
	{
		if(is_string($pseudo))
		{
			$this->pseudo = htmlspecialchars($pseudo);
		}
	}

	public function setMdp($mdp)
	{
		$this->mdp = sha1($mdp);
	}

	

	public function setEmail($email)
	{
		if(preg_match("#^[a-z0-9_.-]+@[a-z0-9_.-]{2,}.[a-z]{2,4}$#", $email))
		{
			$this->email = $email;
		}
	}

	public function setHobby($hobby)
	{
		$this->hobby = htmlspecialchars($hobby);
	}

	public function setProposer($proposer)
	{
		$this->proposer = htmlspecialchars($proposer);
	}

	public function setDateInscription($date)
	{
		$this->dateInscription = $date;
	}

	

	public function setIp($ip)
	{
		$this->ip = $ip;
	}

	public function naissanceValide($naissance)
	{
		return !preg_match("#^[0-9]{2}/[0-9]{2}/[0-9]{4}$#", $naissance);
	}

	public function mailValide($email)
	{
		return preg_match("#^[a-z0-9_.-]+@[a-z0-9_.-]{2,}.[a-z]{2,4}$#", $email);
	}

	public function pseudoValide($pseudo)
	{
		return (bool) preg_match("#^[a-zA-Z0-9._-]{0,13}$#", $pseudo);
	}
}


?>
