<?php
/**
 * envoyer-message.php
 * Traite le formulaire de contact et envoie l'email directement depuis le serveur.
 * Aucun service tiers, aucun compte, aucune vérification : ça fonctionne dès que
 * ce fichier est hébergé sur un serveur PHP avec la fonction mail() active
 * (cas standard chez la quasi-totalité des hébergeurs web, y compris celui
 * qui faisait tourner l'ancien site WordPress).
 */

$destinataire = "ashsbasket@gmail.com";

function champ($nom) {
    return isset($_POST[$nom]) ? trim($_POST[$nom]) : '';
}

// N'accepte que les envois du formulaire (POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.html');
    exit;
}

// Piège à robots : champ invisible qui doit rester vide pour un humain
if (champ('_honey') !== '') {
    // On fait semblant que tout s'est bien passé, sans envoyer de mail
    header('Location: merci.html');
    exit;
}

$nom     = champ('Nom');
$email   = champ('Email');
$sujet   = champ('Sujet');
$message = champ('Message');

if ($nom === '' || $email === '' || $sujet === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: contact.html?erreur=1');
    exit;
}

// Sécurité : évite l'injection d'en-têtes email via des retours à la ligne
$nom_propre   = str_replace(["\r", "\n"], ' ', $nom);
$email_propre = str_replace(["\r", "\n"], ' ', $email);
$sujet_propre = str_replace(["\r", "\n"], ' ', $sujet);

$objet = "[Site BCH] " . $sujet_propre;

$corps  = "Nouveau message reçu depuis le formulaire de contact du site du Basket Club Hoenheim.\n\n";
$corps .= "Nom et prénom : " . $nom_propre . "\n";
$corps .= "Email         : " . $email_propre . "\n";
$corps .= "Sujet         : " . $sujet_propre . "\n\n";
$corps .= "Message :\n" . $message . "\n";

$domaine = isset($_SERVER['HTTP_HOST']) ? preg_replace('/[^a-zA-Z0-9\.\-]/', '', $_SERVER['HTTP_HOST']) : 'baskethoenheim.fr';

$entetes  = "From: Site du BCH <no-reply@" . $domaine . ">\r\n";
$entetes .= "Reply-To: " . $nom_propre . " <" . $email_propre . ">\r\n";
$entetes .= "Content-Type: text/plain; charset=UTF-8\r\n";

$envoye = @mail($destinataire, $objet, $corps, $entetes);

if ($envoye) {
    header('Location: merci.html');
} else {
    header('Location: contact.html?erreur=1');
}
exit;
