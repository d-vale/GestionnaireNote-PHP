<?php

require_once './lib/vendor/autoload.php';

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

$transport = Transport::fromDsn('smtp://localhost:1025');
$mailer = new Mailer($transport);
$email = (new Email())
    ->from('heig@gestionnaire.ch')
    ->to($utilisateur->rendEmail())
    ->subject('Vérification de votre compte')
    ->html('
    <div style="font-family: Arial, sans-serif; text-align: center;">
        <h1 style="color: #333;">Merci de vérifier votre compte</h1>
        <p style="color: #555;">
            <a href="http://localhost/ProgServ2/GestionnaireNote-PHP/verify.php?token='
        . $utilisateur->rendToken()
        . '" style="color: #0066cc; text-decoration: none;">
                Cliquez ici pour vérifier votre compte
            </a>
        </p>
    </div>
');
$result = $mailer->send($email);

//Verification de l'envoi du mail
if ($result == null) {
    echo '<p class="mt-3 text-center">Un mail de vérification a été envoyé</p>';
} else {
    echo '<p style="color: red" class="mt-3 text-center">Le mail de vérification ne peux pas être envoyé</p>';
}
