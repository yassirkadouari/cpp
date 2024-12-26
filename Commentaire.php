<?php
require_once 'Database.php';

class Commentaire {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    // Ajouter un commentaire
    public function addComment($user_id, $randonnee_id, $commentaire) {
        $stmt = $this->pdo->prepare("INSERT INTO commentaires (user_id, randonnee_id, commentaire, date_creation) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$user_id, $randonnee_id, $commentaire]);
    }

    // Récupérer les commentaires pour une randonnée
    public function getCommentsForRandonnee($randonnee_id) {
        $stmt = $this->pdo->prepare("SELECT c.commentaire, c.date_creation, u.nom AS auteur, u.email 
                                     FROM commentaires c 
                                     JOIN users u ON c.user_id = u.id 
                                     WHERE c.randonnee_id = ? 
                                     ORDER BY c.date_creation DESC");
        $stmt->execute([$randonnee_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
