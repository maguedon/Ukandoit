<?php
namespace AppBundle\Services\Devices\Withings;

class Withings
{
    private $consumer_key;
    private $consumer_secret;
    private $callback_url;
    private $access_token_key;
    private $access_token_secret;
    private $user_id;
    private $token;



    function __construct($consumer_key = null, $consumer_secret = null, $callback_url = null, $access_token_key = null, $access_token_secret = null, $user_id = null)
    {
        $this->consumer_key = $consumer_key;
        $this->consumer_secret = $consumer_secret;
        $this->callback_url = $callback_url;
        $this->access_token_key = $access_token_key;
        $this->access_token_secret = $access_token_secret;
        $this->user_id = $user_id;
        $this->token = new \OAuth\OAuth1\Token\StdOAuth1Token();
    }

    public function connection()
    {
        $factory = new \Withings\ApiGatewayFactory;
        $adapter = new \OAuth\Common\Storage\Session();
        $factory->setStorageAdapter($adapter);

        if ($this->access_token_key == null && $this->access_token_secret == null && $this->user_id == null) {
            $factory->setCallbackURL($this->callback_url);
            $factory->setCredentials($this->consumer_key, $this->consumer_secret);

/*            $adapter    = new \OAuth\Common\Storage\Session();
            $factory->setStorageAdapter($adapter);*/

            $auth_gateway = $factory->getAuthenticationGateway();
            return $auth_gateway;
        }
    }

    private function getToken(){
         $factory = new \Withings\ApiGatewayFactory;
//         $adapter    = new \OAuth\Common\Storage\Session();
//         $factory->setStorageAdapter($adapter);
            if (isset($_GET['oauth_token']) && isset($_GET['oauth_verifier'])) {
               // $auth_gateway->authenticateUser($_GET['oauth_token'], $_GET['oauth_verifier']);

                $storage = $factory->getStorageAdapter();
                $this->token = $storage->retrieveAccessToken('Withings');

                $this->access_token_key = $this->token->getRequestToken(); // Your user entity must have a WithingsToken column
                $this->access_token_secret = $this->token->getRequestTokenSecret(); // Your user entity must have a WithingsTokenSecret column
                $this->user_id = $_GET['user_id']; // Your user entity must have a WithingsUserId column
            } else {
               // $auth_gateway->initiateLogin();
            }
    }

    public function withingsGetProfile()
    {
        $factory = new \Withings\ApiGatewayFactory;
        $factory->setCallbackURL( $this->callback_url);
        $factory->setCredentials( $this->consumer_key , $this->consumer_secret ); // these variables come from database

        $this->token->setAccessToken($this->access_token_key); // user credentials;
        $this->token->setAccessTokenSecret($this->access_token_secret); // user credentials

        $auth_gateway = $factory->getAuthenticationGateway();
        $auth_gateway->authenticateUser($this->access_token_key, $this->access_token_secret);

        $adapter = new \OAuth\Common\Storage\Memory();
        $adapter->storeAccessToken('Withings', $this->token);

        $factory->setStorageAdapter($adapter);

        $UserGateway        =   $factory->getUserGateway();
        $profile            =   $UserGateway->getProfile( $this->user_id ); // user withings id

        return $profile;
    }


}






