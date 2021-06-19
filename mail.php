<?php
require_once('vendor/autoload.php');

$transport = new Swift_SmtpTransport('smtp.mailtrap.io', 2525);
$transport->setUsername($transport_username);
$transport->setPassword($transport_password);

$mailer = new Swift_Mailer($transport);

?>