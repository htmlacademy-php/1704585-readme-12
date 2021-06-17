<?php
require_once('vendor/autoload.php');

$transport = new Swift_SmtpTransport('smtp.mailtrap.io', 2525);
$transport->setUsername('3fca14ab7ad498');
$transport->setPassword('9c2208d64a0524');

$mailer = new Swift_Mailer($transport);

?>