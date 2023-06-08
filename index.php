<?php

require_once 'vendor/autoload.php';

use Telethon\Telegram\Client;
use Telethon\Session;

$apiToken = '6022228026:AAH4MwFk3sNno832jZ9SorCsVbHBheK2c-0'; // Replace with your own API token
$sessionFile = 'session'; // Path to your session file

$session = new Session();
$session->load($sessionFile);

$apiId = 'YOUR_API_ID'; // Replace with your own API ID
$apiHash = 'YOUR_API_HASH'; // Replace with your own API hash

$client = new Client($session, $apiId, $apiHash);
$client->start();

// Your code for checking usernames goes here

$client->run();