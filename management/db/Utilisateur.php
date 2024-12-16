<?php

namespace ch\comem;

use \Exception;

/**
 * Permet de simuler une personne ayant :
 *  - un id (facultatif)
 *  - un nom
 *  - un prénom
 *  - un email
 *  - un numéro de téléphone
 *  - un mot de passe
 */
class Utilisateur {

    private $id;
    private $nom;
    private $prenom;
    private $email;
    private $noTel;
    private $password;
    private $token;
    private $verify;

    /**
     * Construit une nouvelle personne avec les paramètres spécifiés
     * @param int $prenom Prénom
     * @param string $nom Nom
     * @param string $email Email
     * @param string $noTel noTel
     * @param string $id Identifiant de la personne
     * @throws Exception Lance une expection si un des paramètres n'est pas spécifié
     */
    public function __construct(string $prenom, string $nom, string $email, string $noTel, string $password, string $token, bool $verify, int $id = 0) {
        if (empty($prenom)) {
            throw new Exception('Il faut un prénom');
        }
        if (empty($nom)) {
            throw new Exception('Il faut un nom');
        }
        if (empty($email)) {
            throw new Exception('Il faut un email');
        }
        if (empty($noTel)) {
            throw new Exception('Il faut un numéro de téléphone');
        }
        if (empty($password)) {
            throw new Exception('Il faut un mot de passe');
        }
        if (empty($token)) {
            throw new Exception('Erreur dans la génération du token');
        }
        if ($id < 0) {
            throw new Exception('Il faut un id valide');
        }

        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->email = $email;
        $this->noTel = $noTel;
        $this->password = $password;
        $this->token = $token;
        $this->verify = $verify;
        $this->id = $id;
    }

    /**
     * Rend l'id de la personne
     */
    public function rendId(): int {
        return $this->id;
    }

    /**
     * Defini l'id de la personne
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
     * Rend le numéro de téléphone
     */
    public function rendNoTel(): string {
        return $this->noTel;
    }

    /**
     * Rend le mot de passe
     */
    public function rendPassword(): string {
        return $this->password;
    }

    /**
     * Rend le token
     */
    public function rendToken(): string {
        return $this->token;
    }

    /**
     * Rend le verify
     */
    public function rendVerify(): bool {
        return $this->verify;
    }

    /**
     * Rend une description complète de la personne
     */
    public function __toString(): string {
        return $this->id . " " .
                $this->prenom . " " .
                $this->nom . " " .
                $this->email . " " .
                $this->noTel . '<br>';
    }

}
