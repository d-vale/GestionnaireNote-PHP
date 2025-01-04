<?php

namespace management\db;

class DbManager implements I_ApiCRUD
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
                token VARCHAR(350) NOT NULL,
                verify BOOLEAN NOT NULL DEFAULT 0
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
                module VARCHAR(120) NOT NULL,
                coeficient INTEGER NOT NULL,
                nomCours VARCHAR(120) NOT NULL,
                note FLOAT NOT NULL,
                nomEvaluation VARCHAR(120) NOT NULL,
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
            'token' => $utilisateur->rendToken(),
            'verify' => $utilisateur->rendVerify(),
        ];
        $sql = "INSERT INTO utilisateurs (prenom, nom, email, ecole, filiere, classe, langue, password, token, verify) VALUES "
            . "(:prenom, :nom, :email, :ecole, :filiere, :classe, :langue, :password, :token, :verify);";
        $this->db->prepare($sql)->execute($datas);
        echo '<p style="color: green" class="mt-3 text-center">Utilisateur ajouté</p>';

        // Envoie du mail de vérification du mail avec le token personnalisé dans le lien
        include('./management/mail/MailSender_Manager.php');

        return $this->db->lastInsertId();
    }

    //Fonction pour ajouter une note
    public function ajouteNotes(Notes $note): int
    {
        //Ajoute la note dans la base de données
        $datas = [
            'module' => $note->rendModule(),
            'nomEvaluation' => $note->rendNomEvaluation(),
            'nomCours' => $note->rendNomCours(),
            'coeficient' => $note->rendCoeficient(),
            'note' => $note->rendNote(),
            'utilisateur_id' => $note->rendUtilisateurId(),
        ];
        $sql = "INSERT INTO notes (module, coeficient, nomCours, note, nomEvaluation, utilisateur_id) VALUES "
            . "(:module, :coeficient, :nomCours, :note, :nomEvaluation, :utilisateur_id);";
        $this->db->prepare($sql)->execute($datas);
        echo '<p style="color: green" class="mt-3 text-center">Note ajoutée</p>';

        return $this->db->lastInsertId();
    }

    //Fonction pour modifier un utilisateur
    public function modifieUtilisateur(Utilisateur $utilisateur): bool
    {
        $sql = "UPDATE utilisateurs SET prenom = :prenom, nom = :nom, email = :email, ecole = :ecole, filiere = :filiere, classe = :classe, langue = :langue, password = :password WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $prenom = $utilisateur->rendPrenom();
        $nom = $utilisateur->rendNom();
        $email = $utilisateur->rendEmail();
        $ecole = $utilisateur->rendEcole();
        $filiere = $utilisateur->rendFiliere();
        $classe = $utilisateur->rendClasse();
        $langue = $utilisateur->rendLangue();
        $password = $utilisateur->rendPassword();
        $id = $utilisateur->rendId();
        $stmt->bindParam('prenom', $prenom, \PDO::PARAM_STR);
        $stmt->bindParam('nom', $nom, \PDO::PARAM_STR);
        $stmt->bindParam('email', $email, \PDO::PARAM_STR);
        $stmt->bindParam('ecole', $ecole, \PDO::PARAM_STR);
        $stmt->bindParam('filiere', $filiere, \PDO::PARAM_STR);
        $stmt->bindParam('classe', $classe, \PDO::PARAM_STR);
        $stmt->bindParam('langue', $langue, \PDO::PARAM_STR);
        $stmt->bindParam('password', $password, \PDO::PARAM_STR);
        $stmt->bindParam('id', $id, \PDO::PARAM_INT);
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
                    $donneesUtilisateur["token"],
                    $donneesUtilisateur["verify"],
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
                    $donneesNote["module"],
                    $donneesNote["coeficient"],
                    $donneesNote["nomCours"],
                    $donneesNote["note"],
                    $donneesNote["nomEvaluation"],
                    $donneesNote["utilisateur_id"],
                    $donneesNote["id"]
                );
                $tabNotes[] = $n;
            }
        }
        return $tabNotes;
    }

    //Rend les notes avec un tri
    public function rendNotesTriees(int $id, string $sortValue): array
    {
        //Attribution de la bonne requête selon la valeur de tri
        if ($sortValue === 'date') {
            $sql = "SELECT * From notes WHERE utilisateur_id =:id ORDER BY id DESC";
        } else if ($sortValue === 'result') {
            $sql = "SELECT * From notes WHERE utilisateur_id = :id ORDER BY note DESC";
        } else {
            $sql = "SELECT * From notes WHERE utilisateur_id = :id ORDER BY  module, nomCours ASC";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $donnees = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $tabNotes = [];
        if ($donnees) {
            foreach ($donnees as $donneesNote) {
                $n = new Notes(
                    $donneesNote["module"],
                    $donneesNote["coeficient"],
                    $donneesNote["nomCours"],
                    $donneesNote["note"],
                    $donneesNote["nomEvaluation"],
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
                    $_SESSION['ecole'] = $result['ecole'];
                    $_SESSION['filiere'] = $result['filiere'];
                    $_SESSION['classe'] = $result['classe'];
                    $_SESSION['langue'] = $result['langue'];
                    $_SESSION['id'] = $result['id'];
                    header("Location: profil.php");
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

    public function rendTotalNbrUtilisateur()
    {
        // Check if table exists
        $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name='utilisateurs'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        if ($stmt->fetch()) {
            $sql = "SELECT COUNT(*) as total FROM utilisateurs";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetch(\PDO::FETCH_ASSOC); // Récupère une seule ligne
            return $data ? (int) $data['total'] : 0; // Retourne le total ou 0 si aucune donnée
        }
        return 0;
    }

    public function rendMoyenneAll()
    {
        // Check if table exists
        $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name='notes'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        if ($stmt->fetch()) {
            $sql = "SELECT AVG(note) AS moyenne FROM notes";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetch(\PDO::FETCH_ASSOC); // Récupère une seule ligne
            return $data ? (float) $data['moyenne'] : 0.0; // Retourne la moyenne ou 0.0 si aucune donnée
        }
        return 0.0;
    }

    public function rendTauxAll()
    {
        // Check if table exists
        $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name='notes'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        if ($stmt->fetch()) {
            // Sélectionne le nombre de notes réussies
            $sql1 = "SELECT COUNT(*) as notesReussies FROM notes WHERE note >= 4";
            $stmt1 = $this->db->prepare($sql1);
            $stmt1->execute();
            $nbrNotesReussies = $stmt1->fetch(\PDO::FETCH_ASSOC);
            // On convertit la valeur en entier ou on met 0 si aucun résultat
            $nbrNotesReussies = $nbrNotesReussies ? (int) $nbrNotesReussies['notesReussies'] : 0;

            // Sélectionne le total de nombre de notes
            $sql2 = "SELECT COUNT(*) as total FROM notes";
            $stmt2 = $this->db->prepare($sql2);
            $stmt2->execute();
            $nbrNotes = $stmt2->fetch(\PDO::FETCH_ASSOC);
            // On convertit la valeur en entier ou on met 0 si aucun résultat
            $nbrNotes = $nbrNotes ? (int) $nbrNotes['total'] : 0;

            // Calcul du pourcentage, on évite la division par zéro
            $pourcentage = ($nbrNotes > 0) ? ($nbrNotesReussies / $nbrNotes) * 100 : 0;

            return $pourcentage;
        }
        return 0;
    }

    public function rendMoyenneUtilisateur($id)
    {
        // Check if table exists
        $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name='notes'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        if ($stmt->fetch()) {
            $sql = "SELECT AVG(note) AS moyenne FROM notes WHERE utilisateur_id = :id ;";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam('id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(\PDO::FETCH_ASSOC); // Récupère une seule ligne
            return $data ? (float) $data['moyenne'] : 0.0; // Retourne la moyenne ou 0.0 si aucune donnée
        }
        return 0.0;
    }

    public function rendTauxUtilisateur($id)
    {
        // Check if table exists
        $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name='notes'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        if ($stmt->fetch()) {
            $sql1 = "SELECT COUNT(*) as notesReussies FROM notes WHERE note >= 4 AND utilisateur_id =:id";
            $stmt1 = $this->db->prepare($sql1);
            $stmt1->bindParam('id', $id, \PDO::PARAM_INT);
            $stmt1->execute();
            $nbrNotesReussies = $stmt1->fetch(\PDO::FETCH_ASSOC);
            // On convertit la valeur en entier ou on met 0 si aucun résultat
            $nbrNotesReussies = $nbrNotesReussies ? (int) $nbrNotesReussies['notesReussies'] : 0;

            // Sélectionne le total de nombre de notes
            $sql2 = "SELECT COUNT(*) as total FROM notes WHERE utilisateur_id =:id";
            $stmt2 = $this->db->prepare($sql2);
            $stmt2->bindParam('id', $id, \PDO::PARAM_INT);
            $stmt2->execute();
            $nbrNotes = $stmt2->fetch(\PDO::FETCH_ASSOC);
            // On convertit la valeur en entier ou on met 0 si aucun résultat
            $nbrNotes = $nbrNotes ? (int) $nbrNotes['total'] : 0;

            // Calcul du pourcentage, on évite la division par zéro
            $pourcentage = ($nbrNotes > 0) ? ($nbrNotesReussies / $nbrNotes) * 100 : 0;

            return $pourcentage;
        }
        return 0;
    }

    //Rend l'id de la note selon le nom du cours, le module, le nom de l'évaluation et l'id de l'utilisateur
    public function rendIdNote(string $nomCours, string $module, string $nomEvaluation, int $idUtilisateur): int
    {
        $sql = "SELECT id FROM notes WHERE nomCours = :nomCours AND module = :module AND nomEvaluation = :nomEvaluation AND utilisateur_id = :idUtilisateur";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('nomCours', $nomCours, \PDO::PARAM_STR);
        $stmt->bindParam('module', $module, \PDO::PARAM_STR);
        $stmt->bindParam('nomEvaluation', $nomEvaluation, \PDO::PARAM_STR);
        $stmt->bindParam('idUtilisateur', $idUtilisateur, \PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $data ? (int) $data['id'] : 0;
    }
}
