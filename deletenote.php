<?php
//Vérifier si l'utilisateur est connecté et a accès à la page
session_start();
require_once("./config/autoload.php");

use management\db\DbManager;

if (!isset($_SESSION['id'])) {
    // If not logged in, redirect to login page
    header("Location: connection.php");
    exit;
}

if (isset($_GET['id'])) {
    $noteId = $_GET['id'];
    
    try {
        $dbManager = new DbManager();
        
        // Delete the note
        if ($dbManager->supprimeNotes($noteId)) {
            // Redirect back to profile page after successful deletion
            header("Location: profil.php");
            exit;
        } else {
            // If deletion failed
            $_SESSION['error'] = "Erreur lors de la suppression de la note";
            header("Location: profil.php");
            exit;
        }
    } catch (\PDOException $e) {
        $_SESSION['error'] = "Erreur de base de données: " . $e->getMessage();
        header("Location: profil.php");
        exit;
    }
} else {
    // If no ID was provided
    $_SESSION['error'] = "Aucune note spécifiée pour la suppression";
    header("Location: profil.php");
    exit;
}
?>