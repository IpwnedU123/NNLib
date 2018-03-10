<?php
/*
 * NNLib
 * by Cyuubi
 */

class NNLib {
    private $config;

    function __construct() {
        include(__DIR__ . "/config.NNLib.php");
        $this->config = $config;
    }

	function validateUsername($username) {
    	$ch = curl_init();

    	curl_setopt_array($ch, array(
    		CURLOPT_SSL_VERIFYPEER => 0,
    	    CURLOPT_RETURNTRANSFER => true,
    	    CURLOPT_SSLCERT => $this->config["sslCert"],
    	    CURLOPT_HTTPHEADER => array(
    	        "X-Nintendo-Client-ID: " . $this->config["clientId"],
    	        "X-Nintendo-Client-Secret: " . $this->config["clientSecret"]
        	)
    	));

    	curl_setopt($ch, CURLOPT_URL, $this->config["apiEndpoint"] . "people/" . $username);

    	return empty(curl_exec($ch));
	}

	function validateEmail($email) {
    	$ch = curl_init();

    	curl_setopt_array($ch, array(
    		CURLOPT_SSL_VERIFYPEER => 0,
    	    CURLOPT_RETURNTRANSFER => true,
    	    CURLOPT_SSLCERT => $this->config["sslCert"],
    	    CURLOPT_POST => 1,
    	    CURLOPT_POSTFIELDS => "email=" . $email,
    	    CURLOPT_HTTPHEADER => array(
    	    	"Content-Type: application/x-www-form-urlencoded",
    	        "X-Nintendo-Client-ID: " . $this->config["clientId"],
    	        "X-Nintendo-Client-Secret: " . $this->config["clientSecret"]
        	)
    	));

    	curl_setopt($ch, CURLOPT_URL, $this->config["apiEndpoint"] . "support/validate/email");

    	return empty(curl_exec($ch));
	}
}