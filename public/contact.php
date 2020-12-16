<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;

// activation du système d'autoloading de Composer
require __DIR__.'/../vendor/autoload.php';

// instanciation du chargeur de templates
$loader = new FilesystemLoader(__DIR__.'/../templates');

// instanciation du moteur de template
$twig = new Environment($loader, [
        // activation du mode debug
        'debug' => true,
        // activation du mode de variables strictes
        'strict_variables' => true,
]);

// chargement de l'extension DebugExtension
$twig->addExtension(new DebugExtension());
session_start();

// traitement des données

$data = [
    'email' => '',
    'object' => '',
    'message' => '',
];
$errors = [];

if ($_POST) {
    foreach ($data as $key => $value) {
        if (isset($_POST[$key])) {
            $data[$key] = $_POST[$key];
        }
    }
    
    if (empty($data['email'])){
        $errors['email'] = 'Veuillez renseigner un email';
    } elseif (strlen($data['email']) >= 190){
        $errors['email'] = 'L\'email est trop long, merci de le raccourcir (max 190 caractères)';    
    } elseif (filter_var($data['email'], FILTER_VALIDATE_EMAIL) == false) {
        $errors['email'] = 'Veuillez renseigner un email valide';
    }

    if (empty($data['object'])){
        $errors['object'] = 'Veuillez renseigner un object';
    } elseif (strlen($data['object']) >= 190){
        $errors['object'] = 'L\'objet du message est trop long, merci de le raccourcir (max 190 caractères)';
    } elseif (preg_match('/<[^>]*>/', $data['email'])) {
        $errors['email'] = 'Les balises html et les caractères \'<\' et \'>\' sont interdites';
    }

    if (empty($data['message'])){
        $errors['message'] = 'Veuillez renseigner un message';
    } elseif (strlen($data['message']) >= 1000){
        $errors['message'] = 'Le message est trop long, merci de le raccourcir (max 1000 caractères)';
    } elseif (preg_match('/<[^>]*>/', $data['message'])) {
        $errors['message'] = 'Les balises html et les caractères \'<\' et \'>\' sont interdites';
    }
/*  Pas securisé !! (DDOS)
    $data['email'] = $_POST['email'];
    $data['object'] = $_POST['object'];
    $data['message'] = $_POST['message'];
*/
    if (empty($errors)){
        $_SESSION['email_form'] = $data['email'];
        $_SESSION['object'] = $data['object'];
        $_SESSION['message'] = $data['message'];

        $url = 'send-mail.php';
        header("location: {$url}", true, 301);
        exit();
    }
}

// affichage du rendu d'un template
echo $twig->render('contact.html.twig', [
    // transmission de données au template
    'errors' => $errors,
    'data' => $data,
]);


// '/<[^>]*>/' regex anti html
// les balises html sont interdites et les > < sont interdits