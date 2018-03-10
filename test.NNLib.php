<?php
require_once("lib/class.NNLib.php");

$nnlib = new NNLib();

$data = array(
	"birth_date" => "1990-01-01",
	"user_id" => "<YOUR_USERNAME>",
	"password" => "<YOUR_PASSWORD>",
	"country" => "US",
	"language" => "en",
	"tz_name" => "America/New_York",
	"agreement" => array(
		"agreement_date" => "1990-01-01T01:01:01",
		"country" => "US",
		"location" => "https://account.nintendo.net/v1/api/people/content/agreements/Nintendo-Network-EULA/0300",
		"type" => "NINTENDO-NETWORK-EULA",
		"version" => "0300"
	),
	"email" => array(
		"address" => "<YOUR_EMAIL>"
	),
	"mii" => array(
		"name" => "TestMii",
		"data" => "AwAAQEuX4+nGhzGy04Y5PxShgom0rwAAIkBwAHIAbwBkAHQAZQBzAHQAMQAAAEBAAAAhAQJoRBgmNEYUgRIXaA0AACkAUkhQSQBhAG4AAAAAAAAAAAAAAAAAAAAAAN2k"
	),
	"parental_consent" => array(
		"consent_date" => "1990-01-01T01:01:01"
	),
	"gender" => "M",
	"region" => 0
);

$res = createNNID($data, $nnlib);
switch ($res) {
	case "username":
	echo "Username is invalid.";
	break;
	case "email":
	echo "Email is invalid.";
	break;
	default:
	echo "Success! NNID was created successfully! Account PID: " . $res;
	break;
}

function createNNID($data, $nnlib) {
	if (!$nnlib->validateUsername($data["user_id"])) {
		return "username";
	}

	if (!$nnlib->validateEmail($data["email"]["address"])) {
		return "email";
	}

	$response = $nnlib->createAccount($data);
	if ($response) {
		return $response;
	} else {
		return "account";
	}
}