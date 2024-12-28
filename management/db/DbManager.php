<?php

namespace ch\comem;

class DbManagerCRUD implements I_ApiCRUD
{

    private $db;

    public function __construct()
    {
        $config = parse_ini_file('config' . DIRECTORY_SEPARATOR . 'db.ini', true);
        $dsn = $config['dsn'];
        $username = $config['username'];
        $password = $config['password'];
        $this->db = new \PDO($dsn, $username, $password);
        if (!$this->db) {
            die("Problème de connection à la base de données");
        }
    }

    //Fonction pour créer la table utilisateur
    public function creeTableUtilisateur(): bool
    {
        $sql = <<<COMMANDE_SQL
            CREATE TABLE IF NOT EXISTS utilisateurs (
		id INTEGER PRIMARY KEY AUTOINCREMENT,
                prenom VARCHAR(120) NOT NULL,
                nom VARCHAR(120) NOT NULL,
                email VARCHAR(120) NOT NULL UNIQUE,
                ecole VARCHAR(120) NOT NULL,
                filiere VARCHAR(120) NOT NULL,
                classe VARCHAR(20) NOT NULL,
                langue VARCHAR(40) NOT NULL,
                password VARCHAR(255) NOT NULL,
            );
COMMANDE_SQL;

        try {
            $this->db->exec($sql);
            $ok = true;
        } catch (\PDOException $e) {
            $e->getMessage();
            $ok = false;
        }
        return $ok;
    }

    //Fonction pour créer la table notes
    public function creeTableNotes(): bool
    {
        $sql = <<<COMMANDE_SQL
            CREATE TABLE IF NOT EXISTS notes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
                nomCours VARCHAR(120) NOT NULL,
                coeficient INTEGER NOT NULL,
                note FLOAT NOT NULL,
                utilisateur_id INTEGER NOT NULL,
                FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
            );
COMMANDE_SQL;

