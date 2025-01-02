<?php

namespace management\db;

use \Exception;

/**
 * Permet de simuler un utilisateur ayant :
 *  - un id (facultatif)
 *  - un nom
 *  - un prénom
 *  - un email
 *  - une ecole
 *  - une filière
 *  - une classe
 *  - une langue
 *  - un mot de passe
 */
class Utilisateur {

    private $id;
    private $prenom;
    private $nom;
    private $email;
    private $ecole;
    private $filiere;
    private $classe;
    private $langue;
    private $password;

    /**
     * Construit une nouvelle personne avec les paramètres spécifiés
     * @param int $prenom Prénom
     * @param string $nom Nom
     * @param string $email Email
     * @param string $noTel noTel
     * @param string $id Identifiant de la personne
     * @throws Exception Lance une expection si un des paramètres n'est pas spécifié
     */
    public function __construct(string $prenom, string $nom, string $email, string $ecole, string $filiere, string $classe, string $password, string $langue = "Français") {
        if (empty($prenom)) {
            throw new Exception('Il faut un prénom');
        }
        if (empty($nom)) {
            throw new Exception('Il faut un nom');
        }
        if (empty($email)) {
            throw new Exception('Il faut un email');
        }
        if (empty($ecole)) {
            throw new Exception('Il faut une école');
        }
        if (empty($filiere)) {
            throw new Exception('Il faut une filière');
        }
        if (empty($classe)) {
            throw new Exception('Il faut une classe');
        }
        if (empty($langue)) {
            throw new Exception('Il faut une langue');
        }
        if (empty($password)) {
            throw new Exception('Il faut un mot de passe');
        }

        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->email = $email;
        $this->ecole = $ecole;
        $this->filiere = $filiere;
        $this->classe = $classe;
        $this->langue = $langue;
        $this->password = $password;
    }

    /**
     * Rend l'id de la utilisateur
     */
    public function rendId(): int {
        return $this->id;
    }

    /**
     * Defini l'id de la utilisateur
     */
    public function definiId($id): void {
        if ($id > 0) {
            $this->id = $id;
        }
    }

    /**
     * Rend le prénom
     */
    public function rendPrenom(): string {
        return $this->prenom;
    }

    /**
     * Rend le nom
     */
    public function rendNom(): string {
        return $this->nom;
    }

    /**
     * Rend l'email
     */
    public function rendEmail(): string {
        return $this->email;
    }
    

    /**
     * Rend le numéro de ecole
     */
    public function rendEcole(): string {
        return $this->ecole;
    }

    /**
     * Rend le mot de passe
     */
    public function rendPassword(): string {
        return $this->password;
    }

    /**
     * Rend le filière
     */
    public function rendFiliere(): string {
        return $this->filiere;
    }

    /**
     * Rend le classe
     */
    public function rendClasse(): string {
        return $this->classe;
    }

    /**
     * Rend la langue
     */
    public function rendLangue(): string {
        return $this->langue;
    }

    /**
     * Rend une description complète de la personne
     */
    public function __toString(): string {
        return $this->id . " " .
                $this->prenom . " " .
                $this->nom . " " .
                $this->email . " " .
                $this->ecole . " " .
                $this->filiere . " " .
                $this->classe . " " .
                $this->langue . " " .
                $this->password;
    }

}
