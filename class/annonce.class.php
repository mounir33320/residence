<?php 
class Annonce
{
	protected $id;
	protected $pseudo;
	protected $message;
	protected $dateCreation;

	public function __construct($donnees)
	{
		$this->hydrate($donnees);
	}

	public function hydrate(array $donnees)
	{
		foreach($donnees as $key => $value)
		{
			$method = "set" .ucfirst($key);
			if(method_exists($this, $method))
			{
				$this->$method($value);
			}
		}
	}

	public function id()
	{
		return $this->id;
	}

	public function pseudo()
	{
		return $this->pseudo;
	}

	public function message()
	{
		return $this->message;
	}

	public function dateCreation()
	{
		return $this->dateCreation;
	}

	public function setId($id)
	{
		$id = (int) $id;
		$this->id = $id;
	}

	public function setPseudo($pseudo)
	{
		$this->pseudo = $pseudo;
	}

	public function setMessage($message)
	{
		if(is_string($message))
		{
			$this->message = htmlspecialchars($message);
		}
	}

	public function setDateCreation($date)
	{
		$this->dateCreation = $date;
	}
}


?>