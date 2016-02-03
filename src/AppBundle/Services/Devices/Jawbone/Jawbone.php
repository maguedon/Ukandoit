<?php

namespace AppBundle\Services\Devices\Jawbone;

class Jawbone{

	private $client_id;
	private $client_secret;
	private $redirect_uri;
	private $scopes;
	private $scope;

	function __construct($client_id, $client_secret, $scopes, $router){
		$this->client_id      = $client_id;
		$this->client_secret  = $client_secret;
		$this->redirect_uri   = (empty($_SERVER['HTTPS'])?'http':'https') . "://" . $_SERVER['HTTP_HOST'] . $router->generate('jawbone_token');

		$this->scopes = $scopes;

		$this->scope = implode(' ', $this->scopes);
	}

	/** 
	 * Connect to the Jawbone account of the user 
	 * and ask permissions to get his information
	 */
	function connection(){
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

		$start_time = new \DateTime("2016-01-23 13:00:00");
		$end_time = new \DateTime("2016-01-23 17:00:00");

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

	/**
	 * Get moves
	 */
	function getTotalMoves($access_token, $start_time, $end_time){
		$url = "https://jawbone.com/nudge/api/v.1.0/users/@me/moves?";

		$opts = array(
			'http'=>array(
				'method'=>"GET",
				'header'=>"Authorization: Bearer {$access_token}\r\n"
			)
		);

		$start_time = strtotime($start_time);
		$end_time = strtotime($end_time);

		$param = array(
			'start_time' => $start_time->getTimestamp(),
			'end_time' => $end_time->getTimestamp()
		);

		$url = $url . http_build_query($param);

		$context = stream_context_create($opts);

		$response = file_get_contents($url, false, $context);

		$moves = json_decode($response, true);

		return $moves['data'];
	}

	/**
	* Get moves
	*/
	function getMoves($access_token){
		$url = "https://jawbone.com/nudge/api/v.1.0/users/@me/moves?";

		$opts = array(
			'http'=>array(
				'method'=>"GET",
				'header'=>"Authorization: Bearer {$access_token}\r\n"
				)
			);

		$start_time = new \DateTime("2016-01-23 00:00:00");
		$end_time = new \DateTime("2016-01-23 23:59:59");

		$param = array(
			'start_time' => $start_time->getTimestamp(),
			'end_time' => $end_time->getTimestamp()
			);

		$url = $url . http_build_query($param);

		$context = stream_context_create($opts);

		$response = file_get_contents($url, false, $context);

		$moves = json_decode($response, true);
		return $moves['data'];
	}
}
