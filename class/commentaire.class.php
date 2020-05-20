<?php 
class Commentaire
{
	protected $id;
	protected $idMessage;
	protected $auteur;
	protected $contenu;
	protected $dateCommentaire;

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

	/*Getters*/
	public function id()
	{
		return $this->id;
	}

	public function idMessage()
	{
		return $this->idMessage;
	}

	public function auteur()
	{
		return $this->auteur;
	}

	public function contenu()
	{
		return $this->contenu;
	}

	public function dateCommentaire()
	{
		return $this->dateCommentaire;
	}

	/*Setters*/
	public function setId($id)
	{
		$id =(int) $id;
		$this->id = $id;
	}

	public function setIdMessage($idMessage)
	{
		$idMessage = (int) $idMessage;
		$this->idMessage = $idMessage;
	}

	public function setAuteur($auteur)
	{
		$this->auteur = $auteur;
	}

	public function setContenu($contenu)
	{
		$this->contenu = htmlspecialchars($contenu);
	}

	public function setDateCommentaire($dateCommentaire)
	{
		$this->dateCommentaire = $dateCommentaire;
	}

	public function nomValide()
	{
		return !empty($this->contenu);
	}
}


?>