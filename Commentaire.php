<?php
require_once 'Database.php';

class Commentaire {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    // Ajouter un commentaire
    public function addComment($user_id, $randonnee_id, $commentaire) {
        $stmt = $this->pdo->prepare("INSERT INTO commentaires (user_id, randonnee_id, commentaire) VALUES (?, ?, ?)");
        return $stmt->execute([$user_id, $randonnee_id, $commentaire]);
    }

    // Récupérer les commentaires pour une randonnée
    public function getCommentsForRandonnee($randonnee_id) {
        $stmt = $this->pdo->prepare("SELECT c.*, u.nom 
                                     FROM commentaires c 
                                     JOIN users u ON c.user_id = u.id 
                                     WHERE randonnee_id = ?");
        $stmt->execute([$randonnee_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
