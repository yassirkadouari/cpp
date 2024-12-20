<?php
require_once 'Database.php';

class Inscription {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    // Vérifie si un utilisateur est déjà inscrit à une randonnée
    public function isUserAlreadyRegistered($user_id, $rando_id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM inscriptions WHERE user_id = :user_id AND randonnee_id = :rando_id");
        $stmt->execute([':user_id' => $user_id, ':rando_id' => $rando_id]);
        return $stmt->fetchColumn() > 0;
    }

    // Inscrit un utilisateur à une randonnée
    public function registerUserToRando($user_id, $rando_id) {
        if ($this->isUserAlreadyRegistered($user_id, $rando_id)) {
            throw new Exception("Vous êtes déjà inscrit à cette randonnée.");
        }

        $stmt = $this->pdo->prepare("INSERT INTO inscriptions (user_id, randonnee_id) VALUES (:user_id, :rando_id)");
        return $stmt->execute([':user_id' => $user_id, ':rando_id' => $rando_id]);
    }

    // Récupère les inscriptions d'un utilisateur
    public function getUserInscriptions($user_id) {
        $stmt = $this->pdo->prepare("
            SELECT r.* 
            FROM inscriptions i
            JOIN randonnees r ON i.randonnee_id = r.id
            WHERE i.user_id = :user_id
        ");
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
