<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Google\Auth\ApplicationDefaultCredentials;
use Google\Auth\Credentials\ServiceAccountCredentials;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$path_file = __DIR__.'/'.$_ENV['SERVICE_ACCOUNT_TOKEN'];
putenv('GOOGLE_APPLICATION_CREDENTIALS='.$path_file);
$scopes = ['https://www.googleapis.com/auth/cloud-platform'];
$credentials = ApplicationDefaultCredentials::getCredentials($scopes);
$accessToken = $credentials->fetchAuthToken()['access_token'];
echo $accessToken;
