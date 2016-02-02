<?php
namespace AppBundle\Services\Devices\Withings;

class Withings
{
    private $consumer_key;
    private $consumer_secret;
    private $callback_url;
    private $withings;
    private $token;
    private $adapter;
    private $user;

    function __construct($consumer_key = null, $consumer_secret = null, $callback_url = null, $access_token_key = null, $access_token_secret = null, $user_id = null)
    {
        $this->consumer_key = "31ec099bddfee6fb7b16d5bc502e8b5bcbbddf0de39346316427f8f25fd";
        $this->consumer_secret = "0d50d732c6655ee07d727ffbc336751d8b3b7c298e32167d945589692e5e9b16";
        $this->callback_url = "http://localhost:8000/withings";
        $this->withings = new \Withings\ApiGatewayFactory;
        $this->access_token_key = $access_token_key;
        $this->access_token_secret = $access_token_secret;
        $this->user_id = $user_id;
        $this->token = new \OAuth\OAuth1\Token\StdOAuth1Token();
        $this->adapter = new \OAuth\Common\Storage\Session();
        $this->user = new \Withings\UserGateway;

    }

    public function setConsumerKey($consumer_key)
    {
        $this->consumer_key = $consumer_key;
    }

    public function setConsumerSecret($consumer_secret)
    {
       $this->consumer_secre = $consumer_secret;
    }

    public function setCallbackUrl($callback_url)
    {
        $this->callback_url = $callback_url;
    }

    public function setWithings($Withings)
    {
        $this->withings = $Withings;
    }

    public function setAccessTokenKey($access_token_key)
    {
        $this->access_token_key = $access_token_key;
    }

    public function setAccessTokenSecret($access_token_secret)
    {
        $this->access_token_secret = $access_token_secret;
    }

    public function setUserID($userid)
    {
        $this->user_id = $userid;
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

    public function getWithings()
    {
        return $this->withings;
    }

    public function getAccessTokenKey()
    {
        return $this->access_token_key;
    }

    public function getAccessTokenSecret()
    {
        return $this->access_token_secret;
    }

    public function getUserID()
    {
        return $this->user_id;
    }


    public function connection(){

        $this->withings->setCallbackURL( $this->callback_url );
        $this->withings->setCredentials( $this->consumer_key, $this->consumer_secret ); // these variables come from database
        $this->withings->setStorageAdapter($this->adapter);

        $auth_gateway = $this->withings->getAuthenticationGateway();

        if (isset($_GET['oauth_token']) && isset($_GET['oauth_verifier'])) {
            $auth_gateway->authenticateUser($_GET['oauth_token'], $_GET['oauth_verifier']);

            $storage = $this->withings->getStorageAdapter();
            $token   = $storage->retrieveAccessToken('Withings');

            $this->setAccessTokenKey($token->getRequestToken()); // Your user entity must have a WithingsToken column
            $this->setAccessTokenSecret($token->getRequestTokenSecret()); // Your user entity must have a WithingsTokenSecret column
            $this->setUserID($_GET['userid']); // Your user entity must have a WithingsUserId column

        }else
        {
            $auth_gateway->initiateLogin();
        }
    }

    public function authenticate($possessedDevice){
        $this->withings->setCallbackURL( $this->callback_url);
        $this->withings->setCredentials( $this->consumer_key , $this->consumer_secret ); // these variables come from database
        $this->withings->setUserID($possessedDevice->getUserIdWithings());

        $this->access_token_key = $possessedDevice->getAccessTokenKeyWithings();
        $this->access_token_secret = $possessedDevice->getAccessTokenSecretWithings();
        $this->user_id = $possessedDevice->getUserIdWithings();
        $this->withings->setStorageAdapter($this->adapter);

        $this->token->setRequestToken($possessedDevice->getAccessTokenKeyWithings()); // user credentials
        $this->token->setRequestTokenSecret($possessedDevice->getAccessTokenSecretWithings()); // user credentials
        $this->token->setAccessToken($possessedDevice->getAccessTokenKeyWithings()); // Your user entity must have a WithingsToken column
        $this->token->setAccessTokenSecret($possessedDevice->getAccessTokenSecretWithings()); // Your user entity must have a WithingsTokenSecret column
        $adapter = new \OAuth\Common\Storage\Memory();
        $adapter->storeAccessToken('Withings', $this->token);
        $this->withings->setStorageAdapter($adapter);
    }

    public function getActivities($userid, $startdate, $enddate = null)
    {
        return $this->withings->getUserGateway()->getActivities($userid, $startdate, $enddate);
    }

    public function getIntradayActivities($userid, $startdate, $enddate)
    {
        return $this->withings->getUserGateway()->getIntradayActivities($userid, $startdate, $enddate);
    }

}






