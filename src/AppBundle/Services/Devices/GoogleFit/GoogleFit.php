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
    public $service; // A METTRE EN PRIVATE
    private $access_token;
    private $refresh_token;

    function __construct($consumer_key = null, $consumer_secret = null, $callback_url = null, $access_token_key = null, $access_token_secret = null, $user_id = null)
    {
        $this->consumer_key = "388262243880-84r5l52341g302ab23vqe8gu3hrivbt5.apps.googleusercontent.com";
        $this->consumer_secret = "OMtqK2OwTXuWvD_s2RBQ0UEU";
        $this->callback_url = "http://localhost:443/web/app_dev.php/google/token";
        $this->google = new \Google_Client();
        $this->google->setAccessType('offline');
        $this->google->setApprovalPrompt('force'); // génère le refresh token dans l'URL
        $this->google->setApplicationName("Ukando'it");
        $this->google->setDeveloperKey("AIzaSyBACQrAapAYL7UTwufO59Z0QOczpptYyg0");
        $this->google->setClientId($this->consumer_key);
        $this->google->setClientSecret($this->consumer_secret);
        $this->google->addScope(Google_Service_Fitness::FITNESS_ACTIVITY_READ); //Google_Service_Drive::DRIVE_METADATA_READONLY
        $this->google->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/web/app_dev.php/google/token');
        //$this->service = new Google_Service_Fitness($this->google);
    }

    public function connection(){
        $auth_url = $this->google ->createAuthUrl();
        $url = filter_var($auth_url, FILTER_SANITIZE_URL);
        return $url;
    }

    public function getToken(){
        if (isset($_GET['code'])){
            $this->google->authenticate($_GET['code']);
            $token = $this->google->getAccessToken();
            $tokens_decoded = json_decode(json_encode($token), false);
            $refreshToken = $tokens_decoded->refresh_token;
            $this->setAccessToken($token["access_token"]);
            $this->setRefreshToken($refreshToken);
        }
        else{
            return false;
        }
    }

    public function authenticate($possessedDevice){
        $this->google->setAccessToken($possessedDevice->getAccessTokenGoogle());
        $this->service = new Google_Service_Fitness($this->google);
       /* $idtoken = $this->google->verifyIdToken();
        var_dump($idtoken);*/
       // $code = $this->google->getOAuth2Service()->getCode();
        //var_dump($code);
      /*  if ($this->google->isAccessTokenExpired()){
            $this->google->refreshToken($possessedDevice->getRefreshTokenGoogle());
            $token = $this->google->getAccessToken();
            $this->setAccessToken($token["access_token"]);
            $this->setRefreshToken($token["refresh_token"]);
            $possessedDevice->setAccessTokenGoogle($this->getAccessToken());
            $possessedDevice->setRefreshTokenGoogle($this->getRefreshToken());
            return true;
        }
        return false;*/
    }

    public function getActivities()//($userid, $startdate, $enddate = null)
    {
       return $this->service->users_dataSources->listUsersDataSources("me");
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






