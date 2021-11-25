<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://localhost:8000/api/v1/signin");
curl_setopt($ch, CURLOPT_POST, 1);
// curl_setopt($ch, CURLOPT_POSTFIELDS,"email=joko@gmail.com&password=password");
curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query
    (array('email' => 'joko@gmail.com','password' => 'password')
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$server_output = curl_exec($ch);
curl_close ($ch);
$token = json_decode($server_output)->access_token;
setCookie("auth._token.local","Bearer ".$token);
echo "SUCCESS SET TOKEN";
?>