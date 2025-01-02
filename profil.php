<!Doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="styles/output.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="/assets/logo.svg" />
    <title>Gestionnaire de notes</title>
</head>

<body class="flex min-h-screen flex-col divide-y divide-gray-100">
    <!-- Header -->
    <header class="bg-gradient-to-r from-gray-100 via-[#bce1ff] to-gray-100">
        <nav class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
            <div class="relative flex h-16 items-center justify-between">

                <!-- Version mobile -->
                <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                    <button id="menu-btn" type="button"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                </div>

                <!-- Logo et liens -->
                <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                    <div class="hidden sm:flex shrink-0 items-center">
                        <a href="./">
                            <img class="h-8 w-auto" src="./assets/logo.svg" alt="Logo de notre projet">
                        </a>
                    </div>
                    <div class="hidden sm:ml-6 sm:block">
                        <?php
                        //Vérifier si l'utilisateur est connecté et a accès à la page
                        session_start();

                        if (!isset($_SESSION['email'])) {
                            echo '';
                        } else {
                            echo '<div class="flex space-x-4">
                            <a href="./profil.php" class="font-medium rounded-md px-3 py-2 text-sm hover:text-blue-900">Mon Profil</a>
                        </div>';
                        }
                        ?>
                    </div>
                </div>


                <!-- Boutons à droite -->
                <?php
                //Vérifier si l'utilisateur est connecté et a accès à la page
                if (!isset($_SESSION['email'])) {
                    echo '<div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                    <a href="./connection.php"
                        class="rounded-md px-3 py-2 text-sm font-medium text-black hover:text-blue-900">Se connecter</a>
                    <a href="./inscription.php"
                        class="rounded-full -md bg-blue-800 hover:bg-blue-900 px-3 py-2 text-sm font-medium text-white">S&#39;inscrire</a>
                </div>';
                } else {
                    echo '<div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                    <a href="logout.php"
                        class="rounded-md px-3 py-2 text-sm font-medium text-black hover:text-blue-900">Se
                        déconnecter</a>
                </div>';
                }
                ?>
            </div>
        </nav>

        <!-- Menu Mobile  -->
        <?php
        if (!isset($_SESSION['email'])) {
            echo '';
        } else {
            echo '<div id="mobile-menu" class="sm:hidden hidden">
            <div class="space-y-1 px-2 pt-2 pb-3">
                <a href="./profil.php" class="font-medium rounded-md px-3 py-2 text-sm hover:text-blue-900">Mon Profil</a>
            </div>
        </div>';
        }
        ?>
    </header>

    <main
        class="items-center space-y-8 flex flex-grow flex-col bg-gradient-to-r from-gray-100 via-[#bce1ff] to-gray-100">
        <!-- Protéction pour la page profil si l'utilisateur est login -->
        <?php
        require_once 'assets/protected.php';

        ?>
        <div class="mt-8 pt-8 bg-gray-100 rounded-md drop-shadow-lg">
            <h1 class="text-4xl font-bold text-center ">Votre Profil</h1>
            <div id="profil" class="p-8 flex flex-col items-center gap-8 m-2">
                <div class="profilBlock grid grid-cols-1 gap-6 text-center items-center">
                    <a href="./settings.php"
                        class="underline row-span-1 rounded-md text-sm font-medium  sm:text-black sm:hover:text-blue-900"
                        aria-label="Modifier le profil">Paramètres</a>
                    <div class="data row-span-3">
                        <div class="name text-lg font-bold mb-4 text-blue-900"><?php echo $_SESSION['prenom'] . " " . $_SESSION['nom'] ?></div>
                        <div class="school">École : <?php echo $_SESSION['ecole'] ?></div>
                        <div class="sector">Filière : <?php echo $_SESSION['filiere'] ?></div>
                        <div class="class">Classe : <?php echo $_SESSION['classe'] ?></div>
                        <div class="mail">Adresse Mail : <?php echo $_SESSION['email'] ?></div>
                    </div>
                </div>

                <div id="averages" class="flex flex-row gap-8 text-center">
                    <div class="averageBlock ">
                        <div class="averageTitle text-xl font-bold text-blue-900">Moyenne Générale</div>
                        <div class="averageDisplay font-bold text-xl mt-2">5.3</div>
                    </div>
                    <div class="averageBlock">
                        <div class="averageTitle text-xl font-bold text-blue-900">Taux de Réussite Global</div>
                        <div class="averageDisplay font-bold text-xl mt-2 ">89%</div>
                    </div>
                </div>
            </div>

            <!--Formulaire pour remplir de nouvelles notes-->
            <div class="flex flex-col space-y-12 items-center mt-4" id="gradesForm">
                <h2 class="text-xl font-bold text-center">Ajouter un résultat</h1>
                    <div class="p-2">
                        <form class="" method="POST" action="">
                            <div class="grid md:grid-cols-2 gap-4 font-semibold grid-cols-1">
                                <label for="module" class="text-center">Module</label>
                                <input type="text" name="module"
                                    class="shadow-lg rounded-md border border-[#e0e0e0] bg-white text-base  outline-none focus:border-[#6A64F1] focus:shadow-md">
                                <label for="ratio" class="text-center">Coefficient</label>
                                <input type="text" name="ratio"
                                    class="shadow-lg rounded-md border border-[#e0e0e0] bg-white text-base  outline-none focus:border-[#6A64F1] focus:shadow-md">
                                <label for="lesson" class="text-center">Cours</label>
                                <input type="text" name="lesson"
                                    class="shadow-lg rounded-md border border-[#e0e0e0] bg-white text-base  outline-none focus:border-[#6A64F1] focus:shadow-md">
                                <label for="result" class="text-center">Résultat</label>
                                <input type="text" name="result"
                                    class="shadow-lg rounded-md border border-[#e0e0e0] bg-white text-base  outline-none focus:border-[#6A64F1] focus:shadow-md">
                                <label for="testName" class="text-center">Nom de l'évaluation</label>
                                <input type="text" name="testName"
                                    class="shadow-lg rounded-md border border-[#e0e0e0] bg-white text-base  outline-none focus:border-[#6A64F1] focus:shadow-md">
                            </div>
                            <div class="flex flex-col items-center pt-4">
                                <button
                                    type="submit" name="submit" class="mt-4 mb-4 block py-2 px-6 rounded-full hover:bg-black bg-blue-900 text-md text-white font-bold">Ajouter</button>
                            </div>
                        </form>
                    </div>
                    <?php
                    //Ajouter une note
                    require_once("./config/autoload.php");

                    use management\db\DbManager;
                    use management\db\Notes;

                    if (isset($_POST['submit'])) {
                        $module = $_POST['module'];
                        $nomCours = $_POST['lesson'];
                        $coeficient = $_POST['ratio'];
                        $nomEvaluation = $_POST['testName'];
                        $note = $_POST['result'];
                        $utilisateur_id = $_SESSION['id'];

                        $dbManager = new DbManager();
                        $newNote = new Notes($module, $coeficient, $nomCours, $note, $nomEvaluation, $utilisateur_id);
                        
                        try {
                            $dbManager->creeTableNotes();
                            $dbManager->ajouteNotes($newNote);
                        } catch (\PDOException $e) {
                            echo '<div class="text-center text-red-500">Erreur lors de l\'ajout de la note</div>';
                        }
                    }
                    ?>
            </div>
        </div>
        <!--Tableau qui affiche les notes-->

        <div class="" id="gradesArray">
            <div class="flex items-center flex-center flex-col rounded-md">
                <table
                    class="w-full text-md text-left rtl:text-right text-black dark:text-gray-400 m-9 border border-gray-200 shadow-lg">
                    <thead class="bg-blue-900 text-white">
                        <tr>
                            <th class="px-6 py-3 text-center">Supprimer</th>
                            <th class="px-6 py-3 text-center">Cours</th>
                            <th class="px-6 py-3 text-center">Module</th>
                            <th class="px-6 py-3 text-center">Nom de l'évaluation</th>
                            <th class="px-6 py-3 text-center">Coefficient</th>
                            <th class="px-6 py-3 text-center">Résultat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-black">
                    <?php
                    //Fonction pour afficher les notes de cette utilisateurs
                    require_once("./config/autoload.php");
                    $dbManager = new DbManager();

                    // Crée la table notes si elle n'existe pas
                    $dbManager->creeTableNotes();

                    // Supprimer les anciennes notes affichées
                    echo '<tbody class="divide-y divide-gray-200 text-black">';
                    
                    // Vérifie si la table notes existe et si l'utilisateur a des notes
                    $notes = $dbManager->rendNotes($_SESSION['id']);
                    if (!empty($notes)) {
                        foreach ($notes as $note) {
                            echo '<tr>
                                    <td class="px-6 py-4 text-center">
                                        <button class="text-red-700 ring-red-700 hover:text-white hover:bg-red-700 ring-2 font-bold rounded-lg text-sm px-4 py-2 text-center">X</button>
                                    </td>
                                    <td class="px-6 py-4 text-center">' . $note->rendNomCours() . '</td>
                                    <td class="px-6 py-4 text-center">' . $note->rendModule() . '</td>
                                    <td class="px-6 py-4 text-center">' . $note->rendNomEvaluation() . '</td>
                                    <td class="px-6 py-4 text-center">' . $note->rendCoeficient() . '</td>
                                    <td class="px-6 py-4 text-center">' . $note->rendNote() . '</td>
                                </tr>';
                        }
                    } else {
                        echo '<tr><td colspan="6" class="px-6 py-4 text-center">Aucune note n\'a été ajoutée</td></tr>';
                    }
                    echo '</tbody>';
                    ?>
                </table>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bottom-0 bg-gradient-to-r from-gray-100 via-[#bce1ff] to-gray-100">
        <div class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <!-- Première colonne -->
                <div>
                    <a href="./">
                        <img src="./assets/logo2.svg" class="mr-5 h-6 sm:h-9" alt="Projet PHP" />
                    </a>
                    <p class="mt-4 max-w-xs text-sm text-gray-600">Ce site est un projet réalisé dans le cadre d'un
                        cours de PHP à la HEIG-VD.</p>
                    <div class="mt-8 flex space-x-6 text-gray-600">
                        <a class="hover:text-blue-800 hover:opacity-75"
                            href="https://github.com/d-vale/GestionnaireNote-PHP">
                            <span class="sr-only"> GitHub </span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Deuxième colonne : Langues -->
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:col-span-2 lg:grid-cols-4">
                    <div>
                        <p class="font-medium">Langues du site</p>
                        <nav class="mt-4 flex flex-col space-y-2 text-sm text-gray-500">
                            <a href="#" class="hover:text-blue-800 hover:opacity-75">Français</a>
                            <a href="#" class="hover:text-blue-800 hover:opacity-75">Anglais</a>
                        </nav>
                    </div>
                </div>
            </div>

            <p class="mt-8 text-xs text-gray-800">© 2024 ProgServ 2 COMEM HEIG-VD</p>
        </div>
    </footer>
</body>

</html>