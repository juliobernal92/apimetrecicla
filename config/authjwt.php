<?php
require '../vendor/autoload.php';

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Definir constantes
define('KEY', '123');
define('ISS', '');
define('AUD', '');

function encode($data, $expTimeInSeconds = 8 * 60 * 60) {
    $time = time();
    $payload = array(
        "iss" => ISS,
        "aud" => AUD,
        "iat" => $time,
        "nbf" => $time,
        "exp" => $time + $expTimeInSeconds,
        "data" => $data
    );

    $jwt = JWT::encode($payload, KEY, 'HS256');
    return $jwt;
}

function decode($jwt) {
    try {
        $decoded = JWT::decode($jwt, new Key(KEY, 'HS256'));
        return (array) $decoded->data;
    } catch (\Firebase\JWT\ExpiredException $e) {
        throw new Exception("Token ha expirado");
    } catch (Exception $e) {
        throw new Exception("Token inválido");
    }
}
