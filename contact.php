<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// activation du système d'autoloading de Composer
require __DIR__.'/vendor/autoload.php';

// instanciation du chargeur de templates
$loader = new FilesystemLoader(__DIR__.'/templates');

// instanciation du moteur de template
$twig = new Environment($loader, [
        // activation du mode debug
        'debug' => true,
        // activation du mode de variables strictes
        'strict_variables' => true,
]);

// traitement des données
dump($_POST);

$data = [];
dump($data);

$errors = [];

if ($_POST) {
    $greeting = 'Hello Twig!';
    
    if (empty($_POST['email'])){
        $errors['email'] = 'Veuillez renseigner un email';
    } elseif (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
        $errors['email'] = 'Veuillez renseigner un email valide';
    } elseif (preg_match('/<[^>]*>/', $_POST['email'])) {
        $errors['email'] = 'Les balises html et les caractères \'<\' et \'>\' sont interdites';
    }

    if (empty($_POST['object'])){
        $errors['object'] = 'Veuillez renseigner un object';
    } elseif (strlen($_POST['object']) >= 190){
        $errors['object'] = 'L\'objet du message est trop long, merci de le raccourcir (max 190 caractères)';
    } elseif (preg_match('/<[^>]*>/', $_POST['email'])) {
        $errors['email'] = 'Les balises html et les caractères \'<\' et \'>\' sont interdites';
    }

    if (empty($_POST['message'])){
        $errors['message'] = 'Veuillez renseigner un message';
    } elseif (strlen($_POST['message']) >= 1000){
        $errors['message'] = 'Le message est trop long, merci de le raccourcir (max 1000 caractères)';
    } elseif (preg_match('/<[^>]*>/', $_POST['message'])) {
        $errors['message'] = 'Les balises html et les caractères \'<\' et \'>\' sont interdites';
    }

    if (empty($errors['email'])) {  
        $data['email'] = $_POST['email'];
    }
    if (empty($errors['object'])) {  
        $data['object'] = $_POST['object'];
    }
    if (empty($errors['message'])) {  
        $data['message'] = $_POST['message'];
    }
}

echo empty($errors);

// affichage du rendu d'un template
echo $twig->render('contact.html.twig', [
    // transmission de données au template
    'errors' => $errors,
    'data' => $data,
]);


// '/<[^>]*>/' regex anti html
// les balises html sont interdites et les > < sont interdits