<?php
//Vérifier si l'utilisateur est connecté et a accès à la page
session_start();
require_once("./config/autoload.php");
require_once('lang' . DIRECTORY_SEPARATOR . 'lang_func.php');

use management\db\DbManager;
use management\db\Notes;

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="styles/output.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/assets/logo.svg">
    <title><?php echo t('title')?></title>
</head>

<body class="flex flex-col min-h-screen divide-gray-100 ">

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

                        if (!isset($_SESSION['email'])) {
                            echo '';
                        } else {
                            echo '<div class="flex space-x-4">
                            <a href="./profil.php" class="font-medium rounded-md px-3 py-2 text-sm hover:text-blue-900">';
                            echo t('myProfile');
                            echo'</a>
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
                        class="rounded-md px-3 py-2 text-sm font-medium text-black hover:text-blue-900">';
                       echo t('login');
                        echo '</a>
                    <a href="./inscription.php"
                        class="rounded-full -md bg-blue-800 hover:bg-blue-900 px-3 py-2 text-sm font-medium text-white">';
                        echo t('signup');
                        echo '</a>
                </div>';
                } else {
                    echo '<div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                    <a href="logout.php"
                        class="rounded-md px-3 py-2 text-sm font-medium text-black hover:text-blue-900">';
                        echo t('logout');
                        echo '</a>
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
        <?php
            $dbManager = new DbManager();

            if(isset($_GET['token'])){
                $dbManager->verifyToken($_GET['token']);
            }
            ?>
    </main>

    <!-- Footer -->
    <footer class=" bg-gradient-to-r from-gray-100 via-[#bce1ff] to-gray-100 bottom-0">
        <div class="max-w-screen-xl px-4 py-8 mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <!-- Première colonne -->
                <div>
                    <a href="./">
                        <img src="./assets/logo2.svg" class="mr-5 h-6 sm:h-9" alt="Projet PHP" />
                    </a>
                    <p class="max-w-xs mt-4 text-sm text-gray-600">
                    <?php echo t('aboutProject')?>
                    </p>
                    <div class="flex mt-8 space-x-6 text-gray-600">
                        <a class="hover:opacity-75 hover:text-blue-800"
                            href="https://github.com/d-vale/GestionnaireNote-PHP">
                            <span class="sr-only"> GitHub </span>
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Deuxième colonne : Langues -->
                <div class="grid grid-cols-1 gap-8 lg:col-span-2 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <p class="font-medium">
                        <?php echo t('siteLanguages')?>
                        </p>
                        <nav class="flex flex-col mt-4 space-y-2 text-sm text-gray-500">
                            <?php 
                            echo '<a href="' . $_SERVER['PHP_SELF'] . '?lang=fr" class="hover:opacity-75 hover:text-blue-800">';
                            echo t('french');
                            echo '</a>';
                            echo '<a href="' . $_SERVER['PHP_SELF'] . '?lang=en" class="hover:opacity-75 hover:text-blue-800">';
                            echo t('english');
                            echo '</a>';
                            ?>
                        </nav>
                    </div>
                </div>
            </div>

            <p class="mt-8 text-xs text-gray-800">
                © 2024 ProgServ 2 COMEM HEIG-VD
            </p>
        </div>
    </footer>
</body>

</html>