<?php
session_start();

require('./fpdf186/fpdf.php');
require_once('./config/autoload.php');

use \management\db\DbManager;
use \ch\comem\I_ApiCRUD;
use \ch\comem\Personne;

$nom = $_SESSION['nom'];
$db = new DbManager();

if($_SESSION["id"]) {

    $utilisateur = $db->rendUtilisateur($_SESSION["nom"]);
    $tableauNote = $db->rendNotes($_SESSION["id"]);
    $chaine = "coucou";

    $prenom = $_SESSION["prenom"];
    $nom = $_SESSION["nom"];
    
    $pdf = new FPDF();
    $pdf->SetTitle($chaine);
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(80, 10, "Carnet de Notes de ".$prenom.' '.$nom);


    $pdf->Output();

} else {
    echo "Erreur du chargement du pdf";
}


?>



