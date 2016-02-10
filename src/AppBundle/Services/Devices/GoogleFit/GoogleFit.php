<?php
namespace AppBundle\Services\Devices\GoogleFit;
use \Google_Client;
use \Google_Service_Fitness;

class GoogleFit
{
    private $consumer_key;
    private $consumer_secret;
    private $callback_url;
    private $google;
    private $service;
    private $access_token;
    private $refresh_token;

    function __construct($consumer_key = null, $consumer_secret = null, $callback_url = null, $access_token_key = null, $access_token_secret = null, $user_id = null)
    {
        $this->consumer_key = "388262243880-84r5l52341g302ab23vqe8gu3hrivbt5.apps.googleusercontent.com";
        $this->consumer_secret = "OMtqK2OwTXuWvD_s2RBQ0UEU";
        $this->callback_url = "https://ukandoit.fr/google/token";
        $this->google = new \Google_Client();
        $this->google->setApplicationName("Ukando'it");
        $this->google->setClientId($this->consumer_key);
        $this->google->setClientSecret($this->consumer_secret);
        $this->google->addScope(Google_Service_Fitness::FITNESS_ACTIVITY_READ); //Google_Service_Drive::DRIVE_METADATA_READONLY
        $this->google->setRedirectUri('https://' . $_SERVER['HTTP_HOST'] . '/google/token');
    }

    public function connection(){
        $auth_url = $this->google ->createAuthUrl();
        $url = filter_var($auth_url, FILTER_SANITIZE_URL);
        return $url;
    }

    public function getToken(){
        if (isset($_GET['error'])){
            return false;
        }
        else{
            $this->google->authenticate($_GET['code']);
            $token = $this->google->getAccessToken();
            $this->setAccessToken($token["access_token"]);
            $this->setRefreshToken($token["refresh_token"]); // $this->google->getRefreshToken();
        }
    }

    public function authenticate($possessedDevice){
        $this->google->setAccessToken($possessedDevice->getAccessTokenGoogle());
        if ($this->google->isAccessTokenExpired()){
            $this->google->refreshToken($possessedDevice->getRefreshTokenGoogle());
            $token = $this->google->getAccessToken();
            $this->setAccessToken($token["access_token"]);
            $this->setRefreshToken($token["refresh_token"]);
            $possessedDevice->setAccessTokenGoogle($this->getAccessToken());
            $possessedDevice->setRefreshTokenGoogle($this->getRefreshToken());
            return true;
        }
        return false;
    }

    public function setConsumerKey($consumer_key)
    {
        $this->consumer_key = $consumer_key;
    }

    public function setConsumerSecret($consumer_secret)
    {
        $this->consumer_secret = $consumer_secret;
    }

    public function setCallbackUrl($callback_url)
    {
        $this->callback_url = $callback_url;
    }

    public function setGoogle($Google)
    {
        $this->google = $Google;
    }

    public function setAccessToken($access_token_key)
    {
        $this->access_token = $access_token_key;
    }

    public function setRefreshToken($refresh_token)
    {
        $this->refresh_token = $refresh_token;
    }

    public function getConsumerKey()
    {
        return $this->getConsumerKey();
    }

    public function getConsumerSecret()
    {
        return $this->consumer_secret();
    }

    public function getCallbackUrl()
    {
        return $this->callback_url;
    }

    public function getGoogle()
    {
        return $this->google;
    }

    public function getAccessToken()
    {
        return $this->access_token;
    }

    public function getRefreshToken()
    {
        return $this->refresh_token;
    }
}






