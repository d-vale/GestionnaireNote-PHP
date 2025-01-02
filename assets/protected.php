<?php
//Vérifier si l'utilisateur est connecté et a accès à la page
if (!isset($_SESSION['email'])) {
    echo '
    <br>
    <h1 class="text-5xl mb-6 font-bold">Votre gestionnaire de notes</h1>
    <p class="text-xl mb-12">Vous n&#39;avez pas accès à cette page si vous n&#39;êtes pas connecté.</p>
    <div class="flex flex-col md:flex-row gap-4">
    <a href="./inscription.php"
        class="mt-4 mb-4 block py-2 px-6 rounded-full hover:bg-black bg-blue-900 text-md text-white font-bold">S&#39;inscrire</a>
    <a href="./connection.php"
        class="text-black py-4 px-12 rounded-full hover:text-blue-900">Déjà inscrit ? Se
        connecter</a>
</div>';
    exit;
}
