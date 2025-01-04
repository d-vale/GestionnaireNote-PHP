<?php
//Vérifier si l'utilisateur est connecté et a accès à la page
session_start();
require_once("./config/autoload.php");

use management\db\DbManager;

if (!isset($_SESSION['id'])) {
    // Si l'utilisateur n'est pas connecté
    header("Location: profil.php");
    exit;
}

if (isset($_GET['id'])) {
    $noteId = $_GET['id'];
    
    try {
        $dbManager = new DbManager();
        
        // Supprimer la note avec la fonction du DbManager
        if ($dbManager->supprimeNotes($noteId)) {
            // Redirection vers la page profil
            header("Location: profil.php");
            exit;
        } else {
            // Si suppression échouel
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
    // Si l'id de la note n'est pas reçu
    $_SESSION['error'] = "Aucune note spécifiée pour la suppression";
    header("Location: profil.php");
    exit;
}
?>