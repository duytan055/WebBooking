<?php

require_once __DIR__ . '/../vendor/autoload.php';

$client = new Google_Client();

$client->setClientId('115926101504-n9u9l2isn07jgg5qditr84m9fie115rm.apps.googleusercontent.com');
$client->setClientSecret('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');

$client->setRedirectUri(
    'http://localhost/WebBooking/API/login-google.php'
);

$client->addScope('email');
$client->addScope('profile');