        try {
            $this->db->exec($sql);
            $ok = true;
        } catch (\PDOException $e) {
            $e->getMessage();
            $ok = false;
        }
        return $ok;
    }

    //Fonction pour ajouter un utilisateur
    public function ajouteUtilisateur(Utilisateur $utilisateur): int
    {
        //Ajoute l'utilisateur dans la base de données
        $datas = [
            'prenom' => $utilisateur->rendPrenom(),
            'nom' => $utilisateur->rendNom(),
            'email' => $utilisateur->rendEmail(),
            'ecole' => $utilisateur->rendEcole(),
            'filiere' => $utilisateur->rendFiliere(),
            'classe' => $utilisateur->rendClasse(),
            'langue' => $utilisateur->rendLangue(),
            'password' => $utilisateur->rendPassword(),
        ];
        $sql = "INSERT INTO utilisateurs (prenom, nom, email, ecole, filiere, classe, langue, password) VALUES "
            . "(:prenom, :nom, :email, :ecole, :filiere, :classe, :langue, :password);";
        $this->db->prepare($sql)->execute($datas);
        echo '<p style="color: green" class="mt-3 text-center">Utilisateur ajouté</p>';

        //Envoie du mail de vérification du mail avec le token personnalisé dans le lien
        // include('MailSender_Manager.php');

        return $this->db->lastInsertId();
    }

    //Fonction pour ajouter une note
    public function ajouteNotes(Notes $note): int
    {
        //Ajoute la note dans la base de données
        $datas = [
            'nomCours' => $note->rendNomCours(),
            'coeficient' => $note->rendCoeficient(),
            'note' => $note->rendNote(),
            'utilisateur_id' => $note->rendUtilisateurId(),
        ];
        $sql = "INSERT INTO notes (nomCours, coeficient, note, utilisateur_id) VALUES "
            . "(:nomCours, :coeficient, :note, :utilisateur_id);";
        $this->db->prepare($sql)->execute($datas);
        echo '<p style="color: green" class="mt-3 text-center">Note ajoutée</p>';

        return $this->db->lastInsertId();
    }

    //Fonction pour modifier un utilisateur
    public function modifieUtilisateur(Utilisateur $utilisateur): bool
    {
        $sql = "UPDATE utilisateurs SET prenom = :prenom, nom = :nom, email = :email, ecole = :ecole, filiere = :filiere, classe = :classe, langue = :langue, password = :password WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('prenom', $utilisateur->rendPrenom(), \PDO::PARAM_STR);
        $stmt->bindParam('nom', $utilisateur->rendNom(), \PDO::PARAM_STR);
        $stmt->bindParam('email', $utilisateur->rendEmail(), \PDO::PARAM_STR);
        $stmt->bindParam('ecole', $utilisateur->rendEcole(), \PDO::PARAM_STR);
        $stmt->bindParam('filiere', $utilisateur->rendFiliere(), \PDO::PARAM_STR);
        $stmt->bindParam('classe', $utilisateur->rendClasse(), \PDO::PARAM_STR);
        $stmt->bindParam('langue', $utilisateur->rendLangue(), \PDO::PARAM_STR);
        $stmt->bindParam('password', $utilisateur->rendPassword(), \PDO::PARAM_STR);
        $stmt->bindParam('id', $utilisateur->rendId(), \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    //Fonction pour récupérer tous les utilisateurs
    public function rendUtilisateur(string $nom): array
    {
        $sql = "SELECT * From utilisateurs WHERE nom = :nom;";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('nom', $nom, \PDO::PARAM_STR);
        $stmt->execute();
        $donnees = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $tabUtilisateurs = [];
        if ($donnees) {
            foreach ($donnees as $donneesUtilisateur) {
                $p = new Utilisateur(
                    $donneesUtilisateur["prenom"],
                    $donneesUtilisateur["nom"],
                    $donneesUtilisateur["email"],
                    $donneesUtilisateur["ecole"],
                    $donneesUtilisateur["filiere"],
                    $donneesUtilisateur["classe"],
                    $donneesUtilisateur["langue"],
                    $donneesUtilisateur["password"],
                    $donneesUtilisateur["id"]
                );
                $tabUtilisateurs[] = $p;
            }
        }
        return $tabUtilisateurs;
    }

    //Fonction pour récupérer les notes d'un utilisateur
    public function rendNotes(int $id): array
    {
        $sql = "SELECT * From notes WHERE utilisateur_id = :id;";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $donnees = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $tabNotes = [];
        if ($donnees) {
            foreach ($donnees as $donneesNote) {
                $n = new Notes(
                    $donneesNote["nomCours"],
                    $donneesNote["coeficient"],
                    $donneesNote["note"],
                    $donneesNote["utilisateur_id"],
                    $donneesNote["id"]
                );
                $tabNotes[] = $n;
            }
        }
        return $tabNotes;
    }

    //Fonction pour supprimer un utilisateur
    public function supprimeUtilisateur(int $id): bool
    {
        $sql = "DELETE FROM utilisateurs WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    //Fonction pour supprimer une note
    public function supprimeNotes(int $id): bool
    {
        $sql = "DELETE FROM notes WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    //Fonction pour vérifier le login d'un utilisateur
    public function loginUtilisateur(): void
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'];

        if ($email && $password) {
            $db = new \PDO("sqlite:db/dbpsw.sqlite", "", "");
            $sql = "SELECT * FROM utilisateurs WHERE email = :email";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':email', $email);

            if ($stmt->execute()) {
                $result = $stmt->fetch(\PDO::FETCH_ASSOC);
                if ($result && password_verify($password, $result['password']) && $result['verify'] == 1) {
                    session_start();
                    $_SESSION['email'] = $email;
                    $_SESSION['prenom'] = $result['prenom'];
                    $_SESSION['nom'] = $result['nom'];
                    header("Location: page1_unprotected.php");
                } else {
                    echo '<p style="color: red" class="mt-3 text-center">Email ou mot de passe incorrect</p>';
                }
            } else {
                echo '<p style="color: red" class="mt-3 text-center">Erreur de connexion</p>';
            }
        } else {
            echo '<p style="color: red" class="mt-3 text-center">Email ou mot de passe incorrect</p>';
        }
    }

    // //Fonction pour vérifier le token de l'utilisateur
    // public function verifyToken(string $token): void
    // {
    //     $sql = "SELECT * FROM utilisateurs WHERE token = :token";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->bindParam(':token', $token);

    //     if ($stmt->execute()) {
    //         $result = $stmt->fetch(\PDO::FETCH_ASSOC);
    //         if ($result) {
    //             $sql = "UPDATE utilisateurs SET verify = 1 WHERE token = :token";
    //             $stmt = $this->db->prepare($sql);
    //             $stmt->bindParam(':token', $token);
    //             $stmt->execute();
    //             echo '<p style="color: green" class="mt-3 text-center">Email vérifié</p>';
    //         } else {
    //             echo '<p style="color: red" class="mt-3 text-center">Erreur de vérification</p>';
    //         }
    //     } else {
    //         echo '<p style="color: red" class="mt-3 text-center">Erreur de vérification</p>';
    //     }
    // }
}
