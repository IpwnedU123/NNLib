<?php
/*
 * NNLib
 * by Cyuubi
 */

class NNLib {
    // NNLib config
    private $config;

    // NNLib constructor
    function __construct() {
        // Include config
        include(__DIR__ . "/config.NNLib.php");

        // Set config data
        $this->config = $config;
    }

    /*
     * validateUsername([USERNAME(STRING)]);
     * Description: Validates a username
     * Return: [true|false]
     */
	function validateUsername($username) {
        // Init cURL
    	$ch = curl_init();

        // Set cURL Options
    	curl_setopt_array($ch, array(
    		CURLOPT_SSL_VERIFYPEER => 0,
    	    CURLOPT_RETURNTRANSFER => true,
    	    CURLOPT_SSLCERT => $this->config["sslCert"],
    	    CURLOPT_HTTPHEADER => array(
    	        "X-Nintendo-Client-ID: " . $this->config["clientId"],
    	        "X-Nintendo-Client-Secret: " . $this->config["clientSecret"]
        	)
    	));

        // Set cURL request URL
    	curl_setopt($ch, CURLOPT_URL, $this->config["apiEndpoint"] . "people/" . $username);

        // Check if empty and execute
    	return empty(curl_exec($ch));
	}

    /*
     * validateEmail([EMAIL(STRING)]);
     * Description: Validates a email
     * Return: [true|false]
     */
	function validateEmail($email) {
        // Init cURL
    	$ch = curl_init();

        // Set cURL Options
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

        // Set cURL request URL
    	curl_setopt($ch, CURLOPT_URL, $this->config["apiEndpoint"] . "support/validate/email");

        // Check if empty and execute
    	return empty(curl_exec($ch));
	}

    /*
     * createAccount([DATA(ARRAY)]);
     * Description: Creates an account
     * Return: [Account PID|false]
     */
    function createAccount($data) {
        $xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><person></person>");

        // Main data
        $xml->addChild("birth_date", $data["birth_date"]);
        $xml->addChild("user_id", $data["user_id"]);
        $xml->addChild("password", $data["password"]);
        $xml->addChild("country", $data["country"]);
        $xml->addChild("language", $data["language"]);
        $xml->addChild("tz_name", $data["tz_name"]);

        // Agreement
        $agreement = $xml->addChild("agreement");
        $agreement->addChild("agreement_date", $data["agreement"]["agreement_date"]);
        $agreement->addChild("country", $data["agreement"]["country"]);
        $agreement->addChild("location", $data["agreement"]["location"]);
        $agreement->addChild("type", $data["agreement"]["type"]);
        $agreement->addChild("version", $data["agreement"]["version"]);

        // Email
        $email = $xml->addChild("email");
        $email->addChild("address", $data["email"]["address"]);
        $email->addChild("owned", "N");
        $email->addChild("parent", "N");
        $email->addChild("primary", "Y");
        $email->addChild("type", "DEFAULT");
        $email->addChild("validated", "N");

        // Mii
        $mii = $xml->addChild("mii");
        $mii->addChild("name", $data["mii"]["name"]);
        $mii->addChild("primary", "Y");
        $mii->addChild("data", $data["mii"]["data"]);

        // Parental consent
        // This isn't really implemented yet.
        $parental_consent = $xml->addChild("parental_consent");
        $parental_consent->addChild("scope", 0);
        $parental_consent->addChild("consent_date", $data["parental_consent"]["consent_date"]);
        $parental_consent->addChild("approval_id", 0);

        // Main data
        $xml->addChild("gender", $data["gender"]);
        $xml->addChild("region", $data["region"]);
        $xml->addChild("marketing_flag", "Y");
        $xml->addChild("off_device_flag", "Y");

        // Init cURL
        $ch = curl_init();

        // Set cURL Options
        curl_setopt_array($ch, array(
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSLCERT => $this->config["sslCert"],
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $xml->asXML(),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/xml",
                "X-Nintendo-Platform-ID: " . $this->config["device"]["platformId"],
                "X-Nintendo-Device-Type: " . $this->config["device"]["deviceType"],
                "X-Nintendo-Device-ID: " . $this->config["device"]["deviceId"],
                "X-Nintendo-Serial-Number: " . $this->config["device"]["serialNumber"],
                "X-Nintendo-System-Version: " . $this->config["device"]["systemVersion"],
                "X-Nintendo-Region: " . $this->config["device"]["region"],
                "X-Nintendo-Country: " . $this->config["device"]["country"],
                "X-Nintendo-Environment: " . $this->config["device"]["environment"],
                "X-Nintendo-Device-Cert: " . $this->config["device"]["deviceCert"],
                "X-Nintendo-Client-ID: " . $this->config["clientId"],
                "X-Nintendo-Client-Secret: " . $this->config["clientSecret"]
            )
        ));

        // Set cURL request URL
        curl_setopt($ch, CURLOPT_URL, $this->config["apiEndpoint"] . "people/");

        // Execute and convert to XML element
        $person = new SimpleXMLElement(curl_exec($ch));

        // Check if PID doesn't exist in data
        if (!$person->pid) {
            return false;
        }

        // Else return PID
        return $person->pid;
    }
}