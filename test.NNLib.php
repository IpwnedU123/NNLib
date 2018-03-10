<?php
require_once("lib/class.NNLib.php");

$nnlib = new NNLib();
if ($nnlib->validateUsername("thisisatest")) {
	echo "This username is valid.<br>";
} else {
	echo "This username is invalid!<br>";
}

if ($nnlib->validateEmail("thisisatest@gmail.com")) {
	echo "This email is valid.<br>";
} else {
	echo "This email is invalid!<br>";
}