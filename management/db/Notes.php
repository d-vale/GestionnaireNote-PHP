<?php

namespace management\db;

use \Exception;

/**
 * Permet de simuler une liste de notes ayant :
 *  - un id (facultatif)
 *  - un nom de cours
 *  - un coeficient
 *  - une note
 *  - l'id d'un utilisateur
 */
class Notes
{

    private $id;
    private $module;
    private $nomEvaluation;
    private $nomCours;
    private $coeficient;
    private $note;
    private $utilisateur_id;

    /**
     * Construit une nouvelle note avec des paramètres spécifiés
     */
    public function __construct(string $module, int $coeficient, string $nomCours,  float $note, string $nomEvaluation, int $utilisateur_id)
    {
        if (empty($module)) {
            echo ('Il faut un module');
        }
        if (empty($nomEvaluation)) {
            echo ('Il faut un nom d\'évaluation');
        }
        if (empty($coeficient)) {
            echo ('Il faut un coeficient');
        }
        if ($coeficient < 1 || $coeficient > 100) {
            echo ('Il faut un coeficient entre 1 et 100 sans le % (ex: 20)');
        }
        if (empty($nomCours)) {
            echo ('Il faut un nom de cours');
        }
        if (empty($coeficient)) {
            echo ('Il faut un coeficient');
        }
        if (empty($note)) {
            echo ('Il faut une note');
        }
        if ($note < 0 || $note > 6) {
            echo ('La note doit être comprise entre 0 et 6');
        }
        if (empty($utilisateur_id)) {
            throw new Exception('Erreur lors de la création de la note');
        }

        $this->module = $module;
        $this->nomEvaluation = $nomEvaluation;
        $this->nomCours = $nomCours;
        $this->coeficient = $coeficient;
        $this->note = $note;
        $this->utilisateur_id = $utilisateur_id;
    }

    /**
     * Defini l'id de la note
     */
    public function definiId($id): void
    {
        if ($id > 0) {
            $this->id = $id;
        }
    }

    /**
     * Rend l'id de la note
     */
    public function rendId(): int
    {
        return $this->id;
    }

    /**
     * Rend le nom du cours
     */
    public function rendNomCours(): string
    {
        return $this->nomCours;
    }

    /**
     * Rend le coeficient
     */
    public function rendCoeficient(): int
    {
        return $this->coeficient;
    }

    /**
     * Rend la note
     */
    public function rendNote(): float
    {
        return $this->note;
    }

    /**
     * Rend le module
     */
    public function rendModule(): string
    {
        return $this->module;
    }

    /**
     * Rend le nom de l'évaluation
     */
    public function rendNomEvaluation(): string
    {
        return $this->nomEvaluation;
    }

    /**
     * Rend l'id de l'utilisateur
     */
    public function rendUtilisateurId(): int
    {
        return $this->utilisateur_id;
    }

    /**
     * Rend une description de la note
     */
    public function __toString(): string
    {
        return $this->id . " " .
            $this->module . " " .
            $this->nomEvaluation . " " .
            $this->nomCours . " " .
            $this->coeficient . " " .
            $this->note . " " .
            $this->utilisateur_id;
    }
}
