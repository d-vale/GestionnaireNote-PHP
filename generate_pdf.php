<?php
session_start();

require('./fpdf186/fpdf.php');

require_once('./config/autoload.php');

use \management\db\DbManager;
use \ch\comem\I_ApiCRUD;
use \ch\comem\Personne;

//on récupère les variables de session pour les mettre dans le pdf
$nom = $_SESSION['nom'];
$sortValue = $_SESSION['sortValue'];
$db = new DbManager();

//si un utilisateur est connecté, on peut créer son pdf
if($_SESSION['id']) {
    $tableauNote = $db->rendNotesTriees($_SESSION["id"], $sortValue);

    $prenom = $_SESSION["prenom"];
    $nom = $_SESSION["nom"];
    $titre = "Carnet de Notes de ".$prenom.' '.$nom;
    $titreEncode = iconv('UTF-8', 'windows-1252', $titre);
    
    $date = date_create()->format(format: 'd-m-Y');
    $hour = date_create('+1 hour')->format(format: 'H:i');


    //on crée l'objet pdf dans lequel on ajoute du contenu
    $pdf = new FPDF();
    $pdf->SetTitle($titre, true);
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',20);
    $pdf->Cell(0, 10, txt: $titreEncode);
    $pdf->Ln();
    $pdf->SetFont('Arial','',16);
    $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Récapitulatif des notes au '.$date. ' à '.$hour));
    $pdf->Ln();
    $pdf->SetFont('Arial','B',12);
    $pdf->Ln();

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(50, 10, iconv('UTF-8', 'windows-1252', "Module"),1,0,"C");
    $pdf->Cell(50, 10, "Evaluation",1,0,"C");
    $pdf->Cell(50, 10, "Cours",1,0,"C");
    $pdf->Cell(23, 10, "Coeficient",1,0,"C");
    $pdf->Cell(20, 10, iconv('UTF-8', 'windows-1252', "Résultat"),1,0,"C");

    $pdf->Ln();

    $pdf->SetFont('Arial','',10);

    //on génère le tableau de notes
    foreach($tableauNote as $note) {
        $module = $note->rendModule();
        $cours = $note->rendNomCours();
        $coeficient = $note->rendCoeficient();
        $resultat = $note->rendNote();
        $nomEvaluation = $note->rendNomEvaluation();

        $pdf->Cell(50, 10, iconv('UTF-8', 'windows-1252', $module),1,0,"C");
        $pdf->Cell(50, 10, iconv('UTF-8', 'windows-1252', $cours),1,0,"C");
        $pdf->Cell(50, 10, iconv('UTF-8', 'windows-1252', $nomEvaluation),1,0,"C");
        $pdf->Cell(23, 10, iconv('UTF-8', 'windows-1252', $coeficient),1,0,"C");
        $pdf->Cell(20, 10, iconv('UTF-8', 'windows-1252', $resultat),1,0,"C");
        $pdf->Ln();


    }

    $pdf->Output();

} else {
    echo "Erreur du chargement du pdf";
}


?>



