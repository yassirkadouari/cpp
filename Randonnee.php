<?php
require_once 'Database.php';

class Randonnee {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    // Création d'une randonnée
    public function create($location, $distance, $difficulte, $organisateur_id, $guide_id = null, $image = null) {
        $stmt = $this->pdo->prepare("INSERT INTO randonnees (location, distance, difficulte, organisateur_id, guide_id, image) 
                                     VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$location, $distance, $difficulte, $organisateur_id, $guide_id, $image]);
    }
    public function getByGuide($guide_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM randonnees WHERE guide_id = :guide_id");
        $stmt->execute([':guide_id' => $guide_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // Récupération de toutes les randonnées
    public function getAll() {
        $stmt = $this->pdo->query("SELECT r.*, u.nom AS organisateur_name 
                                   FROM randonnees r
                                   JOIN users u ON r.organisateur_id = u.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // Récupération par ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM randonnees WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM randonnees WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    
    public function update($id, $location, $distance, $difficulte, $image) {
        $stmt = $this->pdo->prepare("
            UPDATE randonnees 
            SET location = ?, distance = ?, difficulte = ?, image = ? 
            WHERE id = ?
        ");
        return $stmt->execute([$location, $distance, $difficulte, $image, $id]);
    }
    public function incrementInscrits($rando_id) {
        $stmt = $this->pdo->prepare("UPDATE randonnees SET nb_inscrits = nb_inscrits + 1 WHERE id = :id");
        return $stmt->execute([':id' => $rando_id]);
    }
    
    
    // Récupération des randonnées par organisateur
    public function getByOrganisateur($organisateur_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM randonnees WHERE organisateur_id = ?");
        $stmt->execute([$organisateur_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
