<?php

require_once './lib/vendor/autoload.php';

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

$transport = Transport::fromDsn('smtp://localhost:1025');
$mailer = new Mailer($transport);
$email = (new Email())
    ->from('heig@gesionaire.ch')
    ->to($utilisateur->rendEmail())
    ->subject('Vérification de votre compte')
    ->html('<h1>Merci de vérifier votre compte</h1>
            <a href="http://localhost/ProgServ2/GestionnaireNote-PHP/verify.php?token='
            . $utilisateur->rendToken()
            . '">Cliquez ici pour vérifier votre compte</a>');
$result = $mailer->send($email);

//Verification de l'envoi du mail
if ($result == null) {
    echo '<p class="mt-3 text-center">Un mail de vérification a été envoyé</p>';
} else {
    echo '<p style="color: red" class="mt-3 text-center">Le mail de vérification ne peux pas être envoyé</p>';
}
