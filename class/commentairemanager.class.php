<?php 
class CommentaireManager
{
	protected $db;

	public function __construct($db)
	{
		$this->db = $db;
	}

	public function add(Commentaire $commentaire)
	{
		$q = $this->db->prepare("INSERT INTO commentaires(idMessage, auteur, contenu, dateCommentaire) VALUES(:idMessage, :auteur, :contenu, NOW())");
		$q->bindValue(":idMessage", $commentaire->idMessage(), PDO::PARAM_INT);
		$q->bindValue(":auteur", $commentaire->auteur());
		$q->bindValue(":contenu", $commentaire->contenu());
		$q->execute();

		$commentaire->hydrate([
			"id" => $this->db->lastInsertId()]);
	}

	public function delete(Commentaire $commentaire)
	{
		$this->db->exec("DELETE FROM commentaires WHERE id =".$commentaire->id());
	}

	public function get($info)
	{
		$q = $this->db->prepare("SELECT * FROM commentaires WHERE id = :id");
		$q->execute(["id" => $info]);
		return new Commentaire($q->fetch(PDO::FETCH_ASSOC));
	}

	public function getList($idMessage)
	{
		$commentaires = [];

		$q = $this->db->prepare("SELECT id, idMessage, auteur, contenu, DATE_FORMAT(dateCommentaire, 'le %d/%m/%Y à %H:%i') AS dateCommentaire FROM commentaires WHERE idMessage = :idMessage ORDER BY dateCommentaire ASC ");
		$q->bindValue(":idMessage", $idMessage, PDO::PARAM_INT);
		$q->execute();

		while($donnees = $q->fetch(PDO::FETCH_ASSOC))
		{
			$commentaires[] = new Commentaire($donnees);
		}
		return $commentaires;
	}

	public function update(Commentaire $commentaire)
	{
		$q = $this->db->prepare("UPDATE SET contenu = :contenu WHERE id = :id ");
		$q->bindValue(":contenu", $commentaire->contenu());
		$q->bindValue(":id", $commentaire->id());
		$q->execute();
	}
}



?>