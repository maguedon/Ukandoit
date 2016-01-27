<?php

namespace AppBundle\Services\Devices\Jawbone;

class Jawbone{

	private $client_id;
	private $client_secret;
	private $redirect_uri;
	private $scopes;
	private $scope;

	function __construct($client_id, $client_secret, $redirect_uri){
		$this->client_id      = $client_id;
		$this->client_secret  = $client_secret;
		$this->redirect_uri   = $redirect_uri;

  app.jawbone.scopes: ["basic_read", "extended_read", "location_read", "friends_read", "mood_read", "mood_write", "move_read", "move_write", "sleep_read";
        $this->scopes[] = "sleep_write";
        $this->scopes[] = "meal_read";
        $this->scopes[] = "meal_write";
        $this->scopes[] = "weight_read";
        $this->scopes[] = "weight_write";
        $this->scopes[] = "cardiac_read";
        $this->scopes[] = "cardiac_write";
        $this->scopes[] = "generic_event_read";
        $this->scopes[] = "generic_event_write";

		$this->scope = implode(' ', $this->scopes);
	}

	/** 
	 * Connect to the Jawbone account of the user 
	 * and ask permissions to get his information
	 */
	function connexion(){
		echo "connexion";
		$param = array(
			'response_type' => 'code',
			'client_id' => $this->client_id,
			'scope' => $this->scope,
			'redirect_uri' => $this->redirect_uri // rediriger vers l'action qui appellera getToken
			);

		$url = "https://jawbone.com/auth/oauth2/auth?" . http_build_query($param);

		return $url;
	}

	/**
	 * Get and save user token
	 */
	function getToken(){
		$error = false;
		if(isset($_GET['error']) || !isset($_GET['code']) || empty($_GET['code'])){
			$error = true;
		}else{

			$param = array(
				'grant_type' => 'authorization_code',
				'client_id' => $this->client_id,
				'client_secret' => $this->client_secret,
				'code' => $_GET['code']
				);

			$url = "https://jawbone.com/auth/oauth2/token?" . http_build_query($param);
			$body = file_get_contents($url);
			$json = json_decode($body, true);

			return $json;
		}
	}

	/**
	 * Get basic information about the user
	 * @see https://jawbone.com/up/developer/endpoints/user
	 */
	function getUser($access_token){
		$url = "https://jawbone.com/nudge/api/v.1.0/users/@me";

		$opts = array(
			'http'=>array(
				'method'=>"GET",
				'header'=>"Authorization: Bearer {$access_token}\r\n"
				)
			);

		$context = stream_context_create($opts);

		$response = file_get_contents($url, false, $context);
		$user = json_decode($response, true);
		return $user['data'];
	}

	/**
	* Get activities
	*/
	function getSpecialActivities($access_token){
		$url = "https://jawbone.com/nudge/api/v.1.0/users/@me/workouts?";

		$opts = array(
			'http'=>array(
				'method'=>"GET",
				'header'=>"Authorization: Bearer {$access_token}\r\n"
				)
			);

		$start_time = new DateTime("2016-01-23 13:00:00");
		$end_time = new DateTime("2016-01-23 17:00:00");

		$param = array(
			'start_time' => $start_time->getTimestamp(),
			'end_time' => $end_time->getTimestamp()
			);

		$url = $url . http_build_query($param);

		$context = stream_context_create($opts);

		$response = file_get_contents($url, false, $context);
		$activities = json_decode($response, true);
		return $activities['data'];
	}
}