<?php

namespace App\Library;

use Google\Client;
use Google\Service\Oauth2 as ServiceOauth2;
use Google\Service\Oauth2\Userinfo;

class GoogleClient {
    private Userinfo $data;
    private Client $client;

    public function __construct() {
        $this->client = new Client();
    }

    public function init() {
        $guzzleClient = new \GuzzleHttp\Client(['curl' => [CURLOPT_SSL_VERIFYPEER => false, ]]);
        $this->client->setHttpClient($guzzleClient);
        $this->client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $this->client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $this->client->setRedirectUri('http://85.31.63.241:8084/login');
        $this->client->addScope('email');
        $this->client->addScope('profile');
    }

    public function authenticated() {
        // if (isset($_GET['code'])) {
        //     $token = $this->client->fetchAccessTokenWithAuthCode($_GET['code']);
        //     $this->client->setAccessToken($token['access_token']);
        //     $google_service = new ServiceOauth2($this->client);
        //     $this->data = $google_service->userinfo->get();

        //     return true;
        // }
        // return false;
        header('Location: http://127.0.0.1:8000/home');
        exit();
    }

    public function getData() {
        return $this->data;
    }

    public function generateLink() {
        return $this->client->createAuthUrl();
    }
}