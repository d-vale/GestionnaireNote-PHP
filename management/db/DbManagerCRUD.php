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
                nom VARCHAR(120) NOT NULL,
                prenom VARCHAR(120) NOT NULL,
                email VARCHAR(120) NOT NULL UNIQUE,
                noTel VARCHAR(20) NOT NULL UNIQUE,
                password VARCHAR(900) NOT NULL,
                token VARCHAR(900) NOT NULL,
                verify BOOLEAN NOT NULL
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
            'nom' => $utilisateur->rendNom(),
            'prenom' => $utilisateur->rendPrenom(),
            'email' => $utilisateur->rendEmail(),
            'noTel' => $utilisateur->rendNoTel(),
            'password' => $utilisateur->rendPassword(),
            'token' => $utilisateur->rendToken(),
            'verify' => $utilisateur->rendVerify(),
        ];
        $sql = "INSERT INTO utilisateurs (nom, prenom, email, noTel, password, token, verify) VALUES "
            . "(:nom, :prenom, :email, :noTel, :password, :token, :verify);";
        $this->db->prepare($sql)->execute($datas);
        echo '<p style="color: green" class="mt-3 text-center">Utilisateur ajouté</p>';

        //Envoie du mail de vérification du mail avec le token personnalisé dans le lien
        include('MailSender_Manager.php');

        return $this->db->lastInsertId();
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
                    $donneesUtilisateur["nom"],
                    $donneesUtilisateur["prenom"],
                    $donneesUtilisateur["email"],
                    $donneesUtilisateur["noTel"],
                    $donneesUtilisateur["id"],
                    $donneesUtilisateur["password"],
                    $donneesUtilisateur["token"],
                    $donneesUtilisateur["verify"]
                );
                $tabUtilisateurs[] = $p;
            }
        }
        return $tabUtilisateurs;
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

    //Fonction pour vérifier le token de l'utilisateur
    public function verifyToken(string $token): void
    {
        $sql = "SELECT * FROM utilisateurs WHERE token = :token";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':token', $token);

        if ($stmt->execute()) {
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($result) {
                $sql = "UPDATE utilisateurs SET verify = 1 WHERE token = :token";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':token', $token);
                $stmt->execute();
                echo '<p style="color: green" class="mt-3 text-center">Email vérifié</p>';
            } else {
                echo '<p style="color: red" class="mt-3 text-center">Erreur de vérification</p>';
            }
        } else {
            echo '<p style="color: red" class="mt-3 text-center">Erreur de vérification</p>';
        }
    }
}
