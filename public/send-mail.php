<?php

require_once __DIR__.'/../vendor/autoload.php';

// Create the Transport
$transport = (new Swift_SmtpTransport('smtp.googlemail.com', 465, 'ssl'))
  ->setUsername('simon.ponitzki@gmail.com')
  ->setPassword('') // Mot de passe a mettre + activation des less secured app dans compte google et ca fonctionne
;

// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);

// On demarre une session pour avoir $_SESSION
session_start();

// Gestion des données
$content = [
    'email_form' => '',
    'object' => '',
    'message' => '',
];

foreach ($content as $key => $value) {
    if (isset($_SESSION[$key])) {
        $content[$key] = $_SESSION[$key];
    }
}


// Create a message
$message = (new Swift_Message("{$content['object']}"))
    ->setFrom(['simon.ponitzki@gmail.com' => 'Ponitzki Simon'])
    ->setTo(['simon.ponitzki@gmail.com' => 'Simon']) //nom optionnel
    ->setBody("{$content['message']}/nSent by {$content['email_form']}")
    ;

// Send the message
$result = $mailer->send($message);

$url = 'contact.php';
header("location: {$url}", true, 301);
exit();