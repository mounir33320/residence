<?php
class AnnonceManager
{
	protected $db;

	public function __construct($db)
	{
		$this->db = $db;
	}

	public function add(Annonce $annonce)
	{
		$q = $this->db->prepare("INSERT INTO annonces(pseudo, message, dateCreation) VALUES(:pseudo, :message, NOW()) ");
		$q->bindValue(":pseudo", $annonce->pseudo());
		$q->bindValue(":message", $annonce->message());
		$q->execute();

		$annonce->hydrate([
			"id" => $this->db->lastInsertId()]);
	}

	public function delete(Annonce $annonce)
	{
		$this->db->exec("DELETE FROM annonces WHERE id =" .$annonce->id());
	}

	public function count()
	{
		return $this->db->query("SELECT COUNT(*) FROM annonces ")->fetchColumn();
	}

	public function getList()
	{
		$annonces = [];

		$q = $this->db->query("SELECT id, pseudo, message, DATE_FORMAT(dateCreation, 'le %d/%m/%Y à %H:%i') AS dateCreation FROM annonces ORDER BY id DESC");
		while($donnees = $q->fetch(PDO::FETCH_ASSOC))
		{
			$annonces[] = new Annonce($donnees);
		}
		return $annonces;
	}

	public function get($info)
	{
		$q = $this->db->prepare("SELECT * FROM annonces WHERE id = :id");
		$q->execute(["id" => $info]);

		return $annonce = new Annonce($q->fetch(PDO::FETCH_ASSOC));
	}

	public function exist($info)
	{
		$q = $this->db->prepare("SELECT * FROM annonces WHERE id = :id");
		$q->execute(["id" => $info]);
		return (bool) $q->fetchColumn();
	}


}




