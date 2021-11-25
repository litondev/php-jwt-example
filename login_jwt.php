<?php
date_default_timezone_set('Asia/Jakarta');

include "vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$firebaseJwt = new JWT();
$firebaseJwt::$leeway += 60;

function generateRandomString($length = 10) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}

// HARUS SAMA DENGAN DI LARAVEL
$key = "YnZpOVGePxC6NtMtkBeQ2TOSFE5RUUpLsDXyB3UsZMAkmshHe2aq1w75gSBuFHwD";

// HARUS SAMA DENGAN DI LARAVEL CLAIMS
$payload = array(
    "iss" => null,
    "iat" => rand(100000000,999999999),
    "exp" => time() + (60 * 60), 
    "nbf" => rand(100000000,999999999),
    "sub" => "joko@gmail.com",
    "jti" => generateRandomString(16)
);

$jwt = $firebaseJwt::encode($payload, $key);

echo $jwt;

setCookie("auth._token.local","Bearer ".$jwt);