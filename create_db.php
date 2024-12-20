<?php
class DatabaseSetup {
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO('mysql:host=localhost', 'root', '');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    public function createDatabase() {
        $this->pdo->exec("CREATE DATABASE IF NOT EXISTS randonnee_db");
        $this->pdo->exec("USE randonnee_db");

        // Création des tables
        $this->createUsersTable();
        $this->createRandonneesTable();
        $this->createCommentairesTable();
    }

    private function createUsersTable() {
        $query = "CREATE TABLE IF NOT EXISTS users (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            mot_de_passe VARCHAR(255) NOT NULL,
            role ENUM('organisateur', 'guide', 'utilisateur') DEFAULT 'utilisateur'
        ) ENGINE=InnoDB";
        $this->pdo->exec($query);
    }

    private function createRandonneesTable() {
        $query = "CREATE TABLE IF NOT EXISTS randonnees (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            location VARCHAR(100) NOT NULL,
            distance FLOAT NOT NULL,
            difficulte ENUM('facile', 'moyen', 'difficile') NOT NULL,
            organisateur_id INT UNSIGNED NOT NULL,
            guide_id INT UNSIGNED DEFAULT NULL,
            image VARCHAR(255),
            nb_inscrits INT DEFAULT 0,
            FOREIGN KEY (organisateur_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (guide_id) REFERENCES users(id) ON DELETE SET NULL
        ) ENGINE=InnoDB";
        $this->pdo->exec($query);
    }

    private function createCommentairesTable() {
        $query = "CREATE TABLE IF NOT EXISTS commentaires (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT UNSIGNED NOT NULL,
            randonnee_id INT UNSIGNED NOT NULL,
            commentaire TEXT NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (randonnee_id) REFERENCES randonnees(id) ON DELETE CASCADE
        ) ENGINE=InnoDB";
        $this->pdo->exec($query);
    }
}

// Créer la base de données et les tables
try {
    $setup = new DatabaseSetup();
    $setup->createDatabase();
    echo "Base de données et tables créées avec succès.";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
